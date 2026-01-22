<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MusicPromotion;
use App\Models\PromotionPayment;
use App\Models\PromotionWaitlist;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class MusicPromotionController extends Controller
{
    protected const MAX_SLOTS = 6;
    protected const PRICE_7_DAYS = 5000; // NGN
    protected const PRICE_14_DAYS = 9000; // NGN

    protected PaystackService $paystackService;

    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    /**
     * Check available slots
     */
    public function checkAvailability()
    {
        $activeCount = MusicPromotion::active()->count();
        $available = $activeCount < self::MAX_SLOTS;
        
        return response()->json([
            'available' => $available,
            'active_count' => $activeCount,
            'max_slots' => self::MAX_SLOTS,
            'remaining' => max(0, self::MAX_SLOTS - $activeCount),
        ]);
    }

    /**
     * Get pricing information
     */
    public function getPricing()
    {
        return response()->json([
            'durations' => [
                [
                    'days' => 7,
                    'price' => self::PRICE_7_DAYS,
                    'formatted_price' => '₦' . number_format(self::PRICE_7_DAYS, 2),
                ],
                [
                    'days' => 14,
                    'price' => self::PRICE_14_DAYS,
                    'formatted_price' => '₦' . number_format(self::PRICE_14_DAYS, 2),
                ],
            ],
        ]);
    }

    /**
     * Submit promotion form and initiate payment
     */
    public function submit(Request $request)
    {
        // Check slot availability first
        $activeCount = MusicPromotion::active()->count();
        if ($activeCount >= self::MAX_SLOTS) {
            return response()->json([
                'success' => false,
                'message' => 'All promotion slots are currently full. Please join the waitlist.',
                'slots_full' => true,
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'artist_name' => 'required|string|max:255',
            'track_title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'audio_embed_url' => 'nullable|url|max:500',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cta_url' => 'nullable|url|max:500',
            'duration_days' => 'required|in:7,14',
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Calculate price based on duration
            $duration = (int) $request->duration_days;
            $price = $duration === 14 ? self::PRICE_14_DAYS : self::PRICE_7_DAYS;

            // Handle cover image upload
            $coverImagePath = null;
            if ($request->hasFile('cover_image')) {
                $coverImagePath = $request->file('cover_image')->store('promotions', 'public');
            }

            // Create promotion record with pending status
            $promotion = MusicPromotion::create([
                'user_id' => Auth::id(),
                'artist_name' => $request->artist_name,
                'track_title' => $request->track_title,
                'description' => $request->description,
                'audio_embed_url' => $request->audio_embed_url,
                'cover_image' => $coverImagePath,
                'cta_url' => $request->cta_url,
                'duration_days' => $duration,
                'price_paid' => $price,
                'status' => 'pending',
            ]);

            // Initialize Paystack payment
            $paystackData = [
                'email' => $request->email,
                'amount' => $price * 100, // Paystack expects amount in kobo
                'reference' => 'PROMO_' . $promotion->id . '_' . time(),
                'callback_url' => route('promotions.callback'),
                'metadata' => [
                    'promotion_id' => $promotion->id,
                    'custom_fields' => [
                        [
                            'display_name' => 'Promotion Type',
                            'variable_name' => 'promotion_type',
                            'value' => "Music Promotion - {$duration} days",
                        ],
                    ],
                ],
            ];

            $paystackResponse = $this->paystackService->initializeTransaction($paystackData);

            if (!$paystackResponse['status']) {
                throw new \Exception('Failed to initialize payment');
            }

            // Create payment record
            PromotionPayment::create([
                'music_promotion_id' => $promotion->id,
                'paystack_reference' => $paystackResponse['data']['reference'],
                'amount' => $price,
                'currency' => 'NGN',
                'status' => 'pending',
                'paystack_response' => $paystackResponse,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'authorization_url' => $paystackResponse['data']['authorization_url'],
                'access_code' => $paystackResponse['data']['access_code'],
                'reference' => $paystackResponse['data']['reference'],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Promotion submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process promotion. Please try again.',
            ], 500);
        }
    }

    /**
     * Handle Paystack callback
     */
    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('home')->with('error', 'Invalid payment reference');
        }

        try {
            // Verify payment with Paystack
            $verification = $this->paystackService->verifyTransaction($reference);

            if ($verification['status'] && $verification['data']['status'] === 'success') {
                // Find payment record
                $payment = PromotionPayment::where('paystack_reference', $reference)->first();

                if ($payment && $payment->status === 'pending') {
                    DB::beginTransaction();

                    // Update payment status
                    $payment->update([
                        'status' => 'success',
                        'paystack_response' => $verification,
                    ]);

                    // Activate promotion
                    $promotion = $payment->promotion;
                    $now = Carbon::now();
                    $promotion->update([
                        'status' => 'active',
                        'starts_at' => $now,
                        'ends_at' => $now->copy()->addDays($promotion->duration_days),
                    ]);

                    DB::commit();

                    // Notify waitlist if slots were full before
                    $this->notifyWaitlistIfNeeded();

                    return redirect()->route('home')->with('success', 'Your music promotion is now live!');
                }
            }

            return redirect()->route('home')->with('error', 'Payment verification failed');

        } catch (\Exception $e) {
            Log::error('Payment callback failed', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('home')->with('error', 'Payment processing error. Please contact support.');
        }
    }

    /**
     * Handle Paystack webhook
     */
    public function webhook(Request $request)
    {
        // Verify webhook signature (Paystack sends a hash)
        $paystackSignature = $request->header('x-paystack-signature');
        
        // For now, we'll verify the transaction directly
        // In production, you should verify the webhook signature
        
        $event = $request->input('event');
        $data = $request->input('data');

        if ($event === 'charge.success' && isset($data['reference'])) {
            try {
                // Verify transaction
                $verification = $this->paystackService->verifyTransaction($data['reference']);

                if ($verification['status'] && $verification['data']['status'] === 'success') {
                    $payment = PromotionPayment::where('paystack_reference', $data['reference'])->first();

                    if ($payment && $payment->status === 'pending') {
                        DB::beginTransaction();

                        $payment->update([
                            'status' => 'success',
                            'paystack_response' => $verification,
                        ]);

                        $promotion = $payment->promotion;
                        $now = Carbon::now();
                        $promotion->update([
                            'status' => 'active',
                            'starts_at' => $now,
                            'ends_at' => $now->copy()->addDays($promotion->duration_days),
                        ]);

                        DB::commit();

                        $this->notifyWaitlistIfNeeded();
                    }
                }
            } catch (\Exception $e) {
                Log::error('Webhook processing failed', [
                    'event' => $event,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Join waitlist
     */
    public function joinWaitlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email address',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            PromotionWaitlist::firstOrCreate(
                ['email' => $request->email],
                ['notified' => false]
            );

            return response()->json([
                'success' => true,
                'message' => 'You have been added to the waitlist. We will notify you when slots become available.',
            ]);
        } catch (\Exception $e) {
            Log::error('Waitlist join failed', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to join waitlist. Please try again.',
            ], 500);
        }
    }

    /**
     * Track CTA click
     */
    public function trackClick($id)
    {
        $promotion = MusicPromotion::findOrFail($id);
        $promotion->incrementClicks();

        return response()->json(['success' => true]);
    }

    /**
     * Notify waitlist when slots become available
     */
    protected function notifyWaitlistIfNeeded()
    {
        $activeCount = MusicPromotion::active()->count();
        
        if ($activeCount < self::MAX_SLOTS) {
            $waitlist = PromotionWaitlist::where('notified', false)->get();
            
            foreach ($waitlist as $entry) {
                // Send email notification (implement email sending)
                // For now, just mark as notified
                $entry->update([
                    'notified' => true,
                    'notified_at' => Carbon::now(),
                ]);
            }
        }
    }
}
