<?php

namespace Project\Cp\HTTPProcessors\Settings;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SettingsProtected;

class API extends SettingsProtected
{

    public function defaultAction(Request $request)
    {
        return $this->render('cp:settings/api/default');
    }

}