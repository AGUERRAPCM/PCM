<?php
date_default_timezone_set('America/Lima');
session_start();
if (isset($_SESSION['oUser'])) {
    //include_once("../conexion/conexion.php");
    include("head.php");
    include_once 'class/grupo.php';
    $objG = new Grupo(); 
    
    
  if (isset($_GET['m'])&&$_GET['m']!='') {     
      $modo = "editarGrupo";
      $id_grupo = $_GET['m'];
      $grupo = $objG->get_grupo($id_grupo);
      $textoB = 'Editar Grupo';
  }else{
      $modo = "guardarNuevoGrupo";
      $textoB = 'Nuevo Grupo';
  }
?>

<!-------  INICIO  ------------------------------------    BARRA DE NAVEGACION     --------------->
<div class="breadcrumb-full-width">          
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" id="menu">                  
                <li class="breadcrumb-item"><a aria-label="Inicio" href="inicio.php"><i class="fas fa-home"></i></a></li>                    
                <li class="breadcrumb-item"><a href="admin.php">Administración</a></li>
                <li class="breadcrumb-item"><a href="main_grupo.php">Grupos</a></li>
                <li class="breadcrumb-item active"><?php echo $textoB;?></li>                    
            </ol>    
        </nav>            
    </div>      
</div>
<!-------  FIN  ---------------------------------------    BARRA DE NAVEGACION     --------------->


<!-------  INICIO  ------------------------------------    CONTENEDOR PRINCIPAL     -------------->
<div class="container">
  <input type="hidden" name="modo" class="form-control" id="modo" value="<?php echo $modo;?>">
  <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
    <section class="content-header">
      <h2 id="tituloPagina" class="tituloRegistro">Crear Nuevo Grupo</h2>      
    </section>
   
    <form class="form-horizontal mt-5" method="POST" id="frmAgregarGrupo">             
          <input type="hidden" name="id_grupo" class="form-control col-sm-9" id="id_grupo" 
              value="<?php if(isset($grupo)){echo $grupo[0]['id_grupo'];}?>" readonly>
        
          <div class="form-group row">
              <label for="nombre_grupo" class="col-sm-3 control-label">Nombre de Grupo</label>
              <div class="col-sm-6">
                <input type="text" name="nombre_grupo" class="form-control" id="nombre_grupo" value="<?php if(isset($grupo)){echo $grupo[0]['no_grupo'];}?>">
              </div>
          </div>              
          <div class="form-group row" >              
            <div class=" col-md-4"></div>
            <div class="form-check col-md-4">
                <input class="form-check-input chkbox" type="checkbox" value="1" id="chk_estado" name="chk_estado" 
                      <?php 
                          if(isset($grupo)){
                            if($grupo[0]['es_grupo']==1){
                                echo 'checked';
                            }
                          }else{ 
                            echo 'checked disabled';
                          }
                      ?>
                >
                <label class="form-check-label" for="estado" ><strong>Activo</strong></label>
            </div>
          </div>          
          <input type="hidden" name="estado" class="form-control" id="estado">                   
                    
          <button type="button" class="btn action-button-salida" onclick="cerrarVistaGrupo();" ><i class="fa fa-close"></i> Cerrar</button>
          <button onclick="ejecutarAccion()"  type="button" id="btn_add_grupo" name="btn_add_grupo" class="btn action-button"><i class="fa fa-save"></i> Guardar</button>       
      </form>      
  </div>
</div>
<!-------  FIN  ---------------------------------------    CONTENEDOR PRINCIPAL     -------------->


<!-------  INICIO  --------------------------------------------    SCRIPTS     ------------------->
<script>  
    $modo = $('#modo').val();
    if($modo=='editarGrupo'){
        document.getElementById("tituloPagina").innerHTML = "Editar Grupo"; 
    }

    $('#nombre_grupo').on('input', function (e) {
      if (!/^[ a-z0-9áéíóúüñ]*$/i.test(this.value)) {
        this.value = this.value.replace(/[^ a-z0-9áéíóúüñ]+/ig,"");
      }
    });

    var urlController = "controllers/GrupoController.php";
    var validator;

    function ejecutarAccion(){
        $modo = $('#modo').val();
        switch ($modo){
            case 'guardarNuevoGrupo': guardarGrupo();break;
            case 'editarGrupo': actualizarGrupo();break;
            default : window.location.href = "main_grupo.php"; break; 
        }          
    }
    
    function cerrarVistaGrupo(){
      window.location.href = "main_grupo.php";  
    }
    

  function guardarGrupo() {   
      $nombre_grupo = $('#nombre_grupo').val();
      if ($("#frmAgregarGrupo").valid()) {
          $.ajax({
              url: urlController, 
              type: "POST",                          
              data: {
                  opcion:12,
                  no_grupo:$nombre_grupo
              },
              success:  function (data) {
                  console.log(data);                                         
                  window.location.href = "main_grupo.php";           
              }
            });
        }        
    }

  function actualizarGrupo() {         
      if ($("#frmAgregarGrupo").valid()) {
          $.ajax({
              url: urlController, 
              type: "POST",                          
              data: {
                  opcion:16,
                  id_grupo:$('#id_grupo').val(),          
                  no_grupo:$('#nombre_grupo').val(),              
                  es_grupo:(document.getElementById('chk_estado').checked)?'1':'0'
              },
              success:  function (data) {
                  mensaje("Cargo grabado exitosamente!"); 
                  window.location.href = "main_grupo.php";                            
              }
          });
      }    
  }

  

  $(document).ready(function(){ 
    $(".chkbox").change(function () {
        if(this.checked) { 
          $('#estado').val(1);
        } else {          
          $('#estado').val(0);          
        }                
    });

    if ($('#modo').val()==1) {
        document.getElementById("frmAgregarGrupo").reset();
        $('#frmAgregarGrupo').trigger("reset");
        // $('#chk_estado').attr('checked', true);
        // $('#estado').val(1);
        // $('#ModalAgregarCiu').modal('show');  
        // $('#chk_estado').prop('disabled', true); 
    } else {
        document.getElementById("frmAgregarGrupo").reset();
        $('#frmAgregarGrupo').trigger("reset");        
        $('#estado').val(1);
        // $('#ModalAgregarCiu').modal('show');    
        // $('#chk_estado').prop('disabled', false);      
        // console.log($('#id_cargo').val());
        // getItem($('#id_cargo').val());
    }

    validator =$("#frmAgregarGrupo").validate({
          onkeyup: false,
          focusInvalid: false,
          focusCleanup: true,
          onfocusout: false,
          onclick: false,
          rules: {                           
              nombre_grupo:{
                required:true,
                minlength: 3,
              }                
          },  
          messages: {                                                     
              nombre_grupo:{  
                  required: "Nombre de grupo es requerido.",
                  minlength: "Nombre de grupo debe tener más de 3 caracteres.",                          
              }                                    
          },                
          showErrors: function(errorMap, errorList){
            errors=""; 
            
            $.each(errorList,function(key,item){                
                errors+=item.message+"<br>";                
                Swal.fire({              
                    html: errors,
                    icon: 'warning',
                    showConfirmButton: false,
                    position: 'top',
                    width: '500px',
                    timer: 2000,
                    toast: true              
                });                  
            });                                                 
          },
    });   
  }); 
</script>
<!-------  FIN  -----------------------------------------------    SCRIPTS     ------------------->




<?php include("pie.php");?>

<?php
}else{
header("Location:index.php?alter=5");
}
?>
