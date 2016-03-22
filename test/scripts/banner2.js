((function () {
	var el = document.createElement("div");
	el.innerHTML = "<div style='width:90%; background-color: blue; color: white; font-size:20px; text-align:center; padding:20px'>This banner has been added via banner2.js</div>";
	document.body.insertBefore(el, document.body.firstChild);
})());