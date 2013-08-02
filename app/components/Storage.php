<?php
namespace app\components;

use yii;
use yii\base\Component;
use yii\web\UploadedFile;
use app\components\storageProviders\StorageProviderInterface;

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
     * @var StorageProviderInterface
     */
    private $provider;

    public function init() {
        parent::init();

        $this->provider = new $this->storageProvider;
        $this->provider->setParams($this->params);
    }

    // TODO: check MIME-type
    /**
     * Save path via storageProvider
     * @param $localPath UploadedFile for saving
     * @return mixed string|bool
     */
    public function save($localPath) {
        return  $this->provider->save($localPath);
    }
}