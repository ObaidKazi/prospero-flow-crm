<?php

namespace App\Http\Controllers\Mcp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class McpAgentController extends Controller
{
    public function chat(Request $request)
    {
        $sessionId = $request->session()->getId();


        $message = $request->input('message');
        $page = $request->input('page'); // For suggestion context
        $history = cache()->get("mcp_history_{$page}_$sessionId}", []);
        $systemPrompt = <<<PROMPT
            You are a helpful assistant for the $page page.
            Only answer page-related or greeting questions, refuse off-topic politely.
            ALWAYS reply ONLY in HTML.
            NEVER use markdown, never wrap your answer in ``` or code blocks, and never use bullet points, numbers, or dashes.
            Reply using ONLY HTML tags: <div>, <p>, <strong>, <span>, <ul>, <li>.
            DO NOT use numbers or hyphens for lists—just separate each item with a new <div> or <p>.
            If you output anything other than valid HTML, the user interface will break and the user will not be able to see the answer.
            Highlight important fields such as name, status, email with <strong> or inline CSS.
            Each item in a list must be a separate <div>.

            For example, if you receive this data:
            [{"name": "Test", "status": "Open", "email": "test@ex.com"}]

            Reply as:
            <div>
            <p><strong>Name:</strong> Test</p>
            <p><strong>Status:</strong> <span style='color:green'>Open</span></p>
            <p><strong>Email:</strong> test@ex.com</p>
            </div>
            PROMPT;

        // Build functions from config, using 'required' if present
        $functions = [];
        foreach (config('mcp_tools') as $tool) {
            $functions[] = [
                'name' => $tool['name'],
                'description' => $tool['description'],
                'parameters' => [
                    'type' => 'object',
                    'properties' => array_map(fn($type) => ['type' => $type], $tool['parameters']),
                    'required' => $tool['required'] ?? array_keys($tool['parameters']),
                ]
            ];
        }

        $openaiMessages = [
            ["role" => "system", "content" => $systemPrompt]
        ];
        foreach ($history as $msg) $openaiMessages[] = $msg;
        $openaiMessages[] = ["role" => "user", "content" => $message];

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o',
            'messages' => $openaiMessages,
            'functions' => $functions,
            'function_call' => "auto",
        ]);

        $openaiReply = $response['choices'][0]['message'];

        if (isset($openaiReply['function_call'])) {
            $function = $openaiReply['function_call']['name'];
            $arguments = json_decode($openaiReply['function_call']['arguments'], true) ?? [];

            $tool = collect(config('mcp_tools'))->firstWhere('name', $function);

            // Validate only required fields
            $missing = [];
            foreach ($tool['required'] ?? [] as $param) {
                if (!isset($arguments[$param]) || $arguments[$param] === '') {
                    $missing[] = $param;
                }
            }

            if ($missing) {
                $reply = "To proceed, please provide: " . implode(', ', $missing) . ".";
            } else {
                // Call Eloquent or custom function
                $callable = explode('::', $tool['eloquent']);
                $result = call_user_func($callable, $arguments);

                // ---------- Strict HTML Formatting Prompt ----------
                $formatPrompt = [
                    [
                        "role" => "system",
                        "content" => <<<PROMPT
                            You are a helpful assistant.
                            ALWAYS reply ONLY in HTML.
                            NEVER use markdown, never wrap your answer in ``` or code blocks, and never use bullet points, numbers, or dashes.
                            Reply using ONLY HTML tags: <div>, <p>, <strong>, <span>, <ul>, <li>.
                            DO NOT use numbers or hyphens for lists—just separate each item with a new <div> or <p>.
                            If you output anything other than valid HTML, the user interface will break and the user will not be able to see the answer.
                            Highlight important fields such as name, status, email with <strong> or inline CSS.
                            Each item in a list must be a separate <div>.

                            For example, if you receive this data:
                            [{"name": "Test", "status": "Open", "email": "test@ex.com"}]

                            Reply as:
                            <div>
                            <p><strong>Name:</strong> Test</p>
                            <p><strong>Status:</strong> <span style='color:green'>Open</span></p>
                            <p><strong>Email:</strong> test@ex.com</p>
                            </div>
                            PROMPT
                    ],
                    [
                        "role" => "user",
                        "content" => "Here is the raw result: " . json_encode($result)
                    ]
                ];

                $formatResponse = OpenAI::chat()->create([
                    'model' => 'gpt-4o',
                    'messages' => $formatPrompt,
                ]);

                $reply = $formatResponse['choices'][0]['message']['content'] ?? "Action done.";

                // ---------- STRIP CODE BLOCKS, MARKDOWN, BULLETS ----------
                $reply = preg_replace('/^```(?:html)?/im', '', $reply);
                $reply = preg_replace('/```$/im', '', $reply);
                $reply = preg_replace('/^(Here.*:\s*)/i', '', $reply);
                $reply = preg_replace('/^\s*[\-\*]\s?/m', '', $reply);
                $reply = preg_replace('/^\d+\.\s+/m', '', $reply);
                $reply = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $reply);
                $reply = trim($reply);
            }
        } else {
            // Standard LLM reply (instructions, follow-up, etc)
            $reply = $openaiReply['content'] ?? '...';
            $reply = preg_replace('/^```(?:html)?/im', '', $reply);
            $reply = preg_replace('/```$/im', '', $reply);
            $reply = preg_replace('/^(Here.*:\s*)/i', '', $reply);
            $reply = preg_replace('/^\s*[\-\*]\s?/m', '', $reply);
            $reply = preg_replace('/^\d+\.\s+/m', '', $reply);
            $reply = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $reply);
            $reply = trim($reply);
        }

        // --------- Update Chat History Properly ---------
        $history[] = ["role" => "user", "content" => $message];

        if (isset($openaiReply['function_call'])) {
            $history[] = [
                "role" => "function",
                "name" => $function ?? 'unknown',
                "content" => $reply
            ];
        } else {
            $history[] = [
                "role" => "assistant",
                "content" => $reply
            ];
        }

        cache()->put("mcp_history_{$page}_$sessionId}", $history, 60 * 60);

        return response()->json(['reply' => $reply]);
    }

    // Suggestions API remains unchanged
    public function suggestions(Request $request)
    {
        $page = $request->input('page');
        $suggestions = match ($page) {
            'lead' => ['Create lead', 'Search leads', 'Update lead'],
            'user' => ['Create user', 'Search users'],
            default => ['Hey there?']
        };
        return response()->json(['suggestions' => $suggestions]);
    }

}
