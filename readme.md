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
            'endpoint' => 'YOUR_OSS_ENDPOINT.aliyuncs.com',
            'accessId' => 'XXXX',
            'accessSecret' => 'XXXX',
            'buckets' => [
                'tempFiles' => ['access' => 'private'],
                'imageFiles' => ['access' => 'public-read'],
            ]
        ],
        // ...
    ],
```

# Usage
See https://github.com/sup-ham/yii2-filestorage
