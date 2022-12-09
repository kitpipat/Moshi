<?php
set_time_limit(0);
ini_set('memory_limit', '8000M'); // -1 unlimit memory
// ini_set('max_execution_time', 3600 * 120); //3600 seconds =  minutes
ini_set('max_execution_time', 0); //3600 seconds =  minutes
ini_set('sqlsrv.ClientBufferMaxKBSize', '524288'); // Setting to 512M
ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '524288'); // Setting to 512M - for pdo_sqlsrv
ini_set("pcre.backtrack_limit", "1000000000000");

require_once(APPPATH . 'libraries/rabbitmq/vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class cMQExportReportTools extends MX_Controller {
    
    private $aMQConfig = [];
    private $aQname = [];
    private $aParams = [];
    private $tAppVersion = '';
    
    public function __construct() {
        $this->load->helper('report');
        $this->load->library('zip');
        $this->load->model('mCompany');
        $this->load->model('mHisReport');
        parent::__construct();
        $this->FSxInit();
    }
    
    // ค่าเริ่มต้นการทำงาน
    public function FSxInit(){
        $this->aMQConfig = [
            'tHost' => $this->config->item('mq_host'),
            'tUsername' => $this->config->item('mq_username'),
            'tPassword' => $this->config->item('mq_password'),
            'tPort' => $this->config->item('mq_port'),
            'tVHost' => $this->config->item('mq_vhost')
        ];
        $this->aQname = $this->config->item('report_name');
        $this->tAppVersion = $this->config->item('app_version');
    }

    // Consomer รอรับข้อความ เพื่อประมวลผล
    public function FSxMQConsumer($paParams = []) {
        echo "Connect : ".$this->aMQConfig['tHost'].", ".$this->aMQConfig['tPort']." ".$this->aMQConfig['tUsername']." ".$this->aMQConfig['tPassword']." ".$this->aMQConfig['tVHost'], "\n";
        echo "Version : ".$this->tAppVersion, "\n";
        echo "=====================================================================================", "\n\n";
        
                
        $host = $this->aMQConfig['tHost'];
        $port = $this->aMQConfig['tPort'];
        $user = $this->aMQConfig['tUsername'];
        $password = $this->aMQConfig['tPassword'];
        $vhost = $this->aMQConfig['tVHost'];
        $insist = false;
        $login_method = 'AMQPLAIN';
        $login_response = null;
        $locale = 'en_US';
        $connection_timeout = 3.0;
        $read_write_timeout = 130.0;
        $context = null;
        $keepalive = true;
        $heartbeat = 60;
        
        $oConnection = new AMQPStreamConnection(
            $host, $port, $user, $password, $vhost, $insist, $login_method, 
            $login_response, $locale, $connection_timeout, $read_write_timeout, 
            $context, $keepalive, $heartbeat
        );
        
        $oChannel = $oConnection->channel();
        
        echo " [*] Waiting for messages. To exit press CTRL+C\n\n\n";
        
        foreach ($this->aQname as $tQname) {
            $oChannel->queue_declare($tQname, false, false, false, false);
        }
        
        foreach ($this->aQname as $tQname) {
            $oChannel->basic_qos(null, 1, null);
            $oChannel->basic_consume($tQname, '', false, true, false, false, [$this, 'FSxCallback']);
        }
        
        while (count($oChannel->callbacks)) {
            $oChannel->wait();
        }

        $oChannel->close();
        $oConnection->close();
        
    }
    
    // ส่งข้อความสถานะการทำงาน ให้กับผู้เรียก
    public function FSxMQPublish($paParams = []) {
        $tMsgFormat = $paParams['tMsgFormat'];
        $tQueueName = $paParams['tQname'];

        $oConnection = new AMQPStreamConnection(
            $this->aMQConfig['tHost'], 
            $this->aMQConfig['tPort'], 
            $this->aMQConfig['tUsername'], 
            $this->aMQConfig['tPassword'], 
            $this->aMQConfig['tVHost']
        );
        
        $oChannel = $oConnection->channel();
        $oChannel->queue_declare($tQueueName, false, true, false, false);
        
        $tMsg = '';
        switch ($tMsgFormat) {
            case 'json' : {
                $tMsg = json_encode($paParams['tMsg']);
                break;
            }
            case 'text' : {
                $tMsg = $paParams['tMsg'];
                break;
            }
            default : {
                $tMsg = $paParams['tMsg'];
            }
        }
        
        $oMessage = new AMQPMessage($tMsg);
        $oChannel->basic_publish($oMessage, "", $tQueueName);
        $oChannel->close();
        $oConnection->close();

        unset($tMsgFormat);
        unset($tQueueName);
        unset($oConnection);
        unset($oChannel);
        unset($tMsg);
        unset($oMessage);

        echo ' [/] Send Progress Success' , "\n";
    }
    
    // ประมวลผล
    public function FSaCProcess($paParams = []) {
        /*===== Begin Init Variable ====================================================*/
        $oMsg = $paParams['oMsg'];
        $tRptName = $this->aParams['tRptCode'];
        // วันที่ออกรายงาน
        $tCreateDate = date('Y-m-d H:i:s', strtotime($this->aParams['tDate']. ' ' . $this->aParams['tTime']));
        // วันที่หมดอายุ ระยะเวลา 1 ปี
        $tExprDate = date('Y-m-d H:i:s', strtotime("+12 months " . $this->aParams['tDate']. ' ' . $this->aParams['tTime']));
        $tPatternName = $this->aParams['tRptCode'].'_'.$this->aParams['tUserCode'].'_'.$this->aParams['tUserSessionID'].'_'.str_replace('-', '', $this->aParams['tDate']).'_'.str_replace(':', '', $this->aParams['tTime']);
        $tPublishName = "RESRPT_".$tPatternName;
        /*===== End Init Variable ======================================================*/
        
        echo " [*] Begin Process" , "\n";
        
        /*===== Begin Resource Report ==================================================*/
        $aExportParams = [
            'tBchCodeLogin' => $this->aParams['tBchCode'],
            'tCompName' => $this->aParams['tComName'],
            'tRptCode' => $this->aParams['tRptCode'],
            'tUserSessionID' => $this->aParams['tUserSessionID'],
            'tRptExpType' => $this->aParams['tRptExpType'],
            'nPerFile' => $this->aParams['nPerFile'],
            'tUserCode' => $this->aParams['tUserCode'],
            'aFilter' => $this->aParams['tFilter'],
            'nLngID' => $this->aParams['nLngID'],
        ];
        switch ($tRptName) {
            /*===== การขาย POS =============================================================*/
            case '001001001':{ // รายงานยอดขายตามบิล (Pos)
                require_once REPORTPATH . 'controllers/reportsale/cRptSaleByBill.php';
                $oRptExport = new cRptSaleByBill($aExportParams);
        
                $this->load->model('report/reportsale/mRptSaleByBill');
                $oModel = $this->mRptSaleByBill;
                break;
            }
            case '001001002':{ // รายงานยอดขายตามสินค้า (Pos)
                require_once REPORTPATH . 'controllers/reportsale/cRptSaleByProduct.php';
                $oRptExport = new cRptSaleByProduct($aExportParams);
        
                $this->load->model('report/reportsale/mRptSaleByProduct');
                $oModel = $this->mRptSaleByProduct;
                break;
            }
            case '001001003':{ // รายงานยอดขายตามการชำระเงินแบบสรุป (Pos)
                require_once REPORTPATH . 'controllers/reportsale/cRptSalePayment.php';
                $oRptExport = new cRptSalePayment($aExportParams);
        
                $this->load->model('report/reportsale/mRptSalePayment');
                $oModel = $this->mRptSalePayment;
                break;
            }
            case '001001004':{ // รายงานสินค้าขายดีตามจำนวน(Pos)
                require_once REPORTPATH . 'controllers/reportsale/cRptBestSell.php';
                $oRptExport = new cRptBestSell($aExportParams);
        
                $this->load->model('report/reportsale/mRptBestSell');
                $oModel = $this->mRptBestSell;
                break;
            }
            case '001001005':{ // รายงานยอดขายตามการชำระเงินแบบละเอียด(Pos)
                require_once REPORTPATH . 'controllers/reportsale/cRptSaleRecive.php';
                $oRptExport = new cRptSaleRecive($aExportParams);
        
                $this->load->model('report/reportsale/mRptSaleRecive');
                $oModel = $this->mRptSaleRecive;
                break;
            }
            case '001001006':{ // รายงานภาษีขาย (Pos)
                require_once REPORTPATH . 'controllers/reportsale/cRptTaxSalePos.php';
                $oRptExport = new cRptTaxSalePos($aExportParams);
        
                $this->load->model('report/reportsale/mRptTaxSalePos');
                $oModel = $this->mRptTaxSalePos;
                break;
            }
            case '001001007':{ // รายงานภาษีขายตามวันที่ (Pos)
                require_once REPORTPATH . 'controllers/reportsale/cRptTaxSalePosByDate.php';
                $oRptExport = new cRptTaxSalePosByDate($aExportParams);
        
                $this->load->model('report/reportsale/mRptTaxSalePosByDate');
                $oModel = $this->mRptTaxSalePosByDate;
                break;
            }
            case '001001008':{ // รายงานวิเคราะห์กำไรขาดทุนตามสินค้า (Pos)
                require_once REPORTPATH . 'controllers/reportsale/cRptAnalysisProfitLossProductPos.php';
                $oRptExport = new cRptAnalysisProfitLossProductPos($aExportParams);
        
                $this->load->model('report/reportsale/mRptAnalysisProfitLossProductPos');
                $oModel = $this->mRptAnalysisProfitLossProductPos;
                break;
            }

            /*===== คลังสินค้า POS ============================================================*/
            case '001002001':{ // รายงานสินค้าคงคลัง (Pos)
                require_once REPORTPATH . 'controllers/reportsale/cRptInventoryPos.php';
                $oRptExport = new cRptInventoryPos($aExportParams);
        
                $this->load->model('report/reportsale/mRptInventoryPos');
                $oModel = $this->mRptInventoryPos;
                break;
            }
            case '001002002':{ // รายงานความเคลื่อนไหวของสินค้า (Pos) Pos5 ยังไม่เสร็จ
                require_once REPORTPATH . 'controllers/reportsale/cRptMovePosVd.php';
                $oRptExport = new cRptMovePosVd($aExportParams);
        
                $this->load->model('report/reportsale/mRptMovePosVd');
                $oModel = $this->mRptMovePosVd;
                break;
            }
        
            /*===== รายงานการขาย ตู้ขายของ Vending ============================================*/
            case '002001001':{ // รายงานภาษีขายตามกลุ่มร้านค้า  (Vending)
                require_once REPORTPATH . 'controllers/reportvending/cRptsaleshopgroup.php';
                $oRptExport = new cRptsaleshopgroup($aExportParams);
        
                $this->load->model('report/reportvending/mRptsaleshopgroup');
                $oModel = $this->mRptsaleshopgroup;
                break;
            }
            case '002001002':{ // รายงานยอดขายตามการชำระเงินแบบสรุป (Vending)
                require_once REPORTPATH . 'controllers/reportvending/cRptSalePaymentSummary.php';
                $oRptExport = new cRptSalePaymentSummary($aExportParams);
        
                $this->load->model('report/reportvending/mRptSalePaymentSummary');
                $oModel = $this->mRptSalePaymentSummary;
                break;
            }
            case '002001003':{ // รายงานยอดขายตามการชำระเงินแบบละเอียด (Vending)
                require_once REPORTPATH . 'controllers/reportvending/cRptVDSaleRecive.php';
                $oRptExport = new cRptVDSaleRecive($aExportParams);
        
                $this->load->model('report/reportvending/mRptSaleRecive');
                $oModel = $this->mRptSaleRecive;
                break;
            }
            case '002001004':{ // รายงานสินค้าขายดี (Vending)
                require_once REPORTPATH . 'controllers/reportvending/cRptBestSaleVending.php';
                $oRptExport = new cRptBestSaleVending($aExportParams);
        
                $this->load->model('report/reportvending/mRptBestSaleVending');
                $oModel = $this->mRptBestSaleVending;
                break;
            }
            case '002001005':{ // รายงานวิเคราะห์กำไรขาดทุนตามสินค้า (Vending)
                require_once REPORTPATH . 'controllers/reportvending/cRptAnalysisProfitLossProductVending.php';
                $oRptExport = new cRptAnalysisProfitLossProductVending($aExportParams);

                $this->load->model('report/reportvending/mRptAnalysisProfitLossProductVending');
                $oModel = $this->mRptAnalysisProfitLossProductVending;
                break;
            }
            case '002001006':{ // รายงานยอดขายตามบิล (Vending)
                require_once REPORTPATH . 'controllers/reportvending/cRptSalesbybill.php';
                $oRptExport = new cRptSalesbybill($aExportParams);
        
                $this->load->model('report/reportvending/mRptSalesbybill');
                $oModel = $this->mRptSalesbybill;
                break;
            }
            case '002001007':{ // รายงานยอดขายตามสินค้า (Vending)
                require_once REPORTPATH . 'controllers/reportvending/cRptSaleByProductVD.php';
                $oRptExport = new cRptSaleByProductVD($aExportParams);

                $this->load->model('report/reportvending/mRptSaleByProductVD');
                $oModel = $this->mRptSaleByProductVD;
                break;
            } 

            /*===== รายงานสินค้าคงคลัง ตู้ขายของ Vending ========================================*/
            case '002002001':{ // รายงานสินค้าคงคลัง (Vedding)
                require_once REPORTPATH . 'controllers/reportvending/cRptInventory.php';
                $oRptExport = new cRptInventory($aExportParams);
        
                $this->load->model('report/reportvending/mRptInventory');
                $oModel = $this->mRptInventory;
                break;
            }
            case '002002002':{ // รายงานการตรวจนับสต็อก (Vending)
                require_once REPORTPATH . 'controllers/reportvending/cRptAdjStockVending.php';
                $oRptExport = new cRptAdjStockVending($aExportParams);
        
                $this->load->model('report/reportvending/mRptAdjStockVending');
                $oModel = $this->mRptAdjStockVending;
                break;
            }
            case '002002003':{ // รายงานการโอนสินค้า (Vending)
                require_once REPORTPATH . 'controllers/reportvending/cRptProductTransfer.php';
                $oRptExport = new cRptProductTransfer($aExportParams);
        
                $this->load->model('report/reportvending/mRptProductTransfer');
                $oModel = $this->mRptProductTransfer;
                break;
            }
            case '002002004':{ // รายงานความเคลื่อนไหวของสินค้า (Vending)
                require_once REPORTPATH . 'controllers/reportvending/cRptMovePosVD.php';
                $oRptExport = new cRptMovePosVD($aExportParams);
        
                $this->load->model('report/reportvending/mRptMovePosVd');
                $oModel = $this->mRptMovePosVd;
                break;
            }
        
            /*===== ตู้ฝากของ Locker =========================================================*/
            case '003001001':{ // รายงานเปลี่ยนสถานะช่องฝากขาย
                require_once REPORTPATH . 'controllers/reportlocker/cRptChangeStaSale.php';
                $oRptExport = new cRptChangeStaSale($aExportParams);
        
                $this->load->model('report/reportlocker/mRptChangeStaSale');
                $oModel = $this->mRptChangeStaSale;
                break;
            }
            case '003001002':{ // รายงานการเปิดตู้โดยผู้ดูแลระบบ
                require_once REPORTPATH . 'controllers/reportlocker/cRptOpenSysAdmin.php';
                $oRptExport = new cRptOpenSysAdmin($aExportParams);
        
                $this->load->model('report/reportlocker/mRptOpenSysAdmin');
                $oModel = $this->mRptOpenSysAdmin;
                break;
            }
            case '003001003':{ // รายงานภาษีขาย (Locker)
                require_once REPORTPATH . 'controllers/reportlocker/cRptTaxSaleLocker.php';
                $oRptExport = new cRptTaxSaleLocker($aExportParams);
        
                $this->load->model('report/reportlocker/mRptTaxSaleLocker');
                $oModel = $this->mRptTaxSaleLocker;
                break;
            }
            case '003001004':{ // รายงานยอดขายตามการชำระเงิน
                require_once REPORTPATH . 'controllers/reportlocker/cRptLocPayment.php';
                $oRptExport = new cRptLocPayment($aExportParams);
        
                $this->load->model('report/reportlocker/mRptLocPayment');
                $oModel = $this->mRptLocPayment;
                break;
            }
            case '003001005':{ // รายงานยอดขายตามการชำระเงินแบบละเอียด (Locker)
                require_once REPORTPATH . 'controllers/reportlocker/cRptSaleByPaymentDetail.php';
                $oRptExport = new cRptSaleByPaymentDetail($aExportParams);
        
                $this->load->model('report/reportlocker/mRptSaleByPaymentDetail');
                $oModel = $this->mRptSaleByPaymentDetail;
                break;
            }
            case '003001006':{ // รายงานการฝากตามขนาดช่องฝาก (Locker)
                require_once REPORTPATH . 'controllers/reportlocker/cRptDpsSize.php';
                $oRptExport = new cRptDpsSize($aExportParams);
        
                $this->load->model('report/reportlocker/mRptDpsSize');
                $oModel = $this->mRptDpsSize;
                break;
            }
            case '003001007':{ // รายงานยอดฝากตามบริษัทขนส่ง (Locker)
                require_once REPORTPATH . 'controllers/reportlocker/cRptRentAmountFolloweCourier.php';
                $oRptExport = new cRptRentAmountFolloweCourier($aExportParams);
        
                $this->load->model('report/reportlocker/mRptRentAmountFolloweCourier');
                $oModel = $this->mRptRentAmountFolloweCourier;
                break;
            }
            case '003001008':{ // รายงานการฝากแบบละเอียด (Locker)
                require_once REPORTPATH . 'controllers/reportlocker/cRptRentAmountDetail.php';
                $oRptExport = new cRptRentAmountDetail($aExportParams);
        
                $this->load->model('report/reportlocker/mRptRentAmountDetail');
                $oModel = $this->mRptRentAmountDetail;
                break;
            }
            case '003001009':{ // รายงานการฝากตามช่วงเวลา (Locker)
                require_once REPORTPATH . 'controllers/reportlocker/cRptRentAmountFollowTime.php';
                $oRptExport = new cRptRentAmountFollowTime($aExportParams);
        
                $this->load->model('report/reportlocker/mRptRentAmountFollowTime');
                $oModel = $this->mRptRentAmountFollowTime;
                break;
            }
            case '003001010':{ // รายงานการฝากตามช่วงเวลา แบบละเอียด (Locker)
                require_once REPORTPATH . 'controllers/reportlocker/cRptDropByDate.php';
                $oRptExport = new cRptDropByDate($aExportParams);
        
                $this->load->model('report/reportlocker/mRptDropByDate');
                $oModel = $this->mRptDropByDate;
                break;
            }
            case '003001011':{ // รายงานการรับตามช่วงเวลา แบบละเอียด (Locker)
                require_once REPORTPATH . 'controllers/reportlocker/cRptPickByDate.php';
                $oRptExport = new cRptPickByDate($aExportParams);
        
                $this->load->model('report/reportlocker/mRptPickByDate');
                $oModel = $this->mRptPickByDate;
                break;
            }

            /*===== รายงานบัตร ==============================================================*/
            case '004001001':{ // 1. รายงานข้อมูลการใช้บัตร 004001001 rptCrdUseCard1
                require_once REPORTPATH . 'controllers/reportcard/cRptUseCard1.php';
                $oRptExport = new cRptUseCard1($aExportParams);
        
                $this->load->model('report/reportcard/mRptUseCard1');
                $oModel = $this->mRptUseCard1;
                break;
            }
            case '004001002':{ // 2. รายงานตรวจสอบสถานะบัตร 004001002 rptCrdCheckStatusCard
                require_once REPORTPATH . 'controllers/reportcard/cRptCheckStatusCard.php';
                $oRptExport = new cRptCheckStatusCard($aExportParams);
        
                $this->load->model('report/reportcard/mRptCheckStatusCard');
                $oModel = $this->mRptCheckStatusCard;
                break;
            }
            case '004001003':{ // 3. รายงานโอนข้อมูลบัตร 004001003 rptCrdTransferCardInfo
                require_once REPORTPATH . 'controllers/reportcard/cRptTransferCardInfo.php';
                $oRptExport = new cRptTransferCardInfo($aExportParams);
        
                $this->load->model('report/reportcard/mRptTransferCardInfo');
                $oModel = $this->mRptTransferCardInfo;
                break;
            }
            case '004001004':{ // 4. รายงานการปรับมูลค่าเงินสดในบัตร 004001004 rptCrdAdjustCashInCard
                require_once REPORTPATH . 'controllers/reportcard/cRptAdjustCashInCard.php';
                $oRptExport = new cRptAdjustCashInCard($aExportParams);
        
                $this->load->model('report/reportcard/mRptAdjustCashInCard');
                $oModel = $this->mRptAdjustCashInCard;
                break;
            }
            case '004001005':{ // 5. รายงานการล้างมูลค่าบัตรเพื่อกลับมาใช้งานใหม่ 004001005 rptCrdClearCardValueForReuse
                require_once REPORTPATH . 'controllers/reportcard/cRptClearCardValueForReuse.php';
                $oRptExport = new cRptClearCardValueForReuse($aExportParams);
        
                $this->load->model('report/reportcard/mRptClearCardValueForReuse');
                $oModel = $this->mRptClearCardValueForReuse;
                break;
            }
            case '004001006':{ // 6. รายงานการลบข้อมูลบัตรที่ไม่ใช้งาน 004001006 rptCrdCardNoActive
                require_once REPORTPATH . 'controllers/reportcard/cRptCardNoActive.php';
                $oRptExport = new cRptCardNoActive($aExportParams);
        
                $this->load->model('report/reportcard/mRptCardNoActive');
                $oModel = $this->mRptCardNoActive;
                break;
            }
            case '004001007':{ // 7. รายงานจำนวนรอบการใช้บัตร 004001007 rptCrdCardTimesUsed
                require_once REPORTPATH . 'controllers/reportcard/cRptCardTimesUsed.php';
                $oRptExport = new cRptCardTimesUsed($aExportParams);
        
                $this->load->model('report/reportcard/mRptCardTimesUsed');
                $oModel = $this->mRptCardTimesUsed;
                break;
            }
            case '004001008':{ // 8. รายงานบัตรคงเหลือ 004001008 rptCrdCardBalance
                require_once REPORTPATH . 'controllers/reportcard/cRptCardBalance.php';
                $oRptExport = new cRptCardBalance($aExportParams);
        
                $this->load->model('report/reportcard/mRptCardBalance');
                $oModel = $this->mRptCardBalance;
                break;
            }
            case '004001009':{ // 9. รายงานยอดสะสมบัตรหมดอายุ 004001009 rptCrdCollectExpireCard
                require_once REPORTPATH . 'controllers/reportcard/cRptCollectExpireCard.php';
                $oRptExport = new cRptCollectExpireCard($aExportParams);
        
                $this->load->model('report/reportcard/mRptCollectExpireCard');
                $oModel = $this->mRptCollectExpireCard;
                break;
            }
            case '004001010':{ // 10. รายงานรายการต้นงวดบัตรและเงินสด 004001010 rptCrdCardPrinciple
                require_once REPORTPATH . 'controllers/reportcard/cRptCardPrinciple.php';
                $oRptExport = new cRptCardPrinciple($aExportParams);
        
                $this->load->model('report/reportcard/mRptCardPrinciple');
                $oModel = $this->mRptCardPrinciple;
                break;
            }
            case '004001011':{ // 11. รายงานข้อมูลบัตร 004001011 rptCrdCardDetail
                require_once REPORTPATH . 'controllers/reportcard/cRptCardDetail.php';
                $oRptExport = new cRptCardDetail($aExportParams);
        
                $this->load->model('report/reportcard/mRptCardDetail');
                $oModel = $this->mRptCardDetail;
                break;
            }
            case '004001012':{ // 12. รายงานตรวจสอบการเติมเงิน 004001012 rptCrdCheckPrepaid
                require_once REPORTPATH . 'controllers/reportcard/cRptCheckPrepaid.php';
                $oRptExport = new cRptCheckPrepaid($aExportParams);
        
                $this->load->model('report/reportcard/mRptCheckPrepaid');
                $oModel = $this->mRptCheckPrepaid;
                break;
            }
            case '004001013':{ // 13. รายงานตรวจสอบข้อมูลการใช้บัตร 004001013 rptCrdCheckCardUseInfo
                require_once REPORTPATH . 'controllers/reportcard/cRptCheckCardUseInfo.php';
                $oRptExport = new cRptCheckCardUseInfo($aExportParams);
        
                $this->load->model('report/reportcard/mRptCheckCardUseInfo');
                $oModel = $this->mRptCheckCardUseInfo;
                break;
            }
            case '004001014':{ // 14. รายงานการเติมเงิน 004001014 rptCrdTopUp
                require_once REPORTPATH . 'controllers/reportcard/cRptTopUp.php';
                $oRptExport = new cRptTopUp($aExportParams);
        
                $this->load->model('report/reportcard/mRptTopUp');
                $oModel = $this->mRptTopUp;
                break;
            }
            case '004001015':{ // 15. รายงานข้อมูลการใช้บัตร 004001015 (แบบละเอียด) rptCrdUseCard2
                require_once REPORTPATH . 'controllers/reportcard/cRptUseCard2.php';
                $oRptExport = new cRptUseCard2($aExportParams);
        
                $this->load->model('report/reportcard/mRptUseCard2');
                $oModel = $this->mRptUseCard2;
                break;
            } 

            /*===== รายงานวิเคราะห์ ===========================================================*/  
            case '005001001':{ // 1. รายงานยอดขายร้านค้า-ตามวันที่ 005001001 rptSaleShopByDate
                require_once REPORTPATH . 'controllers/reportanalysis/cRptSaleShopByDate.php';
                $oRptExport = new cRptSaleShopByDate($aExportParams);
        
                $this->load->model('report/reportanalysis/mRptSaleShopByDate');
                $oModel = $this->mRptSaleShopByDate;
                break;
            }
            case '005001002':{ // 2. รายงานยอดขายร้านค้า-ตามร้านค้า 005001002 rptSaleShopByShop
                require_once REPORTPATH . 'controllers/reportanalysis/cRptSaleShopByShop.php';
                $oRptExport = new cRptSaleShopByShop($aExportParams);
        
                $this->load->model('report/reportanalysis/mRptSaleShopByShop');
                $oModel = $this->mRptSaleShopByShop;
                break;
            }
            case '005001003':{ // 3. รายงานการเคลื่อนไหวบัตร-แบบสรุป 005001003 rptCrdCardActiveSummary
                /*require_once REPORTPATH . 'controllers/reportanalysis/cRptPickByDate.php';
                $oRptExport = new cRptPickByDate($aExportParams);
        
                $this->load->model('report/reportanalysis/mRptPickByDate');
                $oModel = $this->mRptPickByDate;*/
                break;
            }
            case '005001004':{ // 4. รายงานการเคลื่อนไหวบัตร-แบบละเอียด 005001004 rptCrdCardActiveDetail
                /*require_once REPORTPATH . 'controllers/reportanalysis/cRptPickByDate.php';
                $oRptExport = new cRptPickByDate($aExportParams);
        
                $this->load->model('report/reportanalysis/mRptPickByDate');
                $oModel = $this->mRptPickByDate;*/
                break;
            }
            case '005001005':{ // 5. รายงานสรุปยอดเงินคงเหลือบัตรไม่ได้แลกคืน 005001005 rptCrdUnExchangeBalance
                /*require_once REPORTPATH . 'controllers/reportanalysis/cRptPickByDate.php';
                $oRptExport = new cRptPickByDate($aExportParams);
        
                $this->load->model('report/reportanalysis/mRptPickByDate');
                $oModel = $this->mRptPickByDate;*/
                break;
            }     
        default:{
                echo " [X] Report Code not found.", "\n";
                return;
            }
        
        }
        /*===== End Resource Report ====================================================*/

        /*===== Begin Calulat ==========================================================*/
        // คำนวณหาจำนวนไฟล์ทั้งหมดจากข้อมูลรายงาน(Temp)
        $aCountRowsParams = [
            'tCompName' => $this->aParams['tComName'],
            'tRptCode' => $this->aParams['tRptCode'],
            'tUserSessionID' => $this->aParams['tUserSessionID']
        ];
        $nTotalRows = $oModel->FSnMCountRowInTemp($aCountRowsParams);
        
        if($nTotalRows <= 0){ // ออกจาก Process เมื่อไม่พบข้อมูล
            echo " [X] Data not found." , "\n";
            return;
        }
        
        $nTotalFile = ceil($nTotalRows / $this->aParams['nPerFile']);
        /*===== End Calulat ============================================================*/
        
        /*===== Begin Add History Export ===============================================*/
        // เก็บประวัติการส่งออกรายงาน TSysHisExport
        $aHisRptAddParams = [
            'FTComName' => $this->aParams['tComName'],
            'FTUsrCode' => $this->aParams['tUserCode'],
            'FTUsrSession' => $this->aParams['tUserSessionID'],
            'FTRptCode' => $this->aParams['tRptCode'],
            'FDCreateDate' => $tCreateDate, // วันที่ออกรายงาน
            'FDExprDate' => $tExprDate, // ระเวลาหมดอายุ
            'FTExpType' => $this->aParams['tRptExpType'], // ประเภทการออกรายงาน : exel, pdf
            'FTFilter' => json_encode($this->aParams['tFilter']),
            'FTZipName' => null, // ชื่อไฟล์ Zip
            'FNTotalFile' => $nTotalFile, // ไฟล์ทั้งหมด
            'FNSuccessFile' => 0, // ไฟล์ที่ทำสำเร็จ
            'FTStaDownload' => '0', // สถานะดาวน์โหลด
            'FTStaZip' => '0', // สถานะการ Zip
            'FTStaExpFail' => '0', // สถานะการ Export 0: not fail 1: fail
            'FTStaCancelDownload' => '0' // สถานะการยกเลิกดาวน์โหลด
        ];
        if(!$this->mHisReport->FSaMHISRPTHasRecord($aHisRptAddParams)){
            $this->mHisReport->FSaMHISRPTAdd($aHisRptAddParams);
        }else {
            echo " [X] Data is duplicate." , "\n";
            return;
        }
        /*===== End Add History Export =================================================*/
        
        /*===== Begin Send First Progress ==============================================*/
        // ส่งสถานะก่อนการประมวลผล
        $aFirstMsg = [
            'ptComName' => $this->aParams['tComName'],
            'ptRptCode' => $this->aParams['tRptCode'],
            'ptUserSessionID' => $this->aParams['tUserSessionID'],
            'ptRptExpType' => $this->aParams['tRptExpType'],
            'pnPerFile' => $this->aParams['nPerFile'],
            'ptUserCode' => $this->aParams['tUserCode'],
            "pnSuccessFile" => 0,
            "pnTotalFile" => $nTotalFile,
            "pnStaZip" => 0,
            "pnStaExpFail" => 0
            // "ptPathFile" => ""
        ];
        $aPublishParams = [
            'tMsgFormat' => 'json',
            'tQname' => $tPublishName,
            'tMsg' => json_encode($aFirstMsg)
        ];
        $this->FSxMQPublish($aPublishParams);
        echo " [/] Send First Msg Success." , "\n";
        /*===== End Send First Progress ================================================*/
        
        /*===== Begin Loop Render ======================================================*/
        echo " [*] Start Export" , "\n";
        
        $bIsLastFile = false;
        $nFile = 0; // ไว้เก็บจำนวนไฟล์สุดท้าย
        for($nLoop=1; $nLoop<=$nTotalFile; $nLoop++) {
            
            if($nLoop == $nTotalFile){
                $bIsLastFile = true;
            }
            
            $aRenderExcelParams = [
                'nFile' => $nLoop, // $paParams['nFile']
                'bIsLastFile' => $bIsLastFile,
                'tFileName' => $tPatternName.'_'.$nLoop,
            ];
            $aRenderExcelStatus = $oRptExport->FSvCCallRptRenderExcel($aRenderExcelParams);
            // var_dump($aRenderExcelStatus);
            if($aRenderExcelStatus['nStaExport'] == 1) {
                echo " [/] Export Success: $nLoop/$nTotalFile" , "\n";

                /*===== Begin Update FNSuccessFile =====================================*/
                $aEditStaSuccessFileParams = [
                    'FTComName' => $this->aParams['tComName'],
                    'FTUsrCode' => $this->aParams['tUserCode'],
                    'FTUsrSession' => $this->aParams['tUserSessionID'],
                    'FTRptCode' => $this->aParams['tRptCode'],
                    'FDCreateDate' => $tCreateDate, // วันที่ออกรายงาน
                    'FDExprDate' => $tExprDate, // ระเวลาหมดอายุ
                    'FTExpType' => $this->aParams['tRptExpType'], // ประเภทการออกรายงาน : exel, pdf

                    'FNSuccessFile' => $nLoop, // ไฟล์ที่ทำสำเร็จ
                ];
                $this->mHisReport->FSaMHISRPTEditStaSuccessFile($aEditStaSuccessFileParams);
                /*===== End Update FNSuccessFile =======================================*/

                /*===== Begin Send Progress ============================================*/
                $aRenderMsg = [
                    'ptComName' => $this->aParams['tComName'],
                    'ptRptCode' => $this->aParams['tRptCode'],
                    'ptUserSessionID' => $this->aParams['tUserSessionID'],
                    'ptRptExpType' => $this->aParams['tRptExpType'],
                    'pnPerFile' => $this->aParams['nPerFile'],
                    'ptUserCode' => $this->aParams['tUserCode'],
                    "pnSuccessFile" => $nLoop,
                    "pnTotalFile" => $nTotalFile,
                    "pnStaZip" => 0,
                    "pnStaExpFail" => 0
                    // "ptPathFile" => ""
                ];
                $aPublishParams = [
                    'tMsgFormat' => 'json',
                    'tQname' => $tPublishName,
                    'tMsg' => json_encode($aRenderMsg)
                ];
                $this->FSxMQPublish($aPublishParams);
                echo " [/] Update StaSuccessFile Success: $nLoop/$nTotalFile" , "\n";
                /*===== End Send Progress ==============================================*/
            }else{
                echo " [X] Export Fail: $nLoop/$nTotalFile" , "\n";

                /*===== Begin Update FTStaExpFail ======================================*/
                $aEditStaExpFailParams = [
                    'FTComName' => $this->aParams['tComName'],
                    'FTUsrCode' => $this->aParams['tUserCode'],
                    'FTUsrSession' => $this->aParams['tUserSessionID'],
                    'FTRptCode' => $this->aParams['tRptCode'],
                    'FDCreateDate' => $tCreateDate, // วันที่ออกรายงาน
                    'FDExprDate' => $tExprDate, // ระเวลาหมดอายุ
                    'FTExpType' => $this->aParams['tRptExpType'], // ประเภทการออกรายงาน : exel, pdf

                    'FTStaExpFail' => 1, // ออกไฟล์ไม่สำเร็จ
                ];
                $this->mHisReport->FSaMHISRPTEditStaExpFail($aEditStaExpFailParams);
                /*===== End Update FTStaExpFail ========================================*/

                /*===== Begin Send Message Fail ========================================*/
                $aRenderMsg = [
                    'ptComName' => $this->aParams['tComName'],
                    'ptRptCode' => $this->aParams['tRptCode'],
                    'ptUserSessionID' => $this->aParams['tUserSessionID'],
                    'ptRptExpType' => $this->aParams['tRptExpType'],
                    'pnPerFile' => $this->aParams['nPerFile'],
                    'ptUserCode' => $this->aParams['tUserCode'],
                    "pnSuccessFile" => $nLoop,
                    "pnTotalFile" => $nTotalFile,
                    "pnStaZip" => 0,
                    "pnStaExpFail" => 1
                    // "ptPathFile" => $aStaZip['tPathFile']
                ];
                $aPublishParams = [
                    'tMsgFormat' => 'json',
                    'tQname' => $tPublishName,
                    'tMsg' => json_encode($aRenderMsg)
                ];
                $this->FSxMQPublish($aPublishParams);
                /*===== End Send Message Fail ==========================================*/

                return;
            }
            
            $nFile = $nLoop;

            unset($aRenderExcelParams);
            unset($aRenderExcelStatus);
            unset($aEditStaSuccessFileParams);
            unset($aRenderMsg);
            unset($aPublishParams);
            unset($aEditStaExpFailParams);
            unset($aRenderMsg);
            unset($aPublishParams);
        }
        echo " [*] End Export" , "\n";
        /*===== End Loop Render ========================================================*/
        
        
        
        // ก่อนบีบอัดตรวจสอบก่อนว่าไฟล์ครบหรือไม่
        /*===== Begin Zip File =========================================================*/
        echo " [*] Start Zip Process" , "\n";
        $aZipFileParams = [
            'tPatternName' => $tPatternName,
            'tCompName' => $this->aParams['tComName'],
            'tRptCode' => $this->aParams['tRptCode'],
            'tUserSessionID' => $this->aParams['tUserSessionID'],
            'tUserCode' => $this->aParams['tUserCode'],
            'tRptExpType' => $this->aParams['tRptExpType'],
            'nTotalFile' => $nTotalFile
        ];
        $aStaZip = $this->FSaZipFile($aZipFileParams);
        
        
        if($aStaZip['bStaZip']) { // Zip Success
            
            /*===== Begin Update StaZip ================================================*/
            $aEditStaZipParams = [
                'FTComName' => $this->aParams['tComName'],
                'FTUsrCode' => $this->aParams['tUserCode'],
                'FTUsrSession' => $this->aParams['tUserSessionID'],
                'FTRptCode' => $this->aParams['tRptCode'],
                'FDCreateDate' => $tCreateDate, // วันที่ออกรายงาน
                'FDExprDate' => $tExprDate, // ระเวลาหมดอายุ
                'FTExpType' => $this->aParams['tRptExpType'], // ประเภทการออกรายงาน : exel, pdf

                'FTStaZip' => "1",
                'FTZipName' => $aStaZip['tZipName'],
                'FTZipPath' => $aStaZip['tPathFile']
            ];
            $this->mHisReport->FSaMHISRPTEditStaZip($aEditStaZipParams);
            /*===== End Update StaZip ==================================================*/
            
            // $oMsg->delivery_info['channel']->basic_ack($oMsg->delivery_info['delivery_tag']);    
            
            /*===== Begin Send Progress After Zip ======================================*/
            $aRenderMsg = [
                'ptComName' => $this->aParams['tComName'],
                'ptRptCode' => $this->aParams['tRptCode'],
                'ptUserSessionID' => $this->aParams['tUserSessionID'],
                'ptRptExpType' => $this->aParams['tRptExpType'],
                'pnPerFile' => $this->aParams['nPerFile'],
                'ptUserCode' => $this->aParams['tUserCode'],
                "pnSuccessFile" => $nFile,
                "pnTotalFile" => $nTotalFile,
                "pnStaZip" => 1,
                "pnStaExpFail" => 0
                // "ptPathFile" => $aStaZip['tPathFile']
            ];
            $aPublishParams = [
                'tMsgFormat' => 'json',
                'tQname' => $tPublishName,
                'tMsg' => json_encode($aRenderMsg)
            ];
            $this->FSxMQPublish($aPublishParams);
            /*===== End Send Progress After Zip ========================================*/

            unset($oModel);
            unset($oRptExport);
            unset($oMsg);
            unset($tRptName);
            unset($tCreateDate);
            unset($tExprDate);
            unset($tPatternName);
            unset($tPublishName);
            unset($aExportParams);
            unset($aCountRowsParams);
            unset($nTotalRows);
            unset($nTotalFile);
            unset($aHisRptAddParams);
            unset($aFirstMsg);
            unset($aPublishParams);
            unset($bIsLastFile);
            unset($nFile);
            unset($aZipFileParams);
            unset($aStaZip);
            unset($aEditStaZipParams);
            unset($aRenderMsg);
            unset($aPublishParams);
        }
        
        echo " [*] End Zip Process" , "\n";
        /*===== End Zip File ===========================================================*/
        echo " [*] End Process" , "\n";
        
    }
    
    // MQ Callback
    public function FSxCallback($msg) {
        echo ' [/] Received ', $msg->body, "\n";
        sleep(substr_count($msg->body, '.'));
        echo " [/] Done\n";
        
        if(empty($msg->body)){
            echo ' [X] Parameter Not Found. ', "\n\n";
            return;
        }
        
        $aBody = json_decode($msg->body, true);
        $this->aParams['tBchCode'] = isset($aBody['ptBchCode']) ? $aBody['ptBchCode'] : "";
        $this->aParams['tComName'] = isset($aBody['ptComName']) ? $aBody['ptComName'] : "";
        $this->aParams['tUserCode'] = isset($aBody['ptUserCode']) ? $aBody['ptUserCode'] : "";
        $this->aParams['tUserSessionID'] = isset($aBody['ptUserSessionID']) ? $aBody['ptUserSessionID'] : "";
        $this->aParams['tRptExpType'] = isset($aBody['ptRptExpType']) ? $aBody['ptRptExpType'] : "";
        $this->aParams['tRptCode'] = isset($aBody['ptRptCode']) ? $aBody['ptRptCode'] : "";
        $this->aParams['nPerFile'] = isset($aBody['pnPerFile']) ? $aBody['pnPerFile'] : "";
        
        $this->aParams['nLngID'] = isset($aBody['pnLngID']) ? $aBody['pnLngID'] : "";
        $this->aParams['tFilter'] = isset($aBody['ptFilter']) ? $aBody['ptFilter'] : "";
        $this->aParams['tDate'] = isset($aBody['ptDate']) ? $aBody['ptDate'] : "";
        $this->aParams['tTime'] = isset($aBody['ptTime']) ? $aBody['ptTime'] : "";
        
        if(
            !empty($this->aParams['tComName']) && !empty($this->aParams['tUserCode']) 
            && !empty($this->aParams['tUserSessionID']) && !empty($this->aParams['tRptExpType'])
            && !empty($this->aParams['tRptCode']) && !empty($this->aParams['nPerFile'])
            && !empty($this->aParams['nLngID']) && !empty($this->aParams['tFilter'])
            && !empty($this->aParams['tDate']) && !empty($this->aParams['tTime'])
            && !empty($this->aParams['tBchCode']) 
        ) {
            $aProcessParams = [
                'oMsg' => $msg
            ];
            $this->FSaCProcess($aProcessParams);
        }else {
            echo ' [X] Parameter Not Match. ', $msg->body, "\n\n";
            return;
        }
        echo "=========== END =====================================================================", "\n\n";
    }

    /**
     * Functionality: Zip File
     * Parameters:  Function Parameter
     * Creator: 22/07/2019 Piya
     * LastUpdate: -
     * Return: Status
     * ReturnType: Array
     */
    public function FSaZipFile($paParams = []) {
        /*===== Begin Init Vareable ====================================================*/
        $tPatternName = $paParams['tPatternName'];
        $tRptCode = $paParams['tRptCode'];
        $tUserLoginCode = $paParams['tUserCode'];
        $tRptExportType = $paParams['tRptExpType'];
        $nTotalFile = $paParams['nTotalFile'];
        
        $tExportFolder = '';
        if($tRptExportType == 'excel'){
            $tExportFolder = 'exportexcel';
        }
        
        $tPathExport = EXPORTPATH."$tExportFolder/$tRptCode/$tUserLoginCode/";
        /*===== End Init Vareable ======================================================*/
        
        /*===== Begin Zip ==============================================================*/
        // Add File
        for($nLoop=1; $nLoop<=$nTotalFile; $nLoop++) {
            $this->zip->read_file($tPathExport.$tPatternName."_".$nLoop.".xlsx");
        }
        
        // Set Path
        if(!is_dir(EXPORTPATH."$tExportFolder/")) {
            mkdir(EXPORTPATH."$tExportFolder/");
        }
        if(!is_dir(EXPORTPATH."$tExportFolder/archive/")) {
            mkdir(EXPORTPATH."$tExportFolder/archive/");
        }
        if(!is_dir(EXPORTPATH."$tExportFolder/archive/$tRptCode")) {
            mkdir(EXPORTPATH."$tExportFolder/archive/$tRptCode");
        }
        if(!is_dir(EXPORTPATH."$tExportFolder/archive/$tRptCode/$tUserLoginCode")) {
            mkdir(EXPORTPATH."$tExportFolder/archive/$tRptCode/$tUserLoginCode");
        }
        $tPathExport = EXPORTPATH."$tExportFolder/archive/$tRptCode/$tUserLoginCode/";
        
        $aStaZip = [
            'bStaZip' => false,
            'tZipName' => "",
            'tPathFile' => ""
        ];
        // Write the zip file to a folder on your server.
        if($this->zip->archive($tPathExport.$tPatternName.".zip")) {
            echo ' [/] Zip Success.' , "\n";
            $aStaZip['bStaZip'] = true;
            $aStaZip['tZipName'] = $tPatternName.".zip";
            $aStaZip['tPathFile'] = $tPathExport.$tPatternName.".zip";
        }else {
            echo ' [/] Zip Fail.' , "\n";
        }
        
        $this->zip->clear_data();
        /*===== End Zip ================================================================*/

        $tPathExport = EXPORTPATH."$tExportFolder/$tRptCode/$tUserLoginCode/";
        
        $oFiles = glob($tPathExport.'*');
        
        foreach($oFiles as $tFile) {
            if(is_file($tFile)){
                unlink($tFile);
            }
        }

        unset($tPatternName);
        unset($tRptCode);
        unset($tUserLoginCode);
        unset($tRptExportType);
        unset($nTotalFile);
        unset($tExportFolder);
        unset($tPathExport);
        unset($oFiles);

        return $aStaZip;
        
    }
}








































































