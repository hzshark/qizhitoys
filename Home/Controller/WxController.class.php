<?php
namespace Home\Controller;

use Think\Controller;
use Home\Service\Version;

class WxController extends Controller
{

    public function Index()
    {
            header("Content-Type:text/html; charset=utf-8");
            $this->assign("ios_url", C('IOS_DOWNLOAD_URL'));
            $this->assign("android_url", C('ANDROID_DOWNLOAD_URL'));
            $this->display('wxdownload', 'utf-8');
        
    }
}