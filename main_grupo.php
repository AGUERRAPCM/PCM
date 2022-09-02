<?php
    date_default_timezone_set('America/Lima');
    session_start();
    if (isset($_SESSION['oUser']) && ($_SESSION['perfil']==1||$_SESSION['perfil']==0)) {    
        include("head.php");              
?>

<!-------  INICIO  ------------------------------------    BARRA DE NAVEGACION     --------------->
<div class="breadcrumb-full-width">          
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" id="menu">                  
                <li class="breadcrumb-item"><a aria-label="Inicio" href="inicio.php"><i class="fas fa-home"></i></a></li>                    
                <li class="breadcrumb-item"><a href="admin.php">Administración</a></li>
                <li class="breadcrumb-item active">Grupos</li>                    
            </ol>  
        </nav>            
    </div>      
</div>
<!-------  FIN  ---------------------------------------    BARRA DE NAVEGACION     --------------->


<!-------  INICIO  ------------------------------------    CONTENEDOR PRINCIPAL     -------------->
<div class="container">   
    <section class="content-header">
      <span class="titulos_4">Registra los grupos de dependencias</span>      
    </section>
 
    <section class="content">      
        <div class=" row">        
            <div class="col-md-8" ></div>
            <div class="col-md-4" >
                <button type="button" name="btnCrearGrupo" onclick="mostrarAddGrupo();"class="pull-right btn action-button">CREAR GRUPO</button>                
            </div>
            <input type="hidden" name="id_ciu" class="form-control" id="id_ciu" value="<?php echo $id_ciu;?>">
            <input type="hidden" name="id_sede" class="form-control" id="id_sede" value="<?php echo $id_sede;?>">
            <input type="hidden" name="id_depen" class="form-control" id="id_depen" value="<?php echo $id_depen;?>">
            <input type="hidden" name="nombres" class="form-control" id="nombres" value="<?php echo $nombres;?>">   
        </div>
    
        <div class="box box-danger mt-1">                           
            <div class="box-body">
                <table id="maintable" class="table table-bordered table-striped " style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>                                                
                            <th>Nombre Grupo</th>
                            <th>Dependencias</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            require_once 'class/grupo.php';
                            $objG = new Grupo(); 
                            $grupos = $objG->get_lista_grupos();                         
                        ?>
                        <?php foreach($grupos as $grupo):?>
                        <?php 
                            $dependencias = $objG->get_lista_dependencias_por_grupo($grupo['id_grupo']);                 
                        ?>    
                        <tr>                          
                            <td><?php echo $grupo['id_grupo']?></td>
                            <td><?php echo $grupo['no_grupo']?></td>
                            <td>                             
                                <?php if (count($dependencias)==0):?>
                                    <span class="text-center">No tiene dependencias asignadas, agregue una.</span>
                                    <button onclick="mostrarAddDepen(<?php echo $grupo['id_grupo']?>)" class="btn btn-info border">Agregar Dependencias al grupo</button>
                                <?php else:?>
                                    <?php foreach($dependencias as $d):?>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <input type="hidden" id="id_sede" value="<?php echo $d['id_sede'];?>">
                                                <input type="hidden" id="id_depen" value="<?php echo $d['id_depen'];?>">
                                                <ol><?php echo $d['no_sede'].'->'.$d['no_depen']. ' ::  ' .$d['de_depen']?></ol>
                                            </div>
                                            <div class="col-md-2">
                                                <button onclick="intentaEliminarDependencia(<?php echo $grupo['id_grupo'].','.$d['id_sede'].','.$d['id_depen'];?>)" class="delete btn btn-ligth border"><img src='application/images/delete.png'></button>    
                                            </div>
                                        </div>                                                                      
                                    <?php endforeach?>
                                    <!-- Boton Agregar dependencia , esta en txt-->
                                    <button onclick="mostrarAddDepen(<?php echo $grupo['id_grupo']?>)" class="btn link_button"><i class='fas fa-plus-circle size_icon'></i>Agregar Dependencias al grupo</button>
                                    
                                <?php endif?>                                                          
                            </td>
                                <?php if($grupo['es_grupo']==1): ?>
                                    <td><?php echo 'ACTIVADOO'?></td>
                                <?php else:?>     
                                    <td><?php echo 'NOO ACTIVADOO'?></td>
                                <?php endif?>
                            <td>
                                <button onclick="editarGrupo(<?php echo $grupo['id_grupo']?>)" class="edit btn btn-ligth border"><img src='application/images/edit.png'></button>
                                <button onclick="intentaEliminarGrupo(<?php echo $grupo['id_grupo'];?>)" class="delete btn btn-ligth border"><img src='application/images/delete.png'></button>
                            </td>                                                  
                        </tr>
                        <?php endforeach?>
                    </tbody>            
                </table>
            </div>    
        </div>   
    </section> 
</div>    
<!-------  FIN  ---------------------------------------    CONTENEDOR PRINCIPAL     -------------->



<!-------  INICIO  -------------------------------------------    STYLE     ---------------------->
<style type="text/css">  
    .edit:hover {background-color: #f7dc6f;}
    .delete:hover {background-color: #ec7063;}
</style>
<!-------  FIN  ----------------------------------------------    STYLE     ---------------------->


<!-------  INICIO  --------------------------------------------    SCRIPTS     ------------------->
<script>  
    var tabla;
    var urlController = "controllers/GrupoController.php";  
    var validator;
   
    $(document).ready(function(){
        configurarTabla();   
    }); 

    function mostrarAddDepen(id_grupo){    
        window.location.href = 'main_grupo_add_depen.php?m='+id_grupo;
    }

    function editarGrupo(id_grupo){   
        window.location.href = 'main_grupo_add.php?m='+id_grupo;
    }
  
    function mostrarAddGrupo(){      
        window.location.href = "main_grupo_add.php";
    }
  
    function intentaEliminarDependencia(idGrupo,idSede, idDepen){
        var opcion = confirm("Desea eliminar Dependencia?");
        if (opcion == true) {
            eliminarDependencia(idGrupo,idSede,idDepen);
        } 
    }

    function eliminarDependencia(idGrupo,idSede,idDepen){    
        $.ajax({
            url: urlController, 
            type: "POST",                          
            data: {
                opcion:19, // Opcion del controlador (admin_grupo)
                id_grupo:idGrupo,
                id_sede:idSede,
                id_depen:idDepen
            },
            success:  function (data) {                                               
                window.location.href = "main_grupo.php";           
            }
        });  
    }

    function intentaEliminarGrupo(idGrupo){                 
        var opcion = confirm("Desea eliminar Grupo ID : "+idGrupo);
        if (opcion == true) {
            eliminarGrupo(idGrupo);
        }      
    }

    function eliminarGrupo(id){               
        $id_grupo = id;   
        $.ajax({
            url: urlController, 
            type: "POST",                          
            data: {
                opcion:18, // Opcion del controlador (admin_grupo)
                id_grupo:$id_grupo
            },
            success:  function (data) {                                                     
                window.location.href = "main_grupo.php";           
            }
        });          
    }


    function configurarTabla() {    
        if(tabla) {
            tabla.destroy();
        }

        tabla = $('#maintable').DataTable({                    
            dom: 'Bfrtip',
            responsive: true,
            pageLength: 10,
            paging:   true,
            ordering: false,
            info: true,
            buttons: false,
            searching: true,
            language: {
                "emptyTable": "No hay grupos disponibles",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Registros",
                "infoEmpty": "Mostrando 0 to 0 of 0 Registros",
                "infoFiltered": "(Filtrado de _MAX_ total registros)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Registros",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados"
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
