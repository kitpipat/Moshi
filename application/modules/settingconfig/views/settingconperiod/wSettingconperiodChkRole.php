<?php 
    // ตรวจสอบโหมดการทำงานว่าเป็น โหมดเพิ่มใหม่ หรือแก้ไข
    if(isset($nStaAddOrEdit) && $nStaAddOrEdit == 1){

        $tRoute        = "settingconperiodEventEdit";
        $tLhdCode      = $aLimDataEdit['raItems']['rtLhdCode'];
        $tLhdName      = $aLimDataEdit['raItems']['rtLhdName'];

        $tGrpRolCode   = $aLimDataEdit['raItems']['rtRolCode'];
        $tGrpRolName   = $aLimDataEdit['raItems']['rtRolName'];

        $nCheckEdit = 1;


    }else{

        $tLhdCode       = "";
        $tLhdName       = "";
        $tGrpRolCode    = "";
        $tGrpRolName    = "";
        $nCheckEdit = "";

        $tRoute     = "settingconperiodEventAdd";
    }

?>
<?php if($nCheckEdit == 1) { ?>
<form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddSetingConperiodEdit">
  
    <button style="display:none" type="submit" id="obtSubmitSettingConPeriod" onclick="JSoAddEditSettingConperiodEdit('settingconperiodEventEdit')"></button>
        <div class="panel-body" style="padding-top:20px !important;">
            <div class="row">
                <!-- เงื่อนไข -->
                <div class="col-xs-12 col-md-5 col-lg-5">
                    <label class="xCNLabelFrm"><?php echo language('settingconfig/settingconperiod/settingconperiod','tLIMCondition')?></label> 
                    <div class="form-group">
                        <div class="input-group">
                            <input type="hidden" class="form-control xCNInputWithoutSpcNotThai" maxlength="5" id="oetLhdCodeEdit" name="oetLhdCodeEdit" value="<?php echo $tLhdCode?>" >
                            <input type="text" class="form-control xWPointerEventNone" id="oetLhdNameEdit" name="oetLhdNameEdit" maxlength="100" value="<?php echo $tLhdName ?>" readonly>
                            <span class="input-group-btn">
                                <button id="oimBrowseLhdEdit" type="button" class="btn xCNBtnBrowseAddOn" disabled>
                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-xs-12 col-md-5 col-lg-5">
                    <label class="xCNLabelFrm"><?php echo language('settingconfig/settingconperiod/settingconperiod','tLIMRoleGroup')?></label> 
                    <div class="form-group">
                        <div class="input-group">
                        <input type="text" class="input100 xCNHide" id="oetGrpRolCodeEdt" name="oetGrpRolCodeEdit" maxlength="5" value="<?php echo $tGrpRolCode; ?>">
                            <input type="text" class="form-control xWPointerEventNone" id="oetGrpRolNameEdit" name="oetGrpRolNameEdit" maxlength="100" value="<?php echo $tGrpRolName ?>" readonly>
                            <span class="input-group-btn">
                                <button id="oimBrowseRolGroupEdit" type="button" class="btn xCNBtnBrowseAddOn" disabled>
                                    <img src="<?php echo  base_url() . '/application/modules/common/assets/images/icons/find-24.png' ?>">
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
      
    <?php } ?>

        <!-- BtnAdd -->
        <div id="odvLimMainMenuAdd">
            <div class="xCNMrgNavMenu">
                <div class="row xCNavRow">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right p-r-0">
                        <div id="odvControlBtn">
                        <?php if($aResultChkAlwSeq['rtCode'] == 1 ):?>
                            <?php if($aResultChkAlwSeq['raItems'][0]['FTLhdStaAlwSeq'] ==  1) : ?>
                                <button class="xCNBTNPrimeryPlus xCNHideBtnAdd xCNHideStaWarm" type="button" onclick="JSvCallCheckRolAndLimit()">+</button>
                                <script>
                                    console.log($('#otbLimDataListPageAdd tbody tr').length);
                                    if( $('#otbLimDataListPageAdd tbody tr').length == 0 ){
                                        JSvCallCheckRolAndLimit();
                                    }
                                    $('.xCNHideBtnStaAlw').show();
                                </script>
                            <?php else : ?>
                                <script>
                                    console.log($('#otbLimDataListPageAdd tbody tr').length);
                                    if( $('#otbLimDataListPageAdd tbody tr').length == 0 ){
                                        JSvCallCheckRolAndLimit();
                                    }
                                    $('.xCNHideBtnStaAlw').show();
                                </script>
                            <?php endif; ?>
                        <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <?php if($aResultChkAlwSeq['rtCode'] == 1 ) : ?>
            <input type ="hidden" id="oetChkStaAlwMinMax"  name="oetChkStaAlwMinMax" value="<?php echo $aResultChkAlwSeq['raItems'][0]['FTLhdStaMinMax'];?>">
         <?php endif;?>
        <!-- Call Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="otbLimDataListPageAdd" class="table table-striped">
                        <thead>
                            <tr>
                                <th nowrap class="text-center xCNTextBold"><?php echo language('settingconfig/settingconperiod/settingconperiod','tLIMSeq'); ?></th>

                                <?php if($aResultChkAlwSeq['rtCode'] == 1 ) : ?>
                                    <?php if($aResultChkAlwSeq['raItems'][0]['FTLhdStaMinMax'] ==  1) : ?>
                                        <th nowrap class="text-center xCNTextBold"><?php echo language('settingconfig/settingconperiod/settingconperiod','tLIMMinValue'); ?></th>
                                    <?php elseif($aResultChkAlwSeq['raItems'][0]['FTLhdStaMinMax'] == 2): ?>
                                        <th nowrap class="text-center xCNTextBold"><?php echo language('settingconfig/settingconperiod/settingconperiod','tLIMMaxValue'); ?></th>
                                    <?php elseif($aResultChkAlwSeq['raItems'][0]['FTLhdStaMinMax'] == 3) : ?>
                                        <th nowrap class="text-center xCNTextBold"><?php echo language('settingconfig/settingconperiod/settingconperiod','tLIMMinValue'); ?></th>
                                        <th nowrap class="text-center xCNTextBold"><?php echo language('settingconfig/settingconperiod/settingconperiod','tLIMMaxValue'); ?></th>
                                    <?php endif;?>
                                <?php endif;?>

                                <?php if($aResultChkAlwSeq['rtCode'] == 800 ) : ?>
                                    <th nowrap class="text-center xCNTextBold"><?php echo language('settingconfig/settingconperiod/settingconperiod','tLIMMinValue'); ?></th>
                                    <th nowrap class="text-center xCNTextBold"><?php echo language('settingconfig/settingconperiod/settingconperiod','tLIMMaxValue'); ?></th>
                                <?php endif;?>


                                <th nowrap class="text-center xCNTextBold"><?php echo language('settingconfig/settingconperiod/settingconperiod','tLIMLevMsgAlert'); ?></th>
                                <th nowrap class="text-center xCNTextBold"><?php echo language('settingconfig/settingconperiod/settingconperiod','tLIMSpcMsg'); ?></th>
                                <?php //if($aResultChkAlwSeq['rtCode'] == 1 ):?>
                                    <?php //if($aResultChkAlwSeq['raItems'][0]['FTLhdStaAlwSeq'] ==  1) : ?>
                                        <th nowrap class="text-center xCNTextBold"><?php echo language('settingconfig/settingconperiod/settingconperiod','tLIMDelete'); ?></th>
                                    <?php //endif;?>
                                <?php //endif;?>
                            </tr>
                        </thead>
                        <tbody id="odvLimlist">
                            <?php if($aLimDataChk['rtCode'] == 1 ):?>
                                <?php foreach($aLimDataChk['raItems'] AS $nKey => $aValue) : ?>
                                    <tr data-default="<?php echo number_format($aValue['FCLimMin'],2);?>" data-defaultMax="<?php echo number_format($aValue['FCLimMax'],2);?>" data-seqnumber="<?=$nKey+1?>">
                                        <td width="3%" style="height:38px;" class="text-center"><?php echo $nKey+1 ?></td>
                                      
                                        <?php if($aResultChkAlwSeq['rtCode'] == 1 ) : ?>
                                            <?php if($aResultChkAlwSeq['raItems'][0]['FTLhdStaMinMax'] ==  1) : ?>
                                                <td width="15%"><input style="height:38px; text-align:right;" class="xCNEditInLine" data-desc="min" type="text" name="oetMinValue[]" value="<?php echo number_format($aValue['FCLimMin'],2);?>"></td>
                                            <?php elseif($aResultChkAlwSeq['raItems'][0]['FTLhdStaMinMax'] == 2):?>
                                                <td width="15%"><input style="height:38px; text-align:right;" class="xCNEditInLine" data-desc="max" type="text" name="oetMaxValue[]" value="<?php echo number_format($aValue['FCLimMax'],2);?>"></td>
                                            <?php elseif($aResultChkAlwSeq['raItems'][0]['FTLhdStaMinMax'] == 3):?>
                                                <td width="15%"><input style="height:38px; text-align:right;" class="xCNEditInLine" data-desc="min" type="text" name="oetMinValue[]" value="<?php echo number_format($aValue['FCLimMin'],2);?>"></td>
                                                <td width="15%"><input style="height:38px; text-align:right;" class="xCNEditInLine" data-desc="max" type="text" name="oetMaxValue[]" value="<?php echo number_format($aValue['FCLimMax'],2);?>"></td>
                                            <?php endif;?>
                                        <?php endif;?>
                                      
                                        <td width="15%" class="text-center" valign="bottom">
                                        <select class="selectpicker form-control xCNHideChkStaWarm" name="ocmLimStaWarn[]" maxlength="1">
                                            <option <?php if($aValue['FTLimStaWarn'] == "1"){ echo "selected"; } ?> value="1"><?php echo language('settingconfig/settingconperiod/settingconperiod', 'tLIMAlow');?></option>
                                            <option <?php if($aValue['FTLimStaWarn'] == "2"){ echo "selected"; } ?> value="2" ><?php echo language('settingconfig/settingconperiod/settingconperiod', 'tLIMNotAlow');?></option>
                                        </select>
                                        </td>
                                        <td width="20%"><input style="height:38px;" type="text" name="oetSpcValue[]" value="<?php echo $aValue['FTLimMsgSpc'];?>"></td>
                                        <?php //if($aResultChkAlwSeq['rtCode'] == 1 ):?>
                                            <?php //if($aResultChkAlwSeq['raItems'][0]['FTLhdStaAlwSeq'] ==  1) : ?>
                                                <td width="5%" class="text-center">
                                                    <img class="xCNIconTable xCNIconDel " src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>">
                                                </td>
                                            <?php //endif;?>
                                        <?php //endif;?>
                                    </tr>
                                    
                                <?php endforeach;?>
                            <?php else:?>
                                <!-- <tr><td nowrap class='text-center xCNTextDetail2' colspan='99'><?//= language('common/main/main','tCMNNotFoundData')?></td></tr> -->
                            <?php endif;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php if($aResultChkAlwSeq['rtCode'] == 1 ) : ?>
        <input type="hidden"  name="oetMinMaxValue" value="<?php echo $aResultChkAlwSeq['raItems'][0]['FTLhdStaMinMax'];?>">
    <?php endif;?>

</form>

<div class="modal fade" id="odvModalCallPageEdit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('settingconfig/settingconperiod/settingconperiod', 'tModalConfirmEdit')?></label>
            </div>
            <div class="modal-body">
                <span id="ospConfirmDelete" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
                <input type='hidden' id="ohdConfirmIDDelete">
            </div>
            <div class="modal-footer">
                <button id="osmConfirm" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button">
                    <?php echo language('common/main/main', 'tModalConfirm')?>
                </button>
            </div>
        </div>
    </div>
</div>


<script>

    $('document').ready(function() {
        var tChkStaWarm  = $('.xCNHideChkStaWarm').last().val();
        if(tChkStaWarm == 2){
            $('.xCNHideStaWarm').hide();
        }else{
            $('.xCNHideStaWarm').show();
        }
    });


    $('.xCNHideStaWarm').on('change', function() {
    var tChkStaWarm  = $('.xCNHideChkStaWarm').last().val();
        if(tChkStaWarm == 2){
            $('.xCNHideStaWarm').hide();
        }else{
            $('.xCNHideStaWarm').show();
        }
    });


    $('select').on('change', function() {
        if($('.xCNHideChkStaWarm').last().val() == 1){
            $('.xCNHideStaWarm').show();
        }else{
            $('.xCNHideStaWarm').hide();
        }
        
    });
    

    $('.selectpicker').selectpicker();
    $(document).ready(function(){
        sessionStorage.removeItem("EditInLine");
        $('#otbLimDataListPageAdd').find('.xCNIconTable').addClass('xCNDocDisabled');
        $('#otbLimDataListPageAdd').find('tr:last td:eq(4)').find('.xCNIconTable').removeClass('xCNDocDisabled');
        $('#otbLimDataListPageAdd').find('tr:last td:eq(5)').find('.xCNIconTable').removeClass('xCNDocDisabled');
        // .prop("onclick", null).off("click");
    });

    $('.xCNIconDel').off('click');
    $('.xCNIconDel').on('click',function(e){
        var tCheckClassDelete = $(this).hasClass('xCNDocDisabled');
        if(tCheckClassDelete == false){
            JSoLimDelChkRole(this);
        }
    });

   

    JSxCallEditInline();
    function JSxCallEditInline(){
        //แก้ไขจำนวน
        $('.xCNEditInLine').off("keydown");
        $('.xCNEditInLine').on("keydown",function(e){
            if( e.keyCode == 13 ){
                sessionStorage.setItem("EditInLine", "1");
                if(sessionStorage.getItem("EditInLine") == "1"){
                    sessionStorage.setItem("EditInLine", "2");
                    e.preventDefault();
                    console.log('keydown');
                    var tDesc = $(this).attr("data-desc");
                    if(tDesc == 'min'){ //กรอก ขั้นต่ำ (มูลค่า) 
                        // var nLen                 = $(this).parent().parent().attr("data-seqnumber");
                        // var tBefore_last_row     = $('#otbLimDataListPageAdd tr').eq(nLen - 1).find('td:eq(2)').find('input').val(); 
                        // var tNewReplaceComma    = tBefore_last_row.replace(/,/g, '');
                        // // var cDefaultValue       = $(this).parent().parent().attr('data-default');
                        var nNewValue           = parseFloat($(this).val().replace(/,/g, '')).toFixed(2);
                        // if( parseFloat(nNewValue) <= parseFloat(tNewReplaceComma) ){ //ไม่ผ่าน
                        //     //แปลงให้เป็นทศนิยม
                        //     nValNuformatmber = parseFloat(tNewReplaceComma).toFixed(2)
                        //     var nValNuformatmber = Number(tNewReplaceComma).toLocaleString('en');
                        //     alert('มูลค่าขั่นต่ำน้อยกว่า'+' '+nValNuformatmber+' '+'กรุณากรอกใหม่');
                        //     $(this).val(cDefaultValue);
                        // }else{ //ผ่าน
                            //แปลงให้เป็นทศนิยม
                            nValNuformatmber = parseFloat(nNewValue).toFixed(2)
                            var nValNuformatmber = Number(nNewValue).toLocaleString('en');
                            $(this).val(nValNuformatmber);
                        // }
                    }else if(tDesc == 'max'){ //กรอก ไม่เกิน (มูลค่า)
                        // var nValueMaxOld    = $(this).parent().parent().attr('data-defaultMax');
                        var nValueMax       = parseFloat($(this).val().replace(/,/g, '')).toFixed(2);
                        // var nValueMin       = parseFloat($(this).parent().parent().find('td:eq(1)').find('input').val().replace(/,/g, '')).toFixed(2);
                        // if(parseFloat(nValueMax) <= parseFloat(nValueMin)){ //ไม่ผ่าน
                        //     alert('มูลค่าไม่สามารถน้อยกว่า'+' '+$(this).parent().parent().find('td:eq(1)').find('input').val()+' '+'กรุณากรอกใหม่');
                        //     $(this).val(nValueMaxOld);
                        // }else{ //ผ่าน
                            nValNuformatmber = parseFloat(nValueMax).toFixed(2)
                            var nValNuformatmber = Number(nValueMax).toLocaleString('en');
                            $(this).val(nValNuformatmber);
                        // }
                    }
                    sessionStorage.setItem("EditInLine", "1");
                }
            }
        });

        $('.xCNEditInLine').off("change");
        $('.xCNEditInLine').on("change",function(e){
            sessionStorage.setItem("EditInLine", "1");
            if(sessionStorage.getItem("EditInLine") == "1"){
                sessionStorage.setItem("EditInLine", "2");
                e.preventDefault();
                console.log('blur');
                var tDesc = $(this).attr("data-desc");
                if(tDesc == 'min'){ //กรอก ขั้นต่ำ (มูลค่า) 
                    // var nLen                 = $(this).parent().parent().attr("data-seqnumber");
                    // var tBefore_last_row     = $('#otbLimDataListPageAdd tr').eq(nLen - 1).find('td:eq(2)').find('input').val(); 
                    // var tNewReplaceComma    = tBefore_last_row.replace(/,/g, '');
                    // var cDefaultValue       = $(this).parent().parent().attr('data-default');
                    var nNewValue           = parseFloat($(this).val().replace(/,/g, '')).toFixed(2);
                    // if( parseFloat(nNewValue) <= parseFloat(tNewReplaceComma) ){ //ไม่ผ่าน
                    //     //แปลงให้เป็นทศนิยม
                    //     nValNuformatmber = parseFloat(tNewReplaceComma).toFixed(2)
                    //     var nValNuformatmber = Number(tNewReplaceComma).toLocaleString('en');
                    //     alert('มูลค่าขั่นต่ำน้อยกว่า'+' '+nValNuformatmber+' '+'กรุณากรอกใหม่');
                    //     $(this).val(cDefaultValue);
                    // }else{ //ผ่าน
                        //แปลงให้เป็นทศนิยม
                        nValNuformatmber = parseFloat(nNewValue).toFixed(2);
                        var nValNuformatmber = Number(nNewValue).toLocaleString('en');
                        $(this).val(nValNuformatmber);
                    // }
                }else if(tDesc == 'max'){ //กรอก ไม่เกิน (มูลค่า)
                    // var nValueMaxOld    = $(this).parent().parent().attr('data-defaultMax');
                    var nValueMax       = parseFloat($(this).val().replace(/,/g, '')).toFixed(2);
                    // var nValueMin       = parseFloat($(this).parent().parent().find('td:eq(1)').find('input').val().replace(/,/g, '')).toFixed(2);
                    // if(parseFloat(nValueMax) <= parseFloat(nValueMin)){ //ไม่ผ่าน
                    //     alert('มูลค่าไม่สามารถน้อยกว่า'+' '+$(this).parent().parent().find('td:eq(1)').find('input').val()+' '+'กรุณากรอกใหม่');
                    //     $(this).val(nValueMaxOld);
                    // }else{ //ผ่าน
                        nValNuformatmber = parseFloat(nValueMax).toFixed(2);
                        var nValNuformatmber = Number(nValueMax).toLocaleString('en');
                        $(this).val(nValNuformatmber);
                    // }
                }
                sessionStorage.setItem("EditInLine", "1");
            }
        });
    }








   // กำหนดเงื่อนไข
   $('#oimBrowseLhdEdit').click(function(){
        JSxCheckPinMenuClose();
        var tLhdCodeParam = $('#oetLhdCodeEdit').val();
        window.oBrowseLhdOption = undefined;
        oBrowseLhdOption = oBrowseLimEdit({
            'tLhdCodeParam': tLhdCodeParam
        });
        JCNxBrowseData('oBrowseLhdOption');
    });


    // กลุ่มสิทธิ์
    // option SettingConditionperiod
    // TCNMUsrRole/TCNMUsrRole_L
    $('#oimBrowseRolGroupEdit').click(function(){
        JSxCheckPinMenuClose();
        var tGrpCodeParam = $('#oetGrpRolCodeEdit').val();
        window.oBrowseGrpRolOption =undefined;
        oBrowseGrpRolOption = oBrowseGrpRoleEdit({
            'tGrpCodeParam': tGrpCodeParam
        });
        JCNxBrowseData('oBrowseGrpRolOption');
    });
    

    var  oBrowseGrpRoleEdit = function(poDataFnc){
        var tGrpCodeParam = poDataFnc.tGrpCodeParam;
        var  tLhdCode  = $('#oetLhdCodeEdit').val();

        var oOptionReturn = {
            Title: ['settingconfig/settingconperiod/settingconperiod', 'tLIMRoleGroup'],
            Table: {
                Master: 'TCNMUsrRole',
                PK: 'FTRolCode'
            },
            Join: {
                Table: ['TCNMUsrRole_L'],
                On: ['TCNMUsrRole_L.FTRolCode = TCNMUsrRole.FTRolCode AND TCNMUsrRole_L.FNLngID = ' + nLangEdits, ]
            },
            GrideView: {
                ColumnPathLang: 'settingconfig/settingconperiod/settingconperiod',
                ColumnKeyLang: ['tLIMCode', 'tLIMRoleGroup'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMUsrRole.FTRolCode', 'TCNMUsrRole_L.FTRolName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNMUsrRole.FTRolCode'],
            },

            NextFunc: {
                FuncName: 'JSxCheckConditionRolsBrows'
            },

            CallBack: {
                ReturnType: 'S',
                Value: ["oetGrpRolCodeEdit", "TCNMUsrRole.FTRolCode"],
                Text: ["oetGrpRolNameEdit", "TCNMUsrRole_L.FTRolName"],
            },
            RouteAddNew: 'settingconperiod',
            BrowseLev: 1
        }
        return oOptionReturn;
    }


     // function Check Role
    // Create BY Witsarut 08-10-2020
    // function JSxCheckConditionRolsBrows(ptType){
    //     var  nLhdCode     = $('#oetLhdCode').val();
    //     var  nGrpRolCode  = $('#oetGrpRolCode').val();
        
    //     if(ptType == 'tPageAddrole'){
    //         nLhdCode = 'tPageadd';
    //         nGrpRolCode = 'tPageadd';
    //     }

    //     if(nLhdCode !== '' && nGrpRolCode !== '' ){
    //         $.ajax({
    //             type : "POST",
    //             url: "settingconperiodDataCheckRolCode",
    //             data: {
    //                 nLhdCode: nLhdCode,
    //                 nGrpRolCode : nGrpRolCode
    //             },
    //             cache: false,
    //             timeout: 0,
    //             success: function(tResult){
    //                 $('#ostContentChkRole').html(tResult);
    //                 JCNxCloseLoading();
    //             },
    //         });
    //     }
    // }


    // เงื่อนไข
    // option SettingConditionperiod
    // TCNSLimitHD/TCNSLimitHD_L
    var  oBrowseLimEdit = function(poDataFnc){
        var tLhdCodeParam = poDataFnc.tLhdCodeParam;

        var oOptionReturn = {
            Title: ['settingconfig/settingconperiod/settingconperiod', 'tLIMCondition'],
            Table: {
                Master: 'TCNSLimitHD',
                PK: 'FTLhdCode'
            },
            Join: {
                Table: ['TCNSLimitHD_L'],
                On: ['TCNSLimitHD_L.FTLhdCode = TCNSLimitHD.FTLhdCode AND TCNSLimitHD_L.FNLngID = ' + nLangEdits, ]
            },
            GrideView: {
                ColumnPathLang: 'settingconfig/settingconperiod/settingconperiod',
                ColumnKeyLang: ['tLIMCode', 'tLIMCondition'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNSLimitHD.FTLhdCode', 'TCNSLimitHD_L.FTLhdName'],
                DataColumnsFormat: ['', ''],
                Perpage: 10,
                OrderBy: ['TCNSLimitHD.FTLhdCode'],
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetLhdCodeEdit", "TCNSLimitHD.FTLhdCode"],
                Text: ["oetLhdNameEdit", "TCNSLimitHD_L.FTLhdName"],
            },
            RouteAddNew: 'settingconperiod',
            BrowseLev: 1,
            NextFunc : {
                FuncName: 'JSxClearBrowseConditionEdit',
                ArgReturn: ['FTLhdCode']
            }
        }
        return oOptionReturn;
    }

    // function clear Browse
    function JSxClearBrowseConditionEdit(ptData){
        if(ptData != '' || ptData != 'null'){
            $('#oetGrpRolCodeEdit').val(''); 
            $('#oetGrpRolNameEdit').val('');
            $('#odvLimlist').remove();
        }
    }


</script>




<script src="<?php echo base_url(); ?>application/modules/common/assets/js/jquery.mask.js"></script>
<script src="<?php echo base_url(); ?>application/modules/common/assets/src/jFormValidate.js"></script>