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

do_header();

global $config;

echo '<h3>'.$config['shortener'].' URL shortener</h3>';

echo '<p>Shorten an URL easily!</p>';

echo '<dl>';
echo '<dt>Long URL:</dt><dd><input type="text" id="long"/></dd>';
echo '<dt>Short URL:</dt><dd><input type="text" class="short" id="short" maxlength="'.$config['max_short_length'].'"/></dd>';
echo '</dl>';

echo '<p>Leave the "Short URL" blank if you want to generate a random one.</p>';

echo '<p id="newurl">Move along :)</p>';

echo '<a id="shorten" href="#j">Make it short!</a>';

echo '<div class="clearit"></div>';

do_footer();

?>