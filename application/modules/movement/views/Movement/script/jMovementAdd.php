<script type="text/javascript">

    var nLangEdits      = '<?php echo $this->session->userdata("tLangEdit");?>';

    var tStaUsrLevel    = '<?php  echo $this->session->userdata("tSesUsrLevel"); ?>';
    var tUsrBchCode     = '<?php  echo $this->session->userdata("tSesUsrBchCode"); ?>';
    var tUsrBchName     = '<?php  echo $this->session->userdata("tSesUsrBchName"); ?>';
    var tUsrShpCode     = '<?php  echo $this->session->userdata("tSesUsrShpCode"); ?>';
    var tUsrShpName     = '<?php  echo $this->session->userdata("tSesUsrShpName"); ?>';
    var tUsrBchCom      = '<?php  echo $this->session->userdata("tSesUsrBchCom"); ?>';

    if(tStaUsrLevel == 'HQ'){
        $('#obtMmtMultiBrowseShop').attr('disabled',true);
        $('#obtMmtMultiBrowseWaHouse').attr('disabled',true);
        $('#obtMmtMultiBrowseProduct').attr('disabled',true);
    }

    $(document).ready(function(){
        // ตรวจสอบระดับUser banch  11/03/2020 Saharat(Golf)
        if(tUsrBchCode  != ""){ 
            $('#oetMmtBchCodeSelect').val(tUsrBchCode);
            $('#oetMmtBchNameSelect').val(tUsrBchName);
            $('#obtMmtMultiBrowseBranch').attr("disabled", true);
        }
        // ตรวจสอบระดับUser shop  11/03/2020 Saharat(Golf)
        if(tUsrShpCode  != ""){ 
            $('#oetMmtShpCodeSelect').val(tUsrShpCode);
            $('#oetMmtShpNameSelect').val(tUsrShpName);
            $('#obtMmtMultiBrowseShop').attr("disabled", true);
        }

    });
    
    $('.xCNDatePicker').selectpicker();

    // Click Button Date
    $('#obtMmtBrowseDateStart').unbind().click(function(){
        $('#oetMmtDateStart').datepicker('show');
    });
   
    $('#obtMmtBrowseDateTo').unbind().click(function(){
        $('#oetMmtDateTo').datepicker('show');
    });


    // Event Date Picker
    $('.xCNDatePicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });

      // =========================================== Event Browse Multi Branch ===========================================
      $('#obtMmtMultiBrowseBranch').unbind().click(function(){
        //เปิดปุ่ม
        // $('#obtMmtMultiBrowseBranch').attr("disabled", true);

        // เซตค่าว่าง ร้านค่า
        $('#oetMmtShpStaSelectAll').val('');
        $('#oetMmtShpCodeSelect').val('');
        $('#oetMmtShpNameSelect').val('');

        // เซตค่าว่าง คลังสินค้า
        $('#oetMmtWahStaSelectAll').val('');
        $('#oetMmtWahCodeSelect').val('');
        $('#oetMmtWahNameSelect').val('');

        //เซตค่าว่าง สินค้า
        $('#oetMmtPdtStaSelectAll').val('');
        $('#oetMmtPdtCodeSelect').val('');
        $('#oetMmtPdtNameSelect').val('');

        $('#obtMmtMultiBrowseShop').attr('disabled',true);
        $('#obtMmtMultiBrowseWaHouse').attr('disabled',true);
        $('#obtMmtMultiBrowseProduct').attr('disabled',true);

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
                    // StausAll    : ['oetMmtBchStaSelectAll'],
                    ReturnType: 'S',
                    Value		: ['oetMmtBchCodeSelect','TCNMBranch.FTBchCode'],
                    Text		: ['oetMmtBchNameSelect','TCNMBranch_L.FTBchName']
                },
                NextFunc : {
                FuncName    : "JSvMevementBntBch",
                ArgReturn   : ['FTBchCode','FTBchName']
                },
            };
            // JCNxBrowseMultiSelect('oBranchBrowseMultiOption');
            JCNxBrowseData('oBranchBrowseMultiOption');
            // $('#obtMmtMultiBrowseBranch').attr("disabled", false);
        }else{
            JCNxShowMsgSessionExpired();
        }
    });
    //เปิดปุ่ม
    function JSvMevementBntBch(PtDataBch){
        if (PtDataBch == 'NULL') {
            $('#obtMmtMultiBrowseShop').attr('disabled',true);
            $('#obtMmtMultiBrowseWaHouse').attr('disabled',true);
            $('#obtMmtMultiBrowseProduct').attr('disabled',true);
        } else {
            $('#obtMmtMultiBrowseShop').removeAttr('disabled');
            $('#obtMmtMultiBrowseWaHouse').removeAttr('disabled');
            $('#obtMmtMultiBrowseProduct').removeAttr('disabled');
        }
        return;
        // $('#obtMmtMultiBrowseBranch').attr("disabled", false);
    }
    // =========================================== Event Browse Multi Branch ===========================================

    // ============================================ Event Browse Multi Shop ============================================
    $('#obtMmtMultiBrowseShop').unbind().click(function(){
    //ปิดปุ่ม
    // $('#obtMmtMultiBrowseShop').attr("disabled", true);

    // เซตค่าว่าง คลังสินค้า
    $('#oetMmtWahStaSelectAll').val('');
    $('#oetMmtWahCodeSelect').val('');
    $('#oetMmtWahNameSelect').val('');

    //เซตค่าว่าง สินค้า
    $('#oetMmtPdtStaSelectAll').val('');
    $('#oetMmtPdtCodeSelect').val('');
    $('#oetMmtPdtNameSelect').val('');

    var nStaSession = JCNxFuncChkSessionExpired();
    let tBchcode    = $('#oetMmtBchCodeSelect').val();

    if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
        JSxCheckPinMenuClose(); // Hidden Pin Menu
        window.oShopBrowseMultiOption   = undefined;
        tShpWhereBch                = "";

        if(tUsrBchCode != ""){  
            tShpWhereBch                = "AND TCNMShop.FTBchCode = '"+tUsrBchCode+"' ";  
        }
        if(tUsrBchCode == "" && tBchcode != ""){  
            tBchcode = tBchcode.replace(/,/g, "','");
            tShpWhereBch                = "AND TCNMShop.FTBchCode IN ('"+tBchcode+"') ";
        }

        oShopBrowseMultiOption          = {
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
                    Condition : [tShpWhereBch]
                },
            GrideView:{
                ColumnPathLang	    : 'company/shop/shop',
                ColumnKeyLang	    : ['tSHPTBBranch','tSHPTBCode','tSHPTBName'],
                ColumnsSize         : ['15%', '15%','75%'],
                WidthModal          : 50,
                DataColumns		    : ['TCNMBranch_L.FTBchName', 'TCNMShop.FTShpCode', 'TCNMShop_L.FTShpName'],
                DataColumnsFormat   : ['','',''],
                OrderBy			    : ['TCNMShop.FTBchCode ASC,TCNMShop.FTShpCode ASC'],
            },
            CallBack:{
                StausAll    : ['oetMmtShpStaSelectAll'],
                Value		: ['oetMmtShpCodeSelect',"TCNMShop.FTShpCode"],
                Text		: ['oetMmtShpNameSelect',"TCNMShop_L.FTShpName"]
                },
            // NextFunc    : {
            // FuncName    : "JSvMevementBntShp",
            // ArgReturn   : ['FTBchCode','FTShpCode']
            // },
             // DebugSQL : true
            };
        JCNxBrowseMultiSelect('oShopBrowseMultiOption');
        $('#obtMmtMultiBrowseShop').attr("disabled", false);
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //เปิดปุ่ม
    // function JSvMevementBntShp(PtDataShp){
    //     console.log('JSvMevementBntShp');
    //     console.log(PtDataShp);
    //     $('#obtMmtMultiBrowseShop').attr("disabled", false);
    // }
    // ============================================ END Event Browse Multi Shop ============================================

    // =========================================== Event Browse Multi WaHouse ===========================================
    $('#obtMmtMultiBrowseWaHouse').unbind().click(function(){
    //ปิดปุ่ม
    // $('#obtMmtMultiBrowseWaHouse').attr("disabled", true);
        //เซตค่าว่าง สินค้า
        $('#oetMmtPdtStaSelectAll').val('');
        $('#oetMmtPdtCodeSelect').val('');
        $('#oetMmtPdtNameSelect').val('');
        var nStaSession = JCNxFuncChkSessionExpired();
        let tBchcode    = $('#oetMmtBchCodeSelect').val();
        let tShpcode    = $('#oetMmtShpCodeSelect').val();
        let tTable      = "TCNMWaHouse";   

        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oWaHouseBrowseMultiOption = undefined;
            tWahWherWah                = "";

            if(tUsrBchCode != ""){  
                tWahWherWah                = "AND TCNMWaHouse.FTBchCode = '"+tUsrBchCode+"' ";
            }
            if(tUsrBchCode == "" && tShpcode == "" && tBchcode != ""){
                let tTable                  = "TCNMWaHouse";
                tWahWherWah                 = "AND TCNMWaHouse.FTBchCode  IN ('"+tBchcode+"') ";
            }

            if(tShpcode != ""){
                tTable                      = "TCNMShpWah";
                tWahWherWah                = "AND TCNMShpWah.FTShpCode  IN ('"+tShpcode+"') ";
            }

            if(tStaUsrLevel == "HQ" && tShpcode == "" && tBchcode == ""){
                tWahWherWah                = "AND TCNMWaHouse.FTBchCode = '"+tUsrBchCom+"' ";
            }

            oWaHouseBrowseMultiOption        = {
                Title: ['company/warehouse/warehouse','tWAHSubTitle'],
                Table:{Master:tTable,PK:'FTWahCode'},
                Join :{
                    Table:	['TCNMWaHouse_L'],
                    On:['TCNMWaHouse_L.FTWahCode = "'+tTable+'".FTWahCode AND "'+tTable+'".FTBchCode = TCNMWaHouse_L.FTBchCode AND TCNMWaHouse_L.FNLngID = '+nLangEdits]
                },
                Where :{
                    Condition : [tWahWherWah]
                },
                GrideView:{
                    ColumnPathLang  	: 'company/warehouse/warehouse',
                    ColumnKeyLang	    : ['tWahCode','tWahName'],
                    ColumnsSize         : ['15%','75%'],
                    WidthModal          : 50,
                    DataColumns		    : ['"'+tTable+'".FTWahCode','TCNMWaHouse_L.FTWahName'],
                    DataColumnsFormat   : ['',''],
                    OrderBy			    : ['TCNMWaHouse_L.FTWahCode ASC'],
                },
                CallBack:{
                    StausAll    : ['oetMmtWahStaSelectAll'],
                    Value		: ['oetMmtWahCodeSelect','"'+tTable+'".FTWahCode'],
                    Text		: ['oetMmtWahNameSelect','TCNMWaHouse_L.FTWahName']
                },
                // NextFunc : {
                // FuncName    : "JSvMevementBntWah",
                // ArgReturn   : ['FTWahCode','FTWahName']
                // },
                // DebugSQL : true 
            };
            JCNxBrowseMultiSelect('oWaHouseBrowseMultiOption');
            $('#obtMmtMultiBrowseWaHouse').attr("disabled", false);
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //เปิดปุ่ม
    function JSvMevementBntWah(PtDataWah){
        $('#obtMmtMultiBrowseWaHouse').attr("disabled", false);
    }
    // =========================================== Event Browse Multi WaHouse ===========================================

      // =========================================== Event Browse Multi Branch ===========================================
      $('#obtMmtMultiBrowseProduct').unbind().click(function(){
        // ปิดปุ่ม
        // $('#obtMmtMultiBrowseProduct').attr("disabled", true);
        var nStaSession = JCNxFuncChkSessionExpired();
        if(typeof(nStaSession) !== 'undefined' && nStaSession == 1){
            JSxCheckPinMenuClose(); // Hidden Pin Menu
            window.oProductBrowseMultiOption = undefined;
            // ตรวจสอบระดับใช้งานผู้ใช้
            // switch(tStaUsrLevel) {
            //     case "HQ":
            //         tTableMaster = "VCN_ProductsHQ";
            //         break;
            //     case "BCH":
            //         tTableMaster = "VCN_ProductsBranch";
            //         break;
            //     case "SHP":
            //         tTableMaster = "VCN_ProductShop";
            //         break;
            //     default:
            //         // code block
            // }
            // oProductBrowseMultiOption        = {
            //     Title: ['product/product/product','tPDTTitle'],
            //     Table:{Master:tTableMaster,PK:'FTPdtCode'},
            //     GrideView:{
            //         ColumnPathLang  	: 'product/product/product',
            //         ColumnKeyLang	    : ['tPDTCode','tPDTName'],
            //         ColumnsSize         : ['15%','75%'],
            //         WidthModal          : 50,
            //         DataColumns		    : ['"'+tTableMaster+'".FTPdtCode','"'+tTableMaster+'".FTPdtName'],
            //         DataColumnsFormat   : ['',''],
            //         OrderBy			    : ['"'+tTableMaster+'".FTPdtCode ASC'],
            //     },
            //     CallBack:{
            //         StausAll    : ['oetMmtPdtStaSelectAll'],
            //         Value		: ['oetMmtPdtCodeSelect','"'+tTableMaster+'".FTPdtCode'],
            //         Text		: ['oetMmtPdtNameSelect','"'+tTableMaster+'".FTPdtName']
            //     },
            //     // NextFunc : {
            //     // FuncName    : "JSvMevementBntPdt",
            //     // ArgReturn   : ['FTPdtCode','FTPdtName']
            //     // },
            //     // DebugSQL : true 
            // };
            // JCNxBrowseMultiSelect('oProductBrowseMultiOption');
                // let tBchCodeSess = '<?=$this->session->userdata('tSesUsrBchCode')?>';
                let tBchCodeSess    = $('#oetMmtBchCodeSelect').val();
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
                        Value		: ['oetMmtPdtCodeSelect',"TCNMPdt.FTPdtCode"],
                        Text		: ['oetMmtPdtNameSelect',"TCNMPdt_L.FTPdtName"],
                    },
                    // RouteAddNew : 'saleperson',
                    BrowseLev : 1
                }
                    JCNxBrowseData('oProductBrowseMultiOption');


            $('#obtMmtMultiBrowseProduct').attr("disabled", false);
        }else{
            JCNxShowMsgSessionExpired();
        }
    });

    //เปิดปุ่ม
    function JSvMevementBntPdt(PtDataPdt){
        $('#obtMmtMultiBrowseProduct').attr("disabled", false);
    }
    // =========================================== Event Browse Multi Branch ===========================================

</script>