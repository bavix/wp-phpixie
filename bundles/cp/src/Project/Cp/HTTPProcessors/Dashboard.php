<?php

namespace Project\Cp\HTTPProcessors;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\CPProtected;

/**
 * Admin dashboard
 */
class Dashboard extends CPProtected
{

    /**
     * Dashboard page
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function defaultAction(Request $request)
    {
        return $this->render('cp:dashboard/default');
    }

    public function countAction(Request $request)
    {
        $model = $request->query()->getRequired('model');

        return [
            'count' => $this->components->orm()->query($model)->count()
        ];
    }

}