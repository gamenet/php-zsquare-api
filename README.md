# php-zsquare-api (zsquare kss api v1.0)
 
Service allows you to perform the following operations with subscriptions:
- Activation of the subscription for the specified subscriber to the specified parameters (subscription until the specified date / time, or indefinitely subscription
selected product, etc.). Each subscriber should have a unique identifier (SubscriberId) on the side of the service provider.
- Subscription renewal. Allows you to renew the subscription of said subscriber (SubscriberId) before the specified date / time, or indefinitely.
- Unsubscribe. It allows you to unsubscribe for the specified subscriber (SubscriberId) from that date.
- Cancel subscription. It allows you to cancel your subscription for the specified subscriber (SubscriberId) from that date to the provision of a buffer period.
- Suspending subscription. It allows you to suspend a subscription for the specified subscriber (SubscriberId) from that date.
- Renewal of subscription. It allows you to resume a paused service subscription for the specified subscriber (SubscriberId).
- Requesting a list of available products of Kaspersky Lab.

# Installing

The recommended way to install library is [composer](http://getcomposer.org).

```JSON
{
    "require": {
        "gamenet/zsquare": "*"
    }
}
```
# Running tests

Set the zsquare api login and password in bootstrap.php and use phpunit. 

# Usage
 
```php
    $api = new \ZSquare\Api('login', 'password');
    $activationCode = $api->activate('myUser', 'KISS');
```