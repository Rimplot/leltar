<div class="alert alert-success alert-dismissible" id="success-alert">
    <button type="button" class="close">&times;</button>
    <strong>Siker!</strong>
    <?php switch ($type) {
        case 'box':
            echo "Doboz";
            break;
        case 'category':
            echo "Kategória";
            break;
        case 'item':
            echo "Eszköz";
            break;
        case 'label':
            echo "Címke";
            break;
        case 'owner':
            echo "Tulajdonos";
            break;
        case 'sector':
            echo "Szektor";
            break;
        case 'session':
            echo "Session";
            break;
        case 'storage':
            echo "Raktár";
            break;
        default:
            echo "Elem";
    } ?>
    sikeresen
    <?php switch ($action) {
        case 'created':
            echo "létrehozva";
            break;
        case 'modified':
            echo "módosítva";
            break;
        case 'deleted':
            echo "eltávolítva";
            break;
        case 'archived':
            echo "archiválva";
            break;
        case 'restored':
            echo "visszaállítva";
            break;
        case 'finished':
            echo "lezárva";
            break;
        case 'restarted':
            echo "újraindítva";
            break;
    } ?>.
</div>
