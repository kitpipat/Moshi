<script type="text/javascript">
    $('.selection-2').selectpicker();


    $(document).ready(function () {
        if(JSbRcvIsCreatePage()){
            // Rcv Code
            $("#oetRcvCode").attr("disabled", true);
            $('#ocbReciveAutoGenCode').change(function(){
                if($('#ocbReciveAutoGenCode').is(':checked')) {
                    $('#oetRcvCode').val('');
                    $("#oetRcvCode").attr("disabled", true);
                    $('#odvReciveCodeForm').removeClass('has-error');
                    $('#odvReciveCodeForm em').remove();
                }else{
                    $("#oetRcvCode").attr("disabled", false);
                }
            });
            JSxRcvVisibleComponent('#odvReciveAutoGenCode', true);
        }

        if(JSbRcvIsUpdatePage()){
            // Sale Person Code
            $("#oetRcvCode").attr("readonly", true);
            $('#odvReciveAutoGenCode input').attr('disabled', true);
            JSxRcvVisibleComponent('#odvReciveAutoGenCode', false);    
        }

        $('#oetRcvCode').blur(function(){
            JSxCheckRcvCodeDupInDB();
        });

    });

    //Functionality: Event Check Sale Person Duplicate
    //Parameters: Event Blur Input Sale Person Code
    //Creator: 25/03/2019 wasin (Yoshi)
    //Return: -
    //ReturnType: -
    function JSxCheckRcvCodeDupInDB(){
        if(!$('#ocbReciveAutoGenCode').is(':checked')){
            $.ajax({
                type: "POST",
                url: "CheckInputGenCode",
                data: { 
                    tTableName: "TFNMRcv",
                    tFieldName: "FTRcvCode",
                    tCode: $("#oetRcvCode").val()
                },
                async : false,
                cache: false,
                timeout: 0,
                success: function(tResult){
                    var aResult = JSON.parse(tResult);
                    $("#ohdCheckDuplicateRcvCode").val(aResult["rtCode"]);
                    JSxRcvSetValidEventBlur();
                    $('#ofmAddRecive').submit();
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
    function JSxRcvSetValidEventBlur(){
        $('#ofmAddRecive').validate().destroy();

        // Set Validate Dublicate Code
        $.validator.addMethod('dublicateCode', function(value, element) {
            if($("#ohdCheckDuplicateRcvCode").val() == 1){
                return false;
            }else{
                return true;
            }
        },'');

        // From Summit Validate
        $('#ofmAddRecive').validate({
            rules: {
                oetRcvCode : {
                    "required" :{
                        // ตรวจสอบเงื่อนไข validate
                        depends: function(oElement) {
                            if($('#ocbReciveAutoGenCode').is(':checked')){
                                return false;
                            }else{
                                return true;
                            }
                        }
                    },
                    "dublicateCode" :{}
                },
                
                oetRcvName:     {"required" :{}},
            },
            messages: {
                oetRcvCode : {
                    "required"      : $('#oetRcvCode').attr('data-validate-required'),
                    "dublicateCode" : $('#oetRcvCode').attr('data-validate-dublicateCode')
                },
                oetRcvName : {
                    "required"      : $('#oetRcvName').attr('data-validate-required'),
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

    // Create By Witsarut 27/11/2019
    // Get Data FTRcvCode
    function JSxRcvSpcGetContent(){
        var tRoutepage = '<?=$tRoute?>';
        if(tRoutepage == 'reciveEventAdd'){
            return;
        }else{
            var ptRcvCode = '<?php echo $tRcvCode;?>'
            // Check Login Expried
            var nStaSession = JCNxFuncChkSessionExpired();

            // if have session
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                $("#odvRcvSpcContentInfoDT").attr("class","tab-pane fade out");
                $.ajax({
                    type    : "POST",
                    url     : "recivespc/0/0",
                    data  : {
                        tRcvCode : ptRcvCode
                    },
                    cache    : false,
                    timeout : 0,
                    success : function(tResult){
                        $('#odvRcvSpcData').html(tResult);
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