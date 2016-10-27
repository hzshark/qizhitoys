<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);

// 定义应用目录
define('COMMON_PATH',dirname(__FILE__) . '/Common/');
define('RUNTIME_PATH','./Runtime/');
define('BUILD_DIR_SECURE', true);//如果你的环境足够安全，不希望生成目录安全文件，可以在入口文件里面关闭目录安全文件的生成

// // 绑定Admin模块到当前入口文件
// define('BIND_MODULE','Admin');
// // 生成更多的控制器类
// define('BUILD_CONTROLLER_LIST','Index,User,Report');
// define('BUILD_MODEL_LIST','User,Report');

// define('BIND_MODULE', 'Home'); // 绑定Home模块到当前入口文件
// define('BIND_CONTROLLER','Index'); // 绑定Index控制器到当前入口文件

# the custom variables
define('PAGE_SIZE', 15);
define('CR', "\r");
define('LF', "\n");
define('CRLF', CR.LF);


// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单