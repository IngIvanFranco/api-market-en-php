<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conecta a la base de datos  con usuario, contraseña y nombre de la BD
$servidor = "localhost"; $usuario = "root"; $contrasenia = ""; $nombreBaseDatos = "invercomesadmin";
$conexionBD = new mysqli($servidor, $usuario, $contrasenia, $nombreBaseDatos);


// Actualiza datos pero recepciona datos de nombre, correo y una clave para realizar la actualización


if (isset($_GET["consultarcategorias"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM categoria");
    if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}



if (isset($_GET["productosdescuento"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM products
    WHERE descuento > 0
    AND status = '1'
    ORDER BY RAND() LIMIT 30");
    if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}



if (isset($_GET["consultarsubcategorias"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM sub_categoria_productos");
    if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}



if (isset($_GET["consultarproducto"])){
    
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM products 
    WHERE id =".$_GET['consultarproducto']);
     if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}


if (isset($_GET["consultarproductos"])){
    
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM products WHERE status = '1'  
    AND (products.name LIKE '%".$_GET['consultarproductos']."%' OR products.description LIKE '%".$_GET['consultarproductos']."%')");
     if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}


if(isset($_GET["login"])){
    $data = json_decode(file_get_contents("php://input"));
    $usr=$data->usr;
    $pass=$data->pass;
    $sqllogin = mysqli_query($conexionBD,"SELECT * FROM customers WHERE email= '$usr' AND pass = '$pass'");
    if(mysqli_num_rows($sqllogin) == 1){
        $customer = mysqli_fetch_all($sqllogin,MYSQLI_ASSOC);
        echo json_encode($customer);
        
    }else {
        echo json_encode(["success"=>0]);

    
}
}


if (isset($_GET["consultarcustomers"])){
    
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM  customers 
    WHERE customers.id = ".$_GET['consultarcustomers']."");
     if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}



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


if (isset($_GET["consultarcitys"])){
    
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT departamentos.departamento,municipios.municipio FROM departamentos,municipios WHERE municipios.departamento_id=departamentos.id_departamento;");
     if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados);
        exit();
    }
    else{  echo json_encode(["success"=>0]); }
}



if(isset($_GET["usr"])&&isset($_GET["valor"])){
    $data = json_decode(file_get_contents("php://input"));
    $ciudad=$data->ciudad;
    $direccion=$data->direccion;
    $metodopago=$data->metodopago;
   
    $DateAndTime = date('Y-m-d h:i:s a', time()); 
    
    if(($ciudad!="")&&($direccion!="")&&($metodopago!="")){

    $sqlregister = mysqli_query($conexionBD,"INSERT INTO orders
    (customer_id,total_price,metodo_pago,created,modified,ciudad,direccion) VALUES 
    ('".$_GET["usr"]."','".$_GET["valor"]."','$metodopago','$DateAndTime','$DateAndTime','$ciudad','$direccion') "); 
        
        $orderID = $conexionBD->insert_id;

        echo json_encode(["orden"=>$orderID]);
    }
    exit();
}



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




if(isset($_GET["datosorder"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM orders WHERE orders.id = ".$_GET["datosorder"]);
    if(mysqli_num_rows($sqlEmpleaados) > 0){
       $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
       echo json_encode($empleaados);
       exit();
   }
   else{  echo json_encode(["success"=>0]); }
}




if(isset($_GET["listarproductos"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM products WHERE status = '1'  
    AND id_categoriaproducts = '$_GET[listarproductos]'");
     if(mysqli_num_rows($sqlEmpleaados) > 0){
        $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
        echo json_encode($empleaados);
        
    }

    else{  echo json_encode(["success"=>0]); }
}





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
    $mail->Password   = '1nv3rc0m3s';                               //SMTP password
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

if(isset($_GET["ordenescustomer"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT * FROM orders WHERE orders.customer_id = ".$_GET["ordenescustomer"]);
    if(mysqli_num_rows($sqlEmpleaados) > 0){
       $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
       echo json_encode($empleaados);
       exit();
   }
   else{  echo json_encode(["success"=>0]); }
}


if(isset($_GET["detalleorden"])){
    $sqlEmpleaados = mysqli_query($conexionBD,"SELECT order_items.product_id, order_items.descuento, order_items.quantity, order_items.precio, order_items.talla, products.name FROM order_items, products WHERE order_items.order_id = ".$_GET["detalleorden"]."
    AND products.id = order_items.product_id");
    if(mysqli_num_rows($sqlEmpleaados) > 0){
       $empleaados = mysqli_fetch_all($sqlEmpleaados,MYSQLI_ASSOC);
       echo json_encode($empleaados);
       exit();
   }
   else{  echo json_encode(["success"=>0]); }
}




