<?php

namespace App\Http\Controllers;

use App\Models\ChatbotKnowledge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AskDarlingController extends Controller
{
    /**
     * Handle chat message and return response
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ask(Request $request): \Illuminate\Http\JsonResponse
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:500',
            'session_id' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input. Message is required and must be less than 500 characters.',
            ], 422);
        }

        $message = trim($request->input('message'));
        $sessionId = $request->input('session_id', 'anonymous');

        // Load active knowledge entries, ordered by priority and usage
        $knowledgeEntries = ChatbotKnowledge::where('is_active', true)
            ->orderByDesc('priority')
            ->orderByDesc('usage_count')
            ->get();

        // Score each entry
        $bestMatch = null;
        $bestScore = 0;

        foreach ($knowledgeEntries as $entry) {
            $score = $this->calculateScore($message, $entry);

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMatch = $entry;
            }
        }

        // Use best match if score is sufficient, otherwise fallback
        if ($bestMatch && $bestScore >= 12) {
            // Increment usage count
            $bestMatch->increment('usage_count');

            // Log successful match
            Log::info('AskDarling: Match found', [
                'session_id' => $sessionId,
                'message' => substr($message, 0, 100), // Log first 100 chars only
                'keyword' => $bestMatch->keyword,
                'category' => $bestMatch->category,
                'score' => $bestScore,
            ]);

            return response()->json([
                'response' => $bestMatch->response,
                'matched' => [
                    'keyword' => $bestMatch->keyword,
                    'category' => $bestMatch->category,
                ],
                'score' => $bestScore,
            ]);
        }

        // Fallback response
        $fallbackResponse = "Hmm... I'm still learning o! 😅\n\n" .
            "Try asking about: ad rates, shows timetable, roadshows, music request, listen live, or technical wahala.\n\n" .
            "Or just WhatsApp us sharp sharp: +234 803 000 1073";

        // Log unmatched query
        Log::info('AskDarling: No match found', [
            'session_id' => $sessionId,
            'message' => substr($message, 0, 100),
            'score' => $bestScore,
        ]);

        return response()->json([
            'response' => $fallbackResponse,
            'matched' => null,
            'score' => $bestScore,
        ]);
    }

    /**
     * Calculate score for a message against a knowledge entry
     * 
     * @param string $message
     * @param ChatbotKnowledge $entry
     * @return int
     */
    private function calculateScore(string $message, ChatbotKnowledge $entry): int
    {
        $score = 0;
        $lowerMessage = strtolower($message);
        $lowerKeyword = strtolower($entry->keyword);

        // +12 if message contains keyword exactly (case insensitive)
        if (str_contains($lowerMessage, $lowerKeyword)) {
            $score += 12;
        }

        // +6-10 per regex pattern match from question_patterns
        if ($entry->question_patterns && is_array($entry->question_patterns)) {
            foreach ($entry->question_patterns as $pattern) {
                try {
                    if (preg_match('/' . $pattern . '/i', $message) === 1) {
                        // Give higher score for more specific patterns (longer = more specific)
                        $patternScore = min(10, max(6, strlen($pattern) / 5));
                        $score += (int) $patternScore;
                    }
                } catch (\Exception $e) {
                    // Invalid regex pattern, skip
                    continue;
                }
            }
        }

        // +4 per keyword word found as substring (for loose matching)
        $keywordWords = explode(' ', $lowerKeyword);
        foreach ($keywordWords as $word) {
            $word = trim($word);
            if (strlen($word) > 2 && str_contains($lowerMessage, $word)) {
                $score += 4;
            }
        }

        // +3 bonus if question ≤ 8 words and has any match
        $wordCount = str_word_count($message);
        if ($wordCount <= 8 && $score > 0) {
            $score += 3;
        }

        // +2 if category is frequently used (optional future enhancement)
        // For now, we can add a small bonus for high-priority entries
        if ($entry->priority >= 5) {
            $score += 2;
        }

        return $score;
    }

    /**
     * Handle feedback submission
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function feedback(Request $request): \Illuminate\Http\JsonResponse
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'session_id' => 'nullable|string|max:100',
            'message' => 'nullable|string|max:500',
            'feedback' => 'required|in:good,bad',
            'keyword' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input.',
            ], 422);
        }

        // Log feedback (sanitized)
        Log::info('AskDarling: Feedback received', [
            'session_id' => $request->input('session_id', 'anonymous'),
            'feedback' => $request->input('feedback'),
            'message' => $request->input('message') ? substr($request->input('message'), 0, 100) : null,
            'keyword' => $request->input('keyword'),
        ]);

        return response()->json([
            'status' => 'received, thank you!',
        ]);
    }
}
