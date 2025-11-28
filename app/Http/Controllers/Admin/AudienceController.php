<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AudienceMetric;
use Illuminate\View\View;

class AudienceController extends Controller
{
    public function index(): View
    {
        return view('admin.audience.index', [
            'metrics' => AudienceMetric::orderByDesc('captured_for')->paginate(15),
            'trend' => AudienceMetric::orderByDesc('captured_for')->take(7)->get()->reverse(),
        ]);
    }
}
<?php

