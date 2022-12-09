<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class mInterfaceExport extends CI_Model {
    
    public function FSaMIFXGetHD($pnLang){
        $tSQL = "   SELECT 
                        LNK.FTInfCode,
                        LNK_L.FTInfName
                    FROM TSysLnk LNK WITH(NOLOCK) 
                    LEFT JOIN TSysLnk_L LNK_L ON LNK.FTInfCode = LNK_L.FTInfCode AND LNK_L.FNLngID = $pnLang
                    WHERE 1=1 
                    AND LEFT(LNK.FTInfCode,3) = 'Exp' 
                    AND LNK.FTInfTypeDoc = '2' 
                    AND LNK.FTInfStaUse = '1'
                    AND ISNULL(LNK_L.FTInfName,'') != ''
                    ORDER BY LNK_L.FTInfName ASC
                ";

        $oQuery     = $this->db->query($tSQL);
        $aResult    = $oQuery->result_array();
        return $aResult;
    }
   
}


