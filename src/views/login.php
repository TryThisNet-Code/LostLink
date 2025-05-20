<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to LostLink</title>
</head>
<body>
    <input type="email" name="email" placeholder="Enter your email address">
    <input type="password" name="password" placeholder="Enter your password">
    <button onclick="login()">Login</button>
    <div id="feedback">

    </div>
    <p>Don't have an account?Register <a href="register.php">here</a></p>

    <script>
        function login(){
            const email = document.querySelector('input[name="email"]').value;
            const password = document.querySelector('input[name="password"]').value;

            if(!email || !password){
                alert("Please fill all of the input field");
                return;
            }

            fetch("", {
                method: "POST",
                headers: {"Content-Type":"application/json"},
                body: JSON.stringify({email, password})
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    location.reload();
                }else{
                    document.getElementById("feedback").innerHTML = Array.isArray(data.message) ? data.message.join('<br>') : data.message;
                    document.getElementById("feedback").style.color = "red";
                }
            })
            .catch(error => {
                    const feedback = document.getElementById("feedback");
                    feedback.innerText = "An error occurred. Please try again.";
                    feedback.style.color = "red";
                    console.error("Login error:", error);
            });
        }
    </script>
</body>
</html>