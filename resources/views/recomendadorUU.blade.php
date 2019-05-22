<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

<script src="{{ asset('js/labels.js') }}" defer></script>

<main class="content">
  <div class="container" id="what" style="margin-top: 10px" align="center">

     <div class="panel-group">
        <div class="panel panel-default">
          <div class="panel-body"><h3><strong>Recomendaciones Usuario a Usuario</strong></h3><br></div>

          <form action="/calcular_correlacion" name="formm-user" class="row" style="margin: 10px">
            <div class="form-group col-xs-12 col-sm-8 col-md-6">
              <label for="email">Selecciona un usuario</label>
              <select id="id_user" class="form-control" id="sel1" name="user_id">
                 @foreach($results as $key => $data)
                 <option value="{{$data->user_id}}">{{$data->user_id}}</option>
                 @endforeach
              </select>
            </div>
            <div class="form-group col-xs-12 col-sm-8 col-md-6">
              <label for="pwd">Items del ranking</label>
              <input  type="text" class="form-control" id="item" value="5" name="item">
            </div>
            <div class="form-group col-xs-12 col-sm-8 col-md-6" onclick="enable_umbral()" >
              <label for="pwd">Umbral similitud</label>
              <input type="text" class="form-control" id="umbral" value="0.85" name="umbral">
            </div>
              <div class="form-group col-xs-12 col-sm-8 col-md-6" onclick="enable_vecin()" >
              <label for="pwd">Vecindario</label>
              <input type="text" class="form-control" id="vecin"  disabled="true" value="" name="vecin">
            </div>
            <button id="btn_recomendar" type="submit" class="btn btn-primary">Recomendar</button>
          </form>

        </div>
      </div>

      <!--
      <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo">Info</button>
      <div id="demo" class="collapse" style="margin-top: 10px">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit,
        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
      </div>
      -->

  </div>
</main>