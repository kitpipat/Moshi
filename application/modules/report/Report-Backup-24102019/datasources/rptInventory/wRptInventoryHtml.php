<?php
    $aDataCompany       = $aDataViewRpt['aDataCompany'];
    $aDataFilter        = $aDataViewRpt['aDataFilter'];
    $aDataTextRef       = $aDataViewRpt['aDataTextRef'];
    $aDataBranchAddress = $aDataTextRef['aDataBranchAddress'];
    $aDataReport        = $aDataViewRpt['aDataReport'];
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
    /* .table>tbody>tr.xCNHeaderGroup{
            border-bottom : 1px solid black !important;
            border-top : 1px solid black !important;
        } */
</style>
<div id="odvRptProductTransferHtml">
    <div class="container-fluid xCNLayOutRptHtml">
        <div class="xCNHeaderReport">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-center">
                        <b><label class="xCNRptTitle"><?php echo @$aDataTextRef['tTitleReport'];?></label></b>
                    </div>
                </div>
                <div class="col-md-6 col-md-offset-4">
                    <?php if($aDataFilter['tDocDateFrom'] != "" && $aDataFilter['tDocDateTo'] != ""){?>
                        <?php echo language('report/report/report','tRptDateFrom') ?> <?php echo $aDataFilter['tDocDateFrom'] ?> <?php echo language('report/report/report','tRptDateTo') ?> <?php echo $aDataFilter['tDocDateTo'] ?> 
                    <?php } ?>
                    <?php if($aDataFilter['tMerCodeFrom'] != "" && $aDataFilter['tMerCodeTo'] != ""){?>
                        <br><?php echo language('report/report/report','tRptMerFrom') ?> <?php echo $aDataFilter['tMerNameFrom'] ?> <?php echo language('report/report/report','tRptMerTo') ?> <?php echo $aDataFilter['tMerNameTo'] ?> 
                    <?php } ?>
                    <?php if($aDataFilter['tShpCodeFrom'] != "" && $aDataFilter['tShpCodeTo'] != ""){?>
                        <br><?php echo language('report/report/report','tRptShopFrom') ?> <?php echo $aDataFilter['tShpNameFrom'] ?> <?php echo language('report/report/report','tRptShopTo') ?> <?php echo $aDataFilter['tShpNameTo'] ?> 
                    <?php } ?>
                    <?php if($aDataFilter['tPosCodeFrom'] != "" && $aDataFilter['tPosCodeTo'] != ""){?>
                        <br><?php echo language('report/report/report','tRptPosFrom') ?> <?php echo $aDataFilter['tPosNameFrom'] ?> <?php echo language('report/report/report','tRptPosTo') ?> <?php echo $aDataFilter['tPosNameTo'] ?> 
                    <?php } ?>
                    <?php if($aDataFilter['tWahCodeFrom'] != "" && $aDataFilter['tWahCodeTo'] != ""){?>
                        <br><?php echo language('report/report/report','tRptFromWareHouse') ?> <?php echo $aDataFilter['tWahNameFrom'] ?> <?php echo language('report/report/report','tRptToWareHouse') ?> <?php echo $aDataFilter['tWahNameTo'] ?> 
                    <?php } ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <?php if(isset($aDataBranchAddress) && !empty($aDataBranchAddress)) { ?>
                    <div class="text-left">
                        <label class="xCNRptLabel"><?=$aDataBranchAddress['FTCompName']?></label>
                    </div>

                    <?php if($aDataBranchAddress['FTAddVersion'] == '1'){ // ที่อยู่แบบแยก ?>
                        <div class="text-left">
                            <label class="xCNRptLabel"><?=$aDataBranchAddress['FTAddV1No'] . ' ' . $aDataBranchAddress['FTAddV1Village'] . ' ' . $aDataBranchAddress['FTAddV1Road'] . ' ' . $aDataBranchAddress['FTAddV1Soi']?></label>
                        </div>
                        <div class="text-left">
                            <label class="xCNRptLabel"><?=$aDataBranchAddress['FTSudName'] . ' ' . $aDataBranchAddress['FTDstName'] . ' ' . $aDataBranchAddress['FTPvnName'] . ' ' . $aDataBranchAddress['FTAddV1PostCode']?></label>
                        </div>
                    <?php } ?>

                    <?php if($aDataBranchAddress['FTAddVersion'] == '2'){ // ที่อยู่แบบรวม ?>
                        <div class="text-left">
                            <label class="xCNRptLabel"><?=$aDataBranchAddress['FTAddV2Desc1']?></label>
                        </div>
                        <div class="text-left">
                            <label class="xCNRptLabel"><?=$aDataBranchAddress['FTAddV2Desc2']?></label>
                        </div>
                    <?php } ?>

                    <div class="text-left">
                        <label class="xCNRptLabel"><?=$aDataTextRef['tRptAddrTel'] .$aDataCompany['raItems']['rtCmpTel']?> <?=$aDataTextRef['tRptAddrFax'] . $aDataCompany['raItems']['rtCmpFax']?></label>
                    </div>
                    <div class="text-left">
                        <label class="xCNRptLabel"><?=$aDataTextRef['tRptAddrBranch'] . $aDataCompany['raItems']['rtCmpBchName']?></label>
                    </div>
                <?php } ?>   
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <label class="xCNRptLabel"><?php echo @$aDataTextRef['tDatePrint'].' '.date('d/m/Y').' '.@$aDataTextRef['tTimePrint'].' '.date('H:i:s');?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="xCNContentReport">
            <div id="odvRptTableAdvance" class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th nowrap class="text-left xCNTextBold" style="width:10%; padding: 15px;"><?php echo  language('report/report/report','tRptCabinetCode');?></th>
                            <th nowrap class="text-left xCNTextBold" style="width:10%; padding: 10px;"><?php echo  language('report/report/report','tRptAdjStkVDPdtCode');?></th>
                            <th nowrap class="text-left xCNTextBold" style="width:10%; padding: 10px;"><?php echo  language('report/report/report','tRptAdjStkVDPdtName');?></th>
                            <th nowrap class="text-right xCNTextBold" style="width:5%;  padding: 5px;"><?php echo  language('report/report/report','tRptCabinetclass');?></th>
                            <th nowrap class="text-right xCNTextBold" style="width:5%;  padding: 5px;"><?php echo  language('report/report/report','tRptCabinetCollum');?></th>
                            <th nowrap class="text-right xCNTextBold" style="width:5%;  padding: 5px;"><?php echo  language('report/report/report','tRptCabinetnumber');?></th>
                            <th nowrap class="text-right xCNTextBold" style="width:5%;  padding: 10px;"><?php  echo  language('report/report/report','tRptCabinetCost');?></th>
                            <th nowrap class="text-right xCNTextBold" style="width:5%;  padding: 10px;"><?php  echo  language('report/report/report','tRptCabinetTotalcost');?></th>
                           
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
                                
                            ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey=>$aValue): ?>
                                <?php
                                    // Step 1 เตรียม Parameter สำหรับการ Groupping
                                    $tPosCode     = $aValue["FTPosCode"];  
              

                                    $nGroupMember   = $aValue["FNRptGroupMember"]; 
                                    $nRowPartID     = $aValue["FNRowPartID"]; 
                                ?>
                                <?php
                                    //Step 2 Groupping data
                                    $aGrouppingData = array($tPosCode);
                                    // Parameter
                                    // $nRowPartID      = ลำดับตามกลุ่ม
                                    // $aGrouppingData  = ข้อมูลสำหรับ Groupping
                                    FCNtHRPTHeadGroupping($nRowPartID,$aGrouppingData);
                                ?>
                                <!--  Step 2 แสดงข้อมูลใน TD  -->
                                <tr>
                                    <td></td>
          
                                    <td nowrap class="text-left"><?php echo $aValue["FTPdtCode"];?></td>
                                    <td nowrap class="text-left"><?php echo $aValue["FTPdtName"];?></td>
                                    <td nowrap class="text-right"><?php echo $aValue["FNLayRow"];?></td>
                                    <td nowrap class="text-right"><?php echo $aValue["FNLayCol"];?></td>
                                    <td nowrap class="text-right"><?php echo $aValue["FCStkQty"];?></td>
                                    <td nowrap class="text-right"><?php echo number_format($aValue["FCPdtCostEx"],2)?></td>
                                    <td nowrap class="text-right"><?php echo number_format($aValue["CostExQty"],2)?></td>
                                </tr>

                                <?php
                                    //Step 3 : เตรียม Parameter สำหรับ Summary Sub Footer
                                    $nSubSumAjdWahB4Adj = number_format($aValue["FCPdtCostEx_SubTotal"],2);
                                    $nSubSumAjdUnitQty  = number_format($aValue["FCStkQty_SubTotal"],2);
                                    $nSubSumCostExQty   = number_format($aValue["CostExQty_SubTotal"],2);

                                    $aSumFooter         = array($aDataTextRef['tRptTotal'],'N','N','N','N',$nSubSumAjdUnitQty,$nSubSumAjdWahB4Adj,$nSubSumCostExQty);
                                    
                                    //Step 4 : สั่ง Summary SubFooter
                                    //Parameter 
                                    //$nGroupMember     = จำนวนข้อมูลทั้งหมดในกลุ่ม
                                    //$nRowPartID       = ลำดับข้อมูลในกลุ่ม
                                    //$aSumFooter       =  ข้อมูล Summary SubFooter
                                    FCNtHRPTSumSubFooter($nGroupMember,$nRowPartID,$aSumFooter);


                                    // Step 5 เตรียม Parameter สำหรับ SumFooter
                                    $nSumFooterAjdWahB4Adj  = number_format($aValue["FCPdtCostEx_Footer"],2);
                                    $nSumFooterAjdUnitQty   = number_format($aValue["FCStkQty_Footer"],2);
                                    $nSumCostExQtyQty       = number_format($aValue["CostExQty_Footer"],2);
                                    
                                    $paFooterSumData        = array($aDataTextRef['tRptFooterSumAll'],'N','N','N','N',$nSumFooterAjdUnitQty,$nSumFooterAjdWahB4Adj,$nSumCostExQtyQty);
                                ?>
                            <?php endforeach;?>
                            <?php
                                //Step 6 : สั่ง Summary Footer
                                $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                                FCNtHRPTSumFooter($nPageNo,$nTotalPage,$paFooterSumData);
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



