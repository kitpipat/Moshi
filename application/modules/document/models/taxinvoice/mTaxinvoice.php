<?php
defined('BASEPATH') or exit('No direct script access allowed');

class mTaxinvoice extends CI_Model{

    //ข้อมูลใบกำกับภาษีอย่างย่อ แบบเลือก
    public function FSaMTAXGetListABB($paData){
        $aRowLen    = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $nLngID     = $paData['FNLngID'];
        $tSQL       = "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FDXshDocDate DESC) AS FNRowID,* FROM
                            (SELECT DISTINCT
                                TaxHD.FTXshDocNo,
                                FDXshDocDate,
                                FTPosCode,
                                FTXshCstName,
                                TaxAddr.FTAddName
                            FROM TPSTTaxHD TaxHD WITH (NOLOCK)
                            LEFT JOIN TPSTTaxHDCst HDCst WITH (NOLOCK) ON TaxHD.FTXshDocno = HDCst.FTXshDocno
                            LEFT JOIN TCNMTaxAddress_L TaxAddr WITH (NOLOCK) ON HDCst.FNXshAddrTax = TaxAddr.FNAddSeqNo
                            WHERE 1=1 AND ISNULL(FTXshDocVatFull,'') <> ''  ";

        if($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP'){
            $tBCH = $this->session->userdata("tSesUsrBchCom");
            $tSQL .= " AND  TaxHD.FTBchCode = '$tBCH' ";
        }

        //ค้นหาแบบพิเศษ
        @$tSearchList = $paData['tSearchAll'];
        $tSQL .= "  AND (
                        (TaxHD.FTXshDocNo LIKE '%$tSearchList%') 
                        OR (TaxHD.FDXshDocDate LIKE '%$tSearchList%') 
                        OR (TaxHD.FTPosCode LIKE '%$tSearchList%') 
                        OR (HDCst.FTXshCstName LIKE '%$tSearchList%') 
                        OR (CONVERT(CHAR(10),TaxHD.FDXshDocDate,120) = '$tSearchList')
                    )";
        
        
        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aFoundRow  = $this->FSnMTAXGetListABBPageAll($paData);
            $nFoundRow  = $aFoundRow[0]->counts;
            $nPageAll   = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult    = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //จำนวนใบกำกับภาษีอย่างย่อ แบบเลือก
    public function FSnMTAXGetListABBPageAll($paData){

        $nLngID = $paData['FNLngID'];
        $tSQL   = " SELECT COUNT (TaxHD.FTXshDocNo) AS counts
                    FROM TPSTTaxHD TaxHD WITH (NOLOCK) 
                    LEFT JOIN TPSTTaxHDCst HDCst WITH (NOLOCK) ON TaxHD.FTXshDocno = HDCst.FTXshDocno
                    LEFT JOIN TCNMTaxAddress_L TaxAddr WITH (NOLOCK) ON HDCst.FNXshAddrTax = TaxAddr.FNAddSeqNo
                    WHERE 1=1 AND ISNULL(FTXshDocVatFull,'') <> ''  ";

        if($this->session->userdata("tSesUsrLevel") == 'BCH' || $this->session->userdata("tSesUsrLevel") == 'SHP'){
            $tBCH = $this->session->userdata("tSesUsrBchCom");
            $tSQL .= " AND  TaxHD.FTBchCode = '$tBCH' ";
        }

        //ค้นหาแบบพิเศษ
        @$tSearchList = $paData['tSearchAll'];
        $tSQL .= "  AND (
                        (TaxHD.FTXshDocNo LIKE '%$tSearchList%') 
                        OR (TaxHD.FDXshDocDate LIKE '%$tSearchList%') 
                        OR (TaxHD.FTPosCode LIKE '%$tSearchList%') 
                        OR (HDCst.FTXshCstName LIKE '%$tSearchList%') 
                        OR (CONVERT(CHAR(10),TaxHD.FDXshDocDate,120) = '$tSearchList')
                    )";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //ข้อมูลใบกำกับภาษีอย่างย่อ แบบเลือก
    public function FSaMTAXListABB($paData){
        $aRowLen    = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $nLngID     = $paData['FNLngID'];
        $tSQL       = "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTXshDocNo DESC) AS FNRowID,* FROM
                            (SELECT DISTINCT
                                BCHL.FTBchName,
                                FTXshDocNo,
                                FNXshDocType,
                                FDXshDocDate,
                                FTXshCshOrCrd,
                                FTWahCode,
                                FTPosCode,
                                USRL.FTUsrName,
                                FCXshGrand,
                                SALHD.FTCstCode,
                                CST.FTCstName
                            FROM TPSTSalHD SALHD WITH (NOLOCK)
                            LEFT JOIN TCNMBranch_L  BCHL WITH (NOLOCK) ON SALHD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID 
                            LEFT JOIN TCNMUser_L    USRL WITH (NOLOCK) ON SALHD.FTUsrCode = USRL.FTUsrCode AND USRL.FNLngID = $nLngID 
                            LEFT JOIN TCNMCst_L     CST WITH (NOLOCK) ON SALHD.FTCstCode = CST.FTCstCode AND CST.FNLngID = $nLngID 
                            WHERE 1=1 AND ISNULL(FTXshDocVatFull,'') = ''  ";

        //ค้นหาตามสาขา
        @$tBCH       = $paData['tBCH'];
        if($tBCH != '' || $tBCH != null){
            $tSQL .= "  AND (( SALHD.FTBchCode = '$tBCH')) ";
        }

        //ค้นหาแบบพิเศษ
        @$tFilter       = $paData['tFilter'];
        @$tSearchList   = trim($paData['tSearchABB']);
        switch ($tFilter) {
            case "2": //ทั้งหมด
                $tSQL .= "  AND (
                                (SALHD.FTXshDocNo LIKE '%$tSearchList%') 
                                OR (BCHL.FTBchCode LIKE '%$tSearchList%') 
                                OR (USRL.FTUsrName LIKE '%$tSearchList%') 
                                OR (SALHD.FTPosCode LIKE '%$tSearchList%') 
                                OR (CONVERT(CHAR(10),SALHD.FDXshDocDate,120) = '$tSearchList')
                            )";
                break;
            case "3": //เลขที่
                $tSQL .= "  AND ((SALHD.FTXshDocNo = '$tSearchList'))";
                break;
            default:
        }

        $tTextDateABB   = trim($paData['tTextDateABB']);
        $tSQL .= "  AND ( (CONVERT(CHAR(10),SALHD.FDXshDocDate,120) = '$tTextDateABB') )";

        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aFoundRow  = $this->FSnMTAXGetPageAll($paData);
            $nFoundRow  = $aFoundRow[0]->counts;
            $nPageAll   = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult    = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
                'tSQL'          => $tSQL
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
                'tSQL'          => $tSQL
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //จำนวนใบกำกับภาษีอย่างย่อ แบบเลือก
    public function FSnMTAXGetPageAll($paData){

        $nLngID = $paData['FNLngID'];
        $tSQL   = " SELECT COUNT (SALHD.FTXshDocNo) AS counts
                    FROM TPSTSalHD SALHD WITH (NOLOCK) 
                    LEFT JOIN TCNMBranch_L  BCHL WITH (NOLOCK) ON SALHD.FTBchCode = BCHL.FTBchCode AND BCHL.FNLngID = $nLngID 
                    LEFT JOIN TCNMUser_L    USRL WITH (NOLOCK) ON SALHD.FTUsrCode = USRL.FTUsrCode AND USRL.FNLngID = $nLngID 
                    WHERE 1=1 AND ISNULL(FTXshDocVatFull,'') = ''  ";

        //ค้นหาตามสาขา
        @$tBCH       = $paData['tBCH'];
        if($tBCH != '' || $tBCH != null){
            $tSQL .= "  AND (( SALHD.FTBchCode = '$tBCH')) ";
        }

        //ค้นหาแบบพิเศษ
        @$tFilter       = $paData['tFilter'];
        @$tSearchList   = trim($paData['tSearchABB']);
        switch ($tFilter) {
            case "2": //ทั้งหมด
                $tSQL .= "  AND (
                                (SALHD.FTXshDocNo LIKE '%$tSearchList%') 
                                OR (BCHL.FTBchCode LIKE '%$tSearchList%') 
                                OR (USRL.FTUsrName LIKE '%$tSearchList%') 
                                OR (SALHD.FTPosCode LIKE '%$tSearchList%') 
                                OR (CONVERT(CHAR(10),SALHD.FDXshDocDate,120) = '$tSearchList')
                            )";
                break;
            case "3": //เลขที่
                $tSQL .= "  AND (SALHD.FTXshDocNo = '$tSearchList')";
                break;
            default:
        }

        $tTextDateABB   = trim($paData['tTextDateABB']);
        $tSQL .= "  AND ( (CONVERT(CHAR(10),SALHD.FDXshDocDate,120) = '$tTextDateABB') )";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //ค้นหารหัสใบกำกับภาษีอย่างย่อ แบบคีย์
    public function FSaMTAXCheckABBNumber($ptDocumentnumber,$tBCH){
        $nLngID = $this->session->userdata("tLangEdit");
        $tSQL   = "SELECT SALHD.FTCstCode , CSL.FTCstName FROM TPSTSalHD SALHD WITH (NOLOCK)
                   LEFT JOIN TCNMCst_L CSL ON SALHD.FTCstCode = CSL.FTCstCode AND CSL.FNLngID = $nLngID 
                   WHERE 1=1 AND SALHD.FTXshDocNo = '$ptDocumentnumber' AND ISNULL(SALHD.FTXshDocVatFull,'') = '' ";
        
        //ค้นหาตามสาขา
        @$tBCH       = $tBCH;
        if($tBCH != '' || $tBCH != null){
            $tSQL .= "  AND ( SALHD.FTBchCode = '$tBCH') ";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //ข้อมูลการขาย DT
    public function FSaMTAXGetDT($paData){
        $aRowLen            = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $tDocumentNumber    = $paData['tDocumentNumber'];
        $tSQL       = "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTXshDocNo DESC) AS FNRowID,* FROM
                            (SELECT DISTINCT
                                    DTDis.DISPMT , SALDT.FTBchCode , SALDT.FTXshDocNo , SALDT.FNXsdSeqNo , FTPdtCode , FTXsdPdtName ,
                                    FTPunCode , FTPunName , FCXsdFactor , FTXsdBarCode , FTSrnCode ,
                                    FTXsdVatType , FTVatCode , FTPplCode , FCXsdVatRate , FTXsdSaleType ,
                                    FCXsdSalePrice , FCXsdQty , FCXsdQtyAll , FCXsdSetPrice , FCXsdAmtB4DisChg ,
                                    FTXsdDisChgTxt , FCXsdDis , FCXsdChg , FCXsdNet , FCXsdNetAfHD ,
                                    FCXsdVat , FCXsdVatable , FCXsdWhtAmt , FTXsdWhtCode , FCXsdWhtRate ,
                                    FCXsdCostIn , FCXsdCostEx , FTXsdStaPdt , FCXsdQtyLef , FCXsdQtyRfn ,
                                    FTXsdStaPrcStk , FTXsdStaAlwDis , FNXsdPdtLevel , FTXsdPdtParent , FCXsdQtySet ,
                                    FTPdtStaSet , FTXsdRmk , FDLastUpdOn , FTLastUpdBy , FDCreateOn , FTCreateBy
                            FROM TPSTSalDT SALDT WITH (NOLOCK)
                            LEFT JOIN ( SELECT SUM(FCXddValue) as DISPMT , FNXsdSeqNo , FTBchCode FROM TPSTSalDTDis 
                                        WHERE FNXddStaDis = 2 AND FTXddDisChgType IN ('1','2') AND ISNULL(FTXddRefCode,'') <> ''
                                        AND TPSTSalDTDis.FTXshDocNo = '$tDocumentNumber'
                                        GROUP BY FNXsdSeqNo , FTBchCode
                                    ) DTDis ON DTDis.FNXsdSeqNo = SALDT.FNXsdSeqNo AND DTDis.FTBchCode = SALDT.FTBchCode
                            WHERE 1=1 AND SALDT.FTXshDocNo = '$tDocumentNumber' ";

        //ค้นหาแบบพิเศษ
        @$tSearchPDT   = $paData['tSearchPDT'];
        $tSQL .= "  AND ((SALDT.FTXshDocNo LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTPdtCode LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTXsdPdtName LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTPunName LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTXsdBarCode LIKE '%$tSearchPDT%') 
                        OR (SALDT.FCXsdQty LIKE '%$tSearchPDT%') 
                        OR (SALDT.FCXsdSetPrice LIKE '%$tSearchPDT%') 
                        OR (SALDT.FCXsdNetAfHD LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTBchCode LIKE '%$tSearchPDT%')
                    )";

        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aFoundRow  = $this->FSnMTAXGetDTPageAll($paData);
            $nFoundRow  = $aFoundRow[0]->counts;
            $nPageAll   = ceil($nFoundRow/$paData['nRow']);
            $aResult    = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //หาจำนวนการขาย DT
    public function FSnMTAXGetDTPageAll($paData){
        $tDocumentNumber    = $paData['tDocumentNumber'];
        $tSQL   = " SELECT COUNT (SALDT.FTXshDocNo) AS counts FROM TPSTSalDT SALDT WITH (NOLOCK) WHERE 1=1 AND FTXshDocNo = '$tDocumentNumber' ";

        //ค้นหาแบบพิเศษ
        @$tSearchPDT   = $paData['tSearchPDT'];
        $tSQL   .= "  AND ((SALDT.FTXshDocNo LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTPdtCode LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTXsdPdtName LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTPunName LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTXsdBarCode LIKE '%$tSearchPDT%') 
                        OR (SALDT.FCXsdQty LIKE '%$tSearchPDT%') 
                        OR (SALDT.FCXsdSetPrice LIKE '%$tSearchPDT%') 
                        OR (SALDT.FCXsdNetAfHD LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTBchCode LIKE '%$tSearchPDT%')
                    )";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //ข้อมูลการขาย HD
    public function FSaMTAXGetHD($paData){
        $tDocumentNumber    = $paData['tDocumentNumber'];
        $tSQL               = "SELECT
                                FTBchCode , FTXshDocNo , FTShpCode , FNXshDocType , FDXshDocDate ,
                                FTXshCshOrCrd , FTXshVATInOrEx , FTDptCode , FTWahCode , FTPosCode ,  FTShfCode ,
                                FNSdtSeqNo , FTUsrCode , FTSpnCode , FTXshApvCode ,  FTCstCode , ISNULL(FTXshDocVatFull,'') AS FTXshDocVatFull  ,
                                FTXshRefExt , FDXshRefExtDate , FTXshRefInt , FDXshRefIntDate , FTXshRefAE ,
                                FNXshDocPrint , FTRteCode , FCXshRteFac , FCXshTotal ,
                                FCXshTotalNV , FCXshTotalNoDis , FCXshTotalB4DisChgV , FCXshTotalB4DisChgNV ,
                                FTXshDisChgTxt , FCXshDis , FCXshChg ,  FCXshTotalAfDisChgV ,
                                FCXshTotalAfDisChgNV , FCXshRefAEAmt , FCXshAmtV , FCXshAmtNV ,
                                FCXshVat , FCXshVatable ,  FTXshWpCode , FCXshWpTax ,
                                FCXshGrand ,  FCXshRnd , FTXshGndText , FCXshPaid ,
                                FCXshLeft ,  FTXshRmk ,  FTXshStaRefund ,  FTXshStaDoc ,
                                FTXshStaApv , FTXshStaPrcStk , FTXshStaPaid ,  FNXshStaDocAct ,
                                FNXshStaRef ,  FDLastUpdOn , FTLastUpdBy , FDCreateOn , FTCreateBy
                            FROM TPSTSalHD SALHD WITH (NOLOCK)
                            WHERE 1=1 AND SALHD.FTXshDocNo = '$tDocumentNumber' 
                            --AND ISNULL(FTXshDocVatFull,'') = '' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aResult    = array(
                'raItems'       => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        }else{
            $aResult = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //หาที่อยู่ ที่ตาราง TCNMTaxAddress_L
    public function FSaMTAXFindAddress($ptCuscode){
        $tSQL   = " SELECT ADDL.* , CSTL.FTCstName , CST.FTCstTaxNo FROM TCNMTaxAddress_L ADDL WITH (NOLOCK) 
                    LEFT JOIN TCNMCst_L CSTL ON CSTL.FTCstCode = ADDL.FTCstCode 
                    LEFT JOIN TCNMCst CST  ON CST.FTCstCode = ADDL.FTCstCode  
                    WHERE 1=1 AND ADDL.FTCstCode = '$ptCuscode'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //หาที่อยู่ ที่ตาราง TCNMCstAddress_L
    public function FSaMTAXFindAddressCst($ptCutomerCode,$pnSeq){
        $tSQL   = " SELECT ADDL.*  , CSTL.FTCstName , CST.FTCstTaxNo AS FTAddTaxNo FROM TCNMCstAddress_L ADDL WITH (NOLOCK) 
                    LEFT JOIN TCNMCst_L CSTL ON CSTL.FTCstCode = ADDL.FTCstCode  
                    LEFT JOIN TCNMCst CST  ON CST.FTCstCode = ADDL.FTCstCode  
                    WHERE 1=1 AND ADDL.FTCstCode = '$ptCutomerCode' ";

        if(isset($pnSeq)){
            $tSQL   .= "AND ADDL.FNAddSeqNo = '$pnSeq' ";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //ค้นหาเลขที่ประจำตัวผู้เสียภาษี แบบคีย์
    public function FSaMTAXCheckTaxno($ptTaxno , $pnSeq){
        $tSQL   = "SELECT * FROM  TCNMTaxAddress_L Tax WITH (NOLOCK) LEFT JOIN TCNMCst_L ON TCNMCst_L.FTCstCode = Tax.FTCstCode WHERE 1=1 AND Tax.FTAddTaxNo = '$ptTaxno' ";
        if(isset($pnSeq)){
            $tSQL   .= "AND FNAddSeqNo = '$pnSeq' ";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //ค้นหาเลขที่ประจำตัวผู้เสียภาษี แบบเลือก
    public function FSaMTAXListTaxno($paData){
        $aRowLen    = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $nLngID     = $paData['FNLngID'];
        $tSQL       = "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTAddTaxNo DESC) AS FNRowID,* FROM
                            (SELECT DISTINCT
                                FTAddTaxNo , FNLngID , FNAddSeqNo , FTCstCode ,
                                FTAddName , FTAddRmk , FTAddCountry , FTAreCode ,
                                FTZneCode , FTAddVersion , FTAddV1No , FTAddV1Soi ,
                                FTAddV1Village , FTAddV1Road , FTAddV1SubDist , FTAddV1DstCode ,
                                FTAddV1PvnCode , FTAddV1PostCode , FTAddV2Desc1 , FTAddV2Desc2 ,
                                FTAddWebsite , FTAddLongitude , FTAddLatitude , FTAddStaBusiness ,
                                FTAddStaHQ , FTAddStaBchCode , FTAddTel , FTAddFax
                            FROM TCNMTaxAddress_L ADDL WITH (NOLOCK)
                            WHERE 1=1 AND FNLngID = '$nLngID' ";

        //ค้นหาแบบพิเศษ
        @$tSearchList  = trim($paData['tSearchTaxno']);
        if($tSearchList != '' || $tSearchList != null){
            $tSQL .= "  AND (
                (ADDL.FTAddTaxNo LIKE '%$tSearchList%') 
                OR (ADDL.FTCstCode LIKE '%$tSearchList%') 
                OR (ADDL.FTAddName LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1No LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1Soi LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1Village LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1Road LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1SubDist LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1DstCode LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1PvnCode LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1PostCode LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV2Desc1 LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV2Desc2 LIKE '%$tSearchList%') 
                OR (ADDL.FTAddStaBusiness LIKE '%$tSearchList%') 
                OR (ADDL.FTAddTel LIKE '%$tSearchList%') 
                OR (ADDL.FTAddFax LIKE '%$tSearchList%') 
            )";
        }
        
        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aFoundRow  = $this->FSnMTAXGetTaxnoPageAll($paData);
            $nFoundRow  = $aFoundRow[0]->counts;
            $nPageAll   = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult    = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //จำนวนเลขที่ประจำตัวผู้เสียภาษี แบบเลือก
    public function FSnMTAXGetTaxnoPageAll($paData){
        $nLngID     = $paData['FNLngID'];
        $tSQL       = " SELECT COUNT (ADDL.FTAddTaxNo) AS counts FROM TCNMTaxAddress_L ADDL WITH (NOLOCK) WHERE 1=1 AND FNLngID = '$nLngID' ";

        //ค้นหาแบบพิเศษ
        @$tSearchList  = trim($paData['tSearchTaxno']);
        if($tSearchList != '' || $tSearchList != null){
            $tSQL .= "  AND (
                (ADDL.FTAddTaxNo LIKE '%$tSearchList%') 
                OR (ADDL.FTCstCode LIKE '%$tSearchList%') 
                OR (ADDL.FTAddName LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1No LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1Soi LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1Village LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1Road LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1SubDist LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1DstCode LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1PvnCode LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1PostCode LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV2Desc1 LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV2Desc2 LIKE '%$tSearchList%') 
                OR (ADDL.FTAddStaBusiness LIKE '%$tSearchList%') 
                OR (ADDL.FTAddTel LIKE '%$tSearchList%') 
                OR (ADDL.FTAddFax LIKE '%$tSearchList%') 
            )";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //ค้นหาที่อยู่ของลูกค้า
    public function FSaMTAXListCustomerAddress($paData){
        $aRowLen        = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $nLngID         = $paData['FNLngID'];
        $tCustomerCode  = $paData['tCustomerCode'];
        $tSQL       = "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTCstCode DESC) AS FNRowID,* FROM
                            (SELECT DISTINCT
                                ADDL.FTCstCode , FTAddGrpType , FNAddSeqNo ,
                                FTAddRefNo , FTAddName , FTAddRmk ,  FTAddCountry , FTAreCode ,
                                FTZneCode , FTAddVersion , FTAddV1No ,
                                FTAddV1Soi , FTAddV1Village , FTAddV1Road , FTAddV1SubDist ,
                                FTAddV1DstCode , FTAddV1PvnCode , FTAddV1PostCode , FTAddV2Desc1 , 
                                FTAddV2Desc2 , FTAddWebsite , FTAddLongitude , FTAddLatitude , CST.FTCstName
                            FROM TCNMCstAddress_L ADDL WITH (NOLOCK)
                            LEFT JOIN TCNMCst_L CST ON ADDL.FTCstCode = CST.FTCstCode
                            WHERE 1=1 AND ADDL.FNLngID = '$nLngID' AND CST.FNLngID = '$nLngID' ";

        if($tCustomerCode != ''){
            $tSQL       .= "AND ADDL.FTCstCode = '$tCustomerCode' ";
        }

        //ค้นหาแบบพิเศษ
        @$tSearchList  = trim($paData['tSearchAddress']);
        if($tSearchList != '' || $tSearchList != null){
            $tSQL .= "  AND (
                (CST.FTCstName LIKE '%$tSearchList%') 
                OR (ADDL.FTCstCode LIKE '%$tSearchList%') 
                OR (ADDL.FTAddGrpType LIKE '%$tSearchList%') 
                OR (ADDL.FTAddRefNo LIKE '%$tSearchList%') 
                OR (ADDL.FTAddName LIKE '%$tSearchList%') 
                OR (ADDL.FTAddRmk LIKE '%$tSearchList%') 
                OR (ADDL.FTAddCountry LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1SubDist LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1DstCode LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1PvnCode LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1PostCode LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV2Desc1 LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV2Desc2 LIKE '%$tSearchList%') 
            )";
        }
        
        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aFoundRow  = $this->FSnMTAXGetCustomerAddressPageAll($paData);
            $nFoundRow  = $aFoundRow[0]->counts;
            $nPageAll   = ceil($nFoundRow/$paData['nRow']); //หา Page All จำนวน Rec หาร จำนวนต่อหน้า
            $aResult    = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //จำนวนที่อยู่ของลูกค้า
    public function FSnMTAXGetCustomerAddressPageAll($paData){
        $nLngID         = $paData['FNLngID'];
        $tCustomerCode  = $paData['tCustomerCode'];
        $tSQL       = " SELECT COUNT (ADDL.FTCstCode) AS counts FROM TCNMCstAddress_L ADDL 
                        LEFT JOIN TCNMCst_L CST ON ADDL.FTCstCode = CST.FTCstCode
                        WHERE 1=1 AND ADDL.FNLngID = '$nLngID' AND CST.FNLngID = '$nLngID' ";

        if($tCustomerCode != ''){
            $tSQL       .= "AND ADDL.FTCstCode = '$tCustomerCode' ";
        }

        //ค้นหาแบบพิเศษ
        @$tSearchList  = trim($paData['tSearchAddress']);
        if($tSearchList != '' || $tSearchList != null){
            $tSQL .= "  AND (
                (CST.FTCstName LIKE '%$tSearchList%') 
                OR (ADDL.FTCstCode LIKE '%$tSearchList%') 
                OR (ADDL.FTAddGrpType LIKE '%$tSearchList%') 
                OR (ADDL.FTAddRefNo LIKE '%$tSearchList%') 
                OR (ADDL.FTAddName LIKE '%$tSearchList%') 
                OR (ADDL.FTAddRmk LIKE '%$tSearchList%') 
                OR (ADDL.FTAddCountry LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1SubDist LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1DstCode LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1PvnCode LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV1PostCode LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV2Desc1 LIKE '%$tSearchList%') 
                OR (ADDL.FTAddV2Desc2 LIKE '%$tSearchList%') 
            )";
        }

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //////////////////////////////////// MOVE DATA ////////////////////////////////
    
    // TPSTSalHD -> TPSTTaxHD
    public function  FSaMTAXMoveSalHD_TaxHD($aPackData){
        $tABB               = $aPackData['tDocABB'];
        $tTaxNumberFull     = $aPackData['tTaxNumberFull'];

        //เพิ่มเติม
        $dDateCurrent       = date('Y-m-d H:i:s');
        $tNameTask          = $this->session->userdata('tSesUsername');
        $dDocDateTime       = $aPackData['dDocDate'] .' '. $aPackData['dDocTime'];
        $tRemark            = $aPackData['tReason'];

        $tSQL       = " INSERT INTO TPSTTaxHD (
                            FTBchCode,FTXshDocNo,FTShpCode,FNXshDocType,FDXshDocDate,
                            FTXshCshOrCrd,FTXshVATInOrEx,FTDptCode,FTWahCode,
                            FTPosCode,FTShfCode,FNSdtSeqNo,FTUsrCode,FTSpnCode,
                            FTXshApvCode,FTCstCode,FTXshDocVatFull,FTXshRefExt,
                            FDXshRefExtDate,FTXshRefInt,FDXshRefIntDate,FTXshRefAE,
                            FNXshDocPrint,FTRteCode,FCXshRteFac,FCXshTotal,FCXshTotalNV,FCXshTotalNoDis,
                            FCXshTotalB4DisChgV,FCXshTotalB4DisChgNV,FTXshDisChgTxt,FCXshDis,
                            FCXshChg,FCXshTotalAfDisChgV,FCXshTotalAfDisChgNV,FCXshRefAEAmt,
                            FCXshAmtV,FCXshAmtNV,FCXshVat,FCXshVatable,FTXshWpCode,FCXshWpTax,
                            FCXshGrand,FCXshRnd,FTXshGndText,FCXshPaid,FCXshLeft,FTXshRmk,
                            FTXshStaRefund,FTXshStaDoc,FTXshStaApv,FTXshStaPrcStk,
                            FTXshStaPaid,FNXshStaDocAct,FNXshStaRef,FDLastUpdOn,
                            FTLastUpdBy,FDCreateOn,FTCreateBy
                        ) SELECT 
                            FTBchCode,'$tTaxNumberFull',FTShpCode,
                            CASE
                                WHEN FNXshDocType = 9 THEN 5
                                WHEN FNXshDocType = 1 THEN 4 
                                ELSE 0
                            END AS FNXshDocType,
                            '$dDocDateTime',
                            FTXshCshOrCrd,FTXshVATInOrEx,FTDptCode,FTWahCode,
                            FTPosCode,FTShfCode,FNSdtSeqNo,FTUsrCode,FTSpnCode,
                            FTXshApvCode,FTCstCode,FTXshDocVatFull,
                            '$tABB' AS FTXshRefExt,
                            FDXshRefExtDate,FTXshRefInt,FDXshRefIntDate,FTXshRefAE,
                            FNXshDocPrint,FTRteCode,FCXshRteFac,FCXshTotal,FCXshTotalNV,FCXshTotalNoDis,
                            FCXshTotalB4DisChgV,FCXshTotalB4DisChgNV,FTXshDisChgTxt,FCXshDis,
                            FCXshChg,FCXshTotalAfDisChgV,FCXshTotalAfDisChgNV,FCXshRefAEAmt,
                            FCXshAmtV,FCXshAmtNV,FCXshVat,FCXshVatable,FTXshWpCode,FCXshWpTax,
                            FCXshGrand,FCXshRnd,FTXshGndText,FCXshPaid,FCXshLeft,'$tRemark',
                            FTXshStaRefund,FTXshStaDoc,1 AS FTXshStaApv,FTXshStaPrcStk,
                            FTXshStaPaid,FNXshStaDocAct,FNXshStaRef,FDLastUpdOn,
                            FTLastUpdBy,'$dDateCurrent','$tNameTask'
                        FROM TPSTSalHD WHERE FTXshDocNo = '$tABB' ";
        $this->db->query($tSQL);
    }

    // TPSTSalHDDis -> TPSTTaxHDDis
    public function FSaMTAXMoveSalHDDis_TaxHDDis($aPackData){
        $tABB               = $aPackData['tDocABB'];
        $tTaxNumberFull     = $aPackData['tTaxNumberFull'];
        $tSQL       = " INSERT INTO TPSTTaxHDDis (
                            FTBchCode,FTXshDocNo,FDXhdDateIns,FTXhdDisChgTxt,FTXhdDisChgType,FCXhdTotalAfDisChg,FCXhdDisChg,FCXhdAmt,FTXshRefCode
                        ) SELECT 
                            FTBchCode,'$tTaxNumberFull',FDXhdDateIns,FTXhdDisChgTxt,FTXhdDisChgType,FCXhdTotalAfDisChg,FCXhdDisChg,FCXhdAmt,FTXhdRefCode
                        FROM TPSTSalHDDis WHERE FTXshDocNo = '$tABB' ";
        $this->db->query($tSQL);
    }

    // TPSTSalDT -> TPSTTaxDT
    public function FSaMTAXMoveSalDT_TaxDT($aPackData){
        $tABB       = $aPackData['tDocABB'];
        $tTaxNumberFull     = $aPackData['tTaxNumberFull'];
        $tSQL       = " INSERT INTO TPSTTaxDT (
                            FTBchCode,FTXshDocNo,FNXsdSeqNo,FTPdtCode,FTXsdPdtName,
                            FTPunCode,FTPunName,FTPplCode,FCXsdFactor,FTXsdBarCode,
                            FTSrnCode,FTXsdVatType,FTVatCode,FCXsdVatRate,FTXsdSaleType,
                            FCXsdSalePrice,FCXsdQty,FCXsdQtyAll,FCXsdSetPrice,FCXsdAmtB4DisChg,FTXsdDisChgTxt,
                            FCXsdDis,FCXsdChg,FCXsdNet,FCXsdNetAfHD,
                            FCXsdVat,FCXsdVatable,FCXsdWhtAmt,FTXsdWhtCode,
                            FCXsdWhtRate,FCXsdCostIn,FCXsdCostEx,FTXsdStaPdt,
                            FCXsdQtyLef,FCXsdQtyRfn,FTXsdStaPrcStk,FTXsdStaAlwDis,
                            FNXsdPdtLevel,FTXsdPdtParent,FCXsdQtySet,FTPdtStaSet,
                            FTXsdRmk,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy
                        ) SELECT 
                            FTBchCode,'$tTaxNumberFull',FNXsdSeqNo,FTPdtCode,FTXsdPdtName,
                            FTPunCode,FTPunName,FTPplCode,FCXsdFactor,FTXsdBarCode,
                            FTSrnCode,FTXsdVatType,FTVatCode,FCXsdVatRate,FTXsdSaleType,
                            FCXsdSalePrice,FCXsdQty,FCXsdQtyAll,FCXsdSetPrice,FCXsdAmtB4DisChg,FTXsdDisChgTxt,
                            FCXsdDis,FCXsdChg,FCXsdNet,FCXsdNetAfHD,
                            FCXsdVat,FCXsdVatable,FCXsdWhtAmt,FTXsdWhtCode,
                            FCXsdWhtRate,FCXsdCostIn,FCXsdCostEx,FTXsdStaPdt,
                            FCXsdQtyLef,FCXsdQtyRfn,FTXsdStaPrcStk,FTXsdStaAlwDis,
                            FNXsdPdtLevel,FTXsdPdtParent,FCXsdQtySet,FTPdtStaSet,
                            FTXsdRmk,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy 
                        FROM TPSTSalDT WHERE FTXshDocNo = '$tABB' ";
        $this->db->query($tSQL);
    }

    // TPSTSalDTDis -> TPSTTaxDTDis
    public function FSaMTAXMoveSalDTDis_TaxDTDis($aPackData){
        $tTaxNumberFull     = $aPackData['tTaxNumberFull'];
        $tABB       = $aPackData['tDocABB'];
        $tSQL       = " INSERT INTO TPSTTaxDTDis (
                            FTBchCode,FTXshDocNo,FNXsdSeqNo,FDXddDateIns,FNXddStaDis,
                            FTXddDisChgTxt,FTXddDisChgType,FCXddNet,FCXddValue,FTXddRefCode
                        ) SELECT 
                            FTBchCode,'$tTaxNumberFull',FNXsdSeqNo,FDXddDateIns,FNXddStaDis,
                            FTXddDisChgTxt,FTXddDisChgType,FCXddNet,FCXddValue,FTXddRefCode
                        FROM TPSTSalDTDis WHERE FTXshDocNo = '$tABB' ";
        $this->db->query($tSQL);
    }

    // TPSTSalHDCst -> TPSTTaxHDCst
    public function FSaMTAXMoveSalHDCst_TaxHDCst($aPackData){
        $tABB       = $aPackData['tDocABB'];
        $tTaxNumberFull     = $aPackData['tTaxNumberFull'];

        //ถ้าไปเจอลูกค้า จะ move ลงตาราง Tax เลย
        $tSQL       = "SELECT ISNULL(FTCstCode,'') AS FTCstCode FROM TPSTSalHD SAL WITH (NOLOCK)  WHERE FTXshDocNo = '$tABB'";
        $oQuery     = $this->db->query($tSQL);
        $aResult    = $oQuery->result();
        $tCstCode   = $aResult[0]->FTCstCode;
        if($tCstCode == '' || $tCstCode == null){

            $tCusCodeForm   = $aPackData['tCstCode'];
            $tCstName       = $aPackData['tCstName'];
            if( $tCusCodeForm == '' ||  $tCusCodeForm == null){
                //ไม่มีการเลือกลูกค้า และ ไม่มีลูกค้าอยู่ใน ABB
                $tSQL       = " INSERT INTO TPSTTaxHDCst (
                                    FTBchCode,
                                    FTXshDocNo,
                                    FTXshCstName
                                ) SELECT 
                                    (SELECT FTBchCode FROM TPSTSalHD SAL WITH (NOLOCK) WHERE FTXshDocNo = '$tABB') AS FTBchCode,
                                    '$tTaxNumberFull',
                                    'ลูกค้าทั่วไป' ";
            }else{
                //ในหน้าจอมีการเลือกลูกค้า
                $tSQL       = " INSERT INTO TPSTTaxHDCst (
                                    FTBchCode,
                                    FTXshDocNo,
                                    FTXshCstName
                                ) SELECT 
                                    (SELECT FTBchCode FROM TPSTSalHD SAL WITH (NOLOCK) WHERE FTXshDocNo = '$tABB') AS FTBchCode,
                                    '$tTaxNumberFull',
                                    '$tCstName' ";
            }
            $this->db->query($tSQL);
        }else{
            $tCstName   = $aPackData['tCstName'];
            $tSQL       = " INSERT INTO TPSTTaxHDCst (
                                FTBchCode,FTXshDocNo,FTXshCardID,FTXshCardNo,FNXshCrTerm,FDXshDueDate,
                                FDXshBillDue,FTXshCtrName,FDXshTnfDate,FTXshRefTnfID,FNXshAddrShip,FNXshAddrTax,
                                FTXshCstName,FTXshCstTel
                            ) SELECT 
                                FTBchCode,'$tTaxNumberFull',FTXshCardID,FTXshCardNo,FNXshCrTerm,FDXshDueDate,
                                FDXshBillDue,FTXshCtrName,FDXshTnfDate,FTXshRefTnfID,FNXshAddrShip,FNXshAddrTax,
                                '$tCstName',FTXshCstTel
                            FROM TPSTSalHDCst WHERE FTXshDocNo = '$tABB' ";
            $this->db->query($tSQL);
        }
    }

    // TPSTSalPD -> TPSTTaxPD
    public function FSaMTAXMoveSalPD_TaxPD($aPackData){
        $tABB       = $aPackData['tDocABB'];
        $tTaxNumberFull     = $aPackData['tTaxNumberFull'];

        $tSQL       = " INSERT INTO TPSTTaxPD (
                            FTBchCode,FTXshDocNo,FTPmhDocNo,FNXsdSeqNo,
                            FTPmdGrpName,FTPdtCode,FTPunCode,FCXsdQty,FCXsdQtyAll,
                            FCXsdSetPrice,FCXsdNet,FCXpdGetQtyDiv,FTXpdGetType,FCXpdGetValue,
                            FCXpdDis,FCXpdPerDisAvg,FCXpdDisAvg,FCXpdPoint,FTXpdStaRcv,FTPplCode,
                            FTXpdCpnText,FTCpdBarCpn
                        ) SELECT 
                            FTBchCode,'$tTaxNumberFull',FTPmhDocNo,FNXsdSeqNo,
                            FTPmdGrpName,FTPdtCode,FTPunCode,FCXsdQty,FCXsdQtyAll,
                            FCXsdSetPrice,FCXsdNet,FCXpdGetQtyDiv,FTXpdGetType,FCXpdGetValue,
                            FCXpdDis,FCXpdPerDisAvg,FCXpdDisAvg,FCXpdPoint,FTXpdStaRcv,FTPplCode,
                            FTXpdCpnText,FTCpdBarCpn
                        FROM TPSTSalPD WHERE FTXshDocNo = '$tABB' ";
        $this->db->query($tSQL);
    }

    // TPSTSalRC -> TPSTTaxRC
    public function FSaMTAXMoveSalRC_TaxRC($aPackData){
        $tTaxNumberFull     = $aPackData['tTaxNumberFull'];
        $tABB               = $aPackData['tDocABB'];

        //เพิ่มเติม
        $dDateCurrent       = date('Y-m-d H:i:s');
        $tNameTask          = $this->session->userdata('tSesUsername');
        $dDocDateTime       = $aPackData['dDocDate'] .' '. $aPackData['dDocTime'];
        $tRemark            = $aPackData['tReason'];

        $tSQL       = " INSERT INTO TPSTTaxRC (
                            FTBchCode,FTXshDocNo,FNXrcSeqNo,FTRcvCode,FTRcvName,
                            FTXrcRefNo1,FTXrcRefNo2,FDXrcRefDate,FTXrcRefDesc,FTBnkCode,
                            FTRteCode,FCXrcRteFac,FCXrcFrmLeftAmt,FCXrcUsrPayAmt,FCXrcDep,
                            FCXrcNet,FCXrcChg,FTXrcRmk,FTPhwCode,FTXrcRetDocRef,
                            FTXrcStaPayOffline,FDLastUpdOn,FTLastUpdBy,FDCreateOn,FTCreateBy
                        ) SELECT 
                            FTBchCode,'$tTaxNumberFull',FNXrcSeqNo,FTRcvCode,FTRcvName,
                            FTXrcRefNo1,FTXrcRefNo2,FDXrcRefDate,FTXrcRefDesc,FTBnkCode,
                            FTRteCode,FCXrcRteFac,FCXrcFrmLeftAmt,FCXrcUsrPayAmt,FCXrcDep,
                            FCXrcNet,FCXrcChg,FTXrcRmk,FTPhwCode,FTXrcRetDocRef,
                            FTXrcStaPayOffline,FDLastUpdOn,FTLastUpdBy,'$dDateCurrent','$tNameTask'
                        FROM TPSTSalRC WHERE FTXshDocNo = '$tABB' ";
        $this->db->query($tSQL);
    }

    // TPSTSalRD -> TPSTTaxRD 
    public function FSaMTAXMoveSalRD_TaxRD($aPackData){
        $tTaxNumberFull     = $aPackData['tTaxNumberFull'];
        $tABB       = $aPackData['tDocABB'];
        $tSQL       = " INSERT INTO TPSTTaxRD (
                            FTBchCode,FTXshDocNo,FNXrdSeqNo,FTRdhDocType,FNXrdRefSeq,FTXrdRefCode,FCXrdPdtQty,FNXrdPntUse
                        ) SELECT 
                            FTBchCode,'$tTaxNumberFull',FNXrdSeqNo,FTRdhDocType,FNXrdRefSeq,FTXrdRefCode,FCXrdPdtQty,FNXrdPntUse
                        FROM TPSTSalRD WHERE FTXshDocNo = '$tABB' ";
        $this->db->query($tSQL);
    }

    //อัพเดท ว่าเอกสารนี้ถูกใช้งานเเล้ว
    public function FSaMTAXUpdateDocVatFull($aPackData){
        $tTaxNumberFull     = $aPackData['tTaxNumberFull'];
        $tABB               = $aPackData['tDocABB'];
        $dDocDate           = $aPackData['dDocDate'];
        $dDocTime           = $aPackData['dDocTime'];
        $dDateCurrent       = date('Y-m-d H:i:s');
        $tNameTask          = $this->session->userdata('tSesUsername');

        $tSQL       = " UPDATE TPSTSalHD SET FTXshDocVatFull = '$tTaxNumberFull' , FDLastUpdOn = '$dDateCurrent' , FTLastUpdBy = '$tNameTask' WHERE FTXshDocNo = '$tABB' ";
        $this->db->query($tSQL);

        $tSQL       = " UPDATE TPSTTaxHD SET 
                            FTXshDocVatFull = '$tTaxNumberFull' , 
                            FDLastUpdOn = '$dDateCurrent' , 
                            FTLastUpdBy = '$tNameTask' 
                        WHERE FTXshDocNo = '$tTaxNumberFull' ";
        $this->db->query($tSQL);
    }

    //เพิ่มข้อมูลที่อยู่ใหม่
    public function FSaMTAXInsertTaxAddress($aPackData){
        $nLngID             = $this->session->userdata("tLangEdit");
        $tTaxNumberFull     = $aPackData['tTaxNumberFull'];
        $FTAddTaxNo         = $aPackData['tTaxnumber'];
        $FTCstCode          = $aPackData['tCstCode'];
        $FTAddName          = $aPackData['tCstNameABB'];
        $FTAddVersion       = 2;
        $FTAddV2Desc1       = $aPackData['tAddress1'];
        $FTAddV2Desc2       = $aPackData['tAddress2'];
        $FTAddStaBusiness   = $aPackData['tTypeBusiness'];
        $FTAddStaHQ         = $aPackData['tBusiness'];
        $FTAddStaBchCode    = $aPackData['tBranch'];
        $FTAddTel           = $aPackData['tTel'];
        $FTAddFax           = $aPackData['tFax'];
        $FNAddSeqNo         = $aPackData['tSeqAddress'];
        $FDLastUpdOn        = date('Y-m-d H:i:s');
        $FTLastUpdBy        = $this->session->userdata('tSesUsername');
        $FDCreateOn         = date('Y-m-d H:i:s');
        $FTCreateBy         = $this->session->userdata('tSesUsername');


        //วิ่งเข้าไปเช็ค 3 Key ว่า ตรงไหม FTAddTaxNo / FNAddSeqNo / FTAddStaBchCode
        $tSQL   = "SELECT * FROM  TCNMTaxAddress_L Tax WITH (NOLOCK) WHERE 1=1 
                  AND Tax.FTAddTaxNo = '$FTAddTaxNo'
                  AND Tax.FNAddSeqNo = '$FNAddSeqNo'
                  AND Tax.FTAddStaBchCode = '$FTAddStaBchCode' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            $tStatusFound = 'Found'; //found -> update address
        }else{
            $tStatusFound = 'NotFound'; //not found -> insert address
        }

        if($tStatusFound == 'Found'){
            //Update
            $tSQL       = " UPDATE TCNMTaxAddress_L SET 
                                FNLngID = '$nLngID',
                                FTCstCode = '$FTCstCode',
                                FTAddName = '$FTAddName', 
                                FTAddVersion = '$FTAddVersion',
                                FTAddV2Desc1 = '$FTAddV2Desc1', 
                                FTAddV2Desc2 = '$FTAddV2Desc2', 
                                FTAddStaBusiness = '$FTAddStaBusiness',
                                FTAddStaHQ = '$FTAddStaHQ', 
                                FTAddTel = '$FTAddTel',
                                FTAddFax = '$FTAddFax',
                                FDLastUpdOn = '$FDLastUpdOn', 
                                FTLastUpdBy = '$FTLastUpdBy'
                            WHERE 1=1 
                            AND FTAddTaxNo = '$FTAddTaxNo'
                            AND FNAddSeqNo = '$FNAddSeqNo'
                            AND FTAddStaBchCode = '$FTAddStaBchCode' ";
            $this->db->query($tSQL);
            $nSeqLast = $FNAddSeqNo;
        }else{
            //Insert
            $tSQL       = "INSERT INTO TCNMTaxAddress_L (
                                FTAddTaxNo , FNLngID , 
                                FTCstCode , FTAddName , FTAddVersion ,
                                FTAddV2Desc1 , FTAddV2Desc2 , FTAddStaBusiness ,
                                FTAddStaHQ , FTAddStaBchCode , FTAddTel , FTAddFax ,
                                FDLastUpdOn , FTLastUpdBy , FDCreateOn , FTCreateBy 
                            )
                            VALUES (
                                '$FTAddTaxNo' , '$nLngID' , 
                                '$FTCstCode' , '$FTAddName' , '$FTAddVersion' ,
                                '$FTAddV2Desc1' , '$FTAddV2Desc2' , '$FTAddStaBusiness' ,
                                '$FTAddStaHQ' , '$FTAddStaBchCode' , '$FTAddTel' , '$FTAddFax' ,
                                '$FDLastUpdOn' , '$FTLastUpdBy' , '$FDCreateOn' , '$FTCreateBy' 
                            )";
                            $this->db->query($tSQL);

            //หาข้อมูลว่า SEQ ที่เพิ่มเข้าไปใช้อะไร
            $tSQL       = "SELECT TOP 1 FNAddSeqNo FROM TCNMTaxAddress_L WHERE FTAddTaxNo = '$FTAddTaxNo' ORDER BY FNAddSeqNo DESC";
            $oQuery     = $this->db->query($tSQL);
            $aResult    = $oQuery->result();
            $nSeqLast   = $aResult[0]->FNAddSeqNo;
        }

        //อัพเดทข้อมูล SEQ -> TPSTTaxHDCst
        $tSQL       = "UPDATE TPSTTaxHDCst SET FNXshAddrTax = '$nSeqLast' WHERE FTXshDocNo = '$tTaxNumberFull' ";
        $this->db->query($tSQL);
    }

    ///////////////////////////////////// PREVIEW /////////////////////////////////////

    //หาเอกสารที่ HD ถูกอนุมัติเเล้ว
    public function FSaMTAXGetHDTax($ptDocument){
        $tSQL   = "SELECT Tax.* , HD.FTXshDocNo AS DocABB FROM  TPSTTaxHD Tax WITH (NOLOCK) 
                    LEFT JOIN TPSTSalHD HD ON Tax.FTXshDocVatFull = HD.FTXshDocVatFull AND Tax.FTBchCode = HD.FTBchCode
                    WHERE 1=1 AND Tax.FTXshDocNo = '$ptDocument' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aResult    = array(
                'raItems'       => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        }else{
            $aResult = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //หาเอกสารที่ DT ถูกอนุมัติเเล้ว
    public function FSaMTAXGetDTTax($ptDocument){
        $tSQL   = "SELECT * FROM  TPSTTaxDT Tax WITH (NOLOCK) WHERE 1=1 AND Tax.FTXshDocNo = '$ptDocument' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //หาเอกสารที่ ADDRESS ถูกอนุมัติเเล้ว
    public function FSaMTAXGetAddressTax($ptDocument){
        $tSQL   = "SELECT TaxAdd.* , HDCst.FTXshCstName FROM  TPSTTaxHDCst HDCst WITH (NOLOCK) 
                   LEFT JOIN TCNMTaxAddress_L TaxAdd ON HDCst.FNXshAddrTax = TaxAdd.FNAddSeqNo
                   WHERE 1=1 AND HDCst.FTXshDocNo = '$ptDocument' ";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            return false;
        }
    }

    //อนุญาติว่า ปริ้น from ได้กี่ใบ
    public function FSaMTAXGetConfig(){
        $tSQL   = "SELECT FTSysStaUsrValue , FTSysStaUsrRef FROM TSysConfig WHERE FTSysCode = 'nPS_PrnTax' AND FTSysKEY = 'Tax'";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            return false;
        }
    }

    public function FSaMTAXUpdateWhenApprove($aWhere,$aSet,$ptType){
        $FTAddName          = $aSet['FTAddName'];
        $FTAddTel           = $aSet['FTAddTel'];
        $FTAddFax           = $aSet['FTAddFax'];
        $FTAddV2Desc1       = $aSet['FTAddV2Desc1'];
        $FTAddV2Desc2       = $aSet['FTAddV2Desc2'];
        $dDateCurrent       = date('Y-m-d H:i:s');
        $tNameTask          = $this->session->userdata('tSesUsername');
        $FNAddSeqNo         = $aWhere['FNAddSeqNo'];
        $tDocumentNo        = $aSet['tDocumentNo'];
        $nLngID             = $this->session->userdata("tLangEdit");


        if($ptType == 'UPDATEADDRESS'){
            // $tSQL   = " UPDATE TCNMTaxAddress_L 
            //         SET FTAddName = '$FTAddName' , 
            //             FTAddTel = '$FTAddTel' ,
            //             FTAddFax  = '$FTAddFax' ,
            //             FTAddV2Desc1 = '$FTAddV2Desc1',
            //             FTAddV2Desc2 = '$FTAddV2Desc2',
            //             FDLastUpdOn = '$dDateCurrent' , 
            //             FTLastUpdBy = '$tNameTask' 
            //         WHERE FNAddSeqNo = '$FNAddSeqNo' ";
        }else{

            $tNumberTax             = $aSet['tNumberTax'];
            $tNumberTaxNew          = $aSet['tNumberTaxNew'];
            $tTypeBusiness          = $aSet['tTypeBusiness'];
            $tBusiness              = $aSet['tBusiness'];
            $tBchCode               = $aSet['tBchCode'];
            $tCstCode               = $aSet['tCstCode'];
            $tCstName               = $aSet['tCstName'];

            $tSQLFind   = "SELECT TOP 1 FNAddSeqNo FROM TCNMTaxAddress_L WHERE FTAddTaxNo = '$tNumberTax' ";
            $oQuery     = $this->db->query($tSQLFind);
            if ($oQuery->num_rows() > 0) {
                $tFindAddress   = 1;
                $aResult        = $oQuery->result();
                $nSeqLast       = $aResult[0]->FNAddSeqNo;
            }else{
                $tFindAddress   = 0;
            }

            if($tFindAddress == 1){

                if($tCstCode == '' || $tCstCode == null){
                    $tSQL       = " UPDATE TPSTTaxHDCst SET FNXshAddrTax = '$nSeqLast' WHERE FTXshDocNo = '$tDocumentNo' ";
                }else{
                    $tSQL       = " UPDATE TPSTTaxHDCst SET FNXshAddrTax = '$nSeqLast' , FTXshCstName  = '$tCstName' WHERE FTXshDocNo = '$tDocumentNo' ";
                }

                $tSQLADD    = " UPDATE TCNMTaxAddress_L 
                                SET FTAddName = '$FTAddName' , 
                                    FTAddTel = '$FTAddTel' ,
                                    FTAddFax  = '$FTAddFax' ,
                                    FTAddV2Desc1 = '$FTAddV2Desc1',
                                    FTAddV2Desc2 = '$FTAddV2Desc2',
                                    FDLastUpdOn = '$dDateCurrent' , 
                                    FTAddStaHQ = '$tBusiness',
                                    FTAddStaBchCode = '$tBchCode',
                                    FTAddStaBusiness =  '$tTypeBusiness',
                                    FTLastUpdBy = '$tNameTask' 
                                WHERE FNAddSeqNo = '$nSeqLast' ";
                $this->db->query($tSQLADD);
            }else{  
                //Insert
                $tSQLIns = "INSERT INTO TCNMTaxAddress_L (
                    FTAddTaxNo , FNLngID , 
                    FTCstCode , FTAddName , FTAddVersion ,
                    FTAddV2Desc1 , FTAddV2Desc2 , FTAddStaBusiness ,
                    FTAddStaHQ , FTAddStaBchCode , FTAddTel , FTAddFax ,
                    FDLastUpdOn , FTLastUpdBy , FDCreateOn , FTCreateBy 
                )
                VALUES (
                    '$tNumberTax' , '$nLngID' , 
                    '$tCstCode' , '$FTAddName' , '2' ,
                    '$FTAddV2Desc1' , '$FTAddV2Desc2' , '$tTypeBusiness' ,
                    '$tBusiness' , '$tBchCode' , '$FTAddTel' , '$FTAddFax' ,
                    '$dDateCurrent' , '$tNameTask' , '$dDateCurrent' , '$tNameTask' 
                )";
                $this->db->query($tSQLIns);

                //หาข้อมูลว่า SEQ ที่เพิ่มเข้าไปใช้อะไร
                $tSQL       = "SELECT TOP 1 FNAddSeqNo FROM TCNMTaxAddress_L WHERE FTAddTaxNo = '$tNumberTax' ORDER BY FNAddSeqNo DESC";
                $oQuery     = $this->db->query($tSQL);
                $aResult    = $oQuery->result();
                $nSeqLast   = $aResult[0]->FNAddSeqNo;

                if($tCstCode == '' || $tCstCode == null){
                    $tSQL       = " UPDATE TPSTTaxHDCst SET FNXshAddrTax = '$nSeqLast' WHERE FTXshDocNo = '$tDocumentNo' ";
                }else{
                    $tSQL       = " UPDATE TPSTTaxHDCst SET FNXshAddrTax = '$nSeqLast' , FTXshCstName  = '$tCstName' WHERE FTXshDocNo = '$tDocumentNo' ";
                }
            }
        };
        $this->db->query($tSQL);
    }

    //ข้อมูลการขาย DT
    public function FSaMTAXGetDTInTax($paData){
        $aRowLen            = FCNaHCallLenData($paData['nRow'],$paData['nPage']);
        $tDocumentNumber    = $paData['tDocumentNumber'];
        $tSQL       = "SELECT c.* FROM(
                        SELECT  ROW_NUMBER() OVER(ORDER BY FTXshDocNo DESC) AS FNRowID,* FROM
                            (SELECT DISTINCT
                                    DTDis.DISPMT , SALDT.FTBchCode , SALDT.FTXshDocNo , SALDT.FNXsdSeqNo , FTPdtCode , FTXsdPdtName ,
                                    FTPunCode , FTPunName , FCXsdFactor , FTXsdBarCode , FTSrnCode ,
                                    FTXsdVatType , FTVatCode , FTPplCode , FCXsdVatRate , FTXsdSaleType ,
                                    FCXsdSalePrice , FCXsdQty , FCXsdQtyAll , FCXsdSetPrice , FCXsdAmtB4DisChg ,
                                    FTXsdDisChgTxt , FCXsdDis , FCXsdChg , FCXsdNet , FCXsdNetAfHD ,
                                    FCXsdVat , FCXsdVatable , FCXsdWhtAmt , FTXsdWhtCode , FCXsdWhtRate ,
                                    FCXsdCostIn , FCXsdCostEx , FTXsdStaPdt , FCXsdQtyLef , FCXsdQtyRfn ,
                                    FTXsdStaPrcStk , FTXsdStaAlwDis , FNXsdPdtLevel , FTXsdPdtParent , FCXsdQtySet ,
                                    FTPdtStaSet , FTXsdRmk , FDLastUpdOn , FTLastUpdBy , FDCreateOn , FTCreateBy
                            FROM TPSTTaxDT SALDT WITH (NOLOCK)
                            LEFT JOIN ( SELECT SUM(FCXddValue) as DISPMT , FNXsdSeqNo FROM TPSTTaxDTDis 
                                        WHERE FNXddStaDis = 2 AND FTXddDisChgType IN ('1','2') AND ISNULL(FTXddRefCode,'') <> ''
                                        AND TPSTTaxDTDis.FTXshDocNo = '$tDocumentNumber'
                                        GROUP BY FNXsdSeqNo
                                    ) DTDis ON DTDis.FNXsdSeqNo = SALDT.FNXsdSeqNo
                            WHERE 1=1 AND SALDT.FTXshDocNo = '$tDocumentNumber' ";

        //ค้นหาแบบพิเศษ
        @$tSearchPDT   = $paData['tSearchPDT'];
        $tSQL .= "  AND ((SALDT.FTXshDocNo LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTPdtCode LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTXsdPdtName LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTPunName LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTXsdBarCode LIKE '%$tSearchPDT%') 
                        OR (SALDT.FCXsdQty LIKE '%$tSearchPDT%') 
                        OR (SALDT.FCXsdSetPrice LIKE '%$tSearchPDT%') 
                        OR (SALDT.FCXsdNetAfHD LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTBchCode LIKE '%$tSearchPDT%')
                    )";

        $tSQL .= ") Base) AS c WHERE c.FNRowID > $aRowLen[0] AND c.FNRowID <= $aRowLen[1]";

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aFoundRow  = $this->FSnMTAXGetDTPageAllInTax($paData);
            $nFoundRow  = $aFoundRow[0]->counts;
            $nPageAll   = ceil($nFoundRow/$paData['nRow']);
            $aResult    = array(
                'raItems'       => $oList,
                'rnAllRow'      => $nFoundRow,
                'rnCurrentPage' => $paData['nPage'],
                'rnAllPage'     => $nPageAll,
                'rtCode'        => '1',
                'rtDesc'        => 'success'
            );
        }else{
            $aResult = array(
                'rnAllRow'      => 0,
                'rnCurrentPage' => $paData['nPage'],
                "rnAllPage"     => 0,
                'rtCode'        => '800',
                'rtDesc'        => 'data not found'
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //หาจำนวนการขาย DT
    public function FSnMTAXGetDTPageAllInTax($paData){
        $tDocumentNumber    = $paData['tDocumentNumber'];
        $tSQL   = " SELECT COUNT (SALDT.FTXshDocNo) AS counts FROM TPSTTaxDT SALDT WITH (NOLOCK) WHERE 1=1 AND FTXshDocNo = '$tDocumentNumber' ";

        //ค้นหาแบบพิเศษ
        @$tSearchPDT   = $paData['tSearchPDT'];
        $tSQL   .= "  AND ((SALDT.FTXshDocNo LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTPdtCode LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTXsdPdtName LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTPunName LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTXsdBarCode LIKE '%$tSearchPDT%') 
                        OR (SALDT.FCXsdQty LIKE '%$tSearchPDT%') 
                        OR (SALDT.FCXsdSetPrice LIKE '%$tSearchPDT%') 
                        OR (SALDT.FCXsdNetAfHD LIKE '%$tSearchPDT%') 
                        OR (SALDT.FTBchCode LIKE '%$tSearchPDT%')
                    )";

        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0) {
            return $oQuery->result();
        }else{
            return false;
        }
    }


    //กรณีไม่สารถ GetMassage ได้จาก Stomp ให้ไปหาเองจาก TPSTTaxNo ล่าสุด
    public function FSaMTAXCallTaxNoLastDoc($paData){

        $tBchCode    = $paData['tBchCode'];
        $nDocType    = $paData['nDocType'];
        $tDocABB    = $paData['tDocABB'];

        
        $tXshDocNo = $this->db->select('FTXshDocNo')->where('FTBchCode',$tBchCode)->where('FNXshDocType',$nDocType)->get('TPSTTaxNo')->row_array()['FTXshDocNo'];

        return $tXshDocNo;

    }

    //ตรวจสอบเลข TaxNo ว่ามีการนำไปออกใบกำกับไปแล้วหรือยัง  if > 0 = มีแล้ว
    public function FSnMTAXCheckDuplicationOnTaxHD($ptXshDocNo){ 

        if($ptXshDocNo!='false' && $ptXshDocNo!='end' && $ptXshDocNo!=''){
            $nRowsDoc = $this->db->where('FTXshDocNo',$ptXshDocNo)->get('TPSTTaxHD')->num_rows();
        }else{
            $nRowsDoc = 1;
        }
          if($nRowsDoc> 0 ){
            return 1;
          }else{
            return 0;
          }
    }

}
