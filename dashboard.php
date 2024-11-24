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
    <link rel="stylesheet" href="stylef.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Dashboard</title>
</head>
<body>
<div class="sidebar">
    <div class="logo"></div>
    <ul class="menu">
            <li class="active">
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
                <h2>Dashboard</h2>
            </div>
            <div class="user--info">
                <span class="badge mb-1 rounded-pill text-bg-primary">selamat datang</span>
                <h2 class="fw-bolder fs-6 text-body-emphasis"><?php echo $_SESSION['email'];?></h2>
                <img src="profile.jpg" alt="">
            </div>
        </div>

        <main>
            <div class="tabular--wrapper" style="width: 380px;">
                <h4 class="main--content fw-semibold" style="text-align: center; border-top-right-radius: 10px; border-top-left-radius: 10px; color: dimgray;">Persentase Status Gizi</h4>
                <canvas id="myChart" class="main--content" style="height:30vh; width:330px; margin: 0 auto; max-height: 200px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px;"></canvas>
            </div>
            <div class="tabular--wrapper" style="width: 780px; margin-left: 20px;">
                <canvas id="MyChart" class="main--content" style="height: 268px; width:740px; margin: 0 auto; border-radius: 10px;"></canvas>
            </div>
        </main>
        <main>
            <div class="tabular--wrapper" style="width: 125vh;">
                <canvas id="ChartHu" class="main--content" style="height: 36.5vh; border-radius: 10px;"></canvas>
            </div>
            
            <div class="tabular--wrapper" style="width: 26.5vh; margin-left: 20px;">
                <!-- Selector untuk memilih bulan dan tahun -->
                 <div class="main--content" style="border-radius: 10px; width: 23vh; left: -10px;">
                    <p style="text-align: center; color: dimgray; font-weight: 600;">Filter Data</p>
                    <label for="bulan">Pilih Bulan:</label>
                    <form action="" method="get">
                        <select id="bulan">
                            <option value="0">Januari</option>
                            <option value="1">Februari</option>
                            <option value="2">Maret</option>
                            <option value="3">April</option>
                            <option value="4">Mei</option>
                            <option value="5">Juni</option>
                            <option value="6">Juli</option>
                            <option value="7">Agustus</option>
                            <option value="8">September</option>
                            <option value="9">Oktober</option>
                            <option value="10">November</option>
                            <option value="11">Desember</option>
                        </select>
                        <br><br>
                        <label for="tahun">Pilih Tahun:</label>
                        <select id="tahun">
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                        </select>
                        <br><br><br>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-info">Filter</button>
                        </div>
                    </form>
                 </div>
            </div>
        </main>
    </div>

<!-- Grafik Pertama -->
<script>
const data = {
    labels: ['Gizi Buruk', 'Gizi Kurang', 'Gizi Baik', 'Gizi Lebih', 'Obesitas'],
    datasets: [{
        label: 'Status Gizi Balita',
        data: [
            <?php
            $statuses = ['Gizi Buruk', 'Gizi Kurang', 'Gizi Baik', 'Gizi Lebih', 'Obesitas'];
            $dataPoints = [];
            foreach ($statuses as $status) {
                $qry = $conn->query("SELECT * FROM peserta WHERE status='$status'");
                $dataPoints[] = $qry->num_rows;
            }
            echo implode(",", $dataPoints);
            ?>
        ],
        backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(0, 256, 86)',
            'rgb(255, 205, 86)',
            'rgb(255, 127, 0)'
        ],
        hoverOffset: 5
    }]
};

const config = {
    type: 'pie',
    data: data,
    options: {
        plugins: {
            legend: {
                position: 'right',  // Menempatkan legend di sebelah kanan
                title: {
                    display: true,
                    text: 'Keterangan :',
                    font: {
                        size: 20
                    }
                }
            }
        }
    }
};

const myChart = new Chart(
    document.getElementById('myChart'),
    config
);
</script>

<?php
        // Variabel bulan dan tahun (dapat diambil dari form input atau selector)
        $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
        $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

        // Data untuk Gizi Buruk
        $qryGiziBuruk = $conn->query("
            SELECT MONTH(jadwal) AS bulan, COUNT(*) AS jumlah 
            FROM peserta 
            WHERE status='Gizi Buruk' AND YEAR(jadwal)='$tahun'
            GROUP BY MONTH(jadwal)
        ");
        $dataGiziBuruk = array_fill(1, 12, 0);
        while ($row = $qryGiziBuruk->fetch_assoc()) {
            $dataGiziBuruk[$row['bulan']] = $row['jumlah'];
        }

        // Data untuk Gizi Kurang
        $qryGiziKurang = $conn->query("
            SELECT MONTH(jadwal) AS bulan, COUNT(*) AS jumlah 
            FROM peserta 
            WHERE status='Gizi Kurang' AND YEAR(jadwal)='$tahun'
            GROUP BY MONTH(jadwal)
        ");
        $dataGiziKurang = array_fill(1, 12, 0);
        while ($row = $qryGiziKurang->fetch_assoc()) {
            $dataGiziKurang[$row['bulan']] = $row['jumlah'];
        }

        // Data untuk Gizi Baik
        $qryGiziBaik = $conn->query("
            SELECT MONTH(jadwal) AS bulan, COUNT(*) AS jumlah 
            FROM peserta 
            WHERE status='Gizi Baik' AND YEAR(jadwal)='$tahun'
            GROUP BY MONTH(jadwal)
        ");
        $dataGiziBaik = array_fill(1, 12, 0);
        while ($row = $qryGiziBaik->fetch_assoc()) {
            $dataGiziBaik[$row['bulan']] = $row['jumlah'];
        }

        // Data untuk Gizi Lebih
        $qryGiziLebih = $conn->query("
            SELECT MONTH(jadwal) AS bulan, COUNT(*) AS jumlah 
            FROM peserta 
            WHERE status='Gizi Lebih' AND YEAR(jadwal)='$tahun'
            GROUP BY MONTH(jadwal)
        ");
        $dataGiziLebih = array_fill(1, 12, 0);
        while ($row = $qryGiziLebih->fetch_assoc()) {
            $dataGiziLebih[$row['bulan']] = $row['jumlah'];
        }

        // Data untuk Obesitas
        $qryObesitas = $conn->query("
            SELECT MONTH(jadwal) AS bulan, COUNT(*) AS jumlah 
            FROM peserta 
            WHERE status='Obesitas' AND YEAR(jadwal)='$tahun'
            GROUP BY MONTH(jadwal)
        ");
        $dataObesitas = array_fill(1, 12, 0);
        while ($row = $qryObesitas->fetch_assoc()) {
            $dataObesitas[$row['bulan']] = $row['jumlah'];
        }

        // Data label (bulan-bulan)
        $labels = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    ?>

    <!-- Grafik Kedua -->
    <script>
    const ctx = document.getElementById('MyChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['<?= implode("','", $labels) ?>'],
            datasets: [{
                    label: 'Gizi Buruk',
                    data: [<?= implode(',', $dataGiziBuruk) ?>],
                    borderWidth: 1,
                    backgroundColor: 'rgb(255, 99, 132)'
                },
                {
                    label: 'Gizi Kurang',
                    data: [<?= implode(',', $dataGiziKurang) ?>],
                    borderWidth: 1,
                    backgroundColor: 'rgb(54, 162, 235)'
                },
                {
                    label: 'Gizi Baik',
                    data: [<?= implode(',', $dataGiziBaik) ?>],
                    borderWidth: 1,
                    backgroundColor: 'rgb(0, 256, 86)'
                },
                {
                    label: 'Gizi Lebih',
                    data: [<?= implode(',', $dataGiziLebih) ?>],
                    borderWidth: 1,
                    backgroundColor: 'rgb(255, 205, 86)'
                },
                {
                    label: 'Obesitas',
                    data: [<?= implode(',', $dataObesitas) ?>],
                    borderWidth: 1,
                    backgroundColor: 'rgb(255, 127, 0)'
                }
                
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    title: {
                        display: true,
                        text: 'Jumlah Status Gizi Balita Setiap Bulan',
                        font: {
                            size: 20,
                            weight: '600'
                        },
                        padding: {
                            top: 5,
                            bottom: 5
                        }
                    }
                }
            }
        }
    });
    </script>

    
    <?php
        // Variabel bulan dan tahun (dapat diambil dari form input atau selector)
        $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
        $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

        // Data untuk Perempuan
        $qryPerempuan = $conn->query("
            SELECT MONTH(jadwal) AS bulan, COUNT(*) AS jumlah 
            FROM peserta 
            WHERE gender='Perempuan' AND YEAR(jadwal)='$tahun'
            GROUP BY MONTH(jadwal)
        ");
        $dataPerempuan = array_fill(1, 12, 0);
        while ($row = $qryPerempuan->fetch_assoc()) {
            $dataPerempuan[$row['bulan']] = $row['jumlah'];
        }

        // Data untuk Laki-Laki
        $qryLakiLaki = $conn->query("
            SELECT MONTH(jadwal) AS bulan, COUNT(*) AS jumlah 
            FROM peserta 
            WHERE gender='Laki-Laki' AND YEAR(jadwal)='$tahun'
            GROUP BY MONTH(jadwal)
        ");
        $dataLakiLaki = array_fill(1, 12, 0);
        while ($row = $qryLakiLaki->fetch_assoc()) {
            $dataLakiLaki[$row['bulan']] = $row['jumlah'];
        }

        // Data label (bulan-bulan)
        $labels = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    ?>

    <!-- Grafik Ketiga -->
    <script>
    const grafik = document.getElementById('ChartHu');

    new Chart(grafik, {
        type: 'line',
        data: {
            labels: ['<?= implode("','", $labels) ?>'],
            datasets: [{
                    label: 'Perempuan',
                    data: [<?= implode(',', $dataPerempuan) ?>],
                    fill: false,
                    backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)'
                },
                {
                    label: 'Laki-Laki',
                    data: [<?= implode(',', $dataLakiLaki) ?>],
                    fill: false,
                    backgroundColor: 'rgb(54, 162, 235)',
                    borderColor: 'rgb(54, 162, 235)'
                }
            ]
        },
        options: {
            responsive: true,
            animations: {
                tension: {
                    duration: 1000,
                    easing: 'linear',
                    from: 1,
                    to: 0,
                    loop: true
                }
                },
            scales: {
                y: {
                    beginAtZero: true
                }
                },
            plugins: {
                legend: {
                    title: {
                        display: true,
                        text: 'Jumlah Balita Yang Berjenis Kelamin Laki-laki & Perempuan',
                        font: {
                            size: 20,
                            weight: '600' // Atur font-weight menjadi 600 (semibold)
                        },
                        padding: {
                            top: 5,
                            bottom: 5
                        }
                    }
                }
            }
        }
    });
    </script>

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
</body>
</html>
