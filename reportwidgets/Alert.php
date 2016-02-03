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
        $this->chart['failed'] = $stats->sum('perm_fail_total');

        $grandTotal = $this->chart['delivered'] + $this->chart['failed'];
        $factor = $grandTotal / 100;

        $this->chart['deliveredPercent'] = $grandTotal ? round($this->chart['delivered'] / $factor, 2) : '--';

        $this->chart['failedPercent'] = $grandTotal ? round($this->chart['failed'] / $factor, 2) : '--';

        $this->chart['opened'] = $stats->sum('opened');
        $this->chart['openedPercent'] = $grandTotal ? round($this->chart['opened'] / $factor, 2) : '--';

        $this->chart['clicked'] = $stats->sum('clicked');
        $this->chart['clickedPercent'] = $grandTotal ? round($this->chart['clicked'] / $factor, 2) : '--';

        $this->chart['complained'] = $stats->sum('complained');
        $this->chart['complainedPercent'] = $grandTotal ? round($this->chart['complained'] / $factor, 2) : '--';
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
