<?php 
    require_once("../config/connection.php");
    global $con;
    
    // Check if the form has been submitted
    if(isset($_GET['barcode'])) {
        $barcode = $_GET['barcode']; // Get the barcode entered by the user
        $query = "SELECT * FROM products WHERE barcode = '$barcode' LIMIT 1"; // add LIMIT 1 to the query
        $result = mysqli_query($con,$query);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Records</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <div class="row p-2">
            <div class="col-md-6 offset-md-3">
                <div class="card mt-5">
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="form-group">
                                <label for="barcode">Enter Barcode:</label>
                                <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Enter Barcode" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                        <div class="form-group">
                            <video id="video" width="100%" height="auto"></video>
                            <button id="startButton">Start</button>
                        </div>
                    </div>
                    <?php if(isset($result)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Detail</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Barcode</th>
                                        <th>Image</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($product = mysqli_fetch_assoc($result)) : ?>
                                        <tr>
                                            <td><?php echo $product['id']; ?></td>
                                            <td><?php echo $product['name']; ?></td>
                                            <td><?php echo $product['detail']; ?></td>
                                            <td><?php echo $product['price']; ?></td>
                                            <td><?php echo $product['quantity']; ?></td>
                                            <td><?php echo $product['barcode']; ?></td>
                                            <td><img src="<?php echo asset('image/'.$product['image']); ?>" alt="product image" style="max-height: 100px"></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/quagga/dist/quagga.min.js"></script>

    <script>
        const video = document.getElementById('video');
        const startButton = document.getElementById('startButton');
        const barcodeInput = document.getElementById('barcode');
    
        // Access the user's webcam and stream the video to the <video> element
        function startVideo() {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    video.srcObject = stream;
                    video.play();
                })
                .catch(function(error) {
                    console.error(error);
                });
            }
        }
    
        // Stop the video stream
        function stopVideo() {
            const stream = video.srcObject;
            const tracks = stream.getTracks();
    
            tracks.forEach(function(track) {
                track.stop();
            });
    
            video.srcObject = null;
        }
    
        // Use QuaggaJS library to read the barcode and update the input field
        Quagga.init({
            inputStream: {
                constraints: {
                    width: 640,
                    height: 480,
                    facingMode: "environment" // use rear-facing camera on mobile devices
                },
                target: video
            },
            decoder: {
                readers: ["ean_reader"] // use EAN barcode reader
            }   
        }, function(err) {
            if (err) {
                console.error(err);
                return;
            }
    
            Quagga.start();
    
            // When QuaggaJS detects a barcode, update the input field and stop the video stream
            Quagga.onDetected(function(result) {
                barcodeInput.value = result.codeResult.code;
                stopVideo();
            });
        });
    
        // Start the video stream when the Start button is clicked
        startButton.addEventListener('click', function() {
            startVideo();
        });
    </script>
</body>
</html>