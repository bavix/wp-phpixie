<?php

namespace Project\App\HTTPProcessors;

use PHPixie\HTTP\Request;

/**
 * User dashboard
 */
class Landing extends Processor
{

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function defaultAction(Request $request)
    {
        return $this->components->template()->get('app:layout');
    }

}