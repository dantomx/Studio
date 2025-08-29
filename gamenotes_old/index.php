<?php
// Connessione DB (adatta i parametri)
$pdo = new PDO("mysql:host=localhost;dbname=vljoljzm_gamenotes;charset=utf8mb4", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Parametri GET
$perPage = isset($_GET['perPage']) ? (int)$_GET['perPage'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

$filterName = $_GET['nome'] ?? '';
$filterDate = $_GET['reg_data'] ?? '';

// Query base
$sql = "
    SELECT a.uid, a.nome, a.cognome, a.reg_data, a.citta, a.email,
           l.idLezione, l.startDate, l.comment, l.lessonType
    FROM allievi a
    LEFT JOIN lezioni_old l ON a.uid = l.uid
    WHERE 1=1
";

// Filtri
$params = [];
if ($filterName !== '') {
    $sql .= " AND (a.nome LIKE :nome OR a.cognome LIKE :nome) ";
    $params[':nome'] = "%$filterName%";
}
if ($filterDate !== '') {
    $sql .= " AND DATE(a.reg_data) = :reg_data ";
    $params[':reg_data'] = $filterDate;
}

$sql .= " ORDER BY a.reg_data ASC LIMIT :offset,:perPage";
$stmt = $pdo->prepare($sql);
foreach ($params as $k => $v) $stmt->bindValue($k, $v);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Totale per paginazione
$total = $pdo->query("SELECT COUNT(*) FROM allievi")->fetchColumn();
$totalPages = ceil($total / $perPage);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Vista incrociata allievi/lezioni</title>
    <style>
        body { font-family: Arial, sans-serif; display:flex; margin:0; }
        .sidebar { width:200px; background:#f0f0f0; padding:10px; }
        .content { flex:1; padding:20px; }
        table { border-collapse: collapse; width:100%; }
        td,th { border:1px solid #ccc; padding:5px; font-size:14px; }
        a { text-decoration:none; color:#0066cc; }
    </style>
</head>
<body>
<div class="sidebar">
    <h3>Query utili</h3>
    <ul>
        <li><a href="?">Tutti gli allievi</a></li>
        <li><a href="?perPage=10&nome=">Elenco 10 per pagina</a></li>
        <li><a href="?reg_data=2025-01-01">Registrati dal 01/01/2025</a></li>
        <li><a href="?nome=Rossi">Cerca Rossi</a></li>
        <li><a href="?perPage=50">Vista 50 record</a></li>
    </ul>
    <hr>
    <form method="get">
        <div><input type="text" name="nome" placeholder="Filtra nome/cognome"></div>
        <div><input type="date" name="reg_data" value="<?=htmlspecialchars($filterDate)?>"></div>
        <div>
            <select name="perPage">
                <?php foreach([5,10,20,50] as $n): ?>
                    <option value="<?=$n?>" <?=($perPage==$n)?'selected':''?>><?=$n?> per pagina</option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit">Filtra</button>
    </form>
</div>
<div class="content">
    <h2>Vista incrociata allievi/lezioni</h2>
    <table>
        <tr>
            <th>Nome</th><th>Cognome</th><th>Reg_data</th><th>Città</th><th>Email</th>
            <th>Id Lezione</th><th>Data Lezione</th><th>Tipo</th><th>Commento</th>
        </tr>
        <?php foreach($rows as $r): ?>
            <tr>
                <td><?=$r['nome']?></td>
                <td><?=$r['cognome']?></td>
                <td><?=$r['reg_data']?></td>
                <td><?=$r['citta']?></td>
                <td><?=$r['email']?></td>
                <td><?=$r['idLezione']?></td>
                <td><?=$r['startDate']?></td>
                <td><?=$r['lessonType']?></td>
                <td><?=substr(strip_tags($r['comment']),0,50)?>...</td>
            </tr>
        <?php endforeach; ?>
    </table>
    <div>
        <?php for($i=1;$i<=$totalPages;$i++): ?>
            <a href="?page=<?=$i?>&perPage=<?=$perPage?>"><?=$i?></a>
        <?php endfor; ?>
    </div>
</div>
</body>
</html>

