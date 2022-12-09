<?php

defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set("Asia/Bangkok");

include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

class cRptSalePaymentSummary extends MX_Controller {

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
        $this->load->model('report/reportvending/mRptSalePaymentSummary');
        // Init Report
        $this->init($paParams);
    }

    private function init($paParams = []) {
        $this->aText = [
            'tTitleReport' => language('report/report/report', 'tRptTitleRptSalePaymentSummary'),
            'tTaxNo' => language('report/report/report', 'tRptTaxNo'),
            'tDatePrint' => language('report/report/report', 'tRptDatePrint'),
            'tDateExport' => language('report/report/report', 'tRptDateExport'),
            'tTimePrint' => language('report/report/report', 'tRptTimePrint'),
            'tPrintHtml' => language('report/report/report', 'tRptPrintHtml'),
            'tBranch' => language('report/report/report', 'tRptBranch'),
            'tFaxNo' => language('report/report/report', 'tRptFaxNo'),
            'tTel' => language('report/report/report', 'tRptTel'),
            // Table Report
            'tPayby' => language('report/report/report', 'tRptPayby'),
            'tTotalSale' => language('report/report/report', 'tRptTotalSale'),
            'tTotalSale' => language('report/report/report', 'tRptTotalSale'),
            'tTotalAllSale' => language('report/report/report', 'tRptTotalAllSale'),
            'tOverall' => language('report/report/report', 'tRptOverall'),
            // Filter Heard Report
            'tBchFrom' => language('report/report/report', 'tRptBchFrom'),
            'tBchTo' => language('report/report/report', 'tRptBchTo'),
            'tMerFrom' => language('report/report/report', 'tRptMerFrom'),
            'tMerTo' => language('report/report/report', 'tRptMerTo'),
            'tPosFrom' => language('report/report/report', 'tRptPosFrom'),
            'tPosTo' => language('report/report/report', 'tRptPosTo'),
            'tShopFrom' => language('report/report/report', 'tRptShopFrom'),
            'tShopTo' => language('report/report/report', 'tRptShopTo'),
            'tDateFrom' => language('report/report/report', 'tRptDateFrom'),
            'tDateTo' => language('report/report/report', 'tRptDateTo'),
            'tRcvFrom' => language('report/report/report', 'tRptRcvFrom'),
            'tRcvTo' => language('report/report/report', 'tRptRcvTo'),
            // Address Lang
            'tAddrBuilding' => language('report/report/report', 'tRptAddrBuilding'),
            'tAddrRoad' => language('report/report/report', 'tRptAddrRoad'),
            'tAddrSoi' => language('report/report/report', 'tRptAddrSoi'),
            'tAddrSubDistrict' => language('report/report/report', 'tRptAddrSubDistrict'),
            'tAddrDistrict' => language('report/report/report', 'tRptAddrDistrict'),
            'tAddrProvince' => language('report/report/report', 'tRptAddrProvince'),
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
            'tBchCodeTo' => (isset($paParams['aFilter']['tBchCodeTo']) && !empty($paParams['aFilter']['tBchCodeTo'])) ? $paParams['aFilter']['tBchCodeTo'] : "",
            'tBchNameFrom' => (isset($paParams['aFilter']['tBchNameFrom']) && !empty($paParams['aFilter']['tBchNameFrom'])) ? $paParams['aFilter']['tBchNameFrom'] : "",
            'tBchNameTo' => (isset($paParams['aFilter']['tBchNameTo']) && !empty($paParams['aFilter']['tBchNameTo'])) ? $paParams['aFilter']['tBchNameTo'] : "",
            // ร้านค้า
            'tShpCodeFrom' => (isset($paParams['aFilter']['tShpCodeFrom']) && !empty($paParams['aFilter']['tShpCodeFrom'])) ? $paParams['aFilter']['tShpCodeFrom'] : "",
            'tShpCodeTo' => (isset($paParams['aFilter']['tShpCodeTo']) && !empty($paParams['aFilter']['tShpCodeTo'])) ? $paParams['aFilter']['tShpCodeTo'] : "",
            'tShpNameFrom' => (isset($paParams['aFilter']['tShpNameFrom']) && !empty($paParams['aFilter']['tShpNameFrom'])) ? $paParams['aFilter']['tShpNameFrom'] : "",
            'tShpNameTo' => (isset($paParams['aFilter']['tShpNameTo']) && !empty($paParams['aFilter']['tShpNameTo'])) ? $paParams['aFilter']['tShpNameTo'] : "",
            // เครื่องจุดขาย
            'tPosCodeFrom' => (isset($paParams['aFilter']['tPosCodeFrom']) && !empty($paParams['aFilter']['tPosCodeFrom'])) ? $paParams['aFilter']['tPosCodeFrom'] : "",
            'tPosCodeTo' => (isset($paParams['aFilter']['tPosCodeTo']) && !empty($paParams['aFilter']['tPosCodeTo'])) ? $paParams['aFilter']['tPosCodeTo'] : "",
            'tPosNameFrom' => (isset($paParams['aFilter']['tPosNameFrom']) && !empty($paParams['aFilter']['tPosNameFrom'])) ? $paParams['aFilter']['tPosNameFrom'] : "",
            'tPosNameTo' => (isset($paParams['aFilter']['tPosNameTo']) && !empty($paParams['aFilter']['tPosNameTo'])) ? $paParams['aFilter']['tPosNameTo'] : "",
            // รูปแบบการชำระ
            'tRcvCodeFrom' => (isset($paParams['aFilter']['tRcvCodeFrom']) && !empty($paParams['aFilter']['tRcvCodeFrom'])) ? $paParams['aFilter']['tRcvCodeFrom'] : "",
            'tRcvCodeTo' => (isset($paParams['aFilter']['tRcvCodeTo']) && !empty($paParams['aFilter']['tRcvCodeTo'])) ? $paParams['aFilter']['tRcvCodeTo'] : "",
            'tRcvNameFrom' => (isset($paParams['aFilter']['tRcvNameFrom']) && !empty($paParams['aFilter']['tRcvNameFrom'])) ? $paParams['aFilter']['tRcvNameFrom'] : "",
            'tRcvNameTo' => (isset($paParams['aFilter']['tRcvNameTo']) && !empty($paParams['aFilter']['tRcvNameTo'])) ? $paParams['aFilter']['tRcvNameTo'] : "",
            // วันที่เอกสาร
            'tDateFrom' => (isset($paParams['aFilter']['tDateFrom']) && !empty($paParams['aFilter']['tDateFrom'])) ? $paParams['aFilter']['tDateFrom'] : "",
            'tDateTo' => (isset($paParams['aFilter']['tDateTo']) && !empty($paParams['aFilter']['tDateTo'])) ? $paParams['aFilter']['tDateTo'] : ""
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
            $tDateExport = date('Y-m-d');
            $tTimeExport = date('H:i:s');
            $nFile = $paParams['nFile'];
            $bIsLastFile = $paParams['bIsLastFile'];
            $tFileName = $paParams['tFileName'];
            /** =========== End Init Variable ================================ */
            /** =========== Begin Get Data =================================== */
            // ดึงข้อมูลจากฐานข้อมูล Temp
            $aGetDataReportParams = array(
                'nRow' => $this->nPerPage,
                'nPage' => $nFile,
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tUserSessionID' => $this->tUserSessionID
            );

            // Get Data ReportFSaMGetDataReport
            $aDataReport = $this->mRptSalePaymentSummary->FSaMGetDataReport($aGetDataReportParams, $this->aRptFilter);

            // GetDataSumFootReport
            $aDataSumFoot = $this->mRptSalePaymentSummary->FSaMGetDataSumFootReport($aGetDataReportParams, $this->aRptFilter);
            /** =========== End Get Data ===================================== */
            // ตั้งค่า Font Style
            if (isset($aDataReport['raItems']) && !empty($aDataReport['raItems']) &&
                    count($aDataSumFoot) != 0 && !empty($aDataSumFoot)) {
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
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);

                // ชื่อหัวรายงาน
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:B1');
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
                $nStartRowFillter = 8;
                
                $tFillterColumLEFT = "A";
                $tFillterColumRIGHT = "B";

                /*===== Begin Fillter ===========================================================================*/
                // Fillter Branch (สาขา)
                if (!empty($this->aRptFilter['tBchCodeFrom']) && !empty($this->aRptFilter['tBchCodeTo'])) {
                    
                    $tRptFilterBranchCodeFrom = $this->aText['tBchFrom'] . ' ' . $this->aRptFilter['tBchNameFrom'];
                    $tRptFilterBranchCodeTo = $this->aText['tBchTo'] . ' ' . $this->aRptFilter['tBchNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterBranchCodeFrom . ' ' . $tRptFilterBranchCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                   
                    $nStartRowFillter += 1;
                    
                }

                // Fillter Shop (ร้านค้า)
                if (!empty($this->aRptFilter['tShpCodeFrom']) && !empty($this->aRptFilter['tShpCodeTo'])) {
                    
                    $tRptFilterShopCodeFrom = $this->aText['tShopFrom'] . ' ' . $this->aRptFilter['tShpNameFrom'];
                    $tRptFilterShopCodeTo = $this->aText['tShopTo'] . ' ' . $this->aRptFilter['tShpNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterShopCodeFrom . ' ' . $tRptFilterShopCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter Pos (เครื่องจุดขาย)
                if (!empty($this->aRptFilter['tPosCodeFrom']) && !empty($this->aRptFilter['tPosCodeTo'])) {
                    
                    $tRptFilterPosCodeFrom = $this->aText['tPosFrom'] . ' ' . $this->aRptFilter['tPosNameFrom'];
                    $tRptFilterPosCodeTo = $this->aText['tPosTo'] . ' ' . $this->aRptFilter['tPosNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterPosCodeFrom . ' ' . $tRptFilterPosCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter Rcv (ประเภทการชำระเงิน)
                if (!empty($this->aRptFilter['tRcvCodeFrom']) && !empty($this->aRptFilter['tRcvCodeTo'])) {
                    
                    $tRptFilterRcvCodeFrom = $this->aText['tRcvFrom'] . ' ' . $this->aRptFilter['tRcvNameFrom'];
                    $tRptFilterRcvCodeTo = $this->aText['tRcvTo'] . ' ' . $this->aRptFilter['tRcvNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterRcvCodeFrom . ' ' . $tRptFilterRcvCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter Date (วันที่)
                if (!empty($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateTo'])) {
                    
                    $tRptFilterDocDateFrom = $this->aText['tFillterDateFrom'] . ' ' . $this->aRptFilter['tDocDateFrom'];
                    $tRptFilterDocDateTo = $this->aText['tFillterDateTo'] . ' ' . $this->aRptFilter['tDocDateTo'];
                    $tRptTextLeftRightFilter = $tRptFilterDocDateFrom . ' ' . $tRptFilterDocDateTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }
                /*===== End Filter Data Report ===============================*/
                
                // รายละเอียดการออกรายงาน
                $nStartRowDateExport = $nStartRowFillter + 1;

                // Date Time Print   
                $tRptDateTimeExportText = $this->aText['tDatePrint'] . ' ' . $tDateExport . ' ' . $this->aText['tTimePrint'] . ' ' . $tTimeExport;
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowDateExport.':B'.$nStartRowDateExport);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nStartRowDateExport, $tRptDateTimeExportText);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowDateExport)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowDateExport)->applyFromArray($aStyleRptSizeAddressFont);

                // เริ่มตำแหน่ง หัวรายงาน
                $nStartRowHeadder = $nStartRowDateExport + 1;
                
                /*===== หัวตารางรายงาน =========================================*/
                // กำหนด Style Font ของหัวตาราง
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':B' . $nStartRowHeadder)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':B' . $nStartRowHeadder)->applyFromArray($aStyleRptHeadderTable);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':B' . $nStartRowHeadder)->applyFromArray(array(
                    'borders' => array(
                        'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                        'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))
                    )
                ));

                // กำหนดข้อมูลลงหัวตาราง
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $nStartRowHeadder, $this->aText['tPayby'])
                    ->setCellValue('B' . $nStartRowHeadder, $this->aText['tTotalSale']);

                // Alignment ของหัวตาราง
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':A' . $nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle('B' . $nStartRowHeadder . ':B' . $nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                /*===== ข้อมูลรายละเอียดรายงาน ====================================*/
                // Set Variable Data
                $nStartRowData = $nStartRowHeadder + 1;
                if ($aDataReport['rtCode'] == 1) {
                    foreach ($aDataReport['raItems'] as $nKey => $aValue) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $nStartRowData, $aValue['rtRcvName'])
                            ->setCellValue('B' . $nStartRowData, $aValue['rtXrcNet']);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $nStartRowData++;
                    }
                } else {
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':B' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tPaymentNodata']);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
                }

                // Step 7 : Set Footer Text
                if ($bIsLastFile) {
                    // Set Color Row Sub Footer
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':B' . $nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':B' . $nStartRowData)->applyFromArray(array(
                        'borders' => array(
                            'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                            'bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE, 'color' => array('rgb' => '000000'))
                        )
                    ));

                    // LEFT 
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':A' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tTotalAllSale']);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);

                    // RIGHT
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $nStartRowData, number_format($aDataSumFoot['FCXrcNet_SumFooter'], $this->nOptDecimalShow));
                    $objPHPExcel->getActiveSheet()->getStyle('B' . $nStartRowData . ':B' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
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
        } catch (Exception $err) {
            $aResponse = array(
                'nStaExport' => 500,
                'tMessage' => $err->getMessage()
            );
        }
        return $aResponse;
    }

}




















