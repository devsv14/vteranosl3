<style>
  @media screen and (min-width: 720px){
    .modal-header{
    margin-right: 0px !important;
    opacity: 0.7;
  }
  }
  @media screen and (max-width: 720px){
    .descripcion-preview-pdf{
      display: none;
  }
  }
</style>
<!-- Modal -->
<div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header sticky-top bg-light text-white">
        <h5 style="font-size: 14px;" class="modal-title" id="pdfModalLabel"><b id="paciente_acta"></b> : <b id="duiActa"></b>&nbsp;| |&nbsp;&nbsp;Escaneado por: <b id="usuario_system"></b> &nbsp;&nbsp; <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Descargar Expedientes de Actas Firmadas"><i class="fas fa-download text-light" aria-hidden="true" style="color:#b14e4e; cursor:pointer" onClick="impPDFUploadActa()"></i></button> </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="pdfFrame">
          <div class="header_pdf">
            <!--  <div id="watermark">
              <img src="../dist/img/Escudo_Gobierno.jpg" width="700" height="700" />
            </div> -->
            <table style="width: 100%;margin-top:2px;" width="100%">
              <tr>
                <td width="25%" style="width:25%;margin:0px">
                 
                </td>

                <td class="descripcion-preview-pdf" width="50%" style="width:50%;margin:0px">
                  <table style="width:100%">
                    <br>
                    <tr>
                      <td style="text-align: center;margin-top: 0px;font-size:15px;font-family: Helvetica, Arial, sans-serif;"><b>ACTAS FIRMADAS</b><br><b id="sucursal-acta"></b></td>
                    </tr>
                  </table>
                </td>
   
                <td width="25%" style="display: flex;justify-content-end">
                  <img src='../dist/img/logo_avplus.jpg' width="120" style="margin-top:5px;float: left;"><br>
                </td>
              </tr>
            </table>
          </div>
          <div class="row">
            <div class="pdf-content" id="pdf-content">
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>