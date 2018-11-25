/*
Navicat MySQL Data Transfer

Source Server         : mysql1
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : loan-02-06

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2018-11-25 20:16:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for v1_fields
-- ----------------------------
DROP TABLE IF EXISTS `v1_fields`;
CREATE TABLE `v1_fields` (
  `fields_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '字段ID，自增',
  `title` varchar(248) NOT NULL COMMENT '字段名称',
  `identifier` varchar(64) NOT NULL COMMENT '字段标识串',
  `field_type` varchar(248) DEFAULT NULL COMMENT '字段存储类型',
  `field_length` int(8) DEFAULT NULL COMMENT '字段长度',
  `form_rule` varchar(255) DEFAULT NULL COMMENT '字段验证表达式',
  `note` varchar(248) DEFAULT NULL COMMENT '字段内容备注',
  `form_type` varchar(128) DEFAULT NULL COMMENT '表单类型',
  `form_style` varchar(248) DEFAULT NULL COMMENT '表单CSS',
  `form_script` text COMMENT '表单脚本',
  `format` varchar(128) DEFAULT NULL COMMENT '格式化方式',
  `default_value` varchar(128) DEFAULT NULL COMMENT '默认值',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '255' COMMENT '排序',
  `const_category` varchar(128) DEFAULT NULL COMMENT '常量类型名称',
  `ext` text COMMENT '扩展内容',
  `addition` varchar(32) DEFAULT NULL COMMENT '所属表名',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`fields_id`)
) ENGINE=InnoDB AUTO_INCREMENT=803 DEFAULT CHARSET=utf8 COMMENT='字段管理器';

-- ----------------------------
-- Records of v1_fields
-- ----------------------------
INSERT INTO `v1_fields` VALUES ('604', '姓名', 'name', 'varchar', '20', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_spouse', null);
INSERT INTO `v1_fields` VALUES ('605', '证件类型', 'idcard_type', 'tinyint', '2', ' required', '', 'select', '', '', '', '', '255', 'IDCARD_TYPE', '', 'proxy_customer_spouse', null);
INSERT INTO `v1_fields` VALUES ('606', '单位性质', 'company_type', 'int', '10', ' required', '', 'select', '', null, '', '', '255', 'UNIT_NATURE', '', 'proxy_customer_spouse', null);
INSERT INTO `v1_fields` VALUES ('607', '身份证号', 'idcard', 'varchar', '20', 'required isIdCardNo', '', 'text', '', '', '', '', '255', '', '', 'proxy_customer_spouse', null);
INSERT INTO `v1_fields` VALUES ('608', '受教育程度', 'education', 'int', '10', ' required', '', 'select', '', null, '', '', '255', 'EDUCATION', '', 'proxy_customer_spouse', null);
INSERT INTO `v1_fields` VALUES ('609', '年收入', 'year_income', 'int', '10', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_spouse', null);
INSERT INTO `v1_fields` VALUES ('610', '手机', 'moblie', 'varchar', '15', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_spouse', null);
INSERT INTO `v1_fields` VALUES ('611', '工作单位', 'company_name', 'varchar', '100', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_spouse', null);
INSERT INTO `v1_fields` VALUES ('612', '职务', 'work_title', 'varchar', '50', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_spouse', null);
INSERT INTO `v1_fields` VALUES ('613', '区号', 'company_tel_zone', 'varchar', '15', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_spouse', null);
INSERT INTO `v1_fields` VALUES ('614', '电话', 'company_tel', 'varchar', '15', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_spouse', null);
INSERT INTO `v1_fields` VALUES ('615', '是否知晓贷款', 'is_know', 'tinyint', '1', ' required', '', 'select', '', null, '', '', '255', '', '', 'proxy_customer_spouse', null);
INSERT INTO `v1_fields` VALUES ('616', '是否是担保人', 'is_guarantor', 'int', '1', ' required', '', 'select', '', null, '', '', '255', '', '', 'proxy_customer_spouse', null);
INSERT INTO `v1_fields` VALUES ('617', '地址', 'address', 'varchar', '100', ' required', '', 'address', '', null, '', '', '255', '', '', 'proxy_customer_spouse', null);
INSERT INTO `v1_fields` VALUES ('618', '单位地址', 'company_address', 'varchar', '50', ' required', '', 'address', '', null, '', '', '255', '', '', 'proxy_customer_spouse', null);
INSERT INTO `v1_fields` VALUES ('619', '户籍地址', 'idcard_address', 'varchar', '50', ' required', '', 'address', '', null, '', '', '255', '', '', 'proxy_customer_spouse', null);
INSERT INTO `v1_fields` VALUES ('620', '工作单位', 'company_name', 'varchar', '100', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('621', '单位属性', 'company_type', 'int', '4', ' required', '', 'select', '', '', '', '', '255', 'UNIT_NATURE', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('622', '单位属性描述', 'company_type_desc', 'varchar', '200', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('623', '所属行业', 'industry', 'int', '4', ' required', '', 'select', '', '', '', '', '255', 'INDUSTRY', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('624', '所属行业描述', 'industry_desc', 'varchar', '200', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('625', '部门名称', 'department_name', 'varchar', '50', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('626', '职务名称', 'work_title', 'varchar', '50', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('627', '区号', 'company_tel_zone', 'varchar', '6', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('628', '电话', 'company_tel', 'varchar', '15', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('629', '分机号', 'company_tel_ext', 'varchar', '6', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('630', '地址', 'company_address', 'varchar', '100', ' required', '', 'address', '', null, '', '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('631', '邮编', 'company_address_postcode', 'varchar', '6', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('632', '雇佣类型', 'employ_type', 'varchar', '50', ' required', '', 'select', '', null, '', '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('633', '起始服务时间', 'start_work_date', 'int', '11', ' required', '', 'datetime', '', null, '', '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('634', '现单位工作年限', 'work_years', 'tinyint', '2', ' required', '', 'number', '', null, '', '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('635', '行业工作年限', 'industry_work_years', 'tinyint', '2', ' required', '', 'number', '', null, '', '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('636', '与申请人关系', 'relationship', 'int', '10', ' required', '', 'select', '', null, '', '', '255', 'RELATIONSHIP', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('637', '姓名', 'name', 'varchar', '64', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('638', '生日', 'birthday', 'int', '11', ' required', '', 'datetime', '', null, 'Y-d-m', '', '255', '', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('639', '性别', 'sex', 'tinyint', '1', ' required', '', 'select', '', '', '', '', '255', 'custom', 'a:2:{i:1;s:3:\"男\";i:2;s:3:\"女\";}', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('640', '手机', 'moblie', 'varchar', '15', ' required isMobile', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('641', '婚姻状况', 'marriage', 'int', '10', ' required', '', 'select', '', null, '', '', '255', 'MARRIAGE', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('642', '证件号码', 'idcard', 'varchar', '22', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('643', '证件类型', 'idcard_type', 'tinyint', '2', ' required', '', 'select', '', '', '', '', '255', 'IDCARD_TYPE', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('644', '户籍地址', 'idcard_address', 'varchar', '50', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('645', '是否本地户口', 'is_local_residentstiny', 'tinyint', '1', ' required', '', 'select', '', '', '', '', '255', 'custom', 'a:2:{i:1;s:3:\"是\";i:0;s:3:\"否\";}', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('646', '单位名称', 'company_name', 'varchar', '64', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('647', '单位电话区号', 'company_tel_zone', 'varchar', '8', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('648', '单位电话', 'company_tel', 'varchar', '11', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('649', '单位性质', 'company_type', 'int', '10', ' required', '', 'select', '', null, '', '', '255', '', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('650', '单位地址', 'company_address', 'varchar', '50', ' required', '', 'address', '', null, '', '', '255', '', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('651', '职务名称', 'work_title', 'varchar', '248', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('652', '工作年限', 'work_years', 'tinyint', '2', ' required', '', 'number', '', null, '', '', '255', '', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('653', '年收入', 'year_income', 'int', '10', ' required', '', 'number', '', null, '', '', '255', '', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('654', '住宅电话区号', 'home_tel_zone', 'varchar', '6', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('655', '住宅电话', 'home_tel', 'varchar', '15', ' required', '', 'number', '', null, '', '', '255', '', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('656', '是否知晓贷款', 'is_know', 'tinyint', '1', ' required', '', 'select', '', '', '', '', '255', 'custom', 'a:2:{i:1;s:3:\"是\";i:0;s:3:\"否\";}', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('657', '现住房情况', 'estate_status', 'int', '10', ' required', '', 'select', '', null, '', '', '255', 'ESTATE', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('658', '地址', 'address', 'varchar', '100', ' required', '', 'address', '', null, '', '', '255', '', '', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('659', '是否是担保人', 'is_guarantor', 'tinyint', '1', ' required', '', 'select', '', '', '', '', '255', 'custom', 'a:2:{i:1;s:3:\"是\";i:0;s:3:\"否\";}', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('660', '是否是紧急联系人', 'is_urgent', 'tinyint', '1', ' required', '', 'select', '', '', '', '', '255', 'custom', 'a:2:{i:1;s:3:\"是\";i:0;s:3:\"否\";}', 'proxy_customer_contact', null);
INSERT INTO `v1_fields` VALUES ('661', '车辆号码', 'car_no', 'varchar', '15', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_car', null);
INSERT INTO `v1_fields` VALUES ('662', '车辆品牌', 'car_brand', 'varchar', '20', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_car', null);
INSERT INTO `v1_fields` VALUES ('663', '车辆型号', 'car_model', 'varchar', '20', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_car', null);
INSERT INTO `v1_fields` VALUES ('664', '购买日期', 'car_buy_date', 'int', '10', ' required', '', 'datetime', '', '', 'Y-m-d', '', '255', '', 'a:0:{}', 'proxy_customer_car', null);
INSERT INTO `v1_fields` VALUES ('665', '购买价格', 'car_buy_price', 'int', '10', ' required', '', 'number', '', null, '', '', '255', '', '', 'proxy_customer_car', null);
INSERT INTO `v1_fields` VALUES ('666', '购买方式', 'car_buy_way', 'int', '4', ' required', '', 'select', '', null, '', '', '255', 'BUY_PATTERNS', '', 'proxy_customer_car', null);
INSERT INTO `v1_fields` VALUES ('667', '月收入', 'month_income', 'int', '11', ' required', '', 'number', '', null, '', '', '255', '', '', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('668', '月支出', 'month_expend', 'int', '11', ' required', '', 'number', '', null, '', '', '255', '', '', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('669', '房贷月供', 'month_house_loan', 'int', '11', ' required', '', 'number', '', null, '', '', '255', '', '', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('670', '家庭资产描述', 'assets_desc', 'varchar', '255', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('671', '年收入', 'year_income', 'int', '11', ' required', '', 'number', '', null, '', '', '255', '', '', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('672', '当地房产类型', 'local_estate_type', 'int', '10', ' required', '', 'select', '', '', '', '', '255', 'PROXY_ASSET_TYPE', '', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('674', '居住地址', 'live_address', 'varchar', '200', ' required', '', 'address', '', null, '', '', '255', '', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('675', '居住邮编', 'live_postcode', 'varchar', '6', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('676', '居住状况', 'estate_status', 'int', '4', ' required', '', 'select', '', '\r\n$(\"select[name=\'customer[estate_status]\']\").on(\'change\',function(){\r\n	if($(this).val() == 91 ) {\r\n		$(\'<span>租金<input type=\"number\" name=\"customer[estate_desc]\" id=\"estate_desc\" value=\"\" class=\"span1\" />元</span>\').insertAfter(this);\r\n	} else {\r\n		$(\"input[name=\'customer[estate_desc]\']\").val(\'\').closest(\'span\').remove();\r\n	}\r\n}).trigger(\'change\');', '', '', '255', 'ESTATE', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('677', '客户类型', 'customer_type', 'tinyint', '1', ' required', '', 'select', '', '', '', '', '255', 'custom', 'a:2:{i:0;s:9:\"新客户\";i:1;s:9:\"老客户\";}', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('678', '姓名', 'name', 'varchar', '50', ' required', '', 'text', '', '', '', '', '255', '', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('679', '生日', 'birthday', 'int', '11', ' required', '', 'datetime', '', null, 'Y-m-d', '', '255', '', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('680', '母亲姓氏', 'mother_surname', 'varchar', '4', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('681', '身份证号', 'idcard', 'varchar', '20', 'required isIdCardNo fn=\"fn2016070902\" fn-error=\"身份证号已存在\"', '', 'text', '', 'function fn2016070902(val,e){\r\n	var repet = true;\r\n	$.ajax({\r\n		url:\'/index.php?m=proxy_customer&a=check_repet\',\r\n		data:{\r\n			idcard:val,\r\n			pk:$(\'[name=\"customer[proxy_customer_id]\"]\').val()\r\n		},\r\n		type:\'post\',\r\n		async:false,\r\n		success:function(response){\r\n			if(response.code){\r\n				repet = false;\r\n			}\r\n		}\r\n	});\r\n	if(repet)return false;\r\n\r\n	if( $(\'[name=\"customer[idcard_type]\"]\').find(\"option:selected\").text() == \'身份证\' ){\r\n		var arr = parseIdCard(val);\r\n		$(\'[name=\"customer[sex]\"]\').val(arr[\'sex_code\']);\r\n		$(\'[name=\"customer[birthday]\"]\').val(arr[\'birthday\']);\r\n	} else if( !$(\'[name=\"customer[idcard_type]\"]\').find(\"option:selected\").text() ) {\r\n		var arr = parseIdCard(val);\r\n		$(\'[name=\"customer[sex]\"]\').val(arr[\'sex_code\']);\r\n		$(\'[name=\"customer[birthday]\"]\').val(arr[\'birthday\']);\r\n	}\r\n}', '', '', '255', '', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('682', '证件类型', 'idcard_type', 'tinyint', '2', ' required success=\"fn2016070901\"', '', 'select', '', 'function fn2016070901(val,e){\r\n	var idcard_type_text = $(\'[name=\"customer[idcard_type]\"]\').find(\"option:selected\").text();\r\n	if(idcard_type_text == \'身份证\')\r\n		$(\'[name=\"customer[idcard]\"]\').removeAttr(\'isHuZhao\').attr(\'isIdCardNO\',true);\r\n	else if(idcard_type_text == \'护照\')\r\n		$(\'[name=\"customer[idcard]\"]\').removeAttr(\'isIdCardNO\').attr(\'isHuZhao\',true);\r\n}', '', '', '255', 'IDCARD_TYPE', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('683', '户籍地址', 'idcard_address', 'varchar', '50', ' required', '', 'address', '', null, '', '', '255', '', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('684', '是否本地户口', 'is_local_residentstiny', 'tinyint', '1', ' required', '', 'select', '', '', '', '', '255', 'custom', 'a:2:{i:1;s:3:\"是\";i:0;s:3:\"否\";}', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('685', '身份证有效期是否大于7天', 'is_idcard_enable_7days', 'tinyint', '1', ' required', '', 'select', '', '', '', '', '255', 'custom', 'a:2:{i:1;s:3:\"是\";i:0;s:3:\"否\";}', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('686', '邮箱', 'email', 'varchar', '30', 'required  isEmail', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('687', '手机号码', 'mobile', 'varchar', '15', 'required isMobile fn=\\\"fn2016070903\\\" fn-error=\\\"手机号已存在\\\"', '', 'text', '', 'function fn2016070903(val,e){\r\n	var repet = true;\r\n	$.ajax({\r\n		url:\\\'/index.php?m=proxy_customer&a=check_repet\\\',\r\n		data:{\r\n			mobile:val,\r\n			pk:$(\\\'[name=\\\"customer[proxy_customer_id]\\\"]\\\').val()\r\n		},\r\n		type:\\\'post\\\',\r\n		async:false,\r\n		success:function(response){\r\n			if(response.code){\r\n				repet = false;\r\n			}\r\n		}\r\n	});\r\n	if(repet)return false;\r\n}', '', '', '255', '', 'a:0:{}', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('688', '家庭电话号码区号', 'home_tel_zone', 'varchar', '6', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('689', '家庭电话号码', 'home_tel', 'varchar', '15', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('690', '婚姻状况', 'marriage', 'int', '1', ' required class=\"J-marriage\"', '', 'select', '', '$(function(){\r\n	var marr = $(\".J-marriage\").val();\r\n	if(marr!=6 && marr!=84){\r\n		$(\"#customer_spouse\").hide();\r\n	}\r\n	$(\".J-marriage\").on(\"change\",function(){\r\n		var vl = $(this).val();\r\n		if(vl!=6 && vl!=84){\r\n			$(\"#customer_spouse\").hide();\r\n		}else{\r\n			$(\"#customer_spouse\").show();\r\n		}\r\n	});\r\n});', '', '', '255', 'MARRIAGE', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('691', '性别', 'sex', 'int', '1', ' required', '', 'select', '', '', '', '', '255', 'custom', 'a:2:{i:1;s:3:\"男\";i:2;s:3:\"女\";}', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('692', '社保电脑号', 'social_security_number', 'varchar', '30', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('693', '社保公积金额度', 'social_security_value', 'int', '11', ' required', '', 'text', '', null, '', '', '255', '', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('694', '车辆品牌', 'car_brand', 'varchar', '20', ' required', null, 'text', null, null, null, '', '255', null, '', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('695', '车辆型号', 'car_model', 'varchar', '20', ' required', null, 'text', null, null, null, '', '255', null, '', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('696', '车辆类型', 'car_type', 'varchar', '50', ' required', null, 'text', null, null, null, '', '255', null, '', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('697', '车辆颜色', 'car_color', 'varchar', '20', ' required', null, 'text', null, null, null, '', '255', null, '', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('698', '车架号', 'car_frame_no', 'varchar', '30', ' required', null, 'text', null, null, null, '', '255', null, '', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('699', '车辆装饰项目', 'car_decorate', 'varchar', '100', ' required', null, 'text', null, null, null, '', '255', null, '', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('700', '车辆状况', 'car_status', 'varchar', '50', ' required', null, 'text', null, null, null, '', '255', null, '', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('701', '车辆登记日期(二手车适用)', 'car_regist_date', 'date', '10', '', null, 'datetime', null, null, null, '', '255', null, '', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('702', '车龄(二手车适用)年', 'car_age', 'int', '10', '', null, 'number', null, null, null, '', '255', null, '', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('703', '行使里程数(公里)', 'car_run_km', 'int', '10', '', null, 'number', null, null, null, '', '255', null, '', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('704', '市场指导价', 'market_price', 'float', '10', ' required', null, 'number', null, null, null, '', '255', null, '', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('705', '车辆当前估值', 'assessment_price', 'float', '10', ' required', null, 'number', null, null, null, '', '255', null, '', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('706', '牌照保证金金额', 'license_bail', 'float', '10', ' required', null, 'number', null, null, null, '', '255', null, '', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('707', '购买日期', 'car_buy_date', 'date', '10', ' required', null, 'datetime', null, null, null, '', '255', null, '', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('708', '购买价格', 'car_buy_price', 'int', '10', ' required', null, 'number', null, null, null, '', '255', null, '', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('709', '是否安装GPS', 'is_mount_gps', 'int', '1', ' required', '', 'select', '', '', null, '', '255', 'custom', 'a:2:{i:1;s:3:\"是\";i:0;s:3:\"否\";}', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('710', 'GPS费用', 'gps_fee', 'float', '10', ' required', null, 'number', null, null, null, '', '255', null, '', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('711', '是否办理抵押登记', 'is_mortgage_register', 'int', '1', ' required', '', 'select', '', '', null, '', '255', 'custom', 'a:2:{i:1;s:3:\"是\";i:0;s:3:\"否\";}', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('712', '抵押费', 'mortgage_fee', 'float', '10', ' required', null, 'number', null, null, null, '', '255', null, '', 'proxy_contract_car', null);
INSERT INTO `v1_fields` VALUES ('713', '子女数', 'children_number', 'int', '11', ' required', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('714', '子女情况', 'children_condition', 'int', '4', '', '', 'select', '', '', null, '', '255', 'PROXY_CHILDREN_CONDITION', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('715', '性别', 'sex', 'int', '1', ' required', '', 'select', '', '', null, '', '255', 'custom', 'a:2:{i:1;s:3:\"男\";i:2;s:3:\"女\";}', 'proxy_customer_spouse', null);
INSERT INTO `v1_fields` VALUES ('716', '贷款用途', 'loan_usage', 'varchar', '255', ' required', null, 'text', null, null, null, '', '255', null, '', 'proxy_contract', null);
INSERT INTO `v1_fields` VALUES ('717', '贷款用途说明', 'loan_usage_desc', 'varchar', '50', ' required', null, 'text', null, null, null, '', '255', null, '', 'proxy_contract', null);
INSERT INTO `v1_fields` VALUES ('718', '进件类型', 'const_jjlx_id', 'int', '4', ' required', null, 'text', null, null, null, '', '255', null, '', 'proxy_contract', null);
INSERT INTO `v1_fields` VALUES ('719', '还款方式', 'const_jxfs_id', 'int', '4', ' required', null, 'text', null, null, null, '', '255', null, '', 'proxy_contract', null);
INSERT INTO `v1_fields` VALUES ('720', '户口所在地', 'registered_location', 'int', '4', '', '', 'select', '', '', null, '', '255', 'PROXY_REGISTERED_LOCATION', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('721', '子女就读学校', 'children_school', 'varchar', '128', '', '', 'text', '', '', null, '', '255', '', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('722', '子女所在班级', 'children_grade', 'varchar', '64', '', '', 'text', '', '', null, '', '255', '', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('723', '共同居住的家庭成员', 'family_member_num', 'int', '4', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('724', '详细地址', 'address', 'varchar', '128', '', '', 'address', '', '', null, '', '255', '', '', 'proxy_customer_room', null);
INSERT INTO `v1_fields` VALUES ('725', '房产建筑面积', 'room_area', 'float', '12', '', '', 'text', '', '', null, '', '255', '', '', 'proxy_customer_room', null);
INSERT INTO `v1_fields` VALUES ('726', '共有权人', 'room_ownership', 'varchar', '20', '', null, 'text', null, null, null, '', '255', null, '', 'proxy_customer_room', null);
INSERT INTO `v1_fields` VALUES ('727', '购买方式', 'room_buy_way', 'int', '4', '', '', 'select', '', '', null, '', '255', 'BUY_PATTERNS', '', 'proxy_customer_room', null);
INSERT INTO `v1_fields` VALUES ('728', '购买日期', 'room_buy_date', 'int', '10', '', '', 'datetime', '', '', null, '', '255', '', '', 'proxy_customer_room', null);
INSERT INTO `v1_fields` VALUES ('729', '购买价格', 'room_buy_price', 'int', '10', '', null, 'text', null, null, null, '', '255', null, '', 'proxy_customer_room', null);
INSERT INTO `v1_fields` VALUES ('730', '房产总数量', 'room_number', 'int', '4', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_room', null);
INSERT INTO `v1_fields` VALUES ('731', '按揭数量', 'room_mortgage_number', 'int', '4', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_room', null);
INSERT INTO `v1_fields` VALUES ('732', '主营业务', 'main_business', 'varchar', '248', '', '', 'text', '', '', null, '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('733', '股东人数', 'stockholder_num', 'int', '4', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('734', '员工人数', 'staff_num', 'int', '4', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('735', '经营场所', 'business_place', 'int', '4', '', '', 'select', '', '\r\n$(\"select[name=\'customer_profession[business_place]\']\").on(\'change\',function(){\r\n	var business_place_desc = $(\"input[name=\'customer_profession[business_place_desc]\']\").val();\r\n	if($(this).val() == 91 ) {\r\n		$(\'<em>租金<input type=\"number\" name=\"customer_profession[business_place_desc]\" value=\"\" class=\"span1\" />元</em>\').insertAfter(this);\r\n		$(\"input[name=\'customer_profession[business_place_desc]\']\").val(business_place_desc);\r\n	} else {\r\n		$(\"input[name=\'customer_profession[business_place_desc]\']\").closest(\'em\').remove();\r\n	}\r\n}).trigger(\'change\');\r\n', null, '', '255', 'ESTATE', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('736', '是否有营业执照', 'is_license', 'int', '1', '', '', 'select', '', '', null, '', '255', 'custom', 'a:2:{i:1;s:3:\"是\";i:0;s:3:\"否\";}', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('737', '月均营业额', 'turnover_month', 'int', '11', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('738', '旺季营业额', 'turnover_peak', 'int', '11', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('739', '淡季', 'turnover_slack', 'int', '11', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('740', '毛利率', 'gross_profit_rate', 'int', '11', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('741', '净利率', 'net_profit_ratio', 'int', '11', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('742', '应收账款', 'receivables', 'int', '11', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('743', '存货', 'inventory', 'varchar', '64', '', '', 'text', '', '', null, '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('744', '每月固定开支', 'spending_month', 'int', '11', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('745', '房租', 'rent', 'int', '11', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('746', '水电', 'utilities', 'int', '11', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('747', '月还款金额', 'payments_month', 'int', '11', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('748', '个人负债总额', 'personal_debt', 'int', '11', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('749', '企业负债总额', 'company_debt', 'int', '11', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('750', '工资', 'salary', 'int', '11', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('751', '居住状况描述', 'estate_desc', 'varchar', '64', null, null, 'text', null, null, null, null, '255', null, null, 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('753', '贷款总额', 'total_loan', 'int', '11', '', '', 'number', '', '', null, '', '255', '', '', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('754', '姓名', 'name', 'varchar', '64', '', null, 'text', null, null, null, '', '255', null, '', 'proxy_customer_other', null);
INSERT INTO `v1_fields` VALUES ('755', '性别', 'sex', 'int', '1', '', '', 'select', '', '', null, '', '255', 'custom', 'a:2:{i:1;s:3:\"男\";i:2;s:3:\"女\";}', 'proxy_customer_other', null);
INSERT INTO `v1_fields` VALUES ('756', '是否知晓贷款', 'is_know', 'varchar', '20', '', '', 'select', '', '', null, '', '255', 'custom', 'a:2:{i:1;s:3:\"是\";i:0;s:3:\"否\";}', 'proxy_customer_other', null);
INSERT INTO `v1_fields` VALUES ('757', '股份占比', 'shares', 'varchar', '64', '', null, 'text', null, null, null, '', '255', null, '', 'proxy_customer_other', null);
INSERT INTO `v1_fields` VALUES ('758', '手机号码', 'moblie', 'varchar', '15', '', null, 'number', null, null, null, '', '255', null, '', 'proxy_customer_other', null);
INSERT INTO `v1_fields` VALUES ('759', '办公电话', 'work_tel', 'varchar', '15', '', null, 'text', null, null, null, '', '255', null, '', 'proxy_customer_other', null);
INSERT INTO `v1_fields` VALUES ('760', '身份证号', 'idcard', 'varchar', '22', '', null, 'text', null, null, null, '', '255', null, '', 'proxy_customer_other', null);
INSERT INTO `v1_fields` VALUES ('761', '现居住地址', 'live_address', 'varchar', '20', '', null, 'address', null, null, null, '', '255', null, '', 'proxy_customer_other', null);
INSERT INTO `v1_fields` VALUES ('762', '经营场所描述', 'business_place_desc', 'varchar', '128', null, null, 'text', null, null, null, null, '255', null, null, 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('763', '月供贷款', 'installment_month', 'float', '6', '', '元', 'number', '', '', null, '', '255', '', '', 'proxy_customer_room', null);
INSERT INTO `v1_fields` VALUES ('764', '渠道来源', 'channel_origin', 'int', '8', ' required', '', 'select', '', '', null, '', '255', 'CHANNEL_SOURCE', 'a:0:{}', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('765', '贷款日期', 'credit_date', 'int', '10', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_contract_credit', null);
INSERT INTO `v1_fields` VALUES ('766', '贷款类型', 'credit_type', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_contract_credit', null);
INSERT INTO `v1_fields` VALUES ('767', '贷款机构', 'credit_name', 'varchar', '100', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_contract_credit', null);
INSERT INTO `v1_fields` VALUES ('768', '贷款金额', 'credit_price', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_contract_credit', null);
INSERT INTO `v1_fields` VALUES ('769', '贷款期限', 'credit_duration', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_contract_credit', null);
INSERT INTO `v1_fields` VALUES ('770', '逾期次数', 'credit_overdue_number', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_contract_credit', null);
INSERT INTO `v1_fields` VALUES ('771', '当前逾期金额', 'credit_overdue_price', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_contract_credit', null);
INSERT INTO `v1_fields` VALUES ('772', '每月还款', 'credit_repay_month', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_contract_credit', null);
INSERT INTO `v1_fields` VALUES ('773', '剩余还款期数', 'credit_repay_nper', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_contract_credit', null);
INSERT INTO `v1_fields` VALUES ('774', '尚余欠款', 'credit_arrears', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_contract_credit', null);
INSERT INTO `v1_fields` VALUES ('775', '还款方式', 'credit_repay_way', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_contract_credit', null);
INSERT INTO `v1_fields` VALUES ('776', '信用卡总透支', 'credit_overdraft', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('777', '信用卡总授信额', 'credit_lines', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('778', '信用卡总卡数', 'credit_card_num', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('779', '信用类贷款总贷款额', 'credit_loans_amount', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('780', '信用类贷款总月供', 'credit_loans_monthly', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('781', '信用类贷款总贷款余额', 'credit_loans_balance', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('782', '抵押类贷款总贷款额', 'mortgage_amount', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('783', '抵押类贷款总月供', 'mortgage_monthly', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('784', '抵押类贷款总贷款余额', 'mortgage_balance', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('785', '其它资产(元)', 'asset_other_assets', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('786', '按揭数量(辆)', 'asset_car_mortgage_num', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('787', '车辆总数量(辆)', 'asset_car_number', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('788', '按揭数量(处)', 'asset_room_mortgage_number', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('789', '房产总数量(处)', 'asset_room_number', 'int', '4', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_asset', null);
INSERT INTO `v1_fields` VALUES ('790', '仪表着装', 'impress_dressing', 'VARCHAR', '30', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_other', null);
INSERT INTO `v1_fields` VALUES ('791', '精神状态', 'impress_spiritual', 'VARCHAR', '30', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_other', null);
INSERT INTO `v1_fields` VALUES ('792', '谈吐举止', 'impress_behavior', 'VARCHAR', '30', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_other', null);
INSERT INTO `v1_fields` VALUES ('793', 'QQ号', 'qq', 'int', '16', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('794', '居住开始时间', 'live_start', 'int', '11', '', '', 'datetime', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('795', '居住结束时间', 'live_end', 'int', '11', '', '', 'datetime', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer', null);
INSERT INTO `v1_fields` VALUES ('796', '贷款期间是否出国', 'is_go_abroad', 'int', '11', '', '', 'select', '', '', null, '', '255', 'custom', 'a:2:{i:1;s:3:\"是\";i:0;s:3:\"否\";}', 'proxy_contract', null);
INSERT INTO `v1_fields` VALUES ('797', '月还款能力', 'repayment_capability_month', 'varchar', '64', '', '', 'text', '', '', null, '', '255', '', 'a:0:{}', 'proxy_contract', null);
INSERT INTO `v1_fields` VALUES ('798', '还款来源', 'repayment_source', 'varchar', '32', '', '', 'select', '', '', null, '', '255', 'REPAYMENT_SOURCE', 'a:0:{}', 'proxy_contract', null);
INSERT INTO `v1_fields` VALUES ('799', '还款来源描述', 'repayment_source_desc', 'varchar', '64', '', '', 'text', '', '', null, '', '255', '', 'a:0:{}', 'proxy_contract', null);
INSERT INTO `v1_fields` VALUES ('800', '单位同事姓名', 'colleague_name', 'varchar', '64', '', '', 'text', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('801', '每月薪资', 'pro_salary', 'int', '8', '', '', 'number', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_profession', null);
INSERT INTO `v1_fields` VALUES ('802', '出厂日期', 'car_leave_date', 'int', '10', ' required', '', 'datetime', '', '', null, '', '255', '', 'a:0:{}', 'proxy_customer_car', null);
