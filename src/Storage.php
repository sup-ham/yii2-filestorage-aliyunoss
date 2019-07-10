<?php
namespace Supham\FileStorage\AliyunOss;

use OSS\OssClient;
use Exception;
use yii2tech\filestorage\BaseStorage;

/**
 * @property OssClient $ossClient
 */
class Storage extends BaseStorage
{
    const ACCESS_PUBLIC = 'public-read';
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
