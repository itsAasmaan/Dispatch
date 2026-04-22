<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryInsight extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'role_title',
        'role_type',
        'base_salary',
        'bonus',
        'stock',
        'total_compensation',
        'currency',
        'location',
        'years_of_experience',
        'outcome',
        'offer_year',
        'is_anonymous',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'base_salary' => 'float',
        'bonus' => 'float',
        'stock' => 'float',
        'total_compensation' => 'float',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (SalaryInsight $insight) {
            $insight->total_compensation =
                $insight->base_salary + $insight->bonus + $insight->stock;
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeByCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role_title', 'like', "%{$role}%");
    }
}