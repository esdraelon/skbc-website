<?php

function instructor_list($struct) {
  $instructors = array();
  for ($i = 1; $i <= 3; $i++)
    if (trimlen($struct["Instructor{$i}"]) > 0)
      $instructors[] = "<a href='instructor-bio?instructor=" . $struct["Instructor{$i}Id"] . "'>" . $struct["Instructor{$i}"] . "</a>";
  return implode(", ", $instructors);
}

function trimpre($prefix, $str) {
  if (substr($str, 0, strlen($prefix)) == $prefix) {
      return substr($str, strlen($prefix));
  }
  return $str;
}

function logged_in() {
  if (isset($_SESSION['student_id']) && $_SESSION['student_id'] > 0) return $_SESSION['student_id'];
  return false;
}

function login() {
  global $REQUIRES_LOGIN;
  if (isset($REQUIRES_LOGIN) && $REQUIRES_LOGIN === true) 
    if (!(isset($_SESSION['student_id']) && $_SESSION['student_id'] > 0))
      header("Location: login?" . $_SERVER['REQUEST_URI']); 
}

function requires_login() {
  global $REQUIRES_LOGIN;
  $REQUIRES_LOGIN = true; 
}

function message($message) {
  return "<span class='message'>$message</span>"; 
}