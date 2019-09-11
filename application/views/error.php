<div class="alert alert-danger alert-dismissible" id="danger-alert">
    <button type="button" class="close">&times;</button>
    <strong>Hiba!</strong>
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
    <?php switch ($error) {
        case 'created':
            echo "létrehozása";
            break;
        case 'modified':
            echo "módosítása";
            break;
        case 'deleted':
            echo "eltávolítása";
            break;
        case 'archived':
            echo "archiválása";
            break;
        case 'restored':
            echo "visszaállítása";
            break;
        case 'finished':
            echo "lezárása";
            break;
        case 'restarted':
            echo "újraindítása";
            break;
    } ?> sikertelen.
</div>
