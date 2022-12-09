<input id="oetTcgStaBrowse" type="hidden" value="<?=$nTcgBrowseType?>">
<input id="oetTcgCallBackOption" type="hidden" value="<?=$tTcgBrowseOption?>">

<?php if(isset($nTcgBrowseType) && $nTcgBrowseType == 0) : ?>
<div id="odvTcgMainMenu" class="main-menu">
    <div class="xCNMrgNavMenu">
        <div class="row xCNavRow" style="width:inherit;">
            <div class="xCNTcgVMaster">
                <div class="col-xs-12 col-md-8">
                    <ol id="oliMenuNav" class="breadcrumb">
                        <li id="oliTcgTitle" onclick="JSvCallPagePdtTouchGrpList()" style="cursor:pointer"><?= language('product/pdttouchgroup/pdttouchgroup','tTCGTitle')?></li>
                        <li id="oliTcgTitleAdd" class="active"><a><?= language('product/pdttouchgroup/pdttouchgroup','tTCGTitleAdd')?></a></li>
                        <li id="oliTcgTitleEdit" class="active"><a><?= language('product/pdttouchgroup/pdttouchgroup','tTCGTitleEdit')?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-md-4 text-right p-r-0">
                    <div id="odvBtnTcgInfo">
                        <?php if($aAlwEventPdtTouchGrp['tAutStaFull'] == 1 || $aAlwEventPdtTouchGrp['tAutStaAdd'] == 1) : ?>
                        <button class="xCNBTNPrimeryPlus" type="button" onclick="JSvCallPagePdtTouchGrpAdd()">+</button>
                        <?php endif; ?>
                    </div>
                    <div id="odvBtnAddEdit">
                    <div class="demo-button xCNBtngroup" style="width:100%;">
                        <button onclick="JSvCallPagePdtTouchGrpList()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"> <?php echo language('common/main/main', 'tBack')?></button>
                            <?php if($aAlwEventPdtTouchGrp['tAutStaFull'] == 1 || ($aAlwEventPdtTouchGrp['tAutStaAdd'] == 1 || $aAlwEventPdtTouchGrp['tAutStaEdit'] == 1)) : ?>
                            <div class="btn-group">
								<button type="submit" class="btn xWBtnGrpSaveLeft" onclick="JSxSetStatusClickPdtTouchGrpSubmit();$('#obtSubmitPdtTouchGrp').click()"> <?php echo language('common/main/main', 'tSave')?></button>
								<?php echo $vBtnSave?>
							</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="xCNTcgVBrowse">
                <div class="col-xs-12 col-md-6">
                    <a onclick="JCNxBrowseData('<?=$tTcgBrowseOption?>')" class="xWBtnPrevious xCNIconBack" style="float:left;font-size:19px;">
						<i class="fa fa-arrow-left xCNIcon"></i>	
					</a>
                    <ol id="oliTcgNavBrowse" class="breadcrumb xCNBCMenu" style="margin-left:25px">
                        <li class="xWBtnPrevious" onclick="JCNxBrowseData('<?=$tTcgBrowseOption?>')"><a>แสดงข้อมูล : <?= language('product/pdttouchgroup/pdttouchgroup','tTCGTitle')?></a></li>
                        <li class="active"><a><?= language('product/pdttouchgroup/pdttouchgroup','tTCGTitleAdd')?></a></li>
                    </ol>
                </div>
                <div class="col-xs-12 col-md-6 text-right">
                    <div id="odvTcgBtnGroup" class="demo-button xCNBtngroup" style="width:100%;">
                        <button type="button" class="btn xCNBTNPrimery" onclick="$('#obtSubmitPdtTouchGrp').click()"><?= language('common/main/main', 'tSave')?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif;?>
<div class="xCNMenuCump xCNTCGBrowseLine" id="odvMenuCump">
	&nbsp;
</div>
<div class="main-content">
	<div id="odvContentPagePdtTouchGrp"></div>
</div>
<script src="<?= base_url('application/modules/product/assets/src/pdttouchgroup/jPdtTouchGrp.js')?>"></script>