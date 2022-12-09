<script type="text/javascript">

    var nLangEdits = <?php echo $this->session->userdata("tLangEdit") ?>;
  
    $(function(){
        $("#oetAllCheck").click(function(){
          if($(this).prop("checked")){ // ตรวจสอบค่า ว่ามีการคลิกเลือก
              $(".ocbListItem").prop("checked",true);
          }else{ // ถ้าไม่มีการ ยกเลิกการเลือก
              $(".ocbListItem").prop("checked",false);
          }
      }); 
    });


    // กำหนดเงื่อนไข
    $('#oimBrowseLhd').click(function(){
        JSxCheckPinMenuClose();
        var tLhdCodeParam = $('#oetLhdCode').val();
        window.oBrowseLhdOption = undefined;
        oBrowseLhdOption = oBrowseLim({
            'tLhdCodeParam': tLhdCodeParam
        });
        JCNxBrowseData('oBrowseLhdOption');
    });


    // กลุ่มสิทธิ์
    // option SettingConditionperiod
    // TCNMUsrRole/TCNMUsrRole_L
    $('#oimBrowseRolGroup').click(function(){
        JSxCheckPinMenuClose();
        var tGrpCodeParam = $('#oetGrpRolCode').val();
        window.oBrowseGrpRolOption =undefined;
        oBrowseGrpRolOption = oBrowseGrpRole({
            'tGrpCodeParam': tGrpCodeParam
        });
        JCNxBrowseData('oBrowseGrpRolOption');
    });
    

    var  oBrowseGrpRole = function(poDataFnc){
        var tGrpCodeParam = poDataFnc.tGrpCodeParam;
        var  tLhdCode  = $('#oetLhdCode').val();

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
                Value: ["oetGrpRolCode", "TCNMUsrRole.FTRolCode"],
                Text: ["oetGrpRolName", "TCNMUsrRole_L.FTRolName"],
            },
            RouteAddNew: 'settingconperiod',
            BrowseLev: 1
        }
        return oOptionReturn;
    }


    // function Check Role
    // Create BY Witsarut 08-10-2020
    function JSxCheckConditionRolsBrows(ptType){
        var  nLhdCode     = $('#oetLhdCode').val();
        var  nGrpRolCode  = $('#oetGrpRolCode').val();
        
        if(ptType == 'tPageAddrole'){
            nLhdCode = 'tPageadd';
            nGrpRolCode = 'tPageadd';
        }

        if(nLhdCode != '' && nGrpRolCode != '' ){
                $.ajax({
                    type : "POST",
                    url: "settingconperiodDataCheckRolCode",
                    data: {
                        nLhdCode: nLhdCode,
                        nGrpRolCode : nGrpRolCode
                    },
                    cache: false,
                    timeout: 0,
                    success: function(oResult){
                        var aReturn = JSON.parse(oResult);
                        if(aReturn['nStaEvent'] == 1){
                            $('#ospConfirmDelete').html('การกำหนดเงื่อนไขช่วงการตรวจสอบนี้ มีอยู่แล้วในมระบบ');
                            $('#odvModalCallPageEdit').modal('show');
                            $('#osmConfirm').on('click', function(evt){
                                $('#odvModalCallPageEdit').modal('hide');
                                $('#ospConfirmDelete').empty();
                                localStorage.removeItem('LocalItemData');
                                setTimeout(function() {
                                    JSvCallPageLimEdit($('#oetLhdCode').val(), $('#oetGrpRolCode').val());
                                },500);
                            });
                          
                        }else{
                            $('#ostContentChkRole').html(aReturn['tPagehtml']);
                            JCNxCloseLoading();
                        }
                    },
                });
        }
    }

    // เงื่อนไข
    // option SettingConditionperiod
    // TCNSLimitHD/TCNSLimitHD_L
    var  oBrowseLim = function(poDataFnc){
        var tLhdCodeParam = poDataFnc.tLhdCodeParam;

        if(tLhdCodeParam = ''){
            tWhereStaUse   = '';
        }else{
            tWhereStaUse = " AND TCNSLimitHD.FTLhdStaUse = '1' ";
        }

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
            Where:{
                Condition : [tWhereStaUse]
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
                Value: ["oetLhdCode", "TCNSLimitHD.FTLhdCode"],
                Text: ["oetLhdName", "TCNSLimitHD_L.FTLhdName"],
            },
            RouteAddNew: 'settingconperiod',
            BrowseLev: 1,
            NextFunc : {
                FuncName: 'JSxClearBrowseCondition',
                ArgReturn: ['FTLhdCode']
            }
        }
        return oOptionReturn;
    }

    // function clear Browse
    function JSxClearBrowseCondition(ptData){
        if(ptData != '' || ptData != 'null'){
            $('#oetGrpRolCode').val(''); 
            $('#oetGrpRolName').val('');
            $('#odvLimlist').remove();
            $('.xCNHideBtnStaAlw').hide();
            $('.xCNHideBtnAdd').hide();
        }

    }


</script>