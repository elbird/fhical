<?php
include($_SERVER['DOCUMENT_ROOT'] . '/fhical/inc/global.inc.php');

$url = 'http://' . $_SERVER['HTTP_HOST'] . '/fhical/getIcal.php?user=' .
		$user->getId() . '&key=' . urlencode(!empty($_SESSION['key']) ? $_SESSION['key'] : "PUT_YOUR_KEY_HERE");

$options = $user->getOptions();
if(empty($options)) {
	$options = array();
}

$error = array();
if(!empty($_SESSION['setOptionsFormError'])) {
	$error = $_SESSION['setOptionsFormError'];
	unset($_SESSION['setOptionsFormError']);
}

$currentPage = "options";
$title = "Kalender-Option auswählen";
include($_SERVER['DOCUMENT_ROOT'] . '/fhical/inc/header.inc.php');
?>
<article class="hero clearfix">
	<div>
		<h2>Mit dieser URL kannst du deinen FH-Kalender aufrufen</h2>
		<p class="success"><a href="<?php echo $url; ?>"><?php echo $url; ?></a></p>
		<?php if(empty($options)): ?>
		<p class="message">Du hast noch keine Optionen angegeben bitte fülle das Formular unten aus</p>
		<?php endif;?>
	</div>
</article>
<article class="article clearfix">
	<div class="col_50">
		<form action="/fhical/icalapp/saveOptions.php" method="POST">
			<div>
				<?php if (!empty($error['stg_kz'])): ?>
					<p class="warning">Bitte gib einen Studiengang an</p>
				<?php endif; ?>
				<p>
					<label for="stg_kz">
						Studiengang:<br />
						<select id="stg_kz" name="stg_kz">
							<?php foreach ($config['fhTwCourses'] as $value): ?>
								<option value="<?php echo $value['id'] ?>" 
									<?php echo (!empty($options['stg_kz']) && $options['stg_kz'] == $value['id']) ? 'selected="true"' : ''; ?>>
									<?php echo $value['name'] ?>
								</option>
							<?php endforeach; ?>
						</select>
					</label>
				</p>
			</div>
			<hr>
			<div class="col_33">
				<?php if (!empty($error['sem'])): ?>
					<p class="warning">Bitte gib ein Semester an</p>
				<?php endif; ?>
				<p>
					<label for="sem">
						Semester (als Zahl)<br />
						<input id="sem" type="text" name="sem" <?php echo !empty($options['sem']) ? 'value="' . $options['sem'] . '"' : ''; ?>/>
					</label>
				</p>
			</div>
			<div class="clearfix"></div>
			<div class="col_33">
				<?php if (!empty($error['ver'])): ?>
					<p class="warning">Bitte gib einen Verband an</p>
				<?php endif; ?>
				<p>
					<label for="ver">
						Lehrverband (A, B, etc. - leer wenn nicht zutreffend)<br />
						<input id="ver" type="text" name="ver" <?php echo !empty($options['ver']) ? 'value="' . $options['ver'] . '"' : ''; ?>/>
					</label>
				</p>
			</div>
			<div class="col_33">
				<?php if (!empty($error['grp'])): ?>
					<p class="warning">Bitte gib deine Gruppe an</p>
				<?php endif; ?>
				<p>
					<label for="grp">
						Gruppe (1, 2, etc. - leer wenn nicht zutreffend)<br />
						<input id="grp" type="text" name="grp" <?php echo !empty($options['grp']) ? 'value="' . $options['grp'] . '"' : ''; ?>/>
					</label>
				</p>
			</div>
			<div class="clearfix"></div>
			<hr>
			<div class="col_33">
				<?php 
				$begin = "";
				if(!empty($options["begin"])) {
					$begin =  DateTime::createFromFormat('U', $options["begin"]);
					if($begin) {
						$begin =  'value="' . $begin->format('j.m.Y') . '" ';
					} else {
						$begin = "";
					}
				}
				?>
				<?php if (!empty($error['begin'])): ?>
					<p class="warning">Bitte gib das Datum im Format TT.MM.JJJJ (z.B: 01.01.2012) an</p>
				<?php endif; ?>
				<p>
					<label for="begin">
						Zeitraum Begin:<br />
						<input id="begin" class="datepicker" type="text" name="begin" <?php echo $begin; ?>/>
					</label>
				</p>
			</div>
			<div class="col_33">
				<?php 
				$ende = "";
				if(!empty($options["ende"])) {
					$ende =  DateTime::createFromFormat('U', $options["ende"]);
					if($ende) {
						$ende =  'value="' . $ende->format('j.m.Y') . '" ';
					} else {
						$ende = "";
					}
				}
				?>
				<?php if (!empty($error['ende'])): ?>
					<p class="warning">Bitte gib das Datum im Format TT.MM.JJJJ (z.B: 01.01.2012) an</p>
				<?php endif; ?>
				<p>
					<label for="ende">
						Zeitraum Ende:<br />
						<input id="ende" type="text" class="datepicker" name="ende" <?php echo $ende; ?>/>
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
include($_SERVER['DOCUMENT_ROOT'] . '/fhical/inc/footer.inc.php');