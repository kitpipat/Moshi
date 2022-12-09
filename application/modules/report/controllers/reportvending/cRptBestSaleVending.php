<?php

defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set("Asia/Bangkok");

include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

class cRptBestSaleVending extends MX_Controller {

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

    public function __construct($paParams = []) {
        parent::__construct();
        $this->load->model('company/company/mCompany');
        $this->load->model('report/report/mReport');
        $this->load->model('report/reportvending/mRptBestSaleVending');
        // Init Report
        $this->init($paParams);
    }

    private function init($paParams = []) {
        $this->aText = [
            'tDatePrint' => language('report/report/report', 'tRptBestSaleVDDatePrint'),
            'tTimePrint' => language('report/report/report', 'tRptBestSaleVDTimePrint'),
            'tTitleReport' => language('report/report/report', 'tRptTitleRptBestSaleVending'),
            'tRptTaxNo' => language('report/report/report', 'tRptTaxNo'),
            'tRptDatePrint' => language('report/report/report', 'tRptDatePrint'),
            'tRptDateExport' => language('report/report/report', 'tRptDateExport'),
            'tRptTimePrint' => language('report/report/report', 'tRptTimePrint'),
            'tRptPrintHtml' => language('report/report/report', 'tRptPrintHtml'),
            'tRptBranch' => language('report/report/report', 'tRptBranch'),
            'tRptFaxNo' => language('report/report/report', 'tRptFaxNo'),
            'tRptTel' => language('report/report/report', 'tRptTel'),
            // Filter Head Report
            'tRptMerFrom' => language('report/report/report', 'tRptMerFrom'),
            'tRptMerTo' => language('report/report/report', 'tRptMerTo'),
            'tRptBchFrom' => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo' => language('report/report/report', 'tRptBchTo'),
            'tRptShopFrom' => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo' => language('report/report/report', 'tRptShopTo'),
            'tRptPosFrom' => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo' => language('report/report/report', 'tRptPosTo'),
            'tRptDateFrom' => language('report/report/report', 'tRptDateFrom'),
            'tRptDateTo' => language('report/report/report', 'tRptDateTo'),
            'tPdtCodeFrom' => language('report/report/report', 'tPdtCodeFrom'),
            'tPdtCodeTo' => language('report/report/report', 'tPdtCodeTo'),
            'tPdtGrpFrom' => language('report/report/report', 'tPdtGrpFrom'),
            'tPdtGrpTo' => language('report/report/report', 'tPdtGrpTo'),
            'tPdtTypeFrom' => language('report/report/report', 'tPdtTypeFrom'),
            'tPdtTypeTo' => language('report/report/report', 'tPdtTypeTo'),
            'tRptFillterDateFrom' => language('report/report/report', 'tRptDateFrom'),
            'tRptFillterDateTo' => language('report/report/report', 'tRptDateTo'),
            'tPriority' => language('report/report/report', 'tPriority'),
            // Table Report
            'tRptPdtCode' => language('report/report/report', 'tRptPdtCode'),
            'tRptPdtName' => language('report/report/report', 'tRptPdtName'),
            'tRptPdtGrp' => language('report/report/report', 'tRptPdtGrp'),
            'tRptNumQty' => language('report/report/report', 'tRptNumQty'),
            'tRptSaleNet' => language('report/report/report', 'tRptSaleNet'),
            'tRptDisChg' => language('report/report/report', 'tRptDisChg'),
            'tRptGrandTotal' => language('report/report/report', 'tRptGrandTotal'),
            'tRptOverall' => language('report/report/report', 'tRptOverall'),
            'tRptBestSaleVending' => language('common/main/main', 'tCMNNotFoundData'),
            'tRptBestSaleVendingTotal' => language('report/report/report', 'tRptBestSaleVendingTotal'),
            // Address Lang
            'tRptAddrBuilding' => language('report/report/report', 'tRptAddrBuilding'),
            'tRptAddrRoad' => language('report/report/report', 'tRptAddrRoad'),
            'tRptAddrSoi' => language('report/report/report', 'tRptAddrSoi'),
            'tRptAddrSubDistrict' => language('report/report/report', 'tRptAddrSubDistrict'),
            'tRptAddrDistrict' => language('report/report/report', 'tRptAddrDistrict'),
            'tRptAddrProvince' => language('report/report/report', 'tRptAddrProvince'),
            'tRptAddrTel' => language('report/report/report','tRptAddrTel'),
            'tRptAddrFax' => language('report/report/report','tRptAddrFax'),
            'tRptAddrBranch' => language('report/report/report','tRptAddrBranch'),
            'tRptAddV2Desc1' => language('report/report/report','tRptAddV2Desc1'),
            'tRptAddV2Desc2' => language('report/report/report','tRptAddV2Desc2')
        ];

        $this->tBchCodeLogin = $paParams['tBchCodeLogin'];
        $this->nPerPage = $paParams['nPerFile'];
        $this->nOptDecimalShow = FCNxHGetOptionDecimalShow();
        $this->tCompName = $paParams['tCompName'];
        $this->nLngID = $paParams['nLngID'];
        $this->tRptCode = $paParams['tRptCode'];
        $this->tUserSessionID = $paParams['tUserSessionID'];
        $this->tRptRoute = $this->input->post('ohdRptRoute');
        $this->tRptExportType = $paParams['tRptExpType'];
        $this->nPage = empty($this->input->post('ohdRptCurrentPage')) ? 1 : $this->input->post('ohdRptCurrentPage');
        $this->tUserLoginCode = $paParams['tUserCode'];

        $this->aRptFilter = [
            // สาขา
            'tBchCodeFrom' => (isset($paParams['aFilter']['tBchCodeFrom']) && !empty($paParams['aFilter']['tBchCodeFrom'])) ? $paParams['aFilter']['tBchCodeFrom'] : "",
            'tBchNameFrom' => (isset($paParams['aFilter']['tBchNameFrom']) && !empty($paParams['aFilter']['tBchNameFrom'])) ? $paParams['aFilter']['tBchNameFrom'] : "",
            'tBchCodeTo' => (isset($paParams['aFilter']['tBchCodeTo']) && !empty($paParams['aFilter']['tBchCodeTo'])) ? $paParams['aFilter']['tBchCodeTo'] : "",
            'tBchNameTo' => (isset($paParams['aFilter']['tBchNameTo']) && !empty($paParams['aFilter']['tBchNameTo'])) ? $paParams['aFilter']['tBchNameTo'] : "",
            // กลุ่มธุรกิจ
            'tMerCodeFrom' => (isset($paParams['aFilter']['tMerCodeFrom']) && !empty($paParams['aFilter']['tMerCodeFrom'])) ? $paParams['aFilter']['tMerCodeFrom'] : "",
            'tMerNameFrom' => (isset($paParams['aFilter']['tMerNameFrom']) && !empty($paParams['aFilter']['tMerNameFrom'])) ? $paParams['aFilter']['tMerNameFrom'] : "",
            'tMerCodeTo' => (isset($paParams['aFilter']['tMerCodeTo']) && !empty($paParams['aFilter']['tMerCodeTo'])) ? $paParams['aFilter']['tMerCodeTo'] : "",
            'tMerNameTo' => (isset($paParams['aFilter']['tMerNameTo']) && !empty($paParams['aFilter']['tMerNameTo'])) ? $paParams['aFilter']['tMerNameTo'] : "",
            // ร้านค้า
            'tRptShpCodeFrom' => (isset($paParams['aFilter']['tRptShpCodeFrom']) && !empty($paParams['aFilter']['tRptShpCodeFrom'])) ? $paParams['aFilter']['tRptShpCodeFrom'] : "",
            'tShpNameFrom' => (isset($paParams['aFilter']['tShpNameFrom']) && !empty($paParams['aFilter']['tShpNameFrom'])) ? $paParams['aFilter']['tShpNameFrom'] : "",
            'tShpCodeTo' => (isset($paParams['aFilter']['tShpCodeTo']) && !empty($paParams['aFilter']['tShpCodeTo'])) ? $paParams['aFilter']['tShpCodeTo'] : "",
            'tShpNameTo' => (isset($paParams['aFilter']['tShpNameTo']) && !empty($paParams['aFilter']['tShpNameTo'])) ? $paParams['aFilter']['tShpNameTo'] : "",
            // เครื่องจุดขาย
            'tPosCodeFrom' => (isset($paParams['aFilter']['tPosCodeFrom']) && !empty($paParams['aFilter']['tPosCodeFrom'])) ? $paParams['aFilter']['tPosCodeFrom'] : "",
            'tPosNameFrom' => (isset($paParams['aFilter']['tPosNameFrom']) && !empty($paParams['aFilter']['tPosNameFrom'])) ? $paParams['aFilter']['tPosNameFrom'] : "",
            'tPosCodeTo' => (isset($paParams['aFilter']['tPosCodeTo']) && !empty($paParams['aFilter']['tPosCodeTo'])) ? $paParams['aFilter']['tPosCodeTo'] : "",
            'tPosNameTo' => (isset($paParams['aFilter']['tPosNameTo']) && !empty($paParams['aFilter']['tPosNameTo'])) ? $paParams['aFilter']['tPosNameTo'] : "",
            // สินค้า
            'tPdtCodeFrom' => (isset($paParams['aFilter']['tPdtCodeFrom']) && !empty($paParams['aFilter']['tPdtCodeFrom'])) ? $paParams['aFilter']['tPdtCodeFrom'] : "",
            'tPdtNameFrom' => (isset($paParams['aFilter']['tPdtNameFrom']) && !empty($paParams['aFilter']['tPdtNameFrom'])) ? $paParams['aFilter']['tPdtNameFrom'] : "",
            'tPdtCodeTo' => (isset($paParams['aFilter']['tPdtCodeTo']) && !empty($paParams['aFilter']['tPdtCodeTo'])) ? $paParams['aFilter']['tPdtCodeTo'] : "",
            'tPdtNameTo' => (isset($paParams['aFilter']['tPdtNameTo']) && !empty($paParams['aFilter']['tPdtNameTo'])) ? $paParams['aFilter']['tPdtNameTo'] : "",
            // กลุ่มสินค้า
            'tPdtGrpCodeFrom' => (isset($paParams['aFilter']['tPdtGrpCodeFrom']) && !empty($paParams['aFilter']['tPdtGrpCodeFrom'])) ? $paParams['aFilter']['tPdtGrpCodeFrom'] : "",
            'tPdtGrpNameFrom' => (isset($paParams['aFilter']['tPdtGrpNameFrom']) && !empty($paParams['aFilter']['tPdtGrpNameFrom'])) ? $paParams['aFilter']['tPdtGrpNameFrom'] : "",
            'tPdtGrpCodeTo' => (isset($paParams['aFilter']['tPdtGrpCodeTo']) && !empty($paParams['aFilter']['tPdtGrpCodeTo'])) ? $paParams['aFilter']['tPdtGrpCodeTo'] : "",
            'tPdtGrpNameTo' => (isset($paParams['aFilter']['tPdtGrpNameTo']) && !empty($paParams['aFilter']['tPdtGrpNameTo'])) ? $paParams['aFilter']['tPdtGrpNameTo'] : "",
            // ประเภทสินค้า
            'tPdtTypeCodeFrom' => (isset($paParams['aFilter']['tPdtTypeCodeFrom']) && !empty($paParams['aFilter']['tPdtTypeCodeFrom'])) ? $paParams['aFilter']['tPdtTypeCodeFrom'] : "",
            'tPdtTypeNameFrom' => (isset($paParams['aFilter']['tPdtTypeNameFrom']) && !empty($paParams['aFilter']['tPdtTypeNameFrom'])) ? $paParams['aFilter']['tPdtTypeNameFrom'] : "",
            'tPdtTypeCodeTo' => (isset($paParams['aFilter']['tPdtTypeCodeTo']) && !empty($paParams['aFilter']['tPdtTypeCodeTo'])) ? $paParams['aFilter']['tPdtTypeCodeTo'] : "",
            'tPdtTypeNameTo' => (isset($paParams['aFilter']['tPdtTypeNameTo']) && !empty($paParams['aFilter']['tPdtTypeNameTo'])) ? $paParams['aFilter']['tPdtTypeNameTo'] : "",
            // วันที่เอกสาร
            'tDocDateFrom' => (isset($paParams['aFilter']['tDocDateFrom']) && !empty($paParams['aFilter']['tDocDateFrom'])) ? $paParams['aFilter']['tDocDateFrom'] : "",
            'tDocDateTo' => (isset($paParams['aFilter']['tDocDateTo']) && !empty($paParams['aFilter']['tDocDateTo'])) ? $paParams['aFilter']['tDocDateTo'] : "",
            // ความสำคัญ
            'tPriority' => (isset($paParams['aFilter']['tPriority']) && !empty($paParams['aFilter']['tPriority'])) ? $paParams['aFilter']['tPriority'] : "",
        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID' => $this->nLngID,
            'tBchCode' => $this->tBchCodeLogin
        ];
        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    /**
     * Functionality: Export Excel Report
     * Parameters:  Function Parameter
     * Creator: 21/08/2019 Wasin(Yoshi)
     * LastUpdate: 16/09/2019 Piya
     * Return: View Report Viewer
     * ReturnType: View
    */
    public function FSvCCallRptRenderExcel($paParams = []) {
        try {
            /** =========== Begin Init Variable ============================== */
            $tReportName = $this->aText['tTitleReport'];
            $dDateExport = date('Y-m-d');
            $tTime = date('H:i:s');
            $nFile = $paParams['nFile'];
            $bIsLastFile = $paParams['bIsLastFile'];
            $tFileName = $paParams['tFileName'];
            /** =========== End Init Variable ================================ */
            
            /** =========== Begin Get Data =================================== */
            // ดึงข้อมูลจากฐานข้อมูล Temp
            $aGetDataReportParams = array(
                'nRow' => $this->nPerPage,
                'nPerPage' => $this->nPerPage,
                'nPage' => $nFile,
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tUserSessionID' => $this->tUserSessionID
            );

            // Get Data ReportFSaMGetDataReport
            $aDataReport = $this->mRptBestSaleVending->FSaMGetDataReport($aGetDataReportParams, $this->aRptFilter);

            // GetDataSumFootReport
            $aDataSumFoot = $this->mRptBestSaleVending->FSaMGetDataSumFootReport($aGetDataReportParams, $this->aRptFilter);
            /** =========== End Get Data ===================================== */
            
            if (isset($aDataReport['raItems']) && !empty($aDataReport['raItems']) && count($aDataSumFoot) != 0) {
                // ตั้งค่า Font Style
                $aStyleRptFont = array('font' => array('name' => 'TH Sarabun New'));
                $aStyleRptSizeTitleName = array('font' => array('size' => 14));
                $aStyleRptSizeCompName = array('font' => array('size' => 12));
                $aStyleRptSizeAddressFont = array('font' => array('size' => 12));
                $aStyleRptHeadderTable = array('font' => array('size' => 12, 'color' => array('rgb' => '000000')));
                $aStyleRptDataTable = array('font' => array('size' => 10, 'color' => array('rgb' => '000000')));

                $aStyleRptName = array('font' => array('size' => 14, 'bold' => true,));
                $aStyleHeadder = array('font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => 'FFFFFF')));
                $aStyleCompFont = array('font' => array('size' => 12, 'bold' => true,));
                $aStyleAddressFont = array('font' => array('size' => 11, 'bold' => true,));
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

                // Set Font Style
                $objPHPExcel->getActiveSheet()->getStyle('A1:Z1000')->applyFromArray($aStyleRptFont);

                // จัดความกว้างของคอลัมน์
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(45);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

                // ชื่อหัวรายงาน
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $tReportName);
                $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($aStyleRptSizeTitleName);

                if(isset($this->aCompanyInfo) && !empty($this->aCompanyInfo)){
                    // ชื่อ Conpany
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', $this->aCompanyInfo['FTCmpName'])->getStyle('A2')->applyFromArray($aStyleCompFont);

                    if($this->aCompanyInfo['FTAddVersion'] == '1'){ // ที่อยู่แบบแยก
                        // ที่อยู่
                        $tLabelAddressLine1 = $this->aCompanyInfo['FTAddV1No'] . ' ' . $this->aCompanyInfo['FTAddV1Village'] . ' ' . $this->aCompanyInfo['FTAddV1Road'] . ' ' . $this->aCompanyInfo['FTAddV1Soi'];
                        $tLabelAddressLine2 = $this->aCompanyInfo['FTSudName'] . ' ' . $this->aCompanyInfo['FTDstName'] . ' ' . $this->aCompanyInfo['FTPvnName'] . ' ' . $this->aCompanyInfo['FTAddV1PostCode'];
                        // ที่อยู่ บรรทัดที่ 1
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', $tLabelAddressLine1)->getStyle('A3')->applyFromArray($aStyleAddressFont);
                        // ที่อยู่ บรรทัดที่ 2
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', $tLabelAddressLine2)->getStyle('A4')->applyFromArray($aStyleAddressFont);
                    }
                    if($this->aCompanyInfo['FTAddVersion'] == '2'){ // ที่อยู่แบบรวม
                        // ที่อยู่
                        $tLabelAddressLine1 = $this->aCompanyInfo['FTAddV2Desc1'];
                        $tLabelAddressLine2 = $this->aCompanyInfo['FTAddV2Desc2'];
                        // ที่อยู่ บรรทัดที่ 1
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', $tLabelAddressLine1)->getStyle('A3')->applyFromArray($aStyleAddressFont);
                        // ที่อยู่ บรรทัดที่ 2
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', $tLabelAddressLine2)->getStyle('A4')->applyFromArray($aStyleAddressFont);
                    }

                    // เบอร์โทร, แฟกซ์
                    $tLabelTelFax = $this->aText['tRptAddrTel'] . $this->aCompanyInfo['FTCmpTel'] . ' ' . $this->aText['tRptAddrFax'] . $this->aCompanyInfo['FTCmpFax'];
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', $tLabelTelFax)->getStyle('A5')->applyFromArray($aStyleAddressFont);

                    // สาขา
                    $tLabelBch = $this->aText['tRptAddrBranch'] . $this->aCompanyInfo['FTBchName'];
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A6', $tLabelBch)->getStyle('A6')->applyFromArray($aStyleAddressFont);
                }

                // เริ่มต้น Filter
                $nStartRowFillter = 7;
                
                $tFillterColumLEFT = "C";
                $tFillterColumRIGHT = "G";
                
                /*===== Begin Fillter ===========================================================================*/
                // Fillter Branch (สาขา)
                if (!empty($this->aRptFilter['tBchCodeFrom']) && !empty($this->aRptFilter['tBchCodeTo'])) {
                    
                    $tRptFilterBranchCodeFrom = $this->aText['tRptBchFrom'] . ' ' . $this->aRptFilter['tBchNameFrom'];
                    $tRptFilterBranchCodeTo = $this->aText['tRptBchTo'] . ' ' . $this->aRptFilter['tBchNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterBranchCodeFrom . ' ' . $tRptFilterBranchCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter MerChant กลุ่มธุรกิจ
                if (!empty($this->aRptFilter['tMerCodeFrom']) && !empty($this->aRptFilter['tMerCodeTo'])) {
                    
                    $tRptFilterMerChantCodeFrom = $this->aText['tRptBchFrom'] . ' ' . $this->aRptFilter['tMerNameFrom'];
                    $tRptFilterMerChantCodeTo = $this->aText['tRptBchTo'] . ' ' . $this->aRptFilter['tMerNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterMerChantCodeFrom . ' ' . $tRptFilterMerChantCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter Shop (ร้านค้า)
                if (!empty($this->aRptFilter['tRptShpCodeFrom']) && !empty($this->aRptFilter['tShpCodeTo'])) {
                    
                    $tRptFilterShopCodeFrom = $this->aText['tRptShopFrom'] . ' ' . $this->aRptFilter['tShpNameFrom'];
                    $tRptFilterShopCodeTo = $this->aText['tRptShopTo'] . ' ' . $this->aRptFilter['tShpNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterShopCodeFrom . ' ' . $tRptFilterShopCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter POS (เครื่องจุดขาย)
                if (!empty($this->aRptFilter['tPosCodeFrom']) && !empty($this->aRptFilter['tPosCodeTo'])) {
                    
                    $tRptFilterPosCodeFrom = $this->aText['tRptPosFrom'] . ' ' . $this->aRptFilter['tPosNameFrom'];
                    $tRptFilterPosCodeTo = $this->aText['tRptPosTo'] . ' ' . $this->aRptFilter['tPosNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterPosCodeFrom . ' ' . $tRptFilterPosCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter Product (สินค้า)
                if (!empty($this->aRptFilter['tPdtCodeFrom']) && !empty($this->aRptFilter['tPdtCodeTo'])) {
                    
                    $tRptFilterPdtCodeFrom = $this->aText['tPdtCodeFrom'] . ' ' . $this->aRptFilter['tPdtNameFrom'];
                    $tRptFilterPdtCodeTo = $this->aText['tPdtCodeTo'] . ' ' . $this->aRptFilter['tPdtNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterPdtCodeFrom . ' ' . $tRptFilterPdtCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter Group Product (กลุ่มสินค้า)
                if (!empty($this->aRptFilter['tPdtGrpCodeFrom']) && !empty($this->aRptFilter['tPdtGrpCodeTo'])) {
                    
                    $tRptFilterPdtGrpCodeFrom = $this->aText['tPdtGrpFrom'] . ' ' . $this->aRptFilter['tPdtGrpNameFrom'];
                    $tRptFilterPdtGrpCodeTo = $this->aText['tPdtGrpTo'] . ' ' . $this->aRptFilter['tPdtGrpNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterPdtGrpCodeFrom . ' ' . $tRptFilterPdtGrpCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter Type Product (ประเภทสินค้า)
                if (!empty($this->aRptFilter['tPdtTypeCodeFrom']) && !empty($this->aRptFilter['tPdtTypeCodeTo'])) {
                    
                    $tRptFilterPdtTypeCodeFrom = $this->aText['tPdtTypeFrom'] . ' ' . $this->aRptFilter['tPdtTypeNameFrom'];
                    $tRptFilterPdtTypeCodeTo = $this->aText['tPdtTypeTo'] . ' ' . $this->aRptFilter['tPdtTypeNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterPdtTypeCodeFrom . ' ' . $tRptFilterPdtTypeCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter Date From 
                if (!empty($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateTo'])) {
                    
                    $tRptFilterDocDateFrom = $this->aText['tRptFillterDateFrom'] . ' ' . $this->aRptFilter['tDocDateFrom'];
                    $tRptFilterDocDateTo = $this->aText['tRptFillterDateTo'] . ' ' . $this->aRptFilter['tDocDateTo'];
                    $tRptTextLeftRightFilter = $tRptFilterDocDateFrom . ' ' . $tRptFilterDocDateTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter Top Select
                if (!empty($this->aRptFilter['tPriority'])) {
                    
                    $tRptTopSelect = $this->aText['tPriority'] . ': ' . $this->aRptFilter['tPriority'];
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTopSelect);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }
                /*===== End Filter Data Report ===============================*/
                
                // รายละเอียดการออกรายงาน
                $nStartRowDateExport = $nStartRowFillter + 1;
                
                // Date Time Print  
                $tRptDateTimeExportText = $this->aText['tDatePrint'] . ' ' . $dDateExport . ' ' . $this->aText['tTimePrint'] . ' ' . $tTime;
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowDateExport.':G'.$nStartRowDateExport);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nStartRowDateExport, $tRptDateTimeExportText);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowDateExport)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowDateExport)->applyFromArray($aStyleRptSizeAddressFont);
                
                // เริ่มตำแหน่ง หัวรายงาน
                $nStartRowHeadder = $nStartRowDateExport + 1;
                
                /*===== หัวตารางรายงาน =========================================*/
                // กำหนด Style Font ของหัวตาราง
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':G' . $nStartRowHeadder)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':G' . $nStartRowHeadder)->applyFromArray($aStyleRptHeadderTable);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':G' . $nStartRowHeadder)->applyFromArray(array(
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
                    ->setCellValue('D' . $nStartRowHeadder, $this->aText['tRptNumQty'])
                    ->setCellValue('E' . $nStartRowHeadder, $this->aText['tRptSaleNet'])
                    ->setCellValue('F' . $nStartRowHeadder, $this->aText['tRptDisChg'])
                    ->setCellValue('G' . $nStartRowHeadder, $this->aText['tRptGrandTotal']);

                // Alignment ของหัวตาราง
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':C' . $nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('D' . $nStartRowHeadder . ':G' . $nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                
                /*===== ข้อมูลรายละเอียดรายงาน ===================================*/
                // Set Variable Data
                $nStartRowData = $nStartRowHeadder + 1;

                if ($aDataReport['rtCode'] == 1) {
                    foreach ($aDataReport['raItems'] as $nKey => $aValue) {
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $nStartRowData, $aValue['rtPdtCode'])
                                ->setCellValue('B' . $nStartRowData, $aValue['rtPdtName'])
                                ->setCellValue('C' . $nStartRowData, $aValue['rtPdtChainName'])
                                ->setCellValue('D' . $nStartRowData, $aValue['rtQty'])
                                ->setCellValue('E' . $nStartRowData, $aValue['rtNet'])
                                ->setCellValue('F' . $nStartRowData, $aValue['rtDisChg'])
                                ->setCellValue('G' . $nStartRowData, $aValue['rtGrandTotal']);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':C' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('D' . $nStartRowData . ':G' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $nStartRowData++;
                    }
                } else {
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':G' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tRptBestSaleVending']);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
                }

                // Step 7 : Set Footer Text
                $nPageNo = $aDataReport['rnCurrentPage'];
                $nTotalPage = $aDataReport['rnAllPage'];

                if ($nPageNo == $nTotalPage) {
                    // Set Color Row Sub Footer
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':G' . $nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':G' . $nStartRowData)->applyFromArray(array(
                        'borders' => array(
                            'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                            'bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE, 'color' => array('rgb' => '000000'))
                        )
                    ));

                    // LEFT 
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':C' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tRptBestSaleVendingTotal']);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);

                    // RIGHT
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('D' . $nStartRowData, number_format($aDataSumFoot['FCXsdQty_SumFooter'], $this->nOptDecimalShow))
                        ->setCellValue('E' . $nStartRowData, number_format($aDataSumFoot['FCXsdNet_SumFooter'], $this->nOptDecimalShow))
                        ->setCellValue('F' . $nStartRowData, number_format($aDataSumFoot['FCXsdDisChg_SumFooter'], $this->nOptDecimalShow))
                        ->setCellValue('G' . $nStartRowData, number_format($aDataSumFoot['FCXsdGrandTotal_SumFooter'], $this->nOptDecimalShow));

                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':C' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('D' . $nStartRowData . ':G' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                }
                
                /*===== Set Content Type Export File Excel ===================*/
                // Export File Excel
                $tFilename = "$tFileName.xlsx";

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

                if (!is_dir(EXPORTPATH . 'exportexcel/')) {
                    mkdir(EXPORTPATH . 'exportexcel/');
                }

                if (!is_dir(EXPORTPATH . 'exportexcel/' . $this->tRptCode)) {
                    mkdir(EXPORTPATH . 'exportexcel/' . $this->tRptCode);
                }

                if (!is_dir(EXPORTPATH . 'exportexcel/' . $this->tRptCode . '/' . $this->tUserLoginCode)) {
                    mkdir(EXPORTPATH . 'exportexcel/' . $this->tRptCode . '/' . $this->tUserLoginCode);
                }

                $tPathExport = EXPORTPATH . 'exportexcel/' . $this->tRptCode . '/' . $this->tUserLoginCode . '/';

                /* $oFiles = glob($tPathExport.'*');
                  foreach($oFiles as $tFile){
                  if(is_file($tFile))
                  unlink($tFile);
                  } */

                $objWriter->save($tPathExport . $tFilename);

                $aResponse = array(
                    'nStaExport' => 1,
                    'tFileName' => $tFilename,
                    'tPathFolder' => EXPORTPATH . 'exportexcel/' . $this->tRptCode . '/' . $this->tUserLoginCode . '/',
                    'tMessage' => "Export File Successfully."
                );
            } else {
                $aResponse = array(
                    'nStaExport' => '800',
                    'tMessage' => "ไม่พบข้อมูล"
                );
            }
        } catch (Exception $Error) {
            $aResponse = array(
                'nStaExport' => 500,
                'tMessage' => $Error->getMessage()
            );
        }
        return $aResponse;
    }

}

































