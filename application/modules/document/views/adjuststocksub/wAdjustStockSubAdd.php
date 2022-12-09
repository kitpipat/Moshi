<?php
if($aResult['rtCode'] == "1"){
    $tAjhDocNo = $aResult['raItems']['FTAjhDocNo'];
    $tAjhDocDate = date('Y-m-d', strtotime($aResult['raItems']['FDAjhDocDate']));
    $tAjhDocTime = date('H:i:s', strtotime($aResult['raItems']['FDAjhDocDate']));
    $tCreateBy = $aResult['raItems']['FTCreateBy'];
    $tAjhStaDoc = $aResult['raItems']['FTAjhStaDoc'];
    $nAjhStaDocAct = $aResult['raItems']['FTAjhStaDocAct'];
    $tAjhStaApv = $aResult['raItems']['FTAjhStaApv'];
    $tAjhApvCode = $aResult['raItems']['FTAjhApvCode'];
    $tAjhStaPrcStk = $aResult['raItems']['FTAjhStaPrcStk'];
    $tBchCode = $aResult['raItems']['FTBchCode'];
    $tBchName = $aResult['raItems']['FTBchName'];
    $tMchCode = $aResult['raItems']['FTXthMerCode'];
    $tMchName = $aResult['raItems']['FTMerName'];

    // Event Control
    $tRoute = "adjStkSubEventEdit";
    
    if($this->session->userdata('tSesUsrLevel') == 'HQ'){
        $tUserBchCode = $aResult['raItems']['FTAjhBchTo'];
        $tUserBchName = $aResult['raItems']['FTBchNameTo'];
    }
    if($this->session->userdata('tSesUsrLevel') == 'HQ'){
        $tUserMchCode = $aResult['raItems']['FFAjhMerchantTo'];
        $tUserMchName = $aResult['raItems']['FFAjhMerchantNameTo'];
    }     
    if($this->session->userdata('tSesUsrLevel') == 'HQ'){
        $tUserShpCode = $aResult['raItems']['FTAjhShopTo'];
        $tUserShpName = $aResult['raItems']['FTAjhShopNameTo'];
    }    
    if($this->session->userdata('tSesUsrLevel') == 'HQ' || $this->session->userdata('tSesUsrLevel') == 'BCH' || $this->session->userdata('tSesUsrLevel') == 'SHP'){
        $tUserPosCode = $aResult['raItems']['FTAjhPosTo'];
        $tUserPosName = $aResult['raItems']['FTAjhPosNameTo'];
    }
    if($this->session->userdata('tSesUsrLevel') == 'HQ' || $this->session->userdata('tSesUsrLevel') == 'BCH'){
        $tUserWahCode = $aResult['raItems']['FTAjhWhTo'];
        $tUserWahName = $aResult['raItems']['FTAjhWhNameTo'];
    }
    
}else{
    $tAjhDocNo = "";
    $tAjhDocDate = date('Y-m-d');
    $tAjhDocTime = date('H:i:s');
    $tCreateBy = $this->session->userdata('tSesUsrUsername');
    $tAjhStaDoc = "";
    $nAjhStaDocAct = "";
    $tAjhStaApv = "";
    $tAjhApvCode = "";
    $tAjhStaPrcStk = "";
    $tBchCode = "";
    $tBchName = "";
    $tMchCode = "";
    $tMchName = "";

    // Event Control
    $tRoute = "adjStkSubEventAdd";
    
    
    if($this->session->userdata('tSesUsrLevel') == 'HQ'){
        $tUserBchCode = FCNtGetBchInComp();
        $tUserBchName = FCNtGetBchNameInComp();
    }
    if($this->session->userdata('tSesUsrLevel') == 'HQ'){
        $tUserMchCode = '';
        $tUserMchName = '';
    }     
    if($this->session->userdata('tSesUsrLevel') == 'HQ'){
        $tUserShpCode = '';
        $tUserShpName = '';
    } 
    if($this->session->userdata('tSesUsrLevel') == 'HQ' || $this->session->userdata('tSesUsrLevel') == 'BCH' || $this->session->userdata('tSesUsrLevel') == 'SHP'){
        $tUserPosCode = '';
        $tUserPosName = '';
    } 
    if($this->session->userdata('tSesUsrLevel') == 'HQ' || $this->session->userdata('tSesUsrLevel') == 'BCH'){
        $tUserWahCode = '';
        $tUserWahName = '';
    }
    
}

if($aUserCreated["rtCode"] == "1"){
    $tUserCreatedCode = $aUserCreated["raItems"]["rtUsrCode"];
    $tUserCreatedName = $aUserCreated["raItems"]["rtUsrName"];
}else{
    $tUserCreatedCode = "";
    $tUserCreatedName = "";
}

if($aUserApv["rtCode"] == "1"){
    $tUserApvCode = $aUserApv["raItems"]["rtUsrCode"];
    $tUserApvName = $aUserApv["raItems"]["rtUsrName"];
}else{
    $tUserApvCode = "";
    $tUserApvName = "";
}                      
?>

<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddAdjStkSub">
    <input type="hidden" id="ohdAdjStkSubAjhStaApv" name="ohdAdjStkSubAjhStaApv" value="<?php echo $tAjhStaApv; ?>">
    <input type="hidden" id="ohdAdjStkSubAjhStaDoc" name="ohdAdjStkSubAjhStaDoc" value="<?php echo $tAjhStaDoc; ?>">
    <input type="hidden" id="ohdAdjStkSubAjhStaPrcStk" name="ohdAdjStkSubAjhStaPrcStk" value="<?php echo $tAjhStaPrcStk; ?>">
    <input type="hidden" id="ohdAdjStkSubDptCode" name="ohdAdjStkSubDptCode" maxlength="5" value="<?php echo $tUserDptCode;?>">
    <input type="hidden" id="ohdAdjStkSubUsrCode" name="ohdAdjStkSubUsrCode" maxlength="20" value="<?php echo $tUserCode?>">
    <button style="display:none" type="submit" id="obtSubmitAdjStkSub" onclick="JSnAddEditAdjStkSub();"></button>
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default" style="margin-bottom: 25px;"> 
                <div id="odvHeadDoc" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/document/document', 'tDocLabel'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvAdjStkSubSubHeadDocPanel" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvAdjStkSubSubHeadDocPanel" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <div class="form-group xCNHide" style="text-align: right;">
                            <label class="xCNTitleFrom "><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubApproved'); ?></label>
                        </div>
                        <label class="xCNLabelFrm"><span style = "color:red">*</span><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubDocNo'); ?></label>
                        
                        <div class="form-group" id="odvAdjStkSubSubAutoGenDocNoForm">
                            <div class="validate-input">
                                <label class="fancy-checkbox">
                                    <input type="checkbox" id="ocbAdjStkSubSubAutoGenCode" name="ocbAdjStkSubSubAutoGenCode" checked="true" value="1">
                                    <span> <?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubAutoGenCode'); ?></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group" id="odvAdjStkSubSubDocNoForm">
                            <div class="validate-input">
                                <input 
                                    type="text" 
                                    class="form-control input100"
                                    id="oetAdjStkSubAjhDocNo" 
                                    aria-invalid="false"
                                    name="oetAdjStkSubAjhDocNo"
                                    data-is-created="<?php echo $tAjhDocNo; ?>"
                                    placeholder="<?= language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubDocNo') ?>"
                                    value="<?php echo $tAjhDocNo; ?>"
                                    data-validate="Plese Generate Code">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubDocDate'); ?></label>
                            <div class="input-group">
                                <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetAdjStkSubAjhDocDate" name="oetAdjStkSubAjhDocDate" value="<?php echo $tAjhDocDate; ?>" data-validate-required="<?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubPlsEnterDocDate'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtAdjStkSubDocDate" type="button" class="btn xCNBtnDateTime" onclick="$('#oetAdjStkSubAjhDocDate').focus()">
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubDocTime'); ?></label>
                            <div class="input-group">
                                <input type="text" class="form-control xCNTimePicker" id="oetAdjStkSubAjhDocTime" name="oetAdjStkSubAjhDocTime" value="<?php echo $tAjhDocTime; ?>" data-validate-required="<?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubPlsEnterDocTime'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtAdjStkSubAjhDocTime" type="button" class="btn xCNBtnDateTime" onclick="$('#oetAdjStkSubAjhDocTime').focus()">
                                        <img src="<?php echo base_url('/application/modules/common/assets/images/icons/icons8-Calendar-100.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubCreateBy'); ?></label>
                            </div>
                            <div class="col-md-6 text-right">
                                <input type="text" class="xCNHide" id="oetAdjStkSubAjhCreateBy" name="oetAdjStkSubAjhCreateBy" value="<?php echo $tUserCode ?>">
                                <label><?php echo $tUserName; ?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubTBStaDoc'); ?></label>
                            </div>
                            <div class="col-md-6 text-right">
                                <label><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubStaDoc' . $tAjhStaDoc); ?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubTBStaApv'); ?></label>
                            </div>
                            <div class="col-md-6 text-right">
                                <label><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubStaApv' . $tAjhStaApv); ?></label>
                            </div>
                        </div>
                        
                        <?php if($tAjhDocNo != '') { ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubApvBy'); ?></label>
                                </div>
                                <div class="col-md-6 text-right">
                                    <input type="text" class="xCNHide" id="oetAdjStkSubAjhApvCode" name="oetAdjStkSubAjhApvCode" maxlength="20" value="<?php echo $tAjhApvCode?>">
                                    <label><?php echo $tXthUsrNameApv != '' ? $tXthUsrNameApv : language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubStaDoc'); ?></label>
                                </div>
                            </div>
                        <?php } ?>
                        
                    </div>
                </div>    
            </div>
            <div class="panel panel-default" style="margin-bottom: 25px;">
                <div id="odvHeadGeneralInfo" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/document/document', 'tDocCondition'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse" href="#odvAdjStkSubSubWarehousePanel" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvAdjStkSubSubWarehousePanel" class="panel-collapse collapse in" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <!-- สาขา -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubBranch'); ?></label> <?php echo $tUserBchName; ?>	
                            <div class="input-group">
                                <input type="hidden" id="ohdAdjStkSubBchCode" name="ohdAdjStkSubBchCode" value="<?php echo $tUserBchCode; ?>">
                                <!--input class="form-control xCNHide" id="oetAdjStkSubBchCode" name="oetAdjStkSubBchCode" maxlength="5" value="<?php echo $tUserBchCode; ?>">
                                <input 
                                    class="form-control xWPointerEventNone" 
                                    type="text" 
                                    id="oetAdjStkSubBchName" 
                                    name="oetAdjStkSubBchName"
                                    value="<?php echo $tUserBchName; ?>" 
                                    readonly>
                                <span class="input-group-btn">
                                    <button id="obtAdjStkSubBrowseBch" type="button" class="btn xCNBtnBrowseAddOn">
                                        <img src="<?php echo  base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span-->
                            </div>
                        </div>
                        <!-- สาขา -->
                        
                        <!-- กลุ่มร้านค้า -->
                        <div class="form-group">
                            <label class="xCNLabelFrm">กลุ่มร้านค้า</label>
                            <div class="input-group">
                                <input class="form-control xCNHide" id="oetAdjStkSubMchCode" name="oetAdjStkSubMchCode" maxlength="5" value="<?php echo $tUserMchCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetAdjStkSubMchName" name="oetAdjStkSubMchName" value="<?php echo $tUserMchName; ?>" readonly>
                                <span class="input-group-btn">
                                    <button id="obtAdjStkSubBrowseMch" type="button" class="btn xCNBtnBrowseAddOn">
                                        <img src="<?php echo  base_url('application/modules/common/assets/images/icons/find-24.png'); ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- กลุ่มร้านค้า -->
                        
                        <!-- ร้านค้า -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubShop'); ?></label>
                            <div class="input-group">
                                <input type="hidden" id="ohdAdjStkSubWahCodeInShp" name="ohdAdjStkSubWahCodeInShp" value="<?php echo $tUserWahCode; ?>">
                                <input type="hidden" id="ohdAdjStkSubWahNameInShp" name="ohdAdjStkSubWahNameInShp" value="<?php echo $tUserWahName; ?>">
                                <input class="form-control xCNHide" id="oetAdjStkSubShpCode" name="oetAdjStkSubShpCode" maxlength="5" value="<?php echo $tUserShpCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetAdjStkSubShpName" name="oetAdjStkSubShpName" value="<?php echo $tUserShpName; ?>" readonly>
                                <span class="input-group-btn">
                                    <button id="obtAdjStkSubBrowseShp" type="button" class="btn xCNBtnBrowseAddOn">
                                        <img src="<?php echo  base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- ร้านค้า -->
                        
                        <!-- เครื่องจุดขาย -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubPos'); ?></label>
                            <div class="input-group">
                                <input type="text" class="input100 xCNHide" id="oetAdjStkSubPosCode" name="oetAdjStkSubPosCode" maxlength="5" value="<?php echo $tUserPosCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetAdjStkSubPosName" name="oetAdjStkSubPosName" value="<?php echo $tUserPosName; ?>" readonly>
                                <span class="input-group-btn">
                                    <button id="obtAdjStkSubBrowsePos" type="button" class="btn xCNBtnBrowseAddOn">
                                        <img src="<?php echo base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- เครื่องจุดขาย -->
                        
                        <!-- คลัง -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubWarehouse'); ?></label>
                            <div class="input-group">
                                <input type="hidden" id="ohdAdjStkSubWahCode" name="ohdAdjStkSubWahCode" value="<?php echo $tUserWahCode; ?>">
                                <input type="hidden" id="ohdAdjStkSubWahName" name="ohdAdjStkSubWahName" value="<?php echo $tUserWahName; ?>">
                                <input type="text" class="input100 xCNHide" id="oetAdjStkSubWahCode" name="oetAdjStkSubWahCode" maxlength="5" value="<?php echo $tUserWahCode; ?>">
                                <input class="form-control xWPointerEventNone" type="text" id="oetAdjStkSubWahName" name="oetAdjStkSubWahName" value="<?php echo $tUserWahName; ?>" readonly>
                                <span class="input-group-btn">
                                    <button id="obtAdjStkSubBrowseWah" type="button" class="btn xCNBtnBrowseAddOn">
                                        <img src="<?php echo base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- คลัง -->
                        
                        <!-- เหตุผล -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubReason'); ?></label>
                            <div class="input-group">
                                <input type="text" class="input100 xCNHide" id="oetAdjStkSubReasonCode" name="oetAdjStkSubReasonCode" maxlength="5" value="">
                                <input class="form-control xWPointerEventNone" type="text" id="oetAdjStkSubReasonName" name="oetAdjStkSubReasonName" value="" readonly>
                                <span class="input-group-btn xWConditionSearchPdt">
                                    <button id="obtAdjStkSubBrowseReason" type="button" class="btn xCNBtnBrowseAddOn xWConditionSearchPdt">
                                        <img src="<?php echo base_url() . 'application/modules/common/assets/images/icons/find-24.png' ?>">
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- เหตุผล -->
                        
                        <!-- หมายเหตุ -->
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubNote'); ?></label>
                            <textarea class="form-control xCNInputWithoutSpc" id="otaAdjStkSubAjhRmk" name="otaAdjStkSubAjhRmk"></textarea>
                        </div>
                        <!-- หมายเหตุ -->
                    </div> 
                </div> 
            </div>
            <div class="panel panel-default" style="margin-bottom: 60px;">
                <div id="odvHeadAllow" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubOther'); ?></label>
                    <a class="xCNMenuplus collapsed" role="button" data-toggle="collapse" href="#odvOther" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvOther" class="panel-collapse collapse" role="tabpanel">
                    <div class="panel-body xCNPDModlue">
                        <!-- สถานะความเคลื่อนไหว -->
                        <div class="form-group">
                            <label class="fancy-checkbox">
                                <input type="checkbox" value="1" id="ocbAdjStkSubAjhStaDocAct" name="ocbAdjStkSubAjhStaDocAct" maxlength="1" <?php echo ($nAjhStaDocAct == '1' || empty($nAjhStaDocAct)) ? 'checked' : ''; ?>>
                                <span>&nbsp;</span>
                                <span class="xCNLabelFrm"><?php echo language('document/purchaseorder/purchaseorder', 'tAdjStkSubStaDocAct'); ?></span>
                            </label>
                        </div>
                        <!-- สถานะความเคลื่อนไหว -->
                    </div>
                </div>    
            </div>
        </div>
        
        <!-- Right Panel -->
        <div class="col-md-8" id="odvAdjStkSubRightPanal">
            <!-- Pdt -->
            <div class="panel panel-default" style="margin-bottom: 25px;position: relative;min-height: 200px;"> 
                <div class="panel-collapse collapse in" role="tabpanel" data-grpname="Condition">
                    <div class="panel-body xCNPDModlue">
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-6 no-padding">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                maxlength="100" 
                                                id="oetAdjStkSubSearchPdtHTML" 
                                                name="oetAdjStkSubSearchPdtHTML" 
                                                onchange="JSvAdjStkSubDOCSearchPdtHTML()" 
                                                onkeyup="javascript:if(event.keyCode==13) JSvAdjStkSubDOCSearchPdtHTML()"
                                                placeholder="<?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubSearchPdt'); ?>">
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                maxlength="100" 
                                                id="oetAdjStkSubScanPdtHTML" 
                                                name="oetAdjStkSubScanPdtHTML" 
                                                onkeyup="javascript:if(event.keyCode==13) JSvAdjStkSubScanPdtHTML()" 
                                                placeholder="<?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubScanPdt'); ?>" 
                                                style="display:none;" 
                                                data-validate="ไม่พบข้อมูลที่แสกน">
                                            <span class="input-group-btn">
                                                <div id="odvAdjStkSubMngTableList" class="xCNDropDrownGroup input-group-append">
                                                    <button id="oimAdjStkSubMngPdtIconSearch" type="button" class="btn xCNBTNMngTable xCNBtnDocSchAndScan" onclick="JSvAdjStkSubDOCSearchPdtHTML()">
                                                        <img  src="<?php echo base_url('application/modules/common/assets/images/icons/search-24.png'); ?>" style="width:20px;">
                                                    </button>
                                                    <button id="oimAdjStkSubMngPdtIconScan" type="button" class="btn xCNBTNMngTable xCNBtnDocSchAndScan" style="display:none;" onclick="JSvAdjStkSubScanPdtHTML()">
                                                        <img class="oimMngPdtIconScan" src="<?php echo base_url('application/modules/common/assets/images/icons/scanner.png'); ?>" style="width:20px;">
                                                    </button>
                                                    <button type="button" class="btn xCNDocDrpDwn xCNBtnDocSchAndScan" data-toggle="dropdown">
                                                        <i class="fa fa-chevron-down f-s-14 t-plus-1" style="font-size: 12px;"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li>
                                                            <a id="oliAdjStkSubMngPdtSearch"><label><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubSearchPdt'); ?></label></a>
                                                            <a id="oliAdjStkSubMngPdtScan"><?php echo language('document/adjuststocksub/adjuststocksub', 'tAdjStkSubScanPdt'); ?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="selectpicker form-control" id="ocmAdjStkSubCheckTime" name="ocmAdjStkSubCheckTime" maxlength="1">
                                            <option value="1" selected>ตรวจนับครั้งที่ 1</option>
                                            <option value="2">ตรวจนับครั้งที่ 2</option>
                                            <option value="3">ตรวจนับทั้งหมด</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="btn-group xCNDropDrownGroup right">
                                    <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                                        <?php echo language('common/main/main', 'tCMNOption') ?>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li id="oliAdjStkSubBtnDeleteAll" class="disabled">
                                            <a data-toggle="modal" data-target="#odvModalDelPdtAdjStkSub"><?php echo language('common/main/main', 'tDelAll') ?></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <div style="position: absolute;right: 15px;top:-5px;">
                                        <button id="obtAdjStkSubDocBrowsePdt" class="xCNBTNPrimeryPlus xCNDocBrowsePdt
                                        <?php
                                        if ($tRoute == "AdjStkSubEventAdd") {
                                        ?>
                                            disabled
                                        <?php
                                        } else {
                                            if ($tMchCode != "") {
                                        ?>
                                                disabled
                                        <?php
                                            }
                                        }
                                        ?>" onclick="JCNvAdjStkSubBrowsePdt()" type="button">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="odvAdjStkSubPdtTablePanal"></div>
                        <!--div id="odvPdtTablePanalDataHide"></div-->
                    </div>
                </div>
            </div>
            <!-- Pdt -->
        </div>
        <!-- Right Panel -->
    </div>
</form>

<div class="modal fade" id="odvAdjStkSubPopupApv">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('common/main/main', 'tApproveTheDocument'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php echo language('common/main/main', 'tMainApproveStatus'); ?></p>
                <ul>
                    <li><?php echo language('common/main/main', 'tMainApproveStatus1'); ?></li>
                    <li><?php echo language('common/main/main', 'tMainApproveStatus2'); ?></li>
                    <li><?php echo language('common/main/main', 'tMainApproveStatus3'); ?></li>
                    <li><?php echo language('common/main/main', 'tMainApproveStatus4'); ?></li>
                </ul>
                <p><?php echo language('common/main/main', 'tMainApproveStatus5'); ?></p>
                <p><strong><?php echo language('common/main/main', 'tMainApproveStatus6'); ?></strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSnAdjStkSubApprove(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="odvShowOrderColumn" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo language('common/main/main', 'tModalAdvTable'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo language('common/main/main', 'tModalAdvClose'); ?></button>
                <button type="button" class="btn btn-primary" onclick="JSxSaveColumnShow()"><?php echo language('common/main/main', 'tModalAdvSave'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="odvModalEditAdjStkSubDisHD">
    <div class="modal-dialog xCNDisModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="display:inline-block"><label class="xCNLabelFrm"><?php echo language('common/main/main', 'tAdjStkSubDisEndOfBill'); ?></label></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tAdjStkSubDisType'); ?></label>
                            <select class="selectpicker form-control" id="ostXthHDDisChgText" name="ostXthHDDisChgText">
                                <option value="3"><?php echo language('document/adjuststocksub/adjuststocksub', 'tDisChgTxt3') ?></option>
                                <option value="4"><?php echo language('document/adjuststocksub/adjuststocksub', 'tDisChgTxt4') ?></option>
                                <option value="1"><?php echo language('document/adjuststocksub/adjuststocksub', 'tDisChgTxt1') ?></option>
                                <option value="2"><?php echo language('document/adjuststocksub/adjuststocksub', 'tDisChgTxt2') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="xCNLabelFrm"><?php echo language('common/main/main', 'tAdjStkSubValue'); ?></label>
                        <input type="text" class="form-control xCNInputNumericWithDecimal" id="oetXddHDDis" name="oetXddHDDis" maxlength="11" placeholder="">
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary xCNBtnAddDis" onclick="FSvAdjStkSubAddHDDis()">
                                <label class="xCNLabelAddDis">+</label>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="odvHDDisListPanal"></div>
                <input type='hidden' id="ohdConfirmIDDelete">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="odvAdjStkSubPopupCancel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard">ยกเลิกเอกสาร</label>
            </div>
            <div class="modal-body">
                <p id="obpMsgApv">เอกสารใบนี้ทำการประมวลผล หรือยกเลิกแล้ว ไม่สามารถแก้ไขได้</p>
                <p><strong>คุณต้องการที่จะยกเลิกเอกสารนี้หรือไม่?</strong></p>
            </div>
            <div class="modal-footer">
                <button onclick="JSnAdjStkSubCancel(true)" type="button" class="btn xCNBTNPrimery">
                    <?php echo language('common/main/main', 'tModalConfirm'); ?>
                </button>
                <button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
                    <?php echo language('common/main/main', 'tModalCancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo  base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo  base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include('script/jAdjustStockSubAdd.php')?>
















































































































