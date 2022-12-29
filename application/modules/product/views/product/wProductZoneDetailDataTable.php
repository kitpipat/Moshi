<table class="table" style="width:100%;">
    <label class="xCNLabelFrm"><?php echo language('product/product/product','ตัวแทนขาย')?></label>
    <thead>
        <tr>
            <th nowrap="" class="text-center xCNTextBold" style="width:50%;"><?php echo language('product/product/product','tPdtZoneAgnCode')?></th>
            <th nowrap="" class="text-center xCNTextBold" style="width:50%;"><?php echo language('product/product/product','tPdtZoneAgnName')?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(isset($raItems)){
            $nAgn = 0;
            foreach($raItems AS $nKey => $aValue){
                if($aValue['FTAgnName'] != '' || $aValue['FTAgnName'] != null) {
                    echo "<tr>";
                    echo "<td class='text-left'>" . $aValue['FTZneRefCode'] . "</td>";
                    echo "<td class='text-left'>" . $aValue['FTAgnName'] . "</td>";
                    echo "</tr>";

                    $nAgn++;
                }
        ?>
        <?php
            }              
            if($nAgn == 0 ){
                echo "<tr>";
                echo "<td nowrap colspan='5' class='text-center'>" . language('common/main/main','tCMNNotFoundData') . "</td>";
                echo "</tr>";
            }
        }else{
        ?>
            <tr><td nowrap colspan="5" class="text-center"><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
        <?php
        }
        ?>
    </tbody>
</table>

<table class="table" style="width:100%;">
    <label class="xCNLabelFrm"><?php echo language('product/product/product','สาขา')?></label>
    <thead>
        <tr>
            <th nowrap="" class="text-center xCNTextBold" style="width:50%;"><?php echo language('product/product/product','tPdtZoneBchCode')?></th>
            <th nowrap="" class="text-center xCNTextBold" style="width:50%;"><?php echo language('product/product/product','tPdtZoneBchName')?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(isset($raItems)){
            $nBch = 0;
            foreach($raItems AS $nKey => $aValue){
                if($aValue['FTBchName'] != '' || $aValue['FTBchName'] != null) {
                    echo "<tr>";
                    echo "<td class='text-left'>" . $aValue['FTZneRefCode'] . "</td>";
                    echo "<td class='text-left'>" . $aValue['FTBchName'] . "</td>";
                    echo "</tr>";

                    $nBch++;
                }
        ?>
        <?php
            }
            if($nBch == 0) {
                echo "<tr>";
                echo "<td nowrap colspan='5' class='text-center'>" . language('common/main/main','tCMNNotFoundData') . "</td>";
                echo "</tr>";
            }
        }else{
        ?>
            <tr><td nowrap colspan="5" class="text-center"><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
        <?php
        }
        ?>
    </tbody>
</table>

<table class="table" style="width:100%;">
    <label class="xCNLabelFrm"><?php echo language('product/product/product','กลุ่มธุรกิจ')?></label>
    <thead>
        <tr>
            <th nowrap="" class="text-center xCNTextBold" style="width:50%;"><?php echo language('product/product/product','tPdtZoneMerCode')?></th>
            <th nowrap="" class="text-center xCNTextBold" style="width:50%;"><?php echo language('product/product/product','tPdtZoneMerName')?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(isset($raItems)){
            $nMer = 0;
            foreach($raItems AS $nKey => $aValue){
                if($aValue['FTMerName'] != '' || $aValue['FTMerName'] != null) {
                    echo "<tr>";
                    echo "<td class='text-left'>" . $aValue['FTZneRefCode'] . "</td>";
                    echo "<td class='text-left'>" . $aValue['FTMerName'] . "</td>";
                    echo "</tr>";

                    $nMer++;
                }
        ?>
        <?php
            }
            if($nMer == 0){
                echo "<tr>";
                echo "<td nowrap colspan='5' class='text-center'>" . language('common/main/main','tCMNNotFoundData') . "</td>";
                echo "</tr>";
            }
        }else{
        ?>
            <tr><td nowrap colspan="5" class="text-center"><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
        <?php
        }
        ?>
    </tbody>
</table>

<table class="table" style="width:100%;">
    <label class="xCNLabelFrm"><?php echo language('product/product/product','ร้านค้า')?></label>
    <thead>
        <tr>
            <th nowrap="" class="text-center xCNTextBold" style="width:50%;"><?php echo language('product/product/product','tPdtZoneShpCode')?></th>
            <th nowrap="" class="text-center xCNTextBold" style="width:50%;"><?php echo language('product/product/product','tPdtZoneShpName')?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(isset($raItems)){
            $nShp = 0;
            foreach($raItems AS $nKey => $aValue){
                if($aValue['FTShpName'] != '' || $aValue['FTShpName'] != null) {
                    echo "<tr>";
                    echo "<td class='text-left'>" . $aValue['FTZneRefCode'] . "</td>";
                    echo "<td class='text-left'>" . $aValue['FTShpName'] . "</td>";
                    echo "</tr>";

                    $nShp++;
                }
        ?>
        <?php
            }
            if($nShp == 0 ){
                echo "<tr>";
                echo "<td nowrap colspan='5' class='text-center'>" . language('common/main/main','tCMNNotFoundData') . "</td>";
                echo "</tr>";
            }
        }else{
        ?>
            <tr><td nowrap colspan="5" class="text-center"><?php echo language('common/main/main','tCMNNotFoundData')?></td></tr>
        <?php
        }
        ?>
    </tbody>
</table>