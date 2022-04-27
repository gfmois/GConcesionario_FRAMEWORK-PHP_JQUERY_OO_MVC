const pausedPromise = (ms) => new Promise((resolve, reject) => {
    setTimeout(async() => {
        try {
            resolve()
        } catch (error) {
            reject(error)
        }
    }, ms);
})

const ajaxPromise = (method, url, dataType, vData) => new Promise((resolve, reject) => {
    try {
        $.ajax({
            type: method,
            data: vData,
            url: url,
            dataType: dataType,
            success: (data) => {
                resolve(data)
            },
            error: (error) => {
                reject(error)
            }
        });
    } catch (error) {
        reject(error)
    }
});

const ajaxPromiseW_Token = (method, url, dataType, vData) => new Promise((resolve, reject) => {
    try {
        $.ajax({
            type: method,
            data: vData,
            url: url,
            dataType: dataType,
            headers: { token: localStorage.getItem('token') != null ? localStorage.getItem('token').replace(/['"]+/g, '') : false },
            success: (data) => {
                resolve(data)
            },
            error: (error) => {
                reject(error)
            }
        });
    } catch (error) {
        reject(error)
    }
});

const friendlyURL = (url) => {
    let link = "";
    let cont = 0;

    url = url.replace("?", "");
    url = url.split("&");

    for (let i = 0; i < url.length; i++) {
        cont++;
        let aux = url[i].split("=");

        if (cont == 2) link += "/" + aux[1] + "/";
        else link += "/" + aux[1];
    }

    // FIXME: La ip no se tiene que ver y no poner localhost porque sino solo funciona en el portatil.
    return "http://192.168.1.71/GConcesionario_FRAMEWORK_JQUERY_OO_MVC" + link;
}