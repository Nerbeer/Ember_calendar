<?
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

$query = "DELETE FROM `my_db`.`posts` WHERE `posts`.`id` = '".$_GET['id']."';";
echo( $query);  
$result = mysql_query($query);
  
?>
