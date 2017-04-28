use Migohood;
insert into category(name,code,languaje) value("Space",1,"EN"),("Workspace",2,"EN"),("Parking Space",3,"EN"),("Service",4,"EN"),("Espacio",1,"ES"),("Espacio_de_Trabajo",2,"ES"),("Plaza_de_Aparcamiento",3,"ES"),("Servicio",4,"ES");
insert into type(name,category_id,code,languaje) values("Apartament",1,1,"EN"),("House",1,2,"EN"),("B&B",1,3,"EN"),("Room",1,4,"EN"),("Other",1,5,"EN"),("Apartamento",1,1,"ES"),("Casa",1,2,"ES"),("Cama_y_Desayuno",1,3,"ES"),("Habitacion",1,4,"ES"),("Otros",1,5,"ES");
insert into type(name,category_id,code,languaje) values("Bussiness_Center",2,1,"EN"),("Corporate_Office",2,2,"EN"),("Coworking_Space",2,3,"EN"),("Other_Workspace",2,4,"EN"),("Centro_de_Negocio",2,1,"ES"),("Oficina_Corporativa",2,2,"ES"),("Espacio_de_Trabajo",2,3,"ES"),("Otros_Espacios",2,4,"ES");
insert into type(name,category_id,code,languaje) values("Driveway",3,1,"EN"),("Garage",3,2,"EN"),("Car Park",3,3,"EN"),("Other",3,4,"EN"),("Entrada_de_Coche",3,1,"ES"),("Garaje",3,2,"ES"),("Estacionamiento",3,3,"ES"),("Otros",3,4,"ES");
insert into type(name,category_id,code,languaje) values("Tourism",4,1,"EN"),("Educational",4,2,"EN"),("Cultural",4,3,"EN"),("Recreational",4,4,"EN"),("Eco",4,5,"EN"),("Adventure",4,6,"EN"),("Fitness",4,7,"EN"),("Turismo",4,1,"ES"),("Educacion",4,2,"ES"),("Cultura",4,3,"ES"),("Recreativo",4,4,"ES"),("Eco",4,5,"ES"),("Aventura",4,6,"ES"),("Aptitud",4,7,"ES");
insert into accommodation(name,code,languaje)values("Entire",1,"EN"),("Private",2,"EN"),("Share",3,"EN"),("Todo",1,"ES"),("Privado",2,"ES"),("Compartir",3,"ES");
insert into payment(type,code,languaje)values("Flex",1,"EN"),("Moderate",2,"EN"),("Esctric",3,"EN"),("Flexible",1,"ES"),("Moderado",2,"ES"),("Estricto",3,"ES");
insert into calendar(day,code,languaje) value("All_Days",1,"EN"),("Sunday",2,"EN"),("Monday",3,"EN"),("Tuesday",3,"EN"),("Wednesday",4,"EN"),("Thursday",5,"EN"),("Friday",6,"EN"),("Saturday",7,"EN"),
("Todos_los_Dias",1,"ES"),("Domigo",2,"ES"),("Lunes",3,"ES"),("Martes",3,"ES"),("Miercoles",4,"EN"),("Jueves",5,"ES"),("Viernes",6,"ES"),("Sabado",7,"ES");
insert into duration(type,code,languaje) values("Minute",1,"EN"),("Hour",2,"EN"),("Week",3,"EN"),("Month",4,"EN"),("Minuto",1,"ES"),("Hora",2,"ES"),("Semana",3,"ES"),("Mes",4,"ES");
insert into type_number(type,code,languaje)values("Fixed",1,"EN"),("Cell",2,"EN"),("Fijo",1,"ES"),("Celular",2,"ES");
insert into description(type)values("Title"),("Address1"),("Address2"),("Desc_Neighborhood"),("Desc_Surroundings"),("desc_length"),("desc_latitud"),("description"),("apt"),("desc_around"),("desc_crib"),("desc_acce"),("socialize"),("available"),("desc_guest"),("desc_note");
insert into house_rules(type,code,languaje)values("Suitable for children (2 to 12 years old)",1,"EN"),("Suitable for babies (0 to 2 years)",2,"EN"),("Pets Allowed",3,"EN"),("Smoking Allowed",4,"EN"),("Events allowed",5,"EN"),("Other Rules",6,"EN"),("Guest Phone",7,"EN"),("Guest Email",8,"EN"),("Guest Profile",9,"EN"),("Guest Payment",10,"EN"),("Guest Provided",11,"EN"),("Guest Recomendation",12,"EN"),("Instructions",13,"EN"),("Name Wifi",14,"EN"),("Password Wifi",15,"EN")
,("Apto para niños(2 a 12 años)",1,"ES"),("Apto para bebes(0 a 2 años)",2,"ES"),("Se Admiten Mascotas",3,"ES"),("Permitido Fumar",4,"ES"),("Eventos fiestas permitidos",5,"ES"),("Otras reglas",6,"ES"),("Telefono de Invitados",7,"ES"),("Correo de Invitados",8,"ES"),("Perfil de Invitados",9,"ES"),("Pago de Invitados",10,"ES"),("Invitado Previsto",11,"ES"),("Recomendacion de Invitado",12,"ES"),("Instruccion",13,"ES"),("Nombre Wifi",14,"ES"),("contraseña Wifi",15,"ES");
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
