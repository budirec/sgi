<?php

function generateDataset($n = 100, $min = 1900, $max = 2000) {
  $range = $max - $min;
  $ret = [];

  for ($i = 0; $i < $n; $i++) {
    $birth = $min + rand(0, $range - 1);
    $end = rand($birth + 1, $max);
    $ret[] = [$birth, $end];
  }

  return $ret;
}

if ($_GET['n'] && $_GET['minYear'] && $_GET['maxYear']) {
  $data = generateDataset($_GET['n'], $_GET['minYear'], $_GET['maxYear']);
} else {
  $data = generateDataset();
}

require_once 'MaxAlive.php';
$maxAlive = new MaxAlive($data);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SGI - Test</title>

    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    
    <style>
      label span {
        display: inline-block;
        width: 100px;
      }
    </style>
  </head>
  <body>
    <section>
      <header><h1>Problem 1: <small>Get year where the most number of people alive.</small></h1></header>

      <p>Dataset:</p>
      <div id="dataset"></div>
      <div id="graph" style="width: 95%; margin: 0 auto"></div>

      <footer>
        <p>
          The year with the most number of people alive is: <strong id="yearMax"></strong> with <em id="aliveMax"></em> people.
        </p>
      </footer>

      <script>
        // Ajax ready
        var dataYear = <?= json_encode($maxAlive->getDataChart()['year']); ?>;
        var dataAlive = [{name: 'year', data: <?= json_encode($maxAlive->getDataChart()['alive']); ?>}];
        var dataset = <?= json_encode($maxAlive->getDataset()); ?>;
        var yearMax = <?= $maxAlive->getMaxAlive()['year']; ?>;
        var aliveMax = <?= $maxAlive->getMaxAlive()['alive']; ?>;
      </script>
      <script src="MaxAlive.js"></script>
    </section>


    <section>
      <?php
      require_once 'database.php';
      /* @var $db PDO */

      $sql = 'SELECT player_id, name, salt_value FROM player';
      $players = $db->query($sql)->fetchAll(PDO::FETCH_OBJ);
      ?>
      <header><h1>Problem 2: <small>Basic spin results end point</small></h1></header>

      <form action="SpinResult.php" method="post" id="form_pb2">
        <div><label><span>Bet</span><input type="number" name="bet" id="pb2Bet"></label></div>
        <input type="hidden" name="salt" id="pb2Salt">
        <div>
          <h3>Spinner</h3>
          <?php foreach ($players as $player) : ?>
            <button type="submit" name="playerId" class="playerId" value="<?= $player->player_id; ?>" 
                    onclick="$('#pb2Salt').val('<?= $player->salt_value; ?>')">
              <?= $player->name; ?>
            </button>
          <?php endforeach; ?>
        </div>
        <div>
          <h3>Simulate:</h3>
          <input type="hidden" name="simId" value="<?= $player->player_id; ?>" id="simId">
          <button type="submit" name="wrong" value="win">Wrong win(negative number)</button>
          <button type="submit" name="wrong" value="id">Wrong Player ID</button>
          <button type="submit" name="wrong" value="hash">Wrong hash</button>
        </div>
      </form>

      <footer>
        <ul id="spinHistories" style="height: 400px; overflow-y: scroll;"></ul>
      </footer>

      <script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha256.js"></script>
      <script src="Spins.js"></script>
    </section>

  </body>
</html>
