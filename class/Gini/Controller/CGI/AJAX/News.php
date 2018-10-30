<?php

namespace Gini\Controller\CGI\AJAX;

class News extends \Gini\Controller\CGI
{
    public function actionAdd()
    {
        $me = _G('ME');
        $form = $this->form();
        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
        }
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $validator = new \Gini\CGI\Validator();

            try {

                $validator
                    ->validate('title', $form['title'], T('标题不能为空!'))
                    ->validate('type', $form['type'], T('类别不能为空!'))
                    ->done();

                $n = a('news');
                $n->title = H($form['title']);
                $n->content = $form['content'];
                $n->ctime = date('Y-m-d H:i:s');
                $n->publish = date('Y-m-d H:i:s');
                $n->type = (int)$form['type'];
                if ($n->type == \Gini\ORM\News::TYPE_PRODUCT) {
                    $n->product = a('product', $form['product_id']);
                }
                $n->save();

                return \Gini\IoC::construct('\Gini\CGI\Response\HTML', '<script data-ajax="true">window.location.reload();</script>');
            } catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', V('news/add-news-modal', [
            'form' => $form,
        ]));
    }

    public function actionEdit($id=0)
    {
        $me = _G('ME');
        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
        }
        $form = $this->form();
        $new = a('news', $id);
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $validator = new \Gini\CGI\Validator();

            try {

                $validator
                    ->validate('title', $form['title'], T('标题不能为空!'))
                    ->validate('type', $form['type'], T('类别不能为空!'))
                    ->done();

                $new->title = H($form['title']);
                $new->content = $form['content'];
                $new->publish = date('Y-m-d H:i:s');
                $new->type = (int)$form['type'];
                if ($new->type == \Gini\ORM\News::TYPE_PRODUCT) {
                    $new->product = a('product', $form['product_id']);
                }
                else {
                    $new->product = a('product');
                }
                $new->save();

                return \Gini\IoC::construct('\Gini\CGI\Response\HTML', '<script data-ajax="true">window.location.reload();</script>');
            } catch (\Gini\CGI\Validator\Exception $e) {
                $form['_errors'] = $validator->errors();
            }
        }

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', V('news/edit-news-modal', [
            'form' => $form,
            'new' => $new
        ]));
    }

    public function actionDelete($id = 0)
    {
        $me = _G('ME');
        $new = a('news', $id);
        if (!$new->id) {
            $this->redirect('error/404');
        }
        if (!$me->isAllowedTo('管理')) {
            $this->redirect('error/401');
        }

        //remove this news
        $new->delete();

        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', '<script data-ajax="true">window.location.reload();</script>');
    }

    public function actionEditorUpload($id=0)
    {
        //文件保存目录路径
        $save_path = $save_url = APP_PATH.'/'.DATA_DIR.'/upload/';
        \Gini\File::ensureDir($save_path);
        //文件保存目录URL

        //定义允许上传的文件扩展名
        $ext_arr = array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb', 'mp4'),
            'file' => array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
        );
        //最大文件大小
        $max_size = 1024 * 1024 * 20;

        $save_path = realpath($save_path) . '/';

        //PHP上传失败
        if (!empty($_FILES['imgFile']['error'])) {
            switch($_FILES['imgFile']['error']){
                case '1':
                    $error = '超过php.ini允许的大小。';
                    break;
                case '2':
                    $error = '超过表单允许的大小。';
                    break;
                case '3':
                    $error = '图片只有部分被上传。';
                    break;
                case '4':
                    $error = '请选择图片。';
                    break;
                case '6':
                    $error = '找不到临时目录。';
                    break;
                case '7':
                    $error = '写文件到硬盘出错。';
                    break;
                case '8':
                    $error = 'File upload stopped by extension。';
                    break;
                case '999':
                default:
                    $error = '未知错误。';
            }
            $this->alert($error);
        }
        //有上传文件时
        if (empty($_FILES) === false) {
            //原文件名
            $file_name = $_FILES['imgFile']['name'];
            //服务器上临时文件名
            $tmp_name = $_FILES['imgFile']['tmp_name'];
            //文件大小
            $file_size = $_FILES['imgFile']['size'];
            //检查文件名
            if (!$file_name) {
                $this->alert("请选择文件。");
            }
            //检查目录
            if (@is_dir($save_path) === false) {
                $this->alert("上传目录不存在。");
            }
            //检查目录写权限
            if (@is_writable($save_path) === false) {
                $this->alert("上传目录没有写权限。");
            }
            //检查是否已上传
            if (@is_uploaded_file($tmp_name) === false) {
                $this->alert("上传失败。");
            }
            //检查文件大小
            if ($file_size > $max_size) {
                $this->alert("上传文件大小超过限制。");
            }
            //检查目录名
            $dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
            if (empty($ext_arr[$dir_name])) {
                $this->alert("目录名不正确。");
            }
            //获得文件扩展名
            $temp_arr = explode(".", $file_name);
            $file_ext = array_pop($temp_arr);
            $file_ext = trim($file_ext);
            $file_ext = strtolower($file_ext);
            //检查扩展名
            if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
                $this->alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
            }
            //创建文件夹
            if ($dir_name !== '') {
                $save_path .= $dir_name . "/";
                $save_url .= $dir_name . "/";
                if (!file_exists($save_path)) {
                    \Gini\File::ensureDir($save_path);
                }
            }

            //新文件名

            //新文件名
            $new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
            //移动文件
            $file_path = $save_path . $new_file_name;
            if (move_uploaded_file($tmp_name, $file_path) === false) {
                $this->alert("上传文件失败。");
            }
            // @chmod($file_path, 0644);

            $file_url = URL("/data/upload/{$dir_name}/{$new_file_name}");

            $json = new \Gini\Model\Json();
            echo $json->encode(['error' => 0, 'url' => $file_url]);
            exit;
        }
    }

    private function alert($msg)
    {
        header('Content-type: text/html; charset=UTF-8');
        $json = new \Gini\Model\Json();
        echo $json->encode(array('error' => 1, 'message' => $msg));
        exit;
    }
}
