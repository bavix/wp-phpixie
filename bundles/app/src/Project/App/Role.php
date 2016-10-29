<?php

namespace Project\App;

class Role
{
    // admin rule (User, General Manager)
    const Admin    = 1;

    // user rule (Register)
    const User     = 2;

    // register rule null
    const Register = 3;

    // general Manager rule (ManagerBrandAndDealer, ManagerUser, ManagerWheel)
    const GeneralManager = 4;

    // manager brand and dealer rule (ManagerDealer, ManagerBrand)
    const ManagerBrandAndDealer = 5;

    // manager user rule null
    const ManagerUser           = 9;

    // manager wheel rule null
    const ManagerWheel          = 8;

    // manager brand rule null
    const ManagerBrand          = 6;

    // manager dealer rule null
    const ManagerDealer         = 7;
}