<?php defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set("Asia/Bangkok");

include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
include_once APPPATH.'libraries/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

// require_once APPPATH.'libraries/phpwkhtmltopdf/vendor/autoload.php';

// use mikehaertl\wkhtmlto\Pdf;

class cRptBestSell extends MX_Controller {

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
        $this->load->model('report/reportsale/mRptBestSell');
        
        // Init Report
        $this->init($paParams);
        
        parent::__construct ();
    }
    
    private function init($paParams = []){
        $this->aText = [
            'tTitleReport' => language('report/report/report','tTitleRptBestSell'),
            'tRptTaxNo' => language('report/report/report','tRptTaxNo'),
            'tRptDatePrint' => language('report/report/report','tRptDatePrint'),
            'tRptTimePrint' => language('report/report/report','tRptTimePrint'),
            'tRptDateExport' => language('report/report/report','tRptDateExport'),
            'tRptPrintHtml' => language('report/report/report','tRptPrintHtml'),
            'tRptBranch' => language('report/report/report','tRptBranch'),
            'tRptFaxNo' => language('report/report/report','tRptFaxNo'),
            'tRptTel' => language('report/report/report','tRptTel'),

            // Filter Heard Report
            'tRptFillterBchFrom' => language('report/report/report','tRptBchFrom'),
            'tRptFillterBchTo' => language('report/report/report','tRptBchTo'),
            'tRptFillterShopFrom' => language('report/report/report','tRptShopFrom'),
            'tRptFillterShopTo' => language('report/report/report','tRptShopTo'),
            'tRptFillterDateFrom' => language('report/report/report','tRptDateFrom'),
            'tRptFillterDateTo' => language('report/report/report','tRptDateTo'),
            'tRptFillterGrpFrom' => language('report/report/report','tRptPaymentGrpFrom'),
            'tRptFillterGrpTo' => language('report/report/report','tRptPaymentGrpTo'),
            'tRptFillterPdtTypeFrom' => language('report/report/report','tPdtTypeFrom'),
            'tRptFillterPdtTypeTo' => language('report/report/report','tPdtTypeTo'),
            'tPriority' => language('report/report/report','tPriority'),
            // Table Report
            'tRptNo' => language('report/report/report','tRowNumber'),
            'tRptPdtCode' => language('report/report/report','tRptPdtCode'),
            'tRptPdtName' => language('report/report/report','tRptPdtName'),
            'tRptPdtGroup' => language('report/report/report','tRptPdtGrp'),
            'tRptPdtQty' => language('report/report/report','tPdtQty'),
            'tRptPdtSale' => language('report/report/report','tSales'),
            'tRptDisChg' => language('report/report/report','tRptDisChg'),
            'tRptPdtTotalSal' => language('report/report/report','tTotalsales'),
            'tRptTotal' => language('report/report/report','tRptTotalSub'),
            'tRptDataReportNotFound' => language('report/report/report','tRptDataReportNotFound'),
            // Address Lang
            'tRptAddrBuilding' => language('report/report/report','tRptAddrBuilding'),
            'tRptAddrRoad' => language('report/report/report','tRptAddrRoad'),
            'tRptAddrSoi' => language('report/report/report','tRptAddrSoi'),
            'tRptAddrSubDistrict' => language('report/report/report','tRptAddrSubDistrict'),
            'tRptAddrDistrict' => language('report/report/report','tRptAddrDistrict'),
            'tRptAddrProvince' => language('report/report/report','tRptAddrProvince'),
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
        
        // Report Filter
        $this->aRptFilter = [
            // สาขา(Branch)
            'tBchCodeFrom' => (isset($paParams['aFilter']['tBchCodeFrom']) && !empty($paParams['aFilter']['tBchCodeFrom'])) ? $paParams['aFilter']['tBchCodeFrom'] : "",
            'tBchNameFrom' => (isset($paParams['aFilter']['tBchNameFrom']) && !empty($paParams['aFilter']['tBchNameFrom'])) ? $paParams['aFilter']['tBchNameFrom'] : "",
            'tBchCodeTo' => (isset($paParams['aFilter']['tBchCodeTo']) && !empty($paParams['aFilter']['tBchCodeTo'])) ? $paParams['aFilter']['tBchCodeTo'] : "",
            'tBchNameTo' => (isset($paParams['aFilter']['tBchNameTo']) && !empty($paParams['aFilter']['tBchNameTo'])) ? $paParams['aFilter']['tBchNameTo'] : "",
            // ร้านค้า(Shop)
            'tShopCodeFrom' => (isset($paParams['aFilter']['tShopCodeFrom']) && !empty($paParams['aFilter']['tShopCodeFrom'])) ? $paParams['aFilter']['tShopCodeFrom'] : "",
            'tShopNameFrom' => (isset($paParams['aFilter']['tShopNameFrom']) && !empty($paParams['aFilter']['tShopNameFrom'])) ? $paParams['aFilter']['tShopNameFrom'] : "",
            'tShopCodeTo'=> (isset($paParams['aFilter']['tShopCodeTo']) && !empty($paParams['aFilter']['tShopCodeTo'])) ? $paParams['aFilter']['tShopCodeTo'] : "",
            'tShopNameTo' => (isset($paParams['aFilter']['tShopNameTo']) && !empty($paParams['aFilter']['tShopNameTo'])) ? $paParams['aFilter']['tShopNameTo'] : "",
            // กลุ่มสินค้า(Product Group)
            'tPdtGrpCodeFrom' => (isset($paParams['aFilter']['tPdtGrpCodeFrom']) && !empty($paParams['aFilter']['tPdtGrpCodeFrom'])) ? $paParams['aFilter']['tPdtGrpCodeFrom'] : "",
            'tPdtGrpNameFrom' => (isset($paParams['aFilter']['tPdtGrpNameFrom']) && !empty($paParams['aFilter']['tPdtGrpNameFrom'])) ? $paParams['aFilter']['tPdtGrpNameFrom'] : "",
            'tPdtGrpCodeTo' => (isset($paParams['aFilter']['tPdtGrpCodeTo']) && !empty($paParams['aFilter']['tPdtGrpCodeTo'])) ? $paParams['aFilter']['tPdtGrpCodeTo'] : "",
            'tPdtGrpNameTo' => (isset($paParams['aFilter']['tPdtGrpNameTo']) && !empty($paParams['aFilter']['tPdtGrpNameTo'])) ? $paParams['aFilter']['tPdtGrpNameTo'] : "",
            // ประเภทสินค้า(Product Type)
            'tPdtTypeCodeFrom' => (isset($paParams['aFilter']['tPdtTypeCodeFrom']) && !empty($paParams['aFilter']['tPdtTypeCodeFrom'])) ? $paParams['aFilter']['tPdtTypeCodeFrom'] : "",
            'tPdtTypeNameFrom' => (isset($paParams['aFilter']['tPdtTypeNameFrom']) && !empty($paParams['aFilter']['tPdtTypeNameFrom'])) ? $paParams['aFilter']['tPdtTypeNameFrom'] : "",
            'tPdtTypeCodeTo' => (isset($paParams['aFilter']['tPdtTypeCodeTo']) && !empty($paParams['aFilter']['tPdtTypeCodeTo'])) ? $paParams['aFilter']['tPdtTypeCodeTo'] : "",
            'tPdtTypeNameTo' => (isset($paParams['aFilter']['tPdtTypeNameTo']) && !empty($paParams['aFilter']['tPdtTypeNameTo'])) ? $paParams['aFilter']['tPdtTypeNameTo'] : "",
            // วันที่เอกสาร(DocNo)
            'tDocDateFrom' => (isset($paParams['aFilter']['tDocDateFrom']) && !empty($paParams['aFilter']['tDocDateFrom'])) ? $paParams['aFilter']['tDocDateFrom'] : "",
            'tDocDateTo' => (isset($paParams['aFilter']['tDocDateTo']) && !empty($paParams['aFilter']['tDocDateTo'])) ? $paParams['aFilter']['tDocDateTo'] : "",
            // อันดับสินค้า(Top)
            'tTopPdt' => (isset($paParams['aFilter']['tTopPdt']) && !empty($paParams['aFilter']['tTopPdt'])) ? $paParams['aFilter']['tTopPdt'] : "",
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
    public function FSvCCallRptRenderExcel($paParams = []){
        /** =========== Begin Init Variable ==================================*/
        $tReportName = $this->aText['tTitleReport'];
        $dDateExport = date('Y-m-d');
        $tTime = date('H:i:s');
        $tTextDetail = $this->aText['tRptDatePrint'].' : '.$dDateExport.'  '.$this->aText['tRptTimePrint'].' : '.$tTime;
        $nFile = $paParams['nFile'];
        $bIsLastFile = $paParams['bIsLastFile'];
        $tFileName = $paParams['tFileName'];
        /** =========== End Init Variable ====================================*/
        
        
        /** =========== Begin Get Data =======================================*/
        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aGetDataReportParams = array(
            'nPerPage' => $this->nPerPage,
            'nPage' => $nFile,
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'tUserSessionID' => $this->tUserSessionID
        );
        $aDataReport = $this->mRptBestSell->FSaMGetDataReport($aGetDataReportParams);
        
        //  GetDataSumFootReport
        $aDataSumFoot = $this->mRptBestSell->FSaMGetDataSumFootReport($aGetDataReportParams);
        /** =========== End Get Data =========================================*/
        
        try{
            if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])){
                // ตั้งค่า Font Style
                $aStyleRptName = array('font' => array('size' => 14,'bold' => true,));
                $aStyleHeadder = array('font' => array('size' => 12,'bold' => true,'color' => array('rgb' => 'FFFFFF')));
                $aStyleRptHeadderTable = array('font' => array('size' => 12,'color' => array('rgb' => '000000')));
                $aStyleCompFont = array('font' => array('size' => 12,'bold' => true,));
                $aStyleAddressFont = array('font' => array('size' => 11,'bold' => true,));
                $aStyleBold = ['font' => ['size' => 11,'bold' => true,]];
                $StyleFont = array('font' => array('name' => 'TH Sarabun New'));
    
                // Initiate PHPExcel cache
                $oCacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
                $aCacheSettings = array(' memoryCacheSize ' => '8000MB', 'cacheTime' => 3600 * 120);
                PHPExcel_Settings::setCacheStorageMethod($oCacheMethod, $aCacheSettings);
                
                // เริ่ม phpExcel
                $objPHPExcel = new PHPExcel();
    
                // A4 ตั้งค่าหน้ากระดาษ
                $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

                // Set Font
                $objPHPExcel->getActiveSheet()->getStyle('A1:Z1000')->applyFromArray($StyleFont);
    
                // จัดความกว้างของคอลัมน์
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(45);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(45);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);

                // ชื่อหัวรายงาน
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $tReportName);
                $objPHPExcel->getActiveSheet()->getStyle("A1")
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($aStyleRptName);
                
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
                
                $tFillterColumLEFT  = "D";
                $tFillterColumRIGHT = "H";
                
                /*===== Begin Fillter ===========================================================================*/
                // สาขา
                if(!empty($this->aRptFilter['tBchCodeFrom']) && !empty($this->aRptFilter['tBchCodeTo'])){
                    
                    $tRptFilterFrom = $this->aText['tRptFillterBchFrom'].' '.$this->aRptFilter['tBchNameFrom'];
                    $tRptFilterTo = $this->aText['tRptFillterBchTo'].' '.$this->aRptFilter['tBchNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterFrom.' '.$tRptFilterTo;
                    $tColumsMergeFilter = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;
                    
                }
                
                // ร้านค้า
                if(!empty($this->aRptFilter['tShpCodeFrom']) && !empty($this->aRptFilter['tShpCodeTo'])){
                    
                    $tRptFilterFrom = $this->aText['tRptFillterShopFrom'].' '.$this->aRptFilter['tShpNameFrom'];
                    $tRptFilterTo = $this->aText['tRptFillterShopTo'].' '.$this->aRptFilter['tShpNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterFrom.' '.$tRptFilterTo;
                    $tColumsMergeFilter = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;
                    
                }
                
                // กลุ่มสินค้า
                if(!empty($this->aRptFilter['tPdtGrpCodeFrom']) && !empty($this->aRptFilter['tPdtGrpCodeTo'])){
                    
                    $tRptFilterFrom = $this->aText['tRptFillterGrpFrom'].' '.$this->aRptFilter['tPdtGrpNameFrom'];
                    $tRptFilterTo = $this->aText['tRptFillterGrpTo'].' '.$this->aRptFilter['tPdtGrpNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterFrom.' '.$tRptFilterTo;
                    $tColumsMergeFilter = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;
                    
                }
                
                // ประเภทสินค้า
                if(!empty($this->aRptFilter['tPdtTypeCodeFrom']) && !empty($this->aRptFilter['tPdtTypeCodeTo'])){
                    
                    $tRptFilterFrom = $this->aText['tRptFillterPdtTypeFrom'].' '.$this->aRptFilter['tPdtTypeNameFrom'];
                    $tRptFilterTo = $this->aText['tRptFillterPdtTypeTo'].' '.$this->aRptFilter['tPdtTypeNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterFrom.' '.$tRptFilterTo;
                    $tColumsMergeFilter = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;
                    
                }
                
                // วันที่สร้างเอกสาร
                if(!empty($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateTo'])){
                    
                    $tRptFilterFrom = $this->aText['tRptTaxSalePosFilterDocDateFrom'].' '.$this->aRptFilter['tDocDateFrom'];
                    $tRptFilterTo = $this->aText['tRptTaxSalePosFilterDocDateTo'].' '.$this->aRptFilter['tDocDateTo'];
                    $tRptTextLeftRightFilter = $tRptFilterFrom.' '.$tRptFilterTo;
                    $tColumsMergeFilter = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;
                    
                }
                
                // วันที่สร้างเอกสาร
                if(!empty($this->aRptFilter['tTopPdt']) && !empty($this->aRptFilter['tTopPdt'])){
                    
                    $tRptFilterFrom = $this->aText['tPriority'].' '.$this->aRptFilter['tTopPdt'];
                    $tRptTextLeftRightFilter = $tRptFilterFrom;
                    $tColumsMergeFilter = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;
                    
                }
                /*===== End Fillter ===========================================================================*/
                
                // รายละเอียดการออกรายงาน
                $nStartRowDateExport = $nStartRowFillter + 1;
                
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowDateExport.':H'.$nStartRowDateExport);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nStartRowDateExport, $tTextDetail);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowDateExport)
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                
                // เริ่มตำแหน่ง หัวรายงาน
                $nStartRowHeadder = $nStartRowDateExport + 1;
                
                // กำหนด Style Font ของหัวตาราง
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowHeadder.':H'.$nStartRowHeadder)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowHeadder.':H'.$nStartRowHeadder)->applyFromArray($aStyleRptHeadderTable);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowHeadder.':H'.$nStartRowHeadder)->applyFromArray(array(
                    'borders' => array(
                        'top'       => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')),
                        'bottom'    => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000'))
                    )
                ));
                
                // Main header
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$nStartRowHeadder, $this->aText['tRptNo'])
                ->setCellValue('B'.$nStartRowHeadder, $this->aText['tRptPdtCode'])
                ->setCellValue('C'.$nStartRowHeadder, $this->aText['tRptPdtName'])
                ->setCellValue('D'.$nStartRowHeadder, $this->aText['tRptPdtGroup'])
                ->setCellValue('E'.$nStartRowHeadder, $this->aText['tRptPdtQty'])
                ->setCellValue('F'.$nStartRowHeadder, $this->aText['tRptPdtSale'])
                ->setCellValue('G'.$nStartRowHeadder, $this->aText['tRptDisChg'])
                ->setCellValue('H'.$nStartRowHeadder, $this->aText['tRptPdtTotalSal']);
                
                // ตัวอักษร Head Center
                $objPHPExcel->getActiveSheet()->getStyle("A".$nStartRowHeadder.":H".$nStartRowHeadder)
                ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
                // Loop Data Query DataBase
                $nStartRowData = $nStartRowHeadder+1;
                $nSeq = 0;
                $nLastRowNuber = 0;
                
                foreach($aDataReport['aRptData'] AS $nKey => $aValue){
                    
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$nStartRowData.':H'.$nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$nStartRowData, $aValue['rtRowID'])
                    ->setCellValue('B'.$nStartRowData, $aValue['FTPdtCode'])
                    ->setCellValue('C'.$nStartRowData, $aValue['FTXsdPdtName'])
                    ->setCellValue('D'.$nStartRowData, $aValue['FTPgpChainName'])
                    ->setCellValue('E'.$nStartRowData, number_format(floatval($aValue['FCXsdQty']), $this->nOptDecimalShow))
                    ->setCellValue('F'.$nStartRowData, number_format(floatval($aValue['FCXsdDigChg']), $this->nOptDecimalShow))
                    ->setCellValue('G'.$nStartRowData, number_format(floatval($aValue['FCXsdDis']), $this->nOptDecimalShow))
                    ->setCellValue('H'.$nStartRowData, number_format(floatval($aValue['FCXsdNetAfHD']), $this->nOptDecimalShow));     

                    // ตัวอักษรชิดขวา
                    $objPHPExcel->getActiveSheet()->getStyle("E".$nStartRowData.":H".$nStartRowData)
                    ->getAlignment()
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    
                    // ตัวอักษร Center
                    $objPHPExcel->getActiveSheet()->getStyle("A".$nStartRowData.":A".$nStartRowData)
                    ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    
                    $nLastRowNuber = $nStartRowData;
                    $nStartRowData++;
                }
                
                if($bIsLastFile) { // Summary Last Page
                    // Set Footer Summary
                    $nEndRow = $nStartRowData;
                    $nSummaryRow = $nEndRow;
                    
                    $objPHPExcel->getActiveSheet()->getStyle('E'.$nStartRowData.':H'.$nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$nSummaryRow, '  '.$this->aText['tRptTotal'])
                    ->setCellValue('E'.$nStartRowData,number_format($aDataSumFoot['FCXsdSumQty'], $this->nOptDecimalShow))
                    ->setCellValue('F'.$nStartRowData,number_format($aDataSumFoot['FCXsdSumDigChg'], $this->nOptDecimalShow))
                    ->setCellValue('G'.$nStartRowData,number_format($aDataSumFoot['FCXsdSumDis'], $this->nOptDecimalShow))
                    ->setCellValue('H'.$nStartRowData,number_format($aDataSumFoot['FCSumFooter'], $this->nOptDecimalShow));      

                    // กำหนด Style Font Summary
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$nSummaryRow.':H'.$nSummaryRow)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$nSummaryRow.':H'.$nSummaryRow)->applyFromArray($aStyleRptHeadderTable);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$nSummaryRow.':H'.$nSummaryRow)->applyFromArray(array(
                        'borders' => array(
                            'top'       => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')),
                            'bottom'    => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000'))
                        )
                    ));
                    
                    // จัดตัวอักษรชิดขวา
                    $objPHPExcel->getActiveSheet()->getStyle("E".$nSummaryRow . ':H'.$nSummaryRow)
                    ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    
                    // ตัวอักษร Center
                    $objPHPExcel->getActiveSheet()->getStyle("A".$nSummaryRow.":A".$nSummaryRow)
                    ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                }
                
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
                
            }else {
                $aResponse =  array(
                    'nStaExport' => '800',
                    'tMessage' => $this->aText['tRptDataReportNotFound']
                );
            }
        }catch(Exception $Error){
            $aResponse =  array(
                'nStaExport' => '500',
                'tMessage' => $Error->getMessage()
            );
        }
        return $aResponse;
    }
    
}





































































