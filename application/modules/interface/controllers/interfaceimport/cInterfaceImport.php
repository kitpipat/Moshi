<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class cInterfaceimport extends MX_Controller {

    
    public function __construct(){
        parent::__construct ();
        $this->load->model('interface/interfaceimport/mInterfaceImport');
    }

    public function index($nBrowseType,$tBrowseOption){

        $aData['nBrowseType']                   = $nBrowseType;
        $aData['tBrowseOption']                 = $tBrowseOption;
		$aData['aAlwEventInterfaceImport']      = FCNaHCheckAlwFunc('interfaceimport/0/0'); //Controle Event
        $aData['vBtnSave']                      = FCNaHBtnSaveActiveHTML('interfaceimport/0/0'); //Load Html ของปุ่ม Save ที่เก็บ Session ปัจจุบัน
        $tLangEdit                              = $this->session->userdata("tLangEdit");
        
        $aData['aDataMasterImport'] = $this->mInterfaceImport->FSaMINMGetHD($tLangEdit);

        // echo '<pre>';
        // print_r($aData['aDataMasterImport']);
        // echo '</pre>';

        $tUserCode = $this->session->userdata('tSesUserCode');

        $aParams = [
            'prefixQueueName' => 'LK_RPTransferResponseSAP',
            'ptUsrCode' => $tUserCode
        ];

        $this->FSxCINMRabbitMQDeleteQName($aParams);
        $this->FSxCINMRabbitMQDeclareQName($aParams);

        $this->load->view('interface/interfaceimport/wInterfaceImport',$aData);
    }





    public function FSxCINMCallRabitMQ(){

       $aINMImport = $this->input->post('ocmINMImport');

        // if(!empty($aINMImport)){
        //     foreach($aINMImport as $nKey => $nValue){
                
               $aMQParams = $this->FSaCIMNGetFormatParamSAP();
               FCNxCallRabbitMQ($aMQParams,false);

        //     }
        // }

   
        return;
    }



    function FSxCINMRabbitMQDeclareQName($paParams){

        $tPrefixQueueName = $paParams['prefixQueueName'];
        $tQueueName = $tPrefixQueueName;
        
        $oConnection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
        $oChannel = $oConnection->channel();
        $oChannel->queue_declare($tQueueName, false, true, false, false);
        $oChannel->close();
        $oConnection->close();
        return 1; /** Success */
    }


    function FSxCINMRabbitMQDeleteQName($paParams) {

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



    public function FSaCIMNGetFormatParamSAP(){
        $tUserCode = $this->session->userdata('tSesUserCode');
            $aMQParams = [
                "queueName" => "LK_QTransferSAP",
                "exchangname" => "",
                "params" => [
                    "ptFunction"    => "IMP",
                    "ptSource"      => "HQ.AdaStoreBack",
                    "ptDest"        => "BQ Process",
                    "ptData" => [
                            "ptFilter"      => "",
                            "ptDateFrm"     => "",
                            "ptDateTo"      => ""
                        ]
                    ]
             ];

            //  $aMQParams[2] = [
            //     "queueName" => "LK_QTransferSAP",
            //     "exchangname" => "",
            //     "params" => [
            //         "ptFunction" =>"ImpAdj",//ชื่อ Function
            //         "ptSource" => "HQ.AdaStoreBack", //ต้นทาง
            //         "ptDest" => "BQ Process",  //ปลายทาง
            //         "ptCallBackProgress" => "LK_QImportProgressBar_".$tUserCode,
            //         "ptData" => array()
            //          ]
            //      ];
                 
            return $aMQParams;

    }



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

