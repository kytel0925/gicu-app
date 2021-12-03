<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Dashboard\ViewProps;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class Reports
 * @package App\Http\Controllers
 */
class Reports extends Controller
{
    use ViewProps;

    /**
     * @return Response
     */
    public function index()
    {
        $data = static::getProps()->toArray();

        return Inertia::render('Reports', $data);
    }
}
