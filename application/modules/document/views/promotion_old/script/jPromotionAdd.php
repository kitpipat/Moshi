<script type="text/javascript">

$(document).ready(function(){

	JSvGetDrpdwnCDGrpName();
	
	setTimeout(function(){ JSxPMTControlGetCond(); }, 500); // Controll Div สมาชิก
	
    // $('.xCNDatePicker').datepicker({
    //     format: 'yyyy-mm-dd',
	//     autoclose: true,
    //     todayHighlight: true,
    //     startDate: new Date(),
	// });
	$('#oetPmhDStop').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        enableOnReadonly: false,
        startDate :'1900-01-01',
        disableTouchKeyboard : true,
        autoclose: true,
    });
	$('#oetPmhDStart').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        enableOnReadonly: false,
        startDate :'1900-01-01',
        disableTouchKeyboard : true,
        autoclose: true,
    });
	$('#oetPntSplExpired').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        enableOnReadonly: false,
        startDate :'1900-01-01',
        disableTouchKeyboard : true,
        autoclose: true,
    });
	$('#oetPntSplStart').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        enableOnReadonly: false,
        startDate :'1900-01-01',
        disableTouchKeyboard : true,
        autoclose: true,
    });
	$('#obtPmhDStop').click(function(event){
        $('#oetPmhDStop').datepicker('show');
		event.preventDefault();
    });
	$('#obtPmhDStart').click(function(event){
        $('#oetPmhDStart').datepicker('show');
		event.preventDefault();
    });
    $('#obtPntSplStart').click(function(event){
        $('#oetPntSplStart').datepicker('show');
		event.preventDefault();
    });
	$('#obtPntSplExpired').click(function(event){
		$('#oetPntSplExpired').datepicker('show');
		event.preventDefault();
	});

	$('.xCNTimePicker').datetimepicker({
        format: 'LT'
    });
    $('.xWTooltipsBT').tooltip({'placement': 'bottom'});
	$('[data-toggle="tooltip"]').tooltip({'placement': 'top'});
	
	$(".selection-2").select2({
	minimumResultsForSearch: 20,
	dropdownParent: $('#dropDownSelect1')
	});
	
	$('.selectpicker').selectpicker();

	tSpmCode = $('#oetSpmCode').val();



	// $('tbody tr').click(function(e){
		
	// 	// $('#'+$(this).parent().attr('id')+' tr').css('background-color','#FFFFFF');	
	// 	// $(this).css('background-color','#d9d9d9');

	// 	var nCode = $(this).parent('tr').data('code');  //code
	// 	var tName = $(this).parent('tr').data('name');  //name

	// 	tSpmCode = $('#ohdSpmCode').val();
	// 	if(tSpmCode == '' || tSpmCode != nCode){
	// 		$('#ohdSpmCode').val(nCode); //put ค่า
	// 		// $(this).parent('tr').css('background-color','#d9d9d9');
	// 	}else{
	// 		$('#ohdSpmCode').val(''); //put ค่า
	// 		// $(this).parent('tr').css('background-color','#fff');
	// 	}
	// });



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

	});

});
	function JSxPMTControlGetCond(){

		nCound = $('#ohdControllCound').val();
		aCound 		= nCound.split(',');
		nCoundNum 	= aCound.length;

		nGetCount = '';
		for ($i = 0; $i < nCoundNum; $i++) {
			
			if(aCound[$i] == 4){
				nGetCount = 4;
			}

		}
	
		if(nGetCount != 4){

			$('#ocbSpmStaChkCst').attr('disabled',true).attr('checked',false);
			$('#oetPmhCstNum').attr('disabled',true).addClass('xCNDisable').val('');
			$('#ocbSpmStaChkCstDOB').attr('disabled',true).attr('checked',false);
			$('#oetPmhCstDobNum').attr('disabled',true).addClass('xCNDisable').val('');

			$('#oetPmhCstDobPrev').attr('disabled',true).addClass('xCNDisable').val('');
			$('#oetPmhCstDobNext').attr('disabled',true).addClass('xCNDisable').val('');

			$('#oetPmgCode').attr('disabled',true).addClass('xCNDisable').val('');
			$('#oetPmgCodeName').attr('disabled',true).addClass('xCNDisable').val('');
			$('#obtPmtBrowseCstGrp').attr('disabled',true);

			}else{

			$('#ocbSpmStaChkCst').attr('disabled',false);
			$('#oetPmhCstNum').attr('disabled',false).removeClass('xCNDisable');
			$('#ocbSpmStaChkCstDOB').attr('disabled',false);
			$('#oetPmhCstDobNum').attr('disabled',false).removeClass('xCNDisable');

			$('#oetPmhCstDobPrev').attr('disabled',false).removeClass('xCNDisable');
			$('#oetPmhCstDobNext').attr('disabled',false).removeClass('xCNDisable');

			$('#oetPmgCode').attr('disabled',false).removeClass('xCNDisable');
			$('#oetPmgCodeName').attr('disabled',false).removeClass('xCNDisable');
			$('#obtPmtBrowseCstGrp').attr('disabled',false);
				
		}



	}


	function JSxOpenMDConditoin(){

		tStaGrpCondHave = $('#ohdStaGrpCondHave').val();

		//Clear Data Input
		$('#odvModalPmhCondition input').val();
		$('#odvModalPmhCondition select').val('').trigger('change');
		$('#oetPmcGrpName').val($("#oetPmcGrpName option:first").val()).trigger('change');
		//Clear Data Input

		if(tStaGrpCondHave != undefined){
			aData = tStaGrpCondHave.split(',');
			nNum = aData.length;
			
			/* Set Default ให้ Select*/
			// $('#oetPmcStaGrpCond option').prop('disabled', false);
			// $('#oetPmcStaGrpCond').select2("destroy").select2();

			// if(aData != ''){
			// 	for(i=0;i<nNum;i++){
			// 		if(aData[i] != ''){
			// 			$('#oetPmcStaGrpCond option[value='+aData[i]+']').prop('disabled', true);
			// 			$('#oetPmcStaGrpCond').select2("destroy").select2();
			// 		}
			// 	}
			// }
		}

		$('#odvModalPmhCondition').modal('toggle');
		// $('#ohdControllCound').val(tStaGrpCondHave);
		// $('#ohdControllCound').val($('#odvCondition tr.xWCondition').length);

	}

	function JStChangeGrpName(ptGrpName,ptGrpBothItem){

		//CHeck เฉพราะ Both เท่านั้น ที่จะส่ง numItem มาด้วย
		if(ptGrpBothItem != undefined){
			nGrpBothItem = ptGrpBothItem /* set ค่า num Item */
		}else{
			nGrpBothItem = ''; /* set ค่าว่าง */
		}

		tGrpName = $('#ohdPmd'+ptGrpName+'Name'+nGrpBothItem).val();

		$('.xWPut'+ptGrpName+nGrpBothItem).text(tGrpName);

		$('#ohdPmd'+ptGrpName+'Name'+nGrpBothItem).val(tGrpName);
		$('#ohdPmd'+ptGrpName+'Name'+nGrpBothItem+'Current').val(tGrpName);

		nNumInputHiden = $('.xWValCondition').length;

		$('.xWValHiden'+ptGrpName).each(function(){
			
			tDataHide = $(this).val();
			aDataHide = tDataHide.split(',');
			GrpNameOld = aDataHide[1];

			tNewGrpName = tDataHide.replace(GrpNameOld, tGrpName);

			$(this).val(tNewGrpName);
		
		});

		//Show Div Label
		$('#odvHeadBarShowPanal'+ptGrpName+nGrpBothItem).removeClass('xCNHide');
		//Hide Div Edit
		$('#odvHeadBarEditPanal'+ptGrpName+nGrpBothItem).addClass('xCNHide');

		JSvGetDrpdwnCDGrpName();
	}


	function JSvGetDrpdwnCDGrpName(ptNum){

		$('#oetPmcGrpName').html('');

		$('.xCNGetDrpDwnName').each(function(){
			tGrpName = $('#'+this.id).val();
			tDataName = $('#'+this.id).data('name');
	
			$('#oetPmcGrpName').append($("<option>")
								.text(tGrpName)
								.val(tDataName)
								)
		});

	}


	// เพิ่ม
	function JSvGrpBothPanalPlus(ptNum){
		alert('+'+ptNum)
	}




	// ลบ Div ซื้อร/ับ
	function JSvDeleteGrpBothPanal(ptNum){

		$('#odvStaGrpBoth'+ptNum).remove();

		JSvGetDrpdwnCDGrpName();
	}

	function JSvAddStaGrpBothPanal(pNum){

		n = $('.xCNStaGrpBothPanal').data('num');
		len = $('.xCNStaGrpBothPanal').length;
		num = parseInt(len)+1;

		if(pNum != undefined){
			num = pNum;
		}


		tStaDivNew = $('#odvStaGrpBoth'+num).html();
		
		if(tStaDivNew == undefined){
			$( "#odvStaGrpBothPanal" ).append($('<div>')
									.addClass('panel panel-default xCNStaGrpBothPanal')
									.attr('style','margin-bottom: 30px;')
									.attr('id','odvStaGrpBoth'+num)
										.append($('<input>')
										.attr('class','xCNHide')
										.attr('id','oetGrpBothItem'+num)
										.attr('name','oetGrpBothItem[]')
										.val(num)
										.trigger('change')
										)

										.append($('<div>')
										.attr('id','odvHeadStaGrpBoth'+num)
										.addClass('panel-heading xCNPanelHeadColor')
										.attr('role','tab')

											.append($('<div>')
											.addClass('row')

												.append($('<div>')
												.addClass('col-md-9')
													//Open
													.append($('<a>')
													.addClass('xCNMenuplus')
													.attr('role','button')
													.attr('data-toggle','collapse')
													.attr('data-parent','#odvSubGroupApp')
													.attr('href','#odvDataStaGrpBoth'+num)
													.attr('aria-expanded',true)
													.attr('aria-controls','odvDataPromotion')
														.append($('<i>')
														.addClass('fa fa-plus xCNPlus text-white')
														)
													)
													
													.append($('<div>')
													.attr('id','odvHeadBarShowPanalGrpBoth'+num)
													
														.append($('<a>')
														.attr('href','javascript:0')
														.addClass('text-white xCNTextDetail1 xWPutGrpBoth'+num)
														.attr('id','olaHeadGrpBothName'+num)
														.text('สินค้ากลุ่มซื้อ/รับ'+num)
														)

														.append($('<input>')
														.attr('type','text')
														.addClass('xCNHide')
														.attr('id','ohdPmdGrpBothName'+num+'Current')
														.attr('name','ohdPmdGrpBothName'+num+'Current')
														.val('สินค้ากลุ่มซื้อ/รับ'+num)
														)

														.append($('<i>')
														.addClass('fa fa-pencil fa-lg xCNEditRowBtn text-white')
														.attr('id','oiIconEditGrpBoth'+num)
														.attr('onclick',"JSxShowInputEditInRow('GrpBoth','"+num+"')")
														)

													)

													.append($('<div>')
													.attr('id','odvHeadBarEditPanalGrpBoth'+num)
													.addClass('xCNHide')

														.append($('<div>')
														.attr('class','wrap-input100 validate-input xCNOdvEditRow')
														.attr('data-validate','Please Enter')

															.append($('<input>')
															.attr('type','text')
															.addClass('input100 xCNGetDrpDwnName')
															.attr('id','ohdPmdGrpBothName'+num)
															.attr('name','ohdPmdGrpBothName'+num)
															.attr('data-name','GrpBoth'+num)
															.val('สินค้ากลุ่มซื้อ/รับ'+num)
															)
															.append($('<span>')
															.addClass('focus-input100')
															)
															.append($('<i>')
															.addClass('fa fa-check-square-o fa-lg xCNIconEditRow')
															.attr('id','oiIconEditGrpBoth'+num)
															.attr('onclick',"JStChangeGrpName('GrpBoth','"+num+"')")
															)
														)

													)
													//Close
												)

												.append($('<div>')
												.addClass('col-md-3')
													.append($('<button>')
													.addClass('xCNBTNPrimeryPlus pull-right')
													.attr('onclick','JSvDeleteGrpBothPanal('+num+')')
													.attr('type','button')
													.attr('style','background-color:red;')
													.text('-')
													)
												)

											)

											// .append($('<span>')
											// .addClass('xCNPmhBtnPlus')
											// .attr('id','oimPmhGrpBothBrowsePdt'+num)
											// .attr('onclick','JSxBrowseGrpBothMulti('+num+')')
											// .text('+')
											// )
										)

										.append($('<div>')
										.attr('id','odvDataStaGrpBoth'+num)
										.addClass('panel-body collapse in')
										.attr('role','tabpanel')
										.attr('aria-expanded',true)
										.attr('data-grpname','GrpBoth')
										.attr('data-grpitem',num)
											
											.append($('<div>')
											.addClass('col-md-9 no-padding')
											)

											.append($('<div>')
											.addClass('col-md-3 no-padding')
											.attr('style','margin-bottom:10px;')

												.append($('<button>')
												.addClass('xCNBTNPrimeryPlus pull-right')
												.attr('id','oimPmhGrpBothBrowsePdt' + num)
												.attr('type','button')
												.text('+')
												.attr('onclick','JSxBrowseGrpBothMulti('+num+')')
												)

											)

											.append($('<div>')
											.addClass('row')

												.append($('<div>')
												.addClass('col-md-12')
													.append($('<div>')
													.addClass('table-responsive')

														.append($('<input>')
														.attr('type','text')
														.addClass('xCNHide')
														.attr('id','oetGrpBothPdtCode'+num)
														)

														.append($('<input>')
														.attr('type','text')
														.addClass('xCNHide')
														.attr('id','oetGrpBothPdtName'+num)
														)

														.append($('<table>')
														.addClass('table table-hover')
														.css('width','100%')
															.append($('<thead>')
																.append($('<tr>')
																.addClass('xCNCenter')
																	.append($('<th>')
																	.addClass('xCNTextBold')
																	.text('ลำดับ')
																	)
																	.append($('<th>')
																	.addClass('xCNTextBold')
																	.text('รหัส')
																	)
																	.append($('<th>')
																	.addClass('xCNTextBold')
																	.text('ชื่อสินค้า')
																	)
																	.append($('<th>')
																	.addClass('xCNTextBold')
																	.text('บาร์โค๊ด')
																	)	
																	.append($('<th>')
																	.addClass('	')
																	.text('หน่วย')
																	)
																	.append($('<th>')
																	.addClass('xCNTextBold')
																	.text('ราคา')
																	)
																	.append($('<th>')
																	.addClass('xCNTextBold')
																	.text('ลบ')
																	)
																)
															)

															.append($('<tbody>')
															.attr('id','odvTBodyStaGrpBoth'+num)
															)
														)
													)
												)
											)
											

											// .append($('<div>')
											// .addClass('table-responsive')

											// 	.append($('<input>')
											// 	.attr('type','text')
											// 	.addClass('xCNHide')
											// 	.attr('id','oetGrpBothPdtCode'+num)
											// 	)

											// 	.append($('<input>')
											// 	.attr('type','text')
											// 	.addClass('xCNHide')
											// 	.attr('id','oetGrpBothPdtName'+num)
											// 	)

											// 	.append($('<table>')
											// 	.addClass('table table-hover')
											// 	.css('width','100%')
											// 		.append($('<thead>')
											// 			.append($('<tr>')
											// 			.addClass('xCNCenter')
											// 				.append($('<th>')
											// 				.addClass('xCNTextBold')
											// 				.text('ลำดับ')
											// 				)
											// 				.append($('<th>')
											// 				.addClass('xCNTextBold')
											// 				.text('รหัส')
											// 				)
											// 				.append($('<th>')
											// 				.addClass('xCNTextBold')
											// 				.text('ชื่อสินค้า')
											// 				)
											// 				.append($('<th>')
											// 				.addClass('xCNTextBold')
											// 				.text('บาร์โค๊ด')
											// 				)	
											// 				.append($('<th>')
											// 				.addClass('	')
											// 				.text('หน่วย')
											// 				)
											// 				.append($('<th>')
											// 				.addClass('xCNTextBold')
											// 				.text('ราคา')
											// 				)
											// 				.append($('<th>')
											// 				.addClass('xCNTextBold')
											// 				.text('ลบ')
											// 				)
											// 			)
											// 		)

											// 		.append($('<tbody>')
											// 		.attr('id','odvTBodyStaGrpBoth'+num)
											// 		)
											// 	)
											// )


											// Button Remove
											// .append($('<div>')
											// .attr('id','odvFooterStaGrpBoth'+num)
											// .addClass('xCNPmhFooterBox')
											// 	.append($('<span>')
											// 	.addClass('xCNPmhBtnDeleteGrpBothPanal')
											// 	.attr('onclick','JSvDeleteGrpBothPanal('+num+')')
											// 		.append($('<i>')
											// 		.addClass('fa fa-times')
											// 		)
											// 	)
											// )
										)
										
									);
		}else{
			num = num+1;
			JSvAddStaGrpBothPanal(num);
		}
		
		JSvGetDrpdwnCDGrpName();

	}



//Lang Edit In Browse
var nLangEdits = <?=$this->session->userdata("tLangEdit")?>;
//Set Option Browse -----------
//Option Depart
var oPmhBrowseDepart = {
    Title : ['pos5/user','tBrowseDPTTitle'],
    Table:{Master:'TCNMUsrDepart',PK:'FTDptCode'},
    Join :{
        Table:	['TCNMUsrDepart_L'],
        On:['TCNMUsrDepart_L.FTDptCode = TCNMUsrDepart.FTDptCode AND TCNMUsrDepart_L.FNLngID = '+nLangEdits,]
    },
    GrideView:{
        ColumnPathLang	: 'pos5/user',
        ColumnKeyLang	: ['tBrowseDPTCode','tBrowseDPTName'],
        DataColumns		: ['TCNMUsrDepart.FTDptCode','TCNMUsrDepart_L.FTDptName'],
        ColumnsSize     : ['10%','75%'],
        DataColumnsFormat : ['',''],
        WidthModal      : 50,
        Perpage			: 10,
		OrderBy			: ['TCNMUsrDepart.FTDptCode'],
		SourceOrder		: "ASC"
    },
    CallBack:{
        ReturnType	: 'S',
        Value		: ["oetPmhDepartCode","TCNMUsrDepart.FTDptCode"],
		Text		: ["oetPmhDepartName","TCNMUsrDepart_L.FTDptName"],
    },
    RouteAddNew : 'department',
    BrowseLev : nStaPmtBrowseType
}
//Option Depart

//Option Zone
var oPmhBrowseZone = {
	Title : ['pos5/zone','tZNESubTitle'],
	Table:{Master:'TCNMZone',PK:'FTZneChain'},
	Join :{
		Table:	['TCNMZone_L'],
		On:['TCNMZone_L.FTZneChain = TCNMZone.FTZneChain AND TCNMZone_L.FNLngID = '+nLangEdits,]
	},
	Filter:{
		Selector:'oetBchAreCode',
		Table:'TCNMZone',
        Key:'FTAreCode'
	},
	GrideView:{
		ColumnPathLang	: 'pos5/zone',
		ColumnKeyLang	: ['tZNECode','tZNEName'],
		ColumnsSize     : ['15%','75%'],
        WidthModal      : 50,
		DataColumns		: ['TCNMZone.FTZneCode','TCNMZone_L.FTZneName'],
		DataColumnsFormat : ['',''],
		Perpage			: 5,
		OrderBy			: ['TCNMZone.FTZneCode'],
		SourceOrder		: "ASC"
	},
	CallBack:{
		ReturnType	: 'S',
		Value		: ["oetPmhZneTo","TCNMZone.FTZneCode"],
		Text		: ["oetPmhZneToName","TCNMZone_L.FTZneName"],
	},

	RouteAddNew : 'zone',
	BrowseLev : nStaPmtBrowseType
}
//Option Zone
//Option Branch
var oPmhBrowseBranch = {
	
	Title : ['pos5/branch','tBCHTitle'],
	Table:{Master:'TCNMBranch',PK:'FTBchCode'},
	Join :{
		Table:	['TCNMBranch_L'],
		On:['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
	},
	GrideView:{
		ColumnPathLang	: 'pos5/branch',
		ColumnKeyLang	: ['tBCHCode','tBCHName'],
		ColumnsSize     : ['15%','75%'],
        WidthModal      : 50,
		DataColumns		: ['TCNMBranch.FTBchCode','TCNMBranch_L.FTBchName'],
		DataColumnsFormat : ['',''],
		Perpage			: 5,
		OrderBy			: ['TCNMBranch_L.FTBchName'],
		SourceOrder		: "ASC"
	},
	CallBack:{
		ReturnType	: 'S',
		Value		: ["oetPmhBchTo","TCNMBranch.FTBchCode"],
		Text		: ["oetPmhBchToName","TCNMBranch_L.FTBchName"],
	},
	RouteFrom : 'promotion',
	RouteAddNew : 'branch',
	BrowseLev : nStaPmtBrowseType
}
//Option Branch
//Option Customer Group
var oPmhBrowseCstGrp = {
	
	Title : ['pos5/branch','tBCHTitle'],
	Table:{Master:'TCNMCstGrp',PK:'FTCgpCode'},
	Join :{
		Table:	['TCNMCstGrp_L'],
		On:['TCNMCstGrp_L.FTCgpCode = TCNMCstGrp.FTCgpCode AND TCNMCstGrp_L.FNLngID = '+nLangEdits,]
	},
	GrideView:{
		ColumnPathLang	: 'pos5/branch',
		ColumnKeyLang	: ['tBCHCode','tBCHName'],
		ColumnsSize     : ['15%','75%'],
        WidthModal      : 50,
		DataColumns		: ['TCNMCstGrp.FTCgpCode','TCNMCstGrp_L.FTCgpName'],
		DataColumnsFormat : ['',''],
		Perpage			: 5,
		OrderBy			: ['TCNMCstGrp_L.FTCgpName'],
		SourceOrder		: "ASC"
	},
	CallBack:{
		ReturnType	: 'S',
		Value		: ["oetPmgCode","TCNMCstGrp.FTCgpCode"],
		Text		: ["oetPmgCodeName","TCNMCstGrp_L.FTCgpName"],
	},
	RouteAddNew : 'customergroup',
	BrowseLev : nStaPmtBrowseType
}
//Option Customer Group
//Option Suplier
var oPmhBrowseSpl = {
	
	Title : ['pos5/branch','tBCHTitle'],
	Table:{Master:'TCNMSpl',PK:'FTSplCode'},
	Join :{
		Table:	['TCNMSpl_L'],
		On:['TCNMSpl_L.FTSplCode = TCNMSpl.FTSplCode AND TCNMSpl_L.FNLngID = '+nLangEdits,]
	},
	GrideView:{
		ColumnPathLang	: 'pos5/branch',
		ColumnKeyLang	: ['tBCHCode','tBCHName'],
		ColumnsSize     : ['15%','75%'],
        WidthModal      : 50,
		DataColumns		: ['TCNMSpl.FTSplCode','TCNMSpl_L.FTSplName'],
		DataColumnsFormat : ['',''],
		Perpage			: 5,
		OrderBy			: ['TCNMSpl_L.FTSplName'],
		SourceOrder		: "ASC"
	},
	CallBack:{
		ReturnType	: 'S',
		Value		: ["oetSplCode","TCNMSpl.FTSplCode"],
		Text		: ["oetSplCodeName","TCNMSpl_L.FTSplName"],
	},
	RouteAddNew : 'suplier',
	BrowseLev : nStaPmtBrowseType
}
//Option Customer Group




//Event Browse
$('#oimPmtBrowseDepart').click(function(){ localStorage.GrpBothNumItem = ''; JCNxBrowseData('oPmhBrowseDepart');});
$('#oimPmhBrowseZone').click(function(){ localStorage.GrpBothNumItem = ''; JCNxBrowseData('oPmhBrowseZone');});
$('#oimPmhBrowseBranch').click(function(){ localStorage.GrpBothNumItem = ''; JCNxBrowseData('oPmhBrowseBranch');});
$('#obtPmtBrowseCstGrp').click(function(){ localStorage.GrpBothNumItem = ''; JCNxBrowseData('oPmhBrowseCstGrp');});
$('#oimPmtBrowseSpl').click(function(){ localStorage.GrpBothNumItem = ''; JCNxBrowseData('oPmhBrowseSpl');});








//Option Promotion GrpBuy
var oPmhGrpBuyBrowsePdt = {
	
	Title : ['pos5/product','tPDTTitle'],
	Table:{Master:'TCNMPdt',PK:'FTPdtCode'},
	Join :{
		Table:	['TCNMPdt_L','TCNMPdtPackSize','TCNMPdtUnit_L','TCNMPdtBar','TCNTPdtPrice4PDT'],
		On:['TCNMPdt_L.FTPdtCode 	= 	TCNMPdt.FTPdtCode AND TCNMPdt_L.FNLngID = '+nLangEdits,
			"TCNMPdt.FTPdtCode		= 	TCNMPdtPackSize.FTPdtCode AND TCNMPdtPackSize.FCPdtUnitFact = '1'",
			'TCNMPdtPackSize.FTPunCode	= 	TCNMPdtUnit_L.FTPunCode AND TCNMPdtUnit_L.FNLngID='+nLangEdits,
			'TCNMPdt.FTPdtCode	= 	TCNMPdtBar.FTPdtCode AND TCNMPdtPackSize.FTPunCode = TCNMPdtBar.FTPunCode',
			'TCNTPdtPrice4PDT.FTPdtCode	= 	TCNMPdt.FTPdtCode  AND TCNTPdtPrice4PDT.FTPunCode  = TCNMPdtPackSize.FTPunCode AND TCNTPdtPrice4PDT.FTPghDocType = 1 AND TCNTPdtPrice4PDT.FDPghDStart <= GETDATE()',
			]
	},
	GrideView:{
		ColumnPathLang	: 'pos5/product',
		ColumnKeyLang	: ['tPDTCode','tPDTName','tPDTBarcode','tPDTTBUnit','tPDTTBPrice',''],
		ColumnsSize     : ['15%','25%','20%','20%','20%'],
        WidthModal      : 50,
		DataColumns		: ['TCNMPdt.FTPdtCode','TCNMPdt_L.FTPdtName','TCNMPdtBar.FTBarCode','TCNMPdtUnit_L.FTPunName','TCNTPdtPrice4PDT.FCPgdPriceRET','TCNMPdtUnit_L.FTPunCode'],
		DataColumnsFormat : ['','','','',''],
		Perpage			: 5,
		OrderBy			: ['TCNMPdt_L.FTPdtName'],
		SourceOrder		: "ASC"
	},
	Where:{
		Condition : ["AND TCNMPdt.FTPdtForSystem = '1' "]
	},
	CallBack:{
		ReturnType	: 'M',
		StaSingItem : '1',
		Value		: ["oetGrpBuyPdtCode","TCNMPdt.FTPdtCode"],
		Text		: ["oetGrpBuyPdtName","TCNMPdt_L.FTPdtName"],
	},
	BrowsePdt : 1,
	NextFunc:{
		FuncName:'JSxPMHGrpBuyAddRow',
		ArgReturn:['FTPdtCode','FTPdtName','FTBarCode','FTPunName','FCPgdPriceRET','FTPunCode']
    },
	RouteAddNew : 'product',
	BrowseLev : nStaPmtBrowseType
}
//Option Promotion GrpBuy

//Option Promotion GrpJoin
var oPmhGrpJoinBrowsePdt = {
	
	Title : ['pos5/product','tPDTTitle'],
	Table:{Master:'TCNMPdt',PK:'FTPdtCode'},
	Join :{
		Table:	['TCNMPdt_L','TCNMPdtPackSize','TCNMPdtUnit_L','TCNMPdtBar','TCNTPdtPrice4PDT'],
		On:['TCNMPdt_L.FTPdtCode 	= 	TCNMPdt.FTPdtCode AND TCNMPdt_L.FNLngID = '+nLangEdits,
			"TCNMPdt.FTPdtCode		= 	TCNMPdtPackSize.FTPdtCode AND TCNMPdtPackSize.FCPdtUnitFact = '1'",
			'TCNMPdtPackSize.FTPunCode	= 	TCNMPdtUnit_L.FTPunCode AND TCNMPdtUnit_L.FNLngID='+nLangEdits,
			'TCNMPdt.FTPdtCode	= 	TCNMPdtBar.FTPdtCode AND TCNMPdtPackSize.FTPunCode = TCNMPdtBar.FTPunCode',
			'TCNTPdtPrice4PDT.FTPdtCode	= 	TCNMPdt.FTPdtCode  AND TCNTPdtPrice4PDT.FTPunCode  = TCNMPdtPackSize.FTPunCode AND TCNTPdtPrice4PDT.FTPghDocType = 1 AND TCNTPdtPrice4PDT.FDPghDStart <= GETDATE()',
			]
	},
	GrideView:{
		ColumnPathLang	: 'pos5/product',
		ColumnKeyLang	: ['tPDTCode','tPDTName','tPDTBarcode','tPDTTBUnit','tPDTTBPrice',''],
		ColumnsSize     : ['15%','25%','20%','20%','20%'],
        WidthModal      : 50,
		DataColumns		: ['TCNMPdt.FTPdtCode','TCNMPdt_L.FTPdtName','TCNMPdtBar.FTBarCode','TCNMPdtUnit_L.FTPunName','TCNTPdtPrice4PDT.FCPgdPriceRET','TCNMPdtUnit_L.FTPunCode'],
		DataColumnsFormat : ['','','','',''],
		Perpage			: 5,
		OrderBy			: ['TCNMPdt_L.FTPdtName'],
		SourceOrder		: "ASC"
	},
	Where:{
		Condition : ["AND TCNMPdt.FTPdtForSystem = '1' "]
	},
	CallBack:{
		ReturnType	: 'M',
		StaSingItem : '1',
		Value		: ["oetGrpJoinPdtCode","TCNMPdt.FTPdtCode"],
		Text		: ["oetGrpJoinPdtName","TCNMPdt_L.FTPdtName"],
	},
	BrowsePdt : 1,
	NextFunc:{
		FuncName:'JSxPMHGrpJoinAddRow',
		ArgReturn:['FTPdtCode','FTPdtName','FTBarCode','FTPunName','FCPgdPriceRET','FTPunCode']
    },
	RouteAddNew : 'product',
	BrowseLev : nStaPmtBrowseType
}
//Option Promotion GrpJoin

//Option Promotion GrpRcv
var oPmhGrpRcvBrowsePdt = {
	
	Title : ['pos5/product','tPDTTitle'],
	Table:{Master:'TCNMPdt',PK:'FTPdtCode'},
	Join :{
		Table:	['TCNMPdt_L','TCNMPdtPackSize','TCNMPdtUnit_L','TCNMPdtBar','TCNTPdtPrice4PDT'],
		On:['TCNMPdt_L.FTPdtCode 	= 	TCNMPdt.FTPdtCode AND TCNMPdt_L.FNLngID = '+nLangEdits,
			"TCNMPdt.FTPdtCode		= 	TCNMPdtPackSize.FTPdtCode AND TCNMPdtPackSize.FCPdtUnitFact = '1'",
			'TCNMPdtPackSize.FTPunCode	= 	TCNMPdtUnit_L.FTPunCode AND TCNMPdtUnit_L.FNLngID='+nLangEdits,
			'TCNMPdt.FTPdtCode	= 	TCNMPdtBar.FTPdtCode AND TCNMPdtPackSize.FTPunCode = TCNMPdtBar.FTPunCode',
			'TCNTPdtPrice4PDT.FTPdtCode	= 	TCNMPdt.FTPdtCode  AND TCNTPdtPrice4PDT.FTPunCode  = TCNMPdtPackSize.FTPunCode AND TCNTPdtPrice4PDT.FTPghDocType = 1 AND TCNTPdtPrice4PDT.FDPghDStart <= GETDATE()',
			]
	},
	GrideView:{
		ColumnPathLang	: 'pos5/product',
		ColumnKeyLang	: ['tPDTCode','tPDTName','tPDTBarcode','tPDTTBUnit','tPDTTBPrice',''],
		ColumnsSize     : ['15%','25%','20%','20%','20%'],
        WidthModal      : 50,
		DataColumns		: ['TCNMPdt.FTPdtCode','TCNMPdt_L.FTPdtName','TCNMPdtBar.FTBarCode','TCNMPdtUnit_L.FTPunName','TCNTPdtPrice4PDT.FCPgdPriceRET','TCNMPdtUnit_L.FTPunCode'],
		DataColumnsFormat : ['','','','',''],
		Perpage			: 5,
		OrderBy			: ['TCNMPdt_L.FTPdtName'],
		SourceOrder		: "ASC"
	},
	Where:{
		Condition : ["AND TCNMPdt.FTPdtForSystem = '1' "]
	},
	CallBack:{
		ReturnType	: 'M',
		StaSingItem : '1',
		Value		: ["oetGrpRcvPdtCode","TCNMPdt.FTPdtCode"],
		Text		: ["oetGrpRcvPdtName","TCNMPdt_L.FTPdtName"],
	},
	BrowsePdt : 1,
	NextFunc:{
		FuncName:'JSxPMHGrpRcvAddRow',
		ArgReturn:['FTPdtCode','FTPdtName','FTBarCode','FTPunName','FCPgdPriceRET','FTPunCode']
    },
	RouteAddNew : 'product',
	BrowseLev : nStaPmtBrowseType
}
//Option Promotion GrpRcv


//Option Promotion GrpBoth
var oPmhGrpBothBrowsePdt = {

	Title : ['pos5/product','tPDTTitle'],
	Table:{Master:'TCNMPdt',PK:'FTPdtCode'},
	Join :{
		Table:	['TCNMPdt_L','TCNMPdtPackSize','TCNMPdtUnit_L','TCNMPdtBar','TCNTPdtPrice4PDT'],
		On:['TCNMPdt_L.FTPdtCode 	= 	TCNMPdt.FTPdtCode AND TCNMPdt_L.FNLngID = '+nLangEdits,
			"TCNMPdt.FTPdtCode		= 	TCNMPdtPackSize.FTPdtCode AND TCNMPdtPackSize.FCPdtUnitFact = '1'",
			'TCNMPdtPackSize.FTPunCode	= 	TCNMPdtUnit_L.FTPunCode AND TCNMPdtUnit_L.FNLngID='+nLangEdits,
			'TCNMPdt.FTPdtCode	= 	TCNMPdtBar.FTPdtCode AND TCNMPdtPackSize.FTPunCode = TCNMPdtBar.FTPunCode',
			'TCNTPdtPrice4PDT.FTPdtCode	= 	TCNMPdt.FTPdtCode  AND TCNTPdtPrice4PDT.FTPunCode  = TCNMPdtPackSize.FTPunCode AND TCNTPdtPrice4PDT.FTPghDocType = 1 AND TCNTPdtPrice4PDT.FDPghDStart <= GETDATE()',
			]
	},
	GrideView:{
		ColumnPathLang	: 'pos5/product',
		ColumnKeyLang	: ['tPDTCode','tPDTName','tPDTBarcode','tPDTTBUnit','tPDTTBPrice',''],
		ColumnsSize     : ['15%','25%','20%','20%','20%'],
		WidthModal      : 50,
		DataColumns		: ['TCNMPdt.FTPdtCode','TCNMPdt_L.FTPdtName','TCNMPdtBar.FTBarCode','TCNMPdtUnit_L.FTPunName','TCNTPdtPrice4PDT.FCPgdPriceRET','TCNMPdtUnit_L.FTPunCode'],
		DataColumnsFormat : ['','','','',''],
		Perpage			: 5,
		OrderBy			: ['TCNMPdt_L.FTPdtName'],
		SourceOrder		: "ASC"
	},
	Where:{
		Condition : ["AND TCNMPdt.FTPdtForSystem = '1' "]
	},
	CallBack:{
		ReturnType	: 'M',
		StaSingItem : '1',
		Value		: ["oetGrpBothPdtCode","TCNMPdt.FTPdtCode"],
		Text		: ["oetGrpBothPdtName","TCNMPdt_L.FTPdtName"],
	},
	NextFunc:{
		FuncName:'JSxPMHGrpBothAddRow',
		ArgReturn:['FTPdtCode','FTPdtName','FTBarCode','FTPunName','FCPgdPriceRET','FTPunCode']
	},
	RouteAddNew : 'product',
	BrowseLev : nStaPmtBrowseType
}
//Option Promotion GrpBoth

//Option Promotion GrpBoth
var oPmhGrpRejectBrowsePdt = {

	Title : ['pos5/product','tPDTTitle'],
	Table:{Master:'TCNMPdt',PK:'FTPdtCode'},
	Join :{
		Table:  ['TCNMPdt_L','TCNMPdtPackSize','TCNMPdtUnit_L','TCNMPdtBar','TCNTPdtPrice4PDT'],
		On:['TCNMPdt_L.FTPdtCode 	= 	TCNMPdt.FTPdtCode AND TCNMPdt_L.FNLngID = '+nLangEdits,
			"TCNMPdt.FTPdtCode		= 	TCNMPdtPackSize.FTPdtCode AND TCNMPdtPackSize.FCPdtUnitFact = '1'",
			'TCNMPdtPackSize.FTPunCode	= 	TCNMPdtUnit_L.FTPunCode AND TCNMPdtUnit_L.FNLngID='+nLangEdits,
			'TCNMPdt.FTPdtCode	= 	TCNMPdtBar.FTPdtCode AND TCNMPdtPackSize.FTPunCode = TCNMPdtBar.FTPunCode',
			'TCNTPdtPrice4PDT.FTPdtCode	= 	TCNMPdt.FTPdtCode  AND TCNTPdtPrice4PDT.FTPunCode  = TCNMPdtPackSize.FTPunCode AND TCNTPdtPrice4PDT.FTPghDocType = 1 AND TCNTPdtPrice4PDT.FDPghDStart <= GETDATE()',
			]
	},
	GrideView:{
		ColumnPathLang	: 'pos5/product',
		ColumnKeyLang	: ['tPDTCode','tPDTName','tPDTBarcode','tPDTTBUnit','tPDTTBPrice',''],
		ColumnsSize     : ['15%','25%','20%','20%','20%'],
		WidthModal      : 50,
		DataColumns		: ['TCNMPdt.FTPdtCode','TCNMPdt_L.FTPdtName','TCNMPdtBar.FTBarCode','TCNMPdtUnit_L.FTPunName','TCNTPdtPrice4PDT.FCPgdPriceRET','TCNMPdtUnit_L.FTPunCode'],
		DataColumnsFormat : ['','','','',''],
		Perpage			: 5,
		OrderBy			: ['TCNMPdt_L.FTPdtName'],
		SourceOrder		: "ASC"
	},
	Where:{
		Condition : ["AND TCNMPdt.FTPdtForSystem = '1' "]
	},
	CallBack:{
		ReturnType	: 'M',
		StaSingItem : '1',
		Value		: ["oetGrpRejectPdtCode","TCNMPdt.FTPdtCode"],
		Text		: ["oetGrpRejectPdtName","TCNMPdt_L.FTPdtName"],
	},
	NextFunc:{
		FuncName:'JSxPMHGrpRejectAddRow',
		ArgReturn:['FTPdtCode','FTPdtName','FTBarCode','FTPunName','FCPgdPriceRET','FTPunCode']
	},
	RouteAddNew : 'product',
	BrowseLev : nStaPmtBrowseType
}
//Option Promotion GrpReject

$('#oimPmhGrpBuyBrowsePdt').click(function(){ localStorage.GrpBothNumItem = ''; JCNxBrowseProductData('oPmhGrpBuyBrowsePdt');});
$('#oimPmhGrpJoinBrowsePdt').click(function(){ localStorage.GrpBothNumItem = ''; JCNxBrowseProductData('oPmhGrpJoinBrowsePdt');});
$('#oimPmhGrpRcvBrowsePdt').click(function(){ localStorage.GrpBothNumItem = ''; JCNxBrowseProductData('oPmhGrpRcvBrowsePdt');});
$('#oimPmhGrpRejectBrowsePdt').click(function(){ localStorage.GrpBothNumItem = ''; JCNxBrowseProductData('oPmhGrpRejectBrowsePdt');});


function JSxBrowseGrpBothMulti(ptNumItem){
	
	localStorage.GrpBothNumItem = ptNumItem;

	JCNxBrowseProductData('oPmhGrpBothBrowsePdt');

}


function JSxPMHGrpBothAddRow(poJsonData){

	

	var tPmdGrpName  = $('#olaPmdGrpBothName').text();/*ชื่อกลุ่มจัดรายการ*/

	//เรื่อง Group Both multi Add
	nGrpBothNumItem = localStorage.GrpBothNumItem
	if(nGrpBothNumItem != '' || nGrpBothNumItem != undefined){
		nGrpBothNumItem = nGrpBothNumItem 
	}else{
		nGrpBothNumItem = '';
	}
	//เรื่อง Group Both multi Add

	$('#odvTBodyStaGrpBoth'+nGrpBothNumItem+' tr').remove();
	for (var n = 0; n < poJsonData.length; n++){
			
			nTRID = JCNnRandomInteger(100,1000000);

			aColDatas = JSON.parse(poJsonData[n]);

				FTPdtCode 			= aColDatas[0];
				FTPdtName 			= aColDatas[1];
				FTBarCode 			= aColDatas[2];
				FTUnit 				= aColDatas[3];
				FCPmdSetPriceOrg 	= aColDatas[4];
				FTPunCode 			= aColDatas[5];

				if(FTPdtCode == '' || FTPdtCode == undefined){
					FTPdtCode = '-';
				}

				if(FCPmdSetPriceOrg == '' || FCPmdSetPriceOrg == undefined){
					FCPmdSetPriceOrg = '-';
				}else{
					FCPmdSetPriceOrg =  parseFloat(FCPmdSetPriceOrg);
					FCPmdSetPriceOrg =  FCPmdSetPriceOrg.toFixed(2);
				}

				if(FTBarCode == '' || FTBarCode == undefined){
					FTBarCode = '-';
				}


				$('#odvTBodyStaGrpBoth'+nGrpBothNumItem).append($('<tr>')
				.addClass('text-center xCNTextDetail2')
				.attr('id','otrStaGrpBoth'+nTRID)
				.attr('data-otrval',aColDatas[0])
					.append($('<input>')
					.attr('name','ohdGrpBoth'+nGrpBothNumItem+'[]')
					.addClass('xCNHide')
					.val(	tPmdGrpName+','+			/*FTPmdGrpName*/
							FTPdtCode+','+ 				/*FTPdtCode*/
							aColDatas[4]+','+	/*FCPmdSetPriceOrg*/
							FTPunCode+','+				/*FTPunCode*/
							'GrpBoth'+nGrpBothNumItem	/*FTPmdGrpCode*/
							
						)
					)
					//Append Td Row
					.append($('<td>')
					.text(n+1)
					)
					
					//Append Td Code
					.append($('<td>')
					.addClass('text-left')
					.text(FTPdtCode)
					)

					//Append Td Pdt Name
					.append($('<td>')
					.addClass('text-left')
					.text(FTPdtName)
					)

					//Append Td BarCode
					.append($('<td>')
					.addClass('text-left')
					.text(FTBarCode)
					)

					//Append Td Unit
					.append($('<td>')
					.text(FTUnit)
					)

					//Append Td Prince
					.append($('<td>')
					.addClass('text-right')
					.text(FCPmdSetPriceOrg)
					)

					//Append Td Delete
					.append($('<td>')
					.attr('class', 'text-center')
						.append($('<lable>')
						.attr('class','xCNTextLink')
							.append($('<i>')
							.attr('class','fa fa-trash-o')
							.attr('onclick','JSnRemoveRow(this)')
							)
						)
					)
						
			)

	}
	localStorage.GrpBothNumItem = ''; //Remove Local Storage
}


function JSxPMHGrpRcvAddRow(poJsonData){

	var tPmdGrpName  = $('#olaPmdGrpRcvName').text();/*ชื่อกลุ่มจัดรายการ*/

  $('#odvTBodyStaGrpRcv tr').remove();   
  for (var n = 0; n < poJsonData.length; n++) {
			  
			nTRID = JCNnRandomInteger(100,1000000);

			aColDatas = JSON.parse(poJsonData[n]);

				FCPmdSetPriceOrg  = aColDatas[4];

				if(FCPmdSetPriceOrg == '' || FCPmdSetPriceOrg == undefined){
					FCPmdSetPriceOrg = '-';
				}else{
					FCPmdSetPriceOrg =  parseFloat(FCPmdSetPriceOrg);
					FCPmdSetPriceOrg =  FCPmdSetPriceOrg.toFixed(2);
				}
			
				$('#odvTBodyStaGrpRcv').append($('<tr>')
				.addClass('text-center xCNTextDetail2')
				.attr('id','otrStaGrpRcv'+nTRID)
				.attr('data-otrval',aColDatas[0])
					.append($('<input>')
					.attr('name','ohdGrpRcv[]')	
					.addClass('xCNHide')
					.val(	tPmdGrpName+','+	/*FTPmdGrpName*/
							aColDatas[0]+','+ 	/*FTPdtCode*/
							aColDatas[4]+','+	/*FCPmdSetPriceOrg*/
							aColDatas[5]	  	/*FTPunCode*/
							
						)
					)
					.append($('<td>')
					.text(n+1)
					)
					
					.append($('<td>')
					.addClass('text-left')
					.text(aColDatas[0])
					)
	
					.append($('<td>')
					.addClass('text-left')
					.text(aColDatas[1])
					)

					.append($('<td>')
					.addClass('text-left')
					.text(aColDatas[2])
					)

					.append($('<td>')
					.text(aColDatas[3])
					)

					.append($('<td>')
					.addClass('text-right')
					.text(FCPmdSetPriceOrg)
					)

					//Append Td Delete
					.append($('<td>')
					.attr('class', 'text-center')
						.append($('<lable>')
						.attr('class','xCNTextLink')
							.append($('<i>')
							.attr('class','fa fa-trash-o')
							.attr('onclick','JSnRemoveRow(this)')
							)
						)
					)
						  
			)
			
  }
  localStorage.GrpBothNumItem = ''; //Remove Local Storage
}


function JSxPMHGrpJoinAddRow(poJsonData){

	var tPmdGrpName  = $('#olaPmdGrpJoinName').text();/*ชื่อกลุ่มจัดรายการ*/
	
	  $('#odvTBodyStaGrpJoin tr').remove();   
      for (var n = 0; n < poJsonData.length; n++) {
		  		
                nTRID = JCNnRandomInteger(100,1000000);

				aColDatas = JSON.parse(poJsonData[n]);

					FCPmdSetPriceOrg  = aColDatas[4];

					if(FCPmdSetPriceOrg == '' || FCPmdSetPriceOrg == undefined){
						FCPmdSetPriceOrg = '-';
					}else{
						FCPmdSetPriceOrg =  parseFloat(FCPmdSetPriceOrg);
						FCPmdSetPriceOrg =  FCPmdSetPriceOrg.toFixed(2);
					}
				
					$('#odvTBodyStaGrpJoin').append($('<tr>')
					.addClass('text-center xCNTextDetail2')
					.attr('id','otrStaGrpJoin'+nTRID)
					.attr('data-otrval',aColDatas[0])
						.append($('<input>')
						.attr('name','ohdGrpJoin[]')	
						.addClass('xCNHide')
						.val(	tPmdGrpName+','+	/*FTPmdGrpName*/
								aColDatas[0]+','+ 	/*FTPdtCode*/
								aColDatas[4]+','+	/*FCPmdSetPriceOrg*/
								aColDatas[5]	  	/*FTPunCode*/
								
							)
						)
						.append($('<td>')
						.text(n+1)
						)
						
						.append($('<td>')
						.addClass('text-left')
						.text(aColDatas[0])
						)
		
						.append($('<td>')
						.addClass('text-left')
						.text(aColDatas[1])
						)

						.append($('<td>')
						.addClass('text-left')
						.text(aColDatas[2])
						)

						.append($('<td>')
						.text(aColDatas[3])
						)

						.append($('<td>')
						.addClass('text-right')
						.text(FCPmdSetPriceOrg)
						)

						//Append Td Delete
						.append($('<td>')
						.attr('class', 'text-center')
							.append($('<lable>')
							.attr('class','xCNTextLink')
								.append($('<i>')
								.attr('class','fa fa-trash-o')
								.attr('onclick','JSnRemoveRow(this)')
								)
							)
						)
                              
                )
                
	  }
	  localStorage.GrpBothNumItem = ''; //Remove Local Storage
}

function JSxPMHGrpBuyAddRow(poJsonData){

	var tPmdGrpName  = $('#olaPmdGrpBuyName').text();/*ชื่อกลุ่มจัดรายการ*/

  $('#odvTBodyStaGrpBuy tr').remove();   
  for (var n = 0; n < poJsonData.length; n++) {
			  
			nTRID = JCNnRandomInteger(100,1000000);

			aColDatas = JSON.parse(poJsonData[n]);

			FCPmdSetPriceOrg  = aColDatas[4];

			if(FCPmdSetPriceOrg == '' || FCPmdSetPriceOrg == undefined){
				FCPmdSetPriceOrg = '-';
			}else{
				FCPmdSetPriceOrg =  parseFloat(FCPmdSetPriceOrg);
				FCPmdSetPriceOrg =  FCPmdSetPriceOrg.toFixed(2);
			}
			
				$('#odvTBodyStaGrpBuy').append($('<tr>')
				.addClass('text-center xCNTextDetail2')
				.attr('id','otrStaGrpBuy'+nTRID)
				.attr('data-otrval',aColDatas[0])
					.append($('<input>')
					.attr('name','ohdGrpBuy[]')	
					.addClass('xCNHide')
					.val(	tPmdGrpName+','+	/*FTPmdGrpName*/
							aColDatas[0]+','+ 	/*FTPdtCode*/
							aColDatas[4]+','+	/*FCPmdSetPriceOrg*/
							aColDatas[5]	  	/*FTPunCode*/
							
						)
					)
					.append($('<td>')
					.text(n+1)
					)
					
					.append($('<td>')
					.addClass('text-left')
					.text(aColDatas[0])
					)
	
					.append($('<td>')
					.addClass('text-left')
					.text(aColDatas[1])
					)

					.append($('<td>')
					.addClass('text-left')
					.text(aColDatas[2])
					)

					.append($('<td>')
					.text(aColDatas[3])
					)

					.append($('<td>')
					.addClass('text-right')
					.text(FCPmdSetPriceOrg)
					)


					//Append Td Delete
					.append($('<td>')
					.attr('class', 'text-center')
						.append($('<lable>')
						.attr('class','xCNTextLink')
							.append($('<i>')
							.attr('class','fa fa-trash-o')
							.attr('onclick','JSnRemoveRow(this)')
							)
						)
					)
						  
			)
			
  }
    localStorage.GrpBothNumItem = ''; //Remove Local Storage
}


function JSxPMHGrpRejectAddRow(poJsonData){

var tPmdGrpName  = $('#olaPmdGrpRejectName').text();/*ชื่อกลุ่มจัดรายการ*/

$('#odvTBodyStaGrpReject tr').remove();   
for (var n = 0; n < poJsonData.length; n++) {
		  
		nTRID = JCNnRandomInteger(100,1000000);

		aColDatas = JSON.parse(poJsonData[n]);
		
		FCPmdSetPriceOrg  = aColDatas[4];

		if(FCPmdSetPriceOrg == '' || FCPmdSetPriceOrg == undefined){
			FCPmdSetPriceOrg = '-';
		}else{
			FCPmdSetPriceOrg =  parseFloat(FCPmdSetPriceOrg);
			FCPmdSetPriceOrg =  FCPmdSetPriceOrg.toFixed(2);
		}
			$('#odvTBodyStaGrpReject').append($('<tr>')
			.addClass('text-center xCNTextDetail2')
			.attr('id','otrStaGrpReject'+nTRID)
			.attr('data-otrval',aColDatas[0])
				.append($('<input>')
				.attr('name','ohdGrpReject[]')	
				.addClass('xCNHide')
				.val(	tPmdGrpName+','+	/*FTPmdGrpName*/
						aColDatas[0]+','+ 	/*FTPdtCode*/
						aColDatas[4]+','+	/*FCPmdSetPriceOrg*/
						aColDatas[5]	  	/*FTPunCode*/
						
					)
				)
				.append($('<td>')
				.text(n+1)
				)
				
				.append($('<td>')
				.addClass('text-left')
				.text(aColDatas[0])
				)

				.append($('<td>')
				.addClass('text-left')
				.text(aColDatas[1])
				)

				.append($('<td>')
				.addClass('text-left')
				.text(aColDatas[2])
				)

				.append($('<td>')
				.text(aColDatas[3])
				)

				.append($('<td>')
				.addClass('text-right')
				.text(FCPmdSetPriceOrg)
				)

				//Append Td Delete
				.append($('<td>')
				.attr('class', 'text-center')
					.append($('<lable>')
					.attr('class','xCNTextLink')
						.append($('<i>')
						.attr('class','fa fa-trash-o')
						.attr('onclick','JSnRemoveRow(this)')
						)
					)
				)
					  
		)
		
}
	localStorage.GrpBothNumItem = ''; //Remove Local Storage
}



// Function : Del Row Html
function JSnRemoveRow(ele){

		var nRowID = $(ele).parent().parent().parent().attr('id')
		var tVal = $(ele).parent().parent().parent().attr('data-otrval')
		
		var tGrpName = $(ele).parents().eq(6).data('grpname'); /*Get Group Name ของ เรื่องนั้นๆ*/

		//GrpBoth get nItem เพื่อลบแต่ละ Div->row
		if(tGrpName == 'GrpBoth'){
			var tGrpItem = $(ele).parents().eq(6).data('grpitem'); /*Get Group Name ของ เรื่องนั้นๆ*/
		}else{
			tGrpItem = '';
		}

		tPdtCodeAllSel = $('#oet'+tGrpName+'PdtCode'+tGrpItem).val();

		if(tPdtCodeAllSel != undefined){ /*เข้าทุก Group ยกเว้น Condition เพราะ Condition ไม่ต้องลบ Val */
			aPdtCodeAllSel1 = tPdtCodeAllSel.split(tVal); /*split ค่าที่เลือก ออก*/ 
			$('#oet'+tGrpName+'PdtCode'+tGrpItem).val(aPdtCodeAllSel1).trigger('change');
			$('#ohd'+tGrpName+tGrpItem+"-"+tVal).remove().trigger('change');
		}

		/*ลบ Div Condition*/
		tConditionHave = $('#ohdStaGrpCondHave').val();
		tGetCoundHave = $('#ohdControllCound').val();
		if(tConditionHave != '' || tConditionHave != undefined){
			var nGrpCound = $(ele).parent().parent().parent().data('grpcound');
			tNewConditionAfterDel = tConditionHave.replace(nGrpCound,''); /* ลบค่าเดิมออก แทนที่ค่า ว่าง */
			$('#ohdStaGrpCondHave').val(tNewConditionAfterDel);


			var tGetCound = $(ele).parent().parent().parent().data('getcound'); /*Get Group Name ของ เรื่องนั้นๆ*/
			tGetCound = parseInt(tGetCound);
			tGetCoundHave = tGetCoundHave.replace(tGetCound,''); /* ลบค่าเดิมออก แทนที่ค่า ว่าง */
			$('#ohdControllCound').val(tGetCoundHave);
			
			JSxPMTControlGetCond(); //Controll Div สมาชิก เรื่อง Point
		}
		var tableID = $(ele).closest('table').attr('id');
		var rowsCount = $('#' + tableID + ' tr').length - 1;
		var colCount = '';
		$('#otbGrpCondition tr:nth-child(1) th').each(function () {
			colCount++;
		});
		$(ele).parent().parent().parent().remove();
		if(rowsCount < 1){
			$('#' + tableID).append($("<tr id='" + tableID + "_NotFound'><td class='text-center xCNTextDetail2' colspan='" + colCount + "'><?= language('common/main','tCMNNotFoundData')?></td></tr>"));
		}
}



	function JSxShowInputEditInRow(ptGrpName,pnItemBoth){
		
			if(pnItemBoth != undefined){
				nItemBoth = pnItemBoth;
			}else{
				nItemBoth = '';
				
			}
			
			$('#odvHeadBarShowPanal'+ptGrpName+nItemBoth).addClass('xCNHide'); //ซ่อนปุ่มที่กด

			$('#odvHeadBarEditPanal'+ptGrpName+nItemBoth).removeClass('xCNHide'); //ซ่อนปุ่มที่กด

			// $('#ohdPmd'+ptGrpName+'Name'+pnItemBoth).removeClass('xCNHide'); //แสดง Input ที่ใช้ในการ Edit

	}

</script>
