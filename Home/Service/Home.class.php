<?php
namespace Home\Service;
class Home
{
    Public $SHOW_IMAGE = 1;
    public $SHOW_VIDEO = 2;

    private function addSeriesToDB($name, $image_name,  $m_image, $status, $filepath, $note, $filemd5){
        $series = D("Series");
        $data['name'] = $name;
        $data['image_name'] = $image_name;
        $data['m_image'] = $m_image;
        $data['indate'] =  date('Y-m-d H:i:s',time());
        $data['moddate'] =  date('Y-m-d H:i:s',time());
        $data['status'] = $status;
        $data['show_type_id'] = $this->SHOW_IMAGE;
        $data['filepath'] = $filepath;
        $data['note'] = $note;
        $data['filemd5'] = $filemd5;
        $series->add($data);
    }


    public function getHomePage(){
        $home = D("Home");
        return $home->where('status=1')->find();
    }
    
    public function updateStartPage($id, $name, $filepath){
        $home = M("Home");
        $where['id'] = $id;
        $home->where($where)->setField("status",0);
        $data['name'] = $name;
        $data['status'] = 1;
        $data['filepath'] = $filepath;
        $data['indate'] = date('Y-m-d H:i:s',time());;
        $home->add($data);                
    }

    public function getAllValidSeries(){
        $series = D("Series");

        return $series ->where('status=1')->order('indate')->select();
    }

    public function seriesUploadify($name, $status, $note='', $thumbWidth = '64' , $thumbHeight = '64')
    {
        if ($this->check_name_exist($name)){
            return "系列名称已存在，请更换名称或者修改原有系列信息！";
        }
        if (! empty($_FILES)) {
            $uploadconfig = array(
                'maxSize' => C('UPLOAD_MAX_SIZE'), // 设置附件上传大小
                'rootPath' => './Uploads/', // 设置附件上传根目录
                'savePath' => 'home/', // 设置附件上传（子）目录
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
                $image = new \Think\Image();
                foreach ($info as $file) {

                    $thumb_file = C('UPLOAD_PATH') . $file['savepath'] . $file['savename'];
                    $save_path = C('UPLOAD_PATH') . $file['savepath'] . 'mini_' . $file['savename'];
                    $image->open($thumb_file)
                        ->thumb($thumbWidth, $thumbHeight, \Think\Image::IMAGE_THUMB_FILLED)
                        ->save($save_path);
//                         $name, $m_name, $status, $filepath, $note, $filemd5
                        $this->addSeriesToDB($name, $file['savename'], 'mini_' . $file['savename'], $status, C('UPLOAD_PATH') . $file['savepath'], $note, $file['md5']);
                }
                return "";
            }
        }
    }
}