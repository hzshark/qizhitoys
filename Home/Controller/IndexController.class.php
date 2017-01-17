<?php
namespace Home\Controller;

use Home\Common\CommonController;
use Think\Controller;
use Home\Service\Series;
use Home\Service\Help;
use Home\Service\User;
use Home\Service\Version;
use Home\Service\Toys;
use Home\Service\Uploader;
use Home\Service\Programa;

class IndexController extends Controller
{

    public function Index()
    {
        header("Content-Type:text/html; charset=utf-8");
        $this->display('index', 'utf-8');
    }

    public function Webuploader()
    {
        $uploader = new Uploader();
        // 上传文件
        $info = $uploader->Webuploader();
        if (1 == $info["status"]) {
            $this->error($info["msg"]);
        } else {
            echo $info["msg"];
        }
    }

    public function Help()
    {
        header("Content-Type:text/html; charset=utf-8");
        $help = new Help();
        if (IS_POST) {
            $id = isset($_POST['id']) ? $_POST['id'] : '';
            $conent = isset($_POST['content']) ? $_POST['content'] : '';
            $help->saveHelp($id, $conent);
        }
        $infos = $help->showHelp();
        $this->assign("id", $infos['id']);
        $this->assign("info", $infos['info']);
        $this->display('help', 'utf-8');
    }

    public function Seriesmanage()
    {
        header("Content-Type:text/html; charset=utf-8");
        $series = new Series();
        $result = $series->getSeries();
        // var_dump($result);
        $this->assign("serieslist", $result);
        $this->display('Index/seriesmanage', 'utf-8');
    }

    public function AddSeries()
    {
        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $note = isset($_POST['note']) ? $_POST['note'] : '';
            $status = isset($_POST['status']) ? $_POST['status'] : 0;
            $series = new Series();
            $result = $series->seriesUploadify($name, $status, $note);
            if (empty($result)) {
                // 设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
                $this->success('登入成功,页面调转中......', U("Index/seriesmanage"), 5);
            } else {
                // 错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error($result);
            }
        } else {
            $this->display('Index/addSeries', 'utf-8');
        }
    }

    public function editSeries()
    {
        header("Content-Type:text/html; charset=utf-8");
        $series = new Series();
        if (IS_POST) {
            $id = isset($_POST['id']) ? $_POST['id'] : '';
            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $note = isset($_POST['note']) ? $_POST['note'] : '';
            $status = isset($_POST['status']) ? $_POST['status'] : 0;

            $home_image = $_FILES['home_image']['size'];
            $in_image = $_FILES['in_image']['size'];
            $s_icon = $_FILES['s_icon']['size'];
            $unsicon = $_FILES['unsicon']['size'];
            $result = "";
            if (empty($home_image) && empty($in_image) && empty($s_icon)&&empty($unsicon)) {
                $series->updateSeriesHasNotFile($id, $name, $status, $note);
            } else {
                $result = $series->updateSeriesHasFile($id, $name, $status, $note);
            }
            if (empty($result)) {
                // 设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
                $this->success('登入成功,页面调转中......', U("Index/seriesmanage"), 3);
            } else {
                // 错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error($result);
            }
        } else {
            $id = isset($_GET['id']) ? $_GET['id'] : '';
            $series_info = $series->querySeriesById($id);
            $this->assign("series_info", $series_info);
            $this->display('Index/editSeries', 'utf-8');
        }
    }

    public function delSeries()
    {
        header("Content-Type:text/html; charset=utf-8");
        $series = new Series();
        if (IS_POST) {
            $id = isset($_POST['id']) ? $_POST['id'] : '';
            $donghuas = $series->checkHasCartoonBySeriesid($id);
            if (count($donghuas) > 0) {
                $data['status'] = 0;
                $data['msg'] = '该系列还存在动画没有删除，请先删除动画！';
                $this->ajaxReturn($data);
            } else {
                $series->delSeriesByid($id);
                $data['status'] = 1;
                $data['msg'] = '删除系列成功！';
                $this->ajaxReturn($data);
            }

            $data['status'] = 0;
            $data['msg'] = '删除错误！';
            $this->ajaxReturn($data);
        }
    }

    public function Restpass()
    {
        header("Content-Type:text/html; charset=utf-8");
        $err = '';
        if (IS_POST) {
            $userid = isset($_POST['userid']) ? $_POST['userid'] : '';
            $mpass = isset($_POST['mpass']) ? $_POST['mpass'] : '';
            $newpass = isset($_POST['newpass']) ? $_POST['newpass'] : '';
            $renewpass = isset($_POST['renewpass']) ? $_POST['renewpass'] : 0;
            if ($newpass !== $renewpass) {
                $err = "两次输入密码不一致!";
            }
            $user = new User();
            $user->resetPassword($userid, $newpass);
        }
        $this->assign("username", session('username'));
        $this->assign("userid", session('userid'));
        $this->assign("error_message", $err);
        $this->display('restpass', 'utf-8');
    }

    public function Cartoonlist()
    {
        header("Content-Type:text/html; charset=utf-8");
        $count = 0;
        $cartoonList = [];
        $pagenum = isset($_GET['p']) ? $_GET['p'] : 0;
        $cartoon = new Toys();
        $series = new Series();

        $series_id = isset($_POST['series_id']) ? $_POST['series_id'] : 0;
        $status = isset($_POST['status']) ? $_POST['status'] : - 1;
        $keywords = isset($_POST['keywords']) ? $_POST['keywords'] : null;
        $count = $cartoon->getCartoonsAllCount($series_id, $status, $keywords);
        $cartoonList = $cartoon->getCartoonList($series_id, $status, $keywords, $pagenum);

        $serielist = $series->getAllValidSeries();
        $Page = new \Think\Page($count, C("DEFAULT_PAGESIZE")); // 实例化分页类 传入总记录数和每页显示的记录数
        $Page->setConfig('header', '共%TOTAL_ROW%条');
        $Page->setConfig('first', '首页');
        $Page->setConfig('last', '共%TOTAL_PAGE%页');
        $Page->setConfig('prev', '上一页');
        $Page->setConfig('next', '下一页');
        $Page->setConfig('link', 'indexpagenumb'); // pagenumb 会替换成页码
        $Page->setConfig('theme', '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $page = $Page->show();
        $this->assign("page", $page);
        $this->assign("serielist", $serielist);
        $this->assign("cartoonList", $cartoonList);
        $this->display('cartoonlist', 'utf-8');
    }

    public function Addcartoon()
    {
        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
            $seriesid = isset($_POST['Seriesname']) ? $_POST['Seriesname'] : '';
            $showtype = isset($_POST['showtype']) ? $_POST['showtype'] : '';
            $cartoonname = isset($_POST['cartoonname']) ? $_POST['cartoonname'] : 0;
            $uploader = new Uploader();
            $toy = new Toys();
            $showImg = "";
            $cartoon = $toy->queryCartoonByNameAndSeriesId($cartoonname, $seriesid);
            if ($cartoon) {
                $this->error("本系列已经存在相同的动画名称，请更换名称！");
            } else {
                $showImg = "";
                $ret = $uploader->UploadShowImage();
                if (1 == $ret['status']) {
                    $this->error($ret['msg']);
                } else {
                    $showImg = $ret['msg'][0];
                }
                $dImg = isset($_POST['uploader_files']) ? $_POST['uploader_files'] : [];

                $toy->AddCartoon($seriesid, $cartoonname, $showImg, $showtype, $dImg);

                $this->success("添加动画成功!", "Cartoonlist", 3);
            }
        } else {
            $series = new Series();
            $serielist = $series->getAllValidSeries();
            $this->assign("serielist", $serielist);
            $this->display('addcartoon', 'utf-8');
        }
    }

    public function EditCartoon()
    {
        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
            $id = isset($_POST['id']) ? $_POST['id'] : '';
            $seriesid = isset($_POST['Seriesname']) ? $_POST['Seriesname'] : '';
            $showtype = isset($_POST['showtype']) ? $_POST['showtype'] : '';
            $cartoonname = isset($_POST['cartoonname']) ? $_POST['cartoonname'] : 0;
            $status = isset($_POST['status']) ? $_POST['status'] : 0;
            $uploader = new Uploader();
            $toy = new Toys();
            $showImg = "";
            $cartoon1 = $toy->getCompagesToysById($id);
            $cartoon2 = $toy->queryCartoonByNameAndSeriesId($cartoonname, $seriesid);
            if ($cartoon2 && $cartoon1["name"] != $cartoonname) {
                $this->error("本系列已经存在相同的动画名称，请更换名称！");
            } else {
                $showImg = "";
                if ($_FILES['pre_img']['size'] >0){
                    $ret = $uploader->UploadShowImage();
                    if (1 == $ret['status']) {
                        $this->error($ret['msg']);
                    } else {
                        $showImg = $ret['msg'][0];
                    }
                }
                $toy->updateCartoon($id, $cartoonname, $showtype, $status,$showImg);
                $this->success("修改动画成功!", "Cartoonlist", 3);
            }
        } else {
            $toys = new Toys();
            $id = isset($_GET['id']) ? $_GET['id'] : - 1;
            $toy = $toys->getCompagesToysById($id);
            $showimg = $toys->getCompagesToysDetail($id);
            $this->assign("toy", $toy);
            $this->assign("showimg", $showimg);
            $this->display('editCartoon', 'utf-8');
        }
    }

    public function delCartoon()
    {
        header("Content-Type:text/html; charset=utf-8");
        $toy = new Toys();
        if (IS_POST) {
            $id = isset($_POST['id']) ? $_POST['id'] : '';
            $toy->delCartoon($id);

            $data['status'] = 1;
            $data['msg'] = '删除成功！';
            $this->ajaxReturn($data);
        }
    }

    public function DelPrograma()
    {
        header("Content-Type:text/html; charset=utf-8");
        $programa = new Programa();
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $programa->delByProgramaId($id);
        $this->success("删除成功",  "Programa", 3);
    }

    public function ShowPrograma()
    {
        header("Content-Type:text/html; charset=utf-8");
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $series = new Series();
        $ret = $series->querySeriesById($id);
        $this->assign("column", $ret);
        $this->display('showPrograma', 'utf-8');
    }

    public function editPrograma()
    {
        header("Content-Type:text/html; charset=utf-8");
        if (IS_GET) {
            $id = isset($_POST['id']) ? $_POST['id'] : '';
            $series = new Series();
            $ret = $series->querySeriesById($id);
            $this->assign("programa", $ret);
            $this->display('editPrograma', 'utf-8');
        } else {
            $id = isset($_POST['id']) ? $_POST['id'] : '';
            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $note = isset($_POST['note']) ? $_POST['note'] : '';
            $status = isset($_POST['status']) ? $_POST['status'] : 0;

            $home_image = $_FILES['home_image']['size'];
            $in_image = $_FILES['in_image']['size'];
            $s_icon = $_FILES['s_icon']['size'];
            $unsicon = $_FILES['unsicon']['size'];
            $result = "";
            if (empty($home_image) && empty($in_image) && empty($s_icon)&&empty($unsicon)) {
                $series->updateSeriesHasNotFile($id, $name, $status, $note);
            } else {
                $result = $series->updateSeriesHasFile($id, $name, $status, $note);
            }
            if (empty($result)) {
                // 设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
                $this->success('编辑成功,页面调转中......', 'ShowPrograma?id=' .$id, 3);
            } else {
                // 错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error($result);
            }

            $this->success('操作完成', 'ShowColumn?id=' . $id, 3);
        }
    }

    public function Programa()
    {
        header("Content-Type:text/html; charset=utf-8");
        $programa = new Programa();
        $columns = $programa->queryPrograma();
        $new_list = array();
        foreach ($columns as $column) {
            $id = $column["id"];
            $ret = $programa->queryColumnBySId($id);
            $column["column"] = $ret;
            array_push($new_list, $column);
        }
        unset($columns);
        // var_dump($new_list);
        $this->assign("columns", $new_list);
        $this->display('programa', 'utf-8');
    }

    public function AddPrograma()
    {
        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
         $name = isset($_POST['name']) ? $_POST['name'] : '';
            $note = isset($_POST['note']) ? $_POST['note'] : '';
            $status = isset($_POST['status']) ? $_POST['status'] : 0;
            $series = new Series();
            $result = $series->AddPrograma($name, $status, $note);
            if (empty($result)) {
                // 设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
                $this->success('栏目添加成功,页面调转中......', U("Index/Programa"), 5);
            } else {
                // 错误页面的默认跳转页面是返回前一页，通常不需要设置
                $this->error($result);
            }
        } else {
            $this->display('addPrograma', 'utf-8');
        }
    }

    public function ShowColumn()
    {
        header("Content-Type:text/html; charset=utf-8");
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        $programa = new Programa();
        $ret = $programa->queryColumnById($id);

        $this->assign("column", $ret);
        $this->display('showColumn', 'utf-8');
    }

    public function AddColumn()
    {
        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
            $sid = isset($_POST['sid']) ? $_POST['sid'] : '';
            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $status = isset($_POST['status']) ? $_POST['status'] : '';
            $programa = new Programa();
            $ret = $programa->queryColumnBySIdAndName($sid, $name);
            if ($ret) {
                $this->error("这个栏目的名称已经存在，请更换名称！");
            } else {
                $uploader = new Uploader();
                $ret = $uploader->uploaderImage();
                if (1 == $ret['status']) {
                    $this->error($ret['msg']);
                } else {
                    $img_path = $ret['msg'];
                    $toy = new Toys();
                    $programa = new Programa();
                    $res = $toy->getPathAndName($img_path);
                    $programa->addColumn($sid, $name, $status, $res["path"], $res["name"]);
                    $p_ret = $programa->queryColumnBySIdAndName($sid, $name);
                    $this->assign("column", $p_ret);
                    $this->success('二级栏目添加成功', "Programa", 5);
                }
            }
        } else {
            $sid = isset($_GET['id']) ? $_GET['id'] : '';
            $this->assign("sid", $sid);
            $this->display('addColumn', 'utf-8');
        }
    }

    public function Shoppinglist()
    {
        header("Content-Type:text/html; charset=utf-8");
        $count = 0;
        $cartoonList = [];
        $pagenum = isset($_GET['p']) ? $_GET['p'] : 0;
        $toy = new Toys();
        $programa = new Programa();
        $series_id = isset($_POST['series_id']) ? $_POST['series_id'] : 0;
        $status = isset($_POST['status']) ? $_POST['status'] : - 1;
        $keywords = isset($_POST['keywords']) ? $_POST['keywords'] : null;
        $count = $toy->getShoppingCount($series_id, $status, $keywords);
        $shoppingList = $toy->getShoppings($series_id, $status, $keywords, $pagenum);
        $programalist = $programa->getAllValidColumn();
        $Page = new \Think\Page($count, C("DEFAULT_PAGESIZE")); // 实例化分页类 传入总记录数和每页显示的记录数
        $Page->setConfig('header', '共%TOTAL_ROW%条');
        $Page->setConfig('first', '首页');
        $Page->setConfig('last', '共%TOTAL_PAGE%页');
        $Page->setConfig('prev', '上一页');
        $Page->setConfig('next', '下一页');
        $Page->setConfig('link', 'indexpagenumb'); // pagenumb 会替换成页码
        $Page->setConfig('theme', '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $page = $Page->show();
        $this->assign("page", $page);
        $this->assign("programalist", $programalist);
        $this->assign("shoppingList", $shoppingList);
        $this->display('shoppinglist', 'utf-8');
    }

    public function AddShopping()
    {
        header("Content-Type:text/html; charset=utf-8");
        if (IS_POST) {
            $programa_id = isset($_POST['programa_id']) ? $_POST['programa_id'] : '';
            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $status = isset($_POST['status']) ? $_POST['status'] : 0;
            $url = isset($_POST['url']) ? $_POST['url'] : '';
            $uploader = new Uploader();
            $toy = new Toys();
            $showImg = "";
            $cartoon = $toy->queryCartoonByNameAndProgramaId($name, $programa_id);
            if ($cartoon) {
                $this->error("本栏目已经存在相同的商品名称，请更换名称！");
            } else {
                $showImg = "";
                $ret = $uploader->UploadShowImage();
                if (1 == $ret['status']) {
                    $this->error($ret['msg']);
                } else {
                    $showImg = $ret['msg'][0];
                }
                $toy->AddShopping($programa_id, $name, $url, $showImg, $status);

                $this->success("添加商品成功!", "Shoppinglist", 3);
            }
        } else {
            $programa = new Programa();
            $programalist = $programa->getAllValidColumn();
            $this->assign("programa", $programalist);
            $this->display('addShopping', 'utf-8');
        }
    }

    public function Version()
    {
        header("Content-Type:text/html; charset=utf-8");
        $err = '';
        $ver = new Version();
        $ret = '';
        if (IS_POST) {
            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $note = isset($_POST['note']) ? $_POST['note'] : '';
            $type = isset($_POST['type']) ? $_POST['type'] : 0;
            $force = isset($_POST['force']) ? $_POST['force'] : 0;
            $version = isset($_POST['version']) ? $_POST['version'] : '';
            $versioncode = isset($_POST['versioncode']) ? $_POST['versioncode'] : '';
            $ret = $ver->UploadFile($name, $note, $type, $force, $version, $versioncode);
            if ($ret['status'] == 1){
                $this->success($ret['msg'],'Version', 3);
            }else {
                $this->error($ret['msg'],'Version', 3);
            }

        }else{
        $vers = $ver->queryVersionInfo();
        $this->assign("vers", $vers);
        $this->display('updateVersion', 'utf-8');
        }
    }

    public function DelShopping()
    {
        header("Content-Type:text/html; charset=utf-8");
        $toy = new Toys();
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $toy->delShoppingById($id);
        $data['status'] = 1;
        $data['msg'] = '删除商品成功！';
        $this->ajaxReturn($data);
    }

    public function EditShopping(){
        header("Content-Type:text/html; charset=utf-8");
        $toy = new Toys();
        if (IS_POST){
            $id = isset($_POST['id']) ? $_POST['id'] : '';
            $programa_id = isset($_POST['programa_id']) ? $_POST['programa_id'] : '';
            $name = isset($_POST['name']) ? $_POST['name'] : '';
            $status = isset($_POST['status']) ? $_POST['status'] : 0;
            $url = isset($_POST['url']) ? $_POST['url'] : '';
            $uploader = new Uploader();
            $toy = new Toys();
            $showImg = "";
            $shopping  = $toy->queryShoppingById($id);
            $shoppings = $toy->queryCartoonByNameAndProgramaId($name, $programa_id);
            if ($shoppings && $shopping['name'] != $name) {
                $this->error("本栏目已经存在相同的商品名称，请更换名称！");
            } else {
                $showImg = null;
                if ($_FILES['uploader_files']['size']){
                    $ret = $uploader->UploadShowImage();
                    if (1 == $ret['status']) {
                        $this->error($ret['msg']);
                    } else {
                        $showImg = $ret['msg'][0];
                    }
                }
                $toy->updateShopping($id, $programa_id, $name, $url, $showImg, $status);

                $this->success("修改商品成功!", "Shoppinglist", 3);
            }
        }else{
            $id = isset($_GET['id']) ? $_GET['id'] : '';
            $shopping  = $toy->queryShoppingById($id);
            $programa = new Programa();
            $programalist = $programa->getAllValidColumn();
            $this->assign("programa", $programalist);
            $this->assign("shopping", $shopping);
            $this->display('editShopping', 'utf-8');
        }
    }

}