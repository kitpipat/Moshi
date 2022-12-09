<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptInventoryPos extends CI_Model {

    /**
     * Functionality: Set PriorityGroup
     * Parameters:  Function Parameter
     * Creator: 23/07/2019 Piya
     * Last Modified : -
     * Return : -
     * Return Type: -
     */
    public function FMxMRPTSetPriorityGroup($ptComName, $ptRptCode, $ptUsrSession) {

        $tSQL = "
            UPDATE TRPTPdtStkBalTmp SET 
                FNRowPartID = B.PartID
            FROM( 
                SELECT 
                    ROW_NUMBER() OVER(PARTITION BY FTWahCode ORDER BY FTWahCode DESC) AS PartID, 
                    FTRptRowSeq 
                FROM TRPTPdtStkBalTmp TMP WITH(NOLOCK)
                WHERE TMP.FTComName = '$ptComName' 
                AND TMP.FTRptCode = '$ptRptCode'
                AND TMP.FTUsrSession = '$ptUsrSession'
            ) AS B
            WHERE TRPTPdtStkBalTmp.FTRptRowSeq = B.FTRptRowSeq 
            AND TRPTPdtStkBalTmp.FTComName = '$ptComName' 
            AND TRPTPdtStkBalTmp.FTRptCode = '$ptRptCode'
            AND TRPTPdtStkBalTmp.FTUsrSession = '$ptUsrSession'
        ";
        
        $this->db->query($tSQL);
        
        unset($ptComName);
        unset($ptRptCode);
        unset($ptUsrSession);
        unset($tSQL);
        
    }

    /**
     * Functionality: Calurate Pagination
     * Parameters:  Function Parameter
     * Creator: 23/07/2019 Piya
     * Last Modified : -
     * Return : Pagination
     * Return Type: Array
     */
    public function FMaMRPTPagination($paDataWhere) {
        
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUserSessionID'];

        $tSQL = "   
            SELECT
                COUNT(TT_TMP.FTWahCode) AS rnCountPage
            FROM TRPTPdtStkBalTmp TT_TMP WITH(NOLOCK)
            WHERE 1=1
            AND TT_TMP.FTComName = '$tComName'
            AND TT_TMP.FTRptCode = '$tRptCode'
            AND TT_TMP.FTUsrSession = '$tUsrSession'
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

    /**
     * Functionality: Get Data Report in Temp
     * Parameters:  Function Parameter
     * Creator: 23/07/2019 Piya
     * Last Modified : -
     * Return : Data Rpt Temp
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
        $tUsrSession = $paDataWhere['tUserSessionID'];
        
        //Set Priority
        $this->FMxMRPTSetPriorityGroup($tComName, $tRptCode, $tUsrSession);
        
        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา 
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = "   
                SELECT 
                    FTUsrSession AS FTUsrSession_Footer,
                    SUM(FCStkQty) AS FCStkQty_Footer
                FROM TRPTPdtStkBalTmp WITH(NOLOCK)
                WHERE 1=1
                AND FTComName = '$tComName'
                AND FTRptCode = '$tRptCode'
                AND FTUsrSession = '$tUsrSession'
                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum 
            $tJoinFoooter = "   
                SELECT
                    '$tUsrSession' AS FTUsrSession_Footer,
                    '0' AS FCStkQty_Footer
                ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }

        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        $tSQL = "   
            SELECT
                L.*,
                T.FCStkQty_Footer

            FROM (
                SELECT  
                    ROW_NUMBER() OVER(ORDER BY FTWahCode) AS RowID ,
                    A.*,
                    S.FNRptGroupMember,
                    S.FCStkQty_SubTotal

                FROM TRPTPdtStkBalTmp A WITH(NOLOCK)
                /* Calculate Misures */
                LEFT JOIN (
                    SELECT
                        FTWahCode AS FTWahCode_SUM,
                        COUNT(FTWahCode) AS FNRptGroupMember,
                        SUM(FCStkQty) AS FCStkQty_SubTotal

                    FROM TRPTPdtStkBalTmp WITH(NOLOCK)
                    WHERE 1=1
                    AND FTComName = '$tComName'
                    AND FTRptCode = '$tRptCode'
                    AND FTUsrSession = '$tUsrSession'
                    GROUP BY FTWahCode
                ) AS S ON A.FTWahCode = S.FTWahCode_SUM
                WHERE A.FTComName = '$tComName'
                AND A.FTRptCode = '$tRptCode'
                AND A.FTUsrSession = '$tUsrSession'
                /* End Calculate Misures */
            ) AS L 
            LEFT JOIN (
                " . $tJoinFoooter . "
        ";

        // WHERE เงื่อนไข Page
        $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";

        //สั่ง Order by ตามข้อมูลหลัก
        $tSQL .= " ORDER BY L.FTWahCode ";

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

    /**
     * Functionality: Count Row in Temp
     * Parameters:  Function Parameter
     * Creator: 23/07/2019 Piya
     * Last Modified : -
     * Return : Count row
     * Return Type: Number
     */  
    public function FSnMCountRowInTemp($paParams = []) {
        
        $tCompName = $paParams['tCompName'];
        $tRptCode = $paParams['tRptCode'];
        $tUserSession = $paParams['tUserSessionID'];
        
        $tSQL = "   
            SELECT
                COUNT(TT_TMP.FTWahCode) AS rnCountPage
            FROM TRPTPdtStkBalTmp TT_TMP WITH(NOLOCK)
            WHERE 1=1
            AND TT_TMP.FTComName = '$tCompName'
            AND TT_TMP.FTRptCode = '$tRptCode'
            AND TT_TMP.FTUsrSession = '$tUserSession'
        ";
        
        $oQuery = $this->db->query($tSQL);
        return $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
        
    }
    
}





















