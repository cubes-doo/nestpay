<?php
require __DIR__ . '/bootstrap.php';

$oid = isset($_POST['oid']) ? $_POST['oid'] : '';
$amount = isset($_POST['amount']) ? $_POST['amount'] : null;
?>
<form method="post" action="">
OID: <input type="text" name="oid" value="<?php echo htmlspecialchars($oid);?>">
<br>
Amount: <input type="text" name="amount" value="<?php echo htmlspecialchars($amount);?>" palceholder="Leave blank to capture total amount">
<button type="submit">Process</button>
</form>
<hr>
<pre>
<?php
if ($oid) {
$result = $merchantService->postAuthorizationOverNestpayApi($oid, $amount);

print_r($result);
}
?>
</pre>