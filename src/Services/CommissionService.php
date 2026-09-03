<?php

namespace Azuriom\Plugin\Creatorcodes\Services;

use Azuriom\Plugin\Creatorcodes\Models\CreatorCommission;
use Azuriom\Plugin\Creatorcodes\Models\CreatorSupport;
use Azuriom\Plugin\Shop\Models\Payment;
use Throwable;

class CommissionService
{
    public function __construct(protected PaypalPayoutService $paypal)
    {
    }

    /**
     * Confirme via plugins/shop/src/Models/Payment.php : le statut "paye"
     * vaut bien 'completed' (voir Payment::isCompleted()).
     */
    protected array $paidStatuses = ['completed'];

    public function handle(Payment $payment): void
    {
        // Evite tout doublon si le paiement est resauvegarde plusieurs fois
        if (CreatorCommission::where('order_id', $payment->id)->exists()) {
            return;
        }

        if (! in_array($payment->status, $this->paidStatuses, true)) {
            return;
        }

        // Jamais de commission sur un paiement en points boutique (monnaie du
        // site) : le champ 'price' y represente des points, pas des euros,
        // et le taux points/euros n'est pas forcement fixe selon l'offre.
        // Seuls les paiements en argent reel (achat d'une offre de points,
        // ou d'un produit paye directement en euros) generent une commission.
        if ($payment->isWithSiteMoney()) {
            return;
        }

        $buyerId = $payment->user_id;

        if ($buyerId === null) {
            return;
        }

        $support = CreatorSupport::with('creatorCode')
            ->where('user_id', $buyerId)
            ->first();

        if (! $support || ! $support->creatorCode || ! $support->creatorCode->active) {
            return;
        }

        // Confirme via Payment.php : le champ montant s'appelle bien 'price'.
        $orderAmount = (float) $payment->price;

        if ($orderAmount <= 0) {
            return;
        }

        $creatorCode = $support->creatorCode;
        $commissionAmount = round($orderAmount * ($creatorCode->commission_rate / 100), 2);

        $commission = CreatorCommission::create([
            'creator_code_id' => $creatorCode->id,
            'order_id' => $payment->id,
            'buyer_id' => $buyerId,
            'order_amount' => $orderAmount,
            'commission_amount' => $commissionAmount,
            'currency' => $payment->currency,
        ]);

        // Tentative de versement automatique si le createur a une adresse
        // PayPal renseignee. En cas d'echec (email absent, erreur API...),
        // la commission reste "en attente" : le bouton manuel sur la page
        // admin permet de reessayer plus tard.
        if ($creatorCode->paypal_email) {
            try {
                $this->paypal->payout($commission);
            } catch (Throwable $e) {
                report($e);
            }
        }
    }
}
