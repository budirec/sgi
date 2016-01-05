<?php

class Player {

  // ================ ================ ================ ================ ================ ================ =============

  public function __construct(PDO $db, $id = NULL) {
    $this->db = $db;
    if ($id === NULL) {
      throw new Exception('Player can\'t be found.');
    } else {
      $sql = 'SELECT * FROM `player` WHERE `player_id` = ?';
      $ps_player = $this->db->prepare($sql);
      $ps_player->execute([$id]);
      if ($ps_player->rowCount() == 0) {
        throw new Exception('Player can\'t be found.');
      } else {
        $player = $ps_player->fetch(PDO::FETCH_OBJ);
        $this->playerId = $id;
        $this->name = $player->name;
        $this->credits = $player->credits;
        $this->lifetimeWons = $player->lifetime_wons;
        $this->lifetimeSpins = $player->lifetime_spins;
        $this->saltValue = $player->salt_value;
      }
    }
  }

  // ================ ================ ================ ================ ================ ================ =============

  public function recordSpin($data) {
    $msg = $this->confirmData($data);
    if (empty($msg)) {
      $won = ($data['won'] - $data['bet']);
      $this->credits += $won;
      $this->lifetimeWons += ($won > 0 ? $won : 0);
      $this->lifetimeSpins++;

      $temp = $this->updateData();
      if ($temp === TRUE) {
        return TRUE;
      }

      throw new Exception('ERROR: ' . $temp . PHP_EOL);
    }

    error_log('Possible hack attempt. IP=' . $_SERVER['REMOTE_ADDR'] . '; Data=' . json_encode($data));
    throw new Exception($msg);
  }
 
  // ================ ================ ================ ================ ================ ================ =============

  private function confirmData($data) {
    $msg = '';

    $temp = $this->sanitizeBet($data['bet']);
    if ($temp !== TRUE) {
      $msg .= 'ERROR: ' . $temp . PHP_EOL;
    }

    $temp = $this->sanitizeWon($data['won']);
    if ($temp !== TRUE) {
      $msg .= 'ERROR: ' . $temp . PHP_EOL;
    }

    if (empty($msg)) {
      $hash = hash('sha256', $this->playerId . $data['bet'] . $data['won'] . $this->saltValue);
      if ($data['hash'] != $hash) {
        $msg .= 'ERROR: Hash didn\'t matched.' . PHP_EOL;
      }
    }

    return $msg;
  }

  // ================ ================ ================ ================ ================ ================ =============

  private function updateData() {
    $sql = 'UPDATE `player` '
            . 'SET `credits` = :credits, `lifetime_wons` = :lifetimeWons, `lifetime_spins` = :lifetimeSpins '
            . 'WHERE `player_id` = :playerId';
    $pu_player = $this->db->prepare($sql);
    if (
            $pu_player->execute(
                    [
                        'playerId' => $this->playerId,
                        'credits' => $this->credits,
                        'lifetimeWons' => $this->lifetimeWons,
                        'lifetimeSpins' => $this->lifetimeSpins
                    ]
            )
    ) {
      return TRUE;
    }

    return 'ERROR: Fail to update database.' . PHP_EOL;
  }

  // ================ ================ ================ ================ ================ ================ =============

  private function sanitizeWon($won) {
    if (!is_numeric($won)) {
      return 'Won is not a number.';
    } elseif ($won < 0) {
      return 'Won is negative.';
    }

    return TRUE;
  }

  // ================ ================ ================ ================ ================ ================ =============

  private function sanitizeBet($bet) {
    if (!is_numeric($bet)) {
      return 'Bet is not a number.';
    } elseif ($bet <= 0) {
      return 'Bet is less or equal to 0.';
    } elseif ($bet > $this->credits) {
      return 'Bet is larger than current credits.';
    }

    return TRUE;
  }

  // ================ ================ ================ ================ ================ ================ =============

  /**
   * Database connection
   * 
   * @var PDO
   */
  private $db;
  
  // ================ ================ ================ ================ ================ ================ =============

  //Player data
  private $playerId = '';
  private $name = '';
  private $credits = 0;
  private $lifetimeWons = 0;
  private $lifetimeSpins = 0;
  private $saltValue = '';

  // ================ ================ ================ ================ ================ ================ =============
  //Getters
  function getId() {
    return $this->playerId;
  }

  // ================ ================ ================ ================ ================ ================ =============
  
  function getName() {
    return $this->name;
  }

  // ================ ================ ================ ================ ================ ================ =============
  
  function getCredits() {
    return $this->credits;
  }
  
  // ================ ================ ================ ================ ================ ================ =============
  
  function getLifetimeSpins() {
    return $this->lifetimeSpins;
  }

  // ================ ================ ================ ================ ================ ================ =============
  
  function getLifetimeAverageReturn() {
    return $this->lifetimeWons / $this->lifetimeSpins;
  }
  
  // ================ ================ ================ ================ ================ ================ =============
  
}
