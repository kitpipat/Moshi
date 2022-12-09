<script type="text/javascript">
    var nEJLangEdits    = '<?php echo $this->session->userdata("tLangEdit");?>';
    $(document).ready(function(){
        // *** Set Pugin Select Picker
        $('.selectpicker').selectpicker('refresh');

        // *** Set Pugin Date Picker        
        $('.xCNDatePicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            enableOnReadonly: false,
            disableTouchKeyboard : true,
            autoclose: true
        });

        JSxEJSetConditionCheckBoxRangeDocument();
    });

    // ==================================== Event Click Check Box ====================================
        // Functionality : Next Function Branch
        // Parameter : Event Next Func Modal
        // Create : 10/10/2019 Wasin(Yoshi)
        // Return : Clear Velues Data
        // Return Type : -
        function JSxEJSetConditionCheckBoxRangeDocument(){
            // EJ CheckBox Single Document Code
            $('#ocbEJShipCodeUse').prop('checked',true);
            $('#ocbEJShipCodeUse').prop('readonly', true);
            $('#ocbEJShipCodeUse').parent().css('pointer-events','none');
            $('#obtEJBrowseSlipCode').prop("disabled",false);
             // EJ CheckBox Between Documet Code
            $('#ocbEJShipBetween').prop('checked',false);
            $('#ocbEJShipBetween').prop('readonly', false);
            $('#ocbEJShipBetween').parent().css('pointer-events','');
            $('#obtEJBrowseSlipFrom').prop("disabled",true);
            $('#obtEJBrowseSlipTo').prop("disabled",true);
        }

        // Event Click Use Slip Document Between Code
        $('#ocbEJShipBetween').unbind().click(function(){
            let nStaChkBetween  = ($(this).is(':checked')) ? 1 : 0;
            if(nStaChkBetween == 1){
                let tEJSlipCode = $('#oetEJSlipCode').val();
                if(typeof tEJSlipCode != 'undefined' && tEJSlipCode != ""){
                    $('#oetEJSlipCode').val('');
                    $('#oetEJSlipName').val('');
                }
                // EJ Ship Code Use
                $('#ocbEJShipCodeUse').prop('checked',false);
                $('#ocbEJShipCodeUse').prop('readonly', false);
                $('#ocbEJShipCodeUse').parent().css('pointer-events','');
                $('#obtEJBrowseSlipCode').prop("disabled",true);
                // EJ Ship Code Between
                $('#ocbEJShipBetween').prop('checked',true);
                $('#ocbEJShipBetween').prop('readonly', true);
                $('#ocbEJShipBetween').parent().css('pointer-events','none');
                $('#obtEJBrowseSlipFrom').prop("disabled",false);
                $('#obtEJBrowseSlipTo').prop("disabled",false);
            }
        });

        // Event Click Use Slip Document Single Code
        $('#ocbEJShipCodeUse').unbind().click(function(){
            let nStaChkSingle   = ($(this).is(':checked')) ? 1 : 0;
            if(nStaChkSingle == 1){
                let tEJSlipCodeFrom = $('#oetEJSlipCodeFrom').val();
                let tEJSlipCodeTo   = $('#oetEJSlipCodeTo').val();
                if((typeof tEJSlipCodeFrom != 'undefined' && tEJSlipCodeFrom != "") && (typeof tEJSlipCodeTo != 'undefined' && tEJSlipCodeTo != "")){
                    // Document No From
                    $('#oetEJSlipCodeFrom').val('');
                    $('#oetEJSlipNameFrom').val('');
                    // Document No To
                    $('#oetEJSlipCodeTo').val('');
                    $('#oetEJSlipNameTo').val('');
                }
                // EJ CheckBox Between Documet Code
                $('#ocbEJShipBetween').prop('checked',false);
                $('#ocbEJShipBetween').prop('readonly', false);
                $('#ocbEJShipBetween').parent().css('pointer-events','');
                $('#obtEJBrowseSlipFrom').prop("disabled",true);
                $('#obtEJBrowseSlipTo').prop("disabled",true);
                // EJ CheckBox Single Document Code
                $('#ocbEJShipCodeUse').prop('checked',true);
                $('#ocbEJShipCodeUse').prop('readonly', true);
                $('#ocbEJShipCodeUse').parent().css('pointer-events','none');
                $('#obtEJBrowseSlipCode').prop("disabled",false);
            }
        });
    // ===============================================================================================

    // ====================================== Event Data Picker ======================================
        $('#obtEJBrowseDateFrom').unbind().click(function(){
            $('#oetEJDocDateFrom').datepicker('show');
        });
        $('#obtEJBrowseDateTo').unbind().click(function(){
            $('#oetEJDocDateTo').datepicker('show');
        });
    // ===============================================================================================

    // ======================================== Browse Option ========================================
        // Browse Branch Option
        var oEJBranchOption         = function(poReturnInput){
            var tInputReturnCode    = poReturnInput.tReturnInputCode;
            var tInputReturnName    = poReturnInput.tReturnInputName;
            var tNextFuncName       = poReturnInput.tNextFuncName;
            var aArgReturn          = poReturnInput.aArgReturn;
            var oOptionReturn       = {
                Title: ['company/branch/branch','tBCHTitle'],
                Table:{Master:'TCNMBranch',PK:'FTBchCode'},
                Join :{
                    Table:	['TCNMBranch_L'],
                    On:['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nEJLangEdits]
                },
                GrideView:{
                    ColumnPathLang	: 'company/branch/branch',
                    ColumnKeyLang	: ['tBCHCode','tBCHName'],
                    ColumnsSize     : ['15%','75%'],
                    WidthModal      : 50,
                    DataColumns		: ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
                    DataColumnsFormat : ['',''],
                    Perpage			: 10,
                    OrderBy			: ['TCNMBranch_L.FTBchCode ASC'],
                },
                CallBack:{
                    ReturnType	: 'S',
                    Value		: [tInputReturnCode,"TCNMBranch.FTBchCode"],
                    Text		: [tInputReturnName,"TCNMBranch_L.FTBchName"]
                },
                NextFunc : {
                    FuncName    : tNextFuncName,
                    ArgReturn   : aArgReturn
                },
                RouteAddNew: 'branch',
                BrowseLev: 1
            }
            return oOptionReturn;
        }

        // Browse Shop Option
        var oEJShopOption           = function(poReturnInput){
            var tInputReturnCode    = poReturnInput.tReturnInputCode;
            var tInputReturnName    = poReturnInput.tReturnInputName;
            var tNextFuncName       = poReturnInput.tNextFuncName;
            var aArgReturn          = poReturnInput.aArgReturn;
            var tBranchCode         = poReturnInput.tEJBranhCode;
            var tWhereShop          = "";
            if(typeof tBranchCode !== 'undefined'  && tBranchCode != ""){
                tWhereShop  = " AND (TCNMShop.FTBchCode = '"+tBranchCode+"')";
            }
            var oOptionReturn       = {
                Title: ['company/shop/shop','tSHPTitle'],
                Table: {Master:'TCNMShop', PK:'FTShpCode'},
                Join    : {
                    Table   : ['TCNMShop_L', 'TCNMBranch_L'],
                    On      : [
                        'TCNMShop.FTBchCode = TCNMShop_L.FTBchCode AND TCNMShop.FTShpCode = TCNMShop_L.FTShpCode AND TCNMShop_L.FNLngID = '+nEJLangEdits,
                        'TCNMShop.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID = '+nEJLangEdits
                    ]
                },
                Where :{
                    Condition : [tWhereShop]
                },
                GrideView:{
                    ColumnPathLang	: 'company/shop/shop',
                    ColumnKeyLang	: ['tSHPTBBranch','tSHPTBCode','tSHPTBName'],
                    ColumnsSize     : ['15%','15%','75%'],
                    WidthModal      : 50,
                    DataColumns		: ['TCNMBranch_L.FTBchName', 'TCNMShop.FTShpCode', 'TCNMShop_L.FTShpName'],
                    DataColumnsFormat : ['','',''],
                    Perpage			: 10,
                    OrderBy			: ['TCNMShop.FTBchCode ASC,TCNMShop.FTShpCode ASC'],
                },
                CallBack:{
                    ReturnType	: 'S',
                    Value		: [tInputReturnCode,"TCNMShop.FTShpCode"],
                    Text		: [tInputReturnName,"TCNMShop_L.FTShpName"]
                },
                RouteAddNew: 'shop',
                BrowseLev: 1
            }
            return oOptionReturn;
        }

        // Browse Slip  Option
        var oEJSlipOption           = function(poReturnInput){
            var tInputReturnCode    = poReturnInput.tReturnInputCode;
            var tInputReturnName    = poReturnInput.tReturnInputName;
            var tBranchCode         = poReturnInput.tBranchCode;
            var tShopCode           = poReturnInput.tShopCode;
            var tWhereBranch        = '';
            var tWhereShop          = '';
            if(typeof tBranchCode !== 'undefined' && tBranchCode != ''){
                tWhereBranch    = " AND (TPSTSlipEJ.FTBchCode = '"+tBranchCode+"')";
            }
            if(typeof tShopCode !== 'undefined' && tShopCode != ''){
                tWhereShop      = " AND (TPSTSlipEJ.FTShpCode = '"+tShopCode+"')";
            }
            var oOptionReturn       = {
                Title: ['document/reprintej/reprintej','tEJSlipBrowseTitle'],
                Table: {Master:'TPSTSlipEJ', PK:'FTXshDocNo'},
                Join    : {
                    Table: ['TCNMShop_L', 'TCNMBranch_L'],
                    On: [
                        'TPSTSlipEJ.FTBchCode = TCNMShop_L.FTBchCode AND TPSTSlipEJ.FTShpCode = TCNMShop_L.FTShpCode AND TCNMShop_L.FNLngID = '+nEJLangEdits,
                        'TPSTSlipEJ.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID = '+nEJLangEdits
                    ]
                },
                Where :{
                    Condition : [tWhereBranch+tWhereShop]
                },
                GrideView:{
                    ColumnPathLang	: 'document/reprintej/reprintej',
                    ColumnKeyLang	: ['tEJSlipBrowseBranch','tEJSlipBrowseShop','tEJSlipBrowseDocumentNo','tEJSlipBrowseShfCode'],
                    ColumnsSize     : ['15%','15%','50%','20%'],
                    WidthModal      : 50,
                    DataColumns		: ['TCNMBranch_L.FTBchName','TCNMShop_L.FTShpName','TPSTSlipEJ.FTXshDocNo','TPSTSlipEJ.FTShfCode'],
                    DataColumnsFormat : ['','','',''],
                    Perpage			: 10,
                    OrderBy			: ['TPSTSlipEJ.FTBchCode ASC,TPSTSlipEJ.FTShpCode ASC,TPSTSlipEJ.FTXshDocNo ASC,TPSTSlipEJ.FTShfCode ASC'],
                },
                CallBack:{
                    ReturnType	: 'S',
                    Value		: [tInputReturnCode,"TPSTSlipEJ.FTXshDocNo"],
                    Text		: [tInputReturnName,"TPSTSlipEJ.FTXshDocNo"]
                },
                BrowseLev: 1
            }
            return oOptionReturn;
        }

        // Browse Slip Between Option
        var oEJSlipBetweenOption    = function(poReturnInput){
            var tInputReturnCode    = poReturnInput.tReturnInputCode;
            var tInputReturnName    = poReturnInput.tReturnInputName;
            var tBranchCode         = poReturnInput.tBranchCode;
            var tShopCode           = poReturnInput.tShopCode;
            var tWhereBranch        = '';
            var tWhereShop          = '';
            if(typeof tBranchCode !== 'undefined' && tBranchCode != ''){
                tWhereBranch    = " AND (TPSTSlipEJ.FTBchCode = '"+tBranchCode+"')";
            }
            if(typeof tShopCode !== 'undefined' && tShopCode != ''){
                tWhereShop      = " AND (TPSTSlipEJ.FTShpCode = '"+tShopCode+"')";
            }
            var oOptionReturn       = {
                Title: ['document/reprintej/reprintej','tEJSlipBrowseTitle'],
                Table: {Master:'TPSTSlipEJ', PK:'FTXshDocNo'},
                Join    : {
                    Table: ['TCNMShop_L', 'TCNMBranch_L'],
                    On: [
                        'TPSTSlipEJ.FTBchCode = TCNMShop_L.FTBchCode AND TPSTSlipEJ.FTShpCode = TCNMShop_L.FTShpCode AND TCNMShop_L.FNLngID = '+nEJLangEdits,
                        'TPSTSlipEJ.FTBchCode = TCNMBranch_L.FTBchCode AND TCNMBranch_L.FNLngID = '+nEJLangEdits
                    ]
                },
                Where :{
                    Condition : [tWhereBranch+tWhereShop]
                },
                GrideView:{
                    ColumnPathLang	: 'document/reprintej/reprintej',
                    ColumnKeyLang	: ['tEJSlipBrowseBranch','tEJSlipBrowseShop','tEJSlipBrowseDocumentNo','tEJSlipBrowseShfCode'],
                    ColumnsSize     : ['15%','15%','50%','20%'],
                    WidthModal      : 50,
                    DataColumns		: ['TCNMBranch_L.FTBchName','TCNMShop_L.FTShpName','TPSTSlipEJ.FTXshDocNo','TPSTSlipEJ.FTShfCode'],
                    DataColumnsFormat : ['','','',''],
                    Perpage			: 10,
                    OrderBy			: ['TPSTSlipEJ.FTBchCode ASC,TPSTSlipEJ.FTShpCode ASC,TPSTSlipEJ.FTXshDocNo ASC,TPSTSlipEJ.FTShfCode ASC'],
                },
                CallBack:{
                    ReturnType	: 'S',
                    Value		: [tInputReturnCode,"TPSTSlipEJ.FTXshDocNo"],
                    Text		: [tInputReturnName,"TPSTSlipEJ.FTXshDocNo"]
                },
                BrowseLev: 1
            };
            return oOptionReturn;
        }
    // ===============================================================================================
    
    // ========================================= Event Browse ========================================
        // Evnet Browse Branch
        $('#obtEJBrowseBranch').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oEJBrowseBranchOption    = undefined;
                oEJBrowseBranchOption           = oEJBranchOption({
                    'tReturnInputCode'  : 'oetEJBchCode',
                    'tReturnInputName'  : 'oetEJBchName',
                    'tNextFuncName'     : 'JSxEJConsNextFuncBrowseBch',
                    'aArgReturn'        : ['FTBchCode','FTBchName']
                });
                JCNxBrowseData('oEJBrowseBranchOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Evnet Browse Shop
        $('#obtEJBrowsShop').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oEJBrowseShopOption  = undefined;
                var tBranchCode     = $('#oetEJBchCode').val();
                oEJBrowseShopOption = oEJShopOption({
                    'tReturnInputCode'  : 'oetEJShopCode',
                    'tReturnInputName'  : 'oetEJShopName',
                    'tEJBranhCode'      : tBranchCode
                });
                JCNxBrowseData('oEJBrowseShopOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Evnet Browse Slip Single 
        $('#obtEJBrowseSlipCode').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oEJBrowseSlipSingleOption    = undefined;
                var tBranchCode = $('#oetEJBchCode').val();
                var tShopCode   = $('#oetEJShopCode').val();
                oEJBrowseSlipSingleOption   = oEJSlipOption({
                    'tReturnInputCode'  : 'oetEJSlipCode',
                    'tReturnInputName'  : 'oetEJSlipName',
                    'tBranchCode'       : tBranchCode,
                    'tShopCode'         : tShopCode,
                });
                JCNxBrowseData('oEJBrowseSlipSingleOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Evnet Browse Slip From
        $('#obtEJBrowseSlipFrom').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oEJSlipBetweenFromOption = undefined;
                var tBranchCode = $('#oetEJBchCode').val();
                var tShopCode   = $('#oetEJShopCode').val();
                oEJSlipBetweenFromOption    = oEJSlipOption({
                    'tReturnInputCode'  : 'oetEJSlipCodeFrom',
                    'tReturnInputName'  : 'oetEJSlipNameFrom',
                    'tBranchCode'       : tBranchCode,
                    'tShopCode'         : tShopCode,
                });
                JCNxBrowseData('oEJSlipBetweenFromOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Evnet Browse Slip To
        $('#obtEJBrowseSlipTo').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oEJSlipBetweenToOption   = undefined;
                var tBranchCode = $('#oetEJBchCode').val();
                var tShopCode   = $('#oetEJShopCode').val();
                oEJSlipBetweenToOption  = oEJSlipOption({
                    'tReturnInputCode'  : 'oetEJSlipCodeTo',
                    'tReturnInputName'  : 'oetEJSlipNameTo',
                    'tBranchCode'       : tBranchCode,
                    'tShopCode'         : tShopCode,
                });
                JCNxBrowseData('oEJSlipBetweenToOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
    // ===============================================================================================

    // ========================================= Event Button ========================================
        // Click Refresh View
        $('#obtRPEJRefreshView').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSvCallPageEJMainFormPrint()
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Click Filter Search
        $('#obtRPEJFilterSerch').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxEJFilterDataABBInDB();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
    // ===============================================================================================
</script>