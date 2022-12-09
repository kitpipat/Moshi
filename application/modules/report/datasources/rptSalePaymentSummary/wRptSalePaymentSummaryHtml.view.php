<?php
    use \koolreport\widgets\koolphp\Table;
    $nCurrentPage   = $this->params['nCurrentPage'];
    $nAllPage       = $this->params['nAllPage'];
    $aDataTextRef   = $this->params['aDataTextRef'];
    $tLabelTax      = $aDataTextRef['tRptTaxNo'].' : '.$this->params['tBchTaxNo'];
    $tLabeDataPrint = $aDataTextRef['tRptDatePrint'].' : '.date('Y-m-d');
    $tLabeTimePrint = $aDataTextRef['tRptTimePrint'].' : '.date('H:i:s');
    $tBtnPrint      = $aDataTextRef['tRptPrintHtml'];
    $tCompAndBranch = $this->params['tCompName'];
    $aFilterReport  = $this->params['aFilterReport'];
    $tBranch        = $aDataTextRef['tRptBranch'].' : '.$this->params['tBchName'];
    $tFaxNo         = $aDataTextRef['tRptFaxNo'].' : '.$this->params['tFax'];
    $tHomeNo        = $this->params['tHomeNO'].'  '.$this->params['tSoi'];
    $tRoad          = $this->params['tRoad'].' '.$this->params['tDstName'].' '.$this->params['tPrvName']. ' '.$this->params['tPostCode'];
    $tTel           = $aDataTextRef['tRptTel'].' '.$this->params['tTel'];
    $aDataReport    = $this->params['aDataReturn'];


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

    .table>tfoot>tr{
         border-top: 1px solid black !important;
        background-color: #CFE2F3 !important;
        border-bottom : 6px double black !important;
    }

</style>
<div id="odvRptSaleVatInvoiceByBillHtml">
    <div class="container-fluid xCNLayOutRptHtml">
        <div class="xCNHeaderReport">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <label class="xCNRptTitle"><?php echo $aDataTextRef['tTitleReport'];?></label>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="text-left">
                        <label class="xCNRptLabel"><?php echo $tCompAndBranch; ?></label>
                    </div>
                    <div class="text-left">
                        <label class="xCNRptLabel"><?php echo $tRoad ;?></label>
                    </div>
                    <div class="text-left">
                        <Label class="xCNRptLabel"><?php echo $tTel ;?></label>
                    </div>
                    <div class="text-left">
                        <label class="xCNRptLabel"><?php echo $tFaxNo; ?></label>
                    </div>
                    <div class="text-left">
                        <label class="xCNRptLabel"><?php echo $tBranch; ?></label>
                    </div>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                </div>
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    <!-- Filter Branch Code-->
                    <?php if(!empty($aFilterReport['tBchNameFrom']) && !empty($aFilterReport['tBchNameTo'])):?>
                        <div class="text-left">
                            <label class="xCNRptLabel">
                                <?php echo $aDataTextRef['tRptBchFrom'].' : '.$aFilterReport['tBchNameFrom'].' '.$aDataTextRef['tRptBchTo'].' : '.$aFilterReport['tBchNameTo'];?>
                            </label>
                        </div>
                    <?php endif;?>

                    <!-- Filter Shop Code -->
                    <?php if(!empty($aFilterReport['tShpNameFrom']) && !empty($aFilterReport['tShpNameTo'])):?>
                        <div class="text-left">
                            <label class="xCNRptLabel">
                                <?php echo $aDataTextRef['tRptShopFrom'].' : '.$aFilterReport['tShpNameFrom'].' '.$aDataTextRef['tRptShopTo'].' : '.$aFilterReport['tShpNameTo'];?>
                            </label>
                        </div>
                    <?php endif;?>

                    <!-- Filter Pos Code -->
                    <?php if(!empty($aFilterReport['tPosNameFrom']) && !empty($aFilterReport['tPosNameTo'])):?>
                        <div class="text-left">
                            <label class="xCNRptLabel">
                                <?php echo $aDataTextRef['tRptPosFrom'].' : '.$aFilterReport['tPosNameFrom'].' '.$aDataTextRef['tRptPosTo'].' : '.$aFilterReport['tPosNameTo'];?>
                            </label>
                        </div>
                    <?php endif;?>

                    <!-- Filter Rcv Code -->
                    <?php if(!empty($aFilterReport['tRcvNameFrom']) && !empty($aFilterReport['tRcvNameTo'])):?>
                        <div class="text-left">
                            <label class="xCNRptLabel">
                                <?php echo $aDataTextRef['tRptRcvFrom'].' : '.$aFilterReport['tRcvNameFrom'].' '.$aDataTextRef['tRptRcvTo'].' : '.$aFilterReport['tRcvNameTo'];?>
                            </label>
                        </div>
                    <?php endif;?>

                    <!-- Filter Doc Date -->
                    <?php if(!empty($aFilterReport['tDateFrom']) && !empty($aFilterReport['tDateTo'])):?>
                        <div class="text-left">
                            <label class="xCNRptLabel">
                                <?php echo $aDataTextRef['tRptDateFrom'].' : '.$aFilterReport['tDateFrom'].' '.$aDataTextRef['tRptDateTo'].' : '.$aFilterReport['tDateTo'];?>
                            </label>
                        </div>
                    <?php endif;?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <div class="text-right">
                        <label class="xCNRptLabel"><?php echo $tLabeDataPrint.' '.$tLabeTimePrint ?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="xCNContentReport">
            <div id="odvTableKoolReport" class="table-responsive">
            <?php if(isset($aDataReport['rtCode']) &&  !empty($aDataReport['rtCode']) && $aDataReport['rtCode'] == '1'):?>
                <?php
                    $oOptionKoolRpt = array(
                        "dataSource"        => $this->dataStore("RptSalePaymentSummary"),
                        "cssClass"          => array(
                            "table"         => "table",
                        ),
                        "showFooter"=>true,
                        "columns"           => array(
                            "FTRcvName"             => array(
                                "label"     => $aDataTextRef['tRptPayby'],
                                "cssStyle"  => array(
                                    "th"    => "text-align:left;white-space:nowrap; font-weight: bold;",
                                    "td"    => "text-align:left;"
                                ),
                                "footerText"=> $aDataTextRef['tRptOverall'],
                            ),
                            "NET"             => array(
                                "label"     => $aDataTextRef['tRptTotalSale'],
                                "footer"    =>"sum",
                                "cssStyle"  => array(
                                    "th"    => "text-align:right;white-space:nowrap;  font-weight: bold;",
                                    "td"    => "text-align:right;",
                                    "tf"    => "text-align:right; font-weight: bold;"
                                )
                            )
                            
                        )
                    
                    );
                    
                   
                    Table::create($oOptionKoolRpt);
                ?>
                <?php else:?>
                <table class="table">
                    <thead>
                        <th nowrap  class="text-left" style="width:10%"><?php echo @$aDataTextRef['tRptPayby'];?></th>    
                        <th nowrap  class="text-right" style="width:40%"><?php echo @$aDataTextRef['tRptTotalSale'];?></th>    
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
                    <!-- <label class="xCNRptLabel"><?php echo $nCurrentPage.' / '.$nAllPage; ?></label> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
