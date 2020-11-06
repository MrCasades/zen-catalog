//Вывод сообщения о подтверждении премодерации!

/* AJAX JQuery */

//Добавление-удаление из избранного
$( document ).ready(function() {
    $("#confirm").click(
		function(){
			//$("#ajax_form_conf").html(' ');
			sendAjaxForm('result_form_conf', 'ajax_form_conf', 'confirm.inc.php');
			$("#ajax_form_conf").html('<div align="center"><a href="#" onclick="history.back();" class="btn btn-primary btn-sm">К другим действиям!</a></div>');
			console.log('OK1');
			
			/*if ($("#val_fav").attr('value') === 'delfav'){
				$("#val_fav").attr('value', 'addfav');
				$("#btn_fav").attr('src', 'like_1.gif');
				
			} else {
				$("#val_fav").attr('value', 'delfav');
				$("#btn_fav").attr('src', 'like_2.gif');
			}*/
			
			return false; 
		}
	);
});

//Функция AJAX
function sendAjaxForm(result_form, ajax_form, url) {
    $.ajax({
        url:     url, //url страницы (action_ajax_form.php)
        type:     "POST", //метод отправки
        //dataType: "html", //формат данных
        data: $("#"+ajax_form).serialize(),  // Сеарилизуем объект
        success: function(response) { //Данные отправлены успешно
        	//result = $.parseJSON(response);
			//$('#'+ajax_form).html('Ожидание...');
			//$('#'+ajax_form).html('');
        	//$('#'+result_form).html(' ');
			console.log('OK');
    	},
    	error: function(response) { // Данные не отправлены
            $('#result_form').html('Ошибка. Данные не отправлены.');
			console.log('no');
    	}
 	});
}
