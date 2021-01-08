<?php
//titel tag
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails', array('post','blogs') );

//titel aan de header toevoegen
function theme_slug_render_title(){

  ?><title><?php wp_title( '|', true, 'right' ); ?></title><?php

}
add_action( 'wp_head', 'theme_slug_render_title' );

//Header navigatie menu registreren
function menu_setup() {
  register_nav_menus( array(
    'header' => 'Header menu'
  ));
}
add_action( 'after_setup_theme', 'menu_setup' );

//Blog post type aanmaken
function create_posttype() {
  register_post_type( 'Blogs',
    array(
      'supports'          => array('title','thumbnail'),
      'labels'            => array(
          'name'          => __( 'Blogs' ),
          'singular_name' => __( 'Blog' )
      ),
      'public'            => true,
      'has_archive'       => true,
      'taxonomies'        => array( 'category' ),
      'rewrite'           => array('slug' => 'blogs'),
      'show_in_rest'      => true,
      'menu_icon'         => 'dashicons-format-aside',
    )
  );
}
add_action( 'init', 'create_posttype' );

// De "Header / Footer settings" pagina verstoppen
function hide_settings_page($query) {
    if ( !is_admin() && !is_main_query() ) {
        return;
    }
    global $typenow;
    if ($typenow === "page") {
        // Replace "site-settings" with the slug of your site settings page.
        $settings_page = get_page_by_path("site-settings",NULL,"page")->ID;
        $query->set( 'post__not_in', array($settings_page) );
    }
    return;
}
add_action('pre_get_posts', 'hide_settings_page');

// De "Header / Footer settings" pagina aan het admin menu toevoegen
function add_site_settings_to_menu(){
    add_menu_page( 'Site instellingen', 'Site instellingen', 'manage_options', 'post.php?post='.get_page_by_path("site-settings",NULL,"page")->ID.'&action=edit', '', 'dashicons-admin-tools', 20);
}
add_action( 'admin_menu', 'add_site_settings_to_menu' );

add_filter('parent_file', 'higlight_custom_settings_page');

function higlight_custom_settings_page($file) {
    global $parent_file;
    global $pagenow;
    global $typenow, $self;

    $settings_page = get_page_by_path("site-settings",NULL,"page")->ID;

    $post = (int)$_GET["post"];
    if ($pagenow === "post.php" && $post === $settings_page) {
        $file = "post.php?post=$settings_page&action=edit";
    }
    return $file;
}

function edit_site_settings_title() {
    global $post, $title, $action, $current_screen;
    if( isset( $current_screen->post_type ) && $current_screen->post_type === 'page' && $action == 'edit' && $post->post_name === "site-settings") {
        $title = $post->post_title.' - '.get_bloginfo('name');
    }
    return $title;
}
add_action( 'admin_title', 'edit_site_settings_title' );

//Blogs
function print_blogs($per_page, $pagination){

    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

    $args = array(
      'post_type' => 'blogs',
      'orderby' => 'date',
      'order' => 'DESC',
      'posts_per_page' => $per_page,
      'paged' => $paged
    );

    $custom_query = new WP_query( $args );
    while($custom_query->have_posts()) : $custom_query->the_post();

    global $post;

    $title = get_the_title();
    $url = get_post_permalink();
    $img = get_the_post_thumbnail_url();
    $date = get_the_date();
    $text = get_field('blog_tekst');
  ?>

    <a class="block blog" href="<?php echo $url ?>">

      <div class="subblock img" <?php if ($img): ?>style="background-image: url('<?php echo $img ?>');"<?php endif; ?>></div>
      <div class="subblock imgtext">

          <p class="date">
            <?php echo $date ?>
          </p>

          <p class="category">
            <?php

            $category = wp_get_post_categories($post->ID, 'taxonomy');

            for ($i = 0; $i < count($category); $i++)  {

              echo get_cat_name($category[$i]);

              if ($i != (count($category)-1)){

                echo " / ";
              }
            }

            ?>
          </p>

      </div>

      <div class="subblock text">

        <?php if ($title): ?>
          <p class="title"><?php echo $title ?></p>
        <?php endif; ?>

        <?php if ($text): ?>
          <p class="text"><?php echo mb_strimwidth($text, 0, 120, '...'); ?></p>
        <?php endif; ?>

      </div>

    </a>

    <?php
    endwhile;

    if($pagination):
    ?>

      <div class="block pagination">

        <?php
          if (function_exists("pagination")):
            pagination($custom_query->max_num_pages);
          endif;
        ?>

      </div>

    <?php
    endif;
}

//Pagination
function pagination($pages = '', $range = 2)
{
    $showitems = ($range * 2)+1;

    global $paged;
    if(empty($paged)) $paged = 1;

    if($pages == '')
    {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if(!$pages)
        {
            $pages = 1;
        }
    }

    if(1 != $pages)
    {
        if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a class='first' href='".get_pagenum_link(1)."'></a>";
        if($paged > 1 && $showitems < $pages) echo "<a class='previous' href='".get_pagenum_link($paged - 1)."'></a>";

        for ($i=1; $i <= $pages; $i++)
        {
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
            {
                echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
            }
        }

        if ($paged < $pages && $showitems < $pages) echo "<a class='next' href=\"".get_pagenum_link($paged + 1)."\"></a>";
        if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a class='last' href='".get_pagenum_link($pages)."'></a>";
        echo "</div>\n";
    }
}



?>
