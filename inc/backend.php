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

include('../config.php');

function json_error($error) {
  return json(array('error' => $error));
}

function check_status($id) {
  $query = mysql_query("SELECT id, status FROM urls WHERE id = $id");

  if (!$query)
    return array('error' => 'Internal error');

  $row = mysql_fetch_row($query, MYSQL_ASSOC);

  if (empty($row))
    return json_error('Bogus id');

  switch ($row['status']) {
    case 'active':
      $status = '<span title="Active" class="active">A</span>';
      $buttons = '<a href="#j" title="Suspend" onclick="d('.$row['id'].', s)">S</a>';
      break;
    case 'suspended':
      $status = '<span title="Suspended" class="suspended">S</span>';
      $buttons = '<a href="#j" title="Activate" onclick="d('.$row['id'].', a)">A</a>';
      break;
    default:
      $status = '<span title="Unknown">?</span>';
      $buttons = '<span title="Unknown">?</span>';
  }

  $buttons .= ' <a href="#j" title="Permanently remove" onclick="d('.$row['id'].', r)">R</a>';

  return json(array('status' => escape_quotes($status), 'buttons' => escape_quotes($buttons)));
}

function get_id() {
  if (!isset($_GET['id']))
    die(json_error('wut?'));
  else
    return intval($_GET['id']);
}

function get_urls() {
  global $config;

  if (empty($_GET['long']))
    die(json_error('Where\'s the URL?'));

  $long = clean_url($_GET['long']);

  if (strlen($long > $config['max_long_length']))
    die(json_error('Long URL is too long :-)'));

  if (!preg_match('/^(https?|ftp|irc):\/\/[a-zA-Z0-9\-]*\.\b[a-zA-Z]{2,4}\b/', $long))
    die(json_error('This ain\'t an URL!'));

  if (empty($_GET['short']))
    $short = gen_random(6);
  else {
    $short = clean($_GET['short']);
    if (!preg_match('/^([a-zA-Z]+)$/', $short))
      die(json_error('Only letters are allowed in the short URL'));
    if (strlen($short > $config['max_short_length']))
      die(json_error('Short URL is too long'));
  }

  return array('long' => $long, 'short' => $short);    
}

function get_login($nick, $password) {
  global $session;

  if (empty($nick) || empty($password))
    die(json_error('Both fields are mandatory'));

  $cookie = $session->auth($nick, $password);

  if ($cookie == 1 || $cookie == 2) // 1 means incorrect user, 2 incorrect password, but i'll unify both to avoid bruteforcing
    die(json_error('Wrong user/password'));

  return $cookie;
}

function get_register() {
  // $_GET['nick'], $_GET['password'], $_GET['mail']
  $nick = clean($_GET['nick']);

  // Check for duplicates
  $query = mysql_query("SELECT nick FROM users WHERE nick = '$nick' LIMIT 1");
  if (mysql_affected_rows())
    return 1;

  if (!preg_match('/[a-zA-Z0-9]*/', $nick))
    return 2;

  $password = clean($_GET['password']);
  if (empty($password))
    return 3;

  $mail = clean($_GET['mail']);

  return array('nick' => $nick, 'password' => $password, 'mail' => $mail); 
}

switch ($_GET['action']) {
  case 'new':
    $urls = get_urls();
    $short = $urls['short'];
    $long = $urls['long'];
    $ip = $_SERVER['REMOTE_ADDR'];

    mysql_query("INSERT INTO urls (`short_url`, `long_url`, `ip`) VALUES('$short', '$long', '$ip')");

    if (mysql_affected_rows() && mysql_affected_rows() != -1)
      die(json(array('newurl' => 'http://'.$_SERVER['SERVER_NAME'].'/'.$short)));
    else
      die(json_error('Error inserting'));
    break;
  case 'remove':
    $id = get_id();
    mysql_query("DELETE FROM urls WHERE id = $id");
    if (mysql_affected_rows())
      die(json(array('removed' => $id)));
    else
      die(json_error('Error removing'));
    break;
  case 'suspend':
    $id = get_id();
    mysql_query("UPDATE urls SET status='suspended' WHERE id = $id");
    if (mysql_affected_rows())
      die(check_status($id));
    else
      die(json_error('Error suspending'));
    break;
  case 'activate':
    $id = get_id();
    mysql_query("UPDATE urls SET status='active' WHERE id = $id");
    if (mysql_affected_rows())
      die(check_status($id));
    else
      die(json_error('Error activating'));
    break;
  case 'login':
    $cookie = get_login($_GET['nick'], $_GET['password']);
    setcookie('nsamblr_session', $cookie, time() + 608400, $config['base']);
    die(json(array('auth' => 'ok')));
    break;
  case 'register':
    $register = get_register();

    if ($register == 1)
      die(json_error('That user already exists'));

    if ($register == 2)
      die(json_error('Invalid characters in the nick, only alphanumeric chars are allowed'));

    if ($register == 3)
      die(json_error('You need to specify a password'));

    $nick = $register['nick'];
    $mail = $register['mail'];
    $salt = gen_random(10);
    $password = md5($salt.$register['password']);

    mysql_query("INSERT INTO users (`nick`, `password`, `mail`, `salt`) VALUES('$nick', '$password', '$mail', '$salt')");

    if (mysql_affected_rows() && mysql_affected_rows() != -1)
      die(json(array('newurl' => escape_quotes('User registered correctly. <a href="#j" id="anotherone">Register another one</a>'))));
    else
      die(json_error('Error registering'));
    break;
  default:
    die(json_error('wut?'));
}

?>
