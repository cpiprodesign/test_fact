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
            ['id' => 1, 'name' => 'Comprobantes pendientes de envío', 'slug' => 'tenant.alerts.documents.pendientes-sunat', 'description' => 'Pendientes SUNAT', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 2, 'name' => 'Listar/Ver catálogo', 'slug' => 'tenant.catalogs.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 3, 'name' => 'Cuentas bancarias catálogo', 'slug' => 'tenant.catalogs.cuenta-bancaria', 'description' => 'Cuentas bancarias', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 4, 'name' => 'Monedas catálogo', 'slug' => 'tenant.catalogs.monedas', 'description' => 'Monedas', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 5, 'name' => 'Atributos SUNAT catálogo', 'slug' => 'tenant.catalogs.atributos-sunat', 'description' => 'Atributos SUNAT', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 6, 'name' => 'Categoría productos catálogo', 'slug' => 'tenant.catalogs.categoria-productos', 'description' => 'Categoría productos', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 7, 'name' => 'Bancos catálogo', 'slug' => 'tenant.catalogs.bancos', 'description' => 'Bancos', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 8, 'name' => 'Unidades catálogo', 'slug' => 'tenant.catalogs.unidades', 'description' => 'Unidades', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 9, 'name' => 'Marcas catálogo', 'slug' => 'tenant.catalogs.marcas', 'description' => 'Marcas', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 10, 'name' => 'Avanzado configuración', 'slug' => 'tenant.configuration.advanced.index', 'description' => 'Avanzado', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 11, 'name' => 'Crear empresas', 'slug' => 'tenant.companies.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 12, 'name' => 'Listar/Ver empresas', 'slug' => 'tenant.companies.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 13, 'name' => 'Editar empresas', 'slug' => 'tenant.companies.update', 'description' => 'Editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 14, 'name' => 'Certificado empresas', 'slug' => 'tenant.companies.subir-certificado', 'description' => 'Subir certificado', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 15, 'name' => 'Listar/Ver  establecimientos', 'slug' => 'tenant.establishments.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 16, 'name' => 'Crear establecimientos', 'slug' => 'tenant.establishments.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 17, 'name' => 'Editar establecimientos', 'slug' => 'tenant.establishments.update', 'description' => 'Editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 18, 'name' => 'Eliminar establecimientos', 'slug' => 'tenant.establishments.destroy', 'description' => 'Eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 19, 'name' => 'Series establecimientos', 'slug' => 'tenant.establishments.series', 'description' => 'Series', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 20, 'name' => 'Listar/Ver  usuarios', 'slug' => 'tenant.users.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 21, 'name' => 'Crear usuario', 'slug' => 'tenant.users.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 22, 'name' => 'Eliminar usuario', 'slug' => 'tenant.users.update', 'description' => 'Eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 23, 'name' => 'Editar usuario', 'slug' => 'tenant.users.destroy', 'description' => 'Editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 24, 'name' => 'Listar/Ver Productos', 'slug' => 'tenant.items.index', 'description' => 'Listar/Ver ', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 25, 'name' => 'Crear productos', 'slug' => 'tenant.items.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 26, 'name' => 'Editar productos', 'slug' => 'tenant.items.update', 'description' => 'Editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 27, 'name' => 'Eliminar productos', 'slug' => 'tenant.items.destroy', 'description' => 'Eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 28, 'name' => 'Importar productos', 'slug' => 'tenant.items.import', 'description' => 'Importar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 29, 'name' => 'Listar/Ver  lista de precios', 'slug' => 'tenant.price-list.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 30, 'name' => 'Crear lista de precios', 'slug' => 'tenant.price-list.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 31, 'name' => 'Eliminar lista de precios', 'slug' => 'tenant.price-list.destroy', 'description' => 'Eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 32, 'name' => 'Editar lista de precios', 'slug' => 'tenant.price-list.update', 'description' => 'Editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 33, 'name' => 'Listar/Ver  gastos', 'slug' => 'tenant.expenses.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 34, 'name' => 'Crear gastos', 'slug' => 'tenant.expenses.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 35, 'name' => 'Editar gastos', 'slug' => 'tenant.expenses.update', 'description' => 'Editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 36, 'name' => 'Eliminar gastos', 'slug' => 'tenant.expenses.destroy', 'description' => 'Eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 37, 'name' => 'Listar/Ver  pagos', 'slug' => 'tenant.payments.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 38, 'name' => 'Crear pagos', 'slug' => 'tenant.payments.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 39, 'name' => 'Eliminar pagos', 'slug' => 'tenant.payments.destroy', 'description' => 'Eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 40, 'name' => 'Listar/Ver  bancos', 'slug' => 'tenant.accounts.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 41, 'name' => 'Crear bancos', 'slug' => 'tenant.accounts.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 42, 'name' => 'Editar bancos', 'slug' => 'tenant.accounts.update', 'description' => 'Editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 43, 'name' => 'Eliminar bancos', 'slug' => 'tenant.accounts.destroy', 'description' => 'Eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 44, 'name' => 'Listar/Ver  proveedores', 'slug' => 'tenant.suppliers.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 45, 'name' => 'Crear proveedores', 'slug' => 'tenant.suppliers.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 46, 'name' => 'Editar proveedores', 'slug' => 'tenant.suppliers.update', 'description' => 'Editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 47, 'name' => 'Eliminar proveedores', 'slug' => 'tenant.suppliers.destroy', 'description' => 'Eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 48, 'name' => 'Importar proveedores', 'slug' => 'tenant.suppliers.import', 'description' => 'Importar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 49, 'name' => 'Listar/Ver  clientes', 'slug' => 'tenant.customers.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 50, 'name' => 'Crear clientes', 'slug' => 'tenant.customers.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 51, 'name' => 'Editar clientes', 'slug' => 'tenant.customers.update', 'description' => 'Editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 52, 'name' => 'Eliminar clientes', 'slug' => 'tenant.customers.destroy', 'description' => 'Eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 53, 'name' => 'Import clientes', 'slug' => 'tenant.customers.import', 'description' => 'Importar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 54, 'name' => 'Detalles clientes', 'slug' => 'tenant.customers.detalles', 'description' => 'Detalles', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 55, 'name' => 'Listar/Ver puntos de venta', 'slug' => 'tenant.pos.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 56, 'name' => 'Crear puntos de venta', 'slug' => 'tenant.pos.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 57, 'name' => 'Descargar PDF puntos de venta', 'slug' => 'tenant.pos.report', 'description' => 'Descargar PDF', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 58, 'name' => 'Detalles puntos de venta', 'slug' => 'tenant.pos.detalles', 'description' => 'Detalles', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 59, 'name' => 'Eliminar puntos de venta', 'slug' => 'tenant.pos.destroy', 'description' => 'Cerrar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 60, 'name' => 'Listar/Ver  ventas', 'slug' => 'tenant.documents.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 61, 'name' => 'Crear ventas', 'slug' => 'tenant.documents.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 62, 'name' => 'Editar ventas', 'slug' => 'tenant.documents.update', 'description' => 'Editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 63, 'name' => 'Eliminar ventas', 'slug' => 'tenant.documents.destroy', 'description' => 'Eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 64, 'name' => 'Enviar a SUNAT ventas', 'slug' => 'tenant.documents.enviar-sunat', 'description' => 'Enviar a SUNAT', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 65, 'name' => 'Reporte ventas', 'slug' => 'tenant.documents.report', 'description' => 'Reporte XML/PDF/CDR', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 66, 'name' => 'Pago ventas', 'slug' => 'tenant.documents.agregar-pago', 'description' => 'Pago', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 67, 'name' => 'Email ventas', 'slug' => 'tenant.documents.email', 'description' => 'Email', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 68, 'name' => 'Imprimir ventas', 'slug' => 'tenant.documents.imprimir', 'description' => 'Puntos de venta', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 69, 'name' => 'Puntos de ventas', 'slug' => 'tenant.documents.pos', 'description' => 'Imprimir', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 70, 'name' => 'Documentos configuración', 'slug' => 'tenant.configuration.documents', 'description' => 'Documentos', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 71, 'name' => 'Listar/Ver  notas de crédito', 'slug' => 'tenant.credit-notes.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 72, 'name' => 'Listar/Ver  contizaciones', 'slug' => 'tenant.quotations.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 73, 'name' => 'Crear contizaciones', 'slug' => 'tenant.quotations.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 74, 'name' => 'Editar contizaciones', 'slug' => 'tenant.quotations.update', 'description' => 'Editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 75, 'name' => 'Descargar PDF contizaciones', 'slug' => 'tenant.quotations.report', 'description' => 'Descargar PDF', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 76, 'name' => 'Listar/Ver  notas de venta', 'slug' => 'tenant.sale-notes.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 77, 'name' => 'Crear notas de venta', 'slug' => 'tenant.sale-notes.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 78, 'name' => 'Descargar PDF notas de venta', 'slug' => 'tenant.sale-notes.report', 'description' => 'Descargar PDF', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 79, 'name' => 'Imprimir notas de venta', 'slug' => 'tenant.sale-notes.imprimir', 'description' => 'Imprimir', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 80, 'name' => 'Email notas de venta', 'slug' => 'tenant.sale-notes.email', 'description' => 'Email', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 81, 'name' => 'Listar/Ver  resúmenes ', 'slug' => 'tenant.summaries.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 82, 'name' => 'Descargar XML/PDF/CDR resúmenes', 'slug' => 'tenant.summaries.report', 'description' => 'Descargar XML/PDF/CDR', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 83, 'name' => 'Enviar SUNAT resúmenes', 'slug' => 'tenant.summaries.enviar-sunat', 'description' => 'Enviar SUNAT', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 84, 'name' => 'Listar/Ver  anulaciones', 'slug' => 'tenant.voided.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 85, 'name' => 'Descargar PDF/XML/CDR anulaciones', 'slug' => 'tenant.voided.report', 'description' => 'Descargar PDF/XML/CDR', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 86, 'name' => 'Create anulaciones', 'slug' => 'tenant.voided.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 87, 'name' => 'Enviar SUNAT anulaciones', 'slug' => 'tenant.voided.enviar-sunat', 'description' => 'Enviar SUNAT', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 88, 'name' => 'Listar/Ver  retenciones', 'slug' => 'tenant.retentions.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 89, 'name' => 'Reporte retenciones', 'slug' => 'tenant.retentions.report', 'description' => 'reporte', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 90, 'name' => 'Crear retenciones', 'slug' => 'tenant.retentions.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 91, 'name' => 'Editar retenciones', 'slug' => 'tenant.retentions.update', 'description' => 'Editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 92, 'name' => 'Eliminar retenciones', 'slug' => 'tenant.retentions.destroy', 'description' => 'Eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 93, 'name' => 'Listar/Ver  guía de remisión', 'slug' => 'tenant.dispatches.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 94, 'name' => 'Crear guía de remisión', 'slug' => 'tenant.dispatches.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 95, 'name' => 'Eliminar guía de remisión', 'slug' => 'tenant.dispatches.update', 'description' => 'Eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 96, 'name' => 'Enviar SUNAT guía de remisión', 'slug' => 'tenant.dispatches.resend', 'description' => 'Enviar SUNAT', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 97, 'name' => 'Descargar XML/PDF/CDR guía de remisión', 'slug' => 'tenant.dispatches.report', 'description' => 'Descargar XML/PDF/CDR', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 98, 'name' => 'Listar/Ver  reportes documentos', 'slug' => 'tenant.reports.index', 'description' => 'Documentos', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 99, 'name' => 'Descargar', 'slug' => 'tenant.reports.descargar', 'description' => 'Descargar reportes', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 100, 'name' => 'Listar/Ver  reportes compras', 'slug' => 'tenant.reports.purchases.index', 'description' => 'Compras', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 101, 'name' => 'Listar/Ver  reportes inventario', 'slug' => 'tenant.reports.inventories.index', 'description' => 'Inventarios', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 102, 'name' => 'Listar/Ver  reportes ventas', 'slug' => 'tenant.reports.sells.index', 'description' => 'Ventas', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 103, 'name' => 'Listar/Ver  reportes ventas cliente', 'slug' => 'tenant.reports.customers.index', 'description' => 'Clientes', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 104, 'name' => 'Listar/Ver  reportes gastos', 'slug' => 'tenant.reports.expenses.index', 'description' => 'Gastos', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 105, 'name' => 'Listar/Ver  compras', 'slug' => 'tenant.purchases.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 106, 'name' => 'Editar compras', 'slug' => 'tenant.purchases.update', 'description' => 'Editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 107, 'name' => 'Crear compras', 'slug' => 'tenant.purchases.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 108, 'name' => 'Listar/Ver  roles', 'slug' => 'tenant.roles.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 109, 'name' => 'Crear roles', 'slug' => 'tenant.roles.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 110, 'name' => 'Editar roles', 'slug' => 'tenant.roles.update', 'description' => 'Editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 111, 'name' => 'Eliminar roles', 'slug' => 'tenant.roles.destroy', 'description' => 'Eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 112, 'name' => 'Listar/ver dashboard', 'slug' => 'tenant.dashboard.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 113, 'name' => 'Listar/ver almacenes', 'slug' => 'tenant.warehouses.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 114, 'name' => 'Crear almacenes', 'slug' => 'tenant.warehouses.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 115, 'name' => 'Editar almacenes', 'slug' => 'tenant.warehouses.update', 'description' => 'Editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 116, 'name' => 'Listar/Ver inventario', 'slug' => 'tenant.inventory.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 117, 'name' => 'Trasladar inventario', 'slug' => 'tenant.inventory.trasladar', 'description' => 'Trasladar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 118, 'name' => 'Ajustar inventario', 'slug' => 'tenant.inventory.ajustar', 'description' => 'Ajustar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 119, 'name' => 'Listar/Ver kardex inventario', 'slug' => 'tenant.inventory.report.kardex.index', 'description' => 'Listar/Ver kardex', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 120, 'name' => 'Listar/Ver valor inventario', 'slug' => 'tenant.inventory.report.index', 'description' => 'Listar/Ver valor inventario', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['id' => 121, 'name' => 'Inventarios configuración', 'slug' => 'tenant.configuration.inventories', 'description' => 'Inventarios', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],

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
