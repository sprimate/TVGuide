<html>
<META http-equiv='Content-Type' content='text/html; charset=UTF-8'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="html/css/style.css">
<body>
	<div class = "iframeContainer" >
		<iframe id="tvFrame"></iframe>
	</div>
	<br>
	<button class="MuteComputer">Mute</button>
	<button class="LastChannel">Last</button>
	<button class="PopoutToggle">Toggle Popout</button>
	<button class="GuideToggle">Toggle Guide</button>
	<div class="container">
	    <div class="header"><span></span>

	    </div>
	    <div class="content">

	    <script>
	var xfinityDefaultWidth = 928;
	var xfinityDefaultHeight = 630;
	var currentURL;
	var lastURL;

	var tvWindow;
	var inFrame = true;

	function openTVWindow(url) {
		console.log("URL: " + url);
		//var xfinityDefaultWidth = 916;
		//var xfinityDefaultHeight = 514;
		lastURL = currentURL;
		if (!inFrame)
		    tvWindow = window.open(url, "TVWindow", "width=" + xfinityDefaultWidth+ ",height=" + xfinityDefaultHeight);
		else{
		   // var tvWindow = window.open(url, "TVWindow", "width=" + screen.width + ",height=" + screen.height);
		    var tvFrame = document.getElementById("tvFrame");
		    tvFrame.src = url;
		    tvFrame.style.width = xfinityDefaultWidth;
		    tvFrame.style.height = xfinityDefaultHeight;
		    window.scrollTo(0, 0); //probs not working
		    console.log("I'm here");
		}
		    document.cookie = "lastChannel=" + url + ";";
			currentURL = url;


	    //write last channel
	    //tvWindow.blur();
	    //window.self.focus();
	}
	function togglePopout()
	{
		var tvFrame = document.getElementById("tvFrame");
		if (!inFrame){
			inFrame = true;

			openTVWindow(currentURL);
			tvWindow.close("TVWindow");

   	 	}
   	 	else
   	 	{
			inFrame = false;
   	 		console.log(tvFrame.src);
	    	tvWindow = window.open(currentURL, "TVWindow", "width=" + xfinityDefaultWidth+ ",height=" + xfinityDefaultHeight);
	    	tvFrame.src = "";
	    	tvFrame.style.width = 0;
	    	tvFrame.style=height=0;
			tvWindow.document.onload = function () 
			{
				console.log("Loaded.")
				tvWindow.onunload = function()
				{
					console.log( "Unloading.");
					togglePopout();
					//return "Do you really want to close";
				};
			};
   	 	}
	}

	$(".GuideToggle").click
		(
			function () 
			{
			  	var tvFrame = document.getElementById("tvFrame");
			    $header = $(this);
			    //getting the next element
			    $content = $header.next();

			  	if ($content.is(":visible")){
			  		tvFrame.style.width = "95%"
			  		tvFrame.style.height = "95%"

			  	}
			  	else{
			    	tvFrame.style.width = xfinityDefaultWidth;
			    	tvFrame.style.height = xfinityDefaultHeight;	  	
			 	}

			    //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
			    $content.slideToggle(500, function () {
			        //execute this after slideToggle is done
			        //change text of header based on visibility of content div
			        $header.text(function () {
			            //change text based on condition
			            return $content.is(":visible") ? "Collapse" : "Expand";
			        });
			    });
			  	var tvFrame = document.getElementById("tvFrame");
			}
		);
		$(".PopoutToggle").click
		(
			function () 
			{ 
				togglePopout();
			}
		);
		$(".LastChannel").click
		(
			function () 
			{ 
				if (lastURL != null)
					openTVWindow(lastURL);
			}
		);

		$(".MuteComputer").click
		(
			function () 
			{ 
				if (lastURL != null)
					openTVWindow(lastURL);
			}
		);

	if (document.cookie.indexOf("lastChannel") > -1)
	{
		openTVWindow(document.cookie.substring(12));
	}


	window.onunload = function () {
		if (tvWindow != null)
		{
			tvWindow.close("TVWindow");
		}
    //return "Do you really want to close?";
};
</script>
			<?
				$tvGuideURL = "http://titantv.com";
				$tvListingsFile = "./tvListings.html";
				//$tvGuideHTML = file_get_contents($tvGuideURL);

				//$ch = curl_init();

				//Test on PC (UserAgent cookie) and on a different ip address (ANONUserCookie)
				$tvGuideHTML = exec("curl 'http://titantv.com/' -H 'Accept-Encoding: gzip, deflate, sdch' -H 'Accept-Language: en-US,en;q=0.8' -H 'Upgrade-Insecure-Requests: 1' -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8' -H 'Cache-Control: max-age=0' -H 'Cookie: TitanTVAnonUser=rIVpVFK30gEkAAAAZjFlYjA3YjItYzIwNC00ZjUxLWE1ZWYtNjZkZWZiZGZiNmE5rTbb5fcP0qn1WGBe4dYrL-UL2m01; __qca=P0-355929783-1460880768895; __qseg=Q_D; __utmt=1; __utma=7688503.1824097927.1460880771.1460880771.1460922961.2; __utmb=7688503.1.10.1460922961; __utmc=7688503; __utmz=7688503.1460880771.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none)' -H 'Connection: keep-alive' --compressed > " . $tvListingsFile);
				//echo $tvGuideHTML;
				// set URL and other appropriate options
				/*curl_setopt($ch, CURLOPT_URL, $tvGuideURL);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
				*/
				//$tvGuideHTML = curl_exec($ch);

				//curl_close($ch);
				$channelJSONLocation = "./channels.json";

				$channelJSON = file_get_contents($channelJSONLocation);
				$channels = json_decode($channelJSON, true);
				libxml_use_internal_errors(true); //hide warnings for html i'm pulling
				$doc = new DOMDocument();
				//$doc->loadHTMLFile($tvGuideURL);
				$doc->loadHTMLFile($tvListingsFile);
				libxml_clear_errors();

				$gridClassname="gridTable";
				$xpath = new DomXPath($doc);
				$table = $xpath->query("//*[contains(@class, '$gridClassname')]")->item(0);

				$networkClassName="gridNetworkText";
				if (!is_null($table)) 
				{
					//echo "OG Size: " . sizeof($channels);
					$table->setAttribute("border", 1);
					$table->setAttribute("overflow", "hidden");
					$table->setAttribute("padding", 1);

					$nodesToRemove = array();
					$childNodes = $table->childNodes;

				 	$firstRow = true;
					foreach ($childNodes as $node) {
					 	$wantedChannel = false;
						foreach ($channels as $channel) 
						{
							$id = $channel["id"];

							//$domList = $xpath->query("//*[contains(@class, '$networkClassName')]", $node);
							//$nodeString = $doc->saveHTML($table);
							$string1ToFind = '<span class="gridNetworkText">' . $id . "</span>";
							$string2ToFind = '<span class="gridCallSignText">'.$id.'</span>';
							if (strpos($doc->saveHTML($node), $string1ToFind) !== false || strpos($doc->saveHTML($node), $string2ToFind) ) 
							{
								$wantedChannel = true;
								//unset($channels[$index]);
								//echo "Length now: " . sizeof($channels) . "</br>";
								break;
							}
						}  
						if (!$firstRow && !$wantedChannel)
						{
							array_push($nodesToRemove, $node);
						} 
						else
						{
							foreach ($node -> childNodes as $td)
							{
								if ($td->nodeName == "td")
								{
									if (!$firstRow)
										//$td->setAttribute("onclick", "location.href = '" . $channel["url"] . "';" );
										$functionToSend = 'openTVWindow("' . $channel["url"] . '")';
										$td->setAttribute("onclick", $functionToSend);

									foreach($td -> childNodes as $child)
									{
										if ($child->nodeName == "img")
										{
											$child->setAttribute("src",$tvGuideURL . $child->getAttribute("src"));
											$child->setAttribute("style", "");
										}
										else if ($child->nodeName == "a")
										{
											$child->setAttribute("href",$channel["url"]);
										}
									}
								}
							}
						}
						$firstRow = false;
					}

					foreach($nodesToRemove as $node)
					{
						$table->removeChild($node);
					}
					$html = $doc->saveHTML($table);
					str_replace('Â«', '', $html, $count);
					//echo 'Replaced ' . $count;
					$html = str_replace('Â', '', $html,$count);
					//echo 'Replaced ' . $count;


					echo $html;

				}
				else
					echo "table is Null";
			?>
		</div>
	</div>


</body>
</html>	
