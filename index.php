<?php
	session_start();
	$vars = isset($_SESSION['vars']) ? $_SESSION['vars'] : array(
		'NAMESPACE' => '',
		'MODULE_NAME' => '',
		'FIELDS' => 'title|text
subtitle|text
description|textarea
color|select(red,green,blue)
image|image
thumbnail|image
pdf|file'
	);
// print_r($vars);
?>
<html>
	<head>
		<title>Magento Module Generator</title>
        <style type="text/css">
			* {
				margin: 0; padding: 0;
			}
			html {
				font-family: monospace;
				padding: 20px;
				background: #333;
				color: #0f0;
			}
			input, textarea {
				border: 1px solid #0f0;
				background: #333;
				font-family: monospace;
				padding: 5px;
				color: #fff;
				clear: both;
				margin-bottom: 20px;
			}
			textarea {
				width: 600px;
				height: 400px;
			}
			label {
				display: block;
			}
			hr {
				border: none;
				border-top: 1px dashed #080;
			}
			p.cool {
				color: #ff0;
				padding: 10px 0;
			}
        </style>
	</head>
	<body>
	<?php
	function inputVars($name, $label = null, $debug = '', $value = '')
	{
		if($label == null) { $label = ucfirst(str_replace('_', ' ', $name)); }

		echo sprintf('<label for="%1$s">%2$s:</label><input data-debug="%3$s" type="text" name="vars[%1$s]" id="%1$s"
			value="%4$s" />',
			$name, $label, $debug, $value);
	}
?>
		<p>Magento Module Generator</p>
		<hr />
	<?php
	if(isset($_GET['ok'])) {
		?>
		<p class="cool">Compilation complete. Your module is waiting for you in the 'output'-directory.</p>
			<hr />
		<?php
	}

?>
		<form method="post" action="compile.php">
<?php
			inputVars('namespace', null, '', $vars['NAMESPACE']);
			inputVars('module_name', null, '', $vars['MODULE_NAME']);
?>
			<p>Velden: text,textarea,select(a,b,c),image,file:</p>
			<textarea name="vars[fields]"><?php echo $vars['FIELDS']; ?></textarea>

			<br />
			<input type="submit" value="Compile" />
		</form>
	</body>
</html>