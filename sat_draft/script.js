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
console.log(r);
        PUNBB.env.savedMess = r;
        if (tid && PUNBB.env.savedMess.length == 1) {
          $('#post-form textarea, #brd-qpost textarea')[0].value = PUNBB.env.savedMess[0].message;
        }
      },
      error: function(r) {
console.log(r);
      }
    });
  }
  
  function saveMessage(user, mess, topic, name) {
    gentoken('savemess');
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
console.log(r);
      },
      error: function(r) {
console.log(r);
        PUNBB.env.svmTimer = setTimeout(saveMessage(user, mess, topic, name), 3000);
      }
    });
  }

}
