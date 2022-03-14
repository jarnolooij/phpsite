<!DOCTYPE html>
<html lang="en">
	<head>
	<title>Scouting Uotha Digitaal Fotohokje</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <style>
      body {
        background: #dfd679;
      }
      
      .description {
        left: 50%;
        position: absolute;
        top: 50%;
        transform: translate(-50%, -55%);
        text-align: center;
      }

      .description p {
        color: black;
        font-size: 1rem;
        line-height: 1.5;
      }

      .button-padding{
        padding: 10px;
      }
      .logo-image{
        width: 100px;
        height: 75px;
      }
    </style>

  </head>
  <body>
    <div class="description">
      <img src="/img/scoutinglogo75jaar.png" class="logo-image">
      <h5>Digitaal Fotohokje</h5>
      <p>Voor het 75 jaar bestaan van Scouting Uotha Uden feest maken we een fotoboek. Voer je E-Mail addres in als je een digitaal fotoboek wilt en vink de checkbox aan als u een papieren fotoboek wilt. Zo niet klik dan direct op doorgaan!</p>
      <br>
      <form action="/register" method="POST">
        <input type="email" placeholder="Voer uw E-mail in..." name="email"> 
        <br> 
        <input type="checkbox" name="fotoboek">
        <label for="fotoboek"> ik wil een fotoboek!</label>
        <div class="button-padding">
          <button type="submit" class="btn btn-success">Doorgaan</button>
        </div>
      </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
</html>