<?php
require_once "config.php";
header('Content-Type: text/html; charset=utf-8');

if($_POST){
    $action=$_REQUEST['action'];
    switch($action){
        case "registrar":
            $valido['success']=array('success'=>false,'mensaje'=>"");            
            $a=$_POST['email'];
            $b=md5($_POST['password']);
            $c=$_POST['nombre'];
            $d=$_POST['apellido'];
            $e=$_POST['usuario'];
            $check="SELECT * FROM cecyusuario WHERE usuario='$a'";
            $res=$cx->query($check);
            if($res->num_rows==0){
                $sql="INSERT INTO cecyusuario VALUES (null,'$a','$b','$c','$d','$e',null)";
//otra forma de ingrsar datos es INSERT INTO cecyusuario (usuario,password,nombre,apellido,us)VALUES ('$a','$b','$c','$d','$e',null)
                if($cx->query($sql)){
                    $valido['success']=true;
                    $valido['mensaje']="SE REGISTRO CORRECTAMENTE";
                }else {
                    $valido['success']=false;
                    $valido['mensaje']="ERROR AL REGISTRAR";
                }
            }else{
                $valido['success']=false;
                $valido['mensaje']="USUARIO NO DISPONIBLE";
            }
            echo json_encode($valido);
            break;
        case "login":
            $valido['success']=array('success'=>false,'mensaje'=>"");            
            $a=$_POST['email'];
            $b=md5($_POST['password']);
            $check="SELECT * FROM cecyusuario WHERE usuario='$a' AND password ='$b';";
            $res=$cx->query($check);
            if($res->num_rows>0){
                $valido['success']=true;
                $valido['mensaje']="SE INICIO CORRECTAMENTE";
            }else {
                $valido['success']=false;
                $valido['mensaje']="USUARIO Y/O PASSWORD INCORRECTO";
            }           
            echo json_encode($valido);

            break;
        case "select":
            header('Content-Type: text/html; charset=utf-8');
                $valido['success']=array('success'=>false,'mensaje'=>"","foto"=>"");            
                $a=$_POST['usuario'];
                $check="SELECT * FROM cecyusuario WHERE usuario='$a';";
                $res=$cx->query($check);
                if($res->num_rows>0){
                    $row=$res->fetch_array();
                    $valido['success']=true;
                    $valido['mensaje']=$row[3];
                    $valido['foto']=$row[6];
                }else {
                    $valido['success']=false;
                    $valido['mensaje']="USUARIO Y/O PASSWORD INCORRECTO";
                }           
                echo json_encode($valido);
    
                break;
                case "perfil":
                    header('Content-Type: text/html; charset=utf-8');
                        $valido['success']=array('success'=>false,'mensaje'=>"",'usuario'=>"",'password'=>"",'nombre'=>"",'apellido'=>"",'us'=>"",'foto'=>"");            
                        $a=$_POST['usuario'];
                        $check="SELECT * FROM cecyusuario WHERE usuario='$a';";
                        $res=$cx->query($check);
                        if($res->num_rows>0){
                            $row=$res->fetch_array();
                            $valido['success']=true;
                            $valido['usuario']=$row[1];
                            $valido['password']=$row[2];
                            $valido['nombre']=$row[3];
                            $valido['apellido']=$row[4];
                            $valido['us']=$row[5];
                            $valido['foto']=$row[6];


                        }else {
                            $valido['success']=false;
                            $valido['mensaje']="ALGO SALIO MAL";
                        }           
                        echo json_encode($valido);
            
                        break; 
                        case "saveperfil":
                            header('Content-Type: text/html; charset=utf-8');
                                $valido['success']=array('success'=>false,'mensaje'=>"");            
                                $a=$_POST['nombre'];
                                $b=$_POST['apellido'];
                                $d=$_POST['us'];
                                $c=$_POST['usuario'];
                                $fileName=$_FILES['foto']['name'];
                                $fileTmpName=$_FILES['foto']['tmp_name'];
                                $uploadDirectory='assets/img_profile/';

                                //CREAR EL DIRECTORIO SI NO EXCISTE
                                if (!is_dir($uploadDirectory)) {
                                    mkdir($uploadDirectory,0755,true);
                                }

                                //RUTA DE DESTINO
                                $filePath=$uploadDirectory.basename($fileName);

                                //Mover el archivo a la ruta de destino
                                if (move_uploaded_file($fileTmpName,$filePath)) {
                                    //PREPARAR Y EJECUTAR LA CONSULTA
                                   
                                $check="UPDATE cecyusuario SET nombre='$a',apellido='$b',us='$d',foto='$filePath'  WHERE usuario='$c'";
                                $res=$cx->query($check);
                                if($res->num_rows>0){
                                    $row=$res->fetch_array();
                                    $valido['success']="SE GUARDO CORRECTAMENTE";
        
        
                                }else {
                                    $valido['success']=false;
                                    $valido['mensaje']="ALGO SALIO MAL";
                                }
                                }     else {
                                    $valido['mensaje']="ALGO SALIO MAL 2.0";
                                                                }      
                                echo json_encode($valido);
                    
                                break;                        
    }
}else{
    $valido['success']=false;
    $valido['mensaje']="ERROR NO SE RECIBIO NADA";
}
?>