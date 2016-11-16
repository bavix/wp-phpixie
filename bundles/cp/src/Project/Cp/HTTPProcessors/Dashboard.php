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

}