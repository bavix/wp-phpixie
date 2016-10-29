<?php

namespace Project\App\HTTPProcessors;

use PHPixie\HTTP\Request;
use Project\App\Model;
use Project\App\Role;

/**
 * User dashboard
 */
class Landing extends Processor
{

//    public function initAction()
//    {
//
//        $orm = $this->components->orm();
//
//        $admin = $orm->query(Model::Role)
//            ->in(Role::Admin)
//            ->findOne();
//
//        $genManager = $orm->query(Model::Role)
//            ->in(Role::GeneralManager)
//            ->findOne();
//
//        $managerUser = $orm->query(Model::Role)
//            ->in(Role::ManagerUser)
//            ->findOne();
//
//        $managerBrandAndDealers = $orm->query(Model::Role)
//            ->in(Role::ManagerBrandAndDealer)
//            ->findOne();
//
//        $managerWheel = $orm->query(Model::Role)
//            ->in(Role::ManagerWheel)
//            ->findOne();
//
//        $managerBrand = $orm->query(Model::Role)
//            ->in(Role::ManagerBrand)
//            ->findOne();
//
//        $managerDealer = $orm->query(Model::Role)
//            ->in(Role::ManagerDealer)
//            ->findOne();
//
//        $user = $orm->query(Model::Role)
//            ->in(2)
//            ->findOne();
//
//        $reg = $orm->query(Model::Role)
//            ->in(3)
//            ->findOne();
//
//        $admin->children->add($user);
//        $admin->children->add($genManager);
//
//        $user->children->add($reg);
//
//        $genManager->children->add($managerWheel);
//        $genManager->children->add($managerUser);
//        $genManager->children->add($managerBrandAndDealers);
//
//        $managerBrandAndDealers->children->add($managerBrand);
//        $managerBrandAndDealers->children->add($managerDealer);
//    }

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