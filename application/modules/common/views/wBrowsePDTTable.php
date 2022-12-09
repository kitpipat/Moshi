<style>
    .xCNClickforModalPDT{
        cursor: pointer;
    }

    .xCNActivePDT > td{
        background-color: #179bfd !important;
        color : #FFF !important;
    }

</style>

<?php
    if($aPriceType[0] == 'Pricesell' && $tSelectTier == 'PDT'){
        $tNewClassStyle = 'xCNClickforModalPDT';
        
    }else{
        $tNewClassStyle = '';
    }
?>

<!--layout table-->
<table id='otbBrowserListPDT' class='table table-striped' style='width:100%'>
    <thead>
        <tr>
            <?php if($aPriceType[0] == 'Cost'){ ?>
                <th class='xCNTextBold' style='text-align:center; width:120px;'><?=language('common/main/main','tModalcodePDT')?></th>
                <th class='xCNTextBold' style='text-align:center; width:220px;'><?=language('common/main/main','tModalnamePDT')?></th>
                <th class='xCNTextBold' style='text-align:center; width:160px;'><?=language('common/main/main','tModalbarcodePDT')?></th>
                <th class='xCNTextBold' style='text-align:center'><?=language('common/main/main','tModalPriceUnit')?></th>
                <th class='xCNTextBold' style='text-align:center'><?=language('common/main/main','tModalPricecost')?></th>
            <?php } else if($aPriceType[0] == 'Pricesell'  && $tSelectTier == 'PDT'){ ?>
                <th class='xCNTextBold' style='text-align:center; width:120px;'><?=language('common/main/main','tModalcodePDT')?></th>
                <th class='xCNTextBold' style='text-align:center; width:160px;'><?=language('common/main/main','tModalnamePDT')?></th>
                <th class='xCNTextBold' style='text-align:center'><?=language('common/main/main','tModalPriceUnit')?></th>
                <th class='xCNTextBold' style='text-align:center'><?=language('common/main/main','tModalPriceSellSAL')?></th>
                <th class='xCNTextBold' style='text-align:center'><?=language('common/main/main','tModalPriceSellPLE')?></th>
                <th class='xCNTextBold' style='text-align:center'><?=language('common/main/main','tModalPriceSellONL')?></th>
            <?php } else if($aPriceType[0] == 'Pricesell'){ ?>
                <th class='xCNTextBold' style='text-align:center; width:120px;'><?=language('common/main/main','tModalcodePDT')?></th>
                <th class='xCNTextBold' style='text-align:center; width:160px;'><?=language('common/main/main','tModalnamePDT')?></th>
                <th class='xCNTextBold' style='text-align:center; width:160px;'><?=language('common/main/main','tModalbarcodePDT')?></th>
                <th class='xCNTextBold' style='text-align:center'><?=language('common/main/main','tModalPriceUnit')?></th>
                <th class='xCNTextBold' style='text-align:center'><?=language('common/main/main','tModalPriceSellSAL')?></th>
                <th class='xCNTextBold' style='text-align:center'><?=language('common/main/main','tModalPriceSellPLE')?></th>
                <th class='xCNTextBold' style='text-align:center'><?=language('common/main/main','tModalPriceSellONL')?></th>
            <?php } ?>
        </tr>
    </thead>    

    <?php //echo $aProduct['sql'] ?>

    <?php if($aProduct['rtCode'] != 1){ ?>
        <tr><td class='text-center xCNTextDetail2' colspan='100%'><?= language('common/main/main','tCMNNotFoundData')?></td></tr>
    <?php }else{ ?>
        <?php foreach($aProduct['raItems'] AS $key => $aValue){ ?>
        <?php 
            if ($key & 1) {
                $tClassColor = 'xCNTbodyodd';
            } else { 
                $tClassColor = 'xCNTbodyeven';
            }

            if($aPriceType[0] == 'Pricesell' && $tSelectTier == 'PDT'){ 
                $aPackData = array(
                    'SHP'       => $aValue['FTShpCode'],
                    'BCH'       => $aValue['FTBchCode'],
                    'PDTCode'   => $aValue['FTPdtCode'],
                    'PDTName'   => $aValue['FTPdtName'],
                    'PUNCode'   => $aValue['FTPunCode'],
                    'PUNName'   => $aValue['FTPunName'],
                    'PriceRet'  => number_format($aValue['FCPgdPriceRet'], $nOptDecimalShow, '.', ','),
                    'PriceWhs'  => number_format($aValue['FCPgdPriceWhs'], $nOptDecimalShow, '.', ','),
                    'PriceNet'  => number_format($aValue['FCPgdPriceNet'], $nOptDecimalShow, '.', ','),
                    'IMAGE'     => $aValue['FTImgObj'],
                    'CookTime'  => ($aValue['FCPdtCookTime'] == '') ? 0 : $aValue['FCPdtCookTime'],
                    'CookHeat'  => ($aValue['FCPdtCookHeat'] == '') ? 0 : $aValue['FCPdtCookHeat'],
                );
                $aPackData = JSON_encode($aPackData);
                $tNameFunctionPDT = 'JSxPDTClickData(this,'.$aPackData.')';
                $tNewClassControl = 'JSxPDTClickMuti'.$aValue['FTPdtCode'].$aValue['FTPunCode'];
            }else{
                $tNameFunctionPDT = '';
                $tNewClassControl = '';
            }

        ?>

        <tbody id="otbodyPdt<?=$aValue['FTPdtCode']?>" class="<?=$tClassColor?> <?=$tNewClassStyle?>"  onclick='<?=$tNameFunctionPDT?>'>
            <tr class="panel-heading <?=$tNewClassControl?>">
                <?php if($aPriceType[0] == 'Pricesell' && $tSelectTier == 'PDT'){ ?>
                    <td class="text-left"><?=$aValue['FTPdtCode']; ?></td>
                <?php }else{ ?>
                    <td class="text-left">
                        <a class="xCNMenuplus xCNPdtIconPlus collapsed" role="button" data-toggle="collapse" href=".xWPdtlistDetail<?=$aValue['FTPdtCode'];?>" aria-expanded="false" data-pdtcode="<?=$aValue['FTPdtCode'];?>" style="font-size:16px !important;">
                            <i class="fa fa-plus xCNPlus" style="font-size:10px;"> <label style="font-size:18px !important;cursor:pointer;"><?=$aValue['FTPdtCode']; ?></label></i>
                        </a>
                    </td>
                <?php } ?>
                <td class="text-left"><?=$aValue['FTPdtName']; ?></td>

                <?php if($aPriceType[0] == 'Cost'){ ?>
                    <td class="text-left" colspan='3'></td>
                <?php } else if($aPriceType[0] == 'Pricesell' && $tSelectTier == 'PDT'){ ?>
                    <td class="text-left"><?=$aValue['FTPunName'];?></td>
                    <td class="text-right"><?=number_format($aValue['FCPgdPriceRet'], $nOptDecimalShow, '.', ',');?></td>
                    <td class="text-right"><?=number_format($aValue['FCPgdPriceWhs'], $nOptDecimalShow, '.', ',');?></td>
                    <td class="text-right"><?=number_format($aValue['FCPgdPriceNet'], $nOptDecimalShow, '.', ',');?></td>
                <?php }else if($aPriceType[0] == 'Pricesell'){ ?>
                    <td class="text-left" colspan='5'></td>   
                <?php } ?>
            </tr>
        </tbody>
        <?php } ?>  
    <?php } ?>
</table>
<!--end table-->
        
<!--pagenation-->
<div class="row">
    <!-- เปลี่ยน -->
    <div class="col-md-6">
        <p><?php echo language('common/main/main','tResultTotalRecord')?> <?=$aProduct['rnAllRow']?> <?php echo language('common/main/main','tRecord')?> <?php echo language('common/main/main','tCurrentPage')?> <?php echo $aProduct['rnCurrentPage']; ?> / <?php echo $aProduct['rnAllPage']; ?></p>
    </div>
    <!-- เปลี่ยน -->
    <div class="col-md-6">
        <div class="xWPagePrinter btn-toolbar pull-right"> <!-- เปลี่ยนชื่อ Class เป็นของเรื่องนั้นๆ --> 
            <?php if($nPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
            <button onclick="JSvPDTBrowseClickPage('previous')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
                <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
            </button>
            <?php for($i=max($nPage-2, 1); $i<=max(0, min($aProduct['rnAllPage'],$nPage+2)); $i++){?> <!-- เปลี่ยนชื่อ Parameter Loop เป็นของเรื่องนั้นๆ --> 
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
                <button onclick="JSvPDTBrowseClickPage('<?php echo $i?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
            <?php } ?>
            <?php if($nPage >= $aProduct['rnAllPage']){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
            <button onclick="JSvPDTBrowseClickPage('next')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>> <!-- เปลี่ยนชื่อ Onclick เป็นของเรื่องนั้นๆ --> 
                <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
            </button>
        </div>
    </div>
</div>
<!--end pagenation-->

<script>
    //Control Icon +,-
    tNotItem = '';
    $('.xCNPdtIconPlus').click(function(){
        tPdtCode    = $(this).data('pdtcode');
        
        if($(this).hasClass('collapsed') === true){
            $(this).removeClass('collapsed');
            tPdtCode    = $(this).data('pdtcode');
            aPriceType  = '<?=$aPriceType[0]?>';
            if($('#otbBrowserListPDT tr').hasClass('xWPdtlistDetail'+tPdtCode) === false){

                if(aPriceType == 'Cost'){
                    var tSysconfig = '<?=json_encode($aPriceType)?>';
                    var aDataSendBarcode = {
                        tPdtCode    : tPdtCode,
                        tSysconfig  : tSysconfig,
                        tCodeSpl    : ''
                    }
                     JSxCallGetBarcodeForPDT(aDataSendBarcode);
                }else if(aPriceType == 'Pricesell'){
                    var aDataSendBarcode = {
                        tPdtCode    : tPdtCode,
                        tSysconfig  : '',
                        tCodeSpl    : ''
                    }
                    JSxCallGetBarcodeForPDT(aDataSendBarcode);
                }
            }

        }else{
            $(this).addClass('collapsed');
            
            //Way 01
            // if(tNotItem != '' || tNotItem != "undefined"){
            //     $('#'+tNotItem).find('.xCNPdtIconPlus').addClass('collapsed');
            //     $('#otbBrowserListPDT .otrNobarcode').remove();
            // }
        }
    });

    //click data for PDT and pricesell
    function JSxPDTClickData(elem , packData){
        var aPriceType      = '<?=$aPriceType[0]?>';
        var tSelectTier     = '<?=$tSelectTier?>';
        var tTimeStorage    = $('#odhTimeStorage').val();
        if(aPriceType == 'Pricesell' && tSelectTier == 'PDT'){
            
            var tReturnType = $('#odhEleReturnType').val();
            if(tReturnType == 'S'){
                //remove ค่าเก่าเสมอ
                pnPdtCode = packData.PDTCode;
                ptPunCode = packData.PUNCode;
                ptBarCode = packData.Barcode;

                var obj = [];
                localStorage.removeItem("LocalItemDataPDT" + tTimeStorage);
                $('.panel-heading').removeClass('xCNActivePDT');

                //insert ค่าใหม่
                $(elem).children().addClass('xCNActivePDT');

                obj.push({"pnPdtCode": pnPdtCode, "ptBarCode": ptBarCode, "ptPunCode": ptPunCode  , "packData" : packData });
                localStorage.setItem("LocalItemDataPDT" + tTimeStorage,JSON.stringify(obj));
            }else if(tReturnType == 'M'){
                pnPdtCode = packData.PDTCode;
                ptPunCode = packData.PUNCode;
                ptBarCode = packData.Barcode;
                var nDataSelected = $('span.olbVal'+pnPdtCode+ptPunCode+ptBarCode).length;
                if (nDataSelected == 0) {
                    $('#odvPDTDataSelection').append($('<span>')
                        .append('<label>')
                        .attr('class', 'olbVal'+pnPdtCode+ptPunCode+ptBarCode)
                        .attr('data-pdtcode', pnPdtCode)
                        .attr('data-puncode', ptPunCode)
                        .attr('data-barcode', ptBarCode)
                    );
                    $(elem).children().addClass('xCNActivePDT');
            
                    //Set localstorage
                    aMulti.push({"pnPdtCode": pnPdtCode, "ptPunCode": ptPunCode , "packData" : packData });
                    localStorage.setItem("LocalItemDataPDT" + tTimeStorage,JSON.stringify(aMulti));
                } else {
                    $('span.olbVal'+pnPdtCode+ptPunCode+ptBarCode).remove();
                    $(elem).children().removeClass('xCNActivePDT');
                    //Remove localstorage and New add
                    var tStorePDT   = localStorage.getItem("LocalItemDataPDT" + tTimeStorage);
                    if(tStorePDT == '' || tStorePDT == null){
                        localStorage.removeItem("LocalItemDataPDT" + tTimeStorage);
                    }else{
                        var tStorePDT       = JSON.parse(tStorePDT);
                        var nLength         = tStorePDT.length;   
                        var aNewStore       = []; 
                        var aNewarraydata   = [];
                
                        for($i=0; $i<nLength; $i++){
                            aNewStore.push({"pnPdtCode": tStorePDT[$i].pnPdtCode, "ptPunCode": tStorePDT[$i].ptPunCode , "packData" : tStorePDT[$i].packData});
                        }
            
                        var nLengthStore = aNewStore.length; 
                        for($i=0; $i<nLengthStore; $i++){
                            if(aNewStore[$i].pnPdtCode == pnPdtCode && aNewStore[$i].ptPunCode == ptPunCode){
                                delete aNewStore[$i];
                            }
                        }

                        for($i=0; $i<nLengthStore; $i++){
                            if(aNewStore[$i] != undefined){
                                aNewarraydata.push(aNewStore[$i]);
                            }
                        }   
                        aMulti = [];
                        localStorage.setItem("LocalItemDataPDT" + tTimeStorage,JSON.stringify(aNewarraydata));
                    }
                }
            }else{
                alert(" you can't select type return single(S) or multiselect(M)");
            }

        }else{
            return;
        }
    }
</script>