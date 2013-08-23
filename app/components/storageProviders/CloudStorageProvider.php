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
     * @return array
     */
    public function save($uploadedFile) {

        $meta = $this->dbxClient->uploadFile($this->dropboxPath . uniqid() . '_' . $uploadedFile->getName(), Dropbox\WriteMode::add(), fopen($uploadedFile->getTempName(), "rb"), $uploadedFile->getSize());
        $resource = array();
        $resource['path'] = $meta['path'];
        $share = $this->dbxClient->createShareableLink($meta['path']);
        $resource['link'] = $share;

        return $resource;
    }

    public function delete($path) {
        // Try to delete file
        try {
            $result = $this->dbxClient->delete($path);
            return $result->is_deleted;
        } catch (Dropbox\Exception_BadResponse $e) {  // If file doesn't exist
            return false;
        }
    }

    public function image($path, $size = 'm') {
        try {
            $result = $this->dbxClient->getThumbnail($path, "png", $size);
            $src = '"data:image/png;base64, ' . base64_encode($result[1]) . '"';
            return $src;
        } catch (Dropbox\Exception_BadResponse $e) {  // If file doesn't exist
            return false;
        }
    }
}