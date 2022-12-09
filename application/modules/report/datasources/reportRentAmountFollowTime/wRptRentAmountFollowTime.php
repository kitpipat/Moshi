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
        
        /* background-color: #CFE2F3 !important; */
    }

    .table>tbody>tr.xCNTrSubFooter{
        border-top: 1px solid black !important;
        border-bottom : 1px solid black !important;
        /* background-color: #CFE2F3 !important; */
    }

    .table>tbody>tr.xCNTrFooter{
        /* background-color: #CFE2F3 !important; */
        /* border-bottom : 6px double black !important; */
        /* border-top: dashed 1px #333 !important; */
        border-top: 1px solid black !important;
        border-
         : 1px solid black !important;
    }
    .table tbody tr.xCNHeaderGroup, .table>tbody>tr.xCNHeaderGroup>td {
        /* color: #232C3D !important; */
        font-size: 18px !important;
        font-weight: 600;
    }
    .table>tbody>tr.xCNHeaderGroup>td:nth-child(4), .table>tbody>tr.xCNHeaderGroup>td:nth-child(5) {
        text-align: right;
    }
</style>

<div id="odvRptRentAmountFollowTimeHtml">
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
                    <?php if (isset($aCompanyInfo) && !empty($aCompanyInfo)) : ?>
                        <div class="text-left">
                            <label class="xCNRptCompany"><?php echo $aCompanyInfo['FTCmpName']; ?></label>
                        </div>
                        <?php if ($aCompanyInfo['FTAddVersion'] == '1') : // ที่อยู่แบบแยก 
                                ?>
                            <div class="text-left xCNRptAddress">
                                <label class="xCNRptLabel"><?php echo $aCompanyInfo['FTAddV1No'] . ' ' . $aCompanyInfo['FTAddV1Village'] . ' ' . $aCompanyInfo['FTAddV1Road'] . ' ' . $aCompanyInfo['FTAddV1Soi'] . ' ' . $aCompanyInfo['FTSudName'] . ' ' . $aCompanyInfo['FTDstName'] . ' ' . $aCompanyInfo['FTPvnName'] . ' ' . $aCompanyInfo['FTAddV1PostCode']; ?></label>
                            </div>
                            <!-- <div class="text-left xCNRptAddress">
                                <label class="xCNRptLabel"><?php echo $aCompanyInfo['FTSudName'] . ' ' . $aCompanyInfo['FTDstName'] . ' ' . $aCompanyInfo['FTPvnName'] . ' ' . $aCompanyInfo['FTAddV1PostCode']; ?></label>
                            </div> -->
                        <?php endif; ?>
                        <?php if ($aCompanyInfo['FTAddVersion'] == '2') : // ที่อยู่แบบรวม 
                                ?>
                            <div class="text-left xCNRptAddress">
                                <label class="xCNRptLabel"><?php echo $aCompanyInfo['FTAddV2Desc1'] . ' ' . $aCompanyInfo['FTAddV2Desc2']; ?></label>
                            </div>
                            <!-- <div class="text-left xCNRptAddress">
                                <label class="xCNRptLabel"><?php echo $aCompanyInfo['FTAddV2Desc2']; ?></label>
                            </div> -->
                        <?php endif; ?>
                        <div class="text-left xCNRptAddress">
                            <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAddrTel'] . $aCompanyInfo['FTCmpTel'] ?> <?php echo $aDataTextRef['tRptAddrFax'] . $aCompanyInfo['FTCmpFax']; ?></label>
                        </div>
                        <div class="text-left xCNRptAddress">
                            <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAddrBranch'] . $aCompanyInfo['FTBchName']; ?></label>
                        </div>
                        <div class="text-left xCNRptAddress">
                                <label ><?=$aDataTextRef['tRPCTaxNo'].' '.$aCompanyInfo['FTAddTaxNo']?></label>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <?php if ((isset($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeFrom'])) && (isset($aDataFilter['tBchCodeTo']) && !empty($aDataFilter['tBchCodeTo']))) : ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center xCNRptFilter">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptBchFrom'] . ' ' . $aDataFilter['tBchNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptBchTo'] . ' ' . $aDataFilter['tBchNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ((isset($aDataFilter['tShopCodeFrom']) && !empty($aDataFilter['tShopCodeFrom'])) && (isset($aDataFilter['tShopCodeTo']) && !empty($aDataFilter['tShopCodeTo']))) : ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล ร้านค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center xCNRptFilter">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptShopFrom'] . ' ' . $aDataFilter['tShopNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptShopTo'] . ' ' . $aDataFilter['tShopNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ((isset($aDataFilter['tPosCodeFrom']) && !empty($aDataFilter['tPosCodeFrom'])) && (isset($aDataFilter['tPosCodeTo']) && !empty($aDataFilter['tPosCodeTo']))) : ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล เครื่องจุดขาย ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center xCNRptFilter">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptPosFrom'] . ' ' . $aDataFilter['tPosNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptPosTo'] . ' ' . $aDataFilter['tPosNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))) : ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่ออกเอกสาร ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center xCNRptFilter">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptDateFrom'] . ' ' . $aDataFilter['tDocDateFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptDateTo'] . ' ' . $aDataFilter['tDocDateTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ((isset($aDataFilter['tRackCodeFrom']) && !empty($aDataFilter['tRackCodeFrom'])) && (isset($aDataFilter['tRackCodeTo']) && !empty($aDataFilter['tRackCodeTo']))) : ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล ตู้สินค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center xCNRptFilter">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptRackFrom'] . ' ' . $aDataFilter['tRackNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptRackTo'] . ' ' . $aDataFilter['tRackNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
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
            <div id="odvRptTableAdvance" class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptRentAmountFollowTimeSerailPos']; ?></th>
                            <th nowrap class="text-left xCNRptColumnHeader" rowspan="2"></th>
                            <th nowrap class="text-right xCNRptColumnHeader" rowspan="2"><?php echo $aDataTextRef['tRptRentAmtForFollowTimeNumBill']; ?></th>
                        </tr>
                        <tr>
                            <th nowrap class="text-left xCNRptColumnHeader"><?php echo $aDataTextRef['tRptRentAmtForFollowTimeTime']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php for ($nI = 0; $nI < count($aSumData); $nI++) { ?>
                                <?php $nSumTotal = 0; ?>
                                <?php for ($nFirst = 0; $nFirst < count($aDataTimeRef); $nFirst++) { ?>
                                    <tr>
                                        <?php if ($nFirst == 0) { ?>
                                            <!-- <td nowrap class="text-left xCNRptDetail" rowspan="<?php echo count($aDataTimeRef); ?>">
                                                <?php echo $aSumData[$nI]["FTPosCode"]; ?>
                                            </td> -->
                                            <td nowrap class="text-left xCNRptDetail">
                                                <?php echo $aSumData[$nI]["FTPosCode"]; ?>
                                            </td>
                                        <?php } ?>

                                        <?php $bCheck = true; ?>

                                        <?php for ($nSecond = 0; $nSecond < count($aInforTB); $nSecond++) { ?>

                                            <?php if ($aSumData[$nI]["FTPosCode"] == $aInforTB[$nSecond]["FTPosCode"] && $aDataTimeRef[$nFirst] == $aInforTB[$nSecond]["FTBlockTime"]) { ?>
                                                <?php $nSumTotal += $aInforTB[$nSecond]["FNCountDocNo"]; ?>
                                                <td nowrap class="text-left xCNRptDetail" style="width:20%">
                                                    <?php echo $aInforTB[$nSecond]["FTBlockTime"]; ?>
                                                </td>
                                                <td nowrap class="text-right xCNRptDetail" style="width:20%">
                                                    <?php echo $aInforTB[$nSecond]["FNCountDocNo"]; ?>
                                                </td>
                                                <?php $bCheck = false;
                                                                    continue; ?>
                                            <?php } ?>

                                        <?php } ?>

                                        <?php if ($bCheck) { ?>
                                            <td nowrap class="text-left xCNRptDetail" style="width:20%">
                                                <?php echo $aDataTimeRef[$nFirst]; ?>
                                            </td>
                                            <td nowrap class="text-right xCNRptDetail" style="width:20%">
                                                <?php echo "0"; ?>
                                            </td>
                                        <?php } ?>
                                    </tr>

                                <?php } ?>

                                <tr>
                                    <td nowrap colspan="2" class="text-left xCNRptDetail" style="border-top:1px solid #333 !important;border-bottom:1px solid #333 !important;background-color: #CFE2F3; ;padding: 4px;">
                                        <?php echo $aDataTextRef['tRptRentAmtForDetailSumText']; ?>
                                    </td>
                                    <td nowrap class="text-right xCNRptDetail" style="border-top:1px solid #333 !important;border-bottom:1px solid #333 !important;background-color: #CFE2F3; ;padding: 4px;">
                                        <?php echo $nSumTotal; ?>
                                    </td>
                                </tr>

                            <?php } ?>

                        <?php } else { ?>
                            <tr>
                                <td class='text-center xCNTextDetail2 xCNRptDetail' colspan='100'><?php echo $aDataTextRef['tRptNoData']; ?></td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="xCNContentReport">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <?php if ($aPagination["nTotalPage"] > 0) { ?>
                            <label class="xCNRptLabel"><?php echo $aPagination["nDisplayPage"] . ' / ' . $aPagination["nTotalPage"]; ?></label>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        var oFilterLabel = $('.report-filter .text-left label:first-child');
        var nMaxWidth = 0;
        oFilterLabel.each(function(index) {
            var nLabelWidth = $(this).outerWidth();
            if (nLabelWidth > nMaxWidth) {
                nMaxWidth = nLabelWidth;
            }
        });
        $('.report-filter .text-left label:first-child').width(nMaxWidth + 50);
    });
</script>