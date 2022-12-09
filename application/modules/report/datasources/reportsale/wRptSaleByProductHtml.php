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
<div id="odvRptAdjustStockVendingHtml">
    <div class="container-fluid xCNLayOutRptHtml">
        <div class="xCNHeaderReport">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-center">
                        <label class="xCNRptTitle"><?php echo @$aDataTextRef['tTitleReport'];?></label>
                        <?php if($aDataFilter['tDocDateFrom'] != "" && $aDataFilter['tDocDateTo'] != ""){?>
                            <br><?php echo language('report/report/report','tRptDateFrom') ?> <?php echo $aDataFilter['tDocDateFrom'] ?> <?php echo language('report/report/report','tRptDateTo') ?> <?php echo $aDataFilter['tDocDateTo'] ?> 
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                    <div class="text-left">
                        <label class="xCNRptLabel"><?php echo @$aDataTextRef['tTitleCompName'];?></label>
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
                            <th nowrap class="text-center xCNTextBold" style="width:10%; padding: 15px;"><?php echo  language('report/report/report','tRptBillNo');?></th>
                            <th nowrap class="text-center xCNTextBold" style="width:15%; padding: 10px;"><?php echo  language('report/report/report','tRptDate');?></th>
                            <th nowrap class="text-center xCNTextBold" style="width:10%; padding: 10px;"><?php echo  language('report/report/report','tRptProduct');?></th>
                            <th nowrap class="text-center xCNTextBold" style="width:10%; padding: 10px;"><?php echo  language('report/report/report','tRptCabinetnumber');?></th>
                            <th nowrap class="text-center xCNTextBold" style="width:10%; padding: 10px;"><?php echo  language('report/report/report','tRptPrice');?></th>
                            <th nowrap class="text-center xCNTextBold" style="width:5%;  padding: 10px;"><?php echo  language('report/report/report','tSales');?></th>
                            <th nowrap class="text-center xCNTextBold" style="width:10%; padding: 10px;"><?php echo  language('report/report/report','tDiscount');?></th>
                            <th nowrap class="text-center xCNTextBold" style="width:5%;  padding: 10px;"><?php  echo  language('report/report/report','tRptTax');?></th>
                            <th nowrap class="text-center xCNTextBold" style="width:5%;  padding: 10px;"><?php  echo  language('report/report/report','tRptGrand');?></th>
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
                                    $tDocNo     = $aValue["FTXshDocNo"];  
                                    $dDocDate   = $aValue["FDCreateOn"];  
                    
    

                                    $nGroupMember   = $aValue["FNRptGroupMember"]; 
                                    $nRowPartID     = $aValue["FNRowPartID"]; 
                                ?>
                                <?php
                                    //Step 2 Groupping data
                                    $aGrouppingData = array($tDocNo,$dDocDate);
                                    // Parameter
                                    // $nRowPartID      = ลำดับตามกลุ่ม
                                    // $aGrouppingData  = ข้อมูลสำหรับ Groupping
                                    FCNtHRPTHeadGroupping($nRowPartID,$aGrouppingData,'N','N','N','N','N','N');
                                ?>
                                <!--  Step 2 แสดงข้อมูลใน TD  -->
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td nowrap class="text-left"><?php echo $aValue["FTXsdPdtName"];?></td>
                                    <td nowrap class="text-right"><?php echo $aValue["FCXsdQty"];?></td>
                                    <td nowrap class="text-right"><?php echo $aValue["FCXsdSetPrice"];?></td>
                                    <td nowrap class="text-right"><?php echo $aValue["FCXsdAmtB4DisChg"];?></td>
                                    <td nowrap class="text-right"><?php echo $aValue["FCXsdDis"];?></td>
                                    <td nowrap class="text-right"><?php echo $aValue["FCXsdVat"];?></td>
                                    <td nowrap class="text-right"><?php echo $aValue["FCXsdNetAfHD"];?></td>
                                </tr>

                                <?php
                                    //Step 3 : เตรียม Parameter สำหรับ Summary Sub Footer
                                    $nSubSumFCXsdQty          = $aValue["FCXsdQty_SubTotal"];
                                    $nSubSumFCXsdAmtB4DisChg  = $aValue["FCXsdAmtB4DisChg_SubTotal"];
                                    $nSubSumFCXsdDis          = $aValue["FCXsdDis_SubTotal"];
                                    $nSubSumFCXsdVat          = $aValue["FCXsdVat_SubTotal"];
                                    $nSubSumFCXsdNetAfHD      = $aValue["FCXsdNetAfHD_SubTotal"];

                                    $aSumFooter         = array($aDataTextRef['tRptTotalSub'],'N','N',$nSubSumFCXsdQty,'N',$nSubSumFCXsdAmtB4DisChg,$nSubSumFCXsdDis,$nSubSumFCXsdVat,$nSubSumFCXsdNetAfHD);

                                    //Step 4 : สั่ง Summary SubFooter
                                    //Parameter 
                                    //$nGroupMember     = จำนวนข้อมูลทั้งหมดในกลุ่ม
                                    //$nRowPartID       = ลำดับข้อมูลในกลุ่ม
                                    //$aSumFooter       =  ข้อมูล Summary SubFooter
                                    FCNtHRPTSumSubFooter($nGroupMember,$nRowPartID,$aSumFooter);

            

                                    //Step 5 เตรียม Parameter สำหรับ SumFooter

                                    $nSumFooterFCXsdQty           = number_format($aValue["FCXsdQty_Footer"],2);
                                    $nSumFooterFCXsdAmtB4DisChg   = number_format($aValue["FCXsdAmtB4DisChg_Footer"],2);
                                    $nSumFooterFCXsdDis           = number_format($aValue["FCXsdDis_Footer"],2);
                                    $nSumFooterFCXsdVat           = number_format($aValue["FCXsdVat_Footer"],2);
                                    $nSumFooterFFCXsdNetAfHD      = number_format($aValue["FCXsdNetAfHD_Footer"],2);

                                    $paFooterSumData        = array($aDataTextRef['tRptTotalFooter'],'N','N',$nSumFooterFCXsdQty,'N',$nSumFooterFCXsdAmtB4DisChg,$nSumFooterFCXsdDis,$nSumFooterFCXsdVat,$nSumFooterFFCXsdNetAfHD);
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