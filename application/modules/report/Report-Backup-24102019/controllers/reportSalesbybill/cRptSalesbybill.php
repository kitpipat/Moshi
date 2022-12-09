<?php

defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

class cRptSalesbybill extends MX_Controller {

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

    public function __construct() {
        $this->load->helper('report');
        $this->load->model('company/company/mCompany');
        $this->load->model('report/report/mReport');
        $this->load->model('report/reportSalesbybill/mRptSalesbybill');

        // Init Report
        $this->init();

        parent::__construct();
    }

    private function init() {
        $this->aText = [
            'tTitleReport'        => language('report/report/report', 'tRptSalesbybillTitle'),
            'tDatePrint'          => language('report/report/report', 'tRptTaxSalePosDatePrint'),
            'tTimePrint'          => language('report/report/report', 'tRptTaxSalePosTimePrint'),
            // Address Lang
            'tRptAddrBuilding'    => language('report/report/report', 'tRptAddrBuilding'),
            'tRptAddrRoad'        => language('report/report/report', 'tRptAddrRoad'),
            'tRptAddrSoi'         => language('report/report/report', 'tRptAddrSoi'),
            'tRptAddrSubDistrict' => language('report/report/report', 'tRptAddrSubDistrict'),
            'tRptAddrDistrict'    => language('report/report/report', 'tRptAddrDistrict'),
            'tRptAddrProvince'    => language('report/report/report', 'tRptAddrProvince'),
            'tRptAddrTel'         => language('report/report/report', 'tRptAddrTel'),
            'tRptAddrFax'         => language('report/report/report', 'tRptAddrFax'),
            'tRptAddrBranch'      => language('report/report/report', 'tRptAddrBranch'),
            'tRptAddV2Desc1'      => language('report/report/report', 'tRptAddV2Desc1'),
            'tRptAddV2Desc2'      => language('report/report/report', 'tRptAddV2Desc2'),
            // Table Label
            'tRptTaxSalePosDocNo'             => language('report/report/report', 'tRptTaxSalePosDocNo'),
            'tRptTaxSalePosDocDate'           => language('report/report/report', 'tRptTaxSalePosDocDate'),
            'tRptTaxSalePosDateAndLocker'     => language('report/report/report', 'tRptTaxSalePosDateAndLocker'),
            'tRptTaxSalePosPayTypeAndDocRef'  => language('report/report/report', 'tRptTaxSalePosPayTypeAndDocRef'),
            'tRptTaxSalePosDocRef'            => language('report/report/report', 'tRptTaxSalePosDocRef'),
            'tRptTaxSalePosPayment'           => language('report/report/report', 'tRptTaxSalePosPayment'),
            'tRptTaxSalePosPaymentTotal'      => language('report/report/report', 'tRptTaxSalePosPaymentTotal'),
            'tRptTaxSalePosPosGrouping'       => language('report/report/report', 'tRptTaxSalePosPosGrouping'),
            // No Data Report
            'tRptTaxSalePosNoData'            => language('common/main/main', 'tCMNNotFoundData'),
            'tRptTaxSalePosTotalSub'          => language('report/report/report', 'tRptTaxSalePosTotalSub'),
            'tRptTaxSalePosTotalFooter'       => language('report/report/report', 'tRptTaxSalePosTotalFooter'),
            // Filter Text Label
            'tRptTaxSalePosFilterBchFrom'     => language('report/report/report', 'tRptTaxSalePosFilterBchFrom'),
            'tRptTaxSalePosFilterBchTo'       => language('report/report/report', 'tRptTaxSalePosFilterBchTo'),
            'tRptTaxSalePosFilterShopFrom'    => language('report/report/report', 'tRptTaxSalePosFilterShopFrom'),
            'tRptTaxSalePosFilterShopTo'      => language('report/report/report', 'tRptTaxSalePosFilterShopTo'),
            'tRptTaxSalePosFilterPosFrom'     => language('report/report/report', 'tRptTaxSalePosFilterPosFrom'),
            'tRptTaxSalePosFilterPosTo'       => language('report/report/report', 'tRptTaxSalePosFilterPosTo'),
            'tRptTaxSalePosFilterPayTypeFrom' => language('report/report/report', 'tRptTaxSalePosFilterPayTypeFrom'),
            'tRptTaxSalePosFilterPayTypeTo'   => language('report/report/report', 'tRptTaxSalePosFilterPayTypeTo'),
            'tRptTaxSalePosFilterDocDateFrom' => language('report/report/report', 'tRptTaxSalePosFilterDocDateFrom'),
            'tRptTaxSalePosFilterDocDateTo'   => language('report/report/report', 'tRptTaxSalePosFilterDocDateTo'),
            
            'tRptMerFrom'               => language('report/report/report', 'tRptMerFrom'),
            'tRptMerTo'                 => language('report/report/report', 'tRptMerTo'),
            'tRptShopFrom'              => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo'                => language('report/report/report', 'tRptShopTo'),
            'tRptPosFrom'               => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo'                 => language('report/report/report', 'tRptPosTo'),
            'tPdtCodeFrom'              => language('report/report/report', 'tPdtCodeFrom'),
            'tPdtCodeTo'                => language('report/report/report', 'tPdtCodeTo'),
            'tPdtCodeFrom'              => language('report/report/report', 'tPdtCodeFrom'),
            'tPdtGrpFrom'               => language('report/report/report', 'tPdtGrpFrom'),
            'tPdtGrpTo'                 => language('report/report/report', 'tPdtGrpTo'),
            'tPdtTypeFrom'              => language('report/report/report', 'tPdtTypeFrom'),
            'tPdtTypeTo'                => language('report/report/report', 'tPdtTypeTo'),
            'tRptAdjWahFrom'            => language('report/report/report', 'tRptAdjWahFrom'),
            'tRptAdjWahTo'              => language('report/report/report', 'tRptAdjWahTo'),
            
            
            // Text Label
            'tRptDocBill'               => language('report/report/report', 'tRptDocBill'),
            'tRptDate'                  => language('report/report/report', 'tRptDate'),
            'tRptProduct'               => language('report/report/report', 'tRptProduct'),
            'tRptCabinetnumber'         => language('report/report/report', 'tRptCabinetnumber'),
            'tRptPrice'                 => language('report/report/report', 'tRptPrice'),
            'tSales'                    => language('report/report/report', 'tSales'),
            'tDiscount'                 => language('report/report/report', 'tDiscount'),
            'tRptGrandtotal'            => '%' . language('report/report/report', 'tRptGrandtotal'),
            'tRptCapital'               => '%' . language('report/report/report', 'tRptCapital'),
            'tRptTaxSalePosTel'         => language('report/report/report', 'tRptTaxSalePosTel'),
            'tRptTax'                   => language('report/report/report', 'tRptTax'),
            'tRptTaxSalePosFax'         => language('report/report/report', 'tRptTaxSalePosFax'),
            'tRptGrand'                 => language('report/report/report', 'tRptGrand'),
            'tRptTaxSalePosDatePrint'   => language('report/report/report', 'tRptTaxSalePosDatePrint'),
            'tRptTaxSalePosTimePrint'   => language('report/report/report', 'tRptTaxSalePosTimePrint'),
            'tRptTaxSalePosBch'         => language('report/report/report', 'tRptTaxSalePosBch'),
            'tRptDataReportNotFound'    => language('report/report/report', 'tRptDataReportNotFound'),
            'tRptRentAmtFolCourSumText' => language('report/report/report', 'tRptRentAmtFolCourSumText'),
            'tRptSales'                 => language('report/report/report', 'tRptSales'),
            'tRptDisChg'                => language('report/report/report', 'tRptDisChg'),
            'tRptByBillTotal'           => language('report/report/report', 'tRptByBillTotal'),
            'tRptNoData'                => language('report/report/report', 'tRptNoData'),
        ];

        $this->tBchCodeLogin   = (!empty($this->session->userdata('tSesUsrBchCom')) ? $this->session->userdata('tSesUsrBchCom') : $this->session->userdata('tSesUsrBchCom'));
        $this->nPerPage        = 100;
        $this->nOptDecimalShow = FCNxHGetOptionDecimalShow();
        
        $tIP             = $this->input->ip_address();
        $tFullHost       = gethostbyaddr($tIP);
        $this->tCompName = $tFullHost;
        
        $this->nLngID         = FCNaHGetLangEdit();
        $this->tRptCode       = $this->input->post('ohdRptCode');
        $this->tRptGroup      = $this->input->post('ohdRptGrpCode');
        $this->tUserSessionID = $this->session->userdata('tSesSessionID');
        $this->tRptRoute      = $this->input->post('ohdRptRoute');
        $this->tRptExportType = $this->input->post('ohdRptTypeExport');
        $this->nPage          = empty($this->input->post('ohdRptCurrentPage')) ? 1 : $this->input->post('ohdRptCurrentPage');
        $this->tUserLoginCode = $this->session->userdata('tSesUsername');

        // Report Filter
        $this->aRptFilter = [
            'tUserSession' => $this->tUserSessionID,
            'tCompName' => $this->tCompName,
            'tRptCode'  => $this->tRptCode,
            'nLangID'   => $this->nLngID,

            //สาขา(Branch)
            'tBchCodeFrom'    => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom'    => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo'      => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo'      => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            
            //ร้านค้า
            'tRptShpCodeFrom' => !empty($this->input->post('oetRptShpCodeFrom')) ? $this->input->post('oetRptShpCodeFrom') : "",
            'tRptShpNameFrom' => !empty($this->input->post('oetRptShpNameFrom')) ? $this->input->post('oetRptShpNameFrom') : "",
            'tRptShpCodeTo'   => !empty($this->input->post('oetRptShpCodeTo')) ? $this->input->post('oetRptShpCodeTo') : "",
            'tRptShpNameTo'   => !empty($this->input->post('oetRptShpNameTo')) ? $this->input->post('oetRptShpNameTo') : "",

            //กลุ่มธุรกิจ
            'tRptMerCodeFrom' => !empty($this->input->post('oetRptMerCodeFrom')) ? $this->input->post('oetRptMerCodeFrom') : "",
            'tRptMerNameFrom' => !empty($this->input->post('oetRptMerNameFrom')) ? $this->input->post('oetRptMerNameFrom') : "",
            'tRptMerCodeTo'   => !empty($this->input->post('oetRptMerCodeTo')) ? $this->input->post('oetRptMerCodeTo') : "",
            'tRptMerNameTo'   => !empty($this->input->post('oetRptMerNameTo')) ? $this->input->post('oetRptMerNameTo') : "",
           
            //เครื่องจุดขาย
            'tRptPosCodeFrom' => !empty($this->input->post('oetRptPosCodeFrom')) ? $this->input->post('oetRptPosCodeFrom') : "",
            'tRptPosNameFrom' => !empty($this->input->post('oetRptPosNameFrom')) ? $this->input->post('oetRptPosNameFrom') : "",
            'tRptPosCodeTo'   => !empty($this->input->post('oetRptPosCodeTo')) ? $this->input->post('oetRptPosCodeTo') : "",
            'tRptPosNameTo'   => !empty($this->input->post('oetRptPosNameTo')) ? $this->input->post('oetRptPosNameTo') : "",
            
            //คลังสินค้า
            'tRptWahCodeFrom' => !empty($this->input->post('oetRptWahCodeFrom')) ? $this->input->post('oetRptWahCodeFrom') : "",
            'tRptWahNameFrom' => !empty($this->input->post('oetRptWahNameFrom')) ? $this->input->post('oetRptWahNameFrom') : "",
            'tRptWahCodeTo'   => !empty($this->input->post('oetRptWahCodeTo')) ? $this->input->post('oetRptWahCodeTo') : "",
            'tRptWahNameTo'   => !empty($this->input->post('oetRptWahNameTo')) ? $this->input->post('oetRptWahNameTo') : "",
            
            // วันที่เอกสาร(DocNo)
            'tDocDateFrom'        => !empty($this->input->post('oetRptDocDateFrom')) ? $this->input->post('oetRptDocDateFrom') : "",
            'tDocDateTo'          => !empty($this->input->post('oetRptDocDateTo')) ? $this->input->post('oetRptDocDateTo') : "",
        ];

        // ดึงข้อมูลบริษัทฯ
    $aCompInfoParams = [
            'nLngID'   => $this->nLngID,
            'tBchCode' => $this->tBchCodeLogin
        ];
        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index() {
        if (!empty($this->tRptCode) && !empty($this->tRptExportType)) {

            // Execute Stored Procedure
            $aReturn = $this->mRptSalesbybill->FSnMExecStoreReport($this->aRptFilter);
            // Count Rows
            $aCountRowParams = [
                'tCompName'       => $this->tCompName,
                'tRptCode'        => $this->tRptCode,
                'tSessionID'      => $this->tUserSessionID,
                'ptRptTypeExport' => $this->tRptExportType,
                'paDataFilter'    => $this->aRptFilter,
                'ptRptRoute'      => $this->tRptRoute,
            ];
            $this->nRows = $this->mRptSalesbybill->FSnMCountRowInTemp($aCountRowParams);

            // Report Type
            switch ($this->tRptExportType) {
                case 'html':
                    $this->FSvCCallRptViewBeforePrint();
                    break;
                case 'excel':
                    $this->FSoCChkDataReportInTableTemp();
                    break;
                case 'pdf':
                    $this->FSvCCallRptRenderExcel($aCountRowParams);
                    break;
            }
        }
    }

    /**
     * Functionality: ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 22/07/2019 Piya
     * LastUpdate: 02/10/2019 Saharat(Golf)
     * Return: View Report Viewersd
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrint() {
        try {
            /** ===== Begin Get Data ======================================== */
            // ดึงข้อมูลจากฐานข้อมูล Temp
            $aDataReportParams  = [
                'nPerPage'      => $this->nPerPage,
                'nPage'         => $this->nPage,
                'tCompName'     => $this->tCompName,
                'tRptCode'      => $this->tRptCode,
                'tUsrSessionID' => $this->tUserSessionID,
            ];
            $aDataReport = $this->mRptSalesbybill->FSaMGetDataReport($aDataReportParams);
            /** ===== End Get Data ========================================== */
            
            /** ===== Begin Render View ===================================== */
            // Load View Advance Table
            $aDataViewRptParams = [
                'nOptDecimalShow' => $this->nOptDecimalShow,
                'aCompanyInfo'    => $this->aCompanyInfo,
                'aDataReport'     => $aDataReport,
                'aDataTextRef'    => $this->aText,
                'aDataFilter'     => $this->aRptFilter
            ];
            $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportSalesbybill', 'wRptSalesbybillHtml', $aDataViewRptParams);

            // Data Viewer Center Report
            $aDataViewerParams = [
                'tTitleReport'     => $this->aText['tTitleReport'],
                'tRptTypeExport'   => $this->tRptExportType,
                'tRptCode'         => $this->tRptCode,
                'tRptRoute'        => $this->tRptRoute,
                'tViewRenderKool'  => $tRptView,
                'aDataFilter'      => $this->aRptFilter,
                'aDataReport'       => [
                    'raItems'       => $aDataReport['aRptData'],
                    'rnAllRow'      => $aDataReport['aPagination']['nTotalRecord'],
                    'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                    'rnAllPage'     => $aDataReport['aPagination']['nTotalPage'],
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                ]
            ];
            $this->load->view('report/report/wReportViewer', $aDataViewerParams);
            /** ===== End Render View ======================================= */
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    /**
     * Functionality: Click Page ดูตัวอย่างก่อนพิมพ์ (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 22/07/2019 Piya
     * LastUpdate: 02/10/2019 Saharat(Golf)
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrintClickPage() {

        /** ===== Begin Init Variable ======================================= */
        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);
        /** ===== End Init Variable ========================================= */
        
        /** ===== Begin Get Data ============================================ */
        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataReportParams  =  [
            'nPerPage'      => $this->nPerPage,
            'nPage'         => $this->nPage,
            'tCompName'     => $this->tCompName,
            'tRptCode'      => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        ];
        $aDataReport = $this->mRptSalesbybill->FSaMGetDataReport($aDataReportParams);
        /** ===== End Get Data ============================================== */
        
        /** ===== Begin Render View ========================================= */
        // Load View Advance Table
        $aDataViewRptParams   = array(
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo'    => $this->aCompanyInfo,
            'aDataReport'     => $aDataReport,
            'aDataTextRef'    => $this->aText,
            'aDataFilter'     => $aDataFilter
        );
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportSalesbybill', 'wRptSalesbybillHtml', $aDataViewRptParams);

        // Data Viewer Center Report
        $aDataViewerParams = array(
            'tTitleReport'    => $this->aText['tTitleReport'],
            'tRptTypeExport'  => $this->tRptExportType,
            'tRptCode'        => $this->tRptCode,
            'tRptRoute'       => $this->tRptRoute,
            'tViewRenderKool' => $tRptView,
            'aDataFilter'     => $aDataFilter,
            'aDataReport'       => array(
                'raItems'       => $aDataReport['aRptData'],
                'rnAllRow'      => $aDataReport['aPagination']['nTotalRecord'],
                'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                'rnAllPage'     => $aDataReport['aPagination']['nTotalPage'],
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            )
        );
        $this->load->view('report/report/wReportViewer', $aDataViewerParams);
        /** ===== End Render View =========================================== */
    }

    /**
     * Functionality: Click Page Report (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 22/07/2019 Piya
     * LastUpdate: -
     * Return: Object Status Count Data Report
     * ReturnType: Object
     */
    public function FSoCChkDataReportInTableTemp() {
        try {
            $aDataCountData  = [
                'tCompName'  => $this->tCompName,
                'tRptCode'   => $this->tRptCode,
                'tSessionID' => $this->tUserSessionID,
            ];

            $nDataCountPage = $this->mRptSalesbybill->FSnMCountRowInTemp($aDataCountData);

            $aResponse = array(
                'nCountPageAll' => $nDataCountPage,
                'nStaEvent'     => 1,
                'tMessage'      => 'Success Count Data All'
            );
        } catch (ErrorException $Error) {
            $aResponse = array(
                'nStaEvent' => 500,
                'tMessage'  => $Error->getMessage()
            );
        }
        echo json_encode($aResponse);
    }

    /**
     * Functionality: Send Rabbit MQ Report
     * Parameters:  Function Parameter
     * Creator: 22/07/2019 Piya
     * LastUpdate: 04/10/2019 Piya
     * Return: object Send Rabbit MQ Report
     * ReturnType: Object
     */
    public function FSvCCallRptExportFile() {
        try {
            $tDateSendMQ    = date('Y-m-d');
            $tTimeSendMQ    = date('H:i:s');
            $tDateSubscribe = date('Ymd');
            $tTimeSubscribe = date('His');

            // Set Parameter Send MQ
            $tRptQueueName = 'RPT_' . $this->tRptGroup . '_' .$this->tRptCode;

            $aDataSendMQ = [
                'tQueueName' => $tRptQueueName,
                'aParams'    => [
                    'ptRptCode'       => $this->tRptCode,
                    'pnPerFile'       => 20000,
                    'ptUserCode'      => $this->tUserLoginCode,
                    'ptUserSessionID' => $this->tUserSessionID,
                    'pnLngID'         => $this->nLngID,
                    'ptFilter'        => $this->aRptFilter,
                    'ptRptExpType'    => $this->tRptExportType,
                    'ptComName'       => $this->tCompName,
                    'ptDate'          => $tDateSendMQ,
                    'ptTime'          => $tTimeSendMQ,
                    'ptBchCode' => (!empty($this->session->userdata('tSesUsrBchCom')) ? $this->session->userdata('tSesUsrBchCom') : $this->session->userdata('tSesUsrBchCom'))
                ]
            ];

            FCNxReportCallRabbitMQ($aDataSendMQ);

            $aResponse = array(
                'nStaEvent' => 1,
                'tMessage'  => 'Success Send Rabbit MQ.',
                'aDataSubscribe'      => array(
                    'ptComName'       => $this->tCompName,
                    'ptRptCode'       => $this->tRptCode,
                    'ptUserCode'      => $this->tUserLoginCode,
                    'ptUserSessionID' => $this->tUserSessionID,
                    'pdDateSubscribe' => $tDateSubscribe,
                    'pdTimeSubscribe' => $tTimeSubscribe,
                )
            );
        } catch (Exception $Error) {
            $aResponse      = array(
                'nStaEvent' => 500,
                'tMessage'  => $Error->getMessage()
            );
        }
        echo json_encode($aResponse);
    }




    // Functionality: Function Count Data Report And Calcurate
    // Parameters:  Function Parameter
    // Creator: 10/10/2019 Saharat(Golf)
    // LastUpdate: -
    // Return: View Report Viewer
    // ReturnType: View
    public function FSvCCallRptRenderExcel($paDataSwitchCase) {
        try{
            $tRptRoute      = $paDataSwitchCase['ptRptRoute'];
            $tRptCode       = $paDataSwitchCase['tRptCode'];
            $tRptTypeExport = $paDataSwitchCase['ptRptTypeExport'];
            $aDataFilter    = $paDataSwitchCase['paDataFilter'];
            $nPage = 1;
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $tSesUsername   = $this->session->userdata('tSesUsername');
            $tUsrSessionID  = $this->session->userdata('tSesSessionID');

            // Get data Company
            $aDataAddress = array();
            $tAPIReq = "";
            $tMethodReq = "GET";
            $aDataWhereComp = array('FNLngID' => $nLangEdit);
            $aCompData = $this->mCompany->FSaMCMPList($tAPIReq, $tMethodReq, $aDataWhereComp);
            $aDataAddress = $this->aCompanyInfo;

            // if ($aCompData['rtCode'] == '1') {
            //     $tCompName = $aCompData['raItems']['rtCmpName'];
            //     $tBchCode = $aCompData['raItems']['rtCmpBchCode'];
            //     $aDataAddress = $this->mReport->FSaMRptGetDataAddressByBranchComp($tBchCode, $nLangEdit);
            // } else {
            //     $tCompName = "-";
            //     $tBchCode = "-";
            //     $aDataAddress = array();
            // }


            $aDataTextRef = array(
                'tTitleReport'        => language('report/report/report', 'tRptSalesbybillTitle'),
                'tDatePrint'          => language('report/report/report', 'tRptTaxSalePosDatePrint'),
                'tTimePrint'          => language('report/report/report', 'tRptTaxSalePosTimePrint'),
                // Address Lang
                'tRptAddrBuilding'    => language('report/report/report', 'tRptAddrBuilding'),
                'tRptAddrRoad'        => language('report/report/report', 'tRptAddrRoad'),
                'tRptAddrSoi'         => language('report/report/report', 'tRptAddrSoi'),
                'tRptAddrSubDistrict' => language('report/report/report', 'tRptAddrSubDistrict'),
                'tRptAddrDistrict'    => language('report/report/report', 'tRptAddrDistrict'),
                'tRptAddrProvince'    => language('report/report/report', 'tRptAddrProvince'),
                'tRptAddrTel'         => language('report/report/report', 'tRptAddrTel'),
                'tRptAddrFax'         => language('report/report/report', 'tRptAddrFax'),
                'tRptAddrBranch'      => language('report/report/report', 'tRptAddrBranch'),
                'tRptAddV2Desc1'      => language('report/report/report', 'tRptAddV2Desc1'),
                'tRptAddV2Desc2'      => language('report/report/report', 'tRptAddV2Desc2'),
                // Table Label
                'tRptTaxSalePosDocNo'             => language('report/report/report', 'tRptTaxSalePosDocNo'),
                'tRptTaxSalePosDocDate'           => language('report/report/report', 'tRptTaxSalePosDocDate'),
                'tRptTaxSalePosDateAndLocker'     => language('report/report/report', 'tRptTaxSalePosDateAndLocker'),
                'tRptTaxSalePosPayTypeAndDocRef'  => language('report/report/report', 'tRptTaxSalePosPayTypeAndDocRef'),
                'tRptTaxSalePosDocRef'            => language('report/report/report', 'tRptTaxSalePosDocRef'),
                'tRptTaxSalePosPayment'           => language('report/report/report', 'tRptTaxSalePosPayment'),
                'tRptTaxSalePosPaymentTotal'      => language('report/report/report', 'tRptTaxSalePosPaymentTotal'),
                'tRptTaxSalePosPosGrouping'       => language('report/report/report', 'tRptTaxSalePosPosGrouping'),
                // No Data Report
                'tRptTaxSalePosNoData'            => language('common/main/main', 'tCMNNotFoundData'),
                'tRptTaxSalePosTotalSub'          => language('report/report/report', 'tRptTaxSalePosTotalSub'),
                'tRptTaxSalePosTotalFooter'       => language('report/report/report', 'tRptTaxSalePosTotalFooter'),
                // Filter Text Label
                'tRptTaxSalePosFilterBchFrom'     => language('report/report/report', 'tRptTaxSalePosFilterBchFrom'),
                'tRptTaxSalePosFilterBchTo'       => language('report/report/report', 'tRptTaxSalePosFilterBchTo'),
                'tRptTaxSalePosFilterShopFrom'    => language('report/report/report', 'tRptTaxSalePosFilterShopFrom'),
                'tRptTaxSalePosFilterShopTo'      => language('report/report/report', 'tRptTaxSalePosFilterShopTo'),
                'tRptTaxSalePosFilterPosFrom'     => language('report/report/report', 'tRptTaxSalePosFilterPosFrom'),
                'tRptTaxSalePosFilterPosTo'       => language('report/report/report', 'tRptTaxSalePosFilterPosTo'),
                'tRptTaxSalePosFilterPayTypeFrom' => language('report/report/report', 'tRptTaxSalePosFilterPayTypeFrom'),
                'tRptTaxSalePosFilterPayTypeTo'   => language('report/report/report', 'tRptTaxSalePosFilterPayTypeTo'),
                'tRptTaxSalePosFilterDocDateFrom' => language('report/report/report', 'tRptTaxSalePosFilterDocDateFrom'),
                'tRptTaxSalePosFilterDocDateTo'   => language('report/report/report', 'tRptTaxSalePosFilterDocDateTo'),
                'tRptDateFrom'                    => language('report/report/report', 'tRptDateFrom'),
                'tDocDateFrom'                    => language('report/report/report', 'tDocDateFrom'),
                'tRptDateTo'                      => language('report/report/report', 'tRptDateTo'),
                'tDocDateTo'                      => language('report/report/report', 'tDocDateTo'),
                
                'tRptMerFrom'               => language('report/report/report', 'tRptMerFrom'),
                'tRptMerTo'                 => language('report/report/report', 'tRptMerTo'),
                'tRptShopFrom'              => language('report/report/report', 'tRptShopFrom'),
                'tRptShopTo'                => language('report/report/report', 'tRptShopTo'),
                'tRptPosFrom'               => language('report/report/report', 'tRptPosFrom'),
                'tRptPosTo'                 => language('report/report/report', 'tRptPosTo'),
                'tPdtCodeFrom'              => language('report/report/report', 'tPdtCodeFrom'),
                'tPdtCodeTo'                => language('report/report/report', 'tPdtCodeTo'),
                'tPdtCodeFrom'              => language('report/report/report', 'tPdtCodeFrom'),
                'tPdtGrpFrom'               => language('report/report/report', 'tPdtGrpFrom'),
                'tPdtGrpTo'                 => language('report/report/report', 'tPdtGrpTo'),
                'tPdtTypeFrom'              => language('report/report/report', 'tPdtTypeFrom'),
                'tPdtTypeTo'                => language('report/report/report', 'tPdtTypeTo'),
                'tRptAdjWahFrom'            => language('report/report/report', 'tRptAdjWahFrom'),
                'tRptAdjWahTo'              => language('report/report/report', 'tRptAdjWahTo'),
                
                
                // Text Label
                'tRptDocBill'               => language('report/report/report', 'tRptDocBill'),
                'tRptDate'                  => language('report/report/report', 'tRptDate'),
                'tRptProduct'               => language('report/report/report', 'tRptProduct'),
                'tRptCabinetnumber'         => language('report/report/report', 'tRptCabinetnumber'),
                'tRptPrice'                 => language('report/report/report', 'tRptPrice'),
                'tSales'                    => language('report/report/report', 'tSales'),
                'tDiscount'                 => language('report/report/report', 'tDiscount'),
                'tRptGrandtotal'            => '%' . language('report/report/report', 'tRptGrandtotal'),
                'tRptCapital'               => '%' . language('report/report/report', 'tRptCapital'),
                'tRptTaxSalePosTel'         => language('report/report/report', 'tRptTaxSalePosTel'),
                'tRptTax'                   => language('report/report/report', 'tRptTax'),
                'tRptTaxSalePosFax'         => language('report/report/report', 'tRptTaxSalePosFax'),
                'tRptGrand'                 => language('report/report/report', 'tRptGrand'),
                'tRptTaxSalePosDatePrint'   => language('report/report/report', 'tRptTaxSalePosDatePrint'),
                'tRptTaxSalePosTimePrint'   => language('report/report/report', 'tRptTaxSalePosTimePrint'),
                'tRptTaxSalePosBch'         => language('report/report/report', 'tRptTaxSalePosBch'),
                'tRptDataReportNotFound'    => language('report/report/report', 'tRptDataReportNotFound'),
                'tRptRentAmtFolCourSumText' => language('report/report/report', 'tRptRentAmtFolCourSumText'),
                'tRptSales'                 => language('report/report/report', 'tRptSales'),
                'tRptDisChg'                => language('report/report/report', 'tRptDisChg'),
                'tRptByBillTotal'           => language('report/report/report', 'tRptByBillTotal'),
                'tRptNoData'                => language('report/report/report', 'tRptNoData'),
            );

            $tReportName    =  $aDataTextRef['tTitleReport'];
            $dDateExport    = date('Y-m-d');
            $tTime          = date('H:i:s');

            $aCompInfoParams    = ['nLngID' => FCNaHGetLangEdit()];
            // $aCompData = FCNaGetCompanyInfo($aCompInfoParams);

           $aDataReportParams = [
                'nPerPage' => $this->nPerPage,
                'nPage' => $this->nPage,
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tUsrSessionID' => $this->tUserSessionID,
            ];
          
            $aDataReport = $this->mRptSalesbybill->FSaMGetDataReport($aDataReportParams);
  
             /** =============================================================================================== */
            // ตั้งค่า Font Style
            $aStyleRptFont              = array('font' => array('name' => 'TH Sarabun New'));
            $aStyleRptSizeTitleName     = array('font' => array('size' => 14));
            $aStyleRptSizeCompName      = array('font' => array('size' => 12));
            $aStyleRptSizeAddressFont   = array('font' => array('size' => 12));
            $aStyleRptHeadderTable      = array('font' => array('size' => 12, 'color' => array('rgb' => '000000')));
            $aStyleRptDataTable         = array('font' => array('size' => 10, 'color' => array('rgb' => '000000')));

            // Initiate PHPExcel cache
            $oCacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
            $aCacheSettings = array(' memoryCacheSize ' => '8000MB', 'cacheTime' => 3600 * 120);
            PHPExcel_Settings::setCacheStorageMethod($oCacheMethod, $aCacheSettings);

            // เริ่ม phpExcel
            $objPHPExcel = new PHPExcel();

            // A4 ตั้งค่าหน้ากระดาษ
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            // Set Font Style
            $objPHPExcel->getActiveSheet()->getStyle('A1:Z1000')->applyFromArray($aStyleRptFont);

            // จัดความกว้างของคอลัมน์
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);

            // ชื่อหัวรายงาน
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:I1');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $tReportName);
            $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($aStyleRptSizeTitleName);

            // Check Address Data
            if (isset($aDataAddress) && !empty($aDataAddress)) {
                // Company Name
                $tRptCompName = (empty($aDataAddress['FTCompName'])) ? '-' : $aDataAddress['FTCompName'];
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', $tRptCompName)->getStyle('A2')->applyFromArray($aStyleRptSizeCompName);

                // Check Vertion Address
                if ($aDataAddress['FTAddVersion'] == 1) {
                    // Check Address Line 1
                    $tRptAddV1No    = (empty($aDataAddress['FTAddV1No'])) ? '-' : $aDataAddress['FTAddV1No'];
                    $tRptAddV1Road  = (empty($aDataAddress['FTAddV1Road'])) ? '-' : $aDataAddress['FTAddV1Road'];
                    $tRptAddV1Soi   = (empty($aDataAddress['FTAddV1Soi'])) ? '-' : $aDataAddress['FTAddV1Soi'];
                    $tRptAddressLine1 = $tRptAddV1No . ' ' . $this->aText['tRptAddrRoad'] . ' ' . $tRptAddV1Road . ' ' . $this->aText['tRptAddrSoi'] . ' ' . $tRptAddV1Soi;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', $tRptAddressLine1)->getStyle('A3')->applyFromArray($aStyleRptSizeAddressFont);

                    // Check Address Line 2
                    $tRptAddV1SubDistName   = (empty($aDataAddress['FTSudName'])) ? '-' : $aDataAddress['FTSudName'];
                    $tRptAddV1DstName  = (empty($aDataAddress['FTDstName'])) ? '-' : $aDataAddress['FTDstName'];
                    $tRptAddV1PvnName  = (empty($aDataAddress['FTPvnName'])) ? '-' : $aDataAddress['FTPvnName'];
                    $tRptAddV1PostCode = (empty($aDataAddress['FTAddV1PostCode'])) ? '-' : $aDataAddress['FTAddV1PostCode'];
                    $tRptAddressLine2  = $tRptAddV1SubDistName . ' ' . $tRptAddV1DstName . ' ' . $tRptAddV1PvnName . ' ' . $tRptAddV1PostCode;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', $tRptAddressLine2)->getStyle('A4')->applyFromArray($aStyleRptSizeAddressFont);
                } else {
                    $tRptAddV2Desc1    = (empty($aDataAddress['FTAddV2Desc1'])) ? '-' : $aDataAddress['FTAddV2Desc1'];
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', $tRptAddV2Desc1)->getStyle('A3')->applyFromArray($aStyleRptSizeAddressFont);

                    $tRptAddV2Desc2 = (empty($aDataAddress['FTAddV2Desc2'])) ? '-' : $aDataAddress['FTAddV2Desc2'];
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', $tRptAddV2Desc2)->getStyle('A4')->applyFromArray($aStyleRptSizeAddressFont);
                }

                // Check Data Telephone Number
                if (isset($aDataAddress['FTCompTel']) && !empty($aDataAddress['FTCompTel'])) {
                    $tRptCompTel = $aDataAddress['FTCompTel'];
                } else {
                    $tRptCompTel = '-';
                }
                $tRptCompTelText = $this->aText['tRptAddrTel'] . ' ' . $tRptCompTel;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', $tRptCompTelText)->getStyle('A5')->applyFromArray($aStyleRptSizeAddressFont);

                // Check Data Fax Number
                if (isset($aDataAddress['FTCompFax']) && !empty($aDataAddress['FTCompFax'])) {
                    $tRptCompFax = $aDataAddress['FTCompFax'];
                } else {
                    $tRptCompFax = '-';
                }
                $tRptCompFaxText = $this->aText['tRptAddrFax'] . ' ' . $tRptCompFax;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A6', $tRptCompFaxText)->getStyle('A6')->applyFromArray($aStyleRptSizeAddressFont);
            }
            
            // Row เริ่มต้นของ Filter
            $nStartRowFillter = 2;
            $tFillterColumLEFT = "B";
            $tFillterColumRIGHT = "D";

                
            // Fillter Banch (สาขา)
            if (!empty($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeTo'])) {
                $tRptFilterRcvCodeFrom = $aDataTextRef['tRptTaxSalePosFilterBchFrom'] . ' ' . $aDataFilter['tBchNameFrom'];
                $tRptFilterRcvCodeTo = $aDataTextRef['tRptTaxSalePosFilterBchTo'] . ' ' . $aDataFilter['tBchNameTo'];
                $tRptTextLeftRightFilter = $tRptFilterRcvCodeFrom . ' ' . $tRptFilterRcvCodeTo;
                $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                $nStartRowFillter += 1;
            }

            // Fillter Shop (ร้านค้า)
            if (!empty($aDataFilter['tRptShpCodeFrom']) && !empty($aDataFilter['tRptShpCodeTo'])) {
                $tRptFilterRcvCodeFrom = $aDataTextRef['tRptShopFrom'] . ' ' . $aDataFilter['tRptShpNameFrom'];
                $tRptFilterRcvCodeTo = $aDataTextRef['tRptShopTo'] . ' ' . $aDataFilter['tRptShpNameTo'];
                $tRptTextLeftRightFilter = $tRptFilterRcvCodeFrom . ' ' . $tRptFilterRcvCodeTo;
                $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                $nStartRowFillter += 1;
            }

            // Fillter MerChant (กลุ่มธุรกิจ)
            if (!empty($aDataFilter['tRptMerCodeFrom']) && !empty($aDataFilter['tRptMerCodeFrom'])) {
                $tRptFilterRcvCodeFrom = $aDataTextRef['tRptMerFrom'] . ' ' . $aDataFilter['tRptMerNameFrom'];
                $tRptFilterRcvCodeTo = $aDataTextRef['tRptMerTo'] . ' ' . $aDataFilter['tRptMerNameTo'];
                $tRptTextLeftRightFilter = $tRptFilterRcvCodeFrom . ' ' . $tRptFilterRcvCodeTo;
                $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                $nStartRowFillter += 1;
            }

            // Fillter POS (เครื่องจุดขาย)
            if (!empty($aDataFilter['tRptPosCodeFrom']) && !empty($aDataFilter['tRptPosCodeTo'])) {
                $tRptFilterRcvCodeFrom = $aDataTextRef['tRptPosFrom'] . ' ' . $aDataFilter['tRptPosNameFrom'];
                $tRptFilterRcvCodeTo = $aDataTextRef['tRptPosTo'] . ' ' . $aDataFilter['tRptPosNameTo'];
                $tRptTextLeftRightFilter = $tRptFilterRcvCodeFrom . ' ' . $tRptFilterRcvCodeTo;
                $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                $nStartRowFillter += 1;
            }

            // Fillter (คลังสินค้า)
            if (!empty($aDataFilter['tRptWahCodeFrom']) && !empty($aDataFilter['tRptWahCodeTo'])) {
            $tRptFilterRcvCodeFrom = $aDataTextRef['tRptAdjWahFrom'] . ' ' . $aDataFilter['tRptWahNameFrom'];
            $tRptFilterRcvCodeTo = $aDataTextRef['tRptAdjWahTo'] . ' ' . $aDataFilter['tRptWahNameTo'];
            $tRptTextLeftRightFilter = $tRptFilterRcvCodeFrom . ' ' . $tRptFilterRcvCodeTo;
            $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

            $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
            $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
            $nStartRowFillter += 1;
            }

            
            // Fillter (วันที่)
            if (!empty($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateTo'])) {
                $tRptFilterRcvCodeFrom = $aDataTextRef['tRptTaxSalePosFilterDocDateFrom'] . ' ' . $aDataFilter['tDocDateFrom'];
                $tRptFilterRcvCodeTo = $aDataTextRef['tRptTaxSalePosFilterDocDateTo'] . ' ' . $aDataFilter['tDocDateTo'];
                $tRptTextLeftRightFilter = $tRptFilterRcvCodeFrom . ' ' . $tRptFilterRcvCodeTo;
                $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;
    
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                $nStartRowFillter += 1;
            }
        
    

                // ========================================================================== Date Time Print =========================================================================
                $tRptDateTimeExportText = $aDataTextRef['tDatePrint'] . ' ' . $dDateExport . ' ' . $aDataTextRef['tTimePrint'] . ' ' . $tTime;
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:F7');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A7', $tRptDateTimeExportText);
                $objPHPExcel->getActiveSheet()->getStyle("A7")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($aStyleRptSizeAddressFont);
                // ====================================================================================================================================================================


                // ==================================================================== หัวตารางรายงาน ==================================================================================
                // กำหนดจุดเริ่มต้นของแถวหัวตาราง 
                $nStartRowHeadder = 8;

                // กำหนด Style Font ของหัวตาราง
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':F' . $nStartRowHeadder)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':F' . $nStartRowHeadder)->applyFromArray($aStyleRptHeadderTable);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':F' . $nStartRowHeadder)->applyFromArray(array(
                    'borders' => array(
                        'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                        'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))
                    )
                ));

                // กำหนดข้อมูลลงหัวตาราง
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $nStartRowHeadder, $aDataTextRef['tRptDocBill'])
                ->setCellValue('B' . $nStartRowHeadder, $aDataTextRef['tRptDate'])
                ->setCellValue('C' . $nStartRowHeadder, $aDataTextRef['tRptSales'])
                ->setCellValue('D' . $nStartRowHeadder, $aDataTextRef['tRptDisChg'])
                ->setCellValue('E' . $nStartRowHeadder, $aDataTextRef['tRptTax'])
                ->setCellValue('F' . $nStartRowHeadder, $aDataTextRef['tRptGrand']);

                // Alignment ของหัวตาราง
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':I' . $nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                // $objPHPExcel->getActiveSheet()->getStyle('C' . $nStartRowHeadder . ':F' . $nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                 // Set Variable Data
            $nStartRowData = $nStartRowHeadder + 1;
            if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
 
                // ******* Step 1 ประกาศตัวแปรใช้ในการเช็คเงื่อนไข
                $tGrouppingData = "";
                $nGroupMember   = "";
                $nRowPartID     = "";

                $nXshAmtNV_Footer        = 0;
                $nXshDis_Footer          = 0;
                $nXshVat_Footer          = 0;
                $nXshGrand_Footer        = 0;

                foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                   
                    // ******* Step 3 Set ค่า Value FNRptGroupMember And FNRowPartID
                    $nGroupMember = $aValue["FNRptGroupMember"];
                    $nRowPartID = $aValue["FNRowPartID"];

                    // ******* Step 4 : Check Groupping Create Row Groupping 
                    // if ($tGrouppingData != $aValue['FTXshDocNo']) {
                    //     $tTextLabelGroupping = $aValue['FTXshDocNo'];
                    //     $tTextLabelGrouppingDocDate = $aValue['FDXshDocDate'];
                    //     // $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData);
                    //     $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C' . $nStartRowData . ':I' . $nStartRowData);
                    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $tTextLabelGroupping);
                    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $nStartRowData, $tTextLabelGrouppingDocDate);
                    //     $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    //     $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
                    //     $nStartRowData++;
                    // }

                    // ******* Step 5 : Loop Set Data Value 
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $nStartRowData, $aValue["FTXshDocNo"])
                            ->setCellValue('B' . $nStartRowData, $aValue["FDXshDocDate"])
                            ->setCellValue('C' . $nStartRowData, number_format($aValue["FCXshAmtNV"],2))
                            ->setCellValue('D' . $nStartRowData, number_format($aValue["FCXshDis"],2))
                            ->setCellValue('E' . $nStartRowData, number_format($aValue["FCXshVat"],2))
                            ->setCellValue('F' . $nStartRowData, number_format($aValue["FCXshGrand"],2));
                    
                    $objPHPExcel->getActiveSheet()->getStyle('C' . $nStartRowData . ':F' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':B' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('C' . $nStartRowData . ':F' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    // // ******* Step 6 : เตรียม Parameter สำหรับ Summary Sub Footer
                    // if ($nRowPartID == '0') {
                    //     $nStartRowData++;
                    //     $nXsdQty                = number_format($aValue["FCXsdQty_SUM"],2);
                    //     $nFCXsdDis              = number_format($aValue["FCXsdDis_SUM"],2);
                    //     $nXsdAmtB4DisChg        = number_format($aValue["FCXsdAmtB4DisChg_SUM"],2);
                    //     $nXsdSetPrice           = number_format($aValue["FCXsdSetPrice_SUM"],2);
                    //     $nXsdVat                = number_format($aValue["FCXsdVat_SUM"],2);     
                    //     $nXsdNetAfHD            = number_format($aValue["FCXsdNetAfHD_SUM"],2);   

                    //     // Set Color Row Sub Footer
                    //     $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':F' . $nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                    //     $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':F' . $nStartRowData)->applyFromArray(array(
                    //         'borders' => array(
                    //             // 'top'       => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')),
                    //             'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))
                    //         )
                    //     ));

                    //     // LEFT 
                    //     $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':C' . $nStartRowData);
                    //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $aDataTextRef['tRptRentAmtFolCourSumText']);
                    //     $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    //     $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);

                    //     // RIGHT
                    //     $objPHPExcel->setActiveSheetIndex(0)
                    //             ->setCellValue('D' . $nStartRowData, $nXsdQty)
                    //             ->setCellValue('E' . $nStartRowData, $nXsdSetPrice)
                    //             ->setCellValue('F' . $nStartRowData, $nXsdAmtB4DisChg)
                    //             ->setCellValue('G' . $nStartRowData, $nFCXsdDis)
                    //             ->setCellValue('H' . $nStartRowData, $nXsdVat)
                    //             ->setCellValue('I' . $nStartRowData, $nXsdNetAfHD);
                    // $objPHPExcel->getActiveSheet()->getStyle('D' . $nStartRowData . ':I' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                               
                        $nStartRowData++;
                    // }
                    
                    $nXshAmtNV_Footer         = number_format($aValue["FCXshAmtNV_Footer"],2);
                    $nXshDis_Footer           = number_format($aValue["FCXshDis_Footer"],2);
                    $nXshVat_Footer           = number_format($aValue["FCXshVat_Footer"],2);
                    $nXshGrand_Footer         = number_format($aValue["FCXshGrand_Footer"],2);  
                    
                    // ******* Step 2 Set ค่า Value ให้กับตัวแปร
                    // $tGrouppingData = $aValue["FTXshDocNo"];
                    // $nStartRowData++;
                }

                // Step 7 : Set Footer Text
                $nPageNo = $aDataReport["aPagination"]["nDisplayPage"];
                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                if ($nPageNo == $nTotalPage) {
                    $nStartRowData--;
                    // Set Color Row Sub Footer
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':F' . $nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':F' . $nStartRowData)->applyFromArray(array(
                        'borders' => array(
                            'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                            'bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE, 'color' => array('rgb' => '000000'))
                        )
                    ));

                    // LEFT 
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':B' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $aDataTextRef['tRptByBillTotal']);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);

                    // RIGHT
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('C' . $nStartRowData, $nXshAmtNV_Footer)
                            ->setCellValue('D' . $nStartRowData, $nXshDis_Footer)
                            ->setCellValue('E' . $nStartRowData, $nXshVat_Footer)
                            ->setCellValue('F' . $nStartRowData, $nXshGrand_Footer);
                            $objPHPExcel->getActiveSheet()->getStyle('C' . $nStartRowData . ':F' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                             $objPHPExcel->getActiveSheet()->getStyle('C' . $nStartRowData . ':F' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                }
            } else {
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':F' . $nStartRowData);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $aDataTextRef['tRptNoData']);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
            }






            //  ======================================================= Set Content Type Export File Excel =======================================================
            $tFilename = 'Report-' . $tRptCode . date("dmYhis") . '.xlsx';

            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=$tFilename");
            header("Content-Transfer-Encoding: binary");

            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

            if (!is_dir(APPPATH . 'modules/report/assets/exportexcel/')) {
                mkdir(APPPATH . 'modules/report/assets/exportexcel/');
            }

            if (!is_dir(APPPATH . 'modules/report/assets/exportexcel/' . $tRptCode)) {
                mkdir(APPPATH . 'modules/report/assets/exportexcel/' . $tRptCode);
            }

            if (!is_dir(APPPATH . 'modules/report/assets/exportexcel/' . $tRptCode . '/' . $tSesUsername)) {
                mkdir(APPPATH . 'modules/report/assets/exportexcel/' . $tRptCode . '/' . $tSesUsername);
            }

            $tPathExport = APPPATH . 'modules/report/assets/exportexcel/' . $tRptCode . '/' . $tSesUsername . '/';
            $oFiles = glob($tPathExport . '*');
            foreach ($oFiles as $tFile) {
                if (is_file($tFile))
                    unlink($tFile);
            }

            // Check Status Save File Excel
            $objWriter->save($tPathExport . $tFilename);
            $aResponse = array(
                'nStaExport' => 1,
                'tFileName' => $tFilename,
                'tPathFolder' => 'application/modules/report/assets/exportexcel/' . $tRptCode . '/' . $tSesUsername . '/',
                'tMessage' => "Export File Successfully."
            );
           

        }catch(Exception $Error){
           $aResponse   = array(
               'nStaExport' => 500,
               'tMessage'   => $Error->getMessage()
           );
        }
        echo json_encode($aResponse);
    }




}





