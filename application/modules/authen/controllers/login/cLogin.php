<?php defined( 'BASEPATH' ) or exit( 'No direct script access allowed' );

class cLogin extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library( "session" );
		if(@$_SESSION['tSesUsername'] == true) {
			redirect( '', 'refresh' );
			exit();
		}
	}

	public function index() {
		$this->load->view('authen/login/wLogin');
	}
    
	//Functionality: ตรวจสอบการเข้าใช้งานระบบ
	//Parameters:  รับค่าจากฟอร์ม type POST
	//Creator: 23/03/2018 Phisan(Arm)
	//Last Modified : 
	//Return : Error Code 
	//Return Type: Redirect
	public function FSnCLOGChkLogin(){
		try {
			$tUsername	= $this->input->post('oetUsername'); //ชื่อผู้ใช้
			$tPassword	= $this->input->post('oetPasswordhidden'); //รหัสผ่าน

			//   ตรวจสอบการล็อกอิน
			$this->load->model('authen/login/mLogin');
		
			$aDataUsr	= $this->mLogin->FSaMLOGChkLogin($tUsername,$tPassword);

			  if(!empty($aDataUsr[0])){
					  
					//case : เข้ามาแบบ HQ จะใช้ tSesUsrBchCom 
					//     : เข้ามาแบบ BCH , SHP จะใช้ tSesUsrBchCode 
					// $aDataUsrGroup = $this->mLogin->FSaMLOGGetDataUserLoginGroup($aDataUsr[0]['FTUsrCode']);
					// $aDataUsrRole  = $this->mLogin->FSaMLOGGetUserRole($aDataUsr[0]['FTUsrCode']);
					

					if($aDataUsr[0]['FTBchCode'] == '' || $aDataUsr[0]['FTBchCode'] == null){
						$aGetDataBch = $this->mLogin->FSaMLOGGetBch();

						// $tUsrAgnCodeDefult  = $aDataUsrGroup[0]['FTAgnCode'];
						// $tUsrAgnNameDefult  = $aDataUsrGroup[0]['FTAgnName'];

						$aGetBch 		= $aGetDataBch[0]['FTBchCode'];
						$aGetBchName 	= $aGetDataBch[0]['FTBchName'];
						$tWahCode		= $aGetDataBch[0]['FTWahCode'];
						$tWahName		= $aGetDataBch[0]['FTWahName'];
					}else{
						// $tUsrAgnCodeDefult  = $aDataUsrGroup[0]['FTAgnCode'];
						// $tUsrAgnNameDefult  = $aDataUsrGroup[0]['FTAgnName'];

						$aGetBch 		= $aDataUsr[0]['FTBchCode'];
						$aGetBchName 	= $aDataUsr[0]['FTBchName'];
						$tWahCode		= $aDataUsr[0]['FTWahCode'];
						$tWahName		= $aDataUsr[0]['FTWahName'];
					}
					$this->session->set_userdata ("tSesUsrBchCom", $aGetBch);
					$this->session->set_userdata ("tSesUsrBchNameCom", $aGetBchName);
					$this->session->set_userdata("tSesUsrLogin", $tUsername);
				
					$this->session->set_userdata('bSesLogIn',TRUE);
			  		$this->session->set_userdata("tSesUserCode", $aDataUsr[0]['FTUsrCode']);
			  		$this->session->set_userdata("tSesUsername", $aDataUsr[0]['FTUsrCode']);
					$this->session->set_userdata("nUsrID", 1);			
					$this->session->set_userdata("tSesUsrDptName", $aDataUsr[0]['FTDptName']);
					$this->session->set_userdata("tSesUsrDptCode", $aDataUsr[0]['FTDptCode']);
					$this->session->set_userdata("tSesUsrRoleCode", $aDataUsr[0]['FTRolCode']);
					$this->session->set_userdata("tSesUsrBchCode", $aDataUsr[0]['FTBchCode']);
					$this->session->set_userdata("tSesUsrBchName", $aDataUsr[0]['FTBchName']);
					$this->session->set_userdata("tSesUsrShpCode", $aDataUsr[0]['FTShpCode']);
					$this->session->set_userdata("tSesUsrShpName", $aDataUsr[0]['FTShpName']);
					//Name User
					$this->session->set_userdata("tSesUsrUsername", $aDataUsr[0]['FTUsrName']);
					//New Brach
					$this->session->set_userdata("tSesUsrBchCodeOld", $aDataUsr[0]['FTBchCode']);
					//New sessionID for document 

					$this->session->set_userdata("tSesUsrMerCode", $aDataUsr[0]['FTMerCode']);
					$this->session->set_userdata("tSesUsrMerName", $aDataUsr[0]['FTMerName']);
					$this->session->set_userdata("tSesUsrWahCode", $tWahCode);
					$this->session->set_userdata("tSesUsrWahName", $tWahName);

					$this->session->set_userdata("tSesUsrImagePerson", $aDataUsr[0]['FTImgObj']);
					
					$this->session->set_userdata("tSesUsrInfo", $aDataUsr[0]);

					$tDateNow = date('Y-m-d H:i:s');
					$tSessionID = $aDataUsr[0]['FTUsrCode'].date('YmdHis', strtotime($tDateNow)); 
					$this->session->set_userdata("tSesSessionID", $tSessionID);
					$this->session->set_userdata("tSesSessionDate", $tDateNow);

			  		$nLangEdit = $this->session->userdata("tLangEdit");
			  		if($nLangEdit == ''){
						$this->session->set_userdata( "tLangEdit", $this->session->userdata("tLangID") );
					}
					
					// User level
					if(empty($aDataUsr[0]['FTBchCode']) && empty($aDataUsr[0]['FTShpCode'])){ // HQ level
						$this->session->set_userdata("tSesUsrLevel", "HQ");
					}
					if(!empty($aDataUsr[0]['FTBchCode']) && empty($aDataUsr[0]['FTShpCode'])){ // BCH level
						$this->session->set_userdata("tSesUsrLevel", "BCH");
					}
					if(!empty($aDataUsr[0]['FTBchCode']) && !empty($aDataUsr[0]['FTShpCode'])){ // SHP level
						$this->session->set_userdata("tSesUsrLevel", "SHP");
					}

					// Agency
					// $this->session->set_userdata("tSesUsrAgnCode", $tUsrAgnCodeDefult);
					// $this->session->set_userdata("tSesUsrAgnName", $tUsrAgnNameDefult);


					$aDataUsrRoleCode	= $this->mLogin->FSaMLOGListUsrRoleCode($this->session->userdata("tSesUserCode"));

					// Create RolCode New Table
					if(!empty($aDataUsrRoleCode[0])){
						$this->session->set_userdata("tSesUsrRoleCodeNew", $aDataUsrRoleCode[0]['FTRolCode']);
					}
                                        
					// Delete Doc Temp
					$this->load->helper('document');
					FCNoHDOCDeleteDocTmp();
					// End Delete Temp Card

					// Clear Report Temp
					$this->load->helper('report');
					FCNoHDOCClearRptTmp();

					// $this->FSxCLOGCheckDeleteFileConfigdb();
					
					// Delete Temp Card
					$this->load->helper('card');
					FCNoCARDataListDeleteAllTable();
					// End Delete Temp Card
					
					redirect(); // เข้าสู่ระบบสำเร็จไปยังหน้าหลัก
			
			  }else{
			  	  redirect('login'); // ล็อกอินไม่ผ่าน กลับไปล็อกอินใหม่
			  }
		}catch(Exception $e) {
			echo "Error Code: 500 !Server error".' '.$e;
		}
	}


	// //Functionality: check file configdb ที่วันที่ น้อยกว่าวันปัจจุบัน เพื่อไม่ให้เป็นไฟล์ ขยะ
	// //Creator: 24/04/2019 Krit
	// public function FSxCLOGCheckDeleteFileConfigdb(){

	// 	$tPath = 'application/config/configDB/';
	// 	if(file_exists($tPath)){
	// 		$files = scandir($tPath);
	// 		foreach($files as $key => $filename) {
	// 			if($files[$key] > 1){
	
	// 				$dDateNow = date("d-m-Y");       
	// 				$tFilenameNew = substr($filename, 0,8);
	// 				$nDay 	= substr($tFilenameNew,0,2); //Day
	// 				$nMonth = substr($tFilenameNew,2,2); //Month
	// 				$nYear 	= substr($tFilenameNew,4,4); //Month
	// 				$tDateNewFormat = $nDay."-".$nMonth."-".$nYear; //Implode Day month year
	// 				$dDateFileSource = strtotime($tDateNewFormat); //Set format date 
	// 				$dDateFile = date('d-m-Y',$dDateFileSource); //Change format date
	
	// 				//Check Date Now > Date file for Del file old.
	// 				if($dDateNow > $dDateFile){
	// 					// echo "Del File: ".$filename."<br>";
	// 					unlink($tPath.$filename) or die("Couldn't delete file");
	// 				}
	// 			}
	// 		}
	// 	}else{
	// 		//No Folder
	// 	}
	// }
	
}








