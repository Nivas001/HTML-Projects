var screen = '';

var store = [];
var inputElement = document.querySelector('#input');

//TODO : If mc is clicked during ms in visibility it need to change the value of ms.

//MC button
document.querySelector('.mc').addEventListener('click', function(){
    store = [];
    console.log(store);
})

//MR button
document.querySelector('.mr').addEventListener('click', function(){
    screen = store[store.length - 1];
    inputElement.value = screen;
    console.log(screen);
})

//M+ button
document.querySelector('.mplus').addEventListener('click', function(){
    store.push(screen);
    console.log(store);
})

//M- button
document.querySelector('.msub').addEventListener('click', function(){
    store.pop();
    console.log(store);
})

document.querySelector('.store').style.visibility = 'hidden';

//m_store button
document.querySelector('.mstore').addEventListener('click', function(){
    var element = document.querySelector('.store');
    if (element.style.visibility === 'hidden') {
        element.style.visibility = 'visible';
        const values = document.querySelector('.values');
        values.innerHTML = '';
        for(let i=0; i<store.length; i++){
            const valueElement = document.createElement('div');
            valueElement.textContent = store[i];
            values.appendChild(valueElement);
        }
    }
    else{
        element.style.visibility = 'hidden';
    }

})


//Keyboard input
document.addEventListener('keydown', function(event) {
    // Check if the key pressed is a number or an operator
    if ((event.key >= '0' && event.key <= '9') || ['+', '-', '*', '/', '.', '(', ')'].includes(event.key)) {
        screen += event.key;
        inputElement.value = screen;
    } else if (event.key === 'Enter') {
        // If Enter key is pressed, evaluate the expression
        document.querySelector('.eq').click();
    } else if (event.key === 'Backspace') {
        // If Backspace key is pressed, delete the last character
        screen = screen.slice(0, -1);
        inputElement.value = screen;
    } else if (event.key === 'Escape') {
        // If Escape key is pressed, clear the input
        screen = '';
        inputElement.value = screen;
    }
});


// Clear button
document.querySelector('.clear').addEventListener('click', function(){
    screen = '';
    inputElement.value= screen;
})

//Delete button
document.querySelector('.del').addEventListener('click', function(){
    screen = screen.slice(0, -1);
    inputElement.value = screen;
})

//Permutation button
document.querySelector('.permutation').addEventListener('click', function(){
    screen += '!';
    inputElement.value = screen;
})

//Square button
document.querySelector('.sq').addEventListener('click', function(){
    screen += '²';
    inputElement.value = screen;
})

//sub button
document.querySelector('.addsub').addEventListener('click', function(){
    screen = '-' +screen ;
    inputElement.value = screen;
})

//sqrt button
document.querySelector('.sqrt').addEventListener('click', function(){
  screen = '√'+screen;
    inputElement.value = screen;
})

//pi button
document.querySelector('.pi').addEventListener('click', function(){
    screen += Math.PI.toString();
    inputElement.value = screen;
})

//e button
document.querySelector(".e").addEventListener('click', function(){
    screen += Math.E.toString();
    inputElement.value = screen;
})

//approx
document.querySelector('.approx').addEventListener('click', function(){
  screen = Math.round(screen);
    inputElement.value = screen;
})

//log btn
document.querySelector('.log').addEventListener('click', function (){
    if(screen.length > 0){
        screen = 'log('+screen+')';
    }
    else {
        screen = 'log(';
    }
    inputElement.value = screen;
})

//Random button
document.querySelector('.rand').addEventListener('click', function(){
    screen = parseInt((Math.random()*100)+1).toString();
    inputElement.value = screen;
})

//Sin button
document.querySelector('.sin').addEventListener('click', function(){
    if(screen.length > 0){
        screen = 'sin('+screen+')';
    }
    else {
        screen = 'sin(';
    }
    inputElement.value = screen;
})

//cos button
document.querySelector('.cos').addEventListener('click', function(){
    if(screen.length > 0){
        screen = 'cos('+screen+')';
    }
    else {
        screen = 'cos(';
    }
    inputElement.value = screen;
})

//tan button
document.querySelector('.tan').addEventListener('click', function(){
    if(screen.length > 0){
        screen = 'tan('+screen+')';
    }
    else {
        screen = 'tan(';
    }
    inputElement.value = screen;
})



//Number buttons
document.querySelectorAll('.num').forEach(function (button){
    button.addEventListener('click', function(){
        screen += this.textContent;
        inputElement.value = screen;
    })
})

function factorial(n) {
    return (n != 1) ? n * factorial(n - 1) : 1;
}



//to show number in console page
document.querySelector('.eq').addEventListener('click', function() {

    try {
        if (screen.includes('!')) {
            let parts = screen.split('!');
            let num = eval(parts[0]);
            screen = factorial(num).toString();
        }
        else if(screen.includes('²')){
            let parts = screen.split('²');
            let num = eval(parts[0]);
            screen = Math.pow(num, 2).toString();
        }

        else if(screen.includes('^')){
            let parts = screen.split('^');
            let base = eval(parts[0]);
            let exponent = eval(parts[1]);
            screen = Math.pow(base, exponent).toString();
        }

        else if(screen.includes('√')) {
            let parts = screen.split('√');
            let num = eval(parts[1]);
            screen = Math.sqrt(num, 2).toString();
        }


        else if(screen.includes('log')){
            let parts = screen.slice(4, screen.length - 1);
            screen = Math.log10(parseFloat(parts)).toString();
        }

        else if(screen.includes('sin')) {
            let parts = screen.slice(4, screen.length - 1);
            let exact_value = Math.sin((parseFloat(parts) * Math.PI / 180)).toString();
            screen = Math.round(exact_value * 1e10) / 1e10;
        }

        else if(screen.includes('cos')) {
            let parts = screen.slice(4, screen.length - 1);
            console.log(parts);
            let exact_value = Math.cos((parseFloat(parts) * Math.PI / 180)).toString();
            screen = Math.round(exact_value * 1e10) / 1e10;
        }

        else if(screen.includes('tan')) {
            let parts = screen.slice(4, screen.length - 1);
            console.log(parts);
            let exact_value = Math.tan((parseFloat(parts) * Math.PI / 180)).toString();
            screen = Math.round(exact_value * 1e10) / 1e10;
        }

        else{
            screen = eval(screen.replace(/×/g, '*'));
        }
        inputElement.value = screen;
    }

    catch (e){
        screen = '';
        inputElement.value = 'Error';
        console.log(e.toString());
    }
    console.log(screen);
})

