<?php namespace KurtJensen\AuthNotice;

use Backend;
use System\Classes\PluginBase;

/**
 * AuthNotice Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Author Notice',
            'description' => 'Get and Send Notices to Plugin Users',
            'author' => 'KurtJensen',
            'icon' => 'icon-comment',
            'message_url' => 'http://tosh/~kurt/october/authserve/',
        ];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'KurtJensen\AuthNotice\Components\Service' => 'Service',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {

        return [
            'kurtjensen.authnotice.create' => [
                'tab' => 'Author Notice',
                'label' => 'Create Notices',
            ],
            'kurtjensen.authnotice.read' => [
                'tab' => 'Author Notice',
                'label' => 'Read Notices',
            ],
            'kurtjensen.authnotice.settings' => [
                'tab' => 'Author Notice',
                'label' => 'Settings',
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'authnotice' => [
                'label' => 'Author Notices',
                'url' => Backend::url('kurtjensen/authnotice/messages'),
                'icon' => 'icon-comment',
                'permissions' => ['kurtjensen.authnotice.*'],
                'order' => 500,

                'sideMenu' => [
                    'read' => [
                        'label' => 'Read',
                        'icon' => 'icon-comment',
                        'attributes' => ['title' => 'Read notices for plugins.'],
                        'url' => Backend::url('kurtjensen/authnotice/read'),
                        'permissions' => ['kurtjensen.authnotice.*'],
                    ],
                    'create' => [
                        'label' => 'Create',
                        'icon' => 'icon-pencil-square-o',
                        'attributes' => ['title' => 'Create notices for a plugin.'],
                        'url' => Backend::url('kurtjensen/authnotice/messages'),
                        'permissions' => ['kurtjensen.authnotice.create'],
                    ],
                    'settings' => [
                        'label' => 'AuthNotice Settings Page',
                        'icon' => 'icon-gear',
                        'code' => 'settings',
                        'owner' => 'KurtJensen.AuthNotice',
                        'attributes' => ['title' => 'Configure your AuthNotice settings.'],
                        'url' => Backend::url('system/settings/update/kurtjensen/authnotice/settings'),
                        'permissions' => ['kurtjensen.authnotice.settings'],
                    ],
                ],

            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label' => 'AuthNotice',
                'description' => 'Configure AuthNotice preferences.',
                'icon' => 'icon-comment',
                'class' => 'KurtJensen\AuthNotice\Models\Settings',
                'order' => 100,
                'keywords' => 'author notice server',
                'permissions' => ['kurtjensen.authnotice.settings'],
            ],
        ];
    }
}
