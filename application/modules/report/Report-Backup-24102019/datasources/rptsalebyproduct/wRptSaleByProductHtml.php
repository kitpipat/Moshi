<?php
    $aDataFilterReport  = $aDataViewRpt['aDataFilter'];
    $aDataTextRef       = $aDataViewRpt['aDataTextRef'];
    $aDataReport        = $aDataViewRpt['aDataReport'];
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

<div id="odvRptSaleByProductHtml">
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
                            <label class="xCNRptLabel"><?=$aDataTextRef['tRptAddrTel'] . $aCompanyInfo['FTCmpTel']?> <?=$aDataTextRef['tRptAddrFax'] . $aCompanyInfo['FTCmpFax']?></label>
                        </div>
                        <div class="text-left">
                            <label class="xCNRptLabel"><?=$aDataTextRef['tRptAddrBranch'] . $aCompanyInfo['FTBchName']?></label>
                        </div>
                    
                    <?php } ?>
                </div>
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 report-filter">
                    <?php if( (isset($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeFrom'])) && (isset($aDataFilter['tBchCodeTo']) && !empty($aDataFilter['tBchCodeTo']))) { ?>
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
                        
                    <?php if((isset($aDataFilter['tShpNameFrom']) && !empty($aDataFilter['tShpNameFrom'])) && (isset($aDataFilter['tShpNameTo']) && !empty($aDataFilter['tShpNameTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล ร้านค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptShopFrom'].' '.$aDataFilter['tShpNameFrom'];?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptShopTo'].' '.$aDataFilter['tShpNameTo'];?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                    
                    <?php if((isset($aDataFilter['tPdtNameFrom']) && !empty($aDataFilter['tPdtNameFrom'])) && (isset($aDataFilter['tPdtNameTo']) && !empty($aDataFilter['tPdtNameTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล สินค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tPdtCodeFrom'].' '.$aDataFilter['tPdtNameFrom'];?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tPdtCodeTo'].' '.$aDataFilter['tPdtNameTo'];?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                        
                    <?php if((isset($aDataFilter['tPdtGrpNameFrom']) && !empty($aDataFilter['tPdtGrpNameFrom'])) && (isset($aDataFilter['tPdtGrpNameTo']) && !empty($aDataFilter['tPdtGrpNameTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล กลุ่มสินค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tPdtGrpFrom'].' '.$aDataFilter['tPdtGrpNameFrom'];?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tPdtGrpTo'].' '.$aDataFilter['tPdtGrpNameTo'];?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                        
                    <?php if((isset($aDataFilter['tPdtTypeNameFrom']) && !empty($aDataFilter['tPdtTypeNameFrom'])) && (isset($aDataFilter['tPdtTypeNameTo']) && !empty($aDataFilter['tPdtTypeNameTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล ประเภทสินค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tPdtTypeFrom'].' '.$aDataFilter['tPdtTypeNameFrom'];?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tPdtTypeTo'].' '.$aDataFilter['tPdtTypeNameTo'];?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                        
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
                            <th nowrap class="text-left" style="width:10%"><?php  echo $aDataTextRef['tRptBillNo']; ?></th>
                            <th nowrap class="text-left" style="width:10%"><?php  echo $aDataTextRef['tRptDate']; ?></th>
                            <th nowrap class="text-left" style="width:10%"><?php  echo $aDataTextRef['tRptProduct']; ?></th>
                            <th nowrap class="text-right" style="width:15%"><?php  echo $aDataTextRef['tRptCabinetnumber']; ?></th>
                            <th nowrap class="text-right" style="width:25%"><?php  echo $aDataTextRef['tRptPrice']; ?></th>
                            <th nowrap class="text-right" style="width:5%"><?php   echo $aDataTextRef['tRptSales']; ?></th>
                            <th nowrap class="text-right" style="width:5%"><?php   echo $aDataTextRef['tRptDiscount']; ?></th>
                            <th nowrap class="text-right" style="width:10%"><?php  echo $aDataTextRef['tRptTax']; ?></th>
                            <th nowrap class="text-right" style="width:10%"><?php  echo $aDataTextRef['tRptGrand']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($aDataReport['aRptData']) && !empty($aDataReport['aRptData'])):?>
                            <?php
                                // Set ตัวแปร Sum - SubFooter
                                $nSumSubXsdQty          = 0;
                                $cSumSubXsdAmtB4DisChg  = 0;
                                $cSumSubXsdDis          = 0;
                                $cSumSubXsdVat          = 0;
                                $cSumSubXsdNetAfHD      = 0;
                                // Set ตัวแปร SumFooter
                                $nSumFootXsdQty         = 0;
                                $cSumFootXsdAmtB4DisChg = 0;
                                $cSumFootXsdDis         = 0;
                                $cSumFootXsdVat         = 0;
                                $cSumFootXsdNetAfHD     = 0;
                            ?> 
                            <?php foreach($aDataReport['aRptData'] as $nKey=>$aValue):?>
                                <?php
                                    // Step 1 เตรียม Parameter สำหรับการ Groupping
                                    $tRptDocNo      = $aValue["FTXshDocNo"];
                                    $tRptDocDate    = date('Y-m-d H:i:s', strtotime($aValue["FDCreateOn"]));
                                    $nGroupMember   = $aValue["FNRptGroupMember"]; 
                                    $nRowPartID     = $aValue["FNRowPartID"]; 
                                ?>
                                <?php
                                    //Step 2 Groupping data
                                    $aGrouppingData = array($tRptDocNo,$tRptDocDate);
                                    // Parameter
                                    // $nRowPartID      = ลำดับตามกลุ่ม
                                    // $aGrouppingData  = ข้อมูลสำหรับ Groupping
                                    FCNtHRPTHeadGroupping($nRowPartID,$aGrouppingData);
                                ?>
                                <!--  Step 2 แสดงข้อมูลใน TD  -->
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td><?php echo @$aValue["FTXsdPdtName"];?></td>
                                    <td class="text-right"><?php echo number_format($aValue["FCXsdQty"], 0);?></td>
                                    <td class="text-right"><?php echo number_format($aValue["FCXsdSetPrice"], $nOptDecimalShow);?></td>
                                    <td class="text-right"><?php echo number_format($aValue["FCXsdAmtB4DisChg"], $nOptDecimalShow);?></td>
                                    <td class="text-right"><?php echo number_format($aValue["FCXsdDis"], $nOptDecimalShow);?></td>
                                    <td class="text-right"><?php echo number_format($aValue["FCXsdVat"], $nOptDecimalShow);?></td>
                                    <td class="text-right"><?php echo number_format($aValue["FCXsdNetAfHD"], $nOptDecimalShow);?></td>
                                </tr>

                                <?php
                                    // Step 3 : เตรียม Parameter สำหรับ Summary Sub Footer
                                    $nSumSubXsdQty          = number_format($aValue["FCXsdQty_SubTotal"], 0);
                                    $cSumSubXsdAmtB4DisChg  = number_format($aValue["FCXsdAmtB4DisChg_SubTotal"], $nOptDecimalShow);
                                    $cSumSubXsdDis          = number_format($aValue["FCXsdDis_SubTotal"], $nOptDecimalShow);
                                    $cSumSubXsdVat          = number_format($aValue["FCXsdVat_SubTotal"], $nOptDecimalShow);
                                    $cSumSubXsdNetAfHD      = number_format($aValue["FCXsdNetAfHD_SubTotal"], $nOptDecimalShow);

                                    $aSumFooter             = array($aDataTextRef['tRptTotalSub'],'N','N',$nSumSubXsdQty,'N',$cSumSubXsdAmtB4DisChg,$cSumSubXsdDis,$cSumSubXsdVat,$cSumSubXsdNetAfHD);

                                    //Step 4 : สั่ง Summary SubFooter
                                    //Parameter 
                                    //$nGroupMember     = จำนวนข้อมูลทั้งหมดในกลุ่ม
                                    //$nRowPartID       = ลำดับข้อมูลในกลุ่ม
                                    //$aSumFooter       =  ข้อมูล Summary SubFooter
                                    FCNtHRPTSumSubFooter($nGroupMember,$nRowPartID,$aSumFooter);

                                    //Step 5 เตรียม Parameter สำหรับ SumFooter
                                    $nSumFootXsdQty             = number_format($aValue["FCXsdQty_Footer"], 0);
                                    $cSumFootXsdAmtB4DisChg     = number_format($aValue["FCXsdAmtB4DisChg_Footer"], $nOptDecimalShow);
                                    $cSumFootXsdDis             = number_format($aValue["FCXsdDis_Footer"], $nOptDecimalShow);
                                    $cSumSubXsdVat              = number_format($aValue["FCXsdVat_Footer"], $nOptDecimalShow);
                                    $cSumFootXsdNetAfHD         = number_format($aValue["FCXsdNetAfHD_Footer"], $nOptDecimalShow);
                                    $paFooterSumData    = array($aDataTextRef['tRptTotalFooter'],'N','N',$nSumFootXsdQty,'N',$cSumFootXsdAmtB4DisChg,$cSumFootXsdDis,$cSumSubXsdVat,$cSumFootXsdNetAfHD);
                                ?>
                            <?php endforeach;?>
                            <?php
                                //Step 6 : สั่ง Summary Footer
                                $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                                FCNtHRPTSumFooter($nPageNo,$nTotalPage,$paFooterSumData);
                            ?>
                        <?php else:?>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo @$aDataTextRef['tRptNoData'];?></td></tr>
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














