var nStaTcgBrowseType   = $('#oetTcgStaBrowse').val();
var tCallTcgBackOption  = $('#oetTcgCallBackOption').val();
// alert(nStaTcgBrowseType+'//'+tCallTcgBackOption);
$('document').ready(function(){
    localStorage.removeItem('LocalItemData');
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    JSxTcgNavDefult();
    if(nStaTcgBrowseType != 1){
        JSvCallPagePdtTouchGrpList();
    }else{
        JSvCallPagePdtTouchGrpAdd();
    }
});

//function : Function Clear Defult Button Product Touch Group
//Parameters : Document Ready
//Creator : 19/09/2018 wasin
//Return : Show Tab Menu
//Return Type : -
function JSxTcgNavDefult(){
    if(nStaTcgBrowseType != 1 || nStaTcgBrowseType == undefined){
        $('.xCNTcgVBrowse').hide();
        $('.xCNTcgVMaster').show();
        $('.xCNChoose').hide();
        $('#oliTcgTitleAdd').hide();
        $('#oliTcgTitleEdit').hide();
        $('#odvBtnAddEdit').hide();
        $('#odvBtnTcgInfo').show();
    }else{
        $('#odvModalBody .xCNTcgVMaster').hide();
        $('#odvModalBody .xCNTcgVBrowse').show();
        $('#odvModalBody #odvTcgMainMenu').removeClass('main-menu');
        $('#odvModalBody #oliTcgNavBrowse').css('padding', '2px');
        $('#odvModalBody #odvTcgBtnGroup').css('padding', '0');
        $('#odvModalBody .xCNTcgBrowseLine').css('padding', '0px 0px');
        $('#odvModalBody .xCNTcgBrowseLine').css('border-bottom', '1px solid #e3e3e3');
    }
}

//function : Function Show Event Error
//Parameters : Error Ajax Function 
//Creator : 19/09/2018 wasin
//Return : Modal Status Error
//Return Type : view
function JCNxResponseError(jqXHR,textStatus,errorThrown){
    JCNxCloseLoading();
    var tHtmlError = $(jqXHR.responseText);
    var tMsgError = "<h3 style='font-size:20px;color:red'>";
    tMsgError += "<i class='fa fa-exclamation-triangle'></i>";
    tMsgError += " Error<hr></h3>";
    switch (jqXHR.status) {
        case 404:
            tMsgError += tHtmlError.find('p:nth-child(2)').text();
            break;
        case 500:
            tMsgError += tHtmlError.find('p:nth-child(3)').text();
            break;

        default:
            tMsgError += 'something had error. please contact admin';
            break;
    }
    $("body").append(tModal);
    $('#modal-customs').attr("style", 'width: 450px; margin: 1.75rem auto;top:20%;');
    $('#myModal').modal({ show: true });
    $('#odvModalBody').html(tMsgError);
}

//function : Call Page list Product Touch Group  
//Parameters : Document Redy And Event Button
//Creator :	19/09/2018 wasin
//Return : View
//Return Type : View
function JSvCallPagePdtTouchGrpList(){
    // console.log('JSvCallPagePdtTouchGrpList: 1');
    localStorage.tStaPageNow = 'JSvCallPagePdtTouchGrpList';
    $('#oetSearchPdtTouchGrp').val('');
    JCNxOpenLoading();    
    $.ajax({
        type: "POST",
        url: "pdttouchgrpList",
        cache: false,
        timeout: 0,
        success: function(tResult){
            // console.log('JSvCallPagePdtTouchGrpList: 2');
            $('#odvContentPagePdtTouchGrp').html(tResult);
            JSvPdtTouchGrpDataTable();
            // console.log('JSvCallPagePdtTouchGrpList: 3');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//function: Call Data List Product Touch Group
//Parameters: Ajax Success Event 
//Creator:	19/09/2018 wasin
//Return: View
//Return Type: View
function JSvPdtTouchGrpDataTable(pnPage){
    var tSearchAll  = $('#oetSearchPdtTouchGrp').val();
    var nPageCurrent    = (pnPage === undefined || pnPage == '')? '1' : pnPage;
    $.ajax({
        type: "POST",
        url: "pdttouchgrpDataTable",
        data: {
            tSearchAll: tSearchAll,
            nPageCurrent: nPageCurrent,
        },
        cache: false,
        Timeout: 0,
        success: function(tResult){
            if (tResult != "") {
                $('#ostDataPdtTouchGrp').html(tResult);
            }
            JSxTcgNavDefult();
            JCNxLayoutControll();
            JStCMMGetPanalLangHTML('TCNMPdtTouchGrp_L'); //โหลดภาษาใหม่
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Functionality : Call Page Add Product Touch Group
//Parameters : Event Button Click
//Creator : 19/09/2018 wasin
//Return : View
//Return Type : View
function JSvCallPagePdtTouchGrpAdd(){
    JCNxOpenLoading();
    JStCMMGetPanalLangSystemHTML('', '');
    $.ajax({
        type: "POST",
        url: "pdttouchgrpPageAdd",
        cache: false,
        timeout: 0,
        success: function(tResult){
            if (nStaTcgBrowseType == 1) {
                $('.xCNTcgVMaster').hide();
                $('.xCNTcgVBrowse').show();
            }else{
                $('.xCNTcgVBrowse').hide();
                $('.xCNTcgVMaster').show();
                $('#oliTcgTitleEdit').hide();
                $('#oliTcgTitleAdd').show();
                $('#odvBtnTcgInfo').hide();
                $('#odvBtnAddEdit').show();
            }
            $('#odvContentPagePdtTouchGrp').html(tResult);
            $('#ocbTcgAutoGenCode').change(function(){
                $("#oetTcgCode").val("");
                $("#ohdCheckDuplicateTcgCode").val("1");
                if($('#ocbTcgAutoGenCode').is(':checked')) {
                    $("#oetTcgCode").attr("readonly", true);
                    $("#oetTcgCode").attr("onfocus", "this.blur()");
                    $('#ofmAddPdtTouchGrp').removeClass('has-error');
                    $('#ofmAddPdtTouchGrp em').remove();
                }else{
                    $("#oetTcgCode").attr("readonly", false);
                    $("#oetTcgCode").removeAttr("onfocus");
                }
            });
            $("#oetTcgCode").blur(function(){
                if(!$('#ocbTcgAutoGenCode').is(':checked')) {
                    if($("#ohdCheckTcgClearValidate").val()==1){
                        $('#ofmAddPdtTouchGrp').validate().destroy();
                        $("#ohdCheckTcgClearValidate").val("0");
                    }
                    if($("#ohdCheckTcgClearValidate").val()==0){
                        $.ajax({
                            type: "POST",
                            url: "CheckInputGenCode",
                            data: { 
                                tTableName : "TCNMPdtTouchGrp",
                                tFieldName : "FTTcgCode",
                                tCode : $("#oetTcgCode").val()
                            },
                            cache: false,
                            timeout: 0,
                            success: function(tResult){
                                var aResult = JSON.parse(tResult);
                                $("#ohdCheckDuplicateTcgCode").val(aResult["rtCode"]);
                                JSxValidationFormPdtTouchGrp("",$("#ohdTcgRoute").val());
                                $('#ofmAddPdtTouchGrp').submit();
                                
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                JCNxResponseError(jqXHR, textStatus, errorThrown);
                            }
                        });
                    }
                }
            });
            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Functionality : center validate form
//Parameters : function submit name, route
//Creator : 29/03/2019 pap
//Update : -
//Return : -
//Return Type : -
function JSxValidationFormPdtTouchGrp(pFnSubmitName,ptRoute){
    $.validator.addMethod('dublicateCode', function(value, element) {
        if(ptRoute=="pdttouchgrpEventAdd"){
            if($('#ocbTcgAutoGenCode').is(':checked')){
                return true;
            }else{
                if($("#ohdCheckDuplicateTcgCode").val()==1){
                    return false;
                }else{
                    return true;
                }
            }
        }else{
            return true;
        }
    }, '');
    $('#ofmAddPdtTouchGrp').validate({
        rules: {
            oetTcgCode : {
                "required" :{
                // ตรวจสอบเงื่อนไข validate
                depends: function(oElement) {
                    if(ptRoute=="pdttouchgrpEventAdd"){
                        if($('#ocbTcgAutoGenCode').is(':checked')){
                            return false;
                        }else{
                            return true;
                        }
                    }else{
                        return false;
                    }
                }
                },
                "dublicateCode" :{}
            },
            oetTcgName: {
                "required" :{}
            }
        },
        messages: {
            oetTcgCode : {
                "required" :$('#oetTcgCode').attr('data-validate-required'),
                "dublicateCode" : $('#oetTcgCode').attr('data-validate-dublicateCode')
            },
            oetTcgName : {
                "required" :$('#oetTcgName').attr('data-validate-required')
            },
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
            $( element ).closest('.form-group').addClass( "has-error" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).closest('.form-group').removeClass( "has-error" );
        },
        submitHandler: function(form){
            if(pFnSubmitName!=""){
                window[pFnSubmitName](ptRoute);
            }
        }
    });


}

//Functionality : function submit by submit button only
//Parameters : route
//Creator : 29/03/2019 pap
//Update : -
//Return : -
//Return Type : -
function JSxSubmitEventByButton(ptRoute){
    if($("#ohdCheckTcgClearValidate").val()==1){
        JCNxOpenLoading();
        $.ajax({
            type: "POST",
            url: ptRoute,
            data: $('#ofmAddPdtTouchGrp').serialize(),
            cache: false,
            timeout: 0,
            success: function(oResult){
                if(nStaTcgBrowseType != 1) {
                    var aReturn = JSON.parse(oResult);
                    if(aReturn['nStaEvent'] == 1){
                        switch(aReturn['nStaCallBack']) {
                            case '1':
                                JSvCallPagePdtTouchGrpEdit(aReturn['tCodeReturn']);
                                break;
                            case '2':
                                JSvCallPagePdtTouchGrpAdd();
                                break;
                            case '3':
                                JSvCallPagePdtTouchGrpList();
                                break;
                            default:
                                JSvCallPagePdtTouchGrpEdit(aReturn['tCodeReturn']);
                        }
                    }else{
                        alert(aReturn['tStaMessg']);
                    }
                }else{
                    JCNxCloseLoading();
                    JCNxBrowseData(tCallTcgBackOption);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }
}

//Functionality : Call Page Edit Product Touch Group
//Parameters : Event Button Click 
//Creator : 20/09/2018 wasin
//Return : View
//Return Type : View
function JSvCallPagePdtTouchGrpEdit(ptTcgCode){
    JCNxOpenLoading();
    JStCMMGetPanalLangSystemHTML('JSvCallPagePdtTouchGrpEdit',ptTcgCode);
    $.ajax({
        type: "POST",
        url: "pdttouchgrpPageEdit",
        data: { tTcgCode: ptTcgCode },
        cache: false,
        timeout: 0,
        success: function(tResult){
            if(tResult != ''){
                $('#oliTcgTitleAdd').hide();
                $('#oliTcgTitleEdit').show();
                $('#odvBtnTcgInfo').hide();
                $('#odvBtnAddEdit').show();
                $('#odvContentPagePdtTouchGrp').html(tResult);
                $('#oetTcgCode').addClass('xCNDisable');
                $('.xCNDisable').attr('readonly', true);
                $('.xCNiConGen').css('display', 'none');
            }
            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Functionality : set click status submit form from save button
//Parameters : -
//Creator : 26/03/2019 pap
//Return : -
//Return Type : -
function JSxSetStatusClickPdtTouchGrpSubmit(){
    $("#ohdCheckTcgClearValidate").val("1");
}

//Functionality : Event Add/Edit Product Touch Group
//Parameters : From Submit
//Creator : 20/09/2018 wasin
//Return : Status Event Add/Edit Product Touch Group
//Return Type : object
function JSoAddEditPdtTouchGrp(ptRoute){
    if($("#ohdCheckTcgClearValidate").val()==1){
        $('#ofmAddPdtTouchGrp').validate().destroy();
        if(!$('#ocbTcgAutoGenCode').is(':checked')) {
            $.ajax({
                type: "POST",
                url: "CheckInputGenCode",
                data: { 
                    tTableName : "TCNMPdtTouchGrp",
                    tFieldName : "FTTcgCode",
                    tCode : $("#oetTcgCode").val()
                },
                cache: false,
                timeout: 0,
                success: function(tResult){
                    var aResult = JSON.parse(tResult);
                    $("#ohdCheckDuplicateTcgCode").val(aResult["rtCode"]);
                    JSxValidationFormPdtTouchGrp("JSxSubmitEventByButton",ptRoute);
                    $('#ofmAddPdtTouchGrp').submit();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            JSxValidationFormPdtTouchGrp("JSxSubmitEventByButton",ptRoute);
        }
        
    }
}

//Functionality : Generate Code Product Touch Group
//Parameters : Event Button Click
//Creator : 20/09/2018 wasin
//Return : Event Push Value In Input
//Return Type : object
function JSoGeneratePdtTouchGrpCode(){
    $('#oetTcgCode').parent().removeClass('alert-validate');
    var tTableName = 'TCNMPdtTouchGrp';
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "generateCode",
        data: { tTableName: tTableName },
        cache: false,
        timeout: 0,
        success: function(oResult) {
            var aData = $.parseJSON(oResult);
            if (aData['rtCode'] == '1') {
                $('#oetTcgCode').val(aData['rtTcgCode']);
                $('#oetTcgCode').addClass('xCNDisable');
                $('.xCNDisable').attr('readonly', true);
                //----------Hidden ปุ่ม Gen
                $('.xCNBtnGenCode').attr('disabled', true);
                $('#oetTcgName').focus();
            }else{
                $('#oetTcgCode').val(tData.rtDesc);
            }
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Functionality : Event Single Delete
//Parameters : Event Icon Delete
//Creator : 20/09/2018 wasin
//Return : object Status Delete
//Return Type : object
function JSoPdtTouchGrpDel(pnPage,ptName,tIDCode){
    var aData = $('#ohdConfirmIDDelete').val();
    var aTexts = aData.substring(0, aData.length - 2);
    var aDataSplit = aTexts.split(" , ");
    var aDataSplitlength = aDataSplit.length;
    var aNewIdDelete = [];

    if (aDataSplitlength == '1') {

        $('#odvModalDelPdtTouchGrp').modal('show');
        $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + tIDCode + ' ( ' + ptName + ' ) ');
        $('#osmConfirm').on('click', function(evt) {

            if (localStorage.StaDeleteArray != '1') {

                $.ajax({
                    type: "POST",
                    url: "pdttouchgrpEventDelete",
                    data: { 'tIDCode': tIDCode},
                    cache: false,
                    success: function(tResult) {
                        tResult = tResult.trim();
                        var aReturn = $.parseJSON(tResult);

                        // $('#odvModalDelPdtTouchGrp').modal('hide');
                        // $('#ospConfirmDelete').text($('#oetTextComfirmDeleteSingle').val());
                        // $('#ohdConfirmIDDelete').val('');
                        // localStorage.removeItem('LocalItemData');
                        // $('.modal-backdrop').remove();
                        // JSvPdtTouchGrpDataTable(pnPage);
                        if (aReturn['nStaEvent'] == '1'){
                            $('#odvModalDelPdtTouchGrp').modal('hide');
                            $('#ospConfirmDelete').empty();
                            localStorage.removeItem('LocalItemData');
                            $('#ospConfirmIDDelete').val('');
                            $('#ohdConfirmIDDelete').val('');
                            setTimeout(function() {
                                if(aReturn["nNumRowTCG"]!=0){
                                    if(aReturn["nNumRowTCG"]>10){
                                        nNumPage = Math.ceil(aReturn["nNumRowTCG"]/10);
                                        if(pnPage<=nNumPage){
                                            JSvPdtTouchGrpDataTable(pnPage);
                                        }else{
                                            JSvPdtTouchGrpDataTable(nNumPage);
                                        }
                                    }else{
                                        JSvPdtTouchGrpDataTable(1);
                                    }
                                }else{
                                    JSvPdtTouchGrpDataTable(1);
                                }
                            }, 500);
                        }else{
                            JCNxOpenLoading();
                            alert(tData['tStaMessg']);                        
                        }
                        JSxTcgNavDefult();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }


        });
    }
    // var aData               = $('#ospConfirmIDDelete').val();
    // var aTexts              = aData.substring(0, aData.length - 2);
    // var aDataSplit          = aTexts.split(" , ");
    // var aDataSplitlength    = aDataSplit.length;
    // var aNewIdDelete        = [];
    // if (aDataSplitlength == '1'){
    //     $('#odvModalDelPdtTouchGrp').modal('show');
    //     $('#ospConfirmDelete').html('ยืนยันการลบข้อมูล หมายเลข : ' + tIDCode);
    //     $('#osmConfirm').on('click', function(evt){
    //         JCNxOpenLoading();
    //         $.ajax({
    //             type: "POST",
    //             url: "pdttouchgrpEventDelete",
    //             data: { 'tIDCode': tIDCode },
    //             cache: false,
    //             timeout: 0,
    //             success: function(oResult){
    //                 var aReturn = JSON.parse(oResult);
    //                 if (aReturn['nStaEvent'] == 1){
    //                     $('#odvModalDelPdtTouchGrp').modal('hide');
    //                     $('#ospConfirmDelete').text('ยืนยันการลบข้อมูลของ : ');
    //                     $('#ospConfirmIDDelete').val('');
    //                     localStorage.removeItem('LocalItemData');
    //                     setTimeout(function() {
    //                         JSvCallPagePdtTouchGrpList();
    //                     }, 500);
    //                 }else{
    //                     alert(aReturn['tStaMessg']);                        
    //                 }
    //                 JSxTcgNavDefult();
    //             },
    //             error: function(jqXHR, textStatus, errorThrown) {
    //                 JCNxResponseError(jqXHR, textStatus, errorThrown);
    //             }
    //         });
    //     });
    // }
}

//Functionality: Event Multi Delete
//Parameters: Event Button Delete All
//Creator: 20/09/2018 wasin
//Return:  object Status Delete
//Return Type: object
function JSoPdtTouchGrpDelChoose(pnPage){

    JCNxOpenLoading();

    var aData = $('#ohdConfirmIDDelete').val();
    var aTexts = aData.substring(0, aData.length - 2);
    var aDataSplit = aTexts.split(" , ");
    var aDataSplitlength = aDataSplit.length;
    var aNewIdDelete = [];

    for ($i = 0; $i < aDataSplitlength; $i++) {
        aNewIdDelete.push(aDataSplit[$i]);
    }

    if (aDataSplitlength > 1) {

        localStorage.StaDeleteArray = '1';

        $.ajax({
            type: "POST",
            url: "pdttouchgrpEventDelete",
            data: { 'tIDCode': aNewIdDelete },
            success: function(tResult) {
                
                // JSxTcgNavDefult();
                // setTimeout(function() {
                //     $('#odvModalDelPdtTouchGrp').modal('hide');
                //     JSvPdtTouchGrpDataTable(pnPage);
                //     $('#ospConfirmDelete').text($('#oetTextComfirmDeleteSingle').val());
                //     $('#ohdConfirmIDDelete').val('');
                //     localStorage.removeItem('LocalItemData');
                //     $('.obtChoose').hide();
                //     $('.modal-backdrop').remove();
                // }, 1000);
                var aReturn = $.parseJSON(tResult);
                if (aReturn['nStaEvent'] == '1'){
                    $('#odvModalDelPdtTouchGrp').modal('hide');
                    $('#ospConfirmDelete').empty();
                    localStorage.removeItem('LocalItemData');
                    $('#ospConfirmIDDelete').val('');
                    $('#ohdConfirmIDDelete').val('');
                    setTimeout(function() {
                        if(aReturn["nNumRowTCG"]!=0){
                            if(aReturn["nNumRowTCG"]>10){
                                nNumPage = Math.ceil(aReturn["nNumRowTCG"]/10);
                                if(pnPage<=nNumPage){
                                    JSvPdtTouchGrpDataTable(pnPage);
                                }else{
                                    JSvPdtTouchGrpDataTable(nNumPage);
                                }
                            }else{
                                JSvPdtTouchGrpDataTable(1);
                            }
                        }else{
                            JSvPdtTouchGrpDataTable(1);
                        }
                    }, 500);
                }else{
                    JCNxOpenLoading();
                    alert(tData['tStaMessg']);                        
                }
                JSxTcgNavDefult();


            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });


    } else {
        localStorage.StaDeleteArray = '0';

        return false;
    }
    // JCNxOpenLoading();
    // var aData       = $('#ospConfirmIDDelete').val();
    // var aTexts      = aData.substring(0, aData.length - 2);
    // var aDataSplit  = aTexts.split(" , ");
    // var aDataSplitlength = aDataSplit.length;
    // var aNewIdDelete = [];
    // for ($i = 0; $i < aDataSplitlength; $i++) {
    //     aNewIdDelete.push(aDataSplit[$i]);
    // }
    // if (aDataSplitlength > 1){
    //     localStorage.StaDeleteArray = '1';
    //     $.ajax({
    //         type: "POST",
    //         url: "pdttouchgrpEventDelete",
    //         data: { 'tIDCode': aNewIdDelete },
    //         cache: false,
    //         timeout: 0,
    //         success: function(oResult) {
    //             var aReturn = JSON.parse(oResult);
    //             if (aReturn['nStaEvent'] == 1) {
    //                 setTimeout(function() {
    //                     $('#odvModalDelPdtTouchGrp').modal('hide');
    //                     JSvCallPagePdtTouchGrpList();
    //                     $('#ospConfirmDelete').text('ยืนยันการลบข้อมูลของ : ');
    //                     $('#ospConfirmIDDelete').val('');
    //                     localStorage.removeItem('LocalItemData');
    //                     $('.modal-backdrop').remove();
    //                 },1000);
    //             }else{
    //                 alert(aReturn['tStaMessg']);
    //             }
    //             JSxTcgNavDefult();
    //         },
    //         error: function(jqXHR, textStatus, errorThrown) {
    //             JCNxResponseError(jqXHR, textStatus, errorThrown);
    //         }
    //     });
    // }else{
    //     localStorage.StaDeleteArray = '0';
    //     return false;
    // }
}

//Functionality : เปลี่ยนหน้า pagenation
//Parameters : Event Click Pagenation
//Creator : 17/09/2018 wasin
//Return : View
//Return Type : View
function JSvPdtTouchGrpClickPage(ptPage) {
    var nPageCurrent = '';
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld = $('.xWPageTouchGrpType .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld = $('.xWPageTouchGrpType .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JCNxOpenLoading();
    JSvPdtTouchGrpDataTable(nPageCurrent);
}

//Functionality: Function Chack And Show Button Delete All
//Parameters: LocalStorage Data
//Creator: 20/09/2018 wasin
//Return: - 
//Return Type: -
function JSxShowButtonChoose() {
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

//Functionality: Insert Text In Modal Delete
//Parameters: LocalStorage Data
//Creator: 20/09/2018 wasin
//Return: -
//Return Type: -
function JSxTextinModal() {
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

//Functionality: Function Chack Value LocalStorage
//Parameters: Event Select List Reason
//Creator: 20/09/2018 wasin
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