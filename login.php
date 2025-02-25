<html>
<head>
    <script>
        function HandleLoginResponse(response) {
            try {
                var text = JSON.parse(response);
                document.getElementById("textResponse").innerHTML = "Response: " + text.message + "<p>";
            } catch (error) {
                document.getElementById("textResponse").innerHTML = "Error processing response.";
            }
        }

        function SendLoginRequest(username, password) {
            var request = new XMLHttpRequest();
            request.open("POST", "login.php", true);
            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            request.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    HandleLoginResponse(this.responseText);
                }
            };
            
            request.send("type=login&uname=" + encodeURIComponent(username) + "&pword=" + encodeURIComponent(password));
        }
    </script>
</head>
<body>
    <h1>Login Page</h1>
    <div id="textResponse">Awaiting response...</div>

    <script>
        SendLoginRequest("kehoed", "12345");
    </script>
</body>
</html>

