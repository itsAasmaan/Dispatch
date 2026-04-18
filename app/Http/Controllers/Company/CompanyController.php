<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    // GET /api/companies
    public function index(Request $request): JsonResponse
    {
        $companies = Company::active()
            ->when($request->industry, fn($q) => $q->byIndustry($request->industry))
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->verified, fn($q) => $q->verified())
            ->orderBy('interview_count', 'desc')
            ->paginate(15);

        return $this->success($companies);
    }

    // GET /api/companies/{company}
    public function show(Company $company): JsonResponse
    {
        $company->load('addedBy:id,name,username');

        $company->is_followed = auth()->check() ? $company->isFollowedBy(auth()->user()) : false;

        return $this->success($company);
    }

    // POST /api/companies
    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $company = Company::create([
            ...$request->validated(),
            'added_by' => auth()->id(),
        ]);

        return $this->created($company, 'Company created successfully');
    }

    // PUT /api/companies/{company}
    public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        $company->update($request->validated());

        return $this->success($company, 'Company updated successfully');
    }

    // DELETE /api/companies/{company}
    public function destroy(Company $company): JsonResponse
    {
        $company->delete();

        return $this->success(null, 'Company deleted successfully');
    }

    // POST /api/companies/{company}/follow
    public function follow(Company $company): JsonResponse
    {
        $user = auth()->user();

        if ($company->isFollowedBy($user)) {
            return $this->error('You are already following this company', 409);
        }

        $company->followers()->attach($user->id);
        $company->incrementFollowerCount();

        return $this->success(null, "You are now following {$company->name}");
    }

    // DELETE /api/companies/{company}/follow
    public function unfollow(Company $company): JsonResponse
    {
        $user = auth()->user();

        if (!$company->isFollowedBy($user)) {
            return $this->error('You are not following this company', 409);
        }

        $company->followers()->detach($user->id);
        $company->decrementFollowerCount();

        return $this->success(null, "You have unfollowed {$company->name}");
    }
}
