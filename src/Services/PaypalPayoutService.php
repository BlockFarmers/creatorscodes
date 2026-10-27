<?php

namespace Azuriom\Plugin\Creatorscodes\Services;

use Azuriom\Plugin\Creatorscodes\Models\CreatorCommission;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PaypalPayoutService
{
    protected function baseUrl(): string
    {
        return config('creatorscodes.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    protected function getAccessToken(): string
    {
        $clientId = config('creatorscodes.paypal.client_id');
        $clientSecret = config('creatorscodes.paypal.client_secret');

        if (! $clientId || ! $clientSecret) {
            throw new RuntimeException(trans('creatorscodes::admin.paypal.errors.missing_credentials'));
        }

        $response = Http::asForm()
            ->withBasicAuth($clientId, $clientSecret)
            ->post($this->baseUrl().'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if ($response->failed()) {
            throw new RuntimeException(trans('creatorscodes::admin.paypal.errors.auth_failed', ['error' => $response->body()]));
        }

        return $response->json('access_token');
    }

    /**
     * @throws RuntimeException
     */
    public function payout(CreatorCommission $commission): void
    {
        if ($commission->paid_out) {
            throw new RuntimeException(trans('creatorscodes::admin.paypal.errors.already_paid'));
        }

        $creatorCode = $commission->creatorCode;
        $email = $creatorCode?->paypal_email;

        if (! $email) {
            throw new RuntimeException(trans('creatorscodes::admin.paypal.errors.no_email'));
        }

        $token = $this->getAccessToken();
        $batchId = 'creatorscodes-commission-'.$commission->id;
        $response = Http::withToken($token)
            ->post($this->baseUrl().'/v1/payments/payouts', [
                'sender_batch_header' => [
                    'sender_batch_id' => $batchId,
                    'email_subject' => trans('creatorscodes::admin.paypal.email_subject'),
                    'email_message' => trans('creatorscodes::admin.paypal.email_message'),
                ],
                'items' => [
                    [
                        'recipient_type' => 'EMAIL',
                        'amount' => [
                            'value' => number_format($commission->commission_amount, 2, '.', ''),
                            'currency' => $commission->currency,
                        ],
                        'receiver' => $email,
                        'note' => trans('creatorscodes::admin.paypal.note', ['id' => $commission->id]),
                        'sender_item_id' => (string) $commission->id,
                    ],
                ],
            ]);

        if ($response->failed()) {
            $commission->update([
                'paypal_status' => 'error',
                'paypal_error' => $response->body(),
            ]);

            throw new RuntimeException(trans('creatorscodes::admin.paypal.errors.payout_failed', ['error' => $response->body()]));
        }

        $commission->update([
            'paid_out' => true,
            'paid_out_at' => now(),
            'paypal_batch_id' => $response->json('batch_header.payout_batch_id'),
            'paypal_status' => $response->json('batch_header.batch_status'),
            'paypal_error' => null,
        ]);
    }
}
