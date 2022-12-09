<div id="odvTabAddress" class="tab-pane fade">
    <form class="contact100-form validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" autocorrect="off" autocapitalize="off" autocomplete="off" id="ofmAddCustomerAddress">
        <button style="display:none" type="submit" id="obtSave_oliCstAddress" onclick="JSnCSTAddEditCustomerAddress('<?php echo $tAddressRoute; ?>')"></button>
        <button style="display:none" type="submit" id="obtCancel_oliCstAddress" onclick="JSnCSTCancelCustomerAddress()"></button>
        <input type="hidden" name="ohdCstCode" value="<?php echo $tCstCode; ?>">
        <input type="hidden" name="ohdCstAddressMode" value="2">
        <input type="hidden" name="ohdCstAddSeqNo" id="ohdCstAddSeqNo" value="<?php echo $rtAddSeqNo; ?>">
        <input type="hidden" name="ohdCstAddRefNo" id="ohdCstAddRefNo" value="<?php echo $rtAddRefNo; ?>">
        <input type="hidden" id="ohdCstAddAreaCode" name="ohdCstAddAreaCode" value="n/a">
        <input type="hidden" id="oetCstAddZoneCode" name="oetCstAddZoneCode" value="n/a">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="form-group">
                    <div class="validate-input-">
                        <textarea
                            class="input100"
                            rows="4" 
                            cols="50" 
                            id="otaCstAddDist1" 
                            name="otaCstAddDist1"
                            placeholder="<?= language('customer/customer/customer', 'tCSTAddDist1')?>"><?php echo $tCstAddDesc1; ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="validate-input">
                        <textarea 
                            class="input100" 
                            rows="4" 
                            cols="50"
                            id="otaCstAddDist2" 
                            name="otaCstAddDist2" 
                            placeholder="<?= language('customer/customer/customer', 'tCSTAddDist2')?>"><?php echo $tCstAddDesc2; ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="validate-input" data-validate="Please Insert Website">
                        <label class="xCNLabelFrm"><?= language('customer/customer/customer','tCSTAddWebsite')?></label>
                        <input type="text" class="input100" maxlength="100" id="oetCstAddWebsite" name="oetCstAddWebsite" value="<?= $tCstEmail ?>">
                    </div>
                </div>
            </div>

            <div class="col-xl-12 col-lg-12">
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
