<?php namespace KurtJensen\AuthNotice\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Flash;
use KurtJensen\AuthNotice\Models\Message;

/**
 * Messages Back-end Controller
 */
class Messages extends Controller
{
    public $requiredPermissions = ['kurtjensen.authnotice.create'];

    public $plugin = null;
    public $url = '';

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('KurtJensen.AuthNotice', 'authnotice', 'messages');
    }

    public function listExtendQuery($query)
    {
        return $query->where('source', '')->orWhereNull('source');
    }

    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $messageId) {
                if (!$message = Message::find($messageId)) {
                    continue;
                }

                $message->delete();
            }

            Flash::success('Successfully deleted those messages.');
        }

        return $this->listRefresh();
    }
}
