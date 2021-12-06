<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Dashboard\ViewProps;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class Degrees
 * @package App\Http\Controllers
 */
class Degrees extends Controller
{
    use ViewProps;

    /**
     * @return Response
     */
    public function index()
    {
        $data = static::getProps()->toArray();

        return Inertia::render('Degrees', $data);
    }
}
