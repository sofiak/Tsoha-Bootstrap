<?php

class Task extends BaseModel {

	// Attribuutit
  public $id, $title, $user_id, $description, $priority, $due_date, $status, $categories, $created_at, $modified_at;
  // Konstruktori
  public function __construct($attributes){

  	parent::__construct($attributes);
    $this->validators = array('validate_title', 'validate_description', 'validate_priority', 'validate_due_date');
  }

  public static function all($user_id)
  {
  	$query = DB::connection()->prepare("SELECT * FROM TASKS WHERE user_id = :user_id ORDER BY due_date");
  	$query->execute(array('user_id' => $user_id));
  	$rows = $query->fetchAll();
  	$tasks = array();
  	foreach ($rows as $row) {
  		$tasks[] = new Task(array(
  			'id' => $row['id'],
  			'title' => $row['title'],
        'user_id' => $row['user_id'],
  			'description' => $row['description'],
        'priority'   => $row['priority'],
        'due_date'  => $row['due_date'],
        'status'  => $row['status'],
  			'created_at' => $row['created_at'],
  			'modified_at' => $row['modified_at']
  		));
  	}

  	return $tasks;
  }

  public static function allFromCategory($user_id, $category_id)
  {
    $query = DB::connection()->prepare("SELECT TASKS.* FROM TASKS INNER JOIN TASK_CATEGORY ON TASKS.id = TASK_CATEGORY.task_id WHERE TASKS.user_id = :user_id AND TASK_CATEGORY.category_id = :category_id ORDER BY TASKS.due_date");
    $query->execute(array('user_id' => $user_id, 'category_id' => $category_id));
    $rows = $query->fetchAll();
    $tasks = array();
    foreach ($rows as $row) {
      $tasks[] = new Task(array(
        'id' => $row['id'],
        'title' => $row['title'],
        'user_id' => $row['user_id'],
        'description' => $row['description'],
        'priority'   => $row['priority'],
        'due_date'  => $row['due_date'],
        'status'  => $row['status'],
        'created_at' => $row['created_at'],
        'modified_at' => $row['modified_at']
      ));
    }

    return $tasks;

  }

  public static function search($user_id, $term)
  {
    $query = DB::connection()->prepare("SELECT * FROM TASKS WHERE user_id = :user_id AND title LIKE :term ORDER BY due_date");
    $query->execute(array('user_id' => $user_id, 'term' => "%$term%"));
    $rows = $query->fetchAll();
    $tasks = array();
    foreach ($rows as $row) {
      $tasks[] = new Task(array(
        'id' => $row['id'],
        'title' => $row['title'],
        'user_id' => $row['user_id'],
        'description' => $row['description'],
        'priority'   => $row['priority'],
        'due_date'  => $row['due_date'],
        'status'  => $row['status'],
        'created_at' => $row['created_at'],
        'modified_at' => $row['modified_at']
      ));
    }

    return $tasks;
  }

  public static function find($id)
  {
  	$query = DB::connection()->prepare("SELECT * FROM TASKS WHERE id = :id LIMIT 1");
  	$query->execute(array('id' => $id));
  	$row = $query->fetch();
  	if ($row) {
  		$task = new Task(array(
        'id' => $row['id'],
        'title' => $row['title'],
        'user_id' => $row['user_id'],
        'description' => $row['description'],
        'priority'   => $row['priority'],
        'due_date'  => $row['due_date'],
        'status'  => $row['status'],
        'created_at' => $row['created_at'],
        'modified_at' => $row['modified_at']
      ));
  		return $task;
  	} else {
  		return null;
  	}
  }

   public function save() 
   {
	   	if (property_exists($this, 'id') AND $this->id) {
	   			
	   		$query = DB::connection()->prepare('UPDATE TASKS SET title = :title, user_id = :user_id, description = :description, priority = :priority, due_date = :due_date, status = :status ,modified_at = now() WHERE id = :id');
	   		$query->execute(array('title' => $this->title, 'user_id' => $this->user_id, 'description' => $this->description, 'priority' => $this->priority, 'due_date' => $this->due_date, 'status' => $this->status, 'id' => $this->id));

        $query = DB::connection()->prepare('DELETE FROM TASK_CATEGORY WHERE task_id = :task_id');
        $query->execute(array('task_id' => $this->id));

        foreach ($this->categories as $category_id) {
           $query = DB::connection()->prepare('INSERT INTO TASK_CATEGORY (task_id, category_id, created_at, modified_at) VALUES (:task_id, :category_id, now(), now()) ');
           $query->execute(array('task_id' => $this->id, 'category_id' => $category_id));
        }

	   		return $this->id;

	   	} else {

	   		$query = DB::connection()->prepare('INSERT INTO TASKS (title, user_id, description, priority, due_date, status, created_at, modified_at) VALUES (:title, :user_id, :description, :priority, :due_date, :status, now(), now()) RETURNING id');
	   		
	   		$query->execute(array('title' => $this->title, 'user_id' => $this->user_id, 'description' => $this->description, 'priority' => $this->priority, 'due_date' => $this->due_date, 'status' => $this->status));
	   		
	   		$row = $query->fetch();
	   		
	   		$this->id = $row['id'];

        foreach ($this->categories as $category_id) {
           $query = DB::connection()->prepare('INSERT INTO TASK_CATEGORY (task_id, category_id, created_at, modified_at) VALUES (:task_id, :category_id, now(), now()) ');
           $query->execute(array('task_id' => $this->id, 'category_id' => $category_id));
        }
       

	   		return $this->id;
	   	}
  }

  public function update()
  {
     $query = DB::connection()->prepare('UPDATE TASKS SET title = :title, user_id = :user_id, description = :description, priority = :priority, due_date = :due_date, status = :status ,modified_at = now() WHERE id = :id');
        $query->execute(array('title' => $this->title, 'user_id' => $this->user_id, 'description' => $this->description, 'priority' => $this->priority, 'due_date' => $this->due_date, 'status' => $this->status, 'id' => $this->id));

     $query = DB::connection()->prepare('DELETE FROM TASK_CATEGORY WHERE task_id = :task_id');
     $query->execute(array('task_id' => $this->id));

     foreach ($this->categories as $category_id) {
        $query = DB::connection()->prepare('INSERT INTO TASK_CATEGORY (task_id, category_id, created_at, modified_at) VALUES (:task_id, :category_id, now(), now()) ');
        $query->execute(array('task_id' => $this->id, 'category_id' => $category_id));
     }


     return $this->id;
  }

  public function destroy()
  {
    $query = DB::connection()->prepare('DELETE FROM TASK_CATEGORY WHERE task_id = :task_id');
    $query->execute(array('task_id' => $this->id));

  	$query = DB::connection()->prepare('DELETE FROM TASKS WHERE id = :id');
  	$query->execute(array('id' => $this->id));

  	return true;
  }

  public function markComplete()
  {
    $query = DB::connection()->prepare('UPDATE TASKS SET status = 1 WHERE id = :id');
    $query->execute(array('id' => $this->id));
    return true;
  }

  public function markInComplete()
  {
    $query = DB::connection()->prepare('UPDATE TASKS SET status = 0 WHERE id = :id');
    $query->execute(array('id' => $this->id));
    return true;
  }

  protected function validate_title(){

    $errors = array();

    $validator_errors = $this->validate_string_length($this->title, 'Nimi');
    $errors = array_merge($errors, $validator_errors);

    return $errors;
  }

  protected function validate_description(){

    $errors = array();

    $validator_errors = $this->validate_string_length($this->description, 'Kuvaus', 10);
    $errors = array_merge($errors, $validator_errors);

    return $errors;
  }

  protected function validate_priority() {

    $errors = array();

    if ($this->priority != 1 AND $this->priority != 2 AND $this->priority != 3) {
        $errors[] = "Prioriteetin tulee olla Korkea, Normaali tai Matala.";
    }

    return $errors;
  }

  protected function validate_due_date() {

    $errors = array();

    $due_date = strtotime($this->due_date);
    if (! $due_date) {
      $errors[]  = "Deadline on pakollinen.";
    }

    $current_time = time() - 86400;

    if ($due_date < $current_time) {
        $errors[] = "Deadline ei voi olla ennen tätä päivämäärää.";
    }

    return $errors;

  }

}