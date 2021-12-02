<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Dashboard\ViewProps;
use CTDesarrollo\Regulus\Models\Report;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Class ReportRequests
 * @package App\Http\Controllers
 */
class ReportRequests extends Controller
{
    use ViewProps;

    /**
     * @return Response
     */
    public function index($id)
    {
        $report = Report::query()->findOrFail($id);

        $data = static::getProps()->merge([
            'report' => $report,
        ])->toArray();

        return Inertia::render('ReportRequests', $data);
    }
}
