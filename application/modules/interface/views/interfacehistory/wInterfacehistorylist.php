<div class="panel-heading">
	<div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
                <div class="form-group">
                    <div class="input-group">
                        <input
                            class="form-control xCNInpuASTthoutSingleQuote"
                            type="text"
                            id="oetSearchAll"
                            name="oetSearchAll"
                            placeholder="<?php echo language('document/adjuststock/adjuststock','tASTFillTextSearch')?>"
                            onkeyup="Javascript:if(event.keyCode==13) JSvSearchAllIFH()"
                            autocomplete="off"
                        >
                        <span class="input-group-btn">
                            <button type="button" class="btn xCNBtnDateTime" onclick="JSvSearchAllIFH()">
                                <img class="xCNIconSearch">
                            </button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <div class="form-group">
                    <div class="input-group">
                        <input
                            class="form-control xCNDatePicker"
                            type="text"
                            id="oetIFHDocDateFrom"
                            name="oetIFHDocDateFrom"
                            placeholder="<?php echo language('interface/interfacehistory', 'tIFHDatefrom'); ?>"
                        >
                        <span class="input-group-btn" >
                            <button  type="button" class="btn xCNBtnDateTime "> <img class="xCNIconCalendar"></button>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <div class="form-group">
                    <div class="input-group">
                        <input
                            class="form-control xCNDatePicker"
                            type="text"
                            id="oetIFHDocDateTo"
                            name="oetIFHDocDateTo"
                            placeholder="<?php echo language('interface/interfacehistory', 'tIFHDateto'); ?>"
                        >
                        <span class="input-group-btn" >
                            <button  type="button"  class="btn xCNBtnDateTime" onclick=""><img class="xCNIconCalendar"></button>
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <div class="form-group">
                    <select class="selectpicker form-control" id="ocmIFHStaDone" name="ocmIFHStaDone">
                        <option value=''><?php echo language('interface/interfacehistory','tIFHStatus'); ?></option>
                        <option value='1'><?php echo language('interface/interfacehistory','tIFHSuccess'); ?></option>
                        <option value='2'><?php echo language('interface/interfacehistory','tIFHFail'); ?></option>
                    </select>
                </div>
            </div>

            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <div class="form-group">
                    
                    <select class="selectpicker form-control" id="ocmIFHType" name="ocmIFHType">
                        <option value=''><?php echo language('interface/interfacehistory','tIFHType'); ?></option>
                        <option value='1'><?php echo language('interface/interfacehistory','tIFHImport'); ?></option>
                        <option value='2'><?php echo language('interface/interfacehistory','tIFHExport'); ?></option>
                        
                    </select>
                </div>
            </div>

            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <div class="form-group">
                    
                    <select class="selectpicker form-control" id="ocmIFHInfCode" name="ocmIFHInfCode">
                        <option value=''><?php echo language('interface/interfacehistory','tIFHList'); ?></option>
                        <?php if(!empty($aDataMasterImport)){
                            foreach($aDataMasterImport as $aData){ ?>
                        <option value='<?php echo $aData['FTInfCode']; ?>' class="xWIFHDocType xWIFHDoctype_<?=$aData['FTInfTypeDoc'];?>"><?php echo $aData['FTInfName']; ?></option>
                        <?php } }  ?>
                    </select>
                </div>
            </div>

            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <div class="form-group">
                    <div class="input-group">
                        <a  id="oahIFHSearchData" class="btn xCNBTNDefult xCNBTNDefult1Btn" href="javascript:;" onclick="JSvCallPageIFHDataTable()"> <?php echo language('interface/interfacehistory','tIFHBtnSearch'); ?></a>
                    </div>
                </div>
            </div>
        </div>
                  
    </div>
</div>
<div class="panel-body">

	<div id="odvContentIFHDatatable"></div>
</div>

<script>
    $(document).ready(function(){
        $('.selectpicker').selectpicker();
    });
    $('.xCNDatePicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
           
    });

    //Added by Napat(Jame) 03/04/63
    $('#ocmIFHType').on('change',function(e){
        if(this.value != ''){
            $('.xWIFHDocType').css('display','none');
            $('.xWIFHDoctype_' + this.value).css('display','block');
        }else{
            $('.xWIFHDocType').css('display','block');
        }
    });
</script>
