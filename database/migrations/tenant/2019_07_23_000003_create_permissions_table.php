<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $name = config('shinobi.tables.permissions');

        Schema::create($name, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });


        DB::table('permissions')->insert([
            ['id' => 1, 'name' => 'Comprobantes pendientes de envío', 'slug' => 'tenant.alerts.documents.index', 'description' => 'Comprobantes pendientes', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 2, 'name' => 'Categorías', 'slug' => 'tenant.configuration.catalogs.index', 'description' => 'categorías', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 3, 'name' => 'Avanzado', 'slug' => 'tenant.configuration.advanced.index', 'description' => 'avanzado', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 4, 'name' => 'Crear empresas', 'slug' => 'tenant.configuration.companies.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 5, 'name' => 'Crear configuración', 'slug' => 'tenant.configurations.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 6, 'name' => 'Eliminar certificados', 'slug' => 'tenant.certificates.destroy', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 7, 'name' => 'Index establecimientos', 'slug' => 'tenant.configuration.establishments.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 8, 'name' => 'Crear establecimientos', 'slug' => 'tenant.configuration.establishments.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 9, 'name' => 'Editar establecimientos', 'slug' => 'tenant.configuration.establishments.update', 'description' => 'editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 10, 'name' => 'Eliminar establecimientos', 'slug' => 'tenant.configuration.establishments.destroy', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 11, 'name' => 'Crear series', 'slug' => 'tenant.inventory.series.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 12, 'name' => 'Eliminar series', 'slug' => 'tenant.inventory.series.destroy', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 13, 'name' => 'Index usuarios', 'slug' => 'tenant.users.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 14, 'name' => 'Crear usuario', 'slug' => 'tenant.users.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 15, 'name' => 'Eliminar usuario', 'slug' => 'tenant.users.update', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 16, 'name' => 'Editar usuario', 'slug' => 'tenant.users.destroy', 'description' => 'editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 17, 'name' => 'Index productos', 'slug' => 'tenant.items.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 18, 'name' => 'Crear productos', 'slug' => 'tenant.items.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 19, 'name' => 'Editar productos', 'slug' => 'tenant.items.update', 'description' => 'editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 20, 'name' => 'Eliminar productos', 'slug' => 'tenant.items.destroy', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 21, 'name' => 'Importar productos', 'slug' => 'tenant.items.import', 'description' => 'importar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 22, 'name' => 'Index lista de precios', 'slug' => 'tenant.price-list.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 23, 'name' => 'Crear lista de precios', 'slug' => 'tenant.price-list.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 24, 'name' => 'Eliminar lista de precios', 'slug' => 'tenant.price-list.destroy', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 25, 'name' => 'Editar lista de precios', 'slug' => 'tenant.price-list.update', 'description' => 'editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 26, 'name' => 'Index gastos', 'slug' => 'tenant.expenses.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 27, 'name' => 'Crear gastos', 'slug' => 'tenant.expenses.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 28, 'name' => 'Editar gastos', 'slug' => 'tenant.expenses.update', 'description' => 'editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 29, 'name' => 'Eliminar gastos', 'slug' => 'tenant.expenses.destroy', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 30, 'name' => 'Index pagos', 'slug' => 'tenant.payments.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 31, 'name' => 'Crear pagos', 'slug' => 'tenant.payments.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 32, 'name' => 'Editar pagos', 'slug' => 'tenant.payments.update', 'description' => 'editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 33, 'name' => 'Eliminar pagos', 'slug' => 'tenant.payments.destroy', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 34, 'name' => 'Index bancos', 'slug' => 'tenant.accounts.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 35, 'name' => 'Crear bancos', 'slug' => 'tenant.accounts.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 36, 'name' => 'Editar bancos', 'slug' => 'tenant.accounts.update', 'description' => 'editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 37, 'name' => 'Eliminar bancos', 'slug' => 'tenant.accounts.destroy', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 38, 'name' => 'Index proveedores', 'slug' => 'tenant.persons.suppliers.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 39, 'name' => 'Crear proveedores', 'slug' => 'tenant.persons.suppliers.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 40, 'name' => 'Editar proveedores', 'slug' => 'tenant.persons.suppliers.update', 'description' => 'editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 41, 'name' => 'Eliminar proveedores', 'slug' => 'tenant.persons.suppliers.destroy', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 42, 'name' => 'Importar proveedores', 'slug' => 'tenant.persons.suppliers.import', 'description' => 'import', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 43, 'name' => 'Index clientes', 'slug' => 'tenant.persons.customers.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 44, 'name' => 'Crear clientes', 'slug' => 'tenant.persons.customers.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 45, 'name' => 'Editar clientes', 'slug' => 'tenant.persons.customers.update', 'description' => 'editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 46, 'name' => 'Eliminar clientes', 'slug' => 'tenant.persons.customers.destroy', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 47, 'name' => 'Import clientes', 'slug' => 'tenant.persons.customers.import', 'description' => 'import', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 48, 'name' => 'Index puntos de venta', 'slug' => 'tenant.pos.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 49, 'name' => 'Crear puntos de venta', 'slug' => 'tenant.pos.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 50, 'name' => 'Reporte puntos de venta', 'slug' => 'tenant.pos.report', 'description' => 'reporte', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 51, 'name' => 'Eliminar puntos de venta', 'slug' => 'tenant.pos.destroy', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 52, 'name' => 'Index documentos', 'slug' => 'tenant.documents.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 53, 'name' => 'Crear documentos', 'slug' => 'tenant.documents.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 54, 'name' => 'Eliminar documentos', 'slug' => 'tenant.documents.update', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 55, 'name' => 'Configuracion documentos', 'slug' => 'tenant.documents.configuracion', 'description' => 'configuracion', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 56, 'name' => 'Index notas de crédito', 'slug' => 'tenant.credit-notes.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 57, 'name' => 'Index contizaciones', 'slug' => 'tenant.quotations.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 58, 'name' => 'Crear contizaciones', 'slug' => 'tenant.quotations.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 59, 'name' => 'Editar contizaciones', 'slug' => 'tenant.quotations.update', 'description' => 'editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 60, 'name' => 'Reporte contizaciones', 'slug' => 'tenant.quotations.report', 'description' => 'reporte', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 61, 'name' => 'Index notas de venta', 'slug' => 'tenant.sale-notes.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 62, 'name' => 'Crear notas de venta', 'slug' => 'tenant.sale-notes.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 63, 'name' => 'Reporte notas de venta', 'slug' => 'tenant.sale-notes.report', 'description' => 'reporte', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 64, 'name' => 'Eliminar notas de venta', 'slug' => 'tenant.sale-notes.destroy', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 65, 'name' => 'Editar notas de venta', 'slug' => 'tenant.sale-notes.update', 'description' => 'editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 66, 'name' => 'Index resúmenes ', 'slug' => 'tenant.summaries.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 67, 'name' => 'Crear resúmenes', 'slug' => 'tenant.summaries.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 68, 'name' => 'Reporte resúmenes', 'slug' => 'tenant.summaries.report', 'description' => 'reporte', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 69, 'name' => 'Index anulaciones', 'slug' => 'tenant.voided.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 70, 'name' => 'Reporte anulaciones', 'slug' => 'tenant.voided.report', 'description' => 'reporte', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 71, 'name' => 'Create anulaciones', 'slug' => 'tenant.voided.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 72, 'name' => 'Index retenciones', 'slug' => 'tenant.retentions.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 73, 'name' => 'Reporte retenciones', 'slug' => 'tenant.retentions.report', 'description' => 'reporte', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 74, 'name' => 'Crear retenciones', 'slug' => 'tenant.retentions.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 75, 'name' => 'Editar retenciones', 'slug' => 'tenant.retentions.update', 'description' => 'editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 76, 'name' => 'Eliminar retenciones', 'slug' => 'tenant.retentions.destroy', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 77, 'name' => 'Index guía de remisión', 'slug' => 'tenant.dispatches.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 78, 'name' => 'Crear guía de remisión', 'slug' => 'tenant.dispatches.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 79, 'name' => 'Eliminar guía de remisión', 'slug' => 'tenant.dispatches.update', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 80, 'name' => 'Reenviar guía de remisión', 'slug' => 'tenant.dispatches.resend', 'description' => 'reenviar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 81, 'name' => 'Index reportes', 'slug' => 'tenant.reports.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 82, 'name' => 'Index reportes compras', 'slug' => 'tenant.reports.purchases.index', 'description' => 'principal compras', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 83, 'name' => 'Index reportes inventario', 'slug' => 'tenant.reports.inventories.index', 'description' => 'principal inventario', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 84, 'name' => 'Index reportes ventas', 'slug' => 'tenant.reports.sells.index', 'description' => 'principal ventas', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 85, 'name' => 'Index reportes ventas cliente', 'slug' => 'tenant.reports.customers.index', 'description' => 'principal cliente', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 86, 'name' => 'Index reportes gastos', 'slug' => 'tenant.reports.expenses.index', 'description' => 'principal gastos', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 87, 'name' => 'Index percepciones', 'slug' => 'tenant.perceptions.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 88, 'name' => 'Crear percepciones', 'slug' => 'tenant.perceptions.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 89, 'name' => 'Eliminar percepciones', 'slug' => 'tenant.perceptions.destroy', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 90, 'name' => 'Editar percepciones', 'slug' => 'tenant.perceptions.update', 'description' => 'editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 91, 'name' => 'Index compras', 'slug' => 'tenant.purchases.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 92, 'name' => 'Editar compras', 'slug' => 'tenant.purchases.update', 'description' => 'editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 93, 'name' => 'Crear compras', 'slug' => 'tenant.purchases.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 94, 'name' => 'Index roles', 'slug' => 'tenant.roles.index', 'description' => 'principal', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 95, 'name' => 'Crear roles', 'slug' => 'tenant.roles.store', 'description' => 'crear', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 96, 'name' => 'Editar roles', 'slug' => 'tenant.roles.update', 'description' => 'editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 97, 'name' => 'Eliminar roles', 'slug' => 'tenant.roles.destroy', 'description' => 'eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],

        ]);
        
        

    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        $name = config('shinobi.tables.permissions');

        Schema::drop($name);
    }
}
