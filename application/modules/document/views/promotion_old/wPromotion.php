<input id="oetPmtStaBrowse" type="hidden" value="<?=$nBrowseType?>">
<input id="oetPmtCallBackOption" type="hidden" value="<?=$tBrowseOption?>">

<div class="main-menu clearfix">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="col-xs-12 col-md-8">
                <ol id="oliMenuNav" class="breadcrumb xCNBCMenu">
                    <li id="oliPmtTitle" class="xCNLinkClick" style="cursor:pointer" onclick="JSvCallPagePromotionList()"><?= language('document/promotion/promotion', 'tPMTTitle') ?></li>
                    <li id="oliPmtTitleSel" class="active"><a><?= language('document/promotion/promotion', 'tPMTSelPmtType') ?></a></li>
                    <li id="oliPmtTitleAdd" class="active"><a><?= language('document/promotion/promotion', 'tPMTTitleAdd') ?></a></li>
                    <li id="oliPmtTitleEdit" class="active"><a><?= language('document/promotion/promotion', 'tPMTTitleEdit') ?></a></li>
                </ol>
            </div>

            <div class="col-xs-12 col-md-4 text-right">
                <div class="demo-button xCNBtngroup" style="width:100%;">
                    <div id="odvBtnPmtInfo">
                        <?php if ($aAlwEventPromotion['tAutStaFull'] == 1 || $aAlwEventPromotion['tAutStaAdd'] == 1) : ?>
                            <button id="obtฺBnkAdd" class="xCNBTNPrimeryPlus" type="button" onclick="JSvCallPageTSysPromotionList()">+</button>
                        <?php endif; ?>
                    </div>
                    <div id="odvBtnAddEdit">
                        <button onclick="JSvCallPagePromotionList()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"><?= language('common/main', 'tBack') ?></button>
                        <?php if ($aAlwEventPromotion['tAutStaFull'] == 1 || ($aAlwEventPromotion['tAutStaAdd'] == 1 || $aAlwEventPromotion['tAutStaEdit'] == 1)) : ?>
                            <div class="btn-group"  id="obtBarSubmitPmt">
                                <div class="btn-group">
                                    <button type="submit" class="btn xWBtnGrpSaveLeft" onclick="$('#obtSubmitPromotion').click()"><?= language('common/main', 'tSave') ?></button>
                                    <?= $vBtnSave ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="xCNMenuCump" id="odvMenuCump">
    &nbsp;
</div>
<div class="main-content">
    <div id="odvContentPagePromotion" class="panel panel-headline">
    </div>
</div>
<script src="<?= base_url('application/assets/src/pos5/jPromotion.js')?>"></script>



