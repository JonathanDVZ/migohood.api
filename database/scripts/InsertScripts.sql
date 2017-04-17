 use migohood;
insert into category(name) value("Space"),("Workspace"),("Parking Space"),("Service");
insert into type(name,category_id) values("Apartament",1),("House",1),("B&B",1),("Room",1),("Other",1);
insert into type(name,category_id) values("Bussiness Center",2),("Corporate Office",2),("Coworking Space",2),("Other Workspace",2);
insert into type(name,category_id) values("Driveway",3),("Garage",3),("Car Park",3),("Other",3);
insert into type(name,category_id) values("Tourism",4),("Educational",4),("Cultural",4),("Recreational",4),("Eco",4),("Adventure",4),("Fitness",4);
insert into accommodation(name)values("Entire"),("private"),("Share");
insert into payment(type)values("flex"),("moderate"),("esctric");
insert into calendar(day) value("All Days"),("Sunday"),("Monday"),("Tuesday"),("Wednesday"),("Thursday"),("Friday"),("Sturday");
insert into duration(type) values("Minute"),("Hour"),("Week"),("Month");
insert into type_number(type)values("Fixed"),("Cell");
insert into description(type)values("title"),("address1"),("address2"),("des_neighborhood"),("desc_surroundings"),("desc_length"),("desc_latitud"),("description"),("apt"),("desc_around"),("desc_crib"),("desc_acce"),("socialize"),("available"),("desc_guest"),("desc_note");
insert into house_rules(type)values("Apto para niños(2 a 12 años)"),("Apto para bebes(0 a 2 años)"),("Se Admiten Mascotas"),("Permitido Fumar"),("Eventos fiestas permitidos"),("otras reglas"),("guest_phone"),("guest_email"),("guest_profile"),("guest_payment"),("guest_provided"),("guest_recomendation"),("instructions"),("name_wifi"),("password_wifi");
insert into bed(type)values("double"),("queen"),("individual"),("sofa"),("other");
insert into type_amenities(name)values("Amenities"),("Your_offer"),("Guest_use");
-- ingles--
insert into amenities(name,category_id,type_amenities_id,languaje)values("pets_allowed",1,1,"EN"),("events_allowed",1,1,"EN"),("production_allowed",1,1,"EN"),("family_friendly",1,1,"EN"),("business_guests",1,1,"EN"),("smoke_free",1,1,"EN"),("gym",1,1,"EN"),("parking",1,1,"EN");
insert into amenities(name,category_id,type_amenities_id,languaje)values("essential",1,2,"EN"),("shampoo",1,2,"EN"),("wifi",1,2,"EN"),("tv",1,2,"EN");
insert into amenities(name,category_id,type_amenities_id,languaje)values("kitchen",1,3,"EN"),("laundry_washer",1,3,"EN"),("laundry_dryer",1,3,"EN"),("hot_tub",1,3,"EN"),("parking",1,3,"EN"),("elevator",1,3,"EN"),("pool",1,3,"EN"),("gym",1,3,"EN");
-- español--
insert into amenities(name,category_id,type_amenities_id,languaje)values("mascotas_permitidas",1,1,"ES"),("eventos_permitida",1,1,"ES"),("produccion_permitida",1,1,"ES"),("familia_amable",1,1,"ES"),("cliente_negocio",1,1,"ES"),("libre_de_humo",1,1,"ES"),("gimnasio",1,1,"ES"),("estacionamiento",1,1,"ES");
insert into amenities(name,category_id,type_amenities_id,languaje)values("esencial",1,2,"ES"),("champu",1,2,"ES"),("wifi",1,2,"ES"),("television",1,2,"ES");
insert into amenities(name,category_id,type_amenities_id,languaje)values("cocina",1,3,"ES"),("lavadero",1,3,"ES"),("secadora",1,3,"ES"),("tina_caliente",1,3,"ES"),("estacionamiento",1,3,"ES"),("ascensor",1,3,"ES"),("piscina",1,3,"ES"),("gimnasio",1,3,"ES");