if (!Schema::hasTable('categories')) {
    Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('nomcat', 50);
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->string('image')->nullable();
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamp('approved_at')->nullable();
        $table->text('rejection_reason')->nullable();
        $table->timestamps();
    });
}