<?php
namespace Home\Service;

class Version
{
    public function queryInfoByType($type)
    {
        $ver = D("Version");
        $where['type'] = $type;
        return  $ver->where($where)->find();
    }

    public function resetPassword($userid, $newpwd){
        $user = D("User");
        $where['userid'] = $userid;
        $data['password'] = $newpwd;
        $user->where($where)->save($data);
    }
}
