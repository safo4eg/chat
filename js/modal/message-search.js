let userWrapper = document.querySelector('.user-wrapper');
let openModalM = document.querySelectorAll('#openModalMessage');
let modalMessage = null;


userWrapper.addEventListener('click', event => {
   openModalM.forEach(elem => {
      if(event.target == elem) {
          event.preventDefault();
          modalMessage = elem.nextElementSibling;
          modalMessage.classList.add('active-modal');
          let closeModalM = modalMessage.querySelector('#close-message');
          closeModalM.addEventListener('click', closeModalMessage);

          let hiddenInput = modalMessage.querySelector('input[type="hidden"]');
          localStorage.setItem('id', hiddenInput.value);
      }
   });
});

function closeModalMessage() {
    modalMessage.classList.remove('active-modal');
    this.removeEventListener('click', closeModalMessage);
}