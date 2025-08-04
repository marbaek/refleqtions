<?php
session_start();
include("../assets/php/connections.php");
include("../include/header.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome, <?php echo htmlspecialchars($username); ?></title>
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <link rel="stylesheet" type="text/css" href="assets/css/style-help.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style_show_ads.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release-pro/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<style>
  iframe {
    width: 100%;
    height: 100%;
    min-height: 657px;
    border: none; /* Removes the border */
  }

  /* Media query for screens smaller than 768px */
  @media (max-width: 768px) {
    iframe {
      min-height: 657px; /* Adjust height for smaller screens */
    }
  }

  /* Media query for screens smaller than 480px */
  @media (max-width: 480px) {
    iframe {
      min-height: 750px; /* Adjust height further for very small screens */
    }
  }
</style>

<body style="overflow: hidden;">
<!-- for ads showing-->
    <div class="ads_show" id="ads_show">
                    <div class="ads_counter" id="ads_counter">Ads will be skipped in: </div>
                    <div class="ads_sample" id="ads_sample"></div>
    </div>
<iframe
    src="https://www.chatbase.co/chatbot-iframe/QXHTj9xanekdl1q5YQAsV"
    frameborder="0"
></iframe>

</body>


<script>
//show ads after 20 seconds
const ads_image = [`assets/img/ads_sample.gif`, `assets/img/ads_sample2.gif`, `assets/img/ads_sample3.gif`, `assets/img/ads_sample4.gif`, `assets/img/ads_sample5.gif`, `assets/img/ads_sample6.gif`];
function random_ads(){
    return Math.floor(Math.random() * ads_image.length);
}
let startads = null;

async function showadvertisement(ads_image){
    let adscounter = document.getElementById('ads_counter');
    let ads_sample = document.getElementById('ads_sample');
    const showads = document.getElementById('ads_show');
    ads_sample.style.backgroundImage = `url('${ads_image[random_ads()]}')`;
    showads.style.display = 'flex';

    setTimeout(() => {
            ads_sample.style.display = 'flex';
            adscounter.style.display = 'flex';

            for(let i = 7; i >= 0; i--){
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
                                startInterval();
                            });
                        }
                    }, (7 - i) * 1000);
            }
    }, 1000);
}

function startInterval() {
  if (startads === null) {
    console.log('Starting ads interval...');

    startads = setInterval(() => {
      const adContainer = document.getElementById('ads_show');
      if (getComputedStyle(adContainer).display === 'none') {
        showadvertisement(ads_image);
        stopInterval();
      } else {
        console.log('Ad is already showing.');
      }
    }, 15000);
  }
}

function stopInterval() {
  if (startads !== null) {
    clearInterval(startads);
    startads = null;
    console.log('Ads interval stopped.');
  }
}

startInterval();
</script>