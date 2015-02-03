/*
 * @author Max Severin <makc.severin@gmail.com>
 */

$(document).ready(function(){

	$("{$yoss_settings.idHtml}").keyup(function(){
		var t = $(this);
		if ( t.val().length >= {$yoss_settings.minCharCount} ) {
			var result = $('<div/>');

			$.ajax({
				type: "POST",
				url: "{$search_url}",
				data: "query="+t.val(),
				success: function(result){
					console.log(result);
					var msg = $.parseJSON(result);
					if (msg.data === true) {
						$('.call-b-input').remove();
						$('.call-b-form').append(
							'<p class="call-b-ok">Спасибо ' + n + ',</p>' +
							'<p class="call-b-ok">Ваше сообщение отправлено!</p>' +
							'<div class="call-b-input"><input id="call-b-close" type="button" value="Закрыть" /></div>'
							);
					}
				}
			}, 'json');
		} else {
			return false;
		}
	});

});