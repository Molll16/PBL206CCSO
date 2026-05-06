<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE TRIGGER after_user_insert
            AFTER INSERT ON users
            FOR EACH ROW
            BEGIN
    
                DECLARE dashboard_id BIGINT;
    
                IF NEW.role = 'customer' THEN
    
                    INSERT INTO dasbor_kustom (
                        user_id,
                        nama_dasbor,
                        jenis_dasbor,
                        status_dasbor,
                        created_at,
                        updated_at
                    ) VALUES (
                        NEW.id,
                        'Default',
                        'default',
                        'aktif',
                        NOW(),
                        NOW()
                    );
    
                    SET dashboard_id = LAST_INSERT_ID();
    
                    INSERT INTO hasil_kustom (
                        dasbor_kustom_id,
                        fitur_id,
                        kolom,
                        baris,
                        status_fitur,
                        created_at,
                        updated_at
                    ) VALUES
                    (dashboard_id, 1, 6, 2, 'aktif', NOW(), NOW()),
                    (dashboard_id, 2, 6, 2, 'aktif', NOW(), NOW()),
                    (dashboard_id, 3, 12, 4, 'aktif', NOW(), NOW());
    
                END IF;
    
            END
        ");
    }
    
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS after_user_insert");
    }
};
