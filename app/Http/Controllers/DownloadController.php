<?php

namespace App\Http\Controllers;

use App\Models\quotation_application;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function downloadAdvert(Request $request, $id)
    {
        $application = $request->user()->quotationApplications()->findOrFail($id);

        if (!$application->advert_path || !Storage::disk('local')->exists($application->advert_path)) {
            return back()->with('error', 'Advertisement file not found.');
        }

        $downloadFileName = basename($application->advert_path);

        return Storage::disk('local')->download($application->advert_path, $downloadFileName);
    }

    public function downloadSlip(Request $request, $id)
    {
        $application = $request->user()->quotationApplications()->findOrFail($id);

        if (!$application->slip_path || !Storage::disk('local')->exists($application->slip_path)) {
            return back()->with('error', 'Slip file not found.');
        }

        $downloadFileName = basename($application->slip_path);

        return Storage::disk('local')->download($application->slip_path, $downloadFileName);
    }
}
