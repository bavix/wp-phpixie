<?php

namespace Project\Cp\HTTPProcessors\Settings;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SettingsProtected;
use Project\Model;

class API extends SettingsProtected
{

    /**
     * @param $appName
     *
     * @return mixed
     * @throws \PHPixie\ORM\Exception\Query
     */
    protected function createClient($appName)
    {
        /**
         * @var $builder \Project\Framework\Builder
         */
        $orm     = $this->components->orm();
        $builder = $this->builder->frameworkBuilder();

        $app = $orm->query(Model::APP)
            ->where('name', $appName)
            ->findOne();

        if (!$app)
        {
            // fixme : user & name
            $app = $orm->createEntity(Model::APP);

            $app->userId = $this->user->id();
            $app->name   = $appName;
            $app->active = 1;

            $app->save();
        }

        // remove old tokens
        $orm->query(Model::OAUTH_CLIENT)
            ->where('appId', $app->id())
            ->delete();

        // create new client id/secret
        $client                = $orm->createEntity(Model::OAUTH_CLIENT);
        $client->client_id     = $builder->dHelper()->str()->random(80);
        $client->client_secret = $builder->dHelper()->str()->random(80);
        $client->appId         = $app->id();

        $client->save();

        return $client;
    }

    public function defaultAction(Request $request)
    {
        $this->addItemButton('cp.settings.app@add');

        /**
         * @var $builder \Project\Framework\Builder
         */
        $orm     = $this->components->orm();
        $builder = $this->builder->frameworkBuilder();

        $myApps = $orm->query(Model::APP)
            ->where('userId', $this->user->id())
            ->find();

        $query = $request->query();
        $page  = $query->get('page');

        /**
         * @var $filter array
         */
        $filter = $query->get('filter', []);

        $orm = $this->components->orm();

        $appQuery = $orm->query(Model::APP)
            ->orderDescendingBy('createdAt');

        if (is_array($filter))
        {
            foreach ($filter as $name => $value)
            {
                if (is_array($value))
                {
                    $appQuery->orWhere($name, 'in', $value);
                }
                else
                {
                    $appQuery->orWhere($name, $value);
                }
            }
        }

        $pager = $builder->helper()->pager($page, $appQuery);

        $this->assign('apps', $pager);
        $this->assign('myApps', $myApps);

        return $this->render('cp:settings/api/default');
    }

    public function addAction()
    {

        return $this->render('cp:settings/api/default');
    }

}