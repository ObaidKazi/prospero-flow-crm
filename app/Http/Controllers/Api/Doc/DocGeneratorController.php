<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Doc;

use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OAT;


#[
    OAT\Info(
        version: '1.0.4',
        description: '',
        title: 'Prospero Flow CRM API',
        contact: new OAT\Contact(
            name: 'roskus',
            email: 'hello@roskus.com'
        )
    )
]
/**
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Authorisation with JWT generated tokens",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth"
 * )
 */
class DocGeneratorController
{
    public function render(): JsonResponse
    {
        $app_path = app_path();
        $exclude = [];
        $pattern = '*.php';

        $openapi = \OpenApi\Generator::scan([
            $app_path.DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'Api',
            $app_path.DIRECTORY_SEPARATOR.'Models',
        ],
            [
                'exclude' => $exclude,
                'pattern' => $pattern,
            ]);

        return response()->json($openapi->toJson());
    }
}
