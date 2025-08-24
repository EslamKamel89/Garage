<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name_ar
 * @property string|null $name_en
 * @property numeric $base_fee
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\LabourFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Labour newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Labour newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Labour onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Labour query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Labour whereBaseFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Labour whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Labour whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Labour whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Labour whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Labour whereNameAr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Labour whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Labour whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Labour withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Labour withoutTrashed()
 * @mixin \Eloquent
 */
class Labour extends Model {
    /** @use HasFactory<\Database\Factories\LabourFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name_ar', 'name_en', 'base_fee', 'is_active'];

    protected $casts = [
        'base_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
