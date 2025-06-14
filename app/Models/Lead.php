<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Lead\Message;
use App\Models\Scopes\AssignedSellerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenApi\Annotations\OpenApi as OA;
use Squire\Models\Country;
use Yajra\Auditable\AuditableWithDeletesTrait;

/**
 *  @OA\Schema(
 *    schema="Lead",
 *    type="object",
 *    required={"name", "country", "seller_id"},
 *    @OA\Property(
 *        property="name",
 *        description="Name of the lead",
 *        type="string",
 *        example="My Company"
 *    ),
 *    @OA\Property(
 *        property="business_name",
 *        description="Business name or legal name of the lead",
 *        type="string",
 *        example="My Company S.A."
 *    ),
 *    @OA\Property(
 *        property="dob",
 *        description="Date of fundation of the lead",
 *        type="date",
 *        example="1990-02-20"
 *    ),
 *    @OA\Property(
 *        property="vat",
 *        description="VAT/NIF of the lead",
 *        type="string",
 *        example="ESX1234567X"
 *    ),
 *    @OA\Property(
 *        property="phone",
 *        description="Phone of the lead",
 *        type="string",
 *        example="+3464500000"
 *    ),
 *    @OA\Property(
 *        property="extension",
 *        description="Phone extension of the customer",
 *        type="string",
 *        example="4004"
 *    ),
 *    @OA\Property(
 *        property="phone2",
 *        description="Phone2 of the lead",
 *        type="string",
 *        example="+3464500000"
 *    ),
 *    @OA\Property(
 *        property="mobile",
 *        description="Mobile of the lead",
 *        type="string",
 *        example="+3464500000"
 *    ),
 *    @OA\Property(
 *        property="email",
 *        description="Email of the lead",
 *        type="string",
 *        format="email",
 *        example="jhon.doe@email.com"
 *    ),
 *    @OA\Property(
 *        property="email2",
 *        description="Email2 of the lead",
 *        type="string",
 *        format="email",
 *        example="jhon.doe@email.com"
 *    ),
 *    @OA\Property(
 *        property="website",
 *        description="Website of the lead",
 *        type="string",
 *        format="url",
 *        example="https://www.company.com"
 *    ),
 *    @OA\Property(
 *        property="source_id",
 *        description="Source ID of the lead",
 *        type="int",
 *        example="1"
 *    ),
 *    @OA\Property(
 *        property="linkedin",
 *        description="Linkedin of the lead",
 *        type="string",
 *        format="url",
 *        example="https://www.likedin.com/in/profile"
 *    ),
 *    @OA\Property(
 *        property="seller_id",
 *        description="SellerID of the lead",
 *        type="string"
 *    )
 * )
 */
class Lead extends Model
{
    use AuditableWithDeletesTrait;
    use HasFactory;
    use SoftDeletes;

    const OPEN = 'open'; // New

    const IN_PROGRESS = 'in_progress';

    const WAITING_FEEDBACK = 'waiting_feedback';

    const CONVERTED = 'converted'; // Promoted to customer

    const CLOSED = 'closed';

    protected $table = 'lead';

    protected $fillable = [
        'company_id',
        'external_id',
        'name',
        'business_name',
        'dob',
        'vat',
        'phone',
        'phone_verified',
        'extension',
        'phone2',
        'phone2_verified',
        'mobile',
        'mobile_verified',
        'email',
        'email_verified',
        'email2',
        'website',
        'source_id',
        'linkedin',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'tiktok',
        'notes',
        'seller_id',
        'country_id',
        'province',
        'city',
        'locality',
        'street',
        'zipcode',
        'schedule_contact',
        'industry_id',
        'latitude',
        'longitude',
        'opt_in',
        'tags',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'schedule_contact' => 'datetime:Y-m-d H:i',
        'tags' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'dob' => 'date:Y-m-d',
    ];

    protected $hidden = [
        'company_id',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $with = ['country', 'seller', 'industry', 'company'];

    public function company()
    {
        return $this->hasOne(\App\Models\Company::class, 'id', 'company_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public static function search($params)
    {
        $query = static::query();
        if (isset($params['query'])) {
            $search = $params['query'];
            $query->where(function ($q) use ($search) {
                foreach ((new static)->getFillable() as $column) {
                    $q->orWhere($column, 'like', "%$search%");
                }
            });
        }

        $leads = $query->get();

        foreach ($leads as $lead) {
            if (is_array($lead->tags)) {
                $tags = \App\Models\Tag::whereIn('id', $lead->tags)->get();
                $lead->tags = $tags;
            }
        }

        return $leads;
    }

    public function seller()
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'seller_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function source(): HasOne
    {
        return $this->hasOne(\App\Models\Source::class, 'id', 'source_id');
    }

    public function industry()
    {
        return $this->hasOne(\App\Models\Industry::class, 'id', 'industry_id');
    }

    public function contacts()
    {
        return $this->hasMany(\App\Models\Contact::class, 'lead_id', 'id');
    }

    public function getAll()
    {
        return Lead::all();
    }

    public function getTagsAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }

    public function setTagsAttribute($value)
    {
        $this->attributes['tags'] = $value ? json_encode($value) : null;
    }

    public function getAllByCompanyId(?int $company_id = 0, ?string $search, ?array $filters, ?string $order_by = 'created_at'): mixed
    {
        if (is_null($order_by)) {
            $order_by = 'created_at';
        }
        if ($company_id == 0) {
            $leads = Lead::query();
        } else {
            $leads = Lead::where('company_id', $company_id);
        }
        if (! empty($search)) {
            $leads->where(function ($query) use ($search) {
                $words = explode(' ', $search);
                if (count($words) == 1) {
                    $query->where('name', 'LIKE', "%$search%")
                        ->orWhere('business_name', 'LIKE', "%$search%")
                        ->orWhere('tags', 'LIKE', "%$search%");
                } else {
                    $query->whereFullText(['name', 'business_name'], $search)
                        ->orWhere('tags', 'LIKE', "%$search%");
                }
            });
        }

        if (is_array($filters)) {
            foreach ($filters as $key => $filter) {
                $leads->where($key, $filter);
            }
        }

        return $leads->orderBy($order_by, 'desc')->paginate(10);
    }

    public function getCountByCompany(int $company_id = 0): int
    {
        if ($company_id == 0) {
            $leads = Lead::query();
        } else {
            $leads = Lead::where('company_id', $company_id);
        }
        return $leads->count();
    }

    public function getLatestByCompany(int $company_id = 0, int $limit = 10)
    {
        if ($company_id == 0) {
            $leads = Lead::query();
        } else {
            $leads = Lead::where('company_id', $company_id);
        }
        $leads->orderBy('created_at', 'DESC');

        return $leads->limit($limit)->get();
    }

    public function getScore(): int
    {
        // Ejemplo de lógica de puntuación
        $score = 0;

        // Aumentar puntuación segun criterios específicos
        if (! empty($this->email)) {
            $score = $this->email_verified ? $score + 10 : $score - 10;
        }

        if (! empty($this->mobile)) {
            $score = $this->mobile_verified ? $score + 10 : $score - 10;
        }

        if (! empty($this->phone)) {
            $score = $this->phone_verified ? $score + 10 : $score - 10;
        }

        if ($this->contacts()->count() > 0) {
            $score += 10;
        }

        return $score;
    }

    public static function getStatus(): array
    {
        return [
            self::OPEN => 'Open',
            self::IN_PROGRESS => 'In progress',
            self::WAITING_FEEDBACK => 'Waiting for feedback',
            self::CONVERTED => 'Converted',
            self::CLOSED => 'Closed',
        ];
    }

    // app/Models/Lead.php

    public static function updateLeadFields($params)
    {
        $id = $params['id'] ?? null;
        if (!$id) return ['error' => 'Lead ID is required.'];
        $lead = static::find($id);
        if (!$lead) return ['error' => 'Lead not found.'];

        $allowedStatuses = [
            'open', 'first_contact', 'recall', 'quote', 'quoted',
            'waiting_for_answer', 'standby', 'closed', 'in_progress',
            'waiting_feedback', 'converted'
        ];

        if (isset($params['status']) && !empty($params['status'])) {
            if (!in_array($params['status'], $allowedStatuses)) {
                return ['error' => 'Invalid status value. Allowed values are: ' . implode(', ', $allowedStatuses)];
            }
        }

        $updated = false;
        foreach (['name', 'email', 'status'] as $field) {
            if (isset($params[$field]) && !empty($params[$field])) {
                // For status, we've already validated it if it was set
                $lead->$field = $params[$field];
                $updated = true;
            }
        }
        if ($updated) {
            $lead->save();
            return $lead->toArray();
        } else {
            return ['error' => 'No fields to update.'];
        }
    }

public static function createFromImage($params)
    {
        $prompt = $params['prompt'];
        return [
            'message' => "I have the following information: {$prompt}. Should I create a new lead or update an existing one?",
            'actions' => ['create', 'update']
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new AssignedSellerScope);
    }
}
