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
                'Identifiants PayPal manquants : verifie CREATORCODES_PAYPAL_CLIENT_ID et '.
                'CREATORCODES_PAYPAL_CLIENT_SECRET dans le fichier .env, puis relance '.
                'php artisan config:clear.'
            );
        }

        $response = Http::asForm()
            ->withBasicAuth($clientId, $clientSecret)
            ->post($this->baseUrl().'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Echec de l\'authentification PayPal : '.$response->body());
        }

        return $response->json('access_token');
    }

    /**
     * Envoie le versement PayPal pour une commission et met a jour son statut.
     *
     * @throws RuntimeException si le versement ne peut pas etre effectue
     */
    public function payout(CreatorCommission $commission): void
    {
        if ($commission->paid_out) {
            throw new RuntimeException('Cette commission est deja marquee comme payee.');
        }

        $creatorCode = $commission->creatorCode;
        $email = $creatorCode?->paypal_email;

        if (! $email) {
            throw new RuntimeException('Aucune adresse PayPal renseignee pour ce createur.');
        }

        $token = $this->getAccessToken();

        // Identifiant de lot stable et unique par commission : si ce versement
        // est renvoye deux fois (double-clic, retry), PayPal detecte le doublon
        // via ce meme sender_batch_id au lieu de payer deux fois.
        $batchId = 'creatorcodes-commission-'.$commission->id;

        $response = Http::withToken($token)
            ->post($this->baseUrl().'/v1/payments/payouts', [
                'sender_batch_header' => [
                    'sender_batch_id' => $batchId,
                    'email_subject' => 'Ta commission createur',
                    'email_message' => 'Merci pour ton soutien !',
                ],
                'items' => [
                    [
                        'recipient_type' => 'EMAIL',
                        'amount' => [
                            'value' => number_format($commission->commission_amount, 2, '.', ''),
                            'currency' => $commission->currency,
                        ],
                        'receiver' => $email,
                        'note' => 'Commission createur #'.$commission->id,
                        'sender_item_id' => (string) $commission->id,
                    ],
                ],
            ]);

        if ($response->failed()) {
            $commission->update([
                'paypal_status' => 'error',
                'paypal_error' => $response->body(),
            ]);

            throw new RuntimeException('Echec du versement PayPal : '.$response->body());
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
