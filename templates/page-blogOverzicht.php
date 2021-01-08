<?php
/**
* Template Name: Blog overzicht
*/

//De toevoeging van de blog via de form op de homepagina wordt hier gedaan voor post/redirect/get
if($_GET['addblog'] == 1){

  if(!empty($_POST['action'])){

    $settings_slug = get_page_by_path('site-settings');

    $titel_verplicht = get_field('titel_verplicht', $settings_slug);
    $tekst_verplicht = get_field('tekst_verplicht', $settings_slug);

    $title = $_POST['blog_title'];
    $cat = $_POST['blog_category'];
    $text = $_POST['blog_text'];
    $img = $_FILES['blog_image'];

    //check titel en tekst
    if(strlen($title) <= 0 && $titel_verplicht){
      wp_redirect('/?formcode=02');
      return;
    }
    if(strlen($text) <= 0 && $tekst_verplicht){
      wp_redirect('/?formcode=03');
      return;
    }

    //check bestand
    if(strlen($img['name']) > 0){

      if($img['error'] != 0){
        wp_redirect('/?formcode=04');
        return;
      }

      if($img['size'] > wp_max_upload_size()):
        wp_redirect('/?formcode=04');
        return;
      endif;

      $allowedmimes = array('gif','png','jpeg','jpg');
      $ext = pathinfo($img['name'], PATHINFO_EXTENSION);

      if (!in_array($ext, $allowedmimes)) {
        wp_redirect('/?formcode=04');
        return;
      }

      $addimg = true;
    }

    //Uplod de blog
    $post_data = array(
    'post_title'	=>	$title,
    'post_content'	=>	'meta',
    'post_category'	=>	array($cat),
    'post_status'	=>	'publish',
    'post_type'	=>	'blogs'
    );

    $new_post = wp_insert_post($post_data);
    update_post_meta($new_post, 'blog_tekst', $text);

    //upload en set afbeelding
    if($addimg){

      if (!function_exists('wp_handle_upload')):
        require( ABSPATH . 'wp-admin/includes/file.php' );
      endif;

      include_once( ABSPATH . 'wp-admin/includes/image.php' );

      $upload_overrides = array( 'test_form' => false );

      $movefile = wp_handle_upload( $img, $upload_overrides );

      $file_name = $img['name'];
      $file_path = $movefile['file'];
      $file_url = $movefile['url'];

      $wp_filetype = $img['type'];
      $attachment = array(
        'guid'           => $file_url,
        'post_mime_type' => $wp_filetype,
        'post_title'     => $file_name,
        'post_status'    => 'inherit',
        'post_date'      => date('Y-m-d H:i:s'),
        'post_name'      => $file_name
      );

      $attachment_id = wp_insert_attachment($attachment ,$file_path);
      $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);

      wp_update_attachment_metadata($attachment_id, $attachment_data);
      set_post_thumbnail($new_post, $attachment_id);
    }

    unset($_POST);
    wp_redirect('/?formcode=00');
    return;

  } else {
    wp_redirect('/?formcode=01');
    return;
  }
}

get_header(); ?>

<div class="row overzicht blogs">
  <div class="blocks-container">

    <?php print_blogs(8, true); ?>

  </div>
</div>

<?php get_footer(); ?>
