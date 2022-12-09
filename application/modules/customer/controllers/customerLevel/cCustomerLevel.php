<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
class cCustomerLevel extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('customer/customerLevel/mCustomerLevel');
        $this->load->model('customer/customer/mCustomer');
        date_default_timezone_set("Asia/Bangkok");
    }

    /**
     * Functionality : Main page for Customer Level
     * Parameters : $nCstLevBrowseType, $tCstLevBrowseOption
     * Creator : 20/09/2018 piya
     * Last Modified : -
     * Return : -
     * Return Type : -
     */
    public function index($nCstLevBrowseType, $tCstLevBrowseOption)
    {
        $aDataConfigView    =   [
            'nCstLevBrowseType'     => $nCstLevBrowseType,
            'tCstLevBrowseOption'   => $tCstLevBrowseOption,
            'aAlwEvent'             => FCNaHCheckAlwFunc('customerLevel/0/0'),
            'vBtnSave'              => FCNaHBtnSaveActiveHTML('customerLevel/0/0'),
            'nOptDecimalShow'       => FCNxHGetOptionDecimalShow(),
            'nOptDecimalSave'       => FCNxHGetOptionDecimalSave()
        ];
        $this->load->view('customer/customerLevel/wCustomerLevel', $aDataConfigView);
    }

    /**
     * Functionality : Function Call Customer Level Page List
     * Parameters : Ajax and Function Parameter
     * Creator : 20/09/2018 piya
     * Last Modified : -
     * Return : String View
     * Return Type : View
     */
    public function FSvCstLevListPage()
    {
        $this->load->view('customer/customerLevel/wCustomerLevelList');
    }

    /**
     * Functionality : Function Call DataTables Customer Level
     * Parameters : Ajax and Function Parameter
     * Creator : 20/09/2018 piya
     * Last Modified : -
     * Return : String View
     * Return Type : View
     */
    public function FSvCstLevDataList()
    {
        $nPage      = $this->input->post('nPageCurrent');
        $tSearchAll = $this->input->post('tSearchAll');
        if ($nPage == '' || $nPage == null) {
            $nPage = 1;
        } else {
            $nPage = $this->input->post('nPageCurrent');
        }
        if (!$tSearchAll) {
            $tSearchAll = '';
        }
        //Lang ภาษา
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData  = array(
            'nPage'         => $nPage,
            'nRow'          => 10,
            'FNLngID'       => $nLangEdit,
            'tSearchAll'    => $tSearchAll,

        );

        $tAPIReq = "";
        $tMethodReq = "GET";
        $aResList = $this->mCustomerLevel->FSaMCstLevList($tAPIReq, $tMethodReq, $aData);
        $aGenTable = array(
            'aDataList' => $aResList,
            'nPage' => $nPage,
            'tSearchAll' => $tSearchAll
        );
        $this->load->view('customer/customerLevel/wCustomerLevelDataTable', $aGenTable);
    }

    /**
     * Functionality : Function CallPage Customer Level Add
     * Parameters : Ajax and Function Parameter
     * Creator : 20/09/2018 piya
     * Last Modified : -
     * Return : String View
     * Return Type : View
     */
    public function FSvCstLevAddPage()
    {

        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData  = array(
            'FNLngID'   => $nLangEdit,
        );
        $tAPIReq        = "";
        $tMethodReq     = "GET";
        $aDataAdd = array(
            'aResult'   => array(
                'rtCode' => '99',
            )
        );

        $this->load->view('customer/customerLevel/wCustomerLevelAdd', $aDataAdd);
    }

    /**
     * Functionality : Function CallPage Customer Level Edit
     * Parameters : Ajax and Function Parameter
     * Creator : 20/09/2018 piya
     * Last Modified : -
     * Return : String View
     * Return Type : View
     */
    public function FSvCstLevEditPage()
    {

        $tCstLevCode    = $this->input->post('tCstLevCode');
        $nLangResort    = $this->session->userdata("tLangID");
        $nLangEdit      = $this->session->userdata("tLangEdit");
        $aData  = array(
            'FTCstLevCode' => $tCstLevCode,
            'FNLngID'   => $nLangEdit
        );
        $tAPIReq        = "";
        $tMethodReq     = "GET";
        $aCstLevData       = $this->mCustomerLevel->FSaMCstLevSearchByID($tAPIReq, $tMethodReq, $aData);
        $aDataEdit      = array('aResult' => $aCstLevData);
        $this->load->view('customer/customerLevel/wCustomerLevelAdd', $aDataEdit);
    }

    /**
     * Functionality : Event Add Customer Level
     * Parameters : Ajax and Function Parameter
     * Creator : 20/09/2018 piya
     * Last Modified : -
     * Return : Status Add Event
     * Return Type : String
     */
    public function FSaCstLevAddEvent()
    {
        try {
            date_default_timezone_set("Asia/Bangkok");
            if ($this->input->post('ocbCustomerLevelAppr')=="checked") {
              $tFCClvAlwPnt = "1";
            }else {
              $tFCClvAlwPnt = "2";
            }

            $aDataMaster = array(
                'tIsAutoGenCode'  => $this->input->post('ocbCustomerLevelAutoGenCode'),
                'FTCstLevCode'    => $this->input->post('oetCstLevCode'),
                'FTCstLevRmk'     => $this->input->post('otaCstLevRemark'),
                'FTCstLevName'    => $this->input->post('oetCstLevName'),
                'FTLastUpdBy'     => $this->session->userdata('tSesUsername'),
                'FTPplCode'    => $this->input->post('oetCstPplRetCode'),
                'FTClvStaAlwPnt'     => $tFCClvAlwPnt,
                'FDLastUpdOn'     => date('Y-m-d H:i:s'),
                'FTCreateBy'      => $this->session->userdata('tSesUsername'),
                'FDCreateOn'      => date('Y-m-d H:i:s'),
                'FNLngID'         => $this->session->userdata("tLangEdit"),
            );
            if($aDataMaster['tIsAutoGenCode'] == '1'){ // Check Auto Gen CustomerLevel Code?
                // Auto Gen CustomerLevel Code
                $aGenCode = FCNaHGenCodeV5('TCNMCstLev','0');
                if($aGenCode['rtCode'] == '1'){
                    $aDataMaster['FTCstLevCode'] = $aGenCode['rtClvCode'];
                }
            }
            $oCountDup  = $this->mCustomerLevel->FSoMCstLevCheckDuplicate($aDataMaster['FTCstLevCode']);
            $nStaDup    = $oCountDup[0]->counts;

            if ($nStaDup == 0) {
                $this->db->trans_begin();
                $aStaCstLevMaster  = $this->mCustomerLevel->FSaMCstLevAddUpdateMaster($aDataMaster);
                $aStaCstLevLang    = $this->mCustomerLevel->FSaMCstLevAddUpdateLang($aDataMaster);
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $aReturn = array(
                        'nStaEvent'    => '900',
                        'tStaMessg'    => "Unsucess Add Event"
                    );
                } else {
                    $this->db->trans_commit();
                    $aReturn = array(
                        'nStaCallBack'    => $this->session->userdata('tBtnSaveStaActive'),
                        'tCodeReturn'    => $aDataMaster['FTCstLevCode'],
                        'nStaEvent'        => '1',
                        'tStaMessg'        => 'Success Add Event'
                    );
                }
            } else {
                $aReturn = array(
                    'nStaEvent'    => '801',
                    'tStaMessg'    => "Data Code Duplicate"
                );
            }
            echo json_encode($aReturn);
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    /**
     * Functionality : Event Edit Customer Level
     * Parameters : Ajax and Function Parameter
     * Creator : 20/09/2018 piya
     * Last Modified : -
     * Return : Status Add Event
     * Return Type : String
     */
    public function FSaCstLevEditEvent()
    {
        try {
            if ($this->input->post('ocbCustomerLevelAppr')=="on") {
              $tFCClvAlwPnt = "1";
            }else {
              $tFCClvAlwPnt = "2";
            }

            $aDataMaster = array(
                'FTCstLevCode' => $this->input->post('oetCstLevCode'),
                'FTCstLevRmk' => $this->input->post('otaCstLevRemark'),
                'FTCstLevName' => $this->input->post('oetCstLevName'),
                'FTClvStaAlwPnt'     => $tFCClvAlwPnt,

                'FCClvCalAmt'     => $this->input->post('oetCstLevCalAmt'),
                'FCClvCalPnt'       => $this->input->post('tCstLevCalPnt'),

                'FTPplCode'    => $this->input->post('oetCstPplRetCode'),
                'FTLastUpdBy'   => $this->session->userdata('tSesUsername'),
                'FDLastUpdOn'   => date('Y-m-d H:i:s'),
                'FTCreateBy'   => $this->session->userdata('tSesUsername'),
                'FDCreateOn'   => date('Y-m-d H:i:s'),
                'FNLngID'   => $this->session->userdata("tLangEdit"),
            );
            $this->db->trans_begin();
            $aStaCstLevMaster  = $this->mCustomerLevel->FSaMCstLevAddUpdateMaster($aDataMaster);
            $aStaCstLevLang    = $this->mCustomerLevel->FSaMCstLevAddUpdateLang($aDataMaster);
            
            $aDataCstLev = $this->mCustomerLevel->FSoMCstLevGetLasMem($aDataMaster);
         
            if(!empty($aDataCstLev)){
                 $tCstCode = $aDataCstLev['FTCstCode'];
                 if($tCstCode!=''){
                    ///---------------QMember-----------------------//
                    $aQMemberParam = $this->FSaCCstFormatDataMemberV5($tCstCode);
                    $aMQParams = [
                        "queueName" => "QMember",
                        "exchangname" => "",
                        "params" => $aQMemberParam
                    ];
                    $this->FSxCCSTSendDataMemberV5($aMQParams);
               }
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $aReturn = array(
                    'nStaEvent'    => '900',
                    'tStaMessg'    => "Unsucess Add Event"
                );
            } else {
                $this->db->trans_commit();
                $aReturn = array(
                    'nStaCallBack'    => $this->session->userdata('tBtnSaveStaActive'),
                    'tCodeReturn'    => $aDataMaster['FTCstLevCode'],
                    'nStaEvent'        => '1',
                    'tStaMessg'        => 'Success Add Event'
                );
            }
            echo json_encode($aReturn);
        } catch (Exception $Error) {
            echo $Error;
        }
    }

    /**
     * Functionality : Event Delete Customer Level
     * Parameters : Ajax and Function Parameter
     * Creator : 20/09/2018 piya
     * Last Modified : -
     * Return : Status Delete Event
     * Return Type : String
     */
    public function FSaCstLevDeleteEvent()
    {
        $tIDCode = $this->input->post('tIDCode');
        $aDataMaster = array(
            'FTCstLevCode' => $tIDCode
        );

        $aResDel = $this->mCustomerLevel->FSnMCstLevDel($aDataMaster);
        $aReturn = array(
            'nStaEvent' => $aResDel['rtCode'],
            'tStaMessg' => $aResDel['rtDesc']
        );
        echo json_encode($aReturn);
    }

    /**
     * Functionality : Vatrate unique check
     * Parameters : $tSelect "CstLevcode"
     * Creator : 20/09/2018 piya
     * Last Modified : -
     * Return : Check status true or false
     * Return Type : String
     */
    public function FStCstLevUniqueValidate($tSelect = '')
    {

        if ($this->input->is_ajax_request()) { // Request check
            if ($tSelect == 'CstLevCode') {

                $tCstLevCode = $this->input->post('tCstLevCode');
                $oCustomerLevel = $this->mCustomerLevel->FSoMCstLevCheckDuplicate($tCstLevCode);

                $tStatus = 'false';
                if ($oCustomerLevel[0]->counts > 0) { // If have record
                    $tStatus = 'true';
                }
                echo $tStatus;

                return;
            }
            echo 'Param not match.';
        } else {
            echo 'Method Not Allowed';
        }
    }

    //Functionality : Function Event Multi Delete
    //Parameters : Ajax Function Delete Customer Level
    //Creator : 14/06/2018 wasin
    //Last Modified : -
    //Return : Status Event Delete And Status Call Back Event
    //Return Type : object
    public function FSoCstLevDeleteMulti()
    {
        $tCstLevCode = $this->input->post('tCstLevCode');

        $aCstLevCode = json_decode($tCstLevCode);
        foreach ($aCstLevCode as $oCstLevCode) {
            $aCstLev = ['FTCstLevCode' => $oCstLevCode];
            $this->mCustomerLevel->FSnMCstLevDel($aCstLev);
        }
        echo json_encode($aCstLevCode);
    }

    /**
     * Functionality : Delete vat rate
     * Parameters : -
     * Creator : 20/09/2018 piya
     * Last Modified : -
     * Return : Vat code
     * Return Type : Object
     */
    public function FSoCstLevDelete()
    {
        $tCstLevCode = $this->input->post('tCstLevCode');

        $aCstLev = ['FTCstLevCode' => $tCstLevCode];
        $this->mCustomerLevel->FSnMCstLevDel($aCstLev);
        echo json_encode($tCstLevCode);
    }


    /**
     * Functionality : Delete vat rate
     * Parameters : -
     * Creator : 20/09/2018 piya
     * Last Modified : -
     * Return : Vat code
     * Return Type : Object
     */
    public function FSaCCstFormatDataMemberV5($ptCstCode){


        $aCstMaster =  $this->db->where('FTCstCode',$ptCstCode)->get('TCNMCst')->row_array();
        $aCstCard_L = $this->db->where('FTCstCode',$ptCstCode)->get('TCNMCstCard')->row_array();
        $tCgpCode   = $this->db->where('FTSysCode','AMQMember')->where('FTSysSeq',1)->get('TSysConfig')->row_array()['FTSysStaUsrValue'];
        $tBchCenter = $this->db->where('FTSysCode','AMQMember')->where('FTSysSeq',2)->get('TSysConfig')->row_array()['FTSysStaUsrValue'];
       $aoTCNMMember = array(
           'FTCgpCode' => $tCgpCode,
           'FTMemCode' => $aCstMaster['FTCstCode'],
           'FTMemCardID' => $aCstMaster['FTCstCardID'],
           'FTMemTaxNo' => $aCstMaster['FTCstTaxNo'],
           'FTMemTel' => $aCstMaster['FTCstTel'],
           'FTMemFax' => $aCstMaster['FTCstFax'],
           'FTMemEmail' => $aCstMaster['FTCstEmail'],
           'FTMemSex' => $aCstMaster['FTCstSex'],
           'FDMemDob' => $aCstMaster['FDCstDob'],
           'FTOcpCode' => $aCstMaster['FTOcpCode'],
           'FTMemBusiness' => $aCstMaster['FTCstBusiness'],
           'FTMemBchHQ' => $aCstMaster['FTCstBchHQ'],
           'FTMemBchCode' => $aCstMaster['FTCstBchCode'],
           'FTLevCode'     => $aCstMaster['FTClvCode'],
           'FTMemStaActive' => $aCstMaster['FTCstStaActive'],
           'FDLastUpdOn' => $aCstMaster['FDLastUpdOn'],
           'FTLastUpdBy' => $aCstMaster['FTLastUpdBy'],
           'FDCreateOn' => $aCstMaster['FDCreateOn'],
           'FTCreateBy' => $aCstMaster['FTCreateBy'],
       );

       $aoTCNMMember_L = $this->mCustomer->FSaMCSTGetMasterLang4MQ($ptCstCode);
       $aoTCNMMemberAddress_L = $this->mCustomer->FSaMCSTGetAddress4MQ($ptCstCode);

       $aCstLev =  $this->db->where('FTClvCode',$aCstMaster['FTClvCode'])->get('TCNMCstLev')->row_array();
       $aCstLev_L =  $this->mCustomer->FSaMCSTGetCstLevLang4MQ($aCstMaster['FTClvCode'],$tCgpCode);
       
       $aoTCNMMemCard = array(
           'FTCgpCode'  => $tCgpCode,
           'FTMemCode'  => $ptCstCode,
           'FTMemCrdNo'  => $aCstCard_L['FTCstCrdNo'],
           'FDMemApply'  => $aCstCard_L['FDCstApply'],
           'FDMemCrdIssue'  => $aCstCard_L['FDCstCrdIssue'],
           'FDMemCrdExpire'  => $aCstCard_L['FDCstCrdExpire'],
       );

        if(!empty($aCstMaster['FTClvCode'])){
            $aoTCNMMemTier = array(
                'FTCgpCode'  => $tCgpCode,
                'FTLevCode'  => $aCstMaster['FTClvCode'],
                "FTLevStaAlwPnt"=> $aCstLev['FTLevStaAlwPnt'],
                "FCLevRtePntAmt"=> $aCstLev['FCLevRtePntAmt'],
                "FCLevRtePntQty"=> $aCstLev['FCLevRtePntQty'],
                "FNLevKeepTimes"=> null,
                "FCLevKeepPnt"=> null,
                "FCLevKeepAmt"=> null,
                "FCLevKeepTxnQty"=> null,
                "FNLevGetTimes"=> null,
                "FCLevGetPnt"=> null,
                "FCLevGetAmt"=> null,
                "FCLevGetQtyTxn"=> null,
                "FDLastUpdOn"=> $aCstLev['FDLastUpdOn'],
                "FTLastUpdBy"=> $aCstLev['FTLastUpdBy'],
                "FDCreateOn"=> $aCstLev['FDCreateOn'],
                "FTCreateBy"=> $aCstLev['FTCreateBy'],
                "FTLevPrevTier"=> null,
                "FTLevGetStaReset"=> null,
                "FTLevKeepStaReset"=> null,
            );
        }else{
            $aoTCNMMemTier = null;
        }

       $ptUpdData = array(
        'aoTCNMMember' => ($aoTCNMMember) ? array($aoTCNMMember) : NULL,
        'aoTCNMMember_L' => ($aoTCNMMember_L) ? $aoTCNMMember_L : NULL ,
        'aoTCNMMemCard' => ($aoTCNMMemCard) ? array($aoTCNMMemCard) : NULL,
        'aoTCNMMemAddress_L' => ($aoTCNMMemberAddress_L) ? $aoTCNMMemberAddress_L : NULL,
        'aoTCNMMemTier' => ($aoTCNMMemTier) ? array($aoTCNMMemTier) : NULL,
        'aoTCNMMemTier_L' => ($aCstLev_L) ? $aCstLev_L : NULL,
       );
       $aMemberParam = array(
           'ptFunction' => 'UPDATE_MEMBER',
           'ptSource' => $tBchCenter,
           'ptDest' => 'CENTER',
           'ptDelObj' => '',
           'ptUpdData' => json_encode($ptUpdData)
       );
    //    echo json_encode($ptUpdData);
    //    print_r($aMemberParam);
    //    die();
       return $aMemberParam;
    }


    /**
     * Functionality : Delete vat rate
     * Parameters : -
     * Creator : 20/09/2018 piya
     * Last Modified : -
     * Return : Vat code
     * Return Type : Object
     */
    public function FSxCCSTSendDataMemberV5($paParams){
        $tQueueName             = $paParams['queueName'];
        $aParams                = $paParams['params'];
        // $aParams['ptConnStr']   = DB_CONNECT;
        $tExchange              = EXCHANGE; // This use default exchange
        
        $oConnection = new AMQPStreamConnection(MemberV5_HOST, MemberV5_PORT, MemberV5_USER, MemberV5_PASS, MemberV5_VHOST);
        $oChannel = $oConnection->channel();
        $oChannel->queue_declare($tQueueName, false, true, false, false);
        $oMessage = new AMQPMessage(json_encode($aParams));
        $oChannel->basic_publish($oMessage, "", $tQueueName);
        $oChannel->close();
        $oConnection->close();
        return 1; /** Success */
    }
}
