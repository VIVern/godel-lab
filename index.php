<?php
  ini_set('error_reporting', E_ALL);
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);

  $error = [];

  if (isset($argv)) { // if script was run from cli;

    //cheking for erros
    if (!$argv[1]) {
      array_push($error, "No username written!");
    }
    if (!$argv[2] && $argv[2] !== "0") {
      array_push($error, "No number written!");
    }
    if (!$argv[3] && $argv[2] !== "0") {
      array_push($error, "No month written!");
    }
    if (is_numeric($argv[3]) === false) {
      array_push($error, "Month should be number");
    }

    //if there is an erorr
    if (empty($error) === false) {
      foreach ($error as $err) {
        echo  $err . "\n";
      }
      return;
    }
    else {                        //if there is no any erorr
      $username = $argv[1];
      $number = $argv[2];
      $month_num = $argv[3];

      $tpl = file('template.tpl');

      // Date calculating
      $run_date = date("d.m.Y H:i:s");
      $end_date = date_create();
      date_modify($end_date, $month_num . "month");
      $end_date = date_format($end_date, "d.m.Y");

      // Replacing with neeeded values

      $text = ""; //result string will be placed here

      foreach ($tpl as $val) {
        $val = str_replace("%USERNAME%", $username,$val);
        $val = str_replace("%NUMBER%", $number,$val);
        $val = str_replace("%MONTHNUM%", $month_num ,$val);

        $val = str_replace("%EXECDATE%", $run_date ,$val);
        $val = str_replace("%ENDDATE%", $end_date ,$val);
        $text = $text .  $val;
      }
      echo $text; //to make echo into cli (if script was started from broser echo will be made in massage.php)

    }
  }
  else if ($_SERVER['REQUEST_METHOD'] === 'POST') { //if script was run from browser

    //checking for errors
    if (empty($_POST['username']) === true) {
      array_push($error, "No username written!");
    }
    if (empty($_POST['number']) === true && $_POST['number'] !== "0") {
      array_push($error, "No number written!");
    }
    if (empty($_POST['month_num']) === true && $_POST['month_num'] !== "0") {
      array_push($error, "No month written!");
    }
    if (is_numeric($_POST['month_num']) === false) {
      array_push($error, "Month should be number");
    }

    // if there is some erorrs
    if (empty($error) === false) {
      return;
    }
    else {                             //if no any errors run main part
      $username = $_POST['username'];
      $number = $_POST['number'];
      $month_num = $_POST['month_num'];

      $tpl = file('template.tpl');

      // Date calculating
      $run_date = date("d.m.Y H:i:s");
      $end_date = date_create();
      date_modify($end_date, $month_num . "month");
      $end_date = date_format($end_date, "d.m.Y");

      // Replacing with neeeded values

      $text = ""; //result string will be placed here

      foreach ($tpl as $val) {
        $val = str_replace("%USERNAME%", "<b>" . $username . "</b>", $val);
        $val = str_replace("%NUMBER%", "<b>" . $number . "</b>", $val);
        $val = str_replace("%MONTHNUM%", "<b>" . $month_num . "</b>", $val);

        $val = str_replace("%EXECDATE%", "<b>" . $run_date . "</b>", $val);
        $val = str_replace("%ENDDATE%", "<b>" .  $end_date . "</b>", $val);
        $text = $text .  $val . "<br>";
      }
    }
  }
  else {
    include('form.html');
  }
