<div class="programnaptar-holder">
  <div class="wrapper" ng-controller="Programs" ng-init="init()">
    <div class="napcoltroller">
      <div class="holder">
        <div class="selector">
          <div class="wrapper">
            <div class="sbar">
              <div class="pre-button unique-date" ng-class="(customDateEnable)?'wbg':''" ng-click="allowCustomDateSelect(true)">
                <div><?php echo __('Egyéni', TD); ?></div>
              </div>
              <div class="pre-button" ng-click="changeDateTemplate(dtemp)" ng-class="(calendarModel.selectedTemplate == dtemp.name && !customDateEnable)?'wbg':''" ng-repeat="dtemp in customPickerTemplates">
                <div class="">{{dtemp.name}}</div>
              </div>
            </div>
            <div class="picker">
              <div class="sel-dates">
                <div class="start">
                  <input type="date" name="" ng-disabled="!customDateEnable" ng-change="syncCalendarItems()" ng-model="calendarModel.dateStart">
                </div>
                <div class="div">
                  &mdash;
                </div>
                <div class="end">
                  <input type="date" name="" ng-disabled="!customDateEnable" ng-change="syncCalendarItems()" ng-model="calendarModel.dateEnd">
                </div>
              </div>
              <md-date-range-picker
                first-day-of-week="1"
                one-panel="true"
                localization-map="localizationMap"
                selected-template="calendarModel.selectedTemplate"
                selected-template-name="calendarModel.selectedTemplateName"
                __custom-templates="customPickerTemplates"
                md-on-select="syncCalendarItems()"
                disable-templates="TD YD TW LW TM LM LY TY"
                highlighted-dates="calendarModel.highlightedDates"
                date-start="calendarModel.dateStart"
                date-end="calendarModel.dateEnd">
              </md-date-range-picker>
            </div>
          </div>
        </div>
        <div class="programs-list">
          <div class="wrapper">
            <div class="cont">
              <div class="loading" ng-show="syncing">
                <?php echo __('Programok betöltése folyamatban...', TD); ?> <i class="fa fa-spin fa-spinner"></i>
              </div>
              <div class="no-items" ng-show="!syncing && events.length==0">
                <i class="fa fa-calendar-o"></i><br>
                <?php echo __('A kiválasztott időszakban nincs esemény:', TD); ?><br>
                <strong ng-show="!customDateEnable">{{calendarModel.selectedTemplateName}}</strong>
                <strong ng-show="customDateEnable">{{calendarModel.dateStart|date:'yyyy.MM.dd.'}} - {{calendarModel.dateEnd|date:'yyyy.MM.dd.'}}</strong>
              </div>
              <div class="events" ng-show="!syncing && events.length!=0">
                <div class="event" ng-repeat="event in events">
                  <div class="wrapper">
                    <div class="img">
                      <a href="{{event.url}}"><img ng-src="{{event.img}}" alt="{{event.title}}"></a>
                    </div>
                    <div class="dateplace">
                      <div class="info-text" ng-show="(!event.date.start && !event.pos)">
                        <i class="fa fa-calendar"></i> <?php echo __('Érdeklődjön a részletekért!', TD); ?>
                      </div>
                      <div class="date" ng-show="event.date.start">
                        <i class="fa fa-calendar"></i> {{event.date.start}}, {{event.date.weekday}}<br>{{event.date.comment}}
                      </div>
                      <div class="pos" ng-show="event.pos">
                        <i class="fa fa-map-marker"></i> <span ng-bind-html="event.pos"></span>
                      </div>
                    </div>
                    <div class="title">
                      <h4><a href="{{event.url}}">{{event.title}}</a></h4>
                      <div class="desc">
                          {{event.desc}}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
