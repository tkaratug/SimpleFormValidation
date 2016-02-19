<?php 
if($_POST) {
	
	include 'validation.php';
	$validator = new Validation();
	
	$validator->set_rules([
		'adsoyad'   => 'required|min_numeric,4|max_numeric,8',
		'email'     => 'required|email'
	]);
			
	$validator->set_data([
		'adsoyad'   => $_POST['adsoyad'],
		'email'     => $_POST['email']
	], true);
			
	if($validator->is_valid() !== true) {
		echo '<div id="errors">';
		foreach($validator->errors as $error) {
			echo $error .'<br>';
		}
		echo '</div>';
	}
}
?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>Simple Form Validation</title>
		<style>
			body { margin:0;padding:0;font-size:15px;font-family: calibri;}
			#errors { border: 1px solid #bc5858; padding: 10px; width: 40%; margin: 20px auto 0 auto; text-align: center; }
			#wrapper { border: 1px solid #ccc; padding: 10px; width: 40%; margin: 20px auto 0 auto; text-align: center; }
		</style>
	</head>
	<body>
		<div id="wrapper">
			<form action="" method="post">
				<table border="0" cellpadding="10" align="center">
					<tr>
						<td>Required Field</td>
						<td><input type="text" name="adsoyad" /></td>
					</tr>
					<tr>
						<td>E-Mail</td>
						<td><input type="text" name="email" /></td>
					</tr>
					<tr>
						<td colspan="2" align="center"><input type="submit" value="Gönder" /></td>
					</tr>
				</table>
			</form>
		</div>
		
	</body>
</html>