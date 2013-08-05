<?php
namespace app\jobs;

use app\components\Mailer;
use yii\base\InvalidParamException;

class EmailJob implements JobInterface
{

    /**
     * @var string Job name
     */
    private static $jobName = 'email';

    /**
     * Return processing job name
     * @return string
     */
    public static function getJobName() {
        return static::$jobName;
    }

    /**
     * Process Email job
     * @param \GearmanJob $job Gearman job
     * @param null $context Context data that can be modified by the worker function
     * @return mixed|void
     * @throws \yii\base\InvalidParamException
     */
    public function process(\GearmanJob $job, $context = null) {
        $data = unserialize($job->workload());

        if(!$this->checkCorrectData($data))
            throw new InvalidParamException('Missed required parameter in EmailJob!');

        $this->sendEmail($data);
    }

    /**
     * Send email
     * @param $dataArray Email parameters
     * @param null $failedRecipients An array of failures by-reference
     * @return int Count of sent messages
     */
    protected function sendEmail($dataArray, &$failedRecipients = null) {
        /** @var Mailer $mail */
        $mail = \Yii::$app->getComponent('mail');

        // If "to" is array, add each of it.
        if (is_array($dataArray['to'])) {
            foreach ($dataArray['to'] as $to) {
                $mail->addTo($to);
            }
        } else {
            $mail->setTo($dataArray['to']);
        }

        $mail->setSubject($dataArray['subject']);
        $mail->setBody($dataArray['body']);
        return $mail->send($failedRecipients);
    }

    /**
     * Check EmailJob input data on correct
     * @param $dataArray
     * @return bool If $dataArray is valid, return true. If $dataArray is invalid, return false
     */
    protected function checkCorrectData($dataArray) {
        return isset($dataArray['to'], $dataArray['subject'], $dataArray['body']);
    }

}