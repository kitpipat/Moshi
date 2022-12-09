
<script type="text/javascript">
nLangEdits = <?php echo $this->session->userdata("tLangEdit"); ?>;
tUsrApv = <?php echo $this->session->userdata("tSesUsername"); ?>;
            
// Disabled Enter in Form
$(document).keypress(
    function(event){
        if (event.which == '13') {
            event.preventDefault();
        }
    }
);

$(document).ready(function(){
    
    if(JCNbAdjStkSubIsUpdatePage()){
        // Doc No
        $("#oetAdjStkSubAjhDocNo").attr("readonly", true);
        $("#odvAdjStkSubSubAutoGenDocNoForm input").attr("disabled", true);
        JSxCMNVisibleComponent('#odvAdjStkSubSubAutoGenDocNoForm', false);
        
        JSxCMNVisibleComponent('#obtCardShiftOutBtnApv', true);
        JSxCMNVisibleComponent('#obtCardShiftOutBtnCancelApv', true);
        JSxCMNVisibleComponent('#obtCardShiftOutBtnDocMa', true);
    }
    
    if(JCNbAdjStkSubIsCreatePage()){
        // Doc No
        $("#oetAdjStkSubAjhDocNo").attr("disabled", true);
        $('#ocbAdjStkSubSubAutoGenCode').change(function(){
            if($('#ocbAdjStkSubSubAutoGenCode').is(':checked')) {
                $("#oetAdjStkSubAjhDocNo").attr("disabled", true);
                $('#odvAdjStkSubSubDocNoForm').removeClass('has-error');
                $('#odvAdjStkSubSubDocNoForm em').remove();
            }else{
                $("#oetAdjStkSubAjhDocNo").attr("disabled", false);
            }
        });
        JSxCMNVisibleComponent('#odvAdjStkSubSubAutoGenDocNoForm', true);
        
        JSxCMNVisibleComponent('#obtCardShiftOutBtnApv', false);
        JSxCMNVisibleComponent('#obtCardShiftOutBtnCancelApv', false);
        JSxCMNVisibleComponent('#obtCardShiftOutBtnDocMa', false);
    }
    
    console.log('JStCMNUserLevel: ', JStCMNUserLevel());
    // Condition control onload
    if(JStCMNUserLevel() == 'HQ'){
        // Init
        $('#obtAdjStkSubBrowseMch').attr('disabled', false);
        $('#obtAdjStkSubBrowseShp').attr('disabled', true);
        $('#obtAdjStkSubBrowsePos').attr('disabled', true);
        $('#obtAdjStkSubBrowseWah').attr('disabled', false);
    }
    
    if(JStCMNUserLevel() == 'BCH'){
        // Init
        // $('#obtAdjStkSubBrowseBch').attr('disabled', true);
        $('#obtAdjStkSubBrowseMch').attr('disabled', false);
        $('#obtAdjStkSubBrowseShp').attr('disabled', true);
        $('#obtAdjStkSubBrowsePos').attr('disabled', true);
        $('#obtAdjStkSubBrowseWah').attr('disabled', false);
    }
    
    if(JStCMNUserLevel() == 'SHP'){
        // Init
        console.log('SHP');
        // $('#obtAdjStkSubBrowseBch').attr('disabled', true);
        $('#obtAdjStkSubBrowseMch').attr('disabled', true);
        $('#obtAdjStkSubBrowseShp').attr('disabled', true);
        $('#obtAdjStkSubBrowsePos').attr('disabled', false);
        $('#obtAdjStkSubBrowseWah').attr('disabled', true);
    }
    
    $('#oliAdjStkSubMngPdtScan').click(function(){
        // Hide
        $('#oetAdjStkSubSearchPdtHTML').hide();
        $('#oimAdjStkSubMngPdtIconSearch').hide();
        // Show
        $('#oetAdjStkSubScanPdtHTML').show();
        $('#oimAdjStkSubMngPdtIconScan').show();
    });

    $('#oliAdjStkSubMngPdtSearch').click(function(){
        // Hide
        $('#oetAdjStkSubScanPdtHTML').hide();
        $('#oimAdjStkSubMngPdtIconScan').hide();
        // Show
        $('#oetAdjStkSubSearchPdtHTML').show();
        $('#oimAdjStkSubMngPdtIconSearch').show();
    });

    // DATE
    /*$('#obtXthDocDate').click(function(){
        event.preventDefault();
        $('#oetXthDocDate').datepicker('show');
    });

    $('#obtXthDocTime').click(function(){
        event.preventDefault();
        $('#oetXthDocTime').datetimepicker('show');
    });

    $('#obtXthRefExtDate').click(function(){
        event.preventDefault();
        $('#oetXthRefExtDate').datepicker('show');
    });

    $('#obtXthRefIntDate').click(function(){
        event.preventDefault();
        $('#oetXthRefIntDate').datepicker('show');
    });

    $('#obtXthTnfDate').click(function(){
        event.preventDefault();
        $('#oetXthTnfDate').datepicker('show');
    });*/
    // DATE

    $('.selectpicker').selectpicker();
	
    $('.xCNDatePicker').datepicker({
        format: 'yyyy-mm-dd',
	autoclose: true,
        todayHighlight: true
    });
    
    $('.xCNTimePicker').datetimepicker({
        format: 'HH:mm:ss'
    });
	
    $('.xWTooltipsBT').tooltip({'placement': 'bottom'});
    $('[data-toggle="tooltip"]').tooltip({'placement': 'top'});
        
    /*var tSpmCode = $('#oetSpmCode').val();

    $('#oetSplCode').change(function(){
        // Clear Modal Pdt เพื่อโหลดใหม่ตอนเปลี่ยน Spl
        $('#odvBrowsePdtPanal').html('');
    });
    
    $('#ostPmcGetCond').on('change', function (e) {
        var nSelected = $("option:selected", this);
        var nValue = this.value;
    	if(nValue == 1 || nValue == 3){
            // alert('ราคา')
            $('.xWCdGetValue').removeClass('xCNHide');
            $('.xWCdGetQty').addClass('xCNHide');
            $('.xWCdPerAvgDis').addClass('xCNHide');
        }else if(nValue == 2){
            // alert('จำนวน %')
            $('.xWCdGetValue').addClass('xCNHide');
            $('.xWCdGetQty').addClass('xCNHide');
            $('.xWCdPerAvgDis').removeClass('xCNHide');

        }else if(nValue == 4){
            // alert('จำนวน แต้ม')
            $('.xWCdGetValue').addClass('xCNHide');
            $('.xWCdGetQty').removeClass('xCNHide');
            $('.xWCdPerAvgDis').addClass('xCNHide');
        }

        $('#oetPmcGetQty').val('');
        $('#oetPmcGetValue').val('');
        $('#oetPmcPerAvgDis').val('');
    });*/

    /*// Set DocDate is Date Now	
    var dCurrentDate = new Date();
    var tAmOrPm = (dCurrentDate.getHours() < 12) ? "AM" : "PM";
    var tCurrentTime = dCurrentDate.getHours() + ":" + dCurrentDate.getMinutes() + " " + tAmOrPm;

    if($('#oetXthDocDate').val() =='' ){
        $('#oetXthDocDate').datepicker("setDate",dCurrentDate); // Doc Date
    }
    if($('#oetXthTnfDate').val() =='' ){
        $('#oetXthTnfDate').datepicker("setDate",dCurrentDate); // 
    }
    // Set DocTime is Time Now	
    if($('#oetXthDocTime').val() =='' ){
        $('#oetXthDocTime').val(tCurrentTime);
    }

    $('#ostXthVATInOrEx').on('change', function (e) {
        JSvAdjStkSubLoadPdtDataTableHtml(); // คำนวนท้ายบิลใหม่
    });*/
});

/*var oAdjStkSubBrowseSpl = {
    Title: ['supplier/supplier/supplier', 'tSPLTitle'],
    Table: {Master:'TCNMSpl', PK:'FTSplCode'},
    Join: {
        Table: ['TCNMSpl_L','TCNMSplCredit'],
        On: ['TCNMSpl_L.FTSplCode = TCNMSpl.FTSplCode AND TCNMSpl_L.FNLngID = '+nLangEdits,
            'TCNMSpl_L.FTSplCode = TCNMSplCredit.FTSplCode'
        ]
    },
    Where:{
        Condition : ["AND TCNMSpl.FTSplStaActive = '1' "]
    },
    GrideView:{
        ColumnPathLang	: 'supplier/supplier/supplier',
        ColumnKeyLang	: ['tSPLTBCode', 'tSPLTBName'],
        ColumnsSize     : ['15%', '75%'],
    WidthModal      : 50,
        DataColumns		: ['TCNMSpl.FTSplCode', 'TCNMSpl_L.FTSplName', 'TCNMSplCredit.FNSplCrTerm', 'TCNMSplCredit.FCSplCrLimit', 'TCNMSpl.FTSplStaVATInOrEx', 'TCNMSplCredit.FTSplTspPaid'],
        DataColumnsFormat : ['',''],
        DisabledColumns: [2, 3, 4, 5],
        Perpage: 5,
        OrderBy: ['TCNMSpl_L.FTSplName'],
        SourceOrder: "ASC"
    },
    CallBack:{
        ReturnType: 'S',
        Value: ["oetSplCode", "TCNMSpl.FTSplCode"],
        Text: ["oetSplName", "TCNMSpl_L.FTSplName"]
    },
    NextFunc:{
        FuncName:'JSxAdjStkSubGetDataToFillSpl',
        ArgReturn:['FNSplCrTerm', 'FCSplCrLimit', 'FTSplStaVATInOrEx', 'FTSplTspPaid', 'FTSplCode', 'FTSplName']
    },
    RouteAddNew: 'supplier',
    BrowseLev: nStaAdjStkSubBrowseType

};*/
// Option Suplier

// Option SalePerson
/*var oAdjStkSubBrowseSpn = {
	
    Title: ['pos5/saleperson', 'tSPNTitle'],
    Table: {Master:'TCNMSpn', PK:'FTSpnCode'},
    Join: {
        Table: ['TCNMSpn_L'],
        On: ['TCNMSpn_L.FTSpnCode = TCNMSpn.FTSpnCode AND TCNMSpn_L.FNLngID = '+nLangEdits]
    },
    GrideView: {
        ColumnPathLang: 'pos5/saleperson',
        ColumnKeyLang: ['tSPNCode', 'tSPNName', '', '', ''],
        ColumnsSize: ['15%', '75%'],
        WidthModal: 50,
        DataColumns: ['TCNMSpn.FTSpnCode', 'TCNMSpn_L.FTSpnName'],
        DataColumnsFormat: ['', ''],
        DisabledColumns	:[2, 3, 4],
        Perpage: 5,
        OrderBy: ['TCNMSpn_L.FTSpnName'],
        SourceOrder: "ASC"
    },
    CallBack: {
        ReturnType: 'S',
        Value: ["oetSpnCode", "TCNMSpn.FTSpnCode"],
        Text: ["oetSpnName", "TCNMSpn_L.FTSpnName"]
    },
    RouteAddNew: 'suplier',
    BrowseLev: nStaAdjStkSubBrowseType
};*/

// Option SalePerson
/*var nLangEdits;
var oPmhBrowseBch;
var oAdjStkSubBrowseMch;
var oAdjStkSubBrowseShpStart;
var oAdjStkSubBrowsePosStart;
var obtAdjStkSubBrowseWahStart;
var oAdjStkSubBrowseShpEnd;
var oAdjStkSubBrowsePosEnd;
var obtAdjStkSubBrowseWahEnd;
var oAdjStkSubBrowseShipAdd;
var tOldBchCkChange = "";
var tOldMchCkChange = "";
var tOldShpStartCkChange = "";
var tOldShpEndCkChange = "";*/

/*========================= Begin Browse Options =============================*/

// สาขา 
$('#obtAdjStkSubBrowseBch').click(function(){ 
    // $(".modal.fade:not(#odvAdjStkSubBrowseShipAdd, #odvModalDOCPDT, #odvModalWanning)").remove();
    tOldBchCkChange = $("#oetBchCode").val();
    // Lang Edit In Browse
    nLangEdits = <?php echo $this->session->userdata("tLangEdit")?>;
    // Option Branch
    oPmhBrowseBch = {
        Title: ['company/branch/branch', 'tBCHTitle'],
        Table: {Master:'TCNMBranch', PK:'FTBchCode'},
        Join: {
            Table: ['TCNMBranch_L'],
            On: ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits]
        },
        GrideView:{
            ColumnPathLang: 'company/branch/branch',
            ColumnKeyLang: ['tBCHCode', 'tBCHName'],
            ColumnsSize: ['15%', '75%'],
            WidthModal: 50,
            DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
            DataColumnsFormat: ['', ''],
            DisabledColumns: [],
            Perpage: 5,
            OrderBy: ['TCNMBranch_L.FTBchName'],
            SourceOrder: "ASC"
        },
        CallBack:{
            ReturnType: 'S',
            Value: ["oetAdjStkSubBchCode", "TCNMBranch.FTBchCode"],
            Text: ["oetAdjStkSubBchName", "TCNMBranch_L.FTBchName"]
        },
        NextFunc:{
            FuncName: 'JSxAdjStkSubCallbackAfterSelectBch',
            ArgReturn: ['FTBchCode', 'FTBchName']
        },
        RouteFrom: 'promotion',
        RouteAddNew: 'branch',
        BrowseLev: 2
    };
    // Option Branch
    JCNxBrowseData('oPmhBrowseBch');

});

// กลุ่มร้านค้า
$('#obtAdjStkSubBrowseMch').click(function(){
    // $(".modal.fade:not(#odvAdjStkSubBrowseShipAdd, #odvModalDOCPDT, #odvModalWanning)").remove();
    tOldMchCkChange = $("#oetMchCode").val();
    // Option merchant
    oAdjStkSubBrowseMch = {
        Title: ['company/warehouse/warehouse', 'tWAHBwsMchTitle'],
        Table: {Master:'TCNMMerchant', PK:'FTMerCode'}, 
        Join: {
            Table: ['TCNMMerchant_L'], 
            On: ['TCNMMerchant.FTMerCode = TCNMMerchant_L.FTMerCode AND TCNMMerchant_L.FNLngID = '+nLangEdits]
        },
        Where: {
            Condition: ["AND (SELECT COUNT(FTShpCode) FROM TCNMShop WHERE TCNMShop.FTMerCode = TCNMMerchant.FTMerCode AND TCNMShop.FTBchCode = '"+$("#ohdAdjStkSubBchCode").val()+"') != 0"]
        },
        GrideView: {
            ColumnPathLang: 'company/warehouse/warehouse',
            ColumnKeyLang: ['tWAHBwsMchCode', 'tWAHBwsMchNme'],
            ColumnsSize: ['15%', '75%'],
            WidthModal: 50,
            DataColumns: ['TCNMMerchant.FTMerCode', 'TCNMMerchant_L.FTMerName'],
            DataColumnsFormat: ['',''],
            Perpage: 5,
            OrderBy: ['TCNMMerchant.FTMerCode'],
            SourceOrder: "ASC"
        },
        CallBack:{
            ReturnType: 'S',
            Value: ["oetAdjStkSubMchCode", "TCNMMerchant.FTMerCode"],
            Text: ["oetAdjStkSubMchName", "TCNMMerchant_L.FTMerName"]
        },
        NextFunc:{
            FuncName:'JSxAdjStkSubCallbackAfterSelectMer',
            ArgReturn:['FTMerCode', 'FTMerName']
        },
        BrowseLev: 1
    };
    // Option merchant
    JCNxBrowseData('oAdjStkSubBrowseMch');
});

// ร้านค้า
$('#obtAdjStkSubBrowseShp').click(function(){
    console.log('Mer: ', $("#oetAdjStkSubMchCode").val());
    // $(".modal.fade:not(#odvAdjStkSubBrowseShipAdd, #odvModalDOCPDT, #odvModalWanning)").remove();
    // Option Shop
    oAdjStkSubBrowseShp = {
        Title : ['company/shop/shop', 'tSHPTitle'],
        Table:{Master: 'TCNMShop', PK: 'FTShpCode'},
        Join :{
            Table: ['TCNMShop_L', 'TCNMWaHouse_L'],
            On: ['TCNMShop_L.FTShpCode = TCNMShop.FTShpCode AND TCNMShop.FTBchCode = TCNMShop_L.FTBchCode AND TCNMShop_L.FNLngID = '+nLangEdits,
                'TCNMShop.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMWaHouse_L.FNLngID= '+nLangEdits
            ]
        },
        Where:{
            Condition : [
                function(){
                    var tSQL = "AND TCNMShop.FTBchCode = '"+$("#ohdAdjStkSubBchCode").val()+"' AND TCNMShop.FTMerCode = '"+$("#oetAdjStkSubMchCode").val()+"'";
                    return tSQL;
                }
            ]
        },
        GrideView: {
            ColumnPathLang: 'company/branch/branch',
            ColumnKeyLang: ['tBCHCode', 'tBCHName'],
            ColumnsSize: ['25%', '75%'],
            WidthModal: 50,
            DataColumns: ['TCNMShop.FTShpCode', 'TCNMShop_L.FTShpName', 'TCNMShop.FTWahCode', 'TCNMWaHouse_L.FTWahName', 'TCNMShop.FTShpType', 'TCNMShop.FTBchCode'],
            DataColumnsFormat: ['', '', '', '', '', ''],
            DisabledColumns:[2, 3, 4, 5],
            Perpage: 5,
            OrderBy: ['TCNMShop_L.FTShpName'],
            SourceOrder: "ASC"
        },
        CallBack: {
            ReturnType: 'S',
            Value: ["oetAdjStkSubShpCode", "TCNMShop.FTShpCode"],
            Text: ["oetAdjStkSubShpName", "TCNMShop_L.FTShpName"]
        },
        NextFunc: {
            FuncName: 'JSxAdjStkSubCallbackAfterSelectShp',
            ArgReturn: ['FTBchCode', 'FTShpCode', 'FTShpType', 'FTWahCode', 'FTWahName']
        },
        BrowseLev: 1
    };
    // Option Shop
    JCNxBrowseData('oAdjStkSubBrowseShp');
});

// เครื่องจุดขาย
$('#obtAdjStkSubBrowsePos').click(function(){ 
    // $(".modal.fade:not(#odvAdjStkSubBrowseShipAdd, #odvModalDOCPDT, #odvModalWanning)").remove();
    // Option Shop
    oAdjStkSubBrowsePos = {
        Title: ['pos/posshop/posshop', 'tPshTBPosCode'],
        Table: { Master:'TVDMPosShop', PK:'FTPosCode' },
        Join: {
            Table: ['TCNMPos', 'TCNMPosLastNo', 'TCNMWaHouse', 'TCNMWaHouse_L'],
            On:['TVDMPosShop.FTPosCode = TCNMPos.FTPosCode',
                'TVDMPosShop.FTPosCode = TCNMPosLastNo.FTPosCode',
                'TVDMPosShop.FTPosCode = TCNMWaHouse.FTWahRefCode AND TCNMWaHouse.FTWahStaType = 6',
                'TCNMWaHouse.FTWahCode = TCNMWaHouse_L.FTWahCode AND TCNMWaHouse_L.FNLngID= '+nLangEdits
            ]
        },
        Where: {
            Condition: [
                function(){
                    var tSQL = "AND TVDMPosShop.FTBchCode = '"+$("#ohdAdjStkSubBchCode").val()+"' AND TVDMPosShop.FTShpCode = '"+$("#oetAdjStkSubShpCode").val()+"'";
                    /*if($("#oetShpCodeEnd").val()!=""){
                        if($("#oetShpCodeStart").val()==$("#oetShpCodeEnd").val()){
                            if($("#oetPosCodeEnd").val()!=""){
                                tSQL += " AND TVDMPosShop.FTPosCode != '"+$("#oetPosCodeEnd").val()+"'";
                            }
                        }
                    }*/
                    return tSQL;
                }
            ]
        },
        GrideView: {
            ColumnPathLang: 'pos/posshop/posshop',
            ColumnKeyLang: ['tPshBRWShopTBCode', 'tPshBRWPosTBName'],
            ColumnsSize: ['25%', '75%'],
            WidthModal: 50,
            DataColumns: ['TVDMPosShop.FTPosCode', 'TCNMPosLastNo.FTPosComName', 'TVDMPosShop.FTShpCode', 'TVDMPosShop.FTBchCode', 'TCNMWaHouse.FTWahCode', 'TCNMWaHouse_L.FTWahName'],
            DataColumnsFormat : ['', '', '', '', '', ''],
            DisabledColumns: [2, 3, 4, 5],
            Perpage: 5,
            OrderBy: ['TVDMPosShop.FTPosCode'],
            SourceOrder: "ASC"
        },
        CallBack: {
            ReturnType: 'S',
            Value: ["oetAdjStkSubPosCode", "TVDMPosShop.FTPosCode"],
            Text: ["oetAdjStkSubPosName", "TCNMPosLastNo.FTPosComName"]
        },
        NextFunc: {
            FuncName: 'JSxAdjStkSubCallbackAfterSelectPos',
            ArgReturn: ['FTBchCode', 'FTShpCode', 'FTPosCode', 'FTWahCode', 'FTWahName']
        },
        BrowseLev: 1

    };
    // Option Shop
    JCNxBrowseData('oAdjStkSubBrowsePos');
});

// คลังสินค้า
$('#obtAdjStkSubBrowseWah').click(function(){
    // $(".modal.fade:not(#odvAdjStkSubBrowseShipAdd, #odvModalDOCPDT, #odvModalWanning)").remove();
    // Option WareHouse
    oAdjStkSubBrowseWah = {
        Title: ['company/warehouse/warehouse', 'tWAHTitle'],
        Table: { Master:'TCNMWaHouse', PK:'FTWahCode'},
        Join: {
            Table: ['TCNMWaHouse_L'],
            On:['TCNMWaHouse_L.FTWahCode = TCNMWaHouse.FTWahCode AND TCNMWaHouse_L.FNLngID = '+nLangEdits]
        },
        Where: {
            Condition: [
                function(){
                    var tSQL = "";
                    if( /*($("#oetAdjStkSubMchCode").val() == '') &&*/ ($("#oetAdjStkSubShpCode").val() == '') && ($("#oetAdjStkSubPosCode").val() == '') ){ // Branch Wah
                        tSQL += " AND TCNMWaHouse.FTWahStaType IN (1,2,5)";
                        // tSQL += " AND TCNMWaHouse.FTWahRefCode = '"+$("#ohdAdjStkSubBchCode").val()+"'";
                    }
                    
                    if( ($("#oetAdjStkSubShpCode").val() != '') && ($("#oetAdjStkSubPosCode").val() == '') ){ // Shop Wah
                        tSQL += " AND TCNMWaHouse.FTWahStaType IN (4)";
                        tSQL += " AND TCNMWaHouse.FTWahRefCode = '"+$('#oetAdjStkSubShpCode').val()+"'";
                    }
                    
                    if( ($("#oetAdjStkSubShpCode").val() != '') && ($("#oetAdjStkSubPosCode").val() != '') ){ // Pos(vending) Wah
                        tSQL += " AND TCNMWaHouse.FTWahStaType IN (6)";
                        tSQL += " AND TCNMWaHouse.FTWahRefCode = '"+$('#oetAdjStkSubPosCode').val()+"'";
                    }
                    console.log(tSQL);
                    return tSQL;
                }
            ]
        },
        GrideView:{
            ColumnPathLang: 'company/warehouse/warehouse',
            ColumnKeyLang: ['tWahCode','tWahName'],
            DataColumns: ['TCNMWaHouse.FTWahCode','TCNMWaHouse_L.FTWahName'],
            DataColumnsFormat: ['',''],
            ColumnsSize: ['15%','75%'],
            Perpage: 5,
            WidthModal: 50,
            OrderBy: ['TCNMWaHouse_L.FTWahName'],
            SourceOrder: "ASC"
        },
        CallBack:{
            ReturnType: 'S',
            Value: ["oetAdjStkSubWahCode","TCNMWaHouse.FTWahCode"],
            Text: ["oetAdjStkSubWahName","TCNMWaHouse_L.FTWahName"]
        },
        NextFunc:{
            FuncName: 'JSxAdjStkSubCallbackAfterSelectWah',
            ArgReturn: []
        },
        RouteAddNew: 'warehouse',
        BrowseLev: nStaAdjStkSubBrowseType
    };
    // Option WareHouse
    JCNxBrowseData('oAdjStkSubBrowseWah');
});

// เหตุผล
$('#obtAdjStkSubBrowseReason').click(function(){
    // $(".modal.fade:not(#odvAdjStkSubBrowseShipAdd, #odvModalDOCPDT, #odvModalWanning)").remove();
    // Option WareHouse
    oAdjStkSubBrowseReason = {
            Title: ['other/reason/reason', 'tRSNTitle'],
            Table: { Master:'TCNMRsn', PK:'FTRsnCode' },
            Join: {
                Table: ['TCNMRsn_L'],
                On: ['TCNMRsn_L.FTRsnCode = TCNMRsn.FTRsnCode AND TCNMRsn_L.FNLngID = '+nLangEdits]
            },
            Where: {
                Condition : ["AND TCNMRsn.FTRsgCode = '003' "]
            },
            GrideView:{
                ColumnPathLang: 'other/reason/reason',
                ColumnKeyLang: ['tRSNTBCode', 'tRSNTBName'],
                // ColumnsSize: ['15%', '85%'],
                WidthModal: 50,
                DataColumns: ['TCNMRsn.FTRsnCode', 'TCNMRsn_L.FTRsnName'],
                DisabledColumns: [],
                DataColumnsFormat: ['', ''],
                Perpage: 5,
                OrderBy: ['TCNMRsn_L.FTRsnName'],
                SourceOrder: "ASC"
            },
            CallBack:{
                ReturnType: 'S',
                Value: ["oetAdjStkSubReasonCode", "TCNMRsn.FTRsnCode"],
                Text: ["oetAdjStkSubReasonName", "TCNMRsn_L.FTRsnName"]
            },
            /*NextFunc:{
                FuncName:'JSxCSTAddSetAreaCode',
                ArgReturn:['FTRsnCode']
            },*/
            // RouteFrom : 'cardShiftChange',
            RouteAddNew : 'reason',
            BrowseLev : nStaAdjStkSubBrowseType
    };
    // Option WareHouse
    JCNxBrowseData('oAdjStkSubBrowseReason');
});

/*=========================== End Browse Options =============================*/

/*=================== Begin Callback Browse ==================================*/
/**
 * สาขา
 * Functionality : Process after shoose branch
 * Parameters : -
 * Creator : 22/05/2019 piya(tiger)
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSxAdjStkSubCallbackAfterSelectBch(poJsonData) {
    
    if (poJsonData != "NULL") {
        aData = JSON.parse(poJsonData);
        tAddBch = aData[0];
        tAddSeqNo = aData[1];
    }
    
    var tBchCode = $('#ohdAdjStkSubBchCode').val();
    var tMchName = $('#oetAdjStkSubMchName').val();
    var tShpName = $('#oetAdjStkSubShpName').val();
    var tPosName = $('#oetAdjStkSubPosName').val();
    var tWahName = $('#oetAdjStkSubWahName').val();
    
    $('#obtAdjStkSubBrowseMch').attr('disabled', true);
    $('#obtAdjStkSubBrowseShp').attr('disabled', true);
    $('#obtAdjStkSubBrowsePos').attr('disabled', true);
    $('#obtAdjStkSubBrowseWah').attr('disabled', true);
}

/**
 * กลุ่มร้านค้า
 * Functionality : Process after shoose merchant
 * Parameters : -
 * Creator : 22/05/2019 piya(tiger)
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSxAdjStkSubCallbackAfterSelectMer(poJsonData) {
    
    if (poJsonData != "NULL") {
        aData = JSON.parse(poJsonData);
        tAddBch = aData[0];
        tAddSeqNo = aData[1];
    }
    
    var tBchCode = $('#ohdAdjStkSubBchCode').val();
    var tMchName = $('#oetAdjStkSubMchName').val();
    var tShpName = $('#oetAdjStkSubShpName').val();
    var tPosName = $('#oetAdjStkSubPosName').val();
    var tWahName = $('#oetAdjStkSubWahName').val();
    
    $('#obtAdjStkSubBrowseShp').attr('disabled', true);
    $('#obtAdjStkSubBrowsePos').attr('disabled', true);
    $('#obtAdjStkSubBrowseWah').attr('disabled', true);
    
    if(JStCMNUserLevel() == 'HQ' || JStCMNUserLevel() == 'BCH'){
        if(tMchName != ''){
            $('#obtAdjStkSubBrowseShp').attr('disabled', false);
            $('#obtAdjStkSubBrowseWah').attr('disabled', true);
        }else{
            $('#obtAdjStkSubBrowseWah').attr('disabled', false);
        }
        $('#oetAdjStkSubShpCode, #oetAdjStkSubShpName').val('');
        $('#oetAdjStkSubPosCode, #oetAdjStkSubPosName').val('');
        $('#oetAdjStkSubWahCode, #oetAdjStkSubWahName').val('');
    }
}

/**
 * ร้านค้า
 * Functionality : Process after shoose shop
 * Parameters : -
 * Creator : 22/05/2019 piya(tiger)
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSxAdjStkSubCallbackAfterSelectShp(poJsonData) {
    
    var aData, tResAddBch, tResAddSeqNo, tResWahCode, tResWahName;
    if (poJsonData != "NULL") {
        aData = JSON.parse(poJsonData);
        tResAddBch = aData[0];
        tResAddSeqNo = aData[1];
        tResWahCode = aData[3];
        tResWahName = aData[4];
    }else{
        $('#oetAdjStkSubWahCode, #oetAdjStkSubWahName').val('');
    }
    console.log('aData: ', aData);
    $('#ohdAdjStkSubWahCodeInShp').val(tResWahCode);
    $('#ohdAdjStkSubWahNameInShp').val(tResWahName);
    var tBchCode = $('#ohdAdjStkSubBchCode').val();
    var tMchName = $('#oetAdjStkSubMchName').val();
    var tShpName = $('#oetAdjStkSubShpName').val();
    var tPosName = $('#oetAdjStkSubPosName').val();
    var tWahName = $('#oetAdjStkSubWahName').val();
    
    $('#obtAdjStkSubBrowsePos').attr('disabled', true);
    $('#obtAdjStkSubBrowseWah').attr('disabled', false);
    
    if(JStCMNUserLevel() == 'HQ' || JStCMNUserLevel() == 'BCH'){
        if(tShpName != ''){
            $('#obtAdjStkSubBrowsePos').attr('disabled', false);
            $('#obtAdjStkSubBrowseWah').attr('disabled', true);
            $('#oetAdjStkSubWahCode').val(tResWahCode);
            $('#oetAdjStkSubWahName').val(tResWahName);
        }else{
            $('#oetAdjStkSubWahCode, #oetAdjStkSubWahName').val('');
        }
        $('#oetAdjStkSubPosCode, #oetAdjStkSubPosName').val('');
    }
}

/**
 * เครื่องจุดขาย
 * Functionality : Process after shoose pos
 * Parameters : -
 * Creator : 22/05/2019 piya(tiger)
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSxAdjStkSubCallbackAfterSelectPos(poJsonData) {
    
    var aData, tResAddBch, tResAddSeqNo, tResWahCode, tResWahName;
    if (poJsonData != "NULL") {
        aData = JSON.parse(poJsonData);
        tResAddBch = aData[0];
        tResAddSeqNo = aData[1];
        tResWahCode = aData[3];
        tResWahName = aData[4];
    }else{
        $('#oetAdjStkSubPosCode, #oetAdjStkSubPosName').val('');
        $('#oetAdjStkSubWahCode').val($('#ohdAdjStkSubWahCodeInShp').val());
        $('#oetAdjStkSubWahName').val($('#ohdAdjStkSubWahNameInShp').val());
        return;
    }
    console.log('aData Pos: ', aData);
    
    var tBchCode = $('#ohdAdjStkSubBchCode').val();
    var tMchName = $('#oetAdjStkSubMchName').val();
    var tShpName = $('#oetAdjStkSubShpName').val();
    var tPosName = $('#oetAdjStkSubPosName').val();
    var tWahName = $('#oetAdjStkSubWahName').val();
    
    $('#obtAdjStkSubBrowseWah').attr('disabled', false);
    
    if(JStCMNUserLevel() == 'HQ' || JStCMNUserLevel() == 'BCH' || JStCMNUserLevel() == 'SHP'){
        if(tPosName != ''){
            $('#obtAdjStkSubBrowseWah').attr('disabled', true);
            $('#oetAdjStkSubWahCode').val(tResWahCode);
            $('#oetAdjStkSubWahName').val(tResWahName);
        }
    }
}

/**
 * คลังสินค้า
 * Functionality : Process after shoose warehouse
 * Parameters : -
 * Creator : 22/05/2019 piya(tiger)
 * Last Modified : -
 * Return : -
 * Return Type : -
 */
function JSxAdjStkSubCallbackAfterSelectWah(poJsonData) {
    
    if (poJsonData != "NULL") {
        aData = JSON.parse(poJsonData);
        tAddBch = aData[0];
        tAddSeqNo = aData[1];
    }
    
}
/*===================== End Callback Browse ==================================*/

/*$('#obtAdjStkSubBrowseShipAdd').click(function(pE){
    $(".modal.fade:not(#odvAdjStkSubBrowseShipAdd, #odvModalDOCPDT, #odvModalWanning)").remove();
    $("#odvAdjStkSubBrowseShipAdd").modal("show");
});*/

// Event Browse ShipAdd
/*$('#oliBtnEditShipAdd').click(function(){ 
    
    $(".modal.fade:not(#odvAdjStkSubBrowseShipAdd, #odvModalDOCPDT, #odvModalWanning)").remove();
    // option Ship Address 
    
    oAdjStkSubBrowseShipAdd = {
            Title: ['document/purchaseorder/purchaseorder', 'tBrowseADDTitle'],
            Table: { Master:'TCNMAddress_L',PK:'FNAddSeqNo' },
            Join: {
                Table:	['TCNMProvince_L','TCNMDistrict_L','TCNMSubDistrict_L'],
                On:["TCNMAddress_L.FTAddV1PvnCode = TCNMProvince_L.FTPvnCode AND TCNMProvince_L.FNLngID = "+nLangEdits,
                    "TCNMAddress_L.FTAddV1DstCode = TCNMDistrict_L.FTDstCode AND TCNMDistrict_L.FNLngID = " + nLangEdits,
                    "TCNMAddress_L.FTAddV1SubDist = TCNMSubDistrict_L.FTSudCode AND TCNMSubDistrict_L.FNLngID = " + nLangEdits
                ]
        },
        Where: {
            Condition: [
                function () {
                    var tFilter = "";
                    if ($("#oetBchCode").val() != "") {
                        if ($("#oetMchCode").val() != "") {
                            if ($("#oetShpCodeEnd").val() != "") {
                                if ($("#oetPosCodeEnd").val() != "") {
                                    // เครื่องจุดขาย
                                    tFilter += "AND FTAddGrpType = 6 AND FTAddRefCode = '" + $("#oetPosCodeEnd").val() + "' AND TCNMAddress_L.FNLngID = " + nLangEdits;
                                } else {
                                    // ร้านค้า
                                    tFilter += "AND FTAddGrpType = 4 AND FTAddRefCode = '" + $("#oetShpCodeEnd").val() + "' AND TCNMAddress_L.FNLngID = " + nLangEdits;
                                }
                            } else {
                                // สาขา
                                tFilter += "AND FTAddGrpType = 1 AND FTAddRefCode = '" + $("#oetBchCode").val() + "' AND TCNMAddress_L.FNLngID = " + nLangEdits;
                            }
                        } else {
                            // สาขา
                            tFilter += "AND FTAddGrpType = 1 AND FTAddRefCode = '" + $("#oetBchCode").val() + "' AND TCNMAddress_L.FNLngID = " + nLangEdits;
                        }
                    }
                    return tFilter;
                }
            ]
        },
        GrideView: {
            ColumnPathLang: 'document/purchaseorder/purchaseorder',
            ColumnKeyLang: ['tBrowseADDBch', 'tBrowseADDSeq', 'tBrowseADDV1No', 'tBrowseADDV1Soi', 'tBrowseADDV1Village', 'tBrowseADDV1Road', 'tBrowseADDV1SubDist', 'tBrowseADDV1DstCode', 'tBrowseADDV1PvnCode', 'tBrowseADDV1PostCode'],
            DataColumns: ['TCNMAddress_L.FTAddRefCode', 'TCNMAddress_L.FNAddSeqNo', 'TCNMAddress_L.FTAddV1No', 'TCNMAddress_L.FTAddV1Soi', 'TCNMAddress_L.FTAddV1Village', 'TCNMAddress_L.FTAddV1Road', 'TCNMAddress_L.FTAddV1SubDist', 'TCNMAddress_L.FTAddV1DstCode', 'TCNMAddress_L.FTAddV1PvnCode', 'TCNMAddress_L.FTAddV1PostCode', 'TCNMSubDistrict_L.FTSudName', 'TCNMDistrict_L.FTDstName', 'TCNMProvince_L.FTPvnName', 'TCNMAddress_L.FTAddV2Desc1', 'TCNMAddress_L.FTAddV2Desc2'],
            DataColumnsFormat: ['', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
            ColumnsSize: [''],
            DisabledColumns: [10, 11, 12, 13, 14],
            Perpage: 10,
            WidthModal: 50,
            OrderBy: ['TCNMAddress_L.FTAddRefCode'],
            SourceOrder: "ASC"
        },
        CallBack: {
            ReturnType: 'S',
            Value: ["ohdShipAddSeqNo", "TCNMAddress_L.FNAddSeqNo"],
            Text: ["ohdShipAddSeqNo", "TCNMAddress_L.FNAddSeqNo"]
        },
        NextFunc: {
            FuncName: 'JSvAdjStkSubGetShipAddData',
            ArgReturn: ['FNAddSeqNo', 'FTAddV1No', 'FTAddV1Soi', 'FTAddV1Village', 'FTAddV1Road', 'FTSudName', 'FTDstName', 'FTPvnName', 'FTAddV1PostCode', 'FTAddV2Desc1', 'FTAddV2Desc2']
        },
        BrowseLev: 1
    };
    
    // option Ship Address 
    JCNxBrowseData('oAdjStkSubBrowseShipAdd');
});

$('#obtAdjStkSubBrowseSpl').click(function(){ JCNxBrowseData('oAdjStkSubBrowseSpl');});
$('#obtAdjStkSubBrowseShp').click(function(){ JCNxBrowseData('oAdjStkSubBrowseShp');});

$('#obtAdjStkSubBrowseWahTo').click(function(){ JCNxBrowseData('oAdjStkSubBrowseWahTo');});
$('#obtAdjStkSubBrowseRate').click(function(){ JCNxBrowseData('oAdjStkSubBrowseRate');});*/

// $("#oetBchCode").change(function(){
// 	$("#oetMchCode").val("");
// 	$("#oetMchName").val("");
// 	$("#oetShpCodeStart").val("");
// 	$("#oetShpNameStart").val("");
// 	$("#ohdWahCodeStart").val("");
// 	$("#ohdWahNameStart").val("");
// 	$("#oetShpCodeEnd").val("");
// 	$("#oetShpNameEnd").val("");
// 	$("#ohdWahCodeEnd").val("");
// 	$("#ohdWahNameEnd").val("");
// });
// $("#oetMchCode").change(function(){
// 	$("#oetShpCodeStart").val("");
// 	$("#oetShpNameStart").val("");
// 	$("#ohdWahCodeStart").val("");
// 	$("#ohdWahNameStart").val("");
// 	$("#oetShpCodeEnd").val("");
// 	$("#oetShpNameEnd").val("");
// 	$("#ohdWahCodeEnd").val("");
// 	$("#ohdWahNameEnd").val("");
// });
// $("#oetShpCodeStart").change(function(){
// 	$("#ohdWahCodeStart").val("");
// 	$("#ohdWahNameStart").val("");
// });
// $("#oetShpCodeEnd").change(function(){
// 	$("#ohdWahCodeEnd").val("");
// 	$("#ohdWahNameEnd").val("");
// });

//Option Promotion GrpBuy
/*oAdjStkSubBrowsePdt = {
	
	Title : ['product/product/product','tPDTTitle'],
	Table:{Master:'TCNMPdt',PK:'FTPdtCode'},
	Join :{
		Table:	['TCNMPdt_L','TCNMPdtPackSize','TCNMPdtUnit_L','TCNMPdtBar','TCNMPdtSpl','TCNTPdtPrice4PDT'],
		On:['TCNMPdt_L.FTPdtCode 			= 	TCNMPdt.FTPdtCode AND TCNMPdt_L.FNLngID = '+nLangEdits,
			"TCNMPdt.FTPdtCode					= 	TCNMPdtPackSize.FTPdtCode AND TCNMPdtPackSize.FCPdtUnitFact = '1'",
			'TCNMPdtPackSize.FTPunCode	= 	TCNMPdtUnit_L.FTPunCode AND TCNMPdtUnit_L.FNLngID='+nLangEdits,
			'TCNMPdt.FTPdtCode					= 	TCNMPdtBar.FTPdtCode AND TCNMPdtPackSize.FTPunCode = TCNMPdtBar.FTPunCode',
			'TCNMPdt.FTPdtCode					= 	TCNMPdtSpl.FTPdtCode',
			'TCNTPdtPrice4PDT.FTPdtCode	= 	TCNMPdt.FTPdtCode  AND TCNTPdtPrice4PDT.FTPunCode  = TCNMPdtPackSize.FTPunCode AND TCNTPdtPrice4PDT.FTPghDocType = 1 AND TCNTPdtPrice4PDT.FDPghDStart <= GETDATE()',
			]
	},
	Where:{
		Condition : ["AND TCNMPdt.FTPdtType IN('1','2','4') AND TCNMPdt.FTPdtStaActive='1' AND TCNMPdt.FTPdtForSystem = '1' AND TCNMPdt.FTPdtStaActive = '1' "]
	},
	Filter:{
		Selector:'oetSplCode',
		Table:'TCNMPdtSpl',
        Key:'FTSplCode'
	},
	GrideView:{
		ColumnPathLang	: 'pos5/product',
		ColumnKeyLang	: ['tPDTCode','tPDTName','tPDTTBUnit','tPDTTBPrice',''],
		ColumnsSize     : ['15%','25%','20%','20%'],
		WidthModal      : 50,
		DataColumns		: ['TCNMPdt.FTPdtCode','TCNMPdt_L.FTPdtName','TCNMPdtUnit_L.FTPunName','TCNTPdtPrice4PDT.FCPgdPriceRET','TCNMPdtUnit_L.FTPunCode'],
		DataColumnsFormat : ['','','',''],
		DisabledColumns	:[4],
		Perpage			: 10,
		OrderBy			: ['TCNMPdt_L.FTPdtName'],
		SourceOrder		: "ASC"
	},
	CallBack:{
		ReturnType	: 'M',
		StaDoc		: '1',
		StaSingItem : '1',
		Value		: ["ohdAdjStkSubPdtCode","TCNMPdt.FTPdtCode"],
		Text		: ["ohdAdjStkSubPdtName","TCNMPdt_L.FTPdtName"],
		
	},
	BrowsePdt : 1,
	NextFunc:{
		FuncName:'JSxAdjStkSubAddPdtInRow',
		ArgReturn:['FTPdtCode','FTPunCode']
    },
	RouteAddNew : 'product',
	BrowseLev : nStaAdjStkSubBrowseType,
	DebugSQL : 0,
}*/
// Option Promotion GrpBuy

// Event Browse
// $('#obtAdjStkSubBrowsePdt').click(function(){ JCNxBrowseProductData('oAdjStkSubBrowsePdt');});

// put ค่าจาก Modal ลง Input หน้า Add
/*function JSnAdjStkSubAddShipAdd(){
    var tShipAddSeqNoSelect = $('#ohdShipAddSeqNo').val();
    $('#ohdXthShipAdd').val(tShipAddSeqNoSelect);
    $('#odvAdjStkSubBrowseShipAdd').modal('toggle');
}*/

function JSxAdjStkSubAddPdtInRow(poJsonData){
    for (var n = 0; n < poJsonData.length; n++) {

        var tdVal = $('.nItem'+n).data('otrval')

        if((tdVal != '') && (typeof tdVal == 'undefined')){

            nTRID = JCNnRandomInteger(100, 1000000);

            var aColDatas = JSON.parse(poJsonData[n]);
            var tPdtCode = aColDatas[0];
            var tPunCode = aColDatas[1];
            FSvAdjStkSubAddPdtIntoTableDT(tPdtCode, tPunCode);

        }
    }
}

//Functionality : Select Spl To input
//Parameters : -
//Creator : 01/08/2018 Krit(Copter)
//Return : View
//Return Type : value to input
/*function JSxAdjStkSubGetDataToFillSpl(poJsonData){

	tOldSplCode = $('#ohdOldSplCode').val();
	tOldSplName = $('#oetOldSplName').val();
	tNewSplCode = $('#oetSplCode').val();

	bStaHavePdt = $('#odvAdjStkSubPdtTablePanal tbody tr').hasClass('xCNDOCPdtItem');

	//Check ว่ามีการเปลี่ยน Spl หรือไม่
	if(tOldSplCode != tNewSplCode && tOldSplCode != '' && bStaHavePdt === true){

			bootbox.confirm({
				title: aLocale['tWarning'],
				message: 'Suplier มีการเปลี่ยนแปลง Product ที่ถูกเพิ่มแล้วจะถูกล้างค่า ต้องการทำต่อหรือไม่ ?',
				buttons: {
						cancel: {
								label: aLocale['tBtnConfirm'],
								className: 'xCNBTNPrimery'
						},
						confirm: {
								label: aLocale['tBtnClose'],
								className: 'xCNBTNDefult'
						}
				},
				callback: function (result) {
						if (result == false) {
								
								aJsonData 			= JSON.parse(poJsonData);
								nXthCrTerm 			= days = parseInt(aJsonData[0], 10);  //
								tSplCrLimit			= aJsonData[1] //
								tSplStaVATInOrEx 	= aJsonData[2] //
								tSplTspPaid 		= aJsonData[3] //
								tSplCode			= aJsonData[4] //
								tSplName			= aJsonData[5] //

								$('#ohdOldSplCode').val(tSplCode).trigger('change');
								$('#oetOldSplName').val(tSplName).trigger('change');

								//Put Data into Form
								//สด/เครดิต
								if(nXthCrTerm > 0){
									$('#ostXthCshOrCrd').val('2').trigger('change');
								}else{
									$('#ostXthCshOrCrd').val('1').trigger('change');
								}
								//จำนวนวันเครดิต
								$('#oetXthCrTerm').val(nXthCrTerm);

								//ประเภทภาษี 1.รวมใน 2.แยกนอกแยกนอก
								if(tSplStaVATInOrEx == ''){
									tSplStaVATInOrEx = 1; //Def value 
								}
								$('#ostXthVATInOrEx').val(tSplStaVATInOrEx).trigger('change');

								dDocDate = $('#oetXthDocDate').val(); // Doc Date
								date = new Date($("#oetXthDocDate").val());


								if(!isNaN(date.getTime())){
									date.setDate(date.getDate() + days);
									$('#oetXthDueDate').datepicker("setDate",date); //วันที่ครบกำหนดชำระนที่ครบกำหนดชำระ
								} else {
									alert("Please Enter Date");  
									$('#oetXthDocDate').focus();
								}

								//การชำระเงิน
								if(tSplTspPaid == ''){
									tSplTspPaid = 1; //Def value 
								}
								$('#ostXthDstPaid').val(tSplTspPaid).trigger('change');


								//ลบข้อมูล สินค้าใน File
								JSnAdjStkSubRemoveAllDTInFile();

						}else{
							$('#oetSplCode').val(tOldSplCode).trigger('change');
							$('#oetSplName').val(tOldSplName).trigger('change');
						}
				}
		});

	}else{
								aJsonData 			= JSON.parse(poJsonData);
								nXthCrTerm 			= days = parseInt(aJsonData[0], 10);  //
								tSplCrLimit			= aJsonData[1] //
								tSplStaVATInOrEx 	= aJsonData[2] //
								tSplTspPaid 		= aJsonData[3] //
								tSplCode			= aJsonData[4] //
								tSplName			= aJsonData[5] //

								$('#ohdOldSplCode').val(tSplCode).trigger('change');
								$('#oetOldSplName').val(tSplName).trigger('change');

								//Put Data into Form
								//สด/เครดิต
								if(nXthCrTerm > 0){
									$('#ostXthCshOrCrd').val('2').trigger('change');
								}else{
									$('#ostXthCshOrCrd').val('1').trigger('change');
								}
								//จำนวนวันเครดิต
								$('#oetXthCrTerm').val(nXthCrTerm);

								//ประเภทภาษี 1.รวมใน 2.แยกนอกแยกนอก
								if(tSplStaVATInOrEx == ''){
									tSplStaVATInOrEx = 1; //Def value 
								}
								$('#ostXthVATInOrEx').val(tSplStaVATInOrEx).trigger('change');

								dDocDate = $('#oetXthDocDate').val(); // Doc Date
								date = new Date($("#oetXthDocDate").val());


								if(!isNaN(date.getTime())){
									date.setDate(date.getDate() + days);
									$('#oetXthDueDate').datepicker("setDate",date); //วันที่ครบกำหนดชำระนที่ครบกำหนดชำระ
								} else {
									alert("Please Enter Date");  
									$('#oetXthDocDate').focus();
								}

								//การชำระเงิน
								if(tSplTspPaid == ''){
									tSplTspPaid = 1; //Def value 
								}
								$('#ostXthDstPaid').val(tSplTspPaid).trigger('change');

	}

}*/

/*function JSxAdjStkSubGetWahFormShop(poJsonData){
    if(poJsonData != undefined){
        aData = JSON.parse(poJsonData);

        tWahCode = aData[0];
        tWahName = aData[1];

        if(tWahCode != '' && tWahCode != undefined){
            $('#ohdWahCode').val(tWahCode);
            $('#oetWahCodeName').val(tWahName);
        }else{
            $('#ohdWahCode').val('');
            $('#oetWahCodeName').val('');
        }
    }
}*/

/*function FSvAdjStkSubAddHDDis(){

    tHDXthDisChgText = $('#ostXthHDDisChgText').val();
    cHDXthDis     = $('#oetXddHDDis').val();
    tHDXthDocNo  = $('#oetXthDocNo').val();
    tHDBchCode   = $('#ohdSesUsrBchCode').val();

    nPlusOld = '';
    nPercentOld = '';
    tPlusNew = '';
    nPercentNew = '';
    tOldDisHDChgLength = '';

    if(tHDXthDisChgText == 1 || tHDXthDisChgText == 2){
        tPlusNew = '+';
    }
    if(tHDXthDisChgText == 2 || tHDXthDisChgText == 4){
        nPercentNew = '%';
    }

    // หา length ที่มีอยู่ ของ HD
    $('.xWAlwEditXpdHDDisChgValue').each(function(e){
        nDistypeOld = $(this).data('distype');
        if(nDistypeOld == 1 || nDistypeOld == 2){
                nPlusOld = '+';
        }
        if(nDistypeOld == 2 || nDistypeOld == 4){
                nPercentOld = '%';
        }
        tOldDisHDChgLength += nPlusOld+$(this).text()+nPercentOld+','
    });
    tNewDisHDChgLength = tPlusNew+accounting.formatNumber(cHDXthDis, nOptDecimalSave,"")+nPercentNew;
    // เอาทั้งสองมาต่อกัน
    tCurDisHDChgLength = tOldDisHDChgLength+tNewDisHDChgLength
    // หาจำนวนตัวอักษร
    nCurDisHDChgLength = tCurDisHDChgLength.length;

    if(cHDXthDis == ''){
        $('#oetXddHDDis').focus();
    }else{
        // Check ขนาดของ Text DisChgText
        if(nCurDisHDChgLength <= 20){
            $.ajax({
                type: "AdjStkSubST",
                url: "AdjStkSubAddHDDisIntoTable",
                data: {  
                    tHDXthDocNo  : tHDXthDocNo,
                    tHDBchCode   : tHDBchCode,
                    tHDXthDisChgText : tHDXthDisChgText,
                    cHDXthDis     : cHDXthDis
                },
                cache: false,
                timeout: 5000,
                success: function(tResult){
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    (jqXHR, textStatus, errorThrown);
                }
            });
        }else{
            alert('ไม่สามารถเพิ่มได้ จำนวนขนาดเกิน 20');
        }
    }
}*/

/**
* Functionality : Check Approve
* Parameters : -
* Creator : 24/05/2019 piya(tiger)
* Last Modified : -
* Return : Approve status
* Return Type : boolean
*/
function JSbAdjStkSubIsApv(){
    var bStatus = false;
    if(($("#ohdAdjStkSubAjhStaApv").val() == "1") || ($("#ohdAdjStkSubAjhStaApv").val() == "2")){
        bStatus = true;
    }
    return bStatus;
}

/**
* Functionality : Check Approve
* Parameters : -
* Creator : 24/05/2019 piya(tiger)
* Last Modified : -
* Return : Approve status
* Return Type : boolean
*/
function JSbAdjStkSubIsStaPrcStk(){
    var bStatus = false;
    if($("#ohdAdjStkSubAjhStaPrcStk").val() == "1"){
        bStatus = true;
    }
    return bStatus;
}

/**
* Functionality : Check document status
* Parameters : ptStaType is ("complete", "incomplete", "cancel")
* Creator : 24/05/2019 piya(tiger)
* Last Modified : -
* Return : Document status
* Return Type : boolean
*/
function JSbAdjStkSubIsStaDoc(ptStaType){
    var bStatus = false;
    if(ptStaType == "complete"){
        if($("#ohdAdjStkSubAjhStaDoc").val() == "1"){
            bStatus = true;
        }
        return bStatus;
    }
    if(ptStaType == "incomplete"){
        if($("#ohdAdjStkSubAjhStaDoc").val() == "2"){
            bStatus = true;
        }
        return bStatus;
    }
    if(ptStaType == "cancel"){
        if($("#ohdAdjStkSubAjhStaDoc").val() == "3"){
            bStatus = true;
        }
        return bStatus;
    }
    return bStatus;
}

/*============================= Begin Custom Form Validate ===================*/

var bUniqueAdjStkSubCode;
$.validator.addMethod(
    "uniqueAdjStkSubCode", 
    function(tValue, oElement, aParams) {
        var tAdjStkSubCode = tValue;
        $.ajax({
            type: "POST",
            url: "adjStkSubUniqueValidate/docAdjStkSubCode",
            data: "tAdjStkSubCode=" + tAdjStkSubCode,
            dataType:"html",
            success: function(ptMsg)
            {
                // If vatrate and vat start exists, set response to true
                bUniqueAdjStkSubCode = (ptMsg == 'true') ? false : true;                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Custom validate uniqueAdjStkSubCode: ', jqXHR, textStatus, errorThrown);
            },
            async: false
        });
        return bUniqueAdjStkSubCode;
    },
    "Adjust Stock Doc Code is Already Taken"
);

// Override Error Message
jQuery.extend(jQuery.validator.messages, {
    required: "This field is required.",
    remote: "Please fix this field.",
    email: "Please enter a valid email address.",
    url: "Please enter a valid URL.",
    date: "Please enter a valid date.",
    dateISO: "Please enter a valid date (ISO).",
    number: "Please enter a valid number.",
    digits: "Please enter only digits.",
    creditcard: "Please enter a valid credit card number.",
    equalTo: "Please enter the same value again.",
    accept: "Please enter a value with a valid extension.",
    maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
    minlength: jQuery.validator.format("Please enter at least {0} characters."),
    rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
    range: jQuery.validator.format("Please enter a value between {0} and {1}."),
    max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
    min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
});

/*============================= End Custom Form Validate =====================*/

/**
* Functionality : Form validate
* Parameters : -
* Creator : 24/05/2019 piya(tiger)
* Last Modified : -
* Return : -
* Return Type : -
*/
function JSxValidateFormAddAdjStkSub() {
    $('#ofmAddAdjStkSub').validate({
        focusInvalid: true,
        onclick: false,
        onfocusout: false,
        onkeyup: false,
        rules: {
            oetAdjStkSubAjhDocNo: {
                required: true,
                maxlength: 20,
                uniqueAdjStkSubCode: JCNbAdjStkSubIsCreatePage()
            },
            oetAdjStkSubAjhDocDate: {
                required: true
            },
            oetAdjStkSubAjhDocTime: {
                required: true
            },
            oetAdjStkSubWahCode: {
                required: true
            },
            oetAdjStkSubWahName: {
                required: true
            },
            oetAdjStkSubReasonCode: {
                required: true
            },
            oetAdjStkSubReasonName: {
                required: true
            }
        },
        messages: {
            oetAdjStkSubAjhDocNo: {
                "required": $('#oetAdjStkSubAjhDocNo').attr('data-validate-required')
            }
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
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
        highlight: function (element, errorClass, validClass) {
            $(element).closest('.form-group').addClass("has-error");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass("has-error");
        },
        submitHandler: function (form) {
            JSxAdjStkSubAddUpdateAction();
        }
    });
}

/**
 * Functionality : Add or Update
 * Parameters : route
 * Creator : 23/05/2019 Piya(Tiger)
 * Update : -
 * Return : -
 * Return Type : -
 */
function JSxAdjStkSubAddUpdateAction() {
    $.ajax({
        type: "POST",
        url: '<?php echo $tRoute; ?>',
        data: $("#ofmAddAdjStkSub").serialize(),
        cache: false,
        timeout: 0,
        success: function (tResult) {

            if (nStaAdjStkSubBrowseType != 1) {
                var aReturn = JSON.parse(tResult);
                if (aReturn["nStaEvent"] == 1) {
                    if (
                            aReturn["nStaCallBack"] == "1" ||
                            aReturn["nStaCallBack"] == null
                            ) {
                        JSvCallPageAdjStkSubEdit(aReturn["tCodeReturn"]);
                    } else if (aReturn["nStaCallBack"] == "2") {
                        JSvCallPageAdjStkSubAdd();
                    } else if (aReturn["nStaCallBack"] == "3") {
                        JSvCallPageAdjStkSubList();
                    }
                } else {
                    tMsgBody = aReturn["tStaMessg"];
                    FSvCMNSetMsgWarningDialog(tMsgBody);
                }
            } else {
                JCNxBrowseData(tCallAdjStkSubBackOption);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            JCNxResponseError(jqXHR, textStatus, errorThrown);
        }
    });
}
</script>























































































































































































































