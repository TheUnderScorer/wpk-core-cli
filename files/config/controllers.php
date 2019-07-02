<?php

use UnderScorer\Core\Hooks\Controllers;

/**
 * This file store array of controllers to load during core bootstrap
 */

return [
    Controllers\Admin\DebugMenu::class,
    Controllers\Admin\CoreMenu::class,
    Controllers\Dev\ErrorHandler::class,
    Controllers\Dev\ExceptionHandler::class,
    Controllers\Http\GetCoreVersionHandler::class,
];
