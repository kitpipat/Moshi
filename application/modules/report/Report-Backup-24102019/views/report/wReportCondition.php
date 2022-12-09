<?php if(isset($aRptFilterData) && $aRptFilterData['rtCode'] == 1):?>
    <div id="odvCondition<?php echo $aRptFilterData['raItems']['rtRptCode'];?>">
        <div id="odvRptClearCondition" class="row" style="padding-bottom:15px">
            <div class="col-xs-12 col-smd-9 col-md-9 col-lg-9"></div>
            <div class="col-md-3 text-right">
                <button id="obtRptClearCondition" class="btn btn-primary" style="font-size:17px;width:100%;"><?php echo language('report/report/report','tRptClearCondition')?></button>
            </div>
        </div>
        <?php $tCoditionReport = "";?>
        <?php foreach($aRptFilterData['raItems']['raRptFilterCol'] AS $nKey => $aRptFilValue):?>
            <?php
                switch($aRptFilValue['FTRptFltCode']){
                    case '1': 
                        // Filter Branch (ค้นหาข้อมูลสาขา)
                        $tCoditionReport .= "<div id='odvCondition".$aRptFilValue['FTRptFltCode']."' class='row'>";
                        if($aRptFilValue['FTRptFltStaFrm'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionFrom').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptBchCodeFrom' name='oetRptBchCodeFrom' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptBchNameFrom' name='oetRptBchNameFrom' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowseBchFrom' type='button' class='btn xCNBtnBrowseAddOn'><img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div> ";
                        }
                        if($aRptFilValue['FTRptFltStaTo'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionTo').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptBchCodeTo' name='oetRptBchCodeTo' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptBchNameTo' name='oetRptBchNameTo' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowseBchTo' type='button' class='btn xCNBtnBrowseAddOn'><img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div> ";
                        }
                        $tCoditionReport .= "</div>";
                    break;
                    case '2': 
                        // Filter Shop (ค้นหาข้อมูลร้านค้า)
                        $tCoditionReport .= "<div id='odvCondition".$aRptFilValue['FTRptFltCode']."' class='row'>";
                        if($aRptFilValue['FTRptFltStaFrm'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionFrom').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptShpCodeFrom' name='oetRptShpCodeFrom' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptShpNameFrom' name='oetRptShpNameFrom' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowseShpFrom' type='button' class='btn xCNBtnBrowseAddOn'><img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div> ";
                        }
                        if($aRptFilValue['FTRptFltStaTo'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionTo').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptShpCodeTo' name='oetRptShpCodeTo' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptShpNameTo' name='oetRptShpNameTo' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowseShpTo' type='button' class='btn xCNBtnBrowseAddOn'><img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div> ";
                        }
                        $tCoditionReport .= "</div>";
                    break;
                    case '3': 
                        // Filter Pos (ค้นหาข้อมูลเครื่องจุดขาย)
                        $tCoditionReport .= "<div id='odvCondition".$aRptFilValue['FTRptFltCode']."' class='row'>";
                        if($aRptFilValue['FTRptFltStaFrm'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionFrom').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptPosCodeFrom' name='oetRptPosCodeFrom' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptPosNameFrom' name='oetRptPosNameFrom' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowsePosFrom' type='button' class='btn xCNBtnBrowseAddOn'><img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div> ";
                        }
                        if($aRptFilValue['FTRptFltStaTo'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionTo').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptPosCodeTo' name='oetRptPosCodeTo' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptPosNameTo' name='oetRptPosNameTo' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowsePosTo' type='button' class='btn xCNBtnBrowseAddOn'><img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div> ";
                        }
                        $tCoditionReport .= "</div>";
                    break;
                    case '4':
                        $tCoditionReport .= "<div id='odvCondition".$aRptFilValue['FTRptFltCode']."' class='row'>";
                        if($aRptFilValue['FTRptFltStaFrm'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionFrom').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNDatePicker xCNInputMaskDate xWRptAllInput' id='oetRptDocDateFrom' name='oetRptDocDateFrom'>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowseDocDateFrom' type='button' class='btn xCNBtnDateTime'><img class='xCNIconCalendar'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                        if($aRptFilValue['FTRptFltStaTo'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionTo').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNDatePicker xCNInputMaskDate xWRptAllInput' id='oetRptDocDateTo' name='oetRptDocDateTo'>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowseDocDateTo' type='button' class='btn xCNBtnDateTime'><img class='xCNIconCalendar'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                        $tCoditionReport .= "</div>";
                    break;
                    case '5':
                        $tCoditionReport .= "<div id='odvCondition".$aRptFilValue['FTRptFltCode']."' class='row'>";
                        if($aRptFilValue['FTRptFltStaFrm'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionFrom').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNYearPicker xWRptAllInput' id='oetRptYearFrom' name='oetRptYearFrom'>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowseYearFrom' type='button' class='btn xCNBtnDateTime'><img class='xCNIconCalendar'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                        if($aRptFilValue['FTRptFltStaFrm'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionTo').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNYearPicker xWRptAllInput' id='oetRptYearTo' name='oetRptYearTo'>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowseYearTo' type='button' class='btn xCNBtnDateTime'><img class='xCNIconCalendar'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                        $tCoditionReport .= "</div>";
                    break;
                    case '6': 
                        // From - To MerChant Group (ค้นหาข้อมูลกลุ่มธุรกิจ จาก - ถึง)
                        $tCoditionReport .= "<div id='odvCondition".$aRptFilValue['FTRptFltCode']."' class='row'>";
                        if($aRptFilValue['FTRptFltStaFrm'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionFrom').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptMerCodeFrom' name='oetRptMerCodeFrom' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptMerNameFrom' name='oetRptMerNameFrom' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowseMerFrom' type='button' class='btn xCNBtnBrowseAddOn'> <img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div> ";
                        }
                        if($aRptFilValue['FTRptFltStaTo'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionTo').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptMerCodeTo' name='oetRptMerCodeTo' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptMerNameTo' name='oetRptMerNameTo' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowseMerTo' type='button' class='btn xCNBtnBrowseAddOn'><img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div> ";
                        }
                        $tCoditionReport .= "</div>";
                    break;
                    case '7': 
                        // From - To PaymentType Group (ค้นหาข้อมูลประเภทการชำระ จาก - ถึง)
                        $tCoditionReport .= "<div id='odvCondition".$aRptFilValue['FTRptFltCode']."' class='row'>";
                        if($aRptFilValue['FTRptFltStaFrm'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionFrom').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptRcvCodeFrom' name='oetRptRcvCodeFrom' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptRcvNameFrom' name='oetRptRcvNameFrom' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowseRcvFrom' type='button' class='btn xCNBtnBrowseAddOn'> <img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div> ";
                        }
                        if($aRptFilValue['FTRptFltStaTo'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionTo').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptRcvCodeTo' name='oetRptRcvCodeTo' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptRcvNameTo' name='oetRptRcvNameTo' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowseRcvTo' type='button' class='btn xCNBtnBrowseAddOn'><img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div> ";
                        }
                        $tCoditionReport .= "</div>";
                    break;
                    case '8':
                        // From - To Product  Group (ค้นหาข้อมูลกลุ่มสินค้า จาก - ถึง)
                        $tCoditionReport .= "<div id='odvCondition".$aRptFilValue['FTRptFltCode']."' class='row'>";
                        if($aRptFilValue['FTRptFltStaFrm'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionFrom').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptPdtGrpCodeFrom' name='oetRptPdtGrpCodeFrom' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptPdtGrpNameFrom' name='oetRptPdtGrpNameFrom' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowsePdtGrpFrom' type='button' class='btn xCNBtnBrowseAddOn'> <img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div> ";
                        }
                        if($aRptFilValue['FTRptFltStaTo'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionTo').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptPdtGrpCodeTo' name='oetRptPdtGrpCodeTo' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptPdtGrpNameTo' name='oetRptPdtGrpNameTo' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowsePdtGrpTo' type='button' class='btn xCNBtnBrowseAddOn'><img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div> ";
                        }
                        $tCoditionReport .= "</div>";
                    break;
                    case '9':
                        // From - To Product Type (ค้นหาข้อมูลประเภทสินค้า จาก - ถึง)
                        $tCoditionReport .= "<div id='odvCondition".$aRptFilValue['FTRptFltCode']."' class='row'>";
                        if($aRptFilValue['FTRptFltStaFrm'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionFrom').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptPdtTypeCodeFrom' name='oetRptPdtTypeCodeFrom' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptPdtTypeNameFrom' name='oetRptPdtTypeNameFrom' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowsePdtTypeFrom' type='button' class='btn xCNBtnBrowseAddOn'> <img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div> ";
                        }
                        if($aRptFilValue['FTRptFltStaTo'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionTo').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptPdtTypeCodeTo' name='oetRptPdtTypeCodeTo' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptPdtTypeNameTo' name='oetRptPdtTypeNameTo' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowsePdtTypeTo' type='button' class='btn xCNBtnBrowseAddOn'><img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div> ";
                        }
                        $tCoditionReport .= "</div>";
                    break;
                    case '10':  
                        // Product Type (ค้นหาข้อมูลประเภทสินค้า)
                        $tCoditionReport .= "<div id='odvCondition".$aRptFilValue['FTRptFltCode']."' class='row'>";
                        if($aRptFilValue['FTRptFltStaFrm'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('product/pdttype/pdttype','tPGPPdttypeFrom').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                        <select class='selectpicker ' id='ocmBchPriority' name='ocmBchPriority'>
                                            <option value='5'>5</option>
                                            <option value='10'>10</option>
                                            <option value='20'>20</option>
                                            <option value='50'>50</option>
                                            <option value='10'>100</option>
                                            <option value='200'>200</option>
                                            <option value='500'>500</option>
                                        </select>                                       
                                        </div>
                                    </div>
                                </div> 
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                </div></div>";
                        }
                    break;
                    case '11':
                        // Merchant (ค้นหาข้อมูลกลุ่มธุรกิจ) เดี่ยว ไม่มี จาก - ถึง
                        $tCoditionReport    .= "<div id='odvCondition".$aRptFilValue['FTRptFltCode']."' class='row'>";
                        if(($aRptFilValue['FTRptFltStaFrm'] == '1' && $aRptFilValue['FTRptFltStaTo'] == '0') || ($aRptFilValue['FTRptFltStaFrm'] == '0' && $aRptFilValue['FTRptFltStaTo'] == '1')){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptMerchantCode' name='oetRptMerchantCode' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptMerchantName' name='oetRptMerchantName' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowseMerchant' type='button' class='btn xCNBtnBrowseAddOn'> <img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                        $tCoditionReport    .= "</div>";
                    break;
                    case '12':
                        // WareHouse (ค้นหาข้อมูลคลังสินค้า)
                        $tCoditionReport .= "<div id='odvCondition".$aRptFilValue['FTRptFltCode']."' class='row'>";
                        if($aRptFilValue['FTRptFltStaFrm'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionFrom').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptWahCodeFrom' name='oetRptWahCodeFrom' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptWahNameFrom' name='oetRptWahNameFrom' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowseWahFrom' type='button' class='btn xCNBtnBrowseAddOn'> <img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                        if($aRptFilValue['FTRptFltStaTo'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionTo').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptWahCodeTo' name='oetRptWahCodeTo' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptWahNameTo' name='oetRptWahNameTo' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowseWahTo' type='button' class='btn xCNBtnBrowseAddOn'><img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                        $tCoditionReport .= "</div>";
                    break;
                    case '13':
                        // Product (ค้นหาข้อมูลสินค้า)
                        $tCoditionReport .= "<div id='odvCondition".$aRptFilValue['FTRptFltCode']."' class='row'>";
                        if($aRptFilValue['FTRptFltStaFrm'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionFrom').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptPdtCodeFrom' name='oetRptPdtCodeFrom' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptPdtNameFrom' name='oetRptPdtNameFrom' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowsePdtFrom' type='button' class='btn xCNBtnBrowseAddOn'> <img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                        if($aRptFilValue['FTRptFltStaTo'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionTo').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptPdtCodeTo' name='oetRptPdtCodeTo' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptPdtNameTo' name='oetRptPdtNameTo' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowsePdtTo' type='button' class='btn xCNBtnBrowseAddOn'><img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                        $tCoditionReport .= "</div>";
                    break;
                    case '14':
                        // บริษัท ขนส่ง
                        $tCoditionReport .= "<div id='odvCondition".$aRptFilValue['FTRptFltCode']."' class='row'>";
                        if($aRptFilValue['FTRptFltStaFrm'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionFrom').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptCourierCodeFrom' name='oetRptCourierCodeFrom' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptCourierNameFrom' name='oetRptCourierNameFrom' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowseCourierFrom' type='button' class='btn xCNBtnBrowseAddOn'> <img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                        if($aRptFilValue['FTRptFltStaTo'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionTo').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetRptCourierCodeTo' name='oetRptCourierCodeTo' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetRptCourierNameTo' name='oetRptCourierNameTo' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtRptBrowseCourierTo' type='button' class='btn xCNBtnBrowseAddOn'><img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                        $tCoditionReport .= "</div>";
                    break;
                    case '15':
                        // ตู้ฝากของ Rack
                        $tCoditionReport .= "<div id='odvCondition".$aRptFilValue['FTRptFltCode']."' class='row'>";
                        if($aRptFilValue['FTRptFltStaFrm'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionFrom').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetSMLBrowseGroupCodeFrom' name='oetSMLBrowseGroupCodeFrom' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetSMLBrowseGroupNameFrom' name='oetSMLBrowseGroupNameFrom' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtSMLBrowseGroupFrom' type='button' class='btn xCNBtnBrowseAddOn'> <img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                        if($aRptFilValue['FTRptFltStaTo'] == '1'){
                            $tCoditionReport    .= "
                                <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
                                    <div class='form-group'>
                                        <label class='xCNLabelFrm'>".language('report/report/report','tRptCoditionTo').$aRptFilValue['FTRptFltName']."</label>
                                        <div class='input-group'>
                                            <input type='text' class='form-control xCNHide xWRptAllInput' id='oetSMLBrowseGroupCodeTo' name='oetSMLBrowseGroupCodeTo' maxlength='5'>
                                            <input type='text' class='form-control xWPointerEventNone xWRptAllInput' id='oetSMLBrowseGroupNameTo' name='oetSMLBrowseGroupNameTo' readonly>
                                            <span class='input-group-btn'>
                                                <button id='obtSMLBrowseGroupTo' type='button' class='btn xCNBtnBrowseAddOn'><img class='xCNIconFind'></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                        $tCoditionReport .= "</div>";
                    break;
                }
            ?>
        <?php endforeach;?>
        <br>
        <?php echo $tCoditionReport;?>
        <div id="odvBtnRptProcessGrp" class="row" style="padding-top:20px">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <button type="button" id="obtRptExportExcel" data-rpccode="" class="btn btn-primary" style="font-size: 17px;width: 100%;">
                    <?php echo language('report/report/report','tRptExportExcel') ?>
                </button>
            </div>
            
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <!-- <button type="button" id="obtRptDownloadPdf" data-rpccode="" class="btn btn-primary" style="font-size:17px;width:100%;">
                    <?php echo language('report/report/report','tRptDownloadPDF') ?>
                </button> -->
            </div>

            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"></div>
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                <button type="button" id="obtRptViewBeforePrint" data-rpccode="" class="btn btn-primary" style="font-size: 17px;width: 100%;">
                    <?php echo language('report/report/report','tRptViewBeforePrint') ?>
                </button>
            </div>
        </div>
        <?php include "script/jReportCondition.php"; ?>
    </div>
<?php endif; ?>
