<?php

function FCNvGetModalSwitchLang($ptTableMaster){
    $ci = &get_instance();
    if($ci->session->userdata("tLangEdit") == 1){
        $tLangShow = language('common/main/main','tLangSystemsThai'); 
    }else{
        $tLangShow = language('common/main/main','tLangSystemsEng'); 
    }

    $oOnclick = "JSxSwitchLang('$ptTableMaster')";
    $tHTMLSwitchLang = '<div onclick="'.$oOnclick.'">';
    $tHTMLSwitchLang .= '<button class="btn xCNBTNDefult xCNBTNDefult2Btn xCNBTNSwitchLang" type="button"> + ' . $tLangShow;
    $tHTMLSwitchLang .= '</button>';
    $tHTMLSwitchLang .= '</div>';
    echo $tHTMLSwitchLang;
}

?>

