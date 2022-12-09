<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mSaleMachine extends CI_Model {
    public function __construct(){
        parent::__construct ();
        // pap สร้างเพื่อใช้เวลาของประเทศไทย สามารถเปลี่ยนตามประเทศที่ลูกค้าอยู่
        date_default_timezone_set("Asia/Bangkok");
    }

    //Functionality : list SaleMachine
    //Parameters : function parameters
    //Creator :  30/10/2018 Witsarut
    //Return : data
    //Return Type : Array
    public function FSaMPOSList($paData){
        try{
            $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $tSearchList    = $paData['tSearchAll'];
            $tLngID         = $paData['FNLngID'];
            
            $tSQL       = "SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY rtPosCode ASC) AS rtRowID,* FROM
                                    (SELECT DISTINCT
                                        POS.FTPosCode   AS rtPosCode,
                                        POS.FTBchCode       AS rtBchCode,
                                        POS.FTPosStaRorW  AS rtPosDocType,
                                        POS.FTPosType  AS rtPosType,
                                        POS.FTPosRegNo  AS  rtPosRegNo,
                                        POS.FTPosStaPrnEJ AS  rtPosStaPrnEJ,
                                        POS.FTPosStaVatSend  AS  rtPosStaVatSend,
                                        POS.FTPosStaUse     AS  rtPosStaUse,
                                        WAHL.FTWahCode AS rtWahCode,
                                        WAHL.FTWahName AS rtWahName,
                                        BCHL.FTBchName AS rtBchName,
                                        SHPL.FTShpName AS rtShpName,
                                        POS_L.FTPosName AS rtPosName
                                    FROM [TCNMPos] POS
                                    LEFT JOIN [TCNMBranch_L] BCHL ON POS.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $tLngID
                                    LEFT JOIN TVDMPosShop PSHP ON POS.FTBchCode = PSHP.FTBchCode AND POS.FTPosCode = PSHP.FTPosCode
	                                LEFT JOIN TCNMShop_L SHPL ON PSHP.FTShpCode = SHPL.FTShpCode AND PSHP.FTBchCode = SHPL.FTBchCode AND SHPL.FNLngID = $tLngID
                                    LEFT JOIN [TCNMPosHW] POSHW ON  POS.FTPosCode = POSHW.FTPosCode AND POS.FTBchCode = POSHW.FTBchCode
                                    LEFT JOIN [TSysPosHW] TPOSHW ON POSHW.FTShwCode = TPOSHW.FTShwCode
                                    LEFT JOIN [TCNMWaHouse_L] WAHL ON POS.FTPosCode = WAHL.FTWahCode AND POS.FTBchCode = WAHL.FTBchCode AND WAHL.FNLngID = $tLngID
                                    LEFT JOIN [TCNMPos_L] POS_L  ON POS_L.FTPosCode = POS.FTPosCode AND POS.FTBchCode = POS_L.FTBchCode AND POS_L.FNLngID = $tLngID
                                    WHERE 1=1 
                            ";
            if($this->session->userdata("tSesUsrLevel") != "HQ"){
                $tBchCode = $this->session->userdata("tSesUsrBchCode");
                $tSQL .= " AND POS.FTBchCode  = '$tBchCode' ";
            }

            if(isset($tSearchList) && !empty($tSearchList)){
                $tSQL .= " AND (POS.FTPosCode LIKE '%$tSearchList%'";
                $tSQL .= " OR POS_L.FTPosName  LIKE '%$tSearchList%')";
            }

            $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
            $oQuery = $this->db->query($tSQL);

            
            if($oQuery->num_rows() > 0){
                $aList = $oQuery->result_array();
                $oFoundRow = $this->FSoMPOSGetPageAll($tSearchList);
                $nFoundRow = $oFoundRow[0]->counts;
                $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                $aResult = array(
                    'raItems'       => $aList,
                    'rnAllRow'      => $nFoundRow,
                    'rnCurrentPage' => $paData['nPage'],
                    'rnAllPage'     => $nPageAll,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                );
            }else{
                //No Data
                $aResult = array(
                    'rnAllRow' => 0,
                    'rnCurrentPage' => $paData['nPage'],
                    "rnAllPage"=> 0,
                    'rtCode' => '800',
                    'rtDesc' => 'data not found',
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : All Page Of SaleMachine
    //Parameters : function parameters
    //Creator :  20/09/2018 Witsarut (Bell)
    //Return : object Count All SaleMachine
    //Return Type : Object
    public function FSoMPOSGetPageAll($ptSearchList){
 
        try{
            $tSQL = "SELECT COUNT (POS.FTPosCode) AS counts
                     FROM [TCNMPos] POS
                     LEFT JOIN TVDMPosShop PSHP ON POS.FTBchCode = PSHP.FTBchCode AND POS.FTPosCode = PSHP.FTPosCode
	                 LEFT JOIN TCNMShop_L SHPL ON PSHP.FTShpCode = SHPL.FTShpCode AND PSHP.FTBchCode = SHPL.FTBchCode
                     LEFT JOIN [TCNMPos_L] POS_L  ON POS_L.FTPosCode = POS.FTPosCode AND POS.FTBchCode = POS_L.FTBchCode 
                     WHERE 1=1 
                    ";

            if($this->session->userdata("tSesUsrLevel") != "HQ"){
                $tBchCode = $this->session->userdata("tSesUsrBchCode");
                $tSQL .= " AND POS.FTBchCode  = '$tBchCode' ";
            }
                
            if(isset($ptSearchList) && !empty($ptSearchList)){
                $tSQL .= " AND (POS.FTPosCode LIKE '%$ptSearchList%'";
                $tSQL .= " OR POS_L.FTPosName  LIKE '%$ptSearchList%')";
            }
            $oQuery = $this->db->query($tSQL);


            if ($oQuery->num_rows() > 0) {
                return $oQuery->result();
            }else{
                return false;
            }
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Get Data SaleMachine By ID
    //Parameters : function parameters
    //Creator : 30/10/2018 Witsarut
    //Return : data
    //Return Type : Array
    public function FSaMPOSGetDataByID($paData){
        try{
            $tPosCode   = $paData['FTPosCode'];
            $tBchCode   = $paData['FTBchCode'];
            $tLang =$this->session->userdata("tLangEdit");
            $tSQL       = " SELECT 
                                POS.FTBchCode    AS rtBchCode,
                                BCH_L.FTBchName  AS rtBchName,
                                POS.FTPosCode    AS rtPosCode,
                                POS.FTPosStaRorW  AS rtPosDocType,
                                POS.FTPosType  AS rtPosType,
                                POS.FTPosRegNo  AS  rtPosRegNo,
                                POS.FTPosStaPrnEJ AS  rtPosStaPrnEJ,
                                POS.FTPosStaVatSend  AS  rtPosStaVatSend,
                                POS.FTPosStaUse  AS  rtPosStaUse,
                                POSWH.FTWahCode AS rtWahCode,
                                POSWH_L.FTWahName AS rtWahName,
                                POS_L.FTPosName AS rtPosName,
                                POS.FTSmgCode AS rtSmgCode,
                                SMP_L.FTSmgTitle AS rtSmgTitle
                            FROM [TCNMPos] POS
                            LEFT JOIN [TCNMPosHW] POSHW ON  POS.FTPosCode = POSHW.FTPosCode AND POS.FTBchCode = POSHW.FTBchCode
                            LEFT JOIN [TCNMWaHouse] POSWH ON  POS.FTPosCode = POSWH.FTWahRefCode
                            LEFT JOIN [TCNMWaHouse_L] POSWH_L ON  POSWH.FTWahCode = POSWH_L.FTWahCode AND POS.FTBchCode = POSWH_L.FTBchCode
                            LEFT JOIN [TCNMBranch_L] BCH_L ON  POS.FTBchCode = BCH_L.FTBchCode
                            LEFT JOIN [TCNMPos_L] POS_L  ON POS_L.FTPosCode = POS.FTPosCode AND POS.FTBchCode = POS_L.FTBchCode
                            LEFT JOIN TCNMSlipMsgHD_L SMP_L ON POS.FTSmgCode = SMP_L.FTSmgCode AND SMP_L.FNLngID = '$tLang'
                            WHERE 1=1 
                            AND POS.FTPosCode = '$tPosCode' 
                            AND POS.FTBchCode = '$tBchCode'
                          ";
            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0){
                $aDetail = $oQuery->row_array();
                $aResult = array(
                    'raItems'   => $aDetail,
                    'rtCode'    => '1',
                    'rtDesc'    => 'success',
                );
            }else{
                $aResult = array(
                    'rtCode' => '800',
                    'rtDesc' => 'Data not found.',
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Checkduplicate SaleMachine
    //Parameters : function parameters
    //Creator : 30/10/2018 Witsarut
    //Return : data
    //Return Type : Array
    public function FSnMPOSCheckDuplicate($ptPosCode){
        $tSQL = "SELECT COUNT(POS.FTPosCode) AS counts
                 FROM TCNMPos POS 
                 WHERE POS.FTPosCode = '$ptPosCode'
                ";

        if($this->session->userdata("tSesUsrLevel") != "HQ"){
            $tBchCode = $this->session->userdata("tSesUsrBchCode");
            $tSQL .= " AND POS.FTBchCode  = '$tBchCode' ";
        }

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->row_array();
        }else{
            return FALSE;
        }
    }

    //Functionality : Update Product SaleMachine (TCNMPos)
    //Parameters : function parameters
    //Creator : 19/09/2018 Witsarut(Bell)
    //Return : Array Stutus Add Update
    //Return Type : Array
    public function FSaMPOSAddUpdateMaster($paDataSaleMachine){
        try{
            // Update TCNMPos
            $this->db->where('FTPosCode', $paDataSaleMachine['FTPosCode']);
            $this->db->update('TCNMPos',array(
                'FTPosType'         => $paDataSaleMachine['FTPosType'],
                'FTPosRegNo'        => $paDataSaleMachine['FTPosRegNo'],
                'FTPosStaPrnEJ'     => $paDataSaleMachine['FTPosStaPrnEJ'],
                'FTPosStaVatSend'   => $paDataSaleMachine['FTPosStaVatSend'],
                'FTPosStaUse'       => $paDataSaleMachine['FTPosStaUse'],
                'FDLastUpdOn'       => $paDataSaleMachine['FDLastUpdOn'], 
                'FTLastUpdBy'       => $paDataSaleMachine['FTLastUpdBy']
            ));
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update SaleMachine Success',
                );
            }else{
                //Add TCNMPos
                $this->db->insert('TCNMPos', array(
                    'FTPosCode'         => $paDataSaleMachine['FTPosCode'],
                    'FTPosType'         => $paDataSaleMachine['FTPosType'],
                    'FTPosRegNo'        => $paDataSaleMachine['FTPosRegNo'],
                    'FTPosStaPrnEJ'     => $paDataSaleMachine['FTPosStaPrnEJ'],
                    'FTPosStaVatSend'   => $paDataSaleMachine['FTPosStaVatSend'],
                    'FTPosStaUse'       => $paDataSaleMachine['FTPosStaUse'],
                    'FDCreateOn'        => $paDataSaleMachine['FDCreateOn'],
                    'FDLastUpdOn'       => $paDataSaleMachine['FDLastUpdOn'],
                    'FTCreateBy'        => $paDataSaleMachine['FTCreateBy'],
                    'FTLastUpdBy'       => $paDataSaleMachine['FTLastUpdBy'],
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add SaleMachine Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit SaleMachine.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    }

  
    //Functionality : แก้ไขข้อมูลเครื่องจุดขาย
    //Parameters : ข้อมูลเครื่องจุดขาย
    //Creator : 28/03/2019 pap
    //Update : -
    //Return : -
    //Return Type : -
    public function FSxMPOSUpdatePos($paDataPos){
        $tSQL = "UPDATE TCNMPos SET 
                 FTPosType     = '".$paDataPos["ocmPosType"]."',
                 FTBchCode     = '".$paDataPos["FTBchCode"]."',
                 FTPosRegNo    = '".$paDataPos["oetPosRegNo"]."',
                 FTSmgCode     = '".$paDataPos["oetSmgCode"]."',
                 FTPosStaShift = '".$paDataPos["FTPosStaShift"]."',
                 ";

        if($paDataPos["ocbPOSStaPrnEJ"]!=null){
            $tSQL .= " FTPosStaPrnEJ = '".$paDataPos["ocbPOSStaPrnEJ"]."',";
        }else{
            $tSQL .= " FTPosStaPrnEJ = '2',";
        }
        if($paDataPos["ocbPosStaVatSend"]!=null){
            $tSQL .= " FTPosStaVatSend = '".$paDataPos["ocbPosStaVatSend"]."',";
        }else{
            $tSQL .= " FTPosStaVatSend = '2',";
        }
        if($paDataPos["ocbPosStaUse"]!=null){
            $tSQL .= " FTPosStaUse = '".$paDataPos["ocbPosStaUse"]."',";
        }else{
            $tSQL .= " FTPosStaUse = '2',";
        }
        $tSQL .= " FDLastUpdOn = '".date("Y-m-d H:i:s")."',
                   FTLastUpdBy = '".$this->session->userdata("tSesUsername")."'
                   WHERE 1=1
                   AND FTPosCode = '".$paDataPos["oetPosCode"]."'
                   AND FTBchCode = '".$paDataPos["FTBchOldCode"]."'
                 ";         
        $this->db->query($tSQL);
    }

    //Functionality : แก้ไขข้อมูลเครื่องจุดขาย ในตาราง TCNMWaHouse
    //Parameters : ข้อมูลเครื่องจุดขาย
    //Creator : 28/03/2019 pap
    //Update : 04/10/2019 Saharat(Golf)
    //Return : -
    //Return Type : -
    public function FSxMPOSUpdatePosWaHouse($paDataPos){

        if($paDataPos["ocmPosType"] == 4 && ($paDataPos["oetBchWahCode"] != '' || $paDataPos["oetBchWahCode"] == null )){
                $tSQL = "   UPDATE TCNMWaHouse WITH(ROWLOCK) 
                            SET FTWahRefCode    = null
                            WHERE FTWahCode     = '".$paDataPos["oetBchWahCodeOld"]."'
                            AND FTBchCode       = '".$paDataPos["FTBchCode"]."'
                        ";
                $oQuery = $this->db->query($tSQL);

        if($oQuery){
                $tSQL = "   UPDATE TCNMWaHouse WITH(ROWLOCK)
                            SET FTWahRefCode    = '".$paDataPos["oetPosCode"]."'
                            WHERE FTWahCode     = '".$paDataPos["oetBchWahCode"]."' 
                            AND FTBchCode       = '".$paDataPos["FTBchCode"]."'
                        ";
                $this->db->query($tSQL); 
            }
        }else{
                $tSQL = "   UPDATE TCNMWaHouse WITH(ROWLOCK)
                            SET FTWahRefCode    = null
                            WHERE FTWahCode     = '".$paDataPos["oetBchWahCodeOld"]."'
                            AND FTBchCode       = '".$paDataPos["FTBchCode"]."'
                        ";
                $oQuery = $this->db->query($tSQL);
        }
        
    }

    //Functionality : แก้ไขข้อมูลที่อยู่เครื่องจุดขาย ในตาราง TCNMAddress_L
    //Parameters : ข้อมูลเครื่องจุดขาย
    //Creator : 28/03/2019 pap
    //Update : -
    //Return : -
    //Return Type : -
    public function FSxMPOSUpdatePosAddress($paDataPos){
        $tSQL = "SELECT * FROM TCNMAddress_L
                 WHERE FNLngID = '".$this->session->userdata("tLangID")."'
                 AND FTAddGrpType = '6'
                 AND FTAddRefCode = '".$paDataPos["oetPosCode"]."'";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            if($paDataPos["nAddVersion"]==1){
                $tSQL = "UPDATE TCNMAddress_L SET
                         FTAddCountry = '".$paDataPos["oetPosCountry"]."',
                         FTAddVersion = '".$paDataPos["nAddVersion"]."',
                         FTAddV1No = '".$paDataPos["oetAddV1No"]."',
                         FTAddV1Soi = '".$paDataPos["oetAddV1Soi"]."',
                         FTAddV1Village = '".$paDataPos["oetAddV1Village"]."',
                         FTAddV1Road = '".$paDataPos["oetAddV1Road"]."',
                         FTAddV1SubDist = '".$paDataPos["oetAddV1SubDistCode"]."',
                         FTAddV1DstCode = '".$paDataPos["oetAddV1DstCode"]."',
                         FTAddV1PvnCode = '".$paDataPos["oetAddV1PvnCode"]."',
                         FTAddV1PostCode = '".$paDataPos["oetAddV1PostCode"]."',
                         FDLastUpdOn = '".date("Y-m-d H:i:s")."',
                         FTLastUpdBy = '".$this->session->userdata("tSesUsername")."'
                         WHERE FNLngID = '".$this->session->userdata("tLangID")."' 
                               AND FTAddGrpType = 6
                               AND FTAddRefCode = '".$paDataPos["oetPosCode"]."'";
                $this->db->query($tSQL);
            }else{
                $tSQL = "UPDATE TCNMAddress_L SET
                         FTAddV2Desc1 = '".$paDataPos["oetAddV2Desc1"]."',
                         FTAddV2Desc2 = '".$paDataPos["oetAddV2Desc2"]."',
                         FDLastUpdOn = '".date("Y-m-d H:i:s")."',
                         FTLastUpdBy = '".$this->session->userdata("tSesUsername")."'
                         WHERE FNLngID = '".$this->session->userdata("tLangID")."' 
                               AND FTAddGrpType = 6
                               AND FTAddRefCode = '".$paDataPos["oetPosCode"]."'";
                $this->db->query($tSQL);               
            }
        }else{
            $this->FSxMPOSInsertPosAddress($paDataPos);
        }
        $this->db->query($tSQL);
    }

    //Functionality : เพิ่มข้อมูลเครื่องจุดขายใหม่
    //Parameters : ข้อมูลเครื่องจุดขาย
    //Creator : 28/03/2019 pap
    //Update : -
    //Return : -
    //Return Type : -
    public function FSxMPOSInsertPos($paDataPos){

        $tSQL = "INSERT INTO TCNMPos(FTBchCode,FTPosCode,FTPosType,FTPosRegNo,FTSmgCode,
                                     FTPosStaPrnEJ,FTPosStaVatSend,FTPosStaUse,FTPosStaShift,
                                     FDLastUpdOn,FDCreateOn,FTLastUpdBy,FTCreateBy)
                 VALUES(
                    '".$paDataPos["FTBchCode"]."',
                    '".$paDataPos["oetPosCode"]."',
                    '".$paDataPos["ocmPosType"]."',
                    '".$paDataPos["oetPosRegNo"]."',
                    '".$paDataPos["oetSmgCode"]."',
                ";
        if($paDataPos["ocbPOSStaPrnEJ"]!=null){
            $tSQL .= "'".$paDataPos["ocbPOSStaPrnEJ"]."',";
        }else{
            $tSQL .= "'2',";
        }
        if($paDataPos["ocbPosStaVatSend"]!=null){
            $tSQL .= "'".$paDataPos["ocbPosStaVatSend"]."',";
        }else{
            $tSQL .= "'2',";
        }
        if($paDataPos["ocbPosStaUse"]!=null){
            $tSQL .= "'".$paDataPos["ocbPosStaUse"]."',";
        }else{
            $tSQL .= "'2',";
        }
        $tSQL .= " '".$paDataPos["FTPosStaShift"]."',
                  '".date("Y-m-d H:i:s")."',
                  '".date("Y-m-d H:i:s")."',
                  '".$this->session->userdata("tSesUsername")."',
                  '".$this->session->userdata("tSesUsername")."')";
        $this->db->query($tSQL);


        //เพิ่มชื่อเครื่องจุดขาย 
        $tSQLPos = "INSERT INTO TCNMPos_L(FTPosCode,FNLngID,FTPosName,FTBchCode)
                    VALUES(
                    '".$paDataPos["oetPosCode"]."',
                    '".$this->session->userdata("tLangID")."',
                    '".$paDataPos["oetPosName"]."',
                    '".$paDataPos["FTBchCode"]."')";
        $this->db->query($tSQLPos);
    }

    //Functionality : เซ็ตค่าว่าคลังข้อมูลนี้ เครื่องจุดขายไหนใช้อยู่
    //Parameters : ข้อมูลเครื่องจุดขาย
    //Creator : 28/03/2019 pap
    //Update : -
    //Return : -
    //Return Type : -
    public function FSxMPOSInsertPosWaHouse($paDataPos){
        
        if($paDataPos["ocmPosType"]==4 && ($paDataPos["oetBchWahCode"] != '' || $paDataPos["oetBchWahCode"] == null )){
            $tSQL = "   UPDATE TCNMWaHouse WITH(ROWLOCK)
                        SET FTWahRefCode    = '".$paDataPos["oetPosCode"]."'
                        WHERE FTWahCode     = '".$paDataPos["oetBchWahCode"]."'
                        AND FTBchCode       = '".$paDataPos["FTBchCode"]."'
                    ";
            $this->db->query($tSQL);
        }
        
    }

    //Functionality : เพิ่มข้อมูลเครื่องจุดขายใหม่ ในส่วนของที่อยู่
    //Parameters : ข้อมูลที่อยู่
    //Creator : 28/03/2019 pap
    //Update : -
    //Return : -
    //Return Type : -
    public function FSxMPOSInsertPosAddress($paDataPos){
        $tSQL = "SELECT COUNT(FNAddSeqNo) AS FTMaxAddRefNo FROM TCNMAddress_L 
                 WHERE FNLngID = '".$this->session->userdata("tLangID")."'
                 AND FTAddGrpType = '6'
                 AND FTAddRefCode = '".$paDataPos["oetPosCode"]."'";
        $oQuery = $this->db->query($tSQL);
        $nMaxAddRefNo = 1;
        if($oQuery->num_rows() > 0){
            $nMaxAddRefNo = $oQuery->row_array()["FTMaxAddRefNo"];
        }
        if($paDataPos["nAddVersion"]==1){
            $tSQL = "INSERT INTO TCNMAddress_L(
                                               FNLngID,FTAddGrpType,FTAddRefCode,FTAddRefNo,
                                               FTAddCountry,FTAddVersion,FTAddV1No,FTAddV1Soi,
                                               FTAddV1Village,FTAddV1Road,FTAddV1SubDist,
                                               FTAddV1DstCode,FTAddV1PvnCode,FTAddV1PostCode,
                                               FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy
                    )
                     VALUES(
                                '".$this->session->userdata("tLangID")."',
                                '6',
                                '".$paDataPos["oetPosCode"]."',
                                '".$nMaxAddRefNo."',
                                '".$paDataPos["oetPosCountry"]."',
                                '".$paDataPos["nAddVersion"]."',
                                '".$paDataPos["oetAddV1No"]."',
                                '".$paDataPos["oetAddV1Soi"]."',
                                '".$paDataPos["oetAddV1Village"]."',
                                '".$paDataPos["oetAddV1Road"]."',
                                '".$paDataPos["oetAddV1SubDistCode"]."',
                                '".$paDataPos["oetAddV1DstCode"]."',
                                '".$paDataPos["oetAddV1PvnCode"]."',
                                '".$paDataPos["oetAddV1PostCode"]."',
                                '".date("Y-m-d H:i:s")."',
                                '".$this->session->userdata("tSesUsername")."',
                                '".date("Y-m-d H:i:s")."',
                                '".$this->session->userdata("tSesUsername")."'
                    )";
        }else{
            $tSQL = "INSERT INTO TCNMAddress_L(
                                               FNLngID,FTAddGrpType,FTAddRefCode,FTAddRefNo,
                                               FTAddV2Desc1,FTAddV2Desc2,FDLastUpdOn,FTLastUpdBy,
                                               FDCreateOn,FTCreateBy
                    )
                    VALUES(
                                '".$this->session->userdata("tLangID")."',
                                '6',
                                '".$paDataPos["oetPosCode"]."',
                                '".$nMaxAddRefNo."',
                                '".$paDataPos["oetAddV2Desc1"]."',
                                '".$paDataPos["oetAddV2Desc2"]."',
                                '".date("Y-m-d H:i:s")."',
                                '".$this->session->userdata("tSesUsername")."',
                                '".date("Y-m-d H:i:s")."',
                                '".$this->session->userdata("tSesUsername")."'
                    }";
        }
        
        $this->db->query($tSQL);
    }

    //Functionality : Update SaleMachine (TCNMPosLastNo)
    //Parameters : function parameters
    //Creator : 30/10/2018 Witsarut
    //Return : Array Stutus Add Update
    //Return Type : array
    public function FSaMPOSAddUpdateLang($paDataSaleMachine){

        try{
            $this->db->where('FTPosCode', $paDataSaleMachine['oetPosCode']);
            $this->db->where('FTBchCode', $paDataSaleMachine['FTBchOldCode']);
            $this->db->set('FTPosName',$paDataSaleMachine['FTPosName']);
            $this->db->set('FTBchCode',$paDataSaleMachine['FTBchCode']);
            $this->db->update('TCNMPos_L');

            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update SaleMachine Lang Success.',
                );
            }else{
                $this->db->insert('TCNMPos_L', array(
                    'FTPosCode'     => $paDataSaleMachine['oetPosCode'],
                    'FNLngID'       => $this->session->userdata("tLangEdit"),
                    'FTBchCode'     => $paDataSaleMachine['FTBchCode'],
                    'FTPosName'     => $paDataSaleMachine['FTPosName'],
                ));

                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add SaleMachine Lang Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit SaleMachine Lang.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Delete SaleMachine
    //Parameters : function parameters
    //Creator : 30/10/2018 Witsarut
    //Update : 28/03/2019 pap
    //Return : Status Delete
    //Return Type : array
    public function FSaMPOSDelAll($paData){
        try{
            $this->db->trans_begin();

            $this->db->where_in('FTBchCode', $paData['FTBchCode']);
            $this->db->where_in('FTPosCode', $paData['FTPosCode']);
            $this->db->delete('TCNMPos');

            $this->db->where_in('FTBchCode', $paData['FTBchCode']);
            $this->db->where_in('FTPosCode', $paData['FTPosCode']);
            $this->db->delete('TCNMPos_L');

            $this->db->where('FTAddGrpType', '6');
            $this->db->where_in('FTAddRefCode', $paData['FTPosCode']);
            $this->db->delete('TCNMAddress_L');

            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Delete Unsuccess.',
                );
            }else{
                $this->db->trans_commit();
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Delete Success.',
                );
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : get all row data from pos
    //Parameters : -
    //Creator : 1/04/2019 Pap
    //Return : array result from db
    //Return Type : array
    public function FSnMLOCGetAllNumRow(){
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TCNMPos";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }



    //  Functionality : Get datalist SLipMessage
    //  Creator : 09/09/2019 Witsarut(Bell)
    //  Last Modified : -
    //  Return : data
    //  Return Type : array
    public function FSaMSMGGetDataList($tPosCode){
        try{
            $tSQL = "SELECT 
                       TCNMSlipMsgHD_L.FTSmgCode   AS rtSmgCode,
                       TCNMSlipMsgHD_L.FTSmgTitle  AS rtSmgTitle
                     FROM TCNMPos
                     LEFT JOIN TCNMSlipMsgHD_L ON TCNMPos.FTSmgCode = TCNMSlipMsgHD_L.FTSmgCode AND TCNMSlipMsgHD_L.FNLngID = '".$this->session->userdata("tLangEdit")."'
                     WHERE TCNMPos.FTPosCode = '".$tPosCode."'";
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aList = $oQuery->row_array();
                $aResult = array(
                    'raItems'       => $aList,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                );
            }else{
                //No Data
                $aResult = array(
                    'rtCode' => '800',
                    'rtDesc' => 'data not found',
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }


    //  Functionality : Get datalist BchCode ShpCode   TVDMPosShop
    //  Creator : 12/09/2019 Saharat(Golf)
    //  Last Modified : -
    //  Return : data
    //  Return Type : array
    public function FSaMSMGGetVDPosShopDataList($tPosCode){
        try{
            $tSQL = "SELECT 
                        TPS.FTBchCode AS rtBchCode,
                        TPS.FTShpCode AS rtShpCode,
                        TPS.FTPosCode AS rtPosCode
                     FROM TVDMPosShop TPS
                     WHERE 1=1 
                     AND TPS.FTPosCode = '".$tPosCode."' 
                     ";
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aList = $oQuery->row_array();
                $aResult = array(
                    'raItems'       => $aList,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                );
            }else{
                //No Data
                $aResult = array(
                    'rtCode' => '800',
                    'rtDesc' => 'data not found',
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //  Functionality : Get datalist BchCode ShpCode   TRTMShopPos
    //  Creator : 12/09/2019 Saharat(Golf)
    //  Last Modified : -
    //  Return : data
    //  Return Type : array
    public function FSaMSMGGetSMPosShopDataList($tPosCode){
        try{
            $tSQL = "SELECT 
                        TPS.FTBchCode AS rtBchCode,
                        TPS.FTShpCode AS rtShpCode,
                        TPS.FTPosCode AS rtPosCode
                     FROM TRTMShopPos TPS
                     WHERE 1=1 
                     AND TPS.FTPosCode = '".$tPosCode."'
                    ";
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aList = $oQuery->row_array();
                $aResult = array(
                    'raItems'       => $aList,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                );
            }else{
                //No Data
                $aResult = array(
                    'rtCode' => '800',
                    'rtDesc' => 'data not found',
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }



}