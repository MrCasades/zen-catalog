//Вывод сообщения о подтверждении обнуления конкурсных баллов.

const resetСontest = document.querySelector('#resetcontest')

if (resetСontest)
{
 	resetСontest.addEventListener('click', (event) => {confDel = confirm('Вы уверены, что хотите обнулить конкурсные баллы?')
								 if (confDel === false)
								 {
									 event.preventDefault();
								 }
							}, false)
}
