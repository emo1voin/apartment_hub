<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');

            $table->date('check_in');
            $table->date('check_out');
            $table->integer('nights');

            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->integer('infants')->default(0);
            $table->integer('total_guests')->default(1);
            $table->json('guest_details')->nullable();

            $table->decimal('price_per_night', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('service_fee', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2);

            $table->enum('status', [
                'pending',
                'confirmed',
                'cancelled',
                'completed',
                'no_show',
                'refunded'
            ])->default('pending');

            $table->enum('payment_status', [
                'pending',
                'paid',
                'partially_paid',
                'refunded',
                'failed'
            ])->default('pending');

            $table->string('payment_method')->nullable();
            $table->string('payment_id')->nullable();
            $table->json('payment_details')->nullable();

            $table->json('extras')->nullable();
            $table->decimal('extras_total', 10, 2)->default(0);

            $table->text('special_requests')->nullable();
            $table->text('admin_notes')->nullable();

            $table->time('estimated_arrival_time')->nullable();
            $table->timestamp('check_in_time')->nullable();
            $table->timestamp('check_out_time')->nullable();

            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->decimal('cancellation_fee', 10, 2)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index(['hotel_id', 'check_in', 'check_out']);
            $table->index('booking_number');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
