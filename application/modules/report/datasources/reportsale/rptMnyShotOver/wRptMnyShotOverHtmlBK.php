<?php
    $aCompanyInfo = $aDataViewRpt['aCompanyInfo'];
    $aDataFilter = $aDataViewRpt['aDataFilter'];
    $aDataTextRef = $aDataViewRpt['aDataTextRef'];
    $aDataReport = $aDataViewRpt['aDataReport'];
?>


<style>
    .xCNFooterRpt {
        border-bottom: 7px double #ddd;
    }
    .table thead th,
    .table>thead>tr>th,
    .table tbody tr,
    .table>tbody>tr>td {
        border: 0px transparent !important;
    }

    .table>thead:first-child>tr:first-child>td,
    .table>thead:first-child>tr:first-child>th {
        border-top: 1px solid black !important;
        border-bottom: 0px transparent !important;
        /*border-bottom : 1px solid black !important;
        background-color: #CFE2F3 !important;*/
    }

    .table>thead:first-child>tr:first-child>td:nth-child(1),
    .table>thead:first-child>tr:first-child>th:nth-child(1),
    .table>thead:first-child>tr:first-child>td:nth-child(2),
    .table>thead:first-child>tr:first-child>th:nth-child(2),
    .table>thead:first-child>tr:first-child>td:nth-child(3),
    .table>thead:first-child>tr:first-child>th:nth-child(3) {
        border-bottom: 1px dashed #ccc !important;
    }

.xWRptMSORightline1 {

border-right: 1px  solid black !important;
}

.xWRptMSOleftline1 {

border-left: 1px  solid black !important;
}
   
    .xWRptMSOUnderline {

        border-bottom: 1px solid black !important;
    }
    .table>thead:first-child>tr:last-child>td,
    .table>thead:first-child>tr:last-child>th {
        /*border-top: 1px solid black !important;*/
        border-bottom: 1px solid black !important;
        /*background-color: #CFE2F3 !important;*/
    }

    .table>tbody>tr.xCNTrSubFooter {
        border-top: 1px solid black !important;
        border-bottom: 1px solid black !important;
        /*background-color: #CFE2F3 !important;*/
    }

    .table>tbody>tr.xCNTrFooter {
        border-top: 1px solid black !important;
        /*background-color: #CFE2F3 !important;*/
        border-bottom: 6px double black !important;
    }

    .table>tbody>tr.xCNRptLastPdtList>td:nth-child(3),
    .table>tbody>tr.xCNRptLastPdtList>td:nth-child(4),
    .table>tbody>tr.xCNRptLastPdtList>td:nth-child(5),
    .table>tbody>tr.xCNRptLastPdtList>td:nth-child(6),
    .table>tbody>tr.xCNRptLastPdtList>td:nth-child(7) {
        border: 0px solid black !important;
        border-bottom: 1px dashed #ccc !important;
    }

    .table tbody tr.xCNRptLastGroupTr,
    .table>tbody>tr.xCNRptLastGroupTr>td {
        border: 0px solid black !important;
        border-bottom: 1px dashed #ccc !important;
    }

    .table tbody tr.xCNRptSumFooterTrTop,
    .table>tbody>tr.xCNRptSumFooterTrTop>td {
        border: 0px solid black !important;
        border-top: 1px solid black !important;
    }

    .table tbody tr.xCNRptSumFooterTrBottom,
    .table>tbody>tr.xCNRptSumFooterTrBottom>td {
        border: 0px solid black !important;
        border-bottom: 1px solid black !important;
    }

    .table>tfoot>tr {
        border-top: 1px solid black !important;
        /*background-color: #CFE2F3 !important;*/
        border-bottom: 6px double black !important;
    }

    /*??????????????????*/
    /* @media print{@page {size: landscape}} */
    /*?????????????????????*/
    @media print {
        @page {
            size: portrait
        }
    }
</style>

<div id="odvRptAdjustStockVendingHtml">
    <div class="container-fluid xCNLayOutRptHtml">

        <div class="xCNHeaderReport">
         
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <?php if (isset($aCompanyInfo) && !empty($aCompanyInfo)) { ?>

                        <div class="text-left">
                            <label class="xCNRptCompany"><?= $aCompanyInfo['FTCmpName'] ?></label>
                        </div>

                        <?php if ($aCompanyInfo['FTAddVersion'] == '1') { // ??????????????????????????????????????? ?>
                            <div class="text-left xCNRptAddress">
                                <label><?= $aCompanyInfo['FTAddV1No'] . ' ' . $aCompanyInfo['FTAddV1Road'] . ' ' . $aCompanyInfo['FTAddV1Soi'] ?> <?= $aCompanyInfo['FTSudName'] . ' ' . $aCompanyInfo['FTDstName'] . ' ' . $aCompanyInfo['FTPvnName'] . ' ' . $aCompanyInfo['FTAddV1PostCode'] ?></label>
                            </div>
                        <?php } ?>

                        <?php if ($aCompanyInfo['FTAddVersion'] == '2') { // ??????????????????????????????????????? ?>
                            <div class="text-left xCNRptAddress">
                                <label><?= $aCompanyInfo['FTAddV2Desc1'] ?><?= $aCompanyInfo['FTAddV2Desc2'] ?></label>
                            </div>
                        <?php } ?>

                        <div class="text-left xCNRptAddress">
                            <label><?= $aDataTextRef['tRptAddrTel'] . $aCompanyInfo['FTCmpTel'] ?></label>
                            <label><?= $aDataTextRef['tRptAddrFax'] . $aCompanyInfo['FTCmpFax'] ?></label>
                        </div>
                        <div class="text-left xCNRptAddress">
                            <label><?= $aDataTextRef['tRptAddrBranch'] . $aCompanyInfo['FTBchName'] ?></label>
                        </div>
                        <div class="text-left xCNRptAddress">
                            <label><?=$aDataTextRef['tRptTaxSalePosTaxId'] . $aCompanyInfo['FTAddTaxNo']?></label>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 report-filter">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center">
                                <label class="xCNRptTitle"><?php echo $aDataTextRef['tTitleReport']; ?></label>
                            </div>
                        </div>
                    </div>

                    <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))): ?>
                        <!-- ============================ ?????????????????????????????????????????? ??????????????????????????????????????????????????? ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center">
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateFrom'] ?> : </label>   <label><?=date('d/m/Y',strtotime($aDataFilter['tDocDateFrom'])); ?></label>
                                   <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDateTo'] ?> : </label>   <label><?=date('d/m/Y',strtotime($aDataFilter['tDocDateTo'])); ?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ((isset($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeFrom'])) && (isset($aDataFilter['tBchCodeTo']) && !empty($aDataFilter['tBchCodeTo']))) { ?>
                        <!-- ============================ ?????????????????????????????????????????? ???????????? ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center">
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom'] ?> : </label>   <label><?=$aDataFilter['tBchNameFrom']; ?></label>
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchTo'] ?> : </label>   <label><?=$aDataFilter['tBchNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php }; ?>

                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    &nbsp;
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <label class="xCNRptDataPrint"><?php echo $aDataTextRef['tDatePrint'] . ' ' . date('d/m/Y') . ' ' . $aDataTextRef['tTimePrint'] . ' ' . date('H:i:s'); ?></label>
                    </div>
                </div>
            </div>
        </div>
 
        <div class="xCNContentReport">
            <div id="odvRptTableShotOverHtml" class="table-responsive">
            <table class="table " >
                    <thead>
                        <tr class="xWRptMSOUnderline">
                            <th rowspan ="2" class="text-left xCNRptColumnHeader" style="width:10%; vertical-align : middle;text-align:left"><?php echo $aDataTextRef['tRptMnyPos']; ?></th>
                            <th rowspan ="2" class="text-left xCNRptColumnHeader" style="width:10%; vertical-align : middle;text-align:left"><?php echo $aDataTextRef['tRptMnyUsrId']; ?></th>
                            <th colspan ="4" class="text-left xCNRptColumnHeader" style="vertical-align : middle;text-align:center"><?php echo $aDataTextRef['tRptMnyAmt']; ?></th>
                            <th rowspan ="2" class="text-center xCNRptColumnHeader" style="width:10%; vertical-align : middle;text-align:center"><?php echo $aDataTextRef['tRptMnySign']; ?></th>
                            <th rowspan ="2" class="text-right  xCNRptColumnHeader" style="width:10%; vertical-align : middle;text-align:right"><?php echo $aDataTextRef['tRptMnySignMas']; ?></th>
                        </tr>
                        
                        <tr class= "xWRptMSOUnderline1">
                            <th class="text-center xCNRptColumnHeader" style="width:5%;"><?php echo $aDataTextRef['tRptMnyMM'];?></th>
                            <th class="text-center xCNRptColumnHeader" style="width:5%;"><?php echo $aDataTextRef['tRptMnyOM'];?></th>
                            <th class="text-center xCNRptColumnHeader" style="width:5%;"><?php echo $aDataTextRef['tRptMnyCashIn'];?></th>
                            <th class="text-center xCNRptColumnHeader" style="width:5%;"><?php echo $aDataTextRef['tRptMnyCashOut'];?></th>
                        </tr>
  
                    </thead>
                    <tbody>
                    <?php
                     if(!empty($aDataReport['aRptData'])){
                         foreach($aDataReport['aRptData'] as $aData){
                    ?>
                        <tr class="">
                            <td  class="text-left xCNRptDetail" style="width:5%;"><?php echo $aData['FTPosCode']; ?></td>
                            <td  class="text-left xCNRptDetail" style="width:5%;"><?php echo $aData['FTUsrCode']; ?></td>
                            <td  class="text-right xCNRptDetail" style="width:5%;"><?php echo number_format($aData['FCMnyShot'],2); ?></td>
                            <td  class="text-right xCNRptDetail" style="width:5%;"><?php echo number_format($aData['FCMnyOver'],2); ?></td>
                            <td  class="text-right xCNRptDetail" style="width:5%;"><?php echo number_format($aData['FCSvnCashIn'],2);?></td>
                            <td  class="text-right xCNRptDetail" style="width:5%;"><?php echo number_format($aData['FCSvnCashOut'],2);?></td>
                            <td  class="text-center xCNRptDetail" style="width:3%;"></td>
                            <td  class="text-center xCNRptDetail" style="width:3%;"></td>
                        </tr>
                    <?php
                            }
                        ?>
                            <?php  
                                $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                                if ($nPageNo == $nTotalPage) {
                            ?>
                    <?php 
                            } 
                    ?>
                     <?php   }else{ 
                            ?>
                        <tr>
                            <td  colspan="17"  class="text-center xCNRptColumnFooter"   ><?php echo $aDataTextRef['tRptNoData']; ?></td>
                        </tr>
                    <?php   
                    }
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="xCNRptFilterTitle">
                <div class="text-left">
                    <label class="xCNTextConsOth"><?=$aDataTextRef['tRptConditionInReport'];?></label>
                </div>
            </div>

            <!-- ============================ ?????????????????????????????????????????? ???????????? ============================ -->
            <?php if (isset($aDataFilter['tBchCodeSelect']) && !empty($aDataFilter['tBchCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom']; ?> : </span> <?php echo ($aDataFilter['bBchStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tBchNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ============================ ?????????????????????????????????????????? ????????????????????????????????? ============================ -->
            <?php if ((isset($aDataFilter['tMerCodeFrom']) && !empty($aDataFilter['tMerCodeFrom'])) && (isset($aDataFilter['tMerCodeTo']) && !empty($aDataFilter['tMerCodeTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjMerChantFrom'].' : </span>'.$aDataFilter['tMerNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjMerChantTo'].' : </span>'.$aDataFilter['tMerNameTo'];?></label>
                    </div>
                </div>
            <?php endif; ?>  
            <?php if (isset($aDataFilter['tMerCodeSelect']) && !empty($aDataFilter['tMerCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptMerFrom']; ?> : </span> <?php echo ($aDataFilter['bMerStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tMerNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>  
            
            <!-- ============================ ?????????????????????????????????????????? ????????????????????? ============================ -->
            <?php if ((isset($aDataFilter['tShpCodeFrom']) && !empty($aDataFilter['tShpCodeFrom'])) && (isset($aDataFilter['tShpCodeTo']) && !empty($aDataFilter['tShpCodeTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjShopFrom'].' : </span>'.$aDataFilter['tShpNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjShopTo'].' : </span>'.$aDataFilter['tShpNameTo'];?></label>
                    </div>
                </div>
            <?php endif; ?>    
            <?php if (isset($aDataFilter['tShpCodeSelect']) && !empty($aDataFilter['tShpCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptShopFrom']; ?> : </span> <?php echo ($aDataFilter['bShpStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tShpNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>  

            <!-- ============================ ?????????????????????????????????????????? ?????????????????? ============================ -->
            <?php if ((isset($aDataFilter['tCstCodeFrom']) && !empty($aDataFilter['tCstCodeFrom'])) && (isset($aDataFilter['tCstCodeTo']) && !empty($aDataFilter['tCstCodeTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstFrom'].' : </span>'.$aDataFilter['tCstNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstTo'].' : </span>'.$aDataFilter['tCstNameTo'];?></label>
                    </div>
                </div>
            <?php endif; ?>    
            <?php if (isset($aDataFilter['tCstCodeSelect']) && !empty($aDataFilter['tCstCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptCstFrom']; ?> : </span> <?php echo ($aDataFilter['bCstStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tCstNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>  

            <!-- ============================ ?????????????????????????????????????????? ?????????????????? ============================ -->
            <?php if ((isset($aDataFilter['tPosCodeFrom']) && !empty($aDataFilter['tPosCodeFrom'])) && (isset($aDataFilter['tPosCodeTo']) && !empty($aDataFilter['tPosCodeTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjPosFrom'].' : </span>'.$aDataFilter['tPosCodeFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjPosTo'].' : </span>'.$aDataFilter['tPosCodeTo'];?></label>
                    </div>
                </div>
            <?php endif; ?> 
            <?php if (isset($aDataFilter['tPosCodeSelect']) && !empty($aDataFilter['tPosCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptPosFrom']; ?> : </span> <?php echo ($aDataFilter['bPosStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tPosCodeSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?> 

            <!-- ============================ ?????????????????????????????????????????? ??????????????????????????????????????? ============================ -->
            <?php if ((isset($aDataFilter['tRcvCodeFrom']) && !empty($aDataFilter['tRcvCodeFrom'])) && (isset($aDataFilter['tRcvCodeTo']) && !empty($aDataFilter['tRcvCodeTo']))): ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptRcvFrom'].' : </span>'.$aDataFilter['tRcvNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptRcvTo'].' : </span>'. $aDataFilter['tRcvNameTo']; ?></label>
                    </div>
                </div>
            <?php endif; ?>
            
        
        </div>
        <div class="xCNFooterPageRpt">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <?php if ($aDataReport["aPagination"]["nTotalPage"] > 0): ?>
                            <label class="xCNRptLabel"><?php echo $aDataReport["aPagination"]["nDisplayPage"] . ' / ' . $aDataReport["aPagination"]["nTotalPage"]; ?></label>
                        <?php endif; ?>
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













