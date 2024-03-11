<!DOCTYPE html>
<html>
<head>
<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
</style>
</head>


<?php
echo '<h1><span style="color:purple">Financial Transactions<span></h1>';
/*
// Haetaan tiedot lohkoketjusta meidän tietokannasta
include_once '/u/b/e2203229/public_html/harj9/config/db_config.php';

$conn3 = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn3) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql3 = 'SELECT block_index, current_hash FROM blockchain2_table ORDER BY block_index DESC LIMIT 1';
$result3 = $conn3->query($sql3);
echo "ASKEL 1) HAETAAN VIIMEISIMMÄT TIEDOT TIETOKANNASTA<br><br>";

while($row3 = mysqli_fetch_array($result3))
{
    echo "<b>Latest Block Index in BLockchain Table: </b>" . $row3['block_index'] . "<br>";
    $current_block_index = $row3['block_index'];
    echo "<b>Latest Hash in BLockchain Table: </b>" . $row3['current_hash'];
    $current_previous_hash = $row3['current_hash'] . "<br>";
}
$conn3->close();
*/

echo "<br><br>";

include_once '/u/b/e2203229/public_html/harj9/config/db_config.php';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = 'SELECT block_index, current_hash FROM blockchain2_table ORDER BY block_index DESC LIMIT 1';

$result = $conn->query($sql);

$row = mysqli_fetch_array($result, MYSQLI_ASSOC);

if (isset($row['block_index'])) {
    $current_block_index = $row['block_index'];
    // echo "Latest Block Index: " . $current_block_table . "<br>";
    $current_block_index = $current_block_index+1;
    $current_previous_hash = $row['current_hash'];

    // echo "Previous Hash: " . $current_previous_hash . "<br>";
}
else {
  $current_block_index = 0;
  // echo "Latest Block Index: " . $current_block_table . "<br>";
  // echo "Previous Hash: " . "0 (Genesis Block)" . "<br>";
  $current_previous_hash = "0 (Genesis Block)";
}

$conn->close();


echo "ASKEL 2) LISÄÄ BLOCK INDEXIIN +1 JONKA JÄLKEEN BLOCK ON NYT";

//echo $current_block_index;
//echo $current_previous_hash;

echo "<br><br>";

//$current_block_index = $current_block_index+1; // <-- TÄSSÄ LISÄTÄÄN BLOCK_INDEXIIN + 1;

echo "<b>Block Index Now: </b>" . $current_block_index;

echo "<br><br>";

echo "ASKEL 3) SEURAAVAT TIEDOT LISÄTÄÄN TIETOKANTAAN";

echo "<br><br>";

date_default_timezone_set("Europe/Helsinki");

$blockchain_index = $current_block_index;
$timestamp = date("Y-m-d H:i:sa");
$previous_hash = $current_previous_hash;
$sender = $_POST['sender'];
$receiver = $_POST['receiver'];
$amount = $_POST['amount'];
$hashed_data = $blockchain_index . $timestamp . $previous_hash . $sender . $receiver . $amount;
$current_hash = hash("sha256", $hashed_data);

echo "Blockchain Index: " . $blockchain_index . "<br>";
echo "Timestamp: " . $timestamp . "<br>";
echo "Previous hash: " . $previous_hash . "<br>";
echo "Sender: " . $sender . "<br>";
echo "Receiver: " . $receiver . "<br>";
echo "Amount: " . $amount . "<br>";
echo "Data to be hashed: " . $hashed_data . "<br>";
echo "Current hash: " . $current_hash . "<br>";

include_once '/u/b/e2203229/public_html/harj9/db_config.php';

$conn2 = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn2) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql2 = "INSERT INTO blockchain2_table (block_index, timestamp, previous_hash, current_hash, sender, receiver, amount) VALUES ('$blockchain_index','$timestamp','$previous_hash','$current_hash','$sender','$receiver','$amount')";

$result2 = mysqli_query($conn2, $sql2);

if (!$result2) { die("Query failed: " . mysqli_error($conn2)); }

echo '<br><b><span style="color:green">Data inserted succesfully.</span></b><br>';

echo "<br><br>";

include_once '/u/b/e2203229/public_html/harj9/config/db_config.php';

$conn3 = mysqli_connect($servername, $username, $password, $dbname);


if (!$conn3) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql3 = 'SELECT * FROM blockchain2_table ORDER BY block_index ASC';



$result3 = $conn3->query($sql3);

echo '<table id="customers">';
echo "<tr><th>Block Index</th><th>Timestamp</th><th>Previous Hash</th><th>Current Hash</th><th>Sender</th><th>Receiver</th><th>Amount</th></tr>";

while($row = mysqli_fetch_array($result3))
{
    echo "<tr><td>" . $row['block_index'] . "</td>";
    echo "<td>" . $row['timestamp'] . "</td>";
    echo "<td>" . $row['previous_hash'] . "</td>";
    echo "<td>" . $row['current_hash'] . "</td>";
    echo "<td>" . $row['sender'] . "</td>";
    echo "<td>" . $row['receiver'] . "</td>";
    echo "<td>" . $row['amount'] . "</td>";
}

$conn3->close();

echo "</table>";

?>
</html>