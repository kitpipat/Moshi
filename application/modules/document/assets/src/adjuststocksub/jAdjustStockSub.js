var nStaAdjStkSubBrowseType = $("#oetAdjStkSubStaBrowse").val();
var tCallAdjStkSubBackOption = $("#oetAdjStkSubCallBackOption").val();

$("document").ready(function () {
  localStorage.removeItem("LocalItemData");
  JSxCheckPinMenuClose(); /*Check เปิดปิด Menu ตาม Pin*/
  JSxAdjStkSubNavDefult();

  if (nStaAdjStkSubBrowseType != 1) {
    JSvCallPageAdjStkSubList();
  } else {
    JSvCallPageAdjStkSubAdd();
  }
});

//Functionality: Del Pdt In Row Html And Del in DB
//Parameters: Event Proporty
//Creator: 04/04/2019 Krit(Copter)
//Return:  Call function Delete
function JSnRemoveDTRow(ele) {
  var nStaSession = JCNxFuncChkSessionExpired();
  if (typeof nStaSession !== "undefined" && nStaSession == 1) {
    var tVal = $(ele)
      .parent()
      .parent()
      .parent()
      .attr("data-pdtcode");
    var tSeqno = $(ele)
      .parent()
      .parent()
      .parent()
      .attr("data-seqno");
    $(ele)
      .parent()
      .parent()
      .parent()
      .remove();

    JSnAdjStkSubRemoveDTTemp(tSeqno, tVal);
  } else {
    JCNxShowMsgSessionExpired();
  }
}

function JSxAdjStkSubNavDefult() {
    if (nStaAdjStkSubBrowseType != 1 || nStaAdjStkSubBrowseType == undefined) {
        $(".xCNAdjStkSubVBrowse").hide();
        $(".xCNAdjStkSubVMaster").show();
        $("#oliAdjStkSubTitleAdd").hide();
        $("#oliAdjStkSubTitleEdit").hide();
        $("#odvBtnAddEdit").hide();
        $(".obtChoose").hide();
        $("#odvBtnAdjStkSubInfo").show();
    } else {
        $("#odvModalBody .xCNAdjStkSubVMaster").hide();
        $("#odvModalBody .xCNAdjStkSubVBrowse").show();
        $("#odvModalBody #odvAdjStkSubMainMenu").removeClass("main-menu");
        $("#odvModalBody #oliAdjStkSubNavBrowse").css("padding", "2px");
        $("#odvModalBody #odvAdjStkSubBtnGroup").css("padding", "0");
        $("#odvModalBody .xCNAdjStkSubBrowseLine").css("padding", "0px 0px");
        $("#odvModalBody .xCNAdjStkSubBrowseLine").css("border-bottom", "1px solid #e3e3e3");
    }
}

//function : Function Show Event Error
//Parameters : Error Ajax Function
//Creator : 04/07/2018 Krit
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
      tMsgError += tHtmlError.find("p:nth-child(2)").text();
      break;
    case 500:
      tMsgError += tHtmlError.find("p:nth-child(3)").text();
      break;

    default:
      tMsgError += "something had error. please contact admin";
      break;
  }
  $("body").append(tModal);
  $("#modal-customs").attr(
    "style",
    "width: 450px; margin: 1.75rem auto;top:20%;"
  );
  $("#myModal").modal({ show: true });
  $("#odvModalBody").html(tMsgError);
}

/*
function : Function Browse Pdt
Parameters : Error Ajax Function 
Creator : 22/05/2019 Piya(Tiger)
Return : Modal Status Error
Return Type : view
*/
function JCNvAdjStkSubBrowsePdt() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        aMulti = [];
        $.ajax({
            type: "POST",
            url: "BrowseDataPDT",
            data: {
                Qualitysearch: [
                    /* "NAMEPDT",
                    "CODEPDT",
                    "SUP",
                    "PurchasingManager",
                    "NAMEPDT",
                    "CODEPDT",
                    "BARCODE",
                    'LOC',
                    "FromToBCH",
                    "Merchant",
                    "FromToSHP",
                    "FromToPGP",
                    "FromToPTY",
                    "PDTLOGSEQ"*/
                ],
                PriceType       : ["Cost", "tCN_Cost", "Company", "1"],
                SelectTier      : ["Barcode"],
                ShowCountRecord : 10,
                NextFunc        : "FSvPDTAddPdtIntoTableDT",
                ReturnType      : "M",
                SPL             : ["", ""],
                BCH             : [$("#oetBchCode").val(), $("#oetBchCode").val()],
                SHP             : [$("#oetShpCodeStart").val(), $("#oetShpCodeStart").val()]
            },
            cache: false,
            timeout: 5000,
            success: function (tResult) {
                // $(".modal.fade:not(#odvAdjStkSubBrowseShipAdd, #odvModalDOCPDT, #odvModalWanning)").remove();
                $("#odvModalDOCPDT").modal({backdrop: "static", keyboard: false});
                $("#odvModalDOCPDT").modal({show: true});

                //remove localstorage
                localStorage.removeItem("LocalItemDataPDT");
                $("#odvModalsectionBodyPDT").html(tResult);
            },
            error: function (data) {
                console.log(data);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function : เพิ่มสินค้าจาก ลง Table ฝั่ง Client
// Create : 2018-08-28 Krit(Copter)
function FSvPDTAddPdtIntoTableDT(pjPdtData) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {

        JCNxOpenLoading();
        var ptXthDocNoSend = "";
        if ($("#ohdAdjStkSubRoute").val() == "AdjStkSubEventEdit") {
            ptXthDocNoSend = $("#oetAdjStkSubAjhDocNo").val();
        }

        $.ajax({
            type: "POST",
            url: "adjStkSubAddPdtIntoTableDT",
            data: {

                ptAjhDocNo: ptXthDocNoSend,
                pjPdtData: pjPdtData,
                pnAdjStkSubOptionAddPdt: '2' // เพิ่มแถวใหม่ // $("#ocmAdjStkSubOptionAddPdt").val()
            },
            cache: false,
            timeout: 5000,
            success: function (tResult) {
                console.log(tResult);
                JSvAdjStkSubLoadPdtDataTableHtml();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

// Function : Search Pdt
function JSvAdjStkSubDOCSearchPdtHTML() {
    JSvAdjStkSubLoadPdtDataTableHtml();
    /*var value = $("#oetAdjStkSubSearchPdtHTML").val().toLowerCase();
    $("#otbDOCPdtTable tbody tr ").filter(function () {
        var tText = $(this).toggle(
            $(this).text().toLowerCase().indexOf(value) > -1
        );
    });*/
}

// function JSxAdjStkSubAftSelectShipAddress(poJsonData) {
//   tBchCode = $("#oetBchCode").val();
//   //ถ้าไม่มีการเลือก มาจะส่ง NULL
//   if (poJsonData != "NULL") {
//     aData = JSON.parse(poJsonData);
//     tAddBch = aData[0];
//     tAddSeqNo = aData[1];
//   } else {
//     tAddBch = 0;
//     tAddSeqNo = 0;
//   }

//   JSvAdjStkSubGetShipAddData(tBchCode, tAddSeqNo);
// }

//Get ข้อมูล Address มาใส่ modal แบบ Array
/*function JSvAdjStkSubGetShipAddData(pTAddressInfor) {
  if (pTAddressInfor !== "NULL") {
    var aData = JSON.parse(pTAddressInfor);
    $("#ospShipAddAddV1No").text(aData[1]);
    $("#ospShipAddV1Soi").text(aData[2]);
    $("#ospShipAddV1Village").text(aData[3]);
    $("#ospShipAddV1Road").text(aData[4]);
    $("#ospShipAddV1SubDist").text(aData[5]);
    $("#ospShipAddV1DstCode").text(aData[6]);
    $("#ospShipAddV1PvnCode").text(aData[7]);
    $("#ospShipAddV1PostCode").text(aData[8]);
    $("#ospShipAddV2Desc1").text(aData[9]);
    $("#ospShipAddV2Desc2").text(aData[10]);
  } else {
    $("#ospShipAddAddV1No").text("-");
    $("#ospShipAddV1Soi").text("-");
    $("#ospShipAddV1Village").text("-");
    $("#ospShipAddV1Road").text("-");
    $("#ospShipAddV1SubDist").text("-");
    $("#ospShipAddV1DstCode").text("-");
    $("#ospShipAddV1PvnCode").text("-");
    $("#ospShipAddV1PostCode").text("-");
    $("#ospShipAddV2Desc1").text("-");
    $("#ospShipAddV2Desc2").text("-");
  }

  // $.ajax({
  //   type: "POST",
  //   url: "AdjStkSubGetAddress",
  //   data: {
  //     tBchCode: tBchCode,
  //     tXthShipAdd: tXthShipAdd
  //   },
  //   cache: false,
  //   Timeout: 0,
  //   success: function (tResult) {
  //     aData = JSON.parse(tResult);

  //     if (aData != 0) {
  //       $("#ospShipAddAddV1No").text(aData[0]["FTAddV1No"]);
  //       $("#ospShipAddV1Soi").text(aData[0]["FTAddV1Soi"]);
  //       $("#ospShipAddV1Village").text(aData[0]["FTAddV1Village"]);
  //       $("#ospShipAddV1Road").text(aData[0]["FTAddV1Road"]);
  //       $("#ospShipAddV1SubDist").text(aData[0]["FTSudName"]);
  //       $("#ospShipAddV1DstCode").text(aData[0]["FTDstName"]);
  //       $("#ospShipAddV1PvnCode").text(aData[0]["FTPvnName"]);
  //       $("#ospShipAddV1PostCode").text(aData[0]["FTAddV1PostCode"]);
  //       $("#ospShipAddV2Desc1").text(aData[0]["FTAddV2Desc1"]);
  //       $("#ospShipAddV2Desc2").text(aData[0]["FTAddV2Desc2"]);
  //     } else {
  //       $("#ospShipAddAddV1No").text("-");
  //       $("#ospShipAddV1Soi").text("-");
  //       $("#ospShipAddV1Village").text("-");
  //       $("#ospShipAddV1Road").text("-");
  //       $("#ospShipAddV1SubDist").text("-");
  //       $("#ospShipAddV1DstCode").text("-");
  //       $("#ospShipAddV1PvnCode").text("-");
  //       $("#ospShipAddV1PostCode").text("-");
  //       $("#ospShipAddV2Desc1").text("-");
  //       $("#ospShipAddV2Desc2").text("-");
  //     }

  //     //เอาค่าจาก input หลัก มาใส่ input ใน modal
  //     $("#ohdShipAddSeqNo").val(tXthShipAdd);
  //     $(".modal.fade:not(#odvAdjStkSubBrowseShipAdd)").remove();
  //     //Show
  //     $("#odvAdjStkSubBrowseShipAdd").modal("show");
  //   },
  //   error: function (jqXHR, textStatus, errorThrown) { }
  // });



}*/

// function JSnAdjStkSubApprove(pbIsConfirm){

//     tXthDocNo = $('#oetAdjStkSubAjhDocNo').val();

//     if(pbIsConfirm){

//         $.ajax({
//             type: "POST",
//             url: "AdjStkSubApprove",
//             data: {
//                 tXthDocNo : tXthDocNo
//             },
//             cache: false,
//             timeout: 5000,
//             success: function(tResult){

//                 $("#odvAdjStkSubPopupApv").modal('hide');

//                 aResult = $.parseJSON(tResult);
//                 if(aResult.nSta == 1){
//                     JSvCallPageAdjStkSubEdit(tXthDocNo)
//                 }else{
//                     JCNxCloseLoading();
//                     tMsgBody = aResult.tMsg
//                     FSvCMNSetMsgWarningDialog(tMsgBody);
//                 }

//             },
//             error: function(jqXHR, textStatus, errorThrown) {
//                 JCNxResponseError(jqXHR, textStatus, errorThrown);
//             }
//         });
//     }else{
//         $("#odvAdjStkSubPopupApv").modal('show');
//     }
// }

/**
 * Functionality : Action for approve
 * Parameters : pbIsConfirm
 * Creator : 11/04/2019 Krit(Copter)
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSnAdjStkSubApprove(pbIsConfirm) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        try {
            if (pbIsConfirm) {
                $("#ohdCardShiftTopUpCardStaPrcDoc").val(2); // Set status for processing approve
                $("#odvAdjStkSubPopupApv").modal("hide");

                tXthDocNo = $("#oetAdjStkSubAjhDocNo").val();
                tXthStaApv = $("#ohdXthStaApv").val();

                $.ajax({
                    type: "POST",
                    url: "AdjStkSubApprove",
                    data: {
                        tXthDocNo: tXthDocNo,
                        tXthStaApv: tXthStaApv
                    },
                    cache: false,
                    timeout: 0,
                    success: function (tResult) {
                        console.log(tResult);
                        try {
                            let oResult = JSON.parse(tResult);
                            if (oResult.nStaEvent == "900") {
                                FSvCMNSetMsgErrorDialog(oResult.tStaMessg);
                            }
                        } catch (e) {
                        }

                        JSoAdjStkSubSubscribeMQ();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            } else {
                //console.log("StaApvDoc Call Modal");
                $("#odvAdjStkSubPopupApv").modal("show");
            }
        } catch (err) {
            console.log("JSnAdjStkSubApprove Error: ", err);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

/**
 * Functionality : Action for approve
 * Parameters : pbIsConfirm
 * Creator : 11/04/2019 Krit(Copter)
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSoAdjStkSubSubscribeMQ() {
    // RabbitMQ
    /*===========================================================================*/
    // Document variable
    var tLangCode = $("#ohdLangEdit").val();
    var tUsrBchCode = $("#ohdBchCode").val();
    var tUsrApv = $("#oetXthApvCodeUsrLogin").val();
    var tDocNo = $("#oetAdjStkSubAjhDocNo").val();
    var tPrefix = "RESAdjStkSub";
    var tStaApv = $("#ohdXthStaApv").val();
    var tStaDelMQ = $("#ohdXthStaDelMQ").val();
    var tQName = tPrefix + "_" + tDocNo + "_" + tUsrApv;

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

    // RabbitMQ STOMP Config
    var poMqConfig = {
        host: "ws://202.44.55.94:15674/ws",
        username: "adasoft",
        password: "adasoft",
        vHost: "AdaPosV5.0"
    };

    // Update Status For Delete Qname Parameter
    var poUpdateStaDelQnameParams = {
        ptDocTableName: "TCNTPdtTwxHD",
        ptDocFieldDocNo: "FTXthDocNo",
        ptDocFieldStaApv: "FTXthStaPrcStk",
        ptDocFieldStaDelMQ: "FTXthStaDelMQ",
        ptDocStaDelMQ: tStaDelMQ,
        ptDocNo: tDocNo
    };

    // Callback Page Control(function)
    var poCallback = {
        tCallPageEdit: "JSvCallPageAdjStkSubEdit",
        tCallPageList: "JSvCallPageAdjStkSubList"
    };

    // Check Show Progress %
    FSxCMNRabbitMQMessage(
        poDocConfig,
        poMqConfig,
        poUpdateStaDelQnameParams,
        poCallback
    );
    /*===========================================================================*/
    // RabbitMQ
}

function JSnAdjStkSubCancel(pbIsConfirm) {
    tXthDocNo = $("#oetAdjStkSubAjhDocNo").val();

    if (pbIsConfirm) {
        $.ajax({
            type: "POST",
            url: "AdjStkSubCancel",
            data: {
                tXthDocNo: tXthDocNo
            },
            cache: false,
            timeout: 5000,
            success: function (tResult) {
                $("#odvAdjStkSubChangePopupStaDoc").modal("hide");

                aResult = $.parseJSON(tResult);
                if (aResult.nSta == 1) {
                    JSvCallPageAdjStkSubEdit(tXthDocNo);
                } else {
                    JCNxCloseLoading();
                    tMsgBody = aResult.tMsg;
                    FSvCMNSetMsgWarningDialog(tMsgBody);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        //Check Status Approve for Control Msg In Modal
        nStaApv = $("#ohdXthStaApv").val();

        if (nStaApv == 1) {
            $("#obpMsgApv").show();
        } else {
            $("#obpMsgApv").hide();
        }

        $("#odvAdjStkSubChangePopupStaDoc").modal("show");
    }
}

// Function : GET Scan BarCode
function JSvAdjStkSubScanPdtHTML() {
    tBarCode = $("#oetAdjStkSubScanPdtHTML").val();
    tSplCode = $("#oetSplCode").val();

    if (tBarCode != "") {
        JCNxOpenLoading();

        $.ajax({
            type: "POST",
            url: "adjStkSubGetPdtBarCode",
            data: {
                tBarCode: tBarCode,
                tSplCode: tSplCode
            },
            cache: false,
            timeout: 5000,
            success: function (tResult) {
                aResult = $.parseJSON(tResult);

                if (aResult.aData != 0) {
                    tData = $.parseJSON(aResult.aData);

                    tPdtCode = tData[0].FTPdtCode;
                    tPunCode = tData[0].FTPunCode;

                    //Funtion Add Pdt To Table
                    FSvAdjStkSubAddPdtIntoTableDT(tPdtCode, tPunCode);

                    $("#oetAdjStkSubScanPdtHTML").val("");
                    $("#oetAdjStkSubScanPdtHTML").focus();
                } else {
                    JCNxCloseLoading();
                    tMsgBody = aResult.tMsg;
                    FSvCMNSetMsgWarningDialog(tMsgBody);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        $("#oetAdjStkSubScanPdtHTML").focus();
    }
}

function JSnAdjStkSubRemoveAllDTInFile() {
    ptXthDocNo = $("#oetAdjStkSubAjhDocNo").val();

    $.ajax({
        type: "POST",
        url: "AdjStkSubRemoveAllPdtInFile",
        data: {
            ptXthDocNo: ptXthDocNo
        },
        cache: false,
        timeout: 5000,
        success: function (tResult) {
            JSvAdjStkSubLoadPdtDataTableHtml();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

function JSnAdjStkSubRemoveDTTemp(ptSeqno, ptPdtCode) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        ptXthDocNo = $("#oetAdjStkSubAjhDocNo").val();
        nPage = $(".xWPageAdjStkSubPdt .active").text();

        $.ajax({
            type: "POST",
            url: "adjStkSubRemovePdtInDTTmp",
            data: {
                ptXthDocNo: ptXthDocNo,
                ptSeqno: ptSeqno,
                ptPdtCode: ptPdtCode
            },
            cache: false,
            timeout: 5000,
            success: function (tResult) {
                JSvAdjStkSubLoadPdtDataTableHtml(nPage);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

/*function JSnAdjStkSubRemoveDTInFile(ptIndex, ptPdtCode) {
    ptXthDocNo = $("#oetAdjStkSubAjhDocNo").val();

    $.ajax({
        type: "POST",
        url: "AdjStkSubRemovePdtInFile",
        data: {
            ptXthDocNo: ptXthDocNo,
            ptIndex: ptIndex,
            ptPdtCode: ptPdtCode
        },
        cache: false,
        timeout: 5000,
        success: function (tResult) {
            JSvAdjStkSubLoadPdtDataTableHtml();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}*/

// Function : เพิ่มสินค้าจาก ลง Table ฝั่ง Client
// Create : 2018-08-28 Krit(Copter)
function FSvAdjStkSubAddPdtIntoTableDT(ptPdtCode, ptPunCode, pnXthVATInOrEx) {
    ptOptDocAdd = $("#ohdOptScanSku").val();

    JCNxOpenLoading();

    ptXthDocNo = $("#oetAdjStkSubAjhDocNo").val();
    ptBchCode = $("#ohdSesUsrBchCode").val();

    $.ajax({
        type: "POST",
        url: "adjStkSubAddPdtIntoTableDT",
        data: {
            ptXthDocNo: ptXthDocNo,
            ptBchCode: ptBchCode,
            ptPdtCode: ptPdtCode,
            ptPunCode: ptPunCode,
            ptOptDocAdd: ptOptDocAdd,
            pnXthVATInOrEx: pnXthVATInOrEx
        },
        cache: false,
        timeout: 5000,
        success: function (tResult) {
            JMvDOCGetPdtImgScan(ptPdtCode);

            JSvAdjStkSubLoadPdtDataTableHtml();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Function : Get รูปภาพของสินค้า
function JMvDOCGetPdtImgScan(ptPdtCode){

    $.ajax({
        type: "POST",
        url: "DOCGetPdtImg",
        data: { 
            tPdtCode : ptPdtCode
        },
        cache: false,
        timeout: 5000,
        success: function(tResult){
            $('#odvShowPdtImgScan').html(tResult);
        },
        error: function(data) {
            console.log(data);
        }
    });
}

/**
 * Function : เพิ่มสินค้าจาก ลง Table ฝั่ง Client
 */
function FSvAdjStkSubEditPdtIntoTableDT(ptEditSeqNo, paField, paValue) {
  ptXthDocNo = $("#oetAdjStkSubAjhDocNo").val();
  ptBchCode = $("#ohdSesUsrBchCode").val();

  $.ajax({
    type: "POST",
    url: "adjStkSubEditPdtIntoTableDT",
    data: {
      ptXthDocNo: ptXthDocNo,
      ptEditSeqNo: ptEditSeqNo,
      paField: paField,
      paValue: paValue
    },
    cache: false,
    timeout: 5000,
    success: function (tResult) {
      // console.log(tResult);

      JSvAdjStkSubLoadPdtDataTableHtml();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      JCNxResponseError(jqXHR, textStatus, errorThrown);
    }
  });
}

/*function FSvGetSelectShpByBch(ptBchCode) {
    $.ajax({
        type: "POST",
        url: "adjStkSubGetShpByBch",
        data: {ptBchCode: ptBchCode},
        cache: false,
        timeout: 5000,
        success: function (tResult) {
            var tData = $.parseJSON(tResult);

            $("#ostShpCode option").each(function () {
                if ($(this).val() != "") {
                    $(this).remove();
                }
            });

            if (tData.raItems != undefined) {
                for (var i = 0; i < tData.raItems.length; i++) {
                    if (tData.raItems[i].rtShpCode != "") {
                        //    $('.xWostShpCode #ostShpCode').append($('option')
                        //                    .val(tData.raItems[i].rtShpCode)
                        //                    .text(tData.raItems[i].rtShpName)
                        //    );
                        var data = {
                            id: tData.raItems[i].rtShpCode,
                            text: tData.raItems[i].rtShpName
                        };

                        var newOption = new Option(data.text, data.id, false, false);
                        $("#ostShpCode")
                                .append(newOption)
                                .trigger("change");
                    }
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}*/

/**
 * Functionality : Call Purchase Page Add
 * Parameters : -
 * Creator : 22/05/2019 Piya(Tiger)
 * Return : View
 * Return Type : View
 */
function JSvCallPageAdjStkSubAdd() {
    $.ajax({
        type: "POST",
        url: "adjStkSubPageAdd",
        data: {},
        cache: false,
        timeout: 5000,
        success: function (tResult) {
            if (nStaAdjStkSubBrowseType == 1) {
                $(".xCNAdjStkSubVMaster").hide();
                $(".xCNAdjStkSubVBrowse").show();
            } else {
                $(".xCNAdjStkSubVBrowse").hide();
                $(".xCNAdjStkSubVMaster").show();
                $("#oliAdjStkSubTitleEdit").hide();
                $("#oliAdjStkSubTitleAdd").show();
                $("#odvBtnAdjStkSubInfo").hide();
                $("#odvBtnAddEdit").show();
                $("#obtAdjStkSubApprove").hide();
                $("#obtAdjStkSubCancel").hide();
            }
            $("#odvContentPageAdjStkSub").html(tResult);
            // Control Object And Button ปิด เปิด
            JCNxAdjStkSubControlObjAndBtn();
            // Load Pdt Table


            if ($("#oetBchCode").val() == "") {
                $("#obtAdjStkSubBrowseShipAdd").attr("disabled", "disabled");


            }
            JSvAdjStkSubLoadPdtDataTableHtml();
            $('#ocbAdjStkSubAutoGenCode').change(function () {
                $("#oetAdjStkSubAjhDocNo").val("");
                if ($('#ocbAdjStkSubAutoGenCode').is(':checked')) {
                    $("#oetAdjStkSubAjhDocNo").attr("readonly", true);
                    $("#oetAdjStkSubAjhDocNo").attr("onfocus", "this.blur()");
                    $('#ofmAddAdjStkSub').removeClass('has-error');
                    $('#ofmAddAdjStkSub .form-group').closest('.form-group').removeClass("has-error");
                    $('#ofmAddAdjStkSub em').remove();
                } else {
                    $("#oetAdjStkSubAjhDocNo").attr("readonly", false);
                    $("#oetAdjStkSubAjhDocNo").removeAttr("onfocus");
                }

            });
            $("#oetAdjStkSubAjhDocNo,#oetXthDocDate,#oetXthDocTime").blur(function () {
                JSxSetStatusClickAdjStkSubSubmit(0);
                JSxValidateFormAddAdjStkSub();
                $('#ofmAddAdjStkSub').submit();
            });

            $(".xWConditionSearchPdt.disabled").attr("disabled", "disabled");
            // $("#obtAdjStkSubDocBrowsePdt.disabled").attr("disabled","disabled");
            // $("#obtAdjStkSubDocBrowsePdt").css("opacity","0.4");
            // $("#obtAdjStkSubDocBrowsePdt").css("cursor","not-allowed");

        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

//Functionality : validate AdjStkSub Code (validate ขั้นที่ 2 ตรวจสอบรหัสเอกสาร)
//Parameters : -
//Creator : 07/05/2019 pap
//Update : -
//Return : -
//Return Type : -
/*function JSxValidateAdjStkSubCodeDublicate() {
  $.ajax({
    type: "POST",
    url: "CheckInputGenCode",
    data: {
      tTableName: "TCNTPdtTwxHD",
      tFieldName: "FTXthDocNo",
      tCode: $("#oetAdjStkSubAjhDocNo").val()
    },
    cache: false,
    timeout: 0,
    success: function (tResult) {
      var aResult = JSON.parse(tResult);
      $("#ohdCheckDuplicateAdjStkSub").val(aResult["rtCode"]);
      if ($("#ohdCheckAdjStkSubClearValidate").val() != 1) {
        $('#ofmAddAdjStkSub').validate().destroy();
      }
      $.validator.addMethod('dublicateCode', function (value, element) {
        if ($("#ohdAdjStkSubRoute").val() == "AdjStkSubEventAdd") {
          if ($('#ocbAdjStkSubAutoGenCode').is(':checked')) {
            return true;
          } else {
            if ($("#ohdCheckDuplicateAdjStkSub").val() == 1) {
              return false;
            } else {
              return true;
            }
          }
        } else {
          return true;
        }
      });
      $('#ofmAddAdjStkSub').validate({
        focusInvalid: false,
        onclick: false,
        onfocusout: false,
        onkeyup: false,
        rules: {
          oetAdjStkSubAjhDocNo: {
            "dublicateCode": {}
          }
        },
        messages: {
          oetAdjStkSubAjhDocNo: {
            "dublicateCode": "ไม่สามารถใช้รหัสเอกสารนี้ได้"
          }
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
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
        highlight: function (element, errorClass, validClass) {
          $(element).closest('.form-group').addClass("has-error");
        },
        unhighlight: function (element, errorClass, validClass) {
          $(element).closest('.form-group').removeClass("has-error");
        },
        submitHandler: function (form) {
          if ($("#ohdCheckAdjStkSubSubmitByButton").val() == 1) {
            JSxSubmitEventByButton();
          }
        }
      });
      if ($("#ohdCheckAdjStkSubClearValidate").val() != 1) {
        $("#ofmAddAdjStkSub").submit();
        $("#ohdCheckAdjStkSubClearValidate").val(1);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      JCNxResponseError(jqXHR, textStatus, errorThrown);
    }
  });
}*/

//Functionality : เซ็ตค่าเพื่อให้รู้ว่าตอนนี้กดปุ่มบันทึกหลักจริงๆ (เพราะมีการซัมมิทฟอร์มแต่ไม่บันทึกเพื่อให้เกิด validate ใน on blur)
//Parameters : -
//Creator : 26/04/2019 pap
//Update : -
//Return : -
//Return Type : -
/*function JSxSetStatusClickAdjStkSubSubmit(pnStatus) {
  $("#ohdCheckAdjStkSubSubmitByButton").val(pnStatus);
}*/

/**
 * Functionality : (event) Add/Edit
 * Parameters : form
 * Creator : 22/05/2019 Piya(Tiger)
 * Return : Status Add
 * Return Type : number
 */
function JSnAddEditAdjStkSub() {
  JSxValidateFormAddAdjStkSub();
}


/*function JSnPmhAddCondition() {
    $("#ofmAddCondition").validate({
        rules: {
            oetPmcGrpName: "required",
            oetPmcStaGrpCond: "required",
            oetPmcBuyQty: "required",
            oetPmcBuyAmt: "required",
            ostPmcGetCond: "required"
        },
        messages: {
            oetPmcGrpName: "",
            oetPmcStaGrpCond: "",
            oetPmcBuyQty: "",
            oetPmcBuyAmt: "",
            ostPmcGetCond: ""
        },
        errorClass: "alert-validate",
        validClass: "",
        highlight: function (element, errorClass, validClass) {
            $(element)
                    .parent(".validate-input")
                    .addClass(errorClass)
                    .removeClass(validClass);
            $(element)
                    .parent()
                    .parent(".validate-input")
                    .addClass(errorClass)
                    .removeClass(validClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element)
                    .parent(".validate-input")
                    .removeClass(errorClass)
                    .addClass(validClass);
            $(element)
                    .parent()
                    .parent(".validate-input")
                    .removeClass(errorClass)
                    .addClass(validClass);
        },
        submitHandler: function (form) {
            //กลุ่ม
            nPmcGrpName = $("#oetPmcGrpName").val();
            tPmcGrpName = $("#oetPmcGrpName option:selected").text();

            // ประเภทการซื้อ/รับ
            nPmcStaGrpCond = $("#oetPmcStaGrpCond").val();
            tPmcStaGrpCond = $("#oetPmcStaGrpCond option:selected").text();

            // put ลง Input hiden เพื่อเช็คประเภท
            if (nPmcStaGrpCond != "" || nPmcStaGrpCond == undefined) {
                tStaGrpCondHave = $("#ohdStaGrpCondHave").val();

                if (tStaGrpCondHave != "") {
                    tStaGrpCondHave += "," + nPmcStaGrpCond;
                    $("#ohdStaGrpCondHave").val(tStaGrpCondHave);
                } else {
                    $("#ohdStaGrpCondHave").val(nPmcStaGrpCond);
                }
            }

            // ซื้อครบจำนวน
            nPmcBuyQty = $("#oetPmcBuyQty").val();
            if (nPmcBuyQty == "" || nPmcBuyQty == undefined) {
                nPmcBuyQty = "-";
            } else {
                nPmcBuyQty = parseFloat(nPmcBuyQty);
                nPmcBuyQty = nPmcBuyQty.toFixed(2);
            }

            // ซื้อครบมูลค่า
            nPmcBuyAmt = $("#oetPmcBuyAmt").val();
            if (nPmcBuyAmt == "" || nPmcBuyAmt == undefined) {
                nPmcBuyAmt = "-";
            } else {
                nPmcBuyAmt = parseFloat(nPmcBuyAmt);
                nPmcBuyAmt = nPmcBuyAmt.toFixed(2);
            }

            // รูปแบบส่วนลด
            nPmcGetCond = $("#ostPmcGetCond").val();
            tPmcGetCond = $("#ostPmcGetCond option:selected").text();

            if (nPmcGetCond == 4) {
                ohdControllCound = $("#ohdControllCound").val();
                $("#ohdControllCound").val(ohdControllCound + nPmcGetCond + ",");
            }

            // Avg %
            nPmcPerAvgDis = $("#oetPmcPerAvgDis").val();
            if (nPmcPerAvgDis == "" || nPmcPerAvgDis == undefined) {
                nPmcPerAvgDis = "-";
            } else {
                nPmcPerAvgDis = parseFloat(nPmcPerAvgDis);
                nPmcPerAvgDis = nPmcPerAvgDis.toFixed(2);
            }

            // มูลค่า
            nPmcGetValue = $("#oetPmcGetValue").val();
            if (nPmcGetValue == "" || nPmcGetValue == undefined) {
                nPmcGetValue = "-";
            } else {
                nPmcGetValue = parseFloat(nPmcGetValue);
                nPmcGetValue = nPmcGetValue.toFixed(2);
            }

            // จำนวน
            nPmcGetQty = $("#oetPmcGetQty").val();
            if (nPmcGetQty == "" || nPmcGetQty == undefined) {
                nPmcGetQty = "-";
            } else {
                nPmcGetQty = parseFloat(nPmcGetQty);
                nPmcGetQty = nPmcGetQty.toFixed(2);
            }

            // ขั้นต่ำ
            nPmcBuyMinQty = $("#oetPmcBuyMinQty").val();
            if (nPmcBuyMinQty == "" || nPmcBuyMinQty == undefined) {
                nPmcBuyMinQty = "-";
            } else {
                nPmcBuyMinQty = parseFloat(nPmcBuyMinQty);
                nPmcBuyMinQty = nPmcBuyMinQty.toFixed(2);
            }

            // ไม่เกิน
            nPmcBuyMaxQty = $("#oetPmcBuyMaxQty").val();
            if (nPmcBuyMaxQty == "" || nPmcBuyMaxQty == undefined) {
                nPmcBuyMaxQty = "-";
            } else {
                nPmcBuyMaxQty = parseFloat(nPmcBuyMaxQty);
                nPmcBuyMaxQty = nPmcBuyMaxQty.toFixed(2);
            }

            ohdSpmStaBuy = $("#ohdSpmStaBuy").val();
            if (ohdSpmStaBuy == "3" || ohdSpmStaBuy == "4") {
                nBuyVal = nPmcBuyQty;
            } else if (ohdSpmStaBuy == "1" || ohdSpmStaBuy == "2") {
                nBuyVal = nPmcBuyAmt;
            }

            var nRows = $("#odvCondition tr.xWCondition").length;
            var nRows = nRows + 1;

            if (nRows >= 0) {
                // Append Tr Unit
                $("#odvCondition").append(
                        $("<tr>")
                        .addClass("text-center xCNTextDetail2 xWCondition")
                        .attr("id", "otrCondition" + nRows)
                        .attr("data-grpcound", nPmcStaGrpCond)
                        .attr("data-getcound", nPmcGetCond)
                        .attr("data-name", tPmcGrpName)

                        // <input type="text" name="ohdCondition[]" value="1,กลุ่มซื้อ,1,1,,10,11,12">
                        .append(
                                $("<input>")
                                .addClass("xCNHide " + "xWValHiden" + nPmcGrpName)
                                .attr("name", "ohdCondition[]")
                                .val(
                                        nRows +
                                        "," +
                                        tPmcGrpName +
                                        "," +
                                        nPmcStaGrpCond +
                                        "," +
                                        nPmcPerAvgDis +
                                        "," +
                                        nPmcGetValue +
                                        "," +
                                        nPmcGetQty +
                                        "," +
                                        nPmcGetCond +
                                        "," +
                                        nPmcBuyAmt +
                                        "," +
                                        nPmcBuyQty +
                                        "," +
                                        nPmcBuyMinQty +
                                        "," +
                                        nPmcBuyMaxQty +
                                        "," +
                                        nPmcGrpName
                                        )
                                )

                        // Append Td ลำดับ
                        .append($("<td>").text(nRows))

                        // Append Td กลุ่ม
                        .append(
                                $("<td>")
                                .addClass("text-left " + "xWPut" + nPmcGrpName)
                                .text(tPmcGrpName)
                                )

                        // Append Td ซื้อ/ร้บ
                        .append($("<td>").text(tPmcStaGrpCond))

                        // Append Td Avg
                        .append(
                                $("<td>")
                                .addClass("text-right")
                                .text(nPmcPerAvgDis)
                                )

                        // Append Td Buy Amt
                        .append(
                                $("<td>")
                                .addClass("text-right")
                                .text(nPmcBuyAmt)
                                )

                        // Append Td Buy Qty
                        .append(
                                $("<td>")
                                .addClass("text-right")
                                .text(nPmcBuyQty)
                                )

                        // Append Td Min Amt
                        .append(
                                $("<td>")
                                .addClass("text-right")
                                .text(nPmcBuyMinQty)
                                )

                        // Append Td Max Amt
                        .append(
                                $("<td>")
                                .addClass("text-right")
                                .text(nPmcBuyMaxQty)
                                )

                        // Append Td Value
                        .append(
                                $("<td>")
                                .addClass("text-right")
                                .text(nPmcGetValue)
                                )

                        // Append Td Qty
                        .append(
                                $("<td>")
                                .addClass("text-right")
                                .text(nPmcGetQty)
                                )

                        // Append Td Delete
                        .append(
                                $("<td>")
                                .attr("class", "text-center")
                                .append(
                                        $("<lable>")
                                        .attr("class", "xCNTextLink")
                                        .append(
                                                $("<i>")
                                                .attr("class", "fa fa-trash-o")
                                                .attr("onclick", "JSnRemoveRow(this)")
                                                )
                                        )
                                )
                        );
            } else {
                alert("Duplicate");
            }

            $("#").val();

            $("#odvModalPmhCondition").modal("toggle"); // Close Modal

            //Clear Data Input
            $("#odvModalPmhCondition input").val("");
            $("#odvModalPmhCondition select")
                    .val("")
                    .trigger("change");
            // Clear Data Input

            JSxPMTControlGetCond(); // Check Cound เพื่อ Controll Layout
        },
        errorPlacement: function (error, element) {
            return true;
        }
    });
}*/

/*
// กลุ่มร้านค้า
function JSxSetSeqConditionMerChant(aInForCon) {
    if (tOldMchCkChange != $("#oetMchCode").val()) {
        // เครื่องจุดขาย

        $($($($("#obtAdjStkSubBrowsePosStart").parent()).parent()).parent()).addClass("xCNHide");
        $($("#obtAdjStkSubBrowsePosStart").parent()).addClass("disabled");
        $($("#obtAdjStkSubBrowsePosStart").parent()).attr("disabled", "disabled");
        $("#obtAdjStkSubBrowsePosStart").addClass("disabled");
        $("#obtAdjStkSubBrowsePosStart").attr("disabled", "disabled");


        $($($($("#obtAdjStkSubBrowsePosEnd").parent()).parent()).parent()).addClass("xCNHide");
        $($("#obtAdjStkSubBrowsePosEnd").parent()).addClass("disabled");
        $($("#obtAdjStkSubBrowsePosEnd").parent()).attr("disabled", "disabled");
        $("#obtAdjStkSubBrowsePosEnd").addClass("disabled");
        $("#obtAdjStkSubBrowsePosEnd").attr("disabled", "disabled");



        if ($("#oetMchCode").val() != "") {
            //คลังสินค้า

            $($("#obtAdjStkSubBrowseWahStart").parent()).addClass("disabled");
            $($("#obtAdjStkSubBrowseWahStart").parent()).attr("disabled", "disabled");
            $("#obtAdjStkSubBrowseWahStart").addClass("disabled");
            $("#obtAdjStkSubBrowseWahStart").attr("disabled", "disabled");


            $($("#obtAdjStkSubBrowseWahEnd").parent()).addClass("disabled");
            $($("#obtAdjStkSubBrowseWahEnd").parent()).attr("disabled", "disabled");
            $("#obtAdjStkSubBrowseWahEnd").addClass("disabled");
            $("#obtAdjStkSubBrowseWahEnd").attr("disabled", "disabled");

            // $("#obtAdjStkSubDocBrowsePdt").removeAttr("disabled");
            // $("#obtAdjStkSubDocBrowsePdt").removeClass("disabled");
            // $("#obtAdjStkSubDocBrowsePdt").css("opacity","");
            // $("#obtAdjStkSubDocBrowsePdt").css("cursor","");


            // ร้านค้า
            $($("#obtAdjStkSubBrowseShpStart").parent()).removeClass("disabled");
            $($("#obtAdjStkSubBrowseShpStart").parent()).removeAttr("disabled");
            $("#obtAdjStkSubBrowseShpStart").removeClass("disabled");
            $("#obtAdjStkSubBrowseShpStart").removeAttr("disabled");

            $($("#obtAdjStkSubBrowseShpEnd").parent()).removeClass("disabled");
            $($("#obtAdjStkSubBrowseShpEnd").parent()).removeAttr("disabled");
            $("#obtAdjStkSubBrowseShpEnd").removeClass("disabled");
            $("#obtAdjStkSubBrowseShpEnd").removeAttr("disabled");

        } else {
            // $("#obtAdjStkSubDocBrowsePdt").attr("disabled","disabled");
            // $("#obtAdjStkSubDocBrowsePdt").css("opacity","0.4");
            // $("#obtAdjStkSubDocBrowsePdt").css("cursor","not-allowed");
            // คลังสินค้า
            $($("#obtAdjStkSubBrowseWahStart").parent()).removeClass("disabled");
            $($("#obtAdjStkSubBrowseWahStart").parent()).removeAttr("disabled", "disabled");
            $("#obtAdjStkSubBrowseWahStart").removeClass("disabled");
            $("#obtAdjStkSubBrowseWahStart").removeAttr("disabled", "disabled");

            $($("#obtAdjStkSubBrowseWahEnd").parent()).removeClass("disabled");
            $($("#obtAdjStkSubBrowseWahEnd").parent()).removeAttr("disabled", "disabled");
            $("#obtAdjStkSubBrowseWahEnd").removeClass("disabled");
            $("#obtAdjStkSubBrowseWahEnd").removeAttr("disabled", "disabled");


            // ร้านค้า
            $($("#obtAdjStkSubBrowseShpStart").parent()).addClass("disabled");
            $($("#obtAdjStkSubBrowseShpStart").parent()).attr("disabled", "disabled");
            $("#obtAdjStkSubBrowseShpStart").addClass("disabled");
            $("#obtAdjStkSubBrowseShpStart").attr("disabled", "disabled");

            $($("#obtAdjStkSubBrowseShpEnd").parent()).addClass("disabled");
            $($("#obtAdjStkSubBrowseShpEnd").parent()).attr("disabled", "disabled");
            $("#obtAdjStkSubBrowseShpEnd").addClass("disabled");
            $("#obtAdjStkSubBrowseShpEnd").attr("disabled", "disabled");
        }


        $("#oetShpCodeStart").val("");
        $("#oetShpNameStart").val("");
        $("#oetShpCodeEnd").val("");
        $("#oetShpNameEnd").val("");
        $("#oetPosCodeStart").val("");
        $("#oetPosNameStart").val("");
        $("#oetPosCodeEnd").val("");
        $("#oetPosNameEnd").val("");
        $("#ohdWahCodeStart").val("");
        $("#oetWahNameStart").val("");
        $("#ohdWahCodeEnd").val("");
        $("#oetWahNameEnd").val("");
        tOldMchCkChange = "";



        if ($("#ohdShipAddSeqNo").val() != "" && $(".xWPdtItem").length == 0) {
            FSvCMNSetMsgWarningDialog("<p>ข้อมูลที่อยู่สำหรับจัดส่งเดิมจะถูกเคลียร์ โปรดทำการระบุข้อมูลที่อยู่สำหรับจัดส่งใหม่</p>");
        } else if ($(".xWPdtItem").length != 0 && $("#ohdShipAddSeqNo").val() == "") {
            FSvCMNSetMsgWarningDialog("<p>รายการสินค้าที่ท่านเพิ่มไปแล้วจะถูกล้างค่าทิ้ง เมื่อท่านเปลี่ยนกลุ่มร้านค้าสำหรับจัดส่งสินค้าใหม่</p>");
        } else if ($(".xWPdtItem").length != 0 && $("#ohdShipAddSeqNo").val() != "") {
            FSvCMNSetMsgWarningDialog("<p>ที่อยู่สำหรับจัดส่งและรายการสินค้าที่ท่านเพิ่มไปแล้ว จะถูกล้างค่าเมื่อท่านเปลี่ยนกลุ่มร้านค้าสำหรับจัดส่งใหม่</p>");
        }
        if ($("#ohdShipAddSeqNo").val() != "") {
            $("#ospShipAddAddV1No").text("-");
            $("#ospShipAddV1Soi").text("-");
            $("#ospShipAddV1Village").text("-");
            $("#ospShipAddV1Road").text("-");
            $("#ospShipAddV1SubDist").text("-");
            $("#ospShipAddV1DstCode").text("-");
            $("#ospShipAddV1PvnCode").text("-");
            $("#ospShipAddV1PostCode").text("-");
            $("#ospShipAddV2Desc1").text("-");
            $("#ospShipAddV2Desc2").text("-");
            $("#ohdShipAddSeqNo").val("");
        }
        if ($(".xWPdtItem").length != 0) {
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "AdjStkSubClearDocTemForChngCdt",
                data: {
                    tDocNo: $("#oetAdjStkSubAjhDocNo").val()
                },
                cache: false,
                timeout: 0,
                success: function (tResult) {
                    JSvAdjStkSubLoadPdtDataTableHtml();
                    JCNxCloseLoading();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }



        if ($("#oetBchCode").val() != "" ||
                $("#oetMchCode").val() != "" ||
                $("#oetShpCodeEnd").val() != "" ||
                $("#oetPosCodeEnd").val() != "") {
            $("#obtAdjStkSubBrowseShipAdd").removeAttr("disabled");
        } else {
            $("#obtAdjStkSubBrowseShipAdd").attr("disabled", "disabled");
        }
    }
}

// ร้านค้าเริ่ม
function JSxSetSeqConditionShpStart(paInForCon) {

    if (tOldShpStartCkChange != $("#oetShpCodeStart").val()) {
        $("#oetPosCodeStart").val("");
        $("#oetPosNameStart").val("");
        $("#ohdWahCodeStart").val("");
        $("#oetWahNameStart").val("");
        tOldShpStartCkChange = "";

        if ($(".xWPdtItem").length != 0) {
            FSvCMNSetMsgWarningDialog("<p>รายการสินค้าที่ท่านเพิ่มไปแล้วจะถูกล้างค่าทิ้ง เมื่อท่านเปลี่ยนร้านค้าต้นทางสำหรับจัดส่งสินค้าใหม่</p>");
        }
        if ($(".xWPdtItem").length != 0) {
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "AdjStkSubClearDocTemForChngCdt",
                data: {
                    tDocNo: $("#oetAdjStkSubAjhDocNo").val()
                },
                cache: false,
                timeout: 0,
                success: function (tResult) {
                    JSvAdjStkSubLoadPdtDataTableHtml();
                    JCNxCloseLoading();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }


        if ($("#oetShpCodeStart").val() != "") {
            var aData = JSON.parse(paInForCon);
            tInforType = aData[2];
            if (tInforType == '4') {
                $($($($("#obtAdjStkSubBrowsePosStart").parent()).parent()).parent()).removeClass("xCNHide");
                //เครื่องจุดขาย
                $($("#obtAdjStkSubBrowsePosStart").parent()).removeClass("disabled");
                $($("#obtAdjStkSubBrowsePosStart").parent()).removeAttr("disabled");
                $("#obtAdjStkSubBrowsePosStart").removeClass("disabled");
                $("#obtAdjStkSubBrowsePosStart").removeAttr("disabled");
                //คลังสินค้า
                $($("#obtAdjStkSubBrowseWahStart").parent()).removeClass("disabled");
                $($("#obtAdjStkSubBrowseWahStart").parent()).removeAttr("disabled", "disabled");
                $("#obtAdjStkSubBrowseWahStart").removeClass("disabled");
                $("#obtAdjStkSubBrowseWahStart").removeAttr("disabled", "disabled");
            } else {
                $($($($("#obtAdjStkSubBrowsePosStart").parent()).parent()).parent()).addClass("xCNHide");
                //เครื่องจุดขาย
                $($("#obtAdjStkSubBrowsePosStart").parent()).addClass("disabled");
                $($("#obtAdjStkSubBrowsePosStart").parent()).attr("disabled", "disabled");
                $("#obtAdjStkSubBrowsePosStart").addClass("disabled");
                $("#obtAdjStkSubBrowsePosStart").attr("disabled", "disabled");

                $("#ohdWahCodeStart").val(aData[3]);
                $("#oetWahNameStart").val(aData[4]);
                //คลังสินค้า
                $($("#obtAdjStkSubBrowseWahStart").parent()).addClass("disabled");
                $($("#obtAdjStkSubBrowseWahStart").parent()).attr("disabled", "disabled");
                $("#obtAdjStkSubBrowseWahStart").addClass("disabled");
                $("#obtAdjStkSubBrowseWahStart").attr("disabled", "disabled");
            }
            if ($("#oetShpCodeEnd").val() != "") {
                if ($("#oetShpCodeStart").val() == $("#oetShpCodeEnd").val()) {
                    if ($("#oetPosCodeEnd").val() == "" && $("#ohdWahCodeEnd").val() != "") {
                        //คลังสินค้า
                        $($("#obtAdjStkSubBrowseWahStart").parent()).addClass("disabled");
                        $($("#obtAdjStkSubBrowseWahStart").parent()).attr("disabled", "disabled");
                        $("#obtAdjStkSubBrowseWahStart").addClass("disabled");
                        $("#obtAdjStkSubBrowseWahStart").attr("disabled", "disabled");


                    }
                }
            }
        } else {
            $($($($("#obtAdjStkSubBrowsePosStart").parent()).parent()).parent()).addClass("xCNHide");
            //เครื่องจุดขาย
            $($("#obtAdjStkSubBrowsePosStart").parent()).addClass("disabled");
            $($("#obtAdjStkSubBrowsePosStart").parent()).attr("disabled", "disabled");
            $("#obtAdjStkSubBrowsePosStart").addClass("disabled");
            $("#obtAdjStkSubBrowsePosStart").attr("disabled", "disabled");

            if ($("#oetBchCode").val() == "") {
                //คลังสินค้า
                $($("#obtAdjStkSubBrowseWahStart").parent()).addClass("disabled");
                $($("#obtAdjStkSubBrowseWahStart").parent()).attr("disabled", "disabled");
                $("#obtAdjStkSubBrowseWahStart").addClass("disabled");
                $("#obtAdjStkSubBrowseWahStart").attr("disabled", "disabled");

            } else {
                if ($("#oetMchCode").val() != "") {
                    //คลังสินค้า
                    $($("#obtAdjStkSubBrowseWahStart").parent()).addClass("disabled");
                    $($("#obtAdjStkSubBrowseWahStart").parent()).attr("disabled", "disabled");
                    $("#obtAdjStkSubBrowseWahStart").addClass("disabled");
                    $("#obtAdjStkSubBrowseWahStart").attr("disabled", "disabled");
                } else {
                    //คลังสินค้า
                    $($("#obtAdjStkSubBrowseWahStart").parent()).removeClass("disabled");
                    $($("#obtAdjStkSubBrowseWahStart").parent()).removeAttr("disabled", "disabled");
                    $("#obtAdjStkSubBrowseWahStart").removeClass("disabled");
                    $("#obtAdjStkSubBrowseWahStart").removeAttr("disabled", "disabled");
                }

            }
        }
        if ($("#oetBchCode").val() != "" ||
                $("#oetMchCode").val() != "" ||
                $("#oetShpCodeEnd").val() != "" ||
                $("#oetPosCodeEnd").val() != "") {
            $("#obtAdjStkSubBrowseShipAdd").removeAttr("disabled");
        } else {
            $("#obtAdjStkSubBrowseShipAdd").attr("disabled", "disabled");
        }

    }
}

// เครื่องจุดขาย เริ่ม
function JSxSetSeqConditionPosStart(paInForCon) {
    $("#ohdWahCodeStart").val("");
    $("#oetWahNameStart").val("");
    if ($("#oetPosCodeStart").val() != "") {
        var aData = JSON.parse(paInForCon);
        $("#ohdWahCodeStart").val(aData[3]);
        $("#oetWahNameStart").val(aData[4]);
        //คลังสินค้า
        $($("#obtAdjStkSubBrowseWahStart").parent()).addClass("disabled");
        $($("#obtAdjStkSubBrowseWahStart").parent()).attr("disabled", "disabled");
        $("#obtAdjStkSubBrowseWahStart").addClass("disabled");
        $("#obtAdjStkSubBrowseWahStart").attr("disabled", "disabled");
        if ($("#oetShpCodeStart").val() == $("#oetShpCodeEnd").val()) {
            if (!$($($($("#obtAdjStkSubBrowsePosEnd").parent()).parent()).parent()).hasClass("xCNHide")) {
                if ($("#oetPosCodeEnd").val() == "") {
                    //คลังสินค้า
                    $($("#obtAdjStkSubBrowseWahEnd").parent()).removeClass("disabled");
                    $($("#obtAdjStkSubBrowseWahEnd").parent()).removeAttr("disabled", "disabled");
                    $("#obtAdjStkSubBrowseWahEnd").removeClass("disabled");
                    $("#obtAdjStkSubBrowseWahEnd").removeAttr("disabled", "disabled");
                }
            }
        }
    } else {
        //คลังสินค้า
        $($("#obtAdjStkSubBrowseWahStart").parent()).removeClass("disabled");
        $($("#obtAdjStkSubBrowseWahStart").parent()).removeAttr("disabled", "disabled");
        $("#obtAdjStkSubBrowseWahStart").removeClass("disabled");
        $("#obtAdjStkSubBrowseWahStart").removeAttr("disabled", "disabled");
        if (!$($($($("#obtAdjStkSubBrowsePosEnd").parent()).parent()).parent()).hasClass("xCNHide")) {
            if ($("#oetPosCodeEnd").val() == "" && $("#ohdWahCodeEnd").val() != "") {
                //คลังสินค้า
                $($("#obtAdjStkSubBrowseWahStart").parent()).addClass("disabled");
                $($("#obtAdjStkSubBrowseWahStart").parent()).attr("disabled", "disabled");
                $("#obtAdjStkSubBrowseWahStart").addClass("disabled");
                $("#obtAdjStkSubBrowseWahStart").attr("disabled", "disabled");
            }
        }
    }

    if ($("#oetBchCode").val() != "" ||
            $("#oetMchCode").val() != "" ||
            $("#oetShpCodeEnd").val() != "" ||
            $("#oetPosCodeEnd").val() != "") {
        $("#obtAdjStkSubBrowseShipAdd").removeAttr("disabled");
    } else {
        $("#obtAdjStkSubBrowseShipAdd").attr("disabled", "disabled");
    }
}

// คลัง เริ่ม
function JSxSetSeqConditionWahStart(paInForCon) {
    if ($("#oetShpCodeEnd").val() != "") {
        if ($("#oetShpCodeStart").val() == $("#oetShpCodeEnd").val()) {
            if (!$($($($("#obtAdjStkSubBrowsePosEnd").parent()).parent()).parent()).hasClass("xCNHide")) {
                if ($("#oetPosCodeEnd").val() == "" && $("#ohdWahCodeEnd").val() == "") {
                    //คลังสินค้า
                    $($("#obtAdjStkSubBrowseWahEnd").parent()).addClass("disabled");
                    $($("#obtAdjStkSubBrowseWahEnd").parent()).attr("disabled", "disabled");
                    $("#obtAdjStkSubBrowseWahEnd").addClass("disabled");
                    $("#obtAdjStkSubBrowseWahEnd").attr("disabled", "disabled");
                    if ($("#oetPosCodeStart").val() == "" && $("#ohdWahCodeStart").val() == "") {
                        //คลังสินค้า
                        $($("#obtAdjStkSubBrowseWahEnd").parent()).removeClass("disabled");
                        $($("#obtAdjStkSubBrowseWahEnd").parent()).removeAttr("disabled", "disabled");
                        $("#obtAdjStkSubBrowseWahEnd").removeClass("disabled");
                        $("#obtAdjStkSubBrowseWahEnd").removeAttr("disabled", "disabled");
                    }
                }
            }
        }
    }

    if ($("#oetBchCode").val() != "" ||
            $("#oetMchCode").val() != "" ||
            $("#oetShpCodeEnd").val() != "" ||
            $("#oetPosCodeEnd").val() != "") {
        $("#obtAdjStkSubBrowseShipAdd").removeAttr("disabled");
    } else {
        $("#obtAdjStkSubBrowseShipAdd").attr("disabled", "disabled");
    }
}

// ร้านค้าจบ
function JSxSetSeqConditionShpEnd(paInForCon) {

    if (tOldShpEndCkChange != $("#oetShpCodeEnd").val()) {
        $("#oetPosCodeEnd").val("");
        $("#oetPosNameEnd").val("");
        $("#ohdWahCodeEnd").val("");
        $("#oetWahNameEnd").val("");
        tOldShpEndCkChange = "";

        if ($("#oetShpCodeEnd").val() != "") {
            var aData = JSON.parse(paInForCon);
            tInforType = aData[2];
            if (tInforType == '4') {
                $($($($("#obtAdjStkSubBrowsePosEnd").parent()).parent()).parent()).removeClass("xCNHide");
                //เครื่องจุดขาย
                $($("#obtAdjStkSubBrowsePosEnd").parent()).removeClass("disabled");
                $($("#obtAdjStkSubBrowsePosEnd").parent()).removeAttr("disabled");
                $("#obtAdjStkSubBrowsePosEnd").removeClass("disabled");
                $("#obtAdjStkSubBrowsePosEnd").removeAttr("disabled");

                //คลังสินค้า
                $($("#obtAdjStkSubBrowseWahEnd").parent()).removeClass("disabled");
                $($("#obtAdjStkSubBrowseWahEnd").parent()).removeAttr("disabled", "disabled");
                $("#obtAdjStkSubBrowseWahEnd").removeClass("disabled");
                $("#obtAdjStkSubBrowseWahEnd").removeAttr("disabled", "disabled");
            } else {
                $($($($("#obtAdjStkSubBrowsePosEnd").parent()).parent()).parent()).addClass("xCNHide");
                //เครื่องจุดขาย
                $($("#obtAdjStkSubBrowsePosEnd").parent()).addClass("disabled");
                $($("#obtAdjStkSubBrowsePosEnd").parent()).attr("disabled", "disabled");
                $("#obtAdjStkSubBrowsePosEnd").addClass("disabled");
                $("#obtAdjStkSubBrowsePosEnd").attr("disabled", "disabled");

                $("#ohdWahCodeEnd").val(aData[3]);
                $("#oetWahNameEnd").val(aData[4]);

                //คลังสินค้า
                $($("#obtAdjStkSubBrowseWahEnd").parent()).addClass("disabled");
                $($("#obtAdjStkSubBrowseWahEnd").parent()).attr("disabled", "disabled");
                $("#obtAdjStkSubBrowseWahEnd").addClass("disabled");
                $("#obtAdjStkSubBrowseWahEnd").attr("disabled", "disabled");
            }
            if ($("#oetShpCodeStart").val() != "") {
                if ($("#oetShpCodeStart").val() == $("#oetShpCodeEnd").val()) {
                    if ($("#oetPosCodeStart").val() == "" && $("#ohdWahCodeStart").val() != "") {
                        //คลังสินค้า
                        $($("#obtAdjStkSubBrowseWahEnd").parent()).addClass("disabled");
                        $($("#obtAdjStkSubBrowseWahEnd").parent()).attr("disabled", "disabled");
                        $("#obtAdjStkSubBrowseWahEnd").addClass("disabled");
                        $("#obtAdjStkSubBrowseWahEnd").attr("disabled", "disabled");
                    }
                }
            }
        } else {
            $($($($("#obtAdjStkSubBrowsePosEnd").parent()).parent()).parent()).addClass("xCNHide");
            //เครื่องจุดขาย
            $($("#obtAdjStkSubBrowsePosEnd").parent()).addClass("disabled");
            $($("#obtAdjStkSubBrowsePosEnd").parent()).attr("disabled", "disabled");
            $("#obtAdjStkSubBrowsePosEnd").addClass("disabled");
            $("#obtAdjStkSubBrowsePosEnd").attr("disabled", "disabled");

            if ($("#oetBchCode").val() == "") {
                //คลังสินค้า
                $($("#obtAdjStkSubBrowseWahEnd").parent()).addClass("disabled");
                $($("#obtAdjStkSubBrowseWahEnd").parent()).attr("disabled", "disabled");
                $("#obtAdjStkSubBrowseWahEnd").addClass("disabled");
                $("#obtAdjStkSubBrowseWahEnd").attr("disabled", "disabled");

            } else {
                if ($("#oetMchCode").val() != "") {
                    //คลังสินค้า
                    $($("#obtAdjStkSubBrowseWahEnd").parent()).addClass("disabled");
                    $($("#obtAdjStkSubBrowseWahEnd").parent()).attr("disabled", "disabled");
                    $("#obtAdjStkSubBrowseWahEnd").addClass("disabled");
                    $("#obtAdjStkSubBrowseWahEnd").attr("disabled", "disabled");
                } else {
                    //คลังสินค้า
                    $($("#obtAdjStkSubBrowseWahEnd").parent()).removeClass("disabled");
                    $($("#obtAdjStkSubBrowseWahEnd").parent()).removeAttr("disabled", "disabled");
                    $("#obtAdjStkSubBrowseWahEnd").removeClass("disabled");
                    $("#obtAdjStkSubBrowseWahEnd").removeAttr("disabled", "disabled");
                }

            }
        }

        if ($("#oetBchCode").val() != "" ||
                $("#oetMchCode").val() != "" ||
                $("#oetShpCodeEnd").val() != "" ||
                $("#oetPosCodeEnd").val() != "") {
            $("#obtAdjStkSubBrowseShipAdd").removeAttr("disabled");
        } else {
            $("#obtAdjStkSubBrowseShipAdd").attr("disabled", "disabled");
        }

        if ($(".xWPdtItem").length == 0) {
            FSvCMNSetMsgWarningDialog("<p>ข้อมูลที่อยู่สำหรับจัดส่งเดิมจะถูกเคลียร์ โปรดทำการระบุข้อมูลที่อยู่สำหรับจัดส่งใหม่</p>");
        }
        if ($("#ohdShipAddSeqNo").val() != "") {
            $("#ospShipAddAddV1No").text("-");
            $("#ospShipAddV1Soi").text("-");
            $("#ospShipAddV1Village").text("-");
            $("#ospShipAddV1Road").text("-");
            $("#ospShipAddV1SubDist").text("-");
            $("#ospShipAddV1DstCode").text("-");
            $("#ospShipAddV1PvnCode").text("-");
            $("#ospShipAddV1PostCode").text("-");
            $("#ospShipAddV2Desc1").text("-");
            $("#ospShipAddV2Desc2").text("-");
            $("#ohdShipAddSeqNo").val("");

        }
    }

}

// เครื่องจุดขาย จบ
function JSxSetSeqConditionPosEnd(paInForCon) {
    $("#ohdWahCodeEnd").val("");
    $("#oetWahNameEnd").val("");
    if ($("#oetPosCodeEnd").val() != "") {
        var aData = JSON.parse(paInForCon);
        $("#ohdWahCodeEnd").val(aData[3]);
        $("#oetWahNameEnd").val(aData[4]);
        //คลังสินค้า
        $($("#obtAdjStkSubBrowseWahEnd").parent()).addClass("disabled");
        $($("#obtAdjStkSubBrowseWahEnd").parent()).attr("disabled", "disabled");
        $("#obtAdjStkSubBrowseWahEnd").addClass("disabled");
        $("#obtAdjStkSubBrowseWahEnd").attr("disabled", "disabled");
        if ($("#oetShpCodeStart").val() == $("#oetShpCodeEnd").val()) {
            if (!$($($($("#obtAdjStkSubBrowsePosStart").parent()).parent()).parent()).hasClass("xCNHide")) {
                if ($("#oetPosCodeStart").val() == "") {
                    //คลังสินค้า
                    $($("#obtAdjStkSubBrowseWahStart").parent()).removeClass("disabled");
                    $($("#obtAdjStkSubBrowseWahStart").parent()).removeAttr("disabled", "disabled");
                    $("#obtAdjStkSubBrowseWahStart").removeClass("disabled");
                    $("#obtAdjStkSubBrowseWahStart").removeAttr("disabled", "disabled");
                }
            }
        }
    } else {
        //คลังสินค้า
        $($("#obtAdjStkSubBrowseWahEnd").parent()).removeClass("disabled");
        $($("#obtAdjStkSubBrowseWahEnd").parent()).removeAttr("disabled", "disabled");
        $("#obtAdjStkSubBrowseWahEnd").removeClass("disabled");
        $("#obtAdjStkSubBrowseWahEnd").removeAttr("disabled", "disabled");
        if (!$($($($("#obtAdjStkSubBrowsePosStart").parent()).parent()).parent()).hasClass("xCNHide")) {
            if ($("#oetPosCodeStart").val() == "" && $("#ohdWahCodeStart").val() != "") {
                //คลังสินค้า
                $($("#obtAdjStkSubBrowseWahEnd").parent()).addClass("disabled");
                $($("#obtAdjStkSubBrowseWahEnd").parent()).attr("disabled", "disabled");
                $("#obtAdjStkSubBrowseWahEnd").addClass("disabled");
                $("#obtAdjStkSubBrowseWahEnd").attr("disabled", "disabled");
            }
        }
    }

    if ($("#oetBchCode").val() != "" ||
            $("#oetMchCode").val() != "" ||
            $("#oetShpCodeEnd").val() != "" ||
            $("#oetPosCodeEnd").val() != "") {
        $("#obtAdjStkSubBrowseShipAdd").removeAttr("disabled");
    } else {
        $("#obtAdjStkSubBrowseShipAdd").attr("disabled", "disabled");
    }

    if ($("#ohdShipAddSeqNo").val() != "" && $(".xWPdtItem").length == 0) {
        FSvCMNSetMsgWarningDialog("<p>ข้อมูลที่อยู่สำหรับจัดส่งเดิมจะถูกเคลียร์ โปรดทำการระบุข้อมูลที่อยู่สำหรับจัดส่งใหม่</p>");
    }
    if ($("#ohdShipAddSeqNo").val() != "") {
        $("#ospShipAddAddV1No").text("-");
        $("#ospShipAddV1Soi").text("-");
        $("#ospShipAddV1Village").text("-");
        $("#ospShipAddV1Road").text("-");
        $("#ospShipAddV1SubDist").text("-");
        $("#ospShipAddV1DstCode").text("-");
        $("#ospShipAddV1PvnCode").text("-");
        $("#ospShipAddV1PostCode").text("-");
        $("#ospShipAddV2Desc1").text("-");
        $("#ospShipAddV2Desc2").text("-");
        $("#ohdShipAddSeqNo").val("");
    }
}

// คลัง จบ
function JSxSetSeqConditionWahEnd(paInForCon) {
    if ($("#oetShpCodeStart").val() != "") {
        if ($("#oetShpCodeStart").val() == $("#oetShpCodeEnd").val()) {
            if (!$($($($("#obtAdjStkSubBrowsePosStart").parent()).parent()).parent()).hasClass("xCNHide")) {
                if ($("#oetPosCodeStart").val() == "" && $("#ohdWahCodeStart").val() == "") {
                    //คลังสินค้า
                    $($("#obtAdjStkSubBrowseWahStart").parent()).addClass("disabled");
                    $($("#obtAdjStkSubBrowseWahStart").parent()).attr("disabled", "disabled");
                    $("#obtAdjStkSubBrowseWahStart").addClass("disabled");
                    $("#obtAdjStkSubBrowseWahStart").attr("disabled", "disabled");
                    if ($("#oetPosCodeEnd").val() == "" && $("#ohdWahCodeEnd").val() == "") {
                        //คลังสินค้า
                        $($("#obtAdjStkSubBrowseWahStart").parent()).removeClass("disabled");
                        $($("#obtAdjStkSubBrowseWahStart").parent()).removeAttr("disabled", "disabled");
                        $("#obtAdjStkSubBrowseWahStart").removeClass("disabled");
                        $("#obtAdjStkSubBrowseWahStart").removeAttr("disabled", "disabled");
                    }
                }
            }
        }
    }

    if ($("#oetBchCode").val() != "" ||
            $("#oetMchCode").val() != "" ||
            $("#oetShpCodeEnd").val() != "" ||
            $("#oetPosCodeEnd").val() != "") {
        $("#obtAdjStkSubBrowseShipAdd").removeAttr("disabled");
    } else {
        $("#obtAdjStkSubBrowseShipAdd").attr("disabled", "disabled");
    }
}
*/

function JSvCallPageAdjStkSubList() {
    try {
        $.ajax({
            type: "GET",
            url: "adjStkSubFormSearchList",
            data: {},
            cache: false,
            timeout: 5000,
            success: function (tResult) {
                $("#odvContentPageAdjStkSub").html(tResult);
                JSxAdjStkSubNavDefult();

                JSvCallPageAdjStkSubPdtDataTable(); // แสดงข้อมูลใน List
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } catch (err) {
        console.log('JSvCallPageAdjStkSubList Error: ', err);
    }
}

function JSvCallPageAdjStkSubPdtDataTable(pnPage) {
    JCNxOpenLoading();

    var nPageCurrent = pnPage;
    if (nPageCurrent == undefined || nPageCurrent == "") {
        nPageCurrent = "1";
    }

    var oAdvanceSearch = JSoAdjStkSubGetAdvanceSearchData();

    $.ajax({
        type: "POST",
        url: "adjStkSubDataTable",
        data: {
            oAdvanceSearch: oAdvanceSearch,
            nPageCurrent: nPageCurrent
        },
        cache: false,
        timeout: 5000,
        success: function (tResult) {
            $("#odvContentPurchaseorder").html(tResult);

            JSxAdjStkSubNavDefult();
            JCNxLayoutControll();
            JCNxCloseLoading();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

/**
 * Functionality : Get search data
 * Parameters : -
 * Creator : 22/05/2019 Piya(Tiger)
 * Last Modified : -
 * Return : Search data
 * Return Type : Object
 */
function JSoAdjStkSubGetAdvanceSearchData() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        try {
            let oAdvanceSearchData = {
                tSearchAll: $("#oetSearchAll").val(),
                tSearchBchCodeFrom: $("#oetBchCodeFrom").val(),
                tSearchBchCodeTo: $("#oetBchCodeTo").val(),
                tSearchDocDateFrom: $("#oetSearchDocDateFrom").val(),
                tSearchDocDateTo: $("#oetSearchDocDateTo").val(),
                tSearchStaDoc: $("#ocmStaDoc").val(),
                tSearchStaApprove: $("#ocmStaApprove").val(),
                tSearchStaPrcStk: $("#ocmStaPrcStk").val()
            };
            return oAdvanceSearchData;
        } catch (err) {
            console.log("JSoAdjStkSubGetAdvanceSearchData Error: ", err);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

/**
 * Functionality : Clear search data
 * Parameters : -
 * Creator : 22/05/2019 Piya(Tiger)
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSxAdjStkSubClearSearchData() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        try {
            $("#oetSearchAll").val("");
            $("#oetBchCodeFrom").val("");
            $("#oetBchNameFrom").val("");
            $("#oetBchCodeTo").val("");
            $("#oetBchNameTo").val("");
            $("#oetSearchDocDateFrom").val("");
            $("#oetSearchDocDateTo").val("");
            $(".xCNDatePicker").datepicker("setDate", null);
            $(".selectpicker")
                    .val("0")
                    .selectpicker("refresh");
            JSvCallPageAdjStkSubPdtDataTable();
        } catch (err) {
            console.log("JSxAdjStkSubClearSearchData Error: ", err);
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

/**
 * Functionality : Call Credit Page Edit
 * Parameters : -
 * Creator : 22/05/2019 Piya(Tiger)
 * Return : View
 * Return Type : View
 */
function JSvCallPageAdjStkSubEdit(ptAjhDocNo) {
    JCNxOpenLoading();

    JStCMMGetPanalLangSystemHTML("JSvCallPageAdjStkSubEdit", ptAjhDocNo);

    $.ajax({
        type: "POST",
        url: "adjStkSubPageEdit",
        data: {ptAjhDocNo: ptAjhDocNo},
        cache: false,
        timeout: 0,
        success: function (tResult) {
            if (tResult != "") {
                $("#oliAdjStkSubTitleAdd").hide();
                $("#oliAdjStkSubTitleEdit").show();
                $("#odvBtnAdjStkSubInfo").hide();
                $("#odvBtnAddEdit").show();
                $("#odvContentPageAdjStkSub").html(tResult);
                $("#oetAdjStkSubAjhDocNo").addClass("xCNDisable");
                $(".xCNDisable").attr("readonly", true);
                $(".xCNiConGen").hide();
                $("#obtAdjStkSubApprove").show();
                $("#obtAdjStkSubCancel").show();

            }

            //Control Event Button
            /*if ($("#ohdAdjStkSubAutStaEdit").val() == 0) {
              $(".xCNUplodeImage").hide();
              $(".xCNIconBrowse").hide();
              $(".xCNEditRowBtn").hide();
              $("select").prop("disabled", true);
              $("input").attr("disabled", true);
            } else {
              $(".xCNUplodeImage").show();
              $(".xCNIconBrowse").show();
              $(".xCNEditRowBtn").show();
              $("select").prop("disabled", false);
              $("input").attr("disabled", false);
            }*/
            // Control Event Button

            // Function Load Table Pdt ของ AdjStkSub
            JSvAdjStkSubLoadPdtDataTableHtml();

            // Put Data
            ohdXthCshOrCrd = $("#ohdXthCshOrCrd").val();
            $("#ostXthCshOrCrd option[value='" + ohdXthCshOrCrd + "']").attr("selected", true).trigger("change");

            ohdXthStaRef = $("#ohdXthStaRef").val();
            $("#ostXthStaRef option[value='" + ohdXthStaRef + "']").attr("selected", true).trigger("change");

            // Control Object And Button ปิด เปิด
            JCNxAdjStkSubControlObjAndBtn();

            JCNxLayoutControll();
            $(".xWConditionSearchPdt.disabled").attr("disabled", "disabled");
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Function : Control Object And Button ปิด เปิด
function JCNxAdjStkSubControlObjAndBtn() {
    // Check สถานะอนุมัติ
    var ohdXthStaApv = $("#ohdXthStaApv").val();
    var ohdXthStaDoc = $("#ohdXthStaDoc").val();

    // Set Default
    // Btn Cancel
    $("#obtAdjStkSubCancel").attr("disabled", false);
    // Btn Apv
    $("#obtAdjStkSubApprove").attr("disabled", false);
    // $(".form-control").attr("disabled", false);
    $(".ocbListItem").attr("disabled", false);
    // $(".xCNBtnBrowseAddOn").attr("disabled", false);
    $(".xCNBtnDateTime").attr("disabled", false);
    $(".xCNDocBrowsePdt").attr("disabled", false).removeClass("xCNBrowsePdtdisabled");
    $(".xCNDocDrpDwn").show();
    $("#oetAdjStkSubSearchPdtHTML").attr("disabled", false);
    $(".xWBtnGrpSaveLeft").attr("disabled", false);
    $(".xWBtnGrpSaveRight").attr("disabled", false);
    $("#oliBtnEditShipAdd").show();
    $("#oliBtnEditTaxAdd").show();

    if (ohdXthStaApv == 1) {
        // Btn Apv
        $("#obtAdjStkSubApprove").attr("disabled", true);
        // Control input ปิด
        // $(".form-control").attr("disabled", true);
        $(".ocbListItem").attr("disabled", true);
        // $(".xCNBtnBrowseAddOn").attr("disabled", true);
        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetAdjStkSubSearchPdtHTML").attr("disabled", false);
        $(".xWBtnGrpSaveLeft").attr("disabled", true);
        $(".xWBtnGrpSaveRight").attr("disabled", true);
        $("#oliBtnEditShipAdd").hide();
        $("#oliBtnEditTaxAdd").hide();
    }
    // Check สถานะเอกสาร
    if (ohdXthStaDoc == 3) {
        // Btn Cancel
        $("#obtAdjStkSubCancel").attr("disabled", true);
        // Btn Apv
        $("#obtAdjStkSubApprove").attr("disabled", true);
        // Control input ปิด
        // $(".form-control").attr("disabled", true);
        $(".ocbListItem").attr("disabled", true);
        // $(".xCNBtnBrowseAddOn").attr("disabled", true);
        $(".xCNBtnDateTime").attr("disabled", true);
        $(".xCNDocBrowsePdt").attr("disabled", true).addClass("xCNBrowsePdtdisabled");
        $(".xCNDocDrpDwn").hide();
        $("#oetAdjStkSubSearchPdtHTML").attr("disabled", false);
        $(".xWBtnGrpSaveLeft").attr("disabled", true);
        $(".xWBtnGrpSaveRight").attr("disabled", true);
        $("#oliBtnEditShipAdd").hide();
        $("#oliBtnEditTaxAdd").hide();
    }
}

/**
 * Functionality : (event) Delete
 * Parameters : tIDCode รหัส
 * Creator : 22/05/2019 Piya(Tiger)
 * Return : 
 * Return Type : Status Number
 */
function JSnAdjStkSubDel(tCurrentPage, tIDCode) {
    var aData = $("#ohdConfirmIDDelete").val();
    var aTexts = aData.substring(0, aData.length - 2);
    var aDataSplit = aTexts.split(" , ");
    var aDataSplitlength = aDataSplit.length;
    var aNewIdDelete = [];
    if (aDataSplitlength == "1") {
        $("#odvModalDel").modal("show");
        $("#ospConfirmDelete").html("ยืนยันการลบข้อมูล หมายเลข : " + tIDCode);
        $("#osmConfirm").on("click", function (evt) {
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "AdjStkSubEventDelete",
                data: {tIDCode: tIDCode},
                cache: false,
                success: function (tResult) {
                    var aReturn = JSON.parse(tResult);

                    if (aReturn["nStaEvent"] == 1) {
                        $("#odvModalDel").modal("hide");
                        $("#ospConfirmDelete").text("ยืนยันการลบข้อมูลของ : ");
                        $("#ohdConfirmIDDelete").val("");
                        localStorage.removeItem("LocalItemData");
                        setTimeout(function () {
                            JSvAdjStkSubClickPage(tCurrentPage);
                        }, 500);
                    } else {
                        alert(aReturn["tStaMessg"]);
                    }
                    JSxAdjStkSubNavDefult();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        });
    }
}

/**
 * Functionality : (event) Delete
 * Parameters : tIDCode รหัส
 * Creator : 22/05/2019 Piya(Tiger)
 * Return : 
 * Return Type : Status Number
 */
function JSnAdjStkSubDelChoose() {
    JCNxOpenLoading();
    var aData = $("#ohdConfirmIDDelete").val();
    var aTexts = aData.substring(0, aData.length - 2);
    var aDataSplit = aTexts.split(" , ");
    var aDataSplitlength = aDataSplit.length;
    var aNewIdDelete = [];
    for ($i = 0; $i < aDataSplitlength; $i++) {
        aNewIdDelete.push(aDataSplit[$i]);
    }
    if (aDataSplitlength > 1) {
        localStorage.StaDeleteArray = "1";
        $.ajax({
            type: "POST",
            url: "AdjStkSubEventDelete",
            data: {tIDCode: aNewIdDelete},
            success: function (tResult) {
                var aReturn = JSON.parse(tResult);
                if (aReturn["nStaEvent"] == 1) {
                    setTimeout(function () {
                        $("#odvModalDel").modal("hide");
                        JSvCallPageAdjStkSubPdtDataTable();
                        $("#ospConfirmDelete").text("ยืนยันการลบข้อมูลของ : ");
                        $("#ohdConfirmIDDelete").val("");
                        localStorage.removeItem("LocalItemData");
                        $(".modal-backdrop").remove();
                    }, 1000);
                } else {
                    alert(aReturn["tStaMessg"]);
                }
                JSxAdjStkSubNavDefult();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        localStorage.StaDeleteArray = "0";
        return false;
    }
}

/**
 * Functionality: Event Pdt Multi Delete
 * Parameters: Event Button Delete All
 * Creator: 22/05/2019 Piya(Tiger)
 * Return:  object Status Delete
 * Return Type: object
 */
function JSoAdjStkSubPdtDelChoose(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var aSeq = $("#ohdConfirmSeqDelete").val();
        var tDocNo = $("#oetAdjStkSubAjhDocNo").val();

        //PdtCode
        var aTextSeq = aSeq.substring(0, aSeq.length - 2);
        var aSeqSplit = aTextSeq.split(" , ");
        var aSeqSplitlength = aSeqSplit.length;
        //Seq
        var aTextSeq = aSeq.substring(0, aSeq.length - 2);
        var aSeqSplit = aTextSeq.split(" , ");
        var aSeqData = [];

        for ($i = 0; $i < aSeqSplitlength; $i++) {
            aSeqData.push(aSeqSplit[$i]);
        }
        
        if (aSeqSplitlength > 1) {
            // JCNxOpenLoading();
            localStorage.StaDeleteArray = "1";
            $.ajax({
                type: "POST",
                url: "adjStkSubPdtMultiDeleteEvent",
                data: {
                    tDocNo: tDocNo,
                    tSeqCode: aSeqData
                },
                success: function (tResult) {
                    console.log(tResult);
                    setTimeout(function () {
                        $("#odvModalDelPdtAdjStkSub").modal("hide");
                        JSvAdjStkSubLoadPdtDataTableHtml();
                        $("#ospConfirmDelete").text($("#oetTextComfirmDeleteSingle").val());
                        $("#ohdConfirmSeqDelete").val("");
                        $("#ohdConfirmPdtDelete").val("");
                        $("#ohdConfirmPunDelete").val("");
                        $("#ohdConfirmDocDelete").val("");
                        localStorage.removeItem("LocalItemData");
                        $(".obtChoose").hide();
                        $(".modal-backdrop").remove();
                    }, 1000);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        } else {
            localStorage.StaDeleteArray = "0";
            return false;
        }
    } else {
        JCNxShowMsgSessionExpired();
    }
}

/**
 * Functionality : เปลี่ยนหน้า pagenation
 * Parameters : -
 * Creator : 22/05/2019 Piya(Tiger)
 * Return : View
 * Return Type : View
 */
function JSvAdjStkSubClickPage(ptPage) {
    var nPageCurrent = "";
    switch (ptPage) {
        case "next": //กดปุ่ม Next
            $(".xWBtnNext").addClass("disabled");
            nPageOld = $(".xWPage .active").text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
            nPageCurrent = nPageNew;
            break;
        case "previous": //กดปุ่ม Previous
            nPageOld = $(".xWPage .active").text(); // Get เลขก่อนหน้า
            nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
            nPageCurrent = nPageNew;
            break;
        default:
            nPageCurrent = ptPage;
    }
    JSvCallPageAdjStkSubPdtDataTable(nPageCurrent);
}

//Functionality: Function Chack And Show Button Delete All
//Parameters: LocalStorage Data
//Creator: 10/07/2019 Krit(Copter)
//Return: -
//Return Type: -
function JSxShowButtonChoose() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
        $("#oliAdjStkSubBtnDeleteAll").addClass("disabled");
    } else {
        nNumOfArr = aArrayConvert[0].length;
        if (nNumOfArr > 1) {
            $("#oliAdjStkSubBtnDeleteAll").removeClass("disabled");
        } else {
            $("#oliAdjStkSubBtnDeleteAll").addClass("disabled");
        }
    }
}

//Functionality: Insert Text In Modal Delete
//Parameters: LocalStorage Data
//Creator: 15/05/2018 wasin
//Return: -
//Return Type: -
function JSxTextinModal() {
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
    } else {
        var tTextCode = "";
        for ($i = 0; $i < aArrayConvert[0].length; $i++) {
            tTextCode += aArrayConvert[0][$i].nCode;
            tTextCode += " , ";
        }
        //Disabled ปุ่ม Delete
        if (aArrayConvert[0].length > 1) {
            $(".xCNIconDel").addClass("xCNDisabled");
        } else {
            $(".xCNIconDel").removeClass("xCNDisabled");
        }

        $("#ospConfirmDelete").text("ท่านต้องการลบข้อมูลทั้งหมดหรือไม่ ?");
        $("#ohdConfirmIDDelete").val(tTextCode);
    }
}

//Functionality: Insert Text In Modal Delete
//Parameters: LocalStorage Data
//Creator: 25/02/2019 Napat(Jame)
//Return: -
//Return Type: -
function JSxAdjStkSubPdtTextinModal() {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if (aArrayConvert[0] == null || aArrayConvert[0] == "") {
        } else {
            var tTextSeq = "";
            var tTextPdt = "";
            var tTextDoc = "";
            var tTextPun = "";
            for ($i = 0; $i < aArrayConvert[0].length; $i++) {
                tTextSeq += aArrayConvert[0][$i].tSeq;
                tTextSeq += " , ";
                tTextPdt += aArrayConvert[0][$i].tPdt;
                tTextPdt += " , ";
                tTextDoc += aArrayConvert[0][$i].tDoc;
                tTextDoc += " , ";
                tTextPun += aArrayConvert[0][$i].tPun;
                tTextPun += " , ";
            }
            $("#ospConfirmDelete").text($("#oetTextComfirmDeleteMulti").val());
            $("#ohdConfirmSeqDelete").val(tTextSeq);
            $("#ohdConfirmPdtDelete").val(tTextPdt);
            $("#ohdConfirmPunDelete").val(tTextPun);
            $("#ohdConfirmDocDelete").val(tTextDoc);
        }
    } else {
        JCNxShowMsgSessionExpired();
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
            return "Dupilcate";
        }
    }
    return "None";
}

/**
 * Functionality : เปลี่ยนหน้า pagenation product table
 * Parameters : Event Click Pagination
 * Creator : 22/05/2019 Piya(Tiger)
 * Return : View
 * Return Type : View
 */
function JSvAdjStkSubPdtClickPage(ptPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $(".xWBtnNext").addClass("disabled");
                nPageOld = $(".xWPageAdjStkSubPdt .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case "previous": //กดปุ่ม Previous
                nPageOld = $(".xWPageAdjStkSubPdt .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JCNxOpenLoading();
        JSvAdjStkSubLoadPdtDataTableHtml(nPageCurrent);
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Functionality : Generate Code Subdistrict
//Parameters : Event Icon Click
//Creator : 07/06/2018 wasin
//Return : Data
//Return Type : String
function JStGenerateAdjStkSubCode() {
    var tTableName = "TCNTPdtTwxHD";
    JCNxOpenLoading();
    $.ajax({
        type: "POST",
        url: "generateCode",
        data: {tTableName: tTableName},
        cache: false,
        timeout: 0,
        success: function (tResult) {
            console.log(tResult);
            var tData = $.parseJSON(tResult);
            if (tData.rtCode == "1") {
                console.log(tData);
                $("#oetAdjStkSubAjhDocNo").val(tData.rtXthDocNo);
                $("#oetAdjStkSubAjhDocNo").addClass("xCNDisable");
                $(".xCNDisable").attr("readonly", true);
                //----------Hidden ปุ่ม Gen
                $(".xCNBtnGenCode").attr("disabled", true);
                $("#oetXthDocDate").focus();
                $("#oetXthDocDate").focus();

                JStCMNCheckDuplicateCodeMaster(
                        "oetAdjStkSubAjhDocNo",
                        "JSvCallPageAdjStkSubEdit",
                        "TCNTPdtTwxHD",
                        "FTXthDocNo"
                        );
            } else {
                $("#oetAdjStkSubAjhDocNo").val(tData.rtDesc);
            }
            JCNxCloseLoading();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// Advance Table
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Function : Get Html PDT มาแปะ ในหน้า Add
// Create : 04/04/2019 Krit(Copter)
function JSvAdjStkSubLoadPdtDataTableHtml(pnPage) {
    var nStaSession = JCNxFuncChkSessionExpired();
    if (typeof nStaSession !== "undefined" && nStaSession == 1) {
        
        JCNxOpenLoading();
        
        var tSearchAll = $('#oetAdjStkSubSearchPdtHTML').val();
        var tAjhDocNo, tAjhStaApv, tAjhStaDoc, nPageCurrent;
        if (JCNbAdjStkSubIsCreatePage()) {
            tAjhDocNo = "";
        } else {
            tAjhDocNo = $("#oetAdjStkSubAjhDocNo").val();
        }
        
        tAjhStaApv = $("#ohdAdjStkSubAjhStaApv").val();
        tAjhStaDoc = $("#ohdAdjStkSubAjhStaDoc").val();

        // เช็ค สินค้าใน table หน้านั้นๆ มีหรือไม่ ถ้าไม่มี nPage จะถูกลบ 1
        if ($("#odvTBodyAdjStkSubPdt .xWPdtItem").length == 0) {
            if (typeof pnPage !== 'undefined') {
                pnPage = pnPage - 1;
            }
        }

        nPageCurrent = ( (typeof pnPage === 'undefined') || (pnPage === "") || (pnPage <= 0) ) ? "1" : pnPage;

        $.ajax({
            type: "POST",
            url: "adjStkSubPdtAdvanceTableLoadData",
            data: {
                tSearchAll: tSearchAll,
                tAjhDocNo: tAjhDocNo,
                tAjhStaApv: tAjhStaApv,
                tAjhStaDoc: tAjhStaDoc,
                nPageCurrent: nPageCurrent
            },
            cache: false,
            Timeout: 0,
            success: function (tResult) {
                $("#odvAdjStkSubPdtTablePanal").html(tResult);
                // JSvAdjStkSubLoadVatTableHtml(); // Load Vat Table

                JCNxCloseLoading();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                JCNxResponseError(jqXHR, textStatus, errorThrown);
            }
        });
    } else {
        JCNxShowMsgSessionExpired();
    }
}

//Function : โหลด Html Vat มาแปะ ในหน้า Add
//Create : 04/04/2019 Krit(Copter)
/*function JSvAdjStkSubLoadVatTableHtml() {
  var nStaSession = JCNxFuncChkSessionExpired();
  if (typeof nStaSession !== "undefined" && nStaSession == 1) {
    var tXthDocNo = $("#oetAdjStkSubAjhDocNo").val();
    var tXthVATInOrEx = $("#ostXthVATInOrEx").val();

    $.ajax({
      type: "POST",
      url: "adjStkSubVatTableLoadData",
      data: {
        tXthDocNo: tXthDocNo,
        tXthVATInOrEx: tXthVATInOrEx
      },
      cache: false,
      Timeout: 0,
      success: function (tResult) {
        $("#odvVatPanal").html(tResult);
        //จำนวนรวมภาษี
        var nSumVatRate = 0;
        for(var i = 0;i<$(".xWPriceSumVateRate").length;i++){
            nSumVatRate += parseFloat($($(".xWPriceSumVateRate").get(i)).find("label").html().replace(",", ""));
        }
        if($(".xWPriceSumVateRate").length!=0){

            $("#olaSumXtdVat").html(accounting.formatMoney(nSumVatRate.toFixed(2),"","2"));
            $("#olaVatTotal").html(accounting.formatMoney(nSumVatRate.toFixed(2),"","2"));
        }else{
            $("#olaSumXtdVat").html("-");
            $("#olaVatTotal").html("-");
        }
        JSxAdjStkSubSetCalculateLastBillSetText();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        JCNxResponseError(jqXHR, textStatus, errorThrown);
      }
    });
  } else {
    JCNxShowMsgSessionExpired();
  }
}*/

//Function : ส่ง จำนวนเงิน ไปแปลเป็น ไทย , Set Text จำนวนยอดสุทธิท้ายบิล
//Create : 04/04/2019 Krit(Copter)
/*function JSxAdjStkSubSetCalculateLastBillSetText() {
  var nStaSession = JCNxFuncChkSessionExpired();
  if (typeof nStaSession !== "undefined" && nStaSession == 1) {
    tXthDocNo = $("#oetAdjStkSubAjhDocNo").val();
    tXthVATInOrEx = $("#ostXthVATInOrEx").val();
    $.ajax({
      type: "POST",
      url: "adjStkSubCalculateLastBill",
      data: {
        tXthDocNo: tXthDocNo,
        tXthVATInOrEx: tXthVATInOrEx
      },
      cache: false,
      Timeout: 0,
      success: function (tResult) {
        aResult = $.parseJSON(tResult);
        //จำนวนเงินเป็นภาษาไทย
        $("#othFCXthGrandText").html(aResult.tXphGndText);
        
        //ยอดรวมสุทธิ
        $("#othFCXthGrandB4Wht").text(aResult.FCXthTotal);

        JCNxCloseLoading();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        JCNxResponseError(jqXHR, textStatus, errorThrown);
      }
    });
  } else {
    JCNxShowMsgSessionExpired();
  }
}*/

function JSxOpenColumnFormSet() {
  $.ajax({
    type: "POST",
    url: "adjStkSubAdvanceTableShowColList",
    data: {},
    cache: false,
    Timeout: 0,
    success: function (tResult) {
      $("#odvShowOrderColumn").modal({ show: true });
      $("#odvOderDetailShowColumn").html(tResult);
      //JSCNAdjustTable();
    },
    error: function (jqXHR, textStatus, errorThrown) {
      JCNxResponseError(jqXHR, textStatus, errorThrown);
    }
  });
}

function JSxSaveColumnShow() {
    var aColShowSet = [];
    $(".ocbColStaShow:checked").each(function () {
        aColShowSet.push($(this).data("id"));
    });

    var aColShowAllList = [];
    $(".ocbColStaShow").each(function () {
        aColShowAllList.push($(this).data("id"));
    });

    var aColumnLabelName = [];
    $(".olbColumnLabelName").each(function () {
        aColumnLabelName.push($(this).text());
    });

    // alert(aColShowAllList);

    var nStaSetDef;
    if ($("#ocbSetToDef").is(":checked")) {
        nStaSetDef = 1;
    } else {
        nStaSetDef = 0;
    }
    // alert(aColShowSet);

    $.ajax({
        type: "POST",
        url: "adjStkSubAdvanceTableShowColSave",
        data: {
            aColShowSet: aColShowSet,
            nStaSetDef: nStaSetDef,
            aColShowAllList: aColShowAllList,
            aColumnLabelName: aColumnLabelName
        },
        cache: false,
        Timeout: 0,
        success: function (tResult) {
            $("#odvShowOrderColumn").modal("hide");
            $(".modal-backdrop").remove();
            // Function Gen Table Pdt ของ AdjStkSub
            JSvAdjStkSubLoadPdtDataTableHtml();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}

// ปรับ Value ใน Input หลัวจาก กรอก เสร็จ
function JSxAdjStkSubAdjInputFormat(ptInputID) {
    cVal = $("#" + ptInputID).val();
    cVal = accounting.toFixed(cVal, nOptDecimalShow);
    $("#" + ptInputID).val(cVal);
}

/**
 * Functionality : Is create page.
 * Parameters : -
 * Creator : 22/05/2019 piya(tiger)
 * Last Modified : -
 * Return : Status true is create page
 * Return Type : Boolean
 */
function JCNbAdjStkSubIsCreatePage(){
    try{
        var tAdjStkSubDocNo   = $('#oetAdjStkSubAjhDocNo').data('is-created');
        var bStatus = false;
        if(tAdjStkSubDocNo == ""){ // No have data
            bStatus = true;
        }
        return bStatus;
    }catch(err){
        console.log('JCNbAdjStkSubIsCreatePage Error: ', err);
    }
}

/**
 * Functionality : Is update page.
 * Parameters : -
 * Creator : 22/05/2019 piya(tiger)
 * Last Modified : -
 * Return : Status true is create page
 * Return Type : Boolean
 */
function JCNbAdjStkSubIsUpdatePage(){
    try{
        var tAdjStkSubDocNo = $('#oetAdjStkSubAjhDocNo').data('is-created');
        var bStatus = false;
        if(!tAdjStkSubDocNo == ""){ // Have data
            bStatus = true;
        }
        return bStatus;
    }catch(err){
        console.log('JCNbAdjStkSubIsUpdatePage Error: ', err);
    }
}


























