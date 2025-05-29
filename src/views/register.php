<?php
    include('nav.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to LostLink</title>
</head>
<body>
    <h2>Register</h2>
    <input type="text" name="fname" placeholder="Enter first name">
    <input type="text" name="lname" placeholder="Enter last name">
    <input type="email" name="email" placeholder="Enter your email">
    <input type="tel" name="phonenum" placeholder="Enter phone number">
    <input type="password" name="password" placeholder="Enter password">
    <input type="password" name="twopass" placeholder="Re-enter password">
    <button onclick="register()">Register</button>
    <p>Already had an account?Log in <a href="login.php">here</a></p>
    <div id="feedback">

    </div>

    <script>
        function register(){
            const fname = document.querySelector('input[name="fname"]').value;
            const lname = document.querySelector('input[name="lname"]').value;
            const email = document.querySelector('input[name="email"]').value;
            const phonenum = document.querySelector('input[name="phonenum"]').value;
            const password = document.querySelector('input[name="password"]').value;
            const twopass = document.querySelector('input[name="twopass"]').value;

            if(!fname || !lname || !email || !phonenum || !password || !twopass){
                alert("Please fill all of the fields");
                return;
            }

            if(password != twopass){
                alert("Password does not match");
                return;
            }

            fetch("", {
                method: "POST",
                headers: {"Content-Type":"application/json"},
                body: JSON.stringify({fname, lname, email, phonenum, password, twopass})
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    document.getElementById("feedback").innerText = data.message;
                    document.getElementById("feedback").style.color = "green"

                    setTimeout(() => {
                        window.location.href = "login.php";
                    }, 1500);
                }else{
                    document.getElementById("feedback").innerHTML = Array.isArray(data.message) ? data.message.join('<br>') : data.message;
                    document.getElementById("feedback").style.color = "red";
                }
            })
            .catch(error => {
                const feedback = document.getElementById("feedback");
                feedback.innerText = "An error occurred. Please try again.";
                feedback.style.color = "red";
                console.error("Registration error:", error);
            });
        }
    </script>
</body>
</html>