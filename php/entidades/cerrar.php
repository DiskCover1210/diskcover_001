<?php

	if($_REQUEST['ban']=='3')
	{
		echo '<link rel="stylesheet" href="lib/css/sweetalert.css">
			<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.26.10/dist/sweetalert2.all.min.js"></script>
			<link rel="stylesheet" href="lib/css/sweetalert2.min.css">';
		echo "<script>
				Swal.fire({
					type: 'error',
					title: 'ya esta autorizado este documento electronico',
					text: ''
				});
				alert('ya esta autorizado este documento electronico');
				window.close();
			</script>";
	}
	else
	{
		if($_REQUEST['ban']=='0')
		{
			echo '<link rel="stylesheet" href="lib/css/sweetalert.css">
			<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.26.10/dist/sweetalert2.all.min.js"></script>
			<link rel="stylesheet" href="lib/css/sweetalert2.min.css">';
			echo "<script>
				alert('No se pudo autorizar el documento electronico');
				window.close();
			</script>";
		}
		else
		{
			echo '<link rel="stylesheet" href="lib/css/sweetalert.css">
			<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.26.10/dist/sweetalert2.all.min.js"></script>
			<link rel="stylesheet" href="lib/css/sweetalert2.min.css">';
			echo "<script>
				alert('se ha autorizado con exito el documento electronico');
				window.close();
			</script>";
		}
	}
		

?>

