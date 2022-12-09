<?php
    $nTotalPage = ceil($nTotalRecord / $nPerPage);

    if ($nCurentPage == 1){
        $nPrvPage   = 1;
    }else{
        $nPrvPage   = $nCurentPage - 1;
    }
    
    if ($nCurentPage == $nTotalPage){
        $nNextPage  = $nTotalPage;
    }else{
        $nNextPage  = $nCurentPage + 1;
    }
?>
<input type="hidden" class="xWBrowseOptionName"     value="<?php echo @$tOptionName;?>">
<input type="hidden" class="xWBrowseCallBackValue"  value="<?php echo @$tOldCallBackVal;?>">
<input type="hidden" class="xwBrowseCallBackText"   value="<?php echo @$tOldCallBackText;?>">
<div class="modal-header xCNModalHead">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <label class="xCNTextModalHeard" style="font-weight: bold; font-size: 20px;"><?php echo language('common/main/main', 'tShowData'). ' : '.$tTitleHeader;?></label>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
            <button class="btn xCNBTNPrimery xCNBTNPrimery2Btn xWBtnEventSelectBrowse"><?php echo language('common/main/main','tModalAdvChoose');?></button>
            <button class="btn xCNBTNDefult xCNBTNDefult2Btn xWBtnEventCloseBrowse" data-dismiss="modal"><?php echo language('common/main/main','tCMNClose');?></button>
        </div>
    </div>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
            <div class="form-group">
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control xWSearchTableBrowse"
                        value="<?php echo $tFilterSearch;?>"
                        autocomplete="off" placeholder="<?php echo language('common/main/main','tPlaceholder');?>"  
                    >
                    <span class="input-group-btn">
                        <button type="button" class="btn xCNBtnSearch xWBtnSearchTableBrowse"><img class="xCNIconSearch"></button>
                    </span>
                </div>
            </div>
        </div>
        <?php if(isset($nStaBrowseLevel)):?>
            <?php if($nStaBrowseLevel != 1):?>
                <div class='col-xs-6 col-sm-6 col-md-7 col-lg-7 text-right'>
                    <button class='xCNBtnPushModalBrowse'>+</button>
                </div>
            <?php endif;?>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
            <div class="table-responsive">
                <table id="otbBrowserList" class='table table-striped' style="width:100%">
                    <thead>
                        <tr>
                            <?php echo @$tHtmlTableHeard;?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo @$tHtmlTableData;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class='row m-t-10'>
        <div class='col-xs-12 col-md-6'>
            <?php echo language('common/main/main', 'tResultTotalRecord')." ".number_format($nTotalRecord)." ".language('common/main/main', 'tRecord')." ".language('common/main/main', 'tCurrentPage')." " . ($nCurentPage == "" ? "1" : $nCurentPage) . " / " . $nTotalPage?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
            <div class="btn-toolbar pull-right">
                <?php if($nCurentPage == 1){ $tDisabledLeft = 'disabled'; }else{ $tDisabledLeft = '-';} ?>
                <button onclick="JCNxSearchBrowse('<?php echo $nPrvPage;?>','<?php echo $tOptionName; ?>')" class="btn btn-white btn-sm" <?php echo $tDisabledLeft ?>>
                    <i class="fa fa-chevron-left f-s-14 t-plus-1"></i>
                </button>

                <?php for($i = max($nCurentPage-2, 1); $i <= max(0, min($nTotalPage,$nCurentPage+2)); $i++):?>
                    <?php 
                        if($nCurentPage == $i){ 
                            $tActive = 'active'; 
                            $tDisPageNumber = 'disabled';
                        }else{ 
                            $tActive = '';
                            $tDisPageNumber = '';
                        }
                    ?>
                    <button onclick="JCNxSearchBrowse('<?php echo $i?>','<?php echo $tOptionName;?>')" type="button" class="btn xCNBTNNumPagenation <?php echo $tActive ?>" <?php echo $tDisPageNumber ?>><?php echo $i?></button>
                <?php endfor; ?>
                    
                <?php if($nCurentPage >= $nTotalPage){  $tDisabledRight = 'disabled'; }else{  $tDisabledRight = '-';  } ?>
                <button onclick="JCNxSearchBrowse('<?php echo $nNextPage;?>','<?php echo $tOptionName;?>')" class="btn btn-white btn-sm" <?php echo $tDisabledRight ?>>
                    <i class="fa fa-chevron-right f-s-14 t-plus-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<?php echo @$tHtmlInputCallBack;?>
<script type="text/javascript">
    $(document).ready(function (){
        var nStaBrowseLevel =  '<?php echo @$nStaBrowseLevel;?>';
        var nCurentPage     =  '<?php echo @$nCurentPage;?>';
        if(nStaBrowseLevel == 1){
            var tOldCallBackVal     = $('#myModal2 .xWBrowseCallBackValue').val();
            var tOldCallBackText    = $('#myModal2 .xwBrowseCallBackText').val();
        }else{
            var tOldCallBackVal     = $('#myModal .xWBrowseCallBackValue').val();
            var tOldCallBackText    = $('#myModal .xwBrowseCallBackText').val();
        }

        // Event Click Confirm Select
        $('.xWBtnEventSelectBrowse').unbind().click(function(){
            if(nStaBrowseLevel == 1){
                let tOptionModalName    = $('#myModal2 .xWBrowseOptionName').val();
                JCNxConfirmSelected(tOptionModalName);
            }else{
                let tOptionModalName    = $('#myModal .xWBrowseOptionName').val();
                JCNxConfirmSelected(tOptionModalName);
            }
        });

        // Event Enter Serch Input Modal 
        $('.xWSearchTableBrowse').bind('keypress',function(Evn){
            if(Evn.keyCode == 13){
                if(nStaBrowseLevel == 1){
                    let tOptionModalName    = $('#myModal2 .xWBrowseOptionName').val();
                    JCNxSearchBrowse(nCurentPage,tOptionModalName);
                }else{
                    let tOptionModalName    = $('#myModal .xWBrowseOptionName').val();
                    JCNxSearchBrowse(nCurentPage,tOptionModalName);
                }
            }
        });

        // Event Click Serch Input Modal 
        $('.xWBtnSearchTableBrowse').unbind().click(function(){
            if(nStaBrowseLevel == 1){
                let tOptionModalName    = $('#myModal2 .xWBrowseOptionName').val();
                JCNxSearchBrowse(nCurentPage,tOptionModalName);
            }else{
                let tOptionModalName    = $('#myModal .xWBrowseOptionName').val();
                JCNxSearchBrowse(nCurentPage,tOptionModalName);
            }
        });







    
    });
</script>