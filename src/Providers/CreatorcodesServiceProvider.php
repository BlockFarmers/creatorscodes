<?php

namespace Azuriom\Plugin\Creatorcodes\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Plugin\Creatorcodes\Models\CreatorSupport;
use Azuriom\Plugin\Creatorcodes\Services\CommissionService;
use Azuriom\Plugin\Creatorcodes\Services\PaypalPayoutService;
use Azuriom\Plugin\Creatorcodes\View\Composers\CreatorProfileCardComposer;
use Azuriom\Plugin\Shop\Models\Payment;
use Illuminate\Support\Facades\View;
use Throwable;

class CreatorcodesServiceProvider extends BasePluginServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom($this->pluginPath('config/creatorcodes.php'), 'creatorcodes');

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
            'creatorcodes' => [
                'name' => 'Codes createur',
                'type' => 'dropdown',
                'icon' => 'bi bi-person-badge',
                'route' => 'creatorcodes.admin.*',
                'items' => [
                    'creatorcodes.admin.index' => [
                        'name' => 'Codes createur',
                    ],
                    'creatorcodes.admin.commissions' => [
                        'name' => 'Commissions',
                    ],
                ],
            ],
        ];
    }

    protected function userNavigation(): array
    {
        return [
            'creatorcodes' => [
                'route' => 'creatorcodes.support',
                'name' => 'Soutenir un createur',
                'icon' => 'bi bi-person-heart',
            ],
        ];
    }
}
