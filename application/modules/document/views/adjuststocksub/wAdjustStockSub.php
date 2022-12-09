<input id="oetAdjStkSubStaBrowse" type="hidden" value="<?=$nBrowseType?>">
<input id="oetAdjStkSubCallBackOption" type="hidden" value="<?=$tBrowseOption?>">

<div id="odvAdjStkSubMainMenu" class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="xCNAdjStkSubVMaster">
                <div class="col-xs-12 col-md-8">
                    <ol id="oliMenuNav" class="breadcrumb">
                        <li id="oliAdjStkSubTitle" class="xCNLinkClick" onclick="JSvCallPageAdjStkSubList()"><?= language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubTitle') ?></li>
                        <li id="oliAdjStkSubTitleAdd" class="active"><a href="javascript:;"><?= language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubTitleAdd') ?></a></li>
                        <li id="oliAdjStkSubTitleEdit" class="active"><a href="javascript:;"><?= language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubTitleEdit') ?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-md-4 text-right p-r-0">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <div id="odvBtnAdjStkSubInfo">
                            <?php if ($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaAdd'] == 1) : ?>
                                <button class="xCNBTNPrimeryPlus" type="button" onclick="JSvCallPageAdjStkSubAdd()">+</button>
                            <?php endif; ?>
                        </div>
                        <div id="odvBtnAddEdit">
                            <div class="demo-button xCNBtngroup" style="width:100%;">
                                <button onclick="JSvCallPageAdjStkSubList()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack') ?></button>
                                <?php if ($aAlwEvent['tAutStaFull'] == 1 || ($aAlwEvent['tAutStaAdd'] == 1 || $aAlwEvent['tAutStaEdit'] == 1)) : ?>
                                    <button id="obtAdjStkSubCancel" onclick="JSnAdjStkSubCancel(false)" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCancel') ?></button>
                                    <button id="obtAdjStkSubApprove" onclick="JSnAdjStkSubApprove(false)" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tCMNApprove') ?></button>
                                    <div class="btn-group">
                                        <button type="button" class="btn xWBtnGrpSaveLeft" onclick="$('#obtSubmitAdjStkSub').click()"> <?php echo language('common/main/main', 'tSave') ?></button>
                                        <?php echo $vBtnSave ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="xCNAdjStkSubVBrowse">
                <div class="col-xs-12 col-md-6">
                    <a class="xWBtnPrevious xCNIconBack" style="float:left;font-size:19px;">
                        <i class="fa fa-arrow-left xCNIcon"></i>	
                    </a>
                    <ol id="oliAdjStkSubNavBrowse" class="breadcrumb xCNBCMenu" style="margin-left:25px">
                        <li class="xWBtnPrevious"><a><?= language('common/main/main', 'tShowData') ?> : <?= language('promotion/promotion/promotion', 'tPMTTitle') ?></a></li>
                        <li class="active"><a><?= language('common/main/main', 'tAddData') ?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-md-6 text-right">
                    <div id="odvAdjStkSubBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                        <button type="button" class="btn xCNBTNPrimery" onclick="$('#obtSubmitAdjStkSub').click()"><?= language('common/main/main', 'tSave') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="xCNMenuCump xCNAdjStkSubBrowseLine" id="odvMenuCump">&nbsp;</div>
<div class="main-content">
    <div id="odvContentPageAdjStkSub"></div>
</div>
<script>
    var tBaseURL = '<?php echo base_url(); ?>';
    //tSys Decimal Show
    var nOptDecimalShow = '<?php echo $nOptDecimalShow; ?>';
    var nOptDecimalSave = '<?php echo $nOptDecimalSave; ?>';

    // Set Lang Edit 
    var nLangEdits 	= <?php echo $this->session->userdata("tLangEdit"); ?>;
    var tUsrApv 	= <?php echo $this->session->userdata("tSesUsername"); ?>;
</script>

<!-- Load Lang Eticket -->
<?php if ($_SESSION['lang'] == 'en'):?>
<script src="<?=base_url()?>application/modules/ticket/assets/src/locales/jEN.js"></script>
<?php else:?>
<script src="<?=base_url()?>application/modules/ticket/assets/src/locales/jTH.js"></script>
<?php endif?>
<!-- END Load Lang Eticket -->

<script type="text/javascript" src="<?php echo base_url() ?>application/modules/document/assets/src/adjuststocksub/jAdjustStockSub.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/TableDnD/0.9.1/jquery.tablednd.js" integrity="sha256-d3rtug+Hg1GZPB7Y/yTcRixO/wlI78+2m08tosoRn7A=" crossorigin="anonymous"></script>












