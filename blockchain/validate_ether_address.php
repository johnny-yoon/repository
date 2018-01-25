<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    Enter Ethereum address: <input type="text" id="address" width="300"><br>
    <button onclick="Validate()">Check validity</button>
</body>
<script src="https://cdn.rawgit.com/ethereum/web3.js/develop/dist/web3.js"></script>
<script>
window.addEventListener('load', function() {
  // Checking if Web3 has been injected by the browser (Mist/MetaMask)
  if (typeof web3 !== 'undefined') {
    // Use Mist/MetaMask's provider
    window.web3 = new Web3(web3.currentProvider);
  } else {
    console.log('No web3? You should consider trying MetaMask!')
    // fallback - use your fallback strategy (local node / hosted node + in-dapp id mgmt / fail)
    window.web3 = new Web3(new Web3.providers.HttpProvider("http://localhost:8545"));
  }
  // Now you can start your app & access web3 freely:
});

function Validate() {
    var address = document.getElementById("address").value;
    var result = web3.utils.isAddress('0xc1912fee45d61c87cc5ea59dae31190fffff232d');
  alert(result);
}
</script>
</html>