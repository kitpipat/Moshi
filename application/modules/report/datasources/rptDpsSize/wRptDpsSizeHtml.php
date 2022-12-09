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

    /*แนวตั้ง*/
    @media print{@page {
        size: A4 landscape;
        margin: 5mm 5mm 5mm 5mm;
        }}

    } 
    /*แนวตั้ง*/
    /*@media print{@page {size: portrait}}*/
</style>
<div id="odvRptAdjustStockVendingHtml">
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
                    <!-- Witsarut (Bell) แก้ไขที่อยู่ วันที่ 23/09/2562 -->
                    <?php if(isset($aCompanyInfo) && !empty($aCompanyInfo)) { ?>
                        <div class="text-left">
                            <label class="xCNRptCompany"><?=$aCompanyInfo['FTCmpName']?></label>
                        </div>

                        <?php if($aCompanyInfo['FTAddVersion'] == '1'){ // ที่อยู่แบบแยก ?>
                            <div class="text-left xCNRptAddress">
                                <label>
                                    <?=$aCompanyInfo['FTAddV1No'] . $aCompanyInfo['FTAddV1Road'] . ' ' . $aCompanyInfo['FTAddV1Soi']?>
                                    <?=$aCompanyInfo['FTSudName'] . ' ' . $aCompanyInfo['FTDstName'] . ' ' . $aCompanyInfo['FTPvnName'] . ' ' . $aCompanyInfo['FTAddV1PostCode']?>
                                </label>
                            </div>
                        <?php } ?>

                        <?php if($aCompanyInfo['FTAddVersion'] == '2'){ // ที่อยู่แบบรวม ?>
                            <div class="text-left xCNRptAddress">
                                <label><?=$aCompanyInfo['FTAddV2Desc1']?></label>
                            </div>
                            <div class="text-left xCNRptAddress">
                                <label><?=$aCompanyInfo['FTAddV2Desc2']?></label>
                            </div>
                        <?php } ?>

                        <div class="text-left xCNRptAddress">
                            <label class="xCNRptLabel"><?= $aDataTextRef['tRptAddrTel'] . $aCompanyInfo['FTCmpTel'] ?> <?= $aDataTextRef['tRptAddrFax'] . $aCompanyInfo['FTCmpFax'] ?></label>
                        </div>
                        <div class="text-left xCNRptAddress">
                            <label class="xCNRptLabel"><?= $aDataTextRef['tRptAddrBranch'] . $aCompanyInfo['FTBchName'] ?></label>
                        </div>
                        <div class="text-left xCNRptAddress">
                            <label><?= $aDataTextRef['tRptTaxSalePosTaxId'] . ' ' . $aCompanyInfo['FTAddTaxNo']?></label>
                        </div>

                    <?php } ?>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <?php if( (isset($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeFrom'])) && (isset($aDataFilter['tBchCodeTo']) && !empty($aDataFilter['tBchCodeTo']))) { ?>
                       <!-- Create By Witsarut 23/09/2019 update Fillter ก่อนหน้านั้นไม่มีการ Fillter ข้อมูล -->
                       <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptBchFrom'].' '.$aDataFilter['tBchNameFrom'];?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptBchTo'].' '.$aDataFilter['tBchNameTo'];?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ;?>

                    <?php if( (isset($aDataFilter['tShopCodeFrom']) && !empty($aDataFilter['tShopCodeFrom'])) && (isset($aDataFilter['tShopCodeTo']) && !empty($aDataFilter['tShopCodeTo']))) { ?>
                       <!-- Create By Witsarut 23/09/2019 update Fillter ก่อนหน้านั้นไม่มีการ Fillter ข้อมูล -->
                       <!-- ============================ ฟิวเตอร์ข้อมูล ร้านค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center xCNRptFilter">
                                    <label><?php echo $aDataTextRef['tRptShopFrom'].' '.$aDataFilter['tShopNameFrom'];?></label>&nbsp;&nbsp;
                                    <label><?php echo $aDataTextRef['tRptShopTo'].' '.$aDataFilter['tShopNameTo'];?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ;?>      

                    <?php if( (isset($aDataFilter['tPosCodeFrom']) && !empty($aDataFilter['tPosCodeFrom'])) && (isset($aDataFilter['tPosCodeTo']) && !empty($aDataFilter['tPosCodeTo']))) { ?>
                       <!-- Create By Witsarut 23/09/2019 update Fillter ก่อนหน้านั้นไม่มีการ Fillter ข้อมูล -->
                       <!-- ============================ ฟิวเตอร์ข้อมูล จุดขาย ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center xCNRptFilter">
                                    <label><?php echo $aDataTextRef['tRptPosFrom'].' '.$aDataFilter['tPosNameFrom'];?></label>&nbsp;&nbsp;
                                    <label><?php echo $aDataTextRef['tRptPosTo'].' '.$aDataFilter['tPosNameTo'];?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ;?>      

                    <?php if((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่สร้างเอกสาร ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center xCNRptFilter">

                                    <label><?php echo $aDataTextRef['tRptDateFrom'].' '.date("d/m/Y", strtotime($aDataFilter['tDocDateFrom']));?></label>&nbsp;&nbsp;
                                    <label><?php echo $aDataTextRef['tRptDateTo'].' '.date("d/m/Y", strtotime($aDataFilter['tDocDateTo']));?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 "></div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptDatePrint'].' '.date('d/m/Y').' '.$aDataTextRef['tRptTimePrint'].' '.date('H:i:s');?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="xCNContentReport">
            <div id="odvRptTableAdvance" class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th nowrap class="text-left xCNTextBold" style="width:10%;  padding: 15px;"><?php echo language('report/report/report','tRptShop');?></th><!--tRptPayby-->
                        <th nowrap class="text-left xCNTextBold" style="width:60%; padding: 10px;"><?php echo language('report/report/report','tRptLocker');?></th>
                        <th nowrap class="text-left xCNTextBold" style="width:5%; padding: 10px;"><?php echo language('report/report/report','tRptSize');?></th>
                        <th nowrap class="text-right xCNTextBold" style="width:10%;  padding: 10px;"><?php echo language('report/report/report','tRptBillAmount');?></th>
                        <th nowrap class="text-right xCNTextBold" style="width:10%;  padding: 10px;"><?php echo language('report/report/report','tRptAmount');?></th>
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
                                // echo'<pre>';
                                // print_r($aDataReport['aRptData']); 
                            ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey=>$aValue): ?>
                                <?php
                                // echo '<pre>';
                                // print_r($aValue);
                                    // Step 1 เตรียม Parameter สำหรับการ Groupping
                                        $tShpName = $aValue["FTShpName"];  
                                        $tPosCode = 'POS '.$aValue["FTPosCode"]; 

                                        $nGroupMember = $aValue["Qtybill"]; 
                                        $nRowPartID = $aValue["FNRowPartID"]; 
                                ?>
                                <?php
                                    //Step 2 Groupping data
                                    $aGrouppingData = array($tShpName,$tPosCode);
                                    // Parameter
                                    // $nRowPartID      = ลำดับตามกลุ่ม
                                    // $aGrouppingData  = ข้อมูลสำหรับ Groupping
                                    FCNtHRPTHeadGroupping($nRowPartID,$aGrouppingData);
                                ?>
                                <!--  Step 2 แสดงข้อมูลใน TD  -->
                                <tr>
                                    <td nowrap class="text-left xCNRptDetail"></td>
                                    <td nowrap class="text-left xCNRptDetail"></td>
                                    <td nowrap class="text-left xCNRptDetail"><?php echo $aValue["FTPzeName"];?></td>
                                    <td nowrap class="text-right xCNRptDetail"><?php echo number_format($aValue["FTXhdQty"]);?></td>
                                    <td nowrap class="text-right xCNRptDetail"><?php echo number_format($aValue["FTXhdQty"]);?></td>
                                </tr>
                                <?php
                                    //Step 3 : เตรียม Parameter สำหรับ Summary Sub Footer

                                    // $nSubSumAjdUnitQty  = $aValue["FCXrcNetFooter"];

                                    $nSubSumAjdWahB4Adj = number_format($aValue["Qtybill_All"]);
                                    $aSumFooter         = array($aDataTextRef['tRptTotalAllSale'],'N','N',$nSubSumAjdWahB4Adj,'0');

                                    //Step 4 : สั่ง Summary SubFooter
                                    //Parameter 
                                    //$nGroupMember     = จำนวนข้อมูลทั้งหมดในกลุ่ม
                                    //$nRowPartID       = ลำดับข้อมูลในกลุ่ม
                                    //$aSumFooter       =  ข้อมูล Summary SubFooter

                                    if ($nRowPartID == $nGroupMember) {
                                        echo '<tr class="">';
                                        for ($i = 0; $i < count($aSumFooter); $i++) {
                                            if ($i == 0) {
                                                $tStyle = 'text-align:left;border-top: dashed 1px #333 !important;border-bottom:dashed 1px #333 !important;';
                                            } else {
                                                $tStyle = 'text-align:right;border-top: dashed 1px #333 !important;border-bottom:dashed 1px #333 !important;';
                                            }
                                            if ($aSumFooter[$i] != 'N') {
                                                $tFooterVal = $aSumFooter[$i];
                                            } else {
                                                $tFooterVal = '';
                                            }
                                            echo '<td class="xCNRptSubFooter" style="' . $tStyle . ' ;padding: 4px;">' . $tFooterVal . '</td>';
                                        }
                                        echo '</tr>';
                                    }

                                    // FCNtHRPTSumSubFooter($nGroupMember,$nRowPartID,$aSumFooter);


                                    //Step 5 เตรียม Parameter สำหรับ SumFooter
                                    // $nSumFooterAjdWahB4Adj  = number_format($aValue["FCSdtSubQty"],2);
                                    // $nSumFooterAjdUnitQty   = number_format($aValue["FCXrcNetFooter"],2);
                                    // $paFooterSumData        = array('รวมทั้งสิ้น','N','N',$nSumFooterAjdUnitQty);
                                ?>
                            <?php endforeach;?>
                            <?php
                                //Step 6 : สั่ง Summary Footer
                                // $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                // $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                                // FCNtHRPTSumFooter($nPageNo,$nTotalPage,$paFooterSumData);
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






