<?
    $user_id=get_current_user_id();
    //echo "Your ID: ".get_current_user_id();
    global $wpdb;
    $user_email = $wpdb->get_var("SELECT user_login FROM wx_users WHERE ID = ".$user_id);
    $btc_address = $wpdb->get_var("SELECT btc_address FROM wx_users WHERE ID = ".$user_id);
    $eth_address = $wpdb->get_var("SELECT eth_address FROM wx_users WHERE ID = ".$user_id);
    //echo "Your E-Mail: ".$user_email;
    /////////////////////////////////////////////////////////////////////////////////////

    $url_exchange="http://api.manana.kr/exchange/rate.json.js?base=KRW&code=USD";
    $url_bit_price="http://crix-api-endpoint.upbit.com/v1/crix/candles/minutes/1?code=CRIX.UPBIT.KRW-BTC";
    $url_eth_price="http://crix-api-endpoint.upbit.com/v1/crix/candles/minutes/1?code=CRIX.UPBIT.KRW-ETH";
    $url_wallet_address="https://block.io/api/v2/get_new_address/?api_key=1a6d-f543-277c-4871";

    if(empty($btc_address)){
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_URL, $url_wallet_address); 
        $output = curl_exec($ch); 
        $json=json_decode($output);
        $btc_address=$json->data->address;
        curl_close($ch);

        $query="UPDATE wx_users SET btc_address = '".$btc_address."' WHERE ID = ".$user_id;
        $wpdb->query($query);
    }
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    //--------------------
    curl_setopt($ch, CURLOPT_URL, $url_exchange); 
    $output = curl_exec($ch);
    $output = substr ($output, 14, strlen($output)-16); //parseResponse(
        //$output='{"meta":{"Access-Control-Allow-Origin":"*","Access-Control-Allow-Methods":"POST, GET, OPTIONS, PUT, PATCH, DELETE","Access-Control-Max-Age":"1000","Access-Control-Allow-Headers":"*","Pragma":"no-cache","X-Auth-Status":"true","X-RateLimit-Limit":"5000 per hour","X-RateLimit-Remaining":"4997","Cache-Control":"no-cache, must-revalidate","Expires":"0","X-Powered-By":"Luracast Restler v3.0.0rc6","Content-Type":"text\/javascript; charset=utf-8","Content-Language":"en-US"},"data":[{"date":"2018-01-23 16:00:00","name":"USDKRW=X","rate":1070.469971}]}';
    //var_dump($output);
    $json=json_decode($output);
    $exchagne_rate=$json->data[0]->rate;
    //--------------------
    curl_setopt($ch, CURLOPT_URL, $url_bit_price); 
    $output = curl_exec($ch); 
    $json=json_decode($output);
    $btc_price_in_krw=$json[0]->tradePrice;
    curl_close($ch);
    ////////////////////
    $current_time=time();
    //echo
    $wiix_coin_price_in_usd;
    if($current_time>strtotime("16 January 2018") && $current_time<=strtotime("31 January 2018")) $wiix_coin_price_in_usd=0.06;
    else if($current_time>strtotime("1 February 2018") && $current_time<=strtotime("15 February 2018")) $wiix_coin_price_in_usd=0.06;
    else if($current_time>strtotime("16 February 2018") && $current_time<=strtotime("28 February 2018")) $wiix_coin_price_in_usd=0.07;
    else if($current_time>strtotime("1 March 2018") && $current_time<=strtotime("15 March 2018")) $wiix_coin_price_in_usd=0.08;
    else if($current_time>strtotime("16 March 2018") && $current_time<=strtotime("31 March 2018")) $wiix_coin_price_in_usd=0.09;
    else if($current_time>strtotime("1 April 2018") && $current_time<=strtotime("15 April 2018")) $wiix_coin_price_in_usd=0.10;
    else if($current_time>strtotime("16 April 2018") && $current_time<=strtotime("30 April 2018")) $wiix_coin_price_in_usd=0.11;   
    else  $wiix_coin_price_in_usd=1000;

    echo "Please send your BTC to this address: <br><b>".$btc_address."</b>";
    echo "<br><br>Current Price of 1 BTC (from UpBit.com): ".$btc_price_in_krw." KRW";
    echo "<br>Current Exchange rate (KRW/USD): ".$exchagne_rate;
    echo "<br>Current WIIX COIN Price in USD: ".$wiix_coin_price_in_usd." USD";
    echo "<br>Current WIIX COIN Amount per 1 BTC: ".intval($btc_price_in_krw/$exchagne_rate/$wiix_coin_price_in_usd)." WIIX";

    $_SESSION["btc_address"]=$btc_address;

