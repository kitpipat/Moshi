<script type="text/javascript">
    $(document).ready(function(){
        if(JSbVoucherIsCreatePage()){
            // Department Code
            $("#oetVotCode").attr("disabled", true);
            $('#ocbVoucherAutoGenCode').change(function(){
                if($('#ocbVoucherAutoGenCode').is(':checked')) {
                    $('#oetVotCode').val('');
                    $("#oetVotCode").attr("disabled", true);
                    $('#odvVoucherCodeForm').removeClass('has-error');
                    $('#odvVoucherCodeForm em').remove();
                }else{
                    $("#oetVotCode").attr("disabled", false);
                }
            });
            JSxVoucherVisibleComponent('#odvVoucherAutoGenCode', true);
        }
        
        if(JSbVoucherIsUpdatePage()){
            // Department Code
            $("#oetVotCode").attr("readonly", true);
            $('#odvVoucherAutoGenCode input').attr('disabled', true);
            JSxVoucherVisibleComponent('#odvVoucherAutoGenCode', false);    
        }
    });

    $('#oetVotCode').blur(function(){
        JSxCheckDepartmentCodeDupInDB();
    });

    //Functionality : Event Check Voucher
    //Parameters : Event Blur Input Voucher Code
    //Creator : 25/03/2019 wasin (Yoshi)
    //Return : -
    //Return Type : -
    function JSxCheckDepartmentCodeDupInDB(){
        if(!$('#ocbVoucherAutoGenCode').is(':checked')){
            $.ajax({
                type: "POST",
                url: "CheckInputGenCode",
                data: { 
                    tTableName: "TFNMVoucherType",
                    tFieldName: "FTVotCode",
                    tCode: $("#oetVotCode").val()
                },
                cache: false,
                timeout: 0,
                success: function(tResult){
                // alert(tResult);
                    var aResult = JSON.parse(tResult);
                    $("#ohdCheckDuplicatePdtCode").val(aResult["rtCode"]);
                    // Set Validate Dublicate Code
                $.validator.addMethod('dublicateCode', function(value, element) {
                    if($("#ohdCheckDuplicatePdtCode").val() == 1){
                        return false;
                    }else{
                        return true;
                    }
                },'');
                // From Summit Validate
                $('#ofmAddVouchertype').validate({
                    rules: {
                        oetVotCode : {
                            "required" :{
                                // ตรวจสอบเงื่อนไข validate
                                depends: function(oElement) {
                                if($('#ocbVoucherAutoGenCode').is(':checked')){
                                    return false;
                                }else{
                                    return true;
                                }
                                }
                            },
                            "dublicateCode" :{}
                        },
                        oetVotName:    {"required" :{}},
                    },
                    messages: {
                        oetVotCode : {
                            "required"      : $('#oetVotCode').attr('data-validate-required'),
                            "dublicateCode" : $('#oetVotCode').attr('data-validate-dublicateCode')
                        },
                        oetVotName : {
                            "required"      : $('#oetVotName').attr('data-validate-required'),
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
                        $( element ).closest('.form-group').addClass( "has-error" ).removeClass( "has-success" );
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        $( element ).closest('.form-group').addClass( "has-success" ).removeClass( "has-error" );
                    },
                    submitHandler: function(form){}
                });
                
                // Submit From
                $('#ofmAddVouchertype').submit();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        }    
    }
</script>