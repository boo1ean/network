<?php
namespace app\components;


use yii\base\Component;

/**
 * Class Mailer
 * @package app\components
 *
 * Using:
 * $mail = Yii::$app->getComponent('mail');
 * //$mail->setTo('email1@example.com');
 * $mail->addTo('email1@example.com');
 * $mail->addTo('email2@example.com');
 * $mail->setFrom('sender@example.com');
 * $mail->setSubject('Test subject');
 * $mail->setBody('Test message');
 * $sent = $mail->send();
 *
 * @author Antipenko Ilya <aivus@aivus.name>
 */
class Mailer extends Component {

    /**
     * @var \Swift_Transport  Instance of Swift_Transport
     */
    private $transport;

    /**
     * @var \Swift_Message Instance of Swift_Message
     */
    private $message;

    /**
     * @var \Swift_Mailer Instance of Swift_Mailer
     */
    private $mailer;

    /**
     * @var string Sender email address
     */
    public $senderEmail;

    /**
     * @var string Type of transport. (smtp, mail)
     */
    public $transportType;

    /**
     * @var string SMTP Host (Use only with $transportType='smtp')
     */
    public $smtpHost;

    /**
     * @var int SMTP Port. Default is 25. (Use only with $transportType='smtp')
     */
    public $smtpPort = 25;

    /**
     * @var string SMTP Login (Use only with $transportType='smtp')
     */
    public $smtpLogin;

    /**
     * @var string SMTP Password (Use only with $transportType='smtp')
     */
    public $smtpPassword;

    /**
     * @var string SMTP server encryption  (Use only with $transportType='smtp')
     */
    public $smtpEncryption = null;

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init() {
        parent::init();

        $this->transport = $this->_getTransport();
        $this->message = \Swift_Message::newInstance();
        $this->mailer = \Swift_Mailer::newInstance($this->transport);
    }

    /**
     * @param string $subject Mail subject
     */
    public function setSubject($subject) {
        $this->message->setSubject($subject);
    }

    /**
     * @param string $body Body of message
     * @param string $contentType Message content type
     * @param string $charset Message charset
     */
    public function setBody($body, $contentType = null, $charset = null) {
        $this->message->setBody($body, $contentType, $charset);
    }

    /**
     * @param string $address Email of addressee
     * @param string $name Name of addressee
     */
    public function addTo($address, $name = null) {
        $this->message->addTo($address, $name);
    }

    /**
     * @param string $address Email of addressee
     * @param string $name Name of addressee
     */
    public function setTo($address, $name = null) {
        $this->message->setTo($address, $name);
    }

    /**
     * @param string $address Email of sender
     * @param string $name Name of sender
     */
    public function setFrom($address, $name = null) {
        $this->message->setFrom($address, $name);
    }

    /**
     * Send message
     * @param null $failedRecipients An array of failures by-reference
     * @return int The number of recipients who were accepted for delivery
     */
    public function send(&$failedRecipients = null)
    {
        $from = $this->message->getFrom();
        if (empty($from)) {
            $this->message->setFrom($this->senderEmail);
        }

        return $this->mailer->send($this->message, $failedRecipients);
    }

    /**
     * Return Swift_Transport according to $this->transportType
     * @return \Swift_MailTransport|\Swift_Transport_EsmtpTransport
     */
    private function _getTransport() {
        switch($this->transportType) {
            case 'smtp':
                return \Swift_SmtpTransport::newInstance($this->smtpHost, $this->smtpPort)
                    ->setUsername($this->smtpLogin)
                    ->setPassword($this->smtpPassword)
                    ->setEncryption($this->smtpEncryption);

            case 'mail':
                return \Swift_MailTransport::newInstance();

        }
    }


}