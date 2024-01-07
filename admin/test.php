<?php
include 'config/conn.php';

// Hash password menggunakan bcrypt
$hashedPassword = password_hash('10042003', PASSWORD_BCRYPT);

$sql = "INSERT INTO anggota (username, password, nama, email, tempat_lahir, tanggal_lahir, is_admin)
        VALUES ('nengsilva', '$hashedPassword', 'Neng Silva Astuti Herlanta', 'Belum Diisi', 'Tidak Diketahui', '2003-04-10', 0)";

if ($conn->query($sql) === TRUE) {
    echo "Data admin berhasil ditambahkan.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
