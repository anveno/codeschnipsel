<?php
if (!rex::isBackend()) {

	$yform = new rex_yform();
	$yform->setObjectparams('form_wrap_id', 'rex_website_bewerbungen');
	//$yform->setObjectparams('form_wrap_class', '');
	$yform->setObjectparams('form_name', 'bewerbungen');
	$yform->setObjectparams('form_class', 'uk-form-stacked');
	$yform->setObjectparams('form_action',rex_getUrl('REX_ARTICLE_ID'));
	$yform->setObjectparams('form_ytemplate', 'uikit');
	$yform->setObjectparams('form_showformafterupdate', 0);
	$yform->setObjectparams('real_field_names', true);
	$yform->setObjectparams('form_anchor','rex_website_bewerbungen');
	$yform->setObjectparams('debug',0);
	$yform->setObjectparams('error_class','error');

	// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	
	$yform->setValueField('datestamp', array('datestamp','Zeitstempel','d.m.Y H:i','','0'));
	$yform->setValueField('text', array('vorname','Vorname*','','',' {"required":"required","placeholder":"Vorname*"}'));
	$yform->setValueField('text', array('nachname','Nachname*','','','{"required":"required","placeholder":"Nachname*"}'));
	$yform->setValueField('email', array('email','E-Mail*','','','{"required":"required","placeholder":"E-Mail*"}'));
	$yform->setValueField('submit', array('submitbutton','Bewerbung absenden','','0','','uk-button uk-button-secondary fr-button-inverted '));

	// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	// validation
	$yform->setValidateField('empty', array("vorname","Bitte Vornamen angeben"));
	$yform->setValidateField('empty', array("nachname","Bitte Nachnamen angeben"));
	$yform->setValidateField('empty', array("email","Bitte E-Mail-Adresse angeben"));
	$yform->setValidateField('type', array("email", "email", "Bitte korrekte E-Mail-Adresse angeben"));
	$yform->setValidateField('compare_value', array("datenschutz","1","!=","Bitte Datenschutzerklärung akzeptieren"));

	// action - showtext
	$yform->setActionField('showtext', ['Vielen Dank', '<div class="alert">', '</div>', 1]);

	// action - db
	//$yform->setActionField('db', array('rex_website_bewerbungen'));

	// action - tpl2email
	$yform->setActionField('tpl2email', array('rex_bewerbung_intern', '', 'dev@beispiel.de', '', 'mail error')); 
	
	// form generation
	$form = $yform->getForm();
	
	// ValueFields in ein Array mit passenden Namen
	$myform = array();
	foreach ($yform->objparams['fields']['values'] as $k => $v) {
		$myform[$v->name] = $yform->objparams['form_output'][$k];
	}
	
	if ($yform->objparams['form_show'])
	{
		// aus ytemplate/form.tpl.php übernommen und angepasst:
	?>
	<div id="<?php echo $yform->objparams['form_wrap_id'] ?>" class="<?php echo $yform->objparams['form_wrap_class'] ?>">

	<?php
		if ($yform->objparams['form_action'] != '') {
			echo '<form action="'.$yform->objparams['form_action'].'" method="'.$yform->objparams['form_method'].'" id="'.$yform->objparams['form_name'].'" class="'.$yform->objparams['form_class'].'" enctype="multipart/form-data">';
		}
		if (!$yform->objparams['hide_top_warning_messages']) {
			if ($yform->objparams['warning_messages'] || $yform->objparams['unique_error']) {
				echo $yform->parse('errors.tpl.php');
			}
		}
		// ALT
		/*foreach ($yform->objparams['form_output'] as $field):
			echo $field;
		endforeach;*/
		// \ALT
		
		// NEU
		echo $myform['vorname'];
		?>		
			<section>
				<h1>irgendein HTML Gewurstel</h1>
			</section>
		<?php
		echo $myform['nachname'];
		echo $myform['email'];
		?>		
			<section>
				<h2>mehr HTML Gewurstel</h2>
			</section>
		<?php
		echo $myform['datenschutz'];
		echo $myform['submitbutton'];
		// \NEU

		echo $myform['_csrf_token'];

		 for ($i = 0; $i < $yform->objparams['fieldsets_opened']; ++$i):
			echo $yform->parse('value.fieldset.tpl.php', ['option' => 'close']);
		endfor;

		foreach ($yform->objparams['form_hiddenfields'] as $k => $v): ?>
			<?php if (is_array($v)): foreach ($v as $l => $w): ?>
				<input type="hidden" name="<?php echo $k, '[', $l, ']' ?>" value="<?php echo htmlspecialchars($w) ?>" />
			<?php endforeach; else: ?>
				<input type="hidden" name="<?php echo $k ?>" value="<?php echo htmlspecialchars($v) ?>" />
			<?php endif; ?>
		<?php endforeach ?>

		<?php
		if ($yform->objparams['form_action'] != '') {
			echo '</form>';
		}
		?>
	</div>
	<?php
	}
	else {
		// wenn Formular abgesendet und keine Fehlermeldung -> normal das Formular ausgeben (in diesem Fall "showtext"-Action) 
		echo $form;
	}
	?>
<?php
}
?>