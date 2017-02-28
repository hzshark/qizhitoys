<?php
namespace Home\Controller;

use Think\Controller;
use Home\Service\Version;
use Home\Service\Series;
use Home\Service\Toys;
use Home\Service\Home;

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
                "filepath" => __ROOT__.$info['filepath'],
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
            $infos = $series->getValidSeries();
            foreach ($infos as $info) {
                $content[] = array(
                    "id" => $info["id"],
                    "name" => $info["name"],
                    "sicon" => __ROOT__ . $info["s_icon"],
                    "unsicon"=>__ROOT__ . $info["unsicon"],
                    "inner_img"=>__ROOT__ . $info["m_image"],
                    "url" => __ROOT__ . $info["image_name"],
                    "type" => $info["type_id"],
                    "show_type" => $info["show_type"]
                );
            }
            $ret["status"] = $this->SUCCESS;
            $ret["msg"] = "查询成功！";
            $ret["content"] = $content;
        }
        $this->ajaxReturn($ret);
    }

    public function GetToysList()
    {
        $ret = array(
            'status' => $this->ERROR,
            'msg' => '查询失败!',
            "content" => array()
        );

        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
            $id = isset($_POST['id']) ? $_POST['id'] : 0;
            $toys = new Toys();
            $infos = $toys->getCompagesToysBySId($id);
            foreach ($infos as $info) {
                $toyid = $info["id"];
                $details = $toys->getCompagesToysDetail($toyid);
                $showurl = array();
                foreach ($details as $detail) {
                    $showurl[] = __ROOT__ .  $detail["file_name"];
                }
                $content[] = array(
                    "id" => $toyid,
                    "name" => $info["name"],
                    "preview" => __ROOT__ . $info["image_name"],
                    "url" => $showurl,
                    "type" => $info["show_type"]
                );
            }

            $ret["status"] = $this->SUCCESS;
            $ret["msg"] = "查询成功！";
            $ret["content"] = $content;
        }
        $this->ajaxReturn($ret);
    }

    public function GetSplash()
    {
        $ret = array(
            'status' => $this->ERROR,
            'msg' => '查询失败!',
            "content" => array()
        );

        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
            $id = isset($_POST['id']) ? $_POST['id'] : 0;
            $home = new Home();
            $info = $home->getHomePage();

            $content = array(
                "id" => $info["id"],
                "name" => $info["name"],
                "url" => __ROOT__ . $info["filepath"] . $info["filename"]
            );

            $ret["status"] = $this->SUCCESS;
            $ret["msg"] = "查询成功！";
            $ret["content"] = $content;
        }
        $this->ajaxReturn($ret);
    }

    public function GetShoppingTab()
    {
        $ret = array(
            'status' => $this->ERROR,
            'msg' => '查询失败!',
            "content" => array()
        );

        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
            $id = isset($_POST['id']) ? $_POST['id'] : 0;
            $toys = new Toys();
            $infos = $toys->getColumn($id);
            foreach ($infos as $info) {
                $content[] = array(
                    "parent_id" => $id,
                    "id" => $info["id"],
                    "name" => $info["name"],
                    "sicon" => __ROOT__ . $info["filepath"] . $info["sicon"],
                    "url" => __ROOT__ . $info["filepath"] . $info["image_name"]
                );
            }

            $ret["status"] = $this->SUCCESS;
            $ret["msg"] = "查询成功！";
            $ret["content"] = $content;
        }
        $this->ajaxReturn($ret);
    }
    
    public function Help()
    {
        header("Content-Type:text/html; charset=utf-8");
        $ret = array(
            'status' => $this->ERROR,
            'msg' => '查询失败!',
            "content" => array()
        );
        if (IS_POST) {
            
        }
    }

    public function GetShoppingList()
    {
        $ret = array(
            'status' => $this->ERROR,
            'msg' => '查询失败!',
            "content" => array()
        );

        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
            $id = isset($_POST['id']) ? $_POST['id'] : 0;
            $subid = isset($_POST['subid']) ? $_POST['subid'] : 0;
            $toys = new Toys();
            $infos = $toys->getEffectiveShoppingsBySeriesId($id);
            $content = array();
            foreach ($infos as $info) {
                $content[] = array(
                    "id" => $info["id"],
                    "name" => $info["name"],
                    "tburl" => $info["tburl"],
                    "preview" => __ROOT__  . $info["image_name"]
                );
            }

            $ret["status"] = $this->SUCCESS;
            $ret["msg"] = "查询成功！";
            $ret["content"] = $content;
        }
        $this->ajaxReturn($ret);
    }
}