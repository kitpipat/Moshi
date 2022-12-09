<?php
    $tBchCodeSelectClass = "col-lg-3 col-sm-3 col-md-3 col-xs-3";
    $tShpCodeSelectClass = "col-lg-3 col-sm-3 col-md-3 col-xs-3";
    $tWahCodeSelectClass = "col-lg-3 col-sm-3 col-md-3 col-xs-3";
    $tPdtCodeSelectClass = "col-lg-3 col-sm-3 col-md-3 col-xs-3";
    if(!$bBrowseShpEnabled){
        $tBchCodeSelectClass = "col-lg-4 col-sm-4 col-md-4 col-xs-4";
        $tShpCodeSelectClass = "";
        $tWahCodeSelectClass = "col-lg-4 col-sm-4 col-md-4 col-xs-4";
        $tPdtCodeSelectClass = "col-lg-4 col-sm-4 col-md-4 col-xs-4";
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
                    <div class="col-xs-8 col-sm-8">
                        <!-- Browse สาขา -->
                        <div class="<?= $tBchCodeSelectClass ?>">
                            <div class="form-group">
                                <div class="input-group">
                                        <input type='text' class='form-control xCNHide xWRptAllInput' id='oetMmtBchStaSelectAll' name='oetMmtBchStaSelectAll'>
                                        <input type='text' class='form-control xCNHide xWRptAllInput' id='oetMmtBchCodeSelect'   name='oetMmtBchCodeSelect'>
                                        <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetMmtBchNameSelect' name='oetMmtBchNameSelect' placeholder="<?= language('movement/movement/movement','tMMTListBanch')?>" autocomplete="off" readonly>
                                        <span class="input-group-btn">
                                            <button id="obtMmtMultiBrowseBranch" type="button" class="btn xCNBtnDateTime">
                                            <img  src="<?=base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- End Browse สาขา -->

                        <!-- Browse ร้านค้า -->
                        <div class="<?= $tShpCodeSelectClass ?> <?= !$bBrowseShpEnabled ? 'xCNHide' : ''; ?>">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type='text' class='form-control xCNHide xWRptAllInput' id='oetMmtShpStaSelectAll' name='oetMmtShpStaSelectAll'>
                                    <input type='text' class='form-control xCNHide xWRptAllInput' id='oetMmtShpCodeSelect'   name='oetMmtShpCodeSelect'>
                                    <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetMmtShpNameSelect' name='oetMmtShpNameSelect' placeholder="<?= language('movement/movement/movement','tMMTListShop')?>" autocomplete="off" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtMmtMultiBrowseShop" type="button" class="btn xCNBtnDateTime">
                                            <img  src="<?=base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- End Browse ร้านค้า -->

                        <!-- Browse คลังสินค้า -->
                        <div class="<?= $tWahCodeSelectClass ?>">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type='text' class='form-control xCNHide xWRptAllInput' id='oetMmtWahStaSelectAll' name='oetMmtWahStaSelectAll'>
                                    <input type='text' class='form-control xCNHide xWRptAllInput' id='oetMmtWahCodeSelect'   name='oetMmtWahCodeSelect'>
                                    <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetMmtWahNameSelect' name='oetMmtWahNameSelect' placeholder="<?= language('movement/movement/movement','tMMTListWaHouse')?>" autocomplete="off" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtMmtMultiBrowseWaHouse" type="button" class="btn xCNBtnDateTime">
                                            <img  src="<?=base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- End Browse คลังสินค้า -->

                        <!-- Browse สินค้า -->
                        <div class="<?= $tPdtCodeSelectClass ?>">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type='text' class='form-control xCNHide xWRptAllInput' id='oetMmtPdtStaSelectAll' name='oetMmtPdtStaSelectAll'>
                                    <input type='text' class='form-control xCNHide xWRptAllInput' id='oetMmtPdtCodeSelect'   name='oetMmtPdtCodeSelect'>
                                    <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetMmtPdtNameSelect' name='oetMmtPdtNameSelect' placeholder="<?= language('movement/movement/movement','tMMTListProduct')?>" autocomplete="off" readonly>
                                    <span class="input-group-btn">
                                        <button id="obtMmtMultiBrowseProduct" type="button" class="btn xCNBtnDateTime">
                                            <img  src="<?=base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- End Browse สินค้า -->
                    </div>
                    <!-- End กลุ่ม Browse ข้อมูล -->

                    <div class="col-xs-4 col-sm-4">
                        <!-- Browse วันที่ -->
                        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetMmtDateStart" name="oetMmtDateStart" placeholder="<?= language('movement/movement/movement','tMMTListDate')?>" autocomplete="off">
                                    <span class="input-group-btn">
                                        <button id="obtMmtBrowseDateStart" type="button" class="btn xCNBtnDateTime">
                                            <img  src="<?=base_url().'application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- End Browse วันที่ -->

                        <!-- Browse ถึงวันที่ -->
                        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control xCNDatePicker xCNInputMaskDate" id="oetMmtDateTo" name="oetMmtDateTo" placeholder="<?= language('movement/movement/movement','tMMTListDateTo')?>" autocomplete="off">
                                    <span class="input-group-btn">
                                        <button id="obtMmtBrowseDateTo" type="button" class="btn xCNBtnDateTime">
                                            <img  src="<?=base_url().'application/modules/common/assets/images/icons/icons8-Calendar-100.png'?>">
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- End Browse ถึงวันที่ -->
                        
                        <!-- ปุ่มกรองข้อมูล -->
                        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                            <div class="form-group">
                                <div id="odvBtnMovement" class="text-center">
                                    <button  type="button" id="obtSubmitMmt" class="btn xCNBTNPrimery" onclick="JSvMevementSearchData()"><?= language('movement/movement/movement','tMMTListSearch')?>	</button>	
                                </div>
                            </div>
                        </div>
                        <!-- End ปุ่มกรองข้อมูล -->

                    </div>

                </div>
            </div>

        </div>
        </div>
        <!-- แสดงข้อมูล ความเคลื่อนไหวสินค้า -->
        <div class="col-xs-12 col-md-12 col-lg-12">
            <section id="odvContentMovement"></section>
        </div>
        </div>
    </div>
</div>


<?php include "script/jMovementAdd.php"; ?>
<script src="<?= base_url('application/modules/common/assets/js/jquery.mask.js')?>"></script>
<script src="<?= base_url('application/modules/common/assets/src/jFormValidate.js')?>"></script>