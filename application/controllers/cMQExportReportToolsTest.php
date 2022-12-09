<?php
set_time_limit(0);
ini_set('memory_limit', '8000M'); // -1 unlimit memory
ini_set('max_execution_time', 3600 * 120); //3600 seconds =  minutes
ini_set('sqlsrv.ClientBufferMaxKBSize', '524288'); // Setting to 512M
ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '524288'); // Setting to 512M - for pdo_sqlsrv
ini_set("pcre.backtrack_limit", "1000000000000");

require_once(APPPATH . 'libraries/rabbitmq/vendor/autoload.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class cMQExportReportToolsTest extends MX_Controller {
    
    private $aMQConfig = [];
    private $aQname = [];
    private $aParams = [];
    
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
    }

    // Consomer รอรับข้อความ เพื่อประมวลผล
    public function FSxMQConsumer($paParams = []) {
        echo "Connect : ".$this->aMQConfig['tHost'].", ".$this->aMQConfig['tPort']." ".$this->aMQConfig['tUsername']." ".$this->aMQConfig['tPassword']." ".$this->aMQConfig['tVHost'], "\n";
        echo "=====================================================================================", "\n\n";
        $oConnection = new AMQPStreamConnection(
            $this->aMQConfig['tHost'], 
            $this->aMQConfig['tPort'], 
            $this->aMQConfig['tUsername'], 
            $this->aMQConfig['tPassword'], 
            $this->aMQConfig['tVHost']
        );
        
        $oChannel = $oConnection->channel();
        
        echo " [*] Waiting for messages. To exit press CTRL+C\n\n\n";
        $oChannel->basic_qos(null, 1, null);
        
        /*foreach ($this->aQname as $tQname) {
            $oChannel->queue_declare($tQname, false, false, false, false);
            $oChannel->basic_consume($tQname, '', false, true, false, false, [$this, 'FSxCallback']);
        }*/
        
        $oChannel->queue_declare('TESTRPT', false, false, false, false);
        $oChannel->basic_consume('TESTRPT', '', false, true, false, false, [$this, 'FSxCallback']);
            
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
        
        echo ' [/] Send Progress Success' , "\n";
    }
    
    // ประมวลผล
    public function FSaCProcess($paParams = []) {
        /*===== Begin Init Variable ==========================================*/
        $oMsg = $paParams['oMsg'];
        $tRptName = $this->aParams['tRptCode'];
        // วันที่ออกรายงาน
        $tCreateDate = date('Y-m-d H:i:s', strtotime($this->aParams['tDate']. ' ' . $this->aParams['tTime']));
        // วันที่หมดอายุ ระยะเวลา 1 ปี
        $tExprDate = date('Y-m-d H:i:s', strtotime("+12 months " . $this->aParams['tDate']. ' ' . $this->aParams['tTime']));
        $tPatternName = $this->aParams['tRptCode'].'_'.$this->aParams['tUserCode'].'_'.$this->aParams['tUserSessionID'].'_'.str_replace('-', '', $this->aParams['tDate']).'_'.str_replace(':', '', $this->aParams['tTime']);
        $tPublishName = "RESRPT_".$tPatternName;
        /*===== End Init Variable ==========================================*/
        
        echo " [*] Begin Process" , "\n";
        
        /*===== Begin Resource Report ========================================*/
        $aExportParams = [
            'tCompName' => $this->aParams['tComName'],
            'tRptCode' => $this->aParams['tRptCode'],
            'tUserSessionID' => $this->aParams['tUserSessionID'],
            'tRptExpType' => $this->aParams['tRptExpType'],
            'nPerFile' => $this->aParams['nPerFile'],
            'tUserCode' => $this->aParams['tUserCode'],
            'aFilter' => $this->aParams['tFilter']
        ];
        switch($tRptName) {
            /*===== การขาย POS ================================================*/
            case '001001001' : { // รายงานยอดขายตามบิล (Pos)
                require_once REPORTPATH.'controllers/reportsale/cRptSaleByBill.php';
                $oRptExport = new cRptSaleByBill($aExportParams);
                
                $this->load->model('report/reportsale/mRptSaleByBill');
                $oModel = $this->mRptSaleByBill;
                break;
            }
            case '001001002' : { // รายงานยอดขายตามสินค้า (Pos) Pos5 ยังไม่เสร็จ
                break;
            }
            case '001001003' : { // รายงานยอดขายตามการชำระเงินแบบสรุป (Pos)
                require_once REPORTPATH.'controllers/reportsale/cRptSalePayment.php';
                $oRptExport = new cRptSalePayment($aExportParams);
                
                $this->load->model('report/reportsale/mRptSalePayment');
                $oModel = $this->mRptSalePayment;
                break;
            }
            case '001001004' : { // รายงานสินค้าขายดีตามจำนวน(Pos)
                require_once REPORTPATH.'controllers/reportsale/cRptBestSell.php';
                $oRptExport = new cRptBestSell($aExportParams);
                
                $this->load->model('report/reportsale/mRptBestSell');
                $oModel = $this->mRptBestSell;
                break;
            }
            case '001001005' : { // รายงานยอดขายตามการชำระเงินแบบละเอียด(Pos)
                require_once REPORTPATH.'controllers/reportsale/cRptSaleRecive.php';
                $oRptExport = new cRptSaleRecive($aExportParams);
                
                $this->load->model('report/reportsale/mRptSaleRecive');
                $oModel = $this->mRptSaleRecive;
                break;
            }
            case '001001006' : { // รายงานภาษีขาย (Pos)
                require_once REPORTPATH.'controllers/reportsale/cRptTaxSalePos.php';
                $oRptExport = new cRptTaxSalePos($aExportParams);
                
                $this->load->model('report/reportsale/mRptTaxSalePos');
                $oModel = $this->mRptTaxSalePos;
                break;
            }
            case '001001007' : { // รายงานภาษีขายตามวันที่ (Pos)
                require_once REPORTPATH.'controllers/reportsale/cRptTaxSalePosByDate.php';
                $oRptExport = new cRptTaxSalePosByDate($aExportParams);
                
                $this->load->model('report/reportsale/mRptTaxSalePosByDate');
                $oModel = $this->mRptTaxSalePosByDate;
                break;
            }
            /*===== คลังสินค้า POS ===============================================*/
            case '001002001' : { // รายงานสินค้าคงคลัง (Pos)
                require_once REPORTPATH.'controllers/reportInventoryPos/cRptInventoryPos.php';
                $oRptExport = new cRptInventoryPos($aExportParams);
                
                $this->load->model('report/reportInventoryPos/mRptInventoryPos');
                $oModel = $this->mRptInventoryPos;
                break;
            }
            case '001002002' : { // รายงานความเคลื่อนไหวของสินค้า (Pos) Pos5 ยังไม่เสร็จ
                break;
            }
            
            /*===== รายงานการขาย ตู้ขายของ Vending ===============================*/
            case '002001001' : { // รายงานภาษีขายตามกลุ่มร้านค้า  (Vending)
                break;
            }
            case '002001002' : { // รายงานยอดขายตามการชำระเงินแบบสรุป (Vending)
                break;
            }
            case '002001003' : { // รายงานยอดขายตามการชำระเงินแบบละเอียด (Vending)
                break;
            }
            case '002001004' : { // รายงานสินค้าขายดี (Vending)
                break;
            }
            /*===== รายงานสินค้าคงคลัง ตู้ขายของ Vending ============================*/
            case '002002001' : { // รายงานสินค้าคงคลัง (Vedding)
                break;
            }
            case '002002002' : { // รายงานการตรวจนับสต็อก (Vending)
                break;
            }
            case '002002003' : { // รายงานการโอนสินค้า (Vending)
                break;
            }
            case '002002004' : { // รายงานความเคลื่อนไหวของสินค้า (Vending)
                break;
            }
            
            /*===== ตู้ฝากของ Locker ============================================*/
            case '003001001' : { // รายงานเปลี่ยนสถานะช่องฝากขาย
                break;
            }
            case '003001002' : { // รายงานการเปิดตู้โดยผู้ดูแลระบบ
                break;
            }
            case '003001003' : { // รายงานภาษีขาย (Locker)
                break;
            }
            case '003001004' : { // รายงานยอดขายตามการชำระเงิน
                break;
            }
            case '003001005' : { // รายงานยอดขายตามการชำระเงินแบบละเอียด (Locker)
                require_once REPORTPATH.'controllers/reportlocker/cRptSaleByPaymentDetail.php';
                $oRptExport = new cRptSaleByPaymentDetail($aExportParams);
                
                $this->load->model('report/reportlocker/mRptSaleByPaymentDetail');
                $oModel = $this->mRptSaleByPaymentDetail;
                break;
            }   
            case '003001006' : { // รายงานการฝากตามขนาดช่องฝาก (Locker)
                break;
            }
            case '003001007' : { // รายงานยอดฝากตามบริษัทขนส่ง (Locker)
                break;
            }
            case '003001008' : { // รายงานการฝากแบบละเอียด (Locker)
                break;
            }
            case '003001009' : { // รายงานการฝากตามช่วงเวลา (Locker)
                break;
            }
            
        }
        /*===== End Resource Report ==========================================*/
        
        /*===== Begin Calulat ================================================*/
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
        /*===== End Calulat ==================================================*/
        
        /*===== Begin Add History Export =====================================*/
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
            'FTStaCancelDownload' => '0' // สถานะการยกเลิกดาวน์โหลด
        ];
        if(!$this->mHisReport->FSaMHISRPTHasRecord($aHisRptAddParams)){
            $this->mHisReport->FSaMHISRPTAdd($aHisRptAddParams);
        }else {
            echo " [X] Data is duplicate." , "\n";
            return;
        }
        /*===== End Add History Export =======================================*/
        
        /*===== Begin Send First Progress ====================================*/
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
            // "ptPathFile" => ""
        ];
        $aPublishParams = [
            'tMsgFormat' => 'json',
            'tQname' => $tPublishName,
            'tMsg' => json_encode($aFirstMsg)
        ];
        $this->FSxMQPublish($aPublishParams);
        echo " [/] Send First Msg Success." , "\n";
        /*===== End Send First Progress ======================================*/
        
        /*===== Begin Loop Render ============================================*/
        echo " [*] Start Export" , "\n";
        
        $bUseThreadMode = true;
        $bIsLastFile = false;
        $nFile = 0; // ไว้เก็บจำนวนไฟล์สุดท้าย
        $aPool = []; // ไว้เก็บ Thread
        
        /*===== Begin Thread Mode ============================================*/
        if($bUseThreadMode) { // Use Thread Mode
            for($nLoop=1; $nLoop<=$nTotalFile; $nLoop++) {
                if($nLoop == $nTotalFile){
                    $bIsLastFile = true;
                }
                require_once(APPPATH . 'controllers/cExportThread.php');
                
                $aExportThreadParams = [
                    'oRptExport' => $oRptExport,
                    'oModelReport' => $oModel,
                    'oMsg' => $oMsg,
                    'tExpType' => $this->aParams['tRptExpType'],
                    'nFile' => $nLoop, 
                    'bIsLastFile' => $bIsLastFile,
                    'tPatternName' => $tPatternName,
                    'nTotalFile' => $nTotalFile,
                    'tCreateDate' => $tCreateDate,
                    'tExprDate' => $tExprDate,
                    'tComName' => $this->aParams['tComName'],
                    'tUserCode' => $this->aParams['tUserCode'],
                    'tUserSessionID' => $this->aParams['tUserSessionID'],
                    'tRptCode' => $this->aParams['tRptCode'],
                    'tPublishName' => $tPublishName,
                    'oHisReport' => $this->mHisReport,
                    'oPublish' => $this
                ];
                $aPool[] = new cExportThread($aExportThreadParams);
            }
            foreach($aPool as $oWorker){
                $oWorker->start();
            }
        }
        /*===== End Thread Mode ==============================================*/
        
        /*===== Begin Normor Mode ============================================*/
        if(!$bUseThreadMode) { // Use Normal Mode
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

                    /*===== Begin Update FNSuccessFile ===========================*/
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
                    /*===== End Update FNSuccessFile =============================*/

                    /*===== Begin Send Progress ==================================*/
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
                        // "ptPathFile" => ""
                    ];
                    $aPublishParams = [
                        'tMsgFormat' => 'json',
                        'tQname' => $tPublishName,
                        'tMsg' => json_encode($aRenderMsg)
                    ];
                    $this->FSxMQPublish($aPublishParams);
                    echo " [/] Update StaSuccessFile Success: $nLoop/$nTotalFile" , "\n";
                    /*===== End Send Progress ====================================*/
                }else{
                    echo " [X] Export Fail: $nLoop/$nTotalFile" , "\n";
                }
                
                $nFile = $nLoop;
            }
        }
        /*===== End Normor Mode ==============================================*/
        
        echo " [*] End Export" , "\n";
        /*===== End Loop Render ==============================================*/
        
        
        
        // ก่อนบีบอัดตรวจสอบก่อนว่าไฟล์ครบหรือไม่
        /*===== Begin Zip File ===============================================*/
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
            
            /*===== Begin Update StaZip ===========================*/
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
            /*===== End Update StaZip =============================*/
                
            /*===== Begin Send Progress After Zip ================================*/
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
                // "ptPathFile" => $aStaZip['tPathFile']
            ];
            $aPublishParams = [
                'tMsgFormat' => 'json',
                'tQname' => $tPublishName,
                'tMsg' => json_encode($aRenderMsg)
            ];
            $this->FSxMQPublish($aPublishParams);
            /*===== End Send Progress After Zip ==================================*/
        }
        
        echo " [*] End Zip Process" , "\n";
        /*===== End Zip File =================================================*/
        
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
        /*===== Begin Init Vareable ==========================================*/
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
        /*===== End Init Vareable ============================================*/
        
        /*===== Begin Zip ====================================================*/
        // Add File
        for($nLoop=1; $nLoop<=$nTotalFile; $nLoop++) {
            $this->zip->read_file($tPathExport.$tPatternName."_".$nLoop.".xlsx");
        }
        
        // Set Path
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
        /*===== End Zip ======================================================*/
        
        // Download the file to your desktop. Name it "my_backup.zip"
        // $this->zip->download('my_backup.zip');

        $tPathExport = EXPORTPATH."$tExportFolder/$tRptCode/$tUserLoginCode/";
        
        $oFiles = glob($tPathExport.'*');
        
        foreach($oFiles as $tFile) {
            if(is_file($tFile)){
                unlink($tFile);
            }
        }
        
        return $aStaZip;
        
    }
}
