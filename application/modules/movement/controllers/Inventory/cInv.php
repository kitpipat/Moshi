<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class cInv extends MX_Controller {

    public function __construct(){
        parent::__construct ();
        $this->load->model('Movement/Movement/mMovement');//
        $this->load->model('Movement/Inventory/mInv');
        date_default_timezone_set("Asia/Bangkok");
    }

    /*
    public function index($nMovementType,$tMovementOption){
        $vBtnSave           = FCNaHBtnSaveActiveHTML('movement/0/0');
        $aAlwEventMovement	= FCNaHCheckAlwFunc('movement/0/0');
        $this->load->view ( 'Movement/Movement/wMovement', array (
            'vBtnSave'          =>$vBtnSave,
            'nMovementType'     =>$nMovementType,
            'tMovementOption'   =>$tMovementOption,
            'aAlwEventMovement' =>$aAlwEventMovement
        ));
    }
    */

    /**
     * Functionality : แสดงหน้า list รายการสินค้าคงคลัง
     * Parameters : -
     * Creator : 15/04/2020 surawat
     * Last Modified : -
     * Return : html ฟอร์มค้นหารายการ
     * Return Type : html
     */
    public function FSxCInvPageList(){
        $aAlwEventMovement	       = FCNaHCheckAlwFunc('movement/0/0');
        $tUsrBchCode     = $this->session->userdata("tSesUsrBchCode");
        $tUsrShpCode     = $this->session->userdata("tSesUsrShpCode");
        //ตรวจสอบ config ว่าอนุญาตให้แสดง Browse ร้านค้าหรือไม่ 15/04/2020 surawat
        $aSysConfigResult = FCNaGetSysConfig([  'tSysCode' => 'bCN_BrowseShpEnabled',
                                                'tSysApp' => 'WEB',
                                                'tSysKey' => 'BrowseShpEnabled',
                                                'tSysSeq' => '1']);
        $bBrowseShpConfigValue = true;
        if($aSysConfigResult['rtCode'] == 1){
            $aBrowseShpConfig = $aSysConfigResult['raItems'];
            $nSysStaDefValue = $aBrowseShpConfig['FTSysStaDefValue'];
            $nSysStaUsrValue = $aBrowseShpConfig['FTSysStaUsrValue'];
            
            if($nSysStaUsrValue != ''){
                $bBrowseShpConfigValue = $nSysStaUsrValue; //ถ้า Sys User มีค่าจะใช้ค่าของ UserValue
            }else{
                $bBrowseShpConfigValue = $nSysStaDefValue; //ถ้า Sys User ไม่มีค่าจะใช้ค่าของ DefValue
            }
        }
        // end ตรวจสอบ config ว่าอนุญาตให้แสดง Browse ร้านค้าหรือไม่ 15/04/2020 surawat
        $this->load->view('Movement/Inventory/wInvList', array(
            'aAlwEventMovement'    => $aAlwEventMovement,
            'tUsrBchCode'          => $tUsrBchCode,
            'tUsrShpCode'          => $tUsrShpCode,
            'bBrowseShpEnabled' => $bBrowseShpConfigValue
        ));
    }

    /**
     * Functionality : แสดงหน้า list รายการสินค้าคงคลัง
     * Parameters : -
     * Creator : 15/04/2020 surawat
     * Last Modified : -
     * Return : html ฟอร์มค้นหารายการ
     * Return Type : html
     */
    public function FSxCInvDataTableList(){
        try{
            $nPage          = ($this->input->post('nPageCurrent') == '' || null)? 1 : $this->input->post('nPageCurrent');   // Check Number Page
            $nRow           = 10;
            $nLangResort    = $this->session->userdata("tLangID");
            $nLangEdit      = $this->session->userdata("tLangEdit");
            $tDataSearch    = $this->input->post('tDataFilter');
            $tSearchAll     = json_decode($tDataSearch, true);
            $aData  = array(
                'nPage'         => $nPage,
                'nRow'          => $nRow,
                'FNLngID'       => $nLangEdit,
                'tSearchAll'    => $tSearchAll
            );
            // $aMovementDataList               = $this->mMovement->FSaMMovementList($aData);
            $aInvDataList               = $this->mInv->FSaMInvList($aData);
            $aAlwEventMovement	             = FCNaHCheckAlwFunc('movement/0/0');
            $aGenTable  = array(
                'aDataList'                 => $aInvDataList,
                'nPage'                     => $nPage,
                'nRow'                      => $nRow,
                'tSearchAll'                => $tSearchAll,
                'aAlwEventMovement'         => $aAlwEventMovement,
                'tSearchAll'                => $tSearchAll
            );
            $this->load->view('Movement/Inventory/wInvDataTable',$aGenTable);
        }catch(Exception $Error){
            echo $Error;
        }
    }





}

