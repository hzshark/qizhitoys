<?php
namespace Home\Service;

class Toys
{
    public function getToysById($id)
    {
        $compages = D("Compages");
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
