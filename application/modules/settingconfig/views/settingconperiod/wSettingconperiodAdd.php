<?php 
    // ตรวจสอบโหมดการทำงานว่าเป็น โหมดเพิ่มใหม่ หรือแก้ไข
    if(isset($nStaAddOrEdit) && $nStaAddOrEdit == 1){

        $tRoute        = "settingconperiodEventEdit";
        $tLhdCode      = $aLimDataEdit['raItems']['rtLhdCode'];
        $tLhdName      = $aLimDataEdit['raItems']['rtLhdName'];

        $tGrpRolCode   = $aLimDataEdit['raItems']['rtRolCode'];
        $tGrpRolName   = $aLimDataEdit['raItems']['rtRolName'];
        $nCheckEdit = 1;


    }else{

        $tLhdCode       = "";
        $tLhdName       = "";
        $tGrpRolCode    = "";
        $tGrpRolName    = "";
        $nCheckEdit = "";

        $tRoute     = "settingconperiodEventAdd";
    }

?>


<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddSetingConperiod">
    <button style="display:none" type="submit" id="obtSubmitSettingConPeriod" onclick="JSoAddEditSettingConperiod('<?php echo $tRoute?>')"></button>
    
    <div class="panel-body" style="padding-top:20px !important;">
        <div class="row">
            <!-- เงื่อนไข -->
            <div class="col-xs-12 col-md-5 col-lg-5">
                <label class="xCNLabelFrm"><?php echo language('settingconfig/settingconperiod/settingconperiod','tLIMCondition')?></label> 
                <div class="form-group">
                    <div class="input-group">
                    <input  type="hidden" class="form-control xCNInputWithoutSpcNotThai" maxlength="5" id="oetLhdCode" name="oetLhdCode" value="<?php echo $tLhdCode?>">
                        <input type="text" class="form-control xWPointerEventNone" id="oetLhdName" name="oetLhdName" maxlength="100" value="<?php echo $tLhdName ?>"
                        data-validate-required="<?php echo language('settingconfig/settingconperiod/settingconperiod', 'tLIMValidCondition') ?>" readonly>
                        <span class="input-group-btn">
							<button id="oimBrowseLhd" type="button" class="btn xCNBtnBrowseAddOn">
								<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
							</button>
						</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-5">
                <label class="xCNLabelFrm"><?php echo language('settingconfig/settingconperiod/settingconperiod','tLIMRoleGroup')?></label> 
                <div class="form-group">
                    <div class="input-group">
                    <input type="text" class="input100 xCNHide" id="oetGrpRolCode" name="oetGrpRolCode" maxlength="5" value="<?php echo $tGrpRolCode; ?>">
                        <input type="text" class="form-control xWPointerEventNone" id="oetGrpRolName" name="oetGrpRolName" maxlength="100" value="<?php echo $tGrpRolName ?>"
                        data-validate-required="<?php echo language('settingconfig/settingconperiod/settingconperiod', 'tWAHValidRolGrp') ?>" readonly>
                        <span class="input-group-btn">
							<button id="oimBrowseRolGroup" type="button" class="btn xCNBtnBrowseAddOn">
								<img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
							</button>
						</span>
                    </div>
                </div>
            </div>
        </div>
        <div id="ostContentChkRole"></div>
    </div>
</form>

        


<?php include 'script/jSettingconperiod.php';?>

<script>
   JSxCheckConditionRolsBrows('tPageAddrole');
</script>

<script src="<?php echo base_url(); ?>application/modules/common/assets/js/jquery.mask.js"></script>
<script src="<?php echo base_url(); ?>application/modules/common/assets/src/jFormValidate.js"></script>
