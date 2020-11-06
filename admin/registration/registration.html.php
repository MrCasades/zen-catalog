<?php 
/*Загрузка функций в шаблон*/
include_once MAIN_FILE . '/includes/func.inc.php';

/*Загрузка header*/
include_once MAIN_FILE . '/header.inc.php';?>
	<p><a name="bottom"></a></p>  
	<div class = "maincont_for_view">
	<div class = "post">
	  <div  align="justify">
		<p>Зарегестрируйтесь в системе, чтобы получить возможность добавить в каталог свой канал, добавлять каналы других пользователей в избранное и предлагать взаимные действия.</p>

		  <p><h3>По всем вопросам:</h3>
		  	<ul>
			  <li>Telegramm: @PolyakoffArs</li>
			  <li>E-mail: imagozman@gmail.com</li>
			  <li>VKontakte: <a href="https://vk.com/id213646416" rel="nofollow">Арсений Поляков</a></li>
			</ul>
		  </p>
	  </div>	  
	<div  align="center">
	<strong><p id = "incorr" style="color: red"><?php htmlecho($errLog);?></p></strong>
	<form action = "?<?php htmlecho ($action); ?>" method = "post">
	 <table>
		 <tr>
			<th>Имя автора:* </th><td><input type = "text" name = "authorname" id = "authorname" value = "<?php htmlecho($authorname);?>"></td>
		 </tr>			 
		 <tr>
			<th>E-mail:* </th><td><input type = "text" name = "email" id = "email" value = "<?php htmlecho($email);?>"></td> 	
		 </tr>			
		 <tr>
			<th>Пароль:* </th><td><input type = "password" name = "password" id = "password" value = "<?php htmlecho($password);?>"></td> 		
		 </tr>			
		 <tr>
			<th>Повторить пароль:* </th><td><input type = "password" name = "password2" id = "password2" value = "<?php htmlecho($password2);?>"></td> 	
		 </tr>		
	 </table>
	 <br>	 
			<p><div class="g-recaptcha" data-sitekey="<?php echo SITE_KEY;?>"></div></p>
			<p><input type = "submit" value = "<?php htmlecho($button); ?>" class="btn btn-primary" id = "confirm"></p>
	</form>
	</div>
	</div>
	</div>
		
<?php 
/*Загрузка footer*/
include_once MAIN_FILE . '/footer.inc.php';?>