<?php
// config/mcp_tools.php

return [
    [
        'name' => 'create_lead',
        'description' => 'Create a new lead',
        'parameters' => [
            'name' => 'string',
            'email' => 'string',
        ],
        'required' => ['name', 'email'], // All fields required for create
        'eloquent' => 'App\\Models\\Lead::create',
    ],
    [
        'name' => 'search_lead',
        'description' => 'Search leads',
        'parameters' => [
            'query' => 'string'
        ],
        'required' => ['query'], // Only query is required
        'eloquent' => 'App\\Models\\Lead::search',
    ],
    [
        'name' => 'update_lead',
        'description' => 'Update a lead (id required, other fields optional)',
        'parameters' => [
            'id' =>'integer',
            'name' =>'string',
            'email' =>'string',
            'status' =>'string',
        ],
        'required' => ['id'], // Only 'id' is required!
        'eloquent' => 'App\\Models\\Lead::updateLeadFields',
    ],
];
