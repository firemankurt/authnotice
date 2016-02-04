<?php
namespace KurtJensen\AuthNotice\ReportWidgets;

use Backend\Classes\ReportWidgetBase;

class Alert extends ReportWidgetBase
{
    public function render()
    {
        $this->chart['size'] = $this->property("size");
        $this->daysToShow = $this->property("days");

        $stats = StatsModel::where('date', '>', Carbon::now()->subDay($this->daysToShow))->get();

        $this->chart['delivered'] = $stats->sum('delivered_total');

        return $this->makePartial('widget');
    }

    public function defineProperties()
    {
        return [
            'title' => [
                'title' => 'backend::lang.dashboard.widget_title_label',
                'default' => 'kurtjensen.authornotice::lang.widgets.name',
                'type' => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'backend::lang.dashboard.widget_title_error',
            ],
            'size' => [
                'title' => 'kurtjensen.authornotice::lang.widgets.size',
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'kurtjensen.authornotice::lang.widgets.num_validate',
                'default' => '200',
                'description' => 'kurtjensen.authornotice::lang.widgets.size_desc',
            ],
        ];
    }
}
