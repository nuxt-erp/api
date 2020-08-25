<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
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
        Schema::dropIfExists('sales');
    }
}
/*
CREATE TABLE IF NOT EXISTS `erp_db`.`sales` (
  `order_number` VARCHAR(45) NULL,
  `customer_id` INT NULL,
  `sales_date` DATETIME NULL,
  `financial_status` TINYINT NULL,
  `fulfillment_status` TINYINT NULL,
  `fulfillment_date` DATETIME NULL,
  `payment_date` DATETIME NULL,
  `user_id` INT NULL,
  `company_id` INT NULL,
  `subtotal` FLOAT NULL,
  `discount` FLOAT NULL,
  `taxes` FLOAT NULL,
  `shipping` FLOAT NULL,
  `total` FLOAT NULL,
  `order_status_label` VARCHAR(30) NULL,
*/
