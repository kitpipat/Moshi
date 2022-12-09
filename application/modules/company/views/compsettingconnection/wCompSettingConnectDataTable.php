<?php
    if($aDataList['rtCode'] == '1'){
        $nCurrentPage = $aDataList['rnCurrentPage'];
    }else{
        $nCurrentPage = '1';
    }
?>

<div class="row">
    <div class="col-md-12">
        <input type="hidden" id="nCurrentPageTB" value="<?=$nCurrentPage;?>">
        <div class="table-responsive">
            <table class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?php echo language('company/compsettingconnect/compsettingconnect','tCompSettingChoose')?></th>
                        <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?php echo language('company/compsettingconnect/compsettingconnect','tCompSettingType')?></th>
                        <th nowrap class="xCNTextBold" style="width:15%;text-align:center;"><?php echo language('company/compsettingconnect/compsettingconnect','tCompSettingUrlConnect')?></th>
						<th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?php echo language('company/compsettingconnect/compsettingconnect','tCompSettingPort')?></th>
                        <th nowrap class="xCNTextBold" style="width:10%;text-align:center;"><?php echo language('company/compsettingconnect/compsettingconnect','tCompSettingUrlKey')?></th>
                        <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?php echo language('company/compsettingconnect/compsettingconnect','tCompSettingDelete')?></th>
                        <th nowrap class="xCNTextBold" style="width:5%;text-align:center;"><?php echo language('company/compsettingconnect/compsettingconnect','tCompSettingEdit')?></th>
                    </tr>
                </thead>
                <tbody id="odvCompSetingConlist">
                    <?php if($aDataList['rtCode'] === '1'):?>
                        <?php foreach($aDataList['raItems'] AS $key=>$aValue){ ?>
                            <tr class="text-center xCNTextDetail2" id="otrurlid<?=$key?>"
                                data-urlid="<?=$aValue['FNUrlID'];?>"  
                                data-urladdress="<?=$aValue['FTUrlAddress'];?>" 
                            > 
                            <td class="text-center">
                                <label class="fancy-checkbox">
                                    <input id="ocbListItem<?=$key?>" type="checkbox" class="ocbListItem" name="ocbListItem[]">
                                    <span>&nbsp;</span>
                                </label>
                            </td>
                            <?php
                                switch ($aValue['FNUrlType']){
                                    case 9: //API2CNMember
                                        $tStaUrlType  =  language('company/compsettingconnect/compsettingconnect','tCompURLAPI2CNMember');
                                    break;
                                    case 10: //API2RDFace
                                        $tStaUrlType  =  language('company/compsettingconnect/compsettingconnect','tCompURLAPI2RDFace');
                                    break;
                                    case 11: //tCompURLAPI2RDFaceRegis
                                        $tStaUrlType  =  language('company/compsettingconnect/compsettingconnect','tCompURLAPI2RDFaceRegis');
                                    break;
                                    default: 
                                        $tStaUrlType  =  language('company/compsettingconnect/compsettingconnect','tCompURLAPI2CNMember');
                                }                            
                            ?>
                                <td nowrap class="text-left"><?php echo $tStaUrlType;?></a></td>
                                <td nowrap class="text-left"><?=$aValue['FTUrlAddress'];?></td>
                                <td nowrap class="text-center"><?=$aValue['FTUrlPort'];?></td>
                                <td nowrap class="text-left"><?=$aValue['FTUrlKey'];?></td>


                                <td nowrap class="text-center">
                                    <img class="xCNIconTable" src="<?php echo  base_url().'/application/modules/common/assets/images/icons/delete.png'?>" onClick="JSxCompSettingConnectDelete('<?php echo $aValue['FNUrlType']?>','<?php echo $aValue['FTUrlAddress']?>','<?php echo $aValue['FNUrlID']?>','<?php echo language('common/main/main','tBCHYesOnNo')?>')">
                                </td>

                                <td nowrap class="text-center">
                                    <img class="xCNIconTable xWIMGShpShopEdit" src="<?=base_url().'/application/modules/common/assets/images/icons/edit.png'?>" onClick="JSvCallPageCompSettingConnectEdit('<?=$aValue['FNUrlID']?>');">
                                </td>
                            </tr>
                        <?php } ?>
                    <?php else:?>
                        <tr><td class='text-center xCNTextDetail2' colspan='12'><?=language('common/main/main','tCMNNotFoundData')?></td></tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Click Page -->
<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-left">
        <p><?=language('common/main/main','tResultTotalRecord')?> <?=$aDataList['rnAllRow']?> <?=language('common/main/main','tRecord')?> <?=language('common/main/main','tCurrentPage')?> <?=$aDataList['rnCurrentPage']?> / <?=$aDataList['rnAllPage']?></p>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <div class="xWUSRLPaging btn-toolbar pull-right">
        <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvCompSettingConClickPage('previous')" class="btn btn-white btn-sm" <?=$tDisabledLeft ?>> 
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
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
                <button onclick="JSvCompSettingConClickPage('<?=$i?>')" type="button" class="btn xCNBTNNumPagenation <?=$tActive ?>" <?=$tDisPageNumber ?>><?=$i?></button>
            <?php } ?>
            <?php if($nPage >= $aDataList['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvCompSettingConClickPage('next')" class="btn btn-white btn-sm" <?=$tDisabledRight ?>>
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>

<!--Modal Delete Mutirecord-->
<div id="odvModalDeleteMutirecord" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="overflow: hidden auto; z-index: 7000; display: none;">
	<div class="modal-dialog">
		<div class="modal-content text-left">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?=language('common/main/main', 'tModalDelete')?></label>
			</div>
			<div class="modal-body">
            <span id="ospConfirmDelete"></span>
                <input type="hidden" id="ohdConfirmIDDeleteMutirecordUrlId">
                <input type="hidden" id="ohdConfirmIDDeleteMutirecordAddress">
			</div>
			<div class="modal-footer">
				<button id="osmConfirm" type="button" class="btn xCNBTNPrimery" onClick="JSxCompSettingConDeleteMutirecord('<?php echo $nCurrentPage?>','<?php echo $aValue['FNUrlType']?>','<?php echo $aValue['FTUrlAddress']?>','<?php echo $aValue['FNUrlID']?>')"><?=language('common/main/main', 'tModalConfirm')?></button>
        		<button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
			</div>
		</div>
	</div>
</div>

<!--Modal Delete Single-->
<div id="odvModalDeleteSingle" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" style="overflow: hidden auto; z-index: 7000; display: none;">
	<div class="modal-dialog">
		<div class="modal-content text-left">
			<div class="modal-header xCNModalHead">
				<label class="xCNTextModalHeard"><?=language('common/main/main', 'tModalDelete')?></label>
			</div>
			<div class="modal-body">
				<span id="ospConfirmDelete"> - </span>
				<input type='hidden' id="ohdConfirmIDDelete">
			</div>
			<div class="modal-footer">
				<button id="osmConfirmDelete" type="button" class="btn xCNBTNPrimery"><?=language('common/main/main', 'tModalConfirm')?></button>
        		<button type="button" class="btn xCNBTNDefult" data-dismiss="modal"><?=language('common/main/main', 'tModalCancel')?></button>
			</div>
		</div>
	</div>
</div>
<!--End modal Delete Single-->

<script>
    var nUrlType = "<?php echo count($aCheckUrlType['raItems'])?>";
    if(nUrlType == 3){
        $('#obtCompSetConnection').hide();
    }else{
        $('#obtCompSetConnection').show();
    }

 // Select List CompSettingConnect Table Item
$(function() {
    JSxCompSettingConShowButtonChoose();
    var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    var nlength = $('#odvCompSetingConlist ').children('tr').length;
    for($i=0; $i < nlength; $i++){
        var tDataCode = $('#otrurlid'+$i).data('urlid')
        if(aArrayConvert == null || aArrayConvert == ''){
        }else{
            var aReturnRepeat = findObjectByKey(aArrayConvert[0],'urlid',tDataCode);
            if(aReturnRepeat == 'Dupilcate'){
                $('#ocbListItem'+$i).prop('checked', true);
            }else{ }
        }
    }

    $('.ocbListItem').click(function(){
        var nUrlid   = $(this).parent().parent().parent().data('urlid');  //urlid
        var tUrlAddr = $(this).parent().parent().parent().data('urladdress');  //code


        $(this).prop('checked', true);
        var LocalItemData = localStorage.getItem("LocalItemData");
        var obj = [];
        if(LocalItemData){
            obj = JSON.parse(LocalItemData);
        }else{ }
        var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];

        if(aArrayConvert == '' || aArrayConvert == null){
            obj.push({"nUrlid": nUrlid, "tUrlAddr": tUrlAddr });
            localStorage.setItem("LocalItemData",JSON.stringify(obj));
            JSxCompSettingConPaseCodeDelInModal();

        }else{
            var aReturnRepeat = findObjectByKey(aArrayConvert[0],'nUrlid',nUrlid);
            if(aReturnRepeat == 'None' ){           //ยังไม่ถูกเลือก
                obj.push({"nUrlid": nUrlid, "tUrlAddr": tUrlAddr });
                localStorage.setItem("LocalItemData",JSON.stringify(obj));
                JSxCompSettingConPaseCodeDelInModal();

            }else if(aReturnRepeat == 'Dupilcate'){	//เคยเลือกไว้แล้ว
                localStorage.removeItem("LocalItemData");
                $(this).prop('checked', false);
                var nLength = aArrayConvert[0].length;
                for($i=0; $i<nLength; $i++){
                    if(aArrayConvert[0][$i].nUrlid == nUrlid){
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
                JSxCompSettingConPaseCodeDelInModal();
                }
            }
            JSxCompSettingConShowButtonChoose();
        });
    });

</script>