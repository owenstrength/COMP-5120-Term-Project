<?php
require_once 'db_config.php';
require_once 'functions.php';

if (isset($_POST['sql'])) {
    $_POST['sql'] = stripslashes($_POST['sql']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMP 5120 Term Project</title>
    <!-- Fallback inline CSS in case the external file doesn't load -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .query-form {
            margin-bottom: 20px;
        }
        textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: monospace;
            font-size: 14px;
            resize: vertical;
        }
        .buttons {
            margin-top: 10px;
        }
        button {
            padding: 8px 15px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        button:hover {
            background: #45a049;
        }
        button[type="button"] {
            background: #f44336;
        }
        button[type="button"]:hover {
            background: #d32f2f;
        }
        .result {
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .error {
            color: #f44336;
            padding: 10px;
            background: #ffebee;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .success {
            color: #4CAF50;
            padding: 10px;
            background: #e8f5e9;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .row-count {
            margin-top: 10px;
            font-style: italic;
            color: #666;
        }
        .sql-query {
            font-family: monospace;
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            overflow-x: auto;
        }
        .error-details {
            margin-top: 5px;
            font-family: monospace;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>COMP 5120 Term Project</h1>
        <p>Created by Owen Strength | ods0005@auburn.edu</p>
        
        <div class="query-form">
            <h2>Query Tables</h2>
            <form method="post" action="">
                <textarea name="sql" placeholder="Enter your SQL query here..."><?php echo isset($_POST['sql']) ? htmlspecialchars($_POST['sql']) : ''; ?></textarea>
                <div class="buttons">
                    <button type="submit">Submit</button>
                    <button type="button" onclick="document.querySelector('textarea[name=sql]').value = '';">Clear</button>
                </div>
            </form>
        </div>
        
        <?php if ($result): ?>
            <div class="result">
                <?php if (isset($result['error'])): ?>
                    <div class="error">
                        <strong>Error:</strong> <?php echo htmlspecialchars($result['error']); ?>
                        <?php if (isset($result['query'])): ?>
                            <div class="error-details">
                                <strong>Query:</strong> <?php echo htmlspecialchars($result['query']); ?>
                                <?php if (isset($result['errno'])): ?>
                                    <br><strong>Error Code:</strong> <?php echo htmlspecialchars($result['errno']); ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php elseif ($result['type'] === 'select'): ?>
                    <div class="sql-query">
                        <strong>Executed:</strong> <?php echo htmlspecialchars($result['query']); ?>
                    </div>
                    <?php if (count($result['rows']) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <?php foreach ($result['fields'] as $field): ?>
                                        <th><?php echo htmlspecialchars($field->name); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($result['rows'] as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $value): ?>
                                            <td><?php echo $value === null ? '<em>NULL</em>' : htmlspecialchars((string)$value); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="row-count">
                            <strong>Rows retrieved:</strong> <?php echo $result['rowCount']; ?>
                        </div>
                    <?php else: ?>
                        <div class="success">Query executed successfully, but no rows were returned.</div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="sql-query">
                        <strong>Executed:</strong> <?php echo htmlspecialchars($result['query']); ?>
                    </div>
                    <div class="success">
                        <?php echo htmlspecialchars($result['message']); ?>
                        <?php if ($result['affectedRows'] > 0): ?>
                            (<?php echo $result['affectedRows']; ?> row<?php echo $result['affectedRows'] > 1 ? 's' : ''; ?> affected)
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>