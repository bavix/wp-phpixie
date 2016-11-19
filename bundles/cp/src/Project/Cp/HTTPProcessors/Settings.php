<?php

namespace Project\Cp\HTTPProcessors;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\CPProtected;
use Stash\Pool;

class Settings extends CPProtected
{

    public function defaultAction(Request $request)
    {
        $purgeCache = false;

        if ($request->method() === 'POST')
        {
            $data = $request->data();
            switch ($data->get('type'))
            {
                case 'cache': // reset
                    /**
                     * @var Pool
                     */
                    $pool = $this->builder->frameworkBuilder()->cache();

                    $purgeCache = $pool->purge() && $pool->clear();
                    break;
            }
        }

        $this->assign('purgeCache', $purgeCache);

        return $this->render('cp:settings/default');
    }

}