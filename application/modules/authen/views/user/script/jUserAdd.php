<script type="text/javascript">

    // ตรวจสอบระดับของ User  12/03/2020 Saharat(Golf)
    var tStaUsrLevel    = '<?php  echo $this->session->userdata("tSesUsrLevel"); ?>';
    var tUsrBchCode     = '<?php  echo $this->session->userdata("tSesUsrBchCode"); ?>';
    var tUsrBchName     = '<?php  echo $this->session->userdata("tSesUsrBchName"); ?>';
    var tUsrShpCode     = '<?php  echo $this->session->userdata("tSesUsrShpCode"); ?>';
    var tUsrShpName     = '<?php  echo $this->session->userdata("tSesUsrShpName"); ?>';

$(document).ready(function(){

        $( "#oliUsrloginDetail" ).click(function() {
            $('#odvBtnAddEdit').show();
        });

        // ตรวจสอบระดับUser banch  12/03/2020 Saharat(Golf)
         if(tUsrBchCode != ""){ 
            $('#oetBranchCode').val(tUsrBchCode);
            $('#oetBranchName').val(tUsrBchName);
            $('#oimBrowseBranch').attr("disabled", true);
        }

        // ตรวจสอบระดับUser shop  12/03/2020 Saharat(Golf)
        if(tUsrShpCode != ""){ 
            $('#oetShopCode').val(tUsrShpCode);
            $('#oetShopName').val(tUsrShpName);
            $('#oimBrowseShop').attr("disabled", true);
        }

        $ShpCheck = $('#oetBranchCode').val();
        if($ShpCheck != '' && tUsrShpCode == "" ){
            $('#oimBrowseShop').prop('disabled',false);
        }else{
            $('#oimBrowseShop').prop('disabled',true);
        }


        
     
        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
        autoclose: true,
            todayHighlight: true,
            startDate: new Date()
        });
        $('.xWTooltipsBT').tooltip({'placement': 'bottom'});
        $('[data-toggle="tooltip"]').tooltip({'placement': 'top'});
        
        // Event Browse
        $('#oimBrowseDepart').click(function(){
            // Create By Witsarut 04/10/2019
                JSxCheckPinMenuClose();
            // Create By Witsarut 04/10/2019
            JCNxBrowseData('oBrowseDepart');
        });

        $('#oimBrowseBranch').click(function(){
            // Create By Witsarut 04/10/2019
                JSxCheckPinMenuClose();
            // Create By Witsarut 04/10/2019
            JCNxBrowseData('oBrowseBranch');
        });
        
        $('#oimBrowseShop').click(function(){
            // Create By Witsarut 04/10/2019
              JSxCheckPinMenuClose();
            // Create By Witsarut 04/10/2019
            JCNxBrowseData('oBrowseShop');
        });

        $('#obtUsrDateStart').click(function(event){
                $('#oetUsrDateStart').datepicker('show');
        });

        $('#obtUsrDateStop').click(function(event){
                $('#oetUsrDateStop').datepicker('show');
        });

        if(JSbUsrIsCreatePage()){
            // Usr Code
            $("#oetUsrCode").attr("disabled", true);
            $('#ocbUserAutoGenCode').change(function(){
                if($('#ocbUserAutoGenCode').is(':checked')) {
                    $('#oetUsrCode').val('');
                    $("#oetUsrCode").attr("disabled", true);
                    $('#odvUserCodeForm').removeClass('has-error');
                    $('#odvUserCodeForm em').remove();
                }else{
                    $("#oetUsrCode").attr("disabled", false);
                }
            });
            JSxUsrVisibleComponent('#odvUserAutoGenCode', true);
        }

        if(JSbUsrIsUpdatePage()){
            // Sale Person Code
            $("#oetUsrCode").attr("readonly", true);
            $('#odvUserAutoGenCode input').attr('disabled', true);
            JSxUsrVisibleComponent('#odvUserAutoGenCode', false);    
        }

        $('#oetUsrCode').blur(function(){
            JSxCheckUsrCodeDupInDB();
        });
});

// Lang Edit In Browse
var nLangEdits = <?php echo $this->session->userdata("tLangEdit")?>;
// Set Option Browse
// ************************************************************************************
        // Create By Witsarut 20/02/2020
        // กำหนดสิทธิ  1 สิทธิ์มีได้หลาย UserCode (Multi-select boxes)
        $('#oimBrowseRole').unbind().click(function(){
            var nStaSession  = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose();
                window.oRoleOPtion      = undefined;
                oRoleOPtion             = oBrowseRole({
                    'tReturnInputRoleCode'  : 'oetRoleCode',
                    'tReturnInputRoleName'  : 'oetRoleName',
                    'tNextFuncName'     : 'JSxConsNextFuncBrowseUsrRole',
                    'aArgReturn'        : ['FTRolCode','FTRolName']
                });

                JCNxBrowseMultiSelect('oRoleOPtion');
                // JCNxBrowseData('oRoleOPtion');
            }else{
                JCNxShowMsgSessionExpired();
            }
            
        });

        function JSxConsNextFuncBrowseUsrRole(poDataNextFunc){
            $('#odvUsrRoleShow').html('');
            if(typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL"){
                var tHtml = '';
                for($i=0; $i < poDataNextFunc.length; $i++ ){
                    var aText   = JSON.parse(poDataNextFunc[$i]);
                    tHtml       += '<span class="label label-info m-r-5">'+aText[1]+'</span>';
                }
                $('#odvUsrRoleShow').html(tHtml);
            }
          
        }

        // Option Browse Role
        var oBrowseRole = function(poReturnInputRole){
            let tInputReturnRoleCode    = poReturnInputRole.tReturnInputCode;
            let tInputReturnRoleName    = poReturnInputRole.tReturnInputName;
            let tRoleNextFunc           = poReturnInputRole.tNextFuncName;
            let aRoleArgReturn          = poReturnInputRole.aArgReturn;
            let oRoleOptionReturn       = {
                Title : ['authen/user/user','tBrowseROLTitle'],
                Table:{Master:'TCNMUsrRole',PK:'FTRolCode'},
                Join :{
                    Table:	['TCNMUsrRole_L'],
                    On:['TCNMUsrRole_L.FTRolCode = TCNMUsrRole.FTRolCode AND TCNMUsrRole_L.FNLngID = '+nLangEdits]
                },
                GrideView:{
                    ColumnPathLang	: 'authen/user/user',
                    ColumnKeyLang	: ['tBrowseROLCode','tBrowseROLName'],
                    ColumnsSize     : ['15%','75%'],
                    WidthModal      : 50,
                    DataColumns		: ['TCNMUsrRole.FTRolCode','TCNMUsrRole_L.FTRolName'],
                    DataColumnsFormat : ['',''],
                    Perpage         : 5,
                    OrderBy			: ['TCNMUsrRole.FTRolCode'],
                    SourceOrder		: "ASC" 
                    // DisabledColumns	: [0],
                },
                NextFunc : {
                    FuncName  : tRoleNextFunc,
                    ArgReturn : aRoleArgReturn
                },
                CallBack:{
                    Value		: ["oetRoleCode","TCNMUsrRole.FTRolCode"],
                    Text		: ["oetRoleName","TCNMUsrRole_L.FTRolName"],
                },

            };
            return oRoleOptionReturn;
        }


// ************************************************************************************

// Option Department
var oBrowseDepart = {
    Title : ['authen/user/user','tBrowseDPTTitle'],
    Table:{Master:'TCNMUsrDepart',PK:'FTDptCode'},
    Join :{
        Table:	['TCNMUsrDepart_L'],
        On:['TCNMUsrDepart_L.FTDptCode = TCNMUsrDepart.FTDptCode AND TCNMUsrDepart_L.FNLngID = '+nLangEdits]
    },
    GrideView:{
        ColumnPathLang	: 'authen/user/user',
        ColumnKeyLang	: ['tBrowseDPTCode','tBrowseDPTName'],
        DataColumns		: ['TCNMUsrDepart.FTDptCode','TCNMUsrDepart_L.FTDptName'],
        ColumnsSize     : ['10%','75%'],
        DataColumnsFormat : ['',''],
        WidthModal      : 50,
        Perpage			: 5,
		OrderBy			: ['TCNMUsrDepart.FTDptCode'],
		SourceOrder		: "ASC"
    },
    CallBack:{
        ReturnType	: 'S',
        Value		: ["oetDepartCode","TCNMUsrDepart.FTDptCode"],
		Text		: ["oetDepartName","TCNMUsrDepart_L.FTDptName"]
    },
    NextFunc:{
		FuncName:'JSxChekDisableAddress',
		ArgReturn:['FTDptCode',]
    },
    RouteAddNew : 'department',
    BrowseLev : nStaUsrBrowseType
};



var oBrowseBranch = {
    Title : ['authen/user/user','tBrowseBCHTitle'],
    Table:{Master:'TCNMBranch',PK:'FTBchCode'},
    Join :{
        Table:	['TCNMBranch_L'],
        On:['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits]
    },
    GrideView:{
        ColumnPathLang	: 'authen/user/user',
        ColumnKeyLang	: ['tBrowseBCHCode','tBrowseBCHName'],
        ColumnsSize     : ['10%','75%'],
        DataColumns	: ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
        DataColumnsFormat : ['',''],
        WidthModal      : 50,
        Perpage		: 10,
	OrderBy		: ['TCNMBranch.FTBchCode'],
	SourceOrder	: "ASC"
    },
    CallBack:{
        ReturnType	: 'S',
        Value		: ["oetBranchCode","TCNMBranch.FTBchCode"],
	Text		: ["oetBranchName","TCNMBranch_L.FTBchName"]
    },
    NextFunc:{
		FuncName:'JSxChekDisableAddress',
		ArgReturn:['FTBchCode',]
    },
    RouteAddNew : 'branch',
    BrowseLev : nStaUsrBrowseType
};

var oBrowseShop = {
    Title : ['authen/user/user','tBrowseSHPTitle'],
    Table:{Master:'TCNMShop',PK:'FTShpCode'},
    Join :{
        Table:	['TCNMShop_L'],
        On:['TCNMShop_L.FTShpCode = TCNMShop.FTShpCode AND TCNMShop.FTBchCode = TCNMShop_L.FTBchCode AND TCNMShop_L.FNLngID = '+nLangEdits]
    },
    Filter:{
		Selector:'oetBranchCode',
		Table:'TCNMShop',
        Key:'FTBchCode'
    },
    GrideView:{
        ColumnPathLang	: 'authen/user/user',
        ColumnKeyLang	: ['tBrowseSHPCode','tBrowseSHPName'],
        ColumnsSize     : ['10%','75%'],
        WidthModal      : 50,
        DataColumns		: ['TCNMShop.FTShpCode','TCNMShop_L.FTShpName'],
        DataColumnsFormat : ['',''],
        Perpage			: 10,
		OrderBy			: ['TCNMShop.FTShpCode'],
		SourceOrder		: "ASC"
    },
    CallBack:{
        StaSingItem : '1',
        ReturnType	: 'S',
        Value		: ["oetShopCode","TCNMShop.FTShpCode"],
		Text		: ["oetShopName","TCNMShop_L.FTShpName"]
    },
    // NextFunc:{
	// 	FuncName:'JSxChekDisableAddress',
	// 	ArgReturn:['FTShpCode',]
    // },
    RouteAddNew : 'shop',
    BrowseLev : nStaUsrBrowseType
};

    function JSxChekDisableAddress(paTest){
        tBchCode    = $('#oetBranchCode').val();
        tShpCode    = $('#oetShopCode').val();
        $('#oetShopCode').val('');
        $('#oetShopName').val('');
        if(tBchCode == '' || tBchCode == null){
            $('#oimBrowseShop').prop('disabled',true);
        }else{
            $('#oimBrowseShop').prop('disabled',false);
		}
    }


    //Functionality: Set Validate Event Blur
    //Parameters: Validate Event Blur
    //Creator: 26/03/2019 wasin (Yoshi)
    //Return: -
    //ReturnType: -
    function JSxUsrSetValidEventBlur(){
        $('#ofmAddEditUser').validate().destroy();

        // Set Validate Dublicate Code
        $.validator.addMethod('dublicateCode', function(value, element) {
            if($("#ohdCheckDuplicateUsrCode").val() == 1){
                return false;
            }else{
                return true;
            }
        },'');

        // From Summit Validate
        $('#ofmAddEditUser').validate({
            rules: {
                oetUsrCode : {
                    "required" :{
                        // ตรวจสอบเงื่อนไข validate
                        depends: function(oElement) {
                            if($('#ocbUserAutoGenCode').is(':checked')){
                                return false;
                            }else{
                                return true;
                            }
                        }
                    },
                    "dublicateCode" :{}
                },
            },

            messages: {
                oetUsrCode : {
                    "required"      : $('#oetUsrCode').attr('data-validate-required'),
                    "dublicateCode" : $('#oetUsrCode').attr('data-validate-dublicateCode')
                }
           
            },
            errorElement: "em",
            errorPlacement: function (error, element ) {
                error.addClass( "help-block" );
                if ( element.prop( "type" ) === "checkbox" ) {
                    error.appendTo( element.parent( "label" ) );
                } else {
                    var tCheck = $(element.closest('.form-group')).find('.help-block').length;
                    if(tCheck == 0){
                        error.appendTo(element.closest('.form-group')).trigger('change');
                    }
                }
            },
            highlight: function ( element, errorClass, validClass ) {
                $( element ).closest('.form-group').addClass( "has-error" ).removeClass( "has-success" );
            },
            unhighlight: function(element, errorClass, validClass) {
                var nStaCheckValid  = $(element).parents('.form-group').find('.help-block').length
                if(nStaCheckValid != 0){
                    $(element).closest('.form-group').addClass( "has-success" ).removeClass( "has-error" );
                }
            },
            submitHandler: function(form){}
        });
    }




   //Functionality: Event Check Sale Person Duplicate
    //Parameters: Event Blur Input Sale Person Code
    //Creator: 25/03/2019 wasin (Yoshi)
    //Return: -
    //ReturnType: -
    function JSxCheckUsrCodeDupInDB(){
        if(!$('#ocbUserAutoGenCode').is(':checked')){
            $.ajax({
                type: "POST",
                url: "CheckInputGenCode",
                data: { 
                    tTableName: "TCNMUser",
                    tFieldName: "FTUsrCode",
                    tCode: $("#oetUsrCode").val()
                },
                async : false,
                cache: false,
                timeout: 0,
                success: function(tResult){
                    var aResult = JSON.parse(tResult);
                    $("#ohdCheckDuplicateUsrCode").val(aResult["rtCode"]);
                    JSxUsrSetValidEventBlur();
                    $('#ofmAddEditUser').submit();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }


    // CML = CourierMan Login
    function JSxUsrloginGetContent(){
        var tRoutepage = '<?=$tRoute?>';

        if(tRoutepage == 'userEventAdd'){
            return;
        }else{
            var ptUsrCode    =  '<?php echo $tUsrCode;?>';

            // Check Login Expried
            var nStaSession = JCNxFuncChkSessionExpired();

            // If has Session 
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                $("#odvUsrloginContentInfoDT").attr("class","tab-pane fade out");
                $.ajax({
                    type    : "POST",
                    url     : "userlogin",
                    data    : {
                        tUsrCode    : ptUsrCode
                    },
                    cache	: false,
                    timeout	: 0,
                    success	: function(tResult){
                        $('#odvBtnAddEdit').hide();
                        $('#odvUsrloginData').html(tResult);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }else{
                JCNxShowMsgSessionExpired();
            }
        }
    }




</script>






