<input id="oetANGStaBrowse" type="hidden" value="<?=$nBrowseType?>">
<input id="oetANGCallBackOption" type="hidden" value="<?=$tBrowseOption?>">
 
<div id="odvCpnMainMenu" class="main-menu clearfix">
	<div class="xCNMrgNavMenu">
		<div class="row xCNavRow" style="width:inherit;">
			<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
				<ol id="oliMenuNav" class="breadcrumb">
					<?php FCNxHADDfavorite('agency/0/0');?> 
					<li id="oliAngTitle" class="xCNLinkClick" style="cursor:pointer" onclick="JSvCallPageAgencyList()"><?= language('ticket/agency/agency', 'tSalesAgentInfo'); ?></li>
					<li id="oliAngTitleAdd" class="active"><a><?= language('ticket/agency/agency','tAddSalesAgent')?></a></li>
					<li id="oliAgnTitleEdit" class="active"><a><?= language('ticket/agency/agency','tEditSalesAgent')?></a></li>
				</ol>
			</div>
			
			<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-right p-r-0">
				<div class="demo-button xCNBtngroup" style="width:100%;">
					<div id="odvBtnAgnInfo">
						<?php if($aAlwEventAgency['tAutStaFull'] == 1 || $aAlwEventAgency['tAutStaAdd'] == 1) : ?>
							<button id="obtCpnAdd" class="xCNBTNPrimeryPlus" type="button" onclick="JSvCallPageAgencyAdd()">+</button>
						<?php endif; ?>
					</div>
					<div id="odvBtnAddEdit">
						<button onclick="JSvCallPageAgencyList()" class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"><?= language('common/main/main', 'tBack')?></button>
						<?php if($aAlwEventAgency['tAutStaFull'] == 1 || ($aAlwEventAgency['tAutStaAdd'] == 1 || $aAlwEventAgency['tAutStaEdit'] == 1)) : ?>
							<div class="btn-group"  id="obtBarSubmitAng">
								<div class="btn-group">
									<button type="submit" class="btn xWBtnGrpSaveLeft" onclick="$('#obtSubmitAgency').click()"><?= language('common/main/main', 'tSave')?></button>
									<?=$vBtnSave?>
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
	<div id="odvContentPageAgency" class="panel panel-headline">
	</div>
</div>
<script src="<?php echo base_url('application/modules/ticket/assets/src/agency/jAgency.js')?>"></script>