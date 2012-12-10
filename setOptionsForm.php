<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/config.php';
$config = Config::get();

session_start();

if(empty($_SESSION['user'])) {
	header('Location: http://' . $_SERVER['HTTP_HOST'] . '/fhical/index.php');
	die();
}

$user = $_SESSION['user'];

$url = 'http://' . $_SERVER['HTTP_HOST'] . '/fhical/getIcal.php?user=' . 
		$user->getId() . '&key=' . urlencode(!empty($_SESSION['key']) ? $_SESSION['key'] : "PUT_YOUR_KEY_HERE");

/*
$error = array();
if(!empty($_SESSION['generateKeyFormError'])) {
	$error = $_SESSION['generateKeyFormError'];
	unset($_SESSION['generateKeyFormError']);
}
$data = array();
if(!empty($_SESSION['generateKeyFormData'])) {
	$data = $_SESSION['generateKeyFormData'];
	unset($_SESSION['generateKeyFormData']);
}*/
$currentPage = "options";
$title = "Kalender-Option auswählen";
include($_SERVER['DOCUMENT_ROOT'] . '/fhical/header.inc.php');
?>
<article class="hero clearfix">
	<div>
		<h2>Mit dieser URL kannst du deine FH-Kalender aufrufen</h2>
		<p class="success"><a href="<?php echo $url; ?>"><?php echo $url; ?></a></p>
	</div>
</article>
<article class="article clearfix">
	<div class="col_50">
		<form action="/fhical/generateOptions.php" method="POST">
			<div>
				<label for="stg_kz">
					Studiengang:<br />
					<select id="stg_kz" name="stg_kz">
						<?php foreach ($config['fhTwCourses'] as $value): ?>
							<option value="<?php echo $value['id'] ?>" 
								<?php echo (!empty($data['stg_kz']) && $data['stg_kz'] == $value['id']) ? 'selected="true"' : ''; ?>>
								<?php echo $value['name'] ?>
							</option>
						<?php endforeach; ?>
					</select>
				</label>
			</div>
			<hr>
			<div class="col_33">
				<p>
					<label for="sem">
						Semester (als Zahl)<br />
						<input id="sem" type="text" name="sem" <?php echo !empty($data['sem']) ? 'value="' . $data['sem'] . '"' : ''; ?>/>
					</label>
				</p>
			</div>
			<div class="clearfix"></div>
			<div class="col_33">
				<p>
					<label for="ver">
						Lehrverband (A, B, etc. - leer wenn nicht zutreffend)<br />
						<input id="ver" type="text" name="ver" <?php echo !empty($data['ver']) ? 'value="' . $data['ver'] . '"' : ''; ?>/>
					</label>
				</p>
			</div>
			<div class="col_33">
				<p>
					<label for="grp">
						Gruppe (1, 2, etc. - leer wenn nicht zutreffend)<br />
						<input id="grp" type="text" name="grp" <?php echo !empty($data['grp']) ? 'value="' . $data['grp'] . '"' : ''; ?>/>
					</label>
				</p>
			</div>
			<div class="clearfix"></div>
			<hr>
			<div class="col_33">
				<p>
					<label for="begin">
						Zeitraum Begin:<br />
						<input id="begin" class="datepicker" type="text" name="begin" <?php echo !empty($data['begin']) ? 'value="' . $data['begin'] . '"' : ''; ?>/>
					</label>
				</p>
			</div>
			<div class="col_33">
				<p>
					<label for="ende">
						Zeitraum Ende:<br />
						<input id="ende" type="text" class="datepicker" name="ende" <?php echo !empty($data['ende']) ? 'value="' . $data['ende'] . '"' : ''; ?>/>
					</label>
				</p>
			</div>
			<div class="clearfix"></div>
			<div><button type="submit" class="button">Speichern</button></div>
			<script>
		    $(function() {
		        $( ".datepicker" ).datepicker($.datepicker.regional[ "de" ]);
		    });
		    </script>
		</form>
	</div>
</article>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/fhical/footer.inc.php');