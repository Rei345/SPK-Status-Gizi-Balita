<?php
include "koneksi.php";
session_start();
error_reporting(E_ALL); ini_set('display_errors', 1);

function input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['submit'])) {
    $jadwal = input($_POST["jadwal"]);
    $nik = input($_POST["nik"]);
    $nama = strtoupper(input($_POST["nama"]));
    $berat_badan = str_replace(',', '.', input($_POST["berat_badan"]));
    $tinggi_badan = str_replace(',', '.', input($_POST["tinggi_badan"]));
    $gender = input($_POST["gender"]);

    if($berat_badan <= 0){
        $_SESSION['nilai'] = "danger, Isi berat badan dengan benar!";
        header("Location: kelola.php");
    } elseif ($tinggi_badan <= 0){
        $_SESSION['nilai'] = "danger, Isi tinggi badan dengan benar!";
        header("Location: kelola.php");
    } else {
        // Simpan data peserta baru
        $sql = "INSERT INTO peserta (jadwal, nik, nama, berat_badan, tinggi_badan, gender) VALUES ('$jadwal','$nik', '$nama', '$berat_badan', '$tinggi_badan', '$gender')";
        $hasil = mysqli_query($conn, $sql);
    
        // Ambil nilai minimum dan maksimum tinggi dan berat seluruh peserta
        $sqlMinMax = "SELECT MIN(tinggi_badan) AS min_tinggi, MAX(tinggi_badan) AS max_tinggi, 
                             MIN(berat_badan) AS min_berat, MAX(berat_badan) AS max_berat 
                      FROM peserta";
        $resultMinMax = mysqli_query($conn, $sqlMinMax);
        $minMax = mysqli_fetch_assoc($resultMinMax);
    
        $minTinggi = $minMax['min_tinggi'];
        $maxTinggi = $minMax['max_tinggi'];
        $minBerat = $minMax['min_berat'];
        $maxBerat = $minMax['max_berat'];
    
        // Update status gizi seluruh peserta
        $sqlPeserta = "SELECT * FROM peserta";
        $resultPeserta = mysqli_query($conn, $sqlPeserta);
    
        while ($peserta = mysqli_fetch_assoc($resultPeserta)) {
            $tinggi = $peserta['tinggi_badan'];
            $berat = $peserta['berat_badan'];
    
            // Normalisasi tinggi dan berat
            $normalisasiTinggi = ($maxTinggi != $minTinggi) ? ($tinggi - $minTinggi) / ($maxTinggi - $minTinggi) : 0;
            $normalisasiBerat = ($maxBerat != $minBerat) ? ($berat - $minBerat) / ($maxBerat - $minBerat) : 0;
    
            // Hitung jarak ke kategori gizi
            $jarak1 = sqrt(pow($normalisasiTinggi - 0.92, 2) + pow($normalisasiBerat - 1.0, 2));
            $jarak2 = sqrt(pow($normalisasiTinggi - 1.0, 2) + pow($normalisasiBerat - 0.46, 2));
            $jarak3 = sqrt(pow($normalisasiTinggi - 0.35, 2) + pow($normalisasiBerat - 0.63, 2));
            $jarak4 = sqrt(pow($normalisasiTinggi - 0.58, 2) + pow($normalisasiBerat - 0.13, 2));
            $jarak5 = sqrt(pow($normalisasiTinggi - 0.0, 2) + pow($normalisasiBerat - 0.08, 2));
    
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
    
            // Update status gizi di database
            $sqlUpdate = "UPDATE peserta SET status='$statusGizi' WHERE nik='{$peserta['nik']}'";
            mysqli_query($conn, $sqlUpdate);
        }
    
        // Redirect ke halaman kelola
        header("Location: kelola.php");
    }

} else {
    echo "<div class='alert alert-danger'>Data gagal disimpan.</div>";
}
?>
