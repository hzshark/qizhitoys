<?php
namespace Home\Controller;

use Think\Controller;
use Home\Service\Help;

class HelpController extends Controller
{
   
    public function Index()
    {
        header("Content-Type:text/html; charset=utf-8");
        $help = new Help();
        $infos = $help->showHelp();
        $this->show($infos["info"], "utf-8");
    }
    
    
}