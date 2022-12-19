<div class="text-right">
    <button id="obtPdtZoneConditionsAdd" class="xCNBTNPrimeryPlus" style="margin-bottom:5px;" type="button" onclick="JSvPdtZoneConditionsPageAdd()">+</button>
</div>
<table class="table xWTableListdataSpcWah" id="otbTableListdataSpcWah">
    <thead>
        <tr>

            <th nowrap="" class="text-center xCNTextBold" style="width:5%;"><?php echo language('product/product/product','tPdtZoneSeq')?></th>
            <th nowrap="" class="text-center xCNTextBold" style="width:10%;"><?php echo language('product/product/product','tPdtZoneCode')?></th>
            <th nowrap="" class="text-center xCNTextBold" style="width:10%;"><?php echo language('product/product/product','tPdtZoneName')?></th>
            <th nowrap="" class="text-center xCNTextBold" style="width:10%;"><?php echo language('product/product/product','tPdtZoneInorEx')?></th>
            <th nowrap="" class="text-center xCNTextBold" style="width:10%;" colspan="2"><?php echo language('product/product/product','tPdtZoneManage')?></th>
                      
        </tr>   
</thead>
<tbody>
<?php if($rtCode == 1 ):?>
    <?php foreach($raItems AS $aValue){ ?>
        <?php
            if($aValue['FTPdtStaInOrEx'] == '1') {
                $tPdtZoneInorEx = language('product/product/product','tPdtZoneInclude');
            }else{
                $tPdtZoneInorEx = language('product/product/product','tPdtZoneExclude');
            }
        ?>
        <tr>
           <td class="text-center"><?php echo $aValue['FNRowID'];?></td>
           <td class="text-left"><?php echo $aValue['FTZneCode'];?></td>
           <td class="text-left"><?php echo $aValue['FTZneChainName'];?></td>
           <td class="text-left"><?php echo $tPdtZoneInorEx;?></td>
           <td class="text-center"><img class="xCNIconTable" src="<?= base_url().'/application/modules/common/assets/images/icons/delete.png'?>" onClick="JSoPdtZoneConditionsDelete('<?php echo $aValue['FTPdtCode'];?>','<?php echo $aValue['FTZneCode'];?>','<?php echo $aValue['FTZneChainName'];?>');"></td>
           <td class="text-center"><img class="xCNIconTable" src="<?= base_url().'/application/modules/common/assets/images/icons/view2.png'?>" onClick="JSvPdtZoneConditionsPageView('<?php echo $aValue['FTPdtCode'];?>','<?php echo $aValue['FTZneCode'];?>','<?php echo $aValue['FTZneChainName'];?>');"></td>
        </tr>   
    <?php } ?>
    <?php else:?>
        <tr><td class='text-center xCNTextDetail2' colspan='6'><?= language('common/main/main','tCMNNotFoundData')?></td></tr>
    <?php endif;?>
</tbody>
</table>


<div class="modal fade" id="odvModalZoneConditions">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('product/product/product', 'tPdtZone'); ?></label>
            </div>
            <div class="modal-body">
                <form class="validate-form" action="javascript:void(0)" method="post" enctype="multipart/form-data" id="ofmAddZoneConditions">
                    <input type="text" class="form-control xCNHide" id="oetZoneConditionPdtCode" name="oetZoneConditionPdtCode">
                    <input type="text" class="form-control xCNHide" id="oetZoneConditionZneCodeOld" name="oetZoneConditionZneCodeOld">
                    <!-- <input type="text" class="form-control xCNHide" id="oetStockConditionWahCodeOld" name="oetStockConditionWahCodeOld"> -->
                    <input type="text" class="form-control xCNHide" id="oetZoneConditionRoute" name="oetZoneConditionRoute">

                    <!-- Browse Branch -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><span class="text-danger">*</span><?php echo language('product/product/product','tPdtZone')?></label>
                        <div class="input-group">
                            <input type="text" class="form-control xCNHide" id="oetZoneConditionZneCode" name="oetZoneConditionZneCode">
                            <input type="text" class="form-control xWPointerEventNone" id="oetZoneConditionZneName" name="oetZoneConditionZneName"
                            data-validate-required="<?php echo language('product/product/product','tPdtZoneConditionsValid');?>" readonly>
                            <span class="input-group-btn">
                                <button id="oimPscBrowseZne" type="button" class="btn xCNBtnBrowseAddOn">
                                    <img src="<?php echo  base_url().'/application/modules/common/assets/images/icons/find-24.png'?>">
                                </button>
                            </span>
                        </div>
                    </div>
                    <!-- end Browse Branch -->

                    <!-- include/exclude -->
                    <div class="form-group">
                        <label class="xCNLabelFrm"><span style="color:red">*</span><?php echo language('product/product/product','tPdtZoneInorEx');?></label>
                        <select class="selectpicker form-control" id="ocmTypeInorEx" name="ocmTypeInorEx"
                            data-validate-required = "<?= language('product/product/product','tPdtZoneConditionsValidGrp')?>"
                            data-validate-dublicateCode = "<?= language('product/product/product','tPdtZoneConditionsValidGrp')?>"
                        >   
                            <option value="1" selected><?php echo language('product/product/product','tPdtZoneInclude');?></option>
                            <option value="2"><?php echo language('product/product/product','tPdtZoneExclude');?></option>
                        </select>
                    </div>   
                    <!-- end include/exclude -->

                    <div class="text-right">
                        <button type="button" class="btn" style="background-color: #D4D4D4; color: #000000;" data-dismiss="modal">
                            <?php echo language('product/product/product', 'ยกเลิก')?>
                        </button>
                        <button type="submit" class="btn" style="background-color: rgb(23, 155, 253); color: white;"  onclick="JSvPdtZoneConditionsEventAddEdit()"> 
                            <?php echo  language('product/product/product', 'บันทึก')?>
                        </button>
                    </div>

                   
                </form>
            </div>
        </div>
    </div>
</div>

<div id="odvModalDeleteZoneConditions" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard"><?php echo language('common/main/main', 'tModalDelete')?></label>
            </div>
            <div class="modal-body">
                <span id="ospTextConfirmDelSingle" class="xCNTextModal" style="display: inline-block; word-break:break-all"></span>
            </div>
            <div class="modal-footer">
                <button id="osmConfirmDelSingle" class="btn xCNBTNPrimery xCNBTNPrimery2Btn" type="button"><?php echo language('common/main/main', 'tModalConfirm')?></button>
                <button class="btn xCNBTNDefult xCNBTNDefult2Btn" type="button"  data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
            </div>
        </div>
    </div>
</div>

<div id="odvModalZoneConditionsAlert" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top:10%;">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <label class="xCNTextModalHeard  text-center"><?php echo language('product/product/product', 'tPdtZoneConditionswarning')?></label>
        </div>
        <div class="modal-body">
            <div style="margin-top:10px;margin-bottom:10px;">
                <label><?php echo language('product/product/product', 'tPdtZoneConditionswarningZone')?></label>
            </div>
        
            <div class="text-center" style="margin-top:10px;margin-bottom:10px;">
                <button  type="button" class="btn" style="background-color: #D4D4D4; color: #000000;" data-dismiss="modal"><?php echo language('common/main/main', 'tModalCancel')?></button>
            </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="odvModalZoneDetail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header xCNModalHead">
                <label class="xCNTextModalHeard" id="ospHeaderZoneDetail"><?php echo language('product/product/product', 'tPdtZone'); ?></label>
            </div>
            <div class="modal-body">
                <div class="table-responsive xCNTableScrollY">
                    <div id="odvZoneDetail"></div>   
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('.selectpicker').selectpicker();
    });
    // Berowse
    $('#oimPscBrowseZne').click(function(){JCNxBrowseData('oBrowseZne')});

    var oBrowseZne = {

        Title: ['address/zone/zone', 'tBrowseZoneTitle'],
            Table: { Master: 'TCNMZone',PK: 'FTZneChain' },
            Join: {
                Table: ['TCNMZone_L'],
                On: ["TCNMZone.FTZneCode = TCNMZone_L.FTZneCode AND TCNMZone_L.FNLngID = 1 AND ISNULL(TCNMZone.FTZneCode,'') = ISNULL(TCNMZone_L.FTZneCode,'') AND ISNULL(TCNMZone.FTZneChain,'') = ISNULL(TCNMZone_L.FTZneChain,'') "]
            },
            Where: {
                Condition: [
                    function() {
                        var tAgncode = '<?=$this->session->userdata('tSesUsrAgnCode') ?>';
                        // var tSQL = " AND TCNMZone.FTZneChain NOT IN(SELECT FTZneChain FROM TCNTPdtPmtHDZne_Tmp WHERE FTSessionID = '<?php echo $this->session->userdata("tSesSessionID"); ?>') ";
                        var tSQL = "";
                        if(tAgncode != ''){
                            tSQL += " AND ISNULL(TCNMZone.FTAgnCode,'') = ''  OR ISNULL(TCNMZone.FTAgnCode,'') = '<?=$this->session->userdata('tSesUsrAgnCode') ?>'"
                        }
                        return tSQL;
                    }
                ]
            },
            GrideView: {
                ColumnPathLang: 'address/zone/zone',
                ColumnKeyLang: ['tBrowseZoneCode', 'tBrowseZoneName'],
                ColumnsSize: ['10%', '35%', '55%'],
                DataColumns: ['TCNMZone.FTZneChain', 'TCNMZone_L.FTZneChainName','TCNMZone.FTZneCode'],
                DataColumnsFormat: ['', '',''],
                WidthModal: 50,
                Perpage: 10,
                OrderBy: ['TCNMZone.FTZneChain'],
                SourceOrder: "ASC",
                DisabledColumns: [2],
            },
            CallBack: {
                ReturnType: 'S',
                Value: ["oetZoneConditionZneCode", "TCNMZone.FTZneChain"],
                Text: ["oetZoneConditionZneName", "TCNMZone_L.FTZneChainName"]
            },
            // NextFunc: {
            //     FuncName: 'JSxPromotionStep4CallbackZone',
            //     ArgReturn: ['FTZneChain', 'FTZneChainName','FTZneCode']
            // },
            RouteAddNew: 'zone',
            BrowseLev: 1,
            // DebugSQL : true
        };
        
</script>

