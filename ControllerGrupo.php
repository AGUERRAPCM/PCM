<?php
	require_once '../class/grupo.php';	
	$objGrupo = new Grupo();  		
  
		// OBTENER LISTA DE GRUPOS => OPCION 9
    	if (isset($_GET['opcion']) && $_GET['opcion']==9) {			
			$id_ciu=$_GET['i'];
			$id_depen=$_GET['d'];
			$id_sede=$_GET['s'];
			$datos=$objGrupo->get_lista_grupos();                
        	$data = array();
 			foreach ($datos as $row) {                        	
			    if($row["es_grupo"]==1){
                    $estado = "ACTIVADO";
                }else{
                    $estado = "NO ACTIVADO";
                }
                $data [] = array(
                    "id"=>$row["id_grupo"],                                                        
                    "nombre_grupo"=>$row["no_grupo"],
                    "estado_grupo"=>$estado                                              			  	    
                );			  
        	}
			echo json_encode(array("data"=> $data));        
		}

        // GUARDAR GRUPO => OPCION 12
        if (isset($_POST['opcion']) && $_POST['opcion']==12) {	      	        
    		$no_grupo=$_POST['no_grupo'];
    		$es_grupo=1;	
    		$id_ciu_crea=$_SESSION['id_ciu'];    		
    		//$id_ciu_crea='185';    		
    		$id_ciu_mod='0';    		
	        $datos =$objGrupo->addGrupo($no_grupo,$es_grupo,$id_ciu_crea,$id_ciu_mod);
	        //echo json_encode($datos);
    	}

        // EDITAR GRUPO => OPCION 16
        if (isset($_POST['opcion']) && $_POST['opcion']==16) {	      	        
    		$id_grupo=$_POST['id_grupo'];
            $no_grupo=$_POST['no_grupo'];
    		$es_grupo=$_POST['es_grupo'];		    		
    		$id_ciu_mod=$_SESSION['id_ciu'];    		
	        $datos =$objGrupo->editGrupo($no_grupo,$es_grupo,$id_ciu_mod,$id_grupo);
	        //echo json_encode($datos);
    	}

		// OBTENER UN GRUPO => OPCION 15
    	if (isset($_GET['opcion']) && $_GET['opcion']==15) {			
			$id_ciu=$_GET['i'];
			$id_depen=$_GET['d'];
			$id_sede=$_GET['s'];
			$datos=$objGrupo->get_lista_grupos();                
        	$data = array();
 			foreach ($datos as $row) {                        	
			    if($row["es_grupo"]==1){
                    $estado = "ACTIVADO";
                }else{
                    $estado = "NO ACTIVADO";
                }
                $data [] = array(
                    "id"=>$row["id_grupo"],                                                        
                    "nombre_grupo"=>$row["no_grupo"],
                    "estado_grupo"=>$estado                                              			  	    
                );			  
        	}
			echo json_encode(array("data"=> $data));       
		}

		// OBTENER UN GRUPO => OPCION 17
    	if (isset($_GET['opcion']) && $_GET['opcion']==17) {			
			$id_grupo=$_POST['id_grupo'];
			$id_depen=$_POST['id_depen'];
			$id_sede=$_POST['id_sede'];			
			$datos=$objGrupo->get_dependencia($id_grupo,$id_sede,$id_depen); 		            
        	echo json_encode($datos);		
		}



        // ELIMINAR UN GRUPO => OPCION 18
    	if (isset($_POST['opcion']) && $_POST['opcion']==18) {			
			$id_grupo=$_POST['id_grupo'];
			$objGrupo->delete_grupo($id_grupo);                     	
		}

		
		// ELIMINAR UNA DEPENDENCIA => OPCION 19
    	if (isset($_POST['opcion']) && $_POST['opcion']==19) {			
			$id_grupo =$_POST['id_grupo'];
			$id_sede =$_POST['id_sede'];
			$id_depen =$_POST['id_depen'];
			$objGrupo->delete_dependencia($id_grupo, $id_sede,$id_depen);                     	
		}

		// AGREGAR UNA DEPENDENCIA AL GRUPO => OPCION 20
		if (isset($_POST['opcion']) && $_POST['opcion']==20) {	      	        
    		$id_grupo=$_POST['id_grupo'];   		
			$id_sede=$_POST['id_sede'];
    		$id_depen=$_POST['id_depen']; 				 
	        $datos =$objGrupo->addDepen($id_grupo,$id_sede,$id_depen);       		
			echo json_encode($datos);
    	}
?>	
    
