<?php $f = "1";
if(!empty($_POST['login']))
{
$link = mysql_connect('localhost', 'root', '');
if (!$link) {
  die('Ошибка соединения: ' . mysql_error());
}
$db_selected = mysql_select_db('my_db', $link);
if (!$db_selected) {
   die ('Не удалось выбрать базу: ' . mysql_error());
}
$query = "SELECT * FROM users WHERE name = '".$_POST['login']."' LIMIT 1";
$result = mysql_query($query);
$row = mysql_fetch_assoc($result);
$pass = md5("".$_POST['password']."");
if (mysql_affected_rows() == 0 || $pass != $row['password'])
$f = "0";
else
{
	session_start(); 
	if (!isset($_SESSION['counter'])) $_SESSION['counter']=1;
	$_SESSION['login']=$_POST['login'];
	header("Location: index.php");
}
}
header("Content-Type:text/html; charset=utf-8");
?>	

<!DOCTYPE html>
<html>
<head>
	<title>Авторизация</title>
	<link rel="shortcut icon" href="images/favicon.png">
	<meta charset = "utf-8">
	<script  src="scripts\jquery-2.0.3.min.js"></script>
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
 	<link rel="stylesheet" type="text/css" href="css\style_signup.css">
</head>
<body>   
    <div class="container">       
      <form  action="signup.php" method="POST" name ="login_form" class="form-signin">
        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="text" class="form-control" placeholder="Login" required="" name="login" autofocus="">
        <input type="password" class="form-control" placeholder="Password" name="password"  required="">
        <?php
		if ($f == "0")
		{
		?>
				<p color = "red" id = "login_err"> Неверный логин или пароль</p>
		<?php
		}
			else
			{
		?>
					<p id = "login_err"></p>
		    <?php
			}
		    ?>     
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>
       
   </div>
</body>
</html>


