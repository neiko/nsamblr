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

include('config.php');

function go_to($location) {
  header('Location: '.$location);
  die();
}

function handle_error($status = '404 Not Found', $header, $text) {
  global $config;

  header('HTTP/1.0 '.$status);
  header('Status: '.$status);
  do_header();
  echo '<h3>'.$header.'</h3>';
  echo '<p class="warning">Sorry :-(</p>';
  echo '<p>'.$text.'</p>';
  echo '<p class="leave"><a href="'.$config['base'].'">Go to '.$config['shortener'].'</a></p>';
  do_footer();
  die();
}

$short = get_params($_SERVER['PATH_INFO']);

$short_url = clean($short[0]);

if (empty($short_url))
  go_to('/');

$query = mysql_query("SELECT long_url, status FROM urls WHERE short_url = CONVERT('$short_url' USING binary) LIMIT 1");

$result = mysql_fetch_row($query, MYSQL_ASSOC);

if (empty($result))
  handle_error('404 Not Found', 'Not Found', 'We couldn\'t find that URL.');

if ($result['status'] == 'suspended')
  handle_error('403 Forbidden', 'Suspended URL', 'This URL has been suspended.');

$long_url = $result['long_url'];
go_to($long_url);

?>