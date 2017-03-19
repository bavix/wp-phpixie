<?php

namespace Project\Cp\HTTPProcessors\SOC;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOCProtected;
use Project\Model;

class Heading extends SOCProtected
{

    public function defaultAction(Request $request)
    {
        $this->addItemButton('cp.soc.heading@add');

        return $this->render('cp:soc/heading/default');
    }

    public function addAction(Request $request)
    {
        return $this->render('cp:soc/heading/add');
    }

    public function editAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        $heading = $this->components->orm()->query(Model::HEADING)
            ->in($id)
            ->findOne();

        $this->assign('id', $id);
        $this->assign('item', $heading);

        return $this->render('cp:soc/heading/edit');
    }

}