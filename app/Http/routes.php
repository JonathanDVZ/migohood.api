<?php

/*CRUD USER*//////////////////////////////////////////////////////////
/*Create*/
//Agrega un usuario
$app->post('/user/create','ControllerUser@Create');
//Agrega numero telefonico
$app->post('/user/add_phone','ControllerUser@AddPhone');
//muestra un usuario en especifico
$app->post('/user/getuser','ControllerUser@GetUser');
//muestra un city de un user
$app->post('/user/getcity','ControllerUser@GetCity');
//muestra un state de un user
$app->post('/user/getstate','ControllerUser@GetState');
//muestra un country de un user
$app->post('/user/getcountry','ControllerUser@GetCountry');
//Muestra los phone de un user
$app->post('/user/get_phone','ControllerUser@GetPhone');
/*Read*/
//Muestra todos los usuarios
$app->get('/user/read','ControllerUser@Read');
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
//Elimina un usuario
$app->delete('/user/delete','ControllerUser@Delete');
//Elimina un telefono de un user en especifico
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
//Muestra un service-type en especifico 
$app->post('/service/get_type','ControllerService@GetTypeService');
//Muestra un Calendar-Service en especifico
$app->post('/service/get_calendar','ControllerService@ReadCalendarService');
//Muestra un Amenite-Service en especifico
$app->post('/service/get_amenitie','ControllerService@ReadServiceAmenite');//-->13/2/2017
//Muestra los service de un user en especifico
$app->post('/service/get_service','ControllerService@ReadServiceUser');//-->13/2/2017
/*Read*/
//Muestra al usuario con la category,accommodation,type 
$app->post('/service/get_service_category_type_accommodation','ControllerService@GetUserService');
//Muestra todos los user
$app->get('/service/read_service','ControllerService@ReadService');
/*Update*/
//Actualiza un service
$app->put('/service/update','ControllerService@UpdateService');
/*Delete*/
//Elimina un service
$app->delete('/service/delete','ControllerService@DeleteService');
//Elimina un type-service
$app->delete('/service/delete_type','ControllerService@DeleteTypeService');
//Elimina un service-amenites
$app->delete('/service/delete_amenite','ControllerService@DeleteServiceAmenite');
//Elimina un service-calendar
$app->delete('/service/delete_calendar','ControllerService@DeleteServiceCalendar');
/////////////////////////////////////////////////////////////////////////////////////////

/*CRUD Paypal y Card*/////////////////////////////////////////////////////////////////////
/*Create*/
//Agrega Card
$app->post('/user/card/add_card','ControllerCardPaypal@AddCard');
//Agrega Paypal
$app->post('/user/paypal/add_paypal','ControllerCardPaypal@AddPaypal');
//Muestra Todo los Paypal que tiene un user en especifico----------------->cambios nuevo
$app->post('/user/paypal/get_paypal','ControllerCardPaypal@GetUserPaypal');
//Muestra Todo los Card que tiene un user en especifico------------------>cambios nuevos
$app->post('/user/card/get_card','ControllerCardPaypal@GetUserCard');
/*Read*/
//Muestra Todos los Card de la tabla
 $app->get('/user/card/read_card','ControllerCardPaypal@ReadCard');
//Muestra Todos los Paypal de la tabla
$app->get('/user/paypal/read_paypal','ControllerCardPaypal@ReadPaypal');
/*Update*/
//Actualiza account de paypal
//$app->put('/user/paypal/update_paypal','ControllerCardPaypal@UpdatePaypal');
//Actualiza number de Card
//$app->put('/user/card/update_number','ControllerCardPaypal@UpdateNumber');
//Actualiza month exp de Card
$app->put('/user/card/update_expmonth','ControllerCardPaypal@UpdateExpMonth');
//Actualiza year exp
$app->put('/user/card/update_expyear','ControllerCardPaypal@UpdateExpYear');
//Actualiza cvc de Card
//$app->put('/user/card/update_cvc','ControllerCardPaypal@UpdateCvc');
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
$app->post('/service/add_imagen','ControllerImagen@AddImagen');
/*Update*/
$app->put('/service/update_imagen','ControllerImagen@UpdateImagen');
/*Delete*/
$app->delete('/service/delete_imagen','ControllerImagen@DeleteImagen');
/*Read*/
$app->post('/service/get_imagen','ControllerImagen@GetImagen');
////////////////////////////////////////////////////////////////////////////////////


/*CRUD RENT///////////////////////////////////////////////////////////////////////*/
/*CREATE*/
//Agrega una renta
$app->post('/service/add_rent','ControllerRent@AddRent');
//Muestras todas las rentas seleccionda por el usuario
$app->post('/service/get_rent','ControllerRent@ReadRent');
/*DELETE*/
//Elimina una renta
$app->delete('/service/delete_rent','ControllerRent@DeleteRent');
//////////////////////////////////////////////////////////////////////////////////////

/*CRUD BILL*///////////////////////////////////////////////////////////////////////////////////
/*CREATE*/
//Agrega un bill
$app->post('/bill/add_bill','ControllerBill@AddBill');
//Read  bill
//Muestra una factura de un user en especifico
$app->post('/bill/get_bill','ControllerBill@ReadBill');
/*Delete*/
//Elimina Bill 
$app->delete('/bill/delete_bill','ControllerBill@DeleteBill');
////////////////////////////////////////////////////////////////////////////////////////////////////////

/* CRUD MESSAGE and Inbox *//////////////////////////////////////////////////////////////////////////////
//Muestra los messages del user en especifico
$app->post('/user/get_message','ControllerMessageInbox@ReadMessage');
//Muestra el user que envio y al enviado dependiendo 
$app->post('/user/get_inbox','ControllerMessageInbox@ReadInbox');
//envia un message y lo agrega en la inbox
$app->post('/user/add_message_inbox','ControllerMessageInbox@CreateMessageInbox');
//Elimina un message seleccionado por el user
$app->delete('/user/message/delete_message','ControllerMessageInbox@DeleteMessage');
/////////////////////////////////////////////////////////////////////////////////////////////////////////

/*Comment*///////////////////////////////////////////////////////////////////////////////////////////////
//Agrega un comentario----------------------->nuevo cambio
$app->post('/comment/add_comment','ControllerComment@AddComment');

$app->post('/comment/get_comment','ControllerComment@ReadCommentUser');

$app->delete('/comment/delete_comment','ControllerComment@DeleteComment');
////////////////////////////////////////////////////////////////////////////////////////////////////

/*Notification*///////////////////////////////////////////////////////////////////////////////////////
//Muestra las notification de un user en especifico 
$app->post('/notification/get_notification','ControllerNotification@GetNotification');
//Elimina una notification en especifico
$app->delete('/notification/delete_notification','ControllerNotification@DeleteNotification');
////////////////////////////////////////////////////////////////////////////////////////////////////////

/*CRUD PRICE-HISTORY*/////////////////////////////////////////////////////////////////////////////
//Agrega un Price History
$app->post('/price_history/add_history','ControllerPriceHistory@AddPryceHistory');
/*Stripe*/
$app->post('/stripe/add_stripe','ControllerStripe@stripePayment');

/*_______________________________________________________________________________________________________________*/
//Actualiza un price
$app->put('/price_history/update_price','ControllerPriceHistory@UpdatePrice');//-->13-2-2017
/**//////////////////////////////////////////////////////////////////////////////////////////////





/*Lista de Datos para el Combobox    --->Arreglar*/
//Muestra la Category
$app->post('/service/get_category','ControllerCombobox@GetCategory');
//Muestra todos los GetAmenites
$app->post('/service/get_amenite','ControllerCombobox@GetAmenites');
//Muestra todos lo GetType
//$app->post('/service/get_type','ControllerCombobox@GetType');
//Muestra los accommodation 
$app->get('/service/get_accommodation','ControllerCombobox@GetAcommodation');

