<?
/*
 * nsamblr
 * Copyright (C) 2010 David <grannost@gmail.com>

 * nsamblr is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.

 * nsamblr is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function get_params($params) {
  $param_array = preg_split('/\/+/', $params);
  array_shift($param_array);
  return $param_array;
}

function short($text) {
  if (strlen($text) > 60)
    return substr($text, 0, 60).'...';
  else
    return $text;
}

function clean($text) {
  return mysql_real_escape_string(trim($text));
}

function clean_url($text) {
  $text = trim($text);
  $text = preg_replace('/ /', '%20', $text);

  return mysql_real_escape_string($text);
}

function json($array) {
  $encoded = '{';
  foreach ($array as $id => $value)
    $encoded .= '"'.$id.'":"'.$value.'",';
  $encoded = rtrim($encoded, ',');
  return $encoded . '}';
}

function escape_quotes($text) {
  return preg_replace('/\"/', '\"', $text);
}

// Used to know if we're alone.
function empty_users() {
    $query = mysql_query("SELECT 1 FROM users LIMIT 1");

    return(!mysql_num_rows($query));
}

function go_to($location) {
  header('Location: '.$location);
  die();
}

// From http://www.lost-in-code.com/programming/php-code/php-random-string-with-numbers-and-letters/
function gen_random($length) {
  $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $string = '';    
  for ($p = 0; $p < $length; $p++) {
      $string .= $characters[mt_rand(0, strlen($characters) - 1)];
  }
  return $string;
}

function do_header($title = -1, $wider = false) {
  global $config;

  if ($title == -1)
    $title = $config['shortener'];

  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
  echo '<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">'."\n";
  echo '<head>'."\n";
  echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'."\n";
  echo '<link rel="stylesheet" type="text/css" href="'.$config['base'].'inc/default.css"/>'."\n";
  if ($wider)
    echo '<style type="text/css">#content { width: 800px; } </style>'."\n";
  echo '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>'."\n";
  echo '<script src="'.$config['base'].'inc/default.js" type="text/javascript"></script>'."\n";
  echo '<script type="text/javascript">var base = \''.$config['base'].'\';</script>'."\n";
  echo '<title>'.$title.'</title>'."\n";
  echo '</head>'."\n";
  echo '<body>'."\n";
  echo '<div id="content">'."\n";
  echo '<h1>'.$title.'</h1>'."\n";
  echo '<div id="wrapper">'."\n";
}

function do_footer($show_copy = true) {
  global $config;

  echo '</div>'."\n";
  if ($show_copy) {
    echo '<div id="footer">'."\n";
    echo '<a href="'.$config['base'].'legal/">legal</a> - <span>'.$config['shortener'].'</span> uses <a href="http://github.com/egns/nsamblr/" target="_blank">nsamblr</a>'."\n";
    echo '</div>'."\n";
  }
  echo '</div>'."\n";

  echo '</body>'."\n";
  echo '</html>'."\n";
}

?>
