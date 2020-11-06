const incorr = document.getElementById('incorr')//сообщение об ошибке

//Изменение гонорара

const priceText = document.getElementById('pricetext')

const addBonus = document.getElementById('addbonus')

const defaultPrice = priceText.innerHTML


addBonus.addEventListener('input', (event) => {

    if ((!addBonus.value) || (isNaN(addBonus.value))){
        priceText.innerHTML = defaultPrice
    }

    else{

        priceText.innerHTML = parseFloat(defaultPrice) + parseFloat(addBonus.value)
		incorr.innerHTML = ''
	}
})

//Проверка на число

const checkNum = document.getElementById('checknum')

const confirm = document.getElementById('confirm')

confirm.addEventListener('click', (event) => {
    if ((isNaN(checkNum.value)) || ((checkNum.value < 0) || (checkNum.value > 100))){ 
        incorr.innerHTML = 'Данные некорректны! Введите любое число от 0 до 100'
        event.preventDefault()	
    }

    else if ((addBonus.value < -(defaultPrice*0.25)) || ((addBonus.value > defaultPrice*0.25))){
        incorr.innerHTML = 'Размер бонуса / вычета превышен! Он не может составлять более 25% от гонорара.'
        event.preventDefault()	
    }
})