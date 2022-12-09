<?php

defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set("Asia/Bangkok");

include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';
include APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

class cRptInventory extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('company/company/mCompany');
        $this->load->model('report/reportInventory/mRptInventory');
        $this->load->model('report/report/mReport');
    }

    public function index() {
        $tRptRoute      = $this->input->post('ohdRptRoute');
        $tRptCode       = $this->input->Post('ohdRptCode');
        $tRptTypeExport = $this->input->post('ohdRptTypeExport');

        $tMerCodeF      = $this->input->post('oetRptMerCodeFrom');
        $tMerCodeT      = $this->input->post('oetRptMerCodeTo');
        if ($tMerCodeF != "" && $tMerCodeT != "") {
            $tMerCodeFrom = $this->input->post('oetRptMerCodeFrom');
            $tMerCodeTo     = $this->input->post('oetRptMerCodeTo');
        } else {
            $tMerCodeFrom   = "";
            $tMerCodeTo     = "";
        }

        $tIP = $this->input->ip_address();
        $tFullHost = gethostbyaddr($tIP);
        $this->tCompName = $tFullHost;

        if (!empty($tRptTypeExport) && !empty($tRptCode)) {
            $aDataFilter = array(
                'tSessionID'    => $this->session->userdata('tSesSessionID'),
                'tCompName'     => $tFullHost,
                'tRptCode'      => $this->input->post('ohdRptCode'),
                'tMerCodeFrom'  => $tMerCodeFrom,
                'tMerCodeTo'    => $tMerCodeTo,
                'tMerNameFrom'  => !empty($this->input->post('oetRptMerNameFrom')) ? $this->input->post('oetRptMerNameFrom') : "",
                'tMerNameTo'    => !empty($this->input->post('oetRptMerNameTo')) ? $this->input->post('oetRptMerNameTo') : "",
                'tWahCodeFrom'  => !empty($this->input->post('oetRptWahCodeFrom')) ? $this->input->post('oetRptWahCodeFrom') : "",
                'tWahCodeTo'    => !empty($this->input->post('oetRptWahCodeTo')) ? $this->input->post('oetRptWahCodeTo') : "",
                'tWahNameFrom'  => !empty($this->input->post('oetRptWahNameFrom')) ? $this->input->post('oetRptWahNameFrom') : "",
                'tWahNameTo'    => !empty($this->input->post('oetRptWahNameTo')) ? $this->input->post('oetRptWahNameTo') : "",
                'tShpCodeFrom'  => !empty($this->input->post('oetRptShpCodeFrom')) ? $this->input->post('oetRptShpCodeFrom') : "",
                'tShpCodeTo'    => !empty($this->input->post('oetRptShpCodeTo')) ? $this->input->post('oetRptShpCodeTo') : "",
                'tShpNameFrom'  => !empty($this->input->post('oetRptShpNameFrom')) ? $this->input->post('oetRptShpNameFrom') : "",
                'tShpNameTo'    => !empty($this->input->post('oetRptShpNameTo')) ? $this->input->post('oetRptShpNameTo') : "",
                'tPosCodeFrom'  => !empty($this->input->post('oetRptPosCodeFrom')) ? $this->input->post('oetRptPosCodeFrom') : "",
                'tPosCodeTo'    => !empty($this->input->post('oetRptPosCodeTo')) ? $this->input->post('oetRptPosCodeTo') : "",
                'tPosNameFrom'  => !empty($this->input->post('oetRptPosNameFrom')) ? $this->input->post('oetRptPosNameFrom') : "",
                'tPosNameTo'    => !empty($this->input->post('oetRptPosNameTo')) ? $this->input->post('oetRptPosNameTo') : "",
                'tDocDateFrom'  => !empty($this->input->post('oetRptDocDateFrom')) ? $this->input->post('oetRptDocDateFrom') : "",
                'tDocDateTo'    => !empty($this->input->post('oetRptDocDateTo')) ? $this->input->post('oetRptDocDateTo') : "",
                'nLangID'       => FCNaHGetLangEdit(),
            );

            // Execute Stored Procedure
            $aResult = $this->mRptInventory->FSnMExecStoreCReport($aDataFilter);

            $aDataSwitchCase = array(
                'ptRptRoute'        => $tRptRoute,
                'ptRptCode'         => $tRptCode,
                'ptRptTypeExport'   => $tRptTypeExport,
                'paDataFilter'      => $aDataFilter
            );
            switch ($tRptTypeExport) {
                case 'html':
                    $this->FSvCCallRptViewBeforePrint($aDataSwitchCase);
                    break;
                case 'excel':
                    $this->FSoCChkDataReportInTableTemp($aDataSwitchCase);
                    break;
                case 'pdf':
                    $this->FSvCCallRptRenderExcel($aDataSwitchCase);
                    break;
            }
        }
    }

    // Functionality: ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
    // Parameters:  Function Parameter
    // Creator: 15/07/2019 Wasin(Yoshi)
    // LastUpdate: 22/07/2019 saharat(Golf)
    // Return: View Report Viewersd
    // ReturnType: View
    public function FSvCCallRptViewBeforePrint($paDataSwitchCase) {
        $tRptRoute      = $paDataSwitchCase['ptRptRoute'];
        $tRptCode       = $paDataSwitchCase['ptRptCode'];
        $tRptTypeExport = $paDataSwitchCase['ptRptTypeExport'];
        $aDataFilter    = $paDataSwitchCase['paDataFilter'];
        $nPage          = 1;
        $nLangEdit = $this->session->userdata("tLangEdit");

        // Get Data Company (ดึงข้อมูลอ้างอิงที่อยู่บริษัท)
        $tAPIReq        = "";
        $tMethodReq     = "GET";
        $aDataWhereComp = array('FNLngID' => $nLangEdit);
        $aCompData      = $this->mCompany->FSaMCMPList($tAPIReq, $tMethodReq, $aDataWhereComp);
        if ($aCompData['rtCode'] == '1') {
            $tCompName  = $aCompData['raItems']['rtCmpName'];
            $tBchCode   = $aCompData['raItems']['rtCmpBchCode'];
            $aDataBranchAddress = $this->mReport->FSaMRptGetDataAddressByBranchComp($tBchCode, $nLangEdit);
        } else {
            $tCompName  = "-";
            $tBchCode   = "-";
            $aDataBranchAddress = array();
        }

        // array ข้อมูลภาษาที่เกี่ยวข้องกับรายงาน
        $aDataTextRef = array(
            'tTitleCompName'    => $tCompName,
            'tTitleReport'      => language('report/report/report', 'tRptTitleInventory'),
            'tDatePrint'        => language('report/report/report', 'tRptAdjStkVDDatePrint'),
            'tTimePrint'        => language('report/report/report', 'tRptAdjStkVDTimePrint'),
            // Address Lang
            'tRptAddrBuilding'  => language('report/report/report', 'tRptAddrBuilding'),
            'tRptAddrRoad'      => language('report/report/report', 'tRptAddrRoad'),
            'tRptAddrSoi'       => language('report/report/report', 'tRptAddrSoi'),
            'tRptAddrSubDistrict' => language('report/report/report', 'tRptAddrSubDistrict'),
            'tRptAddrDistrict'  => language('report/report/report', 'tRptAddrDistrict'),
            'tRptAddrProvince'  => language('report/report/report', 'tRptAddrProvince'),
            'tRptAddrTel'       => language('report/report/report', 'tRptAddrTel'),
            'tRptAddrFax'       => language('report/report/report', 'tRptAddrFax'),
            'tRptAddrBranch'    => language('report/report/report', 'tRptAddrBranch'),
            'tRptAddV2Desc1'    => language('report/report/report', 'tRptAddV2Desc1'),
            'tRptAddV2Desc2'    => language('report/report/report', 'tRptAddV2Desc2'),
            // Table Label
            'tRptDocument'      => language('report/report/report', 'tRptDocument'),
            'tRptDateDocument'  => language('report/report/report', 'tRptDateDocument'),
            'tRptFromWareHouse' => language('report/report/report', 'tRptFromWareHouse'),
            'tRptToWareHouse'   => language('report/report/report', 'tRptToWareHouse'),
            'tRptAdjStkVDPdtCode' => language('report/report/report', 'tRptAdjStkVDPdtCode'),
            'tRptAdjStkVDPdtName' => language('report/report/report', 'tRptAdjStkVDPdtName'),
            'tRptAdjStkVDLayRow'  => language('report/report/report', 'tRptAdjStkVDLayRow'),
            'tRptAdjStkVDLayCol'  => language('report/report/report', 'tRptAdjStkVDLayCol'),
            'tRptAdjStkVDLayCol'  => language('report/report/report', 'tRptAdjStkVDLayCol'),
            'tRptTransferamount'  => language('report/report/report', 'tRptTransferamount'),
            'tRptListener'        => language('report/report/report', 'tRptListener'),
            'tRptFooterSumAll'    => language('report/report/report', 'tRptFooterSumAll'),
            'tRptTotal'           => language('report/report/report', 'tRptTotal'),
            'tRptAdjStkVDTotalSub' => language('report/report/report', 'tRptRentAmtForDetailSumText'),
            'tRptAdjStkVDTotalFooter' => language('report/report/report', 'tRptRentAmtForDetailSumTextLast'),
            // No Data Report
            'tRptAdjStkNoData'   => language('common/main/main', 'tCMNNotFoundData'),
            // Data Address Branch
            'aDataBranchAddress' => $aDataBranchAddress
        );

        //Get Full HostName
        $tIP = $this->input->ip_address();
        $tFullHost = gethostbyaddr($tIP);
        $this->tCompName = $tFullHost;


        // ข้อมูลสำหรับดึงข้อมูลจากฐานข้อมูล
        $aDataWhereRpt = array(
            'nPerPage'  => 100,
            'nPage'     => $nPage,
            'tCompName' => $tFullHost,
            'tRptCode'  => $tRptCode,
            'tUsrSessionID' => $this->session->userdata('tSesSessionID'),
        );
        $aDataReport = $this->mRptInventory->FSaMGetDataReport($aDataWhereRpt);

        // ข้อมูล Render Report
        $aDataViewPdt = array(
            'aDataReport'   => $aDataReport,
            'aDataTextRef'  => $aDataTextRef,
            'aDataCompany'  => $aCompData,
            'aDataFilter'   => $paDataSwitchCase['paDataFilter']
        );

        // Load View Advance Table
        $tViewTest = JCNoHLoadViewAdvanceTable('report/datasources/rptInventory', 'wRptInventoryHtml', $aDataViewPdt);

        // Data Viewer Center Report
        $aDataViewer = array(
            'tTitleReport'      => $aDataTextRef['tTitleReport'],
            'tRptTypeExport'    => $tRptTypeExport,
            'tRptCode'          => $tRptCode,
            'tRptRoute'         => $tRptRoute,
            'tViewRenderKool'   => $tViewTest,
            'aDataFilter'       => $paDataSwitchCase['paDataFilter'],
            'aDataReport' => array(
                'raItems'    => $aDataReport['aRptData'],
                'rnAllRow'   => $aDataReport['aPagination']['nTotalRecord'],
                'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                'rnAllPage'  => $aDataReport['aPagination']['nTotalPage'],
                'rtCode'     => '1',
                'rtDesc'     => 'success',
            )
        );
        $this->load->view('report/report/wReportViewer', $aDataViewer);
    }

    // Functionality: Click Page ดูตัวอย่างก่อนพิมพ์ (Report Viewer)
    // Parameters:  Function Parameter
    // Creator: 19/07/2019 Wasin(Yoshi)
    // LastUpdate: 22/04/2019 saharat(Golf)
    // Return: View Report Viewer
    // ReturnType: View
    public function FSvCCallRptViewBeforePrintClickPage() {
        $tRptRoute      = $this->input->post('ohdRptRoute');
        $tRptCode       = $this->input->post('ohdRptCode');
        $tRptTypeExport = $this->input->post('ohdRptTypeExport');
        $aDataFilter    = json_decode($this->input->post('ohdRptDataFilter'), true);
        $nPage          = $this->input->post('ohdRptCurrentPage');
        $nLangEdit      = $this->session->userdata("tLangEdit");
        
        // Get Data Company (ดึงข้อมูลอ้างอิงที่อยู่บริษัท)
        $tAPIReq        = "";
        $tMethodReq     = "GET";
        $aDataWhereComp = array('FNLngID' => $nLangEdit);
        $aCompData      = $this->mCompany->FSaMCMPList($tAPIReq, $tMethodReq, $aDataWhereComp);
        if ($aCompData['rtCode'] == '1') {
            $tCompName  = $aCompData['raItems']['rtCmpName'];
            $tBchCode   = $aCompData['raItems']['rtCmpBchCode'];
            $aDataBranchAddress = $this->mReport->FSaMRptGetDataAddressByBranchComp($tBchCode, $nLangEdit);
        } else {
            $tCompName  = "-";
            $tBchCode   = "-";
            $aDataBranchAddress = array();
        }

        // array ข้อมูลภาษาที่เกี่ยวข้องกับรายงาน
        $aDataTextRef = array(
            'tTitleCompName'    => $tCompName,
            'tTitleReport'      => language('report/report/report', 'tRptTitleInventory'),
            'tDatePrint'        => language('report/report/report', 'tRptAdjStkVDDatePrint'),
            'tTimePrint'        => language('report/report/report', 'tRptAdjStkVDTimePrint'),
            // Address Lang
            'tRptAddrBuilding'  => language('report/report/report', 'tRptAddrBuilding'),
            'tRptAddrRoad'      => language('report/report/report', 'tRptAddrRoad'),
            'tRptAddrSoi'       => language('report/report/report', 'tRptAddrSoi'),
            'tRptAddrSubDistrict' => language('report/report/report', 'tRptAddrSubDistrict'),
            'tRptAddrDistrict'  => language('report/report/report', 'tRptAddrDistrict'),
            'tRptAddrProvince'  => language('report/report/report', 'tRptAddrProvince'),
            'tRptAddrTel'       => language('report/report/report', 'tRptAddrTel'),
            'tRptAddrFax'       => language('report/report/report', 'tRptAddrFax'),
            'tRptAddrBranch'    => language('report/report/report', 'tRptAddrBranch'),
            'tRptAddV2Desc1'    => language('report/report/report', 'tRptAddV2Desc1'),
            'tRptAddV2Desc2'    => language('report/report/report', 'tRptAddV2Desc2'),
            // Table Label
            'tRptDocument'      => language('report/report/report', 'tRptDocument'),
            'tRptDateDocument'  => language('report/report/report', 'tRptDateDocument'),
            'tRptFromWareHouse' => language('report/report/report', 'tRptFromWareHouse'),
            'tRptToWareHouse'   => language('report/report/report', 'tRptToWareHouse'),
            'tRptAdjStkVDPdtCode' => language('report/report/report', 'tRptAdjStkVDPdtCode'),
            'tRptAdjStkVDPdtName' => language('report/report/report', 'tRptAdjStkVDPdtName'),
            'tRptAdjStkVDLayRow'  => language('report/report/report', 'tRptAdjStkVDLayRow'),
            'tRptAdjStkVDLayCol'  => language('report/report/report', 'tRptAdjStkVDLayCol'),
            'tRptAdjStkVDLayCol'  => language('report/report/report', 'tRptAdjStkVDLayCol'),
            'tRptTransferamount'  => language('report/report/report', 'tRptTransferamount'),
            'tRptListener'        => language('report/report/report', 'tRptListener'),
            'tRptFooterSumAll'    => language('report/report/report', 'tRptFooterSumAll'),
            'tRptTotal'           => language('report/report/report', 'tRptTotal'),
            'tRptAdjStkVDTotalSub' => language('report/report/report', 'tRptRentAmtForDetailSumText'),
            'tRptAdjStkVDTotalFooter' => language('report/report/report', 'tRptRentAmtForDetailSumTextLast'),
            // No Data Report
            'tRptAdjStkNoData'   => language('common/main/main', 'tCMNNotFoundData'),
            // Data Address Branch
            'aDataBranchAddress' => $aDataBranchAddress
        );

        $tIP = $this->input->ip_address();
        $tFullHost = gethostbyaddr($tIP);
        $this->tCompName = $tFullHost;

        // ข้อมูลสำหรับดึงข้อมูลจากฐานข้อมูล
        $aDataWhereRpt = array(
            'nPerPage'  => 100,
            'nPage'     => $nPage,
            'tCompName' => $tFullHost,
            'tRptCode'  => $tRptCode,
            'tUsrSessionID' => $this->session->userdata('tSesSessionID'),
        );
        $aDataReport = $this->mRptInventory->FSaMGetDataReport($aDataWhereRpt);

        // ข้อมูล Render Report
        $aDataViewPdt = array(
            'aDataReport'   => $aDataReport,
            'aDataTextRef'  => $aDataTextRef,
            'aDataCompany'  => $aCompData,
            'aDataFilter'   => $aDataFilter
        );

        
        // Load View Advance Table
        $tViewTest = JCNoHLoadViewAdvanceTable('report/datasources/rptInventory', 'wRptInventoryHtml', $aDataViewPdt);
        
        // Data Viewer Center Report
        $aDataViewer = array(
            'tTitleReport'      => $aDataTextRef['tTitleReport'],
            'tRptTypeExport'    => $tRptTypeExport,
            'tRptCode'          => $tRptCode,
            'tRptRoute'         => $tRptRoute,
            'tViewRenderKool'   => $tViewTest,
            'aDataFilter'       => $aDataFilter['paDataFilter'],
            'aDataReport' => array(
                'raItems'    => $aDataReport['aRptData'],
                'rnAllRow'   => $aDataReport['aPagination']['nTotalRecord'],
                'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                'rnAllPage'  => $aDataReport['aPagination']['nTotalPage'],
                'rtCode'     => '1',
                'rtDesc'     => 'success',
            )
        );
        $this->load->view('report/report/wReportViewer', $aDataViewer);
    }

    // Functionality: Function Render Report Excel
    // Parameters:  Function Parameter
    // Creator: 06/08/2019 Wasin(Yoshi)
    // LastUpdate: -
    // Return: View Report Viewer
    // ReturnType: View
    private function FSvCCallRptRenderExcel($paDataMain) {
        try {
            // เตรียมข้อมูลออกรายงาน =================================================================================================================================================
            $tRptRoute      = $paDataMain['ptRptRoute'];
            $tRptCode       = $paDataMain['ptRptCode'];
            $tRptTypeExport = $paDataMain['ptRptTypeExport'];
            $aDataFilter    = $paDataMain['paDataFilter'];
            $nPage = 1;
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $tSesUsername   = $this->session->userdata('tSesUsername');
            $tUsrSessionID  = $this->session->userdata('tSesSessionID');
            $dDatePrint     = date('Y-m-d');
            $dTimePrint     = date('H:i:s');


            // Check Filter Merchant And 
            $tAPIReq = "";
            $tMethodReq = "GET";
            $aDataWhereComp = array('FNLngID' => $nLangEdit);
            $aCompData = $this->mCompany->FSaMCMPList($tAPIReq, $tMethodReq, $aDataWhereComp);
            if ($aCompData['rtCode'] == '1') {
                $tCompName  = $aCompData['raItems']['rtCmpName'];
                $tBchCode  = $aCompData['raItems']['rtCmpBchCode'];
                $aDataAddress = $this->mReport->FSaMRptGetDataAddressByBranchComp($tBchCode, $nLangEdit);
            } else {
                $tCompName = "-";
                $tBchCode = "-";
                $aDataAddress = array();
            }

            // array ข้อมูลภาษาที่เกี่ยวข้องกับรายงาน
            $aDataTextRef = array(
                'tTitleCompName' => $tCompName,
                'tTitleReport'  => language('report/report/report', 'tRptTitleProductTransfer'),
                'tDatePrint'    => language('report/report/report', 'tRptAdjStkVDDatePrint'),
                'tTimePrint'    => language('report/report/report', 'tRptAdjStkVDTimePrint'),
                // Address Lang
                'tRptAddrBuilding'    => language('report/report/report', 'tRptAddrBuilding'),
                'tRptAddrRoad'        => language('report/report/report', 'tRptAddrRoad'),
                'tRptAddrSoi'         => language('report/report/report', 'tRptAddrSoi'),
                'tRptAddrSubDistrict' => language('report/report/report', 'tRptAddrSubDistrict'),
                'tRptAddrDistrict'    => language('report/report/report', 'tRptAddrDistrict'),
                'tRptAddrProvince'    => language('report/report/report', 'tRptAddrProvince'),
                'tRptAddrTel'         => language('report/report/report', 'tRptAddrTel'),
                'tRptAddrFax'         => language('report/report/report', 'tRptAddrFax'),
                'tRptAddrBranch'      => language('report/report/report', 'tRptAddrBranch'),
                'tRptAddV2Desc1'      => language('report/report/report', 'tRptAddV2Desc1'),
                'tRptAddV2Desc2'      => language('report/report/report', 'tRptAddV2Desc2'),
                // Table Label
                'tRptTaxSaleLockerFilterMerChantFrom'   => language('report/report/report', 'tRptTaxSaleLockerFilterMerChantFrom'),
                'tRptTaxSaleLockerFilterMerChantTo'     => language('report/report/report', 'tRptTaxSaleLockerFilterMerChantTo'),
                'tRptTaxSaleLockerFilterShopFrom'       => language('report/report/report', 'ttRptTaxSaleLockerFilterShopFrom'),
                'tRptproducttransferFilterPosTo'        => language('report/report/report', 'tRptproducttransferFilterPosTo'),
                'tRptproducttransferFilterPosFrom'      => language('report/report/report', 'tRptproducttransferFilterPosFrom'),
                'tRptTaxSaleLockerFilterDocDateTo'      => language('report/report/report', 'tRptTaxSaleLockerFilterDocDateTo'),
                'tRptFromWareHouse'     => language('report/report/report', 'tRptFromWareHouse'),
                'tRptToWareHouse'       => language('report/report/report', 'tRptToWareHouse'),
                'tRptTaxSaleLockerFilterDocDateFrom' => language('report/report/report', 'tRptTaxSaleLockerFilterDocDateFrom'),
                'tRptTaxSaleLockerFilterDocDateTo'   => language('report/report/report', 'tRptTaxSaleLockerFilterDocDateTo'),
                'tRptShopFrom'      => language('report/report/report', 'tRptShopFrom'),
                'tRptShopTo'        => language('report/report/report', 'tRptShopTo'),
                'tRptCabinetCode'   => language('report/report/report', 'tRptCabinetCode'),
                'tRptPdtCode'       => language('report/report/report', 'tRptPdtCode'),
                'tRptPdtName'       => language('report/report/report', 'tRptPdtName'),
                'tRptCabinetclass'  => language('report/report/report', 'tRptCabinetclass'),
                'tRptCabinetCollum' => language('report/report/report', 'tRptCabinetCollum'),
                'tRptCabinetnumber' => language('report/report/report', 'tRptCabinetnumber'),
                'tRptAdjStkVDLayRow'=> language('report/report/report', 'tRptAdjStkVDLayRow'),
                'tRptAdjStkVDLayCol'=> language('report/report/report', 'tRptAdjStkVDLayCol'),
                'tRptCabinetCost'   => language('report/report/report', 'tRptCabinetCost'),
                'tRptCabinetTotalcost' => language('report/report/report', 'tRptCabinetTotalcost'),
                'tRptListener'      => language('report/report/report', 'tRptListener'),
                'tRptTotalSub'      => language('report/report/report', 'tRptTotalSub'),
                'tRptTotalFooter'   => language('report/report/report', 'tRptTotalFooter'),
                // No Data Report
                'tRptAdjStkNoData'  => language('common/main/main', 'tCMNNotFoundData'),
            );

            $tIP = $this->input->ip_address();
            $tFullHost = gethostbyaddr($tIP);
            $this->tCompName = $tFullHost;

            // ข้อมูลสำหรับดึงข้อมูลจากฐานข้อมูล
            $aDataWhereRpt = array(
                'nPerPage' => 500000,
                'nPage' => $nPage,
                'tCompName' => $tFullHost,
                'tRptCode' => $tRptCode,
                'tUsrSessionID' => $this->session->userdata('tSesSessionID'),
            );
            $aDataReport = $this->mRptInventory->FSaMGetDataReport($aDataWhereRpt);

            // ====================================================================================================================================================================
            // ตั้งค่ารายงาน =========================================================================================================================================================
            // ตั้งค่า Font Style
            $aReportFontStyle = array('font' => array('name' => 'TH Sarabun New'));

            $aStyleRptSizeTitleName = array('font' => array('size' => 14));
            $aStyleRptSizeCompName = array('font' => array('size' => 12));
            $aStyleRptSizeAddressFont = array('font' => array('size' => 12));
            $aStyleRptHeadderTable = array('font' => array('size' => 12, 'color' => array('rgb' => '000000')));
            $aStyleRptDataTable = array('font' => array('size' => 10, 'color' => array('rgb' => '000000')));

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
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);


            // ชื่อหัวรายงาน
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $aDataTextRef['tTitleReport']);
            $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($aStyleRptSizeTitleName);
            // ====================================================================================================================================================================
            // เช็คที่อยู่รายงาน =======================================================================================================================================================
            if (isset($aDataAddress) && !empty($aDataAddress)) {
                // Company Name
                $tRptCompName = (empty($aDataAddress['FTCompName'])) ? '-' : $aDataAddress['FTCompName'];
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', $tRptCompName)->getStyle('A2')->applyFromArray($aStyleRptSizeCompName);

                // Check Vertion Address
                if ($aDataAddress['FTAddVersion'] == 1) {
                    // Check Address Line 1
                    $tRptAddV1No = (empty($aDataAddress['FTAddV1No'])) ? '-' : $aDataAddress['FTAddV1No'];
                    $tRptAddV1Road = (empty($aDataAddress['FTAddV1Road'])) ? '-' : $aDataAddress['FTAddV1Road'];
                    $tRptAddV1Soi = (empty($aDataAddress['FTAddV1Soi'])) ? '-' : $aDataAddress['FTAddV1Soi'];
                    $tRptAddressLine1 = $tRptAddV1No . ' ' . $aDataTextRef['tRptAddrRoad'] . ' ' . $tRptAddV1Road . ' ' . $aDataTextRef['tRptAddrSoi'] . ' ' . $tRptAddV1Soi;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', $tRptAddressLine1)->getStyle('A3')->applyFromArray($aStyleRptSizeAddressFont);

                    // Check Address Line 2
                    $tRptAddV1SubDistName = (empty($aDataAddress['FTSudName'])) ? '-' : $aDataAddress['FTSudName'];
                    $tRptAddV1DstName = (empty($aDataAddress['FTDstName'])) ? '-' : $aDataAddress['FTDstName'];
                    $tRptAddV1PvnName = (empty($aDataAddress['FTPvnName'])) ? '-' : $aDataAddress['FTPvnName'];
                    $tRptAddV1PostCode = (empty($aDataAddress['FTAddV1PostCode'])) ? '-' : $aDataAddress['FTAddV1PostCode'];
                    $tRptAddressLine2 = $tRptAddV1SubDistName . ' ' . $tRptAddV1DstName . ' ' . $tRptAddV1PvnName . ' ' . $tRptAddV1PostCode;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', $tRptAddressLine2)->getStyle('A4')->applyFromArray($aStyleRptSizeAddressFont);
                } else {
                    $tRptAddV2Desc1 = (empty($aDataAddress['FTAddV2Desc1'])) ? '-' : $aDataAddress['FTAddV2Desc1'];
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', $tRptAddV2Desc1)->getStyle('A3')->applyFromArray($aStyleRptSizeAddressFont);

                    $tRptAddV2Desc2 = (empty($aDataAddress['FTAddV2Desc2'])) ? '-' : $aDataAddress['FTAddV2Desc2'];
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', $tRptAddV2Desc2)->getStyle('A4')->applyFromArray($aStyleRptSizeAddressFont);
                }

                // Check Data Telephone Number
                if (isset($aDataAddress['FTCompTel']) && !empty($aDataAddress['FTCompTel'])) {
                    $tRptCompTel = $aDataAddress['FTCompTel'];
                } else {
                    $tRptCompTel = '-';
                }
                $tRptCompTelText = $aDataTextRef['tRptAddrTel'] . ' ' . $tRptCompTel;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', $tRptCompTelText)->getStyle('A5')->applyFromArray($aStyleRptSizeAddressFont);

                // Check Data Fax Number
                if (isset($aDataAddress['FTCompFax']) && !empty($aDataAddress['FTCompFax'])) {
                    $tRptCompFax = $aDataAddress['FTCompFax'];
                } else {
                    $tRptCompFax = '-';
                }
                $tRptCompFaxText = $aDataTextRef['tRptAddrFax'] . ' ' . $tRptCompFax;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A6', $tRptCompFaxText)->getStyle('A6')->applyFromArray($aStyleRptSizeAddressFont);
            }
            // ====================================================================================================================================================================
            // ฟิวเตอร์ข้อมูลรายงาน =================================================================================================================================================== 
            // Row เริ่มต้นของ Filter
            $nStartRowFillter = 2;
            $tFillterColumLEFT = "C";
            $tFillterColumRIGHT = "F";


            // Fillter MerChant (กลุ่มธุรกิจ)
            if (!empty($aDataFilter['tMerCodeFrom']) && !empty($aDataFilter['tMerCodeTo'])) {
                $tRptFilterShopCodeFrom = $aDataTextRef['tRptTaxSaleLockerFilterMerChantFrom'] . ' ' . $aDataFilter['tMerNameFrom'];
                $tRptFilterShopCodeTo = $aDataTextRef['tRptTaxSaleLockerFilterMerChantTo'] . ' ' . $aDataFilter['tMerNameTo'];
                $tRptTextLeftRightFilter = $tRptFilterShopCodeFrom . ' ' . $tRptFilterShopCodeTo;
                $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                $nStartRowFillter += 1;
            }


            // Fillter Shop (ร้านค้า)
            if (!empty($aDataFilter['tShpCodeFrom']) && !empty($aDataFilter['tShpCodeTo'])) {
                $tRptFilterShopCodeFrom = $aDataTextRef['tRptShopFrom'] . ' ' . $aDataFilter['tShpNameFrom'];
                $tRptFilterShopCodeTo = $aDataTextRef['tRptShopTo'] . ' ' . $aDataFilter['tShpNameTo'];
                $tRptTextLeftRightFilter = $tRptFilterShopCodeFrom . ' ' . $tRptFilterShopCodeTo;
                $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                $nStartRowFillter += 1;
            }

            // Fillter Pos (เครื่องจุดขาย)
            if (!empty($aDataFilter['tPosCodeFrom']) && !empty($aDataFilter['tPosCodeTo'])) {
                $tRptFilterDocDateFrom = $aDataTextRef['tRptproducttransferFilterPosFrom'] . ' ' . $aDataFilter['tPosNameFrom'];
                $tRptFilterDocDateTo = $aDataTextRef['tRptTaxSaleLockerFilterDocDateTo'] . ' ' . $aDataFilter['tPosNameTo'];
                $tRptTextLeftRightFilter = $tRptFilterDocDateFrom . ' ' . $tRptFilterDocDateTo;
                $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                $nStartRowFillter += 1;
            }

            // Fillter WareHouse (คลังสินค้า)
            if (!empty($aDataFilter['tWahCodeFrom']) && !empty($aDataFilter['tWahCodeTo'])) {
                $tRptFilterDocDateFrom = $aDataTextRef['tRptFromWareHouse'] . ' ' . $aDataFilter['tWahNameFrom'];
                $tRptFilterDocDateTo = $aDataTextRef['tRptToWareHouse'] . ' ' . $aDataFilter['tWahNameTo'];
                $tRptTextLeftRightFilter = $tRptFilterDocDateFrom . ' ' . $tRptFilterDocDateTo;
                $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                $nStartRowFillter += 1;
            }


            // Fillter DocDate (วันที่สร้างเอกสาร)
            if (!empty($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateTo'])) {
                $tRptFilterDocDateFrom = $aDataTextRef['tRptTaxSaleLockerFilterDocDateFrom'] . ' ' . $aDataFilter['tDocDateFrom'];
                $tRptFilterDocDateTo = $aDataTextRef['tRptTaxSaleLockerFilterDocDateTo'] . ' ' . $aDataFilter['tDocDateTo'];
                $tRptTextLeftRightFilter = $tRptFilterDocDateFrom . ' ' . $tRptFilterDocDateTo;
                $tColumsMergeFilter = $tFillterColumLEFT . $nStartRowFillter . ':' . $tFillterColumRIGHT . $nStartRowFillter;

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT . $nStartRowFillter, $tRptTextLeftRightFilter);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT . $nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                $nStartRowFillter += 1;
            }

            // ====================================================================================================================================================================
            // ====================================================================================================================================================================
            // ตั้งค่าวันที่เวลาออกรายงาน ===============================================================================================================================================
            $tRptDateTimeExportText = $aDataTextRef['tDatePrint'] . ' ' . $dDatePrint . ' ' . $aDataTextRef['tTimePrint'] . ' ' . $dTimePrint;
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:H7');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A7', $tRptDateTimeExportText);
            $objPHPExcel->getActiveSheet()->getStyle("A7")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($aStyleRptSizeAddressFont);
            // ====================================================================================================================================================================
            // หัวตารางรายงาน =======================================================================================================================================================
            // กำหนดจุดเริ่มต้นของแถวหัวตาราง
            $nStartRowHeadder = 8;
            // กำหนด Style Font ของหัวตาราง
            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':H' . $nStartRowHeadder)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':H' . $nStartRowHeadder)->applyFromArray($aStyleRptHeadderTable);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':H' . $nStartRowHeadder)->applyFromArray(array(
                'borders' => array(
                    'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                    'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))
                )
            ));
            // กำหนดข้อมูลลงหัวตาราง
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $nStartRowHeadder, $aDataTextRef['tRptCabinetCode'])
                    ->setCellValue('B' . $nStartRowHeadder, $aDataTextRef['tRptPdtCode'])
                    ->setCellValue('C' . $nStartRowHeadder, $aDataTextRef['tRptPdtName'])
                    ->setCellValue('D' . $nStartRowHeadder, $aDataTextRef['tRptCabinetclass'])
                    ->setCellValue('E' . $nStartRowHeadder, $aDataTextRef['tRptCabinetCollum'])
                    ->setCellValue('F' . $nStartRowHeadder, $aDataTextRef['tRptCabinetnumber'])
                    ->setCellValue('G' . $nStartRowHeadder, $aDataTextRef['tRptCabinetCost'])
                    ->setCellValue('H' . $nStartRowHeadder, $aDataTextRef['tRptCabinetTotalcost']);

            // Alignment ของหัวตาราง
            $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowHeadder . ':H' . $nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            // ====================================================================================================================================================================
            // วนลูปข้อมูลตารางข้อมูล =================================================================================================================================================
            // Set Variable Data
            $nStartRowData = $nStartRowHeadder + 1;
            if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) {
                // ******* Step 1 ประกาศตัวแปรใช้ในการเช็คเงื่อนไข
                $tGrouppingData = "";
                $nGroupMember = "";
                $nRowPartID = "";

                $nSubSumtkQty = 0;
                $nSubSumtCostEx = 0;
                $nSubSumCostExQty = 0;

                foreach ($aDataReport['aRptData'] as $nKey => $aValue) {
                    // ******* Step 3 Set ค่า Value FNRptGroupMember And FNRowPartID
                    $nRowPartID = $aValue["FNRowPartID"];
                    $nGroupMember = $aValue["FNRptGroupMember"];

                    // ******* Step 4 : Check Groupping Create Row Groupping 
                    if ($tGrouppingData != $aValue['FTPosCode']) {
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $aValue['FTPosCode']);

                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':H' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
                        $nStartRowData++;
                    }

                    // ******* Step 5 : Loop Set Data Value 
                    // $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowData.':C'.$nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $nStartRowData, '')
                            ->setCellValue('B' . $nStartRowData, $aValue['FTPdtCode'])
                            ->setCellValue('C' . $nStartRowData, $aValue['FTPdtName'])
                            ->setCellValue('D' . $nStartRowData, $aValue['FNLayRow'])
                            ->setCellValue('E' . $nStartRowData, $aValue['FNLayCol'])
                            ->setCellValue('F' . $nStartRowData, number_format($aValue["FCStkQty"], 2))
                            ->setCellValue('G' . $nStartRowData, number_format($aValue["FCPdtCostEx"], 2))
                            ->setCellValue('H' . $nStartRowData, number_format($aValue["CostExQty"], 2));

                    // $objPHPExcel->getActiveSheet()->getStyle('D'.$nStartRowData.':E'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('D' . $nStartRowData . ':H' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    // ******* Step 6 : เตรียม Parameter สำหรับ Summary Sub Footer
                    if ($nRowPartID == $nGroupMember) {
                        $nStartRowData++;
                        $nSubSumtkQty = $aValue["FCStkQty_SubTotal"];
                        $nSubSumtCostEx = $aValue["FCPdtCostEx_SubTotal"];
                        $nSubSumCostExQty = $aValue["CostExQty_SubTotal"];

                        // Set Color Row Sub Footer
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':H' . $nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':H' . $nStartRowData)->applyFromArray(array(
                            'borders' => array(
                                'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000'))
                            )
                        ));

                        // Text Sum Sub
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':E' . $nStartRowData);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $aDataTextRef['tRptTotalSub']);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);

                        // Value Sum Sub
                        $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('F' . $nStartRowData, number_format($nSubSumtkQty, 0))
                                ->setCellValue('G' . $nStartRowData, number_format($nSubSumtCostEx, 0))
                                ->setCellValue('H' . $nStartRowData, number_format($nSubSumCostExQty, 0));

                        $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData . ':H' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData . ':H' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    }

                    $nSumFooterFCStkQty = number_format($aValue["FCStkQty_Footer"], 2);
                    $nSumFooterPdtCostEx = number_format($aValue["FCPdtCostEx_Footer"], 2);
                    $nSumFooterCostExQty = number_format($aValue["CostExQty_Footer"], 2);

                    // ******* Step 2 Set ค่า Value ให้กับตัวแปร
                    $tGrouppingData = $aValue["FTPosCode"];
                    $nStartRowData++;
                }

                // Step 7 : Set Footer Text
                $nPageNo = $aDataReport["aPagination"]["nDisplayPage"];
                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                if ($nPageNo == $nTotalPage) {
                    // Set Color Row Sub Footer
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':H' . $nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData . ':H' . $nStartRowData)->applyFromArray(array(
                        'borders' => array(
                            'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')),
                            'bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE, 'color' => array('rgb' => '000000'))
                        )
                    ));

                    // Text Sum Footer
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':E' . $nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $aDataTextRef['tRptTotalFooter']);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);

                    // Value Sum Footer
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('F' . $nStartRowData, $nSumFooterFCStkQty)
                            ->setCellValue('G' . $nStartRowData, $nSumFooterPdtCostEx)
                            ->setCellValue('H' . $nStartRowData, $nSumFooterCostExQty);

                    $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData . ':H' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    $objPHPExcel->getActiveSheet()->getStyle('F' . $nStartRowData . ':H' . $nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                }
            } else {
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $nStartRowData . ':E' . $nStartRowData);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $nStartRowData, $aDataTextRef['tRptAdjStkNoData']);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A' . $nStartRowData)->applyFromArray($aStyleRptDataTable);
            }

            // ====================================================================================================================================================================
            // Set Content Type Export File Excel =================================================================================================================================
            $tFilename = 'Report-' . $tRptCode . date("dmYhis") . '.xlsx';

            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            ;
            header("Content-Disposition: attachment;filename=$tFilename");
            header("Content-Transfer-Encoding: binary");

            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

            if (!is_dir(APPPATH . 'modules/report/assets/exportexcel/')) {
                mkdir(APPPATH . 'modules/report/assets/exportexcel/');
            }

            if (!is_dir(APPPATH . 'modules/report/assets/exportexcel/' . $tRptCode)) {
                mkdir(APPPATH . 'modules/report/assets/exportexcel/' . $tRptCode);
            }

            if (!is_dir(APPPATH . 'modules/report/assets/exportexcel/' . $tRptCode . '/' . $tSesUsername)) {
                mkdir(APPPATH . 'modules/report/assets/exportexcel/' . $tRptCode . '/' . $tSesUsername);
            }

            $tPathExport = APPPATH . 'modules/report/assets/exportexcel/' . $tRptCode . '/' . $tSesUsername . '/';
            $oFiles = glob($tPathExport . '*');
            foreach ($oFiles as $tFile) {
                if (is_file($tFile))
                    unlink($tFile);
            }

            // Check Status Save File Excel
            $objWriter->save($tPathExport . $tFilename);
            $aResponse = array(
                'nStaExport' => 1,
                'tFileName' => $tFilename,
                'tPathFolder' => 'application/modules/report/assets/exportexcel/' . $tRptCode . '/' . $tSesUsername . '/',
                'tMessage' => "Export File Successfully."
            );
            // ====================================================================================================================================================================
        } catch (Exception $Error) {
            $aResponse = array(
                'nStaExport' => 500,
                'tMessage' => $Error->getMessage()
            );
        }
        echo json_encode($aResponse);
    }

    // Functionality: Click Page Report (Report Viewer)
    // Parameters:  Function Parameter
    // Creator: 16/08/2019 Wasin(Yoshi)
    // LastUpdate: 21/08/2019 Saharat(Golf)
    // Return: object Status Count Data Report
    // ReturnType: Object
    public function FSoCChkDataReportInTableTemp($paDataSwitchCase) {
        try {
            $aDataCountData = [
                'tCompName' => $paDataSwitchCase['paDataFilter']['tCompName'],
                'tRptCode' => $paDataSwitchCase['paDataFilter']['tRptCode'],
                'tSessionID' => $paDataSwitchCase['paDataFilter']['tSessionID'],
            ];
            $nDataCountPage = $this->mRptInventory->FSnMCountDataReportAll($aDataCountData);
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

    // Functionality: Send Rabbit MQ Report
    // Parameters:  Function Parameter
    // Creator: 16/08/2019 Wasin(Yoshi)
    // LastUpdate: 21/08/2019 Saharat(Golf)
    // Return: object Send Rabbit MQ Report
    // ReturnType: Object
    public function FSvCCallRptExportFile() {
        try {
            $tRptGrpCode = $this->input->post('ohdRptGrpCode');
            $tRptCode = $this->input->post('ohdRptCode');
            $tUserCode = $this->session->userdata('tSesUsername');
            $tSessionID = $this->session->userdata('tSesSessionID');
            $nLangID = FCNaHGetLangEdit();
            $tRptExportType = $this->input->post('ohdRptTypeExport');

            $tIP = $this->input->ip_address();
            $tFullHost = gethostbyaddr($tIP);
            $this->tCompName = $tFullHost;

            $tCompName = $tFullHost;

            $dDateSendMQ = date('Y-m-d');
            $dTimeSendMQ = date('H:i:s');
            $dDateSubscribe = date('Ymd');
            $dTimeSubscribe = date('His');

            $tMerCodeF = $this->input->post('oetRptMerCodeFrom');
            $tMerCodeT = $this->input->post('oetRptMerCodeTo');
            if ($tMerCodeF != "" && $tMerCodeT != "") {
                $tMerCodeFrom = $this->input->post('oetRptMerCodeFrom');
                $tMerCodeTo = $this->input->post('oetRptMerCodeTo');
            } else {
                $tMerCodeFrom = "";
                $tMerCodeTo = "";
            }

            $aDataFilter = array(
                'tMerCodeFrom' => $tMerCodeFrom,
                'tMerCodeTo' => $tMerCodeTo,
                'tMerNameFrom' => !empty($this->input->post('oetRptMerNameFrom')) ? $this->input->post('oetRptMerNameFrom') : "",
                'tMerNameTo' => !empty($this->input->post('oetRptMerNameTo')) ? $this->input->post('oetRptMerNameTo') : "",
                'tWahCodeFrom' => !empty($this->input->post('oetRptWahCodeFrom')) ? $this->input->post('oetRptWahCodeFrom') : "",
                'tWahCodeTo' => !empty($this->input->post('oetRptWahCodeTo')) ? $this->input->post('oetRptWahCodeTo') : "",
                'tWahNameFrom' => !empty($this->input->post('oetRptWahNameFrom')) ? $this->input->post('oetRptWahNameFrom') : "",
                'tWahNameTo' => !empty($this->input->post('oetRptWahNameTo')) ? $this->input->post('oetRptWahNameTo') : "",
                'tShpCodeFrom' => !empty($this->input->post('oetRptShpCodeFrom')) ? $this->input->post('oetRptShpCodeFrom') : "",
                'tShpCodeTo' => !empty($this->input->post('oetRptShpCodeTo')) ? $this->input->post('oetRptShpCodeTo') : "",
                'tShpNameFrom' => !empty($this->input->post('oetRptShpNameFrom')) ? $this->input->post('oetRptShpNameFrom') : "",
                'tShpNameTo' => !empty($this->input->post('oetRptShpNameTo')) ? $this->input->post('oetRptShpNameTo') : "",
                'tPosCodeFrom' => !empty($this->input->post('oetRptPosCodeFrom')) ? $this->input->post('oetRptPosCodeFrom') : "",
                'tPosCodeTo' => !empty($this->input->post('oetRptPosCodeTo')) ? $this->input->post('oetRptPosCodeTo') : "",
                'tPosNameFrom' => !empty($this->input->post('oetRptPosNameFrom')) ? $this->input->post('oetRptPosNameFrom') : "",
                'tPosNameTo' => !empty($this->input->post('oetRptPosNameTo')) ? $this->input->post('oetRptPosNameTo') : "",
                'tDocDateFrom' => !empty($this->input->post('oetRptDocDateFrom')) ? $this->input->post('oetRptDocDateFrom') : "",
                'tDocDateTo' => !empty($this->input->post('oetRptDocDateTo')) ? $this->input->post('oetRptDocDateTo') : ""
            );

            // Set Parameter Send MQ
            $tRptQueueName = 'RPT_' . $tRptGrpCode . '_' . $tRptCode;

            $aDataSendMQ = [
                'tQueueName' => $tRptQueueName,
                'aParams' => [
                    'ptRptCode' => $tRptCode,
                    'pnPerFile' => 20000,
                    'ptUserCode' => $tUserCode,
                    'ptUserSessionID' => $tSessionID,
                    'pnLngID' => $nLangID,
                    'ptFilter' => $aDataFilter,
                    'ptRptExpType' => $tRptExportType,
                    'ptComName' => $tCompName,
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
                    'ptComName' => $tCompName,
                    'ptRptCode' => $tRptCode,
                    'ptUserCode' => $tUserCode,
                    'ptUserSessionID' => $tSessionID,
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

