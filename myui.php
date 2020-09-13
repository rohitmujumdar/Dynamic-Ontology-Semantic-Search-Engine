<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/MP_GLOBALS.php';
	//require_once $_SERVER['DOCUMENT_ROOT'] . '/MajorProject' . '/MP_GLOBALS.php';

	$arr = array("relatedKeywords: [","images: [","videoList: [", "definitionsList: [");
	$images = array();
	$videos_onto = array();
	$related_keywords = array();
	$definition = array();
	
	$i = 0;

	$handle = fopen("ontology_def.txt", "r");
	
	if ($handle) {
	    while (($line = fgets($handle)) !== false) {
			$a = str_replace($arr[$i],"",$line);
			$a = str_replace("]","",$a);
			$words = preg_split("[,]", $a); // replace
			
			//print_r($words);
			
			if ($i==0)
				$related_keywords = $words;
			else if($i==1)
				$images = $words;
			else if($i==2)
				$videos_onto = $words;
			else if($i==3)
			{
				//echo $words."================================";
				$definition = $words;
			}
	    	$i += 1;
	    }
	    fclose($handle);
	} else {
	    // error opening the file.
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<center id="content">
		<title>Search Engine</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	</head>

	<br></br>
	<img class="img-responsive" src="Used_Images/kids-looking-for-bugs.png" align = "left" style="width:300px;height:170px;">
	<img class="img-responsive" src="Used_Images/3girls.png" align = "right" style="width:300px;height:170px;">
	<img class="img-responsive" src="Used_Images/science1.png" align="middle" style="width:400px;height:170px;">
	<br></br>


	<img class="img-responsive" src="Used_Images/learn.png" align="middle" style="width:400px;height:170px;">
	<br></br>


<?php//===================================IMAGES==================================================?>

	<div class="container">
	
		<?php
			$query = $_GET['q'];
			$html = file_get_contents('https://www.google.co.in/search?q='.$query.'+%2B+science+%2B+kids&espv=2&source=lnms&tbm=isch&sa=X&ved=0ahUKEwjwj5DKv7vMAhXLnpQKHTntB_UQ_AUICCgC');
			preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i',$html, $matches ); 
		
			$images_to_show = array();
			for($i=0; $i<sizeof($images); $i++)
				$images_to_show[$i] = $images[$i];

			$k = 2;

			for($j=$i; $j<12; $j++)
				$images_to_show[$j] = $matches[ 1 ][ $k++ ];
		?>


			
		<div class = "well well-lg" style="background-color:white;"> 
			<h3><p> <b> <font face="Bradley Hand ITC" style = "Bold" style="width:150px;height:200px;" color="Persian Blue">Pictures Speak A Thousand Words</font>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
			<br></br>
			
			<div class="row">
				<div class="col-sm-4" style="background-color:lavender;">
					<img class="center-block img-responsive" src="<?php echo $images_to_show[ 0 ];?>" align="left" style="width:128px;height:128px;">
					<img class="center-block img-responsive" src="<?php echo $images_to_show[ 1 ];?>" align="right" style="width:128px;height:128px;">
				</div>
				<div class="col-sm-4" style="background-color:lavender;">
					<img class="center-block img-responsive" src="<?php echo $images_to_show[ 2 ];?>" align="left" style="width:128px;height:128px;">
					<img class="center-block img-responsive" src="<?php echo $images_to_show[ 3 ];?>" align="right" style="width:128px;height:128px;">
				</div>
				<div class="col-sm-4" style="background-color:lavender;">
					<img class="center-block img-responsive" src="<?php echo $images_to_show[ 4 ];?>" align="left" style="width:128px;height:128px;">
					<img class="center-block img-responsive" src="<?php echo $images_to_show[ 5 ];?>" align="right" style="width:128px;height:128px;">
				</div>
			</div>

			<br></br>

			<div class="row">
				<div class="col-lg-4" style="background-color:lavender;">
					<img class="center-block img-responsive" src="<?php echo $images_to_show[ 6 ];?>" align="left" style="width:128px;height:128px;">
					<img class="center-block img-responsive" src="<?php echo $images_to_show[ 7 ];?>" align="right" style="width:128px;height:128px;">
				</div>
				<div class="col-sm-4" style="background-color:lavender;">
					<img class="center-block img-responsive" src="<?php echo $images_to_show[ 8 ];?>" align="left" style="width:128px;height:128px;">
					<img class="center-block img-responsive" src="<?php echo $images_to_show[ 9 ];?>" align="right" style="width:128px;height:128px;">
				</div>
				<div class="col-sm-4" style="background-color:lavender;">
					<img class="center-block img-responsive" src="<?php echo $images_to_show[ 10 ];?>" align="left" style="width:128px;height:128px;">
					<img class="center-block img-responsive" src="<?php echo $images_to_show[ 11 ];?>" align="right" style="width:128px;height:128px;">
				</div>
			</div>
		</div>
	</div>

<?php//===================================IMAGES-END==============================================?>
	<img class="img-responsive" src="Used_Images/learn.png" align="middle" style="width:400px;height:170px;">
	<br></br>

<?php//=====================================TEXT==================================================?>

	<div class="container-fluid">
		<div class = "well well-lg" style="background-color:lavender;"> 
						<p> <h2> <b> <font face="Helvetica" color="Black"> <?php echo strtoupper($_GET['q'])?> </font></b></h2></p></h3>
							<?php 
								//var_dump($definition);
								for($i=0; $i<sizeof($definition); $i++)
									echo $definition[$i];
							?>
		</div>
	</div>

<?php//======================================TEXTEND=============================================?>

<?php//=========================================VIDEO===============================================?>
	<div class="container-fluid">
		<div class = "well well-lg" style="background-color:lavender;"> 
			<?php 
			    // This code will execute if the user entered a search query in the form
			    // and submitted the form. Otherwise, the page displays the form above. //+++++ PLEASE REMEMBER CHANGE TO POST FROM GET 
			    if ($_GET['q'] ){//&& $_GET['maxResults']){ //change//{
			    // Call set_include_path() as needed to point to your client library.

				    require_once './google-api-php-client/src/Google_Client.php';
				    require_once './google-api-php-client/src/contrib/Google_YouTube_Service.php';
				   // $_GET['q'] = $_GET['q']." science for kids";
				    //echo $_GET['q'];
				   	$_GET['q'] = "what is the science behind ".$_GET['q']. " to kids ";

				     /*
				    * Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
				    * {{ Google Cloud Console }} <{{ https://cloud.google.com/console }}>
				    * Please ensure that you have enabled the YouTube Data API for your project.
				    */
				    $DEVELOPER_KEY = 'AIzaSyDYjMljqq2kloudUngj_NCAdOGhIix_FPc';
				      
				    $client = new Google_Client();
				    $client->setDeveloperKey($DEVELOPER_KEY);
				      
				    // Define an object that will be used to make all API requests.
				    $youtube = new Google_Service_YouTube($client);
				      
				    try {
					    // Call the search.list method to retrieve results matching the specified
					    // query term.
					    $searchResponse = $youtube->search->listSearch('id,snippet',array('q' => $_GET['q'],));//$_GET['q'],));// 'maxResults' => $_GET['maxResults'],)); 
					    $videos = '';
					    $channels = '';
					    $playlists = '';
					    $video_our = array();
					    //echo "help";
					    // Add each result to the appropriate list, and then display the lists of
					    // matching videos, channels, and playlists.
					    foreach ($searchResponse['items'] as $searchResult) {
					     	switch ($searchResult['id']['kind']) {
					       		case 'youtube#video':
					       		$videos .= sprintf('<li>%s (%s)</li>', $searchResult['snippet']['title'], $searchResult['id']['videoId']);
					       		array_push($video_our, $searchResult['id']['videoId']);
					       		break;
					        }
					    }
					     
					    $url = 'https://www.youtube.com/watch?v='.$video_our[0];
					    
					    preg_match('/[\\?\\&]v=([^\\?\\&]+)/',$url, $matches);
					    $id = $matches[1];
					    $width = '640';
					    $height = '385';
					    echo '<object width="' . $width . '" height="' . $height . '"><param name="movie" value="http://www.youtube.com/v/' . $id . '&amp;hl=en_US&amp;fs=1?rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $id . '&amp;hl=en_US&amp;fs=1?rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $width . '" height="' . $height . '"></embed></object>';
				    } 
				    catch (Google_Service_Exception $e) {
				  		$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
				  		htmlspecialchars($e->getMessage()));
				    } 
				    catch (Google_Exception $e) {
			  			$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
			  			htmlspecialchars($e->getMessage()));
				    }
				}
	 		?>
	 		<!--
	 		<br></br>
			<p> <h2> <b> <font face="Helvetica" color="Black"> Check More Videos Here: </font></b></h2></p>
			
			<p> <b> <font face="Helvetica" color="Black">
			<?php 
				/*for($i=0; $i<sizeof($videos_onto); $i++)
				{
					echo '<a href="#">';
					echo nl2br($videos_onto[$i]."\n");
					echo "</a>";	
				}*/?> 
			</font></b></p>-->
		</div>

		

	</div>

<?php//=======================================VIDEOEND===========================================?>

<?php//=======================================RELATEDWORDS=======================================?>

	<div class="container-fluid">
		<div class = "well well-lg" style="background-color:white;"> 	
			<img class="center-block img-responsive" src="Used_Images/kid1.jpg" align="left" style="width:128px;height:160px;">
			<img class="center-block img-responsive" src="Used_Images/kid2.jpg" align="right" style="width:128px;height:160px;">

			<h3><p><b><font face="Helvetica" color="Persian Blue">Hey, why don't you check out these as well?
				<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
		   		
		   		<div id="div1" class="fa"></div>

		   		<script>
					function smile() {
					  var a;
					  a = document.getElementById("div1");
					  a.innerHTML = "&#xf118;";
					  setTimeout(function () {
					      a.innerHTML = "&#xf11a;";
					    }, 1000);
					  setTimeout(function () {
					      a.innerHTML = "&#xf119;";
					    }, 2000);
					  setTimeout(function () {
					      a.innerHTML = "&#xf11a;";
					    }, 3000);
					}
					smile();
					setInterval(smile, 4000);
				</script>

			</font></b></p></h3>
			
			<div class="row">
				<div class="col-md-12" style="background-color:white;">
					<ul id="navigation">
					
				      <?php
				      	for($i=0; $i<sizeof($related_keywords); $i++) {
			      			echo "<li>";
			      			echo '<a href="#">';
			      			echo str_replace("_", " ", $related_keywords[$i]); 
			      			echo "</a>";
					      	echo "</li>";
				      	//str_repeat('&nbsp;', 5);
				      	}
				      ?>

				    </ul>
				</div>			
			</div>
		</div>
	</div>
	
<?php//=====================================RELATEDWORDSEND=====================================?>




	<style>
		body,html{margin:0;padding:0;height:100%;width:100%;}
		#bgimage {position:fixed;left:0;top:0;z-index:1;height:100%;width:100%;}
		#content {position:absolute;left:0;top:0;z-index:1;height:100%;width:100%;}
		
		.tab {
				margin-left: 0.5em
		}
		#navigation li {
		        list-style: none;
		        display: block;
		        float: left;
		        margin: 1em;
		}
		#navigation li a {
		        text-shadow: 0 2px 1px rgba(0,0,0,0.5);
		        display: block;
		        text-decoration: none;
		        color: #f0f0f0;
		        font-size: 1.6em;
		        margin: 0 .5em;
		}
		#navigation li a:hover {
		        margin-top: 2px;
		}

		#div1 {
		  font-size:48px;
		}
	</style>

</html>