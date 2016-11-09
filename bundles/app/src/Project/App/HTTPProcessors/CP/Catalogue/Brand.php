<?php

namespace Project\App\HTTPProcessors\CP\Catalogue;

use Project\App\HTTPProcessors\Processor\CatalogueProtected;

class Brand extends CatalogueProtected
{

    public function defaultAction()
    {
        return $this->render('app:cp/catalogue/brand/default');
    }

}