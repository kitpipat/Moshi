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
</style>
<div id="odvRptAdjustStockVendingHtml">
    <div class="container-fluid xCNLayOutRptHtml">
        <div class="xCNHeaderReport">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-center">
                        <label class="xCNRptTitle"><?php echo @$aDataTextRef['tTitleReport'];?></label>
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
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <div class="text-left">
                        <label class="xCNRptLabel"><?php echo @$aDataTextRef['tTitleCompName'];?></label>
                        <br>
                    </div>
                    <?php if($aDataBranchAddress['FTAddVersion'] == 1):?>
                        <?php
                            // Check Data Address Road
                            if(isset($aDataBranchAddress['FTAddV1Road']) && !empty($aDataBranchAddress['FTAddV1Road'])){
                                $tTextLabeltRoad = $aDataTextRef['tRptAddrRoad'].$aDataBranchAddress['FTAddV1Road'];
                            }else{
                                $tTextLabeltRoad = $aDataTextRef['tRptAddrRoad'].' -';
                            }
                            
                            // Check Data Address Soi
                            if(isset($aDataBranchAddress['FTAddV1Soi']) && !empty($aDataBranchAddress['FTAddV1Soi'])){
                                $tTextLabeltSoi  = $aDataTextRef['tRptAddrSoi'].$aDataBranchAddress['FTAddV1Soi'];
                            }else{
                                $tTextLabeltSoi  = $aDataTextRef['tRptAddrSoi'].' -';
                            }
                        ?>
                        <div class="text-left">
                            <label class="xCNRptLabel"><?php echo @$aDataBranchAddress['FTAddV1No'].' '.@$tTextLabeltRoad.' '.@$tTextLabeltSoi;?></label>
                        </div>
                        <div class="text-left">
                            <label class="xCNRptLabel">
                                <?php echo @$aDataBranchAddress['FTSudName'].' '.$aDataBranchAddress['FTDstName'].' '.$aDataBranchAddress['FTPvnName'].' '.$aDataBranchAddress['FTAddV1PostCode']?>
                            </label>
                        </div>
                    <?php elseif($aDataBranchAddress['FTAddVersion'] == 2):?>
                        <?php
                            // Cheack Address Ver 2 Des 1
                            if(isset($aDataBranchAddress['FTAddV2Desc1']) && !empty($aDataBranchAddress['FTAddV2Desc1'])){
                                $tTextLabelAddrV2Desc1  = $aDataTextRef['tRptAddV2Desc1'].' '.$aDataBranchAddress['FTAddV2Desc1'];
                            }else{
                                $tTextLabelAddrV2Desc1  = $aDataTextRef['tRptAddV2Desc1'].' -';
                            }

                            // Cheack Address Ver 2 Des 1
                            if(isset($aDataBranchAddress['FTAddV2Desc2']) && !empty($aDataBranchAddress['FTAddV2Desc2'])){
                                $tTextLabelAddrV2Desc2  = $aDataTextRef['tRptAddV2Desc2'].' '.$aDataBranchAddress['FTAddV2Desc2'];
                            }else{
                                $tTextLabelAddrV2Desc2  = $aDataTextRef['tRptAddV2Desc2'].' -';
                            }
                        ?>
                        <div class="text-left">
                            <label class="xCNRptLabel"><?php echo $tTextLabelAddrV2Desc1;?></label>
                        </div>
                        <div class="text-left">
                            <label class="xCNRptLabel"><?php echo $tTextLabelAddrV2Desc2;?></label>
                        </div>
                    <?php else: endif;?>
                    <div class="text-left">
                        <?php 
                            if($aDataCompany['rtCode'] == 1){
                                $tTextLabelTel  = $aDataCompany['raItems']['rtCmpTel'];
                            }else{
                                $tTextLabelTel  = '-';
                            }
                        ?>
                        <label class="xCNRptLabel"><?php echo @$aDataTextRef['tRptAddrTel'].' '.@$tTextLabelTel;?></label>
                    </div>
                    <div class="text-left">
                        <?php 
                            if($aDataCompany['rtCode'] == 1){
                                $tTextLabelFax  = $aDataCompany['raItems']['rtCmpFax'];
                            }else{
                                $tTextLabelFax  = '-';
                            }
                        ?>
                        <label class="xCNRptLabel"><?php echo @$aDataTextRef['tRptAddrFax'].' '.@$tTextLabelFax;?></label>
                    </div>
                    <div class="text-left">
                        <?php
                            if(isset($aDataBranchAddress['FTBchName']) && !empty($aDataBranchAddress['FTBchName'])){
                                $tTextLabelBranch   = $aDataBranchAddress['FTBchName'];
                            }else{
                                $tTextLabelBranch   = '-';
                            }
                        ?>
                        <label class="xCNRptLabel"><?php echo @$aDataTextRef['tRptAddrBranch'].' '.@$tTextLabelBranch;?></label>
                    </div>
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
                        <th nowrap class="text-center xCNTextBold" style="width:15%;  padding: 15px;"><?php echo  language('report/report/report','tRowNumber');?></th>
                        <th nowrap class="text-center xCNTextBold" style="width:15%; padding: 10px;"><?php echo  language('report/report/report','tRptTaxSaleLockerDocDate');?></th>
                        <th nowrap class="text-center xCNTextBold" style="width:15%; padding: 10px;"><?php echo  language('report/report/report','tRptXshDocNo');?></th>
                        <th nowrap class="text-center xCNTextBold" style="width:15%; padding: 10px;"><?php echo  language('report/report/report','tRptTaxSaleLockerDocRef');?></th>
                        <th nowrap class="text-center xCNTextBold" style="width:5%;  padding: 10px;"><?php  echo  language('report/report/report','tRptTaxSaleLockerCustomer');?></th>
                        <th nowrap class="text-center xCNTextBold" style="width:5%;  padding: 10px;"><?php  echo  language('report/report/report','tRptTaxNo');?></th>
                        <th nowrap class="text-center xCNTextBold" style="width:5%;  padding: 10px;"><?php  echo  language('report/report/report','tRptCstBussiness');?></th>
                        <th nowrap class="text-center xCNTextBold" style="width:5%;  padding: 10px;"><?php  echo  language('report/report/report','tRptTaxSaleLockerAmt');?></th>
                        <th nowrap class="text-center xCNTextBold" style="width:5%;  padding: 10px;"><?php  echo  language('report/report/report','tRptTaxSaleLockerAmtV');?></th>
                        <th nowrap class="text-center xCNTextBold" style="width:5%;  padding: 10px;"><?php  echo  language('report/report/report','tRptTaxSaleLockerAmtNV');?></th>
                        <th nowrap class="text-center xCNTextBold" style="width:5%;  padding: 10px;"><?php  echo  language('report/report/report','tRptTaxSaleLockerGrandTotal');?></th>
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
                                $row = 0;
                            ?>
                            <?php foreach ($aDataReport['aRptData'] as $nKey=>$aValue): $row++ ?>
                                <?php
                                    // Step 1 เตรียม Parameter สำหรับการ Groupping
                                    $tPosCode       = $aValue["FTPosCode"];  
                                  
                                    $nGroupMember   = $aValue["FNRptGroupMember"]; 
                                    $nRowPartID     = $aValue["FNRowPartID"]; 
                                ?>
                                <?php
                                    //Step 2 Groupping data
                                    $aGrouppingData = array($tPosCode);
                                    // Parameter
                                    // $nRowPartID      = ลำดับตามกลุ่ม
                                    // $aGrouppingData  = ข้อมูลสำหรับ Groupping
                                 $result = FCNtHRPTHeadSSGGroupping($nRowPartID,$aGrouppingData);
                                ?>
                                <!--  Step 2 แสดงข้อมูลใน TD  -->
                                <tr>
                         
                                    <td nowrap class="text-center"><?php echo $row;?></td>
                                    <td nowrap class="text-left" style="padding:7px;"><?php echo $aValue["FTXshDocDate"];?></td>
                                    <td nowrap class="text-left"><?php echo $aValue["FTXshDocNo"];?></td>
                                    <?php if($aValue["FTXshDocRef"] != ""){?>
                                        <td nowrap class="text-left"><?php echo $aValue["FTXshDocRef"];?></td>
                                    <?php }else{?>
                                         <td nowrap class="text-left">-</td>
                                    <?php } ?>
                                    <td nowrap class="text-left"><?php echo $aValue["FTCstName"];?></td>
                                    <td nowrap class="text-left"><?php echo $aValue["FTCstTaxNo"];?></td>
                                    <td nowrap class="text-left"><?php echo $aValue["FTCstBch"];?></td>
                                    <td nowrap class="text-right"><?php echo number_format($aValue["FCXshAmt"],2);?></td>
                                    <td nowrap class="text-right"><?php echo number_format($aValue["FCXshVat"],2);?></td>
                                    <td nowrap class="text-right"><?php echo number_format($aValue["FCXshAmtNV"],2);?></td>
                                    <td nowrap class="text-right"><?php echo number_format($aValue["FCXshGrandTotal"],2);?></td>
             
                        
                                </tr>
                                <?php
                                    //Step 3 : เตรียม Parameter สำหรับ Summary Sub Footer
                                    // $nSubSumAjdWahB4Adj = $aValue["FCSdtSubQty"];
                                    // $nSubSumAjdUnitQty  = $aValue["FCXrcNetFooter"];

                                    // $aSumFooter         = array('รวม'.$aValue["FTRcvName"],'N','N',$nSubSumAjdWahB4Adj);

                                    //Step 4 : สั่ง Summary SubFooter
                                    //Parameter 
                                    //$nGroupMember     = จำนวนข้อมูลทั้งหมดในกลุ่ม
                                    //$nRowPartID       = ลำดับข้อมูลในกลุ่ม
                                    //$aSumFooter       =  ข้อมูล Summary SubFooter
                                    // FCNtHRPTSumSubFooter($nGroupMember,$nRowPartID,$aSumFooter);


                                    //Step 5 เตรียม Parameter สำหรับ SumFooter
                                    $nSumFooterFCXshAmt          = number_format($aValue["FCXshAmt_Footer"],2);
                                    $nSumFooterFCXshVat          = number_format($aValue["FCXshVat_Footer"],2);
                                    $nSumFooterFCXshAmtNV        = number_format($aValue["FCXshAmtNV_Footer"],2);
                                    $nSumFooterFCXshGrandTotal   = number_format($aValue["FCXshGrandTotal_Footer"],2);

                                    $paFooterSumData        = array('รวมทั้งสิ้น','N','N','N','N','N','N',$nSumFooterFCXshAmt,$nSumFooterFCXshVat,$nSumFooterFCXshAmtNV,$nSumFooterFCXshGrandTotal );
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