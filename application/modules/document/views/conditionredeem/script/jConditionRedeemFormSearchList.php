<script type="text/javascript">
    var nLangEdits  = '<?php echo $this->session->userdata("tLangEdit");?>';

    $(document).ready(function(){
        $('.selectpicker').selectpicker();

        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
        });

        // Doc Date From
        $('#obtRDHAdvSearchDocDateForm').unbind().click(function(){
            $('#oetRDHAdvSearcDocDateFrom').datepicker('show');
        });

        // Doc Date To
        $('#obtRDHAdvSearchDocDateTo').unbind().click(function(){
            $('#oetRDHAdvSearcDocDateTo').datepicker('show');
        });
    });

    // Advance search Display control
    $('#obtRDHAdvanceSearch').unbind().click(function(){
        if($('#odvRDHAdvanceSearchContainer').hasClass('hidden')){
            $('#odvRDHAdvanceSearchContainer').removeClass('hidden').hide().slideDown(500);
        }else{
            $("#odvRDHAdvanceSearchContainer").slideUp(500,function() {
                $(this).addClass('hidden');
            });
        }
    });

    // Option Branch
    var oRDHBrowseBranch    = function(poRDHBchReturnInput){
        let tRDHBchInputReturnCode  = poRDHBchReturnInput.tReturnInputCode;
        let tRDHBchInputReturnName  = poRDHBchReturnInput.tReturnInputName;
        let oRDHBchOptionReturn     = {
            Title : ['company/branch/branch','tBCHTitle'],
            Table : {Master:'TCNMBranch',PK:'FTBchCode'},
            Join :{
                Table : ['TCNMBranch_L'],
                On : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
            },
            GrideView:{
                ColumnPathLang      : 'company/branch/branch',
                ColumnKeyLang       : ['tBCHCode','tBCHName'],
                ColumnsSize         : ['15%','75%'],
                WidthModal          : 50,
                DataColumns         : ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
                DataColumnsFormat   : ['',''],
                Perpage             : 20,
                OrderBy             : ['TCNMBranch.FTBchCode ASC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tRDHBchInputReturnCode,"TCNMBranch.FTBchCode"],
                Text		: [tRDHBchInputReturnName,"TCNMBranch_L.FTBchName"],
            },
        }
        return oRDHBchOptionReturn;
    };

    // Event Browse Branch From
    $('#obtRDHAdvSearchBrowseBchFrom').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oRDHBrowseBranchFromOption   = undefined;
            oRDHBrowseBranchFromOption          = oRDHBrowseBranch({
                'tReturnInputCode'  : 'oetRDHAdvSearchBchCodeFrom',
                'tReturnInputName'  : 'oetRDHAdvSearchBchNameFrom'
            });
            JCNxBrowseData('oRDHBrowseBranchFromOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Event Browse Branch To
    $('#obtRDHAdvSearchBrowseBchTo').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose();
            window.oRDHBrowseBranchToOption = undefined;
            oRDHBrowseBranchToOption        = oRDHBrowseBranch({
                'tReturnInputCode'  : 'oetRDHAdvSearchBchCodeTo',
                'tReturnInputName'  : 'oetRDHAdvSearchBchNameTo'
            });
            JCNxBrowseData('oRDHBrowseBranchToOption');
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $('#obtRDHSearchReset').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxRDHClearAdvSearchData();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    // Functionality: ???????????????????????????????????????????????? Input Advance Search
    // Parameters: Button Event Click
    // Creator: 23/12/2019 Wasin(Yoshi)
    // Last Update -
    // Return: Clear Value In Input Advance Search
    // ReturnType: -
    function JSxRDHClearAdvSearchData(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            $('#ofmRDHFromSerchAdv').find('input').val('');
            $('#ofmRDHFromSerchAdv').find('select').val(0).selectpicker("refresh");
            JSvRDHCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    }

    // ====================================================  From Search Data Page Purchase Invioce ====================================================
    $('#oetRDHSearchAllDocument').keyup(function(event){
        var nCodeKey    = event.which;
        if(nCodeKey == 13){
            event.preventDefault();
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSvRDHCallPageDataTable();
            }else{
                JCNxShowMsgSessionExpired();
            }
        }
    });

    $('#obtRDHSerchAllDocument').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            JSvRDHCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    $("#obtRDHAdvSearchSubmitForm").unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof nStaSession !== "undefined" && nStaSession == 1){
            JSvRDHCallPageDataTable();
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
</script>