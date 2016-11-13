<?php

namespace Project\App\HTTPProcessors;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPixie\HTTP\Request;

class Webhook extends Processor
{

    const GL_PSWD         = 'ltiN\p[R7Yz*nj/e';
    const GL_ACCESS_TOKEN = '_D1d^+{NK#T.b9q-4*&IMHj:mJk"]Y[fCRA6l;89S0Us&cVQgWP?}!/E5wv7oXuZ';

    protected $logger;

    /**
     * @return Logger
     *
     * @throws \Exception                If a missing directory is not buildable
     * @throws \InvalidArgumentException If stream is not a resource or string
     */
    protected function logger()
    {
        if (!$this->logger)
        {
            $path = $this->builder->webRoot()->path(__CLASS__ . '.log');

            $handler = new StreamHandler($path, Logger::WARNING);

            $log = new Logger(__CLASS__);
            $log->pushHandler($handler);
        }

        return $this->logger;
    }

    protected function shellExec($command)
    {
        $output = shell_exec($command);
        $this->logger()->info($output);
    }

    public function gitlabAction(Request $request)
    {
        $data  = $request->data();
        $token = $request->server()->get('http_x_gitlab_token');

        if ($token !== static::GL_ACCESS_TOKEN)
        {
            $this->logger()->info($data->get());
            $this->logger()->err($token);
            throw new \Exception(); // 403
        }

        $this->logger()->info(__METHOD__);

        $this->logger()->info('POST', $data->get());

        $branch = basename($data->get('ref'));
        $this->logger()->info('branch', $branch);

        $httpHost = $request->server()->get('http_host');

        switch (true)
        {

            case ($httpHost === 'wheelpro.ru') && ($branch === 'master'):

                chdir('/home/wheelpro/web/www/');

                $this->shellExec("git checkout master");
                $this->shellExec("git pull origin master");

                $this->shellExec("composer install");

                $this->shellExec("redis-cli flushall");
                $this->shellExec("./console framework:migrate");

                break;

            case ($httpHost === 'dev.wheelpro.ru') && ($branch === 'dev'):

                chdir('/home/wheelpro/web/dev/');

                $this->shellExec("git checkout dev");

                $this->shellExec("git pull origin dev");

                $this->shellExec("rm -fr ../doc/*"); // remove docs
                $this->shellExec("rm -fr /tmp/_apigen/*"); // remove temp files

                $apiGen = "php ../apigen.phar generate --config apigen.yaml";

                $this->shellExec($apiGen);

                $this->shellExec("composer install");
                $this->shellExec("composer update");

                $this->shellExec("redis-cli flushall");
                $this->shellExec("./console framework:migrate");

                break;

        }

        return $data->get();
    }

}