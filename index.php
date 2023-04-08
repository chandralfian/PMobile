<?php

// Ambil data mahasiswa dari API
$url = 'https://api.steinhq.com/v1/storages/642110d4eced9b09e9c62384/20A2';
$data = file_get_contents($url);
$mahasiswa = json_decode($data, true);

// Konfigurasi pagination
$per_page = 10;
$total_data = count($mahasiswa);
$total_pages = ceil($total_data / $per_page);

// Ambil parameter halaman dan kata kunci pencarian dari URL
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Filter data mahasiswa berdasarkan kata kunci pencarian
if (!empty($search)) {
    $mahasiswa = array_filter($mahasiswa, function($mhs) use ($search) {
        return strpos(strtolower($mhs['Nama']), strtolower($search)) !== false
            || strpos(strtolower($mhs['NIM']), strtolower($search)) !== false;
    });
    $total_data = count($mahasiswa);
    $total_pages = ceil($total_data / $per_page);
}

// Ambil data mahasiswa untuk halaman ini
$start = ($current_page - 1) * $per_page;
$mahasiswa_page = array_slice($mahasiswa, $start, $per_page);

// Tampilkan form pencarian
echo '<form method="get">';
echo '<input type="text" name="search" value="'.$search.'" placeholder="Search by name or NIM">';
echo '<button type="submit">Search</button>';
echo '</form>';

// Tampilkan tabel dengan desain CSS
echo '<table class="mahasiswa-table">';
echo '<thead>';
echo '<tr>';
echo '<th>No</th>';
echo '<th>Nama</th>';
echo '<th>NIM</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
foreach ($mahasiswa_page as $index => $mhs) {
    $no = $start + $index + 1;
    $nama = $mhs['Nama'];
    $nim = $mhs['NIM'];
    echo '<tr>';
    echo '<td>'.$no.'</td>';
    echo '<td>'.$nama.'</td>';
    echo '<td>'.$nim.'</td>';
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';

// Tampilkan pagination
echo '<div class="pagination">';
echo '<ul>';
if ($current_page > 1) {
    echo '<li><a href="?page='.($current_page-1).'&search='.$search.'">Prev</a></li>';
}
for ($i=1; $i<=$total_pages; $i++) {
    if ($i == $current_page) {
        echo '<li><span>'.$i.'</span></li>';
    } else {
        echo '<li><a href="?page='.$i.'&search='.$search.'">'.$i.'</a></li>';
    }
}
if ($current_page < $total_pages) {
    echo '<li><a href="?page='.($current_page+1).'&search='.$search.'">Next</a></li>';
}
echo '</ul>';
echo '</div>';
echo '<a href="index.php">Kembali</a>';
?>


<style>
body {
  background-color: #f7f7f7;
}

.mahasiswa-table {
  border-collapse: collapse;
  width: 100%;
}

.mahasiswa-table th, .mahasiswa-table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

.mahasiswa-table th {
  background-color: #f2f2f2;
  font-weight: bold;
}

.pagination {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.pagination ul {
  display: flex;
  list-style: none;
  padding: 0;
  margin: 0;
}

.pagination li {
  margin: 0 5px;
}

.pagination li span {
  color: #aaa;
  cursor: default;
  padding: 5px;
}

.pagination li a {
  color: #000;
  cursor: pointer;
  padding: 5px;
  text-decoration: none;
  background-color: #f44336;
  color: white;
}

.pagination li a:hover {
  background-color: #555;
}


</style>
