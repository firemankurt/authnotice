<?php namespace KurtJensen\AuthNotice\Traits;

use KurtJensen\AuthNotice\Models\Message;
use KurtJensen\AuthNotice\Models\MessageMax;
use KurtJensen\AuthNotice\Models\Settings;

trait Purge
{
    public function Purge()
    {
        $retention = Settings::get('retention', 30);
        $retentionDate = date('Y-m-d H:i:s', strtotime('-' . $retention . ' day'));

        $protectRows = MessageMax::lists('row_id');

        Message::whereNotIn('id', $protectRows)
            ->where('sent_at', '<', $retentionDate)
            ->where('read', 1)->delete();
    }
}
