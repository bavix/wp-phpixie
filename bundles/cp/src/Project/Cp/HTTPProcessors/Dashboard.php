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

    /**
     * @param Request $request
     *
     * @return array
     */
    public function countAction(Request $request)
    {
        $model = $request->query()->getRequired('model');
        $query = $this->components->orm()->query($model);

        return [
            'count'  => $query->count(),
            'active' => $query->where('active', 1)->count(),
        ];
    }

}