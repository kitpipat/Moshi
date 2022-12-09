<?php defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set("Asia/Bangkok");

include APPPATH.'third_party/PHPExcel/Classes/PHPExcel.php';
include APPPATH.'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';
include APPPATH.'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

class cRptProductTransfer extends MX_Controller {

    public function __construct(){
        parent::__construct ();
        $this->load->model('company/company/mCompany');
        $this->load->model('report/reportproducttransfer/mRptProductTransfer');
        $this->load->model('report/report/mReport');
    }

    public function index(){
        $tRptRoute  = $this->input->post('ohdRptRoute');
        $tRptCode   = $this->input->Post('ohdRptCode');
        $tRptTypeExport =  $this->input->post('ohdRptTypeExport');

        $tMerCodeF  = $this->input->post('oetRptMerCodeFrom');
        $tMerCodeT  = $this->input->post('oetRptMerCodeTo');
        if($tMerCodeF != "" && $tMerCodeT != ""){
            $tMerCodeFrom  = $this->input->post('oetRptMerCodeFrom');
            $tMerCodeTo    = $this->input->post('oetRptMerCodeTo');
        }else{
            $tMerCodeFrom  = "";
            $tMerCodeTo    = "";
        }

        $tIP = $this->input->ip_address();
        $tFullHost = gethostbyaddr($tIP);
        $this->tCompName = $tFullHost;

        if(!empty($tRptTypeExport) && !empty($tRptCode)){
            $aDataFilter    = array(
                'tSessionID'     => $this->session->userdata('tSesSessionID'),
                'tCompName'     => $tFullHost,
                'tRptCode'      => $this->input->post('ohdRptCode'),
                'tMerCodeFrom'  => $tMerCodeFrom,
                'tMerCodeTo'    => $tMerCodeTo ,
                'tMerNameFrom'  => !empty($this->input->post('oetRptMerNameFrom'))  ? $this->input->post('oetRptMerNameFrom')   : "",
                'tMerNameTo'    => !empty($this->input->post('oetRptMerNameTo'))    ? $this->input->post('oetRptMerNameTo')     : "",
                'tShpCodeFrom'  => !empty($this->input->post('oetRptShpCodeFrom'))  ? $this->input->post('oetRptShpCodeFrom')   : "",
                'tShpCodeTo'    => !empty($this->input->post('oetRptShpCodeTo'))    ? $this->input->post('oetRptShpCodeTo')     : "",
                'tShpNameFrom'  => !empty($this->input->post('oetRptShpNameFrom'))  ? $this->input->post('oetRptShpNameFrom')   : "",
                'tShpNameTo'    => !empty($this->input->post('oetRptShpNameTo'))    ? $this->input->post('oetRptShpNameTo')     : "",
                'tPosCodeFrom'  => !empty($this->input->post('oetRptPosCodeFrom'))  ? $this->input->post('oetRptPosCodeFrom')   : "",
                'tPosCodeTo'    => !empty($this->input->post('oetRptPosCodeTo'))    ? $this->input->post('oetRptPosCodeTo')     : "",
                'tPosNameFrom'  => !empty($this->input->post('oetRptPosNameFrom'))  ? $this->input->post('oetRptPosNameFrom')   : "",
                'tPosNameTo'    => !empty($this->input->post('oetRptPosNameTo'))    ? $this->input->post('oetRptPosNameTo')     : "",
                'tWahCodeFrom'  => !empty($this->input->post('oetRptWahCodeFrom'))  ? $this->input->post('oetRptWahCodeFrom')   : "",
                'tWahCodeTo'    => !empty($this->input->post('oetRptWahCodeTo'))    ? $this->input->post('oetRptWahCodeTo')     : "",
                'tWahNameFrom'  => !empty($this->input->post('oetRptWahNameFrom'))  ? $this->input->post('oetRptWahCodeFrom')   : "",
                'tWahNameTo'    => !empty($this->input->post('oetRptWahNameTo'))    ? $this->input->post('oetRptWahNameTo')     : "",
                'tDocDateFrom'  => !empty($this->input->post('oetRptDocDateFrom'))  ? $this->input->post('oetRptDocDateFrom')   : "",
                'tDocDateTo'    => !empty($this->input->post('oetRptDocDateTo'))    ? $this->input->post('oetRptDocDateTo')     : "",
                'nLangID'       => FCNaHGetLangEdit(),
            );

            // Execute Stored Procedure
            $this->mRptProductTransfer->FSnMExecStoreCReport($aDataFilter);
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

    // Functionality: ฟังก์ชั่นดูตัวอย่างก่อนพิมพ์ (Report Viewer)
    // Parameters:  Function Parameter
    // Creator: 15/07/2019 Wasin(Yoshi)
    // LastUpdate: 22/07/2019 saharat(Golf)
    // Return: View Report Viewersd
    // ReturnType: View
    public function FSvCCallRptViewBeforePrint($paDataSwitchCase){
        $tRptRoute      = $paDataSwitchCase['ptRptRoute'];
        $tRptCode       = $paDataSwitchCase['ptRptCode'];
        $tRptTypeExport = $paDataSwitchCase['ptRptTypeExport'];
        $aDataFilter    = $paDataSwitchCase['paDataFilter'];
        $nPage          = 1;
        $nLangEdit      = $this->session->userdata("tLangEdit");

        // Get Data Company (ดึงข้อมูลอ้างอิงที่อยู่บริษัท)
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

        // array ข้อมูลภาษาที่เกี่ยวข้องกับรายงาน
        $aDataTextRef   = array(
            'tTitleCompName'        => $tCompName,
            'tTitleReport'          => language('report/report/report','tRptTitleProductTransfer'),
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
            // Table Label
            'tRptDocument'          => language('report/report/report','tRptDocument'),
            'tRptDateDocument'      => language('report/report/report','tRptDateDocument'),
            'tRptFromWareHouse'     => language('report/report/report','tRptFromWareHouse'),  
            'tRptToWareHouse'       => language('report/report/report','tRptToWareHouse'),  
            'tRptAdjStkVDPdtCode'   => language('report/report/report','tRptAdjStkVDPdtCode'),  
            'tRptAdjStkVDPdtName'   => language('report/report/report','tRptAdjStkVDPdtName'),  
            'tRptAdjStkVDLayRow'    => language('report/report/report','tRptAdjStkVDLayRow'),  
            'tRptAdjStkVDLayCol'    => language('report/report/report','tRptAdjStkVDLayCol'),  
            'tRptAdjStkVDLayCol'    => language('report/report/report','tRptAdjStkVDLayCol'),  
            'tRptTransferamount'    => language('report/report/report','tRptTransferamount'),  
            'tRptListener'          => language('report/report/report','tRptListener'),  
            // No Data Report
            'tRptAdjStkNoData'      => language('common/main/main', 'tCMNNotFoundData'),
            // Data Address Branch
            'aDataBranchAddress'    => $aDataBranchAddress
        );

        $tIP = $this->input->ip_address();
        $tFullHost = gethostbyaddr($tIP);
        $this->tCompName = $tFullHost;

        // ข้อมูลสำหรับดึงข้อมูลจากฐานข้อมูล
        $aDataWhereRpt  = array(
            'nPerPage'      => 100,
            'nPage'         => $nPage,
            'tCompName'     => $tFullHost,
            'tRptCode'      => $paDataSwitchCase['ptRptCode'],
            'tSessionID'    => $this->session->userdata('tSesSessionID'),
        );
        $aDataReport        = $this->mRptProductTransfer->FSaMGetDataReport($aDataWhereRpt);

        // ข้อมูล Render Report
        $aDataViewPdt       = array(
            'aDataReport'           => $aDataReport,
            'aDataTextRef'          => $aDataTextRef,
            'aDataCompany'          => $aCompData,
            'aDataFilter'           => $paDataSwitchCase['paDataFilter']
        );

        // Load View Advance Table
        $tViewTest = JCNoHLoadViewAdvanceTable('report/datasources/rptProductTransfer','wRptProductTransferHtml',$aDataViewPdt);

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

    // Functionality: Click Page ดูตัวอย่างก่อนพิมพ์ (Report Viewer)
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

        // Get Data Company (ดึงข้อมูลอ้างอิงที่อยู่บริษัท)
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

        // array ข้อมูลภาษาที่เกี่ยวข้องกับรายงาน
        $aDataTextRef   = array(
            'tTitleCompName'        => $tCompName,
            'tTitleReport'          => language('report/report/report','tRptTitleProductTransfer'),
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
            // Table Label
            'tRptDocument'          => language('report/report/report','tRptDocument'),
            'tRptDateDocument'      => language('report/report/report','tRptDateDocument'),
            'tRptFromWareHouse'     => language('report/report/report','tRptFromWareHouse'),  
            'tRptToWareHouse'       => language('report/report/report','tRptToWareHouse'),  
            'tRptAdjStkVDPdtCode'   => language('report/report/report','tRptAdjStkVDPdtCode'),  
            'tRptAdjStkVDPdtName'   => language('report/report/report','tRptAdjStkVDPdtName'),  
            'tRptAdjStkVDLayRow'    => language('report/report/report','tRptAdjStkVDLayRow'),  
            'tRptAdjStkVDLayCol'    => language('report/report/report','tRptAdjStkVDLayCol'),  
            'tRptAdjStkVDLayCol'    => language('report/report/report','tRptAdjStkVDLayCol'),  
            'tRptTransferamount'    => language('report/report/report','tRptTransferamount'),  
            'tRptListener'          => language('report/report/report','tRptListener'),  
            // No Data Report
            'tRptAdjStkNoData'      => language('common/main/main', 'tCMNNotFoundData'),
            // Data Address Branch
            'aDataBranchAddress'    => $aDataBranchAddress
        );
   
        $tIP = $this->input->ip_address();
        $tFullHost = gethostbyaddr($tIP);
        $this->tCompName = $tFullHost;


        // ข้อมูลสำหรับดึงข้อมูลจากฐานข้อมูล
        $aDataWhereRpt  = array(
            'nPage'         => $nPage,
            'nPerPage'      => 100,
            'tCompName'     => $tFullHost,
            'tRptCode'      => $tRptCode,
            'tSessionID'    => $this->session->userdata('tSesSessionID'),
        );
        $aDataReport        = $this->mRptProductTransfer->FSaMGetDataReport($aDataWhereRpt);

        // ข้อมูล Render Report
        $aDataViewPdt       = array(
            'aDataReport'           => $aDataReport,
            'aDataTextRef'          => $aDataTextRef,
            'aDataCompany'          => $aCompData,
            'aDataFilter'           => $aDataFilter
        );

        // Load View Advance Table

        $tViewTest = JCNoHLoadViewAdvanceTable('report/datasources/rptProductTransfer','wRptProductTransferHtml',$aDataViewPdt);

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

    // Functionality: Function Count Data Report And Calcurate
    // Parameters:  Function Parameter
    // Creator: 22/07/2019 Wasin(Yoshi)
    // LastUpdate: 06/08/2019 saharat(Golf)
    // Return: View Report Viewer
    // ReturnType: View
    public function FSvCCallRptRenderExcel($paDataMain){

        try{
            $tRptRoute      = $paDataMain['ptRptRoute'];
            $tRptCode       = $paDataMain['ptRptCode'];
            $tRptTypeExport = $paDataMain['ptRptTypeExport'];
            $aDataFilter    = $paDataMain['paDataFilter'];
            $nPage          = 1;
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $tSesUsername   = $this->session->userdata('tSesUsername');
            $tUsrSessionID  = $this->session->userdata('tSesSessionID');

            // Check Filter Merchant And 
            $aDataAddress   = array();
            if(isset($aDataFilter['tRptMerchantCode']) && !empty($aDataFilter['tRptMerchantCode'])){
                // Get Data MerChant (ดึงข้อมูลอ้างอิงที่อยู่กลุ่มธุรกิจ)
                $aDataWhereMerChant =   array(
                    'tMerChantCode' => $aDataFilter['tRptMerchantCode'],
                    'nLngID'        => $nLangEdit
                );
                $aDataMerChant  = $this->mRptProductTransfer->FSaMGetDataMerChant($aDataWhereMerChant);
                $aDataAddress   = $aDataMerChant;
            }else{
                $tAPIReq        = "";
                $tMethodReq     = "GET";
                $aDataWhereComp = array('FNLngID' => $nLangEdit);
                $aCompData	= $this->mCompany->FSaMCMPList($tAPIReq,$tMethodReq,$aDataWhereComp);
                if($aCompData['rtCode'] == '1'){
                    $tCompName          = $aCompData['raItems']['rtCmpName'];
                    $tBchCode           = $aCompData['raItems']['rtCmpBchCode'];
                    $aDataAddress       = $this->mReport->FSaMRptGetDataAddressByBranchComp($tBchCode,$nLangEdit);
                }else{
                    $tCompName          = "-";
                    $tBchCode           = "-";
                    $aDataAddress       = array();
                }
            }


        // array ข้อมูลภาษาที่เกี่ยวข้องกับรายงาน
        $aDataTextRef   = array(
            'tTitleCompName'        => $tCompName,
            'tTitleReport'          => language('report/report/report','tRptTitleProductTransfer'),
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
            // Table Label
            'tRptDocument'          => language('report/report/report','tRptDocument'),
            'tRptDateDocument'      => language('report/report/report','tRptDateDocument'),
            'tRptFromWareHouse'     => language('report/report/report','tRptFromWareHouse'),  
            'tRptToWareHouse'       => language('report/report/report','tRptToWareHouse'),  
            'tRptAdjStkVDPdtCode'   => language('report/report/report','tRptAdjStkVDPdtCode'),  
            'tRptAdjStkVDPdtName'   => language('report/report/report','tRptAdjStkVDPdtName'),  
            'tRptAdjStkVDLayRow'    => language('report/report/report','tRptAdjStkVDLayRow'),  
            'tRptAdjStkVDLayCol'    => language('report/report/report','tRptAdjStkVDLayCol'),  
            'tRptAdjStkVDLayCol'    => language('report/report/report','tRptAdjStkVDLayCol'),  
            'tRptTransferamount'    => language('report/report/report','tRptTransferamount'),  
            'tRptListener'          => language('report/report/report','tRptListener'), 
            'tRptTaxSaleLockerFilterMerChantFrom' => language('report/report/report','tRptTaxSaleLockerFilterMerChantFrom'), 
            'tRptTaxSaleLockerFilterMerChantTo' => language('report/report/report','tRptTaxSaleLockerFilterMerChantTo'), 
            // No Data Report
            'tRptTaxSaleLockerNoData'               => language('common/main/main', 'tCMNNotFoundData'),
            'tRptTaxSaleLockerTotalSub'             => language('report/report/report','tRptTaxSaleLockerTotalSub'),
            'tRptTaxSaleLockerTotalFooter'          => language('report/report/report','tRptTaxSaleLockerTotalFooter'),
            // Filter Text Label
            'tRptTaxSaleLockerFilterShopFrom'       => language('report/report/report','tRptTaxSaleLockerFilterShopFrom'),
            'tRptTaxSaleLockerFilterShopTo'         => language('report/report/report','tRptTaxSaleLockerFilterShopTo'),
            'tRptTaxSaleLockerFilterDocDateFrom'    => language('report/report/report','tRptTaxSaleLockerFilterDocDateFrom'),
            'tRptTaxSaleLockerFilterDocDateTo'      => language('report/report/report','tRptTaxSaleLockerFilterDocDateTo'),
            'tRptproducttransferFilterPosFrom'      => language('report/report/report','tRptproducttransferFilterPosFrom'),
            'tRptproducttransferFilterPosTo'      => language('report/report/report','tRptproducttransferFilterPosTo'),
        );

        /** ================================== Begin Init Variable Excel ================================== */
            $tReportName    = $aDataTextRef['tTitleReport'];
            $dDateExport    = date('Y-m-d');
            $tTime          = date('H:i:s');
        /** ===================================== End Init Variable ======================================= */

        /** ======================================= Begin Get Data ======================================== */
            // $aCompInfoParams    = ['nLngID' => FCNaHGetLangEdit()];
            // $aCompData          = FCNaGetCompanyInfo($aCompInfoParams);
            // ข้อมูลสำหรับดึงข้อมูลจากฐานข้อมูล
            $tIP = $this->input->ip_address();
            $tFullHost = gethostbyaddr($tIP);
            $this->tCompName = $tFullHost;

            $aDataWhereRpt  = array(
                'nPerPage'      => 100,
                'nPage'         => $nPage,
                'tCompName'     => $tFullHost,
                'tRptCode'      => $tRptCode,
                'tSessionID'    => $tUsrSessionID
            );
            $aDataReport    = $this->mRptProductTransfer->FSaMGetDataReport($aDataWhereRpt);
        /** =============================================================================================== */

        // ตั้งค่า Font Style
        $aStyleRptFont              = array('font' => array('name' => 'TH Sarabun New'));
        $aStyleRptSizeTitleName     = array('font' => array('size' => 14));
        $aStyleRptSizeCompName      = array('font' => array('size' => 12));
        $aStyleRptSizeAddressFont   = array('font' => array('size' => 12));
        $aStyleRptHeadderTable      = array('font' => array('size' => 12,'color' => array('rgb' => '000000')));
        $aStyleRptDataTable         = array('font' => array('size' => 10,'color' => array('rgb' => '000000')));

        // Initiate PHPExcel cache
        $oCacheMethod   = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        $aCacheSettings = array(' memoryCacheSize ' => '8000MB', 'cacheTime' => 3600 * 120);
        PHPExcel_Settings::setCacheStorageMethod($oCacheMethod, $aCacheSettings);
        
        // เริ่ม phpExcel
        $objPHPExcel    = new PHPExcel();
        
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
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1',$tReportName);
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($aStyleRptSizeTitleName);
        
        // Check Address Data
        if(isset($aDataAddress) && !empty($aDataAddress)){
            // Company Name
            $tRptCompName       = (empty($aDataAddress['FTCompName']))? '-' : $aDataAddress['FTCompName'];
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

        // ======================================================================== Filter Data Report ======================================================================== 
            // Row เริ่มต้นของ Filter
            $nStartRowFillter   = 2;
            $tFillterColumLEFT  = "D";
            $tFillterColumRIGHT = "H";

                // Fillter MerChant (กลุ่มธุรกิจ)
                if(!empty($aDataFilter['tMerCodeFrom']) && !empty($aDataFilter['tMerCodeTo'])){
                    $tRptFilterShopCodeFrom     = $aDataTextRef['tRptTaxSaleLockerFilterMerChantFrom'].' '.$aDataFilter['tMerNameFrom'];
                    $tRptFilterShopCodeTo       = $aDataTextRef['tRptTaxSaleLockerFilterMerChantTo'].' '.$aDataFilter['tMerNameTo'];
                    $tRptTextLeftRightFilter    = $tRptFilterShopCodeFrom.' '.$tRptFilterShopCodeTo;
                    $tColumsMergeFilter         = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter,$tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }


                // Fillter Shop (ร้านค้า)
                if(!empty($aDataFilter['tShpCodeFrom']) && !empty($aDataFilter['tShpCodeTo'])){
                    $tRptFilterShopCodeFrom     = $aDataTextRef['tRptTaxSaleLockerFilterShopFrom'].' '.$aDataFilter['tShpNameFrom'];
                    $tRptFilterShopCodeTo       = $aDataTextRef['tRptproducttransferFilterPosTo'].' '.$aDataFilter['tShpNameTo'];
                    $tRptTextLeftRightFilter    = $tRptFilterShopCodeFrom.' '.$tRptFilterShopCodeTo;
                    $tColumsMergeFilter         = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;
                    
                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter,$tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }

                // Fillter Pos (เครื่องจุดขาย)
                if(!empty($aDataFilter['tPosCodeFrom']) && !empty($aDataFilter['tPosCodeTo'])){
                    $tRptFilterDocDateFrom      = $aDataTextRef['tRptproducttransferFilterPosFrom'].' '.$aDataFilter['tPosNameFrom'];
                    $tRptFilterDocDateTo        = $aDataTextRef['tRptTaxSaleLockerFilterDocDateTo'].' '.$aDataFilter['tPosNameTo'];
                    $tRptTextLeftRightFilter    = $tRptFilterDocDateFrom.' '.$tRptFilterDocDateTo;
                    $tColumsMergeFilter         = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter,$tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }

                // Fillter WareHouse (คลังสินค้า)
                if(!empty($aDataFilter['tWahCodeFrom']) && !empty($aDataFilter['tWahCodeTo'])){
                    $tRptFilterDocDateFrom      = $aDataTextRef['tRptFromWareHouse'].' '.$aDataFilter['tWahNameFrom'];
                    $tRptFilterDocDateTo        = $aDataTextRef['tRptToWareHouse'].' '.$aDataFilter['tWahNameTo'];
                    $tRptTextLeftRightFilter    = $tRptFilterDocDateFrom.' '.$tRptFilterDocDateTo;
                    $tColumsMergeFilter         = $tFillterColumLEFT.$nStartRowFillter.':'.$tFillterColumRIGHT.$nStartRowFillter;

                    $objPHPExcel->setActiveSheetIndex(0)->mergeCells($tColumsMergeFilter);
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($tFillterColumLEFT.$nStartRowFillter,$tRptTextLeftRightFilter);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $objPHPExcel->getActiveSheet()->getStyle($tFillterColumLEFT.$nStartRowFillter)->applyFromArray($aStyleRptSizeAddressFont);
                    $nStartRowFillter += 1;
                }
                
            
            // Fillter DocDate (วันที่สร้างเอกสาร)
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

        // ========================================================================== Date Time Print ========================================================================= 
            $tRptDateTimeExportText = $aDataTextRef['tDatePrint'].' '.$dDateExport.' '.$aDataTextRef['tTimePrint'].' '.$tTime;
            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:K7');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A7',$tRptDateTimeExportText);
            $objPHPExcel->getActiveSheet()->getStyle("A7")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($aStyleRptSizeAddressFont);
        // ====================================================================================================================================================================

        // ==================================================================== หัวตารางรายงาน ==================================================================================
            // กำหนดจุดเริ่มต้นของแถวหัวตาราง
            $nStartRowHeadder   = 8;

            // กำหนด Style Font ของหัวตาราง
            $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowHeadder.':J'.$nStartRowHeadder)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
            $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowHeadder.':J'.$nStartRowHeadder)->applyFromArray($aStyleRptHeadderTable);
            $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowHeadder.':J'.$nStartRowHeadder)->applyFromArray(array(
                'borders' => array(
                    'top'       => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')),
                    'bottom'    => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000'))
                )
            ));
         
            // กำหนดข้อมูลลงหัวตาราง
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$nStartRowHeadder,$aDataTextRef['tRptDocument'])
            ->setCellValue('B'.$nStartRowHeadder,$aDataTextRef['tRptDateDocument'])
            ->setCellValue('C'.$nStartRowHeadder,$aDataTextRef['tRptFromWareHouse'])
            ->setCellValue('D'.$nStartRowHeadder,$aDataTextRef['tRptToWareHouse'])
            ->setCellValue('E'.$nStartRowHeadder,$aDataTextRef['tRptAdjStkVDPdtCode'])
            ->setCellValue('F'.$nStartRowHeadder,$aDataTextRef['tRptAdjStkVDPdtName'])
            ->setCellValue('G'.$nStartRowHeadder,$aDataTextRef['tRptAdjStkVDLayRow'])
            ->setCellValue('H'.$nStartRowHeadder,$aDataTextRef['tRptAdjStkVDLayCol'])
            ->setCellValue('I'.$nStartRowHeadder,$aDataTextRef['tRptTransferamount'])
            ->setCellValue('J'.$nStartRowHeadder,$aDataTextRef['tRptListener']);

            // Alignment ของหัวตาราง
            $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowHeadder.':G'.$nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('H'.$nStartRowHeadder.':K'.$nStartRowHeadder)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        // ====================================================================================================================================================================

        // ==================================================================== ข้อมูลรายละเอียดรายงาน ===========================================================================
            // Set Variable Data

            $nStartRowData  =   $nStartRowHeadder+1;
            if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])){

                // ******* Step 1 ประกาศตัวแปรใช้ในการเช็คเงื่อนไข
                $tGrouppingData         = "";
                $nRowID                 = "";
                $nGroupMember           = "";

                $nSubSumXshAmt          = 0;
                $nSubSumXshAmtV         = 0;
                $nSubSumXshAmtNV        = 0;
                $nSubSumGrandTotal      = 0;

                $nSumFooterXshAmt       = 0;
                $nSumFooterXshAmtV      = 0;
                $nSumFooterXshAmtNV     = 0;
                $nSumFooterGrandTotal   = 0;
                foreach ($aDataReport['aRptData'] as $nKey => $aValue){
                    // ******* Step 3 Set ค่า Value FNRptGroupMember And FNRowPartID
                    $nRowID         = $aValue["RowID"];
                    $nGroupMember   = $aValue["FNRptGroupMember"]; 

                    // ******* Step 4 : Check Groupping Create Row Groupping 
                    if($tGrouppingData != $aValue['FTXthDocNo']){
                        $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$nStartRowData,$aValue['FTXthDocNo'])
                        ->setCellValue('B'.$nStartRowData,$aValue['FDXthDocDate'])
                        ->setCellValue('C'.$nStartRowData,$aValue['FTXthWhFrmName'])
                        ->setCellValue('D'.$nStartRowData,$aValue['FTXthWhToName'])
                        ->setCellValue('E'.$nStartRowData,'')
                        ->setCellValue('F'.$nStartRowData,'')
                        ->setCellValue('G'.$nStartRowData,'')
                        ->setCellValue('H'.$nStartRowData,'')
                        ->setCellValue('I'.$nStartRowData,'')
                        ->setCellValue('J'.$nStartRowData,$aValue['FTXtdUsrKey']);
                        $nStartRowData++;
                    }

                    // ******* Step 5 : Loop Set Data Value 
                    $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$nStartRowData,'')
                    ->setCellValue('B'.$nStartRowData,'')
                    ->setCellValue('C'.$nStartRowData,'')
                    ->setCellValue('D'.$nStartRowData,'')
                    ->setCellValue('E'.$nStartRowData,!empty($aValue['FTPdtCode'])?  $aValue['FTPdtCode']    : '-')
                    ->setCellValue('F'.$nStartRowData,!empty($aValue['FTPdtName'])?  $aValue['FTPdtName']    : '-')
                    ->setCellValue('G'.$nStartRowData,!empty($aValue['FNLayRow'])? $aValue['FNLayRow']       : '-')
                    ->setCellValue('H'.$nStartRowData,!empty($aValue['FNLayCol'])?  $aValue['FNLayCol']      : '-')
                    ->setCellValue('I'.$nStartRowData,!empty($aValue['FCXtdQty'])?  $aValue['FCXtdQty']      : '-')
                    ->setCellValue('J'.$nStartRowData,'');

                    // ->setCellValue('I'.$nStartRowData,number_format($aValue["FCXshAmtV"],2))
                    // ->setCellValue('J'.$nStartRowData,number_format($aValue["FCXshAmtNV"],2))
                    // ->setCellValue('K'.$nStartRowData,number_format($aValue["FCXshGrandTotal"],2));

                    // $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData.':G'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('G'.$nStartRowData.':I'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

                    // ******* Step 6 : เตรียม Parameter สำหรับ Summary Sub Footer
                    // if($nRowID  ==  $nGroupMember){
                    //     $nStartRowData++;
                        // $nSubSumXshAmt      = $aValue["FCXshAmt_SubTotal"];
                        // $nSubSumXshAmtV     = $aValue["FCXshAmtV_SubTotal"];
                        // $nSubSumXshAmtNV    = $aValue["FCXshAmtNV_SubTotal"];
                        // $nSubSumGrandTotal  = $aValue["FCXshGrandTotal_SubTotal"];

                        // Set Color Row Sub Footer
                        // $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData.':K'.$nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                        // $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData.':K'.$nStartRowData)->applyFromArray(array(
                        //     'borders' => array(
                        //         // 'top'       => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')),
                        //         'bottom'    => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000'))
                        //     )
                        // ));

                        // LEFT 
                        // $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowData.':G'.$nStartRowData);
                        // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nStartRowData,$aDataTextRef['tRptTaxSaleLockerTotalSub']);
                        // $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        // $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData)->applyFromArray($aStyleRptDataTable);

                        // RIGHT
                        // $objPHPExcel->setActiveSheetIndex(0)
                        // ->setCellValue('H'.$nStartRowData,number_format($nSubSumXshAmt,2))
                        // ->setCellValue('I'.$nStartRowData,number_format($nSubSumXshAmtV,2))
                        // ->setCellValue('J'.$nStartRowData,number_format($nSubSumXshAmtNV,2))
                        // ->setCellValue('K'.$nStartRowData,number_format($nSubSumGrandTotal,2));
                        // $nStartRowData++;
                    // }

                    // $nSumFooterXshAmt       = number_format($aValue["FCXshAmt_Footer"],2);
                    // $nSumFooterXshAmtV      = number_format($aValue["FCXshAmtV_Footer"],2);
                    // $nSumFooterXshAmtNV     = number_format($aValue["FCXshAmtNV_Footer"],2);
                    // $nSumFooterGrandTotal   = number_format($aValue["FCXshGrandTotal_Footer"],2);

                    // ******* Step 2 Set ค่า Value ให้กับตัวแปร
                    $tGrouppingData = $aValue["FTXthDocNo"];
                    $nStartRowData++;
                }

                // Step 7 : Set Footer Text
                // $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                // $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                // if($nPageNo == $nTotalPage){
                //     $nStartRowData--;
                //     // Set Color Row Sub Footer
                //     $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData.':K'.$nStartRowData)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('CFE2F3');
                //     $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData.':K'.$nStartRowData)->applyFromArray(array(
                //         'borders' => array(
                //             'top'       => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000')),
                //             'bottom'    => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE,'color' => array('rgb' => '000000'))
                //         )
                //     ));

                //     // LEFT 
                //     $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowData.':G'.$nStartRowData);
                //     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nStartRowData,$aDataTextRef['tRptTaxSaleLockerTotalFooter']);
                //     $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                //     $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData)->applyFromArray($aStyleRptDataTable);

                //     // RIGHT
                //     $objPHPExcel->setActiveSheetIndex(0)
                //     ->setCellValue('H'.$nStartRowData,number_format($nSumFooterXshAmt,2))
                //     ->setCellValue('I'.$nStartRowData,number_format($nSumFooterXshAmtV,2))
                //     ->setCellValue('J'.$nStartRowData,number_format($nSumFooterXshAmtNV,2))
                //     ->setCellValue('K'.$nStartRowData,number_format($nSumFooterGrandTotal,2));
                // }

            }else{
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$nStartRowData.':K'.$nStartRowData);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$nStartRowData,$aDataTextRef['tRptTaxSaleLockerNoData']);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$nStartRowData)->applyFromArray($aStyleRptDataTable);
            }
        // ====================================================================================================================================================================

        // Export File Excel
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

            $nDataCountPage = $this->mRptProductTransfer->FSnMCountDataReportAll($aDataCountData);

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
    // LastUpdate: 21/08/2019 Saharat(Golf)
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

            $tIP = $this->input->ip_address();
            $tFullHost = gethostbyaddr($tIP);
            $this->tCompName = $tFullHost;

            $tCompName      = $tFullHost;
            $dDateSendMQ    = date('Y-m-d');
            $dTimeSendMQ    = date('H:i:s');
            $dDateSubscribe = date('Ymd');
            $dTimeSubscribe = date('His');
            $tMerCodeF  = $this->input->post('oetRptMerCodeFrom');
            $tMerCodeT  = $this->input->post('oetRptMerCodeTo');
            if($tMerCodeF != "" && $tMerCodeT != ""){
                $tMerCodeFrom  = $this->input->post('oetRptMerCodeFrom');
                $tMerCodeTo    = $this->input->post('oetRptMerCodeTo');
            }else{
                $tMerCodeFrom  = "";
                $tMerCodeTo    = "";
            }
            $aDataFilter    = array(
                'tMerCodeFrom'  => $tMerCodeFrom,
                'tMerCodeTo'    => $tMerCodeTo ,
                'tMerNameFrom'  => !empty($this->input->post('oetRptMerNameFrom'))  ? $this->input->post('oetRptMerNameFrom')   : "",
                'tMerNameTo'    => !empty($this->input->post('oetRptMerNameTo'))    ? $this->input->post('oetRptMerNameTo')     : "",
                'tShpCodeFrom'  => !empty($this->input->post('oetRptShpCodeFrom'))  ? $this->input->post('oetRptShpCodeFrom')   : "",
                'tShpCodeTo'    => !empty($this->input->post('oetRptShpCodeTo'))    ? $this->input->post('oetRptShpCodeTo')     : "",
                'tShpNameFrom'  => !empty($this->input->post('oetRptShpNameFrom'))  ? $this->input->post('oetRptShpNameFrom')   : "",
                'tShpNameTo'    => !empty($this->input->post('oetRptShpNameTo'))    ? $this->input->post('oetRptShpNameTo')     : "",
                'tPosCodeFrom'  => !empty($this->input->post('oetRptPosCodeFrom'))  ? $this->input->post('oetRptPosCodeFrom')   : "",
                'tPosCodeTo'    => !empty($this->input->post('oetRptPosCodeTo'))    ? $this->input->post('oetRptPosCodeTo')     : "",
                'tPosNameFrom'  => !empty($this->input->post('oetRptPosNameFrom'))  ? $this->input->post('oetRptPosNameFrom')   : "",
                'tPosNameTo'    => !empty($this->input->post('oetRptPosNameTo'))    ? $this->input->post('oetRptPosNameTo')     : "",
                'tWahCodeFrom'  => !empty($this->input->post('oetRptWahCodeFrom'))  ? $this->input->post('oetRptWahCodeFrom')   : "",
                'tWahCodeTo'    => !empty($this->input->post('oetRptWahCodeTo'))    ? $this->input->post('oetRptWahCodeTo')     : "",
                'tWahNameFrom'  => !empty($this->input->post('oetRptWahNameFrom'))  ? $this->input->post('oetRptWahCodeFrom')   : "",
                'tWahNameTo'    => !empty($this->input->post('oetRptWahNameTo'))    ? $this->input->post('oetRptWahNameTo')     : "",
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

