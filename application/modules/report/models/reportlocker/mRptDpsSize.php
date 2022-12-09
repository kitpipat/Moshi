<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptDpsSize extends CI_Model {

    // Functionality: Count Data Report All
    // Parameters: Function Parameter
    // Creator: 22/08/2019 Wasin(Yoshi)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSnMCountRowInTemp($paDataWhere) {
        $tCompName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tSessionID = $paDataWhere['tUserSessionID'];
        $tSQL = "   SELECT
                                COUNT(Pos.FTPosCode) AS rnCountPage
                            FROM TRPTRTSalBySizeTmp Pos WITH(NOLOCK)
                            WHERE 1=1
                            AND Pos.FTComName    = '$tCompName'
                            AND Pos.FTRptCode    = '$tRptCode'
                            AND Pos.FTUsrSession = '$tSessionID'
        ";
        $oQuery = $this->db->query($tSQL);
        $nCountData = $oQuery->row_array()['rnCountPage'];
        unset($tCompName);
        unset($tRptCode);
        unset($tSessionID);
        unset($tSQL);
        unset($oQuery);
        return $nCountData;
    }

    // Functonality: Get Data Report
    // Parameters:  Function Parameter
    // Creator: 22/08/2019 Wasin(Yoshi)
    // Last Modified : -
    // Return : Get Data Rpt Temp
    // Return Type: Array
    public function FSaMGetDataReport($paDataWhere) {
        $nPage = $paDataWhere['nPage'];
        // Call Data Pagination
        $aPagination = $this->FMaMRPTPagination($paDataWhere);
        $nRowIDStart = $aPagination["nRowIDStart"];
        $nRowIDEnd = $aPagination["nRowIDEnd"];
        $nTotalPage = $aPagination["nTotalPage"];
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUserSessionID'];

        // Set Priority
        $this->FMxMRPTSetPriorityGroup($paDataWhere);

        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        $tSQL = "   SELECT L.*
                        FROM (
                            SELECT
                                ROW_NUMBER() OVER(ORDER BY A.FTShpCode,A.FTPosCode)  AS RowID,
                                A.*,
                                S.ShpCode_Net,
                                S.PosCode_Net,
                                S.Qtybill,
                                S.FNRptGroupMember,
                                S.Qtybill_All
                            FROM TRPTRTSalBySizeTmp A WITH(NOLOCK)
                            LEFT JOIN (
                                SELECT
                                    FTShpCode           AS ShpCode_Net,
                                    FTPosCode           AS PosCode_Net,
                                    SUM(FTXhdQty)       AS Qtybill_All,
                                    COUNT(FTXhdQty)     AS Qtybill,
                                    COUNT(FTShpCode)    AS FNRptGroupMember
                                FROM TRPTRTSalBySizeTmp WITH(NOLOCK)
                                WHERE 1=1
                                AND FTComName       = '$tComName'
                                AND FTRptCode       = '$tRptCode'
                                AND FTUsrSession    = '$tUsrSession'
                                GROUP BY FTShpCode,FTPosCode
                            ) S ON A.FTShpCode = S.ShpCode_Net AND A.FTPosCode = S.PosCode_Net
                            WHERE 1=1
                            AND A.FTComName     = '$tComName'
                            AND A.FTRptCode     = '$tRptCode'
                            AND A.FTUsrSession  = '$tUsrSession'
                        ) L
        ";
        $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        $tSQL .= " ORDER BY L.FTShpCode, L.FTPosCode ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $aData = $oQuery->result_array();
        } else {
            $aData = NULL;
        }

        $aErrorList = array(
            "nErrInvalidPage" => ""
        );

        $aResualt = array(
            "aPagination" => $aPagination,
            "aRptData" => $aData,
            "aError" => $aErrorList
        );
        unset($nPage);
        unset($aPagination);
        unset($nRowIDStart);
        unset($nRowIDEnd);
        unset($nTotalPage);
        unset($tComName);
        unset($tRptCode);
        unset($tUsrSession);
        unset($tRptJoinFooter);
        unset($tSQL);
        unset($oQuery);
        unset($aData);
        unset($aErrorList);
        return $aResualt;
    }

    // Functionality: Calurate Pagination
    // Parameters:  Function Parameter
    // Creator: 22/08/2019 Wasin(Yoshi)
    // Last Modified : - 
    // Return : Array Data Pagination
    // Return Type: Array
    public function FMaMRPTPagination($paDataWhere) {
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUserSessionID'];
        $tSQL = "   SELECT
                                    COUNT(Pos.FTPosCode) AS rnCountPage
                                FROM TRPTRTSalBySizeTmp Pos WITH(NOLOCK)
                                WHERE 1=1
                                AND Pos.FTComName    = '$tComName'
                                AND Pos.FTRptCode    = '$tRptCode'
                                AND Pos.FTUsrSession = '$tUsrSession'
        ";
        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
        $nPage = $paDataWhere['nPage'];
        $nPerPage = $paDataWhere['nPerPage'];
        $nPrevPage = $nPage - 1;
        $nNextPage = $nPage + 1;
        $nRowIDStart = (($nPerPage * $nPage) - $nPerPage); //RowId Start
        if ($nRptAllRecord <= $nPerPage) {
            $nTotalPage = 1;
        } else if (($nRptAllRecord % $nPerPage) == 0) {
            $nTotalPage = ($nRptAllRecord / $nPerPage);
        } else {
            $nTotalPage = ($nRptAllRecord / $nPerPage) + 1;
            $nTotalPage = (int) $nTotalPage;
        }

        // get rowid end
        $nRowIDEnd = $nPerPage * $nPage;
        if ($nRowIDEnd > $nRptAllRecord) {
            $nRowIDEnd = $nRptAllRecord;
        }

        $aRptMemberDet = array(
            "nTotalRecord" => $nRptAllRecord,
            "nTotalPage" => $nTotalPage,
            "nDisplayPage" => $paDataWhere['nPage'],
            "nRowIDStart" => $nRowIDStart,
            "nRowIDEnd" => $nRowIDEnd,
            "nPrevPage" => $nPrevPage,
            "nNextPage" => $nNextPage
        );
        unset($tComName);
        unset($tRptCode);
        unset($tUsrSession);
        unset($tSQL);
        unset($oQuery);
        unset($nRptAllRecord);
        unset($nPage);
        unset($nPerPage);
        unset($nPrevPage);
        unset($nNextPage);
        unset($nRowIDStart);
        unset($nTotalPage);
        unset($nRowIDEnd);
        return $aRptMemberDet;
    }

    // Functionality: Set PriorityGroup
    // Parameters:  Function Parameter
    // Creator: 22/08/2019 Wasin(Yoshi)
    // Last Modified : - 
    // Return : Array Data Set Priority Group
    // Return Type: Array
    public function FMxMRPTSetPriorityGroup($paDataWhere) {
        
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUserSessionID'];
        
        $tSQL = "
            UPDATE TRPTRTSalBySizeTmp 
		SET FNRowPartID = B.PartID
            FROM( 
                SELECT 
                    ROW_NUMBER() OVER(PARTITION BY FTShpCode,FTPosCode ORDER BY FTShpCode,FTPosCode ASC) AS PartID, 
                    FTRptRowSeq  
                FROM TRPTRTSalBySizeTmp TMP WITH(NOLOCK)
                WHERE TMP.FTComName = '$tComName' 
                AND TMP.FTRptCode = '$tRptCode'
                AND TMP.FTUsrSession = '$tUsrSession' 
            ) AS B
            WHERE TRPTRTSalBySizeTmp.FTRptRowSeq = B.FTRptRowSeq 
            AND TRPTRTSalBySizeTmp.FTComName = '$tComName' 
            AND TRPTRTSalBySizeTmp.FTRptCode = '$tRptCode'
            AND TRPTRTSalBySizeTmp.FTUsrSession = '$tUsrSession'
        ";
        $this->db->query($tSQL);
        
        unset($tComName);
        unset($tRptCode);
        unset($tUsrSession);
        unset($tSQL);
        
    }

}



