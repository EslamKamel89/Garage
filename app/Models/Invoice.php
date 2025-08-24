<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int|null $client_id
 * @property int|null $user_id
 * @property InvoiceStatus $status
 * @property numeric $calculated_total
 * @property numeric $actual_total
 * @property numeric $actual_paid_amount
 * @property array<array-key, mixed>|null $labor_info
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Client|null $client
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InvoiceItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\InvoiceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereActualPaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereActualTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereCalculatedTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereLaborInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice withoutTrashed()
 * @mixin \Eloquent
 */
class Invoice extends Model {
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'user_id',
        'status',
        'calculated_total',
        'actual_total',
        'actual_paid_amount',
        'labor_info',
        'notes'
    ];

    protected $casts = [
        'status' => InvoiceStatus::class,
        'labor_info' => 'array',
        'calculated_total' => 'decimal:2',
        'actual_total' => 'decimal:2',
        'actual_paid_amount' => 'decimal:2',
    ];

    // Relationships
    public function client(): BelongsTo {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany {
        return $this->hasMany(InvoiceItem::class);
    }
}
