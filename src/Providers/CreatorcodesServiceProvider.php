<?php

namespace Azuriom\Plugin\CreatorsCodes\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Plugin\CreatorsCodes\Models\CreatorSupport;
use Azuriom\Plugin\CreatorsCodes\Services\CommissionService;
use Azuriom\Plugin\CreatorsCodes\Services\PaypalPayoutService;
use Azuriom\Plugin\CreatorsCodes\View\Composers\CreatorProfileCardComposer;
use Azuriom\Plugin\Shop\Models\Payment;
use Illuminate\Support\Facades\View;
use Throwable;

class CreatorsCodesServiceProvider extends BasePluginServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom($this->pluginPath('config/creatorscodes.php'), 'creatorscodes');

        $this->app->singleton(CommissionService::class);
        $this->app->singleton(PaypalPayoutService::class);
    }

    public function boot(): void
    {
        $this->loadViews();
        $this->loadTranslations();
        $this->loadMigrations();
        $this->registerAdminNavigation();
        $this->registerUserNavigation();

        try {
            Payment::saved(function (Payment $payment) {
                if ($payment->wasChanged('status')) {
                    app(CommissionService::class)->handle($payment);
                }
            });
        } catch (Throwable $e) {
            report($e);
        }

        try {
            View::composer(['shop::cart.index', 'shop::offers.select'], function ($view) {
                $support = auth()->check()
                    ? CreatorSupport::with('creatorCode.creator')->where('user_id', auth()->id())->first()
                    : null;

                $view->with('creatorSupport', $support);
            });
        } catch (Throwable $e) {
            report($e);
        }

        try {
            View::composer('profile.index', CreatorProfileCardComposer::class);
        } catch (Throwable $e) {
            report($e);
        }
    }

    protected function adminNavigation(): array
    {
        return [
            'creatorscodes' => [
                'name' => 'Creators Codes',
                'type' => 'dropdown',
                'icon' => 'bi bi-person-badge',
                'route' => 'creatorscodes.admin.*',
                'items' => [
                    'creatorscodes.admin.index' => [
                        'name' => 'Creators Codes',
                    ],
                    'creatorscodes.admin.commissions' => [
                        'name' => 'Commissions',
                    ],
                ],
            ],
        ];
    }

    protected function userNavigation(): array
    {
        return [
            'creatorscodes' => [
                'route' => 'creatorscodes.support',
                'name' => 'Support a creator',
                'icon' => 'bi bi-person-heart',
            ],
        ];
    }
}
