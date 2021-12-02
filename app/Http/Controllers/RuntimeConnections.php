<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Dashboard\ViewProps;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class RuntimeConnections
 * @package App\Http\Controllers
 */
class RuntimeConnections extends Controller
{
    use ViewProps;

    /**
     * @return Response
     */
    public function index()
    {
        $data = static::getProps()->toArray();

        return Inertia::render('RuntimeConnections', $data);
    }
}
