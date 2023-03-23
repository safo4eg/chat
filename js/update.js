const CURRENT_URI = window.location.pathname;
const FETCH_URI = '/update';
let form = new FormData;
let fetchParams = {
    method: 'POST',
    body: form,
}

if(
    CURRENT_URI.search(/^\/profile\/\d+$/) !== -1 ||
    CURRENT_URI.search(/^\/search$/) !== -1 ||
    CURRENT_URI.search(/^\/messages$/) !== -1 ||
    CURRENT_URI.search(/^\/dialogue\/\d+$/) !== -1
) {
    // fetchParams.body.set('timestamp', '1');
    // sendTimestamp(FETCH_URI, fetchParams, 1000);

    if(CURRENT_URI.search(/^\/search$/) !== -1) {
        fetchParams.body.set('searchMessage', '1');
        let sendMessageBtn = document.querySelectorAll('.send-message');
        sendMessageBtn.forEach(elem => {
            elem.addEventListener('click', () => {
                let userId = localStorage.getItem('id');
                let hiddenInput = document.querySelector(`input[value="${userId}"]`);
                let textarea = hiddenInput.nextElementSibling;
                fetchParams.body.set('message', textarea.value);
                fetchParams.body.set('id', userId);
                sendMessage(FETCH_URI, fetchParams);
            });
        });
    } else if(CURRENT_URI.search(/^\/messages$/) !== -1) {

    } else if(CURRENT_URI.search(/^\/dialogue\/\d+$/) !== -1) {
        fetchParams.body.set('newMessage', '1');
        let hiddenInput = document.querySelector('input[type="hidden"]');
        let companionId = hiddenInput.value;
        let inputMessage = document.querySelector('.bot textarea');
        let btnSend = document.querySelector('.bot button');
        btnSend.addEventListener('click', (event) => {
            let message = inputMessage.value;
            if(message !== '') {
                fetchParams.body.set('message', message);
                fetchParams.body.set('companion_id', companionId);
                sendMessageFromDialogue(FETCH_URI, fetchParams).then(
                    ok => {
                        console.log(JSON.parse(ok));
                    });
            }
        });
    }
}

function sendMessageFromDialogue(uri, body) {
    return new Promise(
        resolve => {
            fetch(uri, body).then(
                response => {
                    if(response.ok) return Promise.resolve(response.text());
                    else return Promise.reject(response.text());
                }).then(
                    resolved => {
                        resolve(resolved);
                    },
                    rejected => {console.log('Неудача')});
        })
}

function sendMessage(uri, body) {
    fetch(uri, body).then(
        response => {
            if(response.ok) return Promise.resolve(response.text());
            else return Promise.reject(response.text());
        }).then(
        resolved => {
            let activeModal = document.querySelector('.modal.active-modal');
            activeModal.classList.remove('active-modal');
        },
        rejected => {
            console.log('Неудача');
        });
}

function sendTimestamp(uri, body, ms) {
    setTimeout(() => {
        fetch(uri, body).then(
            response => {
                if(response.ok) return Promise.resolve(response.text());
                else return Promise.reject(response.text());
            }).then(
                resolved => {
                    sendTimestamp(uri, body, ms);
                },
                rejected => {
                    console.log('Неудача');
                });
    }, ms)
}

function createNewMessage() {

}

