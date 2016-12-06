<?php
namespace Home\Service;

class Toys
{

    public function getCompagesToysById($id)
    {
        $compages = D("Compages");
        $where['series_id'] = $id;

        return $compages
            ->where($where)
//             ->buildSql();
        ->select();
    }

    public function getCompagesToysDetail($toyid){
        $compages = D("CompagesDetail");
        $where['p_id'] = $toyid;

        return $compages
        ->where($where)
        ->select();
    }

    public function getColumn($series_id)
    {
        $column = D("Column");
        $where['series_id'] = $series_id;
        return $column->where($where)->select();
    }
}
