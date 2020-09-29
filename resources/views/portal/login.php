<!DOCTYPE>
<html lang="hu">
    <head>
        <title>kozossegek.hu - Belépés</title>
        <style>
            @import url(https://fonts.googleapis.com/css?family=Roboto:300);

            .login-page {
                width: 360px;
                padding: 8% 0 0;
                margin: auto;
            }
            .form {
                position: relative;
                z-index: 1;
                background: #FFFFFF;
                max-width: 360px;
                margin: 0 auto 100px;
                padding: 45px;
                text-align: center;
                box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
            }
            .form input {
                font-family: "Roboto", sans-serif;
                outline: 0;
                background: #f2f2f2;
                width: 100%;
                border: 0;
                margin: 0 0 15px;
                padding: 15px;
                box-sizing: border-box;
                font-size: 14px;
            }
            .form button {
                font-family: "Roboto", sans-serif;
                text-transform: uppercase;
                outline: 0;
                background: #4CAF50;
                width: 100%;
                border: 0;
                padding: 15px;
                color: #FFFFFF;
                font-size: 14px;
                -webkit-transition: all 0.3s ease-in;
                transition: all 0.3s ease-in;
                cursor: pointer;
            }
            .form button:hover,.form button:active,.form button:focus {
                background: #43A047;
            }
            .form .message {
                font-family: "Roboto", sans-serif;
                margin: 15px 0 0;
                color: #b3b3b3;
                font-size: 12px;
            }
            .form .message a {
                color: #4CAF50;
                text-decoration: none;
                font-weight: bold;
            }
            .bg_image {
                position: absolute;
                width: 106%;
                height: 106%;
                top: -3%;
                left: -3%;
                filter: blur(10px);
                background: url("/assets/sidebar-09/images/bg_1.jpg") no-repeat center;
                background-size: cover;
            }
            body {
                overflow: hidden;
            }
        </style>
    </head>
    <body>
    <div class="bg_image"></div>
    <div class="login-page">
        <div class="form">
            <form class="login-form" method="post">
                <img src="/images/logo_only.png" style="width: 100px; height: auto; margin-bottom: 2em"/>
                <input type="text" name="username" placeholder="felhasználónév" autofocus/>
                <input type="password" name="password" placeholder="jelszó"/>
                <button>belépés</button>
                <p class="message">
                    <a href="#" style="float: left">Vissza a főoldalra</a>
                    <a href="#" style="float: right">Elfelejtettem a jelszavam</a>
                </p>
            </form>
        </div>
    </div>
    </body>
</html>
