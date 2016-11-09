<?php

namespace Project\App\HTTPProcessors\CP\Catalogue;

use Project\App\HTTPProcessors\Processor\CatalogueProtected;

class Heading extends CatalogueProtected
{

    public function defaultAction()
    {
        return $this->render('app:cp/catalogue/heading/default');
    }

}