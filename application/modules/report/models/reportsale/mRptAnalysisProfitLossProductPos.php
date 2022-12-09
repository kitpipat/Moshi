<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mRptAnalysisProfitLossProductPos extends CI_Model {

    /**
     * Functionality: Get Data Advance Table
     * Parameters:  Function Parameter
     * Creator: 01/10/2019 Sahaart(Golf)
     * Last Modified : 12/11/2019 Piya
     * Return : status
     * Return Type: Array
     */
    public function FSaMGetDataReport($paDataWhere){
        $nPage = $paDataWhere['nPage'];
        $nPerPage = $paDataWhere['nPerPage'];
        
        // Call Data Pagination 
        $aPagination = $this->FMaMRPTPagination($paDataWhere);
        
        $nRowIDStart = $aPagination["nRowIDStart"];
        $nRowIDEnd = $aPagination["nRowIDEnd"];
        $nTotalPage = $aPagination["nTotalPage"];

        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUserSessionID'];
        
        // Set Priority
        $this->FMxMRPTSetPriorityGroup($paDataWhere);

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา 
        if($nPage == $nTotalPage){
            $tRptJoinFooter = " 
                SELECT
                    FTUsrSession AS FTUsrSession_Footer,
                    NULLIF(SUM(FCXsdSaleQty),0) AS FCXsdSaleQty_Footer,
                    NULLIF(SUM(FCPdtCost),0) AS FCPdtCost_Footer,
                    NULLIF(SUM(FCXshGrand),0) AS FCXshGrand_Footer,
                    NULLIF(SUM(FCXsdProfit),0) AS FCXsdProfit_Footer,
                    NULLIF(SUM(FCXshGrand - FCPdtCost),0) AS nMinusProfitAndLost_Footer,
                    CASE  
                        WHEN SUM(ISNULL(FCPdtCost, 0) ) = 0 
                        THEN NULLIF(SUM(FCXshGrand - FCPdtCost),0)
                        ELSE ((NULLIF(SUM(FCXshGrand - FCPdtCost),0) - NULLIF(SUM(FCPdtCost),0))) 
                    END AS nSumCapital_Footer,
                    NULLIF(SUM(FCXshGrand - FCPdtCost),0) / NULLIF(SUM(FCXshGrand),0) * 100 AS nSumSales_Footer

                FROM TRPTPSTSaleProfitTmp WITH(NOLOCK)
                WHERE 1=1
                AND FTComName = '$tComName'
                AND FTRptCode = '$tRptCode'
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

        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        $tSQL = "   
            SELECT
                L.*,
                T.*
            FROM (
                SELECT
                    ROW_NUMBER() OVER(ORDER BY FTPdtCode) AS RowID,
                    A.*,
                    S.FNRptGroupMember,
                    S.nSumCapital,
                    S.nSumSales,
                    S.nMinusProfitAndLost
                FROM TRPTPSTSaleProfitTmp A WITH(NOLOCK)
                /* Calculate Misures */
                LEFT JOIN (
                    SELECT
                        FTPdtCode AS FTPdtCode_SUM,
                        COUNT(FTPdtCode) AS FNRptGroupMember,
                        (FCXshGrand - FCPdtCost) AS nMinusProfitAndLost,
                        CASE  
                            WHEN FCPdtCost = 0
                            THEN (FCXshGrand - FCPdtCost) 
                            ELSE ((FCXshGrand - FCPdtCost) / NULLIF(FCPdtCost, 0))  * 100 
                        END AS nSumCapital,
                        ((FCXshGrand - FCPdtCost) / NULLIF(FCXshGrand, 0)) * 100 AS nSumSales
                    FROM TRPTPSTSaleProfitTmp WITH(NOLOCK)
                    WHERE FTComName = '$tComName'
                    AND FTRptCode = '$tRptCode'
                    AND FTUsrSession = '$tUsrSession'
                    GROUP BY FTPdtCode,FCPdtCost,FCXshGrand,FCXsdProfit
                ) AS S ON A.FTPdtCode = S.FTPdtCode_SUM
                WHERE A.FTComName = '$tComName'
                AND A.FTRptCode = '$tRptCode'
                AND A.FTUsrSession = '$tUsrSession'
                /* End Calculate Misures */
            ) AS L
            LEFT JOIN (
            ".$tRptJoinFooter."
        ";

        // WHERE เงื่อนไข Page
        $tSQL .= " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";

        //สั่ง Order by ตามข้อมูลหลัก
        $tSQL .= " ORDER BY L.FTPdtCode ASC";
       
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aData = $oQuery->result_array();
            $nFoundRow = $this->FSnMCountRowInTemp($paDataWhere);
            $nPageAll = ceil($nFoundRow / $paDataWhere['nPerPage']);
            $aResualt= [
                'aPagination' => $aPagination,
                'aRptData' => $aData,
                'rnAllRow' => $nFoundRow,
                'rnAllPage' => $nPageAll,
                'rnCurrentPage' => $paDataWhere['nPage'],
                'rtCode' => '1',
                'rtDesc' => 'success',
            ];
        }else{
            $aResualt = [
                'aRptData' => NULL,
                'rnAllRow' => 0,
                'rnAllPage' => 0,
                'rnCurrentPage' => $paDataWhere['nPage'],
                'rtCode' => '800',
                'rtDesc' => 'data not found',
            ];
        }
        
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
        $tUsrSession = $paDataWhere['tUserSessionID'];
        $tSQL = "   
            SELECT
                TSPT.FTRptCode
            FROM TRPTPSTSaleProfitTmp TSPT WITH(NOLOCK)
            WHERE TSPT.FTComName = '$tComName'
            AND TSPT.FTRptCode = '$tRptCode'
            AND TSPT.FTUsrSession = '$tUsrSession'
        ";
        
        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->num_rows();
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
     * Last Modified : 12/11/2019 Piya
     * Return : -
     * Return Type: -
     */
    private function FMxMRPTSetPriorityGroup($paDataWhere){
        $tComName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUserSessionID'];

        $tSQL = "   
            UPDATE TRPTPSTSaleProfitTmp
                SET TRPTPSTSaleProfitTmp.FNRowPartID = B.PartID
                FROM (
                    SELECT
                        ROW_NUMBER() OVER(PARTITION BY TSPT.FTPdtCode ORDER BY TSPT.FTPdtCode ASC) AS PartID,
                        TSPT.FTRptRowSeq
                    FROM TRPTPSTSaleProfitTmp TSPT WITH(NOLOCK)
                    WHERE TSPT.FTComName = '$tComName'
                    AND TSPT.FTRptCode = '$tRptCode'
                    AND TSPT.FTUsrSession = '$tUsrSession'
                ) AS B
            WHERE TRPTPSTSaleProfitTmp.FTRptRowSeq = B.FTRptRowSeq
            AND TRPTPSTSaleProfitTmp.FTComName = '$tComName' 
            AND TRPTPSTSaleProfitTmp.FTRptCode = '$tRptCode'
            AND TRPTPSTSaleProfitTmp.FTUsrSession = '$tUsrSession'
        ";
        $this->db->query($tSQL);
    }
    
    /**
     * Functionality: Count Row in Temp
     * Parameters:  Function Parameter
     * Creator: 23/07/2019 Piya
     * Last Modified : 12/11/2019 Piya
     * Return : Count row
     * Return Type: Number
     */  
    public function FSnMCountRowInTemp($paParams = []) {
        
        $tCompName = $paParams['tCompName'];
        $tRptCode = $paParams['tRptCode'];
        $tUserSession = $paParams['tUserSessionID'];

        $tSQL = "   
            SELECT
                TSPT.FTRptCode
            FROM TRPTPSTSaleProfitTmp TSPT WITH(NOLOCK)
            WHERE TSPT.FTComName = '$tCompName'
            AND TSPT.FTRptCode = '$tRptCode'
            AND TSPT.FTUsrSession = '$tUserSession'
        ";
        
        $oQuery = $this->db->query($tSQL);
        $nCountData = $oQuery->num_rows();
        unset($oQuery);
        return $nCountData;
    }
 
    /**
     * Functionality: To Get data SumFootReport
     * Parameters: Function Parameter
     * Creator: 10/10/2019 Napat
     * Last Modified: 12/11/2019 Piya
     * Return: Data Report All
     * ReturnType: Array
     */
    public function FSaMGetDataSumFootReport($paDataWhere) {
        $tUsrSessionID = $paDataWhere['tUserSessionID'];
        $tCompName = $paDataWhere['tCompName'];
        $tRptCode = $paDataWhere['tRptCode'];
 
        $tSQL = "  
        SELECT
            ISNULL(SUM(FCXsdSaleQty),0) AS FCXsdSaleQtySum,
            ISNULL(SUM(FCPdtCost),0) AS FCPdtCostSum,
            ISNULL(SUM(FCXshGrand),0) AS FCXshGrandSum,
            ISNULL(SUM(FCXsdProfit),0) AS FCXsdProfitSum,
            CASE  
                WHEN SUM(ISNULL(FCPdtCost, 0) ) = 0 
                THEN NULLIF(SUM(FCXshGrand - FCPdtCost),0)
                ELSE ((NULLIF(SUM(FCXshGrand - FCPdtCost),0) - NULLIF(SUM(FCPdtCost),0))) 
            END AS FCXsdProfitPercentSum,
            ISNULL((ISNULL(SUM(FCXsdProfit),0)/ISNULL(SUM(FCXshGrand),0)) * 100,0) AS FCXsdSalePercentSum
        FROM TRPTPSTSaleProfitTmp
        WHERE FTUsrSession = '$tUsrSessionID'
        AND FTComName = '$tCompName' 
        AND FTRptCode = '$tRptCode'
        ";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->row_array();
        } else {
            return array();
        }
    }

}

















