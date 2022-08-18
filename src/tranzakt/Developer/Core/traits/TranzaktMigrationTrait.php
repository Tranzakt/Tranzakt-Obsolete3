<?php

namespace Tranzakt\Developer\Traits;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Enhance standard Laravel Migration to support:
 * standardised fields for each Metadata table
 */

 trait DeveloperMigration {
    // Connection type - used for RDBMS specific definitions
    private string $connection_type;
	private bool $is_mysql;
	private bool $is_postgres;
	private bool $is_sqlserver;
	private bool $is_sqlite;

	/**
	 * Determine the database connection for Tranzakt internal tables.
	 *
	 * Also determine RDBMS type in case we need to do RDBMS specific stuff.
	 */
	public function setConnectionType(string $file) {
		// Get the Tranzakt default connection
		$this->connection = config('database.tranzakt', null);

		// Get the connection type
		$this->connection_type = Schema::getConnection()->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME);
        /* ToDo Replace with logging */
		echo "'" . $file . "' using " . $this->connection_type . " connection " . ($this->connection ?? '(default)') . ":\n";
		$this->is_mysql     = $this->connection_type == 'mysql';
		$this->is_postgres  = $this->connection_type == 'postgres';
		$this->is_sqlserver = $this->connection_type == 'sqlserver';
		$this->is_sqlite    = $this->connection_type == $this->connection;
	}

	/**
	 * Define columns that are common to all tables.
	 */
	private function common_columns(Blueprint $table, string $comment = '', bool $versioned = true)
	{
		if ($this->is_mysql) {
			$table->engine = 'InnoDB';
		}
		$table->charset = 'utf8mb4';
		if ($comment <> '' and ($this->is_mysql or $this->is_postgres)) {
			$table->comment($comment);
		}

        $versioned ? $table->integer('version') : null; // -1 = WIP, 0 = current version, >0 = release version
        $table->id();
		$table->timestamps(); // Standard laravel created / last updated timestamps.
		$table->softDeletes(); // All records can be sent to trash and recovered.
		$table->userstamps(); // sqits/laravel-userstamps
		$table->softUserstamps();  // sqits/laravel-userstamps

		// Fields to limit access to specific developers / teams will be added here
	}

 }
