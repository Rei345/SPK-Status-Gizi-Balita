<?php
    include 'koneksi.php'; 
?>
<!DOCTYPE html>
<?php
    session_start();
    if(!isset($_SESSION['email'])){
      header('Location: index.php');
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stylec.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome Cdn link -->
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Data Tables -->
    <link rel="stylesheet" href="datatables/datatables.css">
    <script src="datatables/datatables.js"></script>

    <title>Laporan</title>
</head>

<script>
    $(document).ready(function() {
        $('#dt').DataTable({
            columnDefs: [{ 
                targets: '_all', 
                className: 'dt-body-left' 
            }],
            layout: {
                topStart: 'buttons'
            },
            buttons: [
                {
                    extend: 'csvHtml5',
                    title: 'Daftar Data Balita' // Judul untuk ekspor CSV
                },
                {
                    extend: 'excelHtml5',
                    title: 'Daftar Data Balita' // Judul untuk ekspor Excel
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Daftar Data Balita', // Judul untuk PDF
                    filename: 'daftar_data_balita',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    title: 'Daftar Data Balita' // Judul untuk print
                }
            ]
        });
    });
</script>


<body>

    <div class="sidebar">
        <div class="logo"></div>
        <ul class="menu">
            <li>
                <a href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="kelola.php">
                    <i class="fas fa-database"></i>
                    <span>Kelola</span>
                </a>
            </li>
            <li class="active">
                <a href="laporan.php">
                    <i class="fas fa-file-alt"></i>
                    <span>Laporan</span>
                </a>
            </li>
            <li class="logout">
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <span>Menu</span>
                <h2>Laporan</h2>
            </div>
            <div class="user--info">
                <span class="badge mb-1 rounded-pill text-bg-primary">selamat datang</span>
                <h2 class="fw-bolder fs-6 text-body-emphasis"><?php echo $_SESSION['email'];?></h2>
                <img src="profile.jpg" alt="">
            </div>
        </div>
        <div class="tabular--wrapper">
            <h3 class="main--title">Data Balita</h3>
            <form action="" class="row" method="post">
                        
                        <input type="date" name="tgl_mulai" class="form-control col ">
                        
                        <input type="date" name="tgl_selesai" class="form-control col ms-3">
                        <button type="submit" name="filter_tgl" class="btn btn-primary col-md-2 offset-md-1">Filter</button>
                    </form>
            <div class="table-container">
                <table id="dt" class="display mt-3" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th style="text-align:left;">NIK</th>
                            <th>Nama</th>
                            <th style="text-align:left;">Berat Badan</th>
                            <th style="text-align:left;">Tinggi Badan</th>
                            <th>Jenis Kelamin</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($_POST['filter_tgl'])){
                            $mulai = $_POST['tgl_mulai']; 
                            $selesai = $_POST['tgl_selesai'];
                            
                            if($mulai!=null || $selesai!=null){
                                $hasil = mysqli_query($kon, "SELECT * FROM peserta WHERE jadwal BETWEEN '".$mulai."' and '".$selesai."' ORDER BY id_peserta ASC");
                            } else {
                                $hasil = mysqli_query($kon, "SELECT * FROM peserta ORDER BY id_peserta ASC");
                            }
                        } else {
                            $hasil = mysqli_query($kon, "SELECT * FROM peserta ORDER BY id_peserta ASC");
                        }
                        
                        
                        $no = 0;
                        while ($data = mysqli_fetch_array($hasil)) {
                            $no++;
                        ?>
                        <tr>
                            <td><?php echo $no;?></td>
                            <td><?php echo htmlspecialchars($data["nik"]); ?></td>
                            <td><?php echo htmlspecialchars($data["nama"]); ?></td>
                            <td><?php echo htmlspecialchars($data["berat_badan"]); ?></td>
                            <td><?php echo htmlspecialchars($data["tinggi_badan"]); ?></td>
                            <td><?php echo htmlspecialchars($data["gender"]); ?></td>
                            <td><?php echo htmlspecialchars($data["status"]); ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script> 
            document.querySelector(".logout").addEventListener("click", (event) => {
                event.preventDefault(); // Mencegah aksi default dari elemen <a>
                Swal.fire({
                    title: "Apakah kamu yakin?",
                    text: "Kamu akan keluar dari halaman ini",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = event.target.closest('a').href; // Redirect setelah konfirmasi
                    }
                });
            });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.bootstrap5.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.colVis.min.js"></script>
</body>
</html>