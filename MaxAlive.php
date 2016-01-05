<?php

class MaxAlive {

  // ================== ================== ================== ================== ================== ================== 

  public function __construct($arr) {
    $this->dataset = $arr;
    
    $birth = [];
    $end = [];
    foreach ($this->dataset as $value) {
      $birth[$value[0]] ++;
      $end[$value[1]] ++;
    }
    ksort($birth);
    ksort($end);

    foreach ($birth as $key => $value) {
      $this->birth[] = [$key, $value];
    }
    foreach ($end as $key => $value) {
      $this->end[] = [$key, $value];
    }
  }

  // ================== ================== ================== ================== ================== ================== 

  public function getMaxAlive() {
    if (empty($this->maxAlive['year'])) {
      $this->ploting();
    }
    return $this->maxAlive;
  }

  // ================== ================== ================== ================== ================== ================== 

  public function getDataset() {
    return $this->dataset;
  }

  // ================== ================== ================== ================== ================== ================== 

  public function getDataChart() {
    if (empty($this->dataChart)) {
      $this->ploting();
    }
    return $this->dataChart;
  }

  // ================== ================== ================== ================== ================== ================== 

  private function ploting() {
    $ctBirth = count($this->birth);
    $ctEnd = count($this->end);
    $currentAlive = 0;
    $i = 0;
    $j = 0;
    while ($i < $ctBirth || $j < $ctEnd) {
      if ($i < $ctBirth && $this->birth[$i][0] < $this->end[$j][0]) {
        $currentAlive += $this->birth[$i][1];

        if ($this->maxAlive['alive'] < $currentAlive) {
          $this->maxAlive['year'] = $this->birth[$i][0];
          $this->maxAlive['alive'] = $currentAlive;
        }

        $this->dataChart['year'][] = $this->birth[$i][0];
        $this->dataChart['alive'][] = $currentAlive;

        $i++;
      } elseif ($j < $ctEnd) {
        $currentAlive -= $this->end[$j][1];

        $this->dataChart['year'][] = $this->end[$j][0];
        $this->dataChart['alive'][] = $currentAlive;

        $j++;
      }
    }
  }

  // ================== ================== ================== ================== ================== ================== 

  private $dataset = [];
  private $maxAlive = ['year' => 0, 'alive' => 0];
  private $dataChart = [];
  private $birth = [];
  private $end = [];

  // ================== ================== ================== ================== ================== ================== 
}
