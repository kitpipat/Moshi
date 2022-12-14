<?php
defined('BASEPATH') or exit('No direct script access allowed');
set_time_limit(0);
ini_set('memory_limit', '8000M');
ini_set('max_execution_time', 0);
class cReport extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('report/report/mReport');
    }

    public function index($tRptGrpMod, $nRptBrowseType, $tRptBrowseOption) {
        $this->load->view('report/report/wReport', array(
            'tRptGrpMod' => $tRptGrpMod,
            'nRptBrowseType' => $nRptBrowseType,
            'tRptBrowseOption' => $tRptBrowseOption,
        ));
    }

    // Functionality: Function Call Page Report All Main
    // Parameters: Ajax and Function Parameter
    // Creator: 12/03/2019 wasin(Yoshi)
    // Return: String View
    // ReturnType: View
    public function FCNoCRPTViewPageMain() {
        try {
            // $dStart = round(microtime(true) * 1000);

            $tRptGrpMod = $this->input->post('ptRptGrpMod');
            $tUsrRoleCode = $this->session->userdata('tSesUsrRoleCode');
            $nLangResort = $this->session->userdata("tLangID");
            $nLangEdit = $this->session->userdata("tLangEdit");
            $aLangHave = FCNaHGetAllLangByTable('TSysReport_L');
            $nLangHave = count($aLangHave);
            if ($nLangHave > 1) {
                $nLangEdit = ($nLangEdit != '') ? $nLangEdit : $nLangResort;
            } else {
                $nLangEdit = (@$aLangHave[0]->nLangList == '') ? '1' : $aLangHave[0]->nLangList;
            }

            $aWhereData = array(
                'tRptGrpMod' => $tRptGrpMod,
                'tUsrRoleCode' => $tUsrRoleCode,
                'nLngID' => $nLangEdit,
            );

            $aDataRptModule = $this->mReport->FSaMDataRptModules($aWhereData);
            $aDataRptMenu = $this->mReport->FSaMDataRptMunuList($aWhereData);
            $aDataView = array(
                'aDataRptModule' => $aDataRptModule,
                'aDataRptMenu' => $aDataRptMenu,
            );
            $tViewReportMain = $this->load->view('report/report/wReportMain', $aDataView, true);

            // $dFinish = round(microtime(true) * 1000);
            // $nDiffTime = ( $dFinish - $dStart ) * 0.001;
            $aReturnData = array(
                'tViewReportMain' => $tViewReportMain,
                'nStaEvent' => 1,
                'tStaMessg' => 'Success',
                // 'nDiffTime' => $nDiffTime
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
            );
        }
        echo json_encode($aReturnData);
    }

    // Functionality: Function Call View Condition By Report
    // Parameters: Ajax and Function Parameter
    // Creator: 22/03/2019 wasin(Yoshi)
    // Return: String View
    // ReturnType: View
    public function FCNoCRPTViewCondition() {
        try {
            // $dStart = round(microtime(true) * 1000);

            $aDataCodition = $this->input->post('paDataCodition');
            $nLangResort = $this->session->userdata("tLangID");
            $nLangEdit = $this->session->userdata("tLangEdit");
            $aLangHave = FCNaHGetAllLangByTable('TSysReport_L');
            $nLangHave = count($aLangHave);
            if ($nLangHave > 1) {
                $nLangEdit = ($nLangEdit != '') ? $nLangEdit : $nLangResort;
            } else {
                $nLangEdit = (@$aLangHave[0]->nLangList == '') ? '1' : $aLangHave[0]->nLangList;
            }

            $aWhereData = array(
                'tRptModCode' => $aDataCodition['tRptModCode'],
                'tRptGrpCode' => $aDataCodition['tRptGrpCode'],
                'tRptCode' => $aDataCodition['tRptCode'],
                'nLngID' => $nLangEdit,
            );
            $aRptFilterData = $this->mReport->FSaMDataRptConditionFilter($aWhereData);
            $aDataView = array(
                'aRptFilterData' => $aRptFilterData,
            );
            $tViewCondition = $this->load->view('report/report/wReportCondition', $aDataView, true);
            // $dFinish = round(microtime(true) * 1000);
            // $nDiffTime = ( $dFinish - $dStart ) * 0.001;
            $aReturnData = array(
                'tViewCondition' => $tViewCondition,
                'nStaEvent' => 1,
                'tStaMessg' => 'Success',
                // 'nDiffTime' => $nDiffTime
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => '500',
                'tStaMessg' => $Error->getMessage(),
            );
        }
        echo json_encode($aReturnData);
    }

    // Functionality: ?????????????????????????????? Data In TSysHisExport
    // Parameters: Ajax and Function Parameter
    // Creator: 15/08/2019 wasin(Yoshi)
    // Last Update: -
    // Return: String View
    // ReturnType: View
    public function FCNoCRPTChkDataInTSysHisExport() {
        try {
            // $dStart = round(microtime(true) * 1000);
            $aFromSerialize = $this->input->post();
            $aDataSessionInfo = $this->session->userdata('tSesUsrInfo');
            $tIP = $this->input->ip_address();
            $tFullHost = gethostbyaddr($tIP);
            // $this->tCompName = $tFullHost;

            $aDataWhere = [
                'FTComName' => $tFullHost,
                'FTUsrCode' => $aDataSessionInfo['FTUsrCode'],
                'FTUsrSession' => $this->session->userdata('tSesSessionID'),
                'FTRptCode' => $aFromSerialize['ohdRptCode'],
            ];
            $aDataReportHis = $this->mReport->FCNaMGetDataTop1InTSysHisExport($aDataWhere);

            // $dFinish = round(microtime(true) * 1000);
            // $nDiffTime = ( $dFinish - $dStart ) * 0.001;
            $aReturnData = array(
                'nStaEvent' => 1,
                'tStaMessg' => 'Success Query Data',
                'raFromSerialize' => $aFromSerialize,
                'raItems' => $aDataReportHis,
                'tSysBchCode' => SYS_BCH_CODE,
                // 'nDiffTime' => $nDiffTime
            );
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => 500,
                'tStaMessg' => $Error->getMessage(),
            );
        }
        echo json_encode($aReturnData);
    }

    // Functionality: Update Status Cancel Export Download Report
    // Parameters: Ajax and Function Parameter
    // Creator: 16/08/2019 wasin(Yoshi)
    // Last Update: -
    // Return: Object Status Update Cancel Report
    // ReturnType: object
    public function FCNoCRPTConfirmDownloadFile() {
        try {
            $this->db->trans_begin();
            $tIP = $this->input->ip_address();
            $tFullHost = gethostbyaddr($tIP);
            $aDataUpdStaCancel = $this->input->post('paDataRpt');

            $aDataWhere = [
                'FTComName' => $tFullHost,
                'FTRptCode' => $aDataUpdStaCancel['ptRptCode'],
                'FTUsrCode' => $aDataUpdStaCancel['ptUsrCode'],
                'FTUsrSession' => $this->session->userdata('tSesSessionID'),
            ];

            // Select Data In TSysHisExport
            $aDataRptDownloadFile = $this->mReport->FCNaMGetDataTop1InTSysHisExport($aDataWhere);

            // Update Status Download
            $this->mReport->FCNxMRPTUpdStaDownloadFile($aDataRptDownloadFile);

            // *** Set Parameter Delete Queue Name
            $tRptCode = $aDataRptDownloadFile['FTRptCode'];
            $tRptUsrCode = $aDataRptDownloadFile['FTUsrCode'];
            $tRptUsrSession = $aDataRptDownloadFile['FTUsrSession'];
            $dRptDateSubscribe = $aDataRptDownloadFile['FDDateSubscribe'];
            $dRptTimeConvert = date("His", strtotime($aDataRptDownloadFile['FDTimeSubscribe']));
            $tRptZipName = $aDataRptDownloadFile['FTZipName'];
            $tRptZipPath = $aDataRptDownloadFile['FTZipPath'];

            $tPrefixQueueName = 'RESRPT_' . SYS_BCH_CODE . '_' . $tRptCode . '_' . $tRptUsrCode . '_' . $tRptUsrSession . '_' . $dRptDateSubscribe . '_' . $dRptTimeConvert;
            // Call Helper Center Delte Report Export MQ
            FCNxReportRabbitMQDeleteQName(array(
                'tQueueName' => $tPrefixQueueName,
            ));

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaExport' => 500,
                    'tStaMessg' => 'Error Update Status Download Report.',
                );
            } else {
                $this->db->trans_commit();
                if (!empty($tRptZipName) && !empty($tRptZipPath)) {
                    $aReturnData = $this->FCNaCRPTChkDataInFolderApp($tRptZipName, $tRptZipPath);
                } else {
                    $aReturnData = array(
                        'nStaExport' => 800,
                        'tStaMessg' => language('common/main/main', 'tRptNotFoundZipNameAndPathInDB'),
                    );
                }
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaExport' => 500,
                'tStaMessg' => $Error->getMessage(),
            );
        }
        echo json_encode($aReturnData);
    }

    // Functionality: ?????????????????????????????? Zip ?????? Filder ??????????????????
    // Parameters: Ajax and Function Parameter
    // Creator: 20/08/2019 wasin(Yoshi)
    // Last Update: -
    // Return: Object Status Update Cancel Report
    // ReturnType: object
    function FCNaCRPTChkDataInFolderApp($ptRptZipName, $ptRptZipPath) {
        if (file_exists($ptRptZipPath)) {
            $aRptZipPathExplode = explode('/application/', $ptRptZipPath);
            $tRptZipName = $ptRptZipName;
            $tRptZipPath = base_url() . "application/" . $aRptZipPathExplode[1];
            $aReturnData = array(
                'nStaExport' => 1,
                'tRptZipName' => $tRptZipName,
                'tRptZipPath' => $tRptZipPath,
                'tStaMessg' => 'Success Export File ' . $tRptZipName,
            );
        } else {
            $aReturnData = array(
                'nStaExport' => 800,
                'tStaMessg' => language('common/main/main', 'tRptNotFoundZipNameAndPathInApp'),
            );
        }
        return $aReturnData;
    }

    // Functionality: Update Status Cancel Export Download Report
    // Parameters: Ajax and Function Parameter
    // Creator: 16/08/2019 wasin(Yoshi)
    // Last Update: -
    // Return: Object Status Update Cancel Report
    // ReturnType: object
    public function FCNoCRPTCancelDownloadFile() {
        try {
            $this->db->trans_begin();

            $aDataReport = $this->input->post('paDataRpt');
            $aDataWhere = [
                'FTComName' => $aDataReport['FTComName'],
                'FTRptCode' => $aDataReport['FTRptCode'],
                'FTUsrCode' => $aDataReport['FTUsrCode'],
                'FTUsrSession' => $aDataReport['FTUsrSession'],
            ];
            // Select Data In TSysHisExport
            $aDataRptDownloadFile = $this->mReport->FCNaMGetDataTop1InTSysHisExport($aDataWhere);

            // Update Last Crate Menu
            $this->mReport->FCNxMRPTUpdStaDownloadFile($aDataRptDownloadFile);

            // *** Set Parameter Delete Queue Name
            $tRptCode = $aDataRptDownloadFile['FTRptCode'];
            $tRptUsrCode = $aDataRptDownloadFile['FTUsrCode'];
            $tRptUsrSession = $aDataRptDownloadFile['FTUsrSession'];
            $dRptDateSubscribe = $aDataRptDownloadFile['FDDateSubscribe'];
            $dRptTimeConvert = date("His", strtotime($aDataRptDownloadFile['FDTimeSubscribe']));
            $tPrefixQueueName = 'RESRPT_' . SYS_BCH_CODE . '_' . $tRptCode . '_' . $tRptUsrCode . '_' . $tRptUsrSession . '_' . $dRptDateSubscribe . '_' . $dRptTimeConvert;

            // Call Helper Center Delte Report Export MQ
            FCNxReportRabbitMQDeleteQName(array(
                'tQueueName' => $tPrefixQueueName,
            ));

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturnData = array(
                    'nStaEvent' => 500,
                    'tStaMessg' => 'Error Cannot Update Status Report Cancel Download.',
                );
            } else {
                $this->db->trans_commit();
                $aReturnData = array(
                    'nStaEvent' => 1,
                    'tStaMessg' => 'Update Status Report Cancel Download Success.',
                );
            }
        } catch (Exception $Error) {
            $aReturnData = array(
                'nStaEvent' => 500,
                'tStaMessg' => $Error->getMessage(),
            );
        }
        echo json_encode($aReturnData);
    }
    
  



}
