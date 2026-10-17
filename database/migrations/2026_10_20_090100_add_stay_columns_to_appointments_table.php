<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Un rendez-vous peut maintenant être soit sur un créneau horaire (type "slot"),
// soit un séjour de plusieurs jours (type "stay") avec une date d'arrivée et de départ.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('type', 10)->default('slot');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        });

        // Un séjour n'a pas de créneau horaire : la colonne devient facultative.
        // Les contrôles de clé étrangère sont suspendus le temps de la modification
        // (MySQL les exige parfois pour changer une colonne liée à une autre table).
        Schema::disableForeignKeyConstraints();

        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('availability_id')->nullable()->change();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['type', 'start_date', 'end_date']);
        });
    }
};
