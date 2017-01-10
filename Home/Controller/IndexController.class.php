<?php
namespace Home\Controller;

use Home\Common\CommonController;
use Think\Controller;
use Home\Service\Series;
use Home\Service\Help;
use Home\Service\User;
use Home\Service\Version;
use Home\Service\Toys;
use Home\Service\Uploader;
use Home\Service\Programa;

class IndexController extends Controller
{

    public function Index()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->display('index', 'utf-8');
    }

    public function Webuploader() {
        $uploader = new Uploader();
        // 上传文件
        $info = $uploader->Webuploader();
        if (1 == $info["status"]){
            $this->error($info["msg"]);
        }else {
            echo $info["msg"];
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
        if (IS_POST) {
            $seriesid= isset($_POST['Seriesname']) ? $_POST['Seriesname'] : '';
            $showtype = isset($_POST['showtype']) ? $_POST['showtype'] : '';
            $cartoonname = isset($_POST['cartoonname']) ? $_POST['cartoonname'] : 0;
            $uploader = new Uploader();
            $toy = new Toys();
            $showImg = "";
            $cartoon = $toy->queryCartoonByNameAndSeriesId($cartoonname, $seriesid);
            if ($cartoon){
                $this->error("本系列已经存在相同的动画名称，请更换名称！");
            }else{
                if ("video"==$showtype){

                }elseif ("pic" == $showtype){
                    $showImg = "";
                    $ret = $uploader->UploadShowImage();
                    if (1 == $ret['status']){
                        $this->error($ret['msg']);
                    }else{
                        $showImg = $ret['msg'][0];
                    }
                    $dImg = isset($_POST['uploader_files']) ? $_POST['uploader_files'] : [];
                    $cartoon = $toy->queryCartoonByNameAndSeriesId($cartoonname, $seriesid);
                    if ($cartoon){
                        $this->error("本系列已经存在相同的动画名称，请更换名称！");
                    }else{
                        $toy->AddCartoon($seriesid,$cartoonname, $showImg, $showtype, $dImg);
                    }
                    $this->success("添加动画成功！");
                }
            }
        }else{
            $series = new Series();
            $serielist = $series->getAllValidSeries();
            $this->assign("serielist", $serielist);
            $this->display('addcartoon', 'utf-8');
        }
    }
    
    public function DelPrograma(){
        header("Content-Type:text/html; charset=utf-8");
        $this->success("删除成功");
    }

    public function ShowPrograma(){
        header("Content-Type:text/html; charset=utf-8");
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $series = new Series();
        $ret = $series->querySeriesById($id);
        $this->assign("column", $ret);
        $this->display('showPrograma', 'utf-8');
    }
    
    public function editSeries(){
        header("Content-Type:text/html; charset=utf-8");
        if (IS_GET){
            $id = isset($_POST['id']) ? $_POST['id'] : '';
            $series = new Series();
            $ret = $series->querySeriesById($id);
            $this->assign("column", $ret);
            $this->display('editPrograma1', 'utf-8');
        }else{
            $this->display('editPrograma2', 'utf-8');
        }
        
    }
    
    public function editPrograma(){
        header("Content-Type:text/html; charset=utf-8");
        
            $id = isset($_GET['id']) ? $_GET['id'] : '';
            $series = new Series();
            $ret = $series->querySeriesById($id);
            $this->assign("column", $ret);
            $this->display('showPrograma', 'utf-8');
        
    }
    
    public function Programa(){
        header("Content-Type:text/html; charset=utf-8");
        $programa = new Programa();
        $columns = $programa->queryPrograma();
        $new_list = array();
        foreach ($columns as $column){
            $id = $column["id"];
            $ret = $programa->queryColumnBySId($id);
            $column["column"] = $ret;
            array_push($new_list, $column);
        }
        unset($columns);
//         var_dump($new_list);
        $this->assign("columns", $new_list);
        $this->display('programa', 'utf-8');
    }
    public function AddPrograma(){
        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
            $name= isset($_POST['name']) ? $_POST['name'] : '';
            $note= isset($_POST['note']) ? $_POST['note'] : '';
            $status= isset($_POST['status']) ? $_POST['status'] : '';
            
        }else{
            $this->display('addPrograma1', 'utf-8');
        }
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