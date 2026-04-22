<?php

namespace App\Http\Controllers\SalaryInsight;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalaryInsight\StoreSalaryInsightRequest;
use App\Models\SalaryInsight;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalaryInsightController extends Controller
{
    // GET /api/salary-insights
    public function index(Request $request): JsonResponse
    {
        $insights = SalaryInsight::with('company:id,name,slug,logo')
            ->when($request->company_id, fn($q) => $q->byCompany($request->company_id))
            ->when($request->role, fn($q) => $q->byRole($request->role))
            ->when($request->currency, fn($q) => $q->where('currency', $request->currency))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Hide user info for anonymous entries
        $insights->getCollection()->transform(function ($insight) {
            if ($insight->is_anonymous) {
                $insight->user_id = null;
            }
            return $insight;
        });

        return $this->success($insights);
    }

    // GET /api/salary-insights/stats
    public function stats(Request $request): JsonResponse
    {
        $query = SalaryInsight::when(
            $request->company_id,
            fn($q) => $q->byCompany($request->company_id)
        )->when(
                $request->role,
                fn($q) => $q->byRole($request->role)
            );

        $stats = [
            'count' => $query->count(),
            'avg_base_salary' => round($query->avg('base_salary'), 2),
            'avg_total_comp' => round($query->avg('total_compensation'), 2),
            'min_base_salary' => $query->min('base_salary'),
            'max_base_salary' => $query->max('base_salary'),
            'by_experience' => $query->groupBy('years_of_experience')
                ->selectRaw('years_of_experience, AVG(base_salary) as avg_salary, COUNT(*) as count')
                ->orderBy('years_of_experience')
                ->get(),
        ];

        return $this->success($stats);
    }

    // POST /api/salary-insights
    public function store(StoreSalaryInsightRequest $request): JsonResponse
    {
        $insight = SalaryInsight::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return $this->created($insight, 'Salary insight shared successfully');
    }
}