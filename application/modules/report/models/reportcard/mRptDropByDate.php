<?php

defined('BASEPATH') or exit('No direct script access allowed');

class mRptDropByDate extends CI_Model
{

    /**
     * Functonality: Get Data Report
     * Parameters:  Function Parameter
     * Creator: 11/10/2019 Piya
     * Last Modified : -
     * Return : Get Data Rpt Temp
     * Return Type: Array
     */
    public function FSaMGetDataReport($paDataWhere)
    {

        $aRowLen = FCNaHCallLenData($paDataWhere['nPerPage'], $paDataWhere['nPage']);
        $tCompName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tSessionID = $paDataWhere['tUserSessionID'];

        $tSQL = "
            SELECT
                c.*
            FROM(
                SELECT
                    ROW_NUMBER() OVER(ORDER BY FDXshDocDate DESC) AS rtRowID, *
                FROM(
                    SELECT TOP 100 PERCENT
                        TMP.FDXshDocDate,
                        TMP.FTBchCode,
                        TMP.FTBchName,
                        TMP.FTXshDocNo,
                        TMP.FTXshRefExt,
                        TMP.FTXshFrmLogin,
                        TMP.FTXshToLogin,
                        TMP.FDXshDatePick,
                        TMP.FTPosCode,
                        TMP.FTPzeName,
                        TMP.FTRthCode,
                        TMP.FTXsdTimeStart,
                        TMP.FCXsdNetAfHD,
                        CASE
                            WHEN TMP.FDXshDatePick IS NULL THEN '" . language('report/report/report', 'tRptNotReceived') . "'
                            ELSE '" . language('report/report/report', 'tRptReceived') . "' 
                        END AS FTStatus,
                        TMP.FTUsrSession,
                        TMP.FTComName,
                        TMP.FTRptCode
                    FROM TRPTRTDropByDateTemp TMP
                    WHERE TMP.FTUsrSession = '$tSessionID' AND TMP.FTComName = '$tCompName' AND TMP.FTRptCode = '$tRptCode'
                    ORDER BY
                    TMP.FDXshDocDate DESC,
                    TMP.FTBchCode ASC

            ) Base ) AS c WHERE c.rtRowID > $aRowLen[0] AND c.rtRowID <= $aRowLen[1]
        ";

        $oQuery = $this->db->query($tSQL);

        if ($oQuery->num_rows() > 0) {
            $aDataRpt = $oQuery->result_array();

            $oCountRowRpt = $this->FSnMCountRowInTemp($paDataWhere);
            $nFoundRow = $oCountRowRpt;
            $nPageAll = ceil($nFoundRow / $paDataWhere['nPerPage']);
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

    /**
     * Functionality: Count Data Report All
     * Parameters: Function Parameter
     * Creator: 11/10/2019 Piya
     * Last Modified: -
     * Return: Data Report All
     * ReturnType: Array
     */
    public function FSnMCountRowInTemp($paDataWhere)
    {
        $tSessionID = $paDataWhere['tUserSessionID'];
        $tCompName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        
        $tSQL = "
            SELECT TOP 100 PERCENT
                TMP.FDXshDocDate,
                TMP.FTBchCode,
                TMP.FTXshDocNo,
                TMP.FTXshRefExt,
                TMP.FTXshFrmLogin,
                TMP.FTXshToLogin,
                TMP.FDXshDatePick,
                TMP.FTPosCode,
                TMP.FTPzeName,
                TMP.FTRthCode,
                TMP.FTXsdTimeStart,
                TMP.FCXsdNetAfHD,
                TMP.FTUsrSession AS rtUsrSession,
                TMP.FTComName AS rtComname,
                TMP.FTRptCode AS rtRptCode
            FROM TRPTRTDropByDateTemp TMP
            WHERE TMP.FTUsrSession = '$tSessionID' AND TMP.FTComName = '$tCompName' AND TMP.FTRptCode = '$tRptCode'
            ORDER BY
            TMP.FDXshDocDate DESC,
            TMP.FTBchCode ASC
        ";

        $oQuery = $this->db->query($tSQL);
        $nCountData = $oQuery->num_rows();
        unset($oQuery);
        return $nCountData;
    }
}
