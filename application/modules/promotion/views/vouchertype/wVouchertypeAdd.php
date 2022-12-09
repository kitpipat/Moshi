<?php
if($aResult['rtCode'] == "1"){
	$tVotCode       	= $aResult['raItems']['rtVotCode'];
	$tVotName       	= $aResult['raItems']['rtVotName'];
	$tVotStaUse    	    = $aResult['raItems']['rtVotStaUse'];
    $tVotRemark       	= $aResult['raItems']['rtVotRemark'];

	$tRoute         	= "vouchertypeEventEdit";

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
    $tVotCode       	= "";
	$tVotName       	= "";
	$tVotStaUse      	= "";
    $tVotRemark         = "";

	$tRoute             = "vouchertypeEventAdd";
	$nAutStaEdit        = 0; //Event Control
}
if($tVotStaUse == ""){$tVotStaUse == 1;}
?>
<input type="hidden" id="ohdVocAutStaEdit" value="<?=$nAutStaEdit?>">
    <form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddVouchertype">
        <button style="display:none" type="submit" id="obtSubmitVoucher" onclick="JSnAddEditVoucher('<?= $tRoute?>')"></button>
            <div class="panel-body" style="padding-top:20px !important;">
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-6">
                    <button style="display:none" type="submit" id="obtSubmitPaymentMethod"></button>	
                        <div class="form-group">
                        <label class="xCNLabelFrm"><span class="text-danger">*</span> <?= language('promotion/voucher/vouchertype','tVOTTBCode')?><?= language('promotion/voucher/vouchertype','tVOTTitle')?></label>
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
                                id="oetVotCode" 
                                name="oetVotCode"
                                data-is-created="<?php echo $tVotCode;?>"
                                placeholder="<?= language('promotion/voucher/voucher','tVOCValidCode')?>"
                                value="<?= $tVotCode; ?>" 
                                data-validate-required = "<?= language('promotion/voucher/voucher','tVOCValidCheckCode')?>"
                                data-validate-dublicateCode = "<?= language('promotion/voucher/voucher','tVOCValidCheckCode')?>"
                            >
                        </div>
                    </div>
                </div>
				<div class="form-group">
                    <label class="xCNLabelFrm"><span class="text-danger">*</span> <?= language('promotion/voucher/vouchertype','tVOTTBName')?></label>
                    <input type="text" class="form-control xWTooltipsBT" maxlength="100" id="oetVotName" name="oetVotName" maxlength="100" value="<?= $tVotName?>" data-toggle="tooltip" data-validate="<?= language('promotion/voucher/voucher','tVOCValidName')?>">
                </div>
                <div class="form-group">
                    <div class="validate-input">
                        <label class="xCNLabelFrm"><?php echo language('authen/department/department','tDPTRemark')?></label>
                        <textarea class="form-control" maxlength="100" rows="4" id="otaVotRemark" name="otaVotRemark"><?= $tVotRemark?></textarea>
                    </div>
                </div>
				<div class="form-group">
                <?php 
                    if  (!isset($tVotStaUse) || $tVotStaUse != 1 )  : ?>
                    <input type="checkbox" id="ocbVotcheck" name="ocbVotcheck"  value="1"> 
                <?php else: ?> 
                    <input type="checkbox" id="ocbVotcheck" name="ocbVotcheck" checked="true" value="<?=$tVotStaUse?>"> 
                <?php endif; ?>
                    <?= language('promotion/voucher/vouchertype','tVOTTBUsing')?>
                </div>
            </div>
        </div>
    </div>
</form>
<?php include "script/jVouchertypeAdd.php"; ?>
<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<!-- <script type="text/javascript">
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

// //Lang Edit In Browse
// var nLangEdits = <?=$this->session->userdata("tLangEdit")?>;
// //Set Option Browse -----------
// //Option Depart
// var oVocBrowseVot = {
//     Title : ['promotion/voucher/vouchertype','tVOTTitle'],
//     Table:{Master:'TFNMVoucherType',PK:'FTVotCode'},
//     Join :{
//         Table:	['TFNMVoucherType_L'],
//         On:['TFNMVoucherType_L.FTVotCode = TFNMVoucherType.FTVotCode AND TFNMVoucherType_L.FNLngID = '+nLangEdits,]
//     },
//     GrideView:{
//         ColumnPathLang	: 'promotion/voucher/vouchertype',
//         ColumnKeyLang	: ['tVOTTBCode','tVOTTBName'],
//         DataColumns		: ['TFNMVoucherType.FTVotCode','TFNMVoucherType_L.FTVotName'],
//         ColumnsSize     : ['20%','80%'],
//         DataColumnsFormat : ['',''],
//         WidthModal      : 50,
//         Perpage			: 10,
// 		OrderBy			: ['TFNMVoucherType.FTVotCode'],
// 		SourceOrder		: "ASC"
//     },
//     CallBack:{
//         ReturnType	: 'S',
//         Value		: ["ohdVotCode","TFNMVoucherType.FTVotCode"],
// 		Text		: ["oetVotName","TFNMVoucherType_L.FTVotName"],
//     },
//     BrowseLev : 1
// }
// //Event Browse
// $('#obtVocBrowseVot').click(function(){JCNxBrowseData('oVocBrowseVot');});
</script> -->