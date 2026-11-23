-- MariaDB dump 10.19  Distrib 10.8.4-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: illuminos
-- ------------------------------------------------------
-- Server version	10.8.4-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `illuminos`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `illuminos` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `illuminos`;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `icon` varchar(160) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES
(1,'Драмы','dramy','🎭','Фильмы и сериалы о людях, чувствах и судьбах'),
(2,'Комедии','komedii','😂','Лёгкие фильмы для хорошего настроения'),
(3,'Ужасы','uzhasy','👻','Страшные истории, триллеры и мистика'),
(4,'Фантастика','fantastika','🚀','Космос, технологии и невероятные миры'),
(5,'Боевики','boeviki','💥','Динамичные фильмы с экшеном'),
(6,'Мелодрамы','melodramy','💘','Истории о любви и отношениях'),
(7,'Детективы','detektivy','🕵️','Расследования и загадки'),
(8,'Приключения','priklyucheniya','🗺️','Путешествия и открытия');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_categories`
--

DROP TABLE IF EXISTS `content_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_categories` (
  `content_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`content_id`,`category_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `content_categories_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `content_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `content_categories`
--

LOCK TABLES `content_categories` WRITE;
/*!40000 ALTER TABLE `content_categories` DISABLE KEYS */;
INSERT INTO `content_categories` VALUES
(1,1),
(1,5),
(1,7),
(2,4),
(2,8),
(3,1),
(3,6),
(4,2),
(5,3),
(6,1),
(6,7),
(7,4),
(7,8),
(8,2),
(9,2),
(10,1),
(10,7),
(11,1),
(11,7),
(12,4),
(12,5),
(13,4),
(13,8),
(14,2),
(14,5),
(15,1),
(15,6),
(16,1),
(16,5),
(17,2),
(17,5),
(18,1),
(18,2),
(19,2),
(20,1),
(21,4),
(21,8),
(22,2),
(23,1),
(23,7),
(24,1),
(24,7),
(25,1),
(25,6),
(26,1),
(26,8),
(27,1),
(27,7),
(28,1),
(28,8),
(29,2),
(29,5),
(30,5),
(30,7),
(31,2),
(31,3),
(32,2),
(33,2),
(34,1),
(34,8),
(35,2),
(35,5),
(36,2),
(37,2),
(37,6),
(38,1),
(38,7),
(39,1),
(39,5);
/*!40000 ALTER TABLE `content_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contents`
--

DROP TABLE IF EXISTS `contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(190) NOT NULL,
  `type` enum('movie','series') NOT NULL DEFAULT 'movie',
  `description` text NOT NULL,
  `release_year` smallint(5) unsigned DEFAULT NULL,
  `age_limit` varchar(8) DEFAULT '16+',
  `duration_min` smallint(5) unsigned DEFAULT NULL,
  `seasons` smallint(5) unsigned DEFAULT NULL,
  `director` varchar(190) DEFAULT NULL,
  `actors` varchar(255) DEFAULT NULL,
  `trailer_url` varchar(255) DEFAULT NULL,
  `poster_url` varchar(255) DEFAULT NULL,
  `banner_url` varchar(255) DEFAULT NULL,
  `is_new` tinyint(1) NOT NULL DEFAULT 0,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contents`
--

LOCK TABLES `contents` WRITE;
/*!40000 ALTER TABLE `contents` DISABLE KEYS */;
INSERT INTO `contents` VALUES
(1,'Отступники','movie','Криминальная драма: полицейский тайно внедряется в ирландскую мафию, а мафиозный информатор одновременно работает в полиции. Оба идут по следу друг друга.',2006,'18+',151,NULL,'Мартин Скорсезе','Леонардо ДиКаприо, Мэтт Дэймон, Джек Николсон','https://rutube.ru/play/embed/29562ab005d197828415304d0b1888cd/','assets/img/poster-otstupniki.jpg','assets/img/poster-otstupniki.jpg',0,1,0.00,'2026-11-19 12:01:42'),
(2,'Интерстеллар','movie','Команда исследователей отправляется сквозь червоточину в поисках нового дома для человечества. Эпическая фантастика Кристофера Нолана.',2014,'16+',169,NULL,'Кристофер Нолан','Мэттью Макконахи, Энн Хэтэуэй, Джессика Честейн','https://rutube.ru/play/embed/ff9ab42ec1558c84d684573c4688b792/','assets/img/poster-interstellar.jpg','assets/img/poster-interstellar.jpg',0,1,0.00,'2026-11-19 12:01:42'),
(3,'Ла-Ла Ленд','movie','Джазовый пианист и начинающая актриса влюбляются в Лос-Анджелесе, мечтая о славе. Музыкальная мелодрама, завоевавшая несколько «Оскаров».',2016,'16+',128,NULL,'Дэмьен Шазелл','Райан Гослинг, Эмма Стоун','https://frontend.vh.yandex.ru/player/14946618591080577539?autoplay=0&service=ya-video&from=yavideo','assets/img/poster-la-la-land.jpg','assets/img/poster-la-la-land.jpg',0,1,299.00,'2026-11-19 12:01:42'),
(4,'Один дома','movie','Семья улетает на рождественские каникулы, случайно забыв дома восьмилетнего Кевина. Мальчику предстоит защитить дом от двух грабителей.',1990,'6+',103,NULL,'Крис Коламбус','Маколей Калкин, Джо Пеши, Дэниел Стерн','https://frontend.vh.yandex.ru/player/12304411063656311621?autoplay=0&service=ya-video&from=yavideo','assets/img/poster-odin-doma.jpg','assets/img/poster-odin-doma.jpg',0,0,0.00,'2026-11-19 12:01:42'),
(5,'Оно','movie','Семеро подростков в маленьком городке сталкиваются со злобным клоуном Пеннивайзом, который питается их страхами.',2017,'18+',135,NULL,'Андрес Мускетти','Билл Скарсгард, Джейден Мартелл, София Лиллис','https://frontend.vh.yandex.ru/player/915576367850982588?autoplay=0&service=ya-video&from=yavideo','assets/img/poster-ono.jpg','assets/img/poster-ono.jpg',0,0,249.00,'2026-11-19 12:01:42'),
(6,'Во все тяжкие','series','Учитель химии Уолтер Уайт, узнав о смертельном диагнозе, начинает варить метамфетамин, чтобы обеспечить семью. Культовый криминальный сериал.',2008,'18+',NULL,5,'Винс Гиллиган','Брайан Крэнстон, Аарон Пол','https://rutube.ru/play/embed/248335c2f84ee9b66c95ce830075f7de/','assets/img/poster-vo-vse-tyazhkie.jpg','assets/img/poster-vo-vse-tyazhkie.jpg',0,1,399.00,'2026-11-19 12:01:42'),
(7,'Игра престолов','series','За Железный трон Семи Королевств борются знатные дома, преследуя свои цели. Интриги, битвы и драконы в эпопее по книгам Джорджа Мартина.',2011,'18+',NULL,8,'Дэвид Бениофф, Д.Б. Уайсс','Питер Динклэйдж, Кит Харингтон, Эмилия Кларк','https://rutube.ru/play/embed/d45671e2ed2341c600b039c7dd5cae9f/','assets/img/poster-igra-prestolov.jpg','assets/img/poster-igra-prestolov.jpg',0,1,0.00,'2026-11-19 12:01:42'),
(8,'Кухня','series','Шеф-повар ресторана вынужден работать с новой неопытной командой. Российский комедийный сериал о кухне, любви и кулинарных войнах.',2012,'12+',NULL,6,'Дмитрий Дьяченко','Дмитрий Назаров, Марк Богатырёв, Елена Подкаминская','https://rutube.ru/play/embed/bdd980f878022a11757f2f466a4edc61/','assets/img/poster-kuhnya.jpg','assets/img/poster-kuhnya.jpg',0,1,0.00,'2026-11-19 12:01:42'),
(9,'Папины дочки','series','Психолог-одиночка воспитывает пять дочерей, каждая со своим характером. Легендарный российский семейный сериал.',2007,'12+',NULL,20,'Андрей Кузнецов','Андрей Леонов, Нонна Гришаева, Мирослава Карпович','https://frontend.vh.yandex.ru/player/16316327094306160298?autoplay=0&service=ya-video&from=yavideo','assets/img/poster-papiny-dochki.jpg','assets/img/poster-papiny-dochki.jpg',0,1,0.00,'2026-11-19 12:01:42'),
(10,'Чернобыль','series','История катастрофы на Чернобыльской АЭС и самоотверженной борьбы с её последствиями. Мини-сериал HBO, признанный одной из лучших драм десятилетия.',2019,'18+',NULL,1,'Йохан Ренк','Джаред Харрис, Стеллан Скарсгард, Эмили Уотсон','https://rutube.ru/play/embed/076da351c90207122bfcf311988c89fc/','assets/img/poster-chernobyl.jpg','assets/img/poster-chernobyl.jpg',1,1,349.00,'2026-11-19 12:01:42'),
(11,'Побег из Шоушенка','movie','Бухгалтер Энди Дюфрейн осуждён пожизненно за убийство, которого не совершал. В тюрьме Шоушенк он находит дружбу и надежду, не сломавшись перед жестокостью системы.',1994,'16+',142,NULL,'Фрэнк Дарабонт','Тим Роббинс, Морган Фриман','https://frontend.vh.yandex.ru/player/15765104420469712819?autoplay=0&service=ya-video&from=yavideo','assets/img/poster-shoushenk.jpg','assets/img/poster-shoushenk.jpg',0,1,299.00,'2026-11-19 12:06:41'),
(12,'Тёмный рыцарь','movie','Бэтмен противостоит Джокеру, который погружает Готэм в хаос. Один из лучших супергеройских фильмов всех времён.',2008,'16+',152,NULL,'Кристофер Нолан','Кристиан Бэйл, Хит Леджер, Аарон Экхарт','https://rutube.ru/play/embed/e5eca47f493d9e799f7a0d66d5e9615a/','assets/img/poster-temnyy-rytsar.jpg','assets/img/poster-temnyy-rytsar.jpg',0,1,349.00,'2026-11-19 12:06:41'),
(13,'Властелин колец: Братство кольца','movie','Хоббит Фродо отправляется уничтожить Кольцо Всевластия в Мордоре. Начало величайшей фэнтезийной эпопеи.',2001,'12+',178,NULL,'Питер Джексон','Элайджа Вуд, Иэн Маккеллен, Вигго Мортенсен','https://rutube.ru/play/embed/6d9ce41f41669cbe142d3968cc9daa5d/','assets/img/poster-vlastelin-kolets.jpg','assets/img/poster-vlastelin-kolets.jpg',0,1,399.00,'2026-11-19 12:06:41'),
(14,'Криминальное чтиво','movie','Переплетённые истории гангстеров, боксёра и случайных людей в Лос-Анджелесе. Культовая криминальная комедия Квентина Тарантино.',1994,'18+',154,NULL,'Квентин Тарантино','Джон Траволта, Сэмюэл Л. Джексон, Ума Турман','https://rutube.ru/play/embed/f666a89415c7612d953ff1d3e086f065/','assets/img/poster-kriminalnoe-chtivo.jpg','assets/img/poster-kriminalnoe-chtivo.jpg',0,1,299.00,'2026-11-19 12:06:41'),
(15,'Титаник','movie','Юная Роуз и бедный Джек влюбляются на борту «непотопляемого» Титаника. История любви на фоне величайшей катастрофы.',1997,'12+',195,NULL,'Джеймс Кэмерон','Леонардо ДиКаприо, Кейт Уинслет','https://frontend.vh.yandex.ru/player/6055593005830960403?autoplay=0&service=ya-video&from=yavideo','assets/img/poster-titanik.jpg','assets/img/poster-titanik.jpg',0,1,349.00,'2026-11-19 12:06:41'),
(16,'Брат','movie','Демобилизованный из Чечни Данила Багров приезжает в Петербург и вступает в войну с бандитами. Легендарный фильм 90-х.',1997,'16+',96,NULL,'Алексей Балабанов','Сергей Бодров-мл., Виктор Сухоруков','https://rutube.ru/play/embed/1d7efabab0af6afdceb8dcf2e44d4535/','assets/img/poster-brat.jpg','assets/img/poster-brat.jpg',0,1,0.00,'2026-11-19 12:06:41'),
(17,'Джентльмены','movie','Американский эмигрант Микки Пирсон создал в Лондоне империю по выращиванию марихуаны. Когда бизнес решают отнять — начинается хаос.',2019,'18+',113,NULL,'Гай Ричи','Мэттью МакКонахи, Чарли Ханнэм, Колин Фаррелл','https://rutube.ru/play/embed/8c0789d96b16553aaffb7778dcab5abf/','assets/img/poster-dzhentlmeny.jpg','assets/img/poster-dzhentlmeny.jpg',0,1,349.00,'2026-11-19 12:06:41'),
(18,'1+1 (Неприкасаемые)','movie','Парализованный аристократ Филипп нанимает в сиделки безработного Дрисса. Неожиданная дружба двух миров.',2011,'12+',112,NULL,'Оливье Накаш, Эрик Толедано','Франсуа Клюзе, Омар Си','https://frontend.vh.yandex.ru/player/3953926274984684620?autoplay=0&service=ya-video&from=yavideo','assets/img/poster-plus-one.jpg','assets/img/poster-plus-one.jpg',0,0,0.00,'2026-11-19 12:06:41'),
(19,'Друзья','series','Шестеро друзей в Нью-Йорке проживают жизнь: работа, любовь, смешные ситуации и несбыточные мечты. Легендарный ситком.',1994,'12+',NULL,10,'Дэвид Крейн, Марта Кауффман','Дженнифер Энистон, Кортни Кокс, Мэттью Перри','https://rutube.ru/play/embed/16039de338d2317dd490468c15180b43/','assets/img/poster-druzya.jpg','assets/img/poster-druzya.jpg',0,1,299.00,'2026-11-19 12:06:41'),
(20,'Ход королевы','series','Юная шахматная вундеркинд Бет Хармон пробивается на вершину мира в патриархальном мире шахмат 1960-х.',2020,'16+',NULL,1,'Скотт Фрэнк','Аня Тейлор-Джой','https://frontend.vh.yandex.ru/player/16421079564486492677?autoplay=0&service=ya-video&from=yavideo','assets/img/poster-hod-korolevy.jpg','assets/img/poster-hod-korolevy.jpg',1,1,299.00,'2026-11-19 12:06:41'),
(21,'Ведьмак','series','Охотник на чудовищ Геральт из Ривии странствует по миру, где магия, судьбы и политика переплетены в кровопролитную борьбу.',2019,'18+',NULL,3,'Томаш Багиньский','Генри Кавилл, Аня Чалотра','https://rutube.ru/play/embed/9be3a0a3ad329a3acd31c46f4d2a2318/','assets/img/poster-vedmak.jpg','assets/img/poster-vedmak.jpg',0,1,349.00,'2026-11-19 12:06:41'),
(22,'Офис (США)','series','Сотрудники отделения бумажной компании Dunder Mifflin каждый день попадают в нелепые ситуации под руководством Майкла Скотта.',2005,'16+',NULL,9,'Грег Дэниелс','Стив Карелл, Рэйн Уилсон, Джон Красински','https://frontend.vh.yandex.ru/player/2889928400840003729?autoplay=0&service=ya-video&from=yavideo','assets/img/poster-ofis.jpg','assets/img/poster-ofis.jpg',0,1,299.00,'2026-11-19 12:06:41'),
(23,'Слово пацана','series','Казань конца 1980-х: подростковые уличные группировки борются за влияние. Драма о взрослении, чести и жестокости времени.',2023,'18+',NULL,1,'Жора Крыжовников','Иван Янковский, Рузиль Минекаев','https://rutube.ru/play/embed/281f5ba2f7aea671effc8651e0c5f2b8/','assets/img/poster-slovo-pacana.jpg','assets/img/poster-slovo-pacana.jpg',1,1,349.00,'2026-11-19 12:06:41'),
(24,'Айда!','series','Казань конца 1970-х: подросток Гриша из благополучной семьи сталкивается с жестокими законами улиц и молодёжной ОПГ «Тяп-ляп». Криминальная драма по реальным событиям.',2026,'18+',NULL,1,'Олег Асадулин','Андрей Мерзликин, Игорь Воробьёв, Владимир Вдовиченков','https://rutube.ru/play/embed/7a86c6255df6efa3ba74bdd374b25e39/','assets/img/poster-ayda.jpg','assets/img/poster-ayda.jpg',1,1,349.00,'2026-11-19 12:06:41'),
(25,'Лёд','movie','Надя — вундеркинд из провинции, мечтающая стать фигуристкой. После трагедии она встречает хоккеиста Сашу, который поможет ей снова встать на лёд.',2018,'12+',113,NULL,'Олег Трофим','Аглая Тарасова, Александр Петров, Милош Бикович','https://rutube.ru/play/embed/27953e969aeace23dccb9b43004745aa/','assets/img/poster-led.jpg','assets/img/poster-led.jpg',0,1,349.00,'2026-11-19 12:06:41'),
(26,'Движение вверх','movie','Сборная СССР по баскетболу готовится к легендарному матчу с американцами на Олимпиаде-1972. История о воле, команде и невозможной победе.',2017,'12+',133,NULL,'Антон Мегердичев','Владимир Машков, Андрей Смоляков, Иван Колесников','https://frontend.vh.yandex.ru/player/6220454448737730783?autoplay=0&service=ya-video&from=yavideo','assets/img/poster-dvizhenie-vverh.jpg','assets/img/poster-dvizhenie-vverh.jpg',0,1,349.00,'2026-11-19 12:06:41'),
(27,'Левиафан','movie','Житель северного городка Николай хочет отстоять свой дом и участок против мэра и коррумпированной системы. Драма Андрея Звягинцева.',2014,'18+',140,NULL,'Андрей Звягинцев','Алексей Серебряков, Елена Лядова, Роман Мадянов','https://frontend.vh.yandex.ru/player/13039806949545083656?autoplay=0&service=ya-video&from=yavideo','assets/img/poster-leviafan.jpg','assets/img/poster-leviafan.jpg',0,0,299.00,'2026-11-19 12:06:41'),
(28,'Экипаж','movie','Хрупкая девушка Саша поступает в лётное училище и готовится управлять самолётом. Испытание на прочность и любовь в небе.',2016,'12+',138,NULL,'Николай Лебедев','Данила Козловский, Владимир Машков, Агне Грудите','https://rutube.ru/play/embed/84f21ff7e70913f49e13091856874570/','assets/img/poster-ekipazh.jpg','assets/img/poster-ekipazh.jpg',0,1,299.00,'2026-11-19 12:06:41'),
(29,'Полицейский с Рублёвки','series','Полицейский Гриша Измайлов служит в элитном рублёвском районе Москвы, где расследования сплетаются с юмором и абсурдом.',2016,'16+',NULL,4,'Илья Куликов','Александр Петров, Александра Бортич, Сергей Бурунов','https://rutube.ru/play/embed/90f69ea60364afa93f78bed4830da0a2/','assets/img/poster-police-ruble.jpg','assets/img/poster-police-ruble.jpg',0,1,299.00,'2026-11-19 12:06:41'),
(30,'Мажор','series','Избалованный сын миллионера Игорь Соколовский после инцидента вынужден работать простым опером в полиции.',2014,'18+',NULL,4,'Константин Статский','Павел Прилучный, Карина Разумовская, Дмитрий Шевченко','https://rutube.ru/play/embed/7cb574dc083fc392f63f3c988c3a8e26/','assets/img/poster-mazhor.jpg','assets/img/poster-mazhor.jpg',0,1,349.00,'2026-11-19 12:06:41'),
(31,'Вампиры средней полосы','series','Смоленск: семья вампиров мирно живёт среди людей, скрываясь под видом семьи Смирновых. Чёрная комедия о том, как сохранить семью.',2021,'16+',NULL,2,'Антон Маслов','Юрий Стоянов, Татьяна Догилева, Артём Ткаченко','https://frontend.vh.yandex.ru/player/13280309148466110857?autoplay=0&service=ya-video&from=yavideo','assets/img/poster-vampiry.jpg','assets/img/poster-vampiry.jpg',1,1,299.00,'2026-11-19 12:06:41'),
(32,'Горько!','movie','Свадьба в русском стиле: родители молодых готовят грандиозный праздник, а сами новобрачные мечтают сбежать. Комедия с Сергеем Светлаковым.',2013,'16+',100,NULL,'Жора Крыжовников','Сергей Светлаков, Юлия Александрова, Егор Корешков','https://rutube.ru/play/embed/c459beba80f7c58ddaec88d28db58073/','assets/img/poster-gorko.jpg','assets/img/poster-gorko.jpg',0,1,0.00,'2026-11-19 12:07:18'),
(33,'Холоп','movie','Молодой мажор Гриша попадает в прошлое и становится холопом. Ему предстоит переосмыслить свою жизнь. Кассовая комедия.',2019,'12+',109,NULL,'Клим Шипенко','Милош Бикович, Александра Бортич, Иван Охлобыстин','https://rutube.ru/play/embed/86c040f671510654c79de969d023d43d/','assets/img/poster-holop.jpg','assets/img/poster-holop.jpg',0,1,0.00,'2026-11-19 12:07:18'),
(34,'Т-34','movie','Курсант Николай Ивушкин попадает в плен, но мечтает сбежать на легендарном танке Т-34. Военная драма с масштабными боями.',2018,'12+',139,NULL,'Алексей Сидоров','Александр Петров, Виктор Добронравов, Винценц Кифер','https://rutube.ru/play/embed/41f117dbbcdf481a653bc9097862464c/','assets/img/poster-t-34.jpg','assets/img/poster-t-34.jpg',0,1,0.00,'2026-11-19 12:07:18'),
(35,'Жмурки','movie','Два незадачливых бандита получают задание разобраться с конкурентами. Чёрная комедия Алексея Балабанова.',2005,'18+',111,NULL,'Алексей Балабанов','Алексей Панин, Дмитрий Дюжев, Никита Михалков','https://rutube.ru/play/embed/554f06a14aa3cef96a48e46a0ab68cab/','assets/img/poster-zhmurki.jpg','assets/img/poster-zhmurki.jpg',0,0,0.00,'2026-11-19 12:07:18'),
(36,'Любовь и голуби','movie','Василий Кузякин уезжает на юг и попадает в водоворот курортного романа. Классическая советская комедия.',1984,'12+',107,NULL,'Владимир Меньшов','Александр Михайлов, Нина Дорошина, Людмила Гурченко','https://rutube.ru/play/embed/d2f8a7df253fdc16a4b1c7356f25407d/','assets/img/poster-lubov-golubi.jpg','assets/img/poster-lubov-golubi.jpg',0,1,0.00,'2026-11-19 12:07:18'),
(37,'Ирония судьбы. Продолжение','movie','Спустя годы Павел Лукашин снова встречает Женю Лукашину после бани и перелёта в Петербург. Продолжение легендарной комедии.',2007,'12+',114,NULL,'Тимур Бекмамбетов','Константин Хабенский, Елизавета Боярская, Сергей Безруков','https://rutube.ru/play/embed/2bebcd7df189f1aaf351c5179f84ea26/','assets/img/poster-ironiya.jpg','assets/img/poster-ironiya.jpg',0,0,0.00,'2026-11-19 12:07:18'),
(38,'Метод','series','Следователь-одиночка Родион Меглин по кличке «Метод» ловит серийных убийц. Психологический триллер с Константином Хабенским.',2015,'18+',NULL,3,'Юрий Быков','Константин Хабенский, Паулина Андреева, Александр Петров','https://rutube.ru/play/embed/d07be5fbfbd258dbbfc0ea67e6f64a5d/','assets/img/poster-metod.jpg','assets/img/poster-metod.jpg',0,1,349.00,'2026-11-19 12:07:18'),
(39,'Бригада','series','Саша Белый и его друзья поднимаются по криминальной лестнице Москвы 90-х. Легендарный российский бандитский сериал.',2002,'16+',NULL,1,'Алексей Сидоров','Сергей Безруков, Дмитрий Дюжев, Павел Майков','https://rutube.ru/play/embed/d566820a36e35dd7e5207fbae4ba38ea/','assets/img/poster-brigada.jpg','assets/img/poster-brigada.jpg',0,1,299.00,'2026-11-19 12:07:18');
/*!40000 ALTER TABLE `contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favorites` (
  `user_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`,`content_id`),
  KEY `content_id` (`content_id`),
  CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('new','paid','cancelled') NOT NULL DEFAULT 'new',
  `created_at` datetime DEFAULT current_timestamp(),
  `paid_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `plan_id` (`plan_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES
(1,2,2,499.00,'paid','2026-11-19 12:01:42','2026-11-19 12:01:42');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plans`
--

DROP TABLE IF EXISTS `plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `months` int(11) NOT NULL DEFAULT 1,
  `quality` varchar(40) DEFAULT 'HD',
  `devices` int(11) DEFAULT 1,
  `features` text DEFAULT NULL,
  `is_recommended` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plans`
--

LOCK TABLES `plans` WRITE;
/*!40000 ALTER TABLE `plans` DISABLE KEYS */;
INSERT INTO `plans` VALUES
(1,'Базовый',299.00,1,'HD 720p',1,'Доступ к библиотеке фильмов; Качество HD; 1 устройство; Без рекламы',0),
(2,'Стандарт',499.00,1,'Full HD 1080p',2,'Вся библиотека фильмов и сериалов; Качество Full HD; 2 устройства; Без рекламы; Скачивание на 1 устройство',1),
(3,'Премиум',799.00,1,'4K Ultra HD',4,'Доступ ко всему контенту; Качество 4K Ultra HD + HDR; 4 устройства; Скачивание на 3 устройства; Приоритетная поддержка',0);
/*!40000 ALTER TABLE `plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_user_content` (`user_id`,`content_id`),
  KEY `content_id` (`content_id`),
  CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ratings`
--

LOCK TABLES `ratings` WRITE;
/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;
/*!40000 ALTER TABLE `ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `started_at` datetime DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  `status` enum('active','expired') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `plan_id` (`plan_id`),
  CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
INSERT INTO `subscriptions` VALUES
(1,2,2,'2026-11-19 12:01:42','2026-12-19 12:01:42','active');
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_name` varchar(60) NOT NULL,
  `first_name` varchar(60) NOT NULL,
  `middle_name` varchar(60) DEFAULT NULL,
  `login` varchar(50) NOT NULL,
  `email` varchar(120) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','subscriber','user') NOT NULL DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Админов','Артём','Сергеевич','admin','admin@illuminos.ru','$2y$10$XFlRIefZPb00bG0MsA2dauro5hOkNxHeC5b/mJQcIB3YOM8jMt1Ue','admin','2026-11-19 12:01:42'),
(2,'Иванов','Иван','Иванович','ivanov','ivanov@mail.ru','$2y$10$wxYcqEalF6sUeYxyWHTQv./JwNUkNXvl273J1R2GxgM0ZZW//7PYO','subscriber','2026-11-19 12:01:42'),
(3,'Петрова','Мария','Александровна','petrova','petrova@mail.ru','$2y$10$sL.N3wu8AsFfCySXk0MyaeNTgwk9nrXuuyfhm3PgUHZGgxbLVl0aO','user','2026-11-19 12:01:42');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `watch_history`
--

DROP TABLE IF EXISTS `watch_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `watch_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `watched_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `content_id` (`content_id`),
  CONSTRAINT `watch_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `watch_history_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `watch_history`
--

LOCK TABLES `watch_history` WRITE;
/*!40000 ALTER TABLE `watch_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `watch_history` ENABLE KEYS */;
UNLOCK TABLES;
--
-- Table structure for table `seasons`
--

DROP TABLE IF EXISTS `episodes`;
DROP TABLE IF EXISTS `seasons`;
CREATE TABLE `seasons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`),
  CONSTRAINT `seasons_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `episodes`
--

CREATE TABLE `episodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `season_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration_min` int(11) DEFAULT '45',
  PRIMARY KEY (`id`),
  KEY `season_id` (`season_id`),
  CONSTRAINT `episodes_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `seasons` VALUES (1,6,1),(2,6,2),(3,6,3),(4,6,4),(5,6,5),(6,7,1),(7,7,2),(8,7,3),(9,7,4),(10,7,5),(11,7,6),(12,7,7),(13,7,8),(14,8,1),(15,8,2),(16,8,3),(17,8,4),(18,8,5),(19,8,6),(20,9,1),(21,9,2),(22,9,3),(23,9,4),(24,9,5),(25,9,6),(26,9,7),(27,9,8),(28,9,9),(29,9,10),(30,9,11),(31,9,12),(32,9,13),(33,9,14),(34,9,15),(35,9,16),(36,9,17),(37,9,18),(38,9,19),(39,9,20),(40,10,1),(41,19,1),(42,19,2),(43,19,3),(44,19,4),(45,19,5),(46,19,6),(47,19,7),(48,19,8),(49,19,9),(50,19,10),(51,20,1),(52,21,1),(53,21,2),(54,21,3),(55,22,1),(56,22,2),(57,22,3),(58,22,4),(59,22,5),(60,22,6),(61,22,7),(62,22,8),(63,22,9),(64,23,1),(65,24,1),(66,29,1),(67,29,2),(68,29,3),(69,29,4),(70,29,5),(71,30,1),(72,30,2),(73,30,3),(74,30,4),(75,30,5),(76,31,1),(77,31,2),(78,38,1),(79,38,2),(80,38,3),(81,39,1);
INSERT INTO `episodes` (`season_id`, `number`, `title`, `duration_min`) VALUES (1,1,NULL,47),(1,2,NULL,47),(1,3,NULL,47),(1,4,NULL,47),(1,5,NULL,47),(1,6,NULL,47),(1,7,NULL,47),(2,1,NULL,47),(2,2,NULL,47),(2,3,NULL,47),(2,4,NULL,47),(2,5,NULL,47),(2,6,NULL,47),(2,7,NULL,47),(2,8,NULL,47),(2,9,NULL,47),(2,10,NULL,47),(2,11,NULL,47),(2,12,NULL,47),(2,13,NULL,47),(3,1,NULL,47),(3,2,NULL,47),(3,3,NULL,47),(3,4,NULL,47),(3,5,NULL,47),(3,6,NULL,47),(3,7,NULL,47),(3,8,NULL,47),(3,9,NULL,47),(3,10,NULL,47),(3,11,NULL,47),(3,12,NULL,47),(3,13,NULL,47),(4,1,NULL,47),(4,2,NULL,47),(4,3,NULL,47),(4,4,NULL,47),(4,5,NULL,47),(4,6,NULL,47),(4,7,NULL,47),(4,8,NULL,47),(4,9,NULL,47),(4,10,NULL,47),(4,11,NULL,47),(4,12,NULL,47),(4,13,NULL,47),(5,1,NULL,47),(5,2,NULL,47),(5,3,NULL,47),(5,4,NULL,47),(5,5,NULL,47),(5,6,NULL,47),(5,7,NULL,47),(5,8,NULL,47),(5,9,NULL,47),(5,10,NULL,47),(5,11,NULL,47),(5,12,NULL,47),(5,13,NULL,47),(5,14,NULL,47),(5,15,NULL,47),(5,16,NULL,47),(6,1,NULL,57),(6,2,NULL,57),(6,3,NULL,57),(6,4,NULL,57),(6,5,NULL,57),(6,6,NULL,57),(6,7,NULL,57),(6,8,NULL,57),(6,9,NULL,57),(6,10,NULL,57),(7,1,NULL,57),(7,2,NULL,57),(7,3,NULL,57),(7,4,NULL,57),(7,5,NULL,57),(7,6,NULL,57),(7,7,NULL,57),(7,8,NULL,57),(7,9,NULL,57),(7,10,NULL,57),(8,1,NULL,57),(8,2,NULL,57),(8,3,NULL,57),(8,4,NULL,57),(8,5,NULL,57),(8,6,NULL,57),(8,7,NULL,57),(8,8,NULL,57),(8,9,NULL,57),(8,10,NULL,57),(9,1,NULL,57),(9,2,NULL,57),(9,3,NULL,57),(9,4,NULL,57),(9,5,NULL,57),(9,6,NULL,57),(9,7,NULL,57),(9,8,NULL,57),(9,9,NULL,57),(9,10,NULL,57),(10,1,NULL,57),(10,2,NULL,57),(10,3,NULL,57),(10,4,NULL,57),(10,5,NULL,57),(10,6,NULL,57),(10,7,NULL,57),(10,8,NULL,57),(10,9,NULL,57),(10,10,NULL,57),(11,1,NULL,57),(11,2,NULL,57),(11,3,NULL,57),(11,4,NULL,57),(11,5,NULL,57),(11,6,NULL,57),(11,7,NULL,57),(11,8,NULL,57),(11,9,NULL,57),(11,10,NULL,57),(12,1,NULL,57),(12,2,NULL,57),(12,3,NULL,57),(12,4,NULL,57),(12,5,NULL,57),(12,6,NULL,57),(12,7,NULL,57),(13,1,NULL,57),(13,2,NULL,57),(13,3,NULL,57),(13,4,NULL,57),(13,5,NULL,57),(13,6,NULL,57),(14,1,NULL,25),(14,2,NULL,25),(14,3,NULL,25),(14,4,NULL,25),(14,5,NULL,25),(14,6,NULL,25),(14,7,NULL,25),(14,8,NULL,25),(14,9,NULL,25),(14,10,NULL,25),(14,11,NULL,25),(14,12,NULL,25),(14,13,NULL,25),(14,14,NULL,25),(14,15,NULL,25),(14,16,NULL,25),(14,17,NULL,25),(14,18,NULL,25),(14,19,NULL,25),(14,20,NULL,25),(15,1,NULL,25),(15,2,NULL,25),(15,3,NULL,25),(15,4,NULL,25),(15,5,NULL,25),(15,6,NULL,25),(15,7,NULL,25),(15,8,NULL,25),(15,9,NULL,25),(15,10,NULL,25),(15,11,NULL,25),(15,12,NULL,25),(15,13,NULL,25),(15,14,NULL,25),(15,15,NULL,25),(15,16,NULL,25),(15,17,NULL,25),(15,18,NULL,25),(15,19,NULL,25),(15,20,NULL,25),(16,1,NULL,25),(16,2,NULL,25),(16,3,NULL,25),(16,4,NULL,25),(16,5,NULL,25),(16,6,NULL,25),(16,7,NULL,25),(16,8,NULL,25),(16,9,NULL,25),(16,10,NULL,25),(16,11,NULL,25),(16,12,NULL,25),(16,13,NULL,25),(16,14,NULL,25),(16,15,NULL,25),(16,16,NULL,25),(16,17,NULL,25),(16,18,NULL,25),(16,19,NULL,25),(16,20,NULL,25),(17,1,NULL,25),(17,2,NULL,25),(17,3,NULL,25),(17,4,NULL,25),(17,5,NULL,25),(17,6,NULL,25),(17,7,NULL,25),(17,8,NULL,25),(17,9,NULL,25),(17,10,NULL,25),(17,11,NULL,25),(17,12,NULL,25),(17,13,NULL,25),(17,14,NULL,25),(17,15,NULL,25),(17,16,NULL,25),(17,17,NULL,25),(17,18,NULL,25),(17,19,NULL,25),(17,20,NULL,25),(18,1,NULL,25),(18,2,NULL,25),(18,3,NULL,25),(18,4,NULL,25),(18,5,NULL,25),(18,6,NULL,25),(18,7,NULL,25),(18,8,NULL,25),(18,9,NULL,25),(18,10,NULL,25),(18,11,NULL,25),(18,12,NULL,25),(18,13,NULL,25),(18,14,NULL,25),(18,15,NULL,25),(18,16,NULL,25),(18,17,NULL,25),(18,18,NULL,25),(18,19,NULL,25),(18,20,NULL,25),(19,1,NULL,25),(19,2,NULL,25),(19,3,NULL,25),(19,4,NULL,25),(19,5,NULL,25),(19,6,NULL,25),(19,7,NULL,25),(19,8,NULL,25),(19,9,NULL,25),(19,10,NULL,25),(19,11,NULL,25),(19,12,NULL,25),(19,13,NULL,25),(19,14,NULL,25),(19,15,NULL,25),(19,16,NULL,25),(19,17,NULL,25),(19,18,NULL,25),(19,19,NULL,25),(19,20,NULL,25),(20,1,NULL,22),(20,2,NULL,22),(20,3,NULL,22),(20,4,NULL,22),(20,5,NULL,22),(20,6,NULL,22),(20,7,NULL,22),(20,8,NULL,22),(20,9,NULL,22),(20,10,NULL,22),(20,11,NULL,22),(20,12,NULL,22),(20,13,NULL,22),(20,14,NULL,22),(20,15,NULL,22),(20,16,NULL,22),(20,17,NULL,22),(20,18,NULL,22),(20,19,NULL,22),(20,20,NULL,22),(21,1,NULL,22),(21,2,NULL,22),(21,3,NULL,22),(21,4,NULL,22),(21,5,NULL,22),(21,6,NULL,22),(21,7,NULL,22),(21,8,NULL,22),(21,9,NULL,22),(21,10,NULL,22),(21,11,NULL,22),(21,12,NULL,22),(21,13,NULL,22),(21,14,NULL,22),(21,15,NULL,22),(21,16,NULL,22),(21,17,NULL,22),(21,18,NULL,22),(21,19,NULL,22),(21,20,NULL,22),(22,1,NULL,22),(22,2,NULL,22),(22,3,NULL,22),(22,4,NULL,22),(22,5,NULL,22),(22,6,NULL,22),(22,7,NULL,22),(22,8,NULL,22),(22,9,NULL,22),(22,10,NULL,22),(22,11,NULL,22),(22,12,NULL,22),(22,13,NULL,22),(22,14,NULL,22),(22,15,NULL,22),(22,16,NULL,22),(22,17,NULL,22),(22,18,NULL,22),(22,19,NULL,22),(22,20,NULL,22),(23,1,NULL,22),(23,2,NULL,22),(23,3,NULL,22),(23,4,NULL,22),(23,5,NULL,22),(23,6,NULL,22),(23,7,NULL,22),(23,8,NULL,22),(23,9,NULL,22),(23,10,NULL,22),(23,11,NULL,22),(23,12,NULL,22),(23,13,NULL,22),(23,14,NULL,22),(23,15,NULL,22),(23,16,NULL,22),(23,17,NULL,22),(23,18,NULL,22),(23,19,NULL,22),(23,20,NULL,22),(24,1,NULL,22),(24,2,NULL,22),(24,3,NULL,22),(24,4,NULL,22),(24,5,NULL,22),(24,6,NULL,22),(24,7,NULL,22),(24,8,NULL,22),(24,9,NULL,22),(24,10,NULL,22),(24,11,NULL,22),(24,12,NULL,22),(24,13,NULL,22),(24,14,NULL,22),(24,15,NULL,22),(24,16,NULL,22),(24,17,NULL,22),(24,18,NULL,22),(24,19,NULL,22),(24,20,NULL,22),(25,1,NULL,22),(25,2,NULL,22),(25,3,NULL,22),(25,4,NULL,22),(25,5,NULL,22),(25,6,NULL,22),(25,7,NULL,22),(25,8,NULL,22),(25,9,NULL,22),(25,10,NULL,22),(25,11,NULL,22),(25,12,NULL,22),(25,13,NULL,22),(25,14,NULL,22),(25,15,NULL,22),(25,16,NULL,22),(25,17,NULL,22),(25,18,NULL,22),(25,19,NULL,22),(25,20,NULL,22),(26,1,NULL,22),(26,2,NULL,22),(26,3,NULL,22),(26,4,NULL,22),(26,5,NULL,22),(26,6,NULL,22),(26,7,NULL,22),(26,8,NULL,22),(26,9,NULL,22),(26,10,NULL,22),(26,11,NULL,22),(26,12,NULL,22),(26,13,NULL,22),(26,14,NULL,22),(26,15,NULL,22),(26,16,NULL,22),(26,17,NULL,22),(26,18,NULL,22),(26,19,NULL,22),(26,20,NULL,22),(26,21,NULL,22),(26,22,NULL,22),(26,23,NULL,22),(26,24,NULL,22),(26,25,NULL,22),(27,1,NULL,22),(27,2,NULL,22),(27,3,NULL,22),(27,4,NULL,22),(27,5,NULL,22),(27,6,NULL,22),(27,7,NULL,22),(27,8,NULL,22),(27,9,NULL,22),(27,10,NULL,22),(27,11,NULL,22),(27,12,NULL,22),(27,13,NULL,22),(27,14,NULL,22),(27,15,NULL,22),(27,16,NULL,22),(27,17,NULL,22),(27,18,NULL,22),(27,19,NULL,22),(27,20,NULL,22),(28,1,NULL,22),(28,2,NULL,22),(28,3,NULL,22),(28,4,NULL,22),(28,5,NULL,22),(28,6,NULL,22),(28,7,NULL,22),(28,8,NULL,22),(28,9,NULL,22),(28,10,NULL,22),(28,11,NULL,22),(28,12,NULL,22),(28,13,NULL,22),(28,14,NULL,22),(28,15,NULL,22),(28,16,NULL,22),(28,17,NULL,22),(28,18,NULL,22),(28,19,NULL,22),(28,20,NULL,22),(29,1,NULL,22),(29,2,NULL,22),(29,3,NULL,22),(29,4,NULL,22),(29,5,NULL,22),(29,6,NULL,22),(29,7,NULL,22),(29,8,NULL,22),(29,9,NULL,22),(29,10,NULL,22),(29,11,NULL,22),(29,12,NULL,22),(29,13,NULL,22),(29,14,NULL,22),(29,15,NULL,22),(29,16,NULL,22),(29,17,NULL,22),(29,18,NULL,22),(29,19,NULL,22),(29,20,NULL,22),(30,1,NULL,22),(30,2,NULL,22),(30,3,NULL,22),(30,4,NULL,22),(30,5,NULL,22),(30,6,NULL,22),(30,7,NULL,22),(30,8,NULL,22),(30,9,NULL,22),(30,10,NULL,22),(30,11,NULL,22),(30,12,NULL,22),(30,13,NULL,22),(30,14,NULL,22),(30,15,NULL,22),(30,16,NULL,22),(30,17,NULL,22),(30,18,NULL,22),(30,19,NULL,22),(30,20,NULL,22),(30,21,NULL,22),(30,22,NULL,22),(30,23,NULL,22),(30,24,NULL,22),(30,25,NULL,22),(31,1,NULL,22),(31,2,NULL,22),(31,3,NULL,22),(31,4,NULL,22),(31,5,NULL,22),(31,6,NULL,22),(31,7,NULL,22),(31,8,NULL,22),(31,9,NULL,22),(31,10,NULL,22),(31,11,NULL,22),(31,12,NULL,22),(31,13,NULL,22),(31,14,NULL,22),(31,15,NULL,22),(31,16,NULL,22),(31,17,NULL,22),(31,18,NULL,22),(31,19,NULL,22),(31,20,NULL,22),(32,1,NULL,22),(32,2,NULL,22),(32,3,NULL,22),(32,4,NULL,22),(32,5,NULL,22),(32,6,NULL,22),(32,7,NULL,22),(32,8,NULL,22),(32,9,NULL,22),(32,10,NULL,22),(32,11,NULL,22),(32,12,NULL,22),(32,13,NULL,22),(32,14,NULL,22),(32,15,NULL,22),(32,16,NULL,22),(32,17,NULL,22),(32,18,NULL,22),(32,19,NULL,22),(32,20,NULL,22),(33,1,NULL,22),(33,2,NULL,22),(33,3,NULL,22),(33,4,NULL,22),(33,5,NULL,22),(33,6,NULL,22),(33,7,NULL,22),(33,8,NULL,22),(33,9,NULL,22),(33,10,NULL,22),(33,11,NULL,22),(33,12,NULL,22),(33,13,NULL,22),(33,14,NULL,22),(33,15,NULL,22),(33,16,NULL,22),(33,17,NULL,22),(33,18,NULL,22),(33,19,NULL,22),(33,20,NULL,22),(34,1,NULL,22),(34,2,NULL,22),(34,3,NULL,22),(34,4,NULL,22),(34,5,NULL,22),(34,6,NULL,22),(34,7,NULL,22),(34,8,NULL,22),(34,9,NULL,22),(34,10,NULL,22),(34,11,NULL,22),(34,12,NULL,22),(34,13,NULL,22),(34,14,NULL,22),(34,15,NULL,22),(34,16,NULL,22),(34,17,NULL,22),(34,18,NULL,22),(34,19,NULL,22),(34,20,NULL,22),(35,1,NULL,22),(35,2,NULL,22),(35,3,NULL,22),(35,4,NULL,22),(35,5,NULL,22),(35,6,NULL,22),(35,7,NULL,22),(35,8,NULL,22),(35,9,NULL,22),(35,10,NULL,22),(35,11,NULL,22),(35,12,NULL,22),(35,13,NULL,22),(35,14,NULL,22),(35,15,NULL,22),(35,16,NULL,22),(35,17,NULL,22),(35,18,NULL,22),(35,19,NULL,22),(35,20,NULL,22),(36,1,NULL,22),(36,2,NULL,22),(36,3,NULL,22),(36,4,NULL,22),(36,5,NULL,22),(36,6,NULL,22),(36,7,NULL,22),(36,8,NULL,22),(36,9,NULL,22),(36,10,NULL,22),(36,11,NULL,22),(36,12,NULL,22),(36,13,NULL,22),(36,14,NULL,22),(36,15,NULL,22),(36,16,NULL,22),(36,17,NULL,22),(36,18,NULL,22),(36,19,NULL,22),(36,20,NULL,22),(37,1,NULL,22),(37,2,NULL,22),(37,3,NULL,22),(37,4,NULL,22),(37,5,NULL,22),(37,6,NULL,22),(37,7,NULL,22),(37,8,NULL,22),(37,9,NULL,22),(37,10,NULL,22),(37,11,NULL,22),(37,12,NULL,22),(37,13,NULL,22),(37,14,NULL,22),(37,15,NULL,22),(37,16,NULL,22),(37,17,NULL,22),(37,18,NULL,22),(37,19,NULL,22),(37,20,NULL,22),(38,1,NULL,22),(38,2,NULL,22),(38,3,NULL,22),(38,4,NULL,22),(38,5,NULL,22),(38,6,NULL,22),(38,7,NULL,22),(38,8,NULL,22),(38,9,NULL,22),(38,10,NULL,22),(38,11,NULL,22),(38,12,NULL,22),(38,13,NULL,22),(38,14,NULL,22),(38,15,NULL,22),(38,16,NULL,22),(38,17,NULL,22),(38,18,NULL,22),(38,19,NULL,22),(38,20,NULL,22),(39,1,NULL,22),(39,2,NULL,22),(39,3,NULL,22),(39,4,NULL,22),(39,5,NULL,22),(39,6,NULL,22),(39,7,NULL,22),(39,8,NULL,22),(39,9,NULL,22),(39,10,NULL,22),(39,11,NULL,22),(39,12,NULL,22),(39,13,NULL,22),(39,14,NULL,22),(39,15,NULL,22),(39,16,NULL,22),(39,17,NULL,22),(39,18,NULL,22),(39,19,NULL,22),(39,20,NULL,22),(40,1,'1:23:45',65),(40,2,'Сохраняйте спокойствие',65),(40,3,'Открывайте шире, земля',60),(40,4,'Счастье всего человечества',60),(40,5,'Вечная память',72),(41,1,NULL,22),(41,2,NULL,22),(41,3,NULL,22),(41,4,NULL,22),(41,5,NULL,22),(41,6,NULL,22),(41,7,NULL,22),(41,8,NULL,22),(41,9,NULL,22),(41,10,NULL,22),(41,11,NULL,22),(41,12,NULL,22),(41,13,NULL,22),(41,14,NULL,22),(41,15,NULL,22),(41,16,NULL,22),(41,17,NULL,22),(41,18,NULL,22),(41,19,NULL,22),(41,20,NULL,22),(41,21,NULL,22),(41,22,NULL,22),(41,23,NULL,22),(41,24,NULL,22),(42,1,NULL,22),(42,2,NULL,22),(42,3,NULL,22),(42,4,NULL,22),(42,5,NULL,22),(42,6,NULL,22),(42,7,NULL,22),(42,8,NULL,22),(42,9,NULL,22),(42,10,NULL,22),(42,11,NULL,22),(42,12,NULL,22),(42,13,NULL,22),(42,14,NULL,22),(42,15,NULL,22),(42,16,NULL,22),(42,17,NULL,22),(42,18,NULL,22),(42,19,NULL,22),(42,20,NULL,22),(42,21,NULL,22),(42,22,NULL,22),(42,23,NULL,22),(42,24,NULL,22),(43,1,NULL,22),(43,2,NULL,22),(43,3,NULL,22),(43,4,NULL,22),(43,5,NULL,22),(43,6,NULL,22),(43,7,NULL,22),(43,8,NULL,22),(43,9,NULL,22),(43,10,NULL,22),(43,11,NULL,22),(43,12,NULL,22),(43,13,NULL,22),(43,14,NULL,22),(43,15,NULL,22),(43,16,NULL,22),(43,17,NULL,22),(43,18,NULL,22),(43,19,NULL,22),(43,20,NULL,22),(43,21,NULL,22),(43,22,NULL,22),(43,23,NULL,22),(43,24,NULL,22),(43,25,NULL,22),(44,1,NULL,22),(44,2,NULL,22),(44,3,NULL,22),(44,4,NULL,22),(44,5,NULL,22),(44,6,NULL,22),(44,7,NULL,22),(44,8,NULL,22),(44,9,NULL,22),(44,10,NULL,22),(44,11,NULL,22),(44,12,NULL,22),(44,13,NULL,22),(44,14,NULL,22),(44,15,NULL,22),(44,16,NULL,22),(44,17,NULL,22),(44,18,NULL,22),(44,19,NULL,22),(44,20,NULL,22),(44,21,NULL,22),(44,22,NULL,22),(44,23,NULL,22),(44,24,NULL,22),(45,1,NULL,22),(45,2,NULL,22),(45,3,NULL,22),(45,4,NULL,22),(45,5,NULL,22),(45,6,NULL,22),(45,7,NULL,22),(45,8,NULL,22),(45,9,NULL,22),(45,10,NULL,22),(45,11,NULL,22),(45,12,NULL,22),(45,13,NULL,22),(45,14,NULL,22),(45,15,NULL,22),(45,16,NULL,22),(45,17,NULL,22),(45,18,NULL,22),(45,19,NULL,22),(45,20,NULL,22),(45,21,NULL,22),(45,22,NULL,22),(45,23,NULL,22),(45,24,NULL,22),(46,1,NULL,22),(46,2,NULL,22),(46,3,NULL,22),(46,4,NULL,22),(46,5,NULL,22),(46,6,NULL,22),(46,7,NULL,22),(46,8,NULL,22),(46,9,NULL,22),(46,10,NULL,22),(46,11,NULL,22),(46,12,NULL,22),(46,13,NULL,22),(46,14,NULL,22),(46,15,NULL,22),(46,16,NULL,22),(46,17,NULL,22),(46,18,NULL,22),(46,19,NULL,22),(46,20,NULL,22),(46,21,NULL,22),(46,22,NULL,22),(46,23,NULL,22),(46,24,NULL,22),(46,25,NULL,22),(47,1,NULL,22),(47,2,NULL,22),(47,3,NULL,22),(47,4,NULL,22),(47,5,NULL,22),(47,6,NULL,22),(47,7,NULL,22),(47,8,NULL,22),(47,9,NULL,22),(47,10,NULL,22),(47,11,NULL,22),(47,12,NULL,22),(47,13,NULL,22),(47,14,NULL,22),(47,15,NULL,22),(47,16,NULL,22),(47,17,NULL,22),(47,18,NULL,22),(47,19,NULL,22),(47,20,NULL,22),(47,21,NULL,22),(47,22,NULL,22),(47,23,NULL,22),(47,24,NULL,22),(48,1,NULL,22),(48,2,NULL,22),(48,3,NULL,22),(48,4,NULL,22),(48,5,NULL,22),(48,6,NULL,22),(48,7,NULL,22),(48,8,NULL,22),(48,9,NULL,22),(48,10,NULL,22),(48,11,NULL,22),(48,12,NULL,22),(48,13,NULL,22),(48,14,NULL,22),(48,15,NULL,22),(48,16,NULL,22),(48,17,NULL,22),(48,18,NULL,22),(48,19,NULL,22),(48,20,NULL,22),(48,21,NULL,22),(48,22,NULL,22),(48,23,NULL,22),(48,24,NULL,22),(49,1,NULL,22),(49,2,NULL,22),(49,3,NULL,22),(49,4,NULL,22),(49,5,NULL,22),(49,6,NULL,22),(49,7,NULL,22),(49,8,NULL,22),(49,9,NULL,22),(49,10,NULL,22),(49,11,NULL,22),(49,12,NULL,22),(49,13,NULL,22),(49,14,NULL,22),(49,15,NULL,22),(49,16,NULL,22),(49,17,NULL,22),(49,18,NULL,22),(49,19,NULL,22),(49,20,NULL,22),(49,21,NULL,22),(49,22,NULL,22),(49,23,NULL,22),(49,24,NULL,22),(50,1,NULL,22),(50,2,NULL,22),(50,3,NULL,22),(50,4,NULL,22),(50,5,NULL,22),(50,6,NULL,22),(50,7,NULL,22),(50,8,NULL,22),(50,9,NULL,22),(50,10,NULL,22),(50,11,NULL,22),(50,12,NULL,22),(50,13,NULL,22),(50,14,NULL,22),(50,15,NULL,22),(50,16,NULL,22),(50,17,NULL,22),(50,18,NULL,22),(51,1,NULL,59),(51,2,NULL,65),(51,3,NULL,56),(51,4,NULL,48),(51,5,NULL,48),(51,6,NULL,60),(51,7,NULL,67),(52,1,NULL,60),(52,2,NULL,60),(52,3,NULL,60),(52,4,NULL,60),(52,5,NULL,60),(52,6,NULL,60),(52,7,NULL,60),(52,8,NULL,60),(53,1,NULL,60),(53,2,NULL,60),(53,3,NULL,60),(53,4,NULL,60),(53,5,NULL,60),(53,6,NULL,60),(53,7,NULL,60),(53,8,NULL,60),(54,1,NULL,60),(54,2,NULL,60),(54,3,NULL,60),(54,4,NULL,60),(54,5,NULL,60),(54,6,NULL,60),(54,7,NULL,60),(54,8,NULL,60),(55,1,NULL,22),(55,2,NULL,22),(55,3,NULL,22),(55,4,NULL,22),(55,5,NULL,22),(55,6,NULL,22),(56,1,NULL,22),(56,2,NULL,22),(56,3,NULL,22),(56,4,NULL,22),(56,5,NULL,22),(56,6,NULL,22),(56,7,NULL,22),(56,8,NULL,22),(56,9,NULL,22),(56,10,NULL,22),(56,11,NULL,22),(56,12,NULL,22),(56,13,NULL,22),(56,14,NULL,22),(56,15,NULL,22),(56,16,NULL,22),(56,17,NULL,22),(56,18,NULL,22),(56,19,NULL,22),(56,20,NULL,22),(56,21,NULL,22),(56,22,NULL,22),(57,1,NULL,22),(57,2,NULL,22),(57,3,NULL,22),(57,4,NULL,22),(57,5,NULL,22),(57,6,NULL,22),(57,7,NULL,22),(57,8,NULL,22),(57,9,NULL,22),(57,10,NULL,22),(57,11,NULL,22),(57,12,NULL,22),(57,13,NULL,22),(57,14,NULL,22),(57,15,NULL,22),(57,16,NULL,22),(57,17,NULL,22),(57,18,NULL,22),(57,19,NULL,22),(57,20,NULL,22),(57,21,NULL,22),(57,22,NULL,22),(57,23,NULL,22),(57,24,NULL,22),(57,25,NULL,22),(58,1,NULL,22),(58,2,NULL,22),(58,3,NULL,22),(58,4,NULL,22),(58,5,NULL,22),(58,6,NULL,22),(58,7,NULL,22),(58,8,NULL,22),(58,9,NULL,22),(58,10,NULL,22),(58,11,NULL,22),(58,12,NULL,22),(58,13,NULL,22),(58,14,NULL,22),(58,15,NULL,22),(58,16,NULL,22),(58,17,NULL,22),(58,18,NULL,22),(58,19,NULL,22),(59,1,NULL,22),(59,2,NULL,22),(59,3,NULL,22),(59,4,NULL,22),(59,5,NULL,22),(59,6,NULL,22),(59,7,NULL,22),(59,8,NULL,22),(59,9,NULL,22),(59,10,NULL,22),(59,11,NULL,22),(59,12,NULL,22),(59,13,NULL,22),(59,14,NULL,22),(59,15,NULL,22),(59,16,NULL,22),(59,17,NULL,22),(59,18,NULL,22),(59,19,NULL,22),(59,20,NULL,22),(59,21,NULL,22),(59,22,NULL,22),(59,23,NULL,22),(59,24,NULL,22),(59,25,NULL,22),(59,26,NULL,22),(59,27,NULL,22),(59,28,NULL,22),(60,1,NULL,22),(60,2,NULL,22),(60,3,NULL,22),(60,4,NULL,22),(60,5,NULL,22),(60,6,NULL,22),(60,7,NULL,22),(60,8,NULL,22),(60,9,NULL,22),(60,10,NULL,22),(60,11,NULL,22),(60,12,NULL,22),(60,13,NULL,22),(60,14,NULL,22),(60,15,NULL,22),(60,16,NULL,22),(60,17,NULL,22),(60,18,NULL,22),(60,19,NULL,22),(60,20,NULL,22),(60,21,NULL,22),(60,22,NULL,22),(60,23,NULL,22),(60,24,NULL,22),(60,25,NULL,22),(60,26,NULL,22),(61,1,NULL,22),(61,2,NULL,22),(61,3,NULL,22),(61,4,NULL,22),(61,5,NULL,22),(61,6,NULL,22),(61,7,NULL,22),(61,8,NULL,22),(61,9,NULL,22),(61,10,NULL,22),(61,11,NULL,22),(61,12,NULL,22),(61,13,NULL,22),(61,14,NULL,22),(61,15,NULL,22),(61,16,NULL,22),(61,17,NULL,22),(61,18,NULL,22),(61,19,NULL,22),(61,20,NULL,22),(61,21,NULL,22),(61,22,NULL,22),(61,23,NULL,22),(61,24,NULL,22),(61,25,NULL,22),(61,26,NULL,22),(62,1,NULL,22),(62,2,NULL,22),(62,3,NULL,22),(62,4,NULL,22),(62,5,NULL,22),(62,6,NULL,22),(62,7,NULL,22),(62,8,NULL,22),(62,9,NULL,22),(62,10,NULL,22),(62,11,NULL,22),(62,12,NULL,22),(62,13,NULL,22),(62,14,NULL,22),(62,15,NULL,22),(62,16,NULL,22),(62,17,NULL,22),(62,18,NULL,22),(62,19,NULL,22),(62,20,NULL,22),(62,21,NULL,22),(62,22,NULL,22),(62,23,NULL,22),(62,24,NULL,22),(63,1,NULL,22),(63,2,NULL,22),(63,3,NULL,22),(63,4,NULL,22),(63,5,NULL,22),(63,6,NULL,22),(63,7,NULL,22),(63,8,NULL,22),(63,9,NULL,22),(63,10,NULL,22),(63,11,NULL,22),(63,12,NULL,22),(63,13,NULL,22),(63,14,NULL,22),(63,15,NULL,22),(63,16,NULL,22),(63,17,NULL,22),(63,18,NULL,22),(63,19,NULL,22),(63,20,NULL,22),(63,21,NULL,22),(63,22,NULL,22),(63,23,NULL,22),(63,24,NULL,22),(63,25,NULL,22),(64,1,NULL,50),(64,2,NULL,50),(64,3,NULL,50),(64,4,NULL,50),(64,5,NULL,50),(64,6,NULL,50),(64,7,NULL,50),(64,8,NULL,50),(65,1,NULL,50),(65,2,NULL,50),(65,3,NULL,50),(65,4,NULL,50),(65,5,NULL,50),(65,6,NULL,50),(65,7,NULL,50),(65,8,NULL,50),(66,1,NULL,50),(66,2,NULL,50),(66,3,NULL,50),(66,4,NULL,50),(66,5,NULL,50),(66,6,NULL,50),(66,7,NULL,50),(66,8,NULL,50),(67,1,NULL,50),(67,2,NULL,50),(67,3,NULL,50),(67,4,NULL,50),(67,5,NULL,50),(67,6,NULL,50),(67,7,NULL,50),(67,8,NULL,50),(68,1,NULL,50),(68,2,NULL,50),(68,3,NULL,50),(68,4,NULL,50),(68,5,NULL,50),(68,6,NULL,50),(68,7,NULL,50),(68,8,NULL,50),(69,1,NULL,50),(69,2,NULL,50),(69,3,NULL,50),(69,4,NULL,50),(69,5,NULL,50),(69,6,NULL,50),(69,7,NULL,50),(69,8,NULL,50),(70,1,NULL,50),(70,2,NULL,50),(70,3,NULL,50),(70,4,NULL,50),(70,5,NULL,50),(70,6,NULL,50),(70,7,NULL,50),(70,8,NULL,50),(71,1,NULL,51),(71,2,NULL,51),(71,3,NULL,51),(71,4,NULL,51),(71,5,NULL,51),(71,6,NULL,51),(71,7,NULL,51),(71,8,NULL,51),(71,9,NULL,51),(71,10,NULL,51),(71,11,NULL,51),(71,12,NULL,51),(72,1,NULL,51),(72,2,NULL,51),(72,3,NULL,51),(72,4,NULL,51),(72,5,NULL,51),(72,6,NULL,51),(72,7,NULL,51),(72,8,NULL,51),(72,9,NULL,51),(72,10,NULL,51),(72,11,NULL,51),(72,12,NULL,51),(73,1,NULL,51),(73,2,NULL,51),(73,3,NULL,51),(73,4,NULL,51),(73,5,NULL,51),(73,6,NULL,51),(73,7,NULL,51),(73,8,NULL,51),(73,9,NULL,51),(73,10,NULL,51),(73,11,NULL,51),(73,12,NULL,51),(73,13,NULL,51),(73,14,NULL,51),(73,15,NULL,51),(73,16,NULL,51),(74,1,NULL,51),(74,2,NULL,51),(74,3,NULL,51),(74,4,NULL,51),(74,5,NULL,51),(74,6,NULL,51),(74,7,NULL,51),(74,8,NULL,51),(75,1,NULL,51),(75,2,NULL,51),(75,3,NULL,51),(75,4,NULL,51),(75,5,NULL,51),(75,6,NULL,51),(75,7,NULL,51),(75,8,NULL,51),(76,1,NULL,50),(76,2,NULL,50),(76,3,NULL,50),(76,4,NULL,50),(76,5,NULL,50),(76,6,NULL,50),(76,7,NULL,50),(76,8,NULL,50),(77,1,NULL,50),(77,2,NULL,50),(77,3,NULL,50),(77,4,NULL,50),(77,5,NULL,50),(77,6,NULL,50),(77,7,NULL,50),(77,8,NULL,50),(78,1,NULL,48),(78,2,NULL,48),(78,3,NULL,48),(78,4,NULL,48),(78,5,NULL,48),(78,6,NULL,48),(78,7,NULL,48),(78,8,NULL,48),(78,9,NULL,48),(78,10,NULL,48),(78,11,NULL,48),(78,12,NULL,48),(78,13,NULL,48),(78,14,NULL,48),(78,15,NULL,48),(78,16,NULL,48),(79,1,NULL,48),(79,2,NULL,48),(79,3,NULL,48),(79,4,NULL,48),(79,5,NULL,48),(79,6,NULL,48),(79,7,NULL,48),(79,8,NULL,48),(79,9,NULL,48),(79,10,NULL,48),(79,11,NULL,48),(79,12,NULL,48),(79,13,NULL,48),(79,14,NULL,48),(79,15,NULL,48),(79,16,NULL,48),(80,1,NULL,48),(80,2,NULL,48),(80,3,NULL,48),(80,4,NULL,48),(80,5,NULL,48),(80,6,NULL,48),(80,7,NULL,48),(80,8,NULL,48),(81,1,NULL,45),(81,2,NULL,45),(81,3,NULL,45),(81,4,NULL,45),(81,5,NULL,45),(81,6,NULL,45),(81,7,NULL,45),(81,8,NULL,45),(81,9,NULL,45),(81,10,NULL,45),(81,11,NULL,45),(81,12,NULL,45),(81,13,NULL,45),(81,14,NULL,45),(81,15,NULL,45);

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed
