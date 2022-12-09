<?php
if($aResult['rtCode'] == "1"){
    $tCstLevCode       = $aResult['raItems']['rtCstLevCode'];
    $tCstLevName       = $aResult['raItems']['rtCstLevName'];
    $tCstLevRmk        = $aResult['raItems']['rtCstLevRmk'];
    $tRoute            = "customerLevelEventEdit";
    $tCstLevAlwPnt     = $aResult['raItems']['rtCClvAlwPnt'];
    $tCstLevCalAmt     = $aResult['raItems']['rtCClvCalAmt'];
    $tCstLevCalPnt     = $aResult['raItems']['rtCClvCalPnt'];
    $tCstLevClvCode    = $aResult['raItems']['rtCClvCode'];
    $tCstLevPplName    = $aResult['raItems']['rtPplName'];
}else{
    $tCstLevAlwPnt     = "2";
    $tCstLevCalAmt     = "";
    $tCstLevCalPnt     = "";
    $tCstLevClvCode    = "";

    $tCstLevCode       = "";
    $tCstLevName       = "";
    $tCstLevRmk        = "";
    $tCstLevClvCode    = "";
    $tCstLevPplName    = "" ;
    $tRoute            = "customerLevelEventAdd";
    $tCstLevPplName    = "";
}
   $tAdd   = "customerLevelEventEdit";;
?>
<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddCstLev">
    <button style="display:none" type="submit" id="obtSubmitCstLev" onclick="JSnAddEditCstLev('<?= $tRoute?>')"></button>
    <div class="panel-body" style="padding-top:20px !important;">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                <label class="xCNLabelFrm"><span class="text-danger">*</span> <?php echo language('customer/customerLevel/customerLevel','tCstLevCode'); ?><?= language('customer/customerLevel/customerLevel','tCstLevTitle')?></label>
                <div id="odvCstLevAutoGenCode" class="form-group">
                <div class="validate-input">
                <label class="fancy-checkbox">
                    <input type="checkbox" id="ocbCustomerLevelAutoGenCode" name="ocbCustomerLevelAutoGenCode" checked="true" value="1">
                    <span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
                </label>
            </div>
        </div>
            <div id="odvCstLevCodeForm" class="form-group">
                <input type="hidden" id="ohdCheckDuplicateCstLevCode" name="ohdCheckDuplicateCstLevCode" value="1">
                        <div class="validate-input">
                        <input
                        type="text"
                        class="form-control xCNGenarateCodeTextInputValidate"
                        maxlength="5"
                        id="oetCstLevCode"
                        name="oetCstLevCode"
                        data-is-created="<?php echo $tCstLevCode;?>"
                        placeholder="<?php echo language('customer/customerLevel/customerLevel','tCstLevTBCode'); ?>"
                        value="<?= $tCstLevCode; ?>"
                        data-validate-required = "<?= language('customer/customerLevel/customerLevel','tCstLevValidCode')?>"
                        data-validate-dublicateCode = "<?= language('customer/customerLevel/customerLevel','tCstLevValidCheckCode')?>"
                    >
                </div>
            </div>
        </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <div class="validate-input" data-validate="Please Insert Name">
                        <label class="xCNLabelFrm"><span class="text-danger">*</span> <?= language('customer/customerLevel/customerLevel','tCstLevName')?><?= language('customer/customerLevel/customerLevel','tCstLevCustomer')?></label>
                        <input type="text" class="input100" maxlength="100" id="oetCstLevName" name="oetCstLevName" value="<?= $tCstLevName ?>"
                        data-validate-required = "<?= language('customer/customerLevel/customerLevel','tCstLevvalidateName')?>">
                    </div>
                </div>

                <div class="form-group">
                <?php if ($tRoute == $tAdd) { ?>
                    <label class="xCNLabelFrm"><?php echo language('product/product/product','tCstLevRtePntQty');?></label>
                    <input type="text" class="form-control text-right xCNInputNumericWithDecimal" id="oetCstLevCalAmt" class="form-control" maxlength="100"  name="oetCstLevCalAmt" disabled value="<?php echo $tCstLevCalAmt;?>">
                </div>

                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('product/product/product','tCstLevRtePntAmt');?></label>
                    <input type="text" class="form-control text-right xCNInputNumericWithDecimal" id="$tCstLevCalPnt" class="form-control" maxlength="100"  name="$tCstLevCalPnt" disabled value="<?php echo $tCstLevCalPnt;?>">
                    <?php } ?>    
                    
                </div>
                           

               
                <div class="form-group" >
                    <label class="xCNLabelFrm"><?php echo language('customer/customer/customer','tCSTPplRet');?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide" id="oetCstPplRetCode" name="oetCstPplRetCode" value="<?php echo @$tCstLevClvCode;?>">
                        <input type="text" class="form-control xWPointerEventNone" id="oetCstPplRetName" name="oetCstPplRetName" placeholder="<?php echo language('customer/customer/customer','tCSTPplRet');?>" value="<?php echo @$tCstLevPplName;?>" readonly>
                       
                        <span class="input-group-btn">
                            <button id="oimCstBrowsePpl" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                        </span>
                    </div>
                </div>
             

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                                <label class="xCNLabelFrm"><?= language('customer/customerLevel/customerLevel','tCstLevNote')?></label>
                                <textarea maxlength="100" rows="4" id="otaCstLevRemark" name="otaCstLevRemark"><?= $tCstLevRmk ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                <?php if ($tRoute == $tAdd) { ?>
                    <label class="fancy-checkbox">
                      <?php if ($tCstLevAlwPnt=="1") { ?>
                        <input type="checkbox" id="ocbCustomerLevelAppr" name="ocbCustomerLevelAppr"   checked disabled>
                      <?php }else { ?>
                        <input type="checkbox" id="ocbCustomerLevelAppr" name="ocbCustomerLevelAppr"   disabled >
                      <?php } ?>
              
                        <span> <?= language('customer/customerLevel/customerLevel','tCstLevAlw')?></span>
                
                    </label>
                <?php } ?>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js');?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js');?>"></script>
<?php include "script/jCustomerLevelAdd.php";?>
<script type="text/javascript">
    $('.xWTooltipsBT').tooltip({'placement': 'bottom'});
    $('[data-toggle="tooltip"]').tooltip({'placement': 'top'});

    $('#oimCstLevBrowseProvince').click(function(){
        JCNxBrowseData('oPvnOption');
    });

    if(JCNCstLevIsUpdatePage()){
        $("#obtGenCodeCstLev").attr("disabled", true);
    }
    $("#ocbCustomerLevelAppr").click(function () {
      var tStatus = $(this).prop("checked");
      if (tStatus==true) {
        $("#ocbCustomerLevelAppr").val(1);
      }else {
        $("#oetCstLevAppr2").val(2);
      }
    })
    $('#oimCstBrowsePpl').click(function(){
      // Create By Witsarut 04/10/2019
        JSxCheckPinMenuClose();
      // Create By Witsarut 04/10/2019
      oOptionReturnPpl = oCstBrowsePpl();
      JCNxBrowseData('oOptionReturnPpl');
    });
    var oCstBrowsePpl = function(){
    	var tCondition = '';
    	var tStaUsrLevel = '<?= $this->session->userdata("tSesUsrLevel"); ?>';
    	var tAgnCode  = '<?= $this->session->userdata("tSesUsrAgnCode") ?>';
        var nLangEdits = <?=$this->session->userdata("tLangEdit")?>;
    	var oCstBrowsePplReturn = {
    		Title : ['product/pdtpricelist/pdtpricelist', 'tPPLTitle'],
    		Table:{Master:'TCNMPdtPriList', PK:'FTPplCode'},
    		Join :{
    			Table: ['TCNMPdtPriList_L'],
    			On: ['TCNMPdtPriList_L.FTPplCode = TCNMPdtPriList.FTPplCode AND TCNMPdtPriList_L.FNLngID = '+nLangEdits]
    		},
    		Where :{
    			Condition : [tCondition],
    		},
    		GrideView:{
    			ColumnPathLang	: 'product/pdtpricelist/pdtpricelist',
    			ColumnKeyLang	: ['tPPLTBCode', 'tPPLTBName'],
    			ColumnsSize     : ['15%', '85%'],
    			WidthModal      : 50,
    			DataColumns		: ['TCNMPdtPriList.FTPplCode', 'TCNMPdtPriList_L.FTPplName'],
    			DataColumnsFormat : ['', ''],
    			Perpage			: 10,
    			OrderBy			: ['TCNMPdtPriList.FDCreateOn DESC'],
    		},
    		CallBack:{
    			ReturnType	: 'S',
    			Value		: ["oetCstPplRetCode", "TCNMPdtPriList.FTPplCode"],
    			Text		: ["oetCstPplRetName", "TCNMPdtPriList.FTPplName"]
    		},
    		RouteAddNew : 'pdtpricegroup'
    		//BrowseLev : nStaCstBrowseType
    	}
    	return oCstBrowsePplReturn;
    }
    
</script>
