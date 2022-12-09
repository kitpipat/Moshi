<?php


if(isset($aResult) && $aResult['rtCode'] == '1'){
    // echo"<pre>";
    // print_r($aResult['raItems']);
    // echo"</pre>";
    // $tUsrImage      = $aResult['raItems']['rtUsrImage'];
    $tUsrCode       = $aResult['raItems']['rtUsrCode'];
    $tUsrTel        = $aResult['raItems']['rtUsrTel'];
    $tUsrEmail      = $aResult['raItems']['rtUsrEmail'];
    $tUsrName       = $aResult['raItems']['rtUsrName'];
    $tUsrRmk        = $aResult['raItems']['rtUsrRmk'];
    $tDptCode       = $aResult['raItems']['rtDptCode'];
    $tDptName       = $aResult['raItems']['rtDptName'];
    $tBchCode       = $aResult['raItems']['rtBchCode'];
    $tBchName       = $aResult['raItems']['rtBchName'];
    $tShpCode       = $aResult['raItems']['rtShpCode'];
    $tShpName       = $aResult['raItems']['rtShpName'];
    $tPwd           = $aResult['raItems']['rtUsrPwd'];
    $tPwdNew        = '******';
    $dDateStart     = ($aResult['raItems']['rtUsrStartDate']!="")? date("Y-m-d", strtotime($aResult['raItems']['rtUsrStartDate'])):"";
    $dDateEnd       = ($aResult['raItems']['rtUsrEndDate']!="")? date("Y-m-d",strtotime($aResult['raItems']['rtUsrEndDate'])):"";
    $tRoute         = "userEventEdit";
    $dGetDataNow    = "";
    $dGetDataFuture = "";
}else{
    $tUsrImage      = "";
    $tUsrCode       = "";
    $tUsrTel        = "";
    $tUsrEmail      = "";
    $tUsrName       = "";
    $tUsrRmk        = "";
    $tDptCode       = "";
    $tDptName       = "";
    $tBchCode       = "";
    $tBchName       = "";
    $tShpCode       = "";
    $tShpName       = "";
    $dDateStart     = "";
    $dDateEnd       = "";
    $tRoute         = "userEventAdd";

    $tPwd           = '';
    $tPwdNew        = '';

    $dGetDataNow    = $aResult['dGetDataNow'];
    $dGetDataFuture = $aResult['dGetDataFuture'];
}

?>

<?php

    $tRolCode   = "";
    $tRolName   = "";

    if(isset($aResActRole['raItems']) && !empty($aResActRole['raItems'])){
        foreach ($aResActRole['raItems'] AS $key => $aValue) {
            $tRolName   .= $aValue['FTRolName'];
            $tRolName .= ',';
        }
    }

    if(isset($aResActRole['raItems']) && !empty($aResActRole['raItems'])){
        foreach ($aResActRole['raItems'] AS $key => $aValue) {
            $tRolCode   .= $aValue['FTRolCode'];
            $tRolCode .= ',';
        }
    }

?>

<div class="panel panel-headline">
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="custom-tabs-line tabs-line-bottom left-aligned">        
                    <ul class="nav" role="tablist"> 
                        <!-- ข้อมูลทั่วไป -->   
                        <li id="oliUsrloginDetail" class="xWMenu active" data-menutype="DT">
                            <a role="tab" data-toggle="tab" data-target="#odvUsrloginContentInfoDT" aria-expanded="true"><?php echo language('authen/user/user','tTabNormal')?></a>
                        </li>

                        <!---ข้อมูลล็อกอิน-->
                        <!-- Witsarut Add 10/08/2019 14: 00 -->
                                <!-- ตรวจสอบโหมดการเรียก Page
                                    ถ้าเป็นโหมดเพิ่ม ($aResult['rtCode'] == '99') ให้ปิด Tab ข้อมูลล็อกอิน 
                                    ถ้าเป็นโหมดแก้ไข ($aResult['rtCode'] = 1) ให้เปิด Tab ข้อมูลล็อกอิน 
                                -->
                        <?php
                            if($aResult['rtCode'] == '99'){
                        ?>
                            <li id="oliUsrlogin" class="xWMenu xWSubTab disabled" data-menutype="Log">
                                <a role="tab"   aria-expanded="true"><?php echo language('authen/user/user','tDetailLogin')?></a>
                            </li>
                        <?php }else{ ?>
                            <li id="oliUsrlogin" class="xWMenu xWSubTab" data-menutype="Log" onclick="JSxUsrloginGetContent();">
                                <a role="tab" data-toggle="tab" data-target="#odvUsrloginData" aria-expanded="true"><?php echo language('authen/user/user','tDetailLogin')?></a>
                            </li>
                        <?php } ?>
                    </ul>    
                </div>
            </div>
        </div>
    
        <div id="odvPdtRowContentMenu" class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <!-- Tab Content Detail -->
                    <div class="tab-content">
                        <div id="odvUsrloginContentInfoDT" class="tab-pane fade active in">
                            <form action="javascript:void(0)" method="post" enctype="multipart/form-data" autocomplete="off" id="ofmAddEditUser">
                                <button class="xCNHide" id="obtAddEditUser" type="submit" onclick="JSnAddEditUser('<?=$tRoute?>')"></button>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="row">
                                            <div class="col-xs-4 col-sm-4">
                                                <!-- รูปภาพ -->
                                                <div class="form-group">
                                                    <div id="odvUserImage">
                                                        <?php
                                                        if(isset($tImgObj) && !empty($tImgObj)){
                                                            $tFullPatch = './application/modules/'.$tImgObj;
                                                            if (file_exists($tFullPatch)){
                                                                $tPatchImg = base_url().'/application/modules/'.$tImgObj;
                                                            }else{
                                                                $tPatchImg = base_url().'application/modules/common/assets/images/200x200.png';
                                                            }
                                                        }else{
                                                            $tPatchImg = base_url().'application/modules/common/assets/images/200x200.png';
                                                        }
                                                        ?>      
                                                        <img id="oimImgMasteruser" class="img-responsive xCNImgCenter" src="<?php echo @$tPatchImg;?>">
                                                    </div>
                                                    <div class="xCNUplodeImage">
                                                        <input type="text" class="xCNHide" id="oetImgInputuserOld"  name="oetImgInputuserOld"   value="<?php echo @$tImgName;?>">
                                                        <input type="text" class="xCNHide" id="oetImgInputuser"     name="oetImgInputuser"      value="<?php echo @$tImgName;?>">
                                                        <button type="button" class="btn xCNBTNDefult" onclick="JSvImageCallTempNEW('','','user')">
                                                            <i class="fa fa-picture-o xCNImgButton"></i> <?php echo language('common/main/main','tSelectPic')?>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- end รูปภาพ -->
                                            </div>
                                            <div class="col-xs-8 col-sm-8">
                                                <div class="row">

                                                <!-- รหัสผู้ใช้ -->
                                                <div class="col-xs-8">
                                                    <label class="xCNLabelFrm"><span style = "color:red">*</span><?= language('authen/user/user','tUSRCode')?></label>
                                                    <div class="form-group" id="odvUserAutoGenCode">
                                                        <div class="validate-input">
                                                            <label class="fancy-checkbox">
                                                                <input type="checkbox" id="ocbUserAutoGenCode" name="ocbUserAutoGenCode" checked="true" value="1">
                                                                <span> <?php echo language('common/main/main', 'tGenerateAuto'); ?></span>
                                                            </label>
                                                        </div>
                                                    </div>                                    

                                                    <div class="form-group" id="odvUserCodeForm">
                                                    <input type="hidden" id="ohdCheckDuplicateUsrCode" name="ohdCheckDuplicateUsrCode" value="1">
                                                        <div class="validate-input">
                                                            <input 
                                                                type="text" 
                                                                class="form-control xCNGenarateCodeTextInputValidate" 
                                                                maxlength="5" 
                                                                id="oetUsrCode" 
                                                                name="oetUsrCode"
                                                                placeholder="<?= language('authen/user/user','tUSRCode')?>"
                                                                value="<?php echo $tUsrCode; ?>" 
                                                                data-is-created="<?php echo $tUsrCode; ?>"
                                                                data-validate-required = "<?= language('common/main/main','tValidCode')?><?= language('authen/user/user','tUSRCode')?>"
                                                                data-validate-dublicateCode = "<?= language('common/main/main','tValidCode')?><?= language('authen/user/user','tUSRCode')?>">
                                                        </div>
                                                    </div>
                                
                                                </div>
                                                <!-- end รหัสผู้ใช้ -->

                                                <!-- หน่วยงาน -->
                                                <div class="col-xs-8">
                                                    <div class="form-group">
                                                        <label class="xCNLabelFrm"><?php echo language('authen/user/user','tUSRDepart')?></label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control xCNHide" id="oetDepartCode" name="oetDepartCode" value="<?php echo $tDptCode?>">
                                                                <input type="text" class="form-control xWPointerEventNone" id="oetDepartName" name="oetDepartName" 
                                                                placeholder="<?php echo language('authen/user/user','tUSRDepart')?>"
                                                                value="<?php echo $tDptName?>" readonly>
                                                                <span class="input-group-btn">
                                                                <button id="oimBrowseDepart" type="button" class="btn xCNBtnBrowseAddOn">
                                                                    <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end หน่วยงาน -->

                                                <!-- กลุ่มสิทธิ์ -->
                                                <div class="col-xs-8">
                                                    <div class="form-group">
                                                        <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('authen/user/user','tUSRRole')?></label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control xCNHide" id="oetRoleCode" name="oetRoleCode" value="<?php echo $tRolCode?>" data-validate-required = "<?php echo  language('authen/user/user','tCRDValiUser');?>">
                                                            <input type="text" class="form-control xWPointerEventNone" id="oetRoleName" name="oetRoleName"
                                                            placeholder="<?php echo language('authen/user/user','tUSRRoleGroup')?>"
                                                            value="<?php echo $tRolName?>" 
                                                            data-validate-required = "<?php echo  language('authen/user/user','tCRDValiUser');?>"
                                                            placeholder="<?= language('authen/user/user','tUSRRole')?>"
                                                            readonly>
                                                        <span class="input-group-btn">
                                                            <button id="oimBrowseRole" type="button" class="btn xCNBtnBrowseAddOn">
                                                                <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                              
                                                <div style="white-space:nowrap;width:100%; overflow-x:auto;">
                                                    <div  id="odvUsrRoleShow" style="padding:0px;">
                                                        <?php if(isset($aResActRole['raItems']) && !empty($aResActRole['raItems'])){ ?>
                                                        <?php foreach ($aResActRole['raItems'] AS $key => $aValue) { ?>
                                                            <span class="label label-info m-r-5"><?=$aValue['FTRolName'];?></span>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                    
                                                </div>
                                                <!-- End กลุ่มสิทธิ์ -->
                                                
                                                <!-- ชื่อผู้ใช้ -->
                                                <div class="col-xs-8">
                                                    <div class="form-group">
                                                        <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('authen/user/user','tUSRName')?></label>
                                                            <input class="form-control" maxlength="50" type="text" id="oetUsrName" name="oetUsrName" 
                                                            placeholder="<?= language('authen/user/user','tUSRName')?>"
                                                            value="<?=$tUsrName?>"
                                                            data-validate-required = "<?= language('common/main/main','tUSRVldUser')?>"
                                                            data-validate-dublicateCode = "<?= language('authen/user/user','tUSRVldUser')?>
                                                            ">
                                                    </div>
                                                </div>
                                                <!-- End ชื่อผู้ใช้ -->
                                                        
                                                <!-- อีเมล์ -->
                                                <div class="col-xs-8">
                                                    <div class="form-group">
                                                        <label class="xCNLabelFrm"><span style="color:red">*</span><?= language('authen/user/user','tUSREmail')?></label>
                                                            <input class="form-control" maxlength="50" type="text" id="oetUsrEmail" name="oetUsrEmail"
                                                            placeholder="<?= language('authen/user/user','tUSREmail')?>" 
                                                            data-validate-required ="<?php echo language('authen/user/user','tUSRVldEmail')?>"
                                                            value="<?=$tUsrEmail?>">
                                                    </div>
                                                </div>
                                                <!-- end อีเมล์ -->

                                                <!-- เบอร์โทร -->
                                                <div class="col-xs-8">
                                                    <div class="form-group">
                                                            <label class="xCNLabelFrm"><?= language('authen/user/user','tUSRTel')?></label>
                                                            <input class="form-control" maxlength="50" type="text" id="oetUsrTel" name="oetUsrTel"
                                                            value="<?=$tUsrTel?>"
                                                            placeholder="<?= language('authen/user/user','tUSRTel')?>"
                                                            >
                                                    </div>
                                                </div>
                                                <!-- end เบอร์โทร -->
                                                
                                                <!-- สาขา -->
                                                <div class="col-xs-8">           
                                                    <div class="form-group">
                                                            <label class="xCNLabelFrm"><?php echo language('authen/user/user','tUSRBranch')?></label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control xCNHide" id="oetBranchCode" name="oetBranchCode" value="<?php echo $tBchCode?>">
                                                                <input type="text" class="form-control xWPointerEventNone" id="oetBranchName" name="oetBranchName" 
                                                                placeholder="<?php echo language('authen/user/user','tUSRBranch')?>"
                                                                value="<?php echo $tBchName?>" readonly>
                                                                <span class="input-group-btn">
                                                                    <button id="oimBrowseBranch" type="button" class="btn xCNBtnBrowseAddOn">
                                                                        <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                                                    </button>
                                                                </span>
                                                        </div>
                                                    </div>
                                                </div>  
                                                <!-- end สาขา -->  
                                                
                                                <!-- ร้านค้า -->
                                                <div class="col-xs-8">      
                                                    <div class="form-group">
                                                        <label class="xCNLabelFrm"><?php echo language('authen/user/user','tUSRShop')?></label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control xCNHide" id="oetShopCode" name="oetShopCode" value="<?php echo $tShpCode?>">
                                                            <input type="text" class="form-control xWPointerEventNone" id="oetShopName" name="oetShopName" 
                                                                placeholder="<?php echo language('authen/user/user','tUSRShop')?>"
                                                                value="<?php echo $tShpName?>" readonly>
                                                            <span class="input-group-btn">
                                                                <button id="oimBrowseShop" type="button" class="btn xCNBtnBrowseAddOn">
                                                                    <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end ร้านค้า -->  

                                                <!-- วันที่เริ่มดำเนินการ // วันที่สิ้นสุดดำเนินการ -->
                                                <div class="col-xs-8"> 
                                                    <div class="row">
                                                        <div class="col-xs-6"> 
                                                            <div class="form-group">
                                                                <label class="xCNLabelFrm"><?= language('authen/user/user','tUSRDateStart')?></label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control text-center xCNDatePicker xCNInputMaskDate" id="oetUsrDateStart" name="oetUsrDateStart" value="<?php if($dDateStart != ""){ echo $dDateStart;}else{echo $dGetDataNow;}?>">
                                                                    <span class="input-group-btn">
                                                                        <button id="obtUsrDateStart" type="button" class="btn xCNBtnDateTime">
                                                                            <img src="<?php echo  base_url().'application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <div class="form-group">
                                                                <label class="xCNLabelFrm"><?= language('authen/user/user','tUSRDateStop')?></label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control text-center xCNDatePicker xCNInputMaskDate " id="oetUsrDateStop" name="oetUsrDateStop" value="<?php if($dDateEnd  != ""){ echo $dDateEnd ;}else{echo $dGetDataFuture;}?>">
                                                                    <span class="input-group-btn">
                                                                        <button id="obtUsrDateStop" type="button" class="btn xCNBtnDateTime">
                                                                            <img src="<?php echo  base_url().'application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end วันที่เริ่มดำเนินการ // วันที่สิ้นสุดดำเนินการ -->

                                                <!-- เหตุผล -->
                                                <div class="col-xs-8">
                                                    <div class="form-group">
                                                        <label class="xCNLabelFrm"><?= language('authen/user/user','tUSRRemark')?></label>
                                                        <textarea class="form-control" maxlength="100" rows="4" id="otaUsrRemark" name="otaUsrRemark"><?=$tUsrRmk?></textarea>
                                                    </div>
                                                </div>
                                                <!-- end เหตุผล -->

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div> 
                <!--  End Tab Content Detail -->
                <!-- Tab LoinData  -->
                    <div id="odvUsrloginData" class="tab-pane fade"></div>
                <!-- End Tab LoinData  -->      
            </div>   
        </div>     

<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>
<?php include 'script/jUserAdd.php'; ?>


