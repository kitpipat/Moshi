<script type="text/javascript">
    var nLangEdits  = <?php echo $this->session->userdata("tLangEdit")?>;

    // Option Branch
    var oCmpBranch      = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var oOptionReturn       = {
            Title   : ['company/branch/branch','tBCHTitle'],
            Table   :{Master:'TCNMBranch',PK:'FTBchCode'},
            Join    :{
                Table : ['TCNMBranch_L'],
                On : ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
            },
            GrideView:{
                ColumnPathLang	: 'company/branch/branch',
                ColumnKeyLang	: ['tBCHCode','tBCHName'],
                ColumnsSize     : ['10%','75%'],
                DataColumns		: ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
                DataColumnsFormat : ['',''],
                WidthModal      : 50,
                Perpage			: 10,
                OrderBy			: ['TCNMBranch.FTBchCode ASC'],
            },
            CallBack:{
                ReturnType : 'S',
                Value : [tInputReturnCode,"TCNMBranch.FTBchCode"],
                Text : [tInputReturnName,"TCNMBranch_L.FTBchName"],
            },
            RouteAddNew : 'branch',
            BrowseLev : nStaCmpBrowseType
        };
        return oOptionReturn;
    }
    
    // Option Currency
    var oCmpCurrency    = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var oOptionReturn       = {
            Title   : ['payment/rate/rate','tRTETitle'],
            Table   :{Master:'TFNMRate',PK:'FTRteCode'},
            Join    :{
                Table : ['TFNMRate_L'],
                On : ['TFNMRate_L.FTRteCode = TFNMRate.FTRteCode AND TFNMRate_L.FNLngID = '+nLangEdits,]
            },
            GrideView:{
                ColumnPathLang	: 'payment/rate/rate',
                ColumnKeyLang	: ['tRTETBRteCode','tRTETBRteName'],
                ColumnsSize     : ['10%','75%'],
                DataColumns		: ['TFNMRate.FTRteCode','TFNMRate_L.FTRteName'],
                DataColumnsFormat : ['',''],
                WidthModal      : 50,
                Perpage			: 10,
                OrderBy			: ['TFNMRate.FTRteCode ASC'],
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TFNMRate.FTRteCode"],
                Text		: [tInputReturnName,"TFNMRate_L.FTRteName"],
            },
            RouteAddNew : 'rate',
            BrowseLev : nStaCmpBrowseType,
            // DebugSQL : true
        };
        return oOptionReturn;
    }
    var oCmpVatRate     = function(poDataFnc){
        var tInputReturnCode    = poDataFnc.tReturnInputCode;
        var tInputReturnName    = poDataFnc.tReturnInputName;
        var tTextLeftJoin       = "(SELECT top  1 Result.* FROM ( SELECT TCNMVatRate.FTVatCode,TCNMVatRate.FCVatRate,TCNMVatRate.FDVatStart FROM TCNMVatRate WHERE 1=1 AND (CONVERT(VARCHAR(19),GETDATE(),121) >= CONVERT(VARCHAR(19),TCNMVatRate.FDVatStart,121))) AS Result ORDER BY FDVatStart DESC ) AS TVJOIN ";
        var oOptionReturn       = {
            Title : ['company/vatrate/vatrate','tVATTitle'],
            Table : {Master:'TCNMVatRate',PK:'FTVatCode'},
            Join    :{
                Table : [tTextLeftJoin],
                SpecialJoin : ['INNER JOIN'],
                On : ['TCNMVatRate.FCVatRate = TVJOIN.FCVatRate']
            },
            GrideView :{
                ColumnPathLang	: 'company/vatrate/vatrate',
                ColumnKeyLang	: ['tVATTBCode','tVATTBRate','tVATDateStart'],
                DataColumns		: ['TCNMVatRate.FTVatCode','TCNMVatRate.FCVatRate','TCNMVatRate.FDVatStart'],
                Perpage			: 10,
                OrderBy			: ['TCNMVatRate.FTVatCode'],
                SourceOrder		: "ASC"
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: [tInputReturnCode,"TCNMVatRate.FTVatCode"],
                Text		: [tInputReturnName,"TCNMVatRate.FCVatRate"],
            },
            // DebugSQL : true
        };
        return oOptionReturn;
    }


    $(document).ready(function(){
        $('.xCNComboSelect').selectpicker('refresh');

        // Event Browse ????????????
        $('#obtCmpBrowseBranch').click(function() { 
            poElement = this;
            if (poElement.getAttribute("data-dblclick") == null) {
                poElement.setAttribute("data-dblclick", 1);
                $(poElement).select();
                setTimeout(function () {
                    if (poElement.getAttribute("data-dblclick") == 1) {
                        var nStaSession = JCNxFuncChkSessionExpired();
                        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                            // Create By Witsarut 04/10/2019
                                JSxCheckPinMenuClose();
                             // Create By Witsarut 04/10/2019
                            window.oCmpBranchOption = undefined;
                            oCmpBranchOption        = oCmpBranch({
                                'tReturnInputCode'  : 'oetCmpBchCode',
                                'tReturnInputName'  : 'oetCmpBchName',
                            })
                            JCNxBrowseData('oCmpBranchOption');
                        }else{
                            JCNxShowMsgSessionExpired();
                        }
                    }
                    poElement.removeAttribute("data-dblclick");
                }, 300);
            }
        });

        // Event Browse ????????????????????????
        $('#obtCmpBrowseCurrency').click(function() { 
            poElement = this;
            if (poElement.getAttribute("data-dblclick") == null) {
                poElement.setAttribute("data-dblclick", 1);
                $(poElement).select();
                setTimeout(function () {
                    if (poElement.getAttribute("data-dblclick") == 1) {
                        var nStaSession = JCNxFuncChkSessionExpired();
                        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                            // Create By Witsarut 04/10/2019
                                JSxCheckPinMenuClose();
                             // Create By Witsarut 04/10/2019
                            window.oCmpCurrencyOption   = undefined;
                            oCmpCurrencyOption          = oCmpCurrency({
                                'tReturnInputCode'  : 'oetCmpRteCode',
                                'tReturnInputName'  : 'oetCmpRteName',
                            });
                            JCNxBrowseData('oCmpCurrencyOption');
                        }else{
                            JCNxShowMsgSessionExpired();
                        }
                    }
                    poElement.removeAttribute("data-dblclick");
                }, 300);
            }
        });

        // Event Browse ????????????????????????
        $('#obtCmpBrowseVatRate').click(function() { 
            poElement = this;
            if (poElement.getAttribute("data-dblclick") == null) {
                poElement.setAttribute("data-dblclick", 1);
                $(poElement).select();
                setTimeout(function () {
                    if (poElement.getAttribute("data-dblclick") == 1) {
                        var nStaSession = JCNxFuncChkSessionExpired();
                        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                             // Create By Witsarut 04/10/2019
                                JSxCheckPinMenuClose();
                             // Create By Witsarut 04/10/2019
                            window.oCmpVatRateOption   = undefined;
                            oCmpVatRateOption          = oCmpVatRate({
                                'tReturnInputCode'  : 'oetVatRateCode',
                                'tReturnInputName'  : 'oetVatRateName',
                            });
                            JCNxBrowseData('oCmpVatRateOption');
                        }else{
                            JCNxShowMsgSessionExpired();
                        }
                    }
                    poElement.removeAttribute("data-dblclick");
                }, 300);
            }
        });

    });



</script>