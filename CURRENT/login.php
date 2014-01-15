<?php 
//Barebones of Single Object Page
$objectPID = $_REQUEST['PID'];
?>

<!DOCTYPE php>
<html>
    <head>
        <meta charset="utf-8">
        <title>Digital Collections - Wayne State University Libraries</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/main.css" type="text/css">
        
        <!-- Typography -->
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,900,100,100italic,300,300italic,400italic,500,500italic,700,700italic' rel='stylesheet' type='text/css'>

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js" type="text/javascript"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js" type="text/javascript"></script>

        <!--Mustache-->
        <script src="inc/jquery-Mustache/jquery.mustache.js"></script>
        <script type="text/javascript" src="inc/mustache.js"></script>

        <script src="inc/jquery.cookie.js" type="text/javascript"></script>           
        
        <!-- Local JS -->
        <script src="js/main.js" type="text/javascript"></script>
        <script src="js/utilities.js"></script>        
        <script src="js/login.js"></script>
        <script src="js/userData.js"></script>
        
        <!--WSUDOR Translation Dictionary-->
        <script type="text/javascript" src="js/rosettaHash.js"></script>
        
        <!--Pagination-->
        <script type="text/javascript" src="inc/jquery.bootpag.min.js"></script> 
        
        <script src="js/vendor/bootstrap.min.js" type="text/javascript"></script>

<script>
(function( $ ) {
  // constants
  var SHOW_CLASS = 'show',
      HIDE_CLASS = 'hide',
      ACTIVE_CLASS = 'active';
  
  $( '.tabs' ).on( 'click', 'li a', function(e){
    e.preventDefault();
    var $tab = $( this ),
         href = $tab.attr( 'href' );
  
     $( '.active' ).removeClass( ACTIVE_CLASS );
     $tab.addClass( ACTIVE_CLASS );
  
     $( '.show' )
        .removeClass( SHOW_CLASS )
        .addClass( HIDE_CLASS )
        .hide();
    
      $(href)
        .removeClass( HIDE_CLASS )
        .addClass( SHOW_CLASS )
        .hide()
        .fadeIn( 550 );
  });
})( jQuery );
</script>

    </head>
    <body>

    <div class="container" id="templateCM">
        <h1 class="brand-login">
            <a href="collection.php">Digital Collections</a>
        </h1>
        <div class="flat-form">
            <ul class="tabs">
                <li>
                    <a href="#login_form_container" class="active">Login</a>
                </li>
                <li>
                    <a href="#createAccount_container">Sign Up</a>
                </li>
            </ul>
            <div id="login_form_container" class="form-action show">
                <h1>Hello World.</h1>
                <div id="login_form">
                    <p>Login with your AccessID/Username and Password. If you don't have an AccessID or Username, please sign up!</p>
                    <form onsubmit="loginForm(); return false">
                        <ul>
                            <li><input id="username" type="text" name="username" placeholder="Username"></li>
                            <li><input id="password" type="password" name="password" placeholder="Password"></li>
                            <li><input type="submit" value="Login" class="button"></li>
                        </ul>
                    </form>
                </div>
            </div>
            <!--/#login.form-action-->
            <div id="createAccount_container" class="form-action hide">
                <h1>Register.</h1>
                <p>
                    If you don't have a WSU accessID, you should totes sign up for an account. It's what all the cool kids are doing.
                </p>
                <form onsubmit="createAccountPrep('userDefined'); return false;">
                    <ul>
                        <li>
                            <input id="create_displayName" type="text" name="create_displayName" placeholder="Username" />
                        </li>
                        <li>
                            <input id="create_username" type="password" name="create_username" placeholder="Password" />
                        </li>
                        <li>
                            <input type="submit" value="Sign Up" class="button" />
                        </li>
                    </ul>
                </form>
            </div>
            <!--/#register.form-action-->
        </div>
    </div>  
        
    </body>

    <script class="cssdeck">(function( $ ) {
  // constants
  var SHOW_CLASS = 'show',
      HIDE_CLASS = 'hide',
      ACTIVE_CLASS = 'active';
  
  $( '.tabs' ).on( 'click', 'li a', function(e){
    e.preventDefault();
    var $tab = $( this ),
         href = $tab.attr( 'href' );
  
     $( '.active' ).removeClass( ACTIVE_CLASS );
     $tab.addClass( ACTIVE_CLASS );
  
     $( '.show' )
        .removeClass( SHOW_CLASS )
        .addClass( HIDE_CLASS )
        .hide();
    
      $(href)
        .removeClass( HIDE_CLASS )
        .addClass( SHOW_CLASS )
        .hide()
        .fadeIn( 550 );
  });
})( jQuery );</script>


    <!--API call-->
    <script type="text/javascript">
        $(document).ready(function(){            
            loginGo();
        })      
    </script>
    
</html>