# Creators Codes

A creator code system for your Azuriom shop: a buyer picks a creator to
support, and every real-money purchase they make afterwards generates a
commission for that creator. No discount for the buyer — just a commission,
tracked and paid from the admin panel, the same way Fortnite's
"Support-A-Creator" works.

## Features

- **Creator codes.** Admins create a code (e.g. `GUIGUI10`), link it to a
  site member and set a commission rate.
- **Persistent support.** A buyer enters a code once, from a dedicated page
  or from their profile. The choice sticks to their account until they
  change or remove it.
- **Automatic commissions.** Every shop order paid with real money by a
  supporting buyer creates a commission (order amount × commission rate).
  Orders paid with site currency are ignored.
- **PayPal payouts.** If a creator has a PayPal e-mail on file, their
  commission is paid out automatically through the PayPal Payouts API as
  soon as it's generated. Otherwise it's left pending for a manual payout.
- **Admin dashboard.** Manage creator codes, and a commissions ledger
  showing the amount owed to each creator, its status, and manual/PayPal
  payout actions.

## Requirements

- Azuriom >= 1.2.0
- Shop plugin >= 1.0.0
- A PayPal Business account with REST API credentials (client ID/secret),
  only if you want automatic payouts

## Installation

1. Extract the plugin into `plugins/creatorcodes`.
2. Run the migrations:
   ```
   php artisan migrate
   ```
3. *(Optional)* To enable automatic PayPal payouts, add your credentials to
   `.env`:
   ```
   CREATORCODES_PAYPAL_MODE=live
   CREATORCODES_PAYPAL_CLIENT_ID=your-client-id
   CREATORCODES_PAYPAL_CLIENT_SECRET=your-client-secret
   ```
   Use `sandbox` instead of `live` while testing, then clear the config
   cache:
   ```
   php artisan config:clear
   ```
   Without these credentials, commissions are simply left as pending and
   can still be marked as paid manually.

## Usage

**Buyers** support a creator from the "Support a creator" link in the user
menu, or from a widget on their profile page. Support is optional and can
be withdrawn at any time; only one creator can be supported at once.

**Admins** manage everything under *Admin > Creators Codes*:
- **Creators Codes**: create, edit, deactivate or delete codes, and set
  each creator's commission rate and PayPal e-mail.
- **Commissions**: review every commission generated, its payout status,
  and trigger a manual "mark as paid" or a PayPal payout.

## Notes

- A commission is only created once, the first time an order's payment
  reaches a completed status — re-saving an already-processed order won't
  duplicate it.
- If a PayPal payout fails, the commission stays unpaid and the error
  returned by PayPal is stored for reference; it can be retried from the
  admin panel.
