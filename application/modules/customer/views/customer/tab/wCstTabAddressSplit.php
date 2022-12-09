<div id="odvTabAddress" class="tab-pane fade">
    <form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddCustomerAddress">
        <button style="display:none" type="submit" id="obtSave_oliCstAddress" onclick="JSnCSTAddEditCustomerAddress('<?php echo $tAddressRoute; ?>')"></button>
        <button style="display:none" type="submit" id="obtCancel_oliCstAddress" onclick="JSnCSTCancelCustomerAddress()"></button>
        <input type="hidden" name="ohdCstCode" value="<?php echo $tCstCode; ?>">
        <input type="hidden" name="ohdCstAddressMode" value="1">
        <input type="hidden" name="ohdCstAddSeqNo" id="ohdCstAddSeqNo" value="<?php echo $rtAddSeqNo; ?>">
        <input type="hidden" name="ohdCstAddRefNo" id="ohdCstAddRefNo" value="<?php echo $rtAddRefNo; ?>">
        <div class="row">

            <div class="col-xl-6 col-lg-6">
                <div class="form-group">
                    <div class="validate-input" data-validate="Please Insert Address Number">
                        <label class="xCNLabelFrm"><?php echo language('customer/customer/customer','tCSTAddNo'); ?></label>
                        <input type="text" class="input100" maxlength="100" id="oetCstAddNo" name="oetCstAddNo" value="<?php echo $tCstAddV1No; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="validate-input" data-validate="Please Insert Soi">
                        <label class="xCNLabelFrm"><?php echo language('customer/customer/customer','tCSTAddSoi'); ?></label>
                        <input type="text" class="input100" maxlength="100" id="oetCstAddSoi" name="oetCstAddSoi" value="<?php echo $tCstAddV1Soi; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="validate-input" data-validate="Please Insert Village">
                        <label class="xCNLabelFrm"><?php echo language('customer/customer/customer','tCSTAddVillage'); ?></label>
                        <input type="text" class="input100 xCNInputWithoutSpc" maxlength="100" id="oetCstAddVillage" name="oetCstAddVillage" value="<?php echo $tCstAddV1Village; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="validate-input" data-validate="Please Insert Road">
                        <label class="xCNLabelFrm"><?php echo language('customer/customer/customer','tCSTAddRoad'); ?></label>
                        <input type="text" class="input100 xCNInputWithoutSpc" maxlength="100" id="oetCstAddRoad" name="oetCstAddRoad" value="<?php echo $tCstAddV1Road; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="validate-input" data-validate="Please Insert Country">
                        <label class="xCNLabelFrm"><?php echo language('customer/customer/customer','tCSTAddCountry'); ?></label>
                        <input type="text" class="input100 xCNInputWithoutSpc" maxlength="100" id="oetCstAddCountry" name="oetCstAddCountry" value="<?php echo $tCstAddCountry; ?>">
                    </div>
                </div>
                <?php if(false) : ?>
                <div class="form-group">
                    <div class="validate-input" data-validate="Please Enter">
                        <label class="xCNLabelFrm"><?= language('customer/customer/customer','tCSTAddCountry')?></label>
                        <input type="text" class="form-control xCNHide" id="oetCstAddCountryCode" name="oetCstAddCountryCode" maxlength="5" value="<?=$tCstAddCountryCode?>">
                        <input class="input100 xWPointerEventNone" type="text" id="oetCstAddCountryName" name="oetCstAddCountryName" placeholder="###" value="<?=$tCstAddCountryName?>" readonly>
                        <img id="oimCstBrowseAddCountry" class="xCNIconBrowse" src="<?= base_url().'application/modules/common/assets/images/icons/find-24.png'?>">
                    </div>
                </div>
                <?php endif; ?>
                <input type="hidden" id="ohdProvinceRef" value="">
                <input type="hidden" id="ohdDistrictRef" value="">
                <input type="hidden" id="ohdSubdistrictRef" value="">
                <div class="form-group">
                    <div class="validate-input" data-validate="Please Enter">
                        <label class="xCNLabelFrm"><?php echo language('customer/customer/customer','tCSTAddArea'); ?></label>
                        <input type="hidden" id="ohdCstAddAreaCode" name="ohdCstAddAreaCode" value="<?php echo $tCstAddAreCode; ?>">
                        <input type="text" class="form-control xCNHide" id="oetCstAddZoneCode" name="oetCstAddZoneCode" maxlength="5" value="<?php echo $tCstAddZoneCode; ?>">
                        <input class="input100 xWPointerEventNone" type="text" id="oetCstAddZoneName" name="oetCstAddZoneName" placeholder="###" value="<?php echo $tCstAddZoneName; ?>" readonly>
                        <img id="oimCstBrowseAddZone" class="xCNIconBrowse" src="<?php echo base_url().'application/modules/common/assets/images/icons/find-24.png'; ?>">
                    </div>
                </div>
                <div class="form-group" id="odvAddPvnContainer">
                    <div class="validate-input" data-validate="Please Enter">
                        <label class="xCNLabelFrm"><?php echo language('customer/customer/customer','tCSTAddPvn'); ?></label>
                        <input 
                            type="text" 
                            class="form-control xCNHide" 
                            id="oetCstAddPvnCode" 
                            name="oetCstAddPvnCode" 
                            maxlength="5"
                            onchange="JSxCSTAddChangeLocation(this, event, 'province')"
                            value="<?php echo $tCstAddProvinceCode; ?>">
                        <input class="input100 xWPointerEventNone" type="text" id="oetCstAddPvnName" name="oetCstAddPvnName" placeholder="###" value="<?php echo $tCstAddProvinceName; ?>" readonly>
                        <img id="oimCstBrowseAddPvn" class="xCNIconBrowse" src="<?php echo base_url().'application/modules/common/assets/images/icons/find-24.png'; ?>">
                    </div>
                </div>
                <div class="form-group" id="odvAddDstContainer">
                    <div class="validate-input" data-validate="Please Enter">
                        <label class="xCNLabelFrm"><?php echo language('customer/customer/customer','tCSTAddDst'); ?></label>
                        <input 
                            type="text" 
                            class="form-control xCNHide" 
                            id="oetCstAddDstCode" 
                            name="oetCstAddDstCode" 
                            onchange="JSxCSTAddChangeLocation(this, event, 'district')"
                            maxlength="5" 
                            value="<?php echo $tCstAddDistrictCode; ?>">
                        <input class="input100 xWPointerEventNone" type="text" id="oetCstAddDstName" name="oetCstAddDstName" placeholder="###" value="<?php echo $tCstAddDistrictName; ?>" readonly>
                        <img id="oimCstBrowseAddDst" class="xCNIconBrowse" src="<?php echo base_url().'application/modules/common/assets/images/icons/find-24.png'; ?>">
                    </div>
                </div>
                <div class="form-group" id="odvAddSubDistContainer">
                    <div class="validate-input" data-validate="Please Enter">
                        <label class="xCNLabelFrm"><?php echo language('customer/customer/customer','tCSTAddSubDist'); ?></label>
                        <input 
                            type="text" 
                            class="form-control xCNHide" 
                            id="oetCstAddSubDistCode" 
                            name="oetCstAddSubDistCode"
                            onchange="JSxCSTAddChangeLocation(this, event, 'subdistrict')"
                            maxlength="5" 
                            value="<?php echo $tCstAddSubDistrictCode; ?>">
                        <input class="input100 xWPointerEventNone" type="text" id="oetCstAddSubDistName" name="oetCstAddSubDistName" placeholder="###" value="<?php echo $tCstAddSubDistrictName; ?>" readonly>
                        <img id="oimCstBrowseAddSubDist" class="xCNIconBrowse" src="<?php echo base_url().'application/modules/common/assets/images/icons/find-24.png'; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="validate-input" data-validate="Please Insert Post Code">
                        <label class="xCNLabelFrm"><?php echo language('customer/customer/customer','tCSTAddPostCode'); ?></label>
                        <input type="text" class="input100" maxlength="100" id="oetCstAddPostCode" name="oetCstAddPostCode" value="<?php echo $tCstAddPostCode; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="validate-input" data-validate="Please Insert Website">
                        <label class="xCNLabelFrm"><?php echo language('customer/customer/customer','tCSTAddWebsite'); ?></label>
                        <input type="text" class="input100" maxlength="100" id="oetCstAddWebsite" name="oetCstAddWebsite" value="<?php echo $tCstAddWebsite; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="validate-input">
                                <label class="xCNLabelFrm"><?php echo language('customer/customer/customer','tCSTRmk'); ?></label>
                                <textarea maxlength="100" rows="4" id="otaCstAddRemark" name="otaCstAddRemark"><?php echo $tCstAddRmk; ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6">
                <div class="form-group">
                    <label class="xCNLabelFrm"><?php echo language('customer/customer/customer','tCSTLocation'); ?></label>
                    <input type="hidden" id="ohdCstAddLongitude" name="ohdCstAddLongitude">
                    <input type="hidden" id="ohdCstAddLatitude" name="ohdCstAddLatitude">
                    <div id="odvCstAddMapEdit" class="xCNMapShow"></div>                
                </div>
            </div>

        </div>
    </form>
</div>
