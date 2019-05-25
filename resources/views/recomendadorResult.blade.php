<body  onload="sortTable()">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<script>
function sortTable() {
  var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById("myTable");
  switching = true;
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[2];
      y = rows[i + 1].getElementsByTagName("TD")[2];
      //check if the two rows should switch place:
      if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
        //if so, mark as a switch and break the loop:
        shouldSwitch = true;
        break;
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }
}
</script>
<script src="{{ asset('js/viewmovie.js') }}"></script>
<div class="container">
  <h2>Prediccion</h2>
  <p>Haga click en el nombre para ver más información de la película.</p>
  <table class="table" id="myTable">
    <thead>
      <tr>
        <th>id Pelicula</th>
        <th>Titulo Pelicula</th>
        <th>Predicción</th>
      </tr>
    </thead>
    <tbody>
      @for ($i = 0; $i < $item; $i++)
         @if(!empty($Fidpel[$i]))
          <tr>
            <td>{{$Fidpel[$i]}}</td>
            <td id="{{$Fidpel[$i]}}" class="select-movie">{{$FnombrePeli[$i]}}</td>
            <td>{{$Fpredic[$i]}}</td>
          </tr>
          @endif
       @endfor

    </tbody>
  </table>

  <div class="toast-body">
            
  </div>

  <div class="toast-photo">

  </div>
</div>
</body>
