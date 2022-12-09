<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptSaleRecive extends CI_Model {

    //Functionality: Delete Temp Report
    //Parameters:  Function Parameter
    //Creator: 10/07/2019 Saharat(Golf)
    //Last Modified :
    //Return : Call Store Proce
    //Return Type: Array
    public function FSnMExecStoreReport($paDataFilter) {
        $tCallStore = "{ CALL SP_RPTxPaymentDET1001005(?,?,?,?,?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'pnLngID' => $paDataFilter['nLangID'],
            'ptComName' => $paDataFilter['tCompName'],
            'ptRptCode' => $paDataFilter['tRptCode'],
            'ptUserSession' => $paDataFilter['tUserSession'],
            'ptRcvF' => $paDataFilter['tRcvCodeFrom'],
            'ptRcvT' => $paDataFilter['tRcvCodeTo'],
            'ptBchF' => $paDataFilter['tBchCodeFrom'],
            'ptBchT' => $paDataFilter['tBchCodeTo'],
            'ptShpF' => $paDataFilter['tShpCodeFrom'],
            'ptShpT' => $paDataFilter['tShpCodeTo'],
            'ptDocDateF' => $paDataFilter['tDocDateFrom'],
            'pthDocDateT' => $paDataFilter['tDocDateTo'],
            'FNResult' => 0
        );
        $oQuery = $this->db->query($tCallStore, $aDataStore);
        if ($oQuery != FALSE) {
            unset($oQuery);
            return 1;
        } else {
            unset($oQuery);
            return 0;
        }
    }

    // Functionality: Get Data Report
    // Parameters:  Function Parameter
    // Creator: 10/07/2019 Saharat(Golf)
    // Last Modified : 
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
        $tSession = $paDataWhere['tUsrSessionID'];


        //Set Priority
        $aData = $this->FMxMRPTSetPriorityGroup($tComName, $tRptCode, $tSession);
        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา 
        if ($nPage == $nTotalPage) {
            $tJoinFoooter = "   SELECT 
                                        FTUsrSession     AS FTUsrSession_Footer,
                                        SUM(FCXrcNet)    AS FCXrcNet_Footer
                            
                                    FROM TRPTSalRCTmp WITH(NOLOCK)
                                    WHERE 1=1
                                    AND FTComName       = '$tComName'
                                    AND FTRptCode       = '$tRptCode'
                                    AND FTUsrSession    = '$tSession'
                                    GROUP BY FTUsrSession ) T 
                                    ON L.FTUsrSession = T.FTUsrSession_Footer
                                ";
        } else {
            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum 
            $tJoinFoooter = "   SELECT
                                        '$tSession'     AS FTUsrSession_Footer,
                                        '0'             AS FCXrcNet_Footer
                                    ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
                                ";
        }

        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        $tSQL = "   SELECT
                            L.*,
                            T.FCXrcNet_Footer
                        FROM (
                            SELECT  
                                ROW_NUMBER() OVER(ORDER BY FTRcvCode) AS RowID ,
                                A.*,
                                S.FNRptGroupMember,
                                S.FCXrcNet_SubTotal
                
                            FROM TRPTSalRCTmp A WITH(NOLOCK)
                            -- Calculate Misures
                            LEFT JOIN (
                                SELECT
                                    FTRcvCode          AS FTRcvCode_SUM,
                                    COUNT(FTRcvCode)   AS FNRptGroupMember,
                                    SUM(FCXrcNet)      AS FCXrcNet_SubTotal
                  
                                FROM TRPTSalRCTmp WITH(NOLOCK)
                                WHERE 1=1
                                AND FTComName       = '$tComName'
                                AND FTRptCode       = '$tRptCode'
                                AND FTUsrSession    = '$tSession'
                                GROUP BY FTRcvCode
                            ) AS S ON A.FTRcvCode = S.FTRcvCode_SUM
                            WHERE A.FTComName       = '$tComName'
                            AND   A.FTRptCode       = '$tRptCode'
                            AND   A.FTUsrSession    = '$tSession'
                            -- End Calculate Misures
                        ) AS L 
                        LEFT JOIN (
                            " . $tJoinFoooter . "
                    ";

        // WHERE เงื่อนไข Page
        $tSQL .= "   WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        //สั่ง Order by ตามข้อมูลหลัก
        $tSQL .= "   ORDER BY L.FTRcvCode ";
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
    // Creator: 22/04/2019 Wasin(Yoshi)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array    
    public function FSaMCountDataReportAll($paDataWhere) {
        $tUserCode = $paDataWhere['tUserCode'];
        $tCompName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUserSession = $paDataWhere['tUserSession'];
        $tSQL = " SELECT 
                            FTRcvName  AS rtRcvName,
                            FTXshDocNo AS rtRcvDocNo,
                            FDCreateOn AS rtRcvCreateOn,
                            FCXrcNet   AS rtRcvrcNet 
                    FROM TRPTSalRCTmp  
                    WHERE 1 = 1 AND FTUsrSession = '$tUserSession' AND FTComName = '$tCompName' AND FTRptCode = '$tRptCode'";
        $oQuery = $this->db->query($tSQL);
        $nCountData = $oQuery->num_rows();
        unset($oQuery);
        return $nCountData;
    }

    public function FMaMRPTPagination($paDataWhere) {

        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUsrSessionID'];

        $tSQL = "   SELECT
                                    COUNT(RCV.FTRcvCode) AS rnCountPage
                                FROM TRPTSalRCTmp RCV WITH(NOLOCK)
                                WHERE 1=1
                                AND RCV.FTComName    = '$tComName'
                                AND RCV.FTRptCode    = '$tRptCode'
                                AND RCV.FTUsrSession = '$tUsrSession'
                                
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
            UPDATE TRPTSalRCTmp SET 
                FNRowPartID = B.PartID
            FROM( 
                SELECT 
                    ROW_NUMBER() OVER(PARTITION BY FTRcvCode ORDER BY FTRcvCode ASC) AS PartID, 
                    FTRptRowSeq  
                FROM TRPTSalRCTmp TMP WITH(NOLOCK)
                WHERE TMP.FTComName = '$ptComName' 
                AND TMP.FTRptCode = '$ptRptCode'
                AND TMP.FTUsrSession = '$ptUsrSession' 
            ) AS B
            WHERE TRPTSalRCTmp.FTRptRowSeq = B.FTRptRowSeq 
            AND TRPTSalRCTmp.FTComName = '$ptComName' 
            AND TRPTSalRCTmp.FTRptCode = '$ptRptCode'
            AND TRPTSalRCTmp.FTUsrSession = '$ptUsrSession'
        ";
        $this->db->query($tSQL);
    }

    // Functionality: Data Address Merchant
    // Parameters: function parameters
    // Creator:  22/07/2019 Wasin(Yoshi)
    // Last Modified: 06/08/2019 Saharaat(Golf)
    // Return: Data Array
    // Return Type: Array
    public function FSaMGetDataMerChant($paDataWhereMerChant) {
        $tMerchantCode = $paDataWhereMerChant['tMerChantCode'];
        $nLngID = $paDataWhereMerChant['nLngID'];

        $tSQL = "   SELECT DISTINCT
                            MER.FTMerCode       AS FTCompCode,
                            MER_L.FTMerName     AS FTCompName,
                            ADDL_MER.FTAddVersion,
                            ADDL_MER.FTAddV1No,
                            ADDL_MER.FTAddV1Soi,
                            ADDL_MER.FTAddV1Village,
                            ADDL_MER.FTAddV1Road,
                            ADDL_MER.FTAddV1SubDist,
                            SUBDIS_L.FTSudName,
                            ADDL_MER.FTAddV1DstCode,
                            DST_L.FTDstName,
                            ADDL_MER.FTAddV1PvnCode,
                            PVN_L.FTPvnName,
                            ADDL_MER.FTAddV1PostCode,
                            ADDL_MER.FTAddV2Desc1,
                            ADDL_MER.FTAddV2Desc2,
                            MER.FTMerEmail      AS FTCompEmail,
                            MER.FTMerTel        AS FTCompTel,
                            MER.FTMerFax        AS FTCompFax,
                            MER.FTMerMo         AS FTCompMobile
                        FROM TCNMMerchant               MER         WITH(NOLOCK)
                        LEFT JOIN TCNMMerchant_L 		MER_L 		WITH(NOLOCK) ON MER.FTMerCode = MER_L.FTMerCode AND MER_L.FNLngID = $nLngID
                        LEFT JOIN TCNMAddress_L			ADDL_MER 	WITH(NOLOCK) ON MER.FTMerCode = ADDL_MER.FTAddRefCode AND ADDL_MER.FTAddGrpType = 7 AND ADDL_MER.FNLngID = $nLngID
                        LEFT JOIN TCNMSubDistrict       SUBDIS		WITH(NOLOCK) ON ADDL_MER.FTAddV1SubDist = SUBDIS.FTSudCode
                        LEFT JOIN TCNMSubDistrict_L     SUBDIS_L	WITH(NOLOCK) ON SUBDIS.FTSudCode = SUBDIS_L.FTSudCode AND SUBDIS_L.FNLngID = $nLngID
                        LEFT JOIN TCNMDistrict			DST         WITH(NOLOCK) ON ADDL_MER.FTAddV1DstCode = DST.FTDstCode
                        LEFT JOIN TCNMDistrict_L		DST_L       WITH(NOLOCK) ON DST.FTDstCode = DST_L.FTDstCode AND DST_L.FNLngID = $nLngID
                        LEFT JOIN TCNMProvince			PVN         WITH(NOLOCK) ON ADDL_MER.FTAddV1PvnCode = PVN.FTPvnCode
                        LEFT JOIN TCNMProvince_L		PVN_L       WITH(NOLOCK) ON PVN.FTPvnCode = PVN_L.FTPvnCode	AND PVN_L.FNLngID = $nLngID
                        WHERE 1=1 AND MER.FTMerCode = '$tMerchantCode'
        ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->row_array();
        } else {
            return array();
        }
    }

    // Functionality: Count Data Report All
    // Parameters: Function Parameter
    // Creator: 21/08/2019 Saharat(Golf)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSnMCountDataReportAll($paDataWhere) {
        $tUserSession = $paDataWhere['tUserSession'];
        $tCompName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tSQL = "   SELECT 
                             COUNT(DTTMP.FTRptCode) AS rnCountPage
                         FROM TRPTSalRCTmp AS DTTMP WITH(NOLOCK)
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


