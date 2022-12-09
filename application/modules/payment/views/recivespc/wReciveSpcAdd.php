<?php
    $tRcvSpcCode    = $aRcvSpcCode['tRcvSpcCode'];
 
    // echo '<pre>';
    // print_r($aResult);
    // echo '</pre>';
    // ตรวจสอบโหมดการทำงานว่าเป็น โหมดเพิ่มใหม่ หรือแก้ไข
    if($aResult['rtCode'] == 1){
        $tRcvSpcAppCode         = $aResult['raItems']['FTAppCode'];
        $tRcvSpcAppName         = $aResult['raItems']['FTAppName'];
        $tRcvSpcBchCode         = $aResult['raItems']['FTBchCode'];
        $tRcvSpcBchName         = $aResult['raItems']['FTBchName'];
        $tRcvSpcMerCode         = $aResult['raItems']['FTMerCode'];
        $tRcvSpcMerName         = $aResult['raItems']['FTMerName'];
        $tRcvSpcShpCode         = $aResult['raItems']['FTShpCode'];
        $tRcvSpcShpName         = $aResult['raItems']['FTShpName'];
        $tRcvSpcAggCode         = $aResult['raItems']['FTAggCode'];
        $tRcvSpcAggName         = $aResult['raItems']['FTAggName'];
        $tRemark                = $aResult['raItems']['FTPdtRmk'];
        $tRcvSpcStaAlwRet       = $aResult['raItems']['FTAppStaAlwRet'];
        $tRcvSpcStaAlwCancel    = $aResult['raItems']['FTAppStaAlwCancel'];
        $tRcvSpcStaPayLast      = $aResult['raItems']['FTAppStaPayLast'];
        $tRcvSpcRcvSeq          = $aResult['raItems']['FNRcvSeq'];
        //route for edit
        $tRoute         	= "recivespcEventEdit";
    }else{
        $tRcvSpcAppCode         = "";
        $tRcvSpcAppName         = "";
        $tRcvSpcBchCode         = $this->session->userdata('tSesUsrBchCode');
        $tRcvSpcBchName         = $this->session->userdata('tSesUsrBchName');
        $tRcvSpcMerCode         = "";
        $tRcvSpcMerName         = "";
        $tRcvSpcShpCode         = "";
        $tRcvSpcShpName         = "";
        $tRcvSpcAggCode         = "";
        $tRcvSpcAggName         = "";
        $tRemark                = "";
        $tRcvSpcStaAlwRet       = "1";
        $tRcvSpcStaAlwCancel    = "1";
        $tRcvSpcStaPayLast      = "1";
        $tRcvSpcRcvSeq          = "";
        //route for add
        $tRoute             = "recivespcEventAdd";
    }
?>

<form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddEditRcvSpc">
    <input type="hidden" id="ohdTRoute" name="ohdTRoute" value="<?php echo @$tRoute; ?>">
    <input type="hidden" id="ohdRcvSpcCode" name="ohdRcvSpcCode" value="<?php echo @$tRcvSpcCode?>">

    <input type="hidden" id="ohdRcvtSesUsrBchCode" name="tSesUsrBchCode" value="<?php echo $this->session->userdata('tSesUsrBchCode')?>">
    <input type="hidden" id="ohdRcvSpcRcvSeq" name="ohdRcvSpcRcvSeq" value="<?php echo @$tRcvSpcRcvSeq?>">
    <input type="hidden" id="ohdValidateDuplicate" name="ohdValidateDuplicate" value="0">

    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <label class="xCNLabelFrm" style="color: #179bfd !important; cursor:pointer;" onclick="JSxRcvSpcGetContent();" ><?php echo language('payment/recivespc/recivespc','tDetailManagepayment')?></label>
            <label class="xCNLabelFrm">
            <label class="xCNLabelFrm xWPageAdd" style="color: #aba9a9 !important;"> / <?php echo language('payment/recivespc/recivespc','tRcvSpcAdd')?> </label> 
            <label class="xCNLabelFrm xWPageEdit hidden" style="color: #aba9a9 !important;"> / <?php echo language('payment/recivespc/recivespc','tRcvSpcEdit')?> </label>   
        </div>

        <!--ปุ่มเพิ่ม-->
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6  text-right">
            <button type="button" onclick="JSxRcvSpcGetContent();" class="btn" style="background-color: #D4D4D4; color: #000000;">
                <?php echo language('common/main/main', 'tCancel')?>
            </button>
                    <button type="submit" class="btn" style="background-color: rgb(23, 155, 253); color: white;" id="obtCrdloginSave" onclick="JSxRcvSpvSaveAddEdit('<?=$tRoute?>')"> <?php echo  language('common/main/main', 'tSave')?></button>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><hr></div>
        <div class="row"> 
            <div class="col-xl-6 col-sm-6 col-md-6 col-lg-6">
                <!-- ระบบบัตร -->
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('payment/recivespc/recivespc','tRCVSpcBrwApp');?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide" id="oetRcvSpcAppCode" name="oetRcvSpcAppCode" value="<?php echo @$tRcvSpcAppCode;?>">
                        <input type="text" 
                                class="form-control xWPointerEventNone" 
                                id="oetRcvSpcAppName" 
                                name="oetRcvSpcAppName" 
                                placeholder="<?php echo language('payment/recivespc/recivespc','tRcvSpcholdersystem');?>" 
                                value="<?php echo @$tRcvSpcAppName;?>"  data-validate="<?php echo  language('payment/recivespc/recivespc','tRCVSPCValidName');?>" readonly>
                        <span class="input-group-btn">
                            <button id="oimRcvSpcBrowseApp" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="ohdRcvSpc" >
        <div class="row"> 
            <div class="col-xl-6 col-sm-6 col-md-6 col-lg-6">
                <!-- สาขา -->
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('payment/recivespc/recivespc','tRCVSpcBrwBch');?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide" id="oetRcvSpcBchCode" name="oetRcvSpcBchCode" value="<?php echo @$tRcvSpcBchCode;?>">
                        <input type="text" 
                            class="form-control xWPointerEventNone" 
                            id="oetRcvSpcBchName" 
                            name="oetRcvSpcBchName" 
                            placeholder="<?php echo language('payment/recivespc/recivespc','tRcvSpcholdeBch');?>" 
                            value="<?php echo @$tRcvSpcBchName;?>" data-validate="<?php echo  language('payment/recivespc/recivespc','tRCVSPCValidBchName');?>" readonly>
                        <span class="input-group-btn">
                            <button id="oimRcvSpcBrowseBch" type="button" class="btn xCNBtnBrowseAddOn"  <?php if(!empty($this->session->userdata('tSesUsrBchCode'))){ echo 'disabled'; } ?>><img class="xCNIconFind"></button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="ohdRcvSpcBch">
        <div class="row"> 
            <div class="col-xl-6 col-sm-6 col-md-6 col-lg-6">
                <!-- กลุ่มธุรกิจ -->
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('payment/recivespc/recivespc','tRCVSpcBrwMer');?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide" id="oetRcvSpcMerCode" name="oetRcvSpcMerCode" value="<?php echo @$tRcvSpcMerCode;?>">
                        <input type="text" 
                            class="form-control xWPointerEventNone" 
                            id="oetRcvSpcMerName" 
                            name="oetRcvSpcMerName"
                            placeholder="<?php echo language('payment/recivespc/recivespc','tRcvSpcholdeMer');?>"
                            value="<?php echo @$tRcvSpcMerName;?>" readonly>
                        <span class="input-group-btn">
                            <button id="oimRcvSpcBrowseMer" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="ohdRcvSpcMer">
        <!-- <div class="row"> 
            <div class="col-xl-6 col-sm-6 col-md-6 col-lg-6"> -->
                <!-- ร้านค้า -->
                <!-- <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('payment/recivespc/recivespc','tRCVSpcBrwShp');?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide" id="oetRcvSpcShpCode" name="oetRcvSpcShpCode" value="<?php echo @$tRcvSpcShpCode;?>">
                        <input type="text"
                            class="form-control xWPointerEventNone" 
                            id="oetRcvSpcShpName" 
                            name="oetRcvSpcShpName" 
                            placeholder="<?php echo language('payment/recivespc/recivespc','tRcvSpcholdeShp');?>"
                            value="<?php echo @$tRcvSpcShpName;?>" data-validate="<?php echo  language('payment/recivespc/recivespc','tRCVSPCValidShpName');?>" readonly>
                        <span class="input-group-btn">
                            <button id="oimRcvSpcBrowseShp" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                        </span>
                    </div>
                </div>
            </div>
        </div> -->
        <input type="hidden" id="ohdRcvSpcShp">
        <div class="row"> 
            <div class="col-xl-6 col-sm-6 col-md-6 col-lg-6">
                <!-- กลุ่มตัวแทน -->
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('payment/recivespc/recivespc','tRCVSpcBrwAgg');?></label>
                    <div class="input-group">
                        <input type="text" class="form-control xCNHide" id="oetRcvSpcAggCode" name="oetRcvSpcAggCode" value="<?php echo @$tRcvSpcAggCode;?>">
                        <input type="text" 
                            class="form-control xWPointerEventNone" 
                            id="oetRcvSpcAggName" 
                            name="oetRcvSpcAggName" 
                            placeholder="<?php echo language('payment/recivespc/recivespc','tRcvSpcholdeAgg');?>"
                            value="<?php echo @$tRcvSpcAggName;?>" readonly>
                        <span class="input-group-btn">
                            <button id="oimRcvSpcBrowseAgg" type="button" class="btn xCNBtnBrowseAddOn"><img class="xCNIconFind"></button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- หมายเหตุ -->
        <div class="row">
            <div class="col-xl-6 col-sm-6 col-md-6 col-lg-6">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?= language('payment/cardlogin/cardlogin','tCrdLRemark'); ?></label>
                    <textarea class="form-group" rows="4" maxlength="100" id="oetRcvSpcRemark" name="oetRcvSpcRemark" autocomplete="off"   placeholder="<?php echo language('payment/cardlogin/cardlogin','tCrdLRemark')?>"><?php echo @$tRemark;?></textarea>
                </div>
            </div>
        </div>

        <!-- Status Recive Spc -->
        <div class="row">
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <div class="form-group">
                    <label class="fancy-checkbox">
                        <?php
                            if(isset($tRcvSpcStaAlwRet) && $tRcvSpcStaAlwRet == 1){
                                $tCheckedStaAlwRet  = 'checked';
                            }else{
                                $tCheckedStaAlwRet  = '';
                            }
                        ?>
                        <input type="checkbox" id="ocbRcvSpcStaAlwRet" name="ocbRcvSpcStaAlwRet" <?php echo $tCheckedStaAlwRet;?>>
                        <span> <?php echo language('payment/recivespc/recivespc','tRcvSpcStaAlwRet');?></span>
                    </label>
                </div>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <div class="form-group">
                    <label class="fancy-checkbox">
                        <?php
                            if(isset($tRcvSpcStaAlwCancel) && $tRcvSpcStaAlwCancel == 1){
                                $tCheckedStaAlwCancel   = 'checked';
                            }else{
                                $tCheckedStaAlwCancel   = '';
                            }
                        ?>
                        <input type="checkbox" id="ocbRcvSpcStaAlwCancel" name="ocbRcvSpcStaAlwCancel" <?php echo $tCheckedStaAlwCancel;?>>
                        <span> <?php echo language('payment/recivespc/recivespc','tRcvSpcStaAlwCancel');?></span>
                    </label>
                </div>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-3 col-lg-3">
                <div class="form-group">
                    <label class="fancy-checkbox">
                        <?php
                            if(isset($tRcvSpcStaPayLast) && $tRcvSpcStaPayLast == 1){
                                $tCheckedStaPayLast = 'checked';
                            }else{
                                $tCheckedStaPayLast = '';
                            }
                        ?>
                        <input type="checkbox" id="ocbRcvSpcStaPayLast" name="ocbRcvSpcStaPayLast" <?php echo $tCheckedStaPayLast;?>>
                        <span> <?php echo language('payment/recivespc/recivespc','tRcvSpcStaPayLast');?></span>
                    </label>
                </div>
            </div>
        </div>

        
</form>
<?php include "script/jReciveSpcMain.php"; ?>
<link rel="stylesheet" href="<?php echo base_url('application/modules/common/assets/css/localcss/ada.component.css')?>">
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>