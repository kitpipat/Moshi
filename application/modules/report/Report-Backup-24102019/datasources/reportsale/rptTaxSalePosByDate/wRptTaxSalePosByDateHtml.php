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
    /*แนวนอน*/
    @media print{@page {size: landscape}} 
    /*แนวตั้ง*/
    /*@media print{@page {size: portrait}}*/
</style>
<div id="odvRptTaxSalePosByDateHtml">
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
                            <label class="xCNRptLabel"><?= $aDataTextRef['tRptTaxSalePosByDateTel'] . $aCompanyInfo['FTCmpTel'] ?> <?= $aDataTextRef['tRptTaxSalePosByDateFax'] . $aCompanyInfo['FTCmpFax'] ?></label>
                        </div>
                        <div class="text-left">
                            <label class="xCNRptLabel"><?= $aDataTextRef['tRptTaxSalePosByDateBch'] . $aCompanyInfo['FTBchName'] ?></label>
                        </div>
                    
                    <?php } ?>
                </div>
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 report-filter">
                    <?php if( (isset($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeFrom'])) && (isset($aDataFilter['tBchCodeTo']) && !empty($aDataFilter['tBchCodeTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptTaxSalePosByDateFilterBchFrom'].' '.$aDataFilter['tBchNameFrom'];?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptTaxSalePosByDateFilterBchTo'].' '.$aDataFilter['tBchNameTo'];?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ;?>
                        
                    <?php if((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่สร้างเอกสาร ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptTaxSalePosByDateFilterDocDateFrom'].' '.$aDataFilter['tDocDateFrom'];?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptTaxSalePosByDateFilterDocDateTo'].' '.$aDataFilter['tDocDateTo'];?></label>
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
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptTaxSalePosByDateSeq'];?></th>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptTaxSalePosByDatePos'];?></th>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptTaxSalePosByDateDocNo'];?></th>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptTaxSalePosByDateDocRef'];?></th>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptTaxSalePosByDateCst'];?></th>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptTaxSalePosByDateTaxID'];?></th>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptTaxSalePosByDateComp'];?></th>
                            <th nowrap class="text-right"><?php echo $aDataTextRef['tRptTaxSalePosByDateAmt'];?></th>
                            <th nowrap class="text-right"><?php echo $aDataTextRef['tRptTaxSalePosByDateAmtV'];?></th>
                            <th nowrap class="text-right"><?php echo $aDataTextRef['tRptTaxSalePosByDateAmtNV'];?></th>
                            <th nowrap class="text-right"><?php echo $aDataTextRef['tRptTaxSalePosByDateTotal'];?></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>  
                            <?php 
                            $paFooterSumData1 = 0;
                            $paFooterSumData2 = 0;
                            $paFooterSumData3 = 0;
                            $nSeq = 1;
                            ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>
                                <?php
                                    // Step 1 เตรียม Parameter สำหรับการ Groupping
                                    $tXrcNet = 0;
                                    $tDocNo = $aValue["FTXshDocNo"];
                                    $tPosCode = $aValue["FTPosCode"];
                                    $tDocDate = date("d/m/Y",strtotime($aValue['FDXshDocDate']));
                                    $nRowPartID = $aValue["FNRowPartID"]; 
                                ?>
                                <?php
                                    // Step 2 Groupping data
                                    $aGrouppingData = array($aDataTextRef['tRptTaxSalePosByDateDocDate'] . ' ' . $tDocDate, 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N','N');
                                    /*Parameter
                                    $nRowPartID      = ลำดับตามกลุ่ม
                                    $aGrouppingData  = ข้อมูลสำหรับ Groupping*/
                                    FCNtHRPTHeadGroupping($nRowPartID, $aGrouppingData);
                                    if($aValue["FNRowPartID"] == 1){
                                        $nSeq = 1;
                                    }
                                ?>
                                <!--  Step 2 แสดงข้อมูลใน TD  -->
                                <tr>
                                    <td class="text-left"><?php echo $nSeq++; ?></td>
                                    <td class="text-left"><?php echo $aValue['FTPosCode']; ?></td>
                                    <td class="text-left"><?php echo $aValue['FTXshDocNo']; ?></td>
                                    <td class="text-left"><?php echo $aValue["FTXshDocRef"]; ?></td>
                                    <td class="text-left"><?php echo $aValue["FTCstName"]; ?></td>
                                    <td class="text-left"><?php echo $aValue["FTXshTaxID"]; ?></td>
                                    <td class="text-left"><?php echo $aValue["FTCmpName"]; ?></td>
                                    <td class="text-right"><?php echo number_format($aValue["FCXshAmt"], $nOptDecimalShow); ?></td>
                                    <td class="text-right"><?php echo number_format($aValue["FCXshAmtV"], $nOptDecimalShow); ?></td>
                                    <td class="text-right"><?php echo number_format($aValue["FCXshAmtNV"], $nOptDecimalShow); ?></td>
                                    <td class="text-right"><?php echo number_format($aValue["FCXshGrandTotal"], $nOptDecimalShow); ?></td>
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
                                    $paFooterSumData = array($aDataTextRef['tRptTaxSalePosByDateTotalFooter'], 'N', 'N', 'N', 'N', 'N', 'N', number_format(@$aValue['FCXshAmt_Footer'], $nOptDecimalShow), number_format(@$aValue['FCXshAmtV_Footer'], $nOptDecimalShow), number_format(@$aValue['FCXshAmtNV_Footer'], $nOptDecimalShow), number_format(@$aValue['FCXshGrandTotal_Footer'], $nOptDecimalShow));
                                ?>
                            <?php } ?>
                            <?php
                                // Step 6 : สั่ง Summary Footer
                                $nPageNo = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                                FCNtHRPTSumFooter2($nPageNo, $nTotalPage, $paFooterSumData);
                            ?>
                        <?php }else { ?>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo $aDataTextRef['tRptTaxSalePosByDateNoData'];?></td></tr>
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



















































