<?php
/**
* Template Name: Home
*/

get_header();?>

<div class="row home blogs">

  <div class="blocks-container-container">

    <div class="blocks-container createblog">

      <div class="block title">

        <p class="title">Plaats een blog bericht</p>

      </div>

      <?php

      if($_GET['formcode']):

        switch ($_GET['formcode']) {
          case 00: //De creatie is goed gegaan
            $message = '<i class="fas fa-check"></i>Blog succesvol toegevoegd';
            break;
          case 01: //Iets mis met de post variabelen
            $message = '<i class="fas fa-times-circle"></i>Er is iets mis gegaan, probeer het later opnieuw';
            break;
          case 02: //De titel is niet ingevuld
            $message = '<i class="fas fa-times-circle"></i>De titel moet worden ingevuld';
            break;
          case 03: //De tekst is niet ingevuld
            $message = '<i class="fas fa-times-circle"></i>Het bericht moet worden ingevuld';
            break;
          case 04: //De tekst is niet ingevuld
            $message = '<i class="fas fa-times-circle"></i>Er was een probleem met het geselecteerde bestand';
            break;
        }

        echo '<div class="block succes">'. $message .'</div>';

      endif;

      ?>

      <form id="blog_form" name="blog_form" method="post" action="/blog-overzicht?addblog=1" enctype="multipart/form-data">

        <!--Blog titel-->
        <div class="block input">

          <p class="inputname">Berichtnaam</p>
          <input type="text" id="blog_title" name="blog_title" placeholder="Geen titel">

        </div>

        <!--Blog categorie-->
        <div class="block input">

          <p class="inputname">Categorie</p>
          <div class="selectwrapper">
            <select id="blog_category" name="blog_category" form="blog_form">

              <option value="" disabled selected>Geen categorie</option>

              <?php
                $categories = get_categories();

                foreach($categories as $category) {
                 echo '<option value="'. esc_html($category->term_id) .'">'. esc_html($category->name) .'</option>';
                }
              ?>

            </select>
          </div>

        </div>

        <!--Blog afbeelding-->
        <div class="block input">

          <p class="inputname" required>Header afbeelding</p>
          <div class="filewrapper">
            <input type="file" id="blog_image" name="blog_image" value="" accept=".jpg, .jpeg, .png, .gif">
            <div class="fakebutton">Kies bestand</div>
          </div>

        </div>

        <!--Blog tekst-->
        <div class="block input">

          <p class="inputname" required>Bericht</p>
          <textarea id="blog_text" name="blog_text" rows="8" cols="80"></textarea>

        </div>

        <div class="block button">

          <button type="submit" name="submit" value="Publish" class="button">Bericht aanmaken</button>

        </div>

        <input type="hidden" name="action" value="new_post" />
			  <?php wp_nonce_field( 'new-post' ); ?>

      </form>


    </div>

    <div class="blocks-container homeblogs">

      <?php print_blogs(4, false); ?>

      <div class="block button">
        <a class="button" href="/blog-overzicht">Meer laden</a>
      </div>


    </div>

  </div>

</div>

<?php get_footer(); ?>
