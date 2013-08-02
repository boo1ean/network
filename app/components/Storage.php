<?php
namespace app\components;

use yii;
use yii\base\Component;
use yii\web\UploadedFile;
use yii\helpers\FileHelperBase;

class Storage extends Component
{
    /**
     * @var string name of provider (LocalStorageProvider or CloudStorageProvider)
     */
    public $storageProvider;

    /**
     * @var array Parameters for storageProvider
     */
    public $params;

    /**
     * @var object provider
     */
    private $provider;

    public function init() {
        parent::init();

        $this->provider = new $this->storageProvider;
        $this->provider->setParams($this->params);
    }

    /**
     * Save path via storageProvider
     * @param $localPath Path to the save file
     * @return mixed string|bool
     */
    public function save($localPath) {
        return  $this->provider->save($localPath);
    }
}