function checkContactInfo() {
    let allComplete = false;
    let contactInfo = {
        name: document.getElementById('nameInput').value.length != 0 ? document.getElementById('nameInput').value : 0,
        email: document.getElementById('emailInput').value.length != 0 ? document.getElementById('emailInput').value : 0,
        text: document.getElementById('textInput').value.length != 0 ? document.getElementById('textInput').value : 0,
        theme: document.getElementById('themeInput').value.length != 0 ? document.getElementById('themeInput').value : 0
    }

    for (const contactValue in contactInfo) {
        if (contactInfo[contactValue] == 0) {
            toastr["error"](`El campo ${contactValue} está vacio.`, "Uno de los campos está vacio.")
            allComplete = false;
        } else {
            allComplete = true;
        }
    }

    if (allComplete != false) {
        console.log(contactInfo);
        ajaxPromiseWithSpinner('POST', friendlyURL('?page=contact&op=sendContactMessage'), 'json', contactInfo).then((res) => {
            Swal.fire({
                title: 'Mensaje Enviado',
                text: res.result.message,
                icon: 'success',
                confirmButtonText: 'Aceptar',
            }).then((op) => {
                if (op.isConfirmed) {
                    window.location.href = ajaxPromise('?page=home')
                }
            })
        })
    }
}

$(document).ready(function() {
    $('.submit-btn').on('click', () => {
        checkContactInfo();
    })
});