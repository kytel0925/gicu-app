<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Collection;

/**
 * Trait ViewProps
 * @package App\Http\Controllers\Dashboard
 */
trait ViewProps
{
    /**
     * @return Collection
     */
    public static function getProps()
    {
        return collect([
            //'reports' => Report::query()->get(),
            'reports' => collect(),
        ]);
    }
}
