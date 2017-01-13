<?php
namespace Home\Service;
class Series
{
    Public $SHOW_IMAGE = 'pic';
    public $SHOW_VIDEO = "video";

    private function addSeriesToDB($name, $status, $note, $uploader_list=array()){
        $series = D("Series");
        $data['name'] = $name;
        $data['image_name'] = $uploader_list['home_image'];
        $data['m_image'] = $uploader_list['in_image'];
        $data['s_icon'] = $uploader_list['s_icon'];
        $data['unsicon'] = $uploader_list['unsicon'];
        $data['indate'] =  date('Y-m-d H:i:s',time());
        $data['moddate'] =  date('Y-m-d H:i:s',time());
        $data['status'] = $status;
        $data['show_type'] = $this->SHOW_VIDEO;
        $data['note'] = $note;
        $data['type_id'] = 1;
        $series->add($data);
    }

    private function check_name_exist($name){
        $series = D("Series");
        $where['name'] = $name;
        $seriesCount = $series->where($where)->count("id");
        if ($seriesCount > 0){
            return true;
        }
        return false;
    }

    public function querySeriesById($id){
        $series = D("Series");
        $where['id'] = $id;
        return $series->where($where)->find();
    }

    public function getSeries(){
        $series = D("Series");
        $where["type_id"] = 1;
        return $series->where($where)->order('indate')->limit(C("DEFAULT_PAGESIZE"))->select();
    }

    public function getAllValidSeries(){
        $series = D("Series");
        $where["status"] = 1;
        $where["type_id"] = 1;
        return $series ->where($where)->order('indate')->select();
    }

    public function checkHasCartoonBySeriesid($id){
        $model = D("Compages");
        $where["series_id"] = $id;
        return $model->where($where)->select();
    }

    public function delSeriesByid($id){
        $model = D("Series");
        $where["id"] = $id;
        return $model->where($where)->delete();
    }

    public function seriesUploadify($name, $status, $note='', $thumbWidth = '64' , $thumbHeight = '64')
    {
        if (! empty($_FILES)) {
            $uploadconfig = array(
                'maxSize' => C('UPLOAD_MAX_SIZE'), // 设置附件上传大小
                'rootPath' => C('UPLOAD_PATH'), // 设置附件上传根目录
                'savePath' => '', // 设置附件上传（子）目录
                'saveName' => array(
                    'uniqid',
                    ''
                ),
                'exts' => array(
                    'jpg',
                    'gif',
                    'png',
                    'jpeg'
                ),
                'autoSub' => true,
                'subName' => array(
                    'date',
                    'Ymd'
                )
            );
            $upload = new \Think\Upload($uploadconfig); // 实例化上传类
            $info = $upload->upload();
            if (! $info) { // 上传错误提示错误信息
                return $upload->getError();
            } else { // 上传成功
                $uploader_list = array();
                foreach ($info as $file) {
                    $uploader_list[$file['key']] = C('UPLOAD_PATH') . $file['savepath'].$file['savename'];
                }
                var_dump($uploader_list);

                $this->addSeriesToDB($name, $status, $note, $uploader_list);
                return "";
            }
        }
    }

    public function updateSeriesHasFile($id, $name, $status, $note){
            $uploadconfig = array(
                'maxSize' => C('UPLOAD_MAX_SIZE'), // 设置附件上传大小
                'rootPath' => C('UPLOAD_PATH'), // 设置附件上传根目录
                'savePath' => '', // 设置附件上传（子）目录
                'saveName' => array(
                    'uniqid',
                    ''
                ),
                'exts' => array(
                    'jpg',
                    'gif',
                    'png',
                    'jpeg'
                ),
                'autoSub' => true,
                'subName' => array(
                    'date',
                    'Ymd'
                )
            );
            $upload = new \Think\Upload($uploadconfig); // 实例化上传类
            $info = $upload->upload();
            var_dump($info);
            if (! $info) { // 上传错误提示错误信息
                return $upload->getError();
            } else { // 上传成功
                $uploader_list = array();
                foreach ($info as $file) {
                    if (isset($file['size']) && $file['size'] > 0){
                        $uploader_list[$file['key']] = C('UPLOAD_PATH') . $file['savepath'].$file['savename'];
                    }
                }
                $this->upSeriesToDB($id, $name, $status, $note, $uploader_list);
                return "";
            }
    }

    public function updateSeriesHasNotFile($id, $name, $status, $note){
        $this->upSeriesToDB($id, $name, $status, $note, null);
    }

    private function upSeriesToDB($id, $name, $status, $note, $uploader_list=array()){
        $series = D("Series");
        $where["id"] = $id;
        $data['name'] = $name;
        if (isset($uploader_list)){
            $data['image_name'] = $uploader_list['home_image'];
            $data['m_image'] = $uploader_list['in_image'];
            $data['s_icon'] = $uploader_list['s_icon'];
            $data['unsicon'] = $uploader_list['unsicon'];
        }
        var_dump($uploader_list);
        $data['moddate'] =  date('Y-m-d H:i:s',time());
        $data['status'] = $status;
        $data['note'] = $note;
        $series->where($where)->save($data);
    }
}