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
//        $managerCatalogue = $orm->query(Model::Role)
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
//        $managerHeading = $orm->query(Model::Role)
//            ->in(Role::ManagerHeading)
//            ->findOne();
//
//        $user = $orm->query(Model::Role)
//            ->in(Role::User)
//            ->findOne();
//
//        $reg = $orm->query(Model::Role)
//            ->in(Role::Register)
//            ->findOne();
//
//        $admin->children->add($user);
//        $admin->children->add($genManager);
//
//        $user->children->add($reg);
//
//        $genManager->children->add($managerWheel);
//        $genManager->children->add($managerUser);
//        $genManager->children->add($managerCatalogue);
//
//        $managerCatalogue->children->add($managerBrand);
//        $managerCatalogue->children->add($managerDealer);
//        $managerCatalogue->children->add($managerHeading);
//
//        die;
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