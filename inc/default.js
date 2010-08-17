/* (c) 2010 David <grannost@gmail.com>
 * SEE THE LICENSE FILE FOR DETAILS */

var a = activate = 'activate';
var s = suspend = 'suspend';
var r = remove = 'remove';
var n = 'new'; // new is reserved :(
var l = login = 'login';
var working;
  
function d(id, action) {

  switch (action) {
    case a:
    case s:
    case r:
      var url = base + 'inc/backend.php?id=' + id + '&action=' + action;
      $('#status-' + id).html('?');
      $('#buttons-' + id).html('...');
      break;
    case n:
      if (working)
        return false;
      var long = $('#long').val();
      var short = $('#short').val();
      $('#newurl').removeAttr('class');
      if ($('#newurl').html() == 'Move along :)') // animate only the first time
        $('#newurl').animate({ opacity: 1 }, 100);
      $('#newurl').html('Wait...');
      $('#shorten').attr('class', 'disabled');
      var url = base + 'inc/backend.php?action=' + action + '&short=' + encodeURIComponent(short) + '&long=' + encodeURIComponent(long);
      working = 1;
      break;
    case l:
      if (working)
        return false;
      var nick = $('#nick').val();
      var password = $('#password').val();
      $('#newurl').removeAttr('class');
      if ($('#newurl').html() == 'Move along :)') // animate only the first time
        $('#newurl').animate({ opacity: 1 }, 100);
      $('#newurl').html('Wait...');
      $('#login').attr('class', 'disabled');
      $('#nick').attr('disabled', 'disabled');
      $('#password').attr('disabled', 'disabled');
      var url = base + 'inc/backend.php?action=' + action + '&nick=' + encodeURIComponent(nick) + '&password=' + encodeURIComponent(password);
      working = 1;
      break;
    default:
      break;
  }
  
  $.getJSON(url,
    function(data) {
      if (data.error) {
        switch (action) {
          case l:
          case n:
            $('#newurl').attr('class', 'error');
            $('#newurl').html(data.error);
            if (action == n)
              var button = '#shorten';
            else {
              var button = '#login';
              $('#nick').removeAttr('disabled');
              $('#password').removeAttr('disabled');
            }
            $(button).removeAttr('class');
            working = 0;
            break;
          default:
            alert('Error: ' + data.error);
        }
        return false;
      }
      switch (action) {
        case r:
          if (data.removed == id) {
            $('#link-' + id).attr('class', 'removed');
            $('#status-' + id).html('<span class="removed">D</span>');
            $('#buttons-' + id).html(':-(');
          } else
            alert('Error: this ain\'t possible!');
          break;
        case a:
        case s:
          $('#status-' + id).html(data.status);
          $('#buttons-' + id).html(data.buttons);
          break;
        case n:
          $('#newurl').html('<a href="' + data.newurl + '">' + data.newurl + '</a>');
          $('#shorten').removeAttr('class');
          working = 0;
          break;
        case l:
          $('#newurl').html('All right, redirecting...');
          location.href = base + 'admin/';
          break;
        default:
          alert('wut?');
      }
    }
  );
}

$(function() {
  $('#shorten').click(function() {
    d(-1, n);    
  });
  $('#login').click(function() {
    d(-1, l);    
  });
});