//// calculate percentage after button tracked was clicked/ fetch data from the php and receive json 
// encoded then displays the div elements with the percentage with click add event 
//listener to change data from the cloud comments;

const trackbutton = document.getElementById("track_button");
trackbutton.addEventListener('click', (e) => {
    console.log("clicked");
    fetch(`moodtrack/fetchmoodpercentage.php?user_id=${main_user_id}`)
    .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
    })
    .then(data => {
    console.log('Fetched data:', data);
    const container_moodlist_items = document.getElementById("container_moodlist_items");
    container_moodlist_items.innerHTML = "";

    let loading = document.getElementById('loading');
    console.log(loading);
    loading.style.display = 'flex';

    setTimeout(() => {
            loading.style.display = 'none';
            changecloudcomments("Please Click one of your moods below!");
            data.forEach(items => {
                createMoodItem(items.mood, items.percentage);
            })
            const moodlistItems = document.querySelectorAll(".moodlist_item");
           
            moodlistItems.forEach(item => {
                item.addEventListener("click", function (e) {

                    const mood = this.querySelector('h1');
                    const percentage = this.querySelector('#mood_percentage');
                    const image = this.querySelector('img');
                    image.classList.add('image_animation');
                    document.getElementById("mood_icon").src= `moodtrack/${mood.textContent}2.png`;
                    console.log(mood.textContent);
                    console.log(percentage.style.width);

                                 changecloudcomments(`I am ${percentage.style.width} ${mood.textContent}`);

                                getRandomExpression(mood.textContent).then(data => {

                                        let RandomExpression = setInterval(() => {
                                            const expressions = data[mood.textContent];
                                            const randomExpression = expressions[Math.floor(Math.random() * expressions.length)];
                                            console.log(randomExpression);
                                            changecloudcomments(randomExpression);
                                        }, 5000);

                                        setTimeout(() => {
                                                document.addEventListener('click', () =>{
                                                    console.log("looping stopped");
                                                    clearInterval(RandomExpression);
                                                })
                                            console.log("click listener activated");
                                        }, 100)

                                }).catch(error => {
                                  console.error('Error retrieving data:', error);
                                });
                });
            });

        }, 1500)
    })
    .catch(error => {
    console.error('Fetch error:', error);
    });
})


///change cloudtext near the big mood icon
function changecloudcomments(cloudcomment){
    document.getElementById("mood_comment").remove();
    document.getElementById("mood_clouds").remove();
    const container_mood_icon = document.getElementById('container_mood_icon');


    setTimeout(()=>{
        const moodCloud = document.createElement('div');
        moodCloud.classList.add('mood_clouds');
        moodCloud.id = "mood_clouds";

        const moodComment = document.createElement('div');
        moodComment.classList.add('mood_comment');
        moodComment.id = "mood_comment";
        moodComment.textContent = `${cloudcomment}`;

        container_mood_icon.appendChild(moodCloud);
        container_mood_icon.appendChild(moodComment);
    }, 1500)
}




//returns color value used in progress bar color based on emotions/ array
function getEmotionColor(Emotion){
    const EmotionColors = {
        Happy: "#FFD700", 
        Sad: "#1E90FF",
        Angry: "#DC143C",
        Relaxed: "#B0E0E6",
        Excited: "#FF69B4" 
    }
    return EmotionColors[Emotion] || "#FFFFFF"; 
}



//instantiate a mooditem element
function createMoodItem(mood, percentage) {

    const container_moodlist = document.getElementById("container_moodlist_items");

    const moodItem = document.createElement('div');
    moodItem.classList.add('moodlist_item');
    moodItem.id = mood;
    container_moodlist.appendChild(moodItem);

    const img = document.createElement('img');
    img.src = `moodtrack/${mood}.png`;
    moodItem.appendChild(img);
    
    const container_progress_bar = document.createElement('div');
    container_progress_bar.classList.add('progress_bar');
    moodItem.appendChild(container_progress_bar);


    const container_percentage = document.createElement('div');
    container_percentage.classList.add('container_percentage');
    container_progress_bar.appendChild(container_percentage);

    const moodHeading = document.createElement('h1');
    moodHeading.textContent = mood;
    container_progress_bar.appendChild(moodHeading);

    const moodPercentage = document.createElement('div');
    moodPercentage.classList.add('mood_percentage');
    moodPercentage.id = 'mood_percentage';
    moodPercentage.style.width = `${percentage}%`;
    moodPercentage.style.backgroundColor = `${getEmotionColor(mood)}`;
    container_percentage.appendChild(moodPercentage);
}



async function getRandomExpression(mood) {

  try {
    const response = await fetch(`moodtrack/fetchjournals.php?user_id=${main_user_id}`);

    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
          const data = await response.json();
          console.log('Data fetched successfully:', data);
          return data;

    } catch (error) {
    console.error('There was a problem with the fetch operation:', error);
    return null;
  }
}
