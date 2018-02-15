<?php
header('Content-Type:text/html; charset=utf-8'); 
require 'func.inc.php';
if(file_exists('img')){
	exec("rm -r img");
}
createPaths(["img"]);
?><!DOCTYPE html>
<html>
<head>
	<title>Dessiner lettre</title>
</head>
<style type="text/css">
	*{
		box-sizing: border-box;
	}
	html,body{
		margin: 0;
		padding: 0;
		background: #66ff66;
	}
	canvas { 
		border: 1px solid #ccc;
		vertical-align: top;
		cursor: crosshair;
	}

	#result {
		display: flex;
		flex-direction: column-reverse;
	}

	.resultcontainer{
		display: flex;
		height: 16px;
	}

	.resultcontainer > * {
		display: flex;
		width: 16px;
		height: 16px;
		border: 1px solid #ccc;
		
		font-size: 12px;
		text-align: center;

		justify-content: center;
		align-items: center;
		align-content: center; 
	}

	#cletter {
		border: 1px solid #ccc;
		font-size: 65px;
		line-height: 65px;
		vertical-align: middle;
		display: inline-block;
		width: 66px;
		height: 66px;
		text-align: center;
	}

	#editor{
		text-align: center;
		padding: 5px;
	}


</style>
<body>
<div id="editor">
	<canvas id="c" width="64" height="64"></canvas>
	<button id="clear">x</button>
	<input id="cletter" type="text" >
</div>
<div id="result">
<?php

$letters = array_slice(scandir('./gameset'), 2);

foreach ($letters as $key => $letter) {

	echo "<div id=\"result-$letter\" class=\"resultcontainer\"><span class=\"letter\">$letter</span>";


	$gamesets =  explode("\n", file_get_contents("./gameset/$letter"));

	foreach ($gamesets as $gameset) {
		$name = makeImage($gameset);
		echo "<img src=\"./img/$name.jpg\">";
		
	}

	echo "</div>";
}

?>
</body>
<script type="text/javascript">

	function getXMLHttpRequest() {
		var xhr = null;
		
		if (window.XMLHttpRequest || window.ActiveXObject) {
			if (window.ActiveXObject) {
				try {
					xhr = new ActiveXObject("Msxml2.XMLHTTP");
				} catch(e) {
					xhr = new ActiveXObject("Microsoft.XMLHTTP");
				}
			} else {
				xhr = new XMLHttpRequest(); 
			}
		} else {
			alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
			return null;
		}
		
		return xhr;
	}

	function getMatrix(canva){
		var oCtx = canva.getContext('2d');
		var imgData=oCtx.getImageData(0,0,canva.width, canva.height);
		var matrix = "";
		for (var i=0;i<imgData.data.length;i+=4)
			matrix += (imgData.data[i+3] < 128) ? "0" : "1";
		return matrix;
	}

	function isRightClick(e){
		var isRightMB = false;
		e = e || window.event;

		// Gecko (Firefox), WebKit (Safari/Chrome) & Opera
		if ("which" in e){
			isRightMB = e.which == 3; 
		}
		else if ("button" in e){ // IE, Opera 
			isRightMB = e.button == 2; 
		}

		return isRightMB;
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

		var letterExpected = letter.value.charAt(0);

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
			xhr.open("POST", "save.php", true);
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