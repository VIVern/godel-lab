<?php
  ini_set('error_reporting', E_ALL);
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);

  //counting variabels in template wich we should replace
  function count_variables($template)
  {
    $varriablesArr= [];
    $removeSymbols=["%" => ""];
    foreach ($template as $val) {
      $split_val = explode(" ", $val);                                                  // spliting our string to words
      foreach ($split_val as $str) {
        if (preg_match('/%\D{1,255}%/', $str)) {                                        // check if word is template variable
          array_push($varriablesArr, strtr(substr(trim($str), 0, -1), $removeSymbols)); // to get clear variable name;
        };
      }
    }
    return $varriablesArr;
  }

  //replacing variabels in template while this script will be runing on browser
  function replacing($template, $variableArray, $scope, $arr = null)
  {
    $result = "";
    $i = 1;

    function browserOrCli($scope, $variable, $i, $arr=null)                 // to know how replacing() should work while replace variables
    {
      if ($scope === "browser") {
        if ($variable === "EXECDATE" || $variable === "ENDDATE") {
          return $_POST["MONTHNUM"];
        }
        return $_POST[$variable];                                            // to know how replacing() should work while replace date
      }
      else {
        if ($variable === "EXECDATE" || $variable === "ENDDATE") {
          return $arr[3];
        }
        return $arr[$i];
      }
    }

    foreach ($template as $tmpl) {
      foreach ($variableArray as $variable) {
        if ($variable !== "EXECDATE" && $variable !== "ENDDATE") {
          $tmpl=str_replace("%" . $variable . "%", browserOrCli($scope, $variable, $i, $arr), $tmpl);
          $i++;
        }
        else {
          $runDate = date("d.m.Y H:i:s");
          $endDate = date_create();
          date_modify($endDate, browserOrCli($scope, $variable, $i, $arr) . "month");
          $endDate = date_format($endDate, "d.m.Y");

          $tmpl = str_replace("%EXECDATE%", $runDate,$tmpl);
          $tmpl = str_replace("%ENDDATE%", $endDate,$tmpl);
        }
      }

      $i = 1;

      if ($scope === "cli") {
        $result = $result .  $tmpl;
      }
      elseif ($scope === "browser") {
        $result = $result .  $tmpl . "<br>";
      }
    }
    
    return $result;
  }

  $error = [];
  $scope = "";
  $tpl = file('template.tpl');

  if (isset($argv) === true){                                        // if script was run from cli;
    $scope = "cli";                                                  // helps replace() understand where script is runing


    //cheking for erros
    if (count(count_variables($tpl)) > count($argv) + 1) {
      array_push($error, "You missed some arguments");
    }
    else if (count(count_variables($tpl)) < count($argv) + 1) {
      array_push($error, "You wrote some extra arguments arguments");
    }

    if (is_numeric($argv[3]) === false) {
      array_push($error, " 3rd argument should be number");
    }

    //if there is an erorr
    if (empty($error) === false) {
      foreach ($error as $err) {
        echo  $err . "\n";
      }
      return;
    }
    else {                                                      //if there is no any erorr
      // Replacing with neeeded values
      $text =  replacing($tpl, count_variables($tpl), $scope, $argv);
      echo $text;                                               //to make echo into cli (if script was started from browser echo will be made in massage.php)

    }
  }
  else if ($_SERVER['REQUEST_METHOD'] === 'POST') {               //if script was run from browser
    $scope = "browser";

    // checking for errors
    foreach (count_variables($tpl) as $value) {
      if ($value === "EXECDATE" || $value === "ENDDATE") {
        continue;
      }
      if (empty($_POST[$value]) === true && $_POST[$value] !== "0") {
        array_push($error, "No " . $value . " written!");
      }
      else if ($value === "MONTHNUM" && is_numeric($_POST[$value]) === false) {
        array_push($error, $value . " should be number");
        continue;
      }
    }

    // if there is some erorrs
    if (empty($error) === false) {
      return;
    }
    else {                                                      //if no any errors run main part
      // Replacing with neeeded values
      $text =  replacing($tpl, count_variables($tpl), $scope);
    }
  }
  else {                                                        // if index.php runed from browser 1st time with no data in post.
    $fields = count_variables($tpl);
    include('form.php');
  }
