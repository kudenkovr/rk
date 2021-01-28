<!DOCTYPE html>

<html lang="<?=($lang)?$lang:'ru'?>">
<head>
	<meta charset="<?=($charset)?$charset:'utf-8'?>">
	<title><?=$title?></title>
<?foreach ($styles as $style):?>
	<link rel="stylesheet" href="<?=$style?>">
<?endforeach;?>
<?=$head?>
</head>
<body>
<?=$content?>
<?foreach ($scripts as $script):?>
	<script src="<?=$script?>"></script>
<?endforeach;?>
</body>
</html>