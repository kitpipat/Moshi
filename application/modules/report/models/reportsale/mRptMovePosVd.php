<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptMovePosVd extends CI_Model {

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
        $tSession = $paDataWhere['tUserSessionID'];

        //Set Priority
        $this->FMxMRPTSetPriorityGroup($tComName, $tRptCode, $tSession);
        $this->FMxMRPTAjdStkBal($tComName, $tRptCode, $tSession);

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = "   
                SELECT
                    FTUsrSession  AS FTUsrSession_Footer,
                    CONVERT(FLOAT,SUM(FCStkQtyMonEnd)) AS FCStkQtyMonEnd_Footer,
                    CONVERT(FLOAT,SUM(FCStkQtyIn)) AS FCStkQtyIn_Footer,
                    CONVERT(FLOAT,SUM(FCStkQtyOut)) AS FCStkQtyOut_Footer,
                    CONVERT(FLOAT,SUM(FCStkQtySaleDN - FCStkQtyCN)) AS FCStkQtySale_Footer,
                    CONVERT(FLOAT,SUM(FCStkQtyAdj)) AS FCStkQtyAdj_Footer,
                    CONVERT(FLOAT,SUM(FCStkQtyBal)) AS FCStkQtyBal_Footer

                FROM TRPTPdtStkCrdTmp WITH(NOLOCK)
                WHERE 1=1
                AND FTComName = '$tComName'
                AND FTRptCode = '$tRptCode'
                AND FTUsrSession = '$tSession'
                GROUP BY FTUsrSession
                ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
            $tJoinFoooter = "   
                 SELECT
                    '$tSession' AS FTUsrSession_Footer,
                    '0' AS FCStkQtyMonEnd_Footer,
                    '0' AS FCStkQtyIn_Footer,
                    '0' AS FCStkQtyOut_Footer,
                    '0' AS FCStkQtySale_Footer,
                    '0' AS FCStkQtyAdj_Footer,
                    '0' AS FCStkQtyBal_Footer
                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }

        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        $tSQL = "   
            SELECT
                L.*,
                T.FCStkQtyMonEnd_Footer,
                T.FCStkQtyIn_Footer,
                T.FCStkQtyOut_Footer,
                T.FCStkQtySale_Footer,
                T.FCStkQtyAdj_Footer,
                T.FCStkQtyBal_Footer
            FROM (
                SELECT
                    ROW_NUMBER() OVER(ORDER BY FTWahCode ASC,FTPdtCode ASC, FTRptRowSeq ASC) AS RowID, 
                    A.*,
                    S.FTWahCode_SUB,
                    S.FNRptGroupMember,
                    S.FCStkQtyMonEnd_SUB,
                    S.FCStkQtyIn_SUB,
                    S.FCStkQtyOut_SUB,
                    S.FCStkQtySale_SUB,
                    S.FCStkQtyAdj_SUB,
                    S.FCStkQtyBal_SUB
                FROM TRPTPdtStkCrdTmp A WITH(NOLOCK)
                /* Calculate Misures */
                LEFT JOIN (
                    SELECT
                        FTWahCode AS FTWahCode_SUB,
                        FTPdtCode AS FTPdtCode_SUB,
                        COUNT(FTWahCode) AS FNRptGroupMember,
                        CONVERT(FLOAT,SUM(FCStkQtyMonEnd)) AS FCStkQtyMonEnd_SUB,
                        CONVERT(FLOAT,SUM(FCStkQtyIn)) AS FCStkQtyIn_SUB,
                        CONVERT(FLOAT,SUM(FCStkQtyOut)) AS FCStkQtyOut_SUB,
                        CONVERT(FLOAT,SUM(FCStkQtySaleDN - FCStkQtyCN)) AS FCStkQtySale_SUB,
                        CONVERT(FLOAT,SUM(FCStkQtyAdj)) AS FCStkQtyAdj_SUB,
                        CONVERT(FLOAT,SUM(FCStkQtyBal)) AS FCStkQtyBal_SUB

                    FROM TRPTPdtStkCrdTmp WITH(NOLOCK)
                    WHERE 1=1 
                    AND FTComName = '$tComName' 
                    AND FTRptCode = '$tRptCode' 
                    AND FTUsrSession = '$tSession'
                    GROUP BY FTWahCode,FTPdtCode
                ) S ON 1=1 AND A.FTWahCode = S.FTWahCode_SUB AND A.FTPdtCode = S.FTPdtCode_SUB
                WHERE 1=1
                AND A.FTComName = '$tComName'
                AND A.FTRptCode = '$tRptCode'
                AND A.FTUsrSession = '$tSession'
                /* End Calculate Misures */
            ) AS L
            LEFT JOIN (
                " . $tJoinFoooter . "
        ";
        // WHERE เงื่อนไข Page
        $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";

        // สั่ง Order by ตามข้อมูลหลัก
        $tSQL .= " ORDER BY L.FTWahCode ,L.FTPdtCode,L.FTRptRowSeq ,L.FNRowPartID";

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
                COUNT(FTWahCode) AS rnCountPage
            FROM TRPTPdtStkCrdTmp  
            WHERE 1=1 
            AND FTUsrSession = '$tUserSession' 
            AND FTComName = '$tCompName' 
            AND FTRptCode = '$tRptCode'
        ";
        $oQuery = $this->db->query($tSQL);

        $nCountData = $oQuery->row_array()['rnCountPage'];
        unset($oQuery);
        return $nCountData;
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
                COUNT(STK.FTWahCode) AS rnCountPage
            FROM TRPTPdtStkCrdTmp STK WITH(NOLOCK)
            WHERE 1=1
            AND STK.FTComName = '$tComName'
            AND STK.FTRptCode = '$tRptCode'
            AND STK.FTUsrSession = '$tUsrSession'
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
     * Functionality: Set PriorityGroup
     * Parameters:  Function Parameter
     * Creator: 23/07/2019 Piya
     * Last Modified : -
     * Return : -
     * Return Type: -
     */
    public function FMxMRPTSetPriorityGroup($ptComName, $ptRptCode, $ptUsrSession) {

        $tSQL = " 
            UPDATE TRPTPdtStkCrdTmp SET 
                FNRowPartID = B.PartID
                FROM(
                    SELECT 
                        ROW_NUMBER() OVER(PARTITION BY FTWahCode,FTPdtCode ORDER BY FTWahCode ASC,FTPdtCode ASC, FTRptRowSeq ASC) AS PartID,
                        FTRptRowSeq
                    FROM TRPTPdtStkCrdTmp TMP WITH(NOLOCK)
                    WHERE TMP.FTComName = '$ptComName' 
                    AND TMP.FTRptCode = '$ptRptCode'
                    AND TMP.FTUsrSession = '$ptUsrSession'
                ) AS B 
            WHERE TRPTPdtStkCrdTmp.FTRptRowSeq = B.FTRptRowSeq
            AND TRPTPdtStkCrdTmp.FTComName = '$ptComName' 
            AND TRPTPdtStkCrdTmp.FTRptCode = '$ptRptCode'
            AND TRPTPdtStkCrdTmp.FTUsrSession = '$ptUsrSession'
        ";
        $this->db->query($tSQL);
        
        unset($ptComName);
        unset($ptRptCode);
        unset($ptUsrSession);
        unset($tSQL);
        
    }

    /**
     * Functionality: Adjust Balance
     * Parameters:  Function Parameter
     * Creator: 23/07/2019 Piya
     * Last Modified : -
     * Return : -
     * Return Type: -
     */
    private function FMxMRPTAjdStkBal($ptComName, $ptRptCode, $ptUsrSession) {

        // Adjust stock balance in temp  
        $tSQL = "
        UPDATE STK SET STK.FCStkQtyBal =  STKAJB.FCStkBal
        FROM TRPTPdtStkCrdTmp STK 
    
        /* join this statement with main state key must refer by : FTWahCode,FTPdtCode,FTStkDocNo */
        LEFT JOIN (


        SELECT STKB.* , 
               /* calculate running total partition by warehouse by products (use this column for show balance) */
               SUM(STKB.FCStkSumTrans) OVER ( PARTITION  BY STKB.FTWahCode+STKB.FTPdtCode ORDER BY STKB.FTRptRowSeq) AS FCStkBal
        FROM (
                SELECT FTRptRowSeq, FTWahCode,FTPdtCode,FTStkDocNo,

                /* get row number for order by sequence because sub query can not use order by */
                ROW_NUMBER() OVER(PARTITION by FTPdtCode ORDER BY FTWahCode,FTPdtCode,FTStkDocNo) AS FNStkRowGroupNo,

                /* calculate stock (all transactions) */
                SUM(FCStkQtyMonEnd + FCStkQtyIn + FCStkQtyOut + FCStkQtyAdj - (FCStkQtySaleDN - FCStkQtyCN) ) AS FCStkSumTrans

                FROM TRPTPdtStkCrdTmp
                WHERE 1 = 1

                /* filter data by user parameter */
                /*AND FTWahCode = '00039'
                AND FTPdtCode = '00009'
                AND FTComName       = 'GOFTNB'
                AND FTRptCode       = '001002002'
                AND FTUsrSession    = '00220190819045142'*/

                AND FTComName       = '$ptComName' 
                AND FTRptCode       = '$ptRptCode'
                AND FTUsrSession    = '$ptUsrSession'

                /* gropping data */ 
                GROUP BY FTRptRowSeq,FTWahCode,FTPdtCode,FTStkDocNo 

             ) STKB ) STKAJB ON STK.FTWahCode = STKAJB.FTWahCode AND STK.FTPdtCode = STKAJB.FTPdtCode AND STK.FTStkDocNo = STKAJB.FTStkDocNo
        ";
        $this->db->query($tSQL);
    }

}



