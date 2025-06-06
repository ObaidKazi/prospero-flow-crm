<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenApi\Annotations\OpenApi as OA;

/**
 *  @OA\Schema(
 *    schema="Tag",
 *    type="object",
 *    required={"name"},
 *    @OA\Property(
 *        property="name",
 *        description="Name of the tag",
 *        type="string",
 *        example="Important"
 *    ),
 *    @OA\Property(
 *        property="description",
 *        description="Description of the tag",
 *        type="string",
 *        example="For important items"
 *    ),
 *    @OA\Property(
 *        property="color",
 *        description="Color code for the tag",
 *        type="string",
 *        example="#FF5733"
 *    )
 *  )
 */
class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tags';

    protected $fillable = [
        'name',
        'description',
        'color'
    ];


}