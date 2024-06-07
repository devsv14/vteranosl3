
const bntnSelectLente = document.querySelector('#btn-sel-trats');

if (bntnSelectLente) {
    bntnSelectLente.addEventListener('click', () => {
       $("#selectLentes").modal()
    });
}