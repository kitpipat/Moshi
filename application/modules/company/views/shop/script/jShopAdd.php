<script type="text/javascript">

    //Set Lang Edit 
    var nLangEdits = <?php echo $this->session->userdata("tLangEdit")?>;

    $(document).ready(function () {
        $('.xCNStaShwPrice').hide();
        $('#odvBtnGrpShop').show();
        $('.xWBtnGrpSaveLeft').show();
        $('.xWBtnGrpSaveRight').show();

        if(JSbShopIsCreatePage()){
            // Shop Code
            $("#oetShpCode").attr("disabled", true);
            $('#ocbShopAutoGenCode').change(function(){
                if($('#ocbShopAutoGenCode').is(':checked')) {
                    $('#oetShpCode').val('');
                    $("#oetShpCode").attr("disabled", true);
                    $('#odvShopCodeForm').removeClass('has-error');
                    $('#odvShopCodeForm em').remove();
                }else{
                    $("#oetShpCode").attr("disabled", false);
                }
            });
            JSxShopVisibleComponent('#odvShopAutoGenCode', true);
        }

        if(JSbShopIsUpdatePage()){
            // Shop Code
            $("#oetShpCode").attr("readonly", true);
            $('#odvShopAutoGenCode input').attr('disabled', true);
            JSxShopVisibleComponent('#odvShopAutoGenCode', false);    
        }

        $('#oetShpCode').blur(function(){
            JSxCheckShopCodeDupInDB();
        });

        //Functionality: Event Click Tab Main Or Sub Contril Button Add
        //Parameters: Data Attr
        //Creator: 13/09/2019 wasin (Yoshi)
        //Return: -
        //ReturnType: -
        $('#odvShopPanelBody .xCNSHPTab').unbind().click(function(){
            let tRoutePage  = '<?php echo @$tRoute;?>';
            if(tRoutePage == 'shopEventAdd'){
                return;
            }else{
                let tTypeTab    = $(this).data('typetab');
                if(typeof(tTypeTab) !== undefined && tTypeTab == 'main'){
                    JCNxOpenLoading();
                    setTimeout(function(){
                        $('#odvBtnGrpShop #odvBtnAddEdit').show();
                        JCNxCloseLoading();
                        return;
                    },500);
                }else if(typeof(tTypeTab) !== undefined && tTypeTab == 'sub'){
                    $('#odvBtnGrpShop #odvBtnAddEdit').hide();
                    let tTabTitle   = $(this).data('tabtitle');
                    switch(tTabTitle){
                        case 'shpvdlayout':
                            // ??????????????????????????? Vending
                            JSxGetSHPContentInfoVLY();
                        break;
                        case 'shpvdcabinet':
                            // ????????????????????? Cabinet
                            JSxGetSHPContentCabinet();
                        break;
                        case 'shpvdtype':
                            // ??????????????????????????? Vending
                            JSxGetSHPContentInfoVT();
                        break;
                        case 'shplksize':
                            // ???????????? smart locker
                            JSxGetSHPContentSmartLockerSize();
                        break;
                        case 'shplktype':
                            // ??????????????????????????? smart locker
                            JSxGetSHPContentSmartLockerType();
                        break;
                        case 'shplklayout':
                            // ??????????????????????????? smart locker
                            JSxGetSHPContentSmartLockerLayout();
                        break;
                        case 'shpposshop':
                            // ?????????????????????????????????????????????????????????
                            JSxGetSHPContentInfoPS();
                        break;
                        case 'shpgpshop':
                            // ??????????????? GP ?????????????????????
                            JSxGetSHPContentInfoGPS();
                        break;
                        case 'shpgpproduct':
                            // ??????????????? GP Product
                            JSxGetSHPContentInfoGPP();
                        break;
                        case 'shpaddress':
                            // ???????????????????????????????????????
                            JSxGetSHPContentAddress();
                        break;
                        case 'shpRack':
                            // ???????????????????????????
                            JSxGetSHPContentRack();
                        break;
                    }
                }else{
                    return
                }
            }
        });

    });

    $('#ocmShpType').change(function(){
        var tShpType  = $('#ocmShpType').val();
        switch (tShpType) {
            case'4':
                $('.xCNStaShwPrice').show();
                break;
            case '5':
                $('#odvTypeWahCode').hide();
                break;
            default:
                $('.xCNStaShwPrice').hide();
                $('#odvTypeWahCode').show();
        }

    });


    // Option Wah
    var oBrowseWah = {
        Title : ['company/warehouse/warehouse','tWAHTitle'],
        Table:{Master:'TCNMWaHouse',PK:'FTWahCode'},
        Join :{
            Table:	['TCNMWaHouse_L'],
            On:['TCNMWaHouse_L.FTWahCode = TCNMWaHouse.FTWahCode AND TCNMWaHouse.FTShpCode = TCNMWaHouse_L.FTShpCode AND TCNMWaHouse_L.FNLngID = '+nLangEdits,]
        },
        Where: {
				Condition: [
                    function JSxConditionConfig(){
                        var tSQL = "AND TCNMWaHouse.FTWahStaType = '4' AND ((TCNMWaHouse.FTWahRefCode = '' OR TCNMWaHouse.FTWahRefCode IS NULL)";
                        if($("#oetShpWahCode").val()!=""){
                            tSQL += " OR TCNMWaHouse.FTWahCode = '"+$("#oetShpWahCode").val()+"' )";
                        }else{
                            tSQL += ")";
                        }
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
            OrderBy			: ['TCNMWaHouse_L.FTWahName'],
            SourceOrder		: "ASC"
        },
        CallBack:{
            ReturnType	: 'S',
            Value		: ["oetShpWahCode","TCNMWaHouse.FTWahCode"],
            Text		: ["oetWahName","TCNMWaHouse_L.FTWahName"],
        },
        RouteFrom : 'shop',
        RouteAddNew : 'warehouse',
        BrowseLev : nStaShpBrowseType
    }

    // Option Bch
    var oBrowseBch = {
        // Title : ['company/branch/branch','tBCHTitle'],
        // Table:{Master:'TCNMBranch',PK:'FTBchCode'},
        // Join :{
        //     Table:	['TCNMBranch_L'],
        //     On:['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
        // },
        // GrideView:{
        //     ColumnPathLang	: 'company/branch/branch',
        //     ColumnKeyLang	: ['tBCHCode','tBCHName'],
        //     ColumnsSize     : ['15%','75%'],
        //     WidthModal      : 50,
        //     DataColumns		: ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
        //     DataColumnsFormat : ['',''],
        //     Perpage			: 5,
        //     OrderBy			: ['TCNMBranch_L.FTBchName ASC'],
        //     // SourceOrder		: "ASC"
        // },
        // CallBack:{
        //     ReturnType	: 'M',
        //     Value		: ["oetShpBchCode","TCNMBranch.FTBchCode"],
        //     Text		: ["oetShpBchName","TCNMBranch_L.FTBchName"],
        // },

        // RouteAddNew : 'branch',
        // BrowseLev : nStaShpBrowseType

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
            Perpage			: 100000,
            OrderBy			: ['TCNMBranch_L.FTBchName ASC'],
        },
        CallBack:{
            ReturnType	: 'S',
            Value		: ["oetShpBchCode","TCNMBranch.FTBchCode"],
            Text		: ["oetShpBchName","TCNMBranch_L.FTBchName"],
        },
        // RouteFrom : 'shop',
        RouteAddNew : 'branch',
        BrowseLev : nStaShpBrowseType
    }

    // Option Merchant
    var oBrowseMer = {
        Title : ['company/merchant/merchant','tMerchantTitle'],
        Table:{Master:'TCNMMerchant',PK:'FTMerCode'},
        Join :{
            Table:	['TCNMMerchant_L'],
            On:['TCNMMerchant_L.FTMerCode = TCNMMerchant.FTMerCode AND TCNMMerchant_L.FNLngID = '+nLangEdits,]
        },
        GrideView:{
            ColumnPathLang	: 'company/merchant/merchant',
            ColumnKeyLang	: ['tMerCode','tMerName'],
            ColumnsSize     : ['15%','75%'],
            WidthModal      : 50,
            DataColumns		: ['TCNMMerchant.FTMerCode','TCNMMerchant_L.FTMerName'],
            DataColumnsFormat : ['',''],
            Perpage			: 5,
            OrderBy			: ['TCNMMerchant_L.FTMerName'],
            SourceOrder		: "ASC"
        },
        CallBack:{
            ReturnType	: 'S',
            Value		: ["oetShpMerCode","TCNMMerchant.FTMerCode"],
            Text		: ["oetShpMerName","TCNMMerchant_L.FTMerName"],
        },
        // RouteFrom : 'shop',
        RouteAddNew : 'merchant',
        BrowseLev : nStaShpBrowseType
    }
    
    //Set Event Browse 
    $('#oimShpBrowseWah').click(function(){
        // Create By Witsarut 04/10/2019
            JSxCheckPinMenuClose();
        // Create By Witsarut 04/10/2019
        JCNxBrowseData('oBrowseWah');
    });

    $('#oimShpBrowseBch').click(function(){
        // Create By Witsarut 04/10/2019
            JSxCheckPinMenuClose();
        // Create By Witsarut 04/10/2019
        JCNxBrowseData('oBrowseBch');
    });

    $('#oimShpBrowseMer').click(function(){
        // Create By Witsarut 04/10/2019
            JSxCheckPinMenuClose();
        // Create By Witsarut 04/10/2019
        JCNxBrowseData('oBrowseMer');
    });
    
    $('.xCNDatePicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });

    // $('.xCNDatePicker').mask('00/00/0000');

    $(".selection-2").select2({
        minimumResultsForSearch: 20,
        dropdownParent: $('#dropDownSelect1')
    });

    $('.selectpicker').selectpicker();

    $('.xWTooltipsBT').tooltip({'placement': 'bottom'});
    $('[data-toggle="tooltip"]').tooltip({'placement': 'top'});

    $('#obtShpStart').click(function(event){
        $('#oetShpStart').datepicker('show');
    });

    $('#obtShpStop').click(function(event){
        $('#oetShpStop').datepicker('show');
    });

    $('#obtShpSaleStart').click(function(event){
        $('#oetShpSaleStart').datepicker('show');
    });

    $('#obtShpSaleStop').click(function(event){
        $('#oetShpSaleStop').datepicker('show');
    });


    //New Create Supawat
    //tab -> GP ?????????????????? (success)
    function JSxGetSHPContentInfoGPP(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            var tBchCode    = '<?php echo @$tBchCode?>';
            var tShpCode    = '<?php echo @$tShpCode?>';
            var nPage       = 1;
            // Check Shop GP Type In DB
            if(tShpCode == '' || tShpCode == null){
            }else{
                JCNxOpenLoading();
                JSvCallPageShopGpByPdtMain(tBchCode,tShpCode,nPage);	
            }
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //New Create Supawat
    //tab -> GP ?????????????????????
    function JSxGetSHPContentInfoGPS(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            var tBchCode    = '<?php echo @$tBchCode?>';
            var tShpCode    = '<?php echo @$tShpCode?>';
            var nPage       = 1;
            JCNxOpenLoading();
            JSvCallPageShopGpByShpMain(tBchCode,tShpCode,nPage);
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //tab -> ??????????????????????????? (Vending)
    function JSxGetSHPContentInfoVT(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            var tBchCode    = '<?php echo @$tBchCode?>';
            var tShpCode    = '<?php echo @$tShpCode?>';
            var nPage       = 1;
            if(tShpCode == '' || tShpCode == null){
            }else{
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "VendingShopTypePageEdit",
                    data: {
                        tBchCode        : tBchCode,
                        tShpCode        : tShpCode
                    },
                    cache: false,
                    Timeout: 0,
                    async: false,
                    success: function(tView){
                        $('#odvSHPContentInfoVT').html(tView);
                        JCNxCloseLoading();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //tab -> ????????????????????????????????????
    function JSxGetSHPContentInfoDT(){
        $('#odvBtnAddEdit').show();
        $('#odvBtnPdtInfo').hide();
        $('#obtAllProductGp').show();
        $('#odvBtnAddEditPDT').hide();
        $('#odvBtnGrpShop').show();
        $('.xWBtnGrpSaveLeft').show();
        $('.xWBtnGrpSaveRight').show();
    }

    //tab -> ???????????????????????????????????????
    function JSxGetSHPContentInfoPS(){
        var tBchCode      = '<?php echo @$tBchCode?>';
        var tShpCode      = '<?php echo @$tShpCode?>';
        var tShpName      = '<?php echo @$tShpName?>';
        var tMerCode      = $('#oetSHPMerCode').val();
        var tShpTypeCode  = $('#oetSHPType').val();
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "posshopList",
                data	: {
                        tBchCode        : tBchCode,
                        tShpCode        : tShpCode,
                        tMerCode        : tMerCode,
                        tShpTypeCode    : tShpTypeCode 
                },
                cache: false,
                timeout: 0,
                success: function(tResult){
                    // $('#odvBtnPdtInfo').hide();
                    // $('#odvBtnGrpShop').hide();
                    $('#odvSHPContentInfoPS').html(tResult);
                    $('#oetBchCode').val(tBchCode);
                    $('#oetShpCode').val(tShpCode);
                    $('#oetShpName').val(tShpName);
                    JSvPosShopDataTable(tBchCode,tShpCode,1);
                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //tab -> ????????????????????? (Cabinet)
    function JSxGetSHPContentCabinet(){
        JCNxOpenLoading();
        var tBchCode    = '<?php echo @$tBchCode?>';
        var tShpCode    = '<?php echo @$tShpCode?>';
        var tShpName    = '<?php echo @$tShpName?>';
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            $.ajax({
                type	: "POST",
                url		: "VendingCabinet",
                data	: {
                    tBchCode        : tBchCode,
                    tShpCode        : tShpCode
                },
                cache	: false,
                timeout	: 0,
                success	: function(tResult){
                    $('#odvSHPContentInfoCabinet').html(tResult);
                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //tab -> ??????????????????????????? (Vending)
    function JSxGetSHPContentInfoVLY(){
        JCNxOpenLoading();
        var tBchCode    = '<?php echo @$tBchCode?>';
        var tShpCode    = '<?php echo @$tShpCode?>';
        var tShpName    = '<?php echo @$tShpName?>';
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            $.ajax({
                type	: "POST",
                url		: "VendingLayout",
                data	: {
                    tBchCode        : tBchCode,
                    tShpCode        : tShpCode
                },
                cache	: false,
                timeout	: 0,
                success	: function(tResult){
                    $('#odvSHPContentInfoVLY').html(tResult);
                    // JSvPosShopDataTable(tBchCode,tShpCode,1);
                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //tab -> ??????????????????????????? (Smart locker)
    function JSxGetSHPContentSmartLockerLayout(){
        var tBchCode    = '<?php echo @$tBchCode?>';
        var tShpCode    = '<?php echo @$tShpCode?>';
        var tShpName    = '<?php echo @$tShpName?>';
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JCNxOpenLoading();
            $.ajax({
                type	: "POST",
                url		: "SHPSmartLockerLayoutMain",
                data	: {
                    tBchCode        : tBchCode,
                    tShpCode        : tShpCode
                },
                cache	: false,
                timeout	: 0,
                success	: function(tResult){
                    $('#odvSHPContentSmartLockerLayout').html(tResult);
                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //tab -> ????????????????????? (Smart locker)
    function JSxGetSHPContentSmartLockerSize(){
        var tBchCode    = '<?php echo @$tBchCode?>';
        var tShpCode    = '<?php echo @$tShpCode?>';
        var tShpName    = '<?php echo @$tShpName?>';
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            $.ajax({
                type	: "POST",
                url		: "SHPSmartLockerSize",
                data	: {
                    tBchCode        : tBchCode,
                    tShpCode        : tShpCode
                },
                cache	: false,
                timeout	: 0,
                success	: function(tResult){
                    $('#odvSHPContentSmartLockerSize').html(tResult);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //tab -> ??????????????????????????? (Smart locker)
    function JSxGetSHPContentSmartLockerType(){
        var tBchCode    = '<?php echo @$tBchCode?>';
        var tShpCode    = '<?php echo @$tShpCode?>';
        var tShpName    = '<?php echo @$tShpName?>';
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JCNxOpenLoading();
            $.ajax({
                type	: "POST",
                url		: "LocTypeData",
                data	: {
                    tBchCode        : tBchCode,
                    tShpCode        : tShpCode
                },
                cache	: false,
                timeout	: 0,
                success	: function(tResult){
                    $('#odvSHPContentSmartLockerType').html(tResult);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    //  New Create Wasin(Yoshi)
    //  Tab ??????????????????????????????????????????
    function JSxGetSHPContentAddress(){
        var tBchCode    = '<?php echo @$tBchCode;?>';
        var tShpCode    = '<?php echo @$tShpCode;?>';
        var tShpName    = '<?php echo @$tShpName;?>';
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JCNxOpenLoading();
            $.ajax({
                type : "POST",
                url : "shopAddressData",
                data : {
                    'ptBchCode' : tBchCode,
                    'ptShpCode' : tShpCode,
                    'ptShpName' : tShpName,
                },
                success	: function(tResult){
                    $('#odvSHPAddressData').html(tResult);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JCNxShowMsgSessionExpired();
        }
    } 

    //Functionality: Event Check Sale Person Duplicate
    //Parameters: Event Blur Input Sale Person Code
    //Creator: 27/03/2019 wasin (Yoshi)
    //Return: -
    //ReturnType: -
    function JSxCheckShopCodeDupInDB(){
        if(!$('#ocbShopAutoGenCode').is(':checked')){
            $.ajax({
                type: "POST",
                url: "CheckInputGenCode",
                data: { 
                    tTableName  : "TCNMShop",
                    tFieldName  : "FTShpCode",
                    tFiledBch   : "FTBchCode",
                    tCode       : $("#oetShpCode").val()
                },
                async : false,
                cache: false,
                timeout: 0,
                success: function(tResult){
                    var aResult = JSON.parse(tResult);
                    $("#ohdCheckDuplicateShpCode").val(aResult["rtCode"]);
                    // JSxShopSetValidEventBlur();
                    // $('#ofmAddShop').submit();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }
    }

    //Functionality: Set Validate Event Blur
    //Parameters: Validate Event Blur
    //Creator: 26/03/2019 wasin (Yoshi)
    //Return: -
    //ReturnType: -
    function JSxShopSetValidEventBlur(){
        $('#ofmAddShop').validate().destroy();

        // Set Validate Dublicate Code
        $.validator.addMethod('dublicateCode', function(value, element) {
            if($("#ohdCheckDuplicateShpCode").val() == 1){
                return false;
            }else{
                return true;
            }
        },'');

        // From Summit Validate
        $('#ofmAddShop').validate({
            rules: {
                oetShpCode : {
                    "required" :{
                        // ????????????????????????????????????????????? validate
                        depends: function(oElement) {
                            if($('#ocbShopAutoGenCode').is(':checked')){
                                return false;
                            }else{
                                return true;
                            }
                        }
                    },
                    "dublicateCode" :{}
                },
                oetShpName:     {"required" :{}},
                oetShpBchName:  {"required" :{}},
                oetWahName:     {"required" :{}},
            },
            messages: {
                oetShpCode : {
                    "required"      : $('#oetShpCode').attr('data-validate-required'),
                    "dublicateCode" : $('#oetShpCode').attr('data-validate-dublicateCode')
                },
                oetShpName : {
                    "required"      : $('#oetShpName').attr('data-validate-required'),
                },
                oetShpBchName: {
                    "required"      : $('#oetShpBchName').attr('data-validate-required'),
                },
                oetWahName: {
                    "required"      : $('#oetWahName').attr('data-validate-required'),
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


    //  New Create Saharat(Golf)
    //  Tab ???????????????????????????
    function JSxGetSHPContentRack(){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JCNxOpenLoading();
            $.ajax({
                type : "POST",
                url : "SHPSmartLockerrack",
                // data : {
                //     'ptBchCode' : tBchCode,
                //     'ptShpCode' : tShpCode,
                //     'ptShpName' : tShpName,
                // },
                success	: function(tResult){
                    $('#odvSHPContentRack').html(tResult);
                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // Create By WItsarut 18/02/2020
    // Tab ShopWah
    function JSxShpWahGetContent(){

        var tRoutepage = '<?=$tRoute?>';

        if(tRoutepage == 'shopEventAdd'){
            return;
        }else{
            var ptShpCode    =  '<?php echo $tShpCode;?>';
            var ptBchCode    =  '<?php echo $tBchCode;?>';

            //Check Session Expired
            var nStaSession = JCNxFuncChkSessionExpired();

            //if have Session 
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                $("#odvSHPContentInfoDT").attr("class","tab-pane fade out");

                $.ajax({
                    type : "POST",
                    url  : "ShpWah",
                    data : {
                        tShpCode  : ptShpCode,
                        tBchCode : ptBchCode
                    },
                    cache : false,
                    timeout : 0,
                    success :function(tResult){
                        $('#odvSHPWah').html(tResult);
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