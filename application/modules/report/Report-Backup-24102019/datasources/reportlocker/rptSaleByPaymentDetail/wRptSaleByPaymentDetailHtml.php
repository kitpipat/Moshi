<?php
    $aDataReport    = $aDataViewRpt['aDataReport'];
    $aDataTextRef   = $aDataViewRpt['aDataTextRef'];
    $aDataFilter    = $aDataViewRpt['aDataFilter'];
?>

<style>
    .xCNFooterRpt {
        border-bottom : 7px double #ddd;
    }

    .table thead th, .table>thead>tr>th, .table tbody tr, .table>tbody>tr>td {
        border: 0px transparent !important;
    }

    .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
        border-top: 1px solid black !important;
        border-bottom : 1px solid black !important;
        background-color: #CFE2F3 !important;
    }

    .table>tbody>tr.xCNTrSubFooter{
        border-top: 1px solid black !important;
        border-bottom : 1px solid black !important;
        background-color: #CFE2F3 !important;
    }

    .table>tbody>tr.xCNTrFooter{
        border-top: 1px solid black !important;
        background-color: #CFE2F3 !important;
        border-bottom : 6px double black !important;
    }
    .table tbody tr.xCNHeaderGroup, .table>tbody>tr.xCNHeaderGroup>td {
        color: #232C3D !important;
        font-size: 18px !important;
        font-weight: 600;
    }
    .table>tbody>tr.xCNHeaderGroup>td:nth-child(4), .table>tbody>tr.xCNHeaderGroup>td:nth-child(5) {
        text-align: right;
    }
</style>

<div id="odvRptSaleByPaymentDetailHtml">
    <div class="container-fluid xCNLayOutRptHtml">
        
        <div class="xCNHeaderReport">
            
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-center">
                        <label class="xCNRptTitle"><?php echo $aDataTextRef['tTitleReport'];?></label>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <?php if(isset($aCompanyInfo) && !empty($aCompanyInfo)) { ?>
                    
                        <div class="text-left">
                            <label class="xCNRptLabel"><?=$aCompanyInfo['FTCmpName']?></label>
                        </div>

                        <?php if($aCompanyInfo['FTAddVersion'] == '1'){ // ที่อยู่แบบแยก ?>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?=$aCompanyInfo['FTAddV1No'] . ' ' . $aCompanyInfo['FTAddV1Village'] . ' ' . $aCompanyInfo['FTAddV1Road'] . ' ' . $aCompanyInfo['FTAddV1Soi']?></label>
                            </div>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?=$aCompanyInfo['FTSudName'] . ' ' . $aCompanyInfo['FTDstName'] . ' ' . $aCompanyInfo['FTPvnName'] . ' ' . $aCompanyInfo['FTAddV1PostCode']?></label>
                            </div>
                        <?php } ?>

                        <?php if($aCompanyInfo['FTAddVersion'] == '2'){ // ที่อยู่แบบรวม ?>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?=$aCompanyInfo['FTAddV2Desc1']?></label>
                            </div>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?=$aCompanyInfo['FTAddV2Desc2']?></label>
                            </div>
                        <?php } ?>

                        <div class="text-left">
                            <label class="xCNRptLabel"><?=$aDataTextRef['tRptSaleByPaymentDetailTel'] . $aCompanyInfo['FTCmpTel']?> <?=$aDataTextRef['tRptSaleByPaymentDetailFax'] . $aCompanyInfo['FTCmpFax']?></label>
                        </div>
                        <div class="text-left">
                            <label class="xCNRptLabel"><?=$aDataTextRef['tRptSaleByPaymentDetailBch'] . $aCompanyInfo['FTBchName']?></label>
                        </div>
                    
                    <?php } ?>
                </div>
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 report-filter">
                    <?php if( (isset($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeFrom'])) && (isset($aDataFilter['tBchCodeTo']) && !empty($aDataFilter['tBchCodeTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptSaleByPaymentDetailFilterBchFrom'].' '.$aDataFilter['tBchNameFrom'];?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptSaleByPaymentDetailFilterBchTo'].' '.$aDataFilter['tBchNameTo'];?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ;?>

                    <?php if((isset($aDataFilter['tShpCodeFrom']) && !empty($aDataFilter['tShpCodeFrom'])) && (isset($aDataFilter['tShpCodeTo']) && !empty($aDataFilter['tShpCodeTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล ร้านค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptSaleByPaymentDetailFilterShopFrom'].' '.$aDataFilter['tShpNameFrom'];?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptSaleByPaymentDetailFilterShopTo'].' '.$aDataFilter['tShpNameTo'];?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                         
                    <?php if((isset($aDataFilter['tPosCodeFrom']) && !empty($aDataFilter['tPosCodeFrom'])) && (isset($aDataFilter['tPosCodeTo']) && !empty($aDataFilter['tPosCodeTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล เครื่องจุดขาย ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptSaleByPaymentDetailFilterPosFrom'].' '.$aDataFilter['tPosNameFrom'];?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptSaleByPaymentDetailFilterPosTo'].' '.$aDataFilter['tPosNameTo'];?></label>
                                </div>
                            </div> 
                        </div>
                    <?php endif;?>
                        
                    <?php if((isset($aDataFilter['tRcvCodeFrom']) && !empty($aDataFilter['tRcvCodeFrom'])) && (isset($aDataFilter['tRcvCodeTo']) && !empty($aDataFilter['tRcvCodeTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล ประเภทการชำระ ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptSaleByPaymentDetailFilterPayTypeFrom'].' '.$aDataFilter['tRcvNameFrom'];?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptSaleByPaymentDetailFilterPayTypeTo'].' '.$aDataFilter['tRcvNameTo'];?></label>
                                </div>
                            </div> 
                        </div>
                    <?php endif;?>
                        
                    <?php if((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่สร้างเอกสาร ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptSaleByPaymentDetailFilterDocDateFrom'].' '.$aDataFilter['tDocDateFrom'];?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptSaleByPaymentDetailFilterDocDateTo'].' '.$aDataFilter['tDocDateTo'];?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <label class="xCNRptLabel"><?php echo $aDataTextRef['tDatePrint'].' '.date('d/m/Y').' '.$aDataTextRef['tTimePrint'].' '.date('H:i:s');?></label>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="xCNContentReport">
            <div id="odvRptTableAdvance" class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptSaleByPaymentDetailDocNo'];?></th>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptSaleByPaymentDetailDateAndLocker'];?></th>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptSaleByPaymentDetailPayTypeAndDocRef'];?></th>
                            <th nowrap class="text-right"><?php echo $aDataTextRef['tRptSaleByPaymentDetailPayment'];?></th>
                            <th nowrap class="text-right"><?php echo $aDataTextRef['tRptSaleByPaymentDetailPaymentTotal'];?></th>
                        </tr>
                    </thead>
                    <tbody>
                         
                    <?php if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>  
                            <?php 
                            $paFooterSumData1 = 0;
                            $paFooterSumData2 = 0;
                            $paFooterSumData3 = 0;
                            ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>
                                <?php
                                    // Step 1 เตรียม Parameter สำหรับการ Groupping
                                    $tXrcNet = 0;
                                    $tDocNo = $aValue["FTXshDocNo"];
                                    $tRefDate = empty($aValue['FDXshDocDate']) ? "" : date("d/m/Y", strtotime($aValue['FDXshDocDate']));
                                    $tRcvName = empty($aValue["FTRcvName"]) ? $aDataTextRef['tRptRcvNameEmpty'] : $aValue["FTRcvName"];
                                    $tXrcAmt = $aValue["FTXrcAmt"];
                                    $tXrcNet += floatval($aValue["FCXrcNet"]);
                                    $nRowPartID = $aValue["FNRowPartID"]; 
                                    $nGroupMember = $aValue["FNRptGroupMember"]; 
                                ?>
                                 <?php
                                    // Step 2 Groupping data
                                    $aGrouppingData = array($tDocNo, $tRefDate, $tRcvName, $tXrcAmt, number_format($tXrcNet, $nOptDecimalShow));
                                    /*Parameter
                                    $nRowPartID      = ลำดับตามกลุ่ม
                                    $aGrouppingData  = ข้อมูลสำหรับ Groupping*/
                                    FCNtHRPTHeadGroupping($nRowPartID, $aGrouppingData);
                                ?>
                                <!--  Step 2 แสดงข้อมูลใน TD  -->
                                <tr>
                                    <td></td>
                                    <td class="text-left"><?php echo $aValue['FTPosCode']; ?></td>
                                    <td><?php echo $aValue['FTXrcRefNo1']; ?></td>
                                    <td><?php // echo $aValue["FTXrcAmt"]; ?></td>
                                    <td class="text-right number"><?php // echo number_format($aValue["FCXrcNet"], 2);?></td>
                                </tr>
                                <?php
                                    // Step 3 : เตรียม Parameter สำหรับ Summary Sub Footer
                                    /*$aSumFooter1         = array('N','N', 'เงินสด', number_format($aValue["rcCash"], 2));
                                    $aSumFooter2         = array('N','N', 'บัตรเครดิต', number_format($aValue["rcCredit"], 2));
                                    $aSumFooter3         = array('N','N', 'ชำระรวม', number_format($aValue["rcCash"] + $aValue["rcCredit"], 2));
                                    // Step 4 : สั่ง Summary Sub Footer
                                    /*Parameter 
                                    $nGroupMember     = จำนวนข้อมูลทั้งหมดในกลุ่ม
                                    $nRowPartID       = ลำดับข้อมูลในกลุ่ม
                                    $aSumFooter       =  ข้อมูล Summary SubFooter*/
                                    /*if($aValue["rcCash"] != 0){
                                        FCNtHRPTSumSubFooter2($nGroupMember, $nRowPartID, $aSumFooter1);
                                    }
                                    if($aValue["rcCredit"] != 0){
                                        FCNtHRPTSumSubFooter2($nGroupMember, $nRowPartID, $aSumFooter2);
                                    }
                                    FCNtHRPTSumSubFooter2($nGroupMember, $nRowPartID, $aSumFooter3);*/
                                    
                                    // Step 5 เตรียม Parameter สำหรับ SumFooter
                                    $paFooterSumData = array($aDataTextRef['tRptSaleByPaymentDetailTotalFooter'],'N','N', 'N', number_format(@$aValue['rcSumFootTotal'], $nOptDecimalShow));
                                ?>
                            <?php } ?>
                            <?php
                                // Step 6 : สั่ง Summary Footer
                                $nPageNo = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                                FCNtHRPTSumFooter2($nPageNo, $nTotalPage, $paFooterSumData);
                            ?>
                        <?php }else { ?>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo $aDataTextRef['tRptSaleByPaymentDetailNoData'];?></td></tr>
                        <?php } ;?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="xCNFooterPageRpt">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <?php if($aDataReport["aPagination"]["nTotalPage"] > 0):?>
                            <label class="xCNRptLabel"><?php echo $aDataReport["aPagination"]["nDisplayPage"].' / '.$aDataReport["aPagination"]["nTotalPage"]; ?></label>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        var oFilterLabel = $('.report-filter .text-left label:first-child');
        var nMaxWidth = 0;
        oFilterLabel.each(function(index){
            var nLabelWidth = $(this).outerWidth();
            if(nLabelWidth > nMaxWidth){
                nMaxWidth = nLabelWidth;
            }
        });
        $('.report-filter .text-left label:first-child').width(nMaxWidth + 50);
    });
</script>






















