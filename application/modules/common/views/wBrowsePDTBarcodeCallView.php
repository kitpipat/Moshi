
<?php 
    if($aTSysconfig == null || $aTSysconfig == ''){
        $bCheckPrice    = false;
        $nPriceon       = 0;
    }else{
        $bCheckPrice    = true;
        $nPriceon       = $aTSysconfig;
    }
?> 

<?php if(empty($aPdtBarcode)){ ?>
    <tr class="otrNobarcode<?=$tPdtCode?><?=$tPunCode?> panel-collapse collapse in xCNTrWhiteColor">
        <td class='text-center xCNTextDetail2' colspan='100%'><?= language('common/main/main','tCMNNotFoundData')?></td>
    </tr>
    <script>
        $(".otrNobarcode<?=$tPdtCode?><?=$tPunCode?>").last().addClass('xWPdtlistDetail'+ '<?=$tPdtCode?>' +  '<?=$tPunCode?>');
    </script>
<?php }else{ ?>
    <?php foreach($aPdtBarcode as $Key=>$aValue){?>
        <?php 
            if($tPriceType == 'Cost'){

                //หาราคาต้นทุน
                $aDataFind = array(
                    'nINDEX'             => $nPriceon,
                    'nVATSPL'            => $tVatInorEx,
                    'nCostAvgIn'         => $aValue['FCPdtCostAVGIN'],
                    'nCostAvgEX'         => $aValue['FCPdtCostAVGEX'],
                    'nCostLast'          => $aValue['FCPdtCostLast'],
                    'nCostFiFoIn'        => $aValue['FCPdtCostFIFOIN'],
                    'nCostFiFoEx'        => $aValue['FCPdtCostFIFOEX'],
                    'nCostSTD'           => $aValue['FCPdtCostStd']
                );
                $nTotal = GetTotalByConfig($aDataFind); 
                $nTotal = $nTotal * 1; 

                $aPackDataBarcode = array(
                    'SHP'       => $aValue['FTShpCode'],
                    'BCH'       => $aValue['FTPdtSpcBch'],
                    'Barcode'   => $aValue['FTBarCode'],
                    'PDTCode'   => $aValue['FTPdtCode'],
                    'PDTName'   => $aValue['FTPdtName'],
                    'PUNCode'   => $aValue['FTPunCode'],
                    'PUNName'   => $aValue['FTPunName'],
                    'IMAGE'     => $aValue['FTImgObj'],
                    'LOCSEQ'    => $aValue['FTPlcCode'],
                    'Price'     => $nTotal,
                );
                $aPackDataBarcode = JSON_encode($aPackDataBarcode);
                $tNameFunctionPDT = 'JSxPDTPushMultiSelection(this,'.$aPackDataBarcode.')';
            }else if($tPriceType == 'Pricesell'){
                $aPackDataBarcode = array(
                    'SHP'       => $aValue['FTShpCode'],
                    'BCH'       => $aValue['FTPdtSpcBch'],
                    'Barcode'   => $aValue['FTBarCode'],
                    'PDTCode'   => $aValue['FTPdtCode'],
                    'PDTName'   => $aValue['FTPdtName'],
                    'PUNCode'   => $aValue['FTPunCode'],
                    'PUNName'   => $aValue['FTPunName'],
                    'IMAGE'     => $aValue['FTImgObj'],
                    'LOCSEQ'    => $aValue['FTPlcCode'],
                    'CookTime'  => ($aValue['FCPdtCookTime'] == '') ? 0 : $aValue['FCPdtCookTime'],
                    'CookHeat'  => ($aValue['FCPdtCookHeat'] == '') ? 0 : $aValue['FCPdtCookHeat'],
                    'Remark'    => $aValue['FTPdtName'],
                    'PriceRet'  => number_format($aValue['FCPgdPriceRet'], $nOptDecimalShow, '.', ','),
                    'PeiceWhs'  => number_format($aValue['FCPgdPriceWhs'], $nOptDecimalShow, '.', ','),
                    'PriceNet'  => number_format($aValue['FCPgdPriceNet'], $nOptDecimalShow, '.', ',')
                );
                $aPackDataBarcode = JSON_encode($aPackDataBarcode);
                $tNameFunctionPDT = 'JSxPDTPushMultiSelection(this,'.$aPackDataBarcode.')';
            }
        ?>

        <tr style="cursor: pointer;" class="xCNTrWhiteColor panel-collapse collapse in xWPdtlistDetail<?=$aValue['FTPdtCode']?><?=$aValue['FTPunCode']?> xCNCHECK<?=$aValue['FTPdtCode']?><?=$aValue['FTBarCode']?>" data-pdtcode="<?=$aValue['FTPdtCode']?>" onclick='<?=$tNameFunctionPDT?>'>
        <td class="text-left" colspan="2"></td>
        <td class="text-left"><?=$aValue['FTPunName']?></td>
        <td class="text-left" ><?=$aValue['FTBarCode']?></td>
        <?php if($tPriceType == 'Cost'){ ?>
            <td class="text-right"><?=number_format($nTotal, $nOptDecimalShow, '.', ',')?> </td>
        <?php }else if($tPriceType == 'Pricesell'){ ?>
            <td class="text-right"><?=number_format($aValue['FCPgdPriceRet'], $nOptDecimalShow, '.', ',')?> </td>
            <td class="text-right"><?=number_format($aValue['FCPgdPriceWhs'], $nOptDecimalShow, '.', ',')?> </td>
            <td class="text-right"><?=number_format($aValue['FCPgdPriceNet'], $nOptDecimalShow, '.', ',')?> </td>
        <?php } ?>
        </tr>
    <?php } ?>
<?php } ?>

<?php 
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