<?php

namespace App\Consts;

/**
 * Navigation Constants
 * URL変更時は見直してください。
 */
class Navigation
{
    const ACTION_INDEX  = 'index';
    const HOME    = 'home';
    const ARTICLE = 'article';
    const ABOUT   = 'about';
    const LOGIN   = 'login';
    const INDEX   = 'home';

    /**
     * html 文字列 => Action
     */
    const LIST_GLOBAL = [
        self::HOME => self::ACTION_INDEX,
        self::ARTICLE => self::ACTION_INDEX,
        self::ABOUT => self::ACTION_INDEX,
    ];
    const LIST_GLOBAL_LOGIN = [
        self::LOGIN => self::ACTION_INDEX
    ];
}
