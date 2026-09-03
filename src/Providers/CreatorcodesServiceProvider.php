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

        // Enregistrement protege : si jamais cette ligne echoue pour une
        // raison ou une autre, on logue l'erreur au lieu de faire planter
        // tout le site (une erreur ici, en phase boot(), n'est PAS rattrapee
        // automatiquement par Azuriom contrairement aux erreurs de register()).
        try {
            Payment::saved(function (Payment $payment) {
                if ($payment->wasChanged('status')) {
                    app(CommissionService::class)->handle($payment);
                }
            });
        } catch (Throwable $e) {
            report($e);
        }

        // Injecte le createur actuellement soutenu dans les vues du panier
        // et de selection des offres (surchargees a la racine du site, pas
        // dans ce plugin : resources/views/vendor/shop/cart/index.blade.php
        // et resources/views/vendor/shop/offers/select.blade.php).
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

        // Ajoute la carte "code createur" sur la page de profil general
        // (mecanisme officiel prevu par Azuriom pour ca, pas de surcharge
        // de vue necessaire ici).
        try {
            View::composer('profile.index', CreatorProfileCardComposer::class);
        } catch (Throwable $e) {
            report($e);
        }
    }

    /**
     * Menu admin, a part (regroupe avec Shop demanderait de modifier
     * le plugin Shop lui-meme, ce qui casserait a sa prochaine mise a jour).
     */
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

    /**
     * Lien cote joueur, dans le menu utilisateur, vers la page pour
     * choisir/changer le createur soutenu.
     */
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
