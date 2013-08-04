<?php
namespace app\components;

use app\jobs\JobInterface;
use app\jobs;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;

class QueueWorker extends Component
{
    /**
     * @var array Gearman Job Server address
     */
    public $servers;

    /**
     * @var null Path to job classes
     */
    public $jobClassPath = null;

    /**
     * @var null Job classes namespace
     */
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

        // Check gearman installation
        if (!function_exists('gearman_version')) {
            throw new InvalidConfigException("Could not found Gearman php extension.");
        }

        $this->worker = new \GearmanWorker();

        if (empty($this->servers)) {
            throw new InvalidConfigException("Could not found server IP in config");
        } else {
            foreach ($this->servers as $server=>$port) {
                $this->worker->addServer($server, $port);
            }
        }
    }

    /**
     * Registration all job classes
     */
    public function register() {
        foreach (FileHelper::findFiles($this->jobClassPath) as $file) {
            $fileName = pathinfo($file, PATHINFO_FILENAME);
            $className = $this->jobNamespace . $fileName;

            // If class doesn't exists, continue
            if (!class_exists($className))
                continue;

            /** @var $class JobInterface */
            $class = new $className;
            $this->worker->addFunction($class::getJobName(), array($class, 'process'));
        }
    }

    /**
     * Start worker loop
     */
    public function start() {
        while($this->worker->work()) {
            if (GEARMAN_SUCCESS != $this->worker->returnCode()) {
                echo '\nWorker failed: ' . $this->worker->error() . '\n';
            }

            ob_flush();
        }
    }
}