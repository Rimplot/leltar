<h2><?php echo $page_title; ?></h2>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Azonosító</th>
            <th scope="col">Név</th>
            <th scope="col">Vonalkód</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row"><?php echo $sector['id']; ?></th>
            <td><?php echo $sector['name']; ?></td>
            <td>
                <img src="<?php echo base_url() . 'barcodes/generate/' . $sector['barcode']; ?>" style="display:block; height:15%">
                <a href="<?php echo base_url() . 'barcodes/generate/' . $sector['barcode']; ?>"><?php echo $sector['barcode']; ?></a>
            </td>
            <td class="float-right">
                <button type="button" class="btn btn-success" id="btnPrint">Nyomtatás</button>
                <a class="btn btn-primary" href="<?php echo base_url() . 'sectors/edit/' . $sector['id']; ?>">Módosítás</a>
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#archiveModal">Archiválás</button>
            </td>
        </tr>
    </tbody>
</table>

<h4>Eszközök, amelyek utoljára itt voltak leltárazva</h4>
<?php if (/* !is_null($inventory_history) && */ count($items)) : ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Azonosító</th>
                <th scope="col">Név</th>
                <th scope="col">Kategória</th>
                <th scope="col">Utoljára leltárazva</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item) : ?>
                <tr>
                    <th scope="row"><?php echo $item['id']; ?></th>
                    <td><a href="<?php echo base_url() . 'items/' . $item['id']; ?>"><?php echo $item['name']; ?></a></td>
                    <td><a href="<?php echo base_url() . 'categories/' . $item['category_id']; ?>"><?php echo $item['category']; ?></td>
                    <td><?php echo $item['time']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p>Nincs egy eszköz sem, amit ebben a raktárban leltároztunk utoljára.</p>
<?php endif; ?>

<!-- Delete Modal -->
<div class="modal fade" id="archiveModal" tabindex="-1" role="dialog" aria-labelledby="archiveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveModalLabel">Szektor archiválása</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                A szektor archiválásával elrejted azt a leltárazáskor kiválaszható helyek közül, de az itt készített ellenőrzések eredményei továbbra is megtekinthetőek maradnak. A művelet később is viszavonható. Biztosan folytatni szeretnéd?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Mégsem</button>
                <a href="<?php echo base_url() . 'sectors/archive/'. $sector['storage_id'] . '/' . $sector['id']; ?>" class="btn btn-warning">Archiválás</a>
            </div>
        </div>
    </div>
</div>

<?php

function labelBuilder($label, $args) {
    if ($label == NULL) {
        if (strlen($args['{{name}}']) > 20) {
            $name_label = "^FO160,15^FB200,2,0,C,0^A0N,24,24^FD{{name}}^FS";
            $storage_label = "^FO160,65^FB200,1,0,C,0^A0N,24,24^FD{{storage}}, {{sector}}^FS";
            $barcode_label = "^FS ^BY3,0,35 ^FO160,85^B8^FD{{barcode}}^FS";
        } else {
            $name_label = "^FO160,28^FB200,3,0,C,0^A0N,24,24^FD{{name}}^FS";
            $storage_label = "^FO160,52^FB200,3,0,C,0^A0N,24,24^FD{{storage}}, {{sector}}^FS";
            $barcode_label = "^FS ^BY3,0,35 ^FO160,85^B8^FD{{barcode}}^FS";
        }

        $logo = "^FO30,15^GFA,2025,2025,15,,:::::::R01FFE,R0JFC,Q03KF,Q07KFC,Q0LFE,P03MF,P07FF807FF8,P07FCI0FFC,P0FF8I03FE,O01FEJ01FE,O01FCK0FF,O03F8K07F,O03F8K03F8,O07FL03F8,O07FL01FC,O0FEL01FC,O0FEM0FC,O0FCM0FC,O0FCM0FE,:00IFJ0FCM0FEI03FFC,00IF8I0FCM0FEI03FFC,::00IF8I0FCM0FCI03FFC,00IF8I0FEM0FCI07FFC,00IF8I0FEL01FCI07FFC,007FF8I07EL01FCI07FFC,007FF8I07FL03F8I07FFC,007FFCI07F8K03F8I07FFC,007FFCI03F8K07F8I07FF8,007FFCI03FCK0FFJ0IF8,003FFEI01FEJ01FEJ0IF8,003FFEJ0FF8I03FEJ0IF8,003FFEJ07FCI0FFCI01IF,003IFJ07FF803FF8I01IF,001IFJ03MFJ03IF,001IF8I01LFEJ03FFE,001IF8J07KFCJ07FFE,I0IFCJ03KFK07FFE,I0IFCK0JFCK0IFC,I07FFEK01IFK01IFC,I07IFT01IF8,I03IFT03IF8,I03IF8S07IF,I01IFCS0JF,J0IFER01IFE,J0JFR03IFC,J07IF8Q07IFC,J03IFCQ0JF8,J03JFP01JF,J01JF8O07IFE,K0JFEO0JFC,K07JF8M03JFC,K03JFEM0KF8,K01KF8K07KF,L0LF8I03KFE,L07UF8,L03UF,M0TFE,M07SFC,M03SF,N0RFC,N03QF8,O0PFE,O03OF8,P0NFC,P01LFE,P03MF8,P0NFC,O03OF,O07OF8,O0PFE,N01QF,N07QF8,N0KF807JFC,N0JF8I07IFE,M01IFEK0JF,M03IF8K07IF,M07IFL01IF8,M07FFCM0IFC,M0IF8M07FFC,L01IFN03FFE,L01FFEN01IF,L03FFCO0IF,L03FFCO07FF,L03FF8O07FF8,L07FF8O03FF8,L07FFP03FFC,L07FFP01FFC,L0FFEP01FFC,L0FFEJ07F8J0FFC,L0FFEI01FFEJ0FFE,L0FFCI03IF8I0FFE,L0FFCI0FE0FCI0FFE,L0FFCI0F803EI0FFE,K01FFC001E001FI07FE,K01FFC003CI0FI07FE,L0FF8003CI078007FE,Q038I078,Q078I038,Q07J038,Q07J03C,L0EJ07J03CI01E,:L0FJ07J03CI01C,L0FJ0FJ01CI01C,L07J0FJ01EI03C,L078001EJ01EI078,L03C003CK0FI0F8,L03E007CK07C01F,L01F81F8K03F07E,M0JFL01IFC,M03FFCM0IF8,N0FFN03FE,,:::::::::::";

        $label = "^XA ^LH40,0" . $logo . $name_label . $storage_label . $barcode_label . "^XZ";
    }

    foreach ($args as $key => $value) {
        $label = str_replace($key, $value, $label);
    }

    $label = remove_accents($label);

    return json_encode($label);
}

$args = array(
    "{{id}}" => $sector['id'],
    "{{name}}" => $sector['name'],
    "{{barcode}}" => $sector['barcode']
);

?>

<script>
    $(document).ready(function() {
        $('#btnPrint').click(function() {
            launchQZ();
            if (qz.websocket.isActive()) {
                var config = qz.configs.create("Godex G300");
                var data = [<?php echo labelBuilder($item["label"], $args); ?>];

                qz.print(config, data).catch(function(e) {
                    console.error(e);
                });
            };
            

            /*qz.websocket.connect().then(function() {
                return qz.printers.find("godex");              // Pass the printer name into the next Promise
            }).then(function(printer) {
                var config = qz.configs.create(printer);       // Create a default config for the found printer
                //var data = ['^XA ^FO160,18^FB200,3,0,C,0^A0N,24,24^FD' + name + '^FS ^FO10,10^GFA,2025,2025,15,,:::::::R01FFE,R0JFC,Q03KF,Q07KFC,Q0LFE,P03MF,P07FF807FF8,P07FCI0FFC,P0FF8I03FE,O01FEJ01FE,O01FCK0FF,O03F8K07F,O03F8K03F8,O07FL03F8,O07FL01FC,O0FEL01FC,O0FEM0FC,O0FCM0FC,O0FCM0FE,:00IFJ0FCM0FEI03FFC,00IF8I0FCM0FEI03FFC,::00IF8I0FCM0FCI03FFC,00IF8I0FEM0FCI07FFC,00IF8I0FEL01FCI07FFC,007FF8I07EL01FCI07FFC,007FF8I07FL03F8I07FFC,007FFCI07F8K03F8I07FFC,007FFCI03F8K07F8I07FF8,007FFCI03FCK0FFJ0IF8,003FFEI01FEJ01FEJ0IF8,003FFEJ0FF8I03FEJ0IF8,003FFEJ07FCI0FFCI01IF,003IFJ07FF803FF8I01IF,001IFJ03MFJ03IF,001IF8I01LFEJ03FFE,001IF8J07KFCJ07FFE,I0IFCJ03KFK07FFE,I0IFCK0JFCK0IFC,I07FFEK01IFK01IFC,I07IFT01IF8,I03IFT03IF8,I03IF8S07IF,I01IFCS0JF,J0IFER01IFE,J0JFR03IFC,J07IF8Q07IFC,J03IFCQ0JF8,J03JFP01JF,J01JF8O07IFE,K0JFEO0JFC,K07JF8M03JFC,K03JFEM0KF8,K01KF8K07KF,L0LF8I03KFE,L07UF8,L03UF,M0TFE,M07SFC,M03SF,N0RFC,N03QF8,O0PFE,O03OF8,P0NFC,P01LFE,P03MF8,P0NFC,O03OF,O07OF8,O0PFE,N01QF,N07QF8,N0KF807JFC,N0JF8I07IFE,M01IFEK0JF,M03IF8K07IF,M07IFL01IF8,M07FFCM0IFC,M0IF8M07FFC,L01IFN03FFE,L01FFEN01IF,L03FFCO0IF,L03FFCO07FF,L03FF8O07FF8,L07FF8O03FF8,L07FFP03FFC,L07FFP01FFC,L0FFEP01FFC,L0FFEJ07F8J0FFC,L0FFEI01FFEJ0FFE,L0FFCI03IF8I0FFE,L0FFCI0FE0FCI0FFE,L0FFCI0F803EI0FFE,K01FFC001E001FI07FE,K01FFC003CI0FI07FE,L0FF8003CI078007FE,Q038I078,Q078I038,Q07J038,Q07J03C,L0EJ07J03CI01E,:L0FJ07J03CI01C,L0FJ0FJ01CI01C,L07J0FJ01EI03C,L078001EJ01EI078,L03C003CK0FI0F8,L03E007CK07C01F,L01F81F8K03F07E,M0JFL01IFC,M03FFCM0IF8,N0FFN03FE,,:::::::::::^FS ^BY3,0,35 ^FO150,85^B8^FD' + barcode + '^FS ^XZ'];   // Raw ZPL
                
                alert(data);
                return qz.print(config, data);
            }).catch(function(e) { console.error(e); });*/
        });


        /// Connection ///
        function launchQZ() {
            if (!qz.websocket.isActive()) {
                window.location.assign("qz:launch");
                //Retry 5 times, pausing 1 second between each attempt
                startConnection({ retries: 5, delay: 1 });
            }
        }

        function startConnection(config) {
            if (!qz.websocket.isActive()) {
                updateState('Waiting', 'default');

                qz.websocket.connect(config).then(function() {
                    updateState('Active', 'success');
                    findVersion();
                }).catch(handleConnectionError);
            } else {
                displayMessage('An active connection with QZ already exists.', 'alert-warning');
            }
        }

        function updateState(text, css) {
            $("#qz-status").html(text);
            $("#qz-connection").removeClass().addClass('panel panel-' + css);

            if (text === "Inactive" || text === "Error") {
                $("#launch").show();
            } else {
                $("#launch").hide();
            }
        }

        function handleConnectionError(err) {
            updateState('Error', 'danger');

            if (err.target != undefined) {
                if (err.target.readyState >= 2) { //if CLOSING or CLOSED
                    displayError("Connection to QZ Tray was closed");
                } else {
                    displayError("A connection error occurred, check log for details");
                    console.error(err);
                }
            } else {
                displayError(err);
            }
        }

        function print() {
            var config = qz.configs.create("Godex G300");

            var data = [
                'Data\n',
                'Should be simple data\n',
                'To be printed\n'
            ];

            qz.print(config, data).catch(function(e) {
                console.error(e);
            });
        }
    });
</script>
