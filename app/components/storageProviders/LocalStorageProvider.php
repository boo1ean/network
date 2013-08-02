<?php
namespace app\components\storageProviders;

use yii\base\InvalidConfigException;
use yii\web\UploadedFile;

class LocalStorageProvider
{
    /**
     * @var array of parameters for saving file
     */
    private $params = array();

    /**
     * Set parameters
     * @param $params
     * @throws \yii\base\InvalidConfigException
     */
    public function setParams($params) {

        if (!isset($params['directory']))
            throw new InvalidConfigException('Missing directory param in config!');

        $this->params = $params;
    }

    /**
     * Saves file according to parameters
     * If file saved successfully returns path to file, else returns false
     * @param $filename UploadedFile
     * @return mixed string|bool
     */
    public function save($filename) {

        $newPath = $this->params['directory'] . uniqid() . '_' . $filename->getName();
        return ($filename->saveAs($newPath)) ? $newPath : false;
    }
}