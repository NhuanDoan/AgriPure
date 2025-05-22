const pswrdField = document.querySelector(".form input[type='password']"),
togglesIcon = document.querySelector(".form .input-group i");

togglesIcon.onclick = ()=>{
    if(pswrdField.type === "password")
    {
        pswrdField.type = "text";
        togglesIcon.classList.add("fa-eye-slash");
        togglesIcon.classList.remove("fa-eye");
    } else {
        pswrdField.type = "password";
        togglesIcon.classList.remove("fa-eye-slash");
        togglesIcon.classList.add("fa-eye");
    }
}