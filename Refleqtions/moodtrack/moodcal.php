<?php
include('../include/header.php');
include("../assets/php/connections.php");
$username = $_SESSION['username'];
$sql = "SELECT user_id FROM `login_tbl1` WHERE username = '$username'";
$result = $crud_connections->query($sql);
$data = $result->fetch_assoc();
$user_id = $data['user_id'];
?>

<link rel="stylesheet" href="moodtrack/moodtrackstyle.css">
<body>
    <div id="loading" class="loading">
        Loading
    </div>

    <h1 class="center_h1" style="font-size: 30px;">Mood Track</h1>

    <div class="container_mood">
                <div id="container_mood_icon" class="container_mood_icon">
                    <img id="mood_icon" src="moodtrack/sigma2.png">
                    <div id="mood_clouds" class="mood_clouds"></div>
                    <div id="mood_comment" class="mood_comment">You will see some snippets of your journal here</div>
                </div>
                <img src="moodtrack/shadow.png">
    </div>

    <h1 class="center_h1">Your Moodlist<button id="track_button" class="track_button">Track Mood</button></h1>

    <div id="container_moodlist_items"
     class="w-full max-w-md flex flex-col items-center justify-start p-4 rounded mt-4 bg-transparent overflow-y-auto max-h-[300px] no-scrollbar">
    <div class="w-full mb-4">
    <img src="moodtrack/Happy.png" alt="Happy mood" class="w-16 h-16 mx-auto mb-2" />
    <h1 class="text-center text-lg font-semibold text-white mb-2">Click the Track mood</h1>
    <div class="bg-yellow-500 h-2 rounded w-[70%] mx-auto mt-2"></div>
    </div>
    </div>

</body>

</html>

<!-- Javascript code and path directories.. moodtrackfunction.js contains created function and button listeners!-->

<script>
        //echoes the user_id from php for javascript data retrieval;
        const main_user_id = '<?php echo $user_id?>';
        alert(user_id);
</script>
      
<script src="moodtrack/moodtrackfunction.js" defer></script>



<!-- CSS styling that does not load if put on the separate css fiels-->
<style>
    body{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 1;
    position: relative;
    width: 100vw;
    height: 100vh;
    padding: 0;
    margin: 0;
    background-image: url('BG.png');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    overflow: hidden;
}
/*Big moods icon modify*/
.container_mood{
  margin-top: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100vw;
}
.center_h1{
  z-index: 5;
  border-radius: 10px;
  width: 80vw;
  max-width: 700px;
  text-align: center;
  background-color: #0a214f33;
}
/*popupcloudmodify*/
.mood_comment{
  border-radius: 10px;
  width: 160px;
  height: 100px;
  position: absolute;
  top: 3%;
  right: -6%;
  animation: float 3.5s ease-in-out infinite;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  overflow-y: scroll;
  word-wrap: break-word;
  word-break: break-word;
}
.mood_comment::-webkit-scrollbar {
  display: none;
}
</style>

