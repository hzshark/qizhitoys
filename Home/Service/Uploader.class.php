<?php
namespace Home\Service;

class Uploader
{

    public function uploaderImage()
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
                return array(
                    "status" => 0,
                    "msg" => $upload->getError()
                );
            } else { // 上传成功
                $thumb_file = "";
                foreach ($info as $file) {
                    $thumb_file = C('UPLOAD_PATH') . $file['savepath'] . $file['savename'];
                }
                return array(
                    "status" => 0,
                    "msg" => $thumb_file
                );
            }
        } else {
            return array(
                "status" => 1,
                "msg" => "没有上传文件"
            );
        }
    }

    public function Webuploader()
    {
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
                'jpeg',
                'mp4'
            ), // 设置附件上传类型,
            'autoSub' => true,
            'subName' => array(
                'date',
                'Ymd'
            )
        );
        $upload = new \Think\Upload($uploadconfig); // 实例化上传类
                                                    // 上传文件
        $info = $upload->upload();
        if (! $info) { // 上传错误提示错误信息
            return array(
                "status" => 1,
                "msg" => $upload->getError()
            );
        } else { // 上传成功 获取上传文件信息
            $pathArr = array();
            foreach ($info as $file) {
                array_push($pathArr, C('UPLOAD_PATH') . $file['savepath'] . $file['savename']);
            }
            return array(
                "status" => 0,
                "msg" => json_encode($pathArr)
            );
        }
    }

    public function UploadShowImage()
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
                ), // 设置附件上传类型,
                'autoSub' => true,
                'subName' => array(
                    'date',
                    'Ymd'
                )
            );
            $upload = new \Think\Upload($uploadconfig); // 实例化上传类
            $info = $upload->upload();
            if (! $info) { // 上传错误提示错误信息
                return array(
                    "status" => 1,
                    "msg" => $upload->getError()
                );
            } else {
                $pathArr = array();
                foreach ($info as $file) {
                    array_push($pathArr, C('UPLOAD_PATH') . $file['savepath'] . $file['savename']);
                }
                return array(
                    "status" => 0,
                    "msg" => $pathArr
                );
            }
        } else {
            return array(
                "status" => 0,
                "msg" => "上传文件空"
            );
        }
    }

    public function UploadFile($name, $note, $type, $force, $version, $versioncode)
    {
        if (! empty($_FILES)) {
            $uploadconfig = array(
                'maxSize' => C('UPLOAD_MAX_SIZE'), // 设置附件上传大小
                'rootPath' => C('UPLOAD_PATH'), // 设置附件上传根目录
                'savePath' => '', // 设置附件上传（子）目录
                'saveName' => $name,
                'exts' => array(
                    'dem',
                    'apk',
                    'txt'
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
            } else {
                foreach ($info as $file) {
                    $save_path = C('UPLOAD_PATH') . $file['savepath'] . $file['savename'];
                    $this->updateVersionInfoByType($save_path, $name, $note, $type, $force, $version, $versioncode);
                }
                return "上传文件成功";
            }
        } else {
            return "上传文件空";
        }
    }
}
