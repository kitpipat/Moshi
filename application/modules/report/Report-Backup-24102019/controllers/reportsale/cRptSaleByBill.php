<?php

defined('BASEPATH') or exit('No direct script access allowed');

include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

class cRptSaleByBill extends MX_Controller {

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
     * Report Group
     * @var string
     */
    public $tRptGroup;

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

    public function __construct(){
        $this->load->model('company/company/mCompany');
        $this->load->model('report/report/mReport');
        $this->load->model('report/reportsale/mRptSaleByBill');
        // Init Report
        $this->init();
        parent::__construct();
    }

    private function init(){
        $this->aText = [
            'tTitleReport' => language('report/report/report', 'tRptTitleRptSaleByBill'),
            'tRptTaxNo' => language('report/report/report', 'tRptTaxNo'),
            'tRptDatePrint' => language('report/report/report', 'tRptDatePrint'),
            'tRptDateExport' => language('report/report/report', 'tRptDateExport'),
            'tRptTimePrint' => language('report/report/report', 'tRptTimePrint'),
            'tRptPrintHtml' => language('report/report/report', 'tRptPrintHtml'),
            'tRptBranch' => language('report/report/report', 'tRptAddrBranch'),
            'tRptFaxNo' => language('report/report/report', 'tRptAddrFax'),
            'tRptTel' => language('report/report/report', 'tRptAddrTel'),
            // Filter Heard Report
            'tRptBchFrom' => language('report/report/report', 'tRptBchFrom'),
            'tRptBchTo' => language('report/report/report', 'tRptBchTo'),
            'tRptShopFrom' => language('report/report/report', 'tRptShopFrom'),
            'tRptShopTo' => language('report/report/report', 'tRptShopTo'),
            'tRptDateFrom' => language('report/report/report', 'tRptDateFrom'),
            'tRptDateTo' => language('report/report/report', 'tRptDateTo'),
            // Table Report
            'tRptDocBill' => language('report/report/report', 'tRptDocBill'),
            'tRptDate' => language('report/report/report', 'tRptDate'),
            'tRptSales' => language('report/report/report', 'tRptSales'),
            'tRptDisChg' => language('report/report/report', 'tRptDisChg'),
            'tRptTax' => language('report/report/report', 'tRptTax'),
            'tRptGrand' => language('report/report/report', 'tRptGrand'),
            'tRptOverall' => language('report/report/report', 'tRptOverall')
        ];

        $this->tBchCodeLogin    = (!empty($this->session->userdata('tSesUsrBchCom')) ? $this->session->userdata('tSesUsrBchCom') : $this->session->userdata('tSesUsrBchCom'));
        $this->nPerPage         = 100;
        $this->nOptDecimalShow  = FCNxHGetOptionDecimalShow();

        $tIP                    = $this->input->ip_address();
        $tFullHost              = gethostbyaddr($tIP);
        $this->tCompName        = $tFullHost;

        $this->nLngID           = FCNaHGetLangEdit();
        $this->tRptCode         = $this->input->post('ohdRptCode');
        $this->tRptGroup        = $this->input->post('ohdRptGrpCode');
        $this->tUserSessionID   = $this->session->userdata('tSesSessionID');
        $this->tRptRoute        = $this->input->post('ohdRptRoute');
        $this->tRptExportType   = $this->input->post('ohdRptTypeExport');
        $this->nPage            = empty($this->input->post('ohdRptCurrentPage')) ? 1 : $this->input->post('ohdRptCurrentPage');
        $this->tUserLoginCode   = $this->session->userdata('tSesUsername');

        // Report Filter
        $this->aRptFilter = [
            'tUserSession'  => $this->tUserSessionID,
            'tCompName'     => $tFullHost,
            'tRptCode'      => $this->tRptCode,
            'nLangID'       => $this->nLngID,
            // สาขา(Branch)
            'tBchCodeFrom'  => !empty($this->input->post('oetRptBchCodeFrom')) ? $this->input->post('oetRptBchCodeFrom') : "",
            'tBchNameFrom'  => !empty($this->input->post('oetRptBchNameFrom')) ? $this->input->post('oetRptBchNameFrom') : "",
            'tBchCodeTo'    => !empty($this->input->post('oetRptBchCodeTo')) ? $this->input->post('oetRptBchCodeTo') : "",
            'tBchNameTo'    => !empty($this->input->post('oetRptBchNameTo')) ? $this->input->post('oetRptBchNameTo') : "",
            // ร้านค้า(Shop)
            'tShpCodeFrom'  => !empty($this->input->post('oetRptShpCodeFrom')) ? $this->input->post('oetRptShpCodeFrom') : "",
            'tShpNameFrom'  => !empty($this->input->post('oetRptShpNameFrom')) ? $this->input->post('oetRptShpNameFrom') : "",
            'tShpCodeTo'    => !empty($this->input->post('oetRptShpCodeTo')) ? $this->input->post('oetRptShpCodeTo') : "",
            'tShpNameTo'    => !empty($this->input->post('oetRptShpNameTo')) ? $this->input->post('oetRptShpNameTo') : "",
            // วันที่เอกสาร(DocNo)
            'tDocDateFrom'  => !empty($this->input->post('oetRptDocDateFrom')) ? $this->input->post('oetRptDocDateFrom') : "",
            'tDocDateTo'    => !empty($this->input->post('oetRptDocDateTo')) ? $this->input->post('oetRptDocDateTo') : "",
        ];

        // ดึงข้อมูลบริษัทฯ
        $aCompInfoParams = [
            'nLngID' => $this->nLngID,
            'tBchCode' => $this->tBchCodeLogin
        ];
        $this->aCompanyInfo = FCNaGetCompanyInfo($aCompInfoParams)['raItems'];
    }

    public function index()
    {

        if (!empty($this->tRptExportType) && !empty($this->tRptCode)) {

            // Execute Stored Procedure
            $this->mRptSaleByBill->FSnMExecStoreCReport($this->aRptFilter);

            $aDataSwitchCase = array(
                'ptRptRoute' => $this->tRptRoute,
                'ptRptCode' => $this->tRptCode,
                'ptRptTypeExport' => $this->tRptExportType,
                'paDataFilter' => $this->aRptFilter
            );

            switch ($this->tRptExportType) {
                case 'html':
                    $this->FSvCCallRptViewBeforePrint($aDataSwitchCase);
                    break;
                case 'excel':
                    $this->FSoCChkDataReportInTableTemp($aDataSwitchCase);
                    break;
            }
        }
    }

    /**
     * Functionality: ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 22/07/2019 Wasin(Yoshi)
     * LastUpdate: 23/09/2019 Piya
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrint($paDataSwitchCase)
    {

        $aDataWhere = array(
            'tUserSession' => $this->tUserSessionID,
            'tCompName' => $this->tCompName,
            'tUserCode' => $this->tUserLoginCode,
            'tRptCode' => $this->tRptCode,
            'nPage' => 1, // เริ่มทำงานหน้าแรก
            'nRow' => $this->nPerPage,
        );

        // Get Data ReportFSaMGetDataReport
        $aDataReport = $this->mRptSaleByBill->FSaMGetDataReport($aDataWhere, $this->aRptFilter);

        // GetDataSumFootReport
        $aDataSumFoot = $this->mRptSaleByBill->FSaMGetDataSumFootReport($aDataWhere, $this->aRptFilter);

        // Call View Report
        $tViewRenderKool = $this->FCNvCRenderKoolReportHtml($aDataReport, $aDataSumFoot, $this->aRptFilter);

        $aDataView = array(
            'aCompanyInfo' => $this->aCompanyInfo,
            'tTitleReport' => $this->aText['tTitleReport'],
            'tRptTypeExport' => $this->tRptExportType,
            'tRptCode' => $this->tRptCode,
            'tRptRoute' => $this->tRptRoute,
            'tViewRenderKool' => $tViewRenderKool,
            'aDataFilter' => $this->aRptFilter,
            'aDataReport' => $aDataReport,
            'aDataSumFoot' => $aDataSumFoot
        );

        $this->load->view('report/report/wReportViewer', $aDataView);
    }

    /**
     * Functionality: Call Rpt Table Kool Report
     * Parameters:  Function Parameter
     * Creator: 04/04/2019 Wasin(Yoshi)
     * LastUpdate: 23/09/2019 Piya
     * Return: View Kool Report
     * ReturnType: View
     */
    public function FCNvCRenderKoolReportHtml($paDataReport, $paDataSumFoot, $paDataFilter)
    {

        // Ref File Kool Report
        require_once APPPATH . 'modules\report\datasources\rptSaleByBill\rRptSaleByBill.php';

        // Set Parameter To Report
        $oRptSaleByBill = new rRptSaleByBill(array(
            'nCurrentPage' => $paDataReport['rnCurrentPage'],
            'nAllPage' => $paDataReport['rnAllPage'],
            'aCompanyInfo' => $this->aCompanyInfo,
            'nOptDecimalShow' => $this->nOptDecimalShow,
            'aFilterReport' => $paDataFilter,
            'aDataTextRef' => $this->aText,
            'aDataReturn' => $paDataReport,
            'aDataSumFoot' => $paDataSumFoot
        ));


        $oRptSaleByBill->run();
        $tHtmlViewReport = $oRptSaleByBill->render('wRptSaleByBillHtml', true);
        return $tHtmlViewReport;
    }

    /**
     * Functionality: Click Page Report (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 19/04/2019 Wasin(Yoshi)
     * LastUpdate: 23/09/2019 Piya
     * Return: View Report Viewer
     * ReturnType: View
     */
    public function FSvCCallRptViewBeforePrintClickPage()
    {

        /** =========== Begin Init Variable ================================== */
        $aDataFilter = json_decode($this->input->post('ohdRptDataFilter'), true);
        /** =========== End Init Variable ==================================== */
        $aDataWhere = array(
            'tUserSession' => $this->tUserSessionID,
            'tCompName' => $this->tCompName,
            'tUserCode' => $this->tUserLoginCode,
            'tRptCode' => $this->tRptCode,
            'nPage' => $this->nPage,
            'nRow' => $this->nPerPage,
        );

        // Get Data ReportFSaMGetDataReport
        $aDataReport = $this->mRptSaleByBill->FSaMGetDataReport($aDataWhere, $aDataFilter);

        // GetDataSumFootReport
        $aDataSumFoot = $this->mRptSaleByBill->FSaMGetDataSumFootReport($aDataWhere, $aDataFilter);

        if (!empty($aDataReport['rtCode']) && $aDataReport['rtCode'] == 1) {
            $tViewRenderKool = $this->FCNvCRenderKoolReportHtml($aDataReport, $aDataSumFoot, $aDataFilter);
        } else {
            $tViewRenderKool = "";
        }

        $aDataView = array(
            'tTitleReport' => $this->aText['tTitleReport'],
            'tRptTypeExport' => $this->tRptExportType,
            'tRptCode' => $this->tRptCode,
            'tRptRoute' => $this->tRptRoute,
            'tViewRenderKool' => $tViewRenderKool,
            'aDataFilter' => $aDataFilter,
            'aDataReport' => $aDataReport,
            'aDataSumFoot' => $aDataSumFoot
        );

        $this->load->view('report/report/wReportViewer', $aDataView);
    }

    /**
     * Functionality: Click Page Report (Report Viewer)
     * Parameters:  Function Parameter
     * Creator: 16/08/2019 Wasin(Yoshi)
     * LastUpdate: 23/09/2019 Piya
     * Return: object Status Count Data Report
     * ReturnType: Object
     */
    public function FSoCChkDataReportInTableTemp($paDataSwitchCase)
    {
        try {
            $aDataCountData = [
                'tCompName' => $paDataSwitchCase['paDataFilter']['tCompName'],
                'tRptCode' => $paDataSwitchCase['paDataFilter']['tRptCode'],
                'tUserSession' => $paDataSwitchCase['paDataFilter']['tUserSession'],
            ];

            $nDataCountPage = $this->mRptSaleByBill->FSnMCountDataReportAll($aDataCountData);

            $aResponse = array(
                'nCountPageAll' => $nDataCountPage,
                'nStaEvent' => 1,
                'tMessage' => 'Success Count Data All'
            );
        } catch (ErrorException $Error) {
            $aResponse = array(
                'nStaEvent' => 500,
                'tMessage' => $Error->getMessage()
            );
        }
        echo json_encode($aResponse);
    }

    /**
     * Functionality: Send Rabbit MQ Report
     * Parameters:  Function Parameter
     * Creator: 16/08/2019 Wasin(Yoshi)
     * LastUpdate: 23/09/2019 Piya
     * Return: object Send Rabbit MQ Report
     * ReturnType: Object
     */
    public function FSvCCallRptExportFile()
    {
        try {
            $dDateSendMQ = date('Y-m-d');
            $dTimeSendMQ = date('H:i:s');
            $dDateSubscribe = date('Ymd');
            $dTimeSubscribe = date('His');

            // Set Parameter Send MQ
            $tRptQueueName = 'RPT_' . $this->tRptGroup . '_' . $this->tRptCode;

            $aDataSendMQ = [
                'tQueueName' => $tRptQueueName,
                'aParams' => [
                    'ptRptCode' => $this->tRptCode,
                    'pnPerFile' => 20000,
                    'ptUserCode' => $this->tUserLoginCode,
                    'ptUserSessionID' => $this->tUserSessionID,
                    'pnLngID' => $this->nLngID,
                    'ptFilter' => $this->aRptFilter,
                    'ptRptExpType' => $this->tRptExportType,
                    'ptComName' => $this->tCompName,
                    'ptDate' => $dDateSendMQ,
                    'ptTime' => $dTimeSendMQ,
                    'ptBchCode' => (!empty($this->session->userdata('tSesUsrBchCom')) ? $this->session->userdata('tSesUsrBchCom') : $this->session->userdata('tSesUsrBchCom'))
                ]
            ];
            FCNxReportCallRabbitMQ($aDataSendMQ);

            $aResponse = array(
                'nStaEvent' => 1,
                'tMessage' => 'Success Send Rabbit MQ.',
                'aDataSubscribe' => array(
                    'ptComName' => $this->tCompName,
                    'ptRptCode' => $this->tRptCode,
                    'ptUserCode' => $this->tUserLoginCode,
                    'ptUserSessionID' => $this->tUserSessionID,
                    'pdDateSubscribe' => $dDateSubscribe,
                    'pdTimeSubscribe' => $dTimeSubscribe,
                )
            );
        } catch (Exception $Error) {
            $aResponse = array(
                'nStaEvent' => 500,
                'tMessage' => $Error->getMessage()
            );
        }
        echo json_encode($aResponse);
    }
}
