<?php

namespace app\components;


use yii\base\Component;
use yii\base\InvalidConfigException;

class Queue extends Component
{
    /**
     * @var \GearmanClient Gearman client instance
     */
    protected $gearmanClient;

    /**
     * @var array Array of servers. ($server=>$port)
     */
    public $servers;

    /**
     * @var bool Do all jobs as sync (true) or async (false)
     */
    public $sync;

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init() {

        if (!function_exists('gearman_version')) {
            throw new InvalidConfigException("Could not found Gearman php extension.");
        }

        $this->gearmanClient = new \GearmanClient();

        if (empty($this->servers)) {
            $this->gearmanClient->addServer();
        } else {
            foreach ($this->servers as $server=>$port) {
                $this->gearmanClient->addServer($server, $port);
            }
        }
    }

    /**
     * Add job to job server
     * @param string $task name of task e.g. "email", "store-image"
     * @param array $data task's specific data e.g. array('email' => 'hello@example.com');
     * @param bool $background start task in background?
     * @return bool status whether job was successfully pushed to the queue
     */
    public function enqueue($task, $data, $background = null) {

        // Code $data to string
        $data = serialize($data);
        if (($background === null && !$this->sync) || $background === true) {
            $this->gearmanClient->doBackground($task, $data);
        } else {
            $this->gearmanClient->do($task, $data);
        }

        return $this->gearmanClient->returnCode() == GEARMAN_SUCCESS;
    }
}
