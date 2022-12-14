<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptAnalysisProfitLossProductPos extends CI_Model {
        /**
     * Functionality: Call Store
     * Parameters:  Function Parameter
     * Creator: 01/10/2019 Saharat(Golf)
     * Last Modified : -
     * Return : Status Return Call Stored Procedure
     * Return Type: Array
     */
    public function FSnMExecStoreReport($paParams){
        $tCallStore = "{CALL SP_RPTxPSSalByProfitByLoss(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)}";
        $aDataStore = array(
            'pnLngID'           => $paParams['nLangID'], 
            'pnComName'         => $paParams['tCompName'],
            'ptRptCode'         => $paParams['tRptCode'],
            'ptUsrSession'      => $paParams['tUserSession'],
            'ptBchF'            => $paParams['tBchCodeFrom'],
            'ptBchT'            => $paParams['tBchCodeTo'],
            'ptShpF'            => $paParams['tRptShpCodeFrom'],
            'ptShpT'            => $paParams['tRptShpCodeTo'],
            'ptPosCodeF'        => $paParams['tRptPosCodeFrom'],
            'ptPosCodeT'        => $paParams['tRptPosCodeTo'],
            'ptChainCodeF'      => $paParams['tRptPdtGrpCodeFrom'],
            'ptChainCodeT'      => $paParams['tRptPdtGrpCodeTo'],
            'ptProductCodeF'    => $paParams['tRptPdtCodeFrom'],
            'ptProductCodeT'    => $paParams['tRptPdtCodeTo'],
            'ptDocDateF'        => $paParams['tDocDateFrom'],
            'ptDocDateT'        => $paParams['tDocDateTo'],
            'FTResult'          => 0
        );

        $oQuery = $this->db->query($tCallStore, $aDataStore);
        if($oQuery !== FALSE){
            unset($oQuery);
            return 1;
        }else{
            unset($oQuery);
            return 0;
        }
    }

        /**
     * Functionality: Count Row in Temp
     * Parameters:  Function Parameter
     * Creator: 01/10/2019 Saharat(Golf)
     * Last Modified : -
     * Return : Count row
     * Return Type: Number
     */
    public function FSnMCountRowInTemp($paParams){
        $tComName    = $paParams['tCompName'];
        $tRptCode    = $paParams['tRptCode'];
        $tUsrSession = $paParams['tSessionID'];
        $tSQL = "   
            SELECT
                COUNT(TSPT.FTRptCode) AS rnCountPage
            FROM TRPTPSTSaleProfitTmp TSPT WITH(NOLOCK)
            WHERE 1=1
            AND TSPT.FTComName    = '$tComName'
            AND TSPT.FTRptCode    = '$tRptCode'
            AND TSPT.FTUsrSession = '$tUsrSession'
        ";
        
        $oQuery = $this->db->query($tSQL);
        return $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
    }

    /**
     * Functionality: Get Data Advance Table
     * Parameters:  Function Parameter
     * Creator: 01/10/2019 Sahaart(Golf)
     * Last Modified : -
     * Return : status
     * Return Type: Array
     */
    public function FSaMGetDataReport($paDataWhere){
        $nPage          = $paDataWhere['nPage'];
        $nPerPage       = $paDataWhere['nPerPage'];
        
        // Call Data Pagination 
        $aPagination    = $this->FMaMRPTPagination($paDataWhere);
        
        $nRowIDStart    = $aPagination["nRowIDStart"];
        $nRowIDEnd      = $aPagination["nRowIDEnd"];
        $nTotalPage     = $aPagination["nTotalPage"];

        $tComName       = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];
        $tUsrSession    = $paDataWhere['tUsrSessionID'];
        
        // Set Priority
        $this->FMxMRPTSetPriorityGroup($paDataWhere);

        // Check ??????????????????????????????????????????????????????????????????????????? ????????????????????????????????????????????????????????????????????? Sum footer ???????????????????????? 
        if($nPage == $nTotalPage){
            $tRptJoinFooter = " 
                SELECT
                    FTUsrSession AS FTUsrSession_Footer,
                    SUM(ISNULL(FCXsdSaleQty,0)) AS FCXsdSaleQty_Footer,
                    SUM(ISNULL(FCPdtCost,0))    AS FCPdtCost_Footer,
                    SUM(ISNULL(FCXshGrand,0))   AS FCXshGrand_Footer,
                    SUM(ISNULL(FCXsdProfit,0))  AS FCXsdProfit_Footer,
                    (SUM(FCXsdProfit)/SUM(FCPdtCost))*100   AS nSumCapital_Footer,
                    (SUM(FCXsdProfit)/SUM(FCXshGrand))*100  AS nSumSales_Footer
                FROM TRPTPSTSaleProfitTmp WITH(NOLOCK)
                WHERE 1=1
                AND FTComName    = '$tComName'
                AND FTRptCode    = '$tRptCode'
                AND FTUsrSession = '$tUsrSession'
                GROUP BY FTUsrSession ) T ON L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }else{
            $tRptJoinFooter = " 
                SELECT
                    '$tUsrSession' AS FTUsrSession_Footer,
                    0 AS FCXsdSaleQty_Footer,
                    0 AS FCPdtCost_Footer,
                    0 AS FCXshGrand_Footer,
                    0 AS FCXsdProfit_Footer,
                    0 AS nSumCapital_Footer,
                    0 AS nSumSales_Footer
                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }

        // L = List ???????????????????????????????????????
        // A = SaleDT
        // S = Misures Summary
        $tSQL   =   "   
            SELECT
                L.*,
                T.*
            FROM (
                SELECT
                    ROW_NUMBER() OVER(ORDER BY FTPdtCode) AS RowID,
                    A.*,
                    S.FNRptGroupMember,
                    S.nSumCapital,
                    S.nSumSales
                FROM TRPTPSTSaleProfitTmp A WITH(NOLOCK)
                /* Calculate Misures */
                LEFT JOIN (
                    SELECT
                        FTPdtCode                           AS FTPdtCode_SUM,
                        COUNT(FTPdtCode)                    AS FNRptGroupMember,
                        (FCXsdProfit/NULLIF(FCPdtCost,0))*100   AS nSumCapital,
						(FCXsdProfit/NULLIF(FCXshGrand,0))*100  AS nSumSales
                    FROM TRPTPSTSaleProfitTmp WITH(NOLOCK)
                    WHERE 1=1
                    AND FTComName = '$tComName'
                    AND FTRptCode = '$tRptCode'
                    AND FTUsrSession = '$tUsrSession'
                    GROUP BY FTPdtCode,FCPdtCost,FCXshGrand,FCXsdProfit
                ) AS S ON A.FTPdtCode = S.FTPdtCode_SUM
                WHERE A.FTComName = '$tComName'
                AND   A.FTRptCode = '$tRptCode'
                AND   A.FTUsrSession = '$tUsrSession'
                /* End Calculate Misures */
            ) AS L
            LEFT JOIN (
            ".$tRptJoinFooter."
        ";

        // WHERE ???????????????????????? Page
        $tSQL   .=  " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";

        //???????????? Order by ???????????????????????????????????????
        $tSQL   .=  " ORDER BY L.FTPdtCode ASC";
       
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aData = $oQuery->result_array();
        }else{
            $aData = NULL;
        }

        $aErrorList = [
            "nErrInvalidPage" => ""
        ];

        $aResualt= [
            "aPagination"   =>  $aPagination,
            "aRptData"      =>  $aData,
            "aError"        =>  $aErrorList
        ];
        unset($oQuery); 
        unset($aData);
        return $aResualt;
    }

        /**
     * Functionality: Calurate Pagination
     * Parameters:  Function Parameter
     * Creator: 01/09/2019 Saharat(Golf)
     * Last Modified : -
     * Return : Pagination
     * Return Type: Array
     */
    private function FMaMRPTPagination($paDataWhere){
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUsrSessionID'];
        $tSQL = "   
            SELECT
                COUNT(TSPT.FTRptCode) AS rnCountPage
            FROM TRPTPSTSaleProfitTmp TSPT WITH(NOLOCK)
            WHERE 1=1
            AND TSPT.FTComName = '$tComName'
            AND TSPT.FTRptCode = '$tRptCode'
            AND TSPT.FTUsrSession = '$tUsrSession'
        ";
        
        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
        $nPage = $paDataWhere['nPage'];
        
        $nPerPage = $paDataWhere['nPerPage'];
        
        $nPrevPage = $nPage-1;
        $nNextPage = $nPage+1;
        $nRowIDStart = (($nPerPage*$nPage)-$nPerPage); //RowId Start
        if($nRptAllRecord<=$nPerPage){
            $nTotalPage = 1;
        }else if(($nRptAllRecord % $nPerPage)==0){
            $nTotalPage = ($nRptAllRecord/$nPerPage) ;
        }else{
            $nTotalPage = ($nRptAllRecord/$nPerPage)+1;
            $nTotalPage = (int)$nTotalPage;
        }

        // get rowid end
        $nRowIDEnd = $nPerPage * $nPage;
        if($nRowIDEnd > $nRptAllRecord){
            $nRowIDEnd = $nRptAllRecord;
        }

        $aRptMemberDet = array(
            "nTotalRecord" => $nRptAllRecord,
            "nTotalPage" => $nTotalPage,
            "nDisplayPage" => $paDataWhere['nPage'],
            "nRowIDStart" => $nRowIDStart,
            "nRowIDEnd" => $nRowIDEnd,
            "nPrevPage" => $nPrevPage,
            "nNextPage" => $nNextPage,
            "nPerPage" => $nPerPage
        );
        unset($oQuery);
        return $aRptMemberDet;
    }

    /**
     * Functionality: Set PriorityGroup
     * Parameters:  Function Parameter
     * Creator: 01/09/2019 Saharat(Golf)
     * Last Modified : -
     * Return : -
     * Return Type: -
     */
    private function FMxMRPTSetPriorityGroup($paDataWhere){
        $tComName    = $paDataWhere['tCompName'];
        $tRptCode    = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUsrSessionID'];

        $tSQL = "   
            UPDATE TRPTPSTSaleProfitTmp
                SET TRPTPSTSaleProfitTmp.FNRowPartID = B.PartID
                FROM (
                    SELECT
                        ROW_NUMBER() OVER(PARTITION BY TSPT.FTPdtCode ORDER BY TSPT.FTPdtCode ASC) AS PartID ,
                        TSPT.FTRptRowSeq
                    FROM TRPTPSTSaleProfitTmp TSPT WITH(NOLOCK)
                    WHERE TSPT.FTComName  = '$tComName'
                    AND TSPT.FTRptCode    = '$tRptCode'
                    AND TSPT.FTUsrSession = '$tUsrSession'
                ) AS B
            WHERE 1=1
            AND TRPTPSTSaleProfitTmp.FTRptRowSeq  = B.FTRptRowSeq
            AND TRPTPSTSaleProfitTmp.FTComName    = '$tComName' 
            AND TRPTPSTSaleProfitTmp.FTRptCode    = '$tRptCode'
            AND TRPTPSTSaleProfitTmp.FTUsrSession = '$tUsrSession'
        ";
        $this->db->query($tSQL);
    }

    // Functionality: To Get data SumFootReport
    // Parameters: Function Parameter
    // Creator: 10/10/2019 Napat
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSaMGetDataSumFootReport($paDataWhere) {
        // $aRowLen        = FCNaHCallLenData($paDataWhere['nRow'], $paDataWhere['nPage']);
        $tUsrSessionID  = $paDataWhere['tUsrSessionID'];
        $tCompName      = $paDataWhere['tCompName'];
        $tRptCode       = $paDataWhere['tRptCode'];

        $tSQL = "  SELECT
                        ISNULL(SUM(FCXsdSaleQty),0)                                                 AS FCXsdSaleQtySum,
                        ISNULL(SUM(FCPdtCost),0)                                                    AS FCPdtCostSum,
                        ISNULL(SUM(FCXshGrand),0)                                                   AS FCXshGrandSum,
                        ISNULL(SUM(FCXsdProfit),0)                                                  AS FCXsdProfitSum,
                        ISNULL((ISNULL(SUM(FCXsdProfit),0)/ISNULL(SUM(FCPdtCost),0)) * 100,0)       AS FCXsdProfitPercentSum,
		                ISNULL((ISNULL(SUM(FCXsdProfit),0)/ISNULL(SUM(FCXshGrand),0)) * 100,0)      AS FCXsdSalePercentSum
                    FROM TRPTPSTSaleProfitTmp
                    WHERE 1 = 1 
                      AND FTUsrSession  = '$tUsrSessionID'
                      AND FTComName     = '$tCompName' 
                      AND FTRptCode     = '$tRptCode'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->row_array();
        } else {
            return array();
        }
    }






}




