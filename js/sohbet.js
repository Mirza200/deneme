document.onkeydown = mesajGonder;

$(function(){
$("div#sohbetIcerik").animate({ scrollTop: $('div#sohbetIcerik').prop("scrollHeight")}, 1000);
});
setInterval(function(){
$("div#sohbetIcerik").animate({ scrollTop: $('div#sohbetIcerik').prop("scrollHeight")}, 1000);
}, 2000);

function mesajGonder(x) {
	var tus;
	if (window.event) {
		tus = window.event.keyCode;
	}else if (x) {
		tus = x.which;
	}
	if (tus == 13) {

		$("textarea[name=mesaj]").attr("disabled","disabled");
		var mesaj = $("textarea[name=mesaj]").val();
		
		
		$.ajax({
			type: "POST",
			url: "chat.php",
			data: {"tip":"gonder","mesaj":mesaj},

			success: function(sonuc){
				if(sonuc == "bos") {
					alert ("Lütfen boş mesaj göndermeyin...");
					$("textarea[name=mesaj]").removeAttr("disabled");
				}else{
					$("textarea[name=mesaj]").removeAttr("disabled");
					$("textarea[name=mesaj]").val("");

					sohbetGuncelle();

					var audio = new Audio('ses/enter.mp3');
					audio.play()
				}
			}
		});
	}
}
function sohbetGuncelle(){

	$.ajax({
		type: "POST",
		url: "chat.php",
		data: {"tip":"guncelle"},
		success: function(sonuc){
			$("#sohbetIcerik").html(sonuc);
			
		}
	});

}

function sohbetTemizle(){
	$.ajax({
		type: "POST",
		url: "chat.php",
		data: {"tip":"temizle"},
		success: function(sonuc){
			alert(sonuc);
		}
	});
}
sohbetGuncelle();
setInterval("sohbetGuncelle()",1000);


