<?php

namespace Project;

class Model
{
    const USER       = 'user';
    const ROLE       = 'role';
    const PERMISSION = 'permission';

    const MENU = 'menu';

    const BRAND  = 'brand';
    const DEALER = 'dealer';

    const BOLT_PATTERN = 'boltPattern';

    const HEADING = 'heading';

    const SOCIAL = 'social';

    const IMAGE = 'image';

    const INVITE = 'invite';

    const LOG = 'log';

    // many to many
    const BRAND_SOCIAL  = 'brandSocial';
    const BRAND_DEALER  = 'brandDealer';
    const BRAND_HEADING = 'brandHeading';
}