<?php
    if(isset($nStaAddOrEdit) && $nStaAddOrEdit == 1){
        $tRoute     = "pdttouchgrpEventEdit";
        $tTcgCode   = $aTcgData['raItems']['rtTcgCode'];
        $tTcgStaUse = $aTcgData['raItems']['rtTcgStaUse'];
        $tTcgName   = $aTcgData['raItems']['rtTcgName'];
        $tTcgRmk    = $aTcgData['raItems']['rtTcgRmk'];
    }else{
        $tRoute     = "pdttouchgrpEventAdd";
        $tTcgCode   = "";
        $tTcgStaUse = "";
        $tTcgName   = "";
        $tTcgRmk    = "";
    }
?>
<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddPdtTouchGrp">
    <button style="display:none" type="submit" id="obtSubmitPdtTouchGrp" onclick="JSoAddEditPdtTouchGrp('<?= $tRoute?>')"></button>
    <input type="hidden" id="ohdTcgRoute" value="<?php echo $tRoute; ?>">
    <div class="panel panel-headline">
        <div class="panel-body" style="padding-top:20px !important;">
            <div class="row">
                <div class="col-xs-12 col-md-5 col-lg-5">
                <div class="form-group">
                        <input type="hidden" value="0" id="ohdCheckTcgClearValidate" name="ohdCheckTcgClearValidate"> 
                        <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('product/pdttouchgroup/pdttouchgroup','tTCGTBCode')?></label> <!-- เปลี่ยนชื่อ Class -->
                        <?php
                        if($tRoute=="pdttouchgrpEventAdd"){
                        ?>
                        <div class="form-group" id="odvPgpAutoGenCode">
                            <div class="validate-input">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" id="ocbTcgAutoGenCode" name="ocbTcgAutoGenCode" checked="true" value="1">
                                    <span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group" id="odvPunCodeForm">
                            <input 
                                type="text" 
                                class="form-control xCNInputWithoutSpcNotThai" 
                                maxlength="5" 
                                id="oetTcgCode" 
                                name="oetTcgCode"
                                data-is-created="<?php  ?>"
                                placeholder="<?php echo language('product/pdttouchgroup/pdttouchgroup','tTCGTBCode')?>"
                                value="<?php  ?>" 
                                data-validate-required="<?php echo language('product/pdttouchgroup/pdttouchgroup','tTCGValidCode')?>"
                                data-validate-dublicateCode="<?php echo language('product/pdttouchgroup/pdttouchgroup','tTCGValidDublicateCode')?>"
                                readonly
                                onfocus="this.blur()">
                            <input type="hidden" value="2" id="ohdCheckDuplicateTcgCode" name="ohdCheckDuplicateTcgCode"> 
                        </div>
                        <?php
                        }else{
                        ?>
                        <div class="form-group" id="odvPunCodeForm">
                            <div class="validate-input">
                                <label class="fancy-checkbox">
                                <input 
                                    type="text" 
                                    class="form-control xCNInputWithoutSpcNotThai" 
                                    maxlength="5" 
                                    id="oetTcgCode" 
                                    name="oetTcgCode"
                                    data-is-created="<?php  ?>"
                                    placeholder="<?php echo language('product/pdttouchgroup/pdttouchgroup','tTCGTBCode')?>"
                                    value="<?php echo $tTcgCode; ?>" 
                                    readonly
                                    onfocus="this.blur()">
                                </label>
                            </div>
                        <?php
                        }
                        ?>











                    </div>


                    <div class="form-group">
                        <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('product/pdttouchgroup/pdttouchgroup','tTCGFrmTcgName')?></label> <!-- เปลี่ยนชื่อ Class -->
                        <input type="text" class="form-control xCNInputWithoutSpc" maxlength="100" id="oetTcgName" name="oetTcgName" value="<?=$tTcgName?>" data-validate-required="<?= language('product/pdttouchgroup/pdttouchgroup','tTCGValidName')?>">
                    </div>
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?=language('product/pdttouchgroup/pdttouchgroup','tTCGFrmTcgStaUse')?></label>
                        <select class="selectpicker form-control" id="ocmTcgStaUse" name="ocmTcgStaUse">
                            <option value="1" <?= (isset($tTcgStaUse) && !empty($tTcgStaUse) && $tTcgStaUse == '1')? "selected":""?>>
                                <?=language('product/pdttouchgroup/pdttouchgroup','tTCGFrmUse')?>
                            </option>
                            <option value="2" <?= (isset($tTcgStaUse) && !empty($tTcgStaUse) && $tTcgStaUse == '2')? "selected":""?>>
                                <?=language('product/pdttouchgroup/pdttouchgroup','tTCGFrmNotUse')?>
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('product/pdttouchgroup/pdttouchgroup','tTCGFrmTcgRmk')?></label>
                            <textarea class="form-control xCNInputWithoutSpc" maxlength="100" rows="4" id="otaTcgRmk" name="otaTcgRmk"><?=$tTcgRmk?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="dropDownSelect1"></div>
</form>
<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<script>
    $('#ocmTcgStaUse').selectpicker();
</script>
