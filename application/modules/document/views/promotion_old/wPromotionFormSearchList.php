<div class="panel-heading">
	<div class="row">
        <div class="col-xs-8 col-md-4 col-lg-4">
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('common/main','tSearchNew')?></label>
                <div class="input-group">
                    <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchAll" name="oetSearchAll" onkeypress="Javascript:if(event.keyCode==13) JSvCallPagePromotionDataTable()" autocomplete="off"  placeholder="<?php echo language('common/main','tPlaceholder'); ?>">
                    <span class="input-group-btn">
                        <button class="btn xCNBtnSearch" type="button" onclick="JSvCallPagePromotionDataTable()">
                            <img class="xCNIconBrowse" src="<?php echo base_url().'/application/assets/icons/search-24.png'?>">
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xs-4 col-md-8 col-lg-8 text-right" style="margin-top:34px;">
            <div id="odvMngTableList" class="btn-group xCNDropDrownGroup">
                <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                    <?= language('common/main','tCMNOption')?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li id="oliBtnDeleteAll" class="disabled">
                    <a data-toggle="modal" data-target="#odvModalDelPromotion"><?= language('common/main','tDelAll')?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="panel-body">
	<div id="odvContentPromotionData"></div>
</div>




<!-- 
<div class="panel-body">
    <section id="ostSearchPromotion">
            <div class="row">
                <div class="col-xs-8 col-md-4">
                    <div class="form-group">
                        <div class="wrap-input100">
                            <span class="label-input100"><?= language('common/main','tSearch')?></span>
                            <input class="input100" type="text" id="oetSearchAll" name="oetSearchAll" onkeyup="Javascript:if(event.keyCode==13) JSvCallPagePromotionDataTable()" autocomplete="off">
                            <span class="focus-input100"></span>
                            <img onclick="JSvCallPagePromotionDataTable()" class="xCNIconBrowse" src="<?= base_url().'/application/assets/icons/search-24.png'?>">														
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<div id="odvContentPromotionData"></div>

<div class="modal fade" id="odvModalDelPromotion">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" style="display:inline-block"><?=language('common/main', 'tModalDelete')?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<span id="ospConfirmDelete"> - </span>
				<input type='hidden' id="ospConfirmIDDelete">
			</div>
			<div class="modal-footer">
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSnPromotionDelChoose()">
					<i class="fa fa-check-circle" aria-hidden="true"></i> <?=language('common/main', 'tModalConfirm')?>
				</button>
				<button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
					<i class="fa fa-times-circle" aria-hidden="true"></i> <?=language('common/main', 'tModalCancel')?>
				</button>
			</div>
		</div>
	</div>
</div> -->
