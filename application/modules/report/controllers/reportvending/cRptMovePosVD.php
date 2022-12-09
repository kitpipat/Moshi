<?php

defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set("Asia/Bangkok");

include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

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
        $this->load->model('report/reportvending/mRptMovePosVd');
        $this->load->model('report/report/mReport');
        // Init Report
        $this->init($paParams);
    }

    private function init($paParams = []) {
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
            'tRptAddrTel' => language('report/report/report','tRptAddrTel'),
            'tRptAddrFax' => language('report/report/report','tRptAddrFax'),
            'tRptAddrBranch' => language('report/report/report','tRptAddrBranch'),
            'tRptAddV2Desc1' => language('report/report/report','tRptAddV2Desc1'),
            'tRptAddV2Desc2' => language('report/report/report','tRptAddV2Desc2'),
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
            'tRptAdjStkVDTotalSub' => language('report/report/report', 'tRptAdjStkVDTotalSub'),
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
            // สินค้า
            'tPdtCodeFrom' => (isset($paParams['aFilter']['tPdtCodeFrom']) && !empty($paParams['aFilter']['tPdtCodeFrom'])) ? $paParams['aFilter']['tPdtCodeFrom'] : "",
            'tPdtNameFrom' => (isset($paParams['aFilter']['tPdtNameFrom']) && !empty($paParams['aFilter']['tPdtNameFrom'])) ? $paParams['aFilter']['tPdtNameFrom'] : "",
            'tPdtCodeTo' => (isset($paParams['aFilter']['tPdtCodeTo']) && !empty($paParams['aFilter']['tPdtCodeTo'])) ? $paParams['aFilter']['tPdtCodeTo'] : "",
            'tPdtNameTo' => (isset($paParams['aFilter']['tPdtNameTo']) && !empty($paParams['aFilter']['tPdtNameTo'])) ? $paParams['aFilter']['tPdtNameTo'] : "",
            // คลังสินค้า
            'tWahCodeFrom' => (isset($paParams['aFilter']['tWahCodeFrom']) && !empty($paParams['aFilter']['tWahCodeFrom'])) ? $paParams['aFilter']['tWahCodeFrom'] : "",
            'tWahNameFrom' => (isset($paParams['aFilter']['tWahNameFrom']) && !empty($paParams['aFilter']['tWahNameFrom'])) ? $paParams['aFilter']['tWahNameFrom'] : "",
            'tWahCodeTo' => (isset($paParams['aFilter']['tWahCodeTo']) && !empty($paParams['aFilter']['tWahCodeTo'])) ? $paParams['aFilter']['tWahCodeTo'] : "",
            'tWahNameTo' => (isset($paParams['aFilter']['tWahNameTo']) && !empty($paParams['aFilter']['tWahNameTo'])) ? $paParams['aFilter']['tWahNameTo'] : "",
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
            /*===== เตรียมข้อมูลออกรายงาน ========================================*/
            /*=========== Begin Init Variable ================================*/
            $tReportName = $this->aText['tTitleReport'];
            $nFile = $paParams['nFile'];
            $bIsLastFile = $paParams['bIsLastFile'];
            $tFileName = $paParams['tFileName'];
            $dDatePrint = date('Y-m-d');
            $dTimePrint = date('H:i:s');
            /*=========== End Init Variable ==================================*/
            
            /*=========== Begin Get Data =====================================*/
            // ดึงข้อมูลจากฐานข้อมูล Temp
            $aGetDataReportParams = array(
                'nPerPage' => $this->nPerPage,
                'nPage' => $nFile,
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tUserSessionID' => $this->tUserSessionID
            );
            $aDataReport = $this->mRptMovePosVd->FSaMGetDataReport($aGetDataReportParams);
            /*=========== End Get Data =======================================*/
            
            if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
                /*===== ตั้งค่ารายงาน ============================================*/
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
                $tFillterColumRIGHT = "G";

                /*===== Begin Fillter ===========================================================================*/
                // Fillter Branch (สาขา)
                if (!empty($this->aRptFilter['tBchCodeFrom']) && !empty($this->aRptFilter['tBchCodeTo'])) {
                    
                    $tRptFilterBchCodeFrom = $this->aText['tRptBchFrom'] . ' ' . $this->aRptFilter['tBchNameFrom'];
                    $tRptFilterBchCodeTo = $this->aText['tRptBchTo'] . ' ' . $this->aRptFilter['tBchNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterBchCodeFrom . ' ' . $tRptFilterBchCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter Product (สินค้า)
                if (!empty($this->aRptFilter['tPdtCodeFrom']) && !empty($this->aRptFilter['tPdtCodeTo'])) {
                    
                    $tRptFilterBchCodeFrom = $this->aText['tPdtCodeFrom'] . ' ' . $this->aRptFilter['tPdtNameFrom'];
                    $tRptFilterBchCodeTo = $this->aText['tPdtCodeTo'] . ' ' . $this->aRptFilter['tPdtNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterBchCodeFrom . ' ' . $tRptFilterBchCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter WareHouse (คลังสินค้า)
                if (!empty($this->aRptFilter['tWahCodeFrom']) && !empty($this->aRptFilter['tWahCodeTo'])) {
                    
                    $tRptFilterDocDateFrom = $this->aText['tRptFromWareHouse'] . ' ' . $this->aRptFilter['tWahNameFrom'];
                    $tRptFilterDocDateTo = $this->aText['tRptToWareHouse'] . ' ' . $this->aRptFilter['tWahNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterDocDateFrom . ' ' . $tRptFilterDocDateTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter DocDate (วันที่สร้างเอกสาร)
                if (!empty($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateTo'])) {
                    
                    $tRptFilterDocDateFrom = $this->aText['tRptTaxSaleLockerFilterDocDateFrom'] . ' ' . $this->aRptFilter['tDocDateFrom'];
                    $tRptFilterDocDateTo = $this->aText['tRptTaxSaleLockerFilterDocDateTo'] . ' ' . $this->aRptFilter['tDocDateTo'];
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
                
                // ตั้งค่าวันที่เวลาออกรายงาน
                $tRptDateTimeExportText = $this->aText['tDatePrint'] . ' ' . $dDatePrint . ' ' . $this->aText['tTimePrint'] . ' ' . $dTimePrint;
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowDateExport.':K'.$nStartRowDateExport);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nStartRowDateExport, $tRptDateTimeExportText);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowDateExport)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowDateExport)->applyFromArray($aStyleRptSizeAddressFont);
                
                // เริ่มตำแหน่ง หัวรายงาน
                $nStartRowHeadder = $nStartRowDateExport + 1;
                
                /*===== หัวตารางรายงาน =========================================*/
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
                        ->setCellValue('A' . $nStartRowHeadder, $this->aText['tWahName'])
                        ->setCellValue('B' . $nStartRowHeadder, $this->aText['tPdtCode'])
                        ->setCellValue('C' . $nStartRowHeadder, $this->aText['tPdtName'])
                        ->setCellValue('D' . $nStartRowHeadder, $this->aText['tRptDoc'])
                        ->setCellValue('E' . $nStartRowHeadder, $this->aText['tRptDate'])
                        ->setCellValue('F' . $nStartRowHeadder, $this->aText['tRptBringF'])
                        ->setCellValue('G' . $nStartRowHeadder, $this->aText['tRptIn'])
                        ->setCellValue('H' . $nStartRowHeadder, $this->aText['tRptEx'])
                        ->setCellValue('I' . $nStartRowHeadder, $this->aText['tRptSale'])
                        ->setCellValue('J' . $nStartRowHeadder, $this->aText['tRptAdjud'])
                        ->setCellValue('K' . $nStartRowHeadder, $this->aText['tRptInven']);

                // Alignment ของหัวตาราง
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':K' . $nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
                /*===== วนลูปข้อมูลตารางข้อมูล ====================================*/
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
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tRptTotalSub']);
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
                    if ($bIsLastFile) {
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
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tRptOverall']);
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
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData . ':H' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    }
                } else {
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':E' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tRptAdjStkNoData']);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
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


























