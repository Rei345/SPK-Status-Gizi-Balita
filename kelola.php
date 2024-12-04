<?php include "koneksi.php"; ?>
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
    <link rel="stylesheet" href="styled.css" />

    <!-- Font Awesome Cdn link -->
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Data Tables -->
    <link rel="stylesheet" href="datatables/datatables.css">
    <script src="datatables/datatables.js"></script>

    <!-- Link untuk CSS Bootstrap -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <!-- Script untuk Bootstrap -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>

    <title>Kelola</title>
    <link rel="icon" href="profile.jpg" type="image/x-icon">
</head>

    <script>
        $(document).ready(function() {
            $('#dt').DataTable({
                columnDefs: [{ 
                    targets: '_all', 
                    className: 'dt-body-left' 
                }
                ]
            });
        });
    </script>

<body>
    <?php
        if (isset($_GET['id_peserta'])) {
            $id_peserta = htmlspecialchars($_GET["id_peserta"]);

            // Periksa apakah ada data di dalam tabel peserta sebelum melanjutkan
            $sqlCheckData = "SELECT COUNT(*) AS total_peserta FROM peserta";
            $resultCheck = mysqli_query($conn, $sqlCheckData);
            $checkData = mysqli_fetch_assoc($resultCheck);

            // Jika data lebih dari 1 (karena kita akan menghapus satu peserta), lanjutkan operasi
            if ($checkData['total_peserta'] > 1) {
                $stmt = $conn->prepare("DELETE FROM peserta WHERE id_peserta = ?");
                $stmt->bind_param("i", $id_peserta);

                if ($stmt->execute()) {
                    // Query to get min and max values for height and weight
                    $sqlMinMax = "SELECT MIN(tinggi_badan) AS min_tinggi, MAX(tinggi_badan) AS max_tinggi, 
                                         MIN(berat_badan) AS min_berat, MAX(berat_badan) AS max_berat 
                                  FROM peserta";
                    $resultMinMax = mysqli_query($conn, $sqlMinMax);
                    $minMax = mysqli_fetch_assoc($resultMinMax);

                    $minTinggi = $minMax['min_tinggi'];
                    $maxTinggi = $minMax['max_tinggi'];
                    $minBerat = $minMax['min_berat'];
                    $maxBerat = $minMax['max_berat'];

                    // Loop through all participants and calculate nutrition status
                    $sqlPeserta = "SELECT * FROM peserta";
                    $resultPeserta = mysqli_query($conn, $sqlPeserta);

                    while ($peserta = mysqli_fetch_assoc($resultPeserta)) {
                        $tinggi = $peserta['tinggi_badan'];
                        $berat = $peserta['berat_badan'];

                        // Normalize height and weight
                        $normalisasiTinggi = ($maxTinggi != $minTinggi) ? ($tinggi - $minTinggi) / ($maxTinggi - $minTinggi) : 0;
                        $normalisasiBerat = ($maxBerat != $minBerat) ? ($berat - $minBerat) / ($maxBerat - $minBerat) : 0;

                        // Calculate distances for different nutrition statuses
                        $jarak1 = sqrt(pow($normalisasiTinggi - 0.92, 2) + pow($normalisasiBerat - 1.0, 2));
                        $jarak2 = sqrt(pow($normalisasiTinggi - 1.0, 2) + pow($normalisasiBerat - 0.46, 2));
                        $jarak3 = sqrt(pow($normalisasiTinggi - 0.35, 2) + pow($normalisasiBerat - 0.63, 2));
                        $jarak4 = sqrt(pow($normalisasiTinggi - 0.58, 2) + pow($normalisasiBerat - 0.13, 2));
                        $jarak5 = sqrt(pow($normalisasiTinggi - 0.0, 2) + pow($normalisasiBerat - 0.08, 2));

                        // Determine closest distance to categorize nutrition status
                        if ($jarak1 < $jarak2 && $jarak1 < $jarak3 && $jarak1 < $jarak4 && $jarak1 < $jarak5) {
                            $statusGizi = "Gizi Buruk";
                        } elseif ($jarak2 < $jarak1 && $jarak2 < $jarak3 && $jarak2 < $jarak4 && $jarak2 < $jarak5) {
                            $statusGizi = "Gizi Kurang";
                        } elseif ($jarak3 < $jarak1 && $jarak3 < $jarak2 && $jarak3 < $jarak4 && $jarak3 < $jarak5) {
                            $statusGizi = "Gizi Baik";
                        } elseif ($jarak4 < $jarak1 && $jarak4 < $jarak3 && $jarak4 < $jarak2 && $jarak4 < $jarak5) {
                            $statusGizi = "Gizi Lebih";
                        } else {
                            $statusGizi = "Obesitas";
                        }

                        // Update nutrition status in the database
                        $sqlUpdate = "UPDATE peserta SET status='$statusGizi' WHERE nik='{$peserta['nik']}'";
                        mysqli_query($conn, $sqlUpdate);
                    }

                    // Redirect to index.php
                    header("Location: kelola.php");
                } else {
                    echo "<div class='alert alert-danger'>Data Gagal Dihapus</div>";
                }

                $stmt->close();
            } else {
                $sql = "DELETE FROM peserta WHERE id_peserta='$id_peserta'";
                mysqli_query($conn, $sql);
                header("Location: kelola.php");
            }
        }
    ?> 

    <div class="sidebar">
        <div class="logo"></div>        
        <ul class="menu">
            <li>
                <a href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="active">
                <a href="kelola.php">
                    <i class="fas fa-database"></i>
                    <span>Kelola</span>
                </a>
            </li>
            <li>
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
                <h2>Kelola Data</h2>
            </div>
            <div class="user--info">
                 
                <span class="badge mb-1 rounded-pill text-bg-primary">selamat datang</span>
                <h2 class="fw-bolder fs-6 text-body-emphasis"><?php echo $_SESSION['email'];?></h2>
                <img src="polmed.png" alt="">
            </div>
        </div>

        <div class="tabular--wrapper">
            <div class="header--wrapper">
                <h3 class="main--title fs-3">Data Balita</h3>
                <!-- Button trigger modal -->
                <button type="button" class="Btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <div class="sign">+</div> 
                    <div class="text">Create</div>
                </button> 
            </div>
            <?php
                        if(isset($_SESSION['nilai'])){
                            $split = explode(', ', $_SESSION['nilai']);
                    ?>
                        <div class="alert alert-<?php echo $split[0]; ?> alert-dismissible fade show" role="alert">
                        <?php echo $split[1]; if(count($split)>2){echo ' '.$split[2];}?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php
                        unset($_SESSION['nilai']); // Hapus hanya pesan error, bukan seluruh sesi
                    }
                    ?>  
            
                    <form action="" class="row mx-auto mx-1" method="post">
                        Cari data dari : 
                        <input type="date" name="tgl_mulai" class="form-control col mx-3 ">
                         Sampai : 
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($_POST['filter_tgl'])){
                            $mulai = $_POST['tgl_mulai']; 
                            $selesai = $_POST['tgl_selesai'];
                            
                            if($mulai!=null || $selesai!=null){
                                $hasil = mysqli_query($conn, "SELECT * FROM peserta WHERE jadwal BETWEEN '".$mulai."' and '".$selesai."' ORDER BY id_peserta ASC");
                            } else {
                                $hasil = mysqli_query($conn, "SELECT * FROM peserta ORDER BY id_peserta ASC");
                            }
                        } else {
                            $hasil = mysqli_query($conn, "SELECT * FROM peserta ORDER BY id_peserta ASC");
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
                            <td>
                                <button type="button" class="fas fa-pen" data-bs-toggle="modal" data-bs-target="#updateModal<?php echo $data['id_peserta']; ?>"> </button>
                                <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?id_peserta=<?php echo htmlspecialchars($data['id_peserta']); ?>" class="hapus"><button class="fas fa-trash"  style="background-color:red;"></button></a> 
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Untuk Update -->
    <?php foreach ($hasil as $data) { ?>
        <div class="modal fade" id="updateModal<?php echo $data['id_peserta']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="updateModalLabel">Update Data Balita</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="updateForm" class="form" action="update_data.php" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="id_peserta" value="<?php echo $data['id_peserta']; ?>" />
                            <div class="input-box mb-3">
                                <label for="jadwal" class="form-label">Tanggal Pengukuran</label>
                                <input class="form-control" type="date" id="jadwal" name="jadwal" value="<?php echo $data['jadwal']; ?>" required />
                            </div>
                            <div class="input-box mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input class="form-control" type="number" id="nik" name="nik" value="<?php echo $data['nik']; ?>" required />
                            </div>
                            <div class="input-box mb-3">
                                <label for="nama" class="form-label">Nama Balita</label>
                                <input class="form-control" type="text" id="nama" name="nama" value="<?php echo $data['nama']; ?>" required />
                            </div>
                            <div class="input-box mb-3">
                                <label for="berat_badan" class="form-label">Berat Badan (kg)</label>
                                <input class="form-control" type="text" id="berat_badan" name="berat_badan" value="<?php echo $data['berat_badan']; ?>" required />
                            </div>
                            <div class="input-box mb-3">
                                <label for="tinggi_badan" class="form-label">Tinggi Badan (cm)</label>
                                <input class="form-control" type="text" id="tinggi_badan" name="tinggi_badan" value="<?php echo $data['tinggi_badan']; ?>" required />
                            </div>
                            <div class="gender-box mb-3">
                                <h5 class="form-label">Jenis Kelamin</h5>
                                <div class="gender-option">
                                    <div class="gender">
                                        <input type="radio" id="check-male<?php echo $data['id_peserta']; ?>" name="gender" value="Laki-Laki" <?php if($data['gender'] == "Laki-Laki"){ echo "checked"; } ?> />
                                        <label for="check-male<?php echo $data['id_peserta']; ?>">Laki-Laki</label>
                                    </div>
                                    <div class="gender">
                                        <input type="radio" id="check-female<?php echo $data['id_peserta']; ?>" name="gender" value="Perempuan" <?php if($data['gender'] == "Perempuan"){ echo "checked"; } ?> />
                                        <label for="check-female<?php echo $data['id_peserta']; ?>">Perempuan</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" name="update">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>

    
    <!-- Modal Untuk Create-->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Create Data Balita</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="giziForm" class="form" action="create_data.php" method="post">
        <div class="modal-body">
            <div class="input-box mb-3">
                <label class="form-label">Tanggal Pengukuran</label>
                <input
                class="form-control"
                type="date"
                name="jadwal"
                id="jadwal"
                required
                />
            </div>
            <div class="input-box mb-3">
                <label class="form-label">NIK</label>
                <input
                class="form-control"
                type="number"
                name="nik"
                id="nik"
                placeholder="Masukkan NIK"
                required
                />
            </div>
            <div class="input-box mb-3">
                <label class="form-label">Nama Balita</label>
                <input
                class="form-control"
                type="text"
                id="nama"
                name="nama"
                placeholder="Masukkan Nama Balita"
                required
                />
            </div>
            <div class="input-box mb-3">
                <label class="form-label">Berat Badan (kg)</label>
                <input
                class="form-control"
                type="number"
                id="berat_badan"
                name="berat_badan"
                step="0.01"
                placeholder="Masukkan Berat Badan Balita"
                required
                />
            </div>
            <div class="input-box mb-3">
                <label class="form-label">Tinggi Badan (cm)</label>
                <input
                class="form-control"
                type="number"
                id="tinggi_badan"
                name="tinggi_badan"
                step="0.01"
                placeholder="Masukkan Tinggi Badan Balita"
                required
                />
            </div>
            <div class="gender-box mb-3">
            <h5 class="form-label">Jenis Kelamin</h5>
                <div class="gender-option" require>
                <div class="gender">
                    <input
                    type="radio"
                    id="check-male"
                    name="gender"
                    value="Laki-Laki"
                    required
                    />
                    <label for="check-male">Laki-Laki</label>
                </div>
                <div class="gender">
                    <input
                    type="radio"
                    id="check-female"
                    name="gender"
                    value="Perempuan"
                    />
                    <label for="check-female">Perempuan</label>
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            </div>
        </form>
        </div>
    </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script> 
        document.querySelector(".hapus").addEventListener("click", (event) => {
            event.preventDefault(); // Mencegah aksi default dari elemen <a>
            Swal.fire({
                title: "Apakah kamu yakin?",
                text: "Kamu akan menghapus data ini",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus saja!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = event.target.closest('a').href; // Redirect setelah konfirmasi
                }
            });
        });
    </script>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>