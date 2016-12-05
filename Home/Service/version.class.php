<?php
namespace Home\Service;

class Version
{
    public function queryUserInfoByType($type)
    {
        $ver = D("Version");
        $where['type'] = $type;
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
