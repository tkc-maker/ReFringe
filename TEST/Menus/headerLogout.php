    <style>
        #divWelcome{
            color: darkgreen;
            font-size: 18pt;
            margin: 4px;
        }
        #aMainMenu,
        #aLogout{
            color: red;
            font-size: 12pt;
        }
        #aMainMenu,
        #aLogout:visited{
            color: red;
        }
        .button{
            margin: 4px;
            font-size: 12pt; 
            background-color: orange;
        }
    </style>

    <script>
        $(function(){
            $('#aMainMenu').on('click', function(event){
                event.preventDefault();
                window.location.href = "/TEST/Menus/Main_Menus.php";
            });    

            $('#aLogout').on('click', function(event){
                event.preventDefault();
                window.location.href = "/TEST/Login/Login.php";
            });                
        });
    </script>
    
    <div id="divWelcome">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="" id="aLogout">Logout?</a></div><br><br>
