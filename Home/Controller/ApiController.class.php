<?php
namespace Home\Controller;

use Think\Controller;
use Home\Service\Login;

class ApiController extends Controller
{

    private $IS_ISO = '1';
    private $IS_ANDROID = '0';
    private $SUCCESS = 0;
    private $ERROR = 0;

    private function getIPaddress()

    {
        $IPaddress = '';

        if (isset($_SERVER)) {

            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {

                $IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else
                if (isset($_SERVER["HTTP_CLIENT_IP"])) {

                    $IPaddress = $_SERVER["HTTP_CLIENT_IP"];
                } else {

                    $IPaddress = $_SERVER["REMOTE_ADDR"];
                }
        } else {

            if (getenv("HTTP_X_FORWARDED_FOR")) {

                $IPaddress = getenv("HTTP_X_FORWARDED_FOR");
            } else
                if (getenv("HTTP_CLIENT_IP")) {

                    $IPaddress = getenv("HTTP_CLIENT_IP");
                } else {

                    $IPaddress = getenv("REMOTE_ADDR");
                }
        }

        return $IPaddress;
    }
    public function GetVersion()
    {
        $ret = array(
            'status'=>$this->ERROR,
            'msg' => '查询失败!',
            "content"
        );
        $content =array(
            "version"=>'1.0.0',
            "isforce"=>false,
            "versioncode"=>1,
            "filepath"=>"",
            "verinfo"=>'1,更新了aaa'
        );
        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
            $device = isset($_POST['device']) ? $_POST['device'] : $this->IS_ANDROID;

        }
        $this->ajaxReturn($ret);
    }

}