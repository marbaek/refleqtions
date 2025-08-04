///fetch sql saved quote and displays on the container
async function retrievequotes(iuser_id){
    try {
                const response = await fetch('savequote/retrievequote.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        user_id : iuser_id
                    })
                  })


                const data = await response.json();
                console.log(data);

                document.getElementById('container_quotes').innerHTML = "";

                data.forEach(item =>{
                    let author = item.author;
                    let quote = item.quote;
                    let quote_id = item.quote_id;
                    let user_id = item.user_id;
                    
                    createQuoteContainer(author, quote, quote_id, user_id);
                })

    }catch (error) {
        const view_quote = document.getElementById('view_quote');
        const option_buttons = view_quote.children[2];
        const confirm_buttons = view_quote.children[3];
        confirm_buttons.style.display = 'none';
        option_buttons.style.display = 'none';

        showmainquote("Please add via Quote Generator", "Sorry, you haven't add quotes yet");
    }
}



function createQuoteContainer(author, quote, quote_id, user_id) {

    const quoteContainer = document.createElement('div');
    quoteContainer.classList.add('quote_container', 'item');
    quoteContainer.onclick = () => {showmainquote(`-${author}`, `"${quote}"`, quote_id, user_id)};
    const randomGrow = Math.floor(Math.random() * 5);
    quoteContainer.style.flexGrow = randomGrow;
      
    const authorElement = document.createElement('h2');
    authorElement.textContent = author;
      
    const quoteElement = document.createElement('p');
    quoteElement.textContent = `"${quote}"`;
      
    quoteContainer.appendChild(authorElement);
    quoteContainer.appendChild(quoteElement);
      
    document.getElementById('container_quotes').appendChild(quoteContainer);
}



function showmainquote(author, quote, quote_id, user_id){
    const view_quote = document.getElementById('view_quote');
    view_quote.children[1].textContent = `${author}`;
    view_quote.children[0].textContent = `${quote}`;

    const exit_btn = view_quote.children[2].children[1];
    const yes_btn = view_quote.children[3].children[0];
    const no_btn = view_quote.children[3].children[1];
    const delete_btn = view_quote.children[2].children[0];
    const confirm_buttons = view_quote.children[3];

    exit_btn.onclick = () => {view_quote.style.display = "none"};
    delete_btn.onclick = () =>{confirm_buttons.style.display = "block"};
    no_btn.onclick = () => {confirm_buttons.style.display = "none"};
    yes_btn.onclick = async () => {await deletequote(quote_id); 
    confirm_buttons.style.display = "none";
    view_quote.style.display = "none";
    retrievequotes(user_id)};

    view_quote.style.display = 'flex';
}


async function deletequote(quote_id){
    try{
        const response = await fetch(`savequote/delete_quote.php?quote_id=${quote_id}`);
        const data = await response.json();
        console.log(data);
    }catch(error){
        console.error(error);
    }
}