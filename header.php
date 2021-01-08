<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <?php wp_head(); ?>
    <?php
      global $post;

      $settings_slug = get_page_by_path('site-settings');

      $header_logo = get_field('header_logo', $settings_slug);
      $banner_title = get_field('banner_titel');
      $category = wp_get_post_categories($post->ID, 'taxonomy');

      if(is_singular('blogs')):
        $banner_img = get_the_post_thumbnail_url();
      else:
        $banner_img = get_field('banner_afbeelding');
      endif;
      ?>
  </head>
  <body>
    <div class="row header">
      <div class="blocks-container">

        <?php if($header_logo): ?>

          <div class="block logo">

            <a href="/">
              <img src="<?php echo $header_logo ?>" alt="logo">
            </a>

          </div>

        <?php endif; ?>

        <div class="block headermenu">

          <?php echo wp_nav_menu('Header'); ?>

        </div>

      </div>
    </div>
    <div class="row banner" <?php if($banner_img): ?>style="background-image: url('<?php echo $banner_img ?>')" <?php endif; ?>>

    </div>
    <div class="row bannertext">

      <?php if($banner_title): ?>

        <div class="blocks-container">
          <div class="block title">

            <p><?php echo $banner_title ?></p>

          </div>

            <?php
              if(is_singular('blogs')){

                ?>
                  <div class="block category">
                    <p>
                      <?php
                        for ($i = 0; $i < count($category); $i++)  {

                          echo get_cat_name($category[$i]);

                          if ($i != (count($category)-1)){

                            echo " / ";
                          }
                        }
                      ?>

                    </p>
                  </div>
                <?php

              }
            ?>

        </div>

      <?php
        endif;
      ?>

    </div>
