const form = document.getElementById("form");
const username = document.getElementById("username");
const email = document.getElementById("email");
const password = document.getElementById("password");
const password2 = document.getElementById("password2");
const lastname = document.getElementById("lastname")

if(form !== null ){

form.addEventListener("submit", (e) => {
	e.preventDefault();

	checkInputs();
});

}


function checkInputs() {
	// trim to remove the whitespaces
	var x = 0;
	const usernameValue = username.value.trim();
	const emailValue = email.value.trim();
	const passwordValue = password.value.trim();
	const password2Value = password2.value.trim();
	const lastnameValue = lastname.value.trim();

	if (usernameValue === "" ) {
		setErrorFor(username, "id cannot be blank")
		x += 1;
    }else{
    if(/^[0-9]+$/.test(usernameValue)){

		setSuccessFor(username);
}else {
        setErrorFor(username, "id must be numbers")
        x += 1;
}
	}
	if(emailValue.length > 0 && !isNaN(parseInt(emailValue))){
	     		setErrorFor(email, " cannot be number"); 
	     		x += 1;
	}

	else if (emailValue === "" ) {
	
		setErrorFor(email, " cannot be blank");
		x += 1;
	}
	/*
	else if(emailValue && parseInt(emailValue) === Number){
	      setErrorFor(email, " cannot be a number")
	} */
	 else {
		setSuccessFor(email);
		console.log(typeof(parseInt(emailValue)));
	}
	if(lastnameValue.length > 0 && !isNaN(parseInt(lastnameValue))){
	     		setErrorFor(lastname, " cannot be number"); 
	     		x += 1;
	}
	
	else if(lastnameValue === ""){
		setErrorFor(lastname, "last name cannot be blank")
		x += 1;
	}
	else if(lastnameValue == Number){
	      setErrorFor(lastname, " cannot be a number")
	      x += 1;
	}
	else {
		setSuccessFor(lastname);
	}
	if(passwordValue.length > 0 && !isNaN(parseInt(passwordValue))){
	     		setErrorFor(password, " cannot be number"); 
	     		x += 1;
	}

	else if (passwordValue === "") {
		setErrorFor(password, " cannot be blank");
		x += 1;
	}
	else if(passwordValue == Number){
	      setErrorFor(password, " cannot be a number")
	      x += 1;
	}
	 else {
		setSuccessFor(password);
	}

	if (password2Value === "") {
		setErrorFor(password2, "Password cannot be blank");
		x += 1;
	}  else {
		setSuccessFor(password2);
	}
	
	if (x != 0)
	{return false;}
	else
	{location.href='Employeehomepage.php';}
}

function setErrorFor(input, message) {
	const formControl = input.parentElement;
	const small = formControl.querySelector("small");
	formControl.className = "form-control error";
	small.innerText = message;
}

function setSuccessFor(input) {
	const formControl = input.parentElement;
	formControl.className = "form-control success";
}

function isEmail(email) {
	return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(
		email
	);
}

// SOCIAL PANEL JS
const floating_btn = document.querySelector(".floating-btn");
const close_btn = document.querySelector(".close-btn");
const social_panel_container = document.querySelector(
	".social-panel-container"
);

if(floating_btn !== null && close_btn !== null){
floating_btn.addEventListener("click", () => {
	social_panel_container.classList.toggle("visible");
});

close_btn.addEventListener("click", () => {
	social_panel_container.classList.remove("visible");
});

}











