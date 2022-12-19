<table class="table" style="width:100%;">
    <thead>
        <tr>
            <th nowrap class="xCNTextBold"><?php echo language('product/product/product','tPdtZoneBchCode')?></th>
            <th nowrap class="xCNTextBold"><?php echo language('product/product/product','tPdtZoneBchName')?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(isset($raItems)){
            foreach($raItems AS $nKey => $aValue){
        ?>
            <tr>
                <td nowrap><?php echo $aValue['FTZneRefCode']; ?></td>
                <td nowrap><?php echo $aValue['FTBchName']; ?></td>
            </tr>
        <?php
            }
        }else{
        ?>
            <tr><td nowrap colspan="5" class="text-center"><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
        <?php
        }
        ?>
    </tbody>
</table>