<?php
namespace Home\Service;

class Version
{

    public function queryInfoByType($type)
    {
        $ver = D("Version");
        $where['type'] = $type;
        return $ver->where($where)->find();
    }

    public function queryVersionInfo()
    {
        $ver = D("Version");
        return $ver->select();
    }

    private function updateVersionInfoByType($filepath, $name, $note, $type, $force, $version, $versioncode)
    {
        $ver = D("Version");
        $where['type'] = $type;
        $data['version'] = $version;
        $data['versioncode'] = $versioncode;
        $data['isforce'] = $force;
        $data['filepath'] = $filepath;
        $data['verinfo'] = $note;
        if ($ver->where($where)->count("type") > 0) {
            $ver->where($where)->save($data);
        } else {
            $data['type'] = $type;
            $ver->add($data);
        }
    }
    
    public function UploadPackageFile($note, $type, $force, $version, $versioncode)
    {
        if (! empty($_FILES)) {
            $uploadconfig = array(
                'maxSize' => C('UPLOAD_MAX_SIZE'), // 设置附件上传大小
                'rootPath' => C('UPLOAD_PATH'), // 设置附件上传根目录
                'savePath' => '', // 设置附件上传（子）目录
                'saveName' => C('PACKAGE_NAME'),
                'exts' => array(
                    'dem',
                    'apk',
                    'txt'
                ),
                'replace' => true,
                'autoSub' => true,
                'subName' => C('PACKAGE_SUB_DIR'),
            );
            $upload = new \Think\Upload($uploadconfig); // 实例化上传类
            $info = $upload->upload();
            if (! $info) { // 上传错误提示错误信息
                return array(
                    'status' => 0,
                    'msg' => $upload->getError()
                );
            } else {
                $file = $info['packagefile'];
                $save_path = C('UPLOAD_PATH') . $file['savepath'] . $file['savename'];
                
                $this->updateVersionInfoByType($save_path, C('PACKAGE_NAME'), $note, $type, $force, $version, $versioncode);
    
                return array(
                    'status' => 1,
                    'msg' => "上传文件成功"
                );
            }
        } else {
            return array(
                'status' => 0,
                'msg' => "上传文件空"
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
                return array(
                    'status' => 0,
                    'msg' => $upload->getError()
                );
            } else {
                $file = $info['packagefile'];
                $save_path = C('UPLOAD_PATH') . $file['savepath'] . $file['savename'];
                $this->updateVersionInfoByType($save_path, $name, $note, $type, $force, $version, $versioncode);

                return array(
                    'status' => 1,
                    'msg' => "上传文件成功"
                );
            }
        } else {
            return array(
                'status' => 0,
                'msg' => "上传文件空"
            );
        }
    }
}
