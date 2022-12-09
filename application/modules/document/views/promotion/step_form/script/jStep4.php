<script>

    $(document).ready(function(){
        if(!bIsApvOrCancel){
            if (tUserLoginLevel == 'HQ') {
                // สาขาต้นทางต้องถูกกำหนดก่อนที่จะเลือก กลุ่มร้านค้าปลายทาง
                if($('#oetPromotionStep4BchCode').val() == ''){ // ไม่ได้กำหนดสาขาต้นทาง
                    $('#obtPromotionStep4BrowseMer').attr('disabled', true);
                }else{// กำหนดสาขาต้นทางแล้ว
                    $('#obtPromotionStep4BrowseMer').attr('disabled', false);
                }
                $('#obtPromotionStep4BrowseShp').attr('disabled', true);
            }

            if (tUserLoginLevel == 'BCH') {
                $('#obtPromotionStep4BrowseBch').attr('disabled', true);
                $('#obtPromotionStep4BrowseShp').attr('disabled', true);
            }

            <?php if(false) { ?>
                if (tUserLoginLevel == 'SHP') {
                    $('#obtPromotionStep4BrowseBch').attr('disabled', true);
                    $('#obtPromotionStep4BrowseMer').attr('disabled', true);
                    $('#obtPromotionStep4BrowseShp').attr('disabled', true);
                }

                if($('#oetPromotionStep4MerchantCode').val() != ''){ // กำหนดกลุ่มร้านค้าต้นทางแล้ว
                    $('#obtPromotionStep4BrowseShp').attr('disabled', false);
                }
            <?php } ?>
        }
    });

    /*===== Begin PdtPmtHDCstPri Table Process =========================================*/
    /**
     * Functionality : Get PdtPmtHDCstPri in Temp
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxPromotionStep4GetPdtPmtHDCstPriInTmp(pnPage, pbUseLoading) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var tBchCode = $('#oetPromotionBchCode').val();

            var tSearchAll = $('#oetPromotionPdtLayoutSearchAll').val();

            if (pbUseLoading) {
                JCNxOpenLoading();
            }

            (pnPage == '' || (typeof pnPage) == 'undefined') ? pnPage = 1: pnPage = pnPage;

            $.ajax({
                type: "POST",
                url: "promotionStep4GetPriceGroupConditionInTmp",
                data: {
                    tBchCode: tBchCode,
                    nPageCurrent: pnPage,
                    tSearchAll: tSearchAll
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    $('.xCNPromotionStep4TablePriceGroupCondition').html(oResult.html);

                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : Insert PdtPmtHDCstPri to Temp
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSvPromotionStep4InsertPdtPmtHDCstPriToTemp(ptParams) {
        $('#ohdPromotionStep4PriceGroupCodeTmp').val("");
        $('#ohdPromotionStep4PriceGroupNameTmp').val("");

        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var tBchCode = $('#oetPromotionBchCode').val();

            JCNxOpenLoading();

            $.ajax({
                type: "POST",
                url: "promotionStep4InsertPriceGroupConditionToTmp",
                data: {
                    tBchCode: tBchCode,
                    tPplList: ptParams
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    JSxPromotionStep4GetPdtPmtHDCstPriInTmp(1, true);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : Clear PdtPmtHDCstPri in Temp
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSvPromotionStep4ClearPdtPmtHDCstPriInTemp(pbUseLoading) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var bLoadingGet = false;

            if (pbUseLoading) {
                JCNxOpenLoading();
                bLoadingGet = true;
            }

            var tPmtGroupNameTmp = $('#oetPromotionGroupNameTmp').val();
            var tPmtGroupNameTmpOld = $('#ohdPromotionGroupNameTmpOld').val();

            $.ajax({
                type: "POST",
                url: "promotionStep1ClearPmtDtInTmp",
                cache: false,
                data: {
                    tPmtGroupNameTmp: tPmtGroupNameTmp,
                    tPmtGroupNameTmpOld: tPmtGroupNameTmpOld
                },
                timeout: 0,
                success: function(tResult) {
                    JSxPromotionStep1GetPdtPmtHDCstPriInTmp(1, bLoadingGet);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : Browse Price Group
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxPromotionStep4BrowsePriceGroup(){
        // option Brand
        window.oPromotionBrowseBrand = {
            Title: ['product/pdtpricelist/pdtpricelist', 'tPPLTitle'],
            Table: {
                Master: 'TCNMPdtPriList',
                PK: 'FTPplCode',
                PKName:'FTPplName'
            },
            Join: {
                Table: ['TCNMPdtPriList_L'],
                On: ['TCNMPdtPriList.FTPplCode = TCNMPdtPriList_L.FTPplCode AND TCNMPdtPriList_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [
                    function() {
                        return " AND TCNMPdtPriList.FTPplCode NOT IN (SELECT FTPplCode FROM TCNTPdtPmtHDCstPri_Tmp WHERE FTSessionID = '<?php echo $this->session->userdata("tSesSessionID"); ?>')";
                    }
                ]
            },
            GrideView: {
                ColumnPathLang: 'product/pdtpricelist/pdtpricelist',
                ColumnKeyLang: ['tPPLTBCode', 'tPPLTBName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMPdtPriList.FTPplCode', 'TCNMPdtPriList_L.FTPplName'],
                DataColumnsFormat: ['', ''],
                Perpage: 5,
                OrderBy: ['TCNMPdtPriList.FTPplCode'],
                SourceOrder: "ASC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["ohdPromotionStep4PriceGroupCodeTmp", "TCNMPdtPriList.FTPplCode"],
                Text: ["ohdPromotionStep4PriceGroupNameTmp", "TCNMPdtPriList_L.FTPplName"],
            },
            NextFunc: {
                FuncName: 'JSvPromotionStep4InsertPdtPmtHDCstPriToTemp',
                ArgReturn: ['FTPplCode', 'FTPplName']
            },
            BrowseLev: 1,
            // DebugSQL : true
        }
        JCNxBrowseData('oPromotionBrowseBrand');
    }
    /*===== End PdtPmtHDCstPri Table Process ===============================================*/

    
    /*===== Begin PdtPmtHDBch Table Process ===========================================*/
    /**
     * Functionality : Get PdtPmtHDBch in Temp
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxPromotionStep4GetPdtPmtHDBchInTmp(pnPage, pbUseLoading) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var tBchCode = $('#oetPromotionBchCode').val();

            var tSearchAll = $('#oetPromotionPdtLayoutSearchAll').val();

            if (pbUseLoading) {
                JCNxOpenLoading();
            }

            (pnPage == '' || (typeof pnPage) == 'undefined') ? pnPage = 1: pnPage = pnPage;

            $.ajax({
                type: "POST",
                url: "promotionStep4GetBchConditionInTmp",
                data: {
                    tBchCode: tBchCode,
                    nPageCurrent: pnPage,
                    tSearchAll: tSearchAll
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    $('.xCNPromotionStep4TableBranchCondition').html(oResult.html);
                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : Insert PdtPmtHDBch to Temp
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSvPromotionStep4InsertPdtPmtHDBchToTemp() {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var tBchCode = $('#oetPromotionStep4BchCode').val();
            var tBchName = $('#oetPromotionStep4BchName').val();
            var tMerCode = $('#oetPromotionStep4MerchantCode').val();
            var tMerName = $('#oetPromotionStep4MerchantName').val();
            var tShpCode = $('#oetPromotionStep4ShopCode').val();
            var tShpName = $('#oetPromotionStep4ShopName').val();

            if(tBchCode == '' || tBchCode == undefined){
                var tWarningMessage = 'กรุณาเลือกสาขาก่อนบันทึก';
                FSvCMNSetMsgWarningDialog(tWarningMessage);
                return;
            }

            if(tUserLoginLevel == "BCH"){
                if(tMerCode == '' || tMerCode == undefined){
                    var tWarningMessage = 'กรุณาเลือกกลุ่มร้านค้าก่อนบันทึก';
                    FSvCMNSetMsgWarningDialog(tWarningMessage);
                    return;
                }
            }

            if(tUserLoginLevel == "SHP"){
                if(tMerCode == '' || tMerCode == undefined){
                    var tWarningMessage = 'กรุณาเลือกกลุ่มร้านค้าก่อนบันทึก';
                    FSvCMNSetMsgWarningDialog(tWarningMessage);
                    return;
                } 
                if(tShpCode == '' || tShpCode == undefined){
                    var tWarningMessage = 'กรุณาเลือกร้านค้าก่อนบันทึก';
                    FSvCMNSetMsgWarningDialog(tWarningMessage);
                    return;
                } 
            }

            JCNxOpenLoading();

            $.ajax({
                type: "POST",
                url: "promotionStep4InsertBchConditionToTmp",
                data: {
                    tBchCode: tBchCode,
                    tBchName: tBchName,
                    tMerCode: tMerCode,
                    tMerName: tMerName,
                    tShpCode: tShpCode,
                    tShpName: tShpName
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    if(oResult.nStaEvent == "1"){
                        JSxPromotionStep4GetPdtPmtHDBchInTmp(1, true);
                        $('#odvPromotionStep4AddBchConditionPanel').modal('hide');
                    }
                    if(oResult.nStaEvent == "007"){
                        var tWarningMessage = 'เงื่อนไขที่เลือกมีการกำหนดไว้แล้ว';
                        FSvCMNSetMsgWarningDialog(tWarningMessage);
                    }
                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }
     
    /**
     * Functionality : Add Bch Condition Panel
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxPromotionStep4AddBchConditionPanel(){
        $('#odvPromotionStep4AddBchConditionPanel').modal('show');
    }

    /*===== Begin Event Browse =========================================================*/
    // สาขา
    $("#obtPromotionStep4BrowseBch").click(function() {
        // option 
        window.oPromotionStep4BrowseBch = {
            Title: ['authen/user/user', 'tBrowseBCHTitle'],
            Table: {
                Master: 'TCNMBranch',
                PK: 'FTBchCode'
            },
            Join: {
                Table: ['TCNMBranch_L'],
                On: ['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [""]
            },
            GrideView: {
                ColumnPathLang: 'authen/user/user',
                ColumnKeyLang: ['tBrowseBCHCode', 'tBrowseBCHName'],
                ColumnsSize: ['10%', '75%'],
                DataColumns: ['TCNMBranch.FTBchCode', 'TCNMBranch_L.FTBchName'],
                DataColumnsFormat: ['', ''],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMBranch.FTBchCode'],
                SourceOrder: "ASC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetPromotionStep4BchCode", "TCNMBranch.FTBchCode"],
                Text: ["oetPromotionStep4BchName", "TCNMBranch_L.FTBchName"]
            },
            NextFunc: {
                FuncName: 'JSxPromotionStep4CallbackBch',
                ArgReturn: ['FTBchCode']
            },
            RouteAddNew: 'branch',
            BrowseLev: 1
        };
        JCNxBrowseData('oPromotionStep4BrowseBch');
    });

    // กลุ่มธุรกิจ
    $("#obtPromotionStep4BrowseMer").click(function() {
        // option
        window.oPromotionStep4BrowseMer = {
            Title: ['company/warehouse/warehouse', 'tWAHBwsMchTitle'],
            Table: {
                Master: 'TCNMMerchant',
                PK: 'FTMerCode'
            },
            Join: {
                Table: ['TCNMMerchant_L'],
                On: ['TCNMMerchant.FTMerCode = TCNMMerchant_L.FTMerCode AND TCNMMerchant_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [" AND TCNMMerchant.FTMerCode IN(SELECT FTMerCode FROM TCNMShop WHERE FTBchCode = '" + $('#oetPromotionStep4BchCode').val() + "') "]
            },
            GrideView: {
                ColumnPathLang: 'company/warehouse/warehouse',
                ColumnKeyLang: ['tWAHBwsMchCode', 'tWAHBwsMchNme'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMMerchant.FTMerCode', 'TCNMMerchant_L.FTMerName'],
                DataColumnsFormat: ['', ''],
                Perpage: 5,
                OrderBy: ['TCNMMerchant.FTMerCode'],
                SourceOrder: "ASC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetPromotionStep4MerchantCode", "TCNMMerchant.FTMerCode"],
                Text: ["oetPromotionStep4MerchantName", "TCNMMerchant_L.FTMerName"],
            },
            NextFunc: {
                FuncName: 'JSxPromotionStep4CallbackMer',
                ArgReturn: ['FTMerCode', 'FTMerName']
            },
            BrowseLev: 1,
            // DebugSQL : true
        };
        JCNxBrowseData('oPromotionStep4BrowseMer');
    });

    // ร้านค้า
    $("#obtPromotionStep4BrowseShp").click(function() {
        // Option Shop
        window.oPromotionStep4BrowseShp = {
            Title: ['company/shop/shop', 'tSHPTitle'],
            Table: {
                Master: 'TCNMShop',
                PK: 'FTShpCode'
            },
            Join: {
                Table: ['TCNMShop_L', 'TCNMWaHouse_L'],
                On: [
                    'TCNMShop_L.FTShpCode = TCNMShop.FTShpCode AND TCNMShop.FTBchCode = TCNMShop_L.FTBchCode AND TCNMShop_L.FNLngID = ' + nLangEdits,
                    'TCNMWaHouse_L.FTWahCode = TCNMShop.FTWahCode AND TCNMWaHouse_L.FTBchCode = TCNMShop.FTBchCode AND TCNMWaHouse_L.FNLngID = ' + nLangEdits,
                ]
            },
            Where: {
                Condition: [
                    function() {
                        var tSQL = " AND TCNMShop.FTShpType = '1' AND TCNMShop.FTMerCode = '" + $('#oetPromotionStep4MerchantCode').val() + "' AND TCNMShop.FTBchCode = '" + $('#oetPromotionStep4BchCode').val() + "'";
                        return tSQL;
                    }
                ]
            },
            GrideView: {
                ColumnPathLang: 'company/branch/branch',
                ColumnKeyLang: ['tBCHCode', 'tBCHName'],
                ColumnsSize: ['25%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMShop.FTShpCode', 'TCNMShop_L.FTShpName', 'TCNMShop.FTShpType', 'TCNMShop.FTBchCode', 'TCNMShop.FTWahCode', 'TCNMWaHouse_L.FTWahName'],
                DataColumnsFormat: ['', '', '', '', '', ''],
                DisabledColumns: [2, 3, 4, 5],
                Perpage: 5,
                OrderBy: ['TCNMShop_L.FTShpName'],
                SourceOrder: "ASC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetPromotionStep4ShopCode", "TCNMShop.FTShpCode"],
                Text: ["oetPromotionStep4ShopName", "TCNMShop_L.FTShpName"],
            },
            NextFunc: {
                FuncName: 'JSxPromotionStep4CallbackShp',
                ArgReturn: ['FTBchCode', 'FTShpCode', 'FTShpType', 'FTWahCode', 'FTWahName']
            },
            BrowseLev: 1,
            // DebugSQL : true
        }
        JCNxBrowseData('oPromotionStep4BrowseShp');
    });
    /*===== End Event Browse ===========================================================*/

    /*===== Begin Callback Browse ======================================================*/
    // Browse Bch Callback
    function JSxPromotionStep4CallbackBch(params) {
        var tBchCode = $('#oetPromotionStep4BchCode').val();

        $('#oetPromotionStep4MerchantCode').val("");
        $('#oetPromotionStep4MerchantName').val("");
        $('#oetPromotionStep4ShopCode').val("");
        $('#oetPromotionStep4ShopName').val("");

        $('#obtPromotionStep4BrowseMer').attr('disabled', true);
        $('#obtPromotionStep4BrowseShp').attr('disabled', true);

        if (tBchCode != "") {
            $('#obtPromotionStep4BrowseMer').attr('disabled', false);
        }
    }

    // Browse Mer Callback
    function JSxPromotionStep4CallbackMer(params) {
        var tMerCode = $('#oetPromotionStep4MerchantCode').val();

        $('#oetPromotionStep4ShopCode').val("");
        $('#oetPromotionStep4ShopName').val("");

        $('#obtPromotionStep4BrowseShp').attr('disabled', true);

        if (tMerCode != "") {
            $('#obtPromotionStep4BrowseShp').attr('disabled', false);
        }
    }

    // Browse Shop Callback
    function JSxPromotionStep4CallbackShp(params) {
        var aParam = JSON.parse(params);
        $('#oetPromotionStep4WahInShopCode').val(aParam[3]);
    }
    /*===== End PdtPmtHDBch Table Process ==============================================*/





    
    /*===== Begin PdtPmtHDCstLev Table Process =========================================*/
    /**
     * Functionality : Get PdtPmtHDCstLev in Temp
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxPromotionStep4GetPdtPmtHDCstLevInTmp(pnPage, pbUseLoading) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var tBchCode = $('#oetPromotionBchCode').val();

            var tSearchAll = $('#oetPromotionPdtLayoutSearchAll').val();

            if (pbUseLoading) {
                JCNxOpenLoading();
            }

            (pnPage == '' || (typeof pnPage) == 'undefined') ? pnPage = 1: pnPage = pnPage;

            $.ajax({
                type: "POST",
                url: "promotionStep4GetCstLevConditionInTmp",
                data: {
                    tBchCode: tBchCode,
                    nPageCurrent: pnPage,
                    tSearchAll: tSearchAll
                },
                cache: false,
                timeout: 0,
                success: function(oResult) {
                    $('.xCNPromotionStep4TableCstLevCondition').html(oResult.html);

                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : Insert PdtPmtHDCstLev to Temp
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSvPromotionStep4InsertPdtPmtHDCstLevToTemp(ptParams) {
        $('#ohdPromotionStep4CstLevCodeTmp').val("");
        $('#ohdPromotionStep4CstLevNameTmp').val("");

        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var tBchCode = $('#oetPromotionBchCode').val();

            JCNxOpenLoading();

            $.ajax({
                type: "POST",
                url: "promotionStep4InsertCstLevConditionToTmp",
                data: {
                    tBchCode: tBchCode,
                    tPplList: ptParams
                },
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    JSxPromotionStep4GetPdtPmtHDCstLevInTmp(1, true);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : Clear PdtPmtHDCstLev in Temp
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSvPromotionStep4ClearPdtPmtHDCstLevInTemp(pbUseLoading) {
        var nStaSession = JCNxFuncChkSessionExpired();
        if (typeof nStaSession !== "undefined" && nStaSession == 1) {

            var bLoadingGet = false;

            if (pbUseLoading) {
                JCNxOpenLoading();
                bLoadingGet = true;
            }

            var tPmtGroupNameTmp = $('#oetPromotionGroupNameTmp').val();
            var tPmtGroupNameTmpOld = $('#ohdPromotionGroupNameTmpOld').val();

            $.ajax({
                type: "POST",
                url: "promotionStep1ClearPmtDtInTmp",
                cache: false,
                data: {
                    tPmtGroupNameTmp: tPmtGroupNameTmp,
                    tPmtGroupNameTmpOld: tPmtGroupNameTmpOld
                },
                timeout: 0,
                success: function(tResult) {
                    JSxPromotionStep1GetPdtPmtHDCstLevInTmp(1, bLoadingGet);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxCloseLoading();
                    JCNxResponseError(jqXHR, textStatus, errorThrown);
                }
            });

        } else {
            JCNxShowMsgSessionExpired();
        }
    }

    /**
     * Functionality : Browse Price Group
     * Parameters : -
     * Creator : 04/02/2020 Piya
     * Return : -
     * Return Type : -
     */
    function JSxPromotionStep4BrowseCstLev(){
        // option Brand
        window.oPromotionBrowseBrand = {
            Title: ['customer/customerlevel/customerlevel', 'tCstLevTitle'],
            Table: {
                Master: 'TCNMCstLev',
                PK: 'FTClvCode',
                PKName:'FTClvName'
            },
            Join: {
                Table: ['TCNMCstLev_L'],
                On: ['TCNMCstLev.FTClvCode = TCNMCstLev_L.FTClvCode AND TCNMCstLev_L.FNLngID = ' + nLangEdits]
            },
            Where: {
                Condition: [
                    function() {
                        return " AND TCNMCstLev.FTClvCode NOT IN (SELECT FTClvCode FROM TCNTPdtPmtHDCstLev_Tmp WHERE FTSessionID = '<?php echo $this->session->userdata("tSesSessionID"); ?>')";
                    }
                ]
            },
            GrideView: {
                ColumnPathLang: 'customer/customerlevel/customerlevel',
                ColumnKeyLang: ['tCstLevTBCode', 'tCstLevTBName'],
                ColumnsSize: ['15%', '75%'],
                WidthModal: 50,
                DataColumns: ['TCNMCstLev.FTClvCode', 'TCNMCstLev_L.FTClvName'],
                DataColumnsFormat: ['', ''],
                Perpage: 5,
                OrderBy: ['TCNMCstLev.FTClvCode'],
                SourceOrder: "ASC"
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["ohdPromotionStep4CstLevCodeTmp", "TCNMCstLev.FTClvCode"],
                Text: ["ohdPromotionStep4CstLevNameTmp", "TCNMCstLev_L.FTClvName"],
            },
            NextFunc: {
                FuncName: 'JSvPromotionStep4InsertPdtPmtHDCstLevToTemp',
                ArgReturn: ['FTClvCode', 'FTClvName']
            },
            BrowseLev: 1,
            // DebugSQL : true
        }
        JCNxBrowseData('oPromotionBrowseBrand');
    }
    /*===== End PdtPmtHDCstLev Table Process ===============================================*/


</script>