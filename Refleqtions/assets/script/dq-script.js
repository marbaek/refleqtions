document.addEventListener('DOMContentLoaded', () => {
    function displayQuote(quoteData) {
        document.getElementById('quoteText').innerText = `"${quoteData.quote}"`;
        document.getElementById('quoteAuthor').innerText = `- ${quoteData.author}`;
    }

    const storedQuote = localStorage.getItem('dailyQuote');
    const storedDate = localStorage.getItem('quoteDate');
    
    // Function to get today's date in YYYY-MM-DD format
    function getTodayDate() {
        const now = new Date();
        return now.toISOString().split('T')[0]; // Formats date as YYYY-MM-DD
    }

    const todayDate = getTodayDate();

    if (storedQuote && storedDate === todayDate) {
        // Use the stored quote if the stored date matches today
        console.log('Using stored quote:', storedQuote);
        displayQuote(JSON.parse(storedQuote));
    } else {
        // Fetch a new quote and store it with today's date
        console.log('Fetching new quote...');
        fetch('json/daily-quotes.json')
            .then(response => response.json())
            .then(data => {
                const randomQuote = data[Math.floor(Math.random() * data.length)];
                localStorage.setItem('dailyQuote', JSON.stringify(randomQuote));
                localStorage.setItem('quoteDate', todayDate); // Store today's date
                
                console.log('New quote fetched:', randomQuote);
                displayQuote(randomQuote);
            })
            .catch(error => {
                console.error('Error fetching the quote:', error);
            });
    }
});
