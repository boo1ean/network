<?php
namespace app\jobs;


interface JobInterface
{

    /**
     * Process job
     * @param \GearmanJob $job
     * @return void
     */
    public function process(\GearmanJob $job);

    /**
     * Return processing job name
     * @return string processing job name
     */
    public static function getJobName();

}