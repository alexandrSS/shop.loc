-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 20 2014 г., 14:17
-- Версия сервера: 5.5.40-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `aesstudio_magazin`
--

DELIMITER $$
--
-- Процедуры
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertArticle`(NameArticle Varchar(45), NameCategory Varchar(45))
BEGIN
Insert into article (Название_товара, Id_Category)
	values (NameArticle, (Select IdCategory
								from Category
									Where Category.Name_Category = NameCategory)
			);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procAddDelivery`(Номер_накладной Varchar(45), ArticleCol Varchar(45), Цена_поставки float,
									Количество_товара int, Срок_годности date, remark longtext)
begin

    DECLARE Id_MakersVar int default 0;
	DECLARE Id_ArticleVar int DEFAULT 0;
	
set Id_MakersVar = (select Id_Makers from Makers where Номер_накладной = makers.Номер_накладной);
set Id_ArticleVar = (select Id_Article from article where ArticleCol = article.Название_товара);


insert into delivery (Id_Makers, Id_Article, Цена_поставки, Количество_товара, Срок_годности, remark)
		values (Id_MakersVar, Id_ArticleVar, Цена_поставки, Количество_товара, Срок_годности, remark);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_DeleteFromSales`(Id_saleCol int)
BEGIN


	declare Id_Deliverycol int;
	set Id_Deliverycol = (select Id_Delivery from sales where Id_sale = Id_saleCol);


	Delete from sales where Id_sale = Id_saleCol;
	
	
	Update delivery
		set Статус = 0 
			Where  
				delivery.Количество_товара > (select count(sales.количество) from sales
												where sales.Id_Delivery = Id_Deliverycol
												group by sales.Id_Delivery
											)
						and delivery.Статус = 1
						and delivery.Id_Delivery = Id_Deliverycol;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Proc_Insert_and_Id_DeliveryFromSales`(Id_ArticleCol varchar(45), dataCol date, CountSalesCol int, SalesCol float, remarkCol text)
BEGIN
	DECLARE CountCount int default 0;
	DECLARE Id_Deliverycol int DEFAULT 0;
	DECLARE Id_ArticleVar int default 0;


	set Id_ArticleVar = (select Id_Article from article where Id_articleCol = article.Название_товара);


	set CountCount = CountSalesCol;
	
	while	CountCount > 0 do

	set Id_Deliverycol =

(SELECT Id_Delivery FROM delivery
where Срок_годности = (select Срок_годности
						from delivery
							where Id_Article = Id_ArticleVar 
							and Статус = 0
							Order by Срок_годности asc limit 1)
and Id_Article = Id_ArticleVar
and Статус = 0
Order by Срок_годности asc limit 1)	
;


	Insert into sales (Дата, Id_Delivery, Количество, Цена, remark) 
						values (dataCol, Id_Deliverycol, 1, SalesCol, remarkCol);

	
	Update delivery
		set Статус = 1 
			Where  
				delivery.Количество_товара = (select count(sales.количество) from sales
												where sales.Id_Delivery = Id_Deliverycol
												group by sales.Id_Delivery
											)
						and delivery.Статус = 0
						and delivery.Id_Delivery = Id_Deliverycol;

	set	CountCount = CountCount -1;

	END WHILE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_report_profit`(data1 date, data2 date)
BEGIN

select
 (select Название_товара
		from article
			where Id_Article =
							(select delivery.Id_Article 
												from delivery 
													where delivery.Id_Delivery = sss.Id_Delivery)
							) as Название_товара,

          sum(Прибыль) as Прибыль from 
						(select ee.Id_Delivery, (ee.Продажи- ee.Количество*delivery.Цена_поставки) as Прибыль
							from 
								(select Id_Delivery, Количество, sum(Продажи) as Продажи
									from
											(select Id_Delivery, count(Количество) as Количество, (count(Количество)* Цена) as Продажи
												from sales
													where Дата between data1 and data2
													group by Id_Delivery, Цена
												) as e
								group by Id_Delivery
								) as ee 
							left join delivery on ee.Id_Delivery = delivery.Id_Delivery
						) as sss

group by Название_товара;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_report_profit_sum`(date1 date, date2 date)
BEGIN
select
          sum(Прибыль) as Прибыль from 
						(select ee.Id_Delivery, (ee.Продажи- ee.Количество*delivery.Цена_поставки) as Прибыль
							from 
								(select Id_Delivery, Количество, sum(Продажи) as Продажи
									from
											(select Id_Delivery, count(Количество) as Количество, (count(Количество)* Цена) as Продажи
												from sales
													where Дата between date1 and date2
													group by Id_Delivery, Цена
												) as e
								group by Id_Delivery
								) as ee 
							left join delivery on ee.Id_Delivery = delivery.Id_Delivery
						) as sss;




END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `proc_report_Sales`(date1 date, date2 date)
BEGIN
Select (select Название_товара  from article
									where article.Id_article = (
																	select Id_article from delivery
																	where Id_Delivery = Sales.Id_Delivery
																)
		) as Название_товара,
		sum(Количество) as Количество, Цена, sum(Количество)*Цена as Сумма
from sales
where Дата between date1 and date2
group by Цена, Id_Delivery;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id_article` int(11) NOT NULL AUTO_INCREMENT,
  `name_article` varchar(45) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `number` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_article`),
  UNIQUE KEY `Id_Article_UNIQUE` (`id_article`),
  KEY `idCategory_idx` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=131 ;

--
-- Дамп данных таблицы `article`
--

INSERT INTO `article` (`id_article`, `name_article`, `category_id`, `number`) VALUES
(2, 'Сателлит плюс', 3, 0),
(3, 'Сателлит', 3, 0),
(4, 'Сателлит экспресс', 3, 0),
(5, 'АccuChek Active', 3, 0),
(6, 'AccuChek Performa Nano', 3, 0),
(7, 'One Touch Select + 25 т/п', 3, 0),
(8, 'One Touch Select Simple', 3, 0),
(9, 'Contour TS', 3, 0),
(10, 'CleverChek', 3, 0),
(11, 'CleverChek + 100 т/п', 3, 0),
(12, 'Изи тач (глюкоза+ холестерин)', 3, 0),
(13, 'Изи тач (глюкоза+ холестерин+ гемогл)', 3, 0),
(14, 'Изи тач (глюкоза+ холестерин+ мочев.к-та)', 3, 0),
(120, 'cvfnr', 3, 0),
(121, 'алешка', 39, 0),
(122, 'афродизиак', 40, 0),
(123, 'Сателлит', 2, 0),
(124, 'Сателлит № 50', 2, 0),
(125, 'Сателлит № 25', 2, 0),
(126, 'Сателлит плюс № 25', 2, 0),
(127, 'Сателлит плюс № 50', 2, 0),
(128, 'Сателлит экспресс № 50', 2, 0),
(129, 'Сателлит экспресс № 25', 2, 0),
(130, 'Тест', 42, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id_category` int(11) NOT NULL AUTO_INCREMENT,
  `name_category` varchar(45) NOT NULL,
  PRIMARY KEY (`id_category`),
  UNIQUE KEY `idCategory_UNIQUE` (`id_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id_category`, `name_category`) VALUES
(2, 'Тест полоски'),
(3, 'Глюкометры'),
(4, 'Уход за кожей и полостью рта'),
(5, 'Расходные материалы для помпы'),
(6, 'Визуальные тест полоски'),
(7, 'Батарейки'),
(8, 'Средства введения инсулина'),
(9, 'Чехлы'),
(10, 'Книги'),
(11, 'Тонометры'),
(12, 'Устройства для прокола пальца'),
(13, 'Сахарозаменители'),
(39, ' анастезия '),
(40, ' феромоны '),
(41, ' xxx '),
(42, 'Тест');

-- --------------------------------------------------------

--
-- Структура таблицы `delivery`
--

CREATE TABLE IF NOT EXISTS `delivery` (
  `id_delivery` int(11) NOT NULL AUTO_INCREMENT,
  `makers_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `cena_postavki` float DEFAULT NULL,
  `kolichestvo_tovara` int(11) NOT NULL,
  `srok_godnosti` date DEFAULT NULL,
  `remark` longtext,
  `status` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id_delivery`),
  UNIQUE KEY `Id_Delivery_UNIQUE` (`id_delivery`),
  KEY `Id_Makers_idx` (`makers_id`),
  KEY `Id_Article_idx` (`article_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Дамп данных таблицы `delivery`
--

INSERT INTO `delivery` (`id_delivery`, `makers_id`, `article_id`, `cena_postavki`, `kolichestvo_tovara`, `srok_godnosti`, `remark`, `status`) VALUES
(26, 25, 2, 200, 10, '2014-12-30', '', b'1'),
(27, 26, 3, 200, 10, '2014-12-30', '', b'1'),
(28, 27, 13, 200, 10, '2014-12-30', '', b'1'),
(29, 25, 4, 200, 30, '2014-12-30', '', b'1'),
(30, 26, 4, 200, 10, '2014-12-30', '', b'1'),
(31, 28, 122, 200.8, 2, '2014-12-30', '', b'1'),
(32, 25, 2, 200, 30, '2014-12-30', '', b'1'),
(33, 25, 8, 400, 2, '2014-12-30', '', b'1'),
(34, 29, 124, 250, 50, '2016-02-02', '', b'0'),
(35, 25, 125, 200, 20, '2014-12-01', '', b'1');

-- --------------------------------------------------------

--
-- Структура таблицы `makers`
--

CREATE TABLE IF NOT EXISTS `makers` (
  `id_makers` int(11) NOT NULL AUTO_INCREMENT,
  `providers_id` int(11) NOT NULL,
  `nomer_nakladnoi` varchar(10) NOT NULL,
  `date` date DEFAULT NULL,
  `status` bit(1) DEFAULT NULL,
  `comment` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_makers`),
  UNIQUE KEY `Номер_накладной_UNIQUE` (`nomer_nakladnoi`),
  KEY `Название_Поставщика` (`providers_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Дамп данных таблицы `makers`
--

INSERT INTO `makers` (`id_makers`, `providers_id`, `nomer_nakladnoi`, `date`, `status`, `comment`) VALUES
(25, 1, ' 1 ', '2014-01-01', NULL, ' 1 посылка '),
(26, 2, ' 2 ', '2014-01-30', NULL, '  '),
(27, 2, ' 3 ', '2014-02-28', NULL, '  '),
(28, 3, ' 1418 ', '2014-05-28', NULL, '  '),
(29, 1, ' 543 ', '2014-06-01', NULL, ' Это пробная поставка ');

-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1417349775),
('m140418_204054_users', 1417349784),
('m140703_123055_system_log', 1417349785),
('m140805_084812_system_event', 1417349785);

-- --------------------------------------------------------

--
-- Структура таблицы `profiles`
--

CREATE TABLE IF NOT EXISTS `profiles` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `avatar_url` varchar(64) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `profiles`
--

INSERT INTO `profiles` (`user_id`, `name`, `surname`, `avatar_url`) VALUES
(1, 'Administration', 'Site', ''),
(2, 'Олег', 'Алехин', '');

-- --------------------------------------------------------

--
-- Структура таблицы `providers`
--

CREATE TABLE IF NOT EXISTS `providers` (
  `id_providers` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id_providers`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Поставщики' AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `providers`
--

INSERT INTO `providers` (`id_providers`, `name`, `description`) VALUES
(1, 'Екатерина', NULL),
(2, 'Настя', NULL),
(3, 'Медиалайн', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `sales`
--

CREATE TABLE IF NOT EXISTS `sales` (
  `id_sale` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `delivery_id` int(11) NOT NULL,
  `kolichestvo` int(11) NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  `remark` longtext,
  PRIMARY KEY (`id_sale`),
  KEY `Id_Article2_idx` (`delivery_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=575 ;

--
-- Дамп данных таблицы `sales`
--

INSERT INTO `sales` (`id_sale`, `date`, `delivery_id`, `kolichestvo`, `price`, `remark`) VALUES
(418, '2014-03-20', 26, 1, 250, ''),
(419, '2014-03-20', 26, 1, 250, ''),
(420, '2014-03-20', 26, 1, 250, ''),
(421, '2014-03-20', 26, 1, 250, ''),
(422, '2014-03-20', 26, 1, 250, ''),
(423, '2014-03-20', 26, 1, 250, ''),
(424, '2014-03-20', 26, 1, 250, ''),
(425, '2014-03-20', 26, 1, 250, ''),
(426, '2014-03-20', 26, 1, 250, ''),
(427, '2014-03-20', 26, 1, 250, ''),
(428, '2014-09-28', 28, 1, 230, ''),
(429, '2014-09-28', 28, 1, 230, ''),
(430, '2014-09-28', 28, 1, 230, ''),
(431, '2014-09-28', 28, 1, 230, ''),
(432, '2014-09-28', 28, 1, 230, ''),
(433, '2014-09-28', 28, 1, 230, ''),
(434, '2014-09-28', 28, 1, 230, ''),
(435, '2014-09-28', 28, 1, 230, ''),
(436, '2014-09-28', 28, 1, 230, ''),
(437, '2014-09-28', 28, 1, 230, ''),
(438, '2014-09-28', 27, 1, 230, ''),
(439, '2014-09-28', 27, 1, 230, ''),
(440, '2014-09-28', 27, 1, 230, ''),
(441, '2014-09-28', 27, 1, 230, ''),
(442, '2014-09-28', 27, 1, 230, ''),
(443, '2014-09-28', 27, 1, 230, ''),
(444, '2014-09-28', 27, 1, 230, ''),
(445, '2014-09-28', 27, 1, 230, ''),
(446, '2014-09-28', 27, 1, 230, ''),
(447, '2014-09-28', 27, 1, 230, ''),
(448, '2014-12-20', 29, 1, 250, ''),
(449, '2014-12-20', 29, 1, 250, ''),
(450, '2014-12-20', 29, 1, 250, ''),
(451, '2014-12-20', 29, 1, 250, ''),
(452, '2014-12-20', 29, 1, 250, ''),
(453, '2014-12-20', 29, 1, 250, ''),
(454, '2014-12-20', 29, 1, 250, ''),
(455, '2014-12-20', 29, 1, 250, ''),
(456, '2014-12-20', 29, 1, 250, ''),
(457, '2014-12-20', 29, 1, 250, ''),
(458, '2014-12-20', 29, 1, 250, ''),
(459, '2014-12-20', 29, 1, 250, ''),
(460, '2014-12-20', 29, 1, 250, ''),
(461, '2014-12-20', 29, 1, 250, ''),
(462, '2014-12-20', 29, 1, 250, ''),
(463, '2014-12-20', 29, 1, 250, ''),
(464, '2014-12-20', 29, 1, 250, ''),
(465, '2014-12-20', 29, 1, 250, ''),
(466, '2014-12-20', 29, 1, 250, ''),
(467, '2014-12-20', 29, 1, 250, ''),
(468, '2014-12-20', 29, 1, 250, ''),
(469, '2014-12-20', 29, 1, 250, ''),
(470, '2014-12-20', 29, 1, 250, ''),
(471, '2014-12-20', 29, 1, 250, ''),
(472, '2014-12-20', 29, 1, 250, ''),
(473, '2014-12-20', 29, 1, 250, ''),
(474, '2014-12-20', 29, 1, 250, ''),
(475, '2014-12-20', 29, 1, 250, ''),
(476, '2014-12-20', 29, 1, 250, ''),
(477, '2014-12-20', 29, 1, 250, ''),
(478, '2014-12-20', 30, 1, 250, ''),
(479, '2014-12-20', 30, 1, 250, ''),
(480, '2014-12-20', 30, 1, 250, ''),
(481, '2014-12-20', 30, 1, 250, ''),
(482, '2014-12-20', 30, 1, 250, ''),
(483, '2014-12-20', 30, 1, 250, ''),
(484, '2014-12-20', 30, 1, 250, ''),
(485, '2014-12-20', 30, 1, 250, ''),
(486, '2014-12-20', 30, 1, 250, ''),
(487, '2014-12-20', 30, 1, 250, ''),
(488, '2014-05-28', 31, 1, 210, ''),
(489, '2014-10-21', 31, 1, 210, ''),
(490, '2014-10-04', 32, 1, 230, ''),
(491, '2014-10-04', 32, 1, 230, ''),
(492, '2014-03-12', 32, 1, 230, ''),
(493, '2014-06-01', 34, 1, 350, ''),
(494, '2014-06-01', 34, 1, 350, ''),
(495, '2014-06-01', 34, 1, 350, ''),
(496, '2014-06-01', 34, 1, 350, ''),
(497, '2014-06-01', 34, 1, 350, ''),
(498, '2014-06-01', 34, 1, 350, ''),
(499, '2014-06-01', 34, 1, 350, ''),
(500, '2014-06-01', 34, 1, 350, ''),
(501, '2014-06-01', 34, 1, 350, ''),
(502, '2014-06-01', 34, 1, 350, ''),
(503, '2014-06-01', 34, 1, 350, ''),
(504, '2014-06-01', 34, 1, 350, ''),
(505, '2014-06-01', 34, 1, 350, ''),
(506, '2014-06-01', 34, 1, 350, ''),
(507, '2014-06-01', 34, 1, 350, ''),
(508, '2014-06-01', 34, 1, 350, ''),
(509, '2014-06-01', 34, 1, 350, ''),
(510, '2014-06-01', 34, 1, 350, ''),
(511, '2014-06-01', 34, 1, 350, ''),
(512, '2014-06-01', 34, 1, 350, ''),
(513, '2014-11-20', 32, 1, 230, ''),
(514, '2014-11-20', 32, 1, 230, ''),
(515, '2014-11-20', 32, 1, 230, ''),
(516, '2014-11-20', 32, 1, 230, ''),
(517, '2014-11-20', 32, 1, 230, ''),
(518, '2014-11-20', 32, 1, 230, ''),
(519, '2014-11-20', 32, 1, 230, ''),
(520, '2014-11-20', 32, 1, 230, ''),
(521, '2014-11-20', 32, 1, 230, ''),
(522, '2014-11-20', 32, 1, 230, ''),
(523, '2014-11-20', 32, 1, 230, ''),
(524, '2014-11-20', 32, 1, 230, ''),
(525, '2014-11-20', 32, 1, 230, ''),
(526, '2014-11-20', 32, 1, 230, ''),
(527, '2014-11-20', 32, 1, 230, ''),
(528, '2014-11-20', 32, 1, 230, ''),
(529, '2014-11-20', 32, 1, 230, ''),
(530, '2014-11-20', 32, 1, 230, ''),
(531, '2014-11-20', 32, 1, 230, ''),
(532, '2014-11-20', 32, 1, 230, ''),
(533, '2014-11-20', 32, 1, 230, ''),
(534, '2014-11-20', 32, 1, 230, ''),
(535, '2014-11-20', 32, 1, 230, ''),
(536, '2014-11-20', 32, 1, 230, ''),
(537, '2014-11-20', 32, 1, 230, ''),
(538, '2014-11-20', 32, 1, 230, ''),
(539, '2014-11-20', 32, 1, 230, ''),
(540, '2014-12-12', 35, 1, 400, ''),
(541, '2014-12-12', 35, 1, 400, ''),
(542, '2014-12-12', 35, 1, 400, ''),
(543, '2014-12-12', 35, 1, 400, ''),
(544, '2014-12-12', 35, 1, 400, ''),
(545, '2014-12-12', 35, 1, 400, ''),
(546, '2014-12-12', 35, 1, 400, ''),
(547, '2014-12-12', 35, 1, 400, ''),
(548, '2014-12-12', 35, 1, 400, ''),
(549, '2014-12-12', 35, 1, 400, ''),
(550, '2014-12-12', 35, 1, 400, ''),
(551, '2014-12-12', 35, 1, 400, ''),
(552, '2014-12-12', 35, 1, 400, ''),
(553, '2014-12-12', 35, 1, 400, ''),
(554, '2014-12-12', 35, 1, 400, ''),
(555, '2014-12-12', 35, 1, 400, ''),
(556, '2014-12-12', 35, 1, 400, ''),
(557, '2014-12-12', 35, 1, 400, ''),
(558, '2014-12-12', 35, 1, 400, ''),
(559, '2014-12-12', 35, 1, 400, ''),
(560, '2014-04-12', 34, 1, 350, ''),
(561, '2014-04-12', 33, 1, 350, ''),
(562, '2014-04-12', 33, 1, 350, ''),
(563, '2014-04-12', 34, 1, 350, ''),
(564, '2014-04-12', 34, 1, 350, ''),
(565, '2014-04-12', 34, 1, 350, ''),
(566, '2014-04-12', 34, 1, 350, ''),
(567, '2014-04-12', 34, 1, 350, ''),
(568, '2014-04-12', 34, 1, 350, ''),
(569, '2014-04-12', 34, 1, 350, ''),
(570, '2014-04-12', 34, 1, 350, ''),
(571, '2014-04-12', 34, 1, 550, ''),
(572, '2014-04-12', 34, 1, 550, ''),
(573, '2014-04-12', 34, 1, 550, ''),
(574, '2014-04-12', 34, 1, 550, '');

-- --------------------------------------------------------

--
-- Структура таблицы `saleses`
--

CREATE TABLE IF NOT EXISTS `saleses` (
  `id_saleses` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `category_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `srok_godnosti` int(11) NOT NULL,
  `kolichestvo` int(11) NOT NULL,
  `prace` int(11) NOT NULL,
  `remark` text,
  PRIMARY KEY (`id_saleses`),
  KEY `category_id` (`category_id`,`article_id`),
  KEY `article_id` (`article_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `saleses`
--

INSERT INTO `saleses` (`id_saleses`, `date`, `category_id`, `article_id`, `srok_godnosti`, `kolichestvo`, `prace`, `remark`) VALUES
(9, '2019-12-20', 3, 2, 26, 0, 5000, NULL),
(10, '2019-12-20', 3, 2, 32, 11, 5000, NULL),
(11, '2019-12-20', 3, 2, 26, 10, 5000, NULL),
(12, '2019-12-20', 3, 2, 26, 27, 5000, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `system_log`
--

CREATE TABLE IF NOT EXISTS `system_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `log_time` int(11) NOT NULL,
  `prefix` text,
  `message` text,
  PRIMARY KEY (`id`),
  KEY `idx_log_level` (`level`),
  KEY `idx_log_category` (`category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

--
-- Дамп данных таблицы `system_log`
--

INSERT INTO `system_log` (`id`, `level`, `category`, `log_time`, `prefix`, `message`) VALUES
(46, 1, 'yii\\db\\IntegrityException', 1418974690, '[app-backend][/prodaja/index]', 'exception ''PDOException'' with message ''SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails (`aesstudio_magazin`.`saleses`, CONSTRAINT `saleses_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE)'' in /var/www/shop.loc/vendor/yiisoft/yii2/db/Command.php:768\nStack trace:\n#0 /var/www/shop.loc/vendor/yiisoft/yii2/db/Command.php(768): PDOStatement->execute()\n#1 /var/www/shop.loc/vendor/yiisoft/yii2/db/ActiveRecord.php(463): yii\\db\\Command->execute()\n#2 /var/www/shop.loc/vendor/yiisoft/yii2/db/ActiveRecord.php(438): yii\\db\\ActiveRecord->insertInternal(NULL)\n#3 /var/www/shop.loc/vendor/yiisoft/yii2/db/BaseActiveRecord.php(583): yii\\db\\ActiveRecord->insert(true, NULL)\n#4 /var/www/shop.loc/backend/controllers/ProdajaController.php(73): yii\\db\\BaseActiveRecord->save()\n#5 [internal function]: backend\\controllers\\ProdajaController->actionIndex()\n#6 /var/www/shop.loc/vendor/yiisoft/yii2/base/InlineAction.php(55): call_user_func_array(Array, Array)\n#7 /var/www/shop.loc/vendor/yiisoft/yii2/base/Controller.php(151): yii\\base\\InlineAction->runWithParams(Array)\n#8 /var/www/shop.loc/vendor/yiisoft/yii2/base/Module.php(455): yii\\base\\Controller->runAction(''index'', Array)\n#9 /var/www/shop.loc/vendor/yiisoft/yii2/web/Application.php(83): yii\\base\\Module->runAction(''prodaja/index'', Array)\n#10 /var/www/shop.loc/vendor/yiisoft/yii2/base/Application.php(375): yii\\web\\Application->handleRequest(Object(yii\\web\\Request))\n#11 /var/www/shop.loc/backend/web/index.php(17): yii\\base\\Application->run()\n#12 {main}\n\nNext exception ''yii\\db\\IntegrityException'' with message ''SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails (`aesstudio_magazin`.`saleses`, CONSTRAINT `saleses_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE)\nThe SQL being executed was: INSERT INTO `saleses` (`id_saleses`) VALUES (NULL)'' in /var/www/shop.loc/vendor/yiisoft/yii2/db/Schema.php:532\nStack trace:\n#0 /var/www/shop.loc/vendor/yiisoft/yii2/db/Command.php(776): yii\\db\\Schema->convertException(Object(PDOException), ''INSERT INTO `sa...'')\n#1 /var/www/shop.loc/vendor/yiisoft/yii2/db/ActiveRecord.php(463): yii\\db\\Command->execute()\n#2 /var/www/shop.loc/vendor/yiisoft/yii2/db/ActiveRecord.php(438): yii\\db\\ActiveRecord->insertInternal(NULL)\n#3 /var/www/shop.loc/vendor/yiisoft/yii2/db/BaseActiveRecord.php(583): yii\\db\\ActiveRecord->insert(true, NULL)\n#4 /var/www/shop.loc/backend/controllers/ProdajaController.php(73): yii\\db\\BaseActiveRecord->save()\n#5 [internal function]: backend\\controllers\\ProdajaController->actionIndex()\n#6 /var/www/shop.loc/vendor/yiisoft/yii2/base/InlineAction.php(55): call_user_func_array(Array, Array)\n#7 /var/www/shop.loc/vendor/yiisoft/yii2/base/Controller.php(151): yii\\base\\InlineAction->runWithParams(Array)\n#8 /var/www/shop.loc/vendor/yiisoft/yii2/base/Module.php(455): yii\\base\\Controller->runAction(''index'', Array)\n#9 /var/www/shop.loc/vendor/yiisoft/yii2/web/Application.php(83): yii\\base\\Module->runAction(''prodaja/index'', Array)\n#10 /var/www/shop.loc/vendor/yiisoft/yii2/base/Application.php(375): yii\\web\\Application->handleRequest(Object(yii\\web\\Request))\n#11 /var/www/shop.loc/backend/web/index.php(17): yii\\base\\Application->run()\n#12 {main}\nAdditional Information:\nArray\n(\n    [0] => 23000\n    [1] => 1452\n    [2] => Cannot add or update a child row: a foreign key constraint fails (`aesstudio_magazin`.`saleses`, CONSTRAINT `saleses_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE)\n)\n'),
(47, 1, 'yii\\db\\IntegrityException', 1418974993, '[app-backend][/prodaja/index]', 'exception ''PDOException'' with message ''SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails (`aesstudio_magazin`.`saleses`, CONSTRAINT `saleses_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE)'' in /var/www/shop.loc/vendor/yiisoft/yii2/db/Command.php:768\nStack trace:\n#0 /var/www/shop.loc/vendor/yiisoft/yii2/db/Command.php(768): PDOStatement->execute()\n#1 /var/www/shop.loc/vendor/yiisoft/yii2/db/ActiveRecord.php(463): yii\\db\\Command->execute()\n#2 /var/www/shop.loc/vendor/yiisoft/yii2/db/ActiveRecord.php(438): yii\\db\\ActiveRecord->insertInternal(NULL)\n#3 /var/www/shop.loc/vendor/yiisoft/yii2/db/BaseActiveRecord.php(583): yii\\db\\ActiveRecord->insert(true, NULL)\n#4 /var/www/shop.loc/backend/controllers/ProdajaController.php(73): yii\\db\\BaseActiveRecord->save()\n#5 [internal function]: backend\\controllers\\ProdajaController->actionIndex()\n#6 /var/www/shop.loc/vendor/yiisoft/yii2/base/InlineAction.php(55): call_user_func_array(Array, Array)\n#7 /var/www/shop.loc/vendor/yiisoft/yii2/base/Controller.php(151): yii\\base\\InlineAction->runWithParams(Array)\n#8 /var/www/shop.loc/vendor/yiisoft/yii2/base/Module.php(455): yii\\base\\Controller->runAction(''index'', Array)\n#9 /var/www/shop.loc/vendor/yiisoft/yii2/web/Application.php(83): yii\\base\\Module->runAction(''prodaja/index'', Array)\n#10 /var/www/shop.loc/vendor/yiisoft/yii2/base/Application.php(375): yii\\web\\Application->handleRequest(Object(yii\\web\\Request))\n#11 /var/www/shop.loc/backend/web/index.php(17): yii\\base\\Application->run()\n#12 {main}\n\nNext exception ''yii\\db\\IntegrityException'' with message ''SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails (`aesstudio_magazin`.`saleses`, CONSTRAINT `saleses_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE)\nThe SQL being executed was: INSERT INTO `saleses` (`id_saleses`) VALUES (NULL)'' in /var/www/shop.loc/vendor/yiisoft/yii2/db/Schema.php:532\nStack trace:\n#0 /var/www/shop.loc/vendor/yiisoft/yii2/db/Command.php(776): yii\\db\\Schema->convertException(Object(PDOException), ''INSERT INTO `sa...'')\n#1 /var/www/shop.loc/vendor/yiisoft/yii2/db/ActiveRecord.php(463): yii\\db\\Command->execute()\n#2 /var/www/shop.loc/vendor/yiisoft/yii2/db/ActiveRecord.php(438): yii\\db\\ActiveRecord->insertInternal(NULL)\n#3 /var/www/shop.loc/vendor/yiisoft/yii2/db/BaseActiveRecord.php(583): yii\\db\\ActiveRecord->insert(true, NULL)\n#4 /var/www/shop.loc/backend/controllers/ProdajaController.php(73): yii\\db\\BaseActiveRecord->save()\n#5 [internal function]: backend\\controllers\\ProdajaController->actionIndex()\n#6 /var/www/shop.loc/vendor/yiisoft/yii2/base/InlineAction.php(55): call_user_func_array(Array, Array)\n#7 /var/www/shop.loc/vendor/yiisoft/yii2/base/Controller.php(151): yii\\base\\InlineAction->runWithParams(Array)\n#8 /var/www/shop.loc/vendor/yiisoft/yii2/base/Module.php(455): yii\\base\\Controller->runAction(''index'', Array)\n#9 /var/www/shop.loc/vendor/yiisoft/yii2/web/Application.php(83): yii\\base\\Module->runAction(''prodaja/index'', Array)\n#10 /var/www/shop.loc/vendor/yiisoft/yii2/base/Application.php(375): yii\\web\\Application->handleRequest(Object(yii\\web\\Request))\n#11 /var/www/shop.loc/backend/web/index.php(17): yii\\base\\Application->run()\n#12 {main}\nAdditional Information:\nArray\n(\n    [0] => 23000\n    [1] => 1452\n    [2] => Cannot add or update a child row: a foreign key constraint fails (`aesstudio_magazin`.`saleses`, CONSTRAINT `saleses_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE)\n)\n'),
(48, 1, 'yii\\db\\IntegrityException', 1418975069, '[app-backend][/prodaja/index]', 'exception ''PDOException'' with message ''SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails (`aesstudio_magazin`.`saleses`, CONSTRAINT `saleses_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `article` (`id_article`) ON DELETE CASCADE ON UPDATE CASCADE)'' in /var/www/shop.loc/vendor/yiisoft/yii2/db/Command.php:768\nStack trace:\n#0 /var/www/shop.loc/vendor/yiisoft/yii2/db/Command.php(768): PDOStatement->execute()\n#1 /var/www/shop.loc/vendor/yiisoft/yii2/db/ActiveRecord.php(463): yii\\db\\Command->execute()\n#2 /var/www/shop.loc/vendor/yiisoft/yii2/db/ActiveRecord.php(438): yii\\db\\ActiveRecord->insertInternal(NULL)\n#3 /var/www/shop.loc/vendor/yiisoft/yii2/db/BaseActiveRecord.php(583): yii\\db\\ActiveRecord->insert(true, NULL)\n#4 /var/www/shop.loc/backend/controllers/ProdajaController.php(73): yii\\db\\BaseActiveRecord->save()\n#5 [internal function]: backend\\controllers\\ProdajaController->actionIndex()\n#6 /var/www/shop.loc/vendor/yiisoft/yii2/base/InlineAction.php(55): call_user_func_array(Array, Array)\n#7 /var/www/shop.loc/vendor/yiisoft/yii2/base/Controller.php(151): yii\\base\\InlineAction->runWithParams(Array)\n#8 /var/www/shop.loc/vendor/yiisoft/yii2/base/Module.php(455): yii\\base\\Controller->runAction(''index'', Array)\n#9 /var/www/shop.loc/vendor/yiisoft/yii2/web/Application.php(83): yii\\base\\Module->runAction(''prodaja/index'', Array)\n#10 /var/www/shop.loc/vendor/yiisoft/yii2/base/Application.php(375): yii\\web\\Application->handleRequest(Object(yii\\web\\Request))\n#11 /var/www/shop.loc/backend/web/index.php(17): yii\\base\\Application->run()\n#12 {main}\n\nNext exception ''yii\\db\\IntegrityException'' with message ''SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails (`aesstudio_magazin`.`saleses`, CONSTRAINT `saleses_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `article` (`id_article`) ON DELETE CASCADE ON UPDATE CASCADE)\nThe SQL being executed was: INSERT INTO `saleses` (`id_saleses`) VALUES (NULL)'' in /var/www/shop.loc/vendor/yiisoft/yii2/db/Schema.php:532\nStack trace:\n#0 /var/www/shop.loc/vendor/yiisoft/yii2/db/Command.php(776): yii\\db\\Schema->convertException(Object(PDOException), ''INSERT INTO `sa...'')\n#1 /var/www/shop.loc/vendor/yiisoft/yii2/db/ActiveRecord.php(463): yii\\db\\Command->execute()\n#2 /var/www/shop.loc/vendor/yiisoft/yii2/db/ActiveRecord.php(438): yii\\db\\ActiveRecord->insertInternal(NULL)\n#3 /var/www/shop.loc/vendor/yiisoft/yii2/db/BaseActiveRecord.php(583): yii\\db\\ActiveRecord->insert(true, NULL)\n#4 /var/www/shop.loc/backend/controllers/ProdajaController.php(73): yii\\db\\BaseActiveRecord->save()\n#5 [internal function]: backend\\controllers\\ProdajaController->actionIndex()\n#6 /var/www/shop.loc/vendor/yiisoft/yii2/base/InlineAction.php(55): call_user_func_array(Array, Array)\n#7 /var/www/shop.loc/vendor/yiisoft/yii2/base/Controller.php(151): yii\\base\\InlineAction->runWithParams(Array)\n#8 /var/www/shop.loc/vendor/yiisoft/yii2/base/Module.php(455): yii\\base\\Controller->runAction(''index'', Array)\n#9 /var/www/shop.loc/vendor/yiisoft/yii2/web/Application.php(83): yii\\base\\Module->runAction(''prodaja/index'', Array)\n#10 /var/www/shop.loc/vendor/yiisoft/yii2/base/Application.php(375): yii\\web\\Application->handleRequest(Object(yii\\web\\Request))\n#11 /var/www/shop.loc/backend/web/index.php(17): yii\\base\\Application->run()\n#12 {main}\nAdditional Information:\nArray\n(\n    [0] => 23000\n    [1] => 1452\n    [2] => Cannot add or update a child row: a foreign key constraint fails (`aesstudio_magazin`.`saleses`, CONSTRAINT `saleses_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `article` (`id_article`) ON DELETE CASCADE ON UPDATE CASCADE)\n)\n'),
(49, 1, 'yii\\base\\UnknownPropertyException', 1418976608, '[app-backend][/prodaja/index]', 'exception ''yii\\base\\UnknownPropertyException'' with message ''Getting unknown property: backend\\models\\Saleses::price'' in /var/www/shop.loc/vendor/yiisoft/yii2/base/Component.php:143\nStack trace:\n#0 /var/www/shop.loc/vendor/yiisoft/yii2/db/BaseActiveRecord.php(247): yii\\base\\Component->__get(''price'')\n#1 /var/www/shop.loc/vendor/yiisoft/yii2/helpers/BaseHtml.php(1838): yii\\db\\BaseActiveRecord->__get(''price'')\n#2 /var/www/shop.loc/vendor/yiisoft/yii2/helpers/BaseHtml.php(1139): yii\\helpers\\BaseHtml::getAttributeValue(Object(backend\\models\\Saleses), ''price'')\n#3 /var/www/shop.loc/vendor/yiisoft/yii2/helpers/BaseHtml.php(1160): yii\\helpers\\BaseHtml::activeInput(''text'', Object(backend\\models\\Saleses), ''price'', Array)\n#4 /var/www/shop.loc/vendor/yiisoft/yii2/widgets/ActiveField.php(337): yii\\helpers\\BaseHtml::activeTextInput(Object(backend\\models\\Saleses), ''price'', Array)\n#5 /var/www/shop.loc/backend/views/prodaja/index.php(58): yii\\widgets\\ActiveField->textInput()\n#6 /var/www/shop.loc/vendor/yiisoft/yii2/base/View.php(325): require(''/var/www/shop.l...'')\n#7 /var/www/shop.loc/vendor/yiisoft/yii2/base/View.php(247): yii\\base\\View->renderPhpFile(''/var/www/shop.l...'', Array)\n#8 /var/www/shop.loc/vendor/yiisoft/yii2/base/View.php(149): yii\\base\\View->renderFile(''/var/www/shop.l...'', Array, Object(backend\\controllers\\ProdajaController))\n#9 /var/www/shop.loc/vendor/yiisoft/yii2/base/Controller.php(367): yii\\base\\View->render(''index'', Array, Object(backend\\controllers\\ProdajaController))\n#10 /var/www/shop.loc/backend/controllers/ProdajaController.php(79): yii\\base\\Controller->render(''index'', Array)\n#11 [internal function]: backend\\controllers\\ProdajaController->actionIndex()\n#12 /var/www/shop.loc/vendor/yiisoft/yii2/base/InlineAction.php(55): call_user_func_array(Array, Array)\n#13 /var/www/shop.loc/vendor/yiisoft/yii2/base/Controller.php(151): yii\\base\\InlineAction->runWithParams(Array)\n#14 /var/www/shop.loc/vendor/yiisoft/yii2/base/Module.php(455): yii\\base\\Controller->runAction(''index'', Array)\n#15 /var/www/shop.loc/vendor/yiisoft/yii2/web/Application.php(83): yii\\base\\Module->runAction(''prodaja/index'', Array)\n#16 /var/www/shop.loc/vendor/yiisoft/yii2/base/Application.php(375): yii\\web\\Application->handleRequest(Object(yii\\web\\Request))\n#17 /var/www/shop.loc/backend/web/index.php(17): yii\\base\\Application->run()\n#18 {main}'),
(50, 1, 'yii\\base\\UnknownPropertyException', 1418976609, '[app-backend][/prodaja/index]', 'exception ''yii\\base\\UnknownPropertyException'' with message ''Getting unknown property: backend\\models\\Saleses::price'' in /var/www/shop.loc/vendor/yiisoft/yii2/base/Component.php:143\nStack trace:\n#0 /var/www/shop.loc/vendor/yiisoft/yii2/db/BaseActiveRecord.php(247): yii\\base\\Component->__get(''price'')\n#1 /var/www/shop.loc/vendor/yiisoft/yii2/helpers/BaseHtml.php(1838): yii\\db\\BaseActiveRecord->__get(''price'')\n#2 /var/www/shop.loc/vendor/yiisoft/yii2/helpers/BaseHtml.php(1139): yii\\helpers\\BaseHtml::getAttributeValue(Object(backend\\models\\Saleses), ''price'')\n#3 /var/www/shop.loc/vendor/yiisoft/yii2/helpers/BaseHtml.php(1160): yii\\helpers\\BaseHtml::activeInput(''text'', Object(backend\\models\\Saleses), ''price'', Array)\n#4 /var/www/shop.loc/vendor/yiisoft/yii2/widgets/ActiveField.php(337): yii\\helpers\\BaseHtml::activeTextInput(Object(backend\\models\\Saleses), ''price'', Array)\n#5 /var/www/shop.loc/backend/views/prodaja/index.php(58): yii\\widgets\\ActiveField->textInput()\n#6 /var/www/shop.loc/vendor/yiisoft/yii2/base/View.php(325): require(''/var/www/shop.l...'')\n#7 /var/www/shop.loc/vendor/yiisoft/yii2/base/View.php(247): yii\\base\\View->renderPhpFile(''/var/www/shop.l...'', Array)\n#8 /var/www/shop.loc/vendor/yiisoft/yii2/base/View.php(149): yii\\base\\View->renderFile(''/var/www/shop.l...'', Array, Object(backend\\controllers\\ProdajaController))\n#9 /var/www/shop.loc/vendor/yiisoft/yii2/base/Controller.php(367): yii\\base\\View->render(''index'', Array, Object(backend\\controllers\\ProdajaController))\n#10 /var/www/shop.loc/backend/controllers/ProdajaController.php(79): yii\\base\\Controller->render(''index'', Array)\n#11 [internal function]: backend\\controllers\\ProdajaController->actionIndex()\n#12 /var/www/shop.loc/vendor/yiisoft/yii2/base/InlineAction.php(55): call_user_func_array(Array, Array)\n#13 /var/www/shop.loc/vendor/yiisoft/yii2/base/Controller.php(151): yii\\base\\InlineAction->runWithParams(Array)\n#14 /var/www/shop.loc/vendor/yiisoft/yii2/base/Module.php(455): yii\\base\\Controller->runAction(''index'', Array)\n#15 /var/www/shop.loc/vendor/yiisoft/yii2/web/Application.php(83): yii\\base\\Module->runAction(''prodaja/index'', Array)\n#16 /var/www/shop.loc/vendor/yiisoft/yii2/base/Application.php(375): yii\\web\\Application->handleRequest(Object(yii\\web\\Request))\n#17 /var/www/shop.loc/backend/web/index.php(17): yii\\base\\Application->run()\n#18 {main}');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `token` varchar(53) NOT NULL,
  `role` varchar(64) NOT NULL DEFAULT 'user',
  `status_id` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `role` (`role`),
  KEY `status_id` (`status_id`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `auth_key`, `token`, `role`, `status_id`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@demo.com', '$2y$13$AzbnoDbemfOzNX7YRbivK.ZNebemwP.eSG5iiJTSRLFUNw33nSZKm', '7Rf0cZBKAZhcK7OHxMXyNB2Br0m5Tf69', 'NOQ5DxBiAzlDT3MR05smgH4Q-Fon0E-x_1417349784', 'superadmin', 1, 1417349784, 1417349784),
(2, 'oleg', 'oleg@mail.mail', '$2y$13$QCOLWgw2//ydaFZuIGDgte6narqB3hUS0pL0RBeE1JAissrZ07S56', '1H85I3eFmrp5eLlvEhGNWVzEJslb-G8N', 'kmpPybsVmgJax4nOqy0hOhIDhFeeoycr_1418656053', 'superadmin', 1, 1418656053, 1418656190);

-- --------------------------------------------------------

--
-- Структура таблицы `user_email`
--

CREATE TABLE IF NOT EXISTS `user_email` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(53) NOT NULL,
  PRIMARY KEY (`user_id`,`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ZZZZdelivery`
--

CREATE TABLE IF NOT EXISTS `ZZZZdelivery` (
  `id_delivery` int(11) NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) NOT NULL COMMENT 'Поставщик',
  `invoice` int(11) NOT NULL COMMENT 'Накладная',
  `invoice_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Статус накладной',
  `date` int(11) NOT NULL,
  `article` int(11) NOT NULL,
  `price` int(11) NOT NULL COMMENT 'Цена',
  `amount` int(11) NOT NULL COMMENT 'Количество',
  `expiration_date` int(11) NOT NULL COMMENT 'Срок годности',
  `remark` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_delivery`),
  KEY `provider_id` (`provider_id`,`article`),
  KEY `article` (`article`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Поставки' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `ZZZZsales`
--

CREATE TABLE IF NOT EXISTS `ZZZZsales` (
  `sales_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `article` int(11) NOT NULL COMMENT 'Товар',
  `expiration_date` int(11) NOT NULL COMMENT 'Срок годности',
  `amout` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `remark` int(11) NOT NULL,
  PRIMARY KEY (`sales_id`),
  KEY `article` (`article`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `delivery_ibfk_1` FOREIGN KEY (`makers_id`) REFERENCES `makers` (`id_makers`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `delivery_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `article` (`id_article`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `makers`
--
ALTER TABLE `makers`
  ADD CONSTRAINT `makers_ibfk_1` FOREIGN KEY (`providers_id`) REFERENCES `providers` (`id_providers`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `FK_profile_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_email`
--
ALTER TABLE `user_email`
  ADD CONSTRAINT `FK_user_email_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `ZZZZdelivery`
--
ALTER TABLE `ZZZZdelivery`
  ADD CONSTRAINT `ZZZZdelivery_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`id_providers`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ZZZZdelivery_ibfk_2` FOREIGN KEY (`article`) REFERENCES `article` (`Id_Article`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;