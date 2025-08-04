<?php
include('../include/header.php');
include("../assets/php/connections.php");
$username = $_SESSION['username'];
$sql = "SELECT user_id FROM `login_tbl1` WHERE username = '$username'";
$result = $crud_connections->query($sql);
$data = $result->fetch_assoc();
$user_id = $data['user_id'];
?>

<link rel="stylesheet" href="savequote/savequotestyle.css">

<body>
<div class="body_container_quotes">


            <div id="view_quote" class="view_quote">
                <h2>Author</h2>
                <p>Qoutes</p>
                        <div class="quote_buttons">
                            <button class="delete_btn" id="delete_btn">Delete</button>
                            <button class="exit_btn" id="exit_btn">Exit</button>
                        </div>
                        <div class="quote_buttons" id="confirm_buttons">
                            <button class="delete_btn" id="delete_btn">Yes</button>
                            <button class="exit_btn" id="exit_btn">No</button>
                        </div>
            </div>


            <div id="container_quotes" class="container_quotes">
                
                                <div class="quote_container item">
                                </div>          
            </div>
</div>

</body>
<script>
    const main_user_id = <?php echo $user_id;?>;
</script>
<script src="savequote/retrievequote.js"></script>
<script>retrievequotes(main_user_id);</script>
