const new_qoute = document.getElementById('new-quote');
const add_quote_button = document.getElementById('add_quote');

new_qoute.addEventListener('click', ()=>{
    add_quote_button.style.display = 'block';
    add_quote_button.style.backgroundColor = '#22c55e';
    add_quote_button.textContent = 'Add to my favourites';
})


//add quote function with php/// php api file addquote.php
function add_qoute(user_id){
    const iauthor = document.getElementById('author');
    const iquote = document.getElementById('quote');
    
    add_quote_button.style.backgroundColor = '#c52277';
    add_quote_button.textContent = 'Qoute added to favourites';

    setTimeout(() => {
        add_quote_button.style.display = 'none';
    }, 400)

    fetch('savequote/addquote.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded', // or 'application/json' if sending JSON
        },
        body: new URLSearchParams({
          user_id: user_id,
          author: iauthor.textContent,
          quote: iquote.textContent
        })
      })
      .then(response => response.text()) // or .json() if you expect JSON
      .then(data => {
        console.log('Success:', data);
      })
      .catch(error => {
        console.error('Error:', error);
      });
}