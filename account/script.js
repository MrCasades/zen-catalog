//Вывод сообщения об успешной рекомендации статьи!

const recommOk = document.querySelector('#recommok')

if (recommOk)
{
	recommOk.addEventListener('click', () => alert('Вы успешно рекомендовали статью! Она сейчас лидирует в списке рекомендаций!'))
}

//Вывод сообщения о подтверждении премодерации!

const confirmOk = document.querySelector('#confirmok')

if (confirmOk)
{
 	confirmOk.addEventListener('click', (event) => {confOk = confirm('Вы уверены, что хотите отправить статью на премодерацию? Вы больше не сможете внести в неё правки. Произойдёт перенаправление на главную страницу!')
								if (confOk === false)
								{
									 event.preventDefault();
								}	
							}, false)
											
}

//Вывод сообщения о подтверждении голосования.

const confirmLike = document.querySelector('#confirmlike')

if (confirmLike)
{
 	confirmLike.addEventListener('click', (event) => {confLk = confirm('Вы уверены, что хотите проголосовать за данный материал')
								 if (confLk === false)
								 {
									 event.preventDefault();
								 }
							}, false)
}

//Вывод сообщения о подтверждении удаления объекта.

const confirmDel = document.querySelector('#delobject')

if (confirmDel)
{
 	confirmDel.addEventListener('click', (event) => {confDel = confirm('Вы уверены, что хотите удалить данный оъект? Данное действие может привести к НЕОБРАТИМЫМ последствиям!')
								 if (confDel === false)
								 {
									 event.preventDefault();
								 }
							}, false)
}

//Вывод сообщения о подтверждении обнуления конкурсных баллов.

const removeContest = document.querySelector('#removecontest')

if (removeContest)
{
 	removeContest.addEventListener('click', (event) => {confDel = confirm('Вы уверены, что хотите обнулить конкурсные баллы?')
								 if (confDel === false)
								 {
									 event.preventDefault();
								 }
							}, false)
}
