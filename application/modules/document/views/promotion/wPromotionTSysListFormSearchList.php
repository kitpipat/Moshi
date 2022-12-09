<div class="panel-heading">
	<div class="row">
        <div class="col-xs-8 col-md-4 col-lg-4">
            <div class="form-group">
                <label class="xCNLabelFrm"><?php echo language('common/main','tSearchNew')?></label>
                <div class="input-group">
                    <input type="text" class="form-control xCNInputWithoutSingleQuote" id="oetSearchAll" name="oetSearchAll" onkeypress="Javascript:if(event.keyCode==13) JSvCallPageTSysPmtDataTable()" autocomplete="off"  placeholder="<?php echo language('common/main','tPlaceholder'); ?>">
                    <span class="input-group-btn">
                        <button class="btn xCNBtnSearch" type="button" onclick="JSvCallPageTSysPmtDataTable()">
                            <img class="xCNIconBrowse" src="<?php echo base_url().'/application/assets/icons/search-24.png'?>">
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-xs-4 col-md-8 col-lg-8 text-right" style="margin-top:34px;">
            <button id="obtPmtAdd" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" onclick="JSvCallPagePromotionAdd()"><?= language('common/main', 'tAdd')?></button>
        </div>
    </div>
</div>

<div class="panel-body">
	<div id="odvContentPromotionTSysListData"></div>
</div>



<!-- 
<div class="panel-body" style="background: #FFFFFF;
    border-bottom: 1px solid #e3e3e3;
    padding-bottom: 0px !important;
    width: inherit;
    z-index: 20;
    margin-left: 0px;
    margin-top: - 25px;">
    <section id="ostSearchPromotion">
            <div class="row">
                <div class="col-xs-2 col-md-2">
                    <label class="xCNTextDetail1" style="margin-top: 15px;"><?= language('document/promotion/promotion','tPMTSelPmtType')?></label>
                </div>
                <div class="col-xs-4 col-md-4">
                    <div class="form-group">
                        <div class="wrap-input100">
                            <span class="label-input100"><?= language('common/main','tSearch')?></span>
                            <input class="input100" type="text" id="oetSearchAll" name="oetSearchAll" onkeyup="Javascript:if(event.keyCode==13) JSvCallPageTSysPmtDataTable()" autocomplete="off">
                            <span class="focus-input100"></span>
                            <img onclick="JSvCallPageTSysPmtDataTable()" class="xCNIconBrowse" src="<?= base_url().'/application/assets/icons/search-24.png'?>">														
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-6 xCNBtngroup text-right">
                    <button id="obtPmtAdd" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button" onclick="JSvCallPagePromotionAdd()"><?= language('common/main', 'tAdd')?></button>
                </div>
            </div>
    </section>
</div>

<div id="odvContentPromotionTSysListData"></div> -->

