<?php
  ini_set('error_reporting', E_ALL);
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);

  function count_variables($template){
    $varriables_arr= array();
    $remove_symbols=array("%"=>"");
    foreach ($template as $val) {
      $split_val = explode(" ",$val); // spliting our string to words
      foreach ($split_val as $str) {
        if(preg_match('/%\D{1,255}%/',$str)){ // check if word is template variable
          array_push($varriables_arr,strtr(substr(trim($str),0,-1),$remove_symbols)); //to get clear variable name;
        };
      }
    }
    return $varriables_arr;
  }

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

    $error = array();

    if($_POST){ //if script was run from browser

      //checking for errors
      // if(!$_POST['username']){
      //   array_push($error,"No username written!");
      // }
      // if(!$_POST['number']){
      //   array_push($error,"No number written!");
      // }
      // if(!$_POST['month_num']){
      //     array_push($error,"No month written!");
      // }
      //
      // // if there is some erorrs
      // if(!empty($error)){
      //   foreach ($error as $err) {
      //     echo "<p class='error'>". $err . "</p>";
      //   }
      //   echo "<button class='goBack'><a href='./index.php'>Go back</a></dutton>";
      //   return;
      // }
      // else {                             //if no any errors run main part
        // $username = $_POST['username'];
        // $number = $_POST['number'];
        // $month_num = $_POST['month_num'];

        $file = fopen('template.tpl', 'r');
        $tpl = file('template.tpl');


        // Replacing with neeeded values
          $text =  replacing($tpl,count_variables($tpl));
      // }
    }
    // else if(isset($argv)){ // if script was run from cli;
    //
    //   //cheking for erros
    //   if(!$argv[1]){
    //     array_push($error,"No username written!");
    //   }
    //   if(!$argv[2]){
    //     array_push($error,"No number written!");
    //   }
    //   if(!$argv[3]){
    //       array_push($error,"No month written!");
    //   }
    //
    //   //if there is an erorr
    //   if(!empty($error)){
    //     foreach ($error as $err) {
    //       echo  $err . "\n";
    //     }
    //     return;
    //   }
    //   else {                        //if there is no any erorr
    //     $username = $argv[1];
    //     $number = $argv[2];
    //     $month_num = $argv[3];
    //
    //     $file = fopen('template.tpl', 'r');
    //     $tpl = file('template.tpl');
    //
    //     // Date calculating
    //     $run_date = date("d.m.Y H:i:s");
    //     $end_date = date_create();
    //     date_modify($end_date, $month_num . "month");
    //     $end_date = date_format($end_date, "d.m.Y");
    //
    //     // Replacing with neeeded values
    //
    //     $text=""; //result string will be placed here
    //
    //     foreach ($tpl as $val) {
    //       $val=str_replace("%USERNAME%",$username,$val);
    //       $val=str_replace("%NUMBER%",$number,$val);
    //       $val=str_replace("%MONTHNUM%",$month_num ,$val);
    //
    //       $val=str_replace("%EXECDATE%",$run_date ,$val);
    //       $val=str_replace("%ENDDATE%",$end_date ,$val);
    //       $text = $text .  $val;
    //     }
    //     echo $text; //to make echo into cli (if script was started from browser echo will be made in massage.php)
    //
    //   }
    // }
    else {
      $file = fopen('template.tpl', 'r');
      $tpl = file('template.tpl');
      $fields = count_variables($tpl);

      include('form.php');
    }
