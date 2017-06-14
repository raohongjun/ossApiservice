<?php
/**
 * 图片上传oss类
 * User: raohongjun
 * Date: 2017/6/7
 * Time: 下午6:34
 */

namespace App\Services;

use OSS\Core\OssException;
use OSS\OssClient;

class OssClass
{
    private $ossClient;//oss对象
    private $accessKeyId;// "<您从OSS获得的AccessKeyId>"; ;
    private $accessKeySecret;//"<您从OSS获得的AccessKeySecret>";
    private $endpoint;//"<您选定的OSS数据中心访问域名，例如oss-cn-hangzhou.aliyuncs.com>";
    private $bucket; //oss存储空间名Bucket
    private $uploadDir = 'upload/';//默认保存图片文件夹
    private $fileInformation = []; //返回数组

    /**
     * OSS constructor.
     *
     * @param string|null $bucket oss存储空间名 默认读取配置文件
     * @param bool        $segment 网段选择 默认外网
     */
    public function __construct(?string $bucket = null, ?bool $segment = false)
    {
        $this->ossConfig($bucket, $segment);

        try {
            //oss对象
            $this->ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
            // 判断Bucket是否存在
            $this->ossClient->doesBucketExist($this->bucket);
        } catch (OssException $e) {
            return $e->getMessage();
        }
    }

    // 默认上传文件使用内网，免流量费

    /**
     * @param      $file 文件对象
     * @param null $imgname 自定义文件名，不传就随机文件名
     * @param null $dir 自定义文件保存路径（文件夹），不传就默认
     *
     * @return string
     */
    public function uploadContent(&$file, ?string $imgname = null, ?string $dir = null): array
    {
        if (!is_object($file)) {
            return false;
        }
        list($realPath, $size, $filename) = $this->fileAttribute($file, $imgname, $dir);


        try {
            /**
             * 把本地的文件上传到指定$bucket, 重命名为$object
             *string $bucket bucket名称
             *string $object object名称
             *string $file   本地文件路径
             */
            $result = $this->ossClient->uploadFile($this->bucket, $filename, $realPath);
            if (!empty($result['info']['url']) && $result['info']['http_code'] == 200) {
                $this->returnData($result, $filename, $size);
                return $this->fileInformation;
            }
        } catch (OssException $e) {
            return $e->getMessage();
        }
    }


    /**
     * 删除存储在oss中的文件
     *
     * @param string $ossKey 存储的key（文件路径和文件名）
     *
     * @return
     */
    public function deleteObject(string $filename): ?bool
    {
        // 判断object是否存在
        $doesExist = $this->ossClient->doesObjectExist($this->bucket, $filename);
        if ($doesExist == 1) {
            // 删除object
            $result = $this->ossClient->deleteObject($this->bucket, $filename);
            return true;
        }
        return $doesExist;
    }


    /**
     * 使用分片上传接口上传文件, 接口会根据文件大小决定是使用普通上传还是分片上传
     *
     * @param      $file 文件对象
     * @param null $imgname 自定义文件名，不传就随机文件名
     * @param null $dir 自定义文件保存路径（文件夹），不传就默认
     *
     * @return null
     * @throws OssException
     */
    public function multiuploadFile(&$file, ?string $imgname = null, ?string $dir = null): array
    {
        if (!is_object($file)) {
            return false;
        }

        list($realPath, $size, $filename) = $this->fileAttribute($file, $imgname, $dir);

        try {
            /**
             * @param string $bucket bucket名称
             * @param string $object object名称
             * @param string $file 需要上传的本地文件的路径
             * @param array  $options Key-Value数组
             */
            $result = $this->ossClient->multiuploadFile($this->bucket, $filename, $realPath, array());
            if (!empty($result['info']['url']) && $result['info']['http_code'] == 200) {
                $this->returnData($result, $filename, $size);
                return $this->fileInformation;
            }
        } catch (OssException $e) {
            return $e->getMessage();
        }


    }

    /**
     * @param $file
     * @param $imgname
     * @param $dir
     *
     * @return array
     */
    private function fileAttribute(&$file, $imgname, $dir): array
    {
        //临时文件的绝对路径
        $realPath = $file->getRealPath();
        // 文件原名
        $this->fileInformation['originalName'] = $file->getClientOriginalName();
        // 扩展名
        $ext = $file->getClientOriginalExtension();
        // image/jpeg
        $type = $file->getClientMimeType();
        //获取文件大小
        $size = $file->getClientSize();
        //时间文件夹
        $datedir = date('Ymd');
        //随机文件名
        $name = $imgname ? $imgname : date('YmdHis') . '_' . uniqid() . '.' . $ext;
        //加文件夹
        $filename = $dir ? $dir . '/' . $name : $this->uploadDir . $datedir . '/' . $name;
        return array($realPath, $size, $filename);
    }

    /**
     * @param $result
     * @param $filename
     * @param $size
     */
    private function returnData($result, $filename, $size): void
    {
        $this->fileInformation['url'] = $result['info']['url'];
        $this->fileInformation['filename'] = $filename;
        $this->fileInformation['size'] = $size;
        $this->fileInformation['http_code'] = 2000;
    }

    /**
     * @param $bucket
     * @param $segment
     */
    private function ossConfig($bucket, $segment): void
    {
        // "<您从OSS获得的AccessKeyId>";
        $this->accessKeyId = config('oss.AccessKeyId');

        //"<您从OSS获得的AccessKeySecret>";
        $this->accessKeySecret = config('oss.AccessKeySecret');

        //"<您选定的OSS数据中心访问域名，例如oss-cn-hangzhou.aliyuncs.com>";
        $this->endpoint = $segment || config('oss.useInternal') ? config('oss.ossServerInternal') : config('oss.ossServer');

        //bucket名称
        $this->bucket = $bucket ? $bucket : config('oss.OssBucket');
    }

}