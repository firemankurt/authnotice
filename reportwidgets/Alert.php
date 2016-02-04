<?php
namespace KurtJensen\AuthNotice\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use KurtJensen\AuthNotice\Models\Message;

class Alert extends ReportWidgetBase
{
    public $messageCount = 0;
    public function render()
    {
        $this->messageCount = Message::WhereNotNull('source')->UnRead()->count();

        return $this->makePartial('widget');
    }

    public function defineProperties()
    {
        return [
            'title' => [
                'title' => 'backend::lang.dashboard.widget_title_label',
                'default' => 'Author Notices',
                'type' => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'backend::lang.dashboard.widget_title_error',
            ],
        ];
    }
}
