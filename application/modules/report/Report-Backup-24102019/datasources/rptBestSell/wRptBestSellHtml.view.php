<?php
    use \koolreport\widgets\koolphp\Table;
    $nCurrentPage = $this->params['nCurrentPage'];
    $nAllPage = $this->params['nAllPage'];
    $aDataTextRef = $this->params['aDataTextRef'];
    $aDataReport = $this->params['aDataReturn'];
    $aCompanyInfo = $this->params['aCompanyInfo'];
    $aDataFilter = $this->params['aDataFilter'];  
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
  
    .cssItem{
        text-align:left;
        white-space:nowrap; 
        font-weight: bold;
        background-color:#CFE2F3;
    }
    /*แนวนอน*/
    @media print{@page {size: landscape}} 
    /*แนวตั้ง*/
    /* @media print{@page {size: portrait}} */
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

                    <?php if ((isset($aDataFilter['tShpNameFrom']) && !empty($aDataFilter['tShpNameFrom'])) && (isset($aDataFilter['tShpNameTo']) && !empty($aDataFilter['tShpNameTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล ร้านค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptShopFrom'] . ' ' . $aDataFilter['tShpNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptShopTo'] . ' ' . $aDataFilter['tShpNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ((isset($aDataFilter['tPdtNameFrom']) && !empty($aDataFilter['tPdtNameFrom'])) && (isset($aDataFilter['tPdtNameTo']) && !empty($aDataFilter['tPdtNameTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล สินค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tPdtCodeFrom'] . ' ' . $aDataFilter['tPdtNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tPdtCodeTo'] . ' ' . $aDataFilter['tPdtNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ((isset($aDataFilter['tPdtGrpNameFrom']) && !empty($aDataFilter['tPdtGrpNameFrom'])) && (isset($aDataFilter['tPdtGrpNameTo']) && !empty($aDataFilter['tPdtGrpNameTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล กลุ่มสินค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tPdtGrpFrom'] . ' ' . $aDataFilter['tPdtGrpNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tPdtGrpTo'] . ' ' . $aDataFilter['tPdtGrpNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ((isset($aDataFilter['tPdtTypeNameFrom']) && !empty($aDataFilter['tPdtTypeNameFrom'])) && (isset($aDataFilter['tPdtTypeNameTo']) && !empty($aDataFilter['tPdtTypeNameTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล ประเภทสินค้า ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tPdtTypeFrom'] . ' ' . $aDataFilter['tPdtTypeNameFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tPdtTypeTo'] . ' ' . $aDataFilter['tPdtTypeNameTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ((isset($aDataFilter['tDocDateFrom']) && !empty($aDataFilter['tDocDateFrom'])) && (isset($aDataFilter['tDocDateTo']) && !empty($aDataFilter['tDocDateTo']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล วันที่สร้างเอกสาร ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptDateFrom'] . ' ' . $aDataFilter['tDocDateFrom']; ?></label>
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tRptDateTo'] . ' ' . $aDataFilter['tDocDateTo']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ((isset($aDataFilter['tTopPdt']) && !empty($aDataFilter['tTopPdt']))): ?>
                        <!-- ============================ ฟิวเตอร์ข้อมูล Top ============================ -->
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-left">
                                    <label class="xCNRptLabel"><?php echo $aDataTextRef['tPriority'] . ' ' . $aDataFilter['tTopPdt']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>    
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
            <!-- <?php print_r($aDataReport['rtCode']); ?> -->
            <?php if(isset($aDataReport['rtCode']) &&  !empty($aDataReport['rtCode']) && $aDataReport['rtCode'] == '1'):?>
                <?php
                    // Check Page Footer Show Total Sum
                    if($nCurrentPage == $nAllPage){
                        $oOptionKoolRpt = array(
                            "dataSource"        => $this->dataStore("RptBestSell"),
                            "cssClass"          => array(
                                "table"         => "table",
                                

                            ),
                            "showFooter"=>true,
                            "columns"           => array(
                                "rtRowID"             => array(
                                    "label"     => $aDataTextRef['tRowNumber'],
                                    "cssStyle"  => array(
                                        "th"    =>"text-align:left;white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:left;",
                                        "tf"    => "text-align:left; font-weight: bold; background-color:#CFE2F3;"
                                    ),
                                    "footerText"=>"<b>รวม</b>"
                                ),
                                "FTPdtCode"             => array(
                                    "label"     => $aDataTextRef['tPdtCode'],
                                    "cssStyle"  => array(
                                        "th"    =>"text-align:left;white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:left;",
                                        "tf"    => "text-align:left; font-weight: bold; background-color:#CFE2F3;"
                                    ),
                                    
                                ),
                                "FTXsdPdtName"             => array(
                                    "label"     => $aDataTextRef['tPdtName'],
                                    "cssStyle"  => array(
                                        "th"    => "text-align:left;white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:left;",
                                        "tf"    => "text-align:right; font-weight: bold; background-color:#CFE2F3;"
                                    ),
                                    
                                ),
                                "FTPgpChainName"             => array(
                                    "label"     => $aDataTextRef['tPdtGrp'],
                                    "cssStyle"  => array(
                                        "th"    => "text-align:left;white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:left;",
                                        "tf"    => "text-align:right; font-weight: bold; background-color:#CFE2F3;"
                                    ),
                                    
                                ),
                                "FCXsdQty"             => array(
                                    "label"     => $aDataTextRef['tQty'],
                                    "cssStyle"  => array(
                                        "th"    => "text-align:left;white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:left;"
                                    ),
                                   
                                ),
                                "FCXsdDigChg"             => array(
                                    // "label"     => $aDataTextRef['tQty'],
                                    "cssStyle"  => array(
                                        "th"    => "text-align:left;white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:left;"
                                    ),
                                   
                                ),
                                "FCXsdDis"             => array(
                                    "label"     => $aDataTextRef['tPdtCode'],
                                    "cssStyle"  => array(
                                        "th"    => "text-align:left;white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:left;",
                                       
                                    ),
                                   
                                ),
                                "FCXsdNetAfHD"             => array(
                                    "label"     => $aDataTextRef['tPdtCode'],
                                    "cssStyle"  => array(
                                        "th"    => "text-align:left;white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:left;",
                                        "tf"    => "text-align:right; font-weight: bold; background-color:#CFE2F3;"
                                    ),
                                   
                                ),
                                "FCXsdQty"             => array(
                                    "label"     => $aDataTextRef['tQty'],
                                    "type"      =>"number",
                                    "decimals"  =>2,
                                    "footer"    =>"sum",
                                    "cssStyle"  => array(
                                        "th"    => "text-align:right;white-space:nowrap;  font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:right;",
                                        "tf"    => "text-align:right; font-weight: bold; background-color:#CFE2F3;"
                                    )
                                ),
                                "FCXsdDigChg"             => array(
                                    "label"     => $aDataTextRef['tSales'],
                                    "type"      =>"number",
                                    "decimals"  =>2,
                                    "footer"    =>"sum",
                                    "cssStyle"  => array(
                                        "th"    => "text-align:right;white-space:nowrap;  font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:right;",
                                        "tf"    => "text-align:right; font-weight: bold; background-color:#CFE2F3;"
                                    )
                                ),
                                "FCXsdDis"             => array(
                                    "label"     => $aDataTextRef['tDiscount'],
                                    "type"      =>"number",
                                    "decimals"  =>2,
                                    "footer"    =>"sum",
                                    "cssStyle"  => array(
                                        "th"    => "text-align:right;white-space:nowrap;  font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:right;",
                                        "tf"    => "text-align:right; font-weight: bold; background-color:#CFE2F3;"
                                    )
                                ),
                                "FCXsdNetAfHD"             => array(
                                    "label"     => $aDataTextRef['tTotalsales'],
                                    "type"      =>"number",
                                    "decimals"  =>2,
                                    "footer"    =>"sum",
                                    "cssStyle"  => array(
                                        "th"    => "text-align:right;white-space:nowrap;  font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:right;",
                                        "tf"    => "text-align:right; font-weight: bold; background-color:#CFE2F3;"
                                    )
                                )
                                
                            )
                      
                        );
                    }else{
                        $oOptionKoolRpt = array(
                            "dataSource"        => $this->dataStore("RptBestSell"),
                            "cssClass"          => array(
                                "table"         => "table",
                                

                            ),
                            "showFooter"=>true,
                            "columns"           => array(
                                "rtRowID"             => array(
                                    "label"     => $aDataTextRef['tRowNumber'],
                                    "cssStyle"  => array(
                                        "th"    =>"text-align:left;white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:left;",
                                        "tf"    => "text-align:left; font-weight: bold; background-color:#CFE2F3;"
                                    ),
                                    "footerText"=>"<b>รวม</b>"
                                ),
                                "FTPdtCode"             => array(
                                    "label"     => $aDataTextRef['tPdtCode'],
                                    "cssStyle"  => array(
                                        "th"    =>"text-align:left;white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:left;",
                                        "tf"    => "text-align:left; font-weight: bold; background-color:#CFE2F3;"
                                    ),
                                    
                                ),
                                "FTXsdPdtName"             => array(
                                    "label"     => $aDataTextRef['tPdtName'],
                                    "cssStyle"  => array(
                                        "th"    => "text-align:left;white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:left;",
                                        "tf"    => "text-align:right; font-weight: bold; background-color:#CFE2F3;"
                                    ),
                                    
                                ),
                                "FTPgpChainName"             => array(
                                    "label"     => $aDataTextRef['tPdtGrp'],
                                    "cssStyle"  => array(
                                        "th"    => "text-align:left;white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:left;",
                                        "tf"    => "text-align:right; font-weight: bold; background-color:#CFE2F3;"
                                    ),
                                    
                                ),
                                "FCXsdQty"             => array(
                                    "label"     => $aDataTextRef['tQty'],
                                    "cssStyle"  => array(
                                        "th"    => "text-align:left;white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:left;"
                                    ),
                                   
                                ),
                                "FCXsdDigChg"             => array(
                                    // "label"     => $aDataTextRef['tQty'],
                                    "cssStyle"  => array(
                                        "th"    => "text-align:left;white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:left;"
                                    ),
                                   
                                ),
                                "FCXsdDis"             => array(
                                    "label"     => $aDataTextRef['tPdtCode'],
                                    "cssStyle"  => array(
                                        "th"    => "text-align:left;white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:left;",
                                       
                                    ),
                                   
                                ),
                                "FCXsdNetAfHD"             => array(
                                    "label"     => $aDataTextRef['tPdtCode'],
                                    "cssStyle"  => array(
                                        "th"    => "text-align:left;white-space:nowrap; font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:left;",
                                        "tf"    => "text-align:right; font-weight: bold; background-color:#CFE2F3;"
                                    ),
                                   
                                ),
                                "FCXsdQty"             => array(
                                    "label"     => $aDataTextRef['tQty'],
                                    "footer"    =>"sum",
                                    "cssStyle"  => array(
                                        "th"    => "text-align:right;white-space:nowrap;  font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:right;",
                                        "tf"    => "text-align:right; font-weight: bold; background-color:#CFE2F3;"
                                    )
                                ),
                                "FCXsdDigChg"             => array(
                                    "label"     => $aDataTextRef['tSales'],
                                    "footer"    =>"sum",
                                    "cssStyle"  => array(
                                        "th"    => "text-align:right;white-space:nowrap;  font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:right;",
                                        "tf"    => "text-align:right; font-weight: bold; background-color:#CFE2F3;"
                                    )
                                ),
                                "FCXsdDis"             => array(
                                    "label"     => $aDataTextRef['tDiscount'],
                                    "footer"    =>"sum",
                                    "cssStyle"  => array(
                                        "th"    => "text-align:right;white-space:nowrap;  font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:right;",
                                        "tf"    => "text-align:right; font-weight: bold; background-color:#CFE2F3;"
                                    )
                                ),
                                "FCXsdNetAfHD"             => array(
                                    "label"     => $aDataTextRef['tTotalsales'],
                                    "footer"    =>"sum",
                                    "cssStyle"  => array(
                                        "th"    => "text-align:right;white-space:nowrap;  font-weight: bold; background-color:#CFE2F3;",
                                        "td"    => "text-align:right;",
                                        "tf"    => "text-align:right; font-weight: bold; background-color:#CFE2F3;"
                                    )
                                )
                                
                            )
                      
                        );
                    }
                    
                    // Create Table Kool Report
                    Table::create($oOptionKoolRpt);
                ?>
            <?php else:?>
                    <table class="table">
                        <thead>
                            <th nowrap  class="text-center" style="width:10%; background-color:#CFE2F3;"><?php echo @$aDataTextRef['tRowNumber'];?></th>    
                            <th nowrap  class="text-center" style="width:10%; background-color:#CFE2F3;"><?php echo @$aDataTextRef['tPdtName'];?></th>    
                            <th nowrap  class="text-center" style="width:10%; background-color:#CFE2F3;"><?php echo @$aDataTextRef['tPdtGrp'];?></th>   
                            <th nowrap  class="text-center" style="width:10%; background-color:#CFE2F3;"><?php echo @$aDataTextRef['tQty'];?></th>
                            <th nowrap  class="text-center" style="width:10%; background-color:#CFE2F3;"><?php echo @$aDataTextRef['tSales'];?></th> 
                            <th nowrap  class="text-center" style="width:10%; background-color:#CFE2F3;"><?php echo @$aDataTextRef['tDiscount'];?></th>  
                            <th nowrap  class="text-center" style="width:10%; background-color:#CFE2F3;"><?php echo @$aDataTextRef['tTotalsales'];?></th>       
                        </thead>
                        <tbody>
                            <tr><td class='text-center xCNTextDetail2' colspan='100%'><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
                        </tbody>
                    </table>
            <?php endif;?>
            </div>
        </div>
        <div class="xCNFooterPageRpt">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="text-right">
                    <label class="xCNRptLabel"><?php echo $nCurrentPage.' / '.$nAllPage; ?></label>
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

















