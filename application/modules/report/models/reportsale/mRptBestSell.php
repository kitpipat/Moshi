<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mRptBestSell extends CI_Model {

    /**
     * Functionality: Get Data Report in Temp
     * Parameters:  Function Parameter
     * Creator: 23/07/2019 Piya
     * Last Modified : -
     * Return : Data Rpt Temp
     * Return Type: Array
     */
    public function FSaMGetDataReport($paDataWhere){
        
        $aRowLen = FCNaHCallLenData($paDataWhere['nPerPage'], $paDataWhere['nPage']);
        
        $tUserSessionID = $paDataWhere['tUserSessionID'];
        $tCompName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];

        $tSQL = "
            SELECT C.*
                FROM (
                    SELECT 
                        ROW_NUMBER() OVER(ORDER BY DATAGRP.FTPdtCode ASC, DATAGRP.FCXsdQty DESC) AS rtRowID,
                        DATAGRP.*
                    FROM(
                        SELECT
                            FTPdtCode,
                            FTXsdPdtName,
                            FTPgpChainName,
                            COUNT(FCXsdQty) AS FCXsdQty,
                            SUM(FCXsdAmtB4DisChg) AS FCXsdDigChg,
                            SUM(FCXsdDis) AS FCXsdDis,
                            SUM(FCXsdNetAfHD) AS FCXsdNetAfHD
                        FROM TRPTSalDTTmp
                        WHERE FTComName = '" . $tCompName . "'
                        AND FTRptCode = '" . $tRptCode . "'
                        AND FTUsrSession = '" . $tUserSessionID . "'
                        GROUP BY FTPdtCode,FTXsdPdtName,FTPgpChainName
                    ) AS DATAGRP 
                ) AS C
                WHERE C.rtRowID > $aRowLen[0] AND C.rtRowID <= $aRowLen[1]
        ";
        
        $oQuery = $this->db->query($tSQL);
        
        if($oQuery->num_rows() > 0){

            $aDataRpt = $oQuery->result_array();
            $nFoundRow = $this->FSnMCountRowInTemp($paDataWhere);
            $nPageAll = ceil($nFoundRow / $paDataWhere['nPerPage']);
            $aReturnData = array(
                'aRptData' => $aDataRpt,
                'rnAllRow' => $nFoundRow,
                'rnCurrentPage' => $paDataWhere['nPage'],
                'rnAllPage' => $nPageAll,
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
            
        }else{
            $aReturnData = array(
                'rnAllRow' => 0,
                'rnCurrentPage' => $paDataWhere['nPage'],
                "rnAllPage" => 0,
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        unset($oQuery);
        unset($nFoundRow);
        unset($nPageAll);
        return $aReturnData;
    }

    /**
     * Functionality: Count Row in Temp
     * Parameters:  Function Parameter
     * Creator: 23/07/2019 Piya
     * Last Modified : -
     * Return : Count row
     * Return Type: Number
     */
    public function FSnMCountRowInTemp($paParams){
        
        $tUserSessionID = $paParams['tUserSessionID'];
        $tCompName  = $paParams['tCompName'];
        $tRptCode   = $paParams['tRptCode'];
        
        $tSQL = "
            SELECT
                FTPdtCode,
                FTXsdPdtName,
                FTPgpChainName,
                COUNT(FCXsdQty) AS FCXsdQty,
                SUM(FCXsdAmtB4DisChg) AS FCXsdDigChg,
                SUM(FCXsdDis) AS FCXsdDis,
                SUM(FCXsdNetAfHD) AS FCXsdNetAfHD
            FROM TRPTSalDTTmp
            WHERE FTComName = '$tCompName'
            AND FTRptCode = '$tRptCode'
            AND FTUsrSession = '$tUserSessionID'
            GROUP BY FTPdtCode, FTXsdPdtName, FTPgpChainName
        ";

        $oQuery = $this->db->query($tSQL);
        $nCountData = $oQuery->num_rows();
        unset($oQuery);
        return $nCountData;
    }
    
    /**
     * Functionality: Get data SumFootReport
     * Parameters:  Function Parameter
     * Creator: 23/07/2019 Piya
     * Last Modified : -
     * Return : Data Rpt Sum Footer Temp
     * Return Type: Array
     */
    public function FSaMGetDataSumFootReport($paDataWhere){
        
        $tUserSessionID = $paDataWhere['tUserSessionID'];
        $tCompName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];

        $tSQL = "  
            SELECT
                ISNULL(COUNT(FCXsdQty),0) AS FCXsdSumQty,
                ISNULL(SUM(FCXsdAmtB4DisChg),0) AS FCXsdSumDigChg,
                ISNULL(SUM(FCXsdDis),0)	AS FCXsdSumDis,
                ISNULL(SUM(FCXsdNetAfHD),0) AS FCSumFooter
            FROM TRPTSalDTTmp
            WHERE FTUsrSession = '$tUserSessionID'
            AND FTComName = '$tCompName'
            AND FTRptCode = '$tRptCode'
        ";            

        $oQuery = $this->db->query($tSQL);
        
        if($oQuery->num_rows() > 0){
            return $oQuery->row_array();
        }else{
            return array();
        }
    }

}





































