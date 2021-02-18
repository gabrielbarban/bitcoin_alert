<?php

include("../service/api_prices_service.php");
$api_prices_service = new api_prices_service();
$last_btc = $api_prices_service->pega_prices();
echo "\n\nO valor atual é de: {$last_btc}";
exit;




?>