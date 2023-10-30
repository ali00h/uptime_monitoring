<?php
$uptimeMonitoring = new UptimeMonitoring();
$uptimeMonitoring->run();

class UptimeMonitoring{
    private $env;
    private $cache_dir = "cache\\";

    function __construct() {
        $this->env = array();
        $this->env = array_merge($this->env, $_ENV);
        $this->env = array_merge($this->env, parse_ini_file('.env'));
        date_default_timezone_set($this->env['TIME_ZONE']);
    }

    public function run() {
        for($i=1 ; $i < 10 ; $i++){
            $this->checkURL($this->getEnv('URL' . (string)$i));
        }
    }



    private function checkURL($URL){
        if(isset($URL) && $URL != ''){
            if($this->URLValidForCheck($URL)){
                $responseCode = $this->getHttpResponseCode($URL);
                if($responseCode != 200){
                    $this->sendNotify($URL,$responseCode);
                    $this->saveCache($URL,'',$responseCode);
                }
                $this->pLog('URL: ' . $URL . '<br/>Response Code:' . $responseCode);
            }else{
                $this->pLog('URL: ' . $URL . '<br/>NotifyWait!');
            }
        }
    }

    private function getEnv($key){
        if (array_key_exists($key, $this->env)) 
            return $this->env[$key];
        else
            return '';
    }

    private function pLog($msg) {
        echo ' ' . $msg . '<br />';
    }

    private function getHttpResponseCode($url)
    {
        $context = stream_context_create(
            array(
                'http' => array(
                    'method' => 'HEAD'
                )
            )
        );
        $headers = @get_headers($url, false, $context);

        if(isset($headers) && $headers)
            return (int)substr($headers[0], 9, 3);
        else
            return 0;
    }

    private function sendNotify($URL,$ErrorCode){
        $this->sendBaleMsg(date('Y-m-d H:i:s') . '\\nURL Down Report: \\n' . $URL . '\\nError Code: ' . $ErrorCode);
    }

    private function sendBaleMsg($msg){
        $msg_bale = $msg;
        $curl_bale = curl_init();
        //$this->pLog($msg_bale);
        curl_setopt_array($curl_bale, array(
          CURLOPT_URL => 'https://tapi.bale.ai/bot' . $this->env['BALE_TOKEN'] . '/sendMessage',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{"chat_id": ' . $this->env['BALE_CHAT_ID'] . ', "text": "' . $msg_bale . '"}',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: text/plain'
          ),
        ));
        
        $response_bale = curl_exec($curl_bale);
        //$this->pLog($response_bale);
        curl_close($curl_bale);                
    }

    private function saveCache($URL,$response,$code){
        $filename = $this->cache_dir . md5($URL) . '.htm';
        file_put_contents($filename, '<br />-------------------<br />' . date('Y-m-d H:i:s') . '<br />-------------------<br />ResponseCode: ' . $code);
    }

    private function URLValidForCheck($URL){
        $isValid = false;
        $filename = $this->cache_dir . md5($URL) . '.htm';
        if (file_exists($filename)) {
            if(filemtime($filename) < (time() - (60 * $this->env['NOTIFY_WAIT_NEXT_SEND_MINUTES']))){
                $isValid = true;
            }else{
                $isValid = false;
            }
        }else{
            $isValid = true;
        }   
        return $isValid;     
    }
}