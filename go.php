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

$short = get_params($_SERVER['PATH_INFO']);

$short_url = clean($short[0]);

if (empty($short_url))
  go_to('/');

$query = mysql_query("SELECT long_url FROM urls WHERE short_url = CONVERT('$short_url' USING binary) LIMIT 1");

$result = mysql_fetch_row($query, MYSQL_ASSOC);

if (empty($result))
  go_to('/');

while ($row = $result) {
  $long_url = $row['long_url'];
  go_to($row['long_url']);
}

?>