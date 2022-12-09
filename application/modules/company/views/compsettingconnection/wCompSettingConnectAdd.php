<?php
    if($aResult['rtCode'] == 1){
        
        $tImgObjAll =  $aResult['raItems'][0]['FTUrlLogo'];

        // Type 9  API2CNMember
        for($nI=0;$nI<count($aResult['raItems']);$nI++){
            if($aResult['raItems'][$nI]["FNUrlType"]=="9"){
                $tUrlID       	    = $aResult['raItems'][$nI]['FNUrlID'];
                $tUrlAddress       	= $aResult['raItems'][$nI]['rtAddressUrlobj'];
                $tUrlPort           = $aResult['raItems'][$nI]['FTUrlPort'];
                $tUrlKey            = $aResult['raItems'][$nI]['FTUrlKey'];
                $tUrlType           = $aResult['raItems'][$nI]['FNUrlType'];
            }
        }

        // Type 10 API2RDFace
        for($nI=0;$nI<count($aResult['raItems']);$nI++){
            if($aResult['raItems'][$nI]["FNUrlType"]=="10"){
                $tUrlID       	    = $aResult['raItems'][$nI]['FNUrlID'];
                $tUrlAddress       	= $aResult['raItems'][$nI]['rtAddressUrlobj'];
                $tUrlPort           = $aResult['raItems'][$nI]['FTUrlPort'];
                $tUrlKey            = $aResult['raItems'][$nI]['FTUrlKey'];
                $tUrlType           = $aResult['raItems'][$nI]['FNUrlType'];
            }
        }

        // Type 11 API2RDFaceRegis
        for($nI=0;$nI<count($aResult['raItems']);$nI++){
            if($aResult['raItems'][$nI]["FNUrlType"]=="11"){
                $tUrlID       	    = $aResult['raItems'][$nI]['FNUrlID'];
                $tUrlAddress       	= $aResult['raItems'][$nI]['rtAddressUrlobj'];
                $tUrlPort           = $aResult['raItems'][$nI]['FTUrlPort'];
                $tUrlKey            = $aResult['raItems'][$nI]['FTUrlKey'];
                $tUrlType           = $aResult['raItems'][$nI]['FNUrlType'];
            }
        }
    

        $tEventpage  = "Edit";
        $tRoute      = "CompSettingConEventEdit";
    }else{

        // TCNTUrlObject
        $tUrlID              = "";
        $tUrlAddress         = "";
        $tUrlPort            = "";
        $tUrlKey             = "";
        $tUrlType            = "";


        $tImgObjAll         = "";


        $tRoute     = "CompSettingConEventAdd";
        $tEventpage = "Add";
    }

?>

<form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddEditCompSettingConnect">
    <input type="hidden" id="ohdTRoute" value="<?php echo $tRoute;?>">
    <input type="hidden" id="ohdUrlId" name="ohdUrlId" value="<?php echo $tUrlID;?>">

    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <label class="xCNLabelFrm" style="color: #179bfd !important; cursor:pointer;" onclick="JSxCompSettingConnect();" ><?php echo language('company/compsettingconnect/compsettingconnect','tDetailSettingConnect')?></label>
            <label class="xCNLabelFrm">
            <?php
                if($tEventpage == "Edit"){ ?>
                    <label class="xCNLabelFrm xWPageEdit" style="color: #aba9a9 !important;"> / <?php echo language('company/compsettingconnect/compsettingconnect','tCompSettingEdit')?> </label>   
               <?php }else if($tEventpage == "Add"){?>
                    <label class="xCNLabelFrm xWPageAdd" style="color: #aba9a9 !important;"> / <?php echo language('company/compsettingconnect/compsettingconnect','tCompAddSetConnect')?> </label> 
               <?php }
            ?>
        </div>

        <!--ปุ่มเพิ่ม-->
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6  text-right">
            <button type="button" onclick="JSxCompSettingConnect();" class="btn" style="background-color: #D4D4D4; color: #000000;">
                <?php echo language('common/main/main', 'tCancel')?>
            </button>
            <input id="ohdTypeResendData" type="hidden" value="normal" >
            <button type="submit" class="btn" style="background-color: rgb(23, 155, 253); color: white;" id="obtBchSettingConnectSave" onclick="JSxCompSettingConnectSaveAddEdit('<?=$tRoute?>')"> <?php echo  language('common/main/main', 'tSave')?></button>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><hr></div>
 
        <div class="row">
            <div class="col-md-7">
                <div class="panel panel-default" style="margin-bottom: 25px;" id="odvPanelUrl">
                    <div id="odvHeadStatus" class="panel-heading xCNPanelHeadColor" role="tab" style="padding-top:10px;padding-bottom:10px;">
                        <label class="xCNTextDetail1"><?php echo language('company/compsettingconnect/compsettingconnect', 'tCompSettingURL'); ?></label>
                        <a class="xCNMenuplus" role="button" data-toggle="collapse"  href="#odvDataCompSettingCon" aria-expanded="true">
                            <i class="fa fa-plus xCNPlus"></i>
                        </a>
                    </div>

                    <div id="odvDataCompSettingCon" class="panel-collapse collapse in" role="tabpanel" style="margin-top:10px;">
                        <div class="panel-body xCNPDModlue">
                            <!-- UploadImg CompSettingConnection -->
                            <div class="col-lg-6 col-md-6 col-xs-6">
                                <div class="form-group">
                                    <div class="odvCompLogo">
                                        <?php 
                                            if(isset($tImgObjAll) && !empty($tImgObjAll)){
                                                
                                                $tImgPath  = explode("/application/modules/",$tImgObjAll);

                                                $tFullPatch = './application/modules/'.$tImgPath[1]; 

                                     

                                                if (file_exists($tFullPatch)){
                                                    $tPatchImg = base_url().'/application/modules/'.$tImgPath[1];
                                                }else{
                                                    $tPatchImg = base_url().'application/modules/common/assets/images/300x60.png';
                                                }
                                            }else{
                                                $tPatchImg = base_url().'application/modules/common/assets/images/300x60.png';
                                            }
                                        ?>
                                        <img class="img-responsive xCNImgCenter" id="oimImgMasterCompSetCon" src="<?php echo @$tPatchImg;?>">
                                    </div>
                                    <div class="xCNUplodeImage">
                                        <input type="text" class="xCNHide" id="oetImgInputCompSetConOld" name="oetImgInputCompSetConOld"  value="<?php echo @$tImgName;?>">
                                        <input type="text" class="xCNHide" id="oetImgInputCompSetCon" 	name="oetImgInputCompSetCon" 	value="<?php echo @$tImgName;?>">
                                        <button type="button" class="btn xCNBTNDefult" onclick="JSvImageCallTempNEW('','','CompSetCon')">
                                            <i class="fa fa-picture-o xCNImgButton"></i> <?php echo  language('common/main/main','tSelectPic')?>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- ประเภทการเข้าใช้งาน -->
                            <div class="col-lg-6 col-md-6 col-xs-6">
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?php echo language('company/compsettingconnect/compsettingconnect', 'tCompSettingType');?></label>
                                    <?php
                                        if(isset($tUrlType) && !empty($tUrlType)){
                                    ?>
                                    <input type="hidden" id="ohdTypeAddUrlType" name="ohdTypeAddUrlType" value="1"> 
                                    <input type="hidden" id="ohdTypeAddUrlTypeVal" name="ohdTypeAddUrlTypeVal" value="<?php echo $tUrlType;?>">
                                    <select class="selectpicker form-control" id="ocmUrlConnecttype" name="ocmUrlConnecttype" onchange="JSxCompSettingConTypeUsed('insert')"
                                        <?=  "disabled"?>>
                                        <option value = "9" <?= (!empty($tUrlType) && $tUrlType == '9')? "selected":""?>>
                                            <?php echo language('company/compsettingconnect/compsettingconnect','tCompURLAPI2CNMember');?>
                                        </option>
                                        <option value = "10" <?= (!empty($tUrlType) && $tUrlType == '10')? "selected":""?>>
                                            <?php echo language('company/compsettingconnect/compsettingconnect','tCompURLAPI2RDFace');?>
                                        </option>
                                        <option value = "11" <?= (!empty($tUrlType) && $tUrlType == '11')? "selected":""?>>
                                            <?php echo language('company/compsettingconnect/compsettingconnect','tCompURLAPI2RDFaceRegis');?>
                                        </option>
                                    </select>
                                    <input type="hidden" id="ocmUrlConnecttype" name="ocmUrlConnecttype" value="<?php echo $tUrlType;?>">
                                    <?php
                                        }else{
                                    ?>
                                    <input type="hidden" id="ohdTypeAddUrlType" name="ohdTypeAddUrlType" value="0">
                                    <select class="selectpicker form-control" id="ocmUrlConnecttype" name="ocmUrlConnecttype" onchange="JSxCompSettingConTypeUsed('insert')">
                                        <option value = "9">
                                            <?php echo language('company/compsettingconnect/compsettingconnect','tCompURLAPI2CNMember');?>
                                        </option>
                                        <option value = "10">
                                            <?php echo language('company/compsettingconnect/compsettingconnect','tCompURLAPI2RDFace');?>
                                        </option>
                                        <option value = "11">
                                            <?php echo language('company/compsettingconnect/compsettingconnect','tCompURLAPI2RDFaceRegis');?>
                                        </option>
                                    </select>

                                    <?php 
                                        } 
                                    ?>
                                </div>  

                                <!-- เซิฟเวอร์ URL/IP -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><span style="color:red">*</span><?=language('company/compsettingconnect/compsettingconnect','tCompSettingServerIp')?></label>
                                    <input type="text"
                                        id="oetCompServerip"  
                                        name="oetCompServerip" 
                                        value="<?php echo $tUrlAddress;?>"
                                        data-validate-required="<?php echo language('company/compsettingconnect/compsettingconnect','tValiSettingServerIp')?>"
                                        placeholder="<?php echo language('company/compsettingconnect/compsettingconnect','tCompSettingServerIp')?>"
                                    >
                                    <input type="hidden" name="ohdKeyUrl" id="ohdKeyUrl"  value="<?php echo $tUrlAddress;?>">
                                    <input type="hidden" name="ohdurltype" id="ohdurltype" value="<?php echo $tUrlType;?>">
                                </div>
                                
                                <!-- Port สำหรับการเชื่อมต่อ -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?=language('company/compsettingconnect/compsettingconnect','tCompSettingPortConnect')?></label>
                                    <input type="text"  
                                        id="oetCompPortConnect"  
                                        name="oetCompPortConnect" 
                                        value="<?php echo $tUrlPort;?>"
                                        placeholder="<?php echo language('company/compsettingconnect/compsettingconnect','tCompSettingPortConnect')?>"
                                    >
                                </div>

                                <!-- URL Key -->
                                <div class="form-group">
                                    <label class="xCNLabelFrm"><?=language('company/compsettingconnect/compsettingconnect','tCompSettingUrlKey')?></label>
                                    <input type="text"  
                                        id="oetCompUrlKey"  
                                        name="oetCompUrlKey" 
                                        value="<?php echo $tUrlKey;?>"
                                        placeholder="<?php echo language('company/compsettingconnect/compsettingconnect','tCompSettingUrlKey')?>"
                                    >
                                </div>
                            </div>   


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
      $tCompCode  =  $aCompCodeSetAuthen['tCompCode']; 
    ?>
    <input type="hidden" id="ohdCompCode" name="ohdCompCode" value="<?php echo $tCompCode;?>">
    <input type="hidden" id="ohdValidateDuplicate" name="ohdValidateDuplicate" value="0"><!-- 0 คือ ไม่เกิด validate  และ 1 เกิด validate -->

</form>



<?php include "script/jCompSettingConnectMain.php"; ?>
<link rel="stylesheet" href="<?php echo base_url('application/modules/common/assets/css/localcss/ada.component.css')?>">
<script src="<?php echo base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?php echo base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>