<?php

  function  check_logged_in () { 
  		BaseController::check_logged_in(); 
	}

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

  $routes->get('/tasks/create', 'check_logged_in' ,function() {
  	AppController::createTask();
  });

  $routes->post('/tasks/create', 'check_logged_in' ,function() {
  	AppController::storeTask();
  });

  $routes->get('/tasks/task/:id', 'check_logged_in',  function($id) {
  	AppController::showTask($id);
  });

  $routes->get('/tasks/complete/:id', 'check_logged_in', function($id) {
  	AppController::markComplete($id);
  });

  $routes->get('/tasks/incomplete/:id', 'check_logged_in',  function($id) {
  	AppController::markInComplete($id);
  });

  $routes->get('/tasks/edit/:id', 'check_logged_in', function($id) {
  	AppController::editTask($id);
  });

  $routes->post('/tasks/edit/:id', 'check_logged_in', function($id) {
  	AppController::updateTask($id);
  });

  $routes->get('/tasks/delete/:id', 'check_logged_in', function($id) {
  	AppController::deleteTask($id);
  });

  $routes->get('/hiekkalaatikko', function() {
    AuthController::sandbox();
  });

  $routes->get('/categories/create', 'check_logged_in', function() {
  	CategoriesController::createCategory();
  });

  $routes->post('/categories/create', 'check_logged_in', function() {
  	CategoriesController::storeCategory();
  });

  $routes->get('/categories/delete/:id', 'check_logged_in', function($id) {
  	CategoriesController::deleteCategory($id);
  });

  $routes->get('/tasks/category/:id', 'check_logged_in', function($id) {
  	AppController::categoryTasks($id);
  });

  $routes->post('/tasks/search', 'check_logged_in', function() {
  	AppController::search();
  });
