<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set("Asia/Bangkok");

include APPPATH.'third_party/PHPExcel/Classes/PHPExcel.php';
include APPPATH.'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';
include APPPATH.'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';


class cRptSaleRecive extends MX_Controller {

    public function __construct(){
        parent::__construct ();
        $this->load->model('company/company/mCompany');
        $this->load->model('report/reportsalerecivevd/mRptSaleRecive');
        $this->load->model('report/report/mReport');
    }

    public function index(){
        $tRptRoute      = $this->input->post('ohdRptRoute');
        $tRptCode       = $this->input->post('ohdRptCode');
        $tRptTypeExport = $this->input->post('ohdRptTypeExport');
        
     
        if(!empty($tRptTypeExport) && !empty($tRptCode)){
            $aDataFilter    = array(
                'tSessionID'    => $this->session->userdata('tSesSessionID'),
                'tCompName'     => gethostname(),
                'tRptCode'         => $this->input->post('ohdRptCode'),
                'tRcvCodeFrom'  => !empty($this->input->post('oetRptRcvCodeFrom'))  ? $this->input->post('oetRptRcvCodeFrom')   : "",
                'tRcvNameFrom'  => !empty($this->input->post('oetRptRcvNameFrom'))  ? $this->input->post('oetRptRcvNameFrom')   : "",
                'tRcvCodeTo'    => !empty($this->input->post('oetRptRcvCodeTo'))    ? $this->input->post('oetRptRcvCodeTo')     : "",
                'tRcvNameTo'    => !empty($this->input->post('oetRptRcvNameTo'))    ? $this->input->post('oetRptRcvNameTo')     : "",
                'tBchCodeFrom'  => !empty($this->input->post('oetRptBchCodeFrom'))  ? $this->input->post('oetRptBchCodeFrom')   : "",
                'tBchNameFrom'  => !empty($this->input->post('oetRptBchNameFrom'))  ? $this->input->post('oetRptBchNameFrom')   : "",
                'tBchCodeTo'    => !empty($this->input->post('oetRptBchCodeTo'))    ? $this->input->post('oetRptBchCodeTo')     : "",
                'tBchNameTo'    => !empty($this->input->post('oetRptBchNameTo'))    ? $this->input->post('oetRptBchNameTo')     : "",
                'tShopCodeFrom' => !empty($this->input->post('oetRptShpCodeFrom'))  ? $this->input->post('oetRptShpCodeFrom')   : "",
                'tShopNameFrom' => !empty($this->input->post('oetRptShpNameFrom'))  ? $this->input->post('oetRptShpNameFrom')   : "",
                'tShopCodeTo'   => !empty($this->input->post('oetRptShpCodeTo'))    ? $this->input->post('oetRptShpCodeTo')     : "",
                'tShopNameTo'   => !empty($this->input->post('oetRptShpNameTo'))    ? $this->input->post('oetRptShpNameTo')     : "",
                'tDocDateFrom'  => !empty($this->input->post('oetRptDocDateFrom'))  ? $this->input->post('oetRptDocDateFrom')   : "",
                'tDocDateTo'    => !empty($this->input->post('oetRptDocDateTo'))    ? $this->input->post('oetRptDocDateTo')     : "",
                'nLangID'       => FCNaHGetLangEdit(),

            );

            // Execute Stored Procedure
            $tResult =  $this->mRptSaleRecive->FSnMExecStoreReport($aDataFilter);
            
            $aDataSwitchCase    = array(
                'ptRptRoute'        => $tRptRoute,
                'ptRptCode'         => $tRptCode,
                'ptRptTypeExport'   => $tRptTypeExport,
                'paDataFilter'      => $aDataFilter
            );
            switch($tRptTypeExport){
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

    // Functionality: ???????????????????????????????????????????????????????????????????????????????????? (Report Viewer)
    // Parameters:  Function Parameter
    // Creator: 10/07/2019 Saharat(Golf)
    // LastUpdate: 
    // Return: View Report Viewer
    // ReturnType: View
    public function FSvCCallRptViewBeforePrint($paDataSwitchCase){

        $tRptRoute      = $paDataSwitchCase['ptRptRoute'];
        $tRptCode       = $paDataSwitchCase['ptRptCode'];
        $tRptTypeExport = $paDataSwitchCase['ptRptTypeExport'];
        $aDataFilter    = $paDataSwitchCase['paDataFilter'];
        $nPage          = 1;
        $nLangEdit      = $this->session->userdata("tLangEdit");

        // Get Data Company (???????????????????????????????????????????????????????????????????????????????????????)
        $tAPIReq        = "";
        $tMethodReq     = "GET";
        $aDataWhereComp = array('FNLngID' => $nLangEdit);
        $aCompData	= $this->mCompany->FSaMCMPList($tAPIReq,$tMethodReq,$aDataWhereComp);

        if($aCompData['rtCode'] == '1'){
            $tCompName          = $aCompData['raItems']['rtCmpName'];
            $tBchCode           = $aCompData['raItems']['rtCmpBchCode'];
            $aDataBranchAddress = $this->mReport->FSaMRptGetDataAddressByBranchComp($tBchCode,$nLangEdit);
        }else{
            $tCompName          = "-";
            $tBchCode           = "-";
            $aDataBranchAddress = array();
        }

        // array ????????????????????????????????????????????????????????????????????????????????????????????????
        $aDataTextRef   = array(
            'tTitleCompName'        => $tCompName,
            'tTitleReport'          => language('report/report/report','tRptTitleSaleRecive'),
            'tDatePrint'            => language('report/report/report','tRptAdjStkVDDatePrint'),
            'tTimePrint'            => language('report/report/report','tRptAdjStkVDTimePrint'),
            // Address Lang
            'tRptAddrBuilding'      => language('report/report/report','tRptAddrBuilding'),
            'tRptAddrRoad'          => language('report/report/report','tRptAddrRoad'),
            'tRptAddrSoi'           => language('report/report/report','tRptAddrSoi'),
            'tRptAddrSubDistrict'   => language('report/report/report','tRptAddrSubDistrict'),
            'tRptAddrDistrict'      => language('report/report/report','tRptAddrDistrict'),
            'tRptAddrProvince'      => language('report/report/report','tRptAddrProvince'),
            'tRptAddrTel'           => language('report/report/report','tRptAddrTel'),
            'tRptAddrFax'           => language('report/report/report','tRptAddrFax'),
            'tRptAddrBranch'        => language('report/report/report','tRptAddrBranch'),
            'tRptAddV2Desc1'        => language('report/report/report','tRptAddV2Desc1'),
            'tRptAddV2Desc2'        => language('report/report/report','tRptAddV2Desc2'),
            // Table Report
            'tRptBarchCode'         => language('report/report/report','tRptBarchCode'),
            'tRptBarchName'         => language('report/report/report','tRptBarchName'),
            'tRptDocDate'           => language('report/report/report','tRptDocDate'),
            'tRptShopCode'          => language('report/report/report','tRptShopCode'),
            'tRptShopName'          => language('report/report/report','tRptShopName'),
            'tRptAmount'            => language('report/report/report','tRptAmount'),
            'tRptSale'              => language('report/report/report','tRptSale'),
            'tRptCancelSale'        => language('report/report/report','tRptCancelSale'),
            'tRptTotalSale'         => language('report/report/report','tRptTotalSale'),
            'tRptTotalAllSale'      => language('report/report/report','tRptTotalAllSale'),
            'tRptPayby'             => language('report/report/report','tRptPayby'),
            'tRptRcvDocumentCode'   => language('report/report/report','tRptRcvDocumentCode'),
            'tRptDate'              => language('report/report/report','tRptDate'),
            'tRptRcvTotal'          => language('report/report/report','tRptRcvTotal'),
            // No Data Report
            'tRptAdjStkNoData'      => language('common/main/main', 'tCMNNotFoundData'),
            // Data Address Branch
            'aDataBranchAddress'    => $aDataBranchAddress
        );

        // ???????????????????????????????????????????????????????????????????????????????????????????????????
        $aDataWhereRpt  = array(
            'nPerPage'      => 4,
            'nPage'         => $nPage,
            'tCompName'     => gethostname(),
            'tRptCode'      => $tRptCode,
            'tUsrSessionID' => $this->session->userdata('tSesSessionID'),
            // 'tCompName'     => 'ADA084',
            // 'tRptCode'      => '002002003',
            // 'tUsrSessionID' => 'SESS00000001',
        );
        $aDataReport        = $this->mRptSaleRecive->FSaMGetDataReport($aDataWhereRpt);
        // print_r($aDataReport);
        // exit;
        // ?????????????????? Render Report
        $aDataViewPdt       = array(
            'aDataReport'           => $aDataReport,
            'aDataTextRef'          => $aDataTextRef,
            'aDataCompany'          => $aCompData,
            'aDataFilter'           => $paDataSwitchCase['paDataFilter']
        );
     
        // Load View Advance Table
        $tViewTest = JCNoHLoadViewAdvanceTable('report/datasources/rptSaleReciveVD','wRptSaleReciveHtml',$aDataViewPdt);

        // Data Viewer Center Report
        $aDataViewer    = array(
            'tTitleReport'      => $aDataTextRef['tTitleReport'],
            'tRptTypeExport'    => $tRptTypeExport,
            'tRptCode'          => $tRptCode,
            'tRptRoute'         => $tRptRoute,
            'tViewRenderKool'   => $tViewTest,
            'aDataFilter'       => $paDataSwitchCase['paDataFilter'],
            'aDataReport'       => array(
            'raItems'       => $aDataReport['aRptData'],
            'rnAllRow'      => $aDataReport['aPagination']['nTotalRecord'],
            'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
            'rnAllPage'     => $aDataReport['aPagination']['nTotalPage'],
            'rtCode'        => '1',
            'rtDesc'        => 'success',
            )
        );
        $this->load->view('report/report/wReportViewer',$aDataViewer);

    }

    // Functionality: Call Rpt Table Kool Report
    // Parameters:  Function Parameter
    // Creator: 04/04/2019 Saharat(Golf)
    // LastUpdate:
    // Return: View Kool Report
    // ReturnType: View
    public function FCNvCRenderKoolReportHtml($paDataReport,$paDataFilter){
        $aDataWhere = array('FNLngID' => $paDataFilter['nLangID']);
        $tAPIReq    = "";
        $tMethodReq = "GET";
        $aCompData	= $this->mCompany->FSaMCMPList($tAPIReq,$tMethodReq,$aDataWhere);
        if($aCompData['rtCode'] == '1'){
            $tCompName      = $aCompData['raItems']['rtCmpName'];
            $tBchCode       = $aCompData['raItems']['rtCmpBchCode'];
            $tBchName       = $aCompData['raItems']['rtCmpBchName'];
        }else{
            $tCompName      = "-";
            $tBchCode       = "-";
            $tBchName       = "-";
        }

        // array ????????????????????????????????????????????????????????????????????????????????????????????????
        $aDataTextRef = array(
            'tTitleReport'          => language('report/report/report','tRptTitleSaleRecive'),
            'tRptTaxNo'             => language('report/report/report','tRptTaxNo'),
            'tRptDatePrint'         => language('report/report/report','tRptDatePrint'),
            'tRptDateExport'        => language('report/report/report','tRptDateExport'),
            'tRptTimePrint'         => language('report/report/report','tRptTimePrint'),
            'tRptPrintHtml'         => language('report/report/report','tRptPrintHtml'),
            // Filter Heard Report
            'tRptBchFrom'           => language('report/report/report','tRptBchFrom'),
            'tRptBchTo'             => language('report/report/report','tRptBchTo'),
            'tRptShopFrom'          => language('report/report/report','tRptShopFrom'),
            'tRptShopTo'            => language('report/report/report','tRptShopTo'),
            'tRptDateFrom'          => language('report/report/report','tRptDateFrom'),
            'tRptDateTo'            => language('report/report/report','tRptDateTo'),
            // Table Report
            'tRptBarchCode'         => language('report/report/report','tRptBarchCode'),
            'tRptBarchName'         => language('report/report/report','tRptBarchName'),
            'tRptDocDate'           => language('report/report/report','tRptDocDate'),
            'tRptShopCode'          => language('report/report/report','tRptShopCode'),
            'tRptShopName'          => language('report/report/report','tRptShopName'),
            'tRptAmount'            => language('report/report/report','tRptAmount'),
            'tRptSale'              => language('report/report/report','tRptSale'),
            'tRptCancelSale'        => language('report/report/report','tRptCancelSale'),
            'tRptTotalSale'         => language('report/report/report','tRptTotalSale'),
            'tRptTotalAllSale'      => language('report/report/report','tRptTotalAllSale'),
            'tRptPayby'             => language('report/report/report','tRptPayby'),
            'tRptRcvDocumentCode'   => language('report/report/report','tRptRcvDocumentCode'),
            'tRptDate'              => language('report/report/report','tRptDate'),
            'tRptRcvTotal'          => language('report/report/report','tRptRcvTotal'),
        );  

        // Ref File Kool Report
        require_once APPPATH.'modules\report\datasources\rptSaleRecive\rptSaleRecive.php';

        // Set Parameter To Report
        $oRptSaleReciveHtml     = new rptSaleRecive(array(
            'nCurrentPage'      => $paDataReport['rnCurrentPage'],
            'nAllPage'          => $paDataReport['rnAllPage'],
            'tCompName'         => $tCompName,
            'tBchName'          => $tBchName,
            'tBchTaxNo'         => '-',
            'tAddressLine1'     => '-',
            'tAddressLine2'     => '-',
            'aFilterReport'     => $paDataFilter,
            'aDataTextRef'      => $aDataTextRef,
            'aDataReturn'       => $paDataReport['raItems']
        ));

        $oRptSaleReciveHtml->run();
        $tHtmlViewReport    = $oRptSaleReciveHtml->render('wRptSaleReciveHtml',true);
        return $tHtmlViewReport;
    }

    // Functionality: Click Page ????????????????????????????????????????????????????????? (Report Viewer)
    // Parameters:  Function Parameter
    // Creator: 19/07/2019 Wasin(Yoshi)
    // LastUpdate: 22/04/2019 saharat(Golf)
    // Return: View Report Viewer
    // ReturnType: View
    public function FSvCCallRptViewBeforePrintClickPage(){
        $tRptRoute      = $this->input->post('ohdRptRoute');
        $tRptCode       = $this->input->post('ohdRptCode');
        $tRptTypeExport = $this->input->post('ohdRptTypeExport');
        $aDataFilter    = json_decode($this->input->post('ohdRptDataFilter'),true);
        $nPage          = $this->input->post('ohdRptCurrentPage');
        $nLangEdit      = $this->session->userdata("tLangEdit");

        // Get Data Company (???????????????????????????????????????????????????????????????????????????????????????)
        $tAPIReq        = "";
        $tMethodReq     = "GET";
        $aDataWhereComp = array('FNLngID' => $nLangEdit);
        $aCompData	= $this->mCompany->FSaMCMPList($tAPIReq,$tMethodReq,$aDataWhereComp);
        if($aCompData['rtCode'] == '1'){
            $tCompName          = $aCompData['raItems']['rtCmpName'];
            $tBchCode           = $aCompData['raItems']['rtCmpBchCode'];
            $aDataBranchAddress = $this->mReport->FSaMRptGetDataAddressByBranchComp($tBchCode,$nLangEdit);
        }else{
            $tCompName          = "-";
            $tBchCode           = "-";
            $aDataBranchAddress = array();
        }

        // array ????????????????????????????????????????????????????????????????????????????????????????????????
        $aDataTextRef   = array(
            'tTitleCompName'        => $tCompName,
            'tTitleReport'          => language('report/report/report','tRptTitleSaleRecive'),
            'tDatePrint'            => language('report/report/report','tRptAdjStkVDDatePrint'),
            'tTimePrint'            => language('report/report/report','tRptAdjStkVDTimePrint'),
            // Address Lang
            'tRptAddrBuilding'      => language('report/report/report','tRptAddrBuilding'),
            'tRptAddrRoad'          => language('report/report/report','tRptAddrRoad'),
            'tRptAddrSoi'           => language('report/report/report','tRptAddrSoi'),
            'tRptAddrSubDistrict'   => language('report/report/report','tRptAddrSubDistrict'),
            'tRptAddrDistrict'      => language('report/report/report','tRptAddrDistrict'),
            'tRptAddrProvince'      => language('report/report/report','tRptAddrProvince'),
            'tRptAddrTel'           => language('report/report/report','tRptAddrTel'),
            'tRptAddrFax'           => language('report/report/report','tRptAddrFax'),
            'tRptAddrBranch'        => language('report/report/report','tRptAddrBranch'),
            'tRptAddV2Desc1'        => language('report/report/report','tRptAddV2Desc1'),
            'tRptAddV2Desc2'        => language('report/report/report','tRptAddV2Desc2'),
            // Table Report
            'tRptBarchCode'         => language('report/report/report','tRptBarchCode'),
            'tRptBarchName'         => language('report/report/report','tRptBarchName'),
            'tRptDocDate'           => language('report/report/report','tRptDocDate'),
            'tRptShopCode'          => language('report/report/report','tRptShopCode'),
            'tRptShopName'          => language('report/report/report','tRptShopName'),
            'tRptAmount'            => language('report/report/report','tRptAmount'),
            'tRptSale'              => language('report/report/report','tRptSale'),
            'tRptCancelSale'        => language('report/report/report','tRptCancelSale'),
            'tRptTotalSale'         => language('report/report/report','tRptTotalSale'),
            'tRptTotalAllSale'      => language('report/report/report','tRptTotalAllSale'),
            'tRptPayby'             => language('report/report/report','tRptPayby'),
            'tRptRcvDocumentCode'   => language('report/report/report','tRptRcvDocumentCode'),
            'tRptDate'              => language('report/report/report','tRptDate'),
            'tRptRcvTotal'          => language('report/report/report','tRptRcvTotal'),
            // No Data Report
            'tRptAdjStkNoData'      => language('common/main/main', 'tCMNNotFoundData'),
            // Data Address Branch
            'aDataBranchAddress'    => $aDataBranchAddress
        );
   

        // ???????????????????????????????????????????????????????????????????????????????????????????????????
        $aDataWhereRpt  = array(
            'nPerPage'      => 4,
            'nPage'         => $nPage,
            'tCompName'     => gethostname(),
            'tRptCode'      => $tRptCode,
            'tUsrSessionID' => $this->session->userdata('tSesSessionID'),
        
        );
        $aDataReport        = $this->mRptSaleRecive->FSaMGetDataReport($aDataWhereRpt);

        // ?????????????????? Render Report
        $aDataViewPdt       = array(
            'aDataReport'           => $aDataReport,
            'aDataTextRef'          => $aDataTextRef,
            'aDataCompany'          => $aCompData,
            'aDataFilter'           => $aDataFilter
        );

        // Load View Advance Table

        $tViewTest = JCNoHLoadViewAdvanceTable('report/datasources/rptSaleReciveVD','wRptSaleReciveHtml',$aDataViewPdt);

        // Data Viewer Center Report
        $aDataViewer    = array(
            'tTitleReport'      => $aDataTextRef['tTitleReport'],
            'tRptTypeExport'    => $tRptTypeExport,
            'tRptCode'          => $tRptCode,
            'tRptRoute'         => $tRptRoute,
            'tViewRenderKool'   => $tViewTest,
            'aDataFilter'       => $aDataFilter,
            'aDataReport'       => array(
                'raItems'       => $aDataReport['aRptData'],
                'rnAllRow'      => $aDataReport['aPagination']['nTotalRecord'],
                'rnCurrentPage' => $aDataReport['aPagination']['nDisplayPage'],
                'rnAllPage'     => $aDataReport['aPagination']['nTotalPage'],
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            )
        );
        $this->load->view('report/report/wReportViewer',$aDataViewer);
    }


    // Functionality: Function Render Report Excel
    // Parameters:  Function Parameter
    // Creator: 12/08/2019 Sarun
    // LastUpdate: -
    // Return: View Report Viewer
    // ReturnType: View
    private function FSvCCallRptRenderExcel($paDataMain){
        try{
            // ??????????????????????????????????????????????????????????????? =================================================================================================================================================
                $tRptRoute      = $paDataMain['ptRptRoute'];
                $tRptCode       = $paDataMain['ptRptCode'];
                $tRptTypeExport = $paDataMain['ptRptTypeExport'];
                $aDataFilter    = $paDataMain['paDataFilter'];
                $nPage          = 1;
                $nLangEdit      = $this->session->userdata("tLangEdit");
                $tSesUsername   = $this->session->userdata('tSesUsername');
                $tUsrSessionID  = $this->session->userdata('tSesSessionID');
                $dDatePrint     = date('Y-m-d');
                $dTimePrint     = date('H:i:s');

                // Check Filter Merchant And 
                $tAPIReq        = "";
                $tMethodReq     = "GET";
                $aDataWhereComp = array('FNLngID' => $nLangEdit);
                $aCompData      = $this->mCompany->FSaMCMPList($tAPIReq,$tMethodReq,$aDataWhereComp);
                if($aCompData['rtCode'] == '1'){
                    $tCompName          = $aCompData['raItems']['rtCmpName'];
                    $tBchCode           = $aCompData['raItems']['rtCmpBchCode'];
                    $aDataAddress       = $this->mReport->FSaMRptGetDataAddressByBranchComp($tBchCode,$nLangEdit);
                }else{
                    $tCompName          = "-";
                    $tBchCode           = "-";
                    $aDataAddress       = array();
                }

                // array ????????????????????????????????????????????????????????????????????????????????????????????????
                $aDataTextRef = array(
                    'tTitleReport'          => language('report/report/report','tRptTitleSaleRecive'),
                    'tRptTaxNo'             => language('report/report/report','tRptTaxNo'),
                    'tRptDatePrint'         => language('report/report/report','tRptDatePrint'),
                    'tRptDateExport'        => language('report/report/report','tRptDateExport'),
                    'tRptTimePrint'         => language('report/report/report','tRptTimePrint'),
                    'tRptPrintHtml'         => language('report/report/report','tRptPrintHtml'),
                    // Address Lang
                    'tRptAddrBuilding'          => language('report/report/report','tRptAddrBuilding'),
                    'tRptAddrRoad'              => language('report/report/report','tRptAddrRoad'),
                    'tRptAddrSoi'               => language('report/report/report','tRptAddrSoi'),
                    'tRptAddrSubDistrict'       => language('report/report/report','tRptAddrSubDistrict'),
                    'tRptAddrDistrict'          => language('report/report/report','tRptAddrDistrict'),
                    'tRptAddrProvince'          => language('report/report/report','tRptAddrProvince'),
                    'tRptAddrTel'               => language('report/report/report','tRptAddrTel'),
                    'tRptAddrFax'               => language('report/report/report','tRptAddrFax'),
                    'tRptAddrBranch'            => language('report/report/report','tRptAddrBranch'),
                    'tRptAddV2Desc1'            => language('report/report/report','tRptAddV2Desc1'),
                    'tRptAddV2Desc2'            => language('report/report/report','tRptAddV2Desc2'),
                    // Filter Heard Report
                    'tRptBchFrom'           => language('report/report/report','tRptBchFrom'),
                    'tRptBchTo'             => language('report/report/report','tRptBchTo'),
                    'tRptShopFrom'          => language('report/report/report','tRptShopFrom'),
                    'tRptShopTo'            => language('report/report/report','tRptShopTo'),
                    'tRptDateFrom'          => language('report/report/report','tRptDateFrom'),
                    'tRptDateTo'            => language('report/report/report','tRptDateTo'),
                    'tRptFillterRcvFrom'   => language('report/report/report','tRptPaymentTypeFrom'),
                    'tRptFillterRcvTo'     => language('report/report/report','tRptPaymentTypeTo'),
    
                    // Table Report
                    'tRptBarchCode'         => language('report/report/report','tRptBarchCode'),
                    'tRptBarchName'         => language('report/report/report','tRptBarchName'),
                    'tRptDocDate'           => language('report/report/report','tRptDocDate'),
                    'tRptShopCode'          => language('report/report/report','tRptShopCode'),
                    'tRptShopName'          => language('report/report/report','tRptShopName'),
                    'tRptAmount'            => language('report/report/report','tRptAmount'),
                    'tRptSale'              => language('report/report/report','tRptSale'),
                    'tRptCancelSale'        => language('report/report/report','tRptCancelSale'),
                    'tRptTotalSale'         => language('report/report/report','tRptTotalSale'),
                    'tRptTotalAllSale'      => language('report/report/report','tRptTotalAllSale'),
                    'tRptPayby'             => language('report/report/report','tRptPayby'),
                    'tRptRcvDocumentCode'   => language('report/report/report','tRptRcvDocumentCode'),
                    'tRptDate'              => language('report/report/report','tRptDate'),
                    'tRptRcvTotal'          => language('report/report/report','tRptRcvTotal'),
                    'tRptTaxSaleLockerTotalSub' => language('report/report/report','tRptTaxSaleLockerTotalSub'),
                    'tRptRcvFrom'           => language('report/report/report','tRptRcvFrom'),
                    'tRptRcvTo'             => language('report/report/report','tRptRcvTo'),
                    'tRptTaxSaleLockerFilterDocDateFrom' => language('report/report/report','tRptTaxSaleLockerFilterDocDateFrom'),
                    'tRptTaxSaleLockerFilterDocDateTo'   => language('report/report/report','tRptTaxSaleLockerFilterDocDateTo'),

                    // No Data Report
                    'tRptNoData'            => language('common/main/main', 'tCMNNotFoundData'),
                );  

                // ???????????????????????????????????????????????????????????????????????????????????????????????????
                $aDataWhereRpt  = array(
                    'nPerPage'      => 500000,
                    'nPage'         => $nPage,
                    'tCompName'     => gethostname(),
                    'tRptCode'      => $tRptCode,
                    'tUsrSessionID' => $tUsrSessionID
                );
                $aDataReport    = $this->mRptSaleRecive->FSaMGetDataReport($aDataWhereRpt);
            // ====================================================================================================================================================================
            
            // ??????????????????????????????????????? ????????? ???????????????????????????????????? ==========================================================================================================================================
                // ????????????????????? Font Style
                $aReportFontStyle           = array('font' => array('name' => 'TH Sarabun New'));
                $aStyleRptSizeTitleName     = array('font' => array('size' => 14));
                $aStyleRptSizeCompName      = array('font' => array('size' => 12));
                $aStyleRptSizeAddressFont   = array('font' => array('size' => 12));
                $aStyleRptHeadderTable      = array('font' => array('size' => 12,'color' => array('rgb' => '000000')));
                $aStyleRptDataTable         = array('font' => array('size' => 10,'color' => array('rgb' => '000000')));

                // Initiate PHPExcel cache
                $oCacheMethod   = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
                $aCacheSettings = array(' memoryCacheSize ' => '8000MB', 'cacheTime' => 3600 * 120);
                PHPExcel_Settings::setCacheStorageMethod($oCacheMethod, $aCacheSettings);

                // ??????????????? phpExcel
                $objPHPExcel    = new PHPExcel();

                // A4 ???????????????????????????????????????????????????
                $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

                // Set Font Style Text All In Report
                $objPHPExcel->getActiveSheet()->getStyle('A1:Z1000')->applyFromArray($aReportFontStyle);

                // ??????????????????????????????????????????????????????????????????
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
         

                // ???????????????????????????????????????
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:F1');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1',$aDataTextRef['tTitleReport']);
                $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($aStyleRptSizeTitleName);

            // ====================================================================================================================================================================

            // ??????????????????????????????????????????????????? =======================================================================================================================================================
                if(isset($aDataAddress) && !empty($aDataAddress)){
                    // Company Name
                    $tRptCompName   = (empty($aDataAddress['FTCompName']))? '-' : $aDataAddress['FTCompName'];
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2',$tRptCompName)->getStyle('A2')->applyFromArray($aStyleRptSizeCompName);

                    // Check Vertion Address
                    if($aDataAddress['FTAddVersion'] == 1){
                        // Check Address Line 1
                        $tRptAddV1No        = (empty($aDataAddress['FTAddV1No']))?      '-' : $aDataAddress['FTAddV1No'];
                        $tRptAddV1Road      = (empty($aDataAddress['FTAddV1Road']))?    '-' : $aDataAddress['FTAddV1Road'];
                        $tRptAddV1Soi       = (empty($aDataAddress['FTAddV1Soi']))?     '-' : $aDataAddress['FTAddV1Soi'];
                        $tRptAddressLine1   = $tRptAddV1No.' '.$aDataTextRef['tRptAddrRoad'].' '.$tRptAddV1Road.' '.$aDataTextRef['tRptAddrSoi'].' '.$tRptAddV1Soi;
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3',$tRptAddressLine1)->getStyle('A3')->applyFromArray($aStyleRptSizeAddressFont);

                        // Check Address Line 2
                        $tRptAddV1SubDistName   = (empty($aDataAddress['FTSudName']))?          '-' : $aDataAddress['FTSudName'];
                        $tRptAddV1DstName       = (empty($aDataAddress['FTDstName']))?          '-' : $aDataAddress['FTDstName'];
                        $tRptAddV1PvnName       = (empty($aDataAddress['FTPvnName']))?          '-' : $aDataAddress['FTPvnName'];
                        $tRptAddV1PostCode      = (empty($aDataAddress['FTAddV1PostCode']))?    '-' : $aDataAddress['FTAddV1PostCode'];
                        $tRptAddressLine2       = $tRptAddV1SubDistName.' '.$tRptAddV1DstName.' '.$tRptAddV1PvnName.' '.$tRptAddV1PostCode;
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4',$tRptAddressLine2)->getStyle('A4')->applyFromArray($aStyleRptSizeAddressFont);
                    }else{
                        $tRptAddV2Desc1 = (empty($aDataAddress['FTAddV2Desc1']))? '-' : $aDataAddress['FTAddV2Desc1'];
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3',$tRptAddV2Desc1)->getStyle('A3')->applyFromArray($aStyleRptSizeAddressFont);

                        $tRptAddV2Desc2 = (empty($aDataAddress['FTAddV2Desc2']))? '-' : $aDataAddress['FTAddV2Desc2'];
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4',$tRptAddV2Desc2)->getStyle('A4')->applyFromArray($aStyleRptSizeAddressFont);
                    }

                    // Check Data Telephone Number
                    if(isset($aDataAddress['FTCompTel']) && !empty($aDataAddress['FTCompTel'])){$tRptCompTel = $aDataAddress['FTCompTel'];}else{$tRptCompTel = '-';}
                    $tRptCompTelText    = $aDataTextRef['tRptAddrTel'].' '.$tRptCompTel;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5',$tRptCompTelText)->getStyle('A5')->applyFromArray($aStyleRptSizeAddressFont);

                    // Check Data Fax Number
                    if(isset($aDataAddress['FTCompFax']) && !empty($aDataAddress['FTCompFax'])){$tRptCompFax = $aDataAddress['FTCompFax'];}else{$tRptCompFax = '-';}
                    $tRptCompFaxText    = $aDataTextRef['tRptAddrFax'].' '.$tRptCompFax;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A6',$tRptCompFaxText)->getStyle('A6')->applyFromArray($aStyleRptSizeAddressFont);
                }
            // ====================================================================================================================================================================
            
            // ???????????????????????????????????????????????????????????? =================================================================================================================================================== 
                // Row ????????????????????????????????? Filter
                $nStartRowFillter   = 2;
                $tFillterColumLEFT  = "D";
                $tFillterColumRIGHT = "F";

                // Fillter Rcv (??????????????????????????????????????????)
                if(!empty($aDataFilter['tRcvCodeFrom']) && !empty($aDataFilter['tRcvCodeTo'])){
                    $tRptFilterRcvCodeFrom      = $aDataTextRef['tRptRcvFrom'].' '.$aDataFilter['tRcvNameFrom'];
                    $tRptFilterRcvCodeTo        = $aDataTextRef['tRptRcvTo'].' '.$aDataFilter['tRcvCodeTo'];
                    $tRptTextLeftRightFilter    = $tRptFilterRcvCodeFrom.' '.$tRptFilterRcvCodeTo;
                    $tColumsMergeFilter         = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter,$tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                } 
                 // Fillter Branch (????????????)
                 if(!empty($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeTo'])){
                    $tRptFilterBchCodeFrom      = $aDataTextRef['tRptBchFrom'].' '.$aDataFilter['tBchNameFrom'];
                    $tRptFilterBchCodeTo        = $aDataTextRef['tRptBchTo'].' '.$aDataFilter['tBchNameTo'];
                    $tRptTextLeftRightFilter    = $tRptFilterBchCodeFrom.' '.$tRptFilterBchCodeTo;
                    $tColumsMergeFilter         = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter,$tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }
        

                // Fillter Shop (?????????????????????)
                if(!empty($aDataFilter['tShopCodeFrom']) && !empty($aDataFilter['tShopCodeTo'])){
                    $tRptFilterShopCodeFrom     = $aDataTextRef['tRptShopFrom'].' '.$aDataFilter['tShopNameFrom'];
                    $tRptFilterShopCodeTo       = $aDataTextRef['tRptShopTo'].' '.$aDataFilter['tShopNameTo'];
                    $tRptTextLeftRightFilter    = $tRptFilterShopCodeFrom.' '.$tRptFilterShopCodeTo;
                    $tColumsMergeFilter         = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter,$tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }

                // Fillter DocDate (???????????????????????????????????????????????????)
                if(!empty($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateTo'])){
                    $tRptFilterDocDateFrom      = $aDataTextRef['tRptTaxSaleLockerFilterDocDateFrom'].' '.$aDataFilter['tDocDateFrom'];
                    $tRptFilterDocDateTo        = $aDataTextRef['tRptTaxSaleLockerFilterDocDateTo'].' '.$aDataFilter['tDocDateTo'];
                    $tRptTextLeftRightFilter    = $tRptFilterDocDateFrom.' '.$tRptFilterDocDateTo;
                    $tColumsMergeFilter         = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter,$tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }

            // ====================================================================================================================================================================
            
            // ?????????????????????????????????????????????????????????????????????????????? ===============================================================================================================================================
                $tRptDateTimeExportText = $aDataTextRef['tRptDatePrint'].' '.$dDatePrint.' '.$aDataTextRef['tRptTimePrint'].' '.$dTimePrint;
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:F7');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A7',$tRptDateTimeExportText);
                $objPHPExcel->getActiveSheet()->getStyle("A7")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($aStyleRptSizeAddressFont);
            // ====================================================================================================================================================================
            
            // ?????????????????????????????????????????? =======================================================================================================================================================
                // ??????????????????????????????????????????????????????????????????????????????????????????
                $nStartRowHeadder   = 8;

                // ??????????????? Style Font ?????????????????????????????????
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowHeadder.':F'.$nStartRowHeadder)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowHeadder.':F'.$nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowHeadder.':F'.$nStartRowHeadder)->applyFromArray($aStyleRptHeadderTable);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowHeadder.':F'.$nStartRowHeadder)->applyFromArray(array(
                    'borders' => array(
                        'top'       => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')),
                        'bottom'    => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000'))
                    )
                ));

                // ???????????????????????????????????????????????????????????????
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowHeadder.':B'.$nStartRowHeadder);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nStartRowHeadder,$aDataTextRef['tRptPayby']);

                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C'.$nStartRowHeadder.':D'.$nStartRowHeadder);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$nStartRowHeadder,$aDataTextRef['tRptRcvDocumentCode']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$nStartRowHeadder,$aDataTextRef['tRptDate']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$nStartRowHeadder,$aDataTextRef['tRptRcvTotal']);
            // ====================================================================================================================================================================

            // ?????????????????????????????????????????????????????????????????? =================================================================================================================================================
                // Set Variable Data
                $nStartRowData  = $nStartRowHeadder+1;
                if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])){
                    // ******* Step 1 ????????????????????????????????????????????????????????????????????????????????????????????????
                    $tGrouppingData         = "";
                    $nGroupMember           = "";
                    $nRowPartID             = "";

                    $nSumFooterFCXrcNet     = 0;
                    // echo '<pre>';
                    // print_r($aDataReport['aRptData']); exit();
                    foreach ($aDataReport['aRptData'] as $nKey => $aValue){
                        // ******* Step 3 Set ????????? Value FNRptGroupMember And FNRowPartID
                        $nRowPartID     = $aValue["FNRowPartID"];
                        $nGroupMember   = $aValue["FNRptGroupMember"]; 

                        // ******* Step 4 : Check Groupping Create Row Groupping 
                        if($tGrouppingData != $aValue['FTRcvCode']){
                            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowData.':F'.$nStartRowData);
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nStartRowData,$aValue['FTRcvName']);
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData.':F'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData)->applyFromArray($aStyleRptDataTable);
                            $nStartRowData++;
                        }

                        // ******* Step 5 : Loop Set Data Value 
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowData.':B'.$nStartRowData);
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C'.$nStartRowData.':D'.$nStartRowData);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$nStartRowData,$aValue['FTXshDocNo']);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$nStartRowData,$aValue['FDXrcRefDate']);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$nStartRowData,number_format($aValue['FCXrcNet'],2));
                        
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        $objPHPExcel->getActiveSheet()->getStyle('C'.$nStartRowData.':D'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                        // ******* Step 6 : ?????????????????? Parameter ?????????????????? Summary Sub Footer
                        if($nRowPartID  ==  $nGroupMember){
                            $nStartRowData++;
                            $nSubSumFCXrcNet    = $aValue["FCSdtSubQty"];
                            // Set Color Row Sub Footer
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData.':F'.$nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData.':F'.$nStartRowData)->applyFromArray(array(
                                'borders' => array(
                                    'bottom'    => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000'))
                                )
                            ));

                            // Text Sum Sub
                            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowData.':E'.$nStartRowData);
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nStartRowData,$aDataTextRef['tRptTaxSaleLockerTotalSub'].$aValue['FTRcvName']);
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                            $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData)->applyFromArray($aStyleRptDataTable);
                            $objPHPExcel->getActiveSheet()->getStyle('F'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                            
                            // Value Sum Sub
                            $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('F'.$nStartRowData,number_format($nSubSumFCXrcNet,2));

                            $objPHPExcel->getActiveSheet()->getStyle('F'.$nStartRowData)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                            // $objPHPExcel->setActiveSheetIndex(0)->mergeCells('H'.$nStartRowData.':I'.$nStartRowData);
                            $objPHPExcel->getActiveSheet()->getStyle('F'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        }

                        $nSumFooterFCXrcNet = number_format($aValue["FCXrcNetFooter"],2);

                        // ******* Step 2 Set ????????? Value ????????????????????????????????????
                        $tGrouppingData = $aValue["FTRcvCode"];
                        $nStartRowData++;
                    }

                    // Step 7 : Set Footer Text
                    $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                    $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                    if($nPageNo == $nTotalPage){
                        // Set Color Row Sub Footer
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData.':F'.$nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData.':F'.$nStartRowData)->applyFromArray(array(
                            'borders' => array(
                                'top'       => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')),
                                'bottom'    => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb' => '000000'))
                            )
                        ));

                        // Text Sum Footer
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowData.':E'.$nStartRowData);
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nStartRowData,'?????????????????????????????????');
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData)->applyFromArray($aStyleRptDataTable);

                        // Value Sum Footer
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('F'.$nStartRowData,$nSumFooterFCXrcNet);
                       
                        $objPHPExcel->getActiveSheet()->getStyle('F'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                        $objPHPExcel->getActiveSheet()->getStyle('F1')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                        
                    }

                }else{
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowData.':F'.$nStartRowData);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nStartRowData,$aDataTextRef['tRptNoData']);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData)->applyFromArray($aStyleRptDataTable);
                }          
            // ====================================================================================================================================================================

            // ????????????????????? Content Type Export File Excel ================================================================================================================================
                $tFilename  = 'Report-'.$tRptCode.date("dmYhis").'.xlsx';

                header("Pragma: public");
                header("Expires: 0");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");;
                header("Content-Disposition: attachment;filename=$tFilename");
                header("Content-Transfer-Encoding: binary");

                $objWriter  = new PHPExcel_Writer_Excel2007($objPHPExcel);

                if(!is_dir(APPPATH.'modules/report/assets/exportexcel/')){
                    mkdir(APPPATH.'modules/report/assets/exportexcel/');
                }

                if(!is_dir(APPPATH.'modules/report/assets/exportexcel/'.$tRptCode)){
                    mkdir(APPPATH.'modules/report/assets/exportexcel/'.$tRptCode);
                }

                if(!is_dir(APPPATH.'modules/report/assets/exportexcel/'.$tRptCode.'/'.$tSesUsername)){
                    mkdir(APPPATH.'modules/report/assets/exportexcel/'.$tRptCode.'/'.$tSesUsername);
                }

                $tPathExport    = APPPATH.'modules/report/assets/exportexcel/'.$tRptCode.'/'.$tSesUsername.'/';
                $oFiles = glob($tPathExport.'*');
                foreach($oFiles as $tFile){
                    if(is_file($tFile))
                    unlink($tFile);
                }

                // Check Status Save File Excel
                $objWriter->save($tPathExport.$tFilename);
                $aResponse =  array(
                    'nStaExport'    => 1,
                    'tFileName'     => $tFilename,
                    'tPathFolder'   => 'application/modules/report/assets/exportexcel/'.$tRptCode.'/'.$tSesUsername.'/',
                    'tMessage'      => "Export File Successfully."
                );
            // ====================================================================================================================================================================
        }catch(Exception $Error){
            $aResponse =  array(
                'nStaExport'    => 500,
                'tMessage'      => $Error->getMessage()
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
    public function FSoCChkDataReportInTableTemp($paDataSwitchCase){
        try{
            $aDataCountData = [
                'tCompName'     => $paDataSwitchCase['paDataFilter']['tCompName'],
                'tRptCode'      => $paDataSwitchCase['paDataFilter']['tRptCode'],
                'tSessionID'    => $paDataSwitchCase['paDataFilter']['tSessionID'],
            ];

            $nDataCountPage = $this->mRptSaleRecive->FSnMCountDataReportAll($aDataCountData);

            $aResponse      =  array(
                'nCountPageAll' => $nDataCountPage,
                'nStaEvent'     => 1,
                'tMessage'      => 'Success Count Data All'
            );
        }catch(ErrorException $Error){
            $aResponse =  array(
                'nStaEvent' => 500,
                'tMessage'  => $Error->getMessage()
            );
        }
        echo json_encode($aResponse);
    }

    // Functionality: Send Rabbit MQ Report
    // Parameters:  Function Parameter
    // Creator: 16/08/2019 Wasin(Yoshi)
    // LastUpdate: -
    // Return: object Send Rabbit MQ Report
    // ReturnType: Object
    public function FSvCCallRptExportFile(){
        try{
            $tRptGrpCode    = $this->input->post('ohdRptGrpCode');
            $tRptCode       = $this->input->post('ohdRptCode');
            $tUserCode      = $this->session->userdata('tSesUsername');
            $tSessionID     = $this->session->userdata('tSesSessionID');
            $nLangID        = FCNaHGetLangEdit();
            $tRptExportType = $this->input->post('ohdRptTypeExport');
            $tCompName      = gethostname();
            $dDateSendMQ    = date('Y-m-d');
            $dTimeSendMQ    = date('H:i:s');
            $dDateSubscribe = date('Ymd');
            $dTimeSubscribe = date('His');

            $aDataFilter    = array(
                'tRcvCodeFrom'  => !empty($this->input->post('oetRptRcvCodeFrom'))  ? $this->input->post('oetRptRcvCodeFrom')   : "",
                'tRcvNameFrom'  => !empty($this->input->post('oetRptRcvNameFrom'))  ? $this->input->post('oetRptRcvNameFrom')   : "",
                'tRcvCodeTo'    => !empty($this->input->post('oetRptRcvCodeTo'))    ? $this->input->post('oetRptRcvCodeTo')     : "",
                'tRcvNameTo'    => !empty($this->input->post('oetRptRcvNameTo'))    ? $this->input->post('oetRptRcvNameTo')     : "",
                'tBchCodeFrom'  => !empty($this->input->post('oetRptBchCodeFrom'))  ? $this->input->post('oetRptBchCodeFrom')   : "",
                'tBchNameFrom'  => !empty($this->input->post('oetRptBchNameFrom'))  ? $this->input->post('oetRptBchNameFrom')   : "",
                'tBchCodeTo'    => !empty($this->input->post('oetRptBchCodeTo'))    ? $this->input->post('oetRptBchCodeTo')     : "",
                'tBchNameTo'    => !empty($this->input->post('oetRptBchNameTo'))    ? $this->input->post('oetRptBchNameTo')     : "",
                'tShopCodeFrom' => !empty($this->input->post('oetRptShpCodeFrom'))  ? $this->input->post('oetRptShpCodeFrom')   : "",
                'tShopNameFrom' => !empty($this->input->post('oetRptShpNameFrom'))  ? $this->input->post('oetRptShpNameFrom')   : "",
                'tShopCodeTo'   => !empty($this->input->post('oetRptShpCodeTo'))    ? $this->input->post('oetRptShpCodeTo')     : "",
                'tShopNameTo'   => !empty($this->input->post('oetRptShpNameTo'))    ? $this->input->post('oetRptShpNameTo')     : "",
                'tDocDateFrom'  => !empty($this->input->post('oetRptDocDateFrom'))  ? $this->input->post('oetRptDocDateFrom')   : "",
                'tDocDateTo'    => !empty($this->input->post('oetRptDocDateTo'))    ? $this->input->post('oetRptDocDateTo')     : ""
            );

            // Set Parameter Send MQ
            $tRptQueueName  = 'RPT_'.$tRptGrpCode.'_'.$tRptCode;

            $aDataSendMQ    = [
                'tQueueName'    => $tRptQueueName,
                'aParams'       => [
                    'ptRptCode'         => $tRptCode,
                    'pnPerFile'         => 20000,
                    'ptUserCode'        => $tUserCode,
                    'ptUserSessionID'   => $tSessionID,
                    'pnLngID'           => $nLangID,
                    'ptFilter'          => $aDataFilter,
                    'ptRptExpType'      => $tRptExportType,
                    'ptComName'         => $tCompName,
                    'ptDate'            => $dDateSendMQ,
                    'ptTime'            => $dTimeSendMQ,
                    'ptBchCode'         => (!empty($this->session->userdata('tSesUsrBchCom'))? $this->session->userdata('tSesUsrBchCom') : $this->session->userdata('tSesUsrBchCom'))
                ]
            ];

            FCNxReportCallRabbitMQ($aDataSendMQ);
            
            $aResponse =  array(
                'nStaEvent'         => 1,
                'tMessage'          => 'Success Send Rabbit MQ.',
                'aDataSubscribe'    => array(
                    'ptComName'         => $tCompName,
                    'ptRptCode'         => $tRptCode,
                    'ptUserCode'        => $tUserCode,
                    'ptUserSessionID'   => $tSessionID,
                    'pdDateSubscribe'   => $dDateSubscribe,
                    'pdTimeSubscribe'   => $dTimeSubscribe,
                )
            );
        }catch(Exception $Error){
            $aResponse =  array(
                'nStaEvent' => 500,
                'tMessage'  => $Error->getMessage()
            );
        }
        echo json_encode($aResponse);
    }
}

