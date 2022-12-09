<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptInventoryPos extends CI_Model {

    //Functionality: Delete Temp Report
    //Parameters:  Function Parameter
    //Creator: 04/04/2019 Wasin(Yoshi)
    //Last Modified :
    //Return : Call Store Proce
    //Return Type: Array
    public function FSnMExecStoreCReport($paDataFilter) {
        $tCallStore = "{ CALL SP_RPTxStockBal1002001(?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'pnLngID' => $paDataFilter['nLangID'],
            'ptComName' => $paDataFilter['tCompName'],
            'ptRptCode' => $paDataFilter['tRptCode'],
            'ptUsrSession' => $paDataFilter['tUserSession'],
            'ptBchF' => $paDataFilter['tBchCodeFrom'],
            'ptBchT' => $paDataFilter['tBchCodeTo'],
            'ptWahCodeF' => $paDataFilter['tWahCodeFrom'],
            'ptWahCodeT' => $paDataFilter['tWahCodeTo'],
            'FTResult' => 0,
        );
        // echo '<pre>';
        // print_r($aDataStore); exit();

        $oQuery = $this->db->query($tCallStore, $aDataStore);
        if ($oQuery != FALSE) {
            unset($oQuery);
            return 1;
        } else {
            unset($oQuery);
            return 0;
        }
    }

    public function FMxMRPTSetPriorityGroup($ptComName, $ptRptCode, $ptUsrSession) {

        $tSQL = "
            UPDATE TRPTPdtStkBalTmp 
		SET FNRowPartID = B.PartID
		FROM( 
                    SELECT 
                        ROW_NUMBER() OVER(PARTITION BY FTWahCode ORDER BY FTWahCode DESC) AS PartID, 
                        FTRptRowSeq  
                    FROM TRPTPdtStkBalTmp TMP WITH(NOLOCK)
                    WHERE TMP.FTComName = '$ptComName' 
                    AND TMP.FTRptCode = '$ptRptCode'
                    AND TMP.FTUsrSession = '$ptUsrSession'
                ) B
            WHERE TRPTPdtStkBalTmp.FTRptRowSeq = B.FTRptRowSeq 
            AND TRPTPdtStkBalTmp.FTComName     = '$ptComName' 
            AND TRPTPdtStkBalTmp.FTRptCode     = '$ptRptCode'
            AND TRPTPdtStkBalTmp.FTUsrSession  = '$ptUsrSession' ";
        $this->db->query($tSQL);
    }

    public function FMaMRPTPagination($paDataWhere) {
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUsrSessionID'];


        $tSQL = "   SELECT
                                    COUNT(TT_TMP.FTWahCode) AS rnCountPage
                                FROM TRPTPdtStkBalTmp TT_TMP WITH(NOLOCK)
                                WHERE 1=1
                                AND TT_TMP.FTComName    = '$tComName'
                                AND TT_TMP.FTRptCode    = '$tRptCode'
                                AND TT_TMP.FTUsrSession = '$tUsrSession'
                            ";
        // echo '<pre>'.$tSQL; exit();s
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

    // Functionality: Get Data address
    // Parameters: Function Parameter
    // Creator: 11/04/2019 Wasin(Yoshi)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSaMCMPAddress($paData) {

        try {
            $tRefCode = $paData['tAddRef'];
            $nLngID = $paData['nLangID'];
            $tSQL = "SELECT
                        ADDL.FTAddRefCode       AS rtAddRefCode,
                        ADDL.FTAddTaxNo         AS rtAddTaxNo,
                        ADDL.FTAddVersion       AS rtAddVersion,
                        ADDL.FTAddV1No          AS rtAddV1No,
                        ADDL.FTAddV1Soi         AS rtAddV1Soi,
                        ADDL.FTAddV1Village     AS rtAddV1Village,
                        ADDL.FTAddV1Road        AS rtAddV1Road,
                        ADDL.FTAddV1SubDist     AS rtAddV1SubDist,
                        SUBDSTL.FTSudName       AS rtAddV1SudName,
                        ADDL.FTAddV1DstCode     AS rtAddV1DstCode,
                        DSTL.FTDstName          AS rtAddV1DstName,
                        ADDL.FTAddV1PvnCode     AS rtAddV1PvnCode,
                        PVNL.FTPvnName          AS rtAddV1PvnName,
                        ADDL.FTAddCountry       AS rtAddV1CntName,
                        ADDL.FTAddV1PostCode    AS rtAddV1PostCode,
                        ADDL.FTAddV2Desc1       AS rtAddV2Desc1,
                        ADDL.FTAddV2Desc2       AS rtAddV2Desc2,
                        ADDL.FTAddWebsite       AS rtAddWebsite,
                        ADDL.FTAddLongitude     AS rtAddLongitude,
                        ADDL.FTAddLatitude      AS rtAddLatitude

                    FROM [TCNMAddress_L] ADDL
                    LEFT JOIN [TCNMSubDistrict_L] SUBDSTL ON ADDL.FTAddV1SubDist = SUBDSTL.FTSudCode AND SUBDSTL.FNLngID = $nLngID
                    LEFT JOIN [TCNMDistrict_L] DSTL ON ADDL.FTAddV1DstCode = DSTL.FTDstCode AND DSTL.FNLngID = $nLngID
                    LEFT JOIN [TCNMProvince_L] PVNL ON ADDL.FTAddV1PvnCode = PVNL.FTPvnCode AND PVNL.FNLngID = $nLngID
                    WHERE 1=1  AND ADDL.FNLngID = $nLngID AND ADDL.FTAddRefCode = '$tRefCode' 
                    ";

            $oQuery = $this->db->query($tSQL);
            if ($oQuery->num_rows() > 0) {
                $oList = $oQuery->result();
                $aResult = array(
                    'raItems' => $oList[0],
                    'rtCode' => '1',
                    'rtDesc' => 'success',
                );
            } else {
                //No Data
                $aResult = array(
                    'rtCode' => '800',
                    'rtDesc' => 'data not found'
                );
            }
            $jResult = json_encode($aResult);
            $aResult = json_decode($jResult, true);
            return $aResult;
        } catch (Exception $Error) {
            return $Error;
        }
    }

    /**
     * Functionality: Call Stored Procedure
     * Parameters:  Function Parameter
     * Creator: 18/07/2019 Wasin(Yoshi)
     * Last Modified : 24/09/2019 Piya
     * Return : Status Return Call Stored Procedure
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
        $tUsrSession = $paDataWhere['tUsrSessionID'];
        
        // Set Priority
        $this->FMxMRPTSetPriorityGroup($tComName, $tRptCode, $tUsrSession);
        
        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา 
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = "   
                SELECT 
                    FTUsrSession        AS FTUsrSession_Footer,
                    SUM(FCStkQty)       AS FCStkQty_Footer

                FROM TRPTPdtStkBalTmp WITH(NOLOCK)
                WHERE 1=1
                AND FTComName       = '$tComName'
                AND FTRptCode       = '$tRptCode'
                AND FTUsrSession    = '$tUsrSession'
                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum 
            $tJoinFoooter = "   
                SELECT
                    '$tUsrSession'  AS FTUsrSession_Footer,
                    '0'             AS FCStkQty_Footer
                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
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
                        FTWahCode          AS FTWahCode_SUM,
                        COUNT(FTWahCode)   AS FNRptGroupMember,
                        SUM(FCStkQty)      AS FCStkQty_SubTotal
                    FROM TRPTPdtStkBalTmp WITH(NOLOCK)
                    WHERE 1=1
                    AND FTComName       = '$tComName'
                    AND FTRptCode       = '$tRptCode'
                    AND FTUsrSession    = '$tUsrSession'
                    GROUP BY FTWahCode
                ) AS S ON A.FTWahCode = S.FTWahCode_SUM
                WHERE A.FTComName       = '$tComName'
                AND   A.FTRptCode       = '$tRptCode'
                AND   A.FTUsrSession    = '$tUsrSession'
                /* End Calculate Misures */
            ) AS L 
            LEFT JOIN (
            " . $tJoinFoooter . "
        ";

        // WHERE เงื่อนไข Page
        $tSQL .= "   WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";

        //สั่ง Order by ตามข้อมูลหลัก
        $tSQL .= "   ORDER BY L.FTWahCode ";

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
    // Creator: 11/04/2019 Witsarut(Bell)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSnMCountDataReportAll($paDataWhere) {
        $tUserSession = $paDataWhere['tUserSession'];
        $tCompName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tSQL = "   SELECT 
                             COUNT(DTTMP.FTRptCode) AS rnCountPage
                         FROM TRPTPdtStkBalTmp AS DTTMP WITH(NOLOCK)
                         WHERE 1 = 1
                         AND FTUsrSession    = '$tUserSession'
                         AND FTComName       = '$tCompName'
                         AND FTRptCode       = '$tRptCode'
         ";
        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
        unset($oQuery);
        return $nRptAllRecord;
    }

}














