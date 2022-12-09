<?php 
    if($aTcgDataList['rtCode'] == '1'){
        $nCurrentPage = $aTcgDataList['rnCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
?>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="otbTcgDataList" class="table table-striped">
                <thead>
                    <tr>
                        <?php if($aAlwEventPdtTouchGrp['tAutStaFull'] == 1 || $aAlwEventPdtTouchGrp['tAutStaDelete'] == 1) : ?>
                        <th class="text-center xCNTextBold" style="width:10%;"><?= language('product/pdttouchgroup/pdttouchgroup','tTCGTBChoose')?></th>
                        <?php endif; ?>
                        <th class="text-center xCNTextBold" style="width:10%;"><?= language('product/pdttouchgroup/pdttouchgroup','tTCGTBCode')?></th>
                        <th class="text-center xCNTextBold" style="width:40%;"><?= language('product/pdttouchgroup/pdttouchgroup','tTCGTBName')?></th>
                        <th class="text-center xCNTextBold" style="width:20%;"><?= language('product/pdttouchgroup/pdttouchgroup','tTCGTBStaUse')?></th>
                        <?php if($aAlwEventPdtTouchGrp['tAutStaFull'] == 1 || $aAlwEventPdtTouchGrp['tAutStaDelete'] == 1) : ?>
                        <th class="text-center xCNTextBold" style="width:10%;"><?= language('product/pdttouchgroup/pdttouchgroup','tTCGTBDelete')?></th>
                        <?php endif; ?>
                        <?php if($aAlwEventPdtTouchGrp['tAutStaFull'] == 1 || ($aAlwEventPdtTouchGrp['tAutStaEdit'] == 1 || $aAlwEventPdtTouchGrp['tAutStaRead'] == 1))  : ?>
                        <th class="text-center xCNTextBold" style="width:10%;"><?= language('product/pdttouchgroup/pdttouchgroup','tTCGTBEdit')?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aTcgDataList['rtCode'] == 1 ):?>
                        <?php foreach($aTcgDataList['raItems'] AS $nKey => $aValue):?>
                            <tr class="text-center xCNTextDetail2 otrPdtTouchGrp" id="otrPdtTouchGrp<?=$nKey?>" data-code="<?=$aValue['rtTcgCode']?>" data-name="<?=$aValue['rtTcgName']?>">
                                <?php if($aAlwEventPdtTouchGrp['tAutStaFull'] == 1 || $aAlwEventPdtTouchGrp['tAutStaDelete'] == 1) : ?>
                                <td class="text-center">
                                    <label class="fancy-checkbox">
                                        <input id="ocbListItem<?=$nKey?>" type="checkbox" class="ocbListItem" name="ocbListItem[]">
                                        <span>&nbsp;</span>
                                    </label>
                                </td>
                                <?php endif; ?>
                                <td><?=$aValue['rtTcgCode']?></td>
                                <td class="text-left"><?=$aValue['rtTcgName']?></td>
                                <?php
                                    if($aValue['rtTcgStaUse'] == 1){
                                        $tTcgStaUse     = "ใช้งาน";
                                        $tClassStaUse   = "green";
                                    }else{
                                        $tTcgStaUse     = "ไม่ใช้งาน";
                                        $tClassStaUse   = "red";
                                    }
                                ?> 
                                <td class="text-center" style="color:<?=$tClassStaUse?>;"><?=$tTcgStaUse?></td>
                                <?php if($aAlwEventPdtTouchGrp['tAutStaFull'] == 1 || $aAlwEventPdtTouchGrp['tAutStaDelete'] == 1) : ?>
                                <td>
                                    <img class="xCNIconTable" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>" onClick="JSoPdtTouchGrpDel('<?=$nCurrentPage?>','<?=$aValue['rtTcgName']?>','<?=$aValue['rtTcgCode']?>')">
                                </td>
                                <?php endif; ?>
                                <?php if($aAlwEventPdtTouchGrp['tAutStaFull'] == 1 || ($aAlwEventPdtTouchGrp['tAutStaEdit'] == 1 || $aAlwEventPdtTouchGrp['tAutStaRead'] == 1)) : ?>
                                <td>
                                    <img class="xCNIconTable" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/edit.png'?>" onClick="JSvCallPagePdtTouchGrpEdit('<?=$aValue['rtTcgCode']?>')">
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach;?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='6'><?= language('product/pdttouchgroup/pdttouchgroup','tTCGTBNoData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <p><?= language('common/main/main','tResultTotalRecord')?> <?=$aTcgDataList['rnAllRow']?> <?= language('common/main/main','tRecord')?> <?= language('common/main/main','tCurrentPage')?> <?=$aTcgDataList['rnCurrentPage']?> / <?=$aTcgDataList['rnAllPage']?></p>
    </div>
    <div class="col-md-6">
        <div class="xWPageTouchGrpType btn-toolbar pull-right"> <!-- เปลี่ยนชื่อ Class เป็นของเรื่องนั้นๆ --> 
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvPdtTouchGrpClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aTcgDataList['rnAllPage'],$nPage+2)); $i++){?> <!-- เปลี่ยนชื่อ Parameter Loop เป็นของเรื่องนั้นๆ --> 
                <?php 
                    if($nPage == $i){ 
                        $tActive = 'active'; 
                        $tDisPageNumber = 'disabled';
                    }else{ 
                        $tActive = '';
                        $tDisPageNumber = '';
                    }
                ?>
                <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
                <button onclick="JSvPdtTouchGrpClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>
            <?php if($nPage >= $aTcgDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvPdtTouchGrpClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="odvModalDelPdtTouchGrp">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?=language('common/main/main', 'tModalDelete')?></label>
			</div>
			<div class="modal-body">
				<span id="ospConfirmDelete"> - </span>
				<input type='hidden' id="ohdConfirmIDDelete">
			</div>
			<div class="modal-footer">
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSoPdtTouchGrpDelChoose('<?=$nCurrentPage?>')"><?=language('common/main/main', 'tModalConfirm')?></button>
        		<button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
$('ducument').ready(function(){
    JSxShowButtonChoose();
	var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
	var nlength = $('#odvRGPList').children('tr').length;
	for($i=0; $i < nlength; $i++){
		var tDataCode = $('#otrPdtTouchGrp'+$i).data('code')
		if(aArrayConvert == null || aArrayConvert == ''){
		}else{
			var aReturnRepeat = findObjectByKey(aArrayConvert[0],'nCode',tDataCode);
			if(aReturnRepeat == 'Dupilcate'){
				$('#ocbListItem'+$i).prop('checked', true);
			}else{ }
		}
	}

	$('.ocbListItem').click(function(){
        var nCode = $(this).parent().parent().parent().data('code');  //code
        var tName = $(this).parent().parent().parent().data('name');  //code
        $(this).prop('checked', true);
        var LocalItemData = localStorage.getItem("LocalItemData");
        var obj = [];
        if(LocalItemData){
            obj = JSON.parse(LocalItemData);
        }else{ }
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
        if(aArrayConvert == '' || aArrayConvert == null){
            obj.push({"nCode": nCode, "tName": tName });
            localStorage.setItem("LocalItemData",JSON.stringify(obj));
            JSxTextinModal();
        }else{
            var aReturnRepeat = findObjectByKey(aArrayConvert[0],'nCode',nCode);
            if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
                obj.push({"nCode": nCode, "tName": tName });
                localStorage.setItem("LocalItemData",JSON.stringify(obj));
                JSxTextinModal();
            }else if(aReturnRepeat == 'Dupilcate'){	//เคยเลือกไว้แล้ว
                localStorage.removeItem("LocalItemData");
                $(this).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].nCode == nCode){
                        delete aArrayConvert[0][$i];
                    }
                }
                var aNewarraydata = [];
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i] != undefined){
                        aNewarraydata.push(aArrayConvert[0][$i]);
                    }
                }
                localStorage.setItem("LocalItemData",JSON.stringify(aNewarraydata));
                JSxTextinModal();
            }
        }
        JSxShowButtonChoose();
    })
});
    // $('.ocbListItem').click(function(){
    //     var nCode = $(this).parent().parent().parent().data('code');  //code
    //     var tName = $(this).parent().parent().parent().data('name');  //code
    //     $(this).prop('checked', true);
    //     var LocalItemData = localStorage.getItem("LocalItemData");
    //     var obj = [];
    //     if(LocalItemData){
    //         obj = JSON.parse(LocalItemData);
    //     }else{ }
    //     var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    //     if(aArrayConvert == '' || aArrayConvert == null){
    //         obj.push({"nCode": nCode, "tName": tName });
    //         localStorage.setItem("LocalItemData",JSON.stringify(obj));
    //         JSxTextinModal();
    //     }else{
    //         var aReturnRepeat = findObjectByKey(aArrayConvert[0],'nCode',nCode);
    //         if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
    //             obj.push({"nCode": nCode, "tName": tName });
    //             localStorage.setItem("LocalItemData",JSON.stringify(obj));
    //             JSxTextinModal();
    //         }else if(aReturnRepeat == 'Dupilcate'){	//เคยเลือกไว้แล้ว
    //             localStorage.removeItem("LocalItemData");
    //             $(this).prop('checked', false);
    //             var nLength = aArrayConvert[0].length;
    //             for($i=0; $i<nLength; $i++){
    //                 if(aArrayConvert[0][$i].nCode == nCode){
    //                     delete aArrayConvert[0][$i];
    //                 }
    //             }
    //             var aNewarraydata = [];
    //             for($i=0; $i<nLength; $i++){
    //                 if(aArrayConvert[0][$i] != undefined){
    //                     aNewarraydata.push(aArrayConvert[0][$i]);
    //                 }
    //             }
    //             localStorage.setItem("LocalItemData",JSON.stringify(aNewarraydata));
    //             JSxTextinModal();
    //         }
    //     }
    //     JSxShowButtonChoose();
    // })
</script>