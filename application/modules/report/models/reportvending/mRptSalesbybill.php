<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mRptSalesbybill extends CI_Model {

    // Functionality: Get Data Report
    // Parameters:  Function Parameter
    // Creator: 17/10/2019 Saharat(GolF)
    // Last Modified : -
    // Return : Get Data Rpt Temp
    // Return Type: Array
    public function FSaMGetDataReport($paDataWhere,$paDataFilter){
        $nPage    = $paDataWhere['nPage'];
        $nPerPage = $paDataWhere['nRow'];

        // Call Data Pagination 
        $aPagination = $this->FMaMRPTPagination($paDataWhere);

        $nRowIDStart = $aPagination["nRowIDStart"];
        $nRowIDEnd   = $aPagination["nRowIDEnd"];
        $nTotalPage  = $aPagination["nTotalPage"];

        $tComName    = $paDataWhere['tCompName'];
        $tRptCode    = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUserSessionID'];

        // Set Priority
        $this->FMxMRPTSetPriorityGroup($paDataWhere);

        // Check ว่าเป็นหน้าสุดท้ายหรือไม่ ถ้าเป็นหน้าสุดท้ายให้ไป Sum footer ข้อมูลมา 
        if($nPage == $nTotalPage){
            $tRptJoinFooter = " 
                SELECT
                    FTUsrSession AS FTUsrSession_Footer,
                    SUM (ISNULL( FCXshAmtNV, 0 ))       AS FCXshAmtNV_Footer,
                    SUM (ISNULL( FCXshDis,   0 ))       AS FCXshDis_Footer,
                    SUM (ISNULL( FCXshVat,   0 ))       AS FCXshVat_Footer,
                    SUM (ISNULL( FCXshGrand, 0 ))       AS FCXshGrand_Footer
            
                FROM TRPTVDTSaleByBillTemp WITH(NOLOCK)
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
                    0 AS FCXshAmtNV_Footer,
                    0 AS FCXshDis_Footer,
                    0 AS FCXshVat_Footer,
                    0 AS FCXshGrand_Footer
                ) T ON  L.FTUsrSession = T.FTUsrSession_Footer
            ";
        }

        // L = List ข้อมูลทั้งหมด
        // A = SaleDT
        // S = Misures Summary
        $tSQL   =   "   
            SELECT
                L.*,
                T.*
            FROM (
                SELECT
                    ROW_NUMBER() OVER(ORDER BY FTXshDocNo) AS RowID,
                    A.*,
                    S.FNRptGroupMember,
                    S.FCXshAmtNV_SUM,
                    S.FCXshDis_SUM,
                    S.FCXshVat_SUM,
                    S.FCXshGrand_SUM

                FROM TRPTVDTSaleByBillTemp A WITH(NOLOCK)
                /* Calculate Misures */
                LEFT JOIN (
                    SELECT

                        FTXshDocNo AS FTXshDocNo_SUM,
                        COUNT ( FTXshDocNo )              AS FNRptGroupMember,
                        SUM (ISNULL( FCXshAmtNV, 0 ))     AS FCXshAmtNV_SUM,
                        SUM (ISNULL( FCXshDis,   0 ))     AS FCXshDis_SUM,
                        SUM (ISNULL( FCXshVat,   0 ))     AS FCXshVat_SUM,
                        SUM (ISNULL( FCXshGrand, 0 ))  	  AS FCXshGrand_SUM

                    FROM TRPTVDTSaleByBillTemp WITH(NOLOCK)
                    WHERE 1=1
                    AND FTComName    = '$tComName'
                    AND FTRptCode    = '$tRptCode'
                    AND FTUsrSession = '$tUsrSession'
                    GROUP BY FTXshDocNo
                ) AS S ON A.FTXshDocNo = S.FTXshDocNo_SUM
                WHERE A.FTComName    = '$tComName'
                AND   A.FTRptCode    = '$tRptCode'
                AND   A.FTUsrSession = '$tUsrSession'
                /* End Calculate Misures */
            ) AS L
            LEFT JOIN (
            ".$tRptJoinFooter."
        ";

        // WHERE เงื่อนไข Page
        $tSQL   .=  " WHERE L.RowID > $nRowIDStart AND L.RowID <= $nRowIDEnd ";

        //สั่ง Order by ตามข้อมูลหลัก
        $tSQL   .=  " ORDER BY L.FTXshDocNo ASC";
      
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $aData      = $oQuery->result_array();
            $nFoundRow  = $this->FSnMCountRowInTemp($paDataWhere);
            $nPageAll   = ceil($nFoundRow / $paDataWhere['nRow']);
        }else{
            $aData = NULL;
        }

        $aErrorList = [
            "nErrInvalidPage" => ""
        ];

        $aResualt= [
            "aPagination"   =>  $aPagination,
            "aRptData"      =>  $aData,
            "aError"        =>  $aErrorList,
            'rnAllRow'      =>  $nFoundRow,
            'rnAllPage'     =>  $nPageAll,
            'rnCurrentPage' =>  $paDataWhere['nPage'],
        ];
        unset($oQuery); 
        unset($aData);
        return $aResualt;
    }

    // Functionality: Count Data Report All
    // Parameters: Function Parameter
    // Creator: 17/10/2019  Saharat(GolF)
    // Last Modified: -
    // Return: Data Report All
    // ReturnType: Array
    public function FSnMCountRowInTemp($paDataWhere){
        $tComName    = $paDataWhere['tCompName'];
        $tRptCode    = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUserSessionID'];

        $tSQL = "SELECT COUNT(TSB.FTRptCode) AS rnCountPage
            FROM TRPTVDTSaleByBillTemp TSB WITH(NOLOCK)
            WHERE 1=1
            AND TSB.FTComName    = '$tComName'
            AND TSB.FTRptCode    = '$tRptCode'
            AND TSB.FTUsrSession = '$tUsrSession'   
        ";

        $oQuery = $this->db->query($tSQL);
        return $nRptAllRecord = $oQuery->row_array()['rnCountPage'];
    }

        /**
     * Functionality: Calurate Pagination
     * Parameters:  Function Parameter
     * Creator: 17/10/2019 Saharat(Golf)
     * Last Modified : -
     * Return : Pagination
     * Return Type: Array
     */
    private function FMaMRPTPagination($paDataWhere){
        $tComName    = $paDataWhere['tCompName'];
        $tRptCode    = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUserSessionID'];
        $tSQL = "   
            SELECT
                COUNT(TSB.FTRptCode) AS rnCountPage
            FROM TRPTVDTSaleByBillTemp TSB WITH(NOLOCK)
            WHERE 1=1
            AND TSB.FTComName    = '$tComName'
            AND TSB.FTRptCode    = '$tRptCode'
            AND TSB.FTUsrSession = '$tUsrSession'
        ";

        $oQuery = $this->db->query($tSQL);
        $nRptAllRecord = $oQuery->row_array()['rnCountPage'];

        $nPage    = $paDataWhere['nPage'];
        $nPerPage = $paDataWhere['nRow'];

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
            "nTotalPage"   => $nTotalPage,
            "nDisplayPage" => $paDataWhere['nPage'],
            "nRowIDStart"  => $nRowIDStart,
            "nRowIDEnd"    => $nRowIDEnd,
            "nPrevPage"    => $nPrevPage,
            "nNextPage"    => $nNextPage,
            "nPerPage"     => $nPerPage
        );
        unset($oQuery);
        return $aRptMemberDet;
    }

    /**
     * Functionality: Set PriorityGroup
     * Parameters:  Function Parameter
     * Creator: 17/10/2019 Saharat(GolF)
     * Last Modified : -
     * Return : -
     * Return Type: -
     */
    private function FMxMRPTSetPriorityGroup($paDataWhere){
        $tComName    = $paDataWhere['tCompName'];
        $tRptCode    = $paDataWhere['tRptCode'];
        $tUsrSession = $paDataWhere['tUserSessionID'];

        $tSQL = " UPDATE TRPTVDTSaleByBillTemp
                SET TRPTVDTSaleByBillTemp.FNRowPartID = B.PartID
                FROM (
                    SELECT
                        ROW_NUMBER() OVER(PARTITION BY TSB.FTXshDocNo ORDER BY TSB.FTXshDocNo ASC) AS PartID ,
                        TSB.FTRptRowSeq
                    FROM TRPTVDTSaleByBillTemp TSB WITH(NOLOCK)
                    WHERE TSB.FTComName  = '$tComName'
                    AND TSB.FTRptCode    = '$tRptCode'
                    AND TSB.FTUsrSession = '$tUsrSession'
                ) AS B
            WHERE TRPTVDTSaleByBillTemp.FTRptRowSeq = B.FTRptRowSeq
            AND TRPTVDTSaleByBillTemp.FTComName    = '$tComName' 
            AND TRPTVDTSaleByBillTemp.FTRptCode    = '$tRptCode'
            AND TRPTVDTSaleByBillTemp.FTUsrSession = '$tUsrSession'
        ";
        $this->db->query($tSQL);
    }


}
