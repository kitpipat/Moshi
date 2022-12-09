<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptDpsSize extends CI_Model {

    /**
     * Functionality: Delete Temp Report
     * Parameters:  Function Parameter
     * Creator: 10/07/2019 Saharat(Golf)
     * Last Modified :
     * Return : Call Store Proce
     * Return Type: Array
     */
    public function FSnMExecStoreReport($paDataFilter) {
        
        // $tCallStore = "{ CALL SP_RPTxRentalBySize3002003(?,?,?,?,?,?,?,?,?,?,?,?,?) }";
        // $aDataStore = array(
        //     'pnLngID' => $paDataFilter['nLangID'],
        //     'pnComName' => $paDataFilter['tCompName'],
        //     'ptRptCode' => $paDataFilter['tRptCode'],
        //     'ptUsrSession' => $paDataFilter['tUserSession'],
        //     'ptBchF' => $paDataFilter['tBchCodeFrom'],
        //     'ptBchT' => $paDataFilter['tBchCodeTo'],
        //     'ptShpF' => $paDataFilter['tShopCodeFrom'],
        //     'ptShpT' => $paDataFilter['tShopCodeTo'],
        //     'ptPosCodeF' => $paDataFilter['tPosCodeFrom'],
        //     'ptPosCodeT' => $paDataFilter['tPosCodeTo'],
        //     'ptDocDateF' => $paDataFilter['tDocDateFrom'],
        //     'ptDocDateT' => $paDataFilter['tDocDateTo'],
        //     'FNResult' => 0
        // );
        // $oQuery = $this->db->query($tCallStore, $aDataStore);
        // if ($oQuery != FALSE) {
        //     unset($oQuery);
        //     return 1;
        // } else {
        //     unset($oQuery);
        //     return 0;
        // }
        return 1;
    }

    /**
     * Functionality: Get Data Report
     * Parameters:  Function Parameter
     * Creator: 10/07/2019 Saharat(Golf)
     * Last Modified : 
     * Return : Get Data Rpt Temp
     * Return Type: Array
     */
    public function FSaMGetDataReport($paDataWhere) {

        $nPage = $paDataWhere['nPage'];
        // Call Data Pagination 
        $aPagination = $this->FMaMRPTPagination($paDataWhere);

        $nRowIDStart = $aPagination["nRowIDStart"];
        $nRowIDEnd = $aPagination["nRowIDEnd"];
        $nTotalPage = $aPagination["nTotalPage"];

        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tSession = $paDataWhere['tUsrSessionID'];

        // Set Priority
        $this->FMxMRPTSetPriorityGroup($tComName, $tRptCode, $tSession);
        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary    
        $tSQL = "  
            SELECT 
                L.*
            FROM (
                SELECT 
                    ROW_NUMBER() OVER(ORDER BY A.FTShpCode,A.FTPosCode) AS RowID, 
                    A.*,
                    S.ShpCode_Net,
                    S.PosCode_Net,
                    S.Qtybill,
                    S.FNRptGroupMember,
                    S.Qtybill_All    
                FROM TRPTRTSalBySizeTmp A
                LEFT JOIN (
                    SELECT 
                        FTShpCode AS ShpCode_Net,
                        FTPosCode AS PosCode_Net,
                        SUM(FTXhdQty) AS Qtybill_All,
                        COUNT(FTXhdQty) AS Qtybill,
                        COUNT(FTShpCode) AS FNRptGroupMember
                    FROM TRPTRTSalBySizeTmp
                    WHERE FTComName = '$tComName'
                    AND   FTRptCode = '$tRptCode'
                    AND   FTUsrSession = '$tSession'
                    GROUP BY FTShpCode, FTPosCode
                ) S ON A.FTShpCode = S.ShpCode_Net AND A.FTPosCode = S.PosCode_Net
                WHERE A.FTComName = '$tComName'
                AND A.FTRptCode = '$tRptCode'
                AND A.FTUsrSession = '$tSession'
            ) L
        ";

        // WHERE เงื่อนไข Page
        $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        // สั่ง Order by ตามข้อมูลหลัก
        $tSQL .= " ORDER BY L.FTShpCode, L.FTPosCode ";
        
        // echo $tSQL;
        // exit;

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
        unset($oQuery);
        unset($aData);
        return $aResualt;
    }

    // Functionality: Count Data Report All
    // Parameters: Function Parameter
    // Creator: 14/08/2019 Witsarut (Bell)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSaMCountDataReportAll($paDataWhere) {
        $tSessionID = $paDataWhere['tSessionID'];
        $tCompName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];

        $tSQL = " 
            SELECT 
                *
            FROM TRPTRTSalBySizeTmp 
            WHERE 1=1
            AND FTUsrSession = '$tSessionID' 
            AND FTComName = '$tCompName' 
            AND FTRptCode = '$tRptCode'
        ";
        
        $oQuery = $this->db->query($tSQL);
        
        $nCountData = $oQuery->num_rows();
        unset($oQuery);
        return $nCountData;
    }

    public function FMaMRPTPagination($paDataWhere) {

        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUsrSessionID'];

        $tSQL = "   
            SELECT
                COUNT(POS.FTPosCode) AS rnCountPage
            FROM TRPTRTSalBySizeTmp POS WITH(NOLOCK)
            WHERE 1=1
            AND POS.FTComName = '$tComName'
            AND POS.FTRptCode = '$tRptCode'
            AND POS.FTUsrSession = '$tUsrSession'
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
        unset($oQuery);
        return $aRptMemberDet;
    }

    public function FMxMRPTSetPriorityGroup($ptComName, $ptRptCode, $ptUsrSession) {

        $tSQL = "
            UPDATE TRPTRTSalBySizeTmp 
		SET FNRowPartID = B.PartID
            FROM( 
                SELECT 
                    ROW_NUMBER() OVER(PARTITION BY FTShpCode,FTPosCode ORDER BY FTShpCode,FTPosCode ASC) AS PartID, 
                    FTRptRowSeq  
                FROM TRPTRTSalBySizeTmp TMP WITH(NOLOCK)
                WHERE TMP.FTComName = '$ptComName' 
                AND TMP.FTRptCode = '$ptRptCode'
                AND TMP.FTUsrSession = '$ptUsrSession' 
            ) AS B
            WHERE TRPTRTSalBySizeTmp.FTRptRowSeq = B.FTRptRowSeq 
            AND TRPTRTSalBySizeTmp.FTComName = '$ptComName' 
            AND TRPTRTSalBySizeTmp.FTRptCode = '$ptRptCode'
            AND TRPTRTSalBySizeTmp.FTUsrSession = '$ptUsrSession'
        ";

        $this->db->query($tSQL);
    }

}






