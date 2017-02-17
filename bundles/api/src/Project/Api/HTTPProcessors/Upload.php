<?php

namespace Project\Api\HTTPProcessors;

use PHPixie\HTTP\Request;

class Upload extends AuthProcessor
{

    public function brandsPostAction(Request $request)
    {
        file_put_contents(
            '/home/rez1dent3/web/wbs/www/brands.json',
            json_encode($request->data()->get())
        );

        return ['success' => true];
    }

}