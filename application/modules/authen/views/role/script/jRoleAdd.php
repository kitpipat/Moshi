<script type="text/javascript">
    $(document).ready(function () {
        $('.selectpicker').selectpicker('refresh');

        // ============== Menu List Role =================
        $('#otbModuleMenuRole .xCNMenuGrpModule').unbind().one().click(function(){
            var nMenuKey    = $(this).data('gmc');
            if($('#otbModuleMenuRole .xCNDataRole[data-gmc='+nMenuKey+']').hasClass('hidden')){
                $('#otbModuleMenuRole .xCNDataRole[data-gmc='+nMenuKey+']').removeClass('hidden').hide().slideDown(500);
                $('#otbModuleMenuRole .xCNPlus[data-gmc='+nMenuKey+']').removeClass('fa fa-plus');
                $('#otbModuleMenuRole .xCNPlus[data-gmc='+nMenuKey+']').addClass('fa fa-minus');
            }else{
                $('#otbModuleMenuRole .xCNDataRole[data-gmc='+nMenuKey+']').slideUp(100,function(){
                    $(this).addClass('hidden');
                    $('#otbModuleMenuRole .xCNPlus[data-gmc='+nMenuKey+']').removeClass('fa fa-minus');
                    $('#otbModuleMenuRole .xCNPlus[data-gmc='+nMenuKey+']').addClass('fa fa-plus');
                });
            }
        });

        // ============== report =================
        $('#otbModuleMenuRole .xCNMenuRptModule').unbind().one().click(function(){
            var nRptKey    = $(this).data('rmc');
            if($('#otbModuleMenuRole .xCNDataReport[data-rmc='+nRptKey+']').hasClass('hidden')){
                $('#otbModuleMenuRole .xCNDataReport[data-rmc='+nRptKey+']').removeClass('hidden').hide().slideDown(500);
                $('#otbModuleMenuRole .xCNPlus[data-rmc='+nRptKey+']').removeClass('fa fa-plus');
                $('#otbModuleMenuRole .xCNPlus[data-rmc='+nRptKey+']').addClass('fa fa-minus');
            }else{
                $('#otbModuleMenuRole .xCNDataReport[data-rmc='+nRptKey+']').slideUp(100,function(){
                $(this).addClass('hidden');

                $('#otbModuleMenuRole .xCNPlus[data-rmc='+nRptKey+']').removeClass('fa fa-minus');
                $('#otbModuleMenuRole .xCNPlus[data-rmc='+nRptKey+']').addClass('fa fa-plus');
                });
            }
        });

        //========== Check All Role =============
        $('#otbModuleMenuRole .xWOcbCheckAll').unbind().one().click(function(){
            var tMunuModuleCode = $(this).parents('.xWHeardRoleAll').data('gmc');
            $("#otbModuleMenuRole .xCNDataRole[data-gmc="+tMunuModuleCode+"]").find('.xCNInputRole').prop('checked',false);
            if (this.checked) {
                $("#otbModuleMenuRole .xCNDataRole[data-gmc="+tMunuModuleCode+"]").find('.xCNIsUseChkBox').find('.xCNInputRole').prop('checked',true);
            } else {
                $("#otbModuleMenuRole .xCNDataRole[data-gmc="+tMunuModuleCode+"]").find('.xCNIsUseChkBox').find('.xCNInputRole').prop('checked',false);
            }
        });

        //========== Check All Report ===========
        $('#otbModuleMenuRole .xWAllow').unbind().one().click(function(){
            var tMunuModuleRptCode = $(this).parents('.xWHeardReportAll').data('rmc');
            if (this.checked) {
                $("#otbModuleMenuRole .xCNDataReport[data-rmc="+tMunuModuleRptCode+"]").find('.xCNInputReport').prop('checked',true);
            } else {
                $("#otbModuleMenuRole .xCNDataReport[data-rmc="+tMunuModuleRptCode+"]").find('.xCNInputReport').prop('checked',false);
            }
        });

        // ========== Event Search Menu ==========
        $("#otbModuleMenuRole #oetSearchAll").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#otbDataBody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        if(JSbRoleIsCreatePage()){
            // Role Code
            $("#oetRolCode").attr("disabled", true);
            $('#ocbRoleAutoGenCode').change(function(){
                if($('#ocbRoleAutoGenCode').is(':checked')) {
                    $('#oetRolCode').val('');
                    $("#oetRolCode").attr("disabled", true);
                    $('#odvBanknoteCodeForm').removeClass('has-error');
                    $('#odvBanknoteCodeForm em').remove();
                }else{
                    $("#oetRolCode").attr("disabled", false);
                }
            });
            JSxRoleVisibleComponent('#odvBanknoteAutoGenCode', true);
        }
        
        if(JSbRoleIsUpdatePage()){
            // Sale Person Code
            $("#oetRolCode").attr("readonly", true);
            $('#odvBanknoteAutoGenCode input').attr('disabled', true);
            JSxRoleVisibleComponent('#odvBanknoteAutoGenCode', false);    
        }

        $('#oetRolCode').blur(function(){
            JSxCheckRoleCodeDupInDB();
        });
    
    });

    //Functionality: Event Check Sale Person Duplicate
    //Parameters: Event Blur Input Sale Person Code
    //Creator: 25/03/2019 wasin (Yoshi)
    //Return: -
    //ReturnType: -
    function JSxCheckRoleCodeDupInDB(){
        if(!$('#ocbRoleAutoGenCode').is(':checked')){
            $.ajax({
                type: "POST",
                url: "CheckInputGenCode",
                data: { 
                    tTableName: "TCNMUsrRole",
                    tFieldName: "FTRolCode",
                    tCode: $("#oetRolCode").val()
                },
                async : false,
                cache: false,
                timeout: 0,
                success: function(tResult){
                    var aResult = JSON.parse(tResult);
                    $("#ohdCheckDuplicateRoleCode").val(aResult["rtCode"]);
                    JSxRoleSetValidEventBlur();
                    $('#ofmAddEditRole').submit();
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
    function JSxRoleSetValidEventBlur(){
        $('#ofmAddEditRole').validate().destroy();

        // Set Validate Dublicate Code
        $.validator.addMethod('dublicateCode', function(value, element) {
            if($("#ohdCheckDuplicateRoleCode").val() == 1){
                return false;
            }else{
                return true;
            }
        },'');

        // From Summit Validate
        $('#ofmAddEditRole').validate({
            rules: {
                oetRolCode : {
                    "required" :{
                        // ตรวจสอบเงื่อนไข validate
                        depends: function(oElement) {
                            if($('#ocbRoleAutoGenCode').is(':checked')){
                                return false;
                            }else{
                                return true;
                            }
                        }
                    },
                    "dublicateCode" :{}
                },
                
                oetRolName:     {"required" :{}},
            },
            messages: {
                oetRolCode : {
                    "required"      : $('#oetRolCode').attr('data-validate-required'),
                    "dublicateCode" : $('#oetRolCode').attr('data-validate-dublicateCode')
                },
                oetRolName : {
                    "required"      : $('#oetRolName').attr('data-validate-required'),
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

</script>