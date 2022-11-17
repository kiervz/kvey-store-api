<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\User;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('ulid', 40);
            $table->foreignIdFor(User::class, 'user_id');
            $table->string('status', 2);
            $table->decimal('total_amount', 12, 2);
            $table->string('type', 20);
            $table->string('session_id', 191);
            $table->timestamp('submit_at');
            $table->timestamp('process_at');
            $table->timestamp('shipped_at');
            $table->timestamp('delivered_at');
            $table->timestamp('order_received_at');
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
        Schema::dropIfExists('orders');
    }
}
