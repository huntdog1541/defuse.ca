<?php
require_once('inc/db.inc.php');
require_once('inc/security.inc.php');
require_once('inc/stubshare.inc.php');

$user = Security::GetCurrentUser();

if($user === false)
{
	header('Location: login.php');
	die();
}
?>
<html>
<head>
	<title>StubShare User Account Page</title>
</head>
<body>
<a href="userhome.php">Account Home</a> | <a href="products.php">Sell with StubShare</a>
<h1>Sell with StubShare</h1>
Note: This page will only be of interest to people who are selling their <b>own</b> products with StubShare.
<h2>Your Products</h2>
<table border=1 >
<tr><th>Name</th><th>Description</th><th>Price</th><th>Owner Cut</th><th>Sharer Cut</th><th>Buyer's Charity Cut</th><th>StubShare Cut</th><th>Sale Count</th><th>Get Stub Code</th></tr>
<?php //Fill in Product table
$userID = StubShare::GetUserID($user);
$q = mysql_query("SELECT * FROM products WHERE owner='$userID'");
if($q && mysql_num_rows($q) > 0)
{
	while($prod_info = mysql_fetch_array($q))
	{
		$name = htmlspecialchars($prod_info['name'], ENT_QUOTES);
		$desc = htmlspecialchars($prod_info['description'], ENT_QUOTES);
		$price = (double)$prod_info['price'];
		
		$owner_pct = (double)$prod_info['owner_pct'];
		$sharer_pct = (double)$prod_info['sharer_pct'];
		$charity_pct = (double)$prod_info['charity_pct'];
		$stubshare_pct = (double)$prod_info['stubshare_pct'];

		$owner_cut = $price * $owner_pct / 100.0;
		$sharer_cut = $price * $sharer_pct / 100.0;
		$charity_cut = $price * $charity_pct / 100.0;
		$stubshare_cut = $price * $stubshare_pct / 100.0;	

		$sales = htmlspecialchars($prod_info['sales'], ENT_QUOTES);
		$id = (int)($prod_info['id']);
		echo "<tr><td>$name</td><td>$desc</td><td>$price</td><td>$owner_cut</td><td>$sharer_cut</td><td>$charity_cut</td><td>$stubshare_cut</td><td>$sales</td><td>[link to get stub codes]</td>";
		echo "</tr>";
	}
}
?>
</table>
<a href="addproduct.php">Add Product...</a>
</body>
</html>
