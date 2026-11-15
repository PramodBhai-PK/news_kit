<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends ApiController
{
    public function subscribe(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $email = strtolower(trim($request->input('email')));

        $subscriber = Subscriber::firstOrCreate(
            ['email' => $email],
            ['is_active' => true]
        );

        if (!$subscriber->is_active) {
            $subscriber->update(['is_active' => true]);
        }

        return $this->success([
            'email' => $subscriber->email,
            'is_active' => (bool) $subscriber->is_active,
        ], 'Thank you for subscribing to our news bulletin.');
    }
}

