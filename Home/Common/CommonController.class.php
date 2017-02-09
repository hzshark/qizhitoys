<?php
namespace Home\Common;

use Think\Controller;

class CommonController extends controller
{

    public function _initialize()
    {
        $this->login_check();
    }

    public function login_check()
    {   
        if (session('?userid')) {
            $this->user = session('username');
        } else {
            $this->redirect('Login/index');
        }
    }

    
}