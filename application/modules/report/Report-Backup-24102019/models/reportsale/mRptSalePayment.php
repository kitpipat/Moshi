<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptSalePayment extends CI_Model {

    /**
     * Functionality: Delete Temp Report
     * Parameters:  Function Parameter
     * Creator: 04/04/2019 Wasin(Yoshi)
     * Last Modified : 23/09/2019 Piya
     * Return : Call Store Proce
     * Return Type: Array
     */
    public function FSnMExecStoreReport($paDataFilter) {
        $tCallStore = "{ CALL SP_RPTxPaymentSum1001003(?,?,?,?,?,?,?,?,?,?,?,?,?) }";
        $aDataStore = array(
            'pnLngID' => $paDataFilter['nLangID'],
            'pnComName' => $paDataFilter['tCompName'],
            'ptRptCode' => $paDataFilter['tRptCode'],
            'ptUsrSession' => $paDataFilter['tUserSession'],
            'ptRcvF' => $paDataFilter['tRcvCodeFrom'],
            'ptRcvT' => $paDataFilter['tRcvCodeTo'],
            'ptBchF' => $paDataFilter['tBchCodeFrom'],
            'ptBchT' => $paDataFilter['tBchCodeTo'],
            'ptShpF' => $paDataFilter['tShpCodeFrom'],
            'ptShpT' => $paDataFilter['tShpCodeTo'],
            'ptDocDateF' => $paDataFilter['tDocDateFrom'],
            'ptDocDateT' => $paDataFilter['tDocDateTo'],
            'FNResult' => 0,
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
     * Functionality: Get Data Report
     * Parameters:  Function Parameter
     * Creator: 04/04/2019 Wasin(Yoshi)
     * Last Modified : 23/09/2019 Piya
     * Return : Get Data Rpt Temp
     * Return Type: Array
     */
    public function FSaMGetDataReport($paDataWhere, $paDataFilter) {
        
        $aRowLen = FCNaHCallLenData($paDataWhere['nRow'], $paDataWhere['nPage']);

        // $tUserCode  = $paDataWhere['tUserCode'];
        $tUserSession = $paDataWhere['tUserSession'];
        $tCompName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tBchCodeFrom = $paDataFilter['tBchCodeFrom'];
        $tBchCodeTo = $paDataFilter['tBchCodeTo'];
        $tShopCodeFrom = $paDataFilter['tShpCodeFrom'];
        $tShopCodeTo = $paDataFilter['tShpCodeTo'];
        // $tPosCodeFrom = $paDataFilter['tPosCodeFrom'];
        // $tPosCodeTo = $paDataFilter['tPosCodeTo'];
        $tDocDateFrom = $paDataFilter['tDocDateFrom'];
        $tDocDateTo = $paDataFilter['tDocDateTo'];

        $tSQL = "
           SELECT FTRcvName, 
                CONVERT(FLOAT,SUM(FCXrcNet)) AS NET
                /* SUM(FCXrcNet) AS NET */
            FROM TRPTSalRCTmp
            WHERE FTComName = '" . $tCompName . "' AND FTRptCode = '" . $tRptCode . "' AND FTUsrSession = '" . $tUserSession . "'
        ";

        // if($tBchCodeFrom != "" && $tBchCodeTo != ""){
        //     $tSQL .= " AND FTBchCode BETWEEN '".$tBchCodeFrom."' AND '".$tBchCodeTo."' ";
        // }
        // if($tShopCodeFrom != "" && $tShopCodeTo != ""){
        //     $tSQL .= " AND FTShpCode BETWEEN '".$tShopCodeFrom."' AND '".$tShopCodeTo."' ";
        // }
        // if($tPosCodeFrom != "" && $tPosCodeTo != ""){
        //     $tSQL .= " AND FTPosCode BETWEEN '".$tPosCodeFrom."' AND '".$tPosCodeTo."' ";
        // }
        // if($tDocDateFrom != "" && $tDocDateTo != ""){
        //     $tSQL .=  " AND CONVERT(varchar(10), FDXrcRefDate,121) BETWEEN '".$tDocDateFrom."' AND '".$tDocDateTo."' ";
        // }

        $tSQL .= " GROUP BY FTRcvCode,FTRcvName ORDER BY FTRcvCode";
        
        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $aDataRpt = $oQuery->result_array();
            // echo $nAllNet;exit();
            $oCountRowRpt = $this->FSaMCountDataReportAll($paDataWhere);
            $nFoundRow = $oCountRowRpt;
            $nPageAll = ceil($nFoundRow / $paDataWhere['nRow']);
            $aReturnData = array(
                'raItems' => $aDataRpt,
                'rnAllRow' => $nFoundRow,
                'rnCurrentPage' => $paDataWhere['nPage'],
                'rnAllPage' => $nPageAll,
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        } else {
            $aReturnData = array(
                'rnAllRow' => 0,
                'rnCurrentPage' => $paDataWhere['nPage'],
                "rnAllPage" => 0,
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            );
        }
        unset($oQuery);
        unset($oCountRowRpt);
        unset($nFoundRow);
        unset($nPageAll);
        return $aReturnData;
    }

    // Functionality: Count Data Report All
    // Parameters: Function Parameter
    // Creator: 11/04/2019 Wasin(Yoshi)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSaMCountDataReportAll($paDataWhere) {
        $tUserCode = $paDataWhere['tUserSession'];
        $tCompName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tSQL = "SELECT *
                        FROM TRPTSalRCTmp
                        WHERE 1 = 1
                        AND FTRptCode = '$tRptCode' AND FTComName = '$tCompName' AND  FTUsrSession = '$tUserCode' ";
        $oQuery = $this->db->query($tSQL);
        $nCountData = $oQuery->num_rows();
        unset($oQuery);
        return $nCountData;
    }

    // Functionality: Sum All Value Data Report All
    // Parameters: Function Parameter
    // Creator: 24/04/2019 Wasin(Yoshi)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSaMSumDataReportAll($paDataWhere) {
        $tUserCode = $paDataWhere['tUserCode'];
        $tCompName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
    }

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
            // ZNEL.FTZneCode          AS rtAddZneCode,
            // ZNEL.FTZneName          AS rtAddZneName,
            // AREL.FTAreCode          AS rtAddAreCode,--
            // AREL.FTAreName          AS rtAddAreName,--
            // LEFT JOIN [TCNMArea_L] AREL ON ADDL.FTAreCode = AREL.FTAreCode AND AREL.FNLngID = $nLngID
            // LEFT JOIN [TCNMZone] ZNE ON ADDL.FTZneCode = ZNE.FTZneCode 
            // LEFT JOIN [TCNMZone_L] ZNEL ON ZNE.FTZneChain = ZNEL.FTZneChain AND ZNEL.FNLngID = $nLngID
            // print_r($tSQL);
            // exit;
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

    // Functionality: To Get data SumFootReport
    // Parameters: Function Parameter
    // Creator: 12/08/2019 Sarun
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSaMGetDataSumFootReport($paDataWhere, $paDataFilter) {

        $aRowLen = FCNaHCallLenData($paDataWhere['nRow'], $paDataWhere['nPage']);

        $tUserSession = $paDataWhere['tUserSession'];
        $tCompName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];

        $tSQL = "  SELECT
                        ISNULL(SUM(FCXrcNet),0)   AS FCSumFooter
                    FROM TRPTSalRCTmp
                    WHERE 1 = 1 
                    AND FTUsrSession = '$tUserSession' AND FTComName = '$tCompName' AND FTRptCode = '$tRptCode'";

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

