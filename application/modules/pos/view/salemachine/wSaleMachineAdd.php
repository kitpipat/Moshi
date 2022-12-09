<?php
    if(isset($nStaAddOrEdit) && $nStaAddOrEdit == 1){
        $tRoute             = "salemachineEventEdit";
        $tMenuTabDisable    = "";
        $tMenuTabToggle     = "tab";
        $tPosCode           = $aPosData['raItems']['rtPosCode'];
        $tPosName           = $aPosData['raItems']['rtPosName'];
        $tPosDocType        = $aPosData['raItems']['rtPosDocType'];
        $tPosType           = $aPosData['raItems']['rtPosType'];
        $tPosRegNo          = $aPosData['raItems']['rtPosRegNo'];
        $tPosStaPrnEJ       = $aPosData['raItems']['rtPosStaPrnEJ'];
        $tPosStaVatSend     = $aPosData['raItems']['rtPosStaVatSend'];
        $tPosStaUse         = $aPosData['raItems']['rtPosStaUse'];
        $tWahCode           = $aPosData['raItems']['rtWahCode'];
        $tWahName           = $aPosData['raItems']['rtWahName'];
        $tBchCode           = $aPosData['raItems']['rtBchCode'];
        $tBchName           = $aPosData['raItems']['rtBchName'];
        $tUsrBchCode        = $tUsrBchCode;
        $tStaUsrLevel       = $tStaUsrLevel;
        
        // GetDatalist SlipMessage
        // Create By Witsarut 09/09/2019
        $tSlipMsgCode       = $aPosData['raItems']['rtSmgCode'];
        $tSlipMsgName       = $aPosData['raItems']['rtSmgTitle'];

        if($aCodeBchShp['rtCode'] == 1){
            // $tBchCode       = $aCodeBchShp['raItems']['rtBchCode'];
            $tShpCode       = $aCodeBchShp['raItems']['rtShpCode'];
        }else{
            // $tBchCode       = "";
            $tShpCode       = "";
        }
    }else{
        $tRoute             = "salemachineEventAdd";
        $tMenuTabDisable    = " disabled xCNCloseTabNav";
        $tMenuTabToggle     = "false";
        $tPosCode           = "";
        $tPosName           = "";
        $tPosDocType        = "";
        $tPosType           = "";
        $tPosRegNo          = "";
        $tPosStaPrnEJ       = "";
        $tPosStaVatSend     = "";
        $tPosStaUse         = "";
        $tWahCode           = "";
        $tWahName           = "";
        // GetDatalist SlipMessage
        // Create By Witsarut 09/09/2019
        $tSlipMsgCode       = "";
        $tSlipMsgName       = "";
        $tBchCode           = "";
        $tShpCode           = "";
        $tStaUsrLevel       = $tStaUsrLevel;
        $tUsrBchCode        = $tUsrBchCode;
        $tBchName           = "";

        if($tStaUsrLevel != "HQ"){
            $tBchCode           = $tUsrBchCode;
            $tBchName           = $tUsrBchName;

        }
        
    }
?>
<div id="odvPosPanelBody" class="panel-body" style="padding-top:20px !important;">
    <div class="custom-tabs-line tabs-line-bottom left-aligned">
        <ul class="nav" role="tablist">
            <!-- Info Tab -->
            <li id="oliSaleMachineInTab" class="xCNPOSTab active" data-typetab="main" data-tabtitle="posinfo">
                <a role="tab" data-toggle="tab" data-target="#odvInforGeneralTap" aria-expanded="true">
                    <?php echo language('pos/salemachine/salemachine', 'tPageAddTabNameGeneral') ?> 
                </a>
            </li>
            <!-- อุปกรณ์ -->
            <li id="oliInforMachineTap" class="xCNPOSTab<?php echo @$tMenuTabDisable;?>" data-typetab="sub" data-tabtitle="posinfomachine">
                <a role="tab" data-toggle="<?php echo @$tMenuTabToggle;?>" data-target="#odvInforMachineTap" aria-expanded="true">
                    <?php echo language('pos/salemachine/salemachine','tPageAddTabNameMachine');?> 
                </a>
            </li>
            <!-- จัดการโฆษณา -->
            <li id="oliPosAds" class="xCNPOSTab<?php echo @$tMenuTabDisable;?>" data-typetab="sub" data-tabtitle="posads">
                <a role="tab" data-toggle="<?php echo @$tMenuTabToggle;?>" data-target="#odvInforPosAds" aria-expanded="true">
                    <?php echo language('pos/salemachine/salemachine','tPageAddTabPosAds');?> 
                </a>
            </li>
            <!-- ที่อยู่ -->
            <li id="oliSaleMachineAddress" class="xCNPOSTab<?php echo @$tMenuTabDisable;?>" data-typetab="sub" data-tabtitle="posaddress">
                <a role="tab" data-toggle="<?php echo @$tMenuTabToggle;?>" data-target="#odvPOSAddressData" aria-expanded="true">
                    <?php echo language('pos/salemachine/salemachine','tPageAddTabNameAddress');?>
                </a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="tab-content">
                <div class="tab-pane active" style="margin-top:10px;" id="odvInforGeneralTap" role="tabpanel" aria-expanded="true" >
                    <form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddSaleMachine">
                        <button style="display:none" type="submit" id="obtSubmitSaleMachine" onclick="JSoAddEditSaleMachine()"></button>
                        <input type="hidden" id="ohdBchCode" name="ohdBchCode" value="<?php echo $tBchCode?>">
                        <input type="hidden" id="ohdShpCode" name="ohdShpCode" value="<?php echo $tShpCode?>">
                        
                        <input type="hidden" id="ohdTMacFormRoute" name="ohdTMacFormRoute" value="<?php echo $tRoute;?>">
                        <div class="row">
                            <div class="col-xs-12 col-md-5 col-lg-5">
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span class="text-danger">*</span><?php echo language('pos/salemachine/salemachine','tPOSCode')?></label>
                                    <div id="odvPosAutoGenCode" class="form-group">
                                        <div class="validate-input">
                                            <label class="fancy-checkbox">
                                                <input type="checkbox" id="ocbPosAutoGenCode" name="ocbPosAutoGenCode" checked="true" value="1">
                                                <span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div id="odvPosCodeForm" class="form-group">
                                    <input type="hidden" id="ohdCheckPosClearValidate" name="ohdCheckPosClearValidate" value="1"> 
                                    <input type="hidden" id="ohdCheckPosValidate"      name="ohdCheckPosValidate"      value="1"> 
                                    <div class="validate-input">
                                        <input 
                                            type="text" 
                                            class="form-control xCNInputWithoutSpcNotThai xCNInputWithoutSingleQuote" 
                                            maxlength="5" 
                                            id="oetPosCode" 
                                            name="oetPosCode"
                                            value="<?php echo $tPosCode ?>"
                                            autocomplete="off"
                                            data-is-created="<?php echo $tPosCode ?>"
                                            placeholder="<?php echo language('pos/salemachine/salemachine','tPOSCode')?>"
                                            data-validate-required="<?php echo language('pos/salemachine/salemachine','tPOSValidCode')?>"
                                            data-validate-dublicateCode="<?php echo language('pos/salemachine/salemachine','tPOSValidCheckCode')?>"
                                        >
                                    </div>
                                </div>

                                <!--ชื่อเครื่องจุดขาย-->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span class="text-danger">*</span><?php echo language('pos/salemachine/salemachine','tPOSName')?></label>
                                    <input type="text" class="form-control" maxlength="20" id="oetPosName" name="oetPosName" value="<?php echo @$tPosName;?>" 
                                    data-validate-required="<?php echo language('pos/salemachine/salemachine','tPOSName');?>"
                                    placeholder="<?php echo language('pos/salemachine/salemachine','tPOSName');?>" autocomplete="off"
                                    >
                                </div>

                                <!-- Browse สาขา -->
                                <div class="form-group" id="odvWhaShop">
                                    <label class="xCNLabelFrm"><span class="text-danger">*</span><?php echo language('company/warehouse/warehouse','tBrowseBCHName')?></label>
                                    <?php if($tStaUsrLevel == "HQ") : ?>
                                        <div class="input-group">
                                            <input type="text" class="form-control xCNHide" id="oetPosBchCode" name="oetPosBchCode" value="<?=$tBchCode;?>">
                                            <input type="text" class="form-control xWPointerEventNone" id="oetPosBchName" name="oetPosBchName" value="<?=$tBchName;?>"
                                            data-validate-required="<?php echo language('pos/salemachine/salemachine','tPOSValidBach');?>" readonly
                                            >
                                            <span class="input-group-btn">
                                                <button id="oimPosBrowseBch" type="button" class="btn xCNBtnBrowseAddOn">
                                                    <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                                </button>
                                            </span>
                                            </div>
                                    <?php else : ?>
                                        <div class="form-group">
                                            <input type="text" class="form-control xCNHide" id="oetPosBchCode" name="oetPosBchCode" value="<?=$tBchCode;?>">
                                            <input class="form-control" type="text" id="oetPosBchName" name="oetPosBchName" maxlength="100"value="<?=$tBchName;?>" readonly
                                            data-validate-required="<?php echo language('pos/salemachine/salemachine','tPOSValidBach');?>"
                                            >
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span class="text-danger">*</span><?php echo language('pos/salemachine/salemachine','tPOSRegNo');?></label>
                                    <input type="text" class="form-control" maxlength="20" id="oetPosRegNo" name="oetPosRegNo" value="<?php echo $tPosRegNo;?>" 
                                    data-validate-required="<?php echo language('pos/salemachine/salemachine','tPOSValidRegNo');?>"
                                    placeholder="<?php echo language('pos/salemachine/salemachine','tPOSRegNo');?>" autocomplete="off"
                                    >
                                </div>

                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?=language('pos/salemachine/salemachine','tPOSType')?></label>
                                    <select class="selectpicker form-control" id="ocmPosType" name="ocmPosType" data-live-search="true">
                                            <?php echo $tPosOptionType;?>
                                    </select>
                                </div> 

                                <div class="form-group" id="odvWarehouseForm"
                                    <?php 
                                    if($tRoute=='salemachineEventAdd'){
                                            echo "style=\"display:none;\"";
                                    }else{
                                        if($tPosType!="4"){
                                            echo "style=\"display:none;\"";
                                        }
                                    }
                                    ?>>
                                    <label class="xCNLabelFrm"><?php echo  language('company/branch/branch','tBCHWarehouse')?></label>
                                    <div class="input-group">
                                        <input class="form-control xCNHide" id="oetBchWahCodeOld" name="oetBchWahCodeOld" maxlength="5" value="<?php echo $tWahCode?>">
                                        <input class="form-control xCNHide" id="oetBchWahCode" name="oetBchWahCode" maxlength="5" value="<?php echo $tWahCode?>">
                                        <input class="form-control xWPointerEventNone" type="text" id="oetBchWahName" name="oetBchWahName" value="<?php echo $tWahName?>" readonly
                                        data-validate-required="<?php echo  language('company/branch/branch','tPOSValidWahName')?>">
                                        <span class="input-group-btn">
                                            <button id="obtBchBrowseWah" type="button" class="btn xCNBtnBrowseAddOn">
                                                <img src="<?php echo  base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <!-- Create By Witsarut   09/06/2019 -->
                                <!-- เพิ่ม Browse หัวท้ายใบเสร็จ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('pos/slipMessage/slipmessage','tSMGTitle')?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control xCNHide" id="oetSmgCode" name="oetSmgCode" value="<?php echo $tSlipMsgCode; ?>" data-validate="<?php echo  language('pos/salemachine/salemachine','tSMGValiSlipMessageCode');?>">
                                        <input type="text" class="form-control xCNInputWithoutSpcNotThai" id="oetSmgTitle" name="oetSmgTitle" value="<?php echo $tSlipMsgName; ?>" data-validate="<?php echo  language('pos/salemachine/salemachine','tSMGValiSlipMessageName');?>" readonly>
                                        <span class="input-group-btn">
                                            <button id="obtSlipmessage" type="button" class="btn xCNBtnBrowseAddOn">
                                                <img src="<?php echo base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" name="ocbPOSStaPrnEJ" <?php 
                                        if($tRoute=="salemachineEventAdd"){
                                            echo "checked"; 
                                        }else{
                                            echo ($tPosStaPrnEJ == '1') ? "checked" : ''; 
                                        } ?> value="1">
                                        <span> <?php echo language('pos/salemachine/salemachine','tPOSStaPrnEJ'); ?></span>
                                    </label>
                                </div>

                                <div class="form-group" style="display:none;">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" name="ocbPosStaVatSend" <?php 
                                        if($tRoute=="salemachineEventAdd"){
                                            echo "checked"; 
                                        }else{
                                            echo ($tPosStaVatSend == '1') ? "checked" : ''; 
                                        } ?>
                                        value="1">
                                        <span> <?php echo language('pos/salemachine/salemachine','tPOSStaVatSend'); ?></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" name="ocbPosStaUse" 
                                        <?php 
                                        if($tRoute=="salemachineEventAdd"){
                                            echo "checked"; 
                                        }else{
                                            echo ($tPosStaUse == '1') ? "checked" : ''; 
                                        } ?>
                                        value="1">
                                        <span> <?php echo language('pos/salemachine/salemachine','tPOSStaUse'); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" style="margin-top:10px;" id="odvPOSAddressData" role="tabpanel" aria-expanded="true" >
                </div>
                <div class="tab-pane" style="margin-top:10px;" id="odvInforMachineTap" role="tabpanel" aria-expanded="true" >
                    <?php
                    if($tRoute=="salemachineEventEdit"){
                    ?>
                        <div id="odvMachineControlPage">
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <ol id="oliMenuNav" class="breadcrumb">
                                        <li id="oliPhwTitle" class="xCNLinkClick" onclick="JSvSaleMachineDeviceDataTable(1,'<?=$tPosCode ?>');" style="cursor:pointer"><?php echo language('pos/salemachine/salemachine','tPOSTitleDevice')?></li>
                                        <li id="oliPhwTitleEdit" class="active"><a><?php echo language('pos/salemachine/salemachine','tPOSTitleEdit')?></a></li>
                                        <li id="oliPhwTitleAdd" class="active"><a><?php echo language('pos/salemachine/salemachine','tPOSTitleAdd')?></a></li>
                                    </ol>
                                </div>
                                <div class="col-xs-12 col-md-8 text-right">
                                    <div id="odvBtnMacPhwInfo">
                                        <button class="xCNBTNPrimeryPlus" type="button" onclick="JSvCallPageSaleMachineDeviceAdd()">+</button>
                                    </div>
                                    <div id="odvBtnMacAddEdit">

                                        <button type="button" onclick="JSvSaleMachineDeviceDataTable(1,'<?=$tPosCode ?>');" class="btn" style="background-color: #D4D4D4; color: #000000;">
                                                <?php echo language('company/shopgpbypdt/shopgpbypdt', 'tSGPPBTNCancel')?>
                                            </button>
                                            <button type="submit" class="btn" style="background-color: rgb(23, 155, 253); color: white;" onclick="JSxSetStatusClickPhwSubmit();$('#obtSubmitSaleMachineDevice').click()"> 
                                                <?php echo  language('common/main/main', 'tSave')?>
                                            </button>
           
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group" id="odvBtnMacPhwSearch">
                                        <label class="xCNLabelFrm"><?php echo language('common/main/main','tSearchNew')?></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control xCNInputWithoutSpc" id="oetSearchSaleMachineDevice" name="oetSearchSaleMachineDevice" autocomplete="off"  placeholder="<?php echo language('common/main/main','tPlaceholder'); ?>">
                                            <span class="input-group-btn">
                                                <button class="btn xCNBtnSearch" type="button" id="obtSearchSaleMachineDevice" name="obtSearchSaleMachineDevice">
                                                    <img class="xCNIconBrowse" src="<?php echo base_url().'/application/modules/common/assets/images/icons/search-24.png'?>">
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-8 text-right">
                                    <div class="text-right" style="width:100%">
                                        <div id="odvMngTableList" class="btn-group xCNDropDrownGroup">
                                            <button type="button" class="btn xCNBTNMngTable" data-toggle="dropdown">
                                                <?php echo language('common/main/main','tCMNOption')?>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li id="oliBtnDeleteAll" class="disabled">
                                                <a data-toggle="modal" data-target="#odvModalDelSaleMachineDevice"><?php echo language('common/main/main','tDelAll')?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="odvMachineContentPage">
                        
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <!-- Tab PosAdsData  -->
                <div class="tab-pane" style="margin-top:10px;" id="odvInforPosAds" role="tabpanel" aria-expanded="true" >
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Baud Rate -->
<div class="modal fade" id="odvModalBaudRate">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?=language('pos/salemachine/salemachine', 'tInsertBaudRate')?></label>
			</div>
			<div class="modal-body">

                <!--Baud rate-->
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('pos/salemachine/salemachine','tInputBaudrate')?></label>
                    <input type="text" class="form-control" maxlength="50" id="oetBaudrate" name="oetBaudrate">
                </div>

                <!--Parity-->
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('pos/salemachine/salemachine','tInputParity')?></label>
                    <input type="text" class="form-control" maxlength="50" id="oetParity" name="oetParity">
                </div>

                <!--Length-->
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('pos/salemachine/salemachine','tInputLength')?></label>
                    <input type="text" class="form-control" maxlength="50" id="oetLength" name="oetLength">
                </div>

                <!--Stop bits-->
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('pos/salemachine/salemachine','tInputStopbits')?></label>
                    <input type="text" class="form-control" maxlength="50" id="oetStopbits" name="oetStopbits">
                </div>

                <!--Flow control-->
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('pos/salemachine/salemachine','tInputFlowcontrol')?></label>
                    <input type="text" class="form-control" maxlength="50" id="oetFlowcontrol" name="oetFlowcontrol">
                </div>

			</div>
			<div class="modal-footer">
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSxInsertTextBaudRate();"><?=language('common/main/main', 'tModalConfirm')?></button>
        		<button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include 'script/jSaleMachineAdd.php'; ?>

<script>

    <?php
    if($tRoute=="salemachineEventAdd"){
    ?>
        $.get("http://ip-api.com/json", function (response) {
            $("#oetPosCountry").val(response.country);
        }, "jsonp");
    <?php
    }
    ?>
    // $('#obtGenCodeSaleMachine').click(function(){
    //     JStGenerateSaleMachineCode();
    // });
    $('#oimPosBrowseBch').click(function(){JCNxBrowseData('oBrowseBch')});

    $('#ocmPosDocType').selectpicker();
    $('#ocmPosType').selectpicker();

    var nLangEdits = <?php echo $this->session->userdata("tLangEdit")?>;


    // Add warehouse
    var oBchBrowseWah = {
        Title : ['company/warehouse/warehouse','tWAHTitle'],
        Table:{Master:'TCNMWaHouse',PK:'FTWahCode'},
        Join :{
            Table:	['TCNMWaHouse_L'],
            On:['TCNMWaHouse_L.FTWahCode = TCNMWaHouse.FTWahCode AND TCNMWaHouse_L.FTBchCode = TCNMWaHouse.FTBchCode  AND TCNMWaHouse_L.FNLngID = '+nLangEdits,]
        },
        Where :{
            Condition : [
                
                function JSxConditionConfig(){
                    var tSQL = "AND TCNMWaHouse.FTWahStaType = '6' AND ((TCNMWaHouse.FTWahRefCode = '' OR TCNMWaHouse.FTWahRefCode IS NULL)";
                    if($("#oetBchWahCode").val()!=""){
                        tSQL += " OR TCNMWaHouse.FTWahCode = '"+$("#oetBchWahCode").val()+"' )";
                    }else{
                        tSQL += ")";
                    }

                    var tBchCode = $('#oetPosBchCode').val();
                    tSQL += " AND TCNMWaHouse.FTBchCode = '" + tBchCode + "'";

                    return tSQL;
                }
            ]
        },
        GrideView:{
            ColumnPathLang	: 'company/warehouse/warehouse',
            ColumnKeyLang	: ['tWahCode','tWahName'],
            ColumnsSize     : ['15%','75%'],
            WidthModal      : 50,
            DataColumns		: ['TCNMWaHouse.FTWahCode','TCNMWaHouse_L.FTWahName'],
            DataColumnsFormat : ['',''],
            Perpage			: 5,
            OrderBy			: ['TCNMWaHouse.FTWahCode'],
            SourceOrder		: "ASC"
        },
        CallBack:{
            ReturnType	: 'S',
            Value		: ["oetBchWahCode","TCNMWaHouse.FTWahCode"],
            Text		: ["oetBchWahName","TCNMWaHouse_L.FTWahName"],
        },
        NextFunc:{
            FuncName:'JSxValidFormAddEditSaleMachineTapGeneral',
            ArgReturn:[]
        },
        RouteFrom : 'salemachine',
        RouteAddNew : 'warehouse',
        BrowseLev : nStaPosBrowseType,
        //DebugSQL : true
    }


    // //Option Area
    // var oBchBrowseArea = {
    //     Title : ['address/area/area','tARESubTitle'],
    //     Table:{Master:'TCNMArea',PK:'FTAreCode'},
    //     Join :{
    //         Table:	['TCNMArea_L'],
    //         On:['TCNMArea_L.FTAreCode = TCNMArea.FTAreCode AND TCNMArea_L.FNLngID = '+nLangEdits,]
    //     },
    //     GrideView:{
    //         ColumnPathLang	: 'address/area/area',
    //         ColumnKeyLang	: ['tARECode','tAREName'],
    //         ColumnsSize     : ['15%','75%'],
    //         WidthModal      : 50,
    //         DataColumns		: ['TCNMArea.FTAreCode','TCNMArea_L.FTAreName'],
    //         DataColumnsFormat : ['',''],
    //         Perpage			: 5,
    //         OrderBy			: ['TCNMArea.FTAreCode'],
    //         SourceOrder		: "ASC"
    //     },
    //     CallBack:{
    //         ReturnType	: 'S',
    //         Value		: ["oetBchAreCode","TCNMArea.FTAreCode"],
    //         Text		: ["oetBchAreName","TCNMArea_L.FTAreName"],
    //     },
    //     NextFunc:{
    //         FuncName:'JSxChekDisableAddress',
    //         ArgReturn:['FTAreCode',]
    //     },
    //     RouteAddNew : 'area',
    //     BrowseLev : 1
    // } 

    // //Option Zone
    // var oBchBrowseZone = {
    //     Title : ['address/zone/zone','tZNESubTitle'],
    //     Table:{Master:'TCNMZone',PK:'FTZneCode'},
    //     Join :{
    //         Table:	['TCNMZone_L'],
    //         On:['TCNMZone_L.FTZneCode = TCNMZone.FTZneCode AND TCNMZone_L.FNLngID = '+nLangEdits,]
    //     },
    //     Filter:{
    //         Selector:'oetBchAreCode',
    //         Table:'TCNMZone',
    //         Key:'FTAreCode'
    //     },
    //     GrideView:{
    //         ColumnPathLang	: 'address/zone/zone',
    //         ColumnKeyLang	: ['tZNECode','tZNEName'],
    //         ColumnsSize     : ['15%','75%'],
    //         WidthModal      : 50,
    //         DataColumns		: ['TCNMZone.FTZneCode','TCNMZone_L.FTZneName'],
    //         DataColumnsFormat : ['',''],
    //         Perpage			: 5,
    //         OrderBy			: ['TCNMZone.FTZneCode'],
    //         SourceOrder		: "ASC"
    //     },
    //     CallBack:{
    //         ReturnType	: 'S',
    //         Value		: ["oetBchZneCode","TCNMZone.FTZneCode"],
    //         Text		: ["oetBchZneName","TCNMZone_L.FTZneName"],
    //     },
    //     NextFunc:{
    //         FuncName:'JSxChekDisableAddress',
    //         ArgReturn:['FTZneCode',]
    //     },
    //     RouteAddNew : 'zone',
    //     BrowseLev : 1
    // }

    // Option Branch
    var oBrowseBch = {
        
        Title : ['company/branch/branch','tBCHTitle'],
        Table:{Master:'TCNMBranch',PK:'FTBchCode',PKName:'FTBchName'},
        Join :{
            Table:	['TCNMBranch_L'],
            On:['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
        },
        GrideView:{
            ColumnPathLang	: 'company/branch/branch',
            ColumnKeyLang	: ['tBCHCode','tBCHName'],
            ColumnsSize     : ['15%','75%'],
            WidthModal      : 50,
            DataColumns		: ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
            DataColumnsFormat : ['',''],
            Perpage			: 5,
            OrderBy			: ['TCNMBranch.FTBchCode'],
            SourceOrder		: "ASC"
        },
        CallBack:{
            ReturnType	: 'S',
            Value		: ["oetPosBchCode","TCNMBranch.FTBchCode"],
            Text		: ["oetPosBchName","TCNMBranch_L.FTBchName"],
        },
        RouteAddNew : 'branch',
        BrowseLev : 0

    }


    //Option Province
    var oBchBrowseProvince = {
        Title : ['address/province/province','tPVNTitle'],
        Table:{Master:'TCNMProvince',PK:'FTPvnCode'},
        Join :{
            Table:	['TCNMProvince_L'],
            On:['TCNMProvince_L.FTPvnCode = TCNMProvince.FTPvnCode AND TCNMProvince_L.FNLngID = '+nLangEdits,]
        },
        GrideView:{
            ColumnPathLang	: 'address/province/province',
            ColumnKeyLang	: ['tPVNCode','tPVNName'],
            ColumnsSize     : ['15%','75%'],
            WidthModal      : 50,
            DataColumns		: ['TCNMProvince.FTPvnCode','TCNMProvince_L.FTPvnName'],
            DataColumnsFormat : ['',''],
            Perpage			: 5,
            OrderBy			: ['TCNMProvince.FTPvnCode'],
            SourceOrder		: "ASC"
        },
        CallBack:{
            ReturnType	: 'S',
            Value		: ["oetAddV1PvnCode","TCNMProvince.FTPvnCode"],
            Text		: ["oetAddV1PvnName","TCNMProvince_L.FTPvnName"],
        },
        NextFunc:{
            FuncName:'JSxChekDisableAddress',
            ArgReturn:['FTPvnCode',]
        },
        RouteAddNew : 'province',
        BrowseLev : 0
    }

    //Option District
    var oBchBrowseDistrict = {
        Title : ['address/district/district','tDSTTitle'],
        Table:{Master:'TCNMDistrict',PK:'FTDstCode'},
        Join :{
            Table:	['TCNMDistrict_L'],
            On:['TCNMDistrict_L.FTDstCode = TCNMDistrict.FTDstCode AND TCNMDistrict_L.FNLngID = '+nLangEdits,]
        },
        Filter:{
            Selector:'oetAddV1PvnCode',
            Table:'TCNMDistrict',
            Key:'FTPvnCode'
        },
        GrideView:{
            ColumnPathLang	: 'address/district/district',
            ColumnKeyLang	: ['tDSTTBCode','tDSTTBName'],
            ColumnsSize     : ['15%','75%'],
            WidthModal      : 50,
            DataColumns		: ['TCNMDistrict.FTDstCode','TCNMDistrict_L.FTDstName'],
            DataColumnsFormat : ['',''],
            Perpage			: 5,
            OrderBy			: ['TCNMDistrict.FTDstCode'],
            SourceOrder		: "ASC"
        },
        CallBack:{
            ReturnType	: 'S',
            Value		: ["oetAddV1DstCode","TCNMDistrict.FTDstCode"],
            Text		: ["oetAddV1DstName","TCNMDistrict_L.FTDstName"],
        },
        NextFunc:{
            FuncName:'JSxChekDisableAddress',
            ArgReturn:['FTDstCode',]
        },
        RouteAddNew : 'district',
        BrowseLev : 0
    }

    //Option SubDistrict
    var oBchBrowseSubDistrict = {
        Title : ['address/subdistrict/subdistrict','tSDTTitle'],
        Table:{Master:'TCNMSubDistrict',PK:'FTSudCode'},
        Join :{
            Table:	['TCNMSubDistrict_L'],
            On:['TCNMSubDistrict_L.FTSudCode = TCNMSubDistrict.FTSudCode AND TCNMSubDistrict_L.FNLngID = '+nLangEdits,]
        },
        Filter:{
            Selector:'oetAddV1DstCode',
            Table:'TCNMSubDistrict',
            Key:'FTDstCode'
        },
        GrideView:{
            ColumnPathLang	: 'address/subdistrict/subdistrict',
            ColumnKeyLang	: ['tSDTTBCode','tSDTTBSubdistrict'],
            ColumnsSize     : ['15%','75%'],
            WidthModal      : 50,
            DataColumns		: ['TCNMSubDistrict.FTSudCode','TCNMSubDistrict_L.FTSudName'],
            DataColumnsFormat : ['',''],
            Perpage			: 5,
            OrderBy			: ['TCNMSubDistrict.FTSudCode'],
            SourceOrder		: "ASC"
        },
        CallBack:{
            ReturnType	: 'S',
            Value		: ["oetAddV1SubDistCode","TCNMSubDistrict.FTSudCode"],
            Text		: ["oetAddV1SubDistName","TCNMSubDistrict_L.FTSudName"],
        },
        NextFunc:{
            FuncName:'JSxChekDisableAddress',
            ArgReturn:['FTSudCode']
        },
        RouteAddNew : 'subdistrict',
        BrowseLev : 0
    }



    // SlipMessage (หัวท้ายใบเสร็จ)
      var  oSlipMessage = {
        Title : ['pos/slipMessage/slipmessage','tSMGTitle'],
        Table:{Master:'TCNMSlipMsgHD_L',PK:'FTSmgCode',PKName:'FTSmgTitle'},
        // Join :{
        //     Table:	['TCNMBranch_L'],
        //     On:['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID ='+nLangEdits,]
        // },
        GrideView:{
            ColumnPathLang	: 'company/branch/branch',
            ColumnKeyLang	: ['tSMGTBCode','tSMGTBName'],
            ColumnsSize     : ['15%','75%'],
            DataColumns		: ['TCNMSlipMsgHD_L.FTSmgCode','TCNMSlipMsgHD_L.FTSmgTitle'],
            DataColumnsFormat : ['',''],
            Perpage			: 5,
            OrderBy			: ['TCNMSlipMsgHD_L.FTSmgCode'],
            SourceOrder		: "ASC"
        },
        CallBack:{
            StaSingItem : '1',
            ReturnType	: 'S',
            Value		: ["oetSmgCode","TCNMSlipMsgHD_L.FTSmgCode"],
            Text		: ["oetSmgTitle","TCNMSlipMsgHD_L.FTSmgTitle"],
        },

        RouteAddNew : 'slipMessage',
        BrowseLev : 0,
    }

    // Browser SlipMessage
    $('#obtSlipmessage').click(function(){
        // Create By Witsarut 04/10/2019
            JSxCheckPinMenuClose();
        // Create By Witsarut 04/10/2019
        JCNxBrowseData('oSlipMessage');
    }); 

    $('#obtBchBrowseWah').click(function(){
        // Create By Witsarut 04/10/2019
            JSxCheckPinMenuClose();
        // Create By Witsarut 04/10/2019
        JCNxBrowseData('oBchBrowseWah');
    });

    $('#obtBchBrowseProvince').click(function(){
        // Create By Witsarut 04/10/2019
            JSxCheckPinMenuClose();
        // Create By Witsarut 04/10/2019
        JCNxBrowseData('oBchBrowseProvince');

    });

    $('#obtBchBrowseDistrict').click(function(){
         // Create By Witsarut 04/10/2019
            JSXCheckPinMenuClose();
        // Create By Witsarut 04/10/2019
        JCNxBrowseData('oBchBrowseDistrict');
    });

    $('#obtBchBrowseSubDistrict').click(function(){
        // Create By Witsarut 04/10/2019
            JSXCheckPinMenuClose();
        // Create By Witsarut 04/10/2019
        JCNxBrowseData('oBchBrowseSubDistrict');
    });


    // $('#obtBchBrowseZone').click(function(){JCNxBrowseData('oBchBrowseZone');});
    // $('#obtBchBrowseArea').click(function(){JCNxBrowseData('oBchBrowseArea');});
   
    function JSxChekDisableAddress(){
        //tAreCode  = $('#oetBchAreCode').val();
        //tZneCode  = $('#oetBchZneCode').val();
        tPvnCode  = $('#oetAddV1PvnCode').val();
        tDstCode  = $('#oetAddV1DstCode').val();
        // if(tAreCode == '' || tAreCode == null){
        //     $('#obtBchBrowseProvince').prop('disabled',true);
        // }else{
        //     $('#obtBchBrowseProvince').prop('disabled',false);
        // }
        // // if(tZneCode == '' || tZneCode == null){
        //     $('#obtBchBrowseProvince').prop('disabled',true);
        // }else{
        //     $('#obtBchBrowseProvince').prop('disabled',false);
        // }
        if(tPvnCode == '' || tPvnCode == null){
            $('#obtBchBrowseDistrict').prop('disabled',true);
        }else{
            $('#obtBchBrowseDistrict').prop('disabled',false);
        }
        if(tDstCode == '' || tDstCode == null){
            $('#obtBchBrowseSubDistrict').prop('disabled',true);
        }else{
            $('#obtBchBrowseSubDistrict').prop('disabled',false);
        }
        JSxValidFormAddEditSaleMachineTapAddress();
    }

    function JSxResetVal(ptInputID,ptVal,pnSta){
        tInputID = $('#'+ptInputID).val();

        if(tInputID != ptVal && pnSta == 1){
           // $('#oetBchZneCode').val('');
            $('#oetAddV1PvnCode').val('');
            $('#oetAddV1DstCode').val('');
            $('#oetAddV1SubDistCode').val('');
            
            $('#oetBchZneName').val('');
            $('#oetAddV1PvnName').val('');
            $('#oetAddV1DstName').val('');
            $('#oetAddV1SubDistName').val('');
        }
        if(tInputID != ptVal && pnSta == 2){
            $('#oetAddV1PvnCode').val('');
            $('#oetAddV1DstCode').val('');
            $('#oetAddV1SubDistCode').val('');
            
            $('#oetAddV1PvnName').val('');
            $('#oetAddV1DstName').val('');
            $('#oetAddV1SubDistName').val('');
        }
        if(tInputID != ptVal && pnSta == 3){
            $('#oetAddV1DstCode').val('');
            $('#oetAddV1SubDistCode').val('');

            $('#oetAddV1DstName').val('');
            $('#oetAddV1SubDistName').val('');
        }
        if(tInputID != ptVal && pnSta == 4){
            $('#oetAddV1SubDistCode').val('');
            
            $('#oetAddV1SubDistName').val('');
        }
    }
</script>