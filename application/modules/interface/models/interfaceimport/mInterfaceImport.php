<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mInterfaceImport extends CI_Model {
    
    public function FSaMINMGetHD($pnLang){

    //     $tSql = "SELECT
    //         MTHD.FTInfCode,
    //         MTHD.FTInfNameTH,
    //         MTHD.FTInfNameEN,
    //         MTHD.FTInfType,
    //         MTHD.FTInfStaUse
    //     FROM TLKSysMTableHD MTHD ";
    // if($pnType!=''){
    //     $tSql .= " WHERE MTHD.FTInfType=$pnType ";
    // }
    // $tSql .=  "ORDER BY MTHD.FTInfNameTH DESC";

        $tSQL = "   SELECT 
                        LNK.FTInfCode,
                        LNK_L.FTInfName
                    FROM TSysLnk LNK WITH(NOLOCK) 
                    LEFT JOIN TSysLnk_L LNK_L ON LNK.FTInfCode = LNK_L.FTInfCode AND LNK_L.FNLngID = $pnLang
                    WHERE 1=1 
                    AND LEFT(LNK.FTInfCode,3) = 'Imp' 
                    AND LNK.FTInfTypeDoc = '1' 
                    AND LNK.FTInfStaUse = '1'
                    ORDER BY LNK.FTInfCode DESC
        ";

        $oQuery     = $this->db->query($tSQL);
        $aResult    = $oQuery->result_array();
        return $aResult;
    }
   
}


