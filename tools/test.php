<?php
header('Content-Type:text/html; charset=utf-8'); 
require './api/func.inc.php';
if(file_exists('img')){
	exec("rm -r img");
}
createPaths(["img"]);
?><!DOCTYPE html>
<html>
<head>
	<title>Dessiner lettre</title>
</head>
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="func.js"></script>
<style type="text/css">
	html, body{
		background: #66ff66;
	}
</style>
<body>
<div id="editor">
	<canvas id="c" width="64" height="64"></canvas>
	<button id="clear">x</button>
	<input id="cletter" type="text" disabled>
</div>
<div id="result">
<?php

$letters = array_slice(scandir('./.cache/gameset'), 2);

foreach ($letters as $key => $letter) {

	echo "<div id=\"result-$letter\" class=\"resultcontainer\"><span class=\"letter\">$letter</span>";


	$gamesets =  explode("\n", file_get_contents("./.cache/gameset/$letter"));

	foreach ($gamesets as $gameset) {
		$name = makeImage($gameset);
		echo "<img src=\"./.cache/img/$name.jpg\">";
		
	}

	echo "</div>";
}

?>

</div>
<script type="text/javascript">
	var el = document.getElementById('c'),
	ctx = el.getContext('2d'),
	miniCanvas =document.createElement('canvas'),
	miniCtx = miniCanvas.getContext('2d'),
	isDrawing=false,
	letter=document.getElementById('cletter');

	ctx.imageSmoothingEnabled=false;
	ctx.lineWidth=5;

	miniCanvas.width = 16;
	miniCanvas.height = 16;

	el.onmousedown = function(e) {
		if(!isRightClick(e)){
			isDrawing = true;
			ctx.moveTo(e.clientX-el.offsetLeft, e.clientY-el.offsetTop);
		}	
	};
	
	el.onmousemove = function(e) {
		if (isDrawing) {
			ctx.lineTo(e.clientX-el.offsetLeft, e.clientY-el.offsetTop);
			ctx.stroke();
		}
	};
	
	document.onmouseup = function() {
		isDrawing = false;
	};

	document.getElementById('clear').onclick = function(){
		ctx.clearRect(0, 0, el.width, el.height);
		ctx.restore();
		ctx.beginPath();

		letter.value="";
	}

	window.oncontextmenu = function (){

		var tmpImage = new Image();
		tmpImage.src=el.toDataURL("image/png");
		tmpImage.onload = function(e){
			
			miniCtx.drawImage(tmpImage,0,0,16,16);

			var matrix =getMatrix(miniCanvas);

			miniCtx.clearRect(0, 0, el.width, el.height);
			miniCtx.restore();
			miniCtx.beginPath();


			var xhr = getXMLHttpRequest();
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4) {
					console.log(xhr.status);
					switch (xhr.status) {
						case 200:
							letter.value = xhr.responseText;
						break;
						default:
							console.log("fail");
					}
				}
			};

			xhr.open("POST", "./api/network.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send("input="+ matrix);
		}

		return false;
	};
</script>
</body>
</html>