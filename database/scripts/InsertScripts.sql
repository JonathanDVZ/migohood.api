use Migohood;
insert into category(name,code,languaje) values 
(1,"Space",1,"EN"),
(2,"Workspace",2,"EN"),
(3,"Parking space",3,"EN"),
(4,"Service",4,"EN"),
(5,"Espacio",1,"ES"),
(6,"Espacio de trabajo",2,"ES"),
(7,"Plaza de aparcamiento",3,"ES"),
(8,"Servicio",4,"ES");

insert into type(id,name,category_id,code,languaje) values
(1,"Apartament",1,1,"EN"),
(2,"House",1,2,"EN"),
(3,"B&B",1,3,"EN"),
(4,"Room",1,4,"EN"),
(5,"Other",1,5,"EN"),
(6,"Apartamento",1,1,"ES"),
(7,"Casa",1,2,"ES"),
(8,"Cama y desayuno",1,3,"ES"),
(9"Habitacion",1,4,"ES"),
(10,"Otros",1,5,"ES");
insert into type(id,name,category_id,code,languaje) values
(11,"Bussiness center",2,1,"EN"),
(12,"Corporate office",2,2,"EN"),
(13,"Coworking space",2,3,"EN"),
(14,"Other workspace",2,4,"EN"),
(15,"Centro de negocio",2,1,"ES"),
(16,"Oficina corporativa",2,2,"ES"),
(17,"Espacio de trabajo",2,3,"ES"),
(18,"Otros espacios",2,4,"ES");
insert into type(id,name,category_id,code,languaje) values
(19,"Driveway",3,1,"EN"),
(20,"Garage",3,2,"EN"),
(21,"Car park",3,3,"EN"),
(22,"Other",3,4,"EN"),
(23,"Entrada de coche",3,1,"ES"),
(24,"Garaje",3,2,"ES"),
(25,"Estacionamiento",3,3,"ES"),
(26,"Otros",3,4,"ES");
insert into type(id,name,category_id,code,languaje) values
(27,"Tourism",4,1,"EN"),
(28,"Educational",4,2,"EN"),
(29,"Cultural",4,3,"EN"),
(30,"Recreational",4,4,"EN"),
(31,"Eco",4,5,"EN"),
(32,"Adventure",4,6,"EN"),
(33,"Fitness",4,7,"EN"),
(34,"Turismo",4,1,"ES"),
(35,"Educacion",4,2,"ES"),
(36,"Cultura",4,3,"ES"),
(37,"Recreativo",4,4,"ES"),
(38,"Eco",4,5,"ES"),
(39,"Aventura",4,6,"ES"),
(40,"Aptitud",4,7,"ES");

insert into accommodation(name,code,languaje)values("Entire",1,"EN"),("Private",2,"EN"),("Share",3,"EN"),("Todo",1,"ES"),("Privado",2,"ES"),("Compartir",3,"ES");
insert into payment(type,code,languaje)values("Flex",1,"EN"),("Moderate",2,"EN"),("Esctric",3,"EN"),("Flexible",1,"ES"),("Moderado",2,"ES"),("Estricto",3,"ES");
insert into calendar(day,code,languaje) value("All_Days",1,"EN"),("Sunday",2,"EN"),("Monday",3,"EN"),("Tuesday",3,"EN"),("Wednesday",4,"EN"),("Thursday",5,"EN"),("Friday",6,"EN"),("Saturday",7,"EN"),
("Todos_los_Dias",1,"ES"),("Domigo",2,"ES"),("Lunes",3,"ES"),("Martes",3,"ES"),("Miercoles",4,"EN"),("Jueves",5,"ES"),("Viernes",6,"ES"),("Sabado",7,"ES");
insert into duration(type,code,languaje) values("Minute",1,"EN"),("Hour",2,"EN"),("Week",3,"EN"),("Month",4,"EN"),("Minuto",1,"ES"),("Hora",2,"ES"),("Semana",3,"ES"),("Mes",4,"ES");
insert into type_number(type,code,languaje)values("Fixed",1,"EN"),("Cell",2,"EN"),("Fijo",1,"ES"),("Celular",2,"ES");
insert into description(type)values("Title"),("Address1"),("Address2"),("Desc_Neighborhood"),("Desc_Surroundings"),("desc_length"),("desc_latitud"),("description"),("apt"),("desc_around"),("desc_crib"),("desc_acce"),("socialize"),("available"),("desc_guest"),("desc_note");

insert into house_rules(id,type,code,languaje)values
(1,"Suitable for children (2 to 12 years old)",1,"EN"),
(2,"Suitable for babies (0 to 2 years)",2,"EN"),
(3,"Pets allowed",3,"EN"),
(4,"Smoking allowed",4,"EN"),
(5,"Events allowed",5,"EN"),
(6,"Other rules",6,"EN"),
(7,"Guest phone",7,"EN"),
(8,"Guest email",8,"EN"),
(9,"Guest profile",9,"EN"),
(10,"Guest payment",10,"EN"),
(11,"Guest provided",11,"EN"),
(12,"Guest recomendation",12,"EN"),
(13,"Instructions",13,"EN"),
(14,"Name Wifi",14,"EN"),
(15,"Password Wifi",15,"EN"),
(16,"Apto para niños(2 a 12 años)",1,"ES"),
(17,"Apto para bebes(0 a 2 años)",2,"ES"),
(18,"Se admiten mascotas",3,"ES"),
(19,"Permitido fumar",4,"ES"),
(20,"Eventos fiestas permitidos",5,"ES"),
(21,"Otras reglas",6,"ES"),
(22,"Telefono de invitados",7,"ES"),
(23,"Correo de invitados",8,"ES"),
(24,"Perfil de invitados",9,"ES"),
(25,"Pago de invitados",10,"ES"),
(26,"Invitado previsto",11,"ES"),
(27,"Recomendacion de invitado",12,"ES"),
(28,"Instruccion",13,"ES"),
(29,"Nombre Wifi",14,"ES"),
(30,"Contraseña Wifi",15,"ES");

insert into bed(type,code,languaje)values("Double",1,"EN"),("Queen",2,"EN"),("Individual",3,"EN"),("Sofa",4,"EN"),("Other",5,"EN"),
("Doble",1,"ES"),("Reina",2,"ES"),("Individual",3,"ES"),("Sofa",4,"ES"),("Otro",5,"ES");
insert into type_amenities(name,code,languaje)values("Amenities",1,"EN"),("Your_offer",2,"EN"),("Guest_use",3,"EN"),("Comodidades",1,"ES"),("Tu Oferta",2,"ES"),("Uso de los huesped",3,"ES");

-- ingles--
insert into amenities(name,category_id,type_amenities_id,languaje,code)values("Pets allowed",1,1,"EN",1),("Events allowed",1,1,"EN",2),("Production allowed",1,1,"EN",3),("Family friendly",1,1,"EN",4),("Business guests",1,1,"EN",5),("Smoke free",1,1,"EN",6),("Gym",1,1,"EN",7),("Parking",1,1,"EN",8);
insert into amenities(name,category_id,type_amenities_id,languaje,code)values("Essential",1,2,"EN",9),("Shampoo",1,2,"EN",10),("Wifi",1,2,"EN",11),("TV",1,2,"EN",12);
insert into amenities(name,category_id,type_amenities_id,languaje,code)values("Kitchen",1,3,"EN",13),("Laundry washer",1,3,"EN",14),("Laundry dryer",1,3,"EN",15),("Hot tub",1,3,"EN",16),("Parking",1,3,"EN",17),("Elevator",1,3,"EN",18),("Pool",1,3,"EN",19),("Gym",1,3,"EN",20);
-- español--
insert into amenities(name,category_id,type_amenities_id,languaje,code)values("Mascotas permitidas",1,1,"ES",1),("Eventos permitida",1,1,"ES",2),("Produccion permitida",1,1,"ES",3),("Familia amable",1,1,"ES",4),("Cliente negocio",1,1,"ES",5),("Libre de humo",1,1,"ES",6),("Gimnasio",1,1,"ES",7),("Estacionamiento",1,1,"ES",8);
insert into amenities(name,category_id,type_amenities_id,languaje,code)values("Esencial",1,2,"ES",9),("Champú",1,2,"ES",10),("Wifi",1,2,"ES",11),("Television",1,2,"ES",12);
insert into amenities(name,category_id,type_amenities_id,languaje,code)values("Cocina",1,3,"ES",13),("Lavadero",1,3,"ES",14),("Secadora",1,3,"ES",15),("Tina caliente",1,3,"ES",16),("Estacionamiento",1,3,"ES",17),("Ascensor",1,3,"ES",18),("Piscina",1,3,"ES",19),("Gimnasio",1,3,"ES",20); 
