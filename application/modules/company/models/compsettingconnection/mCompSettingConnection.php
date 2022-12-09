<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mCompSettingConnection extends CI_Model {

    //Functionality : List CompSettingConnection
    //Parameters : function parameters
    //Creator :  19/10/2019 Witsarut
    //Return : data
    //Return Type : Array
    public function FSaMCompSetConnectDataList($paData){
        try{
            $tCompCode       = $paData['FTUrlRefID'];
            $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
            $tFNLngID       = $paData['FNLngID'];
            $tSearchList    = $paData['tSearchAll'];
    
            $tSQL           = " SELECT c.* FROM(SELECT  ROW_NUMBER() OVER(ORDER BY FTUrlRefID ASC) AS rtRowID,*
                                FROM(
                                    SELECT 	
                                        URLObj.FNUrlID,
                                        URLObj.FTUrlRefID,
                                        URLObj.FNUrlType,
                                        URLObj.FTUrlTable,
                                        URLObj.FTUrlKey,
                                        URLObj.FTUrlAddress,
                                        URLObj.FTUrlPort,
                                        URLObj.FTUrlLogo
                                    FROM [TCNTUrlObject] URLObj WITH(NOLOCK)
                                    WHERE 1=1
                                    AND URLObj.FTUrlRefID    = '$tCompCode' 
                                    AND URLObj.FNUrlType IN ('9','10','11')
                            ";
            $tSQL .= ") Base) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]";
            $oQuery  = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){    
                $aList      = $oQuery->result_array();
                $oFoundRow  = $this->FSoMCompSetConnectGetPageAll($tSearchList,$paData);
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
                // if don't have data
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


    //Functionality : Count CompSettingConnection
    //Parameters : function parameters
    //Creator :  19/08/2019 Witsarut
    //Return : data
    //Return Type : Array
    public function FSoMCompSetConnectGetPageAll($ptSearchList,$paData){
        try{
            $tCompCode       = $paData['FTUrlRefID'];
            $tSQL           =  " SELECT 
                                    COUNT (URLObj.FNUrlID) AS counts
                                FROM [TCNTUrlObject] URLObj WITH(NOLOCK)
                                WHERE 1=1
                                AND URLObj.FTUrlRefID    = '$tCompCode'
                                AND URLObj.FNUrlType IN ('9','10','11')
            ";
              if(isset($ptSearchList) && !empty($ptSearchList)){
                $tSQL .= " AND (URLObj.FNUrlID LIKE '%$ptSearchList%')";
            }
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                return $oQuery->result(); 
            }else{
                return false;
            }
        }catch(Exception $Error){
            echo $Error;
        }
    }


    //Functionality : Get Data CompSettingConnection
    //Parameters : function parameters
    //Creator : 17/09/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCompGetConCheckID($paData){
        try{
            $tCompCode  = $paData['FTUrlRefID'];
            $tUrlID     = $paData['FNUrlID'];
            $tnLngID    = $paData['FNLngID'];

            $tSQL  = "SELECT 	
                        URLObj.FNUrlID,
                        URLObj.FTUrlRefID,
                        URLObj.FNUrlType,
                        URLObj.FTUrlTable,
                        URLObj.FTUrlKey,
                        URLObj.FTUrlAddress AS rtAddressUrlobj,
                        URLObj.FTUrlPort,
                        URLObj.FTUrlLogo, 
                        URLObjlogin.FTUrlRefID,
                        URLObjlogin.FTUrlAddress AS rtAddressUrlobjlogin,
                        URLObjlogin.FTUolVhost,
                        URLObjlogin.FTUolUser,
                        URLObjlogin.FTUolPassword,
                        URLObjlogin.FTUolKey,
                        URLObjlogin.FTUolStaActive,
                        URLObjlogin.FTUolgRmk,
                        ImgObj.FTImgObj AS rtSetConImage
                     FROM [TCNTUrlObject] URLObj WITH(NOLOCK)
                     LEFT JOIN TCNTUrlObjectLogin URLObjlogin ON URLObj.FTUrlAddress = URLObjlogin.FTUrlAddress AND URLObj.FTUrlRefID = URLObjlogin.FTUrlRefID
                     LEFT JOIN TCNMImgObj ImgObj ON ImgObj.FTImgRefID = URLObj.FTUrlRefID AND ImgObj.FTImgTable = 'TCNTUrlObject'
                     WHERE 1=1 
                     AND URLObj.FNUrlID    = '$tUrlID' AND URLObj.FTUrlRefID = '$tCompCode'
                     AND URLObj.FNUrlType IN ('9','10','11')";
            $oQuery = $this->db->query($tSQL);
            if($oQuery->num_rows() > 0){
                $oDetail    = $oQuery->result();
                $aResult = array(
                    'raItems'   => $oDetail,
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
        }catch(Exception $Error){
            echo $Error;
        }
    }

    //Functionality : Count Seq
    //Parameters : function parameters
    //Creator : 18/09/2018 Witsarut
    //Return : data
    //Return Type : Array
    public function FSnMCompCountSeq(){
        $tSQL = "SELECT COUNT(FNUrlSeq) AS counts
                FROM TCNTUrlObject";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            return $oQuery->row_array()["counts"];
        }else{
            return FALSE;
        }
    }

    //Functionality : check Data CheckUrlAddress
    //Parameters : function parameters
    //Creator : 17/09/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCompSetConCheckUrlAddress($paData){
        $tUrlAddress   = $paData['FTUrlAddress'];
        $tUrlRefID     = $paData['FTUrlRefID'];
        $tUrlType      = $paData['FNUrlType'];

        $tSQL  = "SELECT 	
                URLObj.FNUrlType
            FROM [TCNTUrlObject] URLObj WITH(NOLOCK)
            WHERE 1=1
            -- AND URLObj.FTUrlAddress   = '$tUrlAddress'
            AND URLObj.FTUrlRefID = '$tUrlRefID'
            AND URLObj.FNUrlType = '$tUrlType'
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

    //Functionality : check Data CheckUrlAddressUpdate
    //Parameters : function parameters
    //Creator : 17/09/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCompSetConCheckUrlAddressUpdate($paData){
        $tUrlAddress   = $paData['FTUrlAddress'];
        $tUrlRefID     = $paData['FTUrlRefID'];
        $tUrlType      = $paData['FNUrlType'];

        $tSQL  = "SELECT 	
                URLObj.FNUrlType
            FROM [TCNTUrlObject] URLObj WITH(NOLOCK)
            WHERE 1=1
            AND URLObj.FTUrlAddress   = '$tUrlAddress'
            AND URLObj.FTUrlRefID = '$tUrlRefID'
            AND URLObj.FNUrlType = '$tUrlType'
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


    //Functionality : check Data CheckUrlAddressUpdate
    //Parameters : function parameters
    //Creator : 17/09/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCompSetConCheckUrlType($paData){
        $tUrlRefID     = $paData['FTUrlRefID'];
        // $tUrlType      = $paData['FNUrlType'];

        $tSQL  = "SELECT 	
                        URLObj.FNUrlType
                    FROM [TCNTUrlObject] URLObj WITH(NOLOCK)
                    WHERE 1=1
                    AND URLObj.FTUrlRefID = '$tUrlRefID'
                    AND URLObj.FNUrlType = '9' OR URLObj.FNUrlType = '10' OR URLObj.FNUrlType = '11'
            ";
        $oQuery = $this->db->query($tSQL);
        // echo $tSQL;
        if($oQuery->num_rows() > 0){
            $oDetail    = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            //if data not found
            $aResult    = array(
                'raItems'   => array(),
                'rtCode'    => '800',
                'rtDesc'    => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }


    //Functionality : Update SettingCon Type 1
    //Parameters : function parameters
    //Creator : 17/09/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCompSetConAddUpdateMasterUrl($paDataUrlObj){

        //Update MasterUrlObj
        try{
            if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Success',
                );
            }else{
                $aResult = array(
                    'FTUrlRefID'    => $paDataUrlObj['FTUrlRefID'],
                    'FNUrlSeq'      => $paDataUrlObj['FNUrlSeq'],
                    'FNUrlType'     => $paDataUrlObj['FNUrlType'],
                    'FTUrlTable'    => $paDataUrlObj['FTUrlTable'],
                    'FTUrlKey'      => $paDataUrlObj['FTUrlKey'],
                    'FTUrlAddress'  => $paDataUrlObj['FTUrlAddress'],
                    'FTUrlPort'     => $paDataUrlObj['FTUrlPort'],
                    'FTUrlLogo'     => $paDataUrlObj['FTUrlLogo'],
                    'FDLastUpdOn'   => $paDataUrlObj['FDLastUpdOn'],
                    'FTLastUpdBy'   => $paDataUrlObj['FTLastUpdBy'],
                    'FDCreateOn'    => $paDataUrlObj['FDCreateOn'],
                    'FTCreateBy'    => $paDataUrlObj['FTCreateBy'],
                );
                //Add Master
                $this->db->insert('TCNTUrlObject', $aResult);
                if($this->db->affected_rows() > 0){
                    $aStatus = array(
                        'rtCode' => '1',
                        'rtDesc' => 'Add Success',
                    );
                }else{
                    // Error
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

    //Functionality : Update SettingCon Type 1
    //Parameters : function parameters
    //Creator : 17/09/2019 Witsarut (Bell)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMCompSetConAddUpdateMasterUrlUpdate($paDataUrlObj,$paOldKeyUrl){
        try{
            $tUrlType  =  $paDataUrlObj['FNUrlType'];


            if($tUrlType == '9'|| $tUrlType == '10' || $tUrlType == '11'){
                // วิ่งไปลบ 
                $this->db->where('FTUrlAddress' , $paOldKeyUrl);
                $this->db->delete('TCNTUrlObjectLogin');

                $this->db->set('FNUrlType'    , $paDataUrlObj['FNUrlType']);
                $this->db->set('FTUrlTable'   , $paDataUrlObj['FTUrlTable']);
                $this->db->set('FTUrlKey'     , $paDataUrlObj['FTUrlKey']);
                $this->db->set('FTUrlAddress' , $paDataUrlObj['FTUrlAddress']);
                $this->db->set('FTUrlPort'    , $paDataUrlObj['FTUrlPort']);
                $this->db->set('FTUrlLogo'    , $paDataUrlObj['FTUrlLogo']);
                $this->db->where('FNUrlID'    , $paDataUrlObj['FNUrlID']);
                $this->db->where('FTUrlAddress' , $paOldKeyUrl);
                $this->db->update('TCNTUrlObject');
           } 

           if($this->db->affected_rows() > 0){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Success',
                );
           }else{
                // Error
                $aStatus = array(
                    'rtCode' => '905',
                    'rtDesc' => 'Error Cannot Add/Edit Master.',
                );
           }
            $jStatus = json_encode($aStatus);
            $aStatus = json_decode($jStatus, true);
            return $aStatus;
        }catch(Exception $Error){
            echo $Error;
        }

    }


    //Functionality : Update Seq Number In Table TCNTUrlObject
    //Parameters : function parameters
    //Creator : 07/10/2019 Witsarut (Bell)
    //Return : data
    //Return Type : Array
    public function FSaMCompSetConUpdateSeqNumber(){
        $tSessionUserEdit = $this->session->userdata('tSesUsername');
        $tSQL = " UPDATE TBLUPD
                SET
                TBLUPD.FNUrlSeq         = TBLSEQ.nRowID,
                TBLUPD.FDLastUpdOn      = CONVERT(VARCHAR(19),GETDATE(),121),
                TBLUPD.FTLastUpdBy      = '$tSessionUserEdit'
            FROM TCNTUrlObject TBLUPD WITH(NOLOCK)
            INNER JOIN (
                SELECT 
                ROW_NUMBER() OVER (PARTITION BY FTUrlRefID ORDER BY FTUrlRefID) nRowID , *
                FROM TCNTUrlObject WITH(NOLOCK)
            ) AS TBLSEQ 
            ON 1=1
            AND TBLUPD.FNUrlID      = TBLSEQ.FNUrlID
            AND TBLUPD.FTUrlRefID   = TBLSEQ.FTUrlRefID
            AND TBLUPD.FNUrlSeq     = TBLSEQ.FNUrlSeq
        ";
        return $this->db->query($tSQL); 
    }

    //Functionality : Get all row 
    //Parameters : -
    //Creator : 19/09/2019 Witsarut (Bell)
    //Return : array result from db
    //Return Type : array
    public function FSnMCompSettingConGetAllNumRow(){
        $tSQL = "SELECT COUNT(*) AS FNAllNumRow FROM TCNTUrlObject";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aResult = $oQuery->row_array()["FNAllNumRow"];
        }else{
            $aResult = false;
        }
        return $aResult;
    }

    //ลบข้อมูลตารางหลักออก เพราะ ว่าเปลี่ยน type มันจะวิ่งไปเข้า insert อีกรอบ
     public function FSaMCompRemoveDataBecauseChangeType($ptOldType){
        $this->db->where_in('FTUrlAddress',$ptOldType);
        $this->db->delete('TCNTUrlObject');

        $this->db->where_in('FTUrlAddress',$ptOldType);
        $this->db->delete('TCNTUrlObjectLogin');
     }

    //Functionality : Delete Setting Connection 
    //Parameters : function parameters
    //Creator : 19/09/2019 Witsarut (Bell)
    //Return : response
    //Return Type : array
     public function FSnMCompSettingConDel($paData){
        $tUrlType  =   $paData['FNUrlType'];

        switch($tUrlType){
            case '9':  // type 1 URL
                $this->db->where_in('FNUrlID',$paData['FNUrlID']);
                $this->db->delete('TCNTUrlObject');
            break;
            case '10' : // Type 10 URL+Author
                $this->db->where_in('FNUrlID',$paData['FNUrlID']);
                $this->db->delete('TCNTUrlObject');
            break;
            case '11' : // Type 11
                $this->db->where_in('FNUrlID',$paData['FNUrlID']);
                $this->db->delete('TCNTUrlObject');
            break;
        }
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


    //Functionality : Delete Mutiple Object
    //Parameters : function parameters
    //Creator : 19/09/2019 Witsarut
    //Return : data
    //Return Type : Arra
    function FSaMCompSetingConnDeleteMultiple($paData){

        $this->db->where_in('FNUrlID',$paData['FNUrlID']);
        $this->db->delete('TCNTUrlObject');

        $this->db->where_in('FTUrlAddress',$paData['FTUrlAddress']);
        $this->db->delete('TCNTUrlObjectLogin');

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

    //Functionality : Get PathUrl
    //Parameters : -
    //Creator : 19/09/2019 Witsarut (Bell)
    //Return : array result from db
    //Return Type : array
    public function FSaMCompSetConAddUpdatePathUrl($paDataUrlObj){
        $tUrlSeq = $paDataUrlObj['FNUrlSeq'];
        $tSQL = "UPDATE  TCNTUrlObject
                    SET TCNTUrlObject.FTUrlLogo = TCNMImgObj.FTImgObj
                FROM  TCNTUrlObject
                INNER JOIN  TCNMImgObj ON TCNTUrlObject.FNUrlSeq = TCNMImgObj.FTImgRefID
                WHERE TCNTUrlObject.FNUrlSeq = '$tUrlSeq' AND TCNMImgObj.FTImgTable = 'TCNTUrlObject'
                ";
        $oQuery = $this->db->query($tSQL);
    }




 }


