<?php

defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set("Asia/Bangkok");

include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

class cRptInventoryPos extends MX_Controller {

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
        $this->load->model('company/company/mCompany');
        $this->load->model('report/reportsale/mRptInventoryPos');
        $this->load->model('report/report/mReport');

        // Init Report
        $this->init($paParams);

        parent::__construct();
    }

    private function init($paParams = []) {
        $this->aText = [
            'tTitleReport' => language('report/report/report', 'tRptTitleInventoryPos'),
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
            'tRptFillterWareHouseFrom' => language('report/report/report', 'tRptFromWareHouse'),
            'tRptFillterWareHouseTo' => language('report/report/report', 'tRptToWareHouse'),
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
            'tRptDataReportNotFound' => language('report/report/report', 'tRptDataReportNotFound'),
            'tRptWah' => language('report/report/report', 'tRptWahName'),
            'tRptPdtCode' => language('report/report/report', 'tRptPdtCode'),
            'tRptPdtName' => language('report/report/report', 'tRptPdtName'),
            'tRptPdtInven' => language('report/report/report', 'tRptPdtInven'),
            'tRptTaxSaleLockerTotalSub' => language('report/report/report', 'tRptTaxSaleLockerTotalSub'),
            'tRptRcvFrom' => language('report/report/report', 'tRptRcvFrom'),
            'tRptRcvTo' => language('report/report/report', 'tRptRcvTo'),
            'tRptTaxSaleLockerFilterDocDateFrom' => language('report/report/report', 'tRptTaxSaleLockerFilterDocDateFrom'),
            'tRptTaxSaleLockerFilterDocDateTo' => language('report/report/report', 'tRptTaxSaleLockerFilterDocDateTo'),
            // No Data Report
            'tRptNoData' => language('common/main/main', 'tCMNNotFoundData'),
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

        // Report Filter
        $this->aRptFilter = [
            // สาขา(Branch)
            'tBchCodeFrom' => (isset($paParams['aFilter']['tBchCodeFrom']) && !empty($paParams['aFilter']['tBchCodeFrom'])) ? $paParams['aFilter']['tBchCodeFrom'] : "",
            'tBchNameFrom' => (isset($paParams['aFilter']['tBchNameFrom']) && !empty($paParams['aFilter']['tBchNameFrom'])) ? $paParams['aFilter']['tBchNameFrom'] : "",
            'tBchCodeTo' => (isset($paParams['aFilter']['tBchCodeTo']) && !empty($paParams['aFilter']['tBchCodeTo'])) ? $paParams['aFilter']['tBchCodeTo'] : "",
            'tBchNameTo' => (isset($paParams['aFilter']['tBchNameTo']) && !empty($paParams['aFilter']['tBchNameTo'])) ? $paParams['aFilter']['tBchNameTo'] : "",
            // คลังสินค้า(Warehouse)
            'tWahCodeFrom' => (isset($paParams['aFilter']['tWahCodeFrom']) && !empty($paParams['aFilter']['tWahCodeFrom'])) ? $paParams['aFilter']['tWahCodeFrom'] : "",
            'tWahCodeTo' => (isset($paParams['aFilter']['tWahCodeTo']) && !empty($paParams['aFilter']['tWahCodeTo'])) ? $paParams['aFilter']['tWahCodeTo'] : "",
            'tWahNameFrom' => (isset($paParams['aFilter']['tWahNameFrom']) && !empty($paParams['aFilter']['tWahNameFrom'])) ? $paParams['aFilter']['tWahNameFrom'] : "",
            'tWahNameTo' => (isset($paParams['aFilter']['tWahNameTo']) && !empty($paParams['aFilter']['tWahNameTo'])) ? $paParams['aFilter']['tWahNameTo'] : "",
        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID' => $this->nLngID,
            'tBchCode' => $this->tBchCodeLogin
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
    public function FSvCCallRptRenderExcel($paParams = []) {

        /** =========== Begin Init Variable ================================== */
        $tDateExport = date('Y-m-d');
        $tTime = date('H:i:s');
        $nFile = $paParams['nFile'];
        $bIsLastFile = $paParams['bIsLastFile'];
        $tFileName = $paParams['tFileName'];
        /** =========== End Init Variable ==================================== */
        /** =========== Begin Get Data ======================================= */
        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aGetDataReportParams = array(
            'nPerPage' => $this->nPerPage,
            'nPage' => $nFile,
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'tUserSessionID' => $this->tUserSessionID
        );
        $aDataReport = $this->mRptInventoryPos->FSaMGetDataReport($aGetDataReportParams);
        /** =========== End Get Data ========================================= */
        try {

            if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {

                /* ===== ตั้งค่ารายงาน และ หัวข้อรายงาน ================================== */
                // ตั้งค่า Font Style
                $aReportFontStyle = array('font' => array('name' => 'TH Sarabun New'));
                $aStyleRptSizeTitleName = array('font' => array('size' => 14));
                $aStyleRptSizeCompName = array('font' => array('size' => 12));
                $aStyleRptSizeAddressFont = array('font' => array('size' => 12));
                $aStyleRptHeadderTable = array('font' => array('size' => 12, 'color' => array('rgb' => '000000')));
                $aStyleRptDataTable = array('font' => array('size' => 10, 'color' => array('rgb' => '000000')));
                $aStyleAddressFont = array('font' => array('size' => 11, 'bold' => true,));
                $aStyleCompFont = array('font' => array('size' => 12, 'bold' => true,));

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

                // Row เริ่มต้นของ Filter
                $nStartRowFillter = 7;
                
                $tFillterColumLEFT = "C";
                $tFillterColumRIGHT = "F";

                /*===== Begin Fillter =======================================================================*/
                // Fillter Branch (สาขา)
                if (!empty($this->aRptFilter['tBchCodeFrom']) && !empty($this->aRptFilter['tBchCodeTo'])) {
                    
                    $tRptFilterFrom = $this->aText['tRptBchFrom'].' '.$this->aRptFilter['tBchNameFrom'];
                    $tRptFilterTo = $this->aText['tRptBchTo'].' '.$this->aRptFilter['tBchNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterFrom.' '.$tRptFilterTo;
                    $tColumsMergeFilter = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;
                    
                }

                // Fillter Wah (คลัง)
                if (!empty($this->aRptFilter['tWahCodeFrom']) && !empty($this->aRptFilter['tWahCodeTo'])) {
                    
                    $tRptFilterFrom = $this->aText['tRptFillterWareHouseFrom'].' '.$this->aRptFilter['tWahNameFrom'];
                    $tRptFilterTo = $this->aText['tRptFillterWareHouseTo'].' '.$this->aRptFilter['tWahNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterFrom.' '.$tRptFilterTo;
                    $tColumsMergeFilter = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;
                    
                }
                /*===== End Fillter ===========================================================================*/

                /* ===== ตั้งค่าวันที่เวลาออกรายงาน ======================================= */
                $nStartRowDateExport = $nStartRowFillter + 1;

                $tRptDateTimeExportText = $this->aText['tRptDatePrint'] . ' ' . $tDateExport . ' ' . $this->aText['tRptTimePrint'] . ' ' . $tTime;
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowDateExport.':F'.$nStartRowDateExport);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nStartRowDateExport, $tRptDateTimeExportText);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowDateExport)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowDateExport)->applyFromArray($aStyleRptSizeAddressFont);

                /* ===== หัวตารางรายงาน ============================================== */
                // เริ่มตำแหน่ง หัวรายงาน
                $nStartRowHeadder = $nStartRowDateExport + 1;

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
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowHeadder, $this->aText['tRptWah']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $nStartRowHeadder, $this->aText['tRptPdtCode']);
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D' . $nStartRowHeadder . ':E' . $nStartRowHeadder);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $nStartRowHeadder, $this->aText['tRptPdtName']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $nStartRowHeadder, $this->aText['tRptPdtInven']);

                /* ===== วนลูปข้อมูลตารางข้อมูล ========================================= */
                // Set Variable Data
                $nStartRowData = $nStartRowHeadder + 1;
                // ******* Step 1 ประกาศตัวแปรใช้ในการเช็คเงื่อนไข
                $tGrouppingData = "";
                $nGroupMember = "";
                $nRowPartID = "";

                $nSumFooterFCXrcNet = 0;

                foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                    // ******* Step 3 Set ค่า Value FNRptGroupMember And FNRowPartID
                    $nRowPartID = $aValue["FNRowPartID"];
                    $nGroupMember = $aValue["FNRptGroupMember"];

                    // ******* Step 4 : Check Groupping Create Row Groupping 
                    if ($tGrouppingData != $aValue['FTWahCode']) {
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':F' . $nStartRowData);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $aValue['FTWahName']);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':F' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
                        $nStartRowData++;
                    }

                    // ******* Step 5 : Loop Set Data Value 
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':B' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $nStartRowData, $aValue['FTPdtCode']);
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D' . $nStartRowData . ':E' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $nStartRowData, $aValue['FTPdtName']);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $nStartRowData, number_format($aValue['FCStkQty'], $this->nOptDecimalShow));

                    $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':E' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    // ******* Step 6 : เตรียม Parameter สำหรับ Summary Sub Footer
                    if ($nRowPartID == $nGroupMember) {
                        $nStartRowData++;
                        $nSubSumFCXrcNet = $aValue["FCStkQty_SubTotal"];
                        // Set Color Row Sub Footer
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':F' . $nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':F' . $nStartRowData)->applyFromArray(array(
                            'borders' => array(
                                'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))
                            )
                        ));

                        // Text Sum Sub
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':E' . $nStartRowData);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tRptTaxSaleLockerTotalSub']);
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

                    $nSumFooterFCXrcNet = $aValue["FCStkQty_Footer"];

                    // ******* Step 2 Set ค่า Value ให้กับตัวแปร
                    $tGrouppingData = $aValue["FTWahCode"];
                    $nStartRowData++;
                }

                // Step 7 : Set Footer Text
                if ($bIsLastFile) { // Summary Last Page
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
                    $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    // Value Sum Footer
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $nStartRowData, number_format($nSumFooterFCXrcNet, $this->nOptDecimalShow));

                    $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                }

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

                // Check Status Save File Excel
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
                    'tMessage' => $this->aText['tRptDataReportNotFound']
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


























