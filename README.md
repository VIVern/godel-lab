# First Lab at Godel Technologies
this is first lab at Godel technologies. This programm gets template and replace variables in it with needed values.

## Geting started
There are two ways of run it.
first one:  
1)You need run web server  
2)clone this repository (master branch);  
3)place cloned files into /www/ folder  
4)run in browser http://localhost/  

second one:  
1)1)You need run web server  
2)clone this repository (master branch);  
3)place cloned files into /www/ folder  
4)run it on cli with comand "php index.php";  

## What to do in programm
if you run programm in browser:  
You will see form with variabels (USERNAME, NUMBER, etc). Inser neded values and press button. You gonna see the result.
NUMBER and MONTHNUM shoudl be numbers  

if you run programm in cli:  
You should run script with comand "php index.php val1 val2 val3".  

val1 => USERNAME  
val2 => NUMBER (should be number)  
val3 => MONTHNUM (should be number)  

## Extra version of programm
If you want to see extra version of programm you should install it like in (Getting started), but with AT-2 branch.
In this version you can add your variables to template.tpl using this syntax:  
%VARIABLE_NAME%  

In browser mod inputs will generate automaticly  
In cli mod you need run script with comand "php index.php val1 val2 val3 your_val", where  
val1 => USERNAME  
val2 => NUMBER (should be number)  
val3 => MONTHNUM (should be number)  
your_val=> will replace you variable;  

you can insert as many variables as you want.


## Author
Made by VIVern.
