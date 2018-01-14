<?php
    $url="https://api.coinmarketcap.com/v1/ticker/ethereum/";
    $file=file_get_contents($url);
    $json=json_decode($file);
    var_dump($json);
    echo "USD price per 1 ETH: ".$json[0]->price_usd;
?>