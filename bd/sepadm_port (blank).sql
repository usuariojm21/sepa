/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 100138
 Source Host           : localhost:3306
 Source Schema         : sepadm_port

 Target Server Type    : MySQL
 Target Server Version : 100138
 File Encoding         : 65001

 Date: 18/04/2020 17:54:44
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ciclos
-- ----------------------------
DROP TABLE IF EXISTS `ciclos`;
CREATE TABLE `ciclos`  (
  `ciclo` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `desciclo` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `desdeciclo` datetime(0) NULL DEFAULT NULL,
  `hastaciclo` datetime(0) NULL DEFAULT NULL,
  `estatus` int(1) NULL DEFAULT NULL,
  PRIMARY KEY (`ciclo`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for datos
-- ----------------------------
DROP TABLE IF EXISTS `datos`;
CREATE TABLE `datos`  (
  `n` int(100) NULL DEFAULT NULL,
  `tipoempresa` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `rif` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `razonsocial` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `estado` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `municipio` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ciudad` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `codsica` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `encargado` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `direccion` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `cap_almacenamiento_inst` double(12, 2) NULL DEFAULT NULL,
  `cap_almcenamiento_oper` double(12, 2) NULL DEFAULT NULL,
  `cap_procesamiento_inst` double(12, 2) NULL DEFAULT NULL,
  `cap_procesamiento_oper` double(12, 2) NULL DEFAULT NULL,
  `cap_empaquetado_inst` double(12, 2) NULL DEFAULT NULL,
  `cap_empaquetado_oper` double(12, 2) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for detalle_costo
-- ----------------------------
DROP TABLE IF EXISTS `detalle_costo`;
CREATE TABLE `detalle_costo`  (
  `idcosto` int(10) NOT NULL AUTO_INCREMENT,
  `codrubro` int(10) NULL DEFAULT NULL,
  `desrubro` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `unidadmedida` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `tipocosto` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `ciclo` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `rifentidad` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `costoitem` double(10, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`idcosto`) USING BTREE,
  INDEX `codrubro`(`codrubro`) USING BTREE,
  INDEX `ciclo`(`ciclo`) USING BTREE,
  INDEX `rifentidad`(`rifentidad`) USING BTREE,
  CONSTRAINT `detalle_costo_ibfk_2` FOREIGN KEY (`ciclo`) REFERENCES `ciclos` (`ciclo`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `detalle_costo_ibfk_3` FOREIGN KEY (`rifentidad`) REFERENCES `entidad` (`rifentidad`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `detalle_costo_ibfk_4` FOREIGN KEY (`codrubro`) REFERENCES `rubros` (`codrubro`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for detalle_intencion
-- ----------------------------
DROP TABLE IF EXISTS `detalle_intencion`;
CREATE TABLE `detalle_intencion`  (
  `nrointencion` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `nrofinan` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  `ciclo` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `desciclo` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `ced_rif` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `razonsocialproductor` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codundprod` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nomundprod` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codgrupo` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `desgrupo` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codsubgrupo` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `dessubgrupo` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codrubro` int(10) NULL DEFAULT NULL,
  `desrubro` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codtenencia` int(2) NULL DEFAULT NULL,
  `destenencia` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `rifentidad` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `razonsocialentidad` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `cedtecnico` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `nomtecnico` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `ha_intencion` double(10, 2) NULL DEFAULT NULL,
  `ha_financiadas` double(10, 2) NULL DEFAULT NULL,
  `ha_sembradas` double(10, 2) NULL DEFAULT NULL,
  `ha_perdidas` double(10, 2) NULL DEFAULT NULL,
  `ha_cosechadas` double(10, 2) NULL DEFAULT NULL,
  `rendimiento_kilos` double(10, 2) NULL DEFAULT NULL,
  `fecha_intencion` datetime(0) NULL DEFAULT NULL,
  `fecha_financiada` datetime(0) NULL DEFAULT NULL,
  `fecha_siembra` datetime(0) NULL DEFAULT NULL,
  `fecha_cosecha` datetime(0) NULL DEFAULT NULL,
  `producion_esperada` double(10, 2) NULL DEFAULT NULL,
  `estado` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `municipio` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `parroquia` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `sector` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  `estatus` int(1) NULL DEFAULT NULL,
  INDEX `codtenencia`(`codtenencia`) USING BTREE,
  INDEX `ciclo`(`ciclo`) USING BTREE,
  INDEX `codrubro`(`codrubro`) USING BTREE,
  INDEX `ced_rif`(`ced_rif`) USING BTREE,
  INDEX `codundprod`(`codundprod`) USING BTREE,
  INDEX `cedtecnico`(`cedtecnico`) USING BTREE,
  INDEX `rifentidad`(`rifentidad`) USING BTREE,
  INDEX `codestado`(`estado`) USING BTREE,
  INDEX `codsector`(`sector`) USING BTREE,
  INDEX `nrointencion`(`nrointencion`) USING BTREE,
  CONSTRAINT `detalle_intencion_ibfk_11` FOREIGN KEY (`ced_rif`) REFERENCES `productor` (`ced_rif`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `detalle_intencion_ibfk_14` FOREIGN KEY (`cedtecnico`) REFERENCES `tecnico` (`cedtecnico`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `detalle_intencion_ibfk_15` FOREIGN KEY (`rifentidad`) REFERENCES `entidad` (`rifentidad`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `detalle_intencion_ibfk_19` FOREIGN KEY (`codrubro`) REFERENCES `rubros` (`codrubro`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `detalle_intencion_ibfk_21` FOREIGN KEY (`codtenencia`) REFERENCES `tenencia` (`codtenencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `detalle_intencion_ibfk_23` FOREIGN KEY (`nrointencion`) REFERENCES `intencion` (`nrointencion`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detalle_intencion_ibfk_24` FOREIGN KEY (`codundprod`) REFERENCES `unidadproduccion` (`codundprod`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `detalle_intencion_ibfk_4` FOREIGN KEY (`ciclo`) REFERENCES `ciclos` (`ciclo`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for entidad
-- ----------------------------
DROP TABLE IF EXISTS `entidad`;
CREATE TABLE `entidad`  (
  `rifentidad` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `razonsocial` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `dirfiscal` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `telefonos` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `correoe` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `representante` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `telfrepresentante` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `paginaweb` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `estado` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `municipio` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `parroquia` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `sector` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `estatus` int(1) NULL DEFAULT NULL,
  PRIMARY KEY (`rifentidad`) USING BTREE,
  INDEX `razonsocial`(`razonsocial`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for estados
-- ----------------------------
DROP TABLE IF EXISTS `estados`;
CREATE TABLE `estados`  (
  `codestado` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nomestado` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  PRIMARY KEY (`codestado`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for grupo
-- ----------------------------
DROP TABLE IF EXISTS `grupo`;
CREATE TABLE `grupo`  (
  `codgrupo` int(10) NOT NULL AUTO_INCREMENT,
  `desgrupo` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`codgrupo`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for intencion
-- ----------------------------
DROP TABLE IF EXISTS `intencion`;
CREATE TABLE `intencion`  (
  `nrointencion` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ciclo` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rifentidad` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `fecha_intencion` datetime(0) NOT NULL,
  `ha_total_hectareas` double(10, 2) NOT NULL,
  `estado` int(1) NULL DEFAULT NULL,
  PRIMARY KEY (`nrointencion`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for municipios
-- ----------------------------
DROP TABLE IF EXISTS `municipios`;
CREATE TABLE `municipios`  (
  `codmunicipio` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nommunicipio` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codestado` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  `nomestado` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  PRIMARY KEY (`codmunicipio`) USING BTREE,
  INDEX `codestado`(`codestado`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for parroquias
-- ----------------------------
DROP TABLE IF EXISTS `parroquias`;
CREATE TABLE `parroquias`  (
  `codparroquia` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nomparroquia` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `nommunicipio` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  `nomestado` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  `codmunicipio` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codestado` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  PRIMARY KEY (`codparroquia`) USING BTREE,
  INDEX `codmunicipio`(`codmunicipio`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for productor
-- ----------------------------
DROP TABLE IF EXISTS `productor`;
CREATE TABLE `productor`  (
  `ced_rif` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `razonsocial` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `dirfiscal` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `representante` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `telefonos` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `correoe` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `pagina` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `estatus` int(1) NOT NULL,
  PRIMARY KEY (`ced_rif`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for productor_entidad
-- ----------------------------
DROP TABLE IF EXISTS `productor_entidad`;
CREATE TABLE `productor_entidad`  (
  `rifentidad` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `razonsocialentidad` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `ced_rif` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `razonsocialproductor` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `usuario` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  INDEX `ced_rif`(`ced_rif`) USING BTREE,
  INDEX `rifentidad`(`rifentidad`) USING BTREE,
  INDEX `usuario`(`usuario`) USING BTREE,
  CONSTRAINT `productor_entidad_ibfk_1` FOREIGN KEY (`ced_rif`) REFERENCES `productor` (`ced_rif`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `productor_entidad_ibfk_2` FOREIGN KEY (`rifentidad`) REFERENCES `entidad` (`rifentidad`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rubros
-- ----------------------------
DROP TABLE IF EXISTS `rubros`;
CREATE TABLE `rubros`  (
  `codrubro` int(10) NOT NULL AUTO_INCREMENT,
  `desrubro` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codsubgrupo` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `dessubgrupo` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codgrupo` int(10) NULL DEFAULT NULL,
  `desgrupo` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`codrubro`) USING BTREE,
  INDEX `codsector`(`codgrupo`) USING BTREE,
  INDEX `codsubgrupo`(`codsubgrupo`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rubros_undprod
-- ----------------------------
DROP TABLE IF EXISTS `rubros_undprod`;
CREATE TABLE `rubros_undprod`  (
  `codrubro` int(10) NULL DEFAULT NULL,
  `desrubro` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codsubsector` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `dessubsector` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codsector` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `dessector` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codundprod` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `desundprod` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  INDEX `rubros_ibfk_1`(`codsubsector`) USING BTREE,
  INDEX `codsector`(`codsector`) USING BTREE,
  INDEX `codrubro`(`codrubro`) USING BTREE,
  INDEX `codundprod`(`codundprod`) USING BTREE,
  CONSTRAINT `rubros_undprod_ibfk_3` FOREIGN KEY (`codrubro`) REFERENCES `rubros` (`codrubro`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `rubros_undprod_ibfk_4` FOREIGN KEY (`codundprod`) REFERENCES `unidadproduccion` (`codundprod`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sectores
-- ----------------------------
DROP TABLE IF EXISTS `sectores`;
CREATE TABLE `sectores`  (
  `codsector` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nomsector` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `grupo` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codparroquia` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `nomparroquia` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `nommunicipio` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codestado` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `nomestado` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`codsector`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for sectores (vieja)
-- ----------------------------
DROP TABLE IF EXISTS `sectores (vieja)`;
CREATE TABLE `sectores (vieja)`  (
  `codsector` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomsector` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codparroquia` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `nomparroquia` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codmunicipio` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `nommunicipio` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codestado` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  `nomestado` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`codsector`) USING BTREE,
  INDEX `codparroquia`(`codparroquia`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for subgrupo
-- ----------------------------
DROP TABLE IF EXISTS `subgrupo`;
CREATE TABLE `subgrupo`  (
  `codgrupo` int(10) NULL DEFAULT NULL,
  `desgrupo` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codsubgrupo` int(10) NOT NULL AUTO_INCREMENT,
  `dessubgrupo` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`codsubgrupo`) USING BTREE,
  INDEX `codgrupo`(`codgrupo`) USING BTREE,
  CONSTRAINT `subgrupo_ibfk_1` FOREIGN KEY (`codgrupo`) REFERENCES `grupo` (`codgrupo`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tecnico
-- ----------------------------
DROP TABLE IF EXISTS `tecnico`;
CREATE TABLE `tecnico`  (
  `cedtecnico` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nomtecnico` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `dirfiscal` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `telefonos` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `correoe` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `estado` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `municipio` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `parroquia` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `sector` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `estatus` int(1) NULL DEFAULT NULL,
  PRIMARY KEY (`cedtecnico`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tecnico_entidad
-- ----------------------------
DROP TABLE IF EXISTS `tecnico_entidad`;
CREATE TABLE `tecnico_entidad`  (
  `rifentidad` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `razonsocial` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `cedtecnico` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `nomtecnico` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  INDEX `rifentidad`(`rifentidad`) USING BTREE,
  INDEX `cedtecnico`(`cedtecnico`) USING BTREE,
  CONSTRAINT `tecnico_entidad_ibfk_1` FOREIGN KEY (`rifentidad`) REFERENCES `entidad` (`rifentidad`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `tecnico_entidad_ibfk_2` FOREIGN KEY (`cedtecnico`) REFERENCES `tecnico` (`cedtecnico`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tenencia
-- ----------------------------
DROP TABLE IF EXISTS `tenencia`;
CREATE TABLE `tenencia`  (
  `codtenencia` int(2) NOT NULL AUTO_INCREMENT,
  `destenencia` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `estatus` int(1) NULL DEFAULT NULL,
  PRIMARY KEY (`codtenencia`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for undprod_productor
-- ----------------------------
DROP TABLE IF EXISTS `undprod_productor`;
CREATE TABLE `undprod_productor`  (
  `ced_rif` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `razonsocial` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codundprod` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `nomundprod` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codtenencia` int(2) NULL DEFAULT NULL,
  `destenencia` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `codfichapredial` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `hadisponibles` double(20, 0) NULL DEFAULT NULL,
  INDEX `codundprod`(`codundprod`) USING BTREE,
  INDEX `ced_rif`(`ced_rif`) USING BTREE,
  INDEX `codtenencia`(`codtenencia`) USING BTREE,
  INDEX `codfichapredial`(`codfichapredial`) USING BTREE,
  CONSTRAINT `undprod_productor_ibfk_2` FOREIGN KEY (`ced_rif`) REFERENCES `productor` (`ced_rif`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `undprod_productor_ibfk_5` FOREIGN KEY (`codtenencia`) REFERENCES `tenencia` (`codtenencia`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `undprod_productor_ibfk_6` FOREIGN KEY (`codundprod`) REFERENCES `unidadproduccion` (`codundprod`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for unidadproduccion
-- ----------------------------
DROP TABLE IF EXISTS `unidadproduccion`;
CREATE TABLE `unidadproduccion`  (
  `codundprod` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `codfichapredial` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `urldocumentoficha` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `nomundprod` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `dirundprod` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `estado` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `municipio` varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `parroquia` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `sector` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `hatotal` double(8, 2) NULL DEFAULT NULL,
  `haproductivas` double(8, 2) NULL DEFAULT NULL,
  `hadisponibles` double(8, 2) NULL DEFAULT NULL,
  `coorprinlat` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `coorprinlog` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `estatus` int(1) NULL DEFAULT NULL,
  PRIMARY KEY (`codundprod`) USING BTREE,
  INDEX `codundprod`(`codundprod`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios`  (
  `rifentidad` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `ced_rif` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `usuario` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `clave` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `nivel` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `filtro` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`usuario`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Procedure structure for cargar_productor_entidad
-- ----------------------------
DROP PROCEDURE IF EXISTS `cargar_productor_entidad`;
delimiter ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `cargar_productor_entidad`(In `erif` VARCHAR(20), `prif` VARCHAR(50), `prazons` VARCHAR(50))
BEGIN
	
	INSERT INTO productor_entidad (rifentidad,ced_rif,razonsocialproductor) VALUES(@erif,@prif,@prazons);
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
