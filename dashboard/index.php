<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../bootstrap/favicon.ico">

    <title>Dashboard Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">
  </head>

  <body>

    <!-- This is the navigation bar -->
    <header>
      <nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">
        <a class="navbar-brand" href="../cover">UK SWE Membership Portal</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="../cover">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../signin">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="../searchID">Check Points</a>
            </li>
          </ul>
          <!-- <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
          </ul> -->
        </div>
      </nav>
    </header>

    <div class="container-fluid">
      <div class="row">
<!-- col-sm-9 ml-sm-auto col-md-10 pt-3 -->
        <main role="main" class="dashboard">
          <h1>Dashboard</h1>

          <section class="row text-center placeholders">
            <div class="col-6 col-sm-3 placeholder">
              <img src="data:image/gif;base64,R0lGODlhAQABAIABAAJ12AAAACwAAAAAAQABAAACAkQBADs=" width="200" height="200" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
              <h4>Label</h4>
              <div class="text-muted">Something else</div>
            </div>
            <div class="col-6 col-sm-3 placeholder">
              <img src="data:image/gif;base64,R0lGODlhAQABAIABAADcgwAAACwAAAAAAQABAAACAkQBADs=" width="200" height="200" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
              <h4>Label</h4>
              <span class="text-muted">Something else</span>
            </div>
            <div class="col-6 col-sm-3 placeholder">
              <img src="data:image/gif;base64,R0lGODlhAQABAIABAAJ12AAAACwAAAAAAQABAAACAkQBADs=" width="200" height="200" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
              <h4>Label</h4>
              <span class="text-muted">Something else</span>
            </div>
            <div class="col-6 col-sm-3 placeholder">
              <img src="data:image/gif;base64,R0lGODlhAQABAIABAADcgwAAACwAAAAAAQABAAACAkQBADs=" width="200" height="200" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
              <h4>Label</h4>
              <span class="text-muted">Something else</span>
            </div>
          </section>

          <h2>General Meetings</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Points</th>
                  <th>Name</th>
                  <th>Date</th>
                  <th>Company</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>3</td>
                  <td>September Meeting</td>
                  <td>9/21/2017</td>
                  <td>Novelis</td>
                </tr>
                <tr>
                  <td>1</td>
                  <td>amet</td>
                  <td>12/5/2017</td>
                  <td>adipiscing</td>
                </tr>
                <tr>
                  <td>1</td>
                  <td>amet</td>
                  <td>12/2/2015</td>
                  <td>adipiscing</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>amet</td>
                  <td>3/4/17</td>
                  <td>adipiscing</td>
                </tr>
              </tbody>
            </table>
          </div>
	  <h2>Outreach</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Points</th>
                  <th>Name</th>
                  <th>Date</th>
                  <th>Company</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>3</td>
                  <td>September Meeting</td>
                  <td>9/21/2017</td>
                  <td>Novelis</td>
                </tr>
                <tr>
                  <td>1</td>
                  <td>amet</td>
                  <td>12/5/2017</td>
                  <td>adipiscing</td>
                </tr>
                <tr>
                  <td>1</td>
                  <td>amet</td>
                  <td>12/2/2015</td>
                  <td>adipiscing</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>amet</td>
                  <td>3/4/17</td>
                  <td>adipiscing</td>
                </tr>
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../bootstrap/assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="../bootstrap/assets/js/vendor/popper.min.js"></script>
    <script src="../bootstrap/dist/js/bootstrap.min.js"></script>
  </body>
</html>
