<?php
    $aDataReport = $aDataViewRpt['aDataReport'];
    $aDataTextRef = $aDataViewRpt['aDataTextRef'];
    $aDataFilter = $aDataViewRpt['aDataFilter'];

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
</style>
<div id="odvRptAdjustStockVendingHtml">
    <div class="container-fluid xCNLayOutRptHtml">
        <div class="xCNHeaderReport">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-center">
                        <label class="xCNRptTitle"><?php echo @$aDataTextRef['tTitleReport'];?></label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <?php if (isset($aCompanyInfo) && !empty($aCompanyInfo)) { ?>
                        <div class="text-left">
                            <label class="xCNRptLabel"><?php echo $aCompanyInfo['FTCmpName'];?></label>
                        </div>

                        <?php if ($aCompanyInfo['FTAddVersion'] == '1') { // ที่อยู่แบบแยก ?>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?= $aCompanyInfo['FTAddV1No'] . ' ' . $aCompanyInfo['FTAddV1Village'] . ' ' . $aCompanyInfo['FTAddV1Road'] . ' ' . $aCompanyInfo['FTAddV1Soi'] ?></label>
                            </div>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?= $aCompanyInfo['FTSudName'] . ' ' . $aCompanyInfo['FTDstName'] . ' ' . $aCompanyInfo['FTPvnName'] . ' ' . $aCompanyInfo['FTAddV1PostCode'] ?></label>
                            </div>
                        <?php } ?>

                        <?php if ($aCompanyInfo['FTAddVersion'] == '2') { // ที่อยู่แบบรวม ?>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?= $aCompanyInfo['FTAddV2Desc1'] ?></label>
                            </div>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?= $aCompanyInfo['FTAddV2Desc2'] ?></label>
                            </div>
                        <?php } ?>

                        <div class="text-left">
                            <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAddrTel'] . $aCompanyInfo['FTCmpTel'] ?> <?php echo $aDataTextRef['tRptAddrFax'] . $aCompanyInfo['FTCmpFax'] ?></label>
                        </div>

                        <div class="text-left">
                            <label class="xCNRptLabel"><?= $aDataTextRef['tRptAddrBranch'] . $aCompanyInfo['FTBchName'] ?></label>
                        </div>

                    <?php } ?>
                </div>
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 report-filter">
                    <?php if ((isset($aDataFilter['tMerCodeFrom']) && !empty($aDataFilter['tMerCodeFrom'])) && (isset($aDataFilter['tMerCodeTo']) && !empty($aDataFilter['tMerCodeTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล กลุ่มธุรกิจ ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAdjMerChantFrom'] . ' ' . $aDataFilter['tMerNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAdjMerChantTo'] . ' ' . $aDataFilter['tMerNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ((isset($aDataFilter['tShpCodeFrom']) && !empty($aDataFilter['tShpCodeFrom'])) && (isset($aDataFilter['tShpCodeTo']) && !empty($aDataFilter['tShpCodeTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล ร้านค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAdjShopFrom'] . ' ' . $aDataFilter['tShpNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAdjShopTo'] . ' ' . $aDataFilter['tShpNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ((isset($aDataFilter['tPosCodeFrom']) && !empty($aDataFilter['tPosCodeFrom'])) && (isset($aDataFilter['tPosCodeTo']) && !empty($aDataFilter['tPosCodeTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล จุดขาย ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAdjPosFrom'] . ' ' . $aDataFilter['tPosNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAdjPosTo'] . ' ' . $aDataFilter['tPosNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ((isset($aDataFilter['tWahCodeFrom']) && !empty($aDataFilter['tWahCodeFrom'])) && (isset($aDataFilter['tWahCodeTo']) && !empty($aDataFilter['tWahCodeTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล จุดขาย ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAdjWahFrom'] . ' ' . $aDataFilter['tWahNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAdjWahTo'] . ' ' . $aDataFilter['tWahNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่ ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAdjDateFrom'] . ' ' . $aDataFilter['tDocDateFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAdjDateTo'] . ' ' . $aDataFilter['tDocDateTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
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
                            <th nowrap class="text-center" style="width:10%"><?php  echo @$aDataTextRef['tRptAdjStkVDDocNo'];?></th>
                            <th nowrap class="text-center" style="width:10%"><?php   echo @$aDataTextRef['tRptAdjStkVDDocDate'];?></th>
                            <th nowrap class="text-center" style="width:10%"><?php   echo @$aDataTextRef['tRptAdjStkVDUserAdj'];?></th>
                            <th nowrap class="text-center" style="width:15%"><?php  echo @$aDataTextRef['tRptAdjStkVDPdtCode'];?></th>
                            <th nowrap class="text-center" style="width:25%"><?php  echo @$aDataTextRef['tRptAdjStkVDPdtName'];?></th>
                            <th nowrap class="text-center" style="width:5%"><?php   echo @$aDataTextRef['tRptAdjStkVDLayRow'];?></th>
                            <th nowrap class="text-center" style="width:5%"><?php   echo @$aDataTextRef['tRptAdjStkVDLayCol'];?></th>
                            <th nowrap class="text-center" style="width:10%"><?php  echo @$aDataTextRef['tRptAdjStkVDWahB4Adj'];?></th>
                            <th nowrap class="text-center" style="width:10%"><?php  echo @$aDataTextRef['tRptAdjStkVDUnitQty'];?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])):?>
                            <?php
                                // Set ตัวแปร SumSubFooter และ ตัวแปร SumFooter
                                $nSubSumAjdWahB4Adj     = 0;
                                $nSubSumAjdUnitQty      = 0;

                                $nSumFooterAjdWahB4Adj  = 0;
                                $nSumFooterAjdUnitQty   = 0;
                            ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey=>$aValue): ?>
                                <?php
                                    // Step 1 เตรียม Parameter สำหรับการ Groupping
                                    $tDocNo         = $aValue["FTAjhDocNo"];  
                                    $tDocDate       = $aValue["FDAjhDocDate"];
                                    $tApvName       = $aValue["FTAjdApvName"];

                                    $nGroupMember   = $aValue["FNRptGroupMember"]; 
                                    $nRowPartID     = $aValue["FNRowPartID"]; 
                                ?>
                                <?php
                                    //Step 2 Groupping data
                                    $aGrouppingData = array($tDocNo,$tDocDate,$tApvName);
                                    // Parameter
                                    // $nRowPartID      = ลำดับตามกลุ่ม
                                    // $aGrouppingData  = ข้อมูลสำหรับ Groupping
                                    FCNtHRPTHeadGroupping($nRowPartID,$aGrouppingData);
                                ?>
                                <!--  Step 2 แสดงข้อมูลใน TD  -->
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo @$aValue["FTPdtCode"];?></td>
                                    <td><?php echo @$aValue["FTPdtName"];?></td>
                                    <td class="text-right number"><?php echo number_format($aValue["FNAjdLayRow"],2);?></td>
                                    <td class="text-right number"><?php echo number_format($aValue["FNAjdLayCol"],2);?></td>
                                    <td class="text-right number"><?php echo number_format($aValue["FCAjdWahB4Adj"],2);?></td>
                                    <td class="text-right number"><?php echo number_format($aValue["FCAjdUnitQty"],2);?></td>
                                </tr>

                                <?php
                                    //Step 3 : เตรียม Parameter สำหรับ Summary Sub Footer
                                    $nSubSumAjdWahB4Adj = $aValue["FCAjdWahB4Adj_SubTotal"];
                                    $nSubSumAjdUnitQty  = $aValue["FCAjdUnitQty_SubTotal"];

                                    $aSumFooter         = array($aDataTextRef['tRptAdjStkVDTotalSub'],'N','N','N','N','N','N',$nSubSumAjdWahB4Adj,$nSubSumAjdUnitQty);

                                    //Step 4 : สั่ง Summary SubFooter
                                    //Parameter 
                                    //$nGroupMember     = จำนวนข้อมูลทั้งหมดในกลุ่ม
                                    //$nRowPartID       = ลำดับข้อมูลในกลุ่ม
                                    //$aSumFooter       =  ข้อมูล Summary SubFooter
                                    FCNtHRPTSumSubFooter($nGroupMember,$nRowPartID,$aSumFooter);


                                    //Step 5 เตรียม Parameter สำหรับ SumFooter
                                    $nSumFooterAjdWahB4Adj  = number_format($aValue["FCAjdWahB4Adj_Footer"],2);
                                    $nSumFooterAjdUnitQty   = number_format($aValue["FCAjdUnitQty_Footer"],2);
                                    $paFooterSumData        = array($aDataTextRef['tRptAdjStkVDTotalFooter'],'N','N','N','N','N','N',$nSumFooterAjdWahB4Adj,$nSumFooterAjdUnitQty);
                                ?>
                            <?php endforeach;?>
                            <?php
                                //Step 6 : สั่ง Summary Footer
                                $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                                FCNtHRPTSumFooter($nPageNo,$nTotalPage,$paFooterSumData);
                            ?>
                        <?php else:?>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo @$aDataTextRef['tRptAdjStkNoData'];?></td></tr>
                        <?php endif;?>
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