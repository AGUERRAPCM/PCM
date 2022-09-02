<?php
date_default_timezone_set('America/Lima');
session_start();
if (isset($_SESSION['oUser'])) {   
    include("head.php");  
    require_once 'class/grupo.php';
    require_once 'class/entidad.php';
    
    if (isset($_GET['m'])) {
        $objGrupo = new Grupo();
        $id_grupo =$_GET['m'];
        $grupo = $objGrupo->get_grupo($id_grupo);                                 
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
                    <li class="breadcrumb-item active">Agregar Dependencias</li>                    
                </ol>
                
            </nav>            
        </div>      
</div>
<!-------  FIN  ---------------------------------------    BARRA DE NAVEGACION     --------------->

<!-------  INICIO  ------------------------------------    CONTENEDOR PRINCIPAL     -------------->
<div class="container">
    <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
        <section class="content-header">
            <h3 class="tituloRegistro">Agregar Dependencias</h3>      
        </section>   
        <form class="form-horizontal mt-5" method="POST">                                          
            <div class="form-group row">
                <label for="no_grupo" class="col-sm-3 control-label">Nombre de Grupo :</label>
                <div class="col-sm-6">
                    <label for="no_grupo" class="control-label"><strong><?php echo $grupo[0]['no_grupo'];?></strong></label>         
                </div>
            </div>

            <div class="form-group row" >
                <label for="id_padre" class="col-sm-3 control-label">Sedes</label>
                <div class="col-sm-6">          
                    <select class="form-control" name="comboSedes" id="comboSedes">
                        <option value="0">Seleccione sede</option>
                        <?php                                              
                                $objE = new Entidad(); 
                                $sedes = $objE->listaSedes();                         
                        ?>
                        <?php foreach($sedes as $sede):?>
                            <option value=<?php echo $sede['id_sede']?>><?php echo $sede['no_sede']?></option>
                        <?php endforeach?>                   
                    </select>              
                </div>        
            </div>

            <div class="form-group row" >
                <label for="no_grupo" class="col-sm-3 control-label">Nombre de Dependencia</label>
                <div class="col-sm-6">    
                    <select class="form-control" name="comboDependencias" id="comboDependencias" disabled>
                        <option value="0">Seleccione dependencia</option>                     
                    </select>
                </div>
            </div>                                                     
                                                        
            <button type="button" class="btn action-button-salida" onclick="cerrarAddDepen();"><i class="fa fa-close"></i> Cerrar</button>
            <button onclick="guardarDepen(<?php echo $grupo[0]['id_grupo']?>);"  type="button" id="btn_add_depen" name="btn_add_depen" class="btn action-button"><i class="fa fa-save"></i> Guardar</button>  
        </form>      
    </div>
</div>
<!-------  FIN  ---------------------------------------    CONTENEDOR PRINCIPAL     -------------->


<!-------  INICIO  --------------------------------------------    SCRIPTS     ------------------->
<script>  
    $('#comboSedes').change(function() {      
          var comboSedes = document.getElementById("comboSedes");
          var idSedeSeleccionada = comboSedes.options[comboSedes.selectedIndex].value;
          var comboDependencias = document.getElementById("comboDependencias");
          
          if(idSedeSeleccionada==0){           
              comboDependencias.selectedIndex = 0;
              comboDependencias.disabled =  true;      
          }else{             
              comboDependencias.disabled =  false;
              cargarDependencias(idSedeSeleccionada);         
          }  
    });

    function cargarDependencias(id_sede){
        var comboDependencias = document.getElementById("comboDependencias");
        $.ajax({
                url: "admin_entidad.php", 
                type: "POST",                          
                data: {"opcion": 3, "id_sede":id_sede},
                success:  function (data) {
                    var dependencias = JSON.parse(data);
                      
                    comboDependencias.innerHTML='';
                    let option = document.createElement('option');
                    option.innerHTML = 'Seleccione una dependencia';
                    option.value = '0';
                    comboDependencias.appendChild(option);
                    
                    for (i = 0; i < dependencias.data.length; i++){
                        let option = document.createElement('option');
                        option.innerHTML = dependencias.data[i].no_depen;
                        option.value = dependencias.data[i].id_depen;
                        comboDependencias.appendChild(option);
                    }      
                }
        }); 
    }


    function cerrarAddDepen(){
        window.location.href = "main_grupo.php";  
    }

    function guardarDepen(id){
        let urlController = 'controllers/GrupoController.php';
        $id_grupo = id;
        $id_sede = $('#comboSedes').val();
        $id_depen = $('#comboDependencias').val();     
        $.ajax({
            url: urlController, 
            type: "POST",                               
            data: {
                opcion:20, // Opcion del controlador (admin_grupo)
                id_grupo:$id_grupo,
                id_depen:$id_depen,
                id_sede: $id_sede       
            },
            success:  function (response) {                                       
                let respuesta = JSON.parse(response);
                
                if(respuesta[0].resultado){
                    window.location.href = "main_grupo.php";  
                } else{
                    alert(' DEPENDENCIA YA EXISTE EN GRUPO');
                }                                                                                                                                  
            }       
          });          
    }
</script>
<!-------  FIN  -----------------------------------------------    SCRIPTS     ------------------->




<?php include("pie.php");?>

<?php
}else{
header("Location:index.php?alter=5");
}
?>
