<?php

defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

class cRptMovePosVD extends MX_Controller {

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
        $this->load->model('report/reportMovePosVD/mRptMovePosVd');
        $this->load->model('report/report/mReport');

        // Init Report
        $this->init();

        parent::__construct();
    }

    private function init() {
        $this->aText = [
            'tTitleReport' => language('report/report/report', 'tRptTitleMoveVD'),
            'tDatePrint' => language('report/report/report', 'tRptAdjStkVDDatePrint'),
            'tTimePrint' => language('report/report/report', 'tRptAdjStkVDTimePrint'),
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
            // Filter Heard Report
            'tRptBchFrom' => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo' => language('report/report/report', 'tRptBchTo'),
            'tRptShopFrom' => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo' => language('report/report/report', 'tRptShopTo'),
            'tPdtCodeFrom' => language('report/report/report', 'tPdtCodeFrom'),
            'tPdtCodeTo' => language('report/report/report', 'tPdtCodeTo'),
            'tRptFromWareHouse' => language('report/report/report', 'tRptFromWareHouse'),
            'tRptToWareHouse' => language('report/report/report', 'tRptToWareHouse'),
            'tRptDateFrom' => language('report/report/report', 'tRptDateFrom'),
            'tRptDateTo' => language('report/report/report', 'tRptDateTo'),
            'tRptTotalSub' => language('report/report/report', 'tRptTotalSub'),
            'tRptTotalFooter' => language('report/report/report', 'tRptTotalFooter')
          
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
            // สินค้า
            'tPdtCodeFrom' => !empty($this->input->post('oetRptPdtCodeFrom')) ? $this->input->post('oetRptPdtCodeFrom') : "",
            'tPdtNameFrom' => !empty($this->input->post('oetRptPdtNameFrom')) ? $this->input->post('oetRptPdtNameFrom') : "",
            'tPdtCodeTo' => !empty($this->input->post('oetRptPdtCodeTo')) ? $this->input->post('oetRptPdtCodeTo') : "",
            'tPdtNameTo' => !empty($this->input->post('oetRptPdtNameTo')) ? $this->input->post('oetRptPdtNameTo') : "",
            // คลังสินค้า(Warehouse)
            'tWahCodeFrom' => !empty($this->input->post('oetRptWahCodeFrom')) ? $this->input->post('oetRptWahCodeFrom') : "",
            'tWahCodeTo' => !empty($this->input->post('oetRptWahCodeTo')) ? $this->input->post('oetRptWahCodeTo') : "",
            'tWahNameFrom' => !empty($this->input->post('oetRptWahNameFrom')) ? $this->input->post('oetRptWahNameFrom') : "",
            'tWahNameTo' => !empty($this->input->post('oetRptWahNameTo')) ? $this->input->post('oetRptWahNameTo') : "",
            // วันที่เอกสาร
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

        if (!empty($this->tRptExportType) && !empty($this->tRptCode)) {

            // Execute Stored Procedure
            $this->mRptMovePosVd->FSnMExecStoreReport($this->aRptFilter);

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
                case 'pdf':
                    $this->FSvCCallRptRenderExcel($aDataSwitchCase);
                    break;
            }
        }
    }

    /**
     * Functionality: ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 10/07/2019 Saharat(Golf)
     * LastUpdate: 24/09/2019 Piya
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrint($paDataSwitchCase) {

        // ข้อมูลสำหรับดึงข้อมูลจากฐานข้อมูล
        $aDataWhereRpt = array(
            'nPerPage' => $this->nPerPage,
            'nPage' => 1, // เริ่มรายงานหน้าแรก
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        );
        $aDataReport = $this->mRptMovePosVd->FSaMGetDataReport($aDataWhereRpt);

        // ข้อมูล Render Report
        $aDataViewPdt = array(
            'aDataReport' => $aDataReport,
            'aDataTextRef' => $this->aText,
            'aCompanyInfo' => $this->aCompanyInfo,
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aDataFilter' => $paDataSwitchCase['paDataFilter']
        );

        // Load View Advance Table
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/rptMovePosVD', 'wRptMovePosVDHtml', $aDataViewPdt);

        // Data Viewer Center Report
        $aDataViewer = array(
            'tTitleReport' => $this->aText['tTitleReport'],
            'tRptTypeExport' => $this->tRptExportType,
            'tRptCode' => $this->tRptCode,
            'tRptRoute' => $this->tRptRoute,
            'tViewRenderKool' => $tRptView,
            'aDataFilter' => $paDataSwitchCase['paDataFilter'],
            'aDataReport' => array(
                'raItems' => $aDataReport['aRptData'],
                'rnAllRow' => $aDataReport['aPagination']['nTotalRecord'],
                'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                'rnAllPage' => $aDataReport['aPagination']['nTotalPage'],
                'rtCode' => '1',
                'rtDesc' => 'success',
            )
        );
        $this->load->view('report/report/wReportViewer', $aDataViewer);
    }

    /**
     * Functionality: Call Rpt Table Kool Report
     * Parameters:  Function Parameter
     * Creator: 04/04/2019 Saharat(Golf)
     * LastUpdate: 24/09/2019 Piya
     * Return: View Kool Report
     * ReturnType: View
     */
    public function FCNvCRenderKoolReportHtml($paDataReport, $paDataFilter) {

        // Ref File Kool Report
        require_once APPPATH . 'modules\report\datasources\rptSaleRecive\rptSaleRecive.php';

        // Set Parameter To Report
        $oRptSaleReciveHtml = new rptSaleRecive(array(
            'nCurrentPage' => $paDataReport['rnCurrentPage'],
            'nAllPage' => $paDataReport['rnAllPage'],
            'aFilterReport' => $this->aRptFilter,
            'aDataTextRef' => $this->aText,
            'aDataReturn' => $paDataReport['raItems']
        ));

        $oRptSaleReciveHtml->run();
        $tHtmlViewReport = $oRptSaleReciveHtml->render('wRptSaleReciveHtml', true);
        return $tHtmlViewReport;
    }

    /**
     * Functionality: Click Page ดูตัวอย่างก่อนพิมพ์ (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 19/07/2019 Wasin(Yoshi)
     * LastUpdate: 24/09/2019
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrintClickPage() {

        /** =========== Begin Init Variable ================================== */
        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);
        /** =========== End Init Variable ==================================== */
        
        // ข้อมูลสำหรับดึงข้อมูลจากฐานข้อมูล
        $aDataWhereRpt = array(
            'nPerPage' => $this->nPerPage,
            'nPage' => $this->nPage,
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'tUsrSessionID' => $this->tUserSessionID,
        );
        $aDataReport = $this->mRptMovePosVd->FSaMGetDataReport($aDataWhereRpt);

        // ข้อมูล Render Report
        $aDataViewPdt = array(
            'aDataReport' => $aDataReport,
            'aDataTextRef' => $this->aText,
            'aCompanyInfo' => $this->aCompanyInfo,
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aDataFilter' => $aDataFilter
        );
        // Load View Advance Table
        $tRptView = JCNoHLoadViewAdvanceTable('report/datasources/rptMovePosVD', 'wRptMovePosVDHtml', $aDataViewPdt);

        // Data Viewer Center Report
        $aDataViewer = array(
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
                'rtDesc' => 'success',
            )
        );
        $this->load->view('report/report/wReportViewer', $aDataViewer);
    }

    // Functionality: Function Render Report Excel
    // Parameters:  Function Parameter
    // Creator: 20/08/2019 Saharat(Golf)
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
                'tTitleCompName' => $tCompName,
                'tTitleReport' => language('report/report/report', 'tRptTitleMoveVD'),
                'tDatePrint' => language('report/report/report', 'tRptAdjStkVDDatePrint'),
                'tTimePrint' => language('report/report/report', 'tRptAdjStkVDTimePrint'),
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
                // Table Label Excal
                'tWahName' => language('report/report/report', 'tRptWahName'),
                'tPdtCode' => language('report/report/report', 'tRptPdtCode'),
                'tPdtName' => language('report/report/report', 'tRptPdtName'),
                'tRptDoc' => language('report/report/report', 'tRptDoc'),
                'tRptDate' => language('report/report/report', 'tRptDate'),
                'tRptBringF' => language('report/report/report', 'tRptBringF'),
                'tRptIn' => language('report/report/report', 'tRptIn'),
                'tRptEx' => language('report/report/report', 'tRptEx'),
                'tRptSale' => language('report/report/report', 'tRptSale'),
                'tRptAdjud' => language('report/report/report', 'tRptAdjud'),
                'tRptInven' => language('report/report/report', 'tRptInven'),
                'tRptOverall' => language('report/report/report', 'tRptOverall'),
                'tRptShopFrom' => language('report/report/report', 'tRptShopFrom'),
                'tRptShopTo' => language('report/report/report', 'tRptShopTo'),
                'tRptCabinetCode' => language('report/report/report', 'tRptCabinetCode'),
                'tRptPdtCode' => language('report/report/report', 'tRptPdtCode'),
                'tRptPdtName' => language('report/report/report', 'tRptPdtName'),
                'tRptCabinetclass' => language('report/report/report', 'tRptCabinetclass'),
                'tRptCabinetCollum' => language('report/report/report', 'tRptCabinetCollum'),
                'tRptCabinetnumber' => language('report/report/report', 'tRptCabinetnumber'),
                'tRptAdjStkVDLayRow' => language('report/report/report', 'tRptAdjStkVDLayRow'),
                'tRptAdjStkVDLayCol' => language('report/report/report', 'tRptAdjStkVDLayCol'),
                'tRptCabinetCost' => language('report/report/report', 'tRptCabinetCost'),
                'tRptCabinetTotalcost' => language('report/report/report', 'tRptCabinetTotalcost'),
                'tRptListener' => language('report/report/report', 'tRptListener'),
                // No Data Report
                'tRptAdjStkNoData' => language('common/main/main', 'tCMNNotFoundData'),
                // Filter Heard Report
                'tRptBchFrom' => language('report/report/report', 'tRptBchFrom'),
                'tRptBchTo' => language('report/report/report', 'tRptBchTo'),
                'tPdtCodeFrom' => language('report/report/report', 'tPdtCodeFrom'),
                'tPdtCodeTo' => language('report/report/report', 'tPdtCodeTo'),
                'tRptFromWareHouse' => language('report/report/report', 'tRptFromWareHouse'),
                'tRptToWareHouse' => language('report/report/report', 'tRptToWareHouse'),
                'tRptTaxSaleLockerFilterDocDateFrom' => language('report/report/report', 'tRptTaxSaleLockerFilterDocDateFrom'),
                'tRptTaxSaleLockerFilterDocDateTo' => language('report/report/report', 'tRptTaxSaleLockerFilterDocDateTo'),
                'tPdtCodeFrom' => language('report/report/report', 'tPdtCodeFrom'),
                'tPdtCodeTo' => language('report/report/report', 'tPdtCodeTo'),
                'tRptTotalSub' => language('report/report/report', 'tRptTotalSub'),
            );

            // ข้อมูลสำหรับดึงข้อมูลจากฐานข้อมูล
            $aDataWhereRpt = array(
                'nPerPage' => 500000,
                'nPage' => $nPage,
                'tCompName' => $this->tCompName,
                'tRptCode' => $tRptCode,
                'tUsrSessionID' => $this->session->userdata('tSesSessionID'),
            );
            $aDataReport = $this->mRptMovePosVd->FSaMGetDataReport($aDataWhereRpt);
            
            // ตั้งค่ารายงาน
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
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);


            // ชื่อหัวรายงาน
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:K1');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $aDataTextRef['tTitleReport']);
            $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($aStyleRptSizeTitleName);
            // ====================================================================================================================================================================
            // Check Address Data
            if (isset($this->aCompanyInfo) && !empty($this->aCompanyInfo )) {
                $tRptCompName = (empty($this->aCompanyInfo['FTCmpName'])) ? '-' : $this->aCompanyInfo['FTCmpName'];
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', $tRptCompName)->getStyle('A2')->applyFromArray($aStyleRptSizeCompName);
            
                
                //Check Vertion Address
                if($this->aCompanyInfo['FTAddVersion'] == 1){
                    // Check Address Line 1
                    $tRptAddV1No = (empty($this->aCompanyInfo['FTAddV1No'])) ? '-' : $this->aCompanyInfo['FTAddV1No'];
                    $tRptAddV1Road = (empty($this->aCompanyInfo['FTAddV1Road'])) ? '-' : $this->aCompanyInfo['FTAddV1Road'];
                    $tRptAddV1Soi = (empty($this->aCompanyInfo['FTAddV1Soi'])) ? '-' : $this->aCompanyInfo['FTAddV1Soi'];
                    $tRptAddAdasoft = (empty($this->aCompanyInfo['FTAddV1Village'])) ? '-' : $this->aCompanyInfo['FTAddV1Village'];
                    $tRptAddressLine1 = $tRptAddV1No . ' '.$tRptAddAdasoft. ' ' . $this->aText['tRptAddrRoad'] . ' ' . $tRptAddV1Road . ' ' . $this->aText['tRptAddrSoi'] . ' ' . $tRptAddV1Soi;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', $tRptAddressLine1)->getStyle('A3')->applyFromArray($aStyleRptSizeAddressFont);

                    // Check Address Line 2
                    $tRptAddV1SubDistName = (empty($this->aCompanyInfo['FTSudName'])) ? '-' : $this->aCompanyInfo['FTSudName'];
                    $tRptAddV1DstName = (empty($this->aCompanyInfo['FTDstName'])) ? '-' : $this->aCompanyInfo['FTDstName'];
                    $tRptAddV1PvnName = (empty($this->aCompanyInfo['FTPvnName'])) ? '-' : $this->aCompanyInfo['FTPvnName'];
                    $tRptAddV1PostCode = (empty($this->aCompanyInfo['FTAddV1PostCode'])) ? '-' : $this->aCompanyInfo['FTAddV1PostCode'];
                    $tRptAddressLine2 = $tRptAddV1SubDistName . ' ' . $tRptAddV1DstName . ' ' . $tRptAddV1PvnName . ' ' . $tRptAddV1PostCode;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', $tRptAddressLine2)->getStyle('A4')->applyFromArray($aStyleRptSizeAddressFont);
                }else{
                    $tRptAddV2Desc1 = (empty($this->aCompanyInfo['FTAddV2Desc1'])) ? '-' : $this->aCompanyInfo['FTAddV2Desc1'];
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', $tRptAddV2Desc1)->getStyle('A3')->applyFromArray($aStyleRptSizeAddressFont);

                    $tRptAddV2Desc2 = (empty($this->aCompanyInfo['FTAddV2Desc2'])) ? '-' : $this->aCompanyInfo['FTAddV2Desc2'];
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', $tRptAddV2Desc2)->getStyle('A4')->applyFromArray($aStyleRptSizeAddressFont);
                }

                // Check Data Telephone Number
                if (isset($this->aCompanyInfo['FTCmpTel']) && !empty($this->aCompanyInfo['FTCmpTel'])) {
                    $tRptCompTel = $this->aCompanyInfo['FTCmpTel'];
                } else {
                    $tRptCompTel = '-';
                }

                if(isset($this->aCompanyInfo['FTCmpFax']) && !empty($this->aCompanyInfo['FTCmpFax'])) {
                    $tRptCmpFax = $this->aCompanyInfo['FTCmpFax'];
                }else{
                    $tRptCmpFax = '-';
                }
                $tRptCompTelText = $this->aText['tRptAddrTel'] . ' ' . $tRptCompTel. ' ' .$this->aText['tRptAddrFax']. ' ' .$tRptCmpFax;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', $tRptCompTelText)->getStyle('A5')->applyFromArray($aStyleRptSizeAddressFont);

                //Check Data Branch
                if(isset($this->aCompanyInfo['FTBchName']) && !empty($this->aCompanyInfo['FTBchName'])){
                    $tRptBchName = $this->aCompanyInfo['FTBchName'];
                }else{
                    $tRptBchName = '-';
                }    
                $tRptBchName = $this->aText['tRptBarchName'] . ' ' . $tRptBchName;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A6', $tRptBchName)->getStyle('A6')->applyFromArray($aStyleRptSizeAddressFont);   
            }

            // ====================================================================================================================================================================
            // ฟิวเตอร์ข้อมูลรายงาน =================================================================================================================================================== 
            // Row เริ่มต้นของ Filter
            $nStartRowFillter = 2;
            $tFillterColumLEFT = "E";
            $tFillterColumRIGHT = "G";


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

            // Fillter Product (สินค้า)
            if (!empty($aDataFilter['tPdtCodeFrom']) && !empty($aDataFilter['tPdtCodeTo'])) {
                $tRptFilterBchCodeFrom = $aDataTextRef['tPdtCodeFrom'] . ' ' . $aDataFilter['tPdtNameFrom'];
                $tRptFilterBchCodeTo = $aDataTextRef['tPdtCodeTo'] . ' ' . $aDataFilter['tPdtNameTo'];
                $tRptTextLeftRightFilter = $tRptFilterBchCodeFrom . ' ' . $tRptFilterBchCodeTo;
                $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                $nStartRowFillter += 1;
            }

            // Fillter WareHouse (คลังสินค้า)
            if (!empty($aDataFilter['tWahCodeFrom']) && !empty($aDataFilter['tWahCodeTo'])) {
                $tRptFilterDocDateFrom = $aDataTextRef['tRptFromWareHouse'] . ' ' . $aDataFilter['tWahNameFrom'];
                $tRptFilterDocDateTo = $aDataTextRef['tRptToWareHouse'] . ' ' . $aDataFilter['tWahNameTo'];
                $tRptTextLeftRightFilter = $tRptFilterDocDateFrom . ' ' . $tRptFilterDocDateTo;
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
            // ====================================================================================================================================================================
            // ตั้งค่าวันที่เวลาออกรายงาน ===============================================================================================================================================
            $tRptDateTimeExportText = $aDataTextRef['tDatePrint'] . ' ' . $dDatePrint . ' ' . $aDataTextRef['tTimePrint'] . ' ' . $dTimePrint;
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:K7');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A7', $tRptDateTimeExportText);
            $objPHPExcel->getActiveSheet()->getStyle("A7")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($aStyleRptSizeAddressFont);
            // ====================================================================================================================================================================
            // หัวตารางรายงาน =======================================================================================================================================================
            // กำหนดจุดเริ่มต้นของแถวหัวตาราง
            $nStartRowHeadder = 8;
            // กำหนด Style Font ของหัวตาราง
            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':K' . $nStartRowHeadder)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':K' . $nStartRowHeadder)->applyFromArray($aStyleRptHeadderTable);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':K' . $nStartRowHeadder)->applyFromArray(array(
                'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))
                )
            ));
            // กำหนดข้อมูลลงหัวตาราง
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $nStartRowHeadder, $aDataTextRef['tWahName'])
                    ->setCellValue('B' . $nStartRowHeadder, $aDataTextRef['tPdtCode'])
                    ->setCellValue('C' . $nStartRowHeadder, $aDataTextRef['tPdtName'])
                    ->setCellValue('D' . $nStartRowHeadder, $aDataTextRef['tRptDoc'])
                    ->setCellValue('E' . $nStartRowHeadder, $aDataTextRef['tRptDate'])
                    ->setCellValue('F' . $nStartRowHeadder, $aDataTextRef['tRptBringF'])
                    ->setCellValue('G' . $nStartRowHeadder, $aDataTextRef['tRptIn'])
                    ->setCellValue('H' . $nStartRowHeadder, $aDataTextRef['tRptEx'])
                    ->setCellValue('I' . $nStartRowHeadder, $aDataTextRef['tRptSale'])
                    ->setCellValue('J' . $nStartRowHeadder, $aDataTextRef['tRptAdjud'])
                    ->setCellValue('K' . $nStartRowHeadder, $aDataTextRef['tRptInven']);

            // Alignment ของหัวตาราง
            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':K' . $nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // ====================================================================================================================================================================
            // วนลูปข้อมูลตารางข้อมูล =================================================================================================================================================
            // Set Variable Data
            $nStartRowData = $nStartRowHeadder + 1;
            if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
                // ******* Step 1 ประกาศตัวแปรใช้ในการเช็คเงื่อนไข
                $tGrouppingData = "";
                $tGrouppingDataPdt = "";
                $nGroupMember = "";
                $nRowPartID = "";

                $nSubSumStkQtyBal = "";
                $nSubSumStkQtyMonEnd = "";
                $nSubSumStkQtyIn = "";
                $nSubSumStkQtyOut = "";
                $nSubSumStkQtySale = "";
                $nSubStkQtyAdj = "";

                $nSumFooterStkQtyBal = "";
                $nSumFooterStkQtyMonEnd = "";
                $nSumFooterSFCStkQtyIn = "";
                $nSumFooterStkQtyOut = "";
                $nSumFooterStkQtySale = "";
                $nSumFooterStkQtyAdj = "";

                foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                    // ******* Step 3 Set ค่า Value FNRptGroupMember And FNRowPartID
                    $nRowPartID = $aValue["FNRowPartID"];
                    $nGroupMember = $aValue["FNRptGroupMember"];

                    $nStkQtySale = $aValue["FCStkQtySaleDN"];
                    $nStkQtyCN = $aValue["FCStkQtyCN"];
                    $nStkQtySale = $nStkQtySale - $nStkQtyCN;
                    if ($nStkQtySale > 0) {
                        $nSaleNumber = "-";
                    } else {
                        $nSaleNumber = "";
                    }
                    // ******* Step 4 : Check Groupping Create Row Groupping 
                    if ($tGrouppingDataPdt != $aValue['FTPdtCode'] || $tGrouppingData != $aValue['FTWahCode']) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $aValue['FTWahName']);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $nStartRowData, $aValue['FTPdtCode']);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $nStartRowData, $aValue['FTPdtName']);

                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':K' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
                        $nStartRowData++;
                    }

                    // ******* Step 5 : Loop Set Data Value 

                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $nStartRowData, '')
                            ->setCellValue('B' . $nStartRowData, '')
                            ->setCellValue('C' . $nStartRowData, '')
                            ->setCellValue('D' . $nStartRowData, $aValue["FTStkDocNo"])
                            ->setCellValue('E' . $nStartRowData, $aValue["FDStkDate"])
                            ->setCellValue('F' . $nStartRowData, number_format($aValue["FCStkQtyMonEnd"], 2))
                            ->setCellValue('G' . $nStartRowData, number_format($aValue["FCStkQtyIn"], 2))
                            ->setCellValue('H' . $nStartRowData, number_format($aValue["FCStkQtyOut"], 2))
                            ->setCellValue('I' . $nStartRowData, -number_format($nStkQtySale, 2))
                            ->setCellValue('J' . $nStartRowData, number_format($aValue['FCStkQtyAdj'], 2))
                            ->setCellValue('K' . $nStartRowData, number_format($aValue['FCStkQtyBal'], 2));

                    $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData . ':K' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('D' . $nStartRowData . ':E' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    // $objPHPExcel->getActiveSheet()->getStyle('D'.$nStartRowData.':H'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    // ******* Step 6 : เตรียม Parameter สำหรับ Summary Sub Footer
                    if ($nRowPartID == $nGroupMember) {
                        $nStartRowData++;

                        $nSubSumStkQtyBal = number_format($aValue["FCStkQtyBal_SUB"], 2);
                        $nSubSumStkQtyMonEnd = $aValue["FCStkQtyMonEnd_SUB"];
                        $nSubSumStkQtyIn = $aValue["FCStkQtyIn_SUB"];
                        $nSubSumStkQtyOut = $aValue["FCStkQtyOut_SUB"];
                        $nSubSumStkQtySale = $aValue["FCStkQtySale_SUB"];
                        $nSubStkQtyAdj = $aValue["FCStkQtyAdj_SUB"];

                        // Set Color Row Sub Footer
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':K' . $nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':K' . $nStartRowData)->applyFromArray(array(
                            'borders' => array(
                                'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))
                            )
                        ));

                        // Text Sum Sub
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':E' . $nStartRowData);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $aDataTextRef['tRptTotalSub']);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);

                        // Value Sum Sub
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('F' . $nStartRowData, $nSubSumStkQtyMonEnd)
                                ->setCellValue('G' . $nStartRowData, $nSubSumStkQtyIn)
                                ->setCellValue('H' . $nStartRowData, $nSubSumStkQtyOut)
                                ->setCellValue('I' . $nStartRowData, -$nSubSumStkQtySale)
                                ->setCellValue('J' . $nStartRowData, $nSubStkQtyAdj)
                                ->setCellValue('K' . $nStartRowData, $nSubSumStkQtyBal);

                        $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData . ':K' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData . ':K' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    }

                    $nSumFooterStkQtyBal = number_format($aValue["FCStkQtyBal_Footer"], 2);
                    $nSumFooterStkQtyMonEnd = number_format($aValue["FCStkQtyMonEnd_Footer"], 2);
                    $nSumFooterSFCStkQtyIn = number_format($aValue["FCStkQtyIn_Footer"], 2);
                    $nSumFooterStkQtyOut = number_format($aValue["FCStkQtyOut_Footer"], 2);
                    $nSumFooterStkQtySale = -number_format($aValue["FCStkQtySale_Footer"], 2);
                    $nSumFooterStkQtyAdj = number_format($aValue["FCStkQtyAdj_Footer"], 2);

                    // ******* Step 2 Set ค่า Value ให้กับตัวแปร
                    $tGrouppingData = $aValue["FTWahCode"];
                    $tGrouppingDataPdt = $aValue["FTPdtCode"];
                    $nStartRowData++;
                }

                // Step 7 : Set Footer Text
                $nPageNo = $aDataReport["aPagination"]["nDisplayPage"];
                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                if ($nPageNo == $nTotalPage) {
                    // Set Color Row Sub Footer
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':K' . $nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':K' . $nStartRowData)->applyFromArray(array(
                        'borders' => array(
                            'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                            'bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE, 'color' => array('rgb' => '000000'))
                        )
                    ));

                    // Text Sum Footer
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':E' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $aDataTextRef['tRptOverall']);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);

                    // Value Sum Footer
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('F' . $nStartRowData, $nSumFooterStkQtyMonEnd)
                            ->setCellValue('G' . $nStartRowData, $nSumFooterSFCStkQtyIn)
                            ->setCellValue('H' . $nStartRowData, $nSumFooterStkQtyOut)
                            ->setCellValue('I' . $nStartRowData, $nSumFooterStkQtySale)
                            ->setCellValue('J' . $nStartRowData, $nSumFooterStkQtyAdj)
                            ->setCellValue('K' . $nStartRowData, $nSumFooterStkQtyBal);

                    $objPHPExcel->getActiveSheet()->getStyle('K' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData . ':H' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData . ':K' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                }
            } else {
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':K' . $nStartRowData);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $aDataTextRef['tRptAdjStkNoData']);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
            }

            // ====================================================================================================================================================================
            // Set Content Type Export File Excel =================================================================================================================================
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
            // ====================================================================================================================================================================
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
     * Creator: 16/08/2019 Wasin(Yoshi)
     * LastUpdate: 24/09/2019 Piya
     * Return: object Status Count Data Report
     * ReturnType: Object
     */
    public function FSoCChkDataReportInTableTemp($paDataSwitchCase) {
        try {
            $aDataCountData = [
                'tCompName' => $paDataSwitchCase['paDataFilter']['tCompName'],
                'tRptCode' => $paDataSwitchCase['paDataFilter']['tRptCode'],
                'tUserSession' => $paDataSwitchCase['paDataFilter']['tUserSession'],
            ];

            $nDataCountPage = $this->mRptMovePosVd->FSnMCountDataReportAll($aDataCountData);

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
     * Creator: 16/08/2019 Wasin(Yoshi)
     * LastUpdate: 24/09/2019 Piya
     * Return: object Send Rabbit MQ Report
     * ReturnType: Object
     */
    public function FSvCCallRptExportFile() {
        try {
            $dDateSendMQ = date('Y-m-d');
            $dTimeSendMQ = date('H:i:s');
            $dDateSubscribe = date('Ymd');
            $dTimeSubscribe = date('His');

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
                'tMessage' => $Error->getMessage()
            );
        }
        echo json_encode($aResponse);
    }

}




