
## oss laravel 调用方式

环境：php7 

nginx root配置到public

* 直接提交放在`home` `index`控制器中
* curl 提交到`api` `UploadController.php`中


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
    $res = uploadClass::curl_post($_FILES);
    print_r($res);
}

/**
 * 上传文件类
 */
class uploadClass
{

    //const url = "http://raohongjun.laravel.com/api/curlUpload";
    const url = "http://www.ossapiservice.com/api/curlUpload";

    /**
     * @param $files 文件对象
     * @param $path  自定义文件上传路径（文件夹）
     * @internal param string $postname 上传图片名称是否保留原名，默认重命名
     * @return bool|mixed
     */
    public static function curl_post(&$files, string $path = '', bool $filename = false)
    {

        if (!file_exists( $filesname = $files['upload_img']['tmp_name'])) {
            return '上传文件不存在！';
        }
        $filesname = $files['upload_img']['tmp_name'];
        $mimetype = $files['upload_img']['type'];
        $postname = $files['upload_img']['name'];

        // 创建一个 cURL 句柄
        $ch = curl_init(self::url);
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


}
```

##有问题反馈
在使用中有任何问题，欢迎反馈给我，可以用以下联系方式跟我交流
* 邮件(raohongjun1101@gmail.com)
* QQ: 112066440
