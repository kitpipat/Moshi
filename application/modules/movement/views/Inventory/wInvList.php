<?php 
    $bIsHQUsr = $this->session->userdata("tSesUsrLevel") == 'HQ' ? true : false;
    $tBchBrowseInputClass = 'col-lg-3 col-sm-3 col-md-3 col-xs-3';
    $tInvBrowseInputClass = 'col-lg-6 col-sm-6 col-md-6 col-xs-6';
    $tWahBrowseInputClass = 'col-lg-3 col-sm-3 col-md-3 col-xs-3';
    
    $tBchCode = '';
    $tBchName = '';
    if (!$bIsHQUsr) {
        $tBchCode       = $this->session->userdata("tSesUsrBchCom");
        $tBchName       = $this->session->userdata("tSesUsrBchNameCom");
    }
?>
<input id="ohdUsrBchCode"      type="hidden" value="<?php echo $tUsrBchCode?>">
<input id="ohdUsrShpCode"      type="hidden" value="<?php echo $tUsrShpCode?>">
<div class="panel-heading">
	<div class="row">
        <div id="odvSetionMovement">
        <div class="row">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <!-- กลุ่ม Browse ข้อมูล -->
                <div class="row">
                    <div class="col-xs-10 col-sm-10">
                        <!-- Browse สาขา -->
                        <div class="<?= $tBchBrowseInputClass ?>">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type='text' class='form-control xCNHide xWRptAllInput' id='oetInvBchStaSelectAll' name='oetInvBchStaSelectAll'>
                                    <input type='text' class='form-control xCNHide xWRptAllInput' id='oetInvBchCodeSelect'   name='oetInvBchCodeSelect' value='<?php echo !$bIsHQUsr ? $tBchCode : ''; ?>' <?php echo !$bIsHQUsr ? 'readonly' : '' ; ?>>
                                    <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetInvBchNameSelect' name='oetInvBchNameSelect' placeholder="<?= language('movement/movement/movement','tMMTListBanch')?>" autocomplete="off" readonly value='<?php echo !$bIsHQUsr ? $tBchName : ''; ?>'>
                                    <span class="input-group-btn">
                                        <button id="obtInvMultiBrowseBranch" type="button" class="btn xCNBtnDateTime" <?php echo !$bIsHQUsr ? 'disabled' : '' ; ?>>      
                                            <img  src="<?=base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- End Browse สาขา -->

                        <!-- Browse สินค้า -->
                        <div class="<?= $tInvBrowseInputClass ?>">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type='text' class='form-control xCNHide xWRptAllInput' id='oetInvPdtStaSelectAll' name='oetInvPdtStaSelectAll'>
                                    <input type='text' class='form-control xCNHide xWRptAllInput' id='oetInvPdtCodeSelect'   name='oetInvPdtCodeSelect'>
                                    <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetInvPdtNameSelect' name='oetInvPdtNameSelect' placeholder="<?= language('movement/movement/movement','tMMTListProduct')?>" autocomplete="off" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtInvMultiBrowseProduct" type="button" class="btn xCNBtnDateTime">
                                            <img  src="<?=base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- End Browse สินค้า -->

                         <!-- Browse คลังสินค้า -->
                         <div class="<?= $tWahBrowseInputClass ?>">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type='text' class='form-control xCNHide xWRptAllInput' id='oetInvWahStaSelectAll' name='oetInvWahStaSelectAll'>
                                    <input type='text' class='form-control xCNHide xWRptAllInput' id='oetInvWahCodeSelect'   name='oetInvWahCodeSelect'>
                                    <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetInvWahNameSelect' name='oetInvWahNameSelect' placeholder="<?= language('movement/movement/movement','tMMTListWaHouse')?>" autocomplete="off" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtInvMultiBrowseWaHouse" type="button" class="btn xCNBtnDateTime">
                                            <img  src="<?=base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- End Browse คลังสินค้า -->
                    </div>
                    <!-- End กลุ่ม Browse ข้อมูล -->


                    <div class="col-xs-2 col-sm-2">
                        <!-- ปุ่มกรองข้อมูล -->
                        <!-- <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4"> -->
                            <div class="form-group">
                                <div id="odvBtnMovement">
                                    <button  type="button" id="obtInvSearchSubmit" class="btn xCNBTNPrimery" _onclick="JSxInvSearchData()"><?= language('movement/movement/movement','tMMTListSearch')?>	</button>	
                                </div>
                            </div>
                        <!-- </div> -->
                        <!-- End ปุ่มกรองข้อมูล -->

                    </div>

                </div>
            </div>

        </div>
        </div>
        <!-- แสดงข้อมูล ความเคลื่อนไหวสินค้า -->
        <div class="col-xs-12 col-md-12 col-lg-12">
            <section id="odvInvContent"></section>
        </div>
        </div>
    </div>
</div>

<?php include "script/jInv.php"; ?>
<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>