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
                            <label class="xCNRptLabel"><?= $aDataTextRef['tRptAddrTel'] . $aCompanyInfo['FTCmpTel'] ?> <?= $aDataTextRef['tRptAddrFax'] . $aCompanyInfo['FTCmpFax'] ?></label>
                        </div>
                        <div class="text-left">
                            <label class="xCNRptLabel"><?= $aDataTextRef['tRptAddrBranch'] . $aCompanyInfo['FTBchName'] ?></label>
                        </div>

                    <?php } ?>
                </div>
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 report-filter">
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
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptShopFrom'].' '.$aDataFilter['tShopNameFrom'];?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptShopTo'].' '.$aDataFilter['tShopNameTo'];?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ;?>      

                    <?php if( (isset($aDataFilter['tPosCodeFrom']) && !empty($aDataFilter['tPosCodeFrom'])) && (isset($aDataFilter['tPosCodeTo']) && !empty($aDataFilter['tPosCodeTo']))) { ?>
                       <!-- Create By Witsarut 23/09/2019 update Fillter ก่อนหน้านั้นไม่มีการ Fillter ข้อมูล -->
                       <!-- ============================ ฟิวเตอร์ข้อมูล จุดขาย ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptPosFrom'].' '.$aDataFilter['tPosNameFrom'];?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptPosTo'].' '.$aDataFilter['tPosNameTo'];?></label>
                                </div>
                            </div>
                        </div>
                    <?php } ;?>      

                    <?php if((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่สร้างเอกสาร ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptDateFrom'].' '.$aDataFilter['tDocDateFrom'];?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptDateTo'].' '.$aDataFilter['tDocDateTo'];?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                </div>
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
                        <th nowrap class="text-left xCNTextBold" style="width:30%;  padding: 15px;"><?php echo  language('report/report/report','ร้านค้า');?></th><!--tRptPayby-->
                        <th nowrap class="text-left xCNTextBold" style="width:40%; padding: 10px;"><?php echo  language('report/report/report','ตู้ล็อคเกอร์');?></th>
                        <th nowrap class="text-left xCNTextBold" style="width:20%; padding: 10px;"><?php echo  language('report/report/report','ขนาด');?></th>
                        <th nowrap class="text-right xCNTextBold" style="width:10%;  padding: 10px;"><?php  echo  language('report/report/report','จำนวนบิลฝาก');?></th>
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
                                    <td class="text-left"></td>
                                    <td class="text-left"><?php /*echo $aValue["FTPosCode"];*/?></td>
                                    <td class="text-left number"><?php echo $aValue["FTPzeName"];?></td>
                                    <td class="text-right"><?php echo number_format($aValue["FTXhdQty"]);?></td>
                                </tr>
                                <?php
                                    //Step 3 : เตรียม Parameter สำหรับ Summary Sub Footer

                                    // $nSubSumAjdUnitQty  = $aValue["FCXrcNetFooter"];

                                    $nSubSumAjdWahB4Adj = number_format($aValue["Qtybill_All"]);
                                    $aSumFooter         = array('รวม','N','N',$nSubSumAjdWahB4Adj);

                                    //Step 4 : สั่ง Summary SubFooter
                                    //Parameter 
                                    //$nGroupMember     = จำนวนข้อมูลทั้งหมดในกลุ่ม
                                    //$nRowPartID       = ลำดับข้อมูลในกลุ่ม
                                    //$aSumFooter       =  ข้อมูล Summary SubFooter
                                    FCNtHRPTSumSubFooter($nGroupMember,$nRowPartID,$aSumFooter);


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






