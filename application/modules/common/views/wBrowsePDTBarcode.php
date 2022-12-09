
<?php 
    if($aTSysconfig == null || $aTSysconfig == ''){
        $bCheckPrice = false;
        $nPrinceCon  = 0;
    }else{
        $bCheckPrice = true;
        $nPrinceCon  = explode(',',$aTSysconfig);
    }
?> 

<?php if(empty($aPdtBarcode)){ ?>
    <tr class="otrNobarcode panel-collapse collapse in xCNTrWhiteColor">
        <td class='text-center xCNTextDetail2' colspan='100%'><?= language('common/main/main','tCMNNotFoundData')?></td>
    </tr>
    <script>
        //Way 01
        // var tElement = $('.otrNobarcode').parent();
        // var tValue   = $(tElement).attr('id');
        // var tNotItem = tValue;

        //Way 02
        $(".otrNobarcode").last().addClass('xWPdtlistDetail'+tPdtCode);
    </script>
<?php }else{ ?>
    <?php foreach($aPdtBarcode as $Key=>$Value){?>
        <?php 
            if($tPriceType == 'Cost'){
                for($i=0; $i<count($nPrinceCon); $i++){
                    $aDataFind = array(
                        'INDEX'             => $nPrinceCon[$i],
                        'CostAvgInorEX'     => $Value->PDTCostAvgInorEX,
                        'CostLastPrice'     => $Value->PDTCostLastPrice,
                        'CostFiFo'          => $Value->PDTCostFiFo,
                        'CostSTD'           => $Value->PDTCostSTD
                    );
                    $nResult = GetTotalByConfig($aDataFind); 
                    if($nResult == 'EMPTY'){
                        $nTotal = 0;
                    }else if($nResult != '' || $nResult != null){
                        $nTotal = $nResult;
                        break;
                    }else{
                        $nTotal = 0;
                    }
                }
                $aPackDataBarcode = array(
                    'SHP'       => $Value->FTShpCode,
                    'BCH'       => $Value->FTBchCode,
                    'Barcode'   => $Value->FTBarCode,
                    'PDTCode'   => $Value->FTPdtCode,
                    'PDTName'   => $Value->FTPdtName,
                    'PUNCode'   => $Value->FTPunCode,
                    'PUNName'   => $Value->FTPunName,
                    'IMAGE'     => $Value->FTImgObj,
                    'LOCSEQ'    => $Value->FTPlcCode,
                    'Price'     => $nTotal,
                );
                $aPackDataBarcode = JSON_encode($aPackDataBarcode);
                $tNameFunctionPDT = 'JSxPDTPushMultiSelection(this,'.$aPackDataBarcode.')';
            }else if($tPriceType == 'Pricesell'){
                $aPackDataBarcode = array(
                    'SHP'       => $Value->FTShpCode,
                    'BCH'       => $Value->FTBchCode,
                    'Barcode'   => $Value->FTBarCode,
                    'PDTCode'   => $Value->FTPdtCode,
                    'PDTName'   => $Value->FTPdtName,
                    'PUNCode'   => $Value->FTPunCode,
                    'PUNName'   => $Value->FTPunName,
                    'IMAGE'     => $Value->FTImgObj,
                    'LOCSEQ'    => $Value->FTPlcCode,
                    'CookTime'  => ($Value->FCPdtCookTime == '') ? 0 : $Value->FCPdtCookTime,
                    'CookHeat'  => ($Value->FCPdtCookHeat == '') ? 0 : $Value->FCPdtCookHeat,
                    'Remark'    => $Value->FTPdtRmk,
                    'PriceRet'  => number_format($Value->FCPgdPriceRet, $nOptDecimalShow, '.', ','),
                    'PeiceWhs'  => number_format($Value->FCPgdPriceWhs, $nOptDecimalShow, '.', ','),
                    'PriceNet'  => number_format($Value->FCPgdPriceNet, $nOptDecimalShow, '.', ',')
                );
                $aPackDataBarcode = JSON_encode($aPackDataBarcode);
                $tNameFunctionPDT = 'JSxPDTPushMultiSelection(this,'.$aPackDataBarcode.')';
            }
        ?>

        <tr style="cursor: pointer;" class="xCNTrWhiteColor panel-collapse collapse in xWPdtlistDetail<?=$Value->FTPdtCode?> xCNCHECK<?=$Value->FTPdtCode?><?=$Value->FTBarCode?>" data-pdtcode="<?php echo $Value->FTPdtCode?>" onclick='<?=$tNameFunctionPDT?>'>
        <td class="text-left" colspan="2"></td>
        <td class="text-left" ><?php echo $Value->FTBarCode?></td>
        <td class="text-left"><?php echo $Value->FTPunName?></td>
        <?php if($tPriceType == 'Cost'){ ?>
            <td class="text-right"><?=number_format($nTotal, $nOptDecimalShow, '.', ',')?> </td>
        <?php }else if($tPriceType == 'Pricesell'){ ?>
            <td class="text-right"><?=number_format($Value->FCPgdPriceRet, $nOptDecimalShow, '.', ',')?> </td>
            <td class="text-right"><?=number_format($Value->FCPgdPriceWhs, $nOptDecimalShow, '.', ',')?> </td>
            <td class="text-right"><?=number_format($Value->FCPgdPriceNet, $nOptDecimalShow, '.', ',')?> </td>
        <?php } ?>
        </tr>
    <?php } ?>
<?php } ?>

<?php 
    function GetTotalByConfig($aData){
        $INDEX          = $aData['INDEX'];
        $CostAvgInorEx  = $aData['CostAvgInorEX'];
        $CostLastPrice  = $aData['CostLastPrice'];
        $CostFiFo       = $aData['CostFiFo'];
        $CostSTD        = $aData['CostSTD'];

        if($INDEX == 1){ //ต้นทุนเฉลี่ย
            return $CostAvgInorEx;
        }else if($INDEX == 2){ //ต้นทุนสุดท้าย
            return $CostLastPrice;
        }else if($INDEX == 3){ //ต้นทุนมาตราฐาน
            return $CostSTD;
        }else if($INDEX == 4){ //ต้นทุน FiFo
            return $CostFiFo;
        }else{
            return 'EMPTY';
        }
    }
?>