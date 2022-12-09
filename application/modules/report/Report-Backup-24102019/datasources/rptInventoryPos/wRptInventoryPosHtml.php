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
    /*@media print{@page {size: landscape}}*/ 
    /*แนวตั้ง*/
    @media print{@page {size: portrait}}
</style>

<div id="odvRptProductTransferHtml">
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
                            <label class="xCNRptLabel"><?= $aDataTextRef['tRptAddrTel'] . $aCompanyInfo['FTCmpTel'] ?> <?= $aDataTextRef['tRptAddrFax'] . $aCompanyInfo['FTCmpFax'] ?></label>
                        </div>
                        <div class="text-left">
                            <label class="xCNRptLabel"><?= $aDataTextRef['tRptAddrBranch'] . $aCompanyInfo['FTBchName'] ?></label>
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
                    <?php }; ?>

                    <?php if ((isset($aDataFilter['tWahNameFrom']) && !empty($aDataFilter['tWahNameFrom'])) && (isset($aDataFilter['tWahNameTo']) && !empty($aDataFilter['tWahNameTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล คลังสินค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptFromWareHouse'] . ' ' . $aDataFilter['tWahNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptToWareHouse'] . ' ' . $aDataFilter['tWahNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
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
                            <th nowrap class="text-left xCNTextBold" style="width:25%; padding: 15px;"><?php echo $aDataTextRef['tRptWahName']; ?></th>
                            <th nowrap class="text-left xCNTextBold" style="width:20%; padding: 10px;"><?php echo $aDataTextRef['tRptPdtCode']; ?></th>
                            <th nowrap class="text-left xCNTextBold" style="width:35%; padding: 10px;"><?php echo $aDataTextRef['tRptPdtName']; ?></th>
                            <th nowrap class="text-right xCNTextBold" style="width:20%; padding: 10px;"><?php echo $aDataTextRef['tRptPdtInven']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])) { ?>
                            <?php
                            // Set ตัวแปร SumSubFooter และ ตัวแปร SumFooter
                            $nSubSumAjdWahB4Adj = 0;
                            $nSubSumAjdUnitQty = 0;
                            // echo '<pre>'; 
                            // print_r($aDataReport);exit();
                            ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey => $aValue) { ?>
                                <?php
                                // Step 1 เตรียม Parameter สำหรับการ Groupping
                                $tWahCode = $aValue["FTWahName"];
                                $nGroupMember = $aValue["FNRptGroupMember"];
                                $nRowPartID = $aValue["FNRowPartID"];
                                // echo $nRowPartID.'<br>';
                                ?>
                                <?php
                                // Step 2 Groupping data
                                $aGrouppingData = array($tWahCode);
                                // echo '<pre>';print_r($aGrouppingData);
                                // Parameter
                                // $nRowPartID      = ลำดับตามกลุ่ม
                                // $aGrouppingData  = ข้อมูลสำหรับ Groupping
                                FCNtHRPTHeadGroupping($nRowPartID, $aGrouppingData);
                                ?>
                                <!--  Step 2 แสดงข้อมูลใน TD  -->
                                <tr>
                                    <td></td>
                                    <td nowrap class="text-left"><?php echo $aValue["FTPdtCode"]; ?></td>
                                    <td nowrap class="text-left"><?php echo $aValue["FTPdtName"]; ?></td>
                                    <td nowrap class="text-right"><?php echo number_format($aValue["FCStkQty"], $nOptDecimalShow) ?></td>
                                </tr>

                                <?php
                                // Step 3 : เตรียม Parameter สำหรับ Summary Sub Footer
                                $nSubSumQty = number_format($aValue["FCStkQty_SubTotal"], 2);

                                $aSumFooter = array($aDataTextRef['tRptTotalSub'], 'N', 'N', $nSubSumQty);

                                // Step 4 : สั่ง Summary SubFooter
                                /* $nGroupMember     = จำนวนข้อมูลทั้งหมดในกลุ่ม
                                  $nRowPartID       = ลำดับข้อมูลในกลุ่ม
                                  $aSumFooter       =  ข้อมูล Summary SubFooter */

                                FCNtHRPTSumSubFooter($nGroupMember, $nRowPartID, $aSumFooter);
                                // // Step 5 เตรียม Parameter สำหรับ SumFooter
                                // $nSumFooterAjdWahB4Adj  = number_format($aValue["FCPdtCostEx_Footer"],2);
                                // $nSumFooterAjdUnitQty   = number_format($aValue["FCStkQty_Footer"],2);
                                $nSumCostExQtyQty = number_format($aValue["FCStkQty_Footer"], $nOptDecimalShow);

                                $paFooterSumData = array($aDataTextRef['tRptTotalFooter'], 'N', 'N', $nSumCostExQtyQty);
                                ?>
                            <?php } ?>
                            <?php
                            // Step 6 : สั่ง Summary Footer
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






















