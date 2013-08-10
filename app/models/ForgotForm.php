<?php
namespace app\models;

use app\components\Queue;
use app\jobs\EmailJob;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

class ForgotForm extends User
{

    /**
     * @return validation rules array
     */
    public function rules() {
        return array(
            array('email', 'required'),
            array('email', 'email'),
            array('email', 'matchEmail')
        );
    }

    /**
     * checking email in database and adding error message if email don't exist
     */
    public function matchEmail() {
        $user = User::findByEmail($this->email);

        if (!$user) {
            $this->addError('email', 'With this email, no one is registered on the network.');
        }
    }

    /**
     * Sending message with invitation token
     * @return mixed
     */
    public function send() {
        if ($this->validate()) {
            // add new user with sent email
            $user = User::findByEmail($this->email);
            $user->password = User::hashPassword(time().'-'.$this->email);
            $user->save();

            // get an auto generated password
            $query = $this->findByEmail($this->email);

            // sending email
            /** @var Queue $queue */
            $queue = Yii::$app->getComponent('queue');
            $emailData = array(
                'to'      => $this->email,
                'subject' => 'Recover password',
                'body'    => 'For recover your password follow '.
                    Html::a(
                        'this link',
                        Yii::$app->getUrlManager()->createAbsoluteUrl('/auth/recover/'.$this->email.'/'.$query->password)),
                'text/html',
                'utf-8'
            );
            $queue->enqueue(EmailJob::getJobName(), $emailData);

            return 'Email with settings for recover your password is successfully sent';
        }

        return false;
    }
}