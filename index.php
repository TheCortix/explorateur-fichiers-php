<?php
declare(strict_types=1);

header('Content-Type: text/html; charset=UTF-8');

$baseDir = realpath(__DIR__);

if ($baseDir === false || !is_dir($baseDir)) {
    http_response_code(404);
    exit('Dossier introuvable');
}

$relativePath = $_GET['path'] ?? '';
$relativePath = str_replace('\\', '/', $relativePath);
$relativePath = ltrim($relativePath, '/');

$currentDir = realpath($baseDir . '/' . $relativePath);

if ($currentDir === false || !is_dir($currentDir)) {
    http_response_code(403);
    exit('Acces refuse');
}

if (
    $currentDir !== $baseDir &&
    strpos($currentDir, $baseDir . DIRECTORY_SEPARATOR) !== 0
) {
    http_response_code(403);
    exit('Acces refuse');
}

function makeUrl(string $path): string
{
    $parts = explode('/', $path);

    foreach ($parts as &$part) {
        $part = rawurlencode($part);
    }

    return implode('/', $parts);
}

$folders = [];
$files = [];

$items = scandir($currentDir);

if ($items === false) {
    exit('Impossible de lire le dossier');
}

foreach ($items as $item) {
    if ($item === '.' || $item === '..') {
        continue;
    }

    $fullPath = $currentDir . DIRECTORY_SEPARATOR . $item;

    $itemPath = $relativePath === ''
        ? $item
        : $relativePath . '/' . $item;

    if (is_dir($fullPath)) {
        $folders[] = [
            'name' => $item,
            'path' => $itemPath
        ];
    } else {
        $files[] = [
            'name' => $item,
            'path' => $itemPath
        ];
    }
}

usort($folders, function ($a, $b) {
    return strcasecmp($a['name'], $b['name']);
});

usort($files, function ($a, $b) {
    return strcasecmp($a['name'], $b['name']);
});

$parentPath = '';

if ($relativePath !== '') {
    $parts = explode('/', $relativePath);
    array_pop($parts);
    $parentPath = implode('/', $parts);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Explorateur de fichiers</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        a {
            color: #0645ad;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .type {
            display: inline-block;
            width: 80px;
            color: #555;
        }

        .dossier {
            font-weight: bold;
        }
    </style>
</head>

<body>

<h1>Explorateur de fichiers</h1>

<p>
    Dossier actuel :
    <strong>
        /hin<?= $relativePath !== ''
            ? '/' . htmlspecialchars($relativePath, ENT_QUOTES, 'UTF-8')
            : '' ?>
    </strong>
</p>

<?php if ($relativePath !== ''): ?>
    <p>
        <a href="?path=<?= urlencode($parentPath) ?>">
            Retour au dossier parent
        </a>
    </p>
<?php endif; ?>

<ul>

<?php foreach ($folders as $folder): ?>
    <li>
        <span class="type">Dossier :</span>

        <a class="dossier"
           href="?path=<?= urlencode($folder['path']) ?>">
            <?= htmlspecialchars(
                $folder['name'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </a>
    </li>
<?php endforeach; ?>

<?php foreach ($files as $file): ?>
    <li>
        <span class="type">Fichier :</span>

        <a href="<?= makeUrl($file['path']) ?>"
           target="_blank">
            <?= htmlspecialchars(
                $file['name'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </a>
    </li>
<?php endforeach; ?>

<?php if (empty($folders) && empty($files)): ?>
    <li>Ce dossier est vide.</li>
<?php endif; ?>

</ul>

</body>
</html>
