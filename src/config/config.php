<?php

return [
  /** Set mongodb connection name **/
  'connection_name' => env('SDK_LOGS_CONNECTION_NAME', 'mongodb'),
  /** Set default mongodb collection **/
  'default_collection' => env('SDK_LOGS_DEFAULT_COLLECTION', 'logs_generics'),
  /** Set default days logs to delete **/
  'default_days_to_delete' => env('SDK_LOGS_DEFAULT_DAYS_TO_DELETE', 7),
];