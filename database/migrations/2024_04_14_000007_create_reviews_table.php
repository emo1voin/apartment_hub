<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');

            $table->unsignedTinyInteger('rating_cleanliness')->default(5);
            $table->unsignedTinyInteger('rating_comfort')->default(5);
            $table->unsignedTinyInteger('rating_location')->default(5);
            $table->unsignedTinyInteger('rating_service')->default(5);
            $table->unsignedTinyInteger('rating_value')->default(5);

            $table->decimal('rating', 3, 2)->default(5.00);

            $table->text('title')->nullable();
            $table->text('comment')->nullable();
            $table->text('pros')->nullable();
            $table->text('cons')->nullable();

            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_country')->nullable();

            $table->date('travel_date')->nullable();
            $table->string('travel_type')->nullable();

            $table->boolean('is_verified')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_recommended')->default(true);

            $table->text('hotel_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users');

            $table->integer('helpful_votes')->default(0);
            $table->json('helpful_users')->nullable();

            $table->json('images')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['hotel_id', 'rating']);
            $table->index(['hotel_id', 'created_at']);
            $table->index('user_id');
            $table->index('is_approved');
            $table->index('is_verified');

            $table->unique(['user_id', 'hotel_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
