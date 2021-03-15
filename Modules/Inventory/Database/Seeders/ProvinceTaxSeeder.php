<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Province;
use App\Models\TaxRule;
use App\Models\TaxRuleComponent;
use App\Models\TaxRuleScope;

class ProvinceTaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $canada = Country::firstOrCreate([
            'name' => 'Canada'
        ]);

        $provinces = [
            'NL' => Province::updateOrCreate([
                'code'      => 'NL',
                ],
                [
                'name'      => 'Newfoundland and Labrador',
                'code'      => 'NL',
                'country_id'=> $canada->id
            ]),
            'PE' => Province::updateOrCreate([
                'code'      => 'PE',
            ],
            [
                'name'      => 'Prince Edward Island',
                'code'      => 'PE',
                'country_id'=> $canada->id
            ]),
            'NS' => Province::updateOrCreate([
                'code'      => 'NS',
                ],
                [
                'name'      => 'Nova Scotia',
                'code'      => 'NS',
                'country_id'=> $canada->id
            ]),
            'NB' => Province::updateOrCreate([
                'code'      => 'NB',
            ],
            [
                'name'      => 'New Brunswick',
                'code'      => 'NB',
                'country_id'=> $canada->id
            ]),
            'QC' => Province::updateOrCreate(
                [
                    'code'      => 'QC',
                ],
                [
                'name'      => 'Quebec',
                'code'      => 'QC',
                'country_id'=> $canada->id
            ]),
            'ON' => Province::updateOrCreate([
                 'code'      => 'ON',
                ],
                [
                'name'      => 'Ontario',
                'code'      => 'ON',
                'country_id'=> $canada->id
            ]),
            'MB' => Province::updateOrCreate([
                'code'      => 'MB',
            ],
            [
                'name'      => 'Manitoba',
                'code'      => 'MB',
                'country_id'=> $canada->id
            ]),
            'SK' => Province::updateOrCreate([
                'code'      => 'SK',
            ],
            [
                'name'      => 'Saskatchewan',
                'code'      => 'SK',
                'country_id'=> $canada->id
            ]),
            'AB' => Province::updateOrCreate([
                'code'      => 'AB',
            ],
            [
                'name'      => 'Alberta',
                'code'      => 'AB',
                'country_id'=> $canada->id
            ]),
            'BC' => Province::updateOrCreate([
                'code'      => 'BC',
            ],
            [
                'name'      => 'British Columbia',
                'code'      => 'BC',
                'country_id'=> $canada->id
            ]),
            'YT' => Province::updateOrCreate([
                'code'      => 'YT',
            ],
            [
                'name'      => 'Yukon',
                'code'      => 'YT',
                'country_id'=> $canada->id
            ]),
            'NT' => Province::updateOrCreate([
                'code'      => 'NT',
            ],
            [
                'name'      => 'Northwest Territories',
                'code'      => 'NT',
                'country_id'=> $canada->id
            ]),
            'NU' => Province::updateOrCreate([
                'code'      => 'NU',
            ],
            [
                'name'      => 'Nunavut',
                'code'      => 'NU',
                'country_id'=> $canada->id
            ]),
        ];
        $tax_rules = [
            'on_hst' => TaxRule::updateOrCreate(
            [
                'name'            => 'ON HST 13%'
            ],
            [
                'name'            => 'ON HST 13%',
                'short_name'      => '13%',
                'computation'     => 'percent_on_price',
                'status'          => 1,
                'province_id'     => $provinces['ON']->id
            ]),

            'ab_gst' => TaxRule::updateOrCreate(
            [
                'name'            => 'AB GST 5%'
            ],
            [
                'name'            => 'AB GST 5%',
                'short_name'      => '5%',
                'computation'     => 'percent_on_price',
                'status'          => 1,
                'province_id'     => $provinces['AB']->id
            ]),

            'bc_gst' => TaxRule::updateOrCreate(
            [
                'name'            => 'BC GST 12%'
            ],
            [
                'name'            => 'BC GST 12%',
                'short_name'      => '12%',
                'computation'     => 'percent_on_price',
                'status'          => 1,
                'province_id'     => $provinces['BC']->id
            ]),
            'entertainment' => TaxRule::updateOrCreate(
            [
                'name'            => 'Entertainment 6.5%'
            ],
            [
                'name'            => 'Entertainment 6.5%',
                'short_name'      => '6.5%',
                'computation'     => 'percent_on_price',
                'status'          => 1,
                'province_id'     => null
            ]),
            'nl_hst' => TaxRule::updateOrCreate(
            [
                'name'            => 'NL HST 15%'
            ],
            [
                'name'            => 'NL HST 15%',
                'short_name'      => '15%',
                'computation'     => 'percent_on_price',
                'status'          => 1,
                'province_id'     => $provinces['NL']->id
            ]),
            'nu_gst' => TaxRule::updateOrCreate(
            [
                'name'            => 'NU GST 5%'
            ],
            [
                'name'            => 'NU GST 5%',
                'short_name'      => '5%',
                'computation'     => 'percent_on_price',
                'status'          => 1,
                'province_id'     => $provinces['NU']->id
            ]),
            'nb_hst' => TaxRule::updateOrCreate(
            [
                'name'            => 'NB HST 15%'
            ],
            [
                'name'            => 'NB HST 15%',
                'short_name'      => '15%',
                'computation'     => 'percent_on_price',
                'status'          => 1,
                'province_id'     => $provinces['NB']->id
            ]),
            'nt_gst' => TaxRule::updateOrCreate(
            [
                'name'            => 'NT GST 5%'
            ],
            [
                'name'            => 'NT GST 5%',
                'short_name'      => '5%',
                'computation'     => 'percent_on_price',
                'status'          => 1,
                'province_id'     => $provinces['NT']->id
            ]),
            'ns_hst' => TaxRule::updateOrCreate(
            [
                'name'            => 'NS HST 15%'
            ],
            [
                'name'            => 'NS HST 15%',
                'short_name'      => '15%',
                'computation'     => 'percent_on_price',
                'status'          => 1,
                'province_id'     => $provinces['NS']->id
            ]),
            'pe_hst' => TaxRule::updateOrCreate(
            [
                'name'            => 'PE HST 15%'
            ],
            [
                'name'            => 'PE HST 15%',
                'short_name'      => '15%',
                'computation'     => 'percent_on_price',
                'status'          => 1,
                'province_id'     => $provinces['PE']->id
            ]),
            'mb_gst' => TaxRule::updateOrCreate(
            [
                'name'            => 'MB GST 12%'
            ],
            [
                'name'            => 'MB GST 12%',
                'short_name'      => '12%',
                'computation'     => 'percent_on_price',
                'status'          => 1,
                'province_id'     => $provinces['MB']->id
            ]),
            'qc_gst' => TaxRule::updateOrCreate(
            [
                'name'            => 'QC GST 14.98%'
            ],
            [
                'name'            => 'QC GST 14.98%',
                'short_name'      => '14.98%',
                'computation'     => 'percent_on_price',
                'status'          => 1,
                'province_id'     => $provinces['QC']->id
            ]),
            'yt_gst' => TaxRule::updateOrCreate(
            [
                'name'            => 'YT GST 5%'
            ],
            [
                'name'            => 'YT GST 5%',
                'short_name'      => '5%',
                'computation'     => 'percent_on_price',
                'status'          => 1,
                'province_id'     => $provinces['YT']->id
            ]),
            'sk_gst' => TaxRule::updateOrCreate(
            [
                'name'            => 'SK GST 11%'
            ],
            [
                'name'            => 'SK GST 11%',
                'short_name'      => '11%',
                'computation'     => 'percent_on_price',
                'status'          => 1,
                'province_id'     => $provinces['SK']->id
            ])

        ];
        $tax_components = [
            'on_hst' => TaxRuleComponent::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['on_hst']->id,
                'component_name'  => 'HST',
                'rate'            => 0.13,
                'compound'        => 0,
                'seq'             => 0
            ]),

            'ab_gst' => TaxRuleComponent::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['ab_gst']->id,
                'component_name'  => 'GST',
                'rate'            => 0.05,
                'compound'        => 0,
                'seq'             => 0
            ]),
            'bc_gst' => TaxRuleComponent::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['bc_gst']->id,
                'component_name'  => 'GST',
                'rate'            => 0.12,
                'compound'        => 0,
                'seq'             => 0
            ]),
            'entertainment' => TaxRuleComponent::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['entertainment']->id,
                'component_name'  => 'Entertainment',
                'rate'            => 0.065,
                'compound'        => 0,
                'seq'             => 0
            ]),
            'nl_hst' => TaxRuleComponent::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['nl_hst']->id,
                'component_name'  => 'HST',
                'rate'            => 0.15,
                'compound'        => 0,
                'seq'             => 0
            ]),
            'nu_gst' => TaxRuleComponent::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['nu_gst']->id,
                'component_name'  => 'GST',
                'rate'            => 0.05,
                'compound'        => 0,
                'seq'             => 0
            ]),
            'nb_hst' => TaxRuleComponent::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['nb_hst']->id,
                'component_name'  => 'HST',
                'rate'            => 0.15,
                'compound'        => 0,
                'seq'             => 0
            ]),
            'nt_gst' => TaxRuleComponent::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['nt_gst']->id,
                'component_name'  => 'GST',
                'rate'            => 0.05,
                'compound'        => 0,
                'seq'             => 0
            ]),
            'ns_hst' => TaxRuleComponent::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['ns_hst']->id,
                'component_name'  => 'HST',
                'rate'            => 0.15,
                'compound'        => 0,
                'seq'             => 0
            ]),
            'pe_hst' => TaxRuleComponent::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['pe_hst']->id,
                'component_name'  => 'HST',
                'rate'            => 0.15,
                'compound'        => 0,
                'seq'             => 0
            ]),
            'mb_gst' => TaxRuleComponent::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['mb_gst']->id,
                'component_name'  => 'GST',
                'rate'            => 0.12,
                'compound'        => 0,
                'seq'             => 0
            ]),
            'yt_gst' => TaxRuleComponent::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['yt_gst']->id,
                'component_name'  => 'GST',
                'rate'            => 0.05,
                'compound'        => 0,
                'seq'             => 0
            ]),
            'sk_gst' => TaxRuleComponent::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['sk_gst']->id,
                'component_name'  => 'GST',
                'rate'            => 0.11,
                'compound'        => 0,
                'seq'             => 0
            ])

        ];

        $tax_scopes = [
            'on_hst_sales' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['on_hst']->id,
                'scope'           => 'sales'
            ]),
            'on_hst_purchases' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['on_hst']->id,
                'scope'           => 'purchases'
            ]),
            'ab_gst_sales' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['ab_gst']->id,
                'scope'           => 'sales'
            ]),
            'ab_gst_purchases' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['ab_gst']->id,
                'scope'           => 'purchases'
            ]),
            'ab_gst_sales' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['ab_gst']->id,
                'scope'           => 'sales'
            ]),
            'ab_gst_purchases' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['ab_gst']->id,
                'scope'           => 'purchases'
            ]),
            'entertainment' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['entertainment']->id,
                'scope'           => 'sales'
            ]),
            'entertainment' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['entertainment']->id,
                'scope'           => 'purchases'
            ]),
            'nl_hst_sales' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['nl_hst']->id,
                'scope'           => 'sales'
            ]),
            'nl_hst_purchases' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['nl_hst']->id,
                'scope'           => 'purchases'
            ]),
            'nu_gst_sales' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['nu_gst']->id,
                'scope'           => 'sales'
            ]),
            'nu_gst_purchases' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['nu_gst']->id,
                'scope'           => 'purchases'
            ]),
            'nb_hst_sales' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['nb_hst']->id,
                'scope'           => 'sales'
            ]),
            'nb_hst_purchases' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['nb_hst']->id,
                'scope'           => 'purchases'
            ]),
            'nt_gst_sales' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['nt_gst']->id,
                'scope'           => 'sales'
            ]),
            'nt_gst_purchases' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['nt_gst']->id,
                'scope'           => 'purchases'
            ]),
            'ns_hst_sales' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['ns_hst']->id,
                'scope'           => 'sales'
            ]),
            'ns_hst_purchases' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['ns_hst']->id,
                'scope'           => 'purchases'
            ]),
            'pe_hst_sales' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['pe_hst']->id,
                'scope'           => 'sales'
            ]),
            'pe_hst_purchases' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['pe_hst']->id,
                'scope'           => 'purchases'
            ]),
            'mb_gst_sales' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['mb_gst']->id,
                'scope'           => 'sales'
            ]),
            'mb_gst_purchases' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['mb_gst']->id,
                'scope'           => 'purchases'
            ]),
            'qc_gst_sales' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['qc_gst']->id,
                'scope'           => 'sales'
            ]),
            'qc_gst_purchases' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['qc_gst']->id,
                'scope'           => 'purchases'
            ]),
            'yt_gst_sales' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['yt_gst']->id,
                'scope'           => 'sales'
            ]),
            'yt_gst_purchases' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['yt_gst']->id,
                'scope'           => 'purchases'
            ]),
            'sk_gst_sales' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['sk_gst']->id,
                'scope'           => 'sales'
            ]),
            'sk_gst_purchases' => TaxRuleScope::updateOrCreate(
            [
                'tax_rule_id'     => $tax_rules['sk_gst']->id,
                'scope'           => 'purchases'
            ])

        ];

    }
}

