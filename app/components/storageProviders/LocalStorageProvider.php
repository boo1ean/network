<?php
namespace app\components\storageProviders;

use yii\base\InvalidConfigException;
use yii\web\UploadedFile;

class LocalStorageProvider implements StorageProviderInterface
{
    /**
     * @var array of parameters for saving file
     */
    private $params = array();

    public function setParams($params) {

        if (!isset($params['directory']))
            throw new InvalidConfigException('Missing directory param in config!');

        $this->params = $params;
    }

    public function save($filename) {

        $newPath = $this->params['directory'] . uniqid() . '_' . $filename->getName();
        return ($filename->saveAs($newPath)) ? $newPath : false;
    }
}