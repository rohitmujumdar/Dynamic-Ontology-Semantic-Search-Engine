<?php
/**
 * <h1>PHP Crawler</h1>
 * @author Vladimir Fedorkov, Doug Martin, and Sumit Dutta
 * @version 0.8 2007-05-04
 * @link http://astellar.com/
 * @copyright 2005-2007
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 */

require_once "head.php";
?>

<div id="content_text">

<h1>Search</h1>

<table class="searchbox" border="0" cellspacing="0" cellpadding="0">
   <tr><td><form id="s" method="get" action="search.php">
   <table border="0" cellspacing="0" cellpadding="0"><tr>
   <?php /* <td><span class="s_text_large_white">Search</span></td> */ ?>
   <td><input type="text" size="35" name="q" id="q" class="s_text" value="<?php
   $q = '';
   if (isset($_GET['q'])) {
      if (isset($q_plus) && $q_plus == 1) {
         $q = urldecode($_GET['q']);
      }
      else {
         $q = $_GET['q'];
      }
   }
   echo $q;
   ?>" /></td>
   <td><input type="submit" name="Submit" value="Go!" class="s_text" /></td>
   </tr></table>
   </form></td></tr>
</table>

<?php
/* <!-- /header --> */
/*
require_once($_SERVER["DOCUMENT_ROOT"] . "/crawler/_config.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/crawler/_db.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/crawler/_search.php");
*/

/*
 === USED VARIABLES:
 $p = current page
 $rpp = rows per page
 $q = search query

*/

if (!isset($p))
   $p = 0;
$p = (int)$p;
$rpp = $CRAWL_RESULTS_PER_PAGE;

$records = false;
$r = false;

function drawPager($records, $p, $rpp, $q_encoded) {
   // $p essentially means nothing
   ?>
   <script type="text/javascript">
   //<![CDATA[
   <?php
   for ($i = $rpp; $i < $records; $i ++) {
      echo 'document.getElementById("r'.$i.'").style.display="none";'."\n";
   }
   ?>
   //]]>
   </script>
   <?php
   echo '<p class="s_text">';
   echo 'View Page: ';
   $j = 1;
   for ($i = 1; $i <= $records; $i += $rpp) {
      echo '<input type="button" name="page'.$j.'" value="'.$j.'" onclick="'.pagerShow($i, $rpp, $records).'" />';
      echo ' ';
      $j ++;
   }
   echo '</p>';
}

function pagerShow($start, $rpp, $records) {
   $show = "window.scrollTo(0,0);";
   for ($i = 0; $i < $records; $i ++) {
      $show .= 'document.getElementById(\'r'.$i.'\').style.display=\'none\';'."\n";
   }
   for ($i = $start; $i <= $records && $i < $start + $rpp; $i ++) {
      $j = $i - 1;
      $show .= 'document.getElementById(\'r'.$j.'\').style.display=\'block\';'."\n";
   }
   return $show;
}

/* Deprecated */
function drawMatchPager($records, $p, $rpp, $q) {
   // ==== PAGER ====
   
   $q_words = explode(' ', urldecode($q));
   
   $total = ceil($records/$rpp);
   print "<hr />\n";
   
   if ($records == 0) return false;
   print "pages: |\n";
   
   $pager = $page;
   if (($p - 1) >= 0) {
      $p_prev = $p - 1; ?> <a href="search.php?q=<?php echo $q; ?>&p=<?php echo $p_prev; ?>" class="s_text">&lt;&lt; prev</a> | <?php
   }
   
   for($i = 0; $i < $total; $i++) {
      $p_show = $i + 1;
      if ($i == $p) {
         ?><span class="s_text"><b><?php echo $p_show; ?></b></span> | <?php
      }
      else {
         ?><a href="search.php?q=<?php echo $q; ?>&p=<?php echo $i; ?>" class="s_text"><?php echo $p_show;?></a> | <?php
      }
   }
   
   if (($p + 1) < $total) {
      $p_next = $p + 1; ?> <a href="search.php?q=<?php echo $q; ?>&p=<?php echo $p_next; ?>" class="s_text">&gt;&gt; next</a> | <?php
   }
   
   // **** /PAGER **** 
   
}

/* Reform query */

$q0 = $q;
$q_encoded = urlencode($q0);
$q = prepareQuery($q);

// content search

// $records = sql_fetch($sql_content_q_count, $q);
$result = sql_query($sql_content_q, $q, $p * $rpp, $rpp);

$r = array();
if (!empty($q0)) {
   $s = getSimilarWords($q0);
   $r = getResults($result, $q);
}

$records = count($r);

/* Did you mean? ... similar words area */

$j = 0;
foreach ($s as $qWord=>$qSuggest) {
   if ($j == 0) {
      echo '<p class="s_text">Did you mean: ';
   }
   echo '<a href="search.php?q='.str_replace($qWord, $qSuggest, strtolower($q_encoded)).'">'.$qSuggest.'</a>';
   $j ++;
   if ($j == count($s)) {
      echo "?</p>";
   }
   else {
      echo ', ';
   }
}

/* <!-- **** PAGER 1 ***** --> */
echo '<h2 class="resultstitle">Results</h2>';

/* Tests */
?>
<?php /*
<pre>Query prepared: <?php echo $q; ?></pre>
<pre>Query encoded: <?php echo $q_encoded; ?></pre>

<pre><?php print_r($r); print_r($s); ?></pre>

<pre>Example Word: <?php $ex_word = "potential adage"; echo $ex_word; ?></pre>
<pre>Example Hash of content: <?php $ex_hash1 = prepareQuery($ex_word); echo $ex_hash1; ?></pre>
<pre>Example Hash of query: <?php $ex_hash2 = prepareHash($ex_word); echo $ex_hash2; ?></pre>
<pre>Example Word from content hash: <?php echo hashToText($ex_hash1); ?></pre>
<pre>Example Word from query hash: <?php echo hashToText($ex_hash2); ?></pre>

<pre>Example ID/decimal: <?php $ex_id = 271828; echo $ex_id; ?></pre>
<pre>Example Hash pair: <?php $ex_id_conv = fromDecimal($ex_id); echo $ex_id_conv; ?></pre>
<pre>Example ID/decimal: <?php echo toDecimal($ex_id_conv); ?></pre>

*/

if ($records > 0)
   drawPager($records, $p, $rpp, $q_encoded);

if ($records > 0) {
   // sql_rows($r)
   echo '<ul class="resultset">';
   $k = 0;
   // while ($a = sql_row_hash($r))
   foreach ($r as $a) {
      // if (!empty($a["fmt_result"])) --- // $a["content"]; // condition being false highly unlikely
      // $a["fmt_result"] = searchOldFormatContent($a["content"], $q);
      // if (empty($a["url_title"])) $a["url_title"] = $a["url"]; taken care of
      /* <!-- SEARCH RESULTS --> */
      ?><li class="s_text" id="r<?php echo $k; ?>">
      <?php echo str_replace(array("%u", "%t", "%d", "%c"), array($a["url"], $a["url_title"], $a["last_crawled"], $a["fmt_result"]), $CRAWL_SEARCH_TEMPLATE); ?>
      </li>
      <?php
      /* <!-- //SEARCH RESULTS --> */
      // ---endif
      $k ++;
   }
   echo '</ul>';
}
else {
   /* <!-- NOTHING FOUND --> */
   ?><p class="s_text"><b>Nothing found</b>. You may enter another query.</p>
   <?php
   /* <!-- //NOTHING FOUND --> */
}

/* <!-- **** PAGER 2 ***** --> */
if ($records > 0)
   drawPager($records, $p, $rpp, $q_encoded);

?>

<p class="s_text_small" style="text-align: right;">Powered by <a href="http://astellar.com/opensource/php-crawler/">PHP Crawler</a>.</p>

<?php if (empty($q0)) { ?><script type="text/javascript">
//<![CDATA[
document.getElementsByTagName("body").item(0).setAttribute("onload", "setFocus();");
//]]>
</script><? } ?>
</div>