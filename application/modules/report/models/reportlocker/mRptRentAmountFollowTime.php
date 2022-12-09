<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptRentAmountFollowTime extends CI_Model
{

    /**
     * Functionality: Call Stored Procedure
     * Parameters:  Function Parameter
     * Creator: 15/07/2019 Teerapap(Pap)
     * Last Modified : 9/10/2019 Piya
     * Return : Status Return Call Stored Procedure
     * Return Type: Array
     */
    public function FSnMExecStoreReport($paDataFilter)
    {
        $tCallStore = "{ CALL SP_RPTxRentalDetail(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'pnLngID' => $paDataFilter['nLangID'],
            'pnComName' => $paDataFilter['tCompName'],
            'ptRptCode' => $paDataFilter['tRptCode'],
            'ptUsrSession' => $paDataFilter['tSessionID'],
            'ptBchF' => $paDataFilter['tBchCodeFrom'],
            'ptBchT' => $paDataFilter['tBchCodeTo'],
            'ptShpF' => $paDataFilter['tShopCodeFrom'],
            'ptShpT' => $paDataFilter['tShopCodeTo'],
            'ptPosCodeF' => $paDataFilter['tPosCodeFrom'],
            'ptPosCodeT' => $paDataFilter['tPosCodeTo'],
            'ptRackCodeF' => $paDataFilter['tRackCodeFrom'],
            'ptRackCodeT' => $paDataFilter['tRackCodeTo'],
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

    /**
     * Functionality: Count Data Report All
     * Parameters: Function Parameter
     * Creator: 15/07/2019 Teerapap(Pap)
     * Last Modified: 9/10/2019 Piya
     * Return: Data Report All
     * ReturnType: numeric
     */
    public function FSnMCountDataReportAll($paDataWhere)
    {

        $tSessionID = $paDataWhere['tSessionID'];
        $tCompName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];

        $tSQL = " 
            SELECT 
                *
            FROM TRPTRTSalDTTmp 
            WHERE 1=1
            AND FTUsrSession = '$tSessionID' 
            AND FTComName = '$tCompName' 
            AND FTRptCode = '$tRptCode'
        ";
        $oQuery = $this->db->query($tSQL);

        $nCountData = $oQuery->num_rows();
        unset($tSessionID);
        unset($tCompName);
        unset($tRptCode);
        unset($tSQL);
        unset($oQuery);
        return $nCountData;
    }

    /**
     * Functionality: Get Data Page
     * Parameters:  Function Parameter
     * Creator: 15/07/2019 Teerapap(Pap)
     * Last Modified : 9/10/2019 Piya
     * Return : Array Data Page Nation
     * Return Type: Array
     */
    public function FMaMRPTPagination($paDataWhere)
    {
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUsrSessionID'];

        $tSQL = "   
            SELECT 
                COUNT(TRPTRTCountPage.FTPosCode) AS rnCountPage
            FROM (
                SELECT 
                    FTPosCode,COUNT(FTRptRowSeq) AS FNSumNumRow 
                FROM TRPTRTSalDTTmp 
                WHERE FTComName = '" . $tComName . "'
                    AND FTRptCode = '" . $tRptCode . "'
                    AND FTUsrSession = '" . $tUsrSession . "'
                GROUP BY FTPosCode
            ) AS TRPTRTCountPage
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
            "nNextPage" => $nNextPage,
            "nPerPage" => $paDataWhere['nPerPage'],
        );

        unset($oQuery);
        return $aRptMemberDet;
    }

    /**
     * Functionality: Get Data Report
     * Parameters:  Function Parameter
     * Creator: 15/07/2019 Teerapap(Pap)
     * Last Modified : 9/10/2019 Piya
     * Return : Data Report
     * Return Type: Array
     */
    public function FSaMGetDataReport($paDataWhere)
    {
        $nPage = $paDataWhere['nPage'];
        // Call Data Pagination 
        $aPagination = $this->FMaMRPTPagination($paDataWhere);
        $nRowIDStart = $aPagination["nRowIDStart"];
        $nRowIDEnd = $aPagination["nRowIDEnd"];
        $nTotalPage = $aPagination["nTotalPage"];
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUsrSessionID'];

        $aTimeDataSet = [
            ["06:00", "06:59"],
            ["07:00", "07:59"],
            ["08:00", "08:59"],
            ["09:00", "09:59"],
            ["10:00", "10:59"],
            ["11:00", "11:59"],
            ["12:00", "12:59"],
            ["13:00", "13:59"],
            ["14:00", "14:59"],
            ["15:00", "15:59"],
            ["16:00", "16:59"],
            ["17:00", "17:59"],
            ["18:00", "18:59"],
            ["19:00", "19:59"],
            ["20:00", "20:59"],
            ["21:00", "21:59"],
            ["22:00", "22:59"],
            ["23:00", "23:59"]
        ];

        $tSQL = "   
            SELECT
                TRPTRTSalDTTmpSplit.*
            FROM(
                SELECT  
                    ROW_NUMBER() OVER(ORDER BY FTPosCode) AS RowID,*
                FROM(
                    SELECT 
                        FTPosCode,
                        COUNT(FTRptRowSeq) AS FNSumNumRow 
                    FROM TRPTRTSalDTTmp 
                    WHERE FTComName = '" . $tComName . "'
                    AND FTRptCode = '" . $tRptCode . "'
                    AND FTUsrSession = '" . $tUsrSession . "'
                    GROUP BY FTPosCode
                ) Base
            ) AS TRPTRTSalDTTmpSplit
         ";
        $tSQL .= " WHERE TRPTRTSalDTTmpSplit.RowID > $nRowIDStart AND TRPTRTSalDTTmpSplit.RowID <= $nRowIDEnd ";

        // echo $tSQL;
        // exit;

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $aSumData = $oQuery->result_array();
        } else {
            $aSumData = false;
        }

        $aData = array();
        for ($nJ = 0; $nJ < count($aSumData); $nJ++) {
            for ($nI = 0; $nI < count($aTimeDataSet); $nI++) {

                $tSQL = "   
                    SELECT 
                        FTPosCode,
                        '" . $aTimeDataSet[$nI][0] . "-" . $aTimeDataSet[$nI][1] . "' AS FTBlockTime,
                        COUNT(FTXshDocNo) AS FNCountDocNo
                    FROM TRPTRTSalDTTmp 
                    WHERE FTComName = '" . $tComName . "'
                    AND FTRptCode = '" . $tRptCode . "'
                    AND FTUsrSession = '" . $tUsrSession . "'
                    AND '" . $aTimeDataSet[$nI][0] . "' <= CONVERT(VARCHAR(10), FDXshDocDate, 8)
                    AND CONVERT(VARCHAR(10), FDXshDocDate, 8) <= '" . $aTimeDataSet[$nI][1] . "'
                    AND FTPosCode = '" . $aSumData[$nJ]["FTPosCode"] . "'
                    GROUP BY FTPosCode
                ";

                $oQuery = $this->db->query($tSQL);

                if ($oQuery->num_rows() > 0) {
                    $aResult = $oQuery->result_array();
                } else {
                    $aResult = false;
                }
                array_push($aData, $aResult);
            }
        }

        $aDataSend = array();

        if ($aSumData) {
            $aDataSend["aData"] = $aData;
            $aDataSend["aSumData"] = $aSumData;
        } else {
            $aDataSend = false;
        }

        $aErrorList = array(
            "nErrInvalidPage" => ""
        );

        $aResualt = array(
            "aPagination" => $aPagination,
            "aRptData" => $aDataSend,
            "aError" => $aErrorList
        );

        unset($oQuery);
        unset($aData);
        return $aResualt;
    }
}
