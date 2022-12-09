<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cVendingSaleInfor extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('monitordashboard/vending/mVendingSaleInfor');
    }

    public function index(){
        $this->load->view("monitordashboard/pos/possaleinfor/wPosSaleInfor");
    }
    
    
    // public function FSxCGetInforDashBoard(){
    //     $tConditionWritGraph = $this->input->post("tConditionWritGraph");
    //     $tTypeWriteGraph = $this->input->post("tTypeWriteGraph");
    //     $tWriteGraphCompare = $this->input->post("tWriteGraphCompare");
    //     $dDateFilter = $this->input->post("dDateFilter");
    //     $tTypeCalDisplayGraph = $this->input->post("tTypeCalDisplayGraph");
    //     $aSendToFillter = array(
    //         "tConditionWritGraph" => $tConditionWritGraph,
    //         "tTypeWriteGraph" => $tTypeWriteGraph,
    //         "tWriteGraphCompare" => $tWriteGraphCompare,
    //         "dDateFilter" => $dDateFilter,
    //         "tTypeCalDisplayGraph"=>$tTypeCalDisplayGraph
    //     );
    //     $aInforDB = $this->mPosSaleInfor->FSxMGetALLBillSale($aSendToFillter);
    //     $tFileName= APPPATH."modules\monitordashboard\assets\systemtmp\dbtmp\db_tmp_graph.txt";
    //     $oHandle = fopen($tFileName, 'w');
    //     rewind($oHandle);
    //     fwrite($oHandle, json_encode($aInforDB));
    //     fclose($oHandle);
    //     $tFileName= APPPATH."modules\monitordashboard\assets\systemtmp\dbtmp\db_tmp_filter.txt";
    //     $oHandle = fopen($tFileName, 'w');
    //     rewind($oHandle);
    //     fwrite($oHandle, json_encode($aSendToFillter));
    //     fclose($oHandle);
    //     $aNumSaleBill = $this->mPosSaleInfor->FSxMGetNumBillSale($aSendToFillter);
    //     $aNumReturnBill = $this->mPosSaleInfor->FSxMGetNumBillReturn($aSendToFillter);
    //     $aNumBill = array(
    //         "aNumSaleBill"=>$aNumSaleBill,
    //         "aNumReturnBill"=>$aNumReturnBill
    //     );
    //     echo json_encode($aNumBill);
    // }

    // public function FSxCDisplaySaleChartInfor(){
    //     $tFileName= APPPATH."modules\monitordashboard\assets\systemtmp\dbtmp\db_tmp_graph.txt";
    //     $oHandle = fopen($tFileName, 'r');
    //     $aInforDB = json_decode(fread($oHandle,filesize($tFileName)), true);
    //     fclose($oHandle);
    //     $aInforDBForBar = $aInforDB;
    //     $aInforDBForPie = $aInforDB;
    //     require_once APPPATH.'modules\monitordashboard\datasources\graphPosSaleInfor\graphPosSaleInfor.php';
        
    //     /* bar chart */
    //     if($aInforDBForBar==false){
    //         $aInforDBForBar = array(array("FTType"=>"","FCValue"=>"0"));
    //     }
    //     $oGraphBarChart  = new graphPosSaleInfor(array(
    //         'aDataReturn'  => $aInforDBForBar
    //     ));
    //     $oGraphBarChart->run();
    //     $tHtmlViewBarChart   = $oGraphBarChart->render('wGraphPosSaleInforBar',true); 
    //     /* end bar chart */

    //     /* pie chart */
    //     if($aInforDBForPie){
    //         $bCheckNoErrorValueZeroForPieChart = false;
    //         for($nI=0;$nI<count($aInforDBForPie);$nI++){
    //             if($aInforDBForPie[$nI]["FCValue"]!=0){
    //                 $bCheckNoErrorValueZeroForPieChart = true;
    //                 break;
    //             }
    //         }
    //         if($bCheckNoErrorValueZeroForPieChart==false){
    //             $aInforDBForPie = array(array("FTType"=>"ไม่มีสินค้า","FCValue"=>"1"));
    //         }
    //     }else{
    //         $aInforDBForPie = array(array("FTType"=>"ไม่มีสินค้า","FCValue"=>"1"));
    //     }
    //     $oGraphCircleChart  = new graphPosSaleInfor(array(
    //         'aDataReturn'  => $aInforDBForPie
    //     ));
    //     $oGraphCircleChart->run();
    //     $tHtmlViewCircleChart   = $oGraphCircleChart->render('wGraphPosSaleInforCircle',true); 
    //     /* end pie chart */
        
    //     $aData = array(
    //         'tHtmlViewBarChart'=>$tHtmlViewBarChart,
    //         'tHtmlViewCircleChart'=>$tHtmlViewCircleChart
    //     );
    //     $this->load->view("monitordashboard/pos/possaleinfor/chart/wChartSaleinfor",$aData);
    // }

    // public function FSxCAddInforByMQ(){
        

        
    // }

    // public function FSxCLoadPdtBestSale(){
    //     $aListBestSalePdt = $this->mPosSaleInfor->FSxMGetListBestSalePdt($this->input->post("dDateFilter"));
    //     echo json_encode($aListBestSalePdt);
    // }
}
