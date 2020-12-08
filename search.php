<?php 
include 'connection.php';

if(isset($_GET['search'])){
	$search = $_GET['search'];
	echo "<b>Hasil pencarian : ".$search."</b>";
}
?>

<table border='1' cellpadding='5'>
	<tr>
        <th>Id</th>
        <th>Judul Musik</th>
        <th>Album</th>
        <th>Artis</th>
        <th>Cover Album</th>
	</tr>
	<?php 
	if(isset($_GET['search'])){
        $cari = $_GET['search'];
        if($_GET['selection'] == 'judul_musik'){
            $data = mysqli_query($connect, "select * from catalog where judul_musik like '%".$search."%'");				
        } else if($_GET['selection'] == 'album'){
            $data = mysqli_query($connect, "select * from catalog where album like '%".$search."%'");				
        } else if($_GET['selection'] == 'artis'){
            $data = mysqli_query($connect, "select * from catalog where artis like '%".$search."%'");				
        }
		
	}else{
		$data = mysqli_query($connect, "select * from catalog");		
	}
	$no = 1;
	while($row = mysqli_fetch_array($data)){
	?>
	<tr>
        <td><?php echo $row["id"] ?></td>
        <td><?php echo $row["judul_musik"] ?></td>
        <td><?php echo $row["album"] ?></td>
        <td><?php echo $row["artis"] ?></td>
        <td><?php echo "<img src='uploads/".$row['cover_album']."' width='250px' height='150px'>";?></td>
	</tr>
	<?php } ?>
</table>