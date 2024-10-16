function togglePassword() {
    const passwordInput = document.querySelector(".password");
    const toggleButton = document.querySelector(".toggleButton");
    const rePassword = document.querySelector(".re_password");
    if (passwordInput.type == "password") {
        passwordInput.type = "text";
        if (rePassword) { rePassword.type = "text"; }
        toggleButton.textContent = "Ocultar";
    } else {
        passwordInput.type = "password";
        if (rePassword) { rePassword.type = "password"; }
        toggleButton.textContent = "Mostrar";
    }
};

function popup(url, username) {
    if (confirm("¿Estás seguro de borrar al usuario '" + username + "'?")) {
        window.location.href = url;
    }
}

let editMode = 0; 

function toggleEdit(id, username, email, password){
    if (editMode == 1){
        return false;
    }
    editMode = 1;
    
    const usernameTD = document.querySelector("#username_"+id);
    const emailTD = document.querySelector("#email_"+id);
    const passwordTD = document.querySelector("#password_"+id);
    const actionsTD = document.querySelector("#actions_"+id);


    usernameTD.innerHTML= `<input id="edit_username_${id}" type="text" name="username" value="${username}">`;
    emailTD.innerHTML= `<input id="edit_email_${id}" type="text" name="email" value="${email}">`;
    passwordTD.innerHTML= `<input id="edit_password_${id}" type="text" name="password" value="${password}">`;

    actionsTD.innerHTML = `<a href="#" onclick="submitForm(${id})">Editar</a><a href="usuarios.php">Cancelar</a>`
}

function submitForm(id){
    const username = document.querySelector("#edit_username_"+id).value;
    const email = document.querySelector("#edit_email_"+id).value;
    const password = document.querySelector("#edit_password_"+id).value;

    document.body.innerHTML += `
        <form id="editForm_${id}" method="POST" action="../../controllers/UserController.php?op=EDIT">
            <input type="hidden" name="formId" value="${id}">
            <input type="hidden" name="newUsername" value="${username}">
            <input type="hidden" name="newEmail" value="${email}">
            <input type="hidden" name="newPassword" value="${password}">
        </form>
    `;
    document.querySelector(`#editForm_${id}`).submit();
}
