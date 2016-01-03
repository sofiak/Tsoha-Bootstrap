<?php
  
  require_once 'app/models/User.php';
  require_once 'app/models/Task.php';

  class AppController extends BaseController{

    public static function index(){

      self::check_logged_in();
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
      $tasks = Task::all(self::get_user_logged_in()->id);
      View::make('dashboard.html', array('tasks' => $tasks));
    
    }

    public static function createTask(){

      self::check_logged_in();

      View::make('newtask.html');
    }

    public static function storeTask() {

      self::check_logged_in();

      $params = $_POST;

      $task = new Task(array(
        'title' => $params['title'],
        'description' => $params['description'],
        'priority' => $params['priority'],
        'due_date' => $params['due_date'],
        'status' => 0,
        'user_id' => self::get_user_logged_in()->id
      ));


      $errors = $task->errors();

      if(count($errors) > 0){
         Redirect::to('/tasks/create', array('errors' => $errors));
      }

      $task->save();

      Redirect::to('/tasks/task/'.$task->id);

    }

    public static function showTask($id)
    {
        self::check_logged_in();

        $task = Task::find($id);

        if ($task->user_id != self::get_user_logged_in()->id) {
            die("Unauthorized!");
        }

        View::make('viewtask.html', array('task' => $task));
    }

    public static function editTask($id)
    {
        self::check_logged_in();

        $task = Task::find($id);

        if ($task->user_id != self::get_user_logged_in()->id) {
            die("Unauthorized!");
        }

        View::make('edittask.html', array('task' => $task));
    }

    public static function updateTask($id)
    {
        self::check_logged_in();

        $task = Task::find($id);

        if ($task->user_id != self::get_user_logged_in()->id) {
            die("Unauthorized!");
        }

        $params = $_POST;

        $task->title = $params['title'];
        $task->description = $params['description'];
        $task->priority = $params['priority'];
        $task->due_date = $params['due_date'];

        $errors = $task->errors();

        if(count($errors) > 0){
           Redirect::to('/tasks/edit/'.$task->id, array('errors' => $errors));
        }

        $task->update();

        Redirect::to('/tasks/task/'.$task->id);
    }

    public function deleteTask($id)
    {
       self::check_logged_in();

        $task = Task::find($id);

        if ($task->user_id != self::get_user_logged_in()->id) {
            die("Unauthorized!");
        }

        $task->destroy();

        Redirect::to('/');

    }

  }