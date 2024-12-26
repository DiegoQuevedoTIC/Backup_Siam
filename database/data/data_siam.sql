/* Clasificacion de creditos */

insert into clasificacion_creditos (CLASIFICACION, DESCRIPCION, NRO_SALARIOS_MIN, NRO_SALARIOS_MAX, PUC_CAUSA_CXC, PUC_CAUSA_INGRESOS, PUC_CAUSA_GASTOS, PUC_CAUSA_CTAS_ORDEN, PORC_CAUSACION, PUC_APROBACION, PUC_CONTRA_PARTIDA, PUC_PROVISION, PUC_PROV_INT, PUC_PROV_INT_REV, PUC_PROV_REV)
values ('1', 'CONSUMO', 1, 9999, '9', '9', '9', '9', 0, '980505', '911505', '51151501', '1', '51151801', '51151501');

insert into clasificacion_creditos (CLASIFICACION, DESCRIPCION, NRO_SALARIOS_MIN, NRO_SALARIOS_MAX, PUC_CAUSA_CXC, PUC_CAUSA_INGRESOS, PUC_CAUSA_GASTOS, PUC_CAUSA_CTAS_ORDEN, PORC_CAUSACION, PUC_APROBACION, PUC_CONTRA_PARTIDA, PUC_PROVISION, PUC_PROV_INT, PUC_PROV_INT_REV, PUC_PROV_REV)
values ('2', 'COMERCIAL', 1, 999, '9', '9', '9', '9', 0, '911505', '980505', null, '1', '511530', null);

insert into clasificacion_creditos (CLASIFICACION, DESCRIPCION, NRO_SALARIOS_MIN, NRO_SALARIOS_MAX, PUC_CAUSA_CXC, PUC_CAUSA_INGRESOS, PUC_CAUSA_GASTOS, PUC_CAUSA_CTAS_ORDEN, PORC_CAUSACION, PUC_APROBACION, PUC_CONTRA_PARTIDA, PUC_PROVISION, PUC_PROV_INT, PUC_PROV_INT_REV, PUC_PROV_REV)
values ('3', 'VIVIENDA', 1, 999, '9', '9', '9', '9', 0, '911505', '980505', null, '1', '511530', null);

insert into clasificacion_creditos (CLASIFICACION, DESCRIPCION, NRO_SALARIOS_MIN, NRO_SALARIOS_MAX, PUC_CAUSA_CXC, PUC_CAUSA_INGRESOS, PUC_CAUSA_GASTOS, PUC_CAUSA_CTAS_ORDEN, PORC_CAUSACION, PUC_APROBACION, PUC_CONTRA_PARTIDA, PUC_PROVISION, PUC_PROV_INT, PUC_PROV_INT_REV, PUC_PROV_REV)
values ('4', 'MICROCREDITO', 1, 999, '9', '9', '9', '9', 9, '911505', '980505', null, '1', '511530', null);

/* Tipo inversiones */
insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (25, 'Préstamos Vehículo');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (27, 'Préstamos Electrodomésticos');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (30, 'Préstamos con recursos externos');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (35, 'Préstamos Educativos');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (40, 'Préstamos Calamidad');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (43, 'Factoring');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (72, 'Fianzas y avales cubiertas');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (75, 'Otros préstamos');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (100, 'Préstamos para vivienda');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (1, 'LIBRE INVERSION');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (5, 'SALUD');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (8, 'COMPRA VEHICULO');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (12, 'ROTATORIO');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (13, 'IMPUESTOS');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (16, 'EXTRAORDINARIO');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (17, 'VIVIENDA');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (19, 'LINEA 2000');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (20, 'EDUCACION');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (21, 'ESPECIAL');

insert into tipo_inversiones (TIPO_INVERSION, DESCRIPCION)
values (15, 'Préstamos Ordinarios');
