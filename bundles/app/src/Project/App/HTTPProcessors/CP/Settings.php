<?php

namespace Project\App\HTTPProcessors\CP;

use PHPixie\HTTP\Request;
use Project\App\HTTPProcessors\Processor\CPProtected;

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
                    $purgeCache = $this->builder->cache()->purge();
                    break;
            }
        }

        $this->variables['purgeCache'] = $purgeCache;

        return $this->render('app:cp/settings/default');
    }

}