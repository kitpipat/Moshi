<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cRole extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        $this->load->model('authen/role/mRole');
        date_default_timezone_set("Asia/Bangkok");
    }

    public function index($nRoleBrowseType,$tRoleBrowseOption){
        /** ========================================== Option การตั้งค่าหน้าจอ ==========================================
         * nRoleBrowseType      : สถานะการเข้าถึงข้อมูล 0 => จากการกดเมนูซ้ายมือ 1 => จากการกดเพิ่มที่ Modal Browse ข้อมูล
         * tRoleBrowseOption    : ชื่อออฟชั่นก่อนหน้าในการเข้าถึงข้อมูลจาก Modal เกี่ยวข้องกับ Modal Center
         * aAlwEvent            : อเรย์ข้อมูล Allow Authen => Full,Add,Edit,Delete,Appove,Reprint,Cancel
         * vBtnSave             : ออฟชั่นปุ่ม Save
         * nOptDecimalShow      : Degit ที่แสดงข้อมูลทศนิยมในกรณีดูข้อมูล
         * nOptDecimalSave      : Degit ที่แสดงข้อมูลทศนิยมในกรณีเซฟ
         * ==========================================================================================================
        */ 
        $aDataConfigView    = [
            'nRoleBrowseType'   => $nRoleBrowseType,
            'tRoleBrowseOption' => $tRoleBrowseOption,
            'aAlwEvent'         => FCNaHCheckAlwFunc('role/0/0'),
            'vBtnSave'          => FCNaHBtnSaveActiveHTML('role/0/0'), 
            'nOptDecimalShow'   => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'   => FCNxHGetOptionDecimalSave()
        ];
        $this->load->view('authen/role/wRole',$aDataConfigView);
    }

    // Functionality : Call Page From Search List
    // Parameters : Ajax and Function Parameter
    // Creator : 17/06/2019 wasin (Yoshi AKA: Mr.JW)
    // LastUpdate: 13/08/2019 Wasin(Yoshi)
    // Return : 
    // Return Type : View
    public function FStCCallPageRoleList(){
        $aDataConfigView    = ['aAlwEvent' => FCNaHCheckAlwFunc('role/0/0')];
        $this->load->view('authen/role/wRoleList',$aDataConfigView);
    }

    // Functionality : Call DataTables Role List
    // Parameters : Ajax Function Call Page
    // Creator : 22/06/2018 wasin
    // LastUpdate: 13/08/2019 Wasin(Yoshi)
    // Return : object Data Table
    // Return Type : object
    public function FSoCCallPageRoleDataTable(){
        try{
            $tSearchAll     = $this->input->post('ptSearchAll');
            $nPage          = ($this->input->post('pnPageCurrent') == '' || null)? 1 : $this->input->post('pnPageCurrent'); 
            $nLangResort    = $this->session->userdata("tLangID");
            $nLangEdit      = $this->session->userdata("tLangEdit");

            // $aLangHave      = FCNaHGetAllLangByTable('TCNMUsrRole_L');
            // $nLangHave      = count($aLangHave);
            // if($nLangHave > 1){
            //     $nLangEdit  = ($nLangEdit != '')? $nLangEdit : $nLangResort;
            // }else{
            //     $nLangEdit  = (@$aLangHave[0]->nLangList == '')? '1' : $aLangHave[0]->nLangList;
            // }

            $aDataWhere = array(
                'nPage'         => $nPage,
                'nRow'          => 10,
                'FNLngID'       => $nLangEdit,
                'tSearchAll'    => $tSearchAll
            );

            $aDataRoleList  = $this->mRole->FSaMGetDataRoleList($aDataWhere);
            $aAlwEvent      = FCNaHCheckAlwFunc('role/0/0');

            $aConfigView    = array(
                'nPage'     => $nPage,
                'aAlwEvent' => $aAlwEvent,
                'aDataList' => $aDataRoleList
            );

            $tRoleViewDataTableList   = $this->load->view('authen/role/wRoleDataTable',$aConfigView,true);
            $aReturnData = array(
                'tRoleViewDataTableList'    => $tRoleViewDataTableList,
                'nStaEvent' => 1,
                'tStaMessg' => 'Success'
            );
        }catch(ErrorException $Error){
            $aReturnData = array(
                'nStaEvent' => 500,
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    // Functionality : Call Page Add Role
    // Parameters : Ajax Function Call Page
    // Creator : 22/06/2018 wasin
    // LastUpdate: 27/08/2019 Wasin(Yoshi)
    // Return : object Data Table
    // Return Type : object
    public function FSoCCallPageRoleAdd(){
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
            
            $aData                  = array('FNLngID'=> $nLangEdit);
            $aDataMenuList          = $this->mRole->FSaMRoleMenuList($aData);
            $aDataMenuRptList       = $this->mRole->FSaMRptListMenu($aData);
            $aDataConfigViewForm    = array(
                'nStaCallView'      => 1, // 1 = Call View Add , 2 = Call View Edits
                'aDataMenuList'     => $aDataMenuList,
                'aDataMenuReport'   => $aDataMenuRptList
            );
            $tRoleViewPageForm  = $this->load->view('authen/role/wRoleAdd',$aDataConfigViewForm,true);
            $aReturnData        = array(
                'tRoleViewPageAdd'  => $tRoleViewPageForm,
                'nStaEvent'         => '1',
                'tStaMessg'         => 'Success'
            );
        }catch(Exception $Error){
            $aReturnData    = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //Functionality : page edit - success
    //Parameters : 
    //Creator : 28/06/2018 wasin
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSoCCallPageRoleEdit(){
        try{
            $tRolCode       = $this->input->post('tRolCode');
            $nLangResort    = $this->session->userdata("tLangID");
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $aLangHave      = FCNaHGetAllLangByTable('TCNMUsrRole_L');
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

            $aDataWhere  = array(
                'FNLngID'       => $nLangEdit,
                'FTRolCode'     => $tRolCode
            );

            // Get Data User Role
            $aDataUsrRole       = $this->mRole->FSaMRoleGetDataMaster($aDataWhere);


            if(isset($aDataUsrRole['rtRoleImgObj']) && !empty($aDataUsrRole['rtRoleImgObj'])){
                $tImgObj        = $aDataUsrRole['rtRoleImgObj'];
                $aImgObjPath	= explode("application/modules/",$tImgObj);
                $aImgObjName	= explode("/",$tImgObj);

                $tImgObjPath	= end($aImgObjPath);
                $tImgObjName	= end($aImgObjName);
            }else{
                $tImgObjPath	= "";
                $tImgObjName	= ""; 
            }

            // Get Data Report Menu
            $aDataMenuList      = $this->mRole->FSaMRoleMenuList($aDataWhere);
            $aDataMenuReport        = $this->mRole->FSaMRptListMenu($aDataWhere);

            $aDataConfigViewForm    = array(
                'nStaCallView'      => 2, // 1 = Call View Add , 2 = Call View Edits
                'aDataMenuList'     => $aDataMenuList,
                'aDataMenuReport'   => $aDataMenuReport,
                'aDataUsrRole'      => $aDataUsrRole,
                'tImgObjPath'	    => $tImgObjPath,
				'tImgObjName'	    => $tImgObjName
            );

            $tRoleViewPageForm      = $this->load->view('authen/role/wRoleAdd',$aDataConfigViewForm,true);

            // Get Data Report Menu Edit
            $aDataRoleMenuEdit      = $this->mRole->FSaMGetDataRoleMenuEdit($aDataWhere);
            $aDataRoleMenuRptEdit   = $this->mRole->FSaMGetDataRoleMenuRptEdit($aDataWhere);

            $aReturnData        = array(
                'nStaEvent'             => '1',
                'tStaMessg'             => 'Success',
                'tRoleViewPageEdit'     => $tRoleViewPageForm,
                'aDataRoleMenuEdit'     => $aDataRoleMenuEdit,
                'aDataRoleMenuRptEdit'  => $aDataRoleMenuRptEdit,
            );
        }catch(Exception $Error){
            $aReturnData    = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage()
            );
        }
        echo json_encode($aReturnData);
    }

    //Functionality : Add Event Role - Success
    //Parameters : Ajax Route Parameter
    //Creator : 29/06/2019 Saharat(Golf)
    //Last Modified : -
    //Return : Status CallBack Add Event
    //Return Type : object
    public function FSoRoleAddEvent(){
        try{
            $this->db->trans_begin();

            $tRoleAutoGenCode   = $this->input->post('ptRoleAutoGenCode');
            $tRoleCode  = "";
            if(isset($tRoleAutoGenCode) &&  $tRoleAutoGenCode == '1'){
                $aGenCode   = FCNaHGenCodeV5('TCNMUsrRole');
                if($aGenCode['rtCode'] == '1'){
                    $tRoleCode  = $aGenCode['rtRolCode'];
                }
            }else{
                $tRoleCode  = $this->input->post('ptRoleCode');
            }

            //imput Imge
            $tImageUplodeOld     = trim($this->input->post('ptImageOld'));
            $tImageUplode        = trim($this->input->post('ptImageNew'));

            // Master Add/Update Table (TCNMUsrRole,TCNMUsrRole_L)
            $aDataMaster    = array(
                'FTRolCode'     => $tRoleCode,
                'FNRolLevel'    => $this->input->post('ptRoleLevel'),
                'FTRolName'     => $this->input->post('ptRoleName'),
                'FTRolRmk'      => $this->input->post('ptRoleRemark'),
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                'FNLngID'       => $this->session->userdata("tLangEdit")
            );


            $this->mRole->FSxMRoleAddUpdateUsrRole($aDataMaster);
            $this->mRole->FSxMRoleAddUpdateUsrRoleLang($aDataMaster);

            // Add Data User Menu
            $aRoleMnuData       = $this->input->post('paRoleMnuData');
            $this->mRole->FSxMRoleAddUpdateUsrMenu($aDataMaster,$aRoleMnuData);

            // Add Data Report Menu
            $aRoleRptMnuData    = $this->input->post('paRoleRptData');
            $this->mRole->FSxMRoleAddUpdateUsrRptMenu($aDataMaster,$aRoleRptMnuData);

            // Check Trancetion Event Menu
            if($this->db->trans_status() !== FALSE){
                $this->db->trans_commit();
                    $aReturnData = array(
                        'nStaEvent'      =>  1,
                        'tStaMessg'      => 'Add/Update Role Complete',
                        'nStaCallBack'   => $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'    => $aDataMaster['FTRolCode'],
                        'tModuleName'    => 'authen',
                        'tImgFolder'     => 'role',
                        'tImgRefID'      => $tRoleCode,
                        'tImgObj'        => $tImageUplode,
                        'tImgTable'      => 'TCNMUsrRole',
                        'tTableInsert'   => 'TCNMImgObj',
                        'tImgKey'        => 'main',
                        'dDateTimeOn'    => date('Y-m-d H:i:s'),
                        'tWhoBy'         => $this->session->userdata('tSesUsername')
                    );
                    FCNnHAddImgObj($aReturnData);
            }else{
                $this->db->trans_rollback();
                throw new Exception(array(
                    'nCodeReturn'   => 500,
                    'tTextStaMessg' => 'Error Not Add/Update Data Role.',
                )); 
            }
        }catch(Exception $Error){
            $aReturnData = array(
                'nStaEvent' => $Error['nCodeReturn'],
                'tStaMessg' => $Error['tTextStaMessg']
            );
        }
        echo json_encode($aReturnData);
    }

    //Functionality : Edit Event Role 
    //Parameters : Ajax Route Parameter
    //Creator : 29/06/2019 Saharat(Golf)
    //Last Modified : -
    //Return : Status CallBack Add Event
    //Return Type : object
    public function FSoRoleEditEvent(){
        try{
            $this->db->trans_begin();

            //imput Imge
            $tImageUplodeOld     = trim($this->input->post('ptImageOld'));
            $tImageUplode        = trim($this->input->post('ptImageNew'));

            // Master Add/Update Table (TCNMUsrRole,TCNMUsrRole_L)
            $aDataMaster    = array(
                'FTRolCode'     => $this->input->post('ptRoleCode'),
                'FNRolLevel'    => $this->input->post('ptRoleLevel'),
                'FTRolName'     => $this->input->post('ptRoleName'),
                'FTRolRmk'      => $this->input->post('ptRoleRemark'),
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FDCreateOn'    => date('Y-m-d H:i:s'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FTCreateBy'    => $this->session->userdata('tSesUsername'),
                'FNLngID'       => $this->session->userdata("tLangEdit")
            );

            $this->mRole->FSxMRoleAddUpdateUsrRole($aDataMaster);
            $this->mRole->FSxMRoleAddUpdateUsrRoleLang($aDataMaster);

            // Add Data User Menu
            $aRoleMnuData       = $this->input->post('paRoleMnuData');
            $this->mRole->FSxMRoleAddUpdateUsrMenu($aDataMaster,$aRoleMnuData);

            // Add Data Report Menu
            $aRoleRptMnuData    = $this->input->post('paRoleRptData');
            $this->mRole->FSxMRoleAddUpdateUsrRptMenu($aDataMaster,$aRoleRptMnuData);

             // Check Trancetion Event Menu
            if($this->db->trans_status() !== FALSE){
                $this->db->trans_commit();
                    $aReturnData = array(
                        'nStaEvent'       =>  1,
                        'tStaMessg'       => 'Add/Update Role Complete',
                        'nStaCallBack'    => $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'     => $aDataMaster['FTRolCode'],
                        'tModuleName'     => 'authen',
                        'tImgFolder'      => 'role',
                        'tImgRefID'       => $this->input->post('ptRoleCode'),
                        'tImgObj'         => $tImageUplode,
                        'tImgTable'       => 'TCNMUsrRole',
                        'tTableInsert'    => 'TCNMImgObj',
                        'tImgKey'         => 'main',
                        'dDateTimeOn'     => date('Y-m-d H:i:s'),
                        'tWhoBy'          => $this->session->userdata('tSesUsername')
                    );
                    FCNnHAddImgObj($aReturnData);
            }else{
                $this->db->trans_rollback();
                throw new Exception(array(
                    'nCodeReturn'   => 500,
                    'tTextStaMessg' => 'Error Not Add/Update Data Role.',
                )); 
            }
            
        }catch(Exception $Error){
            $aReturnData = array(
                'nStaEvent' => $Error['nCodeReturn'],
                'tStaMessg' => $Error['tTextStaMessg']
            );
        }
        echo json_encode($aReturnData);
    }

    //Functionality : Function Event Delete Rol
    //Parameters : Route Parameter
    //Creator : 04/07/2018 wasin
    //Last Modified : -
    //Return : Status Delete Role
    //Return Type : object
    public function FSoRoleDeleteEvent(){
        try{
            $tDeleteIDCode  = $this->input->post('ptDeleteIDCode');
            $aDataMaster    = array(
                'FTRolCode' => $tDeleteIDCode
            );

            $aStaDelRole    = $this->mRole->FSaMRoleDeleteData($aDataMaster);
            if($aStaDelRole['rtCode'] == '1'){
                $nNumRowRolLoc  = $this->mRole->FSnMCountDataRole();
                $aReturnData    = array(
                    'nStaEvent'     => $aStaDelRole['rtCode'],
                    'tStaMessg'     => $aStaDelRole['rtDesc'],
                    'nNumRowRolLoc' => $nNumRowRolLoc
                );
            }else{
                throw new Exception(array(
                    'tCodeReturn'   => $aStaDelRole['rtCode'],
                    'tTextStaMessg' => $aStaDelRole['rtDesc'],
                )); 
            }
        }catch(Exception $Error){
            $aReturnData    = array(
                'nStaEvent' => $Error['tCodeReturn'],
                'tStaMessg' => $Error['tTextStaMessg']
            );
        }
        echo json_encode($aReturnData);
    }





    

}