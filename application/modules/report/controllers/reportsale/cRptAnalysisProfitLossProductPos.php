<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

class cRptAnalysisProfitLossProductPos extends MX_Controller {

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
    
    public function __construct($paParams = []){
        $this->load->helper('report');
        $this->load->library('zip');
        $this->load->model('company/company/mCompany');
        $this->load->model('report/report/mReport');
        $this->load->model('report/reportsale/mRptAnalysisProfitLossProductPos');
        
        // Init Report
        $this->init($paParams);
        
        parent::__construct ();
    }
    
    private function init($paParams = []){
        $this->aText = [
            'tTitleReport' => language('report/report/report', 'tRptAnalysisProfitLossProductPosTitle'),
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
            
            'tRptDateFrom' => language('report/report/report', 'tRptDateFrom'),
            'tRptDateTo' => language('report/report/report', 'tRptDateTo'),
            'tRptBchFrom' => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo' => language('report/report/report', 'tRptBchTo'),
            'tRptMerFrom' => language('report/report/report', 'tRptMerFrom'),
            'tRptMerTo' => language('report/report/report', 'tRptMerTo'),
            'tRptShopFrom' => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo' => language('report/report/report', 'tRptShopTo'),
            'tRptPosFrom' => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo' => language('report/report/report', 'tRptPosTo'),
            'tPdtCodeFrom' => language('report/report/report', 'tPdtCodeFrom'),
            'tPdtCodeTo' => language('report/report/report', 'tPdtCodeTo'),
            'tPdtGrpFrom' => language('report/report/report', 'tPdtGrpFrom'),
            'tPdtGrpTo' => language('report/report/report', 'tPdtGrpTo'),
            'tPdtTypeFrom' => language('report/report/report', 'tPdtTypeFrom'),
            'tPdtTypeTo' => language('report/report/report', 'tPdtTypeTo'),
            // Text Label
            'tRptPdtCode' => language('report/report/report', 'tRptPdtCode'),
            'tRptPdtName' => language('report/report/report', 'tRptPdtName'),
            'tRptPdtGrp' => language('report/report/report', 'tRptPdtGrp'),
            'tRptQtySale' => language('report/report/report', 'tRptQtySale'),
            'tRptSaleQty' => language('report/report/report', 'tRptAnalysisProfitLossProductPosSaleQty'),
            'tRptGrandSale' => language('report/report/report', 'tRptGrandSale'),
            'tRptProfitloss' => language('report/report/report', 'tRptProfitloss'),
            'tRptCost' => language('report/report/report', 'tRptCost'),
            'tRptSalesVending' => language('report/report/report', 'tRptSalesVending'),
            'tRptCabinetCost' => language('report/report/report', 'tRptCabinetCost'),
            'tRptTotalSale' => language('report/report/report', 'tRptTotalSale'),
            'tRptProfitandLost' => language('report/report/report', 'tRptProfitandLost'),
            'tRptGrandtotal' => '%' . language('report/report/report', 'tRptGrandtotal'),
            'tRptCapital' => '%' . language('report/report/report', 'tRptCapital'),
            'tRptTaxSalePosTel' => language('report/report/report', 'tRptTaxSalePosTel'),
            'tRptTaxSalePosFax' => language('report/report/report', 'tRptTaxSalePosFax'),
            'tRptTaxSalePosDatePrint' => language('report/report/report', 'tRptTaxSalePosDatePrint'),
            'tRptTaxSalePosTimePrint' => language('report/report/report', 'tRptTaxSalePosTimePrint'),
            'tRptTaxSalePosBch' => language('report/report/report', 'tRptTaxSalePosBch'),
            'tRptDataReportNotFound' => language('report/report/report', 'tRptDataReportNotFound'),
            'tRptRentAmtFolCourSumText' => language('report/report/report', 'tRptRentAmtFolCourSumText'),
            'tRptTotalSub' => language('report/report/report', 'tRptTotalSub')
        ];
        
        $this->tBchCodeLogin        = $paParams['tBchCodeLogin'];
        $this->nPerPage             = $paParams['nPerFile'];
        $this->nOptDecimalShow      = FCNxHGetOptionDecimalShow();
        $this->tCompName            = $paParams['tCompName'];
        $this->nLngID               = $paParams['nLngID'];
        $this->tRptCode             = $paParams['tRptCode'];
        $this->tUserSessionID       = $paParams['tUserSessionID'];
        $this->tRptRoute            = $this->input->post('ohdRptRoute');
        $this->tRptExportType       = $paParams['tRptExpType'];
        $this->nPage                = empty($this->input->post('ohdRptCurrentPage')) ? 1 : $this->input->post('ohdRptCurrentPage');
        $this->tUserLoginCode       = $paParams['tUserCode'];
        
        // Report Filter
        $this->aRptFilter = [
            // สาขา(Branch)
            'tBchCodeFrom'      => (isset($paParams['aFilter']['tBchCodeFrom']) && !empty($paParams['aFilter']['tBchCodeFrom'])) ? $paParams['aFilter']['tBchCodeFrom'] : "",
            'tBchNameFrom'      => (isset($paParams['aFilter']['tBchNameFrom']) && !empty($paParams['aFilter']['tBchNameFrom'])) ? $paParams['aFilter']['tBchNameFrom'] : "",
            'tBchCodeTo'        => (isset($paParams['aFilter']['tBchCodeTo']) && !empty($paParams['aFilter']['tBchCodeTo'])) ? $paParams['aFilter']['tBchCodeTo'] : "",
            'tBchNameTo'        => (isset($paParams['aFilter']['tBchNameTo']) && !empty($paParams['aFilter']['tBchNameTo'])) ? $paParams['aFilter']['tBchNameTo'] : "",
            // กลุ่มธุรกิจ
            'tRptMerCodeFrom'   => (isset($paParams['aFilter']['tRptMerCodeFrom']) && !empty($paParams['aFilter']['tRptMerCodeFrom'])) ? $paParams['aFilter']['tRptMerCodeFrom'] : "",
            'tRptMerNameFrom'   => (isset($paParams['aFilter']['tRptMerNameFrom']) && !empty($paParams['aFilter']['tRptMerNameFrom'])) ? $paParams['aFilter']['tRptMerNameFrom'] : "",
            'tRptMerCodeTo'     => (isset($paParams['aFilter']['tRptMerCodeTo']) && !empty($paParams['aFilter']['tRptMerCodeTo'])) ? $paParams['aFilter']['tRptMerCodeTo'] : "",
            'tRptMerNameTo'     => (isset($paParams['aFilter']['tRptMerNameTo']) && !empty($paParams['aFilter']['tRptMerNameTo'])) ? $paParams['aFilter']['tRptMerNameTo'] : "",
            // ร้านค้า(Shop)
            'tShopCodeFrom'     => (isset($paParams['aFilter']['tShopCodeFrom']) && !empty($paParams['aFilter']['tShopCodeFrom'])) ? $paParams['aFilter']['tShopCodeFrom'] : "",
            'tShopNameFrom'     => (isset($paParams['aFilter']['tShopNameFrom']) && !empty($paParams['aFilter']['tShopNameFrom'])) ? $paParams['aFilter']['tShopNameFrom'] : "",
            'tShopCodeTo'       => (isset($paParams['aFilter']['tShopCodeTo']) && !empty($paParams['aFilter']['tShopCodeTo'])) ? $paParams['aFilter']['tShopCodeTo'] : "",
            'tShopNameTo'       => (isset($paParams['aFilter']['tShopNameTo']) && !empty($paParams['aFilter']['tShopNameTo'])) ? $paParams['aFilter']['tShopNameTo'] : "",
            // เครื่องจุดขาย
            'tRptPosCodeFrom'   => (isset($paParams['aFilter']['tRptPosCodeFrom']) && !empty($paParams['aFilter']['tRptPosCodeFrom'])) ? $paParams['aFilter']['tRptPosCodeFrom'] : "",
            'tRptPosNameFrom'   => (isset($paParams['aFilter']['tRptPosNameFrom']) && !empty($paParams['aFilter']['tRptPosNameFrom'])) ? $paParams['aFilter']['tRptPosNameFrom'] : "",
            'tRptPosCodeTo'     => (isset($paParams['aFilter']['tRptPosCodeTo']) && !empty($paParams['aFilter']['tRptPosCodeTo'])) ? $paParams['aFilter']['tRptPosCodeTo'] : "",
            'tRptPosNameTo'     => (isset($paParams['aFilter']['tRptPosNameTo']) && !empty($paParams['aFilter']['tRptPosNameTo'])) ? $paParams['aFilter']['tRptPosNameTo'] : "",
            // สินค้า
            'tPdtCodeFrom'      => (isset($paParams['aFilter']['tRptPdtCodeFrom']) && !empty($paParams['aFilter']['tRptPdtCodeFrom'])) ? $paParams['aFilter']['tRptPdtCodeFrom'] : "",
            'tPdtNameFrom'      => (isset($paParams['aFilter']['tRptPdtNameFrom']) && !empty($paParams['aFilter']['tRptPdtNameFrom'])) ? $paParams['aFilter']['tRptPdtNameFrom'] : "",
            'tPdtCodeTo'        => (isset($paParams['aFilter']['tRptPdtCodeTo']) && !empty($paParams['aFilter']['tRptPdtCodeTo'])) ? $paParams['aFilter']['tRptPdtCodeTo'] : "",
            'tPdtNameTo'        => (isset($paParams['aFilter']['tRptPdtNameTo']) && !empty($paParams['aFilter']['tRptPdtNameTo'])) ? $paParams['aFilter']['tRptPdtNameTo'] : "",
            // กลุ่มสินค้า(Product Group)
            'tPdtGrpCodeFrom'   => (isset($paParams['aFilter']['tPdtGrpCodeFrom']) && !empty($paParams['aFilter']['tPdtGrpCodeFrom'])) ? $paParams['aFilter']['tPdtGrpCodeFrom'] : "",
            'tPdtGrpNameFrom'   => (isset($paParams['aFilter']['tPdtGrpNameFrom']) && !empty($paParams['aFilter']['tPdtGrpNameFrom'])) ? $paParams['aFilter']['tPdtGrpNameFrom'] : "",
            'tPdtGrpCodeTo'     => (isset($paParams['aFilter']['tPdtGrpCodeTo']) && !empty($paParams['aFilter']['tPdtGrpCodeTo'])) ? $paParams['aFilter']['tPdtGrpCodeTo'] : "",
            'tPdtGrpNameTo'     => (isset($paParams['aFilter']['tPdtGrpNameTo']) && !empty($paParams['aFilter']['tPdtGrpNameTo'])) ? $paParams['aFilter']['tPdtGrpNameTo'] : "",
            // ประเภทสินค้า(Product Type)
            'tPdtTypeCodeFrom'  => (isset($paParams['aFilter']['tPdtTypeCodeFrom']) && !empty($paParams['aFilter']['tPdtTypeCodeFrom'])) ? $paParams['aFilter']['tPdtTypeCodeFrom'] : "",
            'tPdtTypeNameFrom'  => (isset($paParams['aFilter']['tPdtTypeNameFrom']) && !empty($paParams['aFilter']['tPdtTypeNameFrom'])) ? $paParams['aFilter']['tPdtTypeNameFrom'] : "",
            'tPdtTypeCodeTo'    => (isset($paParams['aFilter']['tPdtTypeCodeTo']) && !empty($paParams['aFilter']['tPdtTypeCodeTo'])) ? $paParams['aFilter']['tPdtTypeCodeTo'] : "",
            'tPdtTypeNameTo'    => (isset($paParams['aFilter']['tPdtTypeNameTo']) && !empty($paParams['aFilter']['tPdtTypeNameTo'])) ? $paParams['aFilter']['tPdtTypeNameTo'] : "",
            // วันที่เอกสาร(DocNo)
            'tDocDateFrom'      => (isset($paParams['aFilter']['tDocDateFrom']) && !empty($paParams['aFilter']['tDocDateFrom'])) ? $paParams['aFilter']['tDocDateFrom'] : "",
            'tDocDateTo'        => (isset($paParams['aFilter']['tDocDateTo']) && !empty($paParams['aFilter']['tDocDateTo'])) ? $paParams['aFilter']['tDocDateTo'] : ""
        ];
        
        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID'    => $this->nLngID,
            'tBchCode'  => $this->tBchCodeLogin
        ];
        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
                
    }

    /**
     * Functionality: Excel Report
     * Parameters:  Function Parameter
     * Creator: 22/07/2019 Piya
     * LastUpdate: 16/09/2019 Piya
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptRenderExcel($paParams = []){
        /** =========== Begin Init Variable ==================================*/
        $tReportName    = $this->aText['tTitleReport'];
        $dDateExport    = date('Y-m-d');
        $tTime          = date('H:i:s');
        $nFile          = $paParams['nFile'];
        $bIsLastFile    = $paParams['bIsLastFile'];
        $tFileName      = $paParams['tFileName'];
        $aDataFilter    = $this->aRptFilter;
        $aCompanyInfo   = $this->aCompanyInfo;
        /** =========== End Init Variable ====================================*/


        /** =========== Begin Get Data =======================================*/
        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aGetDataReportParams = array(
            'nPerPage'          => $this->nPerPage,
            'nPage'             => $nFile,
            'tCompName'         => $this->tCompName,
            'tRptCode'          => $this->tRptCode,
            'tUserSessionID'    => $this->tUserSessionID
        );
        // Get Data Report FSaMGetDataReport
        $aDataReport = $this->mRptAnalysisProfitLossProductPos->FSaMGetDataReport($aGetDataReportParams);

        // GetDataSumFootReport
        $aDataSumFoot = $this->mRptAnalysisProfitLossProductPos->FSaMGetDataSumFootReport($aGetDataReportParams);
        /** =========== End Get Data =========================================*/
            
        try{
            if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
                // ตั้งค่า Font Style
                $aStyleRptFont              = array('font' => array('name' => 'TH Sarabun New'));
                $aStyleRptSizeTitleName     = array('font' => array('size' => 14));
                $aStyleRptSizeCompName      = array('font' => array('size' => 12));
                $aStyleRptSizeAddressFont   = array('font' => array('size' => 12));
                $aStyleRptHeadderTable      = array('font' => array('size' => 12, 'color' => array('rgb' => '000000')));
                $aStyleRptDataTable         = array('font' => array('size' => 10, 'color' => array('rgb' => '000000')));

                // Initiate PHPExcel cache
                $oCacheMethod   = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
                $aCacheSettings = array(' memoryCacheSize ' => '8000MB', 'cacheTime' => 3600 * 120);
                PHPExcel_Settings::setCacheStorageMethod($oCacheMethod, $aCacheSettings);

                // เริ่ม phpExcel
                $objPHPExcel = new PHPExcel();

                // A4 ตั้งค่าหน้ากระดาษ
                $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

                // Set Font Style
                $objPHPExcel->getActiveSheet()->getStyle('A1:Z1000')->applyFromArray($aStyleRptFont);

                // จัดความกว้างของคอลัมน์
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);

                // ชื่อหัวรายงาน
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:I1');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $tReportName);
                $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($aStyleRptSizeTitleName);

                // Check Address Data
                if (isset($aCompanyInfo) && !empty($aCompanyInfo)) {
                    // Company Name
                    $tRptCompName = (empty($aCompanyInfo['FTCmpName'])) ? '-' : $aCompanyInfo['FTCmpName'];
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', $tRptCompName)->getStyle('A2')->applyFromArray($aStyleRptSizeCompName);

                    // Check Vertion Address
                    if ($aCompanyInfo['FTAddVersion'] == 1) {
                        // Check Address Line 1
                        $tRptAddV1No = (empty($aCompanyInfo['FTAddV1No'])) ? '-' : $aCompanyInfo['FTAddV1No'];
                        $tRptAddV1Village = (empty($aCompanyInfo['FTAddV1Village'])) ? '-' : $aCompanyInfo['FTAddV1Village'];
                        $tRptAddV1Road = (empty($aCompanyInfo['FTAddV1Road'])) ? '-' : $aCompanyInfo['FTAddV1Road'];
                        $tRptAddV1Soi = (empty($aCompanyInfo['FTAddV1Soi'])) ? '-' : $aCompanyInfo['FTAddV1Soi'];
                        $tRptAddressLine1 = $tRptAddV1No . ' ' . $tRptAddV1Village . ' ' . $this->aText['tRptAddrRoad'] . ' ' . $tRptAddV1Road . ' ' . $tRptAddV1Soi;
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', $tRptAddressLine1)->getStyle('A3')->applyFromArray($aStyleRptSizeAddressFont);

                        // Check Address Line 2
                        $tRptAddV1SubDistName = (empty($aCompanyInfo['FTSudName'])) ? '-' : $aCompanyInfo['FTSudName'];
                        $tRptAddV1DstName = (empty($aCompanyInfo['FTDstName'])) ? '-' : $aCompanyInfo['FTDstName'];
                        $tRptAddV1PvnName = (empty($aCompanyInfo['FTPvnName'])) ? '-' : $aCompanyInfo['FTPvnName'];
                        $tRptAddV1PostCode = (empty($aCompanyInfo['FTAddV1PostCode'])) ? '-' : $aCompanyInfo['FTAddV1PostCode'];
                        $tRptAddressLine2 = $tRptAddV1SubDistName . ' ' . $tRptAddV1DstName . ' ' . $tRptAddV1PvnName . ' ' . $tRptAddV1PostCode;
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', $tRptAddressLine2)->getStyle('A4')->applyFromArray($aStyleRptSizeAddressFont);
                    } else {
                        $tRptAddV2Desc1 = (empty($aCompanyInfo['FTAddV2Desc1'])) ? '-' : $aCompanyInfo['FTAddV2Desc1'];
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', $tRptAddV2Desc1)->getStyle('A3')->applyFromArray($aStyleRptSizeAddressFont);

                        $tRptAddV2Desc2 = (empty($aCompanyInfo['FTAddV2Desc2'])) ? '-' : $aCompanyInfo['FTAddV2Desc2'];
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', $tRptAddV2Desc2)->getStyle('A4')->applyFromArray($aStyleRptSizeAddressFont);
                    }

                    // Check Data Telephone Number
                    if (isset($aCompanyInfo['FTCmpTel']) && !empty($aCompanyInfo['FTCmpTel'])) {
                        $tRptCompTel = $aCompanyInfo['FTCmpTel'];
                    } else {
                        $tRptCompTel = '-';
                    }
                    $tRptCompTelText = $this->aText['tRptAddrTel'] . ' ' . $tRptCompTel;

                    // Check Data Fax Number
                    if (isset($aCompanyInfo['FTCmpFax']) && !empty($aCompanyInfo['FTCmpFax'])) {
                        $tRptCompFax = $aCompanyInfo['FTCmpFax'];
                    } else {
                        $tRptCompFax = '-';
                    }
                    $tRptCompFaxText = $this->aText['tRptAddrFax'] . ' ' . $tRptCompFax;
                    
                    $tRptAddressLine4 = $tRptCompTelText . ' ' . $tRptCompFaxText;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', $tRptAddressLine4)->getStyle('A5')->applyFromArray($aStyleRptSizeAddressFont);

                    
                    // Check Branch
                    if(isset($aCompanyInfo['FTBchName']) && !empty($aCompanyInfo['FTBchName'])) {
                        $tRptCompBch = $aCompanyInfo['FTBchName'];
                    }else{
                        $tRptCompBch = '-';
                    }
                    $tRptAddressLine5 = $this->aText['tRptAddrBranch'] . ' ' . $tRptCompBch;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A6', $tRptAddressLine5)->getStyle('A6')->applyFromArray($aStyleRptSizeAddressFont);
                }

                // ======================================================================== Filter Data Report ========================================================================
                // Row เริ่มต้นของ Filter
                $nStartRowFillter = 2;
                $tFillterColumLEFT = "D";
                $tFillterColumRIGHT = "F";

                // Fillter ฺRcv (รูปแบบการชำระเงิน)
                if (!empty($this->aRptFilter['tRcvCodeFrom']) && !empty($this->aRptFilter['tRcvCodeTo'])) {
                    $tRptFilterRcvCodeFrom = $this->aText['tPdtTypeFrom'] . ' ' . $this->aRptFilter['tRcvNameFrom'];
                    $tRptFilterRcvCodeTo = $this->aText['tPdtTypeTo'] . ' ' . $this->aRptFilter['tRcvNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterRcvCodeFrom . ' ' . $tRptFilterRcvCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }

                // Fillter ฺBranch (สาขา)
                if (!empty($this->aRptFilter['tBchCodeFrom']) && !empty($this->aRptFilter['tBchCodeTo'])) {
                    $tRptFilterBranchCodeFrom = $this->aText['tRptBchFrom'] . ' ' . $this->aRptFilter['tBchNameFrom'];
                    $tRptFilterBranchCodeTo = $this->aText['tRptBchTo'] . ' ' . $this->aRptFilter['tBchNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterBranchCodeFrom . ' ' . $tRptFilterBranchCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }

                // Fillter กลุ่มธุระกิจ
                if (!empty($this->aRptFilter['tRptMerCodeFrom']) && !empty($this->aRptFilter['tRptMerCodeTo'])) {
                    $tRptFilterBranchCodeFrom = $this->aText['tRptMerFrom'] . ' ' . $this->aRptFilter['tRptMerNameFrom'];
                    $tRptFilterBranchCodeTo = $this->aText['tRptMerTo'] . ' ' . $this->aRptFilter['tRptMerNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterBranchCodeFrom . ' ' . $tRptFilterBranchCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }

                // Fillter Shop (ร้านค้า)
                if (!empty($this->aRptFilter['tRptShpCodeFrom']) && !empty($this->aRptFilter['tRptShpCodeTo'])) {
                    $tRptFilterShopCodeFrom = $this->aText['tRptShopFrom'] . ' ' . $this->aRptFilter['tRptShpNameFrom'];
                    $tRptFilterShopCodeTo = $this->aText['tRptShopTo'] . ' ' . $this->aRptFilter['tRptShpNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterShopCodeFrom . ' ' . $tRptFilterShopCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }

                // Fillter เครื่องจุดขาย
                if (!empty($this->aRptFilter['tRptPosCodeFrom']) && !empty($this->aRptFilter['tRptPosCodeTo'])) {
                    $tRptFilterBranchCodeFrom = $this->aText['tRptPosFrom'] . ' ' . $this->aRptFilter['tRptPosNameFrom'];
                    $tRptFilterBranchCodeTo = $this->aText['tRptPosTo'] . ' ' . $this->aRptFilter['tRptPosNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterBranchCodeFrom . ' ' . $tRptFilterBranchCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }

                // Fillter สินค้า
                if (!empty($this->aRptFilter['tRptPdtCodeFrom']) && !empty($this->aRptFilter['tRptPdtCodeTo'])) {
                    $tRptFilterBranchCodeFrom = $this->aText['tPdtCodeFrom'] . ' ' . $this->aRptFilter['tRptPdtNameFrom'];
                    $tRptFilterBranchCodeTo = $this->aText['tPdtCodeTo'] . ' ' . $this->aRptFilter['tRptPdtNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterBranchCodeFrom . ' ' . $tRptFilterBranchCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }

                // Fillter ประเภทสินค้า
                if (!empty($this->aRptFilter['tRptPdtTypeCodeFrom']) && !empty($this->aRptFilter['tRptPdtTypeCodeTo'])) {
                    $tRptFilterBranchCodeFrom = $this->aText['tPdtGrpFrom'] . ' ' . $this->aRptFilter['tRptPdtTypeNameFrom'];
                    $tRptFilterBranchCodeTo = $this->aText['tPdtGrpTo'] . ' ' . $this->aRptFilter['tRptPdtTypeNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterBranchCodeFrom . ' ' . $tRptFilterBranchCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }

                // Fillter DocDate (วันที่สร้างเอกสาร)
                if (!empty($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateTo'])) {
                    $tRptFilterDocDateFrom = $this->aText['tRptDateFrom'] . ' ' . $this->aRptFilter['tDocDateFrom'];
                    $tRptFilterDocDateTo = $this->aText['tRptDateTo'] . ' ' . $this->aRptFilter['tDocDateTo'];
                    $tRptTextLeftRightFilter = $tRptFilterDocDateFrom . ' ' . $tRptFilterDocDateTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }

                // ====================================================================================================================================================================
                // ========================================================================== Date Time Print =========================================================================
                $nStartRowFillter = 10;
                $tRptDateTimeExportText = $this->aText['tDatePrint'] . ' ' . $dDateExport . ' ' . $this->aText['tTimePrint'] . ' ' . $tTime;
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowFillter . ':I' . $nStartRowFillter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowFillter, $tRptDateTimeExportText);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                // ====================================================================================================================================================================
                // ==================================================================== หัวตารางรายงาน ==================================================================================
                // กำหนดจุดเริ่มต้นของแถวหัวตาราง 
                $nStartRowHeadder = 11;

                // กำหนด Style Font ของหัวตาราง
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':I' . $nStartRowHeadder)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':I' . $nStartRowHeadder)->applyFromArray($aStyleRptHeadderTable);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':I' . $nStartRowHeadder)->applyFromArray(array(
                    'borders' => array(
                        'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                        'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))
                    )
                ));

                // กำหนดข้อมูลลงหัวตาราง
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $nStartRowHeadder, $this->aText['tRptPdtCode'])
                        ->setCellValue('B' . $nStartRowHeadder, $this->aText['tRptPdtName'])
                        ->setCellValue('C' . $nStartRowHeadder, $this->aText['tRptPdtGrp'])
                        ->setCellValue('D' . $nStartRowHeadder, $this->aText['tRptQtySale'])
                        ->setCellValue('E' . $nStartRowHeadder, $this->aText['tRptCabinetCost'])
                        ->setCellValue('F' . $nStartRowHeadder, $this->aText['tRptGrandSale'])
                        ->setCellValue('G' . $nStartRowHeadder, $this->aText['tRptProfitloss'])
                        ->setCellValue('H' . $nStartRowHeadder, $this->aText['tRptCost'])
                        ->setCellValue('I' . $nStartRowHeadder, $this->aText['tRptSalesVending']);

                // Alignment ของหัวตาราง
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':I' . $nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                // ====================================================================================================================================================================
                // ==================================================================== ข้อมูลรายละเอียดรายงาน ===========================================================================
                // Set Variable Data
                $nStartRowData = $nStartRowHeadder + 1;
                if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])){
                    foreach($aDataReport['aRptData'] as $nKey => $aValue) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $nStartRowData, $aValue['FTPdtCode'])
                                ->setCellValue('B' . $nStartRowData, $aValue['FTPdtName'])
                                ->setCellValue('C' . $nStartRowData, $aValue['FTChainName'])
                                ->setCellValue('D' . $nStartRowData, $aValue['FCXsdSaleQty'])
                                ->setCellValue('E' . $nStartRowData, $aValue['FCPdtCost'])
                                ->setCellValue('F' . $nStartRowData, $aValue['FCXshGrand'])
                                ->setCellValue('G' . $nStartRowData, $aValue['FCXsdProfit'])
                                ->setCellValue('H' . $nStartRowData, $aValue['FCXsdProfitPercent'])
                                ->setCellValue('I' . $nStartRowData, $aValue['FCXsdSalePercent']);

                        $objPHPExcel->getActiveSheet()->getStyle('D' . $nStartRowData . ':I' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':C' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('D' . $nStartRowData . ':I' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $nStartRowData++;
                    }
                }else{
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':I' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tRptDataReportNotFound']);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
                }

                // Step 7 : Set Footer Text
                $nPageNo    = $aDataReport['aPagination']['nDisplayPage'];
                $nTotalPage = $aDataReport['aPagination']['nTotalPage'];

                if ($nPageNo == $nTotalPage) {
                    // Set Color Row Sub Footer
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':I' . $nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':I' . $nStartRowData)->applyFromArray(array(
                        'borders' => array(
                            'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                            'bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE, 'color' => array('rgb' => '000000'))
                        )
                    ));

                    // LEFT 
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':C' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tRptTotalSub']);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);

                    // RIGHT
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('D' . $nStartRowData, number_format($aDataSumFoot['FCXsdSaleQtySum'], $this->nOptDecimalShow))
                            ->setCellValue('E' . $nStartRowData, number_format($aDataSumFoot['FCPdtCostSum'], $this->nOptDecimalShow))
                            ->setCellValue('F' . $nStartRowData, number_format($aDataSumFoot['FCXshGrandSum'], $this->nOptDecimalShow))
                            ->setCellValue('G' . $nStartRowData, number_format($aDataSumFoot['FCXsdProfitSum'], $this->nOptDecimalShow))
                            ->setCellValue('H' . $nStartRowData, number_format($aDataSumFoot['FCXsdProfitPercentSum'], $this->nOptDecimalShow))
                            ->setCellValue('I' . $nStartRowData, number_format($aDataSumFoot['FCXsdSalePercentSum'], $this->nOptDecimalShow));
                    $objPHPExcel->getActiveSheet()->getStyle('D' . $nStartRowData . ':G' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':C' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('D' . $nStartRowData . ':I' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    
                    // Export File Excel
                    $tFilename ="$tFileName.xlsx";

                    /*
                    header("Pragma: public");
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    header("Content-Type: application/force-download");
                    header("Content-Type: application/octet-stream");
                    header("Content-Type: application/download");;
                    header("Content-Disposition: attachment;filename=$tFilename");
                    header("Content-Transfer-Encoding: binary");
                    */

                    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

                    if(!is_dir(EXPORTPATH.'exportexcel/')){
                        mkdir(EXPORTPATH.'exportexcel/');
                    }

                    if(!is_dir(EXPORTPATH.'exportexcel/'.$this->tRptCode)){
                        mkdir(EXPORTPATH.'exportexcel/'.$this->tRptCode);
                    }

                    if(!is_dir(EXPORTPATH.'exportexcel/'.$this->tRptCode.'/'.$this->tUserLoginCode)){
                        mkdir(EXPORTPATH.'exportexcel/'.$this->tRptCode.'/'.$this->tUserLoginCode);
                    }

                    $tPathExport = EXPORTPATH.'exportexcel/'.$this->tRptCode.'/'.$this->tUserLoginCode.'/';

                    /*$oFiles = glob($tPathExport.'*');
                    foreach($oFiles as $tFile){
                        if(is_file($tFile))
                        unlink($tFile);
                    }*/

                    $objWriter->save($tPathExport.$tFilename);
                    
                    $aResponse =  array(
                        'nStaExport'    => 1,
                        'tFileName'     => $tFilename,
                        'tPathFolder'   => EXPORTPATH.'exportexcel/'.$this->tRptCode.'/'.$this->tUserLoginCode.'/',
                        'tMessage'      => "Export File Successfully."
                    );

                }
            }else{
                $aResponse =  array(
                    'nStaExport' => '800',
                    'tMessage' => $this->aText['tRptDataReportNotFound']
                );
            }
        }catch(Exception $Error){
            $aResponse =  array(
                'nStaExport' => 500,
                'tMessage' => $Error->getMessage()
            );
        }
        return $aResponse;
    }
    
}





























































