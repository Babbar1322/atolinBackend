<!DOCTYPE html>
<html lang="en">
<head>

  <title>Etherdieum</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
  body {
    font: 20px Montserrat, sans-serif;
    line-height: 1.8;
    color: #f5f6f7;
  }
  .navbar {
    padding-top: 15px;
    padding-bottom: 15px;
    border: 0;
    border-radius: 0;
    margin-bottom: 0;
    font-size: 12px;
    letter-spacing: 5px;
  }
  .navbar-nav  li a:hover {
    color: #1abc9c !important;
  }
  .navbar-brand
  {
    padding: 0px;
  }
  #privacy-policy p
  {
      font-size: 14px;
      color: #000;
      text-align: justify;
  }
  #privacy-policy h1
  {
      font-size: 25px;
      color: #404e67;
      margin-bottom: 25px;
      text-align: left;
  }
  .navbar-default .navbar-nav>li>a
  {
    color: #000;
    letter-spacing: 0px;
  }
  .navbar-default .navbar-nav>li>a:hover
  {
    color: #404e67 !important;
  }
  section#privacy-policy
  {
    padding: 50px 0px;
  }
  footer
  {
    padding: 20px 0px !important;
    background: #29a539;
  }
  #privacy-policy-footer p
  {
    font-size: 14px;
    color: #fff;
    margin:0px;
  }
  .back-to-top
  {
    cursor: pointer;
    position: fixed;
    bottom: 20px;
    right: 20px;
    display:none;
    background: #404e67;
    border-color: #fff;
  }
  .btn-primary:hover
  {
    color: #fff;
    background-color: #404e67;
    border-color: #fff;
  }
  @media (max-width: 767px)
  {
    .nav>li>a{text-align: center;}
  }
  .privacy_content h2
  {
    color: #000;
    font-size: 18px;
    line-height: 30px;
  }
  .col-md-6.offset-md-3.privacy_content 
  {
    margin-left: 25%;
    border: 1px solid #000;
    border-radius: 50px;
    padding: 25px;
  }
  section#privacy-policy-footer 
  {
    position: absolute;
    width: 100%;
    bottom: 0;
    content: '';
    left: 0;
    right: 0;
  }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><img src="logo.png" style="width:50px;"></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#">HOME</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="policy-content" id="privacy-policy">
    <div class="container text-center">

    <div class="row">
      <div class="col-sm-12">
        <h1>Contact Us</h1>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 offset-md-3 privacy_content">
        <h2> Name : Andrej Tokmakov</h2>
        <h2> Mobile: (305) 924-5533</h2>
        <h2> Email : andrej@torustechnology.us</h2>
      </div>
    </div>

    </div>
</section>

<section id="privacy-policy-footer">
  <footer class="container-fluid bg-4 text-center">
    <p>copyrights @ atolin</p>
  </footer>
</section>

<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Click to return on the top page" data-toggle="tooltip" data-placement="left"><span class="glyphicon glyphicon-chevron-up"></span></a>

<script>
$(document).ready(function(){
     $(window).scroll(function () {
            if ($(this).scrollTop() > 50) {
                $('#back-to-top').fadeIn();
            } else {
                $('#back-to-top').fadeOut();
            }
        });
        $('#back-to-top').click(function () {
            $('#back-to-top').tooltip('hide');
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
        $('#back-to-top').tooltip('show');

});
</script>

</body>
</html>
