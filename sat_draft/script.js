if(PUNBB.env.user_is_guest != 1) {
  PUNBB.env.user_id = $('#navprofile a').attr('href').match(/\?id=(\d+)/)[1];
  PUNBB.env.topic_id = $('#post-form form, #brd-qpost form').attr('action').match(/\?tid=(\d+)/)[1];
  
  function gentoken(a) {
    $.ajax({
        url: '/misc.php',
        type: 'GET',
        cache : false,
        async: false,
        data: {
          action: 'gentoken',
          act: a
        },
        success: function(r) {
          PUNBB.env.token = r.token;
        },
        error: function(r) {
console.log(r);
        }
    });
  }
  
  function getsavedmess(uid, tid) {
    gentoken('getsavedmess');
    let data = {};
    data['csrf_token'] = PUNBB.env.token;
    data['uid'] = uid;
    if (tid) {
      data['tid'] = tid;
    }
    $.ajax({
      url: '/misc.php?action=getsavedmess',
      type: 'POST',
      data: data,
      success: function(r) {
        PUNBB.env.savedMess = r;
        if (tid && PUNBB.env.savedMess.length > 0) {
          $('#post-form textarea, #brd-qpost textarea')[0].value = PUNBB.env.savedMess[PUNBB.env.savedMess.length-1].message;
        }
        if (r.code == -3) {
console.log(r);
$('#post-form textarea, #brd-qpost textarea')[0].value = 'Loading error of saved messages';
        }
      },
      error: function(r) {
console.log(r);
$('#post-form textarea, #brd-qpost textarea')[0].value = 'Loading error of saved messages';
      }
    });
  }
  
  function saveMessage(user, mess, topic, name) {
    let data = {
      'csrf_token': PUNBB.env.token,
      'uid': user,
      'mess': mess
    };
    if (topic) {
      data['tid'] = topic;
    }
    if (name) {
      data['name'] = name;
    }
    $.ajax({
      url: '/misc.php?action=savemess',
      type: 'POST',
      data: data,
      success: function(r) {
        if (r.code == -3) {
          gentoken('savemess');
          clearTimeout(PUNBB.env.svmTimer);
          PUNBB.env.svmTimer = setTimeout(saveMessage(user, mess, topic, name), 1000);
        }
      },
      error: function(r) {
console.log(r);
//	gentoken('savemess');
        clearTimeout(PUNBB.env.svmTimer);
        PUNBB.env.svmTimer = setTimeout(saveMessage(user, mess, topic, name), 3000);
      }
    });
  }

  getsavedmess(PUNBB.env.user_id, PUNBB.env.topic_id);
  gentoken('savemess');
  $('#post-form textarea, #brd-qpost textarea').on('input', function() {
    clearTimeout(PUNBB.env.svmTimer);
    PUNBB.env.svmTimer = setTimeout(saveMessage(PUNBB.env.user_id, $('#post-form textarea, #brd-qpost textarea')[0].value, PUNBB.env.topic_id), 1000);
  });
}
