<?php
namespace Home\Controller;

use Think\Controller;
use Home\Service\Help;

class HelpController extends Controller
{

    public function Index()
    {
        header("Content-Type:text/html; charset=utf-8");
        echo '<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">';
        $help = new Help();
        $infos = $help->showHelp();
        $this->show($infos["info"], "utf-8");
    }
}