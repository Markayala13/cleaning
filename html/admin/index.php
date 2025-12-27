<?php
require_once 'config.php';
requireLogin();

// Configuración
$filesDir = __DIR__ . '/files/';
if (!is_dir($filesDir)) {
    mkdir($filesDir, 0755, true);
}

// Obtener lista de archivos
$files = [];
if (is_dir($filesDir)) {
    $items = scandir($filesDir);
    foreach ($items as $item) {
        if ($item !== '.' && $item !== '..' && $item !== '.htaccess') {
            $filePath = $filesDir . $item;
            if (is_file($filePath)) {
                $files[] = [
                    'name' => $item,
                    'size' => filesize($filePath),
                    'date' => filemtime($filePath),
                    'type' => pathinfo($item, PATHINFO_EXTENSION)
                ];
            }
        }
    }
}

// Función para formatear tamaño
function formatBytes($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
        }

        .header {
            background: #001892;
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info span {
            font-size: 14px;
            opacity: 0.9;
        }

        .btn-logout {
            background: #FFD700;
            color: #001892;
            padding: 8px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background: #fff;
            transform: translateY(-2px);
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .stat-card .icon {
            width: 50px;
            height: 50px;
            background: #001892;
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-card h3 {
            color: #666;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .stat-card .value {
            color: #001892;
            font-size: 28px;
            font-weight: 700;
        }

        .files-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .section-header {
            padding: 25px 30px;
            border-bottom: 1px solid #e0e0e0;
        }

        .section-header h2 {
            color: #333;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .files-table {
            width: 100%;
            border-collapse: collapse;
        }

        .files-table thead {
            background: #f9f9f9;
        }

        .files-table th {
            padding: 15px 30px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .files-table td {
            padding: 18px 30px;
            border-top: 1px solid #f0f0f0;
        }

        .files-table tbody tr {
            transition: all 0.2s;
        }

        .files-table tbody tr:hover {
            background: #f9f9f9;
        }

        .file-name {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #333;
            font-weight: 500;
        }

        .file-icon {
            width: 40px;
            height: 40px;
            background: #001892;
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .file-icon.pdf { background: #e74c3c; }
        .file-icon.doc, .file-icon.docx { background: #3498db; }
        .file-icon.xls, .file-icon.xlsx { background: #27ae60; }
        .file-icon.jpg, .file-icon.jpeg, .file-icon.png { background: #9b59b6; }
        .file-icon.zip, .file-icon.rar { background: #f39c12; }

        .file-size {
            color: #999;
            font-size: 14px;
        }

        .file-date {
            color: #666;
            font-size: 14px;
        }

        .btn-download {
            background: #001892;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-download:hover {
            background: #0025d4;
            transform: translateY(-2px);
        }

        .empty-state {
            padding: 60px 30px;
            text-align: center;
            color: #999;
        }

        .empty-state i {
            font-size: 64px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-state p {
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .files-table {
                font-size: 13px;
            }

            .files-table th,
            .files-table td {
                padding: 12px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-folder-open"></i> File Manager</h1>
        <div class="user-info">
            <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['admin_user']); ?></span>
            <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="stats">
            <div class="stat-card">
                <div class="icon">
                    <i class="fas fa-file"></i>
                </div>
                <h3>Total Files</h3>
                <div class="value"><?php echo count($files); ?></div>
            </div>

            <div class="stat-card">
                <div class="icon">
                    <i class="fas fa-database"></i>
                </div>
                <h3>Total Size</h3>
                <div class="value">
                    <?php
                    $totalSize = array_sum(array_column($files, 'size'));
                    echo formatBytes($totalSize);
                    ?>
                </div>
            </div>

            <div class="stat-card">
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Last Updated</h3>
                <div class="value" style="font-size: 18px;">
                    <?php
                    if (!empty($files)) {
                        $latestDate = max(array_column($files, 'date'));
                        echo date('M d, Y', $latestDate);
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="files-section">
            <div class="section-header">
                <h2><i class="fas fa-folder"></i> Your Files</h2>
            </div>

            <?php if (empty($files)): ?>
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>No files available yet</p>
                </div>
            <?php else: ?>
                <table class="files-table">
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>Size</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($files as $file): ?>
                            <tr>
                                <td>
                                    <div class="file-name">
                                        <div class="file-icon <?php echo strtolower($file['type']); ?>">
                                            <i class="fas fa-file"></i>
                                        </div>
                                        <?php echo htmlspecialchars($file['name']); ?>
                                    </div>
                                </td>
                                <td class="file-size"><?php echo formatBytes($file['size']); ?></td>
                                <td class="file-date"><?php echo date('M d, Y H:i', $file['date']); ?></td>
                                <td>
                                    <a href="download.php?file=<?php echo urlencode($file['name']); ?>" class="btn-download">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
