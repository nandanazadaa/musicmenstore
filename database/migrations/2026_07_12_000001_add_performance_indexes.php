<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasIndex('products', 'products_tanggal_index')) {
                $table->index('tanggal');
            }

            if (!Schema::hasIndex('products', 'products_created_at_index')) {
                $table->index('created_at');
            }

            if (!Schema::hasIndex('products', 'products_input_system_created_at_index')) {
                $table->index(['input_system', 'created_at']);
            }
        });

        Schema::table('product_pics', function (Blueprint $table) {
            if (!Schema::hasIndex('product_pics', 'product_pics_pic_name_index')) {
                $table->index('pic_name');
            }
        });

        Schema::table('service_harians', function (Blueprint $table) {
            if (!Schema::hasIndex('service_harians', 'service_harians_tanggal_masuk_index')) {
                $table->index('tanggal_masuk');
            }

            if (!Schema::hasIndex('service_harians', 'service_harians_created_at_index')) {
                $table->index('created_at');
            }
        });

        Schema::table('assignments', function (Blueprint $table) {
            if (!Schema::hasIndex('assignments', 'assignments_staff_id_status_created_at_index')) {
                $table->index(['staff_id', 'status', 'created_at']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            if (Schema::hasIndex('assignments', 'assignments_staff_id_status_created_at_index')) {
                $table->dropIndex('assignments_staff_id_status_created_at_index');
            }
        });

        Schema::table('service_harians', function (Blueprint $table) {
            if (Schema::hasIndex('service_harians', 'service_harians_created_at_index')) {
                $table->dropIndex('service_harians_created_at_index');
            }

            if (Schema::hasIndex('service_harians', 'service_harians_tanggal_masuk_index')) {
                $table->dropIndex('service_harians_tanggal_masuk_index');
            }
        });

        Schema::table('product_pics', function (Blueprint $table) {
            if (Schema::hasIndex('product_pics', 'product_pics_pic_name_index')) {
                $table->dropIndex('product_pics_pic_name_index');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasIndex('products', 'products_input_system_created_at_index')) {
                $table->dropIndex('products_input_system_created_at_index');
            }

            if (Schema::hasIndex('products', 'products_created_at_index')) {
                $table->dropIndex('products_created_at_index');
            }

            if (Schema::hasIndex('products', 'products_tanggal_index')) {
                $table->dropIndex('products_tanggal_index');
            }
        });
    }
};
