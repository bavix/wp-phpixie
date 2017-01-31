<?php

namespace Project\Cp\HTTPProcessors\SOC;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOCProtected;
use Project\Model;

class Dealer extends SOCProtected
{

    public function defaultAction(Request $request)
    {
        $this->addItemButton('cp.soc.dealer@add');

        return $this->render('cp:soc/dealer/default');
    }

    public function addAction(Request $request)
    {
        return $this->render('cp:soc/dealer/add');
    }

    public function editAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        $dealer = $this->components->orm()->query(Model::DEALER)
            ->in($id)
            ->findOne();

        $this->assign('dealer', $dealer);

        return $this->render('cp:soc/dealer/edit');
    }

}