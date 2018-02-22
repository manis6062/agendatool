<?php
require_once 'google-api-php-client/src/contrib/Google_CalendarService.php';
$event = new Google_Service_Calendar_Event(array(
  'summary' => 'Test Test and Test......',
  'location' => 'Ason, Kathmandu',
  'description' => 'So many tesssssssssssssssssssssssssssssssssssss',
  'start' => array(
    'date' => '2015-03-15',
  ),
  'end' => array(
    'date' => '2016-03-16',
  ),
));

$calendarId = 'primary';
$event = $service->events->insert($calendarId, $event);
print_r('Event created: %s\n', $event->htmlLink);
?>