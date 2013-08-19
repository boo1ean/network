<?php
namespace app\events;

use yii\base\Event;

/**
 * Class HandlerEvent
 * @package app\events
 */
class HandlerEvent extends Event
{
    /**
     * @var mixed data for EventHandler
     */
    public $handlerData;

    /**
     * @param array $handlerData data for EventHandler
     * @param array $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct($handlerData, $config = array()) {
        $this->handlerData = $handlerData;
    }
}