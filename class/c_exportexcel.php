<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<style type="text/css">
	body{
		font-family: sans-serif;
	}
	table{
		margin: 20px auto;
		border-collapse: collapse;
	}
	table th,
	table td{
		border: 1px solid #3c3c3c;
		padding: 3px 8px;
 
	}
	a{
		background: blue;
		color: #fff;
		padding: 8px 10px;
		text-decoration: none;
		border-radius: 2px;
	}
	</style>
 
	<?php
	$tanggal = date("Y-m-d h:i:s", time());
	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=Rekap_SPH.xls");
	?>
 
	<center>
		<h1>Export Data SPH<br/></h1>
	</center>
 
	<table border="1">
		<tr>
			<th width="3%">No</th>
			<th style="width: 20%">Nomer SPH</th>
			<th style="width: 10%">Tanggal</th>
			<th style="width: 10%">Klien</th>
			<th style="width: 20%">Kabupaten/Kota</th>
			<th style="width: 20%">Provinsi</th>
			<th style="width: 30%">Kelengkapan</th>
			<th style="width: 30%">Diameter</th>
			<th style="width: 30%">Tinggi</th>
			<th style="width: 30%">Diameter Tengah</th>
			<th style="width: 15%">Operator</th>
		</tr>
		<?php 
		// koneksi database
		$koneksi = mysqli_connect("localhost","u5514609_can",",S1s6h8+Mrc)","u5514609_dbmarketing");
		$q = "SELECT s.*,ds.model,ds.d,ds.t,ds.dt,ds.plafon,ds.harga,ds.harga2,ds.jumlah,ds.ket,ds.transport,u.nama,p.name as pn,k.name as kn ";
		$q.= "FROM aki_sph s right join aki_dsph ds on s.noSph=ds.noSph left join aki_user u on s.kodeUser=u.kodeUser left join provinsi p on s.provinsi=p.id LEFT join kota k on s.kota=k.id ";
		$q.= "WHERE 1=1 group by s.noSph";
		$q.= " ORDER BY s.noSph desc ";
		$data = mysqli_query($koneksi,$q);
		$no = 1;
		while($d = mysqli_fetch_array($data)){
		?>
		<tr>
			<td><?php echo $no++; ?></td>
			<td><?php echo $d['noSph']; ?></td>
			<td><?php echo $d['tanggal']; ?></td>
			<td><?php echo $d['nama_cust']; ?></td>
			<td><?php echo $d['kn']; ?></td>
			<td><?php echo $d['pn']; ?></td>
			<td><?php  
				if ($d["plafon"] == 0){
					$kel = 'Full';
				}else if ($d["plafon"] == 1){
					$kel = 'Tanpa Plafon';
				}else{
					$kel = 'Waterproof';
				}
			echo $kel; ?></td>
			<td><?php echo $d['d'].' meter'; ?></td>
			<td><?php echo $d['t'].' meter'; ?></td>
			<td><?php echo $d['dt'].' meter'; ?></td>
			<td><?php echo $d['nama']; ?></td>
		</tr>
		<?php 
		}
		?>
	</table>
</body>
</html>




