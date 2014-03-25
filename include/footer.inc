    </section><!-- layout-content -->
    <?php
    if (!empty($config['spanning-content'])) {
        print "<div class='spanning-content'>";
        print $config['spanning-content'];
        print "</div>";
    }

    ?>
<?php if (!empty($config['sidebar'])): ?>
    <aside class="tips">
        <div class="inner"><?php echo $config['sidebar']; ?></div>
    </aside>
<?php endif; ?>

<?php if (!empty($config['breadcrumbs'])): ?>
  <div id="breadcrumbs">
    <?php if (isset($config['prev'])): ?>
      <div class="prev">
        <a href="<?php echo $config['prev'][0]; ?>">
          &laquo; <?php echo $config['prev'][1]; ?>
        </a>
      </div>
    <?php endif; ?>
    <?php if (isset($config['next'])): ?>
      <div class="next">
        <a href="<?php echo $config['next'][0]; ?>">
          <?php echo $config['next'][1]; ?> &raquo;
        </a>
      </div>
    <?php endif; ?>
    <ul class="breadcrumbs-container">
      <?php
          $breadcrumbs = $config['breadcrumbs'];
          $last = array_pop($breadcrumbs);
          foreach ($breadcrumbs as $crumb) {
              echo "      <li><a href='{$crumb['link']}'>{$crumb['title']}</a></li>";
          }
          echo "      <li><a href='{$last['link']}'>{$last['title']}</a></li>";

      ?>
    </ul>
  </div>
<?php endif; ?>

  </div><!-- layout -->
         
  <footer>
    <div class="container footer-content">
      <div class="row-fluid">
      <ul class="footmenu">
        <li> Copyright &copy; 2001-<?php echo date('Y'); ?> The PHP Group   |</li>
        <li>Copyright &copy; 2014-<?php echo date('Y'); ?>   Facebook</li>
      </ul>
      </div>
    </div>
  </footer>

    <?php
    // if elephpants enabled, insert placeholder nodes
    // to be populated with images via javascript.
    if (isset($config['elephpants']) && $config['elephpants']) {
        print "<div class='elephpants'><div class=images></div></div>";
    }
    ?>

 <!-- External and third party libraries. -->
 <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<?php
 $jsfiles = array("ext/modernizr.js", "ext/hogan-2.0.0.min.js", "ext/typeahead.min.js", "ext/mousetrap.min.js", "search.js", "common.js");
 foreach ($jsfiles as $filename) {
   $path = dirname(dirname(__FILE__)).'/js/'.$filename;
   // Turn off caching for now.
   //echo '<script type="text/javascript" src="' . $MYSITE . 'cached.php?t=' . @filemtime($path) . '&amp;f=/js/' . $filename . '"></script>'."\n";
   echo '<script type="text/javascript" src="' . $MYSITE . '/js/' . $filename . '"></script>'."\n";
 }
?>

<a id="toTop" href="javascript:;"><span id="toTopHover"></span><img width="40" height="40" alt="To Top" src="/images/to-top@2x.png"></a>

</body>
</html>

