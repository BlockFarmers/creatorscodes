<?php

namespace Azuriom\Plugin\Creatorcodes\Http\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Creatorcodes\Models\CreatorCommission;
use Azuriom\Plugin\Creatorcodes\Services\PaypalPayoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use RuntimeException;

class CommissionController extends Controller
{
    public function index(): View
    {
        $commissions = CreatorCommission::with('creatorCode.creator')
            ->latest()
            ->paginate(30);

        $totalPending = CreatorCommission::where('paid_out', false)->sum('commission_amount');
        $totalPaid = CreatorCommission::where('paid_out', true)->sum('commission_amount');

        return view('creatorcodes::admin.commissions', [
            'commissions' => $commissions,
            'totalPending' => $totalPending,
            'totalPaid' => $totalPaid,
        ]);
    }

    public function markPaid(CreatorCommission $commission): RedirectResponse
    {
        $commission->update([
            'paid_out' => true,
            'paid_out_at' => now(),
        ]);

        return back()->with('success', 'Commission marquee comme payee.');
    }

    public function payoutPaypal(CreatorCommission $commission, PaypalPayoutService $paypal): RedirectResponse
    {
        try {
            $paypal->payout($commission);
        } catch (RuntimeException $e) {
            return back()->withErrors(['paypal' => $e->getMessage()]);
        }

        return back()->with('success', 'Versement PayPal envoye avec succes.');
    }
}
