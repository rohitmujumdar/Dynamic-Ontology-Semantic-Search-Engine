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

if (basename($_SERVER['PHP_SELF']) == 'head.php') return;

$q = "";
if (isset($_GET["q"])) {
   $q = $_GET["q"];
}

require_once("./crawler/_config.php");
require_once("./crawler/_db.php");
require_once("./crawler/_search.php");

/**
 * Start XHTML output
 * <!-- this is real html, trust me -->
 * can be used if needed <link rel="stylesheet" type="text/css" href="default.css" />
 */
echo '<?xml version="1.0" encoding="UTF-8"?>';
$fTextElm = '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>

<title>South River &#8212; Search</title>
<script type="text/javascript">
//<![CDATA[
function setFocus() {
   document.getElementById("q").focus();
}
//]]>
</script>
<link rel="stylesheet" type="text/css" href="phpcrawl.css" />
<?php

$base_dir = dirname($_SERVER['PHP_SELF']);

$shortitle = 'search';
$pagetitle = 'Search';
$titleflag = false;
if ((include $head_file) !== false) {
   echo $head_html;
}
?>
</head>

<body>

<?php
if ((include $header_file) !== false) {
   echo $header_html;
}
?>

<div id="content">
