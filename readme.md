
## oss laravel 调用方式

环境：php7 
nginx root配置到public

项目配置完成完须要用 composer update下 导入阿里云oss sdk

* 直接提交放在`home` `index`控制器中


* 下面是curl 提交方式，将提交到服务层`api` `UploadController.php`中
```html
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>测试上传</title>
</head>
<body>
 <form method="post" enctype="multipart/form-data" action="uploadClass.php">
    <input type="file" name="upload_img" accept="image/*"/>
    <input type="submit" value="上传">
 </form>
</body>
</html>
```
uploadClass.php

```php
<?php

if (!empty($_FILES) && isset($_FILES['upload_img']['tmp_name'])) {
    /**
     *
     */
    $uploadClass = new uploadClass();
    $res = $uploadClass->curl_post($_FILES);
    print_r($res);
}

/**
 * 上传文件类
 */
class uploadClass
{


    /**
     * @var int 最大上传的文件大小，单位是M
     */
    private $max_upload_size = 2;


    //上传类型
    private $allow_type = array(
        'image/jpeg',
    );

    // 服务器地址
    private $server_uri = 'http://www.ossapiservice.com/api/curlUpload';
    /**
     * @var string 错误信息
     */
    private $errMsg = '';

    /**
     * @var int 错误代码
     */
    private $errCode = 0;

    /**
     * @param $files 文件对象
     * @param $path  自定义文件上传路径（文件夹）
     *
     * @internal param string $postname 上传图片名称是否保留原名，默认重命名
     * @return bool|mixed
     */
    public function curl_post(&$files, string $path = '', bool $filename = false)
    {


        if (!file_exists($filesname = $files['upload_img']['tmp_name'])) {
            return '上传文件不存在！';
        }

        $mimetype = $files['upload_img']['type'];

        // 检查图片格式是否支持上传

        if (false == $this->is_allow($mimetype)) {
            return '不允许上传的类型';
        }
        $filesize = $files['upload_img']['size'];
        $this->max_upload_size = (int)$this->max_upload_size * 1024 * 1024; // 转变为字节

        if ($this->max_upload_size < $filesize) {
            return '文件超出限制！';
        }

        $postname = $files['upload_img']['name'];

        // 创建一个 cURL 句柄
        $ch = curl_init($this->server_uri);
        // 创建一个 CURLFile 对象
        $cfile = curl_file_create($filesname, $mimetype, $postname);

        // 设置 POST 数据
        if (!empty($filename) || !empty($path)) {
            $data = [
                'upload_img' => $cfile,
                'upload_path' => $path,
                'upload_originalname' => $filename
            ];
        } else {
            $data = array('upload_img' => $cfile);
        }
        //post提交
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //避免ssl url 提交失败问题
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        //curl获取页面内容, 不直接输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 执行句柄
        $info = curl_exec($ch);

        if (curl_errno($ch)) {
            var_dump(curl_errno($ch));
            return FALSE;
        }
        curl_close($ch); // 关闭CURL会话
        unset($files);
        return $info; // 返回数据
    }


    /**
     * 检查文件是否允许上传
     *
     * @param $buff
     *
     * @return bool
     */
    private function is_allow($buff)
    {
        if (!$buff) {
            return false;
        }
        if (false == in_array($buff, $this->allow_type)) {
            $this->setError(4, '文件类型不支持上传');
            return false;
        }
        return true;
    }

    /**
     * 设置错误代码和错误信息
     *
     * @param $code
     * @param $msg
     */
    private function setError($code, $msg)
    {
        $this->errCode = $code;
        $this->errMsg = $msg;
    }


}

```

##有问题反馈
在使用中有任何问题，欢迎反馈给我，可以用以下联系方式跟我交流
* 邮件(raohongjun1101@gmail.com)
* QQ: 112066440
