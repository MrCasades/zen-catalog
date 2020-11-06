//Проверка на корректность выводимой суммы

const score = parseFloat(document.getElementById('score').innerHTML)//получить размер счёта
const payment = document.getElementById('payment')//сумма платежа
const incorr = document.getElementById('incorr')//сообщение об ошибке
const confirm = document.getElementById('confirm')

confirm.addEventListener('click', (event) => {
    
    switch (true) {
        case isNaN(payment.value):
             incorr.innerHTML = 'Вы ввели некорректные данные'
             event.preventDefault()
             break

        case  payment.value < 0:   
            incorr.innerHTML = 'Cумма должна быть больше 0'
            event.preventDefault()
            break 
        
        case  payment.value < 30:   
            incorr.innerHTML = 'Минимум к выводу 30 руб.'
            event.preventDefault()
            break 
            
        case  payment.value > score:   
            incorr.innerHTML = 'Вывести можно сумму меньше или равную ' + score 
            event.preventDefault()
            break 
    }
})