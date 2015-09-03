App = Ember.Application.create({});

var showdown = new Showdown.converter();

Ember.Handlebars.helper('format-markdown', function(input) {
  return new Handlebars.SafeString(showdown.makeHtml(input));
});

Ember.Handlebars.helper('format-date-from', function(changed) {
  return moment(changed).fromNow();
});

Ember.Handlebars.helper('format-date', function(date) {
  return moment(date).format('DD MMMM YYYY');
});

App.Router.map(function() {
  this.resource('newpost');
  this.resource('posts', function() {
    this.resource('post', { path: ':post_id' });
  });
  moment.lang('ru');
});

App.IndexRoute = Ember.Route.extend({
  model: function(params) {
    return $.getJSON('ajax.php').then(function(data) {
      return data.findBy('id', params.post_id);
    });
  }
});

App.PostRoute = Ember.Route.extend({
  model: function(params) {
    return $.getJSON('ajax.php').then(function(data) {
      return data.findBy('id', params.post_id);
    });
  }
});

App.PostsRoute = Ember.Route.extend({
 setupController : function(controller, model){
        controller.set("model", model);

    },
  model: function() {
   
    return $.getJSON('ajax.php').then(function(data) {
      return data.map(function(post){
       return post;
      });
    });
  }

});

App.PostsIndexRoute = Ember.Route.extend({
  model: function() {
    return $.getJSON('ajax.php').then(function(data) {
      return data.map(function(post){
       return post;
      });
    });
  },
  setupController : function(controller, model){
        controller.set("model", model);
    }
});

App.NewpostRoute = Ember.Route.extend({
  model: function() {
    return {
      new_title: "",
      author: "",                                    
      new_body: "",
      start: moment().format('YYYY-MM-DD HH:mm:ss'),
      end: moment().format('YYYY-MM-DD HH:mm:ss')
    }
  },
  setupController : function(controller, model){
        controller.set("model", model);
    }
});

App.Controller = Ember.ObjectController.extend({
  linkClicked: function(){
     bootbox.dialog({
        message: "Вы точно хотите выйти?",
        title: "DANGER!! Вы пытаетесь покинуть сайт",
        buttons: {
          success: {
            label: "Разлогинь меня",
            className: "btn-danger",
            callback: function() {
              window.location.href = "logout.php";
            }
          },
          main: {
            label: "Я остаюсь",
            className: "btn-success",
          }
        }
      });
  }
});

App.PostController = Ember.ObjectController.extend({
  isEditing: false,
  edit: function() {
    this.set('isEditing', true);
  },

  doneEditing: function() {
    this.set('isEditing', false);    
    this.set('changed',moment().format('YYYY-MM-DD HH:mm:ss '));
 
    $.ajax({
          url: 'save.php',
          data:{
            id: this.get("model").id, 
            title: this.get("model").title,
            start: this.get("model").start,
            end: this.get("model").end,
            changed: this.get("model").changed, 
            allDay: $('#allDay').prop("checked"),
            body: this.get("model").body
          }
        });
  },
  cancelEditing: function() {
    this.set('isEditing', false);  

  },
  delete: function() {
    var self = this;
    bootbox.confirm("Вы уверены?", function(result) {
      if(result)
      {
        $.ajax({
            url: 'delete.php',
            data:{
              id: self.get("model").id,
            }
          });  
         self.transitionTo('posts');
      }
    });    
  } 
});

App.NewpostController = Ember.ObjectController.extend({
  createPost: function() {
    if(this.get("model").new_title != "" )
    {
      $.ajax({
        url: 'create.php',
        data:{
          author: this.get("model").author,
          title: this.get("model").new_title,
          body: this.get("model").new_body,
          changed:moment().format('YYYY-MM-DD HH:mm:ss '), 
          start: this.get("model").start,
          end: this.get("model").end,
          allDay: $('#allDay').prop("checked"),
          url: "http://test1.ru/ember/#/posts/"
        }
      });
      var self = this;
      bootbox.dialog({
        message: "Event создан",
        title: "Успех!",
        buttons: {
          success: {
            label: "Перейти в календарь",
            className: "btn-success",
            callback: function() {
              self.transitionTo('posts');
            }
          },
          main: {
            label: "Остаться тут",
            className: "btn-primary",
          }
        }
      });
    }else bootbox.alert("Для создания нужно ввести хотя бы название события");
  } 
});
                                            
App.CalendarTool = Em.View.extend({
    tagName: 'div',
    attributeBindings: ['model', 'events', 'owner'],
    classNamesBindings: ['class'],
    didInsertElement: function() {
     // debugger
      this.$().fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
      axisFormat: 'HH:mm',
      buttonText: {
        today: 'Сегодня',
        month: 'Месяц',
        day: 'День',
        week: 'Неделя'
      },
      monthNames:['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
      monthNamesShort:['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'], //['Янв','Фев','Мар','Апр','Май','Июнь','Июль','Авг','Сент','Окт','Ноябрь','Дек'],
      dayNames: ['Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота'],
      dayNamesShort: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт','Сб'],
      timeFormat: 'H:mm{ - H:mm}',
      events: "ajax.php",
      selectable: true,
      editable: true,
      droppable: true, 

      drop: function(date, allDay) { 
        var href = $(this).find('.ember-view').attr('href');
        var toRemove = "#/posts/";
        var id = href.replace(toRemove,'');
        var x = $('#calendar').fullCalendar('clientEvents');
        var ev;
        for (var i = x.length - 1; i >= 0; i--) {
          if(x[i].id == id) 
            {ev = x[i]; break;}
        };
       

        var end = moment(ev.end);
        var start = moment(ev.start);
        var g = moment(date).diff(start,'days',true);
        g = g.toFixed();
        var newstart
        var newend
        var abs = Math.abs(g);
        
        if(g < 0) 
        {
           newstart = moment(start).subtract('days',abs);
           newend = moment(end).subtract('days',abs);
        } 
        else
        { 
          newstart = moment(start).add('days',g);
          newend = moment(end).add('days',g);
        }

        ev.start = moment(newstart).toDate();
        ev.end = moment(newend).toDate();
        ev.changed = moment().format('YYYY-MM-DD HH:mm:ss');
         $.ajax({
          url: 'update.php',
          data:{
            id: ev.id, 
            start: moment(newstart).format('YYYY-MM-DD HH:mm:ss') ,
            end:   moment(newend).format('YYYY-MM-DD HH:mm:ss'),
            changed: moment().format('YYYY-MM-DD HH:mm:ss'),
            allDay: ev.allDay
          }
        });

        $('#calendar').fullCalendar('updateEvent', ev);
      },

      eventDrop: function(event){
       
        event.changed = moment().format('YYYY-MM-DD HH:mm:ss');
        $.ajax({
          url: 'update.php',
          data:{
            id: event.id, 
            start: moment(event.start).format('YYYY-MM-DD HH:mm:ss') ,
            end:moment(event.end).format('YYYY-MM-DD HH:mm:ss'),
            changed: event.changed,
            allDay: event.allDay
          }
        });
        
      },
      eventResize: function(event){

        event.changed = moment().format('YYYY-MM-DD HH:mm:ss ');
        $.ajax({
          url: 'update.php',
          data:{
            id: event.id, 
            start: moment(event.start).format('YYYY-MM-DD HH:mm:ss ') ,
            end:moment(event.end).format('YYYY-MM-DD HH:mm:ss '),
            changed: event.changed, 
            allDay: event.allDay
          }
        });
      }
    });
    }
});

App.DropTool = Em.View.extend({

    tagName: 'div',
    attributeBindings: [ 'events', 'owner'],
    classNamesBindings: ['class'],
    didInsertElement: function() {

      $('#external-events div.external-event').each(function() {
      var eventObject = {
        title: $.trim($(this).text()).split('(')[0]
      };
      $(this).data('eventObject', eventObject);
      $(this).draggable({
        zIndex: 999,
        revert: true,      
        revertDuration: 0  
      });    
    });

    }
});

App.SwitchView = Ember.View.extend({
   tagName: "div",
    attributeBindings: ["data-on" ,"data-off","data-text-label"],
   classNames: ['label-toggle-switch make-switch'],
   didInsertElement: function() {
       this.$().bootstrapSwitch();
   }
});

App.DaterangepickerView = Ember.View.extend({
    tagName: "input",
    attributeBindings: ["start", "end", "placeholder"],
    placeholder: moment().format('YYYY-MM-DD HH:mm - YYYY-MM-DD HH:mm'),
    classNames: ['form-control'],
    start: moment().format('YYYY-MM-DD HH:mm:ss '),
    end: moment().format('YYYY-MM-DD HH:mm:ss '),
    format: 'YYYY-MM-DD HH:mm:ss',//
    didInsertElement: function() {
        var self = this;
        var format = this.get('format');
        this.$().daterangepicker(
               { 
                  startDate: moment(),
                  endDate: moment(),
                  minDate: moment().format('DD/MM/YYYY'), //moment().format('MM/DD/YYYY')
                  maxDate: '12/31/2020',
                  dateLimit: { days: 60 },
                  showDropdowns: true,
                  showWeekNumbers: true,
                  timePicker: true,
                  timePickerIncrement: 1,
                  timePicker12Hour: false,
                  ranges: {
                     'Сегодня': [moment(), moment()],
                     'Завтра': [moment().add('days', 1), moment().add('days', 1)],
                     'На 7 дней': [moment(), moment().add('days', 6) ],
                     'На 30 дней': [moment(), moment().add('days', 29) ],
                     'До конца месяца': [moment().startOf('month'), moment().endOf('month')],
                     'На следующий месяц': [moment().add('month', 1).startOf('month'), moment().add('month', 1).endOf('month')]
                  },
                  opens: 'right',
                  buttonClasses: ['btn btn-default'],
                  applyClass: 'btn-small btn-success',
                  cancelClass: 'btn-small',
                  format: 'DD.MM.YYYY HH:mm',
                  separator: ' - ',
                  locale: {
                      applyLabel: 'Применить',
                      cancelLabel: 'Отмена',
                      fromLabel: 'From',
                      toLabel: 'To',
                      customRangeLabel: 'Хочу выбрать сам',
                      daysOfWeek: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт','Сб'],
                      monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                      firstDay: 1
                  }
               },     
               function(start, end) {                        
                   self.set('start',start.format(format));
                   self.set('end',end.format(format));                     
               }
        )
    }
});
