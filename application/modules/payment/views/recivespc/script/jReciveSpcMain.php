<script type="text/javascript">

    var nLangEdits              = <?php echo $this->session->userdata("tLangEdit")?>;
    var nStaRcvSpcBrowseType    = $('#oetRcvSpcStaBrowse').val();
    var tCallRecSpcBackOption   = $('#oetRcvSpcCallBackOption').val();

    $(document).ready(function () {
        if(nStaRcvSpcBrowseType != 1){
            JSvReciveSpcList(1);
        }else{
            JSvCallPageReciveSpcAdd();
        }

        $("#oimRcvSpcBrowseBch").attr("disabled",true);
        $("#oimRcvSpcBrowseMer").attr("disabled",true);
        $("#oimRcvSpcBrowseShp").attr("disabled",true);
        $("#oimRcvSpcBrowseAgg").attr("disabled",true);
    });
  
    //function : Call PosAds Page list  
    //Parameters : Document Redy And Event Button
    //Creator :	25/11/2019 Witsarut (Bell)
    //Return : View
    //Return Type : View
    function JSvReciveSpcList(nPage){
        var tRcvSpcCode    =   $('#ohdRcvSpcCode').val();
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "recivespcDataTable",
            data    : {
                tRcvSpcCode      : tRcvSpcCode,
                nPageCurrent  : nPage,
                tSearchAll    : ''
            },
            cache   : false,
            Timeout : 0,
            async   : false,
            success : function(tView){
                $('#odvContentRcvSpcDataTable').html(tView);
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //Functionality : Call Credit Page Add  
    //Parameters : -
    //Creator : 25/11/2019 Witsarut(Bell)
    //Return : View
    //Return Type : View
    function JSvCallPageReciveSpcAdd(){
        var tRcvSpcCode =  $('#ohdRcvSpcCode').val();
       
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "recivespcPageAdd",
            data  : {
                tRcvSpcCode  : tRcvSpcCode
            },
            cache: false,
            timeout: 5000,
            success: function(tResult){
                $('#odvRcvSpcData').html(tResult);
                $('.xWPageAdd').removeClass('hidden');
                $('.xWPageEdit').addClass('hidden');
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }


    //Functionality : Call Credit Page Edit  
    //Parameters : -
    //Creator : 26/11/2019 witsarut (Bell)
    //Return : View
    //Return Type : View
    function JSvCallPageRcvSpcEdit(paDataWhereEdit){
        JCNxOpenLoading();
        $.ajax({
            type : "POST",
            url: "recivespcPageEdit",
            data: { 'paDataWhereEdit' : paDataWhereEdit},
            cache: false,
            timeout: 0,
            success:  function(tResult){
                $('#odvRcvSpcData').html(tResult);
                $('.xWPageAdd').removeClass('hidden');
                $('.xWPageEdit').addClass('hidden');

                //สาขา
                if($('#oetRcvSpcBchName').val() != ''){
                    $("#oimRcvSpcBrowseBch").attr("disabled",false);
                }else{
                    $("#oimRcvSpcBrowseBch").attr("disabled",true);
                }
                //กลุ่มธุรกิจ
                if($('#oetRcvSpcMerName').val() != ''){
                    $("#oimRcvSpcBrowseMer").attr("disabled",false);
                }else{
                    $("#oimRcvSpcBrowseMer").attr("disabled",true);
                }
                // ร้านค้าd
                if($('#oetRcvSpcShpName').val() != ''){
                    $("#oimRcvSpcBrowseShp").attr("disabled",false);
                }else{
                    $("#oimRcvSpcBrowseShp").attr("disabled",true);
                }
                // กลุ่มตัวแทน
                if($('#oetRcvSpcAggName').val() != ''){
                    $("#oimRcvSpcBrowseAgg").attr("disabled",false);
                }else{
                    $("#oimRcvSpcBrowseAgg").attr("disabled",true);
                }
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //Functionality : Add Data Agency Add/Edit  
    //Parameters : from ofmAddEditCrdLogin
    //Creator : 04/07/2019 witsarut (Bell)
    //Return : View
    //Return Type : View
    function JSxRcvSpvSaveAddEdit(ptRoute){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            $('#ofmAddEditRcvSpc').validate().destroy();
            $('#ofmAddEditRcvSpc').validate({
                focusInvalid: false,
                onclick: false,
                onfocusout: false,
                onkeyup: false,
                rules: {
                    oetRcvSpcAppName:  {"required" :{}},    
                },
                messages: {
                    oetRcvSpcAppName : {
                        "required"      : $('#oetRcvSpcAppName').attr('data-validate'),
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
                highlight: function(element, errorClass, validClass) {
                    $( element ).closest('.form-group').addClass( "has-error" ).removeClass( "has-success" );
                },
                unhighlight: function(element, errorClass, validClass) {
                    $( element ).closest('.form-group').addClass( "has-success" ).removeClass( "has-error" );
                },
                submitHandler: function(form) {
                    JCNxOpenLoading();
                    $.ajax({
                        type : "POST",
                        url: ptRoute,
                        data: $('#ofmAddEditRcvSpc').serialize(),
                        catch: false,
                        timeout: 0,
                        success: function(tResult){
                            var aData = JSON.parse(tResult);
                            if(aData["nStaEvent"] == 1){
                                JSxRcvSpcGetContent();
                                JCNxCloseLoading();
                            }else if(aData["nStaEvent"] == 900){
                                JCNxCloseLoading();
                            }else{
                                var tMsgErrorFunction   = aData['tStaMessg'];
                                FSvCMNSetMsgErrorDialog('<p class="text-left">'+tMsgErrorFunction+'</p>');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                        }
                    });
                },
            });
        }
    }

    //Functionality: (event) Delete
    //Parameters: Button Event [tIDCode tCrdCode]
    //Creator: 26/11/2019 Witsarut (Bell)
    //Update: -
    //Return: Event Delete Reason List
    //Return Type: -
    function JSxRCVSpcDelete(paDataWhere){
        $('#odvModalDeleteSingle').modal('show');
        $('#odvModalDeleteSingle #ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val()+' '+paDataWhere.ptRcvName+' ('+paDataWhere.ptAppName+')'+' '+$('#oetTextComfirmDeleteYesOrNot').val());
        $('#odvModalDeleteSingle #osmConfirmDelete').on('click', function(evt) {
            $.ajax({
                type: "POST",
                url: "recivespcEventDelete",
                data: { 'paDataWhere' : paDataWhere},
                cache: false,
                success: function (tResult){
                    $('#odvModalDeleteSingle').modal('hide');
                    setTimeout(function(){
                        JSvReciveSpcList(1);
                    }, 500);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        });
    }

    //Functionality : (event) Delete All
    //Parameters :
    //Creator : 28/11/2019 Witsarut (Bell)
    //Return : 
    //Return Type :
    function JSxRCVSpcDeleteMutirecord(pnPage){
        let nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            // JCNxOpenLoading();
            let aDataRcvCode    =[];
            let aDataAppCode    =[];
            let aDataRcvSeq     =[];
            let aDataBchCode    =[];
            let aDataMerCode    =[];
            let aDataShpCode    =[];
            let aDataAggCode    =[];
            let ocbListItem     = $(".ocbListItem");
            for(var nI = 0;nI < ocbListItem.length;nI++){
                if($($(".ocbListItem").eq(nI)).prop('checked')){
                    aDataRcvCode.push($($(".ocbListItem").eq(nI)).parents('.xWRcvSpcItems').data('rcvcode'));
                    aDataAppCode.push($($(".ocbListItem").eq(nI)).parents('.xWRcvSpcItems').data('appcode'));
                    aDataRcvSeq.push($($(".ocbListItem").eq(nI)).parents('.xWRcvSpcItems').data('rcvseq'));
                    aDataBchCode.push($($(".ocbListItem").eq(nI)).parents('.xWRcvSpcItems').data('bchcode'));
                    aDataMerCode.push($($(".ocbListItem").eq(nI)).parents('.xWRcvSpcItems').data('mercode'));
                    aDataShpCode.push($($(".ocbListItem").eq(nI)).parents('.xWRcvSpcItems').data('shpcode'));
                    aDataAggCode.push($($(".ocbListItem").eq(nI)).parents('.xWRcvSpcItems').data('aggcode'));
                }                  
            }
            let aDataWhere  = {
                'paRcvCode' : aDataRcvCode,
                'paAppCode' : aDataAppCode,
                'paRcvSeq'  : aDataRcvSeq,
                'paBchCode' : aDataBchCode,
                'paMerCode' : aDataMerCode,
                'paShpCode' : aDataShpCode,
                'paAggCode' : aDataAggCode,
            };
            $.ajax({
                type: "POST",
                url: "recivespcEventDeleteMultiple",
                data: { 'paDataWhere' : aDataWhere},
                cache: false,
                timeout: 0,
                success: function(tResult){
                    tResult = tResult.trim();
                    var aReturn = $.parseJSON(tResult);
                    if(aReturn['nStaEvent'] == '1'){
                        $('#odvModalDeleteMutirecord').modal('hide');
                        $('#ospConfirmDelete').empty();
                        localStorage.removeItem('LocalItemData');
                        setTimeout(function(){
                            JSvReciveSpcList(pnPage);
                        }, 500);
                    }else{
                        alert(aReturn['tStaMessg']);
                    }
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

    //Functionality: Function Chack And Show Button Delete All
    //Parameters: LocalStorage Data
    //Creator: 2//11/2019 witsarut (Bell)
    //Return: - 
    //Return Type: -
    function JSxRCVSPCShowButtonChoose() {
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == '') {
            $('#odvMngTableList #oliBtnDeleteAll').addClass('disabled');
        } else {
            nNumOfArr = aArrayConvert[0].length;
            if (nNumOfArr > 1) {
                $('#odvMngTableList #oliBtnDeleteAll').removeClass('disabled');
            } else {
                $('#odvMngTableList #oliBtnDeleteAll').addClass('disabled');
            }
        }
    }

    //Functionality: Function Chack Value LocalStorage
    //Parameters: Event Select List Branch
    //Creator: 26/11/2019 witsarut (Bell)
    //Return: Duplicate/none
    //Return Type: string
    function findObjectByKey(array, key, value) {
        for (var i = 0; i < array.length; i++) {
            if (array[i][key] === value) {
                return 'Dupilcate';
            }
        }
        return 'None';
    }

    //Functionality: Insert Text In Modal Delete
    //Parameters: LocalStorage Data
    //Creator: 05/07/2019 witsarut (Bell)
    //Return: -
    //Return Type: -
    function JSxRCVSPCPaseCodeDelInModal() {
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == '') {} else {
            var tTextCode = '';
            for ($i = 0; $i < aArrayConvert[0].length; $i++) {
                tTextCode += aArrayConvert[0][$i].nCode;
                tTextCode += ' , ';
            }
            $('#ospConfirmDelete').text($('#oetTextComfirmDeleteMulti').val());
            $('#ohdConfirmIDDelete').val(tTextCode);
        }
    }

    //Functionality: เปลี่ยนหน้า pagenation
    //Parameters: -
    //Creator: 26/11/2019 Witsarut
    //Update: -
    //Return: View
    //Return Type: View
    function JSvRCVSPCClickPage(ptPage){
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $(".xWBtnNext").addClass("disabled");
                nPageOld = $(".xWRcbvSpcPaging .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
            break;
            case "previous": //กดปุ่ม Previous
                nPageOld = $(".xWRcbvSpcPaging .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
            break;
            default:
            nPageCurrent = ptPage;
        }
        JSvReciveSpcList(nPageCurrent);
    }
    

// *******************************************************************************
    // ระบบ
    $('#oimRcvSpcBrowseApp').click(function(){
        JSxCheckPinMenuClose();
        JCNxBrowseData('oBrowsetSysApp');
    });

    //สาขา
    $('#oimRcvSpcBrowseBch').click(function(){
        JSxCheckPinMenuClose();
        oRcvSpcBrwOption     = oBrowseBranch({
            'tHiddenRcvSpc'       : $('#ohdRcvSpc').val()
        });
        JCNxBrowseData('oRcvSpcBrwOption');
    });

    // กลุ่มธุรกิจ
    $('#oimRcvSpcBrowseMer').click(function(){
        JSxCheckPinMenuClose();
        oRcvSpcBchBrwOption     = oBrowseMer({
            'tHiddenRcvSpcMer'       : $('#ohdRcvSpcBch').val()
        });
        JCNxBrowseData('oRcvSpcBchBrwOption');
    });

    // ร้านค้า
    $('#oimRcvSpcBrowseShp').click(function(){
        JSxCheckPinMenuClose();
        oRcvSpcMerBrwOption     = oBrowseShop({
            'tHiddenRcvSpcShp'       : $('#ohdRcvSpcMer').val()
        });
        JCNxBrowseData('oRcvSpcMerBrwOption');
    });

    // กลุ่มตัวแทน
    $('#oimRcvSpcBrowseAgg').click(function(){
        JSxCheckPinMenuClose();
        oRcvSpcShpBrwOption     = oBrowseAgg({
            'tHiddenRcvSpcAgg'   : $('#ohdRcvSpcShp').val()
        });
        JCNxBrowseData('oRcvSpcShpBrwOption');
    });

    // ระบบ
    var  oBrowsetSysApp = {
        Title : ['payment/recivespc/recivespc','tBrowseAppTitle'],
        Table:{Master:'TSysApp',PK:'FTAppCode'},
        Join : {
            Table : ['TSysApp_L'],
            On:['TSysApp_L.FTAppCode = TSysApp.FTAppCode AND TSysApp_L.FNLngID ='+nLangEdits] 
        },
        GrideView:{
            ColumnPathLang	: 'payment/recivespc/recivespc',
            ColumnKeyLang	: ['tBrowseAppCode','tBrowseAppName'],
            ColumnsSize     : ['15%','75%'],
            DataColumns	: ['TSysApp.FTAppCode','TSysApp_L.FTAppName'],
            DataColumnsFormat : ['',''],
            WidthModal      : 50,
            Perpage		: 10,
        OrderBy		: ['TSysApp.FTAppCode'],
        SourceOrder	: "ASC"
        },
        CallBack:{
            ReturnType	: 'S',
            Value		: ["oetRcvSpcAppCode","TSysApp.FTAppCode"],
            Text		: ["oetRcvSpcAppName","TSysApp_L.FTAppName"]
        },
        NextFunc:{
            FuncName:'JSxNextFuncRcvSpc',
            ArgReturn:['FTAppCode']
        },
    };

    function JSxNextFuncRcvSpc(paDataReturn){
        var aRcvSpc = JSON.parse(paDataReturn);
        var ohdRcvtSesUsrBchCode = $('#ohdRcvtSesUsrBchCode').val();
        if(ohdRcvtSesUsrBchCode==''){
        $("#oimRcvSpcBrowseBch").attr("disabled",false);
        $('#oetRcvSpcBchCode').val('');
        $('#oetRcvSpcBchName').val('');
        }
        $('#ohdRcvSpc').val(aRcvSpc[0]);


    }

    // สาขา
    var oBrowseBranch =  function(poDataFnc){
        var tWhereModal         = poDataFnc.tHiddenRcvSpc;
        var ohdRcvtSesUsrBchCode = $('#ohdRcvtSesUsrBchCode').val();
        var tConditionWhere = '';
        if(ohdRcvtSesUsrBchCode!=''){
            tConditionWhere +=" AND TCNMBranch.FTBchCode = '"+ohdRcvtSesUsrBchCode+"' ";
        }
        var oOptionReturn       = {
            Title : ['authen/user/user','tBrowseBCHTitle'],
            Table:{Master:'TCNMBranch',PK:'FTBchCode'},
            Join :{
                Table:	['TCNMBranch_L'],
                On:['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits]
            },
            Where : {
                Condition : [tConditionWhere]
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
                Value		: ["oetRcvSpcBchCode","TCNMBranch.FTBchCode"],
                Text		: ["oetRcvSpcBchName","TCNMBranch_L.FTBchName"]
            },
            NextFunc:{
            FuncName:'JSxNextFuncRcvSpcBch',
                ArgReturn:['FTBchCode']
            },
            RouteAddNew : 'branch',
            BrowseLev : nStaRcvBrowseType 
        };
        return oOptionReturn; 
    }

    function JSxNextFuncRcvSpcBch(paDataReturn){
        var aRcvSpcBch = JSON.parse(paDataReturn);
        $("#oimRcvSpcBrowseMer").attr("disabled",false);
        $('#ohdRcvSpcBch').val(aRcvSpcBch[0]);

        $('#oetRcvSpcMerCode').val('');
        $('#oetRcvSpcMerName').val('');
    }

    // Option กลุ่มธุรกิจ
    var oBrowseMer = function(poDataFnc){
        var tWhereModal         = poDataFnc.tHiddenRcvSpcMer;
        var oOptionReturn       = {
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
                Value		: ["oetRcvSpcMerCode","TCNMMerchant.FTMerCode"],
                Text		: ["oetRcvSpcMerName","TCNMMerchant_L.FTMerName"],
            },
            NextFunc:{
            FuncName:'JSxNextFuncRcvSpcMer',
                ArgReturn:['FTMerCode']
            },
            // RouteFrom : 'shop',
            RouteAddNew : 'merchant',
            BrowseLev : nStaRcvBrowseType
        };
        return oOptionReturn; 
    }

    function JSxNextFuncRcvSpcMer(paDataReturn){
        var aRcvSpcMer = JSON.parse(paDataReturn);
        $("#oimRcvSpcBrowseShp").attr("disabled",false);
        $('#ohdRcvSpcMer').val(aRcvSpcMer[0]);

        $('#oetRcvSpcShpCode').val('');
        $('#oetRcvSpcShpName').val('');
     }

    //ร้านค้า
    var oBrowseShop =  function(poDataFnc){
        var tWhereModal    = poDataFnc.tHiddenRcvSpcShp;
        var oOptionReturn       = {
            Title : ['authen/user/user','tBrowseSHPTitle'],
            Table:{Master:'TCNMShop',PK:'FTShpCode'},
            Join :{
                Table:	['TCNMShop_L'],
                On:['TCNMShop_L.FTShpCode = TCNMShop.FTShpCode AND TCNMShop_L.FNLngID = '+nLangEdits]
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
                Value		: ["oetRcvSpcShpCode","TCNMShop.FTShpCode"],
                Text		: ["oetRcvSpcShpName","TCNMShop_L.FTShpName"]
            },
            NextFunc:{
            FuncName:'JSxNextFuncRcvSpcShp',
                ArgReturn:['FTShpCode']
            },
            RouteAddNew : 'shop',
            BrowseLev : nStaRcvBrowseType
        };
        return oOptionReturn; 
    }

    function JSxNextFuncRcvSpcShp(paDataReturn){
        var aRcvSpcShp = JSON.parse(paDataReturn);
        $("#oimRcvSpcBrowseAgg").attr("disabled",false);
        $('#ohdRcvSpcShp').val(aRcvSpcShp[0]);

        $('#oetRcvSpcAggCode').val('');
        $('#oetRcvSpcAggName').val('');
    }

   // *******************************************************************************

   

    //กลุ่มตัวแทน
    var  oBrowseAgg = function(poDataFnc){
        var tWhereModal    = poDataFnc.tHiddenRcvSpcAgg;
        var oOptionReturn       = {
            Title : ['payment/recivespc/recivespc','tBrowseAggGrp'],
            Table:{Master:'TCNMAgencyGrp',PK:'FTAggCode'},
            Join : {
                Table : ['TCNMAgencyGrp_L'],
                On:['TCNMAgencyGrp_L.FTAggCode = TCNMAgencyGrp.FTAggCode AND TCNMAgencyGrp_L.FNLngID ='+nLangEdits] 
            },
            GrideView:{
                ColumnPathLang	: 'payment/recivespc/recivespc',
                ColumnKeyLang	: ['tBrowseAggCode','tBrowseAggName'],
                ColumnsSize     : ['15%','75%'],
                DataColumns	: ['TCNMAgencyGrp.FTAggCode','TCNMAgencyGrp_L.FTAggName'],
                DataColumnsFormat : ['',''],
                WidthModal      : 50,
                Perpage		: 10,
            OrderBy		: ['TCNMAgencyGrp.FTAggCode'],
            SourceOrder	: "ASC"
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: ["oetRcvSpcAggCode","TCNMAgencyGrp.FTAggCode"],
            Text		: ["oetRcvSpcAggName","TCNMAgencyGrp_L.FTAggName"]
            },
        };
        return oOptionReturn; 
    }


</script>