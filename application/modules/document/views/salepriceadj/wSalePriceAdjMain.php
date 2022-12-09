
<div class="panel panel-headline"> 
	<div class="panel-heading"> 
		<div class="row">
			<div class="col-xs-9 col-md-9 col-lg-9">
				<div class ="row">
					<div class="col-xs-12 col-md-6 col-lg-6">
						<div class="form-group">
							<label class="xCNLabelFrm"><?php echo language('common/main/main','tSearch')?></label>
							<div class="input-group">
								<input type="text" class="form-control" id="oetSearchSpa" name="oetSearchSpa" placeholder="<?php echo language('common/main/main','tPlaceholder')?>">
								<span class="input-group-btn">
									<button id="oimSearchSpa" class="btn xCNBtnSearch" type="button">
										<img class="xCNIconAddOn" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
									</button>
								</span>
							</div>
						</div>
					</div>
					<div class="col-xs-6 col-md-6 col-lg-6">
						<div style="margin-top:28px;">
							<a id="oahTWOAdvanceSearch" class="btn xCNBTNDefult xCNBTNDefult1Btn" href="javascript:;" style="margin-bottom:5px;"><?php echo language('document/salepriceadj/salepriceadj','tPdtAdvSearch');?></a>
							<a id="oahTWOSearchReset" class="btn xCNBTNDefult xCNBTNDefult1Btn" href="javascript:;" style="margin-bottom:5px;" onclick="JSxTWOClearSearchData()"><?php echo language('document/salepriceadj/salepriceadj','tPdtClearData');?></a>
						</div>
					</div>
				</div>
			</div>
			<?php if($aAlwEventSalePriceAdj['tAutStaFull'] == 1 || $aAlwEventSalePriceAdj['tAutStaDelete'] == 1 ) : ?>
			<div class="col-xs-12 col-md-3 col-lg-3 text-right" style="margin-top:25px;">
				<div id="odvMngTableList" class="btn-group xCNDropDrownGroup">
					<button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
						<?php echo language('common/main/main','tCMNOption')?>
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						<li id="oliBtnDeleteAll" class="disabled">
							<a data-toggle="modal" data-target="#odvModalDelSpa"><?php echo language('common/main/main','tDelAll')?></a>
						</li>
					</ul>
				</div>
			</div>
			<?php endif; ?>
		</div>

		<div class="row fadeIn" id="odvSPAAdvanceSearchContainer" style="margin-bottom:20px;">
			<div class="col-xs-12 col-md-6 col-lg-6">
				<div class="col-lg-12 col-md-12 col-xs-12 no-padding">
					<label class="xCNLabelFrm"><?= language('document/salepriceadj/salepriceadj','tSpaBRWBranchTitle')?></label>
				</div>
				<div class="col-lg-6 col-md-6 col-xs-6 no-padding padding-right-15">
					<div class="form-group">
						<div class="input-group">
							<input class="form-control xCNHide" id="oetBchCodeFrom" name="oetBchCodeFrom" maxlength="5">
							<input class="form-control xWPointerEventNone" type="text" id="oetBchNameFrom" name="oetBchNameFrom" placeholder="<?= language('document/salepriceadj/salepriceadj','tPdtFromData')?>" readonly="">
							<!-- ถ้า user มีสาขาจะไม่สามารถ Brw ได้ -->
							<span class="input-group-btn">
								<button id="obtSPABrowseBchFrom" type="button" class="btn xCNBtnBrowseAddOn">
									<img src="<?php echo base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
								</button>
							</span>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-xs-6 no-padding padding-left-15">
					<div class="form-group">
						<div class="input-group">
							<input class="form-control xCNHide" id="oetBchCodeTo" name="oetBchCodeTo" maxlength="5">
							<input class="form-control xWPointerEventNone" type="text" id="oetBchNameTo" name="oetBchNameTo" placeholder="<?= language('document/salepriceadj/salepriceadj','tPdtFromTo')?>" readonly="">
							<!-- ถ้า user มีสาขาจะไม่สามารถ Brw ได้ -->
							<span class="input-group-btn">
								<button id="obtSPABrowseBchTo" type="button" class="btn xCNBtnBrowseAddOn">
									<img src="<?php echo base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
								</button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-6">
				<div class="col-lg-12 col-md-12 col-xs-12 no-padding">
					<label class="xCNLabelFrm"><?= language('document/salepriceadj/salepriceadj','tTBSpaDocDate')?></label>
				</div>
				<div class="col-lg-6 col-md-6 col-xs-6 no-padding padding-right-15">
					<div class="form-group">
						<div class="validate-input">
							<input class="form-control input100 xCNDatePicker" type="text" name="oetSearchDocDateFrom" id="oetSearchDocDateFrom" aria-invalid="false" placeholder="<?= language('document/salepriceadj/salepriceadj','tPdtFromData')?>" data-validate="Please Insert Doc Date">
							<span class="prefix xCNiConGen xCNIconBrowse"><i class="fa fa-calendar" aria-hidden="true" onclick="$('#oetTWOSearchDocDateFrom').focus()"></i></span>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-xs-6 no-padding padding-left-15">
					<div class="form-group">
						<div class="validate-input">
							<input class="form-control input100 xCNDatePicker" type="text" name="oetSearchDocDateTo" id="oetSearchDocDateTo" aria-invalid="false" placeholder="<?= language('document/salepriceadj/salepriceadj','tPdtFromTo')?>" data-validate="Please Insert Doc Date">
							<span class="prefix xCNiConGen xCNIconBrowse"><i class="fa fa-calendar" aria-hidden="true" onclick="$('#oetTWOSearchDocDateTo').focus()"></i></span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-3 col-lg-3">
				<div class="col-lg-12 col-md-12 col-xs-12 no-padding">
					<label class="xCNLabelFrm"><?= language('document/salepriceadj/salepriceadj','tTBSpaStaDoc')?></label>
				</div>
				<div class="form-group">
					<select class="selectpicker form-control" id="ocmStaDoc" name="ocmStaDoc">
						<option value="0"><?php echo language('common/main/main','tStaDocAll')?></option>
						<option value="1"><?php echo language('common/main/main','tStaDocComplete')?></option>
						<option value="2"><?php echo language('common/main/main','tStaDocinComplete')?></option>
						<option value="3"><?php echo language('common/main/main','tStaDocCancel')?></option>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-3 col-lg-3">
				<div class="col-lg-12 col-md-12 col-xs-12 no-padding">
					<label class="xCNLabelFrm"><?= language('document/salepriceadj/salepriceadj','tTBSpaXphUsrApv')?> </label>
				</div>
				<div class="form-group">
					<select class="selectpicker form-control" id="ocmStaApprove" name="ocmStaApprove">
						<option value="0"><?php echo language('common/main/main','tStaDocAll')?></option>
						<option value="2"><?php echo language('common/main/main','tStaDocPendingApv')?></option>
						<option value="1"><?php echo language('common/main/main','tStaDocApv')?></option>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-3 col-lg-3">
				<div class="col-lg-12 col-md-12 col-xs-12 no-padding">
					<label class="xCNLabelFrm"><?= language('document/salepriceadj/salepriceadj','tTBSpaStaPrcDoc')?> </label>
				</div>
				<div class="form-group">
					<select class="selectpicker form-control" id="ocmStaPrcStk" name="ocmStaPrcStk">
						<option value="0"><?php echo language('common/main/main','tStaDocAll')?></option>
						<option value="3"><?php echo language('common/main/main','tStaDocPendingProcessing')?></option>
						<option value="2"><?php echo language('common/main/main','tStaDocProcessing')?></option>
						<option value="1"><?php echo language('common/main/main','tStaDocProcessor')?></option>
					</select>
				</div>
			</div>
			<div class="col-lg-12">
				<a id="oahSPAAdvanceSearchSubmit" class="btn xCNBTNDefult xCNBTNDefult1Btn pull-right" href="javascript:;"><?= language('document/salepriceadj/salepriceadj','tSpaSearch')?></a>
			</div>
		</div>

	</div>
	<div class="panel-body">
		<section id="ostDataSpa"></section>
	</div>
</div>

<script>
	
    // ตรวจสอบระดับของ User  24/03/2020 Saharat(Golf)
    var tStaUsrLevel    = '<?php  echo $this->session->userdata("tSesUsrLevel"); ?>';
    var tUsrBchCode     = '<?php  echo $this->session->userdata("tSesUsrBchCode"); ?>';
    var tUsrBchName     = '<?php  echo $this->session->userdata("tSesUsrBchName"); ?>';
    var tUsrShpCode     = '<?php  echo $this->session->userdata("tSesUsrShpCode"); ?>';
	var tUsrShpName     = '<?php  echo $this->session->userdata("tSesUsrShpName"); ?>';
	
	$(document).ready(function(){
        
        // ตรวจสอบระดับUser banchFrom  24/03/2020 Saharat(Golf)
        if(tUsrBchCode  != ""){ 
            $('#oetBchCodeFrom').val(tUsrBchCode);
            $('#oetBchNameFrom').val(tUsrBchName);
            $('#obtSPABrowseBchFrom').attr("disabled", true);
        }
        // ระดับUser banchTo  24/03/2020 Saharat(Golf)
        if(tUsrBchCode  != ""){ 
            $('#oetBchCodeTo').val(tUsrBchCode);
            $('#oetBchNameTo').val(tUsrBchName);
            $('#obtSPABrowseBchTo').attr("disabled", true);
		}
	});

	$('.selectpicker').selectpicker();
	$('#odvSPAAdvanceSearchContainer').hide();
	$('#oahTWOAdvanceSearch').click(function(){
		$('#odvSPAAdvanceSearchContainer').toggle();
	});
	$('#oimSearchSpa').click(function(){
		JCNxOpenLoading();
		JSvSpaDataTable();
	});
	$('#oetSearchSpa').keypress(function(event){
		if(event.keyCode == 13){
			JCNxOpenLoading();
			JSvSpaDataTable();
		}
	});

	$('#oahSPAAdvanceSearchSubmit').click(function(){
		JCNxOpenLoading();
		JSvSpaDataTable();
	});
	$('#oahTWOSearchReset').click(function(){
		$('#oetSearchSpa').val('');
		$('#oetBchCodeFrom').val('');
		$('#oetBchNameFrom').val('');
		$('#oetBchCodeTo').val('');
		$('#oetBchNameTo').val('');
		$('#oetSearchDocDateFrom').val('');
		$('#oetSearchDocDateTo').val('');
        $(".selectpicker").val('0').selectpicker("refresh");
	});


	//Date and Time
	$('#obtXphDocDateFrom').click(function(){
		event.preventDefault();
		$('#oetXphDocDateFrom').datepicker('show');
	});
	$('#obtXphDocDateTo').click(function(){
		event.preventDefault();
		$('#oetXphDocDateTo').datepicker('show');
	});
	$('.xCNDatePicker').datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
	});

	
	//Event Browse
	$('#obtSPABrowseBchFrom').unbind().click(function(){
		var nStaSession = JCNxFuncChkSessionExpired();
		if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
			JSxCheckPinMenuClose(); // Hide Menu Pin
			JCNxBrowseData('oSpaBrowseBchFrom');
		}else{
			JCNxShowMsgSessionExpired();
		}
	});
	$('#obtSPABrowseBchTo').unbind().click(function(){
		var nStaSession = JCNxFuncChkSessionExpired();
		if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
			JSxCheckPinMenuClose(); // Hide Menu Pin
			JCNxBrowseData('oSpaBrowseBchTo');
		}else{
			JCNxShowMsgSessionExpired();
		}
	});

	//Option Branch To
	var oSpaBrowseBchTo = {
		
		Title : ['company/branch/branch','tBCHTitle'],
		Table:{Master:'TCNMBranch',PK:'FTBchCode'},
		Join :{
			Table:	['TCNMBranch_L'],
			On:['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
		},
		GrideView:{
			ColumnPathLang	: 'company/branch/branch',
			ColumnKeyLang	: ['tBCHCode','tBCHName'],
			ColumnsSize     : ['15%','75%'],
			WidthModal      : 50,
			DataColumns		: ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
			DataColumnsFormat : ['',''],
			Perpage			: 5,
			OrderBy			: ['TCNMBranch_L.FTBchName'],
			SourceOrder		: "ASC"
		},
		CallBack:{
			ReturnType	: 'S',
			Value		: ["oetBchCodeTo","TCNMBranch.FTBchCode"],
			Text		: ["oetBchNameTo","TCNMBranch_L.FTBchName"],
		},
	}

	//Option Branch From
	var oSpaBrowseBchFrom = {
		
		Title : ['company/branch/branch','tBCHTitle'],
		Table:{Master:'TCNMBranch',PK:'FTBchCode'},
		Join :{
			Table:	['TCNMBranch_L'],
			On:['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
		},
		GrideView:{
			ColumnPathLang	: 'company/branch/branch',
			ColumnKeyLang	: ['tBCHCode','tBCHName'],
			ColumnsSize     : ['15%','75%'],
			WidthModal      : 50,
			DataColumns		: ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
			DataColumnsFormat : ['',''],
			Perpage			: 5,
			OrderBy			: ['TCNMBranch_L.FTBchName'],
			SourceOrder		: "ASC"
		},
		CallBack:{
			ReturnType	: 'S',
			Value		: ["oetBchCodeFrom","TCNMBranch.FTBchCode"],
			Text		: ["oetBchNameFrom","TCNMBranch_L.FTBchName"],
		},
	}

</script>
