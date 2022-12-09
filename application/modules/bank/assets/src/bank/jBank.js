var nStaBnkBrowseType = $('#oetBnkStaBrowse').val();
var tCallBnkBackOption = $('#oetBnkCallBackOption').val();
$('document').ready(function() {
    localStorage.removeItem('LocalItemData');
    JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
    JSxBNKNavDefult();

    if (nStaBnkBrowseType != 1) {
        JSvCallPageBankList();
    } else {
        JSvCallPageBankAdd();
    }

});

function JSxBNKNavDefult() {
    if (nStaBnkBrowseType != 1 || nStaBnkBrowseType == undefined) {
        $('.xCNBnkVBrowse').hide();
        $('.xCNBnkVMaster').show();
        $('#oliBnkEdit').hide();
        $('#oliBnkAdd').hide();
        $('#odvBtnAddEdit').hide();
        $('.obtChoose').hide();
        $('#odvBtnBnkInfo').show();
        $('#odvBtnCmpEditInfo').hide();
        $('#odvBtnAgnInfo').show();
        
       
        
    } else {
        $('#odvModalBody .xCNBnkVMaster').hide();
        $('#odvModalBody .xCNBnkVBrowse').show();
        $('#odvModalBody #odvBnkMainMenu').removeClass('main-menu');
        $('#odvModalBody #oliBnkNavBrowse').css('padding', '2px');
        $('#odvModalBody #odvBnkBtnGroup').css('padding', '0');
        $('#odvModalBody .xCNBnkBrowseLine').css('padding', '0px 0px');
        $('#odvModalBody .xCNBnkBrowseLine').css('border-bottom', '1px solid #e3e3e3');
    }
}

//function : Function Show Event Error
//Parameters : Error Ajax Function 
//Creator : 02/07/2018 wasin
//Return : Modal Status Error
//Return Type : view
function JCNxResponseError(jqXHR, textStatus, errorThrown) {
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

// //Functionality : (event) Add/Edit Bank
// //Parameters : form
// //Creator : 02/07/2018 Krit(Copter)
// //Return : Status Add
// //Return Type : n
function JSnAddEditBank(ptRoute) {   
    // alert(ptRoute);
    $('#ofmAddBank').validate({
        rules: {
            oetBnkCode: "required",
            oetBnkName: "required"
        },
        messages: {
            oetBnkCode: $('#oetBnkCode').attr('data-validate-required'),
            oetBnkName: $('#oetBnkName').attr('data-validate-required')
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
        unhighlight: function (element, errorClass, validClass) {
            $( element ).closest('.form-group').addClass( "has-success" ).removeClass( "has-error" );
        },
        submitHandler: function(form) {
            // $('#ofmAddBank').serialize(),
            $.ajax({
                type: "POST",
                url: ptRoute,
                data: $('#ofmAddBank').serialize(),
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    // alert(tResult);
                    console.log(tResult);
                    // $('#oetBnkName').val(tResult);
                    if (nStaBnkBrowseType != 1) {
                        var aReturn = JSON.parse(tResult);
                        // $('#oetBnkName').val(aReturn);
                        if (aReturn['nStaEvent'] == 1) {
                            if (aReturn['nStaCallBack'] == '1' || aReturn['nStaCallBack'] == null) {
                                JSvCallEditBnk(aReturn['tCodeReturn'])
                            } else if (aReturn['nStaCallBack'] == '2') {
                                JSvCallPageBankAddBank();
                            } else if (aReturn['nStaCallBack'] == '3') {
                                
                                JSvCallPageBankList(); 
                                
                            }
                        } else {
                            alert(aReturn['tStaMessg']);
                            // JSvCallPageBankList();
                        }
                    } else {
                        JCNxBrowseData(tCallBnkBackOption);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        },
    });
}

//Functionality : Add Data Agency Add/Edit  
//Parameters : from ofmAddBank
//Creator : 10/06/2019 saharat(Golf)
//Return : View
//Return Type : View
function JSnBankAddEdit(ptRoute) {
    // var nAgnStaApv = $('#ocmAgnStaApv').val();
    // var nStaActive = $('#ocmAgnStaActive').val();
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
        $('#ofmAddBank').validate().destroy();
        $.validator.addMethod('dublicateCode', function(value, element) {
            if (ptRoute == "bankEventUpdate") {
                if ($("#oetBnkCode").val() == 1) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }, '');
        $('#ofmAddBank').validate({
            rules: {
                oetAgnCode: {
                    "required": {
                        depends: function(oElement) {
                            if (ptRoute == "bankEventUpdate") {
                                // if ($('#ocbAgencyAutoGenCode').is(':checked')) {
                                //     return false;
                                // } else {
                                //     return true;
                                // }
                            } else {
                                return true;
                            }
                        }
                    },
                    "dublicateCode": {}
                },
                oetBnkCode: { "required": {} },
                oetBnkName: { "required": {} },
                // oetAgnEmail: { "required": {} },
                // opwAgnPwd: { "required": {} },
            },
            messages: {
                oetBnkCode: {
                    "required": $('#oetBnkCode').attr('data-validate-required'),
                    "dublicateCode": $('#oetBnkCode').attr('data-validate-dublicateCode')
                },
                oetBnkName: {
                    // "required"     : "กรุณากรอก ชื่อตัวแทนขาย!"
                    "required": $('#oetBnkName').attr('data-validate-required'),
                    "dublicateCode": $('#oetBnkName').attr('data-validate-dublicateCode')
                },
                // oetAgnEmail: {
                //     // "required"     : "กรุณากรอก อีเมล์!"
                //     "required": $('#oetAgnEmail').attr('data-validate-required'),
                //     "dublicateCode": $('#oetAgnEmail').attr('data-validate-dublicateCode')
                // },
                // opwAgnPwd: {
                //     // "required"     : "กรุณากรอก รหัสผ่าน!"
                //     "required": $('#opwAgnPwd').attr('data-validate-required'),
                //     "dublicateCode": $('#opwAgnPwd').attr('data-validate-dublicateCode')
                // },
            },
            errorElement: "em",
            errorPlacement: function(error, element) {
                error.addClass("help-block");
                if (element.prop("type") === "checkbox") {
                    error.appendTo(element.parent("label"));
                } else {
                    var tCheck = $(element.closest('.form-group')).find('.help-block').length;
                    if (tCheck == 0) {
                        error.appendTo(element.closest('.form-group')).trigger('change');
                    }
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-error").removeClass("has-success");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').addClass("has-success").removeClass("has-error");
            },
            submitHandler: function(form) {
                $.ajax({
                    type: "POST",
                    url: ptRoute,
                    data: $('#ofmAddBank').serialize(),
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        if (nStaBnkBrowseType != 1) {
                            var aReturn = JSON.parse(tResult);
                            if (aReturn['nStaEvent'] == 1) {
                                if (aReturn['nStaCallBack'] == '1' || aReturn['nStaCallBack'] == null) {
                                    JSvCallEditBnk(aReturn['tCodeReturn'])
                                } else if (aReturn['nStaCallBack'] == '2') {
                                    JSvCallPageBankAddBank();
                                } else if (aReturn['nStaCallBack'] == '3') {
                                    JSvCallPageBankList();
                                }
                            } else {
                                alert(aReturn['tStaMessg']);
                            }
                        } else {
                            JCNxBrowseData(tCallCpnBackOption);
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
////////////////////////////////////////////

function JSvCallPageBankAdd() {
  
    $.ajax({
        type: "GET",
        url: "bankAddData",
        data: {},
        cache: false,
        timeout: 5000,
        success: function(tResult) {
            if (nStaBnkBrowseType == 1) {

                $('#odvModalBodyBrowse').html(tResult);
                $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');

                // $('.xCNBnkVMaster').hide();
                // // $('.xCNBTNPrimeryPlus').hide();
                // $('.xCNBnkVBrowse').show();
                // $('#oliBnkEdit').hide();
                // $('#oliBnkAdd').show();
            } else {
                $('.xCNBnkVBrowse').hide();
                $('.xCNBnkVMaster').show();
                $('#oliBnkTitleEdit').hide();
                $('.xCNBTNPrimeryPlus').show();
                $('#oliBnkTitleAdd').show();
                $('#odvBtnBnkInfo').hide();
                $('#odvBtnAddEdit').show();
                
            }
            $('#obtBarSubmitBnk').show();

            $('#odvContentPageBank').html(tResult);
        },
        error: function(data) {
            console.log(data);
        }
    });
}


function JSvCallPageBankAddBank() {
    
    // alert('test');
    $.ajax({
        type: "GET",
        url: "bankAddData",
        data: {},
        cache: false,
        timeout: 5000,
        success: function(tResult) {
            if (nStaBnkBrowseType == 1) {
                $('.xCNCpnVMaster').hide();
                $('.xCNCpnVBrowse').show();
                // alert('1');
            } else {

                $('.xCNCpnVBrowse').hide();
                $('.xCNCpnVMaster').show();
                $('#odvBtnAddEdit').show();
                $('#odvBtnCmpEditInfo').show();
                $('#odvBtnAgnInfo').hide();
                $('#oliBnkEdit').hide();
                $('#oliBnkAdd').show();
            }
         
            $('#odvContentPageBank').html(tResult);
        },
        error: function(data) {
            console.log(data);
        }
    });
}


function JSvCallPageBankAddBankbackup() {
    
    // alert('test');
    $.ajax({
        type: "GET",
        url: "bankAddData",
        data: {},
        cache: false,
        timeout: 5000,
        success: function(tResult) {
            if (nStaBnkBrowseType == 1) {
                $('.xCNCpnVMaster').hide();
                $('.xCNCpnVBrowse').show();
                // alert('1');
            } else {
                $('.xCNCpnVBrowse').hide();
                $('.xCNCpnVMaster').show();
                $('#odvBtnAddEdit').show();
                $('#odvBtnCmpEditInfo').show();
                $('#odvBtnAgnInfo').hide();
                $('#oliBnkEdit').hide();
                $('#oliBnkAdd').show();
            }
         
            $('#odvContentPageBank').html(tResult);
        },
        error: function(data) {
            console.log(data);
        }
    });
}














// //Functionality : Call Bank Page Edit  
// //Parameters : -
// //Creator : 02/07/2018 krit
// //Return : View
// //Return Type : View
function JSvCallPageBankEdit(ptBnkCode){
    
    JCNxOpenLoading();
    JStCMMGetPanalLangSystemHTML('JSvCallPageBankEdit', ptBnkCode);

    $.ajax({
        type: "POST",
        url: "bankPageEdit",
        data: { tBnkCode: ptBnkCode },
        cache: false,
        timeout: 0,
        success: function(tResult) {
            if (tResult != '') {
                $('#oliBnkTitleAdd').hide();
                $('#oliBnkTitleEdit').show();
                $('#odvBtnBnkInfo').hide();
                $('#odvBtnAddEdit').show();
                $('#odvContentPageBank').html(tResult);
                $('#oetBnkCode').addClass('xCNDisable');
                $('.xCNDisable').attr('readonly', true);
            }

            //Control Event Button
            if ($('#ohdBnkAutStaEdit').val() == 0) {
                $('#obtBarSubmitBnk').hide();
                $('.xCNUplodeImage').hide();
                $('.xCNIconBrowse').hide();
                $("select").prop('disabled', true);
                $('input').attr('disabled', true);
            }else{
                $('#obtBarSubmitBnk').show();
                $('.xCNUplodeImage').show();
                $('.xCNIconBrowse').show();
                $("select").prop('disabled', false);
                $('input').attr('disabled', false);
            }
            //Control Event Button

            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}


function JSvCallPageBankList() {

    localStorage.tStaPageNow = 'JSvCallPageBankList';

    $.ajax({
        type: "GET",
        url: "banklist",
        data: {},
        cache: false,
        timeout: 5000,
        success: function(tResult) {

            $('#odvContentPageBank').html(tResult);
            $('.xCNBTNPrimeryPlus').show();
            $('#oliBnkTitleEdit').hide();
            $('#odvBtnAddEdit').hide();
            JSxBNKNavDefult();

            JSvCallPageBankDataTable(); //แสดงข้อมูลใน List
        },
        error: function(data) {
            console.log(data);
        }
    });

}

function JSvCallPageBankDataTable(pnPage) {
// alert('test');
    var tSearchAll = $('#oetSearchAll').val();
    var nPageCurrent = pnPage;
    if (nPageCurrent == undefined || nPageCurrent == '') {
        nPageCurrent = '1';
    }

    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "bankdatatable2",
        data: {            
                tSearchAll: tSearchAll,
                nPageCurrent: nPageCurrent
            },
        cache: false,
        timeout: 5000,
        success: function(tResult) {
            
            $('#odvContentBankDatatable').html(tResult);

            JSxBNKNavDefult();
            JCNxLayoutControll();
            JStCMMGetPanalLangHTML('TFNMBank_L'); //โหลดภาษาใหม่
            JCNxCloseLoading();

        },
        error: function(data) {
            console.log(data);
        }
    });

}


// //Functionality : (event) Delete
// //Parameters : tIDCode รหัส Bank
// //Creator : 03/07/2018 Krit(Copter)
// //Return : 
//Return Type : Status Number
function JSnBankDel(pnPage,ptName,tIDCode) {
    var aData = $('#ohdConfirmIDDelete').val();
    var aTexts = aData.substring(0, aData.length - 2);
    var aDataSplit = aTexts.split(" , ");
    var aDataSplitlength = aDataSplit.length;
    var aNewIdDelete = [];

    if (aDataSplitlength == '1') {

        $('#odvModalDelBank').modal('show');
        $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + tIDCode + ' ( ' + ptName + ' ) ');
        $('#osmConfirm').on('click', function(evt) {

            if (localStorage.StaDeleteArray != '1') {

                $.ajax({
                    type: "POST",
                    url: "bankEventDelete",
                    data: { 'tIDCode': tIDCode},
                    cache: false,
                    success: function(tResult) {
                        tResult = tResult.trim();
                        var tData = $.parseJSON(tResult);

                        $('#odvModalDelBank').modal('hide');
                        $('#ospConfirmDelete').text($('#oetTextComfirmDeleteSingle').val());
                        $('#ohdConfirmIDDelete').val('');
                        localStorage.removeItem('LocalItemData');
                        $('.modal-backdrop').remove();
                        JSvCallPageBankDataTable(pnPage);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }


        });
    }
}

///function : Function Clear Defult Button Bank
//Parameters : -
//Creator : 11/01/2019 Jame
//Return : -
//Return Type : -
function JSxBNKBtnNavDefult() {
    $('#oliBnkTitleAdd').hide();
    $('#oliBnkTitleEdit').hide();
    $('#odvBtnAddEdit').hide();
    $('.obtChoose').hide();
    $('#odvBtnBnkInfo').show();
}

// //Functionality : (event) Delete All
// //Parameters :
// //Creator : 15/05/2018 wasin
// //Return : 
// //Return Type :
function JSnBankDelChoose1(pnPage) {
       
    JCNxOpenLoading();

    var tNamepage = '';
    var aDataIdBranch = '';
    var nStaBrowse = '';
    var tStaInto = '';
    var aData = $('#ohdConfirmIDDelete').val();
    //console.log('DATA : ' + aData);

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
            url: "bankEventDelete",
            data: { 'tIDCode': aNewIdDelete },
            success: function(tResult) {
                
                JSxBNKBtnNavDefult();
                setTimeout(function() {
                    $('#odvModalDelBank').modal('hide');
                    JSvCallPageBankDataTable(pnPage);
                    $('#ospConfirmDelete').text($('#oetTextComfirmDeleteSingle').val());
                    $('#ohdConfirmIDDelete').val('');
                    localStorage.removeItem('LocalItemData');
                    $('.obtChoose').hide();
                    $('.modal-backdrop').remove();
                }, 1000);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });


    } else {
        localStorage.StaDeleteArray = '0';

        return false;
    }
}


// //Functionality : (event) Delete All
// //Parameters :
// //Creator : 04/07/2018 Krit
// //Return : 
// //Return Type :
function JSnBankDelChoose(pnPage) {
    JCNxOpenLoading();

    var tNamepage = '';
    var aDataIdBranch = '';
    var nStaBrowse = '';
    var tStaInto = '';
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
            url: "bankEventDelete",
            data: { 'tIDCode': aNewIdDelete  },
            success: function(tResult) {
                tResult = tResult.trim();
                var aReturn = $.parseJSON(tResult);
                $('#odvModalDelBank').modal('hide');
                $('#ospConfirmDelete').text($('#oetTextComfirmDeleteSingle').val());
                $('#ohdConfirmIDDelete').val('');
                localStorage.removeItem('LocalItemData');
                $('.obtChoose').hide();
                $('.modal-backdrop').remove();
                //เช็คแถวข้อมูล ว่า <= 10 ไหมถ้าน้อยกว่า 10 ให้ กลับไปหน้า ก่อนหน้า
                setTimeout(function() {
                    if (aReturn["nNumRow"] != 0) {
                        if (aReturn["nNumRow"] > 10) {
                            nNumPage = Math.ceil(aReturn["nNumRow"] / 10);
                            if (pnPage <= nNumPage) {
                                JSvCallPageBankDataTable(pnPage);
                            } else {
                                JSvCallPageBankDataTable(nNumPage);
                            }
                        } else {
                            JSvCallPageBankDataTable(1);
                        }
                    } else {
                        JSvCallPageBankDataTable(1);
                    }
                }, 500);
                JCNxCloseLoading();
                JSxBNKNavDefult();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });


    } else {
        localStorage.StaDeleteArray = '0';

        return false;
    }

}


// Functionality: Event Single Delete Shop Single
// Parameters: Event Icon Delete
// Creator: 27/02/2019 wasin(Yoshi)
// Return: object Status Delete
// ReturnType: object
function JSoBnkDeleteMultiple() {
    var nStaSession = JCNxFuncChkSessionExpired();
    
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
      var aDataDelMultiple = $(
        "#odvModalDeleteBnkMultiple #ohdConfirmIDDelMultiple"
      ).val();
      var aTextsDelMultiple = aDataDelMultiple.substring(
        0,
        aDataDelMultiple.length - 2
      );
      var aDataSplit = aTextsDelMultiple.split(" , ");
      var nDataSplitlength = aDataSplit.length;
      var aNewIdDelete = [];
      for ($i = 0; $i < nDataSplitlength; $i++) {
        aNewIdDelete.push(aDataSplit[$i]);
      }
      if (nDataSplitlength > 1) {
        JCNxOpenLoading();
        localStorage.StaDeleteArray = "1";
        $.ajax({
          type: "POST",
          url: "bankEventDelete",
          data: { tIDCode: aNewIdDelete },
          async: false,
          cache: false,
          timeout: 0,
          success: function(tResult) {
            var aReturnData = JSON.parse(tResult);
            if (aReturnData["nStaEvent"] == 1) {
              setTimeout(function() {
                $("#odvModalDeleteBnkMultiple").modal("hide");
                $(
                  "#odvModalDeleteBnkMultiple #ospTextConfirmDelMultiple"
                ).empty();
                $("#odvModalDeleteBnkMultiple #ohdConfirmIDDelMultiple").val("");
                localStorage.removeItem("LocalItemData");
                JSvCallPageShopList();
                $(".modal-backdrop").remove();
              });
            } else {
              $("#odvModalDeleteBnkMultiple").modal("hide");
              $("#odvModalDeleteBnkMultiple #ospTextConfirmDelMultiple").empty();
              $("#odvModalDeleteBnkMultiple #ohdConfirmIDDelMultiple").val("");
              $(".modal-backdrop").remove();
              setTimeout(function() {
                JCNxCloseLoading();
                FSvCMNSetMsgErrorDialog(aReturnData["tStaMessg"]);
              }, 500);
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
          }
        });
      }
    } else {
      JCNxShowMsgSessionExpired();
    }
  }

//Functionality : เปลี่ยนหน้า pagenation
//Parameters : -
//Creator : 02/07/2018 Krit(Copter)
//Return : View
//Return Type : View
function JSvBNKClickPage(ptPage) {
    var nPageCurrent = '';
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld = $('.xWPageBank .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld = $('.xWPageBank .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JSvCallPageBankDataTable(nPageCurrent);
}



//Functionality: Function Chack And Show Button Delete All
//Parameters: LocalStorage Data
//Creator: 15/05/2018
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
//Creator: 15/05/2018 wasin
//Return: -
//Return Type: -
function JSxPaseCodeDelInModal() {

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
//Parameters: Event Select List Branch
//Creator: 06/06/2018 Krit
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

//Functionality: Search Bank List
//Parameters: tSearchAll = ข้อความที่ใช้ค้นหา , nPageCurrent = 1
//Creator: 11/01/2019 Jame
//Return: View
//Return Type: View
function JSvSearchAllBank() {
    var tSearchAll = $('#oetSearchAll').val();
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "bankdatatable2",
        data: {
            tSearchAll: tSearchAll,
            nPageCurrent: 1
        },
        cache: false,
        Timeout: 0,
        success: function(tResult) {
            if (tResult != "") {
                $('#odvContentBankDatatable').html(tResult);
            }
            JSxBNKBtnNavDefult();
            JCNxLayoutControll();
            JStCMMGetPanalLangHTML('TFNMBank_L'); //โหลดภาษาใหม่
            JCNxCloseLoading();
        },
        error: function(data) {
            console.log(data);
        }
    });
}


//Functionality : เปลี่ยนหน้า pagenation
//Parameters : -
//Creator : 18/06/2019 saharat(Golf)
//Return : View
//Return Type : View
function JSvCPNClickPage(ptPage) {
    var nPageCurrent = '';
    switch (ptPage) {
        case 'next': //กดปุ่ม Next
            $('.xWBtnNext').addClass('disabled');
            nPageOld = $('.xWCDCPaging .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew
            break;
        case 'previous': //กดปุ่ม Previous
            nPageOld = $('.xWCDCPaging .active').text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew
            break;
        default:
            nPageCurrent = ptPage
    }
    JSvCallPageBankDataTable(nPageCurrent);
}




// //Functionality : (event) Delete
// //Parameters : tBnkCode รหัส Bank
// //Creator :  29/1/2020 nonapwich
// //Return : 
//Return Type : Status Number
function JSnBankdelete(pnPage,ptName,tIDCode) {
    var aData = $('#ohdConfirmIDDelete').val();
    var aTexts = aData.substring(0, aData.length - 2);
    var aDataSplit = aTexts.split(" , ");
    var aDataSplitlength = aDataSplit.length;
    var aNewIdDelete = [];

    if (aDataSplitlength == '1') {
        $('#odvModalDelBank').modal('show');
        $('#ospConfirmDelete').html($('#oetTextComfirmDeleteSingle').val() + tIDCode + ' ( ' + ptName + ' ) ');
        $('#osmConfirm').on('click', function(evt) {

            if (localStorage.StaDeleteArray != '1') {
                JCNxOpenLoading();
                $.ajax({
                    type: "POST",
                    url: "bankdelevent",
                    data: { 'tIDCode': tIDCode},
                    cache: false,
                    success: function(tResult) {
                        tResult = tResult.trim();
                        var tData = $.parseJSON(tResult);
                        $('#odvModalDelBank').modal('hide');
                        $('#ospConfirmDelete').text($('#oetTextComfirmDeleteSingle').val());
                        $('#ohdConfirmIDDelete').val('');
                        localStorage.removeItem('LocalItemData');
                        $('.modal-backdrop').remove();
                        JSvCallPageBankDataTable(pnPage);
                        JCNxCloseLoading();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            }
        });
    }
}

function JSvCallEditBnk(ptBnkCode){

    try {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof(nStaSession) !== 'undefined' && nStaSession == 1) {
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "bankedit",
                data: { tBnkCode: ptBnkCode },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                // console.log (tResult);
                    if (tResult != "") {
                   
                        $('#oliBnkEdit').show();
                        $('#oliBnkAdd').hide();
                        $('#odvContentPageBank').html(tResult);
                        $('#oetVatCode').addClass('xCNDisable');
                        $('.xCNiConGen').attr('readonly', true);
                        $('#oetVatCode').attr('readonly', true);
                        $('#odvBtnAgnInfo').hide();
                        $('#odvBtnCmpEditInfo').show();
                        
                        $('.xWVatSave').hide();
                        $('.xWVatCancel').hide();
                    }
                    JCNxLayoutControll();
                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            JCNxShowMsgSessionExpired();
        }
    } catch (err) {
        console.log('JSvCallPageVatrateEdit Error: ', err);
    }

}


//Functionality : Call Credit Page Edit  
//Parameters : -
//Creator : 02/07/2018 krit
//Return : View
//Return Type : View
function JSvCallPageRateAdd() {

    $.ajax({
        type: "GET",
        url: "bankAddData",
        data: {},
        cache: false,
        timeout: 5000,
        success: function(tResult) {
            if (nStaRteBrowseType == 1) {
                $('#odvModalBodyBrowse').html(tResult);
                $('#odvModalBodyBrowse .panel-body').css('padding-top', '0');
            } else {
                $('.xCNCpnVBrowse').hide();
                $('.xCNCpnVMaster').show();
                $('#odvBtnAddEdit').show();
                $('#odvBtnCmpEditInfo').show();
                $('#odvBtnAgnInfo').hide();
                $('#oliBnkEdit').hide();
                $('#oliBnkAdd').show();
            }
            // $('#obtBarSubmitRte').show();

            $('#odvContentPageBank').html(tResult);
        },
        error: function(data) {
            console.log(data);
        }
    });
}
