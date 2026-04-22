<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class OpenAIProductivityService
{
    public function generateSubtasks(string $title, ?string $description = null): array
    {
        $prompt = "Create concise actionable subtasks for this task.\nTitle: {$title}\nDescription: ".($description ?? 'N/A')."\nReturn as plain lines.";

        $content = $this->chat($prompt);
        $lines = preg_split('/\r\n|\r|\n/', trim($content)) ?: [];

        return collect($lines)
            ->map(fn ($line) => trim(preg_replace('/^[-*\d\.\)\s]+/', '', $line)))
            ->filter()
            ->values()
            ->all();
    }

    public function improveDescription(string $description): string
    {
        return $this->chat("Rewrite this task description to be professional, specific, and execution-ready:\n{$description}");
    }

    public function summarizeComments(string $comments): string
    {
        return $this->chat("Summarize this discussion in 3 bullet points with key decisions and blockers:\n{$comments}");
    }

    private function chat(string $prompt): string
    {
        $response = OpenAI::chat()->create([
            'model' => config('services.openai.model', 'gpt-4o-mini'),
            'messages' => [
                ['role' => 'system', 'content' => 'You are a productivity assistant for software teams.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.4,
        ]);

        return trim($response->choices[0]->message->content ?? '');
    }
}
