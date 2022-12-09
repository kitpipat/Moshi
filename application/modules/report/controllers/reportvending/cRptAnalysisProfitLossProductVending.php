<?php

defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set("Asia/Bangkok");

include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

class cRptAnalysisProfitLossProductVending extends MX_Controller {

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
        $this->load->model('report/reportvending/mRptAnalysisProfitLossProductVending');
        // Init Report
        $this->init($paParams);
    }

    private function init($paParams = []) {
        $this->aText = [
            'tTitleReport'      => language('report/report/report', 'tRptAnalysisProfitLossProductVending'),
            'tRptTaxNo'         => language('report/report/report', 'tRptTaxNo'),
            'tRptDatePrint'     => language('report/report/report', 'tRptDatePrint'),
            'tRptDateExport'    => language('report/report/report', 'tRptDateExport'),
            'tRptTimePrint'     => language('report/report/report', 'tRptTimePrint'),
            'tRptPrintHtml'     => language('report/report/report', 'tRptPrintHtml'),
            'tRptBranch'        => language('report/report/report', 'tRptBranch'),
            'tRptFaxNo'         => language('report/report/report', 'tRptFaxNo'),
            'tRptTel'           => language('report/report/report', 'tRptTel'),
            // Filter Heard Report
            // สาขา
            'tRptBchFrom'       => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo'         => language('report/report/report', 'tRptBchTo'),
            //กลุ่มธุรกิจ
            'tRptMerFrom'       => language('report/report/report', 'tRptMerFrom'),
            'tRptMerTo'         => language('report/report/report', 'tRptMerTo'),
            //ร้านค้า
            'tRptShopFrom'      => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo'        => language('report/report/report', 'tRptShopTo'),
            // เครื่องจุดขาย
            'tRptPosFrom'       => language('report/report/report', 'tRptPosFrom'),
            'tRptPosTo'         => language('report/report/report', 'tRptPosTo'),
            //สินค้า
            'tRptPdtCodeFrom'   => language('report/report/report', 'tPdtCodeFrom'),
            'tRptPdtCodeTo'     => language('report/report/report', 'tPdtCodeTo'),
            // กลุ่มสินค้า
            'tRptPdtGrpFrom'    => language('report/report/report', 'tPdtGrpFrom'),
            'tRptPdtGrpTo'      => language('report/report/report', 'tPdtGrpTo'),
            // ประเภทสินค้า
            'tRptPdtTypeFrom'   => language('report/report/report', 'tPdtTypeFrom'),
            'tRptPdtTypeTo'     => language('report/report/report', 'tPdtTypeTo'),
            //วันที่
            'tRptDateFrom'      => language('report/report/report', 'tRptDateFrom'),
            'tRptDateTo'        => language('report/report/report', 'tRptDateTo'),
            // Table Report
            'tRptPdtCode'       => language('report/report/report', 'tRptPdtCode'),
            'tRptPdtName'       => language('report/report/report', 'tRptPdtName'),
            'tRptPdtGrp'        => language('report/report/report', 'tRptPdtGrp'),
            'tRptQtySale'       => language('report/report/report', 'tRptQtySale'),
            'tRptCabinetCost'   => language('report/report/report', 'tRptCabinetCost'),
            'tRptGrandSale'     => language('report/report/report', 'tRptGrandSale'),
            'tRptProfitloss'    => language('report/report/report', 'tRptProfitloss'),
            'tRptCost'          => language('report/report/report', 'tRptCost'),
            'tRptSalesVending'  => language('report/report/report', 'tRptSalesVending'),
            'tRptOverall'       => language('report/report/report', 'tRptOverall'),
            'tRptAddrRoad'      => language('report/report/report', 'tRptAddrRoad'),
            'tRptAddrSoi'       => language('report/report/report', 'tRptAddrSoi'),
            'tRptNoData'        => language('report/report/report', 'tRptNoData'),
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
            'tBchCodeFrom'  => (isset($paParams['aFilter']['tBchCodeFrom']) && !empty($paParams['aFilter']['tBchCodeFrom'])) ? $paParams['aFilter']['tBchCodeFrom'] : "",
            'tBchCodeTo'    => (isset($paParams['aFilter']['tBchCodeTo']) && !empty($paParams['aFilter']['tBchCodeTo'])) ? $paParams['aFilter']['tBchCodeTo'] : "",
            'tBchNameFrom'  => (isset($paParams['aFilter']['tBchNameFrom']) && !empty($paParams['aFilter']['tBchNameFrom'])) ? $paParams['aFilter']['tBchNameFrom'] : "",
            'tBchNameTo'    => (isset($paParams['aFilter']['tBchNameTo']) && !empty($paParams['aFilter']['tBchNameTo'])) ? $paParams['aFilter']['tBchNameTo'] : "",
            
            //กลุ่มธุรกิจ(MerChant)
            'tMerCodeFrom'  => (isset($paParams['aFilter']['tMerCodeFrom']) && !empty($paParams['aFilter']['tMerCodeFrom'])) ? $paParams['aFilter']['tMerCodeFrom'] : "",
            'tMerCodeTo'    => (isset($paParams['aFilter']['tMerCodeTo']) && !empty($paParams['aFilter']['tMerCodeTo'])) ? $paParams['aFilter']['tMerCodeTo'] : "",
            'tMerNameFrom'  => (isset($paParams['aFilter']['tMerNameFrom']) && !empty($paParams['aFilter']['tMerNameFrom'])) ? $paParams['aFilter']['tMerNameFrom'] : "",
            'tMerNameTo'    => (isset($paParams['aFilter']['tMerNameTo']) && !empty($paParams['aFilter']['tMerNameTo'])) ? $paParams['aFilter']['tMerNameTo'] : "",
            
            // ร้านค้า
            'tShpCodeFrom'  => (isset($paParams['aFilter']['tShpCodeFrom']) && !empty($paParams['aFilter']['tShpCodeFrom'])) ? $paParams['aFilter']['tShpCodeFrom'] : "",
            'tShpCodeTo'    => (isset($paParams['aFilter']['tShpCodeTo']) && !empty($paParams['aFilter']['tShpCodeTo'])) ? $paParams['aFilter']['tShpCodeTo'] : "",
            'tShpNameFrom'  => (isset($paParams['aFilter']['tShpNameFrom']) && !empty($paParams['aFilter']['tShpNameFrom'])) ? $paParams['aFilter']['tShpNameFrom'] : "",
            'tShpNameTo'    => (isset($paParams['aFilter']['tShpNameTo']) && !empty($paParams['aFilter']['tShpNameTo'])) ? $paParams['aFilter']['tShpNameTo'] : "",

            // เครื่องจุดขาย
            'tPosCodeFrom'  => (isset($paParams['aFilter']['tPosCodeFrom']) && !empty($paParams['aFilter']['tPosCodeFrom'])) ? $paParams['aFilter']['tPosCodeFrom'] : "",
            'tPosCodeTo'    => (isset($paParams['aFilter']['tPosCodeTo']) && !empty($paParams['aFilter']['tPosCodeTo'])) ? $paParams['aFilter']['tPosCodeTo'] : "",
            'tPosNameFrom'  => (isset($paParams['aFilter']['tPosNameFrom']) && !empty($paParams['aFilter']['tPosNameFrom'])) ? $paParams['aFilter']['tPosNameFrom'] : "",
            'tPosNameTo'    => (isset($paParams['aFilter']['tPosNameTo']) && !empty($paParams['aFilter']['tPosNameTo'])) ? $paParams['aFilter']['tPosNameTo'] : "",

            //สินค้า
            'tPdtCodeFrom'  => (isset($paParams['aFilter']['tPdtCodeFrom']) && !empty($paParams['aFilter']['tPdtCodeFrom'])) ? $paParams['aFilter']['tPdtCodeFrom'] : "",
            'tPdtCodeTo'    => (isset($paParams['aFilter']['tPdtCodeTo']) && !empty($paParams['aFilter']['tPdtCodeTo'])) ? $paParams['aFilter']['tPdtCodeTo'] : "",
            'tPdtNameFrom'  => (isset($paParams['aFilter']['tPdtNameFrom']) && !empty($paParams['aFilter']['tPdtNameFrom'])) ? $paParams['aFilter']['tPdtNameFrom'] : "",
            'tPdtNameTo'    => (isset($paParams['aFilter']['tPdtNameTo']) && !empty($paParams['aFilter']['tPdtNameTo'])) ? $paParams['aFilter']['tPdtNameTo'] : "",
            
            // กลุ่มสินค้า
            'tPdtGrpCodeFrom'  => (isset($paParams['aFilter']['tPdtGrpCodeFrom']) && !empty($paParams['aFilter']['tPdtGrpCodeFrom'])) ? $paParams['aFilter']['tPdtGrpCodeFrom'] : "",
            'tPdtGrpCodeTo'    => (isset($paParams['aFilter']['tPdtGrpCodeTo']) && !empty($paParams['aFilter']['tPdtGrpCodeTo'])) ? $paParams['aFilter']['tPdtGrpCodeTo'] : "",
            'tPdtGrpNameFrom'  => (isset($paParams['aFilter']['tPdtGrpNameFrom']) && !empty($paParams['aFilter']['tPdtGrpNameFrom'])) ? $paParams['aFilter']['tPdtGrpNameFrom'] : "",
            'tPdtGrpNameTo'    => (isset($paParams['aFilter']['tPdtGrpNameTo']) && !empty($paParams['aFilter']['tPdtGrpNameTo'])) ? $paParams['aFilter']['tPdtGrpNameTo'] : "",

             //ประเภทสินค้า
            'tPdtTypeCodeFrom'  => (isset($paParams['aFilter']['tPdtTypeCodeFrom']) && !empty($paParams['aFilter']['tPdtTypeCodeFrom'])) ? $paParams['aFilter']['tPdtTypeCodeFrom'] : "",
            'tPdtTypeCodeTo'    => (isset($paParams['aFilter']['tPdtTypeCodeTo']) && !empty($paParams['aFilter']['tPdtTypeCodeTo'])) ? $paParams['aFilter']['tPdtTypeCodeTo'] : "",
            'tPdtTypeNameFrom'  => (isset($paParams['aFilter']['tPdtTypeNameFrom']) && !empty($paParams['aFilter']['tPdtTypeNameFrom'])) ? $paParams['aFilter']['tPdtTypeNameFrom'] : "",
            'tPdtTypeNameTo'    => (isset($paParams['aFilter']['tPdtTypeNameTo']) && !empty($paParams['aFilter']['tPdtTypeNameTo'])) ? $paParams['aFilter']['tPdtTypeNameTo'] : "",

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
            $aDataFilter  = $this->aRptFilter;
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
            $aDataReport = $this->mRptAnalysisProfitLossProductVending->FSaMGetDataReport($aGetDataReportParams, $this->aRptFilter);

            // GetDataSumFootReport
            $aDataSumFoot = $this->mRptAnalysisProfitLossProductVending->FSaMGetDataSumFootReport($aGetDataReportParams, $this->aRptFilter);
            
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
                    $tRptCompTelText = $this->aText['tRptTel'] . ' ' . $tRptCompTel. ' ' .$this->aText['tRptFaxNo']. ' ' .$tRptCmpFax;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', $tRptCompTelText)->getStyle('A5')->applyFromArray($aStyleRptSizeAddressFont);

                    //Check Data Branch
                    if(isset($this->aCompanyInfo['FTBchName']) && !empty($this->aCompanyInfo['FTBchName'])){
                        $tRptBchName = $this->aCompanyInfo['FTBchName'];
                    }else{
                        $tRptBchName = '-';
                    }    
                    $tRptBchName = $this->aText['tRptBranch'] . ' ' . $tRptBchName;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A6', $tRptBchName)->getStyle('A6')->applyFromArray($aStyleRptSizeAddressFont);            
                }

                // เริ่มต้น Filter
                $nStartRowFillter = 2;
                $tFillterColumLEFT = "D";
                $tFillterColumRIGHT = "F";

                /*===== Begin Fillter ===========================================================================*/
                // Fillter ฺBranch (สาขา)
                if (!empty($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeTo'])) {
                    $tRptFilterBranchCodeFrom = $this->aText['tRptBchFrom'] . ' ' . $aDataFilter['tBchNameFrom'];
                    $tRptFilterBranchCodeTo = $this->aText['tRptBchTo'] . ' ' . $aDataFilter['tBchNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterBranchCodeFrom . ' ' . $tRptFilterBranchCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }


                // Fillter MerChant (กลุ่มธุรกิจ)
                if(!empty($aDataFilter['tMerCodeFrom']) && !empty($aDataFilter['tMerCodeTo'])){
        
                    $tRptFilterFrom = $this->aText['tRptMerFrom'].' '.$aDataFilter['tMerNameFrom'];
                    $tRptFilterTo = $this->aText['tRptMerTo'].' '.$aDataFilter['tMerNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterFrom.' '.$tRptFilterTo;
                    $tColumsMergeFilter = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter,$tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;
                    
                }
            

                // Fillter Shop (ร้านค้า)
                if (!empty($aDataFilter['tShpCodeFrom']) && !empty($aDataFilter['tShpCodeTo'])) {
                    $tRptFilterShopCodeFrom = $this->aText['tRptShopFrom'] . ' ' . $this->aRptFilter['tShpNameFrom'];
                    $tRptFilterShopCodeTo = $this->aText['tRptShopTo'] . ' ' . $this->aRptFilter['tShpNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterShopCodeFrom . ' ' . $tRptFilterShopCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }


                

                // Fillter Pos (เครื่องจุดขาย)
                if (!empty($this->aRptFilter['tPosCodeFrom']) && !empty($this->aRptFilter['tPosCodeTo'])) {
                    $tRptFilterPosCodeFrom = $this->aText['tRptPosFrom'] . ' ' . $this->aRptFilter['tPosNameFrom'];
                    $tRptFilterPosCodeTo = $this->aText['tRptPosTo'] . ' ' . $this->aRptFilter['tPosNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterPosCodeFrom . ' ' . $tRptFilterPosCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }

                // Fillter Product (สินค้า)
                if (!empty($this->aRptFilter['tPdtCodeFrom']) && !empty($this->aRptFilter['tPdtCodeTo'])) {
                    $tRptFilterProductCodeFrom = $this->aText['tRptPdtCodeFrom'] . ' ' . $this->aRptFilter['tPdtNameFrom'];
                    $tRptFilterProductCodeTo = $this->aText['tRptPdtCodeTo'] . ' ' . $this->aRptFilter['tPdtNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterProductCodeFrom . ' ' . $tRptFilterProductCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }

                // Fillter ProductGroup (กลุ่มสินค้า)
                if (!empty($this->aRptFilter['tPdtGrpCodeFrom']) && !empty($this->aRptFilter['tPdtGrpCodeTo'])) {
                    $tRptFilterProductGrpCodeFrom = $this->aText['tRptPdtGrpFrom'] . ' ' . $this->aRptFilter['tPdtGrpNameFrom'];
                    $tRptFilterProductGrpCodeTo = $this->aText['tRptPdtGrpTo'] . ' ' . $this->aRptFilter['tPdtGrpNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterProductGrpCodeFrom . ' ' . $tRptFilterProductGrpCodeTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }

                // Fillter ProductType (ประเภทสินค้า)
                if (!empty($this->aRptFilter['tPdtTypeCodeFrom']) && !empty($this->aRptFilter['tPdtTypeCodeTo'])) {
                    $tRptFilterProductTypeCodeFrom = $this->aText['tRptPdtTypeFrom'] . ' ' . $this->aRptFilter['tPdtTypeNameFrom'];
                    $tRptFilterProductTypeCodeTo = $this->aText['tRptPdtTypeTo'] . ' ' . $this->aRptFilter['tPdtTypeNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterProductTypeCodeFrom . ' ' . $tRptFilterProductTypeCodeTo;
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
                /*===== End Filter Data Report ===============================*/
                
                // รายละเอียดการออกรายงาน
                $nStartRowDateExport = $nStartRowFillter + 1;

                // Date Time Print   
                $nStartRowFillter = 10;
                $tRptDateTimeExportText = $this->aText['tRptDatePrint'] . ' ' . $tDateExport . ' ' . $this->aText['tRptTimePrint'] . ' ' . $tTimeExport;
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowFillter . ':I' . $nStartRowFillter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowFillter, $tRptDateTimeExportText);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);

                // เริ่มตำแหน่ง หัวรายงาน
                $nStartRowHeadder = 11;
                
                /*===== หัวตารางรายงาน =========================================*/
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

                /*===== ข้อมูลรายละเอียดรายงาน ====================================*/
                // Set Variable Data
                $nStartRowData = $nStartRowHeadder + 1;

                if (isset($aDataReport['raItems']) && !empty($aDataReport['raItems'])) {
                    foreach ($aDataReport['raItems'] as $nKey => $aValue) {
    
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $nStartRowData, $aValue['rtPdtCode'])
                            ->setCellValue('B' . $nStartRowData, $aValue['rtPdtName'])
                            ->setCellValue('C' . $nStartRowData, $aValue['rtChainName'])
                            ->setCellValue('D' . $nStartRowData, $aValue['rtSaleQty'])
                            ->setCellValue('E' . $nStartRowData, $aValue['rtPdtCost'])
                            ->setCellValue('F' . $nStartRowData, $aValue['rtGrand'])
                            ->setCellValue('G' . $nStartRowData, $aValue['rtProfit'])
                            ->setCellValue('H' . $nStartRowData, $aValue['rtProfitPercent'])
                            ->setCellValue('I' . $nStartRowData, $aValue['rtSalePercent']);
    
                        $objPHPExcel->getActiveSheet()->getStyle('D' . $nStartRowData . ':I' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':C' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('D' . $nStartRowData . ':I' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $nStartRowData++;
                    }
                }else{
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':I' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tRptNoData']);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
                }

                // Step 7 : Set Footer Text
                $nPageNo    = $aDataReport['rnCurrentPage'];
                $nTotalPage = $aDataReport['rnAllPage'];

                // Step 7 : Set Footer Text
                if ($nPageNo == $nTotalPage) {
                    if (isset($aDataReport['raItems']) && !empty($aDataReport['raItems'])) {
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
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $this->aText['tRptOverall']);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
    
                        // RIGHT
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('D' . $nStartRowData, number_format($aDataSumFoot['FCXsdSaleQty_SumFooter'], 2))
                        ->setCellValue('E' . $nStartRowData, number_format($aDataSumFoot['FCPdtCost_SumFooter'], 2))
                        ->setCellValue('F' . $nStartRowData, number_format($aDataSumFoot['FCXshGrand_SumFooter'], 2))
                        ->setCellValue('G' . $nStartRowData, number_format($aDataSumFoot['FCXsdProfit_SumFooter'], 2))
                        ->setCellValue('H' . $nStartRowData, number_format($aDataSumFoot['FCXsdProfitPercent_SumFooter'], 2))
                        ->setCellValue('I' . $nStartRowData, number_format($aDataSumFoot['FCXsdSalePercent_SumFooter'], 2));
                        $objPHPExcel->getActiveSheet()->getStyle('D' . $nStartRowData . ':I' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':E' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('D' . $nStartRowData . ':I' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    
                    }  
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




















