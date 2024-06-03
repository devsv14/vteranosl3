<!-- The Modal -->
<style>  
  .stilot1{
    border: 0.5px solid   #D0D0D0;    
    text-align: center;    
    padding: 0px
  }

  #modal_contenido{
    font-family: Open Sans, sans-serif;
    font-size: 13px;
    line-height: 1.5;
    margin: 0;
    text-align: left;
    color: black;
    text-transform: uppercase;  
  }

  .table2 {
    border-collapse: collapse;
  }

  #head_rec{
    background: #172b4d;
    color: white
  }

  .encabezado{
    background: #deeaee;
    color: black
  }
</style>

<div class="modal fade" id="modal_detalle_recti" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 55%">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header" style="padding: 15px" id="head_rec">
        <h4 class="modal-title" style="font-size: 14px;">DETALLE RECTIFICACIONES</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <div class="modal-body" id="modal_contenido">

      	<table width="100%" id="ordenes-actual" class="table2">
         <tr><td id="orden_inicial"></td></tr>
         <tr><td id="listar_ordenes_rectif"></td></tr> 
        </table>

        </div>        
      </div>
    </div>
  </div>
  </div>