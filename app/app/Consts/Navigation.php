<?php

namespace App\Consts;

/**
 * Navigation Constants
 */
class Navigation
{
    const ACTION_INDEX  = 'index';
    const ACTION_CREATE = 'create';
    const HOME          = 'home';
    const ARTICLE       = 'article';
    const ABOUT         = 'about';
    const LOGIN         = 'login';
    const REGISTER      = 'register';
    const INDEX         = 'home';
    const MEMBER        = 'dashboard';

    /**
     * Route => Name
     */
    const LIST_GLOBAL = [
        self::HOME . '.' . self::ACTION_INDEX => self::HOME,
        self::ARTICLE . '.' . self::ACTION_INDEX => self::ARTICLE,
        self::ABOUT . '.' . self::ACTION_INDEX => self::ABOUT,
    ];
    const LIST_GLOBAL_LOGIN = [
        self::LOGIN => self::LOGIN,
        self::REGISTER => self::REGISTER,
    ];
    const LIST_GLOBAL_LOGIN_AUTH = [
        self::MEMBER => 'MyPage',
    ];
}
