<?php
ini_set("memory_limit","10M");

require_once(APPPATH . 'libraries/rabbitmq/vendor/autoload.php');

// require_once(APPPATH . 'libraries/async/vendor/autoload.php');

include APPPATH.'third_party/PHPExcel/Classes/PHPExcel.php';
include APPPATH.'third_party/PHPExcel/Classes/PHPExcel/IOFactory.php';
include APPPATH.'third_party/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

require APPPATH.'libraries/phpwkhtmltopdf/vendor/autoload.php';

use mikehaertl\wkhtmlto\Pdf;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
// use Spatie\Async\Pool;

class cExport extends MX_Controller {

    public function __construct() {
        $this->load->helper('report');
        $this->load->library('zip');
        $this->load->model('mCompany');
        parent::__construct();
    }

    public function mqListener() {
        /*$aQname = ['RPT1', 'RPT2', 'RPT3', 'RPT4'];
        $aSubQ = array();
        for($i = 0; $i<count($aQname); $i++){
            echo "Make Q: ". $aQname[$i], "\n";
            $aSubQ[$i] = new SubscribeMQ($aQname[$i]);
            $aSubQ[$i]->start();
        }
        
        for($i = 0; $i<count($aQname); $i++){
            // $aSubQ[$i]->join();
        }*/
        echo $this->config->item('mq_host');
        exit();
        echo "Connect : ".HOST.", ".PORT. " ".USER." ".PASS." ".VHOST , "\n";
        echo "=====================================================================================", "\n\n";
        $connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
        $channel = $connection->channel();
        $channel->queue_declare('RPTB1', false, false, false, false);
        $channel->queue_declare('RPTB2', false, false, false, false);
        
        echo " [*] Waiting for messages. To exit press CTRL+C\n\n\n";

        $callback = function ($msg) {
            echo ' [/] Received ', $msg->body, "\n";
            sleep(substr_count($msg->body, '.'));
            echo " [/] Done\n";
            
            if($msg->body == 'ok'){
                $paParams = [
                    'tBody' => $msg->body
                ];
                $this->precess($paParams);
                // $this->mqPublish($paParams);
                
                echo "=========== END =====================================================================", "\n\n";
            }
        };

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume('RPTB1', '', false, true, false, false, $callback);
        $channel->basic_consume('RPTB2', '', false, true, false, false, $callback);

        while (count($channel->callbacks)) {
            //$channel->wait();
        }

        $channel->close();
        $connection->close();
        
    }

    public function reciev($quename, $connection){
        echo "Begin>>>>>>>>", "\n";
        $channel = $connection->channel();
        echo "Channel>>>>>>", "\n";
        $channel->queue_declare($quename, false, true, false, false);
        
        echo " [*] Waiting for messages. To exit press CTRL+C\n\n\n";

        $callback = function ($msg) {
            echo ' [/] Received ', $msg->body, "\n";
            sleep(substr_count($msg->body, '.'));
            echo " [/] Done\n";
            
            if($msg->body == 'ok'){
                $paParams = [
                    'tBody' => $msg->body
                ];
                $this->precess($paParams);
                // $this->mqPublish($paParams);
                
                echo "=========== END ====================================================================", "\n\n";
    }
        };

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume($quename, '', false, true, false, false, $callback);

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        //$channel->close();
        //$connection->close();
    }
    
    public function precess($paParams = []){
        echo " [/] Process";
        for($i=0; $i<10; $i++){
            sleep(1);
            echo ".";
        }
        echo "\n";
        $this->zip('newfile');
    }
    
    public function mqPublish($paParams = []) {
        $tQueueName = 'RPTB_'; // $paParams['queueName'];
        $aParams = '{"rptCode":"021215", "progress": 30}'; // $paParams['params'];

        $oConnection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
        $oChannel = $oConnection->channel();
        $oChannel->queue_declare($tQueueName, false, false, false, false);
        $oMessage = new AMQPMessage(json_encode($aParams));
        $oChannel->basic_publish($oMessage, "", $tQueueName);
        $oChannel->close();
        $oConnection->close();
        
        echo ' [/] Send Progress.' , "\n";
    }
    
    public function zip($ptName) {
        $name1 = 'mydata11.txt';
        $data1 = 'A Data String!';

        $name2 = 'mydata12.txt';
        $data2 = 'A Data String!';

        $this->zip->add_data($name1, $data1);
        $this->zip->add_data($name2, $data2);

        // Write the zip file to a folder on your server. Name it "my_backup.zip"
        $this->zip->archive(APPPATH . "cache/$ptName.zip");

        // Download the file to your desktop. Name it "my_backup.zip"
        // $this->zip->download('my_backup.zip');
        echo ' [/] Zip Success.' , "\n";
    }

}


// $thread = new class extends Thread {
//  public function run() {
//   echo "Hello World\n";
//  }
// };

/*class SubscribeMQ extends Thread {

    private $SubQName;
    private $connection;
    private $channel;

    function __construct($QName = ''){
          $this->SubQName = $QName;
          $connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
          $this->channel = $connection->channel();
    }

    public function run(){
        /*while(true){
            sleep(1);
            echo $this->SubQName;
        }*
        echo "Run>>>>>>>>>>>>>>>>>", "\n";
        $oMQ = new MQ();
        $oMQ->reciev($this->SubQName, $this->channel);
        
    }
}*/



class MQ {

    public function reciev($quename, $channel){
        echo "Begin>>>>>>>>", "\n";
        // $channel = $connection->channel();
        echo "Channel>>>>>>", "\n";
        $channel->queue_declare($quename, false, true, false, false);
        
        echo " [*] Waiting for messages. To exit press CTRL+C\n\n\n";

        $callback = function ($msg) {
            echo ' [/] Received ', $msg->body, "\n";
            sleep(substr_count($msg->body, '.'));
            echo " [/] Done\n";
            
            if($msg->body == 'ok'){
                $paParams = [
                    'tBody' => $msg->body
                ];
                $this->precess($paParams);
                // $this->mqPublish($paParams);
                
                echo "=========== END ===================================================================", "\n\n";
    }
        };

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume($quename, '', false, true, false, false, $callback);

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        //$channel->close();
        //$connection->close();
    }
    
    public function precess($paParams = []){
        echo " [/] Process";
        for($i=0; $i<10; $i++){
            sleep(1);
            echo ".";
        }
        echo "\n";
        $this->zip('newfile');
    }
    
    public function mqPublish($paParams = []) {
        $tQueueName = 'RPTB_'; // $paParams['queueName'];
        $aParams = '{"rptCode":"021215", "progress": 30}'; // $paParams['params'];

        $oConnection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
        $oChannel = $oConnection->channel();
        $oChannel->queue_declare($tQueueName, false, false, false, false);
        $oMessage = new AMQPMessage(json_encode($aParams));
        $oChannel->basic_publish($oMessage, "", $tQueueName);
        $oChannel->close();
        $oConnection->close();
        
        echo ' [/] Send Progress.' , "\n";
    }
    
    public function zip($ptName) {
        $name1 = 'mydata11.txt';
        $data1 = 'A Data String!';

        $name2 = 'mydata12.txt';
        $data2 = 'A Data String!';

        $this->zip->add_data($name1, $data1);
        $this->zip->add_data($name2, $data2);

        // Write the zip file to a folder on your server. Name it "my_backup.zip"
        $this->zip->archive(APPPATH . "cache/$ptName.zip");

        // Download the file to your desktop. Name it "my_backup.zip"
        // $this->zip->download('my_backup.zip');
        echo ' [/] Zip Success.' , "\n";
    }

}

