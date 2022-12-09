<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mBank extends CI_Model {
    
    //Functionality : Search Recive By ID
    //Parameters : function parameters
    //Creator : 11/05/2018 Wasin
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMBNKSearchByID($paData){

        $tBnkCode   = $paData['FTBnkCode'];
        $nLngID     = $paData['FNLngID'];
        
        $tSQL = "SELECT
                    BNK.FTBnkCode   AS rtBnkCode,
                    BNKL.FTBnkName  AS rtBnkName,
                    BNKL.FTBnkRmk   AS rtBnkRmk ,
                    Img.FTImgObj   AS rtFTImgObj
                 FROM [TFNMBank] BNK
                 LEFT JOIN [TFNMBank_L] BNKL ON BNK.FTBnkCode = BNKL.FTBnkCode AND BNKL.FNLngID = $nLngID
                 LEFT JOIN [TCNMImgObj] Img ON Img.FTImgRefID =  BNK.FTBnkCode AND BNKL.FNLngID = $nLngID
                 WHERE 1=1 "; 
        
        if($tBnkCode!= ""){
            $tSQL .= "AND BNK.FTBnkCode = '$tBnkCode'";
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
    public function FSaMBNKList($paData){

        $aRowLen = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $nLngID = $paData['FNLngID'];
      
        $tSQL   = "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTBnkCode ASC) AS FNRowID,* FROM
                            (SELECT DISTINCT
                                BNK.FTBnkCode,
                                BNKL.FTBnkName,
                                BNKL.FTBnkRmk
                            FROM [TFNMBank] BNK
                            LEFT JOIN [TFNMBank_L] BNKL ON BNK.FTBnkCode = BNKL.FTBnkCode AND BNKL.FNLngID = $nLngID
                          
                            WHERE 1=1 ";
        
        @$tSearchList = $paData['tSearchAll'];
        if(@$tSearchList != ''){
            $tSQL .= " AND (BNK.FTBnkCode LIKE '%$tSearchList%'";
            $tSQL .= " OR BNKL.FTBnkName LIKE '%$tSearchList%'";
            $tSQL .= " OR BNKL.FTBnkRmk LIKE '%$tSearchList%')";
        }
        
        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1] ";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList = $oQuery->result();
            $aFoundRow = $this->FSnMBNKGetPageAll($tSearchList,$nLngID);
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
    
    //Functionality : Update Bank
    //Parameters : function parameters
    //Creator : 02/07/2018 Krit(Copter)
    //Last Modified : -
    //Return : response
    //Return Type : Array
    public function FSaMBNKAddUpdateMaster($paData){
        try{
            //Update Master
            $this->db->set('FTBnkCode' , $paData['FTBnkCode']);
            $this->db->set('FDLastUpdOn' , $paData['FDLastUpdOn']);
            // $this->db->set('FDDateUpd' , $paData['FDDateUpd']);
            // $this->db->set('FTTimeUpd' , $paData['FTTimeUpd']);
            // $this->db->set('FTWhoUpd' , $paData['FTWhoUpd']);
            $this->db->where('FTBnkCode', $paData['FTBnkCode']);
            $this->db->update('TFNMBank');
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Master Success',
                );
            }else{
                //Add Master
                $this->db->insert('TFNMBank',array(
                    'FTBnkCode' => $paData['FTBnkCode'],
                    'FDLastUpdOn' => $paData['FDLastUpdOn']
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
    public function FSaMBNKAddUpdateLang($paData){
        try{
            //Update Lang
            $this->db->set('FTBnkCode', $paData['FTBnkCode']);
            $this->db->set('FTBnkName', $paData['FTBnkName']);
            $this->db->set('FTBnkRmk', $paData['FTBnkRmk']);
            $this->db->where('FNLngID', $paData['FNLngID']);
            $this->db->where('FTBnkCode', $paData['tBnkCodeOld']);
            $this->db->update('TFNMBank_L');
            if($this->db->affected_rows() > 0 ){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Lang Success.',
                );
            }else{
                $this->db->insert('TFNMBank_L',array(
                    'FTBnkCode' => $paData['FTBnkCode'],
                    'FNLngID'   => $paData['FNLngID'],
                    'FTBnkName' => $paData['FTBnkName'],
                    'FTBnkRmk' => $paData['FTBnkRmk'],
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
    public function FSnMBNKGetPageAll($ptSearchList,$ptLngID){
        
        $tSQL = "SELECT COUNT (BNK.FTBnkCode) AS counts
                 FROM TFNMBank BNK
                 LEFT JOIN [TFNMBank_L] BNKL ON BNK.FTBnkCode = BNKL.FTBnkCode AND BNKL.FNLngID = $ptLngID
                 WHERE 1=1 ";
        
        if($ptSearchList != ''){
            $tSQL .= " AND (BNK.FTBnkCode LIKE '%$ptSearchList%'";
            $tSQL .= " OR BNKL.FTBnkName LIKE '%$ptSearchList%')";
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
    //Creator : 15/05/2018 wasin
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSnMBNKCheckDuplicate($ptBnkCode){
        $tSQL = "SELECT COUNT(FTBnkCode)   AS counts
                 FROM TFNMBank
                 WHERE FTBnkCode = '$ptBnkCode' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->result();
        }else{
            return false;
        }
    }
    
    //Functionality : Delete Recive
    //Parameters : function parameters
    //Creator : 14/05/2018 wasin
    //Return : response
    //Return Type : array
    public function FSnMBNKDel($paData){
        $this->db->where_in('FTBnkCode', $paData['FTBnkCode']);
        $this->db->delete('TFNMBank');
        
        $this->db->where_in('FTBnkCode', $paData['FTBnkCode']);
        $this->db->delete('TFNMBank_L');
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
    

      /*
    //Functionality : Add Data Bank
    //Parameters : function parameters
    //Creator : 28/01/2020 nonpawich(petch)
    //Return : array
    //Return Type : array
    */

   public function FSaMBNKGetdataBank($paData) {

    $nLngID     = $paData['FNLngID'];
    
    $tSQL = " SELECT BNK_L.FTBnkName AS FTBnkName,
                BNK.FTBnkCode AS FTBnkCode,
                BNK.FDLastUpdOn AS FDLastUpdOn,
                BNK.FTLastUpdBy AS FTLastUpdBy,
                BNK.FDCreateOn AS FDCreateOn,
                BNK.FTCreateBy AS FTCreateBy
              FROM TFNMBank  BNK
              LEFT JOIN  TFNMBank_L BNK_L 
              ON BNK.FTBnkCode = BNK_L.FTBnkCode   AND BNK_L.FNLngID = ' $nLngID' 
              ";
    $oQuery = $this->db->query($tSQL);
    return $oQuery->result_array(); 
   }



      /*
    //Functionality : get data 
    //Parameters : function parameters
    //Creator : 28/01/2020 nonpawich(petch)
    //Return : array
    //Return Type : array
    */

   public function FSaMBnkListBank($paData){

    $aRowLen = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
    $nLngID = $paData['FNLngID'];
    $tSQL   = " SELECT c.* FROM(
                    SELECT  ROW_NUMBER() OVER(ORDER BY FTBnkCode ASC) AS FNRowID,* FROM
                        (SELECT DISTINCT
                        BNKL.FTBnkName AS FTBnkName,
                        BNK.FTBnkCode AS FTBnkCode,
                        BNK.FDLastUpdOn AS FDLastUpdOn,
                        BNK.FTLastUpdBy AS FTLastUpdBy,
                        BNK.FDCreateOn AS FDCreateOn,
                        BNK.FTCreateBy AS FTCreateBy ,
                        IMG.FTImgObj AS FTImgObj 
                        FROM [TFNMBank] BNK
                        LEFT JOIN [TFNMBank_L] BNKL ON BNK.FTBnkCode = BNKL.FTBnkCode AND BNKL.FNLngID = $nLngID 
                        LEFT JOIN [TCNMImgObj] IMG ON BNK.FTBnkCode = IMG.FTImgRefID  
                        WHERE 1=1 ";
    
    @$tSearchList = $paData['tSearchAll'];
    if(@$tSearchList != ''){
        $tSQL .= " AND (BNK.FTBnkCode LIKE '%$tSearchList%'";
        $tSQL .= " OR BNKL.FTBnkName LIKE '%$tSearchList%'";
        $tSQL .= " OR BNKL.FTBnkRmk LIKE '%$tSearchList%')";
    }
    
   
    $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
    // var_dump  ($tSQL);
    // exit;
    $oQuery = $this->db->query($tSQL);
    if($oQuery->num_rows() > 0){
        $oList = $oQuery->result();
        $aFoundRow = $this->FSnMBNKGetPageAll($tSearchList,$nLngID);
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



      //Functionality : Delete Agency
    //Parameters : function parameters
    //Creator : 11/06/2019 saharat(Golf)
    //Return : response
    //Return Type : array
    public function FSnMBnkDelete($paData){
        $this->db->where_in('FTBnkCode', $paData['FTBnkCode']);
        $this->db->delete('TFNMBank');
 
        $this->db->where_in('FTBnkCode', $paData['FTBnkCode']);
        $this->db->delete('TFNMBank_L');
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


        //Functionality : get all row 
    //Parameters : -
    //Creator : 11/06/2019 saharat(Golf)
    //Return : array result from db
    //Return Type : array
    public function FSnMBnkGetAllNumRow(){
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TFNMBank";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }


    //Functionality : Update&insert bank
    //Parameters : function parameters
    //Creator : 10/06/2019 nonpawich (petch)
    //Last Modified : -
    //Return : response
    //Return Type : Array
    public function FSaMBnkAddAndUpdateMaster($paData){
        try{

           
                //Update Master
                $this->db->set('FTBnkCode' , $paData['FTBnkCode']);
                $this->db->set('FDLastUpdOn' , $paData['FDLastUpdOn']);
                $this->db->where('FTBnkCode', $paData['tBnkCodeOld']);
                $this->db->update('TFNMBank');
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Update Master Success',
                    );
                }else{
                    //Add Master
                    $this->db->insert('TFNMBank',array(
                        'FTBnkCode' => $paData['FTBnkCode'],
                        'FDLastUpdOn' => $paData['FDLastUpdOn']        
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
   
}


