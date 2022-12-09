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
    /*แนวนอน*/
    @media print{@page {size: landscape}} 
    /*แนวตั้ง*/
    /* @media print{@page {size: portrait}} */
</style>

<div id="odvRptAdjustStockVendingHtml">
    <div class="container-fluid xCNLayOutRptHtml">

        <div class="xCNHeaderReport">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-center">
                        <label class="xCNRptTitle"><?php echo $aDataTextRef['tTitleReport']; ?></label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <?php if (isset($aCompanyInfo) && !empty($aCompanyInfo)) { ?>

                        <div class="text-left">
                            <label class="xCNRptLabel"><?= $aCompanyInfo['FTCmpName'] ?></label>
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
                            <label class="xCNRptLabel"><?= $aDataTextRef['tRptTaxSalePosTel'] . $aCompanyInfo['FTCmpTel'] ?> <?= $aDataTextRef['tRptTaxSalePosFax'] . $aCompanyInfo['FTCmpFax'] ?></label>
                        </div>
                        <div class="text-left">
                            <label class="xCNRptLabel"><?= $aDataTextRef['tRptTaxSalePosBch'] . $aCompanyInfo['FTBchName'] ?></label>
                        </div>

                    <?php } ?>
                </div>

                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 report-filter">
                    <?php if ((isset($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeFrom'])) && (isset($aDataFilter['tBchCodeTo']) && !empty($aDataFilter['tBchCodeTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptBchFrom'] . ' ' . $aDataFilter['tBchNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptBchTo'] . ' ' . $aDataFilter['tBchNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ((isset($aDataFilter['tShopCodeFrom']) && !empty($aDataFilter['tShopCodeFrom'])) && (isset($aDataFilter['tShopCodeTo']) && !empty($aDataFilter['tShopCodeTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล ร้านค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptShopFrom'] . ' ' . $aDataFilter['tShopNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptShopTo'] . ' ' . $aDataFilter['tShopNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ((isset($aDataFilter['tPosCodeFrom']) && !empty($aDataFilter['tPosCodeFrom'])) && (isset($aDataFilter['tPosCodeTo']) && !empty($aDataFilter['tPosCodeTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล เครื่องจุดขาย ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptPosFrom'] . ' ' . $aDataFilter['tPosNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptPosTo'] . ' ' . $aDataFilter['tPosNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่ ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="row text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptDateFrom'] . ' ' . $aDataFilter['tDocDateFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptDateTo'] . ' ' . $aDataFilter['tDocDateTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ((isset($aDataFilter['tRackCodeFrom']) && !empty($aDataFilter['tRackCodeFrom'])) && (isset($aDataFilter['tRackCodeTo']) && !empty($aDataFilter['tRackCodeTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล กลุ่มช่อง ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptRackFrom'] . ' ' . $aDataFilter['tRackNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptRackTo'] . ' ' . $aDataFilter['tRackNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <label class="xCNRptLabel"><?php echo $aDataTextRef['tDatePrint'] . ' ' . date('d/m/Y') . ' ' . $aDataTextRef['tTimePrint'] . ' ' . date('H:i:s'); ?></label>
                    </div>
                </div>
            </div>

        </div>
        
        <div class="xCNContentReport">
            <div id="odvRptTableAdvance" class="table-responsive">
                <table class="table"> 
                    <thead>
                        <tr>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptRentAmtForDetailSerailPos']; ?></th>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptRentAmtForDetailUser']; ?></th>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptRentAmtForDetailDocno']; ?></th>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptRentAmtForDetailDocDate']; ?></th>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptRentAmtForDetailRack']; ?></th>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptRentAmtForDetailSubRack']; ?></th>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptRentAmtForDetailSize']; ?></th>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptRentAmtForDetailDateGet']; ?></th>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptRentAmtForDetailLoginTo']; ?></th>
                            <th nowrap class="text-left"><?php echo $aDataTextRef['tRptRentAmtForDetailStaPayment']; ?></th>
                            <th nowrap class="text-center"><?php echo $aDataTextRef['tRptRentAmtForDetailAmtPayment']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $aData = $aDataReport["aRptData"];
                        $aPagination = $aDataReport["aPagination"];
                        ?>
                        <?php if ($aData) { ?>
                            <?php
                            $aDataReport = $aData["aData"];
                            $aSumData = $aData["aSumData"];
                            $nPerPage = $aPagination["nPerPage"];
                            $nNumRowDisplay = 0;
                            $nLastRecodeOfPos = 0;
                            $nFCXshSumAllPrePaid = 0;
                            ?>
                            <?php for ($nI = 0; $nI < count($aSumData); $nI++) { ?>
                                <?php
                                $nFCXshSumAllPrePaid = $aSumData[$nI]["FCXshSumAllPrePaid"];
                                $nSeq = 0;
                                ?>    
                                <?php for ($nJ = 0; $nJ < count($aDataReport); $nJ++) { ?>
                                    <?php if ($aSumData[$nI]["FTPosCode"] == $aDataReport[$nJ]["FTPosCode"]) { ?>

                                        <tr>
                                            <?php if ($nSeq == 0) { ?>
                                                <?php
                                                $nPosCodeRowSpan = 0;
                                                if ($aSumData[$nI]["FNXshNumDoc"] <= $nPerPage) {
                                                    $nDif = $nPerPage - $nNumRowDisplay;
                                                    if ($aSumData[$nI]["FNXshNumDoc"] < $nDif) {
                                                        if ($aSumData[$nI]["FNXshNumDoc"] < count($aDataReport)) {
                                                            $nPosCodeRowSpan = $aSumData[$nI]["FNXshNumDoc"];
                                                        } else {
                                                            $nPosCodeRowSpan = count($aDataReport);
                                                        }
                                                    } else {
                                                        if ($nDif < count($aDataReport)) {
                                                            $nPosCodeRowSpan = $nDif;
                                                        } else {
                                                            $nPosCodeRowSpan = count($aDataReport);
                                                        }
                                                    }
                                                }
                                                ?>
                                                <td nowrap class="text-left" style="width:10%" rowspan="<?php echo $nPosCodeRowSpan; ?>">
                                                    <?php echo $aSumData[$nI]["FTPosCode"]; ?>
                                                </td>
                                            <?php } ?>
                                            <td nowrap class="text-left" style="width:10%">
                                                <?php echo $aDataReport[$nJ]["FTXshFrmLogin"]; ?>
                                            </td>
                                            <td nowrap class="text-left" style="width:10%">
                                                <?php echo $aDataReport[$nJ]["FTXshDocNo"]; ?>
                                            </td>
                                            <td nowrap class="text-left" style="width:10%">
                                                <?php echo date("Y/m/d", strtotime($aDataReport[$nJ]["FDXshDocDate"])); ?>
                                            </td>
                                            <td nowrap class="text-left" style="width:10%">
                                                <?php echo $aDataReport[$nJ]["FTRakName"]; ?>
                                            </td>
                                            <td nowrap class="text-left" style="width:10%">
                                                <?php echo $aDataReport[$nJ]["FNLayNo"]; ?>
                                            </td>
                                            <td nowrap class="text-left" style="width:10%">
                                                <?php echo $aDataReport[$nJ]["FTPzeName"]; ?>
                                            </td>
                                            <td nowrap class="text-left" style="width:10%">
                                                <?php echo date("Y/m/d", strtotime($aDataReport[$nJ]["FDXshDatePick"])); ?>
                                            </td>
                                            <td nowrap class="text-left" style="width:10%">
                                                <?php echo $aDataReport[$nJ]["FTXshToLogin"]; ?>
                                            </td>
                                            <td nowrap class="text-left" style="width:10%">
                                                <?php
                                                if ($aDataReport[$nJ]["FTXshStaPaid"] == 1) {
                                                    echo $aDataTextRef['tRptRentAmtForDetailStaPaymentNoPay'];
                                                } else if ($aDataReport[$nJ]["FTXshStaPaid"] == 2) {
                                                    echo $aDataTextRef['tRptRentAmtForDetailStaPaymentSome'];
                                                }if ($aDataReport[$nJ]["FTXshStaPaid"] == 3) {
                                                    echo $aDataTextRef['tRptRentAmtForDetailStaPaymentAlready'];
                                                }
                                                ?>
                                            </td>
                                            <td nowrap class="text-right" style="width:10%">
                                                <?php
                                                if ($aDataReport[$nJ]["FCXshPrePaid"] == "") {
                                                    echo number_format("0", 2);
                                                } else {
                                                    echo number_format($aDataReport[$nJ]["FCXshPrePaid"], 2);
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $nSeq++;
                                        $nNumRowDisplay++;
                                        $nLastRecodeOfPos = $aDataReport[$nJ]["FTRptSeqOfGroupPos"];
                                        ?>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($aSumData[$nI]["FNXshNumDoc"] == $nLastRecodeOfPos) { ?>
                                    <tr>
                                        <td nowrap colspan="10" class="text-left" style="border-top:1px solid #333 !important;border-bottom:1px solid #333 !important;background-color: #CFE2F3; ;padding: 4px;">
                                            <?php echo @$aDataTextRef['tRptRentAmtForDetailSumText']; ?>
                                        </td>
                                        <td nowrap class="text-right" style="border-top:1px solid #333 !important;border-bottom:1px solid #333 !important;background-color: #CFE2F3; ;padding: 4px;">
                                            <?php echo number_format($aSumData[$nI]["FCXshSumPrePaid"], 2); ?>
                                        </td>
                                    </tr>
                                <?php } ?>


                            <?php } ?>
                            <?php if ($aPagination["nTotalPage"] == $aPagination["nDisplayPage"]) { ?>
                                <tr class="xCNTrFooter">
                                    <td nowrap colspan="10" class="text-left" style="border-top:1px solid #333 !important;border-bottom:1px solid #333 !important;background-color: #CFE2F3; ;padding: 4px;">
                                        <?php echo @$aDataTextRef['tRptRentAmtForDetailSumTextLast']; ?>
                                    </td>
                                    <td nowrap class="text-right" style="border-top:1px solid #333 !important;border-bottom:1px solid #333 !important;background-color: #CFE2F3; ;padding: 4px;">
                                        <?php echo number_format($nFCXshSumAllPrePaid, 2); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo @$aDataTextRef['tRptAdjStkNoData']; ?></td></tr>
                        <?php } ?>
                    </tbody>
                </table>    
            </div>
        </div>
        <!-- <div class="xCNFooterPageRpt">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <?php if ($aPagination["nTotalPage"] > 0): ?>
                            <label class="xCNRptLabel"><?php echo $aPagination["nDisplayPage"] . ' / ' . $aPagination["nTotalPage"]; ?></label>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div> -->
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













