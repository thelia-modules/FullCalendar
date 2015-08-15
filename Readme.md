# Full Calendar

This module easily integrates FullCalendar to your Thelia 2

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is FullCalendar.
* Activate it in your thelia administration panel

### Composer

Execute this command in your project directory:

```
$ composer require thelia/full-calendar-module:~1.0
```

## Usage

This module adds a smarty function that creates a calendar for you.

The ```{calendar}``` function has 4 optional parameters:

- id: The calendar container ID. If this parameter isn't used, the ID will be ```fullcalendar```, followed by a increment number.
    Example: fullcalendar0, fullcalendar1, ...
- class: The calendar container classes. By default, the class ```thelia-fullcalendar``` is automatically added. This parameter can be an array or a string.
    Example: ```class=["foo", "bar"]```, ```class="foo bar"```
- options: This parameter let you give the ```fullcalendar``` javascript method's argument. This parameter must be an array.
    Example: The function ```{calendar options=["weekends" => 0]}``` will add the tag ```<div id="fullcalendar0" class="thelia-fullcalendar"></div>```
    and the js snippet: ```$("#fullcalendar0").fullcalendar({weekends: 0});```
- attr: This parameter let you add more attributes to the calendar container tag. This must be an array.
    Example: ```{calendar attr=["data-id" => 1]``` will output ```<div id="fullcalendar0" class="thelia-fullcalendar" data-id="1"></div>```

## Hook

If your module use one or more hook, fill this part. Explain which hooks are used.

This module uses two hooks:
- ```main.stylesheet```: This hook is used to add FullCalendar's default stylesheets (standard and print)
- ```main.javascript-initialization```: This hook is used to add Moment and FullCalendar javascript files and calendars execution script.
