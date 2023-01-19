<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Order\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *  @OA\Schema(
 *    schema="Order",
 *    type="object",
 *    required={"customer_id", "amount"},
 *    @OA\Property(
 *        property="customer_id",
 *        description="Customer ID of the Order",
 *        type="int",
 *        example="1"
 *    ),
 *    @OA\Property(
 *        property="amount",
 *        description="Amount of the Order",
 *        type="number",
 *        example="21.70"
 *    )
 *  )
 */
final class Order extends Model
{
    use SoftDeletes;

    // Class Constants
    const CANCELED = 0;

    const PENDING = 1;

    const CONFIRMED = 2;

    const COMPLETED = 3;

    // Class Properties
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order';

    protected $fillable = [
        'customer_id',
        'amount',
    ];
    protected $casts = [
        'created_at' => 'date',
    ];

    protected $hidden = [
        'deleted_at' => 'date',
    ];

    protected ?int $company_id = null;

    protected ?int $customer_id;

    protected ?int $status;



    /**
     * @var mixed
     */
    private ?float $amount;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orderCreated', function (Builder $builder) {
            $builder->orderby('created_at', 'DESC');
        });
    }

    // Constructor
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->company_id = 0;
        $this->customer_id = 0;
        $this->setAttribute('status', Order::PENDING);
        $this->setAttribute('created_at', now());
        $this->amount = 0.0;
    }

    // Relationships
    public function company(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Item::class, 'order_id', 'id');
    }

    // Accessors and Mutators
    public function getId(): int
    {
        return (int) $this->getAttribute('id');
    }

    /*
    * @return int
    */
    public function getCompanyId(): int
    {
        return (int) $this->getAttribute('company_id');
    }

    public function setCompanyId(int $company_id)
    {
        $this->setAttribute('company_id', $company_id);
    }

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return (int) $this->getAttribute('customer_id');
    }

    /**
     * @param  int  $customer_id
     */
    public function setCustomerId(int $customer_id): void
    {
        $this->setAttribute('customer_id', $customer_id);
    }

    public function getAmount(): float
    {
        return (float) $this->getAttribute('amount');
    }

    public function setAmount(float $amount): void
    {
        $this->setAttribute('amount', $amount);
    }

    /**
     * Return amount - discounts + taxes
     *
     * @return float
     */
    public function getTotal(): float
    {
        return $this->getAmount();
    }

    /**
     * @return Order[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return Order::all();
    }

    public function getPendingCount(int $company_id): int
    {
        return Order::where('company_id', $company_id)
                    ->where('status', self::PENDING)->count();
    }

    /**
     * @param  int  $company_id
     * @return mixed
     */
    public function getAllActiveByCompany(int $company_id)
    {
        return Order::with('customer')->where('company_id', $company_id)->paginate(10);
    }
}
