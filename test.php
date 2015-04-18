<!DOCTYPE HTML>
<html>
<head>
<title>SVG Dice Rolling</title>
<!-- 
SVG Dice Rolling Script, Written by Dylan M. Taylor. [http://code.dylanmtaylor.com/dice/]
This page is heavily modified from Taylor Copeland's dice script, at http://taylorcopeland.com/jsDice/.
The script has been rewritten in PHP to use inline SVG images instead of static PNGs, 
and instead of replacing the contents of divs, it simple changes visibility using JavaScript.
If inline SVGs are not supported, the user is redirected to a version of the page that uses 
<object> tags to display SVG images in the page. Because of the way the object tag works, if
SVG images are not supported in the browser, static, highly compressed PNG images will be displayed.
The code that detects whether inline SVG is supported was originally written by Niels Leenheer.

The complete source code (including PHP), and all of the images (both vector, and rasterized), as well
as all scripts used in the creation of this page is available at http://code.dylanmtaylor.com/dice/dice.7z

If you use any of the source code in this project, please give proper attribution where necessary.
-->
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<style type="text/css">  
div.die1 {
 position:absolute;
 top:15px;
 left:15px;
 width:300px;
 height:300px;
 display:none;
}
div.die2 {
 position:absolute;
 top:15px;
 left:330px;
 width:300px;
 height:300px;
 display:none;
}
div.button {
 position:absolute;
 top:315px;
 width:630px;
}
</style>
<script type="text/javascript">
	var dice = new Array(0,0);
	var animInterval;
	var rolls = 0;
	function prepare() {
<?php
	if (!isset($_GET["object"]) && !isset($_GET["embed"])) {
		echo "//inline SVG support checking is based off of 'The HTML5 test' by Niels Leenheer\n";
		echo "var e = document.createElement('div');\n";
		echo "e.innerHTML = '<svg></svg>';\n";
		echo "var passed = e.firstChild && \"namespaceURI\" in e.firstChild && e.firstChild.namespaceURI == 'http://www.w3.org/2000/svg';\n";
		echo "if (!passed) { \n";
		echo "	alert('Your browser does NOT support inline SVG.');\n";
		echo "	window.location = \"./\"+returnDocument()+\"?object\";\n";
		echo "}\n";
	}
?>	roll();
	}

	function returnDocument() {
        var file_name = document.location.href;
        var end = (file_name.indexOf("?") == -1) ? file_name.length : file_name.indexOf("?");
        return file_name.substring(file_name.lastIndexOf("/")+1, end);
    }

	function animate() {
		rolls = 0;
		clearInterval(animInterval);
		animInterval = window.setInterval('roll()',5);
	}

	function roll() {
		dice[0] = Math.floor(Math.random() * 6) + 1;
		dice[1] = Math.floor(Math.random() * 6) + 1;
		for (c = 1;c < 7; c++)	{
			for (d = 0; d < 2; d++) {
				if (c != dice[d]) {	document.getElementById(("die" + (d+1) + "-" + c)).style.display='none';
				} else { document.getElementById(("die" + (d+1) + "-" + c)).style.display='block'; }
			}
		}
		if ((rolls = rolls + 1) > 100) {
			clearInterval(animInterval);
		}
	}
</script>
</head>
<body onload="prepare()">
<?php 
	for ($c = 1; $c < 7; $c++) {
		for ($d = 1; $d < 3; $d++) {
			echo "<div id=\"die$d-$c\" class=\"die$d\">";
			#here we are checking if certain get parameters exist, in order to determine how to format the page
			if (isset($_GET["object"])) {
				echo "<object data=\"dice-$c"."s.svg\" type=\"image/svg+xml\" width=\"100%\" height=\"100%\" class=\"img\">";
				#in between the start/end tags for object is the contents that is displayed if the object is not supported.
				#in this case, we are using rasterized PNG images.
				echo "<img src=\"dice-$c"."s.png\" width=\"100%\" height=\"100%\">";
				echo "</object>";
			} else if (isset($_GET["embed"])) { 
				echo "<embed type=\"image/svg+xml\" src=\"dice-$c"."s.svg\" width=\"100%\" height=\"100%\">"; #there is no end tag for embed.
			} else { #if not, default to using inline SVG
				echo substr(file_get_contents('dice-'.$c.'s.svg'),264);
			}
			echo "</div>\n";		
		}			
	}
?>
	<div class="button" style="text-align: center;">
	<form action="#">
		<p><input type="button" onclick="animate()" value="Roll The Dice!" /></p>
	</form>
	</div>
</body>
