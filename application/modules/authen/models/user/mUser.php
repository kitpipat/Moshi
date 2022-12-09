<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mUser extends CI_Model {

    //Functionality : Search User By ID
    //Parameters : function parameters
    //Creator : 01/06/2018 Wasin
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMUSRSearchByID ($ptAPIReq,$ptMethodReq,$paData){
        $tUsrCode   = $paData['FTUsrCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL       = " SELECT
                            IMGP.FTImgObj       AS rtUsrImage,
                            USR.FTUsrCode       AS rtUsrCode,
                            USR.FTUsrTel        AS rtUsrTel,
                            USR.FTUsrEmail      AS rtUsrEmail,
                            USR.FTUsrPwd        AS rtUsrPwd,
                            USRL.FTUsrName      AS rtUsrName,
                            USRL.FTUsrRmk       AS rtUsrRmk,
                            UDPT.FTDptCode      AS rtDptCode,
                            UDPT.FTDptName      AS rtDptName,
                            BCHL.FTBchCode      AS rtBchCode,
                            BCHL.FTBchName      AS rtBchName,
                            SHPL.FTShpCode      AS rtShpCode,
                            SHPL.FTShpName      AS rtShpName,
                            USRG.FDUsrStart     AS rtUsrStartDate,
                            USRG.FDUsrStop      AS rtUsrEndDate
                        FROM [TCNMUser] USR WITH(NOLOCK)
                        LEFT JOIN [TCNMUser_L] USRL WITH(NOLOCK) ON USR.FTUsrCode = USRL.FTUsrCode AND USRL.FNLngID = $nLngID
                        LEFT JOIN [TCNMUsrDepart_L] UDPT WITH(NOLOCK) ON USR.FTDptCode = UDPT.FTDptCode AND UDPT.FNLngID = $nLngID
                        LEFT JOIN [TCNMUsrRole_L] UROL WITH(NOLOCK) ON USR.FTRolCode = UROL.FTRolCode AND UROL.FNLngID = $nLngID
                        LEFT JOIN [TCNTUsrGroup] USRG WITH(NOLOCK) ON USR.FTUsrCode = USRG.FTUsrCode
                        LEFT JOIN [TCNMBranch_L] BCHL WITH(NOLOCK) ON USRG.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                        LEFT JOIN [TCNMShop_L] SHPL WITH(NOLOCK) ON USRG.FTShpCode = SHPL.FTShpCode AND USRG.FTBchCode = SHPL.FTBchCode AND SHPL.FNLngID = $nLngID
                        LEFT JOIN [TCNMImgPerson] IMGP WITH(NOLOCK) ON USR.FTUsrCode = IMGP.FTImgRefID AND IMGP.FTImgTable = 'TCNMUser' AND IMGP.FNImgSeq = 1
                        WHERE 1=1  AND USR.FTUsrCode = '$tUsrCode'
        ";
    
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            //Not Found
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }


    //Functionality : Search ActRoleCode Join เอา USerCode
    //Parameters : function parameters
    //Creator : 01/06/2018 Witsarut
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMActRoleByID ($ptAPIReq,$ptMethodReq,$paData){
        $tUsrCode   = $paData['FTUsrCode'];
        $nLngID     = $paData['FNLngID'];

        $tSQL      = " SELECT 
                            USRACT.FTRolCode,
                            UROL.FTRolName, 
                            USRACT.FTUsrCode
                      FROM [TCNMUsrActRole] USRACT WITH(NOLOCK)
                      LEFT JOIN [TCNMUser] USR WITH(NOLOCK) ON USR.FTUsrCode = USRACT.FTUsrCode
                      LEFT JOIN [TCNMUsrRole_L] UROL WITH(NOLOCK) ON UROL.FTRolCode = USRACT.FTRolCode AND UROL.FNLngID = $nLngID
                      WHERE 1=1
             ";
            if($tUsrCode!= ""){
                $tSQL .= "AND USR.FTUsrCode = '$tUsrCode'";
            }
            $oQuery = $this->db->query($tSQL);

            if ($oQuery->num_rows() > 0){
                $oDetail = $oQuery->result_array();
                $aResult = array(
                    'raItems'   => $oDetail,
                    'rtCode'    => '1',
                    'rtDesc'    => 'success',
                );
            }else{
                //Not Found
                $aResult = array(
                    'rtCode' => '800',
                    'rtDesc' => 'data not found.',
                );
            }
            $jResult = json_encode($aResult);
            $aResult = json_decode($jResult, true);
            return $aResult;


    }


    //Functionality : list User
    //Parameters : function parameters
    //Creator :  01/06/2018 Wasin
    //Last Modified : 12/03/2020 Saharat(Golf)
    //Return : data User List
    //Return Type : Array
    public function FSaMUSRList($ptAPIReq,$ptMethodReq,$paData){

        $tWhereBch = "";
        $tWhereShp  = "";

        if(!empty($paData['tUsrBchCode'])){
            $tUsrBchCode = $paData['tUsrBchCode'];
            $tWhereBch =  " AND USRG.FTBchCode = '$tUsrBchCode' ";
        }

        if(!empty($paData['tUsrShpCode'])){
            $tUsrShpCode = $paData['tUsrShpCode'];
            $tWhereShp =  " AND USRG.FTShpCode = '$tUsrShpCode' ";
        }

        $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $nLngID         = $paData['FNLngID'];
        $tSQL           = " SELECT c.* FROM (
                                SELECT  ROW_NUMBER() OVER(ORDER BY rtUsrCode ASC) AS rtRowID,* FROM (
                                    SELECT DISTINCT
                                        IMGP.FTImgObj       AS rtUsrImage,
                                        USR.FTUsrCode       AS rtUsrCode,
                                        USRL.FTUsrName      AS rtUsrName,
                                        UDPT.FTDptCode      AS rtDptCode,
                                        UDPT.FTDptName      AS rtDptName,
                                        UROL.FTRolCode      AS rtRolCode,
                                        UROL.FTRolName      AS rtRolName,
                                        BCHL.FTBchCode      AS rtBchCode,
                                        BCHL.FTBchName      AS rtBchName,
                                        SHPL.FTShpCode      AS rtShpCode,
                                        SHPL.FTShpName      AS rtShpName
                                    FROM [TCNMUser]             USR     WITH(NOLOCK)
                                    LEFT JOIN [TCNMUser_L]      USRL    WITH(NOLOCK) ON USR.FTUsrCode   = USRL.FTUsrCode    AND USRL.FNLngID = $nLngID
                                    LEFT JOIN [TCNMUsrDepart_L] UDPT    WITH(NOLOCK) ON USR.FTDptCode   = UDPT.FTDptCode    AND UDPT.FNLngID = $nLngID
                                    LEFT JOIN [TCNMUsrRole_L]   UROL    WITH(NOLOCK) ON USR.FTRolCode   = UROL.FTRolCode    AND UROL.FNLngID = $nLngID
                                    LEFT JOIN [TCNTUsrGroup]    USRG    WITH(NOLOCK) ON USR.FTUsrCode   = USRG.FTUsrCode    
                                    LEFT JOIN [TCNMBranch_L]    BCHL    WITH(NOLOCK) ON USRG.FTBchCode  = BCHL.FTBchCode    AND BCHL.FNLngID = $nLngID
                                    LEFT JOIN [TCNMShop_L]      SHPL    WITH(NOLOCK) ON USRG.FTShpCode  = SHPL.FTShpCode    AND USRG.FTBchCode = SHPL.FTBchCode AND SHPL.FNLngID = $nLngID
                                    LEFT JOIN [TCNMImgPerson]   IMGP    WITH(NOLOCK) ON USR.FTUsrCode   = IMGP.FTImgRefID   AND IMGP.FTImgTable = 'TCNMUser' AND IMGP.FNImgSeq   = 1
                                    WHERE 1=1
                                    $tWhereBch
                                    $tWhereShp
        ";
        $tSearchList    = $paData['tSearchAll'];
        if ($tSearchList != ''){
            $tSQL .= " AND (USR.FTUsrCode COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQL .= " OR USRL.FTUsrName  COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQL .= " OR BCHL.FTBchName  COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQL .= " OR SHPL.FTShpName  COLLATE THAI_BIN LIKE '%$tSearchList%')";
        }
        $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $oList      = $oQuery->result();
            $aFoundRow  = $this->FSnMUSRGetPageAll($tSearchList,$nLngID,$paData);
            $nFoundRow  = $aFoundRow[0]->counts;
            $nPageAll   = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => $nPageAll, 
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );

        }else{
            //No Data
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : All Page Of User
    //Parameters : function parameters
    //Creator :  04/06/2018 Wasin
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMUSRGetPageAll($ptSearchList,$ptLngID,$paData){
        $tWhereBch = "";
        $tWhereShp  = "";

        if(!empty($paData['tUsrBchCode'])){
            $tUsrBchCode = $paData['tUsrBchCode'];
            $tWhereBch =  " AND USRG.FTBchCode = '$tUsrBchCode' ";
        }

        if(!empty($paData['tUsrShpCode'])){
            $tUsrShpCode = $paData['tUsrShpCode'];
            $tWhereShp =  " AND USRG.FTShpCode = '$tUsrShpCode' ";
        }
        $tSQL   =   "   SELECT 
                            COUNT (USR.FTUsrCode) AS counts
                        FROM [TCNMUser]             USR     WITH(NOLOCK)
                        LEFT JOIN [TCNMUser_L]      USRL    WITH(NOLOCK) ON USR.FTUsrCode   = USRL.FTUsrCode AND USRL.FNLngID = $ptLngID
                        LEFT JOIN [TCNMUsrDepart_L] UDPT    WITH(NOLOCK) ON USR.FTDptCode   = UDPT.FTDptCode AND UDPT.FNLngID = $ptLngID
                        LEFT JOIN [TCNMUsrRole_L]   UROL    WITH(NOLOCK) ON USR.FTRolCode   = UROL.FTRolCode AND UROL.FNLngID = $ptLngID
                        LEFT JOIN [TCNTUsrGroup]    USRG    WITH(NOLOCK) ON USR.FTUsrCode   = USRG.FTUsrCode
                        LEFT JOIN [TCNMBranch_L]    BCHL    WITH(NOLOCK) ON USRG.FTBchCode  = BCHL.FTBchCode AND BCHL.FNLngID = $ptLngID
                        LEFT JOIN [TCNMShop_L]      SHPL    WITH(NOLOCK) ON USRG.FTShpCode  = SHPL.FTShpCode AND USRG.FTBchCode = SHPL.FTBchCode AND SHPL.FNLngID = $ptLngID
                        WHERE 1=1
                        $tWhereBch
                        $tWhereShp
        ";
        if($ptSearchList != ''){
            $tSQL .= " AND (USR.FTUsrCode COLLATE THAI_BIN LIKE '%$ptSearchList%'";
            $tSQL .= " OR USRL.FTUsrName  COLLATE THAI_BIN LIKE '%$ptSearchList%'";
            $tSQL .= " OR BCHL.FTBchName  COLLATE THAI_BIN LIKE '%$ptSearchList%'";
            $tSQL .= " OR SHPL.FTShpName  COLLATE THAI_BIN LIKE '%$ptSearchList%')";
        }
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            //No Data
            return false;
        }
    }

    //Functionality : Checkduplicate Data 
    //Parameters : function parameters
    //Creator : 07/06/2018 Wasin
    //Last Modified : -
    //Return : data Count Duplicate
    //Return Type : object
    public function FSoMUSRCheckDuplicate($ptUsrCode){
        $tSQL   = "SELECT COUNT(FTUsrCode)AS counts
                   FROM TCNMUser
                   WHERE FTUsrCode = '$ptUsrCode' ";
        
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //Functionality : Function Add Update Master
    //Parameters : function parameters
    //Creator : 11/06/2018 Wasin
    //Last Modified : -
    //Return : Status Add Update Master
    //Return Type : Array
    public function FSaMUSRAddUpdateMaster($paData){
        try{
            //Update Master
            $this->db->set('FTDptCode' , $paData['FTDptCode']);
            // $this->db->set('FTRolCode' , $paData['FTRolCode']);
            $this->db->set('FTUsrTel' , $paData['FTUsrTel']);
            $this->db->set('FTUsrPwd' , $paData['FTUsrPwd']);
            $this->db->set('FTUsrEmail' , $paData['FTUsrEmail']);

            $this->db->set('FDLastUpdOn' , $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy' , $paData['FTLastUpdBy']);

            $this->db->where('FTUsrCode',$paData['FTUsrCode']);
            $this->db->update('TCNMUser');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Success',
                );
            }else{
                //Add Master
                $this->db->insert('TCNMUser',array(
                    'FTUsrCode'     => $paData['FTUsrCode'],
                    'FTDptCode'     => $paData['FTDptCode'],
                    'FTRolCode'     => $paData['FTRolCode'],
                    'FTUsrTel'      => $paData['FTUsrTel'],
                    'FTUsrPwd'      => $paData['FTUsrPwd'],
                    'FTUsrEmail'    => $paData['FTUsrEmail'],
                    'FDCreateOn'    => $paData['FDCreateOn'],
                    'FTCreateBy'    => $paData['FTCreateBy'],
                    'FDLastUpdOn'   => $paData['FDLastUpdOn'],
                    'FTLastUpdBy'   => $paData['FTLastUpdBy'],
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Success',
                    );
                }else{
                    //Error 
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            $jStatus = json_encode($aStatus);
            $aStatus = json_decode($jStatus, true);
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }


    // Delete USerActRole ก่อน แล้วจึง loop
    // Create By Witsarut 24/02/2020
    public function FSaMDelActRoleCode($paData){
        try{
            $this->db->where_in('FTUsrCode',$paData['FTUsrCode']);
            $this->db->delete('TCNMUsrActRole');
        }catch(Exception $Error){
            echo $Error;
        }
    }


    //Update table TCNMUsrActRole  (Bell)
    // Create By Witsarut 24/02/2020
    public function FSaMUpdateMasterActRole($paRoleCode,$paData){

        try{
            $aResult    = array(
                'FTRolCode'     => $paRoleCode,
                'FTUsrCode'     => $paData['FTUsrCode'],
                'FDLastUpdOn'   => $paData['FDLastUpdOn'],
                'FTLastUpdBy'   => $paData['FTLastUpdBy'],
                'FDCreateOn'    => $paData['FDCreateOn'],
                'FTCreateBy'    => $paData['FTCreateBy']                
            );
            $this->db->insert('TCNMUsrActRole',$aResult);
        }catch(Exception $Error){
            return $Error;
        }     
    }

    //Functionality : Function Add Update Master ActRole
    //Parameters : function parameters
    //Creator : 11/06/2018 witsarut (bell)
    //Last Modified : -
    //Return : Status Add Update Master
    //Return Type : Array
    public function FSaMUSRAddUpdateMasterActRole($paRoleCode,$paData){
        try{
            $aResult = array(
                'FTRolCode'     => $paRoleCode,
                'FTUsrCode'     => $paData['FTUsrCode'],
                'FDLastUpdOn'   => $paData['FDLastUpdOn'],
                'FTLastUpdBy'   => $paData['FTLastUpdBy'],
                'FDCreateOn'    => $paData['FDCreateOn'],
                'FTCreateBy'    => $paData['FTCreateBy']
            );

            $this->db->insert('TCNMUsrActRole',$aResult);
        }catch(Exception $Error){
            return $Error;
        }
    }

     //Functionality : Function Add Update Lang
    //Parameters : function parameters
    //Creator : 11/06/2018 Wasin
    //Last Modified : -
    //Return : Status Add Update Lang
    //Return Type : Array
    public function FSaMUSRAddUpdateLang($paData){
        try{
            //Update Lang
            $this->db->set('FTUsrName',$paData['FTUsrName']);
            $this->db->set('FTUsrRmk',$paData['FTUsrRmk']);
            $this->db->where('FTUsrCode',$paData['FTUsrCode']);
            $this->db->where('FNLngID',$paData['FNLngID']);
            $this->db->update('TCNMUser_L');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Success',
                );
            }else{
                //Add Lang
                $this->db->insert('TCNMUser_L',array(
                    'FTUsrCode' => $paData['FTUsrCode'],
                    'FNLngID'   => $paData['FNLngID'],
                    'FTUsrName' => $paData['FTUsrName'],
                    'FTUsrRmk'  => $paData['FTUsrRmk'],
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Success',
                    );
                }else{
                    //Error 
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Lang.',
                    );
                }
            }
            $jStatus = json_encode($aStatus);
            $aStatus = json_decode($jStatus, true);
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    //Functionality : Function Add Update Group
    //Parameters : function parameters
    //Creator : 11/06/2018 Wasin
    //Last Modified : -
    //Return : Status Add Update Group
    //Return Type : Array
    public function FSaMUSRAddUpdateGroup($paData){
        try{
            //Update Group
            $this->db->set('FTBchCode',$paData['FTBchCode']);
            $this->db->set('FTUsrStaShop',$paData['FTUsrStaShop']);
            $this->db->set('FTShpCode',$paData['FTShpCode']);
            $this->db->set('FDUsrStart',$paData['FDUsrStart']);
            $this->db->set('FDUsrStop',$paData['FDUsrStop']);
            // $this->db->where('FTBchCode',$paData['FTBchCode']);
            $this->db->where('FTUsrCode',$paData['FTUsrCode']);
            $this->db->update('TCNTUsrGroup');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Success',
                );
            }else{
                //Add Group
                $this->db->insert('TCNTUsrGroup',array(
                    'FTUsrCode'     => $paData['FTUsrCode'],
                    'FTBchCode'     => $paData['FTBchCode'],
                    'FTUsrStaShop'  => $paData['FTUsrStaShop'],
                    'FTShpCode'     => $paData['FTShpCode'],
                    'FDUsrStart'    => $paData['FDUsrStart'],
                    'FDUsrStop'     => $paData['FDUsrStop']
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Success',
                    );
                }else{
                    //Error 
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Group.',
                    );
                }
            }
            $jStatus = json_encode($aStatus);
            $aStatus = json_decode($jStatus, true);
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    //Functionality : Delete User
    //Parameters : function parameters
    //Creator : 08/06/2018 Wasin
    //Return : Status Delete 
    //Return Type : array
    public function FSnMUSRDel($paData){
        try{
            $this->db->where_in('FTUsrCode', $paData['FTUsrCode']);
            $this->db->delete('TCNMUser');
        
            $this->db->where_in('FTUsrCode', $paData['FTUsrCode']);
            $this->db->delete('TCNMUser_L');

            $this->db->where_in('FTUsrCode', $paData['FTUsrCode']);
            $this->db->delete('TCNTUsrGroup');

            $this->db->where_in('FTUsrCode', $paData['FTUsrCode']);
            $this->db->delete('TCNMUsrLogin');
            
            // Create By Witsarut 21/02/2020
            $this->db->where_in('FTUsrCode' ,$paData['FTUsrCode']);
            $this->db->delete('TCNMUsrActRole');


            if($this->db->affected_rows() > 0){
                //Success
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'success',
                );
            }else{
                //Ploblem
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Cannot Delete.',
                );
            }
            $jStatus = json_encode($aStatus);
            $aStatus = json_decode($jStatus, true);
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    } 
    
    /**
     * Functionality : Query User Full By ID
     * Parameters : function parameters
     * Creator : 03/10/2018 piya
     * Last Modified : 17/10/2018 piya
     * Return : data
     * Return Type : array
     */
    public function FSaMUSRByID($paData){
        $tUsrCode   = $paData['FTUsrCode'];
        $nLngID     = $paData['FNLngID'];
        $tSQL = "SELECT
                    IMGP.FTImgObj       AS rtUsrImage,
                    USR.FTUsrCode       AS rtUsrCode,
                    USR.FTUsrTel        AS rtUsrTel,
                    USR.FTUsrEmail      AS rtUsrEmail,
                    USR.FTUsrPwd        AS rtUsrPassword,
                    USRL.FTUsrName      AS rtUsrName,
                    USRL.FTUsrRmk       AS rtUsrRmk,
                    UDPT.FTDptCode      AS rtDptCode,
                    UDPT.FTDptName      AS rtDptName,
                    UROL.FTRolCode      AS rtRolCode,
                    UROL.FTRolName      AS rtRolName,
                    BCHL.FTBchCode      AS rtBchCode,
                    BCHL.FTBchName      AS rtBchName,
                    SHPL.FTShpCode      AS rtShpCode,
                    SHPL.FTShpName      AS rtShpName,
                    USRG.FDUsrStart     AS rtUsrStartDate,
                    USRG.FDUsrStop      AS rtUsrEndDate

                 FROM [TCNMUser] USR
                 LEFT JOIN [TCNMUser_L] USRL ON USR.FTUsrCode = USRL.FTUsrCode AND USRL.FNLngID = $nLngID
                 LEFT JOIN [TCNMUsrDepart_L] UDPT ON USR.FTDptCode = UDPT.FTDptCode AND UDPT.FNLngID = $nLngID
                 LEFT JOIN [TCNMUsrRole_L] UROL ON USR.FTRolCode = UROL.FTRolCode AND UROL.FNLngID = $nLngID
                 LEFT JOIN [TCNTUsrGroup] USRG ON USR.FTUsrCode = USRG.FTUsrCode
                 LEFT JOIN [TCNMBranch_L] BCHL ON USRG.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                 LEFT JOIN [TCNMShop_L] SHPL ON USRG.FTShpCode = SHPL.FTShpCode AND USRG.FTBchCode = SHPL.FTBchCode AND SHPL.FNLngID = $nLngID
                 LEFT JOIN [TCNMImgPerson] IMGP ON USR.FTUsrCode = IMGP.FTImgRefID AND IMGP.FTImgTable = 'TCNMUser'
                 WHERE 1=1
                 AND USR.FTUsrCode = '$tUsrCode'";
        
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            //Not Found
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }



   //Functionality : get all row data from pdt location
    //Parameters : -
    //Creator : 1/04/2019 Pap
    //Return : array result from db
    //Return Type : array

    public function FSnMLOCGetAllNumRow(){
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TCNMUser";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }        
}





