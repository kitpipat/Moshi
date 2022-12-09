<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class cSettingconfig extends MX_Controller {

    public function __construct(){
        parent::__construct ();
        $this->load->model('settingconfig/settingconfig/mSettingconfig');
    }
    
    public function index($nBrowseType, $tBrowseOption){
        $aData['nBrowseType']       = $nBrowseType;
        $aData['tBrowseOption']     = $tBrowseOption;
        $aData['aAlwEvent']         = FCNaHCheckAlwFunc('settingconfig/0/0');
        $aData['vBtnSave']          = FCNaHBtnSaveActiveHTML('settingconfig/0/0'); 
        $aData['nOptDecimalShow']   = FCNxHGetOptionDecimalShow(); 
        $aData['nOptDecimalSave']   = FCNxHGetOptionDecimalSave(); 
        $this->load->view('settingconfig/settingconfig/wSettingconfig', $aData);
    }

    //Get Page List (Tab : ตั้งค่าระบบ , รหัสอัตโนมัติ)
    public function FSvSETGetPageList(){
        $this->load->view('settingconfig/settingconfig/wSettingconfigList');
    }

    /////////////////////////////////////////////////////////////////////////////////////////////// แท็บตั้งค่าระบบ

    //Get Page List (Content : แท็บตั้งค่าระบบ)
    public function FSvSETGetPageListSearch(){
        $aOption = $this->mSettingconfig->FSaMSETGetAppTpye();
        $aReturn = array(
            'aOption' => $aOption
        );
        $this->load->view('settingconfig/settingconfig/Config/wConfigList',$aReturn);
    }

    //Get Table (แท็บตั้งค่าระบบ)
    public function FSvSETSettingGetTable(){
        $aAlwEvent      = FCNaHCheckAlwFunc('settingconfig/0/0'); 
        $tAppType       = $this->input->post('tAppType');
        $tSearch        = $this->input->post('tSearch');
        $nLangResort    = $this->session->userdata("tLangID");
	    $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData  = array(
            'FNLngID'       => $nLangEdit,
            'tSearchAll'    => $tSearch,
            'tAppType'      => $tAppType
        );

        $aResListCheckbox       = $this->mSettingconfig->FSaMSETConfigDataTableByType($aData,'checkbox');
        $aResListInputText      = $this->mSettingconfig->FSaMSETConfigDataTableByType($aData,'input');
        $aGenTable  = array(
            'aAlwEvent'             => $aAlwEvent,
            'aResListCheckbox'      => $aResListCheckbox,
            'aResListInputText'     => $aResListInputText
        );

        $this->load->view('settingconfig/settingconfig/Config/wConfigDatatable',$aGenTable);
    }

    //Event Save (แท็บตั้งค่าระบบ)
    public function FSxSETSettingEventSave(){
        $aMergeArray = $this->input->post('aMergeArray');
        if(count($aMergeArray) >= 1){
            for($i=0; $i<count($aMergeArray); $i++){

                //Type
                if($aMergeArray[$i]['tType'] == 'checkbox'){
                    $nType = 4;
                }else{
                    $nType = $aMergeArray[$i]['tType'];
                }

                //Packdata
                $aUpdate = array(
                    'FTSysCode'             =>  $aMergeArray[$i]['tSyscode'],
                    'FTSysApp'              =>  $aMergeArray[$i]['tSysapp'],
                    'FTSysKey'              =>  $aMergeArray[$i]['tSyskey'],
                    'FTSysSeq'              =>  $aMergeArray[$i]['tSysseq'],
                    'FTSysStaDataType'      =>  $nType,
                    'nValue'                =>  $aMergeArray[$i]['nValue'],
                    'tKind'                 =>  $aMergeArray[$i]['tKind'],
                    'FDLastUpdOn'           => date('Y-m-d H:i:s'),
                    'FTLastUpdBy'           => $this->session->userdata('tSesUsername'),
                );

                //Update
                $aResList   = $this->mSettingconfig->FSaMSETUpdate($aUpdate);
            }
        }
    }

    //Event Use Default value ใช้แม่แบบ (แท็บตั้งค่าระบบ)
    public function FSxSETSettingUseDefaultValue(){
        $aReturn = $this->mSettingconfig->FSaMSETUseValueDefult();
        echo $aReturn;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////// แท็บรหัสอัตโนมัติ

    //Get Page List (Content : แท็บรหัสอัตโนมัติ)
    public function FSvSETAutonumberGetPageListSearch(){
        $aOption = $this->mSettingconfig->FSaMSETGetAppTpye();
        $aReturn = array(
            'aOption' => $aOption
        );
        $this->load->view('settingconfig/settingconfig/Autonumber/wAutonumberList',$aReturn);
    }

    //Get Table (แท็บรหัสอัตโนมัติ)
    public function FSvSETAutonumberSettingGetTable(){
        $aAlwEvent      = FCNaHCheckAlwFunc('settingconfig/0/0'); 
        $tAppType       = $this->input->post('tAppType');
        $tSearch        = $this->input->post('tSearch');
	    $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData  = array(
            'FNLngID'       => $nLangEdit,
            'tSearchAll'    => $tSearch,
            'tAppType'      => $tAppType
        );

        $aItemRecord    = $this->mSettingconfig->FSaMSETConfigDataTableAutoNumber($aData);
        $aGenTable      = array(
            'aAlwEvent'        => $aAlwEvent,
            'aItemRecord'      => $aItemRecord
        );

        $this->load->view('settingconfig/settingconfig/Autonumber/wAutonumberDatatable',$aGenTable);
    }

    //Load Page Edit
    public function FSvSETAutonumberPageEdit(){
        $aAlwEvent   = FCNaHCheckAlwFunc('settingconfig/0/0'); 
        $tTable      = $this->input->post('ptTable');
        $nSeq        = $this->input->post('pnSeq');

        $aWhere      = array(
            'FTSatTblName'      => $tTable,
            'FTSatStaDocType'   => $nSeq
        );
        $aAllowItem  = $this->mSettingconfig->FSaMSETConfigGetAllowDataAutoNumber($aWhere);
        
        $aGenTable   = array(
            'aAlwEvent'         => $aAlwEvent,
            'aAllowItem'        => $aAllowItem,
            'nMaxFiledSizeBCH'  => $this->mSettingconfig->FSaMSETGetMaxLength('TCNMBranch'),
            'nMaxFiledSizePOS'  => $this->mSettingconfig->FSaMSETGetMaxLength('TCNMPos')
        );
        $this->load->view('settingconfig/settingconfig/Autonumber/wAutonumberPageAdd',$aGenTable);
    }

    //บันทึก
    public function FSvSETAutonumberEventSave(){
        $tTypedefault   = $this->input->post('tTypedefault');
        $aPackData      = $this->input->post('aPackData');
        if($tTypedefault == 'default'){
            $aDelete = array(
                'FTAhmTblName'      => $aPackData[0],
                'FTAhmFedCode'      => $aPackData[1],
                'FTSatStaDocType'	=> $aPackData[2]    
            );
            $tResult = $this->mSettingconfig->FSaMSETAutoNumberDelete($aDelete);
        }else{
            $aIns = array(
                'FTAhmTblName'      => $aPackData[0],
                'FTAhmFedCode'      => $aPackData[1],
                'FTSatStaDocType'   => $aPackData[2],
                'FTAhmFmtAll'       => $aPackData[3]['FTAhmFmtAll'],
                'FTAhmFmtPst'       => $aPackData[3]['FTAhmFmtPst'], 
                'FNAhmFedSize'      => $aPackData[3]['FNAhmFedSize'],
                'FTAhmFmtChar'      => $aPackData[3]['FTAhmFmtChar'],
                'FTAhmStaBch'       => $aPackData[3]['FTAhmStaBch'],
                'FTAhmFmtYear'      => $aPackData[3]['FTAhmFmtYear'],
                'FTAhmFmtMonth'     => $aPackData[3]['FTAhmFmtMonth'],
                'FTAhmFmtDay'       => $aPackData[3]['FTAhmFmtDay'],
                'FTSatStaAlwSep'    => $aPackData[3]['FTSatStaAlwSep'],
                'FNAhmLastNum'      => $aPackData[3]['FNAhmLastNum'],
                'FNAhmNumSize'      => $aPackData[3]['FNAhmNumSize'],
                'FTAhmStaReset'     => $aPackData[3]['FTAhmStaReset'],
                'FTAhmFmtReset'     => $aPackData[3]['FTAhmFmtReset'],
                'FTAhmLastRun'      => $aPackData[3]['FTAhmLastRun'],
                'FTSatUsrNum'       => $aPackData[3]['FTSatUsrNum'],
                'FDLastUpdOn'       => date('Y-m-d H:i:s'),
                'FTLastUpdBy'       => $this->session->userdata('tSesUsername'),
                'FDCreateOn'        => date('Y-m-d H:i:s'),
                'FTCreateBy'        => $this->session->userdata('tSesUsername'),
            );

            //Delete ก่อน
            $this->mSettingconfig->FSaMSETAutoNumberDelete($aIns);

            //Insert
            $aResultInsert = $this->mSettingconfig->FSaMSETAutoNumberInsert($aIns);
        }
    }


}
