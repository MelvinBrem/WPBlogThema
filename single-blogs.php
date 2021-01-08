<?php
get_header();

$title = get_the_title();
$text = get_field('blog_tekst');
?>

<div class="row blog">

  <div class="blocks-container">

    <div class="block text">

      <p class="title"><?php echo $title ?></p>

      <p><?php echo $text ?></p>

    </div>

  </div>

</div>

<div class="row blogs single">
  <div class="blocks-container">

    <?php print_blogs(4, false); ?>

    <div class="block button">
      <a class="button" href="/blog-overzicht">Meer laden</a>
    </div>

  </div>
</div>

<?php get_footer(); ?>
