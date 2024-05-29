<?php

/*
 * This file contains the CBS\SmarterU\DataTypes\Timezone.
 *
 * @copyright $year$ Core Business Solutions
 * @license Proprietary
 * @version $version$
 */

declare(strict_types=1);

namespace CBS\SmarterU\DataTypes;

use InvalidArgumentException;

/**
 * A utility class for working with timezone fields in the SmarterU API.
 *
 * Over time we've discovered that the SmarterU API is not particularly
 * consistent with the format in which it expects a timezone value to be sent
 * in, or in the format that it will send in a response. This class provides
 * a few utility methods that help to deal with this.
 */
class Timezone {
    #region Constants

    /**
     * Valid timezones, grabbed from https://support.smarteru.com/docs/time-zones
     * If you have have to refresh the list open your developer console and run
     * this (assuming page structure doesn't change):
     *
     * let output = '[\n';
     * document.querySelectorAll('table tbody tr').forEach((row) => {
     *     const cells = row.querySelectorAll('td p');
     *     output += "    '" + cells[0].innerText + "' => '" + cells[1].innerText + "',\n";
     * });
     * output += "]";
     * console.log(output);
     */
    public const VALID_TIMEZONES_PROVIDED_NAME_TO_DISPLAY_VALUE = [
        'MIT' => '(GMT-11:00) - MIT',
        'Pacific/Apia' => '(GMT-11:00) - Pacific/Apia',
        'Pacific/Midway' => '(GMT-11:00) - Pacific/Midway',
        'Pacific/Niue' => '(GMT-11:00) - Pacific/Niue',
        'Pacific/Pago_Pago' => '(GMT-11:00) - Pacific/Pago_Pago',
        'Pacific/Samoa' => '(GMT-11:00) - Pacific/Samoa',
        'US/Samoa' => '(GMT-11:00) - US/Samoa',
        'America/Adak' => '(GMT-10:00) - America/Adak',
        'America/Atka' => '(GMT-10:00) - America/Atka',
        'HST' => '(GMT-10:00) - HST',
        'Pacific/Fakaofo' => '(GMT-10:00) - Pacific/Fakaofo',
        'Pacific/Honolulu' => '(GMT-10:00) - Pacific/Honolulu',
        'Pacific/Johnston' => '(GMT-10:00) - Pacific/Johnston',
        'Pacific/Rarotonga' => '(GMT-10:00) - Pacific/Rarotonga',
        'Pacific/Tahiti' => '(GMT-10:00) - Pacific/Tahiti',
        'US/Aleutian' => '(GMT-10:00) - US/Aleutian',
        'US/Hawaii' => '(GMT-10:00) - US/Hawaii',
        'Pacific/Marquesas' => '(GMT-9:30) - Pacific/Marquesas',
        'AST' => '(GMT-9:00) - AST',
        'America/Anchorage' => '(GMT-9:00) - America/Anchorage',
        'America/Juneau' => '(GMT-9:00) - America/Juneau',
        'America/Nome' => '(GMT-9:00) - America/Nome',
        'America/Sitka' => '(GMT-9:00) - America/Sitka',
        'America/Yakutat' => '(GMT-9:00) - America/Yakutat',
        'Pacific/Gambier' => '(GMT-9:00) - Pacific/Gambier',
        'US/Alaska' => '(GMT-9:00) - US/Alaska',
        'America/Dawson' => '(GMT-8:00) - America/Dawson',
        'America/Ensenada' => '(GMT-8:00) - America/Ensenada',
        'America/Los_Angeles' => '(GMT-8:00) - America/Los_Angeles',
        'America/Metlakatla' => '(GMT-8:00) - America/Metlakatla',
        'America/Santa_Isabel' => '(GMT-8:00) - America/Santa_Isabel',
        'America/Tijuana' => '(GMT-8:00) - America/Tijuana',
        'America/Vancouver' => '(GMT-8:00) - America/Vancouver',
        'America/Whitehorse' => '(GMT-8:00) - America/Whitehorse',
        'Canada/Pacific' => '(GMT-8:00) - Canada/Pacific',
        'Canada/Yukon' => '(GMT-8:00) - Canada/Yukon',
        'Mexico/BajaNorte' => '(GMT-8:00) - Mexico/BajaNorte',
        'PST' => '(GMT-8:00) - PST',
        'PST8PDT' => '(GMT-8:00) - PST8PDT',
        'Pacific/Pitcairn' => '(GMT-8:00) - Pacific/Pitcairn',
        'US/Pacific' => '(GMT-8:00) - US/Pacific',
        'US/Pacific-New' => '(GMT-8:00) - US/Pacific-New',
        'America/Boise' => '(GMT-7:00) - America/Boise',
        'America/Cambridge_Bay' => '(GMT-7:00) - America/Cambridge_Bay',
        'America/Chihuahua' => '(GMT-7:00) - America/Chihuahua',
        'America/Dawson_Creek' => '(GMT-7:00) - America/Dawson_Creek',
        'America/Denver' => '(GMT-7:00) - America/Denver',
        'America/Edmonton' => '(GMT-7:00) - America/Edmonton',
        'America/Hermosillo' => '(GMT-7:00) - America/Hermosillo',
        'America/Inuvik' => '(GMT-7:00) - America/Inuvik',
        'America/Mazatlan' => '(GMT-7:00) - America/Mazatlan',
        'America/Ojinaga' => '(GMT-7:00) - America/Ojinaga',
        'America/Phoenix' => '(GMT-7:00) - America/Phoenix',
        'America/Shiprock' => '(GMT-7:00) - America/Shiprock',
        'America/Yellowknife' => '(GMT-7:00) - America/Yellowknife',
        'Canada/Mountain' => '(GMT-7:00) - Canada/Mountain',
        'MST' => '(GMT-7:00) - MST',
        'MST7MDT' => '(GMT-7:00) - MST7MDT',
        'Mexico/BajaSur' => '(GMT-7:00) - Mexico/BajaSur',
        'Navajo' => '(GMT-7:00) - Navajo',
        'PNT' => '(GMT-7:00) - PNT',
        'US/Arizona' => '(GMT-7:00) - US/Arizona',
        'US/Mountain' => '(GMT-7:00) - US/Mountain',
        'America/Bahia_Banderas' => '(GMT-6:00) - America/Bahia_Banderas',
        'America/Belize' => '(GMT-6:00) - America/Belize',
        'America/Cancun' => '(GMT-6:00) - America/Cancun',
        'America/Chicago' => '(GMT-6:00) - America/Chicago',
        'America/Costa_Rica' => '(GMT-6:00) - America/Costa_Rica',
        'America/El_Salvador' => '(GMT-6:00) - America/El_Salvador',
        'America/Guatemala' => '(GMT-6:00) - America/Guatemala',
        'America/Indiana/Knox' => '(GMT-6:00) - America/Indiana/Knox',
        'America/Indiana/Tell_City' => '(GMT-6:00) - America/Indiana/Tell_City',
        'America/Knox_IN' => '(GMT-6:00) - America/Knox_IN',
        'America/Managua' => '(GMT-6:00) - America/Managua',
        'America/Matamoros' => '(GMT-6:00) - America/Matamoros',
        'America/Menominee' => '(GMT-6:00) - America/Menominee',
        'America/Merida' => '(GMT-6:00) - America/Merida',
        'America/Mexico_City' => '(GMT-6:00) - America/Mexico_City',
        'America/Monterrey' => '(GMT-6:00) - America/Monterrey',
        'America/North_Dakota/Beulah' => '(GMT-6:00) - America/North_Dakota/Beulah',
        'America/North_Dakota/Center' => '(GMT-6:00) - America/North_Dakota/Center',
        'America/North_Dakota/New_Salem' => '(GMT-6:00) - America/North_Dakota/New_Salem',
        'America/Rainy_River' => '(GMT-6:00) - America/Rainy_River',
        'America/Rankin_Inlet' => '(GMT-6:00) - America/Rankin_Inlet',
        'America/Regina' => '(GMT-6:00) - America/Regina',
        'America/Swift_Current' => '(GMT-6:00) - America/Swift_Current',
        'America/Tegucigalpa' => '(GMT-6:00) - America/Tegucigalpa',
        'America/Winnipeg' => '(GMT-6:00) - America/Winnipeg',
        'CST' => '(GMT-6:00) - CST',
        'CST6CDT' => '(GMT-6:00) - CST6CDT',
        'Canada/Central' => '(GMT-6:00) - Canada/Central',
        'Canada/East-Saskatchewan' => '(GMT-6:00) - Canada/East-Saskatchewan',
        'Chile/EasterIsland' => '(GMT-6:00) - Chile/EasterIsland',
        'Mexico/General' => '(GMT-6:00) - Mexico/General',
        'Pacific/Easter' => '(GMT-6:00) - Pacific/Easter',
        'Pacific/Galapagos' => '(GMT-6:00) - Pacific/Galapagos',
        'US/Central' => '(GMT-6:00) - US/Central',
        'US/Indiana-Starke' => '(GMT-6:00) - US/Indiana-Starke',
        'America/Atikokan' => '(GMT-5:00) - America/Atikokan',
        'America/Bogota' => '(GMT-5:00) - America/Bogota',
        'America/Cayman' => '(GMT-5:00) - America/Cayman',
        'America/Coral_Harbour' => '(GMT-5:00) - America/Coral_Harbour',
        'America/Detroit' => '(GMT-5:00) - America/Detroit',
        'America/Fort_Wayne' => '(GMT-5:00) - America/Fort_Wayne',
        'America/Grand_Turk' => '(GMT-5:00) - America/Grand_Turk',
        'America/Guayaquil' => '(GMT-5:00) - America/Guayaquil',
        'America/Havana' => '(GMT-5:00) - America/Havana',
        'America/Indiana/Indianapolis' => '(GMT-5:00) - America/Indiana/Indianapolis',
        'America/Indiana/Marengo' => '(GMT-5:00) - America/Indiana/Marengo',
        'America/Indiana/Petersburg' => '(GMT-5:00) - Amaerica/Indiana/Petersburg',
        'America/Indiana/Vevay' => '(GMT-5:00) - America/Indiana/Vevay',
        'America/Indiana/Vincennes' => '(GMT-5:00) - America/Indiana/Vincennes',
        'America/Indiana/Winamac' => '(GMT-5:00) - America/Indiana/Winamac',
        'America/Indianapolis' => '(GMT-5:00) - America/Indianapolis',
        'America/Iqaluit' => '(GMT-5:00) - America/Iqaluit',
        'America/Jamaica' => '(GMT-5:00) - America/Jamaica',
        'America/Kentucky/Louisville' => '(GMT-5:00) - America/Kentucky/Louisville',
        'America/Kentucky/Monticello' => '(GMT-5:00) - America/Kentucky/Monticello',
        'America/Lima' => '(GMT-5:00) - America/Lima',
        'America/Louisville' => '(GMT-5:00) - America/Louisville',
        'America/Montreal' => '(GMT-5:00) - America/Montreal',
        'America/Nassau' => '(GMT-5:00) - America/Nassau',
        'America/New_York' => '(GMT-5:00) - America/New_York',
        'America/Nipigon' => '(GMT-5:00) - America/Nipigon',
        'America/Panama' => '(GMT-5:00) - America/Panama',
        'America/Pangnirtung' => '(GMT-5:00) - America/Pangnirtung',
        'America/Port-au-Prince' => '(GMT-5:00) - America/Port-au-Prince',
        'America/Resolute' => '(GMT-5:00) - America/Resolute',
        'America/Thunder_Bay' => '(GMT-5:00) - America/Thunder_Bay',
        'America/Toronto' => '(GMT-5:00) - America/Toronto',
        'Canada/Eastern' => '(GMT-5:00) - Canada/Eastern',
        'EST' => '(GMT-5:00) - EST',
        'EST5EDT' => '(GMT-5:00) - EST5EDT',
        'IET' => '(GMT-5:00) - IET',
        'Jamaica' => '(GMT-5:00) - Jamaica',
        'US/East-Indiana' => '(GMT-5:00) - US/East-Indiana',
        'US/Eastern' => '(GMT-5:00) - US/Eastern',
        'US/Michigan' => '(GMT-5:00) - US/Michigan',
        'America/Caracas' => '(GMT-4:30) - America/Caracas',
        'America/Anguilla' => '(GMT-4:00) - America/Anguilla',
        'America/Antigua' => '(GMT-4:00) - America/Antigua',
        'America/Argentina/San_Luis' => '(GMT-4:00) - America/Argentina/San_Luis',
        'America/Aruba' => '(GMT-4:00) - America/Aruba',
        'America/Asuncion' => '(GMT-4:00) - America/Asuncion',
        'America/Barbados' => '(GMT-4:00) - America/Barbados',
        'America/Blank-Sablon' => '(GMT-4:00) - America/Blanc-Sablon',
        'America/Boa_Vista' => '(GMT-4:00) - America/Boa_Vista',
        'America/Campo_Grande' => '(GMT-4:00) - America/Campo_Grande',
        'America/Cuiaba' => '(GMT-4:00) - America/Cuiaba',
        'America/Curacao' => '(GMT-4:00) - America/Curacao',
        'America/Dominica' => '(GMT-4:00) - America/Dominica',
        'America/Eirunepe' => '(GMT-4:00) - America/Eirunepe',
        'America/Glace_Bay' => '(GMT-4:00) - America/Glace_Bay',
        'America/Goose_Bay' => '(GMT-4:00) - America/Goose_Bay',
        'America/Grenada' => '(GMT-4:00) - America/Grenada',
        'America/Guadeloupe' => '(GMT-4:00) - America/Guadeloupe',
        'America/Guyana' => '(GMT-4:00) - America/Guyana',
        'America/Halifax' => '(GMT-4:00) - America/Halifax',
        'America/La_Paz' => '(GMT-4:00) - America/La_Paz',
        'America/Manaus' => '(GMT-4:00) - America/Manaus',
        'America/Marigot' => '(GMT-4:00) - America/Marigot',
        'America/Martinique' => '(GMT-4:00) - America/Martinique',
        'America/Moncton' => '(GMT-4:00) - America/Moncton',
        'America/Montserrat' => '(GMT-4:00) - America/Montserrat',
        'America/Port_of_Spain' => '(GMT-4:00) - America/Port_of_Spain',
        'America/Porto_Acre' => '(GMT-4:00) - America/Porto_Acre',
        'America/Porto_Velho' => '(GMT-4:00) - America/Porto_Velho',
        'America/Puerto_Rico' => '(GMT-4:00) - America/Puerto_Rico',
        'America/Rio_Branco' => '(GMT-4:00) - America/Rio_Branco',
        'America/Santiago' => '(GMT-4:00) - America/Santiago',
        'America/Santo_Domingo' => '(GMT-4:00) - America/Santo_Domingo',
        'America/St_Barthelemy' => '(GMT-4:00) - America/St_Barthelemy',
        'America/St_Kitts' => '(GMT-4:00) - America/St_Kitts',
        'America/St_Lucia' => '(GMT-4:00) - America/St_Lucia',
        'America/St_Thomas' => '(GMT-4:00) - America/St_Thomas',
        'America/St_Vincent' => '(GMT-4:00) - America/St_Vincent',
        'America/Thule' => '(GMT-4:00) - America/Thule',
        'America/Tortola' => '(GMT-4:00) - America/Tortola',
        'America/Virgin' => '(GMT-4:00) - America/Virgin',
        'Antartica/Palmer' => '(GMT-4:00) - Antartica/Palmer',
        'Atlantic/Bermuda' => '(GMT-4:00) - Atlantic/Bermuda',
        'Atlantic/Stanley' => '(GMT-4:00) - Atlantic/Stanley',
        'Brazil/Acre' => '(GMT-4:00) - Brazil/Acre',
        'Brazil/West' => '(GMT-4:00) - Brazil/West',
        'Canada/Atlantic' => '(GMT-4:00) - Canada/Atlantic',
        'Chile/Continental' => '(GMT-4:00) - Chile/Continental',
        'PRT' => '(GMT-4:00) - PRT',
        'America/St_Johns' => '(GMT-3:30) - America/St_Johns',
        'CNT' => '(GMT-3:30) - CNT',
        'Canada/Newfoundland' => '(GMT-3:30) - Canada/Newfoundland',
        'AGT' => '(GMT-3:00) - AGT',
        'America/Araguaina' => '(GMT-3:00) - America/Araguaina',
        'America/Argentina/Buenos_Aires' => '(GMT-3:00) - America/Argentina/Buenos_Aires',
        'America/Argentina/Catamarca' => '(GMT-3:00) - America/Argentina/Catamarca',
        'America/Argentina/ComodRivadavia' => '(GMT-3:00) - America/Argentina/ComodRivadavia',
        'America/Argentina/Cordoba' => '(GMT-3:00) - America/Argentina/Cordoba',
        'America/Argentina/Jujuy' => '(GMT-3:00) - America/Argentina/Jujuy',
        'America/Argentina/La_Rioja' => '(GMT-3:00) - America/Argentina/La_Rioja',
        'America/Argentina/Mendoza' => '(GMT-3:00) - America/Argentina/Mendoza',
        'America/Argentina/Rio_Gallegos' => '(GMT-3:00) - America/Argentina/Rio_Gallegos',
        'America/Argentina/Salta' => '(GMT-3:00) - America/Argentina/Salta',
        'America/Argentina/San_Juan' => '(GMT-3:00) - America/Argentina/San_Juan',
        'America/Argentina/Tucuman' => '(GMT-3:00) - America/Argentina/Tucuman',
        'America/Argentina/Ushuaia' => '(GMT-3:00) - America/Argentina/Ushuaia',
        'America/Bahia' => '(GMT-3:00) - America/Bahia',
        'America/Belem' => '(GMT-3:00) - America/Belem',
        'America/Buenos_Aires' => '(GMT-3:00) - America/Buenos_Aires',
        'America/Catamarca' => '(GMT-3:00) - America/Catamarca',
        'America/Cayenne' => '(GMT-3:00) - America/Cayenne',
        'America/Cordoba' => '(GMT-3:00) - America/Cordoba',
        'America/Fortaleza' => '(GMT-3:00) - America/Fortaleza',
        'America/Godthab' => '(GMT-3:00) - America/Godthab',
        'America/Jujuy' => '(GMT-3:00) - America/Jujuy',
        'America/Maceio' => '(GMT-3:00) - America/Maceio',
        'America/Mendoza' => '(GMT-3:00) - America/Mendoza',
        'America/Miquelon' => '(GMT-3:00) - America/Miquelon',
        'America/Montevideo' => '(GMT-3:00) - America/Montevideo',
        'America/Paramaribo' => '(GMT-3:00) - America/Paramaribo',
        'America/Recife' => '(GMT-3:00) - America/Recife',
        'America/Rosario' => '(GMT-3:00) - America/Rosario',
        'America/Santarem' => '(GMT-3:00) - America/Santarem',
        'America/Sao_Paulo' => '(GMT-3:00) - America/Sao_Paulo',
        'Antartica/Rothera' => '(GMT-3:00) - Antartica/Rothera',
        'BET' => '(GMT-3:00) - BET',
        'Brazil/East' => '(GMT-3:00) - Brazil/East',
        'America/Noronha' => '(GMT-2:00) - America/Noronha',
        'Atlantic/South_Georgia' => '(GMT-2:00) - Atlantic/South_Georgia',
        'Brazil/DeNoronha' => '(GMT-2:00) - Brazil/DeNoronha',
        'America/Scoresbysund' => '(GMT-1:00) - America/Scoresbysund',
        'Atlantic/Azores' => '(GMT-1:00) - Atlantic/Azores',
        'Atlantic/Cape_Verde' => '(GMT-1:00) - Atlantic/Cape_Verde',
        'Africa/Abidjan' => '(GMT+0:00) - Africa/Abidjan',
        'Africa/Accra' => '(GMT+0:00) - Africa/Accra',
        'Africa/Bamako' => '(GMT+0:00) - Africa/Bamako',
        'Africa/Banjul' => '(GMT+0:00) - Africa/Banjul',
        'Africa/Bissau' => '(GMT+0:00) - Africa/Bissau',
        'Africa/Casablanca' => '(GMT+0:00) - Africa/Casablanca',
        'Africa/Conakry' => '(GMT+0:00) - Africa/Conakry',
        'Africa/Dakar' => '(GMT+0:00) - Africa/Dakar',
        'Africa/El_Aaiun' => '(GMT+0:00) - Africa/El_Aaiun',
        'Africa/Freetown' => '(GMT+0:00) - Africa/Freetown',
        'Africa/Lome' => '(GMT+0:00) - Africa/Lome',
        'Africa/Monrovia' => '(GMT+0:00) - Africa/Monrovia',
        'Africa/Nouakchott' => '(GMT+0:00) - Africa/Nouakchott',
        'Africa/Ouagadougou' => '(GMT+0:00) - Africa/Ouagadougou',
        'Africa/Sao_Tome' => '(GMT+0:00) - Africa/Sao_Tome',
        'Africa/Timbuktu' => '(GMT+0:00) - Africa/Timbuktu',
        'America/Danmarkshavn' => '(GMT+0:00) - America/Danmarkshavn',
        'Atlantic/Canary' => '(GMT+0:00) - Atlantic/Canary',
        'Atlantic/Faeroe' => '(GMT+0:00) - Atlantic/Faeroe',
        'Atlantic/Faroe' => '(GMT+0:00) - Atlantic/Faroe',
        'Atlantic/Madeira' => '(GMT+0:00) - Atlantic/Madeira',
        'Atlantic/Reykjavik' => '(GMT+0:00) - Atlantic/Reykjavik',
        'Atlantic/St_Helena' => '(GMT+0:00) - Atlantic/St_Helena',
        'Eire' => '(GMT+0:00) - Eire',
        'Europe/Belfast' => '(GMT+0:00) - Europe/Belfast',
        'Europe/Dublin' => '(GMT+0:00) - Europe/Dublin',
        'Europe/Guernsey' => '(GMT+0:00) - Europe/Guernsey',
        'Europe/Isle_of_Man' => '(GMT+0:00) - Europe/Isle_of_Man',
        'Europe/Jersey' => '(GMT+0:00) - Europe/Jersey',
        'Europe/Lisbon' => '(GMT+0:00) - Europe/Lisbon',
        'Europe/London' => '(GMT+0:00) - Europe/London',
        'GB' => '(GMT+0:00) - GB',
        'GB-Eire' => '(GMT+0:00) - GB-Eire',
        'GMT' => '(GMT+0:00) - GMT',
        'GMT0' => '(GMT+0:00) - GMT0',
        'Greenwich' => '(GMT+0:00) - Greenwich',
        'Iceland' => '(GMT+0:00) - Iceland',
        'Portugal' => '(GMT+0:00) - Portugal',
        'UCT' => '(GMT+0:00) - UCT',
        'UTC' => '(GMT+0:00) - UTC',
        'Universal' => '(GMT+0:00) - Universal',
        'WET' => '(GMT+0:00) - WET',
        'Zulu' => '(GMT+0:00) - Zulu',
        'Africa/Algiers' => '(GMT+1:00) - Africa/Algiers',
        'Africa/Bangui' => '(GMT+1:00) - Africa/Bangui',
        'Africa/Brazzaville' => '(GMT+1:00) - Africa/Brazzaville',
        'Africa/Ceuta' => '(GMT+1:00) - Africa/Ceuta',
        'Africa/Douala' => '(GMT+1:00) - Africa/Douala',
        'Africa/Kinshasa' => '(GMT+1:00) - Africa/Kinshasa',
        'Africa/Lagos' => '(GMT+1:00) - Africa/Lagos',
        'Africa/Libreville' => '(GMT+1:00) - Africa/Libreville',
        'Africa/Luanda' => '(GMT+1:00) - Africa/Luanda',
        'Africa/Malabo' => '(GMT+1:00) - Africa/Malabo',
        'Africa/Ndjamena' => '(GMT+1:00) - Africa/Ndjamena',
        'Africa/Niamey' => '(GMT+1:00) - Africa/Niamey',
        'Africa/Porto-Novo' => '(GMT+1:00) - Africa/Porto-Novo',
        'Africa/Tunis' => '(GMT+1:00) - Africa/Tunis',
        'Africa/Windhoek' => '(GMT+1:00) - Africa/Windhoek',
        'Arctic/Longyearbyen' => '(GMT+1:00) - Arctic/Longyearbyen',
        'Atlantic/Jan_Mayen' => '(GMT+1:00) - Atlantic/Jan_Mayen',
        'CET' => '(GMT+1:00) - CET',
        'ECT' => '(GMT+1:00) - ECT',
        'Europe/Amsterdam' => '(GMT+1:00) - Europe/Amsterdam',
        'Europe/Andorra' => '(GMT+1:00) - Europe/Andorra',
        'Europe/Belgrade' => '(GMT+1:00) - Europe/Belgrade',
        'Europe/Berlin' => '(GMT+1:00) - Europe/Berlin',
        'Europe/Bratislava' => '(GMT+1:00) - Europe/Bratislava',
        'Europe/Brussels' => '(GMT+1:00) - Europe/Brussels',
        'Europe/Budapest' => '(GMT+1:00) - Europe/Budapest',
        'Europe/Copenhagen' => '(GMT+1:00) - Europe/Copenhagen',
        'Europe/Gibraltar' => '(GMT+1:00) - Europe/Gibraltar',
        'Europe/Ljubljana' => '(GMT+1:00) - Europe/Ljubljana',
        'Europe/Luxembourg' => '(GMT+1:00) - Europe/Luxembourg',
        'Europe/Madrid' => '(GMT+1:00) - Europe/Madrid',
        'Europe/Malta' => '(GMT+1:00) - Europe/Malta',
        'Europe/Monaco' => '(GMT+1:00) - Europe/Monaco',
        'Europe/Oslo' => '(GMT+1:00) - Europe/Oslo',
        'Europe/Paris' => '(GMT+1:00) - Europe/Paris',
        'Europe/Podgorica' => '(GMT+1:00) - Europe/Podgorica',
        'Europe/Prague' => '(GMT+1:00) - Europe/Prague',
        'Europe/Rome' => '(GMT+1:00) - Europe/Rome',
        'Europe/San_Marino' => '(GMT+1:00) - Europe/San_Marino',
        'Europe/Sarajevo' => '(GMT+1:00) - Europe/Sarajevo',
        'Europe/Skopje' => '(GMT+1:00) - Europe/Skopje',
        'Europe/Stolkholm' => '(GMT+1:00) - Europe/Stolkholm',
        'Europe/Tirane' => '(GMT+1:00) - Europe/Tirane',
        'Europe/Vaduz' => '(GMT+1:00) - Europe/Vaduz',
        'Europe/Vatican' => '(GMT+1:00) - Europe/Vatican',
        'Europe/Vienna' => '(GMT+1:00) - Europe/Vienna',
        'Europe/Warsaw' => '(GMT+1:00) - Europe/Warsaw',
        'Europe/Zagreb' => '(GMT+1:00) - Europe/Zagreb',
        'Europe/Zurick' => '(GMT+1:00) - Europe/Zurkch',
        'MET' => '(GMT+1:00) - MET',
        'Poland' => '(GMT+1:00) - Poland',
        'ART' => '(GMT+2:00) - ART',
        'Africa/Blantyre' => '(GMT+2:00) - Africa/Blantyre',
        'Africa/Bujumbura' => '(GMT+2:00) - Africa/Bujumbura',
        'Africa/Cairo' => '(GMT+2:00) - Africa/Cairo',
        'Africa/Gaborone' => '(GMT+2:00) - Africa/Gaborone',
        'Africa/Harare' => '(GMT+2:00) - Africa/Harare',
        'Africa/Johannesburg' => '(GMT+2:00) - Africa/Johannesburg',
        'Africa/Kigali' => '(GMT+2:00) - Africa/Kigali',
        'Africa/Lubumbashi' => '(GMT+2:00) - Africa/Lubumbashi',
        'Africa/Lusaka' => '(GMT+2:00) - Africa/Lusaka',
        'Africa/Maputo' => '(GMT+2:00) - Africa/Maputo',
        'Africa/Maseru' => '(GMT+2:00) - Africa/Maseru',
        'Africa/Mbabane' => '(GMT+2:00) - Africa/Mbabane',
        'Africa/Tripoli' => '(GMT+2:00) - Africa/Tripoli',
        'Asia/Amman' => '(GMT+2:00) - Asia/Amman',
        'Asia/Beirut' => '(GMT+2:00) - Asia/Beirut',
        'Asia/Damascus' => '(GMT+2:00) - Asia/Damascus',
        'Asia/Gaza' => '(GMT+2:00) - Asia/Gaza',
        'Asia/Istanbul' => '(GMT+2:00) - Asia/Istanbul',
        'Asia/Jerusalem' => '(GMT+2:00) - Asia/Jerusalem',
        'Asia/Nicosia' => '(GMT+2:00) - Asia/Nicosia',
        'Asia/Tel_Aviv' => '(GMT+2:00) - Asia/Tel_Aviv',
        'CAT' => '(GMT+2:00) - CAT',
        'EET' => '(GMT+2:00) - EET',
        'Egypt' => '(GMT+2:00) - Egypt',
        'Europe/Athens' => '(GMT+2:00) - Europe/Athens',
        'Europe/Bucharest' => '(GMT+2:00) - Europe/Bucharest',
        'Europe/Chisinau' => '(GMT+2:00) - Europe/Chisinau',
        'Europe/Helsinki' => '(GMT+2:00) - Europe/Helsinki',
        'Europe/Istanbul' => '(GMT+2:00) - Europe/Istanbul',
        'Europe/Kaliningrad' => '(GMT+2:00) - Europe/Kaliningrad',
        'Europe/Kiev' => '(GMT+2:00) - Europe/Kiev',
        'Europe/Mariehamn' => '(GMT+2:00) - Europe/Mariehamn',
        'Europe/Minsk' => '(GMT+2:00) - Europe/Minsk',
        'Europe/Nicosia' => '(GMT+2:00) - Europe/Nicosia',
        'Europe/Riga' => '(GMT+2:00) - Europe/Riga',
        'Europe/Simferopol' => '(GMT+2:00) - Europe/Simferopol',
        'Europe/Sofia' => '(GMT+2:00) - Europe/Sofia',
        'Europe/Tallinn' => '(GMT+2:00) - Europe/Tallinn',
        'Europe/Tiraspol' => '(GMT+2:00) - Europe/Tiraspol',
        'Europe/Uzhgorod' => '(GMT+2:00) - Europe/Uzhgorod',
        'Europe/Vilnius' => '(GMT+2:00) - Europe/Vilnius',
        'Europe/Zaporozhye' => '(GMT+2:00) - Europe/Zaporozhye',
        'Israel' => '(GMT+2:00) - Israel',
        'Libya' => '(GMT+2:00) - Libya',
        'Turkey' => '(GMT+2:00) - Turkey',
        'Africa/Addis_Ababa' => '(GMT+3:00) - Africa/Addis_Ababa',
        'Africa/Asmara' => '(GMT+3:00) - Africa/Asmara',
        'Africa/Asmera' => '(GMT+3:00) - Africa/Asmera',
        'Africa/Dar_es_Salaam' => '(GMT+3:00) - Africa/Dar_es_Salaam',
        'Africa/Djibouti' => '(GMT+3:00) - Africa/Djibouti',
        'Africa/Kampala' => '(GMT+3:00) - Africa/Kampala',
        'Africa/Khartoum' => '(GMT+3:00) - Africa/Khartoum',
        'Africa/Mogadishu' => '(GMT+3:00) - Africa/Mogadishu',
        'Africa/Nairobi' => '(GMT+3:00) - Africa/Nairobi',
        'Antarctica/Syowa' => '(GMT+3:00) - Antarctica/Syowa',
        'Asia/Aden' => '(GMT+3:00) - Asia/Aden',
        'Asia/Baghad' => '(GMT+3:00) - Asia/Baghdad',
        'Asia/Bahrain' => '(GMT+3:00) - Asia/Bahrain',
        'Asia/Kuwait' => '(GMT+3:00) - Asia/Kuwait',
        'Asia/Qatar' => '(GMT+3:00) - Asia/Qatar',
        'Asia/Riyadh' => '(GMT+3:00) - Asia/Riyadh',
        'EAT' => '(GMT+3:00) - EAT',
        'Europe/Moscow' => '(GMT+3:00) - Europe/Moscow',
        'Europe/Samara' => '(GMT+3:00) - Europe/Samara',
        'Europe/Volgograd' => '(GMT+3:00) - Europe/Volgograd',
        'Indian/Antananarivo' => '(GMT+3:00) - Indian/Antananarivo',
        'Indian/Comoro' => '(GMT+3:00) - Indian/Comoro',
        'Indian/Mayotte' => '(GMT+3:00) - Indian/Mayotte',
        'W-SU' => '(GMT+3:00) - W-SU',
        'Asia/Riyadh87' => '(GMT+3:07) - Asia/Riyadha87',
        'Asia/Riyadh88' => '(GMT+3:07) - Asia/Riyadh88',
        'Asia/Riyadh89' => '(GMT+3:07) - Asia/Riyadh89',
        'Mideast/Riyadh87' => '(GMT+3:07) - Mideast/Riyadh87',
        'Mideast/Riyadh88' => '(GMT+3:07) - Mideast/Riyadh88',
        'Mideast/Riyadh89' => '(GMT+3:07) - Mideast/Riyadh89',
        'Asia/Tehran' => '(GMT+3:30) - Asia/Tehran',
        'Iran' => '(GMT+3:30) - Iran',
        'Asia/Baku' => '(GMT+4:00) - Asia/Baku',
        'Asia/Dubai' => '(GMT+4:00) - Asia/Dubai',
        'Asia/Muscat' => '(GMT+4:00) - Asia/Muscat',
        'Asia/Tbilisi' => '(GMT+4:00) - Asia/Tbilisi',
        'Asia/Yerevan' => '(GMT+4:00) - Asia/Yerevan',
        'Indian/Mahe' => '(GMT+4:00) - Indian/Mahe',
        'Indian/Mauritius' => '(GMT+4:00) - Indian/Mauritius',
        'Indian/Reunion' => '(GMT+4:00) - Indian/Reunion',
        'NET' => '(GMT+4:00) - NET',
        'Asia/Kabul' => '(GMT+4:30) - Asia/Kabul',
        'Antarctica/Mawson' => '(GMT+5:00) - Antarctica/Mawson',
        'Asia/Aqtau' => '(GMT+5:00) - Asia/Aqtau',
        'Asia/Aqtobe' => '(GMT+5:00) - Asia/Aqtobe',
        'Asia/Ashgabat' => '(GMT+5:00) - Asia/Ashgabat',
        'Asia/Dushanbe' => '(GMT+5:00) - Asia/Dushanbe',
        'Asia/Karachi' => '(GMT+5:00) - Asia/Karachi',
        'Asia/Oral' => '(GMT+5:00) - Asia/Oral',
        'Asia/Samarkand' => '(GMT+5:00) - Asia/Samarkand',
        'Asia/Tashkent' => '(GMT+5:00) - Asia/Tashkent',
        'Asia/Yekaterinburg' => '(GMT+5:00) - Asia/Yekaterinburg',
        'Indian/Kerguelen' => '(GMT+5:00) - Indian/Kerguelen',
        'Indian/Maldives' => '(GMT+5:00) - Indian/Maldives',
        'PLT' => '(GMT+5:00) - PLT',
        'Asia/Calcutta' => '(GMT+5:30) - Asia/Calcutta',
        'Asia/Colombo' => '(GMT+5:30) - Asia/Colombo',
        'Asia/Kolkata' => '(GMT+5:30) - Asia/Kolkata',
        'IST' => '(GMT+5:30) - IST',
        'Asia/Kathmandu' => '(GMT+5:45) - Asia/Kathmandu',
        'Asia/Katmandu' => '(GMT+5:45) - Asia/Katmandu',
        'Antarctica/Vostok' => '(GMT+6:00) - Antarctica/Vostok',
        'Asia/Almaty' => '(GMT+6:00) - Asia/Almaty',
        'Asia/Bishkek' => '(GMT+6:00) - Asia/Bishkek',
        'Asia/Dacca' => '(GMT+6:00) - Asia/Dacca',
        'Asia/Dhaka' => '(GMT+6:00) - Asia/Dhaka',
        'Asia/Novokuznetsk' => '(GMT+6:00) - Asia/Novokuznetsk',
        'Asia/Novosibirsk' => '(GMT+6:00) - Asia/Novosibirsk',
        'Asia/Omsk' => '(GMT+6:00) - Asia/Omsk',
        'Asia/Qyzylorda' => '(GMT+6:00) - Asia/Qyzylorda',
        'Asia/Thimbu' => '(GMT+6:00) - Asia/Thimbu',
        'Asia/Thimphu' => '(GMT+6:00) - Asia/Thimphu',
        'BST' => '(GMT+6:00) - BST',
        'Indian/Chagos' => '(GMT+6:00) - Indian/Chagos',
        'Asia/Rangoon' => '(GMT+6:30) - Asia/Rangoon',
        'Indian/Cocos' => '(GMT+6:30) - Indian/Cocos',
        'Antarctica/Davis' => '(GMT+7:00) - Antarctica/Davis',
        'Asia/Bangkok' => '(GMT+7:00) - Asia/Bangkok',
        'Asia/Ho_Chi_Minh' => '(GMT+7:00) - Asia/Ho_Chi_Minh',
        'Asia/Hovd' => '(GMT+7:00) - Asia/Hovd',
        'Asia/Jakarta' => '(GMT+7:00) - Asia/Jakarta',
        'Asia/Krasnoyarsk' => '(GMT+7:00) - Asia/Krasnoyarsk',
        'Asia/Phnom_Penh' => '(GMT+7:00) - Asia_Phnom_Penh',
        'Asia/Pontianak' => '(GMT+7:00) - Asia/Pontianak',
        'Asia/Saigon' => '(GMT+7:00) - Asia/Saigon',
        'Asia/Vientiane' => '(GMT+7:00) - Asia/Vientiane',
        'VST' => '(GMT+7:00) - VST',
        'Antarctica/Casey' => '(GMT+8:00) - Antarctica/Casey',
        'Asia/Brunei' => '(GMT+8:00) - Asia/Brunei',
        'Asia/Choibalsan' => '(GMT+8:00) - Asia/Choibalsan',
        'Asia/Chongqing' => '(GMT+8:00) - Asia/Chongqing',
        'Asia/Chungking' => '(GMT+8:00) - Chungking',
        'Asia/Harbin' => '(GMT+8:00) - Asia/Harban',
        'Asia/Hong_Kong' => '(GMT+8:00) - Asia/Hong_Kong',
        'Asia/Irkutsk' => '(GMT+8:00) - Asia/Irkutsk',
        'Asia/Kashgar' => '(GMT+8:00) - Asia/Kashgar',
        'Asia/Kuala_Lumpur' => '(GMT+8:00) - Asia/Kuala_Lumpur',
        'Asia/Kuching' => '(GMT+8:00) - Asia/Kuching',
        'Asia/Macao' => '(GMT+8:00) - Asia/Macao',
        'Asia/Macau' => '(GMT+8:00) - Asia/Macau',
        'Asia/Makassar' => '(GMT+8:00) - Asia/Makassar',
        'Asia/Manila' => '(GMT+8:00) - Asia/Manila',
        'Asia/Shanghai' => '(GMT+8:00) - Asia/Shanghai',
        'Asia/Singapore' => '(GMT+8:00) - Asia/Singapore',
        'Asia/Taipei' => '(GMT+8:00) - Asia/Taipei',
        'Asia/Ujung_Pandang' => '(GMT+8:00) - Asia/Ujung_Pandang',
        'Asia/Ulaanbaatar' => '(GMT+8:00) - Asia/Ulaanbaatar',
        'Asia/Ulan_Bator' => '(GMT+8:00) - Asia/Ulan_Bator',
        'Asia/Urumqi' => '(GMT+8:00) - Asia/Urumqi',
        'Australia/Perth' => '(GMT+8:00) - Australia/Perth',
        'Australia/West' => '(GMT+8:00) - Australia/West',
        'CTT' => '(GMT+8:00) - CTT',
        'Hongkong' => '(GMT+8:00) - Hongkong',
        'PRC' => '(GMT+8:00) - PRC',
        'Singapore' => '(GMT+8:00) - Singapore',
        'Australia/Eucla' => '(GMT+8:45) - Australia/Eucla',
        'Asia/Dili' => '(GMT+9:00) - Asia/Dili',
        'Asia/Jayapura' => '(GMT+9:00) - Asia/Jayapura',
        'Asia/Pyongyang' => '(GMT+9:00) - Asia/Pyongyang',
        'Asia/Seoul' => '(GMT+9:00) - Asia/Seoul',
        'Asia/Tokyo' => '(GMT+9:00) - Asia/Tokyo',
        'Asia/Yakutsk' => '(GMT+9:00) - Asia/Yakutsk',
        'JST' => '(GMT+9:00) - JST',
        'Japan' => '(GMT+9:00) - Japan',
        'Pacific/Palau' => '(GMT+9:00) - Pacific/Palau',
        'ROK' => '(GMT+9:00) - ROK',
        'ACT' => '(GMT+9:30) - ACT',
        'Australia/Adelaide' => '(GMT+9:30) - Australia/Adelaide',
        'Australia/Broken_Hill' => '(GMT+9:30) - Australia/Broken_Hill',
        'Australia/Darwin' => '(GMT+9:30) - Australia/Darwin',
        'Australia/North' => '(GMT+9:30) - Australia/North',
        'Australia/South' => '(GMT+9:30) - Australia/South',
        'Australia/Yancowinna' => '(GMT+9:30) - Australia/Yancowinna',
        'AET' => '(GMT+10:00) - AET',
        'Antarctica/DumontDUrville' => '(GMT+10:00) - Antarctica/DumontDUrville',
        'Asia/Sakhalin' => '(GMT+10:00) - Asia/Sakhalin',
        'Asia/Vladivostok' => '(GMT+10:00) - Asia/Vladivostok',
        'Australia/ACT' => '(GMT+10:00) - Australia/ACT',
        'Australia/Brisbane' => '(GMT+10:00) - Australia/Brisbane',
        'Australia/Canberra' => '(GMT+10:00) - Australia/Canberra',
        'Australia/Currie' => '(GMT+10:00) - Australia/Currie',
        'Australia/Hobart' => '(GMT+10:00) - Australia/Hobart',
        'Australia/Lindeman' => '(GMT+10:00) - Australia/Lindeman',
        'Australia/Melbourne' => '(GMT+10:00) - Australia/Melbourne',
        'Australia/NSW' => '(GMT+10:00) - Australia/NSW',
        'Australia/Queensland' => '(GMT+10:00) - Australia/Queensland',
        'Australia/Sydney' => '(GMT+10:00) - Australia/Sydney',
        'Australia/Tasmania' => '(GMT+10:00) - Australia/Tasmania',
        'Australia/Victoria' => '(GMT+10:00) - Australia/Victoria',
        'Pacific/Chuuk' => '(GMT+10:00) - Pacific/Chuuk',
        'Pacific/Guam' => '(GMT+10:00) - Pacific/Guam',
        'Pacific/Port_Moresby' => '(GMT+10:00) - Pacific/Port_Moresby',
        'Pacific/Saipan' => '(GMT+10:00) - Pacific/Saipan',
        'Pacific/Truk' => '(GMT+10:00) - Pacific/Truk',
        'Pacific/Yap' => '(GMT+10:00) - Pacific/Yap',
        'Australia/LHI' => '(GMT+10:30) - Australia/LHI',
        'Australia/Lord_Howe' => '(GMT+10:30) - Australia/Lord_Howe',
        'Antarctica/Macquarie' => '(GMT+11:00) - Antarctica/Macquarie',
        'Asia/Anadyr' => '(GMT+11:00) - Asia/Anadyr',
        'Asia/Kamchatka' => '(GMT+11:00) - Asia/Kamchatka',
        'Asia/Magadan' => '(GMT+11:00) - Asia/Magadan',
        'Pacific/Efate' => '(GMT+11:00) - Pacific/Efate',
        'Pacific/Guadalcanal' => '(GMT+11:00) - Pacific/Guadalcanal',
        'Pacific/Kosrae' => '(GMT+11:00) - Pacific/Kosrae',
        'Pacific/Noumea' => '(GMT+11:00) - Pacific/Noumea',
        'Pacific/Pohnpei' => '(GMT+11:00) - Pacific/Pohnpei',
        'Pacific/Ponape' => '(GMT+11:00) - Pacific/Ponape',
        'SST' => '(GMT+11:00) - SST',
        'Pacific/Norfolk' => '(GMT+11:30) - Pacific/Norfolk',
        'Antarctica/McMurdo' => '(GMT+12:00) - Antarctica/McMurdo',
        'Antarctica/South_Pole' => '(GMT+12:00) - Antarctica/South_Pole',
        'Kwajalein' => '(GMT+12:00) - Kwajalein',
        'NST' => '(GMT+12:00) - NST',
        'NZ' => '(GMT+12:00) - NZ',
        'Pacific/Auckland' => '(GMT+12:00) - Pacific/Auckland',
        'Pacific/Fiji' => '(GMT+12:00) - Pacific/Fiji',
        'Pacific/Funafuti' => '(GMT+12:00) - Pacific/Funafuti',
        'Pacific/Kwajalein' => '(GMT+12:00) - Pacific/Kwajalein',
        'Pacific/Majuro' => '(GMT+12:00) - Pacific/Majuro',
        'Pacific/Nauru' => '(GMT+12:00) - Pacific/Nauru',
        'Pacific/Tarawa' => '(GMT+12:00) - Pacific/Tarawa',
        'Pacific/Wake' => '(GMT+12:00) - Pacific/Wake',
        'Pacific/Wallis' => '(GMT+12:00) - Pacific/Wallis',
        'NZ-CHAT' => '(GMT+12:45) - NZ-CHAT',
        'Pacific/Chatham' => '(GMT+12:00) - Pacific/Chatham',
        'Pacific/Enderbury' => '(GMT+13:00) - Pacific/Enderbury',
        'Pacific/Tongatapu' => '(GMT+13:00) - Pacific/Tongatapu',
        'Pacific/Kiritimati' => '(GMT+14:00) - Pacific/Kiritimati',
    ];

    #endregion Constants
    
    #region Properties

    /**
     * The provided name for the Timezone.
     */
    protected string $providedName;

    /**
     * The display value for the Timezone.
     */
    protected string $displayValue;

    #endregion Properties

    #region Constructor

    /**
     * Don't call the constructor directly. If you have a display value, use
     * fromDisplayValue(). If you have a provided name, use fromProvidedName().
     *
     * @param string $providedName The provided name of the timezone
     * @param string $displayValue The display value of the timezone
     */
    protected function __construct(
        string $providedName,
        string $displayValue
    ) {
        $this->providedName = $providedName;
        $this->displayValue = $displayValue;
    }

    #endregion Constructor

    #region Getters and Setters

    /**
     * Returns the provided name for the timezone.
     *
     * @return string The provided name for the timezone
     */
    public function getProvidedName(): string {
        return $this->providedName;
    }

    /**
     * Returns the display value for the timezone.
     *
     * @return string The display value for the timezone
     */
    public function getDisplayValue(): string {
        return $this->displayValue;
    }

    #endregion Getters and Setters

    #region Factory Functions
    
    /**
     * Returns a Timezone for the given Display Value.
     *
     * @param string $displayValue The display value of the timezone
     * @return Timezone The timezone that matches the display value
     * @throws InvalidArgumentException If the display value is not valid
     */
    public static function fromDisplayValue(string $displayValue): Timezone {
        return new self(self::getProvidedNameFromDisplayValue($displayValue), $displayValue);
    }

    /**
     * Returns the timezone provided name for the timezone with the given
     * provided name.
     *
     * @param string $providedName The provided name of the timezone
     *
     */
    public static function fromProvidedName(string $providedName): Timezone {
        return new self($providedName, self::getDisplayValueFromProvidedName($providedName));
    }

    #endregion Factory Functions

    #region Static Methods

    /**
     * Returns the timezone Display Value for the timezone with the given
     * provided name.
     *
     * @param string $providedName The provided name of the timezone
     * @return string The display value of the timezone
     * @throws InvalidArgumentException If the provided name is not valid
     */
    public static function getDisplayValueFromProvidedName(string $providedName): string {
        if (! isset(self::VALID_TIMEZONES_PROVIDED_NAME_TO_DISPLAY_VALUE[$providedName])) {
            throw new InvalidArgumentException(sprintf('Provided name "%s" is not valid.', $providedName));
        }

        return self::VALID_TIMEZONES_PROVIDED_NAME_TO_DISPLAY_VALUE[$providedName];
    }

    /**
     * Returns the timezone provided name for the timezone with the given
     * display value.
     *
     * @param string $displayValue The display value of the timezone
     * @return string The provided name of the timezone
     * @throws InvalidArgumentException If the display value is not valid
     */
    public static function getProvidedNameFromDisplayValue(string $displayValue): string {
        $flippedTimezoneArray = array_flip(self::VALID_TIMEZONES_PROVIDED_NAME_TO_DISPLAY_VALUE);

        if (! isset($flippedTimezoneArray[$displayValue])) {
            throw new InvalidArgumentException(sprintf('Display value "%s" is not valid', $displayValue));
        }

        return $flippedTimezoneArray[$displayValue];
    }
    
    #endregion Static Methods
}
