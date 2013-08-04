<?php
namespace app\components;

use app\jobs\JobInterface;
use app\jobs;
use yii\base\Component;
use yii\helpers\FileHelper;

class QueueWorker extends Component
{
    public $jobClassPath = null;

    public $jobNamespace = null;

    /**
     * @var \GearmanWorker
     */
    private $worker;

    /**
     * Initialize worker
     */
    public function init() {
        parent::init();
        $this->worker = new \GearmanWorker();
    }

    /**
     * Registration all job classes
     */
    public function register() {
        /** @var $file JobInterface */
        foreach (FileHelper::findFiles($this->jobClassPath) as $file) {
            $fileName = pathinfo($file, PATHINFO_FILENAME);
            $className = $this->jobNamespace . $fileName;
            $class = new $className;
            $this->worker->addFunction('email', array($class, 'process'));
        }
    }

    /**
     * Start worker loop
     */
    public function start() {
        while($this->worker->work());
    }
}