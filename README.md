# canadapost-api
candapost php api

sample get rates:
```php
<?php
$canadapost = new \Canadapost\Canadapost(array(
    'username'        => 'your_username',
    'password'        => 'your_password',
    'customer_number' => 'your_customer_number',
    'env'             => 'development'
));
$response = $canadapost->getRates(array(
    'from_zip' => 'K2B8J6',
    'to_zip'   => 'J0E1X0',
    'package'  => 1,
    'weight'   => 1
));
print_r($response);
```
