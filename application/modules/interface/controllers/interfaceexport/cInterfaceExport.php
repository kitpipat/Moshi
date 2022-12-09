<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class cInterfaceExport extends MX_Controller {

    
    public function __construct(){
        parent::__construct ();
        $this->load->model('interface/interfaceexport/mInterfaceExport');
        // $this->load->model('interface/interfaceimport/mInterfaceImport');
    }

    public function index($nBrowseType,$tBrowseOption){

        $tUserCode = $this->session->userdata('tSesUserCode');
        $tLangEdit = $this->session->userdata("tLangEdit");
        $aParams = [
            'prefixQueueName' => 'LK_RPTransferResponseSAP',
            'ptUsrCode' => $tUserCode
        ];
        $this->FSxCIFXRabbitMQDeleteQName($aParams);
        $this->FSxCIFXRabbitMQDeclareQName($aParams);

        $aPackData = array(
            'nBrowseType'                   => $nBrowseType,
            'tBrowseOption'                 => $tBrowseOption,
            'aAlwEventInterfaceExport'      => FCNaHCheckAlwFunc('interfaceexport/0/0'), //Controle Event
            'vBtnSave'                      => FCNaHBtnSaveActiveHTML('interfaceexport/0/0'), //Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
            'aDataMasterImport'             => $this->mInterfaceExport->FSaMIFXGetHD($tLangEdit)
        );
        $this->load->view('interface/interfaceexport/wInterfaceExport',$aPackData);

    }

    function FSxCIFXRabbitMQDeclareQName($paParams){

        $tPrefixQueueName = $paParams['prefixQueueName'];
        $tQueueName = $tPrefixQueueName;
        
        $oConnection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
        $oChannel = $oConnection->channel();
        $oChannel->queue_declare($tQueueName, false, true, false, false);
        $oChannel->close();
        $oConnection->close();
        return 1; /** Success */
    }


    function FSxCIFXRabbitMQDeleteQName($paParams) {

        $tPrefixQueueName = $paParams['prefixQueueName'];
        $tQueueName = $tPrefixQueueName;
        // $oConnection = new AMQPStreamConnection('172.16.30.28', '5672', 'admin', '1234', 'Pandora_PPT1');
        $oConnection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
        $oChannel = $oConnection->channel();
        $oChannel->queue_delete($tQueueName);
        $oChannel->close();
        $oConnection->close();
        return 1; /** Success */
    
        
    }

    public function FSxCIFXCallRabitMQ(){
        $aIFXExport = $this->input->post('ocmIFXExport');
        if(!empty($aIFXExport)){

            $aPackData = array(
                // Sale
                'tBchCodeSale'          => $this->input->post('oetIFXBchCodeSale'),
                'dDateFromSale'         => $this->input->post('oetITFXDateFromSale'),
                'dDateToSale'           => $this->input->post('oetITFXDateToSale'),
                'tDocNoFrom'            => $this->input->post('oetITFXXshDocNoFrom'),
                'tDocNoTo'              => $this->input->post('oetITFXXshDocNoTo'),

                // Fin
                'tBchCodeFin'           => $this->input->post('oetIFXBchCodeFin'),
                'dDateFromFinance'      => $this->input->post('oetITFXDateFromFinance'),
                'dDateToFinance'        => $this->input->post('oetITFXDateToFinance')
            );

            foreach($aIFXExport as $nKey => $nValue){
               $aMQParams = $this->FSaCIFXGetFormatParam($nValue,$aPackData);
               FCNxCallRabbitMQ($aMQParams,false);
            }
        }
        return;
    }

    public function FSaCIFXGetFormatParam($pnFormat,$paPackData){

            if(!empty($paPackData['dDateFromSale'])){
                $aDateFromSale = explode("/", $paPackData['dDateFromSale']);
                $paPackData['dDateFromSale'] = $aDateFromSale[2].$aDateFromSale[1].$aDateFromSale[0];
            }
            if(!empty($paPackData['dDateToSale'])){
                $aDateToSale = explode("/", $paPackData['dDateToSale']);
                $paPackData['dDateToSale'] = $aDateToSale[2].$aDateToSale[1].$aDateToSale[0];
            }
            if(!empty($paPackData['dDateFromFinance'])){
                $aDateFromFinance = explode("/", $paPackData['dDateFromFinance']);
                $paPackData['dDateFromFinance'] = $aDateFromFinance[2].$aDateFromFinance[1].$aDateFromFinance[0];
            }
            if(!empty($paPackData['dDateToFinance'])){
                $aDateToFinance = explode("/", $paPackData['dDateToFinance']);
                $paPackData['dDateToFinance'] = $aDateToFinance[2].$aDateToFinance[1].$aDateToFinance[0];
            }

            // if($this->session->userdata("tSesUsrLevel") != "HQ" ){
            //     $tBchCode = $this->session->userdata("tSesUsrBchCode");
            // }else{
            //     $tBchCode = $this->session->userdata("tSesUsrBchCom");
            // }
            

            $aMQParams[1] = [
                "queueName"     => "LK_QTransferSAP",
                "exchangname"   => "",
                "params"        => [
                    "ptFunction"    =>  "EXPSALE",//ชื่อ Function
                    "ptSource"      =>  "BQ Process", //ต้นทาง
                    "ptDest"        =>  "HQ.AdaStoreBack",  //ปลายทาง
                    "ptData"        =>  [
                        "ptFilter"      => $paPackData['tBchCodeSale'],
                        "ptDateFrm"     => $paPackData['dDateFromSale'],
                        "ptDateTo"      => $paPackData['dDateToSale']
                    ]
                ]
            ];

            // "ptDocFrm"      =>  $paPackData['tDocNoFrom'],
                        // "ptDocTo"       =>  $paPackData['tDocNoTo']

            $aMQParams[2] = [
                "queueName"     => "LK_QTransferSAP",
                "exchangname"   => "",
                "params"        => [
                    "ptFunction"    =>  "EXPFIN",//ชื่อ Function
                    "ptSource"      =>  "BQ Process", //ต้นทาง
                    "ptDest"        =>  "HQ.AdaStoreBack",  //ปลายทาง
                    "ptData"        =>  [
                        "ptFilter"      => $paPackData['tBchCodeFin'],
                        "ptDateFrm"     => $paPackData['dDateFromFinance'],
                        "ptDateTo"      => $paPackData['dDateToFinance']
                    ]
                ]
            ];

            return $aMQParams[$pnFormat];

    }


    // public function FSxCINMDeleteQueue($paParams){
    //     $tQueuesName            = $paParams['queuesname'];
    //     $oConnection            = new AMQPStreamConnection(INTERFACE_HOST,INTERFACE_PORT,INTERFACE_USER,INTERFACE_PASS,INTERFACE_VHOST);
    //     $oChannel               = $oConnection->channel();

    //     $oChannel->queue_delete($paParams['queueName']);

    //     $oChannel->close();
    //     $oConnection->close();
    // }

    // public function FSxCINMCallRabbitQueue($paParams){
    //     $tQueuesName            = $paParams['queuesname'];
    //     $tExchangeName          = $paParams['exchangname'];
    //     $tBindingKey            = "";
    //     $aParams                = $paParams['params'];
    //     $aParams['ptConnStr']   = DB_CONNECT;
    //     $tExchange              = EXCHANGE;
    //     $oConnection            = new AMQPStreamConnection(INTERFACE_HOST,INTERFACE_PORT,INTERFACE_USER,INTERFACE_PASS,INTERFACE_VHOST);
    //     $oChannel               = $oConnection->channel();

    //     $oChannel->queue_declare($paParams['queuesname'],false,True,false,false);
    //     $oMesses = NEW AMQPMessage(json_encode($paParams['params']));
    //     $oChannel->basic_publish($oMesses,"",$paParams['queuesname']);

    //     $oChannel->close();
    //     $oConnection->close();
    // }


    // public function FSxCINMCallRabbitExchaneAndQueue($paParams) {
    //     $tQueuesName            = $paParams['queuesname'];
    //     $tExchangeName          = $paParams['exchangname'];
    //     $tBindingKey            = "";
    //     $aParams                = $paParams['params'];
    //     $aParams['ptConnStr']   = DB_CONNECT;
    //     $tExchange              = EXCHANGE;
    //     $oConnection            = new AMQPStreamConnection(INTERFACE_HOST,INTERFACE_PORT,INTERFACE_USER,INTERFACE_PASS,INTERFACE_VHOST);
    //     $oChannel               = $oConnection->channel();
    //     // Declare Exchange Name
    //     $oChannel->exchange_declare(
    //         $tExchangeName, 
    //         'fanout', # type
    //         false,    # passive
    //         true,    # durable
    //         false     # auto_delete
    //     );
    //     // Declare Queues Name
    //     $oChannel->queue_declare($tQueuesName,false,true,false,false);
    
    //     // Binding Queues To Exchange
    //     $oChannel->queue_bind($tQueuesName,$tExchangeName,$tBindingKey);
    
    //     $oMessage   = new AMQPMessage(json_encode($aParams));
    //     $oChannel->basic_publish($oMessage,$tQueuesName);
    //     $oChannel->close();
    //     $oConnection->close();
    //     return;
    // }

    // สินค้า
    // {
    //  "ptFunction":"ImpPdt" //ชื่อ Function
    //  ,"ptSource":"HQ.AdaStoreBack"      //ต้นทาง
    //  ,"ptDest":"BQ Process"        //ปลายทาง
    //   "ptData": {
    //                                                      }
    // }
    
    // ใบปรับราคา
    //  "ptFunction":"ImpAdj" //ชื่อ Function
    //  ,"ptSource":"HQ.AdaStoreBack"      //ต้นทาง
    //  ,"ptDest":"BQ Process"        //ปลายทาง
    //   "ptData": {
    //                                                      }
    // }
    
    // การขาย
    // {
    //  "ptFunction":"ExpSale" //ชื่อ Function
    //  ,"ptSource":"BQ Process"      //ต้นทาง
    //  ,"ptDest":"HQ.AdaStoreBack"        //ปลายทาง
    //   "ptData": {
    //                 "ptDateFrm": "20200303",   //จากวันที่
    //                 "ptDateTo": "20200303",   //ถึงวันที่
    //                  "ptDocFrm": "S0000120000-000000001",   //จากวันที่
    //                 "ptDocTo": "S0000120000-000000003",   //ถึงวันที่
    //                                                      }
    // }
    
    // การเงิน
    // {
    //  "ptFunction":"ExpFin" //ชื่อ Function
    //  ,"ptSource":"BQ Process"      //ต้นทาง
    //  ,"ptDest":"HQ.AdaStoreBack"        //ปลายทาง
    //   "ptData": {
    //                 "ptDateFrm": "20200303",   //จากวันที่
    //                 "ptDateTo": "20200303",   //ถึงวันที่
    //                                                      }
    // }


}    
?>

