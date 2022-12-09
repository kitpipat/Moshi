<script type="text/javascript">

    var nLangEdits      = '<?php echo $this->session->userdata("tLangEdit");?>';

    var tStaUsrLevel    = '<?php  echo $this->session->userdata("tSesUsrLevel"); ?>';
    var tUsrBchCode     = '<?php  echo $this->session->userdata("tSesUsrBchCode"); ?>';
    var tUsrBchName     = '<?php  echo $this->session->userdata("tSesUsrBchName"); ?>';
    var tUsrShpCode     = '<?php  echo $this->session->userdata("tSesUsrShpCode"); ?>';
    var tUsrShpName     = '<?php  echo $this->session->userdata("tSesUsrShpName"); ?>';
    var tUsrBchCom      = '<?php  echo $this->session->userdata("tSesUsrBchCom"); ?>';

    if(tStaUsrLevel == 'HQ'){
        $('#obtInvMultiBrowseWaHouse').attr('disabled',true);
        $('#obtInvMultiBrowseProduct').attr('disabled',true);
    }

    function JSxChqConsNextFuncBrowseBch(poDataNextfunc) {
        console.log(poDataNextfunc);
        if (poDataNextfunc == 'NULL') {
            $('#obtInvMultiBrowseWaHouse').attr('disabled',true);
            $('#obtInvMultiBrowseProduct').attr('disabled',true);
        } else {
            $('#obtInvMultiBrowseWaHouse').removeAttr('disabled');
            $('#obtInvMultiBrowseProduct').removeAttr('disabled');
        }
        return;
    }

      // =========================================== Event Browse Multi Branch ===========================================
      $('#obtInvMultiBrowseBranch').unbind().click(function(){
        // เซตค่าว่าง คลังสินค้า
        $('#oetInvWahStaSelectAll').val('');
        $('#oetInvWahCodeSelect').val('');
        $('#oetInvWahNameSelect').val('');

        //เซตค่าว่าง สินค้า
        $('#oetInvPdtStaSelectAll').val('');
        $('#oetInvPdtCodeSelect').val('');
        $('#oetInvPdtNameSelect').val('');

        $('#obtInvMultiBrowseWaHouse').attr('disabled',true);
        $('#obtInvMultiBrowseProduct').attr('disabled',true);

        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oBranchBrowseMultiOption = undefined;
            oBranchBrowseMultiOption        = {
                Title: ['company/branch/branch','tBCHTitle'],
                Table:{Master:'TCNMBranch',PK:'FTBchCode'},
                Join :{
                    Table:	['TCNMBranch_L'],
                    On:['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits]
                },
                GrideView:{
                    ColumnPathLang  	: 'company/branch/branch',
                    ColumnKeyLang	    : ['tBCHCode','tBCHName'],
                    ColumnsSize         : ['15%','75%'],
                    WidthModal          : 50,
                    DataColumns		    : ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
                    DataColumnsFormat   : ['',''],
                    OrderBy			    : ['TCNMBranch_L.FTBchCode ASC'],
                    Perpage: 10,
                },
                CallBack:{
                    // StausAll    : ['oetInvBchStaSelectAll'],
                    ReturnType: 'S',
                    Value		: ['oetInvBchCodeSelect','TCNMBranch.FTBchCode'],
                    Text		: ['oetInvBchNameSelect','TCNMBranch_L.FTBchName']
                },
                NextFunc: {
                    FuncName: 'JSxChqConsNextFuncBrowseBch',
                    ArgReturn: ['FTBchCode','FTBchName']
                }
            };
            //JCNxBrowseMultiSelect('oBranchBrowseMultiOption');
            JCNxBrowseData('oBranchBrowseMultiOption');
            $('#obtInvMultiBrowseBranch').attr("disabled", false);
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    // =========================================== Event Browse Multi Branch ===========================================


    // =========================================== Event Browse Multi Branch ===========================================
    $('#obtInvMultiBrowseProduct').unbind().click(function(){
        // $('#oetInvWahStaSelectAll').val('');
        // $('#oetInvWahCodeSelect').val('');
        // $('#oetInvWahNameSelect').val('');

        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oProductBrowseMultiOption = undefined;

            let tBchCodeSess = $('#oetInvBchCodeSelect').val();
            let tCondition ='';
            if(tBchCodeSess!=''){
                tCondition +=  " AND ( TCNMPdtSpcBch.FTBchCode = '"+tBchCodeSess+"' OR ( TCNMPdtSpcBch.FTBchCode IS NULL OR TCNMPdtSpcBch.FTBchCode ='' ) )";
            }

            oProductBrowseMultiOption         = {
                    Title : ['product/product/product','tPDTTitle'],
                    Table:{Master:'TCNMPdt',PK:'FTPdtCode'},
                    Join :{
                        Table:	['TCNMPdt_L','TCNMPdtSpcBch'],
                        On:[
                            'TCNMPdt_L.FTPdtCode = TCNMPdt.FTPdtCode AND TCNMPdt_L.FNLngID = '+nLangEdits,
                            'TCNMPdtSpcBch.FTPdtCode = TCNMPdt.FTPdtCode'
                            ]
                    }, 
                    Where:{
                          Condition : [tCondition]
                    },
                    GrideView:{
                        ColumnPathLang	: 'product/product/product',
                        ColumnKeyLang	: ['tPDTCode','tPDTName'],
                        ColumnsSize     : ['10%','75%'],
                        WidthModal      : 50,
                        DataColumns		: ['TCNMPdt.FTPdtCode','TCNMPdt_L.FTPdtName'],
                        DataColumnsFormat : ['',''],
                        Perpage			: 10,
                        OrderBy			: ['TCNMPdt.FTPdtCode'],
                        SourceOrder		: "ASC"
                    },
                    CallBack:{
                        StaSingItem : '1',
                        ReturnType	: 'M',
                        Value		: ['oetInvPdtCodeSelect',"TCNMPdt.FTPdtCode"],
                        Text		: ['oetInvPdtNameSelect',"TCNMPdt_L.FTPdtName"],
                    },
                    // RouteAddNew : 'saleperson',
                    BrowseLev : 1
                }
                JCNxBrowseData('oProductBrowseMultiOption');


            $('#obtInvMultiBrowseProduct').attr("disabled", false);
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    // =========================================== Event Browse Multi Branch ===========================================


    // =========================================== Event Browse Multi WaHouse ===========================================
    $('#obtInvMultiBrowseWaHouse').unbind().click(function(){
        var nStaSession = JCNxFuncChkSessionExpired();
        let tBchcode    = $('#oetInvBchCodeSelect').val();
        let tShpcode    = $('#oetMmtShpCodeSelect').val();

        let tTable      = "TCNMWaHouse";   

        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oWaHouseBrowseMultiOption = undefined;
            tWahWherWah                = "";

            if(tBchcode != ""){
                tTable  = "TCNMWaHouse";
                tBchcode = tBchcode.replace(/,/g, "','");
                tWahWherWah = "AND TCNMWaHouse.FTBchCode  IN ('"+tBchcode+"') ";
            }

            if(tShpcode != ""){
                tTable                      = "TCNMShpWah";
                tShpcode = tShpcode.replace(/,/g, "','");
                tWahWherWah                = "AND TCNMShpWah.FTShpCode  IN ('"+tShpcode+"') ";
            }

            oWaHouseBrowseMultiOption        = {
                Title: ['company/warehouse/warehouse','tWAHSubTitle'],
                Table:{Master:tTable,PK:'FTWahCode'},
                Join :{
                    Table:	['TCNMWaHouse_L', 'TCNMBranch_L'],
                    On:['TCNMWaHouse_L.FTWahCode = "'+tTable+'".FTWahCode AND "'+tTable+'".FTBchCode = TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID = '+nLangEdits,
                        'TCNMBranch_L.FTBchCode = "'+tTable+'".FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits]
                },
                Where :{
                    Condition : [tWahWherWah]
                },
                GrideView:{
                    ColumnPathLang  	: 'company/warehouse/warehouse',
                    ColumnKeyLang	    : ['tWahCode','tWahName'],
                    ColumnsSize         : ['25%', '50%'],
                    WidthModal          : 50,
                    DataColumns		    : [ '"'+tTable+'".FTWahCode','TCNMWaHouse_L.FTWahName'],
                    DataColumnsFormat   : ['',''],
                    OrderBy			    : ['TCNMBranch_L.FTBchName ASC, TCNMWaHouse_L.FTWahCode ASC'],
                },
                CallBack:{
                    StausAll    : ['oetInvWahStaSelectAll'],
                    Value		: ['oetInvWahCodeSelect','"'+tTable+'".FTWahCode'],
                    Text		: ['oetInvWahNameSelect','TCNMWaHouse_L.FTWahName']
                }
            };
            JCNxBrowseMultiSelect('oWaHouseBrowseMultiOption');
            $('#obtInvMultiBrowseWaHouse').attr("disabled", false);
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    // =========================================== Event Browse Multi WaHouse ===========================================
</script>