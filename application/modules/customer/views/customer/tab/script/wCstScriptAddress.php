<script type="text/javascript">
$(document).ready(() => {
    // $('#oimCstBrowseAddCountry').click(function(){JCNxBrowseData('oCstBrowseAddCountry');});
    $('#oimCstBrowseAddZone').click(function(){JCNxBrowseData('oCstBrowseAddZone');});
    $('#oimCstBrowseAddPvn').click(function(){
        console.log('Pvn');
        JCNxBrowseData('oCstBrowseAddPvn');
    });
    $('#oimCstBrowseAddDst').click(function(){
        console.log('Ref Code: ', JSxCSTAddGetLocationRef("province"));
        window.oCstBrowseAddDstOption = oCstBrowseAddDst(JSxCSTAddGetLocationRef("province"));
        JCNxBrowseData('oCstBrowseAddDstOption');
    });
    $('#oimCstBrowseAddSubDist').click(function(){
        console.log('SubRef Code: ', JSxCSTAddGetLocationRef("district"));
        window.oCstBrowseAddSubDistOption = oCstBrowseAddSubDist(JSxCSTAddGetLocationRef("district"));
        JCNxBrowseData('oCstBrowseAddSubDistOption');
    });
    
    // Call Map Api
	var oMapCustomer = {
		tDivShowMap	:'odvCstAddMapEdit',
		cLongitude	: <?php echo (isset($tCstAddLongitude)&&!empty($tCstAddLongitude))? floatval($tCstAddLongitude):floatval('100.50182294100522')?>,
		cLatitude	: <?php echo (isset($tCstAddLatitude)&&!empty($tCstAddLatitude))? floatval($tCstAddLatitude):floatval('13.757309968845291')?>,
		tInputLong	: 'ohdCstAddLongitude',
		tInputLat	: 'ohdCstAddLatitude',
		tIcon		: "https://openlayers.org/en/v4.6.5/examples/data/icon.png",
		tStatus		: '2'	
	};
    $("#oliCstAddress").on("click", () => {
        $("#odvCstAddMapEdit").empty();
        setTimeout(() => {
            JSxMapAddEdit(oMapCustomer);
        }, 1000);
    });
    JSxCSTAddEnabledLocation('district', false); // Disabled District Input Field
    JSxCSTAddEnabledLocation('subdistrict', false); // Disabled Subdistrict Input Field
});

// Set Lang Edit 
var nLangEdits = <?=$this->session->userdata("tLangEdit")?>;
// Option Reference
/*var oCstBrowseAddCountry = {
	Title : ['pos5/shop', 'tSHPTitle'],
	Table:{Master:'TCNMShop', PK:'FTShpCode'},
	Join :{
		Table: ['TCNMShop_L'],
		On: ['TCNMShop_L.FTShpCode = TCNMShop.FTShpCode AND TCNMShop_L.FNLngID = '+nLangEdits]
	},
	Where :{
        // Condition : ["AND TCNMBranch.FTWahStaType = '3' "]
	},
	GrideView:{
		ColumnPathLang	: 'pos5/shop',
		ColumnKeyLang	: ['tSHPCode', 'tShopName'],
		ColumnsSize     : ['15%', '85%'],
        WidthModal      : 50,
		DataColumns		: ['TCNMShop.FTShpCode', 'TCNMShop_L.FTShpName'],
		DataColumnsFormat : ['', ''],
		Perpage			: 5,
		OrderBy			: ['TCNMShop_L.FTShpName'],
		SourceOrder		: "ASC"
	},
	CallBack:{
		ReturnType	: 'S',
		Value		: ["oetCstAddCountryCode", "TCNMShop.FTShpCode"],
		Text		: ["oetCstAddCountryName", "TCNMShop.FTShpName"]
	},
	RouteFrom : 'customer',
	RouteAddNew : 'shop',
	BrowseLev : nStaCstBrowseType
};*/

var oCstBrowseAddZone = {
	Title : ['address/zone/zone', 'tZNETitle'],
	Table:{Master:'TCNMZone', PK:'FTZneCode'},
	Join :{
		Table: ['TCNMZone_L'],
		On: ['TCNMZone_L.FTZneCode = TCNMZone.FTZneCode AND TCNMZone_L.FNLngID = '+nLangEdits]
	},
	Where :{
        // Condition : ["AND TCNMBranch.FTWahStaType = '3' "]
	},
	GrideView:{
		ColumnPathLang	: 'address/zone/zone',
		ColumnKeyLang	: ['tZNECode', '', 'tZNEName'],
		ColumnsSize     : ['15%', '85%'],
        WidthModal      : 50,
		DataColumns		: ['TCNMZone.FTZneCode', 'TCNMZone_L.FTZneName', 'TCNMZone.FTAreCode'],
        DisabledColumns	:[1],
		DataColumnsFormat : ['', ''],
		Perpage			: 5,
		OrderBy			: ['TCNMZone_L.FTZneName'],
		SourceOrder		: "ASC"
	},
	CallBack:{
		ReturnType	: 'S',
		Value		: ["oetCstAddZoneCode", "TCNMZone.FTZneCode"],
		Text		: ["oetCstAddZoneName", "TCNMZone_L.FTZneName"]
	},
    NextFunc:{
        FuncName:'JSxCSTAddSetAreaCode',
        ArgReturn:['FTAreCode']
    },
	RouteFrom : 'customer',
	RouteAddNew : 'zone',
	BrowseLev : nStaCstBrowseType
};

var oCstBrowseAddPvn = {
	Title : ['address/province/province', 'tPVNTitle'],
	Table:{Master:'TCNMProvince', PK:'FTPvnCode'},
	Join :{
		Table: ['TCNMProvince_L'],
		On: ['TCNMProvince_L.FTPvnCode = TCNMProvince.FTPvnCode AND TCNMProvince_L.FNLngID = '+nLangEdits]
	},
	Where :{
        // Condition : ["AND TCNMBranch.FTWahStaType = '3' "]
	},
	GrideView:{
		ColumnPathLang	: 'address/province/province',
		ColumnKeyLang	: ['tPVNTBCode', 'tPVNTBName'],
		ColumnsSize     : ['15%', '85%'],
        WidthModal      : 50,
		DataColumns		: ['TCNMProvince.FTPvnCode', 'TCNMProvince_L.FTPvnName'],
		DataColumnsFormat : ['', ''],
		Perpage			: 5,
		OrderBy			: ['TCNMProvince_L.FTPvnName'],//TCNMProvince_L.FTShpName
		SourceOrder		: "ASC"
	},
	CallBack:{
		ReturnType	: 'S',
		Value		: ["oetCstAddPvnCode", "TCNMProvince.FTPvnCode"],
		Text		: ["oetCstAddPvnName", "TCNMProvince_L.FTPvnName"]
    },
    DebugSQL : true,
	RouteFrom : 'customer',
	RouteAddNew : 'province',
	BrowseLev : nStaCstBrowseType
};

var oCstBrowseAddDst = function (pRefCode){
    let oOptions = {
        Title : ['address/district/district', 'tDSTTitle'],
        Table:{Master:'TCNMDistrict', PK:'FTDstCode'},
        Join :{
            Table: ['TCNMDistrict_L'],
            On: ['TCNMDistrict_L.FTDstCode = TCNMDistrict.FTDstCode AND TCNMDistrict_L.FNLngID = '+nLangEdits]
        },
        Where :{
            Condition : ["AND TCNMDistrict.FTPvnCode = " + pRefCode + " "]
        },
        GrideView:{
            ColumnPathLang	: 'address/district/district',
            ColumnKeyLang	: ['tDSTTBCode', 'tDSTTBName'],
            ColumnsSize     : ['15%', '85%'],
            WidthModal      : 50,
            DataColumns		: ['TCNMDistrict.FTDstCode', 'TCNMDistrict_L.FTDstName', 'TCNMDistrict.FTDstPost'],
            DisabledColumns	:[1],
            DataColumnsFormat : ['', ''],
            Perpage			: 5,
            OrderBy			: ['TCNMDistrict_L.FTDstName'],
            SourceOrder		: "ASC"
        },
        CallBack:{
            ReturnType	: 'S',
            Value		: ["oetCstAddDstCode", "TCNMDistrict.FTDstCode"],
            Text		: ["oetCstAddDstName", "TCNMDistrict_L.FTDstName"]
        },
        NextFunc:{
            FuncName:'JSxCSTAddSetPostCode',
            ArgReturn:['FTDstPost']
        },
        RouteFrom : 'customer',
        RouteAddNew : 'district',
        BrowseLev : nStaCstBrowseType
    };
    return oOptions;
};

var oCstBrowseAddSubDist = function (pRefCode){
    let oOptions = {
        Title : ['address/subdistrict/subdistrict', 'tSDTTitle'],
        Table:{Master:'TCNMSubDistrict', PK:'FTSudCode'},
        Join :{
            Table: ['TCNMSubDistrict_L'],
            On: ['TCNMSubDistrict_L.FTSudCode = TCNMSubDistrict.FTSudCode AND TCNMSubDistrict_L.FNLngID = '+nLangEdits]
        },
        Where :{
            Condition : ["AND TCNMSubDistrict.FTDstCode = " + pRefCode + " "]
        },
        GrideView:{
            ColumnPathLang	: 'address/subdistrict/subdistrict',
            ColumnKeyLang	: ['tSDTTBCode', 'tSDTTBSubdistrict'],
            ColumnsSize     : ['15%', '85%'],
            WidthModal      : 50,
            DataColumns		: ['TCNMSubDistrict.FTSudCode', 'TCNMSubDistrict_L.FTSudName'],
            DataColumnsFormat : ['', ''],
            Perpage			: 5,
            OrderBy			: ['TCNMSubDistrict_L.FTSudName'],
            SourceOrder		: "ASC"
        },
        CallBack:{
            ReturnType	: 'S',
            Value		: ["oetCstAddSubDistCode", "TCNMSubDistrict.FTSudCode"],
            Text		: ["oetCstAddSubDistName", "TCNMSubDistrict_L.FTSudName"]
        },
        RouteFrom : 'customer',
        RouteAddNew : 'subdistrict',
        BrowseLev : nStaCstBrowseType
    };
    return oOptions;
};

/**
* Functionality : Validate Form Before to Record
* Parameters : -
* Creator : 26/09/2018 piya
* Last Modified : -
* Return : -
* Return Type : -
*/
function JSnCSTAddEditCustomerAddress(){
    console.log("Contact");
    $('#ofmAddCustomerAddress').validate({
        rules: {},
        messages: {
            // oetCstCode: "",
            // oetCstName: ""
        },
        errorClass: "alert-validate",
        validClass: "",
        highlight: function(element, errorClass, validClass) {
            $(element).parent().addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parent().removeClass(errorClass).addClass(validClass);
        },
        submitHandler: function(form) {
            console.log("Contact Validate Complete.");
            JCNxOpenLoading();
            $.ajax({
                type: "POST",
                url: "customerEventAddUpdateAddress",
                data: $('#ofmAddCustomerAddress').serialize(),
                cache: false,
                timeout: 0,
                success: function(tResult) {
                    // console.log(tResult);
                    JCNxCloseLoading();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    JCNxCSTResponseError(jqXHR, textStatus, errorThrown);
                }
            });
        },
        errorPlacement: function(error, element) {
            $(element).parent('.validate-input').attr('data-validate', error[0].textContent);
        }
    });
}

/**
* Functionality : Set Area Code
* Parameters : poAreaCode is Area Code from oCstBrowseAddZone
* Creator : 01/10/2018 piya
* Last Modified : -
* Return : -
* Return Type : -
*/
function JSxCSTAddSetAreaCode(poAreaCode){
    try{
        let tAreaCode = JSON.parse(poAreaCode)[0];
        console.log("Area Code: ", tAreaCode);
        $("#ohdCstAddAreaCode").val(tAreaCode);
    }catch(err){
        console.log("JSxCSTAddSetAreaCode Error: ", err);
    }
}

/**
* Functionality : Set Post Code
* Parameters : poPostCode is Area Code from oCstBrowseAddDst
* Creator : 02/10/2018 piya
* Last Modified : -
* Return : -
* Return Type : -
*/
function JSxCSTAddSetPostCode(poPostCode){
    try{
        let tAreaCode = JSON.parse(poPostCode)[0];
        console.log("Post Code: ", tAreaCode);
        $("#oetCstAddPostCode").val(tAreaCode);
    }catch(err){
        console.log("JSxCSTAddSetAreaCode Error: ", err);
    }
}

/**
* Functionality : Action After Change Input Field
* Parameters : poElement is Itself element, poEvent is Itself event, ptLocation is ("province", "distric", "subdistric")
* Creator : 01/09/2018 piya
* Last Modified : -
* Return : -
* Return Type : -
*/
function JSxCSTAddChangeLocation(poElement, poEvent, ptLocation){
    if(ptLocation == "province"){
        let tProvinceCode = $(poElement).val();
        JSxCSTAddSetLocationRef("province", tProvinceCode); // Set Location Code
        JSxCSTAddResetLocation('district'); // Reset District Field
        JSxCSTAddResetLocation('subdistrict'); // Reset Subdistrict Field
        JSxCSTAddEnabledLocation('district', true); // Enabled District Input Field
        return;
    }
    if(ptLocation == "district"){
        let tDistrictCode = $(poElement).val();
        JSxCSTAddSetLocationRef("district", tDistrictCode); // Set Location Code
        JSxCSTAddResetLocation('subdistrict'); // Reset Subdistrict Field
        JSxCSTAddEnabledLocation('subdistrict', true); // Enabled Subdistrict Input Field
        return;
    }
    if(ptLocation == "subdistrict"){
        return;
    }
}

/**
* Functionality : Reset Location Input Value
* Parameters : ptLocation is ("district", "subdistrict")
* Creator : 01/10/2018 piya
* Last Modified : -
* Return : -
* Return Type : -
*/
function JSxCSTAddResetLocation(ptLocation){
    try{
        if(ptLocation == "district"){
            $("#oetCstAddDstCode").val("");
            $("#oetCstAddDstName").val("");
        }
        if(ptLocation == "subdistrict"){
            $("#oetCstAddSubDistCode").val("");
            $("#oetCstAddSubDistName").val("");
        }
    }catch(err){
        console.log("JSxCSTAddResetLocation Error: ", err);
    }
}

/**
* Functionality : Set Location Code to Input Field
* Parameters : ptLocation is ("province", "district", "subdistrict"), 
* ptLocationCode is Location Reference Code
* Creator : 01/10/2018 piya
* Last Modified : -
* Return : -
* Return Type : -
*/
function JSxCSTAddSetLocationRef(ptLocation, ptLocationCode){
    if(ptLocation == "province"){
        $("#ohdProvinceRef").val(ptLocationCode);
    }
    if(ptLocation == "district"){
        $("#ohdDistrictRef").val(ptLocationCode);
    }
    if(ptLocation == "subdistrict"){
        $("#ohdSubdistrictRef").val(ptLocationCode);
    }
}

/**
* Functionality : Get Location Code from Input Field
* Parameters : ptLocationCode is Location Reference Code
* Creator : 01/10/2018 piya
* Last Modified : -
* Return : -
* Return Type : -
*/
function JSxCSTAddGetLocationRef(ptLocation){
    if(ptLocation == "province"){
        return $("#ohdProvinceRef").val();
    }
    if(ptLocation == "district"){
        return $("#ohdDistrictRef").val();
    }
    if(ptLocation == "subdistrict"){
        return $("#ohdSubdistrictRef").val();
    }
}

/**
* Functionality : Enabled or Disabled Location Input Field
* Parameters : ptLocation is ("district", "subdistrict"), pbEnabled is Enabled(true) Disabled(false)
* Creator : 01/10/2018 piya
* Last Modified : -
* Return : -
* Return Type : -
*/
function JSxCSTAddEnabledLocation(ptLocation, pbEnabled){
    try{
        if(ptLocation == "district"){
            if(pbEnabled == false){
                if($("#oetCstAddPvnCode").val() == ""){
                    $("#odvAddDstContainer div").addClass("xWCurNotAlw");
                    $("#odvAddDstContainer img").addClass("xWPointerEventNone");
                }
            }else{
                $("#odvAddDstContainer div").removeClass("xWCurNotAlw");
                $("#odvAddDstContainer img").removeClass("xWPointerEventNone");
            }
            return;
        }
        if(ptLocation == "subdistrict"){
            if(pbEnabled == false){
                if($("#oetCstAddDstCode").val() == ""){
                    $("#odvAddSubDistContainer div").addClass("xWCurNotAlw");
                    $("#odvAddSubDistContainer img").addClass("xWPointerEventNone");
                }
            }else{
                $("#odvAddSubDistContainer div").removeClass("xWCurNotAlw");
                $("#odvAddSubDistContainer img").removeClass("xWPointerEventNone");
            }
            return;
        }
    }catch(err){
        console.log('JSxCSTAddEnabledLocation', err);
    }
}
</script>
