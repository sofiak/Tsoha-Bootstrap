<?php
  
  require 'app/models/User.php';
  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  View::make('login.html');
    }

    public static function sandbox(){
    	$user = new User();
    	$user = $user->find(2);
    	$user->delete();
    	print_r($user->all());

      View::make('helloworld.html');
    }
  }
