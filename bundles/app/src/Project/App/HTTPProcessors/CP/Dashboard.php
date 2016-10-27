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
        return $this->template->get('app:cp/dashboard', array(
            'admin' => $this->user
        ));
    }

}