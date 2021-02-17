<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Province;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::updateOrcreate(['code' =>'CAD' , 'name' => 'Canadian Dollar']);
        Currency::updateOrcreate(['code' =>'USD' , 'name' => 'US Dollar']);
        
        // Currency::updateOrcreate(['code' =>'EUR' , 'name' => 'Euro']);
        // Currency::updateOrcreate(['code' =>'AFN' , 'name' => 'Afghani']);
        // Currency::updateOrcreate(['code' =>'ALL' , 'name' => 'Lek']);
        // Currency::updateOrcreate(['code' =>'ANG' , 'name' => 'Netherlands Antillian Guilder']);
        // Currency::updateOrcreate(['code' =>'ARS' , 'name' => 'Argentine Peso']);
        // Currency::updateOrcreate(['code' =>'AUD' , 'name' => 'Australian Dollar']);
        // Currency::updateOrcreate(['code' =>'AWG' , 'name' => 'Aruban Guilder']);
        // Currency::updateOrcreate(['code' =>'AZN' , 'name' => 'Azerbaijanian Manat']);
        // Currency::updateOrcreate(['code' =>'BAM' , 'name' => 'Convertible Marks']);
        // Currency::updateOrcreate(['code' =>'BBD' , 'name' => 'Barbados Dollar']);
        // Currency::updateOrcreate(['code' =>'BGN' , 'name' => 'Bulgarian Lev']);
        // Currency::updateOrcreate(['code' =>'BMD' , 'name' => 'Bermudian Dollar']);
        // Currency::updateOrcreate(['code' =>'BND' , 'name' => 'Brunei Dollar']);
        // Currency::updateOrcreate(['code' =>'BOB' , 'name' => 'Boliviano']);
        // Currency::updateOrcreate(['code' =>'BRL' , 'name' => 'Brazilian Real']);
        // Currency::updateOrcreate(['code' =>'BSD' , 'name' => 'Bahamian Dollar']);
        // Currency::updateOrcreate(['code' =>'BWP' , 'name' => 'Pula']);
        // Currency::updateOrcreate(['code' =>'BYR' , 'name' => 'Belarussian Ruble']);
        // Currency::updateOrcreate(['code' =>'BZD' , 'name' => 'Belize Dollar']);
        // Currency::updateOrcreate(['code' =>'CHF' , 'name' => 'Swiss Franc']);
        // Currency::updateOrcreate(['code' =>'CLP' , 'name' => 'Chilean Peso']);
        // Currency::updateOrcreate(['code' =>'CNY' , 'name' => 'Yuan Renminbi']);
        // Currency::updateOrcreate(['code' =>'COP' , 'name' => 'Colombian Peso']);
        // Currency::updateOrcreate(['code' =>'CRC' , 'name' => 'Costa Rican Colon']);
        // Currency::updateOrcreate(['code' =>'CUP' , 'name' => 'Cuban Peso']);
        // Currency::updateOrcreate(['code' =>'CZK' , 'name' => 'Czech Koruna']);
        // Currency::updateOrcreate(['code' =>'DKK' , 'name' => 'Danish Krone']);
        // Currency::updateOrcreate(['code' =>'DOP' , 'name' => 'Dominican Peso']);
        // Currency::updateOrcreate(['code' =>'EGP' , 'name' => 'Egyptian Pound']);
        // Currency::updateOrcreate(['code' =>'FJD' , 'name' => 'Fiji Dollar']);
        // Currency::updateOrcreate(['code' =>'FKP' , 'name' => 'Falkland Islands Pound']);
        // Currency::updateOrcreate(['code' =>'GBP' , 'name' => 'Pound Sterling']);
        // Currency::updateOrcreate(['code' =>'GIP' , 'name' => 'Gibraltar Pound']);
        // Currency::updateOrcreate(['code' =>'GTQ' , 'name' => 'Quetzal']);
        // Currency::updateOrcreate(['code' =>'GYD' , 'name' => 'Guyana Dollar']);
        // Currency::updateOrcreate(['code' =>'HKD' , 'name' => 'Hong Kong Dollar']);
        // Currency::updateOrcreate(['code' =>'HNL' , 'name' => 'Lempira']);
        // Currency::updateOrcreate(['code' =>'HRK' , 'name' => 'Croatian Kuna']);
        // Currency::updateOrcreate(['code' =>'HUF' , 'name' => 'Forint']);
        // Currency::updateOrcreate(['code' =>'IDR' , 'name' => 'Rupiah']);
        // Currency::updateOrcreate(['code' =>'ILS' , 'name' => 'New Israeli Sheqel']);
        // Currency::updateOrcreate(['code' =>'IRR' , 'name' => 'Iranian Rial']);
        // Currency::updateOrcreate(['code' =>'ISK' , 'name' => 'Iceland Krona']);
        // Currency::updateOrcreate(['code' =>'JMD' , 'name' => 'Jamaican Dollar']);
        // Currency::updateOrcreate(['code' =>'JPY' , 'name' => 'Yen']);
        // Currency::updateOrcreate(['code' =>'KGS' , 'name' => 'Som']);
        // Currency::updateOrcreate(['code' =>'KHR' , 'name' => 'Riel']);
        // Currency::updateOrcreate(['code' =>'KPW' , 'name' => 'North Korean Won']);
        // Currency::updateOrcreate(['code' =>'KRW' , 'name' => 'Won']);
        // Currency::updateOrcreate(['code' =>'KYD' , 'name' => 'Cayman Islands Dollar']);
        // Currency::updateOrcreate(['code' =>'KZT' , 'name' => 'Tenge']);
        // Currency::updateOrcreate(['code' =>'LAK' , 'name' => 'Kip']);
        // Currency::updateOrcreate(['code' =>'LBP' , 'name' => 'Lebanese Pound']);
        // Currency::updateOrcreate(['code' =>'LKR' , 'name' => 'Sri Lanka Rupee']);
        // Currency::updateOrcreate(['code' =>'LRD' , 'name' => 'Liberian Dollar']);
        // Currency::updateOrcreate(['code' =>'LTL' , 'name' => 'Lithuanian Litas']);
        // Currency::updateOrcreate(['code' =>'LVL' , 'name' => 'Latvian Lats']);
        // Currency::updateOrcreate(['code' =>'MKD' , 'name' => 'Denar']);
        // Currency::updateOrcreate(['code' =>'MNT' , 'name' => 'Tugrik']);
        // Currency::updateOrcreate(['code' =>'MUR' , 'name' => 'Mauritius Rupee']);
        // Currency::updateOrcreate(['code' =>'MXN' , 'name' => 'Mexican Peso']);
        // Currency::updateOrcreate(['code' =>'MYR' , 'name' => 'Malaysian Ringgit']);
        // Currency::updateOrcreate(['code' =>'MZN' , 'name' => 'Metical']);
        // Currency::updateOrcreate(['code' =>'NGN' , 'name' => 'Naira']);
        // Currency::updateOrcreate(['code' =>'NIO' , 'name' => 'Cordoba Oro']);
        // Currency::updateOrcreate(['code' =>'NOK' , 'name' => 'Norwegian Krone']);
        // Currency::updateOrcreate(['code' =>'NPR' , 'name' => 'Nepalese Rupee']);
        // Currency::updateOrcreate(['code' =>'NZD' , 'name' => 'New Zealand Dollar']);
        // Currency::updateOrcreate(['code' =>'OMR' , 'name' => 'Rial Omani']);
        // Currency::updateOrcreate(['code' =>'PAB' , 'name' => 'Panamanian Balboa']);
        // Currency::updateOrcreate(['code' =>'PEN' , 'name' => 'Nuevo Sol']);
        // Currency::updateOrcreate(['code' =>'PHP' , 'name' => 'Philippine Peso']);
        // Currency::updateOrcreate(['code' =>'PKR' , 'name' => 'Pakistan Rupee']);
        // Currency::updateOrcreate(['code' =>'PLN' , 'name' => 'Zloty']);
        // Currency::updateOrcreate(['code' =>'PYG' , 'name' => 'Guarani']);
        // Currency::updateOrcreate(['code' =>'QAR' , 'name' => 'Qatari Rial']);
        // Currency::updateOrcreate(['code' =>'RON' , 'name' => 'New Leu']);
        // Currency::updateOrcreate(['code' =>'RSD' , 'name' => 'Serbian Dinar']);
        // Currency::updateOrcreate(['code' =>'RUB' , 'name' => 'Russian Ruble']);
        // Currency::updateOrcreate(['code' =>'SAR' , 'name' => 'Saudi Riyal']);
        // Currency::updateOrcreate(['code' =>'SBD' , 'name' => 'Solomon Islands Dollar']);
        // Currency::updateOrcreate(['code' =>'SCR' , 'name' => 'Seychelles Rupee']);
        // Currency::updateOrcreate(['code' =>'SEK' , 'name' => 'Swedish Krona']);
        // Currency::updateOrcreate(['code' =>'SGD' , 'name' => 'Singapore Dollar']);
        // Currency::updateOrcreate(['code' =>'SHP' , 'name' => 'Saint Helena Pound']);
        // Currency::updateOrcreate(['code' =>'SOS' , 'name' => 'Somali Shilling']);
        // Currency::updateOrcreate(['code' =>'SRD' , 'name' => 'Surinam Dollar']);
        // Currency::updateOrcreate(['code' =>'SVC' , 'name' => 'El Salvador Colon']);
        // Currency::updateOrcreate(['code' =>'SYP' , 'name' => 'Syrian Pound']);
        // Currency::updateOrcreate(['code' =>'THB' , 'name' => 'Baht']);
        // Currency::updateOrcreate(['code' =>'TRY' , 'name' => 'Turkish Lira']);
        // Currency::updateOrcreate(['code' =>'TTD' , 'name' => 'Trinidad and Tobago Dollar']);
        // Currency::updateOrcreate(['code' =>'TWD' , 'name' => 'New Taiwan Dollar']);
        // Currency::updateOrcreate(['code' =>'UAH' , 'name' => 'Hryvnia']);
        // Currency::updateOrcreate(['code' =>'UYU' , 'name' => 'Uruguay Peso']);
        // Currency::updateOrcreate(['code' =>'UZS' , 'name' => 'Uzbekistan Sum']);
        // Currency::updateOrcreate(['code' =>'VEF' , 'name' => 'Bolivar Fuerte']);
        // Currency::updateOrcreate(['code' =>'VND' , 'name' => 'Dong']);
        // Currency::updateOrcreate(['code' =>'XCD' , 'name' => 'East Caribbean Dollar']);
        // Currency::updateOrcreate(['code' =>'YER' , 'name' => 'Yemeni Rial']);
        // Currency::updateOrcreate(['code' =>'ZAR' , 'name' => 'Rand']);
    }
}

