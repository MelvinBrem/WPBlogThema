  <?php
    $settings_slug = get_page_by_path('site-settings');

    $footer_tekst = get_field('footer_tekst', $settings_slug);
  ?>

  <div class="row footer">

    <div class="blocks-container">

      <?php if ($footer_tekst): ?>

        <div class="block text">

          <p><?php echo $footer_tekst ?></p>

        </div>

      <?php endif; ?>

    </div>

  </div>


  <?php wp_footer(); ?>
  </body>
</html>
