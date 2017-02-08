<?php

/*CRUD USER*//////////////////////////////////////////////////////////
/*Create*/
//Agrega un usuario
$app->post('/user/create','ControllerUser@Create');
//Agrega numero telefonico
$app->post('/user/add_phone','ControllerUser@AddPhone');

/*Read*/
//Muestra todos los usuarios
$app->get('/user/read','ControllerUser@Read');
//Muestra todos los telefono
$app->get('/user/read_phone','ControllerUser@ReadPhone');

$app->post('/user/get_user','ControllerUser@GetUserPhone');//////////////en proceso
//muestra al usuario con la ciudad,state and country
$app->get('/user/getlocation','ControllerUser@GetUserLocation');

/*Update*/
//Actualiza name
$app->put('/user/update_name','ControllerUser@UpdateName');
//Actualiza email
$app->put('/user/update_email','ControllerUser@UpdateEmail');
//Actualiza password
$app->put('/user/update_password','ControllerUser@UpdatePassword');
//Actualiza thumbnail
$app->put('/user/update_thumbnail','ControllerUser@UpdateThumbnail');
//Actualiza secondname
$app->put('/user/update_secondname','ControllerUser@UpdateSecondname');
//Actualiza lastname
$app->put('/user/update_lastname','ControllerUser@UpdateLastname');
//Actualiza address
$app->put('/user/update_address','ControllerUser@UpdateAddress');
//Actualiza city
$app->put('/user/update_city','ControllerUser@UpdateCity');
//Actualiza phone
$app->put('/user/update_phone','ControllerUser@UpdatePhone');

/*Delete*/
//Elimina usuario
$app->delete('/user/delete','ControllerUser@Delete');
//Elimina telefono
$app->delete('/user/delete_phone','ControllerUser@DeletePhone');

/*Verificar User*/
//Verifica el password y email si estan agregado
$app->post('/user/verification','ControllerUser@verificationLogin');


/*CRUD SERVICE*//////////////////////////////////////////////////////////////
/*Create*/
//Agrega un service
$app->post('/service/create','ControllerService@CreateService');
//Agrega  type
$app->post('/service/add_type','ControllerService@AddTypeService');
/*Read*/
//Muestra todos los service
$app->get('/service/read','ControllerService@ReadService');
//Muestra al usuario con la category,accommodation,type 
$app->get('/service/get_service','ControllerService@GetUserService');
//Muestra la tabla Type-Service
$app->get('/service/read_type','ControllerService@ReadTypeService');
/*Update*/
//Actualiza un service
$app->put('/service/update','ControllerService@UpdateService');
/*Delete*/
//Elimina un service
$app->delete('/service/delete','ControllerService@DeleteService');

/*CRUD Paypal y Card*//////////////////////////////////////////////////
/*Create*/
$app->post('/user/card/add_card','ControllerCardPaypal@AddCard');
$app->post('/user/paypal/add_paypal','ControllerCardPaypal@AddPaypal');
/*Read*/
$app->get('/user/card/read_card','ControllerCardPaypal@ReadCard');
$app->get('/user/paypal/read_paypal','ControllerCardPaypal@ReadPaypal');
/*Update*/
$app->put('/user/paypal/update_paypal','ControllerCardPaypal@UpdatePaypal');
$app->put('/user/card/update_number','ControllerCardPaypal@UpdateNumber');
$app->put('/user/card/update_expmonth','ControllerCardPaypal@UpdateExpMonth');
$app->put('/user/card/update_expyear','ControllerCardPaypal@UpdateExpYear');
$app->put('/user/card/update_cvc','ControllerCardPaypal@UpdateCvc');
$app->put('/user/card/update_name','ControllerCardPaypal@UpdateName');
/*Delete*/
$app->delete('/user/paypal/delete_paypal','ControllerCardPaypal@DeletePaypal');
$app->delete('/user/card/delete_card','ControllerCardPaypal@Deletecard');

/*CRUD imagen*//////////////////////////////////////////////////////////////
/*Create*/
$app->post('/service/add_imagen','ControllerCardPaypal@AddImagen');
/*Update*/
$app->put('/service/update_imagen','ControllerCardPaypal@UpdateImagen');
/*Delete*/
$app->delete('/service/delete_card','ControllerService@Deletecard');
/*Read*/
$app->get('/service/read_imagen','ControllerCardPaypal@ReadImagen');

/*Crud Rent*/

 
/* CRUD MESSAGE *////////////////////////////////////////////////////////////
$app->get('/user/message','ControllerMessageInbox@ReadMessage');

$app->get('/user/inbox','ControllerMessageInbox@ReadInbox');
//En proceso
//$app->post('/user/create/message','ControllerMessageInbox@CreateMesage');