## Made using:

-   [Laravel](https://laravel.com/)
-   [Tailwind CSS](https://tailwindcss.com/)

Admin panel made with: [filamentPHP](https://filamentphp.com/)


## Locally running the project:
`composer install`
`(make sure the env file is filled out)`
`php artisan migrate`
`php artisan migrate:fresh -seed`
`npm install`
`composer run dev`


## Stripe

For local Stripe functionality you will need to install Stripe CLI
Install @ https://docs.stripe.com/stripe-cli/install

Once installed, login to stripe through the CLI:
`stripe login`

After logging in, listen to the webhook route:
`stripe listen --forward-to http://127.0.0.1:8000/api/stripe/webhook`


## Admin panel/account

Initial Admin account is available after seeding using:
Email: `admin@admin.com`
Password: `Admin123!`
This is modifiable within the seeder.
Any account can be given access to admin panel by modifying the is_admin column.
Warning: An account with admin cannot access the general webpage
I.e. Regular users after login get routed to the general webpage, Admin users after login get routed to admin panel.