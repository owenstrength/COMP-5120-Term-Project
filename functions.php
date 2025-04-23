<?php
require_once 'db_config.php';

function executeSql($conn, $sql) {
    // Check for DROP statements and prevent execution
    if (stripos($sql, 'DROP') !== false) {
        return array('error' => 'DROP statements are not allowed for security reasons.', 
                'query' => $sql);
    }
    
    try {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        
        $result = mysqli_query($conn, $sql);
        
        // Reset error mode back to default after query execution
        mysqli_report(MYSQLI_REPORT_OFF);
        
        if (!$result) {
            return array(
                'error' => mysqli_error($conn),
                'errno' => mysqli_errno($conn),
                'query' => $sql
            );
        }
    } catch (mysqli_sql_exception $e) {
        // Reset error mode back to default
        mysqli_report(MYSQLI_REPORT_OFF);
        
        return array(
            'error' => $e->getMessage(),
            'errno' => $e->getCode(),
            'query' => $sql,
            'trace' => $e->getTraceAsString()
        );
    } catch (Exception $e) {
        // Reset error mode back to default
        mysqli_report(MYSQLI_REPORT_OFF);
        
        return array(
            'error' => 'Unexpected error: ' . $e->getMessage(),
            'errno' => $e->getCode(),
            'query' => $sql,
            'trace' => $e->getTraceAsString()
        );
    }
    if (stripos(trim($sql), 'SELECT') === 0) {
        $rows = array();
        $fields = array();
        
        if (mysqli_num_rows($result) > 0) {
            $fields = mysqli_fetch_fields($result);
        }
        
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        
        return array(
            'type' => 'select',
            'fields' => $fields,
            'rows' => $rows,
            'rowCount' => count($rows),
            'query' => $sql
        );
    } 
    else {
        $type = '';
        
        if (stripos(trim($sql), 'INSERT') === 0) {
            $type = 'insert';
            $message = 'Row Inserted';
        } elseif (stripos(trim($sql), 'UPDATE') === 0) {
            $type = 'update';
            $message = 'Table Updated';
        } elseif (stripos(trim($sql), 'DELETE') === 0) {
            $type = 'delete';
            $message = 'Row(s) Deleted';
        } elseif (stripos(trim($sql), 'CREATE') === 0) {
            $type = 'create';
            $message = 'Table Created';
        } elseif (stripos(trim($sql), 'ALTER') === 0) {
            $type = 'alter';
            $message = 'Table Altered';
        } else {
            $type = 'other';
            $message = 'Query Executed Successfully';
        }
        
        return array(
            'type' => $type,
            'message' => $message,
            'affectedRows' => mysqli_affected_rows($conn),
            'query' => $sql
        );
    }
}

// Process SQL query if submitted
$result = null;
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sql'])) {
    $sql = trim($_POST['sql']);
    if (!empty($sql)) {
        $sql = stripslashes($sql);
        $result = executeSql($conn, $sql);
    }
}
?>