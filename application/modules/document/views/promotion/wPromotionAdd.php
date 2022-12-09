<?php
if($aResult['rtCode'] == "1"){

	// print_r($aResult);

	$tPmhCode 			= $aResult['raItems']['rtPmhCode'];
	$tPmhName 			= $aResult['raItems']['rtPmhName'];
	$tPmhNameSlip 		= $aResult['raItems']['rtPmhNameSlip'];
	$tSpmCode 			= $aResult['raItems']['rtSpmCode'];
	$tSpmType 			= $aResult['raItems']['rtSpmType'];
	$dPmhDStart 		= $aResult['raItems']['rdPmhDStart'];
	$dPmhDStop 			= $aResult['raItems']['rdPmhDStop'];
	$dPmhTStart 		= $aResult['raItems']['rdPmhTStart'];
	$dPmhTStop 			= $aResult['raItems']['rdPmhTStop'];
	$tPmhClosed 		= $aResult['raItems']['rtPmhClosed'];
	$tPmhStatus 		= $aResult['raItems']['rtPmhStatus'];
	$tPmhRetOrWhs 		= $aResult['raItems']['rtPmhRetOrWhs'];
	$tPmhRmk 			= $aResult['raItems']['rtPmhRmk'];
	$tPmhStaPrcDoc 		= $aResult['raItems']['rtPmhStaPrcDoc'];
	$nPmhStaAct			= $aResult['raItems']['rnPmhStaAct'];
	$tUsrCode 			= $aResult['raItems']['rtUsrCode'];
	$tPmhApvCode 		= $aResult['raItems']['rtPmhApvCode'];
	$tPmhBchTo 			= $aResult['raItems']['rtPmhBchTo'];
	$tBchName 			= $aResult['raItems']['rtBchName'];
	$tPmhZneTo 			= $aResult['raItems']['rtPmhZneTo'];
	$tZneName 			= $aResult['raItems']['rtZneName'];
	$tPmhStaExceptPmt 	= $aResult['raItems']['rtPmhStaExceptPmt'];
	$tSpmStaRcvFree 	= $aResult['raItems']['rtSpmStaRcvFree'];
	$tSpmStaAlwOffline 	= $aResult['raItems']['rtSpmStaAlwOffline'];
	$tSpmStaChkLimitGet = $aResult['raItems']['rtSpmStaChkLimitGet'];
    $nPmhLimitNum		= $aResult['raItems']['rnPmhLimitNum'];               
	$tPmhStaLimit		= $aResult['raItems']['rtPmhStaLimit'];   
	$tPmhStaLimitCst	= $aResult['raItems']['rtPmhStaLimitCst'];   
	$tSpmStaChkCst		= $aResult['raItems']['rtSpmStaChkCst'];             
	$nPmhCstNum			= $aResult['raItems']['rnPmhCstNum'];
	$tSpmStaChkCstDOB	= $aResult['raItems']['rtSpmStaChkCstDOB']; 
	$nPmhCstDobNum		= $aResult['raItems']['rnPmhCstDobNum'];  
	$nPmhCstDobPrev		= $aResult['raItems']['rnPmhCstDobPrev'];  
	$tSpmStaUseRange	= $aResult['raItems']['rtSpmStaUseRange'];  
	$tSplCode			= $aResult['raItems']['rtSplCode'];  
	$tSplName			= $aResult['raItems']['rtSplName'];
	$nPmhCstDobNext		= $aResult['raItems']['rnPmhCstDobNext'];
	$dPntSplStart		= $aResult['raItems']['rdPntSplStart'];
	$dPntSplExpired		= $aResult['raItems']['rdPntSplExpired'];
	$tPmgCode			= $aResult['raItems']['rtPmgCode'];
	$tCgpName			= $aResult['raItems']['rtCgpName'];
	$tAggCode			= $aResult['raItems']['rtAggCode'];
	
	//Event Control
	if(isset($aAlwEventPromotion)){
		if($aAlwEventPromotion['tAutStaFull'] == 1 || $aAlwEventPromotion['tAutStaEdit'] == 1){
			$nAutStaEdit = 1;
		}else{
			$nAutStaEdit = 0;
		}
	}else{
		$nAutStaEdit = 0;
	}
	//Event Control

	$tRoute         	= "promotionEventEdit";
	
}else{

	$tPmhCode 		= '';
	$tPmhName		= '';
	$tPmhNameSlip	= '';
	$tSpmType 		= '';
	$dPmhDStart 	= '';
	$dPmhDStop 		= '';
	$dPmhTStart 	= '';
	$dPmhTStop 		= '';
	$tPmhClosed 	= '';
	$tPmhStatus 	= '';
	$tPmhRetOrWhs 	= '';
	$tPmhRmk 		= '';
	$tPmhStaPrcDoc 	= '';
	$nPmhStaAct		= '';
	$tUsrCode 		= $this->session->userdata('tSesUsername');
	$tPmhApvCode 	= '';
	$tPmhBchTo 			= '';
	$tBchName			= '';
	$tPmhZneTo 			= '';
	$tZneName			= '';
	$tPmhStaExceptPmt 	= '';
	$tSpmStaRcvFree 	= '';
	$tSpmStaAlwOffline 	= '';
	$tSpmStaChkLimitGet = '';
    $nPmhLimitNum		= '';             
	$tPmhStaLimit		= '';  
	$tPmhStaLimitCst	= '';   
	$tSpmStaChkCst		= '';         
	$nPmhCstNum			= '';
	$tSpmStaChkCstDOB	= '';
	$nPmhCstDobNum		= '';
	$nPmhCstDobPrev		= '';
	$tSpmStaUseRange	= '';
	$tSplCode			= '';
	$tSplName			= '';
	$nPmhCstDobNext		= '';
	$dPntSplStart		= '';
	$dPntSplExpired		= '';
	$tPmgCode			= '';
	$tCgpName			= '';
	$tAggCode			= '';
	$tAgnName			= '';


	$tRoute         	= "promotionEventAdd";

	$nAutStaEdit = 0; //Event Control
}
?>

<input type="hidden" id="ohdPmhAutStaEdit" value="<?=$nAutStaEdit?>">

<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddPromotion">
    <button style="display:none" type="submit" id="obtSubmitPromotion" onclick="JSnAddEditPromotion('<?= $tRoute?>')"></button>
    <input type="text" class="xCNHide" id="ohdSesUsrBchCode" value="<?= $this->session->userdata("tSesUsrBchCode"); ?>"> 

<div class="panel-body">
    <div class="row">
        <div class="xWLeftContainer col-xl-4 col-lg-4 col-md-4"> <!--col-md-5-->

            <div class="panel panel-default" style="margin-bottom: 30px;"> 
                <div id="odvHeadPromotion" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label><?= language('document/promotion/promotion', 'tPMTTitle') ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" data-parent="#odvSubGroupApp" href="#odvDataPromotion" aria-expanded="true" aria-controls="odvDataPromotion">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDataPromotion" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <div class="form-group" style="text-align: right;">
                            <label class="xCNTitleFrom xCNHide"><?= language('document/promotion/promotion', 'tPMTApproved') ?></label>
                        </div>

                        <div class="form-group">
                            <label class="xCNLabelFrm"><span class="text-danger">*</span> <?= language('document/promotion/promotion', 'tPMTPmhCode') ?></label>
                            <div class="input-group">
                                <input type="text" class="form-control xCNInputNumericWithoutDecimal" maxlength="10" id="oetPmhCode" name="oetPmhCode" placeholder="#####" value="<?= $tPmhCode ?>" data-validate="<?= language('document/promotion/promotion', 'tPMTValidCode') ?>">
                                <span class="input-group-btn">
                                    <button id="obtGenCodePmt" class="btn xCNBtnGenCode" type="button" onclick="JStGeneratePromotionCode()">
                                        <i class="fa fa-magic"></i>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTDepart') ?></label>
                            <input type="text" class="form-control" id="oetPmhDepartName" name="oetPmhDepartName" value="<?= $this->session->userdata("tSesUsrDptName"); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTUserSave') ?></label>
                            <input type="text" class="form-control" id="oetPmhUsrCode" name="oetPmhUsrCode" maxlength="100" value="<?= $tUsrCode ?>" readonly>
                        </div>

                    </div>
                </div>    
            </div>


            <div class="panel panel-default" style="margin-bottom: 30px;">
                <div id="odvHeadGeneralInfo" class="panel-heading xCNPanelHeadColor" role="tab">
                    <!-- <a class="xCNTextDetail1"><?= language('document/promotion/promotion', 'tPMTDataGeneralInfo') ?></a> -->
                    <label><?= language('document/promotion/promotion', 'tPMTDataGeneralInfo') ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" data-parent="#odvSubGroupApp" href="#odvDataGeneralInfo" aria-expanded="true" aria-controls="odvDataPromotion">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDataGeneralInfo" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTPmhName') ?></label>
                                <input type="text" class="form-control" id="oetPmhName" name="oetPmhName" maxlength="100" value="<?= $tPmhName ?>">
                            </div>

                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTPmhNameSlip') ?></label>
                                <input type="text" class="form-control" id="oetPmhNameSlip" name="oetPmhNameSlip" maxlength="100" value="<?= $tPmhNameSlip ?>">
                            </div>
                            <input type="hidden" class="input100 xCNInputWithoutSpc" id="oetSpmCode" name="oetSpmCode" value="<?= $tSpmCode ?>">
                            <input type="hidden" class="input100 xCNInputWithoutSpc" id="oetSpmType" name="oetSpmType" value="<?= $aSpmData[0]->FTSpmType ?>">

                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTTBPmtModel') ?></label>
                                <input type="text" class="form-control" id="oetSpmName" name="oetSpmName" maxlength="100" value="<?= $aSpmData[0]->FTSpmName ?>">
                            </div>

                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTPmhZne') ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide" id="oetPmhZneTo" name="oetPmhZneTo" value="<?= $tPmhZneTo ?>">
                                    <input type="text" class="form-control xCNInputWithoutSpcNotThai" id="oetPmhZneToName" name="oetPmhZneToName" value="<?= $tZneName ?>" readonly>
                                    <span class="input-group-btn">
                                        <button id="oimPmhBrowseZone" type="button" class="btn xCNBtnBrowseAddOn">
                                            <img src="<?php echo base_url() . '/application/assets/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTPmhRmk') ?></label>
                                <input type="text" class="form-control" id="oetPmhRmk" name="oetPmhRmk" maxlength="100" value="<?= $tPmhRmk ?>">
                            </div>

                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTPmhBch') ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide" id="oetPmhBchTo" name="oetPmhBchTo" value="<?= $tPmhBchTo ?>">
                                    <input type="text" class="form-control xCNInputWithoutSpcNotThai" id="oetPmhBchToName" name="oetPmhBchToName" value="<?= $tBchName ?>" readonly>
                                    <span class="input-group-btn">
                                        <button id="oimPmhBrowseBranch" type="button" class="btn xCNBtnBrowseAddOn">
                                            <img src="<?php echo base_url() . '/application/assets/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>    
            </div>

            <div class="panel panel-default" style="margin-bottom: 30px;">
                <div id="odvHeadDateTime" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label><?= language('document/promotion/promotion', 'tPMTDataDateTime') ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" data-parent="#odvSubGroupApp" href="#odvDataDateTime" aria-expanded="true" aria-controls="odvDataPromotion">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDataDateTime" class="panel-collapse collapse" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <div class="col-md-12 no-padding">
                            <div class="row">

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTPmhDStart') ?></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetPmhDStart" name="oetPmhDStart" autocomplete="off" value="<?= $dPmhDStart ?>">
                                            <span class="input-group-btn">
                                                <button id="obtPmhDStart" type="button" class="btn xCNBtnDateTime">
                                                    <img src="<?php echo base_url() . '/application/assets/icons/icons8-Calendar-100.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTPmhTStart') ?></label>
                                        <input type="text" class="form-control xCNTimePicker" id="oetPmhTStart" name="oetPmhTStart" value="<?= $dPmhTStart ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTPmhDStop') ?></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetPmhDStop" name="oetPmhDStop" autocomplete="off" value="<?= $dPmhDStop ?>">
                                            <span class="input-group-btn">
                                                <button id="obtPmhDStop" type="button" class="btn xCNBtnDateTime">
                                                    <img src="<?php echo base_url() . '/application/assets/icons/icons8-Calendar-100.png' ?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTPmhTStop') ?></label>
                                        <input type="text" class="form-control xCNTimePicker" id="oetPmhTStop" name="oetPmhTStop" value="<?= $dPmhTStop ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTPmhRetOrWhs') ?></label>
                                        <select class="selectpicker form-control" id="ostPmhRetOrWhs" name="ostPmhRetOrWhs" maxlength="1">
                                            <option value="1"><?= language('document/promotion/promotion', 'tPMTPmhRetail') ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>

            <div class="panel panel-default" style="margin-bottom: 30px;">
                <div id="odvHeadAllow" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label><?= language('document/promotion/promotion', 'tPMTAllow') ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" data-parent="#odvSubGroupApp" href="#odvDataAllow" aria-expanded="true" aria-controls="odvDataPromotion">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDataAllow" class="panel-collapse collapse" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <div class="col-md-12 no-padding">

                            <div class="form-group">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocbListItem" id="ocbPmhStaExceptPmt" name="ocbPmhStaExceptPmt" <?php echo $tPmhStaExceptPmt == 1 ? 'checked' : ''; ?> >
                                    <!-- <span><?= language('document/promotion/promotion', 'tPMTPmhStaExceptPmt') ?></span> -->
                                    <span> <?= language('document/promotion/promotion', 'tPMTPmhStaExceptPmt') ?></span>
                                    <!-- ยกเว้นสินค้าโปรโมชั่น -->
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocbListItem" id="ocbSpmStaRcvFree" name="ocbSpmStaRcvFree" <?php echo $tSpmStaRcvFree == 1 ? 'checked' : ''; ?>>
                                    <span> <?= language('document/promotion/promotion', 'tPMTSpmStaRcvFree') ?></span>
                                    <!-- รับของแถมที่จุดขาย -->
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocbListItem" id="ocbSpmStaAlwOffline" name="ocbSpmStaAlwOffline" <?php echo $tSpmStaAlwOffline == 1 ? 'checked' : ''; ?>>
                                    <span> <?= language('document/promotion/promotion', 'tPMTSpmStaAlwOffline') ?></span>
                                    <!-- อนุญาตใช้งานเมื่อ Offline -->
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" class="ocbListItem" id="ocbSpmStaChkLimitGet" name="ocbSpmStaChkLimitGet" <?php echo $tSpmStaChkLimitGet == 1 ? 'checked' : ''; ?>>
                                    <span> <?= language('document/promotion/promotion', 'tPMTSpmStaChkLimitGet') ?></span>
                                    <!-- ตรวจสอบจำนวนครั้ง -->
                                </label>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="number" class="form-control" id="oetPmhLimitNum" name="oetPmhLimitNum" placeholder="<?= language('document/promotion/promotion', 'tPMTNotMoreThan') ?>" value="<?= $nPmhLimitNum ?>">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">/</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="selectpicker form-control" id="oetPmhStaLimit" name="oetPmhStaLimit" maxlength="1">
                                            <option value="1" <?php echo $tPmhStaLimit == 1 ? 'selected' : ''; ?> ><?= language('document/promotion/promotion', 'tPMTPerDay') ?></option>
                                            <option value="2" <?php echo $tPmhStaLimit == 2 ? 'selected' : ''; ?> ><?= language('document/promotion/promotion', 'tPMTPerMonth') ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="selectpicker form-control" id="oetPmhStaLimitCst" name="oetPmhStaLimitCst" maxlength="1">
                                            <option value="1" <?php echo $tPmhStaLimitCst == 1 ? 'selected' : ''; ?>><?= language('document/promotion/promotion', 'tPMTAll') ?></option>
                                            <option value="2" <?php echo $tPmhStaLimitCst == 2 ? 'selected' : ''; ?>><?= language('document/promotion/promotion', 'tPMTMember') ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>    
            </div>

            <div class="panel panel-default" style="margin-bottom: 30px;">
                <div id="odvHeadCustomer" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label><?= language('document/promotion/promotion', 'tPMTMember') ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" data-parent="#odvSubGroupApp" href="#odvDataCustomer" aria-expanded="true" aria-controls="odvDataPromotion">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDataCustomer" class="panel-collapse collapse" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <div class="col-md-12">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" class="ocbListItem" id="ocbSpmStaChkCst" name="ocbSpmStaChkCst" <?php echo $tSpmStaChkCst == 1 ? 'checked' : ''; ?>>
                                            <span> <?= language('document/promotion/promotion', 'tPMTForMember') ?></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTNumOfTimesGivePoints') ?></label>
                                        <input type="number" class="form-control xCNInputWithoutSpc" id="oetPmhCstNum" name="oetPmhCstNum" value="<?= $nPmhCstNum ?>">
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="fancy-checkbox">
                                            <input type="checkbox" class="ocbListItem" id="ocbSpmStaChkCstDOB" name="ocbSpmStaChkCstDOB" <?php echo $tSpmStaChkCstDOB == 1 ? 'checked' : ''; ?>>
                                            <span> <?= language('document/promotion/promotion', 'tPMTForMembersMatchingBirthMonth') ?></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTNumOfTimesGivePoints') ?></label>
                                        <input type="number" class="form-control xCNInputWithoutSpc" id="oetPmhCstDobNum" name="oetPmhCstDobNum" value="<?= $nPmhCstDobNum ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTDobPrev') ?></label>
                                        <input type="number" class="form-control xCNInputWithoutSpc" id="oetPmhCstDobPrev" name="oetPmhCstDobPrev" value="<?= $nPmhCstDobPrev ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTDobNext') ?></label>
                                        <input type="number" class="form-control xCNInputWithoutSpc" id="oetPmhCstDobNext" name="oetPmhCstDobNext" value="<?= $nPmhCstDobNext ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTCstGrp') ?></label>
                                <input type="hidden" class="form-control" id="oetPmgCode" name="oetPmgCode" value="<?= $tPmgCode ?>">
                                <input type="text" class="form-control xWPointerEventNone" id="oetPmgCodeName" name="oetPmgCodeName" value="<?= $tCgpName ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTCstGrp') ?></label>
                                <div class="input-group">
                                    <input type="hidden" class="form-control" id="oetPmgCode" name="oetPmgCode" value="<?= $tPmgCode ?>">
                                    <input type="text" class="form-control xWPointerEventNone" id="oetPmgCodeName" name="oetPmgCodeName" value="<?= $tCgpName ?>" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtPmtBrowseCstGrp" type="button" class="btn xCNBtnBrowseAddOn">
                                            <img src="<?php echo base_url() . '/application/assets/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>    
            </div>

            <div class="panel panel-default" style="margin-bottom: 5px;">
                <div id="odvHeadSuplier" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label><?= language('document/promotion/promotion', 'tPMTSuplier') ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" data-parent="#odvSubGroupApp" href="#odvDataSuplier" aria-expanded="true" aria-controls="odvDataPromotion">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDataSuplier" class="panel-collapse collapse" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTSuplier') ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNHide" id="oetSplCode" name="oetSplCode" value="<?= $tSplCode ?>">
                                    <input type="text" class="form-control xCNInputWithoutSpcNotThai" id="oetSplCodeName" name="oetSplCodeName" value="<?= $tSplName ?>" readonly>
                                    <span class="input-group-btn">
                                        <button id="oimPmtBrowseSpl" type="button" class="btn xCNBtnBrowseAddOn">
                                            <img src="<?php echo base_url() . '/application/assets/icons/find-24.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTSplStart') ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetPntSplStart" name="oetPntSplStart" autocomplete="off" value="<?= $dPntSplStart ?>">
                                    <span class="input-group-btn">
                                        <button id="obtPntSplStart" type="button" class="btn xCNBtnDateTime xCNDatePicker">
                                            <img src="<?php echo base_url() . '/application/assets/icons/icons8-Calendar-100.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="xCNLabelFrm"><?= language('document/promotion/promotion', 'tPMTSplExpired') ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetPntSplExpired" name="oetPntSplExpired" autocomplete="off" value="<?= $dPntSplExpired ?>">
                                    <span class="input-group-btn">
                                        <button id="obtPntSplExpired" type="button" class="btn xCNBtnDateTime xCNDatePicker">
                                            <img src="<?php echo base_url() . '/application/assets/icons/icons8-Calendar-100.png' ?>">
                                        </button>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>    
            </div>

        </div>

        <div class="col-xl-8 col-lg-8 col-md-8" id="odvRightPanal"> <!--col-md-7-->

            <!-- เงื่อนไข -->
            <div class="panel panel-default" style="margin-bottom: 30px;">
                <input class="xCNHide" type="text" id="ohdSpmStaBuy" name="ohdSpmStaBuy" value="<?=$aSpmData[0]->FTSpmStaBuy?>">
                <?php 
                $tStaGrpCondAHave = '';
                $nStaControllCound = '';
                if (@is_array($aResultPmtCD['raItems']) == 1) {
                    foreach ($aResultPmtCD['raItems'] AS $key => $aValue) {
                        $tStaGrpCondAHave .= $aValue['FTPmcStaGrpCond'] . ",";

                        if ($aValue['FCPmcGetCond'] == '4') {
                            $nStaControllCound .= '4' . ",";
                        }
                    }
                }
                ?>
                <input class="xCNHide" type="text" id="ohdStaGrpCondHave" name="ohdStaGrpCondHave" value="<?=$tStaGrpCondAHave?>">

                <!-- START Header เงื่อนไข -->
                <div id="odvHeadCondition" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" data-parent="#odvSubGroupApp" href="#odvDataStaGrpCondition" aria-expanded="true" aria-controls="odvDataPromotion"><i class="fa fa-plus xCNPlus"></i></a>
                    <div id="odvHeadBarShowPanalGrpCondition">
                        <label><?= language('document/promotion/promotion', 'tPMTGrpCondition') ?></label>
                        <input type="text" class="xCNHide" id="ohdPmdGrpConditionNameCurrent" name="ohdPmdGrpConditionNameCurrent" value="<?= language('document/promotion/promotion', 'tPMTGrpCondition') ?>">
                    </div>
                    <div id="odvHeadBarEditPanalGrpCondition" class="xCNHide">
                        <div class="wrap-input100 validate-input xCNOdvEditRow" data-validate="Please Enter">
                            <input type="text" class="input100" id="ohdPmdGrpConditionName" name="ohdPmdGrpConditionName" data-name="GrpCondition" value="<?= language('document/promotion/promotion', 'tPMTGrpCondition') ?>">
                            <span class="focus-input100"></span>
                        </div>
                    </div>
                </div>
                <!-- END Header เงื่อนไข -->

                <!-- START Content เงื่อนไข -->
                <div id="odvDataStaGrpCondition" class="panel-body collapse in" role="tabpanel" data-grpname="Condition">
                    <div class="col-md-9 no-padding"></div>
                    <div class="col-md-3 no-padding" style="margin-bottom:10px;">
                        <button class="xCNBTNPrimeryPlus pull-right" onclick="JSxOpenMDConditoin();" type="button">+</button>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="otbGrpCondition" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr class="xCNCenter">
                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBNo') ?></th>
                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBGrp') ?></th>
                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBBuyAndRcv') ?></th>
                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBAvg') ?></th>
                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBBuyAmt') ?></th>
                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBBuyQty') ?></th>
                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBMinAmt') ?></th>
                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBMaxAmt') ?></th>
                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBValue') ?></th>
                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBQty') ?></th>
                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBDelete') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="odvCondition">
                                        <?php if (@is_array($aResultPmtCD['raItems']) > 0): ?>
                                            <?php foreach ($aResultPmtCD['raItems'] AS $key => $aValue) { ?>
                                                <tr class="text-center xCNTextDetail2 xWCondition" id="otrCondition<?= $key + 1 ?>" data-grpcound="<?= $aValue['FTPmcStaGrpCond'] ?>" data-getcound="<?= $aValue['FCPmcGetCond'] ?>" data-name="<?= $aValue['FTPmcGrpName'] ?>">
                                                    <td	class="xCNHide">
                                                        <input class="xCNHide xWValHiden<?= $aValue['FTPmcGrpCode'] ?>" name="ohdCondition[]" value="<?= $key + 1 ?>,<?= $aValue['FTPmcGrpName'] ?>,<?= $aValue['FTPmcStaGrpCond'] ?>,<?= $aValue['FCPmcPerAvgDis'] ?>,<?= $aValue['FCPmcGetValue'] ?>,<?= $aValue['FCPmcGetQty'] ?>,<?= $aValue['FCPmcGetCond'] ?>,<?= $aValue['FCPmcBuyAmt'] ?>,<?= $aValue['FCPmcBuyQty'] ?>,<?= $aValue['FCPmcBuyMinQty'] ?>,<?= $aValue['FCPmcBuyMaxQty'] ?>,<?= $aValue['FTPmcGrpCode'] ?>">
                                                    </td>
                                                    <td><?= $key + 1 ?></td>	
                                                    <td class="text-left xWPut<?= $aValue['FTPmcGrpCode'] ?>"><?= $aValue['FTPmcGrpName'] ?></td>		
                                                    <td><?= language('document/promotion/promotion', 'tPMTStaGrpCond-' . $aValue['FTPmcStaGrpCond']) ?></td>
                                                    <td class="text-right"><?= $aValue['FCPmcPerAvgDis'] != '.0000' ? number_format($aValue['FCPmcPerAvgDis'], 2, '.', ' ') : '-'; ?></td>	
                                                    <td class="text-right"><?= $aValue['FCPmcBuyAmt'] != '.0000' ? number_format($aValue['FCPmcBuyAmt'], 2, '.', ' ') : '-'; ?></td>	
                                                    <td class="text-right"><?= $aValue['FCPmcBuyQty'] != '.0000' ? number_format($aValue['FCPmcBuyQty'], 2, '.', ' ') : '-'; ?></td>	
                                                    <td class="text-right"><?= $aValue['FCPmcBuyMinQty'] != '.0000' ? number_format($aValue['FCPmcBuyMinQty'], 2, '.', ' ') : '-'; ?></td>
                                                    <td class="text-right"><?= $aValue['FCPmcBuyMaxQty'] != '.0000' ? number_format($aValue['FCPmcBuyMaxQty'], 2, '.', ' ') : '-'; ?></td>
                                                    <td class="text-right"><?= $aValue['FCPmcGetValue'] != '.0000' ? number_format($aValue['FCPmcGetValue'], 2, '.', ' ') : '-'; ?></td>
                                                    <td class="text-right"><?= $aValue['FCPmcGetQty'] != '.0000' ? number_format($aValue['FCPmcGetQty'], 2, '.', ' ') : '-'; ?></td>
                                                    <td class="text-center"><img class="xCNIconTable xCNIconDel" src="<?= base_url() . '/application/assets/icons/delete.png' ?>" onclick="JSnRemoveRow(this)"></td>
                                                </tr>
                                            <?php } ?>

                                                                                                <!-- <tr id="otbGrpCondition_NotFound"><td class='text-center xCNTextDetail2' colspan='11'><?= language('common/main', 'tCMNNotFoundData') ?></td></tr> -->
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Content เงื่อนไข -->
            </div>
            
            <!-- เงื่อนไข -->
            <input type="hidden" id="ohdControllCound" value="<?= $nStaControllCound; ?>">

                <!-- กลุ่มซื้อ -->
                <?php if($aSpmData[0]->FTSpmStaGrpBuy == 1): ?>
                    <div class="panel panel-default" style="margin-bottom: 30px;">

                        <?php 
                            $tGrpBuyHave = '';  
                            $tGrpBuyName = '';
                            if(@is_array($aResultPmtDT['raItems']) == 1) {
                                foreach($aResultPmtDT['raItems'] AS $key=>$aValue){
                                    if($aValue['FTPmdGrpCode'] == "GrpBuy") {
                                        $tGrpBuyHave .= $aValue['FTPdtCode'] . ",";
                                        $tGrpBuyName = $aValue['FTPmdGrpName'];
                                    }
                                }
                            }
                        ?>
                        <!-- START Header กลุ่มซื้อ -->
                        <div id="odvHeadStaGrpBuy" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                            <?php $tGrpBuyName = $tGrpBuyName != '' ? $tGrpBuyName : language('document/promotion/promotion', 'tPMTGrpBuy'); ?>
                            <a class="xCNMenuplus" role="button" data-toggle="collapse" data-parent="#odvSubGroupApp" href="#odvDataStaGrpBuy" aria-expanded="true" aria-controls="odvDataPromotion"><i class="fa fa-plus xCNPlus"></i></a>

                            <!-- START Edit Header กลุ่มซื้อ -->
                            <div id="odvHeadBarShowPanalGrpBuy">
                                <a href="javascript:0" class="xWPutGrpBuy text-white" id="olaHeadGrpBuyName" data-type="text" data-placement="right" data-title="Enter username"><?= $tGrpBuyName ?></a>
                                <input type="text" class="xCNHide" id="ohdPmdGrpBuyNameCurrent" name="ohdPmdGrpBuyNameCurrent" value="<?= $tGrpBuyName ?>">
                                <i class="fa fa-pencil fa-lg xCNEditRowBtn text-white" id="oiIconEditGrpBuy" onclick="JSxShowInputEditInRow('GrpBuy')"></i>
                            </div>
                            <div id="odvHeadBarEditPanalGrpBuy" class="xCNHide">
                                <div class="wrap-input100 validate-input xCNOdvEditRow" data-validate="Please Enter">
                                    <input type="text" class="input100 xCNGetDrpDwnName" id="ohdPmdGrpBuyName" name="ohdPmdGrpBuyName" data-name="GrpBuy" value="<?= $tGrpBuyName ?>">

                                    <i class="fa fa-check-square-o fa-lg xCNIconEditRow text-white" id="oiIconEditGrpBuy" onclick="JStChangeGrpName('GrpBuy')"></i>
                                </div>
                            </div>
                            <!-- END Edit Header กลุ่มซื้อ -->
                        </div>
                        <!-- END Header กลุ่มซื้อ -->

                        <!-- START Content กลุ่มซื้อ -->
                        <div id="odvDataStaGrpBuy" class="panel-body collapse in" role="tabpanel" data-grpname="GrpBuy">
                            <div class="col-md-9 no-padding"></div>
                            <div class="col-md-3 no-padding" style="margin-bottom:10px;">
                                <button class="xCNBTNPrimeryPlus pull-right" id="oimPmhGrpBuyBrowsePdt" type="button">+</button>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover" style="width:100%">
                                            <input type="text" class="xCNHide" id="oetGrpBuyPdtCode" value="<?=@$tGrpBuyHave?>">
                                            <input type="text" class="xCNHide" id="oetGrpBuyPdtName">
                                            <thead>
                                                <tr class="xCNCenter">
                                                        <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBNo') ?></th>
                                                        <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBCode') ?></th>
                                                        <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBPdtName') ?></th>
                                                        <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBBarCode') ?></th>
                                                        <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBUnit') ?></th>
                                                        <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBPrice') ?></th>
                                                        <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBDelete') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody id="odvTBodyStaGrpBuy">
                                                <?php $nNumSeq = 1; ?>
                                                <?php if(@is_array($aResultPmtDT['raItems']) == 1): ?>
                                                    <?php foreach($aResultPmtDT['raItems'] AS $key=>$aValue){ ?>

                                                        <?php if($aValue['FTPmdGrpCode'] == "GrpBuy") :?>		
                                                            <tr class="text-center xCNTextDetail2" id="otrStaGrpBuy<?=$nNumSeq?>" data-otrval="<?=$aValue['FTPdtCode']?>">
                                                                    <td	class="xCNHide">
                                                                            <input class="xCNHide" name="ohdGrpBuy[]" value="<?=$aValue['FTPmdGrpName']?>,<?=$aValue['FTPdtCode']?>,<?=$aValue['FCPmdSetPriceOrg']?>,<?=$aValue['FTPunCode']?>">
                                                                    </td>
                                                                    <td><?=$nNumSeq?></td>	
                                                                    <td class="text-left"><?= $aValue['FTPdtCode'] != '' ? $aValue['FTPdtCode'] : '-' ?></td>		
                                                                    <td class="text-left"><?= $aValue['FTPdtName'] != '' ? $aValue['FTPdtName'] : '-' ?></td>
                                                                    <td class="text-left"><?= $aValue['FTBarCode'] != '' ? $aValue['FTBarCode'] : '-' ?></td>	
                                                                    <td><?= $aValue['FTPunName'] != '' ? $aValue['FTPunName'] : '-' ?></td>	
                                                                    <td class="text-right"><?= $aValue['FCPmdSetPriceOrg'] != '' ? number_format($aValue['FCPmdSetPriceOrg'], 2, '.', ' ') : '-' ?></td>	
                                                                    <td class="text-center"><img class="xCNIconTable xCNIconDel" src="<?= base_url().'/application/assets/icons/delete.png'?>" onclick="JSnRemoveRow(this)"></td>
                                                            </tr>
                                                            <?php $nNumSeq++; ?>
                                                        <?php endif; ?>
                                                    <?php } ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END Content กลุ่มซื้อ -->
                    </div>
                <?php endif; ?>

                <!-- กลุ่มซื้อร่วม 007 เท่านั้น -->
                <?php if($aSpmData[0]->FTSpmCode == 007):?>
                    <div class="panel panel-default" style="margin-bottom: 30px;"> 
                        <div id="odvHeadStaGrpJoin" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                            <?php 
                            $tGrpJoinHave = ''; 
                            $tGrpJoinName = '';
                            if(@is_array($aResultPmtDT['raItems']) == 1) {
                                foreach($aResultPmtDT['raItems'] AS $key=>$aValue){
                                    if($aValue['FTPmdGrpCode'] == "GrpJoin") {		
                                        $tGrpJoinHave .= $aValue['FTPdtCode'].",";
                                        $tGrpJoinName = $aValue['FTPmdGrpName'];
                                    }
                                }
                            }; 
                            ?>
                            <?php  $tGrpJoinName = $tGrpJoinName != '' ? $tGrpJoinName : 'สินค้าร่วมรายการ'; ?>
                            <a class="xCNMenuplus" role="button" data-toggle="collapse" data-parent="#odvSubGroupApp" href="#odvDataStaGrpJoin" aria-expanded="true" aria-controls="odvDataPromotion">
                                <i class="fa fa-plus xCNPlus"></i>
                            </a>
                            <div id="odvHeadBarShowPanalGrpJoin">
                                <a href="javascript:0" class="xWPutGrpJoin text-white" id="olaHeadGrpJoinName" data-type="text" data-placement="right" data-title="Enter username"><?=$tGrpJoinName?></a>
                                <input type="text" class="xCNHide" id="ohdPmdGrpJoinNameCurrent" name="ohdPmdGrpJoinNameCurrent" value="<?=$tGrpJoinName?>">
                                <i class="fa fa-pencil fa-lg xCNEditRowBtn text-white" id="oiIconEditGrpJoin" onclick="JSxShowInputEditInRow('GrpJoin')"></i>
                            </div>
                            <div id="odvHeadBarEditPanalGrpJoin" class="xCNHide">
                                <div class="wrap-input100 validate-input xCNOdvEditRow" data-validate="Please Enter">
                                    <input type="text" class="input100 xCNGetDrpDwnName" id="ohdPmdGrpJoinName" name="ohdPmdGrpJoinName" data-name="GrpJoin" value="<?=$tGrpJoinName?>">
                                    <span class="focus-input100"></span>
                                    <i class="fa fa-check-square-o fa-lg xCNIconEditRow text-white" id="oiIconEditGrpJoin" onclick="JStChangeGrpName('GrpJoin')"></i>
                                </div>
                            </div>
                            <!-- <span class="xCNPmhBtnPlus" id="oimPmhGrpJoinBrowsePdt" type="button">+</span> -->
                        </div>
                        <div id="odvDataStaGrpJoin" class="panel-body collapse in" role="tabpanel" data-grpname="GrpJoin">
                            <div class="col-md-9 no-padding"></div>
                            <div class="col-md-3 no-padding" style="margin-bottom:10px;">
                                <button class="xCNBTNPrimeryPlus pull-right" id="oimPmhGrpJoinBrowsePdt" type="button">+</button>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover" style="width:100%">

                                            <input type="text" class="xCNHide" id="oetGrpJoinPdtCode" value="<?=@$tGrpJoinHave?>">
                                            <input type="text" class="xCNHide" id="oetGrpJoinPdtName">
                                            <thead>
                                                <tr class="xCNCenter">
                                                    <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBNo') ?></th>
                                                    <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBCode') ?></th>
                                                    <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBPdtName') ?></th>
                                                    <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBBarCode') ?></th>
                                                    <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBUnit') ?></th>
                                                    <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBPrice') ?></th>
                                                    <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBDelete') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody id="odvTBodyStaGrpJoin">
                                                <?php $nNumSeq = 1; ?>
                                                <?php if(@is_array($aResultPmtDT['raItems']) == 1): ?>
                                                    <?php foreach($aResultPmtDT['raItems'] AS $key=>$aValue){ ?>
                                                        <?php if($aValue['FTPmdGrpCode'] == "GrpJoin") :?>		
                                                            <tr class="text-center xCNTextDetail2" id="otrStaGrpJoin<?=$nNumSeq?>" data-otrval="<?=$aValue['FTPdtCode']?>">
                                                                <td	class="xCNHide">
                                                                    <input class="xCNHide" name="ohdGrpJoin[]" value="<?=$aValue['FTPmdGrpName']?>,<?=$aValue['FTPdtCode']?>,<?=$aValue['FCPmdSetPriceOrg']?>,<?=$aValue['FTPunCode']?>">
                                                                </td>
                                                                <td><?=$nNumSeq?></td>	
                                                                <td class="text-left"><?= $aValue['FTPdtCode'] != '' ? $aValue['FTPdtCode'] : '-' ?></td>
                                                                <td class="text-left"><?= $aValue['FTPdtName'] != '' ? $aValue['FTPdtName'] : '-' ?></td>
                                                                <td class="text-left"><?= $aValue['FTBarCode'] != '' ? $aValue['FTBarCode'] : '-' ?></td>	
                                                                <td><?= $aValue['FTPunName'] != '' ? $aValue['FTPunName'] : '-' ?></td>	
                                                                <td class="text-right"><?= $aValue['FCPmdSetPriceOrg'] != '' ? number_format($aValue['FCPmdSetPriceOrg'], 2, '.', ' ') : '-' ?></td>	
                                                                <td class="text-center">
                                                                    <lable class="xCNTextLink">
                                                                        <i class="fa fa-trash-o" onclick="JSnRemoveRow(this)"></i>
                                                                    </lable>
                                                                </td>
                                                            </tr>
                                                            <?php $nNumSeq++; ?>
                                                        <?php endif; ?>
                                                    <?php } ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>    
                    </div>
                <?php endif; ?>
                <!-- กลุ่มซื้อร่วม 007 เท่านั้น -->

                            <!-- กลุ่มรับ -->
                <?php if($aSpmData[0]->FTSpmStaGrpRcv == 1):?>
                    <div class="panel panel-default" style="margin-bottom: 30px;"> 
                        <div id="odvHeadStaGrpRcv" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                            <?php 
                            $tGrpRcvHave = ''; 
                            $tGrpRcvName = '';
                            if(@is_array($aResultPmtDT['raItems']) == 1) {
                                foreach($aResultPmtDT['raItems'] AS $key=>$aValue){
                                    if($aValue['FTPmdGrpCode'] == "GrpRcv") {	
                                        $tGrpRcvHave .= $aValue['FTPdtCode'].",";
                                        $tGrpRcvName = $aValue['FTPmdGrpName'];
                                    }
                                }
                            } 
                            ?>
                            <?php  $tGrpRcvName = $tGrpRcvName != '' ? $tGrpRcvName : 'สินค้ากลุ่มได้รับ'; ?>
                            <a class="xCNMenuplus" role="button" data-toggle="collapse" data-parent="#odvSubGroupApp" href="#odvDataStaGrpRcv" aria-expanded="true" aria-controls="odvDataPromotion">
                                <i class="fa fa-plus xCNPlus"></i>
                            </a>
                            <div id="odvHeadBarShowPanalGrpRcv">
                                <a href="javascript:0" class="xWPutGrpRcv text-white" id="olaHeadGrpRcvName" data-type="text" data-placement="right" data-title="Enter username"><?=$tGrpRcvName?></a>
                                <input type="text" class="xCNHide" id="ohdPmdGrpRcvNameCurrent" name="ohdPmdGrpRcvNameCurrent" value="<?=$tGrpRcvName?>">
                                <i class="fa fa-pencil fa-lg xCNEditRowBtn text-white" id="oiIconEditGrpRcv" onclick="JSxShowInputEditInRow('GrpRcv')"></i>
                            </div>
                            <div id="odvHeadBarEditPanalGrpRcv" class="xCNHide">
                                <div class="wrap-input100 validate-input xCNOdvEditRow" data-validate="Please Enter">
                                    <input type="text" class="input100 xCNGetDrpDwnName" id="ohdPmdGrpRcvName" name="ohdPmdGrpRcvName" data-name="GrpRcv" value="<?=$tGrpRcvName?>">
                                    <span class="focus-input100"></span>
                                    <i class="fa fa-check-square-o fa-lg xCNIconEditRow text-white" id="oiIconEditGrpRcv" onclick="JStChangeGrpName('GrpRcv')"></i>
                                </div>
                            </div>
                            <!-- <span class="xCNPmhBtnPlus" id="oimPmhGrpRcvBrowsePdt" type="button">+</span> -->
                        </div>
                            <div id="odvDataStaGrpRcv" class="panel-body collapse in" role="tabpanel" data-grpname="GrpRcv">
                                <div class="col-md-9 no-padding"></div>
                                <div class="col-md-3 no-padding" style="margin-bottom:10px;">
                                    <button class="xCNBTNPrimeryPlus pull-right" id="oimPmhGrpRcvBrowsePdt" type="button">+</button>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover" style="width:100%">
                                                <input type="text" class="xCNHide" id="oetGrpRcvPdtCode" value="<?=@$tGrpRcvHave?>">
                                                <input type="text" class="xCNHide" id="oetGrpRcvPdtName">
                                                <thead>
                                                    <tr class="xCNCenter">
                                                        <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBNo') ?></th>
                                                        <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBCode') ?></th>
                                                        <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBPdtName') ?></th>
                                                        <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBBarCode') ?></th>
                                                        <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBUnit') ?></th>
                                                        <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBPrice') ?></th>
                                                        <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBDelete') ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="odvTBodyStaGrpRcv">
                                                    <?php $nNumSeq = 1; ?>
                                                    <?php if(@is_array($aResultPmtDT['raItems']) == 1): ?>
                                                        <?php foreach($aResultPmtDT['raItems'] AS $key=>$aValue){ ?>
                                                            <?php if($aValue['FTPmdGrpCode'] == "GrpRcv") :?>		
                                                                <tr class="text-center xCNTextDetail2" id="otrStaGrpRcv<?=$nNumSeq?>" data-otrval="<?=$aValue['FTPdtCode']?>">
                                                                    <td	class="xCNHide">
                                                                        <input class="xCNHide" name="ohdGrpRcv[]" value="<?=$aValue['FTPmdGrpName']?>,<?=$aValue['FTPdtCode']?>,<?=$aValue['FCPmdSetPriceOrg']?>,<?=$aValue['FTPunCode']?>">
                                                                    </td>
                                                                    <td><?=$nNumSeq?></td>	
                                                                    <td class="text-left"><?= $aValue['FTPdtCode'] != '' ? $aValue['FTPdtCode'] : '-' ?></td>
                                                                    <td class="text-left"><?= $aValue['FTPdtName'] != '' ? $aValue['FTPdtName'] : '-' ?></td>
                                                                    <td class="text-left"><?= $aValue['FTBarCode'] != '' ? $aValue['FTBarCode'] : '-' ?></td>	
                                                                    <td><?= $aValue['FTPunName'] != '' ? $aValue['FTPunName'] : '-' ?></td>	
                                                                    <td class="text-right"><?= $aValue['FCPmdSetPriceOrg'] != '' ? number_format($aValue['FCPmdSetPriceOrg'], 2, '.', ' ') : '-' ?></td>
                                                                    <td class="text-center">
                                                                        <lable class="xCNTextLink">
                                                                            <i class="fa fa-trash-o" onclick="JSnRemoveRow(this)"></i>
                                                                        </lable>
                                                                    </td>
                                                                </tr>
                                                                <?php $nNumSeq++; ?>
                                                            <?php endif; ?>
                                                        <?php } ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                    </div>
                <?php endif; ?>
                <!-- กลุ่มรับ -->

                <!-- Edit Page -->
                <?php $tPmdGrpCode = ''; ?>
                <?php $nSeqBothPanal = 0; ?>
                <?php if($tPmhCode != '' && @$aResultPmtGrpBoth['rtCode'] == 1 ): ?>
                    <div id="odvStaGrpBothPanal" style="margin-bottom: 30px;">
                        <?php foreach($aResultPmtGrpBoth['raItems'] AS $key=>$aValue){ ?>
                            <?php if($tPmdGrpCode != $aValue['FTPmdGrpCode']) : ?>
                                <?php 
                                $tPmdGrpCode = $aValue['FTPmdGrpCode'];
                                $nSeqBothPanal++;
                                $aGrpBothItem = explode("GrpBoth", $aValue['FTPmdGrpCode']); 
                                $nGrpBothItem = $aGrpBothItem[1];
                                ?>

                                <div class="panel panel-default xCNStaGrpBothPanal" id="odvStaGrpBoth<?=$nGrpBothItem?>">
                                    <input type="text" class="xCNHide" id="oetGrpBothItem<?=$nGrpBothItem?>" name="oetGrpBothItem[]" value="<?=$nGrpBothItem?>">
                                    <div id="odvHeadStaGrpBoth<?=$nGrpBothItem?>" class="panel-heading xCNPanelHeadColor" role="tab">

                                        <div class="row">
                                            <div class="col-md-9">
                                                <a class="xCNMenuplus" role="button" data-toggle="collapse" data-parent="#odvSubGroupApp" href="#odvDataStaGrpBoth<?=$nGrpBothItem?>" aria-expanded="true" aria-controls="odvDataPromotion">
                                                    <i class="fa fa-plus xCNPlus text-white"></i>
                                                </a>
                                                <div id="odvHeadBarShowPanalGrpBoth<?=$nGrpBothItem?>">
                                                    <a href="javascript:0" class="xWPutGrpBoth<?=$nGrpBothItem?> text-white" id="olaHeadGrpBothName<?=$nGrpBothItem?>" data-type="text" data-placement="right" data-title="Enter username"><?php  echo @$aResultPmtDT['raItems'][$key]['FTPmdGrpName'] != '' ? $aResultPmtDT['raItems'][$key]['FTPmdGrpName'] : 'สินค้ากลุ่มซื้อ/รับ'; ?></a>
                                                    <input type="text" class="xCNHide" id="ohdPmdGrpBothName<?=$nGrpBothItem?>Current" name="ohdPmdGrpBothName<?=$nGrpBothItem?>Current" value="<?php  echo @$aResultPmtDT['raItems'][$key]['FTPmdGrpName'] != '' ? $aResultPmtDT['raItems'][$key]['FTPmdGrpName'] : 'สินค้ากลุ่มซื้อ/รับ'; ?>">
                                                    <i class="fa fa-pencil fa-lg xCNEditRowBtn text-white" id="oiIconEditGrpBoth<?=$nGrpBothItem?>" onclick="JSxShowInputEditInRow('GrpBoth','<?=$nGrpBothItem?>')"></i>
                                                </div>
                                                <div id="odvHeadBarEditPanalGrpBoth<?=$nGrpBothItem?>" class="xCNHide">
                                                    <div class="wrap-input100 validate-input xCNOdvEditRow" data-validate="Please Enter">
                                                        <input type="text" class="input100 xCNGetDrpDwnName" id="ohdPmdGrpBothName<?=$nGrpBothItem?>" name="ohdPmdGrpBothName<?=$nGrpBothItem?>" data-name="GrpBoth<?=$nGrpBothItem?>" value="<?php  echo @$aResultPmtDT['raItems'][$key]['FTPmdGrpName'] != '' ? $aResultPmtDT['raItems'][$key]['FTPmdGrpName'] : 'สินค้ากลุ่มซื้อ/รับ'; ?>">
                                                        <span class="focus-input100"></span>
                                                        <i class="fa fa-check-square-o fa-lg xCNIconEditRow text-white" id="oiIconEditGrpBoth<?=$nGrpBothItem?>" onclick="JStChangeGrpName('GrpBoth','<?=$nGrpBothItem?>')"></i>
                                                    </div>
                                                </div>
                                                <!-- <span class="xCNPmhBtnPlus" id="oimPmhGrpBothBrowsePdt" type="button" onclick="JSxBrowseGrpBothMulti('<?=$nGrpBothItem?>')">+</span> -->
                                            </div>
                                            <div class="col-md-3">
                                                <?php if($aSpmData[0]->FTSpmCode == '010' || $aSpmData[0]->FTSpmCode == '011'): ?>

                                                    <?php if($nSeqBothPanal == '1'):?>
                                                        <button class="xCNBTNPrimeryPlus pull-right" style="background-color:green;" onclick="JSvAddStaGrpBothPanal()" type="button">+</button>
                                                        <!-- <span class="xCNPmhBtnAddGrpBothPanal"  onclick="JSvAddStaGrpBothPanal()"><i class="fa fa-arrow-down"></i></span> -->
                                                    <?php else: ?>
                                                        <button class="xCNBTNPrimeryPlus pull-right" onclick="JSvDeleteGrpBothPanal('<?=$nGrpBothItem?>')" type="button" style="background-color:red;">-</button>
                                                        <!-- <span class="xCNPmhBtnDeleteGrpBothPanal" onclick="JSvDeleteGrpBothPanal('<?=$nGrpBothItem?>')"><i class="fa fa-times"></i></span> -->
                                                    <?php endif; ?>

                                                <?php endif; ?>
                                            </div>
                                        </div>

                                    </div>
                                    
                                    <div id="odvDataStaGrpBoth<?=$nGrpBothItem?>" class="panel-body collapse in" role="tabpanel" data-grpname="GrpBoth" data-grpitem="<?=$nGrpBothItem?>">
                                        <div class="col-md-9 no-padding"></div>
                                        <div class="col-md-3 no-padding" style="margin-bottom:10px;">
                                            <button class="xCNBTNPrimeryPlus pull-right" id="oimPmhGrpBothBrowsePdt" type="button" onclick="JSxBrowseGrpBothMulti('<?=$nGrpBothItem?>')">+</button>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">

                                                <div class="table-responsive">
                                                    <table class="table table-hover" style="width:100%">
                                                        <?php 
                                                        $tGrpBothHave = '';
                                                        if(@is_array($aResultPmtDT['raItems']) == 1) {
                                                            foreach($aResultPmtDT['raItems'] AS $key=>$aValue){
                                                                if($aValue['FTPmdGrpCode'] == "GrpBoth".$nGrpBothItem) {		
                                                                    $tGrpBothHave .= $aValue['FTPdtCode'].",";
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <input type="text" class="xCNHide" id="oetGrpBothPdtCode<?=$nGrpBothItem?>" value="<?=@$tGrpBothHave?>">
                                                        <input type="text" class="xCNHide" id="oetGrpBothPdtName<?=$nGrpBothItem?>">
                                                        <thead>
                                                            <tr class="xCNCenter">
                                                                <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBNo') ?></th>
                                                                <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBCode') ?></th>
                                                                <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBPdtName') ?></th>
                                                                <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBBarCode') ?></th>
                                                                <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBUnit') ?></th>
                                                                <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBPrice') ?></th>
                                                                <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBDelete') ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="odvTBodyStaGrpBoth<?=$nGrpBothItem?>">
                                                        <?php $nNumSeq = 1; ?>
                                                        <?php if(@is_array($aResultPmtGrpBoth['raItems']) == 1): ?>	
                                                            <?php foreach($aResultPmtGrpBoth['raItems'] AS $key=>$aValue){ ?>
                                                                <?php if($aValue['FTPmdGrpCode'] == "GrpBoth".$nGrpBothItem) :?>		
                                                                    <tr class="text-center xCNTextDetail2" id="otrStaGrpBoth<?=$nGrpBothItem?><?=$nNumSeq?>" data-otrval="<?=$aValue['FTPdtCode']?>">
                                                                        <td class="xCNHide">
                                                                            <input class="xCNHide" type="text" id="ohdGrpBoth<?=$nGrpBothItem?>-<?=$aValue['FTPdtCode']?>" name="ohdGrpBoth<?=$nGrpBothItem?>[]" value="<?=$aValue['FTPmdGrpName']?>,<?=$aValue['FTPdtCode']?>,<?=$aValue['FCPmdSetPriceOrg']?>,<?=$aValue['FTPunCode']?>,<?=$aValue['FTPmdGrpCode']?>">
                                                                        </td>
                                                                        <td><?=$nNumSeq?></td>	
                                                                        <td class="text-left"><?= $aValue['FTPdtCode'] != '' ? $aValue['FTPdtCode'] : '-' ?></td>
                                                                        <td class="text-left"><?= $aValue['FTPdtName'] != '' ? $aValue['FTPdtName'] : '-' ?></td>
                                                                        <td class="text-left"><?= $aValue['FTBarCode'] != '' ? $aValue['FTBarCode'] : '-' ?></td>	
                                                                        <td><?= $aValue['FTPunName'] != '' ? $aValue['FTPunName'] : '-' ?></td>	
                                                                        <td class="text-right"><?= $aValue['FCPmdSetPriceOrg'] != '' ? number_format($aValue['FCPmdSetPriceOrg'], 2, '.', ' ') : '-' ?></td>	
                                                                        <td class="text-center">
                                                                            <lable class="xCNTextLink">
                                                                                <i class="fa fa-trash-o" onclick="JSnRemoveRow(this)"></i>
                                                                            </lable>
                                                                        </td>
                                                                    </tr>
                                                                    <?php $nNumSeq++; ?>
                                                                <?php endif; ?>
                                                            <?php } ?>
                                                            <?php $nNumSeq = 1; ?>
                                                        <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>    
                                </div>
                                                        <?php endif; ?>
                        <?php } ?>

                    </div> 
                <?php else: ?>

                            <!-- Add Page -->
                            <!-- กลุ่มซื้อ/รับ -->
                            <?php if($aSpmData[0]->FTSpmStaGrpBoth == 1):?>
                                    <div id="odvStaGrpBothPanal" style="margin-bottom: 30px;">
                                            <div class="panel panel-default xCNStaGrpBothPanal" id="odvStaGrpBoth1"> 
                                                    <input type="text" class="xCNHide" id="oetGrpBothItem1" name="oetGrpBothItem[]" value="1">
                                                    <div id="odvHeadStaGrpBoth1" class="panel-heading xCNPanelHeadColor" role="tab">
                                                            <div class="row">
                                                                    <div class="col-md-9">
                                                                            <a class="xCNMenuplus" role="button" data-toggle="collapse" data-parent="#odvSubGroupApp" href="#odvDataStaGrpBoth1" aria-expanded="true" aria-controls="odvDataPromotion">
                                                                                    <i class="fa fa-plus xCNPlus"></i>
                                                                            </a>
                                                                            <div id="odvHeadBarShowPanalGrpBoth1">
                                                                                    <a href="javascript:0" class="xWPutGrpBoth1 text-white" id="olaHeadGrpBothName1 " data-type="text" data-placement="right" data-title="Enter username"><?php  echo @$aResultPmtDT['raItems'][0]['FTPmdGrpName'] != '' ? $aResultPmtDT['raItems'][0]['FTPmdGrpName'] : 'สินค้ากลุ่มซื้อ/รับ'; ?></a>
                                                                                    <input type="text" class="xCNHide" id="ohdPmdGrpBothName1Current" name="ohdPmdGrpBothName1Current" value="สินค้ากลุ่มซื้อ/รับ">
                                                                                    <i class="fa fa-pencil fa-lg xCNEditRowBtn text-white" id="oiIconEditGrpBoth1" onclick="JSxShowInputEditInRow('GrpBoth','1')"></i>
                                                                            </div>
                                                                            <div id="odvHeadBarEditPanalGrpBoth1" class="xCNHide">
                                                                                            <div class="wrap-input100 validate-input xCNOdvEditRow" data-validate="Please Enter">
                                                                                                    <input type="text" class="input100 xCNGetDrpDwnName" id="ohdPmdGrpBothName1" name="ohdPmdGrpBothName1" data-name="GrpBoth1" value="สินค้ากลุ่มซื้อ/รับ">
                                                                                                    <span class="focus-input100"></span>
                                                                                                    <i class="fa fa-check-square-o fa-lg xCNIconEditRow text-white" id="oiIconEditGrpBoth1" onclick="JStChangeGrpName('GrpBoth','1')"></i>
                                                                                            </div>
                                                                            </div>
                                                                    </div>

                                                                    <div class="col-md-3">
                                                                            <?php if($aSpmData[0]->FTSpmCode == '010' || $aSpmData[0]->FTSpmCode == '011'): ?>
                                                                                    <button class="xCNBTNPrimeryPlus pull-right" style="background-color:green;" onclick="JSvAddStaGrpBothPanal()" type="button" >+</button>
                                                                            <?php endif; ?>
                                                                    </div>
                                                            </div>

                                                    </div>	
                                                    <div id="odvDataStaGrpBoth1" class="panel-body collapse in" role="tabpanel" data-grpname="GrpBoth" data-grpitem="1">
                                                            <div class="col-md-9 no-padding"></div>
                                                            <div class="col-md-3 no-padding" style="margin-bottom:10px;">
                                                                    <button class="xCNBTNPrimeryPlus pull-right" id="oimPmhGrpBothBrowsePdt" type="button" onclick="JSxBrowseGrpBothMulti('1')">+</button>
                                                            </div>

                                                            <div class="row">
                                                                    <div class="col-md-12">
                                                                            <div class="table-responsive">
                                                                                    <table class="table table-hover" style="width:100%">
                                                                                            <input type="text" class="xCNHide" id="oetGrpBothPdtCode1" value="">
                                                                                            <input type="text" class="xCNHide" id="oetGrpBothPdtName1">
                                                                                            <thead>
                                                                                                    <tr class="xCNCenter">
                                                                                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBNo') ?></th>
                                                                                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBCode') ?></th>
                                                                                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBPdtName') ?></th>
                                                                                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBBarCode') ?></th>
                                                                                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBUnit') ?></th>
                                                                                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBPrice') ?></th>
                                                                                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBDelete') ?></th>
                                                                                                    </tr>
                                                                                            </thead>
                                                                                            <tbody id="odvTBodyStaGrpBoth1">

                                                                                            </tbody>
                                                                                    </table>
                                                                            </div>
                                                                    </div>
                                                            </div>
                                                    </div>    
                                            </div>
                                    </div>
                            <?php endif; ?>
                            <!-- กลุ่มซื้อ/รับ -->
                            <!-- Add Page -->

                                    <?php endif; ?>



                            <!-- กลุ่มยกเว้น -->
                            <?php if($aSpmData[0]->FTSpmStaGrpReject == 1):?>
                            <div class="panel panel-default" style="margin-bottom: 30px;"> 
                                    <div id="odvHeadStaGrpReject" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                                            <?php $tGrpRejectHave = ''; $tGrpRejectName = ''; ?>
                                                    <?php if(@is_array($aResultPmtDT['raItems']) == 1): ?>
                                                                    <?php foreach($aResultPmtDT['raItems'] AS $key=>$aValue){ ?>
                                                                            <?php if($aValue['FTPmdGrpCode'] == "GrpReject") :?>		
                                                                                    <?php $tGrpRejectHave .= $aValue['FTPdtCode'].","; ?>
                                                                                    <?php $tGrpRejectName = $aValue['FTPmdGrpName']; ?>
                                                                            <?php endif; ?>
                                                            <?php } ?>
                                            <?php endif; ?>
                                            <?php  $tGrpRejectName = $tGrpRejectName != '' ? $tGrpRejectName : 'สินค้ากลุ่มยกเว้น'; ?>
                                            <a class="xCNMenuplus text-white" role="button" data-toggle="collapse" data-parent="#odvSubGroupApp" href="#odvDataStaGrpReject" aria-expanded="true" aria-controls="odvDataPromotion">
                                                    <i class="fa fa-plus xCNPlus text-white"></i>
                                            </a>
                                            <div id="odvHeadBarShowPanalGrpReject">
                                                            <a href="javascript:0" class="xWPutGrpReject text-white" id="olaHeadGrpRejectName" data-type="text" data-placement="right" data-title="Enter username"><?=$tGrpRejectName?></a>
                                                            <input type="text" class="xCNHide" id="ohdPmdGrpRejectNameCurrent" name="ohdPmdGrpRejectNameCurrent" value="<?=$tGrpRejectName?>">
                                            </div>
                                            <div id="odvHeadBarEditPanalGrpReject" class="xCNHide">
                                                            <div class="wrap-input100 validate-input xCNOdvEditRow" data-validate="Please Enter">
                                                                    <input type="text" class="input100" id="ohdPmdGrpRejectName" name="ohdPmdGrpRejectName" data-name="GrpReject" value="<?=$tGrpRejectName?>">
                                                                    <span class="focus-input100"></span>
                                                            </div>
                                            </div>

                                    </div>
                                    <div id="odvDataStaGrpReject" class="panel-body collapse in" role="tabpanel" data-grpname="GrpReject">
                                            <div class="col-md-9 no-padding"></div>
                                            <div class="col-md-3 no-padding" style="margin-bottom:10px;">
                                                    <button class="xCNBTNPrimeryPlus pull-right" id="oimPmhGrpRejectBrowsePdt" type="button">+</button>
                                            </div>

                                            <div class="row">
                                                    <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                    <table class="table table-hover" style="width:100%">

                                                                            <input type="text" class="xCNHide" id="oetGrpRejectPdtCode" value="<?=@$tGrpRejectHave?>">
                                                                            <input type="text" class="xCNHide" id="oetGrpRejectPdtName">
                                                                            <thead>
                                                                                    <tr class="xCNCenter">
                                                                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBNo') ?></th>
                                                                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBCode') ?></th>
                                                                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBPdtName') ?></th>
                                                                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBBarCode') ?></th>
                                                                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBUnit') ?></th>
                                                                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBPrice') ?></th>
                                                                                            <th class="xCNTextBold"><?= language('document/promotion/promotion', 'tPMTTBDelete') ?></th>
                                                                                    </tr>
                                                                            </thead>
                                                                            <tbody id="odvTBodyStaGrpReject">
                                                                                            <?php $nNumSeq = 1; ?>
                                                                                            <?php if(@is_array($aResultPmtDT['raItems']) == 1): ?>
                                                                                                            <?php foreach($aResultPmtDT['raItems'] AS $key=>$aValue){ ?>
                                                                                                                    <?php if($aValue['FTPmdGrpCode'] == "GrpReject") :?>		
                                                                                                                            <tr class="text-center xCNTextDetail2" id="otrStaGrpReject<?=$nNumSeq?>" data-otrval="<?=$aValue['FTPdtCode']?>">
                                                                                                                                    <td class="xCNHide">
                                                                                                                                            <input class="xCNHide" name="ohdGrpReject[]" value="<?=$aValue['FTPmdGrpName']?>,<?=$aValue['FTPdtCode']?>,<?=$aValue['FCPmdSetPriceOrg']?>,<?=$aValue['FTPunCode']?>">
                                                                                                                                    </td>
                                                                                                                                    <td><?=$nNumSeq?></td>	
                                                                                                                                    <td class="text-left"><?= $aValue['FTPdtCode'] != '' ? $aValue['FTPdtCode'] : '-' ?></td>
                                                                                                                                    <td class="text-left"><?= $aValue['FTPdtName'] != '' ? $aValue['FTPdtName'] : '-' ?></td>
                                                                                                                                    <td class="text-left"><?= $aValue['FTBarCode'] != '' ? $aValue['FTBarCode'] : '-' ?></td>	
                                                                                                                                    <td><?= $aValue['FTPunName'] != '' ? $aValue['FTPunName'] : '-' ?></td>	
                                                                                                                                    <td class="text-right"><?= $aValue['FCPmdSetPriceOrg'] != '' ? number_format($aValue['FCPmdSetPriceOrg'], 2, '.', ' ') : '-' ?></td>
                                                                                                                                    <td class="text-center">
                                                                                                                                            <lable class="xCNTextLink">
                                                                                                                                                    <i class="fa fa-trash-o" onclick="JSnRemoveRow(this)"></i>
                                                                                                                                            </lable>
                                                                                                                                    </td>
                                                                                                                            </tr>
                                                                                                                            <?php $nNumSeq++; ?>
                                                                                                            <?php endif; ?>
                                                                                                    <?php } ?>
                                                                                            <?php endif; ?>
                                                                            </tbody>
                                                                    </table>
                                                            </div>
                                                    </div>
                                            </div>
                                    </div>    
                            </div>
                            <?php endif; ?>
                            <!-- กลุ่มยกเว้น -->



                    </div>
        </div>
    </div>
</form>
<!-- div Dropdownbox -->
<div id="dropDownSelect1"></div>


<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddCondition">
<div class="modal fade" id="odvModalPmhCondition">
	<div class="modal-dialog" style="width: 500px;">
		<div class="modal-content">
			<div class="modal-header">
				<div class="row">
					<div class="col-xs-12 col-md-6">
						<label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?= language('document/promotion/promotion', 'tPMTGrpCondition') ?></label>
					</div>
					<div class="col-xs-12 col-md-6 text-right"> 
							<button class="btn xCNBTNPrimery xCNBTNPrimery2Btn" onclick="JSnPmhAddCondition()"><?=language('common/main', 'tModalConfirm')?></button>  
							<button class="btn xCNBTNDefult xCNBTNDefult2Btn" data-dismiss="modal" onclick="JSxPMTControlGetCond()"><?=language('common/main', 'tModalCancel')?></button> 
					</div>
				</div>
			</div>
			<div class="modal-body">

				<div class="form-group">
					<label class="xCNLabelFrm"><?=language('document/promotion/promotion','tPMTGroup')?></label>
					<select class="selectpicker form-control" id="oetPmcGrpName" name="oetPmcGrpName" maxlength="1" data-validate="<?= language('document/promotion/promotion','tPMTValidGroup')?>">
						<option value=""><?= language('common/main', 'tCMNBlank-NA') ?></option>
					</select>
				</div>

				<div class="form-group">
					<label class="xCNLabelFrm"><?=language('document/promotion/promotion','tPMTType')?></label>
					<select class="selectpicker form-control" id="oetPmcStaGrpCond" name="oetPmcStaGrpCond" maxlength="1" data-validate="<?= language('document/promotion/promotion','tPMTValidType')?>">
						<option value=""><?= language('common/main', 'tCMNBlank-NA') ?></option>
						<?php if($aSpmData[0]->FTSpmStaGrpBuy == 1 || $aSpmData[0]->FTSpmStaGrpBoth == 1): ?>
							<option value="1"><?= language('document/promotion/promotion', 'tPMTBuy') ?></option>
						<?php endif; ?>
						<?php if($aSpmData[0]->FTSpmStaGrpRcv == 1 || $aSpmData[0]->FTSpmStaGrpBoth == 1): ?>
							<option value="2"><?= language('document/promotion/promotion', 'tPMTReceive') ?></option>
						<?php endif; ?>
						<?php if($aSpmData[0]->FTSpmCode  == 007): ?>
							<option value="3"><?= language('document/promotion/promotion', 'tPMTBuyWith') ?></option>
						<?php endif; ?>
					</select>
				</div>

				<div class="form-group">
					<label class="xCNLabelFrm"><?=language('document/promotion/promotion','tPMTDisModel')?></label>
					<select class="selectpicker form-control" id="ostPmcGetCond" name="ostPmcGetCond" maxlength="1" data-validate="<?= language('document/promotion/promotion','tPMTValidGetCond')?>">
						<option value=""><?= language('common/main', 'tCMNBlank-NA') ?></option>
						<?php if($aSpmData[0]->FTSpmStaGetDisAmt == 1): ?> 
							<option value="1"><?= language('document/promotion/promotion', 'tPMTDisBaht') ?></option>
						<?php endif;?>
						<?php if($aSpmData[0]->FTSpmStaGetDisPer == 1): ?> 
							<option value="2"><?= language('document/promotion/promotion', 'tPMTDisPercent') ?> %</option>
						<?php endif;?>
						<?php if($aSpmData[0]->FTSpmStaGetNewPri == 1): ?> 
							<option value="3"><?= language('document/promotion/promotion', 'tPMTAdjPrice') ?></option>
						<?php endif;?>
						<?php if($aSpmData[0]->FTSpmStaGetPoint == 1): ?> 
							<option value="4"><?= language('document/promotion/promotion', 'tPMTGetPoint') ?></option>
						<?php endif;?>
					</select>
				</div>
				
				<div class="form-group">
					<?php if($aSpmData[0]->FTSpmStaBuy == 3 || $aSpmData[0]->FTSpmStaBuy == 4): ?>
					<label class="xCNLabelFrm"><span class="text-danger">*</span> <?= language('document/promotion/promotion','tPMTPmcBuyQty')?></label> 
					<input class="form-control xWTooltipsBT" type="number" id="oetPmcBuyQty" name="oetPmcBuyQty" data-validate="<?= language('document/promotion/promotion','tPMTPmcValidBuyQty')?>">
					<?php elseif ($aSpmData[0]->FTSpmStaBuy == 1 || $aSpmData[0]->FTSpmStaBuy == 2): ?>
					<label class="xCNLabelFrm"><span class="text-danger">*</span> <?= language('document/promotion/promotion','tPMTPmcBuyAmt')?></label> 
					<input class="form-control xWTooltipsBT" type="number" id="oetPmcBuyAmt" name="oetPmcBuyAmt" data-validate="<?= language('document/promotion/promotion','tPMTPmcValidBuyAmt')?>">
					<?php endif; ?>
				</div>

				<div class="form-group xWCdGetValue">
					<label class="xCNLabelFrm"><?= language('document/promotion/promotion','tPMTPmcGetValue')?></label> 
					<input class="form-control xWTooltipsBT" type="number" id="oetPmcGetValue" name="oetPmcGetValue">
				</div>

				<div class="form-group xWCdGetQty xCNHide">
					<label class="xCNLabelFrm"><?= language('document/promotion/promotion','tPMTPmcGetQtyPoint')?></label> 
					<input class="form-control xWTooltipsBT" type="number" id="oetPmcGetQty" name="oetPmcGetQty">
				</div>

				<div class="form-group xWCdPerAvgDis xCNHide">
					<label class="xCNLabelFrm"><?= language('document/promotion/promotion','tPMTPmcPerAvgDis')?>%</label> 
					<input class="form-control xWTooltipsBT" type="number" id="oetPmcPerAvgDis" name="oetPmcPerAvgDis">
				</div>

				<div class="form-group">
					<label class="xCNLabelFrm"><?= language('document/promotion/promotion','tPMTMinimum')?></label> 
					<input class="form-control xWTooltipsBT" type="number" id="oetPmcBuyMinQty" name="oetPmcBuyMinQty">
				</div>

				<div class="form-group">
					<label class="xCNLabelFrm"><?= language('document/promotion/promotion','tPMTNotMoreThan')?></label> 
					<input class="form-control xWTooltipsBT" type="number" id="oetPmcBuyMaxQty" name="oetPmcBuyMaxQty">
				</div>

			</div>
			
		</div>
	</div>
</div>
</form>

<script src="<?= base_url('application/modules/commom/assets/js/jquery.mask.js')?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include 'script/jPromotionAdd.php'; ?>






