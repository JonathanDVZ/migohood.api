<?php

$app->get('/', function () use ($app){
    return 'Migohood API made with '.$app->version();
});

$app->get('/home', function () use ($app){
    return 'Migohood API made with '.$app->version();
});

/*CRUD USER*//////////////////////////////////////////////////////////
/*Create*/
//Agrega un usuario
$app->post('/user/create','ControllerUser@Create');
//Agrega numero telefonico
$app->post('/user/add-phone','ControllerUser@AddPhone');
//muestra un usuario en especifico
$app->post('/user/getuser','ControllerUser@GetUser');
//muestra un city de un user
$app->post('/user/getcity','ControllerUser@GetCity');
//muestra un state de un user
$app->post('/user/getstate','ControllerUser@GetState');
//muestra un country de un user
$app->post('/user/getcountry','ControllerUser@GetCountry');
//Muestra los phone de un user
$app->post('/user/get-phone','ControllerUser@GetPhone');
/*Read*/
//Muestra todos los usuarios
$app->get('/user/read','ControllerUser@Read');
/*Update*/
//Actualiza name
$app->put('/user/update-name','ControllerUser@UpdateName');
//Actualiza email
$app->put('/user/update-email','ControllerUser@UpdateEmail');
//Actualiza password
$app->put('/user/update-password','ControllerUser@UpdatePassword');
//Actualiza thumbnail
$app->put('/user/update-thumbnail','ControllerUser@UpdateThumbnail');
//Actualiza secondname
$app->put('/user/update-secondname','ControllerUser@UpdateSecondname');
//Actualiza lastname
$app->put('/user/update-lastname','ControllerUser@UpdateLastname');
//Actualiza address
$app->put('/user/update-address','ControllerUser@UpdateAddress');
//Actualiza city
$app->put('/user/update-city','ControllerUser@UpdateCity');
//Actualiza phone
$app->put('/user/update-phone','ControllerUser@UpdatePhone');
/*Delete*/
//Elimina un usuario
$app->delete('/user/delete','ControllerUser@Delete');
//Elimina un telefono de un user en especifico
$app->delete('/user/delete-phone','ControllerUser@DeletePhone');
/*Verificar User*/
//Verifica el password y email si estan agregado
$app->post('/user/verification','ControllerUser@verificationLogin');
/////////////////////////////////////////////////////////////////////////////

/*CRUD SERVICE*//////////////////////////////////////////////////////////////
/*Create*/
//Agrega un service
$app->post('/service/create','ControllerService@CreateService');
//Agrega  type
$app->post('/service/add-type','ControllerService@AddTypeService');
//Agrega en la tabla service-calendar
$app->post('/service/add-servicecalendar','ControllerService@AddServiceCalendar');
//Agrega en la service-amenite
$app->post('/service/add-serviceamenite','ControllerService@AddServiceAmentines');
//Muestra un service-type en especifico 
$app->post('/service/get-type','ControllerService@GetTypeService');
//Muestra un Calendar-Service en especifico
$app->post('/service/get-calendar','ControllerService@ReadCalendarService');
//Muestra un Amenite-Service en especifico
$app->post('/service/get-amenitie','ControllerService@ReadServiceAmenite');//-->13/2/2017
//Muestra los service de un user en especifico
$app->post('/service/get-service','ControllerService@ReadServiceUser');//-->13/2/2017
/*Read*/
//Muestra al usuario con la category,accommodation,type 
$app->post('/service/get-service-category-type-accommodation','ControllerService@GetUserService');
//Muestra todos los user
$app->get('/service/read-service','ControllerService@ReadService');
/*Update*/
//Actualiza un service
$app->put('/service/update','ControllerService@UpdateService');
/*Delete*/
//Elimina un service
$app->delete('/service/delete','ControllerService@DeleteService');
//Elimina un type-service
$app->delete('/service/delete-type','ControllerService@DeleteTypeService');
//Elimina un service-amenites
$app->delete('/service/delete-amenitie','ControllerService@DeleteServiceAmenite');
//Elimina un service-calendar
$app->delete('/service/delete-calendar','ControllerService@DeleteServiceCalendar');
/////////////////////////////////////////////////////////////////////////////////////////

/*CRUD Paypal y Card*/////////////////////////////////////////////////////////////////////
/*Create*/
//Agrega Card
$app->post('/user/card/add-card','ControllerCardPaypal@AddCard');
//Agrega Paypal
$app->post('/user/paypal/add-paypal','ControllerCardPaypal@AddPaypal');
//Muestra Todo los Paypal que tiene un user en especifico----------------->cambios nuevo
$app->post('/user/paypal/get-paypal','ControllerCardPaypal@GetUserPaypal');
//Muestra Todo los Card que tiene un user en especifico------------------>cambios nuevos
$app->post('/user/card/get-card','ControllerCardPaypal@GetUserCard');
/*Read*/
//Muestra Todos los Card de la tabla
 $app->get('/user/card/read-card','ControllerCardPaypal@ReadCard');
//Muestra Todos los Paypal de la tabla
$app->get('/user/paypal/read-paypal','ControllerCardPaypal@ReadPaypal');
/*Update*/
//Actualiza account de paypal
//$app->put('/user/paypal/update_paypal','ControllerCardPaypal@UpdatePaypal');
//Actualiza number de Card
//$app->put('/user/card/update_number','ControllerCardPaypal@UpdateNumber');
//Actualiza month exp de Card
$app->put('/user/card/update-expmonth','ControllerCardPaypal@UpdateExpMonth');
//Actualiza year exp
$app->put('/user/card/update-expyear','ControllerCardPaypal@UpdateExpYear');
//Actualiza cvc de Card
//$app->put('/user/card/update_cvc','ControllerCardPaypal@UpdateCvc');
//Actualiza name de Card
$app->put('/user/card/update-name','ControllerCardPaypal@UpdateName');
/*Delete*/
//Elimina Paypal
$app->delete('/user/paypal/delete-paypal','ControllerCardPaypal@DeletePaypal');
//Elimina Card
$app->delete('/user/card/delete-card','ControllerCardPaypal@Deletecard');
///////////////////////////////////////////////////////////////////////////////////

/*CRUD Imagen*/////////////////////////////////////////////////////////////////////
/*Create*/
$app->post('/service/add-imagen','ControllerImagen@AddImagen');
/*Update*/
$app->put('/service/update-imagen','ControllerImagen@UpdateImagen');
/*Delete*/
$app->delete('/service/delete-imagen','ControllerImagen@DeleteImagen');
/*Read*/
$app->post('/service/get-imagen','ControllerImagen@GetImagen');
////////////////////////////////////////////////////////////////////////////////////


/*CRUD RENT///////////////////////////////////////////////////////////////////////*/
/*CREATE*/
//Agrega una renta
$app->post('/service/add-rent','ControllerRent@AddRent');
//Muestras todas las rentas seleccionda por el usuario
$app->post('/service/get-rent','ControllerRent@ReadRent');
/*DELETE*/
//Elimina una renta
$app->delete('/service/delete-rent','ControllerRent@DeleteRent');
//////////////////////////////////////////////////////////////////////////////////////

/*CRUD BILL*///////////////////////////////////////////////////////////////////////////////////
/*CREATE*/
//Agrega un bill
$app->post('/bill/add-bill','ControllerBill@AddBill');
//Read  bill
//Muestra una factura de un user en especifico
$app->post('/bill/get-bill','ControllerBill@ReadBill');
/*Delete*/
//Elimina Bill 
$app->delete('/bill/delete-bill','ControllerBill@DeleteBill');
////////////////////////////////////////////////////////////////////////////////////////////////////////

/* CRUD MESSAGE and Inbox *//////////////////////////////////////////////////////////////////////////////
//Muestra los messages del user en especifico
$app->post('/user/get-message','ControllerMessageInbox@ReadMessage');
//Muestra el user que envio y al enviado dependiendo 
$app->post('/user/get-inbox','ControllerMessageInbox@ReadInbox');
//envia un message y lo agrega en la inbox
$app->post('/user/add_message-inbox','ControllerMessageInbox@CreateMessageInbox');
//Elimina un message seleccionado por el user
$app->delete('/user/message/delete-message','ControllerMessageInbox@DeleteMessage');
/////////////////////////////////////////////////////////////////////////////////////////////////////////

/*Comment*///////////////////////////////////////////////////////////////////////////////////////////////
//Agrega un comentario----------------------->nuevo cambio
$app->post('/comment/add-comment','ControllerComment@AddComment');
//Muestra todos los comentarios de un usuario especifico
$app->post('/comment/get-comment','ControllerComment@ReadCommentUser');
//Elimina un comentario epecifico
$app->delete('/comment/delete-comment','ControllerComment@DeleteComment');
////////////////////////////////////////////////////////////////////////////////////////////////////

/*Notification*///////////////////////////////////////////////////////////////////////////////////////
//Muestra las notification de un user en especifico 
$app->post('/notification/get-notification','ControllerNotification@GetNotification');
//Elimina una notification en especifico
$app->delete('/notification/delete-notification','ControllerNotification@DeleteNotification');
//////////////////////////////////////////////////////////////////////////////////////////////////

/*CRUD PRICE-HISTORY*/////////////////////////////////////////////////////////////////////////////
//Agrega un Price History
$app->post('/price-history/add-history','ControllerPriceHistory@AddPryceHistory');
//Actualiza un price
$app->put('/price-history/update-price','ControllerPriceHistory@UpdatePrice');
//Muestra todo los precios historicos de un servicio
$app->post('/price-history/get-history','ControllerPriceHistory@GetHistory');
//////////////////////////////////////////////////////////////////////////////////////////////////

/*Stripe*/////////////////////////////////////////////////////////////////////////////////////////
$app->post('/user/stripe/add-stripe','ControllerStripe@stripePayment');
//////////////////////////////////////////////////////////////////////////////////////////////////

/*Lista de Datos para el Combobox*////////////////////////////////////////////////////////////////
//Muestra la Category
$app->get('/category/get-category','ControllerCombobox@GetCategory');
//Muestra todos los GetAmenities
$app->get('/amenitie/get-amenitie','ControllerCombobox@GetAmenities');
//Muestra todos los alojamientos
$app->get('/accommodation/get-accommodation','ControllerCombobox@GetAccommodation');
//Muestra todos los dias de la semana
$app->get('/calendar/get-calendar','ControllerCombobox@GetCalendar');
//Muestra todos los tipos
$app->get('/type/get-type','ControllerCombobox@GetType');
//Muestra todas las duraciones
$app->get('/duration/get-duration','ControllerCombobox@GetDuration');
$app->get('/duration/get-duration','ControllerCombobox@GetDuration');
//Muestra todas las ciudades
$app->post('/city/get-city','ControllerCombobox@GetCity');

//$app->post('/city/get-city','ControllerCombobox@GetCity');


