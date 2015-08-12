<?php
/* $Id$ */
$cssfiles = array(200 => "theme-base.css", "theme-medium.css", "hack-hhvm.css");
if (isset($config['css'])) {
    $cssfiles += (array) $config['css'];
}
foreach($cssfiles as $filename) {
    $path = dirname(dirname(__FILE__)).'/styles/'.$filename;
    $CSS[$filename] = @filemtime($path);
}

if (isset($shortname) && $shortname) {
    header("Link: <$shorturl>; rel=shorturl");
}

if ($config["cache"]) {
    if (is_numeric($config["cache"])) {
        $timestamp = $config["cache"];
    } else {
        $timestamp = filemtime($_SERVER["DOCUMENT_ROOT"] . "/" .$_SERVER["BASE_PAGE"]);
    }
    $tsstring = gmdate("D, d M Y H:i:s ", $timestamp) . "GMT";

    if (isset($_SERVER["HTTP_IF_MODIFIED_SINCE"]) && $_SERVER["HTTP_IF_MODIFIED_SINCE"] == $tsstring) {
        header("HTTP/1.1 304 Not Modified");
        exit;
    }
    header("Last-Modified: " . $tsstring);
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $lang?>">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Hack and HHVM: <?php echo $title ?></title>

 <link rel="shortcut icon" href="<?php echo $MYSITE ?>favicon.png">
 <link rel="search" type="application/opensearchdescription+xml" href="http://docs.hhvm.com/docshhvmimprovedsearch.src" title="Add docs.hhvm.com search">
 <link rel="alternate" type="application/atom+xml" href="<?php echo $MYSITE ?>releases/feed.php" title="PHP Release feed">
 <link rel="alternate" type="application/atom+xml" href="<?php echo $MYSITE ?>feed.atom" title="PHP: Hypertext Preprocessor">

  <meta property='og:type' content='website'>
  <meta property='og:title' content='Hack and HHVM Manual'>
  <meta property='og:description' content='The documentation for the Hack language and HHVM'>
  <meta property='og:image' content='http://docs.hhvm.com/images/og.png'>

<?php
// Google Analytics Tracking
include $_SERVER['DOCUMENT_ROOT'] . '/include/ga.inc';
?>

<?php if (isset($_SERVER['BASE_PAGE'])): ?>
 <link rel="canonical" href="http://docs.hhvm.com/<?php echo $_SERVER['BASE_PAGE']?>">
<?php if ($shortname): ?>
 <link rel="shorturl" href="<?php echo $shorturl ?>">
<?php endif ?>
<?php endif ?>

<?php foreach($config["meta-navigation"] as $rel => $page): ?>
 <link rel="<?php echo $rel ?>" href="<?php echo $MYSITE ?><?php echo $page ?>">
<?php endforeach ?>
 <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,400italic,600italic|Source+Code+Pro&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<?php foreach($CSS as $filename => $modified): ?>
<link rel="stylesheet" type="text/css" href="<?php echo $MYSITE ?>cached.php?t=<?php echo $modified?>&amp;f=styles/<?php echo $filename?>" media="screen">
<link rel="stylesheet" type="text/css" href="<?php echo $MYSITE ?>styles/<?php echo $filename?>" media="screen">
<?php endforeach ?>


<?php if (!empty($_SERVER["BASE_HREF"])): ?>
 <base href="<?php echo $_SERVER["BASE_HREF"] ?>">
<?php endif ?>

</head>
<body class="<?php print $curr; ?> <?php echo $classes; ?>">

<nav id="head-nav" class="navbar navbar-fixed-top">
  <div class="navbar-inner clearfix">
    <a href='/' class="brand"></a>
    <a href='http://on.fb.me/1Ujf32Y' class="survey"></a>
    <form class="navbar-search" id="topsearch" action="/search.php">
      <input type="hidden" name="show" value="quickref">
      <input type="search" name="pattern" class="search-query" placeholder="Search" accesskey="s">
    </form>
  </div>
  <div id="flash-message"></div>
</nav>
<?php if (!empty($config["headsup"])): ?>
<div class="headsup"><?php echo $config["headsup"]?></div>
<?php endif ?>
<nav id="trick"><div><?php doc_toc("en") ?></div></nav>
<div id="goto">
    <div class="search">
         <div class="text"></div>
         <div class="results"><ul></ul></div>
   </div>
</div>

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


<?php if (!empty($config['intro'])):?>
<div id="intro" class="clearfix">
  <div class="container">
    <?php echo $config['intro'];?>
  </div>
</div>
<?php endif;?>


<div id="layout">

<?php if (!empty($config["leftmenu"])): ?>
    <aside class='layout-menu'>

        <ul class='parent-menu-list'>
            <?php foreach($config['leftmenu'] as $section): ?>
            <li>
                <a href="<?php echo $section['link']; ?>"><?php echo $section['title']; ?></a>

                <?php if ($section['children']): ?>
                    <ul class='child-menu-list'>

                        <?php
                        foreach($section['children'] as $item):
                            $cleanTitle = $item['title'];
                        ?>
                        <li class="<?php echo ($item['current']) ? 'current' : ''; ?>">
                            <a href="<?php echo $item['link']; ?>" title="<?php echo $cleanTitle; ?>"><?php echo $cleanTitle; ?></a>
                        </li>
                        <?php endforeach; ?>

                    </ul>
                <?php endif; ?>

            </li>
            <?php endforeach; ?>
        </ul>
    </aside>
<?php endif; ?>

<section id="layout-content">
