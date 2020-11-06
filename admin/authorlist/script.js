//Вывод сообщения о подтверждении удаления автора!

function confirmDelAuthor() {
	delMessage = confirm('Вы уверены, что хотите удалить этого автора? Данное действие может привести к необратимым последствиям!')
								 if (delMessage === false)
								 {
									 return false
									 event.preventDefault()
								 }	
	
								else
								{
									return true
								}
}