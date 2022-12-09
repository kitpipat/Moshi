<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mPdtTouchGrp extends CI_Model {

    //Functionality : list Product Touch Group
    //Parameters : function parameters
    //Creator :  19/09/2018 Wasin
    //Return : data
    //Return Type : Array
    public function FSaMTCGList($paData){
        try{
            $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $nLngID         = $paData['FNLngID'];
            $tSearchList    = $paData['tSearchAll'];
            $tSQL       = "SELECT c.* FROM(
                                SELECT  ROW_NUMBER() OVER(ORDER BY rtTcgCode ASC) AS rtRowID,* FROM
                                    (SELECT DISTINCT
                                        TCG.FTTcgCode   AS rtTcgCode,
                                        TCG.FTTcgStaUse AS rtTcgStaUse,
                                        TCG_L.FTTcgName AS rtTcgName
                                     FROM [TCNMPdtTouchGrp] TCG
                                     LEFT JOIN [TCNMPdtTouchGrp_L]  TCG_L ON TCG.FTTcgCode = TCG_L.FTTcgCode AND TCG_L.FNLngID = $nLngID
                                     WHERE 1=1 ";

            if(isset($tSearchList) && !empty($tSearchList)){
                $tSQL .= " AND (TCG.FTTcgCode LIKE '%$tSearchList%'";
                $tSQL .= " OR TCG_L.FTTcgName  LIKE '%$tSearchList%')";
            }
            $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $aList = $oQuery->result_array();
                $oFoundRow = $this->FSoMTCGGetPageAll($tSearchList,$nLngID);
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

    //Functionality : All Page Of Product Touch Group
    //Parameters : function parameters
    //Creator :  19/09/2018 Wasin
    //Return : object Count All Product Touch Group
    //Return Type : Object
    public function FSoMTCGGetPageAll($ptSearchList,$ptLngID){
        try{
            $tSQL = "SELECT COUNT (TCG.FTTcgCode) AS counts
                     FROM [TCNMPdtTouchGrp] TCG
                     LEFT JOIN [TCNMPdtTouchGrp_L]  TCG_L ON TCG.FTTcgCode = TCG_L.FTTcgCode AND TCG_L.FNLngID = $ptLngID
                     WHERE 1=1 ";
            if(isset($ptSearchList) && !empty($ptSearchList)){
                $tSQL .= " AND (TCG.FTTcgCode LIKE '%$ptSearchList%'";
                $tSQL .= " OR TCG_L.FTTcgName  LIKE '%$ptSearchList%')";
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

    //Functionality : Get Data Product Touch Group By ID
    //Parameters : function parameters
    //Creator : 20/09/2018 Wasin
    //Return : data
    //Return Type : Array
    public function FSaMTCGGetDataByID($paData){
        try{
            $tTcgCode   = $paData['FTTcgCode'];
            $nLngID     = $paData['FNLngID'];
            $tSQL       = " SELECT 
                                TCG.FTTcgCode   AS rtTcgCode,
                                TCG.FTTcgStaUse AS rtTcgStaUse,
                                TCG_L.FTTcgName AS rtTcgName,
                                TCG_L.FTTcgRmk  AS rtTcgRmk
                            FROM TCNMPdtTouchGrp TCG
                            LEFT JOIN TCNMPdtTouchGrp_L TCG_L ON TCG.FTTcgCode = TCG_L.FTTcgCode AND TCG_L.FNLngID = $nLngID 
                            WHERE 1=1 AND TCG.FTTcgCode = '$tTcgCode' ";
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

    //Functionality : Checkduplicate Product Touch Group
    //Parameters : function parameters
    //Creator : 20/09/2018 Wasin
    //Return : data
    //Return Type : Array
    public function FSnMTCGCheckDuplicate($ptTcgCode){
        try{
            $tSQL = "SELECT COUNT(TCG.FTTcgCode) AS counts
                     FROM TCNMPdtTouchGrp TCG 
                     WHERE TCG.FTTcgCode = '$ptTcgCode' ";
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                return $oQuery->row_array();
            }else{
                return FALSE;
            }
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Add/Update Product Touch Group Master
    //Parameters : function parameters
    //Creator : 20/09/2018 Wasin
    //Return : Array Stutus Add Update
    //Return Type : Array
    public function FSaMTCGAddUpdateMaster($paDataPdtTouchGrp){
        try{
            // Update Master
            $this->db->where('FTTcgCode', $paDataPdtTouchGrp['FTTcgCode']);
            $this->db->update('TCNMPdtTouchGrp',array(
                'FTTcgStaUse'   => $paDataPdtTouchGrp['FTTcgStaUse'],
                'FDLastUpdOn'   => $paDataPdtTouchGrp['FDLastUpdOn'],
                'FTLastUpdBy'   => $paDataPdtTouchGrp['FTLastUpdBy']
            ));
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Product Touch Group Success.',
                );
            }else{
                //Add Master
                $this->db->insert('TCNMPdtTouchGrp', array(
                    'FTTcgCode'     => $paDataPdtTouchGrp['FTTcgCode'],
                    'FTTcgStaUse'   => $paDataPdtTouchGrp['FTTcgStaUse'],
                    'FDCreateOn'    => $paDataPdtTouchGrp['FDCreateOn'],
                    'FTCreateBy'    => $paDataPdtTouchGrp['FTCreateBy']
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Product Touch Group Success.',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Product Touch Group.',
                    );
                }

            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Update Product Touch Group Lang
    //Parameters : function parameters
    //Creator : 20/09/2018 Wasin
    //Return : Array Stutus Add Update
    //Return Type : array
    public function FSaMTCGAddUpdateLang($paDataPdtTouchGrp){
        try{
            //Update Pdt Type Lang
            $this->db->where('FTTcgCode', $paDataPdtTouchGrp['FTTcgCode']);
            $this->db->where('FNLngID', $paDataPdtTouchGrp['FNLngID']);
            $this->db->update('TCNMPdtTouchGrp_L',array(
                'FTTcgName' => $paDataPdtTouchGrp['FTTcgName'],
                'FTTcgRmk'  => $paDataPdtTouchGrp['FTTcgRmk']
            ));
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Product Touch Group Lang Success.',
                );
            }else{
                //Add Pdt Type Lang
                $this->db->insert('TCNMPdtTouchGrp_L', array(
                    'FTTcgCode' => $paDataPdtTouchGrp['FTTcgCode'],
                    'FNLngID'   => $paDataPdtTouchGrp['FNLngID'],
                    'FTTcgName' => $paDataPdtTouchGrp['FTTcgName'],
                    'FTTcgRmk'  => $paDataPdtTouchGrp['FTTcgRmk']
                ));
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Product Touch Group Lang Success',
                    );
                }else{
                    $aStatus = array(
                        'rtCode' => '905',
                        'rtDesc' => 'Error Cannot Add/Edit Product Touch Group Lang.',
                    );
                }
            }
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Delete Product Touch Group
    //Parameters : function parameters
    //Creator : 20/09/2018 Wasin
    //Return : Status Delete
    //Return Type : array
    public function FSaMTCGDelAll($paData){
        try{
            $this->db->trans_begin();

            $this->db->where_in('FTTcgCode', $paData['FTTcgCode']);
            $this->db->delete('TCNMPdtTouchGrp');

            $this->db->where_in('FTTcgCode', $paData['FTTcgCode']);
            $this->db->delete('TCNMPdtTouchGrp_L');

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

    //Functionality : get all row data from pdt location
    //Parameters : -
    //Creator : 1/04/2019 Pap
    //Return : array result from db
    //Return Type : array
    public function FSnMTouchGrpGetAllNumRow(){
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TCNMPdtTouchGrp";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }





















    



}