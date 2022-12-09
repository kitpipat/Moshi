<style>

.xCNComboSelect{
    height: 33px !important;
}

.filter-option-inner-inner{
    margin-top : 0px;
}

.dropdown-toggle{
    height: 33px !important;
}

</style>

<div class="row">
    <div class="col-xs-8 col-md-4 col-lg-4">
        <div class="form-group">
            <div class="row">
                <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                    <div class="form-group">
                        <label class="xCNLabelFrm"><?=language('common/main/main','tSearchNew')?></label>
                        <select class="selectpicker form-control xCNComboSelect" id="ocmAppType" style="height:33px !important;">
                            <option value="0"><?=language('settingconfig/settingconfig/settingconfig','tOptionAllGroup')?></option>
                            <?php foreach($aOption['raItems'] AS $key=>$aValue){ ?>
                                <?php $tTextOption = 'tOption'.$aValue['FTSysApp']; ?>
                                <option value="<?=$aValue['FTSysApp'];?>"><?=language('settingconfig/settingconfig/settingconfig',$tTextOption)?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
                    <label class="xCNLabelFrm" style="color : #FFF !important;">.</label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchAll" name="oetSearchAll" onkeypress="Javascript:if(event.keyCode==13) JSvSettingConfigLoadTable()" autocomplete="off"  placeholder="<?=language('common/main/main','tPlaceholder'); ?>">
                        <span class="input-group-btn">
                            <button class="btn xCNBtnSearch" type="button" onclick="JSvSettingConfigLoadTable()">
                                <img class="xCNIconAddOn" src="<?=base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-4 col-md-8 col-lg-8 text-right" style="margin-top:25px;">
            <div id="odvBtnAddEdit" style="display: block;">
                <button onclick="JSxSETReDefault()"  class="btn xCNBTNDefult xCNBTNDefult2Btn" style="margin-left: 5px;" type="button"><?=language('settingconfig/settingconfig/settingconfig', 'tBTNOriginal'); ?></button>
                <button onclick="JSxSETCancel()" class="btn xCNBTNDefult xCNBTNDefult2Btn" style="margin-left: 5px;" type="button"><?=language('common/main/main', 'tCancel'); ?></button>
                <div class="btn-group">
                    <button onclick="JSxSETSave()" type="button" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" style="margin-left: 5px;" style="display: block;"><?=language('common/main/main', 'tSave'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="odvContentConfigTable"></div>

<!--MODAL กดยกเลิก-->
<div class="modal fade" id="odvModalSETCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('common/main/main', 'tCancel'); ?></h5>
            </div>
            <div class="modal-body">
                <p><?=language('settingconfig/settingconfig/settingconfig', 'tTextModalCancel'); ?></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxSETModalCancel()" type="button" class="btn xCNBTNPrimery">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!--MODAL กดใช้แม่แบบ-->
<div class="modal fade" id="odvModalSETDefault">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?=language('settingconfig/settingconfig/settingconfig', 'tTextModalSetDefaultHead'); ?></h5>
            </div>
            <div class="modal-body">
                <p><?=language('settingconfig/settingconfig/settingconfig', 'tTextModalSetDefault'); ?></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSxSETModalDefault()" type="button" class="btn xCNBTNPrimery">
                    <?=language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?=language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    //ใช้ selectpicker
    $('.selectpicker').selectpicker();	

    //LoadTable
    JSvSettingConfigLoadTable();

    //ทุกครั้งที่เปลี่ยน Type
    $('#ocmAppType').change(function() {
        JSvSettingConfigLoadTable();
    });
</script>