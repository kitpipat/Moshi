<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptProductTransfer extends CI_Model {

    //Functionality: Delete Temp Report
    //Parameters:  Function Parameter
    //Creator: 04/04/2019 Wasin(Yoshi)
    //Last Modified : 02/08/2019 Saharat(Golf)
    //Return : Call Store Proce
    //Return Type: Array
    public function FSnMExecStoreCReport($paDataFilter) {
        $tCallStore = "{ CALL SP_RPTxVDPdtTwx2002003(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            //21
            'pnLngID' => $paDataFilter['nLangID'],
            'pnComName' => $paDataFilter['tCompName'],
            'ptRptCode' => $paDataFilter['tRptCode'],
            'ptUsrSession' => $paDataFilter['tUserCode'],
            'ptMerF' => $paDataFilter['tMerCodeFrom'],
            'ptMerT' => $paDataFilter['tMerCodeTo'],
            'ptShpFF' => $paDataFilter['tShpCodeFrom'],
            'ptShpFT' => $paDataFilter['tShpCodeTo'],
            'ptShpTF' => "",
            'ptShpTT' => "",
            'ptPosFF' => $paDataFilter['tPosCodeFrom'],
            'ptPosFT' => $paDataFilter['tPosCodeTo'],
            'ptPosTF' => "",
            'ptPosTT' => "",
            'ptWahFF' => $paDataFilter['tWahCodeFrom'],
            'ptWahFT' => $paDataFilter['tWahCodeTo'],
            'ptWahTF' => "",
            'ptWahTT' => "",
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

    public function FMxMRPTSetPriorityGroup($ptComName, $ptRptCode, $ptUsrSession) {

        $tSQL = "
            UPDATE TRPTVDPdtTwxTmp SET 
                FNRowPartID = B.PartID
            FROM( 
                SELECT 
                    ROW_NUMBER() OVER(PARTITION BY FTXthDocNo ORDER BY FTXthDocNo DESC) AS PartID, 
                    FTRptRowSeq  
                FROM TRPTVDPdtTwxTmp TMP WITH(NOLOCK)
                WHERE TMP.FTComName = '$ptComName' 
                AND TMP.FTRptCode = '$ptRptCode'
                AND TMP.FTUsrSession = '$ptUsrSession'
            ) AS B
            WHERE TRPTVDPdtTwxTmp.FTRptRowSeq = B.FTRptRowSeq 
            AND TRPTVDPdtTwxTmp.FTComName = '$ptComName' 
            AND TRPTVDPdtTwxTmp.FTRptCode = '$ptRptCode'
            AND TRPTVDPdtTwxTmp.FTUsrSession = '$ptUsrSession' ";
        $this->db->query($tSQL);
        
        unset($ptComName);
        unset($ptRptCode);
        unset($ptUsrSession);
        unset($tSQL);
        
    }

    public function FMaMRPTPagination($paDataWhere) {
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUserSessionID'];

        $tSQL = "   SELECT
                                    COUNT(TTVD_TMP.FTXthDocNo) AS rnCountPage
                                FROM TRPTVDPdtTwxTmp TTVD_TMP WITH(NOLOCK)
                                WHERE 1=1
                                AND TTVD_TMP.FTComName    = '$tComName'
                                AND TTVD_TMP.FTRptCode    = '$tRptCode'
                                AND TTVD_TMP.FTUsrSession = '$tUsrSession'
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

    // Functionality: Call Stored Procedure
    // Parameters:  Function Parameter
    // Creator: 18/07/2019 Wasin(Yoshi)
    // Last Modified : 22/07/2019 saharat(Golf)
    // Return : Status Return Call Stored Procedure
    // Return Type: Array
    public function FSaMGetDataReport($paDataWhere) {
        $nPage = $paDataWhere['nPage'];
        // Call Data Pagination 
        $aPagination = $this->FMaMRPTPagination($paDataWhere);

        $nRowIDStart = $aPagination["nRowIDStart"];
        $nRowIDEnd = $aPagination["nRowIDEnd"];
        $nTotalPage = $aPagination["nTotalPage"];

        //Set Priority
        // $tComName = gethostname();
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tSession = $paDataWhere['tUserSessionID'];


        $this->FMxMRPTSetPriorityGroup($tComName, $tRptCode, $tSession);

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา 
        $tSQL = " 
            SELECT 
                L.*,
                T.FCXrcNetFooter
            FROM (
                SELECT ROW_NUMBER() OVER(ORDER BY FTXthDocNo) AS RowID, 
                    A.*,
                    S.FNRptGroupMember,
                    S.FCSdtSubQty
                FROM TRPTVDPdtTwxTmp A
                /* Calculate Misures */
                LEFT JOIN 
                (
                    SELECT 
                        FTXthDocNo AS FTRcvCode_SUM,
                        COUNT(FTXthDocNo) AS FNRptGroupMember,
                        SUM(FCXtdQty) AS FCSdtSubQty
                    FROM TRPTVDPdtTwxTmp 
                    WHERE FTComName    = '$tComName'
                    AND   FTRptCode    = '$tRptCode'
                    AND   FTUsrSession = '$tSession'
                    GROUP BY FTXthDocNo
                ) S ON A.FTXthDocNo = S.FTRcvCode_SUM
                    WHERE A.FTComName = '$tComName'
                    AND A.FTRptCode = '$tRptCode'
                    AND A.FTUsrSession = '$tSession'
                /* End Calculate Misures */
                ) L
        ";

        //Join เพื่อหา Summaty Footer
        $tSQL .= " LEFT JOIN ";

        //Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา 
        if ($nPage == $nTotalPage) {

            $tSQL .= " 
                (
                SELECT FTUsrSession AS  FTUsrSession_Footer, 
                    SUM(FCXrcNet) AS FCXrcNetFooter
                FROM   TRPTSalRCTmp
                WHERE  FTComName     = '$tComName'
                AND    FTRptCode     = '$tRptCode'
                AND    FTUsrSession  = '$tSession'
                GROUP BY FTUsrSession) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        } else {

            // ถ้าไม่ใช่ให้ Select 0 เพื่อให้ Join ได้แต่จะไม่มีการ Sum 
            $tSQL .= "
                (
                SELECT 
                    '$tSession' AS FTUsrSession_Footer,
                    '0' AS FCXrcNetFooter 
                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }

        // WHERE เงื่อนไข Page
        $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";
        //สั่ง Order by ตามข้อมูลหลัก
        $tSQL .= " ORDER BY L.FTXthDocNo ";

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
            "rnAllRow" => $this->FSnMCountRowInTemp($paDataWhere),
            "aError" => $aErrorList
        );
        unset($oQuery);
        unset($aData);
        return $aResualt;
    }

    /**
     * Functionality: Count Row in Temp
     * Parameters:  Function Parameter
     * Creator: 21/08/2019 Pap
     * Last Modified : -
     * Return : Count row
     * Return Type: Number
     */
    public function FSnMCountRowInTemp($paParams) {

        $tSessionID = $paParams['tUserSessionID'];
        $tCompName = $paParams['tCompName'];
        $tRptCode = $paParams['tRptCode'];

        $tSQL = "
                SELECT
                COUNT(TTVD_TMP.FTXthDocNo) AS rnCountPage
            FROM TRPTVDPdtTwxTmp TTVD_TMP WITH(NOLOCK)
            WHERE 1=1
            AND TTVD_TMP.FTComName    = '$tCompName'
            AND TTVD_TMP.FTRptCode    = '$tRptCode'
            AND TTVD_TMP.FTUsrSession = '$tSessionID'
        ";
        $oQuery = $this->db->query($tSQL);
        $nCountData = $oQuery->row_array()["rnCountPage"];
        return $nCountData;
    }

    // Functionality: Data Address Merchant
    // Parameters: function parameters
    // Creator:  22/07/2019 Wasin(Yoshi)
    // Last Modified: -
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

}


