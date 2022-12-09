<?php
if($aResult['rtCode'] == "1"){
	$tVocCode       	= $aResult['raItems']['rtVocCode'];
	$tVocBarCode       	= $aResult['raItems']['rtVocBarCode'];
	$dVocExpired       	= $aResult['raItems']['rdVocExpired'];
	$tVotCode       	= $aResult['raItems']['rtVotCode'];
	$tVotName       	= $aResult['raItems']['rtVotName'];
	$cVocValue       	= $aResult['raItems']['rcVocValue'];
	$cVocSalePri       	= $aResult['raItems']['rcVocSalePri'];
	$cVocBalance     	= $aResult['raItems']['rcVocBalance'];
	$tVocComBook     	= $aResult['raItems']['rtVocComBook'];
	$tVocStaBook       	= $aResult['raItems']['rtVocStaBook'];
	$tVocStaSale       	= $aResult['raItems']['rtVocStaSale'];
	$tVocStaUse       	= $aResult['raItems']['rtVocStaUse'];
	$tVocName       	= $aResult['raItems']['rtVocName'];
	$tVocRemark       	= $aResult['raItems']['rtVocRemark'];
	$tRoute         	= "voucherEventEdit";
	
	//Event Control
	if(isset($aAlwEventVoucher)){
		if($aAlwEventVoucher['tAutStaFull'] == 1 || $aAlwEventVoucher['tAutStaEdit'] == 1){
			$nAutStaEdit = 1;
		}else{
			$nAutStaEdit = 0;
		}
	}else{
		$nAutStaEdit = 0;
	}
	//Event Control
	
}else{
	$tVocCode       	= "";
	$tVocBarCode       	= "";
	$dVocExpired       	= "";
	$tVotCode       	= "";
	$tVotName       	= "";
	$cVocValue       	= "";
	$cVocSalePri       	= "";
	$cVocBalance     	= "";
	$tVocComBook     	= "";
	$tVocStaBook       	= "";
	$tVocStaSale       	= "";
	$tVocStaUse       	= "";
	$tVocName       	= "";
	$tVocRemark       	= "";
	$tRoute         = "voucherEventAdd";

	$nAutStaEdit = 0; //Event Control
}
?></span>

<input type="hidden" id="ohdVocAutStaEdit" value="<?=$nAutStaEdit?>">
<form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddVoucher">
	<button style="display:none" type="submit" id="obtSubmitVoucher" onclick="JSnAddEditVoucher('<?= $tRoute?>')"></button>
    <div class="panel-body" style="padding-top:20px !important;">
        <div class="row">
            <div class="col-xs-12 col-md-6 col-lg-6">
				<button style="display:none" type="submit" id="obtSubmitPaymentMethod"></button>	
				<div class="form-group">
                    <label class="xCNLabelFrm"><span class="text-danger">*</span> <?= language('promotion/voucher/voucher','tVOCTBVocCode')?></label>
                    <div id="odvVoucherAutoGenCode" class="form-group">
                    <div class="validate-input">
                        <label class="fancy-checkbox">
                            <input type="checkbox" id="ocbVoucherAutoGenCode" name="ocbVoucherAutoGenCode" checked="true" value="1">
                            <span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
                        </label>
                    </div>
                </div>
                    <div id="odvVoucherCodeForm" class="form-group">
                        <input type="hidden" id="ohdCheckDuplicatePdtCode" name="ohdCheckDuplicatePdtCode" value="1"> 
                                <div class="validate-input">
                                <input 
                                type="text" 
                                class="form-control xCNInputWithoutSpcNotThai" 
                                maxlength="5" 
                                id="oetVocCode" 
                                name="oetVocCode"
                                data-is-created="<?php echo $tVocCode;?>"
                                placeholder="<?= language('promotion/voucher/voucher','tVOCValidCode')?>"
                                value="<?php echo $tVocCode; ?>" 
                                data-validate-required = "<?= language('promotion/voucher/voucher','tVOCValidCheckCode')?>"
                                data-validate-dublicateCode = "<?= language('promotion/voucher/voucher','tVOCValidCheckCode')?>"
               
                            >
                        </div>
                    </div>
                </div>
				<div class="form-group">
                    <label class="xCNLabelFrm"><span class="text-danger">*</span> <?= language('promotion/voucher/voucher','tVOCTBVocName')?></label>
                    <input type="text" class="form-control xWTooltipsBT" maxlength="100" id="oetVocName" name="oetVocName" maxlength="100" value="<?= $tVocName?>" data-toggle="tooltip" data-validate="<?= language('promotion/voucher/voucher','tVOCValidName')?>">
                </div>

				<div class="form-group">
                    <label class="xCNLabelFrm"><?= language('promotion/voucher/voucher','tVOCTBType')?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide" id="ohdVotCode" name="ohdVotCode" maxlength="5" value="<?=$tVotCode?>">
                        <input type="text" class="form-control xCNInputWithoutSpcNotThai" id="oetVotName" name="oetVotName" value="<?=$tVotName?>">
                        <span class="input-group-btn">
                            <button id="obtVocBrowseVot" type="button" class="btn xCNBtnBrowseAddOn">
                                <img src="  <?php echo base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                            </button>
                        </span>
                    </div>
                </div>

				<div class="form-group">
                    <label class="xCNLabelFrm"><?= language('promotion/voucher/voucher','tVOCTBBarcode')?></label>
                    <input type="text" class="form-control" maxlength="30" id="oetVocBarCode" name="oetVocBarCode" maxlength="30" value="<?= $tVocBarCode?>">
                </div>

				<div class="form-group">
                    <label class="xCNLabelFrm"><?= language('promotion/voucher/voucher','tVOCTBExpired')?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetVocExpired" name="oetVocExpired" autocomplete="off" value="<?=$dVocExpired?>">
                        <span class="input-group-btn">
                            <button id="obtVocExpired" type="button" class="btn xCNBtnDateTime">
                                <img src="  <?php echo base_url().'/application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                            </button>
                        </span>
                    </div>
                </div>

				<div class="form-group">
                    <label class="xCNLabelFrm"><?= language('promotion/voucher/voucher','tVOCTBValue')?></label>
                    <input type="text" class="form-control xCNInputNumericWithDecimal text-right" id="oetVocValue" name="oetVocValue"  placeholder="0.00" maxlength="18" value="<?=$cVocValue?>">
                </div>

				<div class="form-group">
                    <label class="xCNLabelFrm"><?= language('promotion/voucher/voucher','tVOCTBSalePri')?></label>
                    <input type="text" class="form-control xCNInputNumericWithDecimal text-right" id="oetVocSalePri" name="oetVocSalePri"  placeholder="0.00" maxlength="18" value="<?=$cVocSalePri?>">
                </div>

				<div class="form-group">
                    <label class="xCNLabelFrm"><?= language('promotion/voucher/voucher','tVOCTBRemark')?></label>
                    <textarea class="form-control" maxlength="100" rows="4" id="otaVocRemark" name="otaVocRemark" maxlength="100"><?=$tVocRemark?></textarea>
                </div>

            </div>
        </div>
    </div>
</form>
<?php include "script/jVoucherAdd.php"; ?>
<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.xWTooltipsBT').tooltip({'placement': 'bottom'});
    $('[data-toggle="tooltip"]').tooltip({'placement': 'top'});
    
    $('#oetVocExpired').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        enableOnReadonly: false,
        startDate :'1900-01-01',
        disableTouchKeyboard : true,
        autoclose: true,
    });
    $('#oetVocExpired').click(function(event){
        $('#oetVocExpired').datepicker('show');
		event.preventDefault();
    });
	$('#obtVocExpired').click(function(event){
		$('#oetVocExpired').datepicker('show');
		event.preventDefault();
	});
});

//Lang Edit In Browse
var nLangEdits = <?=$this->session->userdata("tLangEdit")?>;
//Set Option Browse -----------
//Option Depart
var oVocBrowseVot = {
    Title : ['promotion/voucher/vouchertype','tVOTTitle'],
    Table:{Master:'TFNMVoucherType',PK:'FTVotCode'},
    Join :{
        Table:	['TFNMVoucherType_L'],
        On:['TFNMVoucherType_L.FTVotCode = TFNMVoucherType.FTVotCode  AND TFNMVoucherType_L.FNLngID = '+nLangEdits,],
    },
    Where :{
            Condition : ["AND TFNMVoucherType.FTVotStaUse = 1 "]
    },
    GrideView:{
        ColumnPathLang	: 'promotion/voucher//vouchertype',
        ColumnKeyLang	: ['tVOTTBCode','tVOTTBName'],
        DataColumns		: ['TFNMVoucherType.FTVotCode','TFNMVoucherType_L.FTVotName'],
        ColumnsSize     : ['20%','80%'],
        DataColumnsFormat : ['',''],
        WidthModal      : 50,
        Perpage			: 10,
		OrderBy			: ['TFNMVoucherType.FTVotCode'],
		SourceOrder		: "ASC"
    },
    CallBack:{
        ReturnType	: 'S',
        Value		: ["ohdVotCode","TFNMVoucherType.FTVotCode"],
		Text		: ["oetVotName","TFNMVoucherType_L.FTVotName"],
    },
    BrowseLev : 1

}
//Event Browse
$('#obtVocBrowseVot').click(function(){JCNxBrowseData('oVocBrowseVot');});
</script>