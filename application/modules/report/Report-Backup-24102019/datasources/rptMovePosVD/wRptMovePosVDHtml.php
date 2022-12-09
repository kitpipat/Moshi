<?php
$aCompanyInfo = $aDataViewRpt['aCompanyInfo'];
$aDataFilter = $aDataViewRpt['aDataFilter'];
$aDataTextRef = $aDataViewRpt['aDataTextRef'];
$aDataReport = $aDataViewRpt['aDataReport'];
$nOptDecimalShow = $aDataViewRpt['nOptDecimalShow'];
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
                            <label class="xCNRptLabel"><?php echo $aCompanyInfo['FTCmpName']; ?></label>
                        </div>

                        <?php if ($aCompanyInfo['FTAddVersion'] == '1') { // ที่อยู่แบบแยก ?>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?php echo $aCompanyInfo['FTAddV1No'] . ' ' . $aCompanyInfo['FTAddV1Village'] . ' ' . $aCompanyInfo['FTAddV1Road'] . ' ' . $aCompanyInfo['FTAddV1Soi']; ?></label>
                            </div>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?php echo $aCompanyInfo['FTSudName'] . ' ' . $aCompanyInfo['FTDstName'] . ' ' . $aCompanyInfo['FTPvnName'] . ' ' . $aCompanyInfo['FTAddV1PostCode']; ?></label>
                            </div>
                        <?php } ?>

                        <?php if ($aCompanyInfo['FTAddVersion'] == '2') { // ที่อยู่แบบรวม ?>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?php echo $aCompanyInfo['FTAddV2Desc1']; ?></label>
                            </div>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?php echo $aCompanyInfo['FTAddV2Desc2']; ?></label>
                            </div>
                        <?php } ?>

                        <div class="text-left">
                            <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAddrTel'] . $aCompanyInfo['FTCmpTel']; ?> <?php echo $aDataTextRef['tRptAddrFax'] . $aCompanyInfo['FTCmpFax']; ?></label>
                        </div>
                        <div class="text-left">
                            <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptAddrBranch'] . $aCompanyInfo['FTBchName']; ?></label>
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

                    <?php if ((isset($aDataFilter['tPdtNameFrom']) && !empty($aDataFilter['tPdtNameFrom'])) && (isset($aDataFilter['tPdtNameTo']) && !empty($aDataFilter['tPdtNameTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล สินค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tPdtCodeFrom'] . ' ' . $aDataFilter['tPdtNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tPdtCodeTo'] . ' ' . $aDataFilter['tPdtNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ((isset($aDataFilter['tWahNameFrom']) && !empty($aDataFilter['tWahNameFrom'])) && (isset($aDataFilter['tWahNameTo']) && !empty($aDataFilter['tWahNameTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล คลังสินค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptFromWareHouse'] . ' ' . $aDataFilter['tWahNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptToWareHouse'] . ' ' . $aDataFilter['tWahNameTo']; ?></label>
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
                            <th nowrap class="text-left xCNTextBold" style="width:20%;  padding: 10px;"><?php echo language('report/report/report', 'tRptWahName'); ?></th>
                            <th nowrap class="text-left xCNTextBold" style="width:7%;   padding: 10px;"><?php echo language('report/report/report', 'tRptPdtCode'); ?></th>
                            <th nowrap class="text-left xCNTextBold" style="width:16%;  padding: 10px;"><?php echo language('report/report/report', 'tRptPdtName'); ?></th>
                            <th nowrap class="text-left xCNTextBold" style="width:10%;  padding: 10px;"><?php echo language('report/report/report', 'tRptDoc'); ?></th>
                            <th nowrap class="text-left xCNTextBold" style="width:7%;   padding: 10px;"><?php echo language('report/report/report', 'tRptDate'); ?></th>
                            <th nowrap class="text-right xCNTextBold" style="width:7%;   padding: 10px;"><?php echo language('report/report/report', 'tRptBringF'); ?></th>
                            <th nowrap class="text-right xCNTextBold" style="width:7%;   padding: 10px;"><?php echo language('report/report/report', 'tRptIn'); ?></th>
                            <th nowrap class="text-right xCNTextBold" style="width:7%;   padding: 10px;"><?php echo language('report/report/report', 'tRptEx'); ?></th>
                            <th nowrap class="text-right xCNTextBold" style="width:7%;   padding: 10px;"><?php echo language('report/report/report', 'tRptSale'); ?></th>
                            <th nowrap class="text-right xCNTextBold" style="width:8%;   padding: 10px;"><?php echo language('report/report/report', 'tRptAdjud'); ?></th>
                            <th nowrap class="text-right xCNTextBold" style="width:8%;   padding: 10px;"><?php echo language('report/report/report', 'tRptInven'); ?></th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php
                            // ประกาศตัวแปร sumsub
                            $nSubSumData = 0;
                            $nSumFooterDataAll = 0;
                            ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>
                                <?php
                                // Step 1 เตรียม Parameter สำหรับการ Groupping
                                $tWahCode = $aValue["FTWahName"];
                                $tPdtCode = $aValue["FTPdtCode"];
                                $tPdtName = $aValue["FTPdtName"];

                                $nSubSumStkQtyBal = 0;
                                $nSubSumStkQtyMonEnd = 0;
                                $nSubSumStkQtyIn = 0;
                                $nSubSumStkQtyOut = 0;
                                $nSubSumStkQtySale = 0;
                                $nSubStkQtyAdj = 0;

                                $nSumFooterStkQtyBal_Footer = 0;
                                $nSumFooterStkQtyMonEnd = 0;
                                $nSumFooterSFCStkQtyIn = 0;
                                $nSumFooterStkQtyOut = 0;
                                $nSumFooterStkQtySale = 0;
                                $nSumFooterStkQtyAdj = 0;

                                $nGroupMember = $aValue["FNRptGroupMember"];
                                $nRowPartID = $aValue["FNRowPartID"];
                                ?>
                                <?php
                                // Step 2 Groupping data
                                $aGrouppingData = array($tWahCode, $tPdtCode, $tPdtName);

                                // $nRowPartID      = ลำดับตามกลุ่ม
                                // $aGrouppingData  = ข้อมูลสำหรับ Groupping
                                FCNtHRPTHeadGroupping($nRowPartID, $aGrouppingData);
                                ?>

                                <!--  Step 2 แสดงข้อมูลใน TD  -->
                                <?php
                                $nStkQtySale = $aValue["FCStkQtySaleDN"];
                                $nStkQtyCN = $aValue["FCStkQtyCN"];

                                $nStkQtyMonEnd = $aValue["FCStkQtyMonEnd"];
                                $nStkQtyIn = $aValue["FCStkQtyIn"];
                                $nStkQtyOut = $aValue["FCStkQtyOut"];
                                $nStkQtySale = $nStkQtySale - $nStkQtyCN;
                                $nStkQtyAdj = $aValue["FCStkQtyAdj"];
                                ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td nowrap class="number text-left" style="padding:7px;"><?php echo $aValue["FTStkDocNo"]; ?></td>
                                    <td nowrap class="number text-left" style="padding:7px;"><?php echo date("Y-m-d H:i:s", strtotime($aValue["FDStkDate"])); ?></td>
                                    <td nowrap class="number text-right" style="padding:7px;"><?php echo number_format($aValue["FCStkQtyMonEnd"], $nOptDecimalShow); ?></td>
                                    <td nowrap class="number text-right" style="padding:7px;"><?php echo number_format($aValue["FCStkQtyIn"], $nOptDecimalShow); ?></td>
                                    <td nowrap class="number text-right" style="padding:7px;"><?php echo number_format($aValue["FCStkQtyOut"], $nOptDecimalShow); ?></td>
                                    <td nowrap class="number text-right" style="padding:7px;"><?php echo number_format($nStkQtySale, $nOptDecimalShow) ?></td>
                                    <td nowrap class="number text-right" style="padding:7px;"><?php echo number_format($aValue['FCStkQtyAdj'], $nOptDecimalShow) ?></td>
                                    <td nowrap class="number text-right" style="padding:7px;"><?php echo number_format($aValue['FCStkQtyBal'], $nOptDecimalShow) ?> </td>
                                </tr>
                                <?php
                                //Step 3 : เตรียม Parameter สำหรับ Summary Sub Footer
                                $nSubSumStkQtyBal = number_format($aValue["FCStkQtyBal_SUB"], $nOptDecimalShow);
                                $nSubSumStkQtyMonEnd = number_format($aValue["FCStkQtyMonEnd_SUB"], $nOptDecimalShow);
                                $nSubSumStkQtyIn = number_format($aValue["FCStkQtyIn_SUB"], $nOptDecimalShow);
                                $nSubSumStkQtyOut = number_format($aValue["FCStkQtyOut_SUB"], $nOptDecimalShow);
                                $nSubSumStkQtySale = number_format($aValue["FCStkQtySale_SUB"], $nOptDecimalShow);
                                $nSubStkQtyAdj = number_format($aValue["FCStkQtyAdj_SUB"], $nOptDecimalShow);

                                $aSumFooter = array($aDataTextRef['tRptTotalSub'], 'N', 'N', 'N', 'N', $nSubSumStkQtyMonEnd, $nSubSumStkQtyIn, $nSubSumStkQtyOut, $nSubSumStkQtySale, $nSubStkQtyAdj, $nSubSumStkQtyBal);

                                //Step 4 : สั่ง Summary SubFooter
                                //Parameter 
                                //$nGroupMember     = จำนวนข้อมูลทั้งหมดในกลุ่ม
                                //$nRowPartID       = ลำดับข้อมูลในกลุ่ม
                                //$aSumFooter       =  ข้อมูล Summary SubFooter
                                $nResult = FCNtHRPTSumSubFooter($nGroupMember, $nRowPartID, $aSumFooter);


                                //Step 5 เตรียม Parameter สำหรับ SumFooter 
                                $nSumFooterStkQtyBal_Footer = number_format($aValue["FCStkQtyBal_Footer"], $nOptDecimalShow);
                                $nSumFooterStkQtyMonEnd = number_format($aValue["FCStkQtyMonEnd_Footer"], $nOptDecimalShow);
                                $nSumFooterSFCStkQtyIn = number_format($aValue["FCStkQtyIn_Footer"], $nOptDecimalShow);
                                $nSumFooterStkQtyOut = number_format($aValue["FCStkQtyOut_Footer"], $nOptDecimalShow);
                                $nSumFooterStkQtySale = number_format($aValue["FCStkQtySale_Footer"], $nOptDecimalShow);
                                $nSumFooterStkQtyAdj = number_format($aValue["FCStkQtyAdj_Footer"], $nOptDecimalShow);

                                $paFooterSumData = array($aDataTextRef['tRptTotalFooter'], 'N', 'N', 'N', 'N', $nSumFooterStkQtyMonEnd, $nSumFooterSFCStkQtyIn, $nSumFooterStkQtyOut, $nSumFooterStkQtySale, $nSumFooterStkQtyAdj, $nSumFooterStkQtyBal_Footer);
                                ?>
                            <?php } ?>
                            <?php
                            //Step 6 : สั่ง Summary Footer
                            $nPageNo = $aDataReport["aPagination"]["nDisplayPage"];
                            $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                            FCNtHRPTSumFooter($nPageNo, $nTotalPage, $paFooterSumData);
                            ?>
                        <?php } else { ?>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo $aDataTextRef['tRptAdjStkNoData']; ?></td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="xCNFooterPageRpt">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <?php if ($aDataReport["aPagination"]["nTotalPage"] > 0) { ?>
                            <label class="xCNRptLabel"><?php echo $aDataReport["aPagination"]["nDisplayPage"] . ' / ' . $aDataReport["aPagination"]["nTotalPage"]; ?></label>
                        <?php } ?>
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











