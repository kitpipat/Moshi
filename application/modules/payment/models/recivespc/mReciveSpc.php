<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mReciveSpc extends CI_Model {

    //Functionality : LIist ReciveSpc
    //Parameters : function parameters
    //Creator :  27/11/2019 Witsarut
    //Return : data
    //Return Type : Array
    public function FSaMRCVSpcDataList($paData){
        $aDataUserInfo      = $this->session->userdata("tSesUsrInfo");
        try{

            $tRcvSpcCode    = $paData['FTRcvCode'];
        
                // Check User Level Branch HQ OR Bch Or Shop
        $tUserLevel = $this->session->userdata("tSesUsrLevel");
        $tWhereBch  = "";
        $tWhereShp  = "";
        if(isset($tUserLevel) && !empty($tUserLevel) && $tUserLevel == "BCH"){
            // Check User Level BCH
            $tWhereBch  =   " AND RCVSPC.FTBchCode = '".$aDataUserInfo['FTBchCode']."'";
        }
        if(isset($tUserLevel) && !empty($tUserLevel) && $tUserLevel == "SHP"){
            // Check User Level SHP
            $tWhereShp  =   " AND RCVSPC.FTShpCode = '".$aDataUserInfo['FTShpCode']."'";
        }
            $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $nFNLngID       = $paData['FNLngID'];
            $tSearchList    = $paData['tSearchAll'];
            $tSQL           = " SELECT c.* FROM(SELECT  ROW_NUMBER() OVER(ORDER BY FTRcvCode DESC) AS rtRowID,*
                                FROM(
                                    SELECT 	
                                        RCVSPC.FTRcvCode,
                                        RCVL.FTRcvName,
                                        RCVSPC.FTBchCode,
                                        BCHL.FTBchName,
                                        RCVSPC.FTMerCode,
                                        MERL.FTMerName,
                                        RCVSPC.FTShpCode,
                                        SHPL.FTShpName,
                                        RCVSPC.FTAggCode,
                                        AGGL.FTAggName,
                                        RCVSPC.FTAppCode,
                                        TSYSApp.FTAppName,
                                        RCVSPC.FNRcvSeq,
                                        RCVSPC.FTPdtRmk,
                                        RCVSPC.FTAppStaAlwRet,
                                        RCVSPC.FTAppStaAlwCancel,
                                        RCVSPC.FTAppStaPayLast
                                    FROM [TFNMRcvSpc] RCVSPC WITH(NOLOCK)
                                    LEFT JOIN [TFNMRcv_L] RCVL WITH(NOLOCK) ON RCVSPC.FTRcvCode = RCVL.FTRcvCode 
                                    LEFT JOIN [TCNMBranch_L] BCHL WITH(NOLOCK) ON RCVSPC.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nFNLngID
                                    LEFT JOIN [TCNMMerchant_L] MERL WITH(NOLOCK) ON RCVSPC.FTMerCode = MERL.FTMerCode AND MERL.FNLngID = $nFNLngID
                                    LEFT JOIN [TCNMShop_L] SHPL WITH(NOLOCK) ON RCVSPC.FTShpCode = SHPL.FTShpCode AND SHPL.FTBchCode = RCVSPC.FTBchCode  AND SHPL.FNLngID = $nFNLngID
                                    LEFT JOIN [TCNMAgencyGrp_L] AGGL WITH(NOLOCK) ON RCVSPC.FTAggCode = AGGL.FTAggCode AND AGGL.FNLngID = $nFNLngID
                                    LEFT JOIN [TSysApp_L] TSYSApp WITH(NOLOCK) ON RCVSPC.FTAppCode = TSYSApp.FTAppCode AND TSYSApp.FNLngID = $nFNLngID
                                    WHERE 1=1
                                    AND RCVSPC.FTRcvCode    = '$tRcvSpcCode'
                                    $tWhereBch
                                    $tWhereShp
                                    
            ";
            $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
            $oQuery = $this->db->query($tSQL);

            if($oQuery->num_rows() > 0){
                $aList      = $oQuery->result_array();
               
                $oFoundRow  = $this->FSoMRCVSpcGetPageAll($tSearchList,$paData);
                $nFoundRow  = $oFoundRow[0]->counts;
                $nPageAll   = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
                $aResult    = array(
                    'raItems'       => $aList,
                    'rnAllRow'      => $nFoundRow,
                    'rnCurrentPage' => $paData['nPage'],
                    'rnAllPage'     => $nPageAll,
                    'rtCode'        => '1',
                    'rtDesc'        => 'success',
                    'tSQL'          => $tSQL
                );
            }else{
                //No Data
                $aResult = array(
                    'rnAllRow'      => 0,
                    'rnCurrentPage' => $paData['nPage'],
                    "rnAllPage"     => 0,
                    'rtCode'        => '800',
                    'rtDesc'        => 'data not found'
                );
            }
            return $aResult;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Count Userlogin
    //Parameters : function parameters
    //Creator :  19/08/2019 Witsarut
    //Return : data
    //Return Type : Array
    public function FSoMRCVSpcGetPageAll($ptSearchList,$paData){
        try{
            $tRcvSpcCode    = $paData['FTRcvCode'];
            $tSQL       = " SELECT
                                COUNT (RCVSPC.FTRcvCode) AS counts
                            FROM [TFNMRcvSpc] RCVSPC WITH(NOLOCK)
                            WHERE 1=1
                            AND RCVSPC.FTRcvCode    = '$tRcvSpcCode'
            ";

            if(isset($ptSearchList) && !empty($ptSearchList)){
                $tSQL .= " AND (RCVSPC.FTRcvCode LIKE '%$ptSearchList%')";
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

    //Functionality : check Data Userlogin
    //Parameters : function parameters
    //Creator : 20/08/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMRCVSPCCheckID($paData){
        $tRcvCode   = $paData['FTRcvCode'];
        $tAppCode   = $paData['FTAppCode'];
        $nRcvSeq    = $paData['FNRcvSeq'];
        $nLngID     = $paData['FNLngID'];

        $tSQL   = " SELECT 
                        RCVSPC.FTRcvCode,
                        RCVL.FTRcvName,
                        RCVSPC.FTBchCode,
                        BCHL.FTBchName,
                        RCVSPC.FTMerCode,
                        MERL.FTMerName,
                        RCVSPC.FTShpCode,
                        SHPL.FTShpName,
                        RCVSPC.FTAggCode,
                        AGGL.FTAggName,
                        RCVSPC.FTAppCode,
                        TSYSApp.FTAppName,
                        RCVSPC.FNRcvSeq,
                        RCVSPC.FTPdtRmk,
                        RCVSPC.FTAppStaAlwRet,
                        RCVSPC.FTAppStaAlwCancel,
                        RCVSPC.FTAppStaPayLast
                    FROM [TFNMRcvSpc] RCVSPC WITH(NOLOCK)
                    LEFT JOIN [TFNMRcv_L] RCVL WITH(NOLOCK) ON RCVSPC.FTRcvCode = RCVL.FTRcvCode 
                    LEFT JOIN [TCNMBranch_L] BCHL WITH(NOLOCK) ON RCVSPC.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID
                    LEFT JOIN [TCNMMerchant_L] MERL WITH(NOLOCK) ON RCVSPC.FTMerCode = MERL.FTMerCode AND MERL.FNLngID = $nLngID
                    LEFT JOIN [TCNMShop_L] SHPL WITH(NOLOCK) ON RCVSPC.FTShpCode = SHPL.FTShpCode AND SHPL.FTBchCode = RCVSPC.FTShpCode AND SHPL.FNLngID = $nLngID
                    LEFT JOIN [TCNMAgencyGrp_L] AGGL WITH(NOLOCK) ON RCVSPC.FTAggCode = AGGL.FTAggCode AND AGGL.FNLngID = $nLngID
                    LEFT JOIN [TSysApp_L] TSYSApp WITH(NOLOCK) ON RCVSPC.FTAppCode = TSYSApp.FTAppCode AND TSYSApp.FNLngID = $nLngID
                    WHERE 1=1
                    AND RCVSPC.FTRcvCode = '$tRcvCode'
                    AND RCVSPC.FTAppCode = '$tAppCode'
                    AND RCVSPC.FNRcvSeq  = '$nRcvSeq'
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail    = $oQuery->result_array();
            $aResult= array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            //if data not found
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        unset($tRcvCode);
        unset($tAppCode);
        unset($nRcvSeq);
        unset($nLngID);
        unset($tSQL);
        unset($oQuery);
        unset($oDetail);
        return $aResult;
    }


    //Functionality : Count Seq
    //Parameters : function parameters
    //Creator : 27/09/2018 Witsarut
    //Return : data
    //Return Type : Array
    public function FSnMRcvSpcCountSeq(){
        $tSQL = "SELECT COUNT(FNRcvSeq) AS counts
                FROM TFNMRcvSpc";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->row_array()["counts"];
        }else{
            return FALSE;
        }
    }


    //Functionality : Checkduplicate Data 
    //Parameters : function parameters
    //Creator :  20/08/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : data Count Duplicate
    //Return Type : object
    public function FSaMRcvSpcCheckCrdCode($paData){
        $tAppCode    = $paData['FTAppCode'];
        $tRcvCode    = $paData['FTRcvCode'];

        $tSQL = "SELECT 
                    RCVSPC.FTAppCode
                FROM [TFNMRcvSpc] RCVSPC WITH(NOLOCK)
                WHERE 1=1
                AND RCVSPC.FTRcvCode = '$tRcvCode'
                AND RCVSPC.FTAppCode = '$tAppCode'
        ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oDetail    = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail[0],
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            //if data not found
            $aResult    = array(
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Functionality : Update Seq Number In Table TFNMRcvSpc
    //Parameters : function parameters
    //Creator : 28/11/2019 Witsarut (Bell)
    //Return : data
    //Return Type : Array
    public function FSaMRcvSpcUpdateSeqNumber(){
        $tSQL = " UPDATE RCVSPC
                SET
                RCVSPC.FNRcvSeq     = RCVSEQ.nRowID
            FROM TFNMRcvSpc RCVSPC WITH(NOLOCK)
            INNER JOIN (
                SELECT 
                ROW_NUMBER() OVER (PARTITION BY FTRcvCode ORDER BY FTRcvCode) nRowID , *
                FROM TFNMRcvSpc WITH(NOLOCK)
            ) AS RCVSEQ
            ON 1=1
            AND RCVSPC.FNRcvSeq     =  RCVSEQ.FNRcvSeq
            AND RCVSPC.FTRcvCode    =  RCVSEQ.FTRcvCode
        ";
        return $this->db->query($tSQL);
    }



    //Functionality : Function Add Update Master RCVSPC
    //Parameters : function parameters
    //Creator : 28/11/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : Status Add Update Master
    //Return Type : Array
    public function  FSaMRCVSPCAddMaster($paData){
        $aResult    = array(
            'FTRcvCode'         => $paData['FTRcvCode'],
            'FTAppCode'         => $paData['FTAppCode'],
            'FTBchCode'         => $paData['FTBchCode'],
            'FTMerCode'         => $paData['FTMerCode'],
            'FTShpCode'         => $paData['FTShpCode'],
            'FTAggCode'         => $paData['FTAggCode'],
            'FTPdtRmk'          => $paData['FTPdtRmk'],
            'FTAppStaAlwRet'    => $paData['FTAppStaAlwRet'],
            'FTAppStaAlwCancel' => $paData['FTAppStaAlwCancel'],
            'FTAppStaPayLast'   => $paData['FTAppStaPayLast'],
        );
        $this->db->insert('TFNMRcvSpc',$aResult);
        return;
    }


    //Functionality : Function Update Master RCVSPC
    //Parameters : function parameters
    //Creator : 28/11/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : Status Add Update Master
    //Return Type : Array
    public function FSaMRCVSPCUpdateMaster($paData){

        $this->db->set('FTBchCode', $paData['FTBchCode']);
        $this->db->set('FTMerCode', $paData['FTMerCode']);
        $this->db->set('FTShpCode', $paData['FTShpCode']);
        $this->db->set('FTAggCode', $paData['FTAggCode']);
        $this->db->set('FTBchCode', $paData['FTBchCode']);
        $this->db->set('FTAppCode', $paData['FTAppCode']);
        $this->db->set('FTPdtRmk', $paData['FTPdtRmk']);
        $this->db->set('FTAppStaAlwRet',$paData['FTAppStaAlwRet']);
        $this->db->set('FTAppStaAlwCancel',$paData['FTAppStaAlwCancel']);
        $this->db->set('FTAppStaPayLast',$paData['FTAppStaPayLast']);
        // $this->db->where('FTRcvCode',$paData['FTRcvCode']);
        // if(!empty($paData['FTBchCode'])){
        //     $this->db->where('FTBchCode',$paData['FTBchCode']);
        // }
        $this->db->where('FTRcvCode',$paData['FTRcvCode']);
        $this->db->where('FNRcvSeq',$paData['FNRcvSeq']);
        $this->db->update('TFNMRcvSpc');
        return;
    }

    //Functionality : Get all row 
    //Parameters : -
    //Creator : 28/11/2019 Witsarut (Bell)
    //Return : array result from db
    //Return Type : array
    public function FSnMLOCGetAllNumRow(){
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TFNMRcvSpc";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }


    //Functionality : Delete Userlogin
    //Parameters : function parameters
    //Creator : 04/07/2019 Witsarut (Bell)
    //Return : response
    //Return Type : array
    public function FSnMRCVSpcDel($paDataWhere){
        $this->db->where_in('FTRcvCode',$paDataWhere['FTRcvCode']);
        $this->db->where_in('FTAppCode',$paDataWhere['FTAppCode']);
        $this->db->where_in('FNRcvSeq',$paDataWhere['FNRcvSeq']);
        if(!empty($paDataWhere['FTBchCode'])){
        $this->db->where_in('FTBchCode',$paDataWhere['FTBchCode']);
    }
        if(!empty($paDataWhere['FTMerCode'])){
        $this->db->where_in('FTMerCode',$paDataWhere['FTMerCode']);
        }
        if(!empty($paDataWhere['FTShpCode'])){
        $this->db->where_in('FTShpCode',$paDataWhere['FTShpCode']);
        }
        if(!empty($paDataWhere['FTAggCode'])){
        $this->db->where_in('FTAggCode',$paDataWhere['FTAggCode']);
         }
        $this->db->delete('TFNMRcvSpc');
      
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
                'rtDesc' => 'cannot Delete Item.',
            );
        }
    }

    //Functionality : Delete Mutiple Object
    //Parameters : function parameters
    //Creator : 28/11/2019 Witsarut
    //Return : data
    //Return Type : Arra
    public function FSaMRCVSPCDeleteMultiple($paDataDelete){
        $this->db->where_in('FTRcvCode',$paDataDelete['FTRcvCode']);
        $this->db->where_in('FTAppCode',$paDataDelete['FTAppCode']);
        $this->db->where_in('FNRcvSeq',$paDataDelete['FNRcvSeq']);
        if(!empty($paDataWhere['FTBchCode'])){
            $this->db->where_in('FTBchCode',$paDataWhere['FTBchCode']);
        }
            if(!empty($paDataWhere['FTMerCode'])){
            $this->db->where_in('FTMerCode',$paDataWhere['FTMerCode']);
            }
            if(!empty($paDataWhere['FTShpCode'])){
            $this->db->where_in('FTShpCode',$paDataWhere['FTShpCode']);
            }
            if(!empty($paDataWhere['FTAggCode'])){
            $this->db->where_in('FTAggCode',$paDataWhere['FTAggCode']);
             }
        $this->db->delete('TFNMRcvSpc');
        if($this->db->affected_rows() > 0){
            //Success
            $aStatus   = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }else{
              //Ploblem
              $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }
        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }


 }


