<?session_start();
header("Content-Type:text/html; charset=utf-8");
$link = mysql_connect('localhost', 'root', '');
if (!$link) 
{
    die('Ошибка соединения: ' . mysql_error());
}
$db_selected = mysql_select_db('my_db', $link);
if (!$db_selected) {
    die ('Не удалось выбрать базу: ' . mysql_error());
}
 $id = uniqid();
 $url = $_GET['url'];
 $url .= $id;
 $query = "INSERT INTO `my_db`.`posts` (`id`, `title`, `author`, `all_day`, `start`, `end`, `changed`, `body`, `url`) 
 VALUES ('".$id."' , '".$_GET['title']."', '".$_SESSION['login']."', '".$_GET['allDay']."', '".$_GET['start']."',
  '".$_GET['end']."', '".$_GET['changed']."', '".$_GET['body']."', '".$url."');";
echo( $query);  
$result = mysql_query($query);
?>