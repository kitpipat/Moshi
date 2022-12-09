<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mPromotionStep1ImportPmtExcel extends CI_Model
{
    /**
     * Functionality : Get Pdt Data
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Pdt Data
     * Return Type : array
     */
    public function FSaMGetDataPdt($paParams = []){

        $tPdtCode = $paParams['tPdtCode'];
        $tPunCode = $paParams['tPunCode'];
        $tBarCode = $paParams['tBarCode'];
        $nLngID = $paParams['nLngID'];
        $tUserSessionID = $paParams['tUserSessionID'];
        $tPmtGroupNameTmpOld = $paParams['tPmtGroupNameTmpOld'];

        $tSQL = "
            SELECT
                PDT.FTPdtCode,
                /*PDT.FTPdtStkCode,*/
                PDT.FTPdtStkControl,
                PDT.FTPdtGrpControl,
                PDT.FTPdtForSystem,
                PDT.FCPdtQtyOrdBuy,
                PDT.FCPdtCostDef,
                PDT.FCPdtCostOth,
                PDT.FCPdtCostStd,
                PDT.FCPdtMin,
                PDT.FCPdtMax,
                PDT.FTPdtPoint,
                PDT.FCPdtPointTime,
                PDT.FTPdtType,
                PDT.FTPdtSaleType,
                PDT.FTPdtSetOrSN,
                PDT.FTPdtStaSetPri,
                PDT.FTPdtStaSetShwDT,
                PDT.FTPdtStaAlwDis,
                PDT.FTPdtStaAlwReturn,
                PDT.FTPdtStaVatBuy,
                PDT.FTPdtStaVat,
                PDT.FTPdtStaActive,
                PDT.FTPdtStaAlwReCalOpt,
                PDT.FTPdtStaCsm,
                PDT.FTTcgCode,
                PDT.FTPtyCode,
                PDT.FTPbnCode,
                PDT.FTPmoCode,
                PDT.FTVatCode,
                PDT.FDPdtSaleStart,
                PDT.FDPdtSaleStop,

                PDTL.FTPdtName,
                PDTL.FTPdtNameOth,
                PDTL.FTPdtNameABB,
                PDTL.FTPdtRmk,

                PKS.FTPunCode,
                PKS.FCPdtUnitFact,

                VAT.FCVatRate,

                UNTL.FTPunName,

                BAR.FTBarCode,
                BAR.FTPlcCode,
                PDTLOCL.FTPlcName,

                PDTSRL.FTSrnCode,

                PDT.FCPdtCostStd,
                CAVG.FCPdtCostEx,
                CAVG.FCPdtCostIn,
                SPL.FCSplLastPrice

            FROM TCNMPdt PDT
            LEFT JOIN TCNMPdt_L PDTL ON PDT.FTPdtCode = PDTL.FTPdtCode AND PDTL.FNLngID = $nLngID
            LEFT JOIN TCNMPdtPackSize  PKS ON PDT.FTPdtCode = PKS.FTPdtCode AND PKS.FTPunCode = '$tPunCode'
            LEFT JOIN TCNMPdtUnit_L UNTL ON UNTL.FTPunCode = '$tPunCode' AND UNTL.FNLngID = $nLngID
            LEFT JOIN TCNMPdtBar BAR ON PKS.FTPdtCode = BAR.FTPdtCode AND BAR.FTPunCode = '$tPunCode' 
            LEFT JOIN TCNMPdtLoc_L PDTLOCL ON PDTLOCL.FTPlcCode = BAR.FTPlcCode AND PDTLOCL.FNLngID = $nLngID
            LEFT JOIN (
                SELECT FTVatCode, FCVatRate, FDVatStart   
                FROM TCNMVatRate WHERE GETdate()> FDVatStart
            ) VAT ON PDT.FTVatCode=VAT.FTVatCode 
            LEFT JOIN TCNTPdtSerial PDTSRL ON PDT.FTPdtCode = PDTSRL.FTPdtCode
            LEFT JOIN TCNMPdtSpl SPL ON PDT.FTPdtCode = SPL.FTPdtCode  AND BAR.FTBarCode = SPL.FTBarCode
            LEFT JOIN TCNMPdtCostAvg CAVG ON PDT.FTPdtCode = CAVG.FTPdtCode
            WHERE 1 = 1
            AND PDT.FTPdtCode NOT IN(SELECT FTPmdRefCode FROM TCNTPdtPmtDT_Tmp WHERE FTPmdRefCode = '$tPdtCode' AND FTPmdSubRef = '$tPunCode' AND FTPmdBarCode = '$tBarCode' /*AND FTPmdGrpName = '$tPmtGroupNameTmpOld'*/ AND FTSessionID = '$tUserSessionID')
        ";
        
        if($tPdtCode!= ""){
            $tSQL .= "AND PDT.FTPdtCode = '$tPdtCode'";
        }

        if($tBarCode!= ""){
            $tSQL .= "AND BAR.FTBarCode = '$tBarCode'";
        }
        
        $tSQL .= " ORDER BY FDVatStart DESC";
        
        $oQuery = $this->db->query($tSQL);

        return $oQuery->row_array();
    }

    /**
     * Functionality : Get Brand Data
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : Brand Data
     * Return Type : array
     */
    public function FSaMGetDataBrand($paParams = []){
        $tBrandCode = $paParams['tBrandCode'];
        $tModelCode = $paParams['tModelCode'];
        $nLngID = $paParams['nLngID'];
        $tUserSessionID = $paParams['tUserSessionID'];
        $tPmtGroupNameTmpOld = $paParams['tPmtGroupNameTmpOld'];

        $tSQL = "
            SELECT
                BRDL.FTPbnCode,
                BRDL.FTPbnName
            FROM TCNMPdtBrand_L BRDL WITH (NOLOCK)
            WHERE BRDL.FTPbnCode = '$tBrandCode'
            AND BRDL.FNLngID = $nLngID
            AND BRDL.FTPbnCode NOT IN(SELECT FTPmdRefCode FROM TCNTPdtPmtDT_Tmp WHERE FTPmdRefCode = '$tBrandCode' /*AND FTPmdGrpName = '$tPmtGroupNameTmpOld'*/ AND FTSessionID = '$tUserSessionID')
        ";
        
        $oQuery = $this->db->query($tSQL);
        $aBrand = $oQuery->row_array();

        $tSQL = "
            SELECT
                MODL.FTPmoCode,
                MODL.FTPmoName
            FROM TCNMPdtModel_L MODL WITH (NOLOCK)
            WHERE MODL.FTPmoCode = '$tModelCode'
            AND MODL.FNLngID = $nLngID
            AND MODL.FTPmoCode NOT IN(SELECT FTPmdSubRef FROM TCNTPdtPmtDT_Tmp WHERE FTPmdRefCode = '$tBrandCode' AND FTPmdSubRef = '$tModelCode' /*AND FTPmdGrpName = '$tPmtGroupNameTmpOld'*/ AND FTSessionID = '$tUserSessionID')
        ";
        
        $oQuery = $this->db->query($tSQL);
        $aModel = $oQuery->row_array();

        return array_merge(empty($aBrand)?[]:$aBrand, empty($aModel)?[]:$aModel);
    }
}
