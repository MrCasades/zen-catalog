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

//Проверка заполнения обязательных полей

const title = document.getElementById('posttitle')
const category = document.getElementById('category')
const text = document.getElementById('text')

const confirm = document.getElementById('confirm')


confirm.addEventListener('click', (event) => {
    if ((title.value === "") || (category.options.selectedIndex === 0) || 
        (text.value === "")){
        alert ('Заполните все обязательные поля!')
        event.preventDefault()	
    }

    else if (title.value.length > 200) {
        alert ('Длина заголовка превышена!')
        event.preventDefault()	
    }
})

//Подсчёт количества знаков в заголовке
const countTitleLen = document.getElementById('counttitlelen')

countTitleLen.innerHTML = title.value.length

title.addEventListener('input', (event) => {

        countTitleLen.innerHTML = title.value.length

        //Изменение цвета при привышении лимита
        if (title.value.length > 200){
            countTitleLen.style.color = ('red')
        }

        else {
            countTitleLen.style.color = ('black')
        }
})
