<?php

namespace App\Services;

use App\Models\Course;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CheckoutService
{
    private static function setUp(): void
    {
        /** @var string */
        $apiKey = config('services.stripe.secret');

        Stripe::setApiKey($apiKey);
    }

    public static function createCheckoutSession(Course $course, string $couponCode, string $successUrl, string $cancelUrl): Session
    {
        self::setUp();

        return Session::create([
            'line_items' => [
                [
                    'quantity' => 1,
                    'price_data' => [
                        'currency' => 'usd',
                        'unit_amount' => (int) (self::amount($course, $couponCode) * 100),
                        'product_data' => [
                            'name' => $course->title,
                        ],
                    ],
                ],
            ],
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'metadata' => [
                'course_id' => (string) $course->id,
            ],
        ]);
    }

    private static function amount(Course $course, string $couponCode): float
    {
        $coupon = $course->getActiveCoupon();
        if ($coupon && $coupon->code === $couponCode) {
            return $course->price * (100 - $coupon->discount) / 100;
        }

        return $course->price;
    }
}
