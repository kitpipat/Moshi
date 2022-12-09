<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php');
require_once(APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php');
require_once(APPPATH . 'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php');

class cPromotionStep1ImportPmtExcel extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('document/promotion/mPromotionStep1ImportPmtExcel');
        $this->load->model('document/promotion/mPromotionStep1PmtPdtDt');
        $this->load->model('document/promotion/mPromotionStep1PmtDt');
        $this->load->model('document/promotion/mPromotionStep1PmtBrandDt');
        $this->load->model('document/promotion/mPromotion');
    }

    /**
     * Functionality : Import Promotion Group From Excel
     * Parameters : -
     * Creator : 04/02/2020 piya
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function FStPromotionImportFromExcel()
    {
        $tUserSessionID = $this->session->userdata('tSesSessionID');
        $tUserSessionDate = $this->session->userdata('tSesSessionDate');
        $tUserLoginCode = $this->session->userdata('tSesUsername');
        $nLangEdit = $this->session->userdata("tLangEdit");
        $tUserLevel = $this->session->userdata('tSesUsrLevel');
        $tBchCodeLogin = $tUserLevel == 'HQ' ? FCNtGetBchInComp() : $this->session->userdata("tSesUsrBchCode");

        $tPmtGroupTypeTmp = $this->input->post('tPmtGroupTypeTmp');
        $tPmtGroupListTypeTmp = $this->input->post('tPmtGroupListTypeTmp');
        $tPmtGroupNameTmp = $this->input->post('tPmtGroupNameTmp');
        $tPmtGroupNameTmpOld = $this->input->post('tPmtGroupNameTmpOld');

        $aDataFiles = (isset($_FILES['oefPromotionStep1PmtFileExcel']) && !empty($_FILES['oefPromotionStep1PmtFileExcel']))? $_FILES['oefPromotionStep1PmtFileExcel'] : null;
        
        $aReturn = array(
            'nStaEvent' => '',
            'tStaMessg' => ""
        );

        if(isset($aDataFiles) && !empty($aDataFiles) && is_array($aDataFiles)){
            // Insert
            $aDataFiles = (isset($_FILES['aFile']) && !empty($_FILES['aFile']))? $_FILES['aFile'] : null;
            
            // var_dump($aDataFiles);
            // return;

            $this->db->trans_begin();

            $oLoadExcel = PHPExcel_IOFactory::load($aDataFiles['tmp_name']);

            $oExcelSheet = null;

            /*===== Begin Product Process ==============================================*/
            if($tPmtGroupListTypeTmp == "1"){ // Product
                $oExcelSheet = $oLoadExcel->getSheetByName('Product');
                $aProductDataSheet = $oExcelSheet->toArray();

                $aClearPmtPdtDtInTmpParams = [
                    'tPmtGroupNameTmpOld' => $tPmtGroupNameTmpOld,
                    'tUserSessionID' => $tUserSessionID
                ];
                $this->mPromotionStep1PmtDt->FSbClearPmtDtInTmp($aClearPmtPdtDtInTmpParams);

                foreach($aProductDataSheet as $nIndex => $aProduct){
                    if($nIndex == 0){continue;} // ข้ามแถวที่ 1 หัวตารางไป

                    $aGetDataPdtParams = [
                        'tPdtCode' => $aProduct[0],
                        'tPunCode' => $aProduct[1],
                        'tBarCode' => $aProduct[2],
                        'nLngID' => $nLangEdit,
                        'tUserSessionID' => $tUserSessionID,
                        'tPmtGroupNameTmpOld' => $tPmtGroupNameTmpOld

                    ];
                    $aDataProduct = $this->mPromotionStep1ImportPmtExcel->FSaMGetDataPdt($aGetDataPdtParams);
                    
                    if(!empty($aDataProduct)){
                        $aPmtPdtDtToTempParams = [
                            'tPmtGroupNameTmpOld' => $tPmtGroupNameTmpOld,
                            'tBchCodeLogin' => $tBchCodeLogin,
                            'tUserSessionID' => $tUserSessionID,
                            'tUserSessionDate' => $tUserSessionDate,
                            'tDocNo' => 'PMTDOCTEMP',
                            'tPmtGroupTypeTmp' => $tPmtGroupTypeTmp,
                            'tPmtGroupListTypeTmp' => $tPmtGroupListTypeTmp,
                            'tPdtCode' => $aDataProduct['FTPdtCode'],
                            'tPdtName' => $aDataProduct['FTPdtName'],
                            'tPunCode' => $aDataProduct['FTPunCode'],
                            'tPunName' => $aDataProduct['FTPunName'],
                            'tBarCode' => $aDataProduct['FTBarCode']
                        ];
                        $this->mPromotionStep1PmtPdtDt->FSaMPmtPdtDtToTemp($aPmtPdtDtToTempParams);    
                    }
                }
            }
            /*===== End Product Process ================================================*/

            /*===== Begin Brand Process ================================================*/
            if($tPmtGroupListTypeTmp == "2"){ // Brand
                $oExcelSheet = $oLoadExcel->getSheetByName('Brand');
                $aBrandDataSheet = $oExcelSheet->toArray();

                $aClearPmtPdtDtInTmpParams = [
                    'tPmtGroupNameTmpOld' => $tPmtGroupNameTmpOld,
                    'tUserSessionID' => $tUserSessionID
                ];
                $this->mPromotionStep1PmtDt->FSbClearPmtDtInTmp($aClearPmtPdtDtInTmpParams);

                foreach($aBrandDataSheet as $nIndex => $aBrand){
                    if($nIndex == 0){continue;} // ข้ามแถวที่ 1 หัวตารางไป

                    $aGetDataBrandParams = [
                        'tBrandCode' => $aBrand[0],
                        'tModelCode' => $aBrand[1],
                        'nLngID' => $nLangEdit,
                        'tUserSessionID' => $tUserSessionID,
                        'tPmtGroupNameTmpOld' => $tPmtGroupNameTmpOld
                    ];
                    $aDataBrand = $this->mPromotionStep1ImportPmtExcel->FSaMGetDataBrand($aGetDataBrandParams);

                    // var_dump($aDataBrand); die();
                    
                    if(isset($aDataBrand['FTPbnCode']) && isset($aDataBrand['FTPbnName']) && isset($aDataBrand['FTPmoCode']) && isset($aDataBrand['FTPmoName'])){
                        $aPmtBrandDtToTempParams = [
                            'tPmtGroupNameTmpOld' => $tPmtGroupNameTmpOld,
                            'tBchCodeLogin' => $tBchCodeLogin,
                            'tUserSessionID' => $tUserSessionID,
                            'tUserSessionDate' => $tUserSessionDate,
                            'tDocNo' => 'PMTDOCTEMP',
                            'tPmtGroupTypeTmp' => $tPmtGroupTypeTmp,
                            'tPmtGroupListTypeTmp' => $tPmtGroupListTypeTmp,
                            'tBrandCode' => $aDataBrand['FTPbnCode'],
                            'tBrandName' => $aDataBrand['FTPbnName'],
                            'tModelCode' => $aDataBrand['FTPmoCode'],
                            'tModelName' => $aDataBrand['FTPmoName']
                        ];
                        $this->mPromotionStep1PmtBrandDt->FSaMPmtBrandDtToTemp($aPmtBrandDtToTempParams);    
                    }
                }
            }
            /*===== End Brand Process ==================================================*/

            
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturn['nStaEvent'] = '900';
                $aReturn['tStaMessg'] = "Unsucess Add by Import File";
            } else {
                $this->db->trans_commit();
                $aReturn['nStaEvent'] = '1';
                $aReturn['tStaMessg'] = "Success Add by Import File";
            }
    
            
        }else{
            $aReturn['nStaEvent'] = '900';
            $aReturn['tStaMessg'] = "File Fail";
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($aReturn));

    }
}
