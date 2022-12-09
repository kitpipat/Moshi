<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mVouchertype extends CI_Model {
    
    //Functionality : Search Recive By ID
    //Parameters : function parameters
    //Creator : 11/05/2018 Wasin
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMVOTSearchByID($paData){

        $tVocCode   = $paData['FTVocCode'];
        $nLngID     = $paData['FNLngID'];
        
        $tSQL = "SELECT
                        VOT.FTVotCode        AS rtVotCode,
                        VOT.FTVotStaUse      AS rtVotStaUse,
                        VOTL.FTVotName       AS rtVotName,
                        VOTL.FTVotRemark     AS rtVotRemark


                FROM [TFNMVouchertype] VOT
                LEFT JOIN [TFNMVouchertype_L] VOTL ON VOT.FTVotCode = VOTL.FTVotCode AND VOTL.FNLngID = $nLngID
                WHERE 1=1 ";
        
        if($tVocCode!= ""){
            $tSQL .= "AND VOT.FTVotCode = '$tVocCode'";
        }

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
    
    //Functionality : list Recive
    //Parameters : function parameters
    //Creator :  11/05/2018 Wasin
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMVOTList($paData){

        $aRowLen = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $nLngID = $paData['FNLngID'];
        $tSQL   = "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTVotCode ASC) AS FNRowID,* FROM
                            (SELECT DISTINCT
                                VOT.FTVotCode,
                                VOT.FTVotStaUse,
                                VOT.FDCreateOn,
                                VOTL.FTVotName,
                                VOTL.FTVotRemark
                    
                            FROM [TFNMVoucherType] VOT
                            LEFT JOIN [TFNMVoucherType_L] VOTL ON VOT.FTVotCode = VOTL.FTVotCode AND VOTL.FNLngID = $nLngID
                            WHERE 1=1  ";
        
        @$tSearchList = $paData['tSearchAll'];
        if(@$tSearchList != ''){
            $tSQL .= " AND (VOT.FTVotCode LIKE '%$tSearchList%'";
            $tSQL .= "      OR VOTL.FTVotName LIKE '%$tSearchList%')";
        }
        
        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
   
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList = $oQuery->result();
            $aFoundRow = $this->FSnMVOCGetPageAll($tSearchList,$nLngID);
            $nFoundRow = $aFoundRow[0]->counts;
            $nPageAll = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult = array(
                'raItems'       => $oList,
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
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }
    
    //Functionality : Update Creditcard
    //Parameters : function parameters
    //Creator : 03/07/2018 Krit(Copter)
    //Last Modified : -
    //Return : response
    //Return Type : Array
    public function FSaMVOTAddUpdateMaster($paData){
        try{
            //Update Master
            $this->db->set('FTVotCode' , $paData['FTVotCode']);
            $this->db->set('FTVotStaUse' , $paData['FTVotStaUse']);
            $this->db->set('FDCreateOn' , $paData['FDCreateOn']);
            $this->db->set('FTLastUpdBy' , $paData['FTLastUpdBy']);
            $this->db->set('FTCreateBy' , $paData['FTCreateBy']);
            $this->db->set('FDLastUpdOn' , $paData['FDLastUpdOn']);
            $this->db->where('FTVotCode', $paData['FTVotCode']);
            $this->db->update('TFNMVoucherType');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                //Add Master
                $this->db->insert('TFNMVoucherType',array(
                    'FTVotCode'     => $paData['FTVotCode'],
                    'FTVotStaUse'   => $paData['FTVotStaUse'],
                    'FDCreateOn'    => $paData['FDCreateOn'],
                    'FTLastUpdBy'   => $paData['FTLastUpdBy'],
                    'FTCreateBy'    => $paData['FTCreateBy'],
                    'FDLastUpdOn'   => $paData['FDLastUpdOn']

                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Master Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Master.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }


    //Functionality : Update Lang Bank
    //Parameters : function parameters
    //Creator : 02/07/2018 Krit(Copter)
    //Last Modified : -
    //Return : response
    //Return Type : num
    public function FSaMVOTAddUpdateLang($paData){
        try{
            //Update Lang
            $this->db->set('FTVotName', $paData['FTVotName']);
            $this->db->set('FTVotRemark', $paData['FTVotRemark']);
            $this->db->where('FNLngID', $paData['FNLngID']);
            $this->db->where('FTVotCode', $paData['FTVotCode']);
            $this->db->update('TFNMVoucherType_L');
            if($this->db->affected_rows() > 0 ){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Lang Success.',
                );
            }else{
                $this->db->insert('TFNMVoucherType_L',array(
                    'FTVotCode' => $paData['FTVotCode'],
                    'FNLngID'   => $paData['FNLngID'],
                    'FTVotName' => $paData['FTVotName'],
                    'FTVotRemark' => $paData['FTVotRemark'],
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Lang Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Lang.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }



    //Functionality : All Page Of Recive
    //Parameters : function parameters
    //Creator :  11/05/2018 Wasin
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMVOCGetPageAll($ptSearchList,$ptLngID){
        
        $tSQL = "SELECT COUNT (VOT.FTVotCode) AS counts
                 FROM TFNMVoucherType VOT
                 LEFT JOIN [TFNMVoucherType_L] VOTL ON VOT.FTVotCode = VOTL.FTVotCode AND VOTL.FNLngID = $ptLngID
                 WHERE 1=1 ";
        
        if($ptSearchList != ''){
            $tSQL .= " AND (VOT.FTVotCode LIKE '%$ptSearchList%'";
            $tSQL .= "      OR VOTL.FTVotName LIKE '%$ptSearchList%')";
        }
        
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            //No Data
            return false;
        }
    }
    
    
    //Functionality : Checkduplicate
    //Parameters : function parameters
    //Creator : 29/04/2019 saharat(golf) 
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMVOCCheckDuplicate($ptVotCode){
        $tSQL = "SELECT COUNT(FTVotCode)AS counts
                 FROM TFNMVoucherType
                 WHERE FTVotCode = '$ptVotCode' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->result();
        }else{
            return false;
        }
    }
    
    //Functionality : Delete Voucher
    //Parameters : function parameters
    //Creator : 14/05/2018 wasin
    //Return : response
    //Return Type : array
    public function FSnMVOCDel($paData){
        $this->db->where_in('FTVotCode', $paData['FTVotCode']);
        $this->db->delete('TFNMVoucherType');
        
        $this->db->where_in('FTVotCode', $paData['FTVotCode']);
        $this->db->delete('TFNMVoucherType_L');
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
        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
