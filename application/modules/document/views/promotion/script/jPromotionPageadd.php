<script>
    $(document).ready(function() {

        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: false,
            immediateUpdates: false,
        });

        $('.xCNTimePicker').datetimepicker({
            format: 'HH:mm:ss'
        });

        $('#obtPmtDocDate').click(function() {
            event.preventDefault();
            $('#oetPromotionDocDate').datepicker('show');
        });

        $('#obtPmtDocTime').click(function() {
            event.preventDefault();
            $('#oetPromotionDocTime').datetimepicker('show');
        });

        $('#obtPmtDocDateFrom').click(function() {
            event.preventDefault();
            $('#oetPromotionPmhDStart').datepicker('show');
        });

        $('#obtPmtDocDateTo').click(function() {
            event.preventDefault();
            $('#oetPromotionPmhDStop').datepicker('show');
        });

        $('#obtPmtDocTimeFrom').click(function() {
            event.preventDefault();
            $('#oetPromotionPmhTStart').datetimepicker('show');
        });

        $('#obtPmtDocTimeTo').click(function() {
            event.preventDefault();
            $('#oetPromotionPmhTStop').datetimepicker('show');
        });

        $('.xWTooltipsBT').tooltip({
            'placement': 'bottom'
        });

        $('[data-toggle="tooltip"]').tooltip({
            'placement': 'top'
        });

        $('#ocbPromotionAutoGenCode').unbind().bind('change', function() {
            var bIsChecked = $('#ocbPromotionAutoGenCode').is(':checked');
            var oInputDocNo = $('#oetPromotionDocNo');
            if (bIsChecked) {
                $(oInputDocNo).attr('readonly', true);
                $(oInputDocNo).attr('disabled', true);
                $(oInputDocNo).val("");
                $(oInputDocNo).parents('.form-group').removeClass('has-error').find('em').hide();
            } else {
                $(oInputDocNo).removeAttr('readonly');
                $(oInputDocNo).removeAttr('disabled');
            }
        });

        if (bIsApvOrCancel && !bIsAddPage) {
            $('#obtPromotionApprove').hide();
            $('#obtPromotionCancel').hide();
            $('#odvBtnAddEdit .btn-group').hide();
            $('form .xCNApvOrCanCelDisabled').attr('disabled', true);
        } else {
            $('#odvBtnAddEdit .btn-group').show();
        }

        if(bIsCancel){
            $('form .xCNCanCelDisabled').attr('disabled', true);
        }else{
            $('#odvBtnAddEdit .btn-group').show();
        }

        if (!bIsAddPage) {
            JSxPromotionStep3GetPmtCBInTmp(1, false);
            JSxPromotionStep3GetPmtCGInTmp(1, false);
            JSxPromotionStep3GetPmtCBWithPmtCGInTmp(1, false);
        }
        JSxPromotionStep4GetCheckAndConfirmPage(false);

        $(document).on('keyup keypress', 'form input[type="text"], form input[type="time"], form input[type="number"]', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                return false;
            }
        });

        /*===== Begin Step Form Page Control ===========================================*/
        $('.xCNPromotionCircle').on('click', function(){
            var tTab = $(this).data('tab');
            $('.xCNPromotionNextStep').prop('disabled', false);
            $('.xCNPromotionBackStep').prop('disabled', false);

            switch(tTab){
                case "odvPromotionStep1" : {
                    $('.xCNPromotionBackStep').prop('disabled', true);
                    break;
                }
                case "odvPromotionStep2" : {
                    if(!JCNbPromotionStep1IsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล สร้างกลุ่ม/สินค้า';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        $('.xCNPromotionBackStep').prop('disabled', true);
                        return;   
                    }

                    JSxPromotionStep2GetPmtDtGroupNameInTmp(1, false, 1);
                    JSxPromotionStep2GetPmtDtGroupNameInTmp(1, false, 2);
                    break;
                }
                case "odvPromotionStep3" : {
                    if(!JCNbPromotionStep1IsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล สร้างกลุ่ม/สินค้า';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;   
                    }
                    if(!JCNbPromotionStep2IsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล กำหนดกลุ่ม ซื้อ-รับ';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;   
                    }

                    if(JSbPromotionConditionBuyIsRange()){ // เงื่อนไขการซื้อแบบช่วง
                        $('.xCNPromotionStep3TableGroupBuyContainer').hide();
                        $('.xCNPromotionStep3TableGroupBuyWithGroupGetContainer').show();
                        $('.xCNPromotionStep3TableGroupGetContainer').hide();
                    }
                    if(JSbPromotionConditionBuyIsNormal()){ // เงื่อนไขการซื้อแบบปกติ
                        $('.xCNPromotionStep3TableGroupBuyContainer').show();
                        $('.xCNPromotionStep3TableGroupBuyWithGroupGetContainer').hide();
                        $('.xCNPromotionStep3TableGroupGetContainer').show(); 
                    }
                    break;
                }
                case "odvPromotionStep4" : {
                    if(!JCNbPromotionStep1IsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล สร้างกลุ่ม/สินค้า';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;   
                    }
                    if(!JCNbPromotionStep2IsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล กำหนดกลุ่ม ซื้อ-รับ';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;   
                    }
                    if(!JCNbPromotionStep3AvgDisPercentIsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล % เฉลี่ยส่วนลด';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;     
                    }
                    if(!JCNbPromotionStep3CouponIsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล กำหนดเงื่อนไขกลุ่ม (เงื่อนไข - สิทธิประโยชน์คูปอง)';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;     
                    }
                    if(!JCNbPromotionStep3PointIsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล กำหนดเงื่อนไขกลุ่ม (เงื่อนไข - สิทธิประโยชน์แต้ม)';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;     
                    }

                    JSxPromotionStep4GetPdtPmtHDCstPriInTmp(1, false);
                    JSxPromotionStep4GetPdtPmtHDBchInTmp(1, false);
                    JSxPromotionStep4GetPdtPmtHDCstLevInTmp(1, false);
                    break;
                }
                case "odvPromotionStep5" : {
                    if(!JCNbPromotionStep1IsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล สร้างกลุ่ม/สินค้า';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;   
                    }
                    if(!JCNbPromotionStep2IsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล กำหนดกลุ่ม ซื้อ-รับ';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;   
                    }
                    if(!JCNbPromotionStep3AvgDisPercentIsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล % เฉลี่ยส่วนลด';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;     
                    }
                    if(!JCNbPromotionStep3CouponIsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล กำหนดเงื่อนไขกลุ่ม (เงื่อนไข - สิทธิประโยชน์คูปอง)';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;     
                    }
                    if(!JCNbPromotionStep3PointIsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล กำหนดเงื่อนไขกลุ่ม (เงื่อนไข - สิทธิประโยชน์แต้ม)';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;     
                    }

                    JSxPromotionStep4GetCheckAndConfirmPage(false);
                    $('.xCNPromotionNextStep').prop('disabled', true);
                    break;
                }
                default : {
                }
            }

            $('.xCNPromotionCircle').removeClass('active');
            $(this).addClass('active');
            $('a[href="#'+tTab+'"]').tab('show');
        });
        /*===== End Step Form Page Control =============================================*/

        /*===== Begin Step Form Page Control Btn =======================================*/
        // Next
        $('.xCNPromotionNextStep').unbind().bind('click', function(){

            var tStepNow = $('#odvPromotionLine .xCNPromotionCircle.active').data('step');
            // console.log(('tStepNow: ', tStepNow);

            if(tStepNow < "5"){ 
                $('.xCNPromotionCircle.xCNPromotionStep'+(tStepNow+1)).trigger('click'); 
            }
        });

        // Back
        $('.xCNPromotionBackStep').unbind().bind('click', function(){
            var tStepNow = $('#odvPromotionLine .xCNPromotionCircle.active').data('step');
            // console.log(('tStepNow: ', tStepNow);
            if(tStepNow > "1"){ 
                $('.xCNPromotionCircle.xCNPromotionStep'+(tStepNow-1)).trigger('click'); 
            }
        });
        /*===== End Step Form Page Control Btn =========================================*/

        /*===== Begin ocmPromotionPbyStaBuyCond(เงื่อนไขการซื้อ) Control ===================*/
        var tPbyStaBuyCondOldVal;
        $("button[data-id='ocmPromotionPbyStaBuyCond']").on('click', function(){
            tPbyStaBuyCondOldVal = $(this).parents('.bootstrap-select').find('#ocmPromotionPbyStaBuyCond').val();
        });

        $('#ocmPromotionPbyStaBuyCond').on('change', function(){
            if(!JCNbPromotionStep1PmtDtGroupNameTableIsEmpty()){
                var tWarningMessage = 'ล้างข้อมูลหลังจากเปลี่ยนเงื่อนไขการซื้อ ต้องการดำเนินการต่อหรือไม่';
                FSvCMNSetMsgWarningDialog(tWarningMessage, 'FSxPromotionAfterChangePbyStaBuyCond', '', true);
                $('#odvModalWanning .xWBtnCancel').on('click', function(event){
                    $('#ocmPromotionPbyStaBuyCond').val(tPbyStaBuyCondOldVal).selectpicker('refresh');
                });
            }
            JSxPromotionStep4GetCheckAndConfirmPage(false);
        });

        /**
         * Functionality : Action After Change PbyStaBuyCond
         * Parameters : -
         * Creator : 04/02/2020 Piya
         * Return : -
         * Return Type : -
         */
        window.FSxPromotionAfterChangePbyStaBuyCond = function(){
            // To Step 1
            $('.xCNPromotionCircle.xCNPromotionStep1').trigger('click');

            // Clear กลุ่มซื้อ,กลุ่มรับ Step2
            $('.xCNPromotionStep2GroupBuy .xCNPromotionStep2GroupNameType1Item .close').click();
            $('.xCNPromotionStep2GroupGet .xCNPromotionStep2GroupNameType1Item .close').click();
        }
        /*===== End ocmPromotionPbyStaBuyCond(เงื่อนไขการซื้อ) Control =====================*/

        /*===== Begin ocmPromotionPmhStaGetPdt(เงื่อนไขการเลือกสินค้า) Control ==============*/
        $('#ocmPromotionPmhStaGetPdt').on('change', function(){
            var bIsSelectedType3 = $(this).val() == "3"; // 1:ราคามากกว่า 2:ราคาน้อยกว่า 3:user เลือก
            if(bIsSelectedType3){
                $('#obtPromotionBrowseRole').attr('disabled', false);
                $('#oetPromotionRoleCode').attr('disabled', false);
                $('#oetPromotionRoleName').attr('disabled', false);
            }else{
                $('#obtPromotionBrowseRole').attr('disabled', true);
                $('#oetPromotionRoleCode').attr('disabled', true);
                $('#oetPromotionRoleName').attr('disabled', true);
                $('#oetPromotionRoleCode').val("");
                $('#oetPromotionRoleName').val("");
            }
        });
        /*===== End ocmPromotionPmhStaGetPdt(เงื่อนไขการเลือกสินค้า) Control ================*/

        /*===== Begin ocbPromotionPmhStaLimitGet(จำกัดจำนวนครั้ง) Control =================*/
        $('#ocbPromotionPmhStaLimitGet').on('change', function(){
            var bIsChecked = $(this).is(':checked');
            if(bIsChecked){
                $('#oetPromotionPmhLimitQty').attr('disabled', false);
                $('#ocmPromotionPmhStaLimitTime').attr('disabled', false);
                $('#ocmPromotionPmhStaChkLimit').attr('disabled', false);

                $("#ocmPromotionPmhStaLimitTime").selectpicker("refresh");
                $("#ocmPromotionPmhStaChkLimit").selectpicker("refresh");
            }else{
                $('#oetPromotionPmhLimitQty').attr('disabled', true);
                $('#oetPromotionPmhLimitQty').parents('.form-group').removeClass('has-error');
                $('#oetPromotionPmhLimitQty').parents('.form-group').find('em').empty();
                $('#ocmPromotionPmhStaLimitTime').attr('disabled', true);
                $('#ocmPromotionPmhStaChkLimit').attr('disabled', true);

                $("#ocmPromotionPmhStaLimitTime").selectpicker("refresh");
                $("#ocmPromotionPmhStaChkLimit").selectpicker("refresh");
            }
            JSxPromotionPmhSetStaLimitCst();
        });
        /*===== End ocbPromotionPmhStaLimitGet(จำกัดจำนวนครั้ง) Control ===================*/

        /*===== Begin ocbPromotionPmhStaChkCst(ตรวจสอบเงื่อนไขลูกค้า) Control ==============*/
        $('#ocbPromotionPmhStaChkCst').on('change', function(){
            var bIsChecked = $(this).is(':checked');

            if(bIsChecked){
                $('#oetPromotionSpmMemAgeLT').attr('disabled', false);
                $('#oetPromotionPmhCstDobPrev').attr('disabled', false);
                $('#oetPromotionPmhCstDobNext').attr('disabled', false);

                $('#ocmPromotionSpmStaLimitCst').attr('disabled', false);
                $('#ocmPromotionSpmStaChkCstDOB').attr('disabled', false);

                $("#ocmPromotionSpmStaLimitCst").selectpicker("refresh");
                $("#ocmPromotionSpmStaChkCstDOB").selectpicker("refresh");
            }else{
                $('#oetPromotionSpmMemAgeLT').attr('disabled', true);
                $('#oetPromotionSpmMemAgeLT').parents('.form-group').removeClass('has-error');
                $('#oetPromotionSpmMemAgeLT').parents('.form-group').find('em').empty();
                $('#oetPromotionPmhCstDobPrev').attr('disabled', true);
                $('#oetPromotionPmhCstDobPrev').parents('.form-group').removeClass('has-error');
                $('#oetPromotionPmhCstDobPrev').parents('.form-group').find('em').empty();
                $('#oetPromotionPmhCstDobNext').attr('disabled', true);
                $('#oetPromotionPmhCstDobNext').parents('.form-group').removeClass('has-error');
                $('#oetPromotionPmhCstDobNext').parents('.form-group').find('em').empty();

                $('#ocmPromotionSpmStaLimitCst').attr('disabled', true);
                $('#ocmPromotionSpmStaChkCstDOB').attr('disabled', true);

                $("#ocmPromotionSpmStaLimitCst").selectpicker("refresh");
                $("#ocmPromotionSpmStaChkCstDOB").selectpicker("refresh");
            }
        });
        /*===== End ocbPromotionPmhStaChkCst(ตรวจสอบเงื่อนไขลูกค้า) Control ================*/

        JSxPromotionPmhSetStaLimitCst();
    });

    /*===== Begin Event Browse =========================================================*/
    // เลือกผู้ใช้งานระบบ
    $("#obtPromotionBrowseUsr").click(function() {
        // option User
        window.oPromotionBrowseUsr = {
            Title: ['authen/user/user', 'tUSRTitle'],
            Table: {
                Master: 'TCNMUser',
                PK: 'FTUsrCode'
            },
            Join: {
                Table: ['TCNMUser_L', 'TCNTUsrGroup'],
                On: ['TCNMUser.FTUsrCode = TCNMUser_L.FTUsrCode AND TCNMUser_L.FNLngID = ' + nLangEdits, 'TCNTUsrGroup.FTUsrCode = TCNMUser.FTUsrCode']
            },
            Where: {
                Condition: [
                    function() {
                        return "";
                    }
                ]
            },
            GrideView: {
                ColumnPathLang: 'authen/user/user',
                ColumnKeyLang: ['tUSRCode', 'tUSRTBName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMUser.FTUsrCode', 'TCNMUser_L.FTUsrName'],
                DataColumnsFormat: ['', ''],
                Perpage: 5,
                OrderBy: ['TCNMUser.FTUsrCode'],
                SourceOrder: "ASC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetPromotionUsrCode", "TCNMUser.FTUsrCode"],
                Text: ["oetPromotionUsrName", "TCNMUser_L.FTUsrName"],
            },
            /* NextFunc: {
                FuncName: 'JSxPromotionCallbackUsr',
                ArgReturn: ['FTUsrCode', 'FTUsrName']
            }, */
            BrowseLev: 1,
            // DebugSQL : true
        }
        JCNxBrowseData('oPromotionBrowseUsr');
    });

    $("#obtPromotionBrowseRole").click(function() {
        // option User
        window.oPromotionBrowseRole = {
            Title: ['authen/role/role', 'tROLTitle'],
            Table: {
                Master: 'TCNMUsrRole',
                PK: 'FTRolCode',
                PKName: 'FTRolName'
            },
            Join: {
                Table: ['TCNMUsrRole_L'],
                On: ['TCNMUsrRole.FTRolCode = TCNMUsrRole_L.FTRolCode AND TCNMUsrRole_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [
                    function() {
                        return "";
                    }
                ]
            },
            GrideView: {
                ColumnPathLang: 'authen/role/role',
                ColumnKeyLang: ['tROLTBCode', 'tROLTBName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMUsrRole.FTRolCode', 'TCNMUsrRole_L.FTRolName'],
                DataColumnsFormat: ['', ''],
                Perpage: 5,
                OrderBy: ['TCNMUsrRole.FTRolCode'],
                SourceOrder: "ASC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetPromotionRoleCode", "TCNMUsrRole.FTRolCode"],
                Text: ["oetPromotionRoleName", "TCNMUsrRole_L.FTRolName"],
            },
            /* NextFunc: {
                FuncName: 'JSxPromotionCallbackUsr',
                ArgReturn: ['FTRolCode', 'FTRolName']
            }, */
            BrowseLev: 1,
            // DebugSQL : true
        }
        JCNxBrowseData('oPromotionBrowseRole');
    });
    /*===== End Event Browse ===========================================================*/

    var bUniquePromotionCode;
    $.validator.addMethod(
        "uniquePromotionCode",
        function(tValue, oElement, aParams) {
            var nStaSession = JCNxFuncChkSessionExpired();
            if (typeof nStaSession !== "undefined" && nStaSession == 1) {

                var tPromotionCode = tValue;
                $.ajax({
                    type: "POST",
                    url: "promotionUniqueValidate",
                    data: "tPromotionCode=" + tPromotionCode,
                    dataType: "JSON",
                    success: function(poResponse) {
                        bUniquePromotionCode = (poResponse.bStatus) ? false : true;
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // console.log('Custom validate uniquePromotionCode: ', jqXHR, textStatus, errorThrown);
                    },
                    async: false
                });
                return bUniquePromotionCode;

            } else {
                JCNxShowMsgSessionExpired();
            }

        },
        "Doc No. is Already Taken"
    );

    /**
     * Functionality : Validate Form
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxPromotionValidateForm() {
        var oTopUpVendingForm = $('#ofmPromotionForm').validate({
            focusInvalid: false,
            onclick: false,
            onfocusout: false,
            onkeyup: false,
            rules: {
                oetPromotionDocNo: {
                    required: true,
                    maxlength: 20,
                    uniquePromotionCode: bIsAddPage
                },
                oetPromotionDocDate: {
                    required: true
                },
                oetPromotionDocTime: {
                    required: true
                },
                oetPromotionMchName: {
                    required: true
                },
                oetPromotionShpName: {
                    required: true
                },
                oetPromotionAccountNameTo: {
                    required: true
                },
                oetPromotionRoleName: {
                    required: true
                },

                oetPromotionPmhLimitQty: {
                    required: true
                },
                oetPromotionSpmMemAgeLT: {
                    required: true
                },
                oetPromotionPmhCstDobPrev: {
                    required: true
                },
                oetPromotionPmhCstDobNext: {
                    required: true
                }
            },
            messages: {
                oetCreditNoteDocNo: {
                    "required": $('#oetPromotionDocNo').attr('data-validate-required')
                }
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
                $(element).closest('.form-group').addClass("has-error");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest('.form-group').removeClass("has-error");
            },
            submitHandler: function(form) {
                if(!JCNbPromotionStep1IsValid()){
                    var tWarningMessage = 'กรุณาตรวจสอบข้อมูล สร้างกลุ่ม/สินค้า';
                    FSvCMNSetMsgWarningDialog(tWarningMessage);
                    return;   
                }
                if(!JCNbPromotionStep2IsValid()){
                    var tWarningMessage = 'กรุณาตรวจสอบข้อมูล กำหนดกลุ่ม ซื้อ-รับ';
                    FSvCMNSetMsgWarningDialog(tWarningMessage);
                    return;   
                }
                if(!JCNbPromotionStep3AvgDisPercentIsValid()){
                    var tWarningMessage = 'กรุณาตรวจสอบข้อมูล % เฉลี่ยส่วนลด';
                    FSvCMNSetMsgWarningDialog(tWarningMessage);
                    return;     
                }
                if(!JCNbPromotionStep3CouponIsValid()){
                    var tWarningMessage = 'กรุณาตรวจสอบข้อมูล กำหนดเงื่อนไขกลุ่ม (เงื่อนไข - สิทธิประโยชน์คูปอง)';
                    FSvCMNSetMsgWarningDialog(tWarningMessage);
                    return;     
                }
                if(!JCNbPromotionStep3PointIsValid()){
                    var tWarningMessage = 'กรุณาตรวจสอบข้อมูล กำหนดเงื่อนไขกลุ่ม (เงื่อนไข - สิทธิประโยชน์แต้ม)';
                    FSvCMNSetMsgWarningDialog(tWarningMessage);
                    return;     
                }
                JSxPromotionSave();
            }
        });
    }

    /**
     * Functionality : Save Doc
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxPromotionSave() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var tBchCode = $('#oetPromotionBchCode').val();
            var tMerCode = $('#oetPromotionMchCode').val();
            var tShpCode = $('#oetPromotionShpCode').val();
            var tPosCode = $('#oetPromotionPosCode').val();
            var tWahCode = $('#oetPromotionWahCode').val();

            // JCNxOpenLoading();

            $.ajax({
                type: "POST",
                url: "<?php echo $tRoute; ?>",
                data: $("#ofmPromotionForm").serialize(),
                cache: false,
                timeout: 0,
                dataType: "JSON",
                success: function(oResult) {
                    switch (oResult.nStaCallBack) {
                        case "1": {
                            JSvPromotionCallPageEdit(oResult.tCodeReturn);
                            break;
                        }
                        case "2": {
                            JSvPromotionCallPageAdd();
                            break;
                        }
                        case "3": {
                            JSvPromotionCallPageList();
                            break;
                        }
                        default: {
                            JSvPromotionCallPageEdit(oResult.tCodeReturn);
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : Approve Doc
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSvPromotionApprove(pbIsConfirm) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            try {
                if (pbIsConfirm) {
                    $("#ohdPromotionStaApv").val(2); // Set status for processing approve
                    $("#odvPromotionPopupApv").modal("hide");

                    var tDocNo = $("#oetPromotionDocNo").val();

                    JCNxOpenLoading();

                    $.ajax({
                        type: "POST",
                        url: "promotionDocApprove",
                        data: {
                            tDocNo: tDocNo
                        },
                        cache: false,
                        timeout: 0,
                        success: function(oResult) {
                            // console.log(oResult);
                            try {
                                if (oResult.nStaEvent == "900") {
                                    FSvCMNSetMsgErrorDialog(oResult.tStaMessg);
                                    JCNxCloseLoading();
                                    return;
                                }
                            } catch (err) {}
                            JSvPromotionCallPageEdit(tDocNo);
                            // JCNxCloseLoading();
                            // JSoPromotionSubscribeMQ(); // เอกสารนี้ไม่ต้องรอข้อความตอบกลับ
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            JCNxResponseError(jqXHR, textStatus, errorThrown);
                            JCNxCloseLoading();
                        }
                    });
                } else {
                    if(!JCNbPromotionStep1IsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล สร้างกลุ่ม/สินค้า';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;   
                    }
                    if(!JCNbPromotionStep2IsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล กำหนดกลุ่ม ซื้อ-รับ';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;   
                    }
                    if(!JCNbPromotionStep3AvgDisPercentIsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล % เฉลี่ยส่วนลด';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;     
                    }
                    if(!JCNbPromotionStep3CouponIsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล กำหนดเงื่อนไขกลุ่ม (เงื่อนไข - สิทธิประโยชน์คูปอง)';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;     
                    }
                    if(!JCNbPromotionStep3PointIsValid()){
                        var tWarningMessage = 'กรุณาตรวจสอบข้อมูล กำหนดเงื่อนไขกลุ่ม (เงื่อนไข - สิทธิประโยชน์แต้ม)';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                        return;     
                    }
                    // console.log(("StaApvDoc Call Modal");
                    $("#odvPromotionPopupApv").modal("show");
                }
            } catch (err) {
                // console.log("JSvPromotionApprove Error: ", err);
            }

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : Cancel Doc
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSvPromotionCancel(pbIsConfirm) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var tDocNo = $("#oetPromotionDocNo").val();

            if (pbIsConfirm) {
                $.ajax({
                    type: "POST",
                    url: "promotionDocCancel",
                    data: {
                        tDocNo: tDocNo
                    },
                    cache: false,
                    timeout: 0,
                    success: function(tResult) {
                        $("#odvPromotionPopupCancel").modal("hide");

                        var aResult = $.parseJSON(tResult);
                        if (aResult.nSta == 1) {
                            JSvPromotionCallPageEdit(tDocNo);
                        } else {
                            JCNxCloseLoading();
                            var tMsgBody = aResult.tMsg;
                            FSvCMNSetMsgWarningDialog(tMsgBody);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        JCNxResponseError(jqXHR, textStatus, errorThrown);
                    }
                });
            } else {
                $("#odvPromotionPopupCancel").modal("show");
            }

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : SubscribeMQ
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSoPromotionSubscribeMQ() {
        // RabbitMQ
        /*===========================================================================*/
        // Document variable
        var tLangCode = $("#ohdLangEdit").val();
        var tUsrBchCode = $("#oetPromotionBchCode").val();
        var tUsrApv = $("#oetPromotionApvCodeUsrLogin").val();
        var tDocNo = $("#oetPromotionDocNo").val();
        var tPrefix = "RESTFWVD";
        var tStaApv = $("#ohdPromotionStaApv").val();
        var tStaDelMQ = $("#ohdPromotionStaDelMQ").val();
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
            host: "ws://" + oSTOMMQConfig.host + ":15674/ws",
            username: oSTOMMQConfig.user,
            password: oSTOMMQConfig.password,
            vHost: oSTOMMQConfig.vhost
        };

        // Update Status For Delete Qname Parameter
        var poUpdateStaDelQnameParams = {
            ptDocTableName: "TFNTBnkDplHD",
            ptDocFieldDocNo: "FTBdhDocNo",
            ptDocFieldStaApv: "FTXthStaPrcStk",
            ptDocFieldStaDelMQ: "FTXthStaDelMQ",
            ptDocStaDelMQ: tStaDelMQ,
            ptDocNo: tDocNo
        };

        // Callback Page Control(function)
        var poCallback = {
            tCallPageEdit: "JSvPromotionCallPageEdit",
            tCallPageList: "JSvPromotionCallPageList"
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

    /**
     * Functionality : ตรวจสอบเงื่อนไขการซื้อ ว่าเป็นประเภทช่วงหรือไม่
     * 3:ตามช่วงจำนวน, 4:ตามช่วงมูลค่า, 5:ตามช่วงเวลา
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSbPromotionConditionBuyIsRange() {
        var tPbyStaBuyCond = $('#ocmPromotionPbyStaBuyCond').val();
        var aConditionBuyInRage = ["3","4","5","6"];    
        var bStatus = false;

        if(aConditionBuyInRage.includes(tPbyStaBuyCond)){
            bStatus = true;
        }

        return bStatus;
    }

    /**
     * Functionality : ตรวจสอบเงื่อนไขการซื้อ ว่าเป็นประเภทปกติหรือไม่
     * 1:ครบจำนวน, 2:ครบมูลค่า
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSbPromotionConditionBuyIsNormal() {
        var tPbyStaBuyCond = $('#ocmPromotionPbyStaBuyCond').val();
        var aConditionBuyInRage = ["1","2"];    
        var bStatus = false;

        if(aConditionBuyInRage.includes(tPbyStaBuyCond)){
            bStatus = true;
        }

        return bStatus;
    }


   //กรณีเลือกจำกัดจำนวน ต่อสมาชิก ให้ คิดต่อสมาชิกด้วย By พี่เอ็มแจ้ง
   //Date : 2021-04-01
   //Dev : Nale 
    $('#ocmPromotionPmhStaChkLimit').change(function(){
        JSxPromotionPmhSetStaLimitCst();
    });
    /**
     * Functionality : กรณีเลือกจำกัดจำนวน ต่อสมาชิก ให้ คิดต่อสมาชิกด้วย By พี่เอ็มแจ้ง
     * Parameters : -
     * Creator : 01/04/2021 nale
     * Return : -
     * Return Type : -
     */
    function JSxPromotionPmhSetStaLimitCst(){
        var nPromotionPmhStaChkLimit = $("#ocmPromotionPmhStaChkLimit").val();
        // alert(nPromotionPmhStaChkLimit);
          if(nPromotionPmhStaChkLimit==3 && $('#ocbPromotionPmhStaLimitGet').prop('checked')==true){
              $('#ocmPromotionPmhStaLimitCst').val(2).change();
              $('#ocmPromotionPmhStaLimitCst option:not(:selected)').attr('disabled',true);
          }else{
            $('#ocmPromotionPmhStaLimitCst option:not(:selected)').attr('disabled',false);
            $('#ocmPromotionPmhStaLimitCst').val(1).change();
          }
          $("#ocmPromotionPmhStaLimitCst").selectpicker("refresh");
    }
</script>