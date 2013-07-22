<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;

/**
 * Class AdminForm for administrative goals
 * @package app\models
 */
class AdminForm extends User
{

    /**
     * @return validation rules array
     */
    public function rules()
    {
        return array(
            array('email', 'required'),
            array('email', 'email'),
            array('email', 'matchEmail')
        );
    }

    /**
     * @return scenarios array
     */
    public function scenarios()
    {
        return array(
            'default' => array('email')
        );
    }

    /**
     * checking email in database and adding error message if email already exist
     */
    public function matchEmail()
    {
        $user = User::findByEmail($this->email);

        if ($user)
            $this->addError('email', 'User with this email already exist');
    }

    /**
     * Sending message with invite token
     * @return mixed
     */
    public function sendInvite()
    {
        if ($this->validate())
        {
            // add new user with sent email (in future with field active = 0)
            $this->save();

            // get an auto generated password
            $query = $this->findByEmail($this->email);

            // sending email
            $urlManager = Yii::$app->getComponent('urlManager');
            $mail = Yii::$app->getComponent('mail');
            $mail->addTo($this->email);
            $mail->setSubject('Test message');
            $mail->setBody('
                Congratulations! You are invited into the corporate network of "binary-studio".<br/>
                For confirming registration follow '.
                Html::a('this link', $urlManager->createAbsoluteUrl('/auth/registration/'.$this->email.'/'.$query->password)).'
            ', 'text/html', 'utf-8');
            $sent = $mail->send();

            return 'Email successfully sent';
        }

        return false;
    }
}