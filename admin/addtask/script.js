//Все ли данные введены!

const idPromotionFrom = document.getElementById('idpromotionfrom')//выбрана ли рубрика
const description = document.getElementById('description')//выбрана ли рубрика
const incorr = document.getElementById('incorr')//сообщение об ошибке
const incorrDesc = document.getElementById('incorrdesc')//сообщение об ошибке
const confirm = document.getElementById('confirm')

confirm.addEventListener('click', (event) => {
    
    switch (true) {

        case  idPromotionFrom.options.selectedIndex === 0:   
            incorr.innerHTML = 'Выберете свой канал!'
			if (incorrDesc.innerHTML !== '') incorrDesc.innerHTML = ''
            event.preventDefault()
            break 
        
        case  description.value === '':   
            incorrDesc.innerHTML = 'Добавьте описание взаимного действия!'
			if (incorr.innerHTML !== '') incorr.innerHTML = ''
            event.preventDefault()
            break 
    }
})