<?php
namespace app\components\storageProviders;

interface StorageProviderInterface
{
    /**
     * Saves file according to parameters
     * If file saved successfully returns path to file, else returns false
     * @param $filename UploadedFile
     * @return mixed string|bool
     */
    public function save($filename);

    /**
     * Set parameters for storage provider
     * @param $params array of parameters
     */
    public function setParams($params);
}