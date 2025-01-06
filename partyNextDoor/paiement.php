<?php
require 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51QeE1lL2cXgM5tGyuAHIt4yOuYBjlZ7I9MMOe7qTRPZzyNUs7JJz6UXul9dET7Jmh9wEmY9vfYzrGyRXTVsqz1yt00PS5XsOIv');

header('Content-Type: application/json');

$YOUR_DOMAIN = 'localhost';

$checkout_session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'eur',
            'product_data' => [
                'name' => 'Événement',
            ],
            'unit_amount' => 2000, // Montant en centimes (20,00 €)
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => $YOUR_DOMAIN . '/fiche-evenement.php',
    'cancel_url' => $YOUR_DOMAIN . '/paiement.html',
]);

echo json_encode(['id' => $checkout_session->id]);
?>














<!DOCTYPE HTML>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/page-event.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">  
    <link rel="stylesheet" href="css/header.css">
    <title>Paiement</title>
/* clé publique/*
<body>
    <h1>Paiement pour Événements</h1>
    <script>
        var stripe = Stripe('pk_test_51QeE1lL2cXgM5tGy6Ln2R6a0wmhKlVkNxwJa55WHlFb9sfvXY2mQlKR0foLJBcuG3LdZEeaoqCpT0WCAKdTmUOV900Y7UBzpo6');

        document.getElementById('checkout-button').addEventListener('click', function () {
            fetch('/create-checkout-session.php', {
                method: 'POST',
            })
            .then(function (response) {
                return response.json();
            })
            .then(function (sessionId) {
                return stripe.redirectToCheckout({ sessionId: sessionId });
            })
            .then(function (result) {
                if (result.error) {
                    alert(result.error.message);
                }
            })
            .catch(function (error) {
                console.error('Error:', error);
            });
        });
    </script>

    /*clé sécurisée/*
</body>
</html>









































