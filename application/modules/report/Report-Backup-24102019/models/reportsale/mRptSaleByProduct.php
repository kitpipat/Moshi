<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptSaleByProduct extends CI_Model {

    // Functionality: Call Stored Procedure
    // Parameters:  Function Parameter
    // Creator: 23/07/2019 Wasin(Yoshi)
    // Last Modified : 02/08/2019 Saharat(Golf)
    // Return : Status Return Call Stored Procedure
    // Return Type: Array
    public function FSnMExecStoreReport($paDataFilter) {
        $tCallStore = "{ CALL SP_RPTxDailySaleByInvByPdt1001002(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)}";
        $aDataStore = array(
            'pnLngID' => $paDataFilter['nLangID'],
            'pnComName' => $paDataFilter['tCompName'],
            'ptRptCode' => $paDataFilter['tRptCode'],
            'ptUsrSession' => $paDataFilter['tUserSession'],
            'ptBchF' => $paDataFilter['tBchCodeFrom'],
            'ptBchT' => $paDataFilter['tBchCodeTo'],
            'ptShpF' => $paDataFilter['tShpCodeFrom'],
            'ptShpT' => $paDataFilter['tShpCodeTo'],
            'ptPdtCodeF' => $paDataFilter['tPdtCodeFrom'],
            'ptPdtCodeT' => $paDataFilter['tPdtCodeTo'],
            'ptPdtChanF' => $paDataFilter['tPdtGrpCodeFrom'],
            'ptPdtChanT' => $paDataFilter['tPdtGrpCodeTo'],
            'ptPdtTypeF' => $paDataFilter['tPdtTypeCodeFrom'],
            'ptPdtTypeT' => $paDataFilter['tPdtTypeCodeTo'],
            'ptDocDateF' => $paDataFilter['tDocDateFrom'],
            'ptDocDateT' => $paDataFilter['tDocDateTo'],
            'FNResult' => 0
        );
        $oQuery = $this->db->query($tCallStore, $aDataStore);
        if ($oQuery !== FALSE) {
            unset($oQuery);
            return 1;
        } else {
            unset($oQuery);
            return 0;
        }
    }

    // Functionality: Get Data Page Co
    // Parameters:  Function Parameter
    // Creator: 02/08/2019 Saharat(Golf)
    // Last Modified : 09/08/2019 Wasin(Yoshi)
    // Return : Array Data Page Nation
    // Return Type: Array
    public function FMaMRPTPagination($paDataWhere) {
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUsrSessionID'];
        $tSQL = " SELECT
                                COUNT(DTTMP.FTRptCode) AS rnCountPage
                            FROM TRPTSalDTTmp AS DTTMP WITH(NOLOCK)
                            WHERE 1=1
                            AND DTTMP.FTComName    = '$tComName'
                            AND DTTMP.FTRptCode    = '$tRptCode'
                            AND DTTMP.FTUsrSession = '$tUsrSession'
        ";
        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
        $nPage = $paDataWhere['nPage'];
        $nPerPage = $paDataWhere['nPerPage'];
        $nPrevPage = $nPage - 1;
        $nNextPage = $nPage + 1;
        $nRowIDStart = (($nPerPage * $nPage) - $nPerPage);
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

    // Functionality: Get Data Page Co
    // Parameters:  Function Parameter
    // Creator: 02/08/2019 Saharat(Golf)
    // Last Modified : 09/08/2019 Wasin(Yoshi)
    // Return : Array Data Page Nation
    // Return Type: Array
    public function FMxMRPTSetPriorityGroup($paDataWhere) {
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUsrSessionID'];
        $tSQL = " 
            UPDATE DATAUPD SET 
                DATAUPD.FNRowPartID = B.PartID
            FROM TRPTSalDTTmp AS DATAUPD WITH(NOLOCK)
            INNER JOIN(
                SELECT
                    ROW_NUMBER() OVER(PARTITION BY FTXshDocNo ORDER BY FTXshDocNo DESC) AS PartID,
                    FTRptRowSeq
                FROM TRPTSalDTTmp TMP WITH(NOLOCK)
                WHERE TMP.FTComName = '$tComName'
                AND TMP.FTRptCode = '$tRptCode'
                AND TMP.FTUsrSession = '$tUsrSession'
            ) AS B
            ON DATAUPD.FTRptRowSeq = B.FTRptRowSeq
            AND DATAUPD.FTComName = '$tComName'
            AND DATAUPD.FTRptCode = '$tRptCode'
            AND DATAUPD.FTUsrSession = '$tUsrSession'
        ";
        $this->db->query($tSQL);
    }

    // Functionality: Get Data Report In Table Temp
    // Parameters:  Function Parameter
    // Creator: 09/08/2019 Wasin(Yoshi)
    // Last Modified : -
    // Return : Array Data report
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
        $tUsrSession = $paDataWhere['tUsrSessionID'];

        // Set Priority
        $this->FMxMRPTSetPriorityGroup($paDataWhere);

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = "   SELECT
                                        FTUsrSession            AS FTUsrSession_Footer,
                                        SUM(FCXsdQty)           AS FCXsdQty_Footer,
                                        SUM(FCXsdAmtB4DisChg)   AS FCXsdAmtB4DisChg_Footer,
                                        SUM(FCXsdDis)           AS FCXsdDis_Footer,
                                        SUM(FCXsdVat)           AS FCXsdVat_Footer,
                                        SUM(FCXsdNetAfHD)       AS FCXsdNetAfHD_Footer
                                    FROM TRPTSalDTTmp WITH(NOLOCK)
                                    WHERE 1=1
                                    AND FTComName       = '$tComName'
                                    AND FTRptCode       = '$tRptCode'
                                    AND FTUsrSession    = '$tUsrSession'
                                    GROUP BY FTUsrSession
                                    ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum
            $tJoinFoooter = "   SELECT
                                        '$tUsrSession'  AS FTUsrSession_Footer,
                                        0   AS FCXsdQty_Footer,
                                        0   AS FCXsdAmtB4DisChg_Footer,
                                        0   AS FCXsdDis_Footer,
                                        0   AS FCXsdVat_Footer,
                                        0   AS FCXsdNetAfHD_Footer
                                    ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }

        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        $tSQL = "   SELECT
                            L.*,
                            T.FCXsdQty_Footer,
                            T.FCXsdAmtB4DisChg_Footer,
                            T.FCXsdDis_Footer,
                            T.FCXsdVat_Footer,
                            T.FCXsdNetAfHD_Footer
                        FROM (
                            SELECT
                                ROW_NUMBER() OVER(ORDER BY FTXshDocNo) AS RowID ,
                                A.*,
                                S.FNRptGroupMember,
                                S.FCXsdQty_SubTotal,
                                S.FCXsdAmtB4DisChg_SubTotal,
                                S.FCXsdDis_SubTotal,
                                S.FCXsdVat_SubTotal,
                                S.FCXsdNetAfHD_SubTotal
                            FROM TRPTSalDTTmp A WITH(NOLOCK)
                            /* Calculate Misures */
                            LEFT JOIN (
                                SELECT
                                    FTXshDocNo              AS FTXshDocNo_SUM,
                                    COUNT(FTXshDocNo)       AS FNRptGroupMember,
                                    SUM(FCXsdQty)           AS FCXsdQty_SubTotal,
                                    SUM(FCXsdAmtB4DisChg)   AS FCXsdAmtB4DisChg_SubTotal,
                                    SUM(FCXsdDis)           AS FCXsdDis_SubTotal,
                                    SUM(FCXsdVat)           AS FCXsdVat_SubTotal,
                                    SUM(FCXsdNetAfHD)       AS FCXsdNetAfHD_SubTotal
                                FROM TRPTSalDTTmp WITH(NOLOCK)
                                WHERE 1=1
                                AND FTComName       = '$tComName'
                                AND FTRptCode       = '$tRptCode'
                                AND FTUsrSession    = '$tUsrSession'
                                GROUP BY FTXshDocNo
                            ) AS S ON A.FTXshDocNo = S.FTXshDocNo_SUM
                            WHERE 1=1
                            AND A.FTComName     = '$tComName'
                            AND A.FTRptCode     = '$tRptCode'
                            AND A.FTUsrSession  = '$tUsrSession'
                            /* End Calculate Misures */
                        ) AS L
                        LEFT JOIN (
                            " . $tJoinFoooter . "
        ";

        // WHERE เงื่อนไข Page
        $tSQL .= "   WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";

        // สั่ง Order by ตามข้อมูลหลัก
        $tSQL .= "   ORDER BY L.FTXshDocNo ";

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
    // Creator: 11/04/2019 Saharat(Golf)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSnMCountDataReportAll($paDataWhere) {
        $tUserSession = $paDataWhere['tUserSession'];
        $tCompName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tSQL = "   SELECT 
                             COUNT(DTTMP.FTRptCode) AS rnCountPage
                         FROM TRPTSalDTTmp AS DTTMP WITH(NOLOCK)
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


