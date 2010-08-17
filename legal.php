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

echo '<h3>Terms and legal info for '.$config['shortener'].'</h3>';

echo '<p>Insert your legal info here...</p>';

echo '<div class="clearit"></div>';

echo '<p class="leave"><a href="'.$config['base'].'">Go back to '.$config['shortener'].'</a></p>';

do_footer();

?>