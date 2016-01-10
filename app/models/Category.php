<?php 

Class Category extends BaseModel{
	  // Attribuutit
	  public $id, $user_id, $title, $created_at, $modified_at;
	  // Konstruktori
	  public function __construct($attributes){
	  	parent::__construct($attributes);
    	$this->validators = array('validate_title');
	  }

	  public static function all($user_id)
	  {
	  	$query = DB::connection()->prepare("SELECT * FROM CATEGORIES WHERE user_id = :user_id ORDER BY title");
	  	$query->execute(array('user_id' => $user_id));
	  	$rows = $query->fetchAll();
	  	$categories = array();
	  	foreach ($rows as $row) {
	  		$categories[] = new Category(array(
	  			'id' => $row['id'],
	        	'user_id' => $row['user_id'],
	        	'title' => $row['title'],
	  			'created_at' => $row['created_at'],
	  			'modified_at' => $row['modified_at']
	  		));
	  	}

	  	return $categories;
	  }

	  public static function allFromTask($id)
	  {
	  	$query = DB::connection()->prepare("SELECT * FROM TASK_CATEGORY WHERE task_id = :task_id");
	  	$query->execute(array('task_id' => $id));
	  	$rows = $query->fetchAll();
	  	$categories = array();
	  	foreach ($rows as $row) {
	  		$categories[] = $row['category_id'];
	  	}

	  	return $categories;
	  }

	  public static function find($id)
	  {
	  	$query = DB::connection()->prepare("SELECT * FROM CATEGORIES WHERE id = :id LIMIT 1");
	  	$query->execute(array('id' => $id));
	  	$row = $query->fetch();
	  	if ($row) {
	  		$category = new Category(array(
	  			'id' => $row['id'],
	        	'user_id' => $row['user_id'],
	        	'title' => $row['title'],
	  			'created_at' => $row['created_at'],
	  			'modified_at' => $row['modified_at']
	      ));
	  		return $category;
	  	} else {
	  		return null;
	  	}
	  }

      public function save() 
   {
	   	if (property_exists($this, 'id') AND $this->id) {
	   			
	   		$query = DB::connection()->prepare('UPDATE CATEGORIES SET user_id = :user_id, title = :title, modified_at = now() WHERE id = :id');
	   		$query->execute(array('user_id' => $this->user_id, 'title' => $this->title, 'id' => $this->id));

	   		return $this->id;

	   	} else {

	   		$query = DB::connection()->prepare('INSERT INTO CATEGORIES (user_id, title, created_at, modified_at) VALUES (:user_id, :title, now(), now()) RETURNING id');
	   		
	   		$query->execute(array('user_id' => $this->user_id, 'title' => $this->title));
	   		
	   		$row = $query->fetch();
	   		
	   		$this->id = $row['id'];

	   		return $this->id;
	   	}
  }

	  public function update()
	  {
	     $query = DB::connection()->prepare('UPDATE CATEGORIES SET user_id = :user_id, title = :title, modified_at = now() WHERE id = :id');
	        $query->execute(array('user_id' => $this->user_id, 'title' => $this->title, 'id' => $this->id));

	     return $this->id;
	  }

	  public function destroy()
	  {

	  	$query = DB::connection()->prepare('DELETE FROM TASK_CATEGORY WHERE category_id = :id');
	  	$query->execute(array('id' => $this->id));
	  	
	  	
	  	$query = DB::connection()->prepare('DELETE FROM CATEGORIES WHERE id = :id');
	  	$query->execute(array('id' => $this->id));

	  	return true;
	  }

	 protected function validate_title(){

	   $errors = array();

	   $validator_errors = $this->validate_string_length($this->title, 'Nimi');
	   $errors = array_merge($errors, $validator_errors);

	   return $errors;
	 }
}