const quoteContainer = document.getElementById("quote-container");
const quoteText = document.getElementById("quote");
const authorText = document.getElementById("author");
const copyQuoteBtn = document.getElementById("copy-quote");
const newQuoteBtn = document.getElementById("new-quote");
const loader = document.getElementById("loader");
const generatingMessage = document.getElementById("generating-message"); // New element for generating message

let apiQuotes = [];
let selectedCategory = ''; // Store selected category

// Show loading spinner
function showLoadingSpinner() {
    loader.hidden = false;
    quoteContainer.hidden = true;
    generatingMessage.style.display = "block"; // Show generating message
    generatingMessage.textContent = "Generating..."; // Set the text to "Generating..."
}

// Remove loading spinner
function removeLoadingSpinner() {
    quoteContainer.hidden = false;
    loader.hidden = true;
    generatingMessage.style.display = "none"; // Hide generating message
}

// Show a new quote
function newQuote() {
    showLoadingSpinner();

    let filteredQuotes = apiQuotes;

    // If a category is selected, filter quotes by the selected category
    if (selectedCategory) {
        filteredQuotes = apiQuotes.filter(quote => {
            return quote.tag && quote.tag.toLowerCase() === selectedCategory.toLowerCase();
        });
    }

    // If no quotes match the selected category, fall back to a random quote
    if (filteredQuotes.length === 0) {
        filteredQuotes = apiQuotes;
    }

    // Simulate a delay for 1 second (1000 milliseconds)
    setTimeout(() => {
        // Pick a random quote from filteredQuotes
        const quote = filteredQuotes[Math.floor(Math.random() * filteredQuotes.length)];
        authorText.textContent = quote.author ? quote.author : "Unknown";

        // Check quote length to apply styling
        if (quote.text.length > 120) {
            quoteText.classList.add("long-quote");
        } else {
            quoteText.classList.remove("long-quote");
        }

        // Set the quote text and hide the loader
        quoteText.textContent = quote.text;
        removeLoadingSpinner();
    }, 1000); // 1 second delay
}

// Get quotes from API
async function getQuotes() {
    showLoadingSpinner();
    const apiUrl = "json/quotes.json"; // Replace with your own API URL if needed

    try {
        const response = await fetch(apiUrl);
        apiQuotes = await response.json();

        if (apiQuotes.length > 0) {
            newQuote(); // Display a random quote after loading
        } else {
            quoteText.textContent = "No quotes available.";
            removeLoadingSpinner();
        }
    } catch (err) {
        console.error("Failed to fetch quotes:", err);
        quoteText.textContent = "Sorry, we couldn't load quotes at this time.";
        removeLoadingSpinner();
    }
}

// Handle category selection
function setCategory(category) {
    console.log(category);
    selectedCategory = category; // Set the selected category
    showadvertisement(ads_image);// Get a new quote based on the selected category
}
// Function to copy the current quote to the clipboard
function copyQuote() {
    const quoteTextContent = quoteText.textContent; // Get the quote text
    const authorTextContent = authorText.textContent; // Get the author text

    if (!quoteTextContent) return; // If there's no quote, do nothing

    // Create a temporary textarea element to help with copying
    const textarea = document.createElement("textarea");
    textarea.value = `${quoteTextContent} - ${authorTextContent}`; // Set the quote and author as the textarea's value
    document.body.appendChild(textarea); // Append the textarea to the body
    textarea.select(); // Select the content in the textarea
    document.execCommand("copy"); // Execute the copy command
    document.body.removeChild(textarea); // Remove the textarea from the DOM

    // Change button text to "Copied!" and revert after 2 seconds
    copyQuoteBtn.textContent = "Copied!";
    setTimeout(() => {
        copyQuoteBtn.textContent = "Copy";
    }, 2000);
}

// Event listener for the Copy button
if (copyQuoteBtn) {
    copyQuoteBtn.addEventListener("click", copyQuote);
}

// Reset to show random quotes
newQuoteBtn.addEventListener("click", () => {
    showadvertisement(ads_image);
    selectedCategory = ''; // Reset category and show random quote
});

// On page load, fetch quotes
getQuotes();


//show ads after generating new quotes/ and every 40seconfds of into to website
const ads_image = [`assets/img/ads_sample.gif`, `assets/img/ads_sample2.gif`, `assets/img/ads_sample3.gif`, `assets/img/ads_sample4.gif`, `assets/img/ads_sample5.gif`, `assets/img/ads_sample6.gif`];
function random_ads(){
    return Math.floor(Math.random() * ads_image.length);
}
async function showadvertisement(ads_image){
    let adscounter = document.getElementById('ads_counter');
    let ads_sample = document.getElementById('ads_sample');
    const showads = document.getElementById('ads_show');
    const container_body = document.getElementById('container_body');
    ads_sample.style.backgroundImage = `url('${ads_image[random_ads()]}')`;
    showads.style.display = 'flex';
    container_body.style.overflow = 'hidden';
    newQuote();

    setTimeout(() => {
            ads_sample.style.display = 'flex';
            adscounter.style.display = 'flex';
            for(let i = 5; i >= 0; i--){
                    setTimeout(() => {
                        adscounter.innerHTML = `Ads will be skipped in: ${i} second/s`;
                        if(i == 0){
                            adscounter.innerHTML = `<button class="skip_button" id="skip_button">Skip Ads</button>`;
                            const skip_button = document.getElementById('skip_button');
                            skip_button.addEventListener('click', () => {
                                showads.style.display = 'none';
                                adscounter.innerHTML = `Ads will be skipped in:`;
                                ads_sample.style.display = 'none';
                                adscounter.style.display = 'none';
                                container_body.style.overflow = '';
                            });
                        }
                    }, (5 - i) * 1000);
            }
    }, 1000);
}


console.log(document.getElementById('ads_show'));
