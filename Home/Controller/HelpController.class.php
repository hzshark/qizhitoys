<?php
namespace Home\Controller;

use Think\Controller;
use Home\Service\Login;

class HelpController extends Controller
{
   
    public function Index()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->show("help");
    }
    
    
}