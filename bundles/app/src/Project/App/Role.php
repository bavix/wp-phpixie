<?php

namespace Project\App;

class Role
{
    // admin rule (User, General Manager)
    const Admin = 1;

    // user rule (Register)
    const User = 2;

    // register rule null
    const Register = 3;

    // general Manager rule (ManagerCatalogue, ManagerUser, ManagerWheel)
    const ChiefManager = 4;

    // manager brand and dealer rule (ManagerDealer, ManagerBrand)
    const ManagerCatalogue = 5;

    // manager user rule null
    const ManagerUser = 9;

    // manager wheel rule wheel
    const ManagerWheel = 8;

    // manager brand rule brand
    const ManagerBrand = 6;

    // manager dealer rule dealer
    const ManagerDealer = 7;

    // manager heading rule heading
    const ManagerHeading = 10;
}