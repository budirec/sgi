$(function () {
  var histories = $('#spinHistories');

  function processResponse(data) {
    var str = '';

    if (!data.error) {
      str += '<p>Player ID: ' + data.PlayerID.toString() + '</p>';
      str += '<p>Name: ' + data.Name.toString() + '</p>';
      str += '<p>Credits: ' + data.Credits.toString() + '</p>';
      str += '<p>Lifetime Spins: ' + data.LifetimeSpins.toString() + '</p>';
      str += '<p>Lifetime Average Return: ' + data.LifetimeAverageReturn.toString() + '</p>';
    } else {
      str += data.error;
    }

    histories.prepend('<li>Answer from server: ' + str + '</li>');
  }

  function calcWon(bet) {
    return bet * Math.round(Math.random() * 4);
  }

  var submitBtn = null;
  var $form = $('#form_pb2');
  var submitBtns = $form.find('button[type=submit]');

  $form.submit(function (evt) {
    evt.preventDefault();

    var url = $(this).attr('action');

    var postData = {'bet': 0, 'won': 0, 'hash': '', 'playerId': ''};
    postData.bet = parseInt($('#pb2Bet').val()) || 0;
    postData.won = calcWon(postData.bet);

    if (submitBtn === null) {
      submitBtn = submitBtns[0];
    }

    if (submitBtn.attr('name') == 'wrong') {
      postData.playerId = $('#simId').val();
      switch (submitBtn.val()) {
        case 'win':
          postData.won = -1;
          histories.prepend('<li>Simulating wrong win value(win = -1)</li>');
          break;
        case 'id':
          postData.playerId = 0;
          histories.prepend('<li>Simulating wrong Player ID(playerId = 0)</li>');
          break;
        case 'hash':
          postData.bet = 1;
          postData.hash = -1;
          histories.prepend('<li>Simulating wrong hash value(hash = -1)</li>');
          break;
      }
    } else {
      postData.playerId = submitBtn.val();
    }

    var str = '';
    if (postData.hash === '') {
      str += postData.playerId.toString();
      str += postData.bet.toString();
      str += postData.won.toString();
      str += $('#pb2Salt').val();
      postData.hash = CryptoJS.SHA256(str).toString();
    }

    str = '<p>Player ID: ' + postData.playerId.toString() + '</p>';
    str += '<p>Bet: ' + postData.bet.toString() + '</p>';
    str += '<p>Won: ' + postData.won.toString() + '</p>';
    str += '<p>Hash: ' + postData.hash.toString() + '</p>';

    histories.prepend('<li>Send data to server.' + str + '</li>');
    $.post(url, postData, processResponse, 'json');
  });

  submitBtns.click(function (evt) {
    submitBtn = $(this);
  });


});