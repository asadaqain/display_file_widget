<?php
/*
Plugin Name: Display File Widget
Plugin URI: 
Version: 1.0
Description: Output contents of a file via url or local /path/to/file.
Author: Ali Sadaqain
Author URI: http://www.library.yorku.ca
*/
 
class Display_File_Widget extends WP_Widget
{
  function Display_File_Widget()
  {
    $widget_ops = array('classname' => 'Display_File_Widget', 'description' => 'Output contents of a file via url');
    $this->WP_Widget('Display_File_Widget', 'Display File Widget', $widget_ops);
  }
 
  /* 
   This is the settings form displayed in Appearance->Widgets settings.
   If your adding any new fields, make sure to also add them to update function (see next function).
   Otherwise your form will display but it will not save any values.
   */
   
  function form($instance)
  {
    $instance = wp_parse_args((array) $instance, array( 'title' => '', 'file_url' => '',  ));
    $title = $instance['title'];
    $file_url = $instance['file_url'];
?>
    <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo   $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>

    <p><label for="<?php echo $this->get_field_id('file_url'); ?>">Url of hours html output: <input class="widefat" id="<?php echo $this->get_field_id('file_url'); ?>" name="<?php echo $this->get_field_name('file_url'); ?>" type="text" value="<?php echo attribute_escape($file_url); ?>" /></label></p>
    
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['file_url'] = $new_instance['file_url'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
    $file_url = empty($instance['file_url']) ? '' : $instance['file_url'];

    if(is_front_page()) {    
       if (!empty($title))
         echo $before_title . $title . $after_title;; 

       if(!empty($file_url)) {

          $content = file_get_contents($file_url);
          if (!empty($content))
             echo $content;
          else
            echo "Widget could not read file contents. Please check the url in Appearance->Widgets ";
      
          echo $after_widget;
       }else {
          echo "Please set the file with html output of the hours.";
       }
    } // front page check close
  }
}
add_action( 'widgets_init', create_function('', 'return register_widget("Display_File_Widget");') );


/* 
Just in case you wish to override css of the html or content your loading, you can do so via widget stylesheet.
*/ 
function display_file_widget_enqueue_stylesheet() {
    wp_enqueue_style( 'display-file-stylesheet', plugins_url( 'stylesheet.css', __FILE__ )  );

}
add_action( 'wp_enqueue_scripts', 'display_file_widget_enqueue_stylesheet' ); 
?>
