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
        border: 0px solid #FFF !important;
    }

    .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
        border-top: 1px solid black !important;
        border-bottom : 1px solid black !important;
    }

    .table>tfoot>tr>td{
        border-top: 1px solid black !important;
        border-bottom : 1px solid black !important;
    }

</style>

<div id="odvRptSaleVatInvoiceByBillHtml">
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
                    <!-- Witsarut (Bell) แก้ไขที่อยู่ วันที่ 24/09/2562 -->
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
                                <label class="xCNRptLabel"><?php echo $aCompanyInfo['FTAddV2Desc1'] ?></label>
                            </div>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?php echo $aCompanyInfo['FTAddV2Desc2'] ?></label>
                            </div>
                        <?php } ?>

                        <div class="text-left">
                            <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAddrTel'] . $aCompanyInfo['FTCmpTel'] ?> <?php echo $aDataTextRef['tRptAddrFax'] . $aCompanyInfo['FTCmpFax']; ?></label>
                        </div>

                        <div class="text-left">
                            <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAddrBranch'] . $aCompanyInfo['FTBchName']; ?></label>
                        </div>

                    <?php } ?>                
                </div>
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 report-filter">
                    <?php if ((isset($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeFrom'])) && (isset($aDataFilter['tBchCodeTo']) && !empty($aDataFilter['tBchCodeTo']))) { ?>
                        <!-- Create By Witsarut 24/09/2019 update Fillter ก่อนหน้านั้นไม่มีการ Fillter ข้อมูล -->
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
                        <!-- Create By Witsarut 24/09/2019 update Fillter ก่อนหน้านั้นไม่มีการ Fillter ข้อมูล -->
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
                        <!-- Create By Witsarut 24/09/2019 update Fillter ก่อนหน้านั้นไม่มีการ Fillter ข้อมูล -->
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

                    <?php if ((isset($aDataFilter['tRcvCodeFrom']) && !empty($aDataFilter['tRcvCodeFrom'])) && (isset($aDataFilter['tRcvCodeTo']) && !empty($aDataFilter['tRcvCodeTo']))) { ?>
                        <!-- Create By Witsarut 24/09/2019 update Fillter ก่อนหน้านั้นไม่มีการ Fillter ข้อมูล -->
                        <!-- ============================ ฟิวเตอร์ข้อมูล ประเภทการชำระเงิน ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptRcvFrom'] . ' ' . $aDataFilter['tRcvNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptRcvTo'] . ' ' . $aDataFilter['tRcvNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่สร้างเอกสาร ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptDateFrom'] . ' ' . $aDataFilter['tDocDateFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptDateTo'] . ' ' . $aDataFilter['tDocDateTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
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
                    // Check Page Footer Show Total Sum
                    $oOptionKoolRpt = array(
                        "dataSource" => $this->dataStore("RptLocPayMent"),
                        "cssClass" => array(
                            "table" => "table",
                        ),
                        "showFooter" => true,
                        "columns" => array(
                            "FTRcvName" => array(
                                "label" => $aDataTextRef['tRptPayby'],
                                "formatValue" => function($tValue, $aDataRowRpt) {
                                    $tRptRcvNameEmpty = $this->params['aDataTextRef']['tRptRcvNameEmpty'];
                                    if ((isset($aDataRowRpt['FTRcvCode']) && !empty($aDataRowRpt['FTRcvCode'])) && (isset($aDataRowRpt['FTRcvName']) && !empty($aDataRowRpt['FTRcvName']))) {
                                        return $tValue;
                                    } else {
                                        return $tRptRcvNameEmpty;
                                    }
                                },
                                "cssStyle" => array(
                                    "th" => "text-align:left; white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                    "td" => "text-align:left;",
                                    "tf" => "text-align:left; font-weight: bold; background-color:#CFE2F3;"
                                ),
                                "footerText" => "<b>รวม</b>"
                            ),
                            "NET" => array(
                                "label" => $aDataTextRef['tRptTotal'],
                                "type" => "number",
                                "decimals" => 2,
                                "footer" => "sum",
                                "cssStyle" => array(
                                    "th" => "text-align: right; white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                    "td" => "text-align:right;",
                                    "tf" => "text-align:right; font-weight: bold; background-color:#CFE2F3;"
                                )
                            )
                        )
                    );
                    Table::create($oOptionKoolRpt);
                    ?>
                <?php } else { ?>
                    <table class="table">
                        <thead>
                        <th nowrap  class="text-left" style="width:10%; background-color:#CFE2F3;"><?php echo $aDataTextRef['tRptPayby']; ?></th>    
                        <th nowrap  class="text-right" style="width:10%; background-color:#CFE2F3;"><?php echo $aDataTextRef['tRptTotal']; ?></th>    
                        </thead>
                        <tbody>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main', 'tCMNNotFoundData'); ?></td></tr>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>

        <div class="xCNFooterPageRpt">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                    <!-- <label class="xCNRptLabel"><?php echo $nCurrentPage . ' / ' . $nAllPage; ?></label> -->
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









