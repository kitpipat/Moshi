<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mSettingconfig extends CI_Model {
    
    //Get App Type เอาไปไว้ใน Option
    public function FSaMSETGetAppTpye(){
        $tSQL   = "SELECT DISTINCT FTSysApp from TSysConfig";
        $oQuery = $this->db->query($tSQL);
        if ($oQuery->num_rows() > 0){
            $oDetail = $oQuery->result();
            $aResult = array(
                'raItems'   => $oDetail,
                'rtCode'    => '1',
                'rtDesc'    => 'success',
            );
        }else{
            $aResult = array(
                'rtCode' => '800',
                'rtDesc' => 'data not found.',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////// แท็บตั้งค่าระบบ

    //Load Datatable Type Checkbox
    public function FSaMSETConfigDataTableByType($paData,$ptType){
        $nLngID = $paData['FNLngID'];
        $tSQL   = "SELECT 
                    COM.FTSysCode , 
                    COM.FTSysApp ,
                    COM.FTSysKey ,
                    COM.FTSysSeq ,
                    COM.FTGmnCode ,
                    COM.FTSysStaAlwEdit , 
                    COM.FTSysStaDataType , 
                    COM.FNSysMaxLength , 
                    COM.FTSysStaDefValue , 
                    COM.FTSysStaDefRef , 
                    COM.FTSysStaUsrValue , 
                    COM.FTSysStaUsrRef ,
                    COL.FTSysName ,
                    COL.FTSysDesc
                FROM [TSysConfig] COM
                LEFT JOIN [TSysConfig_L] COL ON COM.FTSysCode = COL.FTSysCode AND COM.FTSysSeq = COL.FTSysSeq AND COL.FNLngID = $nLngID
                WHERE 1=1 ";
               
        if($ptType == 'checkbox'){
            $tSQL   .= " AND COM.FTSysStaDataType = '4' ";
        }else{
            $tSQL   .= " AND COM.FTSysStaDataType != '4' ";
        }
        
        $tSearchList    = trim($paData['tSearchAll']);
        $tAppType       = $paData['tAppType'];
        switch ($tAppType) {
            case "API":
                $tConcatSQL = "AND COM.FTSysApp = 'API'";
                break;
            case "DOC":
                $tConcatSQL = "AND COM.FTSysApp = 'DOC'";
                break;
            case "POS":
                $tConcatSQL = "AND COM.FTSysApp = 'POS'";
                break;
            case "SL":
                $tConcatSQL = "AND COM.FTSysApp = 'SL'";
                break;
            case "WEB":
                $tConcatSQL = "AND COM.FTSysApp = 'WEB'";
                break;
            case "VD":
                $tConcatSQL = "AND COM.FTSysApp = 'VD'";
                break;
            case "ALL":
                $tConcatSQL = "AND COM.FTSysApp = 'ALL'";
                break;
            default:
                $tConcatSQL = "";
        }

        $tSQL .= " $tConcatSQL ";
        if($tSearchList != ''){
            $tSQL .= " AND (COL.FTSysName COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQL .= "      OR COL.FTSysName COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQL .= "      OR COL.FTSysDesc COLLATE THAI_BIN LIKE '%$tSearchList%')";
        }
        $tSQL   .= " ORDER BY COM.FTSysApp DESC";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aResult    = array(
                'raItems'       => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult    = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //Update
    public function FSaMSETUpdate($paData){
        try{

            //แก้ไขประเภทกำหนดเอง หรืออ้างอิง
            if($paData['tKind'] == 'Make'){ //แก้ไขประเภทกำหนดเอง
                $this->db->set('FTSysStaUsrValue' , $paData['nValue']); 
            }else if($paData['tKind'] == 'Ref'){ //แก้ไขประเภทอ้างอิง
                $this->db->set('FTSysStaUsrRef' , $paData['nValue']);
            }

            $this->db->set('FDLastUpdOn' , $paData['FDLastUpdOn']);
            $this->db->set('FTLastUpdBy' , $paData['FTLastUpdBy']);

            $this->db->where('FTSysCode', $paData['FTSysCode']);
            $this->db->where('FTSysApp', $paData['FTSysApp']);
            $this->db->where('FTSysKey', $paData['FTSysKey']);
            $this->db->where('FTSysSeq', $paData['FTSysSeq']);
            $this->db->where('FTSysStaDataType', $paData['FTSysStaDataType']);
            $this->db->update('TSysConfig');
            if($this->db->affected_rows() > 0 ){
                $aStatus = array(
                    'rtCode' => '1',
                    'rtDesc' => 'Update Success.',
                );
            }else{
                $aStatus = array(
                    'rtCode' => '800',
                    'rtDesc' => 'Fail Success.',
                );
            }
            return $aStatus;
        }catch(Exception $Error){
            return $Error;
        }
    }

    //Update ค่าเริ่มต้น
    public function FSaMSETUseValueDefult(){
        $tSQL   = "UPDATE SETHD
                    SET 
                        SETHD.FTSysStaUsrValue = SETDT.FTSysStaDefValue,
                        SETHD.FTSysStaUsrRef = SETDT.FTSysStaDefRef
                    FROM TSysConfig SETHD
                    LEFT JOIN TSysConfig SETDT 
                    ON 
                        SETHD.FTSysApp = SETDT.FTSysApp 
                        AND SETHD.FTSysKey = SETDT.FTSysKey 
                        AND SETHD.FTSysSeq = SETDT.FTSysSeq 
                        AND SETHD.FTGmnCode = SETDT.FTGmnCode 
                        AND SETHD.FTSysStaDataType = SETDT.FTSysStaDataType";
        $oQuery = $this->db->query($tSQL);
        if($this->db->affected_rows() > 0){
            $aResult    = array(
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult    = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////// แท็บรหัสอัตโนมัติ

    public function FSaMSETConfigDataTableAutoNumber($paData){
        $nLngID = $paData['FNLngID'];
        $tSQL   = "SELECT 
                        AO.FTSatTblName,
                        AO.FTSatStaDocType,
                        AO.FNSatMaxFedSize,
                        AO.FTSatDefFmtAll AS DefFmt,
                        AO.FTSatStaReset AS DefResetFmt,
                        TXN.FTAhmFmtAll AS UsrFmt,
                        TXN.FTAhmFmtReset AS UsrResetFmt,
                        AOL.FTSatTblDesc
                FROM [TCNTAuto_X] AO
                LEFT JOIN [TCNTAuto_L_X] AOL ON AO.FTSatTblName = AOL.FTSatTblName AND AO.FTSatFedCode = AOL.FTSatFedCode AND AO.FTSatStaDocType = AOL.FTSatStaDocType 
                LEFT JOIN [TCNTAutoHisTxn_X] TXN ON AO.FTSatTblName = TXN.FTAhmTblName AND AO.FTSatFedCode = TXN.FTAhmFedCode AND AO.FTSatStaDocType = TXN.FTSatStaDocType
                AND AOL.FNLngID = $nLngID
                WHERE 1=1 ";

        $tSearchList = trim($paData['tSearchAll']);
        if($tSearchList != ''){
            $tSQL .= " AND (AO.FTSatTblName COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQL .= "      OR AOL.FTSatTblDesc COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQL .= "      OR TXN.FTAhmFmtAll COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQL .= "      OR TXN.FTAhmFmtAll COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQL .= "      OR AO.FTSatTblName COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQL .= "      OR AO.FTSatDefChar COLLATE THAI_BIN LIKE '%$tSearchList%'";
            $tSQL .= "      OR AO.FTSatStaDocType COLLATE THAI_BIN LIKE '%$tSearchList%')";
        }

        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aResult    = array(
                'raItems'       => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult    = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //อนุญาติให้จัดรูปแบบจากอะไรบ้างข้อมูล DT
    public function FSaMSETConfigGetAllowDataAutoNumber($paData){
        $tTable = $paData['FTSatTblName'];
        $tType  = $paData['FTSatStaDocType'];

        $tSQL   = "SELECT 
                       AO.FTSatStaAlwChr ,
                       AO.FTSatStaAlwBch , 
                       AO.FTSatStaAlwPosShp , 
                       AO.FTSatStaAlwYear , 
                       AO.FTSatStaAlwMonth ,
                       AO.FTSatStaAlwDay , 
                       AO.FTSatStaAlwSep ,
                       AO.FNSatMinRunning ,
                       AO.FTSatDefFmtAll ,
                       AO.FTSatStaDefUsage,
                       AOL.FTSatTblDesc ,
                       AO.FNSatMaxFedSize ,
                       AO.FTSatDefChar,
                       AO.FTSatTblName,
                       AO.FTSatFedCode,
                       AO.FTSatStaDocType,
                       AO.FTSatDefNum,
                       TXN.FTAhmFmtAll AS FormatCustom,
                       TXN.FTAhmFmtPst,
                       TXN.FTAhmFmtChar,
                       TXN.FTAhmFmtReset,
                       TXN.FTSatStaAlwSep,
                       TXN.FTAhmFmtYear,
                       TXN.FNAhmFedSize,
                       TXN.FNAhmNumSize,
                       TXN.FTSatUsrNum
                FROM [TCNTAuto_X] AO
                LEFT JOIN [TCNTAuto_L_X] AOL ON AO.FTSatTblName = AOL.FTSatTblName AND AO.FTSatFedCode = AOL.FTSatFedCode AND AO.FTSatStaDocType = AOL.FTSatStaDocType 
                LEFT JOIN [TCNTAutoHisTxn_X] TXN ON AO.FTSatTblName = TXN.FTAhmTblName AND AO.FTSatFedCode = TXN.FTAhmFedCode AND AO.FTSatStaDocType = TXN.FTSatStaDocType
                WHERE 1=1 AND AO.FTSatTblName = '$tTable' AND AO.FTSatStaDocType = '$tType' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aResult    = array(
                'raItems'       => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult    = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //หาความยาวของสาขา และ เครื่องจุดขาย
    public function FSaMSETGetMaxLength($ptTable){
        $tSQL   = "SELECT 
                       AO.FNSatMaxFedSize
                    FROM [TCNTAuto_X] AO
                    WHERE 1=1 AND AO.FTSatTblName = '$ptTable' ";
        $oQuery = $this->db->query($tSQL);
        if($oQuery->num_rows() > 0){
            $oList      = $oQuery->result();
            $aResult    = array(
                'raItems'       => $oList,
                'rtCode'        => '1',
                'rtDesc'        => 'success',
            );
        }else{
            $aResult    = array(
                'rtCode'        => '800',
                'rtDesc'        => 'data not found',
            );
        }
        $jResult = json_encode($aResult);
        $aResult = json_decode($jResult, true);
        return $aResult;
    }

    //เลือกใช้ค่า ดีฟอล จำเป็นต้องลบ , ลบก่อน insert
    public function FSaMSETAutoNumberDelete($paData){
        $this->db->where_in('FTAhmTblName', $paData['FTAhmTblName']);
        $this->db->where_in('FTAhmFedCode', $paData['FTAhmFedCode']);
        $this->db->where_in('FTSatStaDocType', $paData['FTSatStaDocType']);
        $this->db->delete('TCNTAutoHisTxn_X');
        if($this->db->affected_rows() > 0){
            //Success
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }else{
            //Ploblem
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot Delete Item.',
            );
        }

        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }

    //เพิ่มข้อมูล
    public function FSaMSETAutoNumberInsert($paData){
        $this->db->insert('TCNTAutoHisTxn_X',$paData);
        if($this->db->affected_rows() > 0){
            $aStatus = array(
                'rtCode' => '1',
                'rtDesc' => 'success',
            );
        }else{
            $aStatus = array(
                'rtCode' => '905',
                'rtDesc' => 'cannot insert.',
            );
        }

        $jStatus = json_encode($aStatus);
        $aStatus = json_decode($jStatus, true);
        return $aStatus;
    }
}
