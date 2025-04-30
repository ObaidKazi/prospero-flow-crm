<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Lead;

use App\Repositories\LeadRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Import the Validator facade
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Log;

class LeadCreateController
{
    private LeadRepository $leadSaveRepository;

    public function __construct(LeadRepository $leadRepository)
    {
        $this->leadSaveRepository = $leadRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/lead",
     *     summary="Create a Lead",
     *     tags={"Lead"},
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              required={"name", "email"},
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  example="John Smith"
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  example="john.smith@example.com"
     *              ),
     *              @OA\Property(
     *                   property="business_name",
     *                   type="string",
     *                   example="John Smith LTD"
     *              ),
     *              @OA\Property(
     *                  property="phone",
     *                  type="string", 
     *                  example="34123456789",
     *              ),
     *              @OA\Property(
     *                  property="notes",
     *                  type="string",
     *                  example="Notes of the lead",
     *              ), 
     *              @OA\Property( 
     *                  property="company_id",
     *                  type="integer", 
     *                  example=1,
     *              ),
     *              @OA\Property(
     *                  property="seller_id",
     *                  type="integer", 
     *                  example=1,
     *              ),
     *          )
     *     ),
     *     @OA\Response(response="201", description="Lead created successfully"),
     *     @OA\Response(
     *          response="422",
     *          description="Validation error", 
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={"email": {"The email field is required."}})
     *          )
     *     ),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="500", description="Internal server error")
     *
     * )
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            // Define validation rules
            $rules = [
                'name' => ['required', 'string', 'max:80'], // Added string type hint
                'email' => ['required', 'string', 'max:254', 'email'], // Added string type hint
                'business_name' => ['nullable', 'string', 'max:255'], // Added validation for fields in OA doc
                'phone' => ['nullable', 'string', 'max:20'], // Added validation for fields in OA doc
                'notes' => ['nullable', 'string'], // Added validation for fields in OA doc
                'company_id' => ['nullable', 'integer', 'exists:company,id'], // Assuming table is 'companies' and type is integer
                'seller_id' => ['nullable', 'integer', 'exists:user,id'], // Assuming table is 'users' and type is integer
            ];

            // Create a validator instance
            $validator = Validator::make($request->all(), $rules);

            // Check if validation fails
            if ($validator->fails()) {
                // Return a custom JSON response with 422 status code
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $validator->errors() // Use Laravel's standard error structure
                ], 422);
            }

            // Get validated data
            $validatedData = $validator->validated();

            // Save the lead using the validated data
            $lead = $this->leadSaveRepository->save($validatedData);

            // Return success response
            return response()->json($lead, 201);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
