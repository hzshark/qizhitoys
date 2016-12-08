<?php
namespace Home\Controller;

use Home\Common\CommonController;
use Think\Controller;
use Home\Service\Series;
use Home\Service\Help;
use Home\Service\User;

class IndexController extends Controller
{

    public function Index()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->display('index', 'utf-8');
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
        $this->display('cartoonlist', 'utf-8');
    }
    
    public function Addcartoon()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->display('addcartoon', 'utf-8');
    }
    
    public function Programa(){
        header("Content-Type:text/html; charset=utf-8");
        $this->display('programa', 'utf-8');
    }
}