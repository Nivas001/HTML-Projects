// console.log(window.innerWidth);



//For visibility of blocks
function visibility(){
    const login = document.querySelector('.login');
    const reg = document.querySelector('.regi');
    const rename = document.querySelector('#title');
    reg.style.display = "none";
    rename.innerHTML = "Login";
//for login to be visible.
    document.querySelector('.log-link').addEventListener('click', function(){
        reg.style.display = "none";
        login.style.display = "block";
        rename.innerHTML = "Login";
    })
//for register to be visible if log-link clicked
    document.querySelector('.reg-link').addEventListener('click', function(){
        login.style.display = "none";
        reg.style.display = "block";
        rename.innerHTML = "Register";
    })
}

//change pass to text
const val = document.querySelector('.pass');
document.querySelector('.show').addEventListener('click', function (){
    console.log("pressed");
    const cursor_position = val.selectionStart;
    val.type = val.type === "password"?"text" :"password";
    setTimeout(()=>{
        val.setSelectionRange(cursor_position, cursor_position);
    },0);
})


//disabling button
const  btn = document.querySelector('button');
btn.disabled = true;
btn.classList.remove('bg');
btn.style.cursor = "not-allowed";





//password color change the border bottom
let password
document.querySelector('#pass').addEventListener('keyup', function(){
    password = document.querySelector('#pass').value;
    const has_upper = /[A-Z]/.test(password);
    const has_lower = /[a-z]/.test(password);
    const has_num = /[0-9]/.test(password);
    const has_special = /[~!@#$%^&*(){};<>?]/.test(password);
    console.log(has_num, has_upper, has_lower, has_special);


    if(has_upper===true || has_lower===true || has_special ===true || has_num ===true){
        document.querySelector('input:focus').style.borderBottom = "3px solid red";
        if(has_num===true && has_upper===true && has_lower=== true){
            document.querySelector('input:focus').style.borderBottom = "3px solid yellow";
            if(password.length>7 && has_special===true){
                document.querySelector('input:focus').style.borderBottom = "3px solid green";
                btn.disabled = false;
                btn.style.cursor = "pointer";
            }
        }
    }
    else if(password===''){
        document.querySelector('input:focus').style.borderBottom = "3px solid #836FFF";
    }

    console.log(password);
})

// document.querySelector('form').addEventListener('submit', function(event) {
//     if (!this.checkValidity()) {
//         // event.preventDefault(); // Prevent form submission if validation fails
//         alert('Form is not valid!');
//     } else {
//         console.log("Form is valid and ready to submit!");
//         alert('Form is valid and ready to submit!');
//     }
// });


visibility();


