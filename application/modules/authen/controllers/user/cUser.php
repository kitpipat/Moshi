<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class cUser extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('authen/user/mUser');
        $this->load->library('password/PasswordStorage');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index($nUsrBrowseType,$tUsrBrowseOption){
        $nMsgResp = array('title'=>"User");
        $isXHR = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) === 'XMLHTTPREQUEST';
        if(!$isXHR){
            $this->load->view ( 'common/wHeader', $nMsgResp);
            $this->load->view ( 'common/wTopBar', array ('nMsgResp'=>$nMsgResp));
            $this->load->view ( 'common/wMenu', array ('nMsgResp'=>$nMsgResp));
        }
        $vBtnSave = FCNaHBtnSaveActiveHTML('user/0/0'); //Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
        $aAlwEventUser	= FCNaHCheckAlwFunc('user/0/0');
        $this->load->view ( 'authen/user/wUser', array (
            'nMsgResp'          => $nMsgResp,
            'vBtnSave'          => $vBtnSave,
            'nUsrBrowseType'    => $nUsrBrowseType,
            'tUsrBrowseOption'  => $tUsrBrowseOption,
            'aAlwEventUser'     => $aAlwEventUser 
        ));
    }

    //Functionality : Function Call Page User List
    //Parameters : Ajax jUser()
    //Creator : 01/06/2018 wasin
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvUSRListPage(){
        $aAlwEventUser	= FCNaHCheckAlwFunc('user/0/0');
        $aNewData  		= array( 'aAlwEventUser' => $aAlwEventUser);
        $this->load->view('authen/user/wUserList',$aNewData);
    }

    //Functionality : Function Call DataTables User List
    //Parameters : Ajax jUser()
    //Creator : 01/06/2018 wasin
    //Return : String View
    //Return Type : View
    public function FSvUSRDataList(){
        try{
            $nPage      = $this->input->post('nPageCurrent');
            $tSearchAll = $this->input->post('tSearchAll');
            if($nPage == '' || $nPage == null){$nPage = 1;}else{$nPage = $this->input->post('nPageCurrent');}
            if(!$tSearchAll){$tSearchAll='';}
            //Lang ภาษา
            $nLangEdit  = $this->session->userdata("tLangEdit");

            // เพิ่มระดับผู้ใช้ 12/03/2020 Saharat
            $tStaUsrLevel    = $this->session->userdata("tSesUsrLevel");
            $tUsrBchCode     = $this->session->userdata("tSesUsrBchCode"); 
            $tUsrShpCode     = $this->session->userdata("tSesUsrShpCode"); 

            $aData = array(
                'nPage'         => $nPage,
                'nRow'          => 10,
                'FNLngID'       => $nLangEdit,
                'tSearchAll'    => $tSearchAll,
                'tStaUsrLevel'  => $tStaUsrLevel,
                'tUsrBchCode'   => $tUsrBchCode,
                'tUsrShpCode'   => $tUsrShpCode
            );

            $tAPIReq    = "";
            $tMethodReq = "GET";
            $aResList = $this->mUser->FSaMUSRList($tAPIReq, $tMethodReq, $aData);
            if($aResList['rnAllRow'] == 0){
                $nPage = $nPage - 1;
                $aData['nPage'] = $nPage;
                $aResList = $this->mUser->FSaMUSRList($tAPIReq, $tMethodReq, $aData);
            }
            $aAlwEvent = FCNaHCheckAlwFunc('user/0/0'); //Controle Event
            $aGenTable  = array(
                'aAlwEventUser' => $aAlwEvent,
                'aDataList'     => $aResList,
                'nPage'         => $nPage,
                'tSearchAll'    => $tSearchAll
            );
            $this->load->view('authen/user/wUserDataTable',$aGenTable);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Function CallPage User Add
    //Parameters : Ajax Call Function
    //Creator : 04/06/2018 wasin
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvUSRAddPage(){
        try{
            $nLangResort    = $this->session->userdata("tLangID");
            $nLangEdit      = $this->session->userdata("tLangEdit");
            // $aLangHave      = FCNaHGetAllLangByTable('TCNMUser_L');
            // $nLangHave      = count($aLangHave);
     

            // if($nLangHave > 1){
            //     if($nLangEdit != ''){
            //         $nLangEdit = $nLangEdit;
            //     }else{
            //         $nLangEdit = $nLangResort;
            //     }
            // }else{
            //     if(@$aLangHave[0]->nLangList == ''){
            //         $nLangEdit = '1';
            //     }else{
            //         $nLangEdit = $aLangHave[0]->nLangList;
            //     }
            // }
            $aData  = array(
                'FNLngID'   => $nLangEdit,
            );
            $tAPIReq        = "";
            $tMethodReq     = "GET";
            $aDataAdd = array(
                'aResult'   => array('rtCode'         =>'99',
                                     'dGetDataNow'   => date('Y-m-d'),
                                     'dGetDataFuture' => date('Y-m-d', strtotime('+1 year'))
                ),  
            );
      
            $this->load->view('authen/user/wUserAdd',$aDataAdd);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Function CallPage User Edit
    //Parameters : Ajax Function Edit User
    //Creator : 04/06/2018 wasin
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvUSREditPage(){
        try{
            $tUsrCode       = $this->input->post('tUsrCode');
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $aData  = array(
                'FTUsrCode' => $tUsrCode,
                'FNLngID'   => $nLangEdit
            );
            $tAPIReq        = "";
            $tMethodReq     = "GET";
            $aResList       = $this->mUser->FSaMUSRSearchByID($tAPIReq,$tMethodReq,$aData);

            // Create By Witsarut 21/02/2020
            // Join ขา Edit หา field FTUsrCode 
            $aResActRole   = $this->mUser->FSaMActRoleByID($tAPIReq,$tMethodReq,$aData);

            if(isset($aResList['raItems']['rtUsrImage']) && !empty($aResList['raItems']['rtUsrImage'])){
                $tImgObj        = $aResList['raItems']['rtUsrImage'];
                $aImgObj		= explode("application/modules/",$tImgObj);
				$aImgObjName	= explode("/",$tImgObj);
                $tImgObjAll     = $aImgObj[1];
                $tImgName		= end($aImgObjName);
            }else{
                $tImgObjAll     = "";
                $tImgName       = "";
            }
            $aDataEdit  = [
                'tImgObj'      => $tImgObjAll,
                'tImgName'     => $tImgName,
                'aResult'      => $aResList,
                'aResActRole'  => $aResActRole
            ];
       
            $this->load->view('authen/user/wUserAdd',$aDataEdit);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Function Event Add User
    //Parameters : Ajax Function Add User
    //Creator : 07/06/2018 wasin
    //Last Modified : -
    //Return : Status Event Add And Status Call Back Event
    //Return Type : object
    public function FSoUSRAddEvent(){
        try{
            $tUserImage     = trim($this->input->post('oetImgInputuser'));
            $tUserImageOld  = trim($this->input->post('oetImgInputuserOld'));
            $tBchCode       = $this->input->post('oetBranchCode');
            $tShpCode       = $this->input->post('oetShopCode');
            if(!empty($tBchCode) && !empty($tShpCode)){
                $FTUsrStaShop   = 3;
            }else if(!empty($tBchCode) && empty($tShpCode)){
                $FTUsrStaShop   = 2;
            }else{
                $FTUsrStaShop   = 1;
            }
            $tIsAutoGenCode = $this->input->post('ocbUserAutoGenCode');
            $tUsrCode = "";
            if(isset($tIsAutoGenCode) && $tIsAutoGenCode == '1'){
                $aGenCode = FCNaHGenCodeV5('TCNMUser');
                if($aGenCode['rtCode'] == '1'){
                    $tUsrCode = $aGenCode['rtUsrCode'];
                }
            }else{
                $tUsrCode = $this->input->post('oetUsrCode');
            }

            $aPassExp       = $this->input->post('oetUsrPassword');
            $aDataMaster    = array(
                'FTImgObj'      => $this->input->post('oetImgInputuser'),
                'FTUsrCode'     => $tUsrCode,
                'FTDptCode'     => $this->input->post('oetDepartCode'),
                'FTRolCode'     => '',
                'FTUsrTel'      => $this->input->post('oetUsrTel'),
                'FTUsrPwd'      => $aPassExp,
                'FTUsrEmail'    => $this->input->post('oetUsrEmail'),
                'FTUsrName'     => $this->input->post('oetUsrName'),
                'FTUsrRmk'      => $this->input->post('otaUsrRemark'),
                'FTBchCode'     => $tBchCode,
                'FTUsrStaShop'  => $FTUsrStaShop,
                'FTShpCode'     => $tShpCode,
                'FDUsrStart'    => $this->input->post('oetUsrDateStart'),
                'FDUsrStop'     => $this->input->post('oetUsrDateStop'),
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                'FNLngID'       => $this->session->userdata("tLangEdit")
            );
   
            // Create By Witsarut 21/02/2020
            // Insert  TCNMUsrActRole
            $tRoleCode   =  $this->input->post('oetRoleCode');
            $tRoleSpitCode   = explode(',' , $tRoleCode);

            $aDataMasterActRole = array(
                'FTUsrCode'     => $tUsrCode,
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername'),
            );

            if(isset($tRoleSpitCode) != empty($tRoleSpitCode)){
                for($i=0; $i < count($tRoleSpitCode); $i++){
                    // Inert TCNMUsrActRole
                    if($tRoleSpitCode[$i] == ''){
                        continue;
                    }
                    $aStaMasterActRole  = $this->mUser->FSaMUSRAddUpdateMasterActRole($tRoleSpitCode[$i],$aDataMasterActRole);
                }
            }

            $oCountDup  = $this->mUser->FSoMUSRCheckDuplicate($aDataMaster['FTUsrCode']);
            $nStaDup    = $oCountDup[0]->counts;
            if($nStaDup == 0){
                $this->db->trans_begin();
                    // TCNMUser
                    $aStaUsrMaster      = $this->mUser->FSaMUSRAddUpdateMaster($aDataMaster);
                    $aStaUsrLang        = $this->mUser->FSaMUSRAddUpdateLang($aDataMaster);
                    $aStaUsrGroup       = $this->mUser->FSaMUSRAddUpdateGroup($aDataMaster);
                if($this->db->trans_status() === false){
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Insert"
                    );
                }else{
                    $this->db->trans_commit();
                    // Check Image New Compare Image Old (เช็คข้อมูลรูปภาพใหม่ต้องไม่ตรงกับรูปภาพเก่าในระบบ)
                    if($tUserImage != $tUserImageOld){
                        $aImageUplode   = array(
                            'tModuleName'       => 'authen',
                            'tImgFolder'        => 'user',
                            'tImgRefID'         => $aDataMaster['FTUsrCode'],
                            'tImgObj'           => $tUserImage,
                            'tImgTable'         => 'TCNMUser',
                            'tTableInsert'      => 'TCNMImgPerson',
                            'tImgKey'           => '',
                            'dDateTimeOn'       => date('Y-m-d H:i:s'),
                            'tWhoBy'            => $this->session->userdata('tSesUsername'),
                            'nStaDelBeforeEdit' => 1
                        );
                        FCNnHAddImgObj($aImageUplode);
                    }
                    $aReturn = array(
                        'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'	=> $aDataMaster['FTUsrCode'],
                        'nStaEvent'	    => '1',
                        'tStaMessg'		=> 'Success Update'
                    );
   
                }
            }else{
                $aReturn = array(
                    'nStaEvent'    => '801',
                    'tStaMessg'    => "Status Dup"
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Function Event Edit User
    //Parameters : Ajax Function Edit User
    //Creator : 07/06/2018 wasin
    //Last Modified : -
    //Return : Status Event Edit And Status Call Back Event
    //Return Type : object
    public function FSoUSREditEvent(){
        try{
            $tUserImageOld  = trim($this->input->post('oetImgInputuserOld'));
            $tUserImage     = trim($this->input->post('oetImgInputuser'));
            $tBchCode       = $this->input->post('oetBranchCode');
            $tShpCode       = $this->input->post('oetShopCode');
            if(!empty($tBchCode) && !empty($tShpCode)){
                $FTUsrStaShop   = 3;
            }else if(!empty($tBchCode) && empty($tShpCode)){
                $FTUsrStaShop   = 2;
            }else{
                $FTUsrStaShop   = 1;
            }
            $aPassExp       = $this->input->post('oetUsrPassword');
            $aDataMaster = array(
                'FTImgObj'      => $this->input->post('oetImgInputuser'),
                'FTUsrCode'     => $this->input->post('oetUsrCode'),
                'FTDptCode'     => $this->input->post('oetDepartCode'),
                'FTUsrTel'      => $this->input->post('oetUsrTel'),
                'FTUsrPwd'      => $aPassExp,
                'FTUsrEmail'    => $this->input->post('oetUsrEmail'),
                'FTUsrName'     => $this->input->post('oetUsrName'),
                'FTUsrRmk'      => $this->input->post('otaUsrRemark'),
                'FTBchCode'     => $tBchCode,
                'FTUsrStaShop'  => $FTUsrStaShop,
                'FTShpCode'     => $tShpCode,
                'FDUsrStart'    => $this->input->post('oetUsrDateStart'),
                'FDUsrStop'     => $this->input->post('oetUsrDateStop'),
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                'FNLngID'       => $this->session->userdata("tLangEdit")
            );
       
            // Create By Witsarut 21/02/2020
            // Insert  TCNMUsrActRole
            $aRoleCode     =  $this->input->post('oetRoleCode');
         

            $tRoleSpitCode   = explode(',' , $aRoleCode);

            $aDataMasterActRole = array(
                'FTUsrCode'     => $this->input->post('oetUsrCode'),
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername'),
            );

            $aUsrCode   = array(
                'FTUsrCode'  =>  $this->input->post('oetUsrCode'),
            );

            //Del USerCode Table TCNMUsrActRole
            $this->mUser->FSaMDelActRoleCode($aUsrCode);

            //Update
            if(isset($tRoleSpitCode) != empty($tRoleSpitCode)){
                for($i=0; $i < count($tRoleSpitCode); $i++){
                    if($tRoleSpitCode[$i] == ''){
                        continue;
                    }
                    $aStaMasterActRole  = $this->mUser->FSaMUpdateMasterActRole($tRoleSpitCode[$i],$aDataMasterActRole);
                }
            }

            $this->db->trans_begin();
                $aStaUsrMaster  = $this->mUser->FSaMUSRAddUpdateMaster($aDataMaster);
                $aStaUsrLang    = $this->mUser->FSaMUSRAddUpdateLang($aDataMaster);
                $aStaUsrGroup   = $this->mUser->FSaMUSRAddUpdateGroup($aDataMaster);
            if($this->db->trans_status() === false){
                $this->db->trans_rollback();
                $aReturn = array(
                    'tStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Update User"
                );
            }else{
                $this->db->trans_commit();
                // Check Image New Compare Image Old (เช็คข้อมูลรูปภาพใหม่ต้องไม่ตรงกับรูปภาพเก่าในระบบ)
                if($tUserImage != $tUserImageOld){
                    $aImageUplode   = array(
                        'tModuleName'       => 'authen',
                        'tImgFolder'        => 'user',
                        'tImgRefID'         => $aDataMaster['FTUsrCode'],
                        'tImgObj'           => $tUserImage,
                        'tImgTable'         => 'TCNMUser',
                        'tTableInsert'      => 'TCNMImgPerson',
                        'tImgKey'           => '',
                        'dDateTimeOn'       => date('Y-m-d H:i:s'),
                        'tWhoBy'            => $this->session->userdata('tSesUsername'),
                        'nStaDelBeforeEdit' => 1
                    );
                    FCNnHAddImgObj($aImageUplode);
                }
                $aReturn = array(
                    'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'	=> $aDataMaster['FTUsrCode'],
                    'nStaEvent'	    => '1',
                    'tStaMessg'		=> 'Success Update'
                );
            }
            echo json_encode($aReturn);
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Function Event Delete User
    //Parameters : Ajax Function Delete User
    //Creator : 07/06/2018 wasin
    //Last Modified : -
    //Return : Status Event Delete And Status Call Back Event
    //Return Type : object
    public function FSoUSRDeleteEvent(){
        $tIDCode = $this->input->post('tIDCode');
        $aDataMaster = array(
            'FTUsrCode' => $tIDCode,
            
        );
        $aResDel        = $this->mUser->FSnMUSRDel($aDataMaster);
        $nNumRowUsrLoc  = $this->mUser->FSnMLOCGetAllNumRow();
        if($aResDel['rtCode'] == 1){
            $aDeleteImage = array(
                'tModuleName'   => 'authen',
                'tImgFolder'    => 'user',
                'tImgRefID'     => $tIDCode,
                'tTableDel'     => 'TCNMImgPerson',
                'tImgTable'     => 'TCNMUser'
            );
            $nStaDelImgInDB =   FSnHDelectImageInDB($aDeleteImage);
           
            if($nStaDelImgInDB == 1){
                FSnHDeleteImageFiles($aDeleteImage);
            }
        }

        if($nNumRowUsrLoc){
            $aReturn    = array(
                'nStaEvent'     => $aResDel['rtCode'],
                'tStaMessg'     => $aResDel['rtDesc'],
                'nNumRowUsrLoc' => $nNumRowUsrLoc
            );
            echo json_encode($aReturn);
        }else{
            echo "database error";
        }

    }

}






