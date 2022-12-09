<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cHome extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->helper('url');
        $this->load->library ( "session" );			
        if(@$_SESSION['tSesUsername'] == false) {
            redirect ( 'login', 'refresh' );
            exit ();
        }
        $this->load->model('common/mMenu');
        $this->load->model('favorite/favorite/mFavorites');

        $this->load->model('common/mNotification');
    }

    public function index($nMsgResp = ''){
        $nMsgResp   = array('title' => "Home");
		$this->load->view ('common/wHeader',$nMsgResp);
		$this->load->view ('common/wTopBar',array('nMsgResp'=> $nMsgResp));
        $tUsrID         =   $this->session->userdata("tSesUsername");
        $nLngID         =   $this->session->userdata("tLangID");
        $nOwner         =  $this->session->userdata('tSesUserCode');
        $aMenuFav =  $this->mFavorites->FSaFavGetdataList($nOwner,$nLngID);
        if(isset($nLngID) && !empty($nLngID)){
            $nLngID = $this->session->userdata("tLangID");
        }else{
            $nLngID = 1;
        }
        $oGrpModules    =   $this->mMenu->FSaMMENUGetMenuGrpModulesName($nLngID);

        $oMenuList 	    =   $this->mMenu->FSoMMENUGetMenuList($tUsrID,$nLngID);

        //ไปหา exchang ถ้ายังไม่มีต้องสร้าง exchang
        // Create By Witsarut 03/03/2020
        $aMQParams = [
            "queuesname"    => "CN_QNotiMsg".$this->session->userdata('tSesUsrBchCom').$this->session->userdata('tSesSessionID'),
            "exchangname"   => "CN_XNotiMsg".$this->session->userdata('tSesUsrBchCom'),
            "params" => [
                'ptFunction'    => "",
                'ptSource'      => "",
                'ptDest'        => "",
                'ptFilter'      => "",
                'ptFTNotiId'    => "",
                'ptFTTopic'     => "",
                'ptFDSendDate'  => "",
                'ptFTUsrRole'   => "",
                'ptFTSubTopic'  => "",
                'ptFTMsg'       => "",
                'ptFTSubTopic'  => "",
                'ptFTMsg'       => ""      
            ]
        ];
       // FCNxDeclearExchangeStatDosenotification($aMQParams);

        //  *****************************************************

        // $oMenu           =   $this->mMenu->FSaMMENUGetMenuListByUsrMenuName($tUsrID,$nLngID,'POS');
	    // $oMenuTIK 	    =   $this->mMenu->FSaMMENUGetMenuListByUsrMenuName($tUsrID,$nLngID,'TIK');
        // $oMenuRPT 	    =   $this->mMenu->FSaMMENUGetMenuListByUsrMenuName($tUsrID,$nLngID,'RPT');
        // $oMenuVED 	    =   $this->mMenu->FSaMMENUGetMenuListByUsrMenuName($tUsrID,$nLngID,'VED');
      
        $this->load->view ('common/wMenu',array(
            
            'aMenuFav'	    => $aMenuFav,
            'nMsgResp'	    => $nMsgResp,
            'oGrpModules'   => $oGrpModules,
            'oMenuList'     => $oMenuList,
            // 'oMenu'      => $oMenu,
            // 'oMenuTIK'	=> $oMenuTIK,
            // 'oMenuRPT'	=> $oMenuRPT,
            // 'oMenuVED'   => $oMenuVED,
            'tUsrID'        => $tUsrID
        ));
        
        
        $this->load->view('common/wWellcome', $nMsgResp);
        $this->load->view('common/wFooter',array('nMsgResp' => $nMsgResp));
    }

    //Create by witsarut 04/03/2020
    //function ใช้ในการ Insdata Insert ลง ตาราง Notification
    public function FSxAddDataNoti(){
        try{
            
            $aResData =  $this->input->post('tDataNoti');

            foreach($aResData['ptData']['paContents'] AS $nKey => $aValue){
               $tSubTopic  =  $aValue['ptFTSubTopic'];
               $tMsg       =  $aValue['ptFTMsg'];
            } 

            $aData = array(
                'FTMsgID'       => $aResData['ptFunction'],
                'FTBchCode'     => $this->session->userdata('tSesUsrBchCom'),
                'FDNtiSendDate' => $aResData['ptData']['ptFDSendDate'],
                'FTNtiID'       => $aResData['ptData']['ptFTNotiId'],
                'FTNtiTopic'    => $aResData['ptData']['ptFTTopic'],
                'FTNtiContents' => json_encode($aResData['ptData']['paContents']),
                'FTNtiUsrRole'  => $aResData['ptData']['ptFTUsrRole'],
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                'tSource'       => $aResData['ptSource'],
                'tDest'         => $aResData['ptDest'],
                'tFilter'       => $aResData['ptFilter']
            );


            $this->db->trans_begin();

            // Check ข้อมูลซ้ำ TCNTNoti (FTMsgID)
            // เงื่อนไข : ถ้า Check แล้วเกิดข้อมูลซ้ำจะไม่ Insert TCNTNoti
            $aChkDupNotiMsgID   = $this->mNotification->FSaMCheckNotiMsgID($aData);

            if($aChkDupNotiMsgID['rtCode'] == 1){
                $aReturn = array(
                    'nStaEvent'    => '905',
                    'tStaMessg'    => "Unsucess Add Event"
                );
            }else{
                $aResult = $this->mNotification->FSaMAddNotification($aData);
                
                if($this->db->trans_status() == false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Success Add Data"
                    );
                }else{
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Add Data',
                    );
                }
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }

    }

    //Create by witsarut 04/03/2020
    //function ใช้ในการ Getdata Insert ลง ตาราง Notification
    public function FSxGetDataNoti(){

        $aData = $this->mNotification->FSaMGetNotification();
        if($aData['rtCode'] == 900){
            $aReturn = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => "Unsucess Success Add Data"
            );
        }else{
            $aReturn = array(
                'nStaEvent'	    => '1',
                'tStaMessg'		=> 'Success Add Data',
                'aData'         => $aData['raItems']
            );
        }
        echo json_encode($aReturn);
    }

    //Create by witsarut 04/03/2020
    //function ใช้ในการ Getdata Read 
    public function FSxGetDataNotiRead(){
        $this->mNotification->FSaMMoveDataTableNotiToTableRead();
    }


}

