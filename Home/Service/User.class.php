<?php
namespace Home\Service;

class User
{
    public function queryUserInfoByName($username)
    {
        $user = D("User");
        $where['username'] = $username;
        return  $user->where($where)
            ->field('userid,username,nickname,password,lastdate,lastip')
            ->find();
    }

    public function queryUserInfoById($userid)
    {
        $user = D("User");
        $where['userid'] = $userid;
        return  $user->where($where)
        ->field('userid,username,nickname,password,lastdate,lastip')
        ->find();
    }

    public function resetPassword($userid, $newpwd){
        $user = D("User");
        $where['userid'] = $userid;
        $data['password'] = $newpwd;
        $user->where($where)->save($data);
    }
}
