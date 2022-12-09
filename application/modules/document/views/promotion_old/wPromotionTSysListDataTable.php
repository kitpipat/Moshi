<input type="text" class="xCNHide" id="ohdSpmCode" value="">
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="otbActive table table-striped" style="width:100%">
                <thead>
                    <tr class="xCNCenter">
						<th class="xCNTextBold"><?= language('document/promotion/promotion','tPMTTBName')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($aDataList['rtCode'] == 1 ):?>
                        <?php foreach($aDataList['raItems'] AS $key=>$aValue){ ?>
                            <tr id="otrTSysPmt<?=$key?>">
                                <td class="xCNRPCSelect" role="tab" data-toggle="tab" data-code="<?=$aValue['FTSpmCode']?>" data-name="<?=$aValue['FTSpmName']?>">
                                    <?=$aValue['FTSpmCode']?> : <?=$aValue['FTSpmName']?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php else:?>
                        <tr class="text-center">
                            <td><?php echo language('common/main','tCMNNotFoundData');  ?></td>
                        </tr>
                    <?php endif;?>
                <tbody>
            </table>
            <!-- <table class="table table-striped" style="width:100%">
                <thead>
                    <tr class="xCNCenter">
						<th class="xCNTextBold"><?= language('document/promotion/promotion','tPMTTBCode')?></th>
						<th class="xCNTextBold"><?= language('document/promotion/promotion','tPMTTBName')?></th>
                    </tr>
                </thead>
                <tbody id="odvTSysList">
                <?php if($aDataList['rtCode'] == 1 ):?>
                    <?php foreach($aDataList['raItems'] AS $key=>$aValue){ ?>
                        <tr class="text-center xCNTextDetail2 otrTSysPmt" id="otrTSysPmt<?=$key?>" data-code="<?=$aValue['FTSpmCode']?>" data-name="<?=$aValue['FTSpmName']?>">
							<td><?=$aValue['FTSpmCode']?></td>
                            <td style="text-align:left;"><?=$aValue['FTSpmName']?></td>
                        </tr>
                    <?php } ?>
                <?php else:?>
                    <tr><td class='text-center xCNTextDetail2' colspan='2'><?= language('common/main','tCMNNotFoundData')?></td></tr>
                <?php endif;?>
                </tbody>
            </table> -->
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

<!-- 
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
</div> -->



<script type="text/javascript">
$('ducument').ready(function(){
    JSxShowButtonChoose();
	var aArrayConvert = [JSON.parse(localStorage.getItem("LocalItemData"))];
    var nlength = $('#odvTSysList').children('tr').length;

	// $('#odvTSysList tr td').click(function(){
			
    //     var nCode = $(this).parent('tr').data('code');  //code
    //     var tName = $(this).parent('tr').data('name');  //name

    //     tSpmCode = $('#ohdSpmCode').val();
    //     if(tSpmCode == '' || tSpmCode != nCode){
    //         $('#ohdSpmCode').val(nCode); //put ค่า
    //         // $(this).parent('tr').css('background-color','#d9d9d9');
    //     }else{
    //         $('#ohdSpmCode').val(''); //put ค่า
    //         // $(this).parent('tr').css('background-color','#fff');
    //     }

    //     JSxShowButtonChoose();

    // });
    
    $('.xCNRPCSelect').click(function(){
        var nCode = $(this).data('code');
        
		tSpmCode = $('#ohdSpmCode').val();
        if(tSpmCode == '' || tSpmCode != nCode){
			$('.otbActive tr').removeClass('active');
            $(this).parent().addClass('active');
            $('#ohdSpmCode').val(nCode); //put ค่า
            // alert('GET VALUE: ' + $('#ohdSpmCode').val());
        }

	});

});
</script>

