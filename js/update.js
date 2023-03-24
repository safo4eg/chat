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
    // sendTimestamp(FETCH_URI, fetchParams, 5000);

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
        messagesDown();

        let companionId = document.querySelector('#companionId').value;
        let lastUserId = document.querySelector('#lastUserId').value;
        let inputMessage = document.querySelector('.bot textarea');
        let btnSend = document.querySelector('.bot button');
        btnSend.addEventListener('click', (event) => {
            fetchParams.body.set('action', 'newMessage');
            let message = inputMessage.value;
            if(message !== '') {
                fetchParams.body.set('message', message);
                fetchParams.body.set('companionId', companionId);
                sendMessageFromDialogue(FETCH_URI, fetchParams).then(
                    ok => {
                        let responseObj = JSON.parse(ok);
                        createNewMessage(responseObj, lastUserId, companionId);
                        inputMessage.value = '';
                    });
            }
        });

        inputMessage.addEventListener('input', function(event) {
            fetchParams.body.set('action', 'input');
            fetchParams.body.set('companionId', companionId);
            sendInfoForInput(FETCH_URI, fetchParams);
        });

        fetchParams.body.set('action', 'activity');
        fetchParams.body.set('companionId', companionId);
        getCompanionActivity(FETCH_URI, fetchParams, 500);
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

function sendInfoForInput(uri, body) {
    fetch(uri, body).then(
        response => {
            if(response.ok) return Promise.resolve(response.text());
            else return Promise.reject(response.text());
        }).then(
            resolved => {
                console.log(resolved);
            },
            rejected => {console.log('Неудача')});
}

function getCompanionActivity(uri, body, ms) {
    setTimeout(() => {
        fetch(uri, body).then(
            response => {
                if(response.ok) return Promise.resolve(response.text());
                else return Promise.reject(response.text());
            }).then(
            resolved => {
                let companionTimestamp = +JSON.parse(resolved)['timestamp'];
                let currentTimestamp = Math.ceil(Date.now() / 1000);
                if((currentTimestamp - companionTimestamp) < 3600) { // время для отображения что печатает собеседник
                    console.log('отображает');
                    let loadingBlock = document.querySelector('.loading');
                    loadingBlock.classList.add('active');
                }
                getCompanionActivity(uri, body, ms);
            },
            rejected => {
                console.log('Неудача');
            });
    }, ms);
}

function createNewMessage(responseData, lastUserId, companionId=null) {
    let messagesPlace = document.querySelector('.center');
    console.log(responseData['user_id']);
    console.log(lastUserId);

    let message = document.createElement('DIV');
    message.classList.add('message');
    let right = document.createElement('DIV');
    right.classList.add('right');
    let text = document.createElement('SPAN');
    text.textContent = responseData['message'];
    let avatarWrapper = null;
    if(responseData['user_id'] == lastUserId) {
        avatarWrapper = document.createElement('DIV');
        avatarWrapper.classList.add('empty-avatar');
        message.classList.add('empty');
    } else {
        avatarWrapper = document.createElement('SPAN');
        avatarWrapper.classList.add('image-wrapper');
        let img = document.createElement('IMG');
        let imgUser = null;
        let lastAvatarWrapper = null;
        let lastRight = null;
        let login = null;
        if(responseData['user_id'] == companionId) {
            imgUser = document.querySelector('img[alt="you"]');
            lastAvatarWrapper = imgUser.parentElement;
            lastRight = lastAvatarWrapper.nextElementSibling;
            login = lastRight.querySelector('.login');
        } else {
            imgUser = document.querySelector('img[alt="companion"]');
            lastAvatarWrapper = imgUser.parentElement;
            lastRight = lastAvatarWrapper.nextElementSibling;
            login = lastRight.querySelector('.login');
        }
        right.append(login);
        img.src = imgUser.src;
        avatarWrapper.append(img);
    }

    right.append(text);
    message.append(avatarWrapper);
    message.append(right);
    messagesPlace.append(message);
    messagesDown();
}

    function messagesDown() {
        let content = document.querySelector('.center');
        content.scrollTop = 99999;
}

