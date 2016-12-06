<?php
namespace Home\Controller;

use Think\Controller;
use Home\Service\Version;
use Home\Service\Series;

class ApiController extends Controller
{

    private $IS_ISO = '1';

    private $IS_ANDROID = '0';

    private $SUCCESS = 0;

    private $ERROR = 1;

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
            'status' => $this->ERROR,
            'msg' => '查询失败!',
            "content" => array()
        );

        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
            $device = isset($_POST['device']) ? $_POST['device'] : $this->IS_ANDROID;
            $ver = new Version();
            $info = $ver->queryInfoByType($device);
            $content = array(
                "version" => $info['version'],
                "isforce" => $info['isforce'] === 1 ? true : false,
                "versioncode" => $info['versioncode'],
                "filepath" => $info['filepath'],
                "verinfo" => $info['verinfo']
            );
            $ret["status"] = $this->SUCCESS;
            $ret["msg"] = "查询成功！";
            $ret["content"] = $content;
        }
        $this->ajaxReturn($ret);
    }

    public function GetMainTab()
    {
        $ret = array(
            'status' => $this->ERROR,
            'msg' => '查询失败!',
            "content" => array()
        );

        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
            $series = new Series();
            $infos = $series->getAllValidSeries();
            foreach ($infos as $info) {
                $content  [] = array(
                    "id"=>$info["id"],
                    "name"=>$info["name"],
                    "sicon"=>__ROOT__.$info["filepath"].$info["m_image"],
                    "url"=>__ROOT__.$info["filepath"].$info["image_name"],
                    "type"=>$info["show_type_id"],
                );
            }
            $ret["status"] = $this->SUCCESS;
            $ret["msg"] = "查询成功！";
            $ret["content"] = $content;
        }
        $this->ajaxReturn($ret);
    }
}