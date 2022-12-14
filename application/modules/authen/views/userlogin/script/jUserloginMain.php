<script type="text/javascript">
    $(document).ready(function () {
        JSvUsrloginList(1);
    });


  
    //function : Call PosAds Page list  
    //Parameters : Document Redy And Event Button
    //Creator :	19/08/2019 Witsarut (Bell)
    //Return : View
    //Return Type : View
    function JSvUsrloginList(nPage){
        var tUsrLogCode    =   $('#ohdUsrLogCode').val();
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "userloginDataTable",
            data    : {
                tUsrLogCode         : tUsrLogCode,
                nPageCurrent        : nPage,
                tSearchAll          : ''
            },
            cache   : false,
            Timeout : 0,
            async   : false,
            success : function(tView){
                $('#odvContentUsrloginDataTable').html(tView);
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }




    //Functionality : Call Credit Page Add  
    //Parameters : -
    //Creator : 10/06/2019 Witsarut(Bell)
    //Return : View
    //Return Type : View
    function JSvCallPageUserloginAdd(){
        var tUsrLogCode    =   $('#ohdUsrLogCode').val();

        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "userloginPageAdd",
            data    : {
                tUsrCode    :  tUsrLogCode
            },
            cache: false,
            timeout: 5000,
            success: function(tResult){
                $('#odvUsrloginData').html(tResult);
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
    //Creator : 10/06/2019 witsarut (Bell)
    //Return : View
    //Return Type : View
    function JSvCallPageUserloginEdit(ptUsrLogin){

        JCNxOpenLoading();
        var tUsrLogCode    =   $('#ohdUsrLogCode').val();

        $.ajax({
            type: "POST",
            url:  "userloginPageEdit",
            data:{
                tUsrLogCode :  tUsrLogCode,
                tUsrLogin   :   ptUsrLogin
            },
            cache:  false,
            timeout: 5000,
            success: function(tResult){
                $('#odvUsrloginData').html(tResult);
                $('.xWPageAdd').addClass('hidden');
                $('.xWPageEdit').removeClass('hidden');
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });

    }




    //Functionality : Add Data Agency Add/Edit  
    //Parameters : from ofmAddEditUserLogin
    //Creator : 04/07/2019 witsarut (Bell)
    //Return : View
    //Return Type : View
    function JSxUSRLSaveAddEdit(ptRoute){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
           $('#ofmAddEditUserLogin').validate().destroy();
           $.validator.addMethod('dublicateCode', function(value, element) {
                if($("#ohdValidateDuplicate").val()==1){
                    if($("#ocmlogintype").val()==1 || $("#ocmlogintype").val()==2){
                        if($(element).attr("id")=="oetidUsrlogin"){
                            return false;
                        }else{
                            return true;
                        }
                    }else{
                        if($(element).attr("id")=="oetidUsrlogPw"){
                            return false;
                        }else{
                            return true;
                        }
                    }
                    return false;
                }else{
                    return true;
                }
           });
            $('#ofmAddEditUserLogin').validate({
                focusInvalid: false,
                onclick: false,
                onfocusout: false,
                onkeyup: false,
                rules: {
                    oetidUsrlogin  : {
                        "required" :{
                            depends: function (oElement) {
                                if($("#ohdTRoute").val()=="userloginEventAdd"){
                                    return true;
                                }else{
                                    return false;
                                }
                            }
                        },
                        "dublicateCode":{}
                    },
                    oetidUsrlogPw  : {
                        "required" :{
                            depends: function (oElement) {
                                if($("#ohdTRoute").val()=="userloginEventAdd"){
                                    return true;
                                }else{
                                    return false;
                                }
                            }
                        },
                        "dublicateCode":{}
                    },
                },
                messages: {
                    oetidUsrlogin : {
                        "required"      : $('#oetidUsrlogin').attr('data-validate-required'),
                        "dublicateCode" : "????????????????????????????????????"
                    },
                    oetidUsrlogPw : {
                        "required"      : $('#oetidUsrlogPw').attr('data-validate-required'),
                        "dublicateCode" : "????????????????????????????????????"
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
                highlight: function(element, errorClass, validClass) {
                    $( element ).closest('.form-group').addClass( "has-error" ).removeClass( "has-success" );
                },
                unhighlight: function(element, errorClass, validClass) {
                    $( element ).closest('.form-group').addClass( "has-success" ).removeClass( "has-error" );
                },
                submitHandler: function(form) {

                    // updated ?????????????????????????????????
                    // ------------ Create by Witsarut 31/08/2019 -------------

                    //    tOldPassword     = $('#oetUsrPasswordOld').val(); 
                    //    tPassword        = $('#oetidUsrlogPw').val();

                    //    if(tPassword == '******'){
                    //         $('#oetidUsrlogPw').val(tOldPassword);
                    //    }else{
                    //         var tEncPassword = JCNtAES128EncryptData(tPassword, tKey, tIV);

                    //         if(tEncPassword == tOldPassword){
                    //             $('#oetidUsrlogPw').val(tOldPassword);
                    //         }else{
                    //             $('#oetidUsrlogPw').val(tEncPassword);
                    //         }
                    //    }

                    if($("#ocmlogintype").val()==3 || $("#ocmlogintype").val()==4){
                        $("#oetUsrloginPasswordOld").val(JCNtAES128EncryptData($("#oetidUsrlogin").val(),tKey,tIV));
                    }else{
                        $("#oetUsrloginPasswordOld").val(JCNtAES128EncryptData($("#oetidUsrlogPw").val(),tKey,tIV));
                    }
                    
                    let tUsrlogPw       =  $('#oetidUsrlogPw').val();
                    let tPasswordCheck  =  $('#oetUsrloginPasswordCheck').val();
                    let tPasswordOld    =  $('#oetUsrloginPasswordOld').val();
                    // ?????????????????????????????????????????? /16/03/2020 saharat
                    if(tUsrlogPw == tPasswordCheck){
                        $("#oetUsrloginPasswordOld").val(tPasswordCheck);
                    }
                
                // ------------ Create by Witsarut 31/08/2019 -------------

                    $.ajax({
                        type: "POST",
                        url: ptRoute,
                        data:  $('#ofmAddEditUserLogin').serialize(),
                        cache: false,
                        timeout: 0,
                        success: function(tResult) {
                            var aData = JSON.parse(tResult);
                            if(aData["nStaEvent"]==1){
                                JSxUsrloginGetContent();
                            }else{
                                $("#ohdValidateDuplicate").val(1);
                                JSxUSRLSaveAddEdit(ptRoute);
                                $('#ofmAddEditUserLogin').submit();
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
    //Parameters: Button Event [tIDCode tUsrCode]
    //Creator: 10/05/2018 Witsarut (Bell)
    //Update: -
    //Return: Event Delete Reason List
    //Return Type: -
    function JSxUSRLDelete(ptUsrloginCode, tYesOnNo){
        $('#odvModalDeleteSingle').modal('show');
        $('#odvModalDeleteSingle #ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + ptUsrloginCode + ' '+ tYesOnNo );
        $('#odvModalDeleteSingle #osmConfirmDelete').on('click', function(evt) {
            $.ajax({
                type:   "POST",
                url:    "userloginEventDelete",
                data: {
                    tUsrloginCode   : ptUsrloginCode,
                },
                cache: false,
                success: function(tResult){
                    $('#odvModalDeleteSingle').modal('hide');
                    setTimeout(function(){
                        JSvUsrloginList(1);
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
    //Creator : 11/06/2019 Witsarut (Bell)
    //Return : 
    //Return Type :
    function JSxUSRLDeleteMutirecord(pnPage){

        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JCNxOpenLoading();
            var aDataUsrCode    =[];
            var aDataLogType    =[];
            var aDataPwStart    =[];
            var ocbListItem     = $(".ocbListItem");
            for(var nI = 0;nI<ocbListItem.length;nI++){
                if($($(".ocbListItem").eq(nI)).prop('checked')){
                    aDataUsrCode.push($($(".ocbListItem").eq(nI)).attr("ohdConfirmUsrCodeDelete"));
                    aDataLogType.push($($(".ocbListItem").eq(nI)).attr("ohdConfirmLogTypeDelete"));
                    aDataPwStart.push($($(".ocbListItem").eq(nI)).attr("ohdConfirmPwdStartDelete"));
                }
            }

            $.ajax({
                type: "POST",
                url: "userloginEventDeleteMultiple",
                data: {
                    'paDataUsrCode'   : aDataUsrCode,
                    'paDataLogType'   : aDataLogType,
                    'paDataPwStart'   : aDataPwStart,
                },
                cache: false,
                success: function (tResult){
                    tResult = tResult.trim();
                    var aReturn = $.parseJSON(tResult);
                    if(aReturn['nStaEvent'] == '1'){
                        $('#odvModalDeleteMutirecord').modal('hide');
                        $('#ospConfirmDelete').empty();
                        localStorage.removeItem('LocalItemData');

                        setTimeout(function(){
                            JSvUsrloginList(pnPage);
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
    //Creator: 05/07/2019 witsarut (Bell)
    //Return: - 
    //Return Type: -
    function JSxUSRLShowButtonChoose() {
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
    //Creator: 05/07/2019 witsarut (Bell)
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
    function JSxUSRLPaseCodeDelInModal() {
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
   

    //Functionality: ????????????????????????????????? pagenation
    //Parameters: -
    //Creator: 09/05/2018 Witsarut
    //Update: -
    //Return: View
    //Return Type: View
    function JSvUSRLClickPage(ptPage){
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //?????????????????? Next
                $(".xWBtnNext").addClass("disabled");
                nPageOld = $(".xWUSRLPaging .active").text(); // Get ?????????????????????????????????
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 ???????????????
                nPageCurrent = nPageNew;
            break;
            case "previous": //?????????????????? Previous
                nPageOld = $(".xWUSRLPaging .active").text(); // Get ?????????????????????????????????
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 ???????????????
                nPageCurrent = nPageNew;
            break;
            default:
            nPageCurrent = ptPage;
        }
        JSvUsrloginList(nPageCurrent);
    }


    // Create By : Witsarut
    // Functionality : Control Input User/Password From Login Type Selected
    // Parameters : -
    // Creator: 19/08/2019
    // Return : -
    // Return Type : -
    function JSxUSRLCheckLoginTypeUsed(ptType){

        //  ????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
        nLoginType = $('#ocmlogintype').val();

        /* ??????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????? Username/Password ???????????????????????????????????????????????????
        1 ???????????????????????? ????????????????????? ?????????????????????????????? ????????? ????????????????????????
        2 PIN ???????????????????????????????????????????????????????????? ????????? PIN
        3 RFID ????????????????????? RFID Code ???????????????????????? Coppy ??????????????????????????? ????????????????????????(Password)
        4 QR ????????????????????? QR Code ???????????????????????? Coppy ??????????????????????????? ????????????????????????(Password) 
        */

        switch (nLoginType){
            case '1' :
                        JSxUSRLControlInputTypePassword();
                        // ???????????? Input Password
                        JSxUSRLControlPwsPanalShow();
            break;
            case '2' :
                        JSxUSRLControlInputTypePIN();
                        // ???????????? Input Password
                        JSxUSRLControlPwsPanalShow();
            break;
            case '3' :
                        JSxUSRLControlInputTypeRFID();
                        // ???????????? Input Password ?????????????????????????????????????????????????????? RFID
                        JSxUSRLControlPwsPanalHide();
            break;
            case '4' :
                        JSxUSRLControlInputTypeQRCODE();
                        // ???????????? Input Password ?????????????????????????????????????????????????????? RFID
                        JSxUSRLControlPwsPanalHide();
            break;
            
            default:
                    JSxUSRLControlInputTypePassword();
                    // ???????????? Input Password
                    JSxUSRLControlPwsPanalShow();
        }

            // Reset ????????? User / Passwrod ????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
            if(ptType == 'insert'){
                JSxUSRLControlInputResetVal();
            }
    }

    // Create By : Witsarut
    // Functionality : Control Input User/Password Type Password
    // Parameters : -
    // Creator: 19/08/2019
    // Return : -
    // Return Type :
    function JSxUSRLControlInputTypePassword(){
        //????????????????????????????????? Password
        //????????????
        $('#olbUSRLLocinAcc').show(); // Label ??????????????????????????????
        $('#olbUSRLPassword').show(); // Label ????????????????????????

        //Placeholder
        tHolderLocAcc = $('#olbUSRLLocinAcc').first().contents().eq(2).text().trim(); //??????????????????????????? Label ??????????????????????????????????????? Input
        $("#oetidUsrlogin").attr("placeholder",tHolderLocAcc);

        tHolderLocPw = $('#olbUSRLPassword').first().contents().eq(2).text().trim(); //??????????????????????????? Label ??????????????????????????????????????? Input
        $("#oetidUsrlogPw").attr("placeholder",tHolderLocPw);

        //Validate Input Account
        $('#oetidUsrlogin').removeClass('xCNInputNumericWithoutDecimal');

        //????????????
        $('#olbUSRLTelNo').hide(); // Label ???????????????????????????????????????
        $('#olbUSRLRFID').hide();  // Label RFID
        $('#olbUSRLQRCode').hide();  // Label QR Code
        $('#olbUSRLPin').hide();   // Label PIN
    }


    // Create By : Witsarut
    // Functionality : Control Input User/Password Type Password
    // Parameters : -
    // Creator: 10/08/2019
    // Return : -
    // Return Type :
    function JSxUSRLControlInputTypePIN(){
        //????????????????????????????????? PIN    
        //????????????
        $('#olbUSRLTelNo').show(); // Label ???????????????????????????????????????
        $('#olbUSRLPin').show();   // Label PIN

        //Placeholder Input UsrLoginID
        tHolderLocAcc = $('#olbUSRLTelNo').first().contents().eq(2).text().trim(); //??????????????????????????? Label ??????????????????????????????????????? Input
        $("#oetidUsrlogin").attr("placeholder",tHolderLocAcc);

        //Placeholder Input UsrLoginPassword
        tHolderLocPw = $('#olbUSRLPin').first().contents().eq(2).text().trim(); //??????????????????????????? Label ??????????????????????????????????????? Input
        $("#oetidUsrlogPw").attr("placeholder",tHolderLocPw);

        //Validate Input Account
        $('#oetidUsrlogin').addClass('xCNInputNumericWithoutDecimal');

        //????????????
        $('#olbUSRLLocinAcc').hide(); // Label ??????????????????????????????
        $('#olbUSRLPassword').hide(); // Label ????????????????????????
        $('#olbUSRLRFID').hide();  // Label RFID
        $('#olbUSRLQRCode').hide();  // Label QR Code

    }

    // Create By : Witsarut
    // Functionality : Control Input User/Password Type RFID
    // Parameters : -
    // Creator: 10/08/2019
    // Return : -
    // Return Type : 
    function JSxUSRLControlInputTypeRFID(){
        //????????????????????????????????? RFID    
        //????????????
        $('#olbUSRLRFID').show();  // Label RFID
        
        //Placeholder Input UsrLoginID
        tHolderLocAcc = $('#olbUSRLRFID').first().contents().eq(2).text().trim(); //??????????????????????????? Label ??????????????????????????????????????? Input
        $("#oetidUsrlogin").attr("placeholder",tHolderLocAcc);

        //Placeholder Input UsrLoginPassword
        tHolderLocPw = $('#olbUSRLRFID').first().contents().eq(2).text().trim(); //??????????????????????????? Label ??????????????????????????????????????? Input
        $("#oetidUsrlogPw").attr("placeholder",tHolderLocPw);

        //Validate Input Account
        $('#oetidUsrlogin').removeClass('xCNInputNumericWithoutDecimal');

        //????????????
        $('#olbUSRLLocinAcc').hide(); // Label ??????????????????????????????
        $('#olbUSRLTelNo').hide(); // Label ???????????????????????????????????????
        $('#olbUSRLQRCode').hide();  // Label QR Code

    }


    // Create By : Witsarut
    // Functionality : Control Input User/Password Type QRCODE
    // Parameters : -
    // Creator: 10/08/2019
    // Return : -
    // Return Type : 
    function JSxUSRLControlInputTypeQRCODE(){
        //????????????????????????????????? QRCODE  
        //????????????
        $('#olbUSRLQRCode').show();  // Label RFID
        //Placeholder Input UsrLoginID
        tHolderLocAcc = $('#olbUSRLQRCode').first().contents().eq(2).text().trim(); //??????????????????????????? Label ??????????????????????????????????????? Input
        $("#oetidUsrlogin").attr("placeholder",tHolderLocAcc);

        //Placeholder Input UsrLoginPassword
        tHolderLocPw = $('#olbUSRLQRCode').first().contents().eq(2).text().trim(); //??????????????????????????? Label ??????????????????????????????????????? Input
        $("#oetidUsrlogPw").attr("placeholder",tHolderLocPw);


        //Validate Input Account
        $('#oetidUsrlogin').removeClass('xCNInputNumericWithoutDecimal');

        //????????????
        $('#olbUSRLLocinAcc').hide(); // Label ??????????????????????????????
        $('#olbUSRLTelNo').hide(); // Label ???????????????????????????????????????
        $('#olbUSRLRFID').hide();  // Label RFID
    }


    // Create By : Witsarut
    // Functionality : Reset Password Type Password
    // Parameters : -
    // Creator: 10/08/2019
    // Return : -
    // Return Type : 
    function JSxUSRLControlInputResetVal(){

        $('#oetidUsrlogin').val('');
        $('#oetidUsrlogPw').val('');
    }

    // Create By : Witsarut
    // Functionality : Hidden Password Panel
    // Parameters : -
    // Creator: 10/08/2019
    // Return : -
    // Return Type : 
    function JSxUSRLControlPwsPanalHide(){
        $('#odvUSRLPwsPanel').hide();  // Password Panel
    }



    // Create By : Witsarut
    // Functionality : Show Password Panel
    // Parameters : -
    // Creator: 10/08/2019
    // Return : -
    // Return Type : 
    function JSxUSRLControlPwsPanalShow(){
        $('#odvUSRLPwsPanel').show();  // Password Panel
    }


    $('.xCNDatePicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });


    $('#obtUsrlogStart').click(function(event){
        $('#oetUsrlogStart').datepicker('show');
    });

    $('#obtUsrlogStop').click(function(event){
        $('#oetUsrlogStop').datepicker('show');
    });

    $('#ocmlogintype').selectpicker();

</script>