<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'libraries/PHPExcel/Classes/PHPExcel.php';
require_once APPPATH . 'libraries/PHPExcel/Classes/PHPExcel/IOFactory.php';
require_once APPPATH . 'libraries/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

class cRptDropByDate extends MX_Controller {
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
        $this->load->helper('report');
        $this->load->model('company/company/mCompany');
        $this->load->model('report/report/mReport');
        $this->load->model('report/reportlocker/mRptDropByDate');
        // Init Report
        $this->init($paParams);
        parent::__construct();
    }

    private function init($paParams = []) {
        // Text Language
        $this->aText = [
            'tTitleReport' => language('report/report/report', 'tRptLockerDropByDateTitle'),
            'tDatePrint' => language('report/report/report', 'tRptSaleByBillDatePrint'),
            'tTimePrint' => language('report/report/report', 'tRptSaleByBillTimePrint'),
            'tRptDatePrint' => language('report/report/report', 'tRptDatePrint'),
            'tRptTaxNo' => language('report/report/report', 'tRptTaxNo'),
            'tRptDatePrint' => language('report/report/report', 'tRptDatePrint'),
            'tRptDateExport' => language('report/report/report', 'tRptDateExport'),
            'tRptTimePrint' => language('report/report/report', 'tRptTimePrint'),
            'tRptPrintHtml' => language('report/report/report', 'tRptPrintHtml'),
            'tRptBranch' => language('report/report/report', 'tRptAddrBranch'),
            'tRptFaxNo' => language('report/report/report', 'tRptAddrFax'),
            'tRptTel' => language('report/report/report', 'tRptAddrTel'),
            // Filter Heard Report
            'tRptFilterBchFrom' => language('report/report/report', 'tRptBchFrom'),
            'tRptFilterBchTo' => language('report/report/report', 'tRptBchTo'),
            'tRptFilterShopFrom' => language('report/report/report', 'tRptShopFrom'),
            'tRptFilterShopTo' => language('report/report/report', 'tRptShopTo'),
            'tRptFilterPosFrom' => language('report/report/report', 'tRptPosFrom'),
            'tRptFilterPosTo' => language('report/report/report', 'tRptPosTo'),
            'tRptFilterDateFrom' => language('report/report/report', 'tRptDateFrom'),
            'tRptFilterDateTo' => language('report/report/report', 'tRptDateTo'),
            // Table Report
            'tRptLocCode' => language('report/report/report', 'tRptLocCode'),
            'tRptDate' => language('report/report/report', 'tRptDate'),
            'tRptChannelGrp' => language('report/report/report', 'tRptChannelGrp'),
            'tRptNoChannel' => language('report/report/report', 'tRptNoChannel'),
            'tRptStatus' => language('report/report/report', 'tRptStatus'),
            'tRptReason' => language('report/report/report', 'tRptReason'),
            'tRptUSerChange' => language('report/report/report', 'tRptUSerChange'),
            'tRptChangeStaSale' => language('common/main/main', 'tCMNNotFoundData'),

            'tRptLockerDropByDateDropDate' => language('report/report/report', 'tRptLockerDropByDateDropDate'),
            'tRptCreateOnBch' => language('report/report/report', 'tRptCreateOnBch'),
            'tRptXshDocNo' => language('report/report/report', 'tRptXshDocNo'),
            'tRptTrackingNo' => language('report/report/report', 'tRptTrackingNo'),
            'tRptCabinetCode' => language('report/report/report', 'tRptCabinetCode'),
            'tRptCoditionFrom' => language('report/report/report', 'tRptCoditionFrom'),
            'tRptCoditionTo' => language('report/report/report', 'tRptCoditionTo'),
            'tRptSize' => language('report/report/report', 'tRptSize'),
            'tRptRentalRate' => language('report/report/report', 'tRptRentalRate'),
            'tRptLockerDropByDateDropTime' => language('report/report/report', 'tRptLockerDropByDateDropTime'),
            'tRptStatus' => language('report/report/report', 'tRptStatus'),
            'tRptPrice' => language('report/report/report', 'tRptPrice'),
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
            'tRptDataReportNotFound' => language('report/report/report', 'tRptDataReportNotFound'),
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
            'tShopCodeTo' => (isset($paParams['aFilter']['tShopCodeTo']) && !empty($paParams['aFilter']['tShopCodeTo'])) ? $paParams['aFilter']['tShopCodeTo'] : "",
            'tShopNameTo' => (isset($paParams['aFilter']['tShopNameTo']) && !empty($paParams['aFilter']['tShopNameTo'])) ? $paParams['aFilter']['tShopNameTo'] : "",
            // เครื่องจุดขาย(POS)
            'tPosCodeFrom' => (isset($paParams['aFilter']['tPosCodeFrom']) && !empty($paParams['aFilter']['tPosCodeFrom'])) ? $paParams['aFilter']['tPosCodeFrom'] : "",
            'tPosNameFrom' => (isset($paParams['aFilter']['tPosNameFrom']) && !empty($paParams['aFilter']['tPosNameFrom'])) ? $paParams['aFilter']['tPosNameFrom'] : "",
            'tPosCodeTo' => (isset($paParams['aFilter']['tPosCodeTo']) && !empty($paParams['aFilter']['tPosCodeTo'])) ? $paParams['aFilter']['tPosCodeTo'] : "",
            'tPosNameTo' => (isset($paParams['aFilter']['tPosNameTo']) && !empty($paParams['aFilter']['tPosNameTo'])) ? $paParams['aFilter']['tPosNameTo'] : "",
            // วันที่เอกสาร(DocNo)
            'tDocDateFrom' => (isset($paParams['aFilter']['tDocDateFrom']) && !empty($paParams['aFilter']['tDocDateFrom'])) ? $paParams['aFilter']['tDocDateFrom'] : "",
            'tDocDateTo' => (isset($paParams['aFilter']['tDocDateTo']) && !empty($paParams['aFilter']['tDocDateTo'])) ? $paParams['aFilter']['tDocDateTo'] : "",
        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID' => $this->nLngID,
            'tBchCode' => $this->tBchCodeLogin,
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
        /** ================================== Begin Init Variable ================================== */
        $tReportName = $this->aText['tTitleReport'];
        $tDateExport = date('Y-m-d');
        $tTimeExport = date('H:i:s');
        $tTextDetail = $this->aText['tDatePrint'] . ' : ' . $tDateExport . '  ' . $this->aText['tTimePrint'] . ' : ' . $tTimeExport;
        $nFile = $paParams['nFile'];
        $bIsLastFile = $paParams['bIsLastFile'];
        $tFileName = $paParams['tFileName'];
        /** ================================== End Init Variable ==================================== */

        /** =================================== Begin Get Data ====================================== */
        // ดึงข้อมูลจากฐานข้อมูล Temp
        $aGetDataReportParams = array(
            'nPerPage' => $this->nPerPage,
            'nPage' => $nFile,
            'tCompName' => $this->tCompName,
            'tRptCode' => $this->tRptCode,
            'tUserSessionID' => $this->tUserSessionID,
        );
        $aDataReport = $this->mRptDropByDate->FSaMGetDataReport($aGetDataReportParams);

        /** =================================== End Get Data ======================================== */

        try {
            if (isset($aDataReport['rtCode']) && $aDataReport['rtCode'] == '1') {
                // ตั้งค่า Font Style
                $aStyleRptFont = array('font' => array('name' => 'TH Sarabun New'));
                $aStyleRptSizeTitleName = array('font' => array('size' => 14));
                $aStyleRptSizeCompName = array('font' => array('size' => 12));
                $aStyleRptSizeAddressFont = array('font' => array('size' => 12));
                $aStyleRptHeadderTable = array('font' => array('size' => 12, 'color' => array('rgb' => '000000')));
                $aStyleRptDataTable = array('font' => array('size' => 10, 'color' => array('rgb' => '000000')));
                $aStyleAddressFont = array('font' => array('size' => 11, 'bold' => true));
                $aStyleCompFont = array('font' => array('size' => 12, 'bold' => true));

                // Initiate PHPExcel cache
                $oCacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
                $aCacheSettings = array(' memoryCacheSize ' => '8000MB', 'cacheTime' => 3600 * 120);
                PHPExcel_Settings::setCacheStorageMethod($oCacheMethod, $aCacheSettings);

                // เริ่ม phpExcel
                $objPHPExcel = new PHPExcel();

                // A4 ตั้งค่าหน้ากระดาษ
                $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

                // Set Style Font
                $objPHPExcel->getActiveSheet()->getStyle('A1:Z1000')->applyFromArray($aStyleRptFont);

                // จัดความกว้างของคอลัมน์
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);

                // ชื่อหัวรายงาน
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $tReportName);
                $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($aStyleRptSizeTitleName);

                // ชื่อ Conpany
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', $this->aCompanyInfo['FTCmpName'])->getStyle('A2')->applyFromArray($aStyleCompFont);

                if (isset($this->aCompanyInfo) && !empty($this->aCompanyInfo)) {
                    if ($this->aCompanyInfo['FTAddVersion'] == '1') { // ที่อยู่แบบแยก
                        // ที่อยู่
                        $tLabelAddressLine1 = $this->aCompanyInfo['FTAddV1No'] . ' ' . $this->aCompanyInfo['FTAddV1Village'] . ' ' . $this->aCompanyInfo['FTAddV1Road'] . ' ' . $this->aCompanyInfo['FTAddV1Soi'];
                        $tLabelAddressLine2 = $this->aCompanyInfo['FTSudName'] . ' ' . $this->aCompanyInfo['FTDstName'] . ' ' . $this->aCompanyInfo['FTPvnName'] . ' ' . $this->aCompanyInfo['FTAddV1PostCode'];
                        // ที่อยู่ บรรทัดที่ 1
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', $tLabelAddressLine1)->getStyle('A3')->applyFromArray($aStyleAddressFont);
                        // ที่อยู่ บรรทัดที่ 2
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', $tLabelAddressLine2)->getStyle('A4')->applyFromArray($aStyleAddressFont);
                    }
                    if ($this->aCompanyInfo['FTAddVersion'] == '2') { // ที่อยู่แบบรวม
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
                $tFillterColumRIGHT = "H";

                /*===== Begin Fillter =======================================================================*/
                // Fillter Branch (สาขา)
                if (!empty($this->aRptFilter['tBchCodeFrom']) && !empty($this->aRptFilter['tBchCodeTo'])) {

                    $tRptFilterFrom = $this->aText['tRptFilterBchFrom'] . ' ' . $this->aRptFilter['tBchNameFrom'];
                    $tRptFilterTo = $this->aText['tRptFilterBchTo'] . ' ' . $this->aRptFilter['tBchNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterFrom . ' ' . $tRptFilterTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()
                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;

                }

                // Fillter Shop (ร้านค้า)
                if (!empty($this->aRptFilter['tShopCodeFrom']) && !empty($this->aRptFilter['tShopCodeTo'])) {

                    $tRptFilterFrom = $this->aText['tRptFilterShopFrom'] . ' ' . $this->aRptFilter['tShopNameFrom'];
                    $tRptFilterTo = $this->aText['tRptFilterShopTo'] . ' ' . $this->aRptFilter['tShopNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterFrom . ' ' . $tRptFilterTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;
                }

                // Fillter Pos (จากเครื่องจุดขาย)
                if (!empty($this->aRptFilter['tPosCodeFrom']) && !empty($this->aRptFilter['tPosCodeTo'])) {

                    $tRptFilterFrom = $this->aText['tRptFilterPosFrom'] . ' ' . $this->aRptFilter['tPosNameFrom'];
                    $tRptFilterTo = $this->aText['tRptFilterPosTo'] . ' ' . $this->aRptFilter['tPosNameTo'];
                    $tRptTextLeftRightFilter = $tRptFilterFrom . ' ' . $tRptFilterTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;
                }

                // Fillter DocDate (วันที่สร้างเอกสาร)
                if (!empty($this->aRptFilter['tDocDateFrom']) && !empty($this->aRptFilter['tDocDateTo'])) {

                    $tRptFilterFrom = $this->aText['tRptFilterDateFrom'] . ' ' . $this->aRptFilter['tDocDateFrom'];
                    $tRptFilterTo = $this->aText['tRptFilterDateTo'] . ' ' . $this->aRptFilter['tDocDateTo'];
                    $tRptTextLeftRightFilter = $tRptFilterFrom . ' ' . $tRptFilterTo;
                    $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $nStartRowFillter += 1;
                }
                /*===== End Fillter ===========================================================================*/

                // รายละเอียดการออกรายงาน
                /* ===== ตั้งค่าวันที่เวลาออกรายงาน ======================================= */
                $nStartRowDateExport = $nStartRowFillter + 1;

                // รายละเอียดการออกรายงาน
                $tRptDateTimeExportText = $tTextDetail;
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowDateExport . ':H' . $nStartRowDateExport);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowDateExport, $tRptDateTimeExportText);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowDateExport)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowDateExport)->applyFromArray($aStyleRptSizeAddressFont);

                // เริ่มตำแหน่ง หัวรายงาน หลัก
                $nStartRowHeadder = $nStartRowDateExport + 1;

                // กำหนด Style Font ของหัวตาราง
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':H' . $nStartRowHeadder)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':H' . $nStartRowHeadder)->applyFromArray($aStyleRptHeadderTable);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':H' . $nStartRowHeadder)->applyFromArray(array(
                    'borders' => array(
                        'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                        'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                    ),
                ));

                // Set ค่าหัวตาราง หลัก
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $nStartRowHeadder, $this->aText['tRptLockerDropByDateDropDate'])
                    ->setCellValue('B' . $nStartRowHeadder, $this->aText['tRptCreateOnBch'])
                    ->setCellValue('C' . $nStartRowHeadder, $this->aText['tRptXshDocNo'])
                    ->setCellValue('D' . $nStartRowHeadder, $this->aText['tRptTrackingNo'])
                    ->setCellValue('E' . $nStartRowHeadder, $this->aText['tRptCoditionFrom'])
                    ->setCellValue('F' . $nStartRowHeadder, $this->aText['tRptCoditionTo'])
                // ->setCellValue('G' . $nStartRowHeadder, $this->aText['tRptStatus'])
                    ->setCellValue('H' . $nStartRowHeadder, $this->aText['tRptStatus']);

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F' . $nStartRowHeadder . ':G' . $nStartRowHeadder);

                // Alignment ของหัวตาราง
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':H' . $nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                // เริ่มตำแหน่ง หัวรายงาน รอง
                $nStartRowSubHeader = $nStartRowHeadder + 1;

                // กำหนด Style Font ของหัวตาราง รอง
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowSubHeader . ':H' . $nStartRowSubHeader)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowSubHeader . ':H' . $nStartRowSubHeader)->applyFromArray($aStyleRptHeadderTable);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowSubHeader . ':H' . $nStartRowSubHeader)->applyFromArray(array(
                    'borders' => array(
                        'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                        'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                    ),
                ));

                // Set ค่าหัวตาราง รอง
                $objPHPExcel->setActiveSheetIndex(0)
                // ->setCellValue('A' . $nStartRowHeadder, $this->aText['tRptLockerDropByDateDropDate'])
                // ->setCellValue('B' . $nStartRowHeadder, $this->aText['tRptCreateOnBch'])
                // ->setCellValue('C' . $nStartRowHeadder, $this->aText['tRptXshDocNo'])
                    ->setCellValue('D' . $nStartRowSubHeader, $this->aText['tRptCabinetCode'])
                    ->setCellValue('E' . $nStartRowSubHeader, $this->aText['tRptSize'])
                    ->setCellValue('F' . $nStartRowSubHeader, $this->aText['tRptRentalRate'])
                    ->setCellValue('G' . $nStartRowSubHeader, $this->aText['tRptLockerDropByDateDropTime'])
                    ->setCellValue('H' . $nStartRowSubHeader, $this->aText['tRptPrice']);

                // Alignment ของหัวตาราง รอง
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowSubHeader . ':H' . $nStartRowSubHeader)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                // Loop Data Query DataBase
                $nStartRowData = $nStartRowSubHeader + 1;
                $tDocDateld = "";
                $tBchId = "";

                foreach ($aDataReport['raItems'] as $nKey => $aValue) {
                    if ($nKey == 0) {
                        $RptDocDate = $aValue['FDXshDocDate'];
                        $RptBchName = $aValue['FTBchName'];
                    }

                    if ($tDocDateld == $aValue['FDXshDocDate']) {
                        $RptDocDate = "";
                    } else {
                        $RptDocDate = $aValue['FDXshDocDate'];
                    }

                    if ($tBchId == $aValue['FTBchName']) {
                        $RptBchName = "";
                    } else {
                        $RptBchName = $aValue['FTBchName'];
                    }

                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $nStartRowData, empty($RptDocDate) ? "" : date("Y-m-d", strtotime($RptDocDate)))
                        ->setCellValue('B' . $nStartRowData, $aValue['FTBchName'])
                        ->setCellValue('C' . $nStartRowData, $aValue['FTXshDocNo'])
                        ->setCellValue('D' . $nStartRowData, $aValue['FTXshRefExt'] . PHP_EOL . $aValue['FTPosCode'])
                        ->setCellValue('E' . $nStartRowData, $aValue['FTXshFrmLogin'] . PHP_EOL . $aValue['FTPzeName'])
                        ->setCellValue('F' . $nStartRowData, $aValue['FTXshToLogin'] . PHP_EOL . $aValue['FTRthCode'])
                        ->setCellValue('G' . $nStartRowData, PHP_EOL . $aValue['FTXsdTimeStart'])
                        ->setCellValue('H' . $nStartRowData, $aValue['FTStatus'] . PHP_EOL . number_format($aValue['FCXsdNetAfHD'], $this->nOptDecimalShow));

                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':H' . $nStartRowData)->getAlignment()->setWrapText(true);

                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':H' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

                    $tDocDateld = $aValue['FDXshDocDate'];
                    $tBchId = $aValue['FTBchName'];
                    $nStartRowData++;
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

                /*$oFiles = glob($tPathExport.'*');
                foreach($oFiles as $tFile){
                if(is_file($tFile))
                unlink($tFile);
                }*/

                // Check Status Save File Excel
                $objWriter->save($tPathExport . $tFilename);
                $aResponse = array(
                    'nStaExport' => 1,
                    'tFileName' => $tFilename,
                    'tPathFolder' => EXPORTPATH . 'exportexcel/' . $this->tRptCode . '/' . $this->tUserLoginCode . '/',
                    'tMessage' => "Export File Successfully.",
                );
            } else {
                $aResponse = array(
                    'nStaExport' => '800',
                    'tMessage' => $this->aText['tRptDataReportNotFound'],
                );
            }
        } catch (Exception $Error) {
            $aResponse = array(
                'nStaExport' => '500',
                'tMessage' => $Error->getMessage(),
            );
        }
        return $aResponse;
    }
}
