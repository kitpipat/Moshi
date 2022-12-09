<?php

defined('BASEPATH') or exit('No direct script access allowed');

class cCommon extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function FCNtCCMMErrorRoute() {
        $this->load->view('common/wErrorPage');
    }

    public function FCNtCCMMGenCode() {

        $tTableName = $this->input->post('tTableName');
        $tGencode = FCNaHGencode($tTableName);

        echo json_encode($tGencode);
    }

    public function FCNtCCMMGenCodeV5() {

        $tTableName = $this->input->post('tTableName');
        $tStaDoc = $this->input->post('tStaDoc');
        // $tGencode	= FCNaHGencode($tTableName);
        $tGencode = FCNaHGenCodeV5($tTableName, $tStaDoc);

        echo json_encode($tGencode);
    }

    public function FCNtCCMMCheckInputGenCode() {

        $tTableName     = $this->input->post('tTableName');
        $ptFieldName    = $this->input->post('tFieldName');
        $tCode          = $this->input->post('tCode');

        //supawat 13-04-2020 เพิ่มไว้ เพราะมันต้องเช็คที่สาขาด้วย
        $tFiledBch      = $this->input->post('tFiledBch');
        if($tFiledBch == '' || $tFiledBch == null){
            $tFiledBch = '';
        }else{
            $tFiledBch = $tFiledBch;
        }

        $tCheck = FCNaHCheckInputGenCode($tTableName, $ptFieldName, $tCode , $tFiledBch);

        $nNum = $tCheck[0]->nNum;

        if ($nNum != '0') {
            $nStatus = '1';
            $tDesc = 'มี id นี้แล้วในระบบ';
        } else {
            $nStatus = '2';
            $tDesc = 'รหัสผ่านจะถูก Gen ใหม่';
        }

        $raChkCode = array(
            'rtCode' => $nStatus,
            'rtDesc' => $tDesc,
        );
        print_r(json_encode($raChkCode));
    }

    public function FCNtCCMMChangeLangList() {

        $tTableName = $this->input->post('tTableName');

        $aResLangList = FCNaHGetAllLangByTable($tTableName);
        $nLang = count($aResLangList);
        $oDrpdwnLangEdit = '';
        $nSesLangEdit = $this->session->userdata("tLangEdit");
        if ($nLang > 1) {
            $oDrpdwnLangEdit .= "<div class='form-group'>";
            $oDrpdwnLangEdit .= "<div class='dropup' style='float: right;'>";
            $oDrpdwnLangEdit .= "<a href='javascript:void(0)' class='dropdown-toggle' data-toggle='dropdown' aria-expanded='true'><img class='xWLogoEditLang' src='" . base_url('application/modules/common/assets/images/use/' . $_SESSION['tLangEdit'] . ".png") . "' >  " . language('common/main/main', 'tLanguageType' . $_SESSION['tLangEdit']) . "<b class='caret'></b></a>";
            $oDrpdwnLangEdit .= "<ul class='dropdown-menu xWdropdown-menu'>";
            foreach ($aResLangList AS $aKey => $nValue) {
                $active = '';
                if ($nValue->nLangList == $nSesLangEdit) {
                    $active = "class='active'";
                }
                $oDrpdwnLangEdit .= "<li $active $nValue->nLangList><a onclick=JSvChangLangEdit('$nValue->nLangList')>";
                $oDrpdwnLangEdit .= "<img src='" . base_url() . "application/modules/common/assets/images/use/$nValue->nLangList.png'>  " . language('common/main/main', 'tLanguageType' . $nValue->nLangList) . "</a></li>";
            }
            $oDrpdwnLangEdit .= "</ul>";
            $oDrpdwnLangEdit .= "</div>";
            $oDrpdwnLangEdit .= "</div>";
        }

        echo $oDrpdwnLangEdit;
    }

    function FCNtCCMMGetLangSystem() {

        $tFSName = $this->input->post('tFSName');
        if ($tFSName == '') {
            $tFSName = 'null';
        }
        $tCode = $this->input->post('tCode');

        $aResLangList = FCNaHGetAllLangInSystem();
        $oDrpdwnLangEdit = '';

        if ($aResLangList[0]->FNLngID != '') {
            $oDrpdwnLangEdit .= "<div class='form-group'>";
            $oDrpdwnLangEdit .= "<div class='dropup' style='float: right;'>";
            $oDrpdwnLangEdit .= "<a href='javascript:void(0)' class='dropdown-toggle' data-toggle='dropdown' aria-expanded='true'><img class='xWLogoEditLang' src='" . base_url('application/modules/common/assets/images/use/' . $_SESSION['tLangEdit'] . ".png") . "' >  " . language('common/main/main', 'tLanguageType' . $_SESSION['tLangEdit']) . "<b class='caret'></b></a>";
            $oDrpdwnLangEdit .= "<ul class='dropdown-menu xWdropdown-menu'>";
            foreach ($aResLangList AS $aKey => $nValue) {

                $oDrpdwnLangEdit .= "<li><a onclick=JSvChangLangPageAddEdit('$nValue->FNLngID','$tFSName',$tFSName,'$tCode')>";
                $oDrpdwnLangEdit .= "<img src='" . base_url() . "application/modules/common/assets/images/use/$nValue->FNLngID.png'>  " . language('common/main/main', 'tLanguageType' . $nValue->FNLngID) . "</a></li>";
            }
            $oDrpdwnLangEdit .= "</ul>";
            $oDrpdwnLangEdit .= "</div>";
            $oDrpdwnLangEdit .= "</div>";
        }

        echo $oDrpdwnLangEdit;
    }
    
    function FStGetVateActiveByVatCode(){
        $tVatCode = $this->input->post('tVatCode');
        $oVatActive = FCNoHVatActiveList($tVatCode);
        
        $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($oVatActive));
    }


    //Functionality : Function Get Massage RabbitMQ
    //Parameters : Ajax input type post
    //Creator : 5/11/2020
    //Return : text
    //Return Type : srting
    public function FCNtCCMMGetMassageProgress(){

        $aParam['tQname'] = $this->input->post('tQName');
        $tProgress =  FCNxRabbitMQGetMassage($aParam);
        echo $tProgress;
        
    }

}

