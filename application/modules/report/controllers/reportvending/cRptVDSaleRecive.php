<?php

defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set("Asia/Bangkok");

include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

class cRptVDSaleRecive extends MX_Controller {

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
        $this->load->model('report/reportvending/mRptSaleRecive');
        $this->load->model('report/report/mReport');
        // Init Report
        $this->init($paParams);
    }

    private function init($paParams = []) {
        $this->aText = [
            'tTitleReport' => language('report/report/report', 'tRptTitleSaleRecive'),
            'tTaxNo' => language('report/report/report', 'tRptTaxNo'),
            'tDatePrint' => language('report/report/report', 'tRptDatePrint'),
            'tDateExport' => language('report/report/report', 'tRptDateExport'),
            'tTimePrint' => language('report/report/report', 'tRptTimePrint'),
            'tPrintHtml' => language('report/report/report', 'tRptPrintHtml'),
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
            'tRptAddV2Desc2' => language('report/report/report','tRptAddV2Desc2'),
            // Filter Heard Report
            'tBchFrom' => language('report/report/report', 'tRptBchFrom'),
            'tBchTo' => language('report/report/report', 'tRptBchTo'),
            'tShopFrom' => language('report/report/report', 'tRptShopFrom'),
            'tShopTo' => language('report/report/report', 'tRptShopTo'),
            'tDateFrom' => language('report/report/report', 'tRptDateFrom'),
            'tDateTo' => language('report/report/report', 'tRptDateTo'),
            'tFillterRcvFrom' => language('report/report/report', 'tRptPaymentTypeFrom'),
            'tFillterRcvTo' => language('report/report/report', 'tRptPaymentTypeTo'),
            // Table Report
            'tBarchCode' => language('report/report/report', 'tRptBarchCode'),
            'tBarchName' => language('report/report/report', 'tRptBarchName'),
            'tDocDate' => language('report/report/report', 'tRptDocDate'),
            'tShopCode' => language('report/report/report', 'tRptShopCode'),
            'tShopName' => language('report/report/report', 'tRptShopName'),
            'tAmount' => language('report/report/report', 'tRptAmount'),
            'tSale' => language('report/report/report', 'tRptSale'),
            'tCancelSale' => language('report/report/report', 'tRptCancelSale'),
            'tTotalSale' => language('report/report/report', 'tRptTotalSale'),
            'tTotalAllSale' => language('report/report/report', 'tRptTotalAllSale'),
            'tPayby' => language('report/report/report', 'tRptPayby'),
            'tRcvDocumentCode' => language('report/report/report', 'tRptRcvDocumentCode'),
            'tDate' => language('report/report/report', 'tRptDate'),
            'tRcvTotal' => language('report/report/report', 'tRptRcvTotal'),
            'tTaxSaleLockerTotalSub' => language('report/report/report', 'tRptTaxSaleLockerTotalSub'),
            'tRcvFrom' => language('report/report/report', 'tRptRcvFrom'),
            'tRcvTo' => language('report/report/report', 'tRptRcvTo'),
            'tTaxSaleLockerFilterDocDateFrom' => language('report/report/report', 'tRptTaxSaleLockerFilterDocDateFrom'),
            'tTaxSaleLockerFilterDocDateTo' => language('report/report/report', 'tRptTaxSaleLockerFilterDocDateTo'),
            // No Data Report
            'tNoData' => language('common/main/main', 'tCMNNotFoundData'),
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
            // ประเภทการชำระ
            'tRcvCodeFrom' => (isset($paParams['aFilter']['tRcvCodeFrom']) && !empty($paParams['aFilter']['tRcvCodeFrom'])) ? $paParams['aFilter']['tRcvCodeFrom'] : "",
            'tRcvNameFrom' => (isset($paParams['aFilter']['tRcvNameFrom']) && !empty($paParams['aFilter']['tRcvNameFrom'])) ? $paParams['aFilter']['tRcvNameFrom'] : "",
            'tRcvCodeTo' => (isset($paParams['aFilter']['tRcvCodeTo']) && !empty($paParams['aFilter']['tRcvCodeTo'])) ? $paParams['aFilter']['tRcvCodeTo'] : "",
            'tRcvNameTo' => (isset($paParams['aFilter']['tRcvNameTo']) && !empty($paParams['aFilter']['tRcvNameTo'])) ? $paParams['aFilter']['tRcvNameTo'] : "",
            // สาขา
            'tBchCodeFrom' => (isset($paParams['aFilter']['tBchCodeFrom']) && !empty($paParams['aFilter']['tBchCodeFrom'])) ? $paParams['aFilter']['tBchCodeFrom'] : "",
            'tBchNameFrom' => (isset($paParams['aFilter']['tBchNameFrom']) && !empty($paParams['aFilter']['tBchNameFrom'])) ? $paParams['aFilter']['tBchNameFrom'] : "",
            'tBchCodeTo' => (isset($paParams['aFilter']['tBchCodeTo']) && !empty($paParams['aFilter']['tBchCodeTo'])) ? $paParams['aFilter']['tBchCodeTo'] : "",
            'tBchNameTo' => (isset($paParams['aFilter']['tBchNameTo']) && !empty($paParams['aFilter']['tBchNameTo'])) ? $paParams['aFilter']['tBchNameTo'] : "",
            // ร้านค้า
            'tShopCodeFrom' => (isset($paParams['aFilter']['tShopCodeFrom']) && !empty($paParams['aFilter']['tShopCodeFrom'])) ? $paParams['aFilter']['tShopCodeFrom'] : "",
            'tShopNameFrom' => (isset($paParams['aFilter']['tShopNameFrom']) && !empty($paParams['aFilter']['tShopNameFrom'])) ? $paParams['aFilter']['tShopNameFrom'] : "",
            'tShopCodeTo' => (isset($paParams['aFilter']['tShopCodeTo']) && !empty($paParams['aFilter']['tShopCodeTo'])) ? $paParams['aFilter']['tShopCodeTo'] : "",
            'tShopNameTo' => (isset($paParams['aFilter']['tShopNameTo']) && !empty($paParams['aFilter']['tShopNameTo'])) ? $paParams['aFilter']['tShopNameTo'] : "",
            // วันที่เอกสาร
            'tDocDateFrom' => (isset($paParams['aFilter']['tDocDateFrom']) && !empty($paParams['aFilter']['tDocDateFrom'])) ? $paParams['aFilter']['tDocDateFrom'] : "",
            'tDocDateTo' => (isset($paParams['aFilter']['tDocDateTo']) && !empty($paParams['aFilter']['tDocDateTo'])) ? $paParams['aFilter']['tDocDateTo'] : ""
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
            /** =========== Begin Init Variable ========================== */
            $tReportName = $this->aText['tTitleReport'];
            $nFile = $paParams['nFile'];
            $bIsLastFile = $paParams['bIsLastFile'];
            $tFileName = $paParams['tFileName'];
            $tDatePrint = date('Y-m-d');
            $tTimePrint = date('H:i:s');
            /** =========== End Init Variable ================================ */
            
            /** =========== Begin Get Data =================================== */
            // ดึงข้อมูลจากฐานข้อมูล Temp
            $aGetDataReportParams = array(
                'nPerPage' => $this->nPerPage,
                'nPage' => $nFile,
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tUserSessionID' => $this->tUserSessionID
            );
            $aDataReport = $this->mRptSaleRecive->FSaMGetDataReport($aGetDataReportParams);
            /** =========== End Get Data ===================================== */
            
            if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
                /* ===== ตั้งค่ารายงาน และ หัวข้อรายงาน ============================== */
                // ตั้งค่า Font Style
                $aReportFontStyle = array('font' => array('name' => 'TH Sarabun New'));
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

                // Set Font Style Text All In Report
                $objPHPExcel->getActiveSheet()->getStyle('A1:Z1000')->applyFromArray($aReportFontStyle);

                // จัดความกว้างของคอลัมน์
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);


                // ชื่อหัวรายงาน
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:F1');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $this->aText['tTitleReport']);
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
                
                $tFillterColumLEFT = "D";
                $tFillterColumRIGHT = "F";
                
                /*===== Begin Fillter ===========================================================================*/
                // Fillter Rcv (ประเภทชำระเงิน)
                if (!empty($this->aRptFilter['tRcvCodeFrom']) && !empty($this->aRptFilter['tRcvCodeTo'])) {
                    
                    $tRptFilterRcvCodeFrom = $this->aText['tRcvFrom'] . ' ' . $this->aRptFilter['tRcvNameFrom'];
                    $tRptFilterRcvCodeTo = $this->aText['tRcvTo'] . ' ' . $this->aRptFilter['tRcvCodeTo'];
                    $tRptTextLeftRightFilter = $tRptFilterRcvCodeFrom . ' ' . $tRptFilterRcvCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter Branch (สาขา)
                if (!empty($this->aRptFilter['tBchCodeFrom']) && !empty($this->aRptFilter['tBchCodeTo'])) {
                    
                    $tRptFilterBchCodeFrom = $this->aText['tBchFrom'] . ' ' . $this->aRptFilter['tBchNameFrom'];
                    $tRptFilterBchCodeTo = $this->aText['tBchTo'] . ' ' . $this->aRptFilter['tBchNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterBchCodeFrom . ' ' . $tRptFilterBchCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter Shop (ร้านค้า)
                if (!empty($this->aRptFilter['tShopCodeFrom']) && !empty($this->aRptFilter['tShopCodeTo'])) {
                    
                    $tRptFilterShopCodeFrom = $this->aText['tShopFrom'] . ' ' . $this->aRptFilter['tShopNameFrom'];
                    $tRptFilterShopCodeTo = $this->aText['tShopTo'] . ' ' . $this->aRptFilter['tShopNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterShopCodeFrom . ' ' . $tRptFilterShopCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter DocDate (วันที่สร้างเอกสาร)
                if (!empty($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateTo'])) {
                    
                    $tRptFilterDocDateFrom = $this->aText['tTaxSaleLockerFilterDocDateFrom'] . ' ' . $this->aRptFilter['tDocDateFrom'];
                    $tRptFilterDocDateTo = $this->aText['tTaxSaleLockerFilterDocDateTo'] . ' ' . $this->aRptFilter['tDocDateTo'];
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
                
                // ตั้งค่าวันที่เวลาออกรายงาน ========================================*/
                $tRptDateTimeExportText = $this->aText['tDatePrint'] . ' ' . $tDatePrint . ' ' . $this->aText['tTimePrint'] . ' ' . $tTimePrint;
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowDateExport.':F'.$nStartRowDateExport);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nStartRowDateExport, $tRptDateTimeExportText);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowDateExport)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowDateExport)->applyFromArray($aStyleRptSizeAddressFont);

                // เริ่มตำแหน่ง หัวรายงาน
                $nStartRowHeadder = $nStartRowDateExport + 1;
                
                /* ===== หัวตารางรายงาน =========================================*/
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
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowHeadder, $this->aText['tPayby']);

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C' . $nStartRowHeadder . ':D' . $nStartRowHeadder);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $nStartRowHeadder, $this->aText['tRcvDocumentCode']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $nStartRowHeadder, $this->aText['tDate']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $nStartRowHeadder, $this->aText['tRcvTotal']);

                /*===== วนลูปข้อมูลตารางข้อมูล ====================================*/
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
                        $nGroupMember = $aValue["FNRptGroupMember"];

                        // ******* Step 4 : Check Groupping Create Row Groupping 
                        if ($tGrouppingData != $aValue['FTRcvCode']) {
                            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':F' . $nStartRowData);
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $aValue['FTRcvName']);
                            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':F' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
                            $nStartRowData++;
                        }

                        // ******* Step 5 : Loop Set Data Value 
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':B' . $nStartRowData);
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C' . $nStartRowData . ':D' . $nStartRowData);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $nStartRowData, $aValue['FTXshDocNo']);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $nStartRowData, $aValue['FDXrcRefDate']);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $nStartRowData, number_format($aValue['FCXrcNet'], $this->nOptDecimalShow));

                        $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('C' . $nStartRowData . ':D' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                        // ******* Step 6 : เตรียม Parameter สำหรับ Summary Sub Footer
                        if ($nRowPartID == $nGroupMember) {
                            $nStartRowData++;
                            $nSubSumFCXrcNet = $aValue["FCSdtSubQty"];
                            // Set Color Row Sub Footer
                            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':F' . $nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':F' . $nStartRowData)->applyFromArray(array(
                                'borders' => array(
                                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))
                                )
                            ));

                            // Text Sum Sub
                            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':E' . $nStartRowData);
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tTaxSaleLockerTotalSub'] . $aValue['FTRcvName']);
                            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
                            $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                            // Value Sum Sub
                            $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('F' . $nStartRowData, number_format($nSubSumFCXrcNet, $this->nOptDecimalShow));

                            $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                            // $objPHPExcel->setActiveSheetIndex(0)->mergeCells('H'.$nStartRowData.':I'.$nStartRowData);
                            $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        }

                        $nSumFooterFCXrcNet = number_format($aValue["FCXrcNetFooter"], 2);

                        // ******* Step 2 Set ค่า Value ให้กับตัวแปร
                        $tGrouppingData = $aValue["FTRcvCode"];
                        $nStartRowData++;
                    }

                    // Step 7 : Set Footer Text
                    if ($bIsLastFile) {
                        // Set Color Row Sub Footer
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':F' . $nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':F' . $nStartRowData)->applyFromArray(array(
                            'borders' => array(
                                'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                                'bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE, 'color' => array('rgb' => '000000'))
                            )
                        ));

                        // Text Sum Footer
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':E' . $nStartRowData);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, 'รวมทั้งสิ้น');
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);

                        // Value Sum Footer
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('F' . $nStartRowData, $nSumFooterFCXrcNet);

                        $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $objPHPExcel->getActiveSheet()->getStyle('F1')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    }
                } else {
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':F' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tNoData']);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
                }

                /*===== ตั่งค่า Content Type Export File Excel ===================*/
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


















