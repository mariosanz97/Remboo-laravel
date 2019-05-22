  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<div class="container">
  <h2>Prediccion</h2>
  <table class="table">
    <thead>
      <tr>
        <th>Pelicula</th>
        <th>Prediccion</th>
      </tr>
    </thead>
    <tbody>
     
      @for ($i = 0; $i < sizeof($Fidpel); $i++)
        <tr>
          <td>{{$Fidpel[$i]}}</td>
          <td>{{$Fpredic[$i]}}</td>
        </tr>
       @endfor
    </tbody>
  </table>
</div>