<div id="phaser" class="wrap options">

	<div id="phaser-content-container">

		<div class="cbw-phaser-load-container">
			<?php include( plugin_dir_path(__FILE__) . 'phaser.svg' ); ?>
			<img class="cbw-phaser-selector cbw-phaser-loading" src="<?php echo plugins_url(); ?>/phaser/admin/templates/options/vintage-sci-fi-astronaut.jpg" />
		</div>

		<div id="phaser-content">

			<form action="options.php" method="post">

			<?php
			  settings_fields( 'phaser' );
			  do_settings_sections( 'phaser' );
			?>

			<h1>Phaser</h1>
			<p>Create SVGs and use them as placeholders for your images</p>

			<hr>
			
			<h4>Settings</h4>
			<table>
			     
				<tr>
					<td>
						<b>Input a Hexidecimal color string for SVG Fill Color</b><br>
						<i><b>Example:</b> a0d6b4</i>
					</td>
				</tr>
			    <tr>
			        <td><input type="text" placeholder="Hex Color" name="phaser_fill_hex" value="<?php echo esc_attr( get_option('phaser_fill_hex') ); ?>" size="50" /></td>
			    </tr>
				<tr>
					<td>
						<b>Input a Hexidecimal color string for SVG Stroke Color</b><br>
						<i><b>Example:</b> a0d6b4</i>
					</td>
				</tr>
			    <tr>
			        <td><input type="text" placeholder="Hex Color" name="phaser_stroke_hex" value="<?php echo esc_attr( get_option('phaser_stroke_hex') ); ?>" size="50" /></td>
			    </tr>

			    <tr>
			        <th>Enable SVG creation on Upload</th>
			    </tr>
			    <tr>
			        <td>
			            <label>
			                <input type="checkbox" name="phaser_create_svg_bool" <?php echo esc_attr( get_option('phaser_create_svg_bool') ) == 'on' ? 'checked="checked"' : ''; ?> />Yes</label><br/>
			        </td>
			    </tr>
			     <tr>
			        <th>Show SVGs as loader placeholders</th>
			    </tr>
			     <tr>
			        <td>
			            <label>
			                <input type="checkbox" name="phaser_show_svg_bool" <?php echo esc_attr( get_option('phaser_show_svg_bool') ) == 'on' ? 'checked="checked"' : ''; ?> />Yes</label><br/>
			        </td>
			    </tr>

			    <tr>
			        <td><?php submit_button(); ?></td>
			    </tr>

			</table>

			</form>

			<hr>

			<?php require_once plugin_dir_path( dirname( __FILE__ ) ) . '/functions/regenerate.php'; ?>

		</div>

	</div>	

</div>