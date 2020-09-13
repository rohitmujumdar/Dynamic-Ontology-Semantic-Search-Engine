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

if(empty($GLOBALS["www_has_crawler"])) {
   if (empty($GLOBALS["www_has_crawl_config"])) die("Stop. Crawler has no config. Please include _config.php first.");
   
   // ***** CRAWLER ******
   $GLOBALS["www_has_crawler"] = 1;
   
   function markOldURLsToCrawl() {
      global $CRAWL_PAGE_EXPIRE_DAYS;
      sql_query("UPDATE phpcrawler_links SET crawl_now = 1 WHERE TO_DAYS(NOW()) - TO_DAYS(last_crawled) > %d", $CRAWL_PAGE_EXPIRE_DAYS);
      sql_query("DELETE FROM `words`"); // clears table of words
   }
   
   // Fetch ONE url to crawl
   function getURLToCrawl() {
      global $CRAWL_MAX_DEPTH;
      $url = sql_fetch_hash("SELECT l.* FROM phpcrawler_links l WHERE l.crawl_now = 1 and l.depth < %d and l.url != '' LIMIT 1", $CRAWL_MAX_DEPTH);
      
      return $url;
   }
   
   function addHeadLink($site_id, $page_URL) {
      addURLToDB($site_id, $page_URL, 0);
   }
   
   // *** ADD TO DB
   function addURLToDB($site_id, $URL, $depth) {
      //var_dump($URL);
      
      // FIXME!!! add depth verification!!!
      $link_data = sql_fetch_hash("SELECT id, url, last_crawled FROM phpcrawler_links WHERE url = %s", $URL);
      if (empty($link_data["id"])) {
         sql_query("INSERT INTO phpcrawler_links (site_id, url, depth, last_crawled) VALUES (%d, %s, %d, NOW())", $site_id, $URL, $depth);
         return 1;
      }
      else if ($link_data["depth"] > $depth) {
         sql_query("UPDATE phpcrawler_links depth = %d WHERE id = %d", $depth, $link_data["id"]);
      }
      
      return 0;
   }
   
   function addURLsToCrawl($site_id, $URLs_clean, $depth) {
      $counter = 0;
      foreach($URLs_clean as $id => $URL)	{
         $counter += addURLToDB($site_id, $URL, $depth);
      }
      return $counter;
   }
   
   function dropURLFromDB($link_id) {
      sql_query("DELETE FROM phpcrawler_links WHERE id = %d", $link_id);
   }
   
   function unsetURLFromDB($link_id) {
      sql_query("UPDATE phpcrawler_links SET last_crawled = NOW(), crawl_now = 2 WHERE id = %d", $link_id);
   }
   
   function fetchURL($URL) {
      global $CRAWL_ALLOW_EXT, $CRAWL_ENTRY_POINT_URL;
      $handle = @fopen ($URL, "r");
      if ($handle === false) return false;
      
      // if (in_array(strtolower(substr($URL, -3)), $CRAWL_SKIP_EXT)) return false; $CRAWL_SKIP_EXT = array('ico', 'css', 'xsl', 'xlt', 'bmp', 'jpg', 'png', 'tif', 'pdf', 'doc', 'odt', 'zip', 'exe', 'bin', 'jar', 'tar', '.gz', 'bz2', 'rpm', 'dmg', 'gif');
      $fnpath = substr($URL, strlen($CRAWL_ENTRY_POINT_URL));
      if ($fnpath !== false) {
         $fnsplit = explode(".", $fnpath);
         $fnext = $fnsplit[count($fnsplit)-1];
         if (!in_array($fnext, $CRAWL_ALLOW_EXT) && strlen($fnext) <= 5 && strpos($fnext, '/') === false) return false;
      }
      
      $buffer = "";
      while (!feof ($handle)) {
         $buffer .= fgets($handle, 4096);
      }
      fclose ($handle);
      
      return $buffer;
   }
	
   function getURLsFromPage($page, $depth = 0) {
      global $CRAWL_MAX_DEPTH;
      if ($depth >= $CRAWL_MAX_DEPTH) return array();
      $matches = array();
      $URL_pattern = "/\s+href\s*=\s*[\"\']?([^\s\"\']+)[\"\'\s]+/ims";
      preg_match_all ($URL_pattern, $page, $matches, PREG_PATTERN_ORDER);
      return $matches[1];
   }
   
   function makeFullQualifiedURL($URL_draft, $base_URL, $current_URL) {
      global $CRAWL_URL_MAX_LEN;
      //$URL_draft = trim($URL_draft);
      
      if (strlen ($URL_draft) > $CRAWL_URL_MAX_LEN) return false;
      if (strpos ($URL_draft, "://") != 0 && substr($URL_draft, 0, 7) != "http://") return false;
      
      // make full qualified URL
      if (substr($URL_draft, 0, 1) != "/" && substr($URL_draft, 0, 7) != "http://") $URL_draft = $current_URL . "/" . $URL_draft;
      if (substr($URL_draft, 0, 7) != "http://") $URL_draft = $base_URL . "/" . $URL_draft;
      $URL_draft = str_replace("/./", "/", $URL_draft);
      $URL_draft = preg_replace("/\/[\/]+/i", "/", $URL_draft);
      $URL_draft = str_replace("http:/", "http://", $URL_draft);
      $URL_draft = str_replace("&amp;", "&", $URL_draft);
      
      // DROP session ID
      $URL_draft = preg_replace("/sid=[\w\d]+/i", "", $URL_draft);
      
      return $URL_draft;
   }
   
   function filterURLs($URLs_draft, $base_URL, $current_URL) {
      $URLs_clean = array();
      
      $counter = 0;
      foreach($URLs_draft as $id => $URL) {
         //vds($URL);
         $URL = makeFullQualifiedURL($URL, $base_URL, $current_URL);
         if ($URL === false || strpos ($URL, $base_URL) !== 0) continue;
         $URLs_clean[$counter++] = $URL;
      }
      
      return $URLs_clean;
   }
   
   function getPageTitle($page) {
      preg_match("/<title>(.*)<\/title>/imsU", $page, $matches);
      return $matches[1];
   }
   
   function preparePage($content) {
      $content = preg_replace("/<script(.*)<\/script>/imsU", "", $content);
      $content = preg_replace("/<!--(.*)-->/imsU", "", $content);
      //TEST: added 0.7.7: remove useless spaces
      $content = preg_replace("/[\s]+/ims", " ", $content);
      $content = preg_replace("/<\/?(.*)>/imsU", "", $content);
      // $content = html_entity_decode($content); done in pageWords
      return $content;
   }
   
   function checkEquals($page_content_md5) {
      $page_counter = sql_fetch("SELECT count(*) as cnt FROM phpcrawler_links WHERE content_md5 = %s", $page_content_md5);
      return $page_counter;
   }
   
   function sendPageToDB($link_id, $page_title, $page_content, $page_content_md5) {
      global $CRAWL_URL_MAX_CONTENT;
      if (strlen($page_content) > $CRAWL_URL_MAX_CONTENT) $page_content = substr($page_content, 0, $CRAWL_URL_MAX_CONTENT);
      //sql_query("UPDATE phpcrawler_links SET content = %s, content_md5 = %s, last_crawled = NOW(), crawl_now = 2 WHERE id = %d", $page_content, $page_content_md5, $link_id);
      sql_query("UPDATE phpcrawler_links SET content = %s, content_md5 = %s, url_title = %s, last_crawled = NOW(), crawl_now = 2 WHERE id = %d", $page_content, $page_content_md5, $page_title, $link_id);
   }
   
   function vds($var) {
      print "<!--";
      var_dump($var);
      print "-->";
   }
   
   // ob_end_flush();
   $crawldb = sql_open();
}

?>