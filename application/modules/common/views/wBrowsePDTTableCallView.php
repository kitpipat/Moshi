<style>
    .xCNClickforModalPDT{
        cursor: pointer;
    }

    .xCNActivePDT > td{
        background-color: #179bfd !important;
        color : #FFF !important;
    }

    .xCNTbodyodd > .panel-heading{
        background : #f8f8f8 !important;
    }

    .xCNTbodyeven > .panel-heading{
        background : #FFF !important;
    }

</style>

<?php
    if($aPriceType[0] == 'Pricesell' && $tSelectTier == 'PDT'){
        $tNewClassStyle = 'xCNClickforModalPDT';
        
    }else{
        $tNewClassStyle = '';
    }

    //หาว่าใช้ต้นทุนแบบไหน
    if($aTSysconfig == null || $aTSysconfig == ''){
        $bCheckPrice    = false;
        $nPriceon       = 0;
    }else{
        $bCheckPrice    = true;
        $nPriceon       = $aTSysconfig;
    }
?>

<!--layout table-->
<table id='otbBrowserListPDT' class='table table-striped' style='width:100%'>
    <thead>
        <tr>
            <?php if($aPriceType[0] == 'Cost'){ ?>
                <th class='xCNTextBold' style='text-align:center; width:220px;'><?=language('common/main/main','tModalcodePDT')?></th>
                <th class='xCNTextBold' style='text-align:center; width:220px;'><?=language('common/main/main','tModalnamePDT')?></th>
                <th class='xCNTextBold' style='text-align:center; width:220px;'><?=language('common/main/main','tModalPriceUnit')?></th>
                <th class='xCNTextBold' style='text-align:center; width:220px;'><?=language('common/main/main','tModalbarcodePDT')?></th>
                <th class='xCNTextBold' style='text-align:center; width:220px;'><?=language('common/main/main','tModalPricecost')?></th>
            <?php }else if($aPriceType[0] == 'Pricesell'){ ?>
                <th class='xCNTextBold' style='text-align:center; width:120px;'><?=language('common/main/main','tModalcodePDT')?></th>
                <th class='xCNTextBold' style='text-align:center; width:160px;'><?=language('common/main/main','tModalnamePDT')?></th>
                <th class='xCNTextBold' style='text-align:center; width:120px;'><?=language('common/main/main','tModalPriceUnit')?></th>
                <th class='xCNTextBold' style='text-align:center; width:160px;'><?=language('common/main/main','tModalbarcodePDT')?></th>
                <th class='xCNTextBold' style='text-align:center; width:120px;'><?=language('common/main/main','tModalPriceSellPLE')?></th>
            <?php }else if($aPriceType[0] == 'Price4Cst'){ ?>
                <th class='xCNTextBold' style='text-align:center; width:120px;'><?=language('common/main/main','tModalcodePDT')?></th>
                <th class='xCNTextBold' style='text-align:center; width:160px;'><?=language('common/main/main','tModalnamePDT')?></th>
                <th class='xCNTextBold' style='text-align:center; width:120px;'><?=language('common/main/main','tModalPriceUnit')?></th>
                <th class='xCNTextBold' style='text-align:center; width:160px;'><?=language('common/main/main','tModalbarcodePDT')?></th>
                <th class='xCNTextBold' style='text-align:center; width:120px;'><?=language('common/main/main','tModalPriceSellPLE')?></th>
            <?php } ?>
        </tr>
    </thead>    

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

            if($aPriceType[0] == 'Pricesell' || $aPriceType[0] == 'Price4Cst'){ 
                $aPackData = array(
                    'SHP'       => $aValue['FTShpCode'],
                    'BCH'       => $aValue['FTPdtSpcBch'],
                    'PDTCode'   => $aValue['FTPdtCode'],
                    'PDTName'   => $aValue['FTPdtName'],
                    'PUNCode'   => $aValue['FTPunCode'],
                    'Barcode'   => $aValue['FTBarCode'],
                    'PUNName'   => $aValue['FTPunName'],
                    'PriceRet'  => number_format($aValue['FCPgdPriceRet'], $nOptDecimalShow, '.', ','),
                    'PriceWhs'  => number_format($aValue['FCPgdPriceWhs'], $nOptDecimalShow, '.', ','),
                    'PriceNet'  => number_format($aValue['FCPgdPriceNet'], $nOptDecimalShow, '.', ','),
                    'IMAGE'     => $aValue['FTImgObj'],
                    'LOCSEQ'    => '',
                    'Remark'    => $aValue['FTPdtName'],
                    'CookTime'  => ($aValue['FCPdtCookTime'] == '') ? 0 : $aValue['FCPdtCookTime'],
                    'CookHeat'  => ($aValue['FCPdtCookHeat'] == '') ? 0 : $aValue['FCPdtCookHeat'],
                    'AlwDis'    => ($aValue['FTPdtStaAlwDis'] == '' || $aValue['FTPdtStaAlwDis'] == null ) ? 2 : $aValue['FTPdtStaAlwDis'],
                    'AlwVat'    => $aValue['FTPdtStaVat'],
                    'nVat'      => $aValue['FCVatRate'],
                    'NetAfHD'   => number_format($aValue['FCPgdPriceRet'], $nOptDecimalShow, '.', ',')
                );
                $aPackData = JSON_encode($aPackData);
                $tNameFunctionClickPDT      = 'JSxPDTClickData(this,'.$aPackData.')';
                $tNameFunctionDBClickPDT    = 'JSxPDTDBClickData(this,'.$aPackData.')';
                $tNewClassControl           = 'JSxPDTClickMuti'.$aValue['FTPdtCode'].$aValue['FTPunCode'];
            }else if($aPriceType[0] == 'Cost'){
                //หาราคาต้นทุน
                $aDataFind = array(
                    'nINDEX'             => $nPriceon,
                    'nVATSPL'            => $tVatInorEx,
                    'nCostAvgIn'         => $aValue['FCPdtCostAVGIN'],
                    'nCostAvgEX'         => $aValue['FCPdtCostAVGEx'],
                    'nCostLast'          => $aValue['FCPdtCostLast'],
                    'nCostFiFoIn'        => $aValue['FCPdtCostFIFOIN'],
                    'nCostFiFoEx'        => $aValue['FCPdtCostFIFOEx'],
                    'nCostSTD'           => $aValue['FCPdtCostStd']
                );
                $nCost = GetTotalByConfig($aDataFind); 
                $nCost = $nCost * $aValue['FCPdtUnitFact']; 

                $aPackData = array(
                    'SHP'       => $aValue['FTShpCode'],
                    'BCH'       => $aValue['FTPdtSpcBch'],
                    'PDTCode'   => $aValue['FTPdtCode'],
                    'PDTName'   => $aValue['FTPdtName'],
                    'PUNCode'   => $aValue['FTPunCode'],
                    'Barcode'   => $aValue['FTBarCode'],
                    'PUNName'   => $aValue['FTPunName'],
                    'IMAGE'     => $aValue['FTImgObj'],
                    'Price'     => $nCost,
                    'LOCSEQ'    => '',
                    'AlwDis'    => ($aValue['FTPdtStaAlwDis'] == '' || $aValue['FTPdtStaAlwDis'] == null ) ? 2 : $aValue['FTPdtStaAlwDis'],
                    'AlwVat'    => $aValue['FTPdtStaVat'],
                    'nVat'      => $aValue['FCVatRate'],
                    'NetAfHD'   => $nCost
                );
                $aPackData = JSON_encode($aPackData);
                $tNameFunctionClickPDT      = 'JSxPDTClickData(this,'.$aPackData.')';
                $tNameFunctionDBClickPDT    = 'JSxPDTDBClickData(this,'.$aPackData.')';
                $tNewClassControl           = 'JSxPDTClickMuti'.$aValue['FTPdtCode'].$aValue['FTPunCode'];
            }else{
                $tNameFunctionClickPDT      = '';
                $tNameFunctionDBClickPDT    = '';
                $tNewClassControl           = '';
            }
        ?>

        <tbody id="otbodyPdt<?=$aValue['FTPdtCode']?><?=$aValue['FTPunCode']?>" class="xCNEventClick <?=$tClassColor?> <?=$tNewClassStyle?>" ondblclick='<?=$tNameFunctionDBClickPDT?>'  onclick='<?=$tNameFunctionClickPDT?>'>
            <tr class="panel-heading <?=$tNewClassControl?>">
                <td class="text-left"><?=$aValue['FTPdtCode']; ?></td>
                <td class="text-left"><?=$aValue['FTPdtName']; ?></td>
                <td class="text-left"><?=$aValue['FTPunName'];?></td>
                <td class="text-left"><?=$aValue['FTBarCode']; ?></td>
                <?php if($aPriceType[0] == 'Cost'){ ?>
                    <td class="text-right"><?=number_format($nCost, $nOptDecimalShow, '.', ',');?></td> 
                <?php }else if($aPriceType[0] == 'Pricesell' || $aPriceType[0] == 'Price4Cst'){ ?>
                    <td class="text-right"><?=number_format($aValue['FCPgdPriceRet'], $nOptDecimalShow, '.', ',');?></td>
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
        <p style="display: inline;"><?php echo language('common/main/main','tResultTotalRecord')?></p><p id="ospAllPDTRow" style="display: inline; padding: 5px;"><?=$aProduct['rnAllRow']?></p><p style="display: inline;"><?php echo language('common/main/main','tRecord')?> <?php echo language('common/main/main','tCurrentPage')?> <?php echo $aProduct['rnCurrentPage']; ?> / <?php echo $aProduct['rnAllPage']; ?></p>
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

<?php 
    //หาว่าใช้ราคาแบบไหน
    function GetTotalByConfig($aData){

        $nINDEXConfig       = explode(',',$aData['nINDEX']); 
        $nVATSPL            = $aData['nVATSPL'];
        $nCostAvgIn         = $aData['nCostAvgIn'];
        $nCostAvgEX         = $aData['nCostAvgEX'];
        $nCostLast          = $aData['nCostLast'];
        $nCostFiFoIn        = $aData['nCostFiFoIn'];
        $nCostFiFoEx        = $aData['nCostFiFoEx'];
        $nCostSTD           = $aData['nCostSTD'];

        for($i=0; $i<count($nINDEXConfig); $i++){
            switch ($nINDEXConfig[$i]) {
                case 1: //ต้นทุนเฉลี่ย
                    if($nVATSPL == 1){
                        $nResultCost = $nCostAvgIn;
                    }else if($nVATSPL == 2){
                        $nResultCost = $nCostAvgEX;
                    }
                    $t = 'ต้นทุนเฉลี่ย';
                    break;
                case 2: //ต้นทุนสุดท้าย
                    $nResultCost = $nCostLast;
                    $t = 'ต้นทุนสุดท้าย';
                    break;
                case 3: //ต้นทุนมาตราฐาน
                    $nResultCost = $nCostSTD;
                    $t = 'ต้นทุนมาตราฐาน';
                    break;
                case 4: //ต้นทุน FiFo
                    if($nVATSPL == 1){
                        $nResultCost = $nCostFiFoIn;
                    }else if($nVATSPL == 2){
                        $nResultCost = $nCostFiFoEx;
                    }
                    $t = 'ต้นทุน FiFo';
                    break;
                default:
                    $nResultCost = 'EMPTY';
            }

            if($nResultCost == '' || $nResultCost == null){

            }else{
                break;  
            }
        }
        return $nResultCost;
    }
?>

<script>

    var tTimer;
    var nStatus = 1;
    var tEventWhenDelete = 0;

    //เลือกสินค้าแล้วกด ยืนยัน
    function JSxPDTClickData(elem , packData){
        nStatus = 1;
        tTimer = setTimeout(function() {
            if (nStatus == 1) {
                var aPriceType      = '<?=$aPriceType[0]?>';
                var tSelectTier     = '<?=$tSelectTier?>';
                var tTimeStorage    = $('#odhTimeStorage').val();
                if((aPriceType == 'Pricesell' || aPriceType == 'Cost' || aPriceType == 'Price4Cst')){
                    var tReturnType = $('#odhEleReturnType').val();
                    if(tReturnType == 'S'){
                        //remove ค่าเก่าเสมอ
                        pnPdtCode = packData.PDTCode;
                        ptPunCode = packData.PUNCode;
                        ptBarCode = packData.Barcode;

                        var obj = [];
                        localStorage.removeItem("LocalItemDataPDT" + tTimeStorage);
                        $('.panel-heading').removeClass('xCNActivePDT');
                        $('.panel-heading').find('td').attr('style', 'color: #232C3D !important;');

                        //insert ค่าใหม่
                        $(elem).children().addClass('xCNActivePDT');
                        $(elem).children().find('td').attr('style', 'color: #FFF !important;');

                        obj.push({"pnPdtCode": pnPdtCode, "ptBarCode": ptBarCode, "ptPunCode": ptPunCode  , "packData" : packData });
                        localStorage.setItem("LocalItemDataPDT" + tTimeStorage,JSON.stringify(obj));
                    }else if(tReturnType == 'M'){
                        pnPdtCode = packData.PDTCode;
                        ptPunCode = packData.PUNCode;
                        ptBarCode = packData.Barcode;
                        aNewStore = [];
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

                            $(elem).children().find('td').attr('style', 'color: #FFF !important;');

                            //ถ้าข้อมูลถูกลบ ต้องเพิ่มข้อมูลทุกตัวของตัวเดิม เข้าที่เดิม
                            if(tEventWhenDelete == 1){
                                var tStoreDataOld   = localStorage.getItem("LocalItemDataPDT" + tTimeStorage);
                                var tStorePDT       = JSON.parse(tStoreDataOld);
                                var nLength         = tStorePDT.length; 
                                for($i=0; $i<nLength; $i++){
                                    aNewStore.push({"pnPdtCode": tStorePDT[$i].pnPdtCode , "ptPunCode": tStorePDT[$i].ptPunCode , "ptBarCode": tStorePDT[$i].ptBarCode , "packData" : tStorePDT[$i].packData});
                                }

                                aNewStore.push({"pnPdtCode": pnPdtCode , "ptPunCode": ptPunCode , "ptBarCode": ptBarCode , "packData" : packData });
                                localStorage.setItem("LocalItemDataPDT" + tTimeStorage,JSON.stringify(aNewStore));
                                tEventWhenDelete = 0;
                            }else{
                                aMulti.push({"pnPdtCode": pnPdtCode , "ptPunCode": ptPunCode , "ptBarCode": ptBarCode , "packData" : packData });
                                localStorage.setItem("LocalItemDataPDT" + tTimeStorage,JSON.stringify(aMulti));
                            }
                        } else {
                            $('span.olbVal'+pnPdtCode+ptPunCode+ptBarCode).remove();
                            $(elem).children().removeClass('xCNActivePDT');
                            $(elem).children().find('td').attr('style', 'color: #232C3D !important;');

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
                                    aNewStore.push({"pnPdtCode": tStorePDT[$i].pnPdtCode, "ptPunCode": tStorePDT[$i].ptPunCode , "ptBarCode":  tStorePDT[$i].ptBarCode , "packData" : tStorePDT[$i].packData});
                                }
                                
                                //ลบ array ชุดเดิม
                                var nLengthStore = aNewStore.length; 
                                for($i=0; $i<nLengthStore; $i++){
                                    if(aNewStore[$i].pnPdtCode == pnPdtCode && aNewStore[$i].ptPunCode == ptPunCode && aNewStore[$i].ptBarCode == ptBarCode){
                                        delete aNewStore[$i];
                                    }
                                }

                                //สร้าง array ชุดใหม่
                                for($i=0; $i<nLengthStore; $i++){
                                    if(aNewStore[$i] != undefined){
                                        aNewarraydata.push(aNewStore[$i]);
                                    }
                                }   
                                aMulti = [];
                                localStorage.setItem("LocalItemDataPDT" + tTimeStorage,JSON.stringify(aNewarraydata));
                                tEventWhenDelete = 1;
                            }
                        }
                    }else{
                        alert(" you can't select type return single(S) or multiselect(M)");
                    }
                }else{
                    return;
                }
            }
        }, 100);
    }

    //ดับเบิ้ลคลิก คือเลือกสินค้าเลย
    function JSxPDTDBClickData(elem , packData){
        clearTimeout(tTimer);
        nStatus = 0;
        var aPriceType      = '<?=$aPriceType[0]?>';
        var tSelectTier     = '<?=$tSelectTier?>';
        var tTimeStorage    = $('#odhTimeStorage').val();
        if((aPriceType == 'Pricesell' || aPriceType == 'Cost' || aPriceType == 'Price4Cst')){
            var tReturnType = $('#odhEleReturnType').val();
            if(tReturnType == 'S'){
                //remove ค่าเก่าเสมอ
                pnPdtCode = packData.PDTCode;
                ptPunCode = packData.PUNCode;
                ptBarCode = packData.Barcode;

                var obj = [];
                localStorage.removeItem("LocalItemDataPDT" + tTimeStorage);
                $('.panel-heading').removeClass('xCNActivePDT');
                $('.panel-heading').find('td').attr('style', 'color: #232C3D !important;');

                //insert ค่าใหม่
                $(elem).children().addClass('xCNActivePDT');
                $(elem).children().find('td').attr('style', 'color: #FFF !important;');

                obj.push({"pnPdtCode": pnPdtCode, "ptBarCode": ptBarCode, "ptPunCode": ptPunCode  , "packData" : packData });
                localStorage.setItem("LocalItemDataPDT" + tTimeStorage,JSON.stringify(obj));

                JCNxConfirmSelectedPDT();
            }
        }else{
            return;
        }
    }


</script>