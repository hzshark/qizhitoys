<?php
namespace Home\Controller;

use Home\Common\CommonController;
use Think\Controller;
use Home\Service\Series;
use Home\Service\Help;
use Home\Service\User;
use Home\Service\Version;
use Home\Service\Toys;

class IndexController extends Controller
{

    public function Index()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->display('index', 'utf-8');
    }

    public function Webuploader() {

        $uploadconfig = array(
            'maxSize' => C('UPLOAD_MAX_SIZE'), // 设置附件上传大小
            'rootPath' => C('UPLOAD_PATH'), // 设置附件上传根目录
            'savePath' => '', // 设置附件上传（子）目录
             'saveName' => array(
                    'uniqid',
                    ''
                ),
            'exts' =>  array('jpg', 'gif', 'png', 'jpeg'),// 设置附件上传类型,
            'autoSub' => true,
            'subName' => array('date', 'Ymd')
        );
        $upload = new \Think\Upload($uploadconfig); // 实例化上传类
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
            $pathArr = array();
            foreach($info as $file){
                array_push($pathArr, C('UPLOAD_PATH').$file['savepath'].$file['savename']);
            }
            echo json_encode($pathArr);
        }
    }

    public function Help()
    {
        header("Content-Type:text/html; charset=utf-8");
        $help = new Help();
        if (IS_POST) {
            $id = isset($_POST['id']) ? $_POST['id'] : '';
            $conent = isset($_POST['content']) ? $_POST['content'] : '';
            $help->saveHelp($id, $conent);
        }
        $infos = $help->showHelp();
        $this->assign("id", $infos['id']);
        $this->assign("info", $infos['info']);
        $this->display('help', 'utf-8');
    }

    public function Seriesmanage()
    {
        header("Content-Type:text/html; charset=utf-8");
        $series = new Series();
        $result = $series->getSeries();
        // var_dump($result);
        $this->assign("serieslist", $result);
        $this->display('Index/seriesmanage', 'utf-8');
    }

    public function AddSeries()
    {
        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $note = isset($_POST['note']) ? $_POST['note'] : '';
            $status = isset($_POST['status']) ? $_POST['status'] : 0;
            $series = new Series();
            $result = $series->seriesUploadify($name, $status, $note);
            if (empty($result)) {
                // 设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
                $this->success('登入成功,页面调转中......', U("Index/seriesmanage"), 5);
            } else {
                // 错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error($result);
            }
        }
    }


    public function Restpass()
    {
        header("Content-Type:text/html; charset=utf-8");
        $err = '';
        if (IS_POST) {
            $userid = isset($_POST['userid']) ? $_POST['userid'] : '';
            $mpass = isset($_POST['mpass']) ? $_POST['mpass'] : '';
            $newpass = isset($_POST['newpass']) ? $_POST['newpass'] : '';
            $renewpass = isset($_POST['renewpass']) ? $_POST['renewpass'] : 0;
            if ($newpass !== $renewpass){
                $err = "两次输入密码不一致!";
            }
            $user = new User();
            $user->resetPassword($userid, $newpass);
        }
        $this->assign("username",session('username'));
        $this->assign("userid",session('userid'));
        $this->assign("error_message",$err);
        $this->display('restpass', 'utf-8');
    }

    public function Cartoonlist()
    {
        header("Content-Type:text/html; charset=utf-8");
        $count = 0;
        $cartoonList = [];
        $pagenum = isset($_GET['p'])?$_GET['p']:0;
        $cartoon = new Toys();
        $series = new Series();
        if (IS_POST) {
            $series_id = isset($_POST['series_id']) ? $_POST['series_id'] : 0;
            $status = isset($_POST['status']) ? $_POST['status'] : -1;
            $keywords = isset($_POST['keywords']) ? $_POST['keywords'] : "";
            $count = $cartoon->getCartoonsAllCount($series_id, $status, $keywords);
            $cartoonList = $cartoon->getCartoonList($series_id, $status, $keywords, $pagenum);
        }

        $serielist = $series->getAllValidSeries();
        $Page = new \Think\Page($count,C("DEFAULT_PAGESIZE"));// 实例化分页类 传入总记录数和每页显示的记录数
        $Page -> setConfig('header','共%TOTAL_ROW%条');
        $Page -> setConfig('first','首页');
        $Page -> setConfig('last','共%TOTAL_PAGE%页');
        $Page -> setConfig('prev','上一页');
        $Page -> setConfig('next','下一页');
        $Page -> setConfig('link','indexpagenumb');//pagenumb 会替换成页码
        $Page -> setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $page = $Page->show();
        $this->assign("page",$page);
        $this->assign("serielist", $serielist);
        $this->assign("cartoonList", $cartoonList);
        $this->display('cartoonlist', 'utf-8');
    }

    public function Addcartoon()
    {
        header("Content-Type:text/html; charset=utf-8");
        $cartoon = new Toys();
        $series = new Series();
        $serielist = $series->getAllValidSeries();
        $this->assign("serielist", $serielist);
        $this->display('addcartoon', 'utf-8');
    }

    public function Programa(){
        header("Content-Type:text/html; charset=utf-8");
        $this->display('programa', 'utf-8');
    }
    public function AddPrograma(){
        header("Content-Type:text/html; charset=utf-8");
        $this->display('addPrograma', 'utf-8');
    }
    public function Version(){
        header("Content-Type:text/html; charset=utf-8");
        $err = '';
        $ver = new Version();
        if (IS_POST) {
            $name= isset($_POST['name']) ? $_POST['name'] : '';
            $note = isset($_POST['note']) ? $_POST['note'] : '';
            $type = isset($_POST['type']) ? $_POST['type'] : 0;
            $force = isset($_POST['force']) ? $_POST['force'] : 0;
            $version = isset($_POST['version']) ? $_POST['version'] : '';
            $versioncode = isset($_POST['versioncode']) ? $_POST['versioncode'] : '';
            $ret = $ver->UploadFile($name, $note, $type, $force ,$version, $versioncode);
        }
        $vers = $ver->queryVersionInfo();
        $this->assign("vers",$vers);
        $this->display('updateVersion', 'utf-8');
    }
}