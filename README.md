# Plugin Creator Codes pour Azuriom

Systeme de codes createur pour la boutique Azuriom (plugin Shop) :
un acheteur choisit une fois un createur a soutenir, et chacun de ses
achats en boutique genere une commission pour ce createur.
Pas de reduction pour l'acheteur : uniquement une commission, visible
et geree cote admin.

## Installation recommandee

1. Sur ton serveur, genere le squelette officiel du plugin pour etre
   certain d'avoir les bonnes classes de base pour TA version d'Azuriom :

   ```
   php artisan plugin:create creatorcodes
   ```

2. Copie/fusionne les fichiers de cette archive dans
   `plugins/creatorcodes/`, en gardant le `plugin.json` et le
   `ServiceProvider` generes par la commande (ajoute juste la
   dependance `"shop": "^1.0.0"` dans `dependencies`, et ajoute le
   contenu de `boot()` fourni ici dans le provider genere).

3. Lance les migrations :

   ```
   php artisan migrate
   ```

4. Ajoute un lien vers `route('creatorcodes.support')` quelque part
   sur le site (menu utilisateur, page profil...) pour que les
   acheteurs puissent choisir leur createur. Idem cote admin pour
   `route('creatorcodes.admin.index')`.

## Points a verifier avant la mise en prod

Je n'ai pas d'acces direct a ton installation Azuriom, donc 4 details
internes au plugin Shop sont a confirmer rapidement (5 minutes) avant
de considerer que c'est fini :

1. **Le hook de commande payee** (`src/Providers/CreatorcodesServiceProvider.php`)
   ecoute `Order::saved()`. Verifie que le champ s'appelle bien
   `status`, et surtout **la valeur exacte** qui signifie "commande
   payee" (`src/Services/CommissionService.php`, propriete
   `$paidStatuses`). Le plus simple :
   ```
   php artisan tinker
   >>> \Azuriom\Plugin\Shop\Models\Order::latest()->first()->toArray()
   ```
   sur une commande que tu sais payee, et regarde les valeurs de
   `status` et du champ montant (`total` par defaut dans mon code).

2. **La classe controleur de base** : j'ai suppose
   `Azuriom\Http\Controllers\Controller`. Verifie dans un controleur
   existant d'un autre plugin (ex. `plugins/shop/src/Http/Controllers/`).

3. **Le layout du site** dans `resources/views/support.blade.php`
   (`@extends('layouts.app')`) : remplace par le `@extends(...)` reel,
   visible en ouvrant n'importe quelle vue frontend d'un autre plugin.

4. **Le layout admin** dans les 4 vues de `resources/views/admin/` :
   copie la ligne `@extends(...)` d'une vue admin existante (par
   exemple dans `plugins/shop/resources/views/admin/`).

Tout le reste (migrations, modeles, routes, controleurs, formulaires)
est fonctionnel tel quel.

## Structure

```
creatorcodes/
  plugin.json
  database/migrations/       3 tables : codes, supports, commissions
  src/Models/                CreatorCode, CreatorSupport, CreatorCommission
  src/Services/               CommissionService (calcul + log de la commission)
  src/Providers/              ServiceProvider (hook sur les commandes)
  src/Http/Controllers/       SupportController (front) + Admin/*
  routes/web.php              route front : creatorcodes.support(.update/.destroy)
  routes/admin.php            routes admin : CRUD codes + journal des commissions
  resources/views/            vue front + 4 vues admin
```

## Fonctionnement

- Un admin cree un code (`GUIGUI10`), l'associe a un utilisateur
  (le createur) et definit un taux de commission (ex. 5%).
- Un acheteur va sur `/creatorcodes` (ou l'URL choisie), saisit le
  code : ce choix est **persistant**, comme le Support-a-Creator de
  Fortnite. Il peut le changer ou le retirer a tout moment.
- A chaque commande qui passe en statut paye, le plugin verifie si
  l'acheteur soutient un createur actif, et enregistre une ligne de
  commission (montant commande x taux).
- L'admin consulte `/admin/creatorcodes/commissions` pour voir le
  total du a chaque createur et marquer les commissions comme payees
  une fois le virement fait manuellement.
