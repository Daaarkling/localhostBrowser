<?php
require_once __DIR__ . '/vendor/autoload.php';

use Nette\Utils\Finder;

//----------------------------------------------------------

/**
 * Thank you Nette Framework
 *
 * Converts to human readable file size.
 * @param  int
 * @param  int
 * @return string
 */
function bytes($bytes, $precision = 2)
{
	$bytes = round($bytes);
	$units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
	foreach ($units as $unit) {
		if (abs($bytes) < 1024 || $unit === end($units)) {
			break;
		}
		$bytes = $bytes / 1024;
	}
	return round($bytes, $precision) . ' ' . $unit;
}

//----------------------------------------------------------

$basePath = substr(str_replace('\\', '/', realpath(dirname(__FILE__))), strlen(str_replace('\\', '/', realpath(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')))));

isset($searchPath) ?: $searchPath = '../';
$search = (string) filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);

if ($search) {
	$search .= '*';
}

$files = Finder::find($search)->in($searchPath);

ob_start();
?>
<table id="result" class="table table-hover">
	<thead>
		<tr>
			<th class="col-sm-8">Name</th>
			<th>Last modified</th>
			<th>Size</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($files as $file) { ?>
			<tr>
				<td>					
					<img src="<?php echo $basePath; ?>/assets/<?php echo $file->isDir() ? 'folder' : 'file'; ?>.png" alt="<?php echo $file->isDir() ? 'folder' : 'file'; ?>">&nbsp;&nbsp;
					<a href="<?php echo $searchPath . $file->getBasename() ?>">
						<?php echo $file->getBasename(); ?>
					</a>
				</td>					
				<td><?php echo date('Y-m-d H:i:s', $file->getMTime()); ?></td>
				<td><?php echo $file->isDir() ? '---' : bytes($file->getSize()); ?></td>
			</tr>
		<?php } ?>
	<tbody>
</table>
<?php
$result = ob_get_clean();

// is AJAX?
if (strtolower(filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest') {
	echo $result;
	exit(0);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
		<meta name="author" content="Jan Vaňura (Darkling)">
		<link rel="stylesheet" href="<?php echo $basePath; ?>/assets/bootstrap.min.css">
        <title>File Server Browser</title>
    </head>
    <body>

		<div class="container">

			<h1>Index of /</h1>
			<br>

			<form action="." method="get" enctype="multipart/form-data">
				<div class="form-group">
					<input id="search" class="form-control input-lg" value="<?php echo $search ?>" type="search" name="search" placeholder="Type to search" autofocus="1">
				</div>
			</form>
			
			<div class="table-responsive">
				<?php echo $result?>
			</div>
			
		</div>
		<script src="<?php echo $basePath; ?>/assets/jquery-1.11.3.min.js"></script>
		<script src="<?php echo $basePath; ?>/assets/live.search.js"></script>
    </body>
</html>
