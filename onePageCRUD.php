<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php

// koneksi ke database
$connection = mysqli_connect("localhost","root","","catalogmusik") or die(mysqli_error());

// fungsi create
function create($connection){
	if (isset($_POST['simpan'])){
		$judul_musik = $_POST['judul_musik'];
		$album =$_POST['album'];
		$artis =$_POST['artis'];

		$cover_album = $_FILES['cover_album']['name'];
		$target_path = "uploads/";
		$target_path = $target_path . basename($cover_album);

    	move_uploaded_file($_FILES['cover_album']['tmp_name'], $target_path);
		//validasi kelengkapan data
		if(!empty($judul_musik) && !empty($album) && !empty($artis) && !empty($cover_album)){
			$sql = "INSERT INTO catalog(judul_musik, album, artis, cover_album)
            VALUE('$judul_musik', '$album', '$artis', '$cover_album')";
			$simpan = mysqli_query($connection, $sql);
			if($simpan && isset($_GET['aksi'])){
				if($_GET['aksi'] == 'create'){
					header('location: onePageCRUD.php');
				}
			}
		} else {
			$pesan = "Tidak dapat menyimpan, data belum lengkap!";
		}
	}

	?> 
		<table>
        <form name="insertForm" method="POST" action="" enctype="multipart/form-data">
            <tr>
                <td> Judul Musik : </td>
                <td> <input type="text" name="judul_musik"></td>
            </tr>
            <tr>
                <td> Album : </td>
                <td> <input type="text" name="album"></td>
            </tr>
            <tr>
                <td> Artis : </td>
                <td> <input type="text" name="artis"></td>
            </tr>
            <tr>
                Cover Album :
                <input name="cover_album" type="file"><br>
            </tr>
            <tr>
                <td> <input type="submit" name="simpan" value="Simpan"></td>
				<td><p><?php echo isset($pesan) ? $pesan : "" ?></p></td>
			</tr>
		</form>
	</table>
	<table>
	<br>
		<form action="search.php" method="GET">
			<label>Cari : </label>
			<input type="text" name="search">
			<br>
			<label for="selection">Search based on : </label>
				<select id="selection" name="selection">
					<option value="judul_musik">Judul Musik</option>
					<option value="album">Album</option>
					<option value="artis">Artis</option>
				</select>
			<input type="submit" name ="cari" value="search">
			<br><br>
		</form>
	</table>
	<?php

}
// Tutup Fungsi create


// Fungsi Read
function show($connection){
	$sql = "SELECT * FROM catalog";
	$query = mysqli_query($connection, $sql);
	
	echo "<table border='1' cellpadding='5'>";
	echo "<tr>
			<th> ID</th>
			<th> Judul Musik</th>
			<th> Album</th>
			<th> Artis</th>
			<th> Cover Album</th>
			<th> Action </th>
		</tr>";
	
	//print data
	while($row = mysqli_fetch_array($query)){
		?>
			<tr>
				<td><?php echo $row["id"] ?></td>
                <td><?php echo $row["judul_musik"] ?></td>
                <td><?php echo $row["album"] ?></td>
                <td><?php echo $row["artis"] ?></td>
                <td><?php echo "<img src='uploads/".$row['cover_album']."' width='250px' height='150px'>";?></td>
				<td>
					<a href="onePageCRUD.php?aksi=update&id=<?php echo $row['id']; ?>&judul_musik=<?php echo $row['judul_musik']; ?>&album=<?php echo $row['album']; ?>&artis=<?php echo $row['artis']; ?>">Ubah</a> |
					<a href="onePageCRUD.php?aksi=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah anda yakin akan menghapus data ini?')">Hapus</a>
				</td>
			</tr>
		<?php
	}
}
// Tutup Fungsi Read


// Fungsi Update
function update($connection){

	if(isset($_POST['btn_update'])){
		$id = $_POST['id'];
		$judul_musik = $_POST['judul_musik'];
		$album =$_POST['album'];
		$artis =$_POST['artis'];
	
		$cover_album = $_FILES['cover_album']['name'];
		$target_path = "uploads/";
		$target_path = $target_path . basename($cover_album);
	
		move_uploaded_file($_FILES['cover_album']['tmp_name'], $target_path);
		
		//validasi kelengkapan data
		if(!empty($judul_musik) && !empty($album) && !empty($artis) && !empty($cover_album)){
			$sql_update = "UPDATE catalog SET judul_musik='$judul_musik', album = '$album', artis = '$artis', cover_album='$cover_album' WHERE id = '$id'";
			$update = mysqli_query($connection, $sql_update);
			if($update && isset($_GET['aksi'])){
				if($_GET['aksi'] == 'update'){
					header('location: onePageCRUD.php');
				}
			}
		} else {
			$pesan = "Data tidak lengkap!";
		}
	}
	
	// form untuk edit
	if(isset($_GET['id'])){
		?>
			<a href="onePageCRUD.php"> &laquo; Home</a> | 
			<a href="onePageCRUD.php?aksi=create"> Tambah Data</a>
			<hr>
			
			<form name = "editForm" action="" method="POST" enctype="multipart/form-data">
				<table>
					<tr>
						<td> Id : </td>
						<td> <input type="text" name="id" value="<?php echo $_GET['id']?>"></td>
					</tr>
					<tr>
						<td> Judul Musik : </td>
						<td> <input type="text" name="judul_musik" value="<?php echo $_GET['judul_musik']?>"></td>
					</tr>
					<tr>
						<td> Album : </td>
						<td> <input type="text" name="album" value="<?php echo $_GET['album']?>"></td>
					</tr>
					<tr>
						<td> Artis : </td>
						<td> <input type="text" name="artis" value="<?php echo $_GET['artis']?>"></td>
					</tr>
					<tr>
						choose a file to upload :
						<input name="cover_album" type="file" value="<?php echo $_GET['cover_album']?>" ><br>
					</tr>
					<tr>
						<td> <input type="submit" name="btn_update" value="Update"></td>
						<td><p><?php echo isset($pesan) ? $pesan : "" ?></p></td>
				</table>
			</form>

			<table>
				<br>
				<form action="search.php" method="GET">
					<label>Cari :</label>
					<input type="text" name="search">
					<br>
					<label for="selection">Search based on : </label>
					<select name="selection">
						<option value="judul_musik">Judul Musik</option>
						<option value="album">Album</option>
						<option value="artis">Artis</option>
					</select>
					<input type="submit" name ="cari" value="search">
				</form>
			</table>
		<?php
	}
	
}
// --- Tutup Fungsi Update


// --- Fungsi Delete
function delete($connection){

	if(isset($_GET['id']) && isset($_GET['aksi'])){
		$id = $_GET['id'];
		$sql_delete = "DELETE FROM catalog WHERE id=" . $id;
		$delete = mysqli_query($connection, $sql_delete);
		
		if($delete){
			if($_GET['aksi'] == 'delete'){
				header('location: onePageCRUD.php');
			}
		}
	}
	
}
// Tutup Fungsi Hapus


//  Program Utama
if (isset($_GET['aksi'])){
	switch($_GET['aksi']){
		case "create":
			echo '<a href="onePageCRUD.php"> &laquo; Home</a>';
			create($connection);
			break;
		case "read":
			show($connection);
			break;
		case "update":
			update($connection);
			show($connection);
			break;
		case "delete":
			delete($connection);
			break;
		case "search":
			search($connection);
			break;
		default:
			echo "<h3>Aksi <i>".$_GET['aksi']."</i> tidak ada!</h3>";
			create($connection);
			show($connection);
	}
} else {
	create($connection);
	show($connection);
}

?>
</body>
</html>