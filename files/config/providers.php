<?php

use UnderScorer\Core\Providers\CacheProvider;
use UnderScorer\Core\Providers\DatabaseProvider;
use UnderScorer\Core\Providers\EnqueueProvider;
use UnderScorer\Core\Providers\FileSystemProvider;
use UnderScorer\Core\Providers\LoggerProvider;
use UnderScorer\Core\Providers\NoticesProvider;
use UnderScorer\Core\Providers\SerializerProvider;
use UnderScorer\Core\Providers\ValidationProvider;
use UnderScorer\Core\Providers\ViewProvider;

return [
    CacheProvider::class,
    SerializerProvider::class,
    FileSystemProvider::class,
    EnqueueProvider::class,
    ViewProvider::class,
    NoticesProvider::class,
    LoggerProvider::class,
    DatabaseProvider::class,
    ValidationProvider::class,
];
