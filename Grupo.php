<?php

require_once 'conexion.php';


class Grupo extends Conexion {

    public $mysqli;
    public $data;
    
    public function __construct() {
        $this->mysqli = parent::conectar();
        $this->data = array();
   
    }

    
    public function get_lista_grupos() {
        $query = "SELECT id_grupo, no_grupo, es_grupo, fe_crea, id_ciu_crea, fe_mod, id_ciu_mod
                    FROM grupo_dependencias ORDER BY fe_crea DESC";
        $datos = array(); 
        //$id_enti=$_SESSION['id_enti'];        
        $stmt = $this->mysqli->prepare($query);        
        //$stmt->bind_param('i', $id_enti); 
        $stmt->execute();
        $grupos = $stmt->get_result();        
        print_r($this->mysqli->error);
        while ($row = $grupos->fetch_assoc()) {            
              $datos[] = $row;
        }
        return $datos;
    }

   
    public function get_lista_dependencias_por_grupo($id_grupo) {                     
            
        $query = "SELECT vd.id_sede, vd.id_depen, vd.no_sede, vd.no_depen, vd.de_depen
                    FROM vw_dependencia vd
                    INNER JOIN grupo_dependencias_detalle gdd
                    ON vd.id_depen = gdd.id_depen AND vd.id_sede = gdd.id_sede AND vd.id_entidad = ? AND gdd.id_grupo = ?;";

        $datos = array(); 
        $id_enti=$_SESSION['id_enti'];        
        $stmt = $this->mysqli->prepare($query);        
        $stmt->bind_param('ii',$id_enti, $id_grupo); 
        $stmt->execute();
        
        $dependencias = $stmt->get_result();        
        print_r($this->mysqli->error);
        while ($row = $dependencias->fetch_assoc()) {            
              $datos[] = $row;
        }
        return $datos;
    }

    public function get_grupo($id_grupo) {
        $query = "SELECT id_grupo, no_grupo, es_grupo, fe_crea, id_ciu_crea, fe_mod, id_ciu_mod
                    FROM grupo_dependencias WHERE id_grupo = ?";
        $datos = array();               
        $stmt = $this->mysqli->prepare($query);        
        $stmt->bind_param('i', $id_grupo); 
        $stmt->execute();

        $grupo = $stmt->get_result();
               
        print_r($this->mysqli->error);
        while ($row = $grupo->fetch_assoc()) {            
              $datos[] = $row;
        }
        return $datos;
    }

    public function get_grupo_by_entidad_sede_depen($idEnti,$idSede,$idDepen){
        $query = "SELECT id_grupo_det, id_grupo, id_entidad, id_sede, id_depen
                    FROM grupo_dependencias_detalle WHERE id_entidad = ? AND id_sede = ? AND id_depen = ?";
        $datos = array();               
        $stmt = $this->mysqli->prepare($query);        
        $stmt->bind_param('iii', $idEnti,$idSede,$idDepen); 
        $stmt->execute();

        $grupo = $stmt->get_result();
               
        print_r($this->mysqli->error);
        while ($row = $grupo->fetch_assoc()) {            
              $datos[] = $row;
        }
        return $datos;
    }

    public function get_enti_sede_depen($idEnti,$idSede,$idDepen){
        $query = "SELECT id_grupo_det, id_grupo, id_entidad, id_sede, id_depen
                    FROM grupo_dependencias_detalle WHERE id_entidad = ? AND id_sede = ? AND id_depen = ?";
        $datos = array();               
        $stmt = $this->mysqli->prepare($query);        
        $stmt->bind_param('iii', $idEnti,$idSede,$idDepen); 
        $stmt->execute();

        $grupo = $stmt->get_result();
               
        print_r($this->mysqli->error);
        while ($row = $grupo->fetch_assoc()) {            
              $datos[] = $row;
        }
        return $datos;
    }


    public function addGrupo($no_grupo,$es_grupo,$id_ciu_crea,$id_ciu_mod) {
        $query = "INSERT INTO grupo_dependencias (no_grupo,es_grupo,fe_crea,id_ciu_crea,fe_mod,id_ciu_mod) VALUES (?,?,now(),?,now(),?)";    
        $stmt = $this->mysqli->prepare($query);        
        $stmt->bind_param('siii', $no_grupo,$es_grupo,$id_ciu_crea,$id_ciu_mod);        
        $stmt->execute();
        $resultado = $stmt->get_result();       
        return $resultado;
    }

    public function editGrupo($no_grupo,$es_grupo,$id_ciu_mod,$id_grupo) {       
        $query = "UPDATE grupo_dependencias SET no_grupo=?, es_grupo=?,id_ciu_mod=?, fe_mod=now() WHERE id_grupo=? ";
        $stmt = $this->mysqli->prepare($query);        
        $stmt->bind_param('siii', $no_grupo,$es_grupo,$id_ciu_mod,$id_grupo);        
        $stmt->execute();
        $resultado = $stmt->get_result();    
        return $resultado;
    }

    public function delete_grupo($id_grupo) {  
        $query1 = "SELECT * FROM grupo_dependencias_detalle WHERE id_grupo = ?";
        $datos = array(); 
        $query ="";              
        $stmt = $this->mysqli->prepare($query1);        
        $stmt->bind_param('i', $id_grupo); 
        $stmt->execute();
        $grupo = $stmt->get_result();
        while ($row = $grupo->fetch_assoc()){            
            $datos[] = $row;
        }
        
        if(count($datos)==0){
            $query = "DELETE FROM grupo_dependencias WHERE id_grupo = ?";
            
        }else{
            $query ="DELETE gdd,gd
                    FROM  grupo_dependencias_detalle AS gdd
                    INNER JOIN grupo_dependencias AS gd
                    ON gd.id_grupo = gdd.id_grupo 
                    WHERE gd.id_grupo = ?";
        }         
        $stmt = $this->mysqli->prepare($query);        
        $stmt->bind_param('i', $id_grupo); 
        $stmt->execute();            
        print_r($this->mysqli->error);          
    }

    public function delete_dependencia($id_grupo, $id_sede,$id_depen) { 
        $id_enti=$_SESSION['id_enti'];
        $query = "DELETE FROM grupo_dependencias_detalle 
                    WHERE id_grupo = ? AND id_entidad=? AND id_sede=? AND id_depen=?";
        $stmt = $this->mysqli->prepare($query);        
        $stmt->bind_param('iiii', $id_grupo,$id_enti,$id_sede,$id_depen); 
        $stmt->execute();            
        print_r($this->mysqli->error);
    }

    public function get_dependencia($id_grupo,$id_sede,$id_depen){
        $query = "SELECT id_grupo, id_sede, id_depen
                    FROM grupo_dependencias_detalle 
                    WHERE id_grupo = ? AND id_enti = ? AND id_sede = ? AND id_depen=?";
        $datos = array();
        $id_enti=$_SESSION['id_enti'];               
        $stmt = $this->mysqli->prepare($query);        
        $stmt->bind_param('iiii', $id_grupo, $id_enti,$id_sede,$id_depen); 
        $resultado = false;
        if ($stmt->execute()){                
            $resultado=true;
            $ultimo = $stmt->get_result();
            if( $ultimo->num_rows > 0) {            
                while ($row = $ultimo->fetch_assoc()) {            
                    $datos[] = array("result"=>$resultado,
                                    "id_grupo"=>$row['id_grupo'],
                                    "id_sede"=>$row['id_sede'], 
                                    "id_depen"=>$row['id_depen']                        
                                );
                }    
            } else {
                $datos[] = array("result"=>false);   
            }
        }else{
             $datos[] = array("result"=>$resultado);
        }                  
    }

    public function addDepen($id_grupo,$id_sede,$id_depen) {
        $query = "INSERT INTO grupo_dependencias_detalle(id_grupo,id_entidad,id_sede,id_depen) VALUES (?,?,?,?)";
        
        $stmt = $this->mysqli->prepare($query);
        $id_enti=$_SESSION['id_enti'];         
        $stmt->bind_param('iiii', $id_grupo,$id_enti,$id_sede,$id_depen);        
        $guardo = $stmt->execute();
        //$guardo = $stmt->get_result(); 
        $resultado=false;
        
        if($guardo){
            $resultado = true;
        }
        $data [] = array("resultado"=>$resultado);  
        return $data;
    }

    public function existeDependencia() {
        $query = "SELECT * FROM grupo_dependencias_detalle(id_entidad,id_sede,id_depen) VALUES (?,?,?)";
        
        $stmt = $this->mysqli->prepare($query);
        $id_enti=$_SESSION['id_enti'];         
        $id_sede=$_SESSION['id_sede'];         
        $id_depen=$_SESSION['id_depen'];         
        $stmt->bind_param('iii',$id_enti,$id_sede,$id_depen);        
        $resultado = $stmt->execute();
        $existe = false;
        if ($resultado){                
            $existe=true;
            $grupo = $stmt->get_result();
            if( $grupo->num_rows > 0) {            
                while ($row = $grupo->fetch_assoc()) {            
                    $datos[] = array("result"=>$existe,
                                    "id_grupo"=>$row['id_grupo'],
                                    "id_sede"=>$row['id_sede'], 
                                    "id_depen"=>$row['id_depen']                        
                                );
                }    
            } else {
                $datos[] = array("result"=>false);   
            }
        }else{
             $datos[] = array("result"=>$existe);
        } 
    }






     // ESTE METODO DEBERIA ESTAR EN VISITA.php
     // ESTE METODO DEBERIA ESTAR EN VISITA.php
     public function get_lista_control_x_grupos($idSede,$idDepen) {
        $datos = array(); 
        $id_enti=$_SESSION['id_enti'];
        $id_sede=$idSede;        
        $id_depen=$idDepen;               
    
        $query = "SELECT * FROM vw_all_visita_control WHERE id_entidad = ? AND id_sede=? AND id_depen=? order by finalizado, id_visita desc, fe_ingreso asc";
        $stmt = $this->mysqli->prepare($query);        
        
        $stmt->bind_param('iii', $id_enti,$id_sede,$id_depen);
        $stmt->execute();
        $visita = $stmt->get_result(); 
        //print_r($this->mysqli->error);      
        //print_r($visita);      
        
        while ($row = $visita->fetch_assoc()) {            
              $datos[] = $row;
        }      
        return $datos;
    }
    // ESTE METODO DEBERIA ESTAR EN VISITA.php
    // ESTE METODO DEBERIA ESTAR EN VISITA.php





}
