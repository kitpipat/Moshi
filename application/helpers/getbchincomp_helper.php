<?php
/**
 * Functionality: get bch in company
 * Parameters: -
 * Creator: 25/02/2019 Phisan(Arm)
 * Last Modified : 31/05/2019 Piya
 * Return : bch
 * Return Type: text
 */
function FCNtGetBchInComp() {
    $ci = &get_instance();
    $ci->load->database();
    $tSesUsrBchCode = $ci->session->userdata('tSesUsrBchCode');
    if (empty($tSesUsrBchCode)) {
        $tSQLComp = "SELECT FTBchcode FROM TCNMComp";
        $oQueryComp = $ci->db->query($tSQLComp);
        if(!empty($oQueryComp->result_array())){
            $aComp = $oQueryComp->result_array();
            $tSesUsrBchCode = $aComp[0]['FTBchcode'];
        }else{
            //$tSesUsrBchCode = 'Warning: Branch in company not found.';
            $tSesUsrBchCode = false;
        }
    }
    return $tSesUsrBchCode;
}

/**
 * Functionality: get bch in company
 * Parameters: -
 * Creator: 24/05/2019 Krit(Copter)
 * Last Modified : 31/05/2019 Piya
 * Return : bch
 * Return Type: text
 */
function FCNtGetBchNameInComp() {
    $ci = &get_instance();
    $ci->load->database();
    $tSesUsrBchCode = $ci->session->userdata('tSesUsrBchCode');
    $tSesUsrBchName = "";
    if (empty($tSesUsrBchCode)) {
        $tSQLComp = "   SELECT TCNMBranch_L.FTBchName FROM TCNMComp 
                        LEFT JOIN TCNMBranch_L ON TCNMComp.FTBchcode = TCNMBranch_L.FTBchcode";
        $oQueryComp = $ci->db->query($tSQLComp);
        if(!empty($oQueryComp->result_array())){
            $aComp = $oQueryComp->result_array();
            $tSesUsrBchName = $aComp[0]['FTBchName'];
        }else{
            //$tSesUsrBchName = 'Warning: Branch in company not found.';
            $tSesUsrBchName = false;
        }
    }
    return $tSesUsrBchName;
}



























