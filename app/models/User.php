<?php

class User extends BaseModel {

	// Attribuutit
  public $id, $username, $password, $created_at, $modified_at;
  // Konstruktori
  public function __construct($attributes = null){

  	if (is_array($attributes)) {
  		parent::__construct($attributes);
  	}
    $this->validators = array('validate_username', 'validate_password');
  }

  public function all()
  {
  	$query = DB::connection()->prepare("SELECT * FROM USERS");
  	$query->execute();
  	$rows = $query->fetchAll();
  	$users = array();
  	foreach ($rows as $row) {
  		$users[] = new User(array(
  			'id' => $row['id'],
  			'username' => $row['username'],
  			'password' => $row['password'],
  			'created_at' => $row['created_at'],
  			'modified_at' => $row['modified_at']
  		));
  	}

  	return $users;
  }

  public static function find($id)
  {
  	$query = DB::connection()->prepare("SELECT * FROM USERS WHERE id = :id LIMIT 1");
  	$query->execute(array('id' => $id));
  	$row = $query->fetch();
  	if ($row) {
  		$user = new User(array('id' => $row['id'], 'username' => $row['username'], 'password' => $row['password'], 'created_at' => $row['created_at'], 'modified_at' => $row['modified_at']));
  		return $user;
  	} else {
  		return null;
  	}
  }

  public static function authenticate($username, $password)
  {
  	$query = DB::connection()->prepare("SELECT * FROM USERS WHERE username = :username AND password = :password LIMIT 1");
  	$query->execute(array('username' => $username, 'password' => $password));
  	$row = $query->fetch();
  	if ($row) {
  		$user = new User(array('id' => $row['id'], 'username' => $row['username'], 'password' => $row['password'], 'created_at' => $row['created_at'], 'modified_at' => $row['modified_at']));
  		return $user;
  	} else {
  		return null;
  	}
  }

   public function save() 
   {
	   	
	   	if (property_exists($this, 'id') AND $this->id) {
	   			
	   		$query = DB::connection()->prepare('UPDATE USERS SET username = :username, password = :password, modified_at = now() WHERE id = :id');
	   		$query->execute(array('username' => $this->username, 'password' => $this->password, 'id' => $this->id));

	   		return $this->id;

	   	} else {
	   		$query = DB::connection()->prepare('INSERT INTO USERS (username, password, created_at, modified_at) VALUES (:username, :password, now(), now()) RETURNING id');
	   		
	   		$query->execute(array('username' => $this->username, 'password' => $this->password));
	   		
	   		$row = $query->fetch();
	   		
	   		$this->id = $row['id'];

	   		return $this->id;
	   	}
  }

  public function update()
  {
    $query = DB::connection()->prepare('UPDATE USERS SET username = :username, password = :password, modified_at = now() WHERE id = :id');
    $query->execute(array('username' => $this->username, 'password' => $this->password, 'id' => $this->id));

    return $this->id;
  }


  public function destroy()
  {
  	$query = DB::connection()->prepare('DELETE FROM USERS WHERE id = :id');
  	$query->execute(array('id' => $this->id));
  	return true;
  }

  protected function validate_username(){
    $errors = array();

    $validator_errors = $this->validate_string_length($this->username, 'Käyttäjätunnus');
    $errors = array_merge($errors, $validator_errors);

    return $errors;
  }

  protected function validate_password(){
    $errors = array();

    $validator_errors = $this->validate_string_length($this->password, 'Salasana', 6);
    $errors = array_merge($errors, $validator_errors);

    return $errors;
  }

  public function check_username_exists(){

    $query = DB::connection()->prepare("SELECT * FROM USERS WHERE lower(username) = :username LIMIT 1");
    $query->execute(array('username' => strtolower($this->username)));
    $row = $query->fetch();
    if ($row) {
      return true;
    } else {
      return false;
    }

  }
}