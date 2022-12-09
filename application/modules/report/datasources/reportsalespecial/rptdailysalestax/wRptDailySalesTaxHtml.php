<?php
    $aDataReport    = $aDataViewRpt['aDataReport'];
    $aDataTextRef   = $aDataViewRpt['aDataTextRef'];
    $aDataFilter    = $aDataViewRpt['aDataFilter'];
    $nPageNo        = $aDataReport["aPagination"]["nDisplayPage"];
    
    $nTotalPage     = $aDataReport["aPagination"]["nTotalPage"];
?>
<style>
    /** Set Media Print */
    @media print{@page {size: A4 landscape;}}

    .table thead th, .table>thead>tr>th, .table tbody tr, .table>tbody>tr>td {
        border: 0px transparent !important;
    }

    .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th {
        border-top: 1px solid black !important;
        border-bottom: 1px solid black !important;
    }
    
    .table tbody tr.xCNRptSumFooterTr,
    .table>tbody>tr.xCNRptSumFooterTr>td {
        border: 0px solid black !important;
        border-top: 1px solid black !important;
        border-bottom: 1px solid black !important;
    }
    .table tbody tr.xCNRptSubSumFooterTr,
    .table>tbody>tr.xCNRptSubSumFooterTr>td {
        border: 0px dashed black !important;
        border-top: 1px dashed black !important;
        border-bottom: 1px dashed black !important;
    }
</style>
<div id="odvRptSaleTaxByMonthlyHtml">
    <div class="container-fluid xCNLayOutRptHtml">
        <div class="xCNHeaderReport">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <?php if (isset($aCompanyInfo) && !empty($aCompanyInfo)):?>
                        <div class="text-left">
                            <label class="xCNRptCompany"><?php echo $aCompanyInfo['FTCmpName'];?></label>
                        </div>
                        <?php if ($aCompanyInfo['FTAddVersion'] == '1') { // ที่อยู่แบบแยก ?>
                            <div class="text-left xCNRptAddress">
                                <label ><?php echo @$aCompanyInfo['FTAddV1No'] . ' ' . @$aCompanyInfo['FTAddV1Road'] . ' ' . @$aCompanyInfo['FTAddV1Soi']?>
                                <?php echo @$aCompanyInfo['FTSudName'] . ' ' . @$aCompanyInfo['FTDstName'] . ' ' . @$aCompanyInfo['FTPvnName'] . ' ' . @$aCompanyInfo['FTAddV1PostCode']?></label>
                            </div>
                        <?php } ?>
                        <?php if ($aCompanyInfo['FTAddVersion'] == '2') { // ที่อยู่แบบรวม ?>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?php echo @$aCompanyInfo['FTAddV2Desc1'] ?></label>
                            </div>
                            <div class="text-left">
                                <label class="xCNRptLabel"><?php echo @$aCompanyInfo['FTAddV2Desc2'] ?></label>
                            </div>
                        <?php } ?>
                        <div class="text-left xCNRptAddress">
                            <label><?php echo @$aDataTextRef['tRptAddrTel'] . @$aCompanyInfo['FTCmpTel']?> <?php echo @$aDataTextRef['tRptAddrFax'] . @$aCompanyInfo['FTCmpFax']?></label>
                        </div>
                        <div class="text-left xCNRptAddress">
                            <label><?php echo @$aDataTextRef['tRptAddrBranch'] . @$aCompanyInfo['FTBchName'];?></label>
                        </div>
                        <div class="text-left xCNRptAddress">
                            <label><?php echo @$aDataTextRef['tRptAddrTaxNo'] . @$aCompanyInfo['FTAddTaxNo']?></label>
                        </div> 
                    <?php endif;?>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="text-center">
                            <label class="xCNRptTitle"><?php echo $aDataTextRef['tTitleReport']; ?></label>
                            </div>
                        </div>
                    </div>
                    
                   <!-- ============================ ฟิวเตอร์ข้อมูล ปี เดือน ============================ -->
                   <?php //if ((isset($aDataFilter['tYear']) && !empty($aDataFilter['tYear'])) && isset($aDataFilter['tMonth']) && !empty($aDataFilter['tMonth'])) : ?>
                        <!-- <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center xCNRptFilter">
                                    <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptMonth']; ?> </span> <?php echo language('report/report/report', 'tRptMonth'.$aDataFilter['tMonth'])?></label>
                                    <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptYear']; ?> </span> <?php echo  $aDataFilter['tYear']; ?></label>
                                </div>
                            </div>
                        </div> -->
                    <?php //endif; ?>
                        
                    <?php if ((isset($aDataFilter['tBchCodeFrom']) && !empty($aDataFilter['tBchCodeFrom'])) && (isset($aDataFilter['tBchCodeTo']) && !empty($aDataFilter['tBchCodeTo']))) { ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center">
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom'] ?> : </label>   <label><?=$aDataFilter['tBchNameFrom']; ?></label>
                                    <label class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchTo'] ?> : </label>   <label><?=$aDataFilter['tBchNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php }; ?>

                    <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่สร้างเอกสาร ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center xCNRptFilter">
                                    <label><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptDate']?> : </span>   <label><?= date("d/m/Y", strtotime($aDataFilter['tDocDateFrom'])); ?></label>&nbsp;
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 table-responsive">
                    <div class="text-right">
                        <label class="xCNRptDataPrint"><?php echo @$aDataTextRef['tDatePrint'].' '.date('d/m/Y').' '.@$aDataTextRef['tTimePrint'].' '.date('H:i:s');?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="xCNContentReport">
            <div id="odvRptTableAdvance" class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th  class="text-left xCNRptColumnHeader" width="6%"><?php echo @$aDataTextRef['tRptSaleTaxByDailyPos'];?></th>
                            <th  class="text-left xCNRptColumnHeader" width="8%"><?php echo @$aDataTextRef['tRptSaleTaxByDailyPayment'];?></th>
                            <th  class="text-center xCNRptColumnHeader"  width="8%"><?php echo @$aDataTextRef['tRptSaleTaxByDailyDate'];?></th>
                            <th  class="text-left xCNRptColumnHeader"  width="5%"><?php echo @$aDataTextRef['tRptSaleTaxByDailyEjNo'];?></th>
                            <th  class="text-left xCNRptColumnHeader"  width="25%" ><?php echo @$aDataTextRef['tRptSaleTaxByDailyProduct'];?></th>
                            <th  class="text-left xCNRptColumnHeader"  width="10%"><?php echo @$aDataTextRef['tRptSaleTaxByDailyFirstName'];?></th>
                            <th  class="text-left xCNRptColumnHeader"  width="7%"><?php echo @$aDataTextRef['tRptSaleTaxByDailyLastName'];?></th>
                            <th  class="text-left xCNRptColumnHeader"  width="15%"><?php echo @$aDataTextRef['tRptSaleTaxByDailyIDCardNo'];?></th>
                            <th  class="text-right xCNRptColumnHeader"  width="8%"><?php echo @$aDataTextRef['tRptSaleTaxByDailyVatTable'];?></th>
                            <th  class="text-right xCNRptColumnHeader"  width="8%"><?php echo @$aDataTextRef['tRptSaleTaxByDailyVatAmount'];?></th>
                        </tr>
                    </thead>
                    <tbody> 
                    <?php
                     if(!empty($aDataReport['aRptData'])){
                         $nTotalarray = count($aDataReport['aRptData']);
                         foreach($aDataReport['aRptData'] as $k => $aValue){
                            $cXrcNet_Footer     = $aValue['FCXsdVatable_Footer'];
                            $cXrcVatable_Footer = $aValue['FCXsdVat_Footer'];
                          
                            ?>
                                <?php 
                                
                                if(@$tDate != date('Y-m-d',strtotime($aValue['FDXshDocDate'])).$aValue['FTFmtCode'] && $k>0){ 
                                     
                               ?> 


                            <tr class="xCNRptSubSumFooterTr"> 
                            
                                <td class="text-left xCNRptDetail"></td>
                                <td  class="text-left xCNRptSumFooter"   colspan="2" ><?php echo $aDataTextRef['tRptSaleTaxByMonthlyTotal']. @$cXrcSumTypePayBy; ?></td>
                                <td class="text-left xCNRptDetail"></td>
                                <td class="text-left xCNRptDetail"></td>
                                <td class="text-left xCNRptDetail"></td>
                                <td class="text-left xCNRptDetail"></td>
                                <td class="text-left xCNRptDetail"></td>
                                
                                <td  class="text-right xCNRptSumFooter"><?php echo number_format($cXsdVatable_SubTotal,2);?></td>
                                <td  class="text-right xCNRptSumFooter"><?php echo number_format($cXsdVat_SubTotal,2);?></td>
                               
                                     </tr>  
                                  
                               <?php }  ?>
                            <tr>
                                <td class="text-left xCNRptDetail"><?php echo $aValue['FTPosCode'].@$tTypeCode;?></td>
                                <td class="text-left xCNRptDetail"><?php echo $aValue['FTFmtName'];?></td>
                                
                                <td class="text-center xCNRptDetail "><?php echo date('d/m/Y',strtotime($aValue['FDXshDocDate'])); ?></td>
                                <td class="text-left xCNRptDetail"><?php echo $aValue['FTXshDocNo'];?></td>
                                <td class="text-left xCNRptDetail" ><?php echo $aValue['FTPdtName'];?></td>
                                <td class="text-left xCNRptDetail"><?php echo $aValue['FTCstName'];?></td>
                                <td class="text-left xCNRptDetail"><?php echo $aValue['FTCstLastName'];?></td>
                                <td class="text-left xCNRptDetail"><?php echo $aValue['FTCstCode'];?></td>
                                <td class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXsdVatable"], $nOptDecimalShow);?></td>
                                <td class="text-right xCNRptDetail"><?php echo number_format($aValue["FCXsdVat"], $nOptDecimalShow);?></td>
                            </tr>
                            <?php 
                                      $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                                      $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];
                                      if ($nPageNo == $nTotalPage) {

                                if($nTotalarray==($k+1)){ 
                                     
                               ?> 

                             
                               <tr class="xCNRptSubSumFooterTr" >
                            
                               <td class="text-right xCNRptDetail"></td>
                               
                                <td class="text-left xCNRptSumFooter"   colspan="2" ><?php echo $aDataTextRef['tRptSaleTaxByMonthlyTotal']. $aValue['FTFmtName']; ?></td>
                                <td class="text-center xCNRptDetail"></td>
                                <td class="text-left xCNRptDetail"></td>
                                <td class="text-left xCNRptDetail"></td>
                                <td class="text-left xCNRptDetail"></td> 
                                <td class="text-right xCNRptDetail"></td>
                                
                                <td class="text-right xCNRptSumFooter"><?php echo number_format($aValue['FCXsdVatable_SubTotal'],2);?></td>
                                <td class="text-right xCNRptSumFooter" ><?php echo number_format($aValue['FCXsdVat_SubTotal'],2);?></td>
                               
                                     </tr >
                               <?php }  
                                      }
                              $tDate =  date('Y-m-d',strtotime($aValue['FDXshDocDate'])).$aValue['FTFmtCode'] ;
                              $cXsdVatable_SubTotal =  $aValue['FCXsdVatable_SubTotal'];
                              $cXsdVat_SubTotal =  $aValue['FCXsdVat_SubTotal'];
                              $cXrcSumTypePayBy = $aValue['FTFmtName'];
                            //   $tTypeCode =  $aValue['FTFmtCode'] ;      
                                
                                ?> 
                         
                        <?php 
                        } ?>
                            

                        <?php 
                            $nPageNo    = $aDataReport["aPagination"]["nDisplayPage"];
                            $nTotalPage = $aDataReport["aPagination"]["nTotalPage"];

                            if ($nPageNo == $nTotalPage) { ?>
                                <tr class="xCNRptSumFooterTr">
                                    <td colspan="8" class="text-left xCNRptSumFooter"><?php echo $aDataTextRef['tRptTotalFooter']; ?></td>
                                    
                                    <td class="text-right xCNRptSumFooter"><?php echo number_format($cXrcNet_Footer,2); ?></td>
                                    <td class="text-right xCNRptSumFooter"><?php echo number_format($cXrcVatable_Footer,2); ?></td>
                                 
                                   
                                </tr>
                            <?php }
                        ?>

                        <?php }else{ ?>
                            <tr>
                                <td  colspan="17"  class="text-center xCNRptColumnFooter" ><?php echo $aDataTextRef['tRptNoData']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>    
                </table>
            </div>
            
            <div class="xCNRptFilterTitle">
                <div class="text-left">
                    <label class="xCNTextConsOth"><?=$aDataTextRef['tRptConditionInReport'];?></label>
                </div>
            </div>
                     
            <!-- ============================ ฟิวเตอร์ข้อมูล สาขา ============================ -->
            <?php if (isset($aDataFilter['tBchCodeSelect']) && !empty($aDataFilter['tBchCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptBchFrom']; ?> : </span> <?php echo ($aDataFilter['bBchStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tBchNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>
                
            <!-- ============================ ฟิวเตอร์ข้อมูล กลุ่มธุรกิจ ============================ -->
            <?php if ((isset($aDataFilter['tMerCodeFrom']) && !empty($aDataFilter['tMerCodeFrom'])) && (isset($aDataFilter['tMerCodeTo']) && !empty($aDataFilter['tMerCodeTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjMerChantFrom'].' : </span>'.$aDataFilter['tMerNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjMerChantTo'].' : </span>'.$aDataFilter['tMerNameTo'];?></label>
                    </div>
                </div>
            <?php endif; ?>  
            <?php if (isset($aDataFilter['tMerCodeSelect']) && !empty($aDataFilter['tMerCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptMerFrom']; ?> : </span> <?php echo ($aDataFilter['bMerStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tMerNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>  

            <!-- ============================ ฟิวเตอร์ข้อมูล ร้านค้า ============================ -->
            <?php if ((isset($aDataFilter['tShpCodeFrom']) && !empty($aDataFilter['tShpCodeFrom'])) && (isset($aDataFilter['tShpCodeTo']) && !empty($aDataFilter['tShpCodeTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjShopFrom'].' : </span>'.$aDataFilter['tShpNameFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjShopTo'].' : </span>'.$aDataFilter['tShpNameTo'];?></label>
                    </div>
                </div>
            <?php endif; ?>    
            <?php if (isset($aDataFilter['tShpCodeSelect']) && !empty($aDataFilter['tShpCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptShopFrom']; ?> : </span> <?php echo ($aDataFilter['bShpStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tShpNameSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?>  

            <!-- ============================ ฟิวเตอร์ข้อมูล จุดขาย ============================ -->
            <?php if ((isset($aDataFilter['tPosCodeFrom']) && !empty($aDataFilter['tPosCodeFrom'])) && (isset($aDataFilter['tPosCodeTo']) && !empty($aDataFilter['tPosCodeTo']))) : ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjPosFrom'].' : </span>'.$aDataFilter['tPosCodeFrom'];?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptAdjPosTo'].' : </span>'.$aDataFilter['tPosCodeTo'];?></label>
                    </div>
                </div>
            <?php endif; ?> 
            <?php if (isset($aDataFilter['tPosCodeSelect']) && !empty($aDataFilter['tPosCodeSelect'])) : ?>
                <div class="xCNRptFilterBox">
                    <div class="xCNRptFilter">
                        <label class="xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptPosFrom']; ?> : </span> <?php echo ($aDataFilter['bPosStaSelectAll']) ? $aDataTextRef['tRptAll'] : $aDataFilter['tPosCodeSelect']; ?></label>
                    </div>
                </div>
            <?php endif; ?> 

            <!-- ============================ ฟิวเตอร์ข้อมูล ประเภทการชำระ ============================ -->
            <?php if ((isset($aDataFilter['tRcvCodeFrom']) && !empty($aDataFilter['tRcvCodeFrom'])) && (isset($aDataFilter['tRcvCodeTo']) && !empty($aDataFilter['tRcvCodeTo']))): ?>
                <div class="xCNRptFilterBox">
                    <div class="text-left xCNRptFilter">
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptRcvFrom'].' : </span>'.$aDataFilter['tRcvNameFrom']; ?></label>
                        <label class="xCNRptLabel xCNRptDisplayBlock"><span class="xCNRptFilterHead"><?php echo $aDataTextRef['tRptRcvTo'].' : </span>'. $aDataFilter['tRcvNameTo']; ?></label>
                    </div>
                </div>
            <?php endif; ?>
        <div class="xCNFooterPageRpt">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                        <?php if ($aDataReport["aPagination"]["nTotalPage"] > 0): ?>
                            <label class="xCNRptLabel"><?php echo $aDataReport["aPagination"]["nDisplayPage"] . ' / ' . $aDataReport["aPagination"]["nTotalPage"]; ?></label>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>