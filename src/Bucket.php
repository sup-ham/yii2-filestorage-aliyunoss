<?php
namespace Supham\FileStorage\AliyunOss;

use OSS\Core\OssException;
use yii\base\Exception;
use yii\log\Logger;
use yii2tech\filestorage\BucketSubDirTemplate;

/** 
 * Bucket introduces the file storage bucket based on the Aliyun Oss file system.
 * 
 * Configuration example:
 *
 * ```php
 * 'fileStorage' => [
 *     'class' => 'Supham\FileStorage\AliyunOss\Storage',
 *     'endpoint' => 'YOUR_OSS_ENDPOINT.aliyuncs.com',
 *     'accessId' => 'XXXX',
 *     'accessSecret' => 'XXXX',
 *     'buckets' => [
 *         'tempFiles' => ['access' => 'private'],
 *         'imageFiles' => ['access' => 'public-read'],
 *     ]
 * ]
 * ```
 *
 * @see Storage
 *
 * @property Storage $storage
 *
 * @author Supham <supalpuket@gmail.com>
 * @since 1.0
 */
class Bucket extends BucketSubDirTemplate
{
    public $access = Storage::ACCESS_DEFAULT;
    /**
     * {@inheritdoc}
     */
    public function create()
    {
        $this->resolveFullBasePath();
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy()
    {
        throw new \yii\base\NotSupportedException('Pull Requests are welcome.');
    }

    /**
     * {@inheritdoc}
     */
    public function exists()
    {
        throw new \yii\base\NotSupportedException('Pull Requests are welcome.');
    }

    /**
     * {@inheritdoc}
     */
    public function saveFileContent($fileName, $content)
    {
        $this->storage->ossClient->putObject($this->name, $fileName, $content);

        if ($result = $this->fileExists($fileName)) {
            $this->log("file '{$fileName}' has been saved");
            $this->setAccess($fileName, $this->access);
        } else {
            $this->log("Unable to save file '{$fileName}'!", Logger::LEVEL_ERROR);
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getFileContent($fileName)
    {
        throw new \yii\base\NotSupportedException('Pull Requests are welcome.');
    }

    /**
     * {@inheritdoc}
     */
    public function deleteFile($fileName)
    {
        throw new \yii\base\NotSupportedException('Pull Requests are welcome.');
    }

    /**
     * {@inheritdoc}
     */
    public function fileExists($fileName)
    {
        return $this->storage->ossClient->doesObjectExist($this->name, $fileName);
    }

    /**
     * {@inheritdoc}
     */
    public function copyFileIn($srcFileName, $fileName)
    {
        try {
            $this->storage->ossClient->uploadFile($this->name, $fileName, $srcFileName);
            $result = $this->fileExists($fileName);
        } catch(Exception $ex) {
        }

        if (isset($result)) {
            $this->setAccess($fileName, $this->access);
            $this->log("file '{$srcFileName}' has been copied to '{$fileName}'");
        } else {
            $this->log("unable to copy file from '{$srcFileName}' to '{$fileName}'!", Logger::LEVEL_ERROR);
            $this->log($ex->getMessage(), Logger::LEVEL_ERROR);
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function copyFileOut($fileName, $destFileName)
    {
        try {
            $contents = $this->storage->ossClient->cogetObject($this->name, $fileName);
            $result = file_put_contents($destFileName, $contents) > 0;
        } catch(Exception $ex) {
        }

        if (isset($result)) {
            $this->log("file '{$fileName}' has been copied to '{$destFileName}'");
        } else {
            $this->log("unable to copy file from '{$fileName}' to '{$destFileName}'!", Logger::LEVEL_ERROR);
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function copyFileInternal($srcFile, $destFile)
    {
        throw new \yii\base\NotSupportedException('Pull Requests are welcome.');
    }

    /**
     * {@inheritdoc}
     */
    public function moveFileIn($srcFileName, $fileName)
    {
        return $this->copyFileIn($srcFileName, $fileName) && unlink($srcFileName);
    }

    /**
     * {@inheritdoc}
     */
    public function moveFileOut($fileName, $destFileName)
    {
        if ($result = $this->copyFileOut($fileName, $destFileName)) {
            $this->storage->ossClient->deleteObject($this->name, $fileName);
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function moveFileInternal($srcFile, $destFile)
    {
        throw new \yii\base\NotSupportedException('Pull Requests are welcome.');
    }

    /**
     * {@inheritdoc}
     */
    protected function composeFileUrl($baseUrl, $fileName)
    {
        return sprintf('https://%s.%s/%s', $this->name, $this->storage->endpoint, $fileName);
    }

    /**
     * {@inheritdoc}
     */
    public function openFile($fileName, $mode, $context = null)
    {
        throw new \yii\base\NotSupportedException('Pull Requests are welcome.');
    }

    /**
     * Set access to the file.
     * @param string $fileName
     * @param string $access Storage::ACCESS_PRIVATE|Storage::ACCESS_PUBLIC
     * @return bool success status
     */
    public function setAccess($fileName, $access = Storage::ACCESS_PRIVATE)
    {
        try {
          $this->storage->ossClient->putObjectAcl($this->name, $fileName, $access);
          return true;
        } catch (OssException $e) {
          $this->log($e->getMessage(), Logger::LEVEL_ERROR);
          return false;
        }
    }
}
