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
        if (Schema::hasTable('affiliate_withdraw_requests')) {
            Schema::table('affiliate_withdraw_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('affiliate_withdraw_requests', 'payment_method')) {
                    $table->string('payment_method', 100)->nullable();
                }
                if (!Schema::hasColumn('affiliate_withdraw_requests', 'account_details')) {
                    $table->text('account_details')->nullable();
                }
                if (!Schema::hasColumn('affiliate_withdraw_requests', 'transaction_id')) {
                    $table->string('transaction_id', 150)->nullable();
                }
                if (!Schema::hasColumn('affiliate_withdraw_requests', 'remarks')) {
                    $table->text('remarks')->nullable();
                }
                if (!Schema::hasColumn('affiliate_withdraw_requests', 'approved_by')) {
                    $table->integer('approved_by')->nullable();
                }
                if (!Schema::hasColumn('affiliate_withdraw_requests', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable();
                }
            });
        }

        if (Schema::hasTable('affiliate_logs')) {
            Schema::table('affiliate_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('affiliate_logs', 'customer_id')) {
                    $table->integer('customer_id')->nullable();
                }
                if (!Schema::hasColumn('affiliate_logs', 'commission_type')) {
                    $table->string('commission_type', 50)->nullable();
                }
                if (!Schema::hasColumn('affiliate_logs', 'commission_value')) {
                    $table->double('commission_value', 20, 2)->nullable();
                }
                if (!Schema::hasColumn('affiliate_logs', 'status')) {
                    $table->string('status', 20)->default('pending');
                }
                if (!Schema::hasColumn('affiliate_logs', 'approved_at')) {
                    $table->timestamp('approved_at')->nullable();
                }
            });
        }

        if (!Schema::hasTable('affiliate_customers')) {
            Schema::create('affiliate_customers', function (Blueprint $table) {
                $table->id();
                $table->integer('affiliate_id');
                $table->integer('customer_id');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('affiliate_wallets')) {
            Schema::create('affiliate_wallets', function (Blueprint $table) {
                $table->id();
                $table->integer('affiliate_id');
                $table->double('total_earned', 20, 2)->default(0.00);
                $table->double('total_withdrawn', 20, 2)->default(0.00);
                $table->double('available_balance', 20, 2)->default(0.00);
                $table->double('pending_balance', 20, 2)->default(0.00);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_customers');
        Schema::dropIfExists('affiliate_wallets');

        if (Schema::hasTable('affiliate_withdraw_requests')) {
            Schema::table('affiliate_withdraw_requests', function (Blueprint $table) {
                $table->dropColumn(['payment_method', 'account_details', 'transaction_id', 'remarks', 'approved_by', 'approved_at']);
            });
        }

        if (Schema::hasTable('affiliate_logs')) {
            Schema::table('affiliate_logs', function (Blueprint $table) {
                $table->dropColumn(['customer_id', 'commission_type', 'commission_value', 'status', 'approved_at']);
            });
        }
    }
};
