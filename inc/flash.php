<?php

// Key for all flash messages
const FLASH = 'FLASH_MESSAGES';

// Type of all flash messages
const FLASH_ERROR = 'error';
const FLASH_WARNING = 'warning';
const FLASH_INFO = 'info';
const FLASH_SUCCESS = 'success';

// Create flash message
function create_flash_message(string $name, string $message, string $type): void
{
  // Remove existing message with the name
  if (isset($_SESSION[FLASH][$name])) {
    unset($_SESSION[FLASH][$name]);
  }
  // Add the message to the session
  $_SESSION[FLASH][$name] = ['message' => $message, 'type' => $type];
}

// Format a flash message
function format_flash_message(array $flash_message): string
{
  return sprintf('<div class="alert alert-%s">%s</div>',
    $flash_message['type'],
    $flash_message['message']
  );
}

// Display a flash message
function display_flash_message(string $name): void
{
  if (!isset($_SESSION[FLASH][$name])) {
    return;
  }

  // get message from the session
  $flash_message = $_SESSION[FLASH][$name];

  // delete the flash message
  unset($_SESSION[FLASH][$name]);

  // display the flash message
  echo format_flash_message($flash_message);
}

// Display all flash messages
function display_all_flash_messages(): void
{
  if (!isset($_SESSION[FLASH])) {
    return;
  }

  // get flash messages
  $flash_messages = $_SESSION[FLASH];

  // remove all the flash messages
  unset($_SESSION[FLASH]);

  // show all flash messages
  foreach ($flash_messages as $flash_message) {
    echo format_flash_message($flash_message);
  }
}

// Flash message
function flash(string $name = '', string $message = '', string $type = ''): void
{
  if ($name !== '' && $message !== '' && $type !== '') {
    create_flash_message($name, $message, $type);
  } elseif ($name !== '' && $message === '' && $type === '') {
    display_flash_message($name);
  } elseif ($name === '' && $message === '' && $type === '') {
    display_all_flash_messages();
  }
}

?>