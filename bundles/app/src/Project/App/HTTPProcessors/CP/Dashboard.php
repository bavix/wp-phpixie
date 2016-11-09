<?php

namespace Project\App\HTTPProcessors\CP;

use PHPixie\HTTP\Request;
use Project\App\HTTPProcessors\Processor\CPProtected;

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
        return $this->render('app:cp/dashboard/default');
    }

}