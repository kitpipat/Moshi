
<?php
    date_default_timezone_set("Asia/Bangkok");
    if(isset($nStaAddOrEdit) && $nStaAddOrEdit == 1){
        "<pre>";
        // print_r($aSpaData['raItems']);
        "</pre>";
        $tRoute         = "dcmSPAEventEdit";
        $tBchCode       = $aSpaData['raItems']['FTBchCode'];
        $tMerCode       = $aSpaData['raItems']['FTMerCode'];
        $tMerName       = $aSpaData['raItems']['FTMerName'];
        $tXphDocNo      = $aSpaData['raItems']['FTXphDocNo'];
        $tXphDocType    = $aSpaData['raItems']['FTXphDocType'];
        $tXphStaAdj     = $aSpaData['raItems']['FTXphStaAdj'];
        $dXphDocDate    = $aSpaData['raItems']['FDXphDocDate'];
        $tXphDocTime    = $aSpaData['raItems']['FTXphDocTime'];
        $tXphRefInt     = $aSpaData['raItems']['FTXphRefInt'];
        $dXphRefIntDate = $aSpaData['raItems']['FDXphRefIntDate'];
        $tXphName       = $aSpaData['raItems']['FTXphName'];
        $tPplCode       = $aSpaData['raItems']['FTPplCode'];
        $tPplName       = $aSpaData['raItems']['FTPplName'];
        $tAggCode       = $aSpaData['raItems']['FTAggCode'];
        $tAggName       = $aSpaData['raItems']['FTAggName'];
        $dXphDStart     = $aSpaData['raItems']['FDXphDStart'];
        $tXphTStart     = $aSpaData['raItems']['FTXphTStart'];
        $dXphDStop      = $aSpaData['raItems']['FDXphDStop'];
        $tXphTStop      = $aSpaData['raItems']['FTXphTStop'];
        $tXphPriType    = $aSpaData['raItems']['FTXphPriType'];
        $tXphStaDoc     = $aSpaData['raItems']['FTXphStaDoc'];
        $tXphStaPrcDoc  = $aSpaData['raItems']['FTXphStaPrcDoc'];
        $nXphStaDocAct  = $aSpaData['raItems']['FNXphStaDocAct'];
        $tUsrCode       = $aSpaData['raItems']['FTUsrCode'];
        $tXphUsrApv     = $aSpaData['raItems']['FTXphUsrApv'];
        $tXphStaApv     = $aSpaData['raItems']['FTXphStaApv'];   //เปลี่ยนจาก FTXphUsrApv เป็น FTXphStaApv เนื่องจากตอนแรกไม่มี ฟิลส์ FTXphStaApv [ Napat(Jame) 05-09-2019 ]
        $tXphZneTo      = $aSpaData['raItems']['FTXphZneTo'];
        $tXphZneToName  = $aSpaData['raItems']['FTZneName'];
        $tXphBchTo      = $aSpaData['raItems']['FTXphBchTo'];
        $tXphBchToName  = $aSpaData['raItems']['FTBchName'];
        $tXphRmk        = $aSpaData['raItems']['FTXphRmk'];
        $dLastUpdOn     = $aSpaData['raItems']['FDLastUpdOn'];
        $tLastUpdBy     = $aSpaData['raItems']['FTLastUpdBy'];
        $dCreateOn      = $aSpaData['raItems']['FDCreateOn'];
        $tCreateBy      = $aSpaData['raItems']['FTCreateBy'];
        $tUsrNameCreateBy = $aSpaData['raItems']['FTUsrName'];
        $tXphStaDelMQ   = $aSpaData['raItems']['FTXphStaDelMQ'];
        $tXphMerCode    = $aSpaData['raItems']['FTMerCode'];
        $tXphMerName    = $aSpaData['raItems']['FTMerName'];
        $nLngID         = $nLngID;

        $dXphDStopyear  = date('Y-m-d',strtotime("+1 year"));
        // Create By Witsarut 27/08/2019
        if(isset($aResList['raItems']['rtCmpCode'])){
            $tCmpCode  = $aResList['raItems']['rtCmpCode'];
        }else{
            $tCmpCode  = '';
        }
        
        // Create By Witsarut 27/08/2019

        if($tXphStaApv == ""){
            $tTextApv = language('document/salepriceadj/salepriceadj','tSpaXphUsrApv');
        }else{
            $tTextApv = language('document/salepriceadj/salepriceadj','tSpaXphUsrApv1');
        }
    }else{

        $tRoute         = "dcmSPAEventAdd";
        $tBchCode       = "";
        $tMerCode       = "";
        $tMerName       = "";
        $tXphDocNo      = "";
        $tXphDocType    = "";
        $tXphStaAdj     = "";
        $dXphDocDate    = date('Y-m-d');
        $tXphDocTime    = date('H:i:s');
        $tXphRefInt     = "";
        $dXphRefIntDate = "";
        $tXphName       = "";
        $tPplCode       = "";
        $tPplName       = "";
        $tAggCode       = "";
        $tAggName       = "";
        $dXphDStart     = date('Y-m-d');
        $tXphTStart     = date('00:00:01');
        $dXphDStop      = date('Y-m-d',strtotime("+1 year"));
        $tXphTStop      = date('23:59:59');
        $tXphPriType    = "";
        $tXphStaDoc     = "N/A";
        $tXphStaPrcDoc  = "";
        $nXphStaDocAct  = "";
        $tUsrCode       = "";
        $tXphUsrApv     = "N/A";
        $tXphStaApv     = "";
        $tXphZneTo      = "";
        $tXphZneToName  = "";
        $tXphBchTo      = "";
        $tXphBchToName  = "";
        $tXphRmk        = "";
        $dLastUpdOn     = "";
        $tLastUpdBy     = "";
        $dCreateOn      = "";
        $tUsrNameCreateBy = $this->session->userdata('tSesUsrUsername');
        $tCreateBy        = $this->session->userdata("tSesUsername");
        $tXphStaDelMQ   = "";
        $nLngID         = "";
        $tTextApv       = language('document/salepriceadj/salepriceadj','tSpaXphUsrApv');
        $dXphDStopyear  = date('Y-m-d',strtotime("+1 year"));
        // Create By Witsarut 27/08/2019
        if(isset($aResList['raItems']['rtCmpCode'])){
            $tCmpCode  = $aResList['raItems']['rtCmpCode'];
        }else{
            $tCmpCode  = '';
        }
        // Create By Witsarut 27/08/2019

        if($this->session->userdata("tSesUsrLevel") == "BCH" || $this->session->userdata("tSesUsrLevel") == "SHP"){
            $tXphBchTo          = $this->session->userdata("tSesUsrBchCode");
            $tXphBchToName      = $this->session->userdata("tSesUsrBchName");
        }
        if($this->session->userdata("tSesUsrLevel") == "HQ"){
            $tXphBchTo          = FCNtGetBchInComp();
            $tXphBchToName      = FCNtGetBchNameInComp();
        }
        $tXphMerCode = $this->session->userdata('tSesUsrMerCode');
        $tXphMerName = $this->session->userdata('tSesUsrMerName');
        
    }

?>


<input type="hidden" id="ohdDateStop" value="<?php echo $dXphDStopyear; ?>">
<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddSpa">
    <input type="hidden" id="ohdBaseUrl" name="ohdBaseUrl" value="<?php echo base_url(); ?>">
    <input type="text" class="xCNHide" id="oetLangCode" value="<?=$nLngID?>">
    <input type="text" class="xCNHide" id="oetUsrCode" name="oetUsrCode" value="<?=$tUsrCode?>">
    <input type="text" class="xCNHide" id="oetBchCode" name="oetBchCode" value="<?=FCNtGetBchInComp();?>">
    <input type="text" class="xCNHide" id="ohdCompCode" name="ohdCompCode" value="<?=$tCmpCode?>">
    <input type="text" class="xCNHide" id="oetStaPrcDoc" name="oetStaPrcDoc" value="<?=$tXphStaPrcDoc?>">
    <input type="text" class="xCNHide" id="oetStaDelQname" name="oetStaDelQname" value="<?=$tXphStaDelMQ; ?>">
    <input type="text" class="xCNHide" id="oetXthApvCodeUsrLogin" name="oetXthApvCodeUsrLogin" maxlength="20" value="<?php echo $this->session->userdata('tSesUsername'); ?>">
	<input type="text" class="xCNHide" id="ohdLangEdit" name="ohdLangEdit" maxlength="1" value="<?php echo $this->session->userdata("tLangEdit"); ?>">
    <input 
     type="hidden"
     id="ohdTextValidate"  
        validatedateimpact  = "<?php echo language('document/salepriceadj/salepriceadj', 'tSpaValidDateStrImpact'); ?>"
        validatepdrcode     = "<?php echo language('document/salepriceadj/salepriceadj', 'tSpaValidXphPdtCode'); ?>"
        validatevalue       = "<?php echo language('document/salepriceadj/salepriceadj', 'tSpaValidXphPdtValue'); ?>"
     >
     
    <button style="display:none" type="submit" id="obtSubmitSpa" onclick="JSoAddEditSpa('<?= $tRoute?>')"></button>
    <div class="row">
        <div class="col-md-4">
        
            <div class="panel panel-default" style="margin-bottom: 25px;"> 
                <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/salepriceadj/salepriceadj', 'tSpaADDDocTitle'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvDataDoc" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDataDoc" class="panel-collapse collapse in" role="tabpanel" style="margin-top:10px;">
                    <div class="panel-body xCNPDModlue">

                        <div class="form-group xWAutoGenerate">
                            <label class="fancy-checkbox">
                                <input type="checkbox" id="ocbStaAutoGenCode" name="ocbStaAutoGenCode" maxlength="1" checked>
                                <span class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj', 'tSpaADDAutoGen'); ?></span>
                            </label>
                        </div>
                    
                        <div class="form-group">
                            <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj','tSpaADDXphDocNo');?></label>
                            <input type="text" class="form-control xCNGenarateCodeTextInputValidate" id="oetXphDocNo" name="oetXphDocNo" maxlength="20" value="<?=$tXphDocNo; ?>"  data-validate="<?=language('document/salepriceadj/salepriceadj', 'tSpaValidXphDocNo'); ?>" placeholder="####">
                        </div>

                        <div class="form-group">
                            <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj', 'tSpaADDXphDocDate'); ?></label>
                            <div class="input-group">
                                <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetXphDocDate" name="oetXphDocDate" autocomplete="off" value="<?=$dXphDocDate; ?>" data-validate="<?=language('document/salepriceadj/salepriceadj', 'tSpaValidXphDocDate'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtXphDocDate" type="button" class="btn xCNBtnDateTime">
                                        <img src="<?= base_url().'/application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                                    </button>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj','tSpaADDXphDocTime');?></label>
                            <div class="input-group">
                                <input type="text" class="form-control xCNTimePicker xCNInputMaskDate" id="oetXphDocTime" name="oetXphDocTime" autocomplete="off" value="<?=$tXphDocTime; ?>" data-validate="<?=language('document/salepriceadj/salepriceadj', 'tSpaValidXphDocTime'); ?>">
                                <span class="input-group-btn">
                                    <button id="obtXphDocTime" type="button" class="btn xCNBtnDateTime">
                                        <img src="<?= base_url().'/application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                                    </button>
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj', 'tSpaADDCreateBy'); ?></label>
                            </div>
                            <div class="col-md-6 text-right">
                                <input type="text" class="xCNHide" id="oetCreateBy" name="oetCreateBy" value="<?=$tCreateBy?>">
                                <label><?=$tUsrNameCreateBy?></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="xCNLabelFrm"><?php echo language('document/salepriceadj/salepriceadj', 'tSpaADDXphStaDoc'); ?></label>
                            </div>
                            <div class="col-md-6 text-right">
                                <input type="text" class="xCNHide" id="oetStaDoc" name="oetStaDoc" value="<?=$tXphStaDoc?>">
                                <label><?=language('document/salepriceadj/salepriceadj', 'tSpaADDXphStaDocn'.$tXphStaDoc); ?></label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="xCNLabelFrm"><?php echo language('document/salepriceadj/salepriceadj', 'tTBSpaXphUsrApv'); ?></label>
                            </div>
                            <div class="col-md-6 text-right">
                                <input type="text" class="xCNHide" id="oetStaApv" name="oetStaApv" value="<?=$tXphStaApv?>">
                                <input type="text" class="xCNHide" id="oetUsrApv" name="oetUsrApv" value="<?=$tXphUsrApv?>">
                                <label><?=$tTextApv?></label>
                            </div>
                        </div>
                        
                    </div>
                </div>    
            </div>


            <div class="panel panel-default" style="margin-bottom: 25px;"> 
                <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/salepriceadj/salepriceadj', 'tSpaADDConditionsTitle'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvDataConditions" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDataConditions" class="panel-collapse collapse in" role="tabpanel" style="margin-top:10px;">
                    <div class="panel-body xCNPDModlue">

                        <div class="form-group">
                            <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj','tSpaADDXphDocType');?></label>
                            <select class="selectpicker form-control" id="ocmXphDocType" name="ocmXphDocType" maxlength="1" data-validate="<?=language('document/salepriceadj/salepriceadj', 'tSpaValidXphDocType'); ?>">
                                <!-- <option value=""><?=language('document/salepriceadj/salepriceadj','tSpaADDXphDocType')?></option> -->
                                <option value="1" <?=$tXphDocType == "1" ? "selected" : "";?>><?=language('document/salepriceadj/salepriceadj','tSpaADDXphDocType1')?></option>
                                <option value="2" <?=$tXphDocType == "2" ? "selected" : "";?>><?=language('document/salepriceadj/salepriceadj','tSpaADDXphDocType2')?></option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj','tSpaADDXphStaAdj');?></label>
                            <select class="selectpicker form-control" id="ocmXphStaAdj" name="ocmXphStaAdj" maxlength="1" onchange="JSxCheckValue(value);" data-validate="<?=language('document/salepriceadj/salepriceadj', 'tSpaValidXphStaAdj'); ?>">
                                <!-- <option value=""><?=language('document/salepriceadj/salepriceadj','tSpaADDXphStaAdj')?></option> -->
                                <option id="optStaAdj1" value="1" <?=$tXphStaAdj == "1" ? "selected" : "";?>><?=language('document/salepriceadj/salepriceadj','tSpaADDXphStaAdj1')?></option>
                                <option id="optStaAdj2" value="2" <?=$tXphStaAdj == "2" ? "selected" : "";?>><?=language('document/salepriceadj/salepriceadj','tSpaADDXphStaAdj2')?></option>
                                <option id="optStaAdj3" value="3" <?=$tXphStaAdj == "3" ? "selected" : "";?>><?=language('document/salepriceadj/salepriceadj','tSpaADDXphStaAdj3')?></option>
                                <option id="optStaAdj4" value="4" <?=$tXphStaAdj == "4" ? "selected" : "";?>><?=language('document/salepriceadj/salepriceadj','tSpaADDXphStaAdj4')?></option>
                                <option id="optStaAdj5" value="5" <?=$tXphStaAdj == "5" ? "selected" : "";?>><?=language('document/salepriceadj/salepriceadj','tSpaADDXphStaAdj5')?></option>
                            </select>
                        </div>

                        
                            <div class="row">
                                <div class="col-xs-5 col-md-4 col-lg-4">
                                    <div class="form-group form-inline">
                                        <div class="input-group" style="margin-right:0px;width:100%;">
                                            <input type="hidden" id="ohdValueType1" value="<?php echo language('document/salepriceadj/salepriceadj','tSpaADDValueType1')?>">
                                            <input type="hidden" id="ohdValueType2" value="<?php echo language('document/salepriceadj/salepriceadj','tSpaADDValueType2')?>">
                                            <input type="text" class="form-control" id="oetValue" name="oetValue" placeholder="<?=language('document/salepriceadj/salepriceadj','tSpaADDValue')?>">
                                            <span class="input-group-btn">
                                                <button type="button" id="ospValueType" class="btn xCNBtnBrowseAddOn" style="font-size:17px;font-weight:bold;"><?php echo language('document/salepriceadj/salepriceadj','tSpaADDValueType2')?></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-5 col-md-4 col-lg-4 xCNHide" style="margin-right:0px; margin-left:0px; padding-left:0px; padding-right:0px;">
                                    <select class="selectpicker form-control" id="ocmChangePrice" name="ocmChangePrice" maxlength="1">
                                        <option value="1" selected><?=language('document/salepriceadj/salepriceadj','tPdtPriTBAdjustAll')?></option>
                                        <option value="2"><?=language('document/salepriceadj/salepriceadj','tPdtPriTBPriceRet')?></option>
                                        <option value="3"><?=language('document/salepriceadj/salepriceadj','tPdtPriTBPriceWhs')?></option>
                                        <option value="4"><?=language('document/salepriceadj/salepriceadj','tPdtPriTBPriceNet')?></option>
                                    </select>
                                </div>

                                <div class="col-xs-2 col-md-4 col-lg-4" style="margin-right:0px;margin-left:0px;text-align: center;">
                                    <button type="button" id="obtAdjAll" class="btn btn-primary" style="width:100%;font-size: 17px;padding-left:10px;padding-right:10px;padding-top:5px;padding-bottom:2px;"><?=language('document/salepriceadj/salepriceadj','tSpaADDBtnAdjAll')?></button>
                                </div>
                            </div>


                            <input type="hidden" id="ohdBranchSalePrice" name="ohdBranchSalePrice" value="<?=$this->session->userdata('tSesUsrBchCom')?>" >
                        <!-- <div class="form-group">
                            <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj','tSpaADDXphBchTo');?></label>
                            <div class="input-group">
                                <input name="oetXphBchTo" id="oetXphBchTo" class="form-control xCNHide" value="<?=$tXphBchTo?>">
                                <input name="oetBchName" id="oetBchName" class="form-control xWPointerEventNone xWRptConsCrdInput"  type="text" readonly="" value="<?=$tXphBchToName?>">
                                <span class="input-group-btn">
                                    <button class="btn xCNBtnBrowseAddOn" id="btnBrowseBranch" type="button" <?php 
                                    if($this->session->userdata("tSesUsrLevel") == "BCH" || $this->session->userdata("tSesUsrLevel") == "SHP"){ 
                                        echo "disabled"; 
                                    }else{
                                        if($tCmpCode=='' || !FCNtGetBchInComp()){
                                            echo "disabled"; 
                                        }
                                    } ?>>
                                        <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                    </button>
                                </span>
                            </div>
                        </div> -->

                        <!-- <div class="form-group">
                            <label class="xCNLabelFrm"><?php echo language('merchant/merchant/merchant','tMerchantTitle')?></label>
                            <div class="input-group">
                                <input name="oetXphMerCode" id="oetXphMerCode" class="form-control xCNHide" value="<?=$tXphMerCode?>">
                                <input name="oetMerName" id="oetMerName" class="form-control xWPointerEventNone xWRptConsCrdInput"  type="text" readonly="" value="<?=$tXphMerName?>">
                                <span class="input-group-btn">
                                    <button class="btn xCNBtnBrowseAddOn" id="btnBrowseMerChrant" type="button" <?php 
                                    if($this->session->userdata("tSesUsrLevel") == "SHP"){ 
                                        echo "disabled"; 
                                    }else{
                                        if($tCmpCode=='' || !FCNtGetBchInComp()){
                                            echo "disabled"; 
                                        }
                                    } ?>>
                                        <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                    </button>
                                </span>
                            </div>
                        </div> -->
                        <!-- <div class="form-group">
                            <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj','tSpaADDXphZneTo');?></label>
                            <div class="input-group">
                                <input name="oetZneChain" id="oetZneChain" class="form-control xCNHide" value="<?=$tXphZneTo?>">
                                <input name="oetZneName" id="oetZneName" class="form-control xWPointerEventNone xWRptConsCrdInput"  type="text" readonly="" value="<?=$tXphZneToName?>">
                                <span class="input-group-btn">
                                    <button class="btn xCNBtnBrowseAddOn" id="btnBrowseZone" type="button">
                                        <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                    </button>
                                </span>
                            </div>
                        </div> -->

                        <!-- <div class="form-group">
                            <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj','tSpaADDMerChante');?></label>
                            <div class="input-group">
                                <input name="oetMerCode" id="oetMerCode" class="form-control xCNHide" value="<?=$tMerCode?>">
                                <input name="oetMerName" id="oetMerName" class="form-control xWPointerEventNone xWRptConsCrdInput"  type="text" readonly="" value="<?=$tMerName?>">
                                <span class="input-group-btn">
                                    <button class="btn xCNBtnBrowseAddOn" id="btnBrowseMerchant" type="button">
                                        <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                    </button>
                                </span>
                            </div>
                        </div> -->

                        <div class="form-group">
                            <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj','tSpaADDPplCode');?></label>
                            <div class="input-group">
                                <input name="oetPplCode" id="oetPplCode" class="form-control xCNHide" value="<?=$tPplCode?>">
                                <input name="oetPplName" id="oetPplName" class="form-control xWPointerEventNone xWRptConsCrdInput"  type="text" readonly="" value="<?=$tPplName?>">
                                <span class="input-group-btn">
                                    <button class="btn xCNBtnBrowseAddOn" id="btnBrowsePdtPriList" type="button"
                                    <?php
                                    if($tCmpCode=='' || !FCNtGetBchInComp()){
                                        echo "disabled"; 
                                    }
                                    ?>
                                    >
                                        <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                    </button>
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj','tSpaADDXphDStart');?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNDatePicker xCNInputMaskDate xWRelationshipDate" id="oetXphDStart" name="oetXphDStart" autocomplete="off" value="<?=$dXphDStart; ?>" data-validate="<?=language('document/salepriceadj/salepriceadj', 'tSpaValidXphDStart'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtXphDStart" type="button" class="btn xCNBtnDateTime">
                                                <img src="<?= base_url().'/application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6 col-lg-6">
                                <div class="form-group" id="odvXphDStop">
                                    <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj','tSpaADDXphDStop');?></label>
                                    <div class="input-group">
                                        <input type="hidden"  id="oetCheckDate" name="oetCheckDate" value="">
                                        <input type="text" class="form-control xCNDatePicker xCNInputMaskDate xWRelationshipDate" id="oetXphDStop" name="oetXphDStop" autocomplete="off" value="<?=$dXphDStop; ?>" data-validate="<?=language('document/salepriceadj/salepriceadj', 'tSpaValidXphDStop'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtXphDStop" type="button" class="btn xCNBtnDateTime">
                                                <img src="<?= base_url().'/application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj','tSpaADDXphTStart');?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNTimePicker xCNInputMaskDate" id="oetXphTStart" name="oetXphTStart" autocomplete="off" value="<?=$tXphTStart; ?>" data-validate="<?=language('document/salepriceadj/salepriceadj', 'tSpaValidXphTStart'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtXphTStart" type="button" class="btn xCNBtnDateTime">
                                                <img src="<?= base_url().'/application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj','tSpaADDXphTStop');?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNTimePicker xCNInputMaskDate" id="oetXphTStop" name="oetXphTStop" autocomplete="off" value="<?=$tXphTStop; ?>" data-validate="<?=language('document/salepriceadj/salepriceadj', 'tSpaValidXphTStop'); ?>">
                                        <span class="input-group-btn">
                                            <button id="obtXphTStop" type="button" class="btn xCNBtnDateTime">
                                                <img src="<?= base_url().'/application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>

            <div class="panel panel-default" style="margin-bottom: 25px;"> 
                <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/salepriceadj/salepriceadj', 'tSpaADDDocRefTitle'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvDataRef" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDataRef" class="panel-collapse collapse" role="tabpanel" style="margin-top:10px;">
                    <div class="panel-body xCNPDModlue">

                        <div class="form-group">
                            <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj','tSpaADDXphName');?></label>
                            <input class="form-control" type="text" id="oetXphName" name="oetXphName" value="<?=$tXphName?>">
                        </div>

                        <div class="form-group">
                        <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj','tSpaADDXphRefInt');?></label>
                            <input class="form-control" type="text" id="oetXphRefInt" name="oetXphRefInt" value="<?=$tXphRefInt?>">
                        </div>

                        <div class="form-group">
                            <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj','tSpaADDXphRefIntDate');?></label>
                            <div class="input-group">
                                <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetXphRefIntDate" name="oetXphRefIntDate" autocomplete="off" value="<?=$dXphRefIntDate?>">
                                <span class="input-group-btn">
                                    <button id="obtXphRefIntDate" type="button" class="btn xCNBtnDateTime">
                                        <img src="<?= base_url().'/application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                                    </button>
                                </span>
                            </div>
                        </div>

                    </div>    
                </div>
            </div>

            <div class="panel panel-default" style="margin-bottom: 25px;"> 
                <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/salepriceadj/salepriceadj', 'tSpaADDOtherTitle'); ?></label>
                    <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvDataOther" aria-expanded="true">
                        <i class="fa fa-plus xCNPlus"></i>
                    </a>
                </div>
                <div id="odvDataOther" class="panel-collapse collapse" role="tabpanel" style="margin-top:10px;">
                    <div class="panel-body xCNPDModlue">

                        <!-- <div class="form-group">
                            <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj','tSpaADDAggCode');?></label>
                            <div class="input-group">
                                <input name="oetAggCode" id="oetAggCode" class="form-control xCNHide" value="<?=$tAggCode?>">
                                <input name="oetAggName" id="oetAggName" class="form-control xWPointerEventNone xWRptConsCrdInput"  type="text" readonly="" value="<?=$tAggName?>">
                                <span class="input-group-btn">
                                    <button class="btn xCNBtnBrowseAddOn" id="btnBrowseAgency" type="button">
                                        <img src="<?=base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                    </button>
                                </span>
                            </div>
                        </div> -->

                        <div class="form-group">

                        <input type="hidden" id="ocmXphPriType" name="ocmXphPriType" value="1" >        
                        
<!--                         
                            <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj','tSpaADDXphPriType');?></label>
                            <select class="selectpicker form-control" id="ocmXphPriType" name="ocmXphPriType" maxlength="1">
                                <option value="1" <?=$tXphPriType == "1" ? "selected" : "";?>><?=language('document/salepriceadj/salepriceadj', 'tPdtPriTBPriceRet'); ?></option>
                                <option value="2" <?=$tXphPriType == "2" ? "selected" : "";?>><?=language('document/salepriceadj/salepriceadj', 'tPdtPriTBPriceWhs'); ?></option>
                                <option value="3" <?=$tXphPriType == "3" ? "selected" : "";?>><?=language('document/salepriceadj/salepriceadj', 'tPdtPriTBPriceNet'); ?></option>
                            </select>-->
                        </div> 

                        <div class="form-group">
                            <label class="fancy-checkbox">
                                <input type="checkbox" class="ocbListItem" id="ocbXphStaDocAct" name="ocbXphStaDocAct" maxlength="1" value="1" <?=$nXphStaDocAct == '' ? 'checked' : $nXphStaDocAct == '1' ? 'checked' : '0'; ?> >
                                <span class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj', 'tSpaADDXphStaDocAct'); ?></span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="xCNLabelFrm"><?=language('document/salepriceadj/salepriceadj', 'tSpaADDXphRmk'); ?></label>
                            <textarea class="form-control" maxlength="100" rows="4" id="otaXphRmk" name="otaXphRmk"><?=$tXphRmk?></textarea>
                        </div>


                    </div>    
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="panel panel-default" style="margin-bottom: 25px;"> 
                <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                    <label class="xCNTextDetail1"><?php echo language('document/salepriceadj/salepriceadj', 'tSpaTitlePdtPriList'); ?></label>
                </div>
                <div id="odvDataOther" class="panel-collapse collapse in" role="tabpanel" style="margin-top:10px;">
                    <div class="panel-body xCNPDModlue">
                        
                        <div class="row">
                            <div class="col-xs-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('product/pdtsize/pdtsize','tPSZSearch')?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="oetSearchSpaPdtPri" name="oetSearchSpaPdtPri" placeholder="<?php echo language('product/pdtsize/pdtsize','tPSZSearch')?>">
                                        <span class="input-group-btn">
                                            <button id="oimSearchSpaPdtPri" class="btn xCNBtnSearch" type="button">
                                                <img class="xCNIconAddOn" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-6 col-lg-6 text-right" style="margin-top:25px;">
                                <div id="odvMngTableList" class="btn-group xCNDropDrownGroup">
                                    <button type="button" class="btn xCNBTNMngTable" style="margin-right:10px;" onclick="JSxOpenColumnFormSet()">
                                        <?= language('common/main/main','tModalAdvTable')?>                    
                                    </button>
                                </div>
                                <div id="odvMngTableList" class="btn-group xCNDropDrownGroup">
                                    <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                                        <?php echo language('common/main/main','tCMNOption')?>
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li id="oliBtnDeleteAll" class="disabled">
                                            <a data-toggle="modal" data-target="#odvModalDelSpaPdtPri"><?php echo language('common/main/main','tDelAll')?></a>
                                        </li>
                                    </ul>
                                </div>
                                <button id="obtAddPdt" name="obtAddPdt" class="xCNBTNPrimeryPlus" type="button" style="margin-left:10px;margin-top: 0px;">+</button>
                            </div>
                        </div>
                        
                        <section id="ostDataPdtPri"></section>

                    </div>
                </div>
            </div>

        </div>

    </div>
</form>

<div class="modal fade xCNModalApprove" id="odvSPAPopupApv">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="xCNHeardModal modal-title" style="display:inline-block"><?php echo language('common/main/main','tApproveTheDocument'); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <p><?php echo language('common/main/main','tMainApproveStatus'); ?></p>
	                <ul>
        	            <li><?php echo language('common/main/main','tMainApproveStatus1'); ?></li>
                        <li><?php echo language('common/main/main','tMainApproveStatus2'); ?></li>
                        <li><?php echo language('common/main/main','tMainApproveStatus3'); ?></li>
                        <li><?php echo language('common/main/main','tMainApproveStatus4'); ?></li>
                    </ul>
                <p><?php echo language('common/main/main','tMainApproveStatus5'); ?></p>
                <p><strong><?php echo language('common/main/main','tMainApproveStatus6'); ?></strong></p>
			</div>
			<div class="modal-footer">
                <button id="obtSalePriAdjPopupApvConfirm" type="button" class="btn xCNBTNPrimery">
					<?php echo language('common/main/main', 'tModalConfirm'); ?>
				</button>
				<button type="button" class="btn xCNBTNDefult" data-dismiss="modal">
					<?php echo language('common/main/main', 'tModalCancel'); ?>
				</button>
			</div>
		</div>
	</div>
</div>

<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>

<script type="text/javascript">
var nLangEdits = <?=$this->session->userdata("tLangEdit")?>;
var tUsrBchCode = $('#oetBchCode').val();
var tFTZneChain = "";

$('#oimSearchSpaPdtPri').click(function(){
    JCNxOpenLoading();
    JSvSpaPdtPriDataTable();
});

$('#oetSearchSpaPdtPri').keypress(function(event){
    if(event.keyCode == 13){
        event.preventDefault();
        JCNxOpenLoading();
        JSvSpaPdtPriDataTable();
    }
});

//Added by Napat(Jame) 15/11/2562
//แก้ปัญหาเวลากด enter แล้วชอบเด้งไปปุ่ม submit
$(":input").keypress(function(event){
    if(event.keyCode == 13){
        event.preventDefault();
    }
});

$(document).ready(function(){

    JSxCheckSwitchDocType();

    $('#oetXphDocNo').attr('readonly',true);

    /*===========================================================================*/
    var tLangCode = $("#ohdLangEdit").val();
    var tUsrBchCode = $("#oetBchCode").val();
    var tUsrApv     = $("#oetXthApvCodeUsrLogin").val();
    var tStaPrcDoc = $("#oetStaPrcDoc").val();
    var tUsrCode = $("#oetUsrCode").val();
    var tDocNo = $("#oetXphDocNo").val();
    var tPrefix = 'RESAJP';
    var tStaDelMQ = $("#oetStaDelQname").val();
    var tStaApv = $("#oetStaApv").val();
    var tQName = tPrefix + '_' + tDocNo + '_' +tUsrApv;

    // MQ Message Config
    var poDocConfig = {
        tLangCode: tLangCode,
        tUsrBchCode: tUsrBchCode,
        tUsrApv: tUsrApv,
        tDocNo: tDocNo,
        tPrefix: tPrefix,
        tStaDelMQ: tStaDelMQ,
        tStaApv: tStaApv,
        tQName: tQName
    };

    var poMqConfig = {
        host: 'ws://' + oSTOMMQConfig.host + ':15674/ws',
        username: oSTOMMQConfig.user,
        password: oSTOMMQConfig.password,
        vHost: oSTOMMQConfig.vhost
    };

    // Callback Page Control(function)
    var poCallback = {
        tCallPageEdit: 'JSvCallPageSpaEdit',
        tCallPageList: 'JSvCallPageSpaList'
    };

    // Update Status For Delete Qname Parameter
    var poUpdateStaDelQnameParams = {
        ptDocTableName : "TCNTPdtAdjPriHD",
        ptDocFieldDocNo: "FTXphDocNo",
        ptDocFieldStaApv: "FTXphStaPrcDoc",
        ptDocFieldStaDelMQ: "FTXphStaDelMQ",
        ptDocStaDelMQ: "1",
        ptDocNo : tDocNo    
    };

    //if ((JCNbCardShiftOutIsUpdatePage() && JSbSPAIsStaApv('2')) && (tUsrCode == tUsrApv)) {
    // if ((tDocNo != "" && tStaPrcDoc == "2") && (tUsrCode == tStaApv)){ // 2 = Processing and user approved
    // if ((tDocNo != "" && tStaPrcDoc == "2") && (tUsrCode == tStaApv)){
    // alert('tStaApv: ' + tStaPrcDoc);

    // console.log('tDocNo'+tDocNo);
    // console.log('tStaPrcDoc'+tStaPrcDoc);
    // console.log('tUsrCode'+tUsrCode);
    // console.log('tStaApv'+tUsrApv);

    if((tDocNo != "" && tStaPrcDoc == "2") && (tUsrCode == tUsrApv)){
        FSxCMNRabbitMQMessage(poDocConfig, poMqConfig, poUpdateStaDelQnameParams, poCallback);
    }
    // else{

    //     alert('Not Show Mag \ntDocNo: ' + tDocNo + '\ntUsrCode: ' + tUsrCode + ' = ' + tStaApv + '\nJSbSPAIsStaApv: ' + tStaPrcDoc);

    // }


    if(tStaApv != "" && tStaDelMQ == ""){ // Qname removed ?
        // alert('Q Delete');
        // Delete Queue Name Parameter
        var poDelQnameParams = {
            ptPrefixQueueName: tPrefix,
            ptBchCode: "",
            ptDocNo: tDocNo,
            ptUsrCode: tStaApv
        };
        FSxCMNRabbitMQDeleteQname(poDelQnameParams);
        FSxCMNRabbitMQUpdateStaDeleteQname(poUpdateStaDelQnameParams);
    }
    // else{

    //     alert('Not Q Delete');

    // }
    /*===========================================================================*/

    // if(tUsrBchCode==""){ JSxGetBchComp(); } //Get Bch from Company

	$('.selectpicker').selectpicker();

    $('#obtAddPdt').click(function(){
        JSxCheckPinMenuClose(); // Hide Menu Pin
        JSvPDTBrowseList();
    });

    $('#ocbStaAutoGenCode').click(function(){
        JSxSPACheckAutoGenerate();
    });
    
    $('#obtBtnSpaCancel').click(function(){
        JSxSPAUpdateStaDocCancel();
    });
    

    $('#obtAdjAll').click(function(){
        JSxSpaAdjAll();
    });

	//DATE
	$('#obtXphDocDate').click(function(){
		event.preventDefault();
		$('#oetXphDocDate').datepicker('show');
    });
    $('#obtXphDStart').click(function(){
		event.preventDefault();
		$('#oetXphDStart').datepicker('show');
    });
    $('#obtXphDStop').click(function(){
		event.preventDefault();
		$('#oetXphDStop').datepicker('show');
    });
    $('#obtXphRefIntDate').click(function(){
		event.preventDefault();
		$('#oetXphRefIntDate').datepicker('show');
    });


    $('.xCNTimePicker').datetimepicker({
        format: 'HH:mm',
    });
    $('#obtXphDocTime').click(function(){
		event.preventDefault();
		$('#oetXphDocTime').datetimepicker('show');
    });
    $('#obtXphTStart').click(function(){
		event.preventDefault();
		$('#oetXphTStart').datetimepicker('show');
    });
    $('#obtXphTStop').click(function(){
		event.preventDefault();
		$('#oetXphTStop').datetimepicker('show');
    });

    JSxCheckDocType($('#ocmXphDocType').val());
    var dDateStop   = $('#ohdDateStop').val();
    $('#ocmXphDocType').change(function(){
        JSxCheckDocType(this.value);
        JSxCheckSwitchDocType();
        $('#oetXphDStop').val(dDateStop);
    });


    /*================= ตรวจสอบ DocType 18/10/2019 Saharat(GolF) ==================================*/
    function JSxCheckSwitchDocType(){
        var tXphDocType = $('#ocmXphDocType').val();
        var dXphDStart  = $('#oetXphDStart').val();
        switch (tXphDocType) {
            case "1":
                    $('#odvXphDStop').hide();
                    $('#oetCheckDate').val('1');
                break;
            case "2":
                    $('#odvXphDStop').show();
                    $('#oetCheckDate').val('2');
                    
            break;
        }
    }
    /*===========================================================================*/


    
    $('#btnBrowseZone').click(function(){ 
        var tBchTo = $('#oetXphBchTo').val();
        if(tBchTo!=""){
            oCmpBrowseZone.Where.Condition = [" AND FTZneRefCode = " + tBchTo + " AND FTZneTable = 'TCNMBranch'"];
        }else{
            oCmpBrowseZone.Where.Condition = [""];
        }
        JCNxBrowseData('oCmpBrowseZone');
    });

    $('#btnBrowseAgency').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose(); // Hide Menu Pin
            JCNxBrowseData('oCmpBrowseAgency');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $('#btnBrowseMerchant').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose(); // Hide Menu Pin
            JCNxBrowseData('oCmpBrowseMerchant');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $('#btnBrowseBranch').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose(); // Hide Menu Pin
            JCNxBrowseData('oCmpBrowseBranch');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    
    $('#btnBrowseMerChrant').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose(); // Hide Menu Pin
            JCNxBrowseData('oCmpBrowseMerChrant');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $('#btnBrowseShop').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose(); // Hide Menu Pin
            JCNxBrowseData('oCmpBrowseShop');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    
    $('#btnBrowsePdtPriList').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose(); // Hide Menu Pin
            JCNxBrowseData('oCmpBrowsePdtPriList');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    //เนลแก้ไขให้เลือกวันที่น้อยกว่าวันปัจจุบัน
    $('.xCNDatePicker').datepicker({
        format: 'yyyy-mm-dd',
	    autoclose: true,
        todayHighlight: true,
        startDate:'1900-01-01',
    });

    // $('.xCNStartDatePicker').datepicker({
    //     format: 'yyyy-mm-dd',
    //     autoclose: true,
    //     todayHighlight: true,
    //     startDate: new Date(),
    // });
    // $('.xCNStopDatePicker').datepicker({
    //     format: 'yyyy-mm-dd',
    //     autoclose: true,
    //     todayHighlight: true,
    //     startDate: new Date(),
    // });

    
    // $('.xWRelationshipDate').click(function(){
    //     // JSxSPACheckRelationshipDate(this.id);

    //     var dStart = $('#oetXphDStart').val();

    //     if(dStart != ""){
    //         console.log('1');
    //         $('.xCNStopDatePicker').datepicker('destroy');
            
    //         $('.xCNStopDatePicker').datepicker({
    //             format: 'yyyy-mm-dd',
    //             autoclose: true,
    //             todayHighlight: true,
    //             startDate: dStart,
    //         });
    //         // $('.xCNStopDatePicker').datepicker("refresh");
            
    //     }
    //     $(this.id).datepicker('show');

    // });

    // $('.xCNDatePicker').datepicker({
    //     format: 'yyyy-mm-dd',
	//     autoclose: true,
    //     todayHighlight: true,
    //     startDate: new Date(),
    // }).on('changeDate', function(e) {
    //     $('.xCNStopDatePicker').datepicker({
    //         format: 'yyyy-mm-dd',
    //         autoclose: true,
    //         startDate: e.date,
    //     });
    // });

    // $('.xCNStartDatePicker')
    // $('.xCNStopDatePicker').datepicker({
    //     format: 'yyyy-mm-dd',
    //     autoclose: true,
    //     todayHighlight: true,
    //     startDate: new Date(),
    // });

    // $('#oetXphDStart').data('DateTimePicker').getStartDate('03/14/2019');
    // $('#oetXphDStart').datepicker('getStartDate', '2019-03-14');
    
});

$('#obtBtnSpaApv').click(function(){
    JSxSPAApprove(false);
});
$('#obtSalePriAdjPopupApvConfirm').click(function(){
    $("#oetStaPrcDoc").val(2);
    JSxSPAApprove(true);
});

var oCmpBrowseZone = {
    Title : ['document/salepriceadj/salepriceadj','tSpaBRWZoneTitle'],
    Table:{Master:'TCNMZoneObj',PK:'FNZneID'},
    Join :{
        Table           : ['TCNMZone_L'],
        On              : ['TCNMZone_L.FTZneChain = TCNMZoneObj.FTZneChain AND TCNMZone_L.FNLngID = '+nLangEdits]
    },
    Where:{
        Condition : []
    },
    GrideView:{
        ColumnPathLang	: 'document/salepriceadj/salepriceadj',
        ColumnKeyLang	: ['tSpaBRWZoneTBCode','tSpaBRWZoneTBName'],
        ColumnsSize     : ['10%','75%'],
        DataColumns		: ['TCNMZone_L.FTZneCode','TCNMZone_L.FTZneName','TCNMZoneObj.FNZneID'],
        DisabledColumns : [2],
        DataColumnsFormat : ['',''],
        WidthModal      : 50,
        Perpage			: 10,
        OrderBy			: ['TCNMZoneObj.FNZneID'],
        SourceOrder		: "ASC"
    },
    CallBack:{
        ReturnType	: 'S',
        Value       : ["oetZneChain","TCNMZoneObj.FTZneChain"],
        Text		: ["oetZneName","TCNMZone_L.FTZneName"],
    },
    // DebugSQL : true
    // NextFunc:{
    //     FuncName:'JSxSetCondition',
    //     ArgReturn:['FTZneChain']
    // },
}

var oCmpBrowseBranch = {
    Title : ['document/salepriceadj/salepriceadj','tSpaBRWBranchTitle'],
    Table:{Master:'TCNMBranch',PK:'FTBchCode'},
    Join :{
        Table:	['TCNMBranch_L'],
        On:['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
    },
    GrideView:{
        ColumnPathLang	: 'document/salepriceadj/salepriceadj',
        ColumnKeyLang	: ['tSpaBRWBranchTBCode','tSpaBRWBranchTBName'],
        ColumnsSize     : ['10%','75%'],
        DataColumns		: ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
        DataColumnsFormat : ['',''],
        WidthModal      : 50,
        Perpage			: 10,
        OrderBy			: ['TCNMBranch.FTBchCode'],
        SourceOrder		: "ASC"
    },
    CallBack:{
        ReturnType	: 'S',
        Value       : ["oetXphBchTo","TCNMBranch.FTBchCode"],
        Text		: ["oetBchName","TCNMBranch_L.FTBchName"],
    },
    NextFunc: {
				FuncName: 'JSxSetAfterSElectBrach',
				ArgReturn: ['FTBchCode', 'FTBchName']
    },
    // DebugSQL : true
}
var tOldBranchSelect = $("#oetXphBchTo").val();
function JSxSetAfterSElectBrach(paBranchInfor){
    var aData = JSON.parse(paBranchInfor);
    if(aData[0]!=tOldBranchSelect){
        if($("#oetXphBchTo").val()!=""){
            $("#oetXphMerCode").val("");
            $("#oetMerName").val("");
        }
        tOldBranchSelect = aData[0];
    }
   
}

var oCmpBrowseMerChrant = {
    Title : ['merchant/merchant/merchant','tMerchantTitle'],
    Table:{Master:'TCNMMerchant_L',PK:'FTMerCode'},
    Where: {
            Condition: [
                function(){
                    var tSQL = "";
                    if($("#oetXphBchTo").val()!=""){
                        tSQL += "AND (SELECT COUNT(FTShpCode) FROM TCNMShop WHERE TCNMShop.FTShpStaActive = 1 AND TCNMShop.FTMerCode = TCNMMerchant_L.FTMerCode AND TCNMShop.FTBchCode = '" + $("#oetXphBchTo").val() + "') != 0";
                    }
                    return tSQL;
                }
            ]
    },
    GrideView:{
        ColumnPathLang	: 'merchant/merchant/merchant',
        ColumnKeyLang	: ['tMCNTBCode','tMCNTBName'],
        ColumnsSize     : ['10%','75%'],
        DataColumns		: ['TCNMMerchant_L.FTMerCode','TCNMMerchant_L.FTMerName'],
        DataColumnsFormat : ['',''],
        WidthModal      : 50,
        Perpage			: 10,
        OrderBy			: ['TCNMMerchant_L.FTMerCode'],
        SourceOrder		: "ASC"
    },
    CallBack:{
        ReturnType	: 'S',
        Value       : ["oetXphMerCode","TCNMMerchant_L.FTMerCode"],
        Text		: ["oetMerName","TCNMMerchant_L.FTMerName"],
    },
    // DebugSQL : true
}



// SELECT BCH.FTBchCode,BCH_L.FTBchName FROM TCNMZoneObj AS ZOJ 
// LEFT JOIN TCNMBranch BCH ON BCH.FTBchCode = ZOJ.FTZneRefID
// LEFT JOIN TCNMBranch_L BCH_L ON BCH_L.FTBchCode = BCH.FTBchCode
// WHERE LEFT(FTZneChain, 5) = '00002' AND FTZneTable = 'TCNMBranch'

// var oCmpBrowseBranchRef = {
//     Title : ['document/salepriceadj/salepriceadj','tSpaBRWBranchTitle'],
//     Table:{Master:'TCNMZoneObj',PK:'FNZneID'},
//     Join :{
//         Table:	['TCNMBranch','TCNMBranch_L'],
//         On:[
//             'TCNMBranch.FTBchCode = TCNMZoneObj.FTZneRefID',
//             'TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,
//         ]
//     },
//     Where:{
//         Condition : []
//     },
//     GrideView:{
//         ColumnPathLang	: 'document/salepriceadj/salepriceadj',
//         ColumnKeyLang	: ['tSpaBRWBranchTBCode','tSpaBRWBranchTBName'],
//         ColumnsSize     : ['10%','90%'],
//         DataColumns     : ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName','TCNMZoneObj.FNZneID'],
//         DisabledColumns : [2],
//         DataColumnsFormat : ['','',''],
//         WidthModal      : 50,
//         Perpage			: 10,
//         OrderBy			: ['TCNMBranch.FTBchCode'],
//         SourceOrder		: "ASC"
//     },
//     CallBack:{
//         ReturnType	: 'S',
//         Value       : ["oetXphBchTo","TCNMBranch.FTBchCode"],
//         Text		: ["oetBchName","TCNMBranch_L.FTBchName"],
//     },
//     NextFunc:{
//         FuncName:'JSxSetXphBchCode',
//         ArgReturn:['FTBchCode']
//     },
// }

var oCmpBrowsePdtPriList = {
    Title : ['document/salepriceadj/salepriceadj','tSpaBRWPdtPriListTitle'],
    Table:{Master:'TCNMPdtPriList',PK:'FTPplCode'},
    Join :{
        Table:	['TCNMPdtPriList_L'],
        On:['TCNMPdtPriList_L.FTPplCode = TCNMPdtPriList.FTPplCode AND TCNMPdtPriList_L.FNLngID = '+nLangEdits,]
    },
    GrideView:{
        ColumnPathLang	: 'document/salepriceadj/salepriceadj',
        ColumnKeyLang	: ['tSpaBRWPdtPriListTBCode','tSpaBRWPdtPriListTBName'],
        ColumnsSize     : ['15%','85%'],
        DataColumns		: ['TCNMPdtPriList.FTPplCode','TCNMPdtPriList_L.FTPplName'],
        DataColumnsFormat : ['',''],
        WidthModal      : 50,
        Perpage			: 10,
        OrderBy			: ['TCNMPdtPriList.FTPplCode'],
        SourceOrder		: "ASC"
    },
    CallBack:{
        ReturnType	: 'S',
        Value       : ["oetPplCode","TCNMPdtPriList.FTPplCode"],
        Text		: ["oetPplName","TCNMPdtPriList_L.FTPplName"],
    }
}

var oCmpBrowseAgency = {
    Title : ['document/salepriceadj/salepriceadj','tSpaBRWAgencyTitle'],
    Table:{Master:'TCNMAgencyGrp',PK:'FTAggCode'},
    Join :{
        Table:	['TCNMAgencyGrp_L'],
        On:['TCNMAgencyGrp_L.FTAggCode = TCNMAgencyGrp.FTAggCode AND TCNMAgencyGrp_L.FNLngID = '+nLangEdits,]
    },
    GrideView:{
        ColumnPathLang	: 'document/salepriceadj/salepriceadj',
        ColumnKeyLang	: ['tSpaBRWAgencyTBCode','tSpaBRWAgencyTBName'],
        ColumnsSize     : ['10%','75%'],
        DataColumns		: ['TCNMAgencyGrp.FTAggCode','TCNMAgencyGrp_L.FTAggName'],
        DataColumnsFormat : ['',''],
        WidthModal      : 50,
        Perpage			: 10,
        OrderBy			: ['TCNMAgencyGrp.FTAggCode'],
        SourceOrder		: "ASC"
    },
    CallBack:{
        ReturnType	: 'S',
        Value       : ["oetAggCode","TCNMAgencyGrp.FTAggCode"],
        Text		: ["oetAggName","TCNMAgencyGrp_L.FTAggName"],
    }
}

var oCmpBrowseMerchant = {
    Title : ['document/salepriceadj/salepriceadj','tSpaBRWMRCTitle'],
    Table:{Master:'TCNMMerchant',PK:'FTMerCode'},
    Join :{
        Table:	['TCNMMerchant_L'],
        On:['TCNMMerchant_L.FTMerCode = TCNMMerchant.FTMerCode AND TCNMMerchant_L.FNLngID = '+nLangEdits,]
    },
    GrideView:{
        ColumnPathLang	: 'document/salepriceadj/salepriceadj',
        ColumnKeyLang	: ['tSpaBRWMRCTBCode','tSpaBRWMRCTBName'],
        ColumnsSize     : ['10%','75%'],
        DataColumns		: ['TCNMMerchant.FTMerCode','TCNMMerchant_L.FTMerName'],
        DataColumnsFormat : ['',''],
        WidthModal      : 50,
        Perpage			: 10,
        OrderBy			: ['TCNMMerchant.FTMerCode'],
        SourceOrder		: "ASC"
    },
    CallBack:{
        ReturnType	: 'S',
        Value       : ["oetMerCode","TCNMMerchant.FTMerCode"],
        Text		: ["oetMerName","TCNMMerchant_L.FTMerName"],
    }
}



</script>