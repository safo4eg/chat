let form = document.querySelector('form.auth');
let inputFile = document.querySelector('input[type="file"]');
let xhr = new XMLHttpRequest();
let url = '/register';

form.addEventListener('submit', (event) => {
    let formData = new FormData(form);
    formData.append('submit', '1');
    event.preventDefault();
    sendRequest('POST', url, formData).then(
        response => {
            if(response != null && typeof response['errors'] !== "undefined") {
                let errors = Object.entries(response['errors']);
                deleteErrors();
                addErrors(errors);
            } else {
                window.location = response.url;
            }
        },
        error => {
           let warnings = Object.values(error);
           warnings.forEach(elem => {
               console.log(elem);
           });
        });
});

inputFile.onchange = (event) => {
    let formData = new FormData(form);
    formData.append('change', '1');
    sendRequest('POST', url, formData).then(
        response => {
            let img = document.querySelector('form.auth .item.image-wrapper img');
            if(img === null) showImage(response);
            else img.src = response.image.path;
        },
        error => {
            let warnings = Object.values(error);
            warnings.forEach(elem => {
               console.log(elem);
            });
        });
}

let sendRequest = (method, url, body=null) => {
    return new Promise((resolve, reject) => {
        xhr.open(method, url);
        xhr.responseType = 'json';
        xhr.onload = () => {
            if(xhr.status >= 400) reject(xhr.response);
            else resolve(xhr.response);
        }
        xhr.send(body);
    });
};

function addErrors(errors) {
    errors.forEach(elem => {
        let item = document.querySelector(`input[name=${elem[0]}]`).parentElement;
        item.classList.add('error');

        let errorsWrapper = document.createElement('DIV');
        errorsWrapper.classList.add('errors-wrapper');

        elem[1].forEach(message => {
            let p = document.createElement('P');
            p.classList.add('error');
            p.textContent = message;
            errorsWrapper.append(p);
        });

        item.after(errorsWrapper);
    });
}

function deleteErrors() {
    let items = document.querySelectorAll('.item.error');
    items.forEach(elem => {
       elem.classList.remove('error');
    });

    let errorsWrappers = document.querySelectorAll('.errors-wrapper');
    errorsWrappers.forEach(elem => {
       form.removeChild(elem);
    });
}

function showImage(response) {
    let imageWrapper = document.createElement('DIV');
    imageWrapper.classList.add('item');
    imageWrapper.classList.add('image-wrapper');

    let img = document.createElement('IMG');
    img.src = response.image.path;

    imageWrapper.append(img);
    inputFile.after(imageWrapper);
}