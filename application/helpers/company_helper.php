<?php
/**
 * 
 * @param type $paParams
 * @return type
 */
function FCNaGetCompanyInfo($paParams = []){
    $ci = &get_instance();
    $ci->load->model('company/company/mCompany');

    $aCompParams = [
        'nLngID' => $paParams['nLngID'],
        'tBchCode' => $paParams['tBchCode']
    ];
    
    return $ci->mCompany->FSaMCMPGetCompanyInfo($aCompParams);
}

/**
 * 
 * @param type $paParams
 * @return type
 */
function FCNaGetBranchInfo($paParams = []){
    $ci = &get_instance();
    $ci->load->model('company/branch/mBranch');

     $aBchParams = [
        'nLngID' => $paParams['nLngID'],
        'tBchCode' => $paParams['tBchCode']
    ];
    
    return $ci->mBranch->FSaMCMPGetBchInfo($aBchParams);
}

/**
 * 
 * @param type $paParams
 * @return type
 */
function FCNtGetCompanyCode(){
    $ci = &get_instance();
    $ci->load->model('company/company/mCompany');
    $aCompany = $ci->mCompany->FSaMCMPGetCompanyCode();
    
    $tCompanyCode = "Company Code Not Found.";
    if($aCompany['rtCode'] == '1') {
        $tCompanyCode = $aCompany['raItems']['FTCmpCode'];
    }
    return $tCompanyCode;
}
