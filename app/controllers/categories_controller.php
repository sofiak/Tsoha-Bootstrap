<?php
  
  require_once 'app/models/User.php';
  require_once 'app/models/Task.php';
  require_once 'app/models/Category.php';

  class CategoriesController extends BaseController{

    public static function createCategory(){

      View::make('newCategory.html');
    }

    public static function storeCategory() {

      $params = $_POST;

      $category = new Category(array(
        'title' => $params['title'],
        'user_id' => self::get_user_logged_in()->id
      ));


      $errors = $category->errors();

      if(count($errors) > 0){
         Redirect::to('/categories/create', array('errors' => $errors));
      }

      $category->save();

      Redirect::to('/tasks/category/'.$category->id);

    }

    public function deleteCategory($id)
    {

        $category = Category::find($id);

        if ($category->user_id != self::get_user_logged_in()->id) {
             View::make('error.html', array('error' => 'Sinulla ei ole oikeuksia poistaa tätä kategoriaa !'));
        }

        $category->destroy();

        Redirect::to('/');

    }

  }