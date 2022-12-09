<?php

use \koolreport\widgets\koolphp\Table;

$nCurrentPage = $this->params['nCurrentPage'];
$nAllPage = $this->params['nAllPage'];
$aDataTextRef = $this->params['aDataTextRef'];
$aDataFilter = $this->params['aFilterReport'];
$aDataReport = $this->params['aDataReturn'];
$aCompanyInfo = $this->params['aCompanyInfo'];
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
    .table>tfoot>tr{
        border-top: 1px solid black !important;
        background-color: #CFE2F3 !important;
        border-bottom : 6px double black !important;
    }
</style>

<div id="odvRptSaleShopByDateHtml">
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
                    <!--  Create By Witsarut (Bell) แก้ไขเรื่องที่อยู่ และ Fillter -->
                    <?php if (isset($aCompanyInfo) && !empty($aCompanyInfo)) { ?>
                        <div class="text-left">
                            <label class="xCNRptLabel"><?php echo $aCompanyInfo['FTCmpName']; ?></label>
                        </div>

                        <?php if ($aCompanyInfo['FTAddVersion'] == '1') { // ที่อยู่แบบแยก  ?>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?php echo $aCompanyInfo['FTAddV1No'] . ' ' . $aCompanyInfo['FTAddV1Village'] . ' ' . $aCompanyInfo['FTAddV1Road'] . ' ' . $aCompanyInfo['FTAddV1Soi']; ?></label>
                            </div>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?php echo $aCompanyInfo['FTSudName'] . ' ' . $aCompanyInfo['FTDstName'] . ' ' . $aCompanyInfo['FTPvnName'] . ' ' . $aCompanyInfo['FTAddV1PostCode']; ?></label>
                            </div>
                        <?php } ?>

                        <?php if ($aCompanyInfo['FTAddVersion'] == '2') { // ที่อยู่แบบรวม  ?>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?php echo $aCompanyInfo['FTAddV2Desc1']; ?></label>
                            </div>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?php echo $aCompanyInfo['FTAddV2Desc2']; ?></label>
                            </div>
                        <?php } ?>

                        <div class="text-left">
                            <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAddrTel'] . $aCompanyInfo['FTCmpTel'] ?> <?= $aDataTextRef['tRptAddrFax'] . $aCompanyInfo['FTCmpFax']; ?></label>
                        </div>
                        <div class="text-left">
                            <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAddrBranch'] . $aCompanyInfo['FTBchName']; ?></label>
                        </div>
                    <?php } ?>
                </div>

                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 report-filter">
                    <?php if ((isset($aDataFilter['tMerCodeFrom']) && !empty($aDataFilter['tMerCodeFrom'])) && (isset($aDataFilter['tMerCodeTo']) && !empty($aDataFilter['tMerCodeTo']))) { ?>
                        <!--  Create By Witsarut (Bell) แก้ไขเรื่องที่อยู่ และ Fillter -->
                        <!-- ============================ ฟิวเตอร์ข้อมูล กลุ่มธุรกิจ ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptMerFrom'] . ' ' . $aDataFilter['tMerNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptMerTo'] . ' ' . $aDataFilter['tMerNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php }; ?>

                    <?php if ((isset($aDataFilter['tShopCodeFrom']) && !empty($aDataFilter['tShopCodeFrom'])) && (isset($aDataFilter['tShopCodeTo']) && !empty($aDataFilter['tShopCodeTo']))) { ?>
                        <!--  Create By Witsarut (Bell) แก้ไขเรื่องที่อยู่ และ Fillter -->
                        <!-- ============================ ฟิวเตอร์ข้อมูล ร้านค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptShopFrom'] . ' ' . $aDataFilter['tShopNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptShopTo'] . ' ' . $aDataFilter['tShopNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php }; ?>

                    <?php if ((isset($aDataFilter['tPosCodeFrom']) && !empty($aDataFilter['tPosCodeFrom'])) && (isset($aDataFilter['tPosCodeTo']) && !empty($aDataFilter['tPosCodeTo']))) { ?>
                        <!--  Create By Witsarut (Bell) แก้ไขเรื่องที่อยู่ และ Fillter -->
                        <!-- ============================ ฟิวเตอร์ข้อมูล เครื่องจุดขาย ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptPosFrom'] . ' ' . $aDataFilter['tPosNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptPosTo'] . ' ' . $aDataFilter['tPosNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php }; ?>

                    <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))): ?>
                        <!--  Create By Witsarut (Bell) แก้ไขเรื่องที่อยู่ และ Fillter -->
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่สร้าง report ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptDateFrom'] . ' ' . $aDataFilter['tDocDateFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptDateTo'] . ' ' . $aDataFilter['tDocDateTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptDatePrint'] . ' ' . date('d/m/Y') . ' ' . $aDataTextRef['tRptTimePrint'] . ' ' . date('H:i:s'); ?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="xCNContentReport">
            <div id="odvTableKoolReport" class="table-responsive">
                <?php if (isset($aDataReport['rtCode']) && !empty($aDataReport['rtCode']) && $aDataReport['rtCode'] == '1') { ?>
                    <?php
                    if ($nCurrentPage == $nAllPage) {
                        Table::create(array(
                            "dataSource" => $this->dataStore("RptOpenSysAdmin"),
                            "cssClass" => array(
                                "table" => "table",
                            ),
                            "headers" => array(
                                array(
                                    "$aDataTextRef[tRptLocCode]" => array(
                                        "style" => "text-align:left",
                                        "rowSpan" => 1
                                    ),
                                    "$aDataTextRef[tRptDateOpen]" => array(
                                        "style" => "text-align:left",
                                        "rowSpan" => 1
                                    ),
                                    "$aDataTextRef[tRptChannelGrp]" => array(
                                        "style" => "text-align:left",
                                        "rowSpan" => 1
                                    ),
                                    "$aDataTextRef[tRptNoChannel]" => array(
                                        "style" => "text-align:left",
                                        "rowSpan" => 1
                                    ),
                                    "$aDataTextRef[tRptReason]" => array(
                                        "style" => "text-align:left",
                                        "colSpan" => 1
                                    ),
                                    "$aDataTextRef[tRptUsrOpen]" => array(
                                        "style" => "text-align:left",
                                        "colSpan" => 1
                                    ),
                                )
                            ),
                            "columns" => array(
                                'rtPosCode' => array(
                                    "cssStyle" => array(
                                        "th" => "display:none",
                                    ),
                                ),
                                'rtDateTime' => array(
                                    "formatValue" => function($value, $row) {
                                        return empty($value) ? "" : date("Y-m-d H:i:s", strtotime($value));
                                    },
                                    "cssStyle" => array(
                                        "th" => "display:none",
                                    ),
                                ),
                                'rtRakName' => array(
                                    "cssStyle" => array(
                                        "th" => "display:none",
                                    ),
                                ),
                                'rtHisLayNo' => array(
                                    "cssStyle" => array(
                                        "th" => "display:none",
                                    ),
                                ),
                                'rtHisRsnName' => array(
                                    "cssStyle" => array(
                                        "th" => "display:none"
                                    ),
                                ),
                                'rtHisUsrName' => array(
                                    "cssStyle" => array(
                                        "th" => "display:none"
                                    ),
                                ),
                            ),
                            "removeDuplicate" => array("rtPosCode", "rtDateTime")
                        ));
                    } else {
                        Table::create(array(
                            "dataSource" => $this->dataStore("RptOpenSysAdmin"),
                            "cssClass" => array(
                                "table" => "table table-bordered StyCss",
                            ),
                            "headers" => array(
                                array(
                                    "$aDataTextRef[tRptLocCode]" => array(
                                        "style" => "text-align:left",
                                        "rowSpan" => 1
                                    ),
                                    "$aDataTextRef[tRptDateOpen]" => array(
                                        "style" => "text-align:left",
                                        "rowSpan" => 1
                                    ),
                                    "$aDataTextRef[tRptChannelGrp]" => array(
                                        "style" => "text-align:left",
                                        "rowSpan" => 1
                                    ),
                                    "$aDataTextRef[tRptNoChannel]" => array(
                                        "style" => "text-align:left",
                                        "rowSpan" => 1
                                    ),
                                    "$aDataTextRef[tRptReason]" => array(
                                        "style" => "text-align:left",
                                        "colSpan" => 1
                                    ),
                                    "$aDataTextRef[tRptUsrOpen]" => array(
                                        "style" => "text-align:left",
                                        "colSpan" => 1
                                    ),
                                )
                            ),
                            "columns" => array(
                                'rtPosCode' => array(
                                    "cssStyle" => array(
                                        "th" => "display:none",
                                    ),
                                ),
                                'rtDateTime' => array(
                                    "cssStyle" => array(
                                        "th" => "display:none",
                                    ),
                                ),
                                'rtRakName' => array(
                                    "cssStyle" => array(
                                        "th" => "display:none",
                                    ),
                                ),
                                'rtHisLayNo' => array(
                                    "cssStyle" => array(
                                        "th" => "display:none",
                                    ),
                                ),
                                'rtHisRsnName' => array(
                                    "cssStyle" => array(
                                        "th" => "display:none"
                                    ),
                                ),
                                'rtCreateName' => array(
                                    "cssStyle" => array(
                                        "th" => "display:none"
                                    ),
                                ),
                            ),
                            "removeDuplicate" => array("rtPosCode", "rtDateTime")
                        ));
                    }
                    ?>
                <?php } else { ?>
                    <table class="table">
                        <thead>
                        <th nowrap  class="text-center" style="width:10%"><?php echo @$aDataTextRef['tRptLocCode']; ?></th>
                        <th nowrap  class="text-center" style="width:10%"><?php echo @$aDataTextRef['tRptDateOpen']; ?></th>
                        <th nowrap  class="text-center" style="width:10%"><?php echo @$aDataTextRef['tRptChannelGrp']; ?></th>
                        <th nowrap  class="text-center" style="width:10%"><?php echo @$aDataTextRef['tRptNoChannel']; ?></th>
                        <th nowrap  class="text-center" style="width:10%"><?php echo @$aDataTextRef['tRptReason']; ?></th>
                        <th nowrap  class="text-center" style="width:10%"><?php echo @$aDataTextRef['tRptUsrOpen']; ?></th>
                        </thead>
                        <tbody>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main', 'tCMNNotFoundData') ?></td></tr>
                        </tbody>
                    </table>
                <?php } ?>   
            </div>
        </div>
        <div class="xCNFooterPageRpt">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <label class="xCNRptLabel"><?php echo $nCurrentPage . ' / ' . $nAllPage; ?></label>
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








