<?php

require  'phpchat/Data.php';
$data = new Data();
$result['labels'] = 0;
$result['data'] = 0;
$result['colors'] = 0;
if (isset($_GET['data'])) {
    if ($_GET['data'] == 'emoji') {
        $result = $data->getMostEmojiUsed();
    } else {
        $param = $_GET['data'];
        $result = $data->getMost($param);
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js">
    <title>Preview</title>
</head>

<body>

    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <h6 class="card-header">User Paling Aktif</h6>
                        <div class="card-body">
                            <a href="?data=contact" class="btn btn-primary btn-sm">Check Data</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <h6 class="card-header">Emoji Paling Digunakan</h6>
                        <div class="card-body">
                            <a href="?data=emoji" class="btn btn-primary btn-sm">Check Data</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <h6 class="card-header">Tanggal Paling Aktif</h6>
                        <div class="card-body">
                            <a href="?data=date" class="btn btn-primary btn-sm">Check Data</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <h6 class="card-header">Waktu Paling Aktif</h6>
                        <div class="card-body">
                            <a href="?data=time" class="btn btn-primary btn-sm">Check Data</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <h6 class="card-header">Grafik <a href="index.php" class="btn btn-sm btn-warning float-right">Reset</a></h6>
                        <div class="card-body">
                            <canvas id="datagrafik"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
    <script>
        var config = {
            type: "<?php echo $result['type']; ?>",
            data: {
                datasets: [{
                    data: [
                        <?php echo $result['data']; ?>
                    ],
                    backgroundColor: [
                        "<?php echo $result['colors']; ?>"
                    ],
                    label: 'Graph'
                }],
                labels: [
                    "<?php echo $result['labels']; ?>"
                ],
            },
            options: {
                responsive: true,
                legend: {
                    display: true,
                }
            }
        }

        $(function() {
            var canvas = $('#datagrafik');
            new Chart(canvas, config);
        })
    </script>
</body>

</html>