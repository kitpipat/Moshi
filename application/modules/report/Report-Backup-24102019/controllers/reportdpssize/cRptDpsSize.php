<?php

defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

class cRptDpsSize extends MX_Controller {

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
        $this->load->model('company/company/mCompany');
        $this->load->model('report/reportdpssize/mRptDpsSize');
        $this->load->model('report/report/mReport');

        // Init Report
        $this->init();
        parent::__construct();
    }

    private function init() {
        $this->aText = [
            'tTitleReport' => language('report/report/report', 'tRptTitleDpsSize'),
            'tRptTaxNo' => language('report/report/report', 'tRptTaxNo'),
            'tRptDatePrint' => language('report/report/report', 'tRptDatePrint'),
            'tRptDateExport' => language('report/report/report', 'tRptDateExport'),
            'tRptTimePrint' => language('report/report/report', 'tRptTimePrint'),
            'tRptPrintHtml' => language('report/report/report', 'tRptPrintHtml'),
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
            // Table Report
            'tRptBarchCode' => language('report/report/report', 'tRptBarchCode'),
            'tRptBarchName' => language('report/report/report', 'tRptBarchName'),
            'tRptDocDate' => language('report/report/report', 'tRptDocDate'),
            'tRptShopCode' => language('report/report/report', 'tRptShopCode'),
            'tRptShopName' => language('report/report/report', 'tRptShopName'),
            'tRptAmount' => language('report/report/report', 'tRptAmount'),
            'tRptSale' => language('report/report/report', 'tRptSale'),
            'tRptCancelSale' => language('report/report/report', 'tRptCancelSale'),
            'tRptTotalSale' => language('report/report/report', 'tRptTotalSale'),
            'tRptTotalAllSale' => language('report/report/report', 'tRptTotalAllSale'),
            'tRptPayby' => language('report/report/report', 'tRptPayby'),
            'tRptRcvDocumentCode' => language('report/report/report', 'tRptRcvDocumentCode'),
            'tRptDate' => language('report/report/report', 'tRptDate'),
            'tRptRcvTotal' => language('report/report/report', 'tRptRcvTotal'),
            // No Data Report
            'tRptAdjStkNoData' => language('common/main/main', 'tCMNNotFoundData'),
        ];

        $this->tBchCodeLogin = (!empty($this->session->userdata('tSesUsrBchCom')) ? $this->session->userdata('tSesUsrBchCom') : $this->session->userdata('tSesUsrBchCom'));
        $this->nPerPage = 100;
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

        // Report Filter
        $this->aRptFilter = [
            'tUserSession' => $this->tUserSessionID,
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'nLangID' => $this->nLngID,
            // สาขา
            'tBchCodeFrom' => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom' => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo' => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo' => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            // ร้านค้า
            'tShopCodeFrom' => !empty($this->input->post('oetRptShpCodeFrom')) ? $this->input->post('oetRptShpCodeFrom') : "",
            'tShopNameFrom' => !empty($this->input->post('oetRptShpNameFrom')) ? $this->input->post('oetRptShpNameFrom') : "",
            'tShopCodeTo' => !empty($this->input->post('oetRptShpCodeTo')) ? $this->input->post('oetRptShpCodeTo') : "",
            'tShopNameTo' => !empty($this->input->post('oetRptShpNameTo')) ? $this->input->post('oetRptShpNameTo') : "",
            // จุดขาย
            'tPosCodeFrom' => !empty($this->input->post('oetRptPosCodeFrom')) ? $this->input->post('oetRptPosCodeFrom') : "",
            'tPosNameFrom' => !empty($this->input->post('oetRptPosNameFrom')) ? $this->input->post('oetRptPosNameFrom') : "",
            'tPosCodeTo' => !empty($this->input->post('oetRptPosCodeTo')) ? $this->input->post('oetRptPosCodeTo') : "",
            'tPosNameTo' => !empty($this->input->post('oetRptPosNameTo')) ? $this->input->post('oetRptPosNameTo') : "",
            // วันที่ เอกสาร
            'tDocDateFrom' => !empty($this->input->post('oetRptDocDateFrom')) ? $this->input->post('oetRptDocDateFrom') : "",
            'tDocDateTo' => !empty($this->input->post('oetRptDocDateTo')) ? $this->input->post('oetRptDocDateTo') : "",
        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID' => $this->nLngID,
            'tBchCode' => $this->tBchCodeLogin
        ];

        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index() {

        if (!empty($this->tRptCode) && !empty($this->tRptExportType)) {
            // Execute Stored Procedure
            $tResult = $this->mRptDpsSize->FSnMExecStoreReport($this->aRptFilter);

            $aDataSwitchCase = array(
                'ptRptRoute' => $this->tRptRoute,
                'ptRptCode' => $this->tRptCode,
                'ptRptTypeExport' => $this->tRptExportType,
                'paDataFilter' => $this->aRptFilter
            );

            switch ($this->tRptExportType) {
                case 'html':
                    $this->FSvCCallRptViewBeforePrint($aDataSwitchCase);
                    break;
                case 'excel':
                    $this->FSoCChkDataReportInTableTemp($aDataSwitchCase);
                    break;
            }
        }
    }
 
    /**
     * Functionality: ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 10/07/2019 Saharat(Golf)
     * LastUpdate: 04/10/2019 Piya
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrint($paDataSwitchCase) {
        try {
            /** =========== Begin Get Data =================================== */
            // ดึงข้อมูลจากฐานข้อมูล Temp
            $aDataReportParams = [
                'tUsrSessionID' => $this->tUserSessionID,
                'tCompName' => $this->tCompName,
                'nPerPage' => $this->nPerPage,
                'nPage' => $this->nPage,
                'tRptCode' => $this->tRptCode,
            ];
            $aDataReport = $this->mRptDpsSize->FSaMGetDataReport($aDataReportParams);
            /** =========== End Get Data ===================================== */
            /** =========== Begin Render View ================================ */
            // Load View Advance Table
            $aDataViewRptParams = [
                'nOptDecimalShow' => $this->nOptDecimalShow,
                'aCompanyInfo' => $this->aCompanyInfo,
                'aDataReport' => $aDataReport,
                'aDataTextRef' => $this->aText,
                'aDataFilter' => $this->aRptFilter
            ];
            // Load View Advance Table
            $tViewTest = JCNoHLoadViewAdvanceTable('report/datasources/rptDpsSize', 'wRptDpsSizeHtml', $aDataViewRptParams);

            // Data Viewer Center Report
            $aDataViewerParams = [
                'tTitleReport' => $this->aText['tTitleReport'],
                'tRptTypeExport' => $this->tRptExportType,
                'tRptCode' => $this->tRptCode,
                'tRptRoute' => $this->tRptRoute,
                'tViewRenderKool' => $tViewTest,
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
            /** =========== End Render View ================================== */
        } catch (Exception $Error) {
            echo $Error;
        }
    }
    
    /**
     * Functionality: Click Page ดูตัวอย่างก่อนพิมพ์ (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 19/07/2019 Wasin(Yoshi)
     * LastUpdate: 04/10/2019 Piya
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrintClickPage() {
        
        /** ===== Begin Init Variable ======================================= */
        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);
        /** ===== End Init Variable ========================================= */
        
        /** ===== Begin Get Data ============================================ */
        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataReportParams = [
            'nPerPage' => $this->nPerPage,
            'nPage' => $this->nPage,
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        ];

        $aDataReport = $this->mRptDpsSize->FSaMGetDataReport($aDataReportParams);
        /** ===== End Get Data ============================================== */
        
        /** ===== Begin Render View ========================================= */
        // Load View Advance Table
        $aDataViewRptParams = array(
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo' => $this->aCompanyInfo,
            'aDataReport' => $aDataReport,
            'aDataTextRef' => $this->aText,
            'aDataFilter' => $aDataFilter
        );
        $tViewTest = JCNoHLoadViewAdvanceTable('report/datasources/rptDpsSize', 'wRptDpsSizeHtml', $aDataViewRptParams);

        // Data Viewer Center Report
        $aDataViewerParams = array(
            'tTitleReport' => $this->aText['tTitleReport'],
            'tRptTypeExport' => $this->tRptExportType,
            'tRptCode' => $this->tRptCode,
            'tRptRoute' => $this->tRptRoute,
            'tViewRenderKool' => $tViewTest,
            'aDataFilter' => $aDataFilter,
            'aDataReport' => array(
                'raItems' => $aDataReport['aRptData'],
                'rnAllRow' => $aDataReport['aPagination']['nTotalRecord'],
                'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                'rnAllPage' => $aDataReport['aPagination']['nTotalPage'],
                'rtCode' => '1',
                'rtDesc' => 'success'
            )
        );
        $this->load->view('report/report/wReportViewer', $aDataViewerParams);
        /** ===== End Render View =========================================== */
    }

    // Functionality: Function Render Report Excel
    // Parameters:  Function Parameter
    // Creator: 12/08/2019 Sarun
    // LastUpdate: -
    // Return: View Report Viewer
    // ReturnType: View
    private function FSvCCallRptRenderExcel($paDataMain) {
        try {
            // เตรียมข้อมูลออกรายงาน =================================================================================================================================================
            $tRptRoute = $paDataMain['ptRptRoute'];
            $tRptCode = $paDataMain['ptRptCode'];
            $tRptTypeExport = $paDataMain['ptRptTypeExport'];
            $aDataFilter = $paDataMain['paDataFilter'];
            $nPage = 1;
            $nLangEdit = $this->session->userdata("tLangEdit");
            $tSesUsername = $this->session->userdata('tSesUsername');
            $tUsrSessionID = $this->session->userdata('tSesSessionID');
            $dDatePrint = date('Y-m-d');
            $dTimePrint = date('H:i:s');

            // Check Filter Merchant And 
            $tAPIReq = "";
            $tMethodReq = "GET";
            $aDataWhereComp = array('FNLngID' => $nLangEdit);
            $aCompData = $this->mCompany->FSaMCMPList($tAPIReq, $tMethodReq, $aDataWhereComp);
            if ($aCompData['rtCode'] == '1') {
                $tCompName = $aCompData['raItems']['rtCmpName'];
                $tBchCode = $aCompData['raItems']['rtCmpBchCode'];
                $aDataAddress = $this->mReport->FSaMRptGetDataAddressByBranchComp($tBchCode, $nLangEdit);
            } else {
                $tCompName = "-";
                $tBchCode = "-";
                $aDataAddress = array();
            }

            // array ข้อมูลภาษาที่เกี่ยวข้องกับรายงาน
            $aDataTextRef = array(
                'tTitleReport' => language('report/report/report', 'tRptTitleDpsSize'),
                'tRptTaxNo' => language('report/report/report', 'tRptTaxNo'),
                'tRptDatePrint' => language('report/report/report', 'tRptDatePrint'),
                'tRptDateExport' => language('report/report/report', 'tRptDateExport'),
                'tRptTimePrint' => language('report/report/report', 'tRptTimePrint'),
                'tRptPrintHtml' => language('report/report/report', 'tRptPrintHtml'),
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
                'tRptDateFrom' => language('report/report/report', 'tRptDateFrom'),
                'tRptDateTo' => language('report/report/report', 'tRptDateTo'),
                'tRptFillterRcvFrom' => language('report/report/report', 'tRptPaymentTypeFrom'),
                'tRptFillterRcvTo' => language('report/report/report', 'tRptPaymentTypeTo'),
                // Table Report
                'tRptBarchCode' => language('report/report/report', 'tRptBarchCode'),
                'tRptBarchName' => language('report/report/report', 'tRptBarchName'),
                'tRptDocDate' => language('report/report/report', 'tRptDocDate'),
                'tRptShopCode' => language('report/report/report', 'tRptShopCode'),
                'tRptShopName' => language('report/report/report', 'tRptShopName'),
                'tRptAmount' => language('report/report/report', 'tRptAmount'),
                'tRptSale' => language('report/report/report', 'tRptSale'),
                'tRptCancelSale' => language('report/report/report', 'tRptCancelSale'),
                'tRptTotalSale' => language('report/report/report', 'tRptTotalSale'),
                'tRptTotalAllSale' => language('report/report/report', 'tRptTotalAllSale'),
                'tRptshop' => language('report/report/report', 'tRptshop'),
                'tRptLocker' => language('report/report/report', 'tRptLocker'),
                'tRptSize' => language('report/report/report', 'tRptSize'),
                'tRptBill' => language('report/report/report', 'tRptBill'),
                'tRptTaxSaleLockerTotalSub' => language('report/report/report', 'tRptTaxSaleLockerTotalSub'),
                'tRptPosFrom' => language('report/report/report', 'tRptPosFrom'),
                'tRptPosTo' => language('report/report/report', 'tRptPosTo'),
                'tRptTaxSaleLockerFilterDocDateFrom' => language('report/report/report', 'tRptTaxSaleLockerFilterDocDateFrom'),
                'tRptTaxSaleLockerFilterDocDateTo' => language('report/report/report', 'tRptTaxSaleLockerFilterDocDateTo'),
                // No Data Report
                'tRptNoData' => language('common/main/main', 'tCMNNotFoundData'),
            );

            // ข้อมูลสำหรับดึงข้อมูลจากฐานข้อมูล
            $aDataWhereRpt = array(
                'nPerPage' => 500000,
                'nPage' => $nPage,
                'tCompName' => $this->tCompName,
                'tRptCode' => $tRptCode,
                'tUsrSessionID' => $tUsrSessionID
            );
            $aDataReport = $this->mRptDpsSize->FSaMGetDataReport($aDataWhereRpt);
            // ====================================================================================================================================================================
            // ตั้งค่ารายงาน และ หัวข้อรายงาน ==========================================================================================================================================
            // ตั้งค่า Font Style
            $aReportFontStyle = array('font' => array('name' => 'TH Sarabun New'));
            $aStyleRptSizeTitleName = array('font' => array('size' => 14));
            $aStyleRptSizeCompName = array('font' => array('size' => 12));
            $aStyleRptSizeAddressFont = array('font' => array('size' => 12));
            $aStyleRptHeadderTable = array('font' => array('size' => 12, 'color' => array('rgb' => '000000')));
            $aStyleRptDataTable = array('font' => array('size' => 10, 'color' => array('rgb' => '000000')));

            // Initiate PHPExcel cache
            $oCacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
            $aCacheSettings = array(' memoryCacheSize ' => '8000MB', 'cacheTime' => 3600 * 120);
            PHPExcel_Settings::setCacheStorageMethod($oCacheMethod, $aCacheSettings);

            // เริ่ม phpExcel
            $objPHPExcel = new PHPExcel();

            // A4 ตั้งค่าหน้ากระดาษ
            $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

            // Set Font Style Text All In Report
            $objPHPExcel->getActiveSheet()->getStyle('A1:Z1000')->applyFromArray($aReportFontStyle);

            // จัดความกว้างของคอลัมน์
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);


            // ชื่อหัวรายงาน
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:F1');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $aDataTextRef['tTitleReport']);
            $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($aStyleRptSizeTitleName);

            // ====================================================================================================================================================================
            // เช็คที่อยู่รายงาน =======================================================================================================================================================
            if (isset($aDataAddress) && !empty($aDataAddress)) {
                // Company Name
                $tRptCompName = (empty($aDataAddress['FTCompName'])) ? '-' : $aDataAddress['FTCompName'];
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', $tRptCompName)->getStyle('A2')->applyFromArray($aStyleRptSizeCompName);

                // Check Vertion Address
                if ($aDataAddress['FTAddVersion'] == 1) {
                    // Check Address Line 1
                    $tRptAddV1No = (empty($aDataAddress['FTAddV1No'])) ? '-' : $aDataAddress['FTAddV1No'];
                    $tRptAddV1Road = (empty($aDataAddress['FTAddV1Road'])) ? '-' : $aDataAddress['FTAddV1Road'];
                    $tRptAddV1Soi = (empty($aDataAddress['FTAddV1Soi'])) ? '-' : $aDataAddress['FTAddV1Soi'];
                    $tRptAddressLine1 = $tRptAddV1No . ' ' . $aDataTextRef['tRptAddrRoad'] . ' ' . $tRptAddV1Road . ' ' . $aDataTextRef['tRptAddrSoi'] . ' ' . $tRptAddV1Soi;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', $tRptAddressLine1)->getStyle('A3')->applyFromArray($aStyleRptSizeAddressFont);

                    // Check Address Line 2
                    $tRptAddV1SubDistName = (empty($aDataAddress['FTSudName'])) ? '-' : $aDataAddress['FTSudName'];
                    $tRptAddV1DstName = (empty($aDataAddress['FTDstName'])) ? '-' : $aDataAddress['FTDstName'];
                    $tRptAddV1PvnName = (empty($aDataAddress['FTPvnName'])) ? '-' : $aDataAddress['FTPvnName'];
                    $tRptAddV1PostCode = (empty($aDataAddress['FTAddV1PostCode'])) ? '-' : $aDataAddress['FTAddV1PostCode'];
                    $tRptAddressLine2 = $tRptAddV1SubDistName . ' ' . $tRptAddV1DstName . ' ' . $tRptAddV1PvnName . ' ' . $tRptAddV1PostCode;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', $tRptAddressLine2)->getStyle('A4')->applyFromArray($aStyleRptSizeAddressFont);
                } else {
                    $tRptAddV2Desc1 = (empty($aDataAddress['FTAddV2Desc1'])) ? '-' : $aDataAddress['FTAddV2Desc1'];
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
                $tRptCompTelText = $aDataTextRef['tRptAddrTel'] . ' ' . $tRptCompTel;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', $tRptCompTelText)->getStyle('A5')->applyFromArray($aStyleRptSizeAddressFont);

                // Check Data Fax Number
                if (isset($aDataAddress['FTCompFax']) && !empty($aDataAddress['FTCompFax'])) {
                    $tRptCompFax = $aDataAddress['FTCompFax'];
                } else {
                    $tRptCompFax = '-';
                }
                $tRptCompFaxText = $aDataTextRef['tRptAddrFax'] . ' ' . $tRptCompFax;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A6', $tRptCompFaxText)->getStyle('A6')->applyFromArray($aStyleRptSizeAddressFont);
            }
            // ====================================================================================================================================================================
            // ฟิวเตอร์ข้อมูลรายงาน =================================================================================================================================================== 
            // Row เริ่มต้นของ Filter
            $nStartRowFillter = 2;
            $tFillterColumLEFT = "D";
            $tFillterColumRIGHT = "F";


            // Fillter Branch (สาขา)
            if (!empty($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeTo'])) {
                $tRptFilterBchCodeFrom = $aDataTextRef['tRptBchFrom'] . ' ' . $aDataFilter['tBchNameFrom'];
                $tRptFilterBchCodeTo = $aDataTextRef['tRptBchTo'] . ' ' . $aDataFilter['tBchNameTo'];
                $tRptTextLeftRightFilter = $tRptFilterBchCodeFrom . ' ' . $tRptFilterBchCodeTo;
                $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                $nStartRowFillter += 1;
            }


            // Fillter Shop (ร้านค้า)
            if (!empty($aDataFilter['tShopCodeFrom']) && !empty($aDataFilter['tShopCodeTo'])) {
                $tRptFilterShopCodeFrom = $aDataTextRef['tRptShopFrom'] . ' ' . $aDataFilter['tShopNameFrom'];
                $tRptFilterShopCodeTo = $aDataTextRef['tRptShopTo'] . ' ' . $aDataFilter['tShopNameTo'];
                $tRptTextLeftRightFilter = $tRptFilterShopCodeFrom . ' ' . $tRptFilterShopCodeTo;
                $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                $nStartRowFillter += 1;
            }

            // Fillter Pos (เครื่องจุดขาย)
            if (!empty($aDataFilter['tPosCodeFrom']) && !empty($aDataFilter['tPosCodeTo'])) {
                $tRptFilterRcvCodeFrom = $aDataTextRef['tRptPosFrom'] . ' ' . $aDataFilter['tPosNameFrom'];
                $tRptFilterRcvCodeTo = $aDataTextRef['tRptPosTo'] . ' ' . $aDataFilter['tPosNameTo'];
                $tRptTextLeftRightFilter = $tRptFilterRcvCodeFrom . ' ' . $tRptFilterRcvCodeTo;
                $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                $nStartRowFillter += 1;
            }
            // Fillter DocDate (วันที่สร้างเอกสาร)
            if (!empty($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateTo'])) {
                $tRptFilterDocDateFrom = $aDataTextRef['tRptTaxSaleLockerFilterDocDateFrom'] . ' ' . $aDataFilter['tDocDateFrom'];
                $tRptFilterDocDateTo = $aDataTextRef['tRptTaxSaleLockerFilterDocDateTo'] . ' ' . $aDataFilter['tDocDateTo'];
                $tRptTextLeftRightFilter = $tRptFilterDocDateFrom . ' ' . $tRptFilterDocDateTo;
                $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                $nStartRowFillter += 1;
            }

            // ====================================================================================================================================================================
            // ตั้งค่าวันที่เวลาออกรายงาน ===============================================================================================================================================
            $tRptDateTimeExportText = $aDataTextRef['tRptDatePrint'] . ' ' . $dDatePrint . ' ' . $aDataTextRef['tRptTimePrint'] . ' ' . $dTimePrint;
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:F7');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A7', $tRptDateTimeExportText);
            $objPHPExcel->getActiveSheet()->getStyle("A7")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($aStyleRptSizeAddressFont);
            // ====================================================================================================================================================================
            // หัวตารางรายงาน =======================================================================================================================================================
            // กำหนดจุดเริ่มต้นของแถวหัวตาราง
            $nStartRowHeadder = 8;

            // กำหนด Style Font ของหัวตาราง
            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':F' . $nStartRowHeadder)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':F' . $nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':F' . $nStartRowHeadder)->applyFromArray($aStyleRptHeadderTable);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':F' . $nStartRowHeadder)->applyFromArray(array(
                'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))
                )
            ));

            // กำหนดข้อมูลลงหัวตาราง
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowHeadder . ':B' . $nStartRowHeadder);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowHeadder, $aDataTextRef['tRptshop']);

            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C' . $nStartRowHeadder . ':D' . $nStartRowHeadder);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $nStartRowHeadder, $aDataTextRef['tRptLocker']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $nStartRowHeadder, $aDataTextRef['tRptSize']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $nStartRowHeadder, $aDataTextRef['tRptBill']);
            // ====================================================================================================================================================================
            // วนลูปข้อมูลตารางข้อมูล =================================================================================================================================================
            // Set Variable Data
            $nStartRowData = $nStartRowHeadder + 1;
            if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
                // ******* Step 1 ประกาศตัวแปรใช้ในการเช็คเงื่อนไข
                $tGrouppingData = "";
                $nGroupMember = "";
                $nRowPartID = "";

                $nSumFooterFCXrcNet = 0;
                // echo '<pre>';
                // print_r($aDataReport['aRptData']); exit();
                foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                    // ******* Step 3 Set ค่า Value FNRptGroupMember And FNRowPartID
                    $nRowPartID = $aValue["FNRowPartID"];
                    // $nGroupMember   = $aValue["ShpCode_Net"].$aValue["PosCode_Net"]; 
                    $nGroupMember = $aValue["FNRptGroupMember"];
                    // echo $nGroupMember; exit();
                    // echo $tGroup; exit();
                    // ******* Step 4 : Check Groupping Create Row Groupping 
                    if ($tGrouppingData != $aValue['FTShpCode'] . $aValue['FTPosCode']) {
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':B' . $nStartRowData);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $aValue['FTShpName']);
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C' . $nStartRowData . ':F' . $nStartRowData);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $nStartRowData, 'POS ' . $aValue['FTPosCode']);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':F' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
                        $nStartRowData++;
                    }

                    // ******* Step 5 : Loop Set Data Value 
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':B' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C' . $nStartRowData . ':D' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $nStartRowData, $aValue['FTPzeName']);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $nStartRowData, number_format($aValue['FTXhdQty'], 2));

                    $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':E' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    // ******* Step 6 : เตรียม Parameter สำหรับ Summary Sub Footer
                    if ($nRowPartID == $nGroupMember) {
                        $nStartRowData++;
                        $nSubSumFCXrcNet = $aValue["Qtybill_All"];
                        // Set Color Row Sub Footer
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':F' . $nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':F' . $nStartRowData)->applyFromArray(array(
                            'borders' => array(
                                'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))
                            )
                        ));

                        // Text Sum Sub
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':E' . $nStartRowData);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $aDataTextRef['tRptTaxSaleLockerTotalSub']);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                        // Value Sum Sub
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('F' . $nStartRowData, number_format($nSubSumFCXrcNet, 2));

                        $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        // $objPHPExcel->setActiveSheetIndex(0)->mergeCells('H'.$nStartRowData.':I'.$nStartRowData);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    }

                    // ******* Step 2 Set ค่า Value ให้กับตัวแปร
                    $tGrouppingData = $aValue["FTShpCode"] . $aValue["FTPosCode"];
                    $nStartRowData++;
                }
            } else {
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':F' . $nStartRowData);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $aDataTextRef['tRptNoData']);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
            }

            // ตั่งค่า Content Type Export File Excel
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

        } catch (Exception $Error) {
            $aResponse = array(
                'nStaExport' => 500,
                'tMessage' => $Error->getMessage()
            );
        }
        echo json_encode($aResponse);
    }

    /**
     * Functionality: Click Page Report (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 16/08/2019 Witsarut(Bell)
     * LastUpdate: 04/10/2019 Piya
     * Return: object Status Count Data Report
     * ReturnType: Object
     */
    public function FSoCChkDataReportInTableTemp($paDataSwitchCase) {
        try {

            $aDataCountData = [
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tSessionID' => $this->tUserSessionID,
            ];

            $nDataCountPage = $this->mRptDpsSize->FSaMCountDataReportAll($aDataCountData);

            $aResponse = array(
                'nCountPageAll' => $nDataCountPage,
                'nStaEvent' => 1,
                'tMessage' => 'Success Count Data All'
            );
        } catch (ErrorException $Error) {
            $aResponse = array(
                'nStaEvent' => 500,
                'tMessage' => $Error->getMessage()
            );
        }
        echo json_encode($aResponse);
    }

    /**
     * Functionality: Send Rabbit MQ Report
     * Parameters:  Function Parameter
     * Creator: 16/08/2019 Witsarut(Bell)
     * LastUpdate: 04/10/2019 Piya
     * Return: object Send Rabbit MQ Report
     * ReturnType: Object
     */
    public function FSvCCallRptExportFile() {
        try {
            $tDateSendMQ = date('Y-m-d');
            $tTimeSendMQ = date('H:i:s');
            $tDateSubscribe = date('Ymd');
            $tTimeSubscribe = date('His');

            // Set Parameter Send MQ
            $tRptQueueName = 'RPT_' . $this->tRptGroup . '_' . $this->tRptCode;

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
                    'ptDate' => $tDateSendMQ,
                    'ptTime' => $tTimeSendMQ,
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
                    'pdDateSubscribe' => $tDateSubscribe,
                    'pdTimeSubscribe' => $tTimeSubscribe,
                )
            );
        } catch (Exception $Error) {
            $aResponse = array(
                'nStaEvent' => 500,
                'tMessage' => $Error->getMessage()
            );
        }
        echo json_encode($aResponse);
    }

}












