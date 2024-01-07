<?php
include 'conn.php';

$id = $_POST['id'];
$username = $_POST['username'];
$name = $_POST['name'];
$email = $_POST['email'];
$bornplace = $_POST['bornplace'];
$borndate = $_POST['borndate'];

$query = "UPDATE anggota SET username=?, nama=?, email=?, tempat_lahir=?, tanggal_lahir=? WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssssi", $username, $name, $email, $bornplace, $borndate, $id);


$response = array();
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
}

echo json_encode($response);
$stmt->close();
$conn->close();
