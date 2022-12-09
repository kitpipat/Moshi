<?php

defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set("Asia/Bangkok");

include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

class cRptProductTransfer extends MX_Controller {

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
        $this->load->model('report/reportvending/mRptProductTransfer');
        $this->load->model('report/report/mReport');
        // Init Report
        $this->init($paParams);
    }

    private function init($paParams = []) {
        $this->aText = [
            'tTitleReport' => language('report/report/report', 'tRptTitleProductTransfer'),
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
            // Table Label
            'tRptDocument' => language('report/report/report', 'tRptDocument'),
            'tRptDateDocument' => language('report/report/report', 'tRptDateDocument'),
            'tRptFromWareHouse' => language('report/report/report', 'tRptFromWareHouse'),
            'tRptToWareHouse' => language('report/report/report', 'tRptToWareHouse'),
            'tRptAdjStkVDPdtCode' => language('report/report/report', 'tRptAdjStkVDPdtCode'),
            'tRptAdjStkVDPdtName' => language('report/report/report', 'tRptAdjStkVDPdtName'),
            'tRptAdjStkVDLayRow' => language('report/report/report', 'tRptAdjStkVDLayRow'),
            'tRptAdjStkVDLayCol' => language('report/report/report', 'tRptAdjStkVDLayCol'),
            'tRptAdjStkVDLayCol' => language('report/report/report', 'tRptAdjStkVDLayCol'),
            'tRptTransferamount' => language('report/report/report', 'tRptTransferamount'),
            'tRptListener' => language('report/report/report', 'tRptListener'),
            'tRptTaxSaleLockerFilterMerChantFrom' => language('report/report/report', 'tRptTaxSaleLockerFilterMerChantFrom'),
            'tRptTaxSaleLockerFilterMerChantTo' => language('report/report/report', 'tRptTaxSaleLockerFilterMerChantTo'),
            // No Data Report
            'tRptTaxSaleLockerNoData' => language('common/main/main', 'tCMNNotFoundData'),
            'tRptTaxSaleLockerTotalSub' => language('report/report/report', 'tRptTaxSaleLockerTotalSub'),
            'tRptTaxSaleLockerTotalFooter' => language('report/report/report', 'tRptTaxSaleLockerTotalFooter'),
            // Filter Text Label
            'tRptTaxSaleLockerFilterShopFrom' => language('report/report/report', 'tRptTaxSaleLockerFilterShopFrom'),
            'tRptTaxSaleLockerFilterShopTo' => language('report/report/report', 'tRptTaxSaleLockerFilterShopTo'),
            'tRptTaxSaleLockerFilterDocDateFrom' => language('report/report/report', 'tRptTaxSaleLockerFilterDocDateFrom'),
            'tRptTaxSaleLockerFilterDocDateTo' => language('report/report/report', 'tRptTaxSaleLockerFilterDocDateTo'),
            'tRptproducttransferFilterPosFrom' => language('report/report/report', 'tRptproducttransferFilterPosFrom'),
            'tRptproducttransferFilterPosTo' => language('report/report/report', 'tRptproducttransferFilterPosTo')
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
            // กลุ่มธุรกิจ
            'tMerCodeFrom' => (isset($paParams['aFilter']['tMerCodeFrom']) && !empty($paParams['aFilter']['tMerCodeFrom'])) ? $paParams['aFilter']['tMerCodeFrom'] : "",
            'tMerCodeTo' => (isset($paParams['aFilter']['tMerCodeTo']) && !empty($paParams['aFilter']['tMerCodeTo'])) ? $paParams['aFilter']['tMerCodeTo'] : "",
            'tMerNameFrom' => (isset($paParams['aFilter']['tMerNameFrom']) && !empty($paParams['aFilter']['tMerNameFrom'])) ? $paParams['aFilter']['tMerNameFrom'] : "",
            'tMerNameTo' => (isset($paParams['aFilter']['tMerNameTo']) && !empty($paParams['aFilter']['tMerNameTo'])) ? $paParams['aFilter']['tMerNameTo'] : "",      
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
            // คลังสินค้า
            'tWahCodeFrom' => (isset($paParams['aFilter']['tWahCodeFrom']) && !empty($paParams['aFilter']['tWahCodeFrom'])) ? $paParams['aFilter']['tWahCodeFrom'] : "",
            'tWahCodeTo' => (isset($paParams['aFilter']['tWahCodeTo']) && !empty($paParams['aFilter']['tWahCodeTo'])) ? $paParams['aFilter']['tWahCodeTo'] : "",
            'tWahNameFrom' => (isset($paParams['aFilter']['tWahNameFrom']) && !empty($paParams['aFilter']['tWahNameFrom'])) ? $paParams['aFilter']['tWahNameFrom'] : "",
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
            /** =========== Begin Init Variable ============================= */
            $tReportName = $this->aText['tTitleReport'];
            $tDateExport = date('Y-m-d');
            $tTimeExport = date('H:i:s');
            $nFile = $paParams['nFile'];
            $bIsLastFile = $paParams['bIsLastFile'];
            $tFileName = $paParams['tFileName'];
            /** =========== End Init Variable =============================== */
            
            
            /** =========== Begin Get Data ================================== */
            // ดึงข้อมูลจากฐานข้อมูล Temp
            $aGetDataReportParams = array(
                'nPerPage' => $this->nPerPage,
                'nPage' => $nFile,
                'tCompName' => $this->tCompName,
                'tRptCode' => $this->tRptCode,
                'tUserSessionID' => $this->tUserSessionID
            );
            $aDataReport = $this->mRptProductTransfer->FSaMGetDataReport($aGetDataReportParams);
            /*===== End Get Data =============================================*/
            
            if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
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
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(45);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);

                // ชื่อหัวรายงาน
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:K1');
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
                
                $tFillterColumLEFT = "D";
                $tFillterColumRIGHT = "K";

                /*===== Begin Fillter ===========================================================================*/
                // Fillter MerChant (กลุ่มธุรกิจ)
                if (!empty($this->aRptFilter['tMerCodeFrom']) && !empty($this->aRptFilter['tMerCodeTo'])) {
                    
                    $tRptFilterShopCodeFrom = $this->aText['tRptTaxSaleLockerFilterMerChantFrom'] . ' ' . $this->aRptFilter['tMerNameFrom'];
                    $tRptFilterShopCodeTo = $this->aText['tRptTaxSaleLockerFilterMerChantTo'] . ' ' . $this->aRptFilter['tMerNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterShopCodeFrom . ' ' . $tRptFilterShopCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter Shop (ร้านค้า)
                if (!empty($this->aRptFilter['tShpCodeFrom']) && !empty($this->aRptFilter['tShpCodeTo'])) {
                    
                    $tRptFilterShopCodeFrom = $this->aText['tRptTaxSaleLockerFilterShopFrom'] . ' ' . $this->aRptFilter['tShpNameFrom'];
                    $tRptFilterShopCodeTo = $this->aText['tRptproducttransferFilterPosTo'] . ' ' . $this->aRptFilter['tShpNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterShopCodeFrom . ' ' . $tRptFilterShopCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // Fillter Pos (เครื่องจุดขาย)
                if (!empty($this->aRptFilter['tPosCodeFrom']) && !empty($this->aRptFilter['tPosCodeTo'])) {
                    
                    $tRptFilterDocDateFrom = $this->aText['tRptproducttransferFilterPosFrom'] . ' ' . $this->aRptFilter['tPosNameFrom'];
                    $tRptFilterDocDateTo = $this->aText['tRptTaxSaleLockerFilterDocDateTo'] . ' ' . $this->aRptFilter['tPosNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterDocDateFrom . ' ' . $tRptFilterDocDateTo;
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
                
                // Date Time Print
                $tRptDateTimeExportText = $this->aText['tDatePrint'] . ' ' . $tDateExport . ' ' . $this->aText['tTimePrint'] . ' ' . $tTimeExport;
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
                    ->setCellValue('A' . $nStartRowHeadder, $this->aText['tRptDocument'])
                    ->setCellValue('B' . $nStartRowHeadder, $this->aText['tRptDateDocument'])
                    ->setCellValue('C' . $nStartRowHeadder, $this->aText['tRptFromWareHouse'])
                    ->setCellValue('D' . $nStartRowHeadder, $this->aText['tRptToWareHouse'])
                    ->setCellValue('E' . $nStartRowHeadder, $this->aText['tRptAdjStkVDPdtCode'])
                    ->setCellValue('F' . $nStartRowHeadder, $this->aText['tRptAdjStkVDPdtName'])
                    ->setCellValue('G' . $nStartRowHeadder, $this->aText['tRptAdjStkVDLayRow'])
                    ->setCellValue('H' . $nStartRowHeadder, $this->aText['tRptAdjStkVDLayCol'])
                    ->setCellValue('I' . $nStartRowHeadder, $this->aText['tRptTransferamount'])
                    ->setCellValue('J' . $nStartRowHeadder, $this->aText['tRptListener']);

                // Alignment ของหัวตาราง
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':G' . $nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('H' . $nStartRowHeadder . ':K' . $nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                
                // ข้อมูลรายละเอียดรายงาน =========================================*/
                // Set Variable Data

                $nStartRowData = $nStartRowHeadder + 1;
                if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {

                    // ******* Step 1 ประกาศตัวแปรใช้ในการเช็คเงื่อนไข
                    $tGrouppingData = "";
                    $nRowID = "";
                    $nGroupMember = "";

                    $nSubSumXshAmt = 0;
                    $nSubSumXshAmtV = 0;
                    $nSubSumXshAmtNV = 0;
                    $nSubSumGrandTotal = 0;

                    $nSumFooterXshAmt = 0;
                    $nSumFooterXshAmtV = 0;
                    $nSumFooterXshAmtNV = 0;
                    $nSumFooterGrandTotal = 0;
                    
                    foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                        // ******* Step 3 Set ค่า Value FNRptGroupMember And FNRowPartID
                        $nRowID = $aValue["RowID"];
                        $nGroupMember = $aValue["FNRptGroupMember"];

                        // ******* Step 4 : Check Groupping Create Row Groupping 
                        if ($tGrouppingData != $aValue['FTXthDocNo']) {
                            $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A' . $nStartRowData, $aValue['FTXthDocNo'])
                                ->setCellValue('B' . $nStartRowData, $aValue['FDXthDocDate'])
                                ->setCellValue('C' . $nStartRowData, $aValue['FTXthWhFrmName'])
                                ->setCellValue('D' . $nStartRowData, $aValue['FTXthWhToName'])
                                ->setCellValue('E' . $nStartRowData, '')
                                ->setCellValue('F' . $nStartRowData, '')
                                ->setCellValue('G' . $nStartRowData, '')
                                ->setCellValue('H' . $nStartRowData, '')
                                ->setCellValue('I' . $nStartRowData, '')
                                ->setCellValue('J' . $nStartRowData, $aValue['FTXtdUsrKey']);
                            $nStartRowData++;
                        }

                        // ******* Step 5 : Loop Set Data Value 
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $nStartRowData, '')
                            ->setCellValue('B' . $nStartRowData, '')
                            ->setCellValue('C' . $nStartRowData, '')
                            ->setCellValue('D' . $nStartRowData, '')
                            ->setCellValue('E' . $nStartRowData, !empty($aValue['FTPdtCode']) ? $aValue['FTPdtCode'] : '-')
                            ->setCellValue('F' . $nStartRowData, !empty($aValue['FTPdtName']) ? $aValue['FTPdtName'] : '-')
                            ->setCellValue('G' . $nStartRowData, !empty($aValue['FNLayRow']) ? $aValue['FNLayRow'] : '-')
                            ->setCellValue('H' . $nStartRowData, !empty($aValue['FNLayCol']) ? $aValue['FNLayCol'] : '-')
                            ->setCellValue('I' . $nStartRowData, !empty($aValue['FCXtdQty']) ? $aValue['FCXtdQty'] : '-')
                            ->setCellValue('J' . $nStartRowData, '');

                        /*->setCellValue('I'.$nStartRowData,number_format($aValue["FCXshAmtV"],2))
                        ->setCellValue('J'.$nStartRowData,number_format($aValue["FCXshAmtNV"],2))
                        ->setCellValue('K'.$nStartRowData,number_format($aValue["FCXshGrandTotal"],2));
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData.':G'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);*/
                        $objPHPExcel->getActiveSheet()->getStyle('G' . $nStartRowData . ':I' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                        /******** Step 6 : เตรียม Parameter สำหรับ Summary Sub Footer
                        if($nRowID  ==  $nGroupMember){
                            $nStartRowData++;
                        $nSubSumXshAmt      = $aValue["FCXshAmt_SubTotal"];
                        $nSubSumXshAmtV     = $aValue["FCXshAmtV_SubTotal"];
                        $nSubSumXshAmtNV    = $aValue["FCXshAmtNV_SubTotal"];
                        $nSubSumGrandTotal  = $aValue["FCXshGrandTotal_SubTotal"];
                        Set Color Row Sub Footer
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData.':K'.$nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData.':K'.$nStartRowData)->applyFromArray(array(
                            'borders' => array(
                                // 'top'       => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')),
                                'bottom'    => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000'))
                            )
                        ));
                        LEFT 
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowData.':G'.$nStartRowData);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nStartRowData,$this->aText['tRptTaxSaleLockerTotalSub']);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData)->applyFromArray($aStyleRptDataTable);
                        RIGHT
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('H'.$nStartRowData,number_format($nSubSumXshAmt,2))
                        ->setCellValue('I'.$nStartRowData,number_format($nSubSumXshAmtV,2))
                        ->setCellValue('J'.$nStartRowData,number_format($nSubSumXshAmtNV,2))
                        ->setCellValue('K'.$nStartRowData,number_format($nSubSumGrandTotal,2));
                        $nStartRowData++;
                        }
                        $nSumFooterXshAmt       = number_format($aValue["FCXshAmt_Footer"],2);
                        $nSumFooterXshAmtV      = number_format($aValue["FCXshAmtV_Footer"],2);
                        $nSumFooterXshAmtNV     = number_format($aValue["FCXshAmtNV_Footer"],2);
                        $nSumFooterGrandTotal   = number_format($aValue["FCXshGrandTotal_Footer"],2);*/
                         
                        // ******* Step 2 Set ค่า Value ให้กับตัวแปร
                        $tGrouppingData = $aValue["FTXthDocNo"];
                        $nStartRowData++;
                    }

                    /*Step 7 : Set Footer Text
                    if($bIsLastFile){
                        $nStartRowData--;
                        // Set Color Row Sub Footer
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData.':K'.$nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData.':K'.$nStartRowData)->applyFromArray(array(
                            'borders' => array(
                                'top'       => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')),
                                'bottom'    => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb' => '000000'))
                            )
                        ));
                        // LEFT 
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowData.':G'.$nStartRowData);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nStartRowData,$this->aText['tRptTaxSaleLockerTotalFooter']);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData)->applyFromArray($aStyleRptDataTable);
                        // RIGHT
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('H'.$nStartRowData,number_format($nSumFooterXshAmt,2))
                        ->setCellValue('I'.$nStartRowData,number_format($nSumFooterXshAmtV,2))
                        ->setCellValue('J'.$nStartRowData,number_format($nSumFooterXshAmtNV,2))
                        ->setCellValue('K'.$nStartRowData,number_format($nSumFooterGrandTotal,2));
                    }*/
                } else {
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':K' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tRptTaxSaleLockerNoData']);
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



































