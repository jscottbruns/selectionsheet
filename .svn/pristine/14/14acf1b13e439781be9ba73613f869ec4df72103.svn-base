<?php
include ('../include/db_vars.inc');

$secret_hash_padding = 'A string that is used to pad out short strings for a certain type of encryption';

if(isset($go)){
$password = md5($_POST['pass'].$secret_hash_padding);
echo $password;
}
?>
<br><br>Create UserName ID Hash<br><br>
<form action="<?php echo $PHP_SELF ?>" method="post">
<input type="text" name="pass">
<input type="submit" name="go">
</form> 
