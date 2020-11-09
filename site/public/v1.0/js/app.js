var app = angular.module('Gundel', ['ngMaterial', 'ngMessages', 'ngCookies', 'ngSanitize', 'ngMaterialDateRangePicker', 'timer']);

app.config(function($mdDateLocaleProvider){
  $mdDateLocaleProvider.firstDayOfWeek = 1;
  $mdDateLocaleProvider.months = ['Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Július', 'Augusztus', 'Szeptember', 'Október', 'November', 'December'];
  $mdDateLocaleProvider.shortMonths = ['Jan', 'Feb', 'Már', 'Ápr', 'Máj', 'Jún', 'Júl', 'Aug', 'Szep', 'Okt', 'Nov', 'Dec'];
  $mdDateLocaleProvider.days = ['Vasárnap', 'Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat'];
  $mdDateLocaleProvider.shortDays = ['V', 'H', 'K', 'Sze', 'Cs', 'P', 'Szo'];

  $mdDateLocaleProvider.formatDate = function(date) {
     var m = moment(date);
     return m.isValid() ? m.format('L') : '';
   };
});

app.controller('Programs', ['$scope', '$http', '$mdToast', '$mdDialog', '$httpParamSerializerJQLike', '$mdDateRangePicker', '$window', function($scope, $http, $mdToast, $mdDialog, $httpParamSerializerJQLike, $mdDateRangePicker, $window)
{
  var date = new Date();

  $scope.getWeekDay = function( what )
  {
    var curr = new Date; // get current date
    var first = curr.getDate() - curr.getDay() + 1; // First day is the day of the month - the day of the week
    var last = first + 6; // last day is the first day + 6

    var firstday = new Date(curr.setDate(first));
    var lastday = new Date(curr.setDate(last));

    return ( what == 'first') ? firstday : lastday;
  }

  $scope.customDateEnable = false;
  $scope.calendarModel = {
    highlightedDates: [],
    selectedTemplate: 'Aktuális hét',
    selectedTemplateName: null,
    dateStart: $scope.getWeekDay('first'),
    dateEnd: $scope.getWeekDay('last')
  };

  $scope.syncing = false;
  $scope.events = [];
  $scope.kiemelt_program = {};

  $scope.getMonthFirstLast = function(){
    var date = new Date();
    var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    return [firstDay, lastDay];
  }

  $scope.submitSearch = function()
  {
    var dstart = new Date($scope.calendarModel.dateStart);
    var dstartformat = dstart.getFullYear()+'-'+ ('0' + (dstart.getMonth()+1)).slice(-2) +'-'+ ('0' + dstart.getDate()).slice(-2);
    var dend = new Date($scope.calendarModel.dateEnd);
    var dendformat = dend.getFullYear()+'-'+ ('0' + (dend.getMonth()+1)).slice(-2) +'-'+ ('0' + dend.getDate()).slice(-2);

    console.log(dstartformat+' - '+dendformat);
    $window.location.href = '/programok/?from='+dstartformat+'&to='+dendformat;
  }

  $scope.changeDateTemplate = function(temp)
  {
    $scope.customDateEnable = false;
    $scope.calendarModel.selectedTemplate = temp.name;
    $scope.calendarModel.dateStart = temp.dateStart;
    $scope.calendarModel.dateEnd = temp.dateEnd;
    $scope.syncCalendarItems();
  }

  $scope.allowCustomDateSelect = function( flag )
  {
    if (flag) {
      $scope.customDateEnable = flag;
      $scope.calendarModel.dateStart = null;
      $scope.calendarModel.dateEnd = null;
    } else {
      $scope.customDateEnable = flag;
    }
  }

  var monthfirstlast = $scope.getMonthFirstLast();

  // datepicker
  $scope.customPickerTemplates = [
    {
      name: 'Ma',
      dateStart: new Date(date.getFullYear(), date.getMonth(), date.getDate()),
      dateEnd: new Date(date.getFullYear(), date.getMonth(), date.getDate())
    },
    {
      name: 'Aktuális hét',
      dateStart: $scope.getWeekDay('first'),
      dateEnd: $scope.getWeekDay('last')
    },
    {
      name: 'Az elkövetkezendő 7 nap',
      dateStart: new Date(date.getFullYear(), date.getMonth(), date.getDate()),
      dateEnd: new Date(date.getFullYear(), date.getMonth(), date.getDate() + 7)
    },
    {
      name: 'Ebben a hónapban',
      dateStart: monthfirstlast[0],
      dateEnd: monthfirstlast[1]
    }
  ];

  $scope.localizationMap = {
    'Mon': 'H',
    'Tue': 'K',
    'Wed': 'Sz',
    'Thu': 'Cs',
    'Fri': 'P',
    'Sat': 'Szo',
    'Sun': 'V',
    'Today': 'Ma',
    'Yesterday': 'Tegnap',
    'This week': 'Ez a hét',
    'Last week': 'Utolsó hét',
    'This month': 'Ez a hónap',
    'Last month': 'Utolsó hónap',
    'This year': 'Ez az év',
    'Last year': 'Utolsó év',
    'January': 'Január',
    'February': 'Február',
    'March': 'Március',
    'April': 'Április',
    'May': 'Május',
    'June': 'Június',
    'July': 'Július',
    'August': 'Augusztus',
    'September': 'Szeptember',
    'Sep': 'Szept',
    'October': 'Október',
    'November': 'November',
    'December': 'December'
  };

  $scope.init = function(){
    $scope.syncCalendarItems();
  }

  $scope.syncCalendarItems = function() {
    var dstart = new Date($scope.calendarModel.dateStart);
    var dstartformat = dstart.getFullYear()+'-'+ ('0' + (dstart.getMonth()+1)).slice(-2) +'-'+ ('0' + dstart.getDate()).slice(-2);
    var dend = new Date($scope.calendarModel.dateEnd);
    var dendformat = dend.getFullYear()+'-'+ ('0' + (dend.getMonth()+1)).slice(-2) +'-'+ ('0' + dend.getDate()).slice(-2);

    $scope.syncing = true;
    $scope.events = [];

    $http({
      method: 'POST',
      url: '/ajax/post/',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $httpParamSerializerJQLike({
        type: 'Calendar',
        mode: 'syncCalndarItems',
        datestart: dstartformat,
        dateend: dendformat
      })
    }).success(function(r){
      if (r.dates && r.dates.length != 0) {
        $scope.calendarModel.highlightedDates = r.dates;
      }
      $scope.syncing = false;
      $scope.events = r.data;
    });
  }

}]);


app.directive('formattedDate', function(dateFilter) {
  return {
    require: 'ngModel',
    scope: {
      format: "="
    },
    link: function(scope, element, attrs, ngModelController) {
      ngModelController.$parsers.push(function(data) {
        //convert data from view format to model format
        return dateFilter(data, scope.format); //converted
      });

      ngModelController.$formatters.push(function(data) {
        //convert data from model format to view format
        return dateFilter(data, 'yyyy. MM. dd.'); //converted
      });
    }
  }
});
