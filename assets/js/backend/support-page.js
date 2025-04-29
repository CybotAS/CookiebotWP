(() => {
const questionItem = document.querySelectorAll('.cb-faq__container');

for (let i = 0; i < questionItem.length; i++) {
    questionItem[i].addEventListener('click', function() {
        const active = document.querySelector('.cb-faq--opened');
        active.classList.remove('cb-faq--opened');
        questionItem[i].classList.toggle('cb-faq--opened');
    });
}
})();

function copyDebugInfo() {
    const t = document.getElementById( 'cookiebot-debug-info' )
    t.select()
    t.setSelectionRange( 0, 99999 )
    document.execCommand( 'copy' )
}
