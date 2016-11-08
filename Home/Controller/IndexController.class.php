<?php
namespace Home\Controller;

use Home\Common\CommonController;
use Think\Controller;

class IndexController extends Controller
{

    public function index()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->display('index', 'utf-8');
    }

    public function Help()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->display('help', 'utf-8');
    }

    public function Seriesmanage()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->display('Index/seriesmanage', 'utf-8');
    }
    
    public function AddSeries(){
        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $note = isset($_POST['note']) ? $_POST['note'] : '';
            $status = isset($_POST['status']) ? $_POST['status'] : 0;
            $imagedata = isset($_POST['img']) ? $_POST['img'] : '';
            
            $result = true;
            if ($result) {
                // 设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
                $this->success('登入成功,页面调转中......', U("Home/Index"),1);
            } else {
                // 错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error('登入失败');
            }
        }
        
        $this->redirect('Index/Seriesmanage', 'utf-8');
    }

    public function Cartoonlist()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->display('cartoonlist', 'utf-8');
    }

    public function Restpass()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->display('restpass', 'utf-8');
    }

    public function Addcartoon()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->display('addcartoon', 'utf-8');
    }
}