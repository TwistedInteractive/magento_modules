<?php
session_start();

function flushDir($dirName, $include_subdirs = true)
{
	$files = glob($dirName.'/*');
	if(is_array($files) && !empty($files))
	{
		foreach($files as $file){
			if(is_file($file))
			{
				unlink($file);
			} elseif(is_dir($file) && $include_subdirs) {
				flushDir($file);
				rmdir($file);
			}
		}
	}
}

$vars = array();
foreach($_REQUEST['vars'] as $key => $value)
{
	$vars[strtoupper($key)] = $value;
}

// Modify:
$vars['NAMESPACE'] = ucfirst($vars['NAMESPACE']);
$vars['MODULE_NAME'] = ucfirst($vars['MODULE_NAME']);

// Extra data:
$vars['NAME_LOWERCASE'] = strtolower($vars['MODULE_NAME']);

// Fields:
// Install SQL:
$fields = explode("\n", $vars['FIELDS']);
$sql = '';
$form = '';
$first = true;
foreach($fields as $field)
{
	$field = trim($field);
	if(!empty($field))
	{
		$a = explode('|', $field);
		$name = $a[0];
		$b = explode('(', $a[1]);
		$type = $b[0];
		// SQL:
		switch($type)
		{
			case 'textarea' :
			{
				$sql .= sprintf('`%s` MEDIUMTEXT,', $name);
				break;
			}
			case 'date' :
			{
				$sql .= sprintf('`%s` DATE,', $name);
				break;
			}
			default :
			{
				$sql .= sprintf('`%s` VARCHAR(255),', $name);
				break;
			}
		}
		$sql .= "\n";

		// Form:
		switch($type)
		{
			case 'text' :
			case 'textarea' :
			case 'image' :
			case 'file' :
			{
				$form .= sprintf('
        $fieldset->addField(\'%4$s\', \'%1$s\', array(
            \'label\' => Mage::helper(\'%2$s\')->__(\'%3$s\'), \'name\' => \'%4$s\'
        ));',
					$type,
					$vars['NAME_LOWERCASE'],
					ucfirst($name),
					$name
				);
				break;
			}
			case 'select' :
			{
				$options = explode(',', str_replace(')', '', $b[1]));
				/*
'values' => array(
			    array('value' => 'left', 'label' => Mage::helper('banners')->__('Left')),
			    array('value' => 'right', 'label' => Mage::helper('banners')->__('Right'))
		    )
						 */
				$optionStr = array();
				foreach($options as $option)
				{
					$optionStr[] = 'array(\'value\'=>\''.$option.'\', \'label\'=>Mage::helper(\''.$vars['NAME_LOWERCASE'].'\')->__(\''.ucfirst($option).'\'))';
				}

				$form .= sprintf('
        $fieldset->addField(\'%4$s\', \'select\', array(
            \'label\' => Mage::helper(\'%2$s\')->__(\'%3$s\'), \'name\' => \'%4$s\', \'values\' => array(%5$s)
        ));',
					$name,
					$vars['NAME_LOWERCASE'],
					ucfirst($name),
					$name,
					implode(',', $optionStr)
				);
				break;
			}
			case 'date' :
			{
				$form .= sprintf('
        $fieldset->addField(\'%4$s\', \'%1$s\', array(
            \'label\' => Mage::helper(\'%2$s\')->__(\'%3$s\'), \'name\' => \'%4$s\',
            \'format\' => \'yyyy-MM-dd\',
			\'image\'     => $this->getSkinUrl(\'images/grid-cal.gif\')
        ));',
					$type,
					$vars['NAME_LOWERCASE'],
					ucfirst($name),
					$name
				);
				break;
			}
		}

		// EERSTE KOLOM:
		if($first)
		{
			$vars['FIRST_COLUMN'] = sprintf('
			$this->addColumn(\'%1$s\', array(
				\'header\' => Mage::helper(\'%2$s\')->__(\'%3$s\'),
				\'align\' => \'left\',
				\'index\' => \'%1$s\',
			));',
				$name,
				$vars['NAME_LOWERCASE'],
				ucfirst($name)
			);
			$first = false;
		}
	}
}
$vars['INSTALL_SQL'] = $sql;
$vars['FORM'] = $form;

// Output:
flushDir('output');

function copyFiles($from, $to, $vars)
{
	$templateFiles = glob($from);
	foreach($templateFiles as $file)
	{
		if(is_file($file))
		{
			$content  = file_get_contents($file);
			$filename = $to.'/'.basename($file);
			foreach($vars as $key => $value)
			{
				$content = str_replace('{{'.$key.'}}', $value, $content);
				$filename = str_replace('{{'.$key.'}}', $value, $filename);
				$to = str_replace('{{'.$key.'}}', $value, $to);
			}
			// Save:
			// echo $to.'<br />';
			if(!file_exists($to)) { mkdir($to); }
			file_put_contents($filename, $content);
		} elseif(is_dir($file)) {
			$toDir = $to.'/'.basename($file);
			foreach($vars as $key => $value)
			{
				$toDir = str_replace('{{'.$key.'}}', $value, $toDir);
			}
			if(!file_exists($toDir)) { mkdir($toDir); }
			copyFiles($file.'/*', $toDir, $vars);
		}
	}
}

// Copy Template files:
copyFiles('tpl/*', 'output', $vars);

$_SESSION['vars'] = $vars;
header('Location: index.php?ok');