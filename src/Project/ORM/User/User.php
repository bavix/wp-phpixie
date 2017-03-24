<?php

namespace Project\ORM\User;

use Carbon\Carbon;
use PHPixie\AuthORM\Repositories\Type\Login\User as UserLogin;
use PHPixie\HTTP\Request;
use PHPixie\Template;
use Project\App\Builder;
use Project\Extension\Mail;
use Project\Model;
use Project\ORM\EntityImage;
use Project\ORM\Role\Role;

/**
 * User entity with support for Login auth
 */
class User extends UserLogin
{

    use EntityImage;

    protected $imageType = 'avatar';

    /**
     * @var $builder Builder
     */
    protected $builder;

    public function __construct($entity, $builder)
    {
        parent::__construct($entity);
        $this->builder = $builder;
    }

    /**
     * @return string
     */
    public function passwordHash()
    {
        return $this->passwordHash;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function hasPermission($name)
    {
        /**
         * @var $role Role
         */
        $role = $this->role();

        return $role->hasPermission($name);
    }

    public function recoveryPassword(Template $template)
    {
        $orm = $this->builder->components()->orm();

        $recoveryPasswords = $orm->query(Model::RECOVERY_PASSWORD)
            ->where('userId', $this->id())
            ->where('active', 1)
            ->find();

        foreach ($recoveryPasswords as $recoveryPassword)
        {
            $recoveryPassword->active = 0;
            $recoveryPassword->save();
        }

        $recoveryPassword = $orm->createEntity(Model::RECOVERY_PASSWORD);

        $recoveryPassword->userId = $this->id();

        $carbon = Carbon::create();
        $carbon->addMinute(30); // add 30 min

        $recoveryPassword->expires = $carbon->timestamp;
        $recoveryPassword->code    = random_int(1000, 9999);

        $recoveryPassword->save();

        /**
         * @var \Project\Framework\Builder $builder
         */
        $builder = $this->builder->frameworkBuilder();

        $logoPath = __DIR_WEB__ . 'svg/wheel-white.png';
        $logoData = file_get_contents($logoPath);
        $logoData = 'data:image/png;base64,' . base64_encode($logoData);

        $body = $template->render('app:email/recovery-password', array(
            'assetsPath' => __DIR_WEB__ . 'assets/',
            'cssPath'    => __DIR_WEB__ . 'css/',
            'jsPath'     => __DIR_WEB__ . 'js/',
            'logo'       => $logoData,
            'code'       => $recoveryPassword->code
        ));

        return $builder->helper()->mail(Mail::TYPE_NO_REPLY)
            ->setFrom('Recovery Password Bot')
            ->setTo($this->email)
            ->setSubject('Recovery Password')
            ->setBody($body)
            ->send();
    }

    /**
     * @return string
     */
    public function imageThumbs()
    {
        return $this->_getImage('thumbs', 210, '500x500');
    }

}