<?php

namespace Azuriom\Plugin\Creatorcodes\Services;

use Azuriom\Plugin\Creatorcodes\Models\CreatorCommission;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PaypalPayoutService
{
    protected function baseUrl(): string
    {
        return config('creatorcodes.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    protected function getAccessToken(): string
    {
        $clientId = config('creatorcodes.paypal.client_id');
        $clientSecret = config('creatorcodes.paypal.client_secret');

        if (! $clientId || ! $clientSecret) {
            throw new RuntimeException(
                'Missing PayPal credentials: check CREATORCODES_PAYPAL_CLIENT_ID and'.
                'CREATORCODES_PAYPAL_CLIENT_SECRET in the .env file, then restart.'.
                'php artisan config:clear.'
            );
        }

        $response = Http::asForm()
            ->withBasicAuth($clientId, $clientSecret)
            ->post($this->baseUrl().'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if ($response->failed()) {
            throw new RuntimeException('PayPal authentication failed :'.$response->body());
        }

        return $response->json('access_token');
    }

    /**
     * @throws RuntimeException
     */
    public function payout(CreatorCommission $commission): void
    {
        if ($commission->paid_out) {
            throw new RuntimeException('This commission is already marked as paid.');
        }

        $creatorCode = $commission->creatorCode;
        $email = $creatorCode?->paypal_email;

        if (! $email) {
            throw new RuntimeException('No PayPal address provided for this creator.');
        }

        $token = $this->getAccessToken();
        $batchId = 'creatorcodes-commission-'.$commission->id;
        $response = Http::withToken($token)
            ->post($this->baseUrl().'/v1/payments/payouts', [
                'sender_batch_header' => [
                    'sender_batch_id' => $batchId,
                    'email_subject' => 'Your creator commission',
                    'email_message' => 'Thanks for your support !',
                ],
                'items' => [
                    [
                        'recipient_type' => 'EMAIL',
                        'amount' => [
                            'value' => number_format($commission->commission_amount, 2, '.', ''),
                            'currency' => $commission->currency,
                        ],
                        'receiver' => $email,
                        'note' => 'Creator commission #'.$commission->id,
                        'sender_item_id' => (string) $commission->id,
                    ],
                ],
            ]);

        if ($response->failed()) {
            $commission->update([
                'paypal_status' => 'error',
                'paypal_error' => $response->body(),
            ]);

            throw new RuntimeException('PayPal payment failed : '.$response->body());
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
