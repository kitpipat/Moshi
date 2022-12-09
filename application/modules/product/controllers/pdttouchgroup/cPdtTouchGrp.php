<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cPdtTouchGrp extends MX_Controller {

    public function __construct(){
        parent::__construct ();
        $this->load->model('product/pdttouchgroup/mPdtTouchGrp');
    }

    public function index($nTcgBrowseType,$tTcgBrowseOption){
        $nMsgResp   = array('title'=>"ProductTouch Grroup");
        $isXHR      = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) === 'XMLHTTPREQUEST';
        if(!$isXHR){
            $this->load->view ( 'common/wHeader', $nMsgResp);
            $this->load->view ( 'common/wTopBar', array ('nMsgResp'=>$nMsgResp));
            $this->load->view ( 'common/wMenu', array ('nMsgResp'=>$nMsgResp));
        }

        $vBtnSave                   = FCNaHBtnSaveActiveHTML('pdttouchgrp/0/0'); //Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
        $aAlwEventPdtTouchGrp	    = FCNaHCheckAlwFunc('pdttouchgrp/0/0');
        $this->load->view('product/pdttouchgroup/wPdtTouchGrp', array (
            'nMsgResp'              => $nMsgResp,
            'vBtnSave'              => $vBtnSave,
            'nTcgBrowseType'        => $nTcgBrowseType,
            'tTcgBrowseOption'      => $tTcgBrowseOption,
            'aAlwEventPdtTouchGrp'  => $aAlwEventPdtTouchGrp
        ));
    }

    //Functionality : Function Call Product Touch Group Page List
    //Parameters : Ajax and Function Parameter
    //Creator : 19/09/2018 wasin
    //Return : String View
    //Return Type : View
    public function FSvCTCGListPage(){
        $aAlwEventPdtTouchGrp	    = FCNaHCheckAlwFunc('pdttouchgrp/0/0');
        $this->load->view('product/pdttouchgroup/wPdtTouchGrpList', array(
            'aAlwEventPdtTouchGrp'  => $aAlwEventPdtTouchGrp
        ));
    }

    //Functionality : Function Call DataTables Product Touch Group 
    //Parameters : Ajax Call View DataTable
    //Creator : 19/09/2018 wasin
    //Return : String View
    //Return Type : View
    public function FSvCTCGDataList(){
        try{
            $tSearchAll     = $this->input->post('tSearchAll');
            $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');   // Check Number Page
            $nLangResort    = $this->session->userdata("tLangID");
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $aLangHave      = FCNaHGetAllLangByTable('TCNMPdtTouchGrp_L');
            $nLangHave      = count($aLangHave);
            if($nLangHave > 1){
                $nLangEdit  = ($nLangEdit != '')? $nLangEdit : $nLangResort;
            }else{
                $nLangEdit  = (@$aLangHave[0]->nLangList == '')? '1' : $aLangHave[0]->nLangList;
            }
            $aData  = array(
                'nPage'         => $nPage,
                'nRow'          => 10,
                'FNLngID'       => $nLangEdit,
                'tSearchAll'    => $tSearchAll
            );

            $aTcgDataList   = $this->mPdtTouchGrp->FSaMTCGList($aData);
            $aAlwEventPdtTouchGrp	    = FCNaHCheckAlwFunc('pdttouchgrp/0/0');
            $aGenTable  = array(
                'aTcgDataList'          => $aTcgDataList,
                'nPage'                 => $nPage,
                'tSearchAll'            => $tSearchAll,
                'aAlwEventPdtTouchGrp'  => $aAlwEventPdtTouchGrp
            );
            $this->load->view('product/pdttouchgroup/wPdtTouchGrpDataTable',$aGenTable);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Function CallPage Product Touch Group Add
    //Parameters : Ajax Call View Add
    //Creator : 19/09/2018 wasin
    //Return : String View
    //Return Type : View
    public function FSvCTCGAddPage(){
        try{
            $aDataPdtTouchGrp = array(
                'nStaAddOrEdit'   => 99
            );
            $this->load->view('product/pdttouchgroup/wPdtTouchGrpAdd',$aDataPdtTouchGrp);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Function CallPage Product Touch Group Edits
    //Parameters : Ajax Call View Add
    //Creator : 20/09/2018 wasin
    //Return : String View
    //Return Type : View
    public function FSvCTCGEditPage(){
        try{
            $tTcgCode       = $this->input->post('tTcgCode');
            $nLangResort    = $this->session->userdata("tLangID");
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $aLangHave      = FCNaHGetAllLangByTable('TCNMPdtTouchGrp_L');
            $nLangHave      = count($aLangHave);
            if($nLangHave > 1){
                $nLangEdit  = ($nLangEdit != '')? $nLangEdit : $nLangResort;
            }else{
                $nLangEdit  = (@$aLangHave[0]->nLangList == '')? '1' : $aLangHave[0]->nLangList;
            }

            $aData  = array(
                'FTTcgCode' => $tTcgCode,
                'FNLngID'   => $nLangEdit
            );
            $aTcgData       = $this->mPdtTouchGrp->FSaMTCGGetDataByID($aData);
            $aDataPdtTouchGrp   = array(
                'nStaAddOrEdit' => 1,
                'aTcgData'      => $aTcgData
            );
            $this->load->view('product/pdttouchgroup/wPdtTouchGrpAdd',$aDataPdtTouchGrp);

        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Event Add Product Touch Group
    //Parameters : Ajax Event
    //Creator : 20/09/2018 wasin
    //Return : Status Add Event
    //Return Type : String
    public function FSoCTCGAddEvent(){
        try{
            $aDataPdtTouchGrp   = array(
                'tIsAutoGenCode'    => $this->input->post('ocbTcgAutoGenCode'),
                'FTTcgCode'     => $this->input->post('oetTcgCode'),
                'FTTcgStaUse'   => $this->input->post('ocmTcgStaUse'),
                'FTTcgName'     => $this->input->post('oetTcgName'),
                'FTTcgRmk'      => $this->input->post('otaTcgRmk'),
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                'FNLngID'       => $this->session->userdata("tLangEdit")
            );
            // Setup Reason Code
            if($aDataPdtTouchGrp['tIsAutoGenCode'] == '1'){ // Check Auto Gen Reason Code?
                // Auto Gen Reason Code
                $aGenCode = FCNaHGenCodeV5('TCNMPdtTouchGrp');
                if($aGenCode['rtCode'] == '1'){
                    $aDataPdtTouchGrp['FTTcgCode'] = $aGenCode['rtTcgCode'];
                }
            }
            $this->db->trans_begin();
            $aStaTcgMaster  = $this->mPdtTouchGrp->FSaMTCGAddUpdateMaster($aDataPdtTouchGrp);
            $aStaTcgLang    = $this->mPdtTouchGrp->FSaMTCGAddUpdateLang($aDataPdtTouchGrp);
            if($this->db->trans_status() === false){
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Add Product Touch Group."
                );
            }else{
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'	=> $aDataPdtTouchGrp['FTTcgCode'],
                    'nStaEvent'	    => '1',
                    'tStaMessg'		=> 'Success Add Product Touch Group.'
                );
            }
            echo json_encode($aReturn);            
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Event Edit Product Touch Group
    //Parameters : Ajax Event
    //Creator : 20/09/2018 wasin
    //Return : Status Edit Event
    //Return Type : String
    public function FSoCTCGEditEvent(){
        try{
            $this->db->trans_begin();
            $aDataPdtTouchGrp   = array(
                'FTTcgCode'     => $this->input->post('oetTcgCode'),
                'FTTcgStaUse'   => $this->input->post('ocmTcgStaUse'),
                'FTTcgName'     => $this->input->post('oetTcgName'),
                'FTTcgRmk'      => $this->input->post('otaTcgRmk'),
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                'FNLngID'       => $this->session->userdata("tLangEdit")
            );
            $aStaTcgMaster  = $this->mPdtTouchGrp->FSaMTCGAddUpdateMaster($aDataPdtTouchGrp);
            $aStaTcgLang    = $this->mPdtTouchGrp->FSaMTCGAddUpdateLang($aDataPdtTouchGrp);
            if($this->db->trans_status() === false){
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Edit Product Type"
                );
            }else{
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'	=> $aDataPdtTouchGrp['FTTcgCode'],
                    'nStaEvent'	    => '1',
                    'tStaMessg'		=> 'Success Edit Product Type'
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Event Delete Product Touch Group
    //Parameters : Ajax Event Delete
    //Creator : 20/09/2018 wasin
    //Return : Status Delete Event
    //Return Type : String
    public function FSoCTCGDeleteEvent(){
        $tIDCode = $this->input->post('tIDCode');
        $aDataMaster = array(
            'FTTcgCode' => $tIDCode
        );
        $aResDel    = $this->mPdtTouchGrp->FSaMTCGDelAll($aDataMaster);
        $nNumRowTCG = $this->mPdtTouchGrp->FSnMTouchGrpGetAllNumRow();
        if($nNumRowTCG!==false){
            $aReturn    = array(
                'nStaEvent' => $aResDel['rtCode'],
                'tStaMessg' => $aResDel['rtDesc'],
                'nNumRowTCG' => $nNumRowTCG
            );
            echo json_encode($aReturn);
        }else{
            echo "database error!";
        }
    }







































}