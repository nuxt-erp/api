<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_details');
    }
}
/*
CREATE TABLE IF NOT EXISTS `erp_db`.`sale_details` (
  `sale_id` INT NULL,
  `product_id` INT NULL,
  `qty` DOUBLE NULL,
  `price` FLOAT NULL,
  `discount_value` FLOAT NULL,
  `discount_percent` FLOAT NULL,
  `total_item` FLOAT NULL,
  `shopify_lineitem` VARCHAR(45) NULL,
  `qty_fulfilled` DOUBLE NULL,
  `fulfillment_status` TINYINT NULL,
  `fulfillment_date` DATETIME NULL,
  `location_id` INT NULL,
*/
