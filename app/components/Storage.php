<?php
namespace app\components;

use yii;
use yii\base\Component;
use yii\web\UploadedFile;
use app\components\storageProviders\StorageProviderInterface;
use app\models\Resource;

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
        $file =  $this->provider->save($localPath);
        if ($file['path'] != null && $file['link'] != null) {
            $resource = new Resource();
            $resource->path = $file['path'];
            $resource->link = $file['link'];
            $resource->save();
            return $resource->id;
        } else {
            return false;
        }
    }

    public function delete($id) {
        $resource = Resource::find($id);
        if($resource != null) {
            $path = $resource->path;
            $resource->delete();
            return $this->provider->delete($path);
        } else {
            return false;
        }
    }
}