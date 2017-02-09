<?php

namespace Project\Extension;

use Openbuildings\Swiftmailer\CssInlinerPlugin;
use Project\Framework\Builder;

class Mail
{

    const TYPE_INVITE   = 'invite';
    const TYPE_NO_REPLY = 'no-reply';

    protected $subject;
    protected $username;
    protected $from;
    protected $to;
    protected $html;
    protected $transport;

    /**
     * Mail constructor.
     *
     * @param Builder $builder
     * @param string  $type
     */
    public function __construct(Builder $builder, $type)
    {
        $this->slice = $builder->assets()
            ->configStorage()
            ->slice('mail.' . $type);
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setFrom($name)
    {
        $this->from = $name;

        return $this;
    }

    /**
     * @param string $subject
     *
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @param string $html
     *
     * @return $this
     */
    public function setBody($html)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * @param array|string $email
     *
     * @return $this
     */
    public function setTo($email)
    {
        $this->to = (array)$email;

        return $this;
    }

    /**
     * @return \Swift_SmtpTransport
     */
    protected function transport()
    {
        if (!$this->transport)
        {
            $smtp           = $this->slice->get('smtp');
            $this->username = $this->slice->get('username');
            $password       = $this->slice->get('password');

            $this->transport = \Swift_SmtpTransport::newInstance($smtp);
            $this->transport->setUsername($this->username);
            $this->transport->setPassword($password);
        }

        return $this->transport;
    }

    /**
     * @return int
     */
    public function send()
    {
        $transport = $this->transport();

        $mailMessage = \Swift_Message::newInstance()
            ->setFrom([$this->username => $this->from])
            ->setTo($this->to)
            ->setSubject($this->subject)
            ->setBody($this->html, 'text/html', 'utf-8');

        $mailer = \Swift_Mailer::newInstance($transport);

        $mailer->registerPlugin(new CssInlinerPlugin());

        return $mailer->send($mailMessage);
    }

}