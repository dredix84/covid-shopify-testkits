<?php

namespace App\Http\Controllers;

use App\Exports\FeedbackAggregateExport;
use App\Http\Requests\AdminOnly;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index(AdminOnly $request)
    {
        return Inertia::render(
            'ReportsIndex',
            [
                'init-data' => [
                    'options' => [

                    ]
                ]
            ]
        );
    }

    public function reportAggregateFeedback(Request $request)
    {
        $request->validate(['filters' => 'required']);

        return new FeedbackAggregateExport();
    }
}
