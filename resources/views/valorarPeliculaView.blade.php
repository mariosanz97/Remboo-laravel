<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<div align="center" style="margin: 50px;">
  <form action="/valorar" name="formm-user" class="row" style="margin: 10px">
    <div class="form-group col-xs-12 col-sm-8 col-md-6">
      <label for="email">Selecciona una pelicula para valorar</label>
      <select id="nombrePeli" class="form-control" id="sel1" name="nombrePeli">
            @for ($i = 0; $i < sizeof($NoVistas); $i++)
           <option id="{{$NoVistas[$i]}}" value="{{$NoVistas[$i]}}">{{$NoVistas[$i]}}</option>
           @endfor
      </select>
    </div>
    <div class="form-group col-xs-12 col-sm-8 col-md-6" onclick="enable_umbral()" >
      <label for="pwd">Puntuacion (0-5)</label>
      <input min="0" max="5" type="number" step="any" class="form-control" id="valorar" name="valorar">
    </div>
    <button id="btn_recomendar" type="submit" class="btn btn-primary">Valorar</button>
  </form>
</div>