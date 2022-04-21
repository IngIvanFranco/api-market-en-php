<?php
///////////////////////////////////////////////////////////////////
//WEB SERVICE PARA LA MARKETPLACE DE INVERCOMES SAS////////////////
//DESARROLLADO EN PHP V7///////////////////////////////////////////
//INGENIERO IVAN DARIO FRANCO NOVOA////////////////////////////////
//INGENIERO.IVANFR@GMAIL.COM///////////////////////////////////////
//3028416742///////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
date_default_timezone_set('America/Bogota');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conecta a la base de datos  con usuario, contraseña y nombre de la BD
$servidor = "localhost"; $usuario = "root"; $contrasenia = ""; $nombreBaseDatos = "invercomesadmin";
$conexionBD = new mysqli($servidor, $usuario, $contrasenia, $nombreBaseDatos);

mysqli_set_charset($conexionBD,"utf8");


//////////////////////////////////////////////////////////////////////////////
//Consulta todas las categorias por las cuales estan divididos los productos//
//////////////////////////////////////////////////////////////////////////////
if (isset($_GET["consultarcategorias"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM categoria");

    if(mysqli_num_rows($sqlEmpleaados) > 0){
    	
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
       echo json_encode($empleaados,JSON_INVALID_UTF8_SUBSTITUTE);
        die();
    }
    else{  echo json_encode(["success"=>0]); }
}



//////////////////////////////////////////////////////////////////////////////
//Consulta todos los productos que cuentan con algun descuento////////////////
//////////////////////////////////////////////////////////////////////////////
if (isset($_GET["productosdescuento"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM products
    WHERE descuento > 0
    AND status = '1'
    ORDER BY RAND() LIMIT 30");
    if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados,JSON_INVALID_UTF8_SUBSTITUTE);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}



//////////////////////////////////////////////////////////////////////////////////
//COnsulta todas las subcategorias por las cuales estan divididos los productos//
/////////////////////////////////////////////////////////////////////////////////
if (isset($_GET["consultarsubcategorias"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM sub_categoria_productos");
    if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados,JSON_INVALID_UTF8_SUBSTITUTE);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}



//////////////////////////////////////////////////////////////////////////////
//Consulta un producto especifico recibiendo por get el id del producto///////
//////////////////////////////////////////////////////////////////////////////
if (isset($_GET["consultarproducto"])){
    
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM products 
    WHERE id =".$_GET['consultarproducto']);
     if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados,JSON_INVALID_UTF8_SUBSTITUTE);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//COnsulta todos los productos donde su nombre y/o descripcion contengan la palabra de busqueda que se envia por get//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (isset($_GET["consultarproductos"])){
    
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM products WHERE status = '1'  
    AND (products.name LIKE '%".$_GET['consultarproductos']."%' OR products.description LIKE '%".$_GET['consultarproductos']."%')");
     if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados,JSON_INVALID_UTF8_SUBSTITUTE);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}


/////////////////////////////////////////////////////////////////////////////////////
//consulta si el usr que esta intentando iniciar sesion existe y devuelve sus datos//
////////////////////////////////////////////////////////////////////////////////////

if(isset($_GET["login"])){
    $data = json_decode(file_get_contents("php://input"));
    $usr=$data->usr;
    $pass=$data->pass;
    $sqllogin = mysqli_query($conexionBD,"SELECT * FROM customers WHERE email= '$usr' AND pass = '$pass'");
    if(mysqli_num_rows($sqllogin) == 1){
        $customer = mysqli_fetch_all($sqllogin,MYSQLI_ASSOC);
        echo json_encode($customer,JSON_INVALID_UTF8_SUBSTITUTE);
        
    }else {
        echo json_encode(["success"=>0]);

    
}
}
/////////////////////////////////////////////////////////////////////////////////////
//consulta un usr especifico recibiendo su id por metodo get//
////////////////////////////////////////////////////////////////////////////////////


if (isset($_GET["consultarcustomers"])){
    
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM  customers 
    WHERE customers.id = ".$_GET['consultarcustomers']."");
     if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados,JSON_INVALID_UTF8_SUBSTITUTE);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}

//////////////////////////////////////////////////////////////////////////////////
//Registra un nuevo cliente recibiendo un archivo json con toda la informacion //
/////////////////////////////////////////////////////////////////////////////////


if(isset($_GET["register"])){
    $data = json_decode(file_get_contents("php://input"));
    $tipoid=$data->tipoiden;
    $identificacion=$data->identificacion;
    $nombre=$data->nombre;
    $direccion=$data->direccion;
    $ciudad=$data->ciudad;
    $celular=$data->celular;
    $postal=$data->postal;
    $email=$data->email;
    $pass=$data->pass;
    $DateAndTime = date('Y-m-d h:i:s a', time()); 
    
    if(($nombre!="")&&($email!="")&&($identificacion!="")&&($pass!="")){

    $sqlregister = mysqli_query($conexionBD,"INSERT INTO customers(Tipo_identificacion_id, identificacion_cliente, name, email, phone, address, city, codigo_postal, created, modified, pass) VALUES ('$tipoid','$identificacion','$nombre','$email','$celular','$direccion','$ciudad','$postal','$DateAndTime','$DateAndTime','$pass') "); 
        
        echo json_encode(["success"=>1]);
    }
    exit();
}

////////////////////////////////////////////////////////////////////
//consulta todos los departamentos con sus respectivos municipios//
///////////////////////////////////////////////////////////////////

if (isset($_GET["consultarcitys"])){
    
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT departamentos.departamento,municipios.municipio FROM departamentos,municipios WHERE municipios.departamento_id=departamentos.id_departamento;");
     if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados,JSON_INVALID_UTF8_SUBSTITUTE);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}

/////////////////////////////////////////////////////////////////////////////
//registra una nueva orden y devuelve su id recibiendo datos por post y get//
/////////////////////////////////////////////////////////////////////////////


if(isset($_GET["usr"])&&isset($_GET["valor"])){
    $data = json_decode(file_get_contents("php://input"));
    $ciudad=$data->ciudad;
    $direccion=$data->direccion;
    $metodopago=$data->metodopago;
    $puntos=$data->puntos;
   
    $DateAndTime = date('Y-m-d h:i:s a', time()); 
    
    if(($ciudad!="")&&($direccion!="")&&($metodopago!="")){

    $sqlregister = mysqli_query($conexionBD,"INSERT INTO orders
    (customer_id,total_price,metodo_pago,created,modified,ciudad,direccion,puntos) VALUES 
    ('".$_GET["usr"]."','".$_GET["valor"]."','$metodopago','$DateAndTime','$DateAndTime','$ciudad','$direccion','$puntos') "); 
        
        $orderID = $conexionBD->insert_id;

        echo json_encode(["orden"=>$orderID]);
    }
    exit();
}

/////////////////////////////////////////////////////////////////////////////////////////
//recibe por get el id de la orden y registra los productos correspondientes a la misma//
/////////////////////////////////////////////////////////////////////////////////////////


if(isset($_GET["orderid"])){
    $data = json_decode(file_get_contents("php://input"));
    
  foreach ($data as $key ) {
       
     
    $sqlregister =  mysqli_multi_query($conexionBD,"INSERT INTO order_items
    (order_id, product_id, quantity, precio, descuento, talla) VALUES 
    ('".$_GET["orderid"]."','".$key->id."','".$key->qty."','".$key->price."','".$key->des."','".$key->talla."')");


}
        

        echo json_encode(["success"=>1]);
    
    exit();
}


//////////////////////////////////////////////////////////////////////////
//recibe por get el id de la orden y devuelve su informacion sin detalle//
//////////////////////////////////////////////////////////////////////////

if(isset($_GET["datosorder"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM orders WHERE orders.id = ".$_GET["datosorder"]);
    if(mysqli_num_rows($sqlEmpleaados) > 0){
       $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
       echo json_encode($empleaados);
       exit();
   }
   else{  echo json_encode(["success"=>0]); }
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////
//recibe el id de la categoria de los productos y devuelve todos los productos relacionados a esa categoria//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////


if(isset($_GET["listarproductos"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM products WHERE status = '1'  
    AND id_categoriaproducts = '$_GET[listarproductos]'");
     if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados,JSON_INVALID_UTF8_SUBSTITUTE);
        
    }

    else{  echo json_encode(["success"=>0]); }
}



//////////////////////////////////////////////////////////////////////
//recibe por post los datos email asunto y mensaje y envia el correo//
//////////////////////////////////////////////////////////////////////


if(isset($_GET["correo"])){
   require './phpmailer/mail/src/Exception.php';
require './phpmailer/mail/src/PHPMailer.php';
require './phpmailer/mail/src/SMTP.php';

    $data = json_decode(file_get_contents("php://input"));
    $asunto=$data->asunto;
    $email=$data->email;
    $mensaje=$data->mensaje;


$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 0 ;                      //Enable verbose debug 
    $mail->isSMTP();                                //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'invercomes.analisis@gmail.com';                     //SMTP username
    $mail->Password   = 'nnxrcihvrpqwcrhl';                               //SMTP password
   $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption 
    $mail->Port       = 465;                                    //465TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('comercial@invercomes.com.co', 'Marketpkace Invercomes');
    $mail->addAddress($email, 'Cliente Feliz');     //Add a recipient
           //Name is optional
    //$mail->addReplyTo( 'contabilidad@invercomes.com.co', 'Contabilidad Invercomes');
    //$mail->addReplyTo( 'direcciontics@invercomes.com.co', 'logistica Invercomes');
    //$mail->addCC('direcciontics@invercomes.com.co');
    //$mail->addCC('contabilidad@invercomes.com.co');
    //$mail->addBCC('bcc@example.com');

    //Attachments
  //  $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
  // $mail->addAttachment('../img/ganamenu.png', 'iconoganagana.png');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject =  $asunto;
    $mail->Body    = $mensaje;
   // $mail->AltBody = 'hola prueba alterna';

    $mail->send();
      echo json_encode(["success"=>1]); 

} catch (Exception $e) {
    echo json_encode([" Error:"=>$mail->ErrorInfo]);
}

}

/////////////////////////////////////////////////////////////////////////////////////
//recibe el id de un cliente por get y devuelve todas las ordenes relacionadas a el//
/////////////////////////////////////////////////////////////////////////////////////


if(isset($_GET["ordenescustomer"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM orders WHERE orders.status != 4 AND orders.customer_id = ".$_GET["ordenescustomer"]);
    if(mysqli_num_rows($sqlEmpleaados) > 0){
       $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
       echo json_encode($empleaados,JSON_INVALID_UTF8_SUBSTITUTE);
       exit();
   }
   else{  echo json_encode(["success"=>0]); }
}

///////////////////////////////////////////////////////////////////////////////
//recibe por get el id de la orden y devuelve todos los detalles de la orden //
///////////////////////////////////////////////////////////////////////////////

if(isset($_GET["detalleorden"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT order_items.product_id, order_items.descuento, order_items.quantity, order_items.precio, order_items.talla, products.name FROM order_items, products WHERE order_items.order_id = ".$_GET["detalleorden"]."
    AND products.id = order_items.product_id");
    if(mysqli_num_rows($sqlEmpleaados) > 0){
       $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
       echo json_encode($empleaados,JSON_INVALID_UTF8_SUBSTITUTE);
       exit();
   }
   else{  echo json_encode(["success"=>0]); }
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//recibe por post los mismos datos de el correo electronico, genera un codigo alfanumerico de 8 caracteres y lo envia al usuario para validar los puntos que descontara//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



if(isset($_GET["Validacion"])){
    require './phpmailer/mail/src/Exception.php';
 require './phpmailer/mail/src/PHPMailer.php';
 require './phpmailer/mail/src/SMTP.php';
 
     $data = json_decode(file_get_contents("php://input"));
     $asunto=$data->asunto;
     $email=$data->email;
     $mensaje=$data->mensaje;
 
 
     $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
 
     function generate_string($input, $strength = 16) {
         $input_length = strlen($input);
         $random_string = '';
         for($i = 0; $i < $strength; $i++) {
             $random_character = $input[mt_rand(0, $input_length - 1)];
             $random_string .= $random_character;
         }
      
         return $random_string;
     }
      
     // Output: iNCHNGzByPjhApvn7XBD
     $codigovale= generate_string($permitted_chars, 8);
      


 $mail = new PHPMailer(true);

 
 try {
     //Server settings
     $mail->SMTPDebug = 0 ;                      //Enable verbose debug 
     $mail->isSMTP();                                //Send using SMTP
     $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
     $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
     $mail->Username   = 'invercomes.analisis@gmail.com';                     //SMTP username
     $mail->Password   = 'nnxrcihvrpqwcrhl';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption 
     $mail->Port       = 465;                                    //465TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
 
     //Recipients nnxrcihvrpqwcrhl
     $mail->setFrom('comercial@invercomes.com.co', 'Marketpkace Invercomes');
     $mail->addAddress($email, 'Cliente Feliz');     //Add a recipient
       $mail->isHTML(true);                                  //Set email format to HTML
     $mail->Subject =  $asunto;
     $mail->Body    = "Codigo de verificacion <br> $codigovale";
  
 
     $mail->send();

        echo json_encode(["Codigo"=> $codigovale]); 
 
 } catch (Exception $e) {
     echo json_encode([" Error:"=>$mail->ErrorInfo]);
 }
 
 }

 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//recibe por get el id de una orden y se envia el archivo que luego se enviara al servidor de ggpoints cuando hay una transaccion//
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 if(isset($_GET["ggpoints"])){
    $sqlorden = mysqli_query($conexionBD,"SELECT orders.id, customers.identificacion_cliente, customers.name, orders.created, orders.total_price, orders.puntos
    FROM orders,customers WHERE orders.id = ".$_GET['ggpoints']."
    AND orders.customer_id = customers.id ");
    $sqldetalleorden = mysqli_query($conexionBD,"SELECT products.name, order_items.quantity, order_items.precio, order_items.descuento FROM order_items,products WHERE order_items.order_id = ".$_GET['ggpoints']."
    AND products.id = order_items.product_id");
    if(mysqli_num_rows($sqlorden) > 0){
       $orden = mysqli_fetch_all($sqlorden,MYSQLI_ASSOC);
       $detalleorden = mysqli_fetch_all($sqldetalleorden,MYSQLI_ASSOC);
       echo json_encode(["SUCCESS"=>1,
                           "DATOS_ORDEN"=>$orden,
                            "DETALLE_ORDEN"=>$detalleorden],JSON_INVALID_UTF8_SUBSTITUTE);
       exit();
   }
   else{  echo json_encode(["SUCCESS"=>0]); }
}


//////////////////////////////////////////////////////////////////////////////////////////////////////
//recibe por get el id de un cliente y por post toda su informacion personal la cual sera modificada//
//////////////////////////////////////////////////////////////////////////////////////////////////////


if (isset($_GET["editcustomer"])){
     
    $data = json_decode(file_get_contents("php://input"));

    $id=(isset($data->id))?$data->id:$_GET["editcustomer"];
    $nombre=$data->nombre;
    $correo=$data->email;
    $direccion=$data->direccion;
    $celular=$data->celular;
    $ciudad=$data->ciudad;
    $identificacion=$data->identificacion;
    $postal=$data->postal;
    $DateAndTime = date('Y-m-d h:i:s a', time()); 

    
    $sqlEmpleaados = mysqli_query($conexionBD,"UPDATE  customers 
    SET identificacion_cliente ='$identificacion', name = '$nombre', email='$correo', phone = '$celular', address = '$direccion', city = '$ciudad', codigo_postal = '$postal', modified = '$DateAndTime'
    WHERE customers.id = $id");
     if($sqlEmpleaados){
        
        echo json_encode(["success"=>1]);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//recibe por get el id de una orden y la pone como fallida tras una contestacion negativa del servidor ggpoints//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////


if (isset($_GET["revertirorden"])){
    $DateAndTime = date('Y-m-d h:i:s a', time()); 

    $sqlEmpleaados = mysqli_query($conexionBD,"UPDATE  orders 
    SET status = '4', modified = '$DateAndTime'
    WHERE orders.id =".$_GET['revertirorden']."");
     if($sqlEmpleaados){
        
        echo json_encode(["success"=>1]);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}


////////////////////////////////////////////////////////////////////////////////////////
//recibe por post 2 variables y las compara para devolver la informacion de un cliente//
////////////////////////////////////////////////////////////////////////////////////////


if (isset($_GET["respass"])){
     
    $data = json_decode(file_get_contents("php://input"));
    $identificacion=$data->identificacion;
    $telefono=$data->telefono;
   
    $sql = mysqli_query($conexionBD,"SELECT * FROM `customers` WHERE phone = $telefono AND identificacion_cliente = $identificacion ");
     if(mysqli_num_rows($sql) == 1){
        $res = mysqli_fetch_all($sql,MYSQLI_ASSOC);


        echo json_encode($res);
        exit();
    } if (mysqli_num_rows($sql) > 1) {
        echo json_encode(["success"=>1]); 
    }
    else{  echo json_encode(["success"=>0]); }
}


///////////////////////////////////////////////////////////////////////////////////////////
//recibe por post datos de un pago realizado en un punto gana gana y lo registra en la bd//
///////////////////////////////////////////////////////////////////////////////////////////


if (isset($_GET["pagosgg"])){
     
    $data = json_decode(file_get_contents("php://input"));
    $order=$data->order;
    $valor=$data->valor;
    $lugar=$data->lugar;
    $DateAndTime = date('Y-m-d h:i:s a', time()); 
   
    $sql = mysqli_query($conexionBD,"UPDATE  orders 
    SET status = '2', modified = '$DateAndTime'
    WHERE orders.id =".$order."");
     if($sql){
       $sql2 = mysqli_query($conexionBD,"INSERT INTO pagos_gg
       (order_id, valor, fecha, lugar_pago) VALUES 
       ('".$order."','".$valor."','".$DateAndTime."','".$lugar."')");
        echo json_encode(["success"=>1]);
        exit();
    } 
    else{  echo json_encode(["success"=>0]); }
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//recibe por get el id de una orden y devuelve la informacion necesaria para realizar el pago en un punto gana gana//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


if (isset($_GET["consulta_pagosgg"])){
     
    
    $sql = mysqli_query($conexionBD,"SELECT * FROM `orders`, customers WHERE orders.id = $_GET[consulta_pagosgg]
    AND customers.id = orders.customer_id
    AND orders.status = 1 ");
     if(mysqli_num_rows($sql) == 1){
        $res = mysqli_fetch_all($sql,MYSQLI_ASSOC);
        echo json_encode(
            [
             "Numero_Orden"=> $_GET['consulta_pagosgg'],
             "Valor_Pago"=> $res[0]['total_price'] - $res[0]['puntos'],
             "Identificacion_Cliente"=>$res[0]['identificacion_cliente'],
             "Nombre_Cliente"=>$res[0]['name'],
             "Telefono_Cliente"=>$res[0]['phone'],
             "Email_Cliente"=>$res[0]['email']]
            );
        exit();
    } else{  echo json_encode(["success"=>0]); }
}

