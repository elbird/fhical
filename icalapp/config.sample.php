<?php
/*

https://cis.technikum-wien.at/cis/private/lvplan/stpl_kalender.php
?
&pers_uid=ic12m025
&stg_kz=303
&sem=1
&ver=A
&grp=%20
&begin=1346450400
&ende=1359846000

Create statement used for creating the user table:
DROP TABLE user;

CREATE TABLE `user` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `googleid` varchar(50) NOT NULL,
 `name` varchar(255) NOT NULL,
 `email` varchar(255) NOT NULL,
 `twuser` varchar(50) DEFAULT NULL,
 `encryptedpass` text,
 `iv` text,
 `options` text
 PRIMARY KEY (`id`),
 UNIQUE KEY `googleid` (`googleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
class Config {
	public static function get() {
		$config = array();

		$config['clientId'] = ""; // Your Google APPs Client ID
		$config['clientSecret'] = ""; // Google OAUTH API Client Secret
		$config['redirectUri'] = ""; // the redirect URL for the OAuth API
		$config['developerKey'] = ""; // the Google Developer Key

		$config['mySqlHost'] = 'localhost'; //your DB host
		$config['mySqlUser'] = 'root'; // the DB user name
		$config['mySqlPass'] = ''; // the DB password
		$config['mySqlDb'] = 'fhical'; // the DB name
		$config['mySqlPort'] = 3306; // the DB port

		$config['icalUrlBaseParams'] = array('type' => 'student', 'format' => 'ical', 'version' => '2', 'target' => 'ical');
		$config['pageTitle'] = "FH Technikum Wien - Ical Downloader (für Google Calendar)";
		$config['copyright'] = '&copy; Sebastian Vogel / Theme: <a href="http://simpliste.ru/en/"">simpliste</a>';

		$config['menuItems'] = array(
				'home' => array('url' => 'index.php', 'name' => 'Home'),
				'userData' => array('url' => 'userDataForm.php', 'name' => 'User-Daten'),
				'options' => array('url' => 'setOptionsForm.php', 'name' => 'Optionen'),
				'impressum' => array('url' => 'impressum.php', 'name' => 'Impressum')
			);

		$config['fhTwCourses'] = json_decode('[
				{
					"id": "227",
					"short": "BBE",
					"name": "BME - Biomedical Engineering"
				},
				{
					"id": "476",
					"short": "BEE",
					"name": "BEE - Urbane Erneuerbare Energietechnologien"
				},
				{
					"id": "254",
					"short": "BEL",
					"name": "BEL - Elektronik"
				},
				{
					"id": "255",
					"short": "BEW",
					"name": "BEW - Elektronik/Wirtschaft"
				},
				{
					"id": "258",
					"short": "BIC",
					"name": "BICSS - Informations- und Kommunikationssysteme"
				},
				{
					"id": "257",
					"short": "BIF",
					"name": "BIF - Informatik/Computer Science"
				},
				{
					"id": "335",
					"short": "BIW",
					"name": "BIWI - Internationales Wirtschaftsingenieurwesen"
				},
				{
					"id": "330",
					"short": "BMR",
					"name": "BMR - Mechatronik/Robotik"
				},
				{
					"id": "327",
					"short": "BST",
					"name": "BSET - Sports Equipment Technology / Sportgerätetechnik"
				},
				{
					"id": "333",
					"short": "BVU",
					"name": "BVU - Verkehr und Umwelt"
				},
				{
					"id": "256",
					"short": "BWI",
					"name": "BWI - Wirtschaftsinformatik"
				},
				{
					"id": "999",
					"short": "DBT",
					"name": "bTec - bTec (Dipl.)"
				},
				{
					"id": "11",
					"short": "DEL",
					"name": "EL - Elektronik"
				},
				{
					"id": "91",
					"short": "DEW",
					"name": "EW - Elektronik/Wirtschaft"
				},
				{
					"id": "145",
					"short": "DIA",
					"name": "ICSS - Informations- und Kommunikationssysteme und -Dienste"
				},
				{
					"id": "94",
					"short": "DID",
					"name": "EID - Elektronische Informationsdienste"
				},
				{
					"id": "308",
					"short": "DIW",
					"name": "DIWI - Internationales Wirtschaftsingenieurwesen"
				},
				{
					"id": "204",
					"short": "DMR",
					"name": "MR - Mechatronik/Robotik"
				},
				{
					"id": "92",
					"short": "DPW",
					"name": "DPW - Produkttechnologie/Wirtschaft"
				},
				{
					"id": "222",
					"short": "DVT",
					"name": "VT - Verkehrstechnologien/Transportsteuerungssysteme"
				},
				{
					"id": "10002",
					"short": "EAK",
					"name": "AK - Aufbaukurse"
				},
				{
					"id": "9005",
					"short": "EAS",
					"name": "EAS - Außerordentliche Studierende"
				},
				{
					"id": "10006",
					"short": "ECI",
					"name": "CI - Campus International"
				},
				{
					"id": "0",
					"short": "ETW",
					"name": "TW - FH Technikum Wien"
				},
				{
					"id": "10005",
					"short": "EWU",
					"name": "WU - Warm-up-Kurse"
				},
				{
					"id": "10004",
					"short": "HSA",
					"name": "HSA - Hertha Firnberg Schulen für Wirtschaft und Tourismus"
				},
				{
					"id": "10003",
					"short": "LCA",
					"name": "CISCO-AC - Cisco Academy"
				},
				{
					"id": "10001",
					"short": "LTC",
					"name": "VOCTECH - VOCTECH"
				},
				{
					"id": "228",
					"short": "MBE",
					"name": "BMES - Biomedical Engineering Sciences"
				},
				{
					"id": "578",
					"short": "MEE",
					"name": "MEE - Erneuerbare Urbane Energiesysteme"
				},
				{
					"id": "297",
					"short": "MES",
					"name": "MES - Embedded Systems"
				},
				{
					"id": "329",
					"short": "MGR",
					"name": "MGRT - Gesundheits- und Rehabilitationstechnik"
				},
				{
					"id": "585",
					"short": "MGS",
					"name": "GES - Game Engineering und Simulation"
				},
				{
					"id": "303",
					"short": "MIC",
					"name": "MIMCS - Informationsmanagement und Computersicherheit"
				},
				{
					"id": "300",
					"short": "MIE",
					"name": "MIE - Industrielle Elektronik"
				},
				{
					"id": "334",
					"short": "MIT",
					"name": "MITS - Intelligent Transport Systems"
				},
				{
					"id": "336",
					"short": "MIW",
					"name": "MIWI - Internationales Wirtschaftsingenieurwesen"
				},
				{
					"id": "331",
					"short": "MMR",
					"name": "MMR - Mechatronik/Robotik"
				},
				{
					"id": "299",
					"short": "MSE",
					"name": "MSE - Multimedia und Softwareentwicklung"
				},
				{
					"id": "328",
					"short": "MST",
					"name": "MSET - Sports Equipment Technology / Sp"
				},
				{
					"id": "692",
					"short": "MTE",
					"name": "MTE - Tissue Engineering and Regenerative Medicine"
				},
				{
					"id": "298",
					"short": "MTI",
					"name": "MTKIT - Telekommunikation und Internettechnologien"
				},
				{
					"id": "301",
					"short": "MTM",
					"name": "MTM - Innovations- und Technologiemanagement"
				},
				{
					"id": "332",
					"short": "MUT",
					"name": "MTUM - Technisches Umweltmanagement und Ökotoxikologie"
				},
				{
					"id": "302",
					"short": "MWI",
					"name": "MWI - Wirtschaftsinformatik"
				}
			]', true);
		return $config;

	}
}

