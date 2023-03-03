let input = document.querySelector('.search input');
let startedUrl = '/search?chars=';

input.addEventListener('input', () => {
    let url = startedUrl + input.value;
    fetch(url).then(
        response => {
            if(response.ok) return Promise.resolve(response.text());
            else return Promise.resolve(response.text());
        }).then(
            resolved => {
                let content = document.querySelector('.content');
                let userWrapper = document.querySelector('.user-wrapper');
                content.removeChild(userWrapper);
                content.insertAdjacentHTML('beforeend', resolved);
            },
            rejected => {
                console.log('неудача');
            });
});