const loginForm = document.getElementById("loginForm");
const login = document.getElementById("login");
const password = document.getElementById("password");
const submitBtn = document.getElementById("submitBtn");

if(loginForm !== null ){
loginForm.addEventListener("submit", (e)=>{
	e.preventDefault();
	
	checkLoginInputs()
})

}


function checkLoginInputs(){
	var x = 0;
	const loginValue = login.value.trim();
	const passwordValue = password.value.trim();

	
	if (loginValue === "" ) {
		setErrorFor(login, "id cannot be blank")
		x += 1;
    }else{
    if(/^[0-9]+$/.test(loginValue)){

		setSuccessFor(login);
}else {
        setErrorFor(login, "id must be numbers")
        x += 1;
}
	}
	
	if (passwordValue === "" ) {
		setErrorFor(password, "password cannot be blank")
		x += 1;
    }else {
        setSuccessFor(password)
	}
		if (x != 0)
	{return false;}
	else
	{loginForm.submit();}
	//{location.href='Employeehomepage.php';}
}


const mloginForm = document.getElementById("mloginForm");
const mlogin = document.getElementById("mlogin");
const mpassword = document.getElementById("mpassword");



if(mloginForm !== null ){
mloginForm.addEventListener("submit", (e)=>{
	e.preventDefault();
	
	checkMLoginInputs()
})

}

function checkMLoginInputs(){
	var x = 0;
	const mloginValue = mlogin.value.trim();
	const mpasswordValue = mpassword.value.trim();
	
	if (mloginValue === "" ) {
		setErrorFor(mlogin, "id cannot be blank")
		x += 1;
    }else {
        setSuccessFor(mlogin)
	}
	if(/^[0-9]+$/.test(mloginValue)){

		 setErrorFor(mlogin, "id must be letter");
		 x += 1;
		 
    }else {
        setSuccessFor(mlogin)
    }
	if (mpasswordValue === "" ) {
		setErrorFor(mpassword, "password cannot be blank")
		x += 1;
    }else {
        setSuccessFor(mpassword)
	}
    if (x != 0)
	{return false;}
	else
	{mloginForm.submit();}
	//{location.href='managerHP.php';}
	
}


function setErrorFor(input, message) {
	const parent = input.parentElement;
	const small = parent.querySelector("p");
	parent.className = "form-control error";
	small.innerText = message;
	small.style.color = "red"
}

function setSuccessFor(input) {
	const parent = input.parentElement;
	parent.className = "form-control success";
}