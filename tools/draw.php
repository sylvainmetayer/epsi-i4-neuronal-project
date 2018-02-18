<?php
header('Content-Type:text/html; charset=utf-8'); 
require './api/func.inc.php';
if(file_exists('./.cache/img')){
	exec("rm -r ./.cache/img");
}
createPaths(["./.cache/img"]);
?><!DOCTYPE html>
<html>
<head>
	<title>Dessiner lettre</title>
</head>
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="func.js"></script>
<style type="text/css">
	html, body{
		background: #ff4d4d;
	}
</style>
<body>
	<div id="editor">
		<canvas id="c" width="64" height="64"></canvas>
		<button id="clear">x</button>
		<input id="cletter" type="text">
	</div>
	<div id="result">
	<?php

	$letters = array_slice(scandir('./.cache/gameset'), 2);

	foreach ($letters as $key => $letter) {

		echo "<div id=\"result-$letter\" class=\"resultcontainer\"><span class=\"letter\">$letter</span>";


		$gamesets =  explode("\n", file_get_contents("./.cache/gameset/$letter"));

		foreach ($gamesets as $gameset) {
			$name = makeImage($gameset);
			echo "<img src=\"./.cache/img/$name.jpg\"  data-letter=\"$letter\"onclick=\"deleteDataSet(this)\" >";
			
		}

		echo "</div>";
	}

	?>
	</body>
	<script type="text/javascript">
		var deleteDataSet = function (img) {
			var parent = img.parentNode;
			var index = Array.prototype.indexOf.call(parent.children, img) -1;

			var xhr = getXMLHttpRequest();
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4) {
						switch (xhr.status) {
							case 200:
								parent.removeChild(img);
								console.log("deleted");
							break;
							default:
								console.log("fail");
						}
					}
				};

			xhr.open("POST", "./api/save.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send("expected="+ img.getAttribute("data-letter") +"&delete="+index);
		}
		
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

		window.oncontextmenu = function (){

			var letterExpected = letter.value;

			var result = document.getElementById('result-' + letterExpected);

			if(result === null){
				result = document.createElement('div');
				result.innerHTML = "<span class=\"letter\">"+letterExpected+"</span>";
				result.id = 'result-' + letterExpected;
				result.className = 'resultcontainer';

				document.getElementById('result').appendChild(result);
			}

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
						switch (xhr.status) {
							case 201:
								var img = document.createElement("img");
								img.setAttribute("data-letter", letterExpected);
								img.onclick=function(e){
									deleteDataSet(this);
								};
								result.appendChild(img);
								img.src=xhr.responseText;
								console.log("created");
							break;
							case 200:
								console.log("exist déjà");
							break;
							default:
								console.log("fail");
						}
					}
				};

				xhr.open("POST", "./api/save.php", true);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhr.send("expected="+letterExpected+"&input="+matrix);
			}

			ctx.clearRect(0, 0, el.width, el.height);
			ctx.restore();
			ctx.beginPath();
			

			return false;     // cancel default menu
		};

		document.getElementById('clear').onclick = function(){
			ctx.clearRect(0, 0, el.width, el.height);
			ctx.restore();
			ctx.beginPath();
		}

	</script>
</body>
</html>