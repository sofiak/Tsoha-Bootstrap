<?php
  
  require_once 'app/models/User.php';
  require_once 'app/models/Task.php';
  require_once 'app/models/Category.php';

  class AppController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
      $tasks = Task::all(self::get_user_logged_in()->id);
      $categories = Category::all(self::get_user_logged_in()->id);
      View::make('dashboard.html', array('tasks' => $tasks, 'categories' => $categories, 'category' => 'all'));
    
    }

    public static function categoryTasks($id) 
    {
      $category = Category::find($id);

      if (! $category) {
          View::make('error.html', array('error' => 'Kategoriaa ei löydy !'));
      }
      if ($category->user_id != self::get_user_logged_in()->id) {
          View::make('error.html', array('error' => 'Sinulla ei ole oikeuksia katsella tätä kategoriaa !'));
      }

      $tasks = Task::allFromCategory(self::get_user_logged_in()->id, $id);
      $categories = Category::all(self::get_user_logged_in()->id);
      View::make('dashboard.html', array('tasks' => $tasks, 'categories' => $categories, 'category' => $category));
    }

    public static function search() 
    {
      $params = $_POST;
      $term = $params['term'];
      $tasks = Task::search(self::get_user_logged_in()->id, $term);
      $categories = Category::all(self::get_user_logged_in()->id);
      View::make('dashboard.html', array('tasks' => $tasks, 'categories' => $categories, 'category' => 'all', 'search' => $term));
    }

    public static function createTask(){
      $categories = Category::all(self::get_user_logged_in()->id);
      View::make('newtask.html', array('categories' => $categories));
    }

    public static function storeTask() {

      $params = $_POST;

      $attributes = array(
        'title' => $params['title'],
        'description' => $params['description'],
        'priority' => $params['priority'],
        'due_date' => $params['due_date'],
        'categories' => array(),
        'status' => 0,
        'user_id' => self::get_user_logged_in()->id
      );

      $categories = $params['categories'];

      foreach ($categories as $category) {
        $attributes['categories'][] = $category;
      }

      $task = new Task($attributes);


      $errors = $task->errors();

      if(count($errors) > 0){
         Redirect::to('/tasks/create', array('errors' => $errors));
      }

      $task->save();

      Redirect::to('/tasks/task/'.$task->id);

    }

    public static function showTask($id)
    {

        $task = Task::find($id);

        if (! $task) {
          View::make('error.html', array('error' => 'Tehtävää ei löydy !'));
        }
        if ($task->user_id != self::get_user_logged_in()->id) {
            View::make('error.html', array('error' => 'Sinulla ei ole oikeuksia katsella tätä tehtävää !'));
        }

        View::make('viewtask.html', array('task' => $task));
    }

    public static function editTask($id)
    {

        $task = Task::find($id);

        if (! $task) {
          View::make('error.html', array('error' => 'Tehtävää ei löydy !'));
        }

        if ($task->user_id != self::get_user_logged_in()->id) {
            View::make('error.html', array('error' => 'Sinulla ei ole oikeuksia muokata tätä tehtävää !'));
        }
        $categories = Category::all(self::get_user_logged_in()->id);
        $task_categories = Category::allFromTask($id);

        View::make('edittask.html', array('task' => $task, 'categories' => $categories, 'task_categories' => $task_categories));
    }

    public static function updateTask($id)
    {

        $task = Task::find($id);

        if (! $task) {
          View::make('error.html', array('error' => 'Tehtävää ei löydy !'));
        }

        if ($task->user_id != self::get_user_logged_in()->id) {
             View::make('error.html', array('error' => 'Sinulla ei ole oikeuksia muokata tätä tehtävää !'));
        }

        $params = $_POST;

        $task->title = $params['title'];
        $task->description = $params['description'];
        $task->priority = $params['priority'];
        $task->due_date = $params['due_date'];

        $categories = $params['categories'];

        $categories_attribute = array();

        foreach ($categories as $category) {
          $categories_attribute[] = $category;
        }

        $task->categories = $categories_attribute;

        $errors = $task->errors();

        if(count($errors) > 0){
           Redirect::to('/tasks/edit/'.$task->id, array('errors' => $errors));
        }

        $task->update();

        Redirect::to('/tasks/task/'.$task->id);
    }

    public static function markComplete($id)
    {

        $task = Task::find($id);

        if (! $task) {
          View::make('error.html', array('error' => 'Tehtävää ei löydy !'));
        }


        if ($task->user_id != self::get_user_logged_in()->id) {
            View::make('error.html', array('error' => 'Sinulla ei ole oikeuksia muokata tätä tehtävää !'));
        }

        $task->markComplete();

        Redirect::to('/tasks/task/'.$task->id);
    }

    public static function markInComplete($id)
    {

        $task = Task::find($id);

        if (! $task) {
          View::make('error.html', array('error' => 'Tehtävää ei löydy !'));
        }

        if ($task->user_id != self::get_user_logged_in()->id) {
             View::make('error.html', array('error' => 'Sinulla ei ole oikeuksia muokata tätä tehtävää !'));
        }

        $task->markInComplete();

        Redirect::to('/tasks/task/'.$task->id);
    }

    public function deleteTask($id)
    {

        $task = Task::find($id);

        if (! $task) {
          View::make('error.html', array('error' => 'Tehtävää ei löydy !'));
        }

        if ($task->user_id != self::get_user_logged_in()->id) {
             View::make('error.html', array('error' => 'Sinulla ei ole oikeuksia poistaa tätä tehtävää !'));
        }

        $task->destroy();

        Redirect::to('/');

    }

  }