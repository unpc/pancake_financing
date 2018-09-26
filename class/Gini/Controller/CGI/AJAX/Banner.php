<?php

namespace Gini\Controller\CGI\AJAX;

class Banner extends \Gini\Controller\CGI
{
    public function actionUploadAttachment()
    {
        $me = _G('ME');
        $form = $this->form('files');
        $file = $form['input'];

        if ($file) {
            $fileName = current($file['name']);
            $fullPath = \Gini\Model\Help::bannerPath($fileName);
            \Gini\File::ensureDir(\Gini\Model\Help::bannerPath());
            move_uploaded_file(current($file['tmp_name']), $fullPath);
            if (is_file($fullPath)) {
                return \Gini\IoC::construct('\Gini\CGI\Response\JSON', [
                    'ok' => H(T('文件上传成功!')),
                    'file' => $fullPath
                ]);
            }
        }
        return \Gini\IoC::construct('\Gini\CGI\Response\JSON', [
            'error' => H(T('文件上传失败!')),
            'file' => $fullPath
        ]);
    }

    public function actionDeleteAttachment()
    {
        $me = _G('ME');
        $form = $this->form();

        $path = $form['key'];
        if ($path) {
            $fullPath = \Gini\Model\Help::bannerPath($path);
            if (is_file($fullPath)) {
                \Gini\File::delete($fullPath);
                return \Gini\IoC::construct('\Gini\CGI\Response\JSON', [
                    'ok' => H(T('文件删除成功!')),
                    'file' => $fullPath
                ]);
            }
        }
        return \Gini\IoC::construct('\Gini\CGI\Response\JSON', [
            'error' => H(T('文件删除失败!')),
            'file' => $fullPath
        ]);
    }
}
