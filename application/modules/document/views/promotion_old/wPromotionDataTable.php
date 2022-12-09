<?php 
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage = $aDataList['rnCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
?>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped" style="width:100%">
                <thead>
					<tr class="xCNCenter">
						<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                        	<th class="xCNTextBold" style="width:5%;"><?= language('document/promotion/promotion','tPMTTBChoose')?></th>
						<?php endif; ?>
						<th class="xCNTextBold"><?= language('document/promotion/promotion','tPMTTBName')?></th>
						<th class="xCNTextBold"><?= language('document/promotion/promotion','tPMTTBNameSlip')?></th>
                        <th class="xCNTextBold"><?= language('document/promotion/promotion','tPMTTBPmtModel')?></th>
						<th class="xCNTextBold"><?= language('document/promotion/promotion','tPMTTBDateStart')?></th>
						<th class="xCNTextBold"><?= language('document/promotion/promotion','tPMTTBDateStop')?></th>
						<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
							<th class="xCNTextBold" style="width:10%;"><?= language('common/main','tCMNActionDelete')?></th>
						<?php endif; ?>
						<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
						<th class="xCNTextBold" style="width:10%;"><?= language('common/main','tCMNActionEdit')?></th>
						<?php endif; ?>
                    </tr>
                </thead>
                <tbody id="odvRGPList">
				<?php if($aDataList['rtCode'] == 1 ):?>
                    <?php foreach($aDataList['raItems'] AS $key=>$aValue){ ?>
                        <tr class="text-center xCNTextDetail2 otrPromotion" id="otrPromotion<?=$key?>" data-code="<?=$aValue['FTPmhCode']?>" data-name="<?=$aValue['FTPmhName']?>">
							<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
								<td class="text-center">
									<label class="fancy-checkbox">
										<input id="ocbListItem<?=$key?>" type="checkbox" class="ocbListItem" name="ocbListItem[]">
										<span>&nbsp;</span>
									</label>
								</td>
							<?php endif; ?>
                            <td><?=$aValue['FTPmhName']?></td>
                            <td><?=$aValue['FTPmhNameSlip']?></td>
							<td><?=$aValue['FTSpmCode']?></td>
							<td><?=$aValue['FDPmhDStart']." ".$aValue['FDPmhTStart']?></td>
							<td><?=$aValue['FDPmhDStop']." ".$aValue['FDPmhTStop']?></td>
							<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
								<td><img class="xCNIconTable xCNIconDel" src="<?= base_url().'/application/assets/icons/delete.png'?>" onClick="JSnPromotionDel('<?=$nCurrentPage?>','<?=$aValue['FTPmhName']?>','<?=$aValue['FTPmhCode']?>')"></td>
							<?php endif; ?>
							<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
								<td><img class="xCNIconTable" src="<?= base_url().'/application/assets/icons/edit.png'?>" onClick="JSvCallPagePromotionEdit('<?=$aValue['FTPmhCode']?>')"></td>
							<?php endif; ?>
                        </tr>
                    <?php } ?>
                <?php else:?>
                    <tr><td class='text-center xCNTextDetail2' colspan='8'><?= language('common/main','tCMNNotFoundData')?></td></tr>
                <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
		<p><?= language('common/main','tResultTotalRecord')?> <?=$aDataList['rnAllRow']?> <?= language('common/main','tRecord')?> <?= language('common/main','tCurrentPage')?> <?=$aDataList['rnCurrentPage']?> / <?=$aDataList['rnAllPage']?></p>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <div class="xWPagePromotion btn-toolbar pull-right">
			<?php if($nPage == 1){ $tDisabled = 'disabled'; }else{ $tDisabled = '-';} ?>
            <button onclick="JSvPMTClickPage('previous')" class="btn btn-white btn-sm" <?=$tDisabled?>><i class="fa fa-chevron-left f-s-14 t-plus-1"></i></button>

			<?php for($i=max($nPage-2, 1); $i<=max(0, min($aDataList['rnAllPage'],$nPage+2)); $i++){?>
				<?php 
                    if($nPage == $i){ 
                        $tActive = 'active'; 
                        $tDisPageNumber = 'disabled';
                    }else{ 
                        $tActive = '';
                        $tDisPageNumber = '';
                    }
                ?>
            		<button onclick="JSvPMTClickPage('<?=$i?>')" type="button" class="btn xCNBTNNumPagenation <?=$tActive?>" <?=$tDisPageNumber ?>><?=$i?></button>
			<?php } ?>

            <?php if($nPage >= $aDataList['rnAllPage']){ $tDisabled = 'disabled'; }else{ $tDisabled = '-'; } ?>
			<button onclick="JSvPMTClickPage('next')" class="btn btn-white btn-sm" <?=$tDisabled?>><i class="fa fa-chevron-right f-s-14 t-plus-1"></i></button>
        </div>
    </div>
</div>

<div class="modal fade" id="odvModalDelPromotion">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?=language('common/main', 'tModalDelete')?></label>
			</div>
			<div class="modal-body">
				<span id="ospConfirmDelete"> - </span>
				<input type='hidden' id="ohdConfirmIDDelete">
			</div>
			<div class="modal-footer">
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSnPromotionDelChoose('<?=$nCurrentPage?>')"><?=language('common/main', 'tModalConfirm')?></button>
        		<button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?=language('common/main', 'tModalCancel')?></button>
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
		var tDataCode = $('#otrPromotion'+$i).data('code')
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
            JSxPaseCodeDelInModal();
        }else{
            var aReturnRepeat = findObjectByKey(aArrayConvert[0],'nCode',nCode);
            if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
                obj.push({"nCode": nCode, "tName": tName });
                localStorage.setItem("LocalItemData",JSON.stringify(obj));
                JSxPaseCodeDelInModal();
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
                JSxPaseCodeDelInModal();
            }
        }
        JSxShowButtonChoose();
    })
});
</script>
<!-- 


<div class="row" style="margin-left:0px;margin-right:0px">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-hover" style="width:100%">
                <thead>
                    <tr class="xCNCenter">
						<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                        	<th class="xCNTextBold" style="width:5%;"><?= language('document/promotion/promotion','tPMTTBChoose')?></th>
						<?php endif; ?>
						<th class="xCNTextBold"><?= language('document/promotion/promotion','tPMTTBName')?></th>
						<th class="xCNTextBold"><?= language('document/promotion/promotion','tPMTTBNameSlip')?></th>
                        <th class="xCNTextBold"><?= language('document/promotion/promotion','tPMTTBPmtModel')?></th>
						<th class="xCNTextBold"><?= language('document/promotion/promotion','tPMTTBDateStart')?></th>
						<th class="xCNTextBold"><?= language('document/promotion/promotion','tPMTTBDateStop')?></th>
						<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
							<th class="xCNTextBold" style="width:10%;"><?= language('common/main','tCMNActionDelete')?></th>
						<?php endif; ?>
						<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
						<th class="xCNTextBold" style="width:10%;"><?= language('common/main','tCMNActionEdit')?></th>
						<?php endif; ?>
                    </tr>
                </thead>
                <tbody id="odvRGPList">
                <?php if($aDataList['rtCode'] == 1 ):?>
                    <?php foreach($aDataList['raItems'] AS $key=>$aValue){ ?>
                        <tr class="text-center xCNTextDetail2 otrPromotion" id="otrPromotion<?=$key?>" data-code="<?=$aValue['FTPmhCode']?>" data-name="<?=$aValue['FTPmhName']?>">
							<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
								<td class="text-center">
									<label class="fancy-checkbox">
										<input id="ocbListItem<?=$key?>" type="checkbox" class="ocbListItem" name="ocbListItem[]">
										<span>&nbsp;</span>
									</label>
								</td>
							<?php endif; ?>
                            <td><?=$aValue['FTPmhName']?></td>
                            <td><?=$aValue['FTPmhNameSlip']?></td>
							<td><?=$aValue['FTSpmCode']?></td>
							<td><?=$aValue['FDPmhDStart']." ".$aValue['FDPmhTStart']?></td>
							<td><?=$aValue['FDPmhDStop']." ".$aValue['FDPmhTStop']?></td>
							<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaDelete'] == 1) : ?>
                            	<td><i style="display:block;text-align:center;" class="fa fa-trash-o fa-lg" onClick="JSnPromotionDel('<?=$aValue['FTPmhCode']?>')"></i></td>
							<?php endif; ?>
							<?php if($aAlwEvent['tAutStaFull'] == 1 || $aAlwEvent['tAutStaRead'] == 1) : ?>
                            	<td><i style="display:block;text-align:center;" class="fa fa-pencil-square-o fa-lg" onClick="JSvCallPagePromotionEdit('<?=$aValue['FTPmhCode']?>')"></i></td>
							<?php endif; ?>
                        </tr>
                    <?php } ?>
                <?php else:?>
                    <tr><td class='text-center xCNTextDetail2' colspan='8'><?= language('common/main','tCMNNotFoundData')?></td></tr>
                <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row" style="margin-left:0px;margin-right:0px">
    <div class="col-md-6 xCNPadT30">
        <p><?= language('common/main','tResultTotalRecord')?> <?=$aDataList['rnAllRow']?> <?= language('common/main','tRecord')?> <?= language('common/main','tCurrentPage')?> <?=$aDataList['rnCurrentPage']?> / <?=$aDataList['rnAllPage']?></p>
    </div>
    <div class="col-md-6">
        <nav class="pull-right" aria-label="Page navigation">
            <ul class="xWPMTPaging pagination justify-content-center">
                <?php if($nPage == 1){ $tDisabled = 'xCNDisable'; }else{ $tDisabled = '-';} ?>
                <li class="page-item previous <?=$tDisabled?>">
                    <a class="page-link xWBtnPrevious <?=$tDisabled?>" data-npage="previous" tabindex="0" onclick="JSvPMTClickPage('previous')"><?= language('common/main','tPrevious')?></a>
                </li>
                <?php for($i=max($nPage-2, 1); $i<=max(0, min($aDataList['rnAllPage'],$nPage+2)); $i++){?>
                    <?php if($nPage == $i){ $tActive = 'active'; }else{ $tActive = '-'; }?>
                    <li class="page-item <?=$tActive?>">
                        <a class="page-link" onclick="JSvPMTClickPage('<?=$i?>')"><?=$i?></a>
                    </li>
                <?php } ?>
                <?php if($nPage >= $aDataList['rnAllPage']){ $tDisabled = 'xCNDisable'; }else{ $tDisabled = '-'; } ?>
                <li class="page-item next <?=$tDisabled?>">
		            <a class="page-link xWBtnNext <?=$tDisabled?>" data-npage="next" tabindex="0" onclick="JSvPMTClickPage('next')"><?= language('common/main','tNext')?></a>
                </li>
            </ul>
        </nav>
    </div>
</div>



<script type="text/javascript">
$('ducument').ready(function(){
    JSxShowButtonChoose();
	var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
	var nlength = $('#odvRGPList').children('tr').length;
	for($i=0; $i < nlength; $i++){
		var tDataCode = $('#otrPromotion'+$i).data('code')
		if(aArrayConvert == null || aArrayConvert == ''){
		}else{
			var aReturnRepeat = findObjectByKey(aArrayConvert[0],'nCode',tDataCode);
			if(aReturnRepeat == 'Dupilcate'){
				$('#ocbListItem'+$i).prop('checked', true);
			}else{ }
		}
	}
	$('#odvRGPList tr td').click(function(){
		if($(this).is(":last-child")){
			//alert('into Function delete');
		}else if( $(this).is(":nth-last-child(2)")){
			//alert('into Function delete');
		}else{
			$('#odvRGPList > tr').css('background-color','#FFFFFF');
			$(this).parent('tr').css('background-color','#4fbcf31a');
			var nCode = $(this).parent('tr').data('code');  //code
			var tName = $(this).parent('tr').data('name');  //name
			$(this).parent('tr').find('.ocbListItem').prop('checked', true);
			
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
					$('#odvRGPList > tr').css('background-color','');
					$(this).parent('tr').css('background-color','');
					localStorage.removeItem("LocalItemData");
					$(this).parent('tr').find('.ocbListItem').prop('checked', false);
					
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
		}
	});
});
</script> -->

