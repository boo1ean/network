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
     * @return mixed integer|bool id if uploaded resource or false if failed
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

    /**
     * @param $id integer id of resource to delete;
     * @return bool true if success, false - if fail
     */
    public function delete($id) {
        // Find resource by id
        $resource = Resource::find($id);
        // If resource was found
        if($resource != null) {
            // Get path
            $path = $resource->path;
            // Try to delete
            if ($this->provider->delete($path)) {
                // Delete record from db
                $resource->delete();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function image($id) {
        $res = Resource::find($id);
        return $this->provider->image($res->path);
    }
}