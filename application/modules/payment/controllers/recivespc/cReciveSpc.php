<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cReciveSpc extends MX_Controller {

    public function __construct() {
        parent::__construct ();
        date_default_timezone_set("Asia/Bangkok");
        $this->load->model('company/branch/mBranch');
        $this->load->model('payment/recive/mRecive');
        $this->load->model('payment/recivespc/mReciveSpc');
   
    }

    //Functionality : Function Call Page Main
	//Parameters : From Ajax File RevSpc
	//Creator : 27/11/2019 Witsarut (Bell)
	//Last Modified : -
	//Return : String View
	//Return Type : View
    public function FSvCReciveSpcMainPage($nRcvSpcBrowseType,$tRcvSpcBrowseOption){

        $vBtnSaveGpRcvSpc    = FCNaHBtnSaveActiveHTML('recive/0/0');
        $aAlwEventRcvSpc     = FCNaHCheckAlwFunc('recive/0/0');

        //get data RcvCode
        $tRcvCode   =   $this->input->Post('tRcvCode');

        $aRcvCode   = array(
          'tRcvCode'   => $tRcvCode
        );

        $this->load->view('payment/recivespc/wReciveSpcMain',array(
            'vBtnSaveGpRcvSpc'      => $vBtnSaveGpRcvSpc,
            'aAlwEventRcvSpc'       => $aAlwEventRcvSpc,
            'aRcvCode'              => $aRcvCode,
            'nRcvSpcBrowseType'     => $nRcvSpcBrowseType,
            'tRcvSpcBrowseOption'   => $tRcvSpcBrowseOption
        ));
    }


    //Functionality : List Data 
	//Parameters : From Ajax File RevSpc
	//Creator : 27/11/2019 Witsarut (Bell)
	//Last Modified : -
	//Return : String View
	//Return Type : View
    public function FSvCReciveSpcDataList(){

        $tRcvSpcCode    = $this->input->post('tRcvSpcCode');
        $tRcvSpcBchCode = $this->input->post('tRcvSpcBchCode');  
        $tRcvSpcShpCode  =$this->input->post('tRcvSpcShpCode'); 
        $nPage          = $this->input->post('nPageCurrent');
        $tSearchAll     = $this->input->post('tSearchAll');

        if($nPage == '' || $nPage == null){$nPage = 1;}else{$nPage  = $this->input->post('nPageCurrent');}
        if(!$tSearchAll){$tSearchAll='';}

        // //Lang ภาษา
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");

        //สิทธิ
        $aAlwEventRcvSpc   = FCNaHCheckAlwFunc('recive/0/0');

        $aData = array(
            'FTRcvCode'    => $tRcvSpcCode,
            'FTBchCode'    => $tRcvSpcBchCode,
            'FTShpCode'    => $tRcvSpcShpCode,
            'nPage'         => $nPage,
            'nRow'          => 10,
            'FNLngID'       => $nLangEdit,
            'tSearchAll'    => $tSearchAll,
        );

        $aResList    = $this->mReciveSpc->FSaMRCVSpcDataList($aData);

        $aGenTable      = array(
            'aDataList'   => $aResList,
            'nPage'       => $nPage,
            'FTRcvCode'   =>  $tRcvSpcCode,
            'FTBchCode'    => $tRcvSpcBchCode,
            'FTShpCode'    => $tRcvSpcShpCode,
            'aAlwEventRcvSpc' =>  $aAlwEventRcvSpc,
        );

        //Return Data View
        $this->load->view('payment/recivespc/wReciveSpcDataTable',$aGenTable);
    }

    //Functionality :  Load Page Add CrdLogin 
    //Parameters : 
    //Creator : 26/11/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : HTML View
    //Return Type : View
    public function FSvCReciveSpcPageAdd(){   
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $vBtnSaveGpRcvSpc  = FCNaHBtnSaveActiveHTML('recive/0/0');
        $aAlwEventRcvSpc   = FCNaHCheckAlwFunc('recive/0/0');
        $tRcvSpcCode =   $this->input->post('tRcvSpcCode');
        $aRcvSpcCode   = array(
            'tRcvSpcCode'   => $tRcvSpcCode
        );
        $aDataAdd  = array(
            'aResult'   => array('rtCode'=>'99'),
            'vBtnSaveGpRcvSpc' => $vBtnSaveGpRcvSpc,
            'aAlwEventRcvSpc'  => $aAlwEventRcvSpc,
            'aRcvSpcCode'      => $aRcvSpcCode
        );
        $this->load->view('payment/recivespc/wReciveSpcAdd',$aDataAdd);
    }


    //Functionality :  Load Page Edit Courierlogin 
    //Parameters : 
    //Creator : 26/11/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCReciveSpcPageEdit(){
        $nLangEdit          = $this->session->userdata("tLangEdit");
        $aAlwEventRcvSpc    = FCNaHCheckAlwFunc('recive/0/0');
        $aDataWhereEdit     = $this->input->post('paDataWhereEdit');
        $aData              = [
            'FTRcvCode'     => $aDataWhereEdit['ptRcvCode'],
            'FTRcvCode'     => $aDataWhereEdit['ptRcvCode'],
            'FTRcvCode'     => $aDataWhereEdit['ptRcvCode'],
            'FTAppCode'     => $aDataWhereEdit['ptAppCode'],
            'FNRcvSeq'      => $aDataWhereEdit['pnRcvSeq'],
            'FNLngID'       =>  $nLangEdit
        ];
        $aResult    = $this->mReciveSpc->FSaMRCVSPCCheckID($aData);
        $aRcvSpcCode   = array(
            'tRcvSpcCode'   => $aDataWhereEdit['ptRcvCode']
        );
        $aDataEdit = array(
            'aResult'         => $aResult,  
            'aAlwEventRcvSpc' => $aAlwEventRcvSpc,
            'aRcvSpcCode'     => $aRcvSpcCode
        );
        $this->load->view('payment/recivespc/wReciveSpcAdd',$aDataEdit);
    }

    //Functionality : Function Add Cardlogin
    //Parameters : From Ajax File Cardlogin
    //Creator : 26/11/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : array
    public function FSaCReciveSpcAddEvent(){
        $aDataMaster    = array(
            'FTRcvCode'         => $this->input->post('ohdRcvSpcCode'),
            'FTAppCode'         => $this->input->post('oetRcvSpcAppCode'),
            'FTBchCode'         => $this->input->post('oetRcvSpcBchCode'),
            'FTMerCode'         => $this->input->post('oetRcvSpcMerCode'),
            'FTShpCode'         => $this->input->post('oetRcvSpcShpCode'),
            'FTAggCode'         => $this->input->post('oetRcvSpcAggCode'),
            'FTPdtRmk'          => $this->input->post('oetRcvSpcRemark'),
            'FTAppStaAlwRet'    => (!empty($this->input->post('ocbRcvSpcStaAlwRet')))? 1 : 2,
            'FTAppStaAlwCancel' => (!empty($this->input->post('ocbRcvSpcStaAlwCancel')))? 1 : 2,
            'FTAppStaPayLast'   => (!empty($this->input->post('ocbRcvSpcStaPayLast')))? 1 : 2,
        );
        // $aChkAppCode    = $this->mReciveSpc->FSaMRcvSpcCheckCrdCode($aDataMaster);
        // if($aChkAppCode['rtCode'] == 1){
        //     // ถ้าข้อมูลซ้ำให้ออกลูป
        //     $aReturn = array(
        //         'nStaEvent' => '800',
        //         'tStaMessg' => "Data Code Is Duplicate."
        //     );
        // }else{
            // Insert Data
            $this->db->trans_begin();
            $this->mReciveSpc->FSaMRCVSPCAddMaster($aDataMaster);
            if($this->db->trans_status() === false){
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess"
                );
            }else{
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                    'nStaEvent'	    => '1',
                    'tStaMessg'		=> 'Success '
                );
            }
        // }
        unset($aDataMaster);
        unset($aChkAppCode);
        echo json_encode($aReturn);
    }
   

    //Functionality : Function Add Cardlogin
    //Parameters : From Ajax File Cardlogin
    //Creator : 26/11/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : Status Add Event
    //Return Type : array
    public function  FSaCReciveSpcEditEvent(){
        $aDataMaster    = array(
            'FTRcvCode'         => $this->input->post('ohdRcvSpcCode'),
            'FTAppCode'         => $this->input->post('oetRcvSpcAppCode'),
            'FNRcvSeq'          => $this->input->post('ohdRcvSpcRcvSeq'),
            'FTBchCode'         => $this->input->post('oetRcvSpcBchCode'),
            'FTMerCode'         => $this->input->post('oetRcvSpcMerCode'),
            'FTShpCode'         => $this->input->post('oetRcvSpcShpCode'),
            'FTAggCode'         => $this->input->post('oetRcvSpcAggCode'),
            'FTPdtRmk'          => $this->input->post('oetRcvSpcRemark'),
            'FTAppStaAlwRet'    => (!empty($this->input->post('ocbRcvSpcStaAlwRet')))? 1 : 2,
            'FTAppStaAlwCancel' => (!empty($this->input->post('ocbRcvSpcStaAlwCancel')))? 1 : 2,
            'FTAppStaPayLast'   => (!empty($this->input->post('ocbRcvSpcStaPayLast')))? 1 : 2,
        );
        $this->db->trans_begin();
        $this->mReciveSpc->FSaMRCVSPCUpdateMaster($aDataMaster);
        if($this->db->trans_status() === false){
            $this->db->trans_rollback();
            $aReturn    = array(
                'nStaEvent'    => '900',
                'tStaMessg'    => "Unsucess"
            );
        }else{
            $this->db->trans_commit();
            $aReturn    = array(
                'nStaCallBack'	=> $this->session->userdata('tBtnSaveStaActive'),
                'nStaEvent'	    => '1',
                'tStaMessg'		=> 'Success '
            );
        }
        unset($aDataMaster);
        echo json_encode($aReturn);
    }

    //Functionality : Event Delete Cardlogin
    //Parameters : Ajax jReason()
    //Creator : 26/11/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : Status Delete Event
    //Return Type : String
    public function FSaCReciveSpcDeleteEvent(){
        $aDataWhereDel  = $this->input->post('paDataWhere');
        $aDataMaster    = [
            'FTRcvCode' => $aDataWhereDel['ptRcvCode'],
            'FTAppCode' => $aDataWhereDel['ptAppCode'],
            'FNRcvSeq'  => $aDataWhereDel['pnRcvSeq'],
            'FTBchCode' => $aDataWhereDel['ptBchCode'],
            'FTMerCode' => $aDataWhereDel['ptMerCode'],
            'FTShpCode' => $aDataWhereDel['ptShpCode'],
            'FTAggCode' => $aDataWhereDel['ptAggCode'],
        ];
        $aResDel        = $this->mReciveSpc->FSnMRCVSpcDel($aDataMaster);
        $nNumRowRsnLoc  = $this->mReciveSpc->FSnMLOCGetAllNumRow();
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

 
    //Functionality : Delete Cardlogin Ads Multiple
    //Parameters : Ajax jUserlogin()
    //Creator : 26/11/2019 Witsarut
    //Return : array Data Return Status Delete
    //Return Type : array
    public function FSoCReciveSpcDelMultipleEvent(){
        try{
            $this->db->trans_begin();
            $aDataWhereDel  = $this->input->post('paDataWhere');
            $aDataDelete    = [
                'FTRcvCode' => $aDataWhereDel['paRcvCode'],
                'FTAppCode' => $aDataWhereDel['paAppCode'],
                'FNRcvSeq'  => $aDataWhereDel['paRcvSeq'],
                'FTBchCode' => $aDataWhereDel['paBchCode'],
                'FTMerCode' => $aDataWhereDel['paMerCode'],
                'FTShpCode' => $aDataWhereDel['paShpCode'],
                'FTAggCode' => $aDataWhereDel['paAggCode'],
            ];
            $tResult    = $this->mReciveSpc->FSaMRCVSPCDeleteMultiple($aDataDelete);
            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                $aDataReturn    = array(
                    'nStaEvent' => 500,
                    'tStaMessg' => 'Error Not Delete Data Multiple'
                );
            }else{
                $this->db->trans_commit();
                $aDataReturn     = array(
                    'nStaEvent' => 1,
                    'tStaMessg' => 'Success Delete  Multiple'
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