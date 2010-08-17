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

// Cookie: base64('nsamblr'.userid.md5(md5(salted_password).siteid)).timestamp);

class Session {
  var $id = 0;
  var $nick = '';
  var $password = '';
  var $ip = '';
  var $mail = '';

  function auth($nick, $password) {
    global $config;

    $nick = clean($nick);
    $password = clean($password);

    $query = mysql_query("SELECT id, password, salt FROM users WHERE nick = '$nick' LIMIT 1");
    $result = mysql_fetch_row($query, MYSQL_ASSOC);

    if(empty($result))
      return 1; // no such user

    $md5password = md5(md5($result['salt'].$password).$config['site_id']);

    if ($md5password != md5($result['password'].$config['site_id']))
      return 2; // incorrect password

    // Logged correcty
    return base64_encode('nsamblr'.'!'.$result['id'].'!'.md5($result['password'].$config['site_id']).'!'.time());
  }

  function read_cookie() {
    global $config;

    $this->ip = $_SERVER['REMOTE_ADDR'];

    if (isset($_COOKIE['nsamblr_session']))
      $cookie = explode('!', base64_decode($_COOKIE['nsamblr_session']));
    else
      return false;

    if ($cookie[0] == 'nsamblr') {
      $id = intval($cookie[1]);
      $password = clean($cookie[2]);

      $query = mysql_query("SELECT id, nick, password, ip, mail, salt FROM users WHERE id = '$id' LIMIT 1");
      $result = mysql_fetch_row($query, MYSQL_ASSOC);

      if(empty($result))
        return 1; // no such user

      $bdpassword = md5($result['password'].$config['site_id']);

      if ($bdpassword == $password) { // Authed
        $this->id = $result['id'];
        $this->nick = $result['nick'];
        $this->password = $result['password'];
        mysql_query('UPDATE users SET ip = \''.$this->ip.'\' WHERE id = '.$this->id);

        $mail = $result['mail'];
        return true;
      }
    }
  }

  function destroy_cookie() {
    global $config;

    setcookie('nsamblr_session', '', time() - 3600, $config['base']);
  }

  function you_got_to_authenticate() {
    global $config;

    go_to($config['base'].'admin/login/');
  }

}

?>
