<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mInterfaceHistory extends CI_Model {


    //Functionality : list Recive
    //Parameters : function parameters
    //Creator :  11/05/2018 Wasin
    //Last Modified : Napat(Jame) 03/04/63
    //Return : data
    //Return Type : Array
    public function FSaMIFHList($paData){

        $aRowLen    = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $tLangEdit  = $this->session->userdata("tLangEdit");

        $tSQL   = "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTInfCode ASC) AS FNRowID,* FROM
                            (SELECT DISTINCT
                                TLK.FTInfCode AS  FTInfCode ,
                                TLK.FDHisDate AS  FDHisDate ,
                                TLK.FTStaDone AS  FTStaDone ,
                                TLK.FDHisTime AS  FDHisTime ,
                                TLK.FTHisDesc AS  FTHisDesc ,
                                
                                TLKHD.FTInfTypeDoc AS FTInfType ,
                                TLK.FTInfFile AS  FTInfFile ,
                                
                                LNK_L.FTInfName AS FTInfName

                            FROM [TLKTHistory] TLK WITH(NOLOCK)
                            LEFT JOIN [TSysLnk] TLKHD ON TLK.FTInfCode = TLKHD.FTInfCode 
                            LEFT JOIN [TSysLnk_L] LNK_L ON TLKHD.FTInfCode = LNK_L.FTInfCode AND LNK_L.FNLngID = $tLangEdit
                            WHERE 1=1 ";

                            
        $tSearchList = $paData['aPackDataSearch']['tIFHSearchAll'];
        if(!empty($tSearchList)){
            $tSQL .= " AND (LNK_L.FTInfName LIKE '%$tSearchList%'";
            $tSQL .= " OR  TLKHD.FTInfCode LIKE '%$tSearchList%')";        
        }

        $tStatusIFH = $paData['aPackDataSearch']['tIFHStatus'];
        if(!empty($tStatusIFH)){
            $tSQL .= " AND TLK.FTStaDone = '$tStatusIFH' ";
        }
        
        $nIFHType =  $paData['aPackDataSearch']['tIFHType'];
        if(!empty($nIFHType)){
            $tSQL .= " AND TLKHD.FTInfTypeDoc = '$nIFHType' ";
        }

        $tIFHInfCode = $paData['aPackDataSearch']['tIFHInfCode'];
        if(!empty($tIFHInfCode)){
            $tSQL .= " AND TLKHD.FTInfCode = '$tIFHInfCode' ";
        }

        $tIFHDateFrom   = $paData['aPackDataSearch']['tIFHDateFrom'];
        $tIFHDateTo     = $paData['aPackDataSearch']['tIFHDateTo'];
        if(!empty($tIFHDateFrom) && !empty($tIFHDateTo)){
            $tSQL .= " AND ((TLK.FDHisDate BETWEEN CONVERT(datetime,'$tIFHDateFrom 00:00:00') AND CONVERT(datetime,'$tIFHDateTo 23:59:59')) ";
            $tSQL .= " OR (TLK.FDHisDate BETWEEN CONVERT(datetime,'$tIFHDateTo 23:00:00') AND CONVERT(datetime,'$tIFHDateFrom 00:00:00'))) ";
        }

        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1] ";

        // echo $tSQL ;
        // exit;
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aFoundRow  = $this->FSnMIFHGetPageAll($paData);
            $nFoundRow  = $aFoundRow[0]->counts;
            $nPageAll   = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
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

    //Functionality : All Page Of Recive
    //Parameters : function parameters
    //Creator :  11/05/2018 Wasin
    //Last Modified : Napat(Jame) 03/04/63
    //Return : data
    //Return Type : Array
    public function FSnMIFHGetPageAll($paData){
        $tLangEdit                   = $this->session->userdata("tLangEdit");
        $tSQL = "SELECT COUNT (TLK.FTInfCode) AS counts
                 FROM TLKTHistory TLK WITH(NOLOCK)
                 LEFT JOIN [TSysLnk] TLKHD ON TLK.FTInfCode = TLKHD.FTInfCode 
                 LEFT JOIN [TSysLnk_L] LNK_L ON TLKHD.FTInfCode = LNK_L.FTInfCode AND LNK_L.FNLngID = $tLangEdit
                 WHERE 1=1 ";
        
        $tSearchList = $paData['aPackDataSearch']['tIFHSearchAll'];
        if(!empty($tSearchList)){
            $tSQL .= " AND (LNK_L.FTInfName LIKE '%$tSearchList%'";
            $tSQL .= " OR  TLKHD.FTInfCode LIKE '%$tSearchList%')";        
        }

        $tStatusIFH = $paData['aPackDataSearch']['tIFHStatus'];
        if(!empty($tStatusIFH)){
            $tSQL .= " AND TLK.FTStaDone = '$tStatusIFH' ";
        }
        
        $nIFHType =  $paData['aPackDataSearch']['tIFHType'];
        if(!empty($nIFHType)){
            $tSQL .= " AND TLKHD.FTInfTypeDoc = '$nIFHType' ";
        }

        $tIFHInfCode = $paData['aPackDataSearch']['tIFHInfCode'];
        if(!empty($tIFHInfCode)){
            $tSQL .= " AND TLKHD.FTInfCode = '$tIFHInfCode' ";
        }

        $tIFHDateFrom   = $paData['aPackDataSearch']['tIFHDateFrom'];
        $tIFHDateTo     = $paData['aPackDataSearch']['tIFHDateTo'];
        if(!empty($tIFHDateFrom) && !empty($tIFHDateTo)){
            $tSQL .= " AND ((TLK.FDHisDate BETWEEN CONVERT(datetime,'$tIFHDateFrom 00:00:00') AND CONVERT(datetime,'$tIFHDateTo 23:59:59')) ";
            $tSQL .= " OR (TLK.FDHisDate BETWEEN CONVERT(datetime,'$tIFHDateTo 23:00:00') AND CONVERT(datetime,'$tIFHDateFrom 00:00:00'))) ";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            //No Data
            return false;
        }
    }

    //Functionality : Get All Data From Table [TSysLnk]
    //Parameters : lang
    //Creator :  30/03/2020 Napat(Jame)
    //Last Modified : -
    //Return : data
    //Return Type : Array
    public function FSaMIFHGetLnkAll(){
        $tSQL = "   SELECT 
                        LNK.FTInfCode,
                        LNK_L.FTInfName,
                        LNK.FTInfTypeDoc
                    FROM TSysLnk LNK WITH(NOLOCK) 
                    LEFT JOIN TSysLnk_L LNK_L ON LNK.FTInfCode = LNK_L.FTInfCode AND LNK_L.FNLngID = 1
                    WHERE 1=1 
                    AND LNK.FTInfStaUse = '1'
                    AND ISNULL(LNK_L.FTInfName,'') != ''
                    ORDER BY LNK_L.FTInfName ASC
                ";
        $oQuery = $this->db->query($tSQL);
        return $oQuery->result_array();
        
    }



}


 
  