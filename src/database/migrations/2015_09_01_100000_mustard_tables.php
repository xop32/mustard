<?php

/*

This file is part of Mustard.

Mustard is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Mustard is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Mustard.  If not, see <http://www.gnu.org/licenses/>.

*/

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MustardTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table)
        {
            $table->integer('user_id')->unsigned();
            $table->string('email', 64);
            $table->string('username', 64);
            $table->string('password', 64);
            $table->rememberToken();
            $table->char('locale', 5);
            $table->char('currency', 3);
            $table->integer('joined')->unsigned();
            $table->integer('last_login')->unsigned();

            $table->primary('user_id');
            $table->unique('email');
        });

        Schema::create('categories', function(Blueprint $table)
        {
            $table->mediumInteger('category_id', true)->unsigned();
            $table->mediumInteger('parent_category_id')->nullable()->unsigned();
            $table->string('name', 128);
            $table->string('slug', 32);
            $table->tinyInteger('sort')->unsigned();

            $table->foreign('parent_category_id')->references('category_id')->on('categories');
            $table->unique(['parent_category_id', 'slug']);
        });

        Schema::create('item_conditions', function(Blueprint $table)
        {
            $table->mediumInteger('item_condition_id', true)->unsigned();
            $table->string('name', 64);
        });

        Schema::create('listing_durations', function(Blueprint $table)
        {
            $table->mediumInteger('listing_duration_id', true)->unsigned();
            $table->string('name', 64);
            $table->integer('duration')->unsigned();
        });

        Schema::create('items', function(Blueprint $table)
        {
            $table->integer('item_id')->unsigned();
            $table->string('name', 128);
            $table->mediumInteger('item_condition_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->text('description');
            $table->boolean('auction');
            $table->integer('quantity')->unsigned();
            $table->decimal('start_price', 8, 2)->unsigned();
            $table->decimal('reserve_price', 8, 2)->unsigned();
            $table->decimal('bidding_price', 8, 2)->unsigned();
            $table->decimal('fixed_price', 8, 2)->unsigned();
            $table->decimal('commission', 5, 2)->unsigned();
            $table->integer('winning_bid_id')->unsigned();
            $table->integer('duration')->unsigned();
            $table->integer('start_date')->unsigned();
            $table->integer('end_date')->unsigned();
            $table->integer('created')->unsigned();
            $table->string('collection_location');
            $table->boolean('payment_other');
            $table->tinyInteger('returns_period')->unsigned();

            $table->primary('item_id');
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('item_condition_id')->references('item_condition_id')->on('item_conditions');
        });

        Schema::create('item_categories', function(Blueprint $table)
        {
            $table->integer('item_id')->unsigned();
            $table->mediumInteger('category_id')->unsigned();

            $table->primary(['item_id', 'category_id']);
            $table->foreign('item_id')->references('item_id')->on('items');
            $table->foreign('category_id')->references('category_id')->on('categories');
        });

        Schema::create('delivery_options', function(Blueprint $table)
        {
            $table->integer('delivery_option_id', true)->unsigned();
            $table->integer('item_id')->unsigned();
            $table->string('name', 64);
            $table->float('price', 5, 2)->unsigned();
            $table->tinyInteger('min_arrival_time')->unsigned();
            $table->tinyInteger('max_arrival_time')->unsigned();

            $table->foreign('item_id')->references('item_id')->on('items');
        });

        Schema::create('failed_jobs', function(Blueprint $table)
        {
            $table->increments('failed_job_id');
            $table->text('connection');
            $table->text('queue');
            $table->text('payload');
            $table->timestamp('failed_at');
        });

        Schema::create('watched_items', function(Blueprint $table)
        {
            $table->integer('item_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('added')->unsigned();

            $table->primary(['item_id', 'user_id']);
            $table->foreign('item_id')->references('item_id')->on('items');
            $table->foreign('user_id')->references('user_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('watched_items');
        Schema::drop('failed_jobs');
        Schema::drop('item_categories');
        Schema::drop('items');
        Schema::drop('item_conditions');
        Schema::drop('listing_durations');
        Schema::drop('categories');
        Schema::drop('users');
    }
}