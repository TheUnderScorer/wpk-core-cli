<?php

use UnderScorer\Core\Cron\Cleaners\PostCleanerCron;
use UnderScorer\Core\Cron\Queue\QueueCron;

return [
    'cron'               => [
        QueueCron::class,
        PostCleanerCron::class,
    ],
    'recurrentSchedules' => [
    ],
];
