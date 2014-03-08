--TEST--
Testing a simple FAQ
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../setup.php";
require_once __DIR__ . "/../TestRender.php";
require_once __DIR__ . "/TestChunkedXHTML.php";

$formatclass = "TestChunkedXHTML";
$xml_file = __DIR__ . "/data/faq001.xml";

$opts = array(
    "index"             => false,
    "xml_root"          => dirname($xml_file),
    "xml_file"          => $xml_file,
    "output_dir"        => __DIR__ . "/output/",
);

$extra = array(
    "lang_dir" => __DIR__ . "/../../phpdotnet/phd/data/langs/",
    "phpweb_version_filename" => dirname($xml_file) . '/version.xml',
    "phpweb_acronym_filename" => dirname($xml_file) . '/acronyms.xml',
);

$render = new TestRender($formatclass, $opts, $extra);
$render->run();
?>
--EXPECT--
Filename: faq.001.html
Content:
<div id="faq.001" class="chapter">
  <h1>FAQ Test 001</h1>

  

  <p class="para">
    Testing FAQs
  </p>

  <div class="qandaset"><ol class="qandaset_questions"><li><a href="#faq.001.01">
     
      Simple test question 01
     
    </a></li><li><a href="#faq.001.02">
     
      Simple test question 02
     
    </a></li><li><a href="#faq.001.03">
     
      Simple test question 03
     
    </a></li><li><a href="#faq.001.04">
     
      Simple test question 04
     
    </a></li><li><a href="#faq.001.05">
     
      Simple test question 05
     
    </a></li></ol></div>
   <dl class="qandaentry" id="faq.001.01">
    <dt><strong>
     
      Simple test question 01
     
    </strong></dt>
    <dd class="answer">
     <p class="para">
      Simple test answer 01
     </p>
    </dd>
  </dl>

  <dl class="qandaentry" id="faq.001.02">
    <dt><strong>
     
      Simple test question 02
     
    </strong></dt>
    <dd class="answer">
     <p class="para">
      Simple test answer 02
     </p>
    </dd>
  </dl>

  <dl class="qandaentry" id="faq.001.03">
    <dt><strong>
     
      Simple test question 03
     
    </strong></dt>
    <dd class="answer">
     <p class="para">
      Simple test answer 03
     </p>
    </dd>
  </dl>

  <dl class="qandaentry" id="faq.001.04">
    <dt><strong>
     
      Simple test question 04
     
    </strong></dt>
    <dd class="answer">
     <p class="para">
      Simple test answer 04
     </p>
    </dd>
  </dl>

  <dl class="qandaentry" id="faq.001.05">
    <dt><strong>
     
      Simple test question 05
     
    </strong></dt>
    <dd class="answer">
     <p class="para">
      Simple test answer 05
     </p>
    </dd>
  </dl>

  
</div>
