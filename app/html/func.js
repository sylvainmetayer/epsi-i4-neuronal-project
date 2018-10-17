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

	var isRightMB = false, 
	e = e || window.event;

	// Gecko (Firefox), WebKit (Safari/Chrome) & Opera
	if ("which" in e){
		isRightMB = e.which == 3; 
	}
	else if ("button" in e){
		isRightMB = e.button == 2; 
	}
	return isRightMB;
}