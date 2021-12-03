<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Dashboard\ViewProps;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class Indicators
 * @package App\Http\Controllers
 */
class Indicators extends Controller
{
    use ViewProps;

    /**
     * @return Response
     */
    public function SGIC()
    {
        $data = static::getProps()->toArray();

        return Inertia::render('IndicatorsSGIC', $data);
    }

    public function satisfaction()
    {
        $data = static::getProps()->toArray();

        return Inertia::render('IndicatorsSatisfaction', $data);
    }
}
