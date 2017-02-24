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

    const BRAND_LOGO  = 'brandLogo';
    const DEALER_LOGO = 'dealerLogo';

    const PREVIEW_WHEEL = 'previewWheel';
    const WHEEL_IMAGE   = 'wheelsImage';

    const INVITE = 'invite';

    const LOG = 'log';

    // many to many
    const BRAND_SOCIAL  = 'brandSocial';
    const BRAND_DEALER  = 'brandDealer';
    const BRAND_HEADING = 'brandHeading';


    const WHEEL_COMMENT = 'wheelComment';
}