<?php

if (!empty($_POST)) {

  require_once 'database.php';
  require_once 'Player.php';

  $data = filter_input_array(INPUT_POST);
  try {
    $player = new Player($db, $data['playerId']);
    $player->recordSpin($data);
  } catch (Exception $ex) {
    die(json_encode(['error' => $ex->getMessage()]));
  }

  echo json_encode([
      'PlayerID' => $player->getId(),
      'Name' => $player->getName(),
      'Credits' => $player->getCredits(),
      'LifetimeSpins' => $player->getLifetimeSpins(),
      'LifetimeAverageReturn' => $player->getLifetimeAverageReturn()
  ]);
}