<?php 
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cVouchertype extends MX_Controller {

	public function __construct() {
            parent::__construct ();
            $this->load->model('Vouchertype/mVouchertype');
    }

    public function index($nBrowseType,$tBrowseOption){

        $aData['nBrowseType']        = $nBrowseType;
        $aData['tBrowseOption']      = $tBrowseOption;
		$aData['aAlwEventVoucher']   = FCNaHCheckAlwFunc('Vouchertype/0/0'); //Controle Event
        $aData['vBtnSave']           = FCNaHBtnSaveActiveHTML('Vouchertype/0/0'); //Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน

        $this->load->view('promotion/Vouchertype/wVouchertype',$aData);


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
        $aLangHave = FCNaHGetAllLangByTable('TFNMVouchertype_L');
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

        $this->load->view('promotion/vouchertype/wVouchertypeAdd',$aDataAdd);
    }

     //Functionality : load page wVouchertypeFormSearchList
    //Parameters : -
    //Creator : 03/07/2018 saharat(golf)
    //Last Modified : -
    //Return : view
    //Return Type : veiw
    public function FSxCVOCFormSearchList(){
        $this->load->view('promotion/vouchertype/wVouchertypeFormSearchList');
    }

    //Functionality : Event Add VoucherType
    //Parameters : Ajax jReason()
    //Creator : 03/07/2018 Krit(Krit)
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCVOTAddEvent(){

        date_default_timezone_set("Asia/Bangkok");
        $oetVocValue   = $this->input->post('oetVocValue');
        $oetVocSalePri = $this->input->post('oetVocSalePri');
        $oedVocExpired = $this->input->post('oedVocExpired');
        $nCheck        = $this->input->post('ocbVotcheck');
        if($nCheck == ''){ $nCheck = 2; }
        if($oetVocValue == ''){ $oetVocValue = 0; }
        if($oetVocSalePri == ''){ $oetVocSalePri = 0; }
        if($oedVocExpired == ''){ $oedVocExpired = date('Y-m-d'); }

        try{

            $aDataMaster = array(
                'tIsAutoGenCode' => $this->input->post('ocbVoucherAutoGenCode'),
                'FTVotCode'   => $this->input->post('oetVotCode'),
                'FTVotName'   => $this->input->post('oetVotName'),
                'FTVotRemark' => $this->input->post('otaVotRemark'),
                'FTVotStaUse' => $nCheck,
                'FDVocExpired'=> $oedVocExpired,
                'FCVocValue'  => $oetVocValue,
                'FCVocSalePri'=> $oetVocSalePri,
                'FDCreateOn'  => date("Y-m-d H:i:s"),
                'FTLastUpdBy' => date("Y-m-d H:i:s"),
                'FDLastUpdOn' => date("Y-m-d H:i:s"),
                'FTCreateBy'  => $this->session->userdata('tSesUsername'),
                'FNLngID'     => $this->session->userdata("tLangEdit"),
            );
      
            if($aDataMaster['tIsAutoGenCode'] == '1'){ // Check Auto Gen Department Code?
                // Auto Gen Department Code
                $aGenCode = FCNaHGenCodeV5('TFNMVoucherType','0');
                if($aGenCode['rtCode'] == '1'){
                    $aDataMaster['FTVotCode'] = $aGenCode['rtVotCode'];
                }
            }
            
            $oCountDup  = $this->mVouchertype->FSnMVOCCheckDuplicate($aDataMaster['FTVotCode']);
            $nStaDup    = $oCountDup[0]->counts;


            if($nStaDup == 0){
                $this->db->trans_begin();
                $aStaEventMaster  = $this->mVouchertype->FSaMVOTAddUpdateMaster($aDataMaster);
                $aStaEventLang    = $this->mVouchertype->FSaMVOTAddUpdateLang($aDataMaster);
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
                        'tCodeReturn'	=> $aDataMaster['FTVotCode'],
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


    //Functionality : Event Edit VoucherType
    //Parameters : Ajax jReason()
    //Creator : 29/04/2019 saharat(golf)
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCVOTEditEvent(){

        date_default_timezone_set("Asia/Bangkok");
        $oetVocValue   = $this->input->post('oetVocValue');
        $oetVocSalePri = $this->input->post('oetVocSalePri');
        $oedVocExpired = $this->input->post('oedVocExpired');
        $nCheck        = $this->input->post('ocbVotcheck');
        if($nCheck == ''){ $nCheck = 2; }
        if($oetVocValue == ''){ $oetVocValue = 0; }
        if($oetVocSalePri == ''){ $oetVocSalePri = 0; }
        if($oedVocExpired == ''){ $oedVocExpired = date('Y-m-d'); }

        
        try{
            $aDataMaster = array(
                'tIsAutoGenCode' => $this->input->post('ocbVoucherAutoGenCode'),
                'FTVotCode'   => $this->input->post('oetVotCode'),
                'FTVotName'   => $this->input->post('oetVotName'),
                'FTVotRemark' => $this->input->post('otaVotRemark'),
                'FTVotStaUse' => $nCheck,
                'FDVocExpired'=> $oedVocExpired,
                'FCVocValue'  => $oetVocValue,
                'FCVocSalePri'=> $oetVocSalePri,
                'FDCreateOn'  => date("Y-m-d H:i:s"),
                'FTLastUpdBy' => date("Y-m-d H:i:s"),
                'FDLastUpdOn' => date("Y-m-d H:i:s"),
                'FTCreateBy'  => $this->session->userdata('tSesUsername'),
                'FNLngID'     => $this->session->userdata("tLangEdit"),
            );
            $this->db->trans_begin();
            $aStaEventMaster  = $this->mVouchertype->FSaMVOTAddUpdateMaster($aDataMaster);
            $aStaEventLang    = $this->mVouchertype->FSaMVOTAddUpdateLang($aDataMaster);
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
                    'tCodeReturn'	=> $aDataMaster['FTVotCode'],
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
    public function FSaCVOTDeleteEvent(){
        $tIDCode = $this->input->post('tIDCode');
        $aDataMaster = array(
            'FTVotCode' => $tIDCode
        );

        $aResDel    = $this->mVouchertype->FSnMVOCDel($aDataMaster);
        $aReturn    = array(
            'nStaEvent' => $aResDel['rtCode'],
            'tStaMessg' => $aResDel['rtDesc']
        );
        echo json_encode($aReturn);
    }


    public function FSvCVOTEditPage(){

		$aAlwEventVoucher	= FCNaHCheckAlwFunc('vouchertype/0/0'); //Controle Event

        $tVotCode       = $this->input->post('tVotCode');
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aLangHave      = FCNaHGetAllLangByTable('TFNMVoucherType_L');
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
            'FTVocCode' => $tVotCode,
            'FNLngID'   => $nLangEdit
        );

        $aResult       = $this->mVouchertype->FSaMVOTSearchByID($aData);
        $aDataEdit      = array('aResult'           => $aResult,
                                'aAlwEventVoucher'   => $aAlwEventVoucher
                            );
        $this->load->view('promotion/vouchertype/wVouchertypeAdd',$aDataEdit);

    }


    //Functionality : Function Call DataTables Voucher
    //Parameters : Ajax jBranch()
    //Creator : 03/07/2018 Krit(Copter)
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCVOTDataTable(){

        $aAlwEvent = FCNaHCheckAlwFunc('vouchertype/0/0'); //Controle Event
        
        $nPage = $this->input->post('nPageCurrent');
        $tSearchAll = $this->input->post('tSearchAll');
        if($nPage == '' || $nPage == null){$nPage = 1;}else{$nPage = $this->input->post('nPageCurrent');}
        if(!$tSearchAll){$tSearchAll='';}
        
        //Lang ภาษา
        $nLangResort    = $this->session->userdata("tLangID");
	    $nLangEdit      = $this->session->userdata("tLangEdit");
	    $aLangHave      = FCNaHGetAllLangByTable('TFNMVoucherType_L');
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

        $aResList   = $this->mVouchertype->FSaMVOTList($aData);
        $aGenTable  = array(

            'aAlwEvent' => $aAlwEvent,
            'aDataList' => $aResList,
            'nPage'     => $nPage,
            'tSearchAll'    => $tSearchAll
        );
        $this->load->view('promotion/vouchertype/wVouchertypeDataTable',$aGenTable);
    }


}