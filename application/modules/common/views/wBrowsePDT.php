<style>

    /*.xCNTbodyodd > .panel-heading{
        background : #FFF !important;
    }

    .xCNTbodyeven > .panel-heading{
        background : #FFF !important;
    }*/

    .xCNTrWhiteColor{
        background : #FFF !important;
    }

    #tbody{
        background : #f5f5f5 !important;
    }

    #otbBrowserListPDT .xCNActivePDT > td{
        background-color: #179bfd !important;
        color : #FFF !important;
    }

    .xCNPDT{
        margin-bottom   : 5px;
    }

    .tab-pane {
        padding: 10px 15px;
    }


</style>

<?php 
    //Get parameter SPL
    if(empty($tParameterSPL)){
        $tStatusSPL = 0;
        $tSPLCode   = '';
        $tSPLName   = '';
    }else{
        if($tParameterSPL[0] == '' || $tParameterSPL[1] == ''){
            $tStatusSPL = 0;
            $tSPLCode   = '';
            $tSPLName   = '';
        }else{
            $tStatusSPL = 1;
            $tSPLCode   = $tParameterSPL[0];
            $tSPLName   = $tParameterSPL[1];
        }
    }

    //Get parameter BCH
    if(empty($tParameterBCH)){
        $tStatusBCH = 0;
        $tBCHCode   = '';
        $tBCHName   = '';
    }else{
        if($tParameterBCH[0] == '' || $tParameterBCH[1] == ''){
            $tStatusBCH = 0;
            $tBCHCode   = '';
            $tBCHName   = '';
        }else{
            $tStatusBCH = 1;
            $tBCHCode   = $tParameterBCH[0];
            $tBCHName   = $tParameterBCH[1];
        }
    }

    //Get parameter MCH
    if(empty($tParameterMCH)){
        $tStatusMCH = 0;
        $tMCHCode   = '';
        $tMCHName   = '';
    }else{
        if($tParameterMCH[0] == '' || $tParameterMCH[1] == ''){
            $tStatusMCH = 0;
            $tMCHCode   = '';
            $tMCHName   = '';
        }else{
            $tStatusMCH = 1;
            $tMCHCode   = $tParameterMCH[0];
            $tMCHName   = $tParameterMCH[1];
        }
    }

    //Get parameter SHP
    if(empty($tParameterSHP)){
        $tStatusSHP = 0;
        $tSHPCode   = '';
        $tSHPName   = '';
    }else{
        if($tParameterSHP[0] == '' || $tParameterSHP[1] == ''){
            $tStatusSHP = 0;
            $tSHPCode   = '';
            $tSHPName   = '';
        }else{
            $tStatusSHP = 1;
            $tSHPCode   = $tParameterSHP[0];
            $tSHPName   = $tParameterSHP[1];
        }
    }


    //Get parameter not in item
    if(empty($aNotinItem)){
        $tTextNotinItem = '';
    }else{
        $tTextNotinItem = '';
        for($i=0; $i<count($aNotinItem); $i++){
            $tTextNotinItem .= $aNotinItem[$i][0] . ':::' . $aNotinItem[$i][1] . ',';

            if($i == count($aNotinItem)-1){
                $tTextNotinItem = substr($tTextNotinItem,0,-1);
            }
        }
    }
?>

<!-- element name and value -->
<input type='hidden' name="odhEleNamePDT"       id="odhEleNamePDT"      value="<?=$tElementreturn[0]?>">
<input type='hidden' name="odhEleValuePDT"      id="odhEleValuePDT"     value="<?=$tElementreturn[1]?>">
<input type='hidden' name="odhEleNameNextFunc"  id="odhEleNameNextFunc" value="<?=$tNameNextFunc?>">
<input type='hidden' name="odhEleReturnType"    id="odhEleReturnType"   value="<?=$tReturnType?>">
<input type='hidden' name="odhSelectTier"       id="odhEleSelectTier"   value="<?=$tSelectTier?>">
<input type='hidden' name="odhTimeStorage"      id="odhTimeStorage"     value="<?=$tTimeLocalstorage?>">

<input type='hidden' name="ohdSessionBCH"       id="ohdSessionBCH"      value="<?=$this->session->userdata("tSesUsrBchCode")?>">
<input type='hidden' name="ohdSessionSHP"       id="ohdSessionSHP"      value="<?=$this->session->userdata("tSesUsrShpCode")?>">
<input type='hidden' name="ohdNotinItem"        id="ohdNotinItem"       value="<?=$tTextNotinItem?>">

<?php 
    //control tab data
    $tTabBCH        = 0;
    $tTabPurchasing = 0;
    $tTabStorage    = 0;
    $tTabDetail     = 0;

    for($i=0; $i<count($aQualitysearch); $i++){ 
        switch ($aQualitysearch[$i]) {
            //ทั่วไป
            case 'NAMEPDT': 
            case 'CODEPDT':
            case 'BARCODE': 
            case 'FromToPGP':
            case 'FromToPTY': 
                $tTabDetail = 1;
            break;
            //สาขา
            case 'FromToBCH': 
            case 'Merchant' :
            case 'FromToSHP': 
            case 'GroupMerchant':
                $tTabBCH = 1;
            break;
            //ตัวแทนจำหน่าย
            case 'SUP' :
            case 'PurchasingManager': 
                $tTabPurchasing = 1;
            break; 
            //ที่เก็บ
            case 'PDTLOGSEQ':
                $tTabStorage = 1;
            break; 
            default:
        } 
    }; 

    $tActiveDetail              = '';
    $tActiveBCH                 = '';
    $tActivePurchasing          = '';
    $tActiveStorage             = '';
    $tActiveDetailContent       = '';
    $tActiveBCHContent          = '';
    $tActivePurchasingContent   = '';
    $tActiveStorageContent      = '';

    if($tTabDetail == 1){
        $tActiveDetail              = 'active';
        $tActiveDetailContent       = 'active in';
    }else if($tTabBCH == 1){
        $tActiveBCH                 = 'active';
        $tActiveBCHContent          = 'active in'; 
    }else if($tTabPurchasing == 1){
        $tActivePurchasing          = 'active';
        $tActivePurchasingContent   = 'active in'; 
    }else if($tTabStorage == 1){
        $tActiveStorage             = 'active';
        $tActiveStorageContent      = 'active in'; 
    }
?>

<div class="row">
    <!--layout search-->
    <div class='col-xs-12 col-sm-12 col-md-12 col-lg-4'>

        <!--header tab-->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="custom-tabs-line tabs-line-bottom left-aligned">
                    <ul class="nav" role="tablist">
                        <!--ทั่วไป-->
                        <?php if($tTabDetail == 1){ ?>
                        <li id="oliBrowsePDTDetail" class="xWMenu <?=$tActiveDetail?>">
                            <a role="tab" data-toggle="tab" data-target="#odvBrowsePDTDetail" aria-expanded="true"><?= language('common/main/main','tCenterModalPDTGeneral')?></a>
                        </li>
                        <?php } ?>
                        <!--สาขา-->
                        <?php if($SesShp == '' && $tTabBCH == 1){ ?>
                        <li id="oliBrowsePDTBranch" class="xWMenu xWSubTab <?=$tActiveBCH?>">
                            <a role="tab" data-toggle="tab" data-target="#odvBrowsePDTBranch" aria-expanded="false"><?= language('common/main/main','tCenterModalPDTBranch')?></a>
                        </li>
                        <?php } ?>
                        <!--ตัวแทนจำหน่าย-->
                        <?php if($tTabPurchasing == 1){ ?>
                        <li id="oliBrowsePDTSupply" class="xWMenu xWSubTab <?=$tActivePurchasing?>">
                            <a role="tab" data-toggle="tab" data-target="#odvBrowsePDTSupply" aria-expanded="false"><?= language('common/main/main','tCenterModalPDTSupply')?></a>
                        </li>
                        <?php } ?>
                        <!--ที่เก็บ-->
                        <?php if($tTabStorage == 1){ ?>
                        <li id="oliBrowsePDTStorage" class="xWMenu xWSubTab <?=$tActiveStorage?>">
                            <a role="tab" data-toggle="tab" data-target="#odvBrowsePDTStorage" aria-expanded="false"><?= language('common/main/main','tCenterModalPDTStorage')?></a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>

        <!--content tab-->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="tab-content">
                    <!--ทั่วไป-->
                    <div id="odvBrowsePDTDetail" class="tab-pane fade <?=$tActiveDetailContent?>">
                        <?php for($i=0; $i<count($aQualitysearch); $i++){ ?>
                            <?php 
                                switch ($aQualitysearch[$i]) {
                                    case 'NAMEPDT': ?>
                                        <div class=''>
                                            <div class='form-group xCNPDT'>
                                                <label class='xCNLabelFrm'><?= language('common/main/main','tCenterModalPDTNamePDT')?></label>
                                                <input class='form-control' type='text' id='oetBrowsePDTNamepdt' name='oetBrowsePDTNamepdt' value='' autocomplete='off' placeholder='<?=language('common/main/main','tCenterModalPDTSearch')?>'>
                                            </div>
                                        </div>
                                    <?php break;
                                    case 'CODEPDT': ?>
                                        <div class=''>
                                            <div class='form-group xCNPDT'>
                                                <label class='xCNLabelFrm'><?= language('common/main/main','tCenterModalPDTCodePDT')?></label>
                                                <input class='form-control' type='text' id='oetBrowsePDTCodepdt' name='oetBrowsePDTCodepdt' value='' autocomplete='off' placeholder='<?=language('common/main/main','tCenterModalPDTSearch')?>'>
                                            </div>
                                        </div>
                                    <?php break;
                                    case 'BARCODE': ?>
                                        <?php if($tSelectTier == 'Barcode'){ ?>
                                        <div class=''>
                                            <div class='form-group xCNPDT'>
                                                <label class='xCNLabelFrm'><?= language('common/main/main','tCenterModalPDTBarcode')?></label>
                                                <input class='form-control' type='text' id='oetBrowsePDTBarcode' name='oetBrowsePDTBarcode' value='' autocomplete='off' placeholder='<?=language('common/main/main','tCenterModalPDTSearch')?>'>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    <?php break;
                                    case 'FromToPGP': ?>
                                        <div class=''>
                                            <div class='row'>
                                                <div class='col-lg-6'> 
                                                    <div class='form-group xCNPDT'>
                                                        <label class='xCNLabelFrm'><?= language('common/main/main','tCenterModalPDTPGPFrom')?></label>
                                                        <div class='input-group'>
                                                            <input class='form-control xCNHide' id='oetBrowsePDTCodepgpfrom' name='oetBrowsePDTCodepgpfrom' maxlength='5' value=''>
                                                            <input class='form-control' type='text' id='oetBrowsePDTNamepgpfrom' name='oetBrowsePDTNamepgpfrom' value='' readonly=''>
                                                            <span  class='input-group-btn'>
                                                                <button id='obtPDTpgpBrowsefrom' type='button' class='btn xCNBtnBrowseAddOn'>
                                                                    <img src='<?=base_url()?>/application/modules/common/assets/images/icons/find-24.png'>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='col-lg-6'> 
                                                    <div class='form-group xCNPDT'>
                                                        <label class='xCNLabelFrm'><?= language('common/main/main','tCenterModalPDTPGPTo')?></label>
                                                        <div class='input-group'>
                                                            <input class='form-control xCNHide' id='oetBrowsePDTCodepgpto' name='oetBrowsePDTCodepgpto' maxlength='5' value=''>
                                                            <input class='form-control' type='text' id='oetBrowsePDTNamepgpto' name='oetBrowsePDTNamepgpto' value='' readonly=''>
                                                            <span  class='input-group-btn'>
                                                                <button id='obtPDTpgpBrowseto' type='button' class='btn xCNBtnBrowseAddOn'>
                                                                    <img src='<?=base_url()?>/application/modules/common/assets/images/icons/find-24.png'>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php break;
                                    case 'FromToPTY': ?>
                                        <div class=''>
                                            <div class='row'>
                                                <div class='col-lg-6'> 
                                                    <div class='form-group xCNPDT'>
                                                        <label class='xCNLabelFrm'><?= language('common/main/main','tCenterModalPDTPTYFrom')?></label>
                                                        <div class='input-group'>
                                                            <input class='form-control xCNHide' id='oetBrowsePDTCodeptyfrom' name='oetBrowsePDTCodeptyfrom' maxlength='5' value=''>
                                                            <input class='form-control' type='text' id='oetBrowsePDTNameptyfrom' name='oetBrowsePDTNameptyfrom' value='' readonly=''>
                                                            <span  class='input-group-btn'>
                                                                <button id='obtPDTptyBrowsefrom' type='button' class='btn xCNBtnBrowseAddOn'>
                                                                    <img src='<?=base_url()?>/application/modules/common/assets/images/icons/find-24.png'>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='col-lg-6'> 
                                                    <div class='form-group xCNPDT'>
                                                        <label class='xCNLabelFrm'><?= language('common/main/main','tCenterModalPDTPTYTo')?></label>
                                                        <div class='input-group'>
                                                            <input class='form-control xCNHide' id='oetBrowsePDTCodeptyto' name='oetBrowsePDTCodeptyto' maxlength='5' value=''>
                                                            <input class='form-control' type='text' id='oetBrowsePDTNameptyto' name='oetBrowsePDTNameptyto' value='' readonly=''>
                                                            <span  class='input-group-btn'>
                                                                <button id='obtPDTptyBrowseto' type='button' class='btn xCNBtnBrowseAddOn'>
                                                                    <img src='<?=base_url()?>/application/modules/common/assets/images/icons/find-24.png'>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php break;
                                    default:
                                } 
                            ?>
                        <?php } ?> 
                    </div>
                    <!--สาขา-->
                    <div id="odvBrowsePDTBranch" class="tab-pane fade <?=$tActiveBCHContent?>">
                        <?php 
                        $tDisableBranch = '';
                        $tDisableMerchant = '';
                        if($this->session->userdata('tSesUsrBchCode') == ''){ 
                            $tCodeSes = 'สำนักงานใหญ่'; ?>
                        <?php }else{ ?>
                            <label class='xCNLabelFrm'> <?= language('common/main/main','tCenterModalPDTBCHFrom')?> <?=$this->session->userdata('tSesUsrBchCode')?> ( <?=$this->session->userdata('tSesUsrBchName')?> )</label>
                        <?php } ?>
                        <?php for($i=0; $i<count($aQualitysearch); $i++){ ?>
                            <?php 
                                switch ($aQualitysearch[$i]) {
                                    case 'FromToBCH': 
                                        if(($SesBch == '' || $SesBch == null) && ($SesShp == '' || $SesShp == null)){ ?>
                                        <?php if($tBCHCode == '' || $tBCHCode == null){ ?>
                                            <?php $tDisableBranch = ''; ?>
                                        <?php }else{ ?>
                                            <?php $tDisableBranch = 'disabled'; ?>
                                        <?php } ?>
                                        <div class=''>
                                            <div class='row'>
                                                <div class='col-lg-6'> 
                                                    <div class='form-group xCNPDT'>
                                                        <label class='xCNLabelFrm'><?= language('common/main/main','tCenterModalPDTBCHFrom')?></label>
                                                        <div class='input-group'>
                                                            <input class='form-control xCNHide' id='oetBrowsePDTCodebchfrom' name='oetBrowsePDTCodebchfrom' maxlength='5' value='<?=$tBCHCode?>'>
                                                            <input class='form-control' type='text' id='oetBrowsePDTNamebchfrom' name='oetBrowsePDTNamebchfrom' value='<?=$tBCHName?>' readonly=''>
                                                            <span  class='input-group-btn'>
                                                                <button id='obtPDTbchBrowsefrom' type='button' class='btn xCNBtnBrowseAddOn <?=$tDisableBranch?>'>
                                                                    <img src='<?=base_url()?>/application/modules/common/assets/images/icons/find-24.png'>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='col-lg-6'> 
                                                    <div class='form-group xCNPDT'>
                                                        <label class='xCNLabelFrm'><?= language('common/main/main','tCenterModalPDTBCHTo')?></label>
                                                        <div class='input-group'>
                                                            <input class='form-control xCNHide' id='oetBrowsePDTCodebchto' name='oetBrowsePDTCodebchto' maxlength='5' value='<?=$tBCHCode?>'>
                                                            <input class='form-control' type='text' id='oetBrowsePDTNamebchto' name='oetBrowsePDTNamebchto' value='<?=$tBCHName?>' readonly=''>
                                                            <span  class='input-group-btn'>
                                                                <button id='obtPDTbchBrowseto' type='button' class='btn xCNBtnBrowseAddOn <?=$tDisableBranch?>'>
                                                                    <img src='<?=base_url()?>/application/modules/common/assets/images/icons/find-24.png'>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    <?php  break;
                                    case 'Merchant': ?>
                                        <?php if($tMCHCode == '' || $tMCHCode == null){ ?>
                                            <?php $tDisableMerchant = ''; ?>
                                        <?php }else{ ?>
                                            <?php $tDisableMerchant = 'disabled'; ?>
                                        <?php } ?>
                                        <div class=''>
                                            <div class='form-group xCNPDT'>
                                                <label class='xCNLabelFrm'><?= language('common/main/main','tCenterModalPDTMerchant')?></label>
                                                <div class='input-group'>
                                                    <input class='form-control xCNHide'  id='oetBrowsePDTCodeMerchant' name='oetBrowsePDTCodeMerchant' maxlength='5' value='<?=$tMCHCode?>'>
                                                    <input class='form-control' type='text' id='oetBrowsePDTNameMerchant' name='oetBrowsePDTNameMerchant' value='<?=$tMCHName?>' readonly=''>
                                                    <span  class='input-group-btn'>
                                                        <button id='obtPDTMerchantBrowse' type='button' class='btn xCNBtnBrowseAddOn <?=$tDisableMerchant?> '>
                                                            <img src='<?=base_url()?>/application/modules/common/assets/images/icons/find-24.png'>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php  break;
                                    case 'GroupMerchant': ?>
                                        <div class=''>
                                            <div class='form-group xCNPDT'>
                                                <label class='xCNLabelFrm'><?= language('common/main/main','tCenterModalPDTGroupMerchant')?></label>
                                                <div class='input-group'>
                                                    <input class='form-control xCNHide' id='oetBrowsePDTCodeGroupMerchant' name='oetBrowsePDTCodeGroupMerchant' maxlength='5' value=''>
                                                    <input class='form-control' type='text' id='oetBrowsePDTNameGroupMerchant' name='oetBrowsePDTNameGroupMerchant' value='' readonly=''>
                                                    <span  class='input-group-btn'>
                                                        <button id='obtPDTGroupMerchantBrowse' type='button' class='btn xCNBtnBrowseAddOn'>
                                                            <img src='<?=base_url()?>/application/modules/common/assets/images/icons/find-24.png'>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php  break;
                                    case 'FromToSHP': 
                                        if(($SesBch == '' || $SesBch == null) && ($SesShp == '' || $SesShp == null)){
                                            $nPermission = 'Y';
                                        }else if(($SesBch != '' || $SesBch != null) && ($SesShp == '' || $SesShp == null)){
                                            $nPermission = 'Y';
                                        }else if(($SesBch != '' || $SesBch != null) && ($SesShp != '' || $SesShp != null)){
                                            $nPermission = 'N';
                                        }else{
                                            $nPermission = 'N';
                                        }?>

                                        <?php if($nPermission == 'Y'){ ?>
                                            <div class=''>
                                                <div class='row'>
                                                    <div class='col-lg-6'> 
                                                        <div class='form-group xCNPDT'>
                                                            <label class='xCNLabelFrm'><?= language('common/main/main','tCenterModalPDTShopFrom')?></label>
                                                            <div class='input-group'>
                                                                <input class='form-control xCNHide' id='oetBrowsePDTCodeshpfrom' name='oetBrowsePDTCodeshpfrom' maxlength='5' value='<?=$tSHPCode?>'>
                                                                <input class='form-control' type='text' id='oetBrowsePDTNameshpfrom' name='oetBrowsePDTNameshpfrom' value='<?=$tSHPName?>' readonly=''>
                                                                <span  class='input-group-btn'>
                                                                    <button id='obtPDTshpBrowsefrom' type='button' class='btn xCNBtnBrowseAddOn'>
                                                                        <img src='<?=base_url()?>/application/modules/common/assets/images/icons/find-24.png'>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class='col-lg-6'> 
                                                        <div class='form-group xCNPDT'>
                                                            <label class='xCNLabelFrm'><?= language('common/main/main','tCenterModalPDTShopTo')?></label>
                                                            <div class='input-group'>
                                                                <input class='form-control xCNHide' id='oetBrowsePDTCodeshpto' name='oetBrowsePDTCodeshpto' maxlength='5' value='<?=$tSHPCode?>'>
                                                                <input class='form-control' type='text' id='oetBrowsePDTNameshpto' name='oetBrowsePDTNameshpto' value='<?=$tSHPName?>' readonly=''>
                                                                <span  class='input-group-btn'>
                                                                    <button id='obtPDTshpBrowseto' type='button' class='btn xCNBtnBrowseAddOn'>
                                                                        <img src='<?=base_url()?>/application/modules/common/assets/images/icons/find-24.png'>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php break;
                                    default:
                                } 
                            ?>
                        <?php } ?> 
                    </div>

                    <!--ตัวแทนจำหน่าย-->
                    <div id="odvBrowsePDTSupply" class="tab-pane fade <?=$tActivePurchasingContent?>">
                        <?php for($i=0; $i<count($aQualitysearch); $i++){ ?>
                            <?php 
                                switch ($aQualitysearch[$i]) {
                                    case 'SUP': ?>
                                        <div class=''>
                                            <div class='form-group xCNPDT'>
                                                <label class='xCNLabelFrm'><?= language('common/main/main','tCenterModalPDTSUP')?></label>
                                                <div class='input-group'>
                                                    <input class='form-control xCNHide' id='oetBrowsePDTCodesup' name='oetBrowsePDTCodesup' maxlength='5' value='<?=$tSPLCode?>'>
                                                    <input class='form-control' type='text' id='oetBrowsePDTNamesup' name='oetBrowsePDTNamesup' value='<?=$tSPLName?>' readonly=''>
                                                    <span  class='input-group-btn'>
                                                        <button id='obtPDTSupBrowse' type='button' class='btn xCNBtnBrowseAddOn'>
                                                            <img src='<?=base_url()?>/application/modules/common/assets/images/icons/find-24.png'>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php break; 
                                    case 'PurchasingManager': ?>
                                        <div class=''>
                                            <div class='form-group xCNPDT'>
                                                <label class='xCNLabelFrm'><?= language('common/main/main','tCenterModalPDTPurchasing')?></label>
                                                <div class='input-group'>
                                                    <input class='form-control xCNHide' id='oetBrowsePDTCodePurchasingManager' name='oetBrowsePDTCodePurchasingManager' maxlength='20' value=''>
                                                    <input class='form-control' type='text' id='oetBrowsePDTNamePurchasingManager' name='oetBrowsePDTNamePurchasingManager' value='' readonly=''>
                                                    <span  class='input-group-btn'>
                                                        <button id='obtPDTPurchasingManagerBrowse' type='button' class='btn xCNBtnBrowseAddOn'>
                                                            <img src='<?=base_url()?>/application/modules/common/assets/images/icons/find-24.png'>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php break; 
                                    default:
                                } 
                            ?>
                        <?php } ?> 
                    </div>
                    <!--ที่เก็บ-->
                    <div id="odvBrowsePDTStorage" class="tab-pane fade <?=$tActiveStorageContent?>">
                        <?php for($i=0; $i<count($aQualitysearch); $i++){ ?>
                            <?php 
                                switch ($aQualitysearch[$i]) {
                                    case 'PDTLOGSEQ': ?>
                                        <div class=''>
                                            <div class='form-group xCNPDT'>
                                                <label class='xCNLabelFrm'><?= language('common/main/main','tCenterModalPDTLOGSEQFrom')?></label>
                                                <div class='input-group'>
                                                    <input class='form-control xCNHide' id='oetBrowsePDTCodeLocseqto' name='oetBrowsePDTCodeLocseqto' maxlength='5' value=''>
                                                    <input class='form-control' type='text' id='oetBrowsePDTNameLocseqto' name='oetBrowsePDTNameLocseqto' value='' readonly=''>
                                                    <span  class='input-group-btn'>
                                                        <button id='obtPDTLocseqBrowseto' type='button' class='btn xCNBtnBrowseAddOn'>
                                                            <img src='<?=base_url()?>/application/modules/common/assets/images/icons/find-24.png'>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php break; 
                                    default:
                                } 
                            ?>
                        <?php } ?> 
                    </div>
                </div>
            </div>
        </div>

        <!--สินค้าเคลือนไหว-->
        <div class='col-md-12'>
            <div class='row'>
                <div class="col-lg-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ocbPDTMoveon" id="ocbPDTMoveon" value="1" checked>
                        <label class="form-check-label" >
                            <?= language('common/main/main','tCenterModalPDTMoveon')?>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!--BTN reset-->
        <div class='col-md-6' style='float: left;'>
            <div class='form-group xCNPDT'>
                <div class='input-group' style='width: 100%;'>
                    <button class='btn xCNBTNDefult' onclick='JSxResetDataFrom()' style='width: 100%; border-radius: 0px !important; margin-top: 10px; float: right;'>
                        <?= language('common/main/main','tCenterModalPDTReset')?>
                    </button>
                </div>
            </div>
        </div>

        <!--BTN search-->
        <div class='col-md-6' style='float: left;'>
            <div class='form-group xCNPDT'>
                <div class='input-group' style='width: 100%;'>
                    <button class='btn xCNBTNPrimery xCNBTNPrimery2Btn' onclick='JSxGetPDTTable()' style='width: 100%; border-radius: 0px !important; margin-top: 10px; float: right;'>
                        <?= language('common/main/main','tCenterModalPDTConfirm')?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end layout search-->

    <!--layout table-->
    <div class='col-xs-12 col-sm-12 col-md-12 col-lg-8'>
        <div id="odvTableContentPDT"></div>
    </div>
    <!--end layout table-->

</div>

<script>

    //Browse 
        var tSesBchCode     = $('#ohdSessionBCH').val();
        var tSesShpCode     = $('#ohdSessionSHP').val();
        if(tSesBchCode == '' || tSesBchCode == null){
            var tSesBchCode = '';
        }else{
            var tSesBchCode = tSesBchCode;
        };

        //Browse SPL
        var nLangEdits      = <?php echo $this->session->userdata("tLangEdit");?>;
        var oCmpBrowseSPL   = {
            Title : ['supplier/supplier/supplier','tSPLTitle'],
            Table:{Master:'TCNMSpl',PK:'FTSplCode'},
            Join :{
                Table:	['TCNMSpl_L'],
                On:['TCNMSpl_L.FTSplCode = TCNMSpl.FTSplCode AND TCNMSpl_L.FNLngID = '+nLangEdits,]
            },
            Where:{
                Condition : ["AND TCNMSpl.FTSplStaActive = '1' "]
            },
            GrideView:{
                ColumnPathLang	: 'supplier/supplier/supplier',
                ColumnKeyLang	: ['tSPLTBCode','tSPLTBName'],
                ColumnsSize     : ['15%','75%'],
                WidthModal      : 50,
                DataColumns		: ['TCNMSpl.FTSplCode','TCNMSpl_L.FTSplName','TCNMSpl.FTSplStaVATInOrEx'],
                DataColumnsFormat : ['','',''],
                DisabledColumns : [2],
                Perpage			: 10,
                OrderBy			: ['TCNMSpl_L.FTSplName'],
                SourceOrder		: "ASC"
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: ["oetBrowsePDTCodesup","TCNMSpl.FTSplCode"],
                Text		: ["oetBrowsePDTNamesup","TCNMSpl_L.FTSplName"],
            },
            NextFunc:{
                FuncName    :'JSxModalShow',
                ArgReturn   :[]
            }
        }
        $('#obtPDTSupBrowse').click(function(){
            $('#odvModalDOCPDT').css('display','none');
            JCNxBrowseData('oCmpBrowseSPL');
            JSxWhenCloseModalCenter();
        });

        //Browse Purchasing Manager
        var oCmpBrowsePurchasingManager   = {
            Title : ['authen/user/user','tUSRTitle'],
            Table:{Master:'TCNMUser',PK:'FTUsrCode'},
            Join :{
                Table:	['TCNMUser_L'],
                On:['TCNMUser_L.FTUsrCode = TCNMUser.FTUsrCode AND TCNMUser_L.FNLngID = '+nLangEdits,]
            },
            GrideView:{
                ColumnPathLang	: 'authen/user/user',
                ColumnKeyLang	: ['tUSRCode','tUSRName'],
                ColumnsSize     : ['15%','75%'],
                WidthModal      : 50,
                DataColumns		: ['TCNMUser.FTUsrCode','TCNMUser_L.FTUsrName'],
                DataColumnsFormat : ['',''],
                Perpage			: 10,
                OrderBy			: ['TCNMUser.FTUsrCode'],
                SourceOrder		: "ASC"
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: ["oetBrowsePDTCodePurchasingManager","TCNMUser.FTUsrCode"],
                Text		: ["oetBrowsePDTNamePurchasingManager","TCNMUser_L.FTUsrName"],
            },
            NextFunc:{
                FuncName    :'JSxModalShow',
                ArgReturn   :[]
            }
        }
        $('#obtPDTPurchasingManagerBrowse').click(function(){
            $('#odvModalDOCPDT').css('display','none');
            JCNxBrowseData('oCmpBrowsePurchasingManager');
            JSxWhenCloseModalCenter();
        });

        //Browse BCH From
        var oCmpBrowseBCHFrom   = {
            Title : ['company/branch/branch','tBCHTitle'],
            Table:{Master:'TCNMBranch',PK:'FTBchCode',PKName:'FTBchName'},
            Join :{
                Table:	['TCNMBranch_L'],
                On:['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
            },
            GrideView:{
                ColumnPathLang	: 'company/branch/branch',
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
                Value		: ["oetBrowsePDTCodebchfrom","TCNMBranch.FTBchCode"],
                Text		: ["oetBrowsePDTNamebchfrom","TCNMBranch_L.FTBchName"],
            },
            NextFunc:{
                FuncName    : 'JSxModalShow',
                ArgReturn   : []
            }
        }
        <?php
        if($tDisableBranch==""){
        ?>
        $('#obtPDTbchBrowsefrom').click(function(){
            $('#odvModalDOCPDT').css('display','none');
            JCNxBrowseData('oCmpBrowseBCHFrom');
            JSxWhenCloseModalCenter();
        });
        <?php
        }
        ?>
        

        //Browse BCH To
        var oCmpBrowseBCHTo  = {
            Title : ['company/branch/branch','tBCHTitle'],
            Table:{Master:'TCNMBranch',PK:'FTBchCode',PKName:'FTBchName'},
            Join :{
                Table:	['TCNMBranch_L'],
                On:['TCNMBranch_L.FTBchCode = TCNMBranch.FTBchCode AND TCNMBranch_L.FNLngID = '+nLangEdits,]
            },
            GrideView:{
                ColumnPathLang	: 'company/branch/branch',
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
                Value		: ["oetBrowsePDTCodebchto","TCNMBranch.FTBchCode"],
                Text		: ["oetBrowsePDTNamebchto","TCNMBranch_L.FTBchName"],
            },
            NextFunc:{
                FuncName    : 'JSxModalShow',
                ArgReturn   : []
            }
        }

        <?php
        if($tDisableBranch==""){
        ?>
        $('#obtPDTbchBrowseto').click(function(){
            $('#odvModalDOCPDT').css('display','none');
            JCNxBrowseData('oCmpBrowseBCHTo');
            JSxWhenCloseModalCenter();
        });
        <?php
        }
        ?>
        //Browse SHP From
        var oCmpBrowseSHPFrom   = {
            Title : ['authen/user/user','tBrowseSHPTitle'],
            Table:{Master:'TCNMShop',PK:'FTShpCode'},
            Join : {
                Table   :	['TCNMShop_L'],
                On      :   ['TCNMShop_L.FTShpCode = TCNMShop.FTShpCode AND TCNMShop_L.FNLngID = '+nLangEdits,]
            },
            GrideView:{
                ColumnPathLang	: 'authen/user/user',
                ColumnKeyLang	: ['tBrowseSHPCode','tBrowseSHPName'],
                ColumnsSize     : ['10%','75%'],
                WidthModal      : 50,
                DataColumns		: ['TCNMShop.FTShpCode','TCNMShop_L.FTShpName'],
                DistinctField   : [0],
                DataColumnsFormat : ['',''],
                Perpage			: 10,
                OrderBy			: ['TCNMShop.FTShpCode'],
                SourceOrder		: "ASC"
            },
            Where :{
                Condition : [
                        function() {
                            var tSQL = "";
                            if (tSesBchCode != "") {
                                tSQL += " AND TCNMShop.FTBchCode = '"+tSesBchCode+"' ";
                            }else{
                                tSQL += "";
                            }
                            return tSQL;
                        }
                    ]
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: ["oetBrowsePDTCodeshpfrom","TCNMShop.FTShpCode"],
                Text		: ["oetBrowsePDTNameshpfrom","TCNMShop_L.FTShpName"],
            },
            NextFunc:{
                FuncName    : 'JSxModalShow',
                ArgReturn   : []
            },
            //DebugSQL : true
        }
        $('#obtPDTshpBrowsefrom').click(function(){
            $('#odvModalDOCPDT').css('display','none');
            JCNxBrowseData('oCmpBrowseSHPFrom');
            JSxWhenCloseModalCenter();
        });

        //Browse SHP To
        var oCmpBrowseSHPTo  = {
            Title : ['authen/user/user','tBrowseSHPTitle'],
            Table:{Master:'TCNMShop',PK:'FTShpCode'},
            Join :{
                Table   :	['TCNMShop_L'],
                On      :   ['TCNMShop_L.FTShpCode = TCNMShop.FTShpCode AND TCNMShop_L.FNLngID = '+nLangEdits,]
            },
            GrideView:{
                ColumnPathLang	: 'authen/user/user',
                ColumnKeyLang	: ['tBrowseSHPCode','tBrowseSHPName'],
                ColumnsSize     : ['10%','75%'],
                WidthModal      : 50,
                DataColumns		: ['TCNMShop.FTShpCode','TCNMShop_L.FTShpName'],
                DistinctField   : [0],
                DataColumnsFormat : ['',''],
                Perpage			: 10,
                OrderBy			: ['TCNMShop.FTShpCode'],
                SourceOrder		: "ASC"
            },
            Where :{
                Condition : [
                        function() {
                            var tSQL = "";
                            if (tSesBchCode != "") {
                                tSQL += " AND TCNMShop.FTBchCode = '"+tSesBchCode+"' ";
                            }else{
                                tSQL += "";
                            }
                            return tSQL;
                        }
                    ]
            },
            CallBack:{
                ReturnType	: 'S',
                Value		: ["oetBrowsePDTCodeshpto","TCNMShop.FTShpCode"],
                Text		: ["oetBrowsePDTNameshpto","TCNMShop_L.FTShpName"],
            },
            NextFunc:{
                FuncName    : 'JSxModalShow',
                ArgReturn   : []
            }
        }
        $('#obtPDTshpBrowseto').click(function(){
            $('#odvModalDOCPDT').css('display','none');
            JCNxBrowseData('oCmpBrowseSHPTo');
            JSxWhenCloseModalCenter();
        });

        //Browse PGP From
        var oCmpBrowseProductGroupFrom = {
            Title : ['product/pdtlocation/pdtlocation','tPGPBrowsePDT'],
            Table:{Master:'TCNMPdtGrp',PK:'FTPgpCode'},
            Join :{
                Table:	['TCNMPdtGrp_L'],
                On:['TCNMPdtGrp_L.FTPgpChain = TCNMPdtGrp.FTPgpCode AND TCNMPdtGrp_L.FNLngID = '+nLangEdits,]
            },
            GrideView:{
                ColumnPathLang	: 'product/pdtlocation/pdtlocation',
                ColumnKeyLang	: ['tLOCCode','tLOCName'],
                ColumnsSize     : ['10%','75%'],
                DataColumns		: ['TCNMPdtGrp.FTPgpCode','TCNMPdtGrp_L.FTPgpName'],
                DataColumnsFormat : ['',''],
                WidthModal      : 50,
                Perpage			: 10,
                OrderBy			: ['TCNMPdtGrp.FTPgpCode'],
                SourceOrder		: "ASC"
            },
            CallBack:{
                ReturnType	: 'S',
                Value       : ["oetBrowsePDTCodepgpfrom","TCNMPdtGrp.FTPgpCode"],
                Text		: ["oetBrowsePDTNamepgpfrom","TCNMPdtGrp_L.FTPgpName"],
            },
            NextFunc:{
                FuncName    : 'JSxModalShow',
                ArgReturn   : []
            }
        } 
        $('#obtPDTpgpBrowsefrom').click(function(){
            $('#odvModalDOCPDT').css('display','none');
            JCNxBrowseData('oCmpBrowseProductGroupFrom');
            JSxWhenCloseModalCenter();
        });

        //Browse PGP To
        var oCmpBrowseProductGroupTo = {
            Title : ['product/pdtlocation/pdtlocation','tPGPBrowsePDT'],
            Table:{Master:'TCNMPdtGrp',PK:'FTPgpCode'},
            Join :{
                Table:	['TCNMPdtGrp_L'],
                On:['TCNMPdtGrp_L.FTPgpChain = TCNMPdtGrp.FTPgpCode AND TCNMPdtGrp_L.FNLngID = '+nLangEdits,]
            },
            GrideView:{
                ColumnPathLang	: 'product/pdtlocation/pdtlocation',
                ColumnKeyLang	: ['tLOCCode','tLOCName'],
                ColumnsSize     : ['10%','75%'],
                DataColumns		: ['TCNMPdtGrp.FTPgpCode','TCNMPdtGrp_L.FTPgpName'],
                DataColumnsFormat : ['',''],
                WidthModal      : 50,
                Perpage			: 10,
                OrderBy			: ['TCNMPdtGrp.FTPgpCode'],
                SourceOrder		: "ASC"
            },
            CallBack:{
                ReturnType	: 'S',
                Value       : ["oetBrowsePDTCodepgpto","TCNMPdtGrp.FTPgpCode"],
                Text		: ["oetBrowsePDTNamepgpto","TCNMPdtGrp_L.FTPgpName"],
            },
            NextFunc:{
                FuncName    : 'JSxModalShow',
                ArgReturn   : []
            }
        } 
        $('#obtPDTpgpBrowseto').click(function(){
            $('#odvModalDOCPDT').css('display','none');
            JCNxBrowseData('oCmpBrowseProductGroupTo');
            JSxWhenCloseModalCenter();
        });

        //Browse Type From
        var oCmpBrowseProductPTYFrom = {
            Title : ['product/pdtlocation/pdtlocation','tPTYBrowsePDT'],
            Table:{Master:'TCNMPdtType',PK:'FTPtyCode'},
            Join :{
                Table:	['TCNMPdtType_L'],
                On:['TCNMPdtType_L.FTPtyCode = TCNMPdtType.FTPtyCode AND TCNMPdtType_L.FNLngID = '+nLangEdits,]
            },
            GrideView:{
                ColumnPathLang	: 'product/pdtlocation/pdtlocation',
                ColumnKeyLang	: ['tLOCCode','tLOCName'],
                ColumnsSize     : ['10%','75%'],
                DataColumns		: ['TCNMPdtType.FTPtyCode','TCNMPdtType_L.FTPtyName'],
                DataColumnsFormat : ['',''],
                WidthModal      : 50,
                Perpage			: 10,
                OrderBy			: ['TCNMPdtType.FTPtyCode'],
                SourceOrder		: "ASC"
            },
            CallBack:{
                ReturnType	: 'S',
                Value       : ["oetBrowsePDTCodeptyfrom","TCNMPdtType.FTPtyCode"],
                Text		: ["oetBrowsePDTNameptyfrom","TCNMPdtType_L.FTPtyName"],
            },
            NextFunc:{
                FuncName    : 'JSxModalShow',
                ArgReturn   : []
            }
        } 
        $('#obtPDTptyBrowsefrom').click(function(){
            $('#odvModalDOCPDT').css('display','none');
            JCNxBrowseData('oCmpBrowseProductPTYFrom');
            JSxWhenCloseModalCenter();
        });

        //Browse Type To
        var oCmpBrowseProductPTYTo = {
            Title : ['product/pdtlocation/pdtlocation','tPTYBrowsePDT'],
            Table:{Master:'TCNMPdtType',PK:'FTPtyCode'},
            Join :{
                Table:	['TCNMPdtType_L'],
                On:['TCNMPdtType_L.FTPtyCode = TCNMPdtType.FTPtyCode AND TCNMPdtType_L.FNLngID = '+nLangEdits,]
            },
            GrideView:{
                ColumnPathLang	: 'product/pdtlocation/pdtlocation',
                ColumnKeyLang	: ['tLOCCode','tLOCName'],
                ColumnsSize     : ['10%','75%'],
                DataColumns		: ['TCNMPdtType.FTPtyCode','TCNMPdtType_L.FTPtyName'],
                DataColumnsFormat : ['',''],
                WidthModal      : 50,
                Perpage			: 10,
                OrderBy			: ['TCNMPdtType.FTPtyCode'],
                SourceOrder		: "ASC"
            },
            CallBack:{
                ReturnType	: 'S',
                Value       : ["oetBrowsePDTCodeptyto","TCNMPdtType.FTPtyCode"],
                Text		: ["oetBrowsePDTNameptyto","TCNMPdtType_L.FTPtyName"],
            },
            NextFunc:{
                FuncName    : 'JSxModalShow',
                ArgReturn   : []
            }
        } 
        $('#obtPDTptyBrowseto').click(function(){
            $('#odvModalDOCPDT').css('display','none');
            JCNxBrowseData('oCmpBrowseProductPTYTo');
            JSxWhenCloseModalCenter();
        });

        //Browse Merchant
        var oCmpBrowseProductMerchant = {
            Title   :   ['company/merchant/merchant','tMerchantTitle'],
            Table   :   {Master:'TCNMShop',PK:'FTMerCode'},
            Join    :   {
                Table       :	['TCNMMerchant'],
                SpecialJoin :   ['RIGHT JOIN'],
                On          :   ['TCNMShop.FTMerCode = TCNMMerchant.FTMerCode LEFT JOIN TCNMMerchant_L ON TCNMMerchant_L.FTMerCode = TCNMMerchant.FTMerCode AND TCNMMerchant_L.FNLngID = '+nLangEdits,]
            },
            GrideView   :   {
                ColumnPathLang	: 'company/merchant/merchant',
                ColumnKeyLang	: ['tMerCode','tMerName'],
                ColumnsSize     : ['10%','75%'],
                DataColumns		: ['TCNMMerchant.FTMerCode','TCNMMerchant_L.FTMerName'],
                DistinctField   : [0],
                DataColumnsFormat : ['',''],
                WidthModal      : 50,
                Perpage			: 10,
                OrderBy			: ['TCNMMerchant.FTMerCode'],
                SourceOrder		: "ASC"
            },
            CallBack    :   {
                ReturnType	: 'S',
                Value       : ["oetBrowsePDTCodeMerchant","TCNMMerchant.FTMerCode"],
                Text		: ["oetBrowsePDTNameMerchant","TCNMMerchant_L.FTMerName"],
            },
            NextFunc    :   {
                FuncName    : 'JSxModalShow',
                ArgReturn   : []
            },
            //DebugSQL : true
        } 
        <?php
        if($tDisableMerchant==""){
        ?>
        $('#obtPDTMerchantBrowse').click(function(){
            $('#odvModalDOCPDT').css('display','none');
            JCNxBrowseData('oCmpBrowseProductMerchant');
            JSxWhenCloseModalCenter();

            //reset group merchant
            $('#oetBrowsePDTCodeGroupMerchant').val('');
            $('#oetBrowsePDTNameGroupMerchant').val('');
        });
        <?php
        }
        ?>
        

        //Browse PDT LOG SEQ To
        var oCmpBrowsePDTLogseqto = {
            Title   :   ['common/main/main','tModalPDTLogseqHead'],
            Table   :   {Master:'TCNMPdtLoc_L',PK:'FTPlcCode'},
            GrideView   :   {
                ColumnPathLang	: 'common/main/main',
                ColumnKeyLang	: ['tModalPDTLogseqCode','tModalPDTLogseqName'],
                ColumnsSize     : ['10%','75%'],
                DataColumns		: ['TCNMPdtLoc_L.FTPlcCode','TCNMPdtLoc_L.FTPlcName'],
                DataColumnsFormat : ['',''],
                WidthModal      : 50,
                Perpage			: 10,
                OrderBy			: ['TCNMPdtLoc_L.FTPlcCode'],
                SourceOrder		: "ASC"
            },
            Where :{
                Condition : [ 'AND TCNMPdtLoc_L.FNLngID = ' + nLangEdits]
            },
            CallBack    :   {
                ReturnType	: 'S',
                Value       : ["oetBrowsePDTCodeLocseqto","TCNMPdtLoc_L.FTPlcCode"],
                Text		: ["oetBrowsePDTNameLocseqto","TCNMPdtLoc_L.FTPlcName"],
            },
            NextFunc    :   {
                FuncName    : 'JSxModalShow',
                ArgReturn   : []
            },
            //DebugSQL : true
        } 
        $('#obtPDTLocseqBrowseto').click(function(){
            $('#odvModalDOCPDT').css('display','none');
            JCNxBrowseData('oCmpBrowsePDTLogseqto');
            JSxWhenCloseModalCenter();
        });

        //Browse PDT LOG SEQ From
        // var oCmpBrowsePDTLogseqfrom = {
        //     Title   :   ['common/main/main','tModalPDTLogseqHead'],
        //     Table   :   {Master:'TCNMPdtLoc_L',PK:'FTPlcCode'},
        //     GrideView   :   {
        //         ColumnPathLang	: 'common/main/main',
        //         ColumnKeyLang	: ['tModalPDTLogseqCode','tModalPDTLogseqName'],
        //         ColumnsSize     : ['10%','75%'],
        //         DataColumns		: ['TCNMPdtLoc_L.FTPlcCode','TCNMPdtLoc_L.FTPlcName'],
        //         DataColumnsFormat : ['',''],
        //         WidthModal      : 50,
        //         Perpage			: 10,
        //         OrderBy			: ['TCNMPdtLoc_L.FTPlcCode'],
        //         SourceOrder		: "ASC"
        //     },
        //     Where :{
        //         Condition : [ 'AND TCNMPdtLoc_L.FNLngID = ' + nLangEdits]
        //     },
        //     CallBack    :   {
        //         ReturnType	: 'S',
        //         Value       : ["oetBrowsePDTCodeLocseqfrom","TCNMPdtLoc_L.FTPlcCode"],
        //         Text		: ["oetBrowsePDTNameLocseqfrom","TCNMPdtLoc_L.FTPlcName"],
        //     },
        //     NextFunc    :   {
        //         FuncName    : 'JSxModalShow',
        //         ArgReturn   : []
        //     },
        //     //DebugSQL : true
        // } 
        // $('#obtPDTLocseqBrowsefrom').click(function(){
        //     $('#odvModalDOCPDT').css('display','none');
        //     JCNxBrowseData('oCmpBrowsePDTLogseqfrom');
        //     JSxWhenCloseModalCenter();
        // });
        
        //Browse Group merchant
        var oCmpBrowseGroupMerchant = {
            Title   :   ['common/main/main','tCenterModalPDTGroupMerchant'],
            Table   :   {Master:'TCNMMerPdtGrp',PK:'FTMgpCode'},
            Join    :   {
                Table   :	['TCNMMerPdtGrp_L'],
                On      :   ['TCNMMerPdtGrp_L.FTMgpCode = TCNMMerPdtGrp.FTMgpCode AND TCNMMerPdtGrp_L.FNLngID = '+nLangEdits,]
            },
            GrideView   :   {
                ColumnPathLang	: 'common/main/main',
                ColumnKeyLang	: ['tModalPDTLogseqCode','tModalPDTLogseqName'],
                ColumnsSize     : ['10%','75%'],
                DataColumns		: ['TCNMMerPdtGrp.FTMgpCode','TCNMMerPdtGrp_L.FTMgpName'],
                DistinctField   : [0],
                DataColumnsFormat : ['',''],
                WidthModal      : 50,
                Perpage			: 10,
                OrderBy			: ['TCNMMerPdtGrp.FTMgpCode'],
                SourceOrder		: "ASC"
            },
            Where :{
                Condition : [
                    function() {
                        var tSQL = "";
                        //เข้ามาแบบ user shop
                        if(tSesShpCode == '' || tSesShpCode == null){
                            var nMerchantCode = $('#oetBrowsePDTCodeMerchant').val();
                            //ถ้า merchant ไม่มี
                            if(nMerchantCode == '' || nMerchantCode == null){
                                tSQL += "";
                            }else{ //ถ้ามี merchant เอามา where
                                tSQL += " AND TCNMMerPdtGrp.FTMerCode = '"+nMerchantCode+"' ";
                            }
                        }else{ //เข้ามาแบบ user HQ + user BCH
                            tSQL += " AND TCNMMerPdtGrp.FTMerCode = '"+tSesShpCode+"' ";
                        }
                        return tSQL;
                    }
                ]
            },
            CallBack    :   {
                ReturnType	: 'S',
                Value       : ["oetBrowsePDTCodeGroupMerchant","TCNMMerPdtGrp.FTMgpCode"],
                Text		: ["oetBrowsePDTNameGroupMerchant","TCNMMerPdtGrp_L.FTMgpName"],
            },
            NextFunc    :   {
                FuncName    : 'JSxModalShow',
                ArgReturn   : []
            },
            //DebugSQL : true
        } 
        $('#obtPDTGroupMerchantBrowse').click(function(){
            $('#odvModalDOCPDT').css('display','none');
            JCNxBrowseData('oCmpBrowseGroupMerchant');
            JSxWhenCloseModalCenter();
        });
        
        function JSxModalShow(){
            // $('#odvModalDOCPDT').css('display','block');
            // $('body').append('<div class="odvModalBackdropPDT modal-backdrop fade in"></div>');
        }

    //End Browse

        
    //Get Data
    JSxGetPDTTable();
    function JSxGetPDTTable(pnPage){
        if(pnPage == '' || pnPage == null){ pnPage = 1; }else{ pnPage = pnPage; }

        var SelectTier = $('#odhEleSelectTier').val();
        var aPriceType = '<?=$aPriceType[0]?>';

        //สินค้าเคลื่อนไหว
        if($('#ocbPDTMoveon').is(":checked")){
            var nPDTMoveon = 1;
        }else{
            var nPDTMoveon = 2;
        }
        
        $.ajax({
            type    : "POST",
            url     : "BrowseDataPDTTable",
            data    : {
                'tPagename'             : '<?=$tPagename?>',
                'nPage'                 : pnPage,
                'nRow'                  : '<?=$nShowCountRecord?>',
                'aPriceType'            : '<?=json_encode($aPriceType)?>',
                'SPL'                   : $('#oetBrowsePDTCodesup').val(),
                'NamePDT'               : $('#oetBrowsePDTNamepdt').val(),
                'CodePDT'               : $('#oetBrowsePDTCodepdt').val(),
                'BCH'                   : [$('#oetBrowsePDTCodebchfrom').val(),$('#oetBrowsePDTCodebchto').val()],
                'SHP'                   : [$('#oetBrowsePDTCodeshpfrom').val(),$('#oetBrowsePDTCodeshpto').val()],
                'PGP'                   : [$('#oetBrowsePDTCodepgpfrom').val(),$('#oetBrowsePDTCodepgpto').val()],
                'PTY'                   : [$('#oetBrowsePDTCodeptyfrom').val(),$('#oetBrowsePDTCodeptyto').val()],
                'Merchant'              : $('#oetBrowsePDTCodeMerchant').val(),
                'SelectTier'            : $('#odhEleSelectTier').val(),
                'ReturnType'            : $('#odhEleReturnType').val(),
                'aNotinItem'            : $('#ohdNotinItem').val(),
                'PDTMoveon'             : nPDTMoveon,
                'tBarcode'              : $('#oetBrowsePDTBarcode').val(),
                'tPurchasingManager'    : $('#oetBrowsePDTCodePurchasingManager').val(),
                'tPDTLOGSEQ'            : $('#oetBrowsePDTCodeLocseqto').val(),
                'tMerchantGroup'        : $('#oetBrowsePDTCodeGroupMerchant').val()
            },
            cache   : false,
            timeout : 0,
            success : function(tResult){
                $('#odvTableContentPDT').html(tResult);

                if(aPriceType == 'Pricesell' && SelectTier == 'PDT'){
                    var LocalItemDataPDT  = localStorage.getItem("LocalItemDataPDT");
                    if(LocalItemDataPDT != '' || LocalItemDataPDT != null){
                        var tResultPDT = JSON.parse(LocalItemDataPDT);
                        if(tResultPDT == null || tResultPDT == ''){
                            //console.log('null');
                        }else{
                            var nCount  = tResultPDT.length;
                            for($i=0; $i<nCount; $i++){
                                var tStringCheck    = tResultPDT[$i].pnPdtCode+tResultPDT[$i].ptPunCode;
                                var tChcek          = 'JSxPDTClickMuti' + tStringCheck;
                                $('.'+tChcek).addClass('xCNActivePDT');
                            }
                        }
                    }
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

    //Reset Date
    function JSxResetDataFrom(){
        $('#odvModalsectionBodyPDT').find('input').val('');
    }

    //When Close modal
    var interval = null;
    function JSxWhenCloseModalCenter(){
        interval = setInterval(function(){ 
            var tModalCenter = $('.xCNModalByModule').hasClass('in');
            if(tModalCenter == false){
                JSxModalShowAgain();
                clearInterval(interval);
            }
        }, 1000);
    }

    function JSxModalShowAgain(){
        $('body').fadeIn().append('<div class="odvModalBackdropPDT modal-backdrop fade in"></div>');
        $('#odvModalDOCPDT').css('display','block');
    }



 

</script>