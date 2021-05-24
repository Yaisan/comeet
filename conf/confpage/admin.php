<!DOCTYPE html>
<html>
  <head>
    <title>YAISAN</title>
    <meta name="description" content="A synthesis project of two SMX students" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--CSS-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma-social@1/bin/bulma-social.min.css" />
    <!--JS-->
    <script src="https://kit.fontawesome.com/ac40c2f10c.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <!--Navbar-->
    <nav class="navbar" role="navigation" aria-label="main navigation">
      <div class="navbar-brand">
        <a class="navbar-item" href="./home.html">
          <img src="https://raw.githubusercontent.com/Yaisan/comeet/a4a127f19e8abb6e0612de5e5b184b17fde6b214/docs/img/Logo.svg" width="112" height="28">
        </a>
    </nav>
    <!--Main-->
    <section class="hero is-fullheight">
      <div class="hero-body">
        <div class="container has-text-centered">
          <div class="column is-4 is-offset-4">
            <div class="box">
              <figure class="image">
                  <img src="https://raw.githubusercontent.com/Yaisan/comeet/a4a127f19e8abb6e0612de5e5b184b17fde6b214/docs/img/Welcome.svg">
                </figure>
                <br />
              <p class="subtitle is-4">Please login to proceed.</p>
              <br />
              <form method="post" action="dashboard.php">
                <div class="field">
                  <p class="control has-icons-left has-icons-right">
                    <input class="input is-medium" type="text" placeholder="User" name="user" />
                    <span class="icon is-medium is-left">
                      <i class="fas fa-user"></i>
                    </span>
                  </p>
                </div>
                <div class="field">
                  <p class="control has-icons-left">
                    <input class="input is-medium" type="password" placeholder="Password" name="password" />
                    <span class="icon is-small is-left">
                      <i class="fas fa-lock"></i>
                    </span>
                  </p>
                </div>
                <input type="submit" class="button is-block is-info is-large is-fullwidth" />
            </form>
            </div>
            <p id="error" style="color:red;visibility:hidden"><b>Login failed with the provided user and password</b></p>
          </div>
        </div>
      </div>
    </section>
  </body>
   <?php
       $error = $_GET["error"];
       if($error){
           echo "<script>";
           echo "document.getElementById('error').style.visibility = 'visible';";
           echo "</script>";
       }
   ?>
</html>
