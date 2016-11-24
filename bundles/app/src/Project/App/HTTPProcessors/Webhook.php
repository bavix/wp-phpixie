<?php

namespace Project\App\HTTPProcessors;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPixie\HTTP\Request;

class Webhook extends Processor
{

    const GL_PASSWORD     = 'ltiN\p[R7Yz*nj/e';
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
            $path = $this->builder->webRoot()->path('webhook.log');

            $handler = new StreamHandler($path);

            $this->logger = new Logger(__CLASS__);
            $this->logger->pushHandler($handler);
        }

        return $this->logger;
    }

    /**
     * @param $command string
     *
     * @throws \Exception
     */
    protected function shellExec($command)
    {
        $output = shell_exec($command);
        $this->logger()->addInfo($output);
    }

    /**
     * @param Request $request
     *
     * @return array|null|string
     * @throws \Exception
     */
    public function gitlabAction(Request $request)
    {
        $data  = $request->data();
        $token = $request->server()->get('http_x_gitlab_token');

        if ($token !== static::GL_ACCESS_TOKEN)
        {
            $this->logger()->addInfo($data->get());
            $this->logger()->addError($token);
            throw new \Exception(); // 403
        }

        $branch = basename($data->get('ref'));
        $this->logger()->addInfo($branch);

        $isDev = $branch === 'dev';

        if ($branch === 'master' || $isDev)
        {

            $dirName = $isDev ? $branch : 'www';

            chdir("/home/wheelpro/web/{$dirName}/");

            $this->shellExec("git checkout {$branch}");

            $this->shellExec("git pull origin {$branch}");

            $this->shellExec('composer install');
            $this->shellExec('composer update');

            chdir('web');

            $this->shellExec('yarn');
            $this->shellExec('yarn update');

            $this->shellExec('./console framework:migrate');

            $this->shellExec('redis-cli flushall');

        }

        if ($isDev)
        {
            $this->shellExec('rm -fr ../doc/*'); // remove docs
            $this->shellExec('rm -fr /tmp/_apigen/*'); // remove temp files

            $apiGen = 'php ../apigen.phar generate --config apigen.yaml';

            $this->shellExec($apiGen);
        }

        return $data->get();
    }

}