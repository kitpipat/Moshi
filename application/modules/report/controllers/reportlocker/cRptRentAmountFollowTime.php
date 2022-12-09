<?php

defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

date_default_timezone_set("Asia/Bangkok");

class cRptRentAmountFollowTime extends MX_Controller
{

    /**
     * ภาษา
     * @var array
     */
    public $aText = [];

    /**
     * จำนวนต่อหน้าในรายงาน
     * @var int 
     */
    public $nPerPage = 100;

    /**
     * Page number
     * @var int
     */
    public $nPage = 1;

    /**
     * จำนวนทศนิยม
     * @var int
     */
    public $nOptDecimalShow = 2;

    /**
     * จำนวนข้อมูลใน Temp
     * @var int
     */
    public $nRows = 0;

    /**
     * Computer Name
     * @var string
     */
    public $tCompName;

    /**
     * User Login on Bch
     * @var string
     */
    public $tBchCodeLogin;

    /**
     * Report Code
     * @var string
     */
    public $tRptCode;

    /**
     * Report Group
     * @var string
     */
    public $tRptGroup;

    /**
     * System Language
     * @var int
     */
    public $nLngID;

    /**
     * User Session ID
     * @var string
     */
    public $tUserSessionID;

    /**
     * Report route
     * @var string
     */
    public $tRptRoute;

    /**
     * Report Export Type
     * @var string
     */
    public $tRptExportType;

    /**
     * Filter for Report
     * @var array 
     */
    public $aRptFilter = [];

    /**
     * Company Info
     * @var array
     */
    public $aCompanyInfo = [];

    /**
     * User Login Session
     * @var string 
     */
    public $tUserLoginCode;

    /**
     * Set Construct Report
     */
    public function __construct()
    {
        $this->load->helper('report');
        $this->load->model('company/company/mCompany');
        $this->load->model('report/report/mReport');
        $this->load->model('report/reportlocker/mRptRentAmountFollowTime');

        $this->init(); // Set Init Parameter Report (กำหนดค่าให้กับพารามิเตอร์เริ่มต้น)
        parent::__construct();
    }

    private function init()
    {
        // Set Default Text
        $this->aText = [
            'tTitleReport' => language('report/report/report', 'tRptTitleRentAmountFollowTime'),
            'tDatePrint' => language('report/report/report', 'tRptDatePrint'),
            'tTimePrint' => language('report/report/report', 'tRptTimePrint'),
            // Address Lang
            'tRptAddrBuilding' => language('report/report/report', 'tRptAddrBuilding'),
            'tRptAddrRoad' => language('report/report/report', 'tRptAddrRoad'),
            'tRptAddrSoi' => language('report/report/report', 'tRptAddrSoi'),
            'tRptAddrSubDistrict' => language('report/report/report', 'tRptAddrSubDistrict'),
            'tRptAddrDistrict' => language('report/report/report', 'tRptAddrDistrict'),
            'tRptAddrProvince' => language('report/report/report', 'tRptAddrProvince'),
            'tRptAddrTel' => language('report/report/report', 'tRptAddrTel'),
            'tRptAddrFax' => language('report/report/report', 'tRptAddrFax'),
            'tRptAddrBranch' => language('report/report/report', 'tRptAddrBranch'),
            'tRptAddV2Desc1' => language('report/report/report', 'tRptAddV2Desc1'),
            'tRptAddV2Desc2' => language('report/report/report', 'tRptAddV2Desc2'),
            // Filter Heard Report
            'tRptBchFrom' => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo' => language('report/report/report', 'tRptBchTo'),
            'tRptShopFrom' => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo' => language('report/report/report', 'tRptShopTo'),
            'tRptPosFrom' => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo' => language('report/report/report', 'tRptPosTo'),
            'tRptDateFrom' => language('report/report/report', 'tRptDateFrom'),
            'tRptDateTo' => language('report/report/report', 'tRptDateTo'),
            'tRptRackFrom' => language('report/report/report', 'tRptRackFrom'),
            'tRptRackTo' => language('report/report/report', 'tRptRackTo'),
            // No Data Report
            'tRptNoData' => language('common/main/main', 'tCMNNotFoundData'),
            // Table Label Report
            'tRptRentAmountFollowTimeSerailPos' => language('report/report/report', 'tRptRentAmountFollowTimeSerailPos'),
            'tRptRentAmtForFollowTimeTime' => language('report/report/report', 'tRptRentAmountFollowTimeTime'),
            'tRptRentAmtForFollowTimeNumBill' => language('report/report/report', 'tRptRentAmtForFollowTimeNumBill'),
            'tRptRentAmtForDetailStaPaymentNoPay' => language('report/report/report', 'tRptRentAmtForDetailStaPaymentNoPay'),
            'tRptRentAmtForDetailStaPaymentSome' => language('report/report/report', 'tRptRentAmtForDetailStaPaymentSome'),
            'tRptRentAmtForDetailStaPaymentAlready' => language('report/report/report', 'tRptRentAmtForDetailStaPaymentAlready'),
            'tRptRentAmtForDetailSumText' => language('report/report/report', 'tRptRentAmtForDetailSumText'),
            'tRptRentAmtForDetailSumTextLast' => language('report/report/report', 'tRptRentAmtForDetailSumTextLast'),

            //เพิ่มใหม่ 20/11/2019
            'tRPCTaxNo' => language('report/report/report', 'tRPCTaxNo'),
            
        ];

        $this->tBchCodeLogin = (!empty($this->session->userdata('tSesUsrBchCom')) ? $this->session->userdata('tSesUsrBchCom') : $this->session->userdata('tSesUsrBchCom'));
        $this->nPerPage = 50;
        $this->nOptDecimalShow = FCNxHGetOptionDecimalShow();

        $tIP = $this->input->ip_address();
        $tFullHost = gethostbyaddr($tIP);
        $this->tCompName = $tFullHost;

        $this->nLngID = FCNaHGetLangEdit();
        $this->tRptCode = $this->input->post('ohdRptCode');
        $this->tRptGroup = $this->input->post('ohdRptGrpCode');
        $this->tUserSessionID = $this->session->userdata('tSesSessionID');
        $this->tRptRoute = $this->input->post('ohdRptRoute');
        $this->tRptExportType = $this->input->post('ohdRptTypeExport');
        $this->nPage = empty($this->input->post('ohdRptCurrentPage')) ? 1 : $this->input->post('ohdRptCurrentPage');
        $this->tUserLoginCode = $this->session->userdata('tSesUsername');

        // Report Filter Data
        $this->aRptFilter = [
            'tSessionID' => $this->tUserSessionID,
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'nLangID' => $this->nLngID,
            // Filter Branch (สาขา)
            'tBchCodeFrom' => empty($this->input->post('oetRptBchCodeFrom')) ? '' : $this->input->post('oetRptBchCodeFrom'),
            'tBchNameFrom' => empty($this->input->post('oetRptBchNameFrom')) ? '' : $this->input->post('oetRptBchNameFrom'),
            'tBchCodeTo' => empty($this->input->post('oetRptBchCodeTo')) ? '' : $this->input->post('oetRptBchCodeTo'),
            'tBchNameTo' => empty($this->input->post('oetRptBchNameTo')) ? '' : $this->input->post('oetRptBchNameTo'),
            // Filter Shop (ร้านค้า)
            'tShopCodeFrom' => empty($this->input->post('oetRptShpCodeFrom')) ? '' : $this->input->post('oetRptShpCodeFrom'),
            'tShopNameFrom' => empty($this->input->post('oetRptShpNameFrom')) ? '' : $this->input->post('oetRptShpNameFrom'),
            'tShopCodeTo' => empty($this->input->post('oetRptShpCodeTo')) ? '' : $this->input->post('oetRptShpCodeTo'),
            'tShopNameTo' => empty($this->input->post('oetRptShpNameTo')) ? '' : $this->input->post('oetRptShpNameTo'),
            // Filter Pos (จุดขาย)
            'tPosCodeFrom' => empty($this->input->post('oetRptPosCodeFrom')) ? '' : $this->input->post('oetRptPosCodeFrom'),
            'tPosNameFrom' => empty($this->input->post('oetRptPosNameFrom')) ? '' : $this->input->post('oetRptPosNameFrom'),
            'tPosCodeTo' => empty($this->input->post('oetRptPosCodeTo')) ? '' : $this->input->post('oetRptPosCodeTo'),
            'tPosNameTo' => empty($this->input->post('oetRptPosNameTo')) ? '' : $this->input->post('oetRptPosNameTo'),
            // Filter Rack (ตู้สินค้า)
            'tRackCodeFrom' => empty($this->input->post('oetSMLBrowseGroupCodeFrom')) ? '' : $this->input->post('oetSMLBrowseGroupCodeFrom'),
            'tRackNameFrom' => empty($this->input->post('oetSMLBrowseGroupNameFrom')) ? '' : $this->input->post('oetSMLBrowseGroupNameFrom'),
            'tRackCodeTo' => empty($this->input->post('oetSMLBrowseGroupCodeTo')) ? '' : $this->input->post('oetSMLBrowseGroupCodeTo'),
            'tRackNameTo' => empty($this->input->post('oetSMLBrowseGroupNameTo')) ? '' : $this->input->post('oetSMLBrowseGroupNameTo'),
            // Filter Date (วันที่ออกเอกสาร)
            'tDocDateFrom' => empty($this->input->post('oetRptDocDateFrom')) ? '' : $this->input->post('oetRptDocDateFrom'),
            'tDocDateTo' => empty($this->input->post('oetRptDocDateTo')) ? '' : $this->input->post('oetRptDocDateTo'),
        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID' => $this->nLngID,
            'tBchCode' => $this->tBchCodeLogin
        ];
        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index()
    {
        if (!empty($this->tRptCode) && !empty($this->tRptExportType)) {
            // Execute Stored Procedure
            // $this->mRptRentAmountFollowTime->FSnMExecStoreReport($this->aRptFilter);
            // Count Rows
            $aCountRowParams = [
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tSessionID' => $this->tUserSessionID
            ];
            $this->nRows = $this->mRptRentAmountFollowTime->FSnMCountDataReportAll($aCountRowParams);
            // Switch Case Report Type
            switch ($this->tRptExportType) {
                case 'html':
                    $this->FSvCCallRptViewBeforePrint();
                    break;
                case 'excel':
                    $this->FSoCChkDataReportInTableTemp();
                    break;
                case 'pdf':
                    break;
            }
        }
    }

    /**
     * Functionality: ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 15/07/2019 Teerapap(Pap)
     * LastUpdate: 03/10/2019 Piya
     * Return: View Report Viewersd
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrint()
    {

        /*===== Begin Get Data =========================================================*/
        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataReportParams = [
            'nPerPage' => $this->nPerPage,
            'nPage' => $this->nPage,
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        ];
        $aDataReport = $this->mRptRentAmountFollowTime->FSaMGetDataReport($aDataReportParams);
        $aInforTB = array();
        $aData = $aDataReport["aRptData"]["aData"];
        for ($nI = 0; $nI < count($aData); $nI++) { // loop get data tb info
            if ($aData[$nI]) {
                for ($nJ = 0; $nJ < count($aData[$nI]); $nJ++) {
                    array_push($aInforTB, $aData[$nI][$nJ]);
                }
            }
        }
        /*===== End Get Data ===========================================================*/

        /*===== Begin Render View ======================================================*/
        // Load View Advance Table
        $aDataViewRptParams = [
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo' => $this->aCompanyInfo,
            'aDataTextRef' => $this->aText,
            'aDataFilter' => $this->aRptFilter,
            'aDataReport' => $aDataReport,
            'aPagination' => $aDataReport["aPagination"],
            'aSumData' => $aDataReport["aRptData"]["aSumData"],
            'aInforTB' => $aInforTB,

            'aDataTimeRef' => [
                "06:00-06:59",
                "07:00-07:59",
                "08:00-08:59",
                "09:00-09:59",
                "10:00-10:59",
                "11:00-11:59",
                "12:00-12:59",
                "13:00-13:59",
                "14:00-14:59",
                "15:00-15:59",
                "16:00-16:59",
                "17:00-17:59",
                "18:00-18:59",
                "19:00-19:59",
                "20:00-20:59",
                "21:00-21:59",
                "22:00-22:59",
                "23:00-23:59"
            ]
        ];
        $tViewReportHtml = JCNoHLoadViewAdvanceTable('report/datasources/reportRentAmountFollowTime', 'wRptRentAmountFollowTime', $aDataViewRptParams);

        // Data Viewer Center Report
        $aDataViewerParams = [
            'tTitleReport' => $this->aText['tTitleReport'],
            'tRptTypeExport' => $this->tRptExportType,
            'tRptCode' => $this->tRptCode,
            'tRptRoute' => $this->tRptRoute,
            'tViewRenderKool' => $tViewReportHtml,
            'aDataFilter' => $this->aRptFilter,
            'aDataReport' => [
                'raItems' => $aDataReport['aRptData'],
                'rnAllRow' => $aDataReport['aPagination']['nTotalRecord'],
                'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                'rnAllPage' => $aDataReport['aPagination']['nTotalPage'],
                'rtCode' => '1',
                'rtDesc' => 'success',
            ]
        ];
        $this->load->view('report/report/wReportViewer', $aDataViewerParams);
        /*===== End Render View ========================================================*/
    }

    /**
     * Functionality: Click Page ดูตัวอย่างก่อนพิมพ์ (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 15/07/2019 Teerapap(Pap)
     * LastUpdate: 03/10/2019 Piya
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrintClickPage()
    {

        /*===== Begin Init Filter ======================================================*/
        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);
        /*===== End Init Filter ========================================================*/

        /*===== Begin Get Data =========================================================*/
        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataReportParams = [
            'nPerPage' => $this->nPerPage,
            'nPage' => $this->nPage,
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        ];
        $aDataReport = $this->mRptRentAmountFollowTime->FSaMGetDataReport($aDataReportParams);

        $aInforTB = [];
        $aData = $aDataReport["aRptData"]["aData"];
        for ($nI = 0; $nI < count($aData); $nI++) { // loop get data tb info
            if ($aData[$nI]) {
                for ($nJ = 0; $nJ < count($aData[$nI]); $nJ++) {
                    array_push($aInforTB, $aData[$nI][$nJ]);
                }
            }
        }
        /*===== End Get Data ===========================================================*/

        /*===== Begin Render View ======================================================*/
        // Load View Advance Table
        $aDataViewRptParams = [
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo' => $this->aCompanyInfo,
            'aDataTextRef' => $this->aText,
            'aDataFilter' => $this->aRptFilter,
            'aDataReport' => $aDataReport,
            'aPagination' => $aDataReport["aPagination"],
            'aSumData' => $aDataReport["aRptData"]["aSumData"],
            'aInforTB' => $aInforTB,

            'aDataTimeRef' => [
                "06:00-06:59",
                "07:00-07:59",
                "08:00-08:59",
                "09:00-09:59",
                "10:00-10:59",
                "11:00-11:59",
                "12:00-12:59",
                "13:00-13:59",
                "14:00-14:59",
                "15:00-15:59",
                "16:00-16:59",
                "17:00-17:59",
                "18:00-18:59",
                "19:00-19:59",
                "20:00-20:59",
                "21:00-21:59",
                "22:00-22:59",
                "23:00-23:59"
            ]
        ];
        $tViewReportHtml = JCNoHLoadViewAdvanceTable('report/datasources/reportRentAmountFollowTime', 'wRptRentAmountFollowTime', $aDataViewRptParams);

        // Data Viewer Center Report
        $aDataViewerParams = [
            'tTitleReport' => $this->aText['tTitleReport'],
            'tRptTypeExport' => $this->tRptExportType,
            'tRptCode' => $this->tRptCode,
            'tRptRoute' => $this->tRptRoute,
            'tViewRenderKool' => $tViewReportHtml,
            'aDataFilter' => $aDataFilter,
            'aDataReport' => [
                'raItems' => $aDataReport['aRptData'],
                'rnAllRow' => $aDataReport['aPagination']['nTotalRecord'],
                'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                'rnAllPage' => $aDataReport['aPagination']['nTotalPage'],
                'rtCode' => '1',
                'rtDesc' => 'success'
            ]
        ];
        $this->load->view('report/report/wReportViewer', $aDataViewerParams);
        /*===== End Render View ========================================================*/
    }

    /**
     * Functionality: Count Data Report In DB Temp
     * Parameters:  Function Parameter
     * Creator: 15/07/2019 Teerapap(Pap)
     * LastUpdate: 03/10/2019 Piya
     * Return: Object Status Count Data Report
     * ReturnType: Object
     */
    public function FSoCChkDataReportInTableTemp()
    {
        $aCountRowParams = [
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'tSessionID' => $this->tUserSessionID,
        ];
        $nDataCountPage = $this->mRptRentAmountFollowTime->FSnMCountDataReportAll($aCountRowParams);
        $aResponse = [
            'nCountPageAll' => $nDataCountPage,
            'nStaEvent' => 1,
            'tMessage' => 'Success Count Data All'
        ];
        echo json_encode($aResponse);
    }

    /**
     * Functionality: Send Rabbit MQ Report Excel
     * Parameters:  Function Parameter
     * Creator: 15/07/2019 Teerapap(Pap)
     * LastUpdate: 03/10/2019 Piya
     * Return: object Send Rabbit MQ Report
     * ReturnType: Object
     */
    public function FSvCCallRptExportFile()
    {
        try {
            $dDateSendMQ = date('Y-m-d');
            $dTimeSendMQ = date('H:i:s');
            $dDateSubscribe = date('Ymd');
            $dTimeSubscribe = date('His');

            // Set QueueName 
            $tRptQueueName = 'RPT_' . $this->tRptGroup . '_' . $this->tRptCode;
            // Data Params Report
            $aDataSendMQ = [
                'tQueueName' => $tRptQueueName,
                'aParams' => [
                    'ptRptCode' => $this->tRptCode,
                    'pnPerFile' => 20000,
                    'ptUserCode' => $this->tUserLoginCode,
                    'ptUserSessionID' => $this->tUserSessionID,
                    'pnLngID' => $this->nLngID,
                    'ptFilter' => $this->aRptFilter,
                    'ptRptExpType' => $this->tRptExportType,
                    'ptComName' => $this->tCompName,
                    'ptDate' => $dDateSendMQ,
                    'ptTime' => $dTimeSendMQ,
                    'ptBchCode' => (!empty($this->session->userdata('tSesUsrBchCom')) ? $this->session->userdata('tSesUsrBchCom') : $this->session->userdata('tSesUsrBchCom'))
                ]
            ];
            FCNxReportCallRabbitMQ($aDataSendMQ);

            $aResponse = array(
                'nStaEvent' => 1,
                'tMessage' => 'Success Send Rabbit MQ.',
                'aDataSubscribe' => array(
                    'ptComName' => $this->tCompName,
                    'ptRptCode' => $this->tRptCode,
                    'ptUserCode' => $this->tUserLoginCode,
                    'ptUserSessionID' => $this->tUserSessionID,
                    'pdDateSubscribe' => $dDateSubscribe,
                    'pdTimeSubscribe' => $dTimeSubscribe,
                )
            );
        } catch (Exception $Error) {
            $aResponse = array(
                'nStaEvent' => 500,
                'tMessage' => 'Error Cannot Send Data Rabbit MQ  Report Sale Shop Group. !!!'
            );
        }
        echo json_encode($aResponse);
    }
}
