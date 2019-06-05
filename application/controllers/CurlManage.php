<?php defined('BASEPATH') or exit('No direct script access allowed');

class CurlManage extends BASE_Controller
{
    const PATH_SLACK_API = "dbConfig/slackApi.json";

    public function __construct()
    {
        parent::__construct();
    }

    public function sendMessage()
    {
        $slackUrl = json_decode(file_get_contents(self::PATH_SLACK_API));
        $rlt = array('result' => false, 'msg' => 'failure in sending.');
        $text = $this->_setText($this->dalInput);
        $data = array("username" => "Freshman", "text" => $text);
        switch ($this->dalInput->payloadBody->messageType) {
            case "admin":
                $url = $slackUrl->admin;
                $rlt = $this->_sendFunction($url, $data);
                break;
            case "notify":
                $url = $slackUrl->notify;
                $rlt = $this->_sendFunction($url, $data);
                break;
        }
        header("Content-Type: application/json");
        echo json_encode($rlt);
    }

    private function _setText(\Dal\Input $ciInput)
    {
        return "{$ciInput->payloadBody->messageContent}\n\n【Freshman Email】：{$this->userEmail}";
    }

    private function _sendFunction($url, $data)
    {
        $rtn = array('result' => false, 'msg' => '');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $rtn['msg'] = curl_exec($ch);
        $rtn['result'] = true;
        curl_close($ch);
        return $rtn;
    }
}
