<?php

namespace Project\Cp\HTTPProcessors\SOW;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOWProtected;
use Project\Model;

class Style extends SOWProtected
{

    /**
     * @return string
     */
    public function defaultAction()
    {
        $this->addItemButton('cp.sow.style@add');

        return $this->render('cp:sow/style/default');
    }

    /**
     * @return string
     */
    public function addAction()
    {
        return $this->render('cp:sow/style/add');
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function editAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        $this->variables['style'] = $this->components->orm()
            ->query(Model::STYLE)
            ->in($id)
            ->findOne();

        return $this->render('cp:sow/style/edit');
    }

}