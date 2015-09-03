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

  $query = "SELECT * FROM posts WHERE  author = '".$_SESSION['login']."'";
  $result = mysql_query($query);
  while ($row = mysql_fetch_assoc($result))
  {
    if($row['all_day'] == "true"){$bol = true;}
    else{$bol = false;}
    $results[] = array( "id" => $row['id'], "title" => $row['title'], "author" => $row['author'],"allDay" => $bol,
  "start" => $row['start'],"end" => $row['end'],"changed" => $row['changed'],"body" => $row['body'],"url" => $row['url']);
  }
  echo json_encode($results);
  mysql_free_result($result);
?>