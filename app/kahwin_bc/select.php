
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Blockchain Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#insert">INSERT TO BLOCKCHAIN</a></li>
  <li><a data-toggle="tab" href="#read">READ FROM BLOCKCHAIN</a></li>
</ul>

<div class="tab-content">
  <div id="insert" class="tab-pane fade in active">
   
    <p>
	<div class="container">
<?php
include 'db.php';
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM rekod_perkahwinan where tarikh_transaksi_bc = '0000-00-00 00:00:00'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  echo "<br><br><div>Senarai Rekod Perkahwinan Untuk Disimpan di Blockchain</div><br><br>";
  echo "<table class='table table-striped'><tr>";
  echo "<td>ID</td>";
  echo "<td>Status</td>";
  echo "<td>No Sijil</td>";
  echo "<td>No KP Individu</td>";
  echo "<td>Nama Individu</td>";
  echo "<td>No KP Pasangan</td>";
  echo "<td>Nama Pasangan</td>";
  echo "<td>Tarikh Status</td>";
  echo "<td>Action</td>";
  echo "</tr>";
  while($row = $result->fetch_assoc()) {
	echo"<tr>";
    echo "<td>" . $row["id"]. " </td>";
	echo "<td>" . $row["status_perkahwinan"]. " </td>";
	echo "<td>" . $row["no_sijil"]. " </td>";
	echo "<td>" . $row["no_kp_passport_individu_lelaki"]. " </td>";
	echo "<td>" . $row["nama_individu_lelaki"]. " </td>";
	echo "<td>" . $row["no_kp_passport_perempuan"]. " </td>";
	echo "<td>" . $row["nama_pasangan_perempuan"]. "</td>";
	echo "<td>" . $row["tarikh_status"]. "</td>";
	if ($row["tarikh_transaksi_bc"] == "0000-00-00 00:00:00")
	echo "<td><a id='link_desc".$row["id"]."' onclick=\"setMarriageInfo('".$row["id"]."','".$row["no_kp_passport_individu_lelaki"]."','".$row["nama_individu_lelaki"]."','".$row["no_kp_passport_perempuan"]."','".$row["nama_pasangan_perempuan"]."','".$row["status_perkahwinan"]."','".$row["tarikh_status"]."')\">Copy to blockchain</a></td>";
	echo"</tr>";
 
}

echo"</table>";
} else {
  echo "0 results";
}



$sql = "SELECT * FROM rekod_perkahwinan where tarikh_transaksi_bc <> '0000-00-00 00:00:00'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  echo "<br><br><div>Senarai Rekod Perkahwinan telah berjaya disimpan di Blockchain</div><br><br>";
  echo "<table class='table table-striped'><tr>";
  echo "<td>ID</td>";
  echo "<td>Status</td>";
  echo "<td>No Sijil</td>";
  echo "<td>No KP Individu</td>";
  echo "<td>Nama Individu</td>";
  echo "<td>No KP Pasangan</td>";
  echo "<td>Nama Pasangan</td>";
  echo "<td>Tarikh Status</td>";
  echo "<td>Tarikh Simpan di Blockchain</td>";
  echo "<td>Blockchain Transaction Hash</td>";
  echo "<td>Waiting write time (sec) </td>";
  echo "</tr>";
  while($row = $result->fetch_assoc()) {
	echo"<tr>";
    echo "<td>" . $row["id"]. " </td>";
	echo "<td>" . $row["status_perkahwinan"]. " </td>";
	echo "<td>" . $row["no_sijil"]. " </td>";
	echo "<td>" . $row["no_kp_passport_individu_lelaki"]. " </td>";
	echo "<td>" . $row["nama_individu_lelaki"]. " </td>";
	echo "<td>" . $row["no_kp_passport_perempuan"]. " </td>";
	echo "<td>" . $row["nama_pasangan_perempuan"]. "</td>";
	echo "<td>" . $row["tarikh_status"]. "</td>";
	echo "<td>" . $row["tarikh_transaksi_bc"]. "</td>";
	echo "<td><a  target='_blank' href='https://goerli.etherscan.io/tx/".$row["transactionhash"]."'>" . $row["transactionhash"]. "<a/></td>";
	$wait  = $row["waiting_time"]/1000;
	echo "<td>" . $wait. "</td>";
	echo"</tr>";
 
}

echo"</table>";
} else {
  echo "0 results";
}


$conn->close();
?>
 <p id='MarriageInfos'></p>
</div>
	
	
	
	</p>
  </div>
  <div id="read" class="tab-pane fade">
   
    <p>Some content in menu 1.</p>
  </div>
</div>





	<script src="https://cdn.jsdelivr.net/gh/ethereum/web3.js@1.0.0-beta.35/dist/web3.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script>
        var contract;

        $(document).ready(function () {
            window.addEventListener('load', function () {
                // Check if Web3 has been injected by the browser:
                if (typeof web3 !== 'undefined') {
                    // You have a web3 browser! Continue below!
                    startApp(web3);
                    //alert("Web3");
                } else {
                    //alert("No hay web3");
                    // Warn the user that they need to get a web3 browser
                    // Or install MetaMask, maybe with a nice graphic.
                }
            })

            web3 = new Web3(web3.currentProvider);

            var address = "0x331083fe8f91E5f8060BE47A79ECfE146ea3D637";

            var abi = [
	{
		"constant": false,
		"inputs": [
			{
				"internalType": "uint256",
				"name": "_Ic",
				"type": "uint256"
			},
			{
				"internalType": "string",
				"name": "_IndividualName",
				"type": "string"
			},
			{
				"internalType": "uint256",
				"name": "_spouseIc",
				"type": "uint256"
			},
			{
				"internalType": "string",
				"name": "_SpouseName",
				"type": "string"
			},
			{
				"internalType": "string",
				"name": "_status",
				"type": "string"
			},
			{
				"internalType": "string",
				"name": "_statusDate",
				"type": "string"
			}
		],
		"name": "setMarriageInfo",
		"outputs": [],
		"payable": false,
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [
			{
				"internalType": "uint256",
				"name": "_Ic",
				"type": "uint256"
			}
		],
		"name": "getMarriageInfos",
		"outputs": [
			{
				"internalType": "string",
				"name": "",
				"type": "string"
			},
			{
				"internalType": "uint256",
				"name": "",
				"type": "uint256"
			},
			{
				"internalType": "string",
				"name": "",
				"type": "string"
			},
			{
				"internalType": "string",
				"name": "",
				"type": "string"
			},
			{
				"internalType": "string",
				"name": "",
				"type": "string"
			},
			{
				"internalType": "string",
				"name": "",
				"type": "string"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	},
	{
		"constant": true,
		"inputs": [
			{
				"internalType": "uint256",
				"name": "",
				"type": "uint256"
			}
		],
		"name": "marriageInfos",
		"outputs": [
			{
				"internalType": "uint256",
				"name": "Ic",
				"type": "uint256"
			},
			{
				"internalType": "string",
				"name": "IndividualName",
				"type": "string"
			},
			{
				"internalType": "uint256",
				"name": "spouseIc",
				"type": "uint256"
			},
			{
				"internalType": "string",
				"name": "spouseName",
				"type": "string"
			},
			{
				"internalType": "string",
				"name": "status",
				"type": "string"
			},
			{
				"internalType": "string",
				"name": "statusDate",
				"type": "string"
			}
		],
		"payable": false,
		"stateMutability": "view",
		"type": "function"
	}
];
            contract = new web3.eth.Contract(abi, address);
			contract.methods.getMarriageInfos(770909109899).call().then(console.log);
          // var marriageInfos = contract.methods.getMarriageInfos(770909109899)	;
		   //alert (marriageInfos[0]);
          //  contract.methods.getMarriageInfos(770909109899).call().then(function (marriageInfos) {$('#MarriageInfos').html(marriageInfos);})

        })

		function setMarriageInfo(id,ic,individualName,spouseIc,spouseName,status,statusDate) {
           var startTime = Date.now();       
            web3.eth.getAccounts().then(function (accounts)
            {
                var acc = accounts[0];
                
				ethereum.enable();				
				 return contract.methods.setMarriageInfo(ic,individualName,spouseIc,spouseName,status,statusDate).send({ from: acc });
				/*check bc hash*/
            }).then(function (tx)
            {	
				var endTime = Date.now();
				var waitingTime = endTime - startTime;
				console.log('wait = '+waitingTime);
                console.log(tx);
					
				transactionHash = tx.transactionHash;
				
				if(id!="" && transactionHash!=""){
					$.ajax({
						url: "insert_bcstatus.php",
						type: "POST",
						data: {
							id: id,
							transactionHash: transactionHash,
							waitingTime: waitingTime
						},
						cache: false,
						success: function(dataResult){
							var dataResult = JSON.parse(dataResult);
							if(dataResult.statusCode==200){
								document.getElementById('link_desc'+id).style.display = 'none';								
							}
							else if(dataResult.statusCode==201){
							   alert("Error occured !");
							}
							
						}
					});
				}
				else{
					alert('Please fill all the field !');
				}
		
				
            }).catch(function(tx)
            {
				/*error log*/
                console.log('Error laaaaa'+tx);
				
            })
        }
		
			

		 $('#savemarriageinfo').click(function () {
            var amt = 0;
            amt = parseInt($('#amount').val());
           

            web3.eth.getAccounts().then(function (accounts)
            {
                var acc = accounts[0];
                ethereum.enable();
                return contract.methods.deposit(amt).send({ from: acc });
				/*check bc hash*/
                location.reload();
            }).then(function (tx)
            {
                console.log(tx);
            }).catch(function(tx)
            {
                console.log(tx);
            })
        })
		
        $('#deposit').click(function () {
            var amt = 0;
            amt = parseInt($('#amount').val());
           

            web3.eth.getAccounts().then(function (accounts)
            {
                var acc = accounts[0];
                ethereum.enable();
                return contract.methods.deposit(amt).send({ from: acc });
				/*check bc hash*/
                location.reload();
            }).then(function (tx)
            {
                console.log(tx);
            }).catch(function(tx)
            {
                console.log(tx);
            })
        })

        $('#withdraw').click(function () {
            var amt = 0;
            amt = parseInt($('#amount').val());


            web3.eth.getAccounts().then(function (accounts) {
                var acc = accounts[0];
                ethereum.enable();
                return contract.methods.withdraw(amt).send({ from: acc });
                location.reload();
            }).then(function (tx) {
                console.log(tx);
            }).catch(function (tx) {
                console.log(tx);
            })
        })
 </script>
</body>
</html>
