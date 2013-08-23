<?php
namespace app\components\storageProviders;

interface StorageProviderInterface
{
    /**
     * Saves file according to parameters
     * @param $filename UploadedFile
     * @return array contains path and link for uploaded file
     */
    public function save($filename);

    /**
     * Set parameters for storage provider
     * @param $params array of parameters
     */
    public function setParams($params);

    /**
     * Delete file in specified path
     * @param $path
     * @return bool true on success, false - on fail
     */
    public function delete($path);

    /**
     * @param $path string path to image
     * @param string $size  size of image (xs, s, m, l, xl) for dropbox
     * @return mixed
     */
    public function image($path, $size = 'm');
}