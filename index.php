<?php
  ini_set('error_reporting', E_ALL);
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);

  //counting variabels in template wich we should replace
  function count_variables($template){
    $varriables_arr= [];
    $remove_symbols=["%"=>""];
    foreach ($template as $val) {
      $split_val = explode(" ",$val);                                                 // spliting our string to words
      foreach ($split_val as $str) {
        if(preg_match('/%\D{1,255}%/',$str)){                                // check if word is template variable
          array_push($varriables_arr,strtr(substr(trim($str),0,-1),$remove_symbols)); // to get clear variable name;
        };
      }
    }
    return $varriables_arr;
  }

  //replacing variabels in template while this script will be runing on browser
  function replacing($template, $variable_array){
    $result = "";
    foreach ($template as $tmpl){
      foreach ($variable_array as $variable){
        if($variable != "EXECDATE" && $variable != "ENDDATE"){
          $tmpl=str_replace("%" . $variable . "%",$_POST["$variable"],$tmpl);
        }
        else {
          $run_date = date("d.m.Y H:i:s");
          $end_date = date_create();
          date_modify($end_date, $_POST["MONTHNUM"] . "month");
          $end_date = date_format($end_date, "d.m.Y");

          $tmpl=str_replace("%EXECDATE%",$run_date,$tmpl);
          $tmpl=str_replace("%ENDDATE%",$end_date,$tmpl);
        }
      }
    $result=$result .  $tmpl . "<br>";
    }
    return $result;
  }

  //replacing variabeles in template while this script will be runing on cli mod
  function replacing_cli($template, $variable_array,$argv){
    $result = "";
    $i=1;                                                         // index to get elements from $argv array
    foreach ($template as $tmpl){
      foreach ($variable_array as $variable){
        if($variable != "EXECDATE" && $variable != "ENDDATE"){
          $tmpl=str_replace("%" . $variable . "%",$argv[$i],$tmpl);
          $i++;
        }
        else {
          $run_date = date("d.m.Y H:i:s");
          $end_date = date_create();
          date_modify($end_date, $argv[3] . "month");
          $end_date = date_format($end_date, "d.m.Y");

          $tmpl=str_replace("%EXECDATE%",$run_date,$tmpl);
          $tmpl=str_replace("%ENDDATE%",$end_date,$tmpl);
        }
      }
    $i=1;
    $result=$result .  $tmpl;
    }
    return $result;
  }

    $error = [];

    if(isset($argv) === true){                                        // if script was run from cli;
      $tpl = file('template.tpl');

      //cheking for erros
      if(count(count_variables($tpl)) > count($argv)+1){
        array_push($error,"You missed some arguments");
      }
      else if(count(count_variables($tpl)) < count($argv)+1) {
        array_push($error,"You wrote some extra arguments arguments");
      }

      if(is_numeric($argv[3]) === false) {
        array_push($error," 3rd argument should be number");
      }

      //if there is an erorr
      if(empty($error) === false){
        foreach ($error as $err) {
          echo  $err . "\n";
        }
        return;
      }
      else {                                                      //if there is no any erorr
        // Replacing with neeeded values
        $text =  replacing_cli($tpl,count_variables($tpl),$argv);
        echo $text;                                               //to make echo into cli (if script was started from browser echo will be made in massage.php)

      }
    }
    else if($_SERVER['REQUEST_METHOD'] === 'POST'){               //if script was run from browser
      $tpl = file('template.tpl');

      // checking for errors
      foreach (count_variables($tpl) as $value) {
        if($value == "EXECDATE" || $value == "ENDDATE"){
          continue;
        }
        if($value == "MONTHNUM" && is_numeric($_POST["$value"]) === false) {
          array_push($error,$value ." should be number");
          continue;
        }
        if(empty($_POST["$value"]) === true && $_POST["$value"] !== "0"){
          array_push($error,"No " . $value ." written!");
        }
      }

      // if there is some erorrs
      if(empty($error) === false){
        return;
      }
      else {                                                      //if no any errors run main part
        // Replacing with neeeded values
        $text =  replacing($tpl,count_variables($tpl));
      }
    }
    else {                                                        // if index.php runed from browser 1st time with no data in post.
      $tpl = file('template.tpl');
      $fields = count_variables($tpl);
      include('form.php');
    }
