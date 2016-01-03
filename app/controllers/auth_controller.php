<?php
  
  require 'app/models/User.php';
  class AuthController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	    View::make('login.html');
    }

    public static function handle_login(){
    $params = $_POST;

    $user = User::authenticate($params['username'], $params['password']);

    if(!$user){
      View::make('login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['username']));
    }else{
      $_SESSION['user'] = $user->id;

      Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $user->username . '!'));
    }
  }

    public static function signout(){
      unset($_SESSION['user']);

      Redirect::to('/', array('message' => 'Olet kirjautunut ulos'));
    }

    public static function signup(){
      if($user = self::get_user_logged_in()){
        Redirect::to('/');
      } else {
        View::make('signup.html');
      }
    }

    public static function sandbox(){
      View::make('helloworld.html');
    }

    public static function handle_signup(){

      $params = $_POST;

      $user = new User(array('username' => $params['username'], 'password' => $params['password']));

      $errors = $user->errors();

      if(count($errors) > 0){
         Redirect::to('/signup', array('errors' => $errors));
      }

      if ($user->check_username_exists()) {
          Redirect::to('/signup', array('errors' => array('Käyttäjätunnus on jo olemassa')));
      } 

      $user->save();

      $_SESSION['user'] = $user->id;

      Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $user->username . '!'));

    }
  }
