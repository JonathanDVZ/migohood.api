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
/////////////////////////////////////////////////////////////////////////////

/*CRUD SERVICE*//////////////////////////////////////////////////////////////
/*Create*/
//Agrega un service
$app->post('/service/create','ControllerService@CreateService');
//Agrega  type
$app->post('/service/add_type','ControllerService@AddTypeService');
//Agrega en la tabla service-calendar
$app->post('/service/add_servicecalendar','ControllerService@AddServiceCalendar');
//Agrega en la service-amenite
$app->post('/service/add_serviceamenite','ControllerService@AddServiceAmentines');
/*Read*/
//Muestra todos los service
$app->get('/service/read','ControllerService@ReadService');
//Muestra al usuario con la category,accommodation,type 
$app->get('/service/get_service','ControllerService@GetUserService');
//Muestra la tabla Type-Service
$app->get('/service/read_type','ControllerService@ReadTypeService');
//Muestra toda la tabla Calendar-Service
$app->get('/service/read_readcalendar','ControllerService@ReadCalendarService');
//Muestra toda la tabla Amenite-Service
$app->get('/service/read_readameniteservice','ControllerService@ReadServiceAmenite');
/*Update*/
//Actualiza un service
$app->put('/service/update','ControllerService@UpdateService');
/*Delete*/
//Elimina un service
$app->delete('/service/delete','ControllerService@DeleteService');
//Elimina un type-service
$app->delete('/service/delete_typeservice','ControllerService@DeleteTypeService');
//Elimina un service-amenites
$app->delete('/service/delete_ameniteservice','ControllerService@DeleteServiceAmenite');
//Elimina un service-calendar
$app->delete('/service/delete_calendar','ControllerService@DeleteServiceCalendar');
/*CRUD Paypal y Card*//////////////////////////////////////////////////
/*Create*/
//Agrega Card
$app->post('/user/card/add_card','ControllerCardPaypal@AddCard');
//Agrega Paypal
$app->post('/user/paypal/add_paypal','ControllerCardPaypal@AddPaypal');
/*Read*/
//Muestra Card
$app->get('/user/card/read_card','ControllerCardPaypal@ReadCard');
//Muestra Paypal
$app->get('/user/paypal/read_paypal','ControllerCardPaypal@ReadPaypal');
/*Update*/
//Actualiza account de paypal
$app->put('/user/paypal/update_paypal','ControllerCardPaypal@UpdatePaypal');
//Actualiza number de Card
$app->put('/user/card/update_number','ControllerCardPaypal@UpdateNumber');
//Actualiza month exp de Card
$app->put('/user/card/update_expmonth','ControllerCardPaypal@UpdateExpMonth');
//Actualiza year exp
$app->put('/user/card/update_expyear','ControllerCardPaypal@UpdateExpYear');
//Actualiza cvc de Card
$app->put('/user/card/update_cvc','ControllerCardPaypal@UpdateCvc');
//Actualiza name de Card
$app->put('/user/card/update_name','ControllerCardPaypal@UpdateName');
/*Delete*/
//Elimina Paypal
$app->delete('/user/paypal/delete_paypal','ControllerCardPaypal@DeletePaypal');
//Elimina Card
$app->delete('/user/card/delete_card','ControllerCardPaypal@Deletecard');
///////////////////////////////////////////////////////////////////////////////////

/*CRUD Imagen*/////////////////////////////////////////////////////////////////////
/*Create*/
$app->post('/service/add_imagen','ControllerCardPaypal@AddImagen');
/*Update*/
$app->put('/service/update_imagen','ControllerCardPaypal@UpdateImagen');
/*Delete*/
$app->delete('/service/delete_card','ControllerService@Deletecard');
/*Read*/
$app->get('/service/read_imagen','ControllerCardPaypal@ReadImagen');
////////////////////////////////////////////////////////////////////////////////////


/*CRUD RENT///////////////////////////////////////////////////////////////////////*/
/*CREATE*/
//Agrega una renta
$app->post('/service/add_rent','ControllerRent@AddRent');
//Renta el mismo servicio que esta en renta pero con otra fecha
$app->post('/service/rent_serv','ControllerRent@VerificationRent');
/*READ*/
//Muestras las rentas
$app->get('/service/read_rent','ControllerRent@ReadRent');
//Consulta en rent el usuario que solicita el servcio y la fecha que solicito
$app->get('/service/get_rent','ControllerRent@GetUserServiceRent');
/*DELETE*/
//Elimina una renta
$app->delete('/service/delete_rent','ControllerRent@DeleteRent');




/* CRUD MESSAGE *////////////////////////////////////////////////////////////En proceso
$app->get('/user/message','ControllerMessageInbox@ReadMessage');

$app->get('/user/inbox','ControllerMessageInbox@ReadInbox');
//En proceso
//$app->post('/user/create/message','ControllerMessageInbox@CreateMesage');