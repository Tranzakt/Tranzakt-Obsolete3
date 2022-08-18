<?php

/**
 * Create the Tranzakt metadata schema for Tables.
 *
 * This version of the file holds the definitions for metadata relating to
 * definitions for Tables, Columns, Relations, Indexes
 *
 * Create tables by running: php artisan migrate:refresh
 */


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Tranzakt\Developer\Traits\DeveloperMigration;

return new class extends Migration
{
    use DeveloperMigration;

	public function __construct() {
        // parent::__construct();
        $this->setConnectionType(file: basename(__FILE__, '.php'));
	}

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// Remove tables if they already exist.
		$this->down();

		// Table areas - for ER Diagrams, boxes around grouped tables
		Schema::connection($this->connection)
		->create('tranzakt_table_areas', function(Blueprint $table) {
			$table->string('name', 64);
			$table->text('notes')->nullable();
			$table->foreignId('application_id')
                ->constrained('tranzakt_applications')->onDelete('cascade');
			$this->common_columns(
				$table,
				'Tranzakt allows you to group tables into areas for diagramming and documentation purposes'
			);

			$table->unique(['deleted_at', 'application_id', 'name'], 'tranzakt_table_areas_unique_app_id_name');
		});

		// Tables
		Schema::connection($this->connection)
		->create('tranzakt_tables', function(Blueprint $table) {
			$table->string('name', 64)->comment('Application table name');
			$table->string('table_name', 64)->comment('Database table name with application prefix');
			$table->string('comment')->nullable();
			$table->boolean('timestamps')->default(true);
			$table->boolean('userstamps')->default(true);
			$table->boolean('softdeletes')->default(false);
			$table->json('constraints')->nullable()
						->comment(
							"JSON containing table-level constraints. " .
							"These will be used for SQL checks, Laravel validations and Vue validations"
						);
			// Probably should put these in JSON
			// $table->string('engine', 16)->nullable();
			// $table->string('charset', 16)->nullable();
			// $table->string('collation', 16)->nullable();
			$table->json('options')->nullable();
			$table->text('notes')->nullable();
			$table->foreignId('area_id')->nullable()
                ->constrained('tranzakt_table_areas')->onDelete('cascade');
			$table->json('canvas')->nullable()
						->comment('Holds ER diagram position data');
			$table->foreignId('application_id')
                ->constrained('tranzakt_applications')->onDelete('cascade');
			$table->foreignId('database_id')
                ->constrained('tranzakt_databases')->onDelete('cascade');
            $table->boolean('polymorphic');
            $this->common_columns(
				$table,
				'This table holds the list of tables'
			);

			$table->unique(['deleted_at', 'application_id', 'name'], 'tranzakt_tables_unique_app_id_name');
			$table->unique(['deleted_at', 'database_id', 'table_name'], 'tranzakt_tables_unique_db_id_fullname');
		});

		// Columns
		Schema::connection($this->connection)
		->create('tranzakt_table_columns', function(Blueprint $table) {
			$table->string('name', 64);
			$table->string('type', 64)->comment('Laravel column type rather than SQL');
			$table->boolean('nullable')->default(False);
			$table->string('description')->nullable();
			$table->string('default_value')->nullable();
			$table->enum('generated_value_storage', ['VIRTUAL', 'STORED'])->nullable();
			$table->string('generated_as')->nullable();
			$table->json('constraints')->nullable();
			$table->json('options')->nullable();
			$table->text('notes')->nullable();
			$table->foreignId('table_id')
                ->constrained('tranzakt_tables')->onDelete('cascade');
			$this->common_columns(
				$table,
				'This table holds details of every column in every table.'
			);

			$table->unique(['deleted_at', 'table_id', 'name'], 'tranzakt_columns_unique_table_id_name');
		});

		// Indexes
		Schema::connection($this->connection)
		->create('tranzakt_table_indexes', function(Blueprint $table) {
			$table->string('name', 64)->nullable();
			$table->enum('type', ['primary', 'unique', 'non-unique']);
			$table->json('index_columns');
			$table->enum('sort_order', ['ASC', 'DESC'])->default('ASC');
			$table->text('notes')->nullable();
			$table->foreignId('table_id')
                ->constrained('tranzakt_tables')->onDelete('cascade');
			$this->common_columns(
				$table,
				'Details of all indexes on all tables.'
			);
		});

		// Relationships
		Schema::connection($this->connection)
		->create('tranzakt_table_relationships', function(Blueprint $table) {
			$table->string('name', 64);
			$table->string('comment');
			$table->enum('cardinality', ['0..*', '0..1', '1..*', '1..1']);
			$table->foreignId('primary_table_id')
                ->constrained('tranzakt_tables')->onDelete('cascade');
			$table->foreignId('foreign_table_id')
                ->constrained('tranzakt_tables')->onDelete('cascade');
			$table->json('columns');
			$table->boolean('foreign_mandatory');
			$table->text('notes')->nullable();
			$table->json('canvas')->nullable()
						->comment('Holds diagramatic data');
			$this->common_columns(
				$table,
				'This table holds details of the relationships between tables and supports Laravel polymorphic relationships'
			);
		});

		// Seeds
		// Schema::connection($this->connection)
		// ->create('tranzakt_table_seeds', function(Blueprint $table) {
		// 	$table->foreignId('table_id')
        //         ->constrained('tranzakt_tables')->onDelete('cascade');
		// 	$table->json('row_values');
		// 	$this->common_columns(
		// 		$table,
		// 		'Tranzakt supports the Laravel concept of seeds i.e. rows created when the tables are created'
		// 	);
		// });

		echo "Tranzakt tables metadata defined." . PHP_EOL;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		// Because of foreign key relationships, tables need to be dropped in reverse order
		Schema::dropIfExists('tranzakt_table_relationships');
		Schema::dropIfExists('tranzakt_table_seeds');
		Schema::dropIfExists('tranzakt_table_indexes');
		Schema::dropIfExists('tranzakt_table_columns');
		Schema::dropIfExists('tranzakt_tables');
		Schema::dropIfExists('tranzakt_table_areas');
    }
};
