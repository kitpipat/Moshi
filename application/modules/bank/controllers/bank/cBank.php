<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cBank extends MX_Controller {

    public function __construct(){
        parent::__construct ();
        $this->load->model('bank/bank/mBank');
    }

    public function index($nBrowseType,$tBrowseOption){

        $aData['nBrowseType']       = $nBrowseType;
        $aData['tBrowseOption']     = $tBrowseOption;
		$aData['aAlwEventBank']   = FCNaHCheckAlwFunc('bankindex/0/0'); //Controle Event
        $aData['vBtnSave']          = FCNaHBtnSaveActiveHTML('bankindex/0/0'); //Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
        
        $this->load->view('bank/bank/wBankIndex',$aData);
    }

    public function FSxCBNKAddPage(){

        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aLangHave = FCNaHGetAllLangByTable('TFNMBank_L');
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

        $this->load->view('bank/bank/wBankAdd',$aDataAdd);
    }


    public function FSxCBNKFormSearchList(){
           $this->load->view('bank/bank/wBankFormSearchList');
    }

    
    //Functionality : Event Add District
    //Parameters : Ajax jReason()
    //Creator : 15/05/2018 wasin
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCBNKAddEvent(){
        try{
            $aDataMaster = array(
                'FTBnkCode' => $this->input->post('oetBnkCode'),
                'FTBnkName' => $this->input->post('oetBnkName'),
                'FTBnkRmk' => $this->input->post('otaBnkRmk'),
                'FDLastUpdOn' => date('Y-m-d'),
                'FTWhoIns'  => $this->session->userdata('tSesUsername'),
                'FTWhoUpd'  => $this->session->userdata('tSesUsername'),
                'FNLngID'   => $this->session->userdata("tLangEdit")
            );
            $oCountDup  = $this->mBank->FSnMBNKCheckDuplicate($aDataMaster['FTBnkCode']);
            $nStaDup    = $oCountDup[0]->counts;
            if($nStaDup == 0){
                $this->db->trans_begin();
                $aStaEventMaster  = $this->mBank->FSaMBNKAddUpdateMaster($aDataMaster);
                $aStaEventLang    = $this->mBank->FSaMBNKAddUpdateLang($aDataMaster);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event 1"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'	=> $aDataMaster['FTBnkCode'],
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

    //Functionality : Event Edit Bank
    //Parameters : Ajax jReason()
    //Creator : 02/07/2018 Krit(Copter)
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    public function FSaCBNKEditEvent(){
        try{
            $aDataMaster = array(
                'FTBnkCode'             => $this->input->post('oetBnkCode'),
                'FTBnkName'             => $this->input->post('oetBnkName'),
                'FTBnkRmk'              => $this->input->post('otaBnkRmk'),
                'FDLastUpdOn'           => date('Y-m-d'),
                'FNLngID'               => $this->session->userdata("tLangEdit")
            );

            $this->db->trans_begin();
            $aStaEventMaster  = $this->mBank->FSaMBNKAddUpdateMaster($aDataMaster);
            $aStaEventLang    = $this->mBank->FSaMBNKAddUpdateLang($aDataMaster);
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
                    'tCodeReturn'	=> $aDataMaster['FTBnkCode'],
                    'nStaEvent'	    => '1',
                    'tStaMessg'		=> 'Success Add Event'
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
      
    }


    //Functionality : Event Delete Bank
    //Parameters : Ajax jReason()
    //Creator : 03/07/2018 Krit(Copter)
    //Last Modified : -
    //Return : Status Delete Event
    //Return Type : String
    public function FSaCBNKDeleteEvent(){
        $tIDCode = $this->input->post('tIDCode');
        $aDataMaster = array(
            'FTBnkCode' => $tIDCode
        );

        $aResDel        = $this->mBank->FSnMBNKDel($aDataMaster);
        $aReturn    = array(
            'nStaEvent' => $aResDel['rtCode'],
            'tStaMessg' => $aResDel['rtDesc']
        );
        echo json_encode($aReturn);
    }


    public function FSvCBNKEditPage(){

		$aAlwEventBank	= FCNaHCheckAlwFunc('wBankIndex/0/0'); //Controle Event

        $tBnkCode       = $this->input->post('tBnkCode');
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aLangHave      = FCNaHGetAllLangByTable('TFNMBank_L');
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
            'FTBnkCode' => $tBnkCode,
            'FNLngID'   => $nLangEdit
        );

        $aDstData       = $this->mBank->FSaMBNKSearchByID($aData);
        $aDataEdit      = array('aResult'       => $aDstData,
                                'aAlwEventBank' => $aAlwEventBank
                            );
        $this->load->view('pos5/bank/wBankAdd',$aDataEdit);

    }


	//Functionality : Function Call DataTables Bank
    //Parameters : Ajax jBranch()
    //Creator : 03/07/2018 Krit(Copter)
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCBNKDataTable(){

        $aAlwEvent = FCNaHCheckAlwFunc('wBankIndex/0/0'); //Controle Event
        
        $nPage = $this->input->post('nPageCurrent');
        $tSearchAll = $this->input->post('tSearchAll');
        if($nPage == '' || $nPage == null){$nPage = 1;}else{$nPage = $this->input->post('nPageCurrent');}
        if(!$tSearchAll){$tSearchAll='';}
        
        //Lang ภาษา
        $nLangResort    = $this->session->userdata("tLangID");
	    $nLangEdit      = $this->session->userdata("tLangEdit");
	    $aLangHave      = FCNaHGetAllLangByTable('TFNMBank_L');
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

        $aResList   = $this->mBank->FSaMBNKList($aData);
        $aGenTable  = array(

            'aAlwEvent' => $aAlwEvent,
            'aDataList' => $aResList,
            'nPage'     => $nPage,
            'tSearchAll'    => $tSearchAll
        );

        $this->load->view('bank/bank/wBankDatatable',$aGenTable);
    }

    
    /*
    //Functionality : Function CallGetdata Bank
    //Parameters : 
    //Creator : nonpawich
    //Last Modified : -
    //Return :  View
    //Return Type : View
    */
    public function FSxCBNKGetDataBank($nBrowseType,$tBrowseOption){

        $aData['nBrowseType']       = $nBrowseType;
        $aData['tBrowseOption']     = $tBrowseOption;
		$aData['aAlwEventBank']   = FCNaHCheckAlwFunc('bank/0/0'); //Controle Event
 
        $this->load->view('bank/bank/wBanklist',$aData);

    }
    
    /*
    //Functionality : Function Call ADD Bank
    //Parameters : 
    //Creator :  nonpawich
    //Last Modified : -
    //Return :  View
    //Return Type : View
    */
    public function FSaCAGNAddEvent(){
     
        

        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aLangHave = FCNaHGetAllLangByTable('TFNMBank_L');
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
    
        $this->load->view('bank/bank/wBankAddData',$aDataAdd);
   
       
    }


    /*
    //Functionality : Event Edit bank
    //Parameters : 
    //Creator : 
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    */

    public function FSaCBNKAddEditEventBank(){

        $tImgInputBankOld = $this->input->post('oetImgInputBankOld');
        $tImgInputBank    = $this->input->post('oetImgInputBank');
        $tBnkCodeOld      = $this->input->post('oetBnkCodeOld');
        // echo $tImgInputBank.''.$tImgInputBankOld;
        // exit;
        try{
            $aDataMaster = array(
                'tBnkCodeOld' => $tBnkCodeOld,
                'FTBnkCode' => $this->input->post('oetBnkCode'),
                'FTBnkName' => $this->input->post('oetBnkName'),
                'FTBnkRmk' => $this->input->post('otaBnkRmk'),
                'FDLastUpdOn' => date('Y-m-d'),
                'FTWhoIns'  => $this->session->userdata('tSesUsername'),
                'FTWhoUpd'  => $this->session->userdata('tSesUsername'),
                'FNLngID'   => $this->session->userdata("tLangEdit")
            );
            $oCountDup  = $this->mBank->FSnMBNKCheckDuplicate($aDataMaster['FTBnkCode']);
            $nStaDup    = $oCountDup[0]->counts;
            //  echo $nStaDup;
            //  die();
            if($nStaDup == 0){
                // $this->db->trans_begin();
                $aStaEventMaster  = $this->mBank->FSaMBnkAddAndUpdateMaster($aDataMaster);
                $aStaEventLang    = $this->mBank->FSaMBNKAddUpdateLang($aDataMaster);
                // if($this->db->trans_status() === false){
                    // $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '1',
                        'tStaMessg'    => "sucess Add Event"
                    );
                // }

                // echo $tImgInputBank.''.$tImgInputBankOld;
                // exit;
               if($tImgInputBank != $tImgInputBankOld){
                    $aImageUplode = array(
                        'tModuleName'       => 'bank',
                        'tImgFolder'        => 'bank',
                        'tImgRefID'         => $aDataMaster['FTBnkCode'] ,
                        'tImgObj'           => $tImgInputBank,
                        'tImgTable'         => 'TFNMBank',
                        'tTableInsert'      => 'TCNMImgObj',
                        'tImgKey'           => 'main',
                        'dDateTimeOn'       => date('Y-m-d H:i:s'),
                        'tWhoBy'            => $this->session->userdata('tSesUsername'),
                        'nStaDelBeforeEdit' => 1
                    );
                    $aResAddImgObj	= FCNnHAddImgObj($aImageUplode);
                    // print_r( $aImageUplode);
                //   /
                }

             
                
                else{
                    // $this->db->trans_commit();
                    $aReturn = array(
                        'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'	=> $aDataMaster['FTBnkCode'],
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


    
    /*
    //Functionality : Event Edit bank
    //Parameters : 
    //Creator : 
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : String
    */

    public function FSaCBNKEditEventBank(){
        $tImgInputBankOld = $this->input->post('oetImgInputBankOld');
        $tImgInputBank    = $this->input->post('oetImgInputBank');
        $tBnkCodeOld      = $this->input->post('oetBnkCodeOld');
        
        try{
            $aDataMaster = array(
                'tBnkCodeOld'  => $tBnkCodeOld,
                'FTBnkCode' => $this->input->post('oetBnkCode'),
                'FTBnkName' => $this->input->post('oetBnkName'),
                'FTBnkRmk' => $this->input->post('otaBnkRmk'),
                'FDLastUpdOn' => date('Y-m-d'),
                'FTWhoIns'  => $this->session->userdata('tSesUsername'),
                'FTWhoUpd'  => $this->session->userdata('tSesUsername'),
                'FNLngID'   => $this->session->userdata("tLangEdit")
            );
            
            $oCountDup  = $this->mBank->FSnMBNKCheckDuplicate($aDataMaster['FTBnkCode']);
            $nStaDup    = $oCountDup[0]->counts;
            if($nStaDup==0){
                $this->db->trans_begin();
                $aStaEventMaster  = $this->mBank->FSaMBnkAddAndUpdateMaster($aDataMaster);
                $aStaEventLang    = $this->mBank->FSaMBNKAddUpdateLang($aDataMaster);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                }else{

                    if($tImgInputBank != $tImgInputBankOld){
                        $aImageUplode = array(
                            'tModuleName'       => 'bank',
                            'tImgFolder'        => 'bank',
                            'tImgRefID'         => $aDataMaster['FTBnkCode'] ,
                            'tImgObj'           => $tImgInputBank,
                            'tImgTable'         => 'TFNMBank',
                            'tTableInsert'      => 'TCNMImgObj',
                            'tImgKey'           => 'main',
                            'dDateTimeOn'       => date('Y-m-d H:i:s'),
                            'tWhoBy'            => $this->session->userdata('tSesUsername'),
                            'nStaDelBeforeEdit' => 1
                        );
                        $aResAddImgObj	= FCNnHAddImgObj($aImageUplode);
                    
                    }


                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'	=> $aDataMaster['FTBnkCode'],
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

       // Functionality : โหลด View Lsit 
    // Parameters :  route
    // Creator : 07/06/2019 saharat(Golf)
    // Last Modified : -
    // Return : view
    // Return Type : view
    public function FSvBnkList(){

 
        $aAlwEventBank	= FCNaHCheckAlwFunc('bankindex/0/0');
        $aNewData  			= array( 'aAlwEventBank' => $aAlwEventBank);
        $this->load->view('bank/bank/wBanklist',$aNewData);
    
    }


    // Functionality : โหลด View หลัก และแสดงข้อมูล bank
    // Parameters :  route
    // Creator : nonpawich
    // Last Modified : -
    // Return : view
    // Return Type : view
    public function Bankindex($nBrowseType,$tBrowseOption){
        $aData['nBrowseType']       = $nBrowseType;
        $aData['tBrowseOption']     = $tBrowseOption;
	    $aData['aAlwEventBank']   = FCNaHCheckAlwFunc('bankindex/0/0'); //Controle Event
        $aData['vBtnSave']          = FCNaHBtnSaveActiveHTML('bankindex/0/0'); //Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
        $this->load->view('bank/bank/wBankIndex',$aData);
    }


     //Functionality : Function Call DataTables 
    //Parameters : 
    //Creator : nonpawich
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSxCBnkGetDataTable(){

        $aAlwEvent = FCNaHCheckAlwFunc('bankindex/0/0'); //Controle Event
        $nPage = $this->input->post('nPageCurrent');
        $tSearchAll = $this->input->post('tSearchAll');
        if($nPage == '' || $nPage == null){$nPage = 1;}else{$nPage = $this->input->post('nPageCurrent');}
        if(!$tSearchAll){$tSearchAll='';}
        //Lang ภาษา
	    $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData  = array(
            'nPage'         => $nPage,
            'nRow'          => 10,
            'FNLngID'       => $nLangEdit,
            'tSearchAll'    => $tSearchAll,
            'FTImgTable'     => 'TFNMBank'
            
        );
        $aResList   = $this->mBank->FSaMBnkListBank($aData);
        $aGenTable  = array(

            'aAlwEvent' => $aAlwEvent,
            'aDataList' => $aResList,
            'nPage'     => $nPage,
            'tSearchAll'    => $tSearchAll
        );
        $this->load->view('bank/bank/wBankTable2',$aGenTable);
    }


        //Functionality : Event Delete Agency
    //Parameters : Ajax jReason()
    //Creator : 11/06/2019 saharat(Golf)
    //Last Modified : -
    //Return : Status Delete Event
    //Return Type : String
    public function FSaCBnkDelete(){
        $tIDCode = $this->input->post('tIDCode');
        $aDataMaster = array(
            'FTBnkCode' => $tIDCode
        );
        $aResDel        = $this->mBank->FSnMBnkDelete($aDataMaster);
        $nNumRowAgn     = $this->mBank->FSnMBnkGetAllNumRow();
        $aDeleteImage = array(
                'tModuleName'  => 'bank',
                'tImgFolder'   => 'bank',
                'tImgRefID'    => $tIDCode ,
                'tTableDel'    => 'TCNMImgObj',
                'tImgTable'    => 'TFNMBank'
                );
        //ลบข้อมูลในตาราง         
        $nStaDelImgInDB = FSnHDelectImageInDB($aDeleteImage);
        if($nStaDelImgInDB == 1){
            //ลบรูปในโฟลเดอ
            FSnHDeleteImageFiles($aDeleteImage);
        }
        if($nNumRowAgn!==false){
            $aReturn    = array(
                'nStaEvent'  => $aResDel['rtCode'],
                'tStaMessg'  => $aResDel['rtDesc'],
                'nNumRowAgn' => $nNumRowAgn
            );
            echo json_encode($aReturn);
        }else{
            echo "database error!";
        }
    }

   
 /**
     * Functionality : Function CallPage Vatrate Edit
     * Parameters : Ajax Function Edit Vatrate
     * Creator : 14/06/2018 wasin
     * Last Modified : 30/08/2018 piya
     * Return : View
     * Return Type : View
     */
    public function FSvCBnkEdit(){
        
        $dGetDataNow    = date('Y-m-d');
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $tBnkCode = $this->input->post('tBnkCode');
        $aData  = array(
            'FTBnkCode' => $tBnkCode,
            'dGetDataNow'  => $dGetDataNow,
            'FNLngID'       => $nLangEdit
        );
        $aResList = $this->mBank->FSaMBNKSearchByID($aData);
       
        //   print_r($aResList);
        // exit;
        if(isset($aResList['raItems']['rtFTImgObj']) && !empty($aResList['raItems']['rtFTImgObj'])){
            $tImgObj        = $aResList['raItems']['rtFTImgObj'];
            $aImgObj        = explode("application/modules/",$tImgObj);
            $aImgObjName    = explode("/",$tImgObj);
            $tImgObjAll     = $aImgObj[1];
            $tImgName		= end($aImgObjName);
        }else{
            $tImgObjAll = "";
            $tImgName	= "";
        }
        // print_r($aResList);
        // exit;
        // vat rate by vat code
        $aDataEdit      = array(
            'tImgName'              => $tImgName,
            'tImgObjAll'            => $tImgObjAll,
            'aResult'       => $aResList,
            'dGetDataNow'   => $dGetDataNow
        );
        $this->load->view('bank/bank/wBankAddData', $aDataEdit);
    }
}    
?>

