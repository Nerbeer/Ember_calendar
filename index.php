<?session_start();
if ($_SESSION['counter'] != 1) {
header("Location: signup.php");
exit;
}
header("Content-Type:text/html; charset=utf-8");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Ember Calendar</title>
<link rel="shortcut icon" href="images/favicon.png">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="css/bootstrap-switch.css">
  <link rel='stylesheet' href='css/fullcalendar.css'  >
  <link rel='stylesheet' href='css/datepicker.css'  >
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
  <link rel="stylesheet"  media="all" href="css/daterangepicker-bs2.css" />
  <script src="js/libs/jquery-1.10.2.js"></script>
  <script src='js/libs/jquery-ui.custom.min.js'></script>
  <script src='js/libs/bootstrap-switch.js'></script>

</head>
<body>

  <script type="text/x-handlebars">
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Ember Calendar</a>
          <ul class="nav navbar-nav pull-left">
            <li>{{#link-to 'posts'}}<span class="glyphicon glyphicon-calendar"></span>Календарь{{/link-to}}</li>
          </ul>
          <ul class="nav navbar-nav pull-left">
            <li>{{#link-to 'newpost'}}<span class="glyphicon glyphicon-plus"></span>Новый Event{{/link-to}}</li>
          </ul>
        </div>
        <ul class="nav navbar-nav pull-right">
            <a class="glyphicon glyphicon-log-out navbar-brand pull-right" {{action "linkClicked" on="click"}} style="cursor: pointer;">Logout</a>  
        </ul>
          
      </div>
    </nav>   
    {{outlet}}
  </script>
                                                             
  <script type="text/x-handlebars" id="posts">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-lg-3">
          <table class="table" id="external-events">
            <thead>
            <tr><th>Ваши Events</th></tr>
            </thead>
            {{#each model}}
            <tbody>
            <tr><td>
            {{#view App.DropTool class='external-event' contentBinding="this"}}
                {{#link-to 'post' this}}
                {{{title}}} <small class='text-muted'>({{format-date start}})</small> {{/link-to}}
             {{/view}}
            </td></tr>
            {{/each}}
          </tbody></table>
        </div>
        <div class="col-sm-9 col-lg-9">
          {{outlet}}
        </div>
      </div>
    </div>
  </script>

  <script type="text/x-handlebars" id="posts/index">
    {{#view App.CalendarTool id="calendar" }}{{/view}}
  </script>

  <script type="text/x-handlebars" id="post/_edit">
  <div class="form-group">
    <label for="title" class="col-sm-2 control-label">Название</label>
    <div class="col-sm-10"> 
      {{input class="form-control" name="title" valueBinding="model.title" placeholder="Enter Title here"}}
    </div>
  </div>
  <div class="form-group">
    <label for="Description" class="col-sm-2 control-label">Описание</label>
    <div class="col-sm-10">
      {{textarea class="form-control" name="body" valueBinding="model.body" placeholder="Enter Description here" }}
    </div>
  </div>
  <div class="form-group">
    <label for="Date" class="col-sm-2 control-label">Дата</label>
    <div class="col-sm-10">
      {{view  App.DaterangepickerView startBinding="model.start" endBinding="model.end"}}
    </div>
  </div>
  <div class="form-group">
        <label for="AllDay" class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
          {{#view App.SwitchView data-on="primary" data-off="warning" data-text-label="All Day"}}
            <input type="checkbox" id="allDay" value="model.allDay"  />
          {{/view}}
        </div>
      </div>
  </script>

  <script type="text/x-handlebars" id="post">
    {{#if isEditing}}
      
      {{partial 'post/edit'}}

      <button class="btn btn-success" {{action 'doneEditing'}}>Сохранить</button>
      <button class="btn "{{action 'cancelEditing'}}>Отмена</button>
    {{else}}
      <button class="btn btn-primary" {{action 'edit' target="controller"}}>
      <span class="glyphicon glyphicon-pencil"></span>Изменить</button>
      <button class="btn btn-danger" {{action 'delete' target="controller"}}>
      <span class="glyphicon glyphicon-trash"></span>Удалить</button>
    {{/if}}
    <h1>{{title}}</h1>
    <h2>by {{author}} <small class='muted'>(Последний раз редактировался {{format-date-from changed}})</small></h2>
    <hr>
    <div class='below-the-fold'>
      {{format-markdown body}}
    </div>
  </script>   

  <script type="text/x-handlebars" id="newpost">
    
  <form class="form-horizontal" role="form">
      <div class="form-group">
        <label for="title" class="col-sm-2 control-label">Название</label>
        <div class="col-sm-10">
          {{input class="form-control"  name="new_title" valueBinding="model.new_title" placeholder="Enter title here" required=""}}
        </div>
      </div>        

      <div class="form-group">
        <label for="Description" class="col-sm-2 control-label">Описание</label>
        <div class="col-sm-10">
          {{textarea class="form-control" name="new_body" valueBinding="model.new_body" placeholder="Enter Description here" required=""}}
        </div>
      </div>

      <div class="form-group">
        <label for="Date" class="col-sm-2 control-label">Дата</label>
        <div class="col-sm-10">
          {{view  App.DaterangepickerView startBinding="model.start" endBinding="model.end"}}
        
        </div>
      </div>

      <div class="form-group">
        <label for="AllDay" class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
          {{#view App.SwitchView data-on="primary" data-off="warning" data-text-label="All Day"}}
            <input type="checkbox" id="allDay" value="all_day" checked />
          {{/view}}
          
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-success" {{action 'createPost'}}>Создать event</button>
        </div>
      </div>
  </form>  
  </script>

  <script src="js/libs/bootstrap.min.js"></script>
  <script src="js/libs/bootbox.min.js"></script>
  <script src="js/libs/handlebars-1.1.2.js"></script>
  <script src="js/libs/ember-1.2.0.js"></script>
  <script src="js/libs/ember-data.min.js"></script>
  <script src="js/libs/moment-with-langs.min.js"></script>
  <script src='js/libs/fullcalendar.min.js'></script>  
  <script src="js/libs/daterangepicker.js"></script>
  <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
  <script src="http://cdnjs.cloudflare.com/ajax/libs/showdown/0.3.1/showdown.min.js"></script>
  <script src="js/app.js"></script>
  
</body>
</html>
