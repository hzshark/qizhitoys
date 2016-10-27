<?php
namespace Home\Controller;

use Home\Common\CommonController;


class IndexController extends CommonController
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
        $this->display('seriesmanage', 'utf-8');
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