<?php
    $url="https://api.coindesk.com/v1/bpi/currentprice.json";
    $file=file_get_contents($url);
    $json=json_decode($file);
    var_dump($json);
    echo "BTC/USD: ".$json->bpi->USD->rate;
?>
<html lang="en">
<body>
    BTC amount: <input type="text" id="btc" onChange="btcToUSD();" onKeyup="btcToUSD();" ><br>
    USD value: <input type="text" id="usd" onChange="usdToBTC();" onKeyup="usdToBTC();" >
    <script>
        var btc_value = "<?php echo $json->bpi->USD->rate; ?>";
        var btc_value = Number(btc_value.replace(/[^0-9\.-]+/g,""));
        function btcToUSD(){
            var btc = document.getElementById("btc").value;
            var usd_value = btc * btc_value;
            var usd_value = usd_value.toFixed(2);
            document.getElementById("usd").value=usd_value;
        };
        function usdToBTC(){
            var usd = document.getElementById("usd").value;
            var btc_amount = usd / btc_value;
            var btc_amount = btc_amount.toFixed(8);
            document.getElementById("btc").value=btc_amount;
        };
    </script>
</body>
</html>