ALTER TABLE `horas_cocina` ADD `lpp` INT NOT NULL AFTER `dp`, ADD `mpp` INT NOT NULL AFTER `lpp`, ADD `xpp` INT NOT NULL AFTER `mpp`, ADD `jpp` INT NOT NULL AFTER `xpp`, ADD `vpp` INT NOT NULL AFTER `jpp`, ADD `spp` INT NOT NULL AFTER `vpp`, ADD `dpp` INT NOT NULL AFTER `spp`;
ALTER TABLE `horas_repartos` ADD `lpp` INT NOT NULL AFTER `dp`, ADD `mpp` INT NOT NULL AFTER `lpp`, ADD `xpp` INT NOT NULL AFTER `mpp`, ADD `jpp` INT NOT NULL AFTER `xpp`, ADD `vpp` INT NOT NULL AFTER `jpp`, ADD `spp` INT NOT NULL AFTER `vpp`, ADD `dpp` INT NOT NULL AFTER `spp`;
ALTER TABLE `opcionescompra` ADD `portesgratis` INT NOT NULL AFTER `minimo`, ADD `importeportesgratis` DECIMAL(10,2) NOT NULL AFTER `portesgratis`;
ALTER TABLE `opcionescompra` ADD `portesgratismensaje` INT NOT NULL AFTER `importeportesgratis`;
ALTER TABLE `empresa` ADD `email` VARCHAR(200) NOT NULL AFTER `movil`;


ALTER TABLE `destacados` CHANGE `web` `inicio` CHAR(1) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT '1', CHANGE `app` `catalogo` CHAR(1) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT '1';


ALTER TABLE `opcionescompra` ADD `norepartomensaje` VARCHAR(200) NOT NULL AFTER `portesgratismensaje`;

ALTER TABLE `opcionescompra` ADD `cortesia` INT NOT NULL AFTER `tiempoenvio`;

ALTER TABLE `pedidos` ADD `codigoCupon` CHAR(16) NULL AFTER `cupon`;

ALTER TABLE `pedidos` ADD `importe_fidelizacion` DOUBLE(10,2) NULL AFTER `monedero`;