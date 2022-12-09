<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class cBranch extends MX_Controller {

	public function __construct() {
			parent::__construct ();
			$this->load->model('company/branch/mBranch');
			$this->load->model('company/shop/mShop');
			$this->load->model('company/warehouse/mWarehouse');
			date_default_timezone_set("Asia/Bangkok");
	}

	public function index($nBrowseType,$tBrowseOption){
		$aBrowseType = explode("-",$nBrowseType);
		if(isset($aBrowseType[1])){
			$nBrowseType = $aBrowseType[0];
			$tRouteFromName = $aBrowseType[1];
		}else{
			$nBrowseType = $nBrowseType;
			$tRouteFromName = '';
		}
		
		//Controle Event
		$aAlwEventBranch 	= FCNaHCheckAlwFunc('branch/0/0');
		$aAlwEventShop 		= FCNaHCheckAlwFunc('shop/0/0');

		//Controle Event
		$vBtnSaveBranch 	= FCNaHBtnSaveActiveHTML('branch/0/0'); //Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
		$vBtnSaveShop		= FCNaHBtnSaveActiveHTML('shop/0/0'); 
		$aDataConfigView	= [
			'aAlwEventBranch'	=> $aAlwEventBranch,
			'aAlwEventShop'		=> $aAlwEventShop,
			'vBtnSaveBranch'	=> $vBtnSaveBranch,
			'vBtnSaveShop' 		=> $vBtnSaveShop,
			'nBrowseType'		=> $nBrowseType,
			'tBrowseOption'		=> $tBrowseOption,
			'tRouteFromName'	=> $tRouteFromName
		];
		$this->load->view ('company/branch/wBranch',$aDataConfigView);
	}



	//Functionality : Function CallPage List
	//Parameters : From Ajax File j
	//Creator : 27/03/2018
	//Last Modified : -
	//Return : String View
	//Return Type : View
	public function FSvCBCHListPage(){
		$aAlwEventBranch   = FCNaHCheckAlwFunc('branch/0/0');
		$aNewData  		   = ['aAlwEventBranch' => $aAlwEventBranch];
		$this->load->view('company/branch/wBranchList',$aNewData);
	}




	//Functionality : Function Call DataTables Branch
    //Parameters : Ajax jBranch()
    //Creator : 05/06/2018 Krit(Copter)
    //Last Modified : -
    //Return : String View
    //Return Type : View
    public function FSvCBCHDataList(){
		$aAlwEvent  = FCNaHCheckAlwFunc('branch/0/0'); //Controle Event
        $nPage      = $this->input->post('nPageCurrent');
		$tSearchAll = $this->input->post('tSearchAll');
        if($nPage == '' || $nPage == null){$nPage = 1;}else{$nPage = $this->input->post('nPageCurrent');}
        if(!$tSearchAll){$tSearchAll='';}
        //Lang ภาษา
		$nLangEdit      = $this->session->userdata("tLangEdit");

        $aData  = array(
			'FTBchCode'		=> $this->session->userdata("tSesUsrBchCodeOld"),
            'nPage'         => $nPage,
            'nRow'          => 10,
            'FNLngID'       => $nLangEdit,
            'tSearchAll'    => $tSearchAll
        );

		$aResList = $this->mBranch->FSnMBCHList($aData);
        $aGenTable  = array(
			'aAlwEventBranch' 	=> $aAlwEvent,
            'aDataList' 		=> $aResList,
			'nPage'    		    => $nPage,
            'tSearchAll'    	=> $tSearchAll
		);
        $this->load->view('company/branch/wBranchDataTable',$aGenTable);
    }




	//Functionality : Function CallPage Branch Add
	//Parameters : Ajax jReason()
	//Creator : 03/04/2018 Krit(Copter)
	//Last Modified : -
	//Return : String View
	//Return Type : View
	public function FSvCBCHAddPage(){
		$aCnfAddPanal 		= $this->FSvCBCHGenViewAddress();
		$aAlwEvent 			= FCNaHCheckAlwFunc('branch/0/0'); 
		$aCnfAddVersion  = FCNaHAddressFormat('TCNMBranch');
		$nSysStaDefValue = $aCnfAddVersion;
		$nSysStaUsrValue = $aCnfAddVersion;
		
		if($nSysStaUsrValue != ''){
			$nCnfAddVersion = $nSysStaUsrValue; //ถ้า Sys User มีค่าจะใช้ค่าของ UserValue
		}else{
			$nCnfAddVersion = $nSysStaDefValue; //ถ้า Sys User ไม่มีค่าจะใช้ค่าของ DefValue
		}
		
		//ภาษาของระบบทั้งหมด
		$aSysLangForBch = $this->mBranch->FSvMBCHGetSyslangSystems();

		$aDataEdit = array(
				'aResult' 			=> array('rtCode' => '99'),
				'aCnfAddPanal' 		=> $aCnfAddPanal,
				'nCnfAddVersion' 	=> $nCnfAddVersion,
				'aAlwEventBranch' 	=> $aAlwEvent,
				'aSysLangForBch'	=> $aSysLangForBch
		);
		$this->load->view('company/branch/wBranchAdd',$aDataEdit);

	}
	

	//call branch edit
	public function FSvCBCHGenViewAddress($paResList = '',$nCnfAddVersion = ''){

		$nLangResort = $this->session->userdata("tLangID");
		$nLangEdit	 = $this->session->userdata("tLangEdit");
		
		// $aLangHave = FCNaHGetAllLangByTable('TCNMBranch_L');
		// $nLangHave = count($aLangHave);
		
		// if($nLangHave > 1){
		// 	if($nLangEdit != ''){
		// 		$nLangEdit = $nLangEdit;
		// 	}else{
		// 		$nLangEdit = $nLangResort;
		// 	}
		// }else{
		// 		if(@$aLangHave[0]->nLangList == ''){
		// 			$nLangEdit = '1';
		// 		}else{
		// 			$nLangEdit = $aLangHave[0]->nLangList;
		// 		}
		// }
		
		if(isset($paResList['roItem']['rtBchCode'])){
			
			$tBchCode = $paResList['roItem']['rtBchCode'];
			
			$aData = array(
				'FNLngID' 			=> $nLangEdit,
				'FTAddGrpType' 		=> '1',
				'FTAddVersion' 		=> $nCnfAddVersion,
				'FTAddRefCode' 		=> $tBchCode,
			);
			
			$aCnfAddEdit    = $this->mBranch->FSvMBCHGetAddress($aData);
			
		}else{
			$tBchCode = '';
			$aCnfAddEdit = '';
		}
		
		return $aCnfAddEdit;

	}


	//Functionality : Event Branch Add
	//Parameters : Ajax  ()
	//Creator : 27/03/2018 wasin(yoshi)
	//update : 14/06/2019	saharat(Golf)
	//Last Modified : -
	//Return : Status ReasonAdd
	//Return Type : array
	public function FSaCBCHAddEvent(){
		//Set DateTime Bangkok
		date_default_timezone_set("Asia/Bangkok");
		// Input Image 
		$tBranchImageOld	= trim($this->input->post('oetImgInputbranchOld'));
		$tBranchImage		= trim($this->input->post('oetImgInputbranch'));
		if($this->input->post('ocbBchStaHQ') !== NULL){
			$tBchStaHQ = '1';
		}else{
			$tBchStaHQ = '';
		}

		//Set DateTime Bangkok
		$aDataMaster = array(
			'tIsAutoGenCode'    => $this->input->post('ocbBrachAutoGenCode'),
			'FTBchCode'     	=> $this->input->post('oetBchCode'),
			'FTPplCode'         => $this->input->post('oetBchPplRetCode'),
			'FTBchType'     	=> $this->input->post('ocmBchType'),
			'FTWahCode'     	=> $this->input->post('oetBchWahCode'),
			'FTBchPriority'     => $this->input->post('ocmBchPriority'),
			'FTBchRegNo'     	=> $this->input->post('oetBchRegNo'),
			'FTBchRefID'     	=> $this->input->post('oetBchRefID'),
			'FNBchDefLang'		=> $this->input->post('ocmLangBchSystem'),
			'FDBchStart'     	=> FCNdHConverDate($this->input->post('oetBchStart')),
			'FDBchStop'     	=> FCNdHConverDate($this->input->post('oetBchStop')), 
			'FDBchSaleStart'    => FCNdHConverDate($this->input->post('oetBchSaleStart')),  
			'FDBchSaleStop'     => FCNdHConverDate($this->input->post('oetBchSaleStop')),
			'FTBchStaActive'    => $this->input->post('ocmBchStaActive'),
			'FTBchStaHQ'		=> $tBchStaHQ,
			'FDLastUpdOn' 		=> date('Y-m-d H:i:s'),
			'FDCreateOn'        => date('Y-m-d H:i:s'),
			'FTCreateBy'        => $this->session->userdata("tSesUsername"),
			'FTLastUpdBy' 		=> $this->session->userdata('tSesUsername'),
			'FNLngID'           => $this->session->userdata("tLangEdit"),
			'FTBchName'         => $this->input->post('oetBchName'),
			'FTBchRmk'          => $this->input->post('oetBchRmk'),
		);

		// Setup Branch Code
		if($aDataMaster['tIsAutoGenCode'] == '1'){ // Check Auto Gen User Code?
			// Auto Gen Branch Code
			$aGenCode = FCNaHGenCodeV5('TCNMBranch','0');
			if($aGenCode['rtCode'] == '1'){
				$aDataMaster['FTBchCode'] = $aGenCode['rtBchCode'];
			}
			
		}
	
		//Array Data ของ Branch  Address
		$tZneChainName	= $aDataMaster['FTBchCode'].$this->input->post('oetBchZneCode');
		$aResAdd		= $this->mBranch->FSaMBCHAdd($aDataMaster);
		//เพิ่มคลัง 00001 00002 00003 16/03/2020 saharat
		$this->mBranch->FSaMBCHWahDefaultInsert($aDataMaster);
		$this->mBranch->FSaMBCHWahDefaultInsert_L($aDataMaster);

		if($aResAdd['rtCode'] == '1'){
			if($tBranchImage != $tBranchImageOld){
				$aImageUplode = array(
					'tModuleName'       => 'company',
					'tImgFolder'        => 'branch',
					'tImgRefID'         => $aDataMaster['FTBchCode'],
					'tImgObj'           => $tBranchImage,
					'tImgTable'         => 'TCNMBranch',
					'tTableInsert'      => 'TCNMImgObj',
					'tImgKey'           => 'main',
					'dDateTimeOn'       => date('Y-m-d H:i:s'),
					'tWhoBy'            => $this->session->userdata('tSesUsername'),
					'nStaDelBeforeEdit' => 1
				);
				FCNnHAddImgObj($aImageUplode);
			}
			$nStatus = $aResAdd['rtCode'];
		}else{
			$nStatus = $aResAdd['rtCode'];
		}
	    echo $nStatus.",".$aResAdd['rtDesc'].",".$aResAdd['nStaCallBack'];
	}

	//Functionality : Event Reason Edit
	//Parameters : Ajax jReason()
	//Creator : 27/03/2018 wasin(yoshi)
	//Last Modified : -
	//Return : Status ReasonEdit
	//Return Type : array
	public function FSaCBCHEditEvent(){
		//Set DateTime Bangkok
		date_default_timezone_set("Asia/Bangkok");
		if(null !== $this->input->post('ocbBchStaHQ')){
			$tBchStaHQ = '1';
		}else{
			$tBchStaHQ = '';
		}
		// Input Image 
		$tBranchImageOld	= trim($this->input->post('oetImgInputbranchOld'));
		$tBranchImage		= trim($this->input->post('oetImgInputbranch'));
		$aDataMaster = array(
				'FTBchCode'     	=> $this->input->post('oetBchCode'),
				'FTPplCode'         => $this->input->post('oetBchPplRetCode'),
				'FTWahCode'     	=> $this->input->post('oetBchWahCode'),
				'FTBchType'     	=> $this->input->post('ocmBchType'),
				'FTBchPriority'     => $this->input->post('ocmBchPriority'),
				'FTBchRegNo'     	=> $this->input->post('oetBchRegNo'),
				'FTBchRefID'     	=> $this->input->post('oetBchRefID'),
				'FNBchDefLang'		=> $this->input->post('ocmLangBchSystem'),
				'FDBchStart'     	=> FCNdHConverDate($this->input->post('oetBchStart')),
				'FDBchStop'     	=> FCNdHConverDate($this->input->post('oetBchStop')), 
				'FDBchSaleStart'    => FCNdHConverDate($this->input->post('oetBchSaleStart')),  
				'FDBchSaleStop'     => FCNdHConverDate($this->input->post('oetBchSaleStop')),
				'FTBchStaActive'    => $this->input->post('ocmBchStaActive'),
				'FTBchStaHQ'		=> $tBchStaHQ,
				'FDLastUpdOn'     	=> date('Y-m-d h:i:s'),
				'FTLastUpdBy'      	=> $this->session->userdata("tSesUsername"),
				'FNLngID'       	=> $this->session->userdata("tLangEdit"),
				'FTBchName'     	=> $this->input->post('oetBchName'),
				'FTBchRmk'     		=> $this->input->post('oetBchRmk'),

		);


		//Array Data ของ Branch  Address
		// $tZneChainName 			   = $aDataMaster['FTBchCode'].$this->input->post('oetBchZneCode');
		 $tZneChainName 			   = $this->input->post('oetBchZneCode');

		$aDataAddress = array(
				'FTAddV1No'        => $this->input->post('oetAddV1No'),
				'FTAddV1Soi'       => $this->input->post('oetAddV1Soi'),
				'FTAddV1Village'   => $this->input->post('oetAddV1Village'),
				'FTAddV1Road'      => $this->input->post('oetAddV1Road'),
				'FTAddV1SubDist'   => $this->input->post('oetAddV1SubDistCode'),
				'FTAddV1DstCode'   => $this->input->post('oetAddV1DstCode'),
				'FTAddV1PvnCode'   => $this->input->post('oetAddV1PvnCode'),
				'FTAddV1PostCode'  => $this->input->post('oetAddV1PostCode'),
				'FTAddV2Desc1'     => $this->input->post('oetAddV2Desc1'),
				'FTAddV2Desc2'     => $this->input->post('oetAddV2Desc2'),
				'FTAreCode'        => $this->input->post('oetBchAreCode'),
				'FTZneCode'        => $tZneChainName,
				'FTZneChain'        => $this->input->post('oetBchZneName'),
				'FTZneTable'       => 'Branch', 
				'FTAddGrpType'     => '1',
				'FTAddVersion'     => $this->input->post('ohdAddVersion'),
				'FNLngID'          => $this->session->userdata("tLangEdit"),
				'FTAddGrpType'     => '1',
				'FDCreateOn'       => date('Y-m-d H:i:s'),
				'FDLastUpdOn'      => date('Y-m-d H:i:s'),
				'FTAddRefCode'     => $aDataMaster['FTBchCode'],
				'FTCreateBy'       => $this->session->userdata("tSesUsername"),
				'FTAddVersion'     => $this->input->post('ohdAddVersion'),

			);

		$aResAdd = $this->mBranch->FSaMBCHUpdate($aDataMaster);
		if($aResAdd['rtCode'] == '1'){
			if($tBranchImage != $tBranchImageOld){
				$aImageUplode = array(
					'tModuleName'       => 'company',
					'tImgFolder'        => 'branch',
					'tImgRefID'         => $aDataMaster['FTBchCode'],
					'tImgObj'           => $tBranchImage,
					'tImgTable'         => 'TCNMBranch',
					'tTableInsert'      => 'TCNMImgObj',
					'tImgKey'           => 'main',
					'dDateTimeOn'       => date('Y-m-d H:i:s'),
					'tWhoBy'            => $this->session->userdata('tSesUsername'),
					'nStaDelBeforeEdit' => 1
				);
				$aResAddImgObj	= FCNnHAddImgObj($aImageUplode);
			}
			$nStatus = $aResAdd['rtCode'];
		}else{
			$nStatus = $aResAdd['rtCode'];
		}
		echo $nStatus.",".$aResAdd['rtDesc'];
	}


	//Functionality : Function CallPage Branch Edit
	//Parameters : Ajax jReason()
	//Creator : 30/03/2018 Krit(Copter)
	//Last Modified : 02/04/2018 Krit(Copter)
	//Return : String View
	//Return Type : View
	public function FSvCBCHEditPage($ptBchCode = '',$ptUserLevel = ''){
		//ส่ง BchCode มาจาก Function Check Level
		$aAlwEvent = FCNaHCheckAlwFunc('branch/0/0'); 
		if(@$ptBchCode){
			$tBchCode = $ptBchCode;
		}else{
			$tBchCode = $this->input->post('tBchCode');
		}

		$aData = array(
				'FTBchCode' => $tBchCode,
				'FNLngID'   => $this->session->userdata("tLangEdit"),
		);
		$aResList       = $this->mBranch->FSaMBchSearchByID($aData);

		$aCnfAddVersion  = FCNaHAddressFormat('TCNMBranch');

		$nSysStaDefValue = $aCnfAddVersion;
		$nSysStaUsrValue = $aCnfAddVersion;
		
		if($nSysStaUsrValue != ''){
			$nCnfAddVersion = $nSysStaUsrValue; //ถ้า Sys User มีค่าจะใช้ค่าของ UserValue
		}else{
			$nCnfAddVersion = $nSysStaDefValue; //ถ้า Sys User ไม่มีค่าจะใช้ค่าของ DefValue
		}
		$aCnfAddPanal = $this->FSvCBCHGenViewAddress($aResList,$nCnfAddVersion);

		if(isset($aResList['roItem']['rtImgObj']) && !empty($aResList['roItem']['rtImgObj'])){
            $tImgObj 		= $aResList['roItem']['rtImgObj'];
            $aImgObjPath	= explode("application/modules/",$tImgObj);
			$aImgObjName	= explode("/",$tImgObj);
			$tImgObjPath	= $aImgObjPath[1];
			$tImgObjName	= end($aImgObjName);
        }else{
			$tImgObjPath	= "";
			$tImgObjName	= "";
		}

		//ภาษาของระบบทั้งหมด
		$aSysLangForBch = $this->mBranch->FSvMBCHGetSyslangSystems();

		$aDataEdit  = array(
				'aResult'       	=> $aResList,
				'aCnfAddPanal' 		=> $aCnfAddPanal,
				'nCnfAddVersion' 	=> $nCnfAddVersion,
				'aAlwEventBranch' 	=> $aAlwEvent,
				'tImgObjPath'		=> $tImgObjPath,
				'tImgObjName'		=> $tImgObjName,
				'aSysLangForBch'	=> $aSysLangForBch
		);

		$this->load->view('company/branch/wBranchAdd',$aDataEdit);
	}


	//Functionality : Event Reason Delete
	//Parameters : Ajax jReason()
	//Creator : 02/04/2018 Krit(Copter)
	//Last Modified : -
	//Return : Status Warehouse Delete
	//Return Type : array
	public function FSaCBCHDeleteEvent(){
		
		$tIDCode = $this->input->post('tIDCode');
		$aDataMaster = array(
			'FTBchCode' => $tIDCode
		);
		$aResDel   = $this->mBranch->FSnMBCHDel($aDataMaster);
		$aDeleteImage = array(
			'tModuleName'  => 'branch',
			'tImgFolder'   => 'branch',
			'tImgRefID'    => $tIDCode,
			'tTableDel'    => 'TCNMImgObj',
			'tImgTable'    => 'TCNMBranch',
		);
		$nStaDelImgInDB =   FSnHDelectImageInDB($aDeleteImage);
		if($nStaDelImgInDB == 1){
			FSnHDeleteImageFiles($aDeleteImage);
		}
		 print_r(json_encode($aResDel));	 
	}

	function FSaCBCHDeleteEventFolder(){

					$tIDCode = $this->input->post('tIDCode');
		
					$files = glob('application/modules/common/assets/system/branch/'.$tIDCode.'/*'); //get all file names
					foreach($files as $file){
					if(is_file($file))
							unlink($file); //delete file
					}
					if (!is_dir('application/modules/common/assets/system/branch/'.$tIDCode)) {
						mkdir('application/modules/common/assets/system/branch/'.$tIDCode);
					}
					rmdir('application/modules/common/assets/system/branch/'.$tIDCode);
						
	}

}
