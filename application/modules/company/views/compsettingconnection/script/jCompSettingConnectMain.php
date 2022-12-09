<script type="text/javascript">
    $(document).ready(function(){
        JSvCompSetConnectionList(1);
        switch ($("#ocmUrlConnecttype").val()){
            case '9' :
                JSxCompSettingConControlPanalHide();
            break;
            case '10' :
                JSxCompSettingConControlPanalHide();
            break;
            case '11' :
                JSxCompSettingConControlPanalHide();
            break;
            default:
                JSxCompSettingConControlPanalHide();
        }   
    });


    // Functionality : Control Input 
    // Parameters : -
    // Create By : Witsarut (bell)
    // Creator: 12/09/2019
    // Return : -
    // Return Type : -
    function  JSxCompSettingConTypeUsed(ptType){
        //  ดึงค่าตัวเลือกจากตัวเลือกประเภทการเข้าใช้งาน
        nSelectType = $('#ocmUrlConnecttype').val();

        switch(nSelectType){
            case '9' :
                JSxCompSettingConControlPanalHide();
            break;
            case '10' :
                JSxCompSettingConControlPanalHide();
            break;
            case '11' :
                JSxCompSettingConControlPanalHide();
            break;

            default:
            JSxCompSettingConControlPanalHide();
        }   

           // Reset ค่า ทุกครั้งกรณีมีการเปลี่ยน
        if(ptType == 'insert'){
            JSxCompControlInputResetVal();
        }
    }

    // Create By : Witsarut
    // Functionality : Hide Panel 1,2,3,4,5
    // Parameters : -
    // Creator: 12/09/2019
    // Return : -
    // Return Type : 
    function JSxCompSettingConControlPanalHide(){
        $('#odvPanelUrl').show(); 
    }

    // Create By Witsarut 20/10/2019
    //function : Reset ค่า ใน input  
    function JSxCompControlInputResetVal(){
        nSelectType = $('#ocmUrlConnecttype').val();

        switch(nSelectType){
            case '9' :
                $('#oetCompServerip').val('');
                $('#oetCompPortConnect').val('');
                $('#oetCompUrlKey').val('');
            break;
            case '10' :
                $('#oetCompServerip').val('');
                $('#oetCompPortConnect').val('');
                $('#oetCompUrlKey').val('');
            break;
            case '11' :
                $('#oetCompServerip').val('');
                $('#oetCompPortConnect').val('');
                $('#oetCompUrlKey').val('');
            break;
        }
    }

    //function : Call  CompSettingConnect Page list  
    //Parameters : Document Redy And Event Button
    //Creator :	19/10/2019 Witsarut (Bell)
    //Return : View
    //Return Type : View
    function JSvCompSetConnectionList(nPage){
        var ptCompCode =  $('#ohdCompCode').val();
        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "CompSettingConDataTable",
            data    : {
                tCompCode   :  ptCompCode,
                tSearchAll  : '',
                nPageCurrent  : nPage
            },
            cache : false,
            timeout : 0,
            success  : function (tResult){
                $('#odvContentCompSetConnectDataTable').html(tResult);
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }


    //Functionality : Call CompSettingConnect Page Add  
    //Parameters : -
    //Creator : 11/09/2019 Witsarut(Bell)
    //Return : View
    //Return Type : View
    function JSvCallPageCompSetConnectAdd(){
        var ptCompCode = $('#ohdCompCode').val();

        JCNxOpenLoading();
        $.ajax({
            type    : "POST",
            url     : "CompSettingConPageAdd",
            data  : {
                tCompCode   : ptCompCode
            },
            cache: false,
            timeout: 5000,
            success : function (tResult){
                $('#odvInforSettingConTab').html(tResult);
                JSxCompSettingConControlPanalHide(); 
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }


    //Functionality : Call Credit Page Edit  
    //Parameters : -
    //Creator : 19/10/2019 witsarut (Bell)
    //Return : View
    //Return Type : View
    function JSvCallPageCompSettingConnectEdit(ptUrlID){
        JCNxOpenLoading();
        var ptCompCode = $('#ohdCompCode').val();

     
        $.ajax({
            type : "POST",
            url  : "CompSettingConPageEdit",
            data : {
                tCompCode :  ptCompCode,
                tUrlID   : ptUrlID
            },
            cache: false,
            timeout:5000,
            success: function (tResult){
                $('#odvInforSettingConTab').html(tResult);
                JCNxCloseLoading();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }

    //Functionality : Add Data CompSettingConnection Add/Edit  
    //Parameters : from ofmAddEditCompSettingConnect
    //Creator : 19/10/2019 witsarut (Bell)
    //Return : View
    //Return Type : View
    function JSxCompSettingConnectSaveAddEdit(ptRoute){
        var nStaSession = JCNxFuncChkSessionExpired();
        $('#ofmAddEditCompSettingConnect').validate().destroy();
            $.validator.addMethod('dublicateCode', function(value, element) {
                if($("#ohdValidateDuplicate").val()==1){
                    if($("#ocmUrlConnecttype").val()==9 || $("#ocmUrlConnecttype").val()==10 || $("#ocmUrlConnecttype").val()==11){
                        if($(element).attr("id")=="oetCompServerip"){
                            return false;
                        }else{
                            return true;
                        }
                    }else{
                        return true;
                    }
                }else{
                    return true;
                }
            });
            $('#ofmAddEditCompSettingConnect').validate({
                focusInvalid: false,
                onclick: false,
                onfocusout: false,
                onkeyup: false,
                rules: {
                    oetCompServerip  : {
                        "required" :{
                            depends: function (oElement) {
                                if($("#ohdTRoute").val()=="CompSettingConEventAdd"){
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
                    oetCompServerip: {
                        "required": $('#oetCompServerip').attr('data-validate-required'),
                        "dublicateCode" : "ไม่สามารถกรอกข้อมูลได้เนื่องจากมีข้อมูลแล้ว"
                        // "dublicateCode": $('#oetCompServerip').attr('data-validate-dublicateCode')
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
                    JCNxOpenLoading();
                    $.ajax({
                        type: "POST",
                        url: ptRoute,
                        data: $('#ofmAddEditCompSettingConnect').serialize(),
                        cache: false,
                        timeout: 0,
                        success: function (tResult){
                            console.log(tResult);
                            var aData = JSON.parse(tResult);
                            if(aData["nStaEvent"] == 900){
                                $('#oetBchServerip').focus();
                                // $("#ohdValidateDuplicate").val(1);
                                // JSxCompSettingConnectSaveAddEdit(ptRoute);
                                // $('#ofmAddEditCompSettingConnect').submit();
                                JCNxCloseLoading();
                            }else if(aData["nStaEvent"] == 1){
                                JSxCompSettingConnect();
                                JCNxCloseLoading();
                            }else if(aData["nStaEvent"] == 800){
                                JSxSaveAgain();
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


    function  JSxSaveAgain(){
        $.ajax({
            type: "POST",
            url: 'CompSettingConEventAdd',
            data:  $('#ofmAddEditCompSettingConnect').serialize(),
            cache: false,
            timeout: 0,
            success: function (tResult){
                var aData = JSON.parse(tResult);
                console.log(aData);
                if(aData["nStaEvent"] == 900){
                    // $('#oetBchServerip').focus();
                    // $("#ohdValidateDuplicate").val(1);
                    // $('#ofmAddEditCompSettingConnect').submit();
                    JCNxCloseLoading();
                }else if(aData["nStaEvent"] == 1){
                    JSxCompSettingConnect();
                }else{
                    var tMsgErrorFunction   = aData['tStaMessg'];
                    FSvCMNSetMsgErrorDialog('<p class="text-left">'+tMsgErrorFunction+'</p>');
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    }



    //Functionality: (event) Delete
    //Parameters: Button Event [tIDCode tUsrCode]
    //Creator: 19/09/2019 Witsarut (Bell)
    //Update: -
    //Return: Event Delete Reason List
    //Return Type: -
    function JSxCompSettingConnectDelete(ptUrlType,ptUrlAddress,ptUrlID,tYesOnNo){
        $('#odvModalDeleteSingle').modal('show');
        $('#odvModalDeleteSingle #ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + ptUrlID + ' (' + ptUrlAddress  + ') ' + tYesOnNo );
        $('#odvModalDeleteSingle #osmConfirmDelete').on('click', function(evt) {
            $.ajax({
                type: "POST",
                url:  "CompSettingConEventDelete",
                data: {
                    tUrlAddress : ptUrlAddress,
                    tUrlID      : ptUrlID,
                    tUrlType    : ptUrlType,
                },
                cache: false,
                success: function(tResult){
                    $('#odvModalDeleteSingle').modal('hide');
                    setTimeout(function(){
                        JSvCompSetConnectionList(1);
                    },500);
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
    function JSxCompSettingConDeleteMutirecord(pnPage,ptUrlType,ptUrlAddress,ptUrlID){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JCNxOpenLoading();
            var aDataUrlId =  $('#ohdConfirmIDDeleteMutirecordUrlId').val();
            var aDataUrlAddress =  $('#ohdConfirmIDDeleteMutirecordAddress').val();
            var aUrlId = aDataUrlId.substring(0, aDataUrlId.length - 2);
            var aAddress  = aDataUrlAddress.substring(0, aDataUrlAddress.length - 2);
            var aDataSplitUrlId    = aUrlId.split(" , ");
            var aDataSplitAddr     = aAddress.split(" , ");
            var aDataSplitlength   = aDataSplitAddr.length;
            var aNewUrlIdDelete  = [];
            var aNewAddrDelete   = [];
            for ($i = 0; $i < aDataSplitlength; $i++) {
                aNewUrlIdDelete.push(aDataSplitUrlId[$i]);
                aNewAddrDelete.push(aDataSplitAddr[$i]);
            }
            if(aDataSplitlength > 1){
                localStorage.StaDeleteArray = '1';
                $.ajax({
                    type : "POST",
                    url  : "CompSettingConEventDeleteMultiple",
                    data : {
                        'tUrlID'   : aNewUrlIdDelete,
                        'tAddress' : aNewAddrDelete
                    },
                    success: function (aReturn){
                        aReturn = aReturn.trim();
                        var aReturn = $.parseJSON(aReturn);
                        if(aReturn['nStaEvent'] == '1'){
                            $('#odvModalDeleteMutirecord').modal('hide');
                            $('#odvModalDeleteMutirecord #ospConfirmDelete').text($('#ohdConfirmIDDeleteMutirecord').val());
                            $('#ohdConfirmIDDeleteMutirecordUrlId').val('');
                            $('#ohdConfirmIDDeleteMutirecordAddress').val('');
                            localStorage.removeItem('LocalItemDataAds');
                            $('.modal-backdrop').remove();
                                setTimeout(function() {
                                JSvCompSetConnectionList(1)
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
            }
        }else{
            JCNxShowMsgSessionExpired();
        }
    }


    //Functionality : เปลี่ยนหน้า pagenation
    //Parameters : -
    //Creator : 19/10/2018 Witsarut(Bell)
    //Return : View
    //Return Type : View
    function JSvCompSettingConClickPage(ptPage){
        alert('JSvCompSettingConClickPage');
    }

    //Functionality: Function Chack And Show Button Delete All
    //Parameters: LocalStorage Data
    //Creator: 19/10/2019 witsarut (Bell)
    //Return: - 
    //Return Type: -
    function JSxCompSettingConShowButtonChoose(){
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
    //Creator: 19/10/2019 witsarut (Bell)
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
    //Creator: 19/10/2019 witsarut (Bell)
    //Return: -
    //Return Type: -
    function JSxCompSettingConPaseCodeDelInModal(){
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == '') {} else {

            var tUrlid = '';
            var tUrlAddr = '';

            for ($i = 0; $i < aArrayConvert[0].length; $i++) {
                tUrlid += aArrayConvert[0][$i].nUrlid;
                tUrlid += ' , ';
                tUrlAddr += aArrayConvert[0][$i].tUrlAddr;
                tUrlAddr += ' , ';
            }
            $('#odvModalDeleteMutirecord #ospConfirmDelete').text($('#oetTextComfirmDeleteMulti').val());
            $('#ohdConfirmIDDeleteMutirecordUrlId').val(tUrlid);
            $('#ohdConfirmIDDeleteMutirecordAddress').val(tUrlAddr);
        }
    }



    //Select Type 9,10,11
    $('#ocmUrlConnecttype').selectpicker();

</script>