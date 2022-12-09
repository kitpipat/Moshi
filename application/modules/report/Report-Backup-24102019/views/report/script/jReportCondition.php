<script src="<?php echo base_url(); ?>application/modules/common/assets/js/jquery.mask.js"></script>
<script src="<?php echo base_url(); ?>application/modules/common/assets/src/jFormValidate.js"></script>
<script type="text/javascript">
    var tBaseURL    = '<?php echo base_url();?>';
    var nLangEdits  = '<?php echo $this->session->userdata("tLangEdit");?>';
    $('.selectpicker').selectpicker('refresh');

    /** =================================================== Event Date Picker ================================================== */
        $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

        $('.xCNYearPicker').datepicker({
            format: "yyyy",
            weekStart: 1,
            orientation: "bottom",
            keyboardNavigation: false,
            viewMode: "years",
            minViewMode: "years"
        });

        // Click Button Doc Date
        $('#obtRptBrowseDocDateFrom').unbind().click(function(){
            $('#oetRptDocDateFrom').datepicker('show');
        });
        $('#obtRptBrowseDocDateTo').unbind().click(function(){
            $('#oetRptDocDateTo').datepicker('show');
        });

        // Click Button Date Start
        $('#obtRptBrowseDateStartFrom').unbind().click(function(){
            $('#oetRptDateStartFrom').datepicker('show');
        });
        $('#obtRptBrowseDateStartTo').unbind().click(function(){
            $('#oetRptDateStartTo').datepicker('show');
        });

        // Click Button Date Expire
        $('#obtRptBrowseDateExpireFrom').unbind().click(function(){
            $('#oetRptDateExpireFrom').datepicker('show');
        });
        $('#obtRptBrowseDateExpireTo').unbind().click(function(){
            $('#oetRptDateExpireTo').datepicker('show');
        });

        // Click Button Year
        $('#obtRptBrowseYearFrom').unbind().click(function(){
            $('#oetRptYearFrom').datepicker('show');
        });
        $('#obtRptBrowseYearTo').unbind().click(function(){
            $('#oetRptYearTo').datepicker('show');
        });
    /** ======================================================================================================================== */
    
    /** ===================================================== Data Valiable ==================================================== */
        // Browse Branch Option
        var oRptBranchOption    = function(poReturnInputBch){
            let tNextFuncNameBch    = poReturnInputBch.tNextFuncName;
            let aArgReturnBch       = poReturnInputBch.aArgReturn;
            let tInputReturnCodeBch = poReturnInputBch.tReturnInputCode;
            let tInputReturnNameBch = poReturnInputBch.tReturnInputName;
            let oOptionReturnBch    = {
                Title: ['company/branch/branch','tBCHTitle'],
                Table:{Master:'TCNMBranch',PK:'FTBchCode'},
                Join :{
                    Table:	['TCNMBranch_L'],
                    On:['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits]
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
                    Value		: [tInputReturnCodeBch,"TCNMBranch.FTBchCode"],
                    Text		: [tInputReturnNameBch,"TCNMBranch_L.FTBchName"]
                },
                NextFunc : {
                    FuncName    : tNextFuncNameBch,
                    ArgReturn   : aArgReturnBch
                },
                RouteAddNew: 'branch',
                BrowseLev: 1
            };
            return oOptionReturnBch;
        };

        // Browse Shop Option
        var oRptShopOption      = function(poReturnInputShp){
            let tShpNextFuncName        = poReturnInputShp.tNextFuncName;
            let aShpArgReturn           = poReturnInputShp.aArgReturn;
            let tShpInputReturnCode     = poReturnInputShp.tReturnInputCode;
            let tShpInputReturnName     = poReturnInputShp.tReturnInputName;
            let tShpRptModCode          = poReturnInputShp.tRptModCode;
            let tShpRptBranchForm       = poReturnInputShp.tRptBranchForm;
            let tShpRptBranchTo         = poReturnInputShp.tRptBranchTo;
            let tShpWhereShop           = "";
            let tShpWhereShopAndBch     = "";

            // Case Report Type POS,VD,LK
            switch(tShpRptModCode){
                case '001':
                    // Report Pos (รานงานการขาย)
                    tShpWhereShop       = " AND (TCNMShop.FTShpStaActive = 1) AND (TCNMShop.FTShpType = 1)";
                    tShpWhereShopAndBch = " AND ((TCNMShop.FTBchCode BETWEEN "+tShpRptBranchForm+" AND "+tShpRptBranchTo+") OR (TCNMShop.FTBchCode BETWEEN "+tShpRptBranchTo+" AND "+tShpRptBranchForm+"))";
                break;
                case '002':
                    // Report Vending (รานงานตู้ขายสินค้า)
                    tShpWhereShop       = " AND (TCNMShop.FTShpStaActive = 1) AND (TCNMShop.FTShpType = 4)";
                    tShpWhereShopAndBch = " AND ((TCNMShop.FTBchCode BETWEEN "+tShpRptBranchForm+" AND "+tShpRptBranchTo+") OR (TCNMShop.FTBchCode BETWEEN "+tShpRptBranchTo+" AND "+tShpRptBranchForm+"))";
                break;
                case '003':
                    // Report Locker (รานงานตู้ฝากของ)
                    tShpWhereShop       = " AND (TCNMShop.FTShpStaActive = 1) AND (TCNMShop.FTShpType = 5)";
                    tShpWhereShopAndBch = " AND ((TCNMShop.FTBchCode BETWEEN "+tShpRptBranchForm+" AND "+tShpRptBranchTo+") OR (TCNMShop.FTBchCode BETWEEN "+tShpRptBranchTo+" AND "+tShpRptBranchForm+"))";
                break;
            }

            if(typeof tRptBranchForm === 'undefined'  && typeof tRptBranchTo === 'undefined'){
                // แสดงข้อมูล ร้านค้าทั้งหมดตามประเภทของรายงาน
                var oShopOptionReturn       = {
                    Title   : ['company/shop/shop','tSHPTitle'],
                    Table   : {Master:'TCNMShop', PK:'FTShpCode'},
                    Join    : {
                        Table   : ['TCNMShop_L', 'TCNMBranch_L'],
                        On      : [
                            'TCNMShop.FTBchCode = TCNMShop_L.FTBchCode      AND TCNMShop.FTShpCode = TCNMShop_L.FTShpCode AND TCNMShop_L.FNLngID = '+nLangEdits,
                            'TCNMShop.FTBchCode = TCNMBranch_L.FTBchCode    AND TCNMBranch_L.FNLngID = '+nLangEdits
                        ]
                    },
                    Where :{
                        Condition : [tShpWhereShop]
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
                        Value		: [tShpInputReturnCode,"TCNMShop.FTShpCode"],
                        Text		: [tShpInputReturnName,"TCNMShop_L.FTShpName"]
                    },
                    NextFunc : {
                        FuncName    : tShpNextFuncName,
                        ArgReturn   : aShpArgReturn
                    },
                    RouteAddNew: 'shop',
                    BrowseLev: 1
                };
            }else{
                if(tRptBranchForm == "" && tRptBranchTo == ""){
                    // แสดงข้อมูล ร้านค้าทั้งหมดตามประเภทของรายงาน
                    var oShopOptionReturn   = {
                        Title   : ['company/shop/shop','tSHPTitle'],
                        Table   : {Master:'TCNMShop', PK:'FTShpCode'},
                        Join    : {
                            Table   : ['TCNMShop_L', 'TCNMBranch_L'],
                            On      : [
                                'TCNMShop.FTBchCode = TCNMShop_L.FTBchCode      AND TCNMShop.FTShpCode = TCNMShop_L.FTShpCode AND TCNMShop_L.FNLngID = '+nLangEdits,
                                'TCNMShop.FTBchCode = TCNMBranch_L.FTBchCode    AND TCNMBranch_L.FNLngID = '+nLangEdits
                            ]
                        },
                        Where :{
                            Condition : [tShpWhereShop]
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
                            Value		: [tShpInputReturnCode,"TCNMShop.FTShpCode"],
                            Text		: [tShpInputReturnName,"TCNMShop_L.FTShpName"]
                        },
                        NextFunc : {
                            FuncName    : tShpNextFuncName,
                            ArgReturn   : aShpArgReturn
                        },
                        RouteAddNew: 'shop',
                        BrowseLev: 1
                    };
                }else{
                    // แสดงข้อมูลร้านค้า ตามสาขาที่เลือกไว้
                    var oShopOptionReturn   = {
                        Title   : ['company/shop/shop','tSHPTitle'],
                        Table   : {Master:'TCNMShop', PK:'FTShpCode'},
                        Join    : {
                            Table   : ['TCNMShop_L', 'TCNMBranch_L'],
                            On      : [
                                'TCNMShop.FTBchCode = TCNMShop_L.FTBchCode      AND TCNMShop.FTShpCode = TCNMShop_L.FTShpCode AND TCNMShop_L.FNLngID = '+nLangEdits,
                                'TCNMShop.FTBchCode = TCNMBranch_L.FTBchCode    AND TCNMBranch_L.FNLngID = '+nLangEdits
                            ]
                        },
                        Where :{
                            Condition : [tShpWhereShop+tShpWhereShopAndBch]
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
                            Value		: [tShpInputReturnCode,"TCNMShop.FTShpCode"],
                            Text		: [tShpInputReturnName,"TCNMShop_L.FTShpName"]
                        },
                        NextFunc : {
                            FuncName    : tShpNextFuncName,
                            ArgReturn   : aShpArgReturn
                        },
                        RouteAddNew: 'shop',
                        BrowseLev: 1
                    }
                }
            }
            return oShopOptionReturn;
        };

        // Browse Pos Option
        var oRptPosOption       = function(poReturnInputPos){
            let tPosNextFuncName        = poReturnInputPos.tNextFuncName;
            let aPosArgReturn           = poReturnInputPos.aArgReturn;
            let tPosInputReturnCode     = poReturnInputPos.tReturnInputCode;
            let tPosInputReturnName     = poReturnInputPos.tReturnInputName;
            let tPosRptModCode          = poReturnInputPos.tRptModCode;
            let tPosRptShopForm         = poReturnInputPos.tRptShopForm;
            let tPosRptShopTo           = poReturnInputPos.tRptShopTo;
            let oPosJoinTable           = {};
            let tPosWherePos            = "";
            let tPosWherePosAndShop     = "";
            let tPosOrderByCase         = "";
            // Case Report Type POS,VD,LK
            switch(tPosRptModCode){
                case '001':
                    // Report Pos (รานงานการขาย)
                    tPosWherePos    = " AND (TCNMPos.FTPosStaUse = 1) AND (TCNMPos.FTPosType NOT IN(4,5))";
                    tPosOrderByCase = " TCNMPos.FTPosCode ASC"
                break;
                case '002':
                    // Report Vending (รานงานตู้ขายสินค้า)
                    oPosJoinTable   = {
                        Table: ['TVDMPosShop'],
                        On: [
                            'TCNMPos.FTPosCode = TVDMPosShop.FTPosCode',
                        ]
                    };
                    tPosWherePos        = " AND (TCNMPos.FTPosStaUse = 1) AND (TCNMPos.FTPosType = 4)";
                    tPosWherePosAndShop = " AND ((TVDMPosShop.FTShpCode BETWEEN "+tPosRptShopForm+" AND "+tPosRptShopTo+") OR (TVDMPosShop.FTShpCode BETWEEN "+tPosRptShopTo+" AND "+tPosRptShopForm+"))";
                    tPosOrderByCase     = " TCNMPos.FTPosCode ASC,TVDMPosShop.FTPosCode ASC"
                break;
                case '003':
                    // Report Locker (รานงานตู้ฝากของ)
                    oPosJoinTable  = {
                        Table: ['TRTMShopPos'],
                        On: [
                            'TCNMPos.FTPosCode = TRTMShopPos.FTPosCode',
                        ]
                    };
                    tPosWherePos        = " AND (TCNMPos.FTPosStaUse = 1) AND (TCNMPos.FTPosType = 5)";
                    tPosWherePosAndShop = " AND ((TRTMShopPos.FTShpCode BETWEEN "+tPosRptShopForm+" AND "+tPosRptShopTo+") OR (TRTMShopPos.FTShpCode BETWEEN "+tPosRptShopTo+" AND "+tPosRptShopForm+"))";
                    tPosOrderByCase     = " TCNMPos.FTPosCode ASC,TRTMShopPos.FTPosCode ASC"
                break;
            }

            if(typeof(tPosRptShopForm) == 'undefined' && typeof(tPosRptShopTo) == 'undefined'){
                // เกิดขึ้นในกรณีที่ไม่มีปุ่ม Input Shop From || Input Shop To
                var oPosOptionReturn    = {
                    Title   : ["pos/salemachine/salemachine","tPOSTitle"],
                    Table   : { Master:'TCNMPos', PK:'FTPosCode'},
                    Where   : {
                        Condition : [tPosWherePos]
                    },
                    GrideView   : {
                        ColumnPathLang      : 'pos/salemachine/salemachine',
                        ColumnKeyLang       : ['tPOSCode','tPOSRegNo'],
                        ColumnsSize         : ['40%','50%'],
                        WidthModal          : 50,
                        DataColumns         : ['TCNMPos.FTPosCode','TCNMPos.FTPosRegNo'],
                        DataColumnsFormat   : ['', ''],
                        Perpage             : 10,
                        OrderBy             : ['TCNMPos.FTPosCode ASC'],
                    },
                    CallBack    : {
                        ReturnType  : 'S',
                        Value       : [tPosInputReturnCode,"TCNMPos.FTPosCode"],
                        Text        : [tPosInputReturnName,"TCNMPos.FTPosCode"]
                    },
                    RouteAddNew: 'salemachine',
                    BrowseLev: 1,
                };
            }else{
                if((typeof(tPosRptShopForm) != 'undefined' && tPosRptShopForm == "") && (typeof(tPosRptShopTo) != 'undefined' && tPosRptShopTo == "")){
                    // เกิดขึ้นในกรณีที่ไม่ได้เลือกร้านค้าต้องแสดงทุกเครื่องจุดขายตาม Type ของรายงาน
                    var oPosOptionReturn    = {
                        Title   : ["pos/salemachine/salemachine","tPOSTitle"],
                        Table   : { Master:'TCNMPos', PK:'FTPosCode'},
                        Where   : {
                            Condition : [tPosWherePos]
                        },
                        GrideView   : {
                            ColumnPathLang      : 'pos/salemachine/salemachine',
                            ColumnKeyLang       : ['tPOSCode','tPOSRegNo'],
                            ColumnsSize         : ['40%','50%'],
                            WidthModal          : 50,
                            DataColumns         : ['TCNMPos.FTPosCode','TCNMPos.FTPosRegNo'],
                            DataColumnsFormat   : ['', ''],
                            Perpage             : 10,
                            OrderBy             : ['TCNMPos.FTPosCode ASC'],
                        },
                        CallBack    : {
                            ReturnType  : 'S',
                            Value       : [tPosInputReturnCode,"TCNMPos.FTPosCode"],
                            Text        : [tPosInputReturnName,"TCNMPos.FTPosCode"]
                        },
                        NextFunc : {
                            FuncName    : tPosNextFuncName,
                            ArgReturn   : aPosArgReturn
                        },
                        RouteAddNew: 'salemachine',
                        BrowseLev: 1,
                    };
                }else{
                    // เกิดขึ้นในกรณีที่มีการเลือกร้านค้าต้องแสดงเฉพาะ Pos ของร้าค้านั้นๆ
                    var oPosOptionReturn    = {
                        Title   : ["pos/salemachine/salemachine","tPOSTitle"],
                        Table   : { Master:'TCNMPos', PK:'FTPosCode'},
                        Join    : oPosJoinTable,
                        Where   : {
                            Condition : [tPosWherePos+tPosWherePosAndShop]
                        },
                        GrideView   : {
                            ColumnPathLang      : 'pos/salemachine/salemachine',
                            ColumnKeyLang       : ['tPOSCode','tPOSRegNo'],
                            ColumnsSize         : ['40%','50%'],
                            WidthModal          : 50,
                            DataColumns         : ['TCNMPos.FTPosCode','TCNMPos.FTPosRegNo'],
                            DataColumnsFormat   : ['', ''],
                            Perpage             : 10,
                            OrderBy             : [tPosOrderByCase],
                        },
                        CallBack    : {
                            ReturnType  : 'S',
                            Value       : [tPosInputReturnCode,"TCNMPos.FTPosCode"],
                            Text        : [tPosInputReturnName,"TCNMPos.FTPosCode"]
                        },
                        NextFunc : {
                        FuncName    : tPosNextFuncName,
                        ArgReturn   : aPosArgReturn
                        },
                        RouteAddNew: 'salemachine',
                        BrowseLev: 1,
                    };
                }
            }
            return oPosOptionReturn;
        };

        // Browse Merchant Option
        var oRptMerChantOption  = function(poReturnInputMer){
            let tMerInputReturnCode = poReturnInputMer.tReturnInputCode;
            let tMerInputReturnName = poReturnInputMer.tReturnInputName;
            let tMerNextFuncName    = poReturnInputMer.tNextFuncName;
            let aMerArgReturn       = poReturnInputMer.aArgReturn;
            let oMerOptionReturn    = {
                Title: ['company/merchant/merchant','tMerchantTitle'],
                Table: {Master:'TCNMMerchant',PK:'FTMerCode'},
                Join: {
                    Table: ['TCNMMerchant_L'],
                    On: ['TCNMMerchant.FTMerCode = TCNMMerchant_L.FTMerCode AND TCNMMerchant_L.FNLngID = '+nLangEdits]
                },
                GrideView: {
                    ColumnPathLang	: 'company/merchant/merchant',
                    ColumnKeyLang	: ['tMerCode','tMerName'],
                    ColumnsSize     : ['15%','75%'],
                    WidthModal      : 50,
                    DataColumns		: ['TCNMMerchant.FTMerCode','TCNMMerchant_L.FTMerName'],
                    DataColumnsFormat : ['',''],
                    Perpage			: 5,
                    OrderBy			: ['TCNMMerchant.FTMerCode ASC'],
                },
                CallBack: {
                    ReturnType	: 'S',
                    Value		: [tMerInputReturnCode,"TCNMMerchant.FTMerCode"],
                    Text		: [tMerInputReturnName,"TCNMMerchant_L.FTMerName"],
                },
                NextFunc : {
                    FuncName    : tMerNextFuncName,
                    ArgReturn   : aMerArgReturn
                },
                RouteAddNew: 'merchant',
                BrowseLev: 1,
            };
            return oMerOptionReturn;
        }

        // Browse Merchant Single Option
        var oRptSingleMerOption = function(poReturnInputSingleMer){
            let tMerSingleInputReturnCode   = poReturnInputSingleMer.tReturnInputCode;
            let tMerSingleInputReturnName   = poReturnInputSingleMer.tReturnInputName;
            let oMerSingleOptionReturn      = {
                Title: ['company/merchant/merchant','tMerchantTitle'],
                Table: {Master:'TCNMMerchant',PK:'FTMerCode'},
                Join: {
                    Table: ['TCNMMerchant_L'],
                    On: ['TCNMMerchant.FTMerCode = TCNMMerchant_L.FTMerCode AND TCNMMerchant_L.FNLngID = '+nLangEdits]
                },
                GrideView: {
                    ColumnPathLang	: 'company/merchant/merchant',
                    ColumnKeyLang	: ['tMerCode','tMerName'],
                    ColumnsSize     : ['15%','75%'],
                    WidthModal      : 50,
                    DataColumns		: ['TCNMMerchant.FTMerCode','TCNMMerchant_L.FTMerName'],
                    DataColumnsFormat : ['',''],
                    Perpage			: 5,
                    OrderBy			: ['TCNMMerchant.FTMerCode ASC'],
                },
                CallBack: {
                    ReturnType	: 'S',
                    Value		: [tMerSingleInputReturnCode,"TCNMMerchant.FTMerCode"],
                    Text		: [tMerSingleInputReturnName,"TCNMMerchant_L.FTMerName"],
                },
                RouteAddNew: 'merchant',
                BrowseLev: 1,
            };
            return oMerSingleOptionReturn;
        }

        // Browse Employee Option
        var oRptEmpOption       = function(poReturnInputEmp){
            let tEmpInputReturnCode = poReturnInputEmp.tReturnInputCode;
            let tEmpInputReturnName = poReturnInputEmp.tReturnInputName;
            let oEmpOptionReturn    = {
                Title: ['payment/card/card','tCRDHolderIDTiltle'],
                Table: {Master:'TFNMCard',PK:'FTCrdHolderID'},
                GrideView:{
                    ColumnPathLang	: 'payment/card/card',
                    ColumnKeyLang	: ['tCRDHolderIDCode',],
                    ColumnsSize     : ['15%','85%'],
                    WidthModal      : 50,
                    DataColumns		: ['TFNMCard.FTCrdHolderID'],
                    DisabledColumns	: [],
                    DataColumnsFormat : ['', ''],
                    Perpage			: 100,
                    OrderBy			: ['TFNMCard.FTCrdHolderID ASC'],
                },
                CallBack:{
                    ReturnType	: 'S',
                    StaSingItem : '1',
                    Value		: [tEmpInputReturnCode, "TFNMCard.FTCrdHolderID"],
                    Text		: [tEmpInputReturnName, "TFNMCard.FTCrdHolderID"]
                },
                RouteAddNew : '',
                BrowseLev : 1,
            };
            return oEmpOptionReturn;
        }

        // Browse Recive Option
        var oRptReciveOption    = function(poReturnInputRcv){
            let tRcvInputReturnCode = poReturnInputRcv.tReturnInputCode;
            let tRcvInputReturnName = poReturnInputRcv.tReturnInputName;
            let tRcvNextFuncName    = poReturnInputRcv.tNextFuncName;
            let aRcvArgReturn       = poReturnInputRcv.aArgReturn;
            let oRcvOptionReturn    = {
                Title: ['payment/recive/recive','tRCVTitle'],
                Table: {Master:'TFNMRcv',PK:'FTRcvCode'},
                Join: {
                    Table: ['TFNMRcv_L'],
                    On: ['TFNMRcv.FTRcvCode = TFNMRcv_L.FTRcvCode AND TFNMRcv_L.FNLngID = '+nLangEdits]
                },
                GrideView : {
                    ColumnPathLang	: 'payment/recive/recive',
                    ColumnKeyLang	: ['tRCVCode','tRCVName'],
                    ColumnsSize     : ['15%','75%'],
                    WidthModal      : 50,
                    DataColumns		: ['TFNMRcv.FTRcvCode','TFNMRcv_L.FTRcvName'],
                    DataColumnsFormat : ['',''],
                    Perpage			: 5,
                    OrderBy			: ['TFNMRcv.FTRcvCode ASC'],
                },
                CallBack : {
                    ReturnType	: 'S',
                    Value		: [tRcvInputReturnCode,"TFNMRcv.FTRcvCode"],
                    Text		: [tRcvInputReturnName,"TFNMRcv_L.FTRcvName"],
                },
                NextFunc : {
                    FuncName    : tRcvNextFuncName,
                    ArgReturn   : aRcvArgReturn
                },
                RouteAddNew: 'Payment',
                BrowseLev: 1,
            };
            return oRcvOptionReturn;
        }

        // Browse Product Option
        var oRptProductOption   = function(poReturnInputPdt){
            let tPdtInputReturnCode = poReturnInputPdt.tReturnInputCode;
            let tPdtInputReturnName = poReturnInputPdt.tReturnInputName;
            let tPdtNextFuncName    = poReturnInputPdt.tNextFuncName;
            let aPdtArgReturn       = poReturnInputPdt.aArgReturn;
            let oPdtOptionReturn    = {
                Title: ["product/product/product","tPDTTitle"],
                Table: { Master:"TCNMPdt", PK:"FTPdtCode"},
                Join: {
                    Table: ["TCNMPdt_L"],
                    On: ["TCNMPdt.FTPdtCode = TCNMPdt_L.FTPdtCode AND TCNMPdt_L.FNLngID = '"+nLangEdits+"'"]
                },
                Where: {
                    Condition : ["AND TCNMPdt.FTPdtForSystem = 1 AND TCNMPdt.FTPdtStaActive = 1"]
                },
                GrideView:{
                    ColumnPathLang: 'product/product/product',
                    ColumnKeyLang: ['tPDTCode','tPDTName'],
                    DataColumns: ['TCNMPdt.FTPdtCode','TCNMPdt_L.FTPdtName'],
                    DataColumnsFormat: ['',''],
                    ColumnsSize: ['15%','75%'],
                    Perpage: 5,
                    WidthModal: 50,
                    OrderBy: ['TCNMPdt.FTPdtCode ASC'],
                },
                CallBack: {
                    ReturnType  : 'S',
                    Value       : [tPdtInputReturnCode,"TCNMPdt.FTPdtCode"],
                    Text        : [tPdtInputReturnName,"TCNMPdt_L.FTPdtName"]
                },
                NextFunc : {
                    FuncName    : tPdtNextFuncName,
                    ArgReturn   : aPdtArgReturn
                },
                RouteAddNew: 'product',
                BrowseLev : 1
            };
            return oPdtOptionReturn;
        }

        // Browse Product Type Option
        var oRptPdtTypeOption   = function(poReturnInputPty){
            let tPtyInputReturnCode = poReturnInputPty.tReturnInputCode;
            let tPtyInputReturnName = poReturnInputPty.tReturnInputName;
            let tPtyNextFuncName    = poReturnInputPty.tNextFuncName;
            let aPtyArgReturn       = poReturnInputPty.aArgReturn;
            let oPtyOptionReturn    = {
                Title: ['product/pdttype/pdttype','tPTYTitle'],
                Table: {Master:'TCNMPdtType',PK:'FTPtyCode'},
                Join: {
                    Table:	['TCNMPdtType_L'],
                    On:['TCNMPdtType_L.FTPtyCode = TCNMPdtType.FTPtyCode AND TCNMPdtType_L.FNLngID = '+nLangEdits]
                },
                GrideView: {
                    ColumnPathLang	: 'company/branch/branch',
                    ColumnKeyLang	: ['tBCHCode','tBCHName'],
                    ColumnsSize     : ['15%','75%'],
                    WidthModal      : 50,
                    DataColumns		: ['TCNMPdtType.FTPtyCode','TCNMPdtType_L.FTPtyName'],
                    DataColumnsFormat : ['',''],
                    Perpage			: 10,
                    OrderBy			: ['TCNMPdtType.FTPtyCode ASC'],
                },
                CallBack: {
                    ReturnType	: 'S',
                    Value		: [tPtyInputReturnCode,"TCNMPdtType.FTPtyCode"],
                    Text		: [tPtyInputReturnName,"TCNMPdtType_L.FTPtyName"]
                },
                NextFunc : {
                    FuncName    : tPtyNextFuncName,
                    ArgReturn   : aPtyArgReturn
                },
                RouteAddNew: 'pdttype',
                BrowseLev : 1
            };
            return oPtyOptionReturn;
        }

        // Option Product Group Option
        var oRptPdtGrpOption    = function(poReturnInputPgp){
            let tPgpNextFuncName    = poReturnInputPgp.tNextFuncName;
            let aPgpArgReturn       = poReturnInputPgp.aArgReturn;
            let tPgpInputReturnCode = poReturnInputPgp.tReturnInputCode;
            let tPgpInputReturnName = poReturnInputPgp.tReturnInputName;
            let oPgpOptionReturn    = {
                Title: ['product/pdtgroup/pdtgroup','tPGPTitle'],
                Table:{Master:'TCNMPdtGrp',PK:'FTPgpChain'},
                Join :{
                    Table:	['TCNMPdtGrp_L'],
                    On:['TCNMPdtGrp_L.FTPgpChain = TCNMPdtGrp.FTPgpChain AND TCNMPdtGrp_L.FNLngID = '+nLangEdits]
                },
                GrideView:{
                    ColumnPathLang	: 'company/branch/branch',
                    ColumnKeyLang	: ['tBCHCode','tBCHName'],
                    ColumnsSize     : ['15%','75%'],
                    WidthModal      : 50,
                    DataColumns		: ['TCNMPdtGrp.FTPgpChain','TCNMPdtGrp_L.FTPgpName'],
                    DataColumnsFormat : ['',''],
                    Perpage			: 10,
                    OrderBy			: ['TCNMPdtGrp.FTPgpChain ASC'],
                },
                CallBack:{
                    ReturnType	: 'S',
                    Value		: [tPgpInputReturnCode,"TCNMPdtGrp.FTPgpChain"],
                    Text		: [tPgpInputReturnName,"TCNMPdtGrp_L.FTPgpName"]
                },
                NextFunc : {
                    FuncName    : tPgpNextFuncName,
                    ArgReturn   : aPgpArgReturn
                },
            };
            return oPgpOptionReturn;
        }

        // Option Warehouse Option
        var oRptWarehouseOption = function(poReturnInputWah){
            var tWahInputReturnCode = poReturnInputWah.tReturnInputCode;
            var tWahInputReturnName = poReturnInputWah.tReturnInputName;
            var tWahNextFuncName    = poReturnInputWah.tNextFuncName;
            var aWahArgReturn       = poReturnInputWah.aArgReturn;
            var tWahWhereCondition  = poReturnInputWah.tWhereCondition;
            var oWahOptionReturn    = {
                Title: ["company/warehouse/warehouse","tWAHTitle"],
                Table: { Master:"TCNMWaHouse", PK:"FTWahCode"},
                Join: {
                    Table: ["TCNMWaHouse_L"],
                    On: ["TCNMWaHouse.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMWaHouse_L.FNLngID = '"+nLangEdits+"'"]
                },
                Where: {
                    Condition: [tWahWhereCondition]
                },
                GrideView:{
                    ColumnPathLang: 'company/warehouse/warehouse',
                    ColumnKeyLang: ['tWahCode','tWahName'],
                    DataColumns: ['TCNMWaHouse.FTWahCode','TCNMWaHouse_L.FTWahName'],
                    DataColumnsFormat: ['',''],
                    ColumnsSize: ['15%','75%'],
                    Perpage: 5,
                    WidthModal: 50,
                    OrderBy: ['TCNMWaHouse.FTWahCode ASC'],
                },
                CallBack: {
                    ReturnType  : 'S',
                    Value       : [tWahInputReturnCode,"TCNMWaHouse.FTWahCode"],
                    Text        : [tWahInputReturnName,"TCNMWaHouse_L.FTWahName"]
                },
                NextFunc : {
                    FuncName    : tWahNextFuncName,
                    ArgReturn   : aWahArgReturn
                },
                RouteAddNew: 'warehouse',
                BrowseLev : 1
            };
            return oWahOptionReturn;
        }

        // Option Courier Option
        var oRptCourierOption   = function(poReturnInputCry){
            let tCryInputReturnCode = poReturnInputCry.tReturnInputCode;
            let tCryInputReturnName = poReturnInputCry.tReturnInputName;
            let tCryNextFuncName    = poReturnInputCry.tNextFuncName;
            let aCryArgReturn       = poReturnInputCry.aArgReturn;
            let tCryWhereCondition  = poReturnInputCry.tWhereCondition;
            let oCryOptionReturn    = {
                Title: ["courier/courier/courier","tCRYTitle"],
                Table: { Master:"TCNMCourier", PK:"FTCryCode"},
                Join: {
                    Table: ["TCNMCourier_L"],
                    On: ["TCNMCourier.FTCryCode = TCNMCourier_L.FTCryCode AND TCNMCourier_L.FNLngID = '"+nLangEdits+"'"]
                },
                Where: {
                    Condition : [tCryWhereCondition]
                },
                GrideView:{
                    ColumnPathLang: 'courier/courier/courier',
                    ColumnKeyLang: ['tCRYCode','tCRYName'],
                    DataColumns: ['TCNMCourier.FTCryCode','TCNMCourier_L.FTCryName'],
                    DataColumnsFormat: ['',''],
                    ColumnsSize: ['15%','75%'],
                    Perpage: 5,
                    WidthModal: 50,
                    OrderBy: ['TCNMCourier.FTCryCode ASC'],
                },
                CallBack: {
                    ReturnType  : 'S',
                    Value       : [tCryInputReturnCode,"TCNMCourier.FTCryCode"],
                    Text        : [tCryInputReturnName,"TCNMCourier_L.FTCryName"]
                },
                NextFunc : {
                    FuncName    : tCryNextFuncName,
                    ArgReturn   : aCryArgReturn
                },
                RouteAddNew: 'courier',
                BrowseLev : 1
            };
            return oCryOptionReturn;
        }
        
        // Option Rack Option
        var oRptRackOption      = function(poReturnInputRak){
            let tRakInputReturnCode = poReturnInputRak.tReturnInputCode;
            let tRakInputReturnName = poReturnInputRak.tReturnInputName;
            let tRakNextFuncName    = poReturnInputRak.tNextFuncName;
            let aRakArgReturn       = poReturnInputRak.aArgReturn;
            let oRakOptionReturn    = {
                Title: ['company/smartlockerlayout/smartlockerlayout','tSMLLayoutTitleGroup'],
                Table: {Master:'TRTMShopRack',PK:'FTRakCode',PKName:'FTRakCode'},
                Join: {
                    Table   : ['TRTMShopRack_L'],
                    On      : ['TRTMShopRack_L.FTRakCode = TRTMShopRack.FTRakCode AND TRTMShopRack_L.FNLngID = '+nLangEdits,]
                },
                GrideView   : {
                    ColumnPathLang	: 'company/smartlockerlayout/smartlockerlayout',
                    ColumnKeyLang	: ['tBrowseRackCode','tBrowseRackName'],
                    ColumnsSize     : ['15%','75%'],
                    WidthModal      : 50,
                    DataColumns		: ['TRTMShopRack.FTRakCode','TRTMShopRack_L.FTRakName'],
                    DataColumnsFormat : ['',''],
                    Perpage			: 5,
                    OrderBy			: ['TRTMShopRack.FTRakCode ASC'],
                },
                CallBack:{
                    ReturnType	: 'S',
                    Value		: [tRakInputReturnCode,"TRTMShopRack.FTRakCode"],
                    Text		: [tRakInputReturnName,"TRTMShopRack_L.FTRakName"],
                },
                NextFunc : {
                    FuncName    : tRakNextFuncName,
                    ArgReturn   : aRakArgReturn
                },
                RouteAddNew : 'rack',
                BrowseLev   : 1
            }
            return oRakOptionReturn;
        }



    /** ======================================================================================================================== */

    /** ================================================= Event Browse Branch ================================================== */
        // Browse Event Branch
        $('#obtRptBrowseBchFrom').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oRptBranchOptionFrom = undefined;
                oRptBranchOptionFrom        = oRptBranchOption({
                    'tReturnInputCode'  : 'oetRptBchCodeFrom',
                    'tReturnInputName'  : 'oetRptBchNameFrom',
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowseBch',
                    'aArgReturn'        : ['FTBchCode','FTBchName']
                });
                JCNxBrowseData('oRptBranchOptionFrom');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        $('#obtRptBrowseBchTo').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oRptBranchOptionTo   = undefined;
                oRptBranchOptionTo          = oRptBranchOption({
                    'tReturnInputCode'  : 'oetRptBchCodeTo',
                    'tReturnInputName'  : 'oetRptBchNameTo',
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowseBch',
                    'aArgReturn'        : ['FTBchCode','FTBchName']
                });
                JCNxBrowseData('oRptBranchOptionTo');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Browse Event Shop
        $('#obtRptBrowseShpFrom').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                let tRptModCode     = $('#ohdRptModCode').val();
                let tRptBranchForm  = $('#oetRptBchCodeFrom').val();
                let tRptBranchTo    = $('#oetRptBchCodeTo').val();
                window.oRptShopOptionFrom   = undefined;
                oRptShopOptionFrom          = oRptShopOption({
                    'tReturnInputCode'  : 'oetRptShpCodeFrom',
                    'tReturnInputName'  : 'oetRptShpNameFrom',
                    'tRptModCode'       : tRptModCode,
                    'tRptBranchForm'    : tRptBranchForm,
                    'tRptBranchTo'      : tRptBranchTo,
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowseShp',
                    'aArgReturn'        : ['FTShpCode','FTShpName']
                });
                JCNxBrowseData('oRptShopOptionFrom');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        $('#obtRptBrowseShpTo').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                let tRptModCode     = $('#ohdRptModCode').val();
                let tRptBranchForm  = $('#oetRptBchCodeFrom').val();
                let tRptBranchTo    = $('#oetRptBchCodeTo').val();
                window.oRptShopOptionTo = undefined;
                oRptShopOptionTo        = oRptShopOption({
                    'tReturnInputCode'  : 'oetRptShpCodeTo',
                    'tReturnInputName'  : 'oetRptShpNameTo',
                    'tRptModCode'       : tRptModCode,
                    'tRptBranchForm'    : tRptBranchForm,
                    'tRptBranchTo'      : tRptBranchTo,
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowseShp',
                    'aArgReturn'        : ['FTShpCode','FTShpName']
                });
                JCNxBrowseData('oRptShopOptionTo');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Browse Event Pos
        $('#obtRptBrowsePosFrom').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                var tRptModCode     = $('#ohdRptModCode').val();
                var tRptShopForm    = $('#oetRptShpCodeFrom').val();
                var tRptShopTo      = $('#oetRptShpCodeTo').val();
                window.oRptPosOptionFrom    = undefined;
                oRptPosOptionFrom           = oRptPosOption({
                    'tReturnInputCode'  : 'oetRptPosCodeFrom',
                    'tReturnInputName'  : 'oetRptPosNameFrom',
                    'tRptModCode'       : tRptModCode,
                    'tRptShopForm'      : tRptShopForm,
                    'tRptShopTo'        : tRptShopTo,
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowsePos',
                    'aArgReturn'        : ['FTPosCode','FTPosCode']
                });
                JCNxBrowseData('oRptPosOptionFrom');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        $('#obtRptBrowsePosTo').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                var tRptModCode         = $('#ohdRptModCode').val();
                var tRptShopForm        = $('#oetRptShpCodeFrom').val();
                var tRptShopTo          = $('#oetRptShpCodeTo').val();
                window.oRptPosOptionTo  = undefined;
                oRptPosOptionTo         = oRptPosOption({
                    'tReturnInputCode'  : 'oetRptPosCodeTo',
                    'tReturnInputName'  : 'oetRptPosNameTo',
                    'tRptModCode'       : tRptModCode,
                    'tRptShopForm'      : tRptShopForm,
                    'tRptShopTo'        : tRptShopTo,
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowsePos',
                    'aArgReturn'        : ['FTPosCode','FTPosCode']
                });
                JCNxBrowseData('oRptPosOptionTo');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Browse Event MerChant
        $('#obtRptBrowseMerFrom').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oRptMerChantOptionFrom   = undefined;
                oRptMerChantOptionFrom          = oRptMerChantOption({
                    'tReturnInputCode'  : 'oetRptMerCodeFrom',
                    'tReturnInputName'  : 'oetRptMerNameFrom',
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowseMerChant',
                    'aArgReturn'        : ['FTMerCode','FTMerName']
                });
                JCNxBrowseData('oRptMerChantOptionFrom');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        $('#obtRptBrowseMerTo').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oRptMerChantOptionTo = undefined;
                oRptMerChantOptionTo        = oRptMerChantOption({
                    'tReturnInputCode'  : 'oetRptMerCodeTo',
                    'tReturnInputName'  : 'oetRptMerNameTo',
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowseMerChant',
                    'aArgReturn'        : ['FTMerCode','FTMerName']
                });
                JCNxBrowseData('oRptMerChantOptionTo');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Browse Event Employee
        $('#obtRptBrowseEmpFrom').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oRptEmpOptionFrom    = undefined;
                oRptEmpOptionFrom           = oRptEmpOption({
                    'tReturnInputCode'  : 'oetRptEmpCodeFrom',
                    'tReturnInputName'  : 'oetRptEmpNameFrom'
                });
                JCNxBrowseData('oRptEmpOptionFrom');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        $('#obtRptBrowseEmpTo').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oRptEmpOptionTo  = undefined;
                oRptEmpOptionTo         = oRptEmpOption({
                    'tReturnInputCode'  : 'oetRptEmpCodeTo',
                    'tReturnInputName'  : 'oetRptEmpNameTo'
                });
                JCNxBrowseData('oRptEmpOptionTo');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Browse Event Recive
        $('#obtRptBrowseRcvFrom').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oRptReciveOptionFrom = undefined;
                oRptReciveOptionFrom        = oRptReciveOption({
                    'tReturnInputCode'  : 'oetRptRcvCodeFrom',
                    'tReturnInputName'  : 'oetRptRcvNameFrom',
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowseRcv',
                    'aArgReturn'        : ['FTRcvCode','FTRcvName']
                });
                JCNxBrowseData('oRptReciveOptionFrom');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        $('#obtRptBrowseRcvTo').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oRptReciveOptionTo   = undefined;
                oRptReciveOptionTo          = oRptReciveOption({
                    'tReturnInputCode'  : 'oetRptRcvCodeTo',
                    'tReturnInputName'  : 'oetRptRcvNameTo',
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowseRcv',
                    'aArgReturn'        : ['FTRcvCode','FTRcvName']
                });
                JCNxBrowseData('oRptReciveOptionTo');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Browse Event Product
        $('#obtRptBrowsePdtFrom').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oRptProductFromOption    = undefined;
                oRptProductFromOption           = oRptProductOption({
                    'tReturnInputCode'  : 'oetRptPdtCodeFrom',
                    'tReturnInputName'  : 'oetRptPdtNameFrom',
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowsePdt',
                    'aArgReturn'        : ['FTPdtCode','FTPdtName']
                });
                JCNxBrowseData('oRptProductFromOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        $('#obtRptBrowsePdtTo').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oRptProductToOption  = undefined;
                oRptProductToOption         = oRptProductOption({
                    'tReturnInputCode'  : 'oetRptPdtCodeTo',
                    'tReturnInputName'  : 'oetRptPdtNameTo',
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowsePdt',
                    'aArgReturn'        : ['FTPdtCode','FTPdtName']
                    
                });
                JCNxBrowseData('oRptProductToOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Browse Event ProductType
        $('#obtRptBrowsePdtTypeFrom').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oRptPdtTypeOptionFrom    = undefined;
                oRptPdtTypeOptionFrom   = oRptPdtTypeOption({
                    'tReturnInputCode'  : 'oetRptPdtTypeCodeFrom',
                    'tReturnInputName'  : 'oetRptPdtTypeNameFrom',
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowsePdtType',
                    'aArgReturn'        : ['FTPtyCode','FTPtyName']

                });
                JCNxBrowseData('oRptPdtTypeOptionFrom');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        $('#obtRptBrowsePdtTypeTo').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oRptPdtTypeOptionTo  = undefined;
                oRptPdtTypeOptionTo         = oRptPdtTypeOption({
                    'tReturnInputCode'  : 'oetRptPdtTypeCodeTo',
                    'tReturnInputName'  : 'oetRptPdtTypeNameTo',
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowsePdtType',
                    'aArgReturn'        : ['FTPtyCode','FTPtyName']
                });
                JCNxBrowseData('oRptPdtTypeOptionTo');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Browse Event ProductGroup
        $('#obtRptBrowsePdtGrpFrom').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oRptPdtGrpOptionFrom = undefined;
                oRptPdtGrpOptionFrom        = oRptPdtGrpOption({
                    'tReturnInputCode'  : 'oetRptPdtGrpCodeFrom',
                    'tReturnInputName'  : 'oetRptPdtGrpNameFrom',
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowsePdtGrp',
                    'aArgReturn'        : ['FTPgpChain','FTPgpName']
                });
                JCNxBrowseData('oRptPdtGrpOptionFrom');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        $('#obtRptBrowsePdtGrpTo').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oRptPdtGrpOptionTo   = undefined;
                oRptPdtGrpOptionTo          = oRptPdtGrpOption({
                    'tReturnInputCode'  : 'oetRptPdtGrpCodeTo',
                    'tReturnInputName'  : 'oetRptPdtGrpNameTo',
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowsePdtGrp',
                    'aArgReturn'        : ['FTPgpChain','FTPgpName']
                });
                JCNxBrowseData('oRptPdtGrpOptionTo');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Clck Button Warehouse From-To
        $('#obtRptBrowseWahFrom').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                let tWhereCondition = "";
                let tRptBchCodeFrom     = $('#oetRptBchCodeFrom').val();
                let tRptBchCodeTo       = $('#oetRptBchCodeTo').val();
                let tRptShopCodeFrom    = $('#oetRptShpCodeFrom').val();
                let tRptShopCodeTo      = $('#oetRptShpCodeTo').val();
                let tRptPosCodeFrom     = $('#oetRptPosCodeFrom').val();
                let tRptPosCodeTo       = $('#oetRptPosCodeTo').val();
                
                // เช็คในกรณีเลือกเฉพาะคลังสาขา
                if((tRptBchCodeFrom != 'undefined' && tRptBchCodeFrom != "") && (tRptBchCodeTo != 'undefined' && tRptBchCodeTo != "")){
                    tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (1,2,5))";
                }

                // เช็คในกรณีเลือกเฉพาะร้านค้า
                if((tRptShopCodeFrom != 'undefined' && tRptShopCodeFrom != "") && (tRptShopCodeTo != 'undefined' && tRptShopCodeTo != "")){
                    tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (4))";
                }

                // เช็คในกรณีเลือกเฉพาะร้านค้า
                if((tRptPosCodeFrom != 'undefined' && tRptPosCodeFrom != "") && (tRptPosCodeTo != 'undefined' && tRptPosCodeTo != "")){
                    tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (6))";
                }
                window.oRptWarehouseFromOption  = undefined;
                oRptWarehouseFromOption         = oRptWarehouseOption({
                    'tReturnInputCode'  : 'oetRptWahCodeFrom',
                    'tReturnInputName'  : 'oetRptWahNameFrom',
                    'tWhereCondition'   : tWhereCondition,
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowseWahFrom',
                    'aArgReturn'        : ['FTWahCode','FTWahName']
                });
                JCNxBrowseData('oRptWarehouseFromOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        $('#obtRptBrowseWahTo').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                let tWhereCondition = "";
                let tRptBchCodeFrom     = $('#oetRptBchCodeFrom').val();
                let tRptBchCodeTo       = $('#oetRptBchCodeTo').val();
                let tRptShopCodeFrom    = $('#oetRptShpCodeFrom').val();
                let tRptShopCodeTo      = $('#oetRptShpCodeTo').val();
                let tRptPosCodeFrom     = $('#oetRptPosCodeFrom').val();
                let tRptPosCodeTo       = $('#oetRptPosCodeTo').val();
                
                // เช็คในกรณีเลือกเฉพาะคลังสาขา
                if((tRptBchCodeFrom != 'undefined' && tRptBchCodeFrom != "") && (tRptBchCodeTo != 'undefined' && tRptBchCodeTo != "")){
                    tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (1,2,5))";
                }

                // เช็คในกรณีเลือกเฉพาะร้านค้า
                if((tRptShopCodeFrom != 'undefined' && tRptShopCodeFrom != "") && (tRptShopCodeTo != 'undefined' && tRptShopCodeTo != "")){
                    tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (4))";
                }

                // เช็คในกรณีเลือกเฉพาะร้านค้า
                if((tRptPosCodeFrom != 'undefined' && tRptPosCodeFrom != "") && (tRptPosCodeTo != 'undefined' && tRptPosCodeTo != "")){
                    tWhereCondition = " AND (TCNMWaHouse.FTWahStaType IN (6))";
                }
                window.oRptWarehouseToOption    = undefined;
                oRptWarehouseToOption           = oRptWarehouseOption({
                    'tReturnInputCode'  : 'oetRptWahCodeTo',
                    'tReturnInputName'  : 'oetRptWahNameTo',
                    'tWhereCondition'   : tWhereCondition,
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowseWahFrom',
                    'aArgReturn'        : ['FTWahCode','FTWahName']
                });
                JCNxBrowseData('oRptWarehouseToOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Clck Button Courier From-To
        $('#obtRptBrowseCourierFrom').unbind().click(function(){
            let nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                let tWhereCondition             = "AND FTCryStaActive = 1";
                window.oRptCourierFromOption    = undefined;
                oRptCourierFromOption           = oRptCourierOption({
                    'tReturnInputCode'  : 'oetRptCourierCodeFrom',
                    'tReturnInputName'  : 'oetRptCourierNameFrom',
                    'tWhereCondition'   : tWhereCondition,
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowseCourier',
                    'aArgReturn'        : ['FTCryCode','FTCryName']
                });
                JCNxBrowseData('oRptCourierFromOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        $('#obtRptBrowseCourierTo').unbind().click(function(){
            let nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                let tWhereCondition = "AND FTCryStaActive = 1";
                window.oRptCourierToOption  = undefined;
                oRptCourierToOption         = oRptCourierOption({
                    'tReturnInputCode'  : 'oetRptCourierCodeTo',
                    'tReturnInputName'  : 'oetRptCourierNameTo',
                    'tWhereCondition'   : tWhereCondition,
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowseCourier',
                    'aArgReturn'        : ['FTCryCode','FTCryName']
                });
                JCNxBrowseData('oRptCourierToOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        
        // Click Button Single MerChant
        $('#obtRptBrowseMerchant').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oRptSingleMerChantOption = undefined;
                oRptSingleMerChantOption        = oRptSingleMerOption({
                    'tReturnInputCode'  : 'oetRptMerchantCode',
                    'tReturnInputName'  : 'oetRptMerchantName'
                });
                JCNxBrowseData('oRptSingleMerChantOption');
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // CLick Button Rack From - To
        $('#obtSMLBrowseGroupFrom').click(function(){ 
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oRptRackOptionFrom   = undefined;
                oRptRackOptionFrom          = oRptRackOption({
                    'tReturnInputCode'  : 'oetSMLBrowseGroupCodeFrom',
                    'tReturnInputName'  : 'oetSMLBrowseGroupNameFrom',
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowseRack',
                    'aArgReturn'        : ['FTRakCode','FTRakName']
                });
                JCNxBrowseData('oRptRackOptionFrom'); 
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
        $('#obtSMLBrowseGroupTo').click(function(){ 
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                JSxCheckPinMenuClose(); // Hidden Pin Menu
                window.oRptRackOptionTo = undefined;
                oRptRackOptionTo        = oRptRackOption({
                    'tReturnInputCode'  : 'oetSMLBrowseGroupCodeTo',
                    'tReturnInputName'  : 'oetSMLBrowseGroupNameTo',
                    'tNextFuncName'     : 'JSxRptConsNextFuncBrowseRack',
                    'aArgReturn'        : ['FTRakCode','FTRakName']
                });
                JCNxBrowseData('oRptRackOptionTo'); 
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

    /** ======================================================================================================================== */
    
    /** ============================================ Event Next Function Browse ================================================ */
        // Functionality : Next Function Branch And Check Data Shop And Clear Data
        // Parameter : Event Next Func Modal
        // Create : 30/09/2019 Wasin(Yoshi)
        // update : 03/10/2019 Saharat(Golf)
        // Return : Clear Velues Data
        // Return Type : -
        function JSxRptConsNextFuncBrowseBch(poDataNextFunc){
            if(typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL"){
                var aDataNextFunc   = JSON.parse(poDataNextFunc);
                tBchCode      = aDataNextFunc[0];
                tBchName      = aDataNextFunc[1];
            }

            //ประกาศตัวแปร สาขา
            var tRptBchCodeFrom,tRptBchNameFrom,tRptBchCodeTo,tRptBchNameTo
            tRptBchCodeFrom = $('#oetRptBchCodeFrom').val();
            tRptBchNameFrom = $('#oetRptBchNameFrom').val();
            tRptBchCodeTo   = $('#oetRptBchCodeTo').val();
            tRptBchNameTo   = $('#oetRptBchNameTo').val();

            // เช็คข้อมูลถ้ามีการ Browse จากสาขา ให้ default ถึงสาขา เป็นข้อมูลเดียวกัน 
            if((typeof(tRptBchCodeFrom) !== 'undefined' && tRptBchCodeFrom != "") && (typeof(tRptBchCodeTo) !== 'undefined' && tRptBchCodeTo == "")){
                $('#oetRptBchCodeTo').val(tBchCode);
                $('#oetRptBchNameTo').val(tBchName);
            } 

            // เช็คข้อมูลถ้ามีการ Browse ถึงสาขาให้ default จากสาขา  เป็นข้อมูลเดียวกัน 
            if((typeof(tRptBchCodeTo) !== 'undefined' && tRptBchCodeTo != "") && (typeof(tRptBchCodeFrom) !== 'undefined' && tRptBchCodeFrom == "")){
                $('#oetRptBchCodeFrom').val(tBchCode);
                $('#oetRptBchNameFrom').val(tBchName);
            } 

            var tRptShopCodeFrom,tRptShopCodeTo
            tRptShopCodeFrom    = $('#oetRptShpCodeFrom').val();
            tRptShopCodeTo      = $('#oetRptShpCodeTo').val();
            if((typeof(tRptShopCodeFrom) !== 'undefined' && tRptShopCodeFrom != "") && (typeof(tRptShopCodeTo) !== 'undefined' && tRptShopCodeTo != "")){
                $('#oetRptShpCodeFrom').val('');
                $('#oetRptShpNameFrom').val('');
                $('#oetRptShpCodeTo').val('');
                $('#oetRptShpNameTo').val('');
            }
        }

        // Functionality : Next Function Shop And Check Data Pos And Clear Data
        // Parameter : Event Next Func Modal
        // Create : 30/09/2019 Wasin(Yoshi)
        // update : 03/10/2019 Sahart(Golf)
        // Return : Clear Velues Data
        // Return Type : -
        function JSxRptConsNextFuncBrowseShp(poDataNextFunc){

            if(typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL"){
                var aDataNextFunc   = JSON.parse(poDataNextFunc);
                tSphCode      = aDataNextFunc[0];
                tShpName      = aDataNextFunc[1];
            }

            //ประกาศตัวแปร ร้านค้า
            var tRptShpCodeFrom,tRptShpNameFrom,tRptShpCodeTo,tRptShpNameTo
            tRptShpCodeFrom = $('#oetRptShpCodeFrom').val();
            tRptShpNameFrom = $('#oetRptShpNameFrom').val();
            tRptShpCodeTo   = $('#oetRptShpCodeTo').val();
            tRptShpNameTo   = $('#oetRptShpNameTo').val();

            // เช็คข้อมูลถ้ามีการ Browse จากร้านค้า ให้ default ถึงร้านค้า เป็นข้อมูลเดียวกัน 
            if((typeof(tRptShpCodeFrom) !== 'undefined' && tRptShpCodeFrom != "") && (typeof(tRptShpCodeTo) !== 'undefined' && tRptShpCodeTo == "")){
                $('#oetRptShpCodeTo').val(tSphCode);
                $('#oetRptShpNameTo').val(tShpName);
            } 

            // เช็คข้อมูลถ้ามีการ Browse ถึงร้านค้า default  จากร้านค้า เป็นข้อมูลเดียวกัน 
            if((typeof(tRptShpCodeTo) !== 'undefined' && tRptShpCodeTo != "") && (typeof(tRptShpCodeFrom) !== 'undefined' && tRptShpCodeFrom == "")){
                $('#oetRptShpCodeFrom').val(tSphCode);
                $('#oetRptShpNameFrom').val(tShpName);
            } 

            var tRptPosCodeFrom,tRptPosCodeTo
            tRptPosCodeFrom = $('#oetRptPosCodeFrom').val();
            tRptPosCodeTo   = $('#oetRptPosCodeTo').val();
            if((typeof(tRptPosCodeFrom) !== 'undefined' && tRptPosCodeFrom != "") && (typeof(tRptPosCodeTo) !== 'undefined' && tRptPosCodeTo != "")){
                $('#oetRptPosCodeFrom').val('');
                $('#oetRptPosNameFrom').val('');
                $('#oetRptPosCodeTo').val('');
                $('#oetRptPosNameTo').val('');
            }
        }

        // Functionality : Next Function Product And Check Data 
        // Parameter : Event Next Func Modal
        // Create : 03/10/2019 Saharat(Golf)
        // Return : Clear Velues Data
        // Return Type : -
        function  JSxRptConsNextFuncBrowsePdt(poDataNextFunc){

            if(typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL"){
                var aDataNextFunc   = JSON.parse(poDataNextFunc);
                tPdtCode      = aDataNextFunc[0];
                tPdtName      = aDataNextFunc[1];
            }

            //ประกาศตัวแปร สินค้า
            var tRptPdtCodeFrom,tRptPdtNameFrom,tRptPdtCodeTo,tRptPdtNameTo
            tRptPdtCodeFrom = $('#oetRptPdtCodeFrom').val();
            tRptPdtNameFrom = $('#oetRptPdtNameFrom').val();
            tRptPdtCodeTo   = $('#oetRptPdtCodeTo').val();
            tRptPdtNameTo   = $('#oetRptPdtNameTo').val();

            // เช็คข้อมูลถ้ามีการ Browse จากสินค้า ให้ default ถึงร้านค้า เป็นข้อมูลเดียวกัน 
            if((typeof(tRptPdtCodeFrom) !== 'undefined' && tRptPdtCodeFrom != "") && (typeof(tRptPdtCodeTo) !== 'undefined' && tRptPdtCodeTo == "")){
                $('#oetRptPdtCodeTo').val(tPdtCode);
                $('#oetRptPdtNameTo').val(tPdtName);
            } 

            // เช็คข้อมูลถ้ามีการ Browse ถึงร้านค้า default จากสินค้า เป็นข้อมูลเดียวกัน 
            if((typeof(tRptPdtCodeTo) !== 'undefined' && tRptPdtCodeTo != "") && (typeof(tRptPdtCodeFrom) !== 'undefined' && tRptPdtCodeFrom == "")){
                $('#oetRptPdtCodeFrom').val(tPdtCode);
                $('#oetRptPdtNameFrom').val(tPdtName);
            } 

        }

        // Functionality : Next Function MerChant And Check Data 
        // Parameter : Event Next Func Modal
        // Create : 04/10/2019 Saharat(Golf)
        // Return : Clear Velues Data
        // Return Type : -
        function  JSxRptConsNextFuncBrowseMerChant(poDataNextFunc){

            if(typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL"){
                var aDataNextFunc   = JSON.parse(poDataNextFunc);
                tMerCode      = aDataNextFunc[0];
                tMerName      = aDataNextFunc[1];
            }

            //ประกาศตัวแปร กลุ่มธุรกิจ
            var tRptMerCodeFrom,tRptMerNameFrom,tRptMerCodeTo,tRptPdtNameTo
            tRptMerCodeFrom = $('#oetRptMerCodeFrom').val();
            tRptMerNameFrom = $('#oetRptMerNameFrom').val();
            tRptMerCodeTo   = $('#oetRptMerCodeTo').val();
            tRptMerNameTo   = $('#oetRptMerNameTo').val();

            // เช็คข้อมูลถ้ามีการ Browse จากกลุ่มธุรกิจ ให้ default ถึงกลุ่มธุรกิจ เป็นข้อมูลเดียวกัน 
            if((typeof(tRptMerCodeFrom) !== 'undefined' && tRptMerCodeFrom != "") && (typeof(tRptMerCodeTo) !== 'undefined' && tRptMerCodeTo == "")){
                $('#oetRptMerCodeTo').val(tMerCode);
                $('#oetRptMerNameTo').val(tMerName);
            } 

            // เช็คข้อมูลถ้ามีการ Browse ถึงกลุ่มธุรกิจ default จากกลุ่มธุรกิจ  เป็นข้อมูลเดียวกัน 
            if((typeof(tRptMerCodeTo) !== 'undefined' && tRptMerCodeTo != "") && (typeof(tRptMerCodeFrom) !== 'undefined' && tRptMerCodeFrom == "")){
                $('#oetRptMerCodeFrom').val(tMerCode);
                $('#oetRptMerNameFrom').val(tMerName);
            } 

        }

        //เช็คการเปลี่ยนค่าของ DateFrom
        $("#oetRptDocDateFrom").change(function(){
            
            var dDateFrom,dDateTo
            dDateFrom  = $('#oetRptDocDateFrom').val();
            dDateTo    = $('#oetRptDocDateTo').val();

            //เช็ควันที่ถ้ามีการ Browse จากวันที่ default ถึงวันที่ เป็นวันที่เดียวกัน 
            if((typeof(dDateFrom) !== 'undefined' && dDateFrom != "") && (typeof(dDateTo) !== 'undefined' && dDateTo == "")){
                $('#oetRptDocDateTo').val(dDateFrom);
            }

        });

        //เช็คการเปลี่ยนค่าของ DateTo
        $("#oetRptDocDateTo").change(function(){
            
            var dDateTo,dDateFrom
            dDateTo    = $('#oetRptDocDateTo').val();
            dDateFrom  = $('#oetRptDocDateFrom').val();

            //เช็ควันที่ถ้ามีการ Browse ถึงวันที่ default จากวันที่ เป็นวันที่เดียวกัน 
            if((typeof(dDateTo) !== 'undefined' && dDateTo != "") && (typeof(dDateFrom) !== 'undefined' && dDateFrom == "")){
                $('#oetRptDocDateFrom').val(dDateTo);
            }
            
        });

        // Functionality : Next Function ProductGroup And Check Data 
        // Parameter : Event Next Func Modal
        // Create : 04/10/2019 Saharat(Golf)
        // Return : Clear Velues Data
        // Return Type : -
        function  JSxRptConsNextFuncBrowsePdtGrp(poDataNextFunc){

            if(typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL"){
                var aDataNextFunc   = JSON.parse(poDataNextFunc);
                tPdtGrpCode      = aDataNextFunc[0];
                tPdtGrpName      = aDataNextFunc[1];
            }

            //ประกาศตัวแปร กลุ่มสินค้า
            var tRptPdtGrpCodeFrom,tRptPdtGrpNameFrom,tRptPdtGrpCodeTo,tRptPdtGrpNameTo
            tRptPdtGrpCodeFrom = $('#oetRptPdtGrpCodeFrom').val();
            tRptPdtGrpNameFrom = $('#oetRptPdtGrpNameFrom').val();
            tRptPdtGrpCodeTo   = $('#oetRptPdtGrpCodeTo').val();
            tRptPdtGrpNameTo   = $('#oetRptPdtGrpNameTo').val();

            // เช็คข้อมูลถ้ามีการ Browse จากกลุ่มสินค้า ให้ default ถึงกลุ่มสินค้า เป็นข้อมูลเดียวกัน 
            if((typeof(tRptPdtGrpCodeFrom) !== 'undefined' && tRptPdtGrpCodeFrom != "") && (typeof(tRptPdtGrpCodeTo) !== 'undefined' && tRptPdtGrpCodeTo == "")){
                $('#oetRptPdtGrpCodeTo').val(tPdtGrpCode);
                $('#oetRptPdtGrpNameTo').val(tPdtGrpName);
            } 

            // เช็คข้อมูลถ้ามีการ Browse ถึงกลุ่มสินค้า default จากกลุ่มสินค้า เป็นข้อมูลเดียวกัน 
            if((typeof(tRptPdtGrpCodeTo) !== 'undefined' && tRptPdtGrpCodeTo != "") && (typeof(tRptPdtGrpCodeFrom) !== 'undefined' && tRptPdtGrpCodeFrom == "")){
                $('#oetRptPdtGrpCodeFrom').val(tPdtGrpCode);
                $('#oetRptPdtGrpNameFrom').val(tPdtGrpName);
            } 

        }

        // Functionality : Next Function ProductType And Check Data 
        // Parameter : Event Next Func Modal
        // Create : 04/10/2019 Saharat(Golf)
        // Return : Clear Velues Data
        // Return Type : -
        function  JSxRptConsNextFuncBrowsePdtType(poDataNextFunc){

            if(typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL"){
                let aDataNextFunc   = JSON.parse(poDataNextFunc);
                tPdtTypeCode      = aDataNextFunc[0];
                tPdtTypeName      = aDataNextFunc[1];
            }

            //ประกาศตัวแปร ประเภทสินค้า
            var tRptPdtTypeCodeFrom,tRptPdtTypeNameFrom,tRptPdtTypeCodeTo,tRptPdtTypeNameTo
            tRptPdtTypeCodeFrom = $('#oetRptPdtTypeCodeFrom').val();
            tRptPdtTypeNameFrom = $('#oetRptPdtTypeNameFrom').val();
            tRptPdtTypeCodeTo   = $('#oetRptPdtTypeCodeTo').val();
            tRptPdtTypeNameTo   = $('#oetRptPdtTypeNameTo').val();

            // เช็คข้อมูลถ้ามีการ Browse จากประเภทสินค้า ให้ default ถึงประเภทสินค้า เป็นข้อมูลเดียวกัน 
            if((typeof(tRptPdtTypeCodeFrom) !== 'undefined' && tRptPdtTypeCodeFrom != "") && (typeof(tRptPdtTypeCodeTo) !== 'undefined' && tRptPdtTypeCodeTo == "")){
                $('#oetRptPdtTypeCodeTo').val(tPdtTypeCode);
                $('#oetRptPdtTypeNameTo').val(tPdtTypeName);
            } 

            // เช็คข้อมูลถ้ามีการ Browse ถึงประเภทสินค้า default จากประเภทสินค้า เป็นข้อมูลเดียวกัน 
            if((typeof(tRptPdtTypeCodeTo) !== 'undefined' && tRptPdtTypeCodeTo != "") && (typeof(tRptPdtTypeCodeFrom) !== 'undefined' && tRptPdtTypeCodeFrom == "")){
                $('#oetRptPdtTypeCodeFrom').val(tPdtTypeCode);
                $('#oetRptPdtTypeNameFrom').val(tPdtTypeName);
            } 

        }

        // Functionality : Next Function Receive And Check Data 
        // Parameter : Event Next Func Modal
        // Create : 04/10/2019 Saharat(Golf)
        // Return : Clear Velues Data
        // Return Type : -
        function  JSxRptConsNextFuncBrowseRcv(poDataNextFunc){

            if(typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL"){
                var aDataNextFunc   = JSON.parse(poDataNextFunc);
                tRcvCode      = aDataNextFunc[0];
                tRcvName      = aDataNextFunc[1];
            }

            //ประกาศตัวแปร ประเภทการชำระเงิน
            var tRptRcvCodeFrom,tRptRcvNameFrom,tRptRcvCodeTo,tRptRcvNameTo
            tRptRcvCodeFrom = $('#oetRptRcvCodeFrom').val();
            tRptRcvNameFrom = $('#oetRptRcvNameFrom').val();
            tRptRcvCodeTo   = $('#oetRptRcvCodeTo').val();
            tRptRcvNameTo   = $('#oetRptRcvNameTo').val();

            // เช็คข้อมูลถ้ามีการ Browse จากประเภทชำระเงิน ให้ default ถึงประเภทชำระเงิน เป็นข้อมูลเดียวกัน 
            if((typeof(tRptRcvCodeFrom) !== 'undefined' && tRptRcvCodeFrom != "") && (typeof(tRptRcvCodeTo) !== 'undefined' && tRptRcvCodeTo == "")){
                $('#oetRptRcvCodeTo').val(tRcvCode);
                $('#oetRptRcvNameTo').val(tRcvName);
            } 

            // เช็คข้อมูลถ้ามีการ Browse ถึงประเภทชำระเงิน default จากประเภทชำระเงิน เป็นข้อมูลเดียวกัน 
            if((typeof(tRptRcvCodeTo) !== 'undefined' && tRptRcvCodeTo != "") && (typeof(tRptRcvCodeFrom) !== 'undefined' && tRptRcvCodeFrom == "")){
                $('#oetRptRcvCodeFrom').val(tRcvCode);
                $('#oetRptRcvNameFrom').val(tRcvName);
            } 

        }

        // Functionality : Next Function warehouse And Check Data 
        // Parameter : Event Next Func Modal
        // Create : 04/10/2019 Saharat(Golf)
        // Return : Clear Velues Data
        // Return Type : -
        function  JSxRptConsNextFuncBrowseWahFrom(poDataNextFunc){

            if(typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL"){
                var aDataNextFunc   = JSON.parse(poDataNextFunc);
                tWahCode      = aDataNextFunc[0];
                tWahName      = aDataNextFunc[1];
            }

            //ประกาศตัวแปร คลังสินค้า
            var tRptWahCodeFrom,tRptWahNameFrom,tRptWahCodeTo,tRptWahNameTo
            tRptWahCodeFrom = $('#oetRptWahCodeFrom').val();
            tRptWahNameFrom = $('#oetRptWahNameFrom').val();
            tRptWahCodeTo   = $('#oetRptWahCodeTo').val();
            tRptWahNameTo   = $('#oetRptWahNameTo').val();

            // เช็คข้อมูลถ้ามีการ Browse จากคลังสินค้า ให้ default ถึงคลังสินค้า เป็นข้อมูลเดียวกัน 
            if((typeof(tRptWahCodeFrom) !== 'undefined' && tRptWahCodeFrom != "") && (typeof(tRptWahCodeTo) !== 'undefined' && tRptWahCodeTo == "")){
                $('#oetRptWahCodeTo').val(tWahCode);
                $('#oetRptWahNameTo').val(tWahName);
            } 

            // เช็คข้อมูลถ้ามีการ Browse ถึงคลังสินค้า default จากคลังสินค้า เป็นข้อมูลเดียวกัน 
            if((typeof(tRptWahCodeTo) !== 'undefined' && tRptWahCodeTo != "") && (typeof(tRptWahCodeFrom) !== 'undefined' && tRptWahCodeFrom == "")){
                $('#oetRptWahCodeFrom').val(tWahCode);
                $('#oetRptWahNameFrom').val(tWahName);
            } 

        }

        // Functionality : Next Function PosShop And Check Data 
        // Parameter : Event Next Func Modal
        // Create : 04/10/2019 Saharat(Golf)
        // Return : Clear Velues Data
        // Return Type : -
        function  JSxRptConsNextFuncBrowsePos(poDataNextFunc){

            if(typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL"){
                var aDataNextFunc   = JSON.parse(poDataNextFunc);
                tPosCode      = aDataNextFunc[0];
                tPosCode      = aDataNextFunc[1];
            }

            //ประกาศตัวแปร เครื่องจุดขาย
            var tRptPosCodeFrom,tRptPosNameFrom,tRptPosCodeTo,tRptPosNameTo
            tRptPosCodeFrom = $('#oetRptPosCodeFrom').val();
            tRptPosNameFrom = $('#oetRptPosNameFrom').val();
            tRptPosCodeTo   = $('#oetRptPosCodeTo').val();
            tRptPosNameTo   = $('#oetRptPosNameTo').val();

            // เช็คข้อมูลถ้ามีการ Browse จากเครื่องจุดขาย ให้ default ถึงเครื่องจุดขาย เป็นข้อมูลเดียวกัน 
            if((typeof(tRptPosCodeFrom) !== 'undefined' && tRptPosCodeFrom != "") && (typeof(tRptPosCodeTo) !== 'undefined' && tRptPosCodeTo == "")){
                $('#oetRptPosCodeTo').val(tPosCode);
                $('#oetRptPosNameTo').val(tPosCode);
            } 

            // เช็คข้อมูลถ้ามีการ Browse ถึงเครื่องจุดขาย default จากเครื่องจุดขาย เป็นข้อมูลเดียวกัน 
            if((typeof(tRptPosCodeTo) !== 'undefined' && tRptPosCodeTo != "") && (typeof(tRptPosCodeFrom) !== 'undefined' && tRptPosCodeFrom == "")){
                $('#oetRptPosCodeFrom').val(tPosCode);
                $('#oetRptPosNameFrom').val(tPosCode);
            } 

        }

        // Functionality : Next Function Courier And Check Data 
        // Parameter : Event Next Func Modal
        // Create : 04/10/2019 Saharat(Golf)
        // Return : Clear Velues Data
        // Return Type : -
        function  JSxRptConsNextFuncBrowseCourier(poDataNextFunc){

            if(typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL"){
                var aDataNextFunc   = JSON.parse(poDataNextFunc);
                tCryCode      = aDataNextFunc[0];
                tCryName      = aDataNextFunc[1];
            }

            //ประกาศตัวแปร บริษัทขนส่ง
            var tRptCourierCodeFrom,tRptCourierNameFrom,tRptCourierCodeTo,tRptCourierNameTo
            tRptCourierCodeFrom = $('#oetRptCourierCodeFrom').val();
            tRptCourierNameFrom = $('#oetRptCourierNameFrom').val();
            tRptCourierCodeTo   = $('#oetRptCourierCodeTo').val();
            tRptCourierNameTo   = $('#oetRptCourierNameTo').val();

            // เช็คข้อมูลถ้ามีการ Browse จากบริษัทขนส่ง ให้ default ถึงบริษัทขนส่ง เป็นข้อมูลเดียวกัน 
            if((typeof(tRptCourierCodeFrom) !== 'undefined' && tRptCourierCodeFrom != "") && (typeof(tRptCourierCodeTo) !== 'undefined' && tRptCourierCodeTo == "")){
                $('#oetRptCourierCodeTo').val(tCryCode);
                $('#oetRptCourierNameTo').val(tCryName);
            } 

            // เช็คข้อมูลถ้ามีการ Browse ถึงบริษัทขนส่ง default จากบริษัทขนส่ง เป็นข้อมูลเดียวกัน 
            if((typeof(tRptCourierCodeTo) !== 'undefined' && tRptCourierCodeTo != "") && (typeof(tRptCourierCodeFrom) !== 'undefined' && tRptCourierCodeFrom == "")){
                $('#oetRptCourierCodeFrom').val(tCryCode);
                $('#oetRptCourierNameFrom').val(tCryName);
            } 

        }

        // Functionality : Next Function Rack And Check Data 
        // Parameter : Event Next Func Modal
        // Create : 16/10/2019 Wasin(Yoshi)
        // Return : Clear Velues Data
        // Return Type : -
        function  JSxRptConsNextFuncBrowseRack(poDataNextFunc){
            if(typeof(poDataNextFunc) != 'undefined' && poDataNextFunc != "NULL"){
                var aDataNextFunc   = JSON.parse(poDataNextFunc);
                tRakCode    = aDataNextFunc[0];
                tRakName    = aDataNextFunc[1];
            }

            //ประกาศตัวแปร บริษัทขนส่ง
            var tRptRackCodeFrom,tRptRackNameFrom,tRptRackCodeTo,tRptRackNameTo

            tRptRackCodeFrom    = $('#oetSMLBrowseGroupCodeFrom').val();
            tRptRackNameFrom    = $('#oetSMLBrowseGroupNameFrom').val();
            tRptRackCodeTo      = $('#oetSMLBrowseGroupCodeTo').val();
            tRptRackNameTo      = $('#oetSMLBrowseGroupNameTo').val();

            // เช็คข้อมูลถ้ามีการ Browse จากบริษัทขนส่ง ให้ default ถึงบริษัทขนส่ง เป็นข้อมูลเดียวกัน 
            if((typeof(tRptRackCodeFrom) !== 'undefined' && tRptRackCodeFrom != "") && (typeof(tRptRackCodeTo) !== 'undefined' && tRptRackCodeTo == "")){
                $('#oetSMLBrowseGroupCodeTo').val(tRakCode);
                $('#oetSMLBrowseGroupNameTo').val(tRakName);
            } 

            // เช็คข้อมูลถ้ามีการ Browse ถึงบริษัทขนส่ง default จากบริษัทขนส่ง เป็นข้อมูลเดียวกัน 
            if((typeof(tRptRackCodeTo) !== 'undefined' && tRptRackCodeTo != "") && (typeof(tRptRackCodeFrom) !== 'undefined' && tRptRackCodeFrom == "")){
                $('#oetSMLBrowseGroupCodeFrom').val(tRakCode);
                $('#oetSMLBrowseGroupNameFrom').val(tRakName);
            }
        }

    /** ======================================================================================================================== */

    /** ============================================= Event Click Button Report ================================================ */
        // Click Button Reset Filter
        $('#obtRptClearCondition').click(function(){
            document.forms["ofmRptConditionFilter"].reset();
        });
    
        // Click Button Call View Before Print
        $('#obtRptViewBeforePrint').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                $('#ohdRptTypeExport').val('html');
                JSxReportDataExport();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Click Button Call Export Excel
        $('#obtRptDownloadPdf').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                $('#ohdRptTypeExport').val('pdf');
                JSxReportDataExport();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });

        // Click Button Call Export PDF
        $('#obtRptExportExcel').unbind().click(function(){
            var nStaSession = JCNxFuncChkSessionExpired();
            if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
                $('#ohdRptTypeExport').val('excel');
                JSxReportDataExport();
            }else{
                JCNxShowMsgSessionExpired();
            }
        });
    /** ======================================================================================================================== */


</script>
