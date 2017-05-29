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
$app->post('/user/update-thumbnail','ControllerUser@UpdateThumbnail');
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
$app->post('/user/login','ControllerUser@verificationLogin');//-->3/3/2017
//OAuth User-Login
$app->post('/user/login-oauth','ControllerUser@LoginOauth');//-->3/3/2017
//OAuth User-Name
$app->post('/user/set-name-oauth','ControllerUser@UserOauth');//-->3/3/2017
//Verification email
$app->post('/user/email','ControllerUser@VerificationEmail');//-->3/3/2017
$app->post('/user/set-user-oauth','ControllerUser@UserOauth');//-->3/3/2017
/////////////////////////////////////////////////////////////////////////////

/*CRUD SERVICE Movil*//////////////////////////////////////////////////////////////
/*Create*/
//Agregar un servicio (space-step)
$app->post('/service/space/create','ControllerService@AddNewSpaceStep');
//Agrega una category
$app->post('/service/space/step','ControllerService@AddNewStep');
//Agrega  type
$app->post('/service/space/step-1','ControllerService@AddNewStep1');
//Agrega invitados y habitaciones
$app->put('/service/space/step-2','ControllerService@AddNewStep2');
//Agrega Baños
$app->put('/service/space/step-3','ControllerService@AddNewStep3');
//Agrega datos de ubicacion
$app->post('service/space/step-4','ControllerService@AddNewStep4');
//Agrega en la service-amenitie
$app->post('service/space/step-5','ControllerService@AddNewStep5');
//Agrega en la Agregar costo y politica de pago
$app->post('service/space/step-6','ControllerService@AddNewStep6');
//Agrega titulo
$app->post('service/space/title','ControllerService@AddNewTitle');
//Agrega description
$app->post('service/space/description','ControllerService@AddNewDescription');
//Agrega reglas de casa
$app->post('service/space/rules-house','ControllerService@AddNewRulesHouse');
//Agrega check_in y check_out
$app->post('service/space/check-in-and-check-out','ControllerService@AddNewCheckInCheckOut');//esta ruta tambien es para web
//Agrega Reservation Preferent
$app->post('service/space/reservation-preference','ControllerService@AddNewReservationPrerence');
//Agrega Imagen and description
$app->post('/service/space/add-image','ControllerService@AddNewSpaceStep9');




/*Crud Service Web */////////////////////////////////////////////////////////////////->en proces0
//Agregar un servicio (space-step)
$app->post('/service/space/step/create','ControllerService@AddNewSpaceStep');
//Agregar un servicio (space-step1)
$app->post('/service/space/step-1/create','ControllerService@AddNewSpaceStep1');
//Agregar un servicio (space-step2-bedroom)
$app->post('/service/space/step-2/bedrooms','ControllerService@AddNewSpaceStep2');
//Agregar un servicio (space-step2-beds)
$app->post('/service/space/step-2/beds/details','ControllerService@AddNewSpaceStep2Beds');
//Agregar un servicio (space-step3-bathroom)
$app->put('/service/space/step-3/bathroom','ControllerService@AddNewStep3');
//Agregar un servicio (space-step4)
$app->put('/service/space/step-4/location','ControllerService@AddNewSpaceStep4Location');
//Agregar un Amenities (space-step5)
$app->post('/service/space/step-5/amenities','ControllerService@AddNewStep5');
//Agregar un cancellation policy (space-step6)
$app->post('/service/space/step-6/hosting','ControllerService@AddNewSpaceStep6');
//Agregar un description service (space-step7)
$app->post('/service/space/step-7/description','ControllerService@AddNewSpaceStep7Description');
//Agregar lenguaje
$app->post('/service/space/description/add-languaje','ControllerService@AddLanguaje');
//Agregar un rules description (space-step8)
$app->post('/service/space/step-8/rules','ControllerService@AddNewSpaceStep8Rules');
//Agregar imagen (space-step9)
$app->post('/service/space/step-9/image','ControllerService@AddNewSpaceStep9');
//Agregar Notas de Emergencia (space-step11)
$app->post('/service/space/step-11','ControllerService@AddNewSpaceStep11');
//Agrega fecha en el calendario->16/5/2017
$app->post('/service/day','ControllerService@AddDate');
//Bloquear o desbloquear fecha
$app->put('/service/update-day','ControllerService@UpdateDate');
//Borrar lenguaje
$app->delete('/service/space/delete-languaje','ControllerService@DeleteLanguaje');
//Muestra todos los lenguajes uqe tiene un servicio
$app->get('/service/space/description/languaje','ControllerCombobox@GetLanguaje');
//Agrega numero de emergencia
$app->post('/service/space/step-11/emergency-add','ControllerService@AddNewEmergency');
//Elimina numero de emergencia
$app->delete('/service/space/step-11/emergency-delete','ControllerService@DeleteNewEmergency');
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Agrgar Accommodation
$app->put('/service/add-accommodation','ControllerService@AddAccommodation');
//Agrega en la tabla service-calendar
$app->post('/service/add-servicecalendar','ControllerService@AddServiceCalendar');
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
$app->post('/bill/add-bill','ControllerPaymentBill@AddBill');//-->3/3/2017
//Read  bill
//Muestra una factura de un user en especifico
$app->post('/bill/get-bill','ControllerPaymentBill@ReadBill');
/*Delete*/
//Elimina Bill 
$app->delete('/bill/delete-bill','ControllerPaymentBill@DeleteBill');
////////////////////////////////////////////////////////////////////////////////////////////////////////

/* CRUD MESSAGE and Inbox *//////////////////////////////////////////////////////////////////////////////
//Muestra los messages del user en especifico
$app->post('/user/get-message','ControllerMessageInbox@ReadMessage');
//Muestra el user que envio y al enviado dependiendo 
$app->post('/user/get-inbox','ControllerMessageInbox@ReadInbox');
//envia un message y lo agrega en la inbox
$app->post('/user/add-message','ControllerMessageInbox@CreateMessageInbox');
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
//$app->post('/user/stripe/add-stripe','ControllerStripe@stripePayment');
//////////////////////////////////////////////////////////////////////////////////////////////////

/*Lista de Datos para el Combobox*////////////////////////////////////////////////////////////////
//Muestra todos los alojamientos->para step1
$app->get('/accommodation/get-accommodation','ControllerCombobox@GetAccommodation');
//Muestra todas las type de categoria space->para step1
$app->get('/category/space/get-type','ControllerCombobox@TypeGet');
//Muestra las rules house
$app->get('/rules/space/house','ControllerCombobox@RulesHouse');
//Muestra la Category
$app->get('/category/get-category','ControllerCombobox@GetCategory');
// Muestra currency
$app->get('/currency/get-currency','ControllerCombobox@GetCurrency');
//Muestra todos los GetAmenities--->18/4/2017
$app->get('/amenities/get-space-amenities','ControllerCombobox@GetSpaceAmenities');
//Muestra todos los dias de la semana
$app->get('/calendar/get-calendar','ControllerCombobox@GetCalendar');
//Muestra todas las duraciones
$app->get('/duration/get-duration','ControllerCombobox@GetDuration');
//Muestra todas las politicas de pago
$app->get('/payment/get-payment','ControllerCombobox@GetPayment');
//Muestra todas las  habitaciones que tiene un servicio
$app->get('/service/get-beds','ControllerCombobox@GetBeds');
//Muestra todas las camas que tiene una habitacion
$app->get('/service/get-bed-bedroom','ControllerCombobox@GetBedBedroom');
//Muestra datos de una habitacion
$app->get('/service/get-bed-bedroom-data','ControllerCombobox@GetBedBedroomData');
//Muestra todas los paises
$app->get('/country/get-country','ControllerCombobox@GetCountry');
//Muestra todas las ciudades
$app->get('/city/get-city','ControllerCombobox@GetCity');
//Muestra todas los estados
$app->get('/state/get-state','ControllerCombobox@GetState');
//Muestra todos los numeros de emergencia de un servicio
$app->get('/space/get-number-emergency','ControllerCombobox@GetNumberEmergency');

////////////////////////////////////////////////////////////////////////////////////////
//next and back
$app->get('/service/space/step/create','ControllerCombobox@ReturnStep');

$app->get('/service/space/step-1/create','ControllerCombobox@ReturnStep1');
//Retorna un servicio (space-step2-bedroom)
$app->get('/service/space/step-2/bedrooms','ControllerCombobox@ReturnStep2');
//Retorna un servicio (space-step2-beds)
$app->geat('/service/space/step-2/beds/details','ControllerCombobox@ReturnStep2Beds');
//Retorn un servicio (space-step3-bathroom)
$app->get('/service/space/step-3/bathroom','ControllerCombobox@ReturnStep3');
//Retorna un servicio (space-step4)
$app->get('/service/space/step-4/location','ControllerCombobox@ReturnStep4Location');
//Retorna un Amenities (space-step5)
$app->get('/service/space/step-5/amenities','ControllerCombobox@ReturnStep5');
//Retorna un cancellation policy (space-step6)
$app->get('/service/space/step-6/hosting','ControllerCombobox@ReturnStep6');
//Retorna description service (space-step7)
$app->get('/service/space/step-7/description','ControllerCombobox@ReturnStep7Description');
//Retorna description (space-step8)
$app->get('/service/space/step-8/rules','ControllerCombobox@ReturnStep8Rules');
//Retorna image (space-step9)
$app->get('/service/space/step-9/get-image','ControllerCombobox@ReturnStep9');
//Retorna (space-step11)
$app->get('/service/space/step-11/number-emergency','ControllerCombobox@ReturnStep11');




$app->get('/make_token',function(){
    return str_random(32);
});


