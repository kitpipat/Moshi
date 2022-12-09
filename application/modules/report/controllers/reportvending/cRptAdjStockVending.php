<?php

defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set("Asia/Bangkok");

include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

class cRptAdjStockVending extends MX_Controller {

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
        $this->load->helper('report');
        $this->load->model('company/company/mCompany');
        $this->load->model('report/report/mReport');
        $this->load->model('report/reportvending/mRptAdjStockVending');
        // Init Report
        $this->init($paParams);
    }

    private function init($paParams = []) {
        $this->aText = [
            'tTitleReport' => language('report/report/report', 'tRptAdjStkVDTitle'),
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
            'tRptAdjStkVDDocNo' => language('report/report/report', 'tRptAdjStkVDDocNo'),
            'tRptAdjStkVDDocDate' => language('report/report/report', 'tRptAdjStkVDDocDate'),
            'tRptAdjStkVDUserAdj' => language('report/report/report', 'tRptAdjStkVDUserAdj'),
            'tRptAdjStkVDPdtCode' => language('report/report/report', 'tRptAdjStkVDPdtCode'),
            'tRptAdjStkVDPdtName' => language('report/report/report', 'tRptAdjStkVDPdtName'),
            'tRptAdjStkVDLayRow' => language('report/report/report', 'tRptAdjStkVDLayRow'),
            'tRptAdjStkVDLayCol' => language('report/report/report', 'tRptAdjStkVDLayCol'),
            'tRptAdjStkVDWahB4Adj' => language('report/report/report', 'tRptAdjStkVDWahB4Adj'),
            'tRptAdjStkVDUnitQty' => language('report/report/report', 'tRptAdjStkVDUnitQty'),
            'tRptAdjStkVDTotalSub' => language('report/report/report', 'tRptAdjStkVDTotalSub'),
            'tRptAdjStkVDTotalFooter' => language('report/report/report', 'tRptAdjStkVDTotalFooter'),
            // No Data Report
            'tRptAdjStkNoData' => language('common/main/main', 'tCMNNotFoundData'),
            'tRptFilterMerFrom'     => language('report/report/report','tRptMerFrom'),
            'tRptFilterMerTo'       => language('report/report/report','tRptMerTo'),
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
            // Filter Merchant (กลุ่มธุรกิจ)
            'tMerCodeFrom' => (isset($paParams['aFilter']['tMerCodeFrom']) && !empty($paParams['aFilter']['tMerCodeFrom'])) ? $paParams['aFilter']['tMerCodeFrom'] : "",
            'tMerNameFrom' => (isset($paParams['aFilter']['tMerNameFrom']) && !empty($paParams['aFilter']['tMerNameFrom'])) ? $paParams['aFilter']['tMerNameFrom'] : "",
            'tMerCodeTo' => (isset($paParams['aFilter']['tMerCodeTo']) && !empty($paParams['aFilter']['tMerCodeTo'])) ? $paParams['aFilter']['tMerCodeTo'] : "",
            'tMerNameTo' => (isset($paParams['aFilter']['tMerNameTo']) && !empty($paParams['aFilter']['tMerNameTo'])) ? $paParams['aFilter']['tMerNameTo'] : "",
            // Filter Shop (ร้านค้า)
            'tShpCodeFrom' => (isset($paParams['aFilter']['tShpCodeFrom']) && !empty($paParams['aFilter']['tShpCodeFrom'])) ? $paParams['aFilter']['tShpCodeFrom'] : "",
            'tShpNameFrom' => (isset($paParams['aFilter']['tShpNameFrom']) && !empty($paParams['aFilter']['tShpNameFrom'])) ? $paParams['aFilter']['tShpNameFrom'] : "",
            'tShpCodeTo' => (isset($paParams['aFilter']['tShpCodeTo']) && !empty($paParams['aFilter']['tShpCodeTo'])) ? $paParams['aFilter']['tShpCodeTo'] : "",
            'tShpNameTo' => (isset($paParams['aFilter']['tShpNameTo']) && !empty($paParams['aFilter']['tShpNameTo'])) ? $paParams['aFilter']['tShpNameTo'] : "",
            // Filter Pos (เครื่องจุดขาย)
            'tPosCodeFrom' => (isset($paParams['aFilter']['tPosCodeFrom']) && !empty($paParams['aFilter']['tPosCodeFrom'])) ? $paParams['aFilter']['tPosCodeFrom'] : "",
            'tPosNameFrom' => (isset($paParams['aFilter']['tPosNameFrom']) && !empty($paParams['aFilter']['tPosNameFrom'])) ? $paParams['aFilter']['tPosNameFrom'] : "",
            'tPosCodeTo' => (isset($paParams['aFilter']['tPosCodeTo']) && !empty($paParams['aFilter']['tPosCodeTo'])) ? $paParams['aFilter']['tPosCodeTo'] : "",
            'tPosNameTo' => (isset($paParams['aFilter']['tPosNameTo']) && !empty($paParams['aFilter']['tPosNameTo'])) ? $paParams['aFilter']['tPosNameTo'] : "",
            // Filter Pos (เครื่องจุดขาย)
            'tWahCodeFrom' => (isset($paParams['aFilter']['tWahCodeFrom']) && !empty($paParams['aFilter']['tWahCodeFrom'])) ? $paParams['aFilter']['tWahCodeFrom'] : "",
            'tWahNameFrom' => (isset($paParams['aFilter']['tWahNameFrom']) && !empty($paParams['aFilter']['tWahNameFrom'])) ? $paParams['aFilter']['tWahNameFrom'] : "",
            'tWahCodeTo' => (isset($paParams['aFilter']['tWahCodeTo']) && !empty($paParams['aFilter']['tWahCodeTo'])) ? $paParams['aFilter']['tWahCodeTo'] : "",
            'tWahNameTo' => (isset($paParams['aFilter']['tWahNameTo']) && !empty($paParams['aFilter']['tWahNameTo'])) ? $paParams['aFilter']['tWahNameTo'] : "",
            // Filter Document Date (วันที่สร้างเอกสาร)
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
     * Functionality: Function Render Report Excel
     * Parameters:  Function Parameter
     * Creator: 25/08/2019 pap
     * LastUpdate: 16/09/2019 Piya
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptRenderExcel($paParams = []) {
        try {
            /** =========== Begin Init Variable ============================= */
            $tReportName = $this->aText['tTitleReport'];
            $nFile = $paParams['nFile'];
            $bIsLastFile = $paParams['bIsLastFile'];
            $tFileName = $paParams['tFileName'];
            $tDatePrint = date('Y-m-d');
            $tTimePrint = date('H:i:s');
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
            $aDataReport = $this->mRptAdjStockVending->FSaMGetDataReport($aGetDataReportParams);
            /** =========== End Get Data ==================================== */
            
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
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);

                // ชื่อหัวรายงาน
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:I1');
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
                $tFillterColumRIGHT = "H";
                
                /*===== Begin Fillter ===========================================================================*/
                // Fillter MerChant (กลุ่มธุรกิจ)
                if(!empty($this->aRptFilter['tMerCodeFrom']) && !empty($this->aRptFilter['tMerCodeTo'])){
                    
                    $tRptFilterFrom = $this->aText['tRptFilterMerFrom'].' '.$this->aRptFilter['tMerNameFrom'];
                    $tRptFilterTo = $this->aText['tRptFilterMerTo'].' '.$this->aRptFilter['tMerNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterFrom.' '.$tRptFilterTo;
                    $tColumsMergeFilter = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter,$tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;
                    
                }

                // Fillter Shop (ร้านค้า)
                if(!empty($this->aRptFilter['tShpCodeFrom']) && !empty($this->aRptFilter['tShpCodeTo'])){
                    
                    $tRptFilterShopCodeFrom = $this->aText['tRptFilterShopFrom'].' '.$this->aRptFilter['tShpNameFrom'];
                    $tRptFilterShopCodeTo = $this->aText['tRptFilterShopTo'].' '.$this->aRptFilter['tShpNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterShopCodeFrom.' '.$tRptFilterShopCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter,$tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }

                // เครื่องจุดขาย
                if(!empty($this->aRptFilter['tPosCodeFrom']) && !empty($this->aRptFilter['tPosCodeTo'])){
                    
                    // จากเครื่องจุดขาย
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter, $this->aText['tRptSaleByPaymentDetailFilterPosFrom'] . $this->aRptFilter['tPosNameFrom']);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)
                    ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    // ถึงเครื่องจุดขาย
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumRIGHT.$nStartRowFillter, $this->aText['tRptSaleByPaymentDetailFilterPosTo'] . $this->aRptFilter['tPosNameTo']);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumRIGHT.$nStartRowFillter)
                    ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;
                    
                }
                
                // Fillter WareHouse (คลังสินค้า)
                if (!empty($this->aRptFilter['tWahCodeFrom']) && !empty($this->aRptFilter['tWahCodeTo'])) {
                    
                    $tRptFilterFrom = $this->aText['tRptFromWareHouse'].' '.$this->aRptFilter['tWahNameFrom'];
                    $tRptFilterTo = $this->aText['tRptToWareHouse'].' '.$this->aRptFilter['tWahNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterFrom.' '.$tRptFilterTo;
                    $tColumsMergeFilter = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;
                    
                }
                
                // Fillter DocDate (วันที่สร้างเอกสาร)
                if(!empty($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateTo'])){
                    
                    $tRptFilterDocDateFrom = $this->aText['tRptFilterDateFrom'].' '.$this->aRptFilter['tDocDateFrom'];
                    $tRptFilterDocDateTo = $this->aText['tRptFilterDateTo'].' '.$this->aRptFilter['tDocDateTo'];
                    $tRptTextLeftRightFilter = $tRptFilterDocDateFrom.' '.$tRptFilterDocDateTo;
                    $tColumsMergeFilter = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    
                    $nStartRowFillter += 1;
                    
                }
                /*===== End Fillter ===========================================================================*/
                
                // รายละเอียดการออกรายงาน
                $nStartRowDateExport = $nStartRowFillter + 1;
                
                /*===== ตั้งค่าวันที่เวลาออกรายงาน ===================================*/
                $tRptDateTimeExportText = $this->aText['tDatePrint'] . ' ' . $tDatePrint . ' ' . $this->aText['tTimePrint'] . ' ' . $tTimePrint;
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowDateExport.':I'.$nStartRowDateExport);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nStartRowDateExport, $tRptDateTimeExportText);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowDateExport)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowDateExport)->applyFromArray($aStyleAddressFont);
                
                // เริ่มตำแหน่ง หัวรายงาน
                $nStartRowHeadder = $nStartRowDateExport + 1;
                
                /*===== หัวตารางรายงาน =========================================*/;
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
                        ->setCellValue('A' . $nStartRowHeadder, $this->aText['tRptAdjStkVDDocNo'])
                        ->setCellValue('B' . $nStartRowHeadder, $this->aText['tRptAdjStkVDDocDate'])
                        ->setCellValue('C' . $nStartRowHeadder, $this->aText['tRptAdjStkVDUserAdj'])
                        ->setCellValue('D' . $nStartRowHeadder, $this->aText['tRptAdjStkVDPdtCode'])
                        ->setCellValue('E' . $nStartRowHeadder, $this->aText['tRptAdjStkVDPdtName'])
                        ->setCellValue('F' . $nStartRowHeadder, $this->aText['tRptAdjStkVDLayRow'])
                        ->setCellValue('G' . $nStartRowHeadder, $this->aText['tRptAdjStkVDLayCol'])
                        ->setCellValue('H' . $nStartRowHeadder, $this->aText['tRptAdjStkVDWahB4Adj'])
                        ->setCellValue('I' . $nStartRowHeadder, $this->aText['tRptAdjStkVDUnitQty']);
                // Alignment ของหัวตาราง
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':I' . $nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
                /*===== วนลูปข้อมูลตารางข้อมูล =====================================*/
                // Set Variable Data
                $nStartRowData = $nStartRowHeadder + 1;
                if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
                    // ******* Step 1 ประกาศตัวแปรใช้ในการเช็คเงื่อนไข
                    $tGrouppingData = "";
                    $nGroupMember = "";
                    $nRowPartID = "";

                    $nSumFooterAjdWahB4Adj = 0;
                    $nSumFooterAjdUnitQty = 0;
                    foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                        // ******* Step 3 Set ค่า Value FNRptGroupMember And FNRowPartID
                        $nRowPartID = $aValue["FNRowPartID"];
                        $nGroupMember = $aValue["FNRptGroupMember"];

                        // ******* Step 4 : Check Groupping Create Row Groupping 
                        if ($tGrouppingData != $aValue['FTAjhDocNo']) {
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $aValue['FTAjhDocNo']);
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $nStartRowData, $aValue['FTAjhDocNo']);
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $nStartRowData, $aValue['FTAjdApvName']);
                            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':C' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
                            $nStartRowData++;
                        }

                        // ******* Step 5 : Loop Set Data Value 
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':C' . $nStartRowData);
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('D' . $nStartRowData, $aValue['FTPdtCode'])
                                ->setCellValue('E' . $nStartRowData, $aValue['FTPdtName'])
                                ->setCellValue('F' . $nStartRowData, number_format($aValue['FNAjdLayRow'], 0))
                                ->setCellValue('G' . $nStartRowData, number_format($aValue['FNAjdLayCol'], 0))
                                ->setCellValue('H' . $nStartRowData, number_format($aValue['FCAjdWahB4Adj'], 0))
                                ->setCellValue('I' . $nStartRowData, number_format($aValue['FCAjdUnitQty'], 0));
                        $objPHPExcel->getActiveSheet()->getStyle('D' . $nStartRowData . ':E' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData . ':I' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                        // ******* Step 6 : เตรียม Parameter สำหรับ Summary Sub Footer
                        if ($nRowPartID == $nGroupMember) {
                            $nStartRowData++;
                            $nSubSumAjdWahB4Adj = $aValue["FCAjdWahB4Adj_SubTotal"];
                            $nSubSumAjdUnitQty = $aValue["FCAjdUnitQty_SubTotal"];
                            // Set Color Row Sub Footer
                            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':I' . $nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':I' . $nStartRowData)->applyFromArray(array(
                                'borders' => array(
                                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))
                                )
                            ));

                            // Text Sum Sub
                            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':G' . $nStartRowData);
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tRptAdjStkVDTotalSub']);
                            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);

                            // Value Sum Sub
                            $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('H' . $nStartRowData, number_format($nSubSumAjdWahB4Adj, 0))
                                    ->setCellValue('I' . $nStartRowData, number_format($nSubSumAjdUnitQty, 0));
                        }

                        $nSumFooterAjdWahB4Adj = number_format($aValue["FCAjdWahB4Adj_Footer"], $this->nOptDecimalShow);
                        $nSumFooterAjdUnitQty = number_format($aValue["FCAjdUnitQty_Footer"], $this->nOptDecimalShow);

                        // ******* Step 2 Set ค่า Value ให้กับตัวแปร
                        $tGrouppingData = $aValue["FTAjhDocNo"];
                        $nStartRowData++;
                    }

                    // Step 7 : Set Footer Text
                    if ($bIsLastFile) {
                        // Set Color Row Sub Footer
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':I' . $nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':I' . $nStartRowData)->applyFromArray(array(
                            'borders' => array(
                                'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                                'bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE, 'color' => array('rgb' => '000000'))
                            )
                        ));

                        // Text Sum Footer
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':G' . $nStartRowData);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tRptAdjStkVDTotalFooter']);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);

                        // Value Sum Footer
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('H' . $nStartRowData, number_format($nSumFooterAjdWahB4Adj, $this->nOptDecimalShow))
                                ->setCellValue('I' . $nStartRowData, number_format($nSumFooterAjdUnitQty, $this->nOptDecimalShow));
                    }
                } else {
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':I' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tRptAdjStkNoData']);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
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






























