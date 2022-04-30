function checkContactInfo() {
    let contactInfo = {
        name: document.getElementById('nameInput').value.length != 0 ? document.getElementById('nameInput').value : 0,
        account: document.getElementById('emailInput').value.length != 0 ? document.getElementById('emailInput').value : 0,
        text: document.getElementById('textInput').value.length != 0 ? document.getElementById('textInput').value : 0,
        theme: document.getElementById('themeInput').value.length != 0 ? document.getElementById('themeInput').value : 0
    }

    ajaxPromise('POST', friendlyURL('?page=contact&op=sendContactMessage'), 'json', contactInfo).then((res) => {
        console.log(res.result.message);
    })
}

function sendContactInfo() {
}

$(document).ready(function () {
    $('.submit-btn').on('click', () => {
        checkContactInfo();
    })
});