<?php

namespace Project;

class Model
{
    const APP          = 'app';
    const OAUTH_CLIENT = 'oauthClient';

    const USER              = 'user';
    const RECOVERY_PASSWORD = 'recoveryPassword';
    const ROLE              = 'role';
    const PERMISSION    = 'permission';

    const MENU = 'menu';

    const ADDRESS = 'address';

    const BRAND  = 'brand';
    const DEALER = 'dealer';

    const BOLT_PATTERN = 'boltPattern';
    const STYLE        = 'style';
    const WHEEL        = 'wheel';
    const COLLECTION   = 'collection';

    const HEADING = 'heading';
    const COMMENT = 'comment';

    const SOCIAL = 'social';

    const IMAGE = 'image';
    const VIDEO = 'video';

    const WHEEL_IMAGE   = 'wheelsImage';

    const INVITE = 'invite';

    const LOG = 'log';

    // many to many
    const BRAND_SOCIAL  = 'brandSocial';
    const BRAND_DEALER  = 'brandDealer';
    const BRAND_HEADING = 'brandHeading';

    // many to many
    const DEALER_SOCIAL  = 'dealerSocial';
    const DEALER_DEALER  = 'dealerDealer';
    const DEALER_HEADING = 'dealerHeading';

    const WHEEL_COMMENT = 'wheelComment';
}