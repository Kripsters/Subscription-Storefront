## Made using:

-   [Laravel](https://laravel.com/)
-   [Tailwind CSS](https://tailwindcss.com/)

Admin panel made with: [filamentPHP](https://filamentphp.com/)


## Locally running the project:

1. `composer install`
2. `(make sure the env file is filled out)`
3. `php artisan migrate`
4. `php artisan migrate:fresh -seed`
5. `npm install`
6. `composer run dev`


## Stripe

For local Stripe functionality you will need to install [Stripe CLI](https://docs.stripe.com/stripe-cli/install)

Once installed, login to stripe through the CLI:
`stripe login`

After logging in, listen to the webhook route:
`stripe listen --forward-to http://127.0.0.1:8000/api/stripe/webhook`


## Images and Videos

Images and videos stored within storage folder, move images to public/storage/images and videos to public/storage/videos


## Admin panel/account

Initial Admin account is available after seeding using: </br>

Email: `admin@admin.com`

Password: `Admin123!`

This is modifiable within the seeder. </br>
Any account can be given access to admin panel by modifying the is_admin column. </br>
Warning: An account with admin cannot access the general webpage </br>
I.e. Regular users after login get routed to the general webpage, Admin users after login get routed to admin panel. </br>