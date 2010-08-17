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

// Used to contact the database
$config['db_username'] = '';
$config['db_password'] = '';
$config['db_hostname'] = '';
$config['db_database'] = '';

// This is the name of the shortener, it'll appear in several pages
// It doesn't have to be the hostname, i'll use it here as an example
$config['shortener'] = $_SERVER['SERVER_NAME'];

// This may change in your installation (it should not, remember that this is a shortener :-)
$config['base'] = '/';

// This is a secret key that will be used to compute cookies.
// If you change it, you'll invalidate all the cookies, but you won't lose data.
$config['site_id'] = '';

// Max length for long URLs
$config['max_long_length'] = 1000;

// Max length for short urls, it does not take into account the hostname nor the trailing slash
$config['max_short_length'] = 12;

include('inc/db.php');
include('inc/tools.php');

?>