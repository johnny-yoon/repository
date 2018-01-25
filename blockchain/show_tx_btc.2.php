<?
    $user_id=get_current_user_id();
    global $wpdb;
    //// btc_address <---- 수정요
    $coin_address = $wpdb->get_var("SELECT btc_address FROM wx_users WHERE ID = ".$user_id);
    if(empty($coin_address)){
        echo "ERROR: There is no waller address.";
    }
    else{
        $ch = curl_init();
        $url="https://blockchain.info/ko/rawaddr/".$coin_address;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $json=json_decode($output);
        //var_dump($json);
        $address=$json->address;
        $total_received=$json->total_received;
        $final_balance=$json->final_balance;
        $n_tx=$json->n_tx;
        if($n_tx!=0)   {


        // $tx_index=$json->txs[0]->tx_index;
        // $time=$json->txs[0]->time;
        // $value=$json->txs[0]->out[0]->value;
        //발란스 업데이트
        $sql="UPDATE wx_users SET btc_balance = '".$final_balance."' WHERE ID = ".$user_id;
        $wpdb->query($sql);
        //Deposits Table 업데이트

        // $sql="SELECT tx_index FROM wx_deposits WHERE tx_index = ".$json->txs[$j]->tx_index;
        // $results = $wpdb->get_results($sql) or die(mysql_error());
        // if (count($results) > 0) {
        //     $display_row = null;
        //     foreach ($results as $res) {
        //          echo "id - " . $res->id;
        //     }
        // }
        $j=0;
        while($json->txs[$j]->tx_index) {
            $sql="SELECT tx_index FROM wx_deposits WHERE tx_index = ".$json->txs[$j]->tx_index;
            if($wpdb->get_row($sql)>0){ //이미 레코드가 있으면
                //echo "이미 레코드가 있으면";
                break;
            }
            else{
                //Deposits Table Insert
                //echo "레코드 없음<br>";
                $url_exchange="http://api.manana.kr/exchange/rate.json.js?base=KRW&code=USD";
                $url_bit_price="http://crix-api-endpoint.upbit.com/v1/crix/candles/minutes/1?code=CRIX.UPBIT.KRW-BTC";
                $url_eth_price="http://crix-api-endpoint.upbit.com/v1/crix/candles/minutes/1?code=CRIX.UPBIT.KRW-ETH";
                $url_wallet_address="https://block.io/api/v2/get_new_address/?api_key=1a6d-f543-277c-4871";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                //--------------------
                curl_setopt($ch, CURLOPT_URL, $url_exchange);
                $output = curl_exec($ch);
                $output = substr ($output, 14, strlen($output)-16); //parseResponse(
                $json_ex=json_decode($output);
                $exchagne_rate=$json_ex->data[0]->rate;
                //--------------------
                curl_setopt($ch, CURLOPT_URL, $url_bit_price);
                $output = curl_exec($ch);
                $json_btc=json_decode($output);
                $btc_price_in_krw=$json_btc[0]->tradePrice;
                //--------------------
                curl_close($ch);
                /////////////////////////////////////////////
                $current_time=date('Y-m-d H:i:s');
                echo $current_time;
                if($current_time>strtotime("16 January 2018") && $current_time<=strtotime("31 January 2018")) $wiix_coin_price_in_usd=0.06;
                else if($current_time>strtotime("1 February 2018") && $current_time<=strtotime("15 February 2018")) $wiix_coin_price_in_usd=0.07;
                else if($current_time>strtotime("16 February 2018") && $current_time<=strtotime("28 February 2018")) $wiix_coin_price_in_usd=0.08;
                else if($current_time>strtotime("1 March 2018") && $current_time<=strtotime("15 March 2018")) $wiix_coin_price_in_usd=0.09;
                else if($current_time>strtotime("16 March 2018") && $current_time<=strtotime("31 March 2018")) $wiix_coin_price_in_usd=0.010;
                else if($current_time>strtotime("1 April 2018") && $current_time<=strtotime("15 April 2018")) $wiix_coin_price_in_usd=0.11;
                else  $wiix_coin_price_in_usd=1000;
                /////////////////////////////////////////////
                $wiixcoin_amount=intval($json->txs[$j]->out[0]->value)*0.00000001*intval($btc_price_in_krw)/intval($exchagne_rate)/floatval($wiix_coin_price_in_usd);
                $sql="INSERT INTO wx_deposits (user_id, deposit_cryptocoin, tx_index, tx_time, tx_value, cryptocoin_price_in_krw, krw_usd_exchange_rate, wiixcoin_price_in_usd, wiixcoin_amount) VALUES (".intval($user_id).",'btc',".intval($json->txs[$j]->tx_index).",'".date('Y-m-d H:i:s',$json->txs[$j]->time)."',".intval($json->txs[$j]->out[0]->value).",".intval($btc_price_in_krw).",".intval($exchagne_rate).",".floatval($wiix_coin_price_in_usd).",".$wiixcoin_amount.")";
                echo $sql;
                $wpdb->query($sql);
                echo "<br>------------------------------".$j;
                $j++;
            }
        }

        echo "<br>Address: ".$address;
        echo "<br>Total Number of Transaction: ".$n_tx;
        echo "<br>Total Received: ".$total_received;
        echo "<br>Final Balance: ".$final_balance;
        //echo "<br>Current Time: ".$current_time;
        echo '<br><br>';
        echo '<table class="table table-hover table-responsive">';
        echo '<tr>';
        echo '<th>Transaction ID</th>';
        echo '<th>Date & Time</th>';
        echo '<th>Value (Unit: Satoshi)</th>';
        echo '</tr>';
        for ($i = 0 ; $i < $n_tx; $i++) {
            echo '<tr>';
            echo '<td>'.$json->txs[$i]->tx_index.'</td>';
            echo '<td>'.date('Y-m-d H:i:s', $json->txs[$i]->time).'</td>';
            echo '<td>'.$json->txs[$i]->out[0]->value.'</td>';
            //echo '<td><a type="button" class="btn btn-danger" href="del.php?id='.$row["musician_id"].'">Delete</a></td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '<br><br>';
    }
    else{
        echo "<br>There is no transaction";
    }
    }
