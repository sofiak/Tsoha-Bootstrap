<?php

  $routes->get('/', function() {
  	if ($user = AuthController::get_user_logged_in()) {
 		AppController::index();
  	} else {
  		AuthController::index();
  	}
    
  });

  $routes->post('/login', function(){
  // Kirjautumisen käsittely
   AuthController::handle_login();
  });

  $routes->get('/logout', function() {
    AuthController::signout();
  });

  $routes->get('/signup', function() {
    AuthController::signup();
  });

  $routes->post('/signup', function() {
  	AuthController::handle_signup();
  });

  $routes->get('/tasks/create', function() {
  	AppController::createTask();
  });

  $routes->post('/tasks/create', function() {
  	AppController::storeTask();
  });

  $routes->get('/tasks/task/:id', function($id) {
  	AppController::showTask($id);
  });

  $routes->get('/tasks/edit/:id', function($id) {
  	AppController::editTask($id);
  });

  $routes->post('/tasks/edit/:id', function($id) {
  	AppController::updateTask($id);
  });

  $routes->get('/tasks/delete/:id', function($id) {
  	AppController::deleteTask($id);
  });

  $routes->get('/hiekkalaatikko', function() {
    AuthController::sandbox();
  });
