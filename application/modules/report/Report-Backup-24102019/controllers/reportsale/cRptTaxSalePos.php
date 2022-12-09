<?php

defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

class cRptTaxSalePos extends MX_Controller {

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
        $this->load->model('report/reportsale/mRptTaxSalePos');

        // Init Report
        $this->init();

        parent::__construct();
    }

    private function init() {
        $this->aText = [
            'tTitleReport' => language('report/report/report', 'tRptTaxSalePosTitle'),
            'tDatePrint' => language('report/report/report', 'tRptTaxSalePosDatePrint'),
            'tTimePrint' => language('report/report/report', 'tRptTaxSalePosTimePrint'),
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
            // Table Label
            'tRptTaxSalePosDocNo' => language('report/report/report', 'tRptTaxSalePosDocNo'),
            'tRptTaxSalePosDocDate' => language('report/report/report', 'tRptTaxSalePosDocDate'),
            'tRptTaxSalePosDateAndLocker' => language('report/report/report', 'tRptTaxSalePosDateAndLocker'),
            'tRptTaxSalePosPayTypeAndDocRef' => language('report/report/report', 'tRptTaxSalePosPayTypeAndDocRef'),
            'tRptTaxSalePosDocRef' => language('report/report/report', 'tRptTaxSalePosDocRef'),
            'tRptTaxSalePosPayment' => language('report/report/report', 'tRptTaxSalePosPayment'),
            'tRptTaxSalePosPaymentTotal' => language('report/report/report', 'tRptTaxSalePosPaymentTotal'),
            'tRptTaxSalePosPosGrouping' => language('report/report/report', 'tRptTaxSalePosPosGrouping'),
            // No Data Report
            'tRptTaxSalePosNoData' => language('common/main/main', 'tCMNNotFoundData'),
            'tRptTaxSalePosTotalSub' => language('report/report/report', 'tRptTaxSalePosTotalSub'),
            'tRptTaxSalePosTotalFooter' => language('report/report/report', 'tRptTaxSalePosTotalFooter'),
            // Filter Text Label
            'tRptTaxSalePosFilterBchFrom' => language('report/report/report', 'tRptTaxSalePosFilterBchFrom'),
            'tRptTaxSalePosFilterBchTo' => language('report/report/report', 'tRptTaxSalePosFilterBchTo'),
            'tRptTaxSalePosFilterShopFrom' => language('report/report/report', 'tRptTaxSalePosFilterShopFrom'),
            'tRptTaxSalePosFilterShopTo' => language('report/report/report', 'tRptTaxSalePosFilterShopTo'),
            'tRptTaxSalePosFilterPosFrom' => language('report/report/report', 'tRptTaxSalePosFilterPosFrom'),
            'tRptTaxSalePosFilterPosTo' => language('report/report/report', 'tRptTaxSalePosFilterPosTo'),
            'tRptTaxSalePosFilterPayTypeFrom' => language('report/report/report', 'tRptTaxSalePosFilterPayTypeFrom'),
            'tRptTaxSalePosFilterPayTypeTo' => language('report/report/report', 'tRptTaxSalePosFilterPayTypeTo'),
            'tRptTaxSalePosFilterDocDateFrom' => language('report/report/report', 'tRptTaxSalePosFilterDocDateFrom'),
            'tRptTaxSalePosFilterDocDateTo' => language('report/report/report', 'tRptTaxSalePosFilterDocDateTo'),
            // Text Label
            'tRptTaxSalePosTel' => language('report/report/report', 'tRptTaxSalePosTel'),
            'tRptTaxSalePosFax' => language('report/report/report', 'tRptTaxSalePosFax'),
            'tRptTaxSalePosDatePrint' => language('report/report/report', 'tRptTaxSalePosDatePrint'),
            'tRptTaxSalePosTimePrint' => language('report/report/report', 'tRptTaxSalePosTimePrint'),
            'tRptTaxSalePosBch' => language('report/report/report', 'tRptTaxSalePosBch'),
            'tRptDataReportNotFound' => language('report/report/report', 'tRptDataReportNotFound'),
            'tRptTaxSalePosSeq' => language('report/report/report', 'tRptTaxSalePosSeq'),
            'tRptTaxSalePosCst' => language('report/report/report', 'tRptTaxSalePosCst'),
            'tRptTaxSalePosTaxID' => language('report/report/report', 'tRptTaxSalePosTaxID'),
            'tRptTaxSalePosComp' => language('report/report/report', 'tRptTaxSalePosComp'),
            'tRptTaxSalePosAmt' => language('report/report/report', 'tRptTaxSalePosAmt'),
            'tRptTaxSalePosAmtV' => language('report/report/report', 'tRptTaxSalePosAmtV'),
            'tRptTaxSalePosAmtNV' => language('report/report/report', 'tRptTaxSalePosAmtNV'),
            'tRptTaxSalePosTotal' => language('report/report/report', 'tRptTaxSalePosTotal'),
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
            'tUserSession'  => $this->tUserSessionID,
            'tCompName'     => $tFullHost,
            'tRptCode'      => $this->tRptCode,
            'nLangID'       => $this->nLngID,
            // สาขา(Branch)
            'tBchCodeFrom' => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom' => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo' => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo' => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            // วันที่เอกสาร(DocNo)
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
            $this->mRptTaxSalePos->FSnMExecStoreReport($this->aRptFilter);
            // Count Rows
            $aCountRowParams = [
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tSessionID' => $this->tUserSessionID
            ];
            $this->nRows = $this->mRptTaxSalePos->FSnMCountRowInTemp($aCountRowParams);

            // Report Type
            switch ($this->tRptExportType) {
                case 'html':
                    $this->FSvCCallRptViewBeforePrint();
                    break;
                case 'excel':
                    $this->FSoCChkDataReportInTableTemp();
                    /* $aExportExcelRes = $this->FSvCCallRptRenderExcel();
                      echo json_encode($aExportExcelRes); */
                    break;
                case 'pdf':

                    break;
            }
        }
    }

    /**
     * Functionality: ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 22/07/2019 Piya
     * LastUpdate: -
     * Return: View Report Viewersd
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrint() {
        try {
            /** =========== Begin Get Data =================================== */
            // ดึงข้อมูลจากฐานข้อมูล Temp
            $aDataReportParams = [
                'nPerPage' => $this->nPerPage,
                'nPage' => $this->nPage,
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tUsrSessionID' => $this->tUserSessionID,
            ];
            $aDataReport = $this->mRptTaxSalePos->FSaMGetDataReport($aDataReportParams);
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
            $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportsale/RptTaxSalePos', 'wRptTaxSalePosHtml', $aDataViewRptParams);

            // Data Viewer Center Report
            $aDataViewerParams = [
                'tTitleReport' => $this->aText['tTitleReport'],
                'tRptTypeExport' => $this->tRptExportType,
                'tRptCode' => $this->tRptCode,
                'tRptRoute' => $this->tRptRoute,
                'tViewRenderKool' => $tRptView,
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
     * Creator: 22/07/2019 Piya
     * LastUpdate: -
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrintClickPage() {

        /** =========== Begin Init Variable ================================== */
        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);
        /** =========== End Init Variable ==================================== */
        /** =========== Begin Get Data ======================================= */
        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aDataReportParams = [
            'nPerPage' => $this->nPerPage,
            'nPage' => $this->nPage,
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        ];
        $aDataReport = $this->mRptTaxSalePos->FSaMGetDataReport($aDataReportParams);
        /** =========== End Get Data ========================================= */
        /** =========== Begin Render View ==================================== */
        // Load View Advance Table
        $aDataViewRptParams = array(
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aCompanyInfo' => $this->aCompanyInfo,
            'aDataReport' => $aDataReport,
            'aDataTextRef' => $this->aText,
            'aDataFilter' => $aDataFilter
        );
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/reportsale/RptTaxSalePos', 'wRptTaxSalePosHtml', $aDataViewRptParams);

        // Data Viewer Center Report
        $aDataViewerParams = array(
            'tTitleReport' => $this->aText['tTitleReport'],
            'tRptTypeExport' => $this->tRptExportType,
            'tRptCode' => $this->tRptCode,
            'tRptRoute' => $this->tRptRoute,
            'tViewRenderKool' => $tRptView,
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
        /** =========== End Render View ====================================== */
    }

    /**
     * Functionality: Excel Report
     * Parameters:  Function Parameter
     * Creator: 22/07/2019 Piya
     * LastUpdate: -
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptRenderExcel() {

        /** =========== Begin Init Variable ================================== */
        $tReportName = $this->aText['tTitleReport'];
        $dDateExport = date('Y-m-d');
        $tTime = date('H:i:s');
        $tTextDetail = $this->aText['tRptTaxSalePosDatePrint'] . ' : ' . $dDateExport . '  ' . $this->aText['tRptTaxSalePosTimePrint'] . ' : ' . $tTime;
        /** =========== End Init Variable ==================================== */
        /** =========== Begin Get Data ======================================= */
        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aGetDataReportParams = array(
            'nPerPage' => 100000,
            'nPage' => 1,
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        );
        $aDataReport = $this->mRptTaxSalePos->FSaMGetDataReport($aGetDataReportParams);
        /** =========== End Get Data ========================================= */
        try {
            if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
                // ตั้งค่า Font Style
                $aStyleRptName = array('font' => array('size' => 14, 'bold' => true,));
                $aStyleHeadder = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));
                $aStyleRptHeadderTable = array('font' => array('size' => 12, 'color' => array('rgb' => '000000')));
                $StyleCompFont = array('font' => array('size' => 12, 'bold' => true,));
                $StyleAddressFont = array('font' => array('size' => 11, 'bold' => true,));
                $aStyleBold = ['font' => ['size' => 11, 'bold' => true,]];
                $StyleFont = array('font' => array('name' => 'TH Sarabun New'));

                // Initiate PHPExcel cache
                $oCacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
                $aCacheSettings = array(' memoryCacheSize ' => '8000MB', 'cacheTime' => 3600 * 120);
                PHPExcel_Settings::setCacheStorageMethod($oCacheMethod, $aCacheSettings);

                // เริ่ม phpExcel
                $objPHPExcel = new PHPExcel();

                // A4 ตั้งค่าหน้ากระดาษ
                $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

                // Set Font
                $objPHPExcel->getActiveSheet()->getStyle('A1:Z1000')->applyFromArray($StyleFont);

                // จัดความกว้างของคอลัมน์
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);

                // ชื่อ Conpany
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', $this->aCompanyInfo['FTCmpName'])->getStyle('A2')->applyFromArray($StyleCompFont);

                // ที่อยู่
                $tLabelAddressLine1 = $this->aCompanyInfo['FTAddV1No'] . ' ' . $this->aCompanyInfo['FTAddV1Village'] . ' ' . $this->aCompanyInfo['FTAddV1Road'] . ' ' . $this->aCompanyInfo['FTAddV1Soi'];
                $tLabelAddressLine2 = $this->aCompanyInfo['FTSudName'] . ' ' . $this->aCompanyInfo['FTDstName'] . ' ' . $this->aCompanyInfo['FTPvnName'] . ' ' . $this->aCompanyInfo['FTAddV1PostCode'];
                // ที่อยู่ บรรทัดที่ 1
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', $tLabelAddressLine1)->getStyle('A3')->applyFromArray($StyleAddressFont);
                // ที่อยู่ บรรทัดที่ 2
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', $tLabelAddressLine2)->getStyle('A4')->applyFromArray($StyleAddressFont);

                // เบอร์โทร, แฟกซ์
                $tLabelTelFax = $this->aText['tRptTaxSalePosTel'] . $this->aCompanyInfo['FTCmpTel'] . ' ' . $this->aText['tRptTaxSalePosFax'] . $this->aCompanyInfo['FTCmpFax'];
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', $tLabelTelFax)->getStyle('A5')->applyFromArray($StyleAddressFont);

                // สาขา
                $tLabelBch = $this->aText['tRptTaxSalePosBch'] . $this->aCompanyInfo['FTBchName'];
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A6', $tLabelBch)->getStyle('A6')->applyFromArray($StyleAddressFont);

                // ชื่อหัวรายงาน
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:K1');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $tReportName);
                $objPHPExcel->getActiveSheet()->getStyle("A1")
                        ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($aStyleRptName);

                // กำหนกหัวตาราง
                $nStartRowHeadder = 8;
                $nStartRowFillter = 2;

                $tFillterColumLEFT = "E";
                $tFillterColumRIGHT = "F";

                /* ===== Begin Fillter =========================================================================== */
                // สาขา
                if (!empty($this->aRptFilter['tBchCodeFrom']) && !empty($this->aRptFilter['tBchCodeTo'])) {
                    // จากสาขา
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $this->aText['tRptTaxSalePosFilterBchFrom'] . $this->aRptFilter['tBchNameFrom']);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)
                            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    // ถึงสาขา
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumRIGHT . $nStartRowFillter, $this->aText['tRptTaxSalePosFilterBchTo'] . $this->aRptFilter['tBchNameTo']);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumRIGHT . $nStartRowFillter)
                            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;

                    if ($nStartRowFillter > 7) {
                        $nStartRowHeadder += 1;
                    }
                }

                // วันที่สร้างเอกสาร
                if (!empty($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateTo'])) {
                    // จากวันที่สร้างเอกสาร
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $this->aText['tRptTaxSalePosFilterDocDateFrom'] . $this->aRptFilter['tDocDateFrom']);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)
                            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    // ถึงวันที่สร้างเอกสาร
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumRIGHT . $nStartRowFillter, $this->aText['tRptTaxSalePosFilterDocDateTo'] . $this->aRptFilter['tDocDateTo']);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumRIGHT . $nStartRowFillter)
                            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;

                    if ($nStartRowFillter > 7) {
                        $nStartRowHeadder += 1;
                    }
                }
                /* ===== End Fillter =========================================================================== */

                // รายละเอียดการออกรายงาน
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . ($nStartRowHeadder - 1) . ':K' . ($nStartRowHeadder - 1));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . ($nStartRowHeadder - 1), $tTextDetail);
                $objPHPExcel->getActiveSheet()->getStyle('A' . ($nStartRowHeadder - 1))
                        ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                // กำหนด Style Font ของหัวตาราง
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':K' . $nStartRowHeadder)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':K' . $nStartRowHeadder)->applyFromArray($aStyleRptHeadderTable);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':K' . $nStartRowHeadder)->applyFromArray(array(
                    'borders' => array(
                        'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                        'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))
                    )
                ));

                // Main header
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $nStartRowHeadder, $this->aText['tRptTaxSalePosSeq'])
                        ->setCellValue('B' . $nStartRowHeadder, $this->aText['tRptTaxSalePosDocDate'])
                        ->setCellValue('C' . $nStartRowHeadder, $this->aText['tRptTaxSalePosDocNo'])
                        ->setCellValue('D' . $nStartRowHeadder, $this->aText['tRptTaxSalePosDocRef'])
                        ->setCellValue('E' . $nStartRowHeadder, $this->aText['tRptTaxSalePosCst'])
                        ->setCellValue('F' . $nStartRowHeadder, $this->aText['tRptTaxSalePosTaxID'])
                        ->setCellValue('G' . $nStartRowHeadder, $this->aText['tRptTaxSalePosComp'])
                        ->setCellValue('H' . $nStartRowHeadder, $this->aText['tRptTaxSalePosAmt'])
                        ->setCellValue('I' . $nStartRowHeadder, $this->aText['tRptTaxSalePosAmtV'])
                        ->setCellValue('J' . $nStartRowHeadder, $this->aText['tRptTaxSalePosAmtNV'])
                        ->setCellValue('K' . $nStartRowHeadder, $this->aText['tRptTaxSalePosTotal']);

                // ตัวอักษร Head Center
                $objPHPExcel->getActiveSheet()->getStyle("A" . $nStartRowHeadder . ":K" . $nStartRowHeadder)
                        ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



                // Loop Data Query DataBase
                $nStartRowData = $nStartRowHeadder + 1;
                $nSeq = 0;
                $nLastRowNuber = 0;

                foreach ($aDataReport['aRptData'] AS $nKey => $aValue) {

                    if ($aValue['FNRowPartID'] == 1) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $nStartRowData, '  ' . $this->aText['tRptTaxSalePosPosGrouping'] . $aValue['FTPosCode']);

                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . ($nStartRowData) . ':K' . ($nStartRowData));
                        // รูปแบบตัวอักษร
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':K' . $nStartRowData)->applyFromArray($aStyleBold);

                        $nStartRowData += 1;
                        $nSeq = 1;
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $nStartRowData, '  ' . $nSeq++)
                            ->setCellValue('B' . $nStartRowData, '  ' . date("d/m/Y", strtotime($aValue['FDXshDocDate'])))
                            ->setCellValue('C' . $nStartRowData, '  ' . $aValue["FTXshDocNo"])
                            ->setCellValue('D' . $nStartRowData, '  ' . $aValue["FTXshDocRef"])
                            ->setCellValue('E' . $nStartRowData, '  ' . $aValue["FTCstName"])
                            ->setCellValue('F' . $nStartRowData, '  ' . $aValue["FTXshTaxID"])
                            ->setCellValue('G' . $nStartRowData, '  ' . $aValue["FTCmpName"])
                            ->setCellValue('H' . $nStartRowData, '  ' . number_format($aValue["FCXshAmt"], $this->nOptDecimalShow))
                            ->setCellValue('I' . $nStartRowData, '  ' . number_format($aValue["FCXshAmtV"], $this->nOptDecimalShow))
                            ->setCellValue('J' . $nStartRowData, '  ' . number_format($aValue["FCXshAmtNV"], $this->nOptDecimalShow))
                            ->setCellValue('K' . $nStartRowData, '  ' . number_format($aValue["FCXshGrandTotal"], $this->nOptDecimalShow));

                    // ตัวอักษรชิดขวา
                    $objPHPExcel->getActiveSheet()->getStyle("H" . $nStartRowData . ":K" . $nStartRowData)
                            ->getAlignment()
                            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    // ตัวอักษร Head Center
                    $objPHPExcel->getActiveSheet()->getStyle("A" . $nStartRowData . ":A" . $nStartRowData)
                            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                    $nLastRowNuber = $nStartRowData;
                    $nStartRowData++;
                }

                // Set Footer Summary
                $nEndRow = $nStartRowData;
                $nSummaryRow = $nEndRow;

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $nSummaryRow, '  ' . $this->aText['tRptTaxSalePosTotalFooter'])
                        ->setCellValue("H" . $nSummaryRow, '  ' . number_format($aValue['FCXshAmt_Footer'], $this->nOptDecimalShow))
                        ->setCellValue("I" . $nSummaryRow, '  ' . number_format($aValue['FCXshAmtV_Footer'], $this->nOptDecimalShow))
                        ->setCellValue("J" . $nSummaryRow, '  ' . number_format($aValue['FCXshAmtNV_Footer'], $this->nOptDecimalShow))
                        ->setCellValue("K" . $nSummaryRow, '  ' . number_format($aValue['FCXshGrandTotal_Footer'], $this->nOptDecimalShow));

                // กำหนด Style Font Summary
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nSummaryRow . ':K' . $nSummaryRow)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nSummaryRow . ':K' . $nSummaryRow)->applyFromArray($aStyleRptHeadderTable);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nSummaryRow . ':K' . $nSummaryRow)->applyFromArray(array(
                    'borders' => array(
                        'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                        'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))
                    )
                ));
                // จัดตัวอักษรชิดขวา
                $objPHPExcel->getActiveSheet()->getStyle("A" . $nSummaryRow . ':K' . $nSummaryRow)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                // Export File Excel
                $tFilename = 'ReportCard8-' . date("dmYhis") . '.xlsx';

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

                if (!is_dir(APPPATH . 'modules/report/assets/exportexcel/' . $this->tRptCode)) {
                    mkdir(APPPATH . 'modules/report/assets/exportexcel/' . $this->tRptCode);
                }

                if (!is_dir(APPPATH . 'modules/report/assets/exportexcel/' . $this->tRptCode . '/' . $this->tUserLoginCode)) {
                    mkdir(APPPATH . 'modules/report/assets/exportexcel/' . $this->tRptCode . '/' . $this->tUserLoginCode);
                }

                $tPathExport = APPPATH . 'modules/report/assets/exportexcel/' . $this->tRptCode . '/' . $this->tUserLoginCode . '/';

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
                    'tPathFolder' => 'application/modules/report/assets/exportexcel/' . $this->tRptCode . '/' . $this->tUserLoginCode . '/',
                    'tMessage' => "Export File Successfully."
                );
            } else {
                $aResponse = array(
                    'nStaExport' => '800',
                    'tMessage' => $this->aText['tRptDataReportNotFound']
                );
            }
        } catch (Exception $Error) {
            $aResponse = array(
                'nStaExport' => '500',
                'tMessage' => $Error->getMessage()
            );
        }
        return $aResponse;
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
            $aDataCountData = [
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tSessionID' => $this->tUserSessionID,
            ];

            $nDataCountPage = $this->mRptTaxSalePos->FSnMCountRowInTemp($aDataCountData);

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
     * Creator: 22/07/2019 Piya
     * LastUpdate: -
     * Return: object Send Rabbit MQ Report
     * ReturnType: Object
     */
    public function FSvCCallRptExportFile() {
        try {
            $tRptGrpCode = $this->tRptGroup;
            $tRptCode = $this->tRptCode;
            $tUserCode = $this->tUserLoginCode;
            $tSessionID = $this->tUserSessionID;
            $nLangID = FCNaHGetLangEdit();
            $tRptExportType = $this->tRptExportType;
            $tCompName = $this->tCompName;
            $dDateSendMQ = date('Y-m-d');
            $dTimeSendMQ = date('H:i:s');
            $dDateSubscribe = date('Ymd');
            $dTimeSubscribe = date('His');

            // Set Parameter Send MQ
            $tRptQueueName = 'RPT_' . $tRptGrpCode . '_' . $tRptCode;

            $aDataSendMQ = [
                'tQueueName' => $tRptQueueName,
                'aParams' => [
                    'ptRptCode' => $tRptCode,
                    'pnPerFile' => 20000,
                    'ptUserCode' => $tUserCode,
                    'ptUserSessionID' => $tSessionID,
                    'pnLngID' => $nLangID,
                    'ptFilter' => $this->aRptFilter,
                    'ptRptExpType' => $tRptExportType,
                    'ptComName' => $tCompName,
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
                    'ptComName' => $tCompName,
                    'ptRptCode' => $tRptCode,
                    'ptUserCode' => $tUserCode,
                    'ptUserSessionID' => $tSessionID,
                    'pdDateSubscribe' => $dDateSubscribe,
                    'pdTimeSubscribe' => $dTimeSubscribe,
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


