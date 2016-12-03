<?php
namespace Home\Service;

class Help
{
    public function saveHelp($id, $conent){
        $help = D("Help");
        $data['info'] = $conent;
        if ($id > 0){
            $where['id'] = $id;
            $help->where($where)->save($data);
        }else{
            $help->add($data);
        }
    }
    public function showHelp()
    {
        $help = D("Help");

        return $help
            ->field('id,info')
            ->find();
    }
}
