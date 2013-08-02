<?php

namespace app\components\storageProviders;
use \Dropbox;
use yii\base\InvalidConfigException;
use yii\web\UploadedFile;

class CloudStorageProvider implements StorageProviderInterface
{
    /**
     * @var Dropbox\Client
     */
    private $dbxClient;

    /**
     * @var string
     */
    private $dropboxPath;

    public function setParams($params) {

        if (!isset($params['accessToken']))
            throw new InvalidConfigException('Missing "accessToken" param in config!');

        if (!isset($params['dropboxPath']))
            throw new InvalidConfigException('Missing "dropboxPath" param in config!');

        $this->dropboxPath = $params['dropboxPath'];
        $this->dbxClient = new Dropbox\Client($params['accessToken'], 'CloudStorageProvider/1.0');
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return mixed|void
     */
    public function save($uploadedFile) {

        $meta = $this->dbxClient->uploadFile($this->dropboxPath . uniqid() . '_' . $uploadedFile->getName(), Dropbox\WriteMode::add(), fopen($uploadedFile->getTempName(), "rb"), $uploadedFile->getSize());
        $share = $this->dbxClient->createShareableLink($meta['path']);
        return $share;
    }
}