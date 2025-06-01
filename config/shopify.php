<?php

return [

/*
|--------------------------------------------------------------------------
| Shopify API Credentials
|--------------------------------------------------------------------------
|
| These values are required for authenticating with the Shopify API.
|
*/

'api_url' => env('SHOPIFY_API_URL', 'https://your-shopify-store.myshopify.com'),
'api_key' => env('SHOPIFY_API_KEY', 'your-api-key'),
'api_password' => env('SHOPIFY_API_PASSWORD', 'your-api-password'),

/*
|--------------------------------------------------------------------------
| Shopify Access Token
|--------------------------------------------------------------------------
|
| This access token is required for making authenticated requests to the Shopify API.
|
*/

'access_token' => env('SHOPIFY_ACCESS_TOKEN', 'your-access-token'),

];
