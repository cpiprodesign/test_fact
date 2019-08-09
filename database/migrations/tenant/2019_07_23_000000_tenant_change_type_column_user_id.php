<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantChangeTypeColumnUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::table('documents', function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::table('summaries', function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::table('voided', function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::table('retentions', function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::table('perceptions', function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::table('dispatches', function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::table('purchases', function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::table('pos', function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::table('quotations', function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::table('sale_notes', function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        Schema::table('expenses', function(Blueprint $table){
            $table->dropForeign(['user_id']);
        });

        //--------------------------

        Schema::table('users', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });

        //----------------------------------------

        Schema::table('module_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('summaries', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('voided', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('retentions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('perceptions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('dispatches', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users');
        });
        
        Schema::table('purchases', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('pos', function (Blueprint $table) {
            DB::statement('ALTER TABLE pos CHANGE `user_id` `user_id` BIGINT (10) UNSIGNED NOT NULL');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('sale_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        
    }
}
