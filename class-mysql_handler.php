<?php

/**
 * mysql class that handels all queries
 */

class MysqlHandler
{

    private static $USER;
    private static $PW;
    private static $HOST;
    private static $DB_NAME;

    //public grab args
    
    public function __construct($user, $pw, $host, $db_name)
    {
        self::$USER = $user;
        self::$PW = $pw;
        self::$HOST = $host;
        self::$DB_NAME = $db_name;
        $this->connection = MysqlHandler::getConnection($user, $pw, $host, $db_name);
    }

    // since connection is peresistant, a new connection will only be created if existing ones are all in use.
    private static function getConnection()
    {
        $host = self::$HOST;
        $db_name = self::$DB_NAME;
        $connection = new PDO(
            "mysql:host={$host};dbname={$db_name}",
            self::$USER,
            self::$PW,
            array(PDO::ATTR_PERSISTENT => true)
        );
        return $connection;
    }

    public static $INSERT_IGNORE = 'i';
    public static $DUPLICATE_UPDATE = 'u';
    // std object that holds a modle of DB for caching
    private static $DB_SCHEMA = null;


    /*
    *   Returns the names of all the columns in $table
    */
    private static function getColumns($table)
    {
        // if columns not cached in DB_SCHEMA get them
        if (!isset(self::$DB_SCHEMA) || !isset(self::$DB_SCHEMA[$table]) || self::$DB_SCHEMA[$table] !== []) {
            // ensure the tables are set
            $verified_tables = self::getTables();
            if (in_array($table, $verified_tables)) {
                $query = "DESCRIBE {$table}";
                try {
                    $connection = self::getConnection();
                    $stmt = $connection->prepare($query);
                    $stmt->execute();
                    // fetch the first column in the results which is the tables column names
                    $columns = [];
                    while ($column_name = $stmt->fetchColumn(0)) {
                        array_push($columns, $column_name);
                    }
                    self::$DB_SCHEMA[$table] = $columns;
                } catch (PDOException $e) {
                    $errorMsg = date('m/d/Y h:i:s a', time()).': '.$e->_toString;
                    error_log($errorMsg);
                    die();
                }
            }
        }
        return self::$DB_SCHEMA[$table];
    }

    /*
    *   Return the names of all tables in the database of the connection
    */
    private static function getTables()
    {
        if (!isset(self::$DB_SCHEMA)) {
            $query = "SHOW TABLES";
            try {
                $connection = self::getConnection();
                $stmt = $connection->prepare($query);
                $stmt->execute();
                $tables = [];
                while ($table_name = $stmt->fetchColumn(0)) {
                    array_push($tables, $table_name);
                }
                foreach ($tables as $table) {
                    // if that table hasnt been cached,
                    // create it and set to null so it exsists, but is not set
                    if (!isset(self::$DB_SCHEMA[$table])) {
                        self::$DB_SCHEMA[$table] = null;
                    }
                }
            } catch (PDOException $e) {
                $errorMsg = date('m/d/Y h:i:s a', time()).': '.$e->_toString;
                error_log($errorMsg);
                die();
            }
        }
        return array_keys(self::$DB_SCHEMA);
    }

    /*
        Function that performs a select query from one table
        $table is a string table name
        $columns is an array of the desired columns names
        $conditional is an array of conditional stucured like
            'column' => column name
            'operator' => valid operator for comparsion ('=', '<', '>')
            'value' => value being compared against
            TODO add support for isnull
            TODO change structure of conditinals to support or's and and's
    */
    public function select($table, $columns, $conditionals = null)
    {
        // verify table is good
        if (in_array($table, self::getTables())) {
            // build query verifing columns as we go
            $query = "SELECT ";
            $verified_columns = self::getColumns($table);
            foreach ($columns as $column) {
                if (in_array($column, $verified_columns)) {
                    $query .= " {$column}, ";
                }
            }
            // Strip the last ", ""
            $query = substr($query, 0, strlen($query) - 2);
            $query .= " FROM {$table}";
            // if conditionals is set
            if (isset($conditionals)) {
                $query .= " WHERE ";
                // for each conditional
                 $i = 0;
                foreach ($conditionals as $conditional) {
                    // if column is legit and operator is allowed
                    if (in_array($conditional['column'], $verified_columns) && strpos("<=>=", $conditional['operator']) !== false) {
                        $query .= "{$conditional['column']} {$conditional['operator']} :value{$i} AND";
                        $i++;
                    }
                }
                // Strip the last " AND"
                $query = substr($query, 0, strlen($query) - 4);
            }
            try {
                $connection = self::getConnection();
                $stmt = $connection->prepare($query);
                // bind each value for conditional
                for ($i = 0; $i < count($conditionals); $i++) {
                    $stmt->bindParam("value{$i}", $conditionals[$i]['value']);
                }
                $stmt->execute();
                $results = $stmt->fetchAll();
                return $results;
            } catch (PDOException $e) {
                $errorMsg = date('m/d/Y h:i:s a', time()).': '.$e->_toString;
                error_log($errorMsg);
                die();
            }
            // return all found rows in an array
        } else {
            return false;
        }
    }

    /*
        Function that performs an insert query
        $table is a string table name
        $value_pairs is an array of column => value pairs
        TODO add flags
        $flags is a string used to augment the query.
            Use 'u' or Static variable DUPLICATE_UPDATE for an on duplicate update
            use 'i' or static variable INSERT_IGNORE for an insert ignore
    */
    public function insert($table, $value_pairs, $flags = null)
    {
        // verify table is good
        if (in_array($table, self::getTables())) {
            // TODO check for flags and act accordingly
            // build query verifying column names and adding placeholders as we go
            $query = "INSERT INTO {$table} (";
            $verified_columns = self::getColumns($table);
            foreach ($value_pairs as $column => $value) {
                // if column is legit add it to query
                if (in_array($column, $verified_columns)) {
                    $query .= "{$column}, ";
                }
            }
            // strip off trailing ", "
            $query = substr($query, 0, strlen($query) - 2);
            $query .= ") VALUES (";
            foreach ($value_pairs as $column => $value) {
                $query .= ":{$column}, ";
            }
            // strip off trailing ", "
            $query = substr($query, 0, strlen($query) - 2);
            $query .= ")";
            
                $connection = self::getConnection();
                $connection->beginTransaction();
                $stmt = $connection->prepare($query);
                // bind all values
                // since bindParams only checks variable values at execution
                // and we are using dyanmically named placeholders
                // we have to build an array (where the variables are immeadiately evaluated) and pass that to execute.
                $params = [];
                foreach ($value_pairs as $column => $value) {
                    
                    $params[":{$column}"] = $value;
                }
                $stmt->execute($params);
                $num_rows = $stmt->rowCount();
                $insert_id = $connection->lastInsertId();
                $connection->commit();
                // return number of rows affected and id of insert
                return ['rows'=>$num_rows, 'id'=>$insert_id];
            
        } else {
            return false;
        }
    }

    /*
        function to run an update query
        $table is a string table name
        $value_pairs is an array of column => value pairs
        $conditional is an array of conditionals stucured like
            'column' => column name
            'operator' => valid operator for comparsion ('=', '<', '>', '<=', '>='')
            'value' => value being compared against
            TODO add type for compare vs is null
    */
    public function update($table, $value_pairs, $conditionals)
    {
        // verify table is good
        if (in_array($table, self::getTables())) {
            // build query verifying columns as we go
            $query = "UPDATE {$table} SET ";
            $verified_columns = self::getColumns($table);
            $i = 0;
            foreach ($value_pairs as $column => $value) {
                if (in_array($column, $verified_columns)) {
                    $query .= "{$column} = :{$column}, ";
                    $i++;
                }
            }
            // strip the trailing ", " and add the where to
            $query = substr($query, 0, strlen($query) - 2) . " WHERE ";
            foreach ($conditionals as $conditional) {
                // if column is legit and operator is allowed
                if (in_array($conditional['column'], $verified_columns) && strpos(">=<=", $conditional['operator']) !== false) {
                    $query .= "{$conditional['column']} {$conditional['operator']} :conditional{$conditional['column']} AND";
                    $i++;
                }
            }
            // Strip the last " AND"
            $query = substr($query, 0, strlen($query) - 4);
            try {
                $connection = self::getConnection();
                $connection->beginTransaction();
                $stmt = $connection->prepare($query);
                // bind all values
                // since bindParams only checks variable values at execution
                // and we are using dyanmically named placeholders
                // we have to build an array (where the variables are immeadiately evaluated) and pass that to execute.
                $params = [];
                foreach ($value_pairs as $column => $value) {
                    $params[":{$column}"] = $value;
                }
                foreach ($conditionals as $conditional) {
                    $params[":conditional{$conditional['column']}"] = $conditional['value'];
                }
               
                $stmt->execute($params);
                $num_rows = $stmt->rowCount();
                $insert_id = $connection->lastInsertId();
                $connection->commit();
                // return number of rows affected and id of insert
                return ['rows'=>$num_rows, 'id'=>$insert_id];
            } catch (PDOException $e) {
                $connection->rollback();
                $errorMsg = date('m/d/Y h:i:s a', time()).': '.$e->_toString;
                error_log($errorMsg);
                die();
            }
        // do the thing
        } else {
            return false;
        }
    }

    // TODO test this function
    public function execute($query, $params_array, $fetch = false)
    {
        try {
            $connection = self::getConnection();
            $connection->beginTransaction();
            $stmt = $connection->prepare($query);
            $stmt->execute($params_array);
            if ($fetch) {
                $results = [];
                while ($result_array = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($results, $result_array);
                }
                $connection->commit();
                return $results;
            } else {
                $num_rows = $stmt->rowCount();
                $insert_id = $connection->lastInsertId();
                $connection->commit();
                // return number of rows affected and id of insert
                return ['rows'=>$num_rows, 'id'=>$insert_id];
            }
        } catch (PDOException $e) {
            $connection->rollback();
            $errorMsg = date('m/d/Y h:i:s a', time()).': '.$e->_toString;
            error_log($errorMsg);
            die();
        }
    }
}

//test suite
//$foo = new MysqlHandler;
//$conditional = [];
//array_push($conditional, ['column'=>'office', 'operator'=>"=", "value"=>'top of the tallest mountains']);
//var_dump($foo->select("tpr_recipiant", ["name"], $conditional));
//var_dump($foo->insert("tpr_recipiant", ['name'=>'actions jackson', 'office'=>'badass in chief', 'address'=>"top of the tallest mountains"], "u"));
//var_dump($foo->update("tpr_recipiant", ['name'=>'erction jerkson', 'office'=>'badass in chief', 'address'=>"top of the tallest mountains"], $conditional));
