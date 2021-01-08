<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v9.0" nonce="Sy6mCEYT"></script>

<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-danger">
    <a class="navbar-brand" href="index.php">Gains</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link topbar" href="index.php">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link topbar" href="newmax.php">New Max</a>
        </li>
        <li class="nav-item">
            <a class="nav-link topbar" href="newbodyweight.php">New Bodyweight</a>
        </li>
        <li class="nav-item">
            <a class="nav-link topbar" href="chart.php">Trends</a>
        </li>
        <li class="nav-item">
            <a class="nav-link topbar" href="entries.php">Entry Log</a>
        </li>
      </ul>
      <p class="navbar-text" id="navbar_username"><? session_start(); echo $_SESSION["username"] ?></p>
      <span class="navbar-text">
          
          <a class="btn btn-outline-dark" href="logout.php">Log Out</a>
      </span>
    </div>
  </nav>
  