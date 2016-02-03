<?php namespace KurtJensen\AuthNotice\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Start Back-end Controller
 */
class Start extends Controller
{
    public $requiredPermissions = ['kurtjensen.authnotice.*'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('KurtJensen.AuthNotice', 'authnotice', 'start');
    }

    public function index()
    {
        //return "hello";
    }
}
