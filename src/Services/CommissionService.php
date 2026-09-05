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

    protected array $paidStatuses = ['completed'];

    public function handle(Payment $payment): void
    {
        if (CreatorCommission::where('order_id', $payment->id)->exists()) {
            return;
        }

        if (! in_array($payment->status, $this->paidStatuses, true)) {
            return;
        }

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

        if ($creatorCode->paypal_email) {
            try {
                $this->paypal->payout($commission);
            } catch (Throwable $e) {
                report($e);
            }
        }
    }
}
