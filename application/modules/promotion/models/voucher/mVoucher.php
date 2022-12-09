<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mVoucher extends CI_Model {
    
    //Functionality : Search Recive By ID
    //Parameters : function parameters
    //Creator : 11/05/2018 Wasin
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMVOCSearchByID($paData){

        $tVocCode   = $paData['FTVocCode'];
        $nLngID     = $paData['FNLngID'];
        
        $tSQL = "SELECT
                    VOC.FTVocCode       AS rtVocCode,
                    VOC.FTVocBarCode    AS rtVocBarCode,
                    VOC.FDVocExpired    AS rdVocExpired,
                    VOC.FTVotCode       AS rtVotCode,
                    VOTL.FTVotName      AS rtVotName,
                    VOC.FCVocValue      AS rcVocValue,
                    VOC.FCVocSalePri    AS rcVocSalePri,
                    VOC.FCVocBalance    AS rcVocBalance,
                    VOC.FTVocComBook    AS rtVocComBook,
                    VOC.FTVocStaBook    AS rtVocStaBook,
                    VOC.FTVocStaSale    AS rtVocStaSale,
                    VOC.FTVocStaUse     AS rtVocStaUse,
                    VOCL.FTVocName      AS rtVocName,
                    VOCL.FTVocRemark    AS rtVocRemark

                 FROM [TFNMVoucher] VOC
                 LEFT JOIN [TFNMVoucher_L] VOCL ON VOC.FTVocCode = VOCL.FTVocCode AND VOCL.FNLngID = $nLngID
                 LEFT JOIN [TFNMVoucherType_L] VOTL ON VOC.FTVotCode = VOTL.FTVotCode AND VOTL.FNLngID = $nLngID
                 WHERE 1=1 ";
        
        if($tVocCode!= ""){
            $tSQL .= "AND VOC.FTVocCode = '$tVocCode'";
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
    public function FSaMVOCList($paData){

        $aRowLen = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $nLngID = $paData['FNLngID'];
        $tSQL   = "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTVocCode ASC) AS FNRowID,* FROM
                            (SELECT DISTINCT
                                VOC.FTVocCode,
                                VOC.FTVocBarCode,
                                VOC.FDVocExpired,
                                VOC.FTVotCode,
                                VOC.FCVocValue,
                                VOC.FCVocSalePri,
                                VOC.FCVocBalance,
                                VOC.FTVocComBook,
                                VOC.FTVocStaBook,
                                VOC.FTVocStaSale,
                                VOC.FTVocStaUse,

                                VOCL.FTVocName,
                                VOCL.FTVocRemark,
                                VOTL.FTVotName

                            FROM [TFNMVoucher] VOC
                            LEFT JOIN [TFNMVoucher_L] VOCL ON VOC.FTVocCode = VOCL.FTVocCode AND VOCL.FNLngID = $nLngID
                            LEFT JOIN [TFNMVoucherType_L] VOTL ON VOC.FTVotCode = VOTL.FTVotCode AND VOTL.FNLngID = $nLngID
                            WHERE 1=1 ";
        
        @$tSearchList = $paData['tSearchAll'];
        if(@$tSearchList != ''){
            $tSQL .= " AND (VOC.FTVocCode LIKE '%$tSearchList%'";
            $tSQL .= "      OR VOC.FTVotCode LIKE '%$tSearchList%'";
            $tSQL .= "      OR VOC.FCVocValue LIKE '%$tSearchList%'";
            $tSQL .= "      OR VOC.FCVocSalePri LIKE '%$tSearchList%'";
            $tSQL .= "      OR VOC.FCVocBalance LIKE '%$tSearchList%'";
            $tSQL .= "      OR VOC.FTVocComBook LIKE '%$tSearchList%'";
            $tSQL .= "      OR VOC.FTVocStaBook LIKE '%$tSearchList%'";
            $tSQL .= "      OR VOCL.FTVocName LIKE '%$tSearchList%')";
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
    public function FSaMVOCAddUpdateMaster($paData){
        try{
            //Update Master
            $this->db->set('FTVocBarCode' , $paData['FTVocBarCode']);
            $this->db->set('FDVocExpired' , $paData['FDVocExpired']);
            $this->db->set('FTVotCode' , $paData['FTVotCode']);
            $this->db->set('FCVocValue' , $paData['FCVocValue']);
            $this->db->set('FCVocSalePri' , $paData['FCVocSalePri']);
            $this->db->set('FCVocBalance' , $paData['FCVocBalance']);
            $this->db->set('FTVocComBook' , $paData['FTVocComBook']);
            $this->db->set('FTVocStaBook' , $paData['FTVocStaBook']);
            $this->db->set('FTVocStaSale' , $paData['FTVocStaSale']);
            $this->db->set('FTVocStaUse' , $paData['FTVocStaUse']);

            // $this->db->set('FDDateUpd' , $paData['FDDateUpd']);
            // $this->db->set('FTTimeUpd' , $paData['FTTimeUpd']);
            // $this->db->set('FTWhoUpd' , $paData['FTWhoUpd']);
            $this->db->where('FTVocCode', $paData['FTVocCode']);
            $this->db->update('TFNMVoucher');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                //Add Master
                $this->db->insert('TFNMVoucher',array(
                    'FTVocCode'     => $paData['FTVocCode'],
                    'FTVocBarCode'  => $paData['FTVocBarCode'],
                    'FDVocExpired'  => $paData['FDVocExpired'],
                    'FTVotCode'     => $paData['FTVotCode'],
                    'FCVocValue'    => $paData['FCVocValue'],
                    'FCVocSalePri'  => $paData['FCVocSalePri'],
                    'FCVocBalance'  => $paData['FCVocBalance'],
                    'FTVocComBook'  => $paData['FTVocComBook'],
                    'FTVocStaBook'  => $paData['FTVocStaBook'],
                    'FTVocStaSale'  => $paData['FTVocStaSale'],
                    'FTVocStaUse'   => $paData['FTVocStaUse']

                    // 'FDDateIns' => $paData['FDDateIns'],
                    // 'FTTimeIns' => $paData['FTTimeIns'],
                    // 'FTWhoIns'  => $paData['FTWhoIns']
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
    public function FSaMVOCAddUpdateLang($paData){
        try{
            //Update Lang
            $this->db->set('FTVocName', $paData['FTVocName']);
            $this->db->set('FTVocRemark', $paData['FTVocRemark']);
            $this->db->where('FNLngID', $paData['FNLngID']);
            $this->db->where('FTVocCode', $paData['FTVocCode']);
            $this->db->update('TFNMVoucher_L');
            if($this->db->affected_rows() > 0 ){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Lang Success.',
                );
            }else{
                $this->db->insert('TFNMVoucher_L',array(
                    'FTVocCode' => $paData['FTVocCode'],
                    'FNLngID'   => $paData['FNLngID'],
                    'FTVocName' => $paData['FTVocName'],
                    'FTVocRemark' => $paData['FTVocRemark'],
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
        
        $tSQL = "SELECT COUNT (VOC.FTVocCode) AS counts
                 FROM TFNMVoucher VOC
                 LEFT JOIN [TFNMVoucher_L] VOCL ON VOC.FTVocCode = VOCL.FTVocCode AND VOCL.FNLngID = $ptLngID
                 WHERE 1=1 ";
        
        if($ptSearchList != ''){
            $tSQL .= " AND (VOC.FTVocCode LIKE '%$ptSearchList%'";
            $tSQL .= "      OR VOC.FTVotCode LIKE '%$ptSearchList%'";
            $tSQL .= "      OR VOC.FCVocValue LIKE '%$ptSearchList%'";
            $tSQL .= "      OR VOC.FCVocSalePri LIKE '%$ptSearchList%'";
            $tSQL .= "      OR VOC.FCVocBalance LIKE '%$ptSearchList%'";
            $tSQL .= "      OR VOC.FTVocComBook LIKE '%$ptSearchList%'";
            $tSQL .= "      OR VOC.FTVocStaBook LIKE '%$ptSearchList%'";
            $tSQL .= "      OR VOCL.FTVocName LIKE '%$ptSearchList%')";
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
    //Creator : 03/07/2018 Krit(Copter)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMVOCCheckDuplicate($ptVocCode){
        $tSQL = "SELECT COUNT(FTVocCode)AS counts
                 FROM TFNMVoucher
                 WHERE FTVocCode = '$ptVocCode' ";
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
        $this->db->where_in('FTVocCode', $paData['FTVocCode']);
        $this->db->delete('TFNMVoucher');
        
        $this->db->where_in('FTVocCode', $paData['FTVocCode']);
        $this->db->delete('TFNMVoucher_L');
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
