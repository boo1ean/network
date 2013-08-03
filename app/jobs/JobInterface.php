<?php
/**
 * Created by JetBrains PhpStorm.
 * User: admin
 * Date: 03.08.13
 * Time: 12:11
 * To change this template use File | Settings | File Templates.
 */

namespace app\jobs;


interface JobInterface {

    /**
     * Process job
     * @param \GearmanJob $job
     * @return mixed
     */
    public function process(\GearmanJob $job);

}