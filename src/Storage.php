<?php
namespace Supham\FileStorage\AliyunOss;

use OSS\OssClient;
use Exception;
use yii2tech\filestorage\BaseStorage;

/**
 * Storage introduces the file storage based on the Aliyun Oss file system.
 *
 * Configuration example:
 *
 * ```php
 * 'fileStorage' => [
 *     'class' => 'Supham\FileStorage\AliyunOss\Storage',
 *     'bucketClassName' => 'Supham\FileStorage\AliyunOss\Bucket',
 *     'endpoint' => 'YOUR_OSS_ENDPOINT.aliyuncs.com',
 *     'accessId' => 'XXXX',
 *     'accessSecret' => 'XXXX',
 *     'token' => 'XXXX',
 *     'timeout' => 3600,
 *     'connectTimeout' => 10,
 *     'isCName' => false,
 *     'buckets' => [
 *         'tempFiles' => ['access' => 'private'],
 *         'imageFiles' => ['access' => 'public-read'],
 *     ]
 * ]
 * ```
 *
 * @see Bucket
 *
 * @property OssClient $ossClient
 * @method Bucket getBucket($bucketName)
 *
 * @author Supham <supalpuket@gmail.com>
 * @since 1.0
 */
class Storage extends BaseStorage
{
    const ACCESS_DEFAULT = 'default';
    const ACCESS_PUBLIC_READ = 'public-read';
    const ACCESS_PUBLIC_RW = 'public-read-write';
    const ACCESS_PRIVATE = 'private';

    /** @var OssClient */
    private $oss;
    public $bucketClassName = 'Supham\FileStorage\AliyunOss\Bucket';
    public $endpoint = 'oss-cn-hangzhou.aliyuncs.com';
    public $timeout = 3600;
    public $connectTimeout = 10;
    public $isCName;
    public $token;
    public $accessId;
    public $accessSecret;

    public function init()
    {
        try {
            $this->oss = new OssClient(
                $this->accessId,
                $this->accessSecret,
                $this->endpoint,
                $this->isCName,
                $this->token
            );
            $this->oss->setTimeout($this->timeout);
            $this->oss->setConnectTimeout($this->connectTimeout);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getOssClient()
    {
        return $this->oss;
    }

}
