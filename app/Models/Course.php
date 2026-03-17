<?php

namespace App\Models;

use Database\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    /** @use HasFactory<CourseFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'category',
        'image',
        'price',
        'original_price',
        'duration',
        'students_count',
        'rating',
        'is_new',
        'status',
        'objectives',
        'requirements',
        'instructor_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_new' => 'boolean',
        'requirements' => 'array',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    // public function enrollments(): HasMany
    // {
    //     return $this->hasMany(Enrollment::class);
    // }

    // public function isEnrolledBy($user)
    // {
    //     return $this->enrollments()->where('user_id', $user->id)->exists();
    // }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    // public function scopeCategory($query, $category): void
    // {
    //     return $query->where('category', $category);
    // }
}
