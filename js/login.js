let form = document.querySelector('form.auth');
let url = '/login';

form.addEventListener('submit', (event) => {
   event.preventDefault();

   let body = {
       method: 'POST',
       body: new FormData(form),
   }

   fetch(url, body).then(response => {
      if(response.ok) return Promise.resolve(response.text());
      else return Promise.reject(response.text());
   }).then(
       resolved => {
            let data = JSON.parse(resolved);
            if(typeof data.errors !== 'undefined') {
                deleteErrors();
                let errors = Object.entries(data.errors);
                addErrors(errors);
            } else {
                window.location = data.url;
            }
       }, rejected => {
          console.log('неудача');
       });
});

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
