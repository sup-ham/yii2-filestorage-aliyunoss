supham/yii2-filestorage-aliyunoss

AliYun OSS adapter for [yii2tech/file-storage](https://github.com/sup-ham/yii2-filestorage)

# Installation
```
composer config repositories.yii2oss vcs https://github.com/sup-ham/yii2-filestorage-aliyunoss.git
composer config repositories.fslite vcs https://github.com/sup-ham/yii2-filestorage.git
composer supham/yii2-filestorage-aliyunoss:dev-master
```

# Yii2 Configuration
```
    'components' => [
    'fileStorage' => [
        'class' => 'Supham\FileStorage\AliyunOss\Storage',
        'bucketClassName' => 'Supham\FileStorage\AliyunOss\Bucket',
        'endpoint' => 'YOUR_OSS_ENDPOINT.aliyuncs.com',
        'accessId' => 'XXXX',
        'accessSecret' => 'XXXX',
        'token' => 'XXXX',
        'timeout' => 3600,
        'connectTimeout' => 10,
        'isCName' => false,
        'buckets' => [
            'tempFiles' => ['access' => 'private'],
            'imageFiles' => ['access' => 'public-read'],
        ],
        // ...
    ],
```

# Usage
See https://github.com/sup-ham/yii2-filestorage
