<?php

namespace app\components\storageProviders;
use \Dropbox;

class CloudStorageProvider implements StorageProviderInterface
{
    private $dbxApp;
    public function setParams($params) {
        $appInfo = Dropbox\AppInfo::loadFromJson($params['dbxAppInfo']);
        $this->dbxApp = new Dropbox\WebAuthNoRedirect($appInfo, 'network');
        list($accessToken, $userId) = $this->dbxApp->finish($params['dbxAppInfo']['auth_code']);
        $client = new Dropbox\Client($accessToken, 'network');
        $client->createFolder('TEST');
    }

    public function save($filename) {

    }
}