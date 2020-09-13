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

require_once('_config.php');
require_once('_db.php');
require_once('_crawler.php');

set_time_limit (0);

error_reporting (E_ERROR | E_WARNING | E_PARSE);

$crawl_max_shown_depth = $CRAWL_MAX_DEPTH - 1;

print "PHP-Crawler started...<br />\n";
print "Log format: Crawling: [Current depth ({$crawl_max_shown_depth} MAX)] URL Action<br />\n";

if ($CRAWL_DB_DISABLE_KEYS) sql_query("/*!40000 ALTER TABLE `phpcrawler_links` DISABLE KEYS */;");

addHeadLink(1, $CRAWL_ENTRY_POINT_URL);

markOldURLsToCrawl();

$url_counter = 0;
$url_size = 0;

while($URL_info = getURLToCrawl()) {
   // Cooldown
   usleep ($CRAWL_THREAD_SLEEP_TIME);
   
   $url_counter++;
   
   $URL = $URL_info["url"];
   $site_URL = $CRAWL_ENTRY_POINT_URL;
   //$site_URL = $URL_info["site_url"];
   
   //$current_URL = preg_replace("/\/[^\/]+$/i", "", $URL_info["url"]);
   $current_URL = preg_replace("/([^\/])\/[^\/]+$/i", "\\1", $URL_info["url"]);
   //print(" base: " . $current_URL . " ");
   
   print "Crawling: [" . $URL_info["depth"] . "] {$URL}";
   
   $page = fetchURL($URL);
   if ($page === false) {
      dropURLFromDB($URL_info["id"]);
      print " - FAILED/REMOVED.<br>\n";
      continue;
   }
   
   $page_size = strlen($page);
   $url_size += $page_size;
   print " " . ($page_size / 1000) . "kb";
   
   $page_content = preparePage($page);
   $page_content_md5 = md5($page_content);
   
   $page_hash = prepareHash($page_content); // puts words into DB; returns number of words
   $page_hash_md5 = md5($page_hash);
   
   if($page_counter = checkEquals($page_content_md5)) {
      unsetURLFromDB($URL_info["id"]);
      print " - SKIPPED ({$page_counter} equals).<br />\n";
      continue;
   }
   
   $URLs_draft = getURLsFromPage($page, $URL_info["depth"] + 1); //array
   $page_title = getPageTitle($page);
   $URLs_clean = filterURLs($URLs_draft, $site_URL, $current_URL); //$base_URL, $current_URL
   $URLs_to_crawl = addURLsToCrawl($URL_info["site_id"], $URLs_clean, $URL_info["depth"] + 1);
   
   print " +" . $URLs_to_crawl . " urls.<br />\n";
   
   // send_page_to_db($URL_info["id"], $page_title, $page_content, $page_content_md5);
   sendPageToDB($URL_info["id"], $page_title, $page_hash, $page_hash_md5);
}

if ($CRAWL_DB_DISABLE_KEYS) sql_query("/*!40000 ALTER TABLE `phpcrawler_links` ENABLE KEYS */;");

print $url_counter . " pages crawled, " . ($url_size/1000) . "Kb processed.<br>\n";

?>