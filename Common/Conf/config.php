<?php
return array(
	'DB_TYPE'           =>  'mysql',     	// 数据库类型
    'DB_HOST'           =>  '192.168.50.198', 	// 服务器地址
    'DB_NAME'           =>  'toys',        // 数据库名
    'DB_USER'           =>  'hzshark',     	// 用户名
    'DB_PWD'            =>  'ssqian123',     	// 密码
    'DB_PORT'           =>  '3306',     	// 端口
    'DB_PREFIX'         =>  '',      	// 数据库表前缀
    'DB_DEBUG'  		=>  true, 			// 数据库调试模式 开启后可以记录SQL日志
    'SHOW_PAGE_TRACE'   =>	true,   		// 显示页面Trace信息
    'DEFAULT_MODULE'     => 'Home', //默认模块
//     'DEFAULT_MODULE'       =>    'Admin',
    // 设置禁止访问的模块列表
    'MODULE_DENY_LIST'      =>  array('Common','Runtime'),
    'SESSION_AUTO_START' => true, //是否开启session
    'SESSION_OPTIONS'=> array(
        'expire'=>'3600'
    ),
    'APP_DEBUG' => true, //调试模式开关
    'TOKEN_ON' => true, //是否开启令牌验证
    'TOKEN_NAME'=>'__hash__',    // 令牌验证的表单隐藏字段名称
    'TOKEN_TYPE'=>'md5',  //令牌哈希验证规则默认为MD5
    'URL_MODEL' => 2, //URL模式：0 普通模式 1 PATHINFO 2 REWRITE 3 兼容模式
    'URL_ROUTER_ON' => true,
    'URL_PATHINFO_DEPR' => '/', //PATHINFO URL 模式下，各参数之间的分割符号
    'URL_CASE_INSENSITIVE'  =>  true, //设置为true的时候表示URL地址不区分大小写
    'DEFAULT_THEME' => '', //默认模板主题
    'URL_HTML_SUFFIX' => '.html', //URL伪静态后缀设置
    'DEFAULT_CHARSET' => 'utf-8', // 默认输出编码
    'DEFAULT_TIMEZONE' => 'PRC', // 默认时区
    'DEFAULT_AJAX_RETURN' => 'JSON', // 默认AJAX 数据返回格式,可选JSON XML ...
    //'APP_GROUP_LIST' => 'Index,Home,Admin', //项目分组
    //'DEFAULT_GROUP' => 'Home', //默认分组
    'DEFAULT_PageSize' => 15,
    /* Cookie设置 */
    'COOKIE_EXPIRE' => 3600, // Coodie有效期
    'COOKIE_DOMAIN' => '', // Cookie有效域名
    'COOKIE_PATH' => '/', // Cookie路径
    'COOKIE_PREFIX' => '', // Cookie前缀 避免冲突

    /* 静态缓存设置 */
    'HTML_CACHE_ON' => false, //默认关闭静态缓存
    'HTML_CACHE_TIME' => 60, //静态缓存有效期
    'HTML_READ_TYPE' => 0, //静态缓存读取方式 0 readfile 1 redirect
    'HTML_FILE_SUFFIX' => '.html', //默认静态文件后缀

    /* 错误设置 */
    'ERROR_MESSAGE' => '您浏览的页面暂时发生了错误！请稍后再试～', //错误显示信息,非调试模式有效
    'ERROR_PAGE' => 'Tpl/Public/error.html', // 错误定向页面
    //    'TMPL_EXCEPTION_FILE'=>'./App/Tpl/Public/error.html', // 定义公共错误模板

    /* 网站设置 */
    'SITE_TITLE' => 'CarGps', //网站title

    /* 网站日志设置 */
    'WEB_LOG_RECORD' => false, // 默认不记录日志
    'LOG_FILE_SIZE' => 2097152, // 日志文件大小限制
    'LOG_RECORD_LEVEL' => array('EMERG', 'ALERT', 'CRIT', 'ERR', 'WARN', 'NOTIC', 'INFO', 'DEBUG', 'SQL'), // 允许记录的日志级别
);