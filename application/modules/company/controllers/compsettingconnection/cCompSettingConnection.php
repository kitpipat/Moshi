<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cCompSettingConnection extends MX_Controller {
    public function __construct() {
        parent::__construct ();
        $this->load->model('company/compsettingconnection/mCompSettingConnection');
        date_default_timezone_set("Asia/Bangkok");
    }

    //Functionality : Function Call Page Main
	//Parameters : From Ajax File CompSettingConnect
	//Creator : 19/10/2019 Witsarut (Bell)
	//Last Modified : -
	//Return : String View
    //Return Type : View
    public function FSvCCompConnectMainPage(){
        
        $vBtnSaveGpCompSettingCon    = FCNaHBtnSaveActiveHTML('company/0/0');
        $aAlwEventCompSettingCon     = FCNaHCheckAlwFunc('company/0/0');

        //Get data CompCode
        $tCompCode  =    $this->input->post('tCompCode');

        $aCompCodeSetConnect = array(
            'tCompCode' =>  $tCompCode
        );

        $this->load->view('company/compsettingconnection/wCompSettingConnectMain',array(
            'vBtnSaveGpCompSettingCon'   => $vBtnSaveGpCompSettingCon,
            'aAlwEventCompSettingCon'    => $aAlwEventCompSettingCon,
            'aCompCodeSetConnect'       => $aCompCodeSetConnect
        ));
    }

    //Functionality : List Data 
	//Parameters : From Ajax File CompSettingConnection
	//Creator : 19/10/2019 Witsarut (Bell)
	//Last Modified : -
	//Return : String View
    //Return Type : View
    public function FSvCCompConnectDataList(){

        $tCompCode      = $this->input->post('tCompCode');
        $tSearchAll     = $this->input->post('tSearchAll');
        $nPage          = $this->input->post('nPageCurrent');
        $tUrlType       = $this->input->post('');

        if($nPage == '' || $nPage == null){$nPage = 1;}else{$nPage  = $this->input->post('nPageCurrent');}
        if(!$tSearchAll){$tSearchAll='';}

        //Lang ภาษา
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");

        //สิทธ
        $aAlwEventCompSettingCon     = FCNaHCheckAlwFunc('company/0/0');

        $aData = array(
            'FTUrlRefID'    => $tCompCode,
            'nPage'        => $nPage,
            'nRow'         => 10,
            'FNLngID'      => $nLangEdit,
            'tSearchAll'   => $tSearchAll,
        );

        $aResList    = $this->mCompSettingConnection->FSaMCompSetConnectDataList($aData);

        // Check URL Type
        $aCheckUrlType = $this->mCompSettingConnection->FSaMCompSetConCheckUrlType($aData);

        $aGenTable  = array(
            'aDataList'         => $aResList,
            'nPage'     	    => $nPage,
            'FTUrlRefID'        => $tCompCode,
            'aCheckUrlType'     => $aCheckUrlType,
            'aAlwEventCompSettingCon' => $aAlwEventCompSettingCon,
        );

       //Return Data View
       $this->load->view('company/compsettingconnection/wCompSettingConnectDataTable',$aGenTable);
    }


    //Functionality :  Load Page Add CompSettingConnection 
    //Parameters : 
    //Creator : 19/10/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : HTML View
    //Return Type : View
    public function FSvCCompConnectPageAdd(){
        
        $dGetDataNow    = date('Y-m-d');
        $dGetDataFuture = date('Y-m-d', strtotime('+1 year'));

        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");

        $vBtnSaveGpCompSettingCon    = FCNaHBtnSaveActiveHTML('company/0/0');
        $aAlwEventCompSettingCon     = FCNaHCheckAlwFunc('company/0/0');

        // Get CompCode
        $tCompCode =  $this->input->post('tCompCode');
        
        $aCompCodeSetAuthen     = array(
           'tCompCode' => $tCompCode,
        );

        $aDataAdd   = array(
            'aResult'       => array('rtCode'=>'99'),
            'vBtnSaveGpCompSettingCon'  => $vBtnSaveGpCompSettingCon,
            'aAlwEventCompSettingCon'   => $aAlwEventCompSettingCon,
            'aCompCodeSetAuthen'        => $aCompCodeSetAuthen,
            'dGetDataNow'               => $dGetDataNow,
            'dGetDataFuture'            => $dGetDataFuture
        );

        $this->load->view('company/compsettingconnection/wCompSettingConnectAdd',$aDataAdd);

    }

    //Functionality :  Load Page Edit CompSettingConnection 
    //Parameters : 
    //Creator : 12/09/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCCompConnectPageEdit(){
        
        $dGetDataNow                = date('Y-m-d');
        $dGetDataFuture             = date('Y-m-d', strtotime('+1 year'));
        $nLangEdit                  = $this->session->userdata("tLangEdit");
        $vBtnSaveGpCompSettingCon   = FCNaHBtnSaveActiveHTML('company/0/0');
        $aAlwEventCompSettingCon    = FCNaHCheckAlwFunc('company/0/0');
        $nLangResort                = $this->session->userdata("tLangID");
        $tCompCode                  = $this->input->post('tCompCode');
        $tUrlID                     = $this->input->post('tUrlID');

        $aData   = array(
            'FTUrlRefID'    => $tCompCode,
            'FNUrlID'       => $tUrlID,
            'FNLngID'       => $nLangEdit,
        );

        // TCNTUrlObject
        $aResult    = $this->mCompSettingConnection->FSaMCompGetConCheckID($aData);


        if(isset($aResult['raItems']['rtSetConImage']) && !empty($aResult['raItems']['rtSetConImage'])){
            $tImgObj        = $aResult['raItems']['rtSetConImage'];
            $aImgObj        = explode("application/modules/",$tImgObj);
            $aImgObjName    = explode("/",$tImgObj);
            $tImgObjAll		= $aImgObj[1];
            $tImgName		= end($aImgObjName);

        }else{
            $tImgObjAll     = "";
            $tImgName       = "";
        }

        $aCompCodeSetAuthen    = array(
            'tCompCode'     => $tCompCode
        );

        $aDataEdit      = array(
            'aResult'           => $aResult,
            'dGetDataNow'       => $dGetDataNow,
            'dGetDataFuture'    => $dGetDataFuture,
            'tImgObjAll'        => $tImgObjAll,
            'tImgName'          => $tImgName,
            'aCompCodeSetAuthen'        => $aCompCodeSetAuthen,
            'vBtnSaveGpCompSettingCon'  => $vBtnSaveGpCompSettingCon,
            'aAlwEventCompSettingCon'   => $aAlwEventCompSettingCon
        );

        $this->load->view('company/compsettingconnection/wCompSettingConnectAdd',$aDataEdit);
    }

    //Functionality : Function Add CompSettingConnection
    //Parameters : From Ajax File CompSettingConnection
    //Creator : 20/10/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : array
    public function FSaCCompConnectAddEvent(){
        try{
            $tRefIDSeq       =  $this->input->post('ohdCompCode');

            //imput Imge
            $tCompSettingConOld     = trim($this->input->post('oetImgInputCompSetConOld'));
            $tCompSettingCon        = trim($this->input->post('oetImgInputCompSetCon'));

            $nCountSeq   = $this->mCompSettingConnection->FSnMCompCountSeq();
            $nCountSeq   = $nCountSeq +1;


            $nUrlType    = $this->input->post('ocmUrlConnecttype');


            switch($nUrlType){
                case '9' :
                    // ลงตาราง TCNTUrlObject
                    $aDataUrlObj    = array(
                        'FTUrlRefID'    => $this->input->post('ohdCompCode'), 
                        'FNUrlSeq'      => $nCountSeq,
                        'FNUrlType'     => $this->input->post('ocmUrlConnecttype'),
                        'FTUrlTable'    => 'TCNMComp',
                        'FTUrlKey'      => $this->input->post('oetCompUrlKey'),
                        'FTUrlAddress'  => $this->input->post('oetCompServerip'),
                        'FTUrlPort'     => $this->input->post('oetCompPortConnect'),
                        'FTUrlLogo'     => $this->input->post('oetImgInputCompSetCon'),
                        'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                        'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                        'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                        'FDCreateOn'    => date('Y-m-d H:i:s'),
                    );
                break;
                case '10' :
                    // ลงตาราง TCNTUrlObject
                    $aDataUrlObj    = array(
                        'FTUrlRefID'    => $this->input->post('ohdCompCode'), 
                        'FNUrlSeq'      => $nCountSeq,
                        'FNUrlType'     => $this->input->post('ocmUrlConnecttype'),
                        'FTUrlTable'    => 'TCNMComp',
                        'FTUrlKey'      => $this->input->post('oetCompUrlKey'),
                        'FTUrlAddress'  => $this->input->post('oetCompServerip'),
                        'FTUrlPort'     => $this->input->post('oetCompPortConnect'),
                        'FTUrlLogo'     => $this->input->post('oetImgInputCompSetCon'),
                        'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                        'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                        'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                        'FDCreateOn'    => date('Y-m-d H:i:s'),
                    );
                break;
                case '11' :
                    // ลงตาราง TCNTUrlObject
                    $aDataUrlObj    = array(
                        'FTUrlRefID'    => $this->input->post('ohdCompCode'), 
                        'FNUrlSeq'      => $nCountSeq,
                        'FNUrlType'     => $this->input->post('ocmUrlConnecttype'),
                        'FTUrlTable'    => 'TCNMComp',
                        'FTUrlKey'      => $this->input->post('oetCompUrlKey'),
                        'FTUrlAddress'  => $this->input->post('oetCompServerip'),
                        'FTUrlPort'     => $this->input->post('oetCompPortConnect'),
                        'FTUrlLogo'     => $this->input->post('oetImgInputCompSetCon'),
                        'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                        'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                        'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                        'FDCreateOn'    => date('Y-m-d H:i:s'),
                    );
                break;
            default :
                // ลงตาราง TCNTUrlObject
                $aDataUrlObj    = array(
                    'FTUrlRefID'    => $this->input->post('ohdCompCode'), 
                    'FNUrlSeq'      => $nCountSeq,
                    'FNUrlType'     => $this->input->post('ocmUrlConnecttype'),
                    'FTUrlTable'    => 'TCNMComp',
                    'FTUrlKey'      => $this->input->post('oetCompUrlKey'),
                    'FTUrlAddress'  => $this->input->post('oetCompServerip'),
                    'FTUrlPort'     => $this->input->post('oetCompPortConnect'),
                    'FTUrlLogo'     => $this->input->post('oetImgInputCompSetCon'),
                    'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                    'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                    'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                    'FDCreateOn'    => date('Y-m-d H:i:s'),
                );

            }

            $this->db->trans_begin();

            $aChkAddress  = $this->mCompSettingConnection->FSaMCompSetConCheckUrlAddress($aDataUrlObj); 

            if($aChkAddress['rtCode'] == 1){
                // ถ้าข้อมูลซ้ำให้ออกลูป
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Add Event"
                );
            }else{
                switch($nUrlType){
                    case '9':  // Type 9 
                        $aStaMaster  = $this->mCompSettingConnection->FSaMCompSetConAddUpdateMasterUrl($aDataUrlObj); 
                    break;
                    case '10':  // Type 10
                        $aStaMaster  = $this->mCompSettingConnection->FSaMCompSetConAddUpdateMasterUrl($aDataUrlObj); 
                    break;
                    case '11':  // Type 11
                        $aStaMaster  = $this->mCompSettingConnection->FSaMCompSetConAddUpdateMasterUrl($aDataUrlObj); 
                    break;
                }
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                }else{
                    $this->db->trans_commit();
                    if($tCompSettingCon != $tCompSettingConOld){
                        $aImageData = [
                            'tModuleName'   => 'company', 
                            'tImgFolder'    => 'CompSetCon',
                            'tImgRefID'     =>  '0000'.$nCountSeq,  
                            'tImgObj'       => $tCompSettingCon,
                            'tImgTable'     => 'TCNTUrlObject',
                            'tTableInsert'  => 'TCNMImgObj',
                            'tImgKey'       => 'main',
                            'dDateTimeOn'   => date('Y-m-d H:i:s'),
                            'tWhoBy'        => $this->session->userdata('tSesUsername'),
                            'nStaDelBeforeEdit' => 1
                        ]; 
                        FCNnHAddImgObj($aImageData);
                    }
                    //update Seq
                    $this->mCompSettingConnection->FSaMCompSetConUpdateSeqNumber();

                    // Update Img Parth โดยดึงจาก ImageObj
                    $this->mCompSettingConnection->FSaMCompSetConAddUpdatePathUrl($aDataUrlObj);


                    $aReturn = array(
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Event'
                    );
                }
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Function Edit SettingConnect
    //Parameters : From Ajax File Userlogin
    //Creator : 04/07/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : array
    public function FSaCCompConnectEditEvent(){

        //imput Imge
        $tCompSettingConOld     = trim($this->input->post('oetImgInputCompSetConOld'));
        $tCompSettingCon        = trim($this->input->post('oetImgInputCompSetCon'));

        $nUrlType       = $this->input->post('ocmUrlConnecttype');
        $tOldKeyUrl     = $this->input->post('ohdKeyUrl');
        $tNewKeyUrl     = $this->input->post('oetCompServerip');
        $toldUrltype    = $this->input->post('ohdurltype');

        $nCountSeq      = $this->mCompSettingConnection->FSnMCompCountSeq();


        if($toldUrltype !=  $nUrlType){
            //วิ่งไปลบข้อมูลก่อนเพราะ เปลี่ยน type เดียวมันจะวิ่งไปที่ขา insert เอง
            $this->mCompSettingConnection->FSaMCompRemoveDataBecauseChangeType($tOldKeyUrl); 
            $aReturn = array(
                'nStaEvent'    => '800',
                'tStaMessg'    => "Unsucess Add Event"
            );
            echo json_encode($aReturn);
        }
        else{
            if($tOldKeyUrl == $tNewKeyUrl){
                //รหัสเก่า รหัสใหม่ ไม่ถูกเปลี่ยน
                $bChkAddress = false;
            }else{
                //รหัสใหม่ถูกเปลี่ยน
                $bChkAddress = true;

            }
            switch($nUrlType){
                case '9' :
                    // ลงตาราง TCNTUrlObject
                    $aDataUrlObj    = array(
                        'FNUrlID'       => $this->input->post('ohdUrlId'),
                        'FTUrlRefID'    => $this->input->post('ohdCompCode'), 
                        'FNUrlSeq'      => '0000'.$nCountSeq,  
                        'FNUrlType'     => $this->input->post('ocmUrlConnecttype'),
                        'FTUrlTable'    => 'TCNMComp',
                        'FTUrlKey'      => $this->input->post('oetCompUrlKey'),
                        'FTUrlAddress'  => $this->input->post('oetCompServerip'),
                        'FTUrlPort'     => $this->input->post('oetCompPortConnect'),
                        'FTUrlLogo'     => $this->input->post('oetImgInputCompSetCon'),
                        'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                        'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                        'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                        'FDCreateOn'    => date('Y-m-d H:i:s'),
                    );
                break;
                case '10' :
                    // ลงตาราง TCNTUrlObject
                    $aDataUrlObj    = array(
                        'FNUrlID'       => $this->input->post('ohdUrlId'),
                        'FTUrlRefID'    => $this->input->post('ohdCompCode'), 
                        'FNUrlSeq'      => '0000'.$nCountSeq,
                        'FNUrlType'     => $this->input->post('ocmUrlConnecttype'),
                        'FTUrlTable'    => 'TCNMComp',
                        'FTUrlKey'      => $this->input->post('oetCompUrlKey'),
                        'FTUrlAddress'  => $this->input->post('oetCompServerip'),
                        'FTUrlPort'     => $this->input->post('oetCompPortConnect'),
                        'FTUrlLogo'     => $this->input->post('oetImgInputCompSetCon'),
                        'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                        'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                        'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                        'FDCreateOn'    => date('Y-m-d H:i:s'),
                    );
                break;
                case '11' :
                    // ลงตาราง TCNTUrlObject
                    $aDataUrlObj    = array(
                        'FNUrlID'       => $this->input->post('ohdUrlId'),
                        'FTUrlRefID'    => $this->input->post('ohdCompCode'), 
                        'FNUrlSeq'      => '0000'.$nCountSeq,
                        'FNUrlType'     => $this->input->post('ocmUrlConnecttype'),
                        'FTUrlTable'    => 'TCNMComp',
                        'FTUrlKey'      => $this->input->post('oetCompUrlKey'),
                        'FTUrlAddress'  => $this->input->post('oetCompServerip'),
                        'FTUrlPort'     => $this->input->post('oetCompPortConnect'),
                        'FTUrlLogo'     => $this->input->post('oetImgInputCompSetCon'),
                        'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                        'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                        'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                        'FDCreateOn'    => date('Y-m-d H:i:s'),
                    );
                break;
                default :
                    // ลงตาราง TCNTUrlObject
                    $aDataUrlObj    = array(
                        'FTUrlRefID'    => $this->input->post('ohdCompCode'), 
                        'FNUrlSeq'      => '0000'.$nCountSeq,
                        'FNUrlType'     => $this->input->post('ocmUrlConnecttype'),
                        'FTUrlTable'    => 'TCNMComp',
                        'FTUrlKey'      => $this->input->post('oetCompUrlKey'),
                        'FTUrlAddress'  => $this->input->post('oetCompServerip'),
                        'FTUrlPort'     => $this->input->post('oetCompPortConnect'),
                        'FTUrlLogo'     => $this->input->post('oetImgInputCompSetCon'),
                        'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                        'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                        'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                        'FDCreateOn'    => date('Y-m-d H:i:s'),
                    );
            }
            if($bChkAddress == true){
                $aChkAddress  = $this->mCompSettingConnection->FSaMCompSetConCheckUrlAddressUpdate($aDataUrlObj); 

                if( $aChkAddress['rtCode'] == 1 && $bChkAddress == true){
                    // ถ้าซ้ำให้ออกลูป
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                    $tPass  = false;
                }else{
                    $tPass  = true;    
                }
            }else{
                // ถ้า tPass =  true มันก็จะ วิ่งไปที่ update normal  
                $tPass  = true;
            }



            // Update data normal
            if($tPass  ==  true){
                switch($nUrlType){
                    case '9':  // Type 9
                        $aStaMaster  = $this->mCompSettingConnection->FSaMCompSetConAddUpdateMasterUrlUpdate($aDataUrlObj,$tOldKeyUrl); 
                    break;
                    case '10' : //Type 10
                        $aStaMaster  = $this->mCompSettingConnection->FSaMCompSetConAddUpdateMasterUrlUpdate($aDataUrlObj,$tOldKeyUrl);
                    break;
                    case '11' :  // Type 11
                        $aStaMaster  = $this->mCompSettingConnection->FSaMCompSetConAddUpdateMasterUrlUpdate($aDataUrlObj,$tOldKeyUrl);
                    break;
                }
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                }else{
                    $this->db->trans_commit();
                    if($tCompSettingCon != $tCompSettingConOld){
                        $aImageData = [
                            'tModuleName'   => 'company', 
                            'tImgFolder'    => 'CompSetCon',
                            'tImgRefID'     => '0000'.$nCountSeq, 
                            'tImgObj'       => $tCompSettingCon,
                            'tImgTable'     => 'TCNTUrlObject',
                            'tTableInsert'  => 'TCNMImgObj',
                            'tImgKey'       => 'main',
                            'dDateTimeOn'   => date('Y-m-d H:i:s'),
                            'tWhoBy'        => $this->session->userdata('tSesUsername'),
                            'nStaDelBeforeEdit' => 1
                        ]; 
                        FCNnHAddImgObj($aImageData);
                    }

                    //update Seq                    
                    $this->mCompSettingConnection->FSaMCompSetConUpdateSeqNumber();

                    // Update Img Parth โดยดึงจาก ImageObj
                    $this->mCompSettingConnection->FSaMCompSetConAddUpdatePathUrl($aDataUrlObj);

                    $aReturn = array(
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Event'
                    );
                } 
            } 
            echo json_encode($aReturn);
        }       
    }


    //Functionality : Event Delete CompSettingConnection
    //Parameters : Ajax jReason()
    //Creator : 18/09/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : Status Delete Event
    //Return Type : String
    public function FSaCCompConnectDeleteEvent(){
        $tUrlID       =  $this->input->post('tUrlID');
        $tUrlAddress  =  $this->input->post('tUrlAddress');
        $tUrlType     =  $this->input->post('tUrlType');
        $tUrlRefID    = $this->input->post('tUrlRefID');

        $aDataMaster = array(
            'FNUrlID'      => $tUrlID,
            'FNUrlType'    => $tUrlType,
            'FTUrlAddress' => $tUrlAddress
        );

        $aResDel       = $this->mCompSettingConnection->FSnMCompSettingConDel($aDataMaster);
        
        //Update Seqent number
        $this->mCompSettingConnection->FSaMCompSetConUpdateSeqNumber();
        $nNumRowRsnLoc  = $this->mCompSettingConnection->FSnMCompSettingConGetAllNumRow();

        if($nNumRowRsnLoc){
            $aReturn    = array(
                'nStaEvent'     => $aResDel['rtCode'],
                'tStaMessg'     => $aResDel['rtDesc'],
                'nNumRowRsnLoc' => $nNumRowRsnLoc
            );
            echo json_encode($aReturn);
        }else{
            echo "database error";
        }

    }

    //Functionality : Event Delete Multi CompSettingConnection
    //Parameters : Ajax ()
    //Creator : 18/09/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : Status Delete Event
    //Return Type : String
    public function  FSoCCompConnectDelMultipleEvent(){
        try{
            $this->db->trans_begin();

            $tUrlID       =  $this->input->post('tUrlID');
            $tUrlAddress  =  $this->input->post('tAddress');
            
            $aDataDeleteMuti = array(
                'FNUrlID'       => $tUrlID,
                'FTUrlAddress'  => $tUrlAddress
             );

             $tResult    = $this->mCompSettingConnection->FSaMCompSetingConnDeleteMultiple($aDataDeleteMuti);

             if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $aDataReturn    = array(
                    'nStaEvent' => 500,
                    'tStaMessg' => 'Error Delete Data Multiple'
                );
            }else{
                $this->db->trans_commit();
                $aDataReturn     = array(
                    'nStaEvent' => 1,
                    'tStaMessg' => 'Success Delete Multiple'
                );
            }
        }catch(Exception $Error){
            $aDataReturn     = array(
                'nStaEvent' => 500,
                'tStaMessg' => $Error
            );
        }
        echo json_encode($aDataReturn);
    }

}