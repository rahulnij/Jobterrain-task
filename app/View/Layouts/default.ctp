<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$siteDescription = 'Jobterrain Task';
//$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Article">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $siteDescription ?>:
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
        echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('cake.generic');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

</head>
<body>
	<div id="container">
		<div id="header">
			<h1><?php echo $this->Html->link($siteDescription, array('controller'=>'users','action'=>'login')); ?></h1>
             <?php 
            
            if ($login == true) {
                
                echo $this->Html->link('<b>Welcome '.$currentUser['first_name']. '</b> | <i class="fa fa-power-off"></i> Logout', array('controller'=>'users','action'=>'logout'), array('class' => 'lgot', 'escape' => false));
            }
        ?>
		</div>
        
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			
			
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
