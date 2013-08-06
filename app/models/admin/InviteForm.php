<?php
namespace app\models\admin;

use app\components\Queue;
use app\jobs\EmailJob;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

class InviteForm extends User
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
     * @return scenarios array
     */
    public function scenarios() {
        return array(
            'default' => array('email')
        );
    }

    /**
     * checking email in database and adding error message if email already exist
     */
    public function matchEmail() {
        $user = User::findByEmail($this->email);

        if ($user) {
            $this->addError('email', 'User with this email already exist');
        }
    }

    /**
     * Sending message with invitation token
     * @return mixed
     */
    public function sendInvite() {
        if ($this->validate()) {
            // add new user with sent email
            $this->password = User::hashPassword(time().'-'.$this->email);
            $this->save();

            // get an auto generated password
            $query = $this->findByEmail($this->email);

            // sending email
            /** @var Queue $queue */
            $queue = Yii::$app->getComponent('queue');
            $emailData = array(
                'to'        => $this->email,
                'subject'   =>  'Invite',
                'body'      => 'Congratulations! You are invited into the corporate network of "binary-studio".<br/>
                    For confirming registration follow '.
                    Html::a(
                        'this link',
                        Yii::$app->getUrlManager()->createAbsoluteUrl('/auth/registration/'.$this->email.'/'.$query->password)),
                    'text/html',
                    'utf-8'
            );
            $queue->enqueue(EmailJob::getJobName(), $emailData);

            return 'Email successfully sent';
        }

        return false;
    }

    /**
     * This is temporary function for simplify application testing
     * Creating and returning invitation token
     * @return mixed
     */
    public function sendInviteTest() {
        if ($this->validate()) {
            // add new user with sent email
            $this->password = User::hashPassword(time().'-'.$this->email);
            $this->save();

            // get an auto generated password
            $query = $this->findByEmail($this->email);

            return 'For easy registering new user following '.
                    Html::a(
                        'this link',
                        Yii::$app->getUrlManager()->createAbsoluteUrl('/auth/registration/'.$this->email.'/'.$query->password)
                    );
        }

        return false;
    }

}