<?php
namespace Home\Service;

class Login
{

    private $UNREG = 0;

    private $REG = 1;

    private $FAULT = 2;

    private $TRAVEL = 3;

    private $UNTRAVEL = 4;

    function login($username, $password, $IPaddress)
    {
        $login = D("User");
        $where['username'] = $username;
        $result = $login->where($where)
            ->field('userid,username,nickname,password,lastdate,lastip')
            ->find();
        
        
        // 验证用户名 对比 密码
        if ($result && $result['password'] == $result['password']) {
            // 存储session
            session('uid', $result['userid']); // 当前用户id
            session('nickname', $result['nickname']); // 当前用户昵称
            session('username', $result['username']); // 当前用户名
            session('lastdate', $result['lastdate']); // 上一次登录时间
            session('lastip', $result['lastip']); // 上一次登录ip
                                                  // 更新用户登录信息
            $where['userid'] = session('uid');
            $login->where($where)->setInc('loginnum'); // 登录次数加 1
            $data['lastdate'] = date("Y-m-d H:i:s");
            $data['lastip'] = $IPaddress;
            $login->where($where)->save($data); // 更新登录时间和登录ip
            return true;
        } else {
            return false;
        }
    }
}
