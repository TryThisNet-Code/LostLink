<?php
    include('nav.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <h2>Welcome <?= htmlspecialchars($_SESSION['user']['full_name'])?>!</h2>
    <div class="contact-info">
        <input type="text" name="name" placeholder="Enter your name">
        <input type="email" name="email" placeholder="Enter your email">
        <input type="tel" name="phonenum" placeholder="Enter your phonen number">
    </div>

    <div class="item-info">
        <input type="text" name="item" placeholder="Enter what was lost">
        <input type="text" name="categ" placeholder="Enter what category">
        <input type="text" name="color" placeholder="Enter what was the color">
        <input type="text" name="place" placeholder="Enter where you see it">
        <input type="text" name="add_info" placeholder="Enter additional Info">
        <input type="date" name="date" placeholder="Enter waht date did you find it">
    </div>

    <button onclick="sendPostInfo()"> Submit </button>
    <div id="feedback">

    </div>

    <a href="logout.php">Logout</a>

    <script>
        function sendPostInfo(){
            const item = document.querySelector('input[name="item"]').value;
            const categ = document.querySelector('input[name="categ"]').value;
            const color = document.querySelector('input[name="color"]').value;
            const place = document.querySelector('input[name="place"]').value;
            const add_info = document.querySelector('input[name="add_info"]').value;
            const date = document.querySelector('input[name="date"]').value;
            const name = document.querySelector('input[name="name"]').value;
            const email = document.querySelector('input[name="email"]').value;
            const phonenum = document.querySelector('input[name="phonenum"]').value;
            const feedback = document.getElementById("feedback");

            if(!item||!categ||!color||!place||!date||!name||!email||!phonenum){
                alert("Please fill all of the fields");
                return;
            }

            fetch("", {
                method: "POST",
                headers: {"Content-Type":"application/json"},
                body: JSON.stringify({item, categ, color, place, add_info, date, name, email, phonenum})
            })
            .then(res => res.json())
            .then(data => {
                feedback.innerText = data.message;
                feedback.style.color = data.success ? "green" : "red";

                if(data.success){
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }else{
                    document.getElementById("feedback").innerHTML = Array.isArray(data.message) ? data.message.join('<br>') : data.message;
                    document.getElementById("feedback").style.color = "red";

                    if(data.success == false){
                        setTimeout(() => {
                        location.reload();
                        }, 1500);
                    }
                }
            })
        }
    </script>
</body>
</html>