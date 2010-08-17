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
global $config;

$result = mysql_query("SELECT id, short_url, long_url FROM urls");

$short = get_params($_SERVER['PATH_INFO']);

switch ($short[0]) {
  case 'view':
    echo 'wow!!!';
    break;
  default:
    do_header('administration panel - '.$config['shortener'], true);

    echo '<h3>URLs created</h3><p>Ordered by creation date. Legend:</p>';

    echo '<ul>';
    echo '<li><span class="active">A</span> - Active/Activate</li>';
    echo '<li><span class="suspended">S</span> - Suspended/Suspend temporarily</li>';
    echo '<li><span class="removed">R</span> - Remove permanently</li>';
    echo '</ul>';

    $query = mysql_query("SELECT id, short_url, long_url, ip, date, status FROM urls ORDER BY date");

    if (empty($result))
      echo 'No URLs created yet.';
    else {
      echo '<table>';
      echo '<tr><th>ID</th><th>Short</th><th>Long</th><th>IP</th><th>Date</th><th title="Status">S</th><th title="Actions">A</th></tr>';

      while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
        echo '<tr id="link-'.$row['id'].'">';
        echo '<td>'.$row['id'].'</td>';
        echo '<td><a href="'.$config['base'].$row['short_url'].'">'.$row['short_url'].'</td>';
        echo '<td><a href="'.$row['long_url'].'">'.short($row['long_url']).'</td>';
        echo '<td>'.$row['ip'].'</td>';
        echo '<td>'.$row['date'].'</td>';

        echo '<td id="status-'.$row['id'].'">';
        switch ($row['status']) {
          case 'active':
            echo '<span title="Active" class="active">A</span>';
            break;
          case 'suspended':
            echo '<span title="Suspended" class="suspended">S</span>';
            break;
          default:
            echo '<span title="Unknown">?</span>';
        }
        echo '</td>';

        echo '<td id="buttons-'.$row['id'].'">';
        if ($row['status'] == 'active')
          echo '<a href="#j" title="Suspend" onclick="d('.$row['id'].', s)">S</a>';
        elseif ($row['status'] == 'suspended')
          echo '<a href="#j" title="Activate" onclick="d('.$row['id'].', a)">A</a>';
        echo ' <a href="#j" title="Permanently remove" onclick="d('.$row['id'].', r)">R</a>';
        echo '</td>';

        echo '</tr>';
      }
    }

    echo '</table>';

    do_footer();
}

?>