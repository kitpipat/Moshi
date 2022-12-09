<script>

    $(document).ready(function(){

        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });

        $('.xCNDaterange').datetimepicker({
            format: 'HH:mm:ss'
        });

        if(!bIsApvOrCancel){
            $('#otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionCBPbyMinValue, #otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionCBPbyMaxValue, #otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionCBPbyMinSetPri').unbind().bind('change keyup', function(event){                
                if(event.keyCode == 13) {
                    JSxPromotionStep3PmtCBRangeDataTableEditInline(this);
                }
                if(event.type == "change"){
                    JSxPromotionStep3PmtCBRangeDataTableEditInline(this);
                }
            });

            $('#otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionPgtPerAvgDisCBWithCG').unbind().bind('change keyup', function(event){                
                if(event.keyCode == 13) {
                    JSxPromotionStep3PmtCBRangeDataTableEditInline(this);
                    JSxPromotionStep3PmtCGRangeDataTableEditInline(this);
                }
                if(event.type == "change"){
                    JSxPromotionStep3PmtCBRangeDataTableEditInline(this);
                    JSxPromotionStep3PmtCGRangeDataTableEditInline(this);
                }
            });

            $("#otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionStep3PbyMinTimeHr, #otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionStep3PbyMinTimeMin").on("change", function (e) {
                JSxPromotionStep3PmtCBRangeDataTableEditInline(this);
            });

            $("#otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionStep3PbyMaxTimeHr, #otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionStep3PbyMaxTimeMin").on("change", function (e) {
                JSxPromotionStep3PmtCBRangeDataTableEditInline(this);
            });

            $('#otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionStep3PgtGetvalue, #otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionPgtGetQty').unbind().bind('change keyup', function(event){                
                if(event.keyCode == 13) {
                    JSxPromotionStep3PmtCGRangeDataTableEditInline(this);
                }
                if(event.type == "change"){
                    JSxPromotionStep3PmtCGRangeDataTableEditInline(this);
                }
            });

            $('#otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionStep3PriceGroupCode').unbind().bind('change', function(event){                
                JSxPromotionStep3PmtCGRangeDataTableEditInline(this);
            });

            $('#otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionStep3CGPgtStaGetType').unbind().bind('change', function(){
                var bIsPgtStaGetTypeOfFree = $(this).val() == "5";

                if(bIsPgtStaGetTypeOfFree){ // เป็นของแถม Default 1
                    $(this).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionPgtGetQty').val("1");
                }else{ // ไม่ใช่ของแถม Default 0
                    $(this).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionPgtGetQty').val("0");
                }

                $(this).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionStep3PgtGetvalue').val("0");
                $(this).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionStep3PriceGroupName').val("");
                $(this).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionStep3PriceGroupCode').val("");

                JSxPromotionStep3PmtCGRangeDataTableEditInline(this, function(){
                    $nCurrentPage = $('.xCNPromotionPmtBrandDtPage').find('.btn.xCNBTNNumPagenation.active').text();
                    JSxPromotionStep3GetPmtCBWithPmtCGInTmp($nCurrentPage, false);
                });
            });
        }

        if(bIsApvOrCancel){
            $('#otbPromotionStep3PmtCBWithPmtCGTable .xCNApvOrCanCelDisabledPmtCBWithPmtCG').attr('disabled', true);
            $('#otbPromotionStep3PmtCBWithPmtCGTable .xCNIconDel').addClass('xCNDocDisabled');
            $('#otbPromotionStep3PmtCBWithPmtCGTable .xCNIconDel').removeAttr('onclick', true);
            $('#otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionStep3RangeAddItemRowBtn').hide();
        }else{
            $('#otbPromotionStep3PmtCBWithPmtCGTable .xCNIconDel').removeClass('xCNDocDisabled');
            $('#otbPromotionStep3PmtCBWithPmtCGTable .xCNIconDel').attr('onclick', 'JSxPromotionStep3PmtCBRangeDataTableDeleteBySeq(this)');

            JSxPromotionStep3LockIsOneRow();

            var tPbyStaBuyCond = $('#ocmPromotionPbyStaBuyCond').val();
            if(["3","4"].includes(tPbyStaBuyCond)){
                JSbPromotionStep3LockPbyMinValue();
            }
        }

        $('#otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionStep3RangeAddItemRowBtn').on('click', function(){
            var tGroupName = $(this).data('grpname');
            JSvPromotionStep3InsertPmtCBAndPmtCGToTemp(tGroupName, true);
            /* var oTrPrev = $(this).parents('tr').prev();
            var oTemplate = $(oTrPrev).clone();
            $(oTemplate).insertAfter(oTrPrev); */
        });

        /* document.querySelectorAll('.xCNPromotionStep3TimeContainer input[type=number]')
        .forEach(e => e.oninput = () => {
            // Always 2 digits
            if (e.value.length >= 2) e.value = e.value.slice(0, 2);
            // 0 on the left (doesn't work on FF)
            if (e.value.length === 1) e.value = '0' + e.value;
            // Avoiding letters on FF
            if (!e.value) e.value = '00';
        }); */

    });

    /**
     * Functionality : เรียกหน้าของรายการ PmtCB in Temp
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : Table List
     * Return Type : View
     */
    function JSvPromotionStep3PmtCBWithPmtCGDataTableClickPage(ptPage) {
        var nPageCurrent = "";
        switch (ptPage) {
            case "next": //กดปุ่ม Next
                $(".xCNPromotionPmtCBPage .xWBtnNext").addClass("disabled");
                nPageOld = $(".xCNPromotionPmtCBPage .xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) + 1; // +1 จำนวน
                nPageCurrent = nPageNew;
                break;
            case "previous": //กดปุ่ม Previous
                nPageOld = $(".xCNPromotionPmtCBPage .xWPage .active").text(); // Get เลขก่อนหน้า
                nPageNew = parseInt(nPageOld, 10) - 1; // -1 จำนวน
                nPageCurrent = nPageNew;
                break;
            default:
                nPageCurrent = ptPage;
        }
        JSxPromotionStep3GetPmtCBWithPmtCGInTmp(nPageCurrent, true);
    }

/**
     * Functionality : Delete PmtCB(Range) in Temp by SeqNo
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxPromotionStep3PmtCBRangeDataTableDeleteBySeq(poElm) {
        JSxPromotionStep3LockIsOneRow();
        var tPbyStaBuyCond = $('#ocmPromotionPbyStaBuyCond').val();
        if(["3","4"].includes(tPbyStaBuyCond)){
            JSbPromotionStep3LockPbyMinValue();
        }
        
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            JCNxOpenLoading();

            var nCbSeqNo = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').data('cb-seq-no');
            var nCgSeqNo = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').data('cg-seq-no');

            $.ajax({
                type: "POST",
                url: "promotionStep3DeletePmtCBAndPmtCGInTmpBySeq",
                data: {
                    tCbSeqNo: nCbSeqNo,
                    tCgSeqNo: nCgSeqNo
                },
                cache: false,
                timeout: 0,
                success: function(oRes) {
                    if(oRes.nStaEvent == "1"){
                        if(JSbPromotionConditionBuyIsRange()){ // เงื่อนไขการซื้อแบบช่วง
                            JSxPromotionStep3GetPmtCBWithPmtCGInTmp(1, true);
                            JSvPromotionStep5UpdatePmtCBStaCalSumInTemp(false, false);
                            JSvPromotionStep5UpdatePmtCGPgtStaGetEffectInTemp(false, false);
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                    JCNxCloseLoading();
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : Update PmtCB(Range) in Temp by SeqNo
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxPromotionStep3PmtCBRangeDataTableEditInline(poElm){
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var nSeqNo = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').data('cb-seq-no');
            var tPbyMinValue = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionCBPbyMinValue').val();
            var tPbyMaxValue = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionCBPbyMaxValue').val();
            var tPbyMinSetPri = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionCBPbyMinSetPri').val();
            var tPgtPerAvgDisCBWithCG = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionPgtPerAvgDisCBWithCG').val();
            var tFieldName = $(poElm).data('field-name');
            var tFormatType = $(poElm).data('format-type');
            
            /*===== Begin Time Convert =================================================*/
                // var tPbyMinTimeHr = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionStep3PbyMinTimeHr').val();
                // var tPbyMinTimeMin = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionStep3PbyMinTimeMin').val();
                // var tPbyPbyMaxTimeHr = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionStep3PbyMaxTimeHr').val();
                // var tPbyPbyMaxTimeMin = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionStep3PbyMaxTimeMin').val();

                // if(tPbyMinTimeHr == ""){tPbyMinTimeHr = "00";}
                // if(tPbyMinTimeMin == ""){tPbyMinTimeMin = "00";}
                // if(tPbyPbyMaxTimeHr == ""){tPbyPbyMaxTimeHr = "00";}
                // if(tPbyPbyMaxTimeMin == ""){tPbyPbyMaxTimeMin = "00";}
                
                // if(parseInt(tPbyMinTimeHr) < 10 && tPbyMinTimeHr.length == 1){
                //     tPbyMinTimeHr = '0' + String(tPbyMinTimeHr);    
                // }
                // if(parseInt(tPbyMinTimeMin) < 10 && tPbyMinTimeMin.length == 1){
                //     tPbyMinTimeMin = '0' + String(tPbyMinTimeMin);
                // }

                // if(parseInt(tPbyPbyMaxTimeHr) < 10 && tPbyPbyMaxTimeHr.length == 1){
                //     tPbyPbyMaxTimeHr = '0' + String(tPbyPbyMaxTimeHr);
                // }
                // if(parseInt(tPbyPbyMaxTimeMin) < 10 && tPbyPbyMaxTimeMin.length == 1){
                //     tPbyPbyMaxTimeMin = '0' + String(tPbyPbyMaxTimeMin);
                // }

                // var tPbyMinTime = tPbyMinTimeHr + ':' + tPbyMinTimeMin;
                // var tPbyPbyMaxTime = tPbyPbyMaxTimeHr + ':' + tPbyPbyMaxTimeMin;
            /*===== End Time Convert ===================================================*/

            var tPbyMinTime = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionStep3PbyMinTime').val();
            var tPbyPbyMaxTime = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionStep3PbyMaxTime').val();
            console.log("tPbyMinTime: ", tPbyMinTime);
            console.log("tPbyPbyMaxTime: ", tPbyPbyMaxTime);
            var tBchCode = $('#oetDepositBchCode').val();
            var tPbyStaBuyCond = $('#ocmPromotionPbyStaBuyCond').val();

            $.ajax({
                type: "POST",
                url: "promotionStep3UpdatePmtCBInTmp",
                data: {
                    tStaBuyIsRange: "1",
                    tPbyStaBuyCond: tPbyStaBuyCond, // 1:ครบจำนวน 2:ครบมูลค่า 3:ตามช่วงจำนวน 4:ตามช่วงมูลค่า 5:ตามช่วงเวลา 6:ตามช่วงเวลา ครบจำนวน 7:ตามช่วงเวลา ครบมูลค่า
                    tPbyMinValue: tPbyMinValue,
                    tPbyMaxValue: tPbyMaxValue,
                    tPbyMinSetPri: tPbyMinSetPri,
                    tPgtPerAvgDisCB: tPgtPerAvgDisCBWithCG,
                    tPbyMinTime: (tPbyMinTime == '')?'00:00':tPbyMinTime,
                    tPbyPbyMaxTime: (tPbyPbyMaxTime == '')?'59:00':tPbyPbyMaxTime,
                    nSeqNo: nSeqNo,
                    tFieldName: (tFieldName == "FCPgtPerAvgDis")?"FCPbyPerAvgDis":tFieldName,
                    tFormatType: tFormatType,
                    tBchCode: tBchCode,
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    if(tFormatType == "D"){
                        // var tPbyMinTime = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionStep3PbyMinTime').val();
                        // var tPbyPbyMaxTime = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionStep3PbyMaxTime').val();
                        // $(poElm).parents(".xCNPromotionStep3PmtCBWithPmtCGRow")
                        // .find(".xCNPromotionStep3PbyMinTimeHr").val(tResult.tValue.timeForm.tHr);
                        // $(poElm).parents(".xCNPromotionStep3PmtCBWithPmtCGRow")
                        // .find(".xCNPromotionStep3PbyMinTimeMin").val(tResult.tValue.timeForm.tMin);
                        // $(poElm).parents(".xCNPromotionStep3PmtCBWithPmtCGRow")
                        // .find(".xCNPromotionStep3PbyMaxTimeHr").val(tResult.tValue.timeTo.tHr);
                        // $(poElm).parents(".xCNPromotionStep3PmtCBWithPmtCGRow")
                        // .find(".xCNPromotionStep3PbyMaxTimeMin").val(tResult.tValue.timeTo.tMin);
                    }else{
                        if(tFieldName != "FCPgtPerAvgDis") {
                            $(poElm).val(tResult.tValue);
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : Update PmtCG(Range) in Temp by SeqNo
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxPromotionStep3PmtCGRangeDataTableEditInline(poElm, callback){
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {
            callback = (typeof callback !== 'undefined')?callback: function(){};

            var nSeqNo = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').data('cg-seq-no');
            var tPgtStaGetType = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('select.xCNPromotionStep3CGPgtStaGetType').val();
            var tPgtGetvalue = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionStep3PgtGetvalue').val();
            var tPgtGetQty = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionPgtGetQty').val();
            var tPgtPerAvgDisCBWithCG = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionPgtPerAvgDisCBWithCG').val();
            var tPriceGroupCode = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionStep3PriceGroupCode').val();
            var tPriceGroupName = $(poElm).parents('.xCNPromotionStep3PmtCBWithPmtCGRow').find('.xCNPromotionStep3PriceGroupName').val();
            var tFieldName = $(poElm).data('field-name');
            var tFormatType = $(poElm).data('format-type');
            var tBchCode = $('#oetDepositBchCode').val();
            
            $.ajax({
                type: "POST",
                url: "promotionStep3UpdatePmtCGInTmp",
                data: {
                    tStaBuyIsRange: "1",
                    tPgtStaGetType: tPgtStaGetType,
                    tPgtGetvalue: tPgtGetvalue,
                    tPgtGetQty: tPgtGetQty,
                    tPgtPerAvgDisCG: tPgtPerAvgDisCBWithCG,
                    tPriceGroupCode: tPriceGroupCode,
                    tPriceGroupName: tPriceGroupName,
                    nSeqNo: nSeqNo,
                    tFieldName: tFieldName,
                    tFormatType: tFormatType,
                    tBchCode: tBchCode,
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    $(poElm).val(tResult.tValue);
                    callback();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : Lock Table Row Have One
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxPromotionStep3LockIsOneRow(){
        var oAllRow = $("#otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionStep3PmtCBWithPmtCGRow");
        var aGrpName = [];
        $.each(oAllRow, function(nIndex, oValue){
            var tGrpNamePoint = "";
            var tGrpName = $(this).data("grp-name");
            if(tGrpName != tGrpNamePoint){
                tGrpNamePoint = tGrpName;
                aGrpName.push(tGrpName);
            }
        });

        $.each(aGrpName, function(nIndex, tValue){
            var tName = tValue.replace(" ", "");
            var nRowLength = $("#otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionStep3PmtCBWithPmtCGRow." + tName).length;
            if(nRowLength == 1){
                $('#otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionStep3PmtCBWithPmtCGRow.' + tName + ' .xCNIconDel').addClass('xCNDocDisabled');
                $('#otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionStep3PmtCBWithPmtCGRow.' + tName + ' .xCNIconDel').removeAttr('onclick', true);
            }else{
                $('#otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionStep3PmtCBWithPmtCGRow.' + tName + ' .xCNIconDel').removeClass('xCNDocDisabled');
                $('#otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionStep3PmtCBWithPmtCGRow.' + tName + ' .xCNIconDel').attr('onclick', 'JSxPromotionStep3PmtCBRangeDataTableDeleteBySeq(this)');
            }
        });
    }

    /**
     * Functionality : Lock PbyMinValue Input
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSbPromotionStep3LockPbyMinValue(){
        var oAllRow = $("#otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionStep3PmtCBWithPmtCGRow");
        var aGrpName = [];
        $.each(oAllRow, function(nIndex, oValue){
            var tGrpNamePoint = "";
            var tGrpName = $(this).data("grp-name");
            if(tGrpName != tGrpNamePoint){
                tGrpNamePoint = tGrpName;
                aGrpName.push(tGrpName);
            }
        });

        $.each(aGrpName, function(nIndex, tValue){
            var tName = tValue.replace(" ", "");
            var oRow = $("#otbPromotionStep3PmtCBWithPmtCGTable .xCNPromotionStep3PmtCBWithPmtCGRow." + tName);
            var nRowLength = $(oRow).length;
            $.each(oRow, function(nIndex, oValue){
                if(nIndex == 0){
                }else{
                    $(this).find(".xCNPromotionCBPbyMinValue").attr("readonly", true);
                }
            });
        });
        
    }
</script>