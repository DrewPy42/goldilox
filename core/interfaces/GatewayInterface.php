<?php
/**
 * Abstract class for building gateways easier
 *
 * @todo insertMany? updateMany? deleteMany?
 * @todo maybe a getRequiredFieldNames abstract method? would certainly help with insertion validation and maybe some
 *       update queries
 */
abstract class GatewayInterface {

    /**
     * @var PDO contains the db connection to be used
     */
    protected PDO $db;

    /**
     * @param PDO $db must pass the db connection to be used
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * @return string must return table name for query purposes
     */
    abstract protected function getTableName(): string;

    /**
     * @return string must return primary key name for query purposes
     */
    abstract protected function getPrimaryKeyName(): string;

    /**
     * @return string must return all the fields for the table for query purposes e.g. "field1,field2"
     */
    abstract protected function getFieldNames(): string;

    /**
     * @return string must return all the editable fields in the table for query purposes e.g. "notPrimaryKey,field2"
     */
    abstract protected function getEditableFieldNames(): string;

    /**
     * For raw sql use this, be careful with it though obviously
     *
     * @param string $query raw sql go here
     * @param array  $binds will bind these values to your sql
     *
     * @return PDOStatement
     */
    public function sql(string $query, array $binds = []): PDOStatement {
        $q = $this->db->prepare($query);
        $q->execute($binds);
        return $q;
    }

    /**
     * Gets object from db based on primary key
     *
     * @param string      $id     the primary key value
     * @param string|null $fields optional fields to get for query, will default to $this->getFieldNames()
     * @param string|null $joins  will use these joins if provided
     *
     * @return array|null either a result set (array of results) or probably null on error
     */
    public function get(string $id, string $fields = NULL, string $joins = NULL): ?array {
        $t = $this->getTableName();
        $pk = $this->getPrimaryKeyName();
        $f = $fields ?? $this->getFieldNames();
        $q = $this->db->prepare("SELECT $f FROM $t $joins WHERE $pk = :$pk");
        $q->bindParam(":$pk", $id);
        $q->execute();
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets objects from db based on set of primary keys
     *
     * @param array       $ids    set of primary key values
     * @param string|null $fields optional fields to get for query, will default to $this->getFieldNames()
     * @param string|null $joins  will use these joins if provided
     *
     * @return array|null either a result set (array of results) or probably null on error
     */
    public function getMany(array $ids, string $fields = NULL, string $joins = NULL): ?array {
        $t = $this->getTableName();
        $pk = $this->getPrimaryKeyName();
        $f = $fields ?? $this->getFieldNames();
        $in = implode(",", array_fill(0, count($ids), "?"));
        $q = $this->db->prepare("SELECT $f FROM $t $joins WHERE $pk IN ($in)");
        $q->execute($ids);
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets all objects from db table, really should only be used for small tables
     *
     * @param string|null $fields optional fields to get for query, will default to $this->getFieldNames()
     * @param string|null $joins  will use these joins if provided
     *
     * @return array|null either a result set (array of results) or probably null on error
     */
    public function getAll(string $fields = NULL, string $joins = NULL): ?array {
        $t = $this->getTableName();
        $f = $fields ?? $this->getFieldNames();
        $q = $this->db->prepare("SELECT $f FROM $t $joins");
        $q->execute();
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inserts an object into the db
     *
     * @param array $obj
     *
     * @return string
     */
    public function insert(array $obj): string {
        $q = $this->db->prepare($this->buildInsertQuery($obj));
        $q = $this->bindParams($q, $obj);
        $q->execute();
        return $this->db->lastInsertId();
    }

    /**
     * Updates an object in the db
     *
     * @param string $id
     * @param array  $obj
     *
     * @return void
     */
    public function update(string $id, array $obj): void {
        $pk = $this->getPrimaryKeyName();
        $q = $this->db->prepare($this->buildUpdateQuery($obj, "WHERE $pk = :$pk"));
        $q->bindParam(":$pk", $id);
        $q = $this->bindParams($q, $obj);
        $q->execute();
    }

    /**
     * Deletes an object from db
     *
     * @param string $id
     *
     * @return void
     */
    public function delete(string $id): void {
        $t = $this->getTableName();
        $pk = $this->getPrimaryKeyName();
        $q = $this->db->prepare("DELETE FROM $t WHERE $pk = :$pk");
        $q->bindParam(":$pk", $id);
        $q->execute();
    }

    /**
     * Builds an insert query based on what fields are editable in the db and what fields are in given object
     *
     * @param array $obj the object with values to use for insert
     *
     * @return string the built query
     *
     * @todo what do with discarded values not matched? not throw error, but just log them out maybe?
     */
    protected function buildInsertQuery(array $obj): string {
        $t = $this->getTableName();
        $ef = $this->getEditableFieldNames();
        $usedFieldNamesArr = array_filter(
            explode(",", $ef),
            function($field) use ($obj) { return key_exists($field, $obj); }
        );
        $usedFieldNames = implode(",", $usedFieldNamesArr);
        $values = implode(",", array_map(
            function($field) { return ":$field"; },
            $usedFieldNamesArr
        ));
        return "INSERT INTO $t ($usedFieldNames) VALUES ($values)";
    }

    /**
     * Builds an update query based on what fields are editable in the db and what fields are in given object
     *
     * @param array  $obj         the object with values to use for update
     * @param string $whereClause a var to specify the where clause on update e.g. "WHERE id=:id"
     *
     * @return string the built query
     *
     * @todo what do with discarded values not matched? not throw error, but just log them out maybe?
     */
    protected function buildUpdateQuery(array $obj, string $whereClause): string {
        $t = $this->getTableName();
        $ef = $this->getEditableFieldNames();
        $values = implode(",", array_map(
            function($field) { return $field . "=:" . $field; },
            array_filter(
                explode(",", $ef),
                function($field) use ($obj) { return key_exists($field, $obj); }
            )
        ));
        return "UPDATE $t SET $values $whereClause";
    }

    /**
     * Binds editable params from the obj to the prepared statement for updates and
     * inserts
     *
     * @param PDOStatement $q
     * @param array        $obj
     *
     * @return PDOStatement
     *
     * @todo I think this can pretty much be deleted, I think there is built in way in ->execute() function
     */
    protected function bindParams(PDOStatement $q, array $obj): PDOStatement {
        $ef = $this->getEditableFieldNames();
        $editableFieldArr = explode(",", $ef);
        array_walk(
            $editableFieldArr,
            function($field) use ($q, $obj) {
                if(key_exists($field, $obj))
                    $q->bindParam(":$field", $obj[$field]);
            }
        );
        return $q;
    }
}
