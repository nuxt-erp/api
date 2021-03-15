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
        Currency::updateOrCreate(['code' =>'CAD' , 'name' => 'Canadian Dollar']);
        Currency::updateOrCreate(['code' =>'USD' , 'name' => 'US Dollar']);

        // Currency::updateOrCreate(['code' =>'EUR' , 'name' => 'Euro']);
        // Currency::updateOrCreate(['code' =>'AFN' , 'name' => 'Afghani']);
        // Currency::updateOrCreate(['code' =>'ALL' , 'name' => 'Lek']);
        // Currency::updateOrCreate(['code' =>'ANG' , 'name' => 'Netherlands Antillian Guilder']);
        // Currency::updateOrCreate(['code' =>'ARS' , 'name' => 'Argentine Peso']);
        // Currency::updateOrCreate(['code' =>'AUD' , 'name' => 'Australian Dollar']);
        // Currency::updateOrCreate(['code' =>'AWG' , 'name' => 'Aruban Guilder']);
        // Currency::updateOrCreate(['code' =>'AZN' , 'name' => 'Azerbaijanian Manat']);
        // Currency::updateOrCreate(['code' =>'BAM' , 'name' => 'Convertible Marks']);
        // Currency::updateOrCreate(['code' =>'BBD' , 'name' => 'Barbados Dollar']);
        // Currency::updateOrCreate(['code' =>'BGN' , 'name' => 'Bulgarian Lev']);
        // Currency::updateOrCreate(['code' =>'BMD' , 'name' => 'Bermudian Dollar']);
        // Currency::updateOrCreate(['code' =>'BND' , 'name' => 'Brunei Dollar']);
        // Currency::updateOrCreate(['code' =>'BOB' , 'name' => 'Boliviano']);
        // Currency::updateOrCreate(['code' =>'BRL' , 'name' => 'Brazilian Real']);
        // Currency::updateOrCreate(['code' =>'BSD' , 'name' => 'Bahamian Dollar']);
        // Currency::updateOrCreate(['code' =>'BWP' , 'name' => 'Pula']);
        // Currency::updateOrCreate(['code' =>'BYR' , 'name' => 'Belarussian Ruble']);
        // Currency::updateOrCreate(['code' =>'BZD' , 'name' => 'Belize Dollar']);
        // Currency::updateOrCreate(['code' =>'CHF' , 'name' => 'Swiss Franc']);
        // Currency::updateOrCreate(['code' =>'CLP' , 'name' => 'Chilean Peso']);
        // Currency::updateOrCreate(['code' =>'CNY' , 'name' => 'Yuan Renminbi']);
        // Currency::updateOrCreate(['code' =>'COP' , 'name' => 'Colombian Peso']);
        // Currency::updateOrCreate(['code' =>'CRC' , 'name' => 'Costa Rican Colon']);
        // Currency::updateOrCreate(['code' =>'CUP' , 'name' => 'Cuban Peso']);
        // Currency::updateOrCreate(['code' =>'CZK' , 'name' => 'Czech Koruna']);
        // Currency::updateOrCreate(['code' =>'DKK' , 'name' => 'Danish Krone']);
        // Currency::updateOrCreate(['code' =>'DOP' , 'name' => 'Dominican Peso']);
        // Currency::updateOrCreate(['code' =>'EGP' , 'name' => 'Egyptian Pound']);
        // Currency::updateOrCreate(['code' =>'FJD' , 'name' => 'Fiji Dollar']);
        // Currency::updateOrCreate(['code' =>'FKP' , 'name' => 'Falkland Islands Pound']);
        // Currency::updateOrCreate(['code' =>'GBP' , 'name' => 'Pound Sterling']);
        // Currency::updateOrCreate(['code' =>'GIP' , 'name' => 'Gibraltar Pound']);
        // Currency::updateOrCreate(['code' =>'GTQ' , 'name' => 'Quetzal']);
        // Currency::updateOrCreate(['code' =>'GYD' , 'name' => 'Guyana Dollar']);
        // Currency::updateOrCreate(['code' =>'HKD' , 'name' => 'Hong Kong Dollar']);
        // Currency::updateOrCreate(['code' =>'HNL' , 'name' => 'Lempira']);
        // Currency::updateOrCreate(['code' =>'HRK' , 'name' => 'Croatian Kuna']);
        // Currency::updateOrCreate(['code' =>'HUF' , 'name' => 'Forint']);
        // Currency::updateOrCreate(['code' =>'IDR' , 'name' => 'Rupiah']);
        // Currency::updateOrCreate(['code' =>'ILS' , 'name' => 'New Israeli Sheqel']);
        // Currency::updateOrCreate(['code' =>'IRR' , 'name' => 'Iranian Rial']);
        // Currency::updateOrCreate(['code' =>'ISK' , 'name' => 'Iceland Krona']);
        // Currency::updateOrCreate(['code' =>'JMD' , 'name' => 'Jamaican Dollar']);
        // Currency::updateOrCreate(['code' =>'JPY' , 'name' => 'Yen']);
        // Currency::updateOrCreate(['code' =>'KGS' , 'name' => 'Som']);
        // Currency::updateOrCreate(['code' =>'KHR' , 'name' => 'Riel']);
        // Currency::updateOrCreate(['code' =>'KPW' , 'name' => 'North Korean Won']);
        // Currency::updateOrCreate(['code' =>'KRW' , 'name' => 'Won']);
        // Currency::updateOrCreate(['code' =>'KYD' , 'name' => 'Cayman Islands Dollar']);
        // Currency::updateOrCreate(['code' =>'KZT' , 'name' => 'Tenge']);
        // Currency::updateOrCreate(['code' =>'LAK' , 'name' => 'Kip']);
        // Currency::updateOrCreate(['code' =>'LBP' , 'name' => 'Lebanese Pound']);
        // Currency::updateOrCreate(['code' =>'LKR' , 'name' => 'Sri Lanka Rupee']);
        // Currency::updateOrCreate(['code' =>'LRD' , 'name' => 'Liberian Dollar']);
        // Currency::updateOrCreate(['code' =>'LTL' , 'name' => 'Lithuanian Litas']);
        // Currency::updateOrCreate(['code' =>'LVL' , 'name' => 'Latvian Lats']);
        // Currency::updateOrCreate(['code' =>'MKD' , 'name' => 'Denar']);
        // Currency::updateOrCreate(['code' =>'MNT' , 'name' => 'Tugrik']);
        // Currency::updateOrCreate(['code' =>'MUR' , 'name' => 'Mauritius Rupee']);
        // Currency::updateOrCreate(['code' =>'MXN' , 'name' => 'Mexican Peso']);
        // Currency::updateOrCreate(['code' =>'MYR' , 'name' => 'Malaysian Ringgit']);
        // Currency::updateOrCreate(['code' =>'MZN' , 'name' => 'Metical']);
        // Currency::updateOrCreate(['code' =>'NGN' , 'name' => 'Naira']);
        // Currency::updateOrCreate(['code' =>'NIO' , 'name' => 'Cordoba Oro']);
        // Currency::updateOrCreate(['code' =>'NOK' , 'name' => 'Norwegian Krone']);
        // Currency::updateOrCreate(['code' =>'NPR' , 'name' => 'Nepalese Rupee']);
        // Currency::updateOrCreate(['code' =>'NZD' , 'name' => 'New Zealand Dollar']);
        // Currency::updateOrCreate(['code' =>'OMR' , 'name' => 'Rial Omani']);
        // Currency::updateOrCreate(['code' =>'PAB' , 'name' => 'Panamanian Balboa']);
        // Currency::updateOrCreate(['code' =>'PEN' , 'name' => 'Nuevo Sol']);
        // Currency::updateOrCreate(['code' =>'PHP' , 'name' => 'Philippine Peso']);
        // Currency::updateOrCreate(['code' =>'PKR' , 'name' => 'Pakistan Rupee']);
        // Currency::updateOrCreate(['code' =>'PLN' , 'name' => 'Zloty']);
        // Currency::updateOrCreate(['code' =>'PYG' , 'name' => 'Guarani']);
        // Currency::updateOrCreate(['code' =>'QAR' , 'name' => 'Qatari Rial']);
        // Currency::updateOrCreate(['code' =>'RON' , 'name' => 'New Leu']);
        // Currency::updateOrCreate(['code' =>'RSD' , 'name' => 'Serbian Dinar']);
        // Currency::updateOrCreate(['code' =>'RUB' , 'name' => 'Russian Ruble']);
        // Currency::updateOrCreate(['code' =>'SAR' , 'name' => 'Saudi Riyal']);
        // Currency::updateOrCreate(['code' =>'SBD' , 'name' => 'Solomon Islands Dollar']);
        // Currency::updateOrCreate(['code' =>'SCR' , 'name' => 'Seychelles Rupee']);
        // Currency::updateOrCreate(['code' =>'SEK' , 'name' => 'Swedish Krona']);
        // Currency::updateOrCreate(['code' =>'SGD' , 'name' => 'Singapore Dollar']);
        // Currency::updateOrCreate(['code' =>'SHP' , 'name' => 'Saint Helena Pound']);
        // Currency::updateOrCreate(['code' =>'SOS' , 'name' => 'Somali Shilling']);
        // Currency::updateOrCreate(['code' =>'SRD' , 'name' => 'Surinam Dollar']);
        // Currency::updateOrCreate(['code' =>'SVC' , 'name' => 'El Salvador Colon']);
        // Currency::updateOrCreate(['code' =>'SYP' , 'name' => 'Syrian Pound']);
        // Currency::updateOrCreate(['code' =>'THB' , 'name' => 'Baht']);
        // Currency::updateOrCreate(['code' =>'TRY' , 'name' => 'Turkish Lira']);
        // Currency::updateOrCreate(['code' =>'TTD' , 'name' => 'Trinidad and Tobago Dollar']);
        // Currency::updateOrCreate(['code' =>'TWD' , 'name' => 'New Taiwan Dollar']);
        // Currency::updateOrCreate(['code' =>'UAH' , 'name' => 'Hryvnia']);
        // Currency::updateOrCreate(['code' =>'UYU' , 'name' => 'Uruguay Peso']);
        // Currency::updateOrCreate(['code' =>'UZS' , 'name' => 'Uzbekistan Sum']);
        // Currency::updateOrCreate(['code' =>'VEF' , 'name' => 'Bolivar Fuerte']);
        // Currency::updateOrCreate(['code' =>'VND' , 'name' => 'Dong']);
        // Currency::updateOrCreate(['code' =>'XCD' , 'name' => 'East Caribbean Dollar']);
        // Currency::updateOrCreate(['code' =>'YER' , 'name' => 'Yemeni Rial']);
        // Currency::updateOrCreate(['code' =>'ZAR' , 'name' => 'Rand']);
    }
}

