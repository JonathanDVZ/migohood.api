use Migohood;
insert into category(id,name,code,languaje) values 
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
(9,"Habitacion",1,4,"ES"),
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
 
insert into accommodation(id,name,code,languaje)values
(1,"Entire",1,"EN"),
(2,"Private",2,"EN"),
(3,"Share",3,"EN"),
(4,"Todo",1,"ES"),
(5,"Privado",2,"ES"),
(6,"Compartir",3,"ES");

insert into payment(id,type,code,languaje)values
(1,"Flex",1,"EN"),
(2,"Moderate",2,"EN"),
(3,"Esctric",3,"EN"),
(4,"Flexible",1,"ES"),
(5,"Moderado",2,"ES"),
(6,"Estricto",3,"ES");

insert into calendar(id,day,code,languaje) values
(1,"All_Days",1,"EN"),
(2,"Sunday",2,"EN"),
(3,"Monday",3,"EN"),
(4,"Tuesday",3,"EN"),
(5,"Wednesday",4,"EN"),
(6,"Thursday",5,"EN"),
(7,"Friday",6,"EN"),
(8,"Saturday",7,"EN"),
(9,"Todos_los_Dias",1,"ES"),
(10,"Domigo",2,"ES"),
(11,"Lunes",3,"ES"),
(12,"Martes",3,"ES"),
(13,"Miercoles",4,"EN"),
(14,"Jueves",5,"ES"),
(15,"Viernes",6,"ES"),
(16,"Sabado",7,"ES");

insert into duration(id,type,code,languaje) values
(1,"Minute",1,"EN"),
(2,"Hour",2,"EN"),
(3,"Week",3,"EN"),
(4,"Month",4,"EN"),
(5,"Minuto",1,"ES"),
(6,"Hora",2,"ES"),
(7,"Semana",3,"ES"),
(8,"Mes",4,"ES");

insert into type_number(id,type,code,languaje)values
(1,"Fixed",1,"EN"),
(2,"Cell",2,"EN"),
(3,"Fijo",1,"ES"),
(4,"Celular",2,"ES");

insert into description(id,type,description)values
(1,"Title","his item is used to specify the title of the service"),
(2,"Address1","First address of the service"),
(3,"Address2","Second address of the service"),
(4,"Desc_Neighborhood","Neighborhood description"),
(5,"Desc_Surroundings","Surroundings description"),
(6,"Longitude","Length for google map"),
(7,"Latitude","Latitud for google map"),
(8,"Description","service description"),
(9,"Crib","crib description"),
(10,"Guest Access","User access description"),
(11,"Socialize","check the socialize"),
(12,"Available","check the available"),
(13,"Desc_Guest","guest description"),
(14,"Desc_Note","Any note you want to leave");

insert into house_rules(id,type)values
(1,"Suitable for children (2 to 12 years old)"),
(2,"Suitable for babies (0 to 2 years)"),
(3,"Pets allowed"),
(4,"Smoking allowed"),
(5,"Events allowed"),
(6,"Other rules"),
(7,"Guest phone"),
(8,"Guest email"),
(9,"Guest profile"),
(10,"Guest payment"),
(11,"Guest provided"),
(12,"Guest recomendation"),
(13,"Instructions"),
(14,"Name Wifi"),
(15,"Password Wifi");

insert into bed(id,type,code,languaje)values
(1,"Double",1,"EN"),(2,"Queen",2,"EN"),(3,"Individual",3,"EN"),(4,"Sofa",4,"EN"),(5,"Other",5,"EN"),
(6,"Doble",1,"ES"),(7,"Reina",2,"ES"),(8,"Individual",3,"ES"),(9,"Sofa",4,"ES"),(10,"Otro",5,"ES");

insert into type_amenities(id,name)values
(1,"Amenities"),
(2,"Your_offer"),
(3,"Guest_use");
-- ingles--
insert into amenities(id,name,category_id,type_amenities_id,languaje,code)values(1,"Pets allowed",1,1,"EN",1),(2,"Events allowed",1,1,"EN",2),(3,"Production allowed",1,1,"EN",3),(4,"Family friendly",1,1,"EN",4),(5,"Business guests",1,1,"EN",5),(6,"Smoke free",1,1,"EN",6),(7,"Gym",1,1,"EN",7),(8,"Parking",1,1,"EN",8);
insert into amenities(id,name,category_id,type_amenities_id,languaje,code)values(9,"Essential",1,2,"EN",9),(10,"Shampoo",1,2,"EN",10),(11,"Wifi",1,2,"EN",11),(12,"TV",1,2,"EN",12);
insert into amenities(id,name,category_id,type_amenities_id,languaje,code)values(13,"Kitchen",1,3,"EN",13),(14,"Laundry washer",1,3,"EN",14),(15,"Laundry dryer",1,3,"EN",15),(16,"Hot tub",1,3,"EN",16),(17,"Parking",1,3,"EN",17),(18,"Elevator",1,3,"EN",18),(19,"Pool",1,3,"EN",19),(20,"Gym",1,3,"EN",20);
-- español--
insert into amenities(id,name,category_id,type_amenities_id,languaje,code)values(21,"Mascotas permitidas",1,1,"ES",1),(22,"Eventos permitida",1,1,"ES",2),(23,"Produccion permitida",1,1,"ES",3),(24,"Familia amable",1,1,"ES",4),(25,"Cliente negocio",1,1,"ES",5),(26,"Libre de humo",1,1,"ES",6),(27,"Gimnasio",1,1,"ES",7),(28,"Estacionamiento",1,1,"ES",8);
insert into amenities(id,name,category_id,type_amenities_id,languaje,code)values(29,"Esencial",1,2,"ES",9),(30,"Champú",1,2,"ES",10),(31,"Wifi",1,2,"ES",11),(32,"Television",1,2,"ES",12);
insert into amenities(id,name,category_id,type_amenities_id,languaje,code)values(33,"Cocina",1,3,"ES",13),(34,"Lavadero",1,3,"ES",14),(35,"Secadora",1,3,"ES",15),(36,"Tina caliente",1,3,"ES",16),(37,"Estacionamiento",1,3,"ES",17),(38,"Ascensor",1,3,"ES",18),(39,"Piscina",1,3,"ES",19),(40,"Gimnasio",1,3,"ES",20); 
