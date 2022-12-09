<?php 
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cVoucher extends MX_Controller {

	public function __construct() {
            parent::__construct ();
            $this->load->model('voucher/mVoucher');
    }

    public function index($nBrowseType,$tBrowseOption){

        $aData['nBrowseType']        = $nBrowseType;
        $aData['tBrowseOption']      = $tBrowseOption;
		$aData['aAlwEventVoucher']   = FCNaHCheckAlwFunc('voucher/0/0'); //Controle Event
        $aData['vBtnSave']           = FCNaHBtnSaveActiveHTML('voucher/0/0'); //Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน

        $this->load->view('promotion/voucher/wVoucher',$aData);

    }

    
    //Functionality : Event AddPage
    //Parameters : -
    //Creator : 25/04/2019 saharat(Golf)
    //Last Modified : -
    //Return : AddPage
    //Return Type : view
    public function FSxCVOCAddPage(){

        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aLangHave = FCNaHGetAllLangByTable('TFNMVoucher_L');
        $nLangHave = count($aLangHave);
        if($nLangHave > 1){
	        if($nLangEdit != ''){
	            $nLangEdit = $nLangEdit;
	        }else{
	            $nLangEdit = $nLangResort;
	        }
	    }else{
	        if(@$aLangHave[0]->nLangList == ''){
	            $nLangEdit = '1';
	        }else{
	            $nLangEdit = $aLangHave[0]->nLangList;
	        }
        }
        
        $aData  = array(
            'FNLngID'   => $nLangEdit,
        );

        $aDataAdd = array(
            'aResult'   => array('rtCode'=>'99')
        );

        $this->load->view('promotion/voucher/wVoucherAdd',$aDataAdd);
    }

    public function FSxCVOCFormSearchList(){
        $this->load->view('promotion/voucher/wVoucherFormSearchList');
    }

    //Functionality : Event Add Voucher
    //Parameters : Ajax jReason()
    //Creator : 03/07/2018 Krit(Krit)
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCVOCAddEvent(){
     
        $oetVocValue = $this->input->post('oetVocValue');
        $oetVocSalePri = $this->input->post('oetVocSalePri');
        $oedVocExpired = $this->input->post('oedVocExpired');
        if($oetVocValue == ''){ $oetVocValue = 0; }
        if($oetVocSalePri == ''){ $oetVocSalePri = 0; }
        if($oedVocExpired == ''){ $oedVocExpired = date('Y-m-d'); }

     
        try{
            $aDataMaster = array(
                'tIsAutoGenCode' => $this->input->post('ocbVoucherAutoGenCode'),
                'FTVocCode' => $this->input->post('oetVocCode'),
                'FTVocName' => $this->input->post('oetVocName'),
                'FTVocRemark' => $this->input->post('otaVocRemark'),
                'FTVocBarCode' => $this->input->post('oetVocBarCode'),
                'FDVocExpired' => $oedVocExpired,
                'FTVotCode' => $this->input->post('ohdVotCode'),
                'FCVocValue' => $oetVocValue,
                'FCVocSalePri' => $oetVocSalePri,
                'FCVocBalance' => $this->input->post('oetVocBalance'),
                'FTVocComBook' => $this->input->post('oetVocComBook'),
                'FTVocStaBook' => $this->input->post('oetVocStaBook'),
                'FTVocStaSale' => $this->input->post('oetVocStaSale'),
                'FTVocStaUse' => $this->input->post('oetVocStaUse'),
                'FDDateIns' => date('Y-m-d'),
                'FDDateUpd' => date('Y-m-d'),
                'FTTimeIns' => date('h:i:s'),
                'FTTimeUpd' => date('h:i:s'),
                'FTWhoIns'  => $this->session->userdata('tSesUsername'),
                'FTWhoUpd'  => $this->session->userdata('tSesUsername'),
                'FNLngID'   => $this->session->userdata("tLangEdit"),
            );
           
            if($aDataMaster['tIsAutoGenCode'] == '1'){ // Check Auto Gen Department Code?
                // Auto Gen Department Code
                $aGenCode = FCNaHGenCodeV5('TFNMVoucher','0');
                if($aGenCode['rtCode'] == '1'){
                    $aDataMaster['FTVocCode'] = $aGenCode['rtVocCode'];
                }
            }
       

            $oCountDup  = $this->mVoucher->FSnMVOCCheckDuplicate($aDataMaster['FTVocCode']);
            $nStaDup    = $oCountDup[0]->counts;

    

            if($nStaDup == 0){
                $this->db->trans_begin();
                $aStaEventMaster  = $this->mVoucher->FSaMVOCAddUpdateMaster($aDataMaster);
                $aStaEventLang    = $this->mVoucher->FSaMVOCAddUpdateLang($aDataMaster);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'	=> $aDataMaster['FTVocCode'],
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Event'
                    );
                }
            }else{
                $aReturn = array(
                    'nStaEvent'    => '801',
                    'tStaMessg'    => "Data Code Duplicate"
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }


    //Functionality : Event Edit Voucher
    //Parameters : Ajax jReason()
    //Creator : 02/07/2018 Krit(Copter)
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCVOCEditEvent(){

        $oetVocValue = $this->input->post('oetVocValue');
        $oetVocSalePri = $this->input->post('oetVocSalePri');
        $oedVocExpired = $this->input->post('oedVocExpired');
        if($oetVocValue == ''){ $oetVocValue = 0; }
        if($oetVocSalePri == ''){ $oetVocSalePri = 0; }
        if($oedVocExpired == ''){ $oedVocExpired = date('Y-m-d'); }
        
        try{
            $aDataMaster = array(
                'FTVocCode' => $this->input->post('oetVocCode'),
                'FTVocName' => $this->input->post('oetVocName'),
                'FTVocRemark' => $this->input->post('otaVocRemark'),
                'FTVocBarCode' => $this->input->post('oetVocBarCode'),
                'FDVocExpired' => $oedVocExpired,
                'FTVotCode' => $this->input->post('ohdVotCode'),
                'FCVocValue' => $oetVocValue,
                'FCVocSalePri' => $oetVocSalePri,
                'FCVocBalance' => $this->input->post('oetVocBalance'),
                'FTVocComBook' => $this->input->post('oetVocComBook'),
                'FTVocStaBook' => $this->input->post('oetVocStaBook'),
                'FTVocStaSale' => $this->input->post('oetVocStaSale'),
                'FTVocStaUse' => $this->input->post('oetVocStaUse'),
                'FDDateIns' => date('Y-m-d'),
                'FDDateUpd' => date('Y-m-d'),
                'FTTimeIns' => date('h:i:s'),
                'FTTimeUpd' => date('h:i:s'),
                'FTWhoIns'  => $this->session->userdata('tSesUsername'),
                'FTWhoUpd'  => $this->session->userdata('tSesUsername'),
                'FNLngID'   => $this->session->userdata("tLangEdit"),
            );
            $this->db->trans_begin();
            $aStaEventMaster  = $this->mVoucher->FSaMVOCAddUpdateMaster($aDataMaster);
            $aStaEventLang    = $this->mVoucher->FSaMVOCAddUpdateLang($aDataMaster);
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Add Event"
                );
            }else{
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'	=> $aDataMaster['FTVocCode'],
                    'nStaEvent'	    => '1',
                    'tStaMessg'		=> 'Success Add Event'
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
      
    }

    //Functionality : Event Delete Voucher
    //Parameters : Ajax jReason()
    //Creator : 03/07/2018 Krit(Copter)
    //Last Modified : -
    //Return : Status Delete Event
    //Return Type : String
    public function FSaCVOCDeleteEvent(){
        $tIDCode = $this->input->post('tIDCode');
        $aDataMaster = array(
            'FTVocCode' => $tIDCode
        );

        $aResDel    = $this->mVoucher->FSnMVOCDel($aDataMaster);
        $aReturn    = array(
            'nStaEvent' => $aResDel['rtCode'],
            'tStaMessg' => $aResDel['rtDesc']
        );
        echo json_encode($aReturn);
    }


    public function FSvCVOCEditPage(){

		$aAlwEventVoucher	= FCNaHCheckAlwFunc('voucher/0/0'); //Controle Event

        $tVocCode       = $this->input->post('tVocCode');
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aLangHave      = FCNaHGetAllLangByTable('TFNMVoucher_L');
        $nLangHave      = count($aLangHave);
        
        if($nLangHave > 1){
	        if($nLangEdit != ''){
	            $nLangEdit = $nLangEdit;
	        }else{
	            $nLangEdit = $nLangResort;
	        }
	    }else{
	        if(@$aLangHave[0]->nLangList == ''){
	            $nLangEdit = '1';
	        }else{
	            $nLangEdit = $nLangEdit;
	        }
        }
        
        $aData  = array(
            'FTVocCode' => $tVocCode,
            'FNLngID'   => $nLangEdit
        );

        $aResult       = $this->mVoucher->FSaMVOCSearchByID($aData);
        $aDataEdit      = array('aResult'           => $aResult,
                                'aAlwEventVoucher'   => $aAlwEventVoucher
                            );
        $this->load->view('promotion/voucher/wVoucherAdd',$aDataEdit);

    }


    //Functionality : Function Call DataTables Voucher
    //Parameters : Ajax jBranch()
    //Creator : 03/07/2018 Krit(Copter)
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCVOCDataTable(){

        $aAlwEvent = FCNaHCheckAlwFunc('voucher/0/0'); //Controle Event
        
        $nPage = $this->input->post('nPageCurrent');
        $tSearchAll = $this->input->post('tSearchAll');
        if($nPage == '' || $nPage == null){$nPage = 1;}else{$nPage = $this->input->post('nPageCurrent');}
        if(!$tSearchAll){$tSearchAll='';}
        
        //Lang ภาษา
        $nLangResort    = $this->session->userdata("tLangID");
	    $nLangEdit      = $this->session->userdata("tLangEdit");
	    $aLangHave      = FCNaHGetAllLangByTable('TFNMVoucher_L');
        $nLangHave      = count($aLangHave);
        if($nLangHave > 1){
	        if($nLangEdit != ''){
	            $nLangEdit = $nLangEdit;
	        }else{
	            $nLangEdit = $nLangResort;
	        }
	    }else{
	        if(@$aLangHave[0]->nLangList == ''){
	            $nLangEdit = '1';
	        }else{
	            $nLangEdit = $aLangHave[0]->nLangList;
            }
            
        }
    
        $aData  = array(
            'nPage'         => $nPage,
            'nRow'          => 10,
            'FNLngID'       => $nLangEdit,
            'tSearchAll'    => $tSearchAll
        );

        $aResList   = $this->mVoucher->FSaMVOCList($aData);
        $aGenTable  = array(

            'aAlwEvent' => $aAlwEvent,
            'aDataList' => $aResList,
            'nPage'     => $nPage,
            'tSearchAll'    => $tSearchAll
        );

        $this->load->view('promotion/voucher/wVoucherDataTable',$aGenTable);
    }


}