<form id="contribute-form" action="<?php echo get_the_permalink(); ?>" method="post">
	<fieldset>	
		<p>
			Liebes Graberner GeschichteN-Team, mein <label for="contribute-name">Name</label> ist <input type="text" name="contribute_name" id="contribute-name" value="<?php if ( isset( $name ) ) echo $name; ?>" placeholder="Ihr Vorname und Nachname" />.
			Ich möchte gerne beim Projekt Graberner GeschichteN mithelfen und kann folgende Materialien zur Verfügung stellen:
		</p>

		<ul>
			<li>
				<input type="checkbox" name="contribute_material[]" id="contribute-material-1" value="Ich habe alte Fotos auf Papier, ungeordnet." class="check" />
				<label for="contribute-material-1" class="check-lbl">Ich habe alte Fotos auf Papier, ungeordnet.</label>
			</li>
			<li>
				<input type="checkbox" name="contribute_material[]" id="contribute-material-2" value="Ich habe alte Fotos auf Papier, geordnet und beschriftet." class="check" />
				<label for="contribute-material-2" class="check-lbl">Ich habe alte Fotos auf Papier, geordnet und beschriftet.</label>
			</li>
			<li>
				<input type="checkbox" name="contribute_material[]" id="contribute-material-3" value="Ich habe alte Fotos eingescannt (digital), ungeordnet." class="check" />
				<label for="contribute-material-3" class="check-lbl">Ich habe alte Fotos eingescannt (digital), ungeordnet.</label>
			</li>
			<li>
				<input type="checkbox" name="contribute_material[]" id="contribute-material-4" value="Ich habe alte Fotos eingescannt (digital), geordnet und beschriftet." class="check" />
				<label for="contribute-material-4" class="check-lbl">Ich habe alte Fotos eingescannt (digital), geordnet und beschriftet.</label>
			</li>
			<li>
				<input type="checkbox" name="contribute_material[]" id="contribute-material-5" value="Ich habe altes Filmmaterial." class="check" />
				<label for="contribute-material-5" class="check-lbl">Ich habe altes Filmmaterial.</label>
			</li>
			<li>
				<input type="checkbox" name="contribute_material[]" id="contribute-material-6" value="Ich habe altes Filmmaterial digitalisiert." class="check" />
				<label for="contribute-material-6" class="check-lbl">Ich habe altes Filmmaterial digitalisiert.</label>
			</li>
			<li>
				<input type="checkbox" name="contribute_material[]" id="contribute-material-7" value="Ich habe alte schriftliche Dokumente, handschriftliche Briefe." class="check" />
				<label for="contribute-material-7" class="check-lbl">Ich habe alte schriftliche Dokumente, handschriftliche Briefe.</label>
			</li>
			<li>
				<input type="checkbox" name="contribute_material[]" id="contribute-material-8" value="Ich habe alte Tonaufnahmen." class="check" />
				<label for="contribute-material-8" class="check-lbl">Ich habe alte Tonaufnahmen.</label>
			</li>
			<li>
				<input type="checkbox" name="contribute_material[]" id="contribute-material-9" value="Ich habe alte Tonaufnahmen, digitalisiert." class="check" />
				<label for="contribute-material-9" class="check-lbl">Ich habe alte Tonaufnahmen, digitalisiert.</label>
			</li>
			<li>
				<input type="checkbox" name="contribute_material[]" id="contribute-material-10" value="Ich möchte sonstige Inhalte beitragen." class="check"  />
				<label for="contribute-material-10" class="check-lbl">Ich möchte sonstige Inhalte beitragen.</label>
			</li>
		</ul>

		<p>
			<label for="contribute-info">Was ich noch dazu sagen will:</label>
		</p>

		<div>
				<textarea name="contribute_info" id="contribute-info" rows="5" cols="70"><?php if ( isset( $info ) ) echo $info; ?></textarea>
		</div>

		<p>
			Ihr könnt mich unter folgender
			<label for="contribute-mail">E-Mail-Adresse</label>
			<input type="text" name="contribute_mail" id="contribute-mail" value="<?php if ( isset( $mail ) ) echo $mail; ?>" placeholder="Ihre E-Mail-Adresse" />
			oder dieser <label for="contribute-phone">Telefonnummer</label>
			<input type="text" name="contribute_phone" id="contribute-phone" value="<?php if ( isset( $phone ) ) echo $phone; ?>" placeholder="Ihre Telefonnummer" />
			erreichen.
		</p>

		<p>
			Ich freue mich auf eure Antwort. Schöne Grüße.
		</p>

		<div>
			<input type="submit" name="contribute_submit" id="contribute-submit" value="Nachricht senden" class="btn" />
		</div>
	</fieldset>
</form>